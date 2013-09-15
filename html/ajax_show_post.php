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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_show_post.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';
$pathadt = realpath(dirname(__FILE__));

require_once "$pathadt/../functions/ajax_includes.php";

verify_access($db, urd_modules::URD_CLASS_POST, FALSE, 'P', $userid);

$dlpath = get_dlpath($db);
$postid = get_request('postid', NULL);

$dir = $dlpath . POST_PATH . $username;
$readonly = FALSE;

$dirs = glob($dir . '/*', GLOB_ONLYDIR);

$download_delay = get_pref($db, 'download_delay', $username, 0) ;
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
    $dir = '';
    $postid = '';
    $start_time = $download_delay;
} else {
    $row = get_post_info($db, $userid, $postid);
    $group = $row['groupid'];
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

init_smarty('', 0);
$smarty->assign('postid',      	    $postid);
$smarty->assign('groups',    	    $groups);
$smarty->assign('group',    	    $group);
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
} else {
    $smarty->display('ajax_show_post.tpl');
}
