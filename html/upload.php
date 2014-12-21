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

verify_access($db, urd_modules::URD_CLASS_USENZB|urd_modules::URD_CLASS_DOWNLOAD, FALSE, '', $userid, FALSE);
try {
    list($_REQUEST['upload'], $_REQUEST['upload_orig_filename']) = get_uploaded_files();
} catch (exception $e){ 
    die_html($e->getMessage());
}

require 'parsenzb.php';
