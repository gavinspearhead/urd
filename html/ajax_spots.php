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

function pdo_sql_debug($sql,$placeholders)
{
    foreach($placeholders as $k => $v){
        $sql = preg_replace('/'.$k.'/',"'$v'",$sql);
    }
    return $sql;
}


$__auth = 'silent';
$pathidx = realpath(dirname(__FILE__));

require_once "$pathidx/../functions/ajax_includes.php";

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
    private $Qreference = '';

    private $minage = '';
    private $maxage = '';
    private $minsetsize = '';
    private $maxsetsize = '';
    private $search = '';
    private $killflag = FALSE;
    private $categoryID;

    private $userid = -1;
    private $db = NULL;
    private $now = 0;
    private $search_type = '';
    private $totalsets = 0;
    private $int_sets = 0;
    private $type = 0; // 0 : basic; 1 :modern

    public function __construct(DatabaseConnection& $db, $userid, $type)
    {
        assert(is_numeric($userid));
        $this->db = &$db;
        $this->userID = $userid;
        $this->now = time();
        $this->input_arr = array();
        $this->type = $type;
        $this->search_type = $this->db->get_pattern_search_command('LIKE'); // get the operator we need for the DB LIKE for mysql or ~~* for postgres
    }

    private function get_basic_browse_query($do_count = FALSE)
    {
        $type = USERSETTYPE_SPOT;
        $basic_browse_query = ' FROM spots ';
        if (!$do_count) { 
            if ($this->type == 1) {
                $basic_browse_query .= ' LEFT JOIN spot_images ON (spots."spotid" = spot_images."spotid") ';
            }
            $this->input_arr[':userid'] = $this->userID;
            $this->input_arr[':superuserid'] = user_status::SUPER_USERID;
            $this->input_arr[':wlstatus'] = whitelist::ACTIVE;
        }
        $basic_browse_query .= 
            ' LEFT JOIN spot_blacklist ON (spots."spotter_id" = spot_blacklist."spotter_id" AND spot_blacklist."userid" IN (:userid1, :superuserid1) AND spot_blacklist."status" = :blstatus) ' .
            ' LEFT JOIN usersetinfo ON ((usersetinfo."setID" = spots."spotid") AND (usersetinfo."userID" = :userid2)) AND (usersetinfo."type" = :type1) ' .
            ' LEFT JOIN extsetdata AS extsetdata2 ON (extsetdata2."setID" = spots."spotid" AND extsetdata2."name" = \'setname\' AND extsetdata2."type" = :type2) ' .
            ' LEFT JOIN extsetdata AS extsetdata3 ON (extsetdata3."setID" = spots."spotid" AND extsetdata3."name" = \'xrated\' AND extsetdata3."type" = :type3) ' .
            ' LEFT JOIN extsetdata AS extsetdata4 ON (extsetdata4."setID" = spots."spotid" AND extsetdata4."name" = \'score\' AND extsetdata4."type" = :type4) ' .
            " WHERE (1=1 {$this->Qsearch} {$this->Qsize} {$this->Qcategory} {$this->Qkill} {$this->Qflag} {$this->Qsubcat} {$this->Qspotid} {$this->Qposter} " .
            " {$this->Qspamlimit} {$this->Qrating} {$this->Qage} {$this->Qadult} {$this->Qreference}  AND spot_blacklist.\"spotter_id\" IS NULL)";
        $this->input_arr[':userid1'] = $this->input_arr[':userid2'] = $this->userID;
        $this->input_arr[':superuserid1'] = user_status::SUPER_USERID;
        $this->input_arr[':type1'] = $this->input_arr[':type2'] = $this->input_arr[':type3'] = $this->input_arr[':type4'] = $type;
        $this->input_arr[':blstatus'] = blacklist::ACTIVE;

        return $basic_browse_query;
    }
    private function get_spots($interesting_only=FALSE)
    {
        $sql = '';
        if ($this->type == 1) {
            $sql .= 'spots."description", "image", "image_file", "reference", spots."spotter_id", ';
        }

        $sql .= ' (SELECT count(spot_whitelist.spotter_id) FROM spot_whitelist WHERE spots."spotter_id" = spot_whitelist."spotter_id" AND spot_whitelist."userid" IN (:userid, :superuserid) AND spot_whitelist."status" = :wlstatus) AS whitelisted, ' ;
        $sql .= '"title", spots."size", spots."spotid", spots."stamp", spots."reports", spots."comments", spots."poster",' .
            '"category", "subcata", "subcatb", "subcatc", "subcatd", "subcatz", spots."url", ' . 
            'extsetdata2."value" AS "bettername", spots."rating" AS spots_rating, ' .
            '(CASE WHEN usersetinfo."statusread" IS NULL OR usersetinfo."statusread" <> 1 THEN 0 ELSE 1 END) AS "alreadyread", ' .
            '(CASE WHEN usersetinfo."statusnzb" IS NULL OR usersetinfo."statusnzb" <> 1 THEN 0 ELSE 1 END) AS "nzbcreated", ' .
            '(CASE WHEN usersetinfo."statusint" IS NULL OR usersetinfo."statusint" <> 1 THEN 0 ELSE 1 END) AS "interesting", ' .
            '(CASE WHEN extsetdata4."value" IS NULL THEN \'0\' ELSE extsetdata4."value" END) AS "rating" ';
        $sql .=	$this->get_basic_browse_query();
        if ($interesting_only) {
            $sql .= ' AND usersetinfo."statusint" = 1';
        } else {
            $sql .= ' AND (usersetinfo."statusint" != 1 OR usersetinfo."statusint" IS NULL)';
        }
        $sql .= " ORDER BY {$this->Qorder}";
        return $sql;
    }
    private function get_spots_count($interesting_only)
    {
        global $LN;
        $basic_browse_query = $this->get_basic_browse_query(TRUE);
        $sql = 'COUNT(*) AS cnt ' . $basic_browse_query;
        if ($interesting_only) {
            $sql .= ' AND usersetinfo."statusint" = 1';
        }
        $res = $this->db->select_query($sql, $this->input_arr);
        if (!isset($res[0]['cnt'])) {
            throw new exception($LN['error_setsnumberunknown']);
        }

        return $res[0]['cnt'];
    }
    public function get_page_count($perpage, $offset, $skip_total=FALSE)
    {
        assert(is_numeric($perpage) && is_numeric($offset));
        if (! $skip_total) {
            $this->totalsets = $this->get_spots_count(FALSE);
        }
        $this->int_sets = $this->get_spots_count(TRUE);

        return get_pages($this->totalsets, $perpage, $offset);
    }
    public function get_spot_data($perpage, $offset, &$last_item, $userid)
    {
        assert(is_numeric($perpage) && is_numeric($offset));
        global $LN;

        $setres = array();
        if ($offset <= $this->int_sets) {
            $sql1 = $this->get_spots(TRUE);
            file_put_contents('/tmp/foo', pdo_sql_debug($sql1, $this->input_arr). "\n", FILE_APPEND);

            $setres = $this->db->select_query($sql1, $perpage, $offset, $this->input_arr);
            if (!is_array($setres)) {
                $setres = array();
            }
        }
        $setres_count = count($setres);

        if ($setres_count < $perpage) {
            $sql2 = $this->get_spots(FALSE);
            file_put_contents('/tmp/foo', pdo_sql_debug($sql2, $this->input_arr). "\n", FILE_APPEND);

            $setres2 = $this->db->select_query($sql2, $perpage - $setres_count, $offset - $this->int_sets, $this->input_arr);
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
            $thisset['spotid'] = $arr['spotid'];
            $thisset['comments'] = is_numeric($arr['comments']) ? $arr['comments'] : 0;
            $thisset['reports'] = is_numeric($arr['reports']) ? $arr['reports'] : 0;
            $thisset['categorynr'] = $arr['category'];
            $thisset['anon_url'] = make_url($this->db, trim(strip_tags($arr['url'])), $this->userID);
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
            } elseif ($arr['spots_rating'] != 0) {
                $thisset['rating'] = $arr['spots_rating'];
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

            $thisset['new_set'] = 0;
            if (isset($_SESSION['last_login']) && $_SESSION['last_login'] > 0 && $group_lastupdate > 0) {
                $last_check_time = min($_SESSION['last_login'], $group_lastupdate);
                $thisset['new_set'] = ($arr['stamp'] > $last_check_time) ? 1 : 0;
            }
            
            if ($this->type == 1) {
                $thisset['spotter_id'] = $arr['spotter_id'];
                $thisset['reference'] = $arr['reference'];
                $description = trim(db_decompress($arr['description']));
                $description = link_to_url($this->db, $description, $userid);
                $ubb = new UbbParse($description);
                TagHandler::setDeniedTags( array() );
                //TagHandler::setadditionalinfo('img', 'allowedimgs', get_smileys($smarty->getTemplateVars('IMGDIR'), TRUE));
                $thisset['description'] = insert_wbr($ubb->parse());
                $thisset['first_two_words'] = get_first_two_words($thisset['subject']);
                $thisset['image_file'] = $thisset['image'] = '';
                $thisset['image_from_db'] = 0;

                if (substr($arr['image'], 0, 10) == 'data:image') {
                    $thisset['image_from_db'] = 1;
                } elseif (substr($arr['image'], 0, 9) == 'articles:') {
                    $thisset['image_file'] = get_dlpath($this->db). IMAGE_CACHE_PATH . $arr['spotid'] . '.jpg';
                    if (!file_exists($thisset['image_file'])) {
                        $thisset['image_file'] = '';
                    }
                } else {
                    $thisset['image'] = trim(strip_tags($arr['image']));
                }
            }

            $thisset['subcata'] = get_subcats($arr['category'], $arr['subcata']);
            $thisset['subcatb'] = get_subcats($arr['category'], $arr['subcatb']);
            $thisset['subcatc'] = get_subcats($arr['category'], $arr['subcatc']);
            $thisset['subcatd'] = get_subcats($arr['category'], $arr['subcatd']);
            $thisset['subcatz'] = get_subcats($arr['category'], $arr['subcatz']);
            $age = ($this->now > $arr['stamp']) ? $this->now - $arr['stamp'] : 0;

            $thisset['age'] = readable_time($age, 'largest');
            list($_size, $suffix) = format_size($arr['size'], 'h', $LN['byte_short'], 1024, 1);
            $thisset['size'] = $_size . ' ' . $suffix;
            $thisset['number'] = ++$number;
            $allsets[] = $thisset;
        }
        $last_item = $number;
        return $allsets;
    }

    public function set_qsearch($search)
    {
        $this->search = $search;
        $this->Qsearch = parse_search_string($search, '"title"', 'spots."tag"', 'extsetdata2."value"', $this->search_type, $this->input_arr);
    }
    public function set_qsize($ominsetsize, $omaxsetsize)
    {
        $minsetsize = get_pref($this->db, 'minsetsize', $this->userID, 0);
        $maxsetsize = get_pref($this->db, 'maxsetsize', $this->userID, 0);
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
        $this->minsetsize = $ominsetsize;
        $this->maxsetsize = $omaxsetsize;
        if (is_numeric($ominsetsize) && $ominsetsize > 0) {
            $this->input_arr[':minsetsize'] = $ominsetsize;
            $this->Qsize = ' AND (spots."size" >= :minsetsize) ';
        } elseif ($minsetsize > 0) {
            $this->input_arr[':minsetsize'] = $minsetsize;
            $this->Qsize = ' AND (spots."size" >= :minsetsize) ';
        }

        if (is_numeric($omaxsetsize) && $omaxsetsize > 0) {
            $this->input_arr[':maxsetsize'] = $omaxsetsize;
            $this->Qsize .= ' AND (spots."size" <= :maxsetsize) ';
        } elseif ($maxsetsize > 0) {
            $this->input_arr[':maxsetsize'] = $maxsetsize;
            $this->Qsize .= ' AND (spots."size" <= :maxsetsize) ';
        }
    }

    public function set_qcategory($categoryID)
    {
        $this->categoryID = $categoryID;
        if ($categoryID != '') {
            $this->input_arr[':categoryid'] = $categoryID;
            $this->Qcategory = ' AND spots."category" = :categoryid';
        }
    }

    public function set_qflags($flag)
    {
        if ($flag == 'read') {
            $this->Qflag = ' AND usersetinfo."statusread" = 1 ';
            $this->Qkill = ' AND (usersetinfo."statuskill" != 1 OR usersetinfo."statuskill" IS NULL)';
        } elseif ($flag == 'kill') {
            $this->killflag = TRUE;
            $this->Qkill = ' AND usersetinfo."statuskill" = 1 ';
        } elseif ($flag == 'interesting') {
            $this->Qkill = ' AND (usersetinfo."statuskill" != 1 OR usersetinfo."statuskill" IS NULL)';
            $this->Qflag = ' AND usersetinfo."statusint" = 1 ';
            $this->rss_flag = '&amp;flag=interesting&amp;userid=' . urlencode((int)$this->userID);
        } elseif ($flag == 'nzb') {
            $this->Qflag = ' AND usersetinfo."statusnzb" = 1 ';
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
                    $subcat_subqry[$sc] .= ' OR ' . "( \"subcat$sc\" {$this->search_type} :$sci ) ";
                    $this->input_arr[':' . $sci] = "%$sci|%";
                }
            }
            foreach ($subcat_subqry as $key=> $sc) {
                if ($sc != '') {
                    $this->Qsubcat .= " AND ( 0 = 1 $sc )";
                }
            }
        }
        if ($not_subcats != array() ) {
            $this->Qsubcat .= ' AND NOT ( 0 = 1 ';
            foreach ($not_subcats as $s) {
                if ($s[0] == $this->categoryID) {
                    $sc = $s[1];
                    $sci = $s[1] . $s[2];
                    $this->Qsubcat .= ' OR ' . "( \"subcat$sc\" {$this->search_type} :n$sci ) ";
                    $this->input_arr[':n' . $sci] = "%$sci|%";
                }
            }
            $this->Qsubcat .= ')';
        }
    }

    public function set_qspotid($spotid)
    {
        if ($spotid != '') {
            $this->input_arr[':spotid'] = $spotid;
            $this->Qspotid = ' AND spots."spotid"=:spotid ';
        }
    }
    public function set_qreference($reference)
    {
        if ($reference != '') {
            $this->input_arr[':reference'] = $reference;
            $this->Qreference = ' AND spots."reference" = :reference ';
        }
    }

    public function set_qposter($poster)
    {
        if ($poster != '') {
            $this->input_arr[':poster1'] = "%$poster%";
            $this->input_arr[':poster2'] = "%$poster%";
            $this->Qposter = " AND (spots.\"poster\" {$this->search_type} :poster1 ";
            $this->Qposter .= " OR spots.\"spotter_id\" {$this->search_type} :poster2 ) ";
        }
    }
    public function set_qspamlimit()
    {
        global $prefs;
        if (isset($prefs['spot_spam_limit']) && ($prefs['spot_spam_limit'] > 0)) {
            $this->input_arr[':spam_limit'] = $prefs['spot_spam_limit'];
            $this->Qspamlimit = ' AND (spots."reports" < :spam_limit) ';
        }
    }
    public function set_qorder($order)
    {
        if ($order == 'title') {
            $order = '';
        }

        $def_sort = map_default_sort(load_prefs($this->db, $this->userID), array('subject'=> 'title', 'date'=>'spots.stamp', 'better_subject'=>'title'));

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
            $this->input_arr[':minrating1'] = $minrating;
            $this->input_arr[':minrating2'] = $minrating;
            $this->Qrating .= ' AND ((CAST(extsetdata4."value" AS DECIMAL(5, 2)) >= :minrating1) OR (spots."rating" >= :minrating2))';
        }
        if (is_numeric($maxrating) && $maxrating < 10 && $maxrating > 0) {
            $this->input_arr[':maxrating1'] = $maxrating;
            $this->input_arr[':maxrating2'] = $maxrating;
            $this->Qrating .= ' AND ((CAST(extsetdata4."value" AS DECIMAL(5, 2)) <= :maxrating1) OR (spots."rating" <= :maxrating2))';
        }
    }

    public function set_qage($minage, $maxage)
    {
        if (is_numeric($maxage) && $maxage > 0) {
            $this->maxage = $maxage;
            $maxage = $this->now - ($maxage * 3600 * 24);
            $this->input_arr[':maxage'] = $maxage;
            $this->Qage .= ' AND spots."stamp" >= :maxage';
        }
        if (is_numeric($minage) && $minage > 0) {
            $this->minage = $minage;
            $minage = $this->now - ($minage * 3600 * 24);
            $this->input_arr[':minage'] = $minage;
            $this->Qage .= ' AND spots."stamp" <= :minage';
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
                    $this->Qsubcat .= ' OR (' . " \"subcat$sc\" {$this->search_type} '%$sci|%' ) ";
                } else {
                    $hcat = $s[0];
                    $sc = $s[1];
                    $sci = $s[1] . $s[2];
                    $this->Qsubcat .= ' OR ("category" = ' . "'$hcat' AND \"subcat$sc\" {$this->search_type} '%$sci|%' ) ";
                }
            }
            $this->Qsubcat .= ')';
        }
    }
    public function get_rss_url($perpage)
    {
        assert(is_numeric($perpage));
        $rss_limit = $perpage;
        $url = get_config($this->db, 'baseurl');
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
    public function set_search_options($search, $adult, $minage, $maxage, $spotid, $minrating, $maxrating, $poster, $categoryID, $subcats, $not_subcats, $flag, $minsetsize, $maxsetsize, $order, $reference)
    {
        $this->set_qsearch($search);
        $this->set_qadult($adult);
        $this->set_qage($minage, $maxage);
        $this->set_qspamlimit();
        $this->set_qspotid($spotid);
        $this->set_qrating($minrating, $maxrating);
        $this->set_qposter($poster);
        $this->set_qreference($reference);
        $this->set_qcategory($categoryID);
        $this->set_qsubcat($subcats, $not_subcats);
        $this->set_qflags($flag);
        $this->set_qsize($minsetsize, $maxsetsize);
        $this->set_qorder($order);
    }
}

