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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_edit_usenet_servers.php 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathaeus = realpath(dirname(__FILE__));
require_once "$pathaeus/../functions/ajax_includes.php";
require_once "$pathaeus/../functions/pref_functions.php";

verify_access($db, NULL, TRUE, '', $userid, TRUE);

class usenet_servers_c
{
    public $edit;
    public $id;
    public $name;
    public $hostname;
    public $port;
    public $sec_port;
    public $authentication;
    public $username;
    public $password;
    public $threads;
    public $connection;
    public $compressed_headers;
    public $connection_raw;
    public $posting;
    public $priority; // 1 is highest, 100 is lowest; 0 = disabled
    public function __construct ($edit, $id='', $n='', $h='', $p=119, $sp=563, $a=FALSE, $t=3, $c='off', $cr= 'off', $u='', $pw='', $priority=10, $ch=0, $post=FALSE, $ipversion='both')
    {
        assert((is_numeric($id)|| $id=='') && is_numeric($p) && is_numeric($sp) && is_bool($a) && is_numeric($priority));
        $this->edit = $edit;
        $this->id = $id;
        $this->name = $n;
        $this->hostname = $h;
        $this->port = $p;
        $this->sec_port = $sp;
        $this->authentication = $a;
        $this->threads = $t;
        $this->connection = $c;
        $this->connection_raw = $cr;
        $this->username = $u;
        $this->password = $pw;
        $this->compressed_headers = $ch;
        $this->active = $priority > 0;
        $this->priority = $priority;
        $this->posting = $post;
        $this->ipversion = $ipversion;
    }
};

function verify_usenet_server_id(DatabaseConnection $db, $id)
{
    global $LN;
    if (!is_numeric($id)) {
        throw new exception($LN['error_nosuchserver']);
    }
    $sql = 'COUNT(*) AS cnt FROM usenet_servers WHERE "id"=:id';
    $res = $db->select_query($sql, array(':id'=>$id));
    if ($res === FALSE) {
        throw new exception($LN['error_nosuchserver']);
    }
    if ($res[0]['cnt'] != 1) {
        throw new exception($LN['error_nosuchserver']);
    }
}

