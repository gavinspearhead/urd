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
 * $LastChangedDate: 2014-04-27 21:55:06 +0200 (zo, 27 apr 2014) $
 * $Rev: 3032 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_update_session.php 3032 2014-04-27 19:55:06Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathaus = realpath(dirname(__FILE__));

require_once "$pathaus/../functions/ajax_includes.php";

$type = get_request('type', NULL);
$var = get_request('var', NULL);

// Apparently there's a session variable named $type, which is an array and an element of which is $var, and the value of which is toggled in this function.....

if ($type == 'post') {
    if (isset($_SESSION['post_hide_status'][$var])) {
        $_SESSION['post_hide_status'][$var]++;  // we simply swap a bit 1-> 0 and 0 -> 1 :)
        $_SESSION['post_hide_status'][$var] %= 2;
    } else {
        $_SESSION['post_hide_status'][$var] = 0;
    }
} elseif ($type == 'down') {
    if (isset($_SESSION['transfer_hide_status'][$var])) {
        $_SESSION['transfer_hide_status'][$var]++;  // we simply swap a bit 1-> 0 and 0 -> 1 :)
        $_SESSION['transfer_hide_status'][$var] %= 2;
    } else {
        $_SESSION['transfer_hide_status'][$var] = 0;
    }
} elseif (in_array($type, array('transfers'))) {
    $_SESSION[$type] = $var;
} elseif ($type == 'control') {
    $_SESSION['control_status'] = (++$_SESSION['control_status'] % 2);
}

die_html('OK');
