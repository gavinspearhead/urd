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
 * $Id: transfers.php 3058 2014-05-28 23:03:02Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathtr = realpath(dirname(__FILE__));

require_once "$pathtr/../functions/html_includes.php";

verify_access($db, urd_modules::URD_CLASS_DOWNLOAD | urd_modules::URD_CLASS_POST | urd_modules::URD_CLASS_USENZB, FALSE, '', $userid, FALSE);

$add_menu = array (
    'actions'=>
    array(
        new menu_item2('getnzb', 'transfers_importnzb', urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('post', 'transfers_post', urd_modules::URD_CLASS_POST, '', 'command'),
        new menu_item2('post_spot', 'transfers_post_spot', urd_modules::URD_CLASS_POST |urd_modules::URD_CLASS_SPOTS, '', 'command'),
        new menu_item2('continueall', 'transfers_continueall', urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('pauseall', 'transfers_pauseall', urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2('cleandb', 'transfers_clearcompleted', urd_modules::URD_CLASS_GENERIC, '', 'command'),
    )
);

$poster = urd_user_rights::is_poster($db, $userid);

$active_tab = get_request('active_tab', '');

if ($active_tab == '') {
    $active_tab = get_session('transfers', 'downloads');
}

init_smarty($LN['transfers_title'], 1, $add_menu);
$smarty->assign(array(
    'poster' =>       	    $poster ? 1 : 0,
    'active_tab' =>         $active_tab,
    'offline_message' =>    $LN['enableurddfirst']));
$smarty->display('transfers.tpl');
