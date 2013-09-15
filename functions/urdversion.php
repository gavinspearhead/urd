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
 * $LastChangedDate: 2013-09-07 00:34:21 +0200 (za, 07 sep 2013) $
 * $Rev: 2924 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdversion.php 2924 2013-09-06 22:34:21Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class urd_version
{
    const urd_version       = '1.10.0';
    const urd_status        = 'stable';
    const copyright         = '2007-2013 &copy; Styck &amp; Spearhead';
    const extset_version    = '1.0';
    const long_name         = 'Usenet Resource Downloader';
    const short_name        = 'URD';
    const urdland_url       = 'http://www.urdland.com';

    public static function get_copyright()
    {
        return self::copyright;
    }

    public static function get_extset_version()
    {
        return self::extset_version;
    }

    public static function get_version()
    {
        return self::urd_version;
    }

    public static function get_status()
    {
        return self::urd_status;
    }

    public static function get_urdland_url()
    {
        return self::urdland_url;
    }

    public static function get_urd_name($long=FALSE)
    {
        if ($long) {
            return self::long_name;
        } else {
            return self::short_name;
        }
    }
}
