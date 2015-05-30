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

require_once "$pathng/../functions/ajax_includes.php";
require_once "$pathng/../functions/periods.php";

verify_access($db, urd_modules::URD_CLASS_GROUPS, FALSE, '', $userid, TRUE);

function build_newsgroup_query(DatabaseConnection $db, $userid, $offset, &$retvals = NULL)
{
    global $LN;
    assert(is_numeric($userid));

    $adult = urd_user_rights::is_adult($db, $userid);
    $admin = urd_user_rights::is_admin($db, $userid);

    $order_options = array ('name', 'postcount', 'active', 'last_updated', 'expire', 'refresh_time', 'refresh_period', 'category', 'adult');
    $order_dirs = array ('desc', 'asc', '');
    $minngsize = get_pref($db, 'minngsize', $userid);
    $group_count = count_active_ng($db);
    $perpage = get_maxperpage($db, $userid);
    $search = utf8_decode(trim(get_request('search')));

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
    $unsubscribed = ($group_count == 0 || $search != '') ? TRUE : FALSE;
    $retvals[0] = $unsubscribed;

    // Search google style:
    $Qsearch = '';
    $input_arr = array();
    $search = trim(str_replace('*', ' ', $search));
    $keywords = explode(' ', $search);
    $search_type = $db->get_pattern_search_command('LIKE'); // get the operator we need for the DB LIKE for mysql or ~~* for postgres
    foreach ($keywords as $idx => $keyword) {
        $keyword = trim($keyword);
        if ($keyword == '') { continue; }
        $Qsearch .= " AND \"name\" $search_type :keyword_$idx ";
        $input_arr[":keyword_$idx"] = "%{$keyword}%";
    }
    // Default = we show only active groups:
    if ($search == '' && $group_count > 0) {
        $Qsearch .= ' AND "active" = :active ';
        $input_arr[':active'] = newsgroup_status::NG_SUBSCRIBED;
    } elseif ($minngsize > 0 && $search_all == 1) {
        $Qsearch .= ' AND "postcount" > :minngsize ';
        $input_arr[':minngsize'] = $minngsize;
    }
    if (!$adult && !$admin) {
        $Qsearch .= ' AND "adult" != :adult';
        $input_arr[':adult'] = ADULT_ON;
    }

    //$time = time();
    $query = '*, ' .
        'groups."minsetsize" AS admin_minsetsize, ' .
        'groups."maxsetsize" AS admin_maxsetsize, ' .
        '"last_updated" AS timestamp ' .
        'FROM groups LEFT JOIN usergroupinfo ON groups."ID" = "groupid" AND "userid" = :userid ' .
        "WHERE 1=1 $Qsearch " .
        "ORDER BY $order $order_dir";
    $input_arr[':userid'] = $userid;

    $res = $db->select_query($query, $perpage, $offset, $input_arr);
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
    $group_count = count_active_ng($db);
    $search = utf8_decode(trim(get_request('search')));
    $adult = urd_user_rights::is_adult($db, $userid);
    $admin =  urd_user_rights::is_admin($db, $userid);

    $search_all = get_request('search_all', '');

    // Search google style:
    $Qsearch = '';
    $input_arr = array();
    $search = trim(str_replace('*', ' ', $search));
    $keywords = explode(' ', $search);
    $search_type = $db->get_pattern_search_command('LIKE'); // get the operator we need for the DB LIKE for mysql or ~~* for postgres
    foreach ($keywords as $idx => $keyword) {
        $keyword = trim($keyword);
        if ($keyword == '') { continue; }
        $Qsearch .= " AND \"name\" $search_type :keyword_$idx ";
        $input_arr[":keyword_$idx"] = "%{$keyword}%";
    }
    // Default = we show only active groups:
    if ($search == '' && $group_count > 0) {
        $Qsearch .= ' AND "active" = :active ';
        $input_arr[':active'] = newsgroup_status::NG_SUBSCRIBED;
    } elseif ($minngsize > 0 && $search_all != '0') {
        $Qsearch .= ' AND "postcount" > :minngsize ';
        $input_arr[':minngsize'] = $minngsize;
    }
    if (!$adult && !$admin) {
        $Qsearch .= ' AND "adult" != :adult ';
        $input_arr[':adult'] = ADULT_ON;
    }

    $query = "COUNT(\"name\") AS cnt FROM groups WHERE 1=1 $Qsearch";
    $res = $db->select_query($query, $input_arr);
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

function unsubscribe_group(DatabaseConnection $db, urdd_client $uc, $id)
{
    $uc->cancel(get_command(urdd_protocol::COMMAND_UPDATE) . " $id");
    $uc->cancel(get_command(urdd_protocol::COMMAND_EXPIRE) . " $id");
    $uc->cancel(get_command(urdd_protocol::COMMAND_GENSETS) . " $id");
    $uc->unsubscribe($id, USERSETTYPE_GROUP);
    remove_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE);
}

