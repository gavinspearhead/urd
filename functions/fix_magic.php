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
 * $LastChangedDate: 2013-08-04 00:07:36 +0200 (zo, 04 aug 2013) $
 * $Rev: 2885 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: fix_magic.php 2885 2013-08-03 22:07:36Z gavinspearhead@gmail.com $
 */
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class urd_magic_crap
{
    private static $crap_fixed = FALSE;

    public static function fix_magic_crap()
    {
        // Fuck off with your crap magic quotes:
        if (get_magic_quotes_gpc() == 0 || urd_magic_crap::$crap_fixed) {	// Only do it once:
            return;
        }
        urd_magic_crap::$crap_fixed = TRUE;

        // It's set, remove it from all variables:
        urd_magic_crap::fix_magic_crap_array($_GET);
        urd_magic_crap::fix_magic_crap_array($_POST);
        urd_magic_crap::fix_magic_crap_array($_COOKIE);
        urd_magic_crap::fix_magic_crap_array($_REQUEST);
    }

    private static function fix_magic_crap_array(array &$array)
    {
        foreach ($array as &$val) {
            if (is_array($val)) {
                urd_magic_crap::fix_magic_crap_array($val);
            } else {
                $val = stripslashes($val);
            }
        }
    }
}

urd_magic_crap::fix_magic_crap();
