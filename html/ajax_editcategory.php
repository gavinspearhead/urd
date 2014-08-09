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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_editcategory.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';

$pathaec = realpath(dirname(__FILE__));

require_once "$pathaec/../functions/ajax_includes.php";
require_once "$pathaec/../functions/mail_functions.php";

try {

    $cmd = get_request('cmd', '');

    switch ($cmd) {
        case 'delete_category':
            challenge::verify_challenge($_POST['challenge']);
            $id = get_request('id');
            if (is_numeric($id) && $id > 0) {
                delete_category($db, $id, $userid);
            } else {
                throw new exception($LN['error_invalidid']);
            }
            break;
        case 'update_category':
            challenge::verify_challenge($_POST['challenge']);
            $id = get_request('id');
            $name = get_request('name', '');
            if ($name == '') {
                throw new exception($LN['error_searchnamenotfound']);
            }
            if ($id == 'new' || $id == 0) {
                insert_category($db, $userid, $name);
            } elseif (is_numeric($id)) {
                update_category($db, $id, $userid, $name);
            } else {
                throw new exception($LN['error_invalidid']);
            }
            break;
        case 'get_name':
            $id = get_request('id');
            if (!is_numeric($id)) {
                throw new exception($LN['error_invalidid']);
            }
            $name = get_category($db, $id, $userid);
            if ($name == '') {
                throw new exception($LN['error_searchnamenotfound']);
            } else {
                return_result(array('name' => $name));
            }
            break;

        case 'edit':
            $categories = get_categories($db, $userid);
            foreach ($categories as &$cat) {
                $cat['name'] = $cat['name'];
            }
            init_smarty('', 0);
            $smarty->assign('categories',	    $categories);
            $smarty->assign('text_box_size',	TEXT_BOX_SIZE);
            $contents = $smarty->fetch('ajax_editcategories.tpl');
            return_result(array('contents' => $contents));
            break;
        default:
            throw new exception($LN['error_invalidaction'] . " $cmd");
            break;
    }

    return_result();
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
