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
 * $LastChangedDate: 2010-09-21 19:28:38 +0200 (Tue, 21 Sep 2010) $
 * $Rev: 1748 $
 * $Author: gavinspearhead $
 * $Id: ajax_stats.php 1748 2010-09-21 17:28:38Z gavinspearhead $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
error_reporting(0);

$__auth = 'silent';
$pathstat = realpath(dirname(__FILE__));

require_once "$pathstat/../functions/html_includes.php";
require_once "$pathstat/../functions/libs/phplot/phplot.php";

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
}

$isadmin = urd_user_rights::is_admin($db, $userid);

$possibletypes = array('activity', 'spots_details', 'supply', 'blank', 'spots_subcat');
$possiblesubtypes = array('stats_dl','stats_pv','stats_im','stats_gt','stats_wv','stats_ps', '');
$type = get_request('type', 'blank');
$subtype = get_request('subtype', 'stats_dl');
if (!in_array($type, $possibletypes)) {
    die_html('Invalid type specified.');
}
if (!in_array($subtype, $possiblesubtypes)) {
    die_html('Invalid type specified.');
}

$width = get_request('width', 0);
if (!is_numeric($width) || $width < 400) {
    $width = 400;
}

$width = round($width);
$height = round(($width * 3) / 4);

// Years or months?
$period = get_request('period', 'months');
$year   = get_request('year', '0');

// What type of data should be displayed?
// stats_dl / pv / im / gt / wv / ps

// Display the size or the number of downloads?
$sizeorcount = get_request('source', 'size');

if ($sizeorcount != 'size') {
    $sizeorcount = 'count';
}

/* Determine valid years and months: */
$now = time();
$month = date('n', $now);

if ($year == 0) {
    $year = date('Y', $now); // default we show data from the current year
}

// Check which types of data we can show statistics of:
$types = urd_modules::get_stats_enabled_modules($db);

// Language mapping:
$langtypes = array();
$langtypes[stat_actions::DOWNLOAD] = $LN['stats_dl'];
$langtypes[stat_actions::PREVIEW]  = $LN['stats_pv'];
$langtypes[stat_actions::IMPORTNZB]= $LN['stats_im'];
$langtypes[stat_actions::GETNZB]   = $LN['stats_gt'];
$langtypes[stat_actions::WEBVIEW]  = $LN['stats_wv'];
$langtypes[stat_actions::POST]     = $LN['stats_ps'];

$nametypes = array();
$nametypes[stat_actions::DOWNLOAD] = 'stats_dl';
$nametypes[stat_actions::PREVIEW]  = 'stats_pv';
$nametypes[stat_actions::IMPORTNZB]= 'stats_im';
$nametypes[stat_actions::GETNZB]   = 'stats_gt';
$nametypes[stat_actions::WEBVIEW]  = 'stats_wv';
$nametypes[stat_actions::POST]     = 'stats_ps';

switch ($type) {
    case 'spots_subcat':
        $cat = get_request('cat', 0);
        $subcat = get_request('subcat', 0);
        spots_per_subcat($db, $userid, $cat, $subcat);
        break;
    case 'blank':
        create_blank_graph($db, $userid);
        break;
    case 'activity':
        $graphtitle = $LN[$subtype] . ' (' . $LN[$sizeorcount] . ')';
        if ($period == 'days') {
            $n_type = NULL;
            foreach ($nametypes as $key => $v) {
                if ($v == $subtype) {
                    $n_type = $key;
                    break;
                }
            }
            $month = get_request('month', 1);
            $graphtitle =  $graphtitle . ' - ' . $LN['month_names'][$month];
            $daystats = new OverallDayStats($types);
            $dl = get_stats_by_day($db, $userid, $n_type, $isadmin, $year, $month, $daystats, $types);
            convert_stat_definitions($daystats);
            create_graph_days($db, $userid, $graphtitle, $daystats, $subtype, $sizeorcount);
        } elseif ($period == 'months') {
            $monthstats = new OverallMonthStats($types);
            foreach ($types as $atype) {
                $dl = get_stats_by_month($db, $userid, $atype, $isadmin, $year, $monthstats, $types);
            }

            // Convert definitions to keywords (stat_actions::DOWNLOAD = 1 => 'downloads') to make it better parseable:
            convert_stat_definitions($monthstats);
            enter_month_names($monthstats);
            create_graph_months($db, $userid, $graphtitle, $monthstats, $subtype, $sizeorcount);
        } else {
            $yearstats = new OverallYearStats($types);
            foreach ($types as $atype) {
                $dl = get_stats_by_year($db, $userid, $atype, $isadmin, 0, $yearstats, $types);
            }
            // Convert definitions to keywords (stat_actions::DOWNLOAD = 1 => 'downloads') to make it better parseable:
            convert_stat_definitions($yearstats);
            create_graph_years($db, $userid, $graphtitle, $yearstats, $subtype, $sizeorcount);
        }
        break;
    case 'spots_details':
        if ($period == 'hour') {
            $graphtitle = $LN['stats_spotsbyhour'];
            create_spot_graph_period($db, $userid, $graphtitle, 'hour');
        } elseif ($period == 'dow') {
            $graphtitle = $LN['stats_spotsbydow'];
            create_spot_graph_period($db, $userid, $graphtitle, 'dow');
        } elseif ($period == 'week') {
            $graphtitle = $LN['stats_spotsbyweek'];
            create_spot_graph_period($db, $userid, $graphtitle, 'week');
        } elseif ($period == 'month') {
            $graphtitle = $LN['stats_spotsbymonth'];
            create_spot_graph_period($db, $userid, $graphtitle, 'month');
        } else {
            $graphtitle = $LN['menuspots'];
            create_spot_graph($db, $userid, $graphtitle);
        }
        break;
    case 'supply':
        if ($period == 'day') {
            $month = get_request('month', 1);
            $graphtitle = $LN['menubrowsesets'] . ' ' . $LN['month_names'][$month] . " $year";
            create_spot_graph_date($db, $userid, $graphtitle, $year, $month);
        } elseif ($period == 'month') {
            $graphtitle = $LN['menubrowsesets'] . " $year";
            create_spot_graph_date($db, $userid, $graphtitle, $year);
        } elseif ($period == 'year') {
            $graphtitle = $LN['menubrowsesets'];
            create_spot_graph_date($db, $userid, $graphtitle);
        }
        break;
}

