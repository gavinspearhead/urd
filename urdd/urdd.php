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
 * $LastChangedDate: 2014-06-08 18:21:08 +0200 (zo, 08 jun 2014) $
 * $Rev: 3088 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd.php 3088 2014-06-08 16:21:08Z gavinspearhead@gmail.com $
 */
declare(ticks=1); // we need this for the signals to work properly.... all hail the splendid php documentation :-|
define('ORIGINAL_PAGE', 'URDD');
$pathu = realpath(dirname(__FILE__));

$process_name = 'urdd'; // needed for syslog and logging



require_once "$pathu/../functions/defines.php";
require_once "$pathu/../functions/error_codes.php";

function verify_installed()
{
    clearstatcache(); // we want to be sure, so cache values are flushed.
    $path = realpath(dirname(__FILE__) . '/../.installed');
    if ($path === FALSE || !file_exists($path)) {
        throw new exception('URDD is not installed properly. Please run the installer', ERR_CONFIG_ERROR);
    }
}

try {
    verify_installed();
} catch (exception $e) {
    die($e->getMessage() . "\n");
}

require_once "$pathu/../functions/autoincludes.php";
require_once "$pathu/../functions/defaults.php";
require_once "$pathu/../config.php";
require_once "$pathu/../functions/functions.php";
require_once "$pathu/../functions/db.class.php";
require_once "$pathu/../functions/file_functions.php";
require_once "$pathu/../functions/usenet_functions.php";
require_once "$pathu/../functions/config_functions.php";
require_once "$pathu/../functions/user_functions.php";
require_once "$pathu/../functions/urd_exceptions.php";
require_once "$pathu/../urdd/spots_functions.php";
require_once "$pathu/../urdd/group_functions.php";
require_once "$pathu/urdd_functions.php";
require_once "$pathu/queue_functions.php";
require_once "$pathu/show_functions.php";
require_once "$pathu/do_functions.php";
require_once "$pathu/post_functions.php";
require_once "$pathu/download_functions.php";
require_once "$pathu/../functions/urd_log.php";
require_once "$pathu/urdd_command.php";
require_once "$pathu/urdd_config.php";
require_once "$pathu/urdd_help.php";
require_once "$pathu/urdd_test.php";
require_once "$pathu/urdd_rss.php";
require_once "$pathu/urdd_extsetdata.php";
require_once "$pathu/urdd_error.php";
require_once "$pathu/urdd_sockets.php";
require_once "$pathu/urdd_options.php";
require_once "$pathu/urdd_protocol.php";
require_once "$pathu/../functions/lang/english.php";

function daemonise() // changes cwd to root!
{
    echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    $pid = pcntl_fork();
    if ($pid < 0) {  // error
        write_log('Daemonise failed', LOG_ERR);
        urdd_exit(INTERNAL_ERROR);
    } elseif ($pid != 0) {// parent
        urdd_exit(NO_ERROR); // parent exits
    } else { // child continues
        posix_setsid();
        chdir('/'); // set the working dir otherwise an umount might fail if we run start from some dir
        set_error_handler('urdd_error_handler'); // needed as we close stderr... and php will crash if something writes to stderr/out
        fclose(STDERR); // don't generate any output
        fclose(STDOUT); // don't generate any output
        umask(0);
    }
}

function update_user_last_seen_group(DatabaseConnection $db, $group_id)
{
    assert('is_numeric($group_id)');
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    $sql = '"last_login", "ID" FROM users';
    $res = $db->select_query($sql);
    if ($res === FALSE) {
        write_log('Cannot update user group settings; no data found', LOG_NOTICE);

        return FALSE;
    }
    $now = time();
    foreach ($res as $row) {
        $db->update_query_2('usergroupinfo', array('last_update_seen' => $now), '"userid"=? AND "groupid"=? AND "last_update_seen" < ?', array($row['ID'], $group_id, $row['last_login']));
    }

    return TRUE;
}

