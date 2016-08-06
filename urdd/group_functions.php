<?php
/*
 *  This file is part of Urd.
 *  vim:ts=4:expandtab:cindent
 *
 *  Urd is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  Urd is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. See the file "COPYING". If it does not
 *  exist, see <http://www.gnu.org/licenses/>.
 *
 * $LastChangedDate: 2013-09-29 00:21:06 +0200 (zo, 29 sep 2013) $
 * $Rev: 2927 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: db.class.php 2927 2013-09-28 22:21:06Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

/* Table interface: */
class TableSetData
{
    public $setID;
    public $groupID;
    public $subject;
    public $articlesmax;
    public $binaries;
    public $date;
    public $size;
}

class TableBinaries
{
    public $binaryID;
    public $subject;
    public $date;
    public $bytes;
    public $totalparts;
    public $setID;
}

class TableParts
{
    public $binaryID;
    public $messageID;
    public $subject;
    public $fromname;
    public $date;
    public $partnumber;
    public $size;
}

class TableGroups
{
    /* Todo */
}

class urdd_group 
{
    const MAX_INSERT_PARTS = 200;
    const GENSETS_STEPSIZE = 50;
    
    const DIRTY = 1;
    const CLEAN = 0;

    // Dirty flags:
    const CONSISTENT =    0;
    const BINARYCHANGED = 1;
    const SETCHANGED =    2;
    private $db;

    public function __construct(DatabaseConnection $db)
    {
        $this->db = $db;
    }

    public function add_parts(array $tables, $groupID)
    {
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        assert(is_numeric($groupID));

        // All group ID's should be identical in a batch.
        if (count($tables) == 0) {
            return;
        }

        // $tables is array of ['binaryID', 'messageID', 'subject', 'fromname', 'date', 'partnumber', 'size'] per part
        // Store these in the database and also mark the relevant binaries as dirty

        // Determine the affected binaries:
        $binaries = [];
        foreach ($tables as $table) {
            $binaries[$table->binaryID] = 1;
        }

        // Save parts:
        $x = 1;
        static $cols = ['binaryID', 'messageID', 'subject', 'fromname', 'date', 'partnumber', 'size'];
        $vals = [];

        try {
            $this->db->start_transaction();
            foreach ($tables as $table) {
                if ($x % self::MAX_INSERT_PARTS == 0) {
                    $this->db->insert_query("parts_$groupID", $cols, $vals);
                    $vals = [];
                }
                $vals[] = [$table->binaryID, $table->messageID, $table->subject, $table->fromname, $table->date, $table->partnumber, $table->size];
                $x++;
            }
            // Insert the remaining values (if any):
            if (count($vals) > 0) {
                $this->db->insert_query("parts_$groupID", $cols, $vals);
                unset($vals);
            }
            $this->db->commit_transaction();
        } catch (exception $e) {
            $this->db->commit_transaction();
            throw $e;
        }
        unset($table);
        // Update the binaries
        $x = (int) 1;
        $binarieslist = [];
        try {
            foreach ($binaries as $binary => $dummy) {

                // In case it is a new binary, create it. Let it fail if it wants (because it exists),
                // we will do an update later on anyways:
                try {
                    $this->db->insert_query("binaries_$groupID", array('binaryID'), array($binary));
                } catch (exception $e) { }

                if ($x % self::MAX_INSERT_PARTS == 0) {
                    // Remove the last , before running the query:
                    $this->db->update_query_2("binaries_$groupID ", array('dirty' => self::DIRTY), '"binaryID" IN ( ' . 
                        str_repeat('?,', count($binarieslist) - 1) . '? )', $binarieslist);
                    $binarieslist = [];
                    $x = (int) 1;
                }
                $binarieslist[] = $binary;
                $x++;
            }

            // Insert the remaining values (if any):
            if (count($binarieslist) > 0) {
                $this->db->update_query_2("binaries_$groupID ", array('dirty'=> self::DIRTY), '"binaryID" IN ( ' . str_repeat('?,', count($binarieslist) - 1) . '? )', $binarieslist);
            }
        } catch (exception $e) {
            throw $e;
        }
    }

