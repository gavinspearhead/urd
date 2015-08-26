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
 *n $LastChangedDate: 2010-09-21 19:28:38 +0200 (Tue, 21 Sep 2010) $
 * $Rev: 1748 $
 * $Author: gavinspearhead $
 * $Id: ajax_stats.php 1748 2010-09-21 17:28:38Z gavinspearhead $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
error_reporting(0);

$__auth = 'silent';
$pathstat = realpath(dirname(__FILE__));

require_once "$pathstat/../functions/html_includes.php";

$types = urd_modules::get_stats_enabled_modules($db);

$nametypes = array();
$nametypes[stat_actions::DOWNLOAD] = 'stats_dl';
$nametypes[stat_actions::PREVIEW]  = 'stats_pv';
$nametypes[stat_actions::IMPORTNZB]= 'stats_im';
$nametypes[stat_actions::GETNZB]   = 'stats_gt';
$nametypes[stat_actions::WEBVIEW]  = 'stats_wv';
$nametypes[stat_actions::POST]     = 'stats_ps';


class colour_map
{
    public static function get_colour_map_cache($stylesheet)
    {
        //return FALSE;
        if (isset($_SESSION['colourmap'][$stylesheet]) &&
                isset($_SESSION['colourmap'][$stylesheet]['color1']) &&
                isset($_SESSION['colourmap'][$stylesheet]['color2']) &&
                isset($_SESSION['colourmap'][$stylesheet]['color3']) &&
                isset($_SESSION['colourmap'][$stylesheet]['color4']) &&
                isset($_SESSION['colourmap'][$stylesheet]['color5']) &&
                isset($_SESSION['colourmap'][$stylesheet]['color6']) &&
                isset($_SESSION['colourmap'][$stylesheet]['color7']) &&
                isset($_SESSION['colourmap'][$stylesheet]['color8']) &&
                isset($_SESSION['colourmap'][$stylesheet]['color9']) &&
                isset($_SESSION['colourmap'][$stylesheet]['color10']) &&
                isset($_SESSION['colourmap'][$stylesheet]['color11'])) {
            return $_SESSION['colourmap'][$stylesheet];
        } else {
            return FALSE;
        }
    }

    public static function get_colour_map(DatabaseConnection $db, $userid)
    {
        assert(is_numeric($userid));
        $style = get_active_stylesheet($db, $userid);
        $template = get_template($db, $userid);
        list($tpl_dir) = get_smarty_dirs($template);
        $stylesheet = $tpl_dir . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $style . DIRECTORY_SEPARATOR . $style . '.css';
        $lines = file($stylesheet);
        if ($lines === FALSE) {
            $lines = array();
        }
        $colours = array();

        $colourmap = self::get_colour_map_cache($stylesheet);
        if ($colourmap !== FALSE) {
            return $colourmap;
        }

        foreach ($lines as $line) {
            if (preg_match('/stat_colour([0-9]+)\s*:\s*([0-9]+),\s*([0-9]+),\s*([0-9]+)/', $line, $matches)) {
                if (isset($matches[4])) {
                    $colours[$matches[1]] = array ($matches[2], $matches[3],$matches[4]);
                }
            }
        }

        $colourmap = array(
                'black' => array(0, 0, 0),
                'white' => array(255, 255, 255),
                'gray'  => array(190, 190, 190),
                'red'   => array(255, 0, 0),
                'blue'  => array(0, 0, 255),
                'dimgray' => array(55,55,55),
                // the default colors
                'color1' => array(140, 60, 0),
                'color2' => array(180, 150, 60),
                'color3' => array(200, 200, 0),
                'color4' => array(140, 210, 220),
                'color5' => array(0, 200, 0),
                'color6' => array(0, 200, 100),
                'color7' => array(0, 200, 200),
                'color8' => array(0, 100, 200),
                'color9' => array(0, 0, 200),
                'color10' => array(0,0,0),
                'color11' => array(255,255,255),
                );

        foreach ($colours as $idx => $col) {
            // override them with the ones we just read
            $colourmap['color' . $idx] = $col;
        }
        $_SESSION['colourmap'][$stylesheet] = $colourmap;

        return $colourmap;
    }
    public static function get_rgb_codes(DatabaseConnection $db, $userid, $alpha)
    { 
        $colors = array();
        $cm = self::get_colour_map($db, $userid);
        foreach ($cm as $k=>$c) {
            if (substr($k, 0, 5) == 'color') {
                $colors [] = "rgba({$c[0]},{$c[1]},{$c[2]},$alpha)";
            }
        }
        return $colors;
    }
}

