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
 * $LastChangedDate: 2011-01-23 01:01:05 +0100 (Sun, 23 Jan 2011) $
 * $Rev: 2043 $
 * $Author: gavinspearhead $
 * $Id: browse.php 2043 2011-01-23 00:01:05Z gavinspearhead $
 */
if (!defined('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
}

$pathidx = realpath(dirname(__FILE__));
require_once "$pathidx/../functions/html_includes.php";

if (!isset($_SESSION['setdata']) || !is_array($_SESSION['setdata'])) {
    $_SESSION['setdata'] = array();
}

verify_access($db, urd_modules::URD_CLASS_SPOTS, FALSE, '', $userid, FALSE);
$add_menu = NULL;

if ($isadmin) {
    $add_menu = array (
        'actions'=>
        array(
            new menu_item2 ('updatespots', 'adminupdate_spots', urd_modules::URD_CLASS_SPOTS, '', 'command'),
            new menu_item2 ('updatespotscomments', 'adminupdate_spotscomments', urd_modules::URD_CLASS_SPOTS, '', 'command'),
            new menu_item2 ('updatespotsimages', 'adminupdate_spotsimages', urd_modules::URD_CLASS_SPOTS, '', 'command'),
            new menu_item2 ('expirespots', 'expire', urd_modules::URD_CLASS_SPOTS, '', 'command'),
            new menu_item2 ('purgespots', 'purge', urd_modules::URD_CLASS_SPOTS, $LN['adminpurge_spots'], 'command')
        )
    );
} elseif (urd_user_rights::is_updater($db, $userid)) {
    $add_menu = array (
        'actions'=>
        array(
            new menu_item2 ('updatespots', 'adminupdate_spots', urd_modules::URD_CLASS_SPOTS, '', 'command'),
            new menu_item2 ('updatespotscomments', 'adminupdate_spotscomments', urd_modules::URD_CLASS_SPOTS, '', 'command'),
            new menu_item2 ('updatespotsimages', 'adminupdate_spotimages', urd_modules::URD_CLASS_SPOTS, '', 'command'),
            new menu_item2 ('expirespots', 'expire', urd_modules::URD_CLASS_SPOTS, '', 'command'),
        )
    );
}

if (urd_user_rights::is_poster($db, $userid)) {
    $add_menu['actions'][] = new menu_item2('post_spot', 'transfers_post_spot', urd_modules::URD_CLASS_POST | urd_modules::URD_CLASS_SPOTS, '', 'command');
}

$add_menu['actions'][] = new menu_item2 ('add_search', 'add_search', urd_modules::URD_CLASS_SPOTS, '', 'command');
$add_menu['actions'][] = new menu_item2 ('delete_search', 'delete_search', urd_modules::URD_CLASS_SPOTS, '', 'command');

$search = utf8_decode(html_entity_decode(trim(get_request('search', ''))));
$type = USERSETTYPE_SPOT;

$saved_searches = new saved_searches($userid);
$saved_searches->load($db);

$saved_search = get_request('saved_search', '');
if ($saved_search == '' && $_POST == array() && $_GET == array()) {
    $saved_search = get_pref($db, 'default_spot', $userid, '');
}
$ori_categoryID = $categoryID = get_request('categoryID', '', 'is_numeric');
try {
    $ori_categoryID = $categoryID = $saved_searches->get_search_by_name($saved_search, $type);
} catch (exception $e) { // ignore
    $saved_search = '';
}
$categories = SpotCategories::get_categories();

if (!in_array($categoryID, SpotCategories::get_category_ids())) {
   $ori_categoryID = $categoryID = '';
}

$_categories = get_used_categories_spots($db);
$categories = array();
foreach ($_categories as $key => $cat) {
    $cat['name'] = $LN[$cat['name']];
    $categories[$key] = $cat;
}

uasort($categories, 'spot_name_cmp');

$adult = urd_user_rights::is_adult($db, $userid);
$subcats = SpotCategories::get_allsubcats($adult);

list($select_subcats, $not_subcats, $off_subcats) = get_subcats_requests();
$searched_subcats = array();
foreach ($select_subcats as $key => $value) {
    $searched_subcats[$key] = $value['value'] ;
    $smarty->assign($key, $value['value']);
}
foreach ($not_subcats as $key => $value) {
    $searched_subcats[$key] = $value['value'] ;
    $smarty->assign($key, $value['value']);
}
if ($searched_subcats == array()) {
$smarty->assign('searched_subcats', '');
} else {
$smarty->assign('searched_subcats', json_encode($searched_subcats));
}
$subscribed_categories = subscribed_spots_select($categoryID, $categories);
$totbin = get_total_spots($db);

$title = $LN['browse_download'] . ' ' . $LN['from'] . ' ' . $totbin . ' ' . $LN['sets'] . '!'; // too fix

// Get the required values:
// ori = passed back to the front-end, these are used in the database (backend):

list($minsetsizelimit, $maxsetsizelimit) = get_size_limits_spots($db);
list($minagelimit, $maxagelimit) = get_age_limits_spots($db);
$minsetsizelimit = nearest($minsetsizelimit / (1024 * 1024), FALSE);
$maxsetsizelimit = nearest($maxsetsizelimit / (1024 * 1024), TRUE);
$minagelimit = nearest($minagelimit / (3600 * 24), FALSE);
$maxagelimit = nearest($maxagelimit / (3600 * 24), TRUE);

$offset  = get_request('offset', 0, 'is_numeric');
$order   = get_request('order', '');
$flag    = get_request('flag', '');
$maxage  = get_request('maxage', $maxagelimit, 'is_numeric');
$minage  = get_request('minage', $minagelimit, 'is_numeric');
$minsetsize = get_pref($db, 'minsetsize', $userid, 0) / (1024 * 1024);
$maxsetsize = get_pref($db, 'maxsetsize', $userid, 0) / (1024 * 1024);
if (!is_numeric($maxsetsize) || $maxsetsize <= 0) {
    $maxsetsize = $maxsetsizelimit;
}
if (!is_numeric($minsetsize) || $minsetsize <= 0) {
    $minsetsize = $minsetsizelimit;
}
$minsetsize = get_request('minsetsize', $minsetsize, 'is_numeric');
$maxsetsize = get_request('maxsetsize', $maxsetsize, 'is_numeric');
$poster     = get_request('poster', '');
$maxrating  = get_request('maxrating', 10, 'is_numeric');
$minrating  = get_request('minrating', 0, 'is_numeric');
$maxcomplete = get_request('maxcomplete', '', 'is_numeric');
$mincomplete = get_request('mincomplete', '', 'is_numeric');


if ($order == '') {
    $order = map_default_sort($prefs, array('subject' => 'title', 'date' => 'stamp', 'better_subject' => 'title'));
}
$spotid = get_request('spotid', '');

// make the rss URL
$perpage = get_maxperpage($db, $userid);
$rssurl = '';

$rss_limit = $perpage;

$rss_minsetsize = $minsetsize;
$rss_maxage = $maxage;
$url = get_config($db, 'baseurl');
$rssurl = $url . "html/rss.php?type=$type&amp;categoryID=$categoryID&amp;limit=$rss_limit&amp;minsize=$rss_minsetsize&amp;maxage=$rss_maxage&amp;userid=$userid";

$saved_searches = $saved_searches->get_all_names($type);
init_smarty($title, 1, $add_menu);

list($size, $suffix) = format_size($totbin, 'h', '', 1000);
$smarty->assign('total_articles', $size . $suffix);
$smarty->assign('search',		$search);
$smarty->assign('isadmin',		$isadmin);
$smarty->assign('categoryID',	$ori_categoryID);
$smarty->assign('offset',		$offset);
$smarty->assign('maxage',		$maxage);
$smarty->assign('minage',		$minage);
$smarty->assign('poster',		$poster);
$smarty->assign('maxrating',	$maxrating);
$smarty->assign('minrating',	$minrating);
$smarty->assign('maxratinglimit',10);
$smarty->assign('minratinglimit',0);
$smarty->assign('maxcomplete',	$maxcomplete);
$smarty->assign('mincomplete',	$mincomplete);
$smarty->assign('order',		trim($order));
$smarty->assign('spotid',		$spotid);
$smarty->assign('catid',		'');
$smarty->assign('flag',			$flag);
$smarty->assign('perpage',		$perpage);
$smarty->assign('minsetsize',	$minsetsize);
$smarty->assign('maxsetsize',	$maxsetsize);
$smarty->assign('maxagelimit',	$maxagelimit);
$smarty->assign('minagelimit',	$minagelimit);
$smarty->assign('maxsetsizelimit',	$maxsetsizelimit);
$smarty->assign('minsetsizelimit',	$minsetsizelimit);
$smarty->assign('saved_searches',	$saved_searches);
$smarty->assign('_saved_search',	$saved_search);
$smarty->assign('USERSETTYPE',		$type);
$smarty->assign('rssurl',		$rssurl);
$smarty->assign('categories',	$subscribed_categories);
$smarty->assign('subcats',	    $subcats);

$smarty->display('spots.tpl');
