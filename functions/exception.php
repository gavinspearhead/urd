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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: exception.php 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}
$pathexc = realpath(dirname(__FILE__));

require_once "$pathexc/smarty.php";

define ('DEFAULT_MSG', 'Our sincere apologies but a fatal error has occurred. For more information, turn on DEBUG mode.');

// Default exception handler:
function exception_handler(exception $exception)
{
    global $__message, $LN, $title, $config, $smarty, $userid;
    $thisScript = basename($_SERVER['PHP_SELF']);
    if (strpos($thisScript, 'ajax_') === FALSE) {
        // Not an ajax-script, so display entire page
        if (debug_match(DEBUG_CLIENT, $config['urdd_debug_level'])) {
            $__message[] = $exception->getMessage();
        } else {
            $__message[] = DEFAULT_MSG;
        }
        init_smarty($LN['fatal_error_title'], 1);
        $smarty->assign('__message', $__message);
        $smarty->assign('closelink', 'back');
        $smarty->display('fatal_error.tpl');
    } else {
        // Ajax script, output bare html
        if (debug_match(DEBUG_CLIENT, $config['urdd_debug_level'])) {
            $__message[] = $exception->getMessage();
         } else {
            $__message[] = DEFAULT_MSG;
        }
        die_html(':error:' . $LN['error'] . ': ' . implode ("<br/>\n", $__message));
    }
    die();
}

set_exception_handler('exception_handler');
