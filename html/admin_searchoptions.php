<?php

/*
 *  vim:ts=4:expandtab:cindent
 *  This file is part of Urd.
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
 * $Id: admin_searchoptions.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathvb = realpath(dirname(__FILE__));

require_once "$pathvb/../functions/html_includes.php";

verify_access($db, NULL, TRUE, '', $userid, FALSE);

$add_menu = [
    'actions'=> [
        new menu_item2('add_button', 'usenet_addnew', urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('import_buttons', 'import_buttons', urd_modules::URD_CLASS_GENERIC, '','command'),
        new menu_item2('export_buttons', 'export_buttons', urd_modules::URD_CLASS_GENERIC, '','command'),
    ]
];

$title = $LN['buttons_title'];
init_smarty($title, 1, $add_menu);
$smarty->display('admin_searchoptions.tpl');
