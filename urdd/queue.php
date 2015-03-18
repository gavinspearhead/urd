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
 * $LastChangedDate: 2014-06-07 14:53:28 +0200 (za, 07 jun 2014) $
 * $Rev: 3081 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: queue.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class queue
{
    private $qq;
    private $max_size;
    const MAX_REQUEUE_COUNT = 10000; // the maximum number of times an item can be requeued
    const MOVE_UP = 1;
    const MOVE_DOWN = -1;

    public function __construct($max_size=0)
    {
        assert(is_numeric($max_size));
        $this->qq = array();
        $this->max_size = (int) $max_size;
    }

    public function __destruct()
    {
        $this->qq = NULL;
    }
        
    public function to_string()
    {
        $s = '';
        foreach ($this->qq as $q) {
            $s .= $q->to_string() . "\n";
        }
        return $s;
    }

    public function size()
    {
        return count($this->qq);
    }

    public function is_empty()
    {
        return count($this->qq) == 0;
    }

    public function get_queue()
    {
        $qq = array();
        foreach ($this->qq as $q) {
            $row['id'] = $q->get_id();
            $row['userid'] = $q->get_userid();
            $row['priority'] = $q->get_priority();
            $row['command'] = $q->get_command();
            $row['args'] = $q->get_args();
            $row['queue_time'] = $q->get_queue_time();
            $row['pause'] = $q->is_paused();

            $qq[] = $row;
        }

        return $qq;
    }

    private static function update_download_position(DatabaseConnection $db, action $item, $position)
    {
        if ($item->is_download()) {
            $id = $item->get_args();
            $db->update_query_2('downloadinfo', array('position' => $position), '"ID"=?', array($id));
        }
    }

    public function update_all_download_position(DatabaseConnection $db)
    {
        $db->update_query_2('downloadinfo', array('position' => 0));
        $cnt = 0;

        foreach ($this->qq as $q) {
            $cnt++;
            self::update_download_position($db, $q, $cnt);
        }
    }

    public function move_down(DatabaseConnection $db, $cmd, $arg, $userid)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_numeric($userid));
        $prev = $next = NULL;
        // first find the previous one
        foreach ($this->qq as $k => &$q) {
            if ($prev !== NULL && $prev->is_equal($cmd, $arg) && !$q->is_equal($cmd, $arg)) {
                if ($q->match_userid($userid)) {
                    $next = $q;
                    break;
                }
            }
            if ($q->match_userid($userid)) {
                $prev = $q;
            }
        }

        if ($next === NULL) {
            return FALSE;
        }
        $before = $after = array();
        $previous = $current = array();
        // split the queue
        $found = FALSE;
        foreach ($this->qq as $k=>&$q) {
            if ($q->is_equal($cmd, $arg)) {
                $current[$k] = $q;
            } elseif ($q->is_equal($next->get_command(), $next->get_args())) {
                $previous[$k] = $q;
                $found = TRUE;
            } elseif ($found == TRUE) {
                $after[$k] = $q;
            } else {
                $before[$k] = $q;
            }
        }

        // swap the priorities
        $prio2 = $prev->get_priority();
        $prio1 = $next->get_priority();
        foreach ($current as &$q) {
            $q->set_priority($prio2, $userid);
            update_queue_priority($db, $q->get_dbid(), $prio2);
        }

        foreach ($previous as &$q) {
            $q->set_priority($prio1, $userid);
            update_queue_priority($db, $q->get_dbid(), $prio1);
        }

        $this->qq = array_merge($before, $previous, $current, $after);

        return TRUE;
    }

    public function move_up(DatabaseConnection $db, $cmd, $arg, $userid)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_numeric($userid));
        $prev = $curr = NULL;
        // first find the previous one
        foreach ($this->qq as $k=>&$q) {
            if ($q->is_equal($cmd, $arg) && $q->match_userid($userid)) {
                if ($prev === NULL) {
                    return FALSE; // it's already at the top
                } else {
                    $curr = $q;
                }
                break;
            } else {
                if ($q->match_userid($userid)) {
                    $prev = $q;
                }
            }
        }
        if ($prev === NULL || $curr === NULL) {
            return FALSE;
        }
        $before = $after = array();
        $previous = $current = array();
        // split the queue
        $found = FALSE;
        foreach ($this->qq as $k=>&$q) {
            if ($q->is_equal($cmd, $arg)) {
                $current[$k] = $q;
            } elseif ($q->is_equal($prev->get_command(), $prev->get_args())) {
                $previous[$k] = $q;
                $found = TRUE;
            } elseif ($found == TRUE) {
                $after[$k] = $q;
            } else {
                $before[$k] = $q;
            }
        }

        // swap the priorities
        $prio1 = $curr->get_priority();
        $prio2 = $prev->get_priority();
        foreach ($current as &$q) {
            $q->set_priority($prio2, $userid);
            update_queue_priority($db, $q->get_dbid(), $prio2);
        }

        foreach ($previous as &$q) {
            $q->set_priority($prio1, $userid);
            update_queue_priority($db, $q->get_dbid(), $prio1);
        }

        $this->qq = array_merge($before, $current, $previous, $after);

        return TRUE;
    }

    public function move(DatabaseConnection $db, $cmd, $arg, $userid, $direction)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert (is_numeric($userid));
        if ($direction == self::MOVE_DOWN) {
            $rv = $this->move_down($db, $cmd, $arg, $userid);
        } elseif ($direction == self::MOVE_UP) {
            $rv = $this->move_up($db, $cmd, $arg, $userid);
        } else {
            assert(FALSE);
        }
        $this->update_all_download_position($db);
    }

    public function push(action $item, DatabaseConnection $db, $increase_counter=TRUE, $priority=NULL)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_bool($increase_counter));
        if ($priority != NULL) {
            assert(is_numeric($priority));
            $item->set_priority($priority, $item->get_userid());
            update_queue_priority($db, $item->get_dbid(), $priority);
        }

        $rv = $this->add_prio($item, $db, $increase_counter);
        return $rv;
    }

    public function push_top(action $item, DatabaseConnection $db, $increase_counter=TRUE)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_bool($increase_counter));
        $item->set_priority(1, $item->get_userid());

        return $this->add_prio($item, $db, $increase_counter);
    }

    public function move_top(DatabaseConnection$db, $index, $userid)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_numeric($index) && is_numeric($userid));
        foreach ($this->qq as $k => $q) {
            if ($q->get_id() == $index) {
                if ($q->match_userid($userid)) {
                    unset($this->qq[$k]);
                    array_unshift($this->qq, $q);
                    $this->update_all_download_position($db);

                    return;
                } else {
                    throw new exception('Not allowed', ERR_ACCESS_DENIED);
                }
            }
        }
        throw new exception('Item not found', ERR_ITEM_NOT_FOUND);
    }

    public function has_equal(action $a)
    {
        foreach ($this->qq as $q) {
            if ($q->is_equal($a->get_command_code(), $a->get_args())) {
                return TRUE;
            }
        }

        return FALSE;

    }

    public function get_ids_all($userid)
    {
        assert (is_numeric($userid));
        $keys = array();
        foreach ($this->qq as $k => $q) {
            if ($q->match_userid($userid)) {
                $keys[] = $k;
            }
        }

        return $keys;
    }

    public function get_ids_cmd($cmd, $arg, $userid)
    {
        assert (is_numeric($userid));
        $keys = array();
        foreach ($this->qq as $k => $q) {
            if ($q->is_equal($cmd, $arg) && $q->match_userid($userid)) {
                $keys[] = $k;
            }
        }

        return $keys;
    }

    public function get_ids_action($action_id, $userid)
    {
        assert (is_numeric($action_id) && is_numeric($userid));
        $keys = array();
        foreach ($this->qq as $k => $q) {
            if ($action_id == $q->get_id()) {
                $keys[] = $k;
            }
        }

        return $keys;
    }

    public function delete_ids(DatabaseConnection $db, array $ids, $userid, $delete_db=FALSE)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_bool($delete_db) && is_numeric($userid));
        $cnt = 0;
        foreach ($ids as $id) {
            if (isset($this->qq[$id])) {
                if ($this->qq[$id]->match_userid($userid)) {
                    if ($delete_db) {
                        $status = QUEUE_CANCELLED;
                        update_queue_status($db, $this->qq[$id]->get_dbid(), $status);
                    }
                    unset($this->qq[$id]);
                    $cnt ++;
                } else {
                    throw new exception ('Not allowed', ERR_ACCESS_DENIED);
                }
            } else {
                throw new exception ('Queue item not found', ERR_ITEM_NOT_FOUND);
            }
        }

        return $cnt;
    }

    public function get_queue_item($id)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_numeric($id));
        if (isset($this->qq[$id])) {
            return $this->qq[$id];
        } else {
            throw new exception('Queue item not found', ERR_ITEM_NOT_FOUND);
        }
    }

    public function delete_cmd(DatabaseConnection $db, $cmd, $arg, $userid, $delete_db = FALSE)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_bool($delete_db) && is_numeric($userid));
        $kk = array();
        foreach ($this->qq as $k => $q) {
            if ($q->is_equal($cmd, $arg)) {
                $kk[] = $k;
            }
        }
        if (empty($kk)) {
            return FALSE;
        }

        foreach ($kk as $k) {
            if (!$this->qq[$k]->match_userid($userid)) {
                throw new exception('Not allowed', ERR_ACCESS_DENIED);
            }
            $item = $this->qq[$k];
            unset($this->qq[$k]);
            if ($delete_db === TRUE) {
                $status = QUEUE_CANCELLED;
                update_queue_status($db, $item->get_dbid(), $status);
            }
        }
        $this->update_all_download_position($db);

        return TRUE;
    }


    public function get_preview_action()
    {
        foreach ($this->qq as $q) {
            if ($q->get_preview() && !$q->is_paused()) {
                return $q;
            }
        }

        return FALSE;
    }

    public function delete(DatabaseConnection $db, $action_id, $userid, $delete_db = FALSE)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_bool($delete_db) && is_numeric($action_id) && is_numeric($userid));
        $kk = NULL;
        foreach ($this->qq as $k => $q) {
            if ($action_id == $q->get_id()) {
                $kk = $k;
                break;
            }
        }
        if ($kk === NULL) {
            return FALSE;
        }
        if (!$this->qq[$kk]->match_userid($userid)) {
            throw new exception ('Not allowed', ERR_ACCESS_DENIED);
        }
        $item = $this->qq[$kk];
        if ($delete_db === TRUE) {
            $status = QUEUE_CANCELLED;
            update_queue_status($db, $item->get_dbid(), $status);
        }
        unset($this->qq[$kk]);
        $this->update_all_download_position($db);

        return TRUE;
    }

    public function pause(DatabaseConnection $db, $action_id, $do_pause, $userid)// $do_pause == true then pause, else {  continue (unpause)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_bool($do_pause) && is_numeric($action_id) && is_numeric($userid));
        $status = $do_pause ? QUEUE_PAUSED : QUEUE_QUEUED;
        foreach ($this->qq as $q) {
            if ($action_id == $q->get_id()) {
                $rv = $q->pause($do_pause, $userid);
                $cmd = $q->get_command();
                update_queue_status ($db, $q->get_dbid(), $status, NULL, NULL, NULL, $do_pause);
                if (compare_command($cmd, urdd_protocol::COMMAND_DOWNLOAD) || compare_command($cmd, urdd_protocol::COMMAND_DOWNLOAD_ACTION)) {
                    $dlid = $q->get_args();
                    update_dlinfo_status($db, $do_pause ? DOWNLOAD_PAUSED : DOWNLOAD_QUEUED, $dlid);
                }

                return TRUE;
            }
        }
        throw new exception('Item not found', ERR_ITEM_NOT_FOUND);
    }

    public function pause_cmd(DatabaseConnection $db, $cmd, $arg, $do_pause, $userid)// $do_pause == true then pause, else {  continue (unpause)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_bool($do_pause) && is_numeric($userid));
        $cnt = 0;
        $status = $do_pause ? QUEUE_PAUSED : QUEUE_QUEUED;
        foreach ($this->qq as $q) {
            if ($q->is_equal($cmd, $arg)) {
                try {
                    $rv = $q->pause($do_pause, $userid);
                    $cnt++;
                    update_queue_status ($db, $q->get_dbid(), $status, NULL, NULL, NULL, $do_pause);
                    if (compare_command($cmd, urdd_protocol::COMMAND_DOWNLOAD) || compare_command($cmd, urdd_protocol::COMMAND_DOWNLOAD_ACTION)) {
                        $dlid = $q->get_args();
                        update_dlinfo_status($db, $do_pause ? DOWNLOAD_PAUSED : DOWNLOAD_QUEUED, $dlid);
                    }

                } catch (exception $e) {
                    ;
                }
            }
        }
        if ($cnt == 0) {
            throw new exception('Item not found',ERR_ITEM_NOT_FOUND);
        }
    }

    public function pause_all(DatabaseConnection $db, $do_pause, $userid)// $do_pause == true then pause, else {  continue (unpause)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_bool($do_pause) && is_numeric($userid));
        $status = $do_pause ? QUEUE_PAUSED : QUEUE_QUEUED;
        foreach ($this->qq as $q) {
            try {
                $q->pause($do_pause, $userid);
                $cmd = $q->get_command();
                update_queue_status ($db, $q->get_dbid(), $status, NULL, NULL, NULL, $do_pause);
                if (compare_command($cmd, urdd_protocol::COMMAND_DOWNLOAD) ||compare_command($cmd, urdd_protocol::COMMAND_DOWNLOAD_ACTION)) {
                    $dlid = $q->get_args();
                    update_dlinfo_status($db, $do_pause ? DOWNLOAD_PAUSED : DOWNLOAD_QUEUED, $dlid);
                }

            } catch (exception $e) {
                ;
            }
        }
    }

    protected function add_prio(action $item, DatabaseConnection $db, $increase_counter=TRUE)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_bool($increase_counter));
        if (($this->max_size > 0) && ($this->size() >= $this->max_size)) {
            throw new exception ('Queue full', ERR_QUEUE_FULL);
        }
        if ($item->max_exceeded(self::MAX_REQUEUE_COUNT)) {
            update_queue_status($db, $item->get_dbid(), QUEUE_CANCELLED);
            write_log('Item queued too often, task cancelled: ' . "{$item->get_command()} {$item->get_args()}", LOG_NOTICE);

            return FALSE;
        }
        $item->inc_counter();
        if ($increase_counter === TRUE) { // false if we cancel a thread and push it back to the queue
            echo_debug('pushing on the queue', DEBUG_SERVER);
            $dbid = insert_queue_status($db, $item->get_id(), "{$item->get_command()} {$item->get_args()}", QUEUE_QUEUED, $item->get_command_code(), $item->get_userid(), 0, '', $item->get_priority());
            if ($dbid !== FALSE) {
                $item->set_dbid($dbid);
                echo_debug("Pushing on the queue $dbid", DEBUG_SERVER);
            } else {
                throw new exception_queue_failed('Queueing failed');
            }
        } else {
            $dbid = $item->get_dbid();
        }

        $prio = $item->get_priority();
        $status = $item->is_paused() ? QUEUE_PAUSED : QUEUE_QUEUED;
        update_queue_status ($db, $dbid, $status, NULL, NULL, NULL, $item->is_paused());

        $temp_ql = $temp_qm = array();
        foreach ($this->qq as $q) {
            if ($q->get_priority() <= $prio) {
                $temp_ql[] = $q;
            } else {
                $temp_qm[] = $q;
            }
        }
        $temp_ql[] = $item;
        $this->qq = array_merge($temp_ql, $temp_qm);
        $this->update_all_download_position($db);

        return $dbid;
    }

    public function top($mayhave_nntp=TRUE, array $not_these=array(), $mayhave_db_intensive= TRUE)
    {
        assert(is_bool($mayhave_nntp));
        if ($this->is_empty()) {
            return FALSE;
        }
        if ($mayhave_nntp === TRUE) {
            $first = NULL;
            foreach ($this->qq as $q) {
                if ($q->is_paused() === FALSE) {
                    if (in_array($q->get_id(), $not_these)) {
                        continue; // skip the ones we already tried
                    }
                    if ($mayhave_db_intensive === FALSE && $q->db_intensive()) {
                        continue; // skip, if it is a db intensive job and we may not start one
                    }
                    if ($q->need_nntp() === TRUE) { // find the first with a nntp connection

                        return $q;
                    } elseif ($first === NULL) {
                        $first = $q;
                    }
                }
            }

            return (($first !== NULL) ? $first : FALSE);
        } else {
            foreach ($this->qq as $q) {
                if (($q->need_nntp() === FALSE) && ($q->is_paused() === FALSE) && ($mayhave_db_intensive === TRUE || $q->db_intensive() === FALSE)) {
                    return $q;
                }
            }
        }

        return FALSE;
    }

    public function set_priority(DatabaseConnection $db, $action_id, $userid, $priority)
    {
        assert (is_numeric($userid));

        return $this->set_priorities($db, array($action_id), $userid, $priority);
    }

    public function set_priorities(DatabaseConnection $db, array $action_ids, $userid, $priority)
    {
        assert(is_numeric($priority) && is_numeric($userid));
        $kk = array();
        foreach ($this->qq as $k => $q) {
            if (in_array($q->get_id(), $action_ids)) {
                $q->set_priority($priority, $userid);
                update_queue_priority($db, $q->get_dbid(), $priority);
                $kk[] = $k;
            }
        }
        if ($kk === array()) {
            throw new exception('Item not found', ERR_ITEM_NOT_FOUND);
        }
        usort($this->qq, array('queue', 'prio_sort'));
        $this->update_all_download_position($db);

        return TRUE;
    }

    private static function prio_sort($a, $b)
    {
        $p1 = $a->get_priority();
        $p2 = $b->get_priority();

        return (($p1 == $p2) ? 0 : ($p1 < $p2 ? -1 : 1));
    }

    public function reset_temp_failed_server($server_id)
    {
        foreach ($this->qq as $k => $q) {
            $q->remove_tried_server($server_id);
        }
    }
} //queue
