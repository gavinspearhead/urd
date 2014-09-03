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
 * $LastChangedDate: 2014-05-19 23:50:53 +0200 (ma, 19 mei 2014) $
 * $Rev: 3043 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: getfile.php 3043 2014-05-19 21:50:53Z gavinspearhead@gmail.com $
 */

@define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathgf = realpath(dirname(__FILE__));

require_once "$pathgf/../functions/html_includes.php";

$raw = isset($_GET['raw']) ? TRUE : FALSE;
$preview = get_request('preview', 0) ? TRUE : FALSE;
$closelink = ($preview ? 'close' : 'back');

$is_admin = urd_user_rights::is_admin($db, $userid);

$file = get_request('file', FALSE);
if ($file !== FALSE) {
    $basename =  basename($file);
    $path = my_realpath(dirname($file));
    add_dir_separator($path);
    $file = $path . $basename;
}
if ($file === FALSE) {
    throw new exception($LN['error_filenotfound'] . htmlentities(": '$file'", ENT_QUOTES, 'UTF-8'));
}

$dlpath = get_dlpath($db);
$done_path = $dlpath . DONE_PATH . $username . DIRECTORY_SEPARATOR;
$preview_path = $dlpath . PREVIEW_PATH . $username . DIRECTORY_SEPARATOR;
$cache_path = $dlpath . CACHE_PATH . DIRECTORY_SEPARATOR;

if ($is_admin) {
    if (substr($file, 0, strlen($dlpath)) != $dlpath) {
        throw new exception($LN['error_filenotallowed'] . htmlentities(": $file", ENT_QUOTES, 'UTF-8'));
    }
} else {
    if (substr($file, 0, strlen($done_path)) != $done_path && substr($file, 0, strlen($preview_path)) != $preview_path && substr($file, 0, strlen($cache_path)) != $cache_path) {
        throw new exception($LN['error_filenotallowed'] . htmlentities(": $file", ENT_QUOTES, 'UTF-8'), NULL, NULL, $closelink);
    }
}
if (!file_exists($file) || !is_readable($file)) {
    throw new exception($LN['error_filenotfound'] . htmlentities(": $file", ENT_QUOTES, 'UTF-8'));
}
$ext = strtolower(ltrim(strrchr($file, '.'), '.'));
$type = real_mime_content_type($db, $file, TRUE);
$filename = substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1);
$size = filesize($file);

if (in_array($ext, array('nfo', 'log'))) {
    $type = 'text/html';
    $size += strlen('<html><body><pre></pre></body></html>');
}

$is_image = is_image_file($ext);
$is_text = is_text_file($ext);

$maxfilesize = get_config($db, 'maxfilesize');
if ($maxfilesize != 0 && $size > $maxfilesize) {
    throw new exception($LN['error_filetoolarge']);
}

add_stat_data($db, stat_actions::WEBVIEW, $size, $userid);

if (($raw === TRUE && $is_image) || (!$is_image && !$is_text)) {
    header("Content-Type: $type");
    header("Content-Length: $size");
    header('Content-Description: URD Generated Data');
    if ($type != 'text/plain' && !$is_image) {
        header("Content-Disposition: attachment; filename=\"$filename\"");
    }
}

// turn off output buffering otherwise large files will be hit by the memory limit
set_time_limit(0);
if ($is_text && $size < (1024 * 1024)) {
    $text = file($file);
    if ($ext == 'nfo') {
        header('Content-Type: text/html; charset=CP866'); // For fancy logos
    }

    // To prevent firefox from interpreting this as a non-text-file BAD FIREFOX BAD
    echo '<html><body><pre>';
    foreach ($text as $line) {
        $line = htmlentities($line, ENT_QUOTES);
        if (preg_match('|(http://[\w.+/?\-&;%=]*/?)|', $line, $matches) == 1) {
            $url = trim($matches[1]);
        $line = str_replace($url, "<a href=\"$url\">$url</a>", $line);
    }
    echo $line;
    }
    echo '</pre></body></html>';
} elseif ($is_image && $raw === FALSE) {
    $idx = get_request('idx', '');
    init_smarty(ltrim($filename, DIRECTORY_SEPARATOR), 1);

    $smarty->assign('preview', 0);

    list($size, $suffix) = format_size($size, 'h', 'B', 1024, 0);

    $smarty->assign('file', $file);
    $smarty->assign('idx', $idx);
    $smarty->display('getfile.tpl');
} else {
    @ob_end_flush();
    readfile($file);
}

die(); // make sure there is no more content appended
