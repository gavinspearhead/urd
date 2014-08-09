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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: browse.php 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 */
if (!defined('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
}

$pathidx = realpath(dirname(__FILE__));

require_once "$pathidx/../functions/html_includes.php";

verify_access($db, urd_modules::URD_CLASS_GROUPS, FALSE, '', $userid, FALSE);

if (!isset($_SESSION['setdata']) || !is_array($_SESSION['setdata'])) {
    $_SESSION['setdata'] = array();
}

$origroupID = $groupID = get_request('groupID', '');
$minsetsize = get_request('minsetsize', 0, 'is_numeric');
$maxage  = get_request('maxage', '', 'is_numeric');

$search = utf8_decode(html_entity_decode(trim(get_request('search', ''))));
$saved_search = get_request('saved_search', '');
if ($saved_search == '' && $groupID == '' && $search == '') {
    $origroupID = $groupID = get_pref($db, 'default_group', $userid, '');
}

if ($isadmin) {
    $add_menu['actions'][] = new menu_item2 ('update_ng','update',urd_modules::URD_CLASS_GROUPS, '', 'command');
    $add_menu['actions'][] = new menu_item2 ('gensets_ng','ng_gensets',urd_modules::URD_CLASS_GROUPS, '', 'command');
    $add_menu['actions'][] = new menu_item2 ('expire_ng','expire',urd_modules::URD_CLASS_GROUPS, '', 'command');
    $add_menu['actions'][] = new menu_item2 ('purge_ng','purge', urd_modules::URD_CLASS_GROUPS, $LN['purge'] . ' ' . $LN['ng_title'], 'command');
} elseif (urd_user_rights::is_updater($db, $userid)) {
    $add_menu['actions'][] = new menu_item2 ('update_ng','update',urd_modules::URD_CLASS_GROUPS, '', 'command');
    $add_menu['actions'][] = new menu_item2 ('gensets_ng','ng_gensets',urd_modules::URD_CLASS_GROUPS, '', 'command');
    $add_menu['actions'][] = new menu_item2 ('expire_ng','expire',urd_modules::URD_CLASS_GROUPS, '', 'command');
}

$add_menu['actions'][] = new menu_item2 ('add_search','add_search', urd_modules::URD_CLASS_GROUPS, '', 'command');
$add_menu['actions'][] = new menu_item2 ('delete_search','delete_search', urd_modules::URD_CLASS_GROUPS, '', 'command');
$add_menu['actions'][] = new menu_item2 ('postmessage','post_message', urd_modules::URD_CLASS_POST, '', 'command');

$url = get_config($db, 'url');
$type = $rsstype = USERSETTYPE_GROUP;

$saved_searches = new saved_searches($userid);
$saved_searches->load($db);
$saved_searches = $saved_searches->get_all_names($type);

if (isset($groupID[9]) && substr_compare($groupID, 'category_', 0, 9) == 0) {
    $categoryID = substr($groupID, 9);
    if (!is_numeric($categoryID)) {
        $categoryID = 0;
    }
    $groupID = 0 ;
    $selected = 'category';
} elseif (isset($groupID[6]) && substr_compare($groupID, 'group_', 0, 6) == 0) {
    $groupID = substr($groupID, 6);
    if (!is_numeric($groupID)) {
        $groupID = 0;
    }
    $categoryID = 0;
    $selected = 'group';
} else {
    $selected = '';
    $groupID = $categoryID = $origroupID = 0;
}
if ($groupID == 0) {
    $rss_groupid = '0';
    $rss_minsetsize = $minsetsize;
} else {
    $rss_minsetsize = get_minsetsize_group($db, $groupID, $userid, $minsetsize);
    $rss_groupid = $groupID;
}

$categories = get_used_categories_group($db, $userid);
if ($categoryID > 0 && !in_array($categoryID, array_keys($categories))) {
    $categoryID = 0;
}

$perpage = get_maxperpage($db, $userid);
$rss_limit = $perpage;

$rssurl = $url . "html/rss.php?type=$rsstype&amp;groupID=$rss_groupid&amp;categoryID=$categoryID&amp;limit=$rss_limit&amp;minsize=$rss_minsetsize&amp;maxage=$maxage&amp;userid=$userid";

$totbin = get_total_ng_sets($db);
$title = $LN['browse_download'] . ' ' . $LN['from'] . ' ' . $totbin. ' ' . $LN['sets'] . '!';

// Get the required values:
// ori = passed back to the front-end, these are used in the database (backend):

list($minsetsizelimit, $maxsetsizelimit) = get_size_limits_groups($db);
list($minagelimit, $maxagelimit) = get_age_limits_groups($db);

$minsetsizelimit = nearest($minsetsizelimit / (1024 * 1024), FALSE);
$maxsetsizelimit = nearest($maxsetsizelimit / (1024 * 1024), TRUE);
$minagelimit = nearest($minagelimit / (3600 * 24), FALSE);
$maxagelimit = nearest($maxagelimit / (3600 * 24), TRUE);

$minsetsize = get_pref($db, 'minsetsize', $userid, NULL);
if ($minsetsize !== NULL) {
    $minsetsize /= (1024 * 1024);
}
$maxsetsize = get_pref($db, 'maxsetsize', $userid, NULL);
if ($maxsetsize !== NULL) {
    $maxsetsize /= (1024 * 1024);
}
if ($maxsetsize <= 0) { 
    $maxsetsize = NULL;
}
if ($minsetsize <= 0) { 
    $minsetsize = NULL;
}
$offset  = get_request('offset', 0, 'is_numeric');
$order   = get_request('order', '');
$flag    = get_request('flag', '');
$minage  = get_request('minage', $minagelimit, 'is_numeric');
$maxage  = get_request('maxage', $maxagelimit, 'is_numeric');
$minsetsize = get_request('minsetsize', $minsetsize, 'is_numeric');
$maxsetsize = get_request('maxsetsize', $maxsetsize, 'is_numeric');
$minrating  = get_request('minrating', 0, 'is_numeric');
$maxrating  = get_request('maxrating', 10, 'is_numeric');
$mincomplete = get_request('mincomplete', 0, 'is_numeric');
$maxcomplete = get_request('maxcomplete', 100, 'is_numeric');

$setid = get_request('setid', '');
$subscribedgroups = subscribed_groups_select($db, $groupID, $categoryID, $categories, $userid);
$posting = urd_modules::check_module_enabled($db, urd_modules::URD_CLASS_POST) && urd_user_rights::is_poster($db, $userid);

if ($order == '') {
    $order = map_default_sort($prefs, array('subject' => 'better_subject'));
}

init_smarty($title, 1, $add_menu);

list($size, $suffix) = format_size($totbin, 'h', '', 1000);
$smarty->assign('total_articles', $size . $suffix);
$smarty->assign('search',		$search);
$smarty->assign('isadmin',		$isadmin);
$smarty->assign('groupID',		$origroupID);
$smarty->assign('offset',		$offset);
$smarty->assign('USERSETTYPE',	USERSETTYPE_GROUP);
$smarty->assign('minage',		$minage);
$smarty->assign('maxage',		$maxage);
$smarty->assign('minrating',	$minrating);
$smarty->assign('maxrating',	$maxrating);
$smarty->assign('minagelimit',	0);
$smarty->assign('maxagelimit',	$maxagelimit);
$smarty->assign('minsetsizelimit',	$minsetsizelimit);
$smarty->assign('maxsetsizelimit',	$maxsetsizelimit);
$smarty->assign('minratinglimit',0);
$smarty->assign('maxratinglimit',10);
$smarty->assign('mincompletelimit',	0);
$smarty->assign('perpage',		$perpage);
$smarty->assign('maxcompletelimit',	100);
$smarty->assign('mincomplete',	$mincomplete);
$smarty->assign('maxcomplete',	$maxcomplete);
$smarty->assign('order',		trim($order));
$smarty->assign('posting',		$posting?1:0);
$smarty->assign('setid',		$setid);
$smarty->assign('flag',			$flag);
$smarty->assign('rssurl',		$rssurl);
$smarty->assign('minsetsize',	$minsetsize);
$smarty->assign('maxsetsize',	$maxsetsize);
$smarty->assign('saved_searches',	$saved_searches);
$smarty->assign('_saved_search',	$saved_search);

$smarty->assign('subscribedgroups',	$subscribedgroups);
$smarty->display('browse.tpl');
