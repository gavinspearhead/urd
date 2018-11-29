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
 * $LastChangedDate: 2014-06-13 23:38:58 +0200 (vr, 13 jun 2014) $
 * $Rev: 3091 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: download_functions.php 3091 2014-06-13 21:38:58Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function store_article(&$article, $dir, $msg_id)
{
    $msg_id = trim($msg_id, '<>');
    file_put_contents($dir . $msg_id . '.txt', $article . "\n\n");
}


function parse_filename_from_subject($subject)
{
    $s = html_entity_decode($subject);
    if (preg_match('|yenc.*"(.*)" yenc|i', $subject, $matches) === 1) {
        $name = $matches[1];
    } else if (preg_match('|"(.*)"|', $subject, $matches) === 1) {
        $name = $matches[1];
    } else if (preg_match('|(.*) yenc|i', $subject, $matches) === 1) {
        $name = $matches[1];
    } else {
        $name = $subject;
    }
    $name = str_replace('"', '', $name);

#    echo_debug('Filename ' . $name . " || " . $subject, DEBUG_SERVER);
    return $name;
}

function download_batch(DatabaseConnection& $db, array &$batch, $dir, URD_NNTP &$nzb, &$groupid, $userid, &$connected, $check_for_rar_encryption, $download_par_files)
{
    assert(is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $download_text_file = get_pref($db, 'download_text_file', $userid);
    $yydecode = my_escapeshellcmd (get_config($db, 'yydecode_path'));
    $yydecode_pars = my_escapeshellarg(get_config($db, 'yydecode_pars', ''), FALSE);
    if ($yydecode === FALSE) {
        throw new exception('yydecode not found', ERR_CONFIG_ERROR);
    }

    $cmd = "/bin/sh -c '$yydecode $yydecode_pars  ";
    $size = $p_cnt = $a_cnt = $e_cnt = $groupid = (int) 0;

    $mime_settings = ['include_bodies' => TRUE, 'decode_bodies' => TRUE, 'decode_headers' => TRUE];
    $descriptorspec = [
            0 => ['pipe', 'r'], // where we will write to
            1 => ['file', '/dev/null', 'w'], // we don't want the output
            2 => ['file', '/dev/null', 'w'] // or the errors
            //1 => ['file', '/tmp/out', 'a'], // we do want the output
            //2 => ['file', '/tmp/err', 'a'] // and the errors
    ];
    $pipes = [];
    $p = NULL;
    
    $last_filename = '';
    add_dir_separator($dir);
    // $batch is the result of the select query on ready-articles:
    // First reset them all to DOWNLOAD_READY, because if we throw an error we don't want to have orphaned ACTIVE's

    foreach ($batch as $key => $article) {
        try {
            if (!$download_par_files && preg_match('/vol\d+\+\d+.par2/i', $article['name']) > 0) {
                $batch[$key]['dlstatus'] = DOWNLOAD_IS_PAR_FILE;
                $p_cnt++;
                continue;
            }
            if ($article['groupID'] != $groupid) {
                $groupid = $article['groupID'];
                $nzb->select_group($groupid, $code);
            }

            $name = parse_filename_from_subject($article['name']);
            if ($name != $last_filename) {
                if ($p !== NULL) pclose($p);
                $last_filename = $name;
                $name = my_escapeshell_quoted($name, FALSE);
              //  var_dump ($cmd . " -o \"$name\" '");
                $process = proc_open($cmd . " -o \"$name\" '", $descriptorspec, $pipes, $dir, NULL, ['binary_pipes']);
                if ($process === FALSE || !is_resource($process)) {
                    write_log('Could not create pipe', LOG_WARNING);
                    urdd_exit(PIPE_ERROR);
                }
                $p = $pipes[0];
            }

            $msg_id = $article['messageID'];
            $art = $nzb->get_article($msg_id);
            // If we get here, download was succesful (otherwise try/catch kicked in)
            $batch[$key]['dlstatus'] = DOWNLOAD_FINISHED;
            $type = download_type::get_download_type($art);
            // Check type of encoding:
            if ($type == download_type::TYPE_YYENCODED) {
                $err_level = error_reporting(0); // so we don't get any write errors, saves a @
                foreach ($art as $line) {
                    $r = fwrite($p, $line . "\n");
                    if ($r === FALSE) {
                        error_reporting($err_level);
                        throw new exception('Write failed', ERR_PIPE_ERROR);
                    }
                    $size += strlen($line);
                }
                if ($check_for_rar_encryption != encrar::ENCRAR_CONTINUE) {
                    $is_enc = download_type::check_for_encrypted_rar($art, $dir);
                    if ($is_enc) {
                        touch($dir . 'password_encrypted_file.log');
                        error_reporting($err_level);
                        throw new exception ('Password encrypted file', ENCRYPTED_RAR);
                    }
                }
                error_reporting($err_level);
                $a_cnt++;
            } elseif ($type == download_type::TYPE_UUENCODED) {
                $partnumber = $article['partnumber'];
                $name = preg_replace('/[^a-zA-Z0-9._]/', '', $article['name']);
                $f = fopen($dir . $name . '.' . $partnumber. '.urd_uuencoded_part', 'w');
                if ($f === FALSE) {
                    throw new exception('failed to create file', ERR_PIPE_ERROR);
                }
                $err_level = error_reporting(0); // so we don't get any write errors, saves a @
                foreach ($art as $line) {
                    $r = fwrite($f, $line . "\n");
                    if ($r === FALSE) {
                        error_reporting($err_level);
                        throw new exception('write failed', ERR_PIPE_ERROR);
                    }
                    $size += strlen($line);
                }
                fclose($f);
                error_reporting($err_level);
                $a_cnt++;
            } elseif ($type == download_type::TYPE_XXENCODED) {
                write_log('Can not handle XX encoded files yet... post at the forum if you run in to this error', LOG_ERR);
            //	throw new exception('Unrecognized encoding found', ERR_UNKNOWN_ENCODING);
            } elseif ($type == download_type::TYPE_MIMEENCODED || download_type::TYPE_UNKNOWN) {
                $head = $nzb->get_header($msg_id);
                $mime = new Mail_mimeDecode($head, $art);
                $mime_settings = array('include_bodies'=>TRUE, 'decode_bodies'=>TRUE, 'decode_headers'=>TRUE);
                $res = $mime->decode($mime_settings);
                if (isset($res->parts) && is_array($res->parts)) {
                    foreach ($res->parts as $part) {
                        if (isset($part->d_parameters['filename'])) {
                            $filename = $dir . $part->d_parameters['filename'];
                            file_put_contents($filename, $part->body);
                        } else {
                            if ($download_text_file && count($art) < (4 * 1024)) {
                                store_article($part->body, $dir, $msg_id);
                            }
                        }
                        $size += strlen($part->body);
                    }
                } else {
                    if (isset($res->ctype_parameters['name'])) {
                        $filename = $dir . $res->ctype_parameters['name'];
                        file_put_contents($filename, $res->body);
                    } else {
                        if ($download_text_file && count($art) < (4 * 1024)) {
                            store_article($res->body, $dir, $msg_id);
                        }
                    }
                    $size += strlen($res->body);
                }
            } else {
                $head = $nzb->get_header($msg_id);
                if ($download_text_file && count($art) < (4 * 1024)) {
                    $art = implode("\n", $art);
                    store_article($art, $dir, $msg_id);
                    $size += strlen($art);
                } else {
                    write_log('Unrecognized encoding found', LOG_WARNING);
                }
            }
        } catch (exception $e) {
            $e_cnt++;
            $batch[$key]['dlstatus'] = DOWNLOAD_FAILED;
            write_log('Could not download article: ' . $e->getMessage() . "({$e->getCode()})", LOG_INFO);
            if ($e->getCode() == NNTP_NOT_CONNECTED_ERROR) {
                // Articles didn't really fail so set them to be downloaded again:
                $batch[$key]['dlstatus'] = DOWNLOAD_READY;
                pclose($p);
                $rv = proc_close($process);
                // Set connected to false so start_download function knows it shouldn't continue
                $connected = FALSE;
                // And return because we don't want to continue trying to download stuff from this batch:
                return [$size, $a_cnt, $e_cnt, $p_cnt];
            } elseif ($e->getCode() == ERR_NNTP_AUTH_FAILED) {
                throw $e;
            } elseif ($e->getCode() == ENCRYPTED_RAR) {
                throw $e;
            }
        }
    }
    pclose($p);
    $rv = proc_close($process);
    if ($rv > 1) {
        // yydecode has weird exitcode behaviour, and not documented
        write_log("YYdecode was not successful (exit code $rv) - disk full?", LOG_WARNING);
    }
    if ($a_cnt > 0) {
        write_log("Downloaded $a_cnt articles", LOG_INFO);
    }
    if ($e_cnt > 0) {
        write_log("Failed to download $e_cnt articles", LOG_INFO);
    }

    // Should update download status here, errors/completed/todo/in progress XXX XXX XXX ?????
    return [$size, $a_cnt, $e_cnt, $p_cnt];
}


function get_batchsize($preview, $total_ready)
{
    assert(is_numeric($total_ready) && is_bool($preview));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    if ($preview === TRUE) {
        return batch_size::PV_BATCH_SIZE;
    }
    if ($total_ready < (10 * batch_size::DL_BATCH_SIZE)) {
        $factor = 1;
    } elseif ($total_ready < (20 * batch_size::DL_BATCH_SIZE)) {
        $factor = 2;
    } else {
        $factor = 3;
    }
// we randomize the batch size a bit, so that the status updates will come more frequently.
    mt_srand(getmypid() + time() + $total_ready);
    $batch_size = ($factor * batch_size::DL_BATCH_SIZE) + mt_rand(0, batch_size::DL_BATCH_SIZE);
    return $batch_size;
}

function start_download(DatabaseConnection& $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $dlid = $item->get_args();
    if (check_dl_lock($db, $dlid) === FALSE) { // if db still locked
        echo_debug('Dl still locked, sleeping', DEBUG_SERVER); // todo needs fixing

        return DB_LOCKED;
    }
    $stat_id = get_stat_id($db, $dlid);
    $dbid = $item->get_dbid();
    $total_ready = get_download_articles_count_status($db, $dlid, DOWNLOAD_READY);
    $total = get_download_articles_count($db, $dlid);
    $total_size = get_download_size($db, $dlid);
    $dlpw = get_download_password($db, $dlid) ? TRUE : FALSE;
    $download_par_files = get_download_par_files($db, $dlid) ? TRUE : FALSE;
    $userid = $item->get_userid();
    $check_for_rar_encryption = get_pref($db, 'cancel_crypted_rars', $userid, FALSE);
    if ($dlpw != '') {
        $check_for_rar_encryption = FALSE;
    }
    $done_start = $total - $total_ready;
    // determine a reas/onable batchsize... increase it if we have more articles
    $batch_size = get_batchsize($item->get_preview(), $total_ready);
    $done = $cnt = 0;
    $first_batch_size = max(4, round(batch_size::DL_BATCH_SIZE / 4)); // the first batch is always small, so we get a quick progress update
    $dir = get_download_destination($db, $dlid);
    $groupid = 0;

    $req_status      = DOWNLOAD_READY;
    $dl_status       = DOWNLOAD_ACTIVE;
    $done_status     = DOWNLOAD_FINISHED;
    $failed_status   = DOWNLOAD_FAILED;
    
    $start_time = get_start_time($db, $dlid);
    $now = time();
    if ($start_time > $now) {
        set_start_time($db, $dlid, $now);
    }

    // Update status:
    update_dlinfo_status($db, $dl_status, $dlid);
    $lock_array = array('downloadarticles' => 'write'); // for the article table

    try {
        // Pick a server:
        $server_id = $item->get_active_server();
        if ($server_id == 0) {
            $server_id = $item->get_preferred_server();
        }
        if (get_priority_usenet_server($db, $server_id) <= 0) {
            echo_debug("Server $server_id is disabled", DEBUG_NNTP);
            throw new exception("Server $server_id is disabled", ERR_SERVER_INACTIVE);
        }
        // Connect
        $reconnects = 0;
        $nzb = NULL;
        $connected = FALSE;

        $b_time = microtime(TRUE);
        $article_query = '"groupID", "partnumber", "messageID", "name", "ID" FROM downloadarticles WHERE "downloadID"=:dlid AND "status"=:status ORDER BY "name", "partnumber"';
        $article_input_arr = array(':dlid'=>$dlid, ':status'=>$req_status);
        while (TRUE) {
            if ($connected === FALSE) {
                // Check if we didn't exceed max allowed reconnect attempts:
                if ($reconnects >= MAX_RECONNECTION_ATTEMPTS_PER_THREAD) {
                    throw new exception('Max reconnection attempts exceeded');
                }

                // Connect:
                write_log("Connecting to NNTP server ($server_id).", LOG_NOTICE);
                $nzb = connect_nntp($db, $server_id);
                $connected = TRUE;
                $reconnects++;
            }

            $s_time = microtime(TRUE);
            try {
                $db->lock($lock_array);
                // First time use small batch size:
                if ($first_batch_size > 0) {
                    $res = $db->select_query($article_query, $first_batch_size, $article_input_arr);
                    $first_batch_size = 0;
                } else {
                    $res = $db->select_query($article_query, $batch_size, $article_input_arr);
                }

                if ($res === FALSE) {
                    // Good exit:
                    $db->unlock();
                    $nzb->disconnect();
                    echo_debug('No more articles found', DEBUG_NNTP);
                    $comment = "Processed $cnt batches";
                    write_log($comment, LOG_DEBUG);
                    $progress = NULL; // not set the progress... other threads may still be running....
                    $error_no = NO_ERROR;
                    update_queue_status($db, $item->get_dbid(), QUEUE_FINISHED, 0, $progress, $comment);

                    return $error_no;
                }

                // Set all articles from $res to the DOWNLOAD_ACTIVE status:
                update_batch($db, $res, $dl_status);
            } catch (exception $e) {
                $db->unlock();
                $nzb->disconnect();
                throw $e;
            }
            $db->unlock();

            // Download the batch:
            try {
                list($bytes) = download_batch($db, $res, $dir, $nzb, $groupid, $userid, $connected, $check_for_rar_encryption, $download_par_files);
            } catch (exception $e) {
                if ($e->getCode() == ENCRYPTED_RAR) {
                    $progress = 0;
                    $comment = $e->getMessage();
                    write_log('Cancelling download: ' . $e->getmessage(), LOG_NOTICE);
                    update_queue_status($db, $item->get_dbid(),QUEUE_CANCELLED, 0, $progress, $comment);
                    urdd_exit(ENCRYPTED_RAR);
                } else {
                    throw $e;
                }
            }

            // Downloading changed the status of some articles, update database with new statuses:
            // But before we do that, we can't have any batch items on active as the batch is finished:
            // (this can happen when the NNTP server disconnects:
            if ($connected == FALSE) {
                foreach ($res as $key => $article) {
                    if (!isset($res[$key]['dlstatus']) || (isset($res[$key]['dlstatus']) && $res[$key]['dlstatus'] == $dl_status)) {
                        $res[$key]['dlstatus'] = $req_status;
                    }
                }
            }

            // $req_status means that the default status is 'DOWNLOAD_READY', if for some reason,
            // some batch items won't have a specified status, and then $req_status is the default value
            update_batch($db, $res, $req_status);
            // Download statistics:
            update_dlstats($db, $stat_id, $bytes);
            update_dlinfo($db, $dlid, $bytes);
            $f_time = microtime(TRUE);
            $time_diff = $f_time - $b_time;
            $cnt++;
            $done = get_download_articles_count_status($db, $dlid, $done_status);
            $percentage = ($total > 0) ? floor(100 * ($done / $total)) : 0;
            $remain = $total - $done;
            $done_ready = $done - $done_start;
            $eta = ($done_ready > 0) ? (round(($remain * $time_diff) / $done_ready)) : 0;
            $speed = ($time_diff > 0) ? (round($bytes / $time_diff)) : 0;
            store_ETA($db, $eta, $percentage, $speed, $dbid);
        }
    } catch (exception $e) {
        write_log('Error while downloading: ' . $e->getmessage(), LOG_NOTICE);
        if ($e->getcode() == ERR_NNTP_AUTH_FAILED) {
            $error_no = NNTP_AUTH_ERROR;
        } elseif ($e->getcode() == ERR_SERVER_INACTIVE) {
            $error_no = SERVER_INACTIVE;
        } else {
            $error_no = NNTP_NOT_CONNECTED_ERROR;
        }
    }

    // Bad exit:
    $comment = 'Connection failed';
    update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 0, $comment);

    return $error_no;
}