function handle_queue_item(DatabaseConnection $db, action $item, $nntp_enabled)
{
    echo_debug_function(DEBUG_WORKER, __FUNCTION__);
    assert('is_bool($nntp_enabled)');
    $cmd_code = $item->get_command_code();
    if (get_command($cmd_code) === FALSE) {
        urdd_exit(INTERNAL_FAILURE);
    }

    if ($nntp_enabled !== TRUE && $item->need_nntp()) {
        urdd_exit(URDD_NOERROR);
    }
    $rv = NO_ERROR;
    static $cmd_table = array(
        urdd_protocol::COMMAND_ADDDATA             => 'do_adddata',
        urdd_protocol::COMMAND_ADDSPOTDATA         => 'do_addspotdata',
        urdd_protocol::COMMAND_CHECK_VERSION       => 'do_check_version',
        urdd_protocol::COMMAND_CLEANDB             => 'do_cleandb',
        urdd_protocol::COMMAND_CLEANDIR            => 'do_cleandir',
        urdd_protocol::COMMAND_DELETE_SET          => 'do_delete_set',
        urdd_protocol::COMMAND_DELETE_SET_RSS      => 'do_delete_set_rss',
        urdd_protocol::COMMAND_DELETE_SPOT         => 'do_delete_spot',
        urdd_protocol::COMMAND_DOWNLOAD_ACTION     => 'do_download',
        urdd_protocol::COMMAND_EXPIRE              => 'do_expire',
        urdd_protocol::COMMAND_EXPIRE_RSS          => 'do_expire_rss',
        urdd_protocol::COMMAND_EXPIRE_SPOTS        => 'do_expire_spots',
        urdd_protocol::COMMAND_FINDSERVERS         => 'do_find_servers',
        urdd_protocol::COMMAND_GENSETS             => 'do_gensets',
        urdd_protocol::COMMAND_GETBLACKLIST        => 'do_getblacklist',
        urdd_protocol::COMMAND_GETWHITELIST        => 'do_getwhitelist',
        urdd_protocol::COMMAND_GETNFO              => 'do_getnfo',
        urdd_protocol::COMMAND_GETSETINFO          => 'do_getsetinfo',
        urdd_protocol::COMMAND_GETSPOTS            => 'do_getspots',
        urdd_protocol::COMMAND_GET_IMDB_WATCHLIST  => 'do_get_imdb_watchlist',
        urdd_protocol::COMMAND_GETSPOT_COMMENTS    => 'do_getspot_comments',
        urdd_protocol::COMMAND_GETSPOT_IMAGES      => 'do_getspot_images',
        urdd_protocol::COMMAND_GETSPOT_REPORTS     => 'do_getspot_reports',
        urdd_protocol::COMMAND_GROUPS              => 'do_listupdate',
        urdd_protocol::COMMAND_MAKE_NZB            => 'do_make_nzb',
        urdd_protocol::COMMAND_MERGE_SETS          => 'do_merge_sets',
        urdd_protocol::COMMAND_OPTIMISE            => 'do_optimise',
        urdd_protocol::COMMAND_PARSE_NZB           => 'do_parse_nzb',
        urdd_protocol::COMMAND_POST                => 'do_prepare_post',
        urdd_protocol::COMMAND_POST_SPOT           => 'do_post_spot',
        urdd_protocol::COMMAND_POST_ACTION         => 'do_post_batch',
        urdd_protocol::COMMAND_POST_MESSAGE        => 'do_post_message',
        urdd_protocol::COMMAND_PURGE               => 'do_purge',
        urdd_protocol::COMMAND_PURGE_RSS           => 'do_purge_rss',
        urdd_protocol::COMMAND_PURGE_SPOTS         => 'do_purge_spots',
        urdd_protocol::COMMAND_SENDSETINFO         => 'do_sendsetinfo',
        urdd_protocol::COMMAND_UNPAR_UNRAR         => 'do_unpar_unrar',
        urdd_protocol::COMMAND_UPDATE              => 'do_update',
        urdd_protocol::COMMAND_UPDATE_RSS          => 'do_update_rss'
    );
    if (isset($cmd_table[$cmd_code])) {
        $fn = $cmd_table[$cmd_code];
        if ($cmd_code == urdd_protocol::COMMAND_GETSPOT_COMMENTS) {
                do_getspot_comments($db, $item);
        } else {
        $rv = $fn($db, $item);
        }
    } else {
        write_log('Error: unknown action');
        throw new exception('Error: unknown action', ERR_UNKNOWN_ACTION);
    }
    urdd_exit($rv);
}

function handle_crash(DatabaseConnection $db, server_data &$servers, action $item, $rc)
{
    assert('is_numeric($rc)');
    echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    write_log("Unknown exit status: $rc. Possible crash", LOG_NOTICE);
    if (compare_command($item->get_command(), urdd_protocol::COMMAND_DOWNLOAD_ACTION) && $servers->has_equal($item)) {
        return; // if there are more dl threads running, we do nothing
    }
    if (compare_command($item->get_command(), urdd_protocol::COMMAND_POST_ACTION) && $servers->has_equal($item)) {
        return; // if there are more dl threads running, we do nothing
    }
    $dbid = $item->get_dbid();
    $status = get_queue_status($db, $dbid);
    if ($status == QUEUE_RUNNING) {
        echo_debug('Setting status to crashed', DEBUG_SERVER);
        update_queue_status($db, $dbid, QUEUE_CRASH, NULL, NULL, "Thread crashed ($rc)");
    }
}

function get_exit_code($status, $pid)
{
    assert('is_numeric($pid)');
    if (pcntl_wifexited($status)) {
        echo_debug('Normal exit', DEBUG_SERVER);
        $rc = pcntl_wexitstatus($status);
    } elseif (pcntl_wifsignaled($status)) {
        echo_debug('Signal happened', DEBUG_SERVER);
        $sig = pcntl_wtermsig($status);
        $rc = SIGNAL_TERM;
    } else {
        write_log('What now? Status is not a normal exit???', LOG_WARNING);
        // is it sane to set it to -1??? // we assume it crashed
        $rc = -1;
    }
    echo_debug("Child exited (rc: $rc; pid $pid)", DEBUG_SERVER);

    if ($rc == 255) { // bad stuff really
        write_log('PHP error happened... now what?', LOG_WARNING);
        write_log('We assume it is all okay anyway', LOG_WARNING);
        $rc = 0;
    }

    return $rc;
}

