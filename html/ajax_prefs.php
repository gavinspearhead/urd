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

$pathap = realpath(dirname(__FILE__));

require_once "$pathap/../functions/ajax_includes.php";
require_once "$pathap/../functions/pref_functions.php";

verify_access($db, NULL, FALSE, '', $userid, FALSE);

function get_search_type_array()
{
    global $LN;
    $search_type_array = array(
        'LIKE' => $LN['search_type_like'],
        'REGEXP' => $LN['search_type_regexp']
    );

    return $search_type_array;
}

function get_basket_type_array()
{
    global $LN;
    $basket_type_array = array(
        basket_type::SMALL => $LN['basket_type_small'],
        basket_type::LARGE => $LN['basket_type_large']
    );

    return $basket_type_array;
}

function get_encrar_array()
{
    global $LN;
    $encrar_array = array (
        encrar::ENCRAR_CONTINUE => $LN['continue'],
        encrar::ENCRAR_CANCEL   => $LN['cancel'],
        encrar::ENCRAR_PAUSE    => $LN['pause']
    );

    return $encrar_array;
}

function get_categories_array(DatabaseConnection $db, $userid)
{
    $spot_categories = SpotCategories::get_categories();
    $categories = get_categories($db, $userid);
    $category_array[''] = '';
    foreach ($categories as $cat) {
        $category_array[$cat['id']] = $cat['name'];
    }

    return $category_array;
}

function get_spot_array(DatabaseConnection $db, $userid)
{
    global $LN;
    $saved_searches = new saved_searches($userid);
    $saved_searches->load($db);
    $spot_array = $saved_searches->get_all_names(USERSETTYPE_SPOT);
    $spot_array = array_merge(array('' => $LN['spots_allcategories']), $spot_array);

    return $spot_array;
}

function get_groups_array(DatabaseConnection $db, $userid)
{
    global $LN;
    $categories = get_used_categories_group($db, $userid);
    $subscribedgroups = subscribed_groups_select($db, NULL, NULL, $categories, $userid);
    $groups_array = array();
    $groups_array['0'] = $LN['browse_allgroups'];
    foreach ($subscribedgroups as $ng) {
        $id = $ng['id'];
        $name = $ng['shortname'];
        $type = $ng['type'];
        $idx = $type . '_' . $id;
        $groups_array[ $idx ] = $name;
    }

    return $groups_array;
}

function get_feeds_array(DatabaseConnection $db, $userid)
{
    global $LN;
    $categories = get_used_categories_group($db, $userid);
    $subscribedfeeds = subscribed_feeds_select($db, NULL, NULL, $categories, $userid);
    $feeds_array = array();
    $feeds_array['0'] = $LN['feeds_allgroups'];
    foreach ($subscribedfeeds as $ng) {
        $id = $ng['id'];
        $name = $ng['name'];
        $type = $ng['type'];
        $idx = $type . '_' . $id;
        $feeds_array[ $idx ] = $name;
    }

    return $feeds_array;
}

function get_sort_array()
{
    global $LN;

    $sort_array = array(
            'better_subject ASC'  => $LN['browse_subject'] . ' - ' . $LN['ascending'],
            'better_subject DESC' => $LN['browse_subject'] . ' - ' . $LN['descending'],
            'Date DESC'           => $LN['browse_age'] . ' - ' . $LN['ascending'], // need to be inverted as we sort on timestamp, not age
            'Date ASC'            => $LN['browse_age'] . ' - ' . $LN['descending'],
            'Size ASC'            => $LN['size'] . ' - ' . $LN['ascending'],
            'Size DESC'           => $LN['size'] . ' - ' . $LN['descending']
            );

    return $sort_array;
}

