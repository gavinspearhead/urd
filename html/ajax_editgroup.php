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
 * $LastChangedDate: 2014-06-08 00:30:19 +0200 (zo, 08 jun 2014) $
 * $Rev: 3087 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_editgroup.php 3087 2014-06-07 22:30:19Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathaet = realpath(dirname(__FILE__));
require_once "$pathaet/../functions/ajax_includes.php";
require_once "$pathaet/../functions/periods.php";

verify_access($db, urd_modules::URD_CLASS_GROUPS, TRUE, '', $userid, TRUE);


function showeditgroup(DatabaseConnection $db, $id)
{
    global $LN, $smarty, $periods;
    // Get download info:
    if (is_numeric($id)) {
        $sql = '* FROM groups WHERE "ID"=?';
        $res = $db->select_query($sql, array($id));
        if (!isset($res[0])) {
            throw new exception($LN['error_invalidgroupid']);
        }
        $row = $res[0];
        $oldname = $row['name'];
        $oldadult = ($row['adult'] == ADULT_ON) ? 1 : 0;
        $oldsubscribed = $row['active'];
        $oldexpire = $row['expire'];

        $oldminsetsize = $row['minsetsize'] === NULL ? 0 : $row['minsetsize'];
        list($val, $suf) = format_size($oldminsetsize, 'h', '');
        $oldminsetsize =  $val . $suf;
        $oldmaxsetsize = $row['maxsetsize'] === NULL ? 0 : $row['maxsetsize'];
        list($val, $suf) = format_size($oldmaxsetsize, 'h', '');
        $oldmaxsetsize =  $val . $suf;
        $oldrefresh_time = $row['refresh_time'];
        $oldrefresh_period = $row['refresh_period'];

        if ($oldrefresh_period > 0) {
            $oldtime1 = floor($oldrefresh_time / 60);
            $oldtime2 = floor($oldrefresh_time % 60);
        } else {
            $oldtime1 = $oldtime2 = NULL;
        }
    } elseif ($id == 'new') {
        $oldname = $oldurl = $oldpassword = $oldusername = '';
        $oldexpire = get_config($db, 'default_expire_time');
        $oldadult = 0;
        $oldsubscribed = newsgroup_status::NG_SUBSCRIBED;
        $oldmaxsetsize = $oldminsetsize = 0;
        $time1 = $time2 = NULL;
        $oldrefresh_time = '';
    } else {
        throw new exception($LN['error_invalidfeedid']);
    }

    list($pkeys, $ptexts) = $periods->get_periods();
    init_smarty();
    $smarty->assign(array(
        'id'=>		        $id,
        'oldname'=>	        $oldname,
        'oldminsetsize'=>	$oldminsetsize,
        'oldmaxsetsize'=>	$oldmaxsetsize,
        'oldadult'=>	    $oldadult,
        'oldtime1'=>	    $oldtime1,
        'oldtime2'=>	    $oldtime2,
        'periods_texts'=>	$ptexts,
        'periods_keys'=>	$pkeys,
        'oldrefresh'=>      $oldrefresh_period,
        'oldsubscribed'=>	$oldsubscribed,
        'oldexpire'=>	    $oldexpire));
    return $smarty->fetch('ajax_editgroup.tpl');
}

function update_group_info(DatabaseConnection $db, $id, $userid)
{
    assert(is_numeric($userid));
    global $LN, $smarty, $periods;
    // Actually rename the download
    challenge::verify_challenge($_POST['challenge']);
    $newurl = trim(get_post('group_url'));
    $username = trim(get_post('group_username'));
    $time1 = trim(get_post('group_time1'));
    $time2 = trim(get_post('group_time2'));
    $refresh_period = trim(get_post('group_refresh_period'));
    $password = trim(get_post('group_password'));
    $newexpire = trim(get_post('group_expire'));

    if (!is_numeric($newexpire)) {
        throw new exception($LN['error_notanumber']. ': ' . $LN['ng_expire_time'] . ' ' . htmlentities($newexpire));
    }
    $newadult = trim(get_post('group_adult', '0') == '1') ?  ADULT_ON : ADULT_OFF;
    $newsubscribed = (get_post('group_subscribed', '0') == '1') ? newsgroup_status::NG_SUBSCRIBED : newsgroup_status::NG_UNSUBSCRIBED;
    $uprefs = load_config($db);
    $uc = new urdd_client($db, $uprefs['urdd_host'], $uprefs['urdd_port'], $userid);
    $newminsetsize = trim(get_post('group_minsetsize'));
    $newmaxsetsize = trim(get_post('group_maxsetsize'));
    try {
        $newminsetsize = unformat_size($newminsetsize, 1024, 'm');
        $newmaxsetsize = unformat_size($newmaxsetsize, 1024, 'm');
    } catch (exception $e) {
        throw new exception($LN['error_invalidvalue'] . ' ' . $e->getMessage());
    }
    if ($newmaxsetsize < $newminsetsize && $newmaxsetsize != 0 && $newminsetsize != 0) {
        throw new exception($LN['error_invalidvalue']);
    }

    if ($newsubscribed == newsgroup_status::NG_UNSUBSCRIBED) {
        $db->update_query_2('rss_urls', array('refresh_time'=>0, 'refresh_period'=>0), '"id"=?', array($id));

        $uc->cancel(get_command(urdd_protocol::COMMAND_UPDATE) . " $id");
        $uc->cancel(get_command(urdd_protocol::COMMAND_EXPIRE) . " $id");
        $uc->unsubscribe($id, USERSETTYPE_GROUP);
        remove_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE);
    } else {
        $uc->subscribe($id, $newexpire, $newminsetsize, $newmaxsetsize);
        $period = $periods->get($refresh_period);
        if ($period === FALSE) {
            throw new exception($LN['error_invalidupdatevalue']);
        }

        remove_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE);
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

            set_period($uc, $db, $id, $nicetime, $period->get_interval(), $time, $period->get_id());
        }
        if (is_numeric($id)) {
            update_group_state($db, $id, $newsubscribed, $newexpire, $newminsetsize, $newmaxsetsize, $newadult);
        } else {
            throw new exception($LN['error_invalidgroupid']);
        }
    }
}

try {
    $cmd = get_request('cmd', '');
    $id = get_request('id', '');

    $prefs = load_config($db);

    switch (strtolower($cmd)) {
        case 'showeditgroup':
            $contents = showeditgroup($db, $id);
            return_result(array('contents' => $contents));
            break;
        case 'update_group':
            update_group_info($db, $id, $userid);
            break;
        default:
            throw new exception($LN['error_invalidaction']);
            break;
    }
    return_result();
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}

