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
 * $LastChangedDate: 2014-06-22 00:25:41 +0200 (zo, 22 jun 2014) $
 * $Rev: 3106 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: web_functions.php 3106 2014-06-21 22:25:41Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathwf = realpath(dirname(__FILE__));

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

function get_cookie($var, $default='', $verify_fn=NULL)
{
    assert($var !== NULL);
    if (!isset($_COOKIE[$var])) {
        return $default;
    }
    if (function_exists($verify_fn)) {
        if (!$verify_fn($_COOKIE[$var])) {
            return $default;
        }
    }

    return $_COOKIE[$var];
}

function get_request($var, $default='', $verify_fn=NULL)
{
// can't use $_REQUEST as it also includes $_COOKIE
    assert($var !== NULL);
    if (!isset($_POST[$var]) && !isset($_GET[$var])) {
        return $default;
    }

    if (function_exists($verify_fn)) {
        if (!$verify_fn($_POST[$var]) && !$verify_fn($_GET[$var])) {
            return $default;
        }
    }
    if (isset($_POST[$var])) { 
        return $_POST[$var];
    } else {
        return $_GET[$var];
    }
}

function get_session($var, $default='', $verify_fn=NULL)
{
    assert($var !== NULL);
    if (!isset($_SESSION[$var])) {
        return $default;
    }
    if (function_exists($verify_fn)) {
        if (!$verify_fn($_SESSION[$var])) {
            return $default;
        }
    }

    return $_SESSION[$var];
}

function get_post($var, $default='', $verify_fn=NULL)
{
    assert($var !== NULL);
    if (!isset($_POST[$var])) {
        return $default;
    }
    if (function_exists($verify_fn)) {
        if (!$verify_fn($_POST[$var])) {
            return $default;
        }
    }

    return $_POST[$var];
}

function count_active_ng(DatabaseConnection $db)
{
    $hidden_groups = SPOTS_GROUPS::get_hidden_groups();
    $q_hidden = '';
    $input_arr = array(newsgroup_status::NG_SUBSCRIBED);
    if (count($hidden_groups) > 0) {
        $q_hidden = ' AND "name" NOT IN (' . str_repeat('?,', count($hidden_groups)-1) . '?)';
        $input_arr = array_merge($input_arr, $hidden_groups);
    }
    $sql = 'count(*) AS cnt FROM groups WHERE "active"=? ' . $q_hidden;
    $res = $db->select_query($sql, $input_arr);
    return $res[0]['cnt'];
}

function count_active_rss(DatabaseConnection $db)
{
    $sql = 'count(*) AS cnt FROM rss_urls WHERE "subscribed"=?';
    $res = $db->select_query($sql, array(newsgroup_status::NG_SUBSCRIBED));

    return $res[0]['cnt'];
}

function remove_rss_schedule(DatabaseConnection $db, urdd_client $uc, $id, $cmd)
{
    assert(is_numeric($id));
    $db->update_query_2('rss_urls', array('refresh_time'=>0, 'refresh_period'=>0), '"id"=?', array($id));
    $uc->unschedule(get_command($cmd), $id);
}