$isadmin = urd_user_rights::is_admin($db, $userid);

$possibletypes = array('activity', 'spots_details', 'supply', 'blank', 'spots_subcat');
$possiblesubtypes = array('stats_dl','stats_pv','stats_im','stats_gt','stats_wv','stats_ps', '');
$type = get_request('type', 'blank');
$subtype = get_request('subtype', 'stats_dl');
$period = get_request('period', '');
$year   = get_request('year', '0');
$month = get_request('month', 1);
$sizeorcount = get_request('source', 'size');
if (!in_array($type, $possibletypes)) {
    return_result(array('error'=>'Invalid type specified.' . $type));
}
if (!in_array($subtype, $possiblesubtypes)) {
    return_result(array('error'=>'Invalid type specified.'. $subtype));
}
if ($sizeorcount != 'size') {
    $sizeorcount = 'count';
}

function make_graph_data(DatabaseConnection $db, $userid, array $data) 
{
    $new_data = array(
        'fillcolours'=> colour_map::get_rgb_codes($db, $userid, 0.6),
        'strokecolours'=> colour_map::get_rgb_codes($db, $userid, 1),
        'titles'=> array(),
    );
    $new_data = array_merge($new_data, $data);

    return $new_data;
}

function create_spot_data(DatabaseConnection $db, $userid, $graphtitle)
{
    assert(is_numeric($userid));
    global $LN;
    $data = get_spots_stats($db);
    $stat_data = $labels = array();
    $cats = SpotCategories::get_categories();

    foreach ($data as $key => $row) {
       $stat_data[] = $row;
       $labels[] = $LN[$cats[$key]];

    }
    $data = make_graph_data($db, $userid, array(
        'type'=> 'pie',
        'data'=> $stat_data,
        'fillcolours'=> colour_map::get_rgb_codes($db, $userid, 0.7),
        'labels'=>$labels,
        'title'=> $graphtitle,
    ));

    return_result($data);
}

function get_spots_stats_by_dow(DatabaseConnection $db)
{
    global $LN;
    $time_stamp = $db->get_dow_timestamp('"stamp"');

    $sql = "count(*) AS cnt, $time_stamp AS dow, \"category\" FROM spots GROUP BY $time_stamp, \"category\" ";
    $res = $db->select_query($sql);
    if ($res === FALSE) {
        $res = array();
    }
    $stats = array();
    $titles = array();
    foreach (range(1, 4) as $i) {
            $stats[ $i ] = array_fill(0,7,0);
    }
    foreach (range(1,7) as $i) {
        $labels [$i -1 ] = $LN['short_day_names'][$i];
    }
    $cats = SpotCategories::get_categories() ;
    foreach ($res as $row) {
        $m = $row['dow'];
        $c = $row['category'] + 1;

        $stats[ $c][$m] = $row['cnt'];
        $titles[$c-1] = $LN[$cats[$c-1]];
    }

    return (array(array_values($stats), $labels, $titles));
}

function get_spots_stats_by_period(DatabaseConnection $db, $period)
{
    global $LN;
    if ($period == 'dow') {
        return get_spots_stats_by_dow($db);
    }
    $time_stamp = $db->get_timestamp('"stamp"');
    $time_extract = $db->get_extract($period, $time_stamp);

    $sql = "count(*) AS cnt, $time_extract AS mnth, \"category\" FROM spots GROUP BY $time_extract, \"category\"";
    $res = $db->select_query($sql);
    if ($res === FALSE) {
        $res = array();
    }
    $stats = array();
    $labels = array();
    $titles = array();
    if ($period == 'month') {
        foreach (range(1,4) as $i) {
            $stats[ $i ] = array_fill(0, 12 , 0);
        }
        foreach (range(1,12) as $i) {
            $labels [] = html_entity_decode($LN['short_month_names'][$i]);
        }   
    } elseif ($period == 'week') {
        $max_week = 0;
        foreach (range(25, 31) as $r) {
            $max_week = max($max_week, (int) date('W', mktime(0, 0, 0, 12, $r)));
        }
        foreach (range(1,4) as $i) {
            $stats[ $i ] = array_fill(0, $max_week, 0);
        }
        foreach (range(0, $max_week) as $i) {
            $labels [] = $i;
        }
    } elseif ($period == 'hour') {
        foreach (range(1, 4) as $i) {
            $stats[ $i ] = array_fill(0,23.0);
        }
        foreach (range(0, 23) as $i) {
            $labels [] = $i;
        } 
    }
    $cats = SpotCategories::get_categories() ;
    foreach ($res as $row) {
        $m = $row['mnth'];
        $c = $row['category'] + 1;

        $stats[ $c ] [$m] = (int)$row['cnt'];
        $titles[$c-1] = $LN[$cats[$c-1]];
    }
    return array(array_values($stats), $labels, $titles);
}

