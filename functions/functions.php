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
 * $LastChangedDate: 2014-06-27 23:33:18 +0200 (vr, 27 jun 2014) $
 * $Rev: 3123 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: functions.php 3123 2014-06-27 21:33:18Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathf = realpath(dirname(__FILE__));

require_once "$pathf/autoincludes.php";
require_once "$pathf/libs/magpierss/rss_fetch.php";

function format_size($value, $format, $suffix='B', $base=1024, $dec=1)
{
    global $LN;
    assert(is_numeric($value) && is_numeric($base) && is_numeric($dec));
    if ($value == 0) {
        return array(0, $suffix, 1);
    }
    $units  = array(0 => '', 1 => 'k', 2 => 'M', 3 => 'G', 4 => 'T', 5 => 'P', 6 => 'E', 7 => 'Z', 8 => 'Y'); // these are mixed case for the return value
    $_units = array(0 => '', 1 => 'k', 2 => 'm', 3 => 'g', 4 => 't', 5 => 'p', 6 => 'e', 7 => 'z', 8 => 'y'); // these are all lowercase for the comparison
    $rev_units = array_flip($_units);
    $factor = 0;
    $t = '';
    $idx = strtolower(substr($format, 0, 1));
    if ($idx == 'h') {
        $factor = min(floor(log($value, $base)), 8);
        $t = $units[$factor];
    } elseif (isset($rev_units[$idx])) {
        $factor = $rev_units[$idx];
        $t = $units[$factor];
    }
    $divisor = pow($base, $factor);
    if (($value % $divisor) == 0) { 
        $dec = 0; 
    }
    $value = number_format($value / $divisor, $dec, (isset($LN['decimalseparator']) ? $LN['decimalseparator'] : '.'), '');

    return array($value, $t . $suffix, $divisor);
}

function unformat_size($val, $base = 1024, $default_mul='')
{
     // default_mul is the default multiplier from SI: M=1000^2, K=1000, G=100^3 etc if no multiplier is found like in 100M ==> 100 * 1000^2
    global $LN;
    static $exps = array('y' => 8, 'z'=> 7, 'e'=> 6, 'p'=> 5, 't'=>4, 'g' => 3, 'm'=>2, 'k'=>1);
    assert(is_numeric($base));
    $val = trim($val);
    if ($val == '') {
        throw new exception($LN['error_notanumber']);
    }

    sscanf($val, '%f%c%c', $val, $last, $rem);
    if ($val === NULL) {
        throw new exception($LN['error_notanumber']);
    }
    if ($rem != '') {
        throw new exception('Trailing characters found: ' . $rem);
    }

    if ($last == '') {
        $last = $default_mul;
    }

    $val = round($val);
    $last = strtolower($last);
    if (isset($exps[$last])) {
        return $val * pow ($base, $exps[$last]);
    } elseif ($last != '') {
        throw new exception ("Unknown quantifier $last");
    }

    return $val;
}

function one_or_more($val, $one, $more)
{
    return  "$val " . ( ($val == 1) ? $one : $more);
}

function readable_time($timediff, $value='largest')
{
    assert(is_numeric($timediff));
    global $LN;
    if (!isset($LN)) {
        return 'NOLANGUAGE';
    }

    if ($timediff < 0) {
        return '?';
    }
    $otimediff = $timediff;
    $years = (int) ($timediff / (3600 * 24 * 365));
    $timediff -= ($years * 3600 * 24 * 365);
    $weeks = (int) ($timediff / (3600 * 24 * 7));
    $timediff -= ($weeks * 3600 * 24 * 7);
    $days = (int) ($timediff / (3600 * 24));
    $timediff -= ($days * 3600 * 24);
    $hours = (int) ($timediff / 3600);
    $timediff -= ($hours * 3600);
    $minutes = (int) ($timediff / 60);
    $timediff -= ($minutes * 60);
    $seconds = $timediff;

    switch ($value) {
    default:
        assert(FALSE);
    case 'largest':
        if ($years > 0)   { return $years . ' ' . $LN['year_short'];}
        if ($weeks > 0)   { return $weeks . ' ' . $LN['week_short'];}
        if ($days > 0)    { return $days . ' ' . $LN['day_short'];}
        if ($hours > 0)   { return $hours . ' ' . $LN['hour_short'];}
        if ($minutes > 0) { return $minutes . ' ' . $LN['minute_short'];}
        return $seconds . ' ' . $LN['second_short'];
        break;
    case 'largest_long':
        if ($years > 0)   { return $years . ' ' . $LN['year' . ($years == 1? '':'s')];}
        if ($weeks > 0)   { return $weeks . ' ' . $LN['week' . ($weeks == 1? '':'s')];}
        if ($days > 0)    { return $days . ' ' . $LN['day' . ($days == 1? '':'s')];}
        if ($hours > 0)   { return $hours . ' ' . $LN['hour' . ($hours == 1? '':'s')];}
        if ($minutes > 0) { return $minutes . ' ' . $LN['minute' . ($minutes == 1? '':'s')];}
        return one_or_more($seconds, $LN['second'], $LN['seconds']);
        break;
    case 'largest_two_long':
        if ($years > 0)   { return one_or_more($years, $LN['year'], $LN['years']) . (($weeks > 0) ? ' ' . one_or_more($weeks, $LN['week'], $LN['weeks']) : ''); }
        if ($weeks > 0)   { return one_or_more($weeks, $LN['week'], $LN['weeks']) . (($days > 0) ? ' ' . one_or_more($days, $LN['day'], $LN['days']) : ''); }
        if ($days > 0)    { return one_or_more($days, $LN['day'], $LN['days']) . (($hours > 0) ? ' ' . one_or_more($hours, $LN['hour'], $LN['hours']) : '');}
        if ($hours > 0)   { return one_or_more($hours, $LN['hour'], $LN['hours']) . (($minutes > 0) ? ' ' . one_or_more($minutes, $LN['minute'], $LN['minutes']) : '');}
        if ($minutes > 0) { return one_or_more($minutes, $LN['minute'], $LN['minutes']) . (($seconds > 0) ? ' ' . one_or_more($seconds, $LN['second'], $LN['seconds']) : '');}

        return one_or_more($seconds, $LN['second'], $LN['seconds']);
        break;
    case 'largest_two':
        if ($years > 0)   { return $years . ' ' . $LN['year_short'] . (($weeks > 0) ? ' ' . $weeks . ' ' . $LN['week_short'] : ''); }
        if ($weeks > 0)   { return $weeks . ' ' . $LN['week_short'] . (($days > 0) ? ' ' . $days . ' ' . $LN['day_short'] : ''); }
        if ($days > 0)    { return $days . ' ' . $LN['day_short'] . (($hours > 0) ? ' ' . $hours . ' ' . $LN['hour_short'] : '');}
        if ($hours > 0)   { return $hours . ' ' . $LN['hour_short'] . (($minutes > 0) ? ' ' . $minutes . ' ' . $LN['minute_short'] : '');}
        if ($minutes > 0) { return $minutes . ' ' . $LN['minute_short'] . (($seconds > 0) ? ' ' . $seconds . ' ' . $LN['second_short'] : '');}

        return $seconds . ' ' . $LN['second_short'];
        break;
    case 'fancy':
        if ($otimediff < 60) {
            $return = '0:';
            if ($otimediff < 10) {
                $return .= '0';
            }
            $return .= $otimediff;
        } else {
            if ($minutes < 10) {
                $minutes = '0' . $minutes;
            }
            if ($seconds < 10) {
                $seconds = '0' . $seconds;
            }
            $return = "$hours:$minutes:$seconds";
            $return = ltrim($return, ':0 ');
            if ($days > 0) {
                $return = $days . ' ' . $LN['day_short'] . ' ' . $return;
            }
        }
        break;
    }

    return $return;
}

function time_format($time)
{
    assert(is_numeric($time));

    $dateformat = 'Y-m-d';
    $timeformat = 'H:i:s';

    // Language specific settings:
    global $LN;
    if (isset($LN['dateformat'], $LN['timeformat'])) {
        $dateformat = $LN['dateformat'];
        $timeformat = $LN['timeformat'];
    }

    if ($time == 0) {
        return '';
    }
    $now = time() + 60; // 1 min offset...
    if (date('zY', $now) == date('zY', $time)) {
        return date($timeformat, $time);
    } else {
        return date($dateformat . ' ' . $timeformat, $time);
    }
}

function insert_category(DatabaseConnection $db, $userid, $name)
{
    assert(is_numeric($userid));
    $db->insert_query('categories', array('name', 'userid'), array($name, $userid));
}

function set_userfeedinfo(DatabaseConnection $db, $userid, $feedid, $minsetsize, $maxsetsize, $visible, $category)
{
    assert(is_numeric($userid) && is_numeric($maxsetsize) && is_numeric($minsetsize));
    $sql = '"feedid" FROM userfeedinfo WHERE "feedid" = :feedid AND "userid" = :userid';
    $res = $db->select_query($sql, 1, array(':feedid'=>$feedid, ':userid'=>$userid));
    if ($res === FALSE) {
        $db->insert_query('userfeedinfo', array('minsetsize', 'maxsetsize', 'visible', 'feedid', 'userid', 'category'), array($minsetsize, $maxsetsize, $visible, $feedid, $userid, $category));
    } else {
        $db->update_query('userfeedinfo', array('minsetsize', 'maxsetsize', 'visible', 'category'), array($minsetsize, $maxsetsize, $visible, $category),
                '"feedid"=? AND "userid"=?', array($feedid, $userid));
    }
}

function set_userfeedinfo_value(DatabaseConnection $db, $userid, $feedid, $option, $value)
{
    assert(is_numeric($userid));
    if (!in_array($option, array('minsetsize', 'maxsetsize', 'visible', 'category'))) {
        throw new exception($LN['error_invalidvalue']);
    }
    $sql = '"feedid" FROM userfeedinfo WHERE "feedid" = :feedid AND "userid" = :userid';
    $res = $db->select_query($sql, 1,  array(':feedid'=>$feedid, ':userid'=>$userid));
    if ($res === FALSE) {
        $db->insert_query('userfeedinfo', array('minsetsize', 'maxsetsize', 'visible', 'feedid', 'userid', 'category'), array(0, 0, 1, $feedid, $userid, 0));
    }
    $db->update_query_2('userfeedinfo', array($option=>$value), '"feedid"=? AND "userid"=?', array($feedid,$userid));
}

function set_usergroupinfo(DatabaseConnection $db, $userid, $groupid, $minsetsize, $maxsetsize, $visible, $category)
{
    assert(is_numeric($userid) && is_numeric($maxsetsize) && is_numeric($minsetsize) && is_numeric($groupid));
    $sql = '"groupid" FROM usergroupinfo WHERE "groupid"=:groupid AND "userid"=:userid';
    $res = $db->select_query($sql, 1, array(':groupid'=>$groupid, ':userid'=>$userid));
    if ($res === FALSE) {
        $db->insert_query('usergroupinfo', array('minsetsize', 'maxsetsize', 'visible', 'groupid', 'userid', 'category'), array($minsetsize, $maxsetsize, $visible, $groupid, $userid, $category));
    } else {
        $db->update_query('usergroupinfo', array('minsetsize', 'maxsetsize', 'visible', 'category'), array($minsetsize, $maxsetsize, $visible, $category), '"groupid"=? AND "userid"=?', array($groupid, $userid));
    }
}

function set_usergroup_value(DatabaseConnection $db, $userid, $groupid, $option, $value)
{
    global $LN;
    assert(is_numeric($userid) && is_numeric($groupid));
    $sql = '"groupid" FROM usergroupinfo WHERE "groupid"=:groupid AND "userid"=:userid';
    $res = $db->select_query($sql, 1, array(':groupid'=>$groupid, ':userid'=>$userid));
    if (!in_array($option, array('minsetsize', 'maxsetsize', 'visible', 'category'))) {
        throw new exception($LN['error_invalidvalue']);
    }
    if ($res === FALSE) {
        $db->insert_query('usergroupinfo', array('minsetsize', 'maxsetsize', 'visible', 'groupid', 'userid', 'category'), array(0, 0, 1, $groupid, $userid, 0));
    }
    $db->update_query_2('usergroupinfo', array($option=>$value), '"groupid"=? AND "userid"=?', array($groupid, $userid));
}

function category_by_name(DatabaseConnection $db, $category, $userid)
{
    assert(is_numeric($userid));
    if ($category == '') {
        return 0;
    }
    $like = $db->get_pattern_search_command('LIKE');
    $sql = "\"id\" FROM categories WHERE \"name\" $like :cat AND \"userid\" = :userid";
    $res = $db->select_query($sql, 1, array(':cat'=>$category, ':userid'=>$userid));

    return (!isset($res[0]['id'])) ? 0 : $res[0]['id'];
}

function update_queue_priority(DatabaseConnection $db, $id, $priority)
{
    assert(is_numeric($id) && is_numeric($priority));
    $db->update_query_2('queueinfo', array('priority'=>$priority), '"ID"=?', array($id));
}

function insert_queue_status(DatabaseConnection $db, $id, $description, $status, $command_id, $userid, $paused, $comments=NULL, $priority=1, $restart = TRUE)
{
    assert (is_numeric($id) && is_numeric($priority) && is_bool($restart) && is_numeric($userid));
    $restart = ($restart === TRUE) ? 1 : 0;
    $time = time();
    $cols = array ('status', 'description', 'lastupdate', 'starttime', 'paused', 'username', 'userid', 'progress', 'ETA', 'urdd_id', 'comment', 'priority', 'command_id', 'restart');
    $vals = array ($status, $description, $time, $time, $paused, '', $userid, 0, 0, $id, ($comments === NULL ? '' : $comments), $priority, $command_id, $restart);

    try {
        return $db->insert_query('queueinfo', $cols, $vals, TRUE);
    } catch (exception $e) {
        return FALSE;
    }
}

