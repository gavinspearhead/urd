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
 * $LastChangedDate: 2014-06-07 17:12:45 +0200 (za, 07 jun 2014) $
 * $Rev: 3082 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_usenet_servers.php 3082 2014-06-07 15:12:45Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class usenet_server
{
    private $id; //id
    private $max_threads; //int
    private $used_threads; // int
    private $blocked_threads;
    private $port; // int
    private $hostname; // string
    private $encryption; // off, ssl or tls
    private $username; // string
    private $password; //string
    private $priority; // number (0 == disabled)
    private $posting;
    private $enabled;

    const NNTP_ABS_MAX = 64; // the maximum number of connection that urdd will open at the same time

    public function __construct ($id, $maxt, $port, $hostname, $encr, $username, $pw, $prio, $posting)
    {
        assert (is_numeric($maxt) && is_numeric($port) && is_numeric($prio) && is_numeric($id));
        $this->id = (int) $id;
        $this->max_threads = (int) $maxt;
        $this->used_threads = (int) 0;
        $this->blocked_threads = (int) 0;
        $this->port = (int) $port;
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $pw;
        $this->priority = (int) $prio;
        $this->encryption = $encr;
        $this->posting = $posting;
        $this->enabled = TRUE;
    }
    public function __destruct()
    {
        // stub
    }

    public function restore_server_settings()
    {
        $this->used_threads = (int) 0;
        $this->blocked_threads = (int) 0;
    }

    public function print_size()
    {
        echo_debug("{$this->id} ({$this->priority}): {$this->max_threads} {$this->free_threads} {$this->blocked_threads}", LOG_SERVER);
    }

    public function update_server($maxt=NULL, $port=NULL, $hostname=NULL, $encr=NULL, $username=NULL, $pw=NULL, $prio=NULL, $posting= NULL)
        // a null value means don't update it
    {
        if ($maxt !== NULL) {
            assert (is_numeric($maxt));
            $this->max_threads = (int) $maxt;
            if ($this->blocked_threads >= $maxt) {
                $this->blocked_threads = max(0, $maxt - 1);
            }
        }
        if ($port !== NULL) {
            assert(is_numeric($port));
            $this->port = (int) $port;
        }
        if ($hostname !== NULL) {
            $this->hostname = $hostname;
        }
        if ($username !== NULL) {
            $this->username = $username;
        }
        if ($pw !== NULL) {
            $this->password = $pw;
        }
        if ($prio !== NULL) {
            assert ( is_numeric($prio));
            if ($prio > 0) { 
                $this->enabled = TRUE;
            }
            $this->priority = (int) $prio;
        }
        if ($encr !== NULL) {
            $this->encryption = $encr;
        }
        if ($posting !== NULL) {
            $this->posting = $posting;
        }
    }
    public function add_thread()
    {
        if (($this->used_threads + $this->blocked_threads) < $this->max_threads) {
            $this->used_threads++;
        } else {
            throw new exception ('No slot available 1', ERR_NO_NNTPSLOT_AVAILABLE);
        }
    }
    public function delete_thread()
    {
        if ($this->used_threads > 0) {
            $this->used_threads--;
        }
    }
    public function has_free_slot($update_server = FALSE)
    {
        return ((($this->used_threads + $this->blocked_threads) < $this->max_threads)) && (($this->priority > 0) || ($this->id == $update_server) && $this->is_enabled());
    }
    public function get_free_slots()
    {
        return $this->max_threads - ($this->used_threads + $this->blocked_threads);
    }
    public function get_max_slots()
    {
        return $this->max_threads;
    }
    public function disable_posting()
    {
        $this->posting = FALSE;
    }
    public function enable_posting()
    {
        $this->posting = TRUE;
    }
    public function set_priority($prio)
    {
        assert(is_numeric($prio) && $prio >= 0);
        $this->priority = $prio;
    }

    public function disable()
    {
        $this->enabled = FALSE;
    }
    public function enable()
    {
        $this->enabled = TRUE;
    }
    public function get_id()
    {
        return $this->id;
    }
    public function get_port()
    {
        return $this->port;
    }
    public function get_hostname()
    {
        return $this->hostname;
    }
    public function get_encryption()
    {
        return $this->encryption;
    }
    public function get_posting()
    {
        return $this->posting;
    }
    public function get_username()
    {
        return $this->username;
    }
    public function get_password ()
    {
        return $this->password;
    }
    public function get_priority()
    {
        return $this->priority;
    }
    public function is_enabled()
    {
        return $this->enabled === TRUE;
    }
    public function reset_max_slots()
    {
        $this->blocked_threads = 0;
    }
    public function dec_max_slots()
    {
        if ($this->blocked_threads < $this->max_threads) {
            $this->blocked_threads++;
        }
    }
    public function inc_max_slots()
    {
        if ($this->blocked_threads >= 1) {
            $this->blocked_threads--;
        }
    }
    public function find_max_connections(DatabaseConnection $db, $update_server, test_result_list &$test_results, $check_nntp_connections)
    {
        assert(is_numeric($update_server));
        if ($this->id != $update_server && $this->get_priority() <= 0) { // we only test actually used servers
            return;
        }
        if (0 != $this->used_threads) {
            throw new exception ('Has threads running, cancel all threads first', ERR_SERVER_IN_USE);
        }

        $top = $this->max_threads;
        if ($top <= 0) {
            $top = self::NNTP_ABS_MAX;
        }
        $max_conn = max(1, min($top, self::NNTP_ABS_MAX));
        $conns = array();
        if ($check_nntp_connections) {
            $timeout = get_config($db, 'socket_timeout', '-1');
            if ($timeout <= 0) {
                $timeout = socket::DEFAULT_SOCKET_TIMEOUT;
            }
            for ($i = 0; $i < $max_conn; $i++) {
                try {
                    $auth = ($this->username != '') && ($this->password != '');
                    $conns[$i] = new URD_NNTP($db, $this->hostname, $this->encryption, $this->port, $timeout);
                    $conns[$i]->connect($auth, $this->username, $this->password);
                } catch (exception $e) { // connection failed
                    echo_debug($e->getMessage(), DEBUG_SERVER);
                    break;
                }
            }
            $this->max_threads = $i;
            echo_debug("Found $i connections for server {$this->hostname}", DEBUG_SERVER);

            if ($i == 0) {
                $this->disable();
                $this->set_priority(0);
                    
                $test_results->add(new test_result("Server: {$this->hostname}:{$this->port}", FALSE, "Server: {$this->hostname}:{$this->port} disabled"));
            } else {
                $test_results->add(new test_result("Server: {$this->hostname}:{$this->port}", TRUE, "Server: {$this->hostname}:{$this->port} enabled with $i connections"));
            }
        } else {
            $this->max_threads = $max_conn;
        }
    }
    public function test_server(DatabaseConnection $db)
    {
        static $groups = array ('alt.binaries.test', 'alt.binaries.boneless', 'alt.binaries.tv', 'alt.binaries.mp3', 'alt.binaries.linux');
        if (0 != $this->used_threads) {
            throw new exception ('Has threads running, cancel all threads first', ERR_SERVER_IN_USE);
        }
        $timeout = get_config($db, 'socket_timeout', '-1');
        if ($timeout <= 0) {
            $timeout = socket::DEFAULT_SOCKET_TIMEOUT;
        }
        $found = $indexing = FALSE;
        try {
            $auth = ($this->username != '') && ($this->password != '');
            $conn = new URD_NNTP($db, $this->hostname, $this->encryption, $this->port, $timeout);
            $posting = $conn->connect($auth, $this->username, $this->password);
            $found = TRUE;
            foreach ($groups as $group) {
                if ($conn->test_nntp($group, $code) === TRUE) {
                    $indexing = TRUE;
                    break;
                }
            }
            $conn->disconnect();
        } catch (exception $e) { // connection failed
            $conn->disconnect();
            throw $e;
        }

        return array ($found, $indexing, $posting);
    }
    public function find_server(DatabaseConnection $db, &$test_results, $dbid, $current, $total, $extended, $update_config, &$set_update_server)
    {
        if (0 != $this->used_threads) {
            throw new exception ('Has threads running, cancel all threads first', ERR_SERVER_IN_USE);
        }
        static $test_ports_basic = array(119, 563);
        static $test_ports_extended = array(119, 563, 23, 25, 80, 81, 563, 443, 564, 600, 663, 644, 8080, 7000, 8000, 9000); // most of the ports we know off that are used
        $test_ports = ($extended === TRUE) ? $test_ports_extended : $test_ports_basic;

        static $groups = array ('alt.binaries.test', 'alt.binaries.boneless', 'alt.binaries.tv', 'alt.binaries.mp3', 'alt.binaries.linux');
        $timeout = get_config($db, 'socket_timeout');
        if ($timeout <= 0) {
            $timeout = socket::DEFAULT_SOCKET_TIMEOUT;
        }
        $hostname = $this->hostname;
        $encryption = array ('off', 'ssl', 'tls');
        $port = $code = 0;
        $found_one = FALSE;
        $tp_count = count($test_ports);
        $e_count = count($encryption);
        $total_count = $total * $tp_count * $e_count;
        $curr_count = $current * $tp_count * $e_count;
        foreach ($test_ports as $p) {
            foreach ($encryption as $e) {
                $curr_count++;
                $auth = ($this->username != '') && ($this->password != '');
                try {
                    $conn = new URD_NNTP($db, $hostname, $e, $p, $timeout);
                    $posting = $conn->connect($auth, $this->username, $this->password);
                    reset($groups);
                    if ($conn->server_needs_auth(current($groups))) {
                        $auth = TRUE;
                        $conn->connect($auth, $this->username, $this->password);
                    }

                    $p_str = ($posting === TRUE) ? 'Posting allowed' : 'Posting not allowed';
                    $found_one = TRUE;
                    $indexing = FALSE;
                    $comp_headers = FALSE;
                    foreach ($groups as $group) {
                        if ($conn->test_nntp($group, $code) === TRUE) {
                            $port = $p;
                            $indexing = TRUE;
                            $comp_headers = $conn->test_compressed_headers_nntp($group, $code);
                            $ch_str = $comp_headers ? 'Compressed headers supported' : '';
                            write_log ("Found setting: $hostname port: $p encryption: $e group: $group ($p_str) indexing allowed $ch_str", LOG_NOTICE);
                            $test_results->add(new test_result("$hostname $p $e group: $group " , TRUE, "port: $p encryption: $e group: $group ($p_str) indexing allowed $ch_str"));
                            break;
                        } else {
                            write_log ("Found setting: $hostname port: $p encryption: $e group: $group (code $code) indexing not allowed", LOG_NOTICE);
                            $test_results->add(new test_result("$hostname $p $e", TRUE, "port: $p encryption: $e group: $group failed (code $code) indexing not allowed"));
                        }
                    }
                    if ($e == 'off') {
                        $secure_port = NULL;
                        $port = $port;
                    } else {
                        $secure_port = $port;
                        $port = NULL;
                    }
                    if ($update_config === TRUE) {
                        smart_update_usenet_server($db, $this->id, array('hostname'=> $hostname, 'port'=> $port, 'secury_port'=>$secure_port, 'connection'=>$e, 'priority'=>DEFAULT_USENET_SERVER_PRIORITY, 'compressed_headers'=>$comp_headers, 'posting'=>$posting));
                        if ($indexing === TRUE) {
                            if ($set_update_server) {
                                set_config($db, 'preferred_server', $this->id);
                                write_log("Setting as indexing server: $hostname:$p ({$this->id})", LOG_NOTICE);
                                $set_update_server = FALSE;
                            }
                        }
                    }
                    $conn->disconnect();
                } catch (exception $exc) {
                    write_log ("Result $hostname $p $e: {$exc->getMessage()} ({$exc->getCode()})", LOG_NOTICE);
                    $test_results->add(new test_result("$hostname $p $e", FALSE, "{$exc->getMessage()} ({$exc->getCode()})"));
                }
                $status = QUEUE_RUNNING;
                if ($dbid !== NULL) {
                    update_queue_status($db, $dbid, $status, 0, floor((100 * $curr_count) / $total_count), '');
                }
            }
        }
        if ($found_one === FALSE) {
            if ($update_config === TRUE) {
                smart_update_usenet_server($db, $this->id, array('priority' => DISABLED_USENET_SERVER_PRIORITY));
                if (get_config($db, 'preferred_server') == $this->id) {
                    set_config($db, 'preferred_server', 0);
                }
            }
            write_log ("Cannot find settings for $hostname ", LOG_NOTICE);
            $this->disable();
        }
        $test_results->add(new test_result($hostname, FALSE, "Cannot find settings for $hostname"));
    }
}


