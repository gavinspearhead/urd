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
 * $Id: ajax_browse.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
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
verify_access($db, urd_modules::URD_CLASS_GROUPS, FALSE, '', $userid);

class group_viewer
{
    private static $sort_orders = array (
        '',
        'complete',
        'subject',
        'date',
        'size',
        'better_subject',
        'rating'
    );

    private $Qsearch = '';
    private $Qsize = '';
    private $Qcategory = '';
    private $Qkill = '';
    private $Qflag = '';
    private $Qsubcat = '';
    private $Qsetid = '';
    private $Qgroup = '';
    private $Qrating = '';
    private $Qadult = '';
    private $Qnewgroup1 = '';
    private $Qnewgroup2 = '';
    private $Qnewgroup3 = '';
    private $Qage = '';
    private $Qcomplete = '';

    private $minage = '';
    private $maxage = '';
    private $minsetsize = '';
    private $maxsetsize = '';
    private $search = '';
    private $killflag = FALSE;
    private $rss_flag = '';
    private $categoryID = '';

    private $userid;
    private $db;
    private $now;
    private $search_type;
    private $greatest;

    private $totalsets = 0;
    private $int_sets = 0;

    public function __construct(DatabaseConnection $db, $userid)
    {
        assert(is_numeric($userid));
        $this->db = $db;
        $this->userID = $userid;
        $this->now = time();
        $this->Qnewgroup1 = 'usergroupinfo."groupid" = setdata."groupID"';
        $this->Qnewgroup3 = 'groups."ID" = setdata."groupID"';
        $this->search_type = $this->db->get_pattern_search_command('LIKE'); // get the operator we need for the DB LIKE for mysql or ~~* for postgres
        $this->GREATEST = $this->db->get_greatest_function();
    }

    private function get_basic_browse_query()
    {
        $basic_browse_query =
            ' FROM setdata ' .
            " LEFT JOIN usergroupinfo ON ({$this->Qnewgroup1} AND (usergroupinfo.\"userid\" = {$this->userID})) " .
            " LEFT JOIN groups ON ({$this->Qnewgroup3}) " .
            " LEFT JOIN usersetinfo ON ((usersetinfo.\"setID\" = setdata.\"ID\") AND (usersetinfo.\"userID\" = {$this->userID})) AND (usersetinfo.\"type\" = '" . USERSETTYPE_GROUP . "') " .
            " LEFT JOIN extsetdata AS extsetdata2 ON (extsetdata2.\"setID\" = setdata.\"ID\" AND extsetdata2.\"name\" = 'setname' AND extsetdata2.\"type\" = '" . USERSETTYPE_GROUP . "' ) " .
            " LEFT JOIN extsetdata AS extsetdata1 ON (extsetdata1.\"setID\" = setdata.\"ID\" AND extsetdata1.\"name\" = 'link' AND extsetdata1.\"type\" = '" . USERSETTYPE_GROUP . "') " .
            " LEFT JOIN extsetdata AS extsetdata3 ON (extsetdata3.\"setID\" = setdata.\"ID\" AND extsetdata3.\"name\" = 'score' AND extsetdata3.\"type\" = '" . USERSETTYPE_GROUP . "' ) " .
            " LEFT JOIN extsetdata AS extsetdata4 ON (extsetdata4.\"setID\" = setdata.\"ID\" AND extsetdata4.\"name\" = 'xrated' AND extsetdata4.\"type\" = '" . USERSETTYPE_GROUP . "' ) " .
            " LEFT JOIN extsetdata AS extsetdata5 ON (extsetdata5.\"setID\" = setdata.\"ID\" AND extsetdata5.\"name\" = 'binarytype' AND extsetdata5.\"type\" = '" . USERSETTYPE_GROUP . "' ) " .
            " WHERE (1=1 {$this->Qnewgroup2} {$this->Qsearch} {$this->Qsize} {$this->Qsetid} {$this->Qgroup} {$this->Qkill} {$this->Qflag} {$this->Qrating}) " .
            " AND (1=1 {$this->Qage} {$this->Qadult} {$this->Qcomplete}) ";

        return $basic_browse_query;
    }
    private function get_sets($interesting_only)
    {
        $sql = ' setdata."ID", "subject", "articlesmax", setdata."groupID", setdata."date", setdata."size", ' .
            "(100 * \"binaries\" / {$this->GREATEST}(1, \"articlesmax\")) AS \"complete\", ({$this->now} - \"date\") AS \"age\",  " .
            '(CASE WHEN usersetinfo."statusint" IS NULL OR usersetinfo."statusint" <> 1 THEN 0 ELSE 1 END) AS interesting,' .
            'usersetinfo."statusnzb" AS "nzbcreated", usersetinfo."statusread" AS "alreadyread", extsetdata2."value" AS "bettername", extsetdata1."value" AS "imdblink", ' .
            '(CASE WHEN extsetdata3."value" IS NULL THEN \'0\' ELSE extsetdata3."value" END) AS "rating", ' .
            '(CASE WHEN extsetdata2."value" IS NULL OR extsetdata2."value" = \'\' THEN setdata."subject" ELSE extsetdata2."value" END) AS "better_subject", ' .
            '(CASE WHEN extsetdata5."value" IS NULL OR extsetdata5."value" = \'\' THEN 0 ELSE extsetdata5."value" END) AS "binary_type" ';
        $sql .=	$this->get_basic_browse_query();
        if ($interesting_only) {
            $sql .= ' AND usersetinfo."statusint" = 1 ';
        } else {
            $sql .= ' AND (usersetinfo."statusint" != 1 OR usersetinfo."statusint" IS NULL) ';
        }
        $sql .= " ORDER BY {$this->Qorder}";
        return $sql;
    }
    private function get_sets_count($interesting_only)
    {
        global $LN;
        $basic_browse_query = $this->get_basic_browse_query();
        $sql = 'COUNT(*) AS cnt ' . $basic_browse_query;
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
        assert(is_numeric($perpage) && is_numeric($offset));
        if (! $skip_total) {
            $this->totalsets = $this->get_sets_count(FALSE);
        }
        $this->int_sets = $this->get_sets_count(TRUE);
        return get_pages($this->totalsets, $perpage, $offset);
    }

