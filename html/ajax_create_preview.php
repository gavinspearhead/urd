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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_create_preview.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathajcp = realpath(dirname(__FILE__));

require_once "$pathajcp/../functions/ajax_includes.php";
try {
    challenge::verify_challenge($_POST['challenge']);
    $pbin_id = get_post('preview_bin_id');
    $pgroup_id = get_post('preview_group_id');
    $dlid = start_preview($db, $pbin_id, $pgroup_id, $userid);
    return_result(array('dlid' => $dlid));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
