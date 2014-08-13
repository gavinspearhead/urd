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
 * $LastChangedDate: 2014-06-03 17:23:08 +0200 (di, 03 jun 2014) $
 * $Rev: 3080 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_register.php 3080 2014-06-03 15:23:08Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathreg = realpath(dirname(__FILE__));
$pathca = realpath($pathreg. '/../functions/');

session_name('URD_WEB_REGISTER' . md5($pathca)); // add the hashed path so we can have more than 1 session to different urds in one browser
@session_start();

require_once $pathreg . '/../config.php';
require_once $pathreg . '/../functions/db.class.php';

$process_name = 'urd_web'; // needed for message format in syslog and logging
if (isset($config['urdweb_logfile'])) {
    $config['log_file'] = $config['urdweb_logfile'];
} else {
    $config['log_file'] = '/dev/null';
}

require_once "$pathreg/../functions/urd_log.php";
require_once "$pathreg/../functions/autoincludes.php";
require_once "$pathreg/../functions/defaults.php";
require_once "$pathreg/../functions/functions.php";
require_once "$pathreg/../functions/file_functions.php";

try {
    $db = connect_db(FALSE);  // initialise the database
} catch (exception $e) {
    $msg = $e->getMessage();
    die_html("Connection to database failed. $msg");
}
require_once "$pathreg/../functions/config_functions.php";
config_cache::clear(user_status::SUPER_USERID); // needed to read tthe right values
require_once "$pathreg/../functions/user_functions.php";

try {
    $prefs = load_config($db);
    require_once "$pathreg/../functions/web_functions.php";
    require_once "$pathreg/../functions/exception.php";
    require_once "$pathreg/../functions/mail_functions.php";
    require_once "$pathreg/../html/fatal_error.php";
    require_once "$pathreg/../functions/urdversion.php";
    require_once "$pathreg/../functions/defines.php";
    require_once "$pathreg/../functions/smarty.php";

    $captcha = extension_loaded ('gd') ? 1 : 0;
    // initialise some stuff
    if (!isset($prefs['register']) || $prefs['register'] == 0) {
        throw new exception($LN['reg_disabled']);
    }

    if (isset($_POST['submit_button'])) {
        if ($captcha != 0 && (!isset($_SESSION['register_captcha'], $_POST['register_captcha']) || $_SESSION['register_captcha'] != $_POST['register_captcha'])) {
            throw new exception($LN['error_captcha']);
        }
        if (isset($_POST['username']) && trim($_POST['username']) != '') {
            $username = trim($_POST['username']);
        } else {
            throw new exception($LN['error_invalidusername']);
        }
        if (isset($_POST['fullname']) && $_POST['fullname'] != '') {
            $fullname = trim($_POST['fullname']);
        } else {
            throw new exception($LN['error_invalidfullname']);
        }
        if (isset($_POST['email']) && $_POST['email'] != '' && verify_email($_POST['email'])) {
            $email = trim($_POST['email']);
        } else {
            throw new exception($LN['error_invalidemail']);
        }
        if (isset($_POST['password1']) && $_POST['password1'] != '') {
            $password1 = trim($_POST['password1']);
            $pwmsg = verify_password($password1);
            if ($pwmsg !== TRUE) {
                throw new exception($pwmsg);
            }
        } else {
            throw new exception($LN['error_invalidpassword']);
        }
        if (isset($_POST['password2']) && $_POST['password2'] != '') {
            $password2 = trim($_POST['password2']);
            if ($password1 != $password2) {
                throw new exception($LN['error_pwmatch']);
            }
        } else {
            throw new exception($LN['error_invalidpassword']);
        }

        $active = user_status::USER_PENDING;
        $isadmin = user_status::USER_USER;
        $res = get_userid($db, $username);
        if ($res === FALSE) {
            add_user($db, $username, $fullname, $email, $password1, $isadmin, $active, 'C');
        } else {
            throw new exception($LN['error_userexists']);
        }

        $token = generate_password(200);

        $db->update_query_2('users', array('token'=>$token, 'active'=>user_status::USER_PENDING), '"name"=?', array($username));
        urd_mail::mail_activation($db, $username, $fullname, $email, $token, $prefs['admin_email']);
    } else {
        throw new exception($LN['error_invalidaction']);
    }
    return_result();
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
