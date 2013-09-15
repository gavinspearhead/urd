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
 * $LastChangedDate: 2011-03-06 00:35:58 +0100 (Sun, 06 Mar 2011) $
 * $Rev: 2094 $
 * $Author: gavinspearhead $
 * $Id: ajax_browse.php 2094 2011-03-05 23:35:58Z gavinspearhead $
 */
if (!defined('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
}

$__auth = 'silent';
$pathidx = realpath(dirname(__FILE__));

require_once "$pathidx/../functions/html_includes.php";

if (!isset($_SESSION['setdata']) || !is_array($_SESSION['setdata'])) {
    $_SESSION['setdata'] = array();
}

verify_access($db, urd_modules::URD_CLASS_SPOTS, FALSE, '', $userid, TRUE);

class spot_viewer
{
    private static $sort_orders = array (
            '',
            'title',
            'stamp',
            'size',
            'url',
            'comments',
            'reports',
    );

    private $Qsearch = '';
    private $Qsize = '';
    private $Qcategory = '';
    private $Qkill = '';
    private $Qflag = '';
    private $rss_flag = '';
    private $Qsubcat = '';
    private $Qspotid = '';
    private $Qposter = '';
    private $Qage = '';
    private $Qspamlimit = '';
    private $Qadult = '';
    private $Qrating = '';

    private $minage = '';
    private $maxage = '';
    private $minsetsize = '';
    private $maxsetsize = '';
    private $search = '';
    private $killflag = FALSE;
    private $categoryID;

    private $userid;
    private $db;
    private $now;
    private $search_type;
    private $totalsets = 0;
    private $int_sets = 0;

    public function __construct(DatabaseConnection $db, $userid)
    {
        $this->db = $db;
        $this->userID = $userid;
        $this->now = time();
        $this->search_type = $this->db->get_pattern_search_command('LIKE'); // get the operator we need for the DB LIKE for mysql or ~~* for postgres
    }

    private function get_basic_browse_query()
    {
        $basic_browse_query = ' FROM spots ' .
            ' LEFT JOIN spot_whitelist ON (spots."spotter_id" = spot_whitelist."spotter_id") ' .
            " LEFT JOIN usersetinfo ON ((usersetinfo.\"setID\" = spots.\"spotid\") AND (usersetinfo.\"userID\" = {$this->userID})) AND (usersetinfo.\"type\" = '" . USERSETTYPE_SPOT . "') " .
            " LEFT JOIN extsetdata AS extsetdata2 ON (extsetdata2.\"setID\" = spots.\"spotid\" AND extsetdata2.\"name\" = 'setname' AND extsetdata2.\"type\" = '" . USERSETTYPE_SPOT . "' ) " .
            " LEFT JOIN extsetdata AS extsetdata3 ON (extsetdata3.\"setID\" = spots.\"spotid\" AND extsetdata3.\"name\" = 'xrated' AND extsetdata3.\"type\" = '" . USERSETTYPE_SPOT . "' ) " .
            " LEFT JOIN extsetdata AS extsetdata4 ON (extsetdata4.\"setID\" = spots.\"spotid\" AND extsetdata4.\"name\" = 'score' AND extsetdata4.\"type\" = '" . USERSETTYPE_SPOT . "' ) " .
            " WHERE (1=1 {$this->Qsearch} {$this->Qsize} {$this->Qcategory} {$this->Qkill} {$this->Qflag} {$this->Qsubcat} {$this->Qspotid} {$this->Qposter} {$this->Qspamlimit} {$this->Qrating}) " .
            " AND (1=1 {$this->Qage} {$this->Qadult}) ";

        return $basic_browse_query;
    }
    private function get_spots($interesting_only)
    {
        $sql = " spots.\"id\", \"title\", spots.\"size\", spots.\"spotid\", ({$this->now} - \"stamp\") AS \"age\", \"stamp\", spots.\"reports\", spots.\"comments\", spots.\"poster\"," .
            "\"category\", \"subcat\", \"subcata\" ,\"subcatb\", \"subcatc\", \"subcatd\", \"subcatz\", spots.\"url\", extsetdata2.\"value\" AS \"bettername\"," .
            " spot_whitelist.\"spotter_id\" AS \"whitelisted\", " .
            '(CASE WHEN usersetinfo."statusread" IS NULL OR usersetinfo."statusread" <> 1 THEN 0 ELSE 1 END) AS "alreadyread", ' .
            '(CASE WHEN usersetinfo."statusnzb" IS NULL OR usersetinfo."statusnzb" <> 1 THEN 0 ELSE 1 END) AS "nzbcreated", ' .
            '(CASE WHEN usersetinfo."statusint" IS NULL OR usersetinfo."statusint" <> 1 THEN 0 ELSE 1 END) AS "interesting", ' .
            '(CASE WHEN extsetdata4."value" IS NULL THEN \'0\' ELSE extsetdata4."value" END) AS "rating", ' .
            '(CASE WHEN extsetdata2."value" IS NULL THEN spots."title" ELSE extsetdata2."value" END) AS "better_subject" ';
        $sql .=	$this->get_basic_browse_query();
        if ($interesting_only) {
            $sql1 = $sql . ' AND usersetinfo."statusint" = 1';
        } else {
            $sql1 = $sql . ' AND (usersetinfo."statusint" != 1 OR usersetinfo."statusint" IS NULL)';
        }
        $sql1 .= " ORDER BY {$this->Qorder}";
        return $sql1;
    }
    private function get_spots_count($interesting_only)
    {
        global $LN;
        $basic_browse_query = $this->get_basic_browse_query();
        $sql = 'COUNT(spots."id") AS cnt ' . $basic_browse_query;
        if ($interesting_only) {
            $sql .= ' AND usersetinfo."statusint" = 1';
        }
        $res = $this->db->select_query($sql);
        if (!isset($res[0]['cnt'])) {
            throw new exception($LN['error_setsnumberunknown']);
        }

        return $res[0]['cnt'];
    }
    public function get_page_count($perpage, $offset, $skip_total=FALSE)
    {
        if (! $skip_total) {
            $this->totalsets = $this->get_spots_count(FALSE);
        }
        $this->int_sets = $this->get_spots_count(TRUE);

        return get_pages($this->totalsets, $perpage, $offset);
    }
    public function get_spot_data($perpage, $offset)
    {
        global $LN;

        $setres = array();
        if ($offset <= $this->int_sets) {
            $sql1 = $this->get_spots(TRUE);
            $setres = $this->db->select_query($sql1, $perpage, $offset);
            if (!is_array($setres)) {
                $setres = array();
            }
        }
        $setres_count = count($setres);

        if ($setres_count < $perpage) {
            $sql2 = $this->get_spots(FALSE);
            $setres2 = $this->db->select_query($sql2, $perpage - $setres_count, $offset - $this->int_sets);
            if (!is_array($setres2)) {
                $setres2 = array();
            }
            $setres = array_merge($setres, $setres2);
        }

        // Get the set data
        $allsets = array();
        // If no sets exist, create empty array:
        if (!is_array($setres)) {
            $setres = array();
        }
        $number = $offset;
        try {
            $group_lastupdate = get_group_last_updated($this->db, get_config($this->db, 'spots_group'), $this->userID);
            $group_lastupdate = reset($group_lastupdate);
        } catch (exception $e) {
            $group_lastupdate = 0;
        }

        foreach ($setres as $arr) {
            // Show bar around interesting when applicable:
            $thisset = array();
            $thisset['interesting'] = $arr['interesting'];
            $thisset['sid'] = $arr['spotid'];
            $thisset['comments'] = is_numeric($arr['comments']) ? $arr['comments'] : 0;
            $thisset['reports'] = is_numeric($arr['reports']) ? $arr['reports'] : 0;
            $thisset['categorynr'] = $arr['category'];
            $thisset['url'] = trim(strip_tags($arr['url']));
            $thisset['added'] = (is_array($_SESSION['setdata']) && in_setdata($arr['spotid'], 'spot', $_SESSION['setdata'])) ? 1 : 0;
            $thisset['read'] = $arr['alreadyread'];
            $thisset['whitelisted'] = $arr['whitelisted'];
            $thisset['poster'] = $arr['poster'];
            $thisset['nzb'] = $arr['nzbcreated'];
            $thisset['subject'] = utf8_encode(html_entity_decode($arr['title']));

            $thisset['rating'] = '';
            if ($arr['rating'] != 0) {
                $thisset['rating'] = round_rating(sprintf('%.1f', $arr['rating']));
            }
            if ($arr['bettername'] != '') {
                $thisset['name'] = $arr['bettername'];
            } else {
                $clearname = html_entity_decode($arr['title'], ENT_QUOTES, 'UTF-8');
                // filter common spam in subjects
                $name = preg_replace('/(\byEnc\b)|(\breq:)|(\bwww\.[\w\.]*\b)|(\bhttp:\/\/[\w\.]*\b)|([=><])/i', '', $clearname);
                $thisset['name'] = $name;// we utf8_encode before putting it in the db (otherwise pg will complain); but why need we decode it here so that all funny (german) characters are shown properly???
            }
            $thisset['extcat'] = '';
            if (preg_match('/\:_img_[a-z]+\:/i', $thisset['name'], $matches)) {
                $thisset['extcat'] = trim(substr($thisset['name'], 0, strlen($matches[0])));
                $thisset['name'] = trim(substr($thisset['name'], strlen($matches[0])));
            }

            if (isset($_SESSION['last_login']) && $_SESSION['last_login'] > 0 && $group_lastupdate > 0) {
                $last_check_time = min($_SESSION['last_login'], $group_lastupdate);
                $thisset['new_set'] = ($arr['stamp'] > $last_check_time) ? 1 : 0;
            } else {
                $thisset['new_set'] = 0;
            }

            $thisset['subcata'] = get_subcats($arr['category'], $arr['subcata']);
            $thisset['subcatb'] = get_subcats($arr['category'], $arr['subcatb']);
            $thisset['subcatc'] = get_subcats($arr['category'], $arr['subcatc']);
            $thisset['subcatd'] = get_subcats($arr['category'], $arr['subcatd']);
            $thisset['subcatz'] = get_subcats($arr['category'], $arr['subcatz']);
            $age = ($this->now > $arr['stamp']) ? $this->now - $arr['stamp'] : 0;

            $thisset['age'] = readable_time($age, 'largest_two');
            list($_size, $suffix) = format_size($arr['size'], 'h', $LN['byte_short'], 1024, 1);
            $thisset['size'] = $_size . ' ' . $suffix;
            $thisset['number'] = ++$number;
            $allsets[] = $thisset;
        }

        return $allsets;
    }

    public function set_qsearch($search)
    {
        $this->search = $search;
        $this->db->escape($search);
        $this->Qsearch = parse_search_string($search, '"title"', 'spots."tag"', 'extsetdata2."value"', $this->search_type);
    }
    public function set_qsize($ominsetsize, $omaxsetsize)
    {
        $minsetsize = get_pref($this->db, 'minsetsize', $this->userID, 0);
        $maxsetsize = get_pref($this->db, 'maxsetsize', $this->userID, 0);
        $this->minsetsize = $ominsetsize;
        $this->maxsetsize = $omaxsetsize;
        if ($ominsetsize != '') {
            try {
                $ominsetsize = unformat_size($ominsetsize, 1024, 'M');
            } catch (exception $e) {
                $ominsetsize = NULL;
            }
        }
        if ($omaxsetsize != '') {
            try {
                $omaxsetsize = unformat_size($omaxsetsize, 1024, 'M');
            } catch (exception $e) {
                $omaxsetsize = NULL;
            }
        }
        if (is_numeric($ominsetsize) && $ominsetsize > 0) {
            $this->db->escape($ominsetsize, TRUE);
            $this->Qsize = " AND (spots.\"size\" >= $ominsetsize) ";
        } elseif ($minsetsize > 0) {
            $this->db->escape($minsetsize, TRUE);
            $this->Qsize = " AND (spots.\"size\" >= $minsetsize) ";
        }

        if (is_numeric($omaxsetsize) && $omaxsetsize > 0) {
            $this->db->escape($omaxsetsize, TRUE);
            $this->Qsize .= " AND (spots.size <= $omaxsetsize ) ";
        } elseif ($maxsetsize > 0) {
            $this->db->escape($maxsetsize, TRUE);
            $this->Qsize .= " AND (spots.size <= $maxsetsize ) ";
        }
    }

    public function set_qcategory($categoryID)
    {
        $this->categoryID = $categoryID;
        if ($categoryID != '') {
            $this->db->escape($categoryID, TRUE);
            $this->Qcategory = " AND spots.\"category\" = $categoryID";
        }
    }

    public function set_qflags($flag)
    {
        if ($flag == 'read') {
            $this->Qflag = ' AND usersetinfo."statusread"=1 ';
            $this->Qkill = ' AND (usersetinfo."statuskill" != 1 OR usersetinfo."statuskill" IS NULL)';
        } elseif ($flag == 'kill') {
            $this->killflag = TRUE;
            $this->Qkill = ' AND usersetinfo."statuskill"=1 ';
        } elseif ($flag == 'interesting') {
            $this->Qkill = ' AND (usersetinfo."statuskill" != 1 OR usersetinfo."statuskill" IS NULL)';
            $this->Qflag = ' AND usersetinfo."statusint"=1 ';
            $this->rss_flag = "&amp;flag=interesting&amp;userid={$this->userID}";
        } elseif ($flag == 'nzb') {
            $this->Qflag = ' AND usersetinfo."statusnzb"=1 ';
            $this->Qkill = ' AND (usersetinfo."statuskill" != 1 OR usersetinfo."statuskill" IS NULL)';
        } else {
            $this->Qkill = ' AND (usersetinfo."statuskill" != 1 OR usersetinfo."statuskill" IS NULL)';
        }
    }

    public function set_qsubcat($subcats, $not_subcats)
    {
        if ($subcats != array() ) {
            $subcat_subqry = array();
            foreach ($subcats as $s) {
                if ($s[0] == $this->categoryID) {
                    $sc = $s[1];
                    $sci = $s[1] . $s[2];
                    if (!isset($subcat_subqry[$sc])) {
                        $subcat_subqry[$sc] = '';
                    }
                    $subcat_subqry[$sc] .= ' OR ' . "( \"subcat$sc\" {$this->search_type} '%$sci|%' ) ";
                }
            }
            foreach ($subcat_subqry as $key=> $sc) {
                if ($sc != '') {
                    $this->Qsubcat .= " AND ( 0 = 1 $sc )";
                }
            }
        }
        if ($not_subcats != array() ) {
            $this->Qsubcat .= " AND NOT ( 0 = 1 ";
            foreach ($not_subcats as $s) {
                if ($s[0] == $this->categoryID) {
                    $sc = $s[1];
                    $sci = $s[1] . $s[2];
                    $this->Qsubcat .= ' OR ' . "( \"subcat$sc\" {$this->search_type} '%$sci|%' ) ";
                }
            }
            $this->Qsubcat .= ')';
        }
    }

    public function set_qspotid($spotid)
    {
        if ($spotid != '') {
            $this->db->escape($spotid, TRUE);
            $this->Qspotid = " AND spots.\"spotid\"=$spotid ";
        }
    }
    public function set_qposter($poster)
    {
        if ($poster != '') {
            $this->Qposter = " AND spots.\"poster\" {$this->search_type} '%{$poster}%' ";
        }
    }
    public function set_qspamlimit()
    {
        global $prefs;
        if (isset($prefs['spot_spam_limit']) && ($prefs['spot_spam_limit'] > 0)) {
            $spam_limit = $prefs['spot_spam_limit'];
            $this->db->escape($spam_limit, FALSE);
            $this->Qspamlimit = " AND (\"reports\" < $spam_limit) ";
        }
    }
    public function set_qorder($order)
    {
        if ($order == 'title') {
            $order = '';
        }

        $def_sort = map_default_sort(load_prefs($this->db, $this->userID), array('subject'=> 'title', 'date'=>'stamp', 'better_subject'=>'title'));

        $orderfield = str_ireplace(' desc', '', $order); // $order should be 'complete/subject/date/size' and optional ' desc' or 'asc'.
        $orderfield = trim(str_ireplace(' asc', '', $orderfield));

        if (!in_array(strtolower($orderfield), self::$sort_orders)) {
            $order = $def_sort;
        }

        if (!empty($order)) {
            $this->Qorder = $order;
        } else {
            $this->Qorder = $def_sort;
        }
    }
    public function set_qrating($minrating, $maxrating)
    {
        if (is_numeric($minrating) && $minrating > 0 && $minrating < 10) {
            $this->Qrating .= " AND (CAST(extsetdata4.\"value\" AS DECIMAL(5, 2)) >= $minrating) ";
        }
        if (is_numeric($maxrating) && $maxrating < 10 && $maxrating > 0) {
            $this->Qrating .= " AND (CAST(extsetdata4.\"value\" AS DECIMAL(5, 2)) <= $maxrating) ";
        }
    }

    public function set_qage($minage, $maxage)
    {
        if (is_numeric($maxage) && $maxage > 0) {
            $this->maxage = $maxage;
            $this->Qage .= 'AND (' . $this->now. " - stamp) / 3600 / 24 <= $maxage ";
        }
        if (is_numeric($minage) && $minage > 0) {
            $this->minage = $minage;
            $this->Qage .= 'AND (' . $this->now . " - stamp) / 3600 / 24 >= $minage ";
        }
    }

    public function set_qadult($is_adult)
    {
        if (!$is_adult) {
            $Qadult = ' AND (extsetdata3."value" != \'1\' OR extsetdata3."value" IS NULL)';
            $adult_subcats = SpotCategories::$adult_categories;
            $this->Qsubcat .= ' AND NOT ( 0 = 1 ';
            foreach ($adult_subcats as $s) {
                if ($s[0] == $this->categoryID) {
                    $sc = $s[1];
                    $sci = $s[1] . $s[2];
                    $Qsubcat .= ' OR ' . "( \"subcat$sc\" {$this->search_type} '%$sci|%' ) ";
                } else {
                    $hcat = $s[0];
                    $sc = $s[1];
                    $sci = $s[1] . $s[2];
                    $this->Qsubcat .= ' OR ' . "(\"category\" = '$hcat' AND \"subcat$sc\" {$this->search_type} '%$sci|%' ) ";
                }
            }
            $this->Qsubcat .= ')';
        }
    }
    public function get_rss_url($perpage)
    {
        $rss_limit = $perpage;
        $url = get_config($this->db, 'url');
        $type = USERSETTYPE_SPOT;
        $rssurl = $url . "html/rss.php?type=$type&amp;categoryID={$this->categoryID}&amp;limit=$rss_limit&amp;minsize={$this->minsetsize}&amp;maxsize={$this->maxsetsize}" .
            "&amp;maxage={$this->maxage}&amp;minage={$this->minage}{$this->rss_flag}&amp;userid={$this->userID}&amp;search=" . urlencode(utf8_decode($this->search));

        return $rssurl;
    }
    public function get_sort()
    {
        return divide_sort($this->Qorder);
    }
    public function get_killflag()
    {
        return $this->killflag;
    }
    public function set_search_options($search, $adult, $minage, $maxage, $spotid, $minrating, $maxrating, $poster, $categoryID, $subcats, $not_subcats, $flag, $minsetsize, $maxsetsize, $order)
    {
        $this->set_qsearch($search);
        $this->set_qadult($adult);
        $this->set_qage($minage, $maxage);
        $this->set_qspamlimit();
        $this->set_qspotid($spotid);
        $this->set_qrating($minrating, $maxrating);
        $this->set_qposter($poster);
        $this->set_qcategory($categoryID);
        $this->set_qsubcat($subcats, $not_subcats);
        $this->set_qflags($flag);
        $this->set_qsize($minsetsize, $maxsetsize);
        $this->set_qorder($order);
    }
}

list($subcats, $not_subcats) = get_subcats_requests();
$categoryID = get_request('categoryID', '');
$search     = html_entity_decode(trim(get_request('search', '')));
$adult      = urd_user_rights::is_adult($db, $userid);
$poster     = get_request('poster', '');
$maxage     = get_request('maxage', '');
$minage     = get_request('minage', '');
$spotid     = get_request('spotid', '');
$flag       = get_request('flag', '');
$minsetsize = get_request('minsetsize', NULL);
$maxsetsize = get_request('maxsetsize', NULL);
$order      = get_request('order', '');
$maxrating  = get_request('maxrating', '');
$minrating  = get_request('minrating', '');
$perpage    = get_maxperpage($db, $userid);
$perpage    = get_request('perpage', $perpage);
$only_rows  = get_request('only_rows', 0);
$offset     = get_request('offset', 0);

$spots_viewer = new spot_viewer($db, $userid);
$spots_viewer->set_search_options($search, $adult, $minage, $maxage, $spotid, $minrating, $maxrating, $poster, $categoryID, $subcats, $not_subcats, $flag, $minsetsize, $maxsetsize, $order);
list($pages, $activepage, $totalpages, $offset) = $spots_viewer->get_page_count($perpage, $offset, $only_rows);

$allsets = $spots_viewer->get_spot_data($perpage, $offset);
$rssurl = $spots_viewer->get_rss_url($perpage);

if ($only_rows && count($allsets) == 0) {
    throw new exception('No more rows');
}

init_smarty('', 0);
$smarty->assign('rssurl',		$rssurl);
$smarty->assign('isadmin',		$isadmin);
$smarty->assign('sort',         $spots_viewer->get_sort());
$smarty->assign('killflag',		$spots_viewer->get_killflag());

if (!$only_rows) {
    $smarty->assign('pages',		$pages);
    $smarty->assign('lastpage',		$totalpages);
    $smarty->assign('currentpage',	$activepage);
}

$smarty->assign('only_rows',        $only_rows);
$smarty->assign('categoryID',	    $categoryID);
$smarty->assign('allsets',		    $allsets);
$smarty->assign('show_subcats',     get_pref($db , 'show_subcats', $userid, 0));
$smarty->assign('show_comments',    get_config($db, 'download_spots_comments', 0));
$smarty->assign('USERSETTYPE_GROUP',   	USERSETTYPE_GROUP);
$smarty->assign('USERSETTYPE_RSS',   	USERSETTYPE_RSS);
$smarty->assign('USERSETTYPE_SPOT',   	USERSETTYPE_SPOT);

$smarty->display('ajax_spots.tpl');
