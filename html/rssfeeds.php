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
 * $LastChangedDate: 2013-09-04 23:41:51 +0200 (wo, 04 sep 2013) $
 * $Rev: 2921 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: rssfeeds.php 2921 2013-09-04 21:41:51Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathng = realpath(dirname(__FILE__));

require_once "$pathng/../functions/html_includes.php";
require_once "$pathng/../functions/periods.php";

verify_access($db, urd_modules::URD_CLASS_RSS, FALSE, '', $userid);

$add_menu = array (
    'actions'=>
        array(
            new menu_item2 ('editcategories', 'editcategories', urd_modules::URD_CLASS_RSS, '', 'command'),
        )
);
if ($isadmin) {
    $add_menu['actions'][] = new menu_item2 ('new_rss', 'feeds_addfeed', urd_modules::URD_CLASS_RSS, '', 'command');
    $add_menu['actions'][] = new menu_item2 ('import_rss', 'import_feeds', urd_modules::URD_CLASS_RSS, '', 'command');
    $add_menu['actions'][] = new menu_item2 ('export_rss', 'export_feeds', urd_modules::URD_CLASS_RSS, '', 'command');
}

$search = utf8_decode(trim(get_request('search', '')));
$search_all = get_request('search_all', '1');
init_smarty($LN['feeds_title'], 1, $add_menu);
$smarty->assign('search_all',   $search_all);
$smarty->assign('search',	    $search);

$smarty->display('rssfeeds.tpl');
