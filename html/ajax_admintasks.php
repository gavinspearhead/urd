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
 * $LastChangedDate: 2014-05-25 00:49:47 +0200 (zo, 25 mei 2014) $
 * $Rev: 3051 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_admintasks.php 3051 2014-05-24 22:49:47Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';

$pathadt = realpath(dirname(__FILE__));

require_once "$pathadt/../functions/ajax_includes.php";
require_once "$pathadt/../functions/pref_functions.php";

verify_access($db, NULL, TRUE, '', $userid, TRUE);


try { 
    $urdd_online = check_urdd_online($db);

    $sort = get_request('sort', 'lastupdate');
    $offset = get_request('offset', 0);
    if (!is_numeric($offset)) {
        $offset = 0;
    }
    $sort_dir = get_request('sort_dir', 'desc');
    $tasksearch = get_request('tasksearch', '');

    $_allstatus = array (
            QUEUE_QUEUED,
            QUEUE_FINISHED,
            QUEUE_FAILED,
            QUEUE_RUNNING,
            QUEUE_PAUSED,
            QUEUE_CANCELLED,
            QUEUE_CRASH,
            QUEUE_REMOVED
            );

    $times = array(
            0   => $LN['all'],
            1   => $LN['since'] . ' 1 ' . $LN['day'],
            2   => $LN['since'] . ' 2 ' . $LN['days'],
            7   => $LN['since'] . ' 1 ' . $LN['week'],
            14  => $LN['since'] . ' 2 ' . $LN['weeks'],
            30  => $LN['since'] . ' 1 ' . $LN['month'],
            60  => $LN['since'] . ' 2 ' . $LN['months'],
            365 => $LN['since'] . ' 1 ' . $LN['year']
            );

    $currentstatus = $status = get_request('status', '');
    $timeval = get_request('time', 0);
    if (!in_array($status, $_allstatus)) {
        $status = $currentstatus = '';
    }
    if (!in_array($timeval, array_keys($times))) {
        $timeval = 1;
    }
    $qstatus = '';
    $input_arr = array();
    if ($status != '') {
        $input_arr[] = $status;
        $qstatus = ' AND "status"=?';
    }

    $qtime = '';
    if ($timeval > 0) {
        $input_arr[] = (string) ( time() - ($timeval * 24 * 60 * 60));
        $qtime = ' AND "lastupdate" >= ?';
    }

    if (!in_array($sort, array('description', 'progress', 'ETA', 'status', 'comment', 'lastupdate', 'starttime'))) {
        $sort = 'lastupdate';
    }

    if (!in_array($sort_dir, array('asc', 'desc'))) {
        $sort_dir = 'desc';
    }

    $perpage = get_maxperpage($db, $userid);
    $sql = "* FROM queueinfo WHERE 1=1 $qstatus $qtime ORDER BY \"$sort\" $sort_dir";
    $res = $db->select_query($sql, $input_arr);

    $tasks = array();
    $cnt = 0;

    if ($res !==  FALSE) {
        foreach ($res as $row) {
            $description = $row['description'];
            $progress = $row['progress'];
            $ETA = $row['ETA'];
            $task['progress'] = $progress;
            $task['urdd_id'] = $row['urdd_id'];
            $task['queue_id'] = $row['ID'];
            $task['status'] = isset($LN['transfers_status_' . strtolower($row['status'])]) ? $LN['transfers_status_' . strtolower($row['status'])] : '?';
            $task['raw_status'] = $row['status'];
            $task['comment'] = $row['comment'];
            $task['added'] = time_format($row['starttime']);
            $task['lastupdated'] = time_format($row['lastupdate']);
            $description = command_description($db, $description);

            $task_short = $description[0];
            $task_arg = $description[1];
            if ($progress == '100' || $ETA == 0) {
                $ETA = '';
            } else {
                $ETA = readable_time($ETA, 'fancy');
            }
            $task_popup =  $task_short . ' ' . $task_arg;
            $task['eta'] = $ETA;
            $task['description'] = $task_short;
            $task['arguments'] = $task_arg;
            $task['task_popup'] = $task_popup;

            if ($tasksearch != '' && !stristr($task_popup, $tasksearch)) {
                continue;
            }
            if ($cnt >= $offset && $cnt < ($offset + $perpage)) {
                $tasks[] = $task;
            }
            $cnt++;
        }
    }

    list($pages, $currentpage, $lastpage) = get_pages($cnt, $perpage, $offset);

    init_smarty('', 0);
    $smarty->assign('alltasks',	        $tasks);
    $smarty->assign('sort',	            $sort);
    $smarty->assign('sort_dir',  	    $sort_dir);
    $smarty->assign('urdd_online',    	(int) $urdd_online);
    $smarty->assign('pages',		    $pages);
    $smarty->assign('currentpage',		$currentpage);
    $smarty->assign('offset',		    $offset);
    $smarty->assign('lastpage',		    $lastpage);

    $contents = $smarty->fetch('ajax_admintasks.tpl');
    return_result(array('contents' => $contents));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
