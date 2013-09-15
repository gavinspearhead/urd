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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: web_functions.php 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathwf = realpath(dirname(__FILE__));

require_once "$pathwf/defines.php";
require_once "$pathwf/functions.php";
require_once "$pathwf/autoincludes.php";

function redirect($url, $delay = 0)
{
    assert(is_numeric($delay) && $url != '');
    $output = <<<OUT
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="refresh" content="$delay;url=$url">
</head>
</html>
OUT;
    echo $output;
    exit(NO_ERROR);
}

function get_cookie($var, $default='')
{
    assert($var !== NULL);
    if (!isset($_COOKIE[$var])) {
        return $default;
    }

    return $_COOKIE[$var];
}

function get_request($var, $default='')
{
    assert($var !== NULL);
    if (!isset($_REQUEST[$var])) {
        return $default;
    }

    return $_REQUEST[$var];
}

function get_session($var, $default='')
{
    assert($var !== NULL);
    if (!isset($_SESSION[$var])) {
        return $default;
    }

    return $_SESSION[$var];
}

function get_post($var, $default='')
{
    assert($var !== NULL);
    if (!isset($_POST[$var])) {
        return $default;
    }

    return $_POST[$var];
}

function count_active_ng(DatabaseConnection $db)
{
    $hidden_groups = SPOTS_GROUPS::get_hidden_groups();
    $q_hidden = '';
    if (count($hidden_groups) > 0) {
        $h = '';
        foreach ($hidden_groups as $gr) {
            $db->escape($gr, TRUE);
            $h .= " $gr,";
        }
        $h = rtrim($h, ',');
        $q_hidden = " AND \"name\" NOT IN ($h) ";
    }

    $sql = "count(*) AS cnt FROM groups WHERE \"active\"='" . NG_SUBSCRIBED . "'" . $q_hidden;
    $res = $db->select_query($sql);

    return $res[0]['cnt'];
}

function count_active_rss(DatabaseConnection $db)
{
    $sql = "count(*) AS cnt FROM rss_urls WHERE \"subscribed\"='" . NG_SUBSCRIBED . "'";
    $res = $db->select_query($sql);

    return $res[0]['cnt'];
}

function remove_rss_schedule(DatabaseConnection $db, urdd_client $uc, $id, $cmd)
{
    assert(is_numeric($id));
    $sql = "UPDATE rss_urls SET \"refresh_time\"=0, \"refresh_period\"=0 WHERE \"id\"='$id'";
    $db->execute_query($sql);
    $uc->unschedule(get_command($cmd), $id);
}

function remove_schedule(DatabaseConnection $db, urdd_client $uc, $id, $cmd)
{
    assert(is_numeric($id));
    $sql = "UPDATE groups SET \"refresh_time\"=0, \"refresh_period\"=0 WHERE \"ID\"='$id'";
    $db->execute_query($sql);
    $uc->unschedule(get_command($cmd), $id);
}

function check_urdd_online(DatabaseConnection $db)
{
    $prefs = load_config($db);
    $uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], 0);
    $status = $uc->can_connect();

    return $status;
}

function stop_urdd($userid)
{
    assert(is_numeric($userid));
    global $prefs, $db;
    $uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);
    if ($uc->is_connected()) {
        $uc->shutdown();
    }
    usleep(500000);
}

