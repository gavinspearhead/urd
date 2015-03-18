<?php
/**
/*  vim:ts=4:expandtab:cindent
 *  This file is part of Urd.
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
 * $LastChangedDate: 2014-06-27 23:31:25 +0200 (vr, 27 jun 2014) $
 * $Rev: 3122 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_extsetdata.php 3122 2014-06-27 21:31:25Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function store_extset_data(DatabaseConnection $db, array $result, array &$setidarray)
{
    $counter = 0;
    foreach ($result as $row) {
        if(!isset($row[3])) {
            continue;
        }
        $setid = $row[0];
        $name = $row[1];
        $value = substr($row[2], 0, 255);
        $type = $row[3];
        if ($type == '' || $value == '' || $name == '' || $setid == '') {
            continue;
        }
        // rewrite old names
        if ($name == 'moviescore' || $name == 'musicscore') {
            $name = 'score';
        }
        if (in_array($name, array('movielink', 'musiclink', 'serielink'))) {
            $name = 'link';
        }
        if ($name == 'MERGE_SET') {
            $db->merge_sets($value, array($setid));
        } else {
            $setidarray[$setid] = '1';

            // Insert or update?
            $sql = '"setID" FROM extsetdata WHERE "setID" = ? AND "name" = ? AND "type" = ?';
            $res = $db->select_query($sql, 1, array($setid, $name, $type));

            if (is_array($res)) {// Exists, so update:
                $db->update_query_2('extsetdata',array('value'=>$value, 'committed'=>0), '"setID" = ? AND "name" = ? AND "type" = ?', array($setid, $name, $type));
            } else {
                $db->insert_query_2('extsetdata', array('setID'=>$setid, 'name'=>$name, 'value'=>$value, 'committed'=>ESI_COMMITTED, 'type'=>$type));
            }
        }
        $counter++;
    }

    return $counter;
}

function do_getsetinfo(DatabaseConnection $db, action $item)
{
    $use_newsgroup = FALSE;

    if (get_config($db, 'extset_group') != '') {
        $use_newsgroup = TRUE;
    }
    $comment = '';
    if ($use_newsgroup) {
        $group = get_config($db, 'extset_group');
        $groupid = group_by_name($db, $group);
        $ug = new urdd_group;

        $ug->check_group_subscribed($db, $groupid);
        try {
            $userid = get_admin_userid($db); // get admin user
            $uc = new urdd_client($db, get_config($db, 'urdd_host'), get_config($db,'urdd_port'), $userid);
            $uc->update($groupid, USERSETTYPE_GROUP);
            $uc->disconnect();
        } catch (exception $e) {
            write_log('Connecting back to URDD failed', LOG_ERR);
        }
        $comment .= "Done preparing update of group $group to get ext set info";
        write_log($comment, LOG_INFO);
    }
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, $comment);

    return NO_ERROR;
}

function write_setinfo(DatabaseConnection $db, array $setinfo)
{
    $urd_version = urd_version::get_version();
    $dl_path_basis = get_dlpath($db);
    $id = 'post_' . md5(php_uname() . date('c u'). mt_rand(10000, 99999));
    $post_path = $dl_path_basis . POST_PATH;
    $extset_version = urd_version::get_extset_version();

    $xml = new XMLWriter();
    $xml->openMemory();
    $xml->setIndent(TRUE);
    $xml->startDocument('1.0', 'UTF-8');
    $xml->setIndent(TRUE);
    $xml->startElement('urd_extsetdata');
    $xml->writeAttribute('urd_version', $urd_version);
    $xml->writeAttribute('version', $extset_version);
    $xml->writeAttribute('date', date('c'));
    $xml->startElement('reference');
    $xml->text('http://www.urdland.com');
    $xml->endElement(); // reference

    foreach ($setinfo as $set) {
        $xml->startElement('set');
        if (isset($set['groupname'])) {
            $xml->startElement('groupname');
            $xml->text($set['groupname']);
            $xml->endElement(); // groupname
        }
        $xml->startElement('setid');
        $xml->text($set['setID']);
        $xml->endElement(); // setId
        $xml->startElement('name');
        $xml->text($set['name']);
        $xml->endElement(); // name
        $xml->startElement('value');
        $xml->text($set['value']);
        $xml->endElement(); // value
        $xml->startElement('type');
        $xml->text($set['type']);
        $xml->endElement(); // type
        $xml->endElement(); // set
    }

    $xml->endElement(); // urd_extsetdata
    $xml->endDocument();
    $str = $xml->outputMemory(TRUE);
    $filename = find_unique_name($post_path, 'urd_extsetdata_', $id, '.gz', TRUE);
    $basename = trim(basename($filename));
    $uu_str = "\r\nbegin 644 $basename\r\n";
    $str = gzdeflate($str);
    $str = convert_uuencode($str);
    $uu_str .= str_replace("\n", "\r\n", $str);
    $uu_str .= "end\r\n";
    $message = $uu_str;
    $userid = get_admin_userid($db); // get an admin user
    $group = get_config($db, 'extset_group');
    $groupid = group_by_name($db, $group);
    $subject = $basename;
    $poster = 'URD daemon';
    $email = 'urd@urd.com';
    $ug = new urdd_group;
    $ug->check_group_subscribed($db, $groupid);

    $post_id = $db->insert_query('post_messages',
        array('userid', 'groupid', 'subject', 'poster_id', 'poster_name', 'message'),
        array($userid, $groupid, $subject, $email, $poster, $message), TRUE);
    try {
        $uc = new urdd_client($db, get_config($db, 'urdd_host'), get_config($db, 'urdd_port'), $userid);
        $uc->post_message($post_id);
        $uc->disconnect();
    } catch (exception $e) {
        write_log('Error connecting back to URDD: ' . $e->GetMessage(), LOG_ERR);
    }
}

function do_sendsetinfo(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    write_log('Sending extsetdata', LOG_INFO);
    $use_newsgroup = FALSE;

    // Prepare querys for stuff to be sent:
    $sql[1]  = 'groups."name" AS groupname, extsetdata."setID", extsetdata."name", extsetdata."value", extsetdata."type" FROM extsetdata ';
    $sql[1] .= 'LEFT JOIN setdata ON extsetdata."setID" = setdata."ID" LEFT JOIN groups ON setdata."groupID" = groups."ID" ';
    $sql[1] .= 'WHERE extsetdata."committed" = ? AND extsetdata."name" != ? AND extsetdata."type" = ?';
    $input_arr[1] = array(ESI_NOT_COMMITTED, 'setname', USERSETTYPE_GROUP);

    $sql[2]  = 'rss_urls."url" AS groupname, extsetdata."setID", extsetdata."name", extsetdata."value", extsetdata."type" FROM extsetdata ';
    $sql[2] .= 'LEFT JOIN rss_sets ON extsetdata."setID" = rss_sets."setid" LEFT JOIN rss_urls ON rss_sets."rss_id" = rss_urls."id" ';
    $sql[2] .= 'WHERE extsetdata."committed" = ? AND extsetdata."name" != ? AND extsetdata."type" = ?';
    $input_arr[2] = array(ESI_NOT_COMMITTED, 'setname', USERSETTYPE_RSS);
 
    $sql[3]  = 'extsetdata."setID", extsetdata."name", extsetdata."value", extsetdata."type" FROM extsetdata ';
    $sql[3] .= 'LEFT JOIN spots ON extsetdata."setID" = spots."spotid" ';
    $sql[3] .= 'WHERE extsetdata."committed" = ? AND extsetdata."name" != ? AND extsetdata."type" = ?';
    $input_arr[3] = array(ESI_NOT_COMMITTED, 'setname', USERSETTYPE_SPOT);

    $sql[4]  = 'groups."name" AS groupname, merged_sets."old_setid" AS "setID", \'MERGE_SET\' AS "name", merged_sets."new_setid" AS value, merged_sets."type" FROM merged_sets ';
    $sql[4] .= 'LEFT JOIN setdata ON merged_sets."new_setid" = setdata."ID" LEFT JOIN groups ON setdata."groupID" = groups."ID" ';
    $sql[4] .= 'WHERE merged_sets."committed" = ? AND merged_sets."type" = ?';
    $input_arr[4] = array(ESI_NOT_COMMITTED, USERSETTYPE_GROUP);

    $sql_cnt[1]  = 'COUNT(*) AS "counter" FROM extsetdata ';
    $sql_cnt[1] .= 'LEFT JOIN setdata ON extsetdata."setID" = setdata."ID" LEFT JOIN groups ON setdata."groupID" = groups."ID" ';
    $sql_cnt[1] .= 'WHERE extsetdata."committed" = ? AND extsetdata."name" != ? AND extsetdata."type" = ?';

    $sql_cnt[2]  = 'COUNT(*) AS "counter" FROM extsetdata ';
    $sql_cnt[2] .= 'LEFT JOIN rss_sets ON extsetdata."setID" = rss_sets."setid" LEFT JOIN rss_urls ON rss_sets."rss_id" = rss_urls."id" ';
    $sql_cnt[2] .= 'WHERE extsetdata."committed" = ? AND extsetdata."name" != ? AND extsetdata."type" = ?';

    $sql_cnt[3]  = 'COUNT(*) AS "counter" FROM extsetdata ';
    $sql_cnt[3] .= 'LEFT JOIN spots ON spots."spotid" = extsetdata."setID" ';
    $sql_cnt[3] .= 'WHERE extsetdata."committed" = ? AND extsetdata."name" != ? AND extsetdata."type" = ?';

    $sql_cnt[4]  = 'COUNT(*) AS "counter" FROM merged_sets ';
    $sql_cnt[4] .= 'LEFT JOIN setdata ON merged_sets."new_setid" = setdata."ID" LEFT JOIN groups ON setdata."groupID" = groups."ID" ';
    $sql_cnt[4] .= 'WHERE merged_sets."committed" = ? AND merged_sets."type" = ?';

    $res1 = $db->select_query($sql_cnt[1], $input_arr[1]);
    $sql_arr[0] = array($sql[1], $res1[0]['counter'], $input_arr[1]);
    $res2 = $db->select_query($sql_cnt[2], $input_arr[2]);
    $sql_arr[1] = array($sql[2], $res2[0]['counter'], $input_arr[2]);
    $res3 = $db->select_query($sql_cnt[3], $input_arr[3]);
    $sql_arr[2] = array($sql[3], $res3[0]['counter'], $input_arr[3]);
    $res4 = $db->select_query($sql_cnt[3], $input_arr[3]);
    $sql_arr[3] = array($sql[4], $res3[0]['counter'], $input_arr[4]);
    if ($res1 === FALSE && $res2 === FALSE && $res3 === FALSE && $res4 === FALSE) {
        return NO_ERROR;
    }
    if (get_config($db, 'extset_group') != '') {
        $use_newsgroup = TRUE;
    }
    $counter = 0;
    $total = $sql_arr[0][1] + $sql_arr[1][1] + $sql_arr[2][1]+ $sql_arr[3][1];;
    $step = 100;
    foreach ($sql_arr as $sql) {
        $cnt = $sql[1];
        if ($cnt == 0) {
            write_log('No extsetdata to send.', LOG_INFO);
            continue;
        }
        for($start = 0; $start <= $cnt; $start += $step) {
            $setinfo = $db->select_query($sql[0], $step, $start, $sql[2]);
            if (!is_array($setinfo)) {
                break;
            }
            if ($use_newsgroup) {
                write_setinfo($db, $setinfo);
            }
            update_queue_status($db, $item->get_dbid(), QUEUE_RUNNING, 0, floor(100 * (($counter + $step + $start) / $total)), "Done, sent $counter tags.");
        }
        $counter += $sql[1];
    }

    // Finish up:
    write_log('Extsetdata sending succeeded.', LOG_INFO);
    $res = $db->update_query_2('extsetdata', array('committed'=>ESI_COMMITTED), '"committed"=?', array(ESI_NOT_COMMITTED));
    $res = $db->update_query_2('merged_sets', array('committed'=>ESI_COMMITTED), '"committed"=?', array(ESI_NOT_COMMITTED));
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, "Done, sent $counter tags.");

    return NO_ERROR;
}

function load_extset_data(DatabaseConnection $db, URD_NNTP &$nzb, array $extset_headers)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $setidarray = array();
    $counter = 0;
    foreach ($extset_headers as $msg) {
        try {
            $msg_id = $msg;
            $art = $nzb->get_article($msg_id);
            $art = extset_uudecode($art);
            if ($art === FALSE) {
                continue;
            }
            $art = @gzinflate($art);
            if ($art == '') {
                continue;
            }
            $xml = new urd_xml_reader();
            $xml->init_string($art);
            $extset_data = $xml->read_extset_data();
            if ($extset_data === FALSE) {
                continue;
            }
            $counter += store_extset_data($db, $extset_data, $setidarray);
        } catch (exception $e) {
            write_log($e->getMessage(), LOG_ERR);
        }
    }
    write_log("Downloaded $counter tags", LOG_INFO);
    regenerate_setnames($db, $setidarray);
}

function extset_uudecode(array $art)
{
    $str = '';
    foreach ($art as $line) {
        if (isset($line[0]) && $line[0] == 'M') {
            $str .= $line . "\n";
        }
    }
    $res = convert_uudecode($str);

    return $res;
}
