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

function find_page($pages, $number)
{
    foreach ($pages as $page) {
        if ($page['number'] == $number) {
            return $page;
        }
    }

    return FALSE;
}

function smarty_function_urd_skipper($params, &$smarty)
{
    $rv = array();
    $current_page = isset($params['current']) ? $params['current'] : 0;
    $size = isset($params['size']) ? $params['size'] : 30;
    $last_page = isset($params['last']) ? $params['last'] : 0;
    $pages = isset($params['pages']) ? $params['pages'] : 0;
    $position = (isset($params['position']) && $params['position'] == 'bottom') ? $params['position'] : 'top';
    $class = ( $position == 'bottom' )? 'psb' : 'ps';
    $js = isset($params['js']) ? $params['js'] : '';
    $extra_class = isset($params['extra_class']) ? $params['extra_class'] : '';
    $table_class = ($position != 'bottom') ? 'pageskip' : 'pageskipbottom';

    $start_page = $current_page - (floor($size/3));
    if ($start_page <= 0) {
        $start_page = 1;
    }
    $stop_page = $start_page + (floor($size/3) * 2);
    if ($stop_page > $last_page) {
        $stop_page = $last_page;
    }
    $start_page = $stop_page - (floor($size/3) * 2);
    if ($start_page < 1) {
        $start_page = 1;
    }

    if (($current_page > 1) ) {
        $previous_page = find_page($pages, $current_page - 1);
        if ($previous_page !== FALSE) {
            $rv[] = array($class . '_5', $previous_page['offset'], ' &lt; ');
        }
    }

    foreach ($pages as $page) {
        if ($page['number'] == 1) {
            $rv[] = array($class . '_' . $page['distance'], $page['offset'], $page['number']);

            if ($start_page > 1) {
                $rv[] = array();
            }
        } elseif ($page['number'] == $last_page) {
            if ($stop_page < $last_page) {
                $rv[] = array();
            }
            $rv[] = array($class . '_' . $page['distance'], $page['offset'], $page['number']);
        } elseif ($page['number'] >= $start_page && $page['number'] <= $stop_page) {
            $rv[] = array($class . '_' . $page['distance'], $page['offset'], $page['number']);
        }
    }
    if (($current_page < $last_page) ) {
        $next_page = find_page($pages, $current_page + 1);
        if ($next_page !== FALSE) {
            $rv[] = array($class . '_5', $next_page['offset'], ' &gt; ');
        }
    }
    
    $first = TRUE;
    $html = "<table class=\"$table_class $extra_class\"><tr>";

    foreach($rv as $index => $line) {
        if ($line == array()) {
            $html .= '<td class="spacer">&nbsp;</td>';
            $first = TRUE;
        } else {
            $html .= '<td class="' . $line[0];
            if ($first) {
                if ($position == 'top') { $html .= ' round_left'; } 
                else { $html .= ' round_left_bottom'; } 
                $first = FALSE;
            }
            if (!isset($rv[$index + 1]) || $rv[$index + 1] == array() ) { 
                 if ($position == 'top') { $html .= ' round_right'; } 
                 else { $html .= ' round_right_bottom'; }
            }
            $html .= '" ' .
                'onmouseover="$(this).toggleClass(\'ps_hover\');" onmouseout="$(this).toggleClass(\'ps_hover\');" onclick="' . $js . '(\'' .
                $line[1] . '\');">' .
                $line[2] .
                '</td>';
        }
    }

    $html .= '</tr></table>';

    return $html;
}