// ZE END:
die();

/* CLASSES: */

class Stats
{
    public $StatName;
    public $SizeRaw;
    public $SizeNice;
    public $Count;

    public function __construct($name = '')
    {
        $this->StatName = $name;
        $this->SizeRaw = 0;
        $this->SizeNice = '';
        $this->Count = 0;
    }
}

class IndividualStats
{
    public $StatType;

    public function __construct(array $types)
    {
        global $langtypes;
        foreach ($types as $type) {
            $this->StatType[$type] = new Stats($langtypes[$type]);
        }
    }
}

class MonthStats
{
    public $MonthName;
    public $Users;
    public $OverallTotal;

    public function __construct(array $types)
    {
        $this->MonthName = '';
        $this->Users = array();
        $this->OverallTotal = new IndividualStats($types);
    }

}

class DayStats
{
    public $DayName;
    public $Users;
    public $OverallTotal;

    public function __construct(array $types)
    {
        $this->DayName = '';
        $this->Users = array();
        $this->OverallTotal = new IndividualStats($types);
    }

}

class OverallMonthStats
{
    public $Months;
    public function __construct()
    {
        $this->Months = array();
    }
}

class OverallDayStats
{
    public $Days;
    public function __construct()
    {
        $this->Days = array();
    }
}

class YearStats
{
    public $Users;
    public $OverallTotal;

    public function __construct(array $types)
    {
        $this->Users = array();
        $this->OverallTotal = new IndividualStats($types);
    }
}

class OverallYearStats
{
    public $Years;
    public function __construct()
    {
        $this->Years = array();
    }
}

/* FUNCTIONS: */

function create_graph_days(DatabaseConnection $db, $userid, $graphtitle, OverallDayStats $daystats, $type, $sizeorcount)
{
    assert(is_numeric($userid));
    global $LN;
    // $type is the type of data that is to be shown:


    // Keep track of maximum in order to convert to TB / GB / MB / KB etc
    $maxval = 0;
    $ylabel = '';
    $datainput = array();
    // For all months:
    foreach ($daystats->Days as $dayval =>$daystat) {
        $thisday = $dayval;
        // Format of datainput2: array($month, $dldata_user1, $dldata_user2, ...)
        $x = 0;
        $username = array();

        // Only if we have users:
        if (!isset($daystat->Users)) {
            continue;
        }
        // For all users:
        $userdata = array();
        foreach ($daystat->Users as $name => $userdetails) {
            $username[] = $name;
            $tempval = $userdetails->StatType[$type]->SizeRaw;
            $tempcnt = $userdetails->StatType[$type]->Count;

            if ($sizeorcount == 'size') {
                $userdata[$x] = $tempval;
                $maxval = max($tempval,$maxval);
            } else {
                $userdata[$x] = $tempcnt;
            }

            $x++;
        }

        $datainput2 = array();
        $datainput2[] = $thisday;
        foreach ($userdata as $rawsize) {
            $datainput2[] = $rawsize;
        }
        $datainput[] = $datainput2;

        // (Re) initalise because we only need the usernames once, not for each month again:
    }
    $legend = array();
    foreach ($username as $name) {
        if ($name == '__anonymous') {
            $legend[] = html_entity_decode($LN['unknown']);
        } else {
            $legend[] = $name;
        }
    }

    if (empty($datainput)) {
        $datainput = array(array(0,0));
    }
    if (empty($legend)) {
        $legend = array('-');
    }
    // Display:
    create_graph($db, $userid, $sizeorcount, $datainput, $legend, $graphtitle, $maxval, 'stackedbars', $ylabel);
}

