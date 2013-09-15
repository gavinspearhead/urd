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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_editrss.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathaet = realpath(dirname(__FILE__));
require_once "$pathaet/../functions/html_includes.php";
require_once "$pathaet/../functions/periods.php";

verify_access($db, urd_modules::URD_CLASS_RSS, TRUE, '', $userid, TRUE);

$cmd = get_request('cmd', '');
$id = get_request('id', '');

function deleterssfeed(DatabaseConnection $db, $id)
{
    global $LN;
    if (is_numeric($id) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        delete_rss_feed($db, $id);
        $uprefs = load_config($db);
        $uc = new urdd_client($db, $uprefs['urdd_host'], $uprefs['urdd_port'], $userid);
        $uc->cancel(get_command(urdd_protocol::COMMAND_UPDATE_RSS) . " $id");
        $uc->cancel(get_command(urdd_protocol::COMMAND_EXPIRE_RSS) . " $id");
        $uc->unsubscribe($id, USERSETTYPE_RSS);
        remove_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE_RSS);
        $uc->disconnect();
    } else {
        throw new exception($LN['error_invalidfeedid']);
    }
}

function show_edit_rss(DatabaseConnection $db, $id)
{
    global $smarty, $LN, $periods;
    // Get download info:
    if (is_numeric($id)) {
        $db->escape($id);
        $sql = "SELECT * FROM rss_urls WHERE \"id\" = '$id'";
        $res = $db->execute_query($sql);
        if (!isset($res[0])) {
            throw new exception($LN['error_invalidfeedid']);
        }
        $row = $res[0];
        $oldname = $row['name'];
        $oldadult = $row['adult'] ? 1 : 0;
        $oldurl = $row['url'];
        $oldusername = $row['username'];
        $oldpassword = $row['password'];
        $oldsubscribed = $row['subscribed'];
        $oldexpire = $row['expire'];

        $oldrefresh_time = $row['refresh_time'];
        $oldrefresh_period = $row['refresh_period'];

        if ($oldrefresh_period > 0) {
            $oldtime1 = floor($oldrefresh_time / 60);
            $oldtime2 = floor($oldrefresh_time % 60);
        } else {
            $oldtime1 = $oldtime2 = NULL;
        }
    } elseif ($id == 'new') {
        $oldrefresh_time = $oldrefresh_period = $oldname = $oldurl = $oldpassword = $oldusername = '';
        $oldexpire = get_config($db, 'default_expire_time');
        $oldadult = 0;
        $oldsubscribed = RSS_SUBSCRIBED;
        $oldtime1 = $oldtime2 = NULL;
    } else {
        throw new exception($LN['error_invalidfeedid']);
    }

    list($pkeys, $ptexts) = $periods->get_periods();
    init_smarty('', 0);
    $smarty->assign('id',		        $id);
    $smarty->assign('oldname',	        $oldname);
    $smarty->assign('oldadult',	        $oldadult);
    $smarty->assign('oldtime1',	        $oldtime1);
    $smarty->assign('oldtime2',	        $oldtime2);
    $smarty->assign('periods_texts',	$ptexts);
    $smarty->assign('periods_keys',		$pkeys);
    $smarty->assign('oldrefresh',       $oldrefresh_period);
    $smarty->assign('oldpassword',	    $oldpassword);
    $smarty->assign('oldusername',	    $oldusername);
    $smarty->assign('oldurl',	        $oldurl);
    $smarty->assign('oldsubscribed',	$oldsubscribed);
    $smarty->assign('oldexpire',	    $oldexpire);
    $smarty->display('ajax_editrss.tpl');
    die();
}

function update_rss(DatabaseConnection $db, $id, $userid)
{
    global $periods, $LN;
    $newname = trim(get_post('rss_name', ''));
    $newurl = trim(get_post('rss_url'));
    $username = trim(get_post('rss_username'));
    $time1 = trim(get_post('rss_time1'));
    $time2 = trim(get_post('rss_time2'));
    $refresh_period = trim(get_post('rss_refresh_period'));
    $password = trim(get_post('rss_password'));
    $newexpire = trim(get_post('rss_expire'));
    $newadult = trim(get_post('rss_adult', '0') == '1') ? 1 : 0;
    $newsubscribed = (get_post('rss_subscribed', '0') == '1') ? RSS_SUBSCRIBED : RSS_UNSUBSCRIBED;
    $uprefs = load_config($db);
    $uc = new urdd_client($db, $uprefs['urdd_host'], $uprefs['urdd_port'], $userid);

    if ($newname == '' || $newurl == '') {
        throw new exception($LN['error_invalidvalue']);
    }
    if ($newsubscribed == RSS_UNSUBSCRIBED) {
        $sql = "UPDATE rss_urls SET \"refresh_time\"='0', \"refresh_period\"='0' WHERE \"id\"=$id";
        $db->execute_query($sql);

        $uc->cancel(get_command(urdd_protocol::COMMAND_UPDATE_RSS) . " $id");
        $uc->cancel(get_command(urdd_protocol::COMMAND_EXPIRE_RSS) . " $id");
        $uc->unsubscribe($id, USERSETTYPE_RSS);
        remove_rss_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE_RSS );
    }
    try {
        if (is_numeric($id)) {
            update_rss_url($db, $newname, $newurl, $newsubscribed, $newexpire, $username, $password, $id, $newadult);
        } elseif ($id == 'new') {
            $id = add_rss_url($db, $newname, $newurl, $newsubscribed, $newexpire, $username, $password, $newadult);
            $uc->update($id, USERSETTYPE_RSS);
        } else {
            throw new exception($LN['error_invalidfeedid']);
        }
    } catch (exception $e) {
        throw new exception($e->getMessage());
    }
    $period = $periods->get($refresh_period);
    if ($period === FALSE) {
        throw new exception($LN['error_invalidupdatevalue']);
    }

    remove_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE_RSS);
    if ($period->get_interval() !== NULL) {
        if (!is_numeric($time1)) {
            throw new exception($LN['error_notanumber']);
        }
        if (!is_numeric($time2)) {
            throw new exception($LN['error_notanumber']);
        }
        if ($time1 > 23 || $time1 < 0) {
            throw new exception($LN['error_toomanydays']);
        }
        if ($time2 > 59 || $time1 < 0) {
            throw new exception($LN['error_toomanymins']);
        }

        // Sanity checks:
        $time1 %= 24;
        $time2 %= 60;
        $time = $time1 * 60 + $time2; // Used to display the update time, is in mins after 0:00
        $nicetime = $time1 . ':' . $time2;
        // if we have a proper schedule set it here

        set_period_rss($uc, $db, $id, $nicetime, $period->get_interval(), $time, $period->get_id());
    }
}

switch (strtolower($cmd)) {
case 'delete' :
    challenge::verify_challenge_text($_POST['challenge']);
    deleterssfeed($db, $id);
    break;
case 'showeditrss':
    show_edit_rss($db, $id);
    break;
case 'update_rss':
    // Actually rename the download
    challenge::verify_challenge_text($_POST['challenge']);
    update_rss($db, $id, $userid);
    break;
default:
    throw new exception($LN['error_invalidaction']);
    break;
}

// Success:
die_html('OK');
