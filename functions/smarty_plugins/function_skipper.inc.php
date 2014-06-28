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
    $rv = '';
    $current_page = isset($params['current']) ? $params['current'] : 0;
    $last_page = isset($params['last']) ? $params['last'] : 0;
    $pages = isset($params['pages']) ? $params['pages'] : 0;
    $class = isset($params['class']) ? $params['class'] : 'ps';
    $js = isset($params['js']) ? $params['js'] : '';
    $extra_class = isset($params['extra_class']) ? $params['extra_class'] : '';

    $table_class = ($class == 'ps') ? 'pageskip' : 'pageskipbottom';

    $start_page = $current_page - 10;
    if ($start_page <= 0) {
        $start_page = 1;
    }
    $stop_page = $start_page + 20;
    if ($stop_page > $last_page) {
        $stop_page = $last_page;
    }
    $start_page = $stop_page - 20;
    if ($start_page < 1) {
        $start_page = 1;
    }
    $rv .= "<table class=\"$table_class $extra_class\"><tr>";

    if (($current_page > 1) ) {
        $previous_page = find_page($pages, $current_page - 1);
        if ($previous_page !== FALSE) {
            $rv .= '<td class="' . $class . '_5"' .
                'onmouseover="$(this).toggleClass(\'ps_hover\');" onmouseout="$(this).toggleClass(\'ps_hover\');" onclick="'. $js . '(\'' .
                $previous_page['offset'] . '\');">' .
                ' &lt; ' .
                '</td>';
        }
    }

    foreach ($pages as $page) {
        if ($page['number'] == 1) {
            $rv .= '<td class="' . $class . '_' . $page['distance'] . '"' .
                'onmouseover="$(this).toggleClass(\'ps_hover\');" onmouseout="$(this).toggleClass(\'ps_hover\');" onclick="'. $js . '(\'' .
                $page['offset'] . '\');">' .
                $page['number'] .
                '</td>';

            if ($start_page > 1) {
                $rv .= '<td class="spacer">&nbsp;</td>';
            }
        } elseif ($page['number'] == $last_page) {
            if ($stop_page < $last_page) {
                $rv .= '<td class="spacer">&nbsp;</td>';
            }
            $rv .= '<td class="' . $class . '_' . $page['distance'] . '"' .
                'onmouseover="$(this).toggleClass(\'ps_hover\');" onmouseout="$(this).toggleClass(\'ps_hover\');" onclick="'. $js . '(\''
                . $page['offset'] . '\');">'
                . $page['number'] .
                '</td>';

        } elseif ($page['number'] >= $start_page && $page['number'] <= $stop_page) {
            $rv .= '<td class="' . $class . '_' . $page['distance'] . '"' .
                'onmouseover="$(this).toggleClass(\'ps_hover\');" onmouseout="$(this).toggleClass(\'ps_hover\');" onclick="'. $js . '(\'' .
                $page['offset'] . '\');">' .
                $page['number'] . '</td>';
        }
    }
    if (($current_page < $last_page) ) {
        $next_page = find_page($pages, $current_page + 1);
        if ($next_page !== FALSE) {
            $rv .= '<td class="' . $class . '_5"' .
                'onmouseover="$(this).toggleClass(\'ps_hover\');" onmouseout="$(this).toggleClass(\'ps_hover\');" onclick="'. $js . '(\'' .
                $next_page['offset'] . '\');">' .
                ' &gt; ' .
                '</td>';
        }
    }

    $rv .= '</tr></table>';

    return $rv;
}
