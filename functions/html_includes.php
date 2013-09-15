<?php

/*
 *  This file is part of Urd.
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
 * $Id: html_includes.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */

//ini_set('error_reporting', E_ALL|E_STRICT|E_DEPRECATED);
$time_a = microtime(TRUE);

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

define ('SKIP_LANG', 1);
// Is URD actually installed?
if (!file_exists('../.installed')) {
    header('Location: ../install/install.php');
}

$process_name = 'urd_web'; // needed for message format in syslog and logging

$pathhtmli = realpath(dirname(__FILE__));
require_once $pathhtmli . '/defines.php';
require_once $pathhtmli . '/../config.php';

if (isset($config['urdweb_logfile'])) {
    $config['log_file'] = $config['urdweb_logfile'];
} else {
    $config['log_file'] = '/dev/null';
}

require_once $pathhtmli . '/defaults.php';
require_once $pathhtmli . '/web_functions.php';
require_once $pathhtmli . '/urd_log.php';
require_once $pathhtmli . '/db.class.php';
require_once $pathhtmli . '/functions.php';
// initialise some stuff

try {
    $db = connect_db(TRUE);  // initialise the database
} catch (exception $e) {
    $msg = $e->getMessage();
    die_html("Connection to database failed. $msg\n");
}

require_once "$pathhtmli/config_functions.php";
require_once "$pathhtmli/user_functions.php";
require_once "$pathhtmli/checkauth.php";
$prefs = load_prefs($db, $userid); // load the prefs

require_once "$pathhtmli/usenet_functions.php";
require_once "$pathhtmli/file_functions.php";
// first include all the php files that only define stuff
require_once "$pathhtmli/exception.php";
require_once "$pathhtmli/autoincludes.php";
require_once "$pathhtmli/buttons.php";
require_once "$pathhtmli/module_functions.php";
require_once "$pathhtmli/../html/fatal_error.php";

require_once "$pathhtmli/smarty.php";
// then execute code we always need
require_once "$pathhtmli/fix_magic.php";
require_once "$pathhtmli/../urdd/urdd_client.php";

stored_files::garbage_collect();
