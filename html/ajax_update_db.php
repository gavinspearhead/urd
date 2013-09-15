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

@define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathaudb = realpath(dirname(__FILE__));

require_once "$pathaudb/../install/update_db.php";
