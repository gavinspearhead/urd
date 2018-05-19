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

function smarty_function_urd_checkbox($params, $smarty)
{
    $value = isset($params['value']) ? $params['value'] : '';
    $classes = isset($params['classes']) ? $params['classes'] : '';
    $id = isset($params['id']) ? $params['id'] : '';
    $name = isset($params['name']) ? $params['name'] : '';
    $tristate = isset($params['tristate']) ? $params['tristate'] : '';
    $post_js = isset($params['post_js']) ? $params['post_js'] : '';
    $data = isset($params['data']) ? $params['data'] : '';
    $before = isset($params['before']) ? TRUE : FALSE;
    $readonly = (isset($params['readonly']) && in_array(strtolower($params['readonly']), array('1', 'true', 'on', 'yes'))) ? TRUE : FALSE;
    if ($readonly) {
        $buttonlike = '';
    } else {
        $buttonlike = 'buttonlike';
    }
    $rv = "<div class=\"inline $classes\"  ";
    if (!$readonly) {
        $rv .= "onclick=\"javascript:change_checkbox('$id'";
        if ($tristate) {
            $rv .= ", 'checkbox_tri'";
        }
        $rv .= "); $post_js\" ";

    }
    $rv .= '>';
    $rv .= "<input type=\"hidden\" name=\"$name\" id=\"$id\" value=\"$value\"/>\n";
    if ($data != '' && $before) {
        $rv .= "<div class=\"floatleft buttonlike\">$data</div>";
    }
    if ($value == 0) {
        $cb_class = 'checkbox_off';
    } elseif ($value == 1) {
        $cb_class = 'checkbox_on';
    } elseif ($value == 2) {
        $cb_class = 'checkbox_tri';
    }

    $rv .= "<div class=\"floatleft $cb_class iconsizeplus $buttonlike\" id=\"{$id}_img\">";
    $rv .= "</div>\n";
    if ($data != '' && !$before) {
        $rv .= "<div class=\"floatleft buttonlike\">&nbsp;" . $data . '&nbsp;</div>';
    }
    $rv .= '</div>';

    return $rv;
}
