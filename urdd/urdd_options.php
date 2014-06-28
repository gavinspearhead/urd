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
 * $Id: urdd_options.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class urd_cli_options
{
    private static $shortopt = '46FDp:t:vh?k::l:L:H:rRc::u:g:NnPTf::U';
    private static $longopt = array(
        'version',
        'help',
        'port=',
        'timeout=',
        'daemon',
        'host=',
        'keystore==',
        'logfile=',
        'logto=',
        'nodaemon',
        'ipv4',
        'ipv6',
        'restart',
        'norestart',
        'commands==',
        'user=',
        'group=',
        'nocheck',
        'check',
        'pidfile',
        'test',
        'find==',
        'updatedb',
    );

    public static function read_options()
    {
        global $config, $commands_list;
        try {
            $args = read_argv();
            $con  = new get_opt;
            array_shift($args);
            $options = $con->getopt($args, self::$shortopt, self::$longopt);
        } catch (exception $e) {
            write_log($e->getMessage(), LOG_ERR);
            throw new exception('Cannot parse command line options', ERR_COMMANDLINE_ERROR);
        }

        foreach ($options[0] as $opt) {
            switch ($opt[0]) {
            case '--find':
            case 'f':
                if (isset($opt['1']) && $opt['1'] == 'extended') {
                    $config['find_servers_type'] = 'extended';
                } else {
                    $config['find_servers_type'] = 'basic';
                }

                $config['find_servers'] = TRUE;
                break;
            case '--version':
            case 'v':
                print_version();
                urdd_exit(NO_ERROR);
                break;
            case '--help':
            case 'h':
            case '?':
                urd_help::print_version();
                urd_help::print_help();
                urdd_exit(NO_ERROR);
                break;
            case 'P':
            case '--pidfile':
                $config['force_pidfile'] = TRUE;
                break;
            case 'N':
            case '--nocheck':
                $config['check_nntp_connections'] = FALSE;

                break;
            case 'n':
            case '--check':
                $config['check_nntp_connections'] = TRUE;
                break;

            case 'c':
            case '--commands':
                if (!is_null($opt[1])) {
                    $msg = urd_help::do_help(array($opt[1]), $dummy);
                    echo "Urdd command {$opt[1]}:\n";
                    echo $msg;
                    echo "\n\n";
                } else {
                    $help_message = urd_help::format_help($commands_list);
                    echo "Urdd valid commands:\n";
                    echo $help_message;
                    echo "\n\n";
                }

                urdd_exit(NO_ERROR);
                break;
            case '--host':
            case 'H':
                // validate_hostname($opt[1]);
                $config['urdd_listen_host'] = $opt[1];
                break;
            case '--keystore':
            case 'k':
                $key = '';
                if (isset($opt['1']) ) {
                    $key = $opt['1'];
                }
                $config['keystore'] = $key;
                break;
            case '--logfile':
            case 'l':
                $config['urdd_logfile'] = $opt[1];
                break;
            case '--logto':
            case 'L':
                $config['urdd_log'] = $opt[1];
                break;
            case '--nodaemon':
            case 'F':
                $config['urdd_daemonise'] = FALSE;
                break;
            case '--daemon':
            case 'D':
                $config['urdd_daemonise'] = TRUE;
                break;
            case '--port':
            case 'p':
                if (is_numeric($opt[1])) {
                    $config['urdd_port'] = $opt[1];
                } else {
                    throw new exception('Incorrect parameter: -p', ERR_COMMANDLINE_ERROR);
                }
                break;
            case '--timeout':
            case 't':
                if (is_numeric($opt[1])) {
                    $config['urdd_timeout'] = $opt[1];
                } else {
                    throw new exception('Incorrect parameter: -t', ERR_COMMANDLINE_ERROR);
                }
                break;
            case 'R':
            case '--norestart':
                $config['urdd_restart'] = FALSE;
                break;
            case 'r':
            case '--restart':
                $config['urdd_restart'] = TRUE;
                break;
            case '--ipv4':
            case '4':
                $config['urdd_listen_host6'] = '';
                if ($config['urdd_listen_host'] == '') {
                    throw new exception('No port to listen on', ERR_COMMANDLINE_ERROR);
                }
                break;
            case '--ipv6':
            case '6':
                $config['urdd_listen_host'] = '';
                if ($config['urdd_listen_host6'] == '') {
                    throw new exception('No port to listen on', ERR_COMMANDLINE_ERROR);
                }
                break;
            case 'u':
            case '--user':
                $config['urdd_uid'] = $opt['1'];
                $uinfo = posix_getpwnam($config['urdd_uid']);
                if (!isset($uinfo['uid'])) {
                    throw new exception ("User {$config['urdd_uid']} not found on system.", ERR_COMMANDLINE_ERROR);
                }
                break;
            case 'g':
            case '--group':
                $config['urdd_gid'] = $opt['1'];
                $ginfo = posix_getgrnam($config['urdd_gid']);
                if (!isset($ginfo['gid'])) {
                    throw new exception ("Group {$config['urdd_gid']} not found on system.", ERR_COMMANDLINE_ERROR);
                }
                break;
            case 'T':
            case '--test':
                $config['test_servers'] = TRUE;
                break;
            case 'U':
            case '--updatedb':
                $config['updatedb'] = TRUE;
                break;
            }
        }
    }
}