class usenet_servers
{
    private $servers;
    private $update_server;
    const BACKUP_PRIO = 50;

    public function restore_server_settings()
    {
        foreach ($this->servers as $s) {
            $s->restore_server_settings();
        }
    }
    public function reset_servers()
    {
        $this->servers = array();
        $this->update_server = 0;
    }
    public function print_size()
    {
        foreach ($this->servers as $s) {
            $s->print_size();
        }
    }
    public function is_backup_server($server_id)
    {
        if (isset ($this->servers[$server_id])) {
            return ($this->servers[$server_id]->get_priority() > self::BACKUP_PRIO);
        } else {
            throw new exception ("Server ($server_id) does not exist", ERR_NO_SUCH_SERVER);
        }
    }

    public function __construct()
    {
        $this->reset_servers();
    }
    public function __destruct()
    {
        $this->servers = NULL;
    }
    public function set_update_server($server_id)
    {
        assert(is_numeric($server_id));
        if (isset ($this->servers[$server_id])) {
            $this->update_server = $server_id;
        } else {
            throw new exception ("Server ($server_id) does not exist", ERR_NO_SUCH_SERVER);
        }
    }
    public function get_update_server()
    {
        return $this->update_server;
    }
    public function has_free_slot($server_id)
    {
        assert(is_numeric($server_id));

        return isset ($this->servers[$server_id]) && $this->servers[$server_id]->has_free_slot($this->update_server);
    }
    public function get_max_threads($server_id)
    {
        assert(is_numeric($server_id));
        if (isset ($this->servers[$server_id])) {
            return $this->servers[$server_id]->get_max_slots();
        } else {
            throw new exception ("Server ($server_id) does not exist", ERR_NO_SUCH_SERVER);
        }
    }
    public function unused_servers_available(array $already_used_servers = array(), $must_to_have_free_slot = TRUE)
    {
        if ($must_to_have_free_slot) {
            foreach ($this->servers as $srv) {
                if ($srv->is_enabled() && $srv->get_priority() > 0 && !in_array($srv->get_id(), $already_used_servers) && $srv->has_free_slot()) {
                    return $srv->get_id();
                }
            }
        } else {
            foreach ($this->servers as $srv) {
                if ($srv->is_enabled() && $srv->get_priority() > 0 && !in_array($srv->get_id(), $already_used_servers)) {
                    return $srv->get_id();
                }
            }
        }

        return FALSE;
    }
    public function find_free_slot(array $already_used_servers=array(), $need_posting=FALSE, $is_download = FALSE)
    {
        $server_id = FALSE;
        $prio = 0;
        foreach ($this->servers as $srv) {
            $srv_prio = $srv->get_priority();
            $id = $srv->get_id();
            if ($srv->has_free_slot($this->update_server) 
                && $srv->is_enabled() 
                && $srv->get_priority() > 0
                && ($srv_prio < $prio || $server_id === FALSE) 
                && !in_array($id, $already_used_servers)
                && ($need_posting === FALSE || $srv->get_posting())
                && (!$is_download || $srv_prio < self::BACKUP_PRIO || count($already_used_servers) > 0)) 
            {
                $server_id = $srv->get_id();
                $prio = $srv->get_priority();
            }
        }

        return $server_id;
    }
    public function add_server(usenet_server $server)
    {
        $id = $server->get_id();
        if (isset($this->servers[$id])) {
            throw new exception ("Server ($id) already exists", ERR_SERVER_EXISTS);
        }
        $this->servers[$id] = $server;

        return ($server->get_priority() > 0) ? 1 : 0;
    }
    public function delete_server($id)
    {
        assert(is_numeric($id));
        if (!isset($this->servers[$id])) {
            throw new exception ("Server ($id) does not exist", ERR_NO_SUCH_SERVER);
        }
        unset($this->servers[$id]);
        // XXX need to kill existing connections on this server???
    }
    public function update_server(array $srv, $id)
    {
        assert(is_numeric($id));
        if (!isset($this->servers[$id])) {
            throw new exception ("Server ($id) does not exist", ERR_NO_SUCH_SERVER);
        }
        $this->servers[$id]->update_server($srv['threads'], $srv['port'], $srv['hostname'], $srv['connection'], $srv['username'], $srv['password'], $srv['priority']);

    }
    public function add_thread($id)
    {
        if ($id === FALSE) {
            return TRUE;
        }
        assert (is_numeric($id) && $id > 0);
        if (!isset($this->servers[$id])) {
            throw new exception ("Server ($id) does not exist", ERR_NO_SUCH_SERVER);
        }

        return $this->servers[$id]->add_thread();
    }
    public function delete_thread($server_id)
    {
        if ($server_id === FALSE) {
            return TRUE;
        }
        if ($server_id == 0) {
            write_log ('No valid server_id found: Should not happen', LOG_WARNING);

            return TRUE;
        }

        $id = (int) $server_id;
        if (!isset($this->servers[$id])) {
            throw new exception ("Server ($id) does not exist", ERR_NO_SUCH_SERVER);
        }

        return $this->servers[$id]->delete_thread();
    }
    public function enable_posting($server_id)
    {
        assert(is_numeric($server_id));
        $id = (int) $server_id;
        if (isset($this->servers[$id])) {
            $this->servers[$id]->enable_posting();
        } else {
            throw new exception ("Cannot find server $id", ERR_NO_SUCH_SERVER);
        }
    }
    public function disable_posting($server_id)
    {
        assert(is_numeric($server_id));
        $id = $server_id;
        if (isset($this->servers[$id])) {
            $this->servers[$id]->disable_posting();
        } else {
            throw new exception ("Cannot find server $id", ERR_NO_SUCH_SERVER);
        }
    }
    public function enable_server($server_id, $prio)
    {
        assert(is_numeric($server_id) && is_numeric($prio));
        $id = (int) $server_id;
        if (isset($this->servers[$id])) {
            $this->servers[$id]->enable();
            $this->servers[$id]->set_priority($prio);
        } else {
            throw new exception ("Cannot find server $id", ERR_NO_SUCH_SERVER);
        }
    }
    public function set_priority($server_id, $prio)
    {
        assert(is_numeric($server_id) && is_numeric($prio));
        $id = (int) $server_id;
        if (isset($this->servers[$id])) {
            $this->servers[$id]->set_priority($prio);
        } else {
            throw new exception ("Cannot find server $id", ERR_NO_SUCH_SERVER);
        }
    }