function create_graph_months(DatabaseConnection $db, $userid, $graphtitle, OverallMonthStats $monthstats, $type, $sizeorcount)
{
    assert(is_numeric($userid));
    global $LN;
    // $type is the type of data that is to be shown:


    // Keep track of maximum in order to convert to TB / GB / MB / KB etc
    $maxval = 0;
    $datainput = array();

    // For all months:
    foreach ($monthstats->Months as $monthstat) {
        $thismonth = $monthstat->MonthName;
        // Format of datainput2: array($month, $dldata_user1, $dldata_user2, ...)
        $x = 0;
        $username = array();

        // Only if we have users:
        if (!isset($monthstat->Users)) {
            continue;
        }

        // For all users:
        foreach ($monthstat->Users as $name => $userdetails) {
            $username[] = $name;
            $tempval = $userdetails->StatType[$type]->SizeRaw;
            $tempcnt = $userdetails->StatType[$type]->Count;

            if ($sizeorcount == 'size') {
                $userdata[$x] = $tempval;
                $maxval = max($tempval, $maxval);
            } else {
                $userdata[$x] = $tempcnt;
            }

            $x++;
        }

        $datainput2 = array();
        $datainput2[] = $thismonth;
        foreach ($userdata as $rawsize) {
            $datainput2[] = $rawsize;
        }
        $datainput[] = $datainput2;

        // (Re) initalise because we only need the usernames once, not for each month again:
    }
    $legend = array();
    foreach ($username as $name) {
        if ($name == '__anonymous') {
            $legend[] = html_entity_decode($LN['unknown']);
        } else {
            $legend[] = $name;
        }
    }


    if (empty($datainput)) {
        $datainput = array(array(0,0));
    }
    if (empty($legend)) {
        $legend = array('-');
    }
    $ylabel = '';
    // Display:
  //  var_dump($datainput); die;
    create_graph($db, $userid, $sizeorcount, $datainput, $legend, $graphtitle, $maxval, 'stackedbars', $ylabel);
}