function update_queue_norestart(DatabaseConnection $db, $id)
{
    assert(is_numeric($id));
    $db->update_query_2('queueinfo', ['restart'=>0, 'lastupdate'=>time()], '"ID"=?', [$id]);
}

function update_queue_status(DatabaseConnection $db, $id, $status=NULL, $eta=NULL, $progress=NULL, $comments=NULL, $paused=NULL)
{
    echo_debug("Updating status $status; progress: $progress; ETA $eta; comments: $comments; paused: $paused", DEBUG_MAIN);
    assert(is_numeric($id));
    $cols = ['lastupdate'=>time()];
    if ($status !== NULL) {
        $cols['status'] = $status;
    }
    if ($eta !== NULL) {
        assert(is_numeric($eta));
        $eta = round($eta);
        $cols['ETA'] = $eta;
    }
    if ($progress !== NULL) {
        assert(is_numeric($progress));
        $cols['progress'] = $progress;
    }
    if ($comments !== NULL) {
        $cols['comment'] = $comments;
    }
    if ($paused !== NULL) {
        assert(is_bool($paused));
        $cols['paused'] = ($paused?'1':'0');
    }
    try {
        $db->update_query_2('queueinfo', $cols, '"ID"=?', [$id]);
    } catch (exception $e) {
        echo_debug($e->getMessage(), DEBUG_MAIN);
        throw $e;
    }
}

function sanitise_download_name(DatabaseConnection $db, $name, $has_subdirs=FALSE)
{   
    $name = simplify_chars($name);
    $replacement_str = get_config($db, 'replacement_str');
    $dir_sep = $has_subdirs ? '\\' . DIRECTORY_SEPARATOR : '';
    $pattern = "/[^A-Za-z0-9_\-.();[\]$dir_sep ]/";
    $pattern2 = "/[^A-Za-z0-9_\-.();[\]$dir_sep]/";
    $replacement_str = preg_replace($pattern, '', $replacement_str);
    $res = trim(preg_replace($pattern2, $replacement_str, $name), "_\n\t \r\x00\x0B-;.");

    return $res;
}

function store_ETA(DatabaseConnection $db, $eta, $percentage, $speed, $dbid)
{
    assert(is_numeric($eta) && is_numeric($percentage) && is_numeric($dbid));
    $eta = max(0, floor($eta));
    $qry = 'UPDATE queueinfo SET "ETA"=((:eta + "ETA") / 2), "progress"=:percentage, "comment"=:speed, "lastupdate"=:time WHERE "ID"=:dbid';
    $db->execute_query($qry, array(':dbid'=>$dbid, ':eta'=>$eta, ':percentage'=>$percentage, ':speed'=>$speed, ':time'=>time()));
}

function add_schedule(DatabaseConnection $db, $cmd, $stime, $repeat, $userid)
{
    assert(is_numeric($stime) && is_numeric($userid));
    if ($repeat === NULL) {
        $repeat = 0;
    }
    assert(is_numeric($repeat));

    return $db->insert_query('schedule', array('command', 'at_time', 'interval', 'userid'), array($cmd, $stime, $repeat, $userid), TRUE);
}

function set_period_rss(urdd_client $uc, DatabaseConnection $db, $id, $first_update, $period, $time, $periodselect)
{
    assert(is_numeric($time) && is_numeric($periodselect) && is_numeric($period) && is_numeric($id));
    // $time = update-time that's displayed in the newsgroup page
    $db->update_query_2('rss_urls', array('refresh_time'=>$time, 'refresh_period'=>$periodselect), '"id"=?', array($id));
    $uc->schedule(urdd_protocol::COMMAND_UPDATE_RSS, $id, $first_update, $period * 3600);
}

function set_period(urdd_client $uc, DatabaseConnection $db, $id, $first_update, $period, $time, $periodselect)
{
    assert(is_numeric($time) && is_numeric($periodselect) && is_numeric($period) && is_numeric($id));
    // $time = update-time that's displayed in the newsgroup page
    $db->update_query_2('groups', array('refresh_time'=>$time, 'refresh_period'=>$periodselect), '"ID"=?', array($id));
    $uc->schedule(urdd_protocol::COMMAND_UPDATE, $id, $first_update, $period * 3600);
}

function delete_schedule(DatabaseConnection$db, $id)
{
    assert(is_numeric($id));
    $db->delete_query('schedule', '"id" = ?', array($id));
}

function shorten_newsgroup_name($ng, $maxsize=35)
{
    assert(is_numeric($maxsize) && is_string($ng));
    $short = str_ireplace(array('alt.','binaries.', 'free.'), array('a.', 'b.', 'f.'), $ng);
    if (strlen($short) > $maxsize && $maxsize > 3) {
        $short = substr($short, 0, $maxsize - 3);
        $short .= '...';
    }

    return $short;
}

function generate_password($length = MIN_PASSWORD_LENGTH)
{
    assert(is_numeric($length));
    $password = '';
    $possible = '0123456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $p_len = strlen($possible) - 1;
    foreach (range(0, $length - 1) as $i) {
        $char = $possible[ mt_rand(0, $p_len)];
        $password .= $char;
    }

    return $password;
}

function get_set_size($name)
{
    // Detect max setsize from the subject name:
    // First check for [001/103]:
    if (preg_match("/\[([0-9]+)[\/]+([0-9]+)\]/", $name, $vars)) {
        return $vars[2];
    }

    // Ok so that didn't work, less strict: (allows "(001-103)")
    if (preg_match("/[\[(]([0-9]+)[\/of-]+([0-9]+)[\])]/", $name, $vars)) {
        return $vars[2];
    }

    // "Catch all": (no ( or ['s required, just 2 numbers with '/','\','of' or '-' in between)
    if (preg_match("/([0-9]+)[\/of\-_ ]+([0-9]+)/", $name, $vars)) {
        return $vars[2];
    }

    return 0;
}

function create_binary_id($subject, $poster)
{
    return md5($subject . ' ' . $poster);
}

function create_extset_download_name(DatabaseConnection $db, $setID)
{
    $res = $db->select_query('* FROM extsetdata WHERE "setID" = :setid', array(':setid'=>$setID));
    if (!is_array($res)) {
        return FALSE;
    }

    // Initialise and store extsetdata in $value
    $value = [];

    foreach ($res as $row) {
        $value[$row['name']] = $row['value'];
    }

    // Create a downloadname based on the type of binary:
    $downloadname = '';
    switch ($value['binarytype']) {
        case urd_extsetinfo::SETTYPE_MOVIE:
            if (isset($value['name'])) {
                $downloadname .= $value['name'];
            }
            if (isset($value['year'])) {
                $downloadname .= ' (' . $value['year'] . ')';
            }
            if (isset($value['movieformat'])) {
                $downloadname .= ' [' . $value['movieformat'] . ']';
            }
            break;
        case urd_extsetinfo::SETTYPE_TVSERIES:
            if (isset($value['name'])) {
                $downloadname .= $value['name'];
            }
            if (isset($value['episode'])) {
                $downloadname .= ' ' . $value['episode'];
            }
            if (isset($value['movieformat'])) {
                $downloadname .= ' [' . $value['movieformat'] . ']';
            }
            break;
        default:
            $downloadname = (isset($value['name'])) ? $value['name'] : 'missing name ' . $setID;
        }

    return sanitise_download_name($db, $downloadname);
}

function check_connected(urdd_client $uc)
{
    global $LN;
    if ($uc->is_connected() === FALSE) {
        throw new exception($LN['error_urddconnect']);
    }
}

function download_sets(DatabaseConnection $db, array $sets, $userid, $type)
{
    global $LN;
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    assert (in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)) && is_numeric($userid));
    $rprefs = load_config($db);
    $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);
    check_connected($uc);
    if (($username = get_username($db, $userid)) === FALSE) {
        throw new exception ($LN['error_nosuchuser'], ERR_INVALID_USERNAME);
    }

    $make_nzb = get_pref($db, 'use_auto_download_nzb', $userid, 0);
    $add_setname = get_pref($db, 'add_setname', $userid) ? 1 : 0;
    $marking = sets_marking::MARKING_ON;
    $sql_usersets = '* FROM usersetinfo WHERE "userID"=:userid AND "setID"=:setid AND "type"=:type';
    foreach ($sets as $set) {
        $size = $set['size'];
        $setid = $set['ID'];
        if (isset($set['value'])) {
            $dlname = find_name($db, $set['value']);
        } else {
            $dlname = find_name($db, $set['subject']);
        }
        if ($make_nzb) {
            $result = $uc->make_nzb();
        } else {
            $result = $uc->create_download();
        }
        if ($result === FALSE) {
            throw new exception($LN['error_createdlfailed']);
        }
        $result = $uc->decode($result);

        // Download ID:
        $dlid = $result[0];
        $dlthreads = $result[1];
        set_download_name($db, $dlid, $dlname);
        if (!$make_nzb) {
            $bettersetname = create_extset_download_name($db, $setid);
            if ($type == USERSETTYPE_RSS) {
                $feedid = $set['rss_id'];
                list($dl_dir) = get_user_dlpath($db, FALSE, $feedid, $type, $userid, $bettersetname, 'DOWNLOAD', $setid);
            } elseif ($type == USERSETTYPE_SPOT) {
                $spot_cat = $set['category'];
                list($dl_dir) = get_user_dlpath($db, FALSE, $spot_cat, $type, $userid, $bettersetname, 'DOWNLOAD', $setid); // todo
            } else {
                $groupid = $set['groupID'];
                list($dl_dir) = get_user_dlpath($db, FALSE, $groupid, $type, $userid, $bettersetname, 'DOWNLOAD', $setid);
            }
            $base_dlpath = get_dlpath($db);
            $base_dlpath = $base_dlpath . DONE_PATH . $username . DIRECTORY_SEPARATOR;
            $dl_dir = substr($dl_dir, strlen($base_dlpath));
            set_dl_dir($db, $dlid, $dl_dir, $add_setname);
        }

        if ($type == USERSETTYPE_GROUP) {
            $uc->add_set_data($dlid, $setid);
        } elseif ($type == USERSETTYPE_SPOT) {
            $uc->add_spot_data($dlid, $setid);
        } else {
            $sql = '"nzb_link" FROM rss_sets WHERE "setid" = :setid';
            $res = $db->select_query($sql, array(':setid'=>$setid));
            if ($res !== FALSE) {
                $url = $res[0]['nzb_link'];
                $uc->parse_nzb($url, $dlid);
                // mark user info
            } else {
                throw new exception($LN['error_linknotfound']);
            }
        }
        if ($make_nzb) {
            $mark_status = 'statusnzb';
        } else {
            $mark_status = 'statusread';
        }
        $res = $db->select_query($sql_usersets, array(':userid'=>$userid, ':setid'=>$setid, ':type'=>$type));
        if ($res === FALSE) {
            $db->insert_query('usersetinfo', array('setID', 'userID', $mark_status, 'type'), array($setid, $userid, $marking, $type));
        } else {
            $db->update_query_2('usersetinfo', array($mark_status=>$marking), '"userID"=:userid AND "setID"=:setid AND "type"=:type', array(':userid'=>$userid, ':setid'=>$setid, ':type'=>$type));
        }
        if (!$make_nzb) {
            set_download_size($db, $dlid, $size);
            $stat_id = add_stat_data($db, stat_actions::DOWNLOAD, 0, $userid);
            set_stat_id($db, $dlid, $stat_id);
        }

        $now = time();
        foreach ($dlthreads as $id) {
            set_start_time($db, $dlid, $now);
            usleep(250000);
            $uc->unpause($id);
        }
    }
    $uc->disconnect();
}

function get_from_array(array $arr, $idx, $default = NULL)
{
    if (is_array($idx)) {
        return isset($arr[$idx[0]][$idx[1]]) ? $arr[$idx[0]][$idx[1]] : $default;
    } else {
        return isset($arr[$idx]) ? $arr[$idx] : $default;
    }
}

function read_argv()
{
    global $argv, $LN;
    if (!isset($argv) || !is_array($argv)) {
        if (!isset($_SERVER['argv']) || !is_array($_SERVER['argv'])) {
            if (!isset ($GLOBALS['HTTP_SERVER_VARS']['argv']) || !is_array($GLOBALS['HTTP_SERVER_VARS']['argv'])) {
                throw new exception($LN['error_couldnotreadargs'], ERR_INVALID_ARGUMENT);
            }

            return $GLOBALS['HTTP_SERVER_VARS']['argv'];
        }

        return $_SERVER['argv'];
    }

    return $argv;
}

function update_category(DatabaseConnection $db, $id, $userid, $name)
{
    assert(is_numeric($userid) && is_numeric($id));
    $db->update_query_2('categories', array('name'=>$name), '"userid"=? AND "id"=?', array($userid, $id));
}

function clean_categories(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $db->delete_query('categories', '"userid"=?', array($userid));
}

function clean_userfeedinfo(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $db->delete_query('userfeedinfo', '"userid"=?', array($userid));
}

function clean_usergroupinfo(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $db->delete_query('usergroupinfo', '"userid"=?', array($userid));
}

function get_hiddenfiles($text)
{
    $list = unserialize($text);
    return array_filter($list, 'is_not_empty');
}