function reap_children(DatabaseConnection $db, server_data &$servers)
{
    /// xxx needs cleanup... too much crap here
    ///echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    try {
        while (($pid = pcntl_waitpid(-1, $proc_status, WNOHANG)) > 0) { // we check if there is a signal
            $rc = get_exit_code($proc_status, $pid);
            $servers->remove_kill_list($pid);
            list($item, $server_id, $status) = $servers->delete_thread($db, $pid, TRUE);
            echo_debug("Thread status $status; server: $server_id", DEBUG_SERVER);
            if ($rc == DB_FAILURE) {
                echo_debug('DB error received', DEBUG_SERVER); // does this ever happen?
                // push the action back on the queue if there was a problem with the database....
                $servers->queue_push($db, $item);
            } elseif ($rc == NNTP_NOT_CONNECTED_ERROR || $rc == NNTP_AUTH_ERROR || $rc == SERVER_INACTIVE || $rc == PIPE_ERROR) {
                if ($rc == NNTP_AUTH_ERROR || $rc == NNTP_NOT_CONNECTED_ERROR) {
                    if ($rc == NNTP_AUTH_ERROR) {
                        echo_debug('NNTP Authentication failed', DEBUG_SERVER);
                    } else {
                        echo_debug('NNTP Connection failed', DEBUG_SERVER);
                    }
                    // we need to disable the server
                    $timeout = get_config($db, 'connection_timeout', SERVER_CONNECTION_TIMEOUT); // 5 minutes
                    if ($rc == NNTP_AUTH_ERROR) { 
                        $timeout = 3600; // one hour
                    }
                    if ($timeout > 0) {
                        write_log ('Disabling server: ' . $server_id, LOG_WARNING);
                        $priority = $servers->get_priority($server_id); 
                        $servers->disable_server($server_id);
                        $servers->schedule_enable_server($db, $server_id, $item->get_userid(), $timeout, $priority);
                    }
                } elseif ($rc == SERVER_INACTIVE) {
                    $item->set_active_server(0);
                    $item->set_preferred_server(0);
                    $server_id = 0;
                }
                echo_debug('NNTP Connect error received', DEBUG_SERVER);
                $servers->do_reschedule($db, $item, $server_id);

            } elseif ($rc == DB_LOCKED) {
                $servers->reschedule_locked_item($db, $item);
            } elseif ($rc == ENCRYPTED_RAR) {
                $check_for_rar_encryption = get_pref($db, 'cancel_crypted_rars', $item->get_userid(), FALSE);
                $dlid = $item->get_args();
                if ($check_for_rar_encryption == encrar::ENCRAR_CANCEL) {
                    write_log('Cancelling download: encrypted rar', LOG_NOTICE);
                    $servers->delete_cmd($db, $item->get_userid(), $item->get_command(), $item->get_args(), TRUE);
                } elseif ($check_for_rar_encryption == encrar::ENCRAR_PAUSE) {
                    write_log('Pausing download: encrypted rar', LOG_NOTICE);
                    $servers->pause_cmd($db, $item->get_command(), $item->get_args(), TRUE, $item->get_userid());
                    set_download_password($db, $dlid, PASSWORD_PLACE_HOLDER); // we set the password so it won't stop again users should set it in download
                } else {
                    write_log('Ok we do nothing with this encrypted download? Okay we cancel it anyway', LOG_WARNING);
                    $servers->delete_cmd($db, $item->get_userid(), $item->get_command(), $item->get_args(), TRUE);
                }
                urd_mail::mail_user_download($db, $item->get_args(), $item->get_userid(), DOWNLOAD_CANCELLED_PW); // maybe update text of msg too XXX
                $comment = 'error_encryptedrar';
                update_dlinfo_comment($db, $dlid, $comment);
            } elseif ($rc == GROUP_NOT_FOUND) {
                echo_debug('Group not found', DEBUG_SERVER);
                // nothing to do really...
            } elseif ($rc == POST_FAILURE) {
                echo_debug('Posting failed', DEBUG_SERVER);
            } elseif ($rc == FILE_NOT_FOUND) {
                $args = split_args($item->get_args());
                write_log('Cancelling download: Improper NZB file', LOG_NOTICE);
                $servers->delete_cmd($db, $item->get_userid(), get_command(urdd_protocol::COMMAND_DOWNLOAD), $args[0], TRUE);
                urd_mail::mail_user_download($db, $args[0], $item->get_userid(), DOWNLOAD_CANCELLED);
            } elseif ($rc == RESTART_DOWNLOAD) {
                $dlid = $item->get_args();
                update_dlarticle_status($db, $dlid, DOWNLOAD_READY, DOWNLOAD_IS_PAR_FILE);
                set_download_par_files($db, $dlid, TRUE);
                $servers->recreate_download_command($db, $item, FALSE, TRUE);
            } elseif ($rc == HTTP_CONNECT_ERROR) {
                echo_debug('HTTP connection failed', DEBUG_SERVER);
            } elseif ($rc == GETARTICLE_ERROR) {
                // getting nzbs seems to have failed
                $item->add_failed_servers($item->get_preferred_server()); // do not try this server again
                // check if there is a server we haven't tried yet
                if ($servers->unused_servers_available($item->get_failed_servers()) !== FALSE) {
                    // we have to try another server
                    $servers->recreate_command($db, $item, $item->get_command(), FALSE, TRUE);
                } else {
                    update_queue_status($db, $item->get_dbid(), QUEUE_FAILED, 0, 0, '');
                    $args = split_args($item->get_args());
                    $dlid = $args[0];
                    dec_dl_lock($db, $dlid);
                    if (check_last_lock($db, $dlid)) { // we also set a lock on create so we need to remove that too
                        dec_dl_lock($db, $dlid);
                    }
                }
            } elseif ($rc < NO_ERROR) {
                echo_debug('A thread has crashed', DEBUG_SERVER);
                handle_crash($db, $servers, $item, $rc);
            } elseif ($rc == NO_ERROR) {
                if (get_queue_status($db, $item->get_dbid()) == QUEUE_RUNNING) {
                    // it probably crashed for some reason - push it back on the queue
                    write_log('Thread exitted for no apparent reason - rescheduling', LOG_NOTICE);
                    $servers->queue_push($db, $item, FALSE);
                } elseif ($status <= DOWNLOAD_ACTIVE) {
                    $cmd = $item->get_command();
                    if (compare_command($cmd, urdd_protocol::COMMAND_DOWNLOAD_ACTION)) {
                        complete_download($db, $servers, $item, $status);
                    } elseif (compare_command($cmd, urdd_protocol::COMMAND_POST_ACTION)) {
                        complete_post($db, $servers, $item, $status);
                    } elseif (compare_command($cmd, urdd_protocol::COMMAND_POST)) {
                        queue_post($db, $servers, $item->get_args(), $item->get_userid(), DEFAULT_PRIORITY);
                    } elseif (compare_command($cmd, urdd_protocol::COMMAND_UPDATE)) {
                        queue_gensets($db, $servers, array($item->get_args()), $item->get_userid(), DEFAULT_PRIORITY - 1);
                    } elseif (compare_command($cmd, urdd_protocol::COMMAND_GENSETS)) {
                        if (get_config($db, 'auto_expire') == 1 && !$item->is_paused()) {
                            queue_purge_expire($db, urdd_protocol::COMMAND_EXPIRE, array($item->get_args()), $item->get_userid(), $servers, DEFAULT_PRIORITY + 2);
                        }
                        if (get_config($db, 'auto_getnfo') == 1 && !$item->is_paused()) {
                            queue_getnfo($db, $servers, $item->get_userid(), DEFAULT_PRIORITY);
                        }
                    } elseif (compare_command($cmd, urdd_protocol::COMMAND_UPDATE_RSS)) {
                        if (get_config($db, 'auto_expire') == 1 && !$item->is_paused()) {
                            queue_purge_expire($db, urdd_protocol::COMMAND_EXPIRE_RSS, array($item->get_args()), $item->get_userid(), $servers, DEFAULT_PRIORITY);
                        }
                    } elseif (compare_command($cmd, urdd_protocol::COMMAND_GETSPOTS)) {
                        if (get_config($db, 'download_spots_images') == 1 && !$item->is_paused()) {
                            queue_getspot_images($db, $servers, $item->get_userid(), DEFAULT_PRIORITY);
                        }
                        if (get_config($db, 'download_spots_reports') == 1 && !$item->is_paused()) {
                            queue_getspot_reports($db, $servers, $item->get_userid(), DEFAULT_PRIORITY);
                        }
                        if (get_config($db, 'download_spots_comments') == 1 && !$item->is_paused()) {
                            queue_getspot_comments($db, $servers, $item->get_userid(), DEFAULT_PRIORITY);
                        }
                    } elseif (compare_command($cmd, urdd_protocol::COMMAND_PARSE_NZB)) {
                        $args = split_args($item->get_args());
                        if (get_start_time($db, $args[0]) <= time()) {// needed otherwise dl in browse with timestamp won't work,
                            //   but unpause is needed for other dls (spool, nzb import/upload)
                            $servers->pause_cmd($db, urdd_protocol::COMMAND_DOWNLOAD, $args[0], FALSE, $item->get_userid());
                        }
                    } elseif (compare_command($cmd, urdd_protocol::COMMAND_FINDSERVERS)) {
                        restart_urdd($db, $servers);
                    }
                }
            } else {
                echo_debug("Something weird happened ($rc). Finishing up thread.", DEBUG_SERVER);
            }
            usleep(000);
        }
    } catch (exception $e) {
        write_log($e->getMessage(), LOG_ERR);
        echo_debug_trace($e, DEBUG_SERVER);
        urdd_exit(INTERNAL_FAILURE);
    }
}

