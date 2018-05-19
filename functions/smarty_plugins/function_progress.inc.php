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

function smarty_function_urd_progress($params, $smarty)
{
    $width = isset($params['width']) ? $params['width'] : 100;
    $colour = isset($params['colour']) ? $params['colour'] : 'grey';
    $text = isset($params['text']) ? $params['text'] : '';
    $background = isset($params['background']) ? $params['background'] : '';
    $classes = isset($params['classes']) ? $params['classes'] : '';
    $complete = isset($params['complete']) ? $params['complete'] : 100; // percentage
    $complete = max(min($complete, 100), 0);
    $width_done = round($width * $complete / 100);
    $width_remain = $width - $width_done;

    // style classes
    $left = isset($params['left']) ? $params['left'] : 'progress_left';
    $right = isset($params['right']) ? $params['right'] : 'progress_right';
    $middle = isset($params['middle']) ? $params['middle'] : 'progress_middle';
    $done = isset($params['done']) ? $params['done'] : 'progress_done';
    $remain = isset($params['remain']) ? $params['remain'] : 'progress_remain';

    $random_id = mt_rand();

    $style = '<div style="display:inline">' . '<div style="display:table-cell">';
    $style .= "<div class=\"progressbar $background $classes\" style=\"width:{$width}px\">";
    $style .= "<span class=\"{$colour}\" style=\"width:$complete%;\">";
    $style .= '</span> ' . ' </div> ' . ' </div>' . '<div style="display:table-cell">'. '<span class="progress_text">';
    if ($text != '') {
        $style .= "&nbsp;$text";
    }
    $style .= '</span>' . '</div>'. '</div>';

    return $style ;

}

