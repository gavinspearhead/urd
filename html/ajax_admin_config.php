<?php
/**
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
 * $LastChangedDate: 2013-07-08 10:35:37 +0200 (ma, 08 jul 2013) $
 * $Rev: 2869 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: admin_config.php 2869 2013-07-08 08:35:37Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathaac = realpath(dirname(__FILE__));

require_once "$pathaac/../functions/ajax_includes.php";
require_once "$pathaac/../functions/pref_functions.php";
require_once "$pathaac/../functions/periods.php";

verify_access($db, NULL, TRUE, '', $userid, FALSE);

function get_permissions_array()
{
    global $LN;
    $permissions = array(
        ''     => $LN['config_perms']['none'],
        '0400' => $LN['config_perms']['0400'],
        '0600' => $LN['config_perms']['0600'],
        '0640' => $LN['config_perms']['0640'],
        '0644' => $LN['config_perms']['0644'],
        '0660' => $LN['config_perms']['0660'],
        '0664' => $LN['config_perms']['0664'],
        '0666' => $LN['config_perms']['0666']
    );

    return $permissions;
}

function get_on_off_array()
{
    global $LN;
    $on_off = array(
        'on'    => $LN['on'],
        'off'   => $LN['off']
    );

    return $on_off;
}

function get_cleandir_dirs_array()
{
    global $LN;
    $cleandir_dirs = array(
        'all'       => $LN['all'],
        'preview'   => $LN['preview'],
        'tmp'       => $LN['temporary']
    );

    return $cleandir_dirs;
}

function get_users_array(DatabaseConnection $db)
{
    global $LN;
    $all_users = get_all_users($db);
    $users[''] = $LN['disabled'];
    foreach ($all_users as $u) {
        $users[$u] = $u;
    }

    return $users;
}

function get_log_levels_array()
{
    global $log_str;
    $log_levels = array();

    foreach ($log_str as $level => $log) {
        $log_levels[$level] = ucwords($log);
    }

    return $log_levels;
}

function get_groups_array()
{
    try {
        $groups_orig = read_system_groups();
    } catch (exception$e) {
        $groups_orig = array();
    }

    sort($groups_orig);
    $groups[''] = '';
    foreach ($groups_orig as $g) {
        $groups["$g"] = $g;
    }

    return $groups;
}

function show_config(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    global $LN, $isadmin, $periods, $smarty;

    $follow_link_msg = $shaping_msg = $auto_expire_msg = $urdd_restart_msg = $nntp_useauth_msg = $auto_reg_msg = $register_msg = $hiddenfiles_msg = $urdd_daemonise_msg = $nntp_all_servers_msg = '';
    $sendmail_msg = $webdownload_msg = $auto_download_msg = $check_nntp_connections_msg = $user_scripts_msg = $global_scripts_msg = $download_spots_comments_msg = $download_spots_reports_msg = '';
    $parse_nfo_msg = $keep_int_msg = $compress_nzb_msg = $webeditfile_msg = $config_viewfiles_msg = $auto_getnfo_msg = $allow_robots_msg = $clickjack_msg = $download_spots_images_msg = '';
    $config_groups_msg = $config_makenzb_msg = $config_usenzb_msg = $config_post_msg = $config_rss_msg = $config_sync_msg = $config_download_msg = $need_challenge_msg = $use_encrypted_passwords_msg =
    $config_download_comment_avatar_msg = '';

    $module_msg = array(
        urd_modules::URD_CLASS_GENERIC      => '',
        urd_modules::URD_CLASS_GROUPS       => '',
        urd_modules::URD_CLASS_USENZB       => '',
        urd_modules::URD_CLASS_MAKENZB      => '',
        urd_modules::URD_CLASS_RSS          => '',
        urd_modules::URD_CLASS_SYNC         => '',
        urd_modules::URD_CLASS_SPOTS        => '',
        urd_modules::URD_CLASS_DOWNLOAD     => '',
        urd_modules::URD_CLASS_VIEWFILES    => '',
        urd_modules::URD_CLASS_POST         => ''
    );

    $URDDONLINE = urdd_connected($db, $userid);
    $prefArray_root = get_default_config();
    $prefArray_root = array_merge($prefArray_root, load_config($db, TRUE));

    $pref_level = get_pref($db, 'pref_level', $userid);
    $permissions = get_permissions_array();
    $cleandir_dirs = get_cleandir_dirs_array();
    $groups = get_groups_array();
    $on_off = get_on_off_array();
    $log_levels = get_log_levels_array();
    $languages = get_languages();
    $stylesheets = get_stylesheets($db, $userid);
    $mail_templates = get_mail_templates();
    $templates = get_templates();
    $users = get_users_array($db);
    $index_page_array = get_index_page_array($isadmin, urd_modules::get_urd_module_config(get_config($db, 'modules')));

    $levels = user_levels::get_user_levels();

    foreach (urd_extsetinfo::$SETTYPES as $t) {
        if (!isset($settype_msg[$t])) {
            $settype_msg[$t] = verify_text($prefArray_root["settype_$t"]);
        }
    }

    if (!isset($poster_blacklist_msg)) {
        $poster_blacklist_msg = verify_text_area($prefArray_root['poster_blacklist']);
    }
    if (!isset($index_page_msg)) {
        $index_page_msg = verify_array($prefArray_root['index_page_root'], array_keys($index_page_array));
    }
    if (!isset($group_msg)) {
        $group_msg = verify_group($prefArray_root['group'], TRUE);
    }
    if (!isset($permissions_msg)) {
        $permissions_msg = verify_array($prefArray_root['permissions'], array_keys($permissions));
    }
    if (!isset($log_level_msg)) {
        $log_level_msg = verify_array($prefArray_root['log_level'], array_keys($log_levels));
    }
    if (!isset($scheduler_msg)) {
        $scheduler_msg = verify_array($prefArray_root['scheduler'], array_keys($on_off));
    }
    if (!isset($hidden_files_list_msg)) {
        $hidden_files_list_msg = verify_text_area($prefArray_root['global_hidden_files_list']);
    }
    if (!isset($url_msg)) {
        $url_msg = verify_url($prefArray_root['baseurl']);
    }
    if (!isset($admin_email_msg)) {
        $admin_email_msg = verify_email_address($prefArray_root['admin_email']) ;
    }
    if (!isset($unrar_path_msg)) {
        $unrar_path_msg = verify_prog($prefArray_root['unrar_path'], TRUE);
    }
    if (!isset($rar_path_msg)) {
        $rar_path_msg = verify_prog($prefArray_root['rar_path'], TRUE);
    }
    if (!isset($unarj_path_msg)) {
        $unarj_path_msg = verify_prog($prefArray_root['unarj_path'], TRUE);
    }
    if (!isset($subdownloader_path_msg)) {
        $subdownloader_path_msg = verify_prog($prefArray_root['subdownloader_path'], TRUE);
    }
    if (!isset($file_path_msg)) {
        $file_path_msg = verify_prog($prefArray_root['file_path'], TRUE);
    }
    if (!isset($tar_path_msg)) {
        $tar_path_msg = verify_prog($prefArray_root['tar_path'], TRUE);
    }
    if (!isset($gzip_path_msg)) {
        $gzip_path_msg = verify_prog($prefArray_root['gzip_path'], TRUE);
    }
    if (!isset($unzip_path_msg)) {
        $unzip_path_msg = verify_prog($prefArray_root['unzip_path'], TRUE);
    }
    if (!isset($un7zr_path_msg)) {
        $un7zr_path_msg = verify_prog($prefArray_root['un7zr_path'], TRUE);
    }
    if (!isset($unace_path_msg)) {
        $unace_path_msg = verify_prog($prefArray_root['unace_path'], TRUE);
    }
    if (!isset($unpar_path_msg)) {
        $unpar_path_msg = verify_prog($prefArray_root['unpar_path'], TRUE);
    }
    if (!isset($yydecode_path_msg)) {
        $yydecode_path_msg = verify_prog($prefArray_root['yydecode_path']);
    }
    if (!isset($yyencode_path_msg)) {
        $yyencode_path_msg = verify_prog($prefArray_root['yyencode_path'], TRUE);
    }
    if (!isset($urdd_path_msg)) {
        $urdd_path_msg = verify_prog($prefArray_root['urdd_path']);
    }
    if (!isset($cksfv_path_msg)) {
        $cksfv_path_msg = verify_prog($prefArray_root['cksfv_path'], TRUE);
    }
    if (!isset($trickle_path_msg)) {
        $trickle_path_msg = verify_prog($prefArray_root['trickle_path'], TRUE);
    }
    if (!isset($urdd_maxthreads_msg)) {
        $urdd_maxthreads_msg = verify_numeric($prefArray_root['urdd_maxthreads'], 1);
    }
    if (!isset($nntp_maxdlthreads_msg)) {
        $nntp_maxdlthreads_msg = verify_numeric($prefArray_root['nntp_maxdlthreads'], 0);
    }
    if (!isset($clean_dir_age_msg)) {
        $clean_dir_age_msg = verify_numeric($prefArray_root['clean_dir_age'], 0);
    }
    if (!isset($users_clean_age_msg)) {
        $users_clean_age_msg = verify_numeric($prefArray_root['users_clean_age'], 0);
    }
    if (!isset($max_dl_name_msg)) {
        $max_dl_name_msg = verify_numeric($prefArray_root['max_dl_name'], 16);
    }
    if (!isset($clean_db_age_msg)) {
        $clean_db_age_msg = verify_numeric($prefArray_root['clean_db_age'], 0);
    }
    if (!isset($spot_expire_spam_count_msg)) {
        $spot_expire_spam_count_msg = verify_numeric($prefArray_root['spot_expire_spam_count'], 0);
    }
    if (!isset($nntp_maxthreads_msg)) {
        $nntp_maxthreads_msg = verify_numeric($prefArray_root['nntp_maxthreads'], 1);
    }
    if (!isset($db_intensive_maxthreads_msg)) {
        $db_intensive_maxthreads_msg = verify_numeric($prefArray_root['db_intensive_maxthreads'], 1);
    }
    if (!isset($maxfilesize_msg)) {
        $maxfilesize_msg = verify_numeric($prefArray_root['maxfilesize'], 0);
    }
    if (!isset($maxpreviewsize_msg)) {
        $maxpreviewsize_msg = verify_numeric($prefArray_root['maxpreviewsize'], 0);
    }
    if (!isset($maxexpire_msg)) {
        $maxexpire_msg = verify_numeric($prefArray_root['maxexpire'], 0);
    }
    if (!isset($max_login_count_msg)) {
        $max_login_count_msg = verify_numeric($prefArray_root['max_login_count'], 0);
    }
    if (!isset($maxheaders_msg)) {
        $maxheaders_msg = verify_numeric($prefArray_root['maxheaders'], 0);
    }
    if (!isset($connection_timeout_msg)) {
        $connection_timeout_msg = verify_numeric($prefArray_root['connection_timeout'], 0);
    }
    if (!isset($queue_size_msg)) {
        $queue_size_msg = verify_numeric($prefArray_root['queue_size'], 0);
    }
    if (!isset($urdd_connection_timeout_msg)) {
        $urdd_connection_timeout_msg = verify_numeric($prefArray_root['urdd_connection_timeout'], 0);
    }
    if (!isset($socket_timeout_msg)) {
        $socket_timeout_msg = verify_numeric($prefArray_root['socket_timeout'], 0);
    }
    if (!isset($total_max_articles_msg)) {
        $total_max_articles_msg = verify_numeric($prefArray_root['total_max_articles'], 0);
    }
    if (!isset($nice_value_msg)) {
        $nice_value_msg = verify_numeric($prefArray_root['nice_value'], 0, 19);
    }
    if (!isset($urdd_port_msg)) {
        $urdd_port_msg = verify_numeric($prefArray_root['urdd_port'], 1, 65535);
    }
    if (!isset($urdd_host_msg)) {
        $urdd_host_msg = verify_text($prefArray_root['urdd_host'], '[a-zA-Z0-9.\-_:\[\]]');
    }
    if (!isset($urdd_uid_msg)) {
        $urdd_uid_msg = verify_text_opt($prefArray_root['urdd_uid'], FALSE, '[a-zA-Z0-9.-]');
    }
    if (!isset($urdd_gid_msg)) {
        $urdd_gid_msg = verify_text_opt($prefArray_root['urdd_gid'], FALSE, '[a-zA-Z0-9.-]');
    }
    if (!isset($replacement_str_msg)) {
        $replacement_str_msg = verify_text_opt($prefArray_root['replacement_str'], FALSE, '[a-zA-Z0-9.\-_:\[\] ()!@#$%^&{}+;]');
    }
    if (!isset($group_filter_msg)) {
        $group_filter_msg = verify_text_opt($prefArray_root['group_filter'], FALSE, '[a-zA-Z0-9.*?, ]');
    }
    if (!isset($keystore_path_msg)) {
        $keystore_path_msg = verify_read_only_path($db, $prefArray_root['keystore_path']);
    }
    if (!isset($spots_reports_group_msg)) {
        $spots_reports_group_msg = verify_text_opt($prefArray_root['spots_reports_group'], FALSE, '[a-zA-Z0-9.]');
    }
    if (!isset($spots_group_msg)) {
        $spots_group_msg = verify_text_opt($prefArray_root['spots_group'], FALSE, '[a-zA-Z0-9.]');
    }
    if (!isset($spots_comments_group_msg)) {
        $spots_comments_group_msg = verify_text_opt($prefArray_root['spots_comments_group'], FALSE, '[a-zA-Z0-9.]');
    }
    if (!isset($ftd_group_msg)) {
        $ftd_group_msg = verify_text_opt($prefArray_root['ftd_group'], FALSE, '[a-zA-Z0-9.]');
    }
    if (!isset($spots_blacklist_msg)) {
        $spots_blacklist_msg = verify_text_opt($prefArray_root['spots_blacklist'], FALSE, '[a-zA-Z0-9.:\/&%#;+_\-]');
    }
    if (!isset($spots_whitelist_msg)) {
        $spots_whitelist_msg = verify_text_opt($prefArray_root['spots_whitelist'], FALSE, '[a-zA-Z0-9.:\/&%#;+_\-]');
    }
    if (!isset($extset_group_msg)) {
        $extset_group_msg = verify_text_opt($prefArray_root['extset_group'], FALSE, '[a-zA-Z0-9.]');
    }
    if (!isset($dlpath_msg)) {
        $dlpath_msg = verify_dlpath($db, $prefArray_root['dlpath']);
    }
    if (!isset($pidpath_msg)) {
        $pidpath_msg = verify_path($db, $prefArray_root['pidpath']);
    }
    if (!isset($keystore_path_msg)) {
        $keystore_path_msg = verify_path($db, $prefArray_root['keystore_path']);
    }
    if (!isset($maxdl_msg)) {
        $maxdl_msg = verify_numeric($prefArray_root['maxdl'], 0);
    }
    if (!isset($maxul_msg)) {
        $maxul_msg = verify_numeric($prefArray_root['maxul'], 0);
    }
    if (!isset($default_expire_time_msg)) {
        $default_expire_time_msg = verify_numeric($prefArray_root['default_expire_time'], 1);
    }
    if (!isset($spots_expire_time_msg)) {
        $spots_expire_time_msg = verify_numeric($prefArray_root['spots_expire_time'], 0);
    }
    if (!isset($expire_incomplete_msg)) {
        $expire_incomplete_msg = verify_numeric($prefArray_root['expire_incomplete'], 0);
    }
    if (!isset($expire_percentage_msg)) {
        $expire_percentage_msg = verify_numeric($prefArray_root['expire_percentage'], 0, 100);
    }
    if (!isset($default_language_msg)) {
        $default_language_msg = verify_array($prefArray_root['default_language'], array_keys($languages));
    }
    if (!isset($auto_login_msg)) {
        $auto_login_msg = verify_array($prefArray_root['auto_login'], array_keys($users));
    }
    if (!isset($default_template_msg)) {
        $default_template_msg = verify_array($prefArray_root['default_template'], array_keys($templates));
    }
    if (!isset($mail_account_activated_msg)) {
        $mail_account_activated_msg= verify_array($prefArray_root['mail_account_activated'], array_keys($mail_templates));
    }
    if (!isset($mail_account_disabled_msg)) {
        $mail_account_disabled_msg= verify_array($prefArray_root['mail_account_disabled'], array_keys($mail_templates));
    }
    if (!isset($mail_activate_account_msg)) {
        $mail_activate_account_msg= verify_array($prefArray_root['mail_activate_account'], array_keys($mail_templates));
    }
    if (!isset($mail_download_status_msg)) {
        $mail_download_status_msg= verify_array($prefArray_root['mail_download_status'], array_keys($mail_templates));
    }
    if (!isset($mail_new_interesting_sets_msg)) {
        $mail_new_interesting_sets_msg= verify_array($prefArray_root['mail_new_interesting_sets'], array_keys($mail_templates));
    }
    if (!isset($mail_new_preferences_msg)) {
        $mail_new_preferences_msg= verify_array($prefArray_root['mail_new_preferences'], array_keys($mail_templates));
    }
    if (!isset($mail_new_user_msg)) {
        $mail_new_user_msg= verify_array($prefArray_root['mail_new_user'], array_keys($mail_templates));
    }
    if (!isset($mail_password_reset_msg)) {
        $mail_password_reset_msg= verify_array($prefArray_root['mail_password_reset'], array_keys($mail_templates));
    }
    if (!isset($default_stylesheet_msg)) {
        $default_stylesheet_msg = verify_array($prefArray_root['default_stylesheet'], array_keys($stylesheets));
    }
    if (!isset($spots_max_categories_msg)) {
        $spots_max_categories_msg = verify_numeric($prefArray_root['spots_max_categories'], 0);
    }

    if (!isset($maxbuttons_msg)) {
        $maxbuttons_msg = verify_numeric($prefArray_root['maxbuttons'], 0);
    }
    if (!isset($update_msg)) {
        $update_msg = verify_array($prefArray_root['period_update'], $periods->get_period_keys());
    }
    $getspots_blacklist_msg = '';
    if ($prefArray_root['period_getspots_blacklist'] > 0) {
        if (!isset($getspots_msg)) {
            $getspots_blacklist_msg = verify_numeric($prefArray_root['time1_getspots_blacklist'], 0, 23);
            $getspots_blacklist_msg .= verify_numeric($prefArray_root['time2_getspots_blacklist'], 0, 59);
        }
    }
    $getspots_whitelist_msg = '';
    if ($prefArray_root['period_getspots_whitelist'] > 0) {
        if (!isset($getspots_msg)) {
            $getspots_whitelist_msg = verify_numeric($prefArray_root['time1_getspots_whitelist'], 0, 23);
            $getspots_whitelist_msg .= verify_numeric($prefArray_root['time2_getspots_whitelist'], 0, 59);
        }
    }

    $expirespots_msg = '';
    if ($prefArray_root['period_expirespots'] > 0) {
        if (!isset($expirespots_msg)) {
            $expirespots_msg = verify_numeric($prefArray_root['time1_expirespots'], 0, 23);
            $expirespots_msg .= verify_numeric($prefArray_root['time2_expirespots'], 0, 59);
        }
    }

    $getspots_msg = '';
    if ($prefArray_root['period_getspots'] > 0) {
        if (!isset($getspots_msg)) {
            $getspots_msg = verify_numeric($prefArray_root['time1_getspots'], 0, 23);
            $getspots_msg .= verify_numeric($prefArray_root['time2_getspots'], 0, 59);
        }
    }
    $update_msg = '';
    if ($prefArray_root['period_update'] > 0) {
        if (!isset($update_msg)) {
            $update_msg = verify_numeric($prefArray_root['time1_update'], 0, 23);
            $update_msg .= verify_numeric($prefArray_root['time2_update'], 0, 59);
        }
    }
    $sendinfo_msg = '';
    if ($prefArray_root['period_sendinfo'] > 0) {
        if (!isset($sendinfo_msg)) {
            $sendinfo_msg = verify_numeric($prefArray_root['time1_sendinfo'], 0, 23);
            $sendinfo_msg .= verify_numeric($prefArray_root['time2_sendinfo'], 0, 59);
        }
    }
    $getinfo_msg = '';
    if ($prefArray_root['period_getinfo'] > 0) {
        if (!isset($getinfo_msg)) {
            $getinfo_msg = verify_numeric($prefArray_root['time1_getinfo'], 0, 23);
            $getinfo_msg .= verify_numeric($prefArray_root['time2_getinfo'], 0, 59);
        }
    }

    if (!isset($nglist_msg)) {
        $nglist_msg = verify_array($prefArray_root['period_ng'], $periods->get_period_keys());
    }
    if ($prefArray_root['period_ng'] > 0) {
        if (!isset($nglist_msg)) {
            $nglist_msg .= verify_numeric($prefArray_root['time1_ng'], 0, 23);
            $nglist_msg .= verify_numeric($prefArray_root['time2_ng'], 0, 59);
        }
    } else {
        $nglist_msg = '';
    }
    if (!isset($cleandb_msg)) {
        $cleandb_msg = verify_array($prefArray_root['period_cdb'], $periods->get_period_keys());
    }
    if ($prefArray_root['period_cdb'] > 0) {
        if (!isset($cleandb_msg)) {
            $cleandb_msg = verify_numeric($prefArray_root['time1_cdb'], 0, 23);
            $cleandb_msg .= verify_numeric($prefArray_root['time2_cdb'], 0, 59);
        }
    } else {
        $cleandb_msg = '';
    }
    if (!isset($cleanusers_msg)) {
        $cleanusers_msg = verify_array($prefArray_root['period_cu'], $periods->get_period_keys());
    }
    if ($prefArray_root['period_cu'] > 0) {
        if (!isset($cleanusers_msg)) {
            $cleanusers_msg = verify_numeric($prefArray_root['time1_cu'], 0, 23);
            $cleanusers_msg .= verify_numeric($prefArray_root['time2_cu'], 0, 59);
        }
    } else {
        $cleanusers_msg = '';
    }

    if (!isset($cleandir_msg)) {
        $cleandir_msg = verify_array($prefArray_root['period_cd'], $periods->get_period_keys());
    }
    if ($prefArray_root['period_cd'] > 0) {
        if (!isset($cleandir_msg)) {
            $cleandir_msg = verify_numeric($prefArray_root['time1_cd'], 0, 23);
            $cleandir_msg .= verify_numeric($prefArray_root['time2_cd'], 0, 59);
            $cleandir_msg .= verify_array($prefArray_root['dir_cd'], 0, array_keys($cleandir_dirs));
        }
    } else {
        $cleandir_msg = '';
    }
    if (!isset($optimise_msg)) {
        $optimise_msg = verify_array($prefArray_root['period_opt'], $periods->get_period_keys());
    }
    if ($prefArray_root['period_opt'] > 0) {
        if (!isset($optimise_msg)) {
            $optimise_msg = verify_numeric($prefArray_root['time1_opt'], 0, 23);
            $optimise_msg .= verify_numeric($prefArray_root['time2_opt'], 0, 59);
        }
    } else {
        $optimise_msg = '';
    }

    if (!isset($urdd_pars_msg)) {
        $urdd_pars_msg = verify_text_opt('urdd_pars', TRUE, NULL);
    }
    if (!isset($unpar_pars_msg)) {
        $unpar_pars_msg = verify_text_opt('unpar_pars', TRUE, NULL);
    }
    if (!isset($unrar_pars_msg)) {
        $unrar_pars_msg = verify_text_opt('unrar_pars', TRUE, NULL);
    }
    if (!isset($rar_pars_msg)) {
        $rar_pars_msg = verify_text_opt('rar_pars', TRUE, NULL);
    }
    if (!isset($unace_pars_msg)) {
        $unace_pars_msg = verify_text_opt('unace_pars', TRUE, NULL);
    }
    if (!isset($un7zr_pars_msg)) {
        $un7zr_pars_msg = verify_text_opt('un7zr_pars', TRUE, NULL);
    }
    if (!isset($unzip_pars_msg)) {
        $unzip_pars_msg = verify_text_opt('unzip_pars', TRUE, NULL);
    }
    if (!isset($gzip_pars_msg)) {
        $gzip_pars_msg = verify_text_opt('gzip_pars', TRUE, NULL);
    }
    if (!isset($unarj_pars_msg)) {
        $unarj_pars_msg = verify_text_opt('unarj_pars', TRUE, NULL);
    }
    if (!isset($subdownloader_pars_msg)) {
        $subdownloader_pars_msg = verify_text_opt('subdownloader_pars', TRUE, NULL);
    }
    if (!isset($yydecode_pars_msg)) {
        $yydecode_pars_msg = verify_text_opt('yydecode_pars', TRUE, NULL);
    }
    if (!isset($yyencode_pars_msg)) {
        $yyencode_pars_msg = verify_text_opt('yyencode_pars', TRUE, NULL);
    }

    $poster_blacklist = unserialize($prefArray_root['poster_blacklist']);
    if ($poster_blacklist === FALSE) { 
        $poster_blacklist = $prefArray_root['poster_blacklist'];
    }
    $hidden_files_list = unserialize($prefArray_root['global_hidden_files_list']);
    if ($hidden_files_list === FALSE) { 
        $hidden_files_list = $prefArray_root['global_hidden_files_list'];
    }
    $module_config = urd_modules::get_urd_module_config($prefArray_root['modules']);

    $urdd_cfg = array (
            new pref_numeric_noformat(user_levels::CONFIG_LEVEL_BASIC, $LN['config_urdd_maxthreads'], 'urdd_maxthreads', $LN['config_urdd_maxthreads_msg'],
                $urdd_maxthreads_msg, $prefArray_root['urdd_maxthreads']),
            new pref_numeric_noformat(user_levels::CONFIG_LEVEL_BASIC, $LN['config_nntp_maxthreads'], 'nntp_maxthreads', $LN['config_nntp_maxthreads_msg'],
                $nntp_maxthreads_msg, $prefArray_root['nntp_maxthreads']),
            new pref_numeric_noformat(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_nntp_maxdlthreads'], 'nntp_maxdlthreads', $LN['config_nntp_maxdlthreads_msg'],
                $nntp_maxdlthreads_msg, $prefArray_root['nntp_maxdlthreads']),
            new pref_numeric_noformat(user_levels::CONFIG_LEVEL_MASTER, $LN['config_db_intensive_maxthreads'], 'db_intensive_maxthreads', $LN['config_db_intensive_maxthreads_msg'],
                $db_intensive_maxthreads_msg, $prefArray_root['db_intensive_maxthreads']),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_nntp_all_servers'], 'nntp_all_servers', $LN['config_nntp_all_servers_msg'],
                $nntp_all_servers_msg, $prefArray_root['nntp_all_servers']),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_check_nntp_connections'], 'check_nntp_connections', $LN['config_check_nntp_connections_msg'],
                $check_nntp_connections_msg, $prefArray_root['check_nntp_connections']),
            new pref_text(user_levels::CONFIG_LEVEL_BASIC, $LN['config_urdd_host'], 'urdd_host', $LN['config_urdd_host_msg'], $urdd_host_msg, $prefArray_root['urdd_host']),
            new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_urdd_port'], 'urdd_port', $LN['config_urdd_port_msg'], $urdd_port_msg, $prefArray_root['urdd_port']),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_urdd_restart'], 'urdd_restart', $LN['config_urdd_restart_msg'], $urdd_restart_msg, $prefArray_root['urdd_restart']),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_urdd_daemonise'], 'urdd_daemonise', $LN['config_urdd_daemonise_msg'], $urdd_daemonise_msg, $prefArray_root['urdd_daemonise']),
            new pref_select(user_levels::CONFIG_LEVEL_MASTER, $LN['config_scheduler'], 'scheduler', $LN['config_scheduler_msg'], $scheduler_msg, $on_off, $prefArray_root['scheduler']),
            new pref_numeric_noformat(user_levels::CONFIG_LEVEL_MASTER, $LN['config_nice_value'], 'nice_value', $LN['config_nice_value_msg'], $nice_value_msg, $prefArray_root['nice_value']),
            new pref_numeric_noformat(user_levels::CONFIG_LEVEL_MASTER, $LN['config_queue_size'], 'queue_size', $LN['config_queue_size_msg'], $queue_size_msg, $prefArray_root['queue_size']),
            new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_pidpath'], 'pidpath', $LN['config_pidpath_msg'], $pidpath_msg, $prefArray_root['pidpath']),
            new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_urdd_uid'], 'urdd_uid', $LN['config_urdd_uid_msg'], $urdd_uid_msg, $prefArray_root['urdd_uid']),
            new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_urdd_gid'], 'urdd_gid', $LN['config_urdd_gid_msg'], $urdd_gid_msg, $prefArray_root['urdd_gid'])
                );

    $networking = array (
            new pref_email(user_levels::CONFIG_LEVEL_BASIC, $LN['config_admin_email'], 'admin_email', $LN['config_admin_email_msg'], $admin_email_msg, $prefArray_root['admin_email']),
            new pref_text(user_levels::CONFIG_LEVEL_BASIC, $LN['config_baseurl'], 'baseurl', $LN['config_baseurl_msg'], $url_msg, $prefArray_root['baseurl']),
            new pref_numeric_noformat(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_socket_timeout'], 'socket_timeout', $LN['config_socket_timeout_msg'],
                $socket_timeout_msg, $prefArray_root['socket_timeout']),
            new pref_numeric_noformat(user_levels::CONFIG_LEVEL_MASTER, $LN['config_urdd_connection_timeout'], 'urdd_connection_timeout', $LN['config_urdd_connection_timeout_msg'], $urdd_connection_timeout_msg, $prefArray_root['urdd_connection_timeout']),
            new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_connection_timeout'], 'connection_timeout', $LN['config_connection_timeout_msg'],
            $connection_timeout_msg, $prefArray_root['connection_timeout']),
            new pref_checkbox(user_levels::CONFIG_LEVEL_MASTER, $LN['config_shaping'], 'shaping', $LN['config_shaping_msg'],
                $shaping_msg, $prefArray_root['shaping'], "$('#shaping1').toggleClass('hidden'); $('#shaping2').toggleClass('hidden')"),
            new pref_numeric(user_levels::CONFIG_LEVEL_MASTER, $LN['config_maxdl'], 'maxdl', $LN['config_maxdl_msg'],
                $maxdl_msg, $prefArray_root['maxdl'], NULL, 'shaping1', $prefArray_root['shaping'] ? NULL : 'hidden'),
            new pref_numeric(user_levels::CONFIG_LEVEL_MASTER, $LN['config_maxul'], 'maxul', $LN['config_maxul_msg'],
                $maxul_msg, $prefArray_root['maxul'], NULL, 'shaping2', $prefArray_root['shaping'] ? NULL : 'hidden')
            );

    $download_settsings = array();
    $download_settings[] = new pref_text(user_levels::CONFIG_LEVEL_BASIC, $LN['config_dlpath'], 'dlpath', $LN['config_dlpath_msg'], $dlpath_msg, $prefArray_root['dlpath']);
    $download_settings[] = new pref_select(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_permissions'], 'permissions', $LN['config_permissions_msg'],
            $permissions_msg, $permissions, $prefArray_root['permissions']);
    if ($module_config[urd_modules::URD_CLASS_DOWNLOAD] || $module_config[urd_modules::URD_CLASS_MAKENZB] || $module_config[urd_modules::URD_CLASS_USENZB]) {
        $download_settings[] = new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_replacement_str'], 'replacement_str', $LN['config_replacement_str_msg'],
                $replacement_str_msg, $prefArray_root['replacement_str']);
    }
    $download_settings[] = new pref_numeric_noformat(user_levels::CONFIG_LEVEL_MASTER, $LN['config_max_dl_name'], 'max_dl_name', $LN['config_max_dl_name_msg'],
                $max_dl_name_msg, $prefArray_root['max_dl_name']);
    if ($module_config[urd_modules::URD_CLASS_DOWNLOAD]) {
        $download_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_allow_global_scripts'], 'allow_global_scripts', $LN['config_allow_global_scripts_msg'],
                $global_scripts_msg, $prefArray_root['allow_global_scripts'], "$('#hide_userscripts').toggleClass('hidden')");
        $download_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_allow_user_scripts'], 'allow_user_scripts', $LN['config_allow_user_scripts_msg'],
                $user_scripts_msg, $prefArray_root['allow_user_scripts'], NULL, 'hide_userscripts', $prefArray_root['allow_global_scripts'] == 1 ? NULL : 'hidden');
        $download_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_auto_download'], 'auto_download', $LN['config_auto_download_msg'],
                $auto_download_msg, $prefArray_root['auto_download']);
    }

    $ext_progs = array (
        new pref_text(user_levels::CONFIG_LEVEL_BASIC, $LN['config_urdd_path'], 'urdd_path', $LN['config_urdd_path_msg'], $urdd_path_msg, $prefArray_root['urdd_path']),
        new pref_text(user_levels::CONFIG_LEVEL_BASIC, $LN['config_yydecode_path'], 'yydecode_path', $LN['config_yydecode_path_msg'],
            $yydecode_path_msg, $prefArray_root['yydecode_path']),
        new pref_text(user_levels::CONFIG_LEVEL_BASIC, $LN['config_yyencode_path'], 'yyencode_path', $LN['config_yyencode_path_msg'],
            $yyencode_path_msg, $prefArray_root['yyencode_path']),
        new pref_text(user_levels::CONFIG_LEVEL_BASIC, $LN['config_unpar_path'], 'unpar_path', $LN['config_unpar_path_msg'], $unpar_path_msg, $prefArray_root['unpar_path']),
        new pref_text(user_levels::CONFIG_LEVEL_BASIC, $LN['config_unrar_path'], 'unrar_path', $LN['config_unrar_path_msg'], $unrar_path_msg, $prefArray_root['unrar_path']),
        new pref_text(user_levels::CONFIG_LEVEL_BASIC, $LN['config_rar_path'], 'rar_path', $LN['config_rar_path_msg'], $rar_path_msg, $prefArray_root['rar_path']),
        new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_unace_path'], 'unace_path', $LN['config_unace_path_msg'], $unace_path_msg, $prefArray_root['unace_path']),
        new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_un7zr_path'], 'un7zr_path', $LN['config_un7zr_path_msg'], $un7zr_path_msg, $prefArray_root['un7zr_path']),
        new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_unzip_path'], 'unzip_path', $LN['config_unzip_path_msg'], $unzip_path_msg, $prefArray_root['unzip_path']),
        new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_gzip_path'], 'gzip_path', $LN['config_gzip_path_msg'], $gzip_path_msg, $prefArray_root['gzip_path']),
        new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_unarj_path'], 'unarj_path', $LN['config_unarj_path_msg'], $unarj_path_msg, $prefArray_root['unarj_path']),
        new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_file_path'], 'file_path', $LN['config_file_path_msg'], $file_path_msg, $prefArray_root['file_path']),
        new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_tar_path'], 'tar_path', $LN['config_tar_path_msg'], $tar_path_msg, $prefArray_root['tar_path']),
        new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_cksfv_path'], 'cksfv_path', $LN['config_cksfv_path_msg'], $cksfv_path_msg, $prefArray_root['cksfv_path']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_trickle_path'], 'trickle_path', $LN['config_trickle_path_msg'],
            $trickle_path_msg, $prefArray_root['trickle_path']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_subdownloader_path'], 'subdownloader_path', $LN['config_subdownloader_path_msg'],
            $subdownloader_path_msg, $prefArray_root['subdownloader_path'])
    );

    $prog_params = array(
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_urdd_pars'], 'urdd_pars', $LN['config_urdd_pars_msg'],
            $urdd_pars_msg, $prefArray_root['urdd_pars']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_yydecode_pars'], 'yydecode_pars', $LN['config_yydecode_pars_msg'],
            $yydecode_pars_msg, $prefArray_root['yydecode_pars']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_yyencode_pars'], 'yyencode_pars', $LN['config_yyencode_pars_msg'],
            $yyencode_pars_msg, $prefArray_root['yyencode_pars']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_unpar_pars'], 'unpar_pars', $LN['config_unpar_pars_msg'], $unpar_pars_msg, $prefArray_root['unpar_pars']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_unrar_pars'], 'unrar_pars', $LN['config_unrar_pars_msg'], $unrar_pars_msg, $prefArray_root['unrar_pars']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_rar_pars'], 'rar_pars', $LN['config_rar_pars_msg'], $rar_pars_msg, $prefArray_root['rar_pars']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_unace_pars'], 'unace_pars', $LN['config_unace_pars_msg'], $unace_pars_msg, $prefArray_root['unace_pars']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_un7zr_pars'], 'un7zr_pars', $LN['config_un7zr_pars_msg'], $un7zr_pars_msg, $prefArray_root['un7zr_pars']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_unzip_pars'], 'unzip_pars', $LN['config_unzip_pars_msg'], $unzip_pars_msg, $prefArray_root['unzip_pars']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_gzip_pars'], 'gzip_pars', $LN['config_gzip_pars_msg'], $gzip_pars_msg, $prefArray_root['gzip_pars']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_unarj_pars'], 'unarj_pars', $LN['config_unarj_pars_msg'], $unarj_pars_msg, $prefArray_root['unarj_pars']),
        new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_subdownloader_pars'], 'subdownloader_pars', $LN['config_subdownloader_pars_msg'],
            $unarj_pars_msg, $prefArray_root['subdownloader_pars'])
    );

    // we show this one if URDD is offline
    $maintenance_offline = array(
        new pref_plain(user_levels::CONFIG_LEVEL_BASIC, $LN['urdddisabled'], $LN['urdddisabled'], $LN['enableurddfirst'], NULL, NULL)
    );

    // Maintenance  options
    $maintenance[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_update'], $LN['config_period_update_msg'],
            $update_msg, 'period_update', $prefArray_root['period_update'], 'time1_update', $prefArray_root['time1_update'], 'time2_update', $prefArray_root['time2_update']);

    $maintenance[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_opt'], $LN['config_period_opt_msg'],
            $optimise_msg, 'period_opt', $prefArray_root['period_opt'], 'time1_opt', $prefArray_root['time1_opt'], 'time2_opt', $prefArray_root['time2_opt']);
    if ($module_config[urd_modules::URD_CLASS_GROUPS]) {
        $maintenance[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_ng'], $LN['config_period_ng_msg'],
                $nglist_msg, 'period_ng', $prefArray_root['period_ng'], 'time1_ng', $prefArray_root['time1_ng'], 'time2_ng', $prefArray_root['time2_ng']);
    }
    $maintenance[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_cu'], $LN['config_period_cu_msg'],
            $cleanusers_msg, 'period_cu', $prefArray_root['period_cu'], 'time1_cu', $prefArray_root['time1_cu'], 'time2_cu', $prefArray_root['time2_cu']);
    $maintenance[] = new pref_numeric(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_users_clean_age'], 'users_clean_age', $LN['config_users_clean_age_msg'],
            $users_clean_age_msg, $prefArray_root['users_clean_age']);
    $maintenance[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_cd'], $LN['config_period_cd_msg'],
            $cleandir_msg, 'period_cd', $prefArray_root['period_cd'], 'time1_cd', $prefArray_root['time1_cd'], 'time2_cd', $prefArray_root['time2_cd'], 'dir_cd', $cleandir_dirs, $prefArray_root['dir_cd']);
    $maintenance[] = new pref_numeric(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_clean_dir_age'], 'clean_dir_age', $LN['config_clean_dir_age_msg'],
            $clean_dir_age_msg, $prefArray_root['clean_dir_age']);
    $maintenance[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_cdb'], $LN['config_period_cdb_msg'], $cleandb_msg, 'period_cdb',
            $prefArray_root['period_cdb'], 'time1_cdb', $prefArray_root['time1_cdb'], 'time2_cdb', $prefArray_root['time2_cdb']);
    $maintenance[] = new pref_numeric(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_clean_db_age'], 'clean_db_age', $LN['config_clean_db_age_msg'],
            $clean_db_age_msg, $prefArray_root['clean_db_age']);

    if ($module_config[urd_modules::URD_CLASS_SYNC]) {
        $maintenance[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_sendinfo'], $LN['config_period_sendinfo_msg'],
            $sendinfo_msg, 'period_sendinfo', $prefArray_root['period_sendinfo'], 'time1_sendinfo', $prefArray_root['time1_sendinfo'], 'time2_sendinfo', $prefArray_root['time2_sendinfo']);
        $maintenance[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_getinfo'], $LN['config_period_getinfo_msg'],
            $getinfo_msg, 'period_getinfo', $prefArray_root['period_getinfo'], 'time1_getinfo', $prefArray_root['time1_getinfo'], 'time2_getinfo', $prefArray_root['time2_getinfo']);
    }

    // Global settings
    $global_settings[] = new pref_select(user_levels::CONFIG_LEVEL_ALWAYS, $LN['pref_level'], 'pref_level', $LN['pref_level_msg'],
            '', $levels, $pref_level, 'load_prefs();');
    $global_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_default_language'], 'default_language', $LN['config_default_language_msg'],
            $default_language_msg, $languages, $prefArray_root['default_language']);

    $global_settings[] = new pref_select(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_default_template'], 'default_template', $LN['config_default_template_msg'],
            $default_template_msg, $templates, $prefArray_root['default_template']);
    $global_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_default_stylesheet'], 'default_stylesheet', $LN['config_default_stylesheet_msg'],
            $default_stylesheet_msg, $stylesheets, $prefArray_root['default_stylesheet']);
    $global_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_log_level'], 'log_level', $LN['config_log_level_msg'],
            $log_level_msg, $log_levels, $prefArray_root['log_level']);
    $global_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_index_page_root'], 'index_page_root', $LN['config_index_page_root_msg'],
            $index_page_msg, $index_page_array, $prefArray_root['index_page_root']);

    $global_settings[] = new pref_select(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_group'], 'group', $LN['config_group_msg'],
            $group_msg, $groups, $prefArray_root['group']);
    $global_settings[] = new pref_numeric_noformat(user_levels::CONFIG_LEVEL_MASTER, $LN['config_maxbuttons'], 'maxbuttons', $LN['config_maxbuttons_msg'],
            $maxbuttons_msg, $prefArray_root['maxbuttons']);

    $notify_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_BASIC, $LN['config_sendmail'], 'sendmail', $LN['config_sendmail_msg'],
            $sendmail_msg, $prefArray_root['sendmail'], '$(\'#hide_maa\').toggleClass(\'hidden\');$(\'#hide_macta\').toggleClass(\'hidden\');$(\'#hide_mad\').toggleClass(\'hidden\');$(\'#hide_mds\').toggleClass(\'hidden\');$(\'#hide_mnis\').toggleClass(\'hidden\');$(\'#hide_mnp\').toggleClass(\'hidden\');$(\'#hide_mnu\').toggleClass(\'hidden\');$(\'#hide_mpr\').toggleClass(\'hidden\');');

    $notify_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_mail_account_activated'], 'mail_account_activated', $LN['config_mail_account_activated_msg'],
            $mail_account_activated_msg, $mail_templates, $prefArray_root['mail_account_activated'], NULL, 'hide_maa', $prefArray_root['sendmail'] == 1 ? NULL:'hidden');
    $notify_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_mail_activate_account'], 'mail_activate_account', $LN['config_mail_activate_account_msg'],
            $mail_activate_account_msg, $mail_templates, $prefArray_root['mail_activate_account'], NULL, 'hide_macta', $prefArray_root['sendmail'] == 1 ? NULL:'hidden');
    $notify_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_mail_account_disabled'], 'mail_account_disabled', $LN['config_mail_account_disabled_msg'],
            $mail_account_disabled_msg, $mail_templates, $prefArray_root['mail_account_disabled'], NULL, 'hide_mad', $prefArray_root['sendmail'] == 1 ? NULL:'hidden');
    $notify_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_mail_download_status'], 'mail_download_status', $LN['config_mail_download_status_msg'],
            $mail_download_status_msg, $mail_templates, $prefArray_root['mail_download_status'], NULL, 'hide_mds', $prefArray_root['sendmail'] == 1 ? NULL:'hidden');
    $notify_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_mail_new_interesting_sets'], 'mail_new_interesting_sets', $LN['config_mail_new_interesting_sets_msg'],
            $mail_new_interesting_sets_msg, $mail_templates, $prefArray_root['mail_new_interesting_sets'], NULL, 'hide_mnis', $prefArray_root['sendmail'] == 1 ? NULL:'hidden');
    $notify_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_mail_new_preferences'], 'mail_new_preferences', $LN['config_mail_new_preferences_msg'],
            $mail_new_preferences_msg, $mail_templates, $prefArray_root['mail_new_preferences'], NULL, 'hide_mnp',  $prefArray_root['sendmail'] == 1 ? NULL:'hidden');
    $notify_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_mail_new_user'], 'mail_new_user', $LN['config_mail_new_user_msg'],
            $mail_new_user_msg, $mail_templates, $prefArray_root['mail_new_user'], NULL, 'hide_mnu',  $prefArray_root['sendmail'] == 1 ? NULL:'hidden');
    $notify_settings[] = new pref_select(user_levels::CONFIG_LEVEL_BASIC, $LN['config_mail_password_reset'], 'mail_password_reset', $LN['config_mail_password_reset_msg'],
            $mail_password_reset_msg, $mail_templates, $prefArray_root['mail_password_reset'], NULL, 'hide_mpr',  $prefArray_root['sendmail'] == 1 ? NULL:'hidden');

    $global_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_MASTER, $LN['config_parse_nfo'], 'parse_nfo', $LN['config_parse_nfo_msg'],
            $parse_nfo_msg, $prefArray_root['parse_nfo']);
    $global_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_MASTER, $LN['config_allow_robots'], 'allow_robots', $LN['config_allow_robots_msg'], $allow_robots_msg, $prefArray_root['allow_robots']);
    $global_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_MASTER, $LN['config_clickjack'], 'clickjack', $LN['config_clickjack_msg'],
            $clickjack_msg, $prefArray_root['clickjack']);
    $global_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_MASTER, $LN['config_need_challenge'], 'need_challenge', $LN['config_need_challenge_msg'],
            $need_challenge_msg, $prefArray_root['need_challenge']);
    $global_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_use_encrypted_passwords'], 'use_encrypted_passwords', $LN['config_use_encrypted_passwords_msg'],
            $use_encrypted_passwords_msg, $prefArray_root['use_encrypted_passwords']);
    $global_settings[] = new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_keystore_path'], 'keystore_path', $LN['config_keystore_path_msg'],
            $keystore_path_msg, $prefArray_root['keystore_path']);
    $global_settings[] = new pref_numeric_noformat(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_max_login_count'], 'max_login_count', $LN['config_max_login_count_msg'],
            $max_login_count_msg, $prefArray_root['max_login_count']);
    $global_settings[] = new pref_select(user_levels::CONFIG_LEVEL_MASTER, $LN['config_auto_login'], 'auto_login', $LN['config_auto_login_msg'], $auto_login_msg, $users, $prefArray_root['auto_login']);

    if ($module_config[urd_modules::URD_CLASS_VIEWFILES]) {
        $global_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_BASIC, $LN['config_webdownload'], 'webdownload', $LN['config_webdownload_msg'],
                $webdownload_msg, $prefArray_root['webdownload']);
        $global_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_webeditfile'], 'webeditfile', $LN['config_webeditfile_msg'],
                $webeditfile_msg, $prefArray_root['webeditfile']);
        $global_settings[] = new pref_numeric(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_maxfilesize'], 'maxfilesize', $LN['config_maxfilesize_msg'],
                $maxfilesize_msg, $prefArray_root['maxfilesize']);
        $global_settings[] = new pref_numeric(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_maxpreviewsize'], 'maxpreviewsize', $LN['config_maxpreviewsize_msg'],
                $maxpreviewsize_msg, $prefArray_root['maxpreviewsize']);
        $global_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_global_hiddenfiles'], 'global_hiddenfiles', $LN['config_global_hiddenfiles_msg'],
                $hiddenfiles_msg, $prefArray_root['global_hiddenfiles'], "$('#hidfil').toggleClass('hidden')");
        $global_settings[] = new pref_textarea(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_global_hidden_files_list'], 'global_hidden_files_list', $LN['config_global_hidden_files_list_msg'],
                $hidden_files_list_msg, $hidden_files_list, 10, 40, NULL, 'hidfil', $prefArray_root['global_hiddenfiles'] ? NULL : 'hidden');
        $global_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_compress_nzb'], 'compress_nzb', $LN['config_compress_nzb_msg'],
                $compress_nzb_msg, $prefArray_root['compress_nzb']);
    }

    $global_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_register'], 'register', $LN['config_register_msg'],
            $register_msg, $prefArray_root['register'], "$('#auto_reg_tr').toggleClass('hidden');");
    $global_settings[] =  new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_auto_reg'], 'auto_reg', $LN['config_auto_reg_msg'],
            $auto_reg_msg, $prefArray_root['auto_reg'], NULL, 'auto_reg_tr', $prefArray_root['register'] ? '' : 'hidden');

    $spots_settings = array();
    if ($module_config[urd_modules::URD_CLASS_SPOTS]) {
        $spots_settings[] = new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_spots_group'], 'spots_group', $LN['config_spots_group_msg'],
                $spots_group_msg, $prefArray_root['spots_group']);
        $spots_settings[] = new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_spots_reports_group'], 'spots_reports_group', $LN['config_spots_reports_group_msg'],
                $spots_reports_group_msg, $prefArray_root['spots_reports_group']);
        $spots_settings[] = new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_spots_comments_group'], 'spots_comments_group', $LN['config_spots_comments_group_msg'],
                $spots_comments_group_msg, $prefArray_root['spots_comments_group']);
        $spots_settings[] = new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_ftd_group'], 'ftd_group', $LN['config_ftd_group_msg'], $ftd_group_msg, $prefArray_root['ftd_group']);
        $spots_settings[] = new pref_text(user_levels::CONFIG_LEVEL_BASIC, $LN['config_spots_blacklist'], 'spots_blacklist', $LN['config_spots_blacklist_msg'],
                $spots_blacklist_msg, $prefArray_root['spots_blacklist']);
        $spots_settings[] = new pref_text(user_levels::CONFIG_LEVEL_BASIC, $LN['config_spots_whitelist'], 'spots_whitelist', $LN['config_spots_whitelist_msg'],
                $spots_whitelist_msg, $prefArray_root['spots_whitelist']);
        $spots_settings[] = new pref_numeric(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_spots_expire_time'], 'spots_expire_time', $LN['config_spots_expire_time_msg'],
                $spots_expire_time_msg, $prefArray_root['spots_expire_time']);
        $spots_settings[] = new pref_numeric(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_spot_expire_spam_count'], 'spot_expire_spam_count', $LN['config_spot_expire_spam_count_msg'],
                $spot_expire_spam_count_msg, $prefArray_root['spot_expire_spam_count']);
        $spots_settings[] = new pref_numeric(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_spots_max_categories'], 'spots_max_categories', $LN['config_spots_max_categories_msg'],
                $spots_max_categories_msg, $prefArray_root['spots_max_categories']);
        $spots_settings[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_getspots'], $LN['config_period_getspots_msg'],
                $getspots_msg, 'period_getspots', $prefArray_root['period_getspots'], 'time1_getspots', $prefArray_root['time1_getspots'], 'time2_getspots', $prefArray_root['time2_getspots']);
        $spots_settings[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_expirespots'], $LN['config_period_expirespots_msg'],
                $getspots_msg, 'period_expirespots', $prefArray_root['period_expirespots'], 'time1_expirespots', $prefArray_root['time1_expirespots'], 'time2_expirespots', $prefArray_root['time2_expirespots']);
        $spots_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_BASIC, $LN['config_download_spots_reports'], 'download_spots_reports', $LN['config_download_spots_reports_msg'],
                $download_spots_reports_msg, $prefArray_root['download_spots_reports']);
        $spots_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_BASIC, $LN['config_download_spots_images'], 'download_spots_images', $LN['config_download_spots_images_msg'],
                $download_spots_images_msg, $prefArray_root['download_spots_images']);
        $spots_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_download_spots_comments'], 'download_spots_comments', $LN['config_download_spots_comments_msg'],
                $download_spots_comments_msg, $prefArray_root['download_spots_comments'], '$(\'#hide_cmt_avt\').toggleClass(\'hidden\');');
        $spots_settings[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_download_comment_avatar'], 'download_comment_avatar', $LN['config_download_comment_avatar_msg'],
                $config_download_comment_avatar_msg, $prefArray_root['download_comment_avatar'], NULL, 'hide_cmt_avt', $prefArray_root['download_spots_comments'] == 1 ? NULL : 'hidden');
        $spots_settings[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_getspots_blacklist'], $LN['config_period_getspots_blacklist_msg'],
                $getspots_blacklist_msg, 'period_getspots_blacklist', $prefArray_root['period_getspots_blacklist'], 'time1_getspots_blacklist',
                $prefArray_root['time1_getspots_blacklist'], 'time2_getspots_blacklist', $prefArray_root['time2_getspots_blacklist']);
        $spots_settings[] = new pref_period(user_levels::CONFIG_LEVEL_BASIC, $LN['config_period_getspots_whitelist'], $LN['config_period_getspots_whitelist_msg'],
                $getspots_whitelist_msg, 'period_getspots_whitelist', $prefArray_root['period_getspots_whitelist'], 'time1_getspots_whitelist',
                $prefArray_root['time1_getspots_whitelist'], 'time2_getspots_whitelist', $prefArray_root['time2_getspots_whitelist']);
    }

    $set_updating= array();
    if ($module_config[urd_modules::URD_CLASS_GROUPS]) {
        $set_updating[] = new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_group_filter'], 'group_filter', $LN['config_group_filter_msg'],
                $group_filter_msg, $prefArray_root['group_filter']);
    }
    $set_updating[] = new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_extset_group'], 'extset_group', $LN['config_extset_group_msg'],
            $extset_group_msg, $prefArray_root['extset_group']);

    $set_updating[] = new pref_checkbox(user_levels::CONFIG_LEVEL_BASIC, $LN['config_auto_expire'], 'auto_expire', $LN['config_auto_expire_msg'], $auto_expire_msg, $prefArray_root['auto_expire']);
    if ($module_config[urd_modules::URD_CLASS_GROUPS] || $module_config[urd_modules::URD_CLASS_RSS]) {
        $set_updating[] = new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_maxexpire'], 'maxexpire', $LN['config_maxexpire_msg'], $maxexpire_msg, $prefArray_root['maxexpire']);
    }
    if ($module_config[urd_modules::URD_CLASS_SPOTS]) {
      
    }
    $set_updating[] = new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_default_expire_time'], 'default_expire_time', $LN['config_default_expire_time_msg'],
            $default_expire_time_msg, $prefArray_root['default_expire_time']);
    $set_updating[] = new pref_numeric_noformat(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_total_max_articles'], 'total_max_articles', $LN['config_total_max_articles_msg'],
            $total_max_articles_msg, $prefArray_root['total_max_articles']);

    if ($module_config[urd_modules::URD_CLASS_GROUPS] || $module_config[urd_modules::URD_CLASS_RSS]) {
        $set_updating[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_keep_interesting'], 'keep_interesting', $LN['config_keep_interesting_msg'],
                $keep_int_msg, $prefArray_root['keep_interesting']);
    }

    $set_updating[] = new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_expire_incomplete'], 'expire_incomplete', $LN['config_expire_incomplete_msg'],
            $expire_incomplete_msg, $prefArray_root['expire_incomplete']);
    $set_updating[] = new pref_text(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_expire_percentage'], 'expire_percentage', $LN['config_expire_percentage_msg'],
            $expire_percentage_msg, $prefArray_root['expire_percentage']);

    $set_updating[] = new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['config_maxheaders'], 'maxheaders', $LN['config_maxheaders_msg'],
            $maxheaders_msg, $prefArray_root['maxheaders']);
    $set_updating[] = new pref_checkbox(user_levels::CONFIG_LEVEL_BASIC, $LN['config_auto_getnfo'], 'auto_getnfo', $LN['config_auto_getnfo_msg'],
            $auto_getnfo_msg, $prefArray_root['auto_getnfo'], "$('#follow_link_tr').toggleClass('hidden')");
    $set_updating[] = new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_follow_link'], 'follow_link', $LN['config_follow_link_msg'],
            $follow_link_msg, $prefArray_root['follow_link'], NULL, 'follow_link_tr', $prefArray_root['auto_getnfo'] ? '' : 'hidden');
    $set_updating[] = new pref_textarea (user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_poster_blacklist'], 'poster_blacklist', $LN['config_poster_blacklist_msg'], '',
            $poster_blacklist, 10, 40);

    $modules = array(
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_module_groups'], 'module[' . urd_modules::URD_CLASS_GROUPS . ']', $LN['config_module_groups_msg'],
                $module_msg[urd_modules::URD_CLASS_GROUPS], $module_config[urd_modules::URD_CLASS_GROUPS]),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_module_usenzb'], 'module[' . urd_modules::URD_CLASS_USENZB . ']', $LN['config_module_usenzb_msg'],
                $module_msg[urd_modules::URD_CLASS_USENZB], $module_config[urd_modules::URD_CLASS_USENZB]),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_module_spots'], 'module[' . urd_modules::URD_CLASS_SPOTS . ']', $LN['config_module_spots_msg'],
                $module_msg[urd_modules::URD_CLASS_SPOTS], $module_config[urd_modules::URD_CLASS_SPOTS]),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_module_makenzb'], 'module[' . urd_modules::URD_CLASS_MAKENZB . ']', $LN['config_module_makenzb_msg'],
                $module_msg[urd_modules::URD_CLASS_MAKENZB], $module_config[urd_modules::URD_CLASS_MAKENZB]),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_module_rss'], 'module[' . urd_modules::URD_CLASS_RSS . ']', $LN['config_module_rss_msg'],
                $module_msg[urd_modules::URD_CLASS_RSS], $module_config[urd_modules::URD_CLASS_RSS]),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_module_post'], 'module[' . urd_modules::URD_CLASS_POST . ']', $LN['config_module_post_msg'],
                $module_msg[urd_modules::URD_CLASS_POST], $module_config[urd_modules::URD_CLASS_POST]),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_module_sync'], 'module[' . urd_modules::URD_CLASS_SYNC . ']', $LN['config_module_sync_msg'],
                $module_msg[urd_modules::URD_CLASS_SYNC], $module_config[urd_modules::URD_CLASS_SYNC]),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_module_download'], 'module[' . urd_modules::URD_CLASS_DOWNLOAD . ']', $LN['config_module_download_msg'],
                $module_msg[urd_modules::URD_CLASS_DOWNLOAD], $module_config[urd_modules::URD_CLASS_DOWNLOAD]),
            new pref_checkbox(user_levels::CONFIG_LEVEL_ADVANCED, $LN['config_module_viewfiles'], 'module[' . urd_modules::URD_CLASS_VIEWFILES . ']', $LN['config_module_viewfiles_msg'],
                $module_msg[urd_modules::URD_CLASS_VIEWFILES], $module_config[urd_modules::URD_CLASS_VIEWFILES]),);

    $format_strings = array();
    foreach (urd_extsetinfo::$SETTYPES as $t) {
        $format_strings[] = new pref_text(user_levels::CONFIG_LEVEL_MASTER, $LN['settype'][$t], "settype_$t", $LN['settype_msg'][$t], $settype_msg[$t], $prefArray_root["settype_$t"]);
    }
    $custom = array();
    $custom_prefs = get_custom_prefs($db, user_status::SUPER_USERID);
    foreach( $custom_prefs as $key => $value) {
        $custom[] = new pref_custom_text (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_custom'], $key, $LN['pref_custom_msg'], '', $value);
    }
    $custom[] = new pref_custom_text (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_custom'], '__new', $LN['pref_custom_msg'], '', '');

    $pref_list[] = new pref_list('config_globalsettings', $global_settings);
    $pref_list[] = new pref_list('pref_downloading', $download_settings);
    $pref_list[] = new pref_list('config_urdd_head', $urdd_cfg);
    $pref_list[] = new pref_list('config_setinfo', $set_updating);
    if ($module_config[urd_modules::URD_CLASS_SPOTS]) {
        $pref_list[] = new pref_list('pref_spots', $spots_settings);
    }
    $pref_list[] = new pref_list('config_notifysettings', $notify_settings);
    $pref_list[] = new pref_list('config_networking', $networking);
    $pref_list[] = new pref_list('config_formatstrings', $format_strings);
    $pref_list[] = new pref_list('config_extprogs', $ext_progs);
    $pref_list[] = new pref_list('config_prog_params', $prog_params);
    $pref_list[] = new pref_list('config_modules', $modules);
    if ($URDDONLINE) {
        $pref_list[] = new pref_list('config_maintenance', $maintenance);
    } else {
        $pref_list[] = new pref_list('config_maintenance', $maintenance_offline);
    }
    $pref_list[] = new pref_list('pref_custom_values', $custom);
    $current_tab = get_post('current_tab', '');
    init_smarty();
    $smarty->assign(array(
        'current_tab' =>  $current_tab,
        'level' => 		$pref_level,
        'pref_list' => 	$pref_list));
    return $smarty->fetch('ajax_settings.tpl');
}

function verify_text_field(DatabaseConnection $db, urdd_client $uc, $name, &$value, $userid)
{
    global $LN, $isadmin;

    foreach (urd_extsetinfo::$SETTYPES as $t) {
        if ($name == "settype_$t") {
            $rv = verify_text($value);

            return $rv;
        }
    }
    switch ($name) {
        case 'pref_level':
            return verify_array($value, array_keys(user_levels::get_user_levels()));
        case 'urdd_maxthreads':
            $rv = verify_numeric($value, 1);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'nntp_maxthreads':
            $rv = verify_numeric($value, 1);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'nntp_maxdlthreads':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'db_intensive_maxthreads':
            $rv = verify_numeric($value, 1);

            return $rv;
        case 'dlpath':
            $rv = verify_dlpath($db, $value);

            return $rv;
        case 'urdd_host':
            $rv = verify_text($value, '[a-zA-Z0-9.\-_:\[\]]');

            return $rv;
        case 'urdd_port':
            $rv = verify_numeric($value, 1, 65535);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'nice_value':
            $rv = verify_numeric($value, 0, 19);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'queue_size':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'pidpath':
            $rv = verify_path($db, $value);
            return $rv;
        case 'keystore_path':
            $rv = verify_read_only_path($db, $value);
            return $rv;
        case 'urdd_uid':
            $rv = verify_text_opt($value, FALSE, '[a-zA-Z0-9.-]');

            return $rv;
        case 'urdd_gid':
            $rv = verify_text_opt($value, FALSE, '[a-zA-Z0-9.-]');

            return $rv;
        case 'index_page_root':
            return verify_array($value, array_keys(get_index_page_array($isadmin, urd_modules::get_urd_module_config(get_config($db, 'modules')))));
        case 'poster_blacklist':
            $value = clean_area($value);

            return verify_text_area($value);
        case 'group':
            $rv = verify_group($value, TRUE);

            return $rv;
        case 'permissions':
            $rv = verify_array($value, array_keys(get_permissions_array()));

            return $rv;
        case 'log_level':
            $log_levels = get_log_levels_array();
            $rv = verify_array($value, array_keys($log_levels));
            if ($uc->is_connected()) {
                $uc->set('log_level', $log_levels[$value]);
            }

            return $rv;
        case 'scheduler':
            $rv = verify_array($value, array_keys(get_on_off_array()));

            return $rv;
        case 'global_hidden_files_list':
            $value = clean_area($value);

            return verify_text_area($value);
        case 'baseurl':
            $rv = verify_url($value);

            return $rv;
        case 'admin_email':
            $rv = verify_email_address($value);

            return $rv;
        case 'unpar_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'unrar_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'rar_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'unarj_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'subdownloader_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'file_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'tar_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'gzip_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'unzip_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'un7zr_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'unace_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'yydecode_path':
            $rv = verify_prog($value);

            return $rv;
        case 'yyencode_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'urdd_path':
            $rv = verify_prog($value);

            return $rv;
        case 'cksfv_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'trickle_path':
            $rv = verify_prog($value, TRUE);

            return $rv;
        case 'clean_dir_age':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'users_clean_age':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'max_dl_name':
            $rv = verify_numeric($value, 16);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'clean_db_age':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'spot_expire_spam_count':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'maxfilesize':
            $rv = verify_numeric($value, 0, NULL, 1024, 'k');
            $value = unformat_size($value, 1024);

            return $rv;
        case 'maxpreviewsize':
            $rv = verify_numeric($value, 0, NULL, 1024, 'k');
            $value = unformat_size($value, 1024);

            return $rv;
        case 'maxexpire':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'max_login_count':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'maxheaders':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'connection_timeout':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'urdd_connection_timeout':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'socket_timeout':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'total_max_articles':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'replacement_str':
            $rv = verify_text_opt($value, FALSE, '[a-zA-Z0-9.\-_:\[\] ()!@#$%^&{}+;]');

            return $rv;
        case 'group_filter':
            $rv = verify_text_opt($value, FALSE, '[a-zA-Z0-9.*?, ]');

            return $rv;
        case 'spots_reports_group':
            $rv = verify_text_opt($value, FALSE, '[a-zA-Z0-9.]');

            return $rv;
        case 'spots_group':
            $rv = verify_text_opt($value, FALSE, '[a-zA-Z0-9.]');

            return $rv;
        case 'spots_comments_group':
            $rv = verify_text_opt($value, FALSE, '[a-zA-Z0-9.]');

            return $rv;
        case 'ftd_group':
            $rv = verify_text_opt($value, FALSE, '[a-zA-Z0-9.]');

            return $rv;
        case 'spots_blacklist':
            $rv = verify_text_opt($value, FALSE, '[a-zA-Z0-9.:\/&%#;+_\-]');

            return $rv;
        case 'spots_whitelist':
            $rv = verify_text_opt($value, FALSE, '[[a-zA-Z0-9.:\/&%#;+_\-]');

            return $rv;
        case 'extset_group':
            $rv = verify_text_opt($value, FALSE, '[a-zA-Z0-9.]');

            return $rv;
        case 'maxdl':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1024);

            return $rv;
        case 'maxul':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1024);

            return $rv;
        case 'default_expire_time':
            $rv = verify_numeric($value, 1);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'spots_expire_time':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'expire_incomplete':
            $rv = verify_numeric($value, 0);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'expire_percentage':
            $rv = verify_numeric($value, 0, 100);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'default_language':
            $rv = verify_array($value, array_keys(get_languages_array()));

            return $rv;
        case 'auto_login':
            $rv = verify_array($value, array_keys(get_users_array($db)));

            return $rv;
        case 'default_template':
            $rv = verify_array($value, array_keys(get_templates_array($db)));

            return $rv;
        case 'mail_account_activated':
            $rv = verify_array($value, array_keys(get_mail_templates($db)));

            return $rv;
        case 'mail_account_disabled':
            $rv = verify_array($value, array_keys(get_mail_templates($db)));

            return $rv;
        case 'mail_activate_account':
            $rv = verify_array($value, array_keys(get_mail_templates($db)));

            return $rv;
        case 'mail_download_status':
            $rv = verify_array($value, array_keys(get_mail_templates($db)));

            return $rv;
        case 'mail_new_interesting_sets':
            $rv = verify_array($value, array_keys(get_mail_templates($db)));

            return $rv;
        case 'mail_new_preferences':
            $rv = verify_array($value, array_keys(get_mail_templates($db)));

            return $rv;
        case 'mail_new_user':
            $rv = verify_array($value, array_keys(get_mail_templates($db)));

            return $rv;
        case 'mail_password_reset':
            $rv = verify_array($value, array_keys(get_mail_templates($db)));

            return $rv;
        case 'default_stylesheet':
            $rv = verify_array($value, array_keys(get_stylesheets($db, $userid)));

            return $rv;
        case 'urdd_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'unpar_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'unrar_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'rar_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'unace_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'un7zr_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'unzip_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'gzip_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'unarj_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'subdownloader_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'yydecode_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'yyencode_pars':
            $rv = verify_text_opt($value, TRUE, NULL);

            return $rv;
        case 'maxbuttons':
            $rv = verify_numeric($value, 1, NULL, 1000);
            $value = unformat_size($value, 1000);

            return $rv;
        default:
            throw new exception ($LN['error_invalidvalue']);
    }
}

function set_period_configs(DatabaseConnection $db, urdd_client $uc, $name, $value, $time1, $time2, $extra)
{
    global $LN;
    $post_fix = str_replace('period_', '', $name);
    $t1 = 'time1_' . $post_fix;
    $t2 = 'time2_' . $post_fix;
    $par1 = $par2 = $par3 = '';
    $e = NULL;
    switch ($name) {
        case 'period_cd':
            $e = 'dir_' . $post_fix;
            if ($e !== NULL) {
                $rv = verify_array($extra, array_keys(get_cleandir_dirs_array()));
                if ($rv !=  '') {
                    throw new exception($rv['msg']);
                }
                set_config($db, $e, $extra);
            }
            $cmd = urdd_protocol::COMMAND_CLEANDIR;
            $par1 = '__all';
            $par2 = $extra;
            $par3 = get_config($db, 'dir_cd');
            break;
        case 'period_getspots_blacklist':
            $cmd = urdd_protocol::COMMAND_GETBLACKLIST;
            break;
        case 'period_getspots_whitelist':
            $cmd = urdd_protocol::COMMAND_GETWHITELIST;
            break;
        case 'period_expirespots':
            $cmd = urdd_protocol::COMMAND_EXPIRE_SPOTS;
            break;
        case 'period_getspots':
            $cmd = urdd_protocol::COMMAND_GETSPOTS;
            break;
        case 'period_update':
            $cmd = urdd_protocol::COMMAND_CHECK_VERSION;
            break;
        case 'period_sendinfo':
            $cmd = urdd_protocol::COMMAND_SENDSETINFO;
            break;
        case 'period_getinfo':
            $cmd = urdd_protocol::COMMAND_GETSETINFO;
            break;
        case 'period_ng':
            $cmd = urdd_protocol::COMMAND_GROUPS;
            break;
        case 'period_cdb':
            $cmd = urdd_protocol::COMMAND_CLEANDB;
            break;
        case 'period_cu':
            $cmd = urdd_protocol::COMMAND_CLEANDB;
            $par1 = $par2 = $par3 = 'users';
            break;
        case 'period_opt':
            $cmd = urdd_protocol::COMMAND_OPTIMISE;
            break;
        default:
            throw new exception ($LN['error_invalidvalue']);
            break;
    }
    if ($value > 0) {
        $rv = verify_numeric($time1, 0, 23);
        if ($rv != '') {
            throw new exception($rv['msg']);
        }
        $rv = verify_numeric($time2, 0, 59);
        if ($rv !=  '') {
            throw new exception($rv['msg']);
        }
        set_config($db, $name, $value);
        set_config($db, $t1, $time1);
        set_config($db, $t2, $time2);
        process_schedule($db, $uc, $value, $time1, $time2, $cmd, $par1, $par2, $userid);
    } else {
        set_config($db, $name, 0);
        set_config($db, $t1, '');
        set_config($db, $t2, '');
        if ($uc->is_connected()) { 
            $uc->unschedule(get_command($cmd), $par3);
        } else {
        }
    }
}

function verify_bool(DatabaseConnection $db, $name, urdd_client $uc, $value)
{
    switch ($name) {
        case 'module[' . urd_modules::URD_CLASS_USENZB . ']':
            $mod = urd_modules::URD_CLASS_USENZB;
            if ($uc->is_connected()) { 
                $uc->set('module', $mod, $value ? 'on' : 'off');
            }
            break;
        case 'module[' . urd_modules::URD_CLASS_GROUPS. ']':
            $mod = urd_modules::URD_CLASS_GROUPS;
            if ($uc->is_connected()) { 
                $uc->set('module', $mod, $value ? 'on' : 'off');
            }
            break;
        case 'module[' . urd_modules::URD_CLASS_SPOTS . ']':
            $mod = urd_modules::URD_CLASS_SPOTS;
            if ($uc->is_connected()) { 
                $uc->set('module', $mod, $value ? 'on' : 'off');
            }
            break;
        case 'module[' . urd_modules::URD_CLASS_MAKENZB. ']':
            $mod = urd_modules::URD_CLASS_MAKENZB;
            if ($uc->is_connected()) { 
                $uc->set('module', $mod, $value ? 'on' : 'off');
            }
            break;
        case 'module[' . urd_modules::URD_CLASS_RSS . ']':
            $mod = urd_modules::URD_CLASS_RSS;
            if ($uc->is_connected()) { 
                $uc->set('module', $mod, $value ? 'on' : 'off');
            }
            break;
        case 'module[' . urd_modules::URD_CLASS_POST . ']':
            $mod = urd_modules::URD_CLASS_POST;
            if ($uc->is_connected()) { 
                $uc->set('module', $mod, $value ? 'on' : 'off');
            }
            break;
        case 'module[' . urd_modules::URD_CLASS_SYNC . ']':
            $mod = urd_modules::URD_CLASS_SYNC;
            if ($uc->is_connected()) { 
                $uc->set('module', $mod, $value ? 'on' : 'off');
            }
            break;
        case 'module[' . urd_modules::URD_CLASS_DOWNLOAD . ']':
            $mod = urd_modules::URD_CLASS_DOWNLOAD;
            if ($uc->is_connected()) { 
                $uc->set('module', $mod, $value ? 'on' : 'off');
            }
            break;
        case 'module[' . urd_modules::URD_CLASS_VIEWFILES. ']':
            $mod = urd_modules::URD_CLASS_VIEWFILES;
            if ($uc->is_connected()) { 
                $uc->set('module', $mod, $value ? 'on' : 'off');
            }
            break;
        default:
            set_config($db, $name, $value);
    }
}

function set_configuration(DatabaseConnection $db, urdd_client $uc, $userid, $name, $value, $type)
{
    assert(is_numeric($userid));
    global $LN;

    switch ($type) {
        case 'custom_text':
            $orig_name = get_post('original_name');
            $rv = set_custom_text($db, user_status::SUPER_USERID, $name, $value, $orig_name);
            break;
        case 'period':
            $time1 = get_request('time1');
            $time2 = get_request('time2');
            $extra = get_request('extra', '');
            set_period_configs($db, $uc, $name, $value, $time1, $time2, $extra);
            break;
        case 'checkbox':
            if (!in_array($value, array(0, 1, 2))) {
                throw new exception ($LN['error_invalidvalue'] . "'<i>$value</i>'");
            } 
            verify_bool($db, $name, $uc, $value);
            break;
        case 'multiselect':
            //            $rv = set_multi_select($db, $userid, $name, $value);
            break;
        case 'text':
        case 'text_number':
        case 'email':
        case 'textarea':
        case 'select':
            $rv = verify_text_field($db, $uc, $name, $value, $userid);
            if ($rv != '') {
                throw new exception($rv['msg']);
            }
            if ($name == 'scheduler' && urdd_connected($db, $userid)) {
                $uc->set('scheduler', $value);
            }
            if ($name == 'pref_level') { 
                set_pref($db, $name, $value, $userid); // pref level is a user setting, not a config, but here for convenience
            } else {
                set_config($db, $name, $value);
            }
            break;
        default:
            throw new exception ($LN['error_invalidvalue']);
            break;
    }
}

function get_ln_val($name)
{
    global $LN;
    switch ($name) {
        case 'module[' . urd_modules::URD_CLASS_USENZB . ']':
            return $LN['config_module_usenzb'];
        case 'module[' . urd_modules::URD_CLASS_GROUPS . ']':
            return $LN['config_module_groups'];
        case 'module[' . urd_modules::URD_CLASS_SPOTS . ']':
            return $LN['config_module_spots'];
        case 'module[' . urd_modules::URD_CLASS_MAKENZB . ']':
            return $LN['config_module_makenzb'];
        case 'module[' . urd_modules::URD_CLASS_RSS . ']':
            return $LN['config_module_rss'];
        case 'module[' . urd_modules::URD_CLASS_POST . ']':
            return $LN['config_module_post'];
        case 'module[' . urd_modules::URD_CLASS_SYNC . ']':
            return $LN['config_module_sync'];
        case 'module[' . urd_modules::URD_CLASS_DOWNLOAD . ']':
            return $LN['config_module_download'];
        case 'module[' . urd_modules::URD_CLASS_VIEWFILES . ']':
            return $LN['config_module_viewfiles'];
    }
    if ($name == 'pref_level') {
        return $LN['pref_level'];
    }
    if (substr($name, 0, 8) == 'settype_' && is_numeric(substr($name, 8))) {
        return $LN['settype'][substr($name, 8)];
    } else {
        return $LN ['config_' . $name];
    }
}

try {
    $cmd = get_request('cmd', '');
    $message = $contents = '';
    switch ($cmd) {
        case 'reset':
            challenge::verify_challenge($_POST['challenge']);
            reset_config($db);
            break;

        case 'show':
            $contents = show_config($db, $userid);
            break;
        case 'delete':
            challenge::verify_challenge($_POST['challenge']);
            $option = get_post('option');
            unset_config($db, "__custom_$option");
            break;
        case 'set':
            challenge::verify_challenge($_POST['challenge']);
            $rprefs = load_config($db);
            $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);
            $option = get_post('option');
            if (substr($option, -2) == '[]') { 
                $option = substr($option, 0, -2); 
            }
            $value = get_post('value');
            $type = get_post('type');
            set_configuration($db, $uc, $userid, $option, $value, $type);
            config_cache::clear_all();
            if ($type == 'custom_text') {
                $message = $LN['saved'] . ': ' . get_ln_val('custom') . " $option ";
            } else {
                $message = $LN['saved'] . ': ' . get_ln_val($option);
            }
            break;
        case 'load_settings':
            challenge::verify_challenge($_POST['challenge']);
            $xml = new urd_xml_reader($_FILES['filename']['tmp_name']);
            $settings = $xml->read_config($db);
            reset($settings);
            if ($settings != array()) {
                clean_config($db);
                reset_config($db);
                set_configs($db, $settings);
                $imported = 1;
            } else {
                throw new exception($LN['settings_notfound']);
            }
            break;
        case 'export_settings':
            export_settings($db, 'config', 'urd_config.xml');
            break;
        default:
            throw new exception($LN['error_invalidaction'] . implode($_POST, ' '));
            break;
    }
    return_result(array('message' => $message, 'contents' => $contents));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