function is_not_empty($s)
{
    return $s != '';
}

function read_system_groups()
{
    global $LN;
    $g = @file (GROUPS_FILE, FILE_IGNORE_NEW_LINES);
    if ($g === FALSE) {
        throw new exception ($LN['error_filenotfound'] . ' ' . GROUPS_FILE, ERR_FILE_NOT_FOUND);
    }

    foreach ($g as $grp) {
        $r = explode (':', $grp);
        if ($r === FALSE) {
            continue;
        }
        $grps[] = $r[0];
    }

    return $grps;
}

function find_unique_name($base, $part, $dlname, $extension='', $is_file=FALSE)
{
    $appendix = '';
    $cntr = 1;
    do {
        $to = $base . $part . $dlname. $appendix . (($is_file !== TRUE) ? DIRECTORY_SEPARATOR : '') . $extension;
        $appendix = "_$cntr";
        $cntr++;
    } while (file_exists($to));

    return $to;
}

function test_url($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    @curl_exec($ch);

    return curl_getinfo($ch, CURLINFO_HTTP_CODE);
}

function feed_name(DatabaseConnection $db, $id)
{
    global $LN;
    assert(is_numeric($id));
    $res = $db->select_query('"name" FROM rss_urls WHERE "id"=:id', 1, array(':id'=>$id));
    if (!isset($res[0]['name'])) {
        throw new exception($LN['error_feednotfound'] . ": $id", ERR_RSS_NOT_FOUND);
    }

    return $res[0]['name'];
}

function group_expire(DatabaseConnection $db, $id)
{
    global $LN;
    assert(is_numeric($id));
    $res = $db->select_query('"expire" FROM groups WHERE "ID"=:id', 1, array(':id'=>$id));
    if (!isset($res[0]['expire'])) {
        throw new exception($LN['error_groupnotfound'] . ": $id", ERR_GROUP_NOT_FOUND);
    }

    return $res[0]['expire'];
}

function group_by_name(DatabaseConnection $db, $name)
{
    global $LN;
    $res = $db->select_query('"ID" FROM groups WHERE "name"=:name', 1, array(':name'=>$name));
    if (!isset($res[0]['ID'])) {
        throw new exception($LN['error_groupnotfound'] . " '$name'", ERR_GROUP_NOT_FOUND);
    }

    return $res[0]['ID'];
}

function get_feed_by_name(DatabaseConnection $db, $name)
{
    global $LN;
    $res = $db->select_query('"id" FROM rss_urls WHERE "name"=:name', 1, array(':name'=>$name));
    if (!isset($res[0]['id'])) {
        throw new exception($LN['error_feednotfound'] . " '$name'", ERR_RSS_NOT_FOUND);
    }

    return $res[0]['id'];
}

function get_feed_by_id(DatabaseConnection $db, $id)
{
    global $LN;
    assert(is_numeric($id));
    $res = $db->select_query('"name" FROM rss_urls WHERE "id"=:id', 1, array(':id'=>$id));
    if (!isset($res[0]['name'])) {
        throw new exception($LN['error_feednotfound'] . " '$id'", ERR_RSS_NOT_FOUND);
    }

    return $res[0]['name'];
}

function feed_category(DatabaseConnection $db, $feedid, $userid)
{
    global $LN;
    assert(is_numeric($feedid) && is_numeric($userid));
    $sql = 'categories."name" AS "name" FROM userfeedinfo LEFT JOIN categories ON userfeedinfo."category" = categories."id" AND userfeedinfo."userid" = categories."userid"' .
        ' WHERE userfeedinfo."feedid"=:feedid AND categories."userid"=:userid';
    $res = $db->select_query($sql, 1, array(':feedid'=>$feedid, ':userid'=>$userid));
    if (!isset($res[0]['name'])) {
        throw new exception($LN['error_feednotfound'] . ": $feedid", ERR_RSS_NOT_FOUND);
    }

    return $res[0]['name'];
}


function group_category(DatabaseConnection $db, $groupid, $userid)
{
    global $LN;
    assert(is_numeric($groupid) && is_numeric($userid));
    $sql = 'categories."name" AS "name" FROM usergroupinfo LEFT JOIN categories ON usergroupinfo."category" = categories."id" AND usergroupinfo."userid" = categories."userid"' .
        ' WHERE usergroupinfo."groupid"=:groupid AND categories."userid" = :userid';
    $res = $db->select_query($sql, 1, array(':groupid'=>$groupid, ':userid'=> $userid));
    if (!isset($res[0]['name'])) {
        throw new exception($LN['error_groupnotfound'] . ": $groupid", ERR_GROUP_NOT_FOUND);
    }

    return $res[0]['name'];
}

function group_name(DatabaseConnection $db, $groupid)
{
    global $LN;
    assert(is_numeric($groupid));
    $res = $db->select_query('"name" FROM groups WHERE "ID"=:id', 1, array(':id'=>$groupid));
    if (!isset($res[0]['name'])) {
        throw new exception($LN['error_groupnotfound'] . ": $groupid", ERR_GROUP_NOT_FOUND);
    }

    return $res[0]['name'];
}

function feed_subscribed(DatabaseConnection $db, $id)
{
    global $LN;
    assert(is_numeric($id));
    $res = $db->select_query('"subscribed" FROM rss_urls WHERE "id"=:id', 1, array(':id'=>$id));
    if (!isset($res[0]['subscribed'])) {
        throw new exception($LN['error_feednotfound'] . " $id", ERR_RSS_NOT_FOUND);
    }

    return $res[0]['subscribed'] == rssfeed_status::RSS_SUBSCRIBED;
}

function group_subscribed(DatabaseConnection $db, $id)
{
    global $LN;
    assert(is_numeric($id));
    $res = $db->select_query('"active" FROM groups WHERE "ID"=:id', 1, array(':id'=>$id));
    if (!isset($res[0]['active'])) {
        throw new exception($LN['error_groupnotfound'] . " $id", ERR_GROUP_NOT_FOUND);
    }

    return $res[0]['active'] == newsgroup_status::NG_SUBSCRIBED;
}

function update_feed_state(DatabaseConnection $db, $id, $state, $exp)
{
    assert(is_numeric($id) && is_numeric($exp));
    $db->update_query_2('rss_urls', array('subscribed'=>$state, 'expire'=>$exp), '"id"=?', array($id));
}

function update_group_state(DatabaseConnection $db, $id, $state, $exp, $minsetsize, $maxsetsize, $adult=NULL)
{
    assert(is_numeric($id) && is_numeric($exp));
    assert(is_numeric($minsetsize) && is_numeric($maxsetsize));
    $vals = array($state, $exp, $minsetsize, $maxsetsize);
    $cols = array('active', 'expire', 'minsetsize','maxsetsize');
    if ($adult != NULL) {
        $vals[] = $adult;
        $cols[] = 'adult';
    }
    $db->update_query('groups', $cols, $vals, '"ID"=?', array($id));
}

function get_rar_files(DatabaseConnection $db, $postid)
{
    assert(is_numeric($postid));
    $sql = '"rarfile", count("id") AS "rar_count" FROM post_files WHERE "postid"=:postid GROUP BY "rarfile"';
    $res = $db->select_query($sql, array(':postid'=>$postid));
    if ($res === FALSE) {
        throw new exception('No files to post'); // Xxx make $LN var
    }

    $rarfile_count = [];
    foreach ($res as $row) {
        $rarfile_count[$row['rarfile']] = $row['rar_count'];
    }

    return $rarfile_count;
}

function get_post_articles_count(DatabaseConnection $db, $postid)
{
    assert(is_numeric($postid));
    $sql = 'count("id") AS "total_count" FROM post_files WHERE "postid"=:postid';
    $res = $db->select_query($sql, 1, array(':postid'=>$postid));
    if ($res === FALSE || !isset($res[0]['total_count'])) {
        throw new exception('No files to post'); // Xxx make $LN var
    }

    return $res[0]['total_count'];
}

function get_post_articles_count_status(DatabaseConnection $db, $id, $status)
{
    assert(is_numeric($id));
    $query = 'count("id") AS cnt FROM post_files WHERE "postid"=? AND "status"=?';
    $res = $db->select_query($query, 1, array($id, $status));

    return $res[0]['cnt'];
}

function get_download_articles_count_status(DatabaseConnection $db, $dlid, $status)
{
    assert(is_numeric($dlid));
    $query = 'count("ID") AS cnt FROM downloadarticles WHERE "downloadID"= :dlid AND "status"= :status';
    $res = $db->select_query($query, 1, array('dlid'=> $dlid, ':status'=>$status));

    return $res[0]['cnt'];
}

function get_download_articles_count(DatabaseConnection $db, $dlid)
{
    assert(is_numeric($dlid));
    $query = 'count("ID") AS cnt FROM downloadarticles WHERE "downloadID"=:dlid';
    $res = $db->select_query($query, 1, array(':dlid'=> $dlid));

    return $res[0]['cnt'];
}

function update_post_articles(DatabaseConnection $db, $ready_status, $id, $dl_status)
{
    assert(is_numeric($id));
    $db->update_query_2('post_files', array('status'=>$ready_status), '"postid"=? AND "status"=?', array($id, $dl_status));
}

function update_dlinfo_comment(DatabaseConnection $db, $id, $comment)
{
    assert(is_numeric($id));
    $db->update_query_2('downloadinfo', array('comment'=>$comment), '"ID"=?', array($id));
}

function update_dlinfo_firstrun(DatabaseConnection $db, $id, $first_run)
{
    assert(is_numeric($id));
    $db->update_query_2('downloadinfo', array('first_run'=>(int)$first_run), '"ID"=?', array($id));
}

function update_postinfo_status(DatabaseConnection $db, $status, $id, $oldstatus=NULL)
{
    assert(is_numeric($id));
    $query = '';
    $input_arr = array($id);
    if ($oldstatus !== NULL) {
        $input_arr[] = $oldstatus;
        $query .= ' AND "status"=?';
    }
    $db->update_query_2('postinfo', array('status'=>$status), '"id"=? ' . $query, $input_arr);
}

function update_postinfo_size(DatabaseConnection $db, $id, $size)
{
    assert(is_numeric($id) && is_numeric($size));
    $db->update_query_2('postinfo', array('size'=>$size), '"id"=?', array($id));
}

function update_dlinfo_status(DatabaseConnection $db, $status, $id, $oldstatus=NULL)
{
    assert(is_numeric($id));
    $query = '';
    $input_arr = array($id);
    if ($oldstatus !== NULL) {
        $input_arr[] = $oldstatus;
        $query .= ' AND "status"=?';
    }
    $db->update_query_2('downloadinfo', array('status'=>$status), '"ID"=? ' . $query, $input_arr);
}

function set_download_password(DatabaseConnection $db, $id, $password)
{
    assert(is_numeric($id));
    $db->update_query_2('downloadinfo', array('password'=>$password), '"ID"=?', array($id));
}

function get_download_password(DatabaseConnection $db, $id)
{
    global $LN;
    assert(is_numeric($id));
    $query = '"password" FROM downloadinfo WHERE "ID"=:id';
    $res = $db->select_query($query, 1, array(':id'=>$id));
    if (!isset($res[0]['password'])) {
        throw new exception ($LN['error_downloadnotfound'], ERR_DOWNLOAD_NOT_FOUND);
    }

    return $res[0]['password'];
}

function get_download_destination(DatabaseConnection $db, $id)
{
    global $LN;
    assert(is_numeric($id));
    $res = $db->select_query('"destination" FROM downloadinfo WHERE "ID"=:id', 1, array(':id'=>$id));
    if (!isset($res[0]['destination'])) {
        throw new exception($LN['error_downloadnotfound'], ERR_DOWNLOAD_NOT_FOUND);
    }

    return $res[0]['destination'];
}

function delete_download(DatabaseConnection $db, $id)
{
    assert(is_numeric($id));
    $db->delete_query('downloadinfo', '"ID"=?', array($id));
}

function set_download_par_files(DatabaseConnection $db, $id, $download_par_files)
{
    assert(is_numeric($id));
    $download_par_files = $download_par_files ? 1 : 0;
    $db->update_query_2('downloadinfo', array('download_par'=>$download_par_files), '"ID"=?', array($id));
}

function get_download_par_files(DatabaseConnection $db, $id)
{
    assert(is_numeric($id));

    $query = '"download_par" FROM downloadinfo WHERE "ID"=:id';
    $res = $db->select_query($query, 1, array(':id'=>$id));
    if (!isset($res[0]['download_par'])) {
        global $LN;
        throw new exception ($LN['error_downloadnotfound'], ERR_DOWNLOAD_NOT_FOUND);
    }

    return $res[0]['download_par'];
}

function get_download_name(DatabaseConnection $db, $id)
{
    assert(is_numeric($id));

    $query = '"name" FROM downloadinfo WHERE "ID"=:id';
    $res = $db->select_query($query, 1, array(':id'=>$id));
    if (!isset($res[0]['name'])) {
        global $LN;
        throw new exception ($LN['error_downloadnotfound'], ERR_DOWNLOAD_NOT_FOUND);
    }

    return $res[0]['name'];
}

function get_download_progress(DatabaseConnection $db, $dlid)
{
    $sql = 'max("progress") AS "theprogress" FROM queueinfo WHERE "description" IN (?, ?)';
    $res = $db->select_query($sql, array(get_command(urdd_protocol::COMMAND_DOWNLOAD_ACTION) . " $dlid", get_command(urdd_protocol::COMMAND_DOWNLOAD) . " $dlid"));

    return isset($res[0]['theprogress']) ? ($res[0]['theprogress']): FALSE;
}

