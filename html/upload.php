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
 * $LastChangedDate: 2013-09-03 16:28:23 +0200 (di, 03 sep 2013) $
 * $Rev: 2910 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: upload.php 2910 2013-09-03 14:28:23Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$pathupl = realpath(dirname(__FILE__));

require_once "$pathupl/../functions/html_includes.php";

// This is basically a simple wrapper that transforms a file upload to a local file,
// which is then processed as usual in parsenzb.php

if (isset($_FILES['error']) && $_FILES['error'] != 0) {
    die_html('error!?');
}

verify_access($db, urd_modules::URD_CLASS_USENZB|urd_modules::URD_CLASS_DOWNLOAD, FALSE, '', $userid, FALSE);

//challenge::verify_challenge_noreload($_REQUEST['challenge']);

if (empty($_FILES)) {
    die_html(file_upload_error_message(UPLOAD_ERR_INI_SIZE));
}
if ($_FILES['upfile']['error'] !== UPLOAD_ERR_OK) {
    die_html(file_upload_error_message($_FILES['upfile']['error']));
}

$filename = $_FILES['upfile']['tmp_name'];
$orig_filename = $_FILES['upfile']['name'];

function file_upload_error_message($error_code)
{ // xxx fix language shit
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'File upload stopped by extension';
        default:
            return 'Unknown upload error';
    }
}

$_REQUEST['upload'] = $filename;
$_REQUEST['upload_orig_filename'] = $orig_filename;
require 'parsenzb.php';
