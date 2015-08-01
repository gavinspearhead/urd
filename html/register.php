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
 * $LastChangedDate: 2014-06-07 14:53:28 +0200 (za, 07 jun 2014) $
 * $Rev: 3081 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: register.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
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
require_once "$pathreg/../functions/defines.php";
require_once "$pathreg/../functions/functions.php";

try {
    $db = connect_db(FALSE);  // initialise the database
} catch (exception $e) {
    $msg = $e->getMessage();
    die_html("Connection to database failed. $msg");
}
require_once "$pathreg/../functions/config_functions.php";
config_cache::clear(user_status::SUPER_USERID); // needed to read tthe right values
require_once "$pathreg/../functions/user_functions.php";

$prefs = load_config($db, TRUE);
require_once "$pathreg/../functions/web_functions.php";
$lang = detect_language() . '.php';

require_once "$pathreg/../functions/mail_functions.php";
require_once "$pathreg/../functions/defines.php";

require_once "$pathreg/../functions/smarty.php";
require_once "$pathreg/../functions/exception.php";

// initialise some stuff
$subpage = '';
if (!isset($prefs['register']) || $prefs['register'] == 0) {
    throw new exception ($LN['reg_disabled']);
}

$title = $LN['reg_title'];

if (isset($_GET['activate']) && isset($_GET['username'])) {
    $username = $_GET['username'];
    $token = $_GET['activate'];
    $time = time();
    $sql = '* FROM users WHERE "name"=:name AND "token"=:token AND "active" = :active';
    $res = $db->select_query($sql, 1, array(':name'=>$username, ':token'=>$token, ':active'=>user_status::USER_PENDING));
    if ($res === FALSE) {
        throw new exception($LN['error_nosuchuser']);
    }
    $row = $res[0];
    $id = $row['ID'];
    $email = $row['email'];
    if (($row['regtime'] + (3600 * 24)) <= $time) {
        delete_user($db, $id);
        throw new exception($LN['error_acctexpired']);
    }
    $auto_reg = $prefs['auto_reg'];
    $active = ($auto_reg == 0) ? user_status::USER_INACTIVE : user_status::USER_ACTIVE;
    $res = $db->update_query_2('users', array('active'=>$active, 'token'=>11), '"ID"=?', array($id));
    $admin_email = $prefs['admin_email'];
    urd_mail::mail_admin_new_user($db, $username, $row['fullname'], $admin_email, $admin_email, $_SERVER['REMOTE_ADDR'], $email);
    if ($active == user_status::USER_ACTIVE) {
        $subpage = 'activated';
    } else {
        $subpage = 'pending';
    }
} else {
    $subpage = 'form';
}

$captcha = extension_loaded ('gd') ? 1 : 0;

init_smarty($title, 0);
$smarty->assign(array(
    'captcha' => $captcha, 
    'subpage' => $subpage));
$smarty->display('register.tpl');
