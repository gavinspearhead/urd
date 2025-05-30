<?php
/**
 *  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2014-06-08 18:21:08 +0200 (zo, 08 jun 2014) $
 * $Rev: 3088 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: do_functions.php 3088 2014-06-08 16:21:08Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function get_url_contents($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "URD/1.0 (foot@gmail.com)");
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}


function parse_nzb(DatabaseConnection $db, SimpleXMLElement $xml, $dlid)
{
    assert(is_numeric($dlid));
    $cols = array('downloadID', 'groupID', 'partnumber', 'name', 'status', 'messageID', 'binaryID', 'size');
    $total_size = $count = 0;
    if (!is_null($xml->head->meta)) { 
        foreach ($xml->head->meta as $k => $v) {
            if (strtolower($v['type']) == 'password') {
                $password = $v->__toString();
                set_download_password($db, $dlid, $password);
            }
        }
    }
    $db->start_transaction();

    foreach ($xml as $section) {
        try {
            $group = (string) $section->groups->group[0];
            $groupid = group_by_name($db, $group);
        } catch (exception $e) {
            $groupid = 0;
        }
        $name = (string) $section['subject'];
        $cleansubject = utf8_encode($name);
        $poster = (string) $section['poster'];
        $binaryID = create_binary_id($cleansubject, $poster);
        if (!is_a($section->segments->segment, 'SimpleXMLElement')) {
            echo_debug('No proper NZB segment found', DEBUG_SERVER);
            continue;
        }
        $vals = [];
        $ts = microtime(true);
        foreach ($section->segments->segment as $segment) {
            $messageID = (string) $segment;
            $segment_attr = $segment->attributes();
            $size = (int)($segment_attr['bytes']);
            $part_number = (int)($segment_attr['number']);
            $total_size += $size;

            $vals[] = array($dlid, $groupid, $part_number, $cleansubject, DOWNLOAD_READY, $messageID, $binaryID, $size);
            if ($count == 0 && get_download_name($db, $dlid) == '') {
                $dlname = find_name($db, $name);
                set_download_name($db, $dlid, $dlname);
            }
            ++$count;
        }
        //var_dump(1, microtime(true) - $ts);
        //$ts = microtime(true);
        $db->insert_query('downloadarticles', $cols, $vals);
        //var_dump(2, microtime(true) - $ts);
        //var_dump(count($vals));
    }

    $db->commit_transaction();
    return array($count, $total_size);
}

function do_parse_nzb(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $args = $item->get_args();
    $arg_list = split_args($args);
    $url = $arg_list[1];
    $dlid = $arg_list[0];
    $dlname = basename($url, '.nzb');
    $dlname = find_name($db, $dlname);
    if (get_download_name($db, $dlid) == '') {
        if (strlen($dlname) <= 5 || is_numeric($dlname)) {
            $dlname = 'NZB imported download ' . time();
        }
        set_download_name($db, $dlid, $dlname);
    }
    list($dl_dir) = get_dl_dir($db, $dlid);
    if ($dl_dir == '') {
        // if it's spooled or from the web interface, these settings are untouched as yet.
        $add_setname = get_pref($db, 'add_setname', $item->get_userid());
        set_dl_dir($db, $dlid, '', $add_setname);
    }
    if (get_download_articles_count($db, $dlid) == 0) {
        set_download_size($db, $dlid, 0);
    }

    $count = $totalsize = $fs = 0;
    try {
	    libxml_use_internal_errors(TRUE);
	    $contents = get_url_contents($url);
	    $nzb_file = new SimpleXMLElement($contents, LIBXML_PARSEHUGE, FALSE);
	 //	$nzb_file = @(new SimpleXMLElement($url, LIBXML_PARSEHUGE, TRUE));
        if ($nzb_file === NULL) {
            $msg = implode("\n", libxml_get_errors());
            throw new exception($msg);
        }
        list($count, $totalsize) = parse_nzb($db, $nzb_file, $dlid);
        $fs = strlen($nzb_file->asXML());
    } catch (exception $e) {
        write_log('Could not parse NZB 1: ' . $e->getMessage());
        $count = 0;
    }

    add_download_size($db, $dlid, $totalsize);
    add_stat_data($db, stat_actions::IMPORTNZB, $fs, $item->get_userid());
    if ($count == 0) {
        $dlcomment = 'error_nzbfailed';
        update_dlinfo_comment($db, $dlid, $dlcomment);
        update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, 0, 100, 'Imported NZB failed: no articles found');
        $retval = FILE_NOT_FOUND;
    } else {
        update_queue_status($db, $item->get_dbid(),QUEUE_FINISHED, 0, 100, 'Imported NZB file');
        $retval = NO_ERROR;
    }
    $dlpath = get_dlpath($db) . SPOOL_PATH;
    if (substr($url, 0, strlen($dlpath)) == $dlpath && substr($url, -11) == '.processing') {
        // in the spool directory we rename the file so it isn't processed again.
        $new_name = find_unique_name(substr($url, 0, -11), '', '', '.processed', TRUE);
        rename($url, $new_name);
    }
    dec_dl_lock($db, $dlid);
    if (check_last_lock($db, $dlid)) {// we also set a lock on create so we need to remove that too
        dec_dl_lock($db, $dlid);
    }

    return $retval;
}

function write_binary(DatabaseConnection $db, $binaryid, $groupid, $total_parts, $subject, $file, $dlid)
{
    assert(is_resource($file) && is_numeric($groupid) && is_numeric($dlid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $res = $db->select_query('"active" FROM grouplist WHERE "ID"=:id', 1, array(':id'=>$groupid));
    if ($res === FALSE || $res[0]['active'] != newsgroup_status::NG_SUBSCRIBED) {
        $res = FALSE;
    } else {
        $sql = "*, \"date\" AS unixdate FROM binaries_$groupid WHERE \"binaryID\"=:id";
        $res = $db->select_query($sql, array(':id' => $binaryid));
    }
    try {
        $group = group_name($db, $groupid);
    } catch (exception $e) {
        $group = '';
    }
    if ($res === FALSE) {
        $bin_data['subject'] = $subject;
        $bin_data['totalParts'] = $total_parts;
        $bin_data['unixdate'] = time();
    } else {
        $bin_data = $res[0];
    }

    $total_parts = $bin_data['totalParts'];
    $date = $bin_data['unixdate'];
    $name = preg_replace("/[^a-zA-Z0-9\(\)\! .]/", '', str_replace('"', '', $bin_data['subject']));
    $str = "\t<file poster=\"who@no.com\" date=\"$date\" subject=\"$name (1/{$total_parts})\">\n";
    $str .= "\t<groups>\n";
    $str .= "\t\t<group>{$group}</group>\n";
    $str .= "\t</groups>\n";
    $str .= "\t<segments>\n";
    $sql = '"ID", "messageID", "partnumber", "size" FROM downloadarticles WHERE "binaryID"=:binid AND "downloadID"=:dlid ORDER BY "partnumber"';
    $res2 = $db->select_query($sql, array(':binid'=>$binaryid, ':dlid'=>$dlid));
    if ($res2 === FALSE) {
        $res2 = array();
    }
    foreach ($res2 as $arr2) {
        $messageID = $arr2['messageID'];
        $partnumber = $arr2['partnumber'];
        $messageID = str_replace('&', '&amp;', $messageID);
        $size = $arr2['size'];
        $str .= "\t\t<segment bytes=\"$size\" number=\"" . round($partnumber) . "\">$messageID</segment>\n";
    }
    update_batch($db, $res2, DOWNLOAD_COMPLETE);
    $str .= "\t</segments>\n";
    $str .= "\t</file>\n";

    return fwrite ($file, $str);
}

function do_make_nzb(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $dlid = $item->get_args();
    if (check_dl_lock($db, $dlid) === FALSE) { // if db still locked
        echo_debug('Dl still locked, sleeping', DEBUG_SERVER); // todo needs fixing

        return DB_LOCKED;
    }
    $compression = (bool) get_config($db, 'compress_nzb');
    $dlpath = $item->get_dlpath();
    $file_name = get_download_name($db, $dlid);
    $file_name = sanitise_download_name($db, $file_name);
    $nzb_file = find_unique_name($dlpath, '', $file_name, '.nzb', TRUE);
    $basename = $file_name;
    $fn = $nzb_file;
    $ext = '.nzb';
    try {
        if ($compression === TRUE) {
            $gzip = get_config($db, 'gzip_path');
            $gzip_pars = get_config($db, 'gzip_pars');
            if ($gzip == '' || !file_exists($gzip)) {
                write_log("File not found $gzip", LOG_ERR);
                $compression = FALSE;
            }
        }
    } catch (exception $e) {
        $compression = FALSE;
        write_log($e->getMessage(), LOG_ERR);
    }
    if ($compression === TRUE) {
        $cmd = "/bin/sh -c '$gzip $gzip_pars ' ";
        $zip_file = find_unique_name($dlpath, '', $file_name, '.nzb.gz', TRUE);
        $fn = $zip_file;
        $ext = '.nzb.gz';
        $descriptorspec = array (
            0 => array('pipe', 'r'), // where we will write to
            1 => array('file', $zip_file, 'w'), // we don't want the output
            2 => array('file', '/dev/null', 'w') // or the errors
        );
        $pipes = array();
        $process = proc_open($cmd, $descriptorspec, $pipes, $dlpath, NULL, array('binary_pipes'));
        $file = $pipes[0];
    } else {
        $file = fopen($nzb_file, 'w+');
    }
    if ($file === FALSE) {
        throw new exception ('Could not create file: ' . $nzb_file, FILE_NOT_CREATED);
    }
    $size = 0;
    $str = '<' . '?' . 'xml version="1.0" encoding="us-ascii"' . '?' . '>' . "\n"; // screws up syntax highlighting... hence the wacky sequence of < and ? and ? and > ... DO NOT CHANGE!
    $str .= '<!DOCTYPE nzb PUBLIC "-//newzBin//DTD NZB 1.0//EN" "http://www.newzbin.com/DTD/nzb/nzb-1.0.dtd">'. "\n";
    $str .= '<nzb xmlns="http://www.newzbin.com/DTD/2003/nzb">' . "\n"; // Note this URL is dead!
    $str .= '<!-- Created by ' . urd_version::get_urd_name() . ' ' . urd_version::get_version() . ' : http://www.urdland.com : The web-based usenet resource downloader. -->' . "\n";
    $size += fwrite($file, $str);
    try {
        $query = '"binaryID", count(*) AS cnt, max("groupID") AS "groupID", max("name") AS subject FROM downloadarticles WHERE "downloadID"=:dlid GROUP BY "binaryID" ORDER BY "binaryID"';
        $res = $db->select_query($query, array(':dlid'=>$dlid));
        if ($res === FALSE) {
            throw new exception('Could not find any articles', INTERNAL_FAILURE);
        }
        $total_count = count($res);
        $counter = 0;
        foreach ($res as $binary) {
            $size += write_binary($db, $binary['binaryID'], $binary['groupID'], $binary['cnt'], $binary['subject'], $file, $dlid);
            $counter++;
            update_queue_status($db, $item->get_dbid(), QUEUE_RUNNING, 0, floor(($counter / $total_count) * 100));
        }
    } catch (exception $e) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100);
        if ($e->getCode() == ERR_GROUP_NOT_FOUND) {
            return GROUP_NOT_FOUND;
        } else {
            write_log($e->getMessage(), LOG_ERR);

            return UNKNOWN_ERROR;
        }
    }

    $str = "</nzb>\n";
    $size += fwrite($file, $str);
    if ($compression === TRUE) {
        pclose($file);
        proc_close($process);
    } else {
        fclose($file);
    }
    $userid = $item->get_userid();
    $done = move_file_to_nzb($db, $dlid, $fn, $dlpath, $basename, $ext, $userid);
    set_permissions($db, $done); // setting permissions must be last otherwise we may not be able to move the file
    set_group($db, $done);

    update_queue_status($db, $item->get_dbid(),QUEUE_FINISHED, 0, 100, "Created NZB file $basename$ext");
    cleanup_download_articles($db, $dlid);
    add_stat_data($db, stat_actions::GETNZB, $size, $userid);

    return NO_ERROR;
}

function create_make_nzb(DatabaseConnection $db, server_data &$servers, $userid, $priority)
{
    assert (is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $dl_path_basis = get_dlpath($db);
    $username = get_username($db, $userid);
    $dl_path = $dl_path_basis . TMP_PATH . $username . DIRECTORY_SEPARATOR;
    clearstatcache();
    if (!is_dir($dl_path)) {
        $rv = @mkdir($dl_path, 0775, TRUE);
        if ($rv === FALSE) {
            write_log("Failed to create directory $dl_path", LOG_ERR);

            return urdd_protocol::get_response(405);
        }
    }
    if (!is_writeable($dl_path)) {
        $rv = @chmod($dl_path, 0775); // sometimes mkdir doesn't set the perms correctly (due to umask??), make sure it is set correctly now
        if ($rv === FALSE) {
            write_log("Can't chmod directory: $dl_path", LOG_ERR);
        }
    }

    if (!is_writable($dl_path)) {
        write_log("Download directory is not writable: $dl_path", LOG_ERR);

        return urdd_protocol::get_response(405);
    }
    $download_par_files = get_pref($db, 'download_par', $userid) ? TRUE : FALSE;
    $id = add_download($db, $userid, 0, 0, 0, 0, DOWNLOAD_READY, '', download_types::NZB, TRUE, $download_par_files);
    $item = new action(urdd_protocol::COMMAND_MAKE_NZB, $id, $userid, TRUE);
    $item->set_dlpath($dl_path);
    set_download_dir($db, $id, $dl_path);
    $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
    if ($res === FALSE) {
        throw new exception_queue_failed('Could not queue item');
    }
    update_queue_norestart($db, $res);
    $id_str = "[{$item->get_id()}] ";

    return sprintf(urdd_protocol::get_response(210), $id, $id_str);
}

function do_priority(DatabaseConnection $db, array $arg_list, server_data &$servers, $userid)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert (is_numeric($userid));

    if (!isset($arg_list[0], $arg_list[1])) {
        return urdd_protocol::get_response(501);
    }
    $priority = $arg_list[1];
    $id = $arg_list[0];
    if (!is_numeric($priority) || !is_numeric($id) || $priority <= 1) {
        return urdd_protocol::get_response(501);
    }
    try {
        $servers->queue_set_priority($db, $id, $userid, $priority);

        return urdd_protocol::get_response(200);
    } catch (exception $e) {
        return urdd_protocol::get_response(501);
    }
}

function regenerate_setnames(DatabaseConnection $db, array $setidarray)
{
    $sql = '"name", "value", "type" FROM extsetdata WHERE "setID"=:setid';
    foreach ($setidarray as $setid => $foo) {
        $res = $db->select_query($sql, array(':setid'=>$setid));
        if ($res === FALSE) {
            continue;
        }
        // Convert to proper format:
        $type = $res[0]['type'];
        $namevalues = array();
        foreach ($res as $row) {
            $namevalues[$row['name']] = $row['value'];
        }
        $setname = urd_extsetinfo::generate_set_name($db, $namevalues);

        // Setname in db?
        if (isset($namevalues['setname'])) {
            $db->update_query_2('extsetdata', array('value'=>$setname), '"setID"=? AND "name"=? AND "type"=?', array($setid, 'setname', $type));
        } else {
            $db->insert_query('extsetdata', array('setID', 'name','value', 'committed', 'type'), array($setid, 'setname', $setname, 1, $type));
        }
    }
}

function update_group_timestamp(DatabaseConnection $db, $name, $timestamp)
{
    assert(is_numeric($timestamp));
    $like = $db->get_pattern_search_command('LIKE');
    $db->update_query_2('grouplist', array('extset_update' => $timestamp), "\"name\" $like ?", array($name));
}

function update_feed_timestamp(DatabaseConnection $db, $name, $timestamp)
{
    assert(is_numeric($timestamp));
    $db->update_query_2('rss_urls', array('extset_update' => $timestamp), "\"url\" $like ?", array($name));
}

function do_check_version(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $version = file_get_contents(VERSION_CHECK_URL. '?version=' . urd_version::get_version());
    list ($vstr) = explode("\n", $version, 2);
    $rv = preg_match ('/^(\d+\.\d+\.\d+)[ \t]+(\d)+[ \t]+(.*)$/', $vstr, $matches);
    if ($rv === 0) {
        $comment = '';
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, $comment);

        return COULD_NOT_CHECK_VERSION;
    }
    $version = $matches[1];
    $type = $matches[2];
    $text = $matches[3];
    echo_debug("Version: $version\nType: $type\nText: $text", DEBUG_SERVER);
    set_pref($db, 'update_version', $version, 0);
    set_pref($db, 'update_type', $type, 0);
    set_pref($db, 'update_text', $text, 0);
    $comment = "Newest version is $version";
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, NULL, 100, $comment);
    write_log($comment, LOG_INFO);

    return NO_ERROR;
}

function update_rss_status(DatabaseConnection $db, $id, $last_updated)
{
    assert(is_numeric($id) && is_numeric($last_updated));
    $db->update_query_2('rss_urls', array('last_updated'=>$last_updated), '"id"=?', array($id));
}

function do_update_rss(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    $name = '';
    try {
        $rss = new urdd_rss($db);
        $args = $item->get_args();
        $rss_info = get_rss_info($db, $args);
        $name = $rss_info['name'];
        $cnt = $rss->rss_update($rss_info);
        update_rss_status($db, $args, time());
        write_log("RSS Update $name: $cnt new sets", LOG_NOTICE);
        add_stat_data($db, stat_actions::RSS_COUNT, $cnt, user_status::SUPER_USERID);
        update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, NULL, 100);
        sets_marking::mark_interesting($db, USERSETTYPE_RSS, $args);
    } catch (exception $e) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, $e->getMessage());
        write_log("RSS Update $name failed: " .$e->getmessage(), LOG_ERR);

        return FEED_NOT_FOUND;
    }

    return NO_ERROR;
}

function update_headers(DatabaseConnection $db, URD_NNTP &$nzb, array &$groupArr, action $item, $server_id)
{
    assert(is_numeric($server_id));
    $groupArr['compressed_headers'] = get_compressed_headers($db, $server_id); // ugly code really
    write_log("Updating group {$groupArr['name']}", LOG_INFO);
    $rv = $nzb->update_newsgroup($groupArr, $item);
    if ($rv === ERR_GROUP_NOT_FOUND) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100);

        return GROUP_NOT_FOUND;
    }
    if ($rv !== NO_ERROR) {
        $nzb->disconnect();

        return NNTP_NOT_CONNECTED_ERROR;
    }

    return NO_ERROR;
}

function do_update(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);

    $args = $item->get_args();
    try {
        $groupArr = get_group_info($db, $args);
    } catch (exception $e) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'Group not found');
        throw new exception('Error: group not found', ERR_GROUP_NOT_FOUND);
    }
    try {
        $server_id = $item->get_active_server();
        if ($server_id == 0) {
            $server_id = $item->get_preferred_server();
        }
        if ($server_id <= 0) {
            update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, NULL, 100, 'No server enabled');
            throw new exception('Error: no server found', ERR_NO_ACTIVE_SERVER);
        }
        $nzb = connect_nntp($db, $server_id);
        $rv = update_headers($db, $nzb, $groupArr, $item, $server_id);

        if ($rv != NO_ERROR) {
            return $rv;
        }

        $extset_headers = $nzb->get_extset_headers();
        $nzb->reset_extset_headers();
        if (count($extset_headers) > 0) {
            load_extset_data($db, $nzb, $extset_headers);
        }
        $nzb->disconnect();
        $ug = new urdd_group($db);
        $ug->update_postcount($groupArr['ID']);
        update_queue_status($db, $item->get_dbid(),QUEUE_FINISHED, 0, 100);
    } catch (exception $e) {
        write_log('Update failed ' . $e->getMessage(), LOG_WARNING);
        if ($e->getcode() == ERR_NNTP_AUTH_FAILED) {
            $comment = $e->getMessage();
            update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, 0, 100, $comment);

            return NNTP_AUTH_ERROR;
        } elseif ($e->getcode() == ERR_GROUP_NOT_FOUND) {
            return GROUP_NOT_FOUND;
        } elseif ($e->getcode() == ERR_NO_ACTIVE_SERVER) {
            return CONFIG_ERROR;
        } else {
            return NNTP_NOT_CONNECTED_ERROR;
        }
    }

    return NO_ERROR;
}

function do_listupdate(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    $rv = NO_ERROR;
    $comment = '';
    try {
        $server_id = $item->get_active_server();
        if ($server_id == 0) {
            $server_id = $item->get_preferred_server();
        }
        $nzb = connect_nntp($db, $server_id);
        list($update, $insert) = $nzb->update_group_list();
        $nzb->disconnect();
        $comment = "$update updated and $insert new groups";
        $status = QUEUE_FINISHED;
        $rv = NO_ERROR;
    } catch (exception $e) {
        write_log('connection failed', LOG_ERR);
        $status = QUEUE_FAILED;
        $rv = NNTP_NOT_CONNECTED_ERROR;
    }
    update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

    return $rv;
}

function do_gensets(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    $args = $item->get_args();
    $do_expire = (get_config($db, 'auto_expire') == 1);
    try {
        $groupArr = get_group_info($db, $args);
    } catch (exception $e) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'Group not found');
        throw new exception('Error: group not found', ERR_GROUP_NOT_FOUND);
    }
    $groupname = $groupArr['name'];
    $minsetsize = $groupArr['minsetsize'];
    $maxsetsize = $groupArr['maxsetsize'];
    $groupid = $groupArr['ID'];
    $expire = time() - ($groupArr['expire'] * 24 * 3600);
    try {
        $ug = new urdd_group($db);
        $ug->update_binary_info($args, $groupname, $do_expire, $expire, $item, $minsetsize, $maxsetsize);
        $old_setcount = get_sets_count_group($db, $groupid);
        $setcount = count_sets_group($db, $groupid);
        $new_sets = $setcount - $old_setcount;
        if ($new_sets < 0) {
            $new_sets = 0;
        }
        add_stat_data($db, stat_actions::SET_COUNT, $new_sets, user_status::SUPER_USERID);
        update_group_setcount($db, $groupid, $setcount);
    } catch (exception $e) {
        write_log('Update Binary info failed: ' . $e->getMessage(), LOG_ERR);
    }
    update_queue_status($db, $item->get_dbid(), NULL, 0, 99, 'Marking sets');
    try {
        sets_marking::mark_interesting($db, USERSETTYPE_GROUP, $args);
    } catch (exception $e) {
        write_log('Mark interesting failed: ' . $e->getMessage(), LOG_ERR);
    }
    update_user_last_seen_group($db, $args);
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Generated sets data complete');

    return NO_ERROR;
}

function do_purge_spots(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    // Check if group exists
    try {
        $us = new urd_spots($db);;
        $count = $us->purge_spots($item->get_dbid());
        update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, "Deleted $count spots");

        return NO_ERROR;
    } catch (exception $e) {
        $comment = $e->getMessage();
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, $comment);
        if ($e->getcode() == ERR_GROUP_NOT_FOUND) {
            return GROUP_NOT_FOUND;
        } else {
            return ERR_INTERNAL_ERROR;
        }
    }
}

function do_expire_spots(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    // Check if group exists
    $us = new urd_spots($db);
    $count = $us->expire_spots($item->get_dbid());
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, "Deleted $count spots");

    return NO_ERROR;
}

function do_expire_rss(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    $args = $item->get_args();
    // Check if group exists
    $rss = new urdd_rss($db);
    $res = feed_subscribed($db, $args);
    if ($res === FALSE) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'Feed not found');

        return FEED_NOT_FOUND;
    }
    $count = $rss->expire_rss($args, $item->get_dbid());
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, "Deleted $count articles");

    return NO_ERROR;
}

function do_expire(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    $args = $item->get_args();
    $groupid = $args;
    // Check if group exists
    $res = group_subscribed($db, $groupid);
    if ($res === FALSE) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'Group not found');

        return GROUP_NOT_FOUND;
    }
    $ug = new urdd_group($db);
    $count = $ug->expire_binaries($groupid, $item->get_dbid());
    $setcount = count_sets_group($db, $groupid);
    update_group_setcount($db, $groupid, $setcount);
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, "Deleted $count articles");

    return NO_ERROR;
}

function do_cleandb(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    $finished_status = DOWNLOAD_FINISHED;
    $failed_status = DOWNLOAD_FAILED;
    $rar_failed = DOWNLOAD_RAR_FAILED;
    $par_failed = DOWNLOAD_PAR_FAILED;
    $cksfv_failed = DOWNLOAD_CKSFV_FAILED;
    $cancelled_status = DOWNLOAD_CANCELLED;
    $post_finished_status = POST_FINISHED;
    $post_rar_failed_status = POST_RAR_FAILED;
    $post_par_failed_status = POST_PAR_FAILED;
    $post_cancelled_status = POST_CANCELLED;

    $userid = $item->get_userid();
    $isadmin = urd_user_rights::is_admin($db, $userid);
    try {
        $arg = strtolower($item->get_args());
        if ($arg == 'all') { // do not use truncate as that will reset the autoinc values too
            if ($isadmin) {
                $db->delete_query('downloadinfo');
                $db->delete_query('downloadarticles');
                $db->delete_query('queueinfo');
                $db->delete_query('postinfo');
            } else {
                $db->delete_query('downloadinfo', 'userid=:userid', array(':userid'=>$userid));
                $db->delete_query('downloadarticles', '"downloadID" NOT IN (SELECT "ID" FROM downloadinfo)');
                $db->delete_query('queueinfo', 'userid=:userid', array(':userid'=>$userid));
                $db->delete_query('postinfo', 'userid=:userid', array(':userid'=>$userid));
            }
        } elseif ($arg == 'users') {
            if ($isadmin) {
                do_clean_users($db);
            } else {
                // nothing
            }
        } else {
            // Delete all info that has occurred (ended) <clean_db_age> days ago.
            // For example, downloads that finished 3 days ago, queue messages that happened 3 days ago etc.
            if ($arg == 'now') {
                $timebased = FALSE;
                $cleandbage = 1;
            } elseif (is_numeric($arg) && $arg > 0) {
                $timebased = TRUE;
                $cleandbage = $arg * 24 * 60 * 60;
            } else {
                $timebased = TRUE;
                $cleandbage = get_config($db, 'clean_db_age') * 24 * 3600; // db age is saved in days... convert to seconds
            }
            if ($cleandbage > 0) {// 0 = disabled
                // Clean up downloadinfo:
                $input_arr = array();
                if ($timebased) {
                    $timestamp = time() - $cleandbage;
                } else {
                    $timestamp = time();
                }
                $quser2 = '';
                if (!$isadmin) {
                    $quser2 = ' AND userid=:userid ';
                    $input_arr[':userid'] = $userid;
                }
                $qry = "1=1 $quser2 AND \"start_time\" < :time AND \"status\" IN (:status1, :status2, :status3, :status4, :status5, :status6) ";
                $db->delete_query('downloadinfo', $qry, array_merge($input_arr, array(':time'=>$timestamp, ':status1'=>$finished_status, ':status2'=>$rar_failed,':status3'=> $par_failed,':status4'=> $cksfv_failed, ':status5'=>$cancelled_status, ':status6'=>$failed_status)));

                $qry = "1=1 $quser2 AND \"start_time\" < :time AND \"status\" IN (:status1, :status2, :status3, :status4) ";
                $db->delete_query('postinfo', $qry, array_merge($input_arr, array(':time'=>$timestamp, ':status1'=>$post_finished_status, ':status2'=>$post_rar_failed_status, ':status3'=>$post_par_failed_status, ':status4'=>$post_cancelled_status)));

                // Clean up queueinfo:
                $qry = "1=1 $quser2 AND \"lastupdate\" < :time AND \"status\" IN (:status1, :status2, :status3, :status4, :status5) ";
                $db->delete_query('queueinfo', $qry, array_merge($input_arr, array(':time'=> $timestamp, ':status1'=>QUEUE_FINISHED, ':status2'=>QUEUE_FAILED,':status3'=> QUEUE_CRASH,':status4'=> QUEUE_CANCELLED, ':status5' => QUEUE_REMOVED)));
            }

            // Clean downloadarticles from any lost records:
            $qry = '"downloadID" NOT IN (SELECT "ID" FROM downloadinfo)';
            $db->delete_query('downloadarticles', $qry);
        }
    } catch (exception $e) {
        write_log('Database query failed: ' . $e->getmessage(), LOG_WARNING);
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, $e->getMessage());

        return INTERNAL_FAILURE;
    }
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Cleaned Database');

    return NO_ERROR;
}

function do_group(DatabaseConnection $db, server_data &$servers, array $arg_list)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    if (!isset($arg_list[0])) {
        $response = urdd_protocol::get_response(501);
    } elseif (is_numeric($arg_list[0])) {
        $groupid = $arg_list[0];
        try {
            $response = urdd_protocol::get_response(258);
            $server_id = $servers->get_update_server();
            $nzb = connect_nntp($db, $server_id);
            list($first, $last, $count) = $nzb->get_first_last_group($groupid);
            $nzb->disconnect();
            $response .= "$first $last $count\n";
            $response .= ".\n";

        } catch (exception $e) {
            write_log($e->getMessage(), LOG_WARNING);
            $response = urdd_protocol::get_response(520);
        }
    } else {
        $response = urdd_protocol::get_response(501);
    }

    return $response;
}

function do_clean_users(DatabaseConnection $db)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $age = get_config($db, 'users_clean_age', 0);
    if ($age == 0) {
        return TRUE;
    }
    $timestamp = time() - ($age * 3600 * 24);
    $qry = 'count(*) AS "cnt" FROM users WHERE "isadmin" != :status AND "last_active" < :last_active AND "regtime" < :regtime';
    $res = $db->select_query($qry, array(':status'=> user_status::USER_ADMIN, ':last_active'=> $timestamp, ':regtime'=>$timestamp));
    $cnt = $res[0]['cnt'];
    if ($cnt > 0) {
        write_log("Deleting $cnt users", LOG_NOTICE);
        $db->delete_query('users', '"isadmin" != :isadmin AND "last_active" < :time1 AND "regtime" < :time2', array(':isadmin'=> user_status::USER_ADMIN, ':time1'=> $timestamp, ':time2'=> $timestamp));
    } else {
        write_log('No users to delete', LOG_INFO);
    }

    return TRUE;
}

function do_cleandir(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $dlpath = get_dlpath($db);
    $tmp_dir = $dlpath . TMP_PATH;
    $nzb_dir = $dlpath . NZB_PATH;
    $preview_dir = $dlpath . PREVIEW_PATH;
    $filecache_dir = $dlpath . FILELIST_CACHE_PATH;
    $age = get_config($db, 'clean_dir_age') * 24 * 3600; // db age is saved in days... convert to seconds
    $preview = $tmp = $nzb = $post = $filecache = FALSE;
    $arg = $item->get_args();
    $cnt1 = $cnt2 = $cnt3 = $cnt4 = 0;
    switch (strtolower($arg)) {
    case 'all':
        $filecache = $preview = $tmp = TRUE; // only throw away preview files and temp files, leave nzb and post untouched; only remove those if specifically asked to.
        break;
    case 'preview' :
        $preview = TRUE;
        break;
    case 'tmp':
        $tmp = TRUE;
        break;
    case 'nzb':
        $nzb = TRUE;
        break;
    case 'post':
        $post = TRUE;
        break;
    case 'filecache':
        $filecache = TRUE;
        break;
    default:
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'Incorrect argument');

        return COMMANDLINE_ERROR;
    }
    $error = '';
    try {
        if ($filecache === TRUE) {
            list ($cnt1, $error) = rmdirtree($tmp_dir, $age, FALSE);
        }
        if ($tmp === TRUE) {
            list ($cnt1, $error) = rmdirtree($tmp_dir, $age, FALSE);
        }
        if ($nzb === TRUE) {
            list ($cnt3, $error3) = rmdirtree($nzb_dir, $age, FALSE);
            $error .= "\n" .$error3;
        }
        if ($post === TRUE) {
            list ($cnt4, $error4) = rmdirtree($nzb_dir, $age, FALSE);
            $error .= "\n" .$error4;
        }
        if ($preview === TRUE) {
            list ($cnt2, $error2) = rmdirtree($preview_dir, $age, FALSE);
            $error .= "\n" .$error2;
        }
        write_log("Removed $cnt1 tmp files, $cnt3 nzb files, $cnt4 post files and $cnt2 preview files", LOG_INFO);
        $error = trim($error, "\n\r\t ");
        if ($error == '') {
            update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, NULL, 100, 'Deleted files');

            return NO_ERROR;
        } else {
            update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, NULL, 100, 'Not all files could be deleted');
            write_log($error, LOG_ERR);

            return COMMANDLINE_ERROR;
        }
    } catch (exception $e) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'Something happened that should not');
        write_log($e->getMessage(), LOG_WARNING);

        return COMMANDLINE_ERROR;
    }
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, NULL, 100, 'Deleted files');

    return NO_ERROR;
}

function do_purge_rss(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    $args = $item->get_args();
    // Check if the group exists
    $res = feed_subscribed($db, $args);
    $rss = new urdd_rss($db);
    if ($res === FALSE) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'Feed not found');

        return FEED_NOT_FOUND;
    }
    try {
        $rss->purge_rss($args, $item->get_dbid());
    } catch (exception $e) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, 'Purge failed: ' . $e->getMessage());

        return FEED_NOT_FOUND;
    }
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Finished');

    return NO_ERROR;
}

function do_purge(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    $args = $item->get_args();
    // Check if the group exists
    $res = group_subscribed($db, $args);
    if ($res === FALSE) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'Group not found');

        return GROUP_NOT_FOUND;
    }
    try {
        $ug = new urdd_group($db);
        $ug->purge_binaries( $args);
        update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Finished');
    } catch (exception $e) {
        update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, 0, 100, 'Purge failed: ' . $e->getMessage());

        return GROUP_NOT_FOUND;
    }

    return NO_ERROR;
}

function do_diskfree(DatabaseConnection $db, $format='')
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $dlpath = get_dlpath($db);
    $df = disk_free_space($dlpath);
    $dt = disk_total_space($dlpath);
    $du = $dt - $df;
    $msg = 'available';
    $arg = isset($format[0]) ? $format[0] : '';
    switch ($arg) {
    case 'P':
        // override some values
        $df = $dt - $df; // used space instead of free space
        $msg = 'used';
        // fall thru
    case 'p':
        $dec = 0;
        if (isset($format[1]) && is_numeric($format[1])) {
            $dec = substr($format, 1);
        }
        $perc = round(($df / $dt) * 100, $dec);

        return array(263, "$perc % $msg\n");
        break;
    default:
        list($df, $tf) = format_size($df, $format);
        list($dt, $tt) = format_size($dt, $format);
        list($du, $tu) = format_size($du, $format);
        return array(256, "$df $tf of $dt $tt free $du $tu\n");
        break;
    }
}

function get_spot_nzb(DatabaseConnection $db, $spotid)
{
    $res = $db->select_query('"nzbs" FROM spots WHERE "spotid"=:spotid', 1, array(':spotid'=>$spotid));
    if (!isset($res[0]['nzbs'])) {
        throw new exception('Invalid SpotID');
    }
    $nzbs = $res[0]['nzbs'];
    if ($nzbs == '') {
        throw new exception('No nzbs found');
    }

    $nzbs = unserialize(db_decompress($nzbs));

    return $nzbs;
}

function do_addspotdata(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $args = preg_split('/[\s]+/', $item->get_args());
    $spotid = $args[1];
    $dlid = $args[0];
    if (get_download_articles_count($db, $dlid) == 0) {
        set_download_size($db, $dlid, 0);
    }
    try {
        $retval = NO_ERROR;
        $server_id = $item->get_active_server();
        if ($server_id == 0) {
            $server_id = $item->get_preferred_server();
        }
        if ($server_id <= 0) {
            update_queue_status($db, $item->get_dbid(), QUEUE_QUEUED, 0, 0, '');
            throw new exception('Error: no server found', ERR_NO_ACTIVE_SERVER);
        }

        if (!download_exists($db, $dlid)) {
            update_queue_status($db, $item->get_dbid(), QUEUE_QUEUED, 0, 0, '');
            throw new exception('Download not found', ERR_DOWNLOAD_NOT_FOUND);
        }

        $nzbs = get_spot_nzb($db, $spotid);
        $segment_count = $count = $totalsize = $fs = 0;
        $groupid = group_by_name($db, get_config($db, 'ftd_group', 'alt.binaries.ftd'));
        $nzb_data = '';
        $segments = count($nzbs);
        $nntp = connect_nntp($db, $server_id);
        $nntp->select_group($groupid, $code);
        try {
            echo_debug('Getting ' . $segments. ' NZB segments', DEBUG_SERVER);
            try {
                foreach ($nzbs as $nzb) {
                    $article = $nntp->get_article($nzb);
                    $nzb_data .= implode('', $article);
                    $segment_count++;
                }
            } catch (exception $e) {
                if ($e->getCode() == ERR_ARTICLE_NOT_FOUND) {
                    update_queue_status($db, $item->get_dbid(), QUEUE_QUEUED, 0, 0, '');

                    return GETARTICLE_ERROR;
                } else {
                    throw $e;
                }
            }
            $nzb_data = gzinflate(remove_special_zip_strings($nzb_data));
            if ($nzb_data === FALSE) {
                throw new exception('Error inflating NZB article');
            }
            libxml_use_internal_errors(TRUE);
	    $nzb_file = @(new SimpleXMLElement($nzb_data, LIBXML_PARSEHUGE, FALSE));
	    //var_dump($nzb_file);
            if ($nzb_file === NULL) {
                $msg = implode("\n", libxml_get_errors());
                throw new exception($msg);
            }
            list($count, $totalsize) = parse_nzb($db, $nzb_file, $dlid);
            $fs = strlen($nzb_file->asXML());
        } catch (exception $e) {
            write_log('Could not parse NZB 2 : ' . $e->getMessage());
            $count = 0;
        }

        echo_debug("Parsed spots for $count lines, in $segments segments, total size in NZB $totalsize bytes", DEBUG_SERVER);
        // size from spots is not always correct. Get the right size from the NZB instead
        add_download_size($db, $dlid, $totalsize);
        $nntp->disconnect();
        add_stat_data($db, stat_actions::IMPORTNZB, $fs, $item->get_userid());

        if ($count == 0) {
            $comment = 'error_nzbfailed';
            update_dlinfo_comment($db, $dlid, $comment);
            update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, 'Imported NZB failed: no articles found');
            $retval = FILE_NOT_FOUND;
        } else {
            update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Imported NZB file: ' . $count . ' lines');
        }
        dec_dl_lock($db, $dlid);
        if (check_last_lock($db, $dlid)) { // we also set a lock on create so we need to remove that too
            dec_dl_lock($db, $dlid);
        }

        return $retval;
    } catch (exception $e) {
        $comment = $e->getMessage();
        write_log('Getting NZB from spot failed '. $comment, LOG_WARNING);
        if ($e->getcode() == ERR_NNTP_AUTH_FAILED) {
            update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, $comment);
            $err_code = NNTP_AUTH_ERROR;
        } elseif ($e->getcode() == ERR_GROUP_NOT_FOUND) {
            update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, $comment);
            $err_code = GROUP_NOT_FOUND;
        } elseif ($e->getcode() == ERR_NO_ACTIVE_SERVER) {
            $err_code = CONFIG_ERROR;
        } elseif ($e->getCode() == ERR_ARTICLE_NOT_FOUND) {
            $dlcomment = 'error_nzbfailed';
            update_dlinfo_comment($db, $dlid, $dlcomment);
            update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, 0, 100, $comment);
            $err_code = FILE_NOT_FOUND;
        } elseif ($e->getCode() == ERR_DOWNLOAD_NOT_FOUND) {
            update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, $comment);
            $err_code = INTERNAL_FAILURE;
        } else {
            $err_code = NNTP_NOT_CONNECTED_ERROR;
        }

        return $err_code;
    }
}

function do_adddata(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    try {
        $args = preg_split('/[\s]+/', $item->get_args());

        $dlid = $args[0];
        if (get_download_articles_count($db, $dlid) == 0) {
            set_download_size($db, $dlid, 0);
        }
        if (!download_exists($db, $dlid)) {
            throw new exception('Download not found', ERR_DOWNLOAD_NOT_FOUND);
        }
        $type = $args[1];
        switch (strtolower($type)) {
        case 'set':
            $setid = $args[2];
            $status = DOWNLOAD_READY;
            $res = $db->select_query('"groupID", "size" FROM setdata WHERE "ID"=:setid', 1, array(':setid'=>$setid));
            if ($res === FALSE) {
                throw new exception('Invalid set ID');
            }
            $groupid = $res[0]['groupID'];
            $size = $res[0]['size'];

            $parts = 'parts_' . $groupid;
            $binaries = 'binaries_' . $groupid;
            $sql = 'INSERT INTO downloadarticles ("downloadID", "groupID", "status", "partnumber", "name", "messageID", "binaryID", "size") '
                . "SELECT '$dlid', '$groupid', '$status', \"partnumber\", bin.\"subject\", \"messageID\", par.\"binaryID\", par.\"size\" FROM $parts AS par "
                . "LEFT JOIN $binaries AS bin ON (bin.\"binaryID\" = par.\"binaryID\") WHERE bin.\"setID\" = :setid";
            $res2 = $db->execute_query($sql, array(':setid'=>$setid));

            $sql = '"value" FROM extsetdata WHERE "setID"=:setid AND "name"=:name';
            $res3 = $db->select_query($sql, 1, array(':setid'=>$setid, ':name'=>'password'));
            if (isset($res3[0]['value'])) {
                $pw = $res3[0]['value'];
                $db->update_query_2('downloadinfo', array('password'=>$pw), '"ID"=? AND "password"=?', array($dlid, ''));
            }
            add_download_size($db, $dlid, $size);
            dec_dl_lock($db, $dlid);
            break;
        case 'binary':
            $groupid = $args[2];
            $binid = $args[3];
            $size = get_binary_size($db, $binid, $groupid);
            set_download_size($db, $dlid, $size);
            $parts = 'parts_' . $groupid;
            $status = DOWNLOAD_READY;
            $binaries = 'binaries_' . $groupid;
            $sql = 'INSERT INTO downloadarticles ("downloadID", "groupID", "status", "partnumber", "name", "messageID", "binaryID", "size") '
                 . "SELECT '$dlid', '$groupid', '$status', \"partnumber\", bin.\"subject\", \"messageID\", '$binid', par.\"size\" FROM $parts AS par "
                 . "LEFT JOIN $binaries AS bin ON (bin.\"binaryID\" = par.\"binaryID\") WHERE bin.\"binaryID\" = :binid";
            $time_start = microtime(true); 
            $res2 = $db->execute_query($sql, array(':binid'=>$binid));

            dec_dl_lock($db, $dlid);
            break;
        case 'article':
            throw new exception ('Not implemented yet');
            break;
        }
    } catch (exception $e) {
        echo_debug($e->getMessage(), DEBUG_SERVER);
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, 'Failed');
        dec_dl_lock($db, $dlid);
        throw $e;
    }
    if ($groupid != 0) {
        update_dlinfo_groupid($db, $dlid, $groupid);
    }
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Finished');
    if (check_last_lock($db, $dlid)) {// we also set a lock on create so we need to remove that too
        dec_dl_lock($db, $dlid);
    }

    return NO_ERROR;
}

function do_set(DatabaseConnection $db, server_data &$servers, array $arg_list, $userid)
{
    global $config, $log_str, $yes, $no;
    assert (is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    if (!urd_user_rights::is_admin($db, $userid)) {
        return urdd_protocol::get_response(532);
    }
    if (!isset($arg_list[0], $arg_list[1])) {
        return urdd_protocol::get_response(501);
    }
    switch (strtoupper($arg_list[0])) {
    case 'LOG_LEVEL' :
        $res = array_search (strtoupper($arg_list[1]), $log_str);
        if ($res !== FALSE) {
            $config['urdd_min_loglevel'] = $res;
            set_pref($db, 'log_level', $res, 0);
            $response = urdd_protocol::get_response(200);
        } else {
            $response = urdd_protocol::get_response(501);
        }
        break;
    case 'SCHEDULER':
        if (in_array(strtoupper($arg_list[1]), $yes)) {
            $config['scheduler'] = TRUE;
            set_pref($db, 'scheduler', 'on', 0);
            $response = urdd_protocol::get_response(200);
        } elseif (in_array(strtoupper($arg_list[1]), $no)) {
            $config['scheduler'] = FALSE;
            set_pref($db, 'scheduler', 'off', 0);
            $response = urdd_protocol::get_response(200);
        } else {
            $response = urdd_protocol::get_response(501);
        }
        break;
    case 'SERVER':
        try {
            $response = urdd_protocol::get_response(501);
            if (isset($arg_list[1], $arg_list[2]) && is_numeric($arg_list[1]) && is_numeric($arg_list[2])) {
                $server_id = $arg_list[1];
                $prio = $arg_list[2];
                if ($prio == 0) {
                    write_log('Disabling server: ' . $server_id, LOG_INFO);
                    $servers->set_server_priority($server_id, DISABLED_USENET_SERVER_PRIORITY);
                    disable_usenet_server($db, $server_id);
                } else {
                    write_log('Enabling server: ' . $server_id . ' at prio ' . $prio, LOG_INFO);
                    $servers->set_server_priority($server_id, $prio);
                    enable_usenet_server($db, $server_id, $prio);
                }
                $response = urdd_protocol::get_response(200);
            } elseif (isset($arg_list[1]) && isset($arg_list[2])) {
                $arg1 = strtoupper($arg_list[1]);
                if ($arg1 == 'ENABLE' && is_numeric($arg_list[2])) {
                    $prio = DEFAULT_USENET_SERVER_PRIORITY;
                    $server_id = $arg_list[2];
                    if (isset($arg_list[3]) && is_numeric($arg_list[3])) {
                        $prio = $arg_list[3];
                    }
                    $servers->enable_server($server_id, $prio);
                    write_log('Enabling server: ' . $server_id . ' at prio ' . $prio, LOG_INFO);
                    $response = urdd_protocol::get_response(200);
                } elseif ($arg1 == 'DISABLE' && is_numeric($arg_list[2])) {
                    $servers->disable_server($db, $arg_list[2]);
                    $response = urdd_protocol::get_response(200);
                } elseif ($arg1 == 'RELOAD' && is_numeric($arg_list[2])) {
                    $servers->reload_server($db, $arg_list[2]);
                    $response = urdd_protocol::get_response(200);
                } elseif ($arg1 == 'LOAD' && is_numeric($arg_list[2])) {
                    $servers->load_server($db, $arg_list[2]);
                    $response = urdd_protocol::get_response(200);
                } elseif ($arg1 == 'DELETE' && is_numeric($arg_list[2])) {
                    $servers->delete_server($arg_list[2]);
                    $response = urdd_protocol::get_response(200);
                } elseif ($arg1 == 'POSTING' && is_numeric($arg_list[2])) {
                    $servers->enable_posting($arg_list[2]);
                    set_posting($db, $arg_list[2], TRUE);
                    $response = urdd_protocol::get_response(200);
                } elseif ($arg1 == 'NOPOSTING' && is_numeric($arg_list[2])) {
                    set_posting($db, $arg_list[2], FALSE);
                    $servers->disable_posting($arg_list[2]);
                    $response = urdd_protocol::get_response(200);
                } else {
                    $response = urdd_protocol::get_response(501);
                }
            }
        } catch (exception $e) {
            echo_debug_trace($e, DEBUG_SERVER);
            $response = urdd_protocol::get_response(501);
        }
        break;
    case 'PREFERRED':
        try {
            if (isset($arg_list[1]) && is_numeric($arg_list[1])) {
                $id = $arg_list[1];
                set_config($db, 'preferred_server', $id);
                $servers->set_update_server($id);
                $response = urdd_protocol::get_response(200);
            } else {
                $response = urdd_protocol::get_response(501);
            }
        } catch (exception $e) {
            write_log($e->getMessage(), LOG_WARNING);
            $response = urdd_protocol::get_response(501);
        }
        break;
    case 'MODULE':
        try {
            if (isset($arg_list[1], $arg_list[2]) && is_numeric($arg_list[1])) {
                global $yes, $commands_list;
                $module = $arg_list[1];
                $onoff = in_array(strtoupper($arg_list[2]), $yes)? TRUE : FALSE;
                $modules = urd_modules::get_urd_module_config(get_config($db, 'modules'));
                $modules[$module] = $onoff;
                $msg = array();
                urd_modules::update_urd_modules($db, $modules, $msg);
                $commands_list->update_settings($modules);
                if ($msg[$module] != TRUE) {
                    $response = sprintf(urdd_protocol::get_response(504), $msg[$module]);
                } else {
                    $response = urdd_protocol::get_response(200);
                }
            } else {
                $response = urdd_protocol::get_response(501);
            }
        } catch (exception $e) {
            write_log($e->getMessage(), LOG_WARNING);
            $response = urdd_protocol::get_response(501);
        }
        break;
    default:
        $response = urdd_protocol::get_response(501);
        break;
    }

    return $response;
}

function do_download(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return start_download($db, $item);
}

function do_optimise(DatabaseConnection $db, action $item)
{
    try {
        $fetch_mode = $db->set_fetch_mode(PDO::FETCH_NUM);
        $res = $db->get_tables();
        $total_rows = count($res);
        $cntr = $mtime = 0;
        $stime = microtime(TRUE);
        update_queue_status($db, $item->get_dbid(), NULL, 0, 0);
        foreach ($res as $row) {
            write_log("Optimising table {$row[0]}", LOG_INFO);
            $perc = ceil(((float) $cntr * 100) / $total_rows);
            $time_left = 0;
            if ($perc != 0) {
                $time_left = ($mtime - $stime) * (($total_rows - $cntr) / $cntr);
            }
            update_queue_status($db, $item->get_dbid(), QUEUE_RUNNING, $time_left, $perc, "Optimising {$row[0]}");
            $db->optimise_table($row[0]);
            $mtime = microtime(TRUE);
            $cntr++;
        }
        $db->set_fetch_mode($fetch_mode);
        update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Done');

        return NO_ERROR;
    } catch (exception $e) {
        $db->set_fetch_mode($fetch_mode);
        write_log($e->getMessage(), LOG_WARNING);

        return DB_FAILURE;
    }
}

function do_unschedule(DatabaseConnection $db, array $arg_list, server_data &$server, $userid)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert (is_numeric($userid));
    $rv = FALSE;
    $arg = isset($arg_list[0]) ? strtoupper($arg_list[0]) : '';
    $e_userid = get_effective_userid($db, $userid);
    try {
        if (is_numeric($arg)) {
            $rv = $server->unschedule($db, $e_userid, $arg);
        } elseif (strtolower($arg) == 'all') {
            $rv = $server->unschedule_all($db, $e_userid);
        } elseif (match_command($arg) !== FALSE) {
            $cmd = $arg_list[0];
            array_shift($arg_list);
            // we simply assume that the remainder of the arguments comprises the command to unschedule
            $arg = implode (' ', $arg_list);
            $rv = $server->unschedule_cmd($db, $e_userid, $cmd, $arg);
        } else {
            return urdd_protocol::get_response(501);
        }
    } catch (exception $e) {
        write_log($e->getMessage(), LOG_WARNING);

        return urdd_protocol::get_response(532);
    }

    return urdd_protocol::get_response(($rv === TRUE) ? 200 : 511);
}

function do_schedule(DatabaseConnection $db, array $arg_list, server_data &$servers, $userid)
{
    // arg_list contains an array of Command, @, timestamp, #, recucurrence
    assert (is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    if (!isset($arg_list[0])) {
        // no argument found
        return urdd_protocol::get_response(501);
    }
    // see if we get a valid command to schedule
    // format is:
    // schedule "command arguments" @ start_time # recurrence in seconds
    // with the "" optional and the # as well.
    $args = implode(' ', $arg_list);
    $parts = explode('@', $args);

    if (!isset($parts[1])) {
        /// no timestamp found
        return urdd_protocol::get_response(501);
    }
    $arguments = $parts[0];
    $arguments = split_args($arguments);
    $cmd = array_shift($arguments);
    if (match_command($cmd) === FALSE) {
        return urdd_protocol::get_response(501);
    }
    $parts = explode('#', $parts[1]);
    $stime = trim($parts[0]);
    $repeat = NULL;
    if (isset($parts[1])) {
        // there is a recurrence thingie
        $repeat = trim($parts[1]);
    }
    $stime = strtotime($stime);
    if ($stime === FALSE) {
        // no proper time found
        return urdd_protocol::get_response(521);
    }
    if ($repeat !== NULL && !is_numeric($repeat)) {
        // no proper recurrence found
        return urdd_protocol::get_response(522);
    }
    // no put it all back together
    $arguments = implode(' ', $arguments);
    $item = new action($cmd, $arguments, $userid, FALSE);
    $now = time();
    if (($repeat !== NULL) && ($now > $stime)) {
        while ($now > $stime) {
            $stime += $repeat;
        }
    } elseif ($repeat === NULL && $now > $stime) {
        $stime += 24 * 3600; // next day
    }
    $servers->add_schedule($db, new job($item, $stime, $repeat));
    return sprintf(urdd_protocol::get_response(201), $item->get_id());
}

function do_pause(DatabaseConnection $db, server_data &$servers, array $arg_list, $pause, $userid)
{
    assert(is_bool($pause));
    assert(is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    try {
        $e_userid = get_effective_userid($db, $userid);
        if (!isset($arg_list[0])) {
            $response = urdd_protocol::get_response(501);
        } elseif (is_numeric($arg_list[0])) {
            $id = $arg_list[0];
            try {
                $rv = $servers->pause($db, $id, $pause, $e_userid);
            } catch (exception $e) {
                $rv = FALSE;
            }
            $response =urdd_protocol::get_response( ($rv === FALSE)? 510 : 200);
        } elseif (match_command($arg_list[0]) !== FALSE) {
            $cmd = $arg_list[0];
            $arg = isset($arg_list[1])? $arg_list[1] : '';
            try {
                $rv = $servers->pause_cmd($db, $cmd, $arg, $pause, $e_userid);
            } catch (exception $e) {
                $rv = FALSE;
            }
            $response = urdd_protocol::get_response( ($rv === FALSE)? 510 : 200);
        } elseif (strtolower($arg_list[0]) == 'all') {
            $servers->pause_all($db, $pause, $e_userid);
            $response = urdd_protocol::get_response(200);
        } else {
            $response = urdd_protocol::get_response(501);
        }
    } catch (exception $e) {
        write_log($e->getMessage(), LOG_WARNING);
        $response = urdd_protocol::get_response(532);
    }

    return $response;
}

function do_preempt(DatabaseConnection $db, server_data &$servers, array $arg_list, $userid)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($userid));
    $response = '';
    $e_userid = get_effective_userid($db, $userid);
    if (isset($arg_list[0]) && is_numeric($arg_list[0])) {
        try {
            $id = $arg_list[0];
            if (isset($arg_list[1]) && is_numeric($arg_list[1])) {
                $servers->stop($db, $arg_list[1], $e_userid);
            }
            $rv = $servers->move_top($db, $id, $e_userid);
            $response = urdd_protocol::get_response(($rv === FALSE) ? 510 : 200);
        } catch (exception $e) {
            $response = urdd_protocol::get_response(501);
        }
    } else {
        $response = urdd_protocol::get_response(501);
    }

    return $response;
}

function do_stop(DatabaseConnection $db, server_data &$servers, array $arg_list, $userid)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($userid));
    $e_userid = get_effective_userid($db, $userid);
    if (!isset($arg_list[0])) {
        $response = urdd_protocol::get_response(501);
    } elseif (is_numeric($arg_list[0])) {
        $id = $arg_list[0];
        try {
            $rv = $servers->stop($db, $id, $e_userid);
        } catch (exception $e) {
            if ($e->getCode() == ERR_ACCESS_DENIED) {
                throw $e;
            }
            $rv = FALSE;
        }
        $response = urdd_protocol::get_response(($rv === FALSE) ? 510 : 200);
    } elseif (strtolower($arg_list[0]) == 'all') {
        $servers->stop_all($db, $e_userid);
        $response = urdd_protocol::get_response(200);
    } else {
        $response = urdd_protocol::get_response(501);
    }

    return $response;
}

function do_cancel(DatabaseConnection $db, server_data &$servers, array $arg_list, $userid)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert (is_numeric($userid));
    try {
        $e_userid = get_effective_userid($db, $userid);
        if (!isset($arg_list[0])) {
            $response = urdd_protocol::get_response(501);
        } elseif (is_numeric($arg_list[0])) {
            $id = $arg_list[0];
            $rv = $servers->delete($db, $id, $e_userid, TRUE);
            $response = urdd_protocol::get_response(($rv === FALSE) ? 510 : 200);
        } elseif (match_command($arg_list[0]) !== FALSE) {
            $cmd = $arg_list[0];
            $arg = isset ($arg_list[1]) ? $arg_list[1] : '';
            $rv = $servers->delete_cmd($db, $e_userid, $cmd, $arg, TRUE);
            $response = urdd_protocol::get_response(($rv === FALSE) ? 510 : 200);
        } elseif (strtolower($arg_list[0]) == 'all') {
            do_cancel_all($db, $servers, $userid);
            $response = urdd_protocol::get_response(200);
        } else {
            $response = urdd_protocol::get_response(501);
        }
    } catch (exception $e) {
        write_log($e->getMessage(), LOG_WARNING);
        $response = urdd_protocol::get_response(($e->getCode() == ERR_ACCESS_DENIED) ? 532 : 510);
    }

    return $response;
}

function do_cancel_all(DatabaseConnection $db, server_data &$servers, $userid, $do_kill= FALSE)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($userid));
    assert(is_bool($do_kill));
    $isadmin = (urd_user_rights::is_admin($db, $userid) || ($do_kill === TRUE));
    $servers->delete_all($db, $isadmin ? user_status::SUPER_USERID : $userid, !$do_kill);
}

function do_subscribe_rss(DatabaseConnection $db, array $arg_list, server_data &$servers, $userid)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert (is_numeric($userid));
    try {
        $def_exp = get_config($db, 'default_expire_time');
        if (isset($arg_list[0]) && is_numeric($arg_list[0])) {
            $id = $arg_list[0];
            $is_subscribed = feed_subscribed($db, $id);
            if (isset($arg_list[1]) && strtolower($arg_list[1]) == 'on' && $is_subscribed === TRUE) {
                if (isset($arg_list[2]) && is_numeric($arg_list[2])) {
                    $exp = $arg_list[2];
                    set_feed_expire($db, $id, $exp);
                }
            } elseif (isset($arg_list[1]) && strtolower($arg_list[1]) == 'on' && $is_subscribed === FALSE) {
                if (isset($arg_list[2]) && is_numeric($arg_list[2])) {
                    $exp = $arg_list[2];
                } else {
                    $exp = $def_exp;
                }
                update_feed_state($db, $id, rssfeed_status::RSS_SUBSCRIBED, $exp);

                $response = queue_update_rss($db, urdd_protocol::COMMAND_UPDATE_RSS, array($arg_list[0]), $userid, $servers);

                return $response;
            } elseif (strtolower($arg_list[1]) == 'off' && $is_subscribed !== FALSE) {
                $rss = new urdd_rss($db);
                $rss->purge_rss($id);
                update_feed_state($db, $id, rssfeed_status::RSS_UNSUBSCRIBED, $def_exp);
                $response = urdd_protocol::get_response(200);

                return $response;
            }
        }
    } catch (exception $e) {
        write_log($e->getMessage(), LOG_WARNING);

        return sprintf(urdd_protocol::get_response(503), $e->getMessage());
    }

    return urdd_protocol::get_response(501);
}

function do_subscribe(DatabaseConnection $db, array $arg_list, server_data &$servers, $userid)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($userid));
    try {
        if (isset($arg_list[0]) && is_numeric($arg_list[0])) {
            $id = $arg_list[0];
            $is_subscribed = group_subscribed($db, $id);
            $default_exp = get_config($db, 'default_expire_time');
            $onoff = (isset($arg_list[1]) && strtolower($arg_list[1]) == 'on') ? TRUE : FALSE;
            $exp = (isset($arg_list[2]) && is_numeric($arg_list[2])) ? $arg_list[2] : $default_exp;
            $minsetsize = (isset($arg_list[3]) && is_numeric($arg_list[3])) ? $arg_list[3] : 0;
            $maxsetsize = (isset($arg_list[4]) && is_numeric($arg_list[4])) ? $arg_list[4] : 0;
            $adult = (isset($arg_list[5]) && is_numeric($arg_list[5])) ? $arg_list[5] : ADULT_OFF;
            $ug = new urdd_group($db);
            if ($onoff && $is_subscribed === TRUE) {
                set_group_expire($db, $id, $exp);
                set_group_adult($db, $id, $adult);
                set_group_minsetsize($db, $id, $minsetsize);
                set_group_maxsetsize($db, $id, $maxsetsize);
            } elseif (isset($arg_list[1]) && strtolower($arg_list[1]) == 'on' && $is_subscribed === FALSE) {
                $ug->subscribe($id, $exp, $minsetsize, $maxsetsize);
                $response = queue_update($db, urdd_protocol::COMMAND_UPDATE, array($id), $userid, $servers);

                return $response;
            } elseif (!$onoff && $is_subscribed !== FALSE) {
                $res = $ug->unsubscribe($id);
                $ug->purge_binaries($id);

                return urdd_protocol::get_response($res ? 200 : 402);
            }
        }
    } catch (exception $e) {
        write_log($e->getMessage(), LOG_WARNING);

        return sprintf(urdd_protocol::get_response(503), $e->getMessage());
    }

    return urdd_protocol::get_response(501);
}

function do_unpar_unrar(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $args = explode (' ', $item->get_args());
    $dlid = $args[0];
    $query = '"unpar", "unrar", "subdl", "delete_files", "destination", "first_run" FROM downloadinfo WHERE "ID"=:dlid';
    $res = $db->select_query($query, 1, array(':dlid' => $dlid));
    if ($res === FALSE) {
        $cksfv = $unpar = $concat = $delete_files = $first_run = $dl_subs = 0;
    } else {
        $cksfv = $unpar = $res[0]['unpar'];
        $concat = $unrar = $res[0]['unrar'];
        $delete_files = $res[0]['delete_files'];
        $first_run = $res[0]['first_run'];
        $dl_subs = $res[0]['subdl'];
    }
    update_dlinfo_status($db, DOWNLOAD_COMPLETE, $dlid);
    $to = $item->get_dlpath();
    $dl_status = NULL;
    $all_files = array();
    $uu_error = $par_error = $concat_error = $comp_error = $dir_error = $sfv_error = FALSE;
    $comment = '';
    $ft = new file_types;
    if (!is_dir($to)) {
        $status = QUEUE_FAILED;
        write_log("Not a directory: $to", LOG_ERR);
        $cksfv = $unpar = $unrar = $uudecode = $concat = 0;
        $files = array();
        $dir_error = TRUE;
    } else {
        $files = $ft->get_par_rar_files($db, $to, $all_files);
        $uudecode = 1;
    }
    if ($uudecode > 0) {
        $counter = 0;
        $comment .= uudecode($db, $files, $dlid, $to, $uu_error, $dl_status, $counter);
        if ($uu_error === TRUE) {
            $cksfv = $unpar = $unrar = $uudecode = 0;
        } elseif ($counter > 0) {
            if ($delete_files > 0) {
                $files->delete_files(FALSE, FALSE, FALSE, TRUE);
            }
            $files = $ft->get_par_rar_files($db, $to, $all_files); // re-read the file list, as uuencode creates new ones
        }
    }
    if ($unpar > 0) {
        $comment .= verify_par($db, $to, $dlid, $files, $item, $par_error, $unpar);
        if ($par_error === TRUE) {
            list ($done, $queued, $failed, $par_files) = check_all_dl_done($db, $item);
            if ($par_files > 0) {
                return RESTART_DOWNLOAD;
            }
            //$unrar = 0; // don't unrar if there is a par2 error
        } else {
            if ($delete_files > 0) {
                $files->delete_files(TRUE, FALSE, FALSE, FALSE);
            }
        }
        $files = $ft->get_par_rar_files($db, $to, $all_files); // re-read the file list, as par2 may create new ones
    }
    if ($unpar <= 0 && $cksfv > 0) {
        $comment = 'PAR2 not run ';
        // but try cksfv

        $comment .= verify_cksfv($db, $to, $dlid, $files, $item, $sfv_error);
        if ($sfv_error === TRUE) {
            $unrar = 0;
        } else {
            if ($delete_files > 0) {
                $files->delete_files(FALSE, FALSE, TRUE, FALSE);
            }
        }
    }

    if ($concat > 0) {
        $comment .= concat_files($db, $files, $dlid, $to, $concat_error, $dl_status);
        if ($concat_error !== TRUE && $delete_files > 0) {
            $files->delete_files(FALSE, FALSE, FALSE, FALSE, TRUE);
        } else {
            if ($par_error === TRUE && $unpar > 0) {
                $par_error = FALSE; // if there is a par2 err, it may be that we first have to concat before parring again
                $comment .= verify_par($db, $to, $dlid, $files, $item, $par_error, $unpar);
                if ($par_error === TRUE) {
                    $unrar = 0;
                } else {
                    if ($delete_files > 0) {
                        $files->delete_files(TRUE, FALSE, FALSE, FALSE);
                    }
                }
            }
        }
    }
    $password = get_download_password($db, $dlid);
    if ($unrar > 0 && $password != PASSWORD_PLACE_HOLDER) { // if PASSWORD_PLACE_HOLDER is the pw then we won't unrar since it will fail anyway.
        //global $archives;
        foreach (file_extensions::$archives as $type) {
            $comment .= decompress($db, $type, $to, $files, $password, $dlid, $dl_status, $comp_error_tmp, $par_error);
            if ($comp_error_tmp === TRUE) {
                $comp_error = TRUE;
            }
        }
        if ($comp_error !== TRUE && $delete_files > 0) {
            $files->delete_files(FALSE, TRUE, FALSE, FALSE);
        }
    } else {
        $comment .= ' Decompression not run';
    }
    if (!isset($status) || $status == QUEUE_RUNNING) {
        $status = QUEUE_FINISHED;
    }
    // clean up dir
    if ($delete_files > 0) {
        cleanup_dir($to, $all_files);
    }

    if ($uu_error === FALSE && $par_error === FALSE && $concat_error === FALSE && $comp_error === FALSE && $dir_error === FALSE && $dl_subs && !$item->get_preview()) {
        download_subs($db, $to, $item->get_userid());
    }
    if (!isset($comment)) {
        $comment = 'Complete';
    }
    echo_debug("download finished $dlid", DEBUG_SERVER);
    update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);
    if ($first_run) {
        move_download_to_done($db, $item, $dlid, $item->get_userid());
    }
    $done = get_download_destination($db, $dlid);
    set_permissions($db, $done); // setting permissions must be last otherwise we may not be able to move the file
    set_group($db, $done);

    if ($uu_error === FALSE && $par_error === FALSE && $concat_error === FALSE && $comp_error === FALSE && $dir_error === FALSE && $sfv_error === FALSE) {
        update_dlinfo_status($db, DOWNLOAD_FINISHED, $dlid);
    }

    $dl_status = get_dlinfo_status($db, $dlid); // various functions set this value, so we must retrieve it from the db
    if ($first_run) {
        run_all_scripts($db, $item, $dlid, $dl_status);
    }
    //remove lock file once we won't touch the dir anymore
    if (file_exists($done . URDD_DOWNLOAD_LOCKFILE)) {
        unlink($done . URDD_DOWNLOAD_LOCKFILE);
    }

    update_dlinfo_firstrun($db, $dlid, FALSE);
    if (!$item->get_preview()) {
        try {
            urd_mail::mail_user_download($db, $dlid, $item->get_userid(), $dl_status);
        } catch (exception $e) {
            write_log('Could not send message', LOG_WARNING);
        }
    }

    return NO_ERROR;
}

function do_move(DatabaseConnection $db, server_data &$servers, array $arg_list, $userid)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($userid));
    $e_userid = get_effective_userid($db, $userid);
    if (isset($arg_list[1]) && (match_command($arg_list[1]) !== FALSE)) {
        $cmd = $arg_list[1];
    } else {
        return urdd_protocol::get_response(501);
    }
    if (isset($arg_list[2]) && is_numeric($arg_list[2])) {
        $id = $arg_list[2];
    } else {
        return urdd_protocol::get_response(501);
    }

    if (isset($arg_list[0])) {
        $arg = strtolower($arg_list[0]);
        if ($arg == 'up') {
            $direction = queue::MOVE_UP;
        } elseif ($arg == 'down') {
            $direction = queue::MOVE_DOWN;
        } else {
            return urdd_protocol::get_response(501);
        }
    } else {
        return urdd_protocol::get_response(501);
    }
    try {
        $servers->move($db, $cmd, $id, $e_userid, $direction);
    } catch (exception $e) {
        write_log ("Download $id not found", LOG_INFO);

        return urdd_protocol::get_response(510);
    }

    return urdd_protocol::get_response(200);
}

function do_merge_sets(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    //todo
    try {
        $userid = $item->get_userid();
    } catch (exception $e) {
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, 'User not found');

        return UNKNOWN_ERROR;
    }

    if (!urd_user_rights::is_seteditor($db, $userid)) {
        update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, 0, 100, 'User is no set editor');

        return NOT_ALLOWED;
    }
    $elems = preg_split('/[\s]+/', $item->get_args());
    $setid1 = $elems[0];
    unset($elems[0]);
    $setid2 = $elems;
    try {
        $ug = new urdd_group($db);
        $ug->merge_sets($setid1, $setid2);
    } catch (exception $e) {
        update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, 0, 100, 'Merge failed');

        return GROUP_NOT_FOUND;
    }

    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Merged sets');

    return NO_ERROR;
}

function do_find_servers(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $servers = get_server_data($db);
    $test_results = new test_result_list();
    $arg_list = $item->get_args();

    try {
        $servers->load_servers($db, $test_results, FALSE);
    } catch (exception $e) {
        if ($e->getcode() == ERR_NO_ACTIVE_SERVER) {
            write_log($e->getmessage(), LOG_ERR);
        }
        //if ($config ['find_servers'] === FALSE) { // if we are gonna run find servers next, we don't want to exit
        //   ;//   exit ($e->getcode()); // may have to remove this if we want to run find_servers from the website
        //}
    }

    $servers->enable_nntp(FALSE); // disable nntp so all other nntp actions will not be allowed
    $servers->find_servers($db, $test_results, $item->get_dbid(), isset($arg_list[0]) && $arg_list[0] == 'extended', TRUE);
    update_queue_status($db, $item->get_dbid(),QUEUE_FINISHED, 0, 100, 'Autoconfigure servers complete');

    return NO_ERROR;
}

function do_delete_set(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $setids = preg_split('/[\s]+/', $item->get_args());

    $l = count($setids);
    $cnt = 0;
    $cmt = '';
    $groupids = array();
    foreach ($setids as $setid) {
        try {
            $group_id = get_groupid_for_set($db, $setid);
        } catch (exception $e) {
            write_log("Set not found $setid", LOG_ERR);
            $cmt .= "Set not found $setid ";
            continue;
        }
        $sql = "\"binaryID\" FROM binaries_$group_id WHERE \"setID\" = :setid";
        $res = $db->select_query($sql, array(':setid'=>$setid));
        $binary_ids = ($res === FALSE) ? array() : $res;

        $db->delete_query('setdata', '"ID" = :id', array(':id'=>$setid));
        $db->delete_query("binaries_$group_id", '"setID" = :id', array(':id'=> $setid));

        foreach ($binary_ids as $row) {
            $bin_id = $row['binaryID'];
            $db->delete_query("parts_$group_id", '"binaryID" = :id', array(':id'=>$bin_id));
        }
        $groupids[$group_id] = 1;
        update_queue_status($db, $item->get_dbid(), QUEUE_RUNNING, 0, (int) (($cnt / $l) * 100), $cmt);
    }
    foreach ($groupids as $gr => $dummy) {
        $setcount = count_sets_group($db, $gr);
        update_group_setcount($db, $gr, $setcount);
    }

    update_queue_status($db, $item->get_dbid(),QUEUE_FINISHED, 0, 100, ($cmt == '' ? 'Complete' : $cmt));

    return NO_ERROR;
}

function do_delete_spot(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $setids = preg_split('/[\s]+/', $item->get_args());
    $l = count($setids);
    $cnt = 0;
    foreach ($setids as $setid) {
        $db->delete_query('spots', '"spotid"=:id', array(':id'=>$setid));
        update_queue_status($db, $item->get_dbid(), QUEUE_RUNNING, 0, (int) (($cnt / $l) * 100), 'Complete');
    }
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Complete');

    return NO_ERROR;
}

function do_delete_set_rss(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $setids = preg_split('/[\s]+/', $item->get_args());
    $l = count($setids);
    $cnt = 0;
    foreach ($setids as $setid) {
        $feed_id = get_feedid_for_set($db, $setid);
        $feeds[$feed_id] = 1;
        $db->delete_query('rss_sets', '"setid"=:id', array(':id'=>$setid));
        update_queue_status($db, $item->get_dbid(), QUEUE_RUNNING, 0, (int) (($cnt / $l) * 100), 'Complete');
    }
    foreach ($feeds as $feed => $dummy) {
        $setcount = count_sets_feed($db, $feed);
        update_feed_setcount($db, $feed, $setcount);
    }
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Complete');

    return NO_ERROR;
}

function do_post_message(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $arg_list = $item->get_args();
    $msg_id = $arg_list;
    if (!is_numeric($msg_id)) {
        throw new exception('Message ID not a number');
    }
    $userid = $item->get_userid();
    try {
        $sql = '* FROM post_messages WHERE "id"=:msg_id AND "userid"=:user_id';
        $res = $db->select_query($sql, 1, array(':msg_id'=>$msg_id, ':user_id'=>$userid));
        if (!isset($res[0]['id'])) {
            throw new exception('Message not found: '. $msg_id, ERR_MESSAGE_NOT_FOUND);
        }
        $row = $res[0];
        $message = $row['message'];
        $groupid = $row['groupid'];
        $subject = $row['subject'];
        $poster_id = $row['poster_id'];
        $poster_name = $row['poster_name'];
        $poster_headers = unserialize($row['headers']);
        post_message( $db, $item, $poster_headers, $message, $groupid, $subject, $poster_id, $poster_name);
        update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Complete');
        add_stat_data( $db, stat_actions::POST_MSG_COUNT, 1, $userid);
    } catch (exception $e) {
        write_log($e->getMessage(), LOG_NOTICE);

        update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, 0, 100, 'Failed');
        // and now?
    }
    $db->delete_query('post_messages', '"id"=? AND "userid"=?', array($msg_id, $userid));

    return NO_ERROR;
}

function do_getnfo(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $limit = 10;
    $groupid = 0;
    $server_id = $item->get_active_server();
    if ($server_id == 0) {
        $server_id = $item->get_preferred_server();
    }
    if ($server_id <= 0) {
        update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, NULL, 100, 'No server enabled');
        throw new exception('Error: no server found', ERR_NO_ACTIVE_SERVER);
    }
    try {
        $nzb = connect_nntp($db, $server_id);
        $user_id = $item->get_userid();
        $sql = 'count(*) AS "cnt" FROM nfo_files';
        $res = $db->select_query($sql, $limit);
        if (!isset($res[0]['cnt'])) {
            update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'No articles');

            return NO_ERROR;
        }
        $totalcount = $res[0]['cnt'];
        write_log("Getting $totalcount NFO files", LOG_NOTICE);
        $cnt = 0;
        $time_a = microtime(TRUE);
        while (TRUE) {
            $sql = '* FROM nfo_files';
            $res = $db->select_query($sql, $limit);
            if (!is_array($res)) {
                break;
            }

            $ids = array();

            foreach ($res as $row) {
                if ($row['groupID'] != $groupid) {
                    $groupid = $row['groupID'];
                    $nzb->select_group($groupid, $code);
                }
                $ids[] = $row['id'];
                $sql = "* FROM parts_$groupid WHERE \"binaryID\" = :binid";
                $res2 = $db->select_query($sql, array(':binid'=>$row['binaryID']));
                if (!is_array($res2)) {
                    continue;
                }
                foreach ($res2 as $article) {
                    try {
                        $art = $nzb->get_article($article['messageID']);
                        $art = implode("\n", $art);
                        $nfo = explode("\n", yenc_decode($art));
                        urd_extsetinfo::do_magic_nfo_extsetinfo_contents($db, $nfo, $row['binaryID'], $row['groupID'], $user_id, $row['setID']);
                    } catch (exception $e) {
                        write_log($e->getMessage(), LOG_NOTICE);
                    }
                }
            }
            $cnt += count($res);
            $time_b = microtime(TRUE);
            $perc = ceil(((float) $cnt * 100) / $totalcount);
            $time_left = 0;
            if ($perc != 0 && $cnt > 0) {
                $time_left = ($time_b - $time_a) * (($totalcount - $cnt) / $cnt);
            }
            update_queue_status($db, $item->get_dbid(), QUEUE_RUNNING, $time_left, $perc, 'Getting nfo files');
            $db->delete_query('nfo_files', '"id" in ( ' . (str_repeat('?,', count($ids) - 1)). '? )', $ids);
        }
        update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Complete. ' . $totalcount . ' articles');

        return NO_ERROR;
    } catch (exception $e) {
        write_log('Update failed '. $e->getMessage(), LOG_WARNING);
        if ($e->getcode() == ERR_NNTP_AUTH_FAILED) {
            $comment = $e->getMessage();
            update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, $comment);

            return NNTP_AUTH_ERROR;
        } elseif ($e->getcode() == ERR_GROUP_NOT_FOUND) {
            return GROUP_NOT_FOUND;
        } elseif ($e->getcode() == ERR_NO_ACTIVE_SERVER) {
            return CONFIG_ERROR;
        } else {
            return NNTP_NOT_CONNECTED_ERROR;
        }
    }
}

function do_getspots(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    //run update headers first on spots group
    try {
        $ug = new urdd_group($db);
        $group = get_config($db, 'spots_group');
        $groupid = group_by_name($db, $group);
        $ug->check_group_subscribed($groupid);
        $groupArr_spots = get_group_info($db, $groupid);
        $server_id = $item->get_active_server();
        if ($server_id == 0) {
            $server_id = $item->get_preferred_server();
        }
        if ($server_id <= 0) {
            update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'No server enabled');
            throw new exception('Error: no server found', ERR_NO_ACTIVE_SERVER);
        }
        $nzb = connect_nntp($db, $server_id);
        $rv = update_headers($db, $nzb, $groupArr_spots, $item, $server_id);
        if ($rv != NO_ERROR) {
            return $rv;
        }

        $ug->update_postcount($groupArr_spots['ID']);
        $us = new urd_spots($db);
        $spots_count = $us->load_spots($nzb, $item);
        add_stat_data($db, stat_actions::SPOT_COUNT, $spots_count, user_status::SUPER_USERID);

        sets_marking::mark_interesting($db, USERSETTYPE_SPOT);
        update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Complete. ' . $spots_count);
    } catch (exception $e) {
        write_log('Update failed ' . $e->getMessage(), LOG_WARNING);
        echo_debug_trace($e, DEBUG_SERVER);
        $status = QUEUE_FAILED;
        $comment = $e->getMessage();
        if ($e->getcode() == ERR_NNTP_AUTH_FAILED) {
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

            return NNTP_AUTH_ERROR;
        } elseif ($e->getcode() == ERR_GROUP_NOT_FOUND) {
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

            return GROUP_NOT_FOUND;
        } elseif ($e->getcode() == ERR_NO_ACTIVE_SERVER) {
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

            return CONFIG_ERROR;
        } else {
            return NNTP_NOT_CONNECTED_ERROR;
        }
    }
}

function fetch_image_articles(DatabaseConnection $db, URD_NNTP $nntp, array $articles)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $data = '';
    try {
        foreach ($articles as $art) {
            $article = $nntp->get_article($art);
            $data .= implode('', $article);
        }

        return remove_special_zip_strings($data);
    } catch (exception $e) {
        if ($e->getCode() == ERR_ARTICLE_NOT_FOUND) {
            return FALSE;
        }
        throw $e;
    }
}

function get_unfetched_spot_images_count(DatabaseConnection $db)
{
    $like = $db->get_pattern_search_command('LIKE');
    $sql = "count(*) AS cnt FROM spot_images WHERE \"fetched\"=0 AND \"image\" $like 'articles:%'";
    $res = $db->select_query($sql);
    if (!isset($res[0]['cnt'])) {
        throw new exception('Counter not set');
    }
    $count = $res[0]['cnt'];

    return $count;
}

function do_getspot_images(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    try {
        $pathu = my_realpath(dirname(__FILE__));
        $image_cache_dir = get_dlpath($db) . IMAGE_CACHE_PATH;
        add_dir_separator($image_cache_dir);
        $server_id = $item->get_active_server();
        if ($server_id == 0) {
            $server_id = $item->get_preferred_server();
        }
        if ($server_id <= 0) {
            update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'No server enabled');
            throw new exception('Error: no server found', ERR_NO_ACTIVE_SERVER);
        }
        $count = get_unfetched_spot_images_count($db);
        $nntp = connect_nntp($db, $server_id);

        $image_count = 0;
        $like = $db->get_pattern_search_command('LIKE');
        $sql = '"image", "spotid" FROM spot_images WHERE "fetched" = 0 AND "image" ' . "$like 'articles:%'";
        while (TRUE) {
            $res = $db->select_query($sql, 50);
            if (!isset($res[0])) {
                break;
            }
            $ids = array();
            foreach ($res as $row) {
                $articles = unserialize(substr($row['image'], 9));
                $spotid = $row['spotid'];
                $ids[] = $spotid;
                $image_data = fetch_image_articles($db, $nntp, $articles['segment']);
                if ($image_data !== FALSE) {
                    $image_count++;
                    file_put_contents($image_cache_dir . $spotid . '.jpg', $image_data);
                }
            }
            if (count($ids) > 0) {
                $db->update_query_2('spot_images', array('fetched'=>1), '"spotid" IN (' . (str_repeat('?,', count($ids) - 1) . '?') . ')', $ids);
            }
            update_queue_status($db, $item->get_dbid(), NULL, 0, floor((100 * $image_count) / $count), 'Loaded ' . $image_count . ' images');
        }
        $nntp->disconnect();
        update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Complete. Loaded ' . $image_count . ' images');
    } catch (exception $e) {
        write_log('Update failed ' . $e->getMessage(), LOG_WARNING);
        echo_debug_trace($e, DEBUG_SERVER);
        $status = QUEUE_FAILED;
        $comment = $e->getMessage();
        if ($e->getcode() == ERR_NNTP_AUTH_FAILED) {
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

            return NNTP_AUTH_ERROR;
        } elseif ($e->getcode() == ERR_NO_ACTIVE_SERVER) {
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

            return CONFIG_ERROR;
        } else {
            return NNTP_NOT_CONNECTED_ERROR;
        }
    }
}

function do_getspot_reports(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    //run update headers first on spots group
    try {
        $ug = new urdd_group($db);
        $report_group = get_config($db, 'spots_reports_group');
        $groupid = group_by_name($db, $report_group);
        $ug->check_group_subscribed($groupid);
        $groupArr_reports = get_group_info($db, $groupid);
        $server_id = $item->get_active_server();
        if ($server_id == 0) {
            $server_id = $item->get_preferred_server();
        }
        if ($server_id <= 0) {
            update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'No server enabled');
            throw new exception('Error: no server found', ERR_NO_ACTIVE_SERVER);
        }
        $nzb = connect_nntp($db, $server_id);

        $rv = update_headers($db, $nzb, $groupArr_reports, $item, $server_id);
        if ($rv != NO_ERROR) {
            return $rv;
        }

        $ug->update_postcount($groupArr_reports['ID']);
        $expire = get_config($db, 'spots_expire_time', DEFAULT_SPOTS_EXPIRE_TIME);
        $us = new urd_spots($db);
        $comment_count = $us->load_spot_reports($nzb, $item, $expire);

        $nzb->disconnect();
        update_queue_status($db, $item->get_dbid(),QUEUE_FINISHED, 0, 100, 'Complete. Loaded ' . $comment_count . ' reports');
    } catch (exception $e) {
        write_log('Update failed ' . $e->getMessage(), LOG_WARNING);
        echo_debug_trace($e, DEBUG_SERVER);
        $status = QUEUE_FAILED;
        $comment = $e->getMessage();
        if ($e->getcode() == ERR_NNTP_AUTH_FAILED) {
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

            return NNTP_AUTH_ERROR;
        } elseif ($e->getcode() == ERR_GROUP_NOT_FOUND) {
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

            return GROUP_NOT_FOUND;
        } elseif ($e->getcode() == ERR_NO_ACTIVE_SERVER) {
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

            return CONFIG_ERROR;
        } else {
            return NNTP_NOT_CONNECTED_ERROR;
        }
    }
}

function do_getspot_comments(DatabaseConnection& $db, action& $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    //run update headers first on spots group
    try {
        $ug = new urdd_group($db);
        $comments_group = get_config($db, 'spots_comments_group');
        $groupid = group_by_name($db, $comments_group);
        $ug->check_group_subscribed($groupid);
        $groupArr_comments = get_group_info($db, $groupid);
        $server_id = $item->get_active_server();
        if ($server_id == 0) {
            $server_id = $item->get_preferred_server();
        }
        if ($server_id <= 0) {
            update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, NULL, 100, 'No server enabled');
            throw new exception('Error: no server found', ERR_NO_ACTIVE_SERVER);
        }
        $nzb = connect_nntp($db, $server_id);

        $rv = update_headers($db, $nzb, $groupArr_comments, $item, $server_id);
        if ($rv != NO_ERROR) {
            return $rv;
        }
        $ug->update_postcount($groupArr_comments['ID']);
        $expire = get_config($db, 'spots_expire_time', DEFAULT_SPOTS_EXPIRE_TIME);
        $us = new urd_spots($db);
        $comment_count = $us->load_spot_comments($nzb, $item, $expire);

        update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Complete. Loaded ' . $comment_count . ' comments');
    } catch (exception $e) {
        write_log('Update failed '. $e->getMessage(), LOG_WARNING);
        echo_debug_trace($e, DEBUG_SERVER);
        $status = QUEUE_FAILED;
        $comment = $e->getMessage();
        if ($e->getcode() == ERR_NNTP_AUTH_FAILED) {
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

            return NNTP_AUTH_ERROR;
        } elseif ($e->getcode() == ERR_GROUP_NOT_FOUND) {
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

            return GROUP_NOT_FOUND;
        } elseif ($e->getcode() == ERR_NO_ACTIVE_SERVER) {
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);

            return CONFIG_ERROR;
        } else {
            return NNTP_NOT_CONNECTED_ERROR;
        }
    }
}

function do_getblacklist(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $blacklist_url = get_config($db, 'spots_blacklist', '');
    if ($blacklist_url == '') {
        update_queue_status($db, $item->get_dbid(),QUEUE_FINISHED, 0, 100, 'No blacklist');

        return NO_ERROR;
    }
    $spotter_ids = file($blacklist_url, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
    if ($spotter_ids === FALSE) {
        update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, NULL, 100, "Connection failed to $blacklist_url");

        return HTTP_CONNECT_ERROR;
    }
    $blacklist = load_blacklist($db, blacklist::BLACKLIST_EXTERNAL, NULL, TRUE);
    $old = 0;
    $add_ids = array();
    foreach ($spotter_ids as $id) {
        if (strlen($id) < 3 || strlen($id) > 10 || !preg_match('/[a-z0-9]/i', $id)) {
            continue;
        }
        if (isset($blacklist[$id])) {
            $blacklist[$id]++;
            $old++;
        } else {
            $add_ids[$id] = array($id, blacklist::BLACKLIST_EXTERNAL, blacklist::ACTIVE, 0);
        }
    }
    $cnt = 0;
    if (count($add_ids) > 0) {
        $del = array();
        foreach ($blacklist as $id => $val) {
            if ($val == 0) {
                $cnt++;
                $del[] = $id;
            }
        }
        if ($cnt > 0) {
            $db->delete_query('spot_blacklist', '"spotter_id" IN ( ' . str_repeat('?,', count($del) - 1) . '?)', $del);
        }
    }
    write_log('Added ' . count($add_ids) . " new spotters to the blacklist, removed $cnt spotters, $old spotters kept", LOG_INFO);
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Added ' . count($add_ids) . " new spotters to the blacklist, removed $cnt spotters, $old spotters kept");

    return NO_ERROR;
}

function do_getwhitelist(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $whitelist_url = get_config($db, 'spots_whitelist', '');
    if ($whitelist_url == '') {
        $status = QUEUE_FINISHED;
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'No whitelist');

        return NO_ERROR;
    }
    $spotter_ids = file($whitelist_url, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
    if ($spotter_ids === FALSE) {
        update_queue_status($db, $item->get_dbid(),QUEUE_FAILED, NULL, 100, "Connection failed to $whitelist_url");

        return HTTP_CONNECT_ERROR;
    }
    $whitelist = load_whitelist($db, whitelist::WHITELIST_EXTERNAL, NULL);
    $add_ids = array();
    $old = 0;
    foreach ($spotter_ids as $id) {
        if (strlen($id) < 3 || strlen($id) > 10 || !preg_match('/[a-z0-9]/i', $id)) {
            continue;
        }
        if (isset($whitelist[$id])) {
            $whitelist[$id]++;
            $old++;
        } else {
            $add_ids[$id] = array($id, whitelist::WHITELIST_EXTERNAL, whitelist::ACTIVE, 0);
        }
    }
    $cnt = 0;
    if (count($add_ids) > 0) {
        $cols = array('spotter_id', 'source', 'status', 'userid');
        $db->insert_query('spot_whitelist', $cols, $add_ids);
        foreach ($whitelist as $id => $val) {
            if ($val == 0) {
                $cnt++;
                $del[] = $id;
            }
        }
        if ($cnt > 0) {
            $db->delete_query('spot_whitelist', '"spotter_id" IN ( ' . str_repeat('?,', count($del) - 1) . '?)', $del);
        }
    }
    write_log('Added ' . count($add_ids) . " new spotters to the whitelist, removed $cnt spotters, $old spotters kept", LOG_INFO);
    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'Added ' . count($add_ids) . " new spotters to the whitelist, removed $cnt spotters, $old spotters kept");

    return NO_ERROR;
}

/*
function do_get_imdb_watchlist_old(DatabaseConnection $db, action $item)
{
    $userid = $item->get_userid();
    $imdb_user = get_pref($db, 'imdb_userid', $userid);
    if ($imdb_user == '' || preg_match('/ur\d+/', $imdb_user) != 1) {
        $status = QUEUE_FINISHED;
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'No valid IMDB user set');

        return NO_ERROR;
    }

    $url = "http://rss.imdb.com/user/$imdb_user/watchlist?view=compact&sort=created:asc";
    $cache_dir = get_magpie_cache_dir($db);
    $cnt1 = $cnt2 = 0;
    $search_terms = load_search_terms($db, $userid);
    try {
        $rss = fetch_rss::do_fetch_rss($url, $cache_dir, '', '', 1);
        foreach ($rss->items as $elem) {
            if (isset($elem['title'])) {
                $title = html_entity_decode($elem['title']);
                $title = preg_replace('/\(\d\d\d\d(\s+(TV[-\s]+Movie)|(\s+Mini[-\s]+Series))?\)/i', '', $title);
                $title = trim(preg_replace('/[^a-zA-Z0-9 ]/', ' ', $title));
                $title = preg_replace('/\s+/', ' ', $title);
                $cnt1++;
                if (strlen($title) > 4 && !in_array($title, $search_terms)) {
                    $search_terms[] = $title;
                    $cnt2++;
                }
            }
        }
        store_search_terms($db, $search_terms, $userid);
        write_log("Read $cnt1 entries on the watchlist. Added $cnt2 entries to the search terms.", LOG_INFO);
        update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, "Read $cnt1 entries on the watchlist. Added $cnt2 entries to the search terms.");
        return NO_ERROR;
    } catch (exception $e) {
        write_log($e->getMessage(), LOG_INFO);
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, $e->getMessage());
        return HTTP_CONNECT_ERROR;
    }
}*/

