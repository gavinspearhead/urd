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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_show_upload.php 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';
$pathadt = realpath(dirname(__FILE__));

require_once "$pathadt/../functions/ajax_includes.php";

$localfile = '';
$dir = get_request('dir', '');
$filename = get_request('filename', '');
$setname = '';
if ($dir != '' && $filename != '') {
    $localfile = $dir . $filename;
    $setname = substr($filename, 0, strpos($filename, '.'));
}

$download_delay = get_pref($db, 'download_delay', $userid, 0);
if (is_numeric($download_delay) && ($download_delay > 0)) {
    $download_delay = "+$download_delay minutes";
} else {
    $download_delay = '';
}

$directories = get_directories($db, $userid);
$add_setname = get_pref($db, 'add_setname', $userid) ? 1 : 0;
list($dl_dir) = get_user_dlpath($db, FALSE, 0, NULL, $userid, '', 'DOWNLOAD');
$base_dlpath = get_dlpath($db);
$base_dlpath = $base_dlpath . DONE_PATH . $username . DIRECTORY_SEPARATOR;
$dl_dir = substr($dl_dir, strlen($base_dlpath));

init_smarty('', 0);

if (!$smarty->getTemplateVars('urdd_online')) {
    throw new exception($LN['urdddisabled']);
} else {
    $smarty->assign('localfile', $localfile);
    $smarty->assign('setname', $setname);
    $smarty->assign('download_delay', $download_delay);
    $smarty->assign('add_setname',  $add_setname);
    $smarty->assign('dl_dir', $dl_dir);
    $smarty->assign('directories', $directories);
    $smarty->display('ajax_show_upload.tpl');
}
