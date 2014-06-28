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
 * $LastChangedDate: 2012-04-06 00:50:38 +0200 (Fri, 06 Apr 2012) $
 * $Rev: 2488 $
 * $Author: gavinspearhead $
 * $Id: queue.php 2488 2012-04-05 22:50:38Z gavinspearhead $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class nzb_poller
{
    const POLL_INTERVAL = 15; // seconds
    private static $last_checked = 0;

    public static function poll_nzb_dir(DatabaseConnection $db, server_data &$servers)
    {
        $now = time();
        if (self::$last_checked + self::POLL_INTERVAL > $now) {
            return;
        }
        self::$last_checked = $now;
        try {
            $dl_path_basis = get_dlpath($db);
            $dl_path = $dl_path_basis . SPOOL_PATH;
            $users = get_all_users($db);
            foreach ($users as $user) {
                $path = $dl_path . $user . DIRECTORY_SEPARATOR;
                if (is_dir($path)) {
                    $d = dir($path);
                    while (FALSE !== ($entry = $d->read())) {
                        if (substr($entry, -4) == '.nzb') {
                            $new_name = find_unique_name($path, '', $entry, '.processing', TRUE);
                            rename($path . $entry, $new_name);
                            $userid = get_userid($db, $user);
                            $new_name = addslashes($new_name);
                            queue_parse_nzb($db, $servers, array($new_name), $userid);
                        }
                    }
                    $d->close();
                }
            }
        } catch (exception $e) {
            return;
        }
    }
}
