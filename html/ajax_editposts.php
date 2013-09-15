<?php
/*
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
 * $Id: ajax_editposts.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathaet = realpath(dirname(__FILE__));

require_once "$pathaet/../functions/html_includes.php";
require_once "$pathaet/../functions/functions.php";

$cmd = get_request('cmd');
$postid = get_request('postid');

$db->escape($cmd);
$db->escape($postid);

verify_access($db, urd_modules::URD_CLASS_POST, FALSE, 'P', $userid, TRUE);

if (!is_numeric($postid)) {
    throw new exception('Invalid post ID');
}

$prefs = load_config($db);
$uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);

switch (strtolower($cmd)) {
case 'start' :
    challenge::verify_challenge_text($_POST['challenge']);
    // In case it's paused, continue it:
    try {
        $uc->continue_cmd(get_command(urdd_protocol::COMMAND_POST) . " $postid");
        $uc->continue_cmd(get_command(urdd_protocol::COMMAND_POST_ACTION) . " $postid");
        $uc->continue_cmd(get_command(urdd_protocol::COMMAND_START_POST) . " $postid");
    } catch (exception $e) {
        throw new exception($LN['error_urddconnect']);
    }
    break;
case 'pause' :
    challenge::verify_challenge_text($_POST['challenge']);
    try {
        $uc->pause(get_command(urdd_protocol::COMMAND_POST) . " $postid");
        $uc->pause(get_command(urdd_protocol::COMMAND_POST_ACTION) . " $postid");
        $uc->pause(get_command(urdd_protocol::COMMAND_START_POST) . " $postid");
    } catch (exception $e) {
        throw new exception($LN['error_urddconnect']);
    }
    break;
case 'cancel' :
    challenge::verify_challenge_text($_POST['challenge']);
    try {
        $uc->cancel(get_command(urdd_protocol::COMMAND_POST) . " $postid");
        $uc->cancel(get_command(urdd_protocol::COMMAND_POST_ACTION) . " $postid");
        $uc->cancel(get_command(urdd_protocol::COMMAND_START_POST) . " $postid");
    } catch (exception $e) {
        throw new exception($LN['error_urddconnect']);
    }
    break;
case 'delete' :
    challenge::verify_challenge_text($_POST['challenge']);
    try {
        $uc->cancel(get_command(urdd_protocol::COMMAND_POST) . " $postid");
        $uc->cancel(get_command(urdd_protocol::COMMAND_POST_ACTION) . " $postid");
        $uc->cancel(get_command(urdd_protocol::COMMAND_START_POST) . " $postid");
    } catch (exception $e) {
        throw new exception($LN['error_urddconnect']);
    }
    if ($isadmin) {
        // Admins can delete any download
        $db->delete_query('postinfo', "\"id\" = '$postid'");
        $db->delete_query('post_files', "\"postid\" = '$postid'");
    } else {
        $sql = "SELECT * FROM postinfo WHERE \"userid\" = '$userid' AND \"id\" = '$postid'";
        $res = $db->execute_query($sql);
        if (isset($res[0]['id']) && $res[0]['id'] == $postid) {
        $db->delete_query('postinfo', "\"id\" = '$postid'");
        $db->delete_query('post_files', "\"postid\" = '$postid'");
        }
    }
    break;
default:
    throw new exception('Invalid command!');
}


// Success:
die_html('OK');
