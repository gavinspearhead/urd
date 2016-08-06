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
 * $LastChangedDate: 2014-05-30 00:49:17 +0200 (vr, 30 mei 2014) $
 * $Rev: 3077 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_error.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function urdd_error_handler($errno, $errstr, $errfile, $errline)
{
    $current_level = error_reporting();
    switch ($errno & $current_level) {
    case 0:  // Don't display anything
        break;
    case E_PARSE:
        write_log("Parse error on line $errline in file $errfile: $errstr ($errno)", LOG_ERR);
        urdd_exit(INTERNAL_FAILURE);
        break;
    case E_ERROR:
    case E_COMPILE_ERROR:
    case E_CORE_ERROR:
    case E_USER_ERROR:
        write_log("Fatal error on line $errline in file $errfile: $errstr ($errno)", LOG_ERR);
        urdd_exit(INTERNAL_FAILURE);
        break;
    case E_WARNING:
    case E_COMPILE_WARNING:
    case E_CORE_WARNING:
    case E_USER_WARNING:
        write_log("Warning on line $errline in file $errfile: $errstr ($errno)", LOG_WARNING);
        break;
    case E_NOTICE:
    case E_USER_NOTICE:
        write_log("Notice on line $errline in file $errfile: $errstr ($errno)", LOG_NOTICE);
        break;
    case E_STRICT :
    case E_RECOVERABLE_ERROR:
        //ignore
        break;
    default:
        write_log("Unknown error on line $errline in file $errfile: $errstr ($errno)", LOG_INFO);
        break;
    }

    /* Don't execute PHP internal error handler */
    return TRUE;
}


function urdd_exit($errno)
{
    exit($errno);
}

function sig_handler($signo=0)
{
    echo_debug_function(DEBUG_SIGNAL, __FUNCTION__);
}

function kill_handler($signo=0)
{
    pcntl_signal(SIGTERM, SIG_IGN, FALSE);
    pcntl_signal(SIGINT, SIG_IGN, FALSE);
    echo_debug_function(DEBUG_SIGNAL, __FUNCTION__);
    // shutdown handler will be called now
    urdd_exit(NO_ERROR);
}

function status_shutdown_handler()
{
    global $is_child, $db;
    if (!$is_child) {
        try {
            set_config($db, 'urdd_startup', '0');
        } catch (exception $e) {
           echo_debug('Exception: ' . $e->getMessage(), DEBUG_SERVER);
        }
    }
}

function shutdown_handler()
{
    pcntl_signal(SIGTERM, SIG_IGN, FALSE);
    pcntl_signal(SIGINT, SIG_IGN, FALSE);
    echo_debug_function(DEBUG_SIGNAL, __FUNCTION__);
    global $servers, $is_child, $db, $config;
    if ($is_child) {
        echo_debug('shutdown handler called as child', DEBUG_SERVER);
    } else {
        write_log('shutdown handler called as parent', LOG_INFO);
    }
    try {
        if (!$is_child) {
            $servers->delete_all($db, user_status::SUPER_USERID, FALSE);
            if (isset ($config['urdd_pidfile'])) {
                delete_pid_file($config['urdd_pidfile']);
            }
        }
    } catch (exception $e) {
        write_log('Exception: ' . $e->getMessage(), LOG_ERR);
    }
    urdd_exit(NO_ERROR);
}

function hup_handler($signo=0)
{
    echo_debug_function(DEBUG_SIGNAL, __FUNCTION__);
    global $config, $is_child, $logfile;
    $logfile->reopen_logfile($config['log_file']);
    if (!$is_child) {
        pcntl_signal(SIGHUP, SIG_IGN);
        posix_kill(0, SIGHUP);
        usleep(20000);
        pcntl_signal(SIGHUP, 'hup_handler', FALSE);
    }
}

function restart_handler($signo=0)
{
    echo_debug_function(DEBUG_SIGNAL, __FUNCTION__);
    global $is_child, $db, $listen_sock;
    if (!$is_child) {
        $pid = pcntl_fork();
        if ($pid < 0) {  // error
            write_log('Forking failed', LOG_ERR);
            urdd_exit(INTERNAL_ERROR);
        } elseif ($pid != 0) { // parent
           urdd_exit(NO_ERROR);
        } else {
            foreach ($listen_sock as $s) {
                socket_close($s);
            }
            usleep(1000000);
            $db = connect_db();
            //start urdd will exec urdd again
            start_urdd();
            // so we need to die here
            die;
        }
    }
}

function set_handlers()
{
    echo_debug_function(DEBUG_SIGNAL, __FUNCTION__);

    pcntl_signal(SIGCHLD, 'sig_handler', FALSE); // we want the signal ... but it needn't do anything
    pcntl_signal(SIGTERM, 'kill_handler', FALSE);
    pcntl_signal(SIGINT,  'kill_handler', FALSE);
    pcntl_signal(SIGHUP,  'hup_handler',  FALSE);
    pcntl_signal(SIGUSR1, 'restart_handler', FALSE);
    register_shutdown_function('shutdown_handler');
}
