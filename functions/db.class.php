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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: db.class.php 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathdbc = realpath(dirname(__FILE__));

require_once "$pathdbc/../config.php";
require_once "$pathdbc/config_functions.php";
require_once "$pathdbc/defines.php";
require_once "$pathdbc/urd_exceptions.php";
require_once "$pathdbc/db/urd_db_structure.php";
require_once "$pathdbc/libs/adodb/adodb-exceptions.inc.php";
require_once "$pathdbc/libs/adodb/adodb.inc.php";
require_once "$pathdbc/db_mysql.php";
require_once "$pathdbc/db_psql.php";
require_once "$pathdbc/db_sqlite.php";

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

function check_db_version(DatabaseConnection $db)
{
    $ver = get_config($db, 'db_version', '-1');
    if ($ver != DB_VERSION) {
        echo_debug("DB Version not OK: $ver - should be: " . DB_VERSION , DEBUG_DATABASE);
        if (php_sapi_name() == 'cli') {
            throw new exception_db_version('Database outdated run php install/update_db.php');
        } else {
            global $pathdbc;
            require_once "$pathdbc/web_functions.php";
            redirect('../html/update_db.php');
        }
    } else {
        echo_debug("DB Version OK: $ver", DEBUG_DATABASE);
    }
}

function connect_db($persistent=FALSE, $check_db=TRUE)
{
    assert(is_bool($persistent) && is_bool($check_db));
    global $pathf;
    $db_config = my_realpath("$pathf/../") . '/dbconfig.php';
    $res = @include $db_config;
    if (!$res) {
        throw new exception("Require file $db_config not found. Perhaps you need to reinstall URD.\n");
    }

    if (!isset($config['db_engine'])) {
        write_log('Database engine not set; using defaults', LOG_INFO);
        $config['db_engine'] = '';
    }
    $reconnect = ($persistent ? FALSE : TRUE);
    try {
        switch ($config['databasetype']) {
        case 'mysql':
        case 'mysqli':
        case 'pdo_mysql':
            $db = new DatabaseConnection_mysql($config['databasetype'], $config['db_hostname'], (isset($config['db_port']) ? $config['db_port'] : ''),
                    $config['db_user'], $config['db_password'], $config['database'], $reconnect, $persistent, $config['db_engine']);
            break;
        case 'pdo_pgsql':
        case 'postgres9':
        case 'postgres8':
        case 'postgres7':
            $db = new DatabaseConnection_psql($config['databasetype'], $config['db_hostname'], (isset($config['db_port']) ? $config['db_port'] : ''),
                    $config['db_user'], $config['db_password'], $config['database'], $reconnect, $persistent, $config['db_engine']);
            break;
        case 'pdo_sqlite':
            $db = new DatabaseConnection_sqlite($config['databasetype'], $config['db_hostname'], (isset($config['db_port']) ? $config['db_port'] : ''),
                    $config['db_user'], $config['db_password'], $config['database'], $reconnect, $persistent, $config['db_engine']);
            break;
        case 'mysqlt':
        default:
            throw new exception ('Database type not supported');
        }

        if ($check_db) {
            check_db_version($db);
        }
    } catch (exception $e) {
        unset($db);
        throw $e;
    }

    return $db;
}

/* Database interface */
abstract class DatabaseConnection
{
    protected $databasetype;
    protected $databasename;
    protected $hostname;
    protected $port;
    protected $uri;
    protected $DB;
    protected $username;
    protected $password;
    protected $force_new_connection;
    protected $result;
    protected $persistent;
    protected $error_code;

    const MAX_INSERT_PARTS          = 200;
    const DB_LOCK_TIMEOUT_PREVIEW   = 2;
    const DB_LOCK_TIMEOUT_DEFAULT   = 15;
    const GENSETS_STEPSIZE          = 50;
    const QUERY_LOG_FILE            = '/tmp/urd_query.log';

