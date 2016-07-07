<?php
/*
 *  This file is part of Urd.
 *
 *  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2014-06-07 14:53:28 +0200 (za, 07 jun 2014) $
 * $Rev: 3081 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_client.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathuc = realpath(dirname(__FILE__));

require_once $pathuc . '/urdd_command.php';
require_once $pathuc . '/../functions/libs/socket.php';


class urdd_client
{
    private $connected;
    private $hostname;
    private $port;
    private $sock;
    private $username;
    private $password;
    private $timeout;
    private $can_connect;

    private function get_username_password(DatabaseConnection $db, $userid)
    {
        assert(is_numeric($userid));
        $res = $db->select_query('"name", "pass" FROM users WHERE "ID"=:userid', 1, array(':userid'=>$userid));
        if (!isset($res[0]['name'])) {
            throw new exception("Invalid userID $userid given in urdd_client constructor", ERR_INVALID_USERNAME);
        }

        return array($res[0]['name'], $res[0]['pass']);
    }

    public function __construct(DatabaseConnection $db, $hostname, $port, $userid=0, $timeout=socket::DEFAULT_SOCKET_TIMEOUT)
    {
        echo_debug ("$hostname $port $userid", DEBUG_HTTP);
        assert(is_numeric($port) && is_numeric($userid) && is_numeric($timeout));
        if ($userid > 0) {
            list($username, $md5pass) = $this->get_username_password($db, $userid);
            $password = 'hash:' . $md5pass;

            $this->timeout = (int) $timeout;
            $this->cleanup();
            try {
                $this->connect($hostname, $port, $username, $password, $timeout);
                $this->connected = TRUE;
            } catch (exception $e) {
                $this->cleanup();
            }
        } else {
            $this->can_connect = FALSE;
            $this->quick_connect($hostname, $port, $timeout);
        }
    }
    private function quick_connect($hostname, $port, $timeout)
    {
        assert(is_numeric($port) && is_numeric($timeout));
        $rv = FALSE;
        try {
            $this->sock = new socket();
            $this->sock->connect($hostname, $port, FALSE, $timeout);
            $rv = $this->sock->read_line();
        } catch (exception $e) {
            $this->cleanup();
        }
        if (substr($rv, 0, 3) == '299') {
            $this->can_connect = TRUE;
        }
        $this->disconnect();
        $this->cleanup();
    }
    public function can_connect()
    {
        return $this->can_connect;
    }
    private function cleanup()
    {
        $this->sock = NULL;
        $this->connected = FALSE;
    }
    public function __destruct ()
    {
        $this->disconnect();
    }
    public function is_connected()
    {
        return $this->connected;
    }
    public function connect($hostname, $port=URDD_PORT, $username='', $password='', $timeout=socket::DEFAULT_SOCKET_TIMEOUT)
    {
        assert(is_numeric($port) && is_numeric($timeout));
        $this->hostname = $hostname;
        $this->port = (int) $port;
        $this->username = $username;
        $this->password = $password;
        $this->timeout = (int) $timeout;
        $this->sock = new socket();
        $this->sock->connect($hostname, $port, FALSE, $timeout);
        $rv = $this->sock->read_line();
        if ($rv === FALSE) {
            $this->cleanup();
            throw new exception ('Cannot read from socket', ERR_SOCKET_FAILURE);
        }
        $this->connected = TRUE;
        if ($this->username !== '' && $this->password !== '') {
            list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_USER) . ' ' . $this->username);
            if ($code != 331) {
                $this->sock->disconnect();
                $this->cleanup();
                write_log('Wrong username and password', LOG_ERR);
                throw new exception('331 Wrong username and password', ERR_ACCESS_DENIED);
            }
            list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_PASS) . ' ' . $this->password);
            if ($code != 240) {
                $this->sock->disconnect();
                $this->cleanup();
                write_log('Wrong username and password', LOG_ERR);
                throw new exception('240 Wrong username and password ' . $code, ERR_ACCESS_DENIED);
            }
        } else {
            throw new exception ('Needs a username and password', ERR_NOT_LOGGED_IN);
        }
        $this->connected = TRUE;
    }

    public function disconnect()
    {
        if ($this->sock !== NULL && $this->connected == TRUE && is_resource($this->sock)) {
            $sb = array($this->sock);
            $null_array = array();
            $rv = @socket_select($null_array, $sb, $null_array, 0); // check if socket is writable and will not block
            if ($rv > 0) { // if so we will send a quit command
                $this->send_multi_command('quit');
            }
            // otherwise we will just close the connection
            @socket_close($this->sock);
        }
        $this->cleanup();
    }
    protected function split_response($lines)
    {
        $code = (int) (substr($lines[0], 0, 3));
        if ($code > 999 || $code < 100) {
            throw new exception("Incorrect response $code", ERR_INVALID_RESPONSE);
        }
        $text = substr($lines[0], 4);
        if (($code % 100) > 50) {
            $data = array_slice($lines, 1);
        } else {
            $data = NULL;
        }

        return array($code, $text, $data);
    }
    protected function send_multi_command($cmd)
    {
        // Check if we have a connection:
        if ($this->sock === NULL) {
            $this->connect($this->hostname, $this->port, $this->username, $this->password, $this->timeout);
        }
        // Send command:
        $rv = $this->sock->write_line($cmd);
        if ($rv === FALSE) { // can't write it somehow, cleanup and quit
            $this->sock->disconnect();
            $this->cleanup();
            throw new exception('Could not write to socket', ERR_SOCKET_FAILURE);
        }
        $timeout = time() + 10;
        // Read reply:
        while (1) {
            // Read the result
            $line = $this->sock->read_line();
            if ($line === FALSE || time() > $timeout) { // if we have a timeout or readline timesout
                $this->disconnect();
                $this->cleanup();
                throw new exception('Waited too long', ERR_WAITED_TOO_LONG);
            }

            $bufferlines[] = $line;

            if (preg_match('/^([0-9])([0-9])([0-9]) (.+)$/', $bufferlines[0], $res)) {
                // Only if the second value is 5 or more, there is a multi-line response
                if ($res[2] < 5) {
                    break;
                }

                if ($bufferlines[count($bufferlines)-1] == '.') {
                    unset ($bufferlines[count($bufferlines)-1]); // remove the line with the dot
                    break;
                }
            }
        }

        list ($code, $resp, $data) = $this->split_response($bufferlines);

        return array ($code, $resp, $data);
    }
    public function decode($string)
    {
        // $string can be "(6) [1] [2] [3] Download scheduled.", returning the right values:
        $return = array();

        if (preg_match('/^\(([0-9]+)\)/', $string, $res)) {
            $return[] = $res[1];
        } else {
            $return[] = array();
        }
        if (preg_match_all('|\[([^[]+)\]|U', $string, $res)) {
            $return[] = $res[1];
        } else {
            $return[] = array();
        }

        return $return;
    }
    public function gensets($msg_id)
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_GENSETS) . ' ' . $msg_id);

        return ($code == 200) ? TRUE : FALSE;
    }
    public function update($msg_id='', $type=USERSETTYPE_GROUP)
    {
        switch ($type) {
        case USERSETTYPE_GROUP: $cmd = urdd_protocol::COMMAND_UPDATE; break;
        case USERSETTYPE_RSS: $cmd = urdd_protocol::COMMAND_UPDATE_RSS; break;
        case USERSETTYPE_SPOT: $cmd = urdd_protocol::COMMAND_GETSPOTS; break;
        default: return FALSE;
        }

        list ($code, $resp, $data) = $this->send_multi_command(get_command($cmd) . ' ' . $msg_id);

        return ($code == 200) ? TRUE : FALSE;
    }

     function create_preview()
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_DOWNLOAD) . ' preview');

        return ($code == 210) ? $resp : FALSE;
    }
    public function update_blacklist()
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_GETBLACKLIST));

        return ($code == 210) ? $resp : FALSE;
    }
    public function update_spotscomments()
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_GETSPOT_COMMENTS));

        return ($code == 210) ? $resp : FALSE;
    }
    public function update_spotsimages()
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_GETSPOT_IMAGES));

        return ($code == 210) ? $resp : FALSE;
    }

    public function update_whitelist()
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_GETWHITELIST));

        return ($code == 210) ? $resp : FALSE;
    }
    public function create_download()
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_DOWNLOAD));

        return ($code == 210) ? $resp : FALSE;
    }
    public function make_nzb()
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_MAKE_NZB));

        return ($code == 210) ? $resp : FALSE;
    }
    public function cancel($msg_id)
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_CANCEL) . ' ' . $msg_id);
        return ($code == 200) ? TRUE : FALSE;
    }
    public function continue_cmd($msg_id)
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_CONTINUE) . ' ' . $msg_id);

        return ($code == 200) ? TRUE : FALSE;
    }
    public function pause($msg_id)
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_PAUSE) . ' ' . $msg_id);

        return ($code == 200) ? TRUE : FALSE;
    }
    public function cleandb($arg='')
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_CLEANDB). ' ' . $arg);

        return ($code == 200) ? TRUE : FALSE;
    }
    public function expire($msg_id = '', $type=USERSETTYPE_GROUP)
    {
        switch ($type) {
        case USERSETTYPE_GROUP: $cmd = urdd_protocol::COMMAND_EXPIRE; break;
        case USERSETTYPE_RSS: $cmd = urdd_protocol::COMMAND_EXPIRE_RSS; break;
        case USERSETTYPE_SPOT: $cmd = urdd_protocol::COMMAND_EXPIRE_SPOTS; break;
        default: return FALSE;
        }
        list($code, $resp, $data) = $this->send_multi_command(get_command($cmd) . ' ' . $msg_id);

        return ($code == 201 || $code == 202) ? TRUE : FALSE;
    }

    public function stop($msg_id)
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_STOP) .' ' . $msg_id);

        return ($code == 200) ? TRUE : FALSE;
    }
    public function purge($msg_id, $type=USERSETTYPE_GROUP)
    {
        switch ($type) {
        case USERSETTYPE_GROUP: $cmd = urdd_protocol::COMMAND_PURGE; break;
        case USERSETTYPE_RSS: $cmd = urdd_protocol::COMMAND_PURGE_RSS; break;
        case USERSETTYPE_SPOT: $cmd = urdd_protocol::COMMAND_PURGE_SPOTS; break;
        default: return FALSE;
        }

        list($code, $resp, $data) = $this->send_multi_command(get_command($cmd) . ' ' . $msg_id);

        return ($code == 201 || $code == 202) ? TRUE : FALSE;
    }

    public function preempt($msg_start, $msg_stop = '')
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_PREEMPT) .' ' . $msg_start . ' ' . (($msg_stop !== NULL)? $msg_stop : ''));

        return ($code == 200) ? TRUE : FALSE;
    }
    public function unschedule($cmd, $arg)
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command (urdd_protocol::COMMAND_UNSCHEDULE) . " $cmd $arg");

        return ($code == 201 || $code == 202) ? TRUE : FALSE;
    }
    public function schedule($cmd, $arg, $timestamp, $recurrence=NULL)
    {
        $rec_str = '';
        if ($recurrence !== NULL) {
            $rec_str = " # $recurrence ";
        }
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_SCHEDULE) . " $cmd $arg @ \"$timestamp\" $rec_str");

        return ($code == 201 || $code == 202) ? TRUE : FALSE;
    }
    public function show($var = 'threads', $output_type='text')
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_SHOW) . " output:$output_type $var");

        return ($code == 253) ? $data : FALSE;
    }
    public function diskfree($format = 'b')
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_DISKFREE) . " $format");
        if ($code == 256) {
            $res = explode(' ', $data[0]);

            return array($res[0], $res[1], $res[3], $res[4], $res[6], $res[7]);
        } elseif ($code == 263) {
            $res = explode(' ', $data[0]);

            return $res[0];
        } else {
            return FALSE;
        }
    }
    public function time($output_type='text')
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_SHOW) . ' time ' . "output:$output_type");

        return ($code == 253) ? $data[0] : FALSE;
    }
    public function uptime($output_type='text')
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_SHOW) . ' uptime ' . "output:$output_type");

        return ($code == 253) ? $data[0] : FALSE;
    }
    public function update_newsgroups()
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_GROUPS));

        return ($code == 200) ? TRUE : FALSE;
    }
    public function group($groupid)
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_GROUP) . " $groupid");

        return ($code == 258) ? $data : FALSE;
    }
    public function subscribe($groupid, $expire, $minsetsize, $maxsetsize, $adult=ADULT_OFF)
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_SUBSCRIBE) . " $groupid on $expire $minsetsize $maxsetsize $adult");

        return ($code == 201) ? $data : FALSE;
    }
    public function subscribe_rss($feed_id, $expire)
    {
        list ($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_SUBSCRIBE_RSS) . " $feed_id on $expire");

        return ($code == 201) ? $data : FALSE;
    }
    public function unsubscribe($id, $type= USERSETTYPE_GROUP)
    {
        switch ($type) {
            case USERSETTYPE_GROUP: $cmd = urdd_protocol::COMMAND_SUBSCRIBE; break;
            case USERSETTYPE_RSS:   $cmd = urdd_protocol::COMMAND_SUBSCRIBE_RSS; break;
            case USERSETTYPE_SPOT:  return FALSE;
            default: return FALSE;
        }

        list ($code, $resp, $data) = $this->send_multi_command(get_command($cmd) . " $id off");

        return ($code == 201) ? $data : FALSE;
    }
    public function optimise()
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_OPTIMISE));

        return ($code == 231) ? TRUE : FALSE;
    }
    public function noop()
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_NOOP));

        return ($code == 231) ? TRUE : FALSE;
    }
    public function echo_cmd($msg)
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_ECHO) . " $msg");

        return ($code == 200) ? TRUE : FALSE;
    }
    public function version($output_type='text')
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_SHOW) . " version output:$output_type");

        return ($code == 253) ? $data : FALSE;
    }

    public function unpause($id)
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_CONTINUE) . " $id");

        return ($code == 200) ? TRUE : FALSE;
    }
    public function cleandir($dir)
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_CLEANDIR) . " $dir");

        return ($code == 200) ? TRUE : FALSE;
    }
    public function shutdown()
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_SHUTDOWN));
        $this->disconnect();

        return ($code == 222) ? TRUE : FALSE;
    }
    public function set($parameter, $value1, $value2='', $value3='')
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_SET) . " $parameter $value1 $value2 $value3");

        return ($code == 200) ? TRUE : FALSE;
    }
    public function get_imdb_watchlist()
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_GET_IMDB_WATCHLIST));

        return ($code == 201) ? TRUE : FALSE;
    }
    public function check_version()
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_CHECK_VERSION));

        return ($code == 201) ? TRUE : FALSE;
    }
    public function sendsetinfo()
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_SENDSETINFO));

        return ($code == 201) ? TRUE : FALSE;
    }

    public function getsetinfo()
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_GETSETINFO));

        return ($code == 201) ? TRUE : FALSE;
    }
    public function move_cmd($direction, $command, $id)
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_MOVE) . " $direction $command $id");

        return ($code == 200) ? TRUE : FALSE;
    }
    public function add_spot_data($dlid, $spotid)
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_ADDSPOTDATA) . " $dlid $spotid");

        return ($code == 200) ? TRUE : FALSE;
    }
    public function add_set_data($dlid, $setid, $preview=FALSE)
    {
        $preview_str = ($preview === TRUE) ? 'preview' : '';
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_ADDDATA) . " $dlid set $setid $preview_str");

        return ($code == 200) ? TRUE : FALSE;
    }
    public function add_bin_data($dlid, $groupid, $binid, $preview=FALSE)
    {
        $preview_str = ($preview === TRUE) ? 'preview' : '';
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_ADDDATA) . " $dlid binary $groupid $binid $preview_str");

        return ($code == 200) ? TRUE : FALSE;
    }
    public function parse_nzb($url, $dlid='', $start_time=NULL)
    {
        $url = addslashes($url);
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_PARSE_NZB) . " $dlid '$url' $start_time");

        return ($code == 201) ? TRUE : FALSE;
    }
    public function merge_sets($setid1, array $setid_list)
    {
        $setid2 = '';
        foreach ($setid_list as $s) {
            $setid2 .= $s . ' ';
        }
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_MERGE_SETS) . " {$setid1} {$setid2}");

        return ($code == 201) ? TRUE : FALSE;
    }
   public function restart_urdd()
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_RESTART));

        return ($code == 223) ? TRUE : FALSE;
    }
    public function findservers($options= '')
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_FINDSERVERS) . " $options");

        return ($code == 200) ? TRUE : FALSE;
    }
    public function delete_set(array $setid, $type= USERSETTYPE_GROUP)
    {
        $setid_list = '';
        if ($setid == array()) {
            return FALSE;
        }
        $cnt = 0;
        $cmd = FALSE;
        switch ($type) {
        case USERSETTYPE_GROUP: $cmd = urdd_protocol::COMMAND_DELETE_SET; break;
        case USERSETTYPE_RSS:   $cmd = urdd_protocol::COMMAND_DELETE_SET_RSS; break;
        case USERSETTYPE_SPOT:  $cmd = urdd_protocol::COMMAND_DELETE_SPOT; break;
        default: return FALSE;
        }
        $cmd_id = get_command($cmd);
        foreach ($setid as $s) {
            $setid_list .= " $s";
            $cnt++;
            if ($cnt > 10) {
                list($code, $resp, $data) = $this->send_multi_command($cmd_id . " $setid_list");
                $cnt = 0;
                $setid_list = '';
            }
        }
        if ($cnt > 0) {
            list($code, $resp, $data) = $this->send_multi_command($cmd_id . " $setid_list");
        }

        return ($code == 200) ? TRUE : FALSE;
    }

    public function unpar_unrar($dlid='')
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_UNPAR_UNRAR) . " $dlid");

        return ($code == 200) ? TRUE : FALSE;
    }
    public function post($id)
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_POST) . " $id");

        return ($code == 201) ? TRUE : FALSE;
    }
    public function post_message($id)
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_POST_MESSAGE) . " $id");

        return ($code == 201) ? TRUE : FALSE;
    }
     public function post_spot($id)
    {
        list($code, $resp, $data) = $this->send_multi_command(get_command(urdd_protocol::COMMAND_POST_SPOT) . " $id");

        return ($code == 201) ? TRUE : FALSE;
    }

}
