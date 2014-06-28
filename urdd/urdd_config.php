<?php

/*
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
 * $LastChangedDate: 2014-05-30 00:49:17 +0200 (vr, 30 mei 2014) $
 * $Rev: 3077 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_config.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function verify_bool($entry, array $config)
{
    if (!is_bool($config[$entry])) {
        throw new exception("Boolean value required for $entry", ERR_CONFIG_ERROR);
    }
}

function verify_isnumeric($entry, array $config)
{
    if (!is_numeric($config[$entry])) {
        throw new exception("Numeric value required for $entry {$config[$entry]}", ERR_CONFIG_ERROR);
    }
}

function verify_dlpaths(DatabaseConnection $db, $path, test_result_list &$test_results)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert($path != '');
    if (strpos($path, '..') !== FALSE) {// we don't want relative paths (maybe slightly too tight tho)
        throw new exception('Invalid directory: ' . $path, ERR_CONFIG_ERROR);
    }
    $path = my_realpath($path);
    clearstatcache(); // we want to be sure, so cache values are flushed.
    add_dir_separator($path);

    if (!file_exists($path)) { // check if the path exists
        $rv = @mkdir($path, 0775, TRUE); // if not make it (set the group rights to rwx because we use the group mostly)
        if (!$rv) {
            throw new exception('Cannot create download path: ' . $path, ERR_CONFIG_ERROR);
        }
        set_group($db, $path); // set the group from the config
        clearstatcache(); // we want to be sure, so cache values are flushed.
        if ((!is_dir($path) || !is_writable($path))) {// check if the just created path is writable
            throw new exception('Cannot create download path: ' . $path, ERR_CONFIG_ERROR);
        }
    } elseif (!is_dir($path) || !is_writable($path)) {// if it exist, check if it is a dir and if it is writable
        throw new exception ('Download path not set correctly: ' . $path, ERR_CONFIG_ERROR);
    }

    $paths = get_all_paths($path, '', TRUE); // get the main paths needed (tmp, done, preview)
    foreach ($paths as $p) {
        if (!file_exists($p)) {
            $rv = create_dir($p, 0775); // and create the paths if they exist
            if ($rv === FALSE) {
                throw new exception("Could not create directory: $p", ERR_CONFIG_ERROR);
            }
            set_group($db, $p); // change the group if config is set
        }
        clearstatcache(); // we want to be sure, so cache values are flushed.
        if ((!is_dir($p) || !is_writable($p))) {// check if it valid now
            throw new exception("Directory not accessible: $p", ERR_CONFIG_ERROR);
        }
        test_file_creation($p);
        if (!is_cache_dir($db, $p)) {
            create_required_user_dirs($db, $p);
        }

        $test_results->add(new test_result('Directory '. $p, TRUE, 'Directory usable'));
    }

    return $path;
}


function verify_memory_limit(test_result_list &$test_results)
{
    $memory_size = unformat_size(ini_get('memory_limit'));
    if ($memory_size < (128 * 1024 * 1024) && $memory_size >= 0) {
        write_log('Memory limit should be set to at least 128M in ' . php_ini_loaded_file(), LOG_WARNING);
        $test_results->add(new test_result('Memory limit', FALSE, 'Memory limit below 128M'));
    }
    $test_results->add(new test_result('Memory limit', TRUE, 'Memory limit above 128M'));
}


function verify_magpie_cache_dir(DatabaseConnection $db, test_result_list &$test_results)
{
    $path = get_magpie_cache_dir($db);
    clearstatcache(); // we want to be sure, so cache values are flushed.
    if (!file_exists($path)) {
        $rv = @mkdir($path, 0775, TRUE);
        set_group($db, $path);
        if (!$rv) {
            throw new exception('Cannot create Magpie cache directory: ' . $path, ERR_CONFIG_ERROR);
        }
    } elseif (!is_dir($path) || !is_writable($path)) {
        throw new exception ('Magpie cache directory not set correctly: ' . $path, ERR_CONFIG_ERROR);
    }
    $test_results->add(new test_result('Magpie Cache dir', TRUE, 'Magpie directory accessable'));

    return $path;
}


function verify_logfile()
{
    global $config;
    if (isset($config['urdd_logfile'])) { // have we set a log file
        $config['log_file'] = $config['urdd_logfile'];
    } else {// if not we discard it all
        $config['log_file'] = '/dev/null';
    }
}


function verify_config(DatabaseConnection $db, test_result_list &$test_results)
{
    global $config;

    check_prefs($db);
    $prefs = load_config($db);
    $dlpath = get_dlpath($db);
    verify_dlpaths($db, $dlpath, $test_results);
    /* verify parameters we have */
    verify_isnumeric('urdd_maxthreads', $prefs);
    verify_isnumeric('urdd_port', $config);
    verify_isnumeric('urdd_port', $prefs);
    verify_isnumeric('queue_size', $prefs);
    verify_isnumeric('default_expire_time', $prefs);

    if (!isset($config['urdd_restart']) || !is_bool($config['urdd_restart'])) {
        $config['urdd_restart'] = $prefs['urdd_restart'] == 1 ? TRUE : FALSE;
    }

    $scheduler = get_config($db, 'scheduler', TRUE);
    $config['scheduler'] = $scheduler == 'off' ? FALSE : TRUE;

    if (!isset($config['check_nntp_connections'])) {
        if (isset($prefs['check_nntp_connections'])) {
            $config['check_nntp_connections'] = $prefs['check_nntp_connections'];
        } else {
            $config['check_nntp_connections'] = 1;
        }
    }

    if (isset($config['find_servers']) && $config['find_servers'] === TRUE) {
        $config['check_nntp_connections'] = FALSE;
    }
    $log_level = get_config($db, 'log_level', LOG_INFO);
    $config['urdd_min_loglevel'] = $log_level;

    if (!isset($config['update_disable_keys'])|| !is_bool($config['update_disable_keys'])) { // do we disable keys when running updates
        $config['update_disable_keys'] = TRUE;
    }

    /* verify other tools we may need -- rewrite as we do use the db now to store them verify before saving prefs*/
    verify_tool($db, 'urdd', FALSE);
    verify_tool($db, 'yydecode', FALSE);
    verify_tool($db, 'subdownloader', TRUE);
    verify_tool($db, 'cksfv', TRUE);
    verify_tool($db, 'yyencode', TRUE);
    verify_tool($db, 'unpar', TRUE);
    verify_tool($db, 'unrar', TRUE);
    verify_tool($db, 'unarj', TRUE);
    verify_tool($db, 'unace', TRUE);
    verify_tool($db, 'un7zr', TRUE);
    verify_tool($db, 'unzip', TRUE);
    verify_tool($db, 'file', TRUE);
    verify_tool($db, 'gzip', TRUE);
    verify_tool($db, 'trickle', TRUE);
    verify_tool($db, 'tar', TRUE);
    verify_tool($db, 'cksfv', TRUE);

    $config['urdd_listen_host'] = trim($config['urdd_listen_host']);
    $config['urdd_listen_host6'] = trim($config['urdd_listen_host6']);
    if ($config['urdd_listen_host'] == '' && $config['urdd_listen_host6'] == '') {
        throw new exception ('No listening host specified', ERR_CONFIG_ERROR);
    }
}

