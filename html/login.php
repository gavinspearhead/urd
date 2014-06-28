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
 * $LastChangedDate: 2014-06-22 00:25:41 +0200 (zo, 22 jun 2014) $
 * $Rev: 3106 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: login.php 3106 2014-06-21 22:25:41Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
}
$process_name = 'urd_web';

$pathlo = realpath(dirname(__FILE__));
// We can't include html_includes as that would hit the checkauth and forward to login.php -> endless loop

require_once "$pathlo/../config.php";
require_once "$pathlo/../functions/functions.php";
require_once "$pathlo/../functions/config_functions.php";
require_once "$pathlo/../functions/file_functions.php";
require_once "$pathlo/../functions/defines.php";
require_once "$pathlo/../functions/error_codes.php";
require_once "$pathlo/../functions/urdversion.php";

$process_name = 'urd_web'; // needed for message format in syslog and logging
// Is URD actually installed?
if (!file_exists('../.installed')) {
    header('Location: ../install/install.php');
}

if (isset($config['urdweb_logfile'])) {
    $config['log_file'] = $config['urdweb_logfile'];
} else {
    $config['log_file'] = '/dev/null';
}

require_once "$pathlo/../functions/db.class.php";
require_once "$pathlo/../functions/urd_log.php";
require_once "$pathlo/../functions/web_functions.php";

try {
    $db = connect_db(FALSE);  // initialise the database
} catch (exception $e) {
    $msg = $e->getMessage();
    die_html("Connection to database failed. $msg\n");
}

require_once "$pathlo/../functions/smarty.php";

$languages = get_languages();
$language = get_request('language_name', detect_language() . '.php');
if (!in_array($language, array_keys($languages))) {
    $language = select_language($db, NULL);
}

$lang_change = get_post('lang_change', '');

if (isset($_POST['username'], $_POST['pass'], $_POST['period']) && $_POST['pass'] != '' && $_POST['username'] != '') {
    require_once "$pathlo/../functions/checkauth.php";
    if (isset($valid) && $valid === TRUE) {
        $lang = get_pref($db, 'language', $userid, '');
        if ($lang == '') {
            set_pref($db, 'language', $language, $userid);
        }
        redirect('index.php');
    }
} elseif ((isset($_POST['username']) && $_POST['username'] != '' || isset($_POST['pass']) && $_POST['pass'] != '') && $lang_change != '') {
    $message = $LN['login_failed'] . '.';
}

$prefs = load_config($db, TRUE);

load_language($language);

$register = (get_config($db, 'register') > 0) ? 1 : 0;
$ip_address = $_SERVER['REMOTE_ADDR'];
$token = generate_password(8);

if (!isset($message)) {
    $message = '';
}

init_smarty($LN['login_title'], 0);
$smarty->assign('message',        $message);
$smarty->assign('ip_address',     $ip_address);
$smarty->assign('bind_ip_address',get_post('ipaddr', 0));
$smarty->assign('username',       get_post('username', ''));
$smarty->assign('period',         get_post('period', 0));
$smarty->assign('register',       $register);
$smarty->assign('token',          $token);
$smarty->assign('languages',      $languages);
$smarty->assign('curr_language',  $language);
$smarty->display('login.tpl');
