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
 * $LastChangedDate: 2013-08-06 00:06:11 +0200 (di, 06 aug 2013) $
 * $Rev: 2891 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: fatal_error.php 2891 2013-08-05 22:06:11Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathfe = realpath(dirname(__FILE__));
require_once "$pathfe/../functions/smarty.php";

function fatal_error($msg, $link=NULL, $link_msg=NULL, $closelink='back')
{
    global $smarty, $LN;
    $msg = html_entity_decode($msg, ENT_QUOTES);
    init_smarty($LN['fatal_error_title'], 1);
        syslog(LOG_WARNING,$msg);
    $smarty->assign(array(
        '__message'=> array($msg),
        'msg'=>$msg,
        'link'=> $link,
        'link_msg'=> $link_msg,
        'closelink'=> $closelink));
    $smarty->display('fatal_error.tpl');
    die();
}