function create_spot_graph_period(DatabaseConnection $db, $userid, $graphtitle, $period)
{
    assert(is_numeric($userid));
    global $LN;
    list($stat_data, $labels, $titles) = get_spots_stats_by_period($db, $period);
    $data = make_graph_data($db, $userid, array(
            'type'=> 'stackedbar',
            'data'=> $stat_data,
            'labels'=>$labels,
            'title'=> $graphtitle,
            'titles'=> $titles
            ));

    return_result($data);
}

function get_sets_stats_date_day(DatabaseConnection $db, $type, $year, $month, $days_per_month)
{
    assert(is_numeric($year) && is_numeric($month));
    $ystr = $db->get_extract('year', '"timestamp"');
    $monthstr = $db->get_extract('month', '"timestamp"');
    $daystr = $db->get_extract('day', '"timestamp"');
    $qry = "sum(\"value\") AS \"spot_sum\", $daystr AS \"day\" FROM stats WHERE \"action\"=:type AND $ystr=:year AND $monthstr=:month GROUP BY $daystr ORDER BY \"day\" DESC";
    $res = $db->select_query($qry, array(':type'=>$type, ':year'=>$year, ':month'=>$month));
    $years = array();
    if (is_array($res)) {
        foreach ($res as $row) {
            $years[ $row['day'] ] = $row ['spot_sum'];
        }
    } else {
        $years = array(0 => 0);
    }
    foreach (range(0,$days_per_month) as $i) {
        if (!isset($years[$i])) { 
            $years[$i] = 0;
        }
    }
    ksort($years); 
    return $years;
}

function get_sets_stats_date_month(DatabaseConnection $db, $type, $year)
{
    assert(is_numeric($year));
    $ystr = $db->get_extract('year', '"timestamp"');
    $monthstr = $db->get_extract('month', '"timestamp"');
    $qry = "sum(\"value\") AS \"spot_sum\", $monthstr AS \"month\" FROM stats WHERE \"action\"=:type AND $ystr = :year GROUP BY $monthstr ORDER BY \"month\" DESC";
    $res = $db->select_query($qry, array(':type'=>$type, ':year'=>$year));
    $years = array();

    if (is_array($res)) {
        foreach ($res as $row) {
            $years[ $row['month'] -1 ] = $row['spot_sum'];
        }
            } else {
        $years = array(0 => 0);
    }
    foreach (range(0,11) as $i) {
        if (!isset($years[$i])) { 
            $years[$i] = 0;
        }
    }

    ksort($years); 
    return $years;
}

function get_sets_stats_date(DatabaseConnection $db, $type)
{
    $ystr = $db->get_extract('year', '"timestamp"');
    $qry = "sum(\"value\") AS \"spot_sum\", $ystr AS \"year\" FROM stats WHERE \"action\"=:type GROUP BY $ystr ORDER BY \"year\" DESC";
    $res = $db->select_query($qry, array(':type'=>$type));
    $years = array();

    if (is_array($res)) {
        foreach ($res as $row) {
            $years[ $row['year'] ] = (int)$row['spot_sum'];
        }
    } else {
        $years = array(0 => 0);
    }

    return $years;
}

function create_spot_supply_year(DatabaseConnection $db, $userid, $graphtitle)
{
    global $LN;
    assert(is_numeric($userid));
    $inputdata = array();
    $data1 = (get_sets_stats_date($db, stat_actions::SPOT_COUNT));
    $data2 = (get_sets_stats_date($db, stat_actions::SET_COUNT));
    $data3 = (get_sets_stats_date($db, stat_actions::RSS_COUNT));
    $labels = array_keys($data1);
        
    $data = make_graph_data($db, $userid, array(
        'type'=> 'stackedbar',
        'data'=> array(array_values($data1), array_values($data2), array_values($data3)),
        'labels'=>$labels,
        'title'=> $graphtitle,
        'titles'=>array($LN['menuspots'], $LN['menugroupsets'], $LN['menursssets'])));
    return_result($data);
}

