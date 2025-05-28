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
 * $Id: urd_log.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathul = realpath(dirname(__FILE__));


function trace_str($trace)
{
    if (!is_array($trace)) {
        return '';
    }
    $str = '';
    foreach ($trace as $k => $line) {
        if ($k == 0) {
            continue;
        }
        $function = get_array($line, 'function', '--');
        $linenr = get_array($line, 'line', '--');
        $file = get_array($line, 'file', '--');
        $args = get_array($line, 'args', '--');
        $args_str = '';
        foreach ($args as $a) {
            $args_str .= gettype($a) . ' ,';
        }
        $args_str=rtrim($args_str, ', ');

        $str .= "#$k $file($linenr) $function($args_str)\n";
    }

    return $str;
}

function my_assert_handler($file, $line, $code)
{
    write_log("Assert failed: $file ($line)", LOG_WARNING);
    write_log(trace_str(debug_backtrace()), LOG_INFO);
}

function set_assert($on)
{
    if ($on === TRUE) {
        // Active assert and make it quiet
        assert_options(ASSERT_ACTIVE, 1);
        assert_options(ASSERT_WARNING, 0);
        assert_options(ASSERT_BAIL, 0);
#        assert_options(ASSERT_QUIET_EVAL, 1);
        assert_options(ASSERT_CALLBACK, 'my_assert_handler');
    } else {
        // disable assert and make it quiet
        assert_options(ASSERT_ACTIVE, 0);
        assert_options(ASSERT_WARNING, 0);
        assert_options(ASSERT_BAIL, 0);
#        assert_options(ASSERT_QUIET_EVAL, 1);
        assert_options(ASSERT_CALLBACK, NULL);
    }
}

function debug_match($current_level, $presented_level)
{
    return ($current_level & $presented_level) != 0;
}

function socket_error_handler($die=FALSE)
{
    $errno = socket_last_error();
    if ($errno == 0) {// no error

        return;
    }
    $errstr = socket_strerror($errno);
    socket_clear_error();
    if ($die) {
        write_log("Error: $errstr ($errno)", LOG_ERR);
        urdd_exit(SOCKET_FAILURE);
    } elseif ($errno == 104) {
        // Default connection reset by peer error:
        write_log("Error: $errstr ($errno)", LOG_DEBUG);
    } else {
        write_log("Error: $errstr ($errno)", LOG_INFO);
    }
}

function echo_debug_trace(exception $e, $dbg_lvl)
{
    echo_debug($e->getTraceAsString(), $dbg_lvl);
}

function echo_debug_function($dbg_lvl, $function)
{
    global $config;
    if (debug_match($dbg_lvl, $config['urdd_debug_level'])) {
        $mmu = memory_get_usage(TRUE);
        $mmpu = memory_get_peak_usage(TRUE);
        $msg = "fn:{$function}() ($mmu $mmpu)";
        echo_debug($msg, $dbg_lvl);
    }
}

function echo_debug_file($file, $val, $val2=FALSE, $source=NULL)
{
    $t = microtime(TRUE);
    file_put_contents($file, "$t " . ($source !== NULL? basename($source): '') . ': ' . $val . "\n", FILE_APPEND);
    if ($val2 !== FALSE) {
        echo_debug_var_file($file, $val2);
    }
}

function echo_debug_var_file($file, $var)
{
    file_put_contents($file, var_export($var, TRUE) . "\n", FILE_APPEND);
}

function echo_debug_var($var, $dbg_lvl)
{
    echo_debug(var_export($var, TRUE), $dbg_lvl);
}

function echo_debug($msg, $dbg_lvl)
{
    global $config;

    if (debug_match($dbg_lvl, $config['urdd_debug_level'])) {
        $lines = explode("\n", $msg);
        foreach ($lines as $ln) {
            if (trim($ln) !== '') {
                write_log($ln, LOG_DEBUG);
            }
        }
    }
}

