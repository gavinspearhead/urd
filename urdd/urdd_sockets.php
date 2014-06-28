<?php
/**
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
 * $LastChangedDate: 2011-05-15 23:15:22 +0200 (Sun, 15 May 2011) $
 * $Rev: 2158 $
 * $Author: gavinspearhead $
 * $Id: urdd.php 2158 2011-05-15 21:15:22Z gavinspearhead $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class urdd_sockets
{
// time out for checking the queue
    const DEFAULT_CHECK_TIMEOUT = 5000000;
//maximum buffer size for socket_read
    const MAX_BUFF_SIZE = 1024;

    private $listen_sock;
    public function __construct ()
    {
        $this->listen_sock = array();
    }

    public function read_sockets(DatabaseConnection $db, array $sq, conn_list &$conn_list, server_data &$servers)
    {
        //  echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        foreach ($sq as $s) {
            if (in_array($s, $this->listen_sock)) {
                // this one we can accept on
                $conn = socket_accept($s);
                if ($conn === FALSE) {
                    socket_error_handler();
                } else {
                    $c = $conn_list->add_connection($conn);
                    write_log("Incoming connection from host {$c->get_peer_hostname()}:{$c->get_peer_port()}", LOG_DEBUG);
                    $msg = sprintf(urdd_protocol::get_response(299), urd_version::get_version());
                    $res = socket_write($conn, $msg);
                    if ($res === FALSE) { // some error occured, close the connection
                        socket_error_handler();
                        $conn_list->close($conn);
                        continue;
                    }
                }
            } else { // established connections
                $conn = & $conn_list->get_conn($s);
                disable_log();
                $line = @socket_read($s, self::MAX_BUFF_SIZE, PHP_BINARY_READ);
                enable_log();
                if (socket_last_error($s) == 104 || $line === FALSE || $line === '') {
                    // EOF or connection closed by peer
                    socket_error_handler();
                    $conn_list->close($s);
                    continue;
                }
                $conn->add_buffer($line);
                $conn->update();
                while (TRUE) {
                    $line = $conn->get_buffer_line();
                    if ($line === FALSE) { // no full line read
                        break;
                    }
                    // ok we found a command... do something with it
                    echo_debug('read line: [' . preg_replace ('/pass .*/i', 'PASS XXX', preg_replace("/[\n\r]/", '', $line)) . ']', DEBUG_MAIN); // filter out passwords
                    $response = '';
                    $cmd = do_command($db, $line, $response, $conn_list, $s, $servers, NULL, NULL, FALSE);
                    if ($cmd == URDD_NOCOMMAND) {
                        continue; // no command found, empty line
                    }
                    $res = @socket_write($s, $response);
                    if ($res === FALSE) { // some error occured, close the connection
                        socket_error_handler();
                        $conn_list->close($s);
                        break;
                    }
                    if ($cmd == URDD_SHUTDOWN) {// shutdown requested
                        shutdown_urdd($db, $servers);
                    } elseif ($cmd == URDD_RESTART) {
                        restart_urdd($db, $servers);
                    } elseif ($cmd == URDD_CLOSE_CONN) { // quit received
                        $conn_list->close($s);
                    }
                    if ($cmd != URDD_ERROR) {
                        $conn->set_last_cmd($line);
                    }
                }
            }
        }
    }

    private function get_listen_socket($address, $port, $ipv6)
    {
        $af = $ipv6 ? AF_INET6 : AF_INET;
        $sock = @socket_create($af, SOCK_STREAM, SOL_TCP);
        if ($sock !== FALSE) {
            socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);
            $rv = @socket_bind($sock, $address, $port);
            if ($rv === FALSE) {
                socket_error_handler(FALSE);

                return FALSE;
            } else {
                $rv = socket_listen($sock);
                if ($rv === FALSE) {
                    socket_error_handler(FALSE);
                } else {
                    return $sock;
                }
            }
        } else {
            return FALSE;
        }
    }

    public function listen_socket($address, $address6, $port)
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        assert (is_numeric($port));
        $sockets = array();
        if ($address != '') { // try and create an IPv4 socket first
            echo_debug("Listening on $address:$port", DEBUG_MAIN);
            $sock = $this->get_listen_socket($address, $port, FALSE);
            if ($sock !== FALSE) {
                $sockets[] = $sock;
            }
        }
        if ($address6 != '' /*&& defined(AF_INET6)*/) {// now try to create an IPv6 socket as well.
            echo_debug("Listening on $address:$port", DEBUG_MAIN);
            $sock = $this->get_listen_socket($address6, $port, TRUE);
            if ($sock !== FALSE) {
                $sockets[] = $sock;
            }
        }
        if (count($sockets) > 0) {
            $this->listen_sock = $sockets;
        } else {
            write_log('No listening sockets could be established', LOG_ERR);
            urdd_exit(SOCKET_FAILURE);
        }
    }
    public function select($timeout, $timeout_us, array &$connections)
    {
        $connections = array_merge($this->listen_sock, $connections);
        socket_clear_error(); // clear it just in case
        disable_log(); // needed so the select error doesn't show... bad solution, I know but it confuses users
        $res = @socket_select($connections, $null_array, $null_array, $timeout, $timeout_us); // we always give the timeout in microseconds
        enable_log();

        return $res;
    }
}
