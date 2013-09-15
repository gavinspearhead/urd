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
 * $LastChangedDate: 2013-09-03 23:50:58 +0200 (di, 03 sep 2013) $
 * $Rev: 2911 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_connection.php 2911 2013-09-03 21:50:58Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

// connection states

class connection
{
    private $socket;
    private $last_recv; // last time we received something; use time()
    private $peer_hostname;
    private $peer_address;
    private $peer_port;
    private $buffer;
    private $username;
    private $userid;
    private $state; // 0: no u/p; 1: u; 2: u&p
    private $last_line;

    const STATE_NOT_AUTHENTICATED   = 0;
    const STATE_GOT_USERNAME        = 1;
    const STATE_AUTHENTICATED       = 2;

    private static $conn_states = array(
        self::STATE_NOT_AUTHENTICATED,
        self::STATE_GOT_USERNAME,
        self::STATE_AUTHENTICATED
    );
    public function __construct($sock)
    {
        $this->state = self::STATE_NOT_AUTHENTICATED;
        $this->username = '';
        $this->buffer = '';
        $this->last_line = '';
        $this->socket = $sock;
        $this->last_recv = time();
        if (socket_getpeername($sock, $address, $port)) {
            $this->peer_address = $address;
            $this->peer_hostname = gethostbyaddr($address);
            $this->peer_port = $port;
        }
    }

    public function check_state($state)
    {
        assert(in_array($state, self::$conn_states));

        return $state == $this->state;
    }
    public function check_username($username)
    {
        return ($this->state != self::STATE_NOT_AUTHENTICATED) && ($username == $this->username);
    }
    public function get_username()
    {
        if ($this->state != self::STATE_NOT_AUTHENTICATED) {
            return $this->username;
        } else {
            return 'anonymous';
        }
    }

    public function update()
    {
        $this->last_recv = time();
    }
    public function add_buffer($buf)
    {
        $this->buffer .= $buf;
    }
    public function set_state($state)
    {
        $this->state = $state;
    }
    public function set_username($username)
    {
        assert(is_string($username));
        $this->username = $username;
    }
    public function close()
    {
        socket_close($this->socket);
        $this->socket = NULL;
    }
    public function get_socket()
    {
        return $this->socket;
    }
    public function set_userid($uid)
    {
        assert(is_numeric($uid));
        $this->userid = $uid;
    }
    public function get_peer_address()
    {
        return $this->peer_address;
    }
    public function get_peer_hostname()
    {
        return $this->peer_hostname;
    }
    public function get_peer_port()
    {
        return $this->peer_port;
    }
    public function get_last_recv()
    {
        return $this->last_recv;
    }
    public function get_buffer_line()
    {
        $lines = preg_split("/[\n\r]+/", $this->buffer, 2);
        if (count($lines) == 2) {
            $this->buffer = $lines[1];
            if (strlen($lines[0]) == 0) {
                return FALSE;
            } else {
                return ltrim($lines[0]);
            }
        } else {
            return FALSE;
        }
    }
    public function get_last_cmd()
    {
        return $this->last_line;
    }
    public function set_last_cmd($line)
    {
        $this->last_line = $line;
    }
}

class conn_list
{
    private $conns = array();
    private $default_timeout; // in seconds