function do_get_imdb_watchlist(DatabaseConnection $db, action $item)
{
    $userid = $item->get_userid();
    $url = get_pref($db, 'imdb_userid', $userid);
    $res = preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/[a-z0-9.@\-_:/?~%&;\[\]]*)?$|i', $url);
    if ($res != 1) {
          $status = QUEUE_FINISHED;
          update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'No valid IMDB watchlist set');

          return NO_ERROR;
    }

    $list = file($url);
    $csv = array_map('str_getcsv', ($list));

    $cnt1 = $cnt2 = 0;
    $search_terms = load_search_terms($db, $userid);
    try {
        foreach ($csv as $entry) {
            if (!is_numeric($entry[0])) { 
                continue;
            }
            $title = utf8_encode($entry[5]);
            $cnt1++;
            if (strlen($title) > 4 && !in_array($title, $search_terms)) {
                $search_terms[] = $title;
                $cnt2++;
            }
        }
        store_search_terms($db, $search_terms, $userid);
        write_log("Read $cnt1 entries on the watchlist. Added $cnt2 entries to the search terms.", LOG_INFO);
        update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, "Read $cnt1 entries on the watchlist. Added $cnt2 entries to the search terms.");
        return NO_ERROR;
    } catch (exception $e) {
        write_log($e->getMessage(), LOG_INFO);
        update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 100, $e->getMessage());
        return HTTP_CONNECT_ERROR;
    }
}