function subscribe_group(DatabaseConnection $db, urdd_client $uc, $userid, $id, $period, $time1, $time2, $adult, $expire, $admin_minsetsize, $admin_maxsetsize)
{
    global $periods, $LN, $uprefs;
    $def_expire = $uprefs['default_expire_time'];
    $max_expire = $uprefs['maxexpire'];
     if (!$uc->is_connected()) {
        throw new exception($LN['urdddisabled']);
    }
    $name = get_all_group_by_id($db, $id);
    if ($expire == '' || $expire == 0) {
        $expire = $def_expire;
    }
    verify_expire($db, $expire, $name);
    try { 
        $admin_minsetsize = unformat_size($admin_minsetsize, 1024, 'm');
        $admin_maxsetsize = unformat_size($admin_maxsetsize, 1024, 'm');
    } catch (exception $e) {
        throw new exception($name . ': ' . $LN['error_invalidvalue'] . ' ' . $e->getMessage());
    }
    if (!is_numeric($admin_minsetsize) ) {
        $admin_minsetsize = 0;
    }
    if (!is_numeric($admin_maxsetsize) ) {
        $admin_maxsetsize = 0;
    }
    if (($admin_maxsetsize < $admin_minsetsize) && ($admin_maxsetsize != 0)) {
        throw new exception($name . ': ' . $LN['error_invalidvalue'] . ': ' . $LN['ng_admin_maxsetsize'] . ' ' . htmlentities($admin_maxsetsize));
    }

    $period = $periods->get($period);
    if ($period === FALSE) {
        throw new exception($name . ': ' . $LN['error_invalidupdatevalue']);
    }
    remove_schedule($db, $uc, $id, urdd_protocol::COMMAND_UPDATE);
    if ($period->get_interval() !== NULL) {
        verify_time($time1, $time2, $name);
        
        // Sanity checks:
        $time1 %= 24;
        $time2 %= 60;
        $time = $time1 * 60 + $time2; // Used to display the update time, is in mins after 0:00
        $nicetime = $time1 . ':' . $time2;
        // if we have a proper schedule set it here

        set_period($uc, $db, $id, $nicetime, $period->get_interval(), $time, $period->get_id());
    }

    $uc->subscribe($id, $expire, $admin_minsetsize, $admin_maxsetsize, $adult);
}

function set_ng_value(DatabaseConnection $db, $group_id, $option, $value)
{
    assert(is_numeric($group_id));
    if (!in_array($option, array('minsetsize', 'maxsetsize', 'expire'))) {
        throw new exception($LN['error_invalidvalue']);
    }
    $db->update_query_2('groups', array($option=>$value), '"ID"=?', array($group_id));
}

