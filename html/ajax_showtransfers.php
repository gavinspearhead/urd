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
 * $LastChangedDate: 2014-04-27 21:55:06 +0200 (zo, 27 apr 2014) $
 * $Rev: 3032 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showtransfers.php 3032 2014-04-27 19:55:06Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathajt = realpath(dirname(__FILE__));

require_once "$pathajt/../functions/ajax_includes.php";

$_SESSION['transfers'] = $active_tab = get_request('active_tab', 'downloads');

class download_info
{
    public $status;
    public $name;
    public $size;
    public $comment;
    public $startdate;
    public $progress;
    public $speed;
    public $ETA;
    public $linkview;
    public $dlid;
    public $username;
}

class upload_info
{
    public $status;
    public $name;
    public $size;
    public $startdate;
    public $progress;
    public $speed;
    public $ETA;
    public $linkview;
    public $postid;
    public $username;
}

function calculate_speed($size, $remain, $ETA)
{
    global $LN;

    $speed = $size * $remain / 100 / $ETA;	// 4 GB * 20% / ETA -> bytes per second
    $speed /= 1024;		// KB/s
    $speed *= 1.05;		// Compensate for overhead in transmission
    $speed = floor($speed);
    $speed .= ' K' . $LN['byte_short'] . '/s';

    return $speed;
}