function get_download_info(DatabaseConnection $db, $id)
{
    assert(is_numeric($id));
    $res = $db->select_query('* FROM downloadinfo WHERE "ID"=:id', 1, array(':id'=>$id));
    if (!isset($res[0])) {
        global $LN;
        throw new exception ($LN['error_downloadnotfound'], ERR_DOWNLOAD_NOT_FOUND);
    }

    return $res[0];
}

function set_download_destination(DatabaseConnection $db, $id, $dir)
{
    assert(is_numeric($id));
    $db->update_query_2('downloadinfo', array('destination'=>$dir), '"ID"=?', array($id));
}

function update_batch(DatabaseConnection $db, array &$batch, $status = NULL)
{
    // $status = default value in case no specific value is set.

    foreach ($batch as $row) {
        $id = $row['ID'];
        if ($status === NULL AND !isset($row['dlstatus'])) {
            write_log('No idea what to set status to (update_batch)', LOG_ERR);
            throw new exception ('Need status for update_batch');
        }

        if (isset($row['dlstatus'])) {
            $thisstatus = $row['dlstatus'];
        } else {
            // Make sure the variable is also updated:
            $row['dlstatus'] = $status;
            $thisstatus = $status;
        }
        $db->update_query_2('downloadarticles', array('status'=>$thisstatus), '"ID"=?', array($id));
    }
}

function set_password(DatabaseConnection $db, $userid, $password, $hashed = FALSE)
{
    assert(is_numeric($userid));
    $salt = generate_password(8);
    if ($hashed === FALSE) {
        $hashpw = hash('sha256', $salt . $password . $salt);
    } else {
        $hashpw = $password;
    }
    $db->update_query_2('users', array('pass'=>$hashpw, 'salt'=>$salt, 'failed_login_time'=>0, 'failed_login_count'=>0), '"ID"=?', array($userid));
}

function GetAV(array $array, $key)
{
    return (isset($array[$key])) ? $array[$key] : '';
}

function set_download_name(DatabaseConnection $db, $dlid, $dlname)
{
    assert(is_numeric($dlid));
    $max_dl_name = get_config($db, 'max_dl_name');
    if (!is_numeric($max_dl_name) || $max_dl_name < 3) {
        $max_dl_name = 10;
    }
    $dlname = substr($dlname, 0, $max_dl_name); // DL names are no longer than X characters when auto-generated
    $db->update_query_2('downloadinfo', array('name'=>$dlname), '"ID"=?', array($dlid));
}

function get_dl_dir(DatabaseConnection $db, $dlid)
{
    assert(is_numeric($dlid));
    $sql = '"dl_dir", "add_setname" FROM downloadinfo WHERE "ID"=:id';
    $res = $db->select_query($sql, 1, array(':id'=>$dlid));

    return (!isset($res[0])) ? FALSE : array($res[0]['dl_dir'], $res[0]['add_setname']);
}

function set_dl_dir(DatabaseConnection $db, $dlid, $dl_dir, $add_setname)
{
    assert(is_numeric($dlid));
    add_dir_separator($dl_dir);
    $db->update_query_2('downloadinfo', array('dl_dir'=>$dl_dir, 'add_setname'=>$add_setname), '"ID"=?', array($dlid));
}

function get_binary_size(DatabaseConnection $db, $pbin_id, $pgroup_id)
{
    assert(is_numeric($pgroup_id));
    $sql = "\"bytes\" FROM binaries_$pgroup_id WHERE \"binaryID\" = :id";
    $res = $db->select_query($sql, array(':id'=>$pbin_id));
    if (!isset($res[0]['bytes'])) {
        throw new exception($LN['error_binariesnotfound']);
    }
    $size = $res[0]['bytes'];

    return $size;
}

function add_download_size(DatabaseConnection $db, $dlid, $totalsize)
{
    assert(is_numeric($dlid) && is_numeric($totalsize));
    $sql = 'UPDATE downloadinfo SET "size" = "size" + :totalsize WHERE "ID" = :dlid';
    $db->execute_query($sql, array(':dlid'=>$dlid, ':totalsize'=>$totalsize));
}

function set_download_size(DatabaseConnection $db, $dlid, $totalsize)
{
    assert(is_numeric($dlid) && is_numeric($totalsize));
    $db->update_query_2('downloadinfo', array('size'=>$totalsize), '"ID"=?', array($dlid));
}

function get_start_time(DatabaseConnection $db, $dlid)
{
    assert(is_numeric($dlid));
    $res = $db->select_query('"start_time" FROM downloadinfo WHERE "ID"=:id', 1, array(':id'=>$dlid));

    return (!isset($res[0]['start_time'])) ? FALSE : $res[0]['start_time'];
}

function set_start_time(DatabaseConnection $db, $dlid, $start_time)
{
    assert(is_numeric($dlid) && is_numeric($start_time));
    $db->update_query_2('downloadinfo', array('start_time'=>$start_time), '"ID"=?', array($dlid));
}

function get_dlinfo_status(DatabaseConnection $db, $dlid)
{
    global $LN;
    assert(is_numeric($dlid));
    $res = $db->select_query('"status" FROM downloadinfo WHERE "ID"=:id', 1, array(':id'=>$dlid));
    if (!isset($res[0])) {
        throw new exception ($LN['error_downloadnotfound'] . ": $dlid", ERR_DOWNLOAD_NOT_FOUND);
    }

    return $res[0]['status'];
}

function update_dlarticle_status(DatabaseConnection $db, $id, $status, $oldstatus=NULL, $operator=NULL)
{
    assert(is_numeric($id));
    $input_arr = array($id);
    $sql = '';
    if ($oldstatus != NULL) {
        $input_arr[] = $oldstatus;
        if ($operator === NULL) {
            $operator = '=';
        }
        $sql .= " AND status $operator ?";
    }
    $db->update_query_2('downloadarticles', array('status'=>$status), '"downloadID"=? ' . $sql, $input_arr);
}


function get_active_groups(DatabaseConnection $db)
{
    $res = $db->select_query('"ID" FROM groups WHERE "active"=?', array(newsgroup_status::NG_SUBSCRIBED));
    if (!isset($res[0])) {
        return FALSE;
    }
    $groups = array ();
    foreach ($res as $arr) {
        $groups[] = $arr['ID'];
    }

    return $groups;
}

function get_active_feeds(DatabaseConnection $db)
{
    $res = $db->select_query('"id" FROM rss_urls WHERE "subscribed"=?', array(rssfeed_status::RSS_SUBSCRIBED));
    if (!isset($res[0])) {
        return FALSE;
    }
    $feeds = array ();
    foreach ($res as $arr) {
        $feeds[] = $arr['id'];
    }

    return $feeds;
}

function get_all_group_by_id(DatabaseConnection $db, $groupid)
{
    global $LN;
    assert(is_numeric($groupid));
    $res = $db->select_query('"name" FROM groups WHERE "ID"=?', 1, array($groupid));

    if (!isset($res[0]['name'])) {
        throw new exception($LN['error_groupnotfound'] . ": $groupid", ERR_GROUP_NOT_FOUND);
    }

    return strtolower($res[0]['name']);
}

function get_group_by_id(DatabaseConnection $db, $groupid)
{
    global $LN;
    assert(is_numeric($groupid));
    $res = $db->select_query('"name" FROM groups WHERE "active"=? AND "ID"=?', 1, array(newsgroup_status::NG_SUBSCRIBED, $groupid));

    if (!isset($res[0]['name'])) {
        throw new exception($LN['error_groupnotfound'] . ": $groupid", ERR_GROUP_NOT_FOUND);
    }

    return strtolower($res[0]['name']);
}

function get_all_group_by_name(DatabaseConnection $db, $name)
{
    global $LN;
    assert(is_string($name));

    $search_type = $db->get_pattern_search_command('LIKE');
    $res = $db->select_query("\"ID\" FROM groups WHERE \"name\" $search_type ?", 1, array($name));

    if (!isset($res[0]['ID'])) {
        throw new exception($LN['error_groupnotfound']. ": $name", ERR_GROUP_NOT_FOUND);
    }

    return $res[0]['ID'];
}

function get_group_by_name(DatabaseConnection $db, $name)
{
    global $LN;
    assert(is_string($name));

    $search_type = $db->get_pattern_search_command('LIKE');
    $res = $db->select_query("\"ID\" FROM groups WHERE \"active\"=? AND \"name\" $search_type ?", 1, array(newsgroup_status::NG_SUBSCRIBED, $name));

    if (!isset($res[0]['ID'])) {
        throw new exception($LN['error_groupnotfound']. ": $name", ERR_GROUP_NOT_FOUND);
    }

    return $res[0]['ID'];
}

function get_group_info(DatabaseConnection $db, $groupid)
{
    global $LN;
    assert(is_numeric($groupid));
    $res = $db->select_query('* FROM groups WHERE "active"=? AND "ID"=?', 1, array(newsgroup_status::NG_SUBSCRIBED, $groupid));

    if (!isset($res[0])) {
        throw new exception($LN['error_groupnotfound']. ": $groupid", ERR_GROUP_NOT_FOUND);
    }

    return $res[0];
}

function set_feed_expire(DatabaseConnection $db, $id, $exp)
{
    assert(is_numeric($id) && is_numeric($exp));
    $db->update_query_2('rss_urls', array('expire'=>$exp), '"id"=?', array($id));
}

function set_group_maxsetsize(DatabaseConnection $db, $id, $maxsetsize)
{
    assert(is_numeric($id) && is_numeric($maxsetsize));
    $db->update_query_2('groups', array('maxsetsize'=>$maxsetsize), '"ID"=?', array($id));
}

function set_group_minsetsize(DatabaseConnection $db, $id, $minsetsize)
{
    assert(is_numeric($id) && is_numeric($minsetsize));
    $db->update_query_2('groups', array('minsetsize'=>$minsetsize), '"ID"=?', array($id));
}

function set_group_adult(DatabaseConnection $db, $id, $adult)
{
    assert(is_numeric($id) && is_numeric($adult));
    $db->update_query_2('groups', array('adult'=>$adult), '"ID"=?', array($id));
}

function set_spots_expire(DatabaseConnection $db, $exp)
{
    assert(is_numeric($exp));
    $spots_reports_group_name = get_config($db, 'spots_reports_group');
    if ($spots_reports_group_name != '') {
        $id = group_by_name($db, $spots_reports_group_name);
        set_group_expire($db, $id, $exp);
    }
    $spots_comments_group_name = get_config($db, 'spots_comments_group');
    if ($spots_comments_group_name != '') {
        $id = group_by_name($db, $spots_comments_group_name);
        set_group_expire($db, $id, $exp);
    }
    $spots_group_name = get_config($db, 'spots_group');
    if ($spots_group_name != '') {
        $id = group_by_name($db, $spots_group_name);
        set_group_expire($db, $id, $exp);
    }
}

function set_group_expire(DatabaseConnection $db, $id, $exp)
{
    assert(is_numeric($id) && is_numeric($exp));
    $db->update_query_2('groups', array('expire'=>$exp), '"ID"=?', array($id));
}

function add_stat_data(DatabaseConnection $db, $action, $value, $userid)
{
    global $LN;
    if (!is_numeric($userid)) {
        assert(is_string($userid));
        $rv = $db->select_query('"ID" FROM users WHERE "name"=:name', array(':name'=>$userid));
        if (!isset($rv[0]['ID'])) {
            throw new exception ($LN['error_nosuchuser'] . ": $userid");
        }
        $userid = $rv[0]['ID'];
    }
    if (strtolower($value) == 'all') {
        $value = 0;
    }
    assert(is_numeric($value) && is_numeric($action) && is_numeric($userid));
    $timestamp = date('Y-n-j H:i:s', time());

    return $db->insert_query('stats', array('userid', 'action', 'value', 'timestamp'), array($userid, $action, $value, $timestamp), TRUE);
}

function check_last_lock(DatabaseConnection $db, $dlid) // return TRUE if locked by 1, false otherwise
{
    global $LN;
    assert (is_numeric($dlid));
    $res = $db->select_query('"lock" FROM downloadinfo WHERE "ID"=?', 1, array($dlid));
    if (!isset($res[0]['lock'])) {
        throw new exception ($LN['error_downloadnotfound'] . ": $dlid", ERR_DOWNLOAD_NOT_FOUND);
    }

    return ($res[0]['lock'] == 1 ? TRUE : FALSE);
}

function check_dl_lock(DatabaseConnection $db, $dlid) // return TRUE if not locked, false otherwise
{
    global $LN;
    assert (is_numeric($dlid));
    $res = $db->select_query('"lock" FROM downloadinfo WHERE "ID"=:dlid', 1, array(':dlid'=>$dlid));
    if (!isset($res[0]['lock'])) {
        throw new exception ($LN['error_downloadnotfound'] . ": $dlid", ERR_DOWNLOAD_NOT_FOUND);
    }

    return (($res[0]['lock'] != 0) ? FALSE : TRUE);
}

function reset_dl_lock(DatabaseConnection $db, $dlid)
{
    assert (is_numeric($dlid));
    $db->lock(array('downloadinfo' => 'write'));
    try {
        $db->update_query_2('downloadinfo', array('lock'=>0), '"ID"=?', array($dlid));
        $db->unlock();
    } catch (exception $e) {
        $db->unlock();
        throw $e;
    }
}

