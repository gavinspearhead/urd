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
 * $LastChangedDate: 2014-05-19 23:50:53 +0200 (ma, 19 mei 2014) $
 * $Rev: 3043 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_edittransfers.php 3043 2014-05-19 21:50:53Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathaet = realpath(dirname(__FILE__));

require_once "$pathaet/../functions/ajax_includes.php";

try {

    $cmd = get_request('cmd');
    $dlid = get_request('dlid');

    if (!is_numeric($dlid)) {
        throw new exception($LN['error_downloadnotfound'] . ': ' . $dlid);
    }

    $prefs = load_config($db);
    $uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);

    verify_access($db, urd_modules::URD_CLASS_DOWNLOAD, FALSE, '', $userid, TRUE);

    switch (strtolower($cmd)) {
        case 'reparrar':
            challenge::verify_challenge($_POST['challenge']);
            try {
                $uc->unpar_unrar($dlid);
            } catch (exception $e) {
                throw new exception($LN['error_urddconnect']);
            }
            break;

        case 'start' :
            challenge::verify_challenge($_POST['challenge']);
            // In case it's paused, continue it:
            try {
                $uc->continue_cmd(get_command(urdd_protocol::COMMAND_DOWNLOAD_ACTION) . ' ' . $dlid);
                $uc->continue_cmd(get_command(urdd_protocol::COMMAND_DOWNLOAD) . ' ' . $dlid);
            } catch (exception $e) {
                throw new exception($LN['error_urddconnect']);
            }
            break;

        case 'pause' :
            challenge::verify_challenge($_POST['challenge']);
            try {
                $uc->pause(get_command(urdd_protocol::COMMAND_DOWNLOAD_ACTION) . ' ' . $dlid);  // Cancel it, just in case; we need both as download is on queue only and download action can be queued and running
                $uc->pause(get_command(urdd_protocol::COMMAND_DOWNLOAD) . ' ' . $dlid);  // Cancel it, just in case
            } catch (exception $e) {
                throw new exception($LN['error_urddconnect']);
            }
            break;

        case 'cancel' :
            challenge::verify_challenge($_POST['challenge']);
            try {
                $uc->cancel(get_command(urdd_protocol::COMMAND_DOWNLOAD_ACTION) . ' ' . $dlid);  // Cancel it, just in case; see pauso
                $uc->cancel(get_command(urdd_protocol::COMMAND_DOWNLOAD) . ' ' . $dlid);  // Cancel it, just in case
                $comment = 'error_usercancel';
                update_dlinfo_comment($db, $dlid, $comment);
            } catch (exception $e) {
                throw new exception($LN['error_urddconnect']);
            }
            break;

        case 'delete' :
            challenge::verify_challenge($_POST['challenge']);
            try {
                $rv1 = $uc->cancel(get_command(urdd_protocol::COMMAND_DOWNLOAD_ACTION) . ' ' . $dlid);  // Cancel it, just in case; see pauso
                $rv2 = $uc->cancel(get_command(urdd_protocol::COMMAND_DOWNLOAD) . ' ' . $dlid);  // Cancel it, just in case
                if ($rv1 || $rv2) {
                    $comment = 'error_usercancel';
                    update_dlinfo_comment($db, $dlid, $comment);
                }
            } catch (exception $e) {
                throw new exception($LN['error_urddconnect']);
            }
            if ($isadmin) {
                // Admins can delete any download
                $db->delete_query('downloadinfo', '"ID"=?', array($dlid));
                $db->delete_query('downloadarticles', '"downloadID"=?', array($dlid));
            } else {
                $sql = '"ID" FROM downloadinfo WHERE "userid"=? AND "ID"=?';
                $res = $db->select_query($sql, array($userid, $dlid));
                if ($res[0]['ID'] == $dlid) {
                    $db->delete_query('downloadinfo', '"ID"=?', array($dlid));
                    $db->delete_query('downloadarticles', '"downloadID"=?', array($dlid));
                }
            }
            break;

        case 'showrename':
            // Get download info:
            $sql = '* FROM downloadinfo WHERE "ID"=:dlid';
            $res = $db->select_query($sql, 1, array(':dlid'=>$dlid));
            if (!isset($res[0]['name'])) {
                throw new exception ($LN['error_downloadnotfound']);
            }
            $row = $res[0];
            $oldname = $row['name'];
            $oldpw = $row['password'];
            if ($oldpw == PASSWORD_PLACE_HOLDER) {
                $oldpw = '';
            }

            $oldunpar = $row['unpar'];
            $oldunrar = $row['unrar'];
            $oldsubdl = $row['subdl'];
            $olddelete = $row['delete_files'];
            $dl_dir = $row['dl_dir'];
            $add_setname = $row['add_setname'];
            $status = $row['status'];
            $start_time = $row['start_time'];
            init_smarty();
            if (in_array($status, array(DOWNLOAD_READY, DOWNLOAD_QUEUED, DOWNLOAD_PAUSED)) && $start_time > time()) {
                $smarty->assign('starttime_noedit',	0);
            } else {
                $smarty->assign('starttime_noedit',	1);
            }
            if (in_array($status, array(DOWNLOAD_READY, DOWNLOAD_QUEUED, DOWNLOAD_PAUSED, DOWNLOAD_ACTIVE))) {
                $smarty->assign('dldir_noedit',	0);
            } else {
                $smarty->assign('dldir_noedit',	1);
            }

            $directories = get_directories($db, $userid);
            $starttime = date('Y-m-d H:i:s', $start_time);

            $smarty->assign('starttime', $starttime);
            $smarty->assign('directories', $directories);
            $smarty->assign('id',		$dlid);
            $smarty->assign('dl_dir',	$dl_dir);
            $smarty->assign('oldname',	$oldname);
            $smarty->assign('add_setname',	$add_setname);
            $smarty->assign('oldpw',	$oldpw);
            $smarty->assign('oldunrar',	$oldunrar);
            $smarty->assign('oldsubdl',	$oldsubdl);
            $smarty->assign('oldunpar',	$oldunpar);
            $smarty->assign('olddelete',	$olddelete);
            $contents = $smarty->fetch('ajax_edittransfers.tpl');
            return_result(array('contents' => $contents));
            break;

        case 'rename':
            // Actually rename the download
            challenge::verify_challenge($_POST['challenge']);
            $newname = trim(get_post('dlname', ''));
            $newpass = trim(get_post('dlpass', ''));
            $dl_dir = trim(get_post('dl_dir', ''));
            add_dir_separator($dl_dir);
            $newstarttime = strtotime(trim(get_post('starttime', '')));
            $sql = '"start_time" FROM downloadinfo WHERE "ID"=?';
            $res = $db->select_query($sql, 1, array($dlid));
            if (!isset($res[0]['start_time'])) {
                throw new exception($LN['error_downloadnotfound']);
            }
            $oldstarttime = $res[0]['start_time'];
            $start_time = NULL;
            if ($newstarttime !== FALSE) {
                $uc->unschedule(get_command(urdd_protocol::COMMAND_CONTINUE), '"' . get_command(urdd_protocol::COMMAND_DOWNLOAD) . " $dlid\"");
                $now = time();
                if ($newstarttime > $now) {
                    $start_time = $newstarttime;
                    $uc->schedule(get_command(urdd_protocol::COMMAND_CONTINUE), '"' . get_command(urdd_protocol::COMMAND_DOWNLOAD) . " $dlid\"", get_post('starttime'));
                } elseif ($oldstarttime > $now) {// if the start is in the future we have to start it if the new start time isn't
                    $uc->continue(get_command(urdd_protocol::COMMAND_DOWNLOAD), $dlid);
                    $start_time = $now;
                }
            }
            $unpar = (get_post('unpar', '0') == '1') ? 1 : 0;
            $unrar = (get_post('unrar', '0') == '1') ? 1 : 0;
            $subdl = (get_post('subdl', '0') == '1') ? 1 : 0;
            $add_setname = (get_post('add_setname', '0') == '1') ? 1 : 0;
            $delete = (get_post('delete', '0') == '1') ? 1 : 0;
            $cols = array('name', 'password', 'unrar', 'subdl', 'add_setname', 'dl_dir', 'unpar', 'delete_files');
            $vals = array($newname, $newpass, $unrar, $subdl, $add_setname, $dl_dir, $unpar, $delete);
            if ($start_time !== NULL) {
                $cols[] = 'start_time';
                $vals[] = $start_time;
            }

            $res = $db->update_query('downloadinfo', $cols, $vals, '"ID"=?', array($dlid));
            break;

        case 'move_up':
            challenge::verify_challenge($_POST['challenge']);
            try {
                $uc->move_cmd('UP', get_command(urdd_protocol::COMMAND_DOWNLOAD_ACTION), $dlid);
                $uc->move_cmd('UP', get_command(urdd_protocol::COMMAND_DOWNLOAD), $dlid);
            } catch (exception $e) {
                throw new exception($LN['error_urddconnect']);
            }
            break;

        case 'move_down':
            challenge::verify_challenge($_POST['challenge']);
            try {
                $uc->move_cmd('DOWN', get_command(urdd_protocol::COMMAND_DOWNLOAD_ACTION), $dlid);
                $uc->move_cmd('DOWN', get_command(urdd_protocol::COMMAND_DOWNLOAD), $dlid);
            } catch (exception $e) {
                throw new exception($LN['error_urddconnect']);
            }
            break;

        default:
            throw new exception($$LN['error_novalidaction']);
    }

    // Success:
    return_result();
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