function verify_text_field(DatabaseConnection $db, $userid, $name, &$value)
{
    global $LN;
    switch ($name) {
        case 'spot_spam_limit':
            $rv = verify_numeric($value, 0, NULL, 1000);
            $value = unformat_size($value, 1000);
            return $rv;
        case 'maxsetname':
            $rv = verify_numeric($value, 1, NULL, 1000);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'minsetsize':
            $rv = verify_numeric($value, 0, NULL, 1024, 'M');
            $value = unformat_size($value, 1024, 'M');

            return $rv;
        case 'maxsetsize':
            $rv = verify_numeric($value, 0, NULL, 1024, 'M');
            $value = unformat_size($value, 1024, 'M');

            return $rv;
        case 'minngsize':
            $rv = verify_numeric($value, 0, NULL, 1000);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'download_delay':
            $rv = verify_numeric($value, 0, NULL, 1000);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'rarfile_size':
            $rv = verify_numeric($value, 0, NULL, 1024, 'k');
            $value = unformat_size($value, 1024, 'k');

            return $rv;
        case 'recovery_size':
            $rv = verify_numeric($value, 0, NULL, 1000);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'setsperpage':
            $rv = verify_numeric($value, 1, 10000, 1000);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'setcompleteness':
            $rv = verify_numeric($value, 0, 100, 1000);
            $value = unformat_size($value, 1000);

            return $rv;
        case 'poster_name':
        case 'format_dl_dir':
        case 'poster_email':
        case 'subs_lang':
            return verify_text_opt($value, FALSE, NULL);
        case 'cancel_crypted_rars':
            return verify_array($value, array_keys(get_encrar_array()));
        case 'search_type':
            return verify_array($value, array_keys(get_search_type_array()));
        case 'basket_type':
            return verify_array($value, array_keys(get_basket_type_array()));
        case 'default_group':
            return verify_array($value, array_keys(get_groups_array($db, $userid)));
        case 'default_spot':
            return verify_array($value, array_keys(get_spot_array($db, $userid)));
        case 'default_feed':
            return verify_array($value, array_keys(get_feeds_array($db, $userid)));
        case 'language':
            return verify_array($value, array_keys(get_languages_array($db, $userid)));
        case 'template':
            return verify_array($value, array_keys(get_templates($db, $userid)));
        case 'stylesheet':
            return verify_array($value, array_keys(get_stylesheets()));
        case 'pref_level':
            return verify_array($value, array_keys(user_levels::get_user_levels()));
        case 'index_page':
            return verify_array($value, array_keys(get_index_page_array(urd_user_rights::is_admin($db, $userid), urd_modules::get_urd_module_config(get_config($db, 'modules')))));
        case 'hidden_files_list':
        case 'poster_default_text':
        case 'search_terms':
            $value = clean_area($value);

            return verify_text_area($value);
        case 'blocked_terms':
            $value = clean_area($value);

            return verify_text_area($value);
        case 'spot_category_spots_sound':
        case 'spot_category_spots_image':
        case 'spot_category_spots_game':
        case 'spot_category_spots_application':
            return verify_array($value, array_keys(get_categories_array($db, $userid)));
        case 'defaultsort':
            return verify_array($value, array_keys(get_sort_array()));
            return;
        default:
            throw new exception ($LN['error_invalidvalue']);
    }
}

function set_search_options(DatabaseConnection $db, $userid, $value)
{
    $prefArray = load_prefs($db, $userid, TRUE);
    $maxsearch_options = get_config($db, 'maxbuttons');
    $value = explode(':', $value);
    for ($i = 0; $i < $maxsearch_options; $i++) {
        $search_option = 'button' . (string) ($i + 1);
        $v = (isset($value[$i]) && is_numeric($value[$i])) ? $value[$i] : 'none';
        set_pref($db, $search_option, $v, $userid);
    }
}

function set_multi_select(DatabaseConnection $db, $userid, $name, $value)
{
    global $LN;
    switch ($name) {
        case 'buttons':
            set_search_options($db, $userid, $value);

            return;
        case 'global_scripts':
            $value = explode(':', $value);
            $value = implode("\n", $value);

            set_pref($db, $name, $value, $userid);
            return;
        case 'user_scripts':
            $value = explode(':', $value);
            $value = implode("\n", $value);
            set_pref($db, $name, $value, $userid);
            return;
        default:
            throw new exception ($LN['error_invalidvalue'] . ' '. $name . ' ' . $value );
    }
}

function set_preferences(DatabaseConnection $db, $userid, $name, $value, $type)
{
    global $LN;
    switch ($type) {
        case 'checkbox':
            if (!in_array($value, array(0,1,2))) {
                throw new exception($LN['error_invalidvalue'] .  "'<i>$value</i>'");
            }
            set_pref($db, $name, $value, $userid);
            break;
        case 'multiselect':
            $rv = set_multi_select($db, $userid, $name, $value);
            break;
        case 'custom_text':
            $orig_name = get_post('original_name');
            $rv = set_custom_text($db, $userid, $name, $value, $orig_name);
            break;
        case 'email':
        case 'text':
        case 'textarea':
        case 'select':
            $rv = verify_text_field($db, $userid, $name, $value);
            if ($rv != '') {
                throw new exception($rv['msg'] . " $name => $value");
            }
            set_pref($db, $name, $value, $userid);
            break;
        default:
            throw new exception ($LN['error_invalidvalue']. " $name => $value $type");
            break;
    }
}

