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
 * $LastChangedDate: 2014-06-14 01:20:27 +0200 (za, 14 jun 2014) $
 * $Rev: 3094 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: forgot_password.php 3094 2014-06-13 23:20:27Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathfp = realpath(dirname(__FILE__));
$pathca = realpath($pathfp . '/../functions/');
session_name('URD_WEB_FORGOTPW' . md5($pathca)); // add the hashed path so we can have more than 1 session to different urds in one browser
@session_start();

require_once $pathfp . '/../config.php';
require_once $pathfp . '/../functions/db.class.php';

$process_name = 'urd_web'; // needed for message format in syslog and logging

if (isset($config['urdweb_logfile'])) {
    $config['log_file'] = $config['urdweb_logfile'];
} else {
    $config['log_file'] = '/dev/null';
}

require_once "$pathfp/../functions/web_functions.php";
require_once "$pathfp/../functions/config_functions.php";
require_once "$pathfp/../functions/urd_log.php";
$lang = detect_language() . '.php';

try {
    $db = connect_db(FALSE);  // initialise the database
} catch (exception $e) {
    $msg = $e->getMessage();
    throw new exception("Connection to database failed. $msg\n");
}
$prefs = load_config($db, TRUE);

require_once "$pathfp/../functions/autoincludes.php";
require_once "$pathfp/../functions/functions.php";

require_once "$pathfp/../functions/defines.php";
require_once "$pathfp/../functions/smarty.php";

init_smarty($LN['forgot_title'], 0);
$smarty->display('forgot_password.tpl');
