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
 * $LastChangedDate: 2014-05-29 01:03:02 +0200 (do, 29 mei 2014) $
 * $Rev: 3058 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_show_post.php 3058 2014-05-28 23:03:02Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';
$pathadt = realpath(dirname(__FILE__));

require_once "$pathadt/../functions/ajax_includes.php";

verify_access($db, urd_modules::URD_CLASS_POST, FALSE, 'P', $userid);

try {

    $dlpath = get_dlpath($db);
    $postid = get_request('postid', NULL);

    $dir = $dlpath . POST_PATH . $username;
    $readonly = FALSE;

    $dirs = glob($dir . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);

    $download_delay = get_pref($db, 'download_delay', $userid, 0);
    if ($download_delay > 0) {
        $download_delay = "+$download_delay minutes";
    } else {
        $download_delay = '';
    }

    if ($postid === NULL || !is_numeric($postid)) {
        if ($dirs == array()) {
            throw new exception($LN['error_nouploaddata'] . ': ' . $dir);
        }
        $poster_email = $prefs['poster_email'];
        $poster_name = $prefs['poster_name'];
        $recovery_size = $prefs['recovery_size'];
        list($val, $suffix) = format_size($prefs['rarfile_size'], 'h', '',1024, 0);
        $rarfile_size =  $val . $suffix;
        $delete_files = 0;
        $subject = '';
        $group = '';
        $group_nzb = '';
        $dir = '';
        $postid = '';
        $start_time = $download_delay;
    } else {
        $row = get_post_info($db, $userid, $postid);
        $group = $row['groupid'];
        $group_nzb = $row['groupid_nzb'];
        $subject = $row['subject'];
        $poster_email = $row['poster_id'];
        $poster_name = $row['poster_name'];
        list($val, $suffix) = format_size($row['filesize_rar'], 'h', '',1024, 0);
        $rarfile_size =  $val . $suffix;
        $recovery_size = $row['recovery_par'];
        $delete_files = $row['delete_files'];
        $start_time = time_format($row['start_time']);
        $status = $row['status'];
        $dir = $row['src_dir'];
        if ($status != POST_READY) {
            $readonly = TRUE;
        }
    }

    $groups = array();
    try {
        $groupinfo = get_all_active_groups($db);
        foreach ($groupinfo as $gr) {
            $groups[$gr['ID']] = $gr['name'];
        }
    } catch (exception $e) {
        $groups = array();
    }
    natsort($groups);

    $default_nzb_group = get_config($db, 'ftd_group', '');
    $default_nzb_group_id = get_all_group_by_name($db, $default_nzb_group);
    if (! isset($groups [ $default_nzb_group_id ])) {
        $default_nzb_group = array( 'group_id' => $default_nzb_group_id , 'group_name'=>$default_nzb_group);
    } else {
        $default_nzb_group = NULL;
    }

    init_smarty('', 0);
    $smarty->assign('postid',      	    $postid);
    $smarty->assign('groups',    	    $groups);
    $smarty->assign('group',    	    $group);
    $smarty->assign('group_nzb',    	$group_nzb);
    $smarty->assign('default_nzb_group',$default_nzb_group);
    $smarty->assign('dirs',    	        $dirs);
    $smarty->assign('readonly',         $readonly?1:0);
    $smarty->assign('dir',    	        $dir);
    $smarty->assign('subject',    	    $subject);
    $smarty->assign('start_time',    	$start_time);
    $smarty->assign('poster_name',    	$poster_name);
    $smarty->assign('delete_files',    	$delete_files);
    $smarty->assign('poster_email',    	$poster_email);
    $smarty->assign('rarfile_size',    	$rarfile_size);
    $smarty->assign('recovery_size',   	$recovery_size);

    if (!$smarty->getTemplateVars('urdd_online')) {
        throw new exception($LN['urdddisabled']);
    }
    $contents = $smarty->fetch('ajax_show_post.tpl');
    return_result(array('contents' => $contents));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
