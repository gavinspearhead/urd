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
 * $LastChangedDate: 2013-03-30 00:31:38 +0100 (za, 30 mrt 2013) $
 * $Rev: 2804 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: defines.php 2804 2013-03-29 23:31:38Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

//error_reporting(0);

$__auth = 'silent';
$pathstat = realpath(dirname(__FILE__));

require_once "$pathstat/../functions/html_includes.php";

$spotid = get_request('spotid', '');
if ($spotid == '') {
    throw new exception($LN['error_spotnotfound']);
}

$db->escape($spotid, TRUE);
$sql = "image FROM spot_images WHERE spotid = $spotid";
$res = $db->select_query($sql);
if (!isset($res[0]['image'])) {
    throw new exception($LN['error_spotnotfound']);
}
$image = $res[0]['image'];

// Format is like:
//data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAk....

if (substr($image, 0,16) == 'data:image/jpeg;') {
    $image = substr($image, 16);
    if (substr($image, 0, 6) == 'base64') {
        $image = substr($image, 7); // also strip the comma
        $image = base64_decode($image);
        if ($image === FALSE) {
            throw new exception($LN['error_invalidimage']);
        }
        header('Content-Type: image/jpeg');
        echo $image;
        die;
    }
}

echo 'NOT OK';