function command_description(DatabaseConnection $db, $task)
{
    global $LN;
    $task_parts = explode (' ', $task);
    $cmd_code = get_command_code($task_parts[0]);
    $task = array('', '', 0, '');
    try {
        $name = '';
        switch ($cmd_code) {
        case urdd_protocol::COMMAND_CONTINUE : 		            //continue first cause it will hit on other tasks as well
            array_shift($task_parts);
            $cmd = implode(' ', $task_parts);
            $task[0] = $LN['taskcontinue'];
            list ($t, $a) = command_description($db, $cmd);
            $task[1] = "$t $a";
            $task[3] = '';
            break;
        case urdd_protocol::COMMAND_PAUSE:	 //continue first cause it will hit on other tasks as well
            array_shift($task_parts);
            $cmd = implode(' ', $task_parts);
            $task[0] = $LN['taskpause'];
            list ($t, $a) = command_description($db, $cmd);
            $task[3] = '';
            break;
        case urdd_protocol::COMMAND_UPDATE:
            $groupID = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskupdate'];
            if (is_numeric($groupID)) {
                $group_name = shorten_newsgroup_name(group_name($db, $groupID),0);
                $task[1] = htmlentities($group_name);
            }
            $task[3] = 'update';
            break;
        case urdd_protocol::COMMAND_PURGE:
            $groupID = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskpurge'];
            if (is_numeric($groupID)) {
                $group_name = shorten_newsgroup_name(group_name($db, $groupID),0);
                $task[1]= htmlentities($group_name);
            }
            $task[3] = 'purge';
            break;
        case urdd_protocol::COMMAND_EXPIRE:
            $groupID = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskexpire'];
            if (is_numeric($groupID)) {
                $group_name = shorten_newsgroup_name(group_name($db, $groupID),0);
                $task[1] = htmlentities($group_name);
            }
            $task[3] = 'expire';
            break;
        case urdd_protocol::COMMAND_GENSETS:
            $groupID = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskgensets'];
            if (is_numeric($groupID)) {
                $group_name = shorten_newsgroup_name(group_name($db, $groupID),0);
                $task[1] = htmlentities($group_name);
            }
            $task[3] = 'update';
            break;
        case urdd_protocol::COMMAND_PARSE_NZB:
            $task[0] = $LN['taskparsenzb'];
            $task[3] = 'parsenzb';
            break;
        case urdd_protocol::COMMAND_UPDATE_RSS:
            $feed_id = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskupdate'];
            try {
                $name = feed_name($db, $feed_id);
            } catch (exception $e) {
                $name = '';
            }
            $task[1] = htmlentities($name);
            $task[3] = '';
            break;
        case urdd_protocol::COMMAND_PURGE_RSS:
            $feed_id = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskpurge'];
            try {
                $name = feed_name($db, $feed_id);
            } catch (exception $e) {
                $name = '';
            }
            $task[1]= htmlentities($name);
            $task[3] = 'purge';
            break;
        case urdd_protocol::COMMAND_EXPIRE_RSS:
            $feed_id = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskexpire'];
            try {
                $name = feed_name($db, $feed_id);
            } catch (exception $e) {
                $name = '';
            }
            $task[1] = htmlentities($name);
            $task[3] = 'expire';
            break;
        case urdd_protocol::COMMAND_DOWNLOAD:
        case urdd_protocol::COMMAND_DOWNLOAD_ACTION:
            $id = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskdownload'] .' ';
            try {
                $name = htmlentities(get_download_name($db, $id));
                $task[2] = $id;
            } catch (exception $e) {
                $name = '';
            }
            $task[1] = htmlentities($name);
            $task[3] = 'download';
            break;
        case urdd_protocol::COMMAND_GROUPS:
            $task[0] = $LN['taskgrouplist'];
            $task[3] = 'grouplist';
            break;
        case urdd_protocol::COMMAND_UNPAR_UNRAR:
            $id = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskunparunrar'];
            try {
                $name = htmlentities(get_download_name($db, $id));
                $task[2] = $id;
            } catch (exception $e) {
                $name = '';
            }
            $task[1]= htmlentities($name);
            $task[3] = 'unparunrar';
            break;
        case urdd_protocol::COMMAND_OPTIMISE:
            $task[0] = $LN['taskoptimise'];
            $task[3] = 'optimise';
            break;
        case urdd_protocol::COMMAND_CLEANDB:
            $task[0] = $LN['taskcleandb'];
            $task[3] = 'cleandb';
            break;
        case urdd_protocol::COMMAND_CLEANDIR:
            $dir = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskcleandir'];
            $task[1] = htmlentities($dir);
            $task[3] = 'cleandir';
            break;
        case urdd_protocol:: COMMAND_GETWHITELIST:
            $task[0] = $LN['taskgetwhitelist'];
            $task[3] = 'getbwhitelist';
            break;
        case urdd_protocol:: COMMAND_GETBLACKLIST:
            $task[0] = $LN['taskgetblacklist'];
            $task[3] = 'getblacklist';
            break;
        case urdd_protocol::COMMAND_CHECK_VERSION:
            $task[0] = $LN['taskcheckversion'];
            $task[3] = 'checkversion';
            break;
        case urdd_protocol::COMMAND_SENDSETINFO:
            $task[0] = $LN['tasksendsetinfo'];
            $task[3] = 'sendsetinfo';
            break;
        case urdd_protocol::COMMAND_GETSETINFO:
            $task[0] = $LN['taskgetsetinfo'];
            $task[3] = 'getsetinfo';
            break;
        case urdd_protocol::COMMAND_ADDDATA:
        case urdd_protocol::COMMAND_ADDSPOTDATA:
            $id = $task_parts[1];
            $preview = $task_parts[count($task_parts)-1];
            $preview = ($preview == 'preview') ? TRUE : FALSE;
            if ($preview) {
                $name = 'preview ' . $id;
                $task[2] = $id;
            } else {
                try {
                    $name = htmlentities(get_download_name($db, $id));
                    $task[2] = $id;
                } catch (exception $e) {
                    $name = '';
                }
            }
            $task[0] = $LN['taskadddata'];
            $task[1] = $name;
            $task[3] = 'adddata';
            break;
        case urdd_protocol::COMMAND_MERGE_SETS:
            $task[0] = $LN['taskmergesets'];
            $task[3] = 'mergesets';
            break;
        case urdd_protocol::COMMAND_FINDSERVERS:
            $task[0] = $LN['taskfindservers'];
            $task[3] = 'findservers';
            break;
        case urdd_protocol::COMMAND_MAKE_NZB:
            $id = $task_parts[1];
            try {
                $name = htmlentities(get_download_name($db, $id));
                $task[2] = $id;
            } catch (exception $e) {
                $name = '';
            }
            $task[0] = $LN['taskmakenzb'];
            $task[1] = $name;
            $task[3] = 'makenzb';
            break;
        case urdd_protocol::COMMAND_POST_MESSAGE:
            $task[0] = $LN['taskpostmessage'];
            $task[3] = '';
            break;
        case urdd_protocol::COMMAND_POST_ACTION:
        case urdd_protocol::COMMAND_START_POST:
        case urdd_protocol::COMMAND_POST:
            $id = $task_parts[count($task_parts)-1];
            try {
                $name = get_post_name($db, $id);
                $task[2] = $id;
            } catch (exception $e) {
                $name = '';
            }
            $task[0] = $LN['taskpost'] . ' ';
            $task[1] = htmlentities($name);
            $task[3] = 'post';
            break;
        case urdd_protocol::COMMAND_DELETE_SET_RSS:
        case urdd_protocol::COMMAND_DELETE_SPOT:
        case urdd_protocol::COMMAND_DELETE_SET:
            $task[0] = $LN['taskdeleteset'];
            $n = max(count($task_parts) -2, 0);
            $task[1] = $task_parts[1];
            if ($n > 0) {
                $task[1] .= " + $n " . $LN['sets'];
            }
            $task[3] = 'purge';
            break;
        case urdd_protocol::COMMAND_GETNFO:
            $task[0] = $LN['taskgetnfo'];
            $task[3] = 'update';
            break;
        case urdd_protocol::COMMAND_GETSPOT_COMMENTS:
            $task[0] = $LN['taskgetspot_comments'];
            $task[3] = 'update';
            break;
        case urdd_protocol::COMMAND_GETSPOT_IMAGES:
            $task[0] = $LN['taskgetspot_images'];
            $task[3] = 'update';
            break;
        case urdd_protocol::COMMAND_GETSPOT_REPORTS:
            $task[0] = $LN['taskgetspot_reports'];
            $task[3] = 'update';
            break;
        case urdd_protocol::COMMAND_GETSPOTS:
            $task[0] = $LN['taskgetspots'];
            $task[3] = 'update';
            break;
        case urdd_protocol::COMMAND_EXPIRE_SPOTS:
            $task[0] = $LN['taskexpirespots'];
            $task[3] = 'expire';
            break;
        case urdd_protocol::COMMAND_PURGE_SPOTS:
            $task[0] = $LN['taskpurgespots'];
            $task[3] = 'purge';
            break;
        default:
            $task[0] = $LN['taskunknown'];
            $task[3] = 'download';
            break;
        }

        return $task;
    } catch (exception $e) {
        return array($LN['taskunknown'], '', 0);
    }
}

function get_maxperpage(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));

    return get_pref($db, 'setsperpage', $userid, DEFAULT_PER_PAGE);
}

function get_languages()
{
    $langdir = realpath(dirname(__FILE__)). '/lang/';
    $l = glob($langdir . '*.php');
    $langs = array();
    foreach ($l as $lang) {
        if (strcasecmp(basename($lang), 'index.php') == 0) {
            continue;
        }
        $k = basename($lang);
        $v = ucfirst(basename($lang, '.php'));
        $langs[$k] = $v;
    }

    return $langs;
}

function select_language(DatabaseConnection $db, $userid)
{
    $language = NULL;
    if ($userid !== NULL) {
        assert(is_numeric($userid));
        try {
            $language = get_pref($db, 'language', $userid);
        } catch (exception $e) {
        }
    }

    if ($language === NULL) {
        try {
            $language = get_config($db, 'default_language');
        } catch (exception $e) {
            $language = NULL;
        }
    }

    return $language;
}

function get_templates()
{
    global $tpldir;
    $t = glob($tpldir . '*', GLOB_ONLYDIR|GLOB_MARK);
    $tpls = array();
    foreach ($t as $tpl) {
        $bn = basename($tpl);
        if ($bn[0] == '.') {
            continue;
        }
        $k = $bn;
        $v = ucfirst($bn);
        $tpls[$k] = $v;
    }

    return $tpls;
}

function select_template(DatabaseConnection $db, $userid)
{
    $template = NULL;
    if ($userid !== NULL) {
        assert(is_numeric($userid));
        try {
            $template = get_pref($db, 'template', $userid);
        } catch (exception $e) {
        }
    }
    if ($template === NULL) {
        try {
            $template = get_config($db, 'default_template');
        } catch (exception $e) {
            $template = NULL;
        }
    }

    return $template;
}

function get_buttons(DatabaseConnection $db)
{
    $res_b = get_all_buttons($db);
    $searchbuttons = array();
    foreach ($res_b as $row) {
        $searchbuttons[$row['id']] = $row['name'];
    }

    return $searchbuttons;
}

function process_schedule(urdd_client $uc, $period, $time1, $time2, $command, $arg_unschedule, $arg_schedule)
{
    assert(is_numeric($period) && is_numeric($time1) && is_numeric($time2));
    global $periods;

    $period = $periods->get($period);
    $period_hours = $period->get_interval();
    $nicetime = $time1 . ':' . $time2;
    $next = $period->get_next() . ' ' . $nicetime;
    $uc->unschedule(get_command($command), $arg_unschedule);
    $resp = $uc->schedule(get_command($command), $arg_schedule, $next, $period_hours * 3600);

    return $resp;
}

