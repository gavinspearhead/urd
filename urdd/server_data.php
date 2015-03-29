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
 * $LastChangedDate: 2014-05-29 01:03:02 +0200 (do, 29 mei 2014) $
 * $Rev: 3058 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: server_data.php 3058 2014-05-28 23:03:02Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class server_data { // lots of cleaning up to do
    private $nntp_enabled; // bool nntp is allowed or not
    private $servers; // these are all the news servers that we know of, each has its own thread_list
    private $schedule; // here we store all the scheduled jobs
    private $queue; // this is were all the tasks are put before they can run
    private $threads; // this is for non-nntp threads; other threads run in the usenet_servers' threads
    private $max_total_nntp_threads; // total number of threads that can run on all newsgroups together
    private $free_nntp_slots; // available threads for treads on newgroups
    private $max_total_threads; // total number of threads that can run all together (always at least one more than the nntp threads, so we can do unrar and stuff
    private $free_total_slots; // total number of threads that are available
    private $conn_check_time; // time after which connections must be reset;
    private $max_db_intensive_threads; // max number of threads with db intensive set that can run
    private $free_db_intensive_slots; // available number of threads with db intensive set that can run
    private $check_nntp_connections; // whether to check the max # nntp connenctions a server can have
    private $kill_list; 
    // generic

    const QUEUE_TOP             = 1;
    const QUEUE_BOTTOM          = 2;
    const CONNECT_CHECK_TIME    = 60;

    public function __construct($queue_size, $nntp_threads, $total_threads, $db_intensive_threads)
    {
        assert(is_numeric($queue_size) && is_numeric($nntp_threads) && is_numeric($total_threads) && is_numeric($db_intensive_threads));
        $this->threads = new thread_list();
        $this->servers = new usenet_servers();
        $this->queue = new queue($queue_size);
        $this->schedule = new schedule();
        $this->nntp_enabled = FALSE;
        $this->kill_list = array();
        $this->check_nntp_connections = FALSE;
        if ($total_threads <= $nntp_threads) {
            $total_threads = $nntp_threads + 1; // the default is we have always one slot available for other things
        }
        $this->free_nntp_slots = $this->max_total_nntp_threads = (int) $nntp_threads;
        $this->free_total_slots = $this->max_total_threads = (int) $total_threads;
        $this->free_db_intensive_slots = $this->max_db_intensive_threads = (int) $db_intensive_threads;
        $this->conn_check_time = (int) 0;
    }
    public function __destruct()
    {
        $this->threads = $this->servers = $this->queue = $this->schedule = NULL;
    }

    private function add_slot()
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        $this->free_total_slots--;
    }
    private function remove_slot()
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        $this->free_total_slots++;
    }
    private function add_nntp_slot()
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        $this->free_nntp_slots--;
    }
    private function remove_nntp_slot()
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        $this->free_nntp_slots++;
    }
    private function add_db_intensive_slot()
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        $this->free_db_intensive_slots--;
    }
    private function remove_db_intensive_slot()
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        $this->free_db_intensive_slots++;
    }
    public function enable_nntp($on)
    {
        $this->nntp_enabled = ($on ? TRUE : FALSE);
    }
    public function get_nntp_enabled()
    {
        return $this->nntp_enabled;
    }
    public function check_nntp_connections_enabled()
    {
        return $this->check_nntp_connection;
    }

    public function enable_check_nntp_connections($on)
    {
        $this->check_nntp_connections = ($on ? TRUE : FALSE);
    }

    public function print_size()
    {
        echo_debug("{$this->max_total_nntp_threads} {$this->free_nntp_slots} {$this->max_total_threads} {$this->free_total_slots} {$this->max_db_intensive_threads} {$this->free_db_intensive_slots}", DEBUG_SERVER);
        $this->servers->print_size();
    }

    public function check_conn_time() // never used?
    {
        if (($this->conn_check_time > 0) && (time() > $this->conn_check_time)) {
            echo_debug('Resetting connection count', DEBUG_SERVER);
            $this->reset_connection_limits();
            $this->conn_check_time = 0;
        }
    }
    public function get_slot_data()
    {
        $sd['max_total_nntp_threads'] = $this->max_total_nntp_threads;
        $sd['free_nntp_slots'] = $this->free_nntp_slots;
        $sd['max_total_threads'] = $this->max_total_threads;
        $sd['free_total_slots'] = $this->free_total_slots;
        $sd['max_db_intensive_threads'] = $this->max_db_intensive_threads;
        $sd['free_db_intensive_slots'] = $this->free_db_intensive_slots;

        return $sd;
    }
    public function has_nntp_task()
    {
        return $this->free_nntp_slots == $this->max_total_nntp_threads;
    }
    public function has_equal(action $a)
    {
        return $this->queue->has_equal($a) || $this->threads->has_equal($a);
    }
    public function has_equal_queue(action $a)
    {
        return $this->queue->has_equal($a);
    }
    public function has_equal_thread(action $a)
    {
        return $this->threads->has_equal($a);
    }

    // schedule wrappers
    public function get_first_timeout()
    {
        return $this->schedule->get_first_timeout();
    }
    public function add_schedule(DatabaseConnection $db, job $job)
    {
        return $this->schedule->add($db, $job);
    }
    public function schedule_size()
    {
        return $this->schedule->size();
    }
    public function get_jobs()
    {
        return $this->schedule->get_jobs();
    }
    public function recur_schedule(DatabaseConnection $db, job $job)
    {
        $action = $job->get_action();
        $rec = $job->get_recurrence();
        if ($rec > 0) {
            $new_action = new action(NULL, NULL, NULL);
            $new_action->copy($action);
            $time = $job->get_time();
            $new_time = strtotime("+ $rec seconds", $time); 
            $new_job = new job($new_action, $new_time, $rec);
            $this->add_schedule($db, $new_job);
        }
    }
    public function nntp_slots_available()
    {
        return ($this->free_nntp_slots > 0 && $this->free_total_slots > 0) ? TRUE : FALSE;
    }
    public function slots_available()
    {
        return ($this->free_total_slots > 0) ? TRUE : FALSE;
    }
    public function unschedule(DatabaseConnection $db, $userid, $arg)
    {
        assert(is_numeric($userid));
        return $this->schedule->unschedule($db, $userid, $arg);
    }
    public function unschedule_all(DatabaseConnection $db, $userid)
    {
        assert(is_numeric($userid));
        return $this->schedule->unschedule_all($db, $userid);
    }
    public function unschedule_cmd(DatabaseConnection $db, $userid, $cmd, $arg)
    {
        assert(is_numeric($userid));
        return $this->schedule->unschedule_cmd($db, $userid, $cmd, $arg);
    }
    public function get_first_ready_job(DatabaseConnection $db)
    {
        return $this->schedule->get_first_ready_job($db);
    }
    public function recreate_command(DatabaseConnection $db, action $item, $cmd, $pause=FALSE, $reset_tried_servers=FALSE)
    {
        assert(is_bool($pause) && is_bool($reset_tried_servers));
        $new_item = new action(NULL, NULL, NULL);
        $new_item->copy($item);
        $new_item->set_command($cmd);
        if ($reset_tried_servers === TRUE) {
            $new_item->reset_tried_servers();
        }
        if ($pause === TRUE) {
            $new_item->pause(TRUE, user_status::SUPER_USERID);
        }
        $new_item->set_active_server(0);
        $new_item->set_preferred_server(0);
        $this->queue_push($db, $new_item, FALSE);

        return $new_item->get_id();
    }
    public function recreate_addspotdata(DatabaseConnection $db, action $item, $pause, $reset_tried_servers)
    {
        return $this->recreate_command($db, $item, urdd_protocol::COMMAND_ADDSPOTDATA, $pause, $reset_tried_servers);
    }

    public function recreate_post_command(DatabaseConnection $db, action $item, $pause, $reset_tried_servers)
    {
        return $this->recreate_command($db, $item, urdd_protocol::COMMAND_START_POST, $pause, $reset_tried_servers);
    }

    public function recreate_download_command(DatabaseConnection $db, action $item, $pause=FALSE, $reset_tried_servers=FALSE)
    {
        return $this->recreate_command($db, $item, urdd_protocol::COMMAND_DOWNLOAD, $pause, $reset_tried_servers);
    }
    public function unused_servers_available(array $tried_servers, $must_to_have_free_slot = TRUE)
    {
        return $this->servers->unused_servers_available($tried_servers, $must_to_have_free_slot);
    }
    public function do_reschedule(DatabaseConnection $db, action &$item, $server_id)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        // diminish amount of threads on server
        $this->servers->dec_free_slots($server_id);
        if (compare_command($item->get_command(), urdd_protocol::COMMAND_DOWNLOAD_ACTION)) {// downloads are different as they can run on more servers
            $this->reschedule_download($db, $item, $server_id);
        } elseif (compare_command($item->get_command(), urdd_protocol::COMMAND_POST_ACTION)) {// posts are different as they can run on more servers
            $this->reschedule_post($db, $item, $server_id);
        } elseif (compare_command($item->get_command(), urdd_protocol::COMMAND_ADDSPOTDATA)) {// add_spot data is different as it can run on more servers
            $this->reschedule_addspotdata($db, $item, $server_id);
        } else {
            $this->reschedule($db, $item, $server_id);
        }

    }

    private function reschedule_download(DatabaseConnection $db, action &$item, $server_id)
    {// cleanup this one XXX
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        assert(is_numeric($server_id));
        if (!$this->threads->has_equal($item)) { // this is the last running one
            $now = time();
            if ($this->conn_check_time == 0 || $this->conn_check_time < $now) {// set the connection retry timeout if not set
                $this->conn_check_time = $now + self::CONNECT_CHECK_TIME;
            }
            $this->queue->delete_cmd($db, $item->get_command(), $item->get_args(), user_status::SUPER_USERID, TRUE); // remove all equal from queue
            if ($this->servers->unused_servers_available($item->get_all_failed_servers()) !== FALSE) {// we can try other servers
                $this->recreate_download_command($db, $item, FALSE, FALSE);
            } elseif ($item->get_preview()) { // it's a preview that can't start on any server... need to preempt a task
                try {
                    $userid = $item->get_userid();
                    $rv = $this->queue_push($db, $item, FALSE, self::QUEUE_TOP);
                    if ($rv === FALSE) {
                        update_dlinfo_status($db, DOWNLOAD_PAUSED, $item->get_args());
                    } else {
                        $this->preempt($db, $item, $userid);
                    }
                } catch (exception $e) {
                    echo_debug_trace($e, DEBUG_MAIN);
                    write_log("Fatal error: {$e->getMessage()}", LOG_ERR);
                }
            } else {// all servers tried, queue it
                if ($this->servers->unused_servers_available($item->get_failed_servers(), FALSE) !== FALSE) {
                    echo_debug('Requeueing paused', DEBUG_SERVER);
                    $new_id = $this->recreate_download_command($db, $item, TRUE, TRUE);
                    update_dlinfo_status($db, DOWNLOAD_PAUSED, $item->get_args());
                    $item_unpause = new action(urdd_protocol::COMMAND_CONTINUE, $new_id, $item->get_userid(), TRUE, $item->get_priority());
                    $job = new job($item_unpause, $now + get_timeout($item) * 60, NULL); //try again in 60 secs
                    $this->schedule->add($db, $job);
                } else {
                    complete_download($db, $this, $item, DOWNLOAD_COMPLETE);
                    // all servers have a permanent failure
                    return FALSE;
                }
            }
        } else { // we simple pause the item for a while
            $this->reschedule($db, $item, $server_id);
        }
        return TRUE;
    }

    private function reschedule_post(DatabaseConnection $db, action &$item, $server_id)
    {// cleanup this one XXX
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        assert(is_numeric($server_id));
        if (!$this->threads->has_equal($item)) {
            // this is the last running one
            $now = time();
            if ($this->conn_check_time == 0 ||$this->conn_check_time < $now) {// set the connection retry timeout if not set
                $this->conn_check_time = time() + self::CONNECT_CHECK_TIME;
            }
            $this->queue->delete_cmd($db, $item->get_command(), $item->get_args(), user_status::SUPER_USERID, TRUE); // remove all equal from queue
            if ($this->servers->unused_servers_available($item->get_all_failed_servers()) !== FALSE) {// we can try other servers
                $this->recreate_post_command($db, $item, FALSE, FALSE);
            } else {// all servers tried, queue it paused
                echo_debug('Requeueing paused', DEBUG_SERVER);
                $new_id = $this->recreate_post_command($db, $item, TRUE, TRUE); //xxx
                update_postinfo_status($db, POST_PAUSED, $item->get_args());
                $item_unpause = new action (urdd_protocol::COMMAND_CONTINUE, $new_id, $item->get_userid(), TRUE, $item->get_priority());
                $job = new job($item_unpause, $now + get_timeout($item) * 60, NULL); //try again in 60 secs
                $this->schedule->add ($db, $job);
            }
        } else { // we simple pause the item for a while
            $this->reschedule ($db, $item, $server_id);
        }
    }
    private function reschedule_addspotdata(DatabaseConnection$db, action &$item, $server_id)
    {
        assert(is_numeric($server_id));
        $now = time();
        if ($this->conn_check_time == 0 || $this->conn_check_time < $now) {// set the connection retry timeout if not set
            $this->conn_check_time = time() + self::CONNECT_CHECK_TIME;
        }
        if ($this->servers->unused_servers_available($item->get_all_failed_servers()) !== FALSE) {// we can try other servers
            $this->recreate_addspotdata($db, $item, FALSE, FALSE);
        } else {// all servers tried, queue it paused
            $item->pause(TRUE, user_status::SUPER_USERID);
            $this->queue_push($db, $item, FALSE);
            $item_unpause = new action(urdd_protocol::COMMAND_CONTINUE, $item->get_id(), $item->get_userid(), TRUE, $item->get_priority());
            $job = new job($item_unpause, $now + get_timeout($item) * 60, NULL); //try again in 60 secs
            $this->schedule->add ($db, $job);
        }
    }
    public function reschedule_locked_item(DatabaseConnection $db, action $item)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        try {
            echo_debug('Dl still locked, pausing', DEBUG_SERVER);
            $command = $item->get_command();
            $args = $item->get_args();
            $item->pause(TRUE, user_status::SUPER_USERID);
            $this->queue_push($db, $item, FALSE);
            $item_unpause = new action (urdd_protocol::COMMAND_CONTINUE, "$command $args", $item->get_userid(), TRUE);
            $offset = $item->get_preview() ? DatabaseConnection::DB_LOCK_TIMEOUT_PREVIEW : DatabaseConnection::DB_LOCK_TIMEOUT_DEFAULT;
            $job = new job($item_unpause, time() + $offset, NULL); //try again in XX secs
            $this->add_schedule($db, $job);
        } catch (exception $e) {
            echo_debug_trace($e, DEBUG_SERVER);
            throw $e;
        }
    }

    private function reschedule(DatabaseConnection$db, action &$item, $server_id)
    {// cleanup this one XXX
        assert(is_numeric($server_id));
        $now = time();
        if ($this->conn_check_time == 0 ||$this->conn_check_time < $now) {// set the connection retry timeout if not set
            $this->conn_check_time = time() + self::CONNECT_CHECK_TIME;
        }
        echo_debug('Requeueing paused', DEBUG_SERVER);
        $item->pause(TRUE, user_status::SUPER_USERID);
        $this->queue_push($db, $item, FALSE);
        $item_unpause = new action(urdd_protocol::COMMAND_CONTINUE, $item->get_id(), $item->get_userid(), TRUE, $item->get_priority());
        $job = new job($item_unpause, $now + get_timeout($item) * 60, NULL); //try again in 60 secs
        $this->schedule->add($db, $job);
    }
    public function reload_scheduled_jobs(DatabaseConnection $db)
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        $query = '* FROM schedule';
        $res = $db->select_query($query);
        if (!isset($res[0])) {
            return;
        }
        foreach ($res as $sched) {
            $command = $sched['command'];
            $c = explode(' ', $command, 2);
            $cmd = $c[0];
            $arg = (isset($c[1])) ? $c[1] : '';
            $userid = $sched['userid'];
            $stime = $sched['at_time'];
            $repeat = $sched['interval'];
            $id = $sched['id'];
            $ctime = time();
            if ($repeat > 0) {
                while (($ctime + min(60, $repeat)) > $stime) {
                    $stime += $repeat;
                }
            } else {
                if ($stime < $ctime) {
                    $stime = $ctime;
                }
                $repeat = NULL;
            }
            $item = new action ($cmd, $arg, $userid);
            $this->schedule->add($db, new job($item, $stime, $repeat));
            $res = $db->delete_query('schedule', '"id"=?', array($id));
        }
    }
    //usenet_wrappers
    public function reset_connection_limits($server_id)
    {
        $this->servers->reset_connection_limits($server_id);
    }
    public function set_update_server($srv)
    {
        assert(is_numeric($srv));
        $this->servers->set_update_server($srv);
    }
    public function get_update_server()
    {
        return $this->servers->get_update_server();
    }

    public function reload_server(DatabaseConnection $db, $id)
    {
        assert(is_numeric($id));
        $srv = get_usenet_server($db, $id, FALSE);
        $this->servers->update_server($srv, $id);
    }
    public function load_server(DatabaseConnection$db, $id)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_numeric($id));
        $s = get_usenet_server($db, $id, FALSE);
        $us = new usenet_server($s['id'], $s['threads'], $s['port'], $s['hostname'], $s['connection'], $s['username'], $s['password'], $s['priority'], $s['posting']);
        $this->servers->add_server($us);
    }
    public function load_servers(DatabaseConnection $db, test_result_list &$test_results)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        $srv = get_all_usenet_servers($db, FALSE);
        $cnt = 0;
        foreach ($srv as $s) {
            $us = new usenet_server($s['id'], $s['threads'], $s['port'], $s['hostname'], $s['connection'], $s['username'], $s['password'], $s['priority'], $s['posting']);
            $cnt += $this->add_server($us);
        }
        $update_server = get_config($db, 'preferred_server');
        if ($cnt == 0) {
            throw new exception('Enable at least one server in admin/usenet servers', ERR_NO_ACTIVE_SERVER);
        }
        if ($update_server == 0) {
            throw new exception('Please set update server in admin/usenet servers', ERR_NO_ACTIVE_SERVER);
        }
        try {
            $this->servers->set_update_server($update_server);
        } catch (exception $e) {
            if ($e->getcode() == ERR_NO_SUCH_SERVER) {
                throw new exception('Please set a valid update server in admin/usenet servers', ERR_NO_ACTIVE_SERVER);
            } else {
                throw $e;
            }
        }
        $this->servers->find_max_connections($db, $test_results, $this->check_nntp_connections);
    }
    public function get_max_threads($server_id)
    {
        assert(is_numeric($server_id));

        return $this->servers->get_max_threads($server_id);
    }
    public function get_max_total_nntp_threads()
    { 
        return $this->max_total_nntp_threads;
    }
    public function find_preferred_server()
    {
        try {
            $id = $this->servers->get_update_server();
        } catch (exception $e) {
            write_log ('No primary server set', LOG_ERR);
            throw $e;
        }

        return $id;
    }
    public function add_server(usenet_server $server)
    {
        return $this->servers->add_server($server);
    }
    public function delete_server($id)
    {
        assert(is_numeric($id));
        $this->servers->delete_server($id);
    }
    public function enable_posting($server_id)
    {
        assert(is_numeric($server_id));

        return $this->servers->enable_posting($server_id);
    }

    public function disable_posting($server_id)
    {
        assert(is_numeric($server_id));

        return $this->servers->disable_posting($server_id);
    }

    public function get_priority($server_id)
    {
        assert(is_numeric($server_id));
        return $this->servers->get_priority($server_id);
    }

    public function set_server_priority($server_id, $priority)
    {
        assert(is_numeric($server_id) && is_numeric($priority));
        $this->servers->set_priority($server_id, $priority);
    }

    public function schedule_enable_server(DatabaseConnection $db, $server_id, $userid, $timeout=3600, $priority=DEFAULT_USENET_SERVER_PRIORITY)
    {
        assert(is_numeric($server_id) && is_numeric($userid) && is_numeric($timeout) && is_numeric($priority));
        $item_unpause = new action(urdd_protocol::COMMAND_SET, "SERVER ENABLE $server_id $priority", $userid, TRUE, DEFAULT_PRIORITY);
        echo_debug("Scheduling 'SERVER ENABLE $server_id $priority' at " . date('r', time() + $timeout), DEBUG_SERVER);
        if (!$this->schedule->has_equal($item_unpause)) {
            $job_unpause = new job($item_unpause, time() + $timeout, 0);
            $this->add_schedule($db, $job_unpause);
        }
    }

    public function enable_server($server_id, $prio)
    {
        assert(is_numeric($server_id) && is_numeric($prio));

        $this->servers->enable_server($server_id, $prio);
        $this->queue->reset_temp_failed_server($server_id);
        $this->reset_connection_limits($server_id);
    }

    public function disable_server($server_id)
    {
        assert(is_numeric($server_id));

        $this->servers->disable_server($server_id);
    }

    public function get_servers()
    {
        return $this->servers->get_servers();
    }

    public function find_free_slot(array $already_used_servers=array(), $need_posting=FALSE, $is_download=FALSE)
    {
        return $this->servers->find_free_slot($already_used_servers, $need_posting, $is_download);
    }
    // queue wrappers
    public function pause(DatabaseConnection $db, $id, $pause, $userid)
    {
        assert(is_numeric($id) && is_bool($pause) && is_numeric($userid));
        try {
            $rv = $this->queue->pause($db, $id, $pause, $userid);
        } catch (exception $e) {
            $rv = FALSE;
        }
        if ($pause === TRUE) { // can't restart running threads so only check threads if we are pausing, not continuing
            try {
                $pid = $this->threads->get_pid($id);
                $thread =& $this->threads->get_thread($pid);
                if (!$thread->get_action()->match_userid($userid)) {
                    throw new exception ('Not allowed', ERR_ACCESS_DENIED);
                }
                $this->urdd_kill($pid, SIGTERM);
                wait_for_child();

                $thread->set_status(DOWNLOAD_PAUSED);
                $item = $thread->get_action();
                update_thread_status($db, $item, DOWNLOAD_PAUSED, POST_PAUSED);
                $item = $thread->get_action(); // delete from the list will be done by the reap function
                $item->pause($pause, $userid);
                $this->queue_push($db, $item, FALSE); // if it is paused we simply put it back on the queue

                return TRUE;
            } catch (exception $e) {
                return $rv;
            }
        }
    }

    public function move(DatabaseConnection $db, $cmd, $arg, $userid, $direction)
    {
        assert(is_numeric($userid));
        $this->queue->move($db, $cmd, $arg, $userid, $direction);
    }

    public function pause_cmd(DatabaseConnection $db, $cmd, $arg, $do_pause, $userid)// $do_pause == true then pause, else continue (unpause)
    {
        assert(is_bool($do_pause) && is_numeric($userid));
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        try {
            $rv = $this->queue->pause_cmd($db, $cmd, $arg, $do_pause, $userid);
        } catch (exception $e) {
            $rv = FALSE;
        }
        if ($do_pause === TRUE) { // can't restart running threads so only check threads if we are pausing, not continuing
            $pids = $this->threads->get_pid_cmd($cmd, $arg);
            if ($pids == array()) {
                return TRUE;
            }
            $to_queue = array();
            foreach ($pids as $pid) {
                $thread =& $this->threads->get_thread($pid);
                if (!$thread->get_action()->match_userid($userid)) {
                    continue;
                }
                $this->urdd_kill($pid, SIGTERM); 
                $thread->set_status(DOWNLOAD_PAUSED);
                $item = $thread->get_action(); // delete from the list will be done by the reap function
                $cmd = $item->get_command();
                update_thread_status($db, $item, DOWNLOAD_PAUSED, POST_PAUSED);

                $item->pause($do_pause, $userid);
                $to_queue[] = $item;
                $rv = TRUE;
            }
            // we wait for urdd to catch the child process and do some cleanup
            wait_for_child();
            foreach ($to_queue as $item) {
                $this->queue_push($db, $item, FALSE); // if it is paused we simply put it back on the queue
            }
        }

        return $rv;
    }

    public function pause_all(DatabaseConnection$db, $do_pause, $userid)
    {
        assert(is_bool($do_pause) && is_numeric($userid));
        $this->queue->pause_all($db, $do_pause, $userid);
        if ($do_pause === TRUE) {
            $pids = $this->threads->get_all_pids($userid);
            if ($pids == array()) {
                return TRUE;
            }
            $to_queue = array();
            foreach ($pids as $pid) {
                $thread =& $this->threads->get_thread($pid);
                if ($thread->get_action()->match_userid($userid)) {
                    $thread->set_status(DOWNLOAD_PAUSED);
                    $item = $thread->get_action(); // delete from the list will be done by the reap function
                    $this->urdd_kill($pid, SIGTERM);
                    $item->pause($do_pause, $userid); // we set it to pause
                    update_thread_status($db, $item, DOWNLOAD_PAUSED, POST_PAUSED);
                    $to_queue[] = $item;
                }
            }
            wait_for_child();
            foreach ($to_queue as $item) {
                $this->queue_push($db, $item, FALSE); // if it is paused we simply put it back on the queue
            }
        }

    }

    public function queue_push(DatabaseConnection $db, action $item, $increase_counter=TRUE, $position=self::QUEUE_BOTTOM, $priority=NULL)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_bool($increase_counter));
        echo_debug('Queueing ' . $item->get_command() . ' ' . $item->get_args() . ' ' . $item->get_id(), DEBUG_SERVER);
        switch ($position) {
            case self::QUEUE_TOP:
                return $this->queue->push_top($item, $db, $increase_counter);
                break;
            case self::QUEUE_BOTTOM:
                return $this->queue->push($item, $db, $increase_counter, $priority);
                break;
            default:
                throw new exception_queue_failed('Queue position not understood');
        }
    }

    public function queue_size()
    {
        return $this->queue->size();
    }

    public function move_top(DatabaseConnection $db, $index, $userid)
    {
        assert(is_numeric($index) && is_numeric($userid));

        return $this->queue->move_top($db, $index, $userid);
    }

    public function queue_delete(DatabaseConnection $db, $action_id, $userid, $delete_db = FALSE)
    {
        assert(is_numeric($action_id) && is_bool($delete_db) && is_numeric($userid));
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);

        return $this->queue->delete($db, $action_id, $userid, $delete_db);
    }

    protected function update_dl_status(DatabaseConnection $db, array $q_ids, $dl_status, $post_status)
    {
        foreach ($q_ids as $qid) {
            $item = $this->queue->get_queue_item($qid);
            if ($item === NULL) {
                continue; // need warning here?
            }
            update_thread_status($db, $item, $dl_status, $post_status);
        }
    }
    public function delete_cmd(DatabaseConnection $db, $userid, $cmd, $arg, $delete_db = FALSE)
    {
        assert(is_bool($delete_db) && is_numeric($userid));
        $q_ids = $this->queue->get_ids_cmd($cmd, $arg, $userid);
        if ($delete_db === TRUE) {
            $this->update_dl_status($db, $q_ids, DOWNLOAD_CANCELLED, POST_CANCELLED);
        }
        $rv = $this->queue->delete_ids($db, $q_ids, $userid, $delete_db);
        $rv = $rv > 0;
        $pids = $this->threads->get_pid_cmd($cmd, $arg);
        if ($pids == array()) {
            return TRUE;
        }
        foreach ($pids as $pid) {
            $thread = &$this->threads->get_thread($pid);
            if (!$thread->get_action()->match_userid($userid)) {
                throw new exception ('Not allowed', ERR_ACCESS_DENIED);
            }
            $this->urdd_kill($pid, SIGTERM);

            if ($delete_db === TRUE) {
                $item = $thread->get_action();
                $thread->set_status(DOWNLOAD_CANCELLED);
                update_thread_status($db, $item, DOWNLOAD_CANCELLED, POST_CANCELLED);
                update_queue_status($db, $thread->get_action()->get_dbid(), QUEUE_CANCELLED);
            }
            $rv = TRUE;
        }
        wait_for_child();

        return $rv;
    }
    public function delete_all(DatabaseConnection $db, $userid, $delete_db=FALSE)
    {
        assert(is_bool($delete_db) && is_numeric($userid));
        $q_ids = $this->queue->get_ids_all($userid);
        if ($delete_db === TRUE) {
            $this->update_dl_status($db, $q_ids, DOWNLOAD_CANCELLED, POST_CANCELLED);
        }
        $this->queue->delete_ids($db, $q_ids, $userid, $delete_db);
        $pids = $this->threads->get_all_pids($userid);
        if ($pids == array()) {
            return TRUE;
        }
        foreach ($pids as $pid) {
            $thread = &$this->threads->get_thread($pid);
            if ($delete_db === TRUE) {
                $item = $thread->get_action();
                update_thread_status($db, $item, DOWNLOAD_CANCELLED, POST_CANCELLED);
                update_queue_status($db, $item->get_dbid(), QUEUE_CANCELLED);
                $thread->set_status(DOWNLOAD_CANCELLED);
            } else {
                $thread->set_status(DOWNLOAD_SHUTDOWN);
            }
            $this->urdd_kill($pid, SIGTERM);
            // updating database is done by reap function
        }
        wait_for_child();

        return TRUE; // todo
    }
    public function delete(DatabaseConnection $db, $action_id, $userid, $delete_db = FALSE)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_numeric($action_id) && is_bool($delete_db) && is_numeric($userid));
        $q_ids = $this->queue->get_ids_action($action_id, $userid);
        if ($delete_db === TRUE) {
            $this->update_dl_status($db, $q_ids, DOWNLOAD_CANCELLED, POST_CANCELLED);
        }
        $rv = $this->queue->delete_ids($db, $q_ids, $userid, $delete_db);
        $rv = $rv > 0;

        if ($rv === FALSE) {
            $pid = $this->threads->get_pid($action_id);
            if ($pid !== FALSE) {
                $thread = &$this->threads->get_thread($pid);
                $item = $thread->get_action();
                if (!$item->match_userid($userid)) {
                    throw new exception ('Not allowed', ERR_ACCESS_DENIED);
                }
                if ($delete_db === TRUE) {
                    update_thread_status($db, $item, DOWNLOAD_CANCELLED, POST_CANCELLED);
                    $thread->set_status(DOWNLOAD_CANCELLED);
                    update_queue_status($db, $thread->get_action()->get_dbid(), QUEUE_CANCELLED);
                }
                $this->urdd_kill($pid, SIGTERM);
                wait_for_child();
                $rv = TRUE;
            }
        }

        return $rv;
    }
    public function queue_set_priority(DatabaseConnection $db, $action_id, $userid, $priority)
    {
        assert(is_numeric($action_id) && is_numeric($priority) && is_numeric($userid));

        return $this->queue->set_priority($db, $action_id, $userid, $priority);
    }
    public function get_queue()
    {
        return $this->queue->get_queue();
    }
    protected function get_first_runnable_preview(DatabaseConnection $db, action $item)
    {
        //echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        $ready = TRUE;
        if(($item->get_preferred_server() == 0) && $item->is_download()) {
            // so it must not have a server set yet and it must be a download action and there must be a total slot available
            $srv = $this->find_free_slot($item->get_all_failed_servers(), FALSE); // find a server with a slot available
            if (($srv !== FALSE) && ($srv != 0) && ($this->free_total_slots > 0) && ($this->free_nntp_slots > 0)) {
                // so there must be a free server, and there must be a slot available to set the preferred server
                $item->set_preferred_server($srv); // set the prefered server
                echo_debug('Found a server ' . $srv, DEBUG_SERVER);
                $ready = TRUE;
            } else {
                try {
                    echo_debug('Preempting...', DEBUG_SERVER);
                    $srv = $this->preempt($db, $item, $item->get_userid());
                    $item->set_preferred_server($srv); // set the prefered server
                    usleep(5000);// wait so that the chld signal is delivered and the reap function calls it
                    $ready = TRUE;
                } catch (exception $e) {
                    $ready = FALSE;
                    echo_debug('Could not find a server', DEBUG_SERVER);
                }
                $ready = TRUE;
            }
        }

        if ($ready === TRUE) {
            echo_debug('Found a preview thread... should always start, using server ' . $item->get_preferred_server(), DEBUG_SERVER);

            return $item;
        } 
        return FALSE;

    }

    protected function check_db_intensive(action $item)
    {
        if ($this->free_db_intensive_slots > 0) {
            return $item;
        } else {
            throw new exception_internal('Something has gone terribly wrong');
        }
    }
    public function get_first_runnable_on_queue(DatabaseConnection $db)
    { // returns TRUE if the queue is empty, FALSE if no item is runnable, otherwise it returns the runnable item
        //echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        try {
            if ($this->queue->is_empty()) {// we have nothing to do

                return TRUE;
            }
            $item = $this->queue->get_preview_action(); // see if there are any preview tasks queued
            if ($item !== FALSE) { // there is one preview
                $item = $this->get_first_runnable_preview($db, $item);
                if ($item !== FALSE) {
                    return $item;
                }
            }
            if ($this->free_total_slots <= 0) {
                return TRUE; // no slot available....
            } elseif ($this->free_nntp_slots <= 0) { // no nntp slot available but maybe there is a non-nntp item on the queue
                $item = $this->queue->top(FALSE, array(), ($this->free_db_intensive_slots > 0) ? TRUE : FALSE );
                if ($item === FALSE) {
                    return TRUE;
                }

                if ($item->db_intensive() === TRUE) {
                    return $this->check_db_intensive($item);
                } 
                return $item;
            } else {
                $not_these = array(); // the ones we already tried
                while (1) {
                    $item = $this->queue->top(TRUE, $not_these, $this->free_db_intensive_slots > 0 ? TRUE : FALSE); // get an nntp item from the queue or if non found, a non-nntp item
                    if ($item === FALSE) { // there are no more items that can run

                        return TRUE;
                    }
                    if ($item->db_intensive() === TRUE && $item->need_nntp() === FALSE) { 
                        return $this->check_db_intensive($item);
                    }
                    if ($item->need_nntp() === FALSE) {// non-nntp items can always run if there is a slot available

                        return $item;
                    }
                    if ($item->primary_nntp()) {  // thread needs the indexing server
                        $server_id = $this->get_update_server();
                        $item->set_preferred_server($server_id);
                    } else {
                        $server_id = $item->get_preferred_server();
                        if ($server_id != 0 && ($this->servers->get_priority($server_id) <= 0 ||
                                    $this->servers->is_backup_server($server_id) && count($item->get_all_failed_servers()) == 0 && $is_download)) {
                            $item->set_preferred_server(0);
                            $item->remove_tried_server($server_id);
                            $server_id = 0;
                        }
                    }
                    if ($server_id == 0) {// if the preferred server is not set, it can probably run
                        $srv = $this->find_free_slot($item->get_all_failed_servers(), $item->need_posting(), $item->is_download());

                        if ($srv !== FALSE) {
                            $item->set_preferred_server($srv);
                            // check if a free slot is available
                            return $item;
                        }
                    } elseif ($this->servers->has_free_slot($server_id)) { // check if the preferred server is available
                        return $item;
                    }
                    $not_these[] = $item->get_id(); // not available lets not try this queued item again through this iteration
                }
            }
        } catch (exception $e) {
            // something went wrong,... but what
            echo_debug('O-oh an error... ' . $e->getMessage() . ' ' . $e->getCode(), DEBUG_SERVER);

            return FALSE;
        }

        return FALSE; // safety catch: shouldn't happen tho
    }

    // threads wrappers
    public function add_thread(thread $thread, $server_id=FALSE)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_numeric($server_id) || $server_id === FALSE);
        $item = $thread->get_action();
        $pid = $thread->get_pid();
        if ($this->free_total_slots <= 0) {// check if there is a slot available...
            $this->threads->add_dummy_thread($pid);
            throw new exception ('No slot available', ERR_NO_SLOT_AVAILABLE);
        }
        if ($item->need_nntp() === TRUE) { // we are an nntp item check if there is an nntp slot available on any of the servers
            if ($this->free_nntp_slots <= 0) {
                $this->threads->add_dummy_thread($pid); // need to create a dummy so that the reaper can remove, otherwise we get silly errors there
                throw new exception ('No nntp slot available 2', ERR_NO_NNTPSLOT_AVAILABLE);
            }

            $server_id = $item->get_preferred_server();
            if ($server_id === FALSE || $server_id == 0) {
                $server_id = $this->find_free_slot($item->get_all_failed_servers(), $item->need_posting(), $item->is_download());
            }
            if ($server_id === FALSE) {
                $this->threads->add_dummy_thread($pid);
                throw new exception ('No nntp slot available 1', ERR_NO_NNTPSLOT_AVAILABLE);
            }
            $this->threads->add_thread($thread, $server_id);
            $this->servers->add_thread($server_id);
            $this->add_nntp_slot();
        } else {
            $this->threads->add_thread($thread, 0);
        }
        if ($item->db_intensive() === TRUE) {
            $this->add_db_intensive_slot();
        }
        $this->add_slot();
    }
    public function delete_thread(DatabaseConnection $db, $pid, $store)
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        assert(is_numeric($pid) && is_bool($store));
        list($item, $server_id, $status) = $this->threads->delete_thread($db, $pid, $store);
        $this->remove_slot();
        if ($item->need_nntp()) {
            $this->remove_nntp_slot();
            $this->servers->delete_thread($server_id);
        }
        if ($item->db_intensive()) {
            $this->remove_db_intensive_slot();
        }
        if ($this->threads_size() == 0 && $this->queue_size() == 0) {
            // if there is nothing running and nothing in the queue, we reset the server settings to the defaults (should not be needed)
            $this->restore_server_settings();
        }

        return array($item, $server_id, $status);
    }
    public function threads_size()
    {
        return $this->threads->size();
    }
    public function get_threads()
    {
        return $this->threads->get_threads();
    }
    public function preempt(DatabaseConnection $db, action $item, $userid)
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        assert(is_numeric($userid));
        if (!empty($this->kill_list)) { 
            usleep(5000);
            throw new exception ('Cannot preempt a task');
        }
        $pids = $this->threads->get_all_pids($userid);
        $old_id = FALSE;
        foreach ($pids as $item_pid) {
            $t = $this->threads->get_thread($item_pid);
            $item2 = $t->get_action();
            if ($item2->get_preview()) {
                continue; // preview threads won't get preempted
            }
            $old_id = $item2->get_id();
            if (($item2->need_nntp() == $item->need_nntp())
                    && !in_array($item2->get_active_server(), $item->get_failed_servers())
                    && !in_array($item2->get_active_server(), $item->get_tried_servers())) { // this one will do
                $item->set_preferred_server($item2->get_preferred_server());
                break;
            }
            // otherwise we take the last one... this means no nntp threads were in use, but all were occupied by non nntp threads
            // or we run a non nntp thread and no nonnntp are running ;-)
        }
        if ($old_id === FALSE) {
            $this->delete($db, $item->get_id(), $userid, TRUE);
            throw new exception ('Cannot preempt a task');
        }
        try {
            $this->stop($db, $old_id, $userid);
        echo_debug('QUEUE move top ' . $item->to_string(), DEBUG_SERVER);
            $this->queue->move_top($db, $item->get_id(), user_status::SUPER_USERID);
        } catch (exception $e) {
            throw new exception ('ID not found ' . $e->getMessage());
        }
        return $item2->get_active_server();
    }
    public function stop_all(DatabaseConnection $db, $userid)
    {
        assert(is_numeric($userid));
        $pids = $this->threads->get_all_pids($userid);
        if ($pids == array()) {
            return TRUE;
        }
        $to_queue = array();
        foreach ($pids as $pid) {
            $thread =& $this->threads->get_thread($pid);
            if ($thread->get_action()->match_userid($userid)) {
                $thread->set_status(DOWNLOAD_STOPPED);
                $item = $thread->get_action(); // delete from the list will be done by the reap function
                $this->urdd_kill($pid, SIGTERM);
                $to_queue[] = $item;
            }
        }
        wait_for_child();
        foreach ($to_queue as $item) {
            $this->queue_push($db, $item, TRUE); // if it is paused we simply put it back on the queue
        }
    }
    public function stop(DatabaseConnection $db, $id, $userid)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_numeric($id) && is_numeric($userid));
        $pid = $this->threads->get_pid($id);

        $thread =& $this->threads->get_thread($pid);
        $item = $thread->get_action(); // delete from the list will be done by the reap function
        if (!$item->match_userid($userid)) {
            throw new exception('Not allowed', ERR_ACCESS_DENIED);
        }
        $thread->set_status(DOWNLOAD_STOPPED);
        $item->clear_tried_servers(array($item->get_active_server()));
        echo_debug('QUEUEPUSH ' . $item->to_string(), DEBUG_SERVER);
        $this->queue_push($db, $item, FALSE, self::QUEUE_BOTTOM, 3); // and reschedule it to the top of the queue, but after previews
        $this->urdd_kill($pid, SIGTERM);
        wait_for_child();

        return TRUE;
    }
    public function test_servers(DatabaseConnection $db, test_result_list &$test_results)
    {
        $this->servers->test_servers($db, $test_results);
    }
    public function find_servers(DatabaseConnection $db, test_result_list &$test_results, $dbid, $type, $update_config)
    {
        $this->servers->find_servers($db, $test_results, $dbid, $type, $update_config);
    }
    private function restore_server_settings()
    {
        $this->free_nntp_slots = $this->max_total_nntp_threads;
        $this->free_total_slots = $this->max_total_threads;
        $this->free_db_intensive_slots = $this->max_db_intensive_threads;
        $this->servers->restore_server_settings();
    }
    public function schedule_locked_item(DatabaseConnection $db, action $item)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        echo_debug('Dl still locked, pausing', DEBUG_SERVER);
        $command = $item->get_command();
        $args = $item->get_args();
        $item->pause(TRUE, user_status::SUPER_USERID);
        $item_unpause = new action (urdd_protocol::COMMAND_CONTINUE, "$command $args", $item->get_userid(), TRUE);
        $offset = $item->get_preview() ? DatabaseConnection::DB_LOCK_TIMEOUT_PREVIEW : DatabaseConnection::DB_LOCK_TIMEOUT_DEFAULT;
        $job = new job($item_unpause, time() + $offset, NULL); //try again in 30 secs
        $this->add_schedule($db, $job);
    }
    public function remove_kill_list($pid)
    {
        if (isset($this->kill_list[$pid])) {
            unset($this->kill_list[$pid]);
        }
    }
    private function urdd_kill($pid, $signal)
    {
        $this->kill_list[$pid] = $pid;
        $r = posix_kill($pid, $signal);
        
        if ($r === FALSE) {
            $ec = posix_get_last_error();
            $msg = '';
            if ($ec != 0) {
                $msg = posix_strerror($ec);
            }
        throw new exception('Kill failed: ' . $msg);
    }
}




} // server data