function inc_dl_lock(DatabaseConnection $db, $dlid)
{
    assert (is_numeric($dlid));
    $db->lock(array('downloadinfo' => 'write'));
    try {
        $db->execute_query('UPDATE downloadinfo SET "lock" = "lock" + 1 WHERE "ID"=?', array($dlid));
        $db->unlock();
    } catch (exception $e) {
        $db->unlock();
        throw $e;
    }
}

function dec_dl_lock(DatabaseConnection $db, $dlid)
{
    assert (is_numeric($dlid));
    $db->lock(array('downloadinfo' => 'write'));
    try {
        $db->execute_query('UPDATE downloadinfo SET "lock" = "lock" - 1 WHERE "ID"=? AND "lock" > 0', array($dlid));
        $db->unlock();
    } catch (exception $e) {
        $db->unlock();
        throw $e;
    }
}

function set_all_spots_blacklist(DatabaseConnection $db, $blacklist, $userid)
{
    foreach ($blacklist as $b) {
        add_to_blacklist($db, $b['spotter_id'], $userid, urd_user_rights::is_admin($db, $userid), $b['source'], $b['status']);
    }
}

function set_all_spots_whitelist(DatabaseConnection $db, $whitelist, $userid)
{
    foreach ($whitelist as $b) {
        add_to_whitelist($db, $b['spotter_id'], $userid, urd_user_rights::is_admin($db, $userid), $b['source'], $b['status']);
    }
}

function get_all_spots_blacklist(DatabaseConnection $db, $userid=NULL)
{
    $sql = '* FROM spot_blacklist';
    $inputarr = [];
    if ($userid !== NULL) {
        assert (is_numeric($userid));
        $sql .= ' WHERE "userid"=:userid ';
        $inputarr[':userid'] = $userid;
    }
    
    $sql .= ' ORDER BY "spotter_id" ASC';
    $res = $db->select_query($sql, $inputarr);
    if ($res === FALSE) {
        throw new exception('Cannot find any spotters on the blacklist');
    }

    return $res;
}

function get_all_spots_whitelist(DatabaseConnection $db, $userid=NULL)
{
    $sql = '* FROM spot_whitelist';
    $inputarr = [];
    if ($userid !== NULL) {
        assert (is_numeric($userid));
        $inputarr[':userid'] = $userid;
        $sql .= ' WHERE "userid" = :userid ';
    }
    
    $sql .= ' ORDER BY "spotter_id" ASC';
    $res = $db->select_query($sql, $inputarr);
    if ($res === FALSE) {
        throw new exception('Cannot find any spotters on the whitelist');
    }

    return $res;
}

function ch_group($fn, $group)
{
    $fgroup = filegroup($fn);
    if ($fgroup === FALSE) {
        write_log("Cannot get group of $fn", LOG_ERR);

        return FALSE;
    }
    $fgroup = posix_getgrgid($fgroup);
    if ($fgroup['name'] != $group) { // we don't have to change it if it is already set :D
        $rv = @chgrp($fn, $group);
        if ($rv === FALSE) {
            write_log("Cannot set groups, probably insufficient right to chgrp() $fn to $group", LOG_ERR);

            return FALSE;
        }
    }

    return TRUE;
}

function get_rss_info(DatabaseConnection $db, $id, $subscribed=TRUE)
{
    global $LN;
    assert(is_numeric($id) && is_bool($subscribed));
    $qry = '* FROM rss_urls WHERE "id" = :id';
    $inputarr = array(':id'=> $id);
    if ($subscribed) {
        $qry .= ' AND "subscribed" = :subscribed';
        $inputarr[':subscribed'] = rssfeed_status::RSS_SUBSCRIBED;
    }
    $res = $db->select_query($qry, 1, $inputarr);
    if (!isset($res[0])) {
        throw new exception($LN['error_feednotfound'], ERR_RSS_NOT_FOUND);
    }

    return $res[0];
}

function in_setdata($setID, $type, array $setdata)
{
    foreach ($setdata as $set) {
        if ($set['setid'] == $setID && $set['type'] == $type) {
            return TRUE;
        }
    }

    return FALSE;
}

function validate_url($url, $strict=TRUE)
{
    assert(is_string($url));
    if ($strict) {
        $res = preg_match ('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/[a-z0-9.@\-_:/~%&;\[\]]*)?/$|i', $url);
    } else {
        $res = preg_match ('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/[a-z0-9.@\-_:/?~%&;\[\]]*)?$|i', $url);
    }

    return $res == 1;
}

function delete_rss_feed(DatabaseConnection $db, $id)
{
    assert(is_numeric($id));
    $rss_info = get_rss_info($db, $id, FALSE);
    $type = USERSETTYPE_RSS;
    $db->delete_query('usersetinfo', '"setID" IN (SELECT "setid" FROM rss_sets WHERE "rss_id"=?) AND "type"=?', array($id, $type));
    $db->delete_query('extsetdata', '"setID" IN (SELECT "setid" FROM rss_sets WHERE "rss_id"=?) AND "type"=?', array($id, $type));
    $db->delete_query('rss_urls', '"id"=?', array($id));
    $db->delete_query('rss_sets', '"rss_id"=?', array($id));
    fetch_rss::delete_cache_entry($rss_info['url'], get_magpie_cache_dir($db));
}

function real_mime_content_type(DatabaseConnection $db, $file, $force_file=FALSE)
{
    if (function_exists('mime_content_type') && $force_file === FALSE) {
        return mime_content_type($file);
    } else {
        $file_prog = get_config($db, 'file_path');
        $file = my_escapeshellarg($file);
        $mime_type = @exec("$file_prog -bi $file 2> /dev/null", $dummy, $rv);
        if ($rv != 0) {
            return 'text/html';
        } else {
            return trim($mime_type);
        }
    }
}

function get_stat_id(DatabaseConnection $db, $dlid, $is_post=FALSE)
{
    assert(is_numeric($dlid));
    list($table, $idrow) = ($is_post ? array('postinfo', 'id') : array('downloadinfo', 'ID'));
    $sql = "\"stat_id\" FROM $table WHERE \"$idrow\"=:dlid";
    $res = $db->select_query($sql, 1, array(':dlid' => $dlid));

    return (!isset($res[0]['stat_id'])) ? FALSE : $res[0]['stat_id'];
}

function set_stat_id(DatabaseConnection $db, $dlid, $stat_id, $is_post=FALSE)
{
    assert(is_numeric($dlid) && is_numeric($stat_id));
    list($table, $idrow) = ($is_post ? array('postinfo', 'id') : array('downloadinfo', 'ID'));
    $res = $db->update_query_2($table, array('stat_id'=>$stat_id), "\"$idrow\"=?", array($dlid));

    return ($res === FALSE) ? FALSE : TRUE;
}

function update_dlstats(DatabaseConnection $db, $stat_id, $value)
{
    assert(is_numeric($stat_id) && is_numeric($value));
    if ($stat_id == 0) {
        return;
    }
    $sql = 'UPDATE stats SET "value"= "value" + :value WHERE "id" = :statid';
    $db->execute_query($sql, array(':statid' => $stat_id, ':value'=>$value));
}

function get_groupid_for_set(DatabaseConnection $db, $setid)
{
    global $LN;
    $res = $db->select_query('"groupID" FROM setdata WHERE "ID"=:id', 1, array(':id'=>$setid));
    if (!isset($res[0]['groupID'])) {
        throw new exception($LN['error_groupnotfound'], ERR_GROUP_NOT_FOUND);
    }

    return $res[0]['groupID'];
}

function get_feedid_for_set(DatabaseConnection $db, $setid)
{
    global $LN;
    $res = $db->select_query('"rss_id" FROM rss_sets WHERE "setid"=:id', 1, array(':id'=>$setid));
    if (!isset($res[0]['rss_id'])) {
        throw new exception($LN['error_groupnotfound'], ERR_RSS_NOT_FOUND);
    }

    return $res[0]['rss_id'];
}

function store_merge_sets_data(DatabaseConnection $db, $new_setID, $old_setID, $type, $commit = ESI_COMMITTED)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    // Does info exist?
    $res = $db->select_query('* FROM merged_sets WHERE "old_setid"=? AND "type"=?', 1, array($old_setID, $type));
    // Check if the stored field exists
    if ($res === FALSE) {
        $db->insert_query('merged_sets', array ('new_setid', 'old_setid', 'type', 'committed'), array($new_setID, $old_setID, $type, $commit));
    }
}

function start_urdd($web=FALSE)
{
    global $db;
    $prefs = load_config($db);

    $trickle = my_escapeshellcmd(get_from_array($prefs, 'trickle_path', ''));
    $urdd = my_escapeshellcmd(get_from_array($prefs, 'urdd_path', ''));
    $urdd_pars = '-U ' . my_escapeshellarg(get_from_array($prefs,'urdd_pars',''), FALSE);
    if ($urdd == '') {
        write_log('Urdd.sh not found', LOG_ERR);
        if ($web === FALSE) {
            exit(-1);
        } else {
            return_result(array('error' => 'Urdd.sh not found'));
        }
    }
    $bandwidth = get_from_array($prefs, 'maxdl', '');
    // Command to a variable, use full path
    $shaping = (get_from_array($prefs, 'shaping', 0) == 1);
    if (!is_numeric($bandwidth) || $bandwidth <= 0 ) {
        write_log('Invalid value for bandwidth', LOG_NOTICE);
        $shaping = FALSE;
    }
    if ($trickle == '' || $trickle == 'off') {
        $shaping = FALSE;
    }

    if ($shaping === FALSE) {
        $cmd = "$urdd -D $urdd_pars";
    } else {
        $cmd = "$trickle -d $bandwidth $urdd -D $urdd_pars";
    }
    write_log("Starting URDD: $cmd", LOG_NOTICE);
    exec($cmd);
    // Give it some time to start before we check if it's running etc:
    usleep(500000);
    if ($web !== FALSE) {
        return_result();
    }

}


function get_all_feeds(DatabaseConnection $db)
{
    global $LN;
    $sql = '* FROM rss_urls';
    $rv = $db->select_query($sql);
    if ($rv === FALSE) {
        throw new exception($LN['error_nofeedsfound']);
    }

    return $rv;
}

function get_all_active_groups(DatabaseConnection $db)
{
    global $LN;
    $rv = $db->select_query('* FROM groups WHERE "active"=?', array(newsgroup_status::NG_SUBSCRIBED));
    if ($rv === FALSE) {
        throw new exception($LN['error_nofeedsfound']);
    }

    return $rv;
}

function gmp_min($a, $b)
{
    return (gmp_cmp($a, $b) < 0) ? $a : $b;
}

function gmp_max($a, $b)
{
    return (gmp_cmp($a, $b) > 0) ? $a : $b;
}

function rss_url_name_exists(DatabaseConnection $db, $name, $id=NULL)
{
    $inputarr= array(':name'=>$name);
    $sql = 'count("id") AS cnt FROM rss_urls WHERE "name"=:name';
    if ($id !== NULL) {
        $sql .= ' AND "id"!=:id';
        $inputarr[':id'] = $id;

    }
    $res = $db->select_query($sql, $inputarr);

    return (!isset($res[0]['cnt']) || $res[0]['cnt'] == 0) ? FALSE : TRUE;
}

function update_rss_url(DatabaseConnection $db, $name, $url, $subscribed, $expire, $username, $password, $id, $adult)
{
    global $LN;
    assert(is_numeric($expire) && is_numeric($id));
    $max_exp = get_config($db, 'maxexpire');
    if ($expire > $max_exp || $expire < 1) {
        throw new exception($LN['error_bogusexptime']);
    }
    if (rss_url_name_exists($db, $name, $id)) {
        throw new exception($LN['error_feedexists']);
    }
    $rv = validate_url($url);
    if ($rv != '') {
        throw new exception($rv['msg']);
    }
    $cols = array('name', 'url', 'subscribed', 'adult', 'expire', 'username', 'password');
    $vals = array($name, $url, $subscribed, $adult, $expire, $username, $password);
    $db->update_query('rss_urls', $cols, $vals, '"id"=?', array($id));
}

function add_rss_url(DatabaseConnection $db, $name, $url, $subscribed, $expire, $username, $password, $adult)
{
    global $LN;
    assert(is_numeric($expire));
    $max_exp = get_config($db, 'maxexpire');
    if ($expire > $max_exp || $expire < 1) {
        throw new exception($LN['error_bogusexptime']);
    }

    if (rss_url_name_exists($db, $name)) {
        throw new exception('RSS Feed name exists');
    }
    $rv = validate_url($url);
    if ($rv != '') {
        throw new exception($rv['msg']);
    }
    try {
        $db->insert_query('rss_urls', array('name', 'url', 'subscribed', 'expire', 'username', 'password', 'adult'),
            array($name, $url, $subscribed, $expire, $username, $password, $adult));
    } catch (exception $e) {
        throw new exception('Insert failed: ' . $e->getmessage());
    }
    $sql = '"id" FROM rss_urls WHERE "name"=:name';
    try {
        $res = $db->select_query($sql,1, array(':name'=>$name));
        if (isset($res[0]['id'])) {
            return $res[0]['id'];
        } else {
            throw new exception($LN['error_feednotfound']);
        }
    } catch (exception $e) {
        throw new exception($e->getMessage());
    }
}

function clear_all_spots_blacklist($db, $userid=NULL)
{
    $where = '';
    $input_arr = [];
    if ($userid !== NULL) {
        assert(is_numeric($userid));
        $where = '"userid"=?';
        $input_arr[] = $userid;
    }
    $db->delete_query('spot_blacklist', $where, $input_arr);
}