function start_preview(DatabaseConnection $db, $pbin_id, $pgroup_id, $userid)
{
    assert(is_numeric($userid) && is_numeric($pgroup_id));
    global $LN;

    $rprefs = load_config($db);
    $sql = "SELECT \"subject\", \"bytes\" FROM binaries_$pgroup_id WHERE \"binaryID\" = '$pbin_id'";
    $res = $db->execute_query($sql);
    if (!isset($res[0])) {
        throw new exception($LN['error_binariesnotfound']);
    }
    $dlname = $res[0]['subject'];
    $size = $res[0]['bytes'];
    $max_preview_size = get_config($db, 'maxpreviewsize') * 1024; // setting is in kB
    if ($max_preview_size > 0 && $size > $max_preview_size) {
        throw new exception($LN['error_preview_size_exceeded']);
    }

    $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);

    if (!$uc->is_connected()) {
        throw new exception($LN['preview_failed'] . ': ' .$LN['error_urddconnect']);
    }

    // Create download:
    $result = $uc->create_preview();
    if ($result === FALSE) {
        throw new exception($LN['error_createdlfailed']);
    }
    list($dlid, $dlthreads) = $uc->decode($result);

    $dlname = find_name($db, $dlname);
    $max_dl_name = get_config($db, 'max_dl_name');
    $dlname = substr($dlname, 0, $max_dl_name); // DL names are no longer than X characters when auto-generated

    set_download_name($db, $dlid, $dlname);
    set_download_size($db, $dlid, $size);
    $uc->add_bin_data($dlid, $pgroup_id, $pbin_id, TRUE);
    add_stat_data($db, stat_actions::PREVIEW, $size, $userid);

    foreach ($dlthreads as $id) {
        $uc->unpause($id);
    }

    return $dlid;
}

function add_set_data(DatabaseConnection $db, urdd_client $uc, $userid, $dlid, $nzb_or_dl)
{
    assert(is_numeric($userid) && is_numeric($dlid));
    global $LN;
    // Keep track of total downloadsize:
    foreach ($_SESSION['setdata'] as $set) {
        $setid = $set['setid'];
        $type = $set['type'];
        if ($type == 'group') {
            $type_val = USERSETTYPE_GROUP;
            $uc->add_set_data($dlid, $setid);
        } elseif ($type == 'rss') {
            $type_val = USERSETTYPE_RSS;
            $sql = "nzb_link FROM rss_sets WHERE \"setid\" = '$setid'";
            $res = $db->select_query($sql, 1);
            if ($res !== FALSE) {
                $url = $res[0]['nzb_link'];
                $uc->parse_nzb($url, $dlid);
            } else {
                throw new exception($LN['error_feednotfound'], ERR_RSS_FEED_FAILED);
            }
        } elseif ($type == 'spot') {
            $type_val = USERSETTYPE_SPOT;
            $uc->add_spot_data($dlid, $setid);
        } else {
            throw new exception_internal ($LN['error_unknowntype'] . ': ' . $type);
        }
        // mark user info
        $column = ($nzb_or_dl == 'download' ? 'statusread' : 'statusnzb');
        $res = $db->select_query(" * FROM usersetinfo WHERE \"userID\" = '$userid' AND \"setID\" = '$setid' AND \"type\"='" . $type_val . "'", 1);
        if ($res === FALSE) {
            $qry = "INSERT INTO usersetinfo (\"setID\", \"userID\", \"$column\", \"type\") VALUES ('$setid', '$userid', '" . sets_marking::MARKING_ON . "', '" . $type_val . "')";
        } else {
            $qry = "UPDATE usersetinfo SET \"$column\" = '" . sets_marking::MARKING_ON . "' WHERE \"setID\" = '$setid' AND \"userID\" = '$userid' AND \"type\"='" . $type_val . "'";
        }
        $db->execute_query($qry);
    }
}

function get_timestamp()
{
    global $LN;
    $timestamp = get_request('timestamp', '');
    if ($timestamp != '') {
        $time_int = strtotime($timestamp);
        if ($time_int === FALSE) {
            $timestamp = NULL;
            $time_int = time();
        } elseif ($time_int < time() && $time_int > 0) { // the time is before now, so probably means tomorrow
            $time_int += 24 * 60 * 60; // next day
            $timestamp .= ' +1 day';
        }
    } else {
        $timestamp = NULL;
        $time_int = time();
    }

    return array($timestamp, $time_int);
}

function get_setsize(DatabaseConnection $db, $setid, $type)
{
    global $LN;
    if ($type == 'group') {
        $sql = "\"size\" FROM setdata WHERE \"ID\" = '$setid'";
    } elseif ($type == 'rss') {
        $sql = "\"size\" FROM rss_sets WHERE \"setid\" = '$setid'";
    } elseif ($type == 'spot') {
        $sql = "\"size\" AS \"size\" FROM spots WHERE \"spotid\" = '$setid'";
    } else {
        throw new exception ($LN['error_invalidsetid']);
    }
    $res = $db->select_query($sql, 1);
    if ($res === FALSE) {
        throw new exception($LN['error_invalidsetid']);
    }

    return $res[0]['size'];
}

function get_basket_size(DatabaseConnection $db)
{
    $total_size = 0;
    foreach ($_SESSION['setdata'] as $set) {
        $setID = $set['setid'];
        $type = $set['type'];
        $size = get_setsize($db, $setID, $type);
        $total_size += $size;
    }

    return $total_size;
}

function get_free_diskspace(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    global $LN;
    $rprefs = load_config($db);
    $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);

    if (!$uc->is_connected()) {
        throw new exception($LN['error_urddconnect']);
    }

    $disk_space = $uc->diskfree('b');

    return $disk_space;
}

function create_new_download(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    global $LN;
    $rprefs = load_config($db);
    $dl_dir = trim(get_post('dl_dir', ''));
    $add_setname = get_post('add_setname', '');
    $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);

    if (!$uc->is_connected()) {
        throw new exception($LN['error_urddconnect']);
    }

    $total_size = get_basket_size($db);

    // Create download:
    $result = $uc->create_download();
    if ($result === FALSE) {
        throw new exception($LN['error_createdlfailed']);
    }

    list($dlid, $dlthreads) = $uc->decode($result);
    // Download ID:
    $dlname = get_dlname_from_session($db);
    // Store size:
    set_download_size($db, $dlid, $total_size);

    $max_dl_name = get_config($db, 'max_dl_name');
    $dlname = substr($dlname, 0, $max_dl_name); // DL names are no longer than X characters when auto-generated
    set_download_name($db, $dlid, $dlname);
    set_dl_dir($db, $dlid, $dl_dir, $add_setname);
    add_set_data($db, $uc, $userid, $dlid, 'download');

    list($timestamp, $time_int) = get_timestamp();

    $stat_id = add_stat_data($db, stat_actions::DOWNLOAD, 0, $userid); // fix stats
    set_stat_id($db, $dlid, $stat_id);
    foreach ($dlthreads as $id) {
        set_start_time($db, $dlid, $time_int);
        if ($timestamp === NULL) { // if no timestamp is given, just start it
            usleep(500000);
            $uc->unpause($id);
        } else { // otherwise schedule a continue command @ a given time
            $uc->schedule(get_command(urdd_protocol::COMMAND_CONTINUE), '"' . get_command(urdd_protocol::COMMAND_DOWNLOAD) . ' ' . $dlid . '"', $timestamp);
        }
    }

    $_SESSION['setdata'] = array();
    unset($_SESSION['dlsetname']);

    return $dlname;
}