function check_queue(DatabaseConnection& $par_db, conn_list &$conn_list, server_data &$servers)
{
    reap_children($par_db, $servers);

    $item = $servers->get_first_runnable_on_queue($par_db);
    if ($item === FALSE || $item === TRUE) { // FALSE : no thread can run; TRUE: queue empty
        return $item;
    }
    $command = $item->get_command_code();
    // if it is a download request, select the server it will run on then make n new queued download actions and run these on the server
    if ($command == urdd_protocol::COMMAND_DOWNLOAD) {
        create_download_threads($par_db, $servers, $item);
        return FALSE;
    } elseif ($command == urdd_protocol::COMMAND_START_POST) {
        create_post_threads($par_db, $servers, $item);
        return FALSE;
    } elseif ($command == urdd_protocol::COMMAND_ADDDATA) {
        if ($item->get_preview()) { // if no server is available or no total free slot, we simple force one
            if ($servers->slots_available() === FALSE) {
                try {
                    $servers->preempt($par_db, $item, $item->get_userid());
                    usleep(5000);// wait so that the chld signal is delivered and the reap function calls it
                    return FALSE;
                } catch (exception $e) {
                    write_log('Cannot preempt', LOG_INFO);
                    return FALSE;
                }
            }
        }
    } elseif ($command == urdd_protocol::COMMAND_DOWNLOAD_ACTION) {
        if ($item->get_preview()) { // if no server is available or no total free slot, we simple force one
            $srv_id = $servers->find_free_slot($item->get_all_failed_servers(), FALSE); // is there a server that has a free slot
            $nntp_slots_available = $servers->nntp_slots_available(); // is there a total free slot
            if ($srv_id === FALSE || $nntp_slots_available === FALSE) {
                try {
                    $srv_id = $servers->preempt($par_db, $item, $item->get_userid());
                    if ($srv_id == 0) {
                        write_log('Server ID is 0 must be something wrong', LOG_WARNING);
                    }
                    $item->set_preferred_server($srv_id);
                    usleep(5000); // wait so that the chld signal is delivered and the reap function calls it
                    return FALSE;
                } catch (exception $e) {
                    write_log('Cannot preempt: ' . $e->getMessage(), LOG_INFO);
                    return FALSE;
                }
            }
        }
    }
    // there is something to start
    if (get_command_primary_nntp($item->get_command())) {
        $srv_id = $servers->get_update_server();
        $item->set_preferred_server($srv_id);
        $item->set_active_server(0);
    }
    $pid = pcntl_fork();
    if ($pid < 0) { //error
        update_queue_status($par_db, $item->get_dbid(), QUEUE_FAILED);
        write_log('Error: fork failed');
        urdd_exit(INTERNAL_FAILURE);
    } elseif ($pid != 0) { // parent
        $cnt = 0;
        for (;;) { // connecting may be interrupted. We need to make sure that the connection is restored.
            try {
                $par_db = connect_db(TRUE);
                break;
            } catch (exception $e) {
                usleep (++$cnt * 100); // sleep a bit but not too much as urdd gets unresponsive then
                if ($cnt == 1000) {
                    throw $e; // something really bad must be wrong here
                }
            }
        }

        try {
            pcntl_signal(SIGCHLD, 'sig_handler', FALSE); // we want the signal ... but it needn't do anything
            $servers->add_thread(new thread($pid, $item));
            echo_debug("Worker forked: PID $pid", DEBUG_SERVER);
            $servers->queue_delete($par_db, $item->get_id(), user_status::SUPER_USERID); // remove the item from the queue
        } catch (exception $e) {
            write_log('Cannot start thread: ' . $e->getMessage(), LOG_NOTICE);
            echo_debug_trace($e, DEBUG_SERVER);
            // it probably will still fail on the reap function, but this shouldn't happen anyway now
        }
    } else { // child
        $nntp_enabled = $servers->get_nntp_enabled();
        unset($servers, $db, $command, $pid);
        start_child($item, $conn_list, $nntp_enabled);
    }

    return FALSE;
}

