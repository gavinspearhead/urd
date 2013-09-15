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
 * $Id: show_functions.php 2911 2013-09-03 21:50:58Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathsf = realpath(dirname(__FILE__));

require_once "$pathsf/../functions/autoincludes.php";
require_once "$pathsf/../functions/defines.php";
require_once "$pathsf/../config.php";
require_once "$pathsf/../functions/functions.php";
require_once "$pathsf/urdd_command.php";
require_once "$pathsf/urdd_protocol.php";
require_once "$pathsf/urdd_error.php";
require_once "$pathsf/../functions/urd_log.php";

function show_time($output_type)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    switch (strtolower($output_type)) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><show></show>');
        $xml->addChild('time', date('r'));
        $status = $xml->asXML();
        break;
    default:
        $status = date('r') . "\n";
        break;
    }

    return $status;
}

function do_show_servers(server_data $server_data, $output_type)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $resp = "Servers:\n";
    $c = 1;
    $servers = $server_data->get_servers();
    switch ($output_type) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><show></show>');
        $xml->addChild('servers');
        break;
    default:
        break;
    }
    foreach ($servers as $s) {
        $pref = $s['preferred'] ? '*' : ' ';
        $id = $s['id'];
        $hostname = $s['hostname'];
        $port = $s['port'];
        $maxt = $s['max_threads'];
        $freet = $s['free_threads'];
        $posting = $s['posting'] ? 'posting' : 'noposting';
        $prio = $s['priority'];
        $enabled = ($prio == 0) ? 'disabled' : 'enabled';
        switch ($output_type) {
        case 'xml':
            $server = $xml->servers->addChild('server');
            $server->addChild('preferred', $s['preferred'] ? 'TRUE' : 'FALSE');
            $server->addChild('id', $id);
            $server->addChild('port', $port);
            $server->addChild('hostname', $hostname);
            $server->addChild('priority', $prio);
            $server->addChild('max_threads', $maxt);
            $server->addChild('free_threads', $freet);
            $server->addChild('posting', $s['posting'] ? 'TRUE' : 'FALSE' );
            $server->addChild('enabled', ($prio != 0) ? 'TRUE' : 'FALSE');
            break;
        default:
            $resp .= " #$c. id: $id h:$hostname:$port p:$prio max:$maxt free:$freet $enabled $posting $pref\n";
            break;
        }

        $c++;
    }
    $sd = $server_data->get_slot_data();
    switch ($output_type) {
    case 'xml':
            $xml->addChild('total');
            $xml->total->addChild('total_nntp', $sd['max_total_nntp_threads']);
            $xml->total->addChild('free_nntp', $sd['free_nntp_slots']);
            $xml->total->addChild('total_threads',$sd['max_total_threads']);
            $xml->total->addChild('free_total',$sd['free_total_slots'] );
            $xml->total->addChild('db_intensive',$sd['max_db_intensive_threads'] );
            $xml->total->addChild('free_db_intensive',$sd['free_db_intensive_slots']);
            $resp = $xml->asXML();

        break;
    default:
        $resp .= "\n";
        $resp .= "Total nntp: {$sd['max_total_nntp_threads']}\n"
            . "Free nntp: {$sd['free_nntp_slots']}\n"
            . "Total threads: {$sd['max_total_threads']}\n"
            . "Free total: {$sd['free_total_slots']}\n"
            . "DB intensive: {$sd['max_db_intensive_threads']}\n"
            . "Free DB intensive: {$sd['free_db_intensive_slots']}\n";
        break;
    }

    return $resp;
}

function do_show_modules(DatabaseConnection $db, $output_type)
{
    $urd_classes = urd_modules::get_urd_classes();
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $modules = urd_modules::get_urd_module_config(get_config($db, 'modules'));
    ksort($modules);
    $resp = '';
    $mods = array();
    switch ($output_type) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?' . '><show></show>');
        $xml->addChild('modules');
        break;
    default:
        break;
    }

    foreach ($urd_classes as $module) {
        switch ($output_type) {
        case 'xml':
            $mod = $xml->modules->addChild('module');
            $mod->addChild('id', $module);
            $mod->addChild('status', $modules[$module] ? 'TRUE' : 'FALSE' );
            break;
        default:
            $mods[] = "$module: " . ($modules[$module] ? 'On' : 'Off') . "\n";
            break;
        }
    }
    switch ($output_type) {
    case 'xml':
        $resp = $xml->asXML();
        break;
    default:
        natsort($mods);
        foreach ($mods as $mod) {
            $resp .= $mod;
        }
        break;
    }

    return $resp;
}

