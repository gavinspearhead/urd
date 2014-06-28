<?php
/*
 *  This file is part of Urd.
 *
 *  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2014-06-13 23:45:45 +0200 (vr, 13 jun 2014) $
 * $Rev: 3092 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: mail_functions.php 3092 2014-06-13 21:45:45Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathmf = realpath(dirname(__FILE__));
require_once "$pathmf/autoincludes.php";

class urd_mail
{
    private static $data;

    private static function reset_data_fields()
    {
        self::$data['password'] = '';
        self::$data['emailaddress'] = '';
        self::$data['ipaddress'] = '';
        self::$data['token'] = '';
        self::$data['username'] = '';
        self::$data['fullname'] = '';
        self::$data['group'] = '';
        self::$data['downloadstatus'] = 0;
        self::$data['downloadname'] = '';
        self::$data['custom_url'] = '';
    }

    private static function send_mail(DatabaseConnection $db, $msg, $sender, $email, $subject)
    {
        assert($email != '');
        $sendmail = get_config($db, 'sendmail');
        if (!$sendmail) { // sending mail not allowed by admin

            return;
        }
        $msg = wordwrap($msg, 70, "\r\n");
        $headers = "From: $sender" . "\r\n" .
            "Reply-To: $sender" . "\r\n" .
            'X-Mailer: URD/' . urd_version::get_version();

        $rv = mail($email, $subject, $msg, $headers);
        if ($rv === FALSE) {
            global $LN;
            throw new exception ($LN['error_couldnotsendmail'], ERR_SEND_FAILED);
        }
    }

    public static function parse_mail_template(DatabaseConnection $db, $template_orig, $fullname, $sender, $email_to)
    {
        
        $pathmf = realpath(dirname(__FILE__));
        $url = get_config($db, 'url');
        if (substr($url, -1) != '/') {
            $url .= '/';
        }

        $timestamp = date('r');

        $user_ipaddress = get_from_array(self::$data, 'ipaddress', '');
        $user_emailaddress = get_from_array(self::$data, 'emailaddress', '');
        $user_password = get_from_array(self::$data, 'password', '');
        $user_name = get_from_array(self::$data, 'username', '');
        $user_fullname = get_from_array(self::$data, 'fullname', '');
        $group = get_from_array(self::$data, 'group', '');
        $token = get_from_array(self::$data, 'token', '');
        $downloadstatus = get_from_array(self::$data, 'downloadstatus', '');
        $downloadname = get_from_array(self::$data, 'downloadname', '');
        $custom_url = get_from_array(self::$data, 'custom_url', '');

        $perc = array('%f', '%u', '%p', '%A', '%F', '%g', '%d', '%t', '%i', '%E', '%T', '%U');
        $perc_replace = array ($fullname, $url, $user_password, $user_name, $user_fullname, $group, $downloadname, $timestamp, $user_ipaddress, $user_emailaddress, $token, $custom_url);

        $template = get_config($db, $template_orig, '');
        if ($template == '') {
            write_log('No message sent', LOG_INFO);

            return;
        }
        $template_dir = my_realpath(dirname(__FILE__) . '/../mail_templates/');
        add_dir_separator($template_dir);
        $message = file($template_dir . $template);
        if ($message === FALSE) {
            write_log("Template file for e-mail not found: $template_dir$template", LOG_WARNING);

            return;
        }
        $blank_count = 0;
        $subject = '';
        $msg = '';
        foreach ($message as $line) {
            if ($line[0] == '#') {
                continue;
            }

            if ($blank_count == 0 && strcasecmp(substr($line, 0, 8), 'subject:') == 0) {
                $subject = trim(substr($line, 9));
                continue;
            }
            if (trim($line) == '' && $blank_count == 0) {
                $blank_count++;
                continue;
            }

            if ($blank_count > 0) {
                $line = str_replace($perc, $perc_replace, $line);
                if (($pos = strpos($line, '%s{')) !== FALSE) {
                    $open = $pos + 2;
                    $close = strpos($line, '}', $open + 1);
                    if ($close === FALSE) {
                        throw new exception ('Parse error in mail_template.', ERR_SEND_FAILED);
                    }

                    $options = substr($line, $open, $close - $open);
                    $options = trim($options, '{} \t');
                    $parts = explode('|', $options);
                    $replacement = '';
                    foreach ($parts as $part) {
                        list($p1, $p2) = explode(':', $part, 2);
                        if ($p1 == $downloadstatus || $p1== '*') {
                            $replacement = $p2;
                            break;
                        }
                    }
                    $line = substr_replace($line, $replacement, $pos, $close - $pos + 1);
                }
            }
            $line = rtrim($line);
            $msg .= "$line\r\n";

        }
        urd_mail::send_mail($db, $msg, $sender, $email_to, $subject);
    }

    /*
     * %f fullname of addressee
     * %u URD url
     * %p user password
     * %A account user name
     * %F account full name
     * %T registration token
     * %E account user e-mail address
     * %g group / feed / spot
     * %s Download Status
     * %d download name
     * %t Time stamp
     * %U custom url
     */

    public static function mail_user_update(DatabaseConnection $db, $username, $fullname, $email, $active, $sender, $ipaddress)
    {
        assert($email != '');
        self::reset_data_fields();

        self::$data['ipaddress'] = $ipaddress;
        self::$data['username'] = $username;
        self::$data['fullname'] = $fullname;

        $url = get_config($db, 'url');
        if (substr($url, -1) != '/') {
            $url .= '/';
        }
        if ($active == user_status::USER_ACTIVE) {
            $template = 'mail_account_activated';
            urd_mail::parse_mail_template($db, $template, $fullname, $sender, $email);
        } elseif ($active == user_status::USER_INACTIVE) {
            $template = 'mail_account_disabled';
            urd_mail::parse_mail_template($db, $template, $fullname, $sender, $email);
        } else {
            return; //  error
        }
    }

    public static function mail_admin_new_user(DatabaseConnection $db, $name, $fullname, $email_to, $sender, $ipaddress, $user_emailaddress)
    {
        assert($email_to != '');
        self::reset_data_fields();
        self::$data['ipaddress'] = $ipaddress;
        self::$data['emailaddress'] = $user_emailaddress;
        self::$data['username'] = $name;
        self::$data['fullname'] = $fullname;

        $template = 'mail_new_user';
        urd_mail::parse_mail_template($db, $template, $fullname, $sender, $email_to);
    }

    public static function mail_pw(DatabaseConnection $db, $fullname, $username, $email, $password, $sender)
    {
        assert($email != '');
        self::reset_data_fields();

        self::$data['password'] = $password;
        self::$data['username'] = $username;
        self::$data['fullname'] = $fullname;

        $template = 'mail_password_reset';

        urd_mail::parse_mail_template($db, $template, $fullname, $sender, $email);
    }

    public static function mail_activation(DatabaseConnection $db, $username, $fullname, $email, $token, $sender)
    {
        assert($email != '');
        self::reset_data_fields();

        self::$data['username'] = $username;
        self::$data['fullname'] = $fullname;
        self::$data['token'] = $token;

        $template = 'mail_activate_account';

        urd_mail::parse_mail_template($db, $template, $fullname, $sender, $email);
    }

    public static function mail_update_preferences(DatabaseConnection $db, $userid)
    {
        assert(is_numeric($userid));
        global $LN;
        self::reset_data_fields();
        $mail_user = get_pref($db, 'mail_user', $userid);
        if ($mail_user == 0) {
            return;
        }

        $sender = get_config($db, 'admin_email');
        $qry = '"name", "email", "fullname" FROM users WHERE "ID"=?';
        $res = $db->select_query($qry, 1, array($userid));
        if (!isset($res[0]['email'])) {
            throw new exception ($LN['error_nosuchuser'], ERR_INVALID_USERNAME);
        }
        $email = $res[0]['email'];
        $fullname = $res[0]['fullname'];
        $username = $res[0]['name'];

        self::$data['fullname'] = $fullname;
        self::$data['username'] = $username;

        $template = 'mail_new_preferences';

        urd_mail::parse_mail_template($db, $template, $fullname, $sender, $email);
    }

    public static function mail_sets(DatabaseConnection $db, array $sets, $userid, $groupid, $type)
    {
        global $LN;

        self::reset_data_fields();
        assert(in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)) && is_numeric($userid));

        $mail_user = get_pref($db, 'mail_user', $userid); // fixme other pref
        if ($mail_user == 0) {
            return;
        }
        $sender = get_config($db, 'admin_email');
        $qry = '"name", "email", "fullname" FROM users WHERE "ID"=?';
        $res = $db->select_query($qry, 1, array($userid));
        if (!isset($res[0]['email'])) {
            throw new exception ($LN['error_nosuchuser'], ERR_INVALID_USERNAME);
        }
        $email = $res[0]['email'];
        $fullname = $res[0]['fullname'];
        $username = $res[0]['name'];
        $mailsubject = 'URD new interesting sets found';
        $url = get_config($db, 'url');
        if (substr($url, -1) != '/') {
            $url .= '/';
        }
        $in_group_str = '';
        if (is_numeric($groupid) && $groupid > 0) {
            if ($type == USERSETTYPE_GROUP) {
                $group = group_name($db, $groupid);
                $in_group_str = "group: $group";
                $url .= "html/browse.php?groupID=$groupid";
            } elseif ($type == USERSETTYPE_RSS) {
                $rss_feed = feed_name($db, $groupid);
                $in_group_str = "feed: $rss_feed";
                $url .= "html/rsssets.php?feed_id=$groupid";
            } else {
                throw new exception ($LN['error_unknowntype']);
            }
        } else {
            if ($type == USERSETTYPE_GROUP) {
                    $in_group_str = 'groups';
                    $url .= 'html/browse.php';
            } elseif ($type == USERSETTYPE_SPOT) {
                $in_group_str = 'spots';
                $url .= 'html/spots.php';
            } elseif ($type == USERSETTYPE_RSS) {
                $in_group_str = 'RSS feeds';
                $url .= 'html/rsssets.php';
            }
        }
        self::$data['fullname'] = $fullname;
        self::$data['username'] = $username;
        self::$data['group'] = $in_group_str;
        self::$data['custom_url'] = $url;

        foreach ($sets as $set) {
            if ($type == USERSETTYPE_GROUP) {
                $sql = '"subject" FROM setdata WHERE "ID"=?';
            } elseif ($type == USERSETTYPE_RSS) {
                $sql = '"setname" AS "subject" FROM rss_sets WHERE "setid"=?';
            } elseif ($type == USERSETTYPE_SPOT) {
                $sql = '"title" AS "subject" FROM spots WHERE "spotid"=?';
            }

            $res = $db->select_query($sql, 1, array($set['ID']));
            if (isset($res[0]['subject'])) {
                $subject = sanitise_download_name($db, $res[0]['subject']);
                $subject = preg_replace('/(\byEnc\b)|(\breq:)|(\bwww\.[\w\.]*\b)|(\bhttp:\/\/[\w\.]*\b)|([=><#])/i', '', $subject);
                self::$data['downloadname'] .= $subject . "\r\n";
            }

        }
        $template = 'mail_new_interesting_sets';
        urd_mail::parse_mail_template($db, $template, $fullname, $sender, $email);
    }

    public static function mail_user_download(DatabaseConnection $db, $dlid, $userid, $status)
    {
        global $LN;
        assert(is_numeric($dlid) && is_numeric($userid));
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        self::reset_data_fields();
        $mail_user = get_pref($db, 'mail_user', $userid);
        if ($mail_user == 0) {
            return;
        }
        $sender = get_config($db, 'admin_email');
        $dlname = get_download_name($db, $dlid);
        $qry = '"email", "fullname", "name" FROM users WHERE "ID"=?';
        $res = $db->select_query($qry, 1, array($userid));
        if (!isset($res[0]['email'])) {
            throw new exception ($LN['error_nosuchuser'], ERR_INVALID_USERNAME);
        }
        $email = $res[0]['email'];
        $fullname = $res[0]['fullname'];
        $username = $res[0]['name'];

        self::$data['fullname'] = $fullname;
        self::$data['username'] = $username;
        self::$data['downloadstatus'] = $status;
        self::$data['downloadname'] = $dlname;

        $template = 'mail_download_status';
        urd_mail::parse_mail_template($db, $template, $fullname, $sender, $email);
    }
}
