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
 * $LastChangedDate: 2014-06-07 14:53:28 +0200 (za, 07 jun 2014) $
 * $Rev: 3081 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_adminjobs.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';

$pathadt = realpath(dirname(__FILE__));

require_once "$pathadt/../functions/ajax_includes.php";
require_once "$pathadt/../functions/pref_functions.php";

verify_access($db, NULL, TRUE, '', $userid, TRUE);
try {
    $sort = get_request('sort', 'command');
    $sort_dir = strtolower(get_request('sort_dir', 'desc'));

    if (!in_array($sort, array('command', 'at_time', 'interval', 'username'))) {
        $sort = 'command';
    }
    if (!in_array($sort_dir, array('asc', 'desc'))) {
        $sort_dir = 'desc';
    }

    $qry = "*, users.\"name\" AS \"username\" FROM schedule LEFT JOIN users ON users.\"ID\" = schedule.\"userid\" ORDER BY \"$sort\" $sort_dir";
    $res = $db->select_query($qry);
    $jobs = array();
    if ($res === FALSE) {
        $res = array();
    }

    foreach ($res as $row) {
        $job['time'] = time_format($row['at_time']);
        $job['period'] = readable_time($row['interval'], 'largest');
        $description = command_description($db, $row['command']);
        $task_short = $description[0];
        $task_arg = $description[1];
        $job['user'] = $row['username'];
        $task_long = '';
        $job['cmd'] = $row['command'];
        $job['task'] = $task_short;
        $job['arg'] = $task_arg;
        $jobs[] = $job;
    }

    $urdd_online = check_urdd_online($db);
    init_smarty();
    $smarty->assign(array(
        'alljobs'=>	    $jobs,
        'sort'=>	    $sort,
        'sort_dir'=>    $sort_dir,
        'urdd_online'=> (int) $urdd_online));
    $contents = $smarty->fetch('ajax_adminjobs.tpl');
    return_result(array('contents' => $contents));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
