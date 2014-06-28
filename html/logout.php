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
 * $LastChangedDate: 2014-06-03 17:23:08 +0200 (di, 03 jun 2014) $
 * $Rev: 3080 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: logout.php 3080 2014-06-03 15:23:08Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathlo = realpath(dirname(__FILE__));
$process_name = 'urd_web';

require_once "$pathlo/../config.php";
require_once "$pathlo/../functions/fix_magic.php";
require_once "$pathlo/../functions/autoincludes.php";
require_once "$pathlo/../functions/functions.php";
require_once "$pathlo/../functions/web_functions.php";
require_once "$pathlo/../functions/fix_magic.php";

$pathca = realpath($pathlo. '/../functions/');
session_name('URD_WEB'. md5($pathca)); // add the hashed path so we can have more than 1 session to different urds in one browser
@session_start();
$thepast = time() - 3600;
setcookie('urd_username', 0, $thepast);
setcookie('urd_pass', 0, $thepast);
setcookie('urd_period', 0, $thepast);
setcookie('urd_token', 0, $thepast);
unset($_COOKIE['urd_username'], $_COOKIE['urd_pass'], $_COOKIE['urd_token'], $username, $isadmin, $userid);
unset($_SESSION['urd_username'], $_SESSION['urd_pass'], $_SESSION['urd_token']);
$_SESSION = $_COOKIE = array();
$params = session_get_cookie_params();
// destroy the session cookie
setcookie(session_name(), '', $thepast, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

// destroy the session
session_unset();
session_destroy();
redirect('login.php');
