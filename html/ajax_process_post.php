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
 * $LastChangedDate: 2014-05-30 00:49:17 +0200 (vr, 30 mei 2014) $
 * $Rev: 3077 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_process_post.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */
if (!defined ('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
}

$__auth = 'silent';
$pathpn = realpath(dirname(__FILE__));

require_once "$pathpn/../functions/ajax_includes.php";

if (!urd_user_rights::is_poster($db, $userid)) {
    throw new exception($LN['error_accessdenied'] . " $userid");
}

verify_access($db, urd_modules::URD_CLASS_POST, FALSE, 'P', $userid, TRUE);
challenge::verify_challenge_text($_POST['challenge']);

function get_post_vals(DatabaseConnection $db, $userid, &$timestamp)
{
    global $LN;
    $dlpath = my_realpath(get_dlpath($db) . POST_PATH);
    $groupID = get_post('groupid', '');
    if ($groupID == '' || !is_numeric($groupID) || !group_exists($db, $groupID)) {
        throw new exception ($LN['error_groupnotfound'] . " $groupID");
    }
    $groupID_nzb = get_post('groupid_nzb', '');
    if ($groupID_nzb == '' || !is_numeric($groupID_nzb) || !group_exists($db, $groupID_nzb)) {
        throw new exception ($LN['error_groupnotfound'] . " $groupID_nzb");
    }

    $src_dir = my_realpath(get_post('directory', ''));
    if ($src_dir == '' || (substr($src_dir, 0, strlen($dlpath)) != $dlpath)) {
        throw new exception($LN['error_dirnotfound'] . " $src_dir");
    }

    $tmp_dir = '';
    $subject = trim(get_post('subject', ''));
    if ($subject == '') {
        throw new exception ($LN['error_subjectnofound']);
    }

    $poster_id = trim(get_post('posteremail', ''));
    if ($poster_id == '') {
        throw new exception ($LN['error_posternotfound']);
    }
    $poster_name = trim(get_post('postername', ''));
    if ($poster_name == '') {
        throw new exception ($LN['error_namenotfound']);
    }

    $recovery_par = get_post('recovery', NULL);
    if ($recovery_par === NULL || !is_numeric($recovery_par)) {
        throw new exception ($LN['error_invalidrecsize']);
    }
    $filesize_rar = get_post('filesize', NULL);
    $filesize_rar = unformat_size($filesize_rar, 1024, 'k');
    if ($filesize_rar === NULL || !is_numeric($filesize_rar)) {
        throw new exception ($LN['error_invalidrarsize']);
    }
    $delete_files = get_post('delete_files', NULL);
    $status = POST_READY;
    $timestamp = trim(get_post('timestamp', NULL));
    $now = time();
    $size = dirsize($src_dir);
    if ($timestamp != NULL && $timestamp != '') {
        $time_int = strtotime($timestamp);
        if ($time_int === FALSE) {
            $timestamp = NULL;
            throw new exception ($LN['browse_invalid_timestamp'] . ": '$timestamp'");
        } else {
            if ($time_int < $now) { // the time is before now, so probably means tomorrow
                $time_int += 24 * 60 * 60; // next day
                $timestamp .= ' +1 day';
            }
            $now = $time_int;
        }
    } else {
        $timestamp = NULL;
    }

    $vals = array(
        'userid' => (int) $userid,
        'groupid' => (int) $groupID,
        'groupid_nzb' => (int) $groupID_nzb,
        'src_dir' => $src_dir,
        'tmp_dir' => $tmp_dir,
        'subject' => $subject,
        'poster_id' => $poster_id,
        'poster_name' =>  $poster_name,
        'recovery_par' => (int) $recovery_par,
        'filesize_rar' => (int) $filesize_rar,
        'delete_files' => (int) $delete_files,
        'status' => $status,
        'start_time' => $now,
        'size' => "$size",
        'nzb_file' => '',
        'stat_id' => 0,
        'file_count' => 0,
    );
    return $vals;
}

function add_post_to_db(DatabaseConnection $db, $userid, &$timestamp)
{
    $vals = get_post_vals($db, $userid, $timestamp);
    $id = $db->insert_query_2('postinfo', $vals, TRUE);
    $stat_id = add_stat_data($db, stat_actions::POST, 0, $userid);
    set_stat_id($db, $id, $stat_id, TRUE);

    return $id;
}

function update_post_db(DatabaseConnection $db, $userid, $postid, &$timestamp)
{
    $vals = get_post_vals($db, $userid, $timestamp);
    $db->update_query_2('postinfo', $vals, '"id" =?', array($postid));
}

$rprefs = load_config($db);
$postid = get_request('postid', '');
$uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);

if (!$uc->is_connected()) {
    throw new exception($LN['error_urddconnect']);
}

$timestamp = NULL;
if ($postid == '') { // we got a new post
    $id = add_post_to_db($db, $userid, $timestamp);
    $uc->post($id);
    if ($timestamp === NULL) {
        $uc->unpause(get_command(urdd_protocol::COMMAND_POST) . " $id");
    } else {
        $uc->schedule(get_command(urdd_protocol::COMMAND_CONTINUE), '"' . get_command(urdd_protocol::COMMAND_POST) . " $id\"", $timestamp);
    }
} else { // we need to update a post
    $uc->unschedule(get_command(urdd_protocol::COMMAND_CONTINUE), '"' . get_command(urdd_protocol::COMMAND_POST) . " $postid\"");
    update_post_db($db, $userid, $postid, $timestamp);
    if ($timestamp === NULL) {
        $uc->unpause(get_command(urdd_protocol::COMMAND_POST) . " $postid");
    } else {
        $uc->schedule(get_command(urdd_protocol::COMMAND_CONTINUE), '"' . get_command(urdd_protocol::COMMAND_POST) . " $postid\"", $timestamp);
    }
}
die_html('OK');