function change_password(DatabaseConnection $db, $userid)
{
    global $LN;
    $oldpass  = get_post('oldpass', '');
    $newpass1 = get_post('newpass1', '');
    $newpass2 = get_post('newpass2', '');
    if ($oldpass != '' && $newpass1 != '' && $newpass2 != '') {
        if ($newpass1 != $newpass2) {
            throw new exception($LN['error_pwmatch']);
        } else {
            $username = get_username($db, $userid);
            try {
                $salt = get_salt($db, $username);
            } catch (exception $e) {
                $salt = '';
            }

            $oldpass = hash('sha256',  $salt . $oldpass . $salt);
            $res = $db->select_query('"name" FROM users WHERE "ID"=? AND "pass" = ?', array($userid, $oldpass));
            if ($res === FALSE) {
                throw new exception($LN['error_pwincorrect']);
            } else {
                set_password($db, $userid, $newpass1);
                $salt = get_salt($db, $username);
                $token = generate_password(8);
                list($password, $md5pass) = hash_password($newpass1, $salt, $token);
                $period = get_session('urd_period', 0);
                $period = get_cookie('urd_period', $period);
                $_SESSION['urd_pass'] = $password;
                $_SESSION['urd_token'] = $token;
                setcookie('urd_pass', $password, max(time() + 3600, $period));
                setcookie('urd_token', $token, max(time() + 3600, $period));
            }
        }
    } elseif ($oldpass != '' && ($newpass1 != '' || $newpass2 != '')) {
        throw new exception($LN['error_pwincorrect']);
    }
}

function get_ln_val($name)
{
    global $LN;
    if ($name == 'pref_level') {
        return $LN['pref_level'];
    } elseif (substr($name, 0, 13) == 'spot_category') {
        return $LN['pref_spots_category_mapping'] . ' ' . $LN[substr($name, 14)];
    } else {
        return $LN ['pref_' . $name];
    }
}

