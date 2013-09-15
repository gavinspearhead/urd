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
 * $LastChangedDate: 2013-08-06 00:06:11 +0200 (di, 06 aug 2013) $
 * $Rev: 2891 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: licence.php 2891 2013-08-05 22:06:11Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathab = realpath(dirname(__FILE__));

require_once $pathab . '/../functions/html_includes.php';

init_smarty($LN['licence_title'], 1);

$smarty->display('licence.tpl');
