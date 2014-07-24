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
 * $Id: viewfiles.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathvf = realpath(dirname(__FILE__));

require_once "$pathvf/../functions/html_includes.php";

verify_access($db, urd_modules::URD_CLASS_VIEWFILES, FALSE, '', $userid, FALSE);

$allow_edit = get_config($db, 'webeditfile', 0);
$add_menu = NULL;

if ($allow_edit && urd_user_rights::is_file_editor($db, $userid)) {
    $add_menu = array (
        'actions'=>
        array(
            new menu_item2 ('new_file', 'viewfiles_newfile', urd_modules::URD_CLASS_GENERIC, '', 'command'),
        )
    );
}

init_smarty($LN['viewfiles_title'], 1, $add_menu);

$perpage = get_maxperpage($db, $userid);
$dir = get_request('dir', '');

$smarty->assign('directory',    $dir);
$smarty->assign('maxstrlen',    $prefs['maxsetname']);
$smarty->assign('perpage',		$perpage);
$smarty->display('viewfiles.tpl');