function get_upload_status(DatabaseConnection $db, $userid, $isadmin)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_POST | urd_modules::URD_CLASS_USENZB, FALSE, 'P', $userid, TRUE);
    $infoarray_upload = array();
    $input_arr = array();
    $search = get_request('search', '');
    $qsearch = '';
    $like = $db->get_pattern_search_command('LIKE');

    if ($search != '') { 
        $qsearch = " AND \"subject\" $like :search ";
        $input_arr[':search'] = "%$search%";
    }
    if ($isadmin) {
        // Admins can see any upload
        $sql_up = '* FROM postinfo WHERE 1=1 ' . $qsearch . ' ORDER BY "status" ASC, "id" DESC';
    } else {
        $sql_up = '* FROM postinfo WHERE "userid"= :userid ' . $qsearch . ' ORDER BY "status" ASC, "id" DESC';
        $input_arr[':userid'] = $userid;
    }
    if (urd_modules::check_module_enabled($db, urd_modules::URD_CLASS_POST)) {
        $res_up = $db->select_query($sql_up, $input_arr);
        if ($res_up === FALSE) {
            $res_up = array();
        }
        // parse upload-
        foreach ($res_up as $row) {
            $dlname = $row['subject'];
            $postid = $row['id'];
            $group = group_name($db, $row['groupid']);
            $size = $row['size'];
            $status = $row['status'];
            $nzb = $row['nzb_file'];
            $username = get_username($db, $row['userid']);
            $start_time = $row['start_time'];
            $dest = $row['tmp_dir'];

            // Get more information for this download (when it's in progress:)
            $sql = '* FROM queueinfo WHERE "description" = :post_action OR '
                . ' ("description" = :post AND "status" NOT IN (:qf1, :qr1)) OR '
                . ' ("description" = :start_post AND "status" NOT IN (:qf2, :qr2))';
            $res3 = $db->select_query($sql, array(
                ':post_action'=> get_command(urdd_protocol::COMMAND_POST_ACTION) . ' ' . $postid,
                ':post' => get_command(urdd_protocol::COMMAND_POST) . ' ' . $postid,
                ':start_post' => get_command(urdd_protocol::COMMAND_START_POST) . ' ' . $postid,
                ':qf1' => QUEUE_FINISHED,
                ':qf2' => QUEUE_FINISHED,
                ':qr1' => QUEUE_REMOVED,
                ':qr2' => QUEUE_REMOVED,
            ));
            if ($res3 === FALSE || count($res3) == 0) {
                continue;
            }

            //$starttime = time() + 3600; // Should always be in the future
            $stoptime = 0; // Should always be in the past
            $maxperc = 0;
            $minperc = 100;
            $qstatus = '';
            $ETA = 0;
            foreach ($res3 as $queue) {
                $qstatus = $queue['status'];
                if ($qstatus !== QUEUE_CANCELLED) {
                    $maxperc = max($maxperc, $queue['progress']);
                }
                $stoptime = max($stoptime, $queue['lastupdate']);
                if ($queue['ETA'] > 0) {
                    $ETA = ($ETA <= 0) ? $queue['ETA'] : min($ETA, $queue['ETA']);
                }
            }
            unset($res3);
            $dltime = $stoptime - $start_time;
            $fETA = ($ETA > 0) ? readable_time($ETA, 'fancy') : '';
            if ($fETA == '0' || $fETA == '?') {
                $fETA = '';
            }

            $percentage = $maxperc;
            $remain = 100 - $percentage;

            // "Calculate" download speed:
            $speed = '';
            if ($ETA > 0) {
                $speed = calculate_speed($size, $remain, $ETA);
            }

            // Friendly status name:
            switch ($status) {
                case POST_READY:      $cat = 'ready'; break;
                case POST_QUEUED:     $cat = 'queued'; break;
                case POST_ACTIVE:     $cat = 'active'; break;
                case POST_FINISHED:   $cat = 'finished'; break;
                case POST_CANCELLED:  $cat = 'cancelled'; break;
                case POST_PAUSED:     $cat = 'paused'; break;
                case POST_STOPPED:    $cat = 'stopped'; break;
                case POST_SHUTDOWN:   $cat = 'shutdown'; break;
                case POST_ERROR:      $cat = 'error'; break;
                case POST_RARRED:     $cat = 'rarred'; break;
                case POST_PARRED:     $cat = 'par2ed'; break;
                case POST_YYENCODED:  $cat = 'yyencoded'; break;
                case POST_YYENCODE_FAILED: $cat = 'yyencodefailed'; break;
                case POST_RAR_FAILED: $cat = 'rarfailed'; break;
                case POST_PAR_FAILED: $cat = 'par2failed'; break;
                default:              $cat = 'unknown'; break;
            }

            $info = new upload_info();
            $info->status = $cat;
            $info->name = $dlname;
            $info->ETA = $fETA;
            $info->startdate = time_format($start_time);
            $info->progress = $percentage;
            $info->speed = $speed;
            $info->remain = $remain;
            $info->postid = $postid;
            $info->username = $username;
            $info->destination = $dest;
            $info->nzb = $nzb;
            $info->raw_size = $size;

            list($_size, $suffix) = format_size($size, 'h', $LN['byte_short'], 1024, 1);
            $info->size = $_size . ' ' . $suffix;
            // Cleanup:
            if ($status != POST_ACTIVE) {
                $info->ETA = '';
                $info->speed = '';
            }
            $infoarray_upload[$cat]['items'][] = $info;
            if (isset($infoarray_upload[$cat]['raw_size'])) {
                $infoarray_upload[$cat]['raw_size'] += $size;
            } else {
                $infoarray_upload[$cat]['raw_size'] = $size;
            }

        }
    }
    $_infoarray_upload = array();
    foreach($infoarray_upload as $cat => $data) {
        $_infoarray_upload[$cat] = $data;
        $size = 0;
        foreach ($data['items'] as $d) {
            $size += $d->raw_size;
        }
        list($_size, $_suffix) = format_size($size, 'h' , $LN['byte_short'], 1024, 1);
        $_infoarray_upload[$cat]['size'] = $_size;
        $_infoarray_upload[$cat]['suffix'] = $_suffix;
    }

    return $_infoarray_upload;
}