function show_uptime($output_type)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    global $start_time;
    $uptime = time () - $start_time;
    $sec = $uptime % 60;
    $min = ($uptime / 60) % 60;
    $hrs = ($uptime / 3600) % 24;
    $days = (int) ($uptime / (3600 * 24));
    switch ($output_type) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?' . '><show></show>');
        $up = $xml->addChild('uptime');
        $up->addChild('uptime_secs', $uptime);
        $up->addChild('seconds', $sec);
        $up->addChild('minutes', $min);
        $up->addChild('hours', $hrs);
        $up->addChild('days', $days);
        $status = $xml->asXML();
        break;
    default:
        $status = sprintf("%u days %u:%02u:%02u\n", $days, $hrs, $min, $sec);
        break;
    }

    return $status;
}

function do_show_queue(server_data $servers, $output_type)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $c = 1;
    switch ($output_type) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?' . '><show></show>');
        $queue = $xml->addChild('queue');
        break;
    default:
        $resp = "Queue:\n";
        break;
    }

    $actions = $servers->get_queue();
    foreach ($actions as $q) {
        $stat = $q['pause'] ? 'paused' : 'active';
        $qt = microtime(TRUE) -($q['queue_time']);
        switch ($output_type) {
        case 'xml':
            $action = $queue->addChild('action');
            $action->addChild('id', $q['id']);
            $action->addChild('username', $q['username']);
            $action->addChild('priority', $q['priority']);
            $action->addChild('command', $q['command']);
            $action->addChild('arguments', $q['args']);
            $action->addChild('status', $stat);
            $action->addChild('time', $qt);
            break;
        default:
            $resp .= " #$c. id:{$q['id']} u:{$q['username']} p:{$q['priority']} cmd:{$q['command']} {$q['args']} $stat qt:$qt\n";
            break;
        }
        $c++;
    }
    switch ($output_type) {
    case 'xml':
        $resp = $xml->asXML();
        break;
    default:
        break;
    }

    return $resp;
}

function do_show_newsgroups(DatabaseConnection $db, $subscribed, $output_type)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $like = ($subscribed) ? ' AND "active"=\'' . NG_SUBSCRIBED . "'" : '';

    $query = "* FROM groups WHERE 1=1 $like ORDER BY \"name\"";
    $res = $db->select_query($query);
    if ($res === FALSE) {
        return FALSE;
    }
    $c = 1;

    switch ($output_type) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?' . '><show></show>');
        $ngs = $xml->addChild('newsgroups');
        break;
    default:
        $resp = "Newsgroups:\n";
        break;
    }

    foreach ($res as $row) {
        $name = $row['name'];
        $expire = $row['expire'];
        $postcount = $row['postcount'];
        $lastupdated = $row['last_updated'];
        $id = $row['ID'];
        switch ($output_type) {
        case 'xml':
            $gr = $ngs->addChild('group');
            $gr->addChild('id', $id);
            $gr->addChild('expire', $expire);
            $gr->addChild('name', $name);
            $gr->addChild('postcount', $postcount);
            $gr->addChild('lastupdated', $lastupdated);
            break;
        default:
            $resp .= " #$c id:$id exp:$expire cnt:$postcount lu:$lastupdated ng:$name\n";
            $c++;
            break;
        }

    }
    switch ($output_type) {
    case 'xml':
        $resp = $xml->asXML();
        break;
    default:
        break;
    }

    return $resp;
}

function do_show_feeds(DatabaseConnection $db, $output_type)
{
    $sql = '* FROM rss_urls ORDER BY "name"';
    $res = $db->select_query($sql);
    if ($res === FALSE) {
        return FALSE;
    }
    $c = 1;

    switch ($output_type) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><show></show>');
        $fds = $xml->addChild('rss_feeds');
        break;
    default:
        $resp = "RSS Feeds:\n";
        break;
    }

    foreach ($res as $row) {
        $name = $row['name'];
        $expire = $row['expire'];
        $postcount = $row['feedcount'];
        $lastupdated = $row['last_updated'];
        $url = $row['url'];
        $id = $row['id'];
        switch ($output_type) {
        case 'xml':
            $fd = $fds->addChild('group');
            $fd->addChild('id', $id);
            $fd->addChild('expire', $expire);
            $fd->addChild('name', $name);
            $fd->addChild('url', $url);
            $fd->addChild('postcount', $postcount);
            $fd->addChild('lastupdated', $lastupdated);
            break;
        default:
            $resp .= " #$c id:$id exp:$expire cnt:$postcount lu:$lastupdated ng:$name url:$url\n";
            break;
        }
        $c++;
    }
    switch ($output_type) {
    case 'xml':
        $resp = $xml->asXML();
        break;
    default:
        break;
    }

    return $resp;
}

