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
 * $LastChangedDate: 2014-05-30 00:49:17 +0200 (vr, 30 mei 2014) $
 * $Rev: 3077 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_rsssets.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */
if (!defined('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
}

$__auth = 'silent';
$pathidx = realpath(dirname(__FILE__));

require_once "$pathidx/../functions/ajax_includes.php";

if (!isset($_SESSION['setdata']) || !is_array($_SESSION['setdata'])) {
    $_SESSION['setdata'] = array();
}

verify_access($db, urd_modules::URD_CLASS_RSS, FALSE, '', $userid, TRUE);

class feed_viewer
{
    private static $sort_orders = array (
        '',
        'setname',
        'timestamp',
        'size',
        'better_subject',
        'rating'
    );

    private $Qsearch = '';
    private $Qcategory = '';
    private $Qkill = '';
    private $Qflag = '';
    private $rss_flag = '';
    private $Qsetid = '';
    private $Qsize = '';
    private $Qrating = '';
    private $Qage = '';
    private $Qspamlimit = '';
    private $Qadult = '';
    private $Qnewfeed1 = '';
    private $Qnewfeed2 = '';
    private $Qnewfeed3 = '';
    private $Qfeed_id = '';

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
        $this->input_arr = array();
        $this->Qnewfeed1 = ' userfeedinfo."feedid" = rss_sets."rss_id" ';
        $this->search_type = $this->db->get_pattern_search_command('LIKE'); // get the operator we need for the DB LIKE for mysql or ~~* for postgres
    }

    private function get_basic_browse_query()
    {
        $type = USERSETTYPE_RSS;
        $basic_browse_query =  ' FROM rss_sets ' .
            " LEFT JOIN userfeedinfo ON ({$this->Qnewfeed1} AND userfeedinfo.\"userid\" = :userid1) " .
            " LEFT JOIN rss_urls ON (rss_urls.\"id\" = rss_sets.\"rss_id\") " .
            " LEFT JOIN usersetinfo ON (usersetinfo.\"setID\" = rss_sets.\"setid\") AND (usersetinfo.\"userID\" = :userid2 ) AND (usersetinfo.\"type\" = :type1)" .
            " LEFT JOIN extsetdata AS extsetdata2 ON (extsetdata2.\"setID\" = rss_sets.\"setid\" AND extsetdata2.\"name\" = 'setname' AND extsetdata2.\"type\" = :type2) " .
            " LEFT JOIN extsetdata AS extsetdata1 ON (extsetdata1.\"setID\" = rss_sets.\"setid\" AND extsetdata1.\"name\" = 'link' AND extsetdata1.\"type\" = :type3) " .
            " LEFT JOIN extsetdata AS extsetdata3 ON (extsetdata3.\"setID\" = rss_sets.\"setid\" AND extsetdata3.\"name\" = 'score' AND extsetdata3.\"type\" = :type4) " .
            " LEFT JOIN extsetdata AS extsetdata4 ON (extsetdata4.\"setID\" = rss_sets.\"setid\" AND extsetdata4.\"name\" = 'xrated' AND extsetdata4.\"type\" = :type5) ";
        if ($this->Qsetid == '') {
            $basic_browse_query .= " WHERE (1=1 {$this->Qnewfeed2} {$this->Qfeed_id} {$this->Qsearch} {$this->Qflag} {$this->Qsize} {$this->Qkill} {$this->Qrating} ) AND (1=1 {$this->Qage} {$this->Qadult}) ";
        } else {
            $basic_browse_query .= " WHERE 1=1 {$this->Qsetid}";
        }
        $this->input_arr[':userid1'] = $this->userID;
        $this->input_arr[':userid2'] = $this->userID;
        $this->input_arr[':type1'] = $type;
        $this->input_arr[':type2'] = $type;
        $this->input_arr[':type3'] = $type;
        $this->input_arr[':type4'] = $type;
        $this->input_arr[':type5'] = $type;

        return $basic_browse_query;
    }
    private function get_sets($interesting_only)
    {
        $sql =
            "rss_sets.\"setid\", rss_sets.\"id\", rss_sets.\"setname\", rss_sets.\"timestamp\", rss_sets.\"nzb_link\", rss_sets.\"rss_id\", rss_sets.\"size\", " .
            "({$this->now} - \"timestamp\") AS \"age\", usersetinfo.\"statusint\" AS interesting, " .
            "usersetinfo.\"statusnzb\" AS \"nzbcreated\", usersetinfo.\"statusread\" AS \"alreadyread\", extsetdata2.\"value\" AS \"bettername\", extsetdata1.\"value\" AS imdblink, " .
            "(CASE WHEN extsetdata3.\"value\" IS NULL THEN '0' ELSE extsetdata3.\"value\" END) AS rating, " .
            "(CASE WHEN extsetdata2.\"value\" IS NULL THEN rss_sets.\"setname\" ELSE extsetdata2.\"value\" END) AS better_subject ";

        $sql .= $this->get_basic_browse_query();
        if ($interesting_only) {
            $sql .= ' AND usersetinfo."statusint" = 1';
        } else {
            $sql .= ' AND (usersetinfo."statusint" != 1 OR usersetinfo."statusint" IS NULL)';
        }

        $sql .= " ORDER BY {$this->Qorder}";
        return $sql;
    }
    private function get_sets_count($interesting_only)
    {
        $basic_browse_query = $this->get_basic_browse_query();
            $sql = 'COUNT(*) AS cnt ' . $basic_browse_query;
        if ($interesting_only) {
            $sql .= ' AND usersetinfo."statusint" = 1';
        }
        $res = $this->db->select_query($sql, $this->input_arr);
        if (!isset($res[0]['cnt'])) {
            throw new exception ($LN['error_setsnumberunknown']);
        }

        return $res[0]['cnt'];
    }
    public function get_page_count($perpage, $offset, $skip_total=FALSE)
    {
        if (! $skip_total) {
            $this->totalsets = $this->get_sets_count(FALSE);
        }
        $this->int_sets = $this->get_sets_count(TRUE);

        return get_pages($this->totalsets, $perpage, $offset);
    }
    public function get_set_data($perpage, $offset, &$last_line)
    {
        global $LN;
        $setres = array();
        if ($offset <= $this->int_sets) {
            $sql1 = $this->get_sets(TRUE);
            $setres = $this->db->select_query($sql1, $perpage, $offset, $this->input_arr);
            if ($setres === FALSE) {
                $setres = array();
            }
        }

        $setres_count = count($setres);
        if ($setres_count < $perpage) {
            $sql2 = $this->get_sets(FALSE);
            $setres2 = $this->db->select_query($sql2, $perpage - $setres_count, $offset - $this->int_sets, $this->input_arr);
            if ($setres2 === FALSE) {
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

        foreach ($setres as $arr) {
            // Show bar around interesting when applicable:
            $thisset = array();
            $thisset['interesting'] = $arr['interesting'];
            $thisset['sid'] = $arr['setid'];
            $thisset['added'] = (is_array($_SESSION['setdata']) && in_setdata($arr['setid'], 'rss', $_SESSION['setdata'])) ? 1 : 0; // todo
            $thisset['nzb'] = $arr['nzbcreated'];
            $thisset['read'] = $arr['alreadyread'];
            $thisset['setname'] = $arr['setname'];
            $thisset['link'] = $arr['nzb_link'];
            if ($arr['imdblink'] != NULL) {
                $thisset['imdblink'] = $arr['imdblink'];
            } else {
                $thisset['imdblink'] = '';
            }
            if ($arr['rating'] != 0) {
                $thisset['rating'] = sprintf('%.1f', $arr['rating']);
            } else {
                $thisset['rating'] = '';
            }
            if ($arr['bettername'] != '') {
                $thisset['setname'] = $arr['bettername'];
            } else {
                $clearname = $arr['setname'];
                // filter common spam in subjects
                $name = preg_replace('/(\byEnc\b)|(\breq:)|(\bwww\.[\w\.]*\b)|(\bhttp:\/\/[\w\.]*\b)|([=><#])/i', '', $clearname);
                $thisset['setname'] = $name;
            }

            $feed_id = $arr['rss_id'];
            if (isset($_SESSION['last_login']) && isset ($feed_lastupdate["$feed_id"]) && $_SESSION['last_login'] > 0 && $feed_lastupdate["$feed_id"] > 0) {
                $last_check_time = min($_SESSION['last_login'], $feed_lastupdate["$feed_id"]);
                $thisset['new_set'] = ($arr['date'] > $last_check_time) ? 1 : 0;
            } else {
                $thisset['new_set'] = 0;
            }
            $now = time();
            $age = ($now > $arr['timestamp']) ? $now - $arr['timestamp'] : 0;

            $thisset['age'] = readable_time($age,'largest');
            list($_size, $suffix) = format_size($arr['size'],'h' , $LN['byte_short'], 1024, 1);
            $thisset['size'] = $_size . ' ' . $suffix;
            $thisset['number'] = ++$number;
            $allsets[] = $thisset;
        }
        $last_line = $number;
        return $allsets;
    }
    public function set_qsize($ominsetsize, $omaxsetsize)
    {
        $this->minsetsize = $ominsetsize;
        $this->maxsetsize = $omaxsetsize;
        if ($ominsetsize != '') {
            try {
                $ominsetsize = unformat_size($ominsetsize, 1024, 'M');
            } catch (exception $e) {
                $ominsetsize = NULL;
            }
        } else {
            $ominsetsize = NULL;
        }
        if ($omaxsetsize != '') {
            try {
                $omaxsetsize = unformat_size($omaxsetsize, 1024, 'M');
            } catch (exception $e) {
                $omaxsetsize = NULL;
            }
        } else {
            $omaxsetsize = NULL;
        }

        if (is_numeric($ominsetsize)) {
            $this->input_arr[':minsetsize'] = $ominsetsize;
            $this->Qsize = ' AND (rss_sets."size" >= :minsetsize) ';
        } else {
            $this->Qsize = ' AND (rss_sets."size" >= ((SELECT CASE WHEN userfeedinfo."minsetsize" IS NULL THEN 0 ELSE userfeedinfo."minsetsize" END) ))';
        }

        if (is_numeric($omaxsetsize)) {
            $this->input_arr[':maxsetsize'] = $omaxsetsize;
            $this->Qsize .= ' AND (rss_sets."size" <= :maxsetsize ) ';
        } else {
            $this->Qsize .= ' AND (userfeedinfo."maxsetsize" = 0 OR userfeedinfo."maxsetsize" IS NULL OR rss_sets."size" <= (userfeedinfo."maxsetsize")) ';
        }
    }

    public function set_qadult($is_adult)
    {
        if (!$is_adult) {
            $this->Qadult = ' AND "adult" != \'' . ADULT_ON . '\' AND (extsetdata4."value" != \'1\' OR extsetdata4."value" IS NULL)';
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

    public function set_qrating($minrating, $maxrating)
    {
        if (is_numeric($minrating) && $minrating > 0 && $minrating < 10) {
            $this->input_arr[':minrating'] = $minrating;
            $this->Qrating .= ' AND (CAST(extsetdata3."value" AS DECIMAL(5, 2)) >= :minrating) ';
        }
        if (is_numeric($maxrating) && $maxrating < 10 && $maxrating > 0) {
            $this->input_arr[':maxrating'] = $maxrating;
            $this->Qrating .= ' AND (CAST(extsetdata3."value" AS DECIMAL(5, 2)) <= :maxrating) ';
        }
    }

    public function set_qsetid($setid)
    {
        if ($setid != '') {
            $this->input_arr[':setid'] = $setid;
            $this->Qsetid = ' AND rss_sets."setid" = :setid ';
        }
    }

    public function set_qage($minage, $maxage)
    {
        if (is_numeric($maxage) && $maxage > 0) {
            $this->maxage = $maxage;
            $this->input_arr[':maxage'] = $this->now - ($maxage * 3600 * 24);
            $this->Qage .= ' AND "timestamp" >= :maxage';
        }
        if (is_numeric($minage) && $minage > 0) {
            $this->minage = $minage;
            $this->input_arr[':minage'] = $this->now - ($minage * 3600 * 24);
            $this->Qage .= ' AND "timestamp" <= :minage';
        }

    }
    public function get_killflag()
    {
        return $this->killflag;
    }

    public function set_qorder($order)
    {
        // Validation of order:
        $def_sort = map_default_sort(load_prefs($this->db, $this->userID), array('subject'=> 'better_subject', 'date'=>'timestamp'));

        $orderfield = str_ireplace(' desc', '', $order); // $order should be 'complete/subject/date/size' and optional ' desc' or 'asc'.
        $orderfield = trim(str_ireplace(' asc', '', $orderfield));
        if (!in_array(strtolower($orderfield), self::$sort_orders)) {
            $order = $def_sort;
        }
        if (!empty($order)) {
            if ($order == 'rating') {
                $order = 'extsetdata3.value IS NULL, CAST(extsetdata3."value" AS decimal(5, 2))';
            }
            if ($order == 'setname') {
                $order = 'better_subject';
            }
            $this->Qorder = $order;
        } else {
            $this->Qorder = $def_sort;
        }
    }

    public function set_qfeed($feed_id)
    {
        if (isset($feed_id[9]) && substr_compare($feed_id, 'category_',0, 9) == 0) {
            $this->categoryID = substr($feed_id, 9);
            if (!is_numeric($this->categoryID)) {
                $this->categoryID = 0;
            }
            $this->feed_id = 0;
        } elseif (isset($feed_id[5]) && substr_compare($feed_id, 'feed_',0, 5) == 0) {
            $this->feed_id = substr($feed_id, 5);
            if (!is_numeric($this->feed_id)) {
                $this->feed_id = 0;
            }
            $this->categoryID = 0;
        } else {
            $this->feed_id = $this->categoryID = 0;
        }

        $categories = get_used_categories_rss($this->db, $this->userID);
        if ($this->categoryID > 0 && !in_array($this->categoryID, array_keys($categories))) {
            $this->categoryID = 0;
        }
        if ($this->feed_id != 0 && $this->feed_id != '') {

            $this->input_arr[':feed_id'] =  $this->feed_id;

            $this->Qfeed_id = 'AND "rss_id" = :feed_id ';
        } elseif ($this->categoryID != 0 && $this->categoryID != '') {
            $this->Qfeed_id .= ' AND rss_sets."rss_id" IN (';
            $feeds = get_feeds_by_category($this->db, $this->userID, $this->categoryID);
            $count = 0;
            foreach ($feeds as $feed) {
                $count++;
                $this->input_arr[":feed_id_$count" ] = $feedgr;
                $this->Qfeed_id .= " :feed_id_$count,";
            }
            $this->Qfeed_id = rtrim($this->Qfeed_id, ', ');
            $this->Qfeed_id .= ') ';
            if ($count == 0) {
                $this->Qfeed_id = '';
            }
        }
        if ($this->Qfeed_id == '') {
            $this->Qfeed_id = ' ';
            // Display all groups, except the ones that this user has marked invisible
            $this->Qnewfeed2 = ' AND (userfeedinfo."visible" = 1 OR userfeedinfo."visible" IS NULL) ';
        }
    }

    public function set_search_options($search, $feed_id, $adult, $minage, $maxage, $setid, $minrating, $maxrating, $flag, $minsetsize, $maxsetsize, $order)
    {
        $this->set_qsearch($search);
        $this->set_qadult($adult);
        $this->set_qage($minage, $maxage);
        $this->set_qsetid($setid);
        $this->set_qrating($minrating, $maxrating);
        $this->set_qfeed($feed_id);
        $this->set_qflags($flag);
        $this->set_qsize($minsetsize, $maxsetsize);
        $this->set_qorder($order);
    }
    public function get_sort()
    {
        return divide_sort($this->Qorder);
    }
    public function set_qsearch($search)
    {
        $this->search = $search;
        $this->Qsearch = parse_search_string($search, 'rss_sets."setname"', 'extsetdata2."value"', '', $this->search_type, $this->input_arr);
    }

    public function get_rss_url($perpage)
    {
        $rss_limit = $perpage;
        $url = get_config($this->db, 'baseurl');
        $minsetsize = get_pref($this->db, 'minsetsize', $this->userID, 0);
        $maxsetsize = get_pref($this->db, 'maxsetsize', $this->userID, '');
        $type = USERSETTYPE_RSS;
        if ($this->feed_id == 0) {
            $rss_feed_id = '0';
            $rss_minsetsize = $minsetsize;
            $rss_maxsetsize = $maxsetsize;
        } else {
            $rss_minsetsize = get_minsetsize_feed($this->db, $this->feed_id, $this->userID, $minsetsize);
            $rss_maxsetsize = get_maxsetsize_feed($this->db, $this->feed_id, $this->userID, $maxsetsize);
            $rss_feed_id = $this->feed_id;
        }
        if ($rss_maxsetsize == 0) { $rss_maxsetsize = '';}
        if ($rss_minsetsize == 0) { $rss_minsetsize = '';}
        $rssurl = $url . "html/rss.php?type=$type&amp;feed_id={$rss_feed_id}&amp;categoryID={$this->categoryID}&amp;limit=$rss_limit&amp;minsize={$rss_minsetsize}&amp;" .
            "maxsize={$rss_maxsetsize}&amp;maxage={$this->maxage}{$this->rss_flag}&amp;userid={$this->userID}&amp;search=" . urlencode($this->search);

        return $rssurl;
    }
}

try {
    $perpage = get_maxperpage($db, $userid);
    $perpage = get_request('perpage', $perpage);
    $only_rows  = get_request('only_rows', 0);
    $adult = urd_user_rights::is_adult($db, $userid);
    $feed_id = get_request('feed_id', 0);
    $search = html_entity_decode(get_request('search', ''));
    $offset  = get_request('offset', 0);
    $maxage  = get_request('maxage', 0);
    $order   = get_request('order', '');
    $flag    = get_request('flag', '');
    $maxage  = get_request('maxage', '');
    $minage  = get_request('minage', '');
    $minsetsize = get_request('minsetsize', '');
    $maxsetsize = get_request('maxsetsize', '');
    $maxrating  = get_request('maxrating', '');
    $minrating  = get_request('minrating', '');
    $setid   = get_request('setid','');

    $sets_viewer = new feed_viewer($db, $userid);
    $sets_viewer->set_search_options($search, $feed_id, $adult, $minage, $maxage, $setid, $minrating, $maxrating, $flag, $minsetsize, $maxsetsize, $order);
    list($pages, $activepage, $totalpages, $offset) = $sets_viewer->get_page_count($perpage, $offset, $only_rows);
    $allsets = $sets_viewer->get_set_data($perpage, $offset, $last_line);
    $rssurl = $sets_viewer->get_rss_url($perpage);

    init_smarty('', 0);
    $smarty->assign('rssurl',       $rssurl);
    $smarty->assign('sort',         $sets_viewer->get_sort());
    $smarty->assign('killflag',		$sets_viewer->get_killflag());
    $smarty->assign('isadmin',		$isadmin);
    $smarty->assign('feed_id',      $feed_id);
    if (!$only_rows) {
        $smarty->assign('pages',        $pages);
        $smarty->assign('lastpage',     $totalpages);
        $smarty->assign('currentpage',  $activepage);
    }
    $smarty->assign('allsets',      $allsets);
    $smarty->assign('USERSETTYPE_GROUP',    USERSETTYPE_GROUP);
    $smarty->assign('USERSETTYPE_RSS',      USERSETTYPE_RSS);
    $smarty->assign('only_rows',    $only_rows);

    $content = $smarty->fetch('ajax_rsssets.tpl');

    return_result(array(
        'content' => $content,
        'minsetsize' => $minsetsize,
        'maxsetsize' => $maxsetsize,
        'minage' => $minage,
        'maxage' => $maxage,
        'flag' => $flag,
        'minrating' => $minrating,
        'maxrating' => $maxrating,
        'last_line' => $last_line
    ));

} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
