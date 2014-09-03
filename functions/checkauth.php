<?php
/*
 *  This file is part of Urd.
 *
 *  vim:ts=4:expandtab:cindent
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
 * $Id: checkauth.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die ('This file cannot be accessed directly.');
}

function reset_failed_login(DatabaseConnection $db, $username)
{
    $db->update_query_2('users', array('failed_login_count'=>0, 'failed_login_time'=>0), '"name"=?', array($username));
}

function increase_failed_login(DatabaseConnection $db, $username)
{
    $time = time();
    $sql = 'UPDATE users SET "failed_login_count"="failed_login_count" + 1, "failed_login_time" = :time WHERE "name"=:name';
    $db->execute_query($sql, array(':name'=>$username, ':time'=>$time));
}

function set_session_cookie($username, $password, $period, $token)
{
    assert(is_numeric($period) || $period == NULL); // NULL when it's 0...
    if ($period !== NULL) { // don't store the data in the cookie if the user won't like it (is gone after browser closes)
        // Set cookie:
        setcookie('urd_username', $username, $period);
        setcookie('urd_pass', $password, $period);
        setcookie('urd_period', "$period", $period);
        setcookie('urd_token', $token, $period);
        $_COOKIE['urd_username'] = $username;
        $_COOKIE['urd_pass'] = $password;
        $_COOKIE['urd_period'] = $period;
        $_COOKIE['urd_token'] = $token;
    }

    $_SESSION['urd_username'] = $username;
    $_SESSION['urd_pass'] = $password;
    $_SESSION['urd_period'] = $period;
    $_SESSION['urd_token'] = $token;
}

function hash_password($plain_password, $salt, $token)
{
    $salt_pw = $salt . $plain_password . $salt;
    $token_pw = $token . hash('sha256', $salt_pw) . $token;
    $password = hash('sha256', $token_pw);
    $md5pass = md5($token . md5($plain_password) . $token);

    return array($password, $md5pass);
}

function check_password(DatabaseConnection $db, $username, $plain_password, $password, $md5pass, $token, &$res)
{
    try {
        $max_login_count = get_config($db, 'max_login_count');
    } catch (exception $e) {
        // maybe the value isn't in the database yet, we chose to disable it
        $max_login_count = 0;
    }
    $input_arr = array(':username' => $username, ':status'=>user_status::USER_ACTIVE);
    $sql = '* FROM users WHERE "name"=:username AND "active"=:status';
    if ($max_login_count > 0) {
        $sql .= ' AND "failed_login_count" < :failed_login_count';
        $input_arr[':failed_login_count'] = $max_login_count;
    }
    $res = $db->select_query($sql, 1, $input_arr);
    if ($res === FALSE) { // no valid user found

        return FALSE;
    }
    $salt_db = $res[0]['salt'];
    $password_db = $res[0]['pass'];
    /*if ($md5pass != '') { // check if the md5 stuff still matches... if not, we set the new pasword with salt
        if ($plain_password != '' && $md5pass == md5($token. $res[0]['pass'] . $token)) {
            set_password($db, $res[0]['ID'], $plain_password, FALSE);
            $sql = '* FROM users WHERE "name"=:username AND "active"=:status';
            $res = $db->select_query($sql, 1, array(':username' =>$username, ':status'=>user_status::USER_ACTIVE));
            if ($res === FALSE) {// no valid user found

                return FALSE;
            }
            $salt_db = $res[0]['salt'];
            $password_db = $res[0]['pass'];
            $password = hash('sha256', $token . hash('sha256', $salt_db. $plain_password. $salt_db) . $token);
            set_session_cookie($username, $password, $_SESSION['urd_period'], $token);
        }
    }
    if ($salt_db == '') {
        if ($plain_password != '' && $password == hash('sha256', $token . $password_db . $token)) {// set the salt in de db
            set_password($db, $res[0]['ID'], $plain_password, FALSE);
            $sql = '* FROM users WHERE "name"=:username AND "active"=:status';
            $res = $db->select_query($sql, 1, array(':username' =>$username,':status'=> user_status::USER_ACTIVE));
            if ($res === FALSE) {// no valid user found

                return FALSE;
            }
            $password_db = $res[0]['pass'];
            $salt_db = $res[0]['salt'];
            $password = hash('sha256', $token . hash('sha256', $salt_db . $plain_password . $salt_db) . $token);
            set_session_cookie($username, $password, $_SESSION['urd_period'], $token);
        } else {
            return FALSE;
        }
    }*/

    $pw_hash = hash('sha256', $token . $password_db . $token);

    return $pw_hash == $password;
}

