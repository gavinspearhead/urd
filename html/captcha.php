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
 * $Id: captcha.php 3080 2014-06-03 15:23:08Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

error_reporting(1);

$pathcap = realpath(dirname(__FILE__));
$pathca = realpath($pathcap. '/../functions/');
session_name('URD_WEB_REGISTER' . md5($pathca)); // add the hashed path so we can have more than 1 session to different urds in one browser
@session_start();
$process_name = 'urd_web'; // needed for message format in syslog and logging

require_once "$pathcap/../functions/autoincludes.php";
require_once "$pathcap/../functions/urd_log.php";
require_once "$pathcap/../functions/functions.php";
require_once "$pathcap/../functions/file_functions.php";
require_once "$pathcap/../functions/web_functions.php";
require_once "$pathcap/../functions/db.class.php";

try {
    $db = connect_db(FALSE);  // initialise the database
} catch (exception $e) {
    $msg = $e->getMessage();
    die_html("Connection to database failed. $msg\n");
}

require_once "$pathcap/../functions/config_functions.php";
$prefs = load_config($db);
require_once "$pathcap/../functions/exception.php";
require_once "$pathcap/../html/fatal_error.php";
require_once "$pathcap/../functions/smarty.php";


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

function get_image($x, $y)
{
    $im = imagecreatetruecolor($x, $y);

    for ($i=0 ; $i< $x; $i++) {
        for ($j=0 ; $j< $y; $j++) {
            $c = mt_rand(140, 200);
            if (mt_rand(0,1) == 0) {
                $cl = imagecolorallocate($im, $c, $c, $c);
            }else {
                $cl = imagecolorallocate($im, 255, 255, 255);
            }
                imagesetpixel($im, $i, $j, $cl);
        }
    }
    return $im;
}

$im = get_image(80,20);
$rand = generate_random(4);
$_SESSION['register_captcha'] = $rand;
ImageString($im, 5, 2, 2, $rand[0]. ' ' . $rand[1] . ' ' . $rand[2] . ' '. $rand[3] . ' '  , ImageColorAllocate($im, 0, 0, 0));
$rand = generate_random(4);
ImageString($im, 5, 2, 2, ' ' . $rand[0] . ' ' . $rand[1] . ' ' . $rand[2] .' ' . $rand[3]  , ImageColorAllocate($im, 200, 0, 00));
header('Content-type: image/png'); 
imagepng($im, NULL, 0);
ImageDestroy($im);
die;
