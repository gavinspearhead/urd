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
 * $LastChangedDate: 2012-09-11 14:39:24 +0200 (di, 11 sep 2012) $
 * $Rev: 2662 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: getfile.php 2662 2012-09-11 12:39:24Z gavinspearhead@gmail.com $
 */

// ok this generates a nice template for the db update, but since the db cannot be trusted we need to load as little as possible from it in the hope it works

@define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

if (!file_exists('../.installed')) {
    die;
}
$pathud = realpath(dirname(__FILE__));

require_once "$pathud/../config.php";

if (isset($config['urdweb_logfile'])) {
    $config['log_file'] = $config['urdweb_logfile'];
} else {
    $config['log_file'] = '/dev/null';
}

$process_name = 'urd_web'; // needed for message format in syslog and logging
require_once "$pathud/../functions/file_functions.php";
require_once "$pathud/../functions/defines.php";
require_once "$pathud/../functions/functions.php";
require_once "$pathud/../functions/db.class.php";
require_once "$pathud/../functions/urd_log.php";
require_once "$pathud/../functions/libs/smarty/libs/Smarty.class.php";
try {
    $db = connect_db(FALSE);  // initialise the database
} catch (exception $e) {
    $msg = $e->getMessage();
    echo "Connection to database failed. $msg\n";
    die;
}
require_once "$pathud/../functions/checkauth.php";

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

@header('X-Frame-Options: sameorigin'); // click jack prevention
$stylesheet = 'light';
$tpldir = 'smarty/templates/';
$langdir = '../functions/lang/';
$smarty = new Smarty();
load_language($lang);
foreach ($LN as $key => $word) {
    $LN2['LN_' . $key] = $word;
}
$smarty->assign($LN2);
unset($LN2);

$smarty->assign('TPLDIR', $tpldir . $template);
$smarty->assign('IMGDIR', $tpldir . $template . '/img');
$smarty->assign('CSSDIR', $tpldir . $template . '/css');
$smarty->assign('JSDIR',  $tpldir . $template . '/js');
$smarty->setTemplateDir(realpath("$pathud/../html/smarty/templates/$template/"));
$smarty->setCompileDir(realpath("$pathud/../html/smarty/c_templates") . "/$template/");
$smarty->setCacheDir(realpath("$pathud/../html/smarty/cache"));
$smarty->setConfigDir(realpath("$pathud/../html/smarty/configs"));
$smarty->setCaching(Smarty::CACHING_OFF); // Caching doesn't work for some pages (like Browse Sets)
$smarty->assign('allow_robots',    0);
$smarty->assign('stylesheet',	   $stylesheet);
$smarty->assign('VERSION',         urd_version::get_version());
$smarty->assign('url',             urd_version::get_urdland_url());
$smarty->assign('link',            ''); // need to fix
$smarty->assign('msg',             ''); // need to fix
$smarty->assign('showmenu',	       0);
$urdd_online = check_urdd_online($db);
$smarty->assign('offline_message', $LN['urdddisabled'] . ' -- ' . $LN['enableurddfirst']);
$smarty->assign('urdd_online',    (int) $urdd_online);
$smarty->assign('menu',		   array());
$challenge = challenge::set_challenge();
$smarty->assign('challenge',   	   $challenge);

$smarty->display('update_db.tpl');
