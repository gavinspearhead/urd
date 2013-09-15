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
 * $LastChangedDate: 2012-08-01 01:23:49 +0200 (wo, 01 aug 2012) $
 * $Rev: 2609 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showquickdisplay.php 2609 2012-07-31 23:23:49Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';
$pathaa = realpath(dirname(__FILE__));
require_once "$pathaa/../functions/ajax_includes.php";

$msg = get_request('msg', '');
$msg = insert_wbr(htmlentities($msg), 25);
$allow_cancel = get_request('allow_cancel', 0);

init_smarty('', 0);
$smarty->assign('msg',			    $msg);
$smarty->assign('allow_cancel',		$allow_cancel);
$smarty->display('ajax_alert.tpl');
