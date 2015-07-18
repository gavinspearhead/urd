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
 * $LastChangedDate: 2014-05-30 00:49:17 +0200 (vr, 30 mei 2014) $
 * $Rev: 3077 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: smarty.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

// This page handles the including of the right smarty template.
// Language 'templates' are added as well (although not quite smarty.)

$pathsm = realpath(dirname(__FILE__));

require_once $pathsm . '/menu.php';
require_once $pathsm . '/smarty_plugins/smarty_includes.php';


function get_menu_display()
{
    if (!isset($_SESSION['menudisplay'])) {
        $_SESSION['menudisplay'] = 1;
    }

    return $_SESSION['menudisplay'];
}

function get_template(DatabaseConnection $db, $userid)
{
    if (isset($userid)) {
        $template = select_template($db, $userid);
    } else {
        $template = select_template($db, NULL);
    }
    if ($template === NULL) {
        $template = DEFAULT_TEMPLATE;
    }

    return $template;
}

function get_smarty_dirs($template)
{
    global $pathsm;
    $tpl_dir = realpath("$pathsm/../html/smarty/templates/$template/");
    $ctpl_dir = realpath("$pathsm/../html/smarty/c_templates" . "/$template/");
    $cache_dir = realpath("$pathsm/../html/smarty/cache");
    $config_dir = realpath("$pathsm/../html/smarty/configs");
    if ($tpl_dir === FALSE) { 
        throw new exception('Smarty template directory not accessible');
    }
    if ($ctpl_dir === FALSE) { 
        throw new exception('Smarty compiled template directory not accessible');
    }
    if ($cache_dir === FALSE) { 
        throw new exception('Smarty cache directory not accessible');
    }
    if ($config_dir === FALSE) { 
        throw new exception('Smarty config directory not accessible');
    }
    return array($tpl_dir, $ctpl_dir, $cache_dir, $config_dir);
}

function init_smarty($title='', $show_menu=0, $custom_menu=NULL, $enable_caching=FALSE)
{
    global $LN, $smarty, $db, $isadmin, $config, $userid, $tpldir;
    ob_start();
    $clickjack = get_config($db, 'clickjack', TRUE) ? TRUE : FALSE;
    if ($clickjack) {
        @header('X-Frame-Options: sameorigin'); // click jack prevention
    }
    register_smarty_extensions($smarty);
    $modules = urd_modules::get_urd_module_config(get_config($db, 'modules'));
    $show_post = $modules[urd_modules::URD_CLASS_POST];
    $show_makenzb = $modules[urd_modules::URD_CLASS_MAKENZB];
    $show_usenzb = $modules[urd_modules::URD_CLASS_USENZB];
    $show_rss = $modules[urd_modules::URD_CLASS_RSS];
    $show_groups = $modules[urd_modules::URD_CLASS_GROUPS];
    $show_viewfiles = $modules[urd_modules::URD_CLASS_VIEWFILES];
    $show_sync = $modules[urd_modules::URD_CLASS_SYNC];
    $show_download = $modules[urd_modules::URD_CLASS_DOWNLOAD];
    $show_spots = $modules[urd_modules::URD_CLASS_SPOTS];
    $stylesheet = get_active_stylesheet($db, $userid);
    $urdd_online = check_urdd_online($db);
    $challenge = challenge::set_challenge();
    $template = get_template($db, $userid);
    list($tpl_dir, $ctpl_dir, $cache_dir, $config_dir) = get_smarty_dirs($template);

    $smarty->assign(array(
        'TPLDIR' => $tpldir . $template,
        'IMGDIR' => $tpldir . $template . '/img',
        'CSSDIR' => $tpldir . $template . '/css',
        'JSDIR' => $tpldir . $template . '/js',
        'show_menu' => get_menu_display()));
    $smarty->setTemplateDir($tpl_dir);
    $smarty->setCompileDir($ctpl_dir);
    $smarty->setCacheDir($cache_dir);
    $smarty->setConfigDir($config_dir);
    if (!$enable_caching) {
        $smarty->setCaching(Smarty::CACHING_OFF); // Caching doesn't work for some pages (like Browse Sets)
    } else {
        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT); // Caching doesn't work for some pages (like Browse Sets)
    }

    if ($show_menu != 0) {
        $menu = menu::generate_menu($db, $LN, $isadmin, $custom_menu, $userid);
    } else {
        $menu =	array();
    }
    $smarty->enableSecurity();
    $smarty->setCompileCheck(isset($config['smarty_compile_check']) ? $config['smarty_compile_check'] : TRUE);
    //load smarty language elements
    foreach ($LN as $key => $word) {
        $LN2['LN_' . $key] = $word;
    }
    $smarty->assign($LN2);
    unset($LN2);
    $smarty->assign(array(
        'title' => $LN['urdname'] . ' - ' . $title,
        'allow_robots' => get_config($db, 'allow_robots', 0),
        'heading' => $title,
        'stylesheet' => $stylesheet,
        'challenge' => $challenge,
        'show_sync' =>  $show_sync,
        'max_mobile_viewsize' => 1024, // TODO make constant
        'show_groups' => $show_groups,
        'show_rss' => $show_rss,
        'show_post' => $show_post,
        'show_spots' => $show_spots,
        'show_makenzb'=> $show_makenzb,
        'show_usenzb' => $show_usenzb,
        'show_viewfiles' => $show_viewfiles,
        'show_download' => $show_download,
        'isadmin' => $isadmin,
        'VERSION' => urd_version::get_version(),
        'url' => urd_version::get_urdland_url(),
        'USERSETTYPE_SPOT' => USERSETTYPE_SPOT,
        'USERSETTYPE_RSS' => USERSETTYPE_RSS,
        'USERSETTYPE_GROUP' => USERSETTYPE_GROUP,
        'showmenu' => $show_menu,
        'menu' => $menu,
        'offline_message' => $LN['urdddisabled'] . ' -- ' . $LN['enableurddfirst'],
        'urdd_online' => (int) $urdd_online));
    ob_flush();
}

$tpldir = 'smarty/templates/';
$langdir = '../functions/lang/';

$need_challenge = get_config($db, 'need_challenge', TRUE) ? TRUE : FALSE;
challenge::set_need_challenge($need_challenge);
$smarty = new Smarty();

if (isset($userid)) {
    $lang = select_language($db, $userid);
} elseif (!isset($lang)) { 
    $lang = select_language($db, NULL);
}
load_language($lang);

