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
 * $Id: newsgroups.php 1959 2010-12-24 16:50:50Z gavinspearhead $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathng = realpath(dirname(__FILE__));

require_once "$pathng/../functions/html_includes.php";
require_once "$pathng/../functions/periods.php";

$offset = get_request('offset', 0);
if (!is_numeric($offset)) {
    $offset = 0;
}

function build_newsgroup_query(DatabaseConnection $db, $userid, $offset, &$retvals = NULL)
{
    global $LN;
    assert(is_numeric($userid));

    $adult = urd_user_rights::is_adult($db, $userid);
    $admin = urd_user_rights::is_admin($db, $userid);

    $order_options = array ('name', 'postcount', 'active', 'last_updated', 'expire', 'refresh_time', 'refresh_period', 'category', 'adult');
    $order_dirs = array ('desc', 'asc', '');
    $minngsize = get_pref($db, 'minngsize', $userid);
    $cnt = count_active_ng($db);
    $perpage = get_maxperpage($db, $userid);
    $search = utf8_decode(trim(get_request('search')));

    if ($search == (html_entity_decode("<{$LN['search']}>"))) {
        $search = '';
    }
    $search_all = get_request('search_all');
    $order = strtolower(get_request('order'));
    $order_dir = strtolower(get_request('order_dir'));
    if (!in_array($order, $order_options)) {
        $order = 'name';
    }
    if (!in_array($order_dir, $order_dirs)) {
        $order_dir = '';
    }
    //$showall = get_request('unsubscribed', '0');
    $unsubscribed = ($cnt == 0 || $search != '') ? TRUE : FALSE;
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
    if ($cnt != 0 && $search == '') {
        $Qsearch .= ' AND "active"=\'1\' ';
    } elseif ($minngsize > 0 && $search_all == 1) {
        $Qsearch .= " AND \"postcount\" > '$minngsize' ";
    }
    if (!$adult && !$admin) {
        $Qsearch .= " AND \"adult\" != 1";
    }

    //$time = time();
    $query = '*, ' .
        'groups."minsetsize" AS admin_minsetsize, ' .
        'groups."maxsetsize" AS admin_maxsetsize, ' .
        '"last_updated" AS timestamp ' .
        "FROM groups LEFT JOIN usergroupinfo ON groups.\"ID\" = \"groupid\" AND \"userid\" = '$userid' " .
        "WHERE 1=1 $Qsearch " .
        "ORDER BY $order $order_dir";
    $res = $db->select_query($query, $perpage, $offset);
    if ($res === FALSE) {
        $res = array();
    }

    return $res;
}

function build_newsgroup_query_total(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    global $LN;
    $minngsize = get_pref($db, 'minngsize', $userid);
    $cnt = count_active_ng($db);
    $search = utf8_decode(trim(get_request('search')));
    $adult = urd_user_rights::is_adult($db, $userid);
    $admin =  urd_user_rights::is_admin($db, $userid);

    if ($search == (html_entity_decode("<{$LN['search']}>"))) {
        $search = '';
    }
    $search_all = get_request('search_all', '');

    // Search google style:
    $Qsearch = '';
    $search = str_replace('*', ' ', $search);
    $keywords = explode(' ', $search);
    $search_type = $db->get_pattern_search_command('LIKE'); // get the operator we need for the DB LIKE for mysql or ~~* for postgres
    foreach ($keywords as $keyword) {
        if ($keyword == '') { continue; }
        $k = "%{$keyword}%";
        $db->escape($k, TRUE);
        $Qsearch .= " AND \"name\" $search_type $k ";
    }
    // Default = we show only active groups:
    if ($cnt != 0 && $search == '') {
        $Qsearch .= ' AND "active"=1 ';
    } elseif ($minngsize > 0 && $search_all != '0') {
        $Qsearch .= " AND \"postcount\" > $minngsize ";
    }
    if (!$adult && !$admin) {
        $Qsearch .= " AND \"adult\" != 1";
    }

    $query = "COUNT(\"name\") AS cnt FROM groups WHERE 1=1 $Qsearch";

    $res = $db->select_query($query);
    if ($res === FALSE || !isset($res[0]['cnt'])) {
        return FALSE;
    }

    return $res[0]['cnt'];
}

function build_newsgroup_skipper(DatabaseConnection $db, $userid, $offset)
{
    assert(is_numeric($userid));
    global $LN;

    $perpage = get_maxperpage($db, $userid);
    $search = utf8_decode(trim(get_request('search', '')));
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
    $total = build_newsgroup_query_total($db, $userid);

    return get_pages($total, $perpage, $offset);
}

