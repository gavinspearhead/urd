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
 * $LastChangedDate: 2012-07-08 13:46:01 +0200 (Sun, 08 Jul 2012) $
 * $Rev: 2567 $
 * $Author: gavinspearhead $
 * $Id: show_functions.php 2567 2012-07-08 11:46:01Z gavinspearhead $
 */

function smarty_function_urd_popup($params, $smarty)
{
    global $LN;
    $text = $params['text'];

    if (empty($text)) {
        trigger_error('popup: attribute \'text\' required');

        return FALSE;
    }
    $type = isset($params['type']) ? $params['type'] : '';
    $xpos = (isset($params['xpos'])) ? $params['xpos'] : 'undefined';
    $ypos = (isset($params['ypos']) ) ? $params['ypos'] : 'undefined';
    $caption = isset($params['caption']) ? $params['caption'] : $LN['help'];
    $caption = strip_tags($caption, '<p><a><i><b><br>');

    if ($type == 'small') {
        $text = strip_tags($text, '<p><a><i><b><br>');
        $retval = "onmouseover=\"javascript:show_small_help('" . htmlentities($text, ENT_QUOTES) . "', event);\" onmouseout=\"javascript:hide_small_help();\"";
    } else {
        $retval = "onmouseover=\"javascript:show_help('$text', '$caption', $(this), $xpos, $ypos);\" onmouseout=\"javascript:hide_help();\"";
    }

    return $retval;
}
