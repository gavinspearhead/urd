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
 * $LastChangedDate: 2011-04-05 20:00:36 +0200 (Tue, 05 Apr 2011) $
 * $Rev: 2113 $
 * $Author: gavinspearhead $
 * $Id: functions.php 2113 2011-04-05 18:00:36Z gavinspearhead $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class urd_user_rights
{
    const RIGHTS_STR ='cCrRuUdDPpAaFfEeMm';
    // C: seteditor
    // R: unused
    // U: unused
    // D: unused
    // P: posting/ uploading
    // A: autodownload
    // F: File editor
    // E: Erotica
    // M: Manage update/expires/gensets

    public static function check_rights(DatabaseConnection $db, $userid, $rights)
    {
        $seteditorrights = "%$rights%";
        $db->escape($seteditorrights, TRUE);
        $search_type = $db->get_pattern_search_command('LIKE'); // postgres doesn't like regexp, but uses similar .... I just love standards
        $db->escape($userid, TRUE);

        if (!is_numeric($userid)) {
            $qry = "\"rights\" FROM users WHERE \"ID\" = $userid AND \"rights\" $search_type $seteditorrights";
        } else {
            $qry = "\"rights\" FROM users WHERE \"name\" = $userid AND \"rights\" $search_type $seteditorrights";
        }
        $rv = $db->select_query($qry);

        return ($rv === FALSE) ? FALSE : TRUE;
    }

    public static function verify_rights($rights)
    {
        return (strspn($rights, self::RIGHTS_STR) != strlen($rights)) ? FALSE : TRUE;
    }


    public static function is_autodownloader(DatabaseConnection $db, $userid)
    {
        return self::check_rights($db, $userid, 'A');
    }


    public static function is_adult(DatabaseConnection $db, $userid)
    {
        return self::check_rights($db, $userid, 'E');
    }


    public static function is_updater(DatabaseConnection $db, $userid)
    {
        return self::check_rights($db, $userid, 'M');
    }


    public static function is_seteditor(DatabaseConnection $db, $userid)
    {
        return self::check_rights($db, $userid, 'C');
    }


    public static function is_poster(DatabaseConnection $db, $userid)
    {
        return self::check_rights($db, $userid, 'P');
    }


    public static function is_file_editor(DatabaseConnection $db, $userid)
    {
        return self::check_rights($db, $userid, 'F');
    }


    public static function set_user_rights(DatabaseConnection $db, $userid, $right, $value)
    {
        global $LN;
        assert(is_numeric($userid));
        $db->escape($userid, TRUE);
        $qry = "\"rights\" FROM users WHERE \"ID\" = $userid";
        $rv = $db->select_query($qry);
        if (!isset($rv[0]['rights'])) {
            throw new exception($LN['error_nosuchuser']);
        }
        $rights = $rv[0]['rights'];
        $rights = str_ireplace($right, '', $rights);
        $rights = trim($rights);
        if ($value) {
            $rights .= $right;
        }
        $rights = trim($rights);
        $db->escape($rights, TRUE);
        $qry = "UPDATE users SET \"rights\" = $rights WHERE \"ID\" = $userid";
        $rv = $db->execute_query($qry);
    }


    public static function is_admin(DatabaseConnection $db, $username)
    {
        if (is_numeric($username)) {
            $qry = "\"isadmin\" FROM users WHERE \"ID\" = $username";
        } else {
            $db->escape($username, TRUE);
            $qry = "\"isadmin\" FROM users WHERE \"name\" = $username";
        }
        $rv = $db->select_query($qry);
        if ($rv === FALSE) {
            return FALSE;
        }
        if ($rv[0]['isadmin'] >= 1) {
            return TRUE;
        }

        return FALSE;
    }
    public static function has_rights(DatabaseConnection $db, $username, $rights)
    {
        if ($rights == '') {
            return FALSE;
        }
        $perm = TRUE;
        for ($i = 0;  $i < strlen($rights); $i++) {
            $perm = $perm && urd_user_rights::check_rights($db, $username, $rights[$i]);
        }

        return $perm;
    }
}