function get_dlname_from_session(DatabaseConnection $db)
{
    global $LN;
    $firstSetID = $_SESSION['setdata'][0]['setid'];
    $first_type = $_SESSION['setdata'][0]['type'];
    $dlname = '';

    $db->escape($firstSetID);
    if ($first_type == 'group') {
        $sql = 'SELECT setdata."subject", extsetdata."value" FROM ' 	.
            "(setdata LEFT JOIN extsetdata ON setdata.\"ID\" = extsetdata.\"setID\" AND extsetdata.\"name\" = 'name') " .
            "WHERE \"ID\" = '$firstSetID' ";
    } elseif ($first_type == 'rss') {
        $sql = 'SELECT rss_sets."setname" AS "subject", extsetdata."value" FROM ' 	.
            "(rss_sets LEFT JOIN extsetdata ON rss_sets.\"setid\" = extsetdata.\"setID\" AND extsetdata.\"name\" = 'name') " .
            "WHERE rss_sets.\"setid\" = '$firstSetID' ";
    } elseif ($first_type == 'spot') {
        $sql = 'SELECT spots."title" AS "subject", extsetdata."value" FROM ' 	.
            "(spots LEFT JOIN extsetdata ON spots.\"spotid\" = extsetdata.\"setID\" AND extsetdata.\"name\" = 'name') " .
            "WHERE spots.\"spotid\" = '$firstSetID' ";
    } else {
        throw new exception ($LN['error_unknowntype'] . ': ' . $first_type);
    }
    $dlname = get_post('dlsetname', '');

    if ($dlname == '') {
        $res0 = $db->execute_query($sql);
        // Is there an extset setname? If so, use it, else use the autogenerated setname
        if ($res0[0]['value'] != '') {
            $dlname = create_extset_download_name($db, $firstSetID);
        } else {
            $dlname = $res0[0]['subject'];
            $dlname = find_name($db, $dlname);
        }
    }

    return $dlname;
}

function create_nzb(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    global $LN;
    $rprefs = load_config($db);
    $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);

    if (!$uc->is_connected()) {
        throw new exception($LN['error_urddconnect']);
    }

    $result = $uc->make_nzb();
    if ($result === FALSE) {
        throw new exception($LN['error_createdlfailed']);
    }

    list($dlid, $dlthreads) = $uc->decode($result);
    $dlname = get_dlname_from_session($db);

    $max_dl_name = get_from_array($rprefs, 'max_dl_name', MAX_DL_NAME);
    $dlname = substr($dlname, 0, $max_dl_name); // DL names are no longer than X characters when auto-generated
    set_download_name($db, $dlid, $dlname);
    add_set_data($db, $uc, $userid, $dlid, 'create_nzb');
    foreach ($dlthreads as $id) {
        usleep(500000);
        set_start_time($db, $dlid, time());
        $uc->unpause($id);
    }
    $_SESSION['setdata'] = array();
    unset($_SESSION['dlsetname']);

    return $dlname;
}

function merge_sets(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    global $LN;
    $first_set = NULL;
    $other_sets = array();
    $settype = '';
    foreach ($_SESSION['setdata'] as $set) {
        $setid = $set['setid'];
        $type = $set['type'];
        if ($type == 'group') {
            $settype = '';
            if ($first_set === NULL) {
                $first_set = $setid;
            } else {
                $other_sets[] = $setid;
            }
        } else {
            $settype = 'rss';
        }
    }
    if (count($other_sets) == 0) {
        if ($settype == 'rss') {
            throw new exception($LN['error_onlyforgrops']);
        } else {
            throw new exception($LN['error_onlyoneset']);
        }
    }
    $rprefs = load_config($db);
    $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);

    if (!$uc->is_connected()) {
        throw new exception($LN['error_urddconnect']);
    }

    $uc->merge_sets($first_set, $other_sets);
    $uc->disconnect();
}

function wipe_sets(DatabaseConnection $db, array $setids, $type, $userid)
{
    global $LN;
    assert(is_numeric($userid));
    $skip_int = (bool) get_pref($db, 'skip_int', $userid, 0);
    if ($skip_int) {
        $sets_str = '';
        foreach ($setids as $set) {
            $db->escape($set, TRUE);
            $sets_str .= "$set, ";
        }
        $sets_str = rtrim($sets_str, ', ');
    }

    $prefs = load_config($db);
    if ($type == USERSETTYPE_GROUP) {
        $uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);
        if ($uc->is_connected() === FALSE) {
            throw new exception($LN['error_urddconnect']);
        }
        if ($skip_int) {
            $qry = "SELECT DISTINCT setdata.\"ID\" FROM setdata LEFT JOIN usersetinfo AS usi ON usi.\"setID\" = setdata.\"ID\" AND "
                . "usi.\"type\"='$type' WHERE setdata.\"ID\" IN ($sets_str) AND (usi.\"statusint\" <> " . sets_marking::MARKING_ON . " OR usi.\"statusint\" IS NULL)";
            $res = $db->execute_query($qry);
            if ($res === FALSE) {
                return;
            }
            $setids = array();
            foreach ($res as $r) {
                $setids[] = $r['ID'];
            }
        }

        $uc->delete_set($setids, USERSETTYPE_GROUP);
        $uc->disconnect();
    } elseif ($type == USERSETTYPE_RSS) {
        $uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);
        if ($uc->is_connected() === FALSE) {
            throw new exception($LN['error_urddconnect']);
        }
        if ($skip_int) {
            $qry = "SELECT DISTINCT rss_sets.\"setid\" FROM rss_sets LEFT JOIN usersetinfo AS usi ON usi.\"setID\" = rss_sets.\"ID\" "
                . "AND \"type\"='$type' WHERE rss_sets.\"setID\" IN ($sets_str) AND usi.\"statusint\" <> " . sets_marking::MARKING_ON . " OR usi.\"statusint\" IS NULL)";
            $res = $db->execute_query($qry);
            if ($res === FALSE) {
                return;
            }

            $setids = array();
            foreach ($res as $r) {
                $setids[] = $r['ID'];
            }
        }

        $uc->delete_set($setids, USERSETTYPE_RSS);
        $uc->disconnect();
    } elseif ($type == USERSETTYPE_SPOT) {
        $uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);
        if ($uc->is_connected() === FALSE) {
            throw new exception($LN['error_urddconnect']);
        }
        if ($skip_int) {
            $qry = "SELECT DISTINCT spots.\"spotid\" FROM spots LEFT JOIN usersetinfo AS usi ON usi.\"setID\" = spots.\"spotid\" "
                . "AND \"type\"='$type' WHERE rss_sets.\"setID\" IN ($sets_str) AND usi.\"statusint\" <> " . sets_marking::MARKING_ON . " OR usi.\"statusint\" IS NULL)";
            $res = $db->execute_query($qry);
            if ($res === FALSE) {
                return;
            }

            $setids = array();
            foreach ($res as $r) {
                $setids[] = $r['ID'];
            }
        }
        $uc->delete_set($setids, USERSETTYPE_SPOT);
        $uc->disconnect();
    } else {
        throw new exception ($LN['error_unknowntype']);
    }
}

function get_total_rss_sets(DatabaseConnection $db)
{
    $res = $db->select_query('COUNT("id") AS cnt FROM rss_sets');

    return $res[0]['cnt'];
}

function get_total_ng_sets(DatabaseConnection $db)
{
    $res = $db->select_query('COUNT("ID") AS cnt FROM setdata');

    return $res[0]['cnt'];
}

function get_total_spots(DatabaseConnection $db)
{
    $res = $db->select_query('COUNT("id") AS cnt FROM spots');

    return $res[0]['cnt'];
}

function get_minsetsize_feed(DatabaseConnection $db, $feed_id, $userid, $default=0)
{
    assert(is_numeric($default));
    assert(is_numeric($userid));
    assert(is_numeric($feed_id));
    $db->escape($feed_id, TRUE);
    $db->escape($userid, TRUE);
    $qry = "\"minsetsize\" FROM userfeedinfo WHERE \"feedid\"=$feed_id AND \"userid\"=$userid UNION SELECT $default";
    $res = $db->select_query($qry, 1);

    return (!isset($res[0]['minsetsize'])) ? $default : $res[0]['minsetsize'];
}

function get_maxsetsize_feed(DatabaseConnection $db, $feed_id, $userid, $default=0)
{
    assert(is_numeric($default));
    assert(is_numeric($userid));
    assert(is_numeric($feed_id));
    $db->escape($feed_id, TRUE);
    $db->escape($userid, TRUE);
    $qry = "\"maxsetsize\" FROM userfeedinfo WHERE \"feedid\"=$feed_id AND \"userid\"=$userid UNION SELECT $default";
    $res = $db->select_query($qry, 1);

    return (!isset($res[0]['maxsetsize'])) ? $default : $res[0]['maxsetsize'];
}