function update_dlinfo(DatabaseConnection &$db, $dlid, $bytes)
{
    assert(is_numeric($dlid) &&is_numeric($bytes));
    $sql = 'UPDATE downloadinfo SET "done_size" = "done_size" + :bytes WHERE "ID" = :dlid';
    $db->execute_query($sql, array(':dlid'=>$dlid, ':bytes'=>$bytes));
}

function complete_download(DatabaseConnection &$db, server_data &$servers, action $item, $status)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    if (!$servers->has_equal($item)) {
        echo_debug("Last download (STATUS $status) " . $item->get_command() . ' ' . $item->get_args(), DEBUG_SERVER);
        $dlid = $item->get_args();
        if ($status == DOWNLOAD_FINISHED || $status == DOWNLOAD_QUEUED || $status == DOWNLOAD_COMPLETE || $status == DOWNLOAD_ACTIVE || $status == DOWNLOAD_READY) {
            list ($done, $queued, $failed, $par_files) = check_all_dl_done($db, $item);
            //$failed = 1;
            if ($queued > 0) {
                // there are still things to download left
                // possibly a pause interrupted things
                $dlid = $item->get_args();
                update_dlarticle_status($db, $dlid, DOWNLOAD_READY, DOWNLOAD_FINISHED, '<');
                $servers->recreate_download_command($db, $item, FALSE, TRUE);

                return;
            } elseif ($failed > 0) {
                $item->add_failed_servers($item->get_preferred_server()); // do not try this server again

                // check if there is a server we haven't tried yet
                if ($servers->unused_servers_available($item->get_failed_servers(), FALSE) !== FALSE) {
                    // we have to try another server
                    update_dlarticle_status($db, $dlid, DOWNLOAD_READY, DOWNLOAD_FAILED, '=');
                    $servers->recreate_download_command($db, $item, FALSE, TRUE);
                    update_dlinfo_status ($db, DOWNLOAD_QUEUED, $dlid);

                    return;
                } // otherwise simply assume we're complete
            }
            $qstatus = QUEUE_FINISHED;
            update_queue_status($db, $item->get_dbid(), $qstatus, 0, 100);
            echo_debug("All is done (D: $done, Q: $queued, F: $failed, P: $par_files)", DEBUG_SERVER);
            echo_debug('Download status finished', DEBUG_SERVER);
            $userid = $item->get_userid();
            $destination = get_download_destination($db, $dlid);

            // Renaming to filename specified in database. Might contain escape characters,
            // but only if an attacker has database access. Check anyway:
            $preview = $item->get_preview();
            $done_status = ($done == 0) ? DOWNLOAD_FAILED : DOWNLOAD_COMPLETE;
            update_dlinfo_status ($db, $done_status, $dlid);
            echo_debug("Download complete $dlid", DEBUG_SERVER);
            cleanup_download_articles($db, $dlid);
            if ($done > 0) {
                $new_item = new action(NULL, NULL, NULL);
                $new_item->copy($item);
                $item->set_command(urdd_protocol::COMMAND_DOWNLOAD);
                queue_unpar_unrar($db, $destination, $dlid, $servers, $userid, $preview);
            } else {
                if (!$item->get_preview()) {
                    try {
                        urd_mail::mail_user_download($db, $dlid, $item->get_userid(), $done_status);
                    } catch (exception $e) {
                        write_log('Could not send message', LOG_WARNING);
                    }
                }
            }
        } elseif ($status == DOWNLOAD_CANCELLED) {
            update_dlinfo_status($db, DOWNLOAD_CANCELLED, $dlid);
            cleanup_download_articles($db, $dlid);
        } else {
            echo_debug("Unhandled status of download = $status", DEBUG_SERVER);
        }
    } elseif ($servers->has_equal_queue($item) && $status == DOWNLOAD_QUEUED) {
        // this is the last running but there are others still, we need to set the download status then back to queued
        $dlid = $item->get_args();
        update_dlinfo_status($db, $status, $dlid);
    }
}

