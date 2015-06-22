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

$pathusf = realpath(dirname(__FILE__));
require_once "$pathusf/libs/rsa.php";
require_once "$pathusf/libs/signing/signing_base.php";
require_once "$pathusf/libs/signing/signing_openssl.php";
require_once "$pathusf/libs/signing/signing_php.php";

class urd_user_rights
{
    const RIGHTS_STR = 'cCrRuUdDPpAaFfEeMm';
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
        assert(is_numeric($userid));
        $seteditorrights = "%$rights%";
        $search_type = $db->get_pattern_search_command('LIKE'); // postgres doesn't like regexp, but uses similar .... I just love standards

        $qry = "\"rights\" FROM users WHERE \"ID\"=? AND \"rights\" $search_type ?";
        $rv = $db->select_query($qry, array($userid, $seteditorrights));

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
        $qry = '"rights" FROM users WHERE "ID"=?';
        $rv = $db->select_query($qry, array($userid));
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
        $rv = $db->update_query_2('users', array('rights'=>$rights), '"ID"=?', array($userid));
    }

    public static function is_admin(DatabaseConnection $db, $userid)
    {
        assert(is_numeric($userid));
        $qry = '"isadmin" FROM users WHERE "ID" = :userid';
        $rv = $db->select_query($qry, array(':userid'=>$userid));
        if (!is_array($rv)) {
            return FALSE;
        }
        if ($rv[0]['isadmin'] >= 1) {
            return TRUE;
        }

        return FALSE;
    }

    public static function has_rights(DatabaseConnection $db, $userid, $rights)
    {
        if ($rights == '') {
            return FALSE;
        }
        $perm = TRUE;
        for ($i = 0; $i < strlen($rights); $i++) {
            $perm = $perm && urd_user_rights::check_rights($db, $userid, $rights[$i]);
        }

        return $perm;
    }
}

function valid_username($username, $may_exist=0)
{ // may_exist: 0 -- don't care, 1 must exist, 2 must not exist
    if ($may_exist != 0) {
        global $db, $LN;
        $res = $db->select_query('"ID" FROM users WHERE "name"=:name', array(':name'=>$username));
        if ($res !== FALSE && $may_exist == 2) {
            throw new exception($LN['error_userexists'], ERR_INVALID_USERNAME);
        } elseif ($res === FALSE && $may_exist == 1) {
            throw new exception($LN['error_nosuchuser'], ERR_INVALID_USERNAME);
        }
    }
    if (is_numeric($username)) {
        return FALSE;
    }

    return ((preg_match('/^([A-Za-z_0-9]){3,}$/', $username) == 1) && (strtolower($username) != 'root'));
}

function create_root(DatabaseConnection $db)
{
    $db->insert_query('users', array('name', 'fullname', 'email', 'pass', 'ipaddr', 'isadmin', 'active','regtime', 'rights', 'salt'),
            array('root', '', '', 'nologin', '', user_status::USER_ADMIN, user_status::USER_INACTIVE,0, '', ''));
    $db->update_query_2('users', array('ID'=>user_status::SUPER_USERID), 'name=?', array(user_status::SUPER_USER) ); // force the user ID to 0 doesn't always do that by default due to auto inc
    $pref_array = get_default_config();
    foreach ($pref_array as $var => $val) {
        $db->insert_query('preferences', array ('userID', 'option', 'value'), array(user_status::SUPER_USERID, $var, $val));
    }
}

function verify_email($email)
{ // nasty function but hey it works ;-)
  /*  $expr = '[a-z0-9!#$%&\'*+\/=?^_`{|}~\-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~\-]+)*@((?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[a-z]{2}|com|org|net|gov|mil|biz|int|mobi|name|aero|biz|info|jobs|museum|asia|arpa|cat|coop|edu|pro|tel|travel|academy|agency|bargains|bid|bike|blue|email|eus|guru|xyz|zone|works|wiki|wed|webcam|watch|voyage|villas|viajes|ventures|vacation|uno|travel|training|trade|today|tips|tienda|tel|technology|tattoo|systems|support)|localhost)\b';

    return preg_match("/^$expr$/i", $email) == 1;*/

    return (filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE);

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


    $res = $db->select_query('"ID" FROM users WHERE "ID"=:userid', 1, array(':userid'=>$userid));
    if ($res === FALSE) {
        throw new exception ($LN['error_nosuchuser'] . ": $userid");
    }
    $cols = array('name', 'fullname', 'email', 'isadmin', 'active', 'rights');
    $vals = array($username, $fullname, $email, $isadmin, $active, $rights);
    $db->update_query('users', $cols, $vals, '"ID"=?', array($userid));
    if ($db->get_error_code() != 0) {
        throw new exception($LN['error_userexists'], ERR_ACCESS_DENIED);
    }

    return TRUE;
}

function check_user(DatabaseConnection $db, $username)
{
    $query = '"ID" FROM users WHERE "name"=:username';
    $res = $db->select_query($query, 1, array(':username'=>$username));

    return (isset($res[0]['ID'])) ? TRUE : FALSE;
}