function show_preferences(DatabaseConnection $db, $userid)
{
    global $LN, $username, $isadmin, $smarty;

    $spot_categories = SpotCategories::get_categories();
    $shaping_msg = $unrar_msg = $unpar_msg = $auto_expire_msg = $password_msg = $index_page_msg = $add_setname_msg = '';
    $delete_files_msg = $urdd_restart_msg = $nntp_useauth_msg = $hiddenfiles_msg = $mail_user_msg = $download_text_file_msg = $download_par_msg = '';
    $search_type_msg = $use_auto_download_nzb_msg = $use_auto_download_msg = $skip_int_msg = $delete_account_msg = $max_tasks1_msg = '';
    $max_task2_msg = $mail_user_sets_msg = $show_image_msg = $show_subcats_msg = '';

    foreach ($spot_categories as $sc) {
        $spot_category_msg[$sc] = '';
    }

    $pref_level = get_pref($db, 'pref_level', $userid);
    $sort_array = get_sort_array();

    $sendmail = get_config($db, 'sendmail');
    $auto_download = get_config($db, 'auto_download');
    $allow_global_scripts = get_config($db, 'allow_global_scripts');
    $allow_user_scripts= get_config($db, 'allow_user_scripts');
    $modules = urd_modules::get_urd_module_config( get_config($db, 'modules'));
    $dlpath = get_dlpath($db);
    $scripts_path = $dlpath . SCRIPTS_PATH;

    $prefArray = load_prefs($db, $userid, TRUE);
    $module_config = urd_modules::get_urd_module_config(get_config($db, 'modules'));

    $global_scripts_array = get_scripts($db, $scripts_path, $userid, TRUE);
    $user_scripts_array = get_scripts($db, $scripts_path, $userid, FALSE);
    $category_array = get_categories_array($db, $userid);
    $level_array = user_levels::get_user_levels();
    $templates = get_templates();
    $search_type_array = get_search_type_array();

    $languages = array_map('htmlentities', get_languages());
    $basket_type_array = get_basket_type_array();
    $encrar_array = get_encrar_array();

    $search_options = array();
    try {
        foreach (get_search_options($db) as $k => $s) {
            $search_options[$k] = htmlentities(utf8_decode($s));
        }
    } catch (exception $e) {
        // don't do anything?
    }

    $index_page_array = get_index_page_array($isadmin, $modules);
    $stylesheets = get_stylesheets();
    $saved_searches = new saved_searches($userid);
    $saved_searches->load($db);
    $spot_array = $saved_searches->get_all_names(USERSETTYPE_SPOT);
    $spot_array = array_merge(array('' => $LN['spots_allcategories']), $spot_array);

    $groups_array = get_groups_array($db, $userid);
    $feeds_array = get_feeds_array($db, $userid);

    // test if the current settings are correct
    if (!isset($default_spot_msg)) {
        $default_spot_msg = verify_array($prefArray['default_spot'], array_keys($spot_array));
    }
    if (!isset($cancel_crypted_rars_msg)) {
        $cancel_crypted_rars_msg = verify_array($prefArray['cancel_crypted_rars'], array_keys($encrar_array));
    }
    if (!isset($default_group_msg)) {
        $default_group_msg = verify_array($prefArray['default_group'], array_keys($groups_array));
    }
    if (!isset($default_feed_msg)) {
        $default_feed_msg = verify_array($prefArray['default_feed'], array_keys($feeds_array));
    }
    if (!isset($pref_level_msg)) {
        $pref_level_msg = verify_array($prefArray['pref_level'], array_keys($level_array));
    }
    if (!isset($language_msg)) {
        $language_msg = verify_array($prefArray['language'], array_keys($languages));
    }
    if (!isset($template_msg)) {
        $template_msg = verify_array($prefArray['template'], array_keys($templates));
    }
    if (!isset($stylesheet_msg)) {
        $stylesheet_msg = verify_array($prefArray['stylesheet'], array_keys($stylesheets));
    }
    if (!isset($index_array_msg)) {
        $index_array_msg = verify_array($prefArray['index_page'], array_keys($index_page_array));
    }
    if (!isset($search_type_msg)) {
        $search_type_msg = verify_array($prefArray['search_type'], array_keys($search_type_array));
    }
    if (!isset($basket_type_msg)) {
        $basket_type_msg = verify_array($prefArray['basket_type'], array_keys($basket_type_array));
    }
    if (!isset($search_terms_msg)) {
        $search_terms_msg = verify_text_area($prefArray['search_terms']);
    }
    if (!isset($blocked_terms_msg)) {
        $blocked_terms_msg = verify_text_area($prefArray['blocked_terms']);
    }
    if (!isset($hidden_files_list_msg)) {
        $hidden_files_list_msg = verify_text_area($prefArray['hidden_files_list']);
    }
    if (!isset($defaultsort_msg)) {
        $defaultsort_msg = verify_sort($prefArray['defaultsort'], array_keys($sort_array));
    }
    if (!isset($minsetsize_msg)) {
        $minsetsize_msg = verify_numeric($prefArray['minsetsize'],0, NULL, 1024, 'm');
    }
    if (!isset($maxsetsize_msg)) {
        $maxsetsize_msg = verify_numeric($prefArray['maxsetsize'],0, NULL, 1024, 'm');
    }
    if (!isset($minngsize_msg)) {
        $minngsize_msg = verify_numeric($prefArray['minngsize'],0, NULL, 1000);
    }
    if (!isset($spot_spam_limit_msg)) {
        $spot_spam_limit_msg = verify_numeric($prefArray['spot_spam_limit'], 0, NULL, 1000);
    }
    if (!isset($setsperpage_msg)) {
        $setsperpage_msg = verify_numeric($prefArray['setsperpage'],1, NULL, 1000);
    }
    if (!isset($maxsetname_msg)) {
        $maxsetname_msg = verify_numeric($prefArray['maxsetname'],1, NULL, 1000);
    }
    if (!isset($download_delay_msg)) {
        $download_delay_msg = verify_numeric($prefArray['download_delay'],0, NULL, 1000);
    }
    if (!isset($recovery_size_msg)) {
        $recovery_size_msg = verify_numeric($prefArray['recovery_size'],0, NULL, 1000);
    }
    if (!isset($rarfile_size_msg)) {
        $rarfile_size_msg = verify_numeric($prefArray['rarfile_size'],0, NULL, 1000, 'k');
    }

    if (!isset($subs_lang_msg)) {
        $subs_lang_msg = verify_text_opt('subs_lang', TRUE, NULL);
    }
    if (!isset($poster_default_text_msg)) {
        $poster_default_text_msg = verify_text_opt('poster_default_text', TRUE, NULL);
    }
    if (!isset($poster_name_msg)) {
        $poster_name_msg = verify_text_opt('poster_name', TRUE, NULL);
    }
    if (!isset($format_dl_dir_msg)) {
        $format_dl_dir_msg = verify_text_opt('format_dl_dir', TRUE, NULL);
    }
    if (!isset($poster_email_msg)) {
        $poster_email_msg = verify_text_opt('poster_email', TRUE, NULL);
    }
    if (!isset($setcompleteness_msg)) {
        $setcompleteness_msg = verify_numeric($prefArray['setcompleteness'],0,100,1000);
    }

    if (!isset($global_scripts_msg)) {
        $global_scripts_msg = '';
    }
    if (!isset($user_scripts_msg)) {
        $user_scripts_msg = '';
    }

    if (!isset($buttons_msg)) {
        $buttons_msg = '';
        foreach ($prefArray as $k => $p) {
            if (preg_match('/^button[0-9]+$/', $k) && $p != 'none') {
                $buttons_msg = verify_search_option($prefArray["$k"], $search_options);
            }
        }
    }

    $cur_search_options = array();
    foreach ($prefArray as $k => $p) {
        if (preg_match('/^button[0-9]+$/', $k) && $p != 'none') {
            $cur_search_options[] = $p;
        }
    }

    $search_options_array = array();
    foreach ($search_options as $k => $search_option) {
        $search_options_array[$k] = array('name' => $search_option, 'on' => 0, 'id' => $k);
    }

    foreach ($cur_search_options as $b) {
        if (is_numeric($b) && isset($search_options_array[(int) $b])) {
            $search_options_array[(int) $b]['on'] = 1;
        }
    }

    $search_terms = @unserialize($prefArray['search_terms']);
    if ($search_terms === FALSE) { $search_terms = $prefArray['search_terms']; }

    $blocked_terms = @unserialize($prefArray['blocked_terms']);
    if ($blocked_terms === FALSE) { $blocked_terms = $prefArray['blocked_terms']; }

    $hidden_files_list = @unserialize($prefArray['hidden_files_list']);
    if ($hidden_files_list === FALSE) { $hidden_files_list = $prefArray['hidden_files_list']; }

    $login = array(
            new pref_plain(user_levels::CONFIG_LEVEL_BASIC, $LN['username'], $LN['username_msg'], $username, NULL, NULL),
            new pref_password(user_levels::CONFIG_LEVEL_BASIC, $LN['oldpw'], 'oldpass', $LN['oldpw_msg'] , $password_msg,'' , TEXT_BOX_SIZE),
            new pref_password(user_levels::CONFIG_LEVEL_BASIC, $LN['newpw'] . ' (1)', 'newpass1', $LN['newpw1_msg'] , '', '', TEXT_BOX_SIZE),
            new pref_password(user_levels::CONFIG_LEVEL_BASIC, $LN['newpw'] . ' (2)', 'newpass2', $LN['newpw2_msg'] , '', '', TEXT_BOX_SIZE),
            new pref_password_submit('oldpass', 'newpass1', 'newpass2', 'user_pass_change', $LN['change_password'], 'pass_change', $username),
            new pref_select(user_levels::CONFIG_LEVEL_BASIC,  $LN['pref_index_page'], 'index_page', $LN['pref_index_page_msg'], $index_page_msg,  $index_page_array, $prefArray['index_page']),
            new pref_button(user_levels::CONFIG_LEVEL_BASIC, $LN['delete_account'], 'delete_account', $LN['delete_account_msg'] , $delete_account_msg, $LN['delete'],
                'onclick="javascript:confirm_delete_account(\'delete_account\', \'' . $LN['delete_account'] . '?\');"', 'delete_account', NULL),
            );

    $spots = array(
            new pref_numeric_noformat(user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_spot_spam_limit'], 'spot_spam_limit', $LN['pref_spot_spam_limit_msg'], $spot_spam_limit_msg, $prefArray['spot_spam_limit'], NUMBER_BOX_SIZE),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_show_image'], 'show_image', $LN['pref_show_image_msg'], $show_image_msg, $prefArray['show_image']),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_show_subcats'], 'show_subcats', $LN['pref_show_subcats_msg'], $show_subcats_msg, $prefArray['show_subcats']),
            new pref_select (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_default_spot'], 'default_spot', $LN['pref_default_spot_msg'], $default_spot_msg, $spot_array, $prefArray['default_spot']),
            );

    if (count($category_array) > 0) {
        foreach ($spot_categories as $sc) {
            $spots[] = new pref_select (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_spots_category_mapping'] . ' ' . $LN[$sc],  'spot_category_' . $sc,
                    $LN['pref_spots_category_mapping_msg'], $spot_category_msg[$sc], $category_array, isset($prefArray['spot_category_'. $sc]) ? $prefArray['spot_category_'. $sc] : '');
        }
    }
    $display = array (
            new pref_select (user_levels::CONFIG_LEVEL_ALWAYS, $LN['pref_level'], 'pref_level', $LN['pref_level_msg'], $pref_level_msg, $level_array, $prefArray['pref_level'], 'load_prefs();'),
            new pref_select (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_language'], 'language', $LN['pref_language_msg'], $language_msg, $languages, $prefArray['language'], 'reload_prefs();'),
            new pref_select (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_stylesheet'], 'stylesheet', $LN['pref_stylesheet_msg'], $stylesheet_msg, $stylesheets, $prefArray['stylesheet'], 'change_stylesheet(\'stylesheet\');'),
            new pref_select (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_template'], 'template', $LN['pref_template_msg'], $template_msg, $templates, $prefArray['template']),
            new pref_select (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_defaultsort'], 'defaultsort', $LN['pref_defaultsort_msg'], $defaultsort_msg, $sort_array, $prefArray['defaultsort']),
            new pref_numeric (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_maxsetname'], 'maxsetname',$LN['pref_maxsetname_msg'], $maxsetname_msg, $prefArray['maxsetname'], NUMBER_BOX_SIZE ),
            new pref_numeric (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_setsperpage'], 'setsperpage',$LN['pref_setsperpage_msg'], $setsperpage_msg, $prefArray['setsperpage'], NUMBER_BOX_SIZE ),
            new pref_numeric (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_minsetsize'], 'minsetsize',$LN['pref_minsetsize_msg'], $minsetsize_msg, $prefArray['minsetsize'], NUMBER_BOX_SIZE ),
            new pref_numeric (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_maxsetsize'], 'maxsetsize',$LN['pref_maxsetsize_msg'], $maxsetsize_msg, $prefArray['maxsetsize'], NUMBER_BOX_SIZE ),
            new pref_numeric (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_minngsize'], 'minngsize',$LN['pref_minngsize_msg'], $minngsize_msg, $prefArray['minngsize'], NUMBER_BOX_SIZE) ,
            new pref_numeric (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_setcompleteness'], 'setcompleteness', $LN['pref_setcompleteness_msg'], $setcompleteness_msg,  $prefArray['setcompleteness'], NUMBER_BOX_SIZE),
            new pref_checkbox (user_levels::CONFIG_LEVEL_MASTER, $LN['pref_skip_int'], 'skip_int',$LN['pref_skip_int_msg'], $skip_int_msg, $prefArray['skip_int']),
            new pref_checkbox (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_hiddenfiles'], 'hiddenfiles',$LN['pref_hiddenfiles_msg'], $hiddenfiles_msg, $prefArray['hiddenfiles'], '$(\'#hidfil\').toggleClass(\'hidden\');'),
            new pref_textarea (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_hidden_files_list'], 'hidden_files_list', $LN['pref_hidden_files_list_msg'], $hidden_files_list_msg, $hidden_files_list, 10, 40, NULL, 'hidfil', $prefArray['hiddenfiles']? NULL : 'hidden'),
            new pref_multiselect (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_buttons'], 'buttons', $LN['pref_buttons_msg'], $buttons_msg, $search_options_array, 5),
            new pref_select (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_default_group'], 'default_group', $LN['pref_default_group_msg'], $default_group_msg, $groups_array, $prefArray['default_group']),
            new pref_select (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_default_feed'], 'default_feed', $LN['pref_default_feed_msg'], $default_feed_msg, $feeds_array, $prefArray['default_feed']),
            );

    $downloading = array (
            new pref_select (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_basket_type'],  'basket_type', $LN['pref_basket_type_msg'], $basket_type_msg, $basket_type_array, $prefArray['basket_type']),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_add_setname'], 'add_setname',$LN['pref_add_setname_msg'], $add_setname_msg, $prefArray['add_setname']),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_mail_user'], 'mail_user',$LN['pref_mail_user_msg'], $mail_user_msg, $prefArray['mail_user'], NULL, NULL,  $sendmail?'':'hidden'),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_mail_user_sets'], 'mail_user_sets',$LN['pref_mail_user_sets_msg'], $mail_user_sets_msg, $prefArray['mail_user_sets'], NULL, NULL, ($sendmail )?'':'hidden'),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_download_par'], 'download_par', $LN['pref_download_par_msg'], $download_par_msg, $prefArray['download_par']),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_unpar'], 'unpar', $LN['pref_unpar_msg'], $unpar_msg, $prefArray['unpar']),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_unrar'], 'unrar',$LN['pref_unrar_msg'], $unrar_msg, $prefArray['unrar']),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_delete_files'], 'delete_files', $LN['pref_delete_files_msg'], $delete_files_msg, $prefArray['delete_files']),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_download_text_file'], 'download_text_file', $LN['pref_download_text_file_msg'], $download_text_file_msg, $prefArray['download_text_file']),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_use_auto_download'], 'use_auto_download', $LN['pref_use_auto_download_msg'], $use_auto_download_msg, $prefArray['use_auto_download'],  '$(\'#autodlnzb\').toggleClass(\'hidden\');', NULL, $auto_download?'':'hidden'),
            new pref_checkbox (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_use_auto_download_nzb'], 'use_auto_download_nzb', $LN['pref_use_auto_download_nzb_msg'], $use_auto_download_nzb_msg, $prefArray['use_auto_download_nzb'], NULL, 'autodlnzb', ($auto_download &&$prefArray['use_auto_download']) ?'':'hidden'),
            new pref_text (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_format_dl_dir'], 'format_dl_dir',$LN['pref_format_dl_dir_msg'], $format_dl_dir_msg, $prefArray['format_dl_dir'], TEXT_BOX_SIZE),
            new pref_numeric (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_download_delay'], 'download_delay', $LN['pref_download_delay_msg'], $download_delay_msg, $prefArray['download_delay'], NUMBER_BOX_SIZE),
            new pref_select (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_cancel_crypted_rars'], 'cancel_crypted_rars', $LN['pref_cancel_crypted_rars_msg'], $cancel_crypted_rars_msg, $encrar_array, $prefArray['cancel_crypted_rars']),
            new pref_text (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_subs_lang'], 'subs_lang',$LN['pref_subs_lang_msg'], $subs_lang_msg, $prefArray['subs_lang'], TEXT_BOX_SIZE),
            );

    $downloading[] = new pref_select (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_search_type'], 'search_type', $LN['pref_search_type_msg'], $search_type_msg, $search_type_array, $prefArray['search_type']);
    $downloading[] = new pref_textarea (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_search_terms'], 'search_terms', $LN['pref_search_terms_msg'], '', $search_terms, 10, 40);
    $downloading[] = new pref_textarea (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_blocked_terms'], 'blocked_terms', $LN['pref_blocked_terms_msg'], '', $blocked_terms, 10 , 40);

    if ($allow_global_scripts != 0) {
        if (count($global_scripts_array) > 0) {
            $downloading[] = new pref_multiselect (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_global_scripts'], 'global_scripts[]', $LN['pref_global_scripts_msg'], $global_scripts_msg, $global_scripts_array, 5);
        }
        if ($allow_user_scripts != 0 && count($user_scripts_array) > 0) {
            $downloading[] = new pref_multiselect (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_user_scripts'], 'user_scripts[]', $LN['pref_user_scripts_msg'], $user_scripts_msg, $user_scripts_array, 5);
        }
    }

    $posting = array (
            new pref_email (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_poster_email'], 'poster_email', $LN['pref_poster_email_msg'], $poster_email_msg, $prefArray['poster_email'], TEXT_BOX_SIZE),
            new pref_text (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_poster_name'], 'poster_name', $LN['pref_poster_name_msg'], $poster_name_msg, $prefArray['poster_name'], TEXT_BOX_SIZE),
            new pref_textarea (user_levels::CONFIG_LEVEL_BASIC, $LN['pref_poster_default_text'], 'poster_default_text', $LN['pref_poster_default_text_msg'], $poster_default_text_msg, $prefArray['poster_default_text'], 10 , 40),
            new pref_numeric (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_recovery_size'], 'recovery_size',$LN['pref_recovery_size_msg'], $recovery_size_msg, $prefArray['recovery_size'], NUMBER_BOX_SIZE),
            new pref_numeric (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_rarfile_size'], 'rarfile_size',$LN['pref_rarfile_size_msg'], $rarfile_size_msg, $prefArray['rarfile_size'], NUMBER_BOX_SIZE),
            );

    $custom = array();
    $custom_prefs = get_custom_prefs($db, $userid);
    foreach( $custom_prefs as $key => $value) {
        $custom[] = new pref_custom_text (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_custom'], $key, $LN['pref_custom_msg'], '', $value, TEXT_BOX_SIZE);
    }
    $custom[] = new pref_custom_text (user_levels::CONFIG_LEVEL_ADVANCED, $LN['pref_custom'], '__new', $LN['pref_custom_msg'], '', '', TEXT_BOX_SIZE);
    $pref_list[] = new pref_list('pref_display', $display);
    if ($module_config[urd_modules::URD_CLASS_SPOTS]) {
        $pref_list[] = new pref_list('pref_spots', $spots);
    }
    if ($module_config[urd_modules::URD_CLASS_DOWNLOAD]) {
        $pref_list[] = new pref_list('pref_downloading', $downloading);
    }
    if ($module_config[urd_modules::URD_CLASS_POST]) {
        $pref_list[] = new pref_list('pref_posting', $posting);
    }
    $pref_list[] = new pref_list('pref_login', $login);
    $pref_list[] = new pref_list('pref_custom_values', $custom);

    init_smarty('', 0);
    $current_tab = get_post('current_tab', '');
    $smarty->assign('current_tab',  $current_tab);
    $smarty->assign('level', 		$pref_level);
    $smarty->assign('pref_list', 	$pref_list);
    return $smarty->fetch('ajax_settings.tpl');
}

try {
    $dlpath = get_dlpath($db);
    $scripts_path = $dlpath . SCRIPTS_PATH;


    $cmd = get_request('cmd', '');
    $message = $contents = '';

    switch ($cmd) {
        case 'show':
            init_smarty('', 0);
            $contents = show_preferences($db, $userid);
            break;
        case 'change_password':
            challenge::verify_challenge($_POST['challenge']);
            change_password($db, $userid);
            $message = $LN['password_changed'];
            break;
        case 'set':
            challenge::verify_challenge($_POST['challenge']);
            $option = get_post('option');
            $value = get_post('value');
            $type = get_post('type');
            if (substr($option, -2) == '[]') { $option = substr($option, 0, -2); }
            config_cache::clear($userid);
            set_preferences($db, $userid, $option, $value, $type);
            if ($type == 'custom_text') {
                $message = $LN['saved'] . ': ' . get_ln_val('custom') . " $option ";
            } else {
                $message = $LN['saved'] . ': ' . get_ln_val($option);
            }
            break;
        case 'delete':
            challenge::verify_challenge($_POST['challenge']);
            $option = get_post('option');
            unset_pref($db, "__custom_$option", $userid);
            break;
        case 'load_settings':
            challenge::verify_challenge($_POST['challenge']);
            $xml = new urd_xml_reader($_FILES['filename']['tmp_name']);
            $settings = $xml->read_user_settings($db);
            reset($settings);
            if ($settings != array()) {
                clean_pref($db, $userid); // remove all settings for user
                reset_pref($db, $userid); // restore the default settings
                set_prefs($db, $userid, current($settings)); // overwrite with loaded settings
            } else {
                throw new exception($LN['settings_notfound']);
            }
            break;
        case 'export_settings':
            $username = get_username($db, $userid);
            export_settings($db, 'user_settings', "urd_user_settings_$username.xml", $userid);
            break;
        case 'reset':
            challenge::verify_challenge($_POST['challenge']);
            reset_pref($db, $userid);
            break;
        default:
            throw new exception($LN['error_invalidaction'] . ' ' . htmlentities($cmd));
            break;
    }
    return_result(array('message' => $message, 'contents' => $contents));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
