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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_extsetdata.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathued = realpath(dirname(__FILE__));

require_once "$pathued/../functions/autoincludes.php";
require_once "$pathued/../functions/defines.php";
require_once "$pathued/../config.php";
require_once "$pathued/../functions/functions.php";
require_once "$pathued/urdd_command.php";
require_once "$pathued/urdd_protocol.php";
require_once "$pathued/urdd_error.php";
require_once "$pathued/../functions/urd_log.php";
require_once "$pathued/../functions/mail_functions.php";

function store_extset_data(DatabaseConnection $db, array $result, array &$setidarray)
{
    $counter = 0;
    foreach ($result as $row) {
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

            $db->escape($setid, TRUE);
            $db->escape($name, TRUE);
            $db->escape($value, TRUE);
            $db->escape($type, TRUE);

            // Insert or update?
            $sql = "\"setID\" FROM extsetdata WHERE \"setID\" = $setid AND \"name\" = $name AND \"type\" = $type";
            $res = $db->select_query($sql, 1);

            if (is_array($res)) {// Exists, so update:
                $sql = "UPDATE extsetdata SET \"value\" = $value WHERE \"setID\" = $setid AND \"name\" = $name AND \"type\" = $type";
            } else {
                $sql = "INSERT INTO extsetdata (\"setID\", \"name\", \"value\", \"committed\", \"type\") VALUES ($setid, $name, $value, 1, $type)";
            }
            $db->execute_query($sql);
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

        check_group_subscribed($db, $groupid);
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
    $status = QUEUE_FINISHED;
    update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

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
        $xml->startElement('groupname');
        $xml->text($set['groupname']);
        $xml->endElement(); // groupname
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
    check_group_subscribed($db, $groupid);

    $post_id = $db->insert_query('post_messages',
        array('userid', 'groupid', 'subject', 'poster_id', 'poster_name', 'message'),
        array($userid, $groupid, $subject, $email, $poster, $message), TRUE);
    try {
        $uc = new urdd_client($db, get_config($db, 'urdd_host'), get_config($db, 'urdd_port'), $userid);
        $uc->post_message($post_id);
        $uc->disconnect();
    } catch (exception $e) {
        write_log('Error connecting back to URDD: ' .  $e->GetMessage(), LOG_ERR);
    }
}

function do_sendsetinfo(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    write_log('Sending extsetdata', LOG_INFO);
    $use_newsgroup = FALSE;

    // Prepare querys for stuff to be sent:
    $sql1  = "groups.\"name\" AS groupname, extsetdata.\"setID\", extsetdata.\"name\", extsetdata.\"value\", extsetdata.\"type\" FROM extsetdata ";
    $sql1 .= "LEFT JOIN setdata ON extsetdata.\"setID\" = setdata.\"ID\" LEFT JOIN groups ON setdata.\"groupID\" = groups.\"ID\" ";
    $sql1 .= "WHERE extsetdata.\"committed\" = 0 AND extsetdata.\"name\" != 'setname' AND extsetdata.\"type\" = '" . USERSETTYPE_GROUP . "'";

    $sql2  = "rss_urls.\"url\" AS groupname, extsetdata.\"setID\", extsetdata.\"name\", extsetdata.\"value\", extsetdata.\"type\" FROM extsetdata ";
    $sql2 .= "LEFT JOIN rss_sets ON extsetdata.\"setID\" = rss_sets.\"setid\" LEFT JOIN rss_urls ON rss_sets.\"rss_id\" = rss_urls.\"id\" ";
    $sql2 .= "WHERE extsetdata.\"committed\" = 0 AND extsetdata.\"name\" != 'setname' AND extsetdata.\"type\" = '" . USERSETTYPE_RSS . "'";

    $sql3  = "groups.\"name\" AS groupname, merged_sets.\"old_setid\" AS \"setID\", 'MERGE_SET' AS \"name\", merged_sets.\"new_setid\" AS value, merged_sets.\"type\" FROM merged_sets ";
    $sql3 .= "LEFT JOIN setdata ON merged_sets.\"new_setid\" = setdata.\"ID\" LEFT JOIN groups ON setdata.\"groupID\" = groups.\"ID\" ";
    $sql3 .= "WHERE merged_sets.\"committed\" = 0 AND merged_sets.\"type\" = '" . USERSETTYPE_GROUP . "'";

    $sql_cnt1  = "COUNT(*) AS \"counter\" FROM extsetdata ";
    $sql_cnt1 .= "LEFT JOIN setdata ON extsetdata.\"setID\" = setdata.\"ID\" LEFT JOIN groups ON setdata.\"groupID\" = groups.\"ID\" ";
    $sql_cnt1 .= "WHERE extsetdata.\"committed\" = 0 AND extsetdata.\"name\" != 'setname' AND extsetdata.\"type\" = '" . USERSETTYPE_GROUP . "'";

    $sql_cnt2  = "COUNT(*) AS \"counter\" FROM extsetdata ";
    $sql_cnt2 .= "LEFT JOIN rss_sets ON extsetdata.\"setID\" = rss_sets.\"setid\" LEFT JOIN rss_urls ON rss_sets.\"rss_id\" = rss_urls.\"id\" ";
    $sql_cnt2 .= "WHERE extsetdata.\"committed\" = 0 AND extsetdata.\"name\" != 'setname' AND extsetdata.\"type\" = '" . USERSETTYPE_RSS . "'";

    $sql_cnt3  = "COUNT(*) AS \"counter\" FROM merged_sets ";
    $sql_cnt3 .= "LEFT JOIN setdata ON merged_sets.\"new_setid\" = setdata.\"ID\" LEFT JOIN groups ON setdata.\"groupID\" = groups.\"ID\" ";
    $sql_cnt3 .= "WHERE merged_sets.\"committed\" = 0  AND merged_sets.\"type\" = '" . USERSETTYPE_GROUP . "'";

    $res1 = $db->select_query($sql_cnt1);
    $sql_arr[0] = array($sql1, $res1[0]['counter']);
    $res2 = $db->select_query($sql_cnt2);
    $sql_arr[1] = array($sql2, $res2[0]['counter']);
    $res3 = $db->select_query($sql_cnt3);
    $sql_arr[2] = array($sql3, $res3[0]['counter']);
    if ($res1 === FALSE && $res3 === FALSE && $res2 === FALSE) {
        return NO_ERROR;
    }
//    $prefs = load_config($db);

    if (get_config($db, 'extset_group') != '') {
        $use_newsgroup = TRUE;
    }
    $counter = 0;
    $total = $sql_arr[0][1] + $sql_arr[1][1] + $sql_arr[2][1];
    $status = QUEUE_RUNNING;
    $step = 100;
    foreach ($sql_arr as $sql) {
        $start = 0;
        $cnt = $sql[1];
        if ($cnt == 0) {
            write_log('No extsetdata to send.', LOG_INFO);
            continue;
        }
        while ($start <= $cnt) {
            $setinfo = $db->select_query($sql[0], $step, $start);
            if (!is_array($setinfo)) {
                break;
            }
            if ($use_newsgroup) {
                write_setinfo($db, $setinfo);
            }
            $start += $step;
            update_queue_status($db, $item->get_dbid(), $status, 0, floor(100 * (($counter+$start)/$total)) , "Done, sent $counter tags.");
        }
        $counter += $sql[1];
    }

    // Finish up:
    write_log('Extsetdata sending succeeded.', LOG_INFO);
    $sql = 'UPDATE extsetdata SET "committed" = 1 WHERE "committed" = 0';
    $res = $db->execute_query($sql);
    $sql = 'UPDATE merged_sets SET "committed" = 1 WHERE "committed" = 0';
    $res = $db->execute_query($sql);

    $status = QUEUE_FINISHED;
    update_queue_status($db, $item->get_dbid(), $status, 0, 100, "Done, sent $counter tags.");

    return NO_ERROR;
}

function load_extset_data(DatabaseConnection $db, URD_NNTP &$nzb, array $extset_headers)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $setidarray = array();
    $counter = 0;
    foreach ($extset_headers as $msg) {
        try {
            $msg_id = $msg ;
            $art = $nzb->get_article($msg_id);
            $art = extset_uudecode($art);
            if ($art === FALSE) {
                continue;
            }
            $art = gzinflate($art);
            if ($art == '') {
                continue;
            }
            $xml = new urd_xml_reader();
            $xml->init_string($art);
            $extset_data = $xml->read_extset_data();
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
