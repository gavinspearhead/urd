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
 * $LastChangedDate: 2010-12-24 17:50:50 +0100 (vr, 24 dec 2010) $
 * $Rev: 1959 $
 * $Author: gavinspearhead $
 * $Id: rssfeeds.php 1959 2010-12-24 16:50:50Z gavinspearhead $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathng = realpath(dirname(__FILE__));

require_once "$pathng/../functions/html_includes.php";
require_once "$pathng/../functions/periods.php";

verify_access($db, urd_modules::URD_CLASS_RSS, FALSE, '', $userid, TRUE);

$offset = get_request('offset', 0);
if (!is_numeric($offset)) {
    $offset = 0;
}

function build_rss_query(DatabaseConnection $db, $userid, $offset, &$retvals = NULL)
{
    global $LN;
    assert(is_numeric($userid));
    $order_options = array ('name', 'feedcount', 'subscribed', 'last_updated', 'expire', 'refresh_time', 'refresh_period', 'minsetsize', 'url', 'visible', 'category', 'adult');
    $order_dirs = array ('desc', 'asc', '');
    //$minngsize = get_pref($db, 'minngsize', $userid);
    $cnt = count_active_rss($db);
    $perpage = get_maxperpage($db, $userid);
    $search = trim(utf8_decode(get_request('search', '')));
    if ($search == (html_entity_decode("<{$LN['search']}>"))) {
        $search = '';
    }
    $search_all = get_request('search_all', '0');
    $order = strtolower(get_request('order'));
    $order_dir = strtolower(get_request('order_dir'));
    if (!in_array($order, $order_options)) {
        $order = 'name';
    }
    if (!in_array($order_dir, $order_dirs)) {
        $order_dir = '';
    }

    $unsubscribed = ($cnt == 0 || $search != '0') ? TRUE : FALSE;
    $retvals[0] = $unsubscribed;

    // Search google style:
    $Qsearch = '';
    $search = str_replace('*', ' ', $search);
    $keywords = explode(' ', $search);
    $search_type = $db->get_pattern_search_command('LIKE'); // get the operator we need for the DB LIKE for mysql or ~~* for postgres
    foreach ($keywords as $keyword) {
        $keyword = trim($keyword);
        if ($keyword == '') {
            continue;
        }
        $k = "%{$keyword}%";
        $db->escape($k, TRUE);
        $Qsearch .= " AND \"name\" $search_type $k ";
    }
    // Default = we show only active groups:
    if ($search_all != '0') {
        $Qsearch .= ' AND "subscribed"=\'1\' ';
    }
    //$time = time();
    $query = '*, rss_urls."id" AS rss_id, "last_updated" AS timestamp ' .
        "FROM rss_urls LEFT JOIN userfeedinfo ON rss_urls.\"id\" = \"feedid\" AND \"userid\" = '$userid' " .
        "WHERE 1=1 $Qsearch " .
        "ORDER BY $order $order_dir";
    $res = $db->select_query($query, $perpage, $offset);
    if ($res === FALSE) {
        $res = array();
    }

    return $res;
}

function build_rss_query_total(DatabaseConnection $db, $userid)
{
    global $LN;
    assert(is_numeric($userid));
    //$cnt = count_active_rss($db);
    $search = trim(utf8_decode(get_request('search', '')));
    if ($search == (html_entity_decode("<{$LN['search']}>"))) {
        $search = '';
    }
    $search_all = get_request('search_all', '0');

    // Search google style:
    $Qsearch = '';
    $search = str_replace('*', ' ', $search);
    $keywords = explode(' ', $search);
    $search_type = $db->get_pattern_search_command('LIKE'); // get the operator we need for the DB LIKE for mysql or ~~* for postgres
    foreach ($keywords as $keyword) {
        $k = "%{$keyword}%";
        $db->escape($k, TRUE);
        $Qsearch .= " AND \"name\" $search_type $k ";
    }
    // Default = we show only active groups:
    if ($search_all != '0') {
        $Qsearch .= ' AND "subscribed"=1 ';
    }

    $query = "COUNT(\"name\") AS cnt FROM rss_urls WHERE 1=1 $Qsearch";
    $res = $db->select_query($query);
    if ($res === FALSE || !isset($res[0]['cnt'])) {
        return FALSE;
    }

    return $res[0]['cnt'];
}

function build_rss_skipper(DatabaseConnection $db, $userid, $offset)
{
    assert(is_numeric($userid));

    global $LN;
    
    $perpage = get_maxperpage($db, $userid);
    $search = trim(utf8_decode(get_request('search', '')));
    if ($search == (html_entity_decode("<{$LN['search']}>"))) {
        $search = '';
    }
    $showall = get_request('unsubscribed', '0');
    if ($showall != '0') {
        $unsubscribed = TRUE;
        $unsub = 1;
    } else {
        $unsubscribed = FALSE;
        $unsub = 0;
    }

    // First get all the rows:
    $total = build_rss_query_total($db, $userid);

    return get_pages($total, $perpage, $offset);
}

