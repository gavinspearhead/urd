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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: rss.php 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);


function exception_handler($exception)
{
    die('Error: ' .  $exception->getMessage());
}

set_exception_handler('exception_handler');


$pathhtmli = realpath(dirname(__FILE__));
require_once "$pathhtmli/../functions/defines.php";
require_once "$pathhtmli/../functions/defaults.php";
require_once "$pathhtmli/../config.php";

if (isset($config['urdweb_logfile'])) {
    $config['log_file'] = $config['urdweb_logfile'];
} else {
    $config['log_file'] = '/dev/null';
}
$process_name = 'urd_web'; // needed for message format in syslog and logging

require_once "$pathhtmli/../functions/autoincludes.php";
require_once "$pathhtmli/../functions/functions.php";
require_once "$pathhtmli/../functions/file_functions.php";
require_once "$pathhtmli/../functions/urd_log.php";
require_once "$pathhtmli/../functions/db.class.php";

// initialise some stuff

require_once "$pathhtmli/../functions/config_functions.php";
require_once "$pathhtmli/../functions/user_functions.php";
$db = connect_db(FALSE);  // initialise the database

$prefs = load_config($db); // load the root prefs

// first include all the php files that only define stuff

require_once "$pathhtmli/../functions/web_functions.php";

// then execute code we always need
require_once "$pathhtmli/../functions/fix_magic.php";

load_language(get_config($db, 'default_language', 'dutch'));
$input_arr = array();
$limit = min(50, get_request('limit', 0));
$groupID = get_request('groupID', FALSE, 'is_numeric');
$categoryID = get_request('categoryID', FALSE, 'is_numeric');
$subcatID = get_request('subcatID', FALSE, 'is_numeric');
$feedid = get_request('feed_id', FALSE, 'is_numeric');
$minsize = get_request('minsize', NULL, 'is_numeric');
$maxsize = get_request('maxsize', NULL, 'is_numeric');
$maxage = get_request('maxage', 0, 'is_numeric');
$flag = get_request('flag', '');
$userid = get_request('userid', 0, 'is_numeric');
$type = get_request('type', FALSE);
$search = html_entity_decode(trim(get_request('search', '')));
$now = time();

if ($maxage == '') {
    $maxage = 0;
}

if ($minsize == '') {
    $minsize = NULL;
}

if ($maxsize == '') {
    $maxsize = NULL;
}

if ($type === FALSE || !is_numeric($type)) {
    throw new exception('No type selected');
}

$url = $prefs['baseurl'];

if (!is_numeric($maxage)) {
    throw new exception('Maxage must be numeric');
}
if (!is_numeric($limit)) {
    throw new exception('Limit must be numeric');
}
if ($type == USERSETTYPE_RSS && !is_numeric($feedid)) {
    throw new exception('FeedID must be numeric');
}
if ($type == USERSETTYPE_GROUP && !is_numeric($groupID)) {
    throw new exception('GroupID must be numeric');
}

if ($type == USERSETTYPE_GROUP) {
    $group = '';
    if ($groupID != 0) {
        $group = group_name($db, $groupID);
    }
    $title = $group;
} elseif ($type == USERSETTYPE_RSS) {
    $feed = '';
    if ($feedid != 0) {
        $feed = feed_name($db, $feedid);
    }
    $title = $feed;
} elseif ($type == USERSETTYPE_SPOT) {
    $cat = '';
    if ($categoryID != '' && isset($LN[SpotCategories::HeadCat2Desc($categoryID)])) {
        $cat = $LN[SpotCategories::HeadCat2Desc($categoryID)];
    }
    $title = $cat;
} else {
    throw new exception('Invalid type selected');
}

$search_type = $db->get_pattern_search_command('LIKE'); // get the operator we need for the DB LIKE for mysql or ~~* for postgres
$build_date = date ('r', $now);

$version = urd_version::get_version();

$rss = <<<RSS
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<atom:link href="{$url}html/rss.php" rel="self" type="application/rss+xml" />
<title>URD $title</title>
<link>http://www.urdland.com</link>
<description>URD recent sets</description>
<generator>URD $version @ $url</generator>
<lastBuildDate>$build_date</lastBuildDate>
<language>en-UK</language>
<ttl>60</ttl>
RSS;