function update_user_ng_info(DatabaseConnection $db, $userid)
{
    global $LN;
    assert(is_numeric($userid));
    if (!isset($_POST['newsgroup'])) {
        return;
    }
    foreach ($_POST['newsgroup'] as $id => $on) {
        if (is_numeric($id) && strtolower($on) == 1) {
            $visible = (isset($_POST['visible'][$id]) && $_POST['visible'][$id] != 0) ? 1 : 0;
            $minsetsize = isset($_POST['minsetsize'][$id]) ? $_POST['minsetsize'][$id] : 0;
            $maxsetsize = isset($_POST['maxsetsize'][$id]) ? $_POST['maxsetsize'][$id] : 0;
            $category = isset($_POST['category'][$id]) ? $_POST['category'][$id] : 0;
            try {
                $minsetsize = unformat_size($minsetsize, 1024, 'm');
                $maxsetsize = unformat_size($maxsetsize, 1024, 'm');
            } catch (exception $e) {
                throw new exception($LN['error_invalidvalue'] . ' ' . $e->getMessage());
            }
            set_usergroupinfo($db, $userid, $id, $minsetsize, $maxsetsize, $visible, $category);
        }
    }
}

verify_access($db, urd_modules::URD_CLASS_GROUPS, FALSE, '', $userid, TRUE);
$uprefs = load_config($db);
$uc = new urdd_client($db, $uprefs['urdd_host'], $uprefs['urdd_port'],$userid);
$urdd_online = $uc->is_connected();
$categories = get_categories($db, $userid);

$cmd = get_request('cmd', 'show');

function update_ng_subscriptions(DatabaseConnection $db, urdd_client $uc, $userid)
{
    assert(is_numeric($userid));
    global $periods, $LN, $uprefs;
    if (!$uc->is_connected()) {
        throw new exception($LN['urdddisabled']);
    }
    $def_exp = $uprefs['default_expire_time'];
    $max_exp = $uprefs['maxexpire'];
    if (!isset($_POST['newsgroup'])) {
        return;
    }
    foreach ($_POST['newsgroup'] as $id => $on) {
        if (is_numeric($id)) {
            if ($on == 1 && isset( $_POST['period'][$id]) && isset($_POST['time1'][$id]) && isset( $_POST['time2'][$id]) && isset( $_POST['expire'][$id])) {
                $period = $_POST['period'][$id];
                $time1 = $_POST['time1'][$id];
                $time2 = $_POST['time2'][$id];
                $adult = ($_POST['adult'][$id]) ? ADULT_ON : ADULT_OFF;
                $exp = $_POST['expire'][$id];
                if ($exp == '' || $exp == 0) {
                    $exp = $def_exp;
                } elseif (!is_numeric($exp)) {
                    throw new exception($LN['error_invalidvalue'] . ': ' . $LN['ng_expire_time'] . ' ' . htmlentities($exp));
                }
                if ($exp > $max_exp || $exp < 1) {
                    throw new exception($LN['error_bogusexptime']);
                }
                $admin_minsetsize = $_POST['admin_minsetsize'][$id];
                $admin_maxsetsize = $_POST['admin_maxsetsize'][$id];
                try {
                    $admin_minsetsize = unformat_size($admin_minsetsize, 1024, 'm');
                    $admin_maxsetsize = unformat_size($admin_maxsetsize, 1024, 'm');
                } catch (exception $e) {
                    throw new exception($LN['error_invalidvalue'] . ' ' . $e->getMessage());
                }
                if (!is_numeric($admin_minsetsize) ) {
                    $admin_minsetsize = 0;
                }
                if (!is_numeric($admin_maxsetsize) ) {
                    $admin_maxsetsize = 0;
                }
                if (($admin_maxsetsize < $admin_minsetsize) && ($admin_maxsetsize != 0)) {
                    throw new exception($LN['error_invalidvalue'] . ': ' . $LN['ng_admin_maxsetsize'] . ' ' . htmlentities($admin_maxsetsize));
                }

                $period = $periods->get($period);
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
                // if it all works out, we also subscribe to the newsgroup
                $uc->subscribe($id, $exp, $admin_minsetsize, $admin_maxsetsize, $adult);
            } else {
                $uc->cancel(get_command(urdd_protocol::COMMAND_UPDATE) . " $id");
                $uc->cancel(get_command(urdd_protocol::COMMAND_EXPIRE) . " $id");
                $uc->cancel(get_command(urdd_protocol::COMMAND_GENSETS) . " $id");
                $uc->unsubscribe($id, USERSETTYPE_GROUP);
                remove_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE);
            }
        }
    }
}

$allgroups = array();
$minsetsize_pref = get_pref($db, 'minsetsize', $userid, 0);
$maxsetsize_pref = get_pref($db, 'maxsetsize', $userid, 0);

if ($cmd == 'export') {
    export_settings($db, 'newsgroups', 'urd_group_settings.xml');
} elseif ($cmd == 'load_settings' && isset($_FILES['filename']['tmp_name']) && $isadmin) {
    challenge::verify_challenge_text($_POST['challenge']);
    $xml = new urd_xml_reader($_FILES['filename']['tmp_name']);
    $groups = $xml->read_newsgroup_settings($db);
    if ($groups != array()) {
        clear_all_groups($db, $userid);
        set_all_groups($db, $groups, $userid);
        die_html('OK');
    } else {
        throw new exception($LN['settings_notfound']);
    }
} elseif ($cmd == 'toggle_adult') {
    if ($isadmin) {
        challenge::verify_challenge_text($_POST['challenge']);
        $group_id = get_post('group_id');
        $value = get_post('value') == 1? ADULT_ON:ADULT_OFF;
        toggle_adult($db, 'group', $group_id, $value);
        die_html('OK');
    } else {
        throw new exception($LN['error_accessdenied']);
    }
} elseif ($cmd == 'update') {
    challenge::verify_challenge_text($_POST['challenge']);
    if ($isadmin && $urdd_online) {
        update_ng_subscriptions($db, $uc, $userid);
    }
    update_user_ng_info($db, $userid);
    die_html('OK' . $LN['saved']);
}