function valid_username($username, $may_exist=0)
{ // may_exist: 0 -- don't care, 1 must exist, 2 must not exist
    if ($may_exist != 0) {
        global $db, $LN;
        $db_username = $username;
        $db->escape($db_username, TRUE);
        $res = $db->select_query("\"ID\" FROM users WHERE \"name\" = $db_username");
        if ($res !== FALSE && $may_exist == 2) {
            throw new exception($LN['error_userexists'], ERR_INVALID_USERNAME);
        } elseif ($res === FALSE && $may_exist == 1) {
            throw new exception($LN['error_nosuchuser'], ERR_INVALID_USERNAME);
        }
    }
    if (is_numeric($username)) {
        return FALSE;
    }

    return ((preg_match('/^([A-Za-z_0-9]){3,}$/', $username) === 1) && (strtolower($username) != 'root'));
}

function create_root(DatabaseConnection $db)
{
    $db->insert_query('users', array('name', 'fullname', 'email', 'pass', 'ipaddr', 'isadmin', 'active','regtime', 'rights', 'salt'),
            array('root', '', '', 'nologin', '', user_status::USER_ADMIN, user_status::USER_INACTIVE,0, '', ''));
    $query = 'UPDATE users SET "ID"=' . user_status::SUPER_USERID . ' WHERE "name"=\''. user_status::SUPER_USER . '\''; // force the user ID to 0 doesn't always do that by default due to auto inc
    $db->execute_query($query);
    $pref_array = get_default_config();
    foreach ($pref_array as $var => $val) {
        $db->insert_query('preferences', array ('userID', 'option', 'value'), array (user_status::SUPER_USERID, $var, $val));
    }
}


function verify_email($email)
{ // nasty function but hey it works ;-)
    $expr = '[a-z0-9!#$%&\'*+\/=?^_`{|}~\-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~\-]+)*@((?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[a-z]{2}|com|org|net|gov|mil|biz|int|mobi|name|aero|biz|info|jobs|museum|asia|arpa|cat|coop|edu|pro|tel|travel)|localhost)\b';

    return preg_match("/^$expr$/i", $email) == 1;
}

function clean_fullname($fullname)
{
    $fullname = strip_tags($fullname);
    $fullname = preg_replace('/[^\w- .()\']/', ' ', $fullname);

    return $fullname;
}

function update_user(DatabaseConnection $db, $userid, $username, $fullname, $email, $isadmin, $active, $rights, $password=NULL)
{
    global $LN;
    assert(is_numeric($userid));
    if (verify_email($email) === FALSE) {
        throw new exception($LN['error_invalidemail'], ERR_INVALID_EMAIL);
    }
    if (!valid_username($username)) {
        throw new exception ($LN['error_invalidusername'], ERR_INVALID_USERNAME);
    }
    $fullname = clean_fullname($fullname);

    if (urd_user_rights::verify_rights($rights) === FALSE) {
        throw new exception($LN['error_accessdenied'], ERR_ACCESS_DENIED);
    }

    if ($password !== NULL && $password != '') {
        set_password($db, $userid, $password, FALSE);
    }

    $db->escape($userid, TRUE);

    $res = $db->select_query("\"ID\" FROM users WHERE \"ID\"=$userid", 1);
    if ($res === FALSE) {
        throw new exception ($LN['error_nosuchuser'] . ": $userid");

        return FALSE;
    }
    $cols = array('name', 'fullname', 'email', 'isadmin', 'active', 'rights');
    $vals = array($username, $fullname, $email, $isadmin, $active, $rights);
    $db->update_query('users', $cols, $vals, "\"ID\"=$userid");
    if ($db->get_error_code() != 0) {
        throw new exception($LN['error_userexists'], ERR_ACCESS_DENIED);
    }

    return TRUE;
}

