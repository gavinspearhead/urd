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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_admin_log.php 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathaadl = realpath(dirname(__FILE__));

require_once "$pathaadl/../functions/html_includes.php";
require_once "$pathaadl/../functions/pref_functions.php";

verify_access($db, NULL, TRUE, '', $userid, FALSE);

define ('DEFAULT_LOG_LINES', 1000);
define ('MAX_LOG_LINES', 10000);


function my_log_cmp($a, $b)
{
    global $internal_sort, $sd; /// xxx nasty!
    $sort = $internal_sort;

    return (strcasecmp($a[$sort], $b[$sort])) * $sd;
}

try {
    $sort = get_post('sort_order', 'date');
    $sort_dir = $sort_dir_orig = get_post('sort_dir', 'desc');
    $search = utf8_decode(trim(get_post('search', '')));
    $lines = get_post('lines', DEFAULT_LOG_LINES);
    $default_log_level = get_config($db, 'log_level');
    $min_log_level = get_post('log_level', $default_log_level);
    if (!is_numeric($lines)) {
        throw new exception($LN['error_invalidlinescount']);
    }

    if ($lines < 0) {
        $lines = DEFAULT_LOG_LINES;
    } elseif ($lines > MAX_LOG_LINES) {
        $lines = MAX_LOG_LINES;
    }

    if (!in_array($sort, array ('date', 'time', 'level', 'msg'))) {
        $sort = 'date';
    }

    if ($sort == 'date') {
        $internal_sort = 'timestamp';
    } elseif ($sort == 'time') {
        $internal_sort = 'timestamp2';
    } elseif ($sort == 'level') {
        $internal_sort = 'level_int';
    } else {
        $internal_sort = $sort;
    }

    if (!in_array($sort_dir, array ('asc', 'desc'))) {
        $sort_dir = 'desc';
    }

    $sd = ($sort_dir == 'asc') ? 1 : -1;

    $error_msg = '';
    $year = date('Y');
    $log_file = $config['urdd_logfile'];
    $logerror = URD_NOERROR;
    $log = read_last_lines($log_file, $lines, $logerror, $search, $min_log_level);
    $log_array = array();
    if (!is_array($log)) {
        throw new exception($LN['log_notopenlogfile']);
    } else {
        if (!is_array($log) && $logerror == URD_NOERROR) {
            $logerror = URD_UNKNOWNERROR;
        } else {
            foreach ($log as $line) {
                $blocks = explode (' ', $line, 7);
                if (count($blocks) != 7) {
                    continue;
                }
                $b['month'] = $blocks[0];
                $b['day'] = $blocks[1];
                $b['timestamp'] = strtotime("$year-{$blocks[0]}-{$blocks[1]} {$blocks[2]}");
                $b['time'] = date($LN['timeformat'], $b['timestamp']);
                $b['date'] = date($LN['dateformat2'], $b['timestamp']);
                $b['hostname'] = $blocks[3];
                $b['proc_name'] = rtrim($blocks[4], ':');
                $b['timestamp2'] = "{$blocks[2]} $year-{$blocks[0]}-{$blocks[1]}";
                $b['level'] = $blocks[5];
                $b['level_int'] = array_search($blocks[5], $log_str);
                $b['msg'] = $blocks[6];
                $log_array[] = $b;
            }
            usort($log_array, 'my_log_cmp');
        }
        switch ($logerror) {
            case URD_NOERROR:
                break;
            case URD_FILENOTFOUND:
                throw new exception($LN['log_nofile'] . ': ' . $log_file);
            case URD_SEEKERROR:
                throw new exception($LN['log_seekerror']);
            case URD_UNKNOWNERROR:
            default:
                throw new exception($LN['log_unknownerror']);
        }
    }

    init_smarty();

    $smarty->assign(array(
        'logs'=> $log_array,
        'search'=> $search,
        'lines'=> $lines,
        'logfile'=> $log_file,
        'log_str'=> $log_str,
        'log_level'=> $min_log_level,
        'sort'=>	$sort,
        'sort_dir'=> $sort_dir_orig));

    $contents = $smarty->fetch('ajax_admin_log.tpl');

    return_result(array('contents' => $contents));

} catch ( exception $e) {
    return_result(array('error' => $e->getMessage()));
}