    const DIRTY = 1;
    const CLEAN = 0;
    // Dirty flags:
    const CONSISTENT =    0;
    const BINARYCHANGED = 1;
    const SETCHANGED =    2;
    public function __construct ($databasetype, $hostname, $port, $user, $pass, $database, $force_new_connection=FALSE, $persistent=FALSE, $dbengine='')
    {
        assert(is_bool($force_new_connection));
        $this->error_code = 0;
        $this->databasetype = $databasetype;
        $this->databasename = $database;
        $this->hostname = $hostname;
        $this->port = $port;
        if ($databasetype == 'pdo_mysql') {
              $this->uri = "mysql:host=$hostname;dbname=$database;" . (($port != '') ? "port=$port;" : '');
        } elseif ($databasetype == 'pdo_pgsql') {
            if ($database == '') { $database = 'postgres'; }
            $this->uri = "pgsql:host=$hostname;dbname=$database;" . (($port != '') ? "port=$port;" : '') ;
        } else {
            $this->uri .= $hostname . (($port != '') ? ":$port" : '');
        }
        $this->username = $user;
        $this->password = $pass;
        $this->force_new_connection = $force_new_connection;
        $this->persistent = $persistent;
        $this->databaseengine = $dbengine;
        // We do store the sensitive information.  ;-)
        $this->connect($force_new_connection);
        $this->DB->bulkBind = TRUE;
        $this->DB->SetFetchMode(ADODB_FETCH_ASSOC); // don't do $res[0][0], but only $res[0]['name'];
    }
    public function get_databasetype()
    {
        return $this->databasetype;
    }
    public function get_database_server_info()
    {
        return $this->DB->ServerInfo();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function disconnect()
    {
        $this->DB->close();
    }
    public function is_connected()
    {
       return (is_a($this->DB, 'ADOConnection') && $this->DB->isConnected());
    }

    public function set_fetch_mode($mode = ADODB_FETCH_DEFAULT)
    {
        return $this->DB->SetFetchMode($mode);
    }

    abstract public function truncate_table($table);
    abstract public function get_dow_timestamp($from);
    abstract public function get_timestamp($from);
    abstract public function get_extract($what, $from);
    abstract public function get_greatest_function();
    abstract public function get_pattern_search_command($search_type);
    abstract public function start_transaction();
    abstract public function commit_transaction();
    abstract public function lock(array $tableactions);
    abstract public function unlock();
    abstract public function drop_database($dbase);
    abstract public function drop_user($user, $host='localhost');
    abstract public function create_dbuser($host, $user, $pass);
    abstract public function grant_rights($host, $dbname, $dbuser);
    abstract public function create_database($dbname);
    abstract public function optimise_table($table);
    abstract public function get_tables();
    abstract public function connect($force_new_connection=FALSE);
    abstract protected function execute_if_exists($table, $qry);

    public function get_error_code()
    {
         return $this->error_code;
    }
    public function execute_query($query, $values=FALSE)
    {
        $this->error_code = 0;
        if (defined('QUERY_LOG')) {
            echo_debug_file(self::QUERY_LOG_FILE, $query, $values, ORIGINAL_PAGE);
        }
        $this->result = NULL;
        if (!$this->is_connected()) {
            $this->connect();
        }
        try {
            $this->result = $this->DB->Execute($query, $values);
        } catch (exception $e) {
            $errCode = $e->getCode();
            $this->error_code= $errCode;
            //echo_debug("Database problem: $errCode - " . $e->getMessage(), DEBUG_MAIN);
            // MySQL error codes (http://dev.mysql.com/doc/refman/5.1/en/error-messages-client.html)
            // 1062: Insert failed, duplicate key
            // 2006: Server has gone away
            // 1100: Table was not locked
            // 2013: Lost connection to MySQL server during query

            // PGSql error codes
            // -5: Insert failed, duplicate key

            // If connection was lost, try to reconnect and then do query again:
            $dbtype = $this->databasetype;

            if ((($dbtype == 'mysql' || $dbtype == 'mysqli' || $dbtype == 'pdo_mysql') && ($errCode == 2006 || $errCode == 2013)) ||
                (($dbtype == 'postgres7' || $dbtype == 'postgres8' || $dbtype == 'postgres9'|| $dbtype == 'pdo_pgsql') && ($errCode != -5)))  {// sqlite should not lose connections
                write_log("Database problem: ( $errCode ) " . $e->getMessage(), LOG_WARNING);
                echo_debug_trace($e, DEBUG_DATABASE);
                try {
                    $this->connect(TRUE);
                    $this->result = $this->DB->Execute($query, $values);
                } catch (exception $e1) {
                    write_log("Database problem: ($errCode) " . $e1->getMessage(), LOG_ERR);
                    echo_debug_trace($e1, DEBUG_DATABASE);
                    throw new exception("Could not execute SQL query \"$query\" " . $e1->getMessage());
                }
            } elseif (($dbtype == 'mysql' || $dbtype == 'mysqli' || $dbtype == 'pdo_mysql') && ($errCode == 1062)) {
                return FALSE;
            } else {
                write_log("Database problem: ($errCode) " . $e->getMessage(), LOG_ERR);
                throw new exception("Could not execute SQL query \"$query\" " . $e->getMessage());
            }
        }
        if (defined('QUERY_LOG')) {
            echo_debug_file(self::QUERY_LOG_FILE, 'done', FALSE, ORIGINAL_PAGE);
        }
        if ($this->result->RecordCount() == 0) {
            return FALSE;
        } else {
            $this->result->Move(0); // just to be sure

            return $this->result->GetArray();
        }
    }

    public function update_query($table, array $columns, array $values, $where)
    {
        $col_str = '';
        reset($columns);
        reset($values);
        do {
            $c = current($columns);
            $v = current($values);
            if ($c === FALSE && $v === FALSE) {
                break;
            } elseif ($c === FALSE || $v === FALSE) {
                throw new exception ('Could not execute update query; columns and values do not match');
            }
            $this->escape($v, TRUE);
            $col_str .= "\"$c\" = $v, ";
            next($columns);
            next($values);
        } while (TRUE);
        $col_str = rtrim($col_str, ', ');
        $query = "UPDATE $table SET $col_str WHERE $where";
        $this->execute_query($query);
    }

    public function insert_query($table, array $columns, array $values, $get_last_ID=FALSE)
    {
    /* values can be an array of values or an array of an array of values
       array (1, 2, 3) or
       array (array(1, 2, 4), array (3, 4, 5)) each inside array is one row
     */
        assert(is_string($table) && assert(is_bool($get_last_ID)));

        if ($values == array() ) {
            return FALSE;
        }

        $col_str = $val_str = '';

        foreach ($columns as $col) {
            $col_str .= "\"$col\", ";
            $val_str .= '?, ';
        }
        $col_str = rtrim($col_str, ', ');
        $val_str = rtrim($val_str, ', ');

        $sql = "INSERT INTO $table ($col_str) VALUES ($val_str)";
        $p_sql = $this->DB->prepare($sql);

        try {
            $this->execute_query($p_sql, array_values($values));
            if ($get_last_ID) {
                return $this->get_last_id();
            } else {
                return FALSE;
            }
        } catch (exception $e) {
            //var_dump($e->getTraceAsString());
            throw new exception("Could not execute SQL query \"$sql\" " . $e->getMessage());
        }
    }
    public function escape(&$object, $quoted = FALSE)
    {
        assert(is_bool($quoted));
        if (is_array($object) || is_object($object)) {
            foreach ($object as &$item) {
                $this->escape($item, $quoted);
            }
        } else {
            $object = $this->DB->qstr($object);
            if (!$quoted) {
                $object = substr($object, 1, -1);
            }
        }
    }

    public function num_rows($result = FALSE)
    {
        if ($result === FALSE) {
            return $this->result->RecordCount();
        } else {
            return $result->RecordCount();
        }
    }

    abstract public function get_last_id();


    public function select_query($qry, $num_rows=-1, $offset=-1)
    {
        assert(is_numeric($num_rows) && is_numeric($offset) && is_string($qry)) ;
        if (defined('QUERY_LOG')) {
            echo_debug_file(self::QUERY_LOG_FILE, 'SELECT ' . $qry . " ($num_rows, $offset) ", FALSE, ORIGINAL_PAGE);
        }
        $this->result = NULL;
        $sql = "SELECT $qry";
        if (!$this->DB->isConnected()) {
            $this->connect();
        }
        try {
            $this->result = $this->DB->SelectLimit($sql, $num_rows, $offset);
            if ($this->result == FALSE) {
                throw new exception('No results returned');
            }
        } catch (exception $e) {
            $errCode = $e->getCode();
            write_log("Database problem: $errCode - " . $e->getMessage(), LOG_INFO);
            // MySQL error codes (http://dev.mysql.com/doc/refman/5.1/en/error-messages-client.html)
            // 1062: Insert failed, duplicate key
            // 2006: Server has gone away
            // 1100: Table was not locked
            // 2013: Lost connection to MySQL server during query

            // PGSql error codes
            // -5: Insert failed, duplicate key

            // If connection was lost, try to reconnect and then do query again:
            $dbtype = $this->databasetype;
            if ((($dbtype == 'mysql' || $dbtype == 'mysqli' || $dbtype == 'pdo_mysql') && ($errCode == 2006 || $errCode == 2013)) ||
                (($dbtype == 'postgres7' || $dbtype == 'postgres8' || $dbtype == 'postgres9'|| $dbtype == 'pdo_pgsql') && ($errCode != -5)))  {// sqlite should not lose connections
                write_log("Database problem: ( $errCode ) " . $e->getMessage(), LOG_WARNING);
                try {
                    $this->connect(TRUE);
                    $this->result = $this->DB->SelectLimit($sql, $num_rows, $offset);
                    if ($this->result == FALSE) {
                        throw new exception('No results returned');
                    }
                } catch (exception $e) {
                    write_log("Database problem: ( $errCode ) " . $e->getMessage(), LOG_ERR);
                    throw new exception("Could not execute SQL select query \"$sql\" " . $e->getMessage());
                }
            } else {
                throw new exception("Could not execute SQL select query \"$sql\" " . $e->getMessage());
            }
        }
        if (defined('QUERY_LOG')) {
            echo_debug_file(self::QUERY_LOG_FILE, 'done', FALSE, ORIGINAL_PAGE);
        }
        if ($this->result->RecordCount() == 0) {
            return FALSE;
        } else {
            $this->result->Move(0); // just to be sure

            return $this->result->GetArray();
        }
    }

    public function delete_query($table, $where='')
    {
        $sql = "DELETE FROM $table";
        if ($where != '') {
            $sql .= " WHERE $where";
        }

        return $this->execute_query($sql);
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
        $binaries = array();
        foreach ($tables as $table) {
            $binaries[$table->binaryID] = 1;
        }

        // Save parts:
        $x = 1;
        static $cols = array('binaryID', 'messageID', 'subject', 'fromname', 'date', 'partnumber', 'size');
        $vals = array();

        $this->start_transaction();
        try {
            foreach ($tables as $table) {
                if ($x % self::MAX_INSERT_PARTS == 0) {
                    $this->insert_query("parts_$groupID", $cols, $vals, FALSE);
                    $vals = array();
                }
                $vals[] = array($table->binaryID, $table->messageID, $table->subject, $table->fromname, $table->date, $table->partnumber, $table->size);
                $x++;
            }
            // Insert the remaining values (if any):
            if (count($vals) > 0) {
                $this->insert_query("parts_$groupID", $cols, $vals, FALSE);
            }
        } catch (exception $e) {
            $this->commit_transaction();
            throw $e;
        }
        $this->commit_transaction();

        // Update the binaries
        $x = (int) 1;
        $binarieslist = '';
        try {
            foreach ($binaries as $binary => $garbage) {

                // In case it is a new binary, create it. Let it fail if it wants (because it exists),
                // we will do an update later on anyways:
                try {
                    $this->execute_query("INSERT INTO binaries_$groupID (\"binaryID\") VALUES ('$binary')");
                } catch (exception $e) { }

                if ($x % self::MAX_INSERT_PARTS == 0) {
                    // Remove the last , before running the query:
                    $binarieslist = rtrim($binarieslist, ', ');
                    $qry = "UPDATE binaries_$groupID SET \"dirty\" = " . self::DIRTY . " WHERE \"binaryID\" IN ( $binarieslist )";
                    $this->execute_query($qry);
                    $binarieslist = '';
                    $x = (int) 1;
                }
                $binarieslist .= "'" . $binary . "',";
                $x++;
            }

            // Insert the remaining values (if any):
            if ($binarieslist !== '') {
                $binarieslist = rtrim($binarieslist, ', ');
                $qry = "UPDATE binaries_$groupID SET \"dirty\" = " . self::DIRTY . " WHERE \"binaryID\" in ( $binarieslist )";
                $this->execute_query($qry);
            }
        } catch (exception $e) {
             throw $e;
        }
    }

    public function update_binary_data($groupID, $id)
    {
        assert (is_numeric($groupID));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);

        // Get the group name for use in log messages:
        $groupname = group_name($this, $groupID);

        // Steps: 1) Select a bunch of dirty binaries
        //        2) For these binaries, get the parts data
        //        3) Save the new information for the binary
        //        4) Rinse & Repeat

        // Get the total number of binaries that are going to be updated:
        $this->escape($groupID);
        $sql = "count(*) AS total FROM (SELECT DISTINCT \"binaryID\" FROM binaries_$groupID WHERE \"dirty\" = " . self::DIRTY . ") AS t";
        $res = $this->select_query($sql);
        if (!isset($res[0]['total'])) {
            write_log('No binaries found', LOG_NOTICE);

            return;
        }
        $total = $res[0]['total'];

        // Update queue:
        update_queue_status($this, $id, NULL, NULL, 1, NULL, NULL);
        write_log("Total updated binaries in $groupname is $total", LOG_NOTICE);

        $stepsize = self::GENSETS_STEPSIZE; // Number of binaries that are updated per batch
        $cnt = 0;   // Number of binaries that are done
        // Columns in the binaries table:
        static $cols = array ('binaryID', 'subject', 'date', 'bytes', 'totalParts', 'setID', 'dirty');
        static $cols_nfo = array('setID', 'groupID', 'binaryID');

        // Going through all dirty binaries for this group
        $s_time = microtime(TRUE);
        while (1) {  // Loop through every chunk in turn to benefit caching -> no tmp tables on disk
            // Step 1: Selecting:
            // PS: No need to keep track of how many we select, next run these will not have the dirty flag anymore
            //     so we just keep selecting dirty binaries until they are all gone
            $sql = "DISTINCT \"binaryID\" FROM binaries_$groupID WHERE \"dirty\" = " . self::BINARYCHANGED;
            $res = $this->select_query($sql, $stepsize);
            if (!is_array($res)) {
                echo_debug("Processed $cnt binaries.", DEBUG_SERVER);

                return;
            }
            $cnt += count($res);

            // $l is going to be the list of the binaryID's that we have just gotten back
            $l = '';
            foreach ($res as $row) {
                $l .= "'{$row['binaryID']}',";
            }
            $l = rtrim($l, ', ');

            // Delete all the binaries, then recreate (because inserting in batches is faster? and easier than updating binaries 1 by 1
            $this->delete_query("binaries_$groupID", "\"binaryID\" IN ($l)");

            // Step 2: Updating:
            // Max for subject and fromname are needed to ensure we always get the same result, not a random one
            $sql = "SUM(\"size\") AS totalsize, \"binaryID\", COUNT(*) AS parttotal, MAX(\"subject\") AS subject, MAX(\"fromname\") AS fromname,"
                    . "MIN(\"date\") AS mindate FROM parts_$groupID WHERE \"binaryID\" IN ($l) GROUP BY \"binaryID\"";

            $res = $this->select_query($sql);
            $vals = $vals_nfo = array();
            // For each row, export to binaries_X
            if (!is_array($res)) {
                $res = array();
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

                $vals[] = array ($row['binaryID'], $row['subject'], $row['mindate'], $row['totalsize'], $row['parttotal'], $setID, self::SETCHANGED);
                if ($is_nfo_file) {
                    $vals_nfo[] = array($setID, $groupID, $row['binaryID']);
                }

                // Step 3: Saving new information:
                // Batch insert
                if ((count($vals) % self::MAX_INSERT_PARTS) == 0) {

                // Not INSERT DELAYED because then generating sets will fail; as the binaries aren't there yet
                    $this->insert_query('binaries_' . $groupID, $cols, $vals, FALSE);
                    if (count($vals_nfo) > 0) {
                        $this->insert_query('nfo_files', $cols_nfo, $vals_nfo, FALSE);
                        $vals_nfo = array();
                    }
                    $vals = array();
                }
            }

            // If batch was not complete, make sure we do the remaining ones now:
            if (count($vals) > 0) {
                $this->insert_query('binaries_' . $groupID, $cols, $vals, FALSE);
                $vals = array();
            }
            if (count($vals_nfo) > 0) {
                $this->insert_query('nfo_files', $cols_nfo, $vals_nfo, FALSE);
                $vals_nfo = array();
            }

            $t_time = microtime(TRUE);
            // The ETA / percentage calculation:
            if ($cnt > 0 && $total > 0) {
                $ETA = floor((($total - $cnt) * ($t_time - $s_time) / $cnt) / .75);
                $progress = floor((75 * $cnt) / $total);
                update_queue_status($this, $id, NULL, $ETA, $progress, NULL, NULL);
            }
        }
    }

