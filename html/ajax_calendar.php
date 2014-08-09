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
 * $LastChangedDate: 2014-05-19 23:50:53 +0200 (ma, 19 mei 2014) $
 * $Rev: 3043 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_calendar.php 3043 2014-05-19 21:50:53Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathcal = realpath(dirname(__FILE__));

require_once "$pathcal/../functions/ajax_includes.php";

function calendar($month, $year)
{
    $first_day = date('w', strtotime("1-$month-$year"));
    $leapyear = date('L', strtotime("1-$month-$year"));

    $months = array(
        1=>31,
        2=>28,
        3=>31,
        4=>30,
        5=>31,
        6=>30,
        7=>31,
        8=>31,
        9=>30,
        10=>31,
        11=>30,
        12=>31
    );

    $dates = $week = array();
    $c = 0;
    for ($i = 1 - $first_day; $i <= ($months[$month] + ($month == 2 ? $leapyear : 0)); $i++) {
        $c++;
        if ($i >= 0) {
            $week[] = $i;
        }
        if ($i < 0) {
            $week[] = 0;
        }
        if ($c % 7 == 0) {
            $dates[] = $week;
            $week = array();
        }
    }
    if ($week != array()) {
        $dates[] = $week;
    }

    return $dates;
}

function next_month($month, $year)
{
    if ($month == 12) {
        return array(1, $year + 1);
    } else {
        return array($month + 1, $year);
    }
}

function previous_month($month, $year)
{
    if ($month == 1) {
        return array(12, $year - 1);
    } else {
        return array($month - 1, $year);
    }
}

try {

$cmd = get_request('cmd', '');
switch ($cmd) {
case 'show_calendar':
    $date = getdate();

    $timestamp = get_request('timestamp', '');
    $month = get_request('month', NULL);
    if (!is_numeric($month) || $month < 1 || $month > 12) {
        $month = NULL;
    }
    $minute = get_request('minute', NULL);
    if (!is_numeric($minute) || $minute < 1 || $minute > 59) {
        $minute = NULL;
    }
    $hour = get_request('hour', NULL);
    if (!is_numeric($hour) || $hour < 1 || $hour > 23) {
        $hour = NULL;
    }
    $year = get_request('year', NULL);
    if (!is_numeric($year)) {
        $year = NULL;
    }
    $timestamp = strtotime($timestamp);
    if ($timestamp !== FALSE && $timestamp < time()) {
        while ($timestamp < time()) {
            $timestamp += 3600 * 24;
        }
    }
    if ($year === NULL || $month === NULL) {
        if ($timestamp === FALSE) {
            $month = date('n');
            $year = date('Y');
        } else {
            $month = date ('n', $timestamp);
            $year = date('Y', $timestamp);
        }
    }
    if ($hour === NULL || $minute === NULL) {
        if ($timestamp === FALSE) {
            $now = time() + 60;
        } else {
            $now = $timestamp;
        }
        $minute = date('i', $now);
        $hour = date('G', $now);
    }

    $today = 0;
    if ($date['mon'] == $month && $date['year'] == $year) {
        $today = $date['mday'];
    }
    if ($timestamp === FALSE) {
        $selected_day = $date['mday'];
        $show_day = 0;
    } else {
        $selected_day = $show_day = date('j', $timestamp);
    }
    $dates = calendar($month, $year);
    $smarty->assign('dates',		    $dates);
    $smarty->assign('today',		    $today);
    $smarty->assign('selected_day',	    $selected_day);
    $smarty->assign('show_day',	        $show_day);
    $smarty->assign('year', 		    $year);
    $smarty->assign('hour',	            $hour);
    $smarty->assign('minute', 		    $minute);
    $smarty->assign('month',		    $month);
    $smarty->assign('next_month',		next_month($month, $year));
    $smarty->assign('previous_month',	previous_month($month, $year));
    $contents = $smarty->fetch('ajax_calendar.tpl');
   return_result(array('contents' => $contents, 'hour' => $hour, 'minute'=>$minute));
    break;
default:
    throw new exception($LN['error_invalidaction']);
    break;
}
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
