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
 * $LastChangedDate: 2014-05-26 01:07:16 +0200 (ma, 26 mei 2014) $
 * $Rev: 3054 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: stats.php 3054 2014-05-25 23:07:16Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathstat = realpath(dirname(__FILE__));

require_once "$pathstat/../functions/html_includes.php";

$title = $LN['stats_title'];

$years = get_stats_years($db, $userid, $isadmin);

if (!empty($years)) {
    $thisyear = max($years);
} else {
    $thisyear = date('Y');
}

$tab = get_request('tab', '');
if ($tab == 'supply') {
    $thisyear = 'supply';
} elseif ($tab == 'spots_details') {
    $thisyear = 'spots_details';
} elseif ($tab == 'supply_details') {
    $thisyear = 'supply_details';
}

init_smarty($title, 1);
$smarty->assign(array(
    'years'=>       $years,
    'thisyear'=>    $thisyear,
    'tab'=>         $tab));

$smarty->display('statistics.tpl');

// END