    public function update_set_data($groupID, $id, $minsetsize, $maxsetsize)
    {
        assert(is_numeric($groupID));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        $this->escape($groupID);

        $stepsize = self::GENSETS_STEPSIZE;
        $offset = $cnt = 0;
        $sql = "count(*) AS total FROM (SELECT DISTINCT \"setID\" FROM binaries_$groupID WHERE \"dirty\" = " . self::SETCHANGED . ') AS t';
        $res = $this->select_query($sql);
        if (!isset($res[0]['total'])) {
            write_log('No binaries found', LOG_NOTICE);

            return;
        }
        $total = $res[0]['total'];
        $s_time = microtime(TRUE);
        while (1) {
            $sql = "DISTINCT \"setID\" FROM binaries_$groupID WHERE \"dirty\" = " . self::SETCHANGED;
            $res = $this->select_query($sql, $stepsize);
            if (!is_array($res) && $offset == 0) {
                write_log("No new binaries found for group with id $groupID!", LOG_NOTICE);

                return;
            } elseif (!is_array($res)) {
                echo_debug("Done. Processed $cnt sets. Total was $total", DEBUG_SERVER);
                break;
            }
            $cnt += count($res);

            $l = '';
            foreach ($res as $row) {
                $l .= "'" . $row['setID'] . "',";
            }
            $l = rtrim($l, ', ');
            $this->delete_query('setdata', "\"ID\" IN ($l) AND \"groupID\" = '$groupID'");
            $sql = ' "setID", count("binaryID") AS bins, MIN("subject") AS subject, MIN("date") AS date, SUM("bytes") AS totalsize ' .
                "FROM \"binaries_$groupID\" WHERE \"setID\" IN ($l) GROUP BY \"setID\"";
            $res = $this->select_query($sql);
            $offset += $stepsize;
            $set_list = array();
            // For each row, export to binaries_X
            foreach ($res as $arr) {
                $size = $arr['totalsize'];
                if ($size < $minsetsize) {
                    echo_debug("Discarding set: too small - probably spam ($size < $minsetsize)", DEBUG_SERVER);
                    continue;
                } elseif ($maxsetsize != 0 && $size > $maxsetsize) {
                    echo_debug("Discarding set: too large ($size > $maxsetsize)", DEBUG_SERVER);
                    continue;
                } else {
//                    echo_debug("Adding set: $size $minsetsize", DEBUG_SERVER);
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
            $sql = "UPDATE binaries_$groupID SET \"dirty\" = " . self::CONSISTENT . " WHERE \"setID\" in ($l)";
            $this->execute_query($sql);
            $t_time = microtime(TRUE);
            $ETA = floor((($total - $cnt) * ($t_time - $s_time) / $cnt));
            update_queue_status($this, $id, NULL, $ETA, floor(85 + ((15 * $cnt) / $total)), NULL, NULL);
        }
    }

    private function add_set_data($groupID, $setID_filter)
    {
        assert (is_numeric($groupID));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        $stepsize = self::GENSETS_STEPSIZE;
        $this->escape($groupID);
        // we only update one set
        $this->escape($setID_filter, TRUE);
        $Qsetid = " AND binaries_$groupID.\"setID\" = $setID_filter ";
        $Qsetid1 = " AND \"ID\" = $setID_filter ";

        // First delete everything for this group or only the specific set:
        $this->delete_query('setdata', "\"groupID\" = '$groupID' $Qsetid1");

        // Now re-create it.
        $sql = '"setID", count("binaryID") AS bins, MIN("subject") AS subject, MIN("date") AS date, SUM("bytes") AS totalsize ' .
            "FROM \"binaries_$groupID\" WHERE 1=1 $Qsetid GROUP BY \"setID\"";
        $res1 = $this->select_query($sql);
        $set_list = array();
        // To minimise memory requirements, we update setdata per set, instead of all at the end:
        if (is_array($res1)) {
            foreach ($res1 as $arr) {
                $set_array = new TableSetData;
                $set_array->setID = $arr['setID'];
                $set_array->groupID =  $groupID;
                $set_array->subject = $arr['subject'];  // 1st hit determines the subject, correct.
                // If the subject name is "Some Random Movie [1/104]", then:
                $set_array->articlesmax = get_set_size($arr['subject']);   // This is '104'
                $set_array->binaries =  $arr['bins'];           // And this is the number of files belonging to the set. (hopefully 104).
                $set_array->size = $arr['totalsize'];
                $set_array->date = $arr['date'];        // 1st hit also determines the set date.
                $set_list[] = $set_array;
                if (count($set_list) >= $stepsize) {
                    $this->add_sets($set_list);
                    $set_list = array();
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
        static $cols = array ('ID', 'groupID', 'subject', 'articlesmax', 'binaries', 'date', 'size');
        $vals = array();
        foreach ($sets as $set) {
            $vals[] = array ($set->setID, $set->groupID, $set->subject, $set->articlesmax, $set->binaries, $set->date, $set->size);
        }
        if (count($vals) > 0) {
            $this->insert_query('setdata', $cols, $vals, FALSE);
        }
    }

    public function quick_expire($groupid)
    {
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        $type = USERSETTYPE_GROUP;
        $marking_on = sets_marking::MARKING_ON;
        $keep_int_cfg = get_config($this, 'keep_interesting');
        $time = time();
        // Expire : from days to seconds
        $expire = group_expire($this, $groupid);
        $expire *= 24 * 3600;
        // convert to epochtime:
        $expire = $time - $expire;
        $keep_int = '';
        if ($keep_int_cfg) {
            $keep_int = " AND \"binaryID\" NOT IN (SELECT \"binaryID\" FROM usersetinfo JOIN binaries_$groupid AS bin ON bin.\"setID\" = usersetinfo.\"setID\" "
            . " WHERE \"type\" = '$type' AND \"statusint\" = '$marking_on') ";
        }
        $this->delete_query("parts_$groupID", "\"date\" < $expire $keep_int");
    }

    public function expire_binaries($groupID, $dbid)
    {
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        assert(is_numeric($groupID) && is_numeric($dbid));
        $type = USERSETTYPE_GROUP;

        $time = time();
        // Expire : from days to seconds
        $expire = group_expire($this, $groupID);
        $expire *= 24 * 3600;
        // convert to epochtime:
        $expire = $time - $expire;
        $do_expire_incomplete = $expire_incomplete = get_config($this, 'expire_incomplete');
        $expire_percentage = get_config($this, 'expire_percentage');
        $expire_incomplete *= 24 * 3600;
        $expire_incomplete = $time - $expire_incomplete;
        $marking_on = sets_marking::MARKING_ON;
        $prefs = load_config($this);

        $keep_int = '';
        if ($prefs['keep_interesting']) {
            $keep_int = " AND \"setID\" NOT IN (SELECT \"setID\" FROM usersetinfo WHERE \"type\" = '$type' AND \"statusint\" = '$marking_on') ";
        }

        echo_debug('Deleting expired posts', DEBUG_DATABASE);
        $sql = "count(\"binaryID\") AS cnt FROM binaries_$groupID WHERE \"date\" < $expire $keep_int";
        $res = $this->select_query($sql);
        $cnt = 0;
        if (isset($res[0]['cnt'])) {
            $cnt = $res[0]['cnt'];
        }
        write_log('Deleting '. $cnt . ' binaries');
        update_queue_status ($this, $dbid, NULL, 0, 1);
        $GREATEST = $this->get_greatest_function();

        $keep_int = '';
        if ($prefs['keep_interesting']) {
            $keep_int = " AND \"ID\" NOT IN (SELECT \"setID\" FROM usersetinfo WHERE \"type\" = '$type' AND \"statusint\" = '$marking_on') ";
        }
        // first clean all the sets we want to remove
        $Qcomplete = '';
        if ($do_expire_incomplete != 0 && $expire_percentage > 0 && $expire_percentage < 100) {
            $Qcomplete = "OR (\"articlesmax\" != 0 AND floor(\"binaries\" * 100 / $GREATEST(1, \"articlesmax\")) < '$expire_percentage' AND \"date\" < '$expire_incomplete' )";
        }
        $res = $this->delete_query('setdata', " \"groupID\" = '$groupID' AND (\"date\" < $expire $Qcomplete) $keep_int");
        update_queue_status ($this, $dbid, NULL, 0, 30);

        $res = $this->delete_query('usersetinfo', "\"setID\" NOT IN (SELECT \"ID\" FROM setdata) AND \"type\" = '$type'");

        update_queue_status ($this, $dbid, NULL, 0, 40);
        // note that this will also remove data about sets that hasn't been received yet, but typically, expire runs after an update, so all data should be in.
        $res = $this->delete_query('extsetdata', "\"setID\" NOT IN (SELECT \"ID\" FROM setdata) AND \"type\" = '$type'");

        update_queue_status ($this, $dbid, NULL, 0, 50);
        // see above
        $res = $this->delete_query('merged_sets', "\"new_setid\" NOT IN (SELECT \"ID\" FROM setdata) AND \"type\" = '$type' ");

        update_queue_status ($this, $dbid, NULL, 0, 60);

        $keep_int = '';
        if ($prefs['keep_interesting']) {
            $keep_int = " AND \"setID\" NOT IN (SELECT \"setID\" FROM usersetinfo WHERE \"type\" = '$type' AND \"statusint\" = '$marking_on') ";
        }

        $res = $this->delete_query("binaries_$groupID", "\"setID\" NOT IN (SELECT \"ID\" FROM setdata WHERE \"groupID\" = $groupID) $keep_int");

        update_queue_status ($this, $dbid, NULL, 0, 80);

        $keep_int = '';
        if ($prefs['keep_interesting']) {
            $keep_int = " AND \"binaryID\" NOT IN (SELECT \"binaryID\" FROM usersetinfo JOIN binaries_$groupID AS bin ON bin.\"setID\" = usersetinfo.\"setID\" "
            . " WHERE \"type\" = '$type' AND \"statusint\" = '$marking_on') ";
        }
        $res = $this->delete_query("parts_$groupID", "\"binaryID\" NOT IN (SELECT \"binaryID\" FROM binaries_$groupID) OR \"date\" < $expire $keep_int");

        echo_debug("Deleted {$cnt} binaries", DEBUG_DATABASE);
        update_queue_status ($this, $dbid, NULL, 0, 95);

        $this->update_postcount($groupID);
        update_queue_status ($this, $dbid, NULL, 0, 100);

        return $cnt;
    }


    public function update_postcount($groupid)
    {
        $sql = "UPDATE groups SET postcount = (SELECT COUNT(\"ID\") FROM parts_{$groupid}), \"extset_update\"='0' WHERE \"ID\" = $groupid ";
        $this->execute_query($sql);
    }

    public function compress($data)
    {
        $data = gzdeflate($data);
        $data = base64_encode($data);

        return $data;
    }

    public function decompress($data)
    {
        $data = base64_decode($data);
        $data = gzinflate($data);

        return $data;
    }

    public function purge_binaries($groupID)
    {
        assert (is_numeric($groupID));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        $active = group_subscribed($this, $groupID);

        echo_debug('Deleting all posts', DEBUG_DATABASE);

        $type = USERSETTYPE_GROUP;
        $res = $this->delete_query('usersetinfo', "\"setID\" in (SELECT \"ID\" FROM setdata WHERE \"groupID\" = '$groupID') AND \"type\" = '$type'");
        $res = $this->delete_query('extsetdata', "\"setID\" in (SELECT \"ID\" FROM setdata WHERE \"groupID\" = '$groupID') AND \"type\" = '$type'");
        $res = $this->delete_query('merged_sets', "\"new_setid\" in (SELECT \"ID\" FROM setdata WHERE \"groupID\" = '$groupID') AND \"type\" = '$type'");
        $res = $this->delete_query('setdata', "\"groupID\" = '$groupID'");
        if ($active === TRUE) {
            $this->truncate_table("parts_$groupID");
            $this->truncate_table("binaries_$groupID");
        }
        $res = $this->execute_query("UPDATE groups SET \"last_record\"=0, \"first_record\"=0, \"mid_record\"=0, \"last_updated\"=0, \"postcount\" =0, \"setcount\" = 0 WHERE \"ID\" = '$groupID' ");
        echo_debug('Purged all binaries', DEBUG_DATABASE);
    }


    public function subscribe($groupid, $expire, $minsetsize=0, $maxsetsize=0)
    {
        assert (is_numeric($groupid) && is_numeric($expire) && is_numeric($minsetsize) && is_numeric($maxsetsize));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        $is_subscribed = group_subscribed($this, $groupid);
        if ($is_subscribed !== FALSE) {
            throw new exception('Already subscribed', DB_FAILURE);
        }
        try { // rewrite to urd_db class stuff
            $db_update = create_db_updater($this->databasetype, $this);
            $urd_db = new urd_database($this->databaseengine);
            $part_table = "parts_$groupid";
            $bin_table = "binaries_$groupid" ;
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
            throw new exception('Cannot create table: ' . $e->getMessage() . ' - ' . $this->DB->ErrorMsg() . '(' . $this->DB->ErrorNo() . ')', DB_FAILURE);
        }
        try {
            update_group_state($this, $groupid, NG_SUBSCRIBED, $expire, $minsetsize, $maxsetsize);
        } catch (exception $e) {
            throw new exception('Subscribe failed: ' . $this->DB->ErrorMsg() . '(' . $this->DB->ErrorNo() . ')', DB_FAILURE);
        }

        return TRUE;
    }

    public function unsubscribe($groupid) // set to inactive an remove the binaries table
    {
        assert (is_numeric($groupid));
        echo_debug_function(DEBUG_DATABASE, __FUNCTION__);
        $is_subscribed = group_subscribed($this, $groupid);
        if ($is_subscribed === FALSE) {
            throw new exception('Not subscribed', DB_FAILURE);
        }
        try {
            $expire = get_config($this, 'default_expire_time');
            update_group_state($this, $groupid, NG_UNSUBSCRIBED, $expire, 0, 0);
        } catch (exception $e) {
            throw new exception('Unsubscribe failed: ' . $this->DB->ErrorMsg() . '(' . $this->DB->ErrorNo() . ')', DB_FAILURE);
        }

        try {
            $this->drop_table("binaries_$groupid");
            $this->drop_table("parts_$groupid");
        } catch (exception $e) {
            throw new exception('Cannot drop table: ' . $this->DB->ErrorMsg() . '(' . $this->DB->ErrorNo() . ')', DB_FAILURE);
        }
        // Also mark as 'clean' in group table, otherwise re-subscribe uses bad last_record info.
        $this->execute_query("UPDATE groups SET \"last_record\"=0, \"last_updated\"=0, \"setcount\"=0 WHERE \"ID\" = '$groupid' ");

        return TRUE;
    }

    public function update_binary_info($group_id, $group_name, $do_expire, $expire, action $item, $minsetsize, $maxsetsize)
    {
        assert (is_numeric($group_id) && is_numeric($expire));
        // Update binary info:
        write_log('Updating binary info for ' . $group_name, LOG_NOTICE);
        $this->update_binary_data($group_id, $item->get_dbid());
        update_queue_status($this, $item->get_dbid(), NULL, 0, 75, 'Added binary data');
        $this->merge_binary_sets($group_id);
        update_queue_status($this, $item->get_dbid(), NULL, 0, 85, 'Merged binary sets');

        // Also update set info:
        write_log('Updating set info for ' . $group_name, LOG_NOTICE);
        $this->update_set_data($group_id, $item->get_dbid(), $minsetsize, $maxsetsize);
        update_queue_status($this, $item->get_dbid(), NULL, 0, 99, 'Added set data');
        write_log('Updating set info for ' . $group_name . ' complete', LOG_NOTICE);
    }

    public function merge_binary_sets($group_id)
    {
        $this->escape($group_id);
        $sql = "merged_sets.\"new_setid\", binaries_$group_id.\"setID\" AS old_setid FROM binaries_$group_id "
            . "JOIN merged_sets ON merged_sets.\"old_setid\" = binaries_$group_id.\"setID\" AND merged_sets.\"type\" = '" . USERSETTYPE_GROUP . "'";
        $res = $this->select_query($sql);
        if ($res === FALSE) {
            return;
        }
        foreach ($res as $row) {
            $new_setid = $row['new_setid'];
            $old_setid = $row['old_setid'];
            $this->escape($new_setid, TRUE);
            $this->escape($old_setid, TRUE);
            $sql = "UPDATE binaries_$group_id SET \"setID\" = $new_setid WHERE \"setID\" = $old_setid";
            $this->execute_query($sql);
        }
    }

    public function merge_sets($setid1, array $setids)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        $osetid = $setid1; // new set id
        try {
            $groupid1 = get_groupid_for_set($this, $setid1);
        } catch (exception $e) {
            write_log("Cannot find group for base set $setid1", LOG_INFO);

            return;
        }
        $this->escape($setid1, TRUE);
        $articlesmax = 0;
        $sql = " \"articlesmax\" FROM setdata WHERE \"ID\" = $setid1 ";
        $r = $this->select_query($sql);
        if (isset($r[0]['articlesmax'])) {
            $articlesmax = $r[0]['articlesmax'];
        }

        foreach ($setids as $setid2) { // all old setids
            if (trim($setid2) == '') {
                continue;
            }
            try {
                $groupid2 = get_groupid_for_set($this, $setid2);
            } catch (exception $e) {
                write_log("Cannot find group for merging set $setid2", LOG_INFO);
                continue;
            }
            if ($groupid1 != $groupid2) {
                throw new exception ('Groups do not match');
            }
            $osetid2 = $setid2;
            $this->escape($setid2, TRUE);

            $sql = "UPDATE binaries_$groupid1 SET \"setID\" = $setid1 WHERE \"setID\" = $setid2";
            $this->execute_query($sql);
            $sql = " \"articlesmax\" FROM setdata WHERE \"ID\" = $setid2 ";
            $r = $this->select_query($sql);
            if (isset($r[0]['articlesmax'])) {
                $articlesmax += $r[0]['articlesmax'];
            }

            $this->delete_query('setdata', "\"ID\" = $setid2");
            store_merge_sets_data($this, $osetid, $osetid2, USERSETTYPE_GROUP, ESI_NOT_COMMITTED);
        }
        $this->add_set_data($groupid1, $osetid);
        $this->escape($articlesmax, TRUE);
        $sql = "UPDATE setdata SET articlesmax = $articlesmax WHERE \"ID\" = $setid1";
        $this->execute_query($sql);
        $setcount = count_sets_group($this, $groupid1);
        update_group_setcount($this, $groupid1, $setcount);
    }
}
