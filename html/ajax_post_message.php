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
 * $LastChangedDate: 2014-06-15 00:41:23 +0200 (zo, 15 jun 2014) $
 * $Rev: 3095 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_post_message.php 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathaet = realpath(dirname(__FILE__));

require_once "$pathaet/../functions/ajax_includes.php";

verify_access($db, urd_modules::URD_CLASS_POST, FALSE, 'P', $userid);

$groupid = get_request('groupid', '');
$spotid = get_request('spotid', '');

if (substr($groupid, 0, 6) == 'group_') {
    $groupid = substr($groupid, 6);
}

function generate_hash($prefix, $infix)
{
    do {
        $unique_str = generate_password(15);
        $messageid = '<' . $prefix . '.' . $infix . '.' . $unique_str . '@spot.net>';
        $hash = sha1($messageid);
    } while (substr($hash, 0, 4) == '0000');
    return $messageid;
}

function prepare_message(DatabaseConnection $db, $userid, $messageid, &$header_data)
{
    $server_privatekey = get_config($db, 'privatekey');
    $spot_signing = Services_Signing_Base::factory();
    $user_privatekey = get_user_private_key($db, $userid);
    $server_signature = $spot_signing->signMessage($server_privatekey, $messageid);
    $user_signature = $spot_signing->signMessage($user_privatekey, $messageid);
    $header_data['X-User-Signature'] = prepare_base64($user_signature['signature']);
    $header_data['X-User-Key'] = pubkey_to_xml($user_signature['publickey']);
    $header_data['X-Server-Signature'] = prepare_base64($server_signature['signature']);
    $header_data['X-Server-Key'] = pubkey_to_xml($server_signature['publickey']);
    $header_data['X-No-Archive'] = 'yes';
    $header_data['Message-ID'] = $messageid;
}

$cmd = get_request('cmd');
switch (strtolower($cmd)) {
    case 'post' :
        $type = get_request('type', '');
        challenge::verify_challenge_text($_POST['challenge']);
        $uc = new urdd_client($db, get_config($db, 'urdd_host'), get_config($db,'urdd_port'), $userid);

        $subject = trim(get_request('subject', ''));
        $poster = trim(get_request('postername', ''));
        $email = trim(get_request('posteremail', ''));
        $message = trim(get_request('message', ''));
        $reference = get_request('reference', '');
        $rating = get_request('rating', 0);
        $headers = '';
        $header_data = array();
        if (!is_numeric($groupid)) {
            throw new exception ($LN['error_invalidgroupid']);
        }
        if ($reference != '') {
            $header_data['References'] = "<$reference>";
        }
        if (strlen($message) <= 2) {
            throw new exception($LN['error_nocontent']); 
        }
        if ($subject == '') {
            throw new exception($LN['error_subjectnofound']); 
        }
        if ($poster == '') {
            throw new exception($LN['error_namenotfound']); 
        }
        if ($email == '') {
            throw new exception($LN['error_posternotfound']); 
        }
        if (!verify_email($email)) {
            throw new exception($LN['error_invalidemail']); 
        }
        if (strlen($message) >= 1024*10) {
            throw new exception($LN['error_toolong']); 
        }

        if ($type == 'comment') {
            $reference_msgid = substr($reference, 0, strpos($reference, '@'));
            $messageid = generate_hash($reference_msgid, $rating);
            // check messageid is unique XXX
            prepare_message($db, $userid, $messageid, $header_data);
            $header_data['X-User-Rating'] = $rating;
        } elseif ($type == 'report') {
            $reference_msgid = substr($reference, 0, strpos($reference, '@'));
            $rndstr = generate_password(15);
            $messageid = generate_hash($reference_msgid, $rndstr);
            prepare_message($db, $userid, $messageid, $header_data);
        }
        if ($header_data != array()) {
            $headers = serialize($header_data);
        }
        $post_id = $db->insert_query('post_messages',
                array('userid', 'groupid', 'subject', 'poster_id', 'poster_name', 'message', 'headers'),
                array($userid, $groupid, $subject, $email, $poster, $message, $headers), TRUE);
        $uc->post_message($post_id);
        $uc->disconnect();
        die_html('OK' . $LN['taskpostmessage']);
        break;

    case 'show':
        $type = get_request('type', '');
        $reference = $subject = $content = '';
        $poster_email = $prefs['poster_email'];
        $poster_name = $prefs['poster_name'];
        if ($spotid != '' && $type == 'report') {
            $group = get_config($db, 'spots_reports_group');
            $groups = array(group_by_name($db, $group) => $group);
            $sql = "messageid, title FROM spots WHERE spotid = ?";
            $res = $db->select_query($sql, 1, array($spotid));
            if ($res === FALSE) {
                throw new exception($LN['error_spotnotfound']);
            }
            $messageid = $res[0]['messageid'];
            $title = $res[0]['title'];
            $subject = "REPORT <$messageid> $title";
            $content = "SPAM";
            $reference = "$messageid";
        } elseif ($spotid != '' && $type == 'comment') {
            $group = get_config($db, 'spots_comments_group');
            $groups = array(group_by_name($db, $group) => $group);
            $sql = "messageid, title FROM spots WHERE spotid = ?";
            $res = $db->select_query($sql, 1, array($spotid));
            if ($res === FALSE) {
                throw new exception($LN['error_spotnotfound']);
            }
            $messageid = $res[0]['messageid'];
            $title = $res[0]['title'];
            $subject = "Re: $title";
            $reference = "$messageid";
        } else {
            $type = 'group';
            $groupinfo = get_all_active_groups($db);
            foreach ($groupinfo as $gr) {
                $groups[$gr['ID']] = $gr['name'];
            }
            natsort($groups);
        }
        init_smarty('', 0);
        if (!$smarty->getTemplateVars('urdd_online')) {
            throw new exception($LN['urdddisabled']);
        }

        $smarty->assign('groups',    	    $groups);
        $smarty->assign('ratings',    	    range(0,10));
        $smarty->assign('type',    	        $type);
        $smarty->assign('reference',   	    $reference);
        $smarty->assign('content',    	    $content);
        $smarty->assign('subject',    	    $subject);
        $smarty->assign('groupid',    	    $groupid);
        $smarty->assign('poster_name',    	$poster_name);
        $smarty->assign('poster_email',    	$poster_email);

        $smarty->display('ajax_post_message.tpl');
        die;
    default:
        throw new exception($LN['error_novalidaction']);
}

// Success:
