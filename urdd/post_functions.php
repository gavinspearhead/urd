<?php
/**
/*  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: post_functions.php 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathdf = realpath(dirname(__FILE__));

require_once "$pathdf/../functions/autoincludes.php";
require_once "$pathdf/../functions/defines.php";
require_once "$pathdf/../config.php";
require_once "$pathdf/../functions/functions.php";
require_once "$pathdf/urdd_command.php";
require_once "$pathdf/urdd_protocol.php";
require_once "$pathdf/urdd_error.php";
require_once "$pathdf/../functions/urd_log.php";
require_once "$pathdf/../functions/mail_functions.php";

function update_postinfo(DatabaseConnection $db, $postid, $status=NULL, $tmp_dir=NULL, $file_count= NULL)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($postid));
    $sql = "UPDATE postinfo SET ";
    if ($tmp_dir !== NULL) {
        $db->escape($tmp_dir, TRUE);
        $sql .= "\"tmp_dir\" = $tmp_dir, ";
    }
    if ($status !== NULL) {
        $db->escape($status, TRUE);
        $sql .= "\"status\" = $status, ";
    }
    if ($file_count !== NULL) {
        $db->escape($file_count, TRUE);
        $sql .= "\"file_count\" = $file_count, ";
    }
    $sql = trim($sql, ', ');
    $db->escape($postid, TRUE);
    $sql .= " WHERE \"id\" = $postid ";
    $db->execute_query($sql);
}

function create_rar_files(DatabaseConnection $db, action $item, $postid, $filesize_rar, &$dl_path1, $username, $dir, $name)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($postid));
    $dl_path_basis = get_dlpath($db);
    $id = 'post_' . mt_rand(1000, 9999);
//    $post_path = $dl_path_basis . POST_PATH;
    $dl_path1 = find_unique_name($dl_path_basis, TMP_PATH . $username . DIRECTORY_SEPARATOR, $id);

    $niceval = get_nice_value($db);
    echo_debug("Dir $dl_path1", DEBUG_SERVER);
    $rv = @create_dir($dl_path1, 0775);
    if ($rv === FALSE) {
        $status = QUEUE_FAILED;
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Rar failed');
        update_postinfo_status ($db, POST_RAR_FAILED, $postid, NULL);
        write_log("Failed to create directory $dl_path1", LOG_ERR);
        throw new exception ("Failed to create directory $dl_path1", POST_FAILURE);
    }

    $dir = my_escapeshellarg($dir);
    if ($filesize_rar > 0) {
        $rar_cmd = get_config($db, 'rar_path');
        $rar_cmd = my_escapeshellarg($rar_cmd);
        $rar_file = $dl_path1 . $name . '.rar';
        $rar_file = my_escapeshellarg($rar_file);
        $rar_opt = 'a -ed -inul -idp -m5 -r -ep1 -y'; // get from db
        $rar_size_option =  "-v{$filesize_rar}k" ;

        $cmd_line = "nice -$niceval $rar_cmd $rar_opt $rar_size_option $rar_file $dir/*  > /dev/null 2>&1";
        exec ($cmd_line, $output, $rv);
    }
    if ($rv != 0) {
        $status = QUEUE_FAILED;
        update_postinfo_status ($db, POST_RAR_FAILED, $postid, NULL);
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Rar failed');
        write_log('Creating rar files failed', LOG_ERR);
        throw new exception ('Rar failed', POST_FAILURE);
    }
    copy_files($dir, $dl_path1, array('*.nzb', '*.nfo'), $dl_path_basis);
    $post_status = POST_RARRED;
    update_postinfo($db, $postid, POST_RARRED, $dl_path1);

    return $post_status;
}

function create_par_files(DatabaseConnection $db, action $item, $postid, $recovery_par, $dl_path1, $username, $dir, $name)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($postid));
    $file_list1 = glob($dl_path1 . '*');
    $file_list_par = glob($dl_path1 . '*.par2');
    foreach ($file_list_par as $f) {// clean up possible old shit first
        unlink($f);
    }
    if ($file_list1 === FALSE || empty($file_list1)) {
        $status = QUEUE_FAILED;
        update_postinfo_status ($db, POST_PAR_FAILED, $postid, NULL);
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'No files found');
        throw new exception ('No files found', POST_FAILURE);
    }
    $niceval = get_nice_value($db);
    if ($recovery_par > 0) {
        $par2_cmd = get_config($db, 'unpar_path');
        $par2_cmd = my_escapeshellarg($par2_cmd);
        $par2_file = $dl_path1 . DIRECTORY_SEPARATOR . $name . '.par2';
        $par2_file = my_escapeshellarg($par2_file);
        $cmd_line = "nice -$niceval $par2_cmd c -q -r$recovery_par $par2_file $dl_path1/*  > /dev/null 2>&1";
        exec ($cmd_line, $output, $rv);

        if ($rv != 0) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Par2 failed');
            update_postinfo_status ($db, POST_PAR_FAILED, $postid, NULL);
            throw new exception ('Par2 failed', POST_FAILURE);
        }
    }
    $file_list1 = glob($dl_path1 . '*');

    update_postinfo($db, $postid, POST_PARRED, $dl_path1);

    return $file_list1;
}

function create_yenc_files(DatabaseConnection $db, action $item, $postid, array $file_list1, $username, $dir, $name)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($postid));
    $id = 'post_' . mt_rand(1000, 9999);
    $dl_path_basis = get_dlpath($db);
    $dl_path2 = find_unique_name($dl_path_basis, TMP_PATH . $username . DIRECTORY_SEPARATOR, $id);
    echo_debug("Dir $dl_path2", DEBUG_SERVER);
    $rv = @create_dir($dl_path2, 0775);
    if ($rv === FALSE) {
        $status = QUEUE_FAILED;
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Yenc encoding failed');
        update_postinfo_status ($db, POST_YYENCODE_FAILED, $postid, NULL);
        write_log("Failed to create directory $dl_path2", LOG_ERR);
        throw new exception ("Failed to create directory $dl_path2", POST_FAILURE);
    }

    $yyencode_cmd = get_config ($db, 'yyencode_path');
    $yyencode_opt = get_config ($db, 'yyencode_pars');

    $db->delete_query('post_files', "\"postid\" = '$postid'");
    $rar_cnt = 0;
    foreach ($file_list1 as $rarfile) {
        $rar_cnt++;
        $dl_path3 = $dl_path2 . basename($rarfile) . DIRECTORY_SEPARATOR;
        $rv = @create_dir($dl_path3, 0775);
        if ($rv === FALSE) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Yyencode failed');
            update_postinfo_status ($db, POST_YYENCODE_FAILED, $postid, NULL);
            write_log("Failed to create directory $dl_path3", LOG_ERR);
            throw new exception ("Failed to create directory $dl_path3", POST_FAILURE);
        }
        $yy_path = my_escapeshellarg($dl_path3);
        $yy_rarfile = my_escapeshellarg($rarfile);
        $cmd_line = "$yyencode_cmd $yyencode_opt $yy_path $yy_rarfile > /dev/null 2>&1";
        exec ($cmd_line, $output, $rv);
        if ($rv != 0) {
            $status = QUEUE_FAILED;
            update_postinfo_status ($db, POST_YYENCODE_FAILED, $postid, NULL);
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'YYencode failed');
            throw new exception ('YYencode failed', POST_FAILURE);
        }
        $file_list2 = glob($dl_path3 . '*');
        if ($file_list2 === FALSE || empty($file_list2)) {
            write_log('No Files found', LOG_WARNING);
        }
        $cols = array ('postid', 'filename', 'file_idx', 'rarfile', 'rar_idx', 'status');
        $yy_cnt = 0;
        foreach ($file_list2 as $filename) {
            $yy_cnt++;
            $vals = array($postid, $filename, $yy_cnt, basename($rarfile), $rar_cnt, POST_READY);
            $db->insert_query('post_files', $cols, $vals);
        }
    }
    update_postinfo($db, $postid, POST_YYENCODED, $dl_path2, $rar_cnt);
}

function do_prepare_post(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    mt_srand ();
    $args = $item->get_args();
    if (!is_numeric($args)) {
        $status = QUEUE_FAILED;
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'No post ID given');
        update_postinfo_status ($db, POST_ERROR, $postid, NULL);
        throw new exception ('No post ID given', POST_FAILURE);
    }

    $postid = $args;
    $db->escape($postid);
    $sql = "* FROM postinfo WHERE \"id\" = $postid";
    $res = $db->select_query($sql, 1);
    if ($res === FALSE || $res == array()) {
        $status = QUEUE_FAILED;
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Invalid post ID given');
        update_postinfo_status ($db, POST_ERROR, $postid, NULL);
        throw new exception ('Invalid post ID given', POST_FAILURE);
    }
    $row = $res[0];
    $delete_files = $row['delete_files'];
    $subject = $row['subject'];
    $name = $subject; // better name for rar files ...
    $name = str_replace(' ', '_', $name); // file names don't contain spaces but underscore
    $name = preg_replace('/[^A-Za-z0-9_.]/', '', $name); // replace all fancy characters by nothing

    $username = $item->get_username();
    $dir = $row['src_dir'];
    $size = dirsize($dir);
    update_postinfo_size($db, $postid, $size);
    $post_status = $row['status'];
    $filesize_rar = $row['filesize_rar'];
    $recovery_par = $row['recovery_par'];
    // check dir subdir of dlpath
    if ($post_status < POST_RARRED) {
        $post_status = create_rar_files($db, $item, $postid, $filesize_rar, $dl_path1, $username, $dir, $name);
    } else {
        $dl_path1 = $row['tmp_dir'];
    }

    update_queue_status($db, $item->get_dbid(), NULL , 0, 1, 'RAR complete');

    if ($post_status < POST_PARRED) {
        $file_list1= create_par_files($db, $item, $postid, $recovery_par, $dl_path1, $username, $dir, $name);
    } else {
        $dl_path1 = $row['tmp_dir'];
        $file_list1 = glob($dl_path1 . '*');
    }
    update_queue_status($db, $item->get_dbid(),NULL , 0, 2, 'PAR2 complete');

    if ($post_status < POST_YYENCODED) {
        $file_list1 = create_yenc_files($db, $item, $postid, $file_list1, $username, $dir, $name);
    }
    $status = QUEUE_FINISHED;
    if ($delete_files > 0) {
        rmdirtree($dl_path1, 0, TRUE);
    }

    update_queue_status($db, $item->get_dbid(), $status, 0, 3, 'Complete');
}


function get_files_from_db(DatabaseConnection $db, $postid)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($postid));
    static $lock_array = array('post_files' => 'write'); // for the article table
    $ids = '';
    $filenames = array();

    $sql = "* FROM post_files WHERE \"status\" = '". POST_READY ."'";
    $db->lock($lock_array);
    $res = $db->select_query($sql, POST_BATCH_SIZE);
    if ($res === FALSE) {
        $db->unlock();

        return array(); // where done
    }

    foreach ($res as $row) {
        $ids .= $row['id'] . ',';
        $filenames[] = array('id'=> $row['id'], 'filename'=>$row['filename'], 'rarfile'=>$row['rarfile'], 'id'=>$row['id'], 'rar_idx' => $row['rar_idx'], 'file_idx'=> $row['file_idx']);
    }
    $ids = rtrim($ids, ',');
    $sql = "UPDATE post_files SET \"status\" = '". POST_ACTIVE . "' WHERE \"id\" IN ($ids)";
    $db->execute_query($sql);
    $db->unlock();

    return $filenames;
}


function do_post_batch(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    try {
        $args = $item->get_args();
        $postid = $args;
        $sql = "* FROM postinfo WHERE \"id\" = $postid";
        $res = $db->select_query($sql, 1);
        if ($res === FALSE || $res == array()) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Invalid post ID given');
            throw new exception ('Invalid post ID given', POST_FAILURE);
        }
        $row = $res[0];

        $dbid = $item->get_dbid();
        $group = $row['groupid'];
        $group_name = group_name($db, $group);
        $postername = $row['poster_name'];
        $poster_email = $row['poster_id'];
        $file_count = $row['file_count'];
        $subject = $row['subject'];
       // $name = $subject; // better name for rar files ...

        $stat_id = get_stat_id($db, $postid, TRUE);
        $useragent = urd_version::get_urd_name() . ' ' . urd_version::get_version();
        $server_id = $item->get_active_server();
        $total_ready = get_post_articles_count_status($db, $postid, DOWNLOAD_READY);
        $total_count = get_post_articles_count($db, $postid);
        $done_start = $total_count - $total_ready;
        if ($server_id == 0) {
            $server_id = $item->get_preferred_server();
        }
        $nntp = connect_nntp($db, $server_id);
        $nntp->select_group($group, $code);

        $rarfile_count = get_rar_files($db, $postid);
        $post_status = POST_ACTIVE;
        update_postinfo_status ($db, $post_status, $postid, NULL);
        $header_template = array(
            'from'          => "From: $postername <$poster_email>",
            'newsgroups'    => "Newsgroups: $group_name",
            'subject'       => 'Subject: ' ,
            'user-agent'    => "User-Agent: $useragent",
        );
        $success_count = $failed_count = $done_count = 0;
        $b_time = microtime(TRUE);
        for (;;) {
            $files = get_files_from_db($db, $postid);
            if ($files === array()) {
                break;
            }

            foreach ($files as $a_file) {
                $rarfile = $a_file['rarfile'];
                $rar_idx = $a_file['rar_idx'];
                $file_idx = $a_file['file_idx'];
                $filename = $a_file['filename'];
                if (isset($rarfile_count[$rarfile])) {
                    $rar_max_count = $rarfile_count[$rarfile];
                } else {
                    write_log("Sending wrong file? $rarfile", LOG_WARNING);
                    continue;
                }
                $header = $header_template;
                $header['subject'] .= "\"$subject\" [$rar_idx/$file_count] " . $rarfile . " ($file_idx/$rar_max_count)";
                $header = implode("\r\n", $header) . "\r\n\r\n";
                $article = file_get_contents($filename) . "\r\n";
                echo_debug("Posting $filename", DEBUG_SERVER);
                try {
                    $bytes = strlen($article);
                    $articleid = $nntp->post_article(array($header, $article));
                    $success_count++;
                    update_dlstats($db, $stat_id, $bytes);
                    $art_status = POST_FINISHED;
                    unlink($filename);
                } catch (exception $e) {
                    write_log('Posting article_failed ' . $e->getMessage(), LOG_ERR);
                    $articleid = '';
                    $art_status = POST_FAILED;
                    $failed_count ++;
                }
                $db->escape($articleid);
                $sql = "UPDATE post_files SET \"status\" = '$art_status', \"articleid\"='$articleid' WHERE \"id\" = '{$a_file['id']}'";
                $db->execute_query($sql);
                $sql = "count(*) AS cnt FROM post_files WHERE (\"status\" = '". POST_FINISHED . "' OR \"status\" = '" . POST_FAILED . "') AND \"postid\"='$postid'";
                $rv = $db->select_query($sql);
                if ($res === FALSE) {
                    throw new exception('Post not found?', POST_FAILURE);
                }
                $done_count = $rv[0]['cnt'];
                $remain = $total_count - $done_count;
                $f_time = microtime(TRUE);
                $time_diff = $f_time - $b_time;
                $done_ready = $done_count - $done_start;
                $percentage = ($total_count > 0 ) ? floor(100 * ($done_count / $total_count)) : 0;
                $eta = ($done_count > 0) ? (round(($remain * $time_diff) / $done_ready)) : 0;
                $speed = ($time_diff > 0) ? (round($bytes / $time_diff)) : 0;
                store_ETA($db, $eta, $percentage, $speed, $dbid);
            }
        }
        $nntp->disconnect();
        $status = QUEUE_FINISHED;
        write_log("Posted $success_count article to newsgroup, $failed_count articles failed", LOG_INFO);
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Complete');
    } catch (exception $e) {
        return NNTP_NOT_CONNECTED_ERROR;
    }
}


function restart_post(DatabaseConnection $db, $command, server_data &$servers, $username, $id, $priority=NULL)
{
    assert(is_numeric($id));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $userid = get_userid($db, $username);
    if ($command == urdd_protocol::COMMAND_POST) {
        $item = new action(urdd_protocol::COMMAND_POST, $id, $username, $userid, FALSE);
    } else {
        $item = new action(urdd_protocol::COMMAND_START_POST, $id, $username, $userid, FALSE);
    }
    if ($servers->has_equal($item)) {
        return urdd_protocol::get_response(406);
    }
    $dl_status = POST_ACTIVE;
    $ready_status = POST_READY;
    update_postinfo_status ($db, $ready_status, $id, $dl_status);
    update_post_articles($db, $ready_status, $id, $dl_status);
    if ($item->is_paused()) {
        $status = POST_PAUSED;
        update_postinfo_status ($db, $status, $id);
    }
    echo_debug("Re-starting post $id", DEBUG_SERVER);

    $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
    if ($res === FALSE) {
        throw new exception_queue_failed('Could not queue item');
    }
    $id_str = "[{$item->get_id()}] ";

    return sprintf (urdd_protocol::get_response(210), $id, $id_str);
}


function cleanup_post(DatabaseConnection $db, $postid)
{
    assert(is_numeric($postid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $res = $db->delete_query('post_files', "\"postid\" = '$postid'");
    $sql = "\"tmp_dir\" FROM postinfo WHERE \"id\" = '$postid'";
    $res = $db->select_query($sql);
    if ($res === FALSE || !isset($res[0]['tmp_dir'])) {
        write_log('Could not find setting for tmp dir in post', LOG_ERR);

        return;
    }
    $dir = $res[0]['tmp_dir'];
    rmdirtree($dir, 0, TRUE);
}


function complete_post(DatabaseConnection $db, server_data &$servers, action $item, $status)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    if (!$servers->has_equal($item)) {
        echo_debug("last post (STATUS $status)", DEBUG_SERVER);
        $postid = $item->get_args();
        $db->escape($postid);
        $sql = "count(*) AS cnt FROM post_files WHERE postid = '$postid' AND status = '" . POST_FAILED . "'";
        $res = $db->select_query($sql);
        $failed = $res[0]['cnt'];
        $sql = "count(*) AS cnt FROM post_files WHERE postid = '$postid' AND status = '" . POST_FINISHED . "'";
        $res = $db->select_query($sql);
        $finished = $res[0]['cnt'];
        $sql = "count(*) AS cnt FROM post_files WHERE postid = '$postid' AND status = '" . POST_ACTIVE . "'";
        $res = $db->select_query($sql);
        $active = $res[0]['cnt'];
        $sql = "count(*) AS cnt FROM post_files WHERE postid = '$postid' AND status = '" . POST_READY . "'";
        $res = $db->select_query($sql);
        $queued = $res[0]['cnt'];
        write_log("Posted articles:  Q: $queued, D: $finished, F: $failed, A:$active", LOG_INFO);

        if ($status == DOWNLOAD_FINISHED || $status == DOWNLOAD_QUEUED || $status == DOWNLOAD_ACTIVE || $status == DOWNLOAD_READY) {
            if (($queued + $active) > 0) {
                // there are still things to download left
                // possibly a pause interrupted things
                $postid = $item->get_args();
                $sql = "UPDATE post_files SET status = '" . POST_READY . "' WHERE postid = '$postid' AND status =  '" . POST_FAILED . "' OR status = '" . POST_ACTIVE . "'" ;
                $db->execute_query($sql);
                $servers->queue_push($db, $item, FALSE);

                return;
            } elseif ($failed > 0) {
                write_log("Could not post all articles $failed failed", LOG_WARNING);
            }
            cleanup_post($db, $postid);
            $qstatus = QUEUE_FINISHED;
            update_queue_status($db, $item->get_dbid(), $qstatus, 0, 100);
            echo_debug('post status finished', DEBUG_SERVER);
            $done_status = POST_FINISHED;
            // todo create NZB file from article ID

            update_postinfo_status ($db, $done_status, $postid);
            echo_debug("post complete $postid", DEBUG_SERVER);
        } elseif ($status == DOWNLOAD_CANCELLED) {
            $done_status = POST_CANCELLED; // a cancel is permanent
            update_postinfo_status ($db, $done_status, $postid);
        } else {
            echo_debug("Unhandled status of download = $status", DEBUG_SERVER);
        }
    }
}
