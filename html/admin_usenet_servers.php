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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: admin_usenet_servers.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathvu = realpath(dirname(__FILE__));

require_once "$pathvu/../functions/html_includes.php";

verify_access($db, NULL, TRUE, '', $userid, FALSE);

$add_menu = array (
    'actions'=>
    array(
        new menu_item2('add_server','usenet_addnew',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('import_servers','import_servers',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('export_servers','export_servers',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('autoconfig','autoconfig',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('autoconfig_ext','autoconfig_ext',urd_modules::URD_CLASS_GENERIC, '', 'command'),
    )
);

init_smarty($LN['usenet_title'], 1, $add_menu);

$smarty->display('admin_usenet_servers.tpl');
