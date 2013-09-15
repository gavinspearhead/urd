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
 * $Id: index.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$path_idx_std = realpath(dirname(__FILE__));
$process_name = 'urd_web'; // needed for message format in syslog and logging

require_once "$path_idx_std/../functions/defines.php";
require_once "$path_idx_std/../config.php";
require_once "$path_idx_std/../functions/urdversion.php";

if (isset($config['urdweb_logfile'])) {
    $config['log_file'] = $config['urdweb_logfile'];
} else {
    $config['log_file'] = '/dev/null';
}

require_once "$path_idx_std/../functions/functions.php";
require_once "$path_idx_std/../functions/db.class.php";
require_once "$path_idx_std/../functions/urd_log.php";
require_once "$path_idx_std/../functions/web_functions.php";

// initialise some stuff
try {
    $db = connect_db();  // initialise the database
} catch (exception $e) {
    $msg = $e->getMessage();
    die_html("Connection to database failed. $msg\n");
}

require_once "$path_idx_std/../functions/checkauth.php";

// first include all the php files that only define stuff
require_once "$path_idx_std/../functions/exception.php";
require_once "$path_idx_std/../functions/autoincludes.php";
require_once "$path_idx_std/fatal_error.php";

// then execute code we always need
require_once "$path_idx_std/../functions/fix_magic.php";
$index_page = get_pref($db, 'index_page', $userid);
if (strstr($index_page, DIRECTORY_SEPARATOR) !== FALSE) {
    $index_page = '';
}

if ($index_page == '' || !file_exists("$index_page.php")) {
    $index_page = get_pref($db, 'index_page_root', 0);
}
if ($index_page == '' || !file_exists("$index_page.php")) {
    header ('location: browse.php');
} else {
    header("Location: $index_page.php");
}
