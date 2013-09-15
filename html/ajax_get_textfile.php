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

require_once "$pathgf/../functions/html_includes.php";

$idx = get_request('idx', '');
$preview = get_request('preview', 0) ? TRUE : FALSE;
$closelink = ($preview? 'close' : 'back');
$is_admin = urd_user_rights::is_admin($db, $userid);
try {
    $file = get_request('file', FALSE);
    if ($file !== FALSE) {
        $file = my_realpath($file) ;
    }
    if ($file === FALSE) {
        throw new exception($LN['error_filenotfound'] . htmlentities(": $file", ENT_QUOTES, 'UTF-8'));
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
            throw new exception($LN['error_filenotallowed'] . htmlentities(": $file", ENT_QUOTES, 'UTF-8'));
        }
    }
} catch (exception $e) {
    throw new exception($e->getMessage());
}
if (!file_exists($file) || !is_readable($file)) {
    throw new exception($LN['error_filenotfound'] . htmlentities(": $file", ENT_QUOTES, 'UTF-8'));
}

$filename = substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1);
$size = filesize($file);

init_smarty(ltrim($filename, DIRECTORY_SEPARATOR), 1);
$ext = strtolower(ltrim(strrchr($filename, '.'), '.'));
$text = file($file);
$output = '';
if ($ext == 'nfo') {
    header('Content-Type: text/html; charset=CP866'); // For fancy logos
}

// To prevent firefox from interpreting this as a non-text-file BAD FIREFOX BAD
foreach ($text as $line) {
    $line = htmlentities($line, ENT_QUOTES);
    if (preg_match('|(https?://[\w.+/?\-&;%=]*/?)|', $line, $matches) == 1) {
        $url = trim($matches[1]);
        $line = str_replace($url, "<a href=\"$url\" target=\"_new\">$url</a>", $line);
    }
    $output .=  $line;
}

list($size, $suffix) = format_size($size, 'h', 'B', 1024, 0);
$base_url = get_config($db, 'url');
$smarty->assign('preview', $preview);
$smarty->assign('output', $output);
$smarty->assign('directory', dirname($file));
$smarty->assign('size', $size . $suffix);
$smarty->assign('url', $base_url . 'html/getfile.php?raw=1&amp;file=' . urlencode($file));
$smarty->display('ajax_get_textfile.tpl');
