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
 * $LastChangedDate: 2014-06-07 14:53:28 +0200 (za, 07 jun 2014) $
 * $Rev: 3081 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showquickdisplay.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';
$pathqd = realpath(dirname(__FILE__));

require_once "$pathqd/../functions/ajax_includes.php";

function link_to_url($description)
{
    $position = 0;
    while (preg_match('|https?:\/\/[-a-z0-9_:./&%!@#$?^()+=\\;]+|i', $description, $matches, PREG_OFFSET_CAPTURE, $position)) {
        list($url, $urlposition) = $matches[0];
        $d1 = substr($description, 0, $urlposition);
        $l = strlen($url);
        $d2 = substr($description, $urlposition + $l);
        $new_url = $url;
        if ((strpos(substr($d1, -10), '[url]') === FALSE) && (strpos(substr($d2, 0, 10), '[/url]') === FALSE)) {
            $new_url = '[url]' . $url . '[/url]';
        }
        $new_l = strlen($new_url);
        $description = $d1 . $new_url . $d2;
        $position = $urlposition + $new_l;
    }

    return $description;
}

function show_spotinfo(DatabaseConnection $db, $setID, $userid, $display, $binarytype, $binarytypes)
{
    assert(is_numeric($userid));
    global $smarty, $LN;
    $sql = '"stamp", "category", "url", "reports", "title", "subcat", "subcata", "subcatb", "subcatc", "subcatd", "size", "spotid", "poster", "tag", "description", ' .
        'spots."spotter_id" AS "spotterid", spot_whitelist."spotter_id" AS "whitelisted" ' .
        'FROM spots LEFT JOIN spot_whitelist ON (spots."spotter_id" = spot_whitelist."spotter_id") ' .
        'WHERE "spotid"=?';
    $res = $db->select_query($sql, 1, array($setID));
    if (!isset($res[0])) {
        throw new exception($LN['error_spotnotfound'] . ': '.$setID);
    }
    $row = $res[0];
    $show_image = get_pref($db, 'show_image', $userid, FALSE);
    $description = db_decompress($row['description']);
    $description = strip_tags($description);
    $description = htmlentities($description, ENT_IGNORE, 'UTF-8', FALSE);
    $description = str_replace(array("\r", "\n"), array('', '<br/>'), $description);
    $description = link_to_url($description);

    $ubb = new UbbParse($description);
	TagHandler::setDeniedTags( array() );
	TagHandler::setadditionalinfo('img', 'allowedimgs', get_smileys($smarty->getTemplateVars('IMGDIR'), TRUE));
    $description = insert_wbr($ubb->parse());
    list($_size, $suffix) = format_size($row['size'], 'h', $LN['byte_short'], 1024, 1);
    $filesize = $_size . ' ' . $suffix;
    $category = $LN[SpotCategories::HeadCat2Desc($row['category'])];
    $subcata = get_subcats($row['category'], $row['subcata']);
    $subcatb = get_subcats($row['category'], $row['subcatb']);
    $subcatc = get_subcats($row['category'], $row['subcatc']);
    $subcatd = get_subcats($row['category'], $row['subcatd']);
    $whitelisted = $row['whitelisted'] == NULL ? 0 : 1;
    $sql = 'image_file, image FROM spot_images WHERE "spotid"=? AND "fetched"=?';
    $img_res = $db->select_query($sql, array($setID, 1));
    $sql = '* FROM spot_comments WHERE "spotid"=? ORDER BY "stamp" ASC';
    $spotres = $db->select_query($sql, array($setID));
    $comments = array();
    if (is_array($spotres)) {
        $comments = $spotres;
        $blacklist_url = get_config($db, 'spots_blacklist', '');
        $blacklist = array();
        if ($blacklist_url != '') {
            $blacklist = load_blacklist($db);
        }
    }
    foreach ($comments as $key => &$comment) {
        if (isset($blacklist[$comment['userid']])) { 
            unset($comments[$key]); 
            continue;
        }
        $c = db_decompress($comment['comment']);
        $c = htmlentities(strip_tags($c));
        $c = link_to_url($c);
        $ubb = new UbbParse($c);
        $c = $ubb->parse();
        $c = str_replace("\n", '<br/>', $c);
        $comment['comment'] = insert_wbr($c);
        $comment['stamp'] = date($LN['dateformat2'] . ' ' . $LN['timeformat2'], $comment['stamp']);
        if ($comment['user_avatar'] != '') {
            $comment['user_avatar'] = 'data:image/png;base64,' .$comment['user_avatar'];
        }
    }
    $url = trim(strip_tags($row['url']));
    /// too quick and dirty --- clean up XXX
    $image_file = $image = '';
    $image_from_db = 0;
    if (isset($img_res[0])) {
        if (substr($img_res[0]['image'], 0, 10) == 'data:image') {
            $image_from_db = 1;
        } elseif (substr($img_res[0]['image'], 0, 9) == 'articles:') {
            $image_file = get_dlpath($db). IMAGE_CACHE_PATH . $setID . '.jpg';
        } else {
            $image = trim(strip_tags($img_res[0]['image']));
        }
    }

    $now = time();
    $spam_reports = is_numeric($row['reports']) ? $row['reports'] : 0;
    $age = ($now > $row['stamp']) ? $now - $row['stamp'] : 0;
    $age = readable_time($age, 'largest_two_long');
    $smarty->assign('spotid',       $row['spotid']);
    $smarty->assign('show_image',   $show_image);
    $smarty->assign('image_file',   $image_file);
    $smarty->assign('category',     $category);
    $smarty->assign('category_id',  $row['category']);
    $smarty->assign('subcat',       $row['subcat']);
    $smarty->assign('url',          $url);
    $smarty->assign('poster',       $row['poster']);
    $smarty->assign('image',        $image);
    $smarty->assign('image_from_db',$image_from_db);
    $smarty->assign('timestamp',    date($LN['dateformat'] . ' ' . $LN['timeformat'], $row['stamp']));
    $smarty->assign('subcata',      $subcata);
    $smarty->assign('subcatb',      $subcatb);
    $smarty->assign('subcatc',      $subcatc);
    $smarty->assign('subcatd',      $subcatd);
    $smarty->assign('whitelisted',  $whitelisted);
    $smarty->assign('spotter_id',   $row['spotterid']);
    $smarty->assign('spam_reports', $spam_reports);
    $smarty->assign('title',        insert_wbr(html_entity_decode($row['title'], ENT_QUOTES, 'UTF-8')));
    $smarty->assign('tag',          $row['tag']);
    $smarty->assign('age',          $age);
    $smarty->assign('filesize',     $filesize);
    $smarty->assign('comments',     $comments);
    $smarty->assign('description',  (html_entity_decode($description, ENT_QUOTES, 'UTF-8')));
    $smarty->assign('binarytype',   $binarytype); // Binarytype
    $smarty->assign('binarytypes',  $binarytypes);  // All
    $smarty->assign('display',      $display);      // All values
    return $smarty->fetch('ajax_showspot.tpl');
}