function show_groups(DatabaseConnection $db, urdd_client $uc, $userid, $isadmin)
{
    global $periods, $LN, $smarty, $prefs;
    $offset = get_request('offset', 0);
    if (!is_numeric($offset)) {
        $offset = 0;
    }

    $categories = get_categories($db, $userid);
    $allgroups = array();
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
        $thisng['admin_minsetsize'] = $val . $suf;
        list($val, $suf) = format_size($row['admin_maxsetsize'], 'h', '');
        $thisng['admin_maxsetsize'] = $val . $suf;

        $thisng['name'] = shorten_newsgroup_name($row['name'], 0);
        $thisng['long_name'] = $row['name'];
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
        $thisng['minsetsize'] = $row['minsetsize'] === NULL ? 0: $row['minsetsize'];
        list($val, $suf) = format_size($thisng['minsetsize'], 'h', '');
        $thisng['minsetsize'] =  $val . $suf;
        $thisng['maxsetsize'] = $row['maxsetsize'] === NULL ? 0: $row['maxsetsize'];
        list($val, $suf) = format_size($thisng['maxsetsize'], 'h', '');
        $thisng['maxsetsize'] =  $val . $suf;
        $allgroups[] = $thisng;
    }

    list($pkeys, $ptexts) = $periods->get_periods();

    $urdd_online = $uc->is_connected();
    $message = '';
    if ($isadmin && !$urdd_online) {
        $message = $LN['enableurddfirst'];
    }
    $uc->disconnect();

    init_smarty();

    $smarty->assign(array(
        'urdd_online'=>	    (int) $urdd_online,
        'periods_texts'=>	$ptexts,
        'periods_keys'=>	$pkeys,
        'categories'=>		$categories,
        'NG_SUBSCRIBED'=> 	newsgroup_status::NG_SUBSCRIBED,
        'sort'=>		    $order,
        'sort_dir'=>	    $order_dir,
        'isadmin'=>		    (int) $isadmin,
        'page_tab'=>        $page_tab,
        'unsubscribed'=>    $unsubscribed,
        'pages'=>		    $pages,
        'currentpage'=>		$currentpage,
        'lastpage'=>		$lastpage,
        'offset'=>		    $offset,
        'allgroups'=> 		$allgroups,
        'maxstrlen'=>		$prefs['maxsetname']/2,
        'referrer'=> 		basename(__FILE__, '.php')));

    $contents = $smarty->fetch('ajax_groups.tpl');
    return_result(array('contents' => $contents, 'urdd_online' => (int) $urdd_online, 'message'=>$message));
}