function get_minsetsize_group(DatabaseConnection $db, $group_id, $userid, $default=0)
{
    assert(is_numeric($default));
    assert(is_numeric($userid));
    assert(is_numeric($group_id));
    $db->escape($group_id, TRUE);
    $db->escape($userid, TRUE);
    $qry = "\"minsetsize\" FROM usergroupinfo WHERE \"groupid\"=$group_id AND \"userid\"=$userid UNION SELECT $default";
    $res = $db->select_query($qry, 1);

    return (!isset($res[0]['minsetsize'])) ? $default : $res[0]['minsetsize'];
}

function get_maxsetsize_group(DatabaseConnection $db, $group_id, $userid, $default=0)
{
    assert(is_numeric($default));
    assert(is_numeric($userid));
    assert(is_numeric($group_id));
    $db->escape($group_id, TRUE);
    $db->escape($userid, TRUE);
    $qry = "\"maxsetsize\" FROM usergroupinfo WHERE \"groupid\"=$group_id AND \"userid\"=$userid UNION SELECT $default";
    $res = $db->select_query($qry, 1);

    return (!isset($res[0]['maxsetsize'])) ? $default : $res[0]['maxsetsize'];
}

function die_html($msg)
{
    echo trim(strip_tags($msg));
    exit();
}

function load_language($lang)
{
    global $smarty, $LN;

    if (isset($LN)) {
        unset($LN);
    }

    $pathsl = realpath(dirname(__FILE__));

    if ($lang === NULL || !is_file("$pathsl/lang/$lang")) {
        require "$pathsl/lang/" . DEFAULT_LANGUAGE;
    } else {
        require "$pathsl/lang/$lang";
    }
}

function set_post_status()
{
    $_SESSION['post_hide_status'] = array(
        'global' => 0,
        'ready' => 0,
        'finished' => 0,
        'active' => 0,
        'paused' => 0,
        'stopped' => 0,
        'shutdown' => 0,
        'error' => 0,
        'complete' => 0,
        'rarfailed' => 0,
        'par2failed' => 0,
        'yyencodefailed' => 0,
        'queued' => 0,
        'rarred' => 0,
        'par2ed' => 0,
        'yyencoded' => 0,
        'cancelled' => 0
    );
}

function set_down_status()
{
    $_SESSION['transfer_hide_status'] = array(
        'global' => 0,
        'ready' => 0,
        'finished' => 0,
        'active' => 0,
        'paused' => 0,
        'stopped' => 0,
        'shutdown' => 0,
        'error' => 0,
        'complete' => 0,
        'rarfailed' => 0,
        'par2failed' => 0,
        'cksfvfailed' => 0,
        'queued' => 0,
        'dlfailed' => 0,
        'cancelled' => 0
    );
}

function get_categories(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $db->escape($userid);
    $sql = "* FROM categories WHERE \"userid\" = '$userid' ORDER BY \"name\"";
    $res = $db->select_query($sql);
    if (!is_array($res)) {
        return array();
    }
    $categories = array();
    foreach ($res as $row) {
        $categories["{$row['id']}"] = array('id'=> $row['id'], 'name'=>$row['name']);
    }

    return $categories;
}

function subscribed_groups_select(DatabaseConnection $db, $groupID, $categoryID, array $categories, $userid)
{
    assert(is_numeric($userid));
    $Qgroups = '';
    $adult = urd_user_rights::is_adult($db, $userid);
    $Qadult = '';
    if (!$adult) {
        $Qadult = " AND groups.adult != " . ADULT_ON . ' ';
    }
    if (is_numeric($groupID) && $groupID != 0 && $groupID != '') {
        $Qgroups .= " OR \"groupid\" = '$groupID'";
    } elseif (is_numeric($categoryID) && $categoryID != 0 && $categoryID != '') {
        $Qgroups .= " OR \"groupid\" IN (";
        $groups = get_groups_by_category($db, $userid, $categoryID);
        $count = 0;
        foreach ($groups as $gr) {
            $Qgroups .= " $gr,";
            $count++;
        }
        $Qgroups = rtrim($Qgroups, ',');
        $Qgroups .= ')';
        if ($count == 0) {
            $Qgroups = '';
        }
    }
    $db->escape($userid, TRUE);
    $sql = "groups.\"ID\", groups.\"name\", groups.\"setcount\" FROM groups LEFT JOIN usergroupinfo ON groups.\"ID\" = \"groupid\" AND \"userid\" = $userid " .
        " WHERE \"active\" = '" . NG_SUBSCRIBED . "' AND (\"visible\" > 0 OR \"visible\" IS NULL $Qgroups) $Qadult ORDER BY \"name\"";
    $res = $db->select_query($sql);
    if (!is_array($res)) {
        $res = array();
    }

    $subscribedgroups = array();
    $c = 0;
    foreach ($res as $arr) {
        list($size, $suffix) = format_size($arr['setcount'], 'h', '', 1000);
        if ($size != 0 || ($arr['ID'] == $groupID)) { // don't show empty groups anyway
            $subscribedgroups[$c] = array(
                'id'=>$arr['ID'],
                'name'=>$arr['name'],
                'shortname'=>shorten_newsgroup_name($arr['name']),
                'article_count'=>$size . $suffix,
                'type' =>'group'
            );
            $c++;
        }
    }
    foreach ($categories as $arr) {
        list($size, $suffix) = format_size($arr['setcount'], 'h', '', 1000);
        if ($size != 0 || $arr['id'] == $categoryID) { // don't show empty categories either
            $subscribedgroups[$c] = array(
                'id'=> $arr['id'],
                'name'=>$arr['name'],
                'shortname'=>$arr['name'],
                'article_count'=>$size . $suffix,
                'type'=>'category'
            );
            $c++;
        }
    }

    return $subscribedgroups;
}

function get_userfeed_settings(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $db->escape($userid, TRUE);
    $sql = "categories.\"name\" AS c_name, rss_urls.\"name\" AS f_name, userfeedinfo.\"minsetsize\", userfeedinfo.\"maxsetsize\", userfeedinfo.\"visible\" " .
        "FROM userfeedinfo LEFT JOIN categories ON userfeedinfo.\"category\" = categories.\"id\" LEFT JOIN rss_urls ON userfeedinfo.feedid = rss_urls.\"id\" " .
        "WHERE userfeedinfo.\"userid\" = $userid";
    $res = $db->select_query($sql);
    if (!is_array($res)) {
        return array();
    }

    return $res;
}

function get_usergroup_settings(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $db->escape($userid, TRUE);
    $sql = "categories.\"name\" AS c_name, groups.\"name\" AS g_name, usergroupinfo.\"minsetsize\", usergroupinfo.\"maxsetsize\", usergroupinfo.\"visible\" ".
        "FROM usergroupinfo LEFT JOIN categories ON usergroupinfo.\"category\" = categories.\"id\" LEFT JOIN groups ON usergroupinfo.\"groupid\" = groups.\"ID\" " .
        "WHERE usergroupinfo.\"userid\" = $userid";
    $res = $db->select_query($sql);
    if (!is_array($res)) {
        return array();
    }

    return $res;
}

function get_used_categories_group(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $db->escape($userid);
    $sql = "SUM(\"setcount\") AS cnt, usergroupinfo.\"category\", MAX(categories.\"name\") AS \"name\" FROM usergroupinfo "
        . "JOIN groups ON \"groupid\" = groups.\"ID\" "
        . "JOIN categories ON usergroupinfo.\"category\" = categories.\"id\" "
        . "WHERE categories.\"userid\"=$userid AND usergroupinfo.\"category\" > 0 GROUP BY usergroupinfo.\"category\"";
    $res = $db->select_query($sql);
    if (!is_array($res)) {
        return array();
    }
    $categories = array();
    foreach ($res as $row) {
        $categories["{$row['category']}"] = array('id'=> $row['category'], 'name'=>$row['name'], 'setcount'=>$row['cnt']);
    }

    return $categories;
}

