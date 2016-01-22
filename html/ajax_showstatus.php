<?php
/*
 *  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2014-05-30 00:49:17 +0200 (vr, 30 mei 2014) $
 * $Rev: 3077 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showstatus.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathajss = realpath(dirname(__FILE__));

require_once "$pathajss/../functions/ajax_includes.php";

try {
    $type = get_request('type', 'normal');
    if (!in_array($type, array('quick', 'disk', 'activity', 'icon'))) {
        throw new exception($LN['error_unknowntype']);
    }
    $root_prefs = load_config($db);
    $startup_perc = get_config($db, 'urdd_startup', NULL, TRUE);

    init_smarty();
    // First: Basic stats.
    // can we connect?
    try {
        $uc = new urdd_client($db, $root_prefs['urdd_host'], $root_prefs['urdd_port'], $userid);
        $isconnected = $uc->is_connected();
    } catch (exception $e) {
        $isconnected = FALSE;
    }
    if ($type == 'quick' || $type == 'icon') {
        $counter = 0;
        if ($isconnected) {
            $sql = 'count("ID") as "counter" FROM queueinfo WHERE "status" = :status';
            $res = $db->select_query($sql, array(':status' => QUEUE_RUNNING));
            $counter = isset($res[0]['counter']) ? $res[0]['counter'] : 0;
        }
        $smarty->assign('counter', $counter);
    } elseif ($type == 'disk') {
        if ($isconnected) {
            $diskspace = $uc->diskfree('h');
            $disk_perc = $uc->diskfree('p1');
            $nodisk_perc = 100 - $disk_perc;
            $smarty->assign(array(
                'diskfree' => $diskspace[0] . ' ' . $diskspace[1], 
                'diskused' => $diskspace[4] . ' ' . $diskspace[5],
                'disktotal' => $diskspace[2] . ' ' . $diskspace[3],
                'disk_perc' => $disk_perc,
                'nodisk_perc' => $nodisk_perc));
        }
    } elseif ($type == 'activity') {
        $tasks = array();
        if ($isconnected) {
            // Second: Current jobs.
            $sql = '"description", max("progress") AS "progress", min("ETA") AS "ETA", min("command_id") AS "command_id", count("ID") AS "counter" ' 
                . 'FROM queueinfo WHERE "status" = :status GROUP BY "description"';
            $res = $db->select_query($sql, array(':status'=>QUEUE_RUNNING));
            if ($res === FALSE) {
                $res = array();
            }
            $like = $db->get_pattern_search_command('LIKE');

            foreach ($res as $row) {
                $task = command_description($db, $row['description']);
                $sql = "min(\"ETA\") AS \"ETA\" FROM queueinfo WHERE \"description\" $like :desc AND \"ETA\" > :eta ";
                $res2 = $db->select_query($sql, array(':desc'=>$row['description'], ':eta'=>0));
                $ETA = isset($res2[0]['ETA']) ? $res2[0]['ETA'] : '';
                $row['task'] = $task[0];
                $row['args'] = $task[1];
                $row['type'] = $task[3];
                $dlid = $task[2];
                $progress = $row['progress'] = min(100, $row['progress']);
                $command_id = $row['command_id'];

                // We're gonna use $row as the array containing all data:
                $row['niceeta'] = -1;
                $row['target'] = '';

                if ($progress < 100 && $ETA != 0) {
                    $row['niceeta'] = readable_time($ETA, 'fancy');
                }

                // Uniquify:
                $arrayid = $row['task'] . $row['args'] . $dlid;

                // $tasks are previously added items, $row is current item.
                // If there's a previous item, it might be merged with this one if they are identical.

                // Create or Overwrite the item:
                $tasks[$arrayid] = $row;
            }
            unset($res);
            $cnt = count($tasks);
            $input_arr = array(':preview' => 2, ':hidden' => 0);
            $sql = '"name", "size", "groupid", "ID", "status", "done_size" FROM downloadinfo WHERE "preview" = :preview AND "hidden" = :hidden';
            if (!$isadmin) {
                $input_arr[':userid'] = $userid;
                $sql .= ' AND "userid"=:userid ';
            }
            $sql .= ' ORDER BY "start_time" DESC';

            $res = $db->select_query($sql, $input_arr);
            if ($res === FALSE) {
                $res = array();
            }
            $previews = array();
            foreach ($res as $row) {
                $preview['name'] = $row['name'];
                list($size, $suffix) = format_size($row['size'], 'h', $LN['byte_short'], 1024);
                $preview['size'] = $size . $suffix;
                list($donesize, $donesuffix) = format_size($row['done_size'], 'h', $LN['byte_short'], 1024);
                $preview['donesize'] = $donesize . $donesuffix;
                $preview['group_id'] = $row['groupid'];
                $preview['dlid'] = $row['ID'];
                $preview['binary_id'] = 0; // where does this come from?
                switch ($row['status']) {
                    case DOWNLOAD_READY:         $cat = $LN['transfers_status_ready']; break;
                    case DOWNLOAD_STOPPED:
                    case DOWNLOAD_QUEUED:        $cat = $LN['transfers_status_queued']; break;
                    case DOWNLOAD_ACTIVE:        $cat = $LN['transfers_status_active']; break;
                    case DOWNLOAD_FINISHED:      $cat = $LN['transfers_status_finished']; break;
                    case DOWNLOAD_CANCELLED:     $cat = $LN['transfers_status_cancelled']; break;
                    case DOWNLOAD_PAUSED:        $cat = $LN['transfers_status_paused']; break;
                    case DOWNLOAD_COMPLETE:      $cat = $LN['transfers_status_complete']; break;
                    case DOWNLOAD_SHUTDOWN:
                    case DOWNLOAD_RAR_FAILED :
                    case DOWNLOAD_PAR_FAILED :
                    case DOWNLOAD_CKSFV_FAILED :
                    case DOWNLOAD_ERROR:
                    case DOWNLOAD_FAILED:        $cat = $LN['transfers_status_dlfailed']; break;
                    default :                    $cat = $LN['transfers_status_unknown']; break;
                }
                unset($res);
                $preview['status'] = $cat;
                $previews[] = $preview;
            }

            $smarty->assign(array (
                'tasks'	=> $tasks,
                'previews'=> $previews,
                'counter'=>	$cnt)
            );
        }
    }

    $uc->disconnect();
    $smarty->assign(array(
        'startup_perc' => $startup_perc,
        'isconnected' => $isconnected,
        'isadmin' => $isadmin,
        'type' => $type));

    $contents = $smarty->fetch('ajax_showstatus.tpl');
    return_result(array('contents' => $contents, 'connected' => $isconnected));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
