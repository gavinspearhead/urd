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
 * $LastChangedDate: 2014-06-03 17:23:08 +0200 (di, 03 jun 2014) $
 * $Rev: 3080 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_edit_users.php 3080 2014-06-03 15:23:08Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';
$pathaau = realpath(dirname(__FILE__));

require_once "$pathaau/../functions/ajax_includes.php";
require_once "$pathaau/../functions/mail_functions.php";

verify_access($db, NULL, TRUE, '', $userid, TRUE);

$prefs_root = load_config($db);

class users_c
{
    public $username;
    public $fullname;
    public $email;
    public $password;
    public $admin;
    public $active;
    public $id;
    public $rights;
    public function __construct ($id='', $u='', $fn='', $e='', $p='', $admin=0, $act=1, $r='c', $la=0)
    {
        global $LN;
        assert (is_numeric($id));
        $this->username = $u;
        $this->fullname = $fn;
        $this->email = $e;
        $this->password = $p;
        $this->admin = $admin;
        $this->active = $act;
        $this->id = $id;

        if ($this->fullname == '') {
            $this->fullname = '-';
        }

        // Turn them into seperate variables for easy smartying:
        $rights = str_split(strtolower($r));
        $rightsarray = array();
        foreach ($rights as $letter) {
            $rightsarray[$letter] = 1;
        }
        $this->rights = $rightsarray;
        if ($la != 0) {
            $this->last_active = readable_time(time() - $la, 'largest_long');
        } else {
            $this->last_active = $LN['never'];
        }
    }
}

