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
 * $LastChangedDate: 2013-09-03 16:28:23 +0200 (di, 03 sep 2013) $
 * $Rev: 2910 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_delete_account.php 2910 2013-09-03 14:28:23Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathda = realpath(dirname(__FILE__));

require_once "$pathda/../functions/ajax_includes.php";

if (isset($_POST['delete_account']) && $_POST['delete_account'] == 1) {
    challenge::verify_challenge_text($_POST['challenge']);
    delete_user($db, $userid);
    die_html('OK' . $LN['account_deleted']);
} else {
    throw new exception($LN['failed']);
}