function child_sig_handler($foo)
{
    exit(0);
}

function start_child(action $item, conn_list $conn_list, $nntp_enabled)
{
    global $is_child;
    assert('is_bool($nntp_enabled)');
    try {
        $is_child = TRUE; // for overriding the shutdown function
        pcntl_signal(SIGTERM, 'child_sig_handler');
        pcntl_signal(SIGCHLD, SIG_DFL);
        pcntl_signal(SIGINT, SIG_DFL);
        set_error_handler('urdd_error_handler'); // needed as we close stderr... and php will crash if something writes to stderr/out
        $conn_list->close_all(); // needed otherwise quit will not exit if children are running
        $child_db = connect_db(TRUE);
        update_queue_status($child_db, $item->get_dbid(), QUEUE_RUNNING);
        handle_queue_item($child_db, $item, $nntp_enabled);
        // doesn't return here,
        write_log('You should never see this', LOG_ERR);
        urdd_exit(INTERNAL_FAILURE); // for safety tho
    } catch (exception $e) {
        $code = $e->getCode();
        write_log("Child Died? {$e->getMessage()} ({$e->getCode()})", LOG_WARNING);
        urdd_exit($code <= 0 ? INTERNAL_FAILURE : -$code);
    }
}

function check_schedule(DatabaseConnection $db, conn_list &$conn_list, server_data &$servers)
{
    //    echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    global $config;
    if ($config['scheduler'] !== TRUE) {
        return;
    }
    while (($job = $servers->get_first_ready_job($db)) !== FALSE) {
        $action = $job->get_action();
        $line = "{$action->get_command()} {$action->get_args()}";
        $response = '';
        $cmd = do_command($db, $line, $response, $conn_list, $s=NULL, $servers, $action->get_userid(), NULL, FALSE);
        if ($cmd == URDD_SHUTDOWN) {  // shutdown requested
            shutdown_urdd($db, $servers);
        } elseif ($cmd == URDD_RESTART) {
            restart_urdd($db, $servers);
        }
        $servers->recur_schedule($db, $job); // reschedule the action if needed
    }
}

function reset_download_status(DatabaseConnection $db)
{
    $db->update_query_2('downloadinfo', array('status'=>DOWNLOAD_QUEUED), '"status" IN (?,?,?)', array(DOWNLOAD_READY, DOWNLOAD_ACTIVE, DOWNLOAD_QUEUED));
}