function write_log($message, $priority=LOG_INFO) // do  not add a newline; this fn prints one line if you want to write multi line msgs do more write_logs
{
    global $logfile;
    $logfile->write_log($message, $priority);
}

$logfile = new logfile($process_name);

function enable_log()
{
    global $logfile;
    $logfile->enable();
}

function disable_log()
{
    global $logfile;
    $logfile->disable();
}

class logfile
{
    private $logging_enabled;
    private $urd_log_opt;
    private $log_file;
    private $process_name;
    private $last_line;
    public function __construct($process_name)
    {
        global $config;
        $this->logging_enabled = TRUE;
        $this->urd_log_opt = NULL;
        $this->log_file = NULL;
        $this->get_logoption($config['urdd_log']);
        $this->process_name = $process_name;
        $this->last_line = '';
    }
    public function enable()
    {
        $this->logging_enabled = TRUE;
    }

    public function disable()
    {
        $this->logging_enabled = FALSE;
    }

    public function get_logoption($log)
    {
        if ($this->urd_log_opt === NULL) {
            $this->urd_log_opt = preg_split('/[\s]*\|[\s]*/', $log); // log options are separated by a | and optional spaces
        }

        return $this->urd_log_opt;
    }
    public function write_log($message, $priority=LOG_INFO) // do  not add a newline; this fn prints one line if you want to write multi line msgs do more write_logs
    {
        global $config, $log_str;
        if ($this->logging_enabled === FALSE || $priority > $config['urdd_min_loglevel']) {
            return;
        }
        if ($this->last_line == $message) {
            return;
        }
        $hostname = php_uname('n');
        $pid = posix_getpid();
        $date = date('M d H:i:s');
        $this->last_line = $message;
        $lines = explode("\n", $message);
        foreach ($lines as $msg) {
            $msg = trim($msg);
            if ($msg == '') { continue; }
            foreach ($this->urd_log_opt as $opt) {
                switch ($opt) {
                case 'file' :
                    if (!isset($config['log_file'])) {
                        continue 2;
                    }
                    if (!is_resource($this->log_file) ) {
                        $this->open_log_file($config['log_file'], $this->process_name);
                    }

                    fwrite($this->log_file, $date . ' ' . $hostname . " $this->process_name: " . $log_str[$priority] . ' ' . $msg . " (pid: $pid)\n");
                    fflush($this->log_file);
                    break;
                case 'stderr':
                    if ((!isset($config['urdd_daemonise']) || ($config['urdd_daemonise'] === FALSE)) && ((defined('ORIGINAL_PAGE') === FALSE) || ORIGINAL_PAGE === 'URDD')) {
                        fwrite(STDERR, $date . ' ' . $hostname . " $this->process_name: " . $log_str[$priority] . ' ' . $msg . " (pid: $pid)\n");
                        fflush(STDERR);
                    }
                    break;
                case 'syslog':
                    syslog($priority, $log_str[$priority] . ' ' . $msg . " (pid: $pid)");
                    break;
                default:
                    echo "Unknown log option\n";
                    urdd_exit(CONFIG_ERROR);
                    break;
                }
            }
        }
    }
    public function open_log_file($filename)
    {
        openlog($this->process_name, LOG_ODELAY, LOG_LOCAL7);
        $f = @fopen($filename, 'a');
        if ($f === FALSE) {
            $filename = '/dev/null'; // we simply log to dev/null
            $f = @fopen($filename, 'a');
            if ($f === FALSE) {//if _that_ fails we exit
                echo "Error: cannot open log_file ($filename) for appending\n";
                urdd_exit(CONFIG_ERROR);
            }
        }
        $this->log_file = $f;
        write_log("Opening log file: $filename", LOG_INFO);

        // Also try a chmod:
        $rv = @chmod($filename, 0660); // TODO fix log_files properly
        if ($rv === FALSE) {
            write_log('Warning: cannot set permissions on log_file', LOG_WARNING);
        }
    }

    public function reopen_log_file($filename)
    {
        fclose($this->log_file);
        $this->open_log_file($filename);
    }
}