    private function update_binary_data($groupID, $id)
    {
        assert (is_numeric($groupID));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);

        // Get the group name for use in log messages:
        $groupname = group_name($this->db, $groupID);

        // Steps: 1) Select a bunch of dirty binaries
        //        2) For these binaries, get the parts data
        //        3) Save the new information for the binary
        //        4) Rinse & Repeat

        // Get the total number of binaries that are going to be updated:
        $this->db->escape($groupID, FALSE);
        $sql = "count(*) AS total FROM (SELECT DISTINCT \"binaryID\" FROM binaries_$groupID WHERE \"dirty\" = :dirty) AS t";
        $res = $this->db->select_query($sql, array(':dirty'=>self::DIRTY));
        if (!isset($res[0]['total'])) {
            write_log('No binaries found', LOG_NOTICE);

            return;
        }
        $total = $res[0]['total'];

        // Update queue:
        update_queue_status($this->db, $id, NULL, NULL, 1, NULL, NULL);
        write_log("Total updated binaries in $groupname is $total", LOG_NOTICE);

        $stepsize = self::GENSETS_STEPSIZE; // Number of binaries that are updated per batch
        $cnt = 0;   // Number of binaries that are done
        // Columns in the binaries table:
        static $cols = array('binaryID', 'subject', 'date', 'bytes', 'totalParts', 'setID', 'dirty');
        static $cols_nfo = array('setID', 'groupID', 'binaryID');
        $sql_get_arts = "DISTINCT \"binaryID\" FROM binaries_$groupID WHERE \"dirty\" = :dirty";

