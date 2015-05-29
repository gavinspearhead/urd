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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: admin_tasks.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathadt = realpath(dirname(__FILE__));

require_once "$pathadt/../functions/html_includes.php";

verify_access($db, NULL, TRUE, '', $userid, FALSE);

$add_menu = array (
    'actions'=>
    array(
        new menu_item2 ('continueall', 'admincontinue', urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2 ('pauseall', 'adminpause', urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2 ('cancelall', 'control_cancelall', urd_modules::URD_CLASS_GENERIC, '', 'command'),
        new menu_item2 ('cleanall', 'admincleandb', urd_modules::URD_CLASS_GENERIC, '', 'command'),
    )
);

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

$allstatus = array(''=>'');
foreach ($_allstatus as $s) {
    $allstatus[$s] = $LN['transfers_status_' . strtolower($s)];
}

asort($allstatus);
init_smarty($LN['tasks_title'], 1, $add_menu);

$smarty->assign(array(
    'allstatus' =>    $allstatus,
    'alltimes' =>	  $times));
$smarty->display('admin_tasks.tpl');