function check_user(DatabaseConnection $db, $username)
{
    $db->escape($username, TRUE);
    $query = "\"ID\" FROM users WHERE \"name\"=$username";
    $res = $db->select_query($query, 1);

    return (isset($res[0]['ID'])) ? TRUE : FALSE;
}

function add_user(DatabaseConnection $db, $username, $fullname, $email, $password, $isadmin, $active, $rights, $plain_password=TRUE, $salt='')
{
    global $LN;
    if ($plain_password === TRUE) {
        $salt = generate_password(8);
        $password = hash('sha256', $salt. $password . $salt);
    }
    $fullname = clean_fullname($fullname);
    $o_username = $username = trim($username);
    if (!valid_username($username)) {
        throw new exception ($LN['error_invalidusername'], ERR_INVALID_USERNAME);
    }
    if (verify_email($email) === FALSE) {
        throw new exception($LN['error_invalidemail'], ERR_INVALID_EMAIL);
    }
    if (urd_user_rights::verify_rights($rights) === FALSE) {
        throw new exception('Rights are Wrong!', ERR_ACCESS_DENIED); // XXX make lang thingie
    }

    $db->escape($username, TRUE);
    $res = $db->select_query("\"ID\" FROM users WHERE \"name\" = $username", 1);
    if ($res !== FALSE) {
        throw new exception($LN['error_userexists'], ERR_INVALID_USERNAME);
    }

    $time = time();
    $cols = array('name', 'fullname', 'email', 'pass', 'isadmin', 'active', 'regtime', 'rights', 'salt');
    $vals = array($o_username, $fullname, $email, $password, $isadmin, $active, $time, $rights, $salt);
    $db->insert_query('users', $cols, $vals);
    $res = $db->select_query("\"ID\" FROM users WHERE \"name\" = $username");
    if ($res === FALSE) {
        return FALSE;
    }
    $userid = $res[0]['ID'];
    $prefArray = get_default_prefs();
    $def_language = get_config($db, 'default_language');
    $prefArray['default_language'] = $def_language;
    $cols = array('userID', 'option', 'value');
    $vals = array();
    foreach ($prefArray as $var => $val) {
        $vals[] = array ($userid, $var, $val);
    }
    $db->insert_query('preferences', $cols, $vals);
    try {
        $path = get_dlpath($db);
        create_user_dirs($db, $path, $o_username);
    } catch (exception $e) {
        // dlpath not (yet) set ... in installer
    }

    return TRUE;
}

function delete_user(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    global $LN;
    if ($userid == 0) {
        throw new exception($LN['error_nodeleteroot']);
    }

    $db->escape($userid, TRUE);
    $db->delete_query('users', "\"ID\"=$userid");
    $db->delete_query('preferences', "\"userID\"=$userid");
    $db->delete_query('usergroupinfo', "\"userID\"=$userid");
    $db->delete_query('userfeedinfo', "\"userID\"=$userid");
    $db->delete_query('usersetinfo', "\"userID\"=$userid");
    $db->delete_query('categories', "\"userID\"=$userid");
}

function get_active_users(DatabaseConnection $db)
{
    $sql = "SELECT \"ID\" FROM users WHERE \"active\" = '" . user_status::USER_ACTIVE . "'";

    return $db->execute_query($sql);
}

function get_effective_username(DatabaseConnection $db, $username)
{
    $isadmin = urd_user_rights::is_admin($db, $username);

    return $isadmin ? user_status::SUPER_USER : $username;
}


function get_effective_userid(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $isadmin = urd_user_rights::is_admin($db, $userid);

    return $isadmin ? user_status::SUPER_USERID : $userid;
}

function set_login_ip(DatabaseConnection $db, $ipaddr, $userid)
{
    assert(is_numeric($userid));
    $db->escape($ipaddr, TRUE);
    $db->escape($userid, TRUE);
    $sql = "UPDATE users SET \"ipaddr\" = $ipaddr WHERE \"ID\" = $userid";
    $db->execute_query($sql);
}