    public function get_set_data($perpage, $offset)
    {
        assert(is_numeric($perpage) && is_numeric($offset));
        global $LN;

        $setres = array();
        if ($offset <= $this->int_sets) {
            $sql1 = $this->get_sets(TRUE);
            $setres = $this->db->select_query($sql1, $perpage, $offset);
            if (!is_array($setres)) {
                $setres = array();
            }
        }
        $setres_count = count($setres);

        if ($setres_count < $perpage) {
            $sql2 = $this->get_sets(FALSE);
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
        $group_lastupdate = get_group_last_updated($this->db, $this->groupID, $this->userID);
        $number = $offset;
        foreach ($setres as $arr) {
            // Show bar around interesting when applicable:
            $thisset = array();
            $thisset['interesting'] = $arr['interesting'];
            $thisset['sid'] = $arr['ID'];
            $thisset['added'] = (is_array($_SESSION['setdata']) && in_setdata($arr['ID'], 'group', $_SESSION['setdata'])) ? 1 : 0;
            $thisset['nzb'] = $arr['nzbcreated'];
            $thisset['read'] = $arr['alreadyread'];
            $thisset['subject'] = $arr['better_subject'];
            $thisset['rating'] = '';
            if ($arr['rating'] != 0) {
                $thisset['rating'] = round_rating(sprintf('%.1f', $arr['rating']));
            }

            $thisset['imdblink'] = '';
            if ($arr['imdblink'] != NULL) {
                $thisset['imdblink'] = $arr['imdblink'];
            }

            if ($arr['bettername'] != '') {
                $thisset['name'] = utf8_decode(html_entity_decode($arr['bettername']));
            } else {
                $clearname = $arr['subject'];
                // filter common spam in subjects
                $name = preg_replace('/(\byEnc\b)|(\breq:)|(\bwww\.[\w\.]*\b)|(\bhttp:\/\/[\w\.]*\b)|([=><#])/i', '', $clearname);
                $thisset['name'] = $name;// we utf8_encode before putting it in the db (otherwise pg will complain); but why need we decode it here so that all funny (german) characters are shown properly???
            }

            // Determine completeness:
            if ($arr['articlesmax'] == 0) {
                $complete = '-1';
            } else {
                $complete = floor($arr['complete']);
            }

            $thisset['complete'] = $complete;
            $thisset['binary_type'] = $arr['binary_type'];
            $groupID = $arr['groupID'];
            $thisset['new_set'] = 0;
            $last_login = get_session('last_login', 0);
            if (isset($group_lastupdate["$groupID"]) && ($last_login > 0) && ($group_lastupdate["$groupID"] > 0)) {
                $last_check_time = min($last_login, $group_lastupdate["$groupID"]);
                $thisset['new_set'] = ($arr['date'] > $last_check_time) ? 1 : 0;
            }
            $age = ($this->now > $arr['date']) ? $this->now - $arr['date'] : 0;

            $thisset['age'] = readable_time($age, 'largest_two');
            list($_size, $suffix) = format_size($arr['size'], 'h', $LN['byte_short'], 1024, 1);
            $thisset['size'] = $_size . ' ' . $suffix;
            $thisset['number'] = ++$number;
            $allsets[] = $thisset;
        }

        return $allsets;
    }
    public function get_killflag()
    {
        return $this->killflag;
    }
    public function set_qorder($order)
    {
        // Validation of order:
        $def_sort = get_pref($this->db, 'defaultsort', $this->userID, 'better_subject');
        $orderfield = str_ireplace(' desc', '', $order); // $order should be 'complete/subject/date/size' and optional ' desc' or 'asc'.
        $orderfield = trim(str_ireplace(' asc', '', $orderfield));

        if ($order == 'subject') {
            $order = 'better_subject';
        }
        if (!in_array(strtolower($orderfield), self::$sort_orders)) {
            $order = $def_sort;
        }
        if ($order == 'rating') {
                $order = 'extsetdata3.value IS NULL, CAST(extsetdata3."value" AS decimal(5, 2))';
        }
        if (!empty($order)) {
            $this->Qorder = $order;
        } else {
            $this->Qorder = $def_sort;
        }
    }

    public function get_rss_url($perpage)
    {
        assert(is_numeric($perpage));
        $rss_limit = $perpage;
        $url = get_config($this->db, 'url');
        $type = USERSETTYPE_GROUP;
        $minsetsize = get_pref($this->db, 'minsetsize', $this->userID, 0);
        $maxsetsize = get_pref($this->db, 'maxsetsize', $this->userID, 0);
        if ($this->groupID == 0) {
            $rss_groupid = '0';
            $rss_minsetsize = $minsetsize;
            $rss_maxsetsize = $maxsetsize;
        } else {
            $rss_minsetsize = get_minsetsize_group($this->db, $this->groupID, $this->userID, $minsetsize);
            $rss_maxsetsize = get_maxsetsize_group($this->db, $this->groupID, $this->userID, $maxsetsize);
            $rss_groupid = $this->groupID;
        }
        if ($rss_maxsetsize == 0) { $rss_maxsetsize = ''; }
        if ($rss_minsetsize == 0) { $rss_minsetsize = ''; }
        $rssurl = $url . "html/rss.php?type=$type&amp;groupID={$rss_groupid}&amp;categoryID={$this->categoryID}&amp;limit=$rss_limit&amp;minsize={$rss_minsetsize}&amp;" .
            "maxsize={$rss_maxsetsize}&amp;maxage={$this->maxage}{$this->rss_flag}&amp;userid={$this->userID}&amp;search=" . urlencode($this->search);

        return $rssurl;
    }
    public function get_sort()
    {
        return divide_sort($this->Qorder);
    }
    public function set_qsearch($search)
    {
        $this->search = $search;
        $this->db->escape($search);
        $this->Qsearch = parse_search_string($search, '"subject"', 'extsetdata2."value"', '', $this->search_type);
    }

    public function set_qgroup($groupID)
    {
        if (isset($groupID[9]) && substr_compare($groupID, 'category_',0, 9) == 0) {
            $this->categoryID = substr($groupID, 9);
            if (!is_numeric($this->categoryID)) {
                $categoryID = 0;
            }
            $this->groupID = 0;
        } elseif (isset($groupID[6]) && substr_compare($groupID, 'group_',0, 6) == 0) {
            $this->groupID = substr($groupID, 6);
            if (!is_numeric($this->groupID)) {
                $this->groupID = 0;
            }
            $this->categoryID = 0;
        } else {
            $this->groupID = $this->categoryID = 0;
        }

        $categories = get_used_categories_group($this->db, $this->userID);
        if ($this->categoryID > 0 && !in_array($this->categoryID, array_keys($categories))) {
            $this->categoryID = 0;
        }

        if ($this->groupID != 0 && $this->groupID != '') {
            // Display this group only
            $this->Qgroup = " AND setdata.\"groupID\" = {$this->groupID} ";
        } elseif ($this->categoryID != 0 && $this->categoryID != '') {
            $this->Qgroup .= " AND setdata.\"groupID\" IN (";
            $groups = get_groups_by_category($this->db, $this->userID, $this->categoryID);
            $count = 0;
            foreach ($groups as $gr) {
                $this->Qgroup .= " $gr,";
                $count++;
            }
            $this->Qgroup = rtrim($this->Qgroup, ',');
            $this->Qgroup .= ')';
            if ($count == 0) {
                $this->Qgroup = '';
            }
        }

        if ($this->Qgroup == '') {
            $this->Qgroup = ' ';
            // Display all groups, except the ones that this user has marked invisible
            $this->Qnewgroup2 = ' AND (usergroupinfo."visible" = 1 OR usergroupinfo."visible" IS NULL) ';
        }
    }

    public function set_qsize($ominsetsize, $omaxsetsize)
    {
        $this->minsetsize = $ominsetsize;
        $this->maxsetsize = $omaxsetsize;
        if ($ominsetsize != '') {
            try {
                $ominsetsize = unformat_size($ominsetsize, 1024, 'M');
                if ($ominsetsize < 0) { 
                    $ominsetsize = NULL;
                }

            } catch (exception $e) {
                $ominsetsize = NULL;
            }
        } else {
            $ominsetsize = NULL;
        }
        
        if ($omaxsetsize != '') {
            try {
                $omaxsetsize = unformat_size($omaxsetsize, 1024, 'M');
                if ($ominsetsize !== NULL && $omaxsetsize < $ominsetsize) {
                    $omaxsetsize = NULL;
                }

            } catch (exception $e) {
                $omaxsetsize = NULL;
            }
        } else {
            $omaxsetsize = NULL;
        }
        if (is_numeric($ominsetsize)) {
            $this->db->escape($ominsetsize, TRUE);
            $this->Qsize = " AND (setdata.\"size\" >= $ominsetsize) ";
        } else {
            $this->Qsize = ' AND (setdata."size" >= ((SELECT CASE WHEN usergroupinfo."minsetsize" IS NULL THEN 0 ELSE usergroupinfo."minsetsize" END)))';
        }

        if (is_numeric($omaxsetsize)) {
            $this->db->escape($omaxsetsize, TRUE);
            $this->Qsize .= " AND (setdata.\"size\" <= $omaxsetsize) ";
        } else {
            $this->Qsize .= ' AND (usergroupinfo."maxsetsize" = 0 OR usergroupinfo."maxsetsize" IS NULL OR setdata."size" <= ( usergroupinfo."maxsetsize")) ';
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
            $this->Qrating .= " AND (CAST(extsetdata3.\"value\" AS DECIMAL(5, 2)) >= $minrating) ";
        }
        if (is_numeric($maxrating) && $maxrating < 10 && $maxrating > 0) {
            $this->Qrating .= " AND (CAST(extsetdata3.\"value\" AS DECIMAL(5, 2)) <= $maxrating) ";
        }
    }
    public function set_qsetid($setid)
    {
        if ($setid != '') {
            $this->db->escape($setid, TRUE);
            $this->Qsetid = " AND setdata.\"ID\"=$setid ";
        }
    }

    public function set_qcomplete($mincomplete, $maxcomplete)
    {
        $this->mincomplete = $mincomplete;
        $this->maxcomplete = $maxcomplete;

        $setcompleteness = get_pref($this->db, 'setcompleteness', $this->userID, 0);
        if (is_numeric($mincomplete) || is_numeric($maxcomplete)) {
            if (is_numeric($mincomplete) && $mincomplete < 100 && $mincomplete > 0) {
                $this->db->escape($mincomplete);
                $this->Qcomplete .= " AND (\"articlesmax\"=0 OR floor(\"binaries\" * 100 / {$this->GREATEST}(1, \"articlesmax\")) >= $mincomplete ) ";
            }
            if (is_numeric($maxcomplete) && $maxcomplete < 100 && $maxcomplete > 0) {
                $this->db->escape($maxcomplete);
                $this->Qcomplete .= " AND (\"articlesmax\"=0 OR floor(\"binaries\" * 100 / {$this->GREATEST}(1, \"articlesmax\")) <= $maxcomplete ) ";
            }
        } elseif ($setcompleteness > 0) {
            $this->Qcomplete = " AND (\"articlesmax\"=0 OR floor(\"binaries\" * 100 / {$this->GREATEST}(1, \"articlesmax\")) >= $setcompleteness)";/// euah ... the horror... but it is ansi sql compliant... no refers to as fields in where clauses ...
        }

    }
    public function set_qage($minage, $maxage)
    {
        if (is_numeric($maxage) && $maxage > 0) {
            $this->maxage = $maxage;
            $maxage = $this->now - ($maxage * 3600 * 24);
            $this->Qage .= " AND \"date\" >= $maxage";
        }
        if (is_numeric($minage) && $minage > 0) {
            $this->minage = $minage;
            $minage = $this->now - ($minage * 3600 * 24);
            $this->Qage .= " AND \"date\" <= $minage";
        }

    }
    public function set_search_options($search, $groupID, $adult, $minage, $maxage, $setid, $minrating, $maxrating, $flag, $minsetsize, $maxsetsize, $mincomplete, $maxcomplete, $order)
    {
        $this->set_qsearch($search);
        $this->set_qadult($adult);
        $this->set_qage($minage, $maxage);
        $this->set_qsetid($setid);
        $this->set_qrating($minrating, $maxrating);
        $this->set_qcomplete($mincomplete, $maxcomplete);
        $this->set_qgroup($groupID);
        $this->set_qflags($flag);
        $this->set_qsize($minsetsize, $maxsetsize);
        $this->set_qorder($order);
    }
}

try {
    $adult = urd_user_rights::is_adult($db, $userid);
    $search = html_entity_decode(trim(get_request('search', '')));
    $offset = get_request('offset', 0);

    $order = get_request('order', '');
    $flag = get_request('flag', '');
    $maxage = get_request('maxage', '');
    $minage = get_request('minage', '');
    $minsetsize = get_request('minsetsize', NULL);
    $maxsetsize = get_request('maxsetsize', NULL);
    $groupID = get_request('groupID', 0);

    $maxrating = get_request('maxrating', '');
    $minrating = get_request('minrating', '');
    $maxcomplete = get_request('maxcomplete', '');
    $mincomplete = get_request('mincomplete', '');

    $setid = get_request('setid', '');
    $perpage = get_maxperpage($db, $userid);
    $perpage = get_request('perpage', $perpage);
    $only_rows = get_request('only_rows', 0);

    $sets_viewer = new group_viewer($db, $userid);
    $sets_viewer->set_search_options($search, $groupID, $adult, $minage, $maxage, $setid, $minrating, $maxrating, $flag, $minsetsize, $maxsetsize, $mincomplete, $maxcomplete, $order);
    list($pages, $activepage, $totalpages, $offset) = $sets_viewer->get_page_count($perpage, $offset, $only_rows);
    $allsets = $sets_viewer->get_set_data($perpage, $offset);
    $rssurl = $sets_viewer->get_rss_url($perpage);

    init_smarty('', 0);
    $smarty->assign('rssurl',		        $rssurl);
    $smarty->assign('sort',                 $sets_viewer->get_sort());
    $smarty->assign('killflag',		        $sets_viewer->get_killflag());
    $smarty->assign('isadmin',		        $isadmin);
    
    if (!$only_rows) {
        $smarty->assign('pages',		    $pages);
        $smarty->assign('lastpage',		    $totalpages);
        $smarty->assign('currentpage',	    $activepage);
    }
    $smarty->assign('allsets',		        $allsets);
    $smarty->assign('USERSETTYPE_GROUP',   	USERSETTYPE_GROUP);
    $smarty->assign('USERSETTYPE_RSS',   	USERSETTYPE_RSS);
    $smarty->assign('only_rows',            $only_rows);

    $content = $smarty->fetch('ajax_browse.tpl');

    return_result(array(
        'content' => $content,
        'minsetsize' => $minsetsize,
        'maxsetsize' => $maxsetsize,
        'minage' => $minage,
        'maxage' => $maxage,
        'flag' => $flag,
        'minrating' => $minrating,
        'maxrating' => $maxrating,
        'mincomplete' => $mincomplete,
        'maxcomplete' => $maxcomplete
    ));

} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
