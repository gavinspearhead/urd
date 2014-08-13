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
 * $LastChangedDate: 2013-10-16 23:50:40 +0200 (wo, 16 okt 2013) $
 * $Rev: 2932 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_edit_users.php 2932 2013-10-16 21:50:40Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';
$pathaaubl = realpath(dirname(__FILE__));

require_once "$pathaaubl/../functions/ajax_includes.php";

verify_access($db, NULL, TRUE, '', $userid, TRUE);

function build_skipper($perpage, $offset, $total)
{
    assert(is_numeric($perpage) && is_numeric($offset) && is_numeric($total));
    // Normal size, if there are more pages, a 1st and/or last page indicator can be added:
    $size = SKIPPER_SIZE;

    // First get all the rows:
    $totalpages = ceil($total / $perpage);		// Total number of pages.
    $activepage = ceil(($offset+1) / $perpage); 	// This is the page we're on. (+1 because 0/100 = page 1)

    $start = max($activepage - floor($size/2), 1);	// We start at 1 unless we're now on page 12, then we show page 2.
    $end = min($start + $size, $totalpages);	// We don't go beyond 'totalpages' ofcourse.
    $start = max($end - $size, 1);			// Re-check $start, in case the pagenumber is near the end

    $pages = array();
    for ($i = 1; $i <= $totalpages; $i++) {
        $thispage = array();
        $thispage['number'] = $i;
        $pageoffset = ($i - 1) * $perpage;          // For page 1, offset = 0.

        $thispage['offset'] = $pageoffset;
        // distance is the distance from the current page, maximum of 5. Used to colour close pagenumbers:
        $thispage['distance'] = min(abs($activepage - $i), 5);
        $pages[] = $thispage;
    }

    return array($pages, $totalpages, $activepage);
}

function show_spots_list(DatabaseConnection $db, $userid, $which)
{
    global $smarty;
    $users = array();
    $offset = get_request('offset', 0);
    $perpage = get_maxperpage($db, $userid);
    $search = $o_search = (trim(get_request('search', '')));
    $sort = get_request('sort', 'spotter_id');
    $sort_dir = get_request('sort_dir', 'asc');
    $only_rows = get_request('only_rows', 0);
    $show_status = get_request('status', 'all');
    if (!in_array($sort, array('spotter_id', 'source', 'username', 'status'))) {
        $sort = 'spotter_id';
    }
    if (!in_array($sort_dir, array('asc', 'desc'))) {
        $sort_dir = 'asc';
    }
    $Qsearch = '';
    if ($search != '') {
        $search = "%$search%";
        $db->escape($search, TRUE);
        $like = $db->get_pattern_search_command('LIKE');
        $Qsearch = " AND \"spotter_id\" $like $search ";
    }

    // Display:
    switch($which) {
        default:
        case 'spots_blacklist':
            $table = 'spot_blacklist';
            $list_external = blacklist::BLACKLIST_EXTERNAL;
            $list_internal = blacklist::BLACKLIST_INTERNAL;
            $list_status_disabled = blacklist::DISABLED;
            $list_status_active = blacklist::ACTIVE;
            $list_status_nonactive = blacklist::NONACTIVE;
            $active_tab = 'blacklist';
            break;
        case 'spots_whitelist':
            $table = 'spot_whitelist';
            $list_external = whitelist::WHITELIST_EXTERNAL;
            $list_internal = whitelist::WHITELIST_INTERNAL;
            $list_status_disabled = whitelist::DISABLED;
            $list_status_active = whitelist::ACTIVE;
            $list_status_nonactive = whitelist::NONACTIVE;
            $active_tab = 'whitelist';
            break;
    }
    if ($show_status == 'all') {
        $Qstatus = '';
    } else if ($show_status == 'active') {
        $Qstatus = 'AND status = ' . $list_status_active;
    } else if ($show_status == 'nonactive') {
        $Qstatus = 'AND status = ' . $list_status_nonactive;
    } else if ($show_status == 'disabled') {
        $Qstatus = 'AND status = ' . $list_status_disabled;
    }
    $sql = "*, users.\"name\" AS \"username\" FROM $table LEFT JOIN users ON $table.\"userid\" = users.\"id\" WHERE 1=1 $Qsearch $Qstatus ORDER BY \"$sort\" $sort_dir";
    $res = $db->select_query($sql, $perpage, $offset);
    if (!is_array($res)) {
        $res = array();
    }
    $cnt_sql = "COUNT(*) AS \"cnt\" FROM $table LEFT JOIN users ON $table.\"userid\" = users.\"id\" WHERE 1=1 $Qsearch $Qstatus";
    $cnt_res = $db->select_query($cnt_sql, 1);
    $cnt = 0;
    if (isset($cnt_res[0]['cnt'])) {
        $cnt = $cnt_res[0]['cnt'];
    }
    $blacklist = array();
    $number = $offset;
    foreach ($res as $row) {
        $user['number'] = ++$number;
        $user['spotter_id'] = $row['spotter_id'];
        $user['id'] = $row['id'];
        $user['source'] = $row['source'];
        $user['username'] = $row['username'];
        $user['userid'] = $row['userid'];
        $user['status'] = $row['status'];
        $blacklist[] = $user;
    }
    list($pages, $lastpage, $currentpage) = build_skipper($perpage, $offset, $cnt);
    init_smarty('', 0);
    if (!$only_rows) {
        $smarty->assign('pages',		    $pages);
        $smarty->assign('currentpage',		$currentpage);
        $smarty->assign('lastpage',		    $lastpage);
    }
    $smarty->assign('offset',		    $offset);
    $smarty->assign('list_external',	$list_external);
    $smarty->assign('list_internal',	$list_internal);
    $smarty->assign('sort',             $sort);
    $smarty->assign('sort_dir',         $sort_dir);
    $smarty->assign('search',           $o_search);
    $smarty->assign('blacklist',        $blacklist);
    $smarty->assign('active_tab',       $active_tab);
    $smarty->assign('status_active',    $list_status_active);
    $smarty->assign('status_nonactive', $list_status_nonactive);
    $smarty->assign('status_disabled',  $list_status_disabled);
    $smarty->assign('maxstrlen',        round($perpage / 3));
    $smarty->assign('only_rows',        $only_rows);
    return $smarty->fetch('ajax_user_blacklist.tpl');
}