/*
 * the password is stored in the database hashed with a salt hence the database contains
 * username, salt, hash(salt, password, salt) // where password is the plaintext password
 * the cookie stores:
 * username, token, hash(token, hash(salt, password, salt), token)
 *
 * in case the pw is entered via a form field, it is checked against md5 of the entered password with database and the new pw is set
 * and it is checked against an unsalted pw, and the pw is reset.
 * For cookies/ sessions in this case the pw is considered wrong and a relogin is needed.
 */

try {
    $pathca = realpath(dirname(__FILE__));
    session_name('URD_WEB'. md5($pathca)); // add the hashed path so we can have more than 1 session to different urds in one browser
    session_set_cookie_params(0, '/', '', FALSE, TRUE);
    @session_start();
    require_once "$pathca/config_functions.php";
    require_once "$pathca/user_functions.php";
    $plain_password = $md5pass = '';

    // select the language from the form
    if (isset($_POST['curr_language'])) {
        require_once "$pathca/web_functions.php";
        $languages = get_languages();
        $language = $_POST['curr_language'];
        if (in_array($language, array_keys($languages))) {
            if (isset($LN)) {
                unset($LN);
            }
            load_language($language);
        }
    } elseif (!isset($LN) && !defined('SKIP_LANG')) { // get the default language
        require_once "$pathca/web_functions.php";
        $language = get_config($db, 'default_language');
        load_language($language);
    }
    $form_login = $log_ip = FALSE;
    // First, check for post (from login.php):
    if (isset($_POST['username'], $_POST['pass'], $_POST['period'], $_POST['token']) && $_POST['username'] != '' &&  $_POST['pass'] != '') {
        // we have a username, password, period and token and u/p is not blank
        $username = get_post('username');
        $token = get_post('token');
        try {
            $salt = get_salt($db, $username);
            $plain_password = get_post('pass');
            list($password, $md5pass) = hash_password($plain_password, $salt, $token);
            $form_login = TRUE;
            $log_ip = get_post('ipaddr', 0) == 1 ? TRUE : FALSE;

            // Set cookie:
            switch ($_POST['period']) {
            case 0: $period = NULL; break;
            case 1: $period = time() + 3600 * 24 * 7; break;
            case 2: $period = time() + 3600 * 24 * 30; break;
            case 3: $period = time() + 3600 * 24 * 365; break;
            case 4: $period = mktime(23, 59, 59, 12, 31, 2037);break;
            default: $period = NULL; break;
            }
            session_regenerate_id();
            // Set cookie variable for the current context
            set_session_cookie($username, $password, $period, $token);
        } catch (exception $e) {
        }
    }

    // Examine cookie/session (old or brand new from above code):
    if (isset($_SESSION['urd_username'], $_SESSION['urd_pass'], $_SESSION['urd_token'])) { // first try if we have a session
        $username = $_SESSION['urd_username'];
        $password = $_SESSION['urd_pass'];
        $token = $_SESSION['urd_token'];
    } elseif (isset($_COOKIE['urd_username'], $_COOKIE['urd_pass'], $_COOKIE['urd_token'])) { // if not try the cookie
        $username = $_COOKIE['urd_username'];
        $password = $_COOKIE['urd_pass'];
        $token = $_COOKIE['urd_token'];
    }

    // Found credentials? Check!
    $valid = $isadmin = FALSE;
    $ipaddr = $_SERVER['REMOTE_ADDR'];
    if (isset($username, $password, $token) && $username != '') {
        if (check_password($db, $username, $plain_password, $password, $md5pass, $token, $res)) {
            if ($form_login === TRUE) {
                $valid = TRUE;
                if ($log_ip !== TRUE) {
                    $ipaddr = '';
                }

                // if we log on from the form, we update the IP if requested
                set_login_ip($db, $ipaddr, $res[0]['ID']);
                reset_failed_login($db, $username);
            } elseif ($res[0]['ipaddr'] == '' ||  $res[0]['ipaddr'] == $ipaddr || $res[0]['ipaddr'] === NULL) {
                // if we use the cookie, we make sure the IP address is the same... otherwise user needs to login again
                $valid = TRUE;
            }
            $cur_time = time();
            if ($valid === TRUE) {
                $userid = $res[0]['ID'];
                $isadmin = ($res[0]['isadmin'] == user_status::USER_ADMIN);
                $username = $res[0]['name'];

                if (isset($_SESSION['last_login'])) {
                    $last_login = $_SESSION['last_login'];
                } else {
                    $_SESSION['last_login'] = $res[0]['last_login'];
                    $last_login = $cur_time;
                    if ($form_login === TRUE) {
                        set_last_login($db, $last_login, $res[0]['ID']);
                    }
                }
                if (!isset($_SESSION['last_active']) || (($cur_time - $_SESSION['last_active'] ) > 60)) { // to cut down on excessive db updates
                    set_last_active($db, $cur_time, $res[0]['ID']);
                    $_SESSION['last_active'] = $cur_time;
                }
            }
        }
    }

    $auto_login = FALSE;
    if (!isset($username) || $username == '' || $valid === FALSE) {
        try {
            $valid = FALSE;
            $auto_login_username = get_config($db, 'auto_login');
            if ($auto_login_username != '') {
                $username = $auto_login_username;
                $auto_login = TRUE;
            }
        } catch (exception $e) {
            $auto_login = FALSE;
        }
        if (isset($username) && $username != '' && $username != 'root' && $auto_login) {
            $_SESSION['urd_token'] = '';
            try {
                $userid = get_userid($db, $username);
                if ($userid === FALSE || $userid == 0) {
                    $valid = FALSE;
                } else {
                    $_SESSION['urd_username'] = $username;
                    $_SESSION['urd_pass'] = '';
                    $_SESSION['urd_period'] = NULL;
                    $valid = TRUE;
                    $isadmin = urd_user_rights::is_admin($db, $userid);
                }
            } catch (exception $e) {
                $valid = FALSE;
            }
        }
    }

    if ($valid !== TRUE) {
        // Invalid login
        // Write error message in case user tried to log in (this code is also accessed when user tries to access but not login yet):
        if (isset($username) && $username != '') {
            write_log("Login failed for account '$username', IP address is " . $_SERVER['REMOTE_ADDR'], LOG_WARNING);
            increase_failed_login($db, $username);
        }
        // Remove cookie:
        $onehourago = time() - 3600;
        @setcookie('urd_username', 0, $onehourago);
        @setcookie('urd_pass', 0, $onehourago);
        @setcookie('urd_period', 0,$onehourago);
        @setcookie('urd_cookie', 0, $onehourago);
        unset($_COOKIE['urd_username'], $_COOKIE['urd_pass'], $_COOKIE['urd_token'], $username, $isadmin, $userid);
        $_SESSION['urd_username'] = $_SESSION['urd_pass'] = $_SESSION['urd_period'] = $_SESSION['urd_token'] = '';
        // If ($__auth = 'silent') then just die without giving output (useful for some pages):
        if (isset($__auth) && $__auth == 'silent') {
            die();
        }
        // Otherwise, show login form
        if (isset($_POST['username']) && $_POST['username'] != '') {
            $message = $LN['login_failed'] . '.';
        }

        require realpath($pathca . '/../html/login.php');
        die; // make sure we die
    }

} catch (exception $e) {
    die_html('Interesting... ' . $e->getmessage());
}