try {
    $cmd = get_request('cmd');
    $uprefs = load_config($db);
    $uc = new urdd_client($db, $uprefs['urdd_host'], $uprefs['urdd_port'],$userid);

    $message = '';
    switch ($cmd) {
        case 'export_settings':
            export_settings($db, 'newsgroups', 'urd_group_settings.xml');
            break;
        case 'load_settings':
            if (isset($_FILES['filename']['tmp_name']) && $isadmin) {
                challenge::verify_challenge($_POST['challenge']);
                $xml = new urd_xml_reader($_FILES['filename']['tmp_name']);
                $groups = $xml->read_newsgroup_settings($db);
                if ($groups != array()) {
                    clear_all_groups($db, $userid);
                    set_all_groups($db, $groups, $userid);
                } else {
                    throw new exception($LN['settings_notfound']);
                } 
            } else {
                throw new exception($LN['error_nouploadsfound']);
            }
            break;
        case 'set_value':
            if ($isadmin) {
                challenge::verify_challenge($_POST['challenge']);
                $group_id = get_post('group_id');
                $option = get_post('option');

                $value = unformat_size(get_post('value'), 1024, 'm');
                set_ng_value($db, $group_id, $option, $value);
                $message = $LN['saved'];
            } else {
                throw new exception($LN['error_accessdenied']);
            }
            break;
        case 'set_plain_value':
            if ($isadmin) {
                challenge::verify_challenge($_POST['challenge']);
                $group_id = get_post('group_id');
                $name = get_all_group_by_id($db, $group_id);
                $option = get_post('option');
                $value = get_post('value');
                if ($option == 'expire') {
                    verify_expire($db, $value, $name);
                }
                set_ng_value($db, $group_id, $option, $value);
                $message = $LN['saved'];
            } else {
                throw new exception($LN['error_accessdenied']);
            }
            break;
        case 'toggle_visibility':
            challenge::verify_challenge($_POST['challenge']);
            $group_id = get_post('group_id');
            $value = get_post('visibility') ? 1 : 0;
            set_usergroup_value($db, $userid, $group_id, 'visible', $value);
            $message = $LN['saved'];
            break;
        case 'set_user_value':
            challenge::verify_challenge($_POST['challenge']);
            $group_id = get_post('group_id');
            $option = get_post('option');
            $value = unformat_size(get_post('value'), 1024, 'm');
            if (substr($option, 0, 5) == 'user_') { 
                $option = substr($option, 5);
            }
            set_usergroup_value($db, $userid, $group_id, $option, $value);
            $message = $LN['saved'];
            break;
        case 'set_plain_user_value':
            challenge::verify_challenge($_POST['challenge']);
            $group_id = get_post('group_id');
            $option = get_post('option');
            $value = get_post('value');
            if (substr($option, 0, 5) == 'user_') { 
                $option = substr($option, 5);
            }
            set_usergroup_value($db, $userid, $group_id, $option, $value);
            $message = $LN['saved'];
            break;
        case 'set_update_time':
            if ($isadmin) {
                challenge::verify_challenge($_POST['challenge']);
                $group_id = get_post('group_id');
                $time1 = get_post('time1');
                $time2 = get_post('time2');
                $period = get_post('period');
                $name = get_all_group_by_id($db, $group_id);
                $period = $periods->get($period);
                if ($period === FALSE) {
                    throw new exception($name . ': ' . $LN['error_invalidupdatevalue']);
                }
                remove_schedule($db, $uc, $group_id, urdd_protocol::COMMAND_UPDATE);
                if ($period->get_interval() !== NULL) {
                    verify_time($time1, $time2, $name);
                    // Sanity checks:
                    $time1 %= 24;
                    $time2 %= 60;
                    $time = $time1 * 60 + $time2; // Used to display the update time, is in mins after 0:00
                    $nicetime = $time1 . ':' . $time2;

                    set_period($uc, $db, $group_id, $nicetime, $period->get_interval(), $time, $period->get_id());
                }

                $message = $LN['saved'];
            } else {
                throw new exception($LN['error_accessdenied']);
            }
            break;
        case 'toggle_adult':
            if ($isadmin) {
                challenge::verify_challenge($_POST['challenge']);
                $group_id = get_post('group_id');
                $value = get_post('value') == 1? ADULT_ON:ADULT_OFF;
                toggle_adult($db, 'group', $group_id, $value);
                $message = $LN['saved'];
            } else {
                throw new exception($LN['error_accessdenied']);
            }
            break;
        case 'subscribe':
            if ($isadmin) {
                $groupid = get_request('groupid');
                $name = get_all_group_by_id($db, $groupid);
                $period = get_request('period');
                $time1 = get_request('time1');
                $time2 = get_request('time2');
                $adult = get_request('adult');
                $expire = get_request('expire');
                verify_expire($db, $expire, $name);
                $admin_minsetsize = get_request('admin_minsetsize');
                $admin_maxsetsize = get_request('admin_maxsetsize');
                subscribe_group($db, $uc, $userid, $groupid, $period, $time1, $time2, $adult, $expire, $admin_minsetsize, $admin_maxsetsize);
                $message = $LN['saved'];
            } else {
                throw new exception($LN['error_accessdenied']);
            }
            break;
        case 'unsubscribe':
            if ($isadmin) {
                $groupid = get_request('groupid');
                unsubscribe_group($db, $uc, $groupid);
                $message = $LN['saved'];
            } else {
                throw new exception($LN['error_accessdenied']);
            }
            break;
        case 'show':
            show_groups($db, $uc, $userid, $isadmin);
            break;
        default:
            throw new exception($LN['error_invalidaction'] );
    }
    return_result(array('message' => $message));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