function clear_all_spots_whitelist($db, $userid=NULL)
{
    $where = '';
    $input_arr = [];
    if ($userid !== NULL) {
        assert(is_numeric($userid));
        $where = '"userid"=?';
        $input_arr[] = $userid;
    }
    $db->delete_query('spot_whitelist', $where, $input_arr);

}
function clear_all_feeds(DatabaseConnection $db, $userid)
{
    assert (is_numeric($userid));
    $uprefs = load_config($db);
    $uc = new urdd_client($db, $uprefs['urdd_host'], $uprefs['urdd_port'], $userid);
    try {
        $feedinfo = get_all_feeds($db);
        foreach ($feedinfo as $feed) {
            $id = $feed['id'];
            if ($feed['subscribed'] == rssfeed_status::RSS_SUBSCRIBED) {
                $uc->cancel(get_command(urdd_protocol::COMMAND_UPDATE_RSS) . " $id");
                $uc->cancel(get_command(urdd_protocol::COMMAND_EXPIRE_RSS) . " $id");
                remove_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE_RSS);
                $uc->unsubscribe($id, USERSETTYPE_RSS);
            }
            delete_rss_feed($db, $id);
        }
    } catch (exception $e) {
    }
    $uc->disconnect();
}

function clear_all_groups(DatabaseConnection $db, $userid)
{
    assert (is_numeric($userid));
    $uprefs = load_config($db);
    $uc = new urdd_client($db, $uprefs['urdd_host'], $uprefs['urdd_port'], $userid);
    try {
        $groupinfo = get_all_active_groups($db);
        foreach ($groupinfo as $gr) {
            $id = $gr['ID'];
            $uc->cancel(get_command(urdd_protocol::COMMAND_UPDATE) . " $id");
            $uc->cancel(get_command(urdd_protocol::COMMAND_EXPIRE) . " $id");
            $uc->cancel(get_command(urdd_protocol::COMMAND_GENSETS) . " $id");
            remove_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE);
            $uc->unsubscribe($id, USERSETTYPE_GROUP);
        }
    } catch (exception $e) {
    }
    $uc->disconnect();
}

function set_all_feeds(DatabaseConnection $db, array $settings, $userid)
{
    assert (is_numeric($userid));
    global $periods;
    $uprefs = load_config($db);
    $uc = new urdd_client($db, $uprefs['urdd_host'], $uprefs['urdd_port'], $userid);
    foreach ($settings as $set) {
        $name = $set['name'];
        $url = $set['url'];
        $expire = $set['expire'];
        $password = $set['password'];
        $username = $set['username'];
        $subscribed = $set['subscribed'];
        $adult = $set['adult'];
        add_rss_url($db, $name, $url, 0, $expire, $name, $username, $password, $adult);
        $rss_id = get_feed_by_name($db, $set['name']);
        if ($subscribed) {
            $uc->subscribe_rss($rss_id, $set['expire']);
            $refresh_time = $set['refresh_time'];
            $refresh_period = $set['refresh_period'];
            if ($refresh_period > 0) {
                $period = $periods->get($refresh_period);
                if ($period !== NULL) {
                    $time = (floor($refresh_time / 60)) . ':' . ($refresh_time % 60);
                    set_period_rss($uc, $db, $rss_id, $time, $period->get_interval(), $refresh_time, $refresh_period);
                }
            }
        }
    }
    $uc->disconnect();
}

function set_all_groups(DatabaseConnection $db, array $settings, $userid)
{
    assert (is_numeric($userid));
    global $periods;
    $uprefs = load_config($db);
    $uc = new urdd_client($db, $uprefs['urdd_host'], $uprefs['urdd_port'], $userid);
    foreach ($settings as $set) {
        $groupid = group_by_name($db, $set['groupname']);
        $uc->subscribe($groupid, $set['expire'], $set['minsetsize'], (isset($set['maxsetsize']) ? $set['maxsetsize'] : 0), (isset($set['adult']) ? $set['adult'] : 0));
        $refresh_time = $set['refresh_time'];
        $refresh_period = $set['refresh_period'];
        if ($refresh_period > 0) {
            $period = $periods->get($refresh_period);
            if ($period !== NULL) {
                $time = (floor($refresh_time / 60)) . ':' . ($refresh_time % 60);
                set_period($uc, $db, $groupid, $time, $period->get_interval(), $refresh_time, $refresh_period);
            }
        }
    }
    $uc->disconnect();
}

function perms_to_string($perms)
{
        assert(is_numeric($perms));
        if (($perms & 0xC000) == 0xC000) { // Socket
                $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) { // Symbolic Link
                $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) { // Regular
                $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) { // Block special
                $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) { // Directory
                $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) { // Character special
                $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) { // FIFO pipe
                $info = 'p';
        } else { // Unknown
                $info = 'u';
        }

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));

        return $info;
}

function deyenc($msg)
{
    // db is global, because when it is used, there is no db parameter. Ugly? Yes!
    global $LN, $db;
    for ($i = 0; $i < 10; $i++) {
        if (strstr($msg[$i], '=ybegin')) {
            // yydecode doesn't like size=-1 nor a missing name, so we need to fix that first
            // usually it is the first line anyway.
            $msg[$i] = preg_replace('/size=-?[0-9]+/', 'size=1000000', $msg[$i], 1); // tricky ... size needs to be big enough to hold the data as yydecode will truncate it
            $msg[$i] .= ' name=temp'; // just a stupid basic name... anything goes really
            break;
        }
    }
    if ($i == 10) { // ok something went terribly wrong. May not by yyencoded after all
        throw new exception('Not YYencoded?');
    }

    $yenc = get_config($db, 'yydecode_path');
    $cmd = "/bin/sh -c '$yenc -v -b -i -l -o - ' ";
    // always returns an error cos the size is bogus
    $pipes = [];
    $descriptorspec = array(
        0 => array('pipe', 'r'), // where we will write to
        1 => array('pipe', 'w'), // well get the output from here
        2 => array('file', '/dev/null', 'a') //   we don't want the errors
    );
    $process = proc_open($cmd, $descriptorspec, $pipes, '/tmp/', NULL, array('binary_pipes'));
    if ($process === FALSE || !is_resource($process)) {
        write_log('Failed to open pipe', LOG_ERR);
        throw new exception('Failed to open pipe');
    }
    //pipe to yenc
    foreach ($msg as $line) {
        $rw = fwrite($pipes[0], $line. "\n");
        if ($rw === FALSE) {
            //error_reporting($err_level);
            throw new exception('Write failed', ERR_PIPE_ERROR);
        }
    }
    pclose($pipes[0]);
    $data = stream_get_contents($pipes[1]); // get all the output as one string
    if ($data === FALSE) {
        throw new exception('Could not read from pipe', ERR_PIPE_ERROR);
    }
    if ($data == '') {
        write_log('Something must gone wrong yydecoden', LOG_NOTICE);
    }

    pclose($pipes[1]);
    proc_close($process);

    return $data;
}

function update_dlinfo_groupid(DatabaseConnection $db, $dlid, $groupid)
{
    assert(is_numeric($dlid) && is_numeric($groupid));
    $db->update_query_2('downloadinfo', array('groupid'=>$groupid), '"ID"=? AND "groupid"=?', array($dlid, 0));
}

function get_groupid_dlinfo(DatabaseConnection $db, $dlid)
{
    assert(is_numeric($dlid));
    $res = $db->select_query('"groupid" FROM downloadinfo WHERE "ID"=:dlid', array(':dlid'=>$dlid));

    return !isset($res[0]['groupid']) ? 0 : $res[0]['groupid'];
}

function get_post_name(DatabaseConnection $db, $id)
{
    global $LN;
    assert(is_numeric($id));
    $res = $db->select_query('"subject" FROM postinfo WHERE "id"=:post_id', 1, array(':post_id'=>$id));
    if (isset($res[0]['subject'])) {
        return $res[0]['subject'];
    } else {
        throw new exception ($LN['error_post_not_found']);
    }
}

function get_feeds_by_category(DatabaseConnection $db, $userid, $categoryID)
{
    assert(is_numeric($userid) && is_numeric($categoryID));
    $sql = '"feedid" FROM userfeedinfo WHERE "userid"=:userid AND "category"=:cat';
    $res = $db->select_query($sql, array(':userid'=>$userid, ':cat'=>$categoryID));
    if (!is_array($res)) {
        return [];
    }
    $groups = [];
    foreach ($res as $row) {
        $groups[$row['feedid']] = $row['feedid'];
    }

    return $groups;
}

function get_groups_by_category(DatabaseConnection $db, $userid, $categoryID)
{
    assert(is_numeric($userid) && is_numeric($categoryID));
    $sql = '"groupid" FROM usergroupinfo WHERE "userid"=:userid AND "category"=:cat';
    $res = $db->select_query($sql, array(':userid'=>$userid, ':cat'=>$categoryID));
    if (!is_array($res)) {
        return [];
    }
    $groups = [];
    foreach ($res as $row) {
        $groups[$row['groupid']] = $row['groupid'];
    }

    return $groups;
}

function verify_tool(DatabaseConnection $db, $toolname, $optional=FALSE)
{
    try {
        $path = get_config($db, "{$toolname}_path");
    } catch (exception $e) {
        if (!$optional) {
            throw $e;
        }
        write_log("Config parameter not set {$toolname}_path", LOG_WARNING);
        return;
    }
    if (!$optional && $path == '') {
        $rv = FALSE;
    } elseif ($path != '' && !is_executable($path)) {
        $rv = FALSE;
    } else {
        $rv = TRUE;
    }
    if (!$rv) {
        throw new exception ("File not found ($toolname) $path. Check admin/config", ERR_CONFIG_ERROR);
    }
}

function validate_generic(DatabaseConnection $db, &$msg)
{
    try {
        verify_tool($db, 'urdd', FALSE);
        verify_tool($db, 'file', TRUE);
        verify_tool($db, 'trickle', TRUE);
        verify_tool($db, 'cksfv', TRUE);
    } catch (exception $e) {
        $msg = $e->GetMessage();

        return FALSE;
    }

    return TRUE;
}

function validate_groups(DatabaseConnection $db, &$msg)
{
    return TRUE;
}

function validate_usenzb(DatabaseConnection $db, &$msg)
{
    return TRUE;
}

function validate_makenzb(DatabaseConnection $db, &$msg)
{
    try {
        verify_tool($db, 'gzip', TRUE);

        return TRUE;
    } catch (exception $e) {
        $msg = $e->GetMessage();

        return FALSE;
    }
}

function validate_rss(DatabaseConnection $db, &$msg)
{
    return TRUE;
}

function validate_sync(DatabaseConnection $db, &$msg)
{
    return TRUE;
}

function validate_download(DatabaseConnection $db, &$msg)
{
    try {
        verify_tool($db, 'yydecode', FALSE);
        verify_tool($db, 'cksfv', TRUE);
        verify_tool($db, 'unpar', TRUE);
        verify_tool($db, 'unrar', TRUE);
        verify_tool($db, 'unarj', TRUE);
        verify_tool($db, 'unace', TRUE);
        verify_tool($db, 'un7zr', TRUE);
        verify_tool($db, 'unzip', TRUE);
        verify_tool($db, 'subdownloader', TRUE);

        return TRUE;
    } catch (exception $e) {
        $msg = $e->GetMessage();

        return FALSE;
    }
}

function validate_viewfiles(DatabaseConnection $db)
{
    try {
        verify_tool($db, 'tar', TRUE);

        return TRUE;
    } catch (exception $e) {
        return FALSE;
    }
}

function validate_post(DatabaseConnection $db)
{
    try {
        verify_tool($db, 'yyencode', TRUE);

        return TRUE;
    } catch (exception $e) {
        return FALSE;
    }
}

function update_group(DatabaseConnection $db, $ID, $desc, $msg_count, $adult)
{
    assert(is_numeric($ID) && is_numeric($msg_count));
    $db->update_query_2('groups', array('description'=>$desc, 'postcount'=>$msg_count), '"ID"=?', array($ID));
    $db->update_query_2('groups', array('adult'=>($adult ? ADULT_ON : ADULT_OFF)), '"ID"=? AND "adult"=?', array($ID, ADULT_DEFAULT));
}

function insert_group(DatabaseConnection $db, $group_name, $description, $expire_time, $msg_count, $adult)
{
    assert(is_numeric($expire_time) && is_numeric($msg_count));
    $rows = array('name', 'description', 'active', 'expire', 'postcount', 'adult');
    $vals = array(strtolower($group_name), $description, 0, $expire_time, $msg_count, $adult ? ADULT_ON : ADULT_OFF);

    return $db->insert_query('groups', $rows, $vals, TRUE);
}

function strip_filename_bits($dlname)
{
    // Also, we do not want .001 / .002 in our set name, or r01:
    $dlname = preg_replace([
    // And remove .part01, .part02 etc too
            '/\.part(\d{1,3})\.(rar|r\d{2.3}|\d{3})?/i', 
    // also remove the vol12+23 bits for par2 posts
            '/\.vol(\d{1,3})\+\d{1,3}\.par2/i',
    //strip other known extensions
            '/\.(sfv|nzb|par2|nfo|jpg|png|gif|rar|vob|arj|lzh|txt|htm|flac|mp3|avi|mkv|cue|log|wav|mp4|wmv|srt|idx|7z|\d{3}|r\d{2,3})/i'
            ],  '', $dlname);
    //$dlname = preg_replace('/\.vol(\d{1,3})\+\d{1,3}\.par2/i', '', $dlname);
    //$dlname = preg_replace('/\.(sfv|nzb|par2|nfo|jpg|png|gif|rar|vob|arj|lzh|txt|htm|flac|mp3|avi|mkv|cue|log|wav|mp4|wmv|\d{3}|r\d{2,3})/i', '', $dlname);
    // strip urls
    return trim($dlname);
}