// Functions follow:
function display_extsetinfo(DatabaseConnection $db, $setID, $type, $userid)
{
    global $smarty, $LN;
    assert(is_numeric($userid));
    assert (in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)));
    // First the extended info:
    $sql = '* FROM extsetdata WHERE "setID"=? AND "type"=?';
    $res = $db->select_query($sql, array($setID, $type));
    // Store it in an easy to use array:
    $extsetinfo = array();
    $extsetinfo['binarytype'] = 0;

    if (is_array($res)) {
        foreach ($res as $row) {
            $extsetinfo[$row['name']] = $row['value'];
        }
    }
    $binarytypes = urd_extsetinfo::get_binary_types();

    // files by default:
    if ($type == USERSETTYPE_GROUP) {
        $sql = '* FROM setdata WHERE "ID"=?';
        $res = $db->select_query($sql, 1, array($setID));
        if (!is_array($res)) {
            throw new exception($LN['error_setnotfound'] . ': '.$setID);
        }
        $res = $res[0]; // Only 1 row should be returned.

        $groupID = $res['groupID'];
        $size = $res['size'];

        list($_size, $suffix) = format_size($size, 'h', $LN['byte_short'], 1024, 1);
        $nicesize = $_size . ' ' . $suffix;
        $articlesmax = $res['articlesmax'];
        $binaries = $res['binaries'];
        $setname = $res['subject'];
    } elseif ($type == USERSETTYPE_RSS) {
        $sql = '"size", "rss_id", "setname" FROM rss_sets WHERE "setid"=?';
        $res = $db->select_query($sql, 1, array($setID));
        if (!is_array($res)) {
            throw new exception($LN['error_setnotfound'] . ': '.$setID);
        }
        $size = $res[0]['size'];
        list($_size, $suffix) = format_size($size, 'h', $LN['byte_short'], 1024, 1);
        $nicesize = $_size . $suffix;
        $groupID = $res[0]['rss_id'];
        $setname = $res[0]['setname'];
        $binaries = 0;
        $articlesmax = 0;
    } else {
        $setname = '';
    }

    if (!isset($extsetinfo['name'])) {
        // filter common spam in subjects
        $setname = $extsetinfo['name'] = preg_replace('/(\byEnc\b)|(\breq:)|(\bwww\.[\w\.]*\b)|(\bhttp:\/\/[\w\.]*\b)|([=><#])/i', '', $setname);
    } else {
        $setname = $extsetinfo['name'];
    }

    $display = urd_extsetinfo::generate_set_info($extsetinfo);

    if ($type == USERSETTYPE_SPOT) {
        return show_spotinfo($db, $setID, $userid, $display, $extsetinfo['binarytype'], $binarytypes);
    }

    $smarty->assign('srctype',        'display');         // Edit or just Display?
    $smarty->assign('type',           $type);         // the type RSS or Groups
    $smarty->assign('setID',          $setID);        // FYI
    $smarty->assign('binarytype',     $extsetinfo['binarytype']); // Binarytype
    $smarty->assign('binarytypes',    $binarytypes);  // All
    $smarty->assign('display',        $display);      // All values
    $poster = '';

    if ($type == USERSETTYPE_GROUP) {
        $groupname = group_name($db, $groupID);
        $sql = "* FROM binaries_{$groupID} WHERE \"setID\"=? ORDER BY \"subject\" ASC";
        $res = $db->select_query($sql, array($setID));
        if (!isset($res[0])) {
            throw new exception($LN['error_binariesnotfound']);
        }
        $bin_id = $res[0]['binaryID'];
        $sql = "MAX(\"fromname\") AS \"poster\" FROM parts_{$groupID} WHERE \"binaryID\"=?";
        $res1 = $db->select_query($sql, 1, array($bin_id));
        if (isset($res1[0]['poster'])) {
            $poster = $res1[0]['poster'];
        }
    } elseif ($type == USERSETTYPE_RSS) {
        $groupname = feed_name($db, $groupID);
    }

    $par2s = 0;

    $files = array();

    if ($type == USERSETTYPE_GROUP) {
        $totalsize = 0;
        foreach ($res as $arr) {
            $file = array();

            //$posters[ $arr['fromname'] ] = 1;
            $size = $arr['bytes'];
            $totalsize += $size;
            list($_size, $suffix) = format_size($size, 'h', $LN['byte_short'], 1024, 1);
            $size = $_size . ' ' . $suffix;
            //$size = readable_size($size, 1, $LN['byte_short']);
            $filename = $arr['subject'];
            $filename = str_ireplace('yEnc','',$filename);

            $file['cleanfilename'] = $filename;
            $file['binaryID'] = $arr['binaryID'];
            $file['size'] = $size;
            if (strstr($filename, '.nfo') !== FALSE) { // ensure .nfo files are always on top of the list
                array_unshift($files, $file);
            } else {
                $files[] = $file;
            }

            $arr['subject'] = strtolower($arr['subject']);
            if (strpos($arr['subject'], '.par2') !== FALSE) {
                $par2s++;
            }
        }
    } else {
        // now what
        $totalsize = $size;
    }

    list($_size, $suffix) = format_size($totalsize, 'h', $LN['byte_short'], 1024, 1);
    $totalsize = $_size . ' ' . $suffix;

    $smarty->assign('articlesmax',          $articlesmax);
    $smarty->assign('binaries',             $binaries);
    $smarty->assign('groupID',              $groupID);
    $smarty->assign('groupname',            $groupname);
    $smarty->assign('files',                $files);
    $smarty->assign('setname', 	        	$setname);
    $smarty->assign('fromnames',            $poster);
    $smarty->assign('totalsize',            $totalsize);
    $smarty->assign('par2s',                $par2s);
    $smarty->assign('type',                 $type);
    return $smarty->fetch('ajax_showextsetinfo.tpl');
}