function set_last_login(DatabaseConnection $db, $last_login, $userid)
{
    assert(is_numeric($userid) && is_numeric($last_login));
    $db->escape($last_login, TRUE);
    $db->escape($userid, TRUE);
    $db->execute_query("UPDATE users SET \"last_login\" = $last_login WHERE \"ID\" = $userid");
}

function set_last_active(DatabaseConnection $db, $last_active, $userid)
{
    assert(is_numeric($userid) && is_numeric($last_active));
    $db->escape($last_active, TRUE);
    $db->escape($userid, TRUE);
    $db->execute_query("UPDATE users SET \"last_active\" = $last_active WHERE \"ID\" = $userid");
}

function get_username(DatabaseConnection $db, $userid)
{
    $db->escape($userid, TRUE);
    $res = $db->select_query("\"name\" FROM users WHERE \"ID\"=$userid", 1);

    return ($res === FALSE) ? FALSE : $res[0]['name'];
}

function get_userid(DatabaseConnection $db, $username)
{
    $db->escape($username, TRUE);
    $res = $db->select_query("\"ID\" FROM users WHERE \"name\"=$username", 1);

    return isset($res[0]['ID']) ? $res[0]['ID'] : FALSE;
}

function get_all_userids(DatabaseConnection $db)
{
    global $LN;
    $sql = ' "ID" FROM users WHERE "ID" > 0';
    $res = $db->select_query($sql);
    if ($res === FALSE) {
        throw new exception($LN['error_nousersfound'] );
    }
    $ids = array();
    foreach ($res as $row) {
        $ids[] = $row['ID'];
    }

    return $ids;
}

function get_all_users_full(DatabaseConnection $db)
{
    global $LN;
    $sql = '* FROM users WHERE "ID" > 0';
    $res = $db->select_query($sql);
    if ($res === FALSE) {
        throw new exception($LN['error_nousersfound']);
    }

    return $res;
}

function get_all_users(DatabaseConnection $db)
{
    global $LN;
    $sql = '"name" FROM users WHERE "ID" > 0';
    $res = $db->select_query($sql);
    if ($res === FALSE) {
        throw new exception($LN['error_nousersfound']);
    }
    $names = array();
    foreach ($res as $row) {
        $names[] = $row['name'];
    }

    return $names;
}

function get_salt(DatabaseConnection $db, $username)
{
    assert ($username != '');
    $db->escape($username, TRUE);
    $sql = "\"salt\" FROM users WHERE \"name\" = $username AND \"active\" = '". user_status::USER_ACTIVE ."'";
    $res = $db->select_query($sql, 1);

    if ($res === FALSE) { // no valid user found
        global $LN;
        throw new exception($LN['error_nosuchuser'], ERR_NO_SUCH_SERVER);
    }

    return $res[0]['salt'];
}

function clear_all_users(DatabaseConnection $db)
{
    $users = get_all_userids($db);
    foreach ($users as $userid) {
//        if ($userid != 2) // XXX must remove -- for testing only
        delete_user($db, $userid);
    }
}

function set_all_users(DatabaseConnection $db, array $users, array $settings)
{
    foreach ($users as $user) {
        try {
            add_user($db, $user['username'], $user['fullname'], $user['email'], $user['password'], $user['isadmin'], $user['active'], $user['rights'], FALSE , $user['salt']);
        if (isset($settings[$user['username']])) {
            $userid = get_userid($db, $user['username']);
            reset_pref($db, $userid); // restore the default settings
            set_prefs($db, $userid, $settings[$user['username']]); // overwrite with loaded settings
        }
        } catch (exception $e) {
            // what do we do now?
        }
    }
}

function get_admin_userid(DatabaseConnection $db)
{
    $sql = '"ID" FROM users WHERE isadmin > 0 AND "ID" > 0';
    $res = $db->select_query($sql, 1);
    if (!isset($res[0]['ID'])) {
        throw new exception ('No admin found');
    }

    return $res[0]['ID'];
}
