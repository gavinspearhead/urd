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

class config_cache
{
    const CACHE_TIMEOUT = 15; // seconds

    private static $data = NULL;
    private static $timestamp = 0;
    public static function store($value, $userid, $index=NULL)
    {
        assert(is_numeric($userid));
        if (isset($_SESSION)) {
            // we are a webbased thingie so we can store in session
            if ($index === NULL) {
                $_SESSION['urd_cache'][$userid] = $value;
            } else {
                $_SESSION['urd_cache'][$userid][$index] = $value;
            }
            $_SESSION['urd_cache']['timestamp'] = time();
        } else {
            // we must be urdd so we take our own storage;
            if ($index === NULL) {
                self::$data[$userid] = $value;
            } else {
                self::$data[$userid][$index] = $value;
            }
            self::$timestamp = time();
        }
    }
    public static function get($userid, $index = NULL)
    {
        assert(is_numeric($userid));
        if (isset($_SESSION)) {
            // we are a webbased thingie so we can store in session
            if (isset($_SESSION['urd_cache']['timeout']) && ($_SESSION['urd_cache']['timeout'] + self::CACHE_TIMEOUT <= time())) {
                unset($_SESSION['urd_cache']);
            }
            if (isset($_SESSION['urd_cache'][$userid])) {
                if ($index === NULL) {
                    return $_SESSION['urd_cache'][$userid];
                } else {
                    return get_from_array($_SESSION['urd_cache'][$userid], $index, FALSE);
                }
            }

            return FALSE;
        } elseif (isset(self::$data[$userid])) {
            // we must be urdd so we take our own storage;
            if ($index === NULL) {
                $val = self::$data[$userid];
            } else {
                $val = get_from_array(self::$data[$userid], $index, FALSE);
            }
            if ($val !== FALSE && isset(self::$data[$userid]) && (self::$timestamp + self::CACHE_TIMEOUT <= time())) {
                    // we don't want to cache for ever, but just be on the save side
                unset(self::$data[$userid]);
            }

            return $val;
        }

        return FALSE;
    }
    public static function clear($index)
    {
        if (isset($_SESSION)) {
            if (isset($_SESSION['urd_cache'][$index])) { // we are a webbased thingie so we can store in session
                unset($_SESSION['urd_cache'][$index]);
            }
        } elseif (isset(self::$data[$index])) { // we must be urdd so we take our own storage;
            unset(self::$data[$index]);
        }
    }
    public static function clear_all()
    {
        if (isset($_SESSION)) {
            if (isset($_SESSION['urd_cache'])) { // we are a webbased thingie so we can store in session
                unset($_SESSION['urd_cache']);
            }
        } elseif (isset(self::$data[$index])) { // we must be urdd so we take our own storage;
            self::$data = NULL;
        }
    }
}


function get_config(DatabaseConnection $db, $name, $default = NULL)
{
    global $LN;
    assert ($name != '');

    $val = config_cache::get(user_status::SUPER_USERID);

    if (isset($val[$name])) {
        return $val[$name];
    }
    $rprefs = load_config($db, TRUE);
    if (isset($rprefs[$name])) {
        return $rprefs[$name];
    } else {
        if ($default === NULL) {
            throw new exception ($LN['error_prefnotfound'] . '(' . $name . ')', ERR_INVALID_OPTION);
        } else {
            return $default;
        }
    }
}


function get_pref(DatabaseConnection $db, $name, $username, $default=NULL)
{
    global $LN;
    assert ($name != '');
    if (is_numeric($username)) {
        $userid = $username;
    } else { 
        $userid = get_userid($db, $username);
    }
    $val = config_cache::get($userid);
    if (isset($val[$name])) {
        return $val[$name];
    }
    $prefs = load_prefs($db, $userid, TRUE);
    if (isset($prefs[$name])) {
        return $prefs[$name];
    } else {
        if ($default === NULL) {
            throw new exception ($LN['error_prefnotfound']. '(' . $name . ')', ERR_INVALID_OPTION);
        } else {
            return $default;
        }
    }
}