function update_user_rss_info(DatabaseConnection $db, $userid)
{// todo
    assert(is_numeric($userid));
    if (!isset($_POST['rssfeed'])) {
        return;
    }
    foreach ($_POST['rssfeed'] as $id => $on) {
        if (is_numeric($id) /*&& strtolower($on) == 1*/) {
            $visible = (isset($_POST['visible'][$id]) && $_POST['visible'][$id] != 0) ? 1 : 0;
            $minsetsize = isset($_POST['minsetsize'][$id]) ? $_POST['minsetsize'][$id] : 0;
            $maxsetsize = isset($_POST['maxsetsize'][$id]) ? $_POST['maxsetsize'][$id] : 0;
            $category = isset($_POST['category'][$id]) ? $_POST['category'][$id] : 0;
            $minsetsize = unformat_size($minsetsize, 1024, 'm');
            $maxsetsize = unformat_size($maxsetsize, 1024, 'm');
            set_userfeedinfo($db, $userid, $id, $minsetsize, $maxsetsize, $visible, $category);
        }
    }
}

function update_rss_subscriptions (DatabaseConnection $db, urdd_client $uc, $userid)
{ // todo
    assert(is_numeric($userid));
    global $periods, $LN;
    if (!$uc->is_connected()) {
        throw new exception($LN['urdddisabled']);
    }
    if (!isset($_POST['rssfeed'])) {
        return;
    }
    $max_exp = get_config($db, 'maxexpire');
    foreach ($_POST['rssfeed'] as $id => $on) {
        if (is_numeric($id)) {
            if ($on == 1) {
                if (isset( $_POST['period'][$id]) && isset( $_POST['time1'][$id]) && isset($_POST['time2'][$id]) && isset($_POST['expire'][$id])) {
                    $period = $_POST['period'][$id];
                    $time1 = $_POST['time1'][$id];
                    $time2 = $_POST['time2'][$id];
                    $exp = $_POST['expire'][$id];
                    if (!is_numeric($exp)) {
                        $exp = get_config($db,'default_expire_time');
                    }
                    if ($exp > $max_exp || $exp < 1) {
                        throw new exception($LN['error_bogusexptime']);
                    }
                    $period = $periods->get($period);
                    if ($period === FALSE) {
                        throw new exception($LN['error_invalidupdatevalue']);
                    }

                    remove_rss_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE_RSS);
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
                        //$next = $period->get_next() .  ' ' . $nicetime;
                        // if we have a proper schedule set it here

                        set_period_rss($uc, $db, $id, $nicetime, $period->get_interval(), $time, $period->get_id());
                    }
                    // if it all works out, we also subscribe to the newsgroup
                    $uc->subscribe_rss($id, $exp);
                } else {
                    throw new exception($LN['error_invalidvalue']);
                }
            } else {
                $uc->cancel(get_command(urdd_protocol::COMMAND_UPDATE_RSS) . " $id");
                $uc->cancel(get_command(urdd_protocol::COMMAND_EXPIRE_RSS) . " $id");
                $uc->unsubscribe($id, USERSETTYPE_RSS);
                remove_rss_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE_RSS );
            }
        }
    }
    $uc->disconnect();
}

$allfeeds = array();
$uprefs = load_config($db);
$uc = new urdd_client($db, $uprefs['urdd_host'], $uprefs['urdd_port'],$userid);
$urdd_online = $uc->is_connected();

$categories = get_categories($db, $userid);

$minsetsize_pref = get_pref($db, 'minsetsize', $userid, 0);
$maxsetsize_pref = get_pref($db, 'maxsetsize', $userid, 0);
$cmd = get_request('cmd', 'show');