function edit_extsetinfo(DatabaseConnection $db, $setid, $type)
{
    global $smarty, $LN;
    assert (in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)));
    // Get the default name: set subject
    if ($type == USERSETTYPE_GROUP) {
        $sql = '"subject" AS "setname" FROM setdata WHERE "ID"=?';
    } elseif ($type == USERSETTYPE_RSS) {
        $sql = '"setname" FROM rss_sets WHERE "setid"=?';
    } elseif ($type == USERSETTYPE_SPOT) {
        $sql = '"title" AS "setname" FROM spots WHERE "spotid"=?';
    }
    $res = $db->select_query($sql, array($setid));
    if (!is_array($res)) {
        throw new exception($LN['error_setnotfound'] . ': '.$setid);
    }
    $setname = $res[0]['setname'];

    $sql = '* FROM extsetdata WHERE "setID"=? AND "type"=?';
    $res = $db->select_query($sql, array($setid, $type));
    // Store it in an easy to use array:
    $extsetinfo = array();
    // Set default values:
    $extsetinfo['binarytype'] = 0;
    $extsetinfo['name'] = $setname;

    // Overwrite default values with stuff from db:
    if (is_array($res)) {
        foreach ($res as $row) {
            $extsetinfo[$row['name']] = $row['value'];
        }
    }

    $display = urd_extsetinfo::generate_set_info($extsetinfo);
    $binarytypes = urd_extsetinfo::get_binary_types();

    $smarty->assign('setname',          $setname);        // FYI
    $smarty->assign('setID',            $setid);        // FYI
    $smarty->assign('srctype',          'edit');         // Edit or just Display?
    $smarty->assign('type',             $type);         //  RSS or group
    $smarty->assign('name',             $extsetinfo['name']); // Name
    $smarty->assign('binarytype',       $extsetinfo['binarytype']); // Binarytype
    $smarty->assign('binarytypes',      $binarytypes);  // All
    $smarty->assign('display',          $display);      // All values
    return $smarty->fetch('ajax_showextsetinfo.tpl');
}
init_smarty('', 0);