function remove_schedule(DatabaseConnection $db, urdd_client $uc, $id, $cmd)
{
    assert(is_numeric($id));
    $db->update_query_2('groups', array('refresh_time'=>0, 'refresh_period'=>0), '"ID"=?', array($id));
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

function command_description(DatabaseConnection $db, $task_)
{
    global $LN;
    $task_parts = explode (' ', $task_);
    $cmd_code = get_command_code($task_parts[0]);
    $task = array('', '', 0, '');
    try {
        $name = '';
        switch ($cmd_code) {
        case urdd_protocol::COMMAND_CONTINUE: 	 //continue first cause it will hit on other tasks as well
            array_shift($task_parts);
            $task[0] = $LN['taskcontinue'];
            if (is_numeric($task_parts[0])) {
                $task[1] = $LN['taskunknown'];
            } else {
                $cmd = implode(' ', $task_parts);
                list ($t, $a) = command_description($db, $cmd);
                $task[1] = "$t $a";
                $task[3] = '';
            }
            break;
        case urdd_protocol::COMMAND_PAUSE:	 //continue first cause it will hit on other tasks as well
            array_shift($task_parts);
            $cmd = implode(' ', $task_parts);
            $task[0] = $LN['taskpause'];
            if (is_numeric($task_parts[0])) {
                $task[1] = $LN['taskunknown'];
            } else {
                list ($t, $a) = command_description($db, $cmd);
                $task[1] = "$t $a";
            }
            $task[3] = '';
            break;
        case urdd_protocol::COMMAND_UPDATE:
            $groupID = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskupdate'];
            if (is_numeric($groupID)) {
                $group_name = shorten_newsgroup_name(group_name($db, $groupID), 0);
                $task[1] = htmlentities($group_name);
            }
            $task[3] = 'update';
            break;
        case urdd_protocol::COMMAND_PURGE:
            $groupID = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskpurge'];
            if (is_numeric($groupID)) {
                $group_name = shorten_newsgroup_name(group_name($db, $groupID), 0);
                $task[1]= htmlentities($group_name);
            }
            $task[3] = 'purge';
            break;
        case urdd_protocol::COMMAND_EXPIRE:
            $groupID = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskexpire'];
            if (is_numeric($groupID)) {
                $group_name = shorten_newsgroup_name(group_name($db, $groupID), 0);
                $task[1] = htmlentities($group_name);
            }
            $task[3] = 'expire';
            break;
        case urdd_protocol::COMMAND_GENSETS:
            $groupID = $task_parts[count($task_parts)-1];
            $task[0] = $LN['taskgensets'];
            if (is_numeric($groupID)) {
                $group_name = shorten_newsgroup_name(group_name($db, $groupID), 0);
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
            $task[0] = $LN['taskdownload'] . ' ';
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
            $id = isset($task_parts[1]) ? $task_parts[1] : 0;
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
        case urdd_protocol::COMMAND_POST_SPOT:
            $task[0] = $LN['taskpostspot'];
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
        case urdd_protocol::COMMAND_SET:
            $task[0] = $LN['taskset'];
            $task[3] = 'set';
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
    $langdir = realpath(dirname(__FILE__)) . '/lang/';
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

function get_search_options(DatabaseConnection $db)
{
    $res_b = get_all_search_options($db);
    $searchoptions = array();
    foreach ($res_b as $row) {
        $searchoptions[$row['id']] = $row['name'];
    }

    return $searchoptions;
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
    $sql = "\"subject\", \"bytes\" FROM binaries_$pgroup_id WHERE \"binaryID\"=?";
    $res = $db->select_query($sql, array($pbin_id));
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
    set_start_time($db, $dlid, time());
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
            $sql = '"nzb_link" FROM rss_sets WHERE "setid"=?';
            $res = $db->select_query($sql, 1, array($setid));
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
            throw new exception_internal($LN['error_unknowntype'] . ': ' . $type);
        }
        // mark user info
        $column = ($nzb_or_dl == 'download' ? 'statusread' : 'statusnzb');
        $res = $db->select_query('* FROM usersetinfo WHERE "userID"=? AND "setID"=? AND "type"=?', 1, array($userid, $setid, $type_val));
        if ($res === FALSE) {
            $db->insert_query('usersetinfo', array('setID', 'userID', $column, 'type'), array($setid, $userid, sets_marking::MARKING_ON, $type_val));
        } else {
            $db->update_query_2('usersetinfo', array($column=>sets_marking::MARKING_ON), '"userID"=? AND "setID"=? AND "type"=?', array($userid, $setid, $type_val));
        }
    }
}

function get_timestamp()
{
    global $LN;
    $timestamp = trim(get_request('timestamp', ''));
    if ($timestamp != '') {
        $time_int = strtotime($timestamp);
        if ($time_int === FALSE) {
            $timestamp = NULL;
            $time_int = time();
        } elseif ($time_int < time() && $time_int > 0) { // the time is before now, so probably means tomorrow
            $time_int += 24 * 3600; // next day
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
        $sql = '"size" FROM setdata WHERE "ID"=?';
    } elseif ($type == 'rss') {
        $sql = '"size" FROM rss_sets WHERE "setid"=?';
    } elseif ($type == 'spot') {
        $sql = '"size" AS "size" FROM spots WHERE "spotid"=?';
    } else {
        throw new exception ($LN['error_invalidsetid']);
    }
    $res = $db->select_query($sql, 1, array($setid));
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
    check_connected($uc);

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
    check_connected($uc);

    // Create download:
    $result = $uc->create_download();
    if ($result === FALSE) {
        throw new exception($LN['error_createdlfailed']);
    }

    list($dlid, $dlthreads) = $uc->decode($result);
    // Download ID:
    $dlname = get_dlname_from_session($db);
    // Store size:
    $total_size = get_basket_size($db);
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

    if ($first_type == 'group') {
        $sql = 'setdata."subject", extsetdata."value" FROM ' 	.
            '(setdata LEFT JOIN extsetdata ON setdata."ID" = extsetdata."setID" AND extsetdata."name" = \'name\') ' .
            'WHERE "ID"=? ';
    } elseif ($first_type == 'rss') {
        $sql = 'rss_sets."setname" AS "subject", extsetdata."value" FROM ' 	.
            '(rss_sets LEFT JOIN extsetdata ON rss_sets."setid" = extsetdata."setID" AND extsetdata."name" = \'name\') ' .
            'WHERE rss_sets."setid"=? ';
    } elseif ($first_type == 'spot') {
        $sql = 'spots."title" AS "subject", extsetdata."value" FROM ' 	.
            '(spots LEFT JOIN extsetdata ON spots."spotid" = extsetdata."setID" AND extsetdata."name" = \'name\') ' .
            'WHERE spots."spotid"=? ';
    } else {
        throw new exception ($LN['error_unknowntype'] . ': ' . $first_type);
    }
    $dlname = get_post('dlsetname', '');

    if ($dlname == '') {
        $res0 = $db->select_query($sql, array($firstSetID));
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
    check_connected($uc);

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
    check_connected($uc);
    $uc->merge_sets($first_set, $other_sets);
    $uc->disconnect();
}

function wipe_sets(DatabaseConnection $db, array $setids, $type, $userid)
{
    global $LN;
    assert(is_numeric($userid));
    
    if (!in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT))) { 
        throw new exception ($LN['error_unknowntype']);
    }
    $prefs = load_config($db);
    $uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);
    check_connected($uc);
    $skip_int = (bool) get_pref($db, 'skip_int', $userid, 0) && (count($setids) > 0);
    if ($skip_int) {
        $sets_str = str_repeat('?,', count($setids)-1) . '?';
        if ($type == USERSETTYPE_GROUP) {
            $qry = 'DISTINCT setdata."ID" AS "ID" FROM setdata LEFT JOIN usersetinfo AS usi ON usi."setID" = setdata."ID" AND '
                . "usi.\"type\"=? WHERE setdata.\"ID\" IN ($sets_str) AND (usi.\"statusint\" <> ? OR usi.\"statusint\" IS NULL)";
        } elseif ($type == USERSETTYPE_RSS) {
            $qry = 'DISTINCT rss_sets."setid" AS "ID" FROM rss_sets LEFT JOIN usersetinfo AS usi ON usi."setID" = rss_sets."ID" '
                . "AND \"type\"=? WHERE rss_sets.\"setID\" IN ($sets_str) AND (usi.\"statusint\" <> ? OR usi.\"statusint\" IS NULL)";
        } elseif ($type == USERSETTYPE_SPOT) {
            $qry = 'DISTINCT spots."spotid" AS "ID" FROM spots LEFT JOIN usersetinfo AS usi ON usi."setID" = spots."spotid" '
                . "AND \"type\"=? WHERE spots.\"spotid\" IN ($sets_str) AND (usi.\"statusint\" <> ? OR usi.\"statusint\" IS NULL)";
        }
        $res = $db->select_query($qry, array_merge(array($type), $setids, array(sets_marking::MARKING_ON)));
        if ($res === FALSE) {
            return;
        }

        $setids = array();
        foreach ($res as $r) {
            $setids[] = $r['ID'];
        }

    }
    $uc->delete_set($setids, $type);
    $uc->disconnect();
}

function get_total_rss_sets(DatabaseConnection $db)
{
    $res = $db->select_query('COUNT(*) AS cnt FROM rss_sets');

    return $res[0]['cnt'];
}

function get_total_ng_sets(DatabaseConnection $db)
{
    $res = $db->select_query('COUNT(*) AS cnt FROM setdata');

    return $res[0]['cnt'];
}

function get_total_spots(DatabaseConnection $db)
{
    $res = $db->select_query('COUNT(*) AS cnt FROM spots');

    return $res[0]['cnt'];
}

function get_minsetsize_feed(DatabaseConnection $db, $feed_id, $userid, $default=0)
{
    assert(is_numeric($default));
    assert(is_numeric($userid));
    assert(is_numeric($feed_id));
    $qry = "\"minsetsize\" FROM userfeedinfo WHERE \"feedid\"=? AND \"userid\"=? UNION SELECT $default";
    $res = $db->select_query($qry, 1, array($feed_id, $userid));

    return (!isset($res[0]['minsetsize'])) ? $default : $res[0]['minsetsize'];
}

function get_maxsetsize_feed(DatabaseConnection $db, $feed_id, $userid, $default=0)
{
    assert(is_numeric($default));
    assert(is_numeric($userid));
    assert(is_numeric($feed_id));
    $qry = "\"maxsetsize\" FROM userfeedinfo WHERE \"feedid\"=? AND \"userid\"=? UNION SELECT $default";
    $res = $db->select_query($qry, 1, array($feed_id, $userid));

    return (!isset($res[0]['maxsetsize'])) ? $default : $res[0]['maxsetsize'];
}

function get_minsetsize_group(DatabaseConnection $db, $group_id, $userid, $default=0)
{
    assert(is_numeric($default));
    assert(is_numeric($userid));
    assert(is_numeric($group_id));
    $qry = "\"minsetsize\" FROM usergroupinfo WHERE \"groupid\"=? AND \"userid\"=? UNION SELECT $default";
    $res = $db->select_query($qry, 1, array($group_id, $userid));

    return (!isset($res[0]['minsetsize'])) ? $default : $res[0]['minsetsize'];
}

function get_maxsetsize_group(DatabaseConnection $db, $group_id, $userid, $default=0)
{
    assert(is_numeric($default));
    assert(is_numeric($userid));
    assert(is_numeric($group_id));
    $qry = "\"maxsetsize\" FROM usergroupinfo WHERE \"groupid\"=? AND \"userid\"=? UNION SELECT $default";
    $res = $db->select_query($qry, 1, array($group_id, $userid));

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
    $sql = '* FROM categories WHERE "userid"=? ORDER BY "name"';
    $res = $db->select_query($sql, array($userid));
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
        $Qadult = ' AND groups."adult" != ' . ADULT_ON . ' ';
    }
    if (is_numeric($groupID) && $groupID != 0 && $groupID != '') {
        $Qgroups .= " OR \"groupid\" = '$groupID'";
    } elseif (is_numeric($categoryID) && $categoryID != 0 && $categoryID != '') {
        $Qgroups .= ' OR "groupid" IN (';
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
    $sql = 'groups."ID", groups."name", groups."setcount" FROM groups LEFT JOIN usergroupinfo ON groups."ID" = "groupid" AND "userid" = :userid ' .
        " WHERE \"active\" = :active AND (\"visible\" > 0 OR \"visible\" IS NULL $Qgroups) $Qadult ORDER BY \"name\"";
    $res = $db->select_query($sql, array(':userid'=>$userid, ':active'=> newsgroup_status::NG_SUBSCRIBED));
    if (!is_array($res)) {
        $res = array();
    }

    $subscribedgroups = array();
    $c = 0;
    foreach ($res as $arr) {
        list($size, $suffix) = format_size($arr['setcount'], 'h', '', 1000, 0);
        if ($size != 0 || ($arr['ID'] == $groupID)) { // don't show empty groups anyway
            $subscribedgroups[$c] = array(
                'id'            => $arr['ID'],
                'name'          => $arr['name'],
                'shortname'     => shorten_newsgroup_name($arr['name']),
                'article_count' => $size . $suffix,
                'type'          => 'group'
            );
            $c++;
        }
    }
    foreach ($categories as $arr) {
        list($size, $suffix) = format_size($arr['setcount'], 'h', '', 1000, 0);
        if ($size != 0 || $arr['id'] == $categoryID) { // don't show empty categories either
            $subscribedgroups[$c] = array(
                'id'            => $arr['id'],
                'name'          => $arr['name'],
                'shortname'     => $arr['name'],
                'article_count' => $size . $suffix,
                'type'          => 'category'
            );
            $c++;
        }
    }

    return $subscribedgroups;
}

function get_userfeed_settings(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $sql = 'categories."name" AS c_name, rss_urls."name" AS f_name, userfeedinfo."minsetsize", userfeedinfo."maxsetsize", userfeedinfo."visible" ' .
        'FROM userfeedinfo LEFT JOIN categories ON userfeedinfo."category" = categories."id" LEFT JOIN rss_urls ON userfeedinfo.feedid = rss_urls."id" ' .
        'WHERE userfeedinfo."userid"=?';
    $res = $db->select_query($sql, array($userid));
    if (!is_array($res)) {
        return array();
    }

    return $res;
}

function get_usergroup_settings(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $sql = 'categories."name" AS c_name, groups."name" AS g_name, usergroupinfo."minsetsize", usergroupinfo."maxsetsize", usergroupinfo."visible" ' .
        'FROM usergroupinfo LEFT JOIN categories ON usergroupinfo."category" = categories."id" LEFT JOIN groups ON usergroupinfo."groupid" = groups."ID" ' .
        'WHERE usergroupinfo."userid"=?';
    $res = $db->select_query($sql, array($userid));
    if (!is_array($res)) {
        return array();
    }

    return $res;
}

function get_used_categories_group(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $sql = 'SUM("setcount") AS cnt, usergroupinfo."category", MAX(categories."name") AS "name" FROM usergroupinfo '
        . 'JOIN groups ON "groupid" = groups."ID" '
        . 'JOIN categories ON usergroupinfo."category" = categories."id" '
        . 'WHERE categories."userid"=? AND usergroupinfo."category" > 0 GROUP BY usergroupinfo."category"';
    $res = $db->select_query($sql, array($userid));
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
    $sql = 'spots."category", COUNT("id") AS cnt FROM spots GROUP BY spots."category"';
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
        list($size, $suffix) = format_size($row['setcount'], 'h', '', 1000, 0);
        if ($size != 0 || ($row['id'] == $categoryid)) { // don't show empty groups anyway
            $subscribedspots[$row['id']] = array('id'=> $row['id'], 'article_count' => $size . $suffix, 'name' => $row['name']);
        }
    }

    return $subscribedspots;
}

function get_used_categories_rss(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $sql = 'SUM("feedcount") AS cnt, userfeedinfo."category", MAX(categories."name") AS "name" FROM userfeedinfo '
        . 'JOIN rss_urls ON "feedid" = rss_urls."id" '
        . 'JOIN categories ON userfeedinfo."category" = categories."id" '
        . 'WHERE categories."userid"=? AND userfeedinfo."category" > 0 GROUP BY userfeedinfo."category"';
    $res = $db->select_query($sql, array($userid));
    if (!is_array($res)) {
        return array();
    }
    $categories = array();
    foreach ($res as $row) {
        $categories["{$row['category']}"] = array('id' => $row['category'], 'name' => $row['name'], 'setcount' => $row['cnt']);
    }

    return $categories;
}

function subscribed_feeds_select(DatabaseConnection $db, $feed_id, $categoryID, array $categories, $userid)
{
    assert(is_numeric($userid));
    $adult = urd_user_rights::is_adult($db, $userid);
    $input_arr = array($userid, rssfeed_status::RSS_SUBSCRIBED);
    $Qadult = '';
    if (!$adult) {
        $Qadult = ' AND rss_urls.adult != ? ';
        $input_arr[] = ADULT_ON;
    }
    $qfeed_id = '';
    if (is_numeric($feed_id)) {
        $qfeed_id = 'OR "feedid" = ?';
        $input_arr[] = $feed_id;
    }
    // Get the feeds:
    $sql = 'rss_urls."id", "name", "feedcount" FROM rss_urls LEFT JOIN userfeedinfo ON rss_urls."id" = "feedid" AND "userid" = ? ' .
        " WHERE \"subscribed\" = ? $Qadult AND (\"visible\" > 0 OR \"visible\" IS NULL $qfeed_id) ORDER BY \"name\"";
    $res = $db->select_query($sql, $input_arr);

    if (!is_array($res)) {
        $res = array();
    }

    $c = 0;
    $subscribedfeeds = array();
    foreach ($res as $arr) {
        list($size, $suffix) = format_size($arr['feedcount'], 'h', '', 1000, 0);
        if ($size != 0 || ($arr['id'] == $feed_id)) { // don't show empty groups anyway
            $subscribedfeeds[$c] = array(
                'id'            => $arr['id'],
                'name'          => $arr['name'],
                'type'          => 'feed',
                'article_count' => $size . $suffix,
            );
            $c++;
        }
    }

    foreach ($categories as $arr) {
        list($size, $suffix) = format_size($arr['setcount'], 'h', '', 1000, 0);
        if ($size != 0 || ($arr['id'] == $categoryID)) { // don't show empty categories either
            $subscribedfeeds[$c] = array(
                'id'            => $arr['id'],
                'name'          => $arr['name'],
                'type'          => 'category',
                'article_count' => $size . $suffix,
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
    $input_arr = array($userid);
    $sql = '"feedid", "last_update_seen" FROM userfeedinfo WHERE "userid"=?';

    if (is_numeric($feed_id) && $feed_id != 0) {
        $sql .= " AND \"feedid\" = '$feed_id'";
        $input_arr[] = $feed_id;
    }
    $res = $db->select_query($sql, $input_arr);
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
    $input_arr = array($userid);
    $sql = '"groupid", "last_update_seen" FROM usergroupinfo WHERE "userid"=?';
    if ($groupid != '') {
        if (!is_numeric($groupid)) {
            $groupid = group_by_name($db, $groupid);
        }
        if ($groupid != 0) {
            $sql .= " AND \"groupid\" = ?";
            $input_arr[] = $groupid;
        }
    }

    $res = $db->select_query($sql, $input_arr);
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
    if ($stylesheet == '' || !file_exists($template_dir . '/' . $stylesheet . '/' . $stylesheet . '.css') || !is_file($template_dir . '/' . $stylesheet. '/' . $stylesheet . '.css')) {
        if (!file_exists($template_dir . '/' . $default_stylesheet . '/'. $default_stylesheet . '.css') || !is_file($template_dir . '/' . $default_stylesheet . '/' .$default_stylesheet . '.css')) {
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
    foreach ($_REQUEST as $key => $value) {
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
    $input_arr = array();
    if (!$isadmin) {
        assert(is_numeric($userid));
        $input_arr[] = $userid;
        $quser = 'AND "userid"=?';
    }

    $ystr = $db->get_extract('year', '"timestamp"');
    $qry = " $ystr AS \"year\" FROM stats WHERE 1=1 $quser GROUP BY $ystr ORDER BY \"year\" DESC";
    $res = $db->select_query($qry, $input_arr);
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
    if ($type == 'group') {
        $table = 'groups';
    } elseif ($type == 'rss') {
        $table = 'rss_urls';
    } else {
        throw new exception($LN['error_unknowntype']);
    }
    $db->update_query_2($table, array('adult'=>$value), '"ID"=?', array($groupid));
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

    return array('order' => $o, 'direction' => $d);
}


function add_to_blacklist(DatabaseConnection $db, $spotterID, $userid, $global, $source =blacklist::BLACKLIST_INTERNAL, $status=blacklist::ACTIVE)
{
    assert(is_bool($global) && is_numeric($userid));
    if ($global && urd_user_rights::is_admin($db, $userid)) {
        $userid = user_status::SUPER_USERID; // if it is set by the root user it is global, if by any other userid it's for that user onl
    }
    $sql = 'count(*) AS cnt FROM spot_blacklist WHERE "spotter_id"=? AND "source"=? AND "userid"=?';
    $res = $db->select_query($sql, array($spotterID, $source, $userid));
    if ($res[0]['cnt'] == 0) {
        $add_ids = array($spotterID, $source, $userid, $status);
        $cols = array('spotter_id', 'source', 'userid', 'status');
        $db->insert_query('spot_blacklist', $cols, $add_ids);
    }
}

function add_to_whitelist(DatabaseConnection $db, $spotterID, $userid, $global, $source=whitelist::WHITELIST_INTERNAL, $status=whitelist::ACTIVE)
{
    assert(is_bool($global) && is_numeric($userid));
    if ($global && urd_user_rights::is_admin($db, $userid)) { // if it is set by the root user it is global, if by any other userid it's for that user only
        $userid == user_status::SUPER_USERID;
    }

    $sql = 'count(*) AS cnt FROM spot_whitelist WHERE "spotter_id"=? AND "source"=? AND "userid"=?';
    $res = $db->select_query($sql, array($spotterID, $source, $userid));
    if ($res[0]['cnt'] == 0) {
        $add_ids = array($spotterID, $source, $userid, $status);
        $cols = array('spotter_id', 'source', 'userid', 'status');
        $db->insert_query('spot_whitelist', $cols, $add_ids);
    }
}

function get_spotterid_from_spot(DatabaseConnection $db, $spotid)
{
    $sql = '"spotter_id" FROM spots WHERE "spotid"=?';
    $res = $db->select_query($sql, 1, array($spotid));
    if (!isset($res[0]['spotter_id'])) {
        return FALSE;
    }

    return $res[0]['spotter_id'];
}

function get_pages($totalsets, $perpage, $offset)
{
    assert (is_numeric($totalsets) && is_numeric($perpage) && is_numeric($offset));
    $size = SKIPPER_SIZE;
    $totalpages = max(1, ceil($totalsets / $perpage));      // Total number of pages.
    $activepage = ceil(($offset + 1) / $perpage);     // This is the page we're on. (+1 because 0/100 = page 1)
    $start = max($activepage - floor($size / 2), 1);  // We start at 1 unless we're now on page 12, then we show page 2.
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

function get_directories(DatabaseConnection $db, $userid)
{
    $dlpath = get_dlpath($db);
    $username = get_username($db, $userid);

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

    $sql = "count(*) AS cnt, $time_stamp AS dow, \"category\" FROM spots GROUP BY $time_stamp, \"category\" ";
    $res = $db->select_query($sql);
    if ($res === FALSE) {
        $res = array();
    }
    $stats = array();
    foreach (range(1, 7) as $i) {
            $stats[ ($i - 1) ] = array(html_entity_decode($LN['short_day_names'][$i]), 0, 0, 0, 0);
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

    $sql = "count(*) AS cnt, $time_extract AS mnth, \"category\" FROM spots GROUP BY $time_extract, \"category\"";
    $res = $db->select_query($sql);
    if ($res === FALSE) {
        $res = array();
    }
    $stats = array();
    if ($period == 'month') {
        foreach (range(1,12) as $i) {
            $stats[ $i ] = array(html_entity_decode($LN['short_month_names'][$i]), 0, 0, 0, 0);
        }
    } elseif ($period == 'week') {
        $max_week = 0;
        foreach (range(25, 31) as $r) {
            $max_week = max($max_week, (int) date('W', mktime(0, 0, 0, 12, $r)));
        }
        foreach (range(1,$max_week) as $i) {
            $stats[ $i ] = array($i, 0, 0, 0, 0);
        }
    } elseif ($period == 'hour') {
        foreach (range(0, 23) as $i) {
            $stats[ $i ] = array($i, 0, 0, 0, 0);
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
    $sql = 'count(*) AS "cnt", "category" FROM spots GROUP BY "category"';
    $res = $db->select_query($sql);
    if ($res === FALSE) {
        $res = array(0, 0, 0, 0);
    }
    $stats = array();
    foreach ($res as $row) {
        $stats[ $row['category'] ] = $row['cnt'];
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

function is_nzb_file($ext)
{
    return in_array(strtolower($ext), array('nzb'));
}
function is_nfo_file($ext)
{
    return in_array(strtolower($ext), array('nfo'));
}


function insert_wbr($str, $size = 64)
{
    assert(is_numeric($size));
    $l = strlen($str);
    $str_new = '';
    $t = 0;
    $in_tag = 0;
    for ($i = 0; $i < $l; $i++) {
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
        if (ctype_space($str[$i])) {
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

function split_search_string($search)
{
    $keywords = array();
    $keyword = '';
    $quote = ' ';
    $l = strlen($search);
    for ($i = 0; $i < $l; ++$i) {
        $s = $search[$i];
        if ($s == '\\') { 
            $i++;
            $keyword .= $search[$i];
        } elseif (in_array($s, array('\'', '"'))) {
            if ($s == $quote) { 
                $quote = ' ';
                if ($keyword != '') {
                    $keywords[] = $keyword;
                    $keyword = '';
                }
            } elseif ($quote == ' ') {
                $quote = $s;
                if ($keyword != '') {
                    $keywords[] = $keyword;
                    $keyword = '';
                }
            }
        } elseif ($quote == ' ' && (in_array($s, array(' ', "\t", "\n", "\v", "\r")))) {
            if ($keyword != '') {
                $keywords[] = $keyword;
                $keyword = '';
            }            
        } else {
            $keyword .= $s;
        }
    }
    if ($keyword != '') {
        $keywords[] = $keyword;
    }
    return $keywords;
}
 
function parse_search_string($search, $column1, $column2, $column3, $search_type, &$input_arr)
{
    $Qsearch = '';
    // Search google style:
    if ($search != '') {
        $search = trim(str_replace('*', ' ', $search));
        $search = strtolower($search);
        $keywords = split_search_string($search);
        $Qsearch1 = $Qsearch2 = $Qsearch3 = '';
        $next = $not = '';
        foreach ($keywords as $idx => $keyword) {
            $keyword = trim($keyword);
            if ($keyword == '') { continue; }
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
                    $input_arr[":keyword_1_$idx"] = "%{$keyword}%";
                    $Qsearch1 .= " $qand $not $column1 $search_type :keyword_1_$idx "; // nasty: like is case sensitive in psql, insensitive in mysql
                }
                if ($column2 != '') {
                    $input_arr[":keyword_2_$idx"] = "%{$keyword}%";
                    $Qsearch2 .= " $qand $not $column2 $search_type :keyword_2_$idx ";
                }
                if ($column3 != '') {
                    $input_arr[":keyword_3_$idx"] = "%{$keyword}%";
                    $Qsearch3 .= " $qand $not $column3 $search_type :keyword_3_$idx ";
                }
            }
        }
        $Qsearch .= 'AND ( 1=0 ';
        if ($Qsearch1 != '') {
            $Qsearch .= " OR (1=1 $Qsearch1 ) ";
        }
        if ($Qsearch2 != '') {
            $Qsearch .= " OR (1=1 $Qsearch2 ) ";
        }
        if ($Qsearch3 != '') {
            $Qsearch .= " OR (1=1 $Qsearch3 ) ";
        }
        $Qsearch .= ')';
    }
    return $Qsearch;
}

function get_size_limits_spots(DatabaseConnection $db)
{
    $sql = 'min("size") AS minsetsize, max("size") AS maxsetsize FROM spots';
    $res = $db->select_query($sql, 1);

    return array($res[0]['minsetsize'], $res[0]['maxsetsize']);
}

function get_age_limits_spots(DatabaseConnection $db)
{
    $now = time();
    $sql = "min({$now} - \"stamp\") AS \"minage\", max({$now} - \"stamp\") AS \"maxage\" FROM spots";
    $res = $db->select_query($sql, 1);

    return array($res[0]['minage'], $res[0]['maxage']);
}

function get_size_limits_groups(DatabaseConnection $db, $groupID=NULL)
{
    $sql = 'min("size") AS minsetsize, max("size") AS maxsetsize FROM setdata';
    $input_arr = array();
    if (is_numeric($groupID) && $groupID > 0) {
        $input_arr[] = $groupID;
        $sql .= ' WHERE "groupID"=?';
    }
    $res = $db->select_query($sql, 1, $input_arr);

    return array($res[0]['minsetsize'], $res[0]['maxsetsize']);
}

function get_age_limits_groups(DatabaseConnection $db, $groupID=NULL)
{
    $now = time();
    $input_arr = array();
    $sql = "min({$now} - \"date\") AS \"minage\", max({$now} - \"date\") AS \"maxage\" FROM setdata";
    if (is_numeric($groupID) && $groupID > 0) {
        $input_arr[] = $groupID;
        $sql .= ' WHERE "groupID"=?';
    }
    $res = $db->select_query($sql, 1, $input_arr);

    return array($res[0]['minage'], $res[0]['maxage']);
}

function get_size_limits_rsssets(DatabaseConnection $db, $rss_id=NULL)
{
    $sql = 'min("size") AS minsetsize, max("size") AS maxsetsize FROM rss_sets';
    $input_arr = array();
    if (is_numeric($rss_id) && $rss_id > 0) {
        $input_arr[] = $rss_id;
        $sql .= ' WHERE "rss_id"=?';
    }
    $res = $db->select_query($sql, 1, $input_arr);

    return array($res[0]['minsetsize'], $res[0]['maxsetsize']);
}

function get_age_limits_rsssets(DatabaseConnection $db, $rss_id=NULL)
{
    $now = time();
    $sql = "min({$now} - \"timestamp\") AS \"minage\", max({$now} - \"timestamp\") AS \"maxage\" FROM rss_sets";
    $input_arr = array();
    if (is_numeric($rss_id) && $rss_id > 0) {
        $input_arr[] = $rss_id;
        $sql .= ' WHERE "rss_id"=?';

    }
    $res = $db->select_query($sql, 1, $input_arr);

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

function get_poster_from_set(DatabaseConnection $db, $setid)
{
    $groupid = get_groupid_for_set($db, $setid);
    $sql = "\"fromname\" FROM parts_$groupid LEFT JOIN binaries_$groupid ON parts_$groupid.\"binaryID\" = binaries_$groupid.\"binaryID\" where setID = ? AND fromname != ''";
    $res = $db->select_query($sql, 1, array($setid));
    if (!isset($res[0]['fromname'])) {
        throw new exception($LN['error_binariesnotfound']);
    }
    $fromname = $res[0]['fromname'];

    return $fromname;
}

// parse list of comma separated language tags and sort it by the quality value
function parse_language_list($language_list)
{
    $languages = array();
    $language_ranges = explode(',', trim($language_list));
    foreach ($language_ranges as $language_range) {
        if (preg_match('/(\*|[a-zA-Z0-9]{1,8}(?:-[a-zA-Z0-9]{1,8})*)(?:\s*;\s*q\s*=\s*(0(?:\.\d{0,3})|1(?:\.0{0,3})))?/', trim($language_range), $match)) {
            if (!isset($match[2])) {
                $match[2] = '1.0';
            } else {
                $match[2] = (string) floatval($match[2]);
            }
            if (!isset($languages[$match[2]])) {
                $languages[$match[2]] = array();
            }
            $languages[$match[2]][] = strtolower($match[1]);
        }
    }
    krsort($languages);

    return $languages;
}

// compare two parsed arrays of language tags and find the matches
function find_language_matches($accepted, $available)
{
    $matches = array();
    $any = FALSE;
    foreach ($accepted as $acceptedQuality => $acceptedValues) {
        $acceptedQuality = floatval($acceptedQuality);
        if ($acceptedQuality === 0.0) { 
            continue;
        }
        foreach ($available as $availableQuality => $availableValues) {
            $availableQuality = floatval($availableQuality);
            if ($availableQuality === 0.0) { 
                continue;
            }
            foreach ($acceptedValues as $acceptedValue) {
                if ($acceptedValue === '*') {
                    $any = TRUE;
                }
                foreach ($availableValues as $availableValue) {
                    $matchingGrade = match_language($acceptedValue, $availableValue);
                    if ($matchingGrade > 0) {
                        $q = (string) ($acceptedQuality * $availableQuality * $matchingGrade);
                        if (!isset($matches[$q])) {
                            $matches[$q] = array();
                        }
                        if (!in_array($availableValue, $matches[$q])) {
                            $matches[$q][] = $availableValue;
                        }
                    }
                }
            }
        }
    }
    if (count($matches) === 0 && $any) {
        $matches = $available;
    }
    krsort($matches);

    return $matches;
}

// compare two language tags and distinguish the degree of matching
function match_language($a, $b)
{
    $a = explode('-', $a);
    $b = explode('-', $b);
    for ($i = 0, $n = min(count($a), count($b)); $i < $n; $i++) {
        if ($a[$i] !== $b[$i]) break;
    }

    return $i === 0 ? 0 : ((float) $i / count($a));
}

function detect_language()
{
    if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        return 'english';
    }
    $accepted   = parse_language_list($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $available  = parse_language_list('en, fr, de, sv, nl');
    $matches    = find_language_matches($accepted, $available);

    // the default is English
    $lang = 'english';
    foreach ($matches as $ln1) {
        foreach ($ln1 as $ln) {
            switch (substr(trim($ln), 0, 2)) {
                case 'en':
                    $lang = 'english';

                    return $lang;
                    break;
                case 'nl':
                    $lang = 'nederlands';

                    return $lang;
                    break;
                case 'fr':
                    $lang = 'francais';

                    return $lang;
                    break;
                case 'de':
                    $lang = 'deutsch';

                    return $lang;
                    break;
                case 'sv':
                    $lang = 'svenska';

                    return $lang;
                    break;
            }
        }
    }

    return $lang;
}

function verify_time($time1, $time2, $name)
{
    global $LN;

    if (!is_numeric($time1)) {
            throw new exception($name . ': ' . $LN['error_notanumber'] . " ({$LN['time']}) ");
        }
        if (!is_numeric($time2)) {
            throw new exception($name . ': ' . $LN['error_notanumber'] . " ({$LN['time']}) ");
        }
        if ($time1 > 23 || $time1 < 0) {
            throw new exception($name . ': ' . $LN['error_toomanydays'] . " ({$LN['time']}) ");
        }
        if ($time2 > 59 || $time1 < 0) {
            throw new exception($name . ': ' . $LN['error_toomanymins'] . " ({$LN['time']}) ");
        }
}

function verify_expire(DatabaseConnection $db, $expire, $name)
{
    global  $LN;
    $max_expire = get_config($db, 'maxexpire');
    if (!is_numeric($expire)) {
        throw new exception($name . ': ' . $LN['error_invalidvalue'] . ': ' . $LN['ng_expire_time'] . ' ' . htmlentities($expire));
    }
    if ($expire > $max_expire || $expire < 1) {
        throw new exception($name . ': ' . $LN['error_bogusexptime'] . ': ' . htmlentities($expire) . ' XXX ' . $max_expire);
    }
}

function file_upload_error_message($error_code)
{ // xxx fix language shit
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'File upload stopped by extension';
        default:
            return 'Unknown upload error';
    }
}

function get_uploaded_files() 
{
    if (isset($_FILES['error']) && $_FILES['error'] != 0) {
        throw new exception('error!?');
    }

    if (empty($_FILES)) {
        throw new exception (file_upload_error_message(UPLOAD_ERR_INI_SIZE));
    }
    if ($_FILES['upfile']['error'] !== UPLOAD_ERR_OK) {
        print_r($_FILES);
        throw new exception(file_upload_error_message($_FILES['upfile']['error']));
    }

    $filename = $_FILES['upfile']['tmp_name'];
    $orig_filename = $_FILES['upfile']['name'];
    if (!file_exists($filename)) {
        throw new exception(file_upload_error_message(UPLOAD_ERR_NO_FILE));
    }

    return array( $filename, $orig_filename);
}

function generate_hash($prefix, $infix=FALSE)
    // we need to move this actually to the js code
{
    if ($infix !== FALSE) { 
        $prefix .= '.' . $infix;
    }
    do {
        $unique_str = generate_password(15);
        $messageid = '<' . $prefix . '.' . $unique_str . '@spot.net>';
        $hash = sha1($messageid);
    } while (substr($hash, 0, 4) !== '0000');
    return $messageid;
}

function get_smileys($dir, $full= FALSE)
{
    $smileys = array();
    $_smileys = glob($dir. DIRECTORY_SEPARATOR . '/smileys/' . '*.gif');
    foreach($_smileys as $smiley) {
        $s = basename($smiley, '.gif');
        if (!$full) {
            $smileys [$s] = $s;
        } else {
            $smileys [$s] = $smiley;
        }

    }
    return $smileys;
}

function return_result(array $vars=array()) 
{
    if (!isset($vars['error'])) { 
        $vars['error'] = 0;
    }
//    if (isset($var['contents'])) { // should we do this??? might mangle some utf8 stuff
     //   $vars['contents'] = preg_replace('/[[:cntrl:]]+/u', '', $vars['contents']);
 //   }
    die(json_encode($vars));
}

function link_to_url($description)
{
    $position = 0;
    while (preg_match('|https?:\/\/[-a-z0-9_:./&%!@#$?^()+=\\;]+|i', $description, $matches, PREG_OFFSET_CAPTURE, $position)) {
        list($url, $urlposition) = $matches[0];
        $d1 = substr($description, 0, $urlposition);
        $l = strlen($url);
        $d2 = substr($description, $urlposition + $l);
        $new_url = $url;
        if ((strpos(substr($d1, -10), '[url]') === FALSE) && (strpos(substr($d2, 0, 10), '[/url]') === FALSE)) {
            $new_url = '[url]' . $url . '[/url]';
        }
        $new_l = strlen($new_url);
        $description = $d1 . $new_url . $d2;
        $position = $urlposition + $new_l;
    }

    return $description;
}

function get_languages_array()
{
    $languages = array_map('htmlentities', get_languages());

    return $languages;
}

function urdd_connected(DatabaseConnection $db, $userid)
{
    $rprefs = load_config($db);
    $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);

    return $uc->is_connected();
}