function strip_garbage($dlname)
{
    $dlname = preg_replace([ 
    // removes spammy content from a post name
            '/(http:\/\/([-a-z0-9]+\.)+[-a-z0-9]{1,6})|(www\.([-a-z0-9]+\.)*[-a-z0-9]{1,6})|(yenc)/i', 
    // strip newsgroup names
            '/((a.b)|(alt.binaries))(\.[-a-z0-9]+)+(@[-a-z0-9]+)?/i',
    // strip all weird charachters we don't need anyway
            '/\-\/;:`\|+?[=~!@#$\\\%^&*,><]+/'
            ], '', $dlname);
    //$dlname = preg_replace('/((a.b)|(alt.binaries))(\.[-a-z0-9]+)+(@[-a-z0-9]+)?/i', '', $dlname);
    // $dlname = preg_replace('/\-\/;:`\|+?[=~!@#$\\\%^&*,><]+/', '', $dlname);

    return trim($dlname);
}

function check_contains_filename($str)
{
    if (preg_match('/\.(sfv|nzb|par2|nfo|jpg|png|gif|rar|arj|vob|pls|lzh|txt|htm|flac|mp3|avi|mkv|cue|log|wav|mp4|wmv)/i', $str)) {
        return 2;
    } elseif (preg_match('/.[-a-z0-9]{1,4}\W/i', $str)) {
        return 1;
    } else {
        return 0;
    }
}

function simplify_chars($str)
{
    // replaces non- standard ascii characters by some similar character or character sequence
    $search = explode(',','ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,ñ,Ç,Æ,Œ,Á,É,Í,Ó,Ú,À,È,Ì,Ò,Ù,Ä,Ë,Ï,Ö,Ü,Â,Ê,Î,Ô,Û,Å,Ø,Ñ');
    $replace = explode(',','c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,n,C,AE,OE,A,E,I,O,U,A,E,I,O,U,A,E,I,O,U,A,E,I,O,U,A,O,N');
    $str = str_replace($search, $replace, $str);

    return $str;
}

function clean_dlname_for_setid($subject)
{
    $subject = simplify_chars($subject);
    $subject = strip_garbage($subject);

    if (preg_match("/[[(]?\d+((\s+of\s+)|(\s*\/\s*))\d+[])]?/i", $subject, $vars, PREG_OFFSET_CAPTURE)) {
        $subject1 = substr($subject, 0, $vars[0][1]);
        $subject2 = substr($subject, $vars[0][1] + strlen($vars[0][0]));
        $fn1 = check_contains_filename($subject1);
        $subject1 = strip_filename_bits($subject1);
        $fn2 = check_contains_filename($subject2);
        $subject2 = strip_filename_bits($subject2);
        if ($subject2 === FALSE) {
            $subject2 = '';
            $fn2 += 3;
        }
        if (strlen($subject1) < 8) {
            $fn1 += 3;
        }
        if (strlen($subject2) < 8) {
            $fn2 += 3;
        }
    } else {
        $subject1 = $subject;
        $subject2 = '';
        $fn1 = check_contains_filename($subject1);
        $subject1 = strip_filename_bits($subject1);
        $fn2 = 4;
    }

    $dlname = (($fn1 <= $fn2) ? $subject1 : $subject2);

    $dlname = preg_replace('/[^a-z0-9]*/i', '', $dlname); // remove all crap characters --> only leave letters and numbers
    $dlname = substr($dlname, 0, 20); // we only use 20 characters

    return $dlname;
}

function check_xml_function(test_result_list &$test_results)
{
    if (!function_exists('xml_parser_create')) {
        $test_results->add(new test_result('xml_parser_create', FALSE, 'Not all required modules have been loaded'));
        throw new exception ('Not all required modules have been loaded', ERR_CONFIG_ERROR);
    } else {
        $test_results->add(new test_result('xml_parser_create', TRUE, 'Required modules has been loaded'));
    }
}

function get_nice_value(DatabaseConnection $db)
{
    $niceval = get_config($db, 'nice_value');
    if (!is_numeric($niceval) || $niceval < 0 || $niceval > 19) {
        $niceval = 0;
    }

    return $niceval;
}

function add_line_to_text_area(DatabaseConnection $db, $option, $line, $userid)
{
    assert(is_numeric($userid));
    $value = get_pref($db, $option, $userid, '');
    $value = @unserialize($value);
    $value[] = $line;
    $value = @serialize($value);
    set_pref($db, $option, $value, $userid);
}

function get_extsetdata(DatabaseConnection $db, $setid, $name)
{
    $like = $db->get_pattern_search_command('LIKE');
    $sql = "\"value\" FROM extsetdata WHERE \"setID\"=:setid AND \"name\" $like :name";
    $res = $db->select_query($sql, 1, array(':setid'=>$setid, ':name'=>$name));

    return (isset($res[0]['value'])) ? $res[0]['value'] : '';
}

function get_user_dlpath(DatabaseConnection $db, $preview, $groupid, $dltype, $userid, $dlname, $type = 'DOWNLOAD', $setID = NULL, $category='')
{
    assert(is_numeric($groupid) && is_numeric($userid));
    $dlname = sanitise_download_name($db, $dlname);

    try {
        if ($category == '' && $groupid > 0) {
            if ($dltype == USERSETTYPE_RSS) {
                $category = feed_category($db, $groupid, $userid);
            } elseif ($dltype == USERSETTYPE_GROUP) {
                $category = group_category($db, $groupid, $userid);
            } elseif ($dltype == USERSETTYPE_SPOT) {
                $category = SpotCategories::HeadCat2Desc($groupid);
                $category = get_pref($db, 'spot_category_' . $category, $userid, '');
                if ($category != '') {
                    $category = get_category($db, $category, $userid);
                }
            }
        }
    } catch (exception $e) {
        $category = '';
    }

    try {
        $groupname = '';
        if ($dltype == USERSETTYPE_RSS && $groupid > 0) {
            $groupname = feed_name($db, $groupid);
        } elseif ($dltype == USERSETTYPE_GROUP && $groupid > 0) {
            $groupname = group_name($db, $groupid);
        } elseif ($dltype == USERSETTYPE_SPOT && $category == '') {
            $category = SpotCategories::HeadCat2Desc($groupid);
            $category = get_pref($db, 'spot_category_' . $category, $userid, '');
            if ($category != '') {
                $category = get_category($db, $category, $userid);
            }
        }
    } catch (exception $e) {
    }
    $extended_paths = !$preview;
    $done_part = ($preview === TRUE) ? PREVIEW_PATH : DONE_PATH;
    $username = get_username($db, $userid);
    $dlpath = get_dlpath($db);
    $user_dlpath = $dlpath . $done_part . $username . DIRECTORY_SEPARATOR;
    $add_setname = TRUE;
    $format_chars = 'cdugyYmDMwWzsnNx';
    $ext_dlpath = $setname = $genre = $xrated = '';
    if ($setID !== NULL) {
        $setname = get_extsetdata($db, $setID, 'name');
        $genre = get_extsetdata($db, $setID, '%genre');
        $xrated = get_extsetdata($db, $setID, 'xrated') ? 'XXX' : '';
    }
    if ($extended_paths) {
        $now = time();
        $format_string = get_pref($db, 'format_dl_dir', $userid, '');
        $values['u'] = $username;
        $values['n'] = $setname;
        $values['c'] = $category;
        $values['g'] = $groupname;
        $values['G'] = $groupid;
        $values['s'] = $dlname;
        $values['N'] = $genre;
        $values['x'] = $xrated;
        $values['D'] = date('Y-m-d', $now); // date
        $values['y'] = date('y', $now); // year 2 digits
        $values['Y'] = date('Y', $now); // year 4 digits
        $values['m'] = date('m', $now); // month numeric
        $values['d'] = date('d', $now); // day of month
        $values['M'] = date('M', $now); // month short name
        $values['F'] = date('F', $now); // month long name
        $values['w'] = date('w', $now); // day of the week
        $values['W'] = date('W', $now); // week number
        $values['z'] = date('z', $now); // day of the year

        $str = format_setname($format_string, $format_chars, $values);
        $str = str_replace('..' . DIRECTORY_SEPARATOR, '', $str); // remove nasty subdirs
        $str = preg_replace('/[^a-zA-Z0-9-()._\/+# ]/', '', $str); // remove "weird" characters
        $ext_dlpath .= trim($str);
        if (isset($ext_dlpath[0]) && $ext_dlpath[0] == '#') {
            $add_setname = FALSE;
            $ext_dlpath = ltrim($ext_dlpath, '#');
        }
    }

    $ext_dlpath = trim($ext_dlpath, DIRECTORY_SEPARATOR);
    $ext_dlpath = sanitise_download_name($db, $ext_dlpath, TRUE);
    if (!$add_setname) {
        $ext_dlpath = rtrim($ext_dlpath, DIRECTORY_SEPARATOR);
        $dir_elems = explode(DIRECTORY_SEPARATOR, $ext_dlpath);
        $dlpath = array_pop($dir_elems);
        if ($dlpath === FALSE) {
            $dlpath = '';
        }
        $user_dlpath .= implode(DIRECTORY_SEPARATOR, $dir_elems) . DIRECTORY_SEPARATOR;
    } else {
        $user_dlpath .= $ext_dlpath . DIRECTORY_SEPARATOR;
        $dlpath = $dlname;
    }

    return array($user_dlpath, $dlpath, $add_setname);
}

function update_group_setcount(DatabaseConnection $db, $groupid, $setcount)
{
    assert(is_numeric($groupid) && is_numeric($setcount));
    $db->update_query_2('groups', array('setcount'=>$setcount), '"ID"=?', array($groupid));
}

function get_sets_count_group(DatabaseConnection $db, $groupid)
{
    assert(is_numeric($groupid));
    $sql = '"setcount" AS "cnt" FROM groups WHERE "ID"=:groupid';
    $res = $db->select_query($sql, array(':groupid'=> $groupid));

    return (!isset($res[0]['cnt'])) ? FALSE : $res[0]['cnt'];
}

function count_sets_group(DatabaseConnection $db, $groupid)
{
    $sql = 'COUNT("ID") AS "cnt" FROM setdata WHERE "groupID"=:groupid';
    $res = $db->select_query($sql, array(':groupid'=>$groupid));

    return (!isset($res[0]['cnt'])) ? FALSE : $res[0]['cnt'];
}

function update_feed_setcount(DatabaseConnection $db, $feed_id, $setcount)
{
    assert(is_numeric($feed_id) && is_numeric($setcount));
    $db->update_query_2('rss_urls', array('feedcount'=>$setcount), '"id"=?', array($feed_id));
}

function count_sets_feed(DatabaseConnection $db, $feed_id)
{
    assert(is_numeric($feed_id));
    $sql = 'COUNT("id") AS "cnt" FROM rss_sets WHERE "rss_id"=?';
    $res = $db->select_query($sql, array($feed_id));

    return (!isset($res[0]['cnt'])) ? FALSE : $res[0]['cnt'];
}

function get_queue_status(DatabaseConnection $db, $dbid)
{
    assert(is_numeric($dbid));
    $sql = '"status" FROM queueinfo WHERE "ID"=?';
    $res = $db->select_query($sql, 1, array($dbid));

    return (isset($res[0]['status']) ? $res[0]['status'] : FALSE);
}

function get_set_info(DatabaseConnection $db, $setID, $origin_type)
{
    global $LN;
    if ($origin_type == USERSETTYPE_GROUP) {
        $sql = '* FROM setdata WHERE "ID"=?';
    } elseif ($origin_type == USERSETTYPE_RSS) {
        $sql = '* FROM rss_sets WHERE "setid"=?';
    } elseif ($origin_type == USERSETTYPE_SPOT) {
        $sql = '* FROM spots WHERE "spotid"=?';
    } else {
        throw new exception('Unknown origin_type');
    }

    $res = $db->select_query($sql, 1, array($setID));
    if (!isset($res[0])) {
        throw new exception($LN['error_setnotfound'] . ': ' . $setID);
    }
    $row = $res[0];
    if ($origin_type == USERSETTYPE_GROUP) {
        $originalsetname = strtolower($row['subject']); // Only 1 row should be returned.
        $groupname = group_name($db, $row['groupID']);
        $size = $row['size'];
    } elseif ($origin_type == USERSETTYPE_RSS) {
        $originalsetname = strtolower($row['setname']); // Only 1 row should be returned.
        $groupname = feed_name($db, $row['rss_id']);
        $size = $row['size'];
    } elseif ($origin_type == USERSETTYPE_SPOT) {
        $originalsetname = strtolower($row['title']); // Only 1 row should be returned.
        $groupname = array($row['category'], $row['subcata'], $row['subcatb'], $row['subcatc'], $row['subcatd'], $row['subcatz']); // xxx todo
        $size = $row['size'];
    }

    return array(utf8_decode($originalsetname), $groupname, $size);
}

function round_rating($rating, $min=0, $max=10)
{
    assert(is_numeric($rating) && is_numeric($min) && is_numeric($max));

    if ($rating < $min || $rating > $max) {
        return FALSE;
    }
    $decimals = $rating - (int) $rating;
    $rating = floor($rating);
    if ($decimals > 0.7) {
        $rating += 1;
        $decimals = 0;
    } elseif ($decimals > 0.2) {
        $decimals = 5;
    } else {
        $decimals = 0;
    }

    return "$rating.$decimals";
}

