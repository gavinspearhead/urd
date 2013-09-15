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
 * $Id: parsenzb.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */
if (!defined ('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
}

$pathpn = realpath(dirname(__FILE__));

require_once "$pathpn/../functions/html_includes.php";

verify_access($db, urd_modules::URD_CLASS_USENZB | urd_modules::URD_CLASS_DOWNLOAD, FALSE, '', $userid, FALSE);

if (isset($_REQUEST['upload'])) {
    $url = trim($_REQUEST['upload']);
    if ($url == '') {
        die_html($LN['error_readnzbfailed']);
    }
    if (!is_uploaded_file($url)) {
        die_html($LN['error_filenotexec'] . '(1)');
    }

    $dlpath = get_dlpath($db) . NZB_PATH;
    if (isset($_REQUEST['upload_orig_filename'])) {
        $file_name = $_REQUEST['upload_orig_filename'];
    } else {
        $file_name = $url;
    }
    if (substr($file_name, -4) == '.nzb') {
        $file_name = trim(substr($file_name, 0, -4));
    }
    $nzb_file = find_unique_name($dlpath, $username . DIRECTORY_SEPARATOR, $file_name, '.nzb', TRUE);
    move_uploaded_file($url, $nzb_file);
    $url = $nzb_file;
} elseif (isset($_REQUEST['url'])) {
    $url = trim($_REQUEST['url']);
    if ($url == '') {
        die_html($LN['error_filenotexec']);
    }
/* Since when don't we allow local files anymore? IMO allow all files to be read, just don't display the content if it's not a valid file... */

    $pattern = '/((https?:\/\/)|(ftps?:\/\/))/i';
    if (preg_match($pattern, $url) == 0) {
        die_html($LN['error_filenotexec'] . '(2)');
    }
} elseif (isset($_REQUEST['file'])) {
    $url = realpath($_REQUEST['file']);
    if ($url == '') {
        die_html($LN['error_readnzbfailed']);
    }
/* This doesn't seem to work right either... needs some testing: */
    $dlpath = get_config($db, 'dlpath');
    if (substr($url, 0, strlen($dlpath)) != $dlpath) {
        die_html($LN['error_filenotexec'] . '(3)');
    }
} else {
    die_html($LN['error_readnzbfailed']);
}

$rprefs = load_config($db);
$uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);

if (!$uc->is_connected()) {
    die_html($LN['error_urddconnect']);
}

$result = $uc->create_download();
if ($result === FALSE) {
    die_html($LN['error_createdlfailed']);
}
list($dlid) = $uc->decode($result);

// Download ID:
$dl_dir = trim(get_post('dl_dir', ''));
$add_setname = (get_post('add_setname', 1)) ? 1 : 0;
$dlname = get_post('setname', '');
if ($dlname != '') {
    $dlname = find_name($db, $dlname);
    set_download_name($db, $dlid, $dlname);
}
$stat_id = add_stat_data($db, stat_actions::DOWNLOAD, 0, $userid);
set_stat_id($db, $dlid, $stat_id);
set_dl_dir($db, $dlid, $dl_dir, $add_setname);
list($timestamp, $time_int) = get_timestamp();

set_start_time($db, $dlid, $time_int);
$url = addslashes($url);
$uc->parse_nzb($url, $dlid, $time_int);
if ($timestamp !== NULL) { // delayed start.... schedule continue
    $uc->schedule(get_command(urdd_protocol::COMMAND_CONTINUE), '"' . get_command(urdd_protocol::COMMAND_DOWNLOAD) . ' ' . $dlid . '"', $timestamp);
}

die_html('OK' . $LN['uploaded'] . ': ' . $dlname);