function create_spot_graph_date(DatabaseConnection $db, $userid, $graphtitle, $year= NULL, $month=NULL)
{
    global $LN;
    assert(is_numeric($userid));
    $inputdata = array();
    $data = make_graph_data($db, $userid, array(
        'type'=> 'stackedbar',
        'fillcolours'=> colour_map::get_rgb_codes($db, $userid, 0.6),
        'strokecolours'=> colour_map::get_rgb_codes($db, $userid, 1),
        'title'=> $graphtitle,
        'titles'=>array($LN['menuspots'], $LN['menugroupsets'], $LN['menursssets'])));

    if ($month === NULL || !is_numeric($month)) {
        $data1 = get_sets_stats_date_month($db, stat_actions::SPOT_COUNT, $year);
        $data2 = get_sets_stats_date_month($db, stat_actions::SET_COUNT, $year);
        $data3 = get_sets_stats_date_month($db, stat_actions::RSS_COUNT, $year);
        $labels = array_keys($data1);

        foreach($labels as $key => $val) {
            $data ['labels'][] = $LN['short_month_names'][$val+1];
        }
        $data ['data'] = array(array_values($data1), array_values($data2), array_values($data3));
    } else {
        $days_per_month = date('t', mktime(0, 0, 0, $month, 1, $year));
        $data1 = get_sets_stats_date_day($db, stat_actions::SPOT_COUNT, $year, $month, $days_per_month);
        $data2 = get_sets_stats_date_day($db, stat_actions::SET_COUNT, $year, $month, $days_per_month);
        $data3 = get_sets_stats_date_day($db, stat_actions::RSS_COUNT, $year, $month, $days_per_month);
        $data ['data'] = array(array_values($data1), array_values($data2), array_values($data3));
        foreach(range(1, $days_per_month) as $d) {
            $dow = get_dow($d, $month, $year);
            $data['labels'] [] = html_entity_decode(get_array($LN['short_day_names'], $dow, date('D', mktime(0, 0, 0, $month, $d, $year)))) ." $d";
        }
        
    }
    return_result($data);
}

function spots_per_subcat(DatabaseConnection $db, $userid, $cat, $subcat)
{
    assert(is_numeric($userid));
    global $width, $height, $pathstat;
    if (!in_array($subcat, array('a', 'b', 'c', 'd', 'z'))) {
        $data = array(
            'type'=> 'empty',
            'data'=> array(),
            'colours'=> array(),
            'labels'=>array(),
            'title'=> '');
        return_result($data);
    }
    $sql = "\"subcat$subcat\" AS \"subcat\" FROM spots WHERE \"category\"=:cat";
    $limit = 0;
    $stats = SpotCategories::get_subcats_ids($cat, $subcat);
    $row_count = 20000;
    while (TRUE) {
        $res = $db->select_query($sql, $row_count, $limit, array(':cat'=>$cat));
        if (!is_array($res)) {
            break;
        }
        $limit += $row_count;

        // Loop through all subcats for this type:
        foreach ($res as $row) {
            $subcats = explode('|', $row['subcat']);

            foreach ($subcats as $s) {
                if (isset($stats[$s])) {
                    $stats[$s]++;
                }
            }
        }
    }
    
    $s = array();
    foreach ($stats as $k => $v) {
        $subcat_ln = trim(to_ln(SpotCategories::Cat2Desc($cat, $k)));
        if ($subcat_ln == '??') {
            if ($v > 0) {
                $subcat_ln = (to_ln('unknown') . " - $cat$k");
            } else {
                continue;
            }
        }
        $s[$subcat_ln] = array(substr(html_entity_decode($subcat_ln, ENT_COMPAT, 'UTF-8'), 0, 32), $v);

    }
    krsort($s);

    foreach ($s as $v){
        $data [] = $v[1];
        $labels [] = $v[0];
    }
    $data = make_graph_data($db, $userid, array(
        'type'=> 'horizontalbar',
        'data'=> array($data),
        'labels'=>$labels,
        'title'=> to_ln(SpotCategories::HeadCat2Desc($cat)),
        'titles'=>array()));
    return_result($data);
}