function get_download_status(DatabaseConnection $db, $userid, $isadmin)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_DOWNLOAD | urd_modules::URD_CLASS_POST | urd_modules::URD_CLASS_USENZB, FALSE, '', $userid, TRUE);
    $search = get_request('search', '');
    $qsearch = '';
    $input_arr = array();
    $like = $db->get_pattern_search_command('LIKE');
    if ($search != '') { 
        $qsearch = " \"name\" $like :search AND ";
        $input_arr[':search'] = "%$search%";
    }
    if ($isadmin) {
        // Admins can see any download
        $sql_dl = '* FROM downloadinfo WHERE ' . $qsearch . ' "preview"=:preview ORDER BY "status" ASC, "position" ASC, "start_time" DESC, "ID" DESC';
    } else {
        $sql_dl = '* FROM downloadinfo WHERE ' . $qsearch . ' "userid"=:userid AND "preview"=:preview ORDER BY "status" ASC, "position" ASC, "start_time" DESC, "ID" DESC';
        $input_arr[':userid'] = $userid;
    }
    $input_arr[':preview'] = download_types::NORMAL;
    // Get download info:
    

    $infoarray_download = array();
    if (urd_modules::check_module_enabled($db, urd_modules::URD_CLASS_DOWNLOAD)) {
        $res_dl = $db->select_query($sql_dl, $input_arr);
        if ($res_dl === FALSE) {
            $res_dl = array();
        }
        // parse downloads
        foreach ($res_dl as $row) {
            $dlname = $row['name'];
            $dlid = $row['ID'];
            $dest = $row['destination'];
            $size = $row['size'];
            $done_size = $row['done_size'];
            $status = $row['status'];
            $userid = $row['userid'];
            $start_time = $row['start_time'];
            $comment = $row['comment'];
            if ($comment != '' && isset($LN[$comment])) {
                $comment = $LN[$comment];
            } else {
                $comment = '';
            }

            $stoptime = 0; // Should always be in the past
            $maxperc = 0;
            $ETA = 0;
            $minperc = 100;
            $qstatus = '';

            // Get more information for this download (when it's in progress:)
            $sql = '* FROM queueinfo WHERE "description"= :desc1 OR ("description"= :desc2 AND "status" NOT IN (:q1,:q2))';
            $res3 = $db->select_query($sql, array(
                ':desc1' => get_command(urdd_protocol::COMMAND_DOWNLOAD_ACTION) . ' ' . $dlid, 
                ':desc2' => get_command(urdd_protocol::COMMAND_DOWNLOAD) . ' ' . $dlid, 
                ':q1' => QUEUE_FINISHED, 
                ':q2' => QUEUE_REMOVED
            ));
            if ($res3 === FALSE || count($res3) == 0) {
                continue;
            }

            foreach ($res3 as $queue) {
                $qstatus = $queue['status'];
                if ($qstatus == QUEUE_RUNNING) { // if there is a threat that is still running we consider the entire dl to be running
                    $status = DOWNLOAD_ACTIVE;
                } 
                $maxperc = max($maxperc, $queue['progress']);
                $stoptime = max($stoptime, $queue['lastupdate']);
                if ($queue['ETA'] > 0) {
                    $ETA = ($ETA == 0) ? $queue['ETA'] : min($ETA, $queue['ETA']);
                }
            }
            unset($res3);
            $dltime = $stoptime - $start_time;
            $fETA = ($ETA > 0) ? readable_time($ETA, 'fancy') : '';
            if ($fETA == '0' || $fETA == '?') {
                $fETA = '';
            }

            $percentage = $maxperc;
            $remain = 100 - $percentage;

            // "Calculate" download speed:
            $speed = '';
            if ($ETA > 0) {
                $speed = calculate_speed($size, $remain, $ETA);
            }

            // Friendly status name:
            switch ($status) {
                case DOWNLOAD_READY:         $cat = 'ready'; break;
                case DOWNLOAD_QUEUED:        $cat = 'queued'; break;
                case DOWNLOAD_ACTIVE:        $cat = 'active'; break;
                case DOWNLOAD_FINISHED:      $cat = 'finished'; break;
                case DOWNLOAD_CANCELLED:     $cat = 'cancelled'; break;
                case DOWNLOAD_PAUSED:        $cat = 'paused'; break;
                case DOWNLOAD_STOPPED:       $cat = 'stopped'; break;
                case DOWNLOAD_SHUTDOWN:      $cat = 'shutdown'; break;
                case DOWNLOAD_ERROR:         $cat = 'error'; break;
                case DOWNLOAD_COMPLETE:      $cat = 'complete'; break;
                case DOWNLOAD_RAR_FAILED:    $cat = 'rarfailed'; break;
                case DOWNLOAD_PAR_FAILED:    $cat = 'par2failed'; break;
                case DOWNLOAD_CKSFV_FAILED:  $cat = 'cksfvfailed'; break;
                case DOWNLOAD_FAILED:        $cat = 'dlfailed'; break;
                default:                     $cat = 'unknown'; break;
            }
            $info = new download_info();
            $info->status = $cat;
            $info->name = $dlname;
            $info->ETA = $fETA;
            $info->startdate = time_format($start_time); //$startdate . ' '. $starttime;
            $info->progress = $percentage;
            $info->speed = $speed;
            $info->comment = $comment;
            $info->destination = $dest;
            $info->nfo_link = '';
            if ($status == DOWNLOAD_FINISHED) {
                $nfo_file = glob($dest . DIRECTORY_SEPARATOR . '*.nfo', GLOB_NOSORT);
                if (isset($nfo_file[0])) {
                    $info->nfo_link = $nfo_file[0];
                }
            }
            $info->dlid = $dlid;
            $info->username = get_username($db, $userid);
            $info->raw_size = $size;
            list($_size, $suffix) = format_size($size, 'h', $LN['byte_short'], 1024, 1);
            $info->size = $_size . ' ' . $suffix;
            $info->raw_done_size = $done_size;
            list($_done_size, $done_suffix) = format_size($done_size, 'h', $LN['byte_short'], 1024, 1);
            if ($_done_size == 0) {
                $done_suffix = '';
            }
            $info->done_size = $_done_size . ' ' . $done_suffix;
            // Cleanup:
            if ($status != DOWNLOAD_ACTIVE) {
                $info->ETA = '';
                $info->speed = '';
            }
            $infoarray_download[$cat]['items'][] = $info;
            if (isset($infoarray_download[$cat]['raw_size'])) {
                $infoarray_download[$cat]['raw_size'] += $size;
            } else {
                $infoarray_download[$cat]['raw_size'] = $size;
            }
        }
    }
    $_infoarray_download = array();
    foreach($infoarray_download as $cat => $data) {
        $_infoarray_download[$cat] = $data;
        $size = 0;
        $done_size = 0;
        foreach ($data['items'] as $d) {
            $size += $d->raw_size;
            $done_size += $d->raw_done_size;
        }
        list($_size, $_suffix) = format_size($size, 'h' , $LN['byte_short'], 1024, 1);
        $_infoarray_download[$cat]['size'] = $_size;
        $_infoarray_download[$cat]['suffix'] = $_suffix;
        list($_done_size, $_done_suffix) = format_size($done_size, 'h' , $LN['byte_short'], 1024, 1);
        $_infoarray_download[$cat]['done_size'] = $_done_size;
        $_infoarray_download[$cat]['done_suffix'] = $_done_suffix;
    }
        
    return $_infoarray_download;
}