try {
    $message = '';
    $name_pattern       = '[ a-zA-Z0-9_\-.():,@!$%^&*+=]';
    $hostname_pattern   = '[a-zA-Z0-9.\-_:\[\]]';

    $prefs_root = load_config($db);
    $connection_types = [
        'off'=>$LN['off'],
        'ssl'=>'SSL',
        'tls'=>'TLS'
    ];
    
    $ipversions = [ 'both' => $LN['both'], 'ipv4'=> 'IPv4', 'ipv6' => 'IPv6' ];

    $cmd = get_request('cmd');
    $id = get_request('id');

    switch (strtolower($cmd)) {
        case 'import_settings':
            if (isset ($_FILES['filename']['tmp_name'])) {
                challenge::verify_challenge($_POST['challenge']);
                $xml = new urd_xml_reader($_FILES['filename']['tmp_name']);
                $usenet_servers = $xml->read_usenet_servers($db);
                if ($usenet_servers != array()) {
                    clear_all_usenet_servers($db);
                    set_all_usenet_servers($db, $usenet_servers);
                    $prefs = $prefs_root;
                    stop_urdd($userid);
                    usleep(500000);
                    start_urdd();
                } else {
                    throw new exception($LN['error_nosearchoptionsfound']);
                }
            }
            break;
        case 'export_settings':
            export_settings($db, 'usenet_servers', 'urd_usenet_servers_settings.xml');
            break;
        case 'disable_auth':
            challenge::verify_challenge($_POST['challenge']);
            verify_usenet_server_id($db, $id);
            smart_update_usenet_server($db, $id, array('authentication'=> 0, 'username'=>'', 'password'=>''));
            $uc = new urdd_client($db, $prefs_root['urdd_host'], $prefs_root['urdd_port'], $userid);
            if ($uc->is_connected()) {
                $uc->set('server', 'reload', $id);
            }
            break;
        case 'delete_server':
            challenge::verify_challenge($_POST['challenge']);
            verify_usenet_server_id($db, $id);
            delete_usenet_server($db, $id);
            $uc = new urdd_client($db, $prefs_root['urdd_host'], $prefs_root['urdd_port'], $userid);
            if ($uc->is_connected()) {
                $uc->set('server', 'delete', $id);
                usleep(50000);
            }
            break;
        case 'disable_posting':
            challenge::verify_challenge($_POST['challenge']);
            verify_usenet_server_id($db, $id);
            $uc = new urdd_client($db, $prefs_root['urdd_host'], $prefs_root['urdd_port'], $userid);
            if ($uc->is_connected()) {
                $uc->set('server', 'noposting', $id);
                usleep(50000);
            } else {
                set_posting($db, $id, FALSE);
            }
            break;
        case 'enable_posting':
            challenge::verify_challenge($_POST['challenge']);
            verify_usenet_server_id($db, $id);
            $uc = new urdd_client($db, $prefs_root['urdd_host'], $prefs_root['urdd_port'], $userid);
            if ($uc->is_connected()) {
                $uc->set('server', 'posting', $id);
                usleep(50000);
            } else {
                set_posting($db, $id, TRUE);
            }
            break;

        case 'enable_server':
            challenge::verify_challenge($_POST['challenge']);
            verify_usenet_server_id($db, $id);
            $uc = new urdd_client($db, $prefs_root['urdd_host'],$prefs_root['urdd_port'],$userid);
            $prio = DEFAULT_USENET_SERVER_PRIORITY;
            if ($uc->is_connected()) {
                $uc->set('server', $id, $prio);
                usleep(50000);
            } else {
                enable_usenet_server($db, $id, $prio);
            }
            break;
        case 'disable_server':
            challenge::verify_challenge($_POST['challenge']);
            verify_usenet_server_id($db, $id);
            $uc = new urdd_client($db, $prefs_root['urdd_host'], $prefs_root['urdd_port'], $userid);
            $prio = DISABLED_USENET_SERVER_PRIORITY;
            if ($uc->is_connected()) {
                $uc->set('server', $id, $prio);
                usleep(50000);
            } else {
                disable_usenet_server($db, $id);
            }
            break;
        case 'set_preferred':
            challenge::verify_challenge($_POST['challenge']);
            verify_usenet_server_id($db, $id);
            $uc = new urdd_client($db, $prefs_root['urdd_host'], $prefs_root['urdd_port'], $userid);
            if ($uc->is_connected()) {
                $uc->set('preferred', $id);
                usleep(50000);
                config_cache::clear(0);
            } else {
                set_config($db, 'preferred_server', $id);
            }
            break;
        case 'showeditusenetserver':
            $only_auth = (get_request('only_auth', 0) ? TRUE : FALSE);
            if (is_numeric($id)) {
                $srv = get_usenet_server($db, $id, FALSE);
                $name = $srv['name'];
                $username = $srv['username'];
                $hostname = $srv['hostname'];
                $password = $srv['password'];
                $port = $srv['plain_port'];
                $threads = $srv['threads'];
                $sec_port = $srv['secure_port'];
                $authentication = $srv['authentication'];
                $priority = $srv['priority'];
                $connection = $srv['connection'];
                $posting = $srv['posting'];
                $compressed_headers = $srv['compressed_headers'];
                $ipversion = $srv['ipversion'];

            } elseif ($id == 'new') {
                $name = '';
                $username = '';
                $hostname = '';
                $password = '';
                $threads = 1;
                $port = 119;
                $sec_port = 563;
                $authentication = 0;
                $priority = 10;
                $connection = 'off';
                $compressed_headers = 0;
                $posting = 0;
                $ipversion = 'both';
            } else {
                throw new exception($LN['error_nosuchserver']);
            }
            if ($only_auth) {
                $authentication = 1;
            }

            init_smarty();
            $smarty->assign(array(
                'id'=> $id,
                'name'=> $name,
                'only_auth'=> $only_auth,
                'connection_types'=>    $connection_types,
                'ipversions'=>    $ipversions,
                'hostname'=> $hostname,
                'username'=> $username,
                'password'=> $password,
                'compressed_headers'=> $compressed_headers,
                'text_box_size'=> TEXT_BOX_SIZE,
                'number_box_size'=> NUMBER_BOX_SIZE,
                'port'=> $port,
                'posting'=> $posting,
                'threads'=> $threads,
                'sec_port'=> $sec_port,
                'authentication'=> $authentication,
                'priority'=> $priority,
                'ipversion'=> $ipversion,
                'connection'=> $connection));
            $contents = $smarty->fetch('ajax_edit_usenet_servers.tpl');
            return_result(array('contents' => $contents));
            break;

        case 'update_usenet_server':
            challenge::verify_challenge($_REQUEST['challenge']);
            if (is_numeric($id) || $id == 'new') {
                $error = '';
                $hostname = trim(get_request('hostname'));
                $name = trim(get_request('name'));
                $sec_port = get_request('secure_port', 0);
                $port = get_request('port', 0);
                $priority = get_request('priority', 0);
                $threads = get_request('threads', 0);
                $connection = get_request('connection', 'off');
                $ipversion = get_request('ipversion', 'both');
                $compressed_headers = (get_request('compressed_headers', '0') == '1')? 1 : 0;
                $posting = (get_request('posting', '0') == '1')? 1 : 0;

                if (($error = verify_text($name, $name_pattern)) != '') {
                    throw new exception($LN['name'] . ': ' . $error['msg']);
                }
                if (($error = verify_text($hostname, $hostname_pattern)) != '') {
                    throw new exception($LN['usenet_hostname'] . ': ' . $error['msg']);
                }

                if (($error = verify_numeric_opt($sec_port, TRUE, 0, 65535, 1000)) != '') {
                    throw new exception($LN['usenet_secport'] . ': ' . $error['msg']);
                }

                if (($error = verify_numeric_opt($port, TRUE, 0, 65535, 1000)) != '') {
                    throw new exception($LN['usenet_port'] . ': ' . $error['msg']);
                }

                if (($port == '' || $port == 0) && ($sec_port == '' || $sec_port == 0)) {
                    throw new exception($LN['error_needatleastoneport']);
                }

                if (($error = verify_numeric_opt($priority, TRUE, 0, 100, 1000)) != '') {
                    throw new exception($LN['usenet_priority'] . ': ' . $error['msg']);
                }
                if (($error = verify_numeric($threads, 1, NULL, 1000)) != '') {
                    throw new exception($LN['usenet_threads'] . ': ' . $error['msg']);
                }

                if (($error = verify_array($ipversion, array_keys($ipversions))) != '') {
                    throw new exception($LN['ipversions'] . ': ' . $error['msg'] . ' '  . $ipversion);
                }
                if (($error = verify_array($connection, array_keys($connection_types))) != '') {
                    throw new exception($LN['usenet_connection'] . ': ' . $error['msg']);
                }
                if ($connection != 'off' && ($sec_port == '' || $sec_port == 0)) {
                    throw new exception($LN['error_needsecureport']);
                }
                if (get_request('authentication', '0') == '1') {
                    $authentication = TRUE;
                    $username = trim(get_request('username'));
                    if (!verify_text($username) == '') {
                        throw new exception($LN['error_invalidusername'] . $username);
                    }
                    $password = trim(get_request('password'));
                    $pwmsg = verify_text($password);
                    if ($pwmsg != '') {
                        throw new exception($pwmsg['msg']);
                    }
                } else {
                    $authentication = 0;
                    $username = '';
                    $password = '';
                }
                if ($id == 'new') {
                    $query = '"id" FROM usenet_servers WHERE "name"=:name';
                    $res = $db->select_query($query, 1, array(':name'=> $name));
                    if ($res === FALSE) {
                        $pref_server = get_config($db, 'preferred_server', 0);
                        $id = add_usenet_server($db, $name, $hostname, $port, $sec_port, $threads, $connection, $authentication, $username, $password, $priority, $compressed_headers, $posting, $ipversion);
                        $uc = new urdd_client($db, $prefs_root['urdd_host'], $prefs_root['urdd_port'], $userid);
                        if ($uc->is_connected()) {
                            $uc->set('server', 'load', $id);
                            if ($pref_server == 0) {
                                $uc->set('server', 'PREFERRED', $id);
                            }
                        } else {
                            if ($pref_server == 0) {
                                set_config($db, 'preferred_server', $id);
                            }
                        }
                    } else {
                        throw new exception ($LN['error_usenetserverexists']);
                    }
                } elseif (is_numeric($id)) {
                    $query = '"name" FROM usenet_servers WHERE "id"=:id';
                    $res = $db->select_query($query, 1, array(':id'=>$id));
                    if ($res !== FALSE) {
                        update_usenet_server($db, $id, $name, $hostname, $port, $sec_port, $threads, $connection, $authentication, $username, $password, $priority, $compressed_headers, $posting, $ipversion);

                        $uc = new urdd_client($db, $prefs_root['urdd_host'], $prefs_root['urdd_port'], $userid);
                        if ($uc->is_connected()) {
                            $uc->set('server', 'reload', $id);
                        }
                    } else {
                        throw new exception ($LN['error_nosuchserver']);
                    }
                }
            }
            break;
        case 'reload_servers':
            load_config($db, TRUE);
            $primary = get_config($db, 'preferred_server', '');
            $sort = get_request('sort', 'name');
            $sort_dir = get_request('sort_dir', 'asc');
            $view_size  = get_request('view_size', 1024);

            $search = $o_search = (trim(get_request('search', '')));
            $Qsearch = '';
            $input_arr = array();
            if ($search != '') {
                $like = $db->get_pattern_search_command('LIKE');
                $Qsearch = " WHERE \"name\" $like :search ";
                $input_arr[':search'] = "%$search%";
            }
            if (!in_array($sort, array('name', 'connection', 'authentication', 'username', 'posting', 'priority', 'threads', 'ipversion'))) {
                $sort = 'name';
            }
            if (!in_array($sort_dir, array('asc', 'desc'))) {
                $sort_dir = 'asc';
            }

            $sql = "* FROM usenet_servers $Qsearch ORDER BY $sort $sort_dir";
            $res = $db->select_query($sql, $input_arr);
            if ($res == FALSE) {
                $res = array();
            }
            $usenet_servers= array();
            foreach ($res as $row) {
                $id = $row['id'];
                $name = $row['name'];
                $hostname = $row['hostname'];
                $port = $row['port'];
                $sec_port = $row['secure_port'];
                $threads = $row['threads'];
                $conn = (isset($connection_types[$row['connection']])) ? ($connection_types[$row['connection']]) : 'off';
                $conn_raw = $row['connection'];
                $auth = $row['authentication'];
                $username = $row['username'];
                $password = $row['password'];
                $posting = $row['posting'];
                $ipversion = $row['ipversion'];
                $compressed_headers = $row['compressed_headers'];
                $priority = $row['priority'];
                $usenet_servers[] = new usenet_servers_c(0, $id, $name, $hostname, $port, $sec_port, (bool) $auth, $threads, $conn, $conn_raw, $username, $password, $priority, $compressed_headers, $posting, $ipversion);
            }
            init_smarty();
            $smarty->assign(array(
                'maxstrlen'=>      $prefs['maxsetname']/2,
                'usenet_servers'=> $usenet_servers,
                'sort'=>	       $sort,
                'sort_dir'=>	   $sort_dir,
                'search'=>         $o_search,
                'view_size' =>     $view_size,
                'primary'=>        $primary));
            $contents = $smarty->fetch('ajax_show_usenet_servers.tpl');
            return_result(array('contents' => $contents));
            break;
        default:
            throw new exception ($LN['error_invalidaction'] . ": $cmd");
    }
    return_result(array('message' => $message));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}