$subject = '';
// Process commands:
try {
    if (isset($_REQUEST['type']) && isset($_REQUEST['subject'])) {
        $type = get_request('type');
        $srctype = get_request('srctype');
        $subject = get_request('subject');
        $contents = '';
        assert(in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)));
        $items = array();
        switch ($srctype) {
        case 'setshowesi': // Display extsetinfo
            $contents = display_extsetinfo($db, $subject, $type, $userid);
            echo_debug_var_file('/tmp/foo', $contents);
            break;
        case 'seteditesi': // Display the edit screen for extsetinfo
            $contents = edit_extsetinfo($db, $subject, $type);
            break;
        case 'setsavebintype': // Update the binary type
            urd_extsetinfo::save_extsetinfo($db, $subject, $userid, $_REQUEST['values'], $type);
            // No output.
            break;
        case 'setsaveesi': // Save the extsetinfo stuff
            $has_link = urd_extsetinfo::check_extset_link_exists($db, $subject, $type);
            $newname = urd_extsetinfo::save_extsetinfo($db, $subject, $userid, $_REQUEST['values'], $type);
            if (isset($_REQUEST['values']['link']) && !$has_link && ($_REQUEST['values']['link'] != '')) {
                $file_nfo['link'] = $_REQUEST['values']['link'];
                $file_nfo['binarytype'] = $_REQUEST['binarytype'];
                nfo_parser::get_link_info($file_nfo);
                $newname = urd_extsetinfo::save_extsetinfo($db, $subject, $userid, $file_nfo, $type);
            }
            // Returning the new setname so the browse page can be updated on the fly
            // Actually not really returning but using smarty for name-post-processing (such as icons):
            //echo htmlentities ($newname);
            $smarty->assign('maxstrlen', get_pref($db, 'maxsetname', $userid));
            $smarty->assign('newname', $newname);
            $contents = $smarty->fetch('formatsetname.tpl');
            break;
        case 'setguessesisafe': // Guess extsetinfo stuff (safe: User needs to approve before we send it to urdland)
            urd_extsetinfo::guess_extsetinfo_safe($db,$subject, $type, $userid);
            break;
        case 'setbasketguessesi': // Guess extsetinfo stuff for everything in the basket (not safe, assume that our analysis is flawless)
            urd_extsetinfo::basketguess_extsetinfo($db,$subject, $type, $userid);
            break;
        case 'setguessesi': // Guess extsetinfo stuff (not safe, assume that our analysis is flawless)
            $newname = urd_extsetinfo::guess_extsetinfo($db,$subject, $type, $userid);
            $smarty->assign('maxstrlen', get_pref($db, 'maxsetname', $userid));
            $smarty->assign('newname', $newname);
            $contents = $smarty->fetch('formatsetname.tpl');
            break;
        default:
            throw new exception($LN['error_novalidaction']);
            break;
        }
    } else {
        throw new exception($LN['error_novalidaction']);
    }
    return_result(array('contents' => $contents));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
