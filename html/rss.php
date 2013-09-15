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
 * $LastChangedDate: 2013-09-06 00:48:29 +0200 (vr, 06 sep 2013) $
 * $Rev: 2922 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: rss.php 2922 2013-09-05 22:48:29Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);


function exception_handler(exception $exception)
{
    die_html('Error: ' .  $exception->getMessage());
}

set_exception_handler('exception_handler');


$pathhtmli = realpath(dirname(__FILE__));
require_once "$pathhtmli/../functions/defines.php";
require_once "$pathhtmli/../config.php";
require_once "$pathhtmli/../functions/urdversion.php";
require_once "$pathhtmli/../functions/spots_categories.php";

if (isset($config['urdweb_logfile'])) {
    $config['log_file'] = $config['urdweb_logfile'];
} else {
    $config['log_file'] = '/dev/null';
}
$process_name = 'urd_web'; // needed for message format in syslog and logging

require_once "$pathhtmli/../functions/functions.php";
require_once "$pathhtmli/../functions/urd_log.php";
require_once "$pathhtmli/../functions/db.class.php";

// initialise some stuff

require_once "$pathhtmli/../functions/config_functions.php";
require_once "$pathhtmli/../functions/user_functions.php";
$db = connect_db();  // initialise the database

$prefs = load_config($db); // load the root prefs

// first include all the php files that only define stuff
require_once "$pathhtmli/../functions/autoincludes.php";
require_once "$pathhtmli/../functions/defines.php";

require_once "$pathhtmli/../functions/web_functions.php";

// then execute code we always need
require_once "$pathhtmli/../functions/fix_magic.php" ;
require_once "$pathhtmli/../urdd/urdd_client.php";

$limit = min(50, get_request('limit', 0));
$groupID = get_request('groupID', FALSE);
$categoryID = get_request('categoryID', FALSE);
$subcatID = get_request('subcatID', FALSE);
$feedid = get_request('feed_id', FALSE);
$minsize = get_request('minsize', NULL);
$maxsize = get_request('maxsize', NULL);
$maxage = get_request('maxage', 0);
$flag = get_request('flag', '');
$userid = get_request('userid', 0);
$type = get_request('type', FALSE);
$search = html_entity_decode(trim(get_request('search', '')));

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

$url = $prefs['url'];

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

$db->escape($groupID, FALSE);
$db->escape($subcatID, FALSE);
$db->escape($categoryID, FALSE);
$db->escape($feedid, FALSE);
$db->escape($maxage, FALSE);
$db->escape($limit, FALSE);

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
$build_date = date ('r', time());

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

if ($minsize != NULL) {
    try {
        $minsize = unformat_size($minsize, 1024, 'M');
    } catch (exception $e) {
        throw new exception('Minsize must be numeric');
    }

    $db->escape($minsize, FALSE);
    $Qsize = " AND \"size\" > ( $minsize) ";
}
if ($maxsize != NULL) {
    try {
        $maxsize = unformat_size($maxsize, 1024, 'M');
    } catch (exception $e) {
        throw new exception('Maxsize must be numeric');
    }
    $Qsize .= " AND \"size\" < ($maxsize) ";
}


$search = trim(str_replace('*', ' ', $search));

