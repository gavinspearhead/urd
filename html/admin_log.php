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
 * $Id: admin_log.php 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathadl = realpath(dirname(__FILE__));

require_once "$pathadl/../functions/html_includes.php";
require_once "$pathadl/../functions/pref_functions.php";

verify_access($db, NULL, TRUE, '', $userid, FALSE);

define ('DEFAULT_LOG_LINES', 1000);
define ('MAX_LOG_LINES', 10000);

$sort = get_post('sort', 'date');
$sort_dir = $sort_dir_orig = get_post('sort_dir', 'desc');
$search = utf8_decode(trim(get_post('search', '')));
$lines = get_post('lines', DEFAULT_LOG_LINES);
$default_log_level = get_config($db, 'log_level');
$min_log_level = get_post('log_level', $default_log_level);
if (!is_numeric($lines)) {
    $lines = DEFAULT_LOG_LINES;
}

if ($lines < 0) {
    $lines = DEFAULT_LOG_LINES;
} elseif ($lines > MAX_LOG_LINES) {
    $lines = MAX_LOG_LINES;
}

if (FALSE === in_array($sort, array ('date', 'time', 'level', 'msg'))) {
    $sort = 'date';
}

if ($sort == 'date') {
    $internal_sort = 'timestamp';
} elseif ($sort == 'time') {
    $internal_sort = 'timestamp2';
} else {
    $internal_sort = $sort;
}

if (FALSE === in_array($sort_dir, array ('asc', 'desc'))) {
    $sort_dir = 'desc';
}

init_smarty($LN['log_title'], 1);

$smarty->assign(array('search'=> $search,
            'lines' => $lines,
            'log_str' => $log_str,
            'log_level' => $min_log_level,
            'sort'=> $sort,
            'sort_dir' => $sort_dir_orig));

$smarty->display('admin_log.tpl');
