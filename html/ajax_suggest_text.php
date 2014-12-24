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
 * $LastChangedDate: 2014-06-15 00:41:23 +0200 (zo, 15 jun 2014) $
 * $Rev: 3095 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_post_message.php 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathaet = realpath(dirname(__FILE__));

require_once "$pathaet/../functions/ajax_includes.php";

function subcats_adult(DatabaseConnection $db, $is_adult, $cat)
{
    $Qsubcat = '';
    if (!$is_adult) {
        $search_type = $db->get_pattern_search_command('LIKE');
        $adult_subcats = SpotCategories::$adult_categories;
        $Qsubcat .= ' AND NOT ' . '(' . ' 0 = 1 ';
        foreach ($adult_subcats as $s) {
            if ($s[0] == $cat) {
                $sc = $s[1];
                $sci = $s[1] . $s[2];
                $Qsubcat .= ' OR ' . "( \"subcat$sc\" {$this->search_type} '%$sci|%' ) ";
            } else {
                $hcat = $s[0];
                $sc = $s[1];
                $sci = $s[1] . $s[2];
                $Qsubcat .= ' OR ' . "(\"category\" = '$hcat' AND \"subcat$sc\" {$search_type} '%$sci|%' ) ";
            }
        }
        $Qsubcat .= ')';
    }
    return $Qsubcat;
}


function get_spot_suggestions(DatabaseConnection $db, $text, $cat, $is_adult)
{
    $like = $db->get_pattern_search_command('like');
    $text = str_replace('%', '', $text);
    $input_arr = array(':text' => "%$text%");
    $q_adult = subcats_adult($db, $is_adult, $cat);
    $q_cat = '';
    if ($cat != '') {
        $q_cat = 'AND "category" = :cat';
        $input_arr [':cat'] = $cat;
    }
    $sql = "\"title\" FROM spots WHERE \"title\" $like :text $q_cat $q_adult ORDER BY \"stamp\" DESC";
    $res = $db->select_query($sql, 16, $input_arr); 
    if ($res === FALSE) {
        $res = array();
    }
    return $res;
}


function get_group_suggestions(DatabaseConnection $db, $text, $is_adult)
{
    $like = $db->get_pattern_search_command('like');
    $text = str_replace('%', '', $text);
    $input_arr = array(':text' => "%$text%");
    $q_adult = '';
    $sql = "\"subject\" AS \"title\" FROM setdata WHERE \"subject\" $like :text $q_adult ORDER BY \"date\" DESC";
    $res = $db->select_query($sql, 16, $input_arr); 
    if ($res === FALSE) {
        $res = array();
    }
    return $res;
}


function get_rss_suggestions(DatabaseConnection $db, $text, $is_adult)
{
    $like = $db->get_pattern_search_command('like');
    $text = str_replace('%', '', $text);
    $input_arr = array(':text' => "%$text%");
    $q_adult = '';
    $sql = "\"setname\" AS \"title\" FROM rss_sets WHERE \"setname\" $like :text $q_adult ORDER BY \"timestamp\" DESC";
    $res = $db->select_query($sql, 16, $input_arr); 
    if ($res === FALSE) {
        $res = array();
    }
    return $res;
}




try {
    $text = get_request('text', '');
    $cat = get_request('cat', '');
    $type = get_request('type', '');
    $adult = urd_user_rights::is_adult($db, $userid);
    switch($type) { 
        case USERSETTYPE_SPOT:
            $suggestions = get_spot_suggestions($db, $text, $cat, $adult);
            break;
        case USERSETTYPE_GROUP:
            $suggestions = get_group_suggestions($db, $text, $adult);
            break;
        case USERSETTYPE_RSS:
            $suggestions = get_rss_suggestions($db, $text, $adult);
            break;
        default:
            $suggestions = array();
            break;
    }
    init_smarty('', 0);
    foreach ($suggestions as $k => &$s) { 
        $suggestions[$k] = strip_tags(preg_replace(array('/&#?[a-z0-9]{2,8};/i', '/[;.,+]/i'),' ',$s['title']));
    }
    $smarty->assign('suggestions', $suggestions);
    $content = $smarty->fetch('ajax_suggest_text.tpl');
    return_result(array('content' => $content, 'counter' => count($suggestions)));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