function do_show_users(conn_list $conn_list, $output_type)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $c = 1;
    switch ($output_type) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?' . '><show></show>');
        $usrs = $xml->addChild('users');
        break;
    default:
        $resp = "Users:\n";
        break;
    }

    $users = $conn_list->get_usernames();
    foreach ($users as $u) {
        switch ($output_type) {
        case 'xml':
            $usr = $usrs->addChild('user');
            $usr->addChild('user', $u);
            break;
        default:
            $resp .= " #$c.  $u\n";
            break;
        }

        $c++;
    }
    switch ($output_type) {
    case 'xml':
        $resp = $xml->asXML();
        break;
    default:
        break;
    }

    return $resp;
}

function do_show_threads(DatabaseConnection $db, server_data $servers, $output_type)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $c = 1;
    switch ($output_type) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><show></show>');
        $thrds = $xml->addChild('threads');
        break;
    default:
        $resp = "Users:\n";
        break;
    }

    $resp = 'Threads:' . "\n";
    $threads = $servers->get_threads();
    foreach ($threads as $t) {
        $server = $t['server'];
        $server_name = get_server_name($db, $server);
        $stat = $t['paused'] ? 'paused' : 'active';
        $qt = microtime(TRUE) - ($t['queue_time']);
        $st = microtime(TRUE) - ($t['start_time']);
        switch ($output_type) {
        case 'xml':
            $thrd = $thrds->addChild('thread');
            $thrd->addChild('id', $t['id']);
            $thrd->addChild('pid', $t['pid']);
            $thrd->addChild('username', $t['username']);
            $thrd->addChild('servername', $server_name);
            $thrd->addChild('server', $server);
            $thrd->addChild('command', $t['command']);
            $thrd->addChild('arguments', $t['args']);
            $thrd->addChild('status', $stat);
            $thrd->addChild('queuetime', $qt);
            $thrd->addChild('starttime', $st);
            break;
        default:
            $resp .= " #$c.  id:{$t['id']} pid:{$t['pid']} u:{$t['username']} s:{$server_name} ($server)"
                .  " cmd:{$t['command']} {$t['args']} $stat qt:$qt st:$st\n";
            break;
        }
        $c++;
    }

    switch ($output_type) {
    case 'xml':
        $resp = $xml->asXML();
        break;
    default:
        break;
    }

    return $resp;
}

function do_show_jobs(server_data $servers, $output_type)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $c = 1;

    switch ($output_type) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><show></show>');
        $js = $xml->addChild('jobs');
        break;
    default:
        $resp = "Jobs:\n";
        break;
    }
    $jobs = $servers->get_jobs();
    foreach ($jobs as $j) {
        $time = strftime('%D %T', $j['time']);
        $recur = $j['recurrence'];
        if ($recur !== NULL) {
            $rec_str = "(repeat every $recur sec) ";
        } else {
            $rec_str = '';
        }
        switch ($output_type) {
        case 'xml':
            $jb = $js->addChild('job');
            $jb->addChild('id', $j['id']);
            $jb->addChild('username', $j['username']);
            $jb->addChild('command', $j['command']);
            $jb->addChild('arguments', $j['args']);
            $jb->addChild('time', $time);
            $jb->addChild('recurrence', $recur);
            break;
        default:
            $resp .= " #$c.  @ $time $rec_str{$j['id']} u:{$j['username']} cmd:{$j['command']} {$j['args']}\n";
            break;
         }
        $c++;
    }
    switch ($output_type) {
    case 'xml':
        $resp = $xml->asXML();
        break;
    default:
        break;
    }

    return $resp;
}

function do_show_config($output_type)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    global $config;
    $resp = '';
    switch ($output_type) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><show></show>');
        $cfg = $xml->addChild('config');
        break;
    default:
        $resp = "Jobs:\n";
        break;
    }

    foreach ($config as $elem => $value) {
        $v = is_bool($value) ? ($value ? 'TRUE' : 'FALSE') : $value;
        switch ($output_type) {
        case 'xml':
            $cfg->addChild($elem, $v);
            break;
        default:
            $resp .= "$elem : $v\n";
        }
    }
    switch ($output_type) {
    case 'xml':
        $resp = $xml->asXML();
        break;
    default:
        break;
    }

    return $resp;
}