function server(urdd_sockets $listen_sockets, DatabaseConnection $db, server_data &$servers)
{
    echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    global $config;
    $conn_list = new conn_list(get_config($db, 'urdd_timeout', socket::DEFAULT_SOCKET_TIMEOUT));
    $restart = $config['urdd_restart'];
    reset_download_status($db);

    restore_old_queue($db, $servers, $conn_list, $restart);
    $username = get_config($db, 'run_update');
    if ($username != '0') { // some install magic, after the install we run update group automagically with the user given by the installer
        set_config($db, 'run_update', '0');
        if (check_user(db, $username) === TRUE) {
            // run the update groups command when just installed as the main user/admin
            // should do this differently // TODO
            $userid = get_userid($db, $username);
            queue_groups($db, $servers, $userid);
        } else {
            write_log('Invalid user specified for update group list', LOG_NOTICE);
        }
    }
    unset($username, $userid);
    set_config($db, 'urdd_startup', '100');
    while (1) {
        $conn_list->close_timedout();
        nzb_poller::poll_nzb_dir($db, $servers); // see if there are nzb files in any of the spool directors
        check_schedule($db, $conn_list, $servers); // check if there are scheduled jobs to run
        //  $servers->check_conn_time(); 
        $queue_ready = check_queue($db, $conn_list, $servers);
        if ($queue_ready === TRUE) {
            $timeout = $conn_list->first_timeout();
            // queue is empty, we can wait till something arrives
        } else {
            // there is still stuff in the queue, just check if there is any msg
            $timeout = 0;
        }
        $sched_time = NULL;
        if ($config['scheduler'] === TRUE) {
            $sched_time = $servers->get_first_timeout();
        }
        $sched_timeout = urdd_sockets::DEFAULT_CHECK_TIMEOUT;
        if ($sched_time !== NULL) {
            $sched_timeout = max(0, $sched_time - time());
        }
        if ($timeout === NULL) {
            $timeout = $sched_timeout;
        } elseif ($sched_timeout != 0) {
            $timeout = min($timeout, $sched_timeout);
        }
        $timeout *= 1000000; // to microseconds
        $timeout_us = max(min($timeout, urdd_sockets::DEFAULT_CHECK_TIMEOUT), 1000); // we at least wait 100 us
        $timeout = 0;
        $sq = $conn_list->get_fdlist();
        $res = $listen_sockets->select(0, $timeout_us, $sq);
        if ($res === FALSE) {
            if (socket_last_error() == 4) {// child exited, we can schedule some more now
                socket_clear_error();
                continue;
            } else {
                socket_error_handler();
            }
        } elseif ($res == 0) {
            //time out but no read... go back to do check queue
            continue;
        } else {
            $listen_sockets->read_sockets($db, $sq, $conn_list, $servers);
        }
    }
}

function restore_old_queue(DatabaseConnection $db, server_data &$servers, conn_list &$conn_list, $restart)
{
    assert('is_bool($restart)');
    echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    try {
        $like = $db->get_pattern_search_command('LIKE');
        $query = "\"description\", \"ID\", \"userid\", \"restart\", \"priority\", \"status\" FROM queueinfo WHERE \"status\" $like :qr";
        $res_running = $db->select_query($query, array(':qr'=>QUEUE_RUNNING));
        $query = "\"description\", \"ID\", \"userid\", \"status\", \"restart\", \"priority\" FROM queueinfo WHERE (\"status\" $like :qq OR \"status\" $like :qp)";
        $res_queued = $db->select_query($query, array(':qp'=>QUEUE_PAUSED, ':qq'=>QUEUE_QUEUED));
        if (is_array($res_running)) {
            $response = '';
            foreach ($res_running as $row) {
                $cmd = $row['description'];
                $priority = $row['priority'];
                $restarted = FALSE;
                if ($restart === TRUE && $row['restart'] > 0) {
                    $userid = $row['userid'];
                    do_command($db, $cmd, $response, $conn_list, NULL, $servers, $userid, $priority, TRUE);
                    $restarted = TRUE;
                }
                $res = $db->delete_query('queueinfo', '"ID"=?', array($row['ID']));
                if ($restarted === TRUE) {
                    write_log("Restored '$cmd'", LOG_NOTICE);
                } else {
                    write_log ("Removed stale '$cmd'", LOG_NOTICE);
                }
            }
        }
        if (is_array($res_queued)) {
            $response = '';
            foreach ($res_queued as $row) {
                $cmd = $row['description'];
                $args = commands_list::get_arg_list($cmd);
                $cmd = $args[0];
                if (compare_command($cmd, urdd_protocol::COMMAND_DOWNLOAD) || compare_command($cmd, urdd_protocol::COMMAND_MAKE_NZB)) {
                    $dlid = $args[1];
                    echo_debug("Reseting lock $dlid", DEBUG_SERVER);
                    reset_dl_lock($db, $dlid);
                }
            }
            foreach ($res_queued as $row) {
                $cmd = $row['description'];
                $priority = $row['priority'];
                $restarted = FALSE;
                if ($restart === TRUE && $row['restart'] > 0) {
                    $userid = $row['userid'];
                    do_command($db, $cmd, $response, $conn_list, NULL, $servers, $userid, $priority, TRUE);
                    if ($row['status'] == QUEUE_PAUSED) {
                        preg_match_all('/\[([0-9]+)\]/', $response, $match);
                        foreach ($match[1] as $m) {
                            if (is_numeric($m)) {
                                $arg = array ("{$m}");
                                do_pause($db, $servers, $arg, TRUE, $userid);
                                if (compare_command($cmd, urdd_protocol::COMMAND_DOWNLOAD)) {
                                    update_dlinfo_status($db, DOWNLOAD_PAUSED, $m);
                                }
                                if (compare_command($cmd, urdd_protocol::COMMAND_POST)) {
                                    update_postinfo_status($db, POST_PAUSED, $m);
                                }
                            }
                        }
                    }
                    $restarted = TRUE;
                }
                $res = $db->delete_query('queueinfo', '"ID"=?', array($row['ID']));
                if ($restarted === TRUE) {
                    write_log("Restored '$cmd'", LOG_NOTICE);
                } else {
                    write_log("Removed stale '$cmd'", LOG_NOTICE);
                }
            }
        }
        $servers->reload_scheduled_jobs($db);
    } catch (exception $e) {
        write_log('Restore failed: ' . $e->getmessage(), LOG_ERR);
    }
}