    public function __construct($timeout)
    {
        assert(is_numeric($timeout));
        $this->default_timeout = $timeout;
    }
    public function size()
    {
        return count($this->conns);
    }
    public function set_timeout($timeout)
    {
        assert(is_numeric($timeout));
        $this->default_timeout = $timeout;
    }
    public function update($sock) // update the last receive time
    {
        assert(is_resource($sock));
        foreach ($this->conns as $conn) {
            if ($conn->get_socket() === $sock) {
                $conn->update();

                return;
            }
        }
    }
    public function check_buffer($sock) // update the last receive time
    {
        assert(is_resource($sock));
        foreach ($this->conns as $conn) {
            if ($conn->get_socket() === $sock) {
                return $conn->get_buffer_line();
            }
        }

        return FALSE;
    }
    public function add_connection($sock)
    {
        assert(is_resource($sock));
        $conn = $this->conns[] = new connection($sock);

        return $conn;
    }
    public function get_fdlist() // get the list of fds for a call to select()
    {
        $fdlist = array();
        foreach ($this->conns as $conn) {
            $fdlist[] = $conn->get_socket();
        }

        return $fdlist;
    }
    public function first_timeout() // get the time that the first timeout will occur
    {
        $timeout = NULL;
        foreach ($this->conns as $conn) {
            if ($timeout === NULL || $conn->get_last_recv() < $timeout) {
                $timeout = $conn->get_last_recv();
            }
        }
        if ($timeout !== NULL) {
            $timeout = ($timeout + $this->default_timeout) - time();
            if ($timeout < 0) {
                $timeout = 0;
            }
        }

        return $timeout;
    }
    public function close_all()
    {
        foreach ($this->conns as $conn) {
            socket_close($conn->get_socket());
        }
    }
    public function close($sock) // close a socket and remove from list
    {
        assert(is_resource($sock));
        $k = NULL;
        foreach ($this->conns as $akey => $conn) {
            if ($conn->get_socket() === $sock) {
                socket_close($sock);
                $k = $akey;
                break;
            }
        }

        if ($k === NULL) {
            throw new exception ('Error: cannot close connection; not found', ERR_SOCKET_FAILURE);
        }
        unset($this->conns[$k]);
    }
    public function close_timedout()  // close all timed out connection;
    {
        $keys = array();
        $timestamp = time();
        foreach ($this->conns as $key => $conn) {
            if ($conn->get_last_recv() + $this->default_timeout < $timestamp) {
                socket_close($conn->get_socket());
                $keys[] = $key;
            }
        }
        foreach ($keys as $key) {
            unset($this->conns[$key]);
        }
    }
    public function add_buffer($sock, $line)
    {
        assert(is_resource($sock));
        foreach ($this->conns as $conn) {
            if ($conn->get_socket() === $sock) {
                $conn->add_buffer($line);

                return $conn;
            }
        }

        return NULL;
    }
    function &get_conn($sock)
    {
        assert(is_resource($sock));
        foreach ($this->conns as &$conn) {
            if ($conn->get_socket() === $sock) {
                return $conn;
            }
        }

        return NULL;
    }
    public function add_username($sock, $username)
    {
        assert(is_resource($sock));
        if (trim($username) == '') {
            return FALSE;
        }
        foreach ($this->conns as &$conn) {
            if ($conn->get_socket() === $sock) {
                $conn->set_username($username);
                $conn->set_state(connection::STATE_GOT_USERNAME);

                return TRUE;
            }
        }

        return FALSE;
    }
    public function verify_password_db(DatabaseConnection $db, $sock, $password)
    {
        assert(is_resource($sock));

        foreach ($this->conns as &$conn) {
            if ($conn->get_socket() === $sock) {
                $username = $conn->get_username();
                try {
                    $salt = get_salt($db, $username);
                } catch (exception $e) {
                    $conn->set_state(connection::STATE_NOT_AUTHENTICATED);
                    $conn->set_username('');

                    return FALSE;
                }
                $db->escape($username, TRUE);

                if (strlen($password) > 4 && substr_compare($password, 'hash:', 0, 4) == 0) {
                    $hash_pw = substr($password, 5);
                } else {
                    $hash_pw = hash('sha256', $salt . $password . $salt);
                }
                $db->escape($hash_pw, TRUE);
                $res = $db->select_query("\"ID\" FROM users WHERE \"name\"=$username AND \"pass\"=$hash_pw AND \"active\" = '" . user_status::USER_ACTIVE . "'", 1);
                if ($conn->check_state(connection::STATE_GOT_USERNAME) && $res !== FALSE) {
                    $conn->set_userid($res[0]['ID']);
                    $conn->set_state(connection::STATE_AUTHENTICATED);

                    return TRUE;
                } else {
                    $conn->set_state(connection::STATE_NOT_AUTHENTICATED);
                    $conn->set_username('');

                    return FALSE;
                }
            }
        }

        return FALSE;
    }
    public function check_authorised($sock)
    {
        foreach ($this->conns as $conn) {
            if ($conn->get_socket() === $sock) {
                return ($conn->check_state(connection::STATE_AUTHENTICATED));
            }
        }

        return FALSE;
    }
    public function get_username($sock)
    {
        foreach ($this->conns as $conn) {
            if ($sock == $conn->get_socket()) {
                return $conn->get_username();
            }
        }

        return FALSE;
    }
    public function get_usernames()
    {
        $users = array();
        foreach ($this->conns as $conn) {
            $users[] = $conn->get_username();
        }

        return $users;
    }
}
