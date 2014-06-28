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
 * $Id: rsssets.php 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 */
if (!defined('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
}

$pathidx = realpath(dirname(__FILE__));

require_once "$pathidx/../functions/html_includes.php";

if (!isset($_SESSION['setdata']) || !is_array($_SESSION['setdata'])) {
    $_SESSION['setdata'] = array();
}

verify_access($db, urd_modules::URD_CLASS_RSS, FALSE, '', $userid, FALSE);

$add_menu = NULL;

if ($isadmin) {
    $add_menu = array (
        'actions'=>
        array(
            new menu_item2 ('update_rss','update',urd_modules::URD_CLASS_RSS, '', 'command'),
            new menu_item2 ('expire_rss','expire',urd_modules::URD_CLASS_RSS, '', 'command'),
            new menu_item2 ('purge_rss','purge', urd_modules::URD_CLASS_RSS, $LN['purge'] . ' ' . $LN['feeds_title'], 'command'),
        )
    );
} elseif (urd_user_rights::is_updater($db, $userid)) {
    $add_menu = array (
        'actions'=>
        array(
            new menu_item2 ('update_rss','update',urd_modules::URD_CLASS_RSS, '', 'command'),
            new menu_item2 ('expire_rss','expire',urd_modules::URD_CLASS_RSS, '', 'command'),
        )
    );
}

$add_menu['actions'][] = new menu_item2 ('add_search','add_search', urd_modules::URD_CLASS_RSS, '', 'command');
$add_menu['actions'][] = new menu_item2 ('delete_search','delete_search', urd_modules::URD_CLASS_RSS, '', 'command');

$type = $rsstype = USERSETTYPE_RSS;

$saved_searches = new saved_searches($userid);
$saved_searches->load($db);
$saved_searches = $saved_searches->get_all_names($type);

$feed_id = $origfeed_id = get_request('feed_id', '');

$search = html_entity_decode(get_request('search', ''));

$saved_search = get_request('saved_search', '');
if ($saved_search == '' && $feed_id == '' && $search == '') {
    $origfeed_id = $feed_id = get_pref($db, 'default_feed', $userid, '');
}

$categories = get_used_categories_rss($db, $userid);

// Create title:
$totbin = get_total_rss_sets($db);
$title = $LN['browse_download'] . ' ' . $LN['from'] . ' ' . $totbin. ' ' . $LN['sets'] . '!';

// Get the required values:
// ori = passed back to the front-end, these are used in the database (backend):

if (isset($feed_id[9]) && substr_compare($feed_id, 'category_',0, 9) == 0) {
    $categoryID = substr($feed_id, 9);
    if (!is_numeric($categoryID)) {
        $categoryID = 0;
    }
    $feed_id = 0;
} elseif (isset($feed_id[5]) && substr_compare($feed_id, 'feed_',0, 5) == 0) {
    $feed_id = substr($feed_id, 5);
    if (!is_numeric($feed_id)) {
        $feed_id = 0;
    }
    $categoryID = 0;
} else {
    $feed_id = $categoryID = $origfeed_id = 0;
}

if ($categoryID > 0 && !in_array($categoryID, array_keys($categories))) {
    $categoryID = 0;
}

list($minsetsizelimit, $maxsetsizelimit) = get_size_limits_rsssets($db);
list($minagelimit, $maxagelimit) = get_age_limits_rsssets($db);

$minsetsizelimit = nearest($minsetsizelimit / (1024 * 1024), FALSE);
$maxsetsizelimit = nearest($maxsetsizelimit / (1024 * 1024), TRUE);
$minagelimit = nearest($minagelimit / (3600 * 24), FALSE);
$maxagelimit = nearest($maxagelimit / (3600 * 24), TRUE);
$orisearch  = utf8_decode($search);
$offset  = get_request('offset', 0);
$minage  = get_request('minage', $minagelimit, 'is_numeric');
$maxage  = get_request('maxage', $maxagelimit, 'is_numeric');
$flag    = get_request('flag', '');
$order   = $oriorder   = get_request('order', '');

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

$minsetsize = get_request('minsetsize', $minsetsize, 'is_numeric');
$maxsetsize = get_request('maxsetsize', $maxsetsize, 'is_numeric');
$maxrating  = get_request('maxrating', 10, 'is_numeric');
$minrating  = get_request('minrating', 0, 'is_numeric');
$setid      = get_request('setid', '');

$subscribedfeeds = subscribed_feeds_select($db, $feed_id, $categoryID, $categories, $userid);

$killflag = FALSE;
if ($flag == 'kill') {
    $killflag = TRUE;
}

$perpage = get_maxperpage($db, $userid);

// make the rss URL
$rss_limit = $perpage;

$url = get_config($db, 'url');
$rssurl = $url . "html/rss.php?type=$rsstype&amp;feed_id=$feed_id&amp;categoryID=$categoryID&amp;limit=$rss_limit&amp;minsize=$minsetsize&amp;maxage=$maxage&amp;userid=$userid";

if ($order == '') {
    $order = map_default_sort($prefs, array('subject'=> 'better_subject'));
}

init_smarty($title, 1, $add_menu);

list($size, $suffix) = format_size($totbin, 'h', '', 1000);
$smarty->assign('rssurl',		$rssurl);
$smarty->assign('total_articles', $size . $suffix);
$smarty->assign('search',       $orisearch);
$smarty->assign('feed_id',      $origfeed_id);
$smarty->assign('offset',       $offset);
$smarty->assign('order',        trim($order));
$smarty->assign('flag',         $flag);
$smarty->assign('USERSETTYPE',	USERSETTYPE_RSS);
$smarty->assign('minage',       $minage);
$smarty->assign('maxage',       $maxage);
$smarty->assign('maxrating',    $maxrating);
$smarty->assign('minrating',    $minrating);
$smarty->assign('perpage',		$perpage);
$smarty->assign('setid',		$setid);
$smarty->assign('minsetsize',   $minsetsize);
$smarty->assign('maxsetsize',   $maxsetsize);
$smarty->assign('minagelimit',	$minagelimit);
$smarty->assign('maxagelimit',	$maxagelimit);
$smarty->assign('minsetsizelimit',	$minsetsizelimit);
$smarty->assign('maxsetsizelimit',	$maxsetsizelimit);
$smarty->assign('minratinglimit',   0);
$smarty->assign('maxratinglimit',   10);
$smarty->assign('subscribedfeeds',  $subscribedfeeds);
$smarty->assign('saved_searches',	$saved_searches);
$smarty->assign('_saved_search',	$saved_search);

$smarty->display('rsssets.tpl');