function add_user(DatabaseConnection $db, $username, $fullname, $email, $password, $isadmin, $active, $rights, $plain_password=TRUE, $salt='')
{
    global $LN;

    if ($plain_password === TRUE) {
        $pw_ver = verify_password($password, $username);
        if ($pw_ver !== TRUE) {
            throw new exception($pw_ver);
        }
        $salt = generate_password(8);
        $password = hash('sha256', $salt . $password . $salt);
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

    $res = $db->select_query('"ID" FROM users WHERE "name"=?', 1, array($username));
    if ($res !== FALSE) {
        throw new exception($LN['error_userexists'], ERR_INVALID_USERNAME);
    }

    $time = time();

    $user_key = generate_keypair();
    $publickey = $user_key['public'];
    $privatekey = $user_key['private'];
    $cols = array('name', 'fullname', 'email', 'pass', 'isadmin', 'active', 'regtime', 'rights', 'salt', 'publickey', 'privatekey');
    $vals = array($o_username, $fullname, $email, $password, $isadmin, $active, $time, $rights, $salt, $publickey, $privatekey);
    $db->insert_query('users', $cols, $vals);
    $res = $db->select_query('"ID" FROM users WHERE "name"=?', array($username));
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

    $db->delete_query('users', '"ID"=?', array($userid));
    $db->delete_query('preferences', '"userID"=?', array($userid));
    $db->delete_query('usergroupinfo', '"userid"=?', array($userid));
    $db->delete_query('userfeedinfo', '"userid"=?', array($userid));
    $db->delete_query('usersetinfo', '"userID"=?', array($userid));
    $db->delete_query('categories', '"userid"=?', array($userid));
}

function get_active_users(DatabaseConnection $db)
{
    $sql = '"ID" FROM users WHERE "active" = ?';

    return $db->select_query($sql, array(user_status::USER_ACTIVE));
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
    $db->update_query_2('users', array('ipaddr'=>$ipaddr), '"ID"=?', array($userid));
}

function set_last_login(DatabaseConnection $db, $last_login, $userid)
{
    assert(is_numeric($userid) && is_numeric($last_login));
    $db->update_query_2('users', array('last_login'=>$last_login), '"ID"=?', array($userid));
}

function set_last_active(DatabaseConnection $db, $last_active, $userid)
{
    assert(is_numeric($userid) && is_numeric($last_active));
    $db->update_query_2('users', array('last_active'=>$last_active), '"ID"=?', array($userid));
}

function get_username(DatabaseConnection $db, $userid)
{
    $res = $db->select_query('"name" FROM users WHERE "ID"=?', 1, array($userid));

    return (!isset($res[0]['name'])) ? FALSE : $res[0]['name'];
}

function get_userid(DatabaseConnection $db, $username)
{
    $res = $db->select_query('"ID" FROM users WHERE "name"=?', 1, array($username));

    return isset($res[0]['ID']) ? $res[0]['ID'] : FALSE;
}

function get_all_userids(DatabaseConnection $db)
{
    global $LN;
    $sql = '"ID" FROM users WHERE "ID" > 0';
    $res = $db->select_query($sql);
    if (!is_array($res)) {
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
    if (!is_array($res)) {
        throw new exception($LN['error_nousersfound']);
    }

    return $res;
}

function get_all_users(DatabaseConnection $db)
{
    global $LN;
    $sql = '"name" FROM users WHERE "ID" > 0';
    $res = $db->select_query($sql);
    if (!is_array($res)) {
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
    $sql = '"salt" FROM users WHERE "name"=? AND "active"=?';
    $res = $db->select_query($sql, 1, array($username, user_status::USER_ACTIVE));
    if (!isset($res[0]['salt'])) { // no valid user found
        global $LN;
        throw new exception($LN['error_nosuchuser'], ERR_NO_SUCH_USER);
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

function set_user_keys(DatabaseConnection $db, $userid, $user_key)
{
    $publickey = $user_key['public'];
    $privatekey = $user_key['private'];
    $rv = $db->update_query_2('users', array('publickey'=>$publickey, 'privatekey'=>$privatekey), '"ID"=?', array($userid));

}

function generate_keypair()
{
    global $pathusf;
    $signing = Services_Signing_Base::factory();
    $keypair = $signing->createPrivateKey($pathusf . '/libs/signing/openssl.cnf');

    return $keypair;
}

function generate_server_keys(DatabaseConnection $db)
{
    echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    if (get_config($db, 'privatekey', '') == '' || get_config($db, 'publickey', '') == '') {
        $server_key = generate_keypair();
        set_config($db, 'privatekey', $server_key['private']);
        set_config($db, 'publickey', $server_key['public']);
    }
}

function generate_user_keys(DatabaseConnection $db)
{
    echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    $sql = '"ID" FROM users WHERE "ID" > 0 AND ( "privatekey" = \'\' OR "publickey" = \'\' )';
    $res = $db->select_query($sql);
    if (!is_array($res)) {
        return;
    }
    foreach ($res as $user) {
        $userid = $user['ID'];
        $user_key = generate_keypair();
        set_user_keys($db, $userid, $user_key);
    }
}

function get_user_public_key(DatabaseConnection $db, $userid)
{
    $sql = '"publickey" FROM users WHERE "ID"=?';
    $res = $db->select_query($sql, 1, array($userid));
    if (isset($res[0]['publickey'])) {
        return $res[0]['publickey'];
    }

    return FALSE;
}

function get_user_private_key(DatabaseConnection $db, $userid)
{
    $sql = '"privatekey" FROM users WHERE "ID" = ?';
    $res = $db->select_query($sql, 1, array($userid));
    if (isset($res[0]['privatekey'])) {
        return $res[0]['privatekey'];
    }

    return FALSE;
}

function pubkey_to_xml(array $pubkey)
{
    return '<RSAKeyValue><Modulus>' . $pubkey['modulo'] . '</Modulus><Exponent>' . $pubkey['exponent'] . '</Exponent></RSAKeyValue>';
}

function verify_password($password, $username = '')
{
    global $LN;
    $len = strlen($password);
    if ($len < MIN_PASSWORD_LENGTH) {
        return $LN['error_pwlength'];
    }
    if ($username != '' && levenshtein($password, $username) < (MIN_PASSWORD_LENGTH/2)) {
        return $LN['error_pwusername'];
    }
    return TRUE;
}