function check_all_dl_done(DatabaseConnection &$db, action $item)
{
    echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    $dlid = $item->get_args();
    $sql = 'count("ID") AS "counter" FROM downloadarticles WHERE "downloadID"=? AND "status" < ?';
    $queued = $db->select_query($sql, array($dlid, DOWNLOAD_FINISHED));
    if (!isset($queued[0]['counter'])) {
        throw new exception_db('Database error @ queued');
    }
    $sql = 'count("ID") AS "counter" FROM downloadarticles WHERE "downloadID"=? AND "status"=?';
    $failed = $db->select_query($sql, array($dlid, DOWNLOAD_FAILED));
    if (!isset($failed[0]['counter'])) {
        throw new exception_db('Database error @ failed');
    }
    $sql = 'count("ID") AS "counter" FROM downloadarticles WHERE "downloadID"=? AND "status"=?';
    $done = $db->select_query($sql, array($dlid, DOWNLOAD_FINISHED));
    if (!isset($done[0]['counter'])) {
        throw new exception_db('Database error @ done');
    }
    $sql = 'count("ID") AS "counter" FROM downloadarticles WHERE "downloadID"=? AND "status"=?';
    $par_files = $db->select_query($sql, array($dlid, DOWNLOAD_IS_PAR_FILE ));
    if (!isset($par_files[0]['counter'])) {
        throw new exception_db('Database error @ par_files');
    }

    return array($done[0]['counter'], $queued[0]['counter'], $failed[0]['counter'], $par_files[0]['counter']);
}

