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
 * $LastChangedDate: 2012-09-11 14:39:24 +0200 (di, 11 sep 2012) $
 * $Rev: 2662 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: getfile.php 2662 2012-09-11 12:39:24Z gavinspearhead@gmail.com $
 */

@define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathgf = realpath(dirname(__FILE__));

require_once "$pathgf/../functions/ajax_includes.php";

$idx = get_request('idx', '');
$preview = get_request('preview', 0) ? TRUE : FALSE;
$closelink = ($preview? 'close' : 'back');
$is_admin = urd_user_rights::is_admin($db, $userid);
try {
    $file = get_request('file', FALSE);
    if ($file !== FALSE) {
        $file = my_realpath($file);
    }

    $dlpath = get_dlpath($db);
    $done_path = $dlpath . DONE_PATH . $username . DIRECTORY_SEPARATOR;
    $preview_path = $dlpath . PREVIEW_PATH . $username . DIRECTORY_SEPARATOR;

    if ($is_admin) {
        if (substr($file, 0, strlen($dlpath)) != $dlpath) {
            throw new exception($LN['error_filenotallowed'] . htmlentities(": $file", ENT_QUOTES, 'UTF-8'));
        }
    } else {
        if (substr($file, 0, strlen($done_path)) != $done_path && substr($file, 0, strlen($preview_path)) != $preview_path) {
            throw new exception($ln['error_filenotallowed'] . htmlentities(": $file", ENT_QUOTES, 'UTF-8'));
        }
    }
} catch (exception $e) {
    if ($e->getCode() == ERR_PATH_NOT_FOUND) {
        throw new exception($LN['error_filenotfound'] . ' ' . $file);
    } else {
        throw new exception ($LN['error_nodlpath']);
    }
}
if (!file_exists($file) || !is_readable($file)) {
    throw new exception ($LN['error_filenotfound'] . htmlentities(": $file", ENT_QUOTES, 'UTF-8'));
}

stored_files::set_cache_dir(get_config($db, 'dlpath') . DIRECTORY_SEPARATOR . FILELIST_CACHE_PATH);
$filename = substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1);
$size = filesize($file);
$def_width = get_request('width', 800);
$def_height = get_request('height', 600);
$i_size = @getimagesize($file);
if ($i_size === FALSE) {
    throw new exception($LN['error_invalidimage']);
}
$width = $i_size[0];
$height = $i_size[1];
init_smarty(ltrim($filename, DIRECTORY_SEPARATOR), 1);
if ($width > $def_width && $def_width > 0) {
    $f = $width / $def_width;
    $height /= $f;
    $width = $def_width;
}
if ($height > $def_height && $def_height > 0) {
    $f = $height / $def_height;
    $width /= $f;
    $height = $def_height;
}

$width = round($width);
$height = round($height);

if (!stored_files::check_path(dirname($file))) {
    stored_files::reset();
}
$next = stored_files::find_next($idx, 'image');
$previous = stored_files::find_previous($idx, 'image');
$first = stored_files::find_first($idx, 'image');
$last = stored_files::find_last($idx, 'image');
list($size, $suffix) = format_size($size, 'h', 'B', 1024, 0);
$base_url = get_config($db, 'url');
$smarty->assign('nextidx', $next);
$smarty->assign('lastidx', $last);
$smarty->assign('previousidx', $previous);
$smarty->assign('firstidx', $first);
$smarty->assign('current', $idx);
$smarty->assign('preview', $preview);
$smarty->assign('next', stored_files::get_file($next));
$smarty->assign('last', stored_files::get_file($last));
$smarty->assign('previous', stored_files::get_file($previous));
$smarty->assign('first', stored_files::get_file($first));
$smarty->assign('directory', dirname($file));
$smarty->assign('width', round($width));
$smarty->assign('height', round($height));
$smarty->assign('size', $size . $suffix);
$smarty->assign('url', $base_url . 'html/getfile.php?raw=1&file=' . urlencode($file));
$smarty->display('ajax_get_image.tpl');
