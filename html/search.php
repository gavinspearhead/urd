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
 * $LastChangedDate: 2013-09-03 16:28:23 +0200 (di, 03 sep 2013) $
 * $Rev: 2910 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: search.php 2910 2013-09-03 14:28:23Z gavinspearhead@gmail.com $
 */
if (!defined('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
}

$pathsrc = realpath(dirname(__FILE__));

require_once "$pathsrc/../functions/html_includes.php";

verify_access($db, urd_modules::URD_CLASS_RSS|urd_modules::URD_CLASS_GROUPS|urd_modules::URD_CLASS_SPOTS, FALSE, '', $userid, FALSE);

$saved_searches = new saved_searches($userid);
$saved_searches->load($db);

$new_ss = array();
foreach ($saved_searches->get() as $key => $ss) {
    if (isset($ss ['cat'])) {
        $new_ss[ $ss ['cat'] ][] = array ('name' => $key);
    }
}

$saved_searches = $new_ss;

$show_groups = $show_rss = 0;
$subscribedfeeds = $subscribedgroups= array();
init_smarty($LN['menusearch'], 1);

$smarty->assign('saved_searches', $saved_searches);

if (urd_modules::check_module_enabled($db, urd_modules::URD_CLASS_GROUPS)) {
    list($minsetsizelimit, $maxsetsizelimit) = get_size_limits_groups($db);
    list($minagelimit, $maxagelimit) = get_age_limits_groups($db);
    $minsetsizelimit = nearest($minsetsizelimit / (1024 * 1024), FALSE);
    $maxsetsizelimit = nearest($maxsetsizelimit / (1024 * 1024), TRUE);
    $minagelimit = nearest($minagelimit / (3600 * 24), FALSE);
    $maxagelimit = nearest($maxagelimit / (3600 * 24), TRUE);
    $categories = get_used_categories_group($db, $userid);
    $subscribedgroups = subscribed_groups_select($db, '', '', $categories, $userid);
    $totbin = get_total_ng_sets($db);
    list($groups_size, $groups_suffix) = format_size($totbin, 'h', '', 1000);
    $smarty->assign('groups_total_articles', $groups_size . $groups_suffix);
    $smarty->assign('subscribedgroups',	     $subscribedgroups);
    $smarty->assign('groupminagelimit',	$minagelimit);
    $smarty->assign('groupmaxagelimit',	$maxagelimit);
    $smarty->assign('groupminsetsizelimit',	$minsetsizelimit);
    $smarty->assign('groupmaxsetsizelimit',	$maxsetsizelimit);
    $smarty->assign('groupminratinglimit',0);
    $smarty->assign('groupmaxratinglimit',10);
    $smarty->assign('groupmincompletelimit',	0);
    $smarty->assign('groupmaxcompletelimit',	100);
}

if (urd_modules::check_module_enabled($db, urd_modules::URD_CLASS_RSS)) {
    list($minsetsizelimit, $maxsetsizelimit) = get_size_limits_rsssets($db);
    list($minagelimit, $maxagelimit) = get_age_limits_rsssets($db);
    $minsetsizelimit = nearest($minsetsizelimit / (1024 * 1024), FALSE);
    $maxsetsizelimit = nearest($maxsetsizelimit / (1024 * 1024), TRUE);
    $minagelimit = nearest($minagelimit / (3600 * 24), FALSE);
    $maxagelimit = nearest($maxagelimit / (3600 * 24), TRUE);
    $categories = get_used_categories_rss($db, $userid);
    $subscribedfeeds = subscribed_feeds_select($db, 0, '',  $categories, $userid);
    $totbin = get_total_rss_sets($db);
    list($rss_size, $rss_suffix) = format_size($totbin, 'h', '', 1000);
    $smarty->assign('rss_total_articles',   $rss_size . $rss_suffix);
    $smarty->assign('subscribedfeeds',      $subscribedfeeds);
    $smarty->assign('rssminagelimit',	    $minagelimit);
    $smarty->assign('rssmaxagelimit',	    $maxagelimit);
    $smarty->assign('rssminsetsizelimit',	$minsetsizelimit);
    $smarty->assign('rssmaxsetsizelimit',	$maxsetsizelimit);
    $smarty->assign('rssminratinglimit',0);
    $smarty->assign('rssmaxratinglimit',10);
}

if (urd_modules::check_module_enabled($db, urd_modules::URD_CLASS_SPOTS)) {

    list($minsetsizelimit, $maxsetsizelimit) = get_size_limits_spots($db);
    list($minagelimit, $maxagelimit) = get_age_limits_spots($db);
    $minsetsizelimit = nearest($minsetsizelimit / (1024 * 1024), FALSE);
    $maxsetsizelimit = nearest($maxsetsizelimit / (1024 * 1024), TRUE);
    $minagelimit = nearest($minagelimit / (3600 * 24), FALSE);
    $maxagelimit = nearest($maxagelimit / (3600 * 24), TRUE);
    $_categories = get_used_categories_spots($db);
    $spot_categories = array();
    foreach ($_categories as $key => $cat) {
        $cat['name'] = $LN[$cat['name']];
        $spot_categories[$key] = $cat;
    }

    uasort($spot_categories, 'spot_name_cmp');
    $adult = urd_user_rights::is_adult($db, $userid);
    $spot_subcats = SpotCategories::get_allsubcats($adult);

    $minsetsize = get_pref($db, 'minsetsize', $userid, 0) / (1024 * 1024);
    $maxsetsize = get_pref($db, 'maxsetsize', $userid, 0) / (1024 * 1024);
    if ($maxsetsize <= 0) {
        $maxsetsize = $maxsetsizelimit;
    }
    if ($minsetsize <= 0) {
        $minsetsize = $minsetsizelimit;
    }
    $spot_categories = subscribed_spots_select('', $spot_categories);
    $spots_totbin = get_total_spots($db);
    list($spots_size, $spots_suffix) = format_size($spots_totbin, 'h', '', 1000);
    $smarty->assign('spots_total_articles', $spots_size . $spots_suffix);
    $smarty->assign('spot_categories',	    $spot_categories);
    $smarty->assign('spot_subcats',	        $spot_subcats);
    $smarty->assign('spotmaxagelimit',	    $maxagelimit);
    $smarty->assign('spotminagelimit',	    $minagelimit);
    $smarty->assign('spotminsetsize',	    $minsetsize);
    $smarty->assign('spotmaxsetsize',	    $maxsetsize);
    $smarty->assign('spotmaxsetsizelimit',	$maxsetsizelimit);
    $smarty->assign('spotminsetsizelimit',	$minsetsizelimit);
    $smarty->assign('spotminratinglimit',0);
    $smarty->assign('spotmaxratinglimit',10);
}

$smarty->display('search.tpl');