function add_download(DatabaseConnection &$db, $userid, $unpar, $unrar, $subdl, $delete_files, $status, $destination, $dl_type, $first_run, $download_par)
{
    assert(is_numeric($userid));
    $id = $db->insert_query('downloadinfo',
        array('name', 'unpar', 'unrar', 'subdl', 'delete_files', 'status', 'destination', 'userid', 'preview', 'size', 'first_run', 'stat_id', 'download_par', 'hidden'),
        array('', $unpar, $unrar, $subdl, $delete_files, $status, $destination, $userid, $dl_type, 0, $first_run? 1 : 0, 0, $download_par ? 1 : 0, 0),
        TRUE);

    return $id;
}

function set_download_dir(DatabaseConnection &$db, $id, $destination)
{
    assert(is_numeric($id));
    $db->update_query_2('downloadinfo', array('destination'=>$destination), '"ID"=?', array($id));
}

function create_download(DatabaseConnection &$db, server_data &$servers, $userid, $preview = FALSE, $priority=NULL)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($userid));
    list($code, $dlid, $item_id) = do_create_download($db, $servers, $userid, $preview, $priority);
    if ($code == 210) {
        $id_str = "[$item_id] ";

        return sprintf(urdd_protocol::get_response(210), $dlid, $id_str);
    } else {
        return urdd_protocol::get_response($code);
    }
}

