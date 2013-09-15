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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_post_message.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathaet = realpath(dirname(__FILE__));

require_once "$pathaet/../functions/ajax_includes.php";

verify_access($db, urd_modules::URD_CLASS_POST, FALSE, 'P', $userid);

/*if (!urd_user_rights::is_poster($db, $userid)) {
    throw new exception($LN['error_accessdenied']);
}*/

$groupid = get_request('groupid', '');
$spotid = get_request('spotid', '');

if (substr($groupid, 0, 6) == 'group_') {
    $groupid = substr($groupid, 6);
}

$cmd = get_request('cmd');
switch (strtolower($cmd)) {
    case 'post' :
        challenge::verify_challenge_text($_POST['challenge']);
        $uc = new urdd_client($db, get_config($db, 'urdd_host'), get_config($db,'urdd_port'), $userid);

        $subject = get_request('subject', '');
        $poster = get_request('postername', '');
        $email = get_request('posteremail', '');
        $message = get_request('message', '');
        $reference = get_request('reference', '');
        $headers = '';
        if (!is_numeric($groupid)) {
            throw new exception ($LN['error_invalidgroupid']);
        }
        if ($reference != '') {
            $headers = serialize(array('References' => "<$reference>"));
        }

        $post_id = $db->insert_query('post_messages',
                array('userid', 'groupid', 'subject', 'poster_id', 'poster_name', 'message', 'headers'),
                array($userid, $groupid, $subject, $email, $poster, $message, $headers), TRUE);
        $uc->post_message($post_id);
        $uc->disconnect();
        die_html('OK' . $LN['taskpostmessage']);
        break;

    case 'show':
        $reference = $subject = $content = '';
        $poster_email = $prefs['poster_email'];
        $poster_name = $prefs['poster_name'];
        if ($spotid != '') {
            $group = get_config($db, 'spots_reports_group');
            $groups = array($group);
            $db->escape($spotid, TRUE);
            $sql = "messageid, title FROM spots WHERE spotid = $spotid";
            $res = $db->select_query($sql, 1);
            if ($res === FALSE) {
                throw new exception($LN['error_spotnotfound']);
            }
            $messageid = $res[0]['messageid'];
            $title = $res[0]['title'];
            $subject = "REPORT <$messageid> $title";
            $content = "SPAM";
            $reference = "$messageid";
        } else {
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