function load_config(DatabaseConnection $db, $force = FALSE)
{
    if (!$force) {
        $val = config_cache::get(user_status::SUPER_USERID);
        if ($val !== FALSE) {
            return $val;
        }
    }

    $res = $db->select_query('"option", "value" FROM preferences WHERE "userID" = 0');
    if (!is_array($res)) {
        $res = array();
    }
    $prefs = array();

    foreach ($res as $row) {
        $prefs[$row['option']] = $row['value'];
    }
    config_cache::store($prefs, user_status::SUPER_USERID);

    return $prefs;
}


function load_prefs(DatabaseConnection $db, $username, $force = FALSE)
{
    if (!is_numeric($username)) {
        $userid = get_userid($db, $username);
    } else {
        $userid = $username;
        $username = get_username($db, $userid);
    }
    if (!$force) {
        $val = config_cache::get($userid);
        if ($val !== FALSE) {
            return $val;
        }
    }
    $query = "\"userID\", \"option\", \"value\" FROM preferences WHERE \"userID\"= '$userid'";

    $res = $db->select_query($query);
    if (!is_array($res)) {
        $res = array();
    }

    $prefs = array();
    $uid = NULL;
    foreach ($res as $row) {
        $prefs[$row['option']] = $row['value'];
        $uid = $row['userID'];
    }
    if ($uid !== NULL) {
        config_cache::store($prefs, $uid);
        if (!is_numeric($username)) {
            config_cache::store($prefs, $userid);
        }
    }

    return $prefs;
}


function set_prefs(DatabaseConnection $db, $userid, array $settings)
{
    assert(is_numeric($userid));
    foreach ($settings as $n1 => $v1) {
        if ($n1 == 'parameters') {
            foreach ($v1 as $n2 => $v2) {
                set_pref($db, $n2, $v2, $userid);
            }
        } elseif ($n1 == 'userfeedinfo') {
            foreach ($v1 as $n2 => $v2) {
                try {
                    $feed_id = get_feed_by_name($db, $v2['f_name']);
                    $minsetsize = $v2['minsetsize'];
                    $maxsetsize = $v2['maxsetsize'];
                    $visible = $v2['visible'];
                    $category = category_by_name($db, $v2['c_name'], $userid);
                    set_userfeedinfo($db, $userid, $feed_id, $minsetsize, $maxsetsize, $visible, $category);
                } catch (exception $e) {
                }
            }
        } elseif ($n1 == 'usergroupinfo') {
            foreach ($v1 as $n2 => $v2) {
                try {
                    $group_id = group_by_name($db, $v2['g_name']);
                    $minsetsize = $v2['minsetsize'];
                    $maxsetsize = $v2['maxsetsize'];
                    $visible = $v2['visible'];
                    $category = category_by_name($db, $v2['c_name'], $userid);
                    set_usergroupinfo($db, $userid, $group_id, $minsetsize, $maxsetsize, $visible, $category);
                } catch (exception $e) {
                }
            }
        } elseif ($n1 == 'categories') {
            foreach ($v1 as $n2 => $v2) {
                insert_category($db, $userid, $v2);
            }
        }
    }
}


function set_configs(DatabaseConnection $db, array $settings)
{
    foreach ($settings as $n => $v) {
        set_config($db, $n, $v);
    }
}


function set_pref(DatabaseConnection $db, $name, $value, $userid)
{
    assert ($name != '' && is_numeric($userid));
    if ($userid == '0') { // we're trying to set root prefs... these are the config tho
        set_config($db, $name, $value);

        return;
    }
    $dbname = $name;
    $dbuserid = $userid;
    $db->escape($dbname, TRUE);
    $db->escape($dbuserid, TRUE);
    $res = $db->select_query("\"value\", \"userID\" FROM preferences WHERE \"option\" = $dbname AND \"userID\" = $dbuserid", 1);
    if ($res !== FALSE) {
        $db->escape($value, TRUE);
        $db->execute_query("UPDATE \"preferences\" SET \"value\" = $value WHERE \"option\" = $dbname AND \"userID\" = $dbuserid");
    } else {
        $db->insert_query('preferences', array ('value', 'option', 'userID'), array ($value, $name, $userid));
    }
    config_cache::clear($userid);
}