function do_create_download(DatabaseConnection &$db, server_data &$servers, $userid, $preview = FALSE, $priority=NULL)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($userid));
    $r = get_pref($db, 'unrar', $userid);
    $unrar = ($r !== FALSE) ? $r : 0; // default off?
    $r = get_pref($db, 'unpar', $userid);
    $unpar = ($r !== FALSE) ? $r : 0; // default off?
    $r = get_pref($db, 'subs_lang', $userid, '');
    $subdl = ($r !== FALSE) ? (($r != '')? 1 : 0) : 0; // default off?
    $r = get_pref($db, 'delete_files', $userid);
    $delete_files = ($r !== FALSE) ? $r : 0; // default off?
    $download_par_files = get_pref($db, 'download_par', $userid);
    $id = add_download($db, $userid, $unpar, $unrar, $subdl, $delete_files, DOWNLOAD_READY, '', $preview ? download_types::PREVIEW : download_types::NORMAL, TRUE, $download_par_files);

    $dl_path_basis = get_dlpath($db);
    $username = get_username($db, $userid);
    $dl_path = find_unique_name($dl_path_basis, TMP_PATH . $username . DIRECTORY_SEPARATOR, $id);
    $rv = @create_dir($dl_path, 0775);
    if ($rv === FALSE) {
        write_log("Failed to create directory $dl_path", LOG_ERR);

        return array(405, NULL, NULL);
    }
    clearstatcache();
    if (!is_writable($dl_path)) {
        write_log("Download directory is not writable: $dl_path", LOG_ERR);

        return array(405, NULL, NULL);
    }
    //$id_str = '';
    $item = new action(urdd_protocol::COMMAND_DOWNLOAD, $id, $userid, TRUE);
    $item->set_dlpath($dl_path);
    set_download_dir($db, $id, $dl_path);
    if ($preview) {
        $item->set_preview(TRUE);
        $priority = 2;
    }
    $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
    if ($res === FALSE) {
        throw new exception_queue_failed ('Could not queue item');
    }
    inc_dl_lock($db, $id); // lock the dowload so it won't start until it is unlocked and all setdata is added to the article tabels
    if ($preview) {
        update_queue_norestart($db, $res);
    }
    $status = ($item->is_paused()) ? DOWNLOAD_PAUSED : DOWNLOAD_QUEUED;
    set_download_destination($db, $id, $dl_path);
    touch ($dl_path . URDD_DOWNLOAD_LOCKFILE); //lock file set to indicate URDD is still doing stuff with it
    update_dlinfo_status ($db, $status, $id);

    return array(210, $id, $item->get_id());
}


