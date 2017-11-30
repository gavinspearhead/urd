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
 * $LastChangedDate: 2012-07-08 13:46:01 +0200 (Sun, 08 Jul 2012) $
 * $Rev: 2567 $
 * $Author: gavinspearhead $
 * $Id: ajax_admincontrol.php 2567 2012-07-08 11:46:01Z gavinspearhead $
 */

$pathsr = realpath(dirname(__FILE__));

require_once "$pathsr/function_popup.inc.php";
require_once "$pathsr/function_flush.inc.php";
require_once "$pathsr/function_skipper.inc.php";
require_once "$pathsr/modifier_capitalise.inc.php";
require_once "$pathsr/modifier_wbr.inc.php";
require_once "$pathsr/function_progress.inc.php";
require_once "$pathsr/function_checkbox.inc.php";

function register_smarty_extensions(&$smarty)
{
    $smarty->registerPlugin('function', 'urd_popup', 'smarty_function_urd_popup');
    $smarty->registerPlugin('function', 'urd_checkbox', 'smarty_function_urd_checkbox');
    $smarty->registerPlugin('function', 'urd_skipper', 'smarty_function_urd_skipper');
    $smarty->registerPlugin('function', 'urd_flush', 'smarty_function_urd_flush');
    $smarty->registerPlugin('function', 'urd_progressbar', 'smarty_function_urd_progress');
    $smarty->registerPlugin('modifier', 'urd_capitalise', 'smarty_modifier_capitalise');
    $smarty->registerPlugin('modifier', 'urd_wbr', 'smarty_modifier_wbr');
}
