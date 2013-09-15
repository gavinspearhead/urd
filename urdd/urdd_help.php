<?php
/*
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
 * $LastChangedDate: 2013-08-28 00:47:19 +0200 (wo, 28 aug 2013) $
 * $Rev: 2905 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_help.php 2905 2013-08-27 22:47:19Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathhlp = realpath(dirname(__FILE__));

require_once "$pathhlp/../functions/urd_log.php";
require_once "$pathhlp/../functions/autoincludes.php";
require_once "$pathhlp/urdd_command.php";

class urd_help
{
    public static function print_version()
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        echo 'URD Daemon version ' . urd_version::get_version(). "\n";
        echo "\n";
    }

    private static $help_text = array (
        // array (short option, alternate short option, long option, description)
        array ('-6', '', '--ipv6', 'URDD only listens on IPv6'),
        array ('-4', '', '--ipv4', 'URDD only listens on IPv4'),
        array ('-c<command>', '', '--commands=<command>', 'Show help about an urdd command'),
        array ('-D ', '', '--daemon', 'Run urdd as a daemon process'),
//        array ('-E ', '', '--extsetinfo=<boolean>', 'Enable or disable getting extsetinfo from URDland'),
        array ('-fextendend', '', '--find=extended', 'Find server configurations for usenet servers, print them and exit. Without \'extended\', it only tries default port 119 and 563, with it also tries many other ports, but may be slower'),
        array ('-F', '', '--nodaemon', 'Run urdd as a foreground process'),
        array ('-g<group>', '', '--group=<group>', 'Set the group urdd will run as'),
        array ('-h', '-?', '--help', 'Display this help page'),
        array ('-H<name> ', '', '--host=<name>', 'host to bind to'),
        array ('-k', '', '--keystore', 'create a keystore file'),
        array ('-l<file>', '', '--logfile=<file>', 'Filename to log to'),
        array ('-L<opt>', '', '--logto=<opt>', 'Log options: one or more of file|stderr|syslog'),
        array ('-N', '', '--nocheck', 'Do not check number of NNTP connections at startup'),
        array ('-n', '', '--check', 'Check number of NNTP connections at startup'),
        array ('-p<num>', '', '--port=<num>', 'Set the port number Urdd will listen on'),
        array ('-P', '', '--pidfile', 'Remove any stale PID files before starting URD'),
        array ('-r', '', '--restart', 'Restart earlier urdd tasks'),
        array ('-R', '', '--norestart', 'Do not restart earlier urdd tasks'),
        array ('-t<num>', '', '--timeout=<num>', 'Set the connection time-out'),
        array ('-T', '', '--test', 'Test which configured server can be used for indexing'),
        array ('-u<user>', '', '--user=<user>', 'Set the user urdd will run as'),
        array ('-v ', '', '--version ', 'Show urdd version')
    );

    private static $help_intro =
        "Usage: urdd.sh [OPTION]\n";

    private static $help_outro =
        "Report URD bugs to urd-dev@urdland.com\nURD homepage: http://www.urdland.com\n" ;

    public static function print_help()
    {
        echo self::$help_intro . "\n";
        foreach (self::$help_text as $line) {
            $t = '';
            $t .= sprintf('  %s', $line[0]);
            if ($line[1] != '') {// alternate option
                $t .=  sprintf('  %s,', $line[1]);
            }
            if ($line[2] != '') { // long option
                $t .=  sprintf('  %s,', $line[2]);
            }
            $t = ltrim($t, ' ,');
            $t = rtrim($t, ' ,');
            echo $t . "\n";
            echo "    " . $line[3] . "\n";
        }
        echo "\n";
        echo self::$help_outro . "\n";
    }

    public static function do_help(array $args, &$response)
    {
        global $commands_list;
        if (isset($args[0]) && $args[0] != '') {
            if (strtolower($args[0]) == 'all') {
                $response = urdd_protocol::get_response(252);
                $help_message = get_help_all();
            } else {
                $c = match_command($args[0]);
                if ($c === FALSE) {
                    $response = sprintf(urdd_protocol::get_response(500), "'{$args[0]}'");
                    $help_message = '';
                } else {
                    $msg = $c->get_helpmessage();
                    $syntax = $c->get_syntax();
                    $help_message = $syntax . "\n\n" . $msg . "\n";
                    $response = urdd_protocol::get_response(257);
                }
            }
        } else {
            $help_message = self::format_help($commands_list);
            $response = urdd_protocol::get_response(252);
        }

        return $help_message;
    }

    public static function format_help(commands_list $commands_list)
    {
        $hm = '';
        $cnt = 0;
        $commands = $commands_list->get_commands();
        foreach ($commands as $k => $cmd) {
            if (is_numeric($k)) {
                continue;
            }
            $hm .= sprintf('%-15.15s', $cmd->get_command());
            if ($cnt == 6) {
                $hm .= "\n";
                $cnt = 0;
            } else {
                $cnt++;
            }
        }
        $hm .= "\n";

        return $hm;
    }

}
