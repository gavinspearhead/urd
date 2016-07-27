<?php
/*  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate$
 * $Rev$
 * $Author$
 * $Id$
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function get_usenet_server(DatabaseConnection $db, $id, $active = TRUE)
{
    assert(is_bool($active) && is_numeric($id));
    $prio_sql = '';
    if ($active === TRUE) {
        $prio_sql = ' AND "priority" > 0';
    }
    $sql = "* FROM usenet_servers WHERE \"id\" = ? $prio_sql";
    $res = $db->select_query($sql, 1, [$id]);
    if (!is_array($res)) {
        throw new exception ('No active usenet server selected', ERR_NO_ACTIVE_SERVER);
    }
    $row = $res[0];
    $srv['id'] = $row['id'];
    $srv['name'] = $row['name'];
    $srv['hostname'] = $row['hostname'];
    $srv['threads'] = $row['threads'];
    $srv['connection'] = $row['connection'];
    $srv['port'] = ($row['connection'] == 'off') ? $row['port'] : $row['secure_port'];
    $srv['plain_port'] = $row['port'];
    $srv['secure_port'] = $row['secure_port'];
    $srv['authentication'] = $row['authentication'];
    $srv['priority'] = $row['priority'];
    $srv['posting'] = $row['posting'];
    $srv['compressed_headers'] = $row['compressed_headers'];
    if ($srv['authentication'] != 0) {
        $srv['username'] = $row['username'];
        $srv['password'] = keystore::decrypt_password($db, $row['password']);
    } else {
        $srv['username'] = '';
        $srv['password'] = '';
    }

    return $srv;
}

function get_all_usenet_servers(DatabaseConnection $db, $active=TRUE)
{ // todo fix get update server too
    assert(is_bool($active));
    $prio_sql = '';
    if ($active === TRUE) {
        $prio_sql = 'WHERE "priority" > 0';
    }
    $sql = "* FROM usenet_servers $prio_sql ORDER BY \"name\" ASC";
    $res = $db->select_query($sql);
    if (!is_array($res)) {
        throw new exception ('No active usenet servers found', ERR_NO_ACTIVE_SERVER);
    }
    foreach ($res as $row) {
        $srv['id'] = $row['id'];
        $srv['name'] = $row['name'];
        $srv['hostname'] = $row['hostname'];
        $srv['threads'] = $row['threads'];
        $srv['connection'] = $row['connection'];
        $srv['port'] = ($row['connection'] == 'off') ? $row['port'] : $row['secure_port'];
        $srv['plain_port'] = $row['port'];
        $srv['secure_port'] = $row['secure_port'];
        $srv['authentication'] = $row['authentication'];
        $srv['priority'] = $row['priority'];
        $srv['posting'] = $row['posting'];
        $srv['compressed_headers'] = $row['compressed_headers'];
        if ($srv['authentication'] != 0) {
            $srv['username'] = $row['username'];
            $srv['password'] = keystore::decrypt_password($db, $row['password']);
        } else {
            $srv['username'] = '';
            $srv['password'] = '';
        }
        $servers[] = $srv;
    }
    return $servers;
}

function create_usenet_servers(DatabaseConnection $db)
{
    add_usenet_server($db, '@home 1', 'news.home.nl', 119, 563, 4, 'off', 1, '', '', 0, 0, 1);
    add_usenet_server($db, '@home 2', 'newnews.home.nl', 119, 563, 4, 'off', 1, '', '', 0, 0, 1);
    add_usenet_server($db, '4UX', 'news.4ux.nl', 119, 563, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, '4usenet', 'news.4usenet.nl', 119, 563, 6, 'ssl', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Astraweb', 'news.astraweb.com', 119, 563, 20, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Blocknews EU', 'eunews.blocknews.com', 119, 563, 20, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Blocknews US', 'usnews.blocknews.com', 119, 563, 20, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Casema', 'news.casema.nl', 119, 563, 6, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Dommel', 'news.dommel.be', 119, 563, 6, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Download 2 Day', 'reader.download2day.nl', 119, 563, 6, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Easynews', 'news.easynews.com', 119, 563, 4, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Ewaka', 'news.eweka.nl', 119, 0, 8, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Ewaka IPv6', 'news.ipv6.eweka.nl', 119, 0, 8, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Extreme Usenet', 'reader.extremeusenet.nl', 119, 563, 8, 'ssl', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Flexnewz', 'news.flexnewz.com', 119, 563, 8, 'ssl', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Giganews (America)', 'news.giganews.com', 119, 563, 10, 'off', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Giganews (Europe)', 'news-europe.giganews.com', 119, 563, 10, 'off', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Hitnews EU', 'news.hitnews.eu', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Hitnews US', 'ssl.hitnews.com', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'I-Telligent', 'news.i-telligent.com', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Internet Solutions', 'news.is.co.za', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'ITgate', 'news.itgate.net', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'KPN', 'news.kpn.nl', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Multiweb', 'news.quicknet.nl', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'MWeb', 'news.mweb.co.za', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Newsconnection', 'news.newsconnection.nl', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Newsgrabber', 'news.newsgrabber.nl', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Newshosting', 'news.newshosting.nl', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'NewsXS', 'reader2.newsxs.nl', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Ngroups', 'news.us.ngroups.net', 119, 0, 3, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Online', 'news.online.nl', 119, 0, 3, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'OnsBrabantnet', 'news.onsbrabantnet.nl', 119, 0, 3, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Premiumize', 'usenet.premiumize.me', 119, 0, 3, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Powernews', 'reader.powernews.nl', 119, 0, 3, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Power Usenet', 'reader.powerusenet.com', 119, 0, 3, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Pure Usenet', 'news.pureusenet.nl', 119, 0, 3, 'ssl', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Saix', 'news.saix.net', 119, 0, 3, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Supernews EU', 'news.eu.supernews.com', 119, 0, 3, 'ssl', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Supernews US', 'news.supernews.com', 119, 0, 3, 'ssl', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'SMSUsenet', 'news.smsusenet.nl', 119, 563, 5, 'ssl', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'SnelNL EU', 'ssl-news.snelnl.com', 119, 563, 5, 'ssl', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'SnelNL US', 'ssl-news2.snelnl.com', 119, 563, 5, 'ssl', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Speedplaza', 'news.speedplaza.net', 119, 563, 5, 'ssl', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Sunny Usenet', 'news.sunnyusenet.com', 119, 563, 5, 'ssl', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Thundernews', 'secure.us.thundernews.com', 119, 563, 4, 'ssl', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Tele2 1', 'tele2news.tweaknews.nl', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Tele2 2', 'news.tele2.nl', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Telenet', 'newsgroups.telenet.be', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Tweakdsl', 'news.tweaknews.nl', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'TweakFiber', 'news.tweak.nl', 119, 563, 4, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Tweaknews', 'news.tweaknews.nl', 119, 563, 4, 'ssl', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Usenext', 'news.usenet.com', 119, 443, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Usenet News (EU)', 'news.eu.usenet-news.net', 119, 443, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Usenet News (US)', 'news.us.usenet-news.net', 119, 443, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Usenet Server', 'news.usenetserver.com', 119, 443, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Usenet.nl', 'news1.usenet.nl', 119, 443, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Usenet XL', 'reader.usenetxl.nl', 119, 443, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Usenet Monster', 'bignews.usenetmonster.com', 119, 443, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Usenet.Pro', 'reader.usenet.pro', 119, 443, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Usenet4u (4)', 'news4.usenet4u.nl', 119, 443, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Usenet4u (3)', 'news3.usenet4u.nl', 119, 443, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'VoordeligUsenet', 'reader.voordeligusenet.nl', 119, 443, 4, 'ssl', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Web Africa', 'news.webafrica.co.za', 119, 0, 3, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Wondernews', 'reader.wondernews.eu', 119, 563, 3, 'tls', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'XLNED', 'news.xlned.com', 119, 0, 4, 'off', 0, '', '', 0, 0, 1);
    add_usenet_server($db, 'XLusenet', 'ssl-news.eu.xlusenet.nl', 119, 0, 4, 'ssl', 0, '', '', 0, 0, 1);
    add_usenet_server($db, 'XS usenet', 'reader.xsusenet.com', 119, 0, 4, 'ssl', 0, '', '', 0, 0, 1);
    add_usenet_server($db, 'XS usenet Free', 'free.xsusenet.com', 119, 0, 4, 'off', 0, '', '', 0, 0, 1);
    add_usenet_server($db, 'XS4all - Newszilla', 'newszilla.xs4all.nl', 119, 0, 8, 'off', 0, '', '', 0, 0, 1);
    add_usenet_server($db, 'XS4all - Newszilla IPv6', 'newszilla6.xs4all.nl', 119, 0, 8, 'off', 0, '', '', 0, 0, 1);
    add_usenet_server($db, 'XMS', 'news.xmsnet.nl', 119, 0, 4, 'off', 0, '', '', 0, 0, 1);
    add_usenet_server($db, 'XSnews', 'reader.xsnews.nl', 119, 563, 50, 'ssl', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'XSnews (IPv6)', 'reader.ipv6.xsnews.nl', 119, 563, 50, 'off', 0, '', '', 0, 0, 0);
    add_usenet_server($db, 'Yabnews', 'news.yabnews.com', 119, 0, 4, 'off', 1, '', '', 0, 0, 0);
    add_usenet_server($db, 'Zeelandnet', 'news.zeelandnet.nl', 119, 0, 4, 'off', 1, '', '', 0, 0, 1);
    add_usenet_server($db, 'Ziggo', 'news.ziggo.nl', 119, 0, 4, 'off', 1, '', '', 0, 0, 1);
}

function add_usenet_server(DatabaseConnection $db, $name, $hostname, $port, $secure_port, $threads, $connection, $authentication, $username, $password, 
        $priority=DEFAULT_USENET_SERVER_PRIORITY, $compressed_headers=FALSE, $posting=FALSE)
{
    assert(is_numeric($priority) && is_numeric($port) && is_numeric($secure_port) && is_numeric($threads));
    $name = trim($name);
    $hostname = trim($hostname);
    $username = trim($username);
    $password = trim($password);
    if (get_config($db, 'use_encrypted_passwords', FALSE)) {
        $password = keystore::encrypt_password($db, $password);
    }
    $connection = trim($connection);

    $cols = array('name', 'hostname', 'port', 'secure_port', 'threads', 'connection', 'authentication', 'username', 'password', 'priority', 'compressed_headers', 'posting');
    $vals = array($name, $hostname, $port, $secure_port, $threads, $connection, ($authentication?1:0), $username, $password, $priority, ($compressed_headers?1:0), ($posting?1:0));
    $last_id = $db->insert_query('usenet_servers', $cols, $vals, TRUE);

    $res = $db->select_query('"id" FROM usenet_servers WHERE "name"=?', array($name));
    if ($res === FALSE) {
        return FALSE;
    }

    return $last_id;
}

function set_posting(DatabaseConnection $db, $id, $posting)
{
    assert(is_numeric($id));
    $db->update_query_2('usenet_servers', array('posting'=> ($posting? 1 : 0)), '"id"=?', array($id));
}

function smart_update_usenet_server(DatabaseConnection $db, $id, $values)
{
    assert(is_numeric($id));
    $cols = $vars = array();
    if (isset($values['name'])) {
        $cols[] = 'name';
        $vals[] = trim($values['name']);
    }

    if (isset($values['hostname'])) {
        $hostname = trim($values['hostname']);
        $cols[] = 'hostname';
        $vals[] = trim($values['hostname']);
    }

    if (isset($values['username'])) {
        $cols[] = 'username';
        $vals[] = trim($values['username']);
    }
    if (isset($values['password'])) {
        $password = trim($values['password']);
        if (get_config($db, 'use_encrypted_passwords', FALSE)) {
            $password = keystore::encrypt_password($db, $password);
        }
        $cols[] = 'password';
        $vals[] = $password;
    }
    if (isset($values['connection'])) {
        $cols[] = 'connection';
        $vals[] = trim($values['connection']);

    }
    if (isset($values['port'])) {
        $port = $values['port'];
        assert(is_numeric($port));
        $cols[] = 'port';
        $vals[] = $port;
    }
    if (isset($values['secure_port'])) {
        $secure_port = $values['secure_port'];
        assert(is_numeric($secure_port));
        $cols[] = 'secure_port';
        $vals[] = $secure_port;
    }
    if (isset($values['compressed_headers'])) {
        assert(is_bool($values['compressed_headers']));
        $cols[] = 'compressed_headers';
        $vals[] = ($values['compressed_headers'] ? 1 : 0);
    }
    if (isset($values['threads'])) {
        $threads = $values['threads'];
        assert(is_numeric($threads));
        $cols[] = 'threads';
        $vals[] = $threads;
    }

    if (isset($values['authentication'])) {
        $authentication = $values['authentication'];
        $cols[] = 'authentication';
        $vals[] = $values['authentication'];
    }
    if (isset($values['posting'])) {
        $cols[] = 'posting';
        $vals[] = ($values['posting'] ? 1 : 0);
    }
    if (isset($values['priority'])) {
        $priority = $values['priority'];
        assert(is_numeric($priority) );
        $cols[] = 'priority';
        $vals[] = $priority;
    }
    $db->update_query('usenet_servers', $cols, $vals, '"id"=?', array($id));

    return TRUE;
}

function update_usenet_server(DatabaseConnection $db, $id, $name, $hostname, $port, $secure_port, $threads, $connection, $authentication, $username, $password, $priority, $compressed_headers, $posting)
{
    assert(is_numeric($priority) && is_numeric($port) && is_numeric($secure_port) && is_numeric($threads) && is_numeric($id));
    $name = trim($name);
    $hostname = trim($hostname);
    $username = trim($username);
    $password = trim($password);
    if (get_config($db, 'use_encrypted_passwords', FALSE)) {
        $password = keystore::encrypt_password($db, $password);
    }
    $connection = trim($connection);
    $authentication = $authentication ? 1 : 0;
    $compressed_headers = $compressed_headers ? 1 : 0;

    $posting = ($posting ? 1 : 0);
    $cols = array('name', 'hostname', 'port', 'secure_port', 'threads', 'connection', 'authentication', 'username', 'password', 'compressed_headers', 'priority', 'posting');
    $vals = array($name, $hostname, $port, $secure_port, $threads, $connection, $authentication, $username, $password, $compressed_headers, $priority, $posting);

    $db->update_query('usenet_servers', $cols, $vals, '"id"=?', array($id));

    return TRUE;
}

function disable_usenet_server(DatabaseConnection $db, $id)
{
    assert(is_numeric($id));
    set_priority_usenet_server($db, $id, DISABLED_USENET_SERVER_PRIORITY);
}

function enable_usenet_server(DatabaseConnection $db, $id, $priority=0)
{
    assert(is_numeric($id) && is_numeric($priority));
    if ($priority <= 0 || $priority > 100) {
        $priority = DEFAULT_USENET_SERVER_PRIORITY;
    }
    set_priority_usenet_server($db, $id, $priority);
}

function set_priority_usenet_server(DatabaseConnection $db, $id, $priority)
{
    assert(is_numeric($id) && is_numeric($priority));
    $db->update_query_2('usenet_servers', array('priority' => $priority), '"id"=?', array($id));
}

function get_priority_usenet_server(DatabaseConnection $db, $id)
{
    assert(is_numeric($id));
    $sql = '"priority" FROM usenet_servers WHERE "id"=:id';
    $res = $db->select_query($sql, 1, array(':id'=>$id));
    if (isset($res[0]['priority'])) {
        return $res[0]['priority'];
    }

    return 0;
}

function delete_usenet_server(DatabaseConnection $db, $id)
{
    assert(is_numeric($id));
    $update_id = get_config($db, 'preferred_server');
    if ($update_id == $id) { // if we delete the preferred server, we reset the preferred server
        set_config($db, 'preferred_server', 0);
    }
    $db->delete_query('usenet_servers', '"id"=:id', array(':id'=>$id));
}

function clear_all_usenet_servers(DatabaseConnection $db)
{
    set_config($db, 'preferred_server', 0);
    try {
        $srv = get_all_usenet_servers($db, FALSE);
        foreach ($srv as $s) {
            delete_usenet_server($db, $s['id']);
        }
    } catch (exception $e) {
    }
}

function set_all_usenet_servers(DatabaseConnection $db, array $settings)
{
    $pref_server = $settings['preferred_server'];
    foreach ($settings as $setting) {
        if (is_array($setting)) {
            $id = add_usenet_server($db, $setting['name'], $setting['hostname'], $setting['port'], $setting['secure_port'], $setting['threads'],
            $setting['connection'], $setting['authentication'], $setting['username'], $setting['password'], $setting['priority'], $setting['compressed_headers'], $setting['posting']);
            if ($setting['name'] == $pref_server && $pref_server != '') {
                set_config($db, 'preferred_server', $id);
            }
        }
    }
}

function encrypt_all_usenet_passwords(DatabaseConnection $db)
{
    $srv = get_all_usenet_servers($db, FALSE);
    foreach ($srv as $server) {
        smart_update_usenet_server($db, $server['id'], array('password' => $server['password']));
    }
}