function get_stats_by_month(DatabaseConnection $db, $userid, $type, $admin, $year, $sizeorcount, $graphtitle)
{
    assert(is_numeric($userid));
    global $LN;
    $ystr = $db->get_extract('year', '"timestamp"');
    $mstr = $db->get_extract('month', '"timestamp"');
    $input_arr = array( ':type1' => $type);
    if ($year !== NULL) {
        $input_arr[':year1'] = $year;
        $qyear1 = "$ystr = :year1";
    } else {
        $qyear1 = '1=1';
    }
    $quser = '';
    if (!$admin) {
        $input_arr[':userid'] = $userid;
        $quser = ' AND users."ID" = :userid ';
    }
    
    $qry = <<<QRY1
         sum("value") AS "total", count("action") AS "counter", (CASE WHEN users."name" IS NULL OR users."name" = '' THEN '__anonymous' ELSE users."name" END) AS "name",
                $mstr AS "month", $ystr AS "year"
                FROM stats
                LEFT JOIN users ON users."ID" = stats."userid"
                WHERE $qyear1 AND "action" = :type1 $quser
                GROUP BY "name", $mstr, $ystr 
                ORDER BY "name", month, year
QRY1;

    $res = $db->select_query($qry, $input_arr);
    if (!is_array($res)) {
        $res = array();
    }
    $data = array();
    $users = array();
    $months = array();
    $maxval = 0;

    foreach($res as $row) {
        $year = $row['year'];
        $name = $row['name'];
        $size = $row['total'];
        $month = $row['month'];
        $count = $row['counter'];
        $users[$name] = $name;
        $months[$month] = $month;
        if ($sizeorcount == 'size') {
            $data[$name][$month] = $size;
            $maxval = max($maxval, $size);
        } else {
            $data[$name][$month] = $count;
        }
    }
    if ($admin) {
        foreach(get_all_users($db) as $u) {
            $users[ $u ] = $u;
        }
    }
    ksort($users);
    
    $suffix = '';
    if ($sizeorcount == 'size') {
        list($d, $suffix, $factor) = format_size($maxval, 'h', $LN['byte_short'], 1024, 0);
    } 
    $new_data = array();
    $row_template = array();
    foreach (range(1,12) as $month) {
        $row_template[$month] = 0;
    }
    foreach ($users as $user) {
        $new_data [$user] = $row_template;
    }
    foreach($data as $name => $mv) {
        $tmp_data = $row_template;
        foreach ($mv as $month => $v) {
            if ($sizeorcount == 'size') {
                $tmp_data[$month] = round(($v / $factor), 1);
            } else {
                $tmp_data[$month] = $v;
            }
        }
        $new_data[$name] = array_values($tmp_data);
    }
    $data = array_values($new_data);
    foreach($users as &$u) {
        if ($u == '__anonymous') {
            $u = $LN['unknown'];
        }
    }
    $legend = array_values($users);
    foreach (range(1,12) as $i) {
        $labels[] = html_entity_decode($LN['short_month_names'][$i]);
    }
    $data = make_graph_data($db, $userid, array(
        'type'=> 'stackedbar',
        'data'=> $data,
        'labels'=>$labels,
        'title'=> $graphtitle,
        'titles'=>$legend));
    if ($sizeorcount == 'size') {
        $data['yaxislabel'] = $suffix;
    } else {
        $data['yaxisminimuminterval'] = 1;
    }
    return_result($data);
}

