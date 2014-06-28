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

function smarty_modifier_capitalise($string, $uc_digits = FALSE)
{
    smarty_modifier_capitalise_ucfirst(null, $uc_digits);

    return htmlentities(preg_replace_callback('!\'?\b\w(\w|\')*\b!', 'smarty_modifier_capitalise_ucfirst', html_entity_decode($string, ENT_COMPAT,'UTF-8' )), ENT_COMPAT,'UTF-8');
}

function smarty_modifier_capitalise_ucfirst($string, $uc_digits = null)
{
    static $_uc_digits = FALSE;

    if (isset($uc_digits)) {
        $_uc_digits = $uc_digits;

        return;
    }

    if(substr($string[0], 0, 1) != "'" && !preg_match("!\d!", $string[0]) || $_uc_digits) {

        return mb_convert_case($string[0], MB_CASE_TITLE);
    }
    else {
        return $string[0];
    }
}
