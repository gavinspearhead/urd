<?php
/**
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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: admin_config.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathaubl = realpath(dirname(__FILE__));

require_once "$pathaubl/../functions/html_includes.php";

verify_access($db, NULL, TRUE, '', $userid, FALSE);

$perpage = get_maxperpage($db, $userid);
$offset = 0;

$add_menu = array();
$add_menu['actions'][] = new menu_item2 ('import_spots_blacklist', 'import_spots_blacklist', urd_modules::URD_CLASS_SPOTS, '', 'command');
$add_menu['actions'][] = new menu_item2 ('import_spots_whitelist', 'import_spots_whitelist', urd_modules::URD_CLASS_SPOTS, '', 'command');
$add_menu['actions'][] = new menu_item2 ('export_spots_blacklist', 'export_spots_blacklist', urd_modules::URD_CLASS_SPOTS, '', 'command');
$add_menu['actions'][] = new menu_item2 ('export_spots_whitelist', 'export_spots_whitelist', urd_modules::URD_CLASS_SPOTS, '', 'command');

init_smarty($LN['user_lists_title'], 1, $add_menu);
$smarty->assign('perpage',		$perpage);
$smarty->assign('offset',		$offset);
$smarty->assign('status',		'');
$smarty->display('user_blacklist.tpl');
