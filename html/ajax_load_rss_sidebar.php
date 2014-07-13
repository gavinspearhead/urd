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

try {
    verify_access($db, urd_modules::URD_CLASS_RSS, FALSE, '', $userid);

    init_smarty('', 0);

    $content = $smarty->fetch('ajax_load_rss_sidebar.tpl');
    die(json_encode(array('error'=>0, 'contents'=> $content)));

} catch (exception $e) {
    die(json_encode(array('error'=>$e->getMessage())));
}