function get_used_categories_spots(DatabaseConnection $db)
{
    $sql = "spots.\"category\", COUNT(\"id\") AS cnt FROM spots GROUP BY spots.\"category\"";
    $res = $db->select_query($sql);
    if (!is_array($res)) {
        return array();
    }
    $categories = array();
    foreach ($res as $row) {
        $categories[$row['category']] = array('id'=> $row['category'], 'setcount'=>$row['cnt'], 'name'=>SpotCategories::HeadCat2Desc($row['category']));
    }

    return $categories;
}

function subscribed_spots_select($categoryid, array $categories)
{
    $subscribedspots = array();
     foreach ($categories as $row) {
        list($size, $suffix) = format_size($row['setcount'], 'h', '', 1000);
        if ($size != 0 || ($row['id'] == $categoryid)) { // don't show empty groups anyway
            $subscribedspots[$row['id']] = array ('id'=> $row['id'], 'article_count'=> $size . $suffix, 'name'=>$row['name']);
        }
    }

    return $subscribedspots;
}


function get_used_categories_rss(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $db->escape($userid);
    $sql = "SUM(\"feedcount\") AS cnt, userfeedinfo.\"category\", MAX(categories.\"name\") AS \"name\" FROM userfeedinfo "
        . "JOIN rss_urls ON \"feedid\" = rss_urls.\"id\" "
        . "JOIN categories ON userfeedinfo.\"category\" = categories.\"id\" "
        . "WHERE categories.\"userid\"=$userid AND userfeedinfo.\"category\" > 0 GROUP BY userfeedinfo.\"category\"";
    $res = $db->select_query($sql);
    if (!is_array($res)) {
        return array();
    }
    $categories = array();
    foreach ($res as $row) {
        $categories["{$row['category']}"] = array('id'=> $row['category'], 'name'=>$row['name'], 'setcount'=>$row['cnt']);
    }

    return $categories;
}


function subscribed_feeds_select(DatabaseConnection $db, $feed_id, $categoryID, array $categories, $userid)
{
    assert(is_numeric($userid));
    $adult = urd_user_rights::is_adult($db, $userid);
    $Qadult = '';
    if (!$adult) {
        $Qadult = " AND rss_urls.adult != " . ADULT_ON . ' ';
    }
    $db->escape($userid, TRUE);
    $qfeed_id = '';
    if (is_numeric($feed_id)) {
        $qfeed_id = "OR \"feedid\" = '$feed_id'";
    }
    // Get the feeds:
    $sql = "rss_urls.\"id\", \"name\", \"feedcount\" FROM rss_urls LEFT JOIN userfeedinfo ON rss_urls.\"id\" = \"feedid\" AND \"userid\" = $userid " .
        " WHERE \"subscribed\" = '". RSS_SUBSCRIBED . "' AND (\"visible\" > 0 OR \"visible\" IS NULL $qfeed_id) $Qadult ORDER BY \"name\"";
    $res = $db->select_query($sql);

    if (!is_array($res)) {
        $res = array();
    }

    $c = 0;
    $subscribedfeeds = array();
    foreach ($res as $arr) {
        list($size, $suffix) = format_size($arr['feedcount'], 'h', '', 1000);
        if ($size != 0 || ($arr['id'] == $feed_id)) { // don't show empty groups anyway
            $subscribedfeeds[$c] = array(
                'id'=>$arr['id'],
                'name'=>($arr['name']),
                'type'=>'feed',
                'article_count'=>$size . $suffix,
            );
            $c++;
        }
    }

    foreach ($categories as $arr) {
        list($size, $suffix) = format_size($arr['setcount'], 'h', '', 1000);
        if ($size != 0 || ($arr['id'] == $categoryID)) { // don't show empty categories either
            $subscribedfeeds[$c] = array(
                'id'=> $arr['id'],
                'name'=>($arr['name']),
                'type'=>'category',
                'article_count'=>$size . $suffix,
            );
            $c++;
        }
    }

    return $subscribedfeeds;
}


function verify_access(DatabaseConnection $db, $module_bits, $needadmin, $rights, $userid)
{
    global $LN, $isadmin;
    assert(is_numeric($userid));
    if ($module_bits !== NULL && !urd_modules::check_module_enabled($db, $module_bits)) {
        throw new exception($LN['error_accessdenied']);
    }
    if ($needadmin && !$isadmin) {
        $perm = FALSE;
        if ($rights != '') {
            $perm = urd_user_rights::has_rights($db, $userid, $rights);
        }
        if (!$perm) {
            throw new exception($LN['error_noadmin']);
        }
    }
}


function get_feed_last_updated(DatabaseConnection $db, $feed_id, $userid)
{
    // get last update times for groups
    assert(is_numeric($userid));
    $sql = "SELECT \"feedid\", \"last_update_seen\" FROM userfeedinfo WHERE \"userid\" = '$userid'";

    if (is_numeric($feed_id) && $feed_id != 0) {
        $sql .= " AND \"feedid\" = '$feed_id'";
    }
    $res = $db->execute_query($sql);
    $feed_lastupdate = array();
    if ($res !== FALSE) {
        foreach ($res as $row) {
            $feed_lastupdate["{$row['feedid']}"] = $row['last_update_seen'];
        }
    }

    return $feed_lastupdate;
}


function get_group_last_updated(DatabaseConnection $db, $groupid, $userid)
{
    assert(is_numeric($userid));
    // get last update times for groups
    $sql = "\"groupid\", \"last_update_seen\" FROM usergroupinfo WHERE \"userid\" = '$userid'";
    if ($groupid != '') {
        if (!is_numeric($groupid)) {
            $groupid = group_by_name($db, $groupid);
        }
        if ($groupid != 0) {
            $sql .= " AND \"groupid\" = '$groupid'";
        }
    }

    $res = $db->select_query($sql);
    $group_lastupdate = array();
    if ($res !== FALSE) {
        foreach ($res as $row) {
            $group_lastupdate["{$row['groupid']}"] = $row['last_update_seen'];
        }
    }

    return $group_lastupdate;
}


function get_mail_templates()
{
    global $smarty, $pathwf;
    $template_dir = realpath($pathwf . '/../mail_templates');
    $templates = glob($template_dir . '/*.tpl');
    $mail_templates = array(''=>'');
    foreach ($templates as $template) {
        $name = basename($template);
        if (file_exists($template) && is_file($template) && $name[0] != '_') {
            $mail_templates[$name] = ucfirst(substr($name, 0, -4));
        }
    }

    return $mail_templates;
}


function get_stylesheets()
{
    global $smarty;
    $template_dir = $smarty->getTemplateDir();
    $sheets = glob($template_dir[0] . '/css/*');
    $stylesheets = array();
    foreach ($sheets as $sheet) {
        $name = basename($sheet);
        if (file_exists($sheet) && is_dir($sheet) && $name[0] != '_') {
            $stylesheets[$name] = ucfirst($name);
        }
    }

    return $stylesheets;
}


function get_active_stylesheet(DatabaseConnection $db, $userid)
{
    global $smarty;
    $stylesheet = ($userid > 0) ? get_pref($db, 'stylesheet', $userid, '') : '';
    $template_dir = $smarty->getTemplateDir();
    $template_dir = $template_dir[0] . '/css';

    $default_stylesheet = get_config($db, 'default_stylesheet', 'light.css');
    if ($stylesheet == '' || !file_exists($template_dir . '/' . $stylesheet . '/' .$stylesheet . '.css') || !is_file($template_dir . '/' . $stylesheet. '/'. $stylesheet . '.css')) {
        if (!file_exists($template_dir . '/' . $default_stylesheet. '/'. $default_stylesheet . '.css') || !is_file($template_dir . '/' . $default_stylesheet. '/' .$default_stylesheet . '.css')) {
            $stylesheet = '';
        } else {
            $stylesheet = $default_stylesheet;
        }
    }

    return $stylesheet;
}