$res = build_newsgroup_query($db, $userid, $offset, $retvals);
$unsubscribed = $retvals[0];
$order = get_request('order', 'name');
if ($order == '') { 
    $order = 'name'; 
}
$order_dir = get_post('order_dir', '');
$page_tab = get_post('page_tab', $isadmin ? 'admin' : 'user');
$search = utf8_decode(trim(get_request('search', '')));
$search_all = get_post('search_all', '');

list ($pages, $currentpage, $lastpage) = build_newsgroup_skipper($db, $userid, $offset);
$def_exp = get_config($db, 'default_expire_time');
$number = $offset;
$hidden_groups = SPOTS_GROUPS::get_hidden_groups();
foreach ($res as $row) {
    $thisng = array();
    $id = $row['ID'];
    $active = $row['active'];
    if (in_array($row['name'], $hidden_groups) && ($search != $row['name'])) {
        continue;
    }

    $description = trim (str_replace('?', '', $row['description']));
    $thisng['id'] = $id;
    $thisng['active_val'] = $active;
    $thisng['category'] = utf8_encode($row['category']);
    $thisng['description'] = $description;
    list($val, $suf) = format_size($row['admin_minsetsize'], 'h', '');
    $thisng['admin_minsetsize'] =  $val . $suf;
    list($val, $suf) = format_size($row['admin_maxsetsize'], 'h', '');
    $thisng['admin_maxsetsize'] =  $val . $suf;

    $thisng['name'] = shorten_newsgroup_name ($row['name'], 0);
    if ($active == 0) {
        $thisng['expire'] = $def_exp;
    } else {
        $thisng['expire'] = $row['expire'];
    }
    list($postcount, $suffix) = format_size($row['postcount'], 'h', '', 1000);
    $thisng['postcount'] = $postcount . $suffix;
    $lastupdated = $row['timestamp'];
    $refresh_time = $row['refresh_time'];
    $refresh_period = $row['refresh_period'];

    $time1 = $time2 = NULL;
    if ($refresh_period > 0) {
        $time1 = floor($refresh_time / 60);
        $time2 = floor($refresh_time % 60);
    }

    $select = $refresh_period;

    if ($lastupdated == 0) {
        $lastupdated = '-';
    } else {
        $lastupdated = time() - $lastupdated;
        $lastupdated = readable_time($lastupdated, 'largest_two');
    }
    $thisng['lastupdated'] = $lastupdated;

    $thisng['select'] = $select;
    $thisng['adult'] = $row['adult'] == ADULT_ON ? 1 : 0;
    $thisng['number'] = ++$number;
    $thisng['time1'] = $time1;
    $thisng['time2'] = $time2;
    $thisng['visible'] = $row['visible'] === NULL ? 1 : $row['visible'];
    $thisng['minsetsize'] = $row['minsetsize'] === NULL ? 0: $row['minsetsize'] ;
    list($val, $suf) = format_size($thisng['minsetsize'], 'h', '');
    $thisng['minsetsize'] =  $val . $suf;
    $thisng['maxsetsize'] = $row['maxsetsize'] === NULL ? 0: $row['maxsetsize'] ;
    list($val, $suf) = format_size($thisng['maxsetsize'], 'h', '');
    $thisng['maxsetsize'] =  $val . $suf;
    $allgroups[] = $thisng;
}

list($pkeys, $ptexts) = $periods->get_periods();

$message = '';
if ($isadmin && !$urdd_online) {
    $message = $LN['enableurddfirst'];
}
$uc->disconnect();

init_smarty('', 0);

$smarty->assign('urdd_online',	    (int) $urdd_online);
$smarty->assign('periods_texts',	$ptexts);
$smarty->assign('periods_keys',		$pkeys);
$smarty->assign('message',		    $message);
$smarty->assign('categories',		$categories);
$smarty->assign('NG_SUBSCRIBED', 	NG_SUBSCRIBED);
$smarty->assign('sort',		        $order);
$smarty->assign('sort_dir',		    $order_dir);
$smarty->assign('isadmin',		    (int) $isadmin);
$smarty->assign('page_tab',         $page_tab);
$smarty->assign('unsubscribed',		$unsubscribed);
$smarty->assign('pages',		    $pages);
$smarty->assign('currentpage',		$currentpage);
$smarty->assign('lastpage',		    $lastpage);
$smarty->assign('offset',		    $offset);
$smarty->assign('allgroups', 		$allgroups);
$smarty->assign('maxstrlen',		$prefs['maxsetname']/2);
$smarty->assign('referrer', 		basename(__FILE__, '.php'));

$smarty->display('ajax_groups.tpl');
