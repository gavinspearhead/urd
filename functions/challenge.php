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
 * $LastChangedDate: 2013-09-01 16:37:15 +0200 (zo, 01 sep 2013) $
 * $Rev: 2907 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: web_functions.php 2907 2013-09-01 14:37:15Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class challenge
{
    const CHALLENGE_LENGTH = 16;
    const CHALLENGE_TIMEOUT = 86400; // 1 day
    const CHALLENGE_REFRESH_TIME = 43200; // 12 hours
    private static $need_challenge = TRUE;

    public static function set_need_challenge($val)
    {
        self::$need_challenge = ($val ? TRUE : FALSE);
    }

    public static function verify_challenge($c)
    {
        global $LN;
        if (!self::$need_challenge) {
            return;
        }
        if (!isset($_SESSION['challenge']) || ($c != $_SESSION['challenge']) || ($_SESSION['challenge_timeout'] < time())) {
            throw new exception($LN['error_invalidchallenge']);
        }
    }

    public static function set_challenge()
    {
        if (!self::$need_challenge) {
            return FALSE;
        }
        $now = time();
        if (!isset($_SESSION['challenge']) || ($_SESSION['challenge_timeout']  < ($now + (self::CHALLENGE_REFRESH_TIME)))) { // if the challenge is valid for less than the current time - the refresh time
            $challenge = generate_password(self::CHALLENGE_LENGTH);
            $_SESSION['challenge'] = $challenge;
            $_SESSION['challenge_timeout'] = $now + self::CHALLENGE_TIMEOUT;
        } else {
            $challenge = $_SESSION['challenge'];
        }

        return $challenge;
    }
}