function to_ln($cat)
{
    global $LN;

    return (isset($LN[$cat]) && $LN[$cat] != '') ? $LN[$cat] : '??' . $cat;
}


function get_subcats($hcat, $scat)
{
    global $LN;
    $_subcat = SpotCategories::Cat2ShortDescs($hcat, $scat);
    $subcat = array();
    foreach ($_subcat as $key => $cat) {
        $key = $LN[$key];
        foreach ($cat as $k=> $c) {
            $c[0] = to_ln($c[0]);
            $subcat[$key][$k] = $c;
        }
    }

    return $subcat;
}


function map_default_sort(array $prefs, array $mapping)
{
    list($def_sort, $def_sort_order) = explode (' ', strtolower((isset($prefs['defaultsort']) ? ($prefs['defaultsort'] . ' ') : 'date asc')), 2);
    if (isset ($mapping[$def_sort])) {
        $def_sort = $mapping[$def_sort];
    }

    return $def_sort . ' ' . $def_sort_order;
}


function export_settings(DatabaseConnection $db, $what, $filename, $userid=NULL)
{
    header('Content-Type: text/html/force-download');
    header('Content-Disposition: attachment; filename=' . $filename);
    $xml = new urd_xml_writer('php://output');
    $xml->write($db, $what, $userid);
    $xml->output_xml_data();
    die();
}


function get_subcats_requests()
{
    $subcats = $not_subcats = $off_subcats = array();
    foreach ($_REQUEST as $key=>$value) {
        if (strncmp($key, 'subcat_', 7) == 0) {
            $t = explode('_', $key);
            if (isset($t[3])) {
                if ($value == 1) {
                    $subcats[$key]= array(0 => $t[1], 1=> $t[2], 2 => $t[3], 'value' => 1);
                } elseif ($value == 2) {
                    $not_subcats[$key] = array(0 => $t[1], 1 => $t[2], 2 => $t[3], 'value' => 2);
                } else {
                    $off_subcats[$key] = array(0 => $t[1], 1 => $t[2], 2 => $t[3], 'value' => 0);
                }

            }
        }
    }

    return array($subcats, $not_subcats, $off_subcats);
}


function spot_name_cmp(array $a, array $b)
{
    return strcmp($a['name'], $b['name']);
}


function get_stats_years(DatabaseConnection $db, $userid, $isadmin)
{
    $quser = '';
    if (!$isadmin) {
        assert(is_numeric($userid));
        $quser = "AND \"userid\" = '$userid'";
    }

    $ystr = $db->get_extract('year', '"timestamp"');
    $qry = " $ystr AS \"year\" FROM stats WHERE 1=1 $quser GROUP BY $ystr ORDER BY \"year\" DESC";
    $res = $db->select_query($qry);
    $years = array();

    if (is_array($res)) {
        foreach ($res as $row) {
            $years[] = $row['year'];
        }
    }

    return $years;
}


function toggle_adult(DatabaseConnection $db, $type, $groupid, $value)
{
    assert(is_numeric($groupid));
    if (!in_array($value, array(ADULT_ON, ADULT_OFF,ADULT_DEFAULT))) {
        throw new exception($LN['error_invalidvalue']);
    }
    $db->escape($groupid, TRUE);
    $db->escape($value, TRUE);
    if ($type == 'group') {
        $sql = "UPDATE groups SET \"adult\" = $value WHERE \"ID\" = $groupid";
    } elseif ($type == 'rss') {
        $sql = "UPDATE rss_urls SET \"adult\" = $value WHERE \"ID\" = $groupid";
    } else {
        throw new exception($LN['error_unknowntype']);
    }
    $db->execute_query($sql);
}


function divide_sort($sort)
{
    $s = explode(' ', $sort, 2);
    if (!isset($s[0])) {
        return array();
    }
    $o = trim($s[0]);
    if (!isset($s[1])) {
        $d = 'asc';
    } else {
        $d = strtolower(trim($s[1]));
    }

    return array('order'=>$o, 'direction'=>$d);
}


function add_to_blacklist(DatabaseConnection $db, $spotterID)
{
    $db->escape($spotterID, FALSE);
    $sql = "SELECT count(*) AS cnt FROM spot_blacklist WHERE \"spotter_id\" = '$spotterID' AND \"source\" = " . blacklist::BLACKLIST_INTERNAL;
    $res = $db->execute_query($sql);
    if ($res[0]['cnt'] == 0) {
        $add_ids = array($spotterID, blacklist::BLACKLIST_INTERNAL);
        $cols = array('spotter_id', 'source');
        $db->insert_query('spot_blacklist', $cols, $add_ids);
    }
}


function get_spotterid_from_spot(DatabaseConnection $db, $spotid)
{
    $db->escape($spotid, TRUE);
    $sql = "\"spotter_id\" FROM spots WHERE \"spotid\" = $spotid";
    $res = $db->select_query($sql, 1);
    if (!isset($res[0]['spotter_id'])) {
        return FALSE;
    }

    return $res[0]['spotter_id'];
}


function get_pages ($totalsets, $perpage, $offset)
{
    assert (is_numeric($totalsets) && is_numeric($perpage) && is_numeric($offset));
    $size = SKIPPER_SIZE;
    $totalpages = max(1, ceil($totalsets / $perpage));      // Total number of pages.
    $activepage = ceil(($offset+1) / $perpage);     // This is the page we're on. (+1 because 0/100 = page 1)
    $start = max($activepage - floor($size/2), 1);  // We start at 1 unless we're now on page 12, then we show page 2.
    $end = min($start + $size, $totalpages);        // We don't go beyond 'totalpages' ofcourse.
    $start = max($end - $size, 1);                  // Re-check $start, in case the pagenumber is near the end

    $pages = array();
    foreach (range(1, $totalpages) as $i) {
        $thispage = array();
        $thispage['number'] = $i;
        $pageoffset = ($i - 1) * $perpage;          // For page 1, offset = 0.
        $thispage['offset'] = $pageoffset;
        // distance is the distance from the current page, maximum of 5. Used to colour close pagenumbers:
        $thispage['distance'] = min(abs($activepage - $i),5);
        $pages[] = $thispage;
    }

    return array($pages, $activepage, $totalpages, $offset);
}

function get_directories(DatabaseConnection $db, $username)
{
    $dlpath = get_dlpath($db);
    if (!is_numeric($username)) {
        $userid = get_userid($db, $username);
    } else {
        $userid = $username;
        $username = get_username($db, $userid);
    }

    $user_dlpath = $dlpath . DONE_PATH . $username . DIRECTORY_SEPARATOR;
    $dir = dir($user_dlpath);
    $directories = array();
    while ($file = $dir->read()) {
        if (is_dir($user_dlpath . $file) && $file != '.' && $file != '..') {
            $directories[] = $file;
        }
    }
    natcasesort($directories);

    return $directories;
}

function get_spots_stats_by_dow(DatabaseConnection $db)
{
    global $LN;
    $time_stamp = $db->get_dow_timestamp('"stamp"');

    $sql = "SELECT count(*) as cnt, $time_stamp AS dow, \"category\" FROM spots  GROUP BY $time_stamp, \"category\" ";
    $res = $db->execute_query($sql);
    if ($res === FALSE) {
        $res = array();
    }
    $stats = array();
    foreach (range(1, 7) as $i) {
            $stats[ ($i - 1) ] = array( html_entity_decode($LN['short_day_names'][$i]), 0, 0, 0, 0);
    }

    foreach ($res as $row) {
        $m = $row['dow'];
        $c = $row['category'] + 1;

        $stats[ $m] [$c] = $row['cnt'];
    }

    return array_values($stats);
}

