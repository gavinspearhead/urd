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
 * $LastChangedDate: 2014-05-30 00:49:17 +0200 (vr, 30 mei 2014) $
 * $Rev: 3077 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_editposts.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathaet = realpath(dirname(__FILE__));

require_once "$pathaet/../functions/ajax_includes.php";

$cmd = get_request('cmd');
$postid = get_request('postid');

verify_access($db, urd_modules::URD_CLASS_POST, FALSE, 'P', $userid, TRUE);

if (!is_numeric($postid)) {
    throw new exception('Invalid post ID');
}

$prefs = load_config($db);
$uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);

switch (strtolower($cmd)) {
case 'start' :
    challenge::verify_challenge($_POST['challenge']);
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
    challenge::verify_challenge($_POST['challenge']);
    try {
        $uc->pause(get_command(urdd_protocol::COMMAND_POST) . " $postid");
        $uc->pause(get_command(urdd_protocol::COMMAND_POST_ACTION) . " $postid");
        $uc->pause(get_command(urdd_protocol::COMMAND_START_POST) . " $postid");
    } catch (exception $e) {
        throw new exception($LN['error_urddconnect']);
    }
    break;
case 'cancel' :
    challenge::verify_challenge($_POST['challenge']);
    try {
        $uc->cancel(get_command(urdd_protocol::COMMAND_POST) . " $postid");
        $uc->cancel(get_command(urdd_protocol::COMMAND_POST_ACTION) . " $postid");
        $uc->cancel(get_command(urdd_protocol::COMMAND_START_POST) . " $postid");
    } catch (exception $e) {
        throw new exception($LN['error_urddconnect']);
    }
    break;
case 'delete' :
    challenge::verify_challenge($_POST['challenge']);
    try {
        $uc->cancel(get_command(urdd_protocol::COMMAND_POST) . " $postid");
        $uc->cancel(get_command(urdd_protocol::COMMAND_POST_ACTION) . " $postid");
        $uc->cancel(get_command(urdd_protocol::COMMAND_START_POST) . " $postid");
    } catch (exception $e) {
        throw new exception($LN['error_urddconnect']);
    }
    if ($isadmin) {
        // Admins can delete any download
        $db->delete_query('postinfo', '"id" = ?', array($postid));
        $db->delete_query('post_files', '"postid" = ?', array($postid));
    } else {
        $sql = '* FROM postinfo WHERE "userid" = ? AND "id" = ?';
        $res = $db->select_query($sql, array($userid, $postid));
        if (isset($res[0]['id']) && $res[0]['id'] == $postid) {
            $db->delete_query('postinfo', '"id" = ?', array($postid));
            $db->delete_query('post_files', '"postid" = ?', array($postid));
        }
    }
    break;
default:
    throw new exception('Invalid command!');
}


// Success:
die_html('OK');