function create_graph_years(DatabaseConnection $db, $userid, $graphtitle, OverallYearStats $yearstats, $type, $sizeorcount)
{
    assert(is_numeric($userid));
    global $LN;
    // $type is the type of data that is to be shown:


    // Keep track of maximum in order to convert to TB / GB / MB / KB etc
    $maxval = 0;
    $datainput = array();

    // For all months:
    foreach ($yearstats->Years as $yearnr => $yearstat) {
        $thisyear = $yearnr;
        // Format of datainput2: array($year, $dldata_user1, $dldata_user2, ...)
        $x = 0;

        // Only if we have users:
        if (!isset($yearstat->Users)) {
            continue;
        }

        // For all users:
        foreach ($yearstat->Users as $name => $userdetails) {
            $username[] = $name;
            $tempval = $userdetails->StatType[$type]->SizeRaw;
            $tempcnt = $userdetails->StatType[$type]->Count;

            if ($sizeorcount == 'size') {
                $userdata[$x] = $tempval;
                $maxval = max($tempval,$maxval);
            } else {
                $userdata[$x] = $tempcnt;
            }
            $x++;
        }

        $datainput2 = array();
        $datainput2[] = $thisyear;
        foreach ($userdata as $rawsize) {
            $datainput2[] = $rawsize;
        }
        $datainput[] = $datainput2;

        // (Re) initalise because we only need the usernames once, not for each month again:
    }
    $legend = array();
    foreach ($username as $name) {
        if ($name == '__anonymous') {
            $legend[] = html_entity_decode($LN['unknown']);
        } else {
            $legend[] = $name;
        }
    }


    if (empty($datainput)) {
        $datainput = array(array(0,0));
    }
    if (empty($legend)) {
        $legend = array('-');
    }
    $ylabel = ucfirst($LN['sets']);
    // Display:
    create_graph($db, $userid, $sizeorcount, $datainput, $legend, $graphtitle, $maxval, 'stackedbars', $ylabel);
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

function array_join(array $data1, array $data2, array $data3, $key_map=NULL)
{
    $values = array();
    $keys = array_unique(array_merge (array_keys($data1), array_keys($data2), array_keys($data3)));
    sort($keys);
    foreach ($keys as $k) {
        $key = $k;
        if ($key_map !== NULL) {
            assert(is_array($key_map));
            $key = isset($key_map[$k] ) ? $key_map[$k] : $k;
        }
        $values[$k] = array($key, get_array($data1, $k, 0), get_array($data2, $k, 0), get_array($data3, $k, 0));
    }

    return $values;
}

function create_spot_graph_date(DatabaseConnection $db, $userid, $graphtitle, $year= NULL, $month=NULL)
{
    global $LN;
    assert(is_numeric($userid));
    $inputdata = array();
    if ($year === NULL || !is_numeric($year)) {
        $data1 = get_sets_stats_date($db, stat_actions::SPOT_COUNT);
        $data2 = get_sets_stats_date($db, stat_actions::SET_COUNT);
        $data3 = get_sets_stats_date($db, stat_actions::RSS_COUNT);
        $inputdata = array_join($data1, $data2, $data3);
    } elseif ($month === NULL || !is_numeric($month)) {
        $data1 = get_sets_stats_date_month($db, stat_actions::SPOT_COUNT, $year);
        $data2 = get_sets_stats_date_month($db, stat_actions::SET_COUNT, $year);
        $data3 = get_sets_stats_date_month($db, stat_actions::RSS_COUNT, $year);
        $inputdata_tmp = array_join($data1, $data2, $data3, $LN['short_month_names']);
        $inputdata = array();
        foreach (range(1, 12) as $k) {
            if (isset($inputdata_tmp[$k])) {
                $inputdata[$k] = $inputdata_tmp[$k];
            } else {
                $inputdata[$k] = array(html_entity_decode(get_array($LN['short_month_names'], $k, $k)), 0, 0, 0);
            }
        }
    } else {
        $data1 = get_sets_stats_date_day($db, stat_actions::SPOT_COUNT, $year, $month);
        $data2 = get_sets_stats_date_day($db, stat_actions::SET_COUNT, $year, $month);
        $data3 = get_sets_stats_date_day($db, stat_actions::RSS_COUNT, $year, $month);
        $days_per_month = date('t', mktime(0, 0, 0, $month, 1, $year));
        $days = array();
        foreach (range(1, $days_per_month) as $k) {
            $dow = get_dow($k, $month, $year);
            $days[ $k ] = "$k\n$dow";
        }
        $inputdata_tmp = array_join($data1,  $data2, $data3, $days);
        $inputdata = array();
        foreach (range(1, $days_per_month) as $k) {
            $dow = date('N', mktime(0, 0, 0, $month, $k, $year));
            $dow = html_entity_decode(get_array($LN['short_day_names'], $dow, date('D', mktime(0, 0, 0, $month, $k, $year))));
            if (isset($inputdata_tmp[$k])) {
                $inputdata[$k] = $inputdata_tmp[$k];
            } else {
                $inputdata[$k] = array($days[$k], 0, 0, 0);
            }
        }

    }
    if ($inputdata == array() ) {
        create_blank_graph($db, $userid);
    }
    $ylabel = ucfirst($LN['sets']);

    // Make a legend for the 3 data sets plotted:
    $legend = array($LN['menuspots'], $LN['menugroupsets'], $LN['menursssets']);
    create_graph($db, $userid, '', array_values($inputdata), $legend, $graphtitle, 0, 'stackedbars', $ylabel);
}

function create_spot_graph(DatabaseConnection $db, $userid, $graphtitle)
{
    assert(is_numeric($userid));
    global $LN;
    $data = get_spots_stats($db);
    $stat_data = array();

    foreach ($data as $key => $row) {
       $stat_data[] = array($key, $row);
    }
    $cats = array();
    foreach (SpotCategories::get_categories() as $key => $cat) {
        $cats [$key] = $LN[$cat];
    }
    create_graph($db, $userid, '', $stat_data, $cats, $graphtitle, 0, 'pie', '');
}

function create_spot_graph_period(DatabaseConnection $db, $userid, $graphtitle, $period)
{
    assert(is_numeric($userid));
    global $LN;
    $data = get_spots_stats_by_period($db, $period);
    if ($data === FALSE) {
        return;
    }
    $stat_data = array();
    $stat_data = $data;
    $cats = array();
    foreach (SpotCategories::get_categories() as $key => $cat) {
        $cats [$key] = $LN[$cat];
    }
    $ylabel = ucfirst($LN['sets']);
    create_graph($db, $userid, '', $stat_data, $cats, $graphtitle, 0, 'stackedbars', $ylabel);
}

function create_blank_graph(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    header('Content-Type: image/png');
    global $width, $height, $pathstat;

    $plot = new PHPlot_truecolor($width, $height);
    $plot->SetImageBorderType('raised');
    $plot->SetImageBorderWidth('1');

    // Main plot title:
   
    $plot->SetTitle('');
    $plot->SetYTitle('none');
    $plot->SetXTitle('none');

    $plot->SetBrowserCache(TRUE);

    // Make a legend for the 3 data sets plotted:

    // Colors:
    $colourmap = colour_map::get_colour_map($db, $userid);
    $bgcolor = $colourmap['color10'];
    $plot->SetTextColor($bgcolor);
    $plot->SetTickColor($bgcolor);
    $plot->SetTitleColor($bgcolor);
    $plot->SetLightGridColor($bgcolor);
    $plot->SetGridColor($bgcolor);
    $plot->SetDataValues(array(array(0, 0)));
    $plot->SetBackgroundColor($bgcolor);
    $plot->SetDataColors(array($bgcolor));
    $plot->DrawGraph();
}

function create_graph(DatabaseConnection $db, $userid, $sizeorcount, $datainput, $legend, $graphtitle, $maxval, $type='stackedbars', $ylabel='')
{
    assert(is_numeric($userid));
    global $LN, $pathstat;
    header('Content-Type: image/png');
    // Hide notices:
    //error_reporting(0);

    global $width, $height;
    $plot = new PHPlot_truecolor($width, $height);
    $graphtitle = html_entity_decode($graphtitle, ENT_QUOTES, 'UTF-8');
    $suffix = $ylabel;
    // Scale::
    if ($sizeorcount == 'size') {
        list($d, $suffix, $factor) = format_size($maxval, 'h', $LN['byte_short'], 1024, 0);
        // Correct all values:
        // $datainput[0] = array(X,y,z), array(A,b,c) => first item is x value, next items are y values
        $newdata = array();
        foreach ($datainput as $itemarray) {
            for ($x = 1; $x < count($itemarray); $x++) {
            // Round up, with 1 decimal
                $itemarray[$x] = round(($itemarray[$x] / $factor), 1);
            }
            $newdata[] = $itemarray;
        }

        unset($datainput);
        $datainput = array();
        $datainput = $newdata;
    }

    $colourmap = colour_map::get_colour_map($db, $userid);
    $gridincolor = $gridoutcolor = $colourmap['dimgray'];
    $bgcolor = $colourmap['color10'];
    $pielabelcolor = $textcolor = $tickcolor = $titlecolor = $colourmap['color11'];
    $ylabel = $suffix;
    $plot->SetImageBorderType('raised');
    $plot->SetImageBorderWidth('1');
    if ($type == 'stackedbars') {
        $plot->SetPlotType('stackedbars');
        $plot->SetDataType('text-data');
        $plot->SetLegendReverse(TRUE);
    } else {
        $plot->SetPieLabelColor($pielabelcolor);
        $plot->SetPlotType('pie');
        $plot->SetDataType('text-data-single');
        $plot->SetShading(32);
    }
    $plot->SetDataValues($datainput);

    $plot->SetYTitle($ylabel);
    $plot->SetBrowserCache(FALSE);
    // Main plot title:
    $font_path = $pathstat.'/../functions/libs/phplot/';
    $plot->SetFontTTF('title', $font_path . 'LiberationSans-Regular.ttf', 12);
    $plot->SetFontTTF('y_label', $font_path . 'LiberationSans-Regular.ttf', 8);
    $plot->SetFontTTF('x_label', $font_path . 'LiberationSans-Regular.ttf', 8);
    $plot->SetFontTTF('x_title', $font_path . 'LiberationSans-Regular.ttf', 12);
    $plot->SetFontTTF('legend', $font_path . 'LiberationSans-Regular.ttf', 8);
    $plot->SetTitle($graphtitle);

    // Make a legend for the 3 data sets plotted:
    $plot->SetLegend($legend);
    list($lwidth, $lheight) = $plot->GetLegendSize();
    $plot->SetMarginsPixels(NULL, $lwidth + 6);
    $plot->SetLegendPosition(1, 0, 'image', 1, 0, -4, 4);

    // Colors:
    $plot->SetRGBArray($colourmap);
    $plot->SetTextColor($textcolor);
    $plot->SetTickColor($tickcolor);
    $plot->SetTitleColor($titlecolor);
    $plot->SetLightGridColor($gridincolor);
    $plot->SetGridColor($gridoutcolor);
    $plot->SetDataColors(array('color1','color2','color3','color4','color5','color6','color7','color8','color9'));
    $plot->SetBackgroundColor($bgcolor);

    // Turn off X tick labels and ticks because they don't apply here:
    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');

    // Display exact value in bars:
    $plot->SetYDataLabelPos('plotstack');
    $plot->DrawGraph();
    exit(0);
}

function get_stats_by_year(DatabaseConnection $db, $userid, $type, $admin, $fromyear, OverallYearStats &$overallyearstats, $types)
{
    assert(is_numeric($userid));
    global $LN;

    $ystr = $db->get_extract('year', '"timestamp"');

    $max_cnt = 0;
    $quser = '';
    $input_arr =  array( ':fromyear2'=>$fromyear, ':fromyear1'=>$fromyear, ':type1'=>$type, ':type2'=>$type);
    if (!$admin) {
        $input_arr[':userid'] = $userid;
        $quser = ' AND users."ID" = :userid ';
    }
    $qry = <<<QRY1
        * FROM (SELECT sum("value") AS "total", count("action") AS "counter",
                (CASE WHEN users."name" IS NULL OR users."name" = '' THEN '__anonymous' ELSE users."name" END) AS "name" ,
                $ystr AS "year" FROM stats LEFT JOIN users ON users."ID" = stats."userid"
                WHERE $ystr > :fromyear1
                AND "action" = :type2 $quser GROUP BY "name", $ystr
                UNION  SELECT sum("value") AS "total", count("action") AS "counter", '__total' AS "name", $ystr AS "year" FROM stats WHERE $ystr > :fromyear2
                AND "action" = :type1 GROUP BY $ystr) AS "val" ORDER BY year, "name"
QRY1;
    $res = $db->select_query($qry, $input_arr);
    if (!is_array($res)) {
        $res = array();
    }

    // Loop through all users for this type:
    foreach ($res as &$row) {
        // Clean up 0/1/2/3/4 stuff:
        for ($x = 0; $x < 5; $x++) {
            if (isset($row["$x"])) {
                unset($row["$x"]);
            }
        }

        if ($row['counter'] > $max_cnt) {
            $max_cnt = $row['counter'];
        }
        $row['total_nr'] = $row['total'];
        list($size, $suffix) = format_size($row['total'], 'h', $LN['byte_short'], 1024, 1);
        $row['total'] = $size . $suffix;

        if ($row['name'] == '__total') {
            $row['name'] = html_entity_decode($LN['total']);
            $row['sum'] = 'true';
        }
    }

    // Initialize:
    $yearsarray = array();
    foreach ($res as $user) {
        $year = $user['year'];
        $yearsarray[$year] = 'x';
    }

    foreach ($res as $user) {
        $name = $user['name'];
        if (isset($user['sum'])) {
            continue; // Not for totals, only for real users
        }

        foreach ($yearsarray as $year => $x) {
            if (!isset( $overallyearstats->Years[$year] )) {
                $overallyearstats->Years[$year] = new YearStats($types);
                $overallyearstats->Years[$year]->OverallTotal->StatType[$type]->SizeRaw = 0;
                $overallyearstats->Years[$year]->OverallTotal->StatType[$type]->SizeNice = 0;
                $overallyearstats->Years[$year]->OverallTotal->StatType[$type]->Count = 0;
            }

            if (!isset($overallyearstats->Years[$year]->Users[$name])) {
                $overallyearstats->Years[$year]->Users[$name] = new IndividualStats($types);
                $overallyearstats->Years[$year]->Users[$name]->StatType[$type]->SizeRaw = 0;
                $overallyearstats->Years[$year]->Users[$name]->StatType[$type]->SizeNice = 0;
                $overallyearstats->Years[$year]->Users[$name]->StatType[$type]->Count = 0;
            }
        }
    }

    // Parse into structs:
    foreach ($res as $user) {
        $year = $user['year'];
        $name = $user['name'];
        $issum = isset($user['sum']) ? TRUE : FALSE;

        if ($issum) {
            $overallyearstats->Years[$year]->OverallTotal->StatType[$type]->SizeRaw = $user['total_nr'];
            list($size, $suffix) = format_size($user['total_nr'],'h' , $LN['byte_short'], 1024, 1);
            $overallyearstats->Years[$year]->OverallTotal->StatType[$type]->SizeNice = $size . $suffix;
            $overallyearstats->Years[$year]->OverallTotal->StatType[$type]->Count = $user['counter'];
        } else {
            $overallyearstats->Years[$year]->Users[$name]->StatType[$type]->SizeRaw = $user['total_nr'];
            $overallyearstats->Years[$year]->Users[$name]->StatType[$type]->SizeNice = $user['total'];
            $overallyearstats->Years[$year]->Users[$name]->StatType[$type]->Count = $user['counter'];
        }
    }
    krsort($overallyearstats->Years);

    return $res;
}

// Translate definitions for stats to keywords that can be safely used in smarty without having to worry if the definition values change:
function convert_stat_definitions(&$stats)
{
    if (is_object($stats) && get_class($stats) == 'IndividualStats') {
        foreach ($stats->StatType as $key => $data) {
            $newkey = transform_key($key);
            $stats->StatType[$newkey] = $stats->StatType[$key];
            unset($stats->StatType[$key]);
        }
    }

    if (is_array($stats) || is_object($stats)) {
        foreach ($stats as $otherobj) {
            convert_stat_definitions($otherobj);
        }
    }
}

function transform_key($key)
{
    global $nametypes;
    if (isset($nametypes[$key])) {
        return $nametypes[$key];
    }

    return $key;
}

function move_total_to_users(&$stats)
{
    if (is_object($stats) && get_class($stats) == 'OverallYearStats') {
        foreach ($stats->Years as $yearstats) {
            $yearstats->Users['__total'] = $yearstats->OverallTotal;
        }
    }
    if (is_object($stats) && get_class($stats) == 'OverallMonthStats') {
        foreach ($stats->Months as $monthstats) {
            $monthstats->Users['__total'] = $monthstats->OverallTotal;
        }
    }
}

function get_stats_by_month(DatabaseConnection $db, $userid, $type, $admin, $year=NULL, OverallMonthStats &$overallmonthstats, $types)
{
    assert(is_numeric($userid));
    global $LN;

    $ystr = $db->get_extract('year', '"timestamp"');
    $mstr = $db->get_extract('month', '"timestamp"');
    $input_arr = array( ':type1' => $type, ':type2' => $type);
    if ($year !== NULL) {
        $input_arr[':year1'] = $input_arr[':year2'] = $year;
        $qyear1 = "$ystr = :year1";
        $qyear2 = "$ystr = :year2";
    } else {
        $qyear = '1=1';
    }
    $quser = '';
    if (!$admin) {
        $input_arr[':userid'] = $userid;
        $quser = ' AND users."ID" = :userid ';
    }

    $qry = <<<QRY1
        * FROM (SELECT sum("value") AS "total", count("action") AS "counter", (CASE WHEN users."name" IS NULL OR users."name" = '' THEN '__anonymous' ELSE users."name" END) AS "name",
                $mstr AS "month", $ystr AS "year"
                FROM stats
                LEFT JOIN users ON users."ID" = stats."userid"
                WHERE $qyear1 AND "action" = :type2 $quser
                GROUP BY "name", $mstr, $ystr
                UNION
                SELECT sum("value") AS "total", count("action") AS "counter", '__total' AS "name", $mstr AS "month", $ystr AS "year"
                FROM stats
                WHERE $qyear2 AND "action" = :type1
                GROUP BY $mstr, $ystr ) AS "val"
        ORDER BY "name", month, year
QRY1;
    $res = $db->select_query($qry, $input_arr);
    if (!is_array($res)) {
        $res = array();
    }
    // Loop through all users for this type:
    foreach ($res as &$row) {
        // Clean up 0/1/2/3/4 stuff:
        for ($x = 0; $x < 5; $x++) {
            if (isset($row["$x"])) {
                unset($row["$x"]);
            }
        }

        $row['total_nr'] = $row['total'];
        list($size, $suffix) = format_size($row['total'], 'h', $LN['byte_short'], 1024, 1);
        $row['total'] = $size . $suffix;

        if ($row['name'] ==  '__total') {
            $row['name'] = html_entity_decode($LN['total']);
            $row['sum'] = 'true';
        }
    }

    // Initialize:
    foreach ($res as $user) {
        $name = $user['name'];
        if (isset($user['sum'])) {
            continue; // Not for totals, only for real users
        }

        for ($x = 1; $x < 13; $x++) {
            if (!isset( $overallmonthstats->Months[$x] )) {
                $overallmonthstats->Months[$x] = new MonthStats($types);
                $overallmonthstats->Months[$x]->OverallTotal->StatType[$type]->SizeRaw = 0;
                $overallmonthstats->Months[$x]->OverallTotal->StatType[$type]->SizeNice = 0;
                $overallmonthstats->Months[$x]->OverallTotal->StatType[$type]->Count = 0;
            }
            if (!isset($overallmonthstats->Months[$x]->Users[$name])) {
                $overallmonthstats->Months[$x]->Users[$name] = new IndividualStats($types);
            }

            $overallmonthstats->Months[$x]->Users[$name]->StatType[$type]->SizeRaw = 0;
            $overallmonthstats->Months[$x]->Users[$name]->StatType[$type]->SizeNice = 0;
            $overallmonthstats->Months[$x]->Users[$name]->StatType[$type]->Count = 0;
        }
    }

    // Parse into structs:
    foreach ($res as $user) {
        $year = $user['year'];
        $month = $user['month'];
        $name = $user['name'];
        $issum = isset($user['sum']) ? TRUE : FALSE;

        if ($issum) {
            $overallmonthstats->Months[$month]->OverallTotal->StatType[$type]->SizeRaw = $user['total_nr'];
            list($size, $suffix) = format_size($user['total_nr'], 'h', $LN['byte_short'], 1024, 1);
            $overallmonthstats->Months[$month]->OverallTotal->StatType[$type]->SizeNice = $size . $suffix;
            $overallmonthstats->Months[$month]->OverallTotal->StatType[$type]->Count = $user['counter'];
        } else {
            if (!isset($overallmonthstats->Months[$month]->Users[$name])) {
                $overallmonthstats->Months[$month]->Users[$name] = new IndividualStats($types);
            }

            $overallmonthstats->Months[$month]->Users[$name]->StatType[$type]->SizeRaw = $user['total_nr'];
            $overallmonthstats->Months[$month]->Users[$name]->StatType[$type]->SizeNice = $user['total'];
            $overallmonthstats->Months[$month]->Users[$name]->StatType[$type]->Count = $user['counter'];
        }
    }
    ksort($overallmonthstats->Months);
}

function get_stats_by_day(DatabaseConnection $db, $userid, $type, $admin, $year, $month, OverallDayStats &$overalldaystats, $types)
{
    assert(is_numeric($userid));
    global $LN;
    $o_month = $month;
    $o_year = $year;

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
    $input_arr = array(':type1'=>$type, ':type2'=>$type, ':year1'=>$year, ':year2'=>$year, ':month1'=>$month, ':month2'=>$month);
    $timestamp = strtotime("$year/$month/1 00:00");
    $max_month = date('t', $timestamp);
    
    $qyear1 = "$ystr = :year1";
    $qyear2 = "$ystr = :year2";
    $qmonth1 = "$mstr = :month1";
    $qmonth2 = "$mstr = :month2";

    $quser = '';
    if (!$admin) {
        $input_arr[':userid'] = $userid;
        $quser = ' AND users."ID" = :userid ';
    }

    $qry = <<<QRY1
        * FROM (SELECT sum("value") AS "total", count("action") AS "counter", (CASE WHEN users."name" IS NULL OR users."name" = '' THEN '__anonymous' ELSE users."name" END) AS "name",
                $mstr AS "month", $ystr AS "year", $dstr AS "day"
                FROM stats
                LEFT JOIN users ON users."ID" = stats."userid"
                WHERE $qyear1 AND $qmonth1 AND "action" = :type1 $quser
                GROUP BY $dstr, "name", $mstr, $ystr
                UNION
                SELECT sum("value") AS "total", count("action") AS "counter", '__total' AS "name", $mstr AS "month", $ystr AS "year", $dstr AS "day"
                FROM stats
                WHERE $qyear2 AND $qmonth2 AND "action" = :type2
                GROUP BY $dstr, $mstr, $ystr ) AS "val"
        ORDER BY "name", month, year
QRY1;
    $res = $db->select_query($qry, $input_arr);

    if (!is_array($res)) {
        $res = array();
    }
    // Loop through all users for this type:
    foreach ($res as &$row) {
        // Clean up 0/1/2/3/4 stuff:
        for ($x = 0; $x < 5; $x++) {
            if (isset($row["$x"])) {
                unset($row["$x"]);
            }
        }

        $row['total_nr'] = $row['total'];
        list($size, $suffix) = format_size($row['total'], 'h', $LN['byte_short'], 1024, 1);
        $row['total'] = $size . $suffix;

        if ($row['name'] ==  '__total') {
            $row['name'] = html_entity_decode($LN['total']);
            $row['sum'] = 'true';
        }
    }

    foreach (range(1, $max_month) as $x) {
        $overalldaystats->Days[$x] = new DayStats($types);
        $overalldaystats->Days[$x]->OverallTotal->StatType[$type]->SizeRaw = 0;
        $overalldaystats->Days[$x]->OverallTotal->StatType[$type]->SizeNice = 0;
        $overalldaystats->Days[$x]->OverallTotal->StatType[$type]->Count = 0;
    }
    // Initialize:
    foreach ($res as $user) {
        $name = $user['name'];
        if (isset($user['sum'])) {
            continue; // Not for totals, only for real users
        }

        foreach (range(1, $max_month) as $x) {
            if (!isset( $overalldaystats->Days[$x] )) {
                $overalldaystats->Days[$x] = new DayStats($types);
                $overalldaystats->Days[$x]->OverallTotal->StatType[$type]->SizeRaw = 0;
                $overalldaystats->Days[$x]->OverallTotal->StatType[$type]->SizeNice = 0;
                $overalldaystats->Days[$x]->OverallTotal->StatType[$type]->Count = 0;
            }
            if (!isset($overalldaystats->Days[$x]->Users[$name])) {
                $overalldaystats->Days[$x]->Users[$name] = new IndividualStats($types);
            }

            $overalldaystats->Days[$x]->Users[$name]->StatType[$type]->SizeRaw = 0;
            $overalldaystats->Days[$x]->Users[$name]->StatType[$type]->SizeNice = 0;
            $overalldaystats->Days[$x]->Users[$name]->StatType[$type]->Count = 0;
        }
    }
    // Parse into structs:
    foreach ($res as $user) {
        $year = $user['year'];
        $day = $user['day'];
        $name = $user['name'];
        $issum = isset($user['sum']) ? TRUE : FALSE;

        if ($issum) {
            $overalldaystats->Days[$day]->OverallTotal->StatType[$type]->SizeRaw = $user['total_nr'];
            list($size, $suffix) = format_size($user['total_nr'],'h' , $LN['byte_short'], 1024, 1);
            $overalldaystats->Days[$day]->OverallTotal->StatType[$type]->SizeNice = $size . $suffix;
            $overalldaystats->Days[$day]->OverallTotal->StatType[$type]->Count = $user['counter'];
        } else {
            if (!isset($overalldaystats->Days[$day]->Users[$name])) {
                $overalldaystats->Days[$day]->Users[$name] = new IndividualStats($types);
            }

            $overalldaystats->Days[$day]->Users[$name]->StatType[$type]->SizeRaw = $user['total_nr'];
            $overalldaystats->Days[$day]->Users[$name]->StatType[$type]->SizeNice = $user['total'];
            $overalldaystats->Days[$day]->Users[$name]->StatType[$type]->Count = $user['counter'];
        }
    }
    ksort($overalldaystats->Days);
    $daystats = new OverallDayStats($types);
    foreach ($overalldaystats->Days as $x => $data) {
        $day = get_dow($x, $o_month, $o_year) . "\n$x";
        $daystats->Days[$day] = $overalldaystats->Days[$x];
    }
    $overalldaystats = $daystats;
}

function enter_month_names(OverallMonthStats &$overallmonthstats)
{
    global $LN;
    foreach ($overallmonthstats->Months as $monthnumber => $monthobj) {
        $monthobj->MonthName = html_entity_decode($LN['short_month_names'][$monthnumber]);
    }
}

function spots_per_subcat(DatabaseConnection $db, $userid, $cat, $subcat)
{
    assert(is_numeric($userid));
    global $width, $height, $pathstat;
    if (!in_array($subcat, array('a', 'b', 'c', 'd', 'z'))) {
        create_blank_graph($db, $userid);
        die;
    }
    $sql = "\"subcat$subcat\" AS \"subcat\" FROM spots WHERE \"category\"=?";
    $limit = 0;
    $stats = SpotCategories::get_subcats_ids($cat, $subcat);
    $row_count = 20000;
    while (TRUE) {
        $res = $db->select_query($sql, $row_count, $limit, array($cat));
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
    if ($stats == array()) {
        create_blank_graph($db, $userid);
        die;
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
    $count = count($s);
    $plot = new PHPlot_truecolor($width,($count * 16) + 48);
    $colourmap = colour_map::get_colour_map($db, $userid);
    $gridincolor = $gridoutcolor = $colourmap['dimgray'];
    $bgcolor = $colourmap['color10'];
    $pielabelcolor = $textcolor = $tickcolor = $titlecolor = $colourmap['color11'];
    $plot->SetRGBArray($colourmap);
    $plot->SetTextColor($textcolor);
    $plot->SetTickColor($tickcolor);
    $plot->SetTitleColor($titlecolor);
    $plot->SetLightGridColor($gridincolor);
    $plot->SetGridColor($gridoutcolor);
    $plot->SetTitle(html_entity_decode(to_ln(SpotCategories::HeadCat2Desc($cat))) . ' - ' . html_entity_decode(to_ln(SpotCategories::SubcatDescription($cat, $subcat))));

    $plot->SetBackgroundColor($bgcolor);
    $plot->SetDataColors(array('color1','color2','color3','color4','color5','color6','color7','color8','color9'));

    $plot->SetImageBorderType('plain');
    $font_path = $pathstat .  '/../functions/libs/phplot/';
    $plot->SetFontTTF('title', $font_path . 'LiberationSans-Regular.ttf', 12);
    $plot->SetFontTTF('y_label',$font_path . 'LiberationSans-Regular.ttf', 8);
    $plot->SetFontTTF('legend', $font_path . 'LiberationSans-Regular.ttf', 8);
    $plot->SetFontTTF('x_label', $font_path . 'LiberationSans-Regular.ttf', 8);
    $plot->SetFontTTF('x_title', $font_path . 'LiberationSans-Regular.ttf', 12);

    $plot->SetXDataLabelPos('plotin');
    $plot->SetPlotType('bars');
    $plot->SetDataType('text-data-yx');
    $plot->SetDataValues(array_values($s));
    $plot->DrawGraph();
}
