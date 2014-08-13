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
 * $Id: ajax_admincontrol.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';
$pathadctl = realpath(dirname(__FILE__));

require_once "$pathadctl/../functions/ajax_includes.php";

verify_access($db, NULL, TRUE, '', $userid, TRUE);


function parse_server_info($xml)
{
    $xml = new SimpleXMLElement($xml);
    $data = array();
    foreach ($xml->servers->server as $a) {
        $row['hostname'] = (string) $a->hostname;
        $row['port'] = (string) $a->port;
        $row['priority'] = (string) $a->priority;
        $row['max_threads'] = (string) $a->max_threads;
        $row['free_threads'] = (string) $a->free_threads;
        $row['preferred'] = (string) $a->preferred;
        $row['posting'] = (string) $a->posting;
        $row['enabled'] = (string) $a->enabled;
        $row['id'] = (string) $a->id;
        $data[] = $row;
    }
    return $data;
}

function parse_server_totals($xml)
{
    $xml = new SimpleXMLElement($xml);
    $data = array();
    $data['total_nntp'] = (string) $xml->total->total_nntp;
    $data['total_threads'] = (string) $xml->total->total_threads;
    $data['free_nntp'] = (string) $xml->total->free_nntp;
    $data['free_total'] = (string) $xml->total->free_total;
    $data['db_intensive'] = (string) $xml->total->db_intensive;
    $data['free_db_intensive'] = (string) $xml->total->free_db_intensive;

    return $data;
}

function parse_jobs_info($xml)
{
    $xml = new SimpleXMLElement($xml);
    $data = array();
    foreach ($xml->jobs->job as $a) {
        $row['username'] = (string) $a->username;
        $row['command'] = (string) $a->command;
        $row['arguments'] = (string) $a->arguments;
        $row['time'] = (string) $a->time;
        $row['recurrence'] = (string) $a->recurrence;
        $row['id'] = (string) $a->id;
        $data[] = $row;
    }

    return $data;
}

function parse_queue_info($xml)
{
    $xml = new SimpleXMLElement($xml);
    $data = array();
    foreach ($xml->queue->action as $a) {
        $row['username'] = (string) $a->username;
        $row['priority'] = (string) $a->priority;
        $row['command'] = (string) $a->command;
        $row['arguments'] = (string) $a->arguments;
        $row['status'] = (string) $a->status;
        $row['time'] = (string) $a->time;
        $row['id'] = (string) $a->id;
        $data[] = $row;
    }

    return $data;
}

function parse_threads_info($xml)
{
    $xml = new SimpleXMLElement($xml);
    $data = array();
    foreach ($xml->threads->thread as $a) {
        $row['servername'] = (string) $a->servername;
        $row['server'] = (string) $a->server;
        $row['username'] = (string) $a->username;
        $row['command'] = (string) $a->command;
        $row['arguments'] = (string) $a->arguments;
        $row['status'] = (string) $a->status;
        $row['queuetime'] = (string) $a->queuetime;
        $row['starttime'] = (string) $a->starttime;
        $row['id'] = (string) $a->id;
        $row['pid'] = (string) $a->pid;
        $data[] = $row;
    }

    return $data;
}

function parse_load_info($xml)
{
    $xml = new SimpleXMLElement($xml);
    $data = array();
    $data['load_1'] = (string) $xml->load_1;
    $data['load_5'] = (string) $xml->load_5;
    $data['load_15'] = (string) $xml->load_15;

    return $data;
}

try {
    $prefs = load_config($db);
    $uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);

    $disable_urdland = ($prefs['extset_group'] == '') ? 1 : 0;
    if ($uc->is_connected()) {
        $isconnected = 1;
        $queue_info = $uc->show('queue', 'xml');
        $queue_info = parse_queue_info($queue_info[1]);
        $jobs_info = $uc->show('jobs', 'xml');
        $jobs_info = parse_jobs_info($jobs_info[1]);
        $threads_info = $uc->show('threads', 'xml');
        $threads_info = parse_threads_info($threads_info[1]);
        $servers_info = $uc->show('servers', 'xml');
        $servers_totals = parse_server_totals($servers_info[1]);
        $servers_info = parse_server_info($servers_info[1]);
        $load_info = $uc->show('load', 'xml');
        $load_info = parse_load_info($load_info[1]);
        $uptime_info = $uc->uptime();
        $diskspace = $uc->diskfree('h');
        $disk_perc = $uc->diskfree('p1');
        $nodisk_perc = 100 - (int) $disk_perc;
        unset($load_info[0], $servers_info[0]);
    } else {
        $diskspace = $disk_perc = $nodisk_perc = $isconnected = 0;
        $load_info = array (1 => '', 2 => '', 3 => '');
        $queue_info = $jobs_info = $threads_info = $servers_info = $servers_totals = array();
        $uptime_info = '';
    }

    $_SESSION['control_status'] = $control_status = get_session('control_status', 0);

    init_smarty('', 0);
    $smarty->assign('isconnected',      $isconnected);
    $smarty->assign('disable_urdland',  $disable_urdland);
    $smarty->assign('queue_info',       $queue_info);
    $smarty->assign('uptime_info',      $uptime_info);
    $smarty->assign('threads_info',     $threads_info);
    $smarty->assign('jobs_info',        $jobs_info);
    $smarty->assign('load_info',        $load_info);
    $smarty->assign('control_status',   $control_status);
    $smarty->assign('servers_info',     $servers_info);
    $smarty->assign('servers_totals',   $servers_totals);
    $smarty->assign('referrer',         'admin_control');
    $smarty->assign('diskfree',		    $diskspace[0] . ' ' . $diskspace[1]);
    $smarty->assign('disk_perc',		$disk_perc);
    $smarty->assign('nodisk_perc',		$nodisk_perc);

    $contents = $smarty->fetch('ajax_admincontrol.tpl');
    return_result(array('contents' => $contents));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
