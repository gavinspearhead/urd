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
 * $Id: ajax_import_settings.php 2891 2013-08-05 22:06:11Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathadt = realpath(dirname(__FILE__));

require_once "$pathadt/../functions/ajax_includes.php";
try {
    $referrer = get_request('referrer', '');
    $command = get_request('cmd', '');
    if (preg_match('/^[a-zA-Z_.]+$/', $referrer) == 0) {
        throw new exception ($LN['error_invalidfilename']);
    }

    init_smarty();
    $smarty->assign(array(
        'referrer' => $referrer . '.php',
        'command' => $command));
    $contents = $smarty->fetch('ajax_import_settings.tpl');
    return_result(array('contents' => $contents));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