$cmd = get_request('cmd', FALSE);
$id = get_request('id', FALSE);
switch ($cmd) {
case FALSE:
    throw new exception('No command found');
case 'export_settings':
    export_settings($db, 'all_user_settings', 'urd_users.xml');
    break;
case 'import_settings':
    if (isset ($_FILES['filename']['tmp_name'])) {
        try {
            $xml = new urd_xml_reader($_FILES['filename']['tmp_name']);
            $users = $xml->read_users($db);
            $settings = $xml->read_user_settings($db);
            if ($users != array()) {
                $prefs = $prefs_root;
                stop_urdd($userid);
                clear_all_users($db);
                set_all_users($db, $users, $settings);

                usleep(500000);
                start_urdd();
                redirect('admin_users.php');
            }
        } catch (exception $e) {
            echo_debug($e->getMessage(), DEBUG_ALL);
            echo_debug_trace($e, 255);
        }

    } else {
        throw new exception('File not found');
    }
    break;
case 'edit':
    if (is_numeric($id)) {
        $sql = '* FROM users WHERE "ID" = ?';
        $res = $db->select_query($sql, 1, array($id));
        if ($res === FALSE) {
            throw new exception('User not found');
        }
        $row = $res[0];

        $password = $row['pass'];
        $email = $row['email'];
        $name = $row['name'];
        $fullname = $row['fullname'];
        $id = $row['ID'];
        $rights = strstr(strtolower($row['rights']), 'c')?1:0;
        $allow_erotica = strstr(strtolower($row['rights']), 'e')?1:0;
        $allow_update = strstr(strtolower($row['rights']), 'm')?1:0;
        $autodownload = strstr(strtolower($row['rights']), 'a')?1:0;
        $post = strstr(strtolower($row['rights']), 'p')?1:0;
        $file_edit = strstr(strtolower($row['rights']), 'f')?1:0;
        $last_active = $row['last_active'];
        $isadmin = $row['isadmin'];
        $isactive = $row['active'];
    } elseif ($id == 'new') {
        $password = '';
        $autodownload = '';
        $email = '';
        $name = '';
        $fullname ='';
        $rights = 0;
        $allow_update = 0;
        $allow_erotica = 0;
        $post = 0;
        $file_edit =
        $last_active = '';
        $isadmin = user_status::USER_USER;
        $isactive = user_status::USER_ACTIVE;
    } else {
        throw new exception('ID not found');
    }

    $email_allowed = get_config($db, 'sendmail');
    init_smarty('', 0);
    $smarty->assign('USER_ADMIN',   user_status::USER_ADMIN);
    $smarty->assign('USER_ACTIVE',  user_status::USER_ACTIVE);
    $smarty->assign('USER_PENDING', user_status::USER_PENDING);
    $smarty->assign('id', $id);
    $smarty->assign('password', $password);
    $smarty->assign('email', $email);
    $smarty->assign('text_box_size', TEXT_BOX_SIZE);
    $smarty->assign('number_box_size', NUMBER_BOX_SIZE);
    $smarty->assign('name', $name);
    $smarty->assign('fullname', $fullname);
    $smarty->assign('rights', $rights);
    $smarty->assign('emailallowed', $email_allowed?1:0);
    $smarty->assign('post', $post);
    $smarty->assign('file_edit', $file_edit);
    $smarty->assign('isadmin', $isadmin);
    $smarty->assign('allow_erotica', $allow_erotica?1:0);
    $smarty->assign('allow_update', $allow_update?1:0);
    $smarty->assign('autodownload', $autodownload);
    $smarty->assign('isactive', $isactive );
    $smarty->assign('last_active', $last_active);
    $smarty->display('ajax_edit_users.tpl');
    die;
    break;
case 'resetpw':
    challenge::verify_challenge($_POST['challenge']);
    $newpw = generate_password(MIN_PASSWORD_LENGTH);
    $user_id = $id;

    $sql = '* FROM users WHERE "ID"=?';
    $res = $db->select_query($sql, array($user_id));
    if ($res === FALSE) {
        throw new exception($LN['error_nosuchuser']);
    }
    $email = $res[0]['email'];
    $fullname = $res[0]['fullname'];
    $name = $res[0]['name'];
    set_password($db, $user_id, $newpw, FALSE);
    try {
        urd_mail::mail_pw($db, $fullname, $name, $email, $newpw, get_config($db, 'admin_email'));
    } catch (exception $e) {
        write_log('Could not send email message', LOG_WARNING);
        throw new exception($LN['error_pwresetnomail'] . ': ' . $e->getmessage());
    }
    die_html('OK' . $LN['forgot_sent']);
    break;
case 'delete':
    challenge::verify_challenge($_POST['challenge']);
    delete_user($db, $id);
    die_html('OK');
    break;
case 'update_setting':
    $id = get_post('id', '');
    if (!is_numeric($id)) {
        throw new exception('No valid UID');
    }
    $action = get_post('action', NULL);
    $value = get_post('value', NULL);
    if (!in_array($value, array(0,1))) {
        throw new exception('No valid value');
    }

    switch ($action) {
    case 'admin':
        try {
            $db->update_query_3('users', array('isadmin'=>$value), '"ID"=?', array($id));
        } catch (exception $e) {
            throw new exception($e->getMessage());
        }

        break;
    case 'set_editor':
        try {
            urd_user_rights::set_user_rights($db, $id, 'C', $value);
        } catch (exception $e) {
            throw new exception($e->getMessage());
        }
        break;
    case 'posting':
        try {
            urd_user_rights::set_user_rights($db, $id, 'P', $value);
        } catch (exception $e) {
            throw new exception($e->getMessage());
        }
        break;
    case 'active':
        try {
            $db->update_query_2('users', array('active'=>$value), '"ID"=?', array($id));
            $sql = '"fullname", "name", "email", "active" FROM users WHERE "ID" = ?';
            $res = $db->select_query($sql, 1, array($id));
            if ($res === FALSE) {
                throw new exception('User not found');
            }
            $username = $res[0]['name'];
            $fullname = $res[0]['fullname'];
            $email = $res[0]['email'];
            $active = $res[0]['active'];
            urd_mail::mail_user_update($db, $username, $fullname, $email, $active, $prefs_root['admin_email'], $_SERVER['REMOTE_ADDR']);
        } catch (exception $e) {
            throw new exception($e->getMessage());
        }
        break;
    case 'fileedit':
        try {
            urd_user_rights::set_user_rights($db, $id, 'F', $value);
        } catch (exception $e) {
            throw new exception($e->getMessage());
        }
        break;
    default:
        throw new exception('No valid action');
        break;
    }
    break;

case 'update_user':
    //var_dump($_REQUEST);
    challenge::verify_challenge($_POST['challenge']);
    $rights = '';
    $fullname = get_post('fullname', '');
    $username = get_post('username', '');
    $password = get_post('password', '');
    $email = get_post('email', '');
    $isadmin = (get_post('isadmin', '') == '1') ? user_status::USER_ADMIN : user_status::USER_USER;
    $active = (get_post('isactive', '') == '1') ? user_status::USER_ACTIVE : user_status::USER_INACTIVE;
    if (get_post('allow_update', '') == '1') {
        $rights .= 'M';
    }
    if (get_post('allow_erotica', '') == '1') {
        $rights .= 'E';
    }
    if (get_post('seteditor', '') == '1') {
        $rights .= 'C';
    }
    if (get_post('fileedit', '') == '1') {
        $rights .= 'F';
    }

    if (get_post('post', '') == '1') {
        $rights .= 'P';
    }
    if (get_post('autodownload', '') == '1') {
        $rights .= 'A';
    }
    $email_allowed = get_config($db, 'sendmail');
    if (is_numeric($id)) {
        $sql = '"ID", "active" FROM users WHERE "ID"=?';
        $res = $db->select_query($sql, 1, array($id));
        if ($res === FALSE) {
            throw new exception($LN['error_nosuchuser']);
        }
        if ($email_allowed) {
            $password = NULL; // we don't allow to set a password manual if the mail new pw button is enabled
        }
        try {
            update_user($db, $id, $username, $fullname, $email, $isadmin, $active, $rights, $password);
        } catch (exception $e) {
            throw new exception($e->getMessage());
        }
        if ($email_allowed) {
            try {
                if ($res[0]['active'] != $active) { // we only send an message if the active status changed.
                    urd_mail::mail_user_update($db, $username, $fullname, $email, $active, $prefs_root['admin_email'], $_SERVER['REMOTE_ADDR']);
                }
            } catch (exception $e) {
                throw new exception($LN['error_userupnomail'] . ': ' . $e->getmessage());
            }
        }
    } elseif ($id == 'new') {
        $query = '"ID" FROM users WHERE "name"=?';
        $res = $db->select_query($query, 1, array($username));
        if ($res === FALSE) {
            try {
                add_user($db, $username, $fullname, $email, $password, $isadmin, $active, $rights);
            } catch (exception $e) {
                throw new exception ($e->getMessage());
            }
        } else {
            throw new exception($LN['error_userexists']. ' ' . $username);
        }
    } else {
        throw new exception('Unknown ID');
    }
    die_html('OK');
    break;
case 'reload_users':
    $users = array();
    $search = $o_search = (trim(get_request('search', '')));
    $sort = get_request('sort', 'name');
    $sort_dir = get_request('sort_dir', 'asc');
    $only_rows  = get_request('only_rows', 0);

    if (!in_array($sort, array('name', 'fullname', 'email', 'rights', 'last_active', 'isadmin', 'active'))) {
        $sort = 'name';
    }
    if (!in_array($sort_dir, array('asc', 'desc'))) {
       $sort_dir = 'asc';
    }
    $Qsearch = '';
    if ($search != '') {
        $search = "%$search%";
        $db->escape($search, TRUE);
        $like = $db->get_pattern_search_command('LIKE');
        $Qsearch = " AND \"name\" $like $search ";
    }

    // Display:
    $sql = "* FROM users WHERE \"ID\" > 0 $Qsearch ORDER BY \"$sort\" $sort_dir";
    $res = $db->select_query($sql);
    if (!is_array($res)) {
        $res = array();
    }
    foreach ($res as $row) {
        $password = $row['pass'];
        $email = $row['email'];
        $name = $row['name'];
        $fullname = $row['fullname'];
        $id = $row['ID'];
        $rights = strtolower($row['rights']);
        $last_active = $row['last_active'];
        $users[] = new users_c($id, $name, $fullname, $email, $password, $row['isadmin'], $row['active'], $rights, $last_active);
    }
    $email_allowed = get_config($db, 'sendmail');
    init_smarty('', 0);
    $smarty->assign('USER_ADMIN',   user_status::USER_ADMIN);
    $smarty->assign('USER_ACTIVE',  user_status::USER_ACTIVE);
    $smarty->assign('USER_PENDING', user_status::USER_PENDING);
    $smarty->assign('sort',         $sort);
    $smarty->assign('sort_dir',     $sort_dir);
    $smarty->assign('search',       $o_search);
    $smarty->assign('users',        $users);
    $smarty->assign('only_rows',        $only_rows);
    $smarty->assign('emailallowed', $email_allowed?1:0);
    $smarty->assign('maxstrlen',    $prefs['maxsetname']/3);
    $smarty->display('ajax_admin_users.tpl');
    die;
default:
    throw new exception ("O-oh - Unknown command $cmd");
    break;
}

die_html('OK');