try {
    list($subcats, $not_subcats) = get_subcats_requests();
    $categoryID = get_request('categoryID', '');
    $search     = html_entity_decode(trim(get_request('search', '')));
    $adult      = urd_user_rights::is_adult($db, $userid);
    $poster     = get_request('poster', '');
    $type       = get_request('type', 0);
    $reference  = get_request('reference', '');
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
    $view_size  = get_request('view_size', 1024);

    $spots_viewer = new spot_viewer($db, $userid, $type);
    $spots_viewer->set_search_options($search, $adult, $minage, $maxage, $spotid, $minrating, $maxrating, $poster, $categoryID, $subcats, $not_subcats, $flag, $minsetsize, $maxsetsize, $order, $reference);
    list($pages, $activepage, $totalpages, $offset) = $spots_viewer->get_page_count($perpage, $offset, $only_rows);

    $allsets = $spots_viewer->get_spot_data($perpage, $offset, $last_line, $userid);
    $rssurl = $spots_viewer->get_rss_url($perpage);
    $show_image = get_pref($db, 'show_image', $userid, FALSE);
    init_smarty();
    $smarty->assign(array(
        'rssurl' =>	        $rssurl, 
        'isadmin' =>		$isadmin,
        'sort' =>           $spots_viewer->get_sort(),
        'killflag' =>		$spots_viewer->get_killflag() ? 1 : 0)
    );

    if (!$only_rows) {
        $smarty->assign(array(
            'pages' =>	  	    $pages,
            'lastpage' =>		$totalpages,
            'currentpage' =>	$activepage)
        );
   }

    $smarty->assign(array(
        'only_rows' =>          $only_rows,
        'view_size' =>          $view_size,
        'categoryID' =>	        $categoryID,
        'show_image' =>         $show_image,
        'allsets' =>		    $allsets,
        'show_subcats' =>       get_pref($db, 'show_subcats', $userid, 0),
        'show_comments' =>      get_config($db, 'download_spots_comments', 0),
        'USERSETTYPE_GROUP' =>  USERSETTYPE_GROUP,
        'USERSETTYPE_RSS' =>   	USERSETTYPE_RSS,
        'USERSETTYPE_SPOT' =>   USERSETTYPE_SPOT)
    );
    
    if ($type == 1) {
        $content = $smarty->fetch('ajax_spots_alt.tpl');
    } else {
        $content = $smarty->fetch('ajax_spots.tpl');
    }

    return_result(array(
        'content' => $content,
        'minsetsize' => $minsetsize,
        'maxsetsize' => $maxsetsize,
        'poster' => $poster,
        'minage' => $minage,
        'maxage' => $maxage,
        'flag' => $flag,
        'minrating' => $minrating,
        'maxrating' => $maxrating,
        'only_rows' => $only_rows,
        'REQUEST' => $_REQUEST,
        'last_line' => $last_line
    ));

} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
