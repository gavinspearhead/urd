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
 * $Id: prefs.php 2921 2013-09-04 21:41:51Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathpr = realpath(dirname(__FILE__));

require_once "$pathpr/../functions/html_includes.php";

$add_menu = array (
    'actions'=>
    array(
        new menu_item2 ('import_prefs','settings_import',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2 ('export_prefs','settings_export',urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2 ('reset_prefs','reset',urd_modules::URD_CLASS_GENERIC, $LN['reset'] . ' ' . strtolower( $LN['pref_title']) , 'command'),
    )
);

init_smarty($LN['pref_title'], 1, $add_menu);
$smarty->assign('source', 'prefs');
$smarty->display('settings.tpl');