$Qsearch = $Qsize = $Qage = $Qgroup = $Qfeed = $Qcategory = $Qsubcat = '';

if (is_numeric($minsize)) {
    $Qsize = ' AND "size" > ( :minsize) ';
    $input_arr [':minsize'] = $minsize;
}
if (is_numeric($maxsize)) {
    $Qsize .= ' AND "size" < (:maxsize) ';
    $input_arr [':maxsize'] = $maxsize;
}

$search = trim(str_replace('*', ' ', $search));

function make_search_query_part($search, $type, $search_type)
{
    $Qsearch = $Qsearch1 = $Qsearch2 = '';
    $keywords = explode(' ', $search);
    foreach ($keywords as $idx => $keyword) {
        if (isset($keyword[0]) && $keyword[0] == '-') {
            $not = 'NOT';
            $keyword = ltrim($keyword, '-');
        } else {
            $not = '';
        }
        if ($keyword != '') {
            if ($type == USERSETTYPE_GROUP) {
                $Qsearch1 .= " $not \"subject\" $search_type :keyword_2_$idx AND "; // nasty: like is case sensitive in psql, insensitive in mysql
                $Qsearch2 .= " $not extsetdata2.\"value\" $search_type :keyword_1_$idx AND ";
                $input_arr[":keyword_1_$idx"] = "%$keyword%";
                $input_arr[":keyword_2_$idx"] = "%$keyword%";

            } elseif ($type == USERSETTYPE_RSS) {
                $Qsearch1 .= " $not rss_sets.\"setname\" $search_type :keyword_2_$idx AND "; // nasty: like is case sensitive in psql, insensitive in mysql
                $Qsearch2 .= " $not extsetdata2.\"value\" $search_type :keyword_1_$idx AND ";
                $input_arr[":keyword_1_$idx"] = "%$keyword%";
                $input_arr[":keyword_2_$idx"] = "%$keyword%";
            } elseif ($type == USERSETTYPE_SPOT) {
                $Qsearch1 .= " $not \"title\" $search_type :keyword_1_$idx AND "; // nasty: like is case sensitive in psql, insensitive in mysql
                $Qsearch2 .= " $not spots.\"tag\" $search_type :keyword_2_$idx  AND ";
                $input_arr[":keyword_1_$idx"] = "%$keyword%";
                $input_arr[":keyword_2_$idx"] = "%$keyword%";
            }
        }
    }
    $Qsearch .= "AND ( ($Qsearch1 1=1) OR ( $Qsearch2 1=1) )";

    return $Qsearch;
}

$Qsearch =  make_search_query_part($search, $type, $search_type);

if ($type == USERSETTYPE_GROUP) {
    verify_access($db, urd_modules::URD_CLASS_GROUPS, FALSE, '', $userid, FALSE);
    if ($categoryID !== FALSE && $categoryID > 0) {
        $groups = get_groups_by_category($db, $userid, $categoryID);
        $Qgroup = '';
        if (count($groups) > 0) {
            $Qgroup .= ' AND "groupID" IN (';
            $Qgroup .= implode(',', $groups);
            $Qgroup .= ')';
        }
    } elseif ($groupID != 0) {
        $Qgroup = ' AND "groupID" = :groupid ';
        $input_arr [':groupid'] = $groupID;
    }
} elseif ($type == USERSETTYPE_RSS) {
    verify_access($db, urd_modules::URD_CLASS_RSS, FALSE, '', $userid, FALSE);
    if ($categoryID !== FALSE && $categoryID > 0) {
        $feeds = get_feeds_by_category($db, $userid, $categoryID);
        $Qfeed = '';
        if (count($feeds) > 0) { 
            $Qfeed .= ' AND rss_sets."rss_id" IN (';
            $Qfeed .= implode(',', $feeds);
            $Qfeed .= ')';
        }
    } elseif ($feedid != 0) {
        $Qfeed = ' AND rss_sets."rss_id" = :feedid ';
        $input_arr [':feedid'] = $feedID;
    }
} elseif ($type == USERSETTYPE_SPOT) {
    verify_access($db, urd_modules::URD_CLASS_SPOTS, FALSE,'',  $userid, FALSE);
    if (!in_array($categoryID, array(0, 1, 2, 3))) {
        $categoryID = '';
    }
    if (!isset($subcatID[1]) || !in_array($subcatID[0], array ('a', 'b', 'c', 'd')) || !is_numeric(substr($subcatID, 1)) || $categoryID == '') {
        $subcatID = '';
    }

    if ($categoryID != '') {
        $Qcategory = " AND spots.\"category\" = :category";
        $input_arr [':category'] = $categoryID;
    }

    if ($subcatID != '') {
        $sc = $subcatID[0];
        $input_arr [':subcat'] = "%$subcatID|%";
        $Qsubcat = " AND ( \"subcat$sc\" $search_type :subcatID) ";
    }
}