function set_urdd_userid(DatabaseConnection $db)
{
    global $config;
    $cur_uid = posix_getuid();
    $cur_euid = posix_geteuid();
    $urdd_gid = get_config($db, 'urdd_gid');
    $urdd_uid = get_config($db, 'urdd_uid');
    if ($cur_uid == 0 || $cur_euid == 0) {
        write_log('We are started as root. Dropping priviliges', LOG_NOTICE);
        // we are root and must reduce to
        if ($urdd_gid != '') {
            $ginfo = posix_getgrnam($urdd_gid);
            if (!isset($ginfo['gid'])) {
                throw new exception ("Could not drop priviliges. Group $urdd_gid not found.", ERR_GID_NOT_FOUND);
            }
            chgrp($config['log_file'], $ginfo['gid']);
            $rv1 = posix_setgid($ginfo['gid']);
            $rv2 = posix_setegid($ginfo['gid']);
            write_log("Dropping priviliges to group $urdd_gid", LOG_NOTICE);

            if ($rv1 === FALSE || $rv2 === FALSE) {
                throw new exception ('Could not drop priviliges', ERR_DROP_PRIVS_FAILED);
            }
        }
        if ($urdd_uid != '') {
            $uinfo = posix_getpwnam($urdd_uid);
            if (!isset($uinfo['uid'])) {
                throw new exception ("Could not drop priviliges. User $urdd_uid not found.", ERR_UID_NOT_FOUND);
            }
            chown($config['log_file'], $uinfo['uid']);
            $rv1 = posix_setuid($uinfo['uid']);
            $rv2 = posix_seteuid($uinfo['uid']);
            write_log("Dropping priviliges to user $urdd_uid", LOG_NOTICE);
            if ($rv1 === FALSE || $rv2 === FALSE) {
                throw new exception ('Could not drop priviliges', ERR_DROP_PRIVS_FAILED);
            }
        }
    } else {
        posix_seteuid(posix_getuid());
        posix_setegid(posix_getgid());
    }
}

function stupid_php_crap()
{
    date_default_timezone_set(date_default_timezone_get()); // silly but otherwise we get tons of notices for each time fn call with E_STRICT
}

function find_server(DatabaseConnection $db, server_data $servers, test_result_list $test_results)
{
    global $config;
    $servers->find_servers($db, $test_results, NULL, ($config['find_servers_type'] == 'extended'), TRUE);
}

function get_server_data(DatabaseConnection $db)
{
    //global $config;
    $urdd_maxthreads = get_config($db, 'urdd_maxthreads'); // total number of threads allowed
    $nntp_maxthreads = get_config($db, 'nntp_maxthreads'); // total number of threads that connect to a news server allowed
    $db_intensive_maxthreads = get_config($db, 'db_intensive_maxthreads'); // total number of threads that are marked db intensive
    if ($db_intensive_maxthreads == 0) {
        $db_intensive_maxthreads = $urdd_maxthreads; // if we set it to 0, we mean no limit, which is limited by the actual max threads :)
    }
    $servers = new server_data(get_config($db, 'queue_size'), $nntp_maxthreads, $urdd_maxthreads, $db_intensive_maxthreads);

    return $servers;
}

function respawn_urdd()
{
    $pid = pcntl_fork();
    if ($pid < 0) {  // error
        write_log('Daemonise failed', LOG_ERR);
        urdd_exit(INTERNAL_ERROR);
    } elseif ($pid != 0) {// parent
        urdd_exit(NO_ERROR); // parent exits
    } else {
        start_urdd();
    }
}

//////// Code: ////////////

$start_time = time();
set_time_limit(0);

// not all versions of libxml seem to have this parameter

if (!defined('LIBXML_PARSEHUGE')) {
    define ('LIBXML_PARSEHUGE', 0);
}

// enable asserts
set_assert(TRUE);
// disable asserts
//set_assert(FALSE);
$is_child = FALSE; // this is the main start up, so we are always the parent
// make PHP verbose
ini_set('display_errors', '1');
ini_set('log_errors', '1');
//ini_set('error_reporting', E_ALL|E_STRICT|E_DEPRECATED);

