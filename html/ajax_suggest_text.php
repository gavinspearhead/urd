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
    $input_arr = array();
    $q_search = parse_search_string($text, 'title', '', '', $like, $input_arr);
    $q_adult = subcats_adult($db, $is_adult, $cat);
    $q_cat = '';
    if ($cat != '') {
        $q_cat = 'AND "category" = :cat';
        $input_arr [':cat'] = $cat;
    }
    $sql = "\"title\", \"spotid\" AS \"setid\" FROM spots WHERE 1=1 $q_search $q_cat $q_adult ORDER BY \"stamp\" DESC";
    $res = $db->select_query($sql, 16, $input_arr); 
    if ($res === FALSE) {
        $res = array();
    }
    return $res;
}

function get_group_suggestions(DatabaseConnection $db, $text, $group_id, $is_adult)
{
    $like = $db->get_pattern_search_command('like');
    $input_arr = array();
    $q_adult = '';
    $q_adult_join = '';
    $text = str_replace('%', ' ', $text);
    $q_search = parse_search_string($text, 'subject', '', '', $like, $input_arr);
    if (!$is_adult) {
        $q_adult_join = 'LEFT JOIN groups ON groups."ID" = setdata."groupID"';
        $q_adult = 'AND groups.adult != ' . ADULT_ON;
    }
    $q_group = '';
    if (is_numeric($group_id) && $group_id != 0) {
        $q_group = 'AND "groupID" = :group';
        $input_arr[':group'] = $group_id;
    }
    $sql = "\"subject\" AS \"title\", \"ID\" AS \"setid\" FROM setdata $q_adult_join WHERE 1=1 $q_search $q_group $q_adult ORDER BY \"date\" DESC";
    //echo $q_search;
    $res = $db->select_query($sql, 16, $input_arr); 
    if ($res === FALSE) {
        $res = array();
    }
    return $res;
}

function get_rss_suggestions(DatabaseConnection $db, $text, $feed_id, $is_adult)
{
    $like = $db->get_pattern_search_command('like');
    $text = str_replace('%', '', $text);
    $input_arr = array(':text' => "%$text%");
    $q_adult = '';
    $q_adult_join = '';
    if (!$is_adult) {
        $q_adult_join = 'LEFT JOIN rss_urls ON rss_urls."id" = rss_sets."rss_id"';
        $q_adult = 'AND rss_urls.adult != :adult';
        $input_arr[':adult'] = ADULT_ON;
    }
    $q_feed = '';
    if (is_numeric($feed_id) && $feed_id != 0) {
        $q_feed = 'AND "rss_id" = :feed';
        $input_arr[':feed']= $feed_id;
    }
    $sql = "\"setname\" AS \"title\", \"setid\" AS \"setid\" FROM rss_sets $q_adult_join WHERE \"setname\" $like :text $q_feed $q_adult ORDER BY \"timestamp\" DESC";
    $res = $db->select_query($sql, 16, $input_arr); 
    if ($res === FALSE) {
        $res = array();
    }
    return $res;
}


try {
    $text = get_request('text', '');
    $type = get_request('type', '');
    $adult = urd_user_rights::is_adult($db, $userid);
    switch($type) { 
        case USERSETTYPE_SPOT:
            $cat = get_request('cat', '');
            $suggestions = get_spot_suggestions($db, $text, $cat, $adult);
            break;
        case USERSETTYPE_GROUP:
            $group = substr(get_request('group', ''), 6);
            $suggestions = get_group_suggestions($db, $text, $group, $adult);
            break;
        case USERSETTYPE_RSS:
            $feed_id = substr(get_request('feed', ''), 5);
            $suggestions = get_rss_suggestions($db, $text, $feed_id, $adult);
            break;
        default:
            $suggestions = array();
            break;
    }
    init_smarty();
    foreach ($suggestions as $k => &$s) { 
        $suggestions[$k] = array('title'=> strip_tags(preg_replace(array('/&#?[a-z0-9]{2,8};/i', '/[;.,+]/i'), ' ', $s['title'])), 'setid'=> $s['setid']);
    }
    $smarty->assign('suggestions', $suggestions);
    $content = $smarty->fetch('ajax_suggest_text.tpl');
    return_result(array('content' => $content, 'counter' => count($suggestions)));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