if ($maxage > 0) {
    $maxage = $maxage * 3600 * 24;
    $input_arr [':maxage'] = $maxage;
    if ($type == USERSETTYPE_GROUP) {
        $Qage = 'AND (('. $now . ' - "date")) <= :maxage ';
    } elseif ($type == USERSETTYPE_RSS) {
        $Qage = 'AND (('. $now . ' - "timestamp")) <= :maxage ';
    } elseif ($type == USERSETTYPE_SPOT) {
        $Qage = 'AND (('. $now . ' - "stamp")) <= :maxage ';
    }
}

$Qflag = '';
if ($flag == 'interesting' && $userid != 0) {
    $Qflag = ' AND usersetinfo."statusint" = 1 ';
}


$input_arr [':userid'] = $userid;
if ($type == USERSETTYPE_GROUP) {
   $GREATEST = $db->get_greatest_function();
    $sql = "*, (100 * \"binaries\" / $GREATEST(1,articlesmax)) AS complete,
        ($now - date) AS age
        FROM setdata LEFT
        JOIN usersetinfo ON (usersetinfo.\"setID\" = setdata.\"ID\") AND (usersetinfo.\"userID\" = :userid) AND (usersetinfo.\"type\" = :type1)
        LEFT JOIN extsetdata AS extsetdata2 ON (extsetdata2.\"setID\" = setdata.\"ID\" AND extsetdata2.\"name\" = 'setname' AND extsetdata2.\"type\" = :type2 )
        WHERE 1=1 $Qgroup $Qsearch $Qage $Qsize $Qflag
        ORDER BY \"date\" DESC";
    $input_arr[':type1'] = $input_arr[':type2'] = USERSETTYPE_GROUP;

} elseif ($type == USERSETTYPE_RSS) {
    $sql = "\"setname\" AS \"subject\", rss_sets.\"setid\" AS \"ID\", 100 as \"complete\", \"timestamp\" AS \"date\", ($now - \"timestamp\") AS \"age\", \"size\", \"rss_id\"
        FROM rss_sets
        LEFT JOIN usersetinfo ON (usersetinfo.\"setID\" = rss_sets.\"setid\") AND (usersetinfo.\"userID\" = :userid) AND (usersetinfo.\"type\" = :type1)
        LEFT JOIN extsetdata AS extsetdata2 ON (extsetdata2.\"setID\" = rss_sets.\"setid\") AND extsetdata2.\"name\" = 'setname' AND extsetdata2.\"type\" = :type2
        WHERE 1=1 $Qfeed $Qsearch $Qage $Qsize $Qflag
        ORDER BY \"timestamp\" DESC";
    $input_arr[':type1'] = $input_arr[':type2'] = USERSETTYPE_RSS;
} elseif ($type == USERSETTYPE_SPOT) {
    $sql = "\"title\" AS subject, spots.\"size\", spots.\"spotid\" AS \"ID\", ($now - \"stamp\") AS \"age\", \"stamp\" AS \"date\", " .
    '"category", "subcat", "subcata", "subcatb", "subcatc", "subcatd"' .
    ' FROM spots ' .
    " LEFT JOIN usersetinfo ON ((usersetinfo.\"setID\" = spots.\"spotid\") AND (usersetinfo.\"userID\" = :userid)) AND (usersetinfo.\"type\" = :type) " .
    " WHERE (1=1 $Qsearch $Qsize $Qcategory $Qage $Qflag $Qsubcat) " .
    ' ORDER BY "stamp" DESC';
    $input_arr[':type'] = USERSETTYPE_SPOT;
}

function subcat2str($subcata)
{
    $_subcata = '';
    foreach ($subcata as $k=>$c) {
        $_subcata = $k . ': ';
        foreach ($c as $c1) {
            $_subcata .= $c1[0] . ', ';
        }
    }

    return rtrim($_subcata, ' ,'). '<br/>';
}
$res = $db->select_query($sql, $limit, $input_arr);
if (!is_array($res)) {
    die($rss. '</rss>');
} else {
    foreach ($res as $row) {
        $subject = $row['subject'];
        $subject = preg_replace('/(\byEnc\b)|(\breq:)|(\bwww\.[\w\.]*\b)|(\bhttp:\/\/[\w\.]*\b)/i', '', $subject);

        $sub_len = strlen($subject);
        if ($sub_len > 60) {
            $sub_short = substr($subject, 0, 57) . '...';
        } else {
            $sub_short = $subject;
        }
        $sub_short = htmlspecialchars($sub_short);
        $subject = htmlspecialchars($subject);
        $setid = $row['ID'];
        $size = $row['size'];
        $date = date ('r', $row['date']);
        list($_size, $suffix) = format_size($row['size'], 'h' , $LN['byte_short'], 1024, 1);
        $size = $_size . ' ' . $suffix;
        $subcata = $subcatb = $subcatc = $subcatd = '';
        if ($type == USERSETTYPE_GROUP) {
            if ($groupID == 0) {
                $qry = '"name" FROM groups WHERE "ID" = ?';
                $r2 = $db->select_query($qry, 1, array($row['groupID']));
                if ($r2 === FALSE) {
                    $group_name = '';
                } else {
                    $group_name = "Group: {$r2[0]['name']}<br/>";
                }
            } else {
                $group_name = "Group: $group<br/>";
            }
            $url_prefix = 'html/browse.php';
            $complete = round($row['complete']);
            $complete = "$complete % complete<br/>";
            $setid_ref = 'setid';
        } elseif ($type == USERSETTYPE_RSS) {
            if ($feedid == 0) {
                $qry = '"name" FROM rss_urls WHERE "id" = ?';
                $r2 = $db->select_query($qry, 1, array($row['rss_id']));
                if ($r2 === FALSE) {
                    $group_name = '';
                } else {
                    $group_name = "Feed: {$r2[0]['name']}<br/>";
                }
            } else {
                $group_name = "Feed: $feed<br/>";
            }
            $url_prefix = 'html/rsssets.php';
            $complete = round($row['complete']);
            $complete = "$complete % complete<br/>";
            $setid_ref = 'setid';
        } elseif ($type == USERSETTYPE_SPOT) {
            $url_prefix = 'html/spots.php';
            $complete = '';
            $setid_ref = 'spotid';
            $group_name = $LN[SpotCategories::HeadCat2Desc($row['category'])];
            $subcata = subcat2str(get_subcats($row['category'], $row['subcata']));
            $subcatb = subcat2str(get_subcats($row['category'], $row['subcatb']));
            $subcatc = subcat2str(get_subcats($row['category'], $row['subcatc']));
            $subcatd = subcat2str(get_subcats($row['category'], $row['subcatd']));
        }

        $rss .= <<<RSS

<item>
<title>$sub_short</title>
<link>{$url}{$url_prefix}?$setid_ref=$setid</link>
<guid>{$url}{$url_prefix}?$setid_ref=$setid</guid>
<pubDate>$date</pubDate>
<description><![CDATA[$subject <br/>
$group_name<br/>
$size<br/>
$complete
$subcata
$subcatb
$subcatc
$subcatd
]]>
</description>
</item>
RSS;
    }

}
$rss .=<<<RSS
</channel>
</rss>


RSS;

echo $rss;