try {
    verify_installed();
    $test_results = new test_result_list();
    verify_php_version(FALSE);
    verify_safe_mode();
    stupid_php_crap();
} catch (exception $e) {
    echo $e->getmessage() . "\n";
    urdd_exit($e->getcode());
}
try {
    urd_cli_options::read_options();
    verify_logfile();
   // verify_bool('urdd_daemonise', $config);
} catch (exception $e) {
    if ($e->getcode () == COMMANDLINE_ERROR) {
        write_log('Incorrect commandline option: ' . $e->getmessage(), LOG_ERR);
    } else {
        write_log('Config error?' . $e->getmessage(), LOG_ERR);
    }
    urdd_exit($e->getcode());
}

// now check too if it is the recommended version too!
verify_php_version(TRUE);

try {
    write_log('Starting urdd', LOG_NOTICE);
} catch (exception $e) {
    echo $e->getmessage(), "\n"; // must be echo, cause if it fails the log file cannot be written to
    urdd_exit ($e->getcode());
}

try {
    if (isset($config['updatedb']) && $config['updatedb'] === TRUE) {
        $quiet = TRUE;
        require($pathu . '/../install/update_db.php');
    }
    $db = connect_db(TRUE);
    check_deprecated_db($db);

    $config['urdd_pidfile'] = get_config($db, 'pidpath', '');
    if (!isset($config['urdd_daemonise'])) {
        $config['urdd_daemonise'] = get_config($db, 'urdd_daemonise', FALSE) ? TRUE : FALSE;
    }
    if (isset($config['keystore'])) {
        keystore::create_keystore($db, $config['keystore']);
        unset($config['keystore']);
        urdd_exit(NO_ERROR);
    }
    if ($config['urdd_daemonise'] === TRUE) {
        daemonise(); // start it as a daemon process
        $db = connect_db(TRUE);
    }
    if (isset($config['urdd_pidfile']) && $config['urdd_pidfile'] != '') {
        try {
            if (substr($config['urdd_pidfile'], -9) != '/urdd.pid') {
                $config['urdd_pidfile'] .= '/urdd.pid';
            }
            check_pid_file($config['urdd_pidfile']);
        } catch (exception $e) {
            if (!isset($config['force_pidfile']) || $config['force_pidfile'] !== TRUE) {
                write_log($e->getmessage(), LOG_ERR);
                die;
            }
            delete_pid_file($config['urdd_pidfile']);
        }
        try {
            set_pid_file($config['urdd_pidfile']);
        } catch (exception $e) {
            write_log($e->getmessage(), LOG_ERR);
        }
    } else {
        write_log('Update the configuration; set the pid-file location in admin/config/URD Daemon', LOG_ERR);
    }

    $dummy = array();
    check_php_modules($test_results, get_config($db, 'modules', urd_modules::URD_CLASS_ALL), $dummy);
    register_shutdown_function('status_shutdown_handler'); // so that the startup status will be reset in case of urdd termination during startup
    set_config($db, 'urdd_startup', '1');
    verify_config($db, $test_results);
    set_config($db, 'urdd_startup', '10');
    verify_magpie_cache_dir($db, $test_results);
    verify_memory_limit($test_results);
    keystore::verify_keystore($db);
    set_config($db, 'urdd_startup', '20');
    set_db_version($db);
    load_config($db, TRUE);
    $commands_list->update_settings(urd_modules::get_urd_module_config(get_config($db, 'modules', urd_modules::URD_CLASS_ALL)));
    $config['find_servers'] = (isset($config['find_servers']) && $config['find_servers'] === TRUE) ? TRUE: FALSE; // force value to be set
    $servers = get_server_data($db);
    $servers->enable_check_nntp_connections($config['check_nntp_connections']);
    try {
        $servers->load_servers($db, $test_results, FALSE);
    } catch (exception $e) {
        if ($e->getcode() == ERR_NO_ACTIVE_SERVER) {
            write_log($e->getmessage(), LOG_ERR);
        }
    }
    set_config($db, 'urdd_startup', '50');
    generate_server_keys($db);
    generate_user_keys($db);
    if ($config ['find_servers'] === TRUE) {
        find_server($db, $servers, $test_results);
    }
    if (isset($config['test_servers']) && $config['test_servers'] === TRUE) {
        $servers->test_servers($db, $test_results);
    }

    set_config($db, 'urdd_startup', '80');
    set_handlers();
} catch (exception $e) {
    write_log('An error occured during startup of URD daemon: ' . $e->getMessage(), LOG_ERR);
    echo_debug_trace($e, DEBUG_SERVER);
    urdd_exit($e->getcode());
}

try {
    $listen_sock = new urdd_sockets();
    $listen_sock->listen_socket($config['urdd_listen_host'], $config['urdd_listen_host6'], $config['urdd_port']);
    set_config($db, 'urdd_startup', '90');
    set_urdd_userid($db);
    $servers->enable_nntp(TRUE);
    set_config($db, 'urdd_startup', '99');
    server($listen_sock, $db, $servers); // actually start the urd daemon
} catch (exception $e) {
    if ($e->getcode() == ERR_RESTART_REQUESTED) {
        respawn_urdd();
    } else {
        $message = $e->getmessage();
        $code = $e->getcode();
        if ($is_child) {
            write_log ("A Thread terminated prematurely: $message ($code)", LOG_ERR);
            urdd_exit(-$code);
        } else {
            write_log ("An error occured during startup of URD daemon: $message ($code)", LOG_CRIT);
            write_log ('is there another URD daemon running?', LOG_NOTICE);
            echo_debug_trace($e, DEBUG_SERVER);
            urdd_exit($code <= 0 ? -1 : -$code);
        }
    }
}
