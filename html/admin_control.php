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
 * $LastChangedDate: 2014-05-29 01:03:02 +0200 (do, 29 mei 2014) $
 * $Rev: 3058 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: admin_control.php 3058 2014-05-28 23:03:02Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$pathadctl = realpath(dirname(__FILE__));

require_once "$pathadctl/../functions/html_includes.php";

verify_access($db, NULL, TRUE, '', $userid, FALSE);

$add_menu = [
    'actions'=> [
        new menu_item2('sendsetinfo','adminsendsetinfo',urd_modules::URD_CLASS_SYNC, '', 'command'),
        new menu_item2('getsetinfo','admingetsetinfo',urd_modules::URD_CLASS_SYNC, '', 'command'),
        new menu_item2('optimise','adminoptimisedb',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('cleandir','admincleandir',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('checkversion','admincheckversion',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('autoconfig','adminfindservers',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('autoconfig_ext','adminfindservers_ext',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('updategroups', 'adminupdatenglist',urd_modules::URD_CLASS_GROUPS, '', 'command'),
        new menu_item2('updateblacklist', 'adminupdateblacklist', urd_modules::URD_CLASS_SPOTS, '', 'command'),
        new menu_item2('updatewhitelist', 'adminupdatewhitelist', urd_modules::URD_CLASS_SPOTS, '', 'command'),
        new menu_item2('update_db.php', 'update_database',urd_modules::URD_CLASS_GENERIC, '', 'jump'),
        new menu_item2 ('cleanall', 'admincleandb', urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('reload','adminrestart',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('import_all_settings','adminimport_all',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('export_all_settings','adminexport_all',urd_modules::URD_CLASS_GENERIC, '', 'command'),
    ]
];

init_smarty($LN['control_title'], 1, $add_menu);

$smarty->display('admin_control.tpl');