    public function disable_server($server_id)
    {
        assert(is_numeric($server_id));
        $id = (int) $server_id;
        if (isset($this->servers[$id])) {
            $this->servers[$id]->disable();
        } else {
            throw new exception ("Cannot find server $id", ERR_NO_SUCH_SERVER);
        }
    }
    public function find_max_connections(DatabaseConnection $db, test_result_list &$test_results, $check_nntp_connections)
    {
        $current = 0;
        $count = count($this->servers);
        foreach ($this->servers as $srv) {
            $srv->find_max_connections($db, $this->update_server, $test_results, $check_nntp_connections);
            set_config($db, 'urdd_startup', 20 + floor(30 * ($current / $count)));
        }
    }
    public function get_servers()
    {
        $srvs = array();
        foreach ($this->servers as $s) {
            $srv['id'] = $s->get_id();
            $srv['preferred'] = ($srv['id'] == $this->update_server) ? TRUE : FALSE;
            $srv['max_threads'] = $s->get_max_slots();
            $srv['free_threads'] = $s->get_free_slots();
            $srv['port'] = $s->get_port();
            $srv['hostname'] = $s->get_hostname();
            $srv['encryption'] = $s->get_encryption();
            $srv['username'] = $s->get_username();
            $srv['password'] = $s->get_password() == '' ? '': '******';
            $srv['priority'] = $s->get_priority();
            $srv['posting'] = $s->get_posting();
            $srv['enabled'] = $s->is_enabled() ? TRUE : FALSE;
            $srvs[$srv['id']] = $srv;
        }
        ksort($srvs);

        return $srvs;
    }
    public function inc_free_slots($server_id)
    {
        if ($server_id === FALSE) {
            return;
        }
        $id = $server_id;
        if (isset($this->servers[$id])) {
            $this->servers[$id]->inc_max_slots();
        } else {
            throw new exception ("Cannot find server $id", ERR_NO_SUCH_SERVER);
        }
    }
    public function dec_free_slots($server_id)
    {
        if ($server_id === FALSE) {
            return;
        }
        $id = $server_id;
        if (isset($this->servers[$id])) {
            $this->servers[$id]->dec_max_slots();
        } else {
            throw new exception ("Cannot find server $id", ERR_NO_SUCH_SERVER);
        }
    }
    public function reset_connection_limits($server_id=FALSE)
    {
        foreach ($this->servers as $id => &$srv) {
            if ($server_id === FALSE || $id == $server_id) {
                $srv->reset_max_slots();
            }
        }
    }
    public function test_servers(DatabaseConnection $db, test_result_list &$test_results)
    {
        foreach ($this->servers as $srv) {
            try {
                list($res, $indexing, $posting) = $srv->test_server($db);
                $p_str = ($posting === TRUE) ? 'Posting allowed' : 'Posting not allowed';
                if ($indexing === TRUE) {
                    $test_results->add(new test_result("{$srv->get_hostname()}:{$srv->get_port()}", TRUE, "We can probably use this server for indexing ($p_str)"));
                    write_log('We can probably use this server for indexing: ' . $srv->get_hostname() . ':' . $srv->get_port() . " ($p_str)", LOG_NOTICE);
                } else {
                    $test_results->add(new test_result("{$srv->get_hostname()}:{$srv->get_port()}", FALSE, 'We probably cannot use this server for indexing'));
                    write_log('We probably cannot use this server for indexing: ' . $srv->get_hostname() . ':' . $srv->get_port(), LOG_NOTICE);
                }
            } catch (exception $e) {
                    $test_results->add(new test_result("{$srv->get_hostname()}:{$srv->get_port()}", FALSE, 'The server is  probably not configured right. Error: ' . $e->getmessage()));
                    write_log('Something went wrong testing: ' . $srv->get_hostname() . ':' . $srv->get_port() . ' probably not configured right', LOG_ERR);
            }
        }
    }
    public function find_servers(DatabaseConnection $db, test_result_list &$test_results, $dbid, $type, $update_config)
    {
        write_log('Starting to discover usenet servers we can use. (This may take a long time!)', LOG_WARNING);
        $set_update_server = TRUE;
        $total = count($this->servers);
        $current = 0;
        foreach ($this->servers as &$srv) {
            $srv->find_server($db, $test_results, $dbid, $current, $total, $type, $update_config, $set_update_server);
            if ($update_config === TRUE) {
                $s = get_usenet_server($db, $srv->get_id(), FALSE);
                $this->update_server($s, $srv->get_id());
            }
            $current++;
            set_config($db, 'urdd_startup', 50 + floor(30 * ($current / $total)));
        }
    }
    public function get_priority($server_id)
    {
        $id = $server_id;
        if (isset($this->servers[$id])) {
            return $this->servers[$id]->get_priority();
        } else {
            throw new exception ("Cannot find server $id", ERR_NO_SUCH_SERVER);
        }
    }
}