function get_stats_by_year(DatabaseConnection $db, $userid, $type, $admin, $fromyear, $sizeorcount, $graphtitle)
{
    assert(is_numeric($userid));
    global $LN;

    $ystr = $db->get_extract('year', '"timestamp"');

    $max_cnt = 0;
    $quser = '';
    $input_arr = array(':fromyear1'=>$fromyear, ':type1'=>$type);
    if (!$admin) {
        $input_arr[':userid'] = $userid;
        $quser = ' AND users."ID" = :userid ';
    }
    $qry = <<<QRY1
         sum("value") AS "total", count("action") AS "counter",
                (CASE WHEN users."name" IS NULL OR users."name" = '' THEN '__anonymous' ELSE users."name" END) AS "name" ,
                $ystr AS "year" FROM stats LEFT JOIN users ON users."ID" = stats."userid"
                WHERE $ystr > :fromyear1
                AND "action" = :type1 $quser GROUP BY "name", $ystr
                 ORDER BY year, "name"
QRY1;
    $res = $db->select_query($qry, $input_arr);
    if (!is_array($res)) {
        $res = array();
    }
    $data = array();
    $users = array();
    $years = array();
    $maxval = 0;

    foreach($res as $row) {
        $year = $row['year'];
        $name = $row['name'];
        $size = $row['total'];
        $count = $row['counter'];
        $users[$name] = $name;
        $years[$year] = $year;
        if ($sizeorcount == 'size') {
            $data[$name][$year] = $size;
            $maxval = max($maxval, $size);
        } else {
            $data[$name][$year] = $count;
        }
    }
    if ($admin) {
        foreach(get_all_users($db) as $u) {
            $users[ $u ] = $u;
        }
    }
    ksort($users);
    
    $suffix = '';
    if ($sizeorcount == 'size') {
        list($d, $suffix, $factor) = format_size($maxval, 'h', $LN['byte_short'], 1024, 0);
    } 
    $new_data = array();
    $row_template = array();
    foreach ($years as $year) {
        $row_template[$year] = 0;
    }
    foreach ($users as $user) {
        $new_data [$user] = $row_template;
    }

    foreach($data as $name => $yv) {
        $tmp_data = $row_template;
        foreach ($yv as $year => $v) {
            if ($sizeorcount == 'size') {
                $tmp_data[$year] = round(($v / $factor), 1);
            } else {
                $tmp_data[$year] = $v;
            }
        }
        $new_data[$name] = array_values($tmp_data);
    }
    foreach($users as &$u) {
        if ($u == '__anonymous') {
            $u = $LN['unknown'];
        }
    }
    $data = array_values($new_data);
    $legend = array_values($users);
    $labels = array_keys($row_template);
    $data = make_graph_data($db, $userid, array(
        'type'=> 'stackedbar',
        'data'=> $data,
        'labels'=>$labels,
        'title'=> $graphtitle,
        'titles'=>$legend));
    if ($sizeorcount == 'size') {
        $data['yaxislabel'] = $suffix;
    } else {
        $data['yaxisminimuminterval'] = 1;
    }
    return_result($data);
}

function get_stats_by_day(DatabaseConnection $db, $userid, $type, $admin, $year, $month, $sizeorcount, $graphtitle)
{
    assert(is_numeric($userid));
    global $LN;

    $ystr = $db->get_extract('year', '"timestamp"');
    $mstr = $db->get_extract('month', '"timestamp"');
    $dstr = $db->get_extract('day', '"timestamp"');
    $now = time();

    if ($year === NULL) {
        $year = date('Y', $now);
    }
    if ($month === NULL) {
        $month = date('m', $now);
    }
    $input_arr = array(':type1'=>$type,  ':year1'=>$year, ':month1'=>$month);
    $timestamp = strtotime("$year/$month/1 00:00");
    $max_month = date('t', $timestamp);
    
    $qyear1 = "$ystr = :year1";
    $qmonth1 = "$mstr = :month1";

    $quser = '';
    if (!$admin) {
        $input_arr[':userid'] = $userid;
        $quser = ' AND users."ID" = :userid ';
    }

    $qry = <<<QRY1
       sum("value") AS "total", count("action") AS "counter", (CASE WHEN users."name" IS NULL OR users."name" = '' THEN '__anonymous' ELSE users."name" END) AS "name",
                $mstr AS "month", $ystr AS "year", $dstr AS "day"
                FROM stats
                LEFT JOIN users ON users."ID" = stats."userid"
                WHERE $qyear1 AND $qmonth1 AND "action" = :type1 $quser
                GROUP BY $dstr, "name", $mstr, $ystr
                ORDER BY "name", month, year
QRY1;

    $res = $db->select_query($qry, $input_arr);
    if (!is_array($res)) {
        $res = array();
    }
    $data = array();
    $users = array();
    $maxval = 0;

    foreach($res as $row) {
        $year = $row['year'];
        $name = $row['name'];
        $size = $row['total'];
        $count = $row['counter'];
        $day = $row['day'];
        $users[$name] = $name;
        if ($sizeorcount == 'size') {
            $data[$name][$day] = $size;
            $maxval = max($maxval, $size);
        } else {
            $data[$name][$day] = $count;
        }
    }
    if ($admin) {
        foreach(get_all_users($db) as $u) {
            $users[ $u ] = $u;
        }
    }
    ksort($users);
    $suffix = '';
    if ($sizeorcount == 'size') {
        list($d, $suffix, $factor) = format_size($maxval, 'h', $LN['byte_short'], 1024, 0);
    } 
    $new_data = array();
    $row_template = array();
    foreach (range(1, $max_month) as $day) {
        $row_template[$day] = 0;
    }
    foreach ($users as $user) {
        $new_data [$user] = $row_template;
    }

    foreach($data as $name => $dv) {
        $tmp_data = $row_template;
        foreach ($dv as $day => $v) {
            if ($sizeorcount == 'size') {
                $tmp_data[$day] = round(($v / $factor), 1);
            } else {
                $tmp_data[$day] = $v;
            }
        }
        $new_data[$name] = array_values($tmp_data);
    }
    foreach($users as &$u) {
        if ($u == '__anonymous') {
            $u = $LN['unknown'];
        }
    }
    $data = array_values($new_data);
    $legend = array_values($users);
    $labels = array_keys($row_template);
    $data = make_graph_data($db, $userid, array(
        'type'=> 'stackedbar',
        'data'=> $data,
        'labels'=>$labels,
        'title'=> $graphtitle,
        'titles'=>$legend));
    if ($sizeorcount == 'size') {
        $data['yaxislabel'] = $suffix;
    } else {
        $data['yaxisminimuminterval'] = 1;
    }
    return_result($data);
}