        // Going through all dirty binaries for this group
        $s_time = microtime(TRUE);
        while (1) {  // Loop through every chunk in turn to benefit caching -> no tmp tables on disk
            // Step 1: Selecting:
            // PS: No need to keep track of how many we select, next run these will not have the dirty flag anymore
            //     so we just keep selecting dirty binaries until they are all gone
            $res = $this->db->select_query($sql_get_arts, $stepsize, array(':dirty' => self::BINARYCHANGED));
            if (!is_array($res)) {
                echo_debug("Processed $cnt binaries.", DEBUG_SERVER);

                return;
            }
            $cnt += count($res);

            // $l is going to be the list of the binaryID's that we have just gotten back
            $l = [];
            foreach ($res as $row) {
                $l[] = $row['binaryID'];
            }
            $binary_list = str_repeat('?,', count($l) - 1) . '?';
            // Delete all the binaries, then recreate (because inserting in batches is faster? and easier than updating binaries 1 by 1
            $this->db->delete_query("binaries_$groupID", "\"binaryID\" IN ( $binary_list )", $l);

            // Step 2: Updating:
            // Max for subject and fromname are needed to ensure we always get the same result, not a random one
            $sql = 'SUM("size") AS totalsize, "binaryID", COUNT(*) AS parttotal, MAX("subject") AS subject, MAX("fromname") AS fromname, MIN("date") AS mindate '
                . " FROM parts_$groupID WHERE \"binaryID\" IN ($binary_list) GROUP BY \"binaryID\"";

            $res = $this->db->select_query($sql, $l);
            unset($l);
            $vals = $vals_nfo = [];
            // For each row, export to binaries_X
            if (!is_array($res)) {
                $res = [];
            }
            foreach ($res as $row) {
                // Determine SetID:
                $subject = $row['subject'];
                $poster = $row['fromname'];
                $size = $row['totalsize'];

                //$is_par_file = (preg_match('/vol\d+\+\d+.par2/i', $subject) > 0);
                $is_nfo_file = (stripos($subject, '.nfo') !== FALSE && $size < (50 * 1024)); // 50K seems large for a .nfo file...
                $cntFull = get_set_size($subject);

                // First 3 characters of the poster are also used:
                $poster = preg_replace('/([^a-z]+)/i', '', substr($poster, 0, 10));
                $poster = strtolower(substr($poster, 0, 3));
                $dlname = clean_dlname_for_setid($subject);

                // Hash it, so we can shorten the length of $dlname without possibly missing the unique part
                // (Think "#Alt.Binaries.Movies.XviD On EFNet Presents: Rambo.V.REAL.PROPER.R5.xVID-UNiVERSAL",
                // if we take the first 20 characters, a lot of uploads will be merged to 1 set)
                // Also: Can't use groupID as it's newsserver specific, need to use the group name.
                //       (We do need a group identifier or x-posts will be merged)

                // This is it:
                $setID = md5($dlname . $groupname . $poster . $cntFull);

                $vals[] = [$row['binaryID'], $row['subject'], $row['mindate'], $row['totalsize'], $row['parttotal'], $setID, self::SETCHANGED];
                if ($is_nfo_file) {
                    $vals_nfo[] = [$setID, $groupID, $row['binaryID']];
                }

                // Step 3: Saving new information:
                // Batch insert
                if ((count($vals) % self::MAX_INSERT_PARTS) == 0) {

                    // Not INSERT DELAYED because then generating sets will fail; as the binaries aren't there yet
                    $this->db->insert_query('binaries_' . $groupID, $cols, $vals, FALSE);
                    $vals = [];
                    if (count($vals_nfo) > 0) {
                        $this->db->insert_query('nfo_files', $cols_nfo, $vals_nfo, FALSE);
                        $vals_nfo = [];
                    }
                }
            }

            // If batch was not complete, make sure we do the remaining ones now:
            if (count($vals) > 0) {
                $this->db->insert_query('binaries_' . $groupID, $cols, $vals, FALSE);
            }
            if (count($vals_nfo) > 0) {
                $this->db->insert_query('nfo_files', $cols_nfo, $vals_nfo, FALSE);
            }

            $t_time = microtime(TRUE);
            // The ETA / percentage calculation:
            if ($cnt > 0 && $total > 0) {
                $ETA = floor((($total - $cnt) * ($t_time - $s_time) / $cnt) / .75);
                $progress = floor((75 * $cnt) / $total);
                update_queue_status($this->db, $id, NULL, $ETA, $progress);
            }
        }
    }

    private function update_set_data($groupID, $id, $minsetsize, $maxsetsize)
    {
        assert(is_numeric($groupID) && assert(is_numeric($minsetsize)) && assert(is_numeric($minsetsize)));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        $this->db->escape($groupID, FALSE);

        $stepsize = self::GENSETS_STEPSIZE;
        $offset = $cnt = 0;
        $sql = "count(*) AS total FROM (SELECT DISTINCT \"setID\" FROM binaries_$groupID WHERE \"dirty\" = :dirty) AS tmp";
        $res = $this->db->select_query($sql, [':dirty'=>self::SETCHANGED]);
        if (!isset($res[0]['total'])) {
            write_log('No binaries found', LOG_NOTICE);

            return;
        }
        $total = $res[0]['total'];
        $s_time = microtime(TRUE);
        $sql_select_bins = "DISTINCT \"setID\" FROM binaries_$groupID WHERE \"dirty\" = :dirty";
        while (1) {
            $res = $this->db->select_query($sql_select_bins, $stepsize, [':dirty' => self::SETCHANGED]);
            if (!is_array($res) && $offset == 0) {
                write_log("No new binaries found for group with id $groupID!", LOG_NOTICE);

                return;
            } elseif (!is_array($res)) {
                echo_debug("Done. Processed $cnt sets. Total was $total", DEBUG_SERVER);
                break;
            }
            $cnt += count($res);

            $l = [];
            foreach ($res as $row) {
                $l[] = $row['setID'];
            }
            $binary_str = str_repeat('?,', count($l) -1) . '?';
            $this->db->delete_query('setdata', "\"ID\" IN ($binary_str) AND \"groupID\" = ?", array_merge($l, [$groupID]));
            $sql = '"setID", count("binaryID") AS bins, MIN("subject") AS subject, MIN("date") AS date, SUM("bytes") AS totalsize ' .
                "FROM \"binaries_$groupID\" WHERE \"setID\" IN ($binary_str) GROUP BY \"setID\"";
            $res = $this->db->select_query($sql, $l);
            $offset += $stepsize;
            $set_list = [];
            // For each row, export to binaries_X
            foreach ($res as $arr) {
                $size = $arr['totalsize'];
                if ($size < $minsetsize) {
                    echo_debug("Discarding set: too small - probably spam ($size < $minsetsize)", DEBUG_SERVER);
                    continue;
                } elseif ($maxsetsize != 0 && $size > $maxsetsize) {
                    echo_debug("Discarding set: too large ($size > $maxsetsize)", DEBUG_SERVER);
                    continue;
                }

                $set_array = new TableSetData;
                $set_array->setID = $arr['setID'];
                $set_array->groupID = $groupID;
                $set_array->subject = $arr['subject'];  // 1st hit determines the subject, correct.
                // If the subject name is "Some Random Movie [1/104]", then:
                $set_array->articlesmax = get_set_size($arr['subject']);   // This is '104'
                $set_array->binaries = $arr['bins'];           // And this is the number of files belonging to the set. (hopefully 104).
                $set_array->size = $size;
                $set_array->date = $arr['date'];        // 1st hit also determines the set date.
                $set_list[] = $set_array;
            }
            $this->add_sets($set_list);
            $this->db->update_query_2("binaries_$groupID", array('dirty' => self::CONSISTENT), "\"setID\" IN ($binary_str)", $l);
            $t_time = microtime(TRUE);
            $ETA = floor((($total - $cnt) * ($t_time - $s_time) / $cnt));
            update_queue_status($this->db, $id, NULL, $ETA, floor(85 + ((15 * $cnt) / $total)));
        }
    }

    private function add_set_data($groupID, $setID_filter)
    {
        assert (is_numeric($groupID));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        $stepsize = self::GENSETS_STEPSIZE;
        // we only update one set

        // First delete everything for this group or only the specific set:
        $this->db->delete_query('setdata', '"groupID"=? AND "ID"=?', [$groupID, $setID_filter]);
        // Now re-create it.
        $this->db->escape($groupID, FALSE);
        $sql = '"setID", count("binaryID") AS bins, MIN("subject") AS subject, MIN("date") AS date, SUM("bytes") AS totalsize ' .
            "FROM \"binaries_$groupID\" WHERE binaries_$groupID.\"setID\"=:setid GROUP BY \"setID\"";

        $res1 = $this->db->select_query($sql, array(':setid'=>$setID_filter));
        $set_list = [];
        // To minimise memory requirements, we update setdata per set, instead of all at the end:
        if (is_array($res1)) {
            foreach ($res1 as $arr) {
                $set_array = new TableSetData;
                $set_array->setID = $arr['setID'];
                $set_array->groupID = $groupID;
                $name = preg_replace('/(\byEnc\b)|(\breq:)|(\bwww\.[\w\.]*\b)|(\bhttp:\/\/[\w\.]*\b)|([=><#])/i', '', $arr['subject']);
                $set_array->subject = $name;  // 1st hit determines the subject, correct.
                // If the subject name is "Some Random Movie [1/104]", then:
                $set_array->articlesmax = get_set_size($arr['subject']);   // This is '104'
                $set_array->binaries = $arr['bins'];           // And this is the number of files belonging to the set. (hopefully 104).
                $set_array->size = $arr['totalsize'];
                $set_array->date = $arr['date'];        // 1st hit also determines the set date.
                $set_list[] = $set_array;
                if (count($set_list) >= $stepsize) {
                    $this->add_sets($set_list);
                    $set_list = [];
                }
            }
            if (count($set_list) > 0) {
                $this->add_sets($set_list);
            }
        }

    }
    private function add_sets(array $sets)
    {
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        static $cols = ['ID', 'groupID', 'subject', 'articlesmax', 'binaries', 'date', 'size'];
        $vals = [];
        foreach ($sets as $set) {
            $vals[] = [$set->setID, $set->groupID, $set->subject, $set->articlesmax, $set->binaries, $set->date, $set->size];
        }
        if (count($vals) > 0) {
            $this->db->insert_query('setdata', $cols, $vals);
        }
    }

    public function expire_binaries($groupID, $dbid)
    {
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        assert(is_numeric($groupID) && is_numeric($dbid));
        $type = USERSETTYPE_GROUP;

        // Expire : from days to seconds
        $expire = group_expire($this->db, $groupID);
        $expire *= 24 * 3600;
        // convert to epochtime:
        $do_expire_incomplete = $expire_incomplete = get_config($this->db, 'expire_incomplete');
        $expire_percentage = get_config($this->db, 'expire_percentage');
        $input_arr = [$expire];
        $ki_pref = get_config($this->db, 'keep_interesting');
        $keep_int = '';
        $time = time();
        $expire = $time - $expire;
        if ($ki_pref) {
            $keep_int = ' AND "setID" NOT IN (SELECT "setID" FROM usersetinfo WHERE "type"=? AND "statusint"=?) ';
            $input_arr = array_merge($input_arr, [$type, sets_marking::MARKING_ON]);
        }

        echo_debug('Deleting expired posts', DEBUG_DATABASE);
        $sql = "count(*) AS cnt FROM binaries_$groupID WHERE \"date\" < ? $keep_int";
        $res = $this->db->select_query($sql, $input_arr);
        $cnt = 0;
        if (isset($res[0]['cnt'])) {
            $cnt = $res[0]['cnt'];
        }
        write_log('Deleting '. $cnt . ' binaries');
        update_queue_status ($this->db, $dbid, NULL, 0, 1);
        $GREATEST = $this->db->get_greatest_function();

        $keep_int = '';
        if ($ki_pref) {
            $keep_int = ' AND "ID" NOT IN (SELECT "setID" FROM usersetinfo WHERE "type"=? AND "statusint"=?) ';
        }
        // first clean all the sets we want to remove
        $Qcomplete = '';
        if ($do_expire_incomplete != 0 && $expire_percentage > 0 && $expire_percentage < 100) {
            $expire_incomplete *= 24 * 3600;
            $expire_incomplete = $time - $expire_incomplete;
            $Qcomplete = "OR (\"articlesmax\" != 0 AND floor((\"binaries\" * 100) / $GREATEST(1, \"articlesmax\")) < '$expire_percentage' AND \"date\" < '$expire_incomplete' )";
        }
        $res = $this->db->delete_query('setdata', "\"groupID\" = ? AND (\"date\" <= ? $Qcomplete) $keep_int", array_merge([$groupID], $input_arr));
        update_queue_status($this->db, $dbid, NULL, 0, 30);

        $res = $this->db->delete_query('usersetinfo', '"setID" NOT IN (SELECT "ID" FROM setdata) AND "type" = ?', [$type]);

        update_queue_status($this->db, $dbid, NULL, 0, 40);
        // note that this will also remove data about sets that hasn't been received yet, but typically, expire runs after an update, so all data should be in.
        $res = $this->db->delete_query('extsetdata', '"setID" NOT IN (SELECT "ID" FROM setdata) AND "type" = ?', [$type]);

        update_queue_status($this->db, $dbid, NULL, 0, 50);
        // see above
        $res = $this->db->delete_query('merged_sets', '"new_setid" NOT IN (SELECT "ID" FROM setdata) AND "type" = ?', [$type]);

        update_queue_status($this->db, $dbid, NULL, 0, 60);

        $keep_int = '';
        $input_arr = [$groupID, $expire];
        if ($ki_pref) {
            $keep_int = ' AND "setID" NOT IN (SELECT "setID" FROM usersetinfo WHERE "type" = ? AND "statusint" = ?) ';
            $input_arr = array_merge($input_arr, [$type, sets_marking::MARKING_ON]);
        }

        $res = $this->db->delete_query("binaries_$groupID", '"setID" NOT IN (SELECT "ID" FROM setdata WHERE "groupID" = ?) OR "date" <= ? ' . $keep_int, $input_arr);

        update_queue_status($this->db, $dbid, NULL, 0, 70);

        $keep_int = '';
        $input_arr = [$expire];
        if ($ki_pref) {
            $keep_int = " AND \"binaryID\" NOT IN (SELECT \"binaryID\" FROM usersetinfo JOIN binaries_$groupID AS bin ON bin.\"setID\" = usersetinfo.\"setID\" "
                . ' WHERE "type" = ? AND "statusint" = ?) ';
            $input_arr = array_merge($input_arr, [$type, sets_marking::MARKING_ON]);
        }
        $res = $this->db->delete_query("parts_$groupID", "\"binaryID\" NOT IN (SELECT \"binaryID\" FROM binaries_$groupID) OR \"date\" <= ? $keep_int", $input_arr);

        echo_debug("Deleted {$cnt} binaries", DEBUG_DATABASE);
        update_queue_status($this->db, $dbid, NULL, 0, 95);

        $this->update_postcount($groupID);
        update_queue_status($this->db, $dbid, NULL, 0, 100);

        return $cnt;
    }

    public function update_postcount($groupid)
    {
        $sql = "UPDATE groups SET \"postcount\" = (SELECT COUNT(*) FROM parts_{$groupid}), \"extset_update\" = :upd WHERE \"ID\" = :id";
        $this->db->execute_query($sql, [':upd'=>'0', ':id'=>$groupid]);
    }

    public function purge_binaries($groupID)
    {
        assert(is_numeric($groupID));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        $active = group_subscribed($this->db, $groupID);

        echo_debug('Deleting all posts', DEBUG_DATABASE);

        $res = $this->db->delete_query('usersetinfo', '"setID" IN (SELECT "ID" FROM setdata WHERE "groupID"=?) AND "type"=?', [$groupID, USERSETTYPE_GROUP]);
        $res = $this->db->delete_query('extsetdata', '"setID" IN (SELECT "ID" FROM setdata WHERE "groupID"=?) AND "type"=?', [$groupID, USERSETTYPE_GROUP]);
        $res = $this->db->delete_query('merged_sets', '"new_setid" IN (SELECT "ID" FROM setdata WHERE "groupID"=?) AND "type"=?', [$groupID, USERSETTYPE_GROUP]);
        $res = $this->db->delete_query('setdata', '"groupID"=?', [$groupID]);
        if ($active === TRUE) {
            $this->db->truncate_table("parts_$groupID");
            $this->db->truncate_table("binaries_$groupID");
        }
        $res = $this->db->update_query_2('groups', ['last_record'=>0, 'first_record'=>0, 'mid_record'=>0, 'last_updated'=>0, 'postcount'=>0, 'setcount'=>0], '"ID"=?', [$groupID]);
        echo_debug('Purged all binaries', DEBUG_DATABASE);
    }

    public function subscribe($groupid, $expire, $minsetsize=0, $maxsetsize=0)
    {
        assert(is_numeric($groupid) && is_numeric($expire) && is_numeric($minsetsize) && is_numeric($maxsetsize));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        $is_subscribed = group_subscribed($this->db, $groupid);
        if ($is_subscribed !== FALSE) {
            throw new exception('Already subscribed', DB_FAILURE);
        }
        try { // rewrite to urd_db class stuff
            $db_update = urd_db_structure::create_db_updater($this->db->get_databasetype(), $this->db);
            $urd_db = new urd_database($this->db->get_databaseengine());
            $part_table = "parts_$groupid";
            $bin_table = "binaries_$groupid";
            $t = new urd_table($part_table, 'ID', 'utf8');
            $t->add_column(new urd_column('ID', 'BIGSERIAL', '', TRUE, '', ''));
            $t->add_column(new urd_column('binaryID', 'CHAR(32)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('messageID', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('subject', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('fromname', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('date', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
            $t->add_column(new urd_column('partnumber', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
            $t->add_column(new urd_column('size', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
            $t->add_index(new urd_index('idx_binaries_id_'. $part_table, 'PRIMARY', array('ID')));
            $t->add_index(new urd_index('idx_binaryID_'. $part_table, '', array('binaryID')));
            $t->add_index(new urd_index('idx_date_'. $part_table, '', array('date')));
            $urd_db->add($t);
            $t = new urd_table($bin_table, 'tmp', 'utf8'); // tmp primary index because sqlite can't drop columns
            $t->add_column(new urd_column('binaryID', 'CHAR(32)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('setID', 'CHAR(32)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('subject', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('date', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
            $t->add_column(new urd_column('bytes', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
            $t->add_column(new urd_column('totalParts', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
            $t->add_column(new urd_column('dirty', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
            $t->add_index(new urd_index($bin_table . '_pkey', 'PRIMARY', array('binaryID')));
            $t->add_index(new urd_index('idx_dirty_' . $bin_table, '', array('dirty')));
            $t->add_index(new urd_index('idx_setID_' . $bin_table, '', array('setID')));
            $t->drop_column('tmp');
            $urd_db->add($t);
            $urd_db->add_tables($db_update);

        } catch (exception $e) {
            throw new exception('Cannot create table: ' . $e->getMessage() . ' - ' . $this->db->ErrorMsg() . '(' . $this->db->ErrorNo() . ')', DB_FAILURE);
        }
        try {
            update_group_state($this->db, $groupid, newsgroup_status::NG_SUBSCRIBED, $expire, $minsetsize, $maxsetsize);
        } catch (exception $e) {
            throw new exception('Subscribe failed: ' . $this->db->ErrorMsg() . '(' . $this->db->ErrorNo() . ')', DB_FAILURE);
        }

        return TRUE;
    }
    
    public function check_group_subscribed($groupid)
    {
        if (!group_subscribed($this->db, $groupid)) {
            write_log("Subscribing to group: $groupid", LOG_NOTICE);
            $exp = get_config($this->db, 'default_expire_time');
            $this->subscribe($groupid, $exp);
        }
    }
    
    public function unsubscribe($groupid) // set to inactive an remove the binaries table
    {
        assert(is_numeric($groupid));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        $is_subscribed = group_subscribed($this->db, $groupid);
        if ($is_subscribed === FALSE) {
            throw new exception('Not subscribed', DB_FAILURE);
        }
        try {
            $expire = get_config($this->db, 'default_expire_time');
            update_group_state($this->db, $groupid, newsgroup_status::NG_UNSUBSCRIBED, $expire, 0, 0);
        } catch (exception $e) {
            throw new exception('Unsubscribe failed: ' . $this->db->ErrorMsg() . '(' . $this->db->ErrorNo() . ')', DB_FAILURE);
        }

        try {
            $this->db->drop_table("binaries_$groupid");
            $this->db->drop_table("parts_$groupid");
        } catch (exception $e) {
            throw new exception('Cannot drop table: ' . $this->db->ErrorMsg() . '(' . $this->db->ErrorNo() . ')', DB_FAILURE);
        }
        // Also mark as 'clean' in group table, otherwise re-subscribe uses bad last_record info.
        $this->db->update_query_2('groups', array('last_record'=>0, 'last_updated'=>0, 'setcount'=>0), '"ID" = ?', array($groupid)); 
        return TRUE;
    }

    public function update_binary_info($group_id, $group_name, $do_expire, $expire, action $item, $minsetsize, $maxsetsize)
    {
        assert(is_numeric($group_id) && is_numeric($expire));
        // Update binary info:
        write_log('Updating binary info for ' . $group_name, LOG_NOTICE);
        $this->update_binary_data($group_id, $item->get_dbid());
        update_queue_status($this->db, $item->get_dbid(), NULL, 0, 75, 'Added binary data');
        $this->merge_binary_sets($group_id);
        update_queue_status($this->db, $item->get_dbid(), NULL, 0, 85, 'Merged binary sets');

        // Also update set info:
        write_log('Updating set info for ' . $group_name, LOG_NOTICE);
        $this->update_set_data($group_id, $item->get_dbid(), $minsetsize, $maxsetsize);
        update_queue_status($this->db, $item->get_dbid(), NULL, 0, 99, 'Added set data');
        write_log('Updating set info for ' . $group_name . ' complete', LOG_NOTICE);
    }

    private function merge_binary_sets($group_id)
    {
        $this->db->escape($group_id, FALSE);
        $sql = "merged_sets.\"new_setid\", binaries_$group_id.\"setID\" AS old_setid FROM binaries_$group_id "
            . "JOIN merged_sets ON merged_sets.\"old_setid\" = binaries_$group_id.\"setID\" AND merged_sets.\"type\" = :type";
        $res = $this->db->select_query($sql, array(':type'=>USERSETTYPE_GROUP));
        if ($res === FALSE) {
            return;
        }
        foreach ($res as $row) {
            $this->db->update_query_2("binaries_$group_id", array('setID'=>$row['new_setid']), '"setID"=?', array($row['old_setid']));
        }
    }

    public function merge_sets($setid1, array $setids)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        try {
            $groupid1 = get_groupid_for_set($this->db, $setid1);
        } catch (exception $e) {
            write_log("Cannot find group for base set $setid1", LOG_INFO);

            return;
        }
        $articlesmax = 0;
        $r = $this->db->select_query('"articlesmax" FROM setdata WHERE "ID"=:id', array(':id'=>$setid1));
        if (isset($r[0]['articlesmax'])) {
            $articlesmax = $r[0]['articlesmax'];
        }
        $sql = '"articlesmax" FROM setdata WHERE "ID"=:id';
        foreach ($setids as $setid2) { // all old setids
            if (trim($setid2) == '') {
                continue;
            }
            try {
                $groupid2 = get_groupid_for_set($this->db, $setid2);
            } catch (exception $e) {
                write_log("Cannot find group for merging set $setid2", LOG_INFO);
                continue;
            }
            if ($groupid1 != $groupid2) {
                throw new exception('Groups do not match');
            }
            $this->db->update_query_2("binaries_$groupid1", array('setID'=>$setid1), '"setID"=?', array($setid2));
            $r = $this->db->select_query($sql, array(':id'=>$setid2));
            if (isset($r[0]['articlesmax'])) {
                $articlesmax += $r[0]['articlesmax'];
            }

            $this->db->delete_query('setdata', '"ID"=:setid', array(':setid'=>$setid2));
            store_merge_sets_data($this->db, $setid1, $setid2, USERSETTYPE_GROUP, ESI_NOT_COMMITTED);
        }
        unset($setids);
        $this->add_set_data($groupid1, $setid1);
        $this->db->update_query_2('setdata', array('articlesmax'=>$articlesmax), '"ID"=?', array($setid1));
        $setcount = count_sets_group($this->db, $groupid1);
        update_group_setcount($this->db, $groupid1, $setcount);
    }
}
