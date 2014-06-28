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
 * $LastChangedDate: 2013-07-13 00:57:46 +0200 (za, 13 jul 2013) $
 * $Rev: 2871 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: forgot_password.php 2871 2013-07-12 22:57:46Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathfp = realpath(dirname(__FILE__));
$pathca = realpath($pathfp . '/../functions/');
session_name('URD_WEB_FORGOTPW' . md5($pathca)); // add the hashed path so we can have more than 1 session to different urds in one browser
@session_start();

require_once $pathfp . '/../config.php';
require_once $pathfp . '/../functions/db.class.php';

if (isset($config['urdweb_logfile'])) {
    $config['log_file'] = $config['urdweb_logfile'];
} else {
    $config['log_file'] = '/dev/null';
}

$process_name = 'urd_web'; // needed for message format in syslog and logging

require_once "$pathfp/../functions/urd_log.php";
require_once "$pathfp/../functions/autoincludes.php";
require_once "$pathfp/../functions/functions.php";
require_once "$pathfp/../functions/config_functions.php";

try {
    $db = connect_db(FALSE);  // initialise the database
} catch (exception $e) {
    $msg = $e->getMessage();
    throw new exception("Connection to database failed. $msg\n");
}

require_once "$pathfp/../functions/user_functions.php";
require_once "$pathfp/../functions/web_functions.php";
require_once "$pathfp/../functions/exception.php";
require_once "$pathfp/../functions/mail_functions.php";
require_once "$pathfp/../html/fatal_error.php";
require_once "$pathfp/../functions/defines.php";

$status = 'show';
if (isset($_POST['username'])&& $_POST['username'] != '' && isset($_POST['email']) && $_POST['email'] != '' && verify_email($_POST['email'])) {
    $username = get_post('username');
    $email = get_post('email');
    $sql = '"ID", "fullname", "name", "email" FROM users WHERE "email" = ? AND "name" = ?';
    $res = $db->select_query($sql, 1, array($email, $username));
    if ($res !== FALSE) {
        $newpw = generate_password(MIN_PASSWORD_LENGTH);
        $id = $res[0]['ID'];
        $fullname = $res[0]['fullname'];
        $username = $res[0]['name'];
        $email = $res[0]['email'];
        set_password($db, $id, $newpw);

        $sender = get_config($db, 'admin_email');
        urd_mail::mail_pw($db, $fullname, $username, $email, $newpw, $sender);
    }
    die_html('OK');
} else {
    $err_msg = '';
    if (!isset($_POST['username']) ||$_POST['username'] == '' ) {
        $err_msg .= 'Invalid username; ';
    }
    if (!isset($_POST['email']) || $_POST['email'] == '' || !verify_email($_POST['email'])) {
        $err_msg .= 'Invalid email address';
    }
    throw new exception($err_msg);
}