switch ($type) {
    case 'activity':
        $graphtitle = $LN[$subtype] . ' (' . $LN[$sizeorcount] . ')';
        $atype = array_search($subtype, $nametypes);
        if ($atype === FALSE) { 
            return_result(array('error'=> $LN['error_unknowntype']));
        }
        if ($period == 'years') {
            get_stats_by_year($db, $userid, $atype, $isadmin, 0, $sizeorcount, html_entity_decode( $graphtitle));
        } elseif ($period == 'months') {
            $graphtitle =  $graphtitle . ' - ' . $year;
            get_stats_by_month($db, $userid, $atype, $isadmin, $year, $sizeorcount,  html_entity_decode($graphtitle));
        } elseif ($period == 'days') {
            $graphtitle =  $graphtitle . ' - ' . $LN['month_names'][$month];
            get_stats_by_day($db, $userid, $atype, $isadmin, $year, $month, $sizeorcount,  html_entity_decode($graphtitle));
        }
        break;

    case 'spots_details':
        if ($period == 'hour') {
            $graphtitle = $LN['stats_spotsbyhour'];
            create_spot_graph_period($db, $userid,  html_entity_decode($graphtitle), 'hour');
        } elseif ($period == 'dow') {
            $graphtitle = $LN['stats_spotsbydow'];
            create_spot_graph_period($db, $userid,  html_entity_decode($graphtitle), 'dow');
        } elseif ($period == 'week') {
            $graphtitle = $LN['stats_spotsbyweek'];
            create_spot_graph_period($db, $userid,  html_entity_decode($graphtitle), 'week');
        } elseif ($period == 'month') {
            $graphtitle = $LN['stats_spotsbymonth'];
            create_spot_graph_period($db, $userid,  html_entity_decode($graphtitle), 'month');
        } else {
            $graphtitle = $LN['menuspots'];
            create_spot_data($db, $userid, $graphtitle);
        }
        break;
    case 'supply':
        if ($period == 'day') {
            $month = get_request('month', 1);
            $graphtitle = $LN['menubrowsesets'] . ' ' . $LN['month_names'][$month] . " $year";
            create_spot_graph_date($db, $userid,  html_entity_decode($graphtitle), $year, $month);
        } elseif ($period == 'month') {
            $graphtitle = $LN['menubrowsesets'] . " $year";
            create_spot_graph_date($db, $userid,  html_entity_decode($graphtitle), $year);
        } elseif ($period == 'year') {
            $graphtitle = $LN['menubrowsesets'];
            create_spot_supply_year($db, $userid,  html_entity_decode($graphtitle));
        }
        break;
    case 'spots_subcat':
        $cat = get_request('cat', 0);
        $subcat = get_request('subcat', 0);
        spots_per_subcat($db, $userid, $cat, $subcat);
        break;
}