function get_spots_stats_by_period(DatabaseConnection $db, $period)
{
    global $LN;
    if ($period == 'dow') {
        return get_spots_stats_by_dow($db);
    }
    $time_stamp = $db->get_timestamp('"stamp"');
    $time_extract = $db->get_extract($period, $time_stamp);

    $sql = "SELECT count(*) as cnt, $time_extract AS mnth, \"category\" FROM spots GROUP BY $time_extract, \"category\"";
    $res = $db->execute_query($sql);
    if ($res === FALSE) {
        $res = array();
    }
    $stats = array();
    if ($period == 'month') {
        foreach (range(1,12) as $i) {
            $stats[ $i ] = array( html_entity_decode($LN['short_month_names'][$i]), 0, 0, 0, 0);
        }
    } elseif ($period == 'week') {
        $max_week = 0;
        foreach (range(25, 31) as $r) {
            $max_week = max($max_week, (int) date('W', mktime(0, 0, 0, 12, $r)));
        }
        foreach (range(1,$max_week) as $i) {
            $stats[ $i ] = array($i, 0, 0, 0,0 );
        }
    } elseif ($period == 'hour') {
        foreach (range(0, 23) as $i) {
            $stats[ $i ] = array($i, 0, 0, 0,0 );
        }
    }
    foreach ($res as $row) {
        $m = $row['mnth'];
        $c = $row['category'] + 1;

        $stats[ $m] [$c] = $row['cnt'];
    }

    return array_values($stats);
}

function get_spots_stats(DatabaseConnection $db)
{
    $sql = "SELECT count(*) as cnt, category FROM spots GROUP BY category";
    $res = $db->execute_query($sql);
    if ($res === FALSE) {
        $res = array(0, 0, 0, 0);
    }
    $stats = array();
    foreach ($res as $row) {
        $stats[ $row['category'] ] = $row[ 'cnt'];
    }

    return $stats;
}

function check_tidy($template)
{
    global $smarty;
    $tidy = new tidy();
    $tidyconfig = array();
    $data = $smarty->fetch($template);
    $tidy->parseString($data, $tidyconfig, 'utf8');
    $tidy->diagnose();
    var_dump($tidy->errorBuffer, $data); die;
}

function is_image_file($ext)
{
    return in_array(strtolower($ext), array('jpeg', 'jpg', 'gif', 'png'));
}

function is_text_file($ext)
{
    return in_array(strtolower($ext), array('htm', 'html', 'nfo', 'log', 'txt', URDD_SCRIPT_EXT));
}

function insert_wbr($str, $size = 64)
{
    assert(is_numeric($size));
    $l = strlen($str);
    $str_new = '';
    $t = 0;
    $in_tag = 0;
    for ($i=0; $i< $l ; $i++) {
        if ($str[$i] == '<') {
            $t = 0;
            $in_tag++;
        } elseif ($str[$i] == '>') {
            $t = 0;
            $in_tag--;
        }
        $str_new .= $str[$i];
        if ($in_tag > 0) {
            continue;
        }
        if (ctype_space($str[$i]) ) {
            $t = 0;
        } else {
            $t++;
            if ($t >= $size) {
                $str_new .= '<wbr>';
                $t = 0;
            }
        }
    }

    return $str_new;
}

function parse_search_string($search, $column1, $column2, $column3, $search_type)
{
    $Qsearch = '';
    // Search google style:
    if ($search != '') {
        $search = trim(str_replace('*',' ',$search));
        $search = strtolower($search);
        $keywords = explode(' ', $search);
        $Qsearch1 = $Qsearch2 = $Qsearch3 = '';
        $next = $not = '';
        foreach ($keywords as $keyword) {
            $keyword = trim($keyword);
            if ($keyword == '') { continue;}
            if ($next == '') {
                $qand = 'AND';
            } elseif ($next == 'NOT') {
                $not = 'NOT';
            } else {
                $qand = $next;
                $not = '';
                $next = '';
            }
            if ($keyword == 'or') {
                $next = 'OR';
                continue;
            } elseif ($keyword == 'not') {
                $next = 'NOT';
                continue;
            }

            if ($keyword != '') {
                if ($column1 != '') {
                    $Qsearch1 .= " $qand $not $column1 $search_type '%{$keyword}%' "; // nasty: like is case sensitive in psql, insensitive in mysql
                }
                if ($column2 != '') {
                    $Qsearch2 .= " $qand $not $column2 $search_type '%{$keyword}%' ";
                }
                if ($column3 != '') {
                    $Qsearch3 .= " $qand $not $column3 $search_type '%{$keyword}%' ";
                }
            }
        }
        $Qsearch .= 'AND ( 1=0 ';
        if ($Qsearch1 != '') {
            $Qsearch .= " OR ( 1=1 $Qsearch1 ) ";
        }
        if ($Qsearch2 != '') {
            $Qsearch .= " OR (1=1 $Qsearch2 ) ";
        }
        if ($Qsearch3 != '') {
            $Qsearch .= " OR (1=1 $Qsearch3 )";
        }
        $Qsearch .= ')';
    }

    return $Qsearch;
}

function get_size_limits_spots(DatabaseConnection $db)
{
    $sql = "min(size) AS minsetsize, max(size) AS maxsetsize FROM spots";
    $res = $db->select_query($sql, 1);

    return array($res[0]['minsetsize'], $res[0]['maxsetsize']);
}

function get_age_limits_spots(DatabaseConnection $db)
{
    $now =  time();
    $sql = "min({$now} - \"stamp\") AS \"minage\", max({$now} - \"stamp\") AS \"maxage\" FROM spots";
    $res = $db->select_query($sql, 1);

    return array($res[0]['minage'], $res[0]['maxage']);
}

function get_size_limits_groups(DatabaseConnection $db, $groupID=NULL)
{
    $sql = "min(size) AS minsetsize, max(size) AS maxsetsize FROM setdata";
    if (is_numeric($groupID) && $groupID > 0) {
        $db->escape($groupID, TRUE) ;
        $sql .= " WHERE \"groupID\" = $groupID";
    }
    $res = $db->select_query($sql, 1);

    return array($res[0]['minsetsize'], $res[0]['maxsetsize']);
}

function get_age_limits_groups(DatabaseConnection $db, $groupID=NULL)
{
    $now = time();
    $sql = "min({$now} - \"date\") AS \"minage\", max({$now} - \"date\") AS \"maxage\" FROM setdata";
    if (is_numeric($groupID) && $groupID > 0) {
        $db->escape($groupID, TRUE) ;
        $sql .= " WHERE \"groupID\" = $groupID";
    }
    $res = $db->select_query($sql, 1);

    return array($res[0]['minage'], $res[0]['maxage']);
}

function get_size_limits_rsssets(DatabaseConnection $db, $rss_id=NULL)
{
    $sql = "min(size) AS minsetsize, max(size) AS maxsetsize FROM rss_sets";
    if (is_numeric($rss_id) && $rss_id > 0) {
        $db->escape($rss_id, TRUE) ;
        $sql .= " WHERE \"rss_id\" = $rss_id";
    }
    $res = $db->select_query($sql, 1);

    return array($res[0]['minsetsize'], $res[0]['maxsetsize']);
}

function get_age_limits_rsssets(DatabaseConnection $db, $rss_id=NULL)
{
    $now = time();
    $sql = "min({$now} - \"timestamp\") AS \"minage\", max({$now} - \"timestamp\") AS \"maxage\" FROM rss_sets";
    if (is_numeric($rss_id) && $rss_id > 0) {
        $db->escape($rss_id, TRUE) ;
        $sql .= " WHERE \"rss_id\" = $rss_id";
    }
    $res = $db->select_query($sql, 1);

    return array($res[0]['minage'], $res[0]['maxage']);
}

function nearest($val, $up)
{
    assert(is_numeric($val));
    $l = max(0, floor(log10($val)) - 1);
    if ($up) {
        $v = ceil($val / (pow(10, $l)));
    } else {
        $v = floor($val / (pow(10, $l)));
    }

    $v = max(0, $v * pow(10, $l));

    return $v;
}
