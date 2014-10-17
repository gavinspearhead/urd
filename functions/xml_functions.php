<?php
/*
 *  This file is part of Urd.
/*  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2014-05-30 00:49:17 +0200 (vr, 30 mei 2014) $
 * $Rev: 3077 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: xml_functions.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathx = realpath(dirname(__FILE__));

require_once "$pathx/error_codes.php";
require_once "$pathx/autoincludes.php";

class urd_xml_writer
{
    private $xml;
    public function __construct($uri)
    {
        $version = urd_version::get_version();

        $this->xml = new XMLWriter();
        if ($uri === NULL) {
            $this->xml->openMemory();
        } else {
            $this->xml->openURI($uri);
        }

        $this->xml->setIndent(TRUE);
        $this->xml->startDocument('1.0', 'UTF-8');
        $this->xml->writeComment("\n\n   Created by URD $version. http://www.urdland.com\n   on " .date('r') . "\n\n");
        $this->xml->setIndent(TRUE);
        $this->xml->startElement('urdsettings');
    }

    public function __destruct()
    {
    }
    public function finalise()
    {
        $this->xml->endElement(); // urdsettings
        $this->xml->writeComment("\n\n === end of configuration === \n\n");
        $this->xml->endDocument();
    }
    public function output_xml_data()
    {
        $this->finalise();
        $this->xml->flush();
    }
    public function write_rssfeeds(DatabaseConnection $db)
    {
        try {
            $feeds = get_all_feeds($db);
        } catch (exception $e) {
            return;
        }

        $this->xml->setIndent(TRUE);
        $this->xml->startElement('rssfeeds');

        foreach ($feeds as $gr) {
            $this->xml->setIndent(TRUE);
            $this->xml->startElement('rss_url');
            $this->xml->writeAttribute('expire', $gr['expire']);
            $this->xml->writeAttribute('id', $gr['id']);
            $this->xml->writeAttribute('name', $gr['name']);
            $this->xml->writeAttribute('username', $gr['username']);
            $this->xml->writeAttribute('password', $gr['password']);
            $this->xml->writeAttribute('subscribed', $gr['subscribed']);
            $this->xml->writeAttribute('refresh_time', $gr['refresh_time']);
            $this->xml->writeAttribute('refresh_period', $gr['refresh_period']);
            $this->xml->writeAttribute('adult', $gr['adult']);
            $this->xml->text($gr['url']);
            $this->xml->endElement(); // rss_url
        }
        $this->xml->endElement(); //
    }

    public function write_newsgroups(DatabaseConnection $db)
    {
        try {
            $groups = get_all_active_groups($db);
        } catch (exception $e) {
            return;
        }

        $this->xml->setIndent(TRUE);
        $this->xml->startElement('newsgroups');

        foreach ($groups as $gr) {
            $this->xml->setIndent(TRUE);
            $this->xml->startElement('group');
            $this->xml->writeAttribute('expire', $gr['expire']);
            $this->xml->writeAttribute('id', $gr['ID']);
            $this->xml->writeAttribute('refresh_time', $gr['refresh_time']);
            $this->xml->writeAttribute('refresh_period', $gr['refresh_period']);
            $this->xml->writeAttribute('minsetsize', $gr['minsetsize']);
            $this->xml->writeAttribute('maxsetsize', $gr['maxsetsize']);
            $this->xml->writeAttribute('adult', $gr['adult']);
            $this->xml->text($gr['name']);
            $this->xml->endElement(); // group
        }
        $this->xml->endElement(); // newsgroup
    }
    public function write_config(DatabaseConnection $db)
    {
        $prefs = load_config($db, TRUE);

        $this->xml->setIndent(TRUE);
        $this->xml->startElement('config');

        foreach ($prefs as $k=>$p) {
            $this->xml->setIndent(TRUE);
            $this->xml->startElement('parameter');
            $this->xml->writeAttribute('name', $k);
            $this->xml->text($p);
            $this->xml->endElement(); // parameter
        }
        $this->xml->endElement(); // config
    }
    public function write_categories(DatabaseConnection $db, $userid)
    {
        assert (is_numeric($userid));
        $categories = get_categories($db, $userid);
        $this->xml->startElement('categories');
        foreach ($categories as $cat) {
            $this->xml->startElement('categorie');
            $this->xml->writeAttribute('id', $cat['id']);
            $this->xml->text($cat['name']);
            $this->xml->endElement(); // categorie
        }
        $this->xml->endElement(); // categories
    }
    public function write_userfeed_settings(DatabaseConnection $db, $userid)
    {
        assert (is_numeric($userid));
        $groupinfo = get_userfeed_settings($db, $userid);
        $this->xml->startElement('userfeedinfo');
        foreach ($groupinfo as $p) {
            $this->xml->startElement('feedinfo');
            $this->xml->writeAttribute('feed_name', $p['f_name']);
            $this->xml->writeAttribute('category_name', $p['c_name']);
            $this->xml->writeAttribute('minsetsize', $p['minsetsize']);
            $this->xml->writeAttribute('maxsetsize', $p['maxsetsize']);
            $this->xml->writeAttribute('visible', $p['visible']);
            $this->xml->endElement(); // feedinfo
        }
        $this->xml->endElement(); // userfeedinfo

    }

    public function write_usergroup_settings(DatabaseConnection $db, $userid)
    {
        assert (is_numeric($userid));
        $groupinfo = get_usergroup_settings($db, $userid);
        $this->xml->startElement('usergroupinfo');
        foreach ($groupinfo as $p) {
            $this->xml->startElement('groupinfo');
            $this->xml->writeAttribute('group_name', $p['g_name']);
            $this->xml->writeAttribute('category_name', $p['c_name']);
            $this->xml->writeAttribute('minsetsize', $p['minsetsize']);
            $this->xml->writeAttribute('maxsetsize', $p['maxsetsize']);
            $this->xml->writeAttribute('visible', $p['visible']);
            $this->xml->endElement(); // groupinfo
        }
        $this->xml->endElement(); // usergroupinfo
    }

    public function write_user_settings(DatabaseConnection $db, $userid)
    {
        assert (is_numeric($userid));
        try {
            $username = get_username($db, $userid);
            $prefs = load_prefs($db, $userid, TRUE);
        } catch (exception $e) {
            return;
        }

        $this->xml->setIndent(TRUE);
        $this->xml->startElement('usersettings');
        $this->xml->writeAttribute('userid', $userid);
        $this->xml->writeAttribute('username', $username);

        foreach ($prefs as $k=>$p) {
            $this->xml->setIndent(TRUE);
            $this->xml->startElement('parameter');
            $this->xml->writeAttribute('name', $k);
            $this->xml->text($p);
            $this->xml->endElement(); // parameter
        }
        $this->write_categories($db, $userid);
        $this->write_usergroup_settings($db, $userid);
        $this->write_userfeed_settings($db, $userid);
        $this->xml->endElement(); // usersettings
    }
    public function write_usenet_servers(DatabaseConnection $db)
    {
        try {
            $servers = get_all_usenet_servers($db, FALSE);
        } catch (exception $e) {
            return;
        }
        try {
            $id = get_config($db, 'preferred_server');
            $rv = get_usenet_server($db, $id);
            $pref_server = $rv['name'];
        } catch (exception $e) {
            $pref_server = '';
        }

        // get preferred server
        $this->xml->setIndent(TRUE);
        $this->xml->startElement('preferred_server');
        $this->xml->text($pref_server);
        $this->xml->endElement(); //preferred_servers
        $this->xml->startElement('usenet_servers');
        foreach ($servers as $s) {
            $this->xml->setIndent(TRUE);
            $this->xml->startElement('server');
            $this->xml->writeAttribute('name', $s['name']);
            $this->xml->writeAttribute('hostname', $s['hostname']);
            $this->xml->writeAttribute('threads', $s['threads']);
            $this->xml->writeAttribute('connection', $s['connection']);
            $this->xml->writeAttribute('port', $s['plain_port']);
            $this->xml->writeAttribute('secure_port', $s['secure_port']);
            $this->xml->writeAttribute('authentication', $s['authentication']);
            $this->xml->writeAttribute('priority', $s['priority']);
            $this->xml->writeAttribute('username', $s['username']);
            $this->xml->writeAttribute('password', $s['password']);
            $this->xml->writeAttribute('posting', $s['posting']);
            $this->xml->writeAttribute('compressed_headers', $s['compressed_headers']);
            $this->xml->endElement(); // server
        }
        $this->xml->endElement(); // usenet_servers
    }
    public function write_users(DatabaseConnection $db)
    {
        try {
            $users = get_all_users_full($db);
        } catch (exception $e) {
            return;
        }
        $this->xml->setIndent(TRUE);
        $this->xml->startElement('users');
        foreach ($users as $u) {
            $this->xml->setIndent(TRUE);
            $this->xml->startElement('user');
            $this->xml->writeAttribute('password', $u['pass']);
            $this->xml->writeAttribute('fullname', $u['fullname']);
            $this->xml->writeAttribute('email', $u['email']);
            $this->xml->writeAttribute('salt', $u['salt']);
            $this->xml->writeAttribute('isadmin', $u['isadmin']);
            $this->xml->writeAttribute('rights', trim($u['rights']));
            $this->xml->writeAttribute('active', $u['active']);
            $this->xml->text($u['name']);
            $this->xml->endElement(); // user
        }
        $this->xml->endElement(); // users

    }
    public function write_search_options(DatabaseConnection $db)
    {
        try {
            $search_options = get_all_search_options($db);
        } catch (exception $e) {
            return;
        }
        $this->xml->setIndent(TRUE);
        $this->xml->startElement('buttons');
        foreach ($search_options as $b) {
            $this->xml->setIndent(TRUE);
            $this->xml->startElement('button');
            $this->xml->writeAttribute('search_url', $b['search_url']);
            $this->xml->text($b['name']);
            $this->xml->endElement(); // button
        }
        $this->xml->endElement(); // buttons
    }
    public function write_spots_blacklist(DatabaseConnection $db, $userid=NULL)
    {
        try {
            $blacklist = get_all_spots_blacklist($db, $userid);
        } catch (exception $e) {
            return;
        }
        $this->xml->setIndent(TRUE);
        $this->xml->startElement('spots_blacklist');
        foreach ($blacklist as $b) {
            $this->xml->setIndent(TRUE);
            $this->xml->startElement('spotter');
            $this->xml->writeAttribute('spotter_id', $b['spotter_id']);
            $this->xml->writeAttribute('source', $b['source']);
            $this->xml->writeAttribute('status', $b['status']);
            $this->xml->writeAttribute('userid', $b['userid']);
            $this->xml->endElement(); // button

        }

    }
    public function write_spots_whitelist(DatabaseConnection $db, $userid=NULL)
    {
        try {
            $blacklist = get_all_spots_whitelist($db, $userid);
        } catch (exception $e) {
            return;
        }
        $this->xml->setIndent(TRUE);
        $this->xml->startElement('spots_whitelist');
        foreach ($blacklist as $b) {
            $this->xml->setIndent(TRUE);
            $this->xml->startElement('spotter');
            $this->xml->writeAttribute('spotter_id', $b['spotter_id']);
            $this->xml->writeAttribute('source', $b['source']);
            $this->xml->writeAttribute('status', $b['status']);
            $this->xml->writeAttribute('userid', $b['userid']);
            $this->xml->endElement(); // spots_whitelist
        }
    }

    public function write_all_user_settings(DatabaseConnection $db)
    {
        try {
            $users = get_all_userids($db);
        } catch (exception $e) {
            return;
        }
        $this->write_users($db);
        foreach ($users as $u) {
            $this->write_user_settings($db, $u);
        }
    }
    public function write_all(DatabaseConnection $db)
    {
        try {
            $users = get_all_userids($db);
        } catch (exception $e) {
            return;
        }

        $this->write_newsgroups($db);
        $this->write_rssfeeds($db);
        $this->write_config($db);
        $this->write_usenet_servers($db);
        $this->write_users($db);
        $this->write_search_options($db);
        $this->write_spots_blacklist($db);
        $this->write_spots_whitelist($db);
        foreach ($users as $u) {
            $this->write_user_settings($db, $u);
        }
    }
    public function write(DatabaseConnection $db, $what, $userid)
    {
        switch ($what) {
        case 'all': $this->write_all($db); break;
        case 'buttons': $this->write_search_options($db); break;
        case 'config': $this->write_config($db); break;
        case 'users': $this->write_users($db); break;
        case 'usenet_servers': $this->write_usenet_servers($db); break;
        case 'user_settings': $this->write_user_settings($db, $userid); break;
        case 'all_user_settings': $this->write_all_user_settings($db); break;
        case 'usergroup_settings': $this->write_usergroup_settings($db, $userid); break;
        case 'userfeed_settings': $this->write_userfeed_settings($db, $userid); break;
        case 'spots_blacklist': $this->write_spots_blacklist($db, $userid); break;
        case 'spots_whitelist': $this->write_spots_whitelist($db, $userid); break;
        case 'categories': $this->write_categories($db, $userid); break;
        case 'newsgroups': $this->write_newsgroups($db); break;
        case 'rssfeeds': $this->write_rssfeeds($db); break;
        }
    }

    public function write_extset_data(DatabaseConnection $db)
    {
    }
}

function get_numeric(array $foo, $index)
{
    if (isset($foo[$index]) && is_numeric($foo[$index])) {
        return $foo[$index];
    } else {
        return NULL;
    }
}

function get_string(array $foo, $index)
{
    if (isset($foo[$index]) && is_string($foo[$index])) {
        return $foo[$index];
    } else {
        return NULL;
    }
}

class urd_xml_reader
{
    private $xml;
    private $arr;

    public function __construct ($file=NULL)
    {
        $this->arr = NULL;
        $this->xml = NULL;
        $this->xml = new XMLReader();
        if ($file !== NULL) {
            $this->init_file($file);
        }
    }
    public function init_string($str)
    {
        $this->xml->xml($str);
        $this->arr = $this->xml2assoc($this->xml);
    }

    private function init_file($file)
    {
        global $LN;
        $xml = $this->xml;
        if ((!file_exists($file) && !is_readable($file)) || filesize($file) < 10) {
            $this->xml = NULL;
            throw new exception ($LN['error_filenotfound']);
        }
        $contents = @file_get_contents($file);
        if ($contents === FALSE) {
            $this->xml = NULL;
            throw new exception ($LN['error_filenotfound']);
        }

        $this->init_string($contents);
    }

    private function xml2assoc(XMLReader $xml)
    {
        if ($this->xml === NULL) {
            throw new exception ('Not initialised');
        }

        $tree = NULL;
        // suppress parse warninsg
        $old_err_rep = error_reporting(E_ERROR);
        $old_val = ini_set('track_errors', 1);
        $php_errormsg = '';
        while ($xml->read()) {
            switch ($xml->nodeType) {
            case XMLReader::END_ELEMENT:
                return $tree;
            case XMLReader::ELEMENT:
                $tag = $xml->name;
                $value = ($xml->isEmptyElement) ? '' : $this->xml2assoc($xml);
                if ($value === NULL) {
                    $value = '';
                }

                $node = array('tag' => $tag, 'value' => $value);
                if ($xml->hasAttributes) {
                    while ($xml->moveToNextAttribute()) {
                        $node['attributes'][$xml->name] = $xml->value;
                    }
                }
                $tree[] = $node;
                break;
            case XMLReader::TEXT:
            case XMLReader::CDATA:
                $value = $xml->value;
                if ($value === NULL) {
                    $value = '';
                }
                $tree .= $value;
                break;
            }
        }
        error_reporting($old_err_rep);
        $errormsg = $php_errormsg;
        if ($old_val !== FALSE) {
            ini_set('track_errors', $old_val);
        }
        if ($errormsg != '') {
            throw new exception ('A parse error occurred in XML file');
        }

        return $tree;
    }
    public function read_user_settings(DatabaseConnection $db, $userid=NULL, $verify_name=FALSE)
    {
        assert ((is_numeric($userid) || $userid === NULL) && is_bool($verify_name));
        if ($userid !== NULL && $verify_name === TRUE) {
            $username = get_username($db, $userid);
        } else {
            $username = '';
        }
        if ($this->arr === NULL) {
            return array();
        }
        $settings = $user_settings = array();
        foreach ($this->arr as $t1) {
            if (get_string($t1,'tag') == 'urdsettings' && isset($t1['value'])) {
                foreach ($t1['value'] as $t2) {
                    if (get_string($t2, 'tag') == 'usersettings' && isset($t1['value'])) {
                        $settings = array();
                        $uname = get_string($t2['attributes'], 'username');
                        if (!$verify_name || $uname == $username) {
                            //$uid = get_numeric($t2['attributes'],'userid');
                            foreach ($t2['value'] as $t3) {
                                if (get_string($t3,'tag') == 'parameter') {
                                    $name = get_string($t3['attributes'],'name');
                                    $value = get_string($t3, 'value');
                                    if ($name !== NULL && $value !== NULL) {
                                        $settings['parameters'][$name] = $value;
                                    }
                                } elseif (get_string($t3,'tag') == 'categories' && is_array($t3['value'])) {
                                    foreach ($t3['value'] as $t4) {
                                        if (get_string($t4,'tag') == 'categorie') {
                                            $id = get_string($t4['attributes'],'id');
                                            $cat = get_string($t4, 'value');
                                            $settings['categories'][$id] = $cat;
                                        }
                                    }
                                } elseif (get_string($t3,'tag') == 'userfeedinfo' && is_array($t3['value'])) {
                                    foreach ($t3['value'] as $t4) {
                                        if (get_string($t4,'tag') == 'feedinfo') {
                                            $f_name = get_string($t4['attributes'],'feed_name');
                                            $c_name = get_string($t4['attributes'],'category_name');
                                            $minsetsize = get_string($t4['attributes'],'minsetsize');
                                            $maxsetsize = get_string($t4['attributes'],'maxsetsize');
                                            $visible = get_string($t4['attributes'],'visible');

                                            $cat = get_string($t4, 'value');
                                            $settings['userfeedinfo'][$f_name] = array(
                                                'f_name'=> $f_name,
                                                'c_name'=> $c_name,
                                                'minsetsize'=> $minsetsize,
                                                'maxsetsize'=> $maxsetsize,
                                                'visible'=> $visible
                                            );
                                        }
                                    }

                                } elseif (get_string($t3,'tag') == 'usergroupinfo' && is_array($t3['value'])) {
                                    foreach ($t3['value'] as $t4) {
                                        if (get_string($t4,'tag') == 'groupinfo') {
                                            $g_name = get_string($t4['attributes'],'group_name');
                                            $c_name = get_string($t4['attributes'],'category_name');
                                            $minsetsize = get_string($t4['attributes'],'minsetsize');
                                            $maxsetsize = get_string($t4['attributes'],'maxsetsize');
                                            $visible = get_string($t4['attributes'],'visible');

                                            $cat = get_string($t4, 'value');
                                            $settings['usergroupinfo'][$g_name] = array(
                                                'g_name'=> $g_name,
                                                'c_name'=> $c_name,
                                                'minsetsize'=> $minsetsize,
                                                'maxsetsize'=> $maxsetsize,
                                                'visible'=> $visible
                                            );
                                        }
                                    }
                                }
                            }
                        }
                        if ($verify_name !== TRUE) {
                            $user_settings[$uname] = $settings;
                        } else {
                            $user_settings = $settings;

                            return $settings;
                        }
                    }
                }
            }
        }

        return $user_settings;
    }
    public function read_config()
    {
        if ($this->arr === NULL) {
            return array();
        }
        $settings = array();
        foreach ($this->arr as $t1) {
            if (get_string($t1,'tag') == 'urdsettings' && isset($t1['value'])) {
                foreach ($t1['value'] as $t2) {
                    if (get_string($t2, 'tag') == 'config' && isset($t1['value'])) {
                        $settings = array();
                        foreach ($t2['value'] as $t3) {
                            if (get_string($t3,'tag') == 'parameter') {
                                $name = get_string ($t3['attributes'],'name');
                                $value = get_string($t3, 'value');
                                if ($name !== NULL && $value !== NULL) {
                                    $settings[$name] = $value;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $settings;
    }

    public function read_usenet_servers()
    {
        if ($this->arr === NULL) {
            return array();
        }
        $settings = array();
        foreach ($this->arr as $t1) {
            if (get_string($t1,'tag') == 'urdsettings' && isset($t1['value'])) {
                foreach ($t1['value'] as $t2) {
                    $tag = get_string($t2,'tag');
                    if ($tag == 'usenet_servers' && isset($t2['value'])) {
                        foreach ($t2['value'] as $t3) {
                            if (get_string($t3,'tag') == 'server') {
                                $name = get_string($t3['attributes'],'name');
                                $hostname = get_string($t3['attributes'],'hostname');
                                $threads = get_numeric($t3['attributes'],'threads');
                                $connection = get_string($t3['attributes'],'connection');
                                $port = get_numeric($t3['attributes'],'port');
                                $secure_port = get_numeric($t3['attributes'],'secure_port');
                                $authentication = get_numeric($t3['attributes'],'authentication');
                                $username = get_string($t3['attributes'],'username');
                                $password = get_string($t3['attributes'],'password');
                                $priority = get_numeric($t3['attributes'],'priority');
                                $posting = get_numeric($t3['attributes'],'posting');
                                $compressed_headers = get_numeric($t3['attributes'],'compressed_headers');
                                if ($name !== NULL && $hostname !== NULL && $threads !== NULL && $connection !== NULL && $port !== NULL && $priority != NULL) {
                                    $set = array (
                                        'name' => $name,
                                        'hostname' => $hostname,
                                        'username' => $username !== NULL ? $username : '',
                                        'password' => $password !== NULL ? $password : '',
                                        'threads' => $threads,
                                        'connection' => $connection,
                                        'port' => $port,
                                        'secure_port' => $secure_port,
                                        'priority' => $priority,
                                        'compressed_headers' => $compressed_headers,
                                        'posting' => $posting,
                                        'authentication' => $authentication
                                    );
                                    $settings[$name] = $set;
                                }
                            }
                        }
                    } elseif ($tag == 'preferred_server') {
                        $pref_server = trim(get_string($t2,'value'));
                        if ($pref_server !== NULL) {
                            $settings['preferred_server'] = $pref_server;
                        }
                    }
                }
            }
        }

        return $settings;
    }

    public function read_feeds_settings()
    {
        if ($this->arr === NULL) {
            return array();
        }
        $settings = array();
        foreach ($this->arr as $t1) {
            if (get_string($t1,'tag') == 'urdsettings' && isset($t1['value'])) {
                foreach ($t1['value'] as $t2) {
                    if (get_string($t2,'tag') == 'rssfeeds' && isset($t2['value'])) {
                        foreach ($t2['value'] as $t3) {
                            if (get_string($t3,'tag') == 'rss_url') {
                                $url = get_string($t3,'value');
                                $expire = get_numeric($t3['attributes'],'expire');
                                $refresh_time = get_numeric($t3['attributes'],'refresh_time');
                                $refresh_period = get_numeric($t3['attributes'],'refresh_period');
                                $name = get_string($t3['attributes'],'name');
                                $subscribed = get_numeric($t3['attributes'],'subscribed');
                                $username = get_string($t3['attributes'],'username');
                                $password = get_string($t3['attributes'],'password');
                                $adult = get_numeric($t3['attributes'],'adult');
                                if ($name !== NULL && $subscribed !== NULL && $url !== NULL && $expire !== NULL) {
                                    $set = array (
                                        'name' => $name,
                                        'subscribed' => $subscribed,
                                        'username' => $username !== NULL ? $username : '',
                                        'password' => $password !== NULL ? $password : '',
                                        'url' => $url,
                                        'adult' => $adult,
                                        'expire' => $expire,
                                        'refresh_time' => $refresh_time,
                                        'refresh_period' => $refresh_period
                                    );
                                    $settings[] = $set;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $settings;
    }
    public function read_search_options()
    {
        if ($this->arr === NULL) {
            return array();
        }
        $settings = array();
        foreach ($this->arr as $t1) {
            if (get_string($t1,'tag') == 'urdsettings' && isset($t1['value'])) {
                foreach ($t1['value'] as $t2) {
                    if (get_string($t2,'tag') == 'buttons' && isset($t2['value'])) {
                        foreach ($t2['value'] as $t3) {
                            if (get_string($t3,'tag') == 'button') {
                                $name = get_string($t3,'value');
                                $search_url = get_string($t3['attributes'],'search_url');
                                if ($name !== NULL && $search_url !== NULL) {
                                    $set = array (
                                        'name' => $name,
                                        'search_url' => $search_url
                                    );
                                    $settings[$name] = $set;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $settings;
    }
    public function read_spots_blacklist($userid)
    {
        if ($this->arr === NULL) {
            return array();
        }
        $settings = array();
        foreach ($this->arr as $t1) {
            if (get_string($t1,'tag') == 'urdsettings' && isset($t1['value'])) {
                foreach ($t1['value'] as $t2) {
                    if (get_string($t2,'tag') == 'spots_blacklist' && isset($t2['value'])) {
                        foreach ($t2['value'] as $t3) {
                            if (get_string($t3,'tag') == 'spotter') {
                                $spotter_id = get_string($t3['attributes'],'spotter_id');
                                $source = get_string($t3['attributes'],'source');
                                $status = get_string($t3['attributes'],'status');
                                $userid = get_string($t3['attributes'],'userid');
                                if ($spotter_id !== NULL && $source !== NULL && $status !== NULL&& $userid !== NULL) {
                                    $set = array (
                                        'spotter_id' => $spotter_id,
                                        'source' => $source,
                                        'status' => $status,
                                        'userid' => $userid
                                    );
                                    $settings[$spotter_id] = $set;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $settings;
    }
    public function read_spots_whitelist($userid)
    {
        if ($this->arr === NULL) {
            return array();
        }
        $settings = array();
        foreach ($this->arr as $t1) {
            if (get_string($t1,'tag') == 'urdsettings' && isset($t1['value'])) {
                foreach ($t1['value'] as $t2) {
                    if (get_string($t2,'tag') == 'spots_whitelist' && isset($t2['value'])) {
                        foreach ($t2['value'] as $t3) {
                            if (get_string($t3,'tag') == 'spotter') {
                                $spotter_id = get_string($t3['attributes'],'spotter_id');
                                $source = get_string($t3['attributes'],'source');
                                $status = get_string($t3['attributes'],'status');
                                $userid = get_string($t3['attributes'],'userid');
                                if ($spotter_id !== NULL && $source !== NULL && $status !== NULL&& $userid !== NULL) {
                                    $set = array (
                                        'spotter_id' => $spotter_id,
                                        'source' => $source,
                                        'status' => $status,
                                        'userid' => $userid
                                    );
                                    $settings[$spotter_id] = $set;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $settings;
    }

    public function read_users()
    {
        if ($this->arr === NULL) {
            return array();
        }
        $settings = array();
        foreach ($this->arr as $t1) {
            if (get_string($t1,'tag') == 'urdsettings' && isset($t1['value'])) {
                foreach ($t1['value'] as $t2) {
                    if (get_string($t2,'tag') == 'users' && isset($t2['value'])) {
                        foreach ($t2['value'] as $t3) {
                            if (get_string($t3,'tag') == 'user') {
                                $username = get_string($t3,'value');
                                $password = get_string($t3['attributes'],'password');
                                $fullname = get_string($t3['attributes'],'fullname');
                                $salt = get_string($t3['attributes'],'salt');
                                $email = get_string($t3['attributes'],'email');
                                $isadmin = get_numeric($t3['attributes'],'isadmin');
                                $active = get_numeric($t3['attributes'],'active');
                                $rights = get_string($t3['attributes'],'rights');
                                if ($username !== NULL && $password !== NULL && $fullname !== NULL && $salt !== NULL && $email !== NULL && $isadmin !== NULL && $active !== NULL && $rights !== NULL) {
                                    $set = array (
                                        'username' => $username,
                                        'password' => $password,
                                        'fullname' => $fullname,
                                        'salt' => $salt,
                                        'email' => $email,
                                        'isadmin' => $isadmin,
                                        'active' => $active,
                                        'rights' => $rights,
                                    );
                                    $settings[$username] = $set;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $settings;
    }
    public function read_newsgroup_settings()
    {
        if ($this->arr === NULL) {
            return array();
        }
        $settings = array();
        foreach ($this->arr as $t1) {
            if (get_string($t1,'tag') == 'urdsettings' && isset($t1['value'])) {
                foreach ($t1['value'] as $t2) {
                    if (get_string($t2,'tag') == 'newsgroups' && isset($t2['value'])) {
                        foreach ($t2['value'] as $t3) {
                            if (get_string($t3,'tag') == 'group') {
                                $groupname = get_string($t3,'value');
                                $expire = get_numeric($t3['attributes'],'expire');
                                $refresh_time = get_numeric($t3['attributes'],'refresh_time');
                                $refresh_period = get_numeric($t3['attributes'],'refresh_period');
                                $minsetsize = get_numeric($t3['attributes'],'minsetsize');
                                $maxsetsize = get_numeric($t3['attributes'],'maxsetsize');
                                $adult = get_numeric($t3['attributes'],'adult');
                                if ($groupname !== NULL && $expire !== NULL) {
                                    $set = array (
                                        'groupname' => $groupname,
                                        'expire' => $expire,
                                        'refresh_time' => $refresh_time,
                                        'refresh_period' => $refresh_period,
                                        'adult' => $adult,
                                        'minsetsize' => $minsetsize,
                                        'maxsetsize' => $maxsetsize
                                    );
                                    $settings[$groupname] = $set;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $settings;
    }

    public function read_all_settings(DatabaseConnection $db)
    { // not finished and not called
        if ($this->arr === NULL) {
            return array();
        }
        $settings = array();
        $settings['newsgroups'] = $this->read_newsgroup_settings();
        $settings['rssfeeds'] = $this->read_feeds_settings();
        $settings['users'] = $this->read_users();
        $settings['users_settings'] = $this->read_user_settings($db);
        $settings['buttons'] = $this->search_options();
        $settings['usenet_servers'] = $this->read_usenet_servers();
        $settings['config'] = $this->read_config();

        return $settings;
    }
    public function read_extset_data()
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        $res = array();

        foreach ($this->arr as $t1) {
            if (get_string($t1,'tag') == 'urd_extsetdata' && isset($t1['value'])) {
                foreach ($t1['value'] as $t2) {
                    if (get_string($t2,'tag') == 'set' && isset($t2['value'])) {
                        $groupname = $setid = $name = $value = $type = NULL;
                        foreach ($t2['value'] as $t3) {
                            if (get_string($t3,'tag') == 'groupname') {
                                $groupname = get_string($t3,'value');
                            }
                            if (get_string($t3,'tag') == 'setid') {
                                $setid = get_string($t3,'value');
                            }
                            if (get_string($t3,'tag') == 'name') {
                                $name = get_string($t3,'value');
                            }
                            if (get_string($t3,'tag') == 'value') {
                                $value = get_string($t3,'value');
                            }
                            if (get_string($t3,'tag') == 'type') {
                                $type = get_string($t3,'value');
                            }
                            if ($groupname !== NULL && $setid !== NULL && $name !== NULL && $value !== NULL && $type !== NULL) {
                                $res[] = array(5 => $groupname, 0 =>$setid, 1 =>$name, 2 => $value, 3 =>$type);
                            }
                        }
                    }
                }
            }
        }

        return $res;
    }
}