function split_args($args)
{
    // like explode on whitespace, but also parse a string for quotes, backslashes
    $arg_list = [];
    $item = $quote = '';
    $len = strlen($args);
    for ($i = 0; $i < $len; $i++) {
        $c = $args[$i];
        if ($c == $quote && $quote != '') {
            $quote = '';
            continue;
        } elseif (($c == '"' || $c == '\'') && $quote == '') {
            $quote = $c;
            continue;
        } elseif ($c == '\\') {
            $i++;
            if (isset($args[$i])) {
                $item .= $args[$i];
            }
            continue;
        } elseif ($c == ' ' && $quote == '') {
            if ($item == '') {
                continue;
            } else {
                $arg_list[] = $item;
                $item = '';
            }
        } else {
            $item .= $c;
        }
    }
    if ($item != '') {
        $arg_list[] = $item;
    }

    return $arg_list;
}

function delete_category(DatabaseConnection $db, $id, $userid)
{
    assert(is_numeric($userid) && is_numeric($id));
    $db->delete_query('categories', '"userid" = ? AND "id"=?', array($userid, $id));
    $db->update_query_2('usergroupinfo', array('category'=>0), '"userid"=? AND "category"=?', array($userid, $id));
    $db->update_query_2('userfeedinfo', array('category'=>0), '"userid"=? AND "category"=?', array($userid, $id));
}

function get_category(DatabaseConnection $db, $cat_id, $userid)
{
    assert(is_numeric($userid) && is_numeric($cat_id));
    $sql = '"name" FROM categories WHERE "userid"=:userid AND "id"=:cat_id';
    $res = $db->select_query($sql, 1, array(':userid'=> $userid, ':cat_id'=>$cat_id));
    if (!isset($res[0]['name'])) {
        return '';
    }

    return $res[0]['name'];
}

function get_server_name(DatabaseConnection $db, $server_id)
{
    assert(is_numeric($server_id));
    $sql = '"name" FROM usenet_servers WHERE "id"=:id';
    $res = $db->select_query($sql, 1, array(':id'=>$server_id));
    if (!isset($res[0]['name'])) {
        return '';
    }

    return $res[0]['name'];
}

function get_array(array $arr, $key, $default=NULL)
{
    // get a value from an array if the index is set, the default otherwise
    return (isset($arr [ $key ]) ? $arr [ $key ] : $default);
}

function get_compressed_headers(DatabaseConnection $db, $server_id)
{
    assert(is_numeric($server_id));
    $sql = '"compressed_headers" FROM usenet_servers WHERE "id"=:id';
    $rv = $db->select_query($sql, 1, array(':id'=>$server_id));
    if (!isset($rv[0]['compressed_headers'])) {
        throw new exception('No such Server', ERR_NO_SUCH_SERVER);
    }

    return $rv[0]['compressed_headers'];
}

function check_xrated($groupname)
{
    $is_xrated = ['erotic', 'sex', 'nude', 'porn', 'gay', 'xxx', 'nudism', 'seks', 'milf'];
    foreach ($is_xrated as $str) {
        if (stripos($groupname, $str) !== FALSE) {
            return TRUE;
        }
    }

    return FALSE;
}

function format_setname($format_string, $chars, $value)
{
    $len = strlen($format_string);
    $setname = '';
    $pattern = "/([\[{(]?)([-+]?\d*)(.)?([-+]?\d*)([$chars])([\])}]?)/";
    for ($i = 0; $i < $len; $i++) {
        if ($format_string[$i] == '%') {
            if ($format_string[$i+1] == '%') {
                $i++;
                $setname .= '%';
                continue;
            } else {
                $rv = preg_match($pattern, substr($format_string, $i + 1), $matches);
                if (!$rv) {
                    write_log('Unknown format character found', LOG_ERR);
                    continue;
                } else {
                    $str = preg_replace("/[$chars]/", 's', $matches[0]);
                    $str = '%' . preg_replace("/[(\[\]}{)]/", '', $str);
                    $match_len = strlen($matches[0]);
                    if ($matches[1] != '') {
                        $str = $matches[1] . $str . $matches[6];
                    } elseif ($matches[6] != '') {
                        $match_len--;
                    }
                    if (!isset($value[$matches[5]])) {
                        write_log('Unknown format character found', LOG_ERR);
                        continue;
                    }
                    if ($value[ $matches[5]] != '') {
                        $setname .= sprintf("$str", $value[ $matches[5]]);
                    }
                    $i += $match_len;
                }
            }
        } else {
            $setname .= $format_string[$i];
        }
    }

    return $setname;
}

function get_post_info(DatabaseConnection $db, $userid, $postid)
{
    assert(is_numeric($userid) && is_numeric($postid));
    $is_admin = urd_user_rights::is_admin($db, $userid);
    $input_arr = array(':postid' => $postid);
    $sql = '* FROM postinfo WHERE "id"= :postid';
    if (!$is_admin) {
        $sql .= ' AND "userid" = :userid';
        $input_arr[':userid'] = $userid;
    }

    $res = $db->select_query($sql, 1, $input_arr);
    if (!isset($res[0])) {
        die_html('error:' . $LN['error_post_not_found'] . ": $postid");
    }

    return $res[0];
}

function create_schedule(DatabaseConnection $db, $command, $time1, $time2, $interval, $userid)
{
    assert(is_numeric($interval) && is_numeric($time1) && is_numeric($time2) && is_numeric($userid));
    global $periods;

    $period = $periods->get($interval);
    $period_hours = $period->get_interval() * 3600;
    $nicetime = "$time1:$time2";
    $next = strtotime($period->get_next() . ' ' . $nicetime);
    add_schedule($db, $command, $next, $period_hours, $userid);
}

function add_default_schedules(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $prefArray_root = load_config($db);
    if ($prefArray_root['period_update'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_CHECK_VERSION), $prefArray_root['time1_update'], $prefArray_root['time2_update'], $prefArray_root['period_update'], $userid);
    }
    if ($prefArray_root['period_cu'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_CLEANDB) . ' users', $prefArray_root['time1_cu'], $prefArray_root['time2_cu'], $prefArray_root['period_cu'], $userid);
    }
    if ($prefArray_root['period_cd'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_CLEANDIR) . ' ' . $prefArray_root['dir_cd'], $prefArray_root['time1_cd'], $prefArray_root['time2_cd'], $prefArray_root['period_cd'], $userid);
    }
    if ($prefArray_root['period_cdb'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_CLEANDB), $prefArray_root['time1_cdb'], $prefArray_root['time2_cdb'], $prefArray_root['period_cdb'], $userid);
    }
    if ($prefArray_root['period_opt'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_OPTIMISE), $prefArray_root['time1_opt'], $prefArray_root['time2_opt'], $prefArray_root['period_opt'], $userid);
    }
    if ($prefArray_root['period_ng'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_GROUPS), $prefArray_root['time1_ng'], $prefArray_root['time2_ng'], $prefArray_root['period_ng'], $userid);
    }
    if ($prefArray_root['period_getspots'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_GETSPOTS), $prefArray_root['time1_getspots'], $prefArray_root['time2_getspots'], $prefArray_root['period_getspots'], $userid);
    }
    if ($prefArray_root['period_expirespots'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_EXPIRE_SPOTS), $prefArray_root['time1_expirespots'], $prefArray_root['time2_expirespots'], $prefArray_root['period_expirespots'], $userid);
    }
    if ($prefArray_root['period_getspots_blacklist'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_GETBLACKLIST), $prefArray_root['time1_getspots_blacklist'], $prefArray_root['time2_getspots_blacklist'], $prefArray_root['period_getspots_blacklist'], $userid);
    }
    if ($prefArray_root['period_getspots_whitelist'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_GETWHITELIST), $prefArray_root['time1_getspots_whitelist'], $prefArray_root['time2_getspots_whitelist'], $prefArray_root['period_getspots_whitelist'], $userid);
    }
    if ($prefArray_root['period_sendinfo'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_SENDINFO), $prefArray_root['time1_sendinfo'], $prefArray_root['time2_sendinfo'], $prefArray_root['period_sendinfo'], $userid);
    }
    if ($prefArray_root['period_getinfo'] > 0) {
        create_schedule($db, get_command(urdd_protocol::COMMAND_GETINFO), $prefArray_root['time1_getinfo'], $prefArray_root['time2_getinfo'], $prefArray_root['period_getinfo'], $userid);
    }
}

function get_scripts(DatabaseConnection $db, $dir, $userid, $global = TRUE)
{
    assert(is_numeric($userid));
    if ($global === TRUE) {
        $scripts = get_pref($db, 'global_scripts', $userid, '');
    } else {
        $scripts = get_pref($db, 'user_scripts', $userid, '');
    }
    $scripts = explode("\n", $scripts);
    if ($global === TRUE) {
        $files = glob("$dir/*." . URDD_SCRIPT_EXT);
    } else {
        $username = get_username($db, $userid);
        $files = glob("$dir/$username/*." . URDD_SCRIPT_EXT);
    }
    $filenames = [];
    sort($files);

    foreach ($files as $fn) {
        if ($fn == '') {
            continue;
        }
        $fn = basename($fn);
        $file['on'] = (int) in_array($fn, $scripts);
        $file['name'] = $fn;
        $file['id'] = $fn;
        $filenames[] = $file;
    }

    return $filenames;
}

function verify_php_version($test_recommend=FALSE)
{
    if (!version_compare(phpversion(), MIN_PHP_VERSION, '>=')) {
        throw new exception ('PHP version too old. ' . MIN_PHP_VERSION . ' or better must be used. PHP version ' . RECOMMENDED_PHP_VERSION . ' is recommended', ERR_CONFIG_ERROR);
    }
    if ($test_recommend && !version_compare(phpversion(), RECOMMENDED_PHP_VERSION, '>=')) {
        write_log('PHP version ' . RECOMMENDED_PHP_VERSION . ' is recommended; current version is ' . phpversion(), LOG_NOTICE);
    }
}

function verify_safe_mode()
{
    $safe_mode = ini_get('safe_mode');
    if ($safe_mode == 'on') {
        throw new exception ('Safe mode must be DISabled in ' . php_ini_loaded_file(), ERR_CONFIG_ERROR);
    }
}

function db_compress($data)
{
    $data = gzdeflate($data);
    $data = base64_encode($data);

    return $data;
}

function db_decompress($data)
{
    $data = base64_decode($data);
    $data = gzinflate($data);

    return $data;
}

function load_blacklist(DatabaseConnection $db, $source = NULL, $status = blacklist::ACTIVE, $global = NULL)
    // global == TRUE ==> global blacklist
    // global == FALSE ==> only user blacklist
    // global == NULL ==> all of the blacklist
{
    $Qsource = $Qstatus = $Quserid = '';
    $input_arr = [];
    if ($source !== NULL) {
        $input_arr[':src'] = $source;
        $Qsource = ' AND "source"= :src';
    }
    if ($status !== NULL) {
        $input_arr[':status'] = $status;
        $Qstatus = ' AND "status"= :status';
    }
    if ($global === TRUE) {
        $input_arr[':userid' ] = user_status::SUPER_USERID;
        $Quserid = ' AND "userid"= :userid';
    }
    elseif ($global === FALSE) {
        $input_arr[':superuser'] = user_status::SUPER_USERID;
        $Quserid = ' AND "userid" > :superuser';
    }

    $sql = '"id", "spotter_id", "source" FROM spot_blacklist WHERE 1=1 ' . "$Qsource $Qstatus $Quserid";
    $res = $db->select_query($sql, $input_arr);
    if ($res === FALSE) {
        $res = [];
    }
    $blacklist = [];
    foreach ($res as $row) {
        $blacklist[ $row['spotter_id'] ] = 0;
    }
    echo_debug(count($blacklist) . ' SpotIDs on the blacklist', DEBUG_SERVER);

    return $blacklist;
}

function prepare_base64($str)
{
    return str_replace(array('/', '+'), array('-s', '-p'), $str);
}

function remove_special_zip_strings($line)
{
    return str_replace(array('=C', '=B', '=A', '=D'), array("\n", "\r", "\0", '='), $line);
}

function special_zip_str($line) 
{
    return str_replace(array('=', "\n", "\r", "\0"), array('=D', '=C', '=B', '=A'), $line);
} 

function download_exists(DatabaseConnection $db, $dlid)
{
    $res = $db->select_query('"ID" FROM downloadinfo WHERE "ID"= :id', 1, array(':id'=>$dlid));
    return isset($res[0]['ID']);
}

function find_reference($url) 
{
    $rv = preg_match('/http\:\/\/.*imdb\.(com|de|es|pt|fr|it)\/[\w?\-\/?]*(tt[0-9]+)[\w?\-\/?]*/i', $url, $matches);
    if ($rv) {
        if (isset($matches[2])) { 
            return 'imdb:' . $matches[2];
        }
    }
    $rv = preg_match('/http\:\/\/.*moviemeter\.nl\/film\/([0-9]+)/i', $url, $matches);
    if ($rv) {
        if (isset($matches[1])) { 
            return 'movm:' . $matches[1];
        }
    }

    return '';
}

function preg_trim($string, $pattern) 
{
    $pattern = array('/^' . $pattern . '*/', '/' . $pattern . '*$/');
    return preg_replace($pattern, '', $string);
}
