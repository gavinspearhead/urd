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
 * $LastChangedDate: 2014-06-07 14:53:28 +0200 (za, 07 jun 2014) $
 * $Rev: 3081 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: newsgroups.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathng = realpath(dirname(__FILE__));

require_once "$pathng/../functions/html_includes.php";

verify_access($db, urd_modules::URD_CLASS_GROUPS, FALSE, '', $userid, FALSE);

$add_menu = array (
    'actions'=>
    array(
        new menu_item2 ('editcategories', 'editcategories', urd_modules::URD_CLASS_GROUPS, '', 'command'),
    )
);

if ($isadmin) {
    $add_menu['actions'][] = new menu_item2('updategroups', 'adminupdatenglist', urd_modules::URD_CLASS_GROUPS, '', 'command');
    $add_menu['actions'][] = new menu_item2('import_groups', 'import_groups', urd_modules::URD_CLASS_GROUPS, '', 'command');
    $add_menu['actions'][] = new menu_item2('export_groups', 'export_groups', urd_modules::URD_CLASS_GROUPS, '', 'command');
} elseif (urd_user_rights::is_updater($db, $userid)) {
    $add_menu['actions'][] = new menu_item2('updategroups', 'adminupdatenglist', urd_modules::URD_CLASS_GROUPS, '', 'command');
}

$search = utf8_decode(trim(get_request('search', '')));
$search_all = get_post('search_all', '');

init_smarty($LN['ng_title'], 1, $add_menu);
$smarty->assign(array(
            'search' =>  $search,
            'search_all' =>  $search_all));

$smarty->display('newsgroups.tpl');