function set_config(DatabaseConnection $db, $name, $value)
{
    assert ($name !== '');
    $dbname = $name;
    $db->escape($dbname, TRUE);
    $res = $db->select_query("\"value\" FROM preferences WHERE \"option\" = $dbname AND \"userID\" = 0", 1);
    if ($res !== FALSE) {
        $db->escape($value, TRUE);
        $db->execute_query("UPDATE preferences SET \"value\" = $value WHERE \"option\" = $dbname AND \"userID\" = 0");
    } else {
        $db->insert_query('preferences', array ('value', 'option', 'userID'), array ($value, $name, user_status::SUPER_USERID));
    }
    config_cache::clear(user_status::SUPER_USERID);
}

function clean_config(DatabaseConnection $db)
{
    config_cache::clear(user_status::SUPER_USERID);
    $db->delete_query('preferences', "\"userID\" = 0");
}

function clean_pref(DatabaseConnection $db, $userid)
{
    global $LN;
    assert(is_numeric($userid));
    if ($userid == user_status::SUPER_USERID) {
        throw new exception ($LN['error_resetnotallowed'], ERR_ACCESS_DENIED);
    }
    clean_categories($db, $userid);
    clean_usergroupinfo($db, $userid);
    $db->escape($userid, FALSE);
    $db->delete_query('preferences', " \"userID\" = '$userid'");
    config_cache::clear($userid);
}

function reset_pref(DatabaseConnection $db, $userid)
{
    global $LN;
    assert(is_numeric($userid));
    if ($userid == user_status::SUPER_USERID) {
        throw new exception ($LN['error_resetnotallowed'], ERR_ACCESS_DENIED);
    }
    $prefArray = get_default_prefs();

    foreach ($prefArray as $var => $val) {
        set_pref($db, $var, $val, $userid);
    }
}

function reset_config(DatabaseConnection $db)
{
    $prefArray = get_default_config();
    foreach ($prefArray as $var => $val) {
        set_pref($db, $var, $val, user_status::SUPER_USERID);
    }
}

function update_settings(DatabaseConnection $db, $userid)
{
    global $LN;
    assert (is_numeric($userid));
    if (get_username($db, $userid) === FALSE) {
        throw new exception ($LN['error_nosuchuser'], ERR_INVALID_USERNAME);
    }
    if ($userid == user_status::SUPER_USERID) {
        $default = get_default_config();
        $config = load_config($db);
        $diff = array_diff_key($default, $config);
    } else {
        $default = get_default_prefs();
        $prefs = load_prefs($db, $userid);
        $diff = array_diff_key($default, $prefs);
    }
    $cnt = count($diff);
    if ($cnt == 0) {
        return 0;
    }

    $arr = array();
    foreach ($diff as $var => $val) {
        $arr[] = array ($userid, $var, $val);
    }

    $db->insert_query('preferences', array ('userID', 'option', 'value'), $arr);

    return $cnt;
}

function check_prefs(DatabaseConnection $db, $sendmail=TRUE)
{
    global $LN;
    assert(is_bool($sendmail));
    $qry = '"ID" FROM users';
    $res = $db->select_query($qry);
    if ($res === FALSE) {
        throw new exception ($LN['error_nousersfound'], ERR_NO_USERS);
    }
    foreach ($res as $row) {
        $userid = $row['ID'];
        $cnt = update_settings($db, $userid);
        if ($cnt > 0) {
            if ($userid == user_status::SUPER_USERID) {
                write_log('New configuration parameters added; check admin/config', LOG_WARNING);
            } else {
                write_log("New preferences added for user $userid; check preferences", LOG_WARNING);
                if ($sendmail === TRUE) {
                    urd_mail::mail_update_preferences($db, $userid);
                }
            }
        }
    }
}
