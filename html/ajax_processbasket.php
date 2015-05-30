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
 * $Id: ajax_processbasket.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */
if (!defined('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
}
$__auth = 'silent';

$pathpb = realpath(dirname(__FILE__));

require_once "$pathpb/../functions/ajax_includes.php";

verify_access($db, urd_modules::URD_CLASS_MAKENZB|urd_modules::URD_CLASS_DOWNLOAD, FALSE, '', $userid, TRUE);
if (!isset($_SESSION['setdata']) || !is_array($_SESSION['setdata'])) {
    $_SESSION['setdata'] = array();
}

function add_to_basket($setID, $type)
{
    $download_delay = get_request('timestamp', '');
    if ($download_delay != '') {
        $_SESSION['download_delay'] = $download_delay;
    }
    $dl_dir = get_request('dl_dir', '');
    if ($dl_dir != '' && $dl_dir !== 'null') {
        $_SESSION['dl_dir'] = $dl_dir;
    }
    $add_setname = get_request('add_setname', '');
    if ($add_setname != '') {
        $_SESSION['add_setname'] = $add_setname;
    }
    if (!isset($_SESSION['setdata'])) {
        $_SESSION['setdata'] = array();
    }
    if (count($_SESSION['setdata']) == 0) {
        clear_basket();
    }
    if (!in_setdata($setID, $type, $_SESSION['setdata'])) {
        $set = array('setid' => $setID, 'type' => $type);
        $_SESSION['setdata'][] = $set;
    }
}

function del_from_basket($setID)
{
    $newarray = array();
    foreach ($_SESSION['setdata'] as $set) {
        if ($set['setid'] != $setID) {
            $newarray[] = $set;
        }
    }
    $_SESSION['setdata'] = $newarray;
    if (count($newarray) == 0) {
        clear_basket();
    }
}

function clear_basket()
{
    $_SESSION['setdata'] = array();
    unset($_SESSION['download_delay'], $_SESSION['dl_dir'], $_SESSION['add_setname'], $_SESSION['dlsetname']);
}

function get_setid()
{
    global $LN;
    if (!isset($_REQUEST['setID'])) {
        throw new exception($LN['error_nosetids']);
    }

    return $_REQUEST['setID'];
}

function display_basket(DatabaseConnection $db, $userid)
{
    global $smarty, $LN;
    $addedsets = array();
    $totalsize = $cnt = $add_setname = $feedid = $groupid = 0;
    $dlsetname = $bettersetname = $download_delay = $dl_dir = '';
    $spot_cat = -1;
    $dltype = NULL; // we'll take the first one in the basket.
    $show_merge = TRUE;
    $nrofsets = (is_array($_SESSION['setdata']) ? count($_SESSION['setdata']) : 0);
    if ($nrofsets > 0) {
        $dlsetname = sanitise_download_name($db, simplify_chars(get_request('dlsetname', '')));
        if ($dlsetname == '') {
            $dlsetname = get_session('dlsetname', '');
        }
        $category = get_request('save_category', '');
        $add_setname = get_request('add_setname', '');
        if ($add_setname == '') {
            $add_setname = get_session('add_setname', get_pref($db, 'add_setname', $userid) ? 1 : 0);
        }
        $dl_dir = get_request('dl_dir', '');
        if ($dl_dir == '') {
            $dl_dir = get_session('dl_dir', '');
        }
        $download_delay = get_request('download_delay', '');
        if ($download_delay == '') {
            $download_delay = get_session('download_delay', '');
        }
        if ($download_delay == '') {
            $download_delay_val = get_pref($db, 'download_delay', $userid, 0);
            if ($download_delay_val > 0) {
                $download_delay = "+$download_delay_val minutes";
            }
        }

        // For each set that's in the basket:
        foreach ($_SESSION['setdata'] as $set) {
            $setID = $set['setid'];
            $type = $set['type'];
            if ($type == 'group') {
                $res = $db->select_query('setdata."subject", setdata."size", extsetdata."value", "groupID" FROM (setdata LEFT JOIN extsetdata ON '
                    . ' setdata."ID" = extsetdata."setID" AND extsetdata."name"=? AND extsetdata."type"=?) where "ID"=?', 1, array('setname', USERSETTYPE_GROUP, $setID));
                if (!isset($res[0])) {
                    continue;
                }
                $setname = $res[0]['subject'];
                $setsize = $res[0]['size'];
                $extsetname = $res[0]['value'];
                $groupid = $res[0]['groupID'];
                if ($dltype === NULL) {
                    $dltype = USERSETTYPE_GROUP;
                }
            } elseif ($type == 'rss') {
                $res = $db->select_query('"setname", "size", extsetdata."value", "rss_id"  FROM (rss_sets LEFT JOIN extsetdata ON ' .
                    'rss_sets."setid" = extsetdata."setID" AND extsetdata."name"=? AND extsetdata."type"=?) WHERE rss_sets."setid"=?', 1, array('setname',USERSETTYPE_RSS, $setID));
                if (!isset($res[0])) {
                    continue;
                }
                $setname = $res[0]['setname'];
                $extsetname = $res[0]['value'];
                $setsize = $res[0]['size'];
                $feedid = $res[0]['rss_id'];
                if ($dltype === NULL) {
                    $dltype = USERSETTYPE_RSS;
                }
                $show_merge = FALSE;
            } elseif ($type == 'spot') {
                $res = $db->select_query('"title", "size", extsetdata."value", "spotid", "category" FROM spots LEFT JOIN extsetdata ON ' .
                    'spots."spotid" = extsetdata."setID" AND extsetdata."name"=? AND extsetdata."type"=? WHERE spots."spotid"=?', 1, array('setname', USERSETTYPE_SPOT, $setID));
                if (!isset($res[0])) {
                    continue;
                }
                $setname = html_entity_decode($res[0]['title']);
                $extsetname = $res[0]['value'];
                $setsize = $res[0]['size'];
                $spot_cat = $res[0]['category'];
                if ($dltype === NULL) {
                    $dltype = USERSETTYPE_SPOT;
                }
                $show_merge = FALSE;
            } else {
                throw new exception($LN['error_unknowntype'] . ': ' . $type);
            }
            $totalsize += $setsize;
            list($size, $suffix) = format_size($setsize, 'h', $LN['byte_short'], 1024, 1);
            $setsize = $size . $suffix;
            // If there's extsetinfo, create a fancy download name:
            if ($extsetname != '') {
                $bettersetname = create_extset_download_name($db, $setID);
            } else { // No extsetinfo, use the original setname:
                $bettersetname = $setname;
            }
            if ($dl_dir == '' && ($groupid != 0 || $feedid != 0 || $spot_cat >= 0 || $category != '') && $dltype !== NULL) {
                if ($dltype == USERSETTYPE_GROUP) {
                    list($dl_dir) = get_user_dlpath($db, FALSE, $groupid, $dltype, $userid, $bettersetname, 'DOWNLOAD', $setID, $category);
                } elseif ($dltype == USERSETTYPE_RSS) {
                    list($dl_dir) = get_user_dlpath($db, FALSE, $feedid, $dltype, $userid, $bettersetname, 'DOWNLOAD', $setID, $category);
                } else {
                    list($dl_dir) = get_user_dlpath($db, FALSE, $spot_cat, $dltype, $userid, $bettersetname, 'DOWNLOAD', $setID, $category);
                }
                $username = get_username($db, $userid);
                $base_dlpath = get_dlpath($db);
                $base_dlpath = $base_dlpath . DONE_PATH . $username . DIRECTORY_SEPARATOR;
                $dl_dir = substr($dl_dir, strlen($base_dlpath));
            }
            $addedsets[] = array('subject' => $bettersetname, 'size' => $setsize);
            $cnt++;
        }

        // If this is the 1st item in the basket, use its bettersetname as the download name:
        if ($cnt > 0 && $dlsetname == '') {
            $dlsetname = find_name($db, $bettersetname);
            $_SESSION['dlsetname'] = $dlsetname;
        }
        if ($dlsetname != '' && !$add_setname) {
            add_dir_separator($dl_dir);
        }
        $_SESSION['download_delay'] = $download_delay;
        $_SESSION['dl_dir'] = $dl_dir;
        $_SESSION['add_setname'] = $add_setname;
        $_SESSION['dlsetname'] = $dlsetname;
    }
    list($size, $suffix) = format_size($totalsize, 'h', $LN['byte_short'], 1024, 1);
    $totalsize = $size . $suffix;
    $directories = get_directories($db, $userid);
    // only show the merge button if it is useful. IE when there are 2 or more sets and all of them are from groups (not RSS/Spots)
    $show_merge = ($show_merge && count($addedsets) >= 2);
    $default_basket_type = get_pref($db, 'basket_type', $userid, basket_type::LARGE);
    $basket_type = get_request('basket_type', $default_basket_type);
    $_SESSION['basket_type'] = ($basket_type == basket_type::SMALL ? basket_type::SMALL : basket_type::LARGE);
    init_smarty();
    $smarty->assign(array(
        'dlsetname'=>        $dlsetname,
        'nrofsets'=>         $nrofsets,
        'show_merge'=>       $show_merge,
        'download_delay'=>   $download_delay,
        'dl_dir'=>           $dl_dir,
        'add_setname'=>      $add_setname,
        'addedsets'=>        $addedsets,
        'directories'=>      $directories,
        'totalsize'=>        $totalsize,
        'maxstrlen'=>        get_pref($db, 'maxsetname', $userid)));
    if ($basket_type == basket_type::SMALL) {
        $contents = $smarty->fetch('ajax_showminibasket.tpl');
    } else {
        $contents = $smarty->fetch('ajax_showbasket.tpl');
    }
    return_result(array('contents' => $contents));
}

function process_which_button(DatabaseConnection $db, $userid, $type, $groupID /* or feedid*/)
{
    assert(is_numeric($userid));
    global $LN;
    $message = '';
    $whichbutton = get_request('whichbutton');
    $all = get_request('all', 0);
    // remove interesting marking from all sets on page
    if ($whichbutton == 'unmark_int_all' && isset($_POST['set_ids'])) {
        challenge::verify_challenge($_POST['challenge']);
        $_groupID  = ($groupID == 0 || $groupID == '') ? NULL : $groupID;
        if ($all) {
            sets_marking::unmark_all($db, $userid, 'statusint', $_groupID, $type, sets_marking::MARKING_OFF, TRUE);
        } else {
            sets_marking::unmark_all2($db, $userid, $_POST['set_ids'], 'statusint', $_groupID, $type, sets_marking::MARKING_OFF, TRUE);
        }
    } elseif ($whichbutton == 'wipe_all' && isset($_POST['set_ids'])) {
        challenge::verify_challenge($_POST['challenge']);
        wipe_sets($db, $_POST['set_ids'], $type, $userid);
        $message = $LN['browse_deletedsets'];
    }
    // mark all sets as not deleted on page
    elseif ($whichbutton == 'unmark_kill_all' && isset($_POST['set_ids'])) {
        challenge::verify_challenge($_POST['challenge']);
        $_groupID  = ($groupID == 0 || $groupID == '') ? NULL : $groupID;
        sets_marking::unmark_all2($db, $userid, $_POST['set_ids'], 'statuskill', $_groupID, $type, sets_marking::MARKING_OFF, TRUE);
    }
    // mark all sets as deleted on page
    elseif ($whichbutton == 'mark_kill_all' && isset($_POST['set_ids'])) {
        challenge::verify_challenge($_POST['challenge']);
        $_groupID  = ($groupID == 0 || $groupID == '') ? NULL : $groupID;
        $skip_int = (bool) get_pref($db, 'skip_int', $userid, 0);
        sets_marking::mark_all2($db, $userid, $_POST['set_ids'], 'statuskill', $_groupID,  $type, sets_marking::MARKING_ON, TRUE, $skip_int);
    }

    /* Create NZB */
    elseif ($whichbutton == 'getnzb' && count($_SESSION['setdata']) > 0) {
        challenge::verify_challenge($_POST['challenge']);
        $dlname = create_nzb($db, $userid);
        $message .= $LN['NZB_created'] . ': "' . $dlname. '"';
    }

    /* Download via Urdd */
    elseif ($whichbutton == 'urddownload' && count($_SESSION['setdata']) > 0) {
        challenge::verify_challenge($_POST['challenge']);
        $dlname = create_new_download($db, $userid);
        $message .= $LN['taskdownload'] . ': "' . $dlname. '"';
        clear_basket();
        // Connect to Urdd:
    } elseif ($whichbutton == 'checksize' && count($_SESSION['setdata']) > 0) {
        $total_size = get_basket_size($db);
        $diskspace = get_free_diskspace($db, $userid);
        if ($diskspace[0] < (2.5 * $total_size)) {
            list($size, $suffix) = format_size($diskspace[0], 'h', $LN['byte_short'], 1024, 1);
            throw new exception($LN['error_diskfull'] . " ($size $suffix)");
        }
    } elseif ($whichbutton == 'mergesets' && count($_SESSION['setdata']) > 0) {
        challenge::verify_challenge($_POST['challenge']);
        merge_sets($db, $userid);
        clear_basket();
    }

    /* Clear list? */
    elseif ($whichbutton == 'clearbasket') {
        challenge::verify_challenge($_POST['challenge']);
        clear_basket();
    }

    return $message;
}

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && get_request('whichbutton') != '') { // need to move this elsewhere possibly
        $type = get_post('type', 'groups');
        if ($type == 'groups') {
            $type = USERSETTYPE_GROUP;
            $group_id = get_post('group'); // temp
        } elseif ($type == 'spots') {
            $type = USERSETTYPE_SPOT;
            $group_id = NULL;
        } else {
            $type = USERSETTYPE_RSS;
            $group_id = get_post('feed'); // temp
        }
        $message = process_which_button($db, $userid, $type, $group_id);
        return_result(array('message' => $message));
    }

    $command = get_request('command', '');
    $type = get_request('type', 'none');

    switch ($command) {
    case 'add':
        $setID = get_setid();
        add_to_basket($setID, $type);
        break;
    case 'del':
        $setID = get_setid();
        del_from_basket($setID);
        break;
    case 'clear':
        clear_basket();
        break;
    case 'get':
        return_result(array('error' => 0, 'basket_type' => (get_session('basket_type', basket_type::SMALL) == basket_type::SMALL) ? basket_type::SMALL : basket_type::LARGE));
        break;
    case 'set':
        $basket_type = get_request('basket_type', basket_type::SMALL);
        $_SESSION['basket_type'] = ($basket_type == basket_type::SMALL) ? basket_type::SMALL : basket_type::LARGE;
        break;
    case 'view':
        display_basket($db, $userid);
        break;
    default:
        throw new exception($LN['error_invalidaction']);
    }
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