function check_pid_file($pid_file)
{
    if (file_exists($pid_file)) {
        $pid = file_get_contents($pid_file);
        if (is_numeric($pid) && $pid > 0) {
            $oldErrorLevel = error_reporting(0);
            $prio = @pcntl_getpriority($pid);
            error_reporting($oldErrorLevel);
            if ($prio !== FALSE) {
                // at least a process with this pid is running
                $cmd = "ps ax | grep '^ *$pid '|grep -i 'php.*urdd\.php'";
                // we'd actually would like to check that it is actually URD
                exec($cmd, $dummy, $rv);
                if ($rv == 1) {
                    // it is probably stale or some other process
                    write_log('Probably a stale pid file found.', LOG_WARNING);
                    delete_pid_file($pid_file);
                } else {
                    throw new exception("An instance of URDD seems already been running with pid: $pid", ERR_ALREADY_RUNNING);
                }
            } else {
                write_log('Probably a stale pid file found.', LOG_WARNING);
                delete_pid_file($pid_file);
            }
        } else {
            // there is a pid file, but probably stale as there is no process runnig
            write_log('There seems to be something wrong with the PID file ' . $pid_file . '. Removing this file', LOG_WARNING);
            delete_pid_file($pid_file);
        }
    } else {
        return TRUE;
    }
}

function set_pid_file($pid_file)
{
    $pid = posix_getpid();
    if (FALSE === file_put_contents($pid_file, $pid)) {
        throw new exception('Cannot write pid file ' . $pid_file);
    }
}

function delete_pid_file($pid_file)
{
    if (file_exists($pid_file)) {
        @unlink($pid_file);
    }
}

function set_db_version(DatabaseConnection $db)
{
    $ver = get_config($db, 'URD_version');
    $ver2 = urd_version::get_version();
    if (version_compare($ver, $ver2, '<') === TRUE) {
        set_config($db, 'URD_version', $ver2);
    }
}


