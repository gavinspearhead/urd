<?php
/**
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
 * $LastChangedDate: 2013-09-01 16:37:15 +0200 (zo, 01 sep 2013) $
 * $Rev: 2907 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: action.php 2907 2013-09-01 14:37:15Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathact = realpath(dirname(__FILE__));
require_once "$pathact/../functions/autoincludes.php";

class action
{
    private static $lastid = 0;
    private $command; // the string representing the command
    private $command_code; // the code of the command
    private $args; // the arguments passed as one string
    private $counter; // number of times it has been queued
    private $db_intensive; // is flagged as heavy on the db
    private $needs_nntp; // the command needs an nntp connection
    private $needs_posting; // the command needs the posting capability of the server
    private $primary_nntp;
    private $id; // the internal id of the action, should be incrementing
    private $paused; // is the thread paused or active
    private $username; // the user that created the action
    private $userid; // the user id of the user that created the action
    private $queue_time; // the time the action was put on the queue
    private $start_time; // the time the action started running
    private $db_id; // the id in the db that matches with this item, if on the queue
    private $dlpath; // the path where downloads are put
    private $priority; // the priority of the action, used to determine which action to pop off the queue and start running
    private $preview; // is this a preview or (a regular download or other task) - previews get priority over other tasks
    private $active_server; // if it is set, we either will try to run it only on this server, or it already runs on this one
    private $preferred_server; // if this is 0 we try any active server, otherwise run it _only_ on the server given here; note if it is not active on starting this item, we need to re-schedule it with a new preferred server
    private $temp_fail_servers; // servers we have already tried to run this task on, but failed to connect, or timed out or other temporary failures
    private $perm_fail_servers; // servers that don't have any more articles for us
    public function __construct ($cmd, $args, $username, $userid, $paused=FALSE, $priority=DEFAULT_PRIORITY)
    {
        assert(is_numeric($priority) && $priority >= 0 && is_bool($paused) &&  ((is_numeric($userid)  && $username != '') || ($cmd === NULL && $args === NULL && $username === NULL && $userid === NULL)));
        if ($cmd === NULL) {
            $this->command_code = NULL;
            $this->command = NULL;
        } elseif (is_numeric($cmd)) { // we got the code , need to find the string
            $this->command_code = $cmd;
            $this->command = get_command($cmd);
            if ($this->command === FALSE) {
                throw new exception_invalid_command ('Unknown command supplied');
            }
        } else { // we got the string, need to find the code
            $this->command = $cmd;
            $this->command_code = get_command_code($cmd);
            if ($this->command_code === FALSE) {
                throw new exception_invalid_command('Unknown command supplied');
            }
        }
        $this->args = $args;
        $this->needs_nntp = get_command_nntp($cmd);
        $this->needs_posting = get_command_posting($cmd);
        $this->primary_nntp = get_command_primary_nntp($cmd);
        $this->id = ++self::$lastid;
        $this->paused = (bool) $paused;
        $this->username = $username;
        $this->userid = $userid;
        $this->queue_time = microtime(TRUE);
        $this->start_time = (int) 0;
        $this->counter = (int) 0;
        $this->db_id = (int) 0;
        $this->priority = (int) $priority;
        $this->preview = FALSE;
        $this->active_server = (int) 0;
        $this->preferred_server = (int) 0;
        $this->temp_fail_servers = $this->perm_fail_servers = array();
        $this->db_intensive = (bool) get_command_db_intensive($cmd);
    }
    public function set_command($cmd)
    {
        if (is_numeric($cmd)) {
            $this->command_code = $cmd;
            $this->command = get_command($cmd);
            if ($this->command === FALSE) {
                throw new exception_invalid_command ('Unknown command supplied');
            }
        } else { // we got the string, need to find the code
            assert($cmd != '');
            $this->command = $cmd;
            $this->command_code = get_command_code($cmd);
            if ($this->command_code === FALSE) {
                throw new exception_invalid_command ('Unknown command supplied');
            }
        }
    }
    public function copy(action $action)
    {
        $this->command = $action->get_command();
        $this->command_code = $action->get_command_code();
        $this->args = $action->get_args();
        $this->needs_nntp = $action->need_nntp();
        $this->primary_nntp = $action->primary_nntp();
        $this->counter = (int) 0;
        $this->start_time = (int) 0;
        $this->db_id = $action->get_dbid();
        $this->id = ++self::$lastid;
        $this->paused = FALSE;
        $this->username = $action->get_username();
        $this->userid = $action->get_userid();
        $this->queue_time = microtime(TRUE);
        $this->priority = $action->get_priority();
        $this->preview = $action->get_preview();
        $this->active_server = (int) 0;
        $this->temp_fail_servers = $action->get_tried_servers();
        $this->perm_fail_servers = $action->get_failed_servers();
        $this->db_intensive = $action->db_intensive();
        if (!in_array($action->get_preferred_server(), $this->perm_fail_servers) && !in_array($action->get_preferred_server(), $this->temp_fail_servers)) {
            $this->preferred_server = (int) $action->get_preferred_server();
        } else {
            $this->preferred_server = (int) 0;
        }
    }
    public function pause($pause, $userid)  // pause == true -> set pause, false->  continue
    {
        assert(is_bool($pause));
        if (!$this->match_userid($userid)) {
            throw new exception('Not allowed', ERR_ACCESS_DENIED);
        }
        if ($pause === TRUE) {
            $this->paused = TRUE;
            $status = QUEUE_PAUSED;
        } else {
            $this->paused = FALSE;
            $status = QUEUE_QUEUED;
        }

        return TRUE;
    }
    public function is_paused()
    {
        return ($this->paused === TRUE);
    }
    public function is_equal($cmd, $args)
    {
        if (is_numeric($cmd)) {
            $cmd_eq = ($cmd == $this->command_code);
        } else {
            $cmd_eq = strcasecmp($cmd, $this->command) == 0;
        }

        return ($cmd_eq) && ($args == $this->args);
    }
    public function get_command()
    {
        return $this->command;
    }
    public function get_command_code()
    {
        return $this->command_code;
    }
    public function get_args()
    {
        return $this->args;
    }
    public function get_arg($id)
    {
        assert(is_numeric($id));
        if (isset($this->args[$id])) {
            return $this->args[$id];
        } else {
            return '';
        }
    }
    public function get_id()
    {
        return $this->id;
    }
    public function get_userid()
    {
        return $this->userid;
    }
    public function get_username()
    {
        return $this->username;
    }
    public function max_exceeded($count)
    {
        assert(is_numeric($count));

        return $this->counter > $count;
    }
    public function get_counter()
    {
        return $this->counter;
    }
    public function need_db()
    {
        return $this->needs_db;
    }
    public function need_posting()
    {
        return $this->needs_posting;
    }
    public function need_nntp()
    {
        return $this->needs_nntp;
    }
    public function set_need_nntp($nntp)
    {
        $this->needs_nntp = $nntp;
    }
    public function primary_nntp()
    {
        return $this->primary_nntp;
    }
    public function inc_counter()
    {
        $this->counter++;
    }
    public function db_intensive()
    {
        return $this->db_intensive;
    }
    public function set_start_time()
    {
        $this->start_time = microtime(TRUE);
    }
    public function get_start_time()
    {
        return $this->start_time;
    }
    public function get_queue_time()
    {
        return $this->queue_time;
    }
    public function get_dbid()
    {
        return $this->db_id;
    }
    public function set_dbid($id)
    {
        assert(is_numeric($id));
        $this->db_id = $id;
    }
    public function set_dlpath($path)
    {
        $this->dlpath = $path;
    }
    public function get_dlpath()
    {
        return $this->dlpath;
    }
    public function get_priority()
    {
        return $this->priority;
    }
    public function set_active_server($id)
    {
        assert(is_numeric($id));
        $this->active_server = $id;
    }
    public function get_active_server()
    {
        return $this->active_server;
    }
    public function set_preferred_server($id)
    {
        if (!is_numeric($id)) {
            throw new exception ('Server id must be a number', ERR_NO_SUCH_SERVER);
        }
        $this->preferred_server = $id;
    }
    public function get_preferred_server()
    {
        return $this->preferred_server;
    }
    public function get_tried_servers()
    {
        return $this->temp_fail_servers;
    }
    public function get_failed_servers()
    {
        return $this->perm_fail_servers;
    }
    public function reset_tried_servers()
    {
        $this->temp_fail_servers = array();
    }
    public function clear_tried_servers(array $not_those_servers)
    {
        $srvr = $this->temp_fail_servers;
        $this->temp_fail_servers = array();
        foreach ($srvr as $s) {
            if (!in_array($s, $not_those_servers)) {
                $this->temp_fail_servers[$s] = $s;
            }
        }
    }
    public function remove_tried_server($id)
    {
        assert(is_numeric($id));
        unset($this->temp_fail_servers[$id]);
    }

    public function add_tried_servers($id)
    {
        assert(is_numeric($id));
        $this->temp_fail_servers[$id] = $id;
    }
    public function add_failed_servers($id)
    {
        assert(is_numeric($id));
        $this->perm_fail_servers[$id] = $id;
    }
    public function get_all_failed_servers()
    {
        return array_merge($this->temp_fail_servers, $this->perm_fail_servers);
    }
    public function clear_server($store=FALSE)
    {
        assert (is_bool($store));
        if ($store === TRUE && $this->active_server != 0) {
            $this->temp_fail_servers[$this->active_server] = $this->active_server;
        }
        $this->active_server = 0;
    }
    public function set_priority($priority, $userid)
    {
        assert(is_numeric($priority) && $priority > 0 && is_numeric($userid));
        if (!$this->match_userid($userid)) {
            throw new exception ('Not allowed', ERR_ACCESS_DENIED);
        }
        $this->priority = $priority;

        return TRUE;
    }
    public function set_preview($p)
    {
        assert(is_bool($p));
        $this->preview = (bool) $p;
    }
    public function get_preview ()
    {
        return $this->preview;
    }
    public function match_username($username)
    {
        assert(is_string($userid));
        return ($username == user_status::SUPER_USER || $this->username == $username);
    }
    public function match_userid($userid)
    {
        assert(is_numeric($userid));
        return ($userid == user_status::SUPER_USERID || $this->userid == $userid);
    }
} // action
