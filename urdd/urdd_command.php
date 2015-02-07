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
 * $Rev $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_command.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function do_command(DatabaseConnection $db, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
{
    global $commands_list;

    return $commands_list->do_command($db, $line, $response, $conn_list, $sock, $servers, $userid, $priority, $internal);
}

class command
{
    private $do_function; // function to call
    private $enabled; // command is enabled
    private $type; // type of the command (posting, groups, nzb, etc)
    private $command;  // command string
    private $need_auth; // must be authenticated before executing the command
    private $code; // the numeral code of the command
    private $help_msg; // text string description of the command
    private $syntax;   // format of the command for help
    private $need_admin; // must be admin user to exec the command
    private $need_nntp; // an nntp connection is needed
    private $primary_nntp; // connect to the primary nntp server (that is it uses the indexing server)
    private $arg_str;   // argument string format
    private $rights; // needed rights to execute this
    private $db_intensive; // relies heavily on db interaction==> additional limitation on threads running of this type
    private $need_posting; // needs the posting capability of a nntp server
    public function __construct ($cmd, $do_function, $auth, $admin, $code, $need_nntp, $syntax='', $help_message='', $arg_str='', $primary_nntp=FALSE, $db_intensive= FALSE, $need_posting=FALSE, $type=urd_modules::URD_CLASS_GENERIC, $rights = '')
    {
        assert(is_bool($need_nntp) && is_bool($primary_nntp) && is_bool($auth) && is_bool($admin) && is_numeric($code));
        $this->command = $cmd;
        $this->need_auth = $auth;
	$this->code = (int) $code;
	$this->help_msg = $help_message;
	$this->syntax = $syntax;
	$this->need_admin = $admin;
	$this->need_nntp = $need_nntp;
	$this->arg_str = $arg_str;
	$this->primary_nntp = $primary_nntp;
        $this->db_intensive = $db_intensive;
        $this->need_posting = $need_posting;
        $this->enabled = TRUE;
        $this->rights = $rights;
        $this->type = $type;
        $this->do_function = $do_function;
    }
    public function needs_auth() { return $this->need_auth;}
    public function match_command($str) { return strtoupper($str) === $this->command; }
    public function get_code() { return $this->code;}
    public function get_type() { return $this->type;}
    public function get_syntax() { return $this->syntax;}
    public function get_helpmessage() { return $this->help_msg;}
    public function get_command() { return $this->command;}
    public function get_rights() { return $this->rights;}
    public function needs_admin() { return $this->need_admin;}
    public function needs_nntp() { return $this->need_nntp;}
    public function needs_posting() { return $this->need_posting;}
    public function get_arg_str() { return $this->arg_str;}
    public function primary_nntp() { return $this->primary_nntp;}
    public function db_intensive() { return $this->db_intensive;}
    public function call_function(DatabaseConnection $db, $arg_list, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid, $priority, $internal)
    {
        assert(is_numeric($priority));
        if ($this->enabled) {
            $fn = $this->do_function;

            return commands_list::$fn($db, $arg_list, $line, $response, $conn_list, $sock, $servers, $userid, $priority, $internal);
        } else {
            $response = urdd_protocol::get_response(533);

            return NO_ERROR;
        }
    }
    public function enable()
    {
        $this->enabled = TRUE;
    }
    public function is_enabled()
    {
        return ($this->enabled === TRUE);
    }
    public function disable()
    {
        $this->enabled = FALSE;
    }
};

class commands_list
{
    private $commands = array();
    private $settings = array();
    public function __construct(array $settings)
    {
        $this->settings = array(
            urd_modules::URD_CLASS_GENERIC => TRUE,
            urd_modules::URD_CLASS_GROUPS => FALSE,
            urd_modules::URD_CLASS_USENZB => FALSE,
            urd_modules::URD_CLASS_MAKENZB => FALSE,
            urd_modules::URD_CLASS_POST => FALSE,
            urd_modules::URD_CLASS_RSS => FALSE,
            urd_modules::URD_CLASS_SYNC => FALSE,
            urd_modules::URD_CLASS_DOWNLOAD => FALSE
        );
        $this->update_settings($settings);
    }
    public function update_settings (array $settings)
    {
        foreach ($settings as $key => $setting) {
            if (isset($this->settings[$key])) {
                $this->settings[$key] = $setting;
            }
            $this->enable($key, $setting);
        }

    }

    public static function get_arg_list($line)
    {
        $elems = preg_split('/[\s]+/', $line, 2);
        $cmd_str = strtoupper($elems[0]);
        $args = (isset($elems[1])) ? $elems[1] : NULL;

        return array($cmd_str, $args);
    }

    public function __destruct()
    {
    }

    public function enable ($type, $enable)
    {
        foreach ($this->commands as &$command) {
            if ($command->get_type() == $type) {
                if ($enable) {
                    $command->enable();
                } else {
                    $command->disable();
                }
            }
        }
    }

    public function get_commands()
    {
        $commands = array();
        foreach ($this->commands as $k=>$command) {
            if ($command->is_enabled()) {
                $commands[$k] = $command;
            }
        }

        return $commands;
    }

    public function is_commented($str)
    {
        if ($str[0] == '#' || $str[0] == ';' || substr($str, 0, 2) == '//') {
            return TRUE;
        }

        return FALSE;
    }

    public function do_command(DatabaseConnection $db, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
        //if internal == TRUE, download_action can be used same as download
    {
        //echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        assert(is_bool($internal));
        $isadmin = FALSE;
        $response = '';
        list($cmd_str, $args) = self::get_arg_list($line);
        if (self::is_commented($cmd_str)) {
            $response = urdd_protocol::get_response(231);

            return URDD_NOERROR;
        }

        $cmd = match_command($cmd_str);
        if ($cmd === FALSE) {
            $response = sprintf(urdd_protocol::get_response(500), "'$cmd_str'");

            return URDD_ERROR;
        }
        if ($userid === NULL && $sock !== NULL) {
            if ($cmd->needs_auth()) {
                if ($conn_list->check_authorised($sock) !== TRUE) {
                    $response = urdd_protocol::get_response(530);

                    return URDD_NOERROR;
                }
            }
            $username = $conn_list->get_username($sock);
            $userid = get_userid($db, $username);
        }
        if ($cmd->needs_admin() || $cmd->get_rights() != '') {
            $perm = urd_user_rights::is_admin($db, $userid);
            if (!$perm) {
                $perm = urd_user_rights::has_rights($db, $userid, $cmd->get_rights());
            } else {
                $isadmin = TRUE;
            }
            if ($perm !== TRUE) {
                $response = urdd_protocol::get_response(532);

                return URDD_NOERROR;
            }
        }
        if ($priority === NULL) {
            $priority = DEFAULT_PRIORITY;
        }

        if ($servers->get_nntp_enabled() !== TRUE && $cmd->needs_nntp() ) {
            $response = urdd_protocol::get_response(410);

            return URDD_NOERROR;
        }

        return $this->commands[$cmd_str]->call_function($db, $args, $line, $response, $conn_list, $sock, $servers, $userid, $priority, $internal);
    }

    public function register_command(command $command)
    {
        // we set both the command string and the code
        $this->commands[$command->get_code()]    = $command;
        $this->commands[$command->get_command()] = $command;
    }

    public function get_command_code($str)
    {
        $str = strtoupper($str);
        if (isset($this->commands[$str])) {
            return $this->commands[$str]->get_code();
        }

        return FALSE;
    }

    public function match_command($str)
    {
        $str = strtoupper(trim($str));
        if (isset($this->commands[$str])) {
            return $this->commands[$str];
        }

        return FALSE;
    }

    public function compare_command($str, $cmd)
    {
        $str = strtoupper(trim($str));
        if (isset($this->commands[$cmd])) {
            return $this->commands[$cmd]->get_command() == $str;
        }

        return FALSE;
    }

    public function get_command($cmd)
    {// get the commandstring for a command code
        if (isset($this->commands[$cmd])) {
            return $this->commands[$cmd]->get_command();
        }

        return FALSE;
    }

    public function get_help_all()
    {
        $msg = '';
        foreach ($this->commands as $k=>$c) {
            if (is_numeric($k) && $c->is_enabled()) {
                $msg .= $c->get_syntax() . "\n";
                $msg .= $c->get_helpmessage() . "\n\n";
            }
        }

        return $msg;
    }

    public function get_command_posting($cmd)
    {//return if a command needs an nntp connection
        if (isset($this->commands[$cmd])) {
            return $this->commands[$cmd]->needs_posting();
        }

        return FALSE;
    }

    public function get_command_nntp($cmd)
    {//return if a command needs an nntp connection
        if (isset($this->commands[$cmd])) {
            return $this->commands[$cmd]->needs_nntp();
        }

        return FALSE;
    }

    public function get_command_primary_nntp($cmd)
    {//return if a command needs the primary (indexing) nntp server for the connection
        if (isset($this->commands[$cmd])) {
            return $this->commands[$cmd]->primary_nntp();
        }

        return FALSE;
    }

    public function get_command_db_intensive($cmd)
    {//return if a command needs an nntp connection
        if (isset($this->commands[$cmd])) {
            return $this->commands[$cmd]->db_intensive();
        }

        return FALSE;
    }

    public static function command_repeat_last_command(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $conn =& $conn_list->get_conn($sock);
        $line = $conn->get_last_cmd();
        $rv = do_command($db, $line, $response, $conn_list, $sock, $servers, $userid, $priority, FALSE);

        return ($rv != URDD_NOERROR) ? $rv : URDD_ERROR;
    }

    public static function command_user(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $arg = isset($arg_list[0]) ? $arg_list[0] : '';
        $username = trim($arg);
        if (valid_username($username)) {
            $conn_list->add_username($sock, $username);
            $response = urdd_protocol::get_response(331);
        } else {
            $response = urdd_protocol::get_response(501);
        }

        return URDD_NOERROR;
    }

    public static function command_pass(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $arg = isset($arg_list[0]) ? $arg_list[0] : '';
        $res = $conn_list->verify_password_db($db, $sock, $arg);
        if ($res) {
            $response = urdd_protocol::get_response(240);
        } else {
            $username = $conn_list->get_username($sock);
            write_log("Invalid password provided for $username", LOG_NOTICE);
            $response = urdd_protocol::get_response(530);
        }

        return URDD_NOERROR;
    }

    public static function command_download_action(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        if ($internal === FALSE) {
            $response = urdd_protocol::get_response(231);

            return URDD_NOERROR;
        }

        return self::command_download($db, $args, $line, $response, $conn_list, $sock, $servers, $userid, $priority, $internal);
    }

    public static function command_download(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $arg = isset($arg_list[0]) ? $arg_list[0] : '';
        if (is_numeric($arg)) {
            $response = restart_download($db, $servers, $userid, $arg, $priority);
        } elseif (strtolower($arg) == 'preview') {
            $response = create_download($db, $servers, $userid, TRUE);
        } else {
            $response = create_download($db, $servers, $userid, FALSE);
        }

        return URDD_NOERROR;
    }

    public static function command_make_nzb(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = create_make_nzb($db, $servers, $userid, $priority-1);

        return URDD_NOERROR;
    }

    public static function command_shutdown(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = urdd_protocol::get_response(222);

        return URDD_SHUTDOWN;
    }

    public static function command_restart(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = urdd_protocol::get_response(223);

        return URDD_RESTART;
    }

    public static function command_unschedule(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        try {
            $arg_list = split_args($args);
            $response = do_unschedule($db, $arg_list, $servers, $userid);
        } catch (exception $e) {
            $response = urdd_protocol::get_response(501);
        }

        return URDD_NOERROR;
    }

    public static function command_schedule(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        try {
            $arg_list = split_args($args);
            $response = do_schedule($db, $arg_list, $servers, $userid);
        } catch (exception $e) {
            $response = urdd_protocol::get_response(501);
        }

        return URDD_NOERROR;
    }

    public static function command_show(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = urdd_protocol::get_response(253);
        $arg_list = split_args($args);
        if (count($arg_list) == 0) {
            $response = urdd_protocol::get_response(501);
        } else {
            $r = do_show($arg_list, $conn_list, $servers, $db);
            if ($r === FALSE) {
                $response = urdd_protocol::get_response(501);
            } else {
                $response .= $r;
                $response .= ".\n";
            }
        }

        return URDD_NOERROR;
    }

    public static function command_cleandb(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $arg = isset($arg_list[0]) ? $arg_list[0] : '';
        $response = queue_cleandb($db, $servers, $arg, $userid, $priority+1);

        return URDD_NOERROR;
    }

    public static function command_time(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = urdd_protocol::get_response(260);
        $response .= show_time();
        $response .= ".\n";

        return URDD_NOERROR;
    }

    public static function command_update(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = queue_update($db, urdd_protocol::COMMAND_UPDATE, $arg_list, $userid, $servers, $priority);

        return URDD_NOERROR;
    }

    public static function command_groups(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_groups($db, $servers, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_group(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = do_group($db, $servers, $arg_list);

        return URDD_NOERROR;
    }

    public static function command_diskfree(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = urdd_protocol::get_response(256);
        $arg_list = split_args($args);
        $arg = isset($arg_list[0]) ? $arg_list[0] : '';
        try {
            list($code, $msg) = do_diskfree($db, $arg);
            $response = urdd_protocol::get_response($code) . $msg . ".\n";
        } catch (exception $e) {
            $response = sprintf(urdd_protocol::get_response(503), $e->getMessage());
        }

        return URDD_NOERROR;
    }

    public static function command_continue(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = do_pause($db, $servers, $arg_list, FALSE, $userid);

        return URDD_NOERROR;
    }

    public static function command_pause(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = do_pause($db, $servers, $arg_list, TRUE, $userid);

        return URDD_NOERROR;
    }

    public static function command_cancel(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = do_cancel($db, $servers, $arg_list, $userid);

        return URDD_NOERROR;
    }

    public static function command_stop(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = do_stop($db, $servers, $arg_list, $userid);

        return URDD_NOERROR;
    }

    public static function command_preempt(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        try {
            $response = do_preempt($db, $servers, $arg_list, $userid);
        } catch (exception $e) {
            $response = urdd_protocol::get_response(532);
        }

        return URDD_NOERROR;
    }

    public static function command_purge(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = queue_purge_expire($db, urdd_protocol::COMMAND_PURGE, $arg_list, $userid, $servers, $priority);

        return URDD_NOERROR;
    }

    public static function command_purge_spots(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_purge_spots($db, $servers, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_purge_rss(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = queue_purge_expire($db, urdd_protocol::COMMAND_PURGE_RSS, $arg_list, $userid, $servers, $priority);

        return URDD_NOERROR;
    }

    public static function command_expire(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = queue_purge_expire($db, urdd_protocol::COMMAND_EXPIRE, $arg_list, $userid, $servers, $priority);

        return URDD_NOERROR;
    }

    public static function command_expire_spots(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_expire_spots($db, $servers, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_expire_rss(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = queue_purge_expire($db, urdd_protocol::COMMAND_EXPIRE_RSS, $arg_list, $userid, $servers, $priority);

        return URDD_NOERROR;
    }

    public static function command_quit(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $conn =& $conn_list->get_conn($sock);
        write_log("Ending connection from host {$conn->get_peer_hostname()}:{$conn->get_peer_port()}");
        $response = urdd_protocol::get_response(221);

        return URDD_CLOSE_CONN;
    }

    public static function command_echo(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = urdd_protocol::get_response(254);
        $response .= "$args\n";
        $response .= ".\n";

        return URDD_NOERROR;
    }

    public static function command_noop(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = urdd_protocol::get_response(231);

        return URDD_NOERROR;
    }

    public static function command_help(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $help_message = urd_help::do_help($arg_list, $response);
        $response .= $help_message;
        $response .= ".\n";

        return URDD_NOERROR;
    }
   
    public static function command_subscribe(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = do_subscribe($db, $arg_list, $servers, $userid);

        return URDD_NOERROR;
    }

    public static function command_subscribe_rss(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = do_subscribe_rss($db, $arg_list, $servers, $userid);

        return URDD_NOERROR;
    }

    public static function command_getspot_comments(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_getspot_comments($db, $servers, $userid, $priority+1);

        return URDD_NOERROR;
    }

    public static function command_getspot_images(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_getspot_images($db, $servers, $userid, $priority+1);

        return URDD_NOERROR;
    }

    public static function command_getspot_reports(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_getspot_reports($db, $servers, $userid, $priority+1);

        return URDD_NOERROR;
    }

    public static function command_getspots(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_getspots($db, $servers, $userid, $priority+1);

        return URDD_NOERROR;
    }

    public static function command_getblacklist(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_getblacklist($db, $servers, $userid, $priority+1);

        return URDD_NOERROR;
    }

    public static function command_getwhitelist(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_getwhitelist($db, $servers, $userid, $priority+1);

        return URDD_NOERROR;
    }

    public static function command_getnfo(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_getnfo($db, $servers, $userid, $priority+1);

        return URDD_NOERROR;
    }

    public static function command_optimise(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_optimise($db, $servers, $userid, $priority+1);

        return URDD_NOERROR;
    }

    public static function command_unpar_unrar(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $arg = isset($arg_list[0]) ? $arg_list[0] : '';
        $response = queue_unpar_unrar($db, '', $arg, $servers, $userid, FALSE, $priority);

        return URDD_NOERROR;
    }

    public static function command_cleandir(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $arg = isset($arg_list[0]) ? $arg_list[0] : '';
        $response = queue_cleandir($db, $servers, $arg, $userid, $priority+1);

        return URDD_NOERROR;
    }

    public static function command_check_version(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_check_version($db, $servers, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_whoami(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $username = get_username($db, $userid);
        $response = urdd_protocol::get_response(261) . $username . "\n.\n";

        return URDD_NOERROR;
    }

    public static function command_priority(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = do_priority($db, $arg_list, $servers, $userid);

        return URDD_NOERROR;
    }

    public static function command_set(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = do_set($db, $servers, $arg_list, $userid);

        return URDD_NOERROR;
    }

    public static function command_getsetinfo(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_getsetinfo($db, $servers, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_sendsetinfo(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_sendsetinfo($db, $servers, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_move(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = do_move($db, $servers, $arg_list, $userid);

        return URDD_NOERROR;
    }

    public static function command_gensets(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = queue_gensets($db, $servers, $arg_list, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_addspotdata(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = queue_addspotdata($db, $servers, $arg_list, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_adddata(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = queue_adddata($db, $servers, $arg_list, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_post_message(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_post_message($db, $servers, $args, $userid, $priority);

        return URDD_NOERROR;
    }
    public static function command_post_spot(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $response = queue_post_spot($db, $servers, $args, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_parse_nzb(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = queue_parse_nzb($db, $servers, $arg_list, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_update_rss(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = queue_update_rss($db, urdd_protocol::COMMAND_UPDATE_RSS, $arg_list, $userid, $servers, $priority);

        return URDD_NOERROR;
    }

    public static function command_findservers(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $response = queue_find_servers($db, $servers, $arg_list, $userid, $priority);

        return URDD_NOERROR;
    }

    public static function command_post_action(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        if ($internal === FALSE) {
            $response = urdd_protocol::get_response(231);

            return URDD_NOERROR;
        }

        return self::command_post( $db, $args, $line,$response, $conn_list, $sock, $servers, $userid, $priority, $internal);
    }

    public static function command_post(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        $arg = isset($arg_list[0]) ? $arg_list[0] : '';
        list($cmd_str, $args) = self::get_arg_list($line);
        $cmd = match_command($cmd_str);
        if ($internal === FALSE) {
            $response = queue_prepare_post($db, $servers, $arg, $userid, $priority);
        } else {
            if (!is_numeric($arg)) {
                return INTERNAL_FAILURE;
            }
            list($cmd_str, $args) = self::get_arg_list($line);
            $cmd = match_command($cmd_str);
            $response = restart_post($db, $cmd->get_code(), $servers, $userid, $arg, $priority);
        }

        return URDD_NOERROR;
    }

    public static function command_merge_sets(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        if (!isset($arg_list[0]) || !isset($arg_list[1])) {
            $response = urdd_protocol::get_response(501);
        } else {
            $response = queue_merge_sets($db, $servers, $args, $userid, $priority);
        }

        return URDD_NOERROR;
    }

    public static function command_delete_set_rss(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        if (!isset($arg_list[0])) {
            $response = urdd_protocol::get_response(501);
        } else {
            try {
                queue_delete_set_rss($db, $servers, $args, $userid, $priority);
                $response = urdd_protocol::get_response(200);
            } catch (exception $e) {
                $response = urdd_protocol::get_response(513);
            }
        }

        return URDD_ERROR;
    }

    public static function command_delete_set(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        if (!isset($arg_list[0])) {
            $response = urdd_protocol::get_response(501);
        } else {
            try {
                queue_delete_set($db, $servers, $args, $userid, $priority);
                $response = urdd_protocol::get_response(200);
            } catch (exception $e) {
                $response = urdd_protocol::get_response(513);
            }
        }

        return URDD_ERROR;
    }

    public static function command_delete_spot(DatabaseConnection $db, $args, $line, &$response, conn_list &$conn_list, $sock, server_data &$servers, $userid=NULL, $priority=NULL, $internal=FALSE)
    {
        $arg_list = split_args($args);
        if (!isset($arg_list[0])) {
            $response = urdd_protocol::get_response(501);
        } else {
            try {
                queue_delete_spot($db, $servers, $args, $userid, $priority);
                $response = urdd_protocol::get_response(200);
            } catch (exception $e) {
                $response = urdd_protocol::get_response(513);
            }
        }

        return URDD_ERROR;
    }

}

$modules = urd_modules::get_urd_module_config(0);

$commands_list = new commands_list($modules);

$commands_list->register_command( new command('!!', 'command_repeat_last_command', TRUE, FALSE, urdd_protocol::COMMAND_REPEAT_LAST_COMMAND, FALSE, '!!', 'Repeat last command', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('ADDDATA', 'command_adddata', TRUE, FALSE, urdd_protocol::COMMAND_ADDDATA, FALSE, 'ADDDATA DLID SET SETID [PREVIEW]', 'Adds a set with SETID to a download with DLID', '%n %n SET %n', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_DOWNLOAD, ''));
$commands_list->register_command( new command('ADDSPOTDATA', 'command_addspotdata', TRUE, FALSE, urdd_protocol::COMMAND_ADDSPOTDATA, TRUE, 'ADDSPOTDATA DLID SPOTTID ', 'Adds segments from a spot with id SPOTID to the download with DLID', '%n %n %n', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_SPOTS, ''));
$commands_list->register_command( new command('CANCEL', 'command_cancel', TRUE, FALSE, urdd_protocol::COMMAND_CANCEL, FALSE, 'CANCEL ID|ALL', 'Cancel action with ID, or cancel all actions', '%n', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('CHECK_VERSION', 'command_check_version', TRUE, FALSE, urdd_protocol::COMMAND_CHECK_VERSION, FALSE, 'CHECK_VERSION', 'Checks whether a new version is available', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('CLEANDB','command_cleandb', TRUE, FALSE, urdd_protocol::COMMAND_CLEANDB, FALSE, 'CLEANDB [USERS|ALL|AGE|NOW]', 'Remove download items that are finished, or failed; ALL removes all download items (not regarding status), Age gives a number of days since when items can be removed, now removes all items with regard to status', '[%s]', FALSE, TRUE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('CLEANDIR', 'command_cleandir',TRUE, TRUE, urdd_protocol::COMMAND_CLEANDIR, FALSE, 'CLEANDIR ALL|PREVIEW|TMP|NZB', 'Remove all files older than one day form the downloaddir/tmp or downloaddir/preview or downloaddir/nzb', '%s', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('CONTINUE', 'command_continue',TRUE, FALSE, urdd_protocol::COMMAND_CONTINUE, FALSE, 'CONTINUE ID|ALL', 'Restarted a paused action ID, or all actions', '(%n|%s)', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('DISKFREE', 'command_diskfree',TRUE, FALSE, urdd_protocol::COMMAND_DISKFREE, FALSE, 'DISKFREE P|p|h|k|M|G|T|b', 'Shows the available disk space', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('DELETE_SET','command_delete_set', TRUE, TRUE, urdd_protocol::COMMAND_DELETE_SET, FALSE, 'DELETE_SET ID', 'Remove a group set', '%n', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GROUPS, ''));
$commands_list->register_command( new command('DELETE_SET_RSS', 'command_delete_set_rss',TRUE, TRUE, urdd_protocol::COMMAND_DELETE_SET_RSS, FALSE, 'DELETE_SET_RSS ID', 'Remove an RSS set', '%n', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_RSS, ''));
$commands_list->register_command( new command('DELETE_SPOT','command_delete_spot', TRUE, TRUE, urdd_protocol::COMMAND_DELETE_SPOT, FALSE, 'DELETE_SPOT ID', 'Remove a spot', '%n', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_SPOTS, ''));
$commands_list->register_command( new command('DOWNLOAD', 'command_download',TRUE, FALSE, urdd_protocol::COMMAND_DOWNLOAD, TRUE, 'DOWNLOAD [ID|PREVIEW]', 'Starts a new download or restarts a download with ID', '[%n]', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_DOWNLOAD, ''));
$commands_list->register_command( new command('DOWNLOAD_ACTION', 'command_download_action',TRUE, FALSE, urdd_protocol::COMMAND_DOWNLOAD_ACTION, TRUE, 'DOWNLOAD_ACTION', 'No operation (used interally)', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_DOWNLOAD, ''));
$commands_list->register_command( new command('ECHO', 'command_echo',TRUE, FALSE, urdd_protocol::COMMAND_ECHO, FALSE, 'ECHO text', 'Echoes the given text', '%s', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('EXIT', 'command_quit',FALSE, FALSE, urdd_protocol::COMMAND_EXIT, FALSE, 'EXIT', 'End the connection', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('EXPIRE', 'command_expire',TRUE, TRUE, urdd_protocol::COMMAND_EXPIRE, FALSE, 'EXPIRE ID|ALL', 'Expire old messages in group ID or in all groups', '(%n|%s)', FALSE, TRUE, FALSE, urd_modules::URD_CLASS_GROUPS, 'M'));
$commands_list->register_command( new command('EXPIRE_RSS', 'command_expire_rss',TRUE, TRUE, urdd_protocol::COMMAND_EXPIRE_RSS, FALSE, 'EXPIRE_RSS ID|ALL', 'Expire old feed information for feed n or in all feeds', '(%n|%s)', FALSE, TRUE, FALSE, urd_modules::URD_CLASS_RSS, 'M'));
$commands_list->register_command( new command('EXPIRE_SPOTS', 'command_expire_spots', TRUE, TRUE, urdd_protocol::COMMAND_EXPIRE_SPOTS, FALSE, 'EXPIRE_SPOTS', 'Expire old spot information ', '', FALSE, TRUE, FALSE, urd_modules::URD_CLASS_SPOTS, 'M'));

// FINDSERVERS has need_nntp set to false, because it is special and it doesn't use the nntp slots
$commands_list->register_command( new command('FINDSERVERS', 'command_findservers', TRUE, TRUE, urdd_protocol::COMMAND_FINDSERVERS, FALSE, 'FINDSERVERS [EXTENDED]', 'Autoconfigure usenet servers', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('GENSETS', 'command_gensets',TRUE, TRUE, urdd_protocol::COMMAND_GENSETS, FALSE, 'GENSETS ID', 'Generate sets for group ID', '%n', FALSE, TRUE, FALSE, urd_modules::URD_CLASS_GROUPS, 'M'));
$commands_list->register_command( new command('GET_NFO', 'command_getnfo',TRUE, TRUE, urdd_protocol::COMMAND_GETNFO, TRUE, 'GET_NFO', 'Get the nfo files of all updates', '', TRUE, FALSE, FALSE, urd_modules::URD_CLASS_GROUPS, ''));
$commands_list->register_command( new command('GET_BLACKLIST', 'command_getblacklist',TRUE, TRUE, urdd_protocol::COMMAND_GETBLACKLIST, FALSE, 'GET_BLACKLIST', 'Get the blacklist for spots', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_SPOTS, ''));
$commands_list->register_command( new command('GET_WHITELIST', 'command_getwhitelist',TRUE, TRUE, urdd_protocol::COMMAND_GETWHITELIST, FALSE, 'GET_WHITELIST', 'Get the whitelist for spots', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_SPOTS, ''));
$commands_list->register_command( new command('GETSETINFO', 'command_getsetinfo',TRUE, TRUE, urdd_protocol::COMMAND_GETSETINFO, FALSE, 'GETSETINFO', 'Get the extended set information from the newsserver', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_SYNC, ''));
$commands_list->register_command( new command('GET_SPOTS', 'command_getspots',TRUE, TRUE, urdd_protocol::COMMAND_GETSPOTS, TRUE, 'GET_SPOTS', 'Get new spots', '', TRUE, FALSE, FALSE, urd_modules::URD_CLASS_SPOTS, 'M'));
$commands_list->register_command( new command('GET_SPOT_IMAGES', 'command_getspot_images',TRUE, TRUE, urdd_protocol::COMMAND_GETSPOT_IMAGES, TRUE, 'GET_SPOT_IMAGES', 'Get new spot images', '', TRUE, FALSE, FALSE, urd_modules::URD_CLASS_SPOTS, 'M'));
$commands_list->register_command( new command('GET_SPOT_REPORTS', 'command_getspot_reports',TRUE, TRUE, urdd_protocol::COMMAND_GETSPOT_REPORTS, TRUE, 'GET_SPOT_REPORTS', 'Get new spot reports', '', TRUE, FALSE, FALSE, urd_modules::URD_CLASS_SPOTS, 'M'));
$commands_list->register_command( new command('GET_SPOT_COMMENTS', 'command_getspot_comments',TRUE, TRUE, urdd_protocol::COMMAND_GETSPOT_COMMENTS, TRUE, 'GET_SPOT_COMMENTS', 'Get new spot comments', '', TRUE, FALSE, FALSE, urd_modules::URD_CLASS_SPOTS, 'M'));
$commands_list->register_command( new command('GROUP', 'command_group',TRUE, FALSE, urdd_protocol::COMMAND_GROUP, FALSE, 'GROUP ID', 'Show information about group with ID', '%n', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GROUPS, ''));
$commands_list->register_command( new command('GROUPS', 'command_groups',TRUE, TRUE, urdd_protocol::COMMAND_GROUPS, TRUE, 'GROUPS', 'Update the list of available newsgroups', '', TRUE, FALSE, FALSE, urd_modules::URD_CLASS_GROUPS, 'M'));
$commands_list->register_command( new command('HELP', 'command_help',FALSE, FALSE, urdd_protocol::COMMAND_HELP, FALSE, 'HELP [Command]', 'Show all valid commands or syntax of one command', '[%s]', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('MAKE_NZB', 'command_make_nzb',TRUE, FALSE, urdd_protocol::COMMAND_MAKE_NZB, FALSE, 'MAKE_NZB', 'Create an NZB file', '', FALSE, TRUE, FALSE, urd_modules::URD_CLASS_MAKENZB, ''));
$commands_list->register_command( new command('MERGE_SETS', 'command_merge_sets',TRUE, FALSE, urdd_protocol::COMMAND_MERGE_SETS, FALSE, 'MERGE_SETS setid1 setid2', 'Merges sets setid2 into setid1', '%s %s', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('MOVE', 'command_move',TRUE, FALSE, urdd_protocol::COMMAND_MOVE, FALSE, 'MOVE UP|DOWN <ID>', 'Move an item up in the queue', ' (up|down) [%n]', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('NOOP', 'command_noop',TRUE, FALSE, urdd_protocol::COMMAND_NOOP, FALSE, 'NOOP', 'No operation', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('OPTIMISE', 'command_optimise',TRUE, TRUE, urdd_protocol::COMMAND_OPTIMISE, FALSE, 'OPTIMISE', 'Optimise the databases', '', FALSE, TRUE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('PARSE_NZB', 'command_parse_nzb',TRUE, FALSE, urdd_protocol::COMMAND_PARSE_NZB, FALSE, 'PARSE_NZB [dlid] url [starttime]', 'read and NZB file from the given URL', '[%n] %s [%n]', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_USENZB, ''));
$commands_list->register_command( new command('PASS', 'command_pass',FALSE, FALSE, urdd_protocol::COMMAND_PASS, FALSE, 'PASS [hash:]password', 'Enter a password, optionally as sha256 hash', '%s', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('PAUSE', 'command_pause',TRUE, FALSE, urdd_protocol::COMMAND_PAUSE, FALSE, 'PAUSE ID|ALL', 'Pause action ID or all actions', '%s', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('POST', 'command_post',TRUE, FALSE, urdd_protocol::COMMAND_POST, FALSE, 'POST ID', 'Post the files identified by ID in the postinfo table', '%s %s', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_POST, 'P'));
$commands_list->register_command( new command('POST_ACTION', 'command_post_action',TRUE, FALSE, urdd_protocol::COMMAND_POST_ACTION, TRUE, 'POST_ACTION', 'No operation (internal use)', '', FALSE, FALSE, TRUE, urd_modules::URD_CLASS_POST, 'P'));
$commands_list->register_command( new command('POST_MESSAGE', 'command_post_message',TRUE, FALSE, urdd_protocol::COMMAND_POST_MESSAGE, TRUE, 'POST_MESSAGE MESSAGE_ID', 'Post a text message to the group', '%n', FALSE, FALSE, TRUE, urd_modules::URD_CLASS_POST, ''));
$commands_list->register_command( new command('POST_SPOT', 'command_post_spot',TRUE, FALSE, urdd_protocol::COMMAND_POST_SPOT, TRUE, 'POST ID', 'Post the spot info identified by ID in the spot_postinfo table', '%s %s', FALSE, FALSE, TRUE, urd_modules::URD_CLASS_POST, 'P'));
$commands_list->register_command( new command('PREEMPT', 'command_preempt',TRUE, FALSE, urdd_protocol::COMMAND_PREEMPT, FALSE, 'PREEMPT ID1 ID2', 'Start the process with ID1 and push process with ID2 back on the queue', '%n %n', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('PRIORITY', 'command_priority',TRUE, FALSE, urdd_protocol::COMMAND_PRIORITY, FALSE, 'PRIORITY ID PRIORITY', 'Set the priority of a process with ID1 to priority PRIORITY', '%n %n', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('PURGE', 'command_purge',TRUE, TRUE, urdd_protocol::COMMAND_PURGE, FALSE, 'PURGE ID|ALL', 'Remove all articles from a group ID or from all groups', '%n|%s', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GROUPS, ''));
$commands_list->register_command( new command('PURGE_RSS', 'command_purge_rss',TRUE, TRUE, urdd_protocol::COMMAND_PURGE_RSS, FALSE, 'PURGE_RSS ID|ALL', 'Remove all links from a feed ID or from all feeds', '%n|%s', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_RSS, ''));
$commands_list->register_command( new command('PURGE_SPOTS', 'command_purge_spots',TRUE, TRUE, urdd_protocol::COMMAND_PURGE_SPOTS, FALSE, 'PURGE_SPOTS', 'Remove all spots', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_SPOTS, ''));
$commands_list->register_command( new command('QUIT', 'command_quit',FALSE, FALSE, urdd_protocol::COMMAND_QUIT, FALSE, 'QUIT', 'End the connection', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('RESTART', 'command_restart',TRUE, TRUE, urdd_protocol::COMMAND_RESTART, FALSE, 'RESTART', 'Restart URDD', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('SCHEDULE', 'command_schedule',TRUE, FALSE, urdd_protocol::COMMAND_SCHEDULE, FALSE, 'SCHEDULE <command> @ \'<time>\' [# recurrence]', 'Run the given command at the given time, optionally repeat every recurrence.', '%l @ %t [# %n]', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('SENDSETINFO', 'command_sendsetinfo',TRUE, TRUE, urdd_protocol::COMMAND_SENDSETINFO, FALSE, 'SENDSETINFO', 'Send the extended set information to the news server', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_SYNC, ''));
$commands_list->register_command( new command('SET', 'command_set', TRUE, TRUE, urdd_protocol::COMMAND_SET, FALSE, 'SET LOG_LEVEL <value>|SCHEDULER <bool>|SERVER <id> <priority>|PREFERRED <id>|MODULE <id> <bool>|ENABLE <id> | DISABLE <id>', 'Set the internal parameter to the given value.', '%s %s [%s]', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('SHOW', 'command_show', TRUE, FALSE, urdd_protocol::COMMAND_SHOW, FALSE, 'SHOW ALL|QUEUE|NEWSGROUPS|SUBSCRIBED|FEEDS|USERS|THREADS|JOBS|CONFIG|VERSION|SERVERS|TESTS|LOAD|MODULES|UPTIME|TIME|STATUS', 'Output all actions on the queue, all newsgroups, all subscribed newsgroups, rss feeds, all logged in users, all running actions, all scheduled jobs, the running configuration or all servers, the results of the performed tests at startup, the system load; ALL outputs the threads, queue, jobs, users, servers, status', '%s', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('SHUTDOWN', 'command_shutdown', TRUE, TRUE, urdd_protocol::COMMAND_SHUTDOWN, FALSE, 'SHUTDOWN', 'Shutdown URDD', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('START_POST', 'command_post_action', TRUE, FALSE, urdd_protocol::COMMAND_START_POST, TRUE, 'START_POST', 'No operation (internal use)', '', FALSE, FALSE, TRUE, urd_modules::URD_CLASS_POST, 'P'));
$commands_list->register_command( new command('STOP', 'command_stop', TRUE, FALSE, urdd_protocol::COMMAND_STOP, FALSE, 'STOP ID|ALL', 'Stop a download with ID, or all download ', '(%n|%s)' , FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('SUBSCRIBE', 'command_subscribe', TRUE, TRUE, urdd_protocol::COMMAND_SUBSCRIBE, FALSE, 'SUBSCRIBE ID OFF|ON [Expire] ', 'Subscribe or unsubcribe to group ID with optional expire time in day ', '%n %s [%n]', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GROUPS, ''));
$commands_list->register_command( new command('SUBSCRIBE_RSS', 'command_subscribe_rss',TRUE, TRUE, urdd_protocol::COMMAND_SUBSCRIBE_RSS, FALSE, 'SUBSCRIBE_RSS ID OFF|ON [Expire]', 'Subscribe or unsubcribe to an rss feed with optional expire time in day ', '%n %s [%n]', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_RSS, ''));
$commands_list->register_command( new command('UNPAR_UNRAR', 'command_unpar_unrar',TRUE, FALSE, urdd_protocol::COMMAND_UNPAR_UNRAR, FALSE, 'UNPAR_UNRAR ID', 'Start to verify the downloaded files, unrar them and delete the rar and par2 file ', '%n', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('UNSCHEDULE', 'command_unschedule', TRUE, FALSE, urdd_protocol::COMMAND_UNSCHEDULE, FALSE, 'UNSCHEDULE ID|ALL|COMMAND (ARG|__ALL)', 'Remove a scheduled job with ID, all jobs, or a command with an argument or all of the jobs for that comman ', '(%n|%c|%s) %s)', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('UPDATE', 'command_update',TRUE, TRUE, urdd_protocol::COMMAND_UPDATE, TRUE, 'UPDATE ID|ALL', 'Update the articles is news group ID or all groups ', '(%n|%s)', TRUE, TRUE, FALSE, urd_modules::URD_CLASS_GROUPS, 'M'));
$commands_list->register_command( new command('UPDATE_RSS', 'command_update_rss',TRUE, TRUE, urdd_protocol::COMMAND_UPDATE_RSS, FALSE, 'UPDATE_RSS ID|ALL', 'Update the rss feed with ID or all feeds', '(%n|%s)', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_RSS, 'M'));
$commands_list->register_command( new command('USER', 'command_user', FALSE, FALSE, urdd_protocol::COMMAND_USER, FALSE, 'USER username', 'Enter a username to login', '%s', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));
$commands_list->register_command( new command('WHOAMI', 'command_whoami',TRUE, FALSE, urdd_protocol::COMMAND_WHOAMI, FALSE, 'WHOAMI', 'Returns the users login name', '', FALSE, FALSE, FALSE, urd_modules::URD_CLASS_GENERIC, ''));

function get_command_code($str)
{
    global $commands_list;

    return $commands_list->get_command_code($str);
}

function match_command($str)
{
    global $commands_list;

    return $commands_list->match_command($str);
}

function compare_command($str, $cmd)
{
    global $commands_list;

    return $commands_list->compare_command($str, $cmd);
}

function get_command($cmd)
{// get the commandstring for a command code
    global $commands_list;

    return $commands_list->get_command($cmd);
}

function get_help_all()
{
    global $commands_list;

    return $commands_list->get_help_all();
}

function get_command_posting($cmd)
{//return if a command needs an nntp connection
    global $commands_list;

    return $commands_list->get_command_posting($cmd);
}

function get_command_nntp($cmd)
{//return if a command needs an nntp connection
    global $commands_list;

    return $commands_list->get_command_nntp($cmd);
}

function get_command_primary_nntp($cmd)
{//return if a command needs an nntp connection
    global $commands_list;

    return $commands_list->get_command_primary_nntp($cmd);
}

function get_command_db_intensive($cmd)
{//return if a command needs an nntp connection
    global $commands_list;

    return $commands_list->get_command_db_intensive($cmd);
}
