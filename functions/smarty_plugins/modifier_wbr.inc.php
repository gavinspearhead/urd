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


function sm_insert_wbr($str, $size = 64)
{
    assert(is_numeric($size));
    $l = strlen($str);
    $str_new = '';
    $t = 0;
    $in_tag = 0;
    for ($i = 0; $i < $l; $i++) {
        if ($str[$i] == '<') {
            $t = 0;
            $in_tag++;
        } elseif ($str[$i] == '>') {
            $t = 0;
            $in_tag--;
        }
        $str_new .= $str[$i];
        if ($in_tag > 0) {
            continue;
        }
        if (ctype_space($str[$i])) {
            $t = 0;
        } else {
            $t++;
            if ($t >= $size) {
                $str_new .= '<wbr>';
                $t = 0;
            }
        }
    }

    return $str_new;
}


function smarty_modifier_wbr($string)
{

    return sm_insert_wbr($string, 32);
}

