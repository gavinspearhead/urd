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
 * $LastChangedDate: 2013-09-07 00:34:21 +0200 (za, 07 sep 2013) $
 * $Rev: 2924 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: captcha.php 2924 2013-09-06 22:34:21Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

error_reporting(1);

$pathcap = realpath(dirname(__FILE__));
$pathca = realpath($pathcap. '/../functions/');
session_name('URD_WEB_REGISTER' . md5($pathca)); // add the hashed path so we can have more than 1 session to different urds in one browser
@session_start();
$process_name = 'urd_web'; // needed for message format in syslog and logging

require_once "$pathcap/../functions/urd_log.php";
require_once "$pathcap/../functions/autoincludes.php";
require_once "$pathcap/../functions/functions.php";
require_once "$pathcap/../functions/web_functions.php";
require_once "$pathcap/../functions/db.class.php";

try {
    $db = connect_db();  // initialise the database
} catch (exception $e) {
    $msg = $e->getMessage();
    die_html("Connection to database failed. $msg\n");
}

require_once "$pathcap/../functions/config_functions.php";
$prefs = load_config($db);
require_once "$pathcap/../functions/exception.php";
require_once "$pathcap/../html/fatal_error.php";
require_once "$pathcap/../functions/smarty.php";

$img_dir = $smarty->getTemplateVars('IMGDIR');

header('Expires: Mon, 25 Jun 1995 06:06:06 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

function generate_random($length=6)
{
    assert (is_numeric($length));
    $_rand_src = array(
        array(49, 57), //digits
//        array(97,122), //lowercase chars
      array(65,90) //uppercase chars
    );
    mt_srand ();
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $i1 = mt_rand(0, sizeof($_rand_src) - 1);
        $random_string .= chr(mt_rand($_rand_src[$i1][0], $_rand_src[$i1][1]));
    }

    return $random_string;
}

$im = @imagecreatefromjpeg($img_dir . '/captcha.jpg');
$rand = generate_random(3);
$_SESSION['register_captcha'] = $rand;
ImageString($im, 5, 2, 2, $rand[0]. ' ' . $rand[1] . ' ' . $rand[2] . ' ', ImageColorAllocate($im, 0, 0, 0));
$rand = generate_random(3);
ImageString($im, 5, 2, 2, ' ' . $rand[0] . ' ' . $rand[1] . ' ' . $rand[2] , ImageColorAllocate($im, 255, 0, 0));
header('Content-type: image/jpeg');
imagejpeg($im, NULL, 100);
ImageDestroy($im);
die;