function do_show_version($output_type)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $version = urd_version::get_version();
    $status = urd_version::get_status();
    switch (strtolower($output_type)) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><show></show>');
        $xml->addChild('version', $version);
        $xml->addChild('status', $status);
        $resp = $xml->asXML();
        break;
    default:
        $resp = "Version: $version ($status)\n";
        break;
    }

    return $resp;
}

function do_show_load($output_type)
{
    $load = sys_getloadavg();
    switch (strtolower($output_type)) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><show></show>');
        $xml->addChild('load_1', $load[0]);
        $xml->addChild('load_5', $load[1]);
        $xml->addChild('load_15', $load[2]);
        $resp = $xml->asXML();
        break;
    default:
        $resp = "Load:\n";
        $resp .= "1' {$load[0]}\n";
        $resp .= "5' {$load[1]}\n";
        $resp .= "15' {$load[2]}\n";
        break;
    }

    return $resp;
}

function do_show_tests($output_type)
{
    global $test_results;
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    switch (strtolower($output_type)) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><show></show>');
        $xml = $test_results->get_all_as_xml($xml);
        $resp = $xml->asXML();
        break;
    default:
        $resp = "Test results:\n";
        $resp .= $test_results->get_all_as_string();
        break;
    }

    return $resp;
}

function do_show_status(server_data $servers, conn_list $conn_list, $output_type)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    global $start_time;
    $uptime = time () - $start_time;
    $sec = $uptime % 60;
    $min = ($uptime / 60) % 60;
    $hrs = ($uptime / 3600) % 24;
    $days = (int) ($uptime / (3600 * 24));
    $qs = $servers->queue_size();
    $ts = $servers->threads_size();
    $ss = $servers->schedule_size();
    $us = $conn_list->size();

    switch (strtolower($output_type)) {
    case 'xml':
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?' . '><show></show>');
        $xml->addChild('uptime', $uptime);
        $xml->addChild('queued', $qs);
        $xml->addChild('running', $ts);
        $xml->addChild('scheduled', $ss);
        $xml->addChild('users', $us);
        $resp = $xml->asXML();
        break;
    default:
    $resp = "Uptime: $days days $hrs:$min:$sec\n";
    $resp .= "Queued: $qs\n";
    $resp .= "Running: $ts\n";
    $resp .= "Scheduled: $ss\n";
    $resp .= "Users: $us\n";
    }

    return $resp;
}

function do_show(array $arg_list, conn_list $conn_list, server_data $servers, DatabaseConnection $db)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $resp = '';
    $output_type = 'text';
    foreach ($arg_list as $arg) {
        switch (strtolower($arg)) {
        case 'all':
            $resp .= do_show_threads($db, $servers, $output_type);
            $resp .= do_show_queue($servers, $output_type);
            $resp .= do_show_jobs($servers, $output_type);
            $resp .= do_show_users($conn_list, $output_type);
            $resp .= do_show_servers($servers, $output_type);
            break;
        case 'output:text':
            $output_type = 'text';
            break;
        case 'output:xml':
            $output_type = 'xml';
            break;
        case 'feeds':
            $resp .= do_show_feeds($db, $output_type);
            break;
        case 'modules':
            $resp .= do_show_modules($db, $output_type);
            break;
        case 'version':
            $resp .= do_show_version($output_type);
            break;
        case 'users':
            $resp .= do_show_users($conn_list, $output_type);
            break;
        case 'queue':
            $resp .= do_show_queue($servers, $output_type);
            break;
        case 'threads':
            $resp .= do_show_threads($db,$servers, $output_type);
            break;
        case 'jobs':
            $resp .= do_show_jobs($servers, $output_type);
            break;
        case 'servers':
            $resp .= do_show_servers($servers, $output_type);
            break;
        case 'newsgroups':
            $resp .= do_show_newsgroups($db, FALSE, $output_type);
            break 2;
            break;
        case 'subscribed':
            $resp .= do_show_newsgroups($db, TRUE, $output_type);
            break 2;
        case 'config':
            $resp .= do_show_config($output_type);
            break 2;
        case 'tests':
            $resp .= do_show_tests($output_type);
            break 2;
        case 'load':
            $resp .= do_show_load($output_type);
            break;
        case 'uptime':
            $resp .= show_uptime($output_type);
            break;
        case 'time':
            $resp .= show_time($output_type);
            break;
        case 'status':
            $resp .= do_show_status($servers, $conn_list, $output_type);
            break;
        default :
            write_log('Error_unknown action', LOG_WARNING);

            return FALSE;
            break;
        }
    }

    return $resp;
}
