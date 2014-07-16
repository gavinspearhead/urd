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
 * $LastChangedDate: 2014-06-21 23:20:44 +0200 (za, 21 jun 2014) $
 * $Rev: 3105 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_markread.php 3105 2014-06-21 21:20:44Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathmr = realpath(dirname(__FILE__));
require_once "$pathmr/../functions/ajax_includes.php";

if (!isset( $_GET['setid'], $_GET['cmd'], $_GET['type'])) {
    throw new exception('Parameter missing');
}

$setid = get_request('setid');
$type = get_request('type');
$cmd = get_request('cmd');

if (!in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT))) {
    throw new exception($LN['error_invalidvalue']);
}
try {
switch (strtolower($cmd)) {
    case 'hide' :
        sets_marking::mark_set($db, $userid, $setid, 'statuskill', $type, 1);
        break;
    case 'unhide':
        sets_marking::mark_set($db, $userid, $setid, 'statuskill', $type, 255);
        break;
    case 'interesting':
        sets_marking::mark_set($db, $userid, $setid, 'statusint', $type, 1, 255);
        break;
    case 'wipe':
        challenge::verify_challenge($_REQUEST['challenge']);
        if ($isadmin) {
            wipe_sets($db, array($setid), $type, $userid);
        }
        break;
    default:
        throw new exception($LN['error_invalidaction']);
        break;
}
}catch (exception $e) {
    var_dump($e);
}
die_html('OK');