function make_search_query_part($search, $type, $search_type)
{
    $Qsearch = $Qsearch1 = $Qsearch2 = '';
    $keywords = explode(' ', $search);
    foreach ($keywords as $keyword) {
        if (isset($keyword[0]) && $keyword[0] == '-') {
            $not = 'NOT';
            $keyword = ltrim($keyword, '-');
        } else {
            $not = '';
        }
        if ($keyword != '') {
            if ($type == USERSETTYPE_GROUP) {
                $Qsearch1 .= " $not \"subject\" $search_type '%{$keyword}%' AND "; // nasty: like is case sensitive in psql, insensitive in mysql
                $Qsearch2 .= " $not extsetdata2.\"value\" $search_type '%{$keyword}%' AND ";
            } elseif ($type == USERSETTYPE_RSS) {
                $Qsearch1 .= " $not rss_sets.\"setname\" $search_type '%{$keyword}%' AND "; // nasty: like is case sensitive in psql, insensitive in mysql
                $Qsearch2 .= " $not extsetdata2.\"value\" $search_type '%{$keyword}%' AND ";
            } elseif ($type == USERSETTYPE_SPOT) {
                $Qsearch1 .= " $not \"title\" $search_type '%{$keyword}%' AND "; // nasty: like is case sensitive in psql, insensitive in mysql
                $Qsearch2 .= " $not spots.\"tag\" $search_type '%{$keyword}%' AND ";
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
        $Qgroup .= " AND \"groupID\" IN (";
        $groups = get_groups_by_category($db, $userid, $categoryID);
        $count = 0;
        foreach ($groups as $gr) {
            $Qgroup .= " $gr,";
            $count++;
        }
        $Qgroup = rtrim($Qgroup, ',');
        $Qgroup .= ')';
        if ($count == 0) {
            $Qgroup = '';
        }
    } elseif ($groupID != 0) {
        $Qgroup = " AND \"groupID\" = $groupID ";
    }
} elseif ($type == USERSETTYPE_RSS) {
    verify_access($db, urd_modules::URD_CLASS_RSS, FALSE, '', $userid, FALSE);
    if ($categoryID !== FALSE && $categoryID > 0) {
        $Qfeed .= " AND rss_sets.\"rss_id\" IN (";
        $feeds = get_feeds_by_category($db, $userid, $categoryID);
        $count = 0;
        foreach ($feeds as $gr) {
            $Qfeed .= " $gr,";
            $count++;
        }
        $Qfeed = rtrim($Qfeed, ',');
        $Qfeed .= ')';
        if ($count == 0) {
            $Qfeed = '';
        }
    } elseif ($feedid != 0) {
        $Qfeed = " AND rss_sets.\"rss_id\" = $feedid ";
    }
} elseif ($type == USERSETTYPE_SPOT) {
    verify_access($db, urd_modules::URD_CLASS_SPOTS, FALSE,'',  $userid, FALSE);
    if (!in_array($categoryID, array(0, 1, 2, 3))) {
        $$categoryID = '';
    }
    if (!isset($subcatID[1]) || !in_array($subcatID[0], array ('a', 'b', 'c', 'd')) || !is_numeric(substr($subcatID, 1)) || $categoryID == '') {
        $subcatID = '';
    }

    if ($categoryID != '') {
        $db->escape($categoryID, TRUE);
        $Qcategory = " AND spots.\"category\" = $categoryID";
    }

    if ($subcatID != '') {
        $sc = $subcatID[0];
        $Qsubcat = " AND ( \"subcat$sc\" $search_type '%$subcatID|%') ";
    }
}


if ($maxage > 0) {
    $Qage = 'AND ('. time() . " - date) / 3600 / 24 <= $maxage ";
}

$Qflag = '';
if ($flag == 'interesting' && $userid != 0) {
    $Qflag = ' AND usersetinfo."statusint"=1 ';
}

$now = time();

if ($type == USERSETTYPE_GROUP) {
   $GREATEST = $db->get_greatest_function();
    $sql = "*, (100 * \"binaries\" / $GREATEST(1,articlesmax)) AS complete,
        ($now - date) AS age
        FROM setdata LEFT
        JOIN usersetinfo ON (usersetinfo.\"setID\" = setdata.\"ID\") AND (usersetinfo.\"userID\" = $userid) AND (usersetinfo.\"type\" = '" . USERSETTYPE_GROUP . "')
        LEFT JOIN extsetdata AS extsetdata2 ON (extsetdata2.\"setID\" = setdata.\"ID\" AND extsetdata2.\"name\" = 'setname' AND extsetdata2.\"type\" = '" . USERSETTYPE_GROUP . "' )
        WHERE 1=1 $Qgroup $Qsearch $Qage $Qsize $Qflag
        ORDER BY \"date\" DESC";

} elseif ($type == USERSETTYPE_RSS) {
    $sql = "setname AS subject, rss_sets.\"setid\" as \"ID\", 100 as \"complete\", \"timestamp\" as \"date\", ($now - timestamp) AS age, \"size\", \"rss_id\"
        FROM rss_sets
        LEFT JOIN usersetinfo ON (usersetinfo.\"setID\" = rss_sets.\"setid\") AND (usersetinfo.\"userID\" = $userid) AND (usersetinfo.\"type\" = '" . USERSETTYPE_RSS . "')
        LEFT JOIN extsetdata AS extsetdata2 ON (extsetdata2.\"setID\" = rss_sets.\"setid\") AND extsetdata2.\"name\" = 'setname' AND extsetdata2.\"type\" = '" . USERSETTYPE_RSS . "'
        WHERE 1=1 $Qfeed $Qsearch $Qage $Qsize $Qflag
        ORDER BY \"timestamp\" DESC";
} elseif ($type == USERSETTYPE_SPOT) {
    $sql = "\"title\" AS subject, spots.\"size\", spots.\"spotid\" AS \"ID\", ($now - \"stamp\") AS \"age\", \"stamp\" AS \"date\", " .
    "\"category\", \"subcat\", \"subcata\" ,\"subcatb\", \"subcatc\", \"subcatd\"" .
    " FROM spots " .
    " LEFT JOIN usersetinfo ON ((usersetinfo.\"setID\" = spots.\"spotid\") AND (usersetinfo.\"userID\" = $userid)) AND (usersetinfo.\"type\" = '" . USERSETTYPE_SPOT . "') " .
    " WHERE (1=1 $Qsearch $Qsize $Qcategory $Qflag $Qsubcat) ";
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

$res = $db->select_query($sql, $limit);
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
                $groupID2 = $row['groupID'];
                $qry = "\"name\" FROM groups WHERE \"ID\" = '$groupID2' ";
                $r2 = $db->select_query($qry,1);
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
                $feedid2 = $row['rss_id'];
                $qry = "\"name\" FROM rss_urls WHERE \"id\" = '$feedid2' ";
                $r2 = $db->select_query($qry,1);
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
