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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: schedule.php 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathschd = realpath(dirname(__FILE__));
require_once "$pathschd/../functions/autoincludes.php";
require_once "$pathschd/../functions/functions.php";

class job
{
    private $action;
    private $time;
    private $recurrence;
    private $dbid; // ID in database
    public function __construct(action $action, $time, $recurrence=0)
    {
        assert (is_numeric($time));
        $this->action = $action;
        $this->time = (int) $time;
        $this->recurrence = (int) $recurrence;
        $this->dbid = (int) 0;
    }

    public function get_action()
    {
        return $this->action;
    }
    public function get_time()
    {
        return $this->time;
    }
    public function get_recurrence()
    {
        return $this->recurrence;
    }
    public function set_id($id)
    {
        assert(is_numeric($id));
        $this->dbid = (int) $id;
    }
    public function get_id()
    {
        return $this->dbid;
    }
}//job

class schedule
{
    private $jobs;

    public function __construct()
    {
        $this->jobs = array();
    }
    public function size ()
    {
        return count($this->jobs);
    }
    public function add(DatabaseConnection $db, job $job)
    {
        $repeat = $job->get_recurrence();
        $stime = $job->get_time();
        $action = $job->get_action();
        $userid = $action->get_userid();
        $cmd = $action->get_command() . ' ' . $action->get_args();
        if ( !($repeat > 0)) {
            $repeat = NULL;
        }

        $id = add_schedule($db, $cmd, $stime, $repeat, $userid);

        $job->set_id($id);
        $this->jobs[] = $job;
    }
    public function unschedule_all(DatabaseConnection $db, $userid)
    {
        assert(is_numeric($userid));
        $kk = array();
        foreach ($this->jobs as $k=>$j) {
            $id = $j->get_id();
            if ($id !== 0 && ($j->get_action()->get_userid() == $userid || $userid = user_status::SUPER_USERID)) {
                $kk[] = $k;
                delete_schedule($db, $id);
            }
        }
        foreach ($kk as $k) {
            unset($this->jobs[$k]);
        }

        return count($kk) != 0;
    }
    public function get_jobs()
    {
        $jobs = array();
        foreach ($this->jobs as $j) {
            $action = $j->get_action();
            $job['id'] = $action->get_id();
            $job['username'] = $action->get_username();
            $job['userid'] = $action->get_userid();
            $job['command'] = $action->get_command();
            $job['args'] = $action->get_args();
            $job['recurrence'] = $j->get_recurrence();
            $job['time'] = $j->get_time();
            $jobs[] = $job;
        }

        return $jobs;
    }
    public function get_first_ready_job(DatabaseConnection $db)
    {
        $ft = FALSE;
        $t = time();
        foreach ($this->jobs as $k => $j) {
            if ($j->get_time() <= $t) {
                $kk = $k;
                $ft = TRUE;
                break;
            }
        }
        if ($ft) {
            $j = $this->jobs[$kk];
            $id = $j->get_id();
            if ($id !== 0) {
                delete_schedule($db, $id);
            }
            unset($this->jobs[$kk]);

            return $j;
        } else {
            return FALSE;
        }
    }
    public function get_first_timeout()
    {
        $t = NULL;
        foreach ($this->jobs as $j) {
            if ($t === NULL || $j->get_time() <= $t) {
                $t = $j->get_time();
            }
        }

        return $t;
    }

    public function unschedule(DatabaseConnection $db, $userid, $id)
    {
        assert(is_numeric($userid));
        assert(is_numeric($id));
        $kk = NULL;
        foreach ($this->jobs as $k => $j) {
            if ($j->get_action()->get_id() == $id) {
                if (!$j->get_action()->match_userid($userid)) {
                    throw new exception ('Not allowed', ERR_ACCESS_DENIED);
                }
                $kk = $k;
                break;
            }
        }
        if ($kk !== NULL) {
            $id = $this->jobs[$kk]->get_id();
            if ($id !== 0) {
                delete_schedule($db, $id);
            }
            unset($this->jobs[$kk]);

            return TRUE;
        }

        return FALSE;
    }

    public function unschedule_cmd(DatabaseConnection $db, $userid, $cmd, $arg)
    {
        assert(is_numeric($userid));
        $kk = array();
        foreach ($this->jobs as $k => $j) {
            $a = $j->get_action();
            if (strcasecmp($a->get_command(),$cmd) == 0  && (strcasecmp($a->get_args(), $arg) == 0 || strcasecmp($arg, '__all') == 0)
                && $a->match_userid($userid)) {
                $kk[] = $k;
            }
        }
        foreach ($kk as $k) {
            $id = $this->jobs[$k]->get_id();
            if ($id !== 0) {
                delete_schedule($db, $id);
            }
            unset($this->jobs[$k]);
        }

        return (count($kk) != 0);
    }
}