function restart_download(DatabaseConnection &$db, server_data &$servers, $userid, $id, $priority=NULL)
{
    assert(is_numeric($id) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    try {
        $username = get_username($db, $userid);
        $item = new action(urdd_protocol::COMMAND_DOWNLOAD, $id, $userid, FALSE);
        if ($servers->has_equal($item)) {
            return urdd_protocol::get_response(406);
        }
        update_dlinfo_status ($db, DOWNLOAD_READY, $id, DOWNLOAD_ACTIVE);
        if ($item->is_paused()) {
            update_dlinfo_status ($db, DOWNLOAD_PAUSED, $id);
        }
        $dl_path = get_download_destination($db, $id);
        $rv = is_dir($dl_path) && is_writable($dl_path);

        if ($rv === FALSE) {
            write_log("Directory is not accessible $dl_path", CONFIG_ERROR);

            return urdd_protocol::get_response(405);
        }
        $id_str = '';
        echo_debug("Re-starting download $id", DEBUG_SERVER);

        $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
        if ($res === FALSE) {
            throw new exception_queue_failed('Could not queue item');
        }
        $id_str .= "[{$item->get_id()}] ";

        return sprintf (urdd_protocol::get_response(210), $id, $id_str);
    } catch (exception $e) {
        return sprintf (urdd_protocol::get_response(503), $e->getMessage());
    }
}

function verify_cksfv(DatabaseConnection &$db, $dir, $dlid, pr_list $files, action $item, &$error)
{
    assert(is_numeric($dlid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $sfv_cmd = my_escapeshellcmd(get_config($db, 'cksfv_path', ''));
    if ($sfv_cmd == '') {
        return 'sfv not run';
    }
    $starttime = time();
    chdir($dir);
    $count = $succ = 0;
    $log_file = my_escapeshellarg($dir . 'sfv.log');
    if (!is_array($files->file_list)) {
        write_log("No files found for download $dlid");

        return 'No files found';
    }
    $niceval = get_nice_value($db);
    foreach ($files->file_list as $f) {
        if ($f->ext == file_extensions::SFV_EXT) {
            $filename = my_escapeshellarg($f->files[0]);
            exec ("nice -$niceval $sfv_cmd -f ./$filename -q -i >>$log_file 2>&1", $output, $rv);
            $count++;
            if ($rv == 0) {
                $succ++;
            }
        }
    }
    if ($count == $succ) {
        write_log("Successful download $dlid");
        $comment = 'Cksfv complete ';
        $endtime = time();
        $t_time = $endtime - $starttime;
        $error = FALSE;
        if ($count > 0) {
            unlink($log_file);
        }
        update_queue_status($db, $item->get_dbid(), NULL, $t_time, 50, $comment);
    } else {
        write_log("Incomplete download $dlid");
        update_dlinfo_status($db, DOWNLOAD_CKSFV_FAILED, $dlid);

        $comment = 'Cksfv failed ';
    }

    return $comment;
}

function verify_par(DatabaseConnection &$db, $dir, $dlid, pr_list $files, action $item, &$error, &$unpar)
{
    assert(is_numeric($dlid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $par_cmd = my_escapeshellcmd(get_config($db, 'unpar_path', ''));
    if ($par_cmd === '') {
        $unpar = FALSE;

        return 'Par not run';
    }
    $starttime = time();
    chdir($dir);
    $count = $succ = 0;
    $par2_params = my_escapeshellarg(get_config($db, 'unpar_pars'), FALSE);
    $log_file = my_escapeshellarg($dir . 'par2.log');
    $niceval = get_nice_value($db);
    foreach ($files->file_list as $f) {
        if ($f->ext == file_extensions::PAR_EXT) {
            $filename = my_escapeshellarg($f->files[0]);
            exec("nice -$niceval $par_cmd $par2_params ./$filename * >>$log_file 2>&1", $output, $rv);
            $count++;
            if ($rv == 0) {
                $succ++;
            } else {
                write_log("Par2 failed for $filename - exit code $rv", LOG_INFO);
            }
        }
    }
    if ($count == 0) {
        $unpar = 0;

        return 'No par files';
    }
    if ($count == $succ) {
        write_log("Successful download $dlid");
        $comment = 'PAR2 complete ';
        $t_time = time() - $starttime;
        $error = FALSE;
        unlink($log_file);
        update_queue_status($db, $item->get_dbid(), NULL, $t_time, 50, $comment);
    } else {
        write_log("Incomplete download $dlid");
        update_dlinfo_status($db, DOWNLOAD_PAR_FAILED, $dlid);
        $comment = 'PAR2 failed ';
        $error = TRUE;
    }

    return $comment;
}

function decompress(DatabaseConnection $db, $type, $dir, pr_list $files, $password, $dlid, &$dl_status, &$error, $par_error)
{
    assert(is_numeric($dlid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $rar_cmd = get_config($db, 'unrar_path', '');
    $arj_cmd = get_config($db, 'unarj_path', '');
    $zip_cmd = get_config($db, 'unzip_path', '');
    $ace_cmd = get_config($db, 'unace_path', '');
    $zr7_cmd = get_config($db, 'un7zr_path', '');
    $rar_arg = get_config($db, 'unrar_pars', '');
    $arj_arg = get_config($db, 'unarj_pars', '');
    $zip_arg = get_config($db, 'unzip_pars', '');
    $ace_arg = get_config($db, 'unace_pars', '');
    $zr7_arg = get_config($db, 'un7zr_pars', '');

    $archive_types = array ( // command, options, password option, nopassword
        //file_extensions::RAR_EXT => array($rar_cmd, 'x -y -kb -o- -inul -c-', '-p@@@', '-p-'),
        file_extensions::RAR_EXT => array($rar_cmd, $rar_arg, '-p@@@', '-p-'),
        file_extensions::ACE_EXT => array($ace_cmd, $ace_arg, '-p@@@', ''),
        file_extensions::ZIP_EXT => array($zip_cmd, $zip_arg, '-P @@@', ''),
        file_extensions::ARJ_EXT => array($arj_cmd, $arj_arg, '-g@@@', ''),
        file_extensions::ZR7_EXT => array($zr7_cmd, $zr7_arg, '-p@@@', '')
    );

    chdir($dir);
    $niceval = get_nice_value($db);
    $count = $succ = 0;
    $comment = '';
    $log_file = my_escapeshellarg($dir . "$type.log");
    foreach ($files->file_list as $f) {
        if ($f->ext == $type) {
            $count++;
            foreach ($f->files as $filename) {
                echo_debug("Trying to expand $filename", DEBUG_SERVER);
                $filename = my_escapeshellarg($filename);
                $cmd = my_escapeshellcmd($archive_types[$type][0]);
                if ($cmd === '') {
                    continue;
                }
                $options = my_escapeshellarg($archive_types[$type][1], FALSE);

                if ($password != '') {
                    $pw_opt = $archive_types[$type][2];
                    $pw_opt = my_escapeshellarg(str_replace('@@@', $password, $pw_opt));
                } else {
                    $pw_opt = $archive_types[$type][3];
                }
                $cmd_line = "LANG=en_US.UTF-8 nice -$niceval $cmd $options $pw_opt ./$filename >>$log_file 2>&1";
                exec($cmd_line, $output, $rv);
                if ($rv == 0 || $rv == 1) {
                    $succ++;
                    break;
                } elseif ($type != file_extensions::ZR7_EXT) { // only 7z is picky about the order?? RAR is not at least; ARJ, ACE, ZIP are not tested
                    break;
                } else {
                    write_log("Decompress failed for '$filename': Error code $rv", LOG_NOTICE);
                }
            }
        }
    }
    if ($count == 0) {
        echo_debug("No $type files found", DEBUG_SERVER);
    } elseif ($count == $succ) {
        unlink($log_file);
        write_log("Successfully decompressed download $dlid ($count archives)");
        $comment .= "Decompression successful ($count archives) ";
    } else {
        $comment .= " Decompression failed ($count archives found; $succ decompressed)";
        if (!$par_error) {
            update_dlinfo_status($db, DOWNLOAD_RAR_FAILED, $dlid);
            $dl_status = DOWNLOAD_RAR_FAILED;
        }
        write_log("Decompressing download $dlid failed ($count archives found; $succ decompressed) ");
        $error = TRUE;
    }

    return $comment;
}

function concat_files(DatabaseConnection $db, pr_list $files, $dlid, $dir, &$error, &$dl_status)
{
    assert(is_numeric($dlid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $comment = '';
    foreach ($files->file_list as $f) {
        if ($f->ext == file_extensions::CAT_EXT) {
            natcasesort($f->files);
            $filename = $f->files[0];
            $filename = preg_replace("/\.\d{3}$/", '', $filename);
            $fp = fopen ($dir . $filename, 'x');
            if ($fp === FALSE) {
                $comment .= ' could not create file';
                update_dlinfo_status($db, DOWNLOAD_RAR_FAILED, $dlid);
                $dl_status = DOWNLOAD_RAR_FAILED;
                write_log("Concatenating download $dlid failed");
                $error = TRUE;
            }
            foreach ($f->files as $file) {
                $fpr = fopen ($dir . $file, 'r');
                if ($fpr === FALSE) {
                    $comment .= " could not create file $file";
                    update_dlinfo_status($db, DOWNLOAD_RAR_FAILED, $dlid);
                    $dl_status = DOWNLOAD_RAR_FAILED;
                    write_log("Concatenating download $dlid failed");
                    $error = TRUE;
                    break;
                }
                while (!feof($fpr)) {
                    $rv = @fwrite($fp, fread($fpr, 8192));
                    if ($rv === FALSE) {
                        $comment .= ' could not write file';
                        update_dlinfo_status($db, DOWNLOAD_RAR_FAILED, $dlid);
                        $dl_status = DOWNLOAD_RAR_FAILED;
                        write_log("Concatenating download $dlid failed");
                        $error = TRUE;
                        break;
                    }
                }
                fclose ($fpr);
            }
            fclose ($fp);
        }
    }

    return $comment;
}

function uudecode(DatabaseConnection $db, pr_list $files, $dlid, $dir, &$error, &$dl_status, &$counter)
{
    assert(is_numeric($dlid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $counter++;
    $descriptorspec = array(
        0 => array('pipe', 'r'), // where we will write to
       // 1 => array('file', '/tmp/out1', 'a'), // we don't want the output
        //2 => array('file', '/tmp/out2', 'a') // or the errors
        1 => array('file', '/dev/null', 'w'), // we don't want the output
        2 => array('file', '/dev/null', 'w') // or the errors
    );
    $yydecode = my_escapeshellcmd(get_config($db, 'yydecode_path', ''));
    $yydecode_pars = my_escapeshellarg(get_config($db, 'yydecode_pars', ''), FALSE);
    if ($yydecode == '') {
        throw new exception('yydecode not found', ERR_CONFIG_ERROR);
    }

    $cmd = "/bin/sh -c '$yydecode $yydecode_pars ' ";
    chdir($dir);
    foreach ($files->file_list as $f) {
        if ($f->ext == file_extensions::UUE_EXT) {
            $counter++;
            natcasesort($f->files);
            $pipes = array();
            $process = proc_open($cmd, $descriptorspec, $pipes, $dir, NULL, array('binary_pipes'));
            if ($process !== FALSE) {
                foreach ($f->files as $f) {
                    $contents = file_get_contents($dir . $f);
                    $r = fwrite($pipes[0], $contents);
                    if ($r === FALSE) {
                        throw new exception('Write failed', ERR_PIPE_ERROR);
                    }
                }
                pclose($pipes[0]);
                proc_close($process);
            } else {
                update_dlinfo_status($db, DOWNLOAD_ERROR, $dlid);
                write_log('Failed to open pipe', LOG_ERR);
                $dl_status = DOWNLOAD_ERROR;
                $error = TRUE;
            }
        }
    }

    return '';
}

function select_thread_count($dlsize, $nr_threads)
{
    assert(is_numeric($dlsize) && is_numeric($nr_threads));
    if ($dlsize <= (2 * 1024 * 1024)) { // < 2 MB
        $nr_threads = 1;
    } elseif ($dlsize <= (5 * 1024 * 1024)) { //< 5MB
        $nr_threads = min($nr_threads, 2);
    } elseif ($dlsize <= (10 * 1024 * 1024)) { // < 10MB
        $nr_threads = min($nr_threads, 3);
    } elseif ($dlsize <= (20 * 1024 * 1024)) { // < 20 MB
        $nr_threads = min($nr_threads, 5);
    } // we just take the max possible

    return $nr_threads;
}

function create_download_threads(DatabaseConnection $db, server_data &$servers, action $item)
{
    echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    $dlid = $item->get_args();
    try {
        if (check_dl_lock($db, $dlid) === FALSE) { // if db still locked
            $servers->schedule_locked_item($db, $item);

            return;
        }
        if ($item->get_preview()) {
            $nr_threads = 1;
            $priority = 1; // preview always gets ahead of anything
        } else {
            $srv_id = $servers->find_free_slot($item->get_all_failed_servers(), $item->need_posting(), TRUE); // is there a server that has a free slot
            if ($srv_id === FALSE) {
                write_log('Server ID not specified', LOG_ERR);
                throw new exception('Server ID not specified');
            }
            $max_dl_nntp = get_config($db, 'nntp_maxdlthreads');
            $all_servers = get_config($db, 'nntp_all_servers', 0);
            if ($all_servers) {
                $nr_threads = $servers->get_max_total_nntp_threads();
            } else {
                $nr_threads = $servers->get_max_threads($srv_id);
            }
            if ($max_dl_nntp > 0) {
                $nr_threads = min($max_dl_nntp, $nr_threads);
            }
            $priority = 2; // a ready download gets the highest priority so it is scheduled in asap; only previews will overrule this
        }
        $dlsize = get_download_size($db, $dlid);

        // we want to limit the number of threads started to avoid unneeded threads
        $nr_threads = select_thread_count($dlsize, $nr_threads);

        for ($i = 0; $i < $nr_threads; $i++) {
            $new_item = new action(NULL, NULL, NULL); // create a dummy...
            $new_item->copy($item); // fill it with data here
            $new_item->set_command(urdd_protocol::COMMAND_DOWNLOAD_ACTION);
            $new_item->set_need_nntp(TRUE);
            $new_item->set_priority($priority, user_status::SUPER_USERID);
            $res = $servers->queue_push($db, $new_item, TRUE, server_data::QUEUE_BOTTOM, NULL);
            if ($item->get_preview()) {
                update_queue_norestart($db, $res);
            }
        }
    } catch (exception $e) {
        write_log("Cannot find download $dlid", LOG_ERR);
    }

    try {
        $servers->queue_delete($db, $item->get_id(), user_status::SUPER_USERID, TRUE); // remove the item from the queue
        update_queue_status($db, $item->get_dbid(), QUEUE_REMOVED, 0, NULL, '');
    } catch (exception $e) {
        write_log($e->getMessage(), LOG_ERR);
        throw $e;
    }

    return;
}

function create_post_threads(DatabaseConnection $db, server_data &$servers, action $item)
{
    echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    $dlid = $item->get_args();
    try {
        $srv_id = $servers->find_free_slot($item->get_all_failed_servers(), $item->need_posting()); // is there a server that has a free slot
        if ($srv_id === FALSE) {
            write_log('Server ID not specified', LOG_ERR);
            throw new exception('Server ID not specified');
        }
        $max_dl_nntp = get_config($db, 'nntp_maxdlthreads');
        $nr_threads = $servers->get_max_threads($srv_id);
        if ($max_dl_nntp > 0) {
            $nr_threads = min ($max_dl_nntp, $nr_threads);
        }
        $priority = 2; // a ready download gets the highest priority so it is scheduled in asap; only previews will overrule this
        for ($i = 0; $i < $nr_threads; $i++) {
            $new_item = new action(NULL, NULL, NULL); // create a dummy...
            $new_item->copy($item); // fill it with data here
            $new_item->set_command(urdd_protocol::COMMAND_POST_ACTION);
            $new_item->set_need_nntp(TRUE);
            $new_item->set_priority($priority, user_status::SUPER_USERID);
            $servers->queue_push($db, $new_item, TRUE, server_data::QUEUE_BOTTOM, NULL);
        }
    } catch (exception $e) {
        write_log("Cannot find post $dlid", LOG_ERR);
    }

    try {
        $servers->queue_delete($db, $item->get_id(), user_status::SUPER_USERID, TRUE); // remove the item from the queue
        update_queue_status($db, $item->get_dbid(), QUEUE_REMOVED, 0, NULL, '');
    } catch (exception $e) {
        write_log($e->getMessage(), LOG_ERR);
        throw $e;
    }

    return;
}

function run_scripts(DatabaseConnection $db, action $item, $dlid, $dl_status, $global = FALSE)
{ // global == TRUE run admin set scripts; global == FALSE run user set scripts
    assert(is_numeric($dlid));
    $destination = get_download_destination($db, $dlid);
    $dlpath = my_escapeshellcmd($destination);
    $dlid = my_escapeshellcmd($dlid);
    $dl_status = my_escapeshellcmd($dl_status);
    $rv = $rv_tmp = 0;

    $userid = $item->get_userid();
    $scripts_path = get_dlpath($db);
    $scripts_path .= SCRIPTS_PATH;
    $urd_path = my_escapeshellcmd(realpath(dirname(__FILE__) . '/..'));
    if ($global === TRUE) {
        $scripts = get_pref($db, 'global_scripts', $userid);
        $add_parameters = "$userid $urd_path";
    } else {
        $username = get_username($db, $userid);
        $scripts_path .= $username . '/';
        $scripts = get_pref($db, 'user_scripts', $userid);
        $add_parameters = '';
    }
    $scripts = explode("\n", $scripts);
    sort($scripts);
    foreach ($scripts as $script) {
        if ($script == '') {
            continue;
        }
        if (($script_error = verify_script($db, $scripts_path, $script)) == '') {
            unset($output);
            $cmd = $scripts_path . $script . " $dlpath $dlid $dl_status $add_parameters";
            write_log("Running script $script for download $dlid", LOG_NOTICE);
            echo_debug("Running command $cmd", DEBUG_SERVER);
            exec($cmd, $output, $rv_tmp);
            if ($rv_tmp != 0) {
                write_log("The script $script exited with error code $rv_tmp", LOG_ERR);
            }
            if ($rv == 0) {
                $rv = $rv_tmp;
            }
        } else {
            echo_debug("Script $script error: {$script_error['msg']}", DEBUG_SERVER);
        }
    }

    return $rv;
}

function run_all_scripts(DatabaseConnection $db, action $item, $dlid, $dl_status)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($dlid));
    $global_scripts = get_config($db, 'allow_global_scripts', 0);
    $user_scripts = get_config($db, 'allow_user_scripts', 0);
    if ($global_scripts == 0) { // not allowed to run any scripts

        return;
    }
    run_scripts($db, $item, $dlid, $dl_status, TRUE);
    if ($user_scripts == 0) {// not allowed to run scripts from a user
        return;
    }
    run_scripts($db, $item, $dlid, $dl_status, FALSE);
}

function move_sub_files($from, $to)
{
    // Make sure the $from and $to variables have a trailing /
    add_dir_separator($from);
    add_dir_separator($to);

    // Move all subtitle files somewhere:
    $sub_ext = array('.srt', '.SRT', '.sub', '.SUB', '.idx', '.IDX');
    foreach ($sub_ext as $ext) {
        // Move files to the $to folder:
        foreach (glob("$from*$ext", GLOB_NOSORT) as $file) {
            rename($file, $to . basename($file));
        }
    }
}

function rename_sub_files($folder, $language, $destination)
{
    // Rename all subtitle files:
    add_dir_separator($destination);
    $sub_ext = array('.srt', '.SRT', '.sub', '.SUB', '.idx', '.IDX');
    foreach ($sub_ext as $ext) {
        // Change 'Movie.srt' to 'Movie.en.srt':
        foreach (glob("$folder*$ext", GLOB_NOSORT) as $file) {
            $tofile = $destination . basename($file, $ext) . '.' . $language . $ext;
            rename($file, $tofile);
            write_log('Subtitle found: ' . basename($tofile), LOG_NOTICE);
        }
    }
}

function download_subs(DatabaseConnection $db, $dir, $userid)
{
    // $dir : /somewhere/urd/tmp/User1/1/
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($userid));

    // Variables:
    $originalsubs = 'original_subtitles';
    $addedsubs = 'downloaded_subtitles';
    $sublog = 'subs.log';

    $lang = get_pref($db, 'subs_lang', $userid, '');
    if ($lang == '') {
        return 'subdl not run';
    }
    $langs = explode(',', $lang);

    $subdownloader_cmd = my_escapeshellcmd(get_config($db, 'subdownloader_path', ''));
    $subdownloader_pars = my_escapeshellarg(get_config($db, 'subdownloader_pars', ''), FALSE);
    if ($subdownloader_cmd === '') {
        //$unpar = FALSE;
        return 'subdl not run';
    }

    // Store original subtitles in the 'originalsubs' folder:
    $target_dir = $dir . $originalsubs;
    $target_dir = find_unique_name($target_dir, '', '', '', FALSE);
    create_dir($target_dir, 0775);
    move_sub_files($dir, $target_dir);
    @rmdir($target_dir); // Only works on empty folders

    // Put all subtitles in the addedsubs directory later on
    create_dir($dir . $addedsubs, 0775);

    $log_file = my_escapeshellarg($dir . $sublog);
    foreach ($langs as $l) {
        $l = trim($l);
        write_log("Getting subs for $l", LOG_INFO);
        $cmd = "/bin/sh -c '$subdownloader_cmd $subdownloader_pars --lang=$l 2>> $log_file >>$log_file'";
        exec($cmd, $foo, $rc);
        // todo do sth with the rc value :D

        // Rename subtitle files so they include the language in the filename, prevents collisions when
        // multiple subtitles are downloaded because multiple languages are selected.
        rename_sub_files($dir, $l, $dir . $addedsubs);
        // (File sub.srt for language 'en' is now moved to 'x/downloaded_subtitles/sub.en.srt)
    }

    // Now move all subtitles back and remove the downloaded_subtitles folder
    move_sub_files($dir . $addedsubs, $dir);
    @rmdir($dir . $addedsubs);
}

function get_download_size(DatabaseConnection $db, $dlid)
{
    global $LN;
    assert(is_numeric($dlid));
    $sql = '"size" FROM downloadinfo WHERE "ID" = :dlid';
    $res = $db->select_query($sql, 1, array(':dlid' => $dlid));
    if (!isset($res[0]['size'])) {
        throw new exception($LN['error_downloadnotfound'] . ": $dlid");
    }

    return $res[0]['size'];
}


