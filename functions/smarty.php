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
 * $LastChangedDate: 2013-09-07 00:34:21 +0200 (za, 07 sep 2013) $
 * $Rev: 2924 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: smarty.php 2924 2013-09-06 22:34:21Z gavinspearhead@gmail.com $
 */
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

// This page handles the including of the right smarty template.
// Language 'templates' are added as well (although not quite smarty.)

$pathsm = realpath(dirname(__FILE__));

//require_once $pathsm . '/web_functions.php';
//require_once $pathsm . '/autoincludes.php';
require_once $pathsm . '/defines.php';
require_once $pathsm . '/menu.php';
require_once $pathsm . '/smarty_plugins/smarty_includes.php';

function init_smarty($title, $show_menu=1, $custom_menu=NULL)
{
    ob_start();
    global $LN, $smarty, $db, $isadmin, $config, $time_a, $userid;
    $clickjack = get_config($db, 'clickjack', TRUE) ? TRUE : FALSE;
    if ($clickjack) {
        @header('X-Frame-Options: sameorigin'); // click jack prevention
    }
    $modules = urd_modules::get_urd_module_config(get_config($db, 'modules'));
    $smarty->setCompileCheck(isset($config['smarty_compile_check']) ? $config['smarty_compile_check'] : TRUE);
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
    $smarty->assign('title', 		   $LN['urdname'] . ' - ' . $title);
    $smarty->assign('allow_robots',    get_config($db, 'allow_robots', 0));
    $smarty->assign('heading', 		   $title);
    $smarty->assign('stylesheet',	   $stylesheet);
    $smarty->assign('__show_time',     '');
    $smarty->assign('show_sync',       $show_sync);
    $smarty->assign('show_groups',     $show_groups);
    $smarty->assign('show_rss',        $show_rss);
    $smarty->assign('show_post',       $show_post);
    $smarty->assign('show_spots',      $show_spots);
    $smarty->assign('show_makenzb',    $show_makenzb);
    $smarty->assign('show_usenzb',     $show_usenzb);
    $smarty->assign('show_viewfiles',  $show_viewfiles);
    $smarty->assign('show_download',   $show_download);
    $smarty->assign('isadmin',         $isadmin);
    $smarty->assign('time_a',          $time_a);
    $smarty->assign('VERSION',         urd_version::get_version());
    $smarty->assign('url',             urd_version::get_urdland_url());
    $smarty->assign('link',            ''); // need to fix
    $smarty->assign('msg',             ''); // need to fix
    $smarty->assign('showmenu',	       $show_menu);
    $urdd_online = check_urdd_online($db);
    $smarty->assign('offline_message', $LN['urdddisabled'] . ' -- ' . $LN['enableurddfirst']);
    $smarty->assign('urdd_online',    (int) $urdd_online);
    if ($show_menu != 0) {
        $smarty->assign('menu',		   generate_menu($db, $LN, $isadmin, $custom_menu, $userid));
    } else {
        $smarty->assign('menu',		   array());
    }
    $challenge = challenge::set_challenge();
    $smarty->assign('challenge',   	   $challenge);
    ob_flush();
}

$smarty = new Smarty();
register_smarty_extensions($smarty);

$need_challenge = get_config($db, 'need_challenge', TRUE) ? TRUE : FALSE;
challenge::set_need_challenge($need_challenge);
if (isset($userid)) {
    $template = select_template($db, $userid);
    $lang = select_language($db, $userid);
} else {
    $template = select_template($db, NULL);
    $lang = select_language($db, NULL);
}
if ($template === NULL) {
    $template = DEFAULT_TEMPLATE;
}
$tpldir = 'smarty/templates/';
$langdir = '../functions/lang/';

function get_menu_display()
{
    if (!isset($_SESSION['menudisplay'])) {
        $_SESSION['menudisplay'] = 1;
    }

    return $_SESSION['menudisplay'];
}

$smarty->assign('TPLDIR', $tpldir . $template);
$smarty->assign('IMGDIR', $tpldir . $template . '/img');
$smarty->assign('CSSDIR', $tpldir . $template . '/css');
$smarty->assign('JSDIR',  $tpldir . $template . '/js');
$smarty->assign('show_menu', get_menu_display());
$smarty->setTemplateDir(realpath("$pathsm/../html/smarty/templates/$template/"));
$smarty->setCompileDir(realpath("$pathsm/../html/smarty/c_templates") . "/$template/");
$smarty->setCacheDir(realpath("$pathsm/../html/smarty/cache"));
$smarty->setConfigDir(realpath("$pathsm/../html/smarty/configs"));
$smarty->setCaching(Smarty::CACHING_OFF); // Caching doesn't work for some pages (like Browse Sets)

load_language($lang);