if ($cmd == 'export') {
    export_settings($db, 'rssfeeds', 'urd_rss_feedssettings.xml');
} elseif ($cmd == 'load_settings' && isset ($_FILES['filename']['tmp_name']) && $isadmin) {
    challenge::verify_challenge_text($_POST['challenge']);
    $xml = new urd_xml_reader($_FILES['filename']['tmp_name']);
    $feeds = $xml->read_feeds_settings($db);
    if ($feeds != array()) {
        clear_all_feeds($db, $userid);
        set_all_feeds($db, $feeds, $userid);
        die_html('OK');
    } else {
        throw new exception($LN['settings_notfound']);
    }
} elseif ($cmd == 'toggle_adult') {
    if ($isadmin) {
        challenge::verify_challenge_text($_POST['challenge']);
        $feed_id = get_post('feed_id');
        $value = get_post('value') == 1? ADULT_ON:ADULT_OFF;
        toggle_adult($db, 'rss', $feed_id, $value);
        die_html('OK');
    } else {
        throw new exception($LN['settings_notfound']);
    }
} elseif ($cmd == 'update') {
    challenge::verify_challenge_text($_POST['challenge']);
    if ($isadmin && $urdd_online) {
        update_rss_subscriptions ($db, $uc, $userid);
    }
    update_user_rss_info($db, $userid);
    die_html('OK'. $LN['saved']);
}

$res = build_rss_query($db, $userid, $offset, $retvals);
$unsubscribed = $retvals[0];
$order = get_post('order', 'name');
if ($order == '') { $order = 'name'; }
$page = get_post('page_tab', $isadmin ? 'admin':'user');
$order_dir = get_post('order_dir', '');
$search = utf8_decode(trim(get_request('search', '')));
$search_all = get_post('search_all', '');

list ($pages, $currentpage, $lastpage) = build_rss_skipper($db, $userid, $offset);
$number = $offset;
foreach ($res as $row) {
    $this_rss = array();
    $id = $row['rss_id'];
    $active = $row['subscribed'];
    $this_rss['id'] = $id;
    $this_rss['active_val'] = $active ;
    $this_rss['url'] = $row['url'];
    $this_rss['name'] = $row['name'];
    $this_rss['adult'] = $row['adult'] == ADULT_ON ? 1 : 0;
    $this_rss['category'] = $row['category'];
    $this_rss['expire'] = $row['expire'];
    $this_rss['feedcount'] = $row['feedcount'];
    $this_rss['authentication'] = ($row['password'] != '' && $row['username'] != '') ? 1 : 0;
    $lastupdated = $row['timestamp'];
    $refresh_time = $row['refresh_time'];

    $refresh_period = $row['refresh_period'];

    if ($refresh_period > 0) {
        $time1 = floor($refresh_time / 60);
        $time2 = floor($refresh_time % 60);
    } else {
        $time1 = $time2 = NULL;
    }
    $select = $refresh_period;

    if ($lastupdated == 0) {
        $lastupdated = '-';
    } else {
        $lastupdated = time() - $lastupdated;
        $lastupdated = readable_time($lastupdated, 'largest_two');
    }
    $this_rss['lastupdated'] = $lastupdated;

    $this_rss['select'] = $select;
    $this_rss['number'] = ++$number;
    $this_rss['time1'] = $time1;
    $this_rss['time2'] = $time2;
    $this_rss['visible'] = $row['visible'] === NULL ? 1 : $row['visible'];
    $this_rss['minsetsize'] = $row['minsetsize'] === NULL ? 0: $row['minsetsize'] ;
    list($val, $suf) = format_size($this_rss['minsetsize'], 'h', '');
    $this_rss['minsetsize'] =  $val . $suf;

    $this_rss['maxsetsize'] = $row['maxsetsize'] === NULL ? 0: $row['maxsetsize'] ;
    list($val, $suf) = format_size($this_rss['maxsetsize'], 'h', '');
    $this_rss['maxsetsize'] =  $val . $suf;

    $allfeeds[] = $this_rss;
}

list($pkeys, $ptexts) = $periods->get_periods();
init_smarty('', 0);

$message = '';
if ($isadmin && !$urdd_online) {
    $message = $LN['enableurddfirst'];
}

$smarty->assign('periods_texts',	$ptexts);
$smarty->assign('categories',		$categories);
$smarty->assign('urdd_online',	    (int) $urdd_online);
$smarty->assign('periods_keys',		$pkeys);
$smarty->assign('RSS_SUBSCRIBED', 	RSS_SUBSCRIBED);
$smarty->assign('sort',	    	    $order);
$smarty->assign('sort_dir',		    $order_dir);
$smarty->assign('search',		    $search);
$smarty->assign('page_tab',         $page);
$smarty->assign('message',		    $message);
$smarty->assign('isadmin',		    (int) $isadmin);
$smarty->assign('unsubscribed',		$unsubscribed);
$smarty->assign('pages',		    $pages);
$smarty->assign('currentpage',		$currentpage);
$smarty->assign('lastpage',		    $lastpage);
$smarty->assign('offset',		    $offset);
$smarty->assign('allfeeds', 		$allfeeds);
$smarty->assign('maxstrlen',		$prefs['maxsetname']/2);
$smarty->assign('referrer', 		basename(__FILE__, '.php'));

$smarty->display('ajax_rss_feeds.tpl');
