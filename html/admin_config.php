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
 * $Id: admin_config.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathadc = realpath(dirname(__FILE__));

require_once "$pathadc/../functions/html_includes.php";

verify_access($db, NULL, TRUE, '', $userid, FALSE);

$add_menu = array (
    'actions'=>
    array(
        new menu_item2('import_config', 'settings_import', urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('export_config', 'settings_export', urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('reset_config', 'reset', urd_modules::URD_CLASS_GENERIC, $LN['reset'] . ' ' . $LN['config_title'], 'command'),
    )
);

init_smarty($LN['config_title'], 1, $add_menu);
$smarty->assign('source', 'config');
$smarty->display('settings.tpl');
