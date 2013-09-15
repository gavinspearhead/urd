<?php
/*
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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: thread.php 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$paththr = realpath(dirname(__FILE__));
require_once "$paththr/../functions/autoincludes.php";
require_once "$paththr/../functions/functions.php";

class thread
{
    private $pid;
    private $action;
    private $end_status;  // Use DOWNLOAD_ACTIVE etc

    public function __construct ($pid, action $action)
    {
        assert(is_numeric($pid));
        $this->pid = (int) $pid;
        $this->action = $action;
        $this->action->set_start_time();
        $this->end_status = DOWNLOAD_ACTIVE;
    }
    public function get_pid()
    {
        return $this->pid;
    }
    function &get_action()
    {
        return $this->action;
    }
    public function set_status($stat)
    {
        $this->end_status = $stat;
    }
    public function get_status()
    {
        return $this->end_status;
    }
} // thread

class thread_list
{
    private $threads;
    const DUMMY_THREAD = NULL;

    public function __construct ()
    {
        $this->threads = array();
    }
    public function size()
    {
        return count($this->threads);
    }
    public function add(thread $thread)
    {
        $this->threads[$thread->get_pid()] = $thread;
    }
    public function add_thread(thread $thread, $server_id)
    {
        assert(is_numeric($server_id));
        $thread->get_action()->set_active_server($server_id);
        $pid = $thread->get_pid();
        if (isset($this->threads[$pid])) {
            throw new exception ('PID already exists');
        }
        $this->threads[$pid] = $thread;
    }
    public function add_dummy_thread($pid)
    {
        assert(is_numeric($pid));
        if (!isset ($this->threads[$pid])) {
            $this->thread[$pid] = self::DUMMY_THREAD;
        } else {
            throw new exception ('PID already exists');
        }
    }
    function &get_thread($pid)
    {
        assert(is_numeric($pid));
        if (!isset($this->threads[$pid])) {
            throw new exception ("Thread not found (pid $pid)", ERR_THREAD_NOT_FOUND);
        }

        return $this->threads[$pid];
    }
    public function has_thread($pid)
    {
        assert(is_numeric($pid));

        return isset($this->threads[$pid]);
    }

    public function get_threads()
    {
        $threads = array();
        foreach ($this->threads as $t) {
            if ($t === self::DUMMY_THREAD) {
                continue;
            }
            $a = $t->get_action();
            $thread['id'] = $a->get_id();
            $thread['pid'] = $t->get_pid();
            $thread['username'] = $a->get_username();
            $thread['userid'] = $a->get_userid();
            $thread['command'] = $a->get_command();
            $thread['args'] = $a->get_args();
            $thread['paused'] = $a->is_paused();
            $thread['queue_time'] = $a->get_queue_time();
            $thread['start_time'] = $a->get_start_time();
            $thread['server'] = $a->get_active_server();
            $threads[] = $thread;
        }

        return $threads;
    }
    public function delete_thread(DatabaseConnection $db, $pid, $store=FALSE)
    {
        assert(is_numeric($pid));
        if (!isset($this->threads[$pid])) {
            throw new exception ("Thread not found (pid $pid)", ERR_THREAD_NOT_FOUND);
        }
        $thread = $this->threads[$pid];
        if ($thread == self::DUMMY_THREAD) {
            return array(NULL, 0, 0);
        }
        $dbid = $thread->get_action()->get_dbid();
        $status = NULL;
        $stat_code = $thread->get_status();

        if ($stat_code == DOWNLOAD_CANCELLED) {
            $status = QUEUE_CANCELLED;
        } elseif ($stat_code == DOWNLOAD_PAUSED) {
            $status = QUEUE_PAUSED;
        } elseif ($stat_code == DOWNLOAD_FINISHED) {
            $status = QUEUE_FINISHED;
        } elseif ($stat_code == DOWNLOAD_ERROR) {
            $status = QUEUE_FAILED;
        } elseif ($stat_code == DOWNLOAD_STOPPED) {
            $status = QUEUE_QUEUED;
        }
        if ($status !== NULL) {
            update_queue_status ($db, $dbid, $status, 0);
        }

        $item = $thread->get_action();
        $server_id = $item->get_active_server();
        $item->clear_server($store);
        unset($this->threads[$pid]);

        return array ($item, $server_id, $stat_code);
    }
    public function get_pid($action_id)
    {
        assert(is_numeric($action_id));
        foreach ($this->threads as $t) {
            if ($t === self::DUMMY_THREAD) {
                continue;
            }
            if ($t->get_action()->get_id() == $action_id) {
                return $t->get_pid();
            }
        }
        throw new exception ('Thread not found', ERR_THREAD_NOT_FOUND);
    }
    public function get_pid_cmd($cmd, $arg)
    {
        $pids = array();
        foreach ($this->threads as $t) {
            if ($t === self::DUMMY_THREAD) {
                continue;
            }
            if ($t->get_action()->is_equal($cmd, $arg)) {
                $pids[] = $t->get_pid();
            }
        }

        return $pids;
    }
    public function has_equal(action $action)
    {
        foreach ($this->threads as $t) {
            if ($t === self::DUMMY_THREAD) {
                continue;
            }
            if ($t->get_action()->is_equal($action->get_command_code(), $action->get_args())) {
                return TRUE;
            }
        }

        return FALSE;
    }
    public function get_all_pids($userid)
    {
        assert(is_numeric($userid));
        $pids = array();
        foreach ($this->threads as $t) {
            if ($t === self::DUMMY_THREAD) {
                continue;
            }
            if ($t->get_action()->match_userid($userid)) {
                $pids[] = $t->get_pid();
            }
        }

        return $pids;
    }
} // thread_list
