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
 * $LastChangedDate: 2014-06-07 14:53:28 +0200 (za, 07 jun 2014) $
 * $Rev: 3081 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_edit_searchoptions.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathaeb = realpath(dirname(__FILE__));

require_once "$pathaeb/../functions/ajax_includes.php";
require_once "$pathaeb/../functions/buttons.php";

verify_access($db, NULL, TRUE, '', $userid,  TRUE);

class buttons_c
{
    private $name;
    private $url;
    private $id;

    public function __construct ($id=0, $n='', $u='')
    {
        assert(is_numeric($id));
        $this->id = $id;
        $this->name = $n;
        $this->url = $u;
    }
    public function get_name()
    {
        return strip_tags($this->name);
    }
    public function get_url()
    {
        return $this->url;
    }
    public function get_id()
    {
        return $this->id;
    }
}

$cmd = get_request('cmd');
$id = get_request('id');

function import_settings(DatabaseConnection $db)
{
    global $LN;
    $xml = new urd_xml_reader($_FILES['filename']['tmp_name']);
    $buttons = $xml->read_buttons($db);
    if ($buttons != array()) {
        clear_all_buttons($db);
        set_all_buttons($db, $buttons);
    } else {
        throw new exception($LN['error_nosearchoptionsfound']);
    }
    die_html('OK');
}

function edit_button(DatabaseConnection $db, $id)
{
    global $LN, $smarty;
    if (is_numeric($id)) {
        $sql = '* FROM searchbuttons WHERE "id"=? ORDER BY "name" ASC';
        $res = $db->select_query($sql, 1, array($id));
        if (!isset($res[0])) {
            throw new exception($LN['buttons_buttonnotfound']);
        }
        $row = $res[0];
        $name = $row['name'];
        $search_url = $row['search_url'];
        $id = $row['id'];
        $button = new buttons_c($id, $name, $search_url);
    } elseif ($id == 'new') {
        $button = new buttons_c(0, '', '', '');
    } else {
        throw new exception($LN['buttons_buttonnotfound']);
    }
    init_smarty('', 0);
    $smarty->assign('id',	$id);
    $smarty->assign('text_box_size', TEXT_BOX_SIZE);
    $smarty->assign('number_box_size', NUMBER_BOX_SIZE);
    $smarty->assign('button',	$button);
    $smarty->display('ajax_edit_searchoptions.tpl');
    die;
}

function show_buttons(DatabaseConnection $db, $userid)
{
    global $smarty;
    $order_options = array ('name', 'search_url');
    $order_dirs = array ('desc', 'asc');
    $search = $o_search = trim(get_request('search', ''));
    $order = strtolower(get_request('sort'));
    $order_dir = strtolower(get_request('sort_dir'));
    $inputarr = array(0);
    if (!in_array($order, $order_options)) {
        $order = 'name';
    }
    if (!in_array($order_dir, $order_dirs)) {
        $order_dir = 'asc';
    }
    $Qsearch = '';
    if ($search != '') {
        $search = "%$search%";
        $like = $db->get_pattern_search_command('LIKE');
        $Qsearch = " AND \"name\" $like ? ";
        $inputarr[] = $search;
    }

    $sql = " * FROM searchbuttons WHERE \"id\" > ? $Qsearch ORDER BY $order $order_dir ";
    $res = $db->select_query($sql, $inputarr);
    if (!is_array($res)) {
        $res = array();
    }
    $cnt = 0;
    $buttons = array();
    foreach ($res as $row) {
        $name = $row['name'];
        $search_url = $row['search_url'];
        $id = $row['id'];
        $buttons[] = new buttons_c($id, $name, $search_url);
    }
    init_smarty('', 0);
    $smarty->assign('maxstrlen', get_pref($db, 'maxsetname', $userid));
    $smarty->assign('sort', $order);
    $smarty->assign('sort_dir', $order_dir);
    $smarty->assign('search', $o_search);
    $smarty->assign('buttons', $buttons);
    $smarty->display('ajax_show_searchoptions.tpl');
    die;
}

function update_button_info(DatabaseConnection $db, $id)
{
    global $LN;

    challenge::verify_challenge($_POST['challenge']);
    if (is_numeric($id)) {
        if (isset($_POST['name']) && trim($_POST['name']) != '') {
            $name = trim($_POST['name']);
        } else {
            throw new exception($LN['buttons_invalidname']);
        }
        if (isset($_POST['search_url']) && $_POST['search_url'] != '') {
            $search_url = $_POST['search_url'];
        } else {
            throw new exception($LN['buttons_invalidurl']);
        }
        if (isset($_POST['id']) && $_POST['id'] != '' && $id == $_POST['id']) {
           // $id = $_POST['id'];
        } else {
            throw new exception($LN['buttons_invalidurl']);
        }
        $query = '"id" FROM searchbuttons WHERE "id"=?';
        $res = $db->select_query($query, 1, array($id));
        if ($res !== FALSE) {
            update_button($db, $name, $search_url, $id);
        } else {
            throw new exception($LN['buttons_buttonexists']);
        }
    } elseif ($id == 'new') {
        if (isset($_POST['name']) && trim($_POST['name']) != '') {
            $name = trim($_POST['name']);
        } else {
            throw new exception($LN['buttons_invalidname']);
        }
        if (isset($_POST['search_url']) && $_POST['search_url'] != '') {
            $search_url = $_POST['search_url'];
        } else {
            throw new exception($LN['buttons_invalidurl']);
        }
        $query = '"id" FROM searchbuttons WHERE "name"=?';
        $res = $db->select_query($query, array($name));
        if ($res === FALSE) {
            add_button($db, new button($name, $search_url));
        } else {
            throw new exception($LN['buttons_buttonexists']);
        }
    } else {
        throw new exception($LN['buttons_buttonnotfound']);
    }
}

switch (strtolower($cmd)) {
    case 'import_settings':
        challenge::verify_challenge($_POST['challenge']);
        import_settings($db);
        break;
    case 'export_settings':
        export_settings($db, 'buttons', 'urd_search_settings.xml');
    case 'delete_button':
        challenge::verify_challenge($_POST['challenge']);
        if (is_numeric($id)) {
            delete_button($db, $id);
        } else {
            throw new exception($LN['buttons_buttonnotfound']);
        }
        break;
    case 'edit':
        edit_button( $db, $id);
        break;
    case 'show_buttons':
        show_buttons($db, $userid);
        break;
    case 'update_button':
        update_button_info($db, $id);
        break;
    default:
        throw new exception ($LN['error_invalidaction'] . " $cmd");
        break;
}

die_html('OK');