try {
    $cmd = get_request('cmd', FALSE);
    $which = get_request('which', 'spots_blacklist');

    switch ($cmd) {
        case 'export_settings':
            $list = get_request('list');
            if ($list == 'black') {
                export_settings($db, 'spots_blacklist', 'urd_spots_blacklist.xml', (urd_user_rights::is_admin($db, $userid)?NULL:$userid));
            } elseif ($list == 'white') {
                export_settings($db, 'spots_whitelist', 'urd_spots_whitelist.xml', (urd_user_rights::is_admin($db, $userid)?NULL:$userid));
            }
            break;
        case 'import_settings_blacklist':
            if (isset ($_FILES['filename']['tmp_name'])) {
                $xml = new urd_xml_reader($_FILES['filename']['tmp_name']);
                $blacklist = $xml->read_spots_blacklist($db);
                clear_all_spots_blacklist($db, (urd_user_rights::is_admin($db, $userid)?NULL:$userid));
                set_all_spots_blacklist($db, $blacklist, $userid);
            } else {
                throw new exception($LN['error_filenotfound'] );
            }
            return_result();
            break;
        case 'import_settings_whitelist':
            if (isset ($_FILES['filename']['tmp_name'])) {
                try {
                    $xml = new urd_xml_reader($_FILES['filename']['tmp_name']);
                    $whitelist = $xml->read_spots_whitelist($db);
                    clear_all_spots_whitelist($db, (urd_user_rights::is_admin($db, $userid)?NULL:$userid));
                    set_all_spots_whitelist($db, $whitelist, $userid);
                } catch (exception $e) {
                    echo_debug($e->getMessage(), DEBUG_ALL);
                }
            } else {
                throw new exception($LN['error_filenotfound'] );
            }
            return_result();
            break;
        case 'load_blacklist':
            $contents = show_spots_list($db, $userid, $which);
            return_result(array('contents' => $contents));
        default:
            throw new exception ($LN['error_invalidaction']);
            break;
    }

} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}