try {
    init_smarty('', 0);
    $urdd_online = check_urdd_online($db);
    $poster = urd_user_rights::is_poster($db, $userid);
    if ($active_tab == 'uploads' && ($poster || $isadmin)) {
        $infoarray_upload = get_upload_status($db, $userid, $isadmin);
        $smarty->assign(array(
            'infoarray_upload'=> $infoarray_upload,
            'infoarray_upload_size'=> count($infoarray_upload)));
        $filename = 'ajax_showuploads.tpl';
    } else { // if active_tab == downloads)
        $active_tab = 'downloads';
        $infoarray_download = get_download_status($db, $userid, $isadmin);
        $smarty->assign(array(
            'infoarray_download'=> $infoarray_download,
            'infoarray_download_size'=> count($infoarray_download)));
        $filename = 'ajax_showdownloads.tpl';
    }

    if (!isset($_SESSION['post_hide_status'])) {
        set_post_status();
    }

    if (!isset($_SESSION['transfer_hide_status'])) {
        set_down_status();
    }

    $show_viewfiles = urd_modules::check_module_enabled($db, urd_modules::URD_CLASS_VIEWFILES);
    $smarty->assign(array('transfer_hide_status'=> $_SESSION['transfer_hide_status'],
        'post_hide_status'=>    $_SESSION['post_hide_status'],
        'active_tab'=>          $active_tab,
        'maxstrlen'=>		    $prefs['maxsetname']/2,
        'poster'=>         	    $poster?1:0,
        'isadmin'=>         	$isadmin?1:0,
        'urdd_online'=>    	    (int) $urdd_online,
        'offline_message'=>     $LN['enableurddfirst']));
    $contents = $smarty->fetch($filename);
    return_result(array('contents' => $contents));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
