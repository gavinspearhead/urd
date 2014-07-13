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

verify_access($db, urd_modules::URD_CLASS_POST, FALSE, 'P', $userid);

function add_nzb_to_spot_post(DatabaseConnection $db, $userid, $filename, $orig_filename, $postid)
{
    global $LN;
    $nzb_contents = file_get_contents($filename);
    $nzb = @simplexml_load_string($nzb_contents);
    if (empty($nzb->file)) {
        throw new exception($LN['error_nopartsinnzb']);
    }
    $size = 0;
    foreach($nzb->file as $file) {
        foreach($file->segments->segment as $seg) {
            $size += (int) $seg['bytes'];
        } 
    } 
    if ($size == 0) {
        throw new exception($LN['error_nopartsinnzb']);
    }

    $username = get_username($db, $userid);
    $dlpath = get_dlpath($db) . TMP_PATH;
    do {
        $dlpath = find_unique_name($dlpath, $username . DIRECTORY_SEPARATOR, 'spotpost_nzb_' . $postid);
    } while (! mkdir($dlpath, 0775, TRUE));
    $nzb_file = find_unique_name($dlpath, $orig_filename, '', '.nzb', TRUE);

    move_uploaded_file($filename, $nzb_file);
    $db->update_query_2('spot_postinfo', array('nzb_file'=> $nzb_file, 'size'=>$size), '"id"=?', array($postid));
}

function add_image_to_spot_post(DatabaseConnection $db, $userid, $filename, $orig_filename, $postid)
{
    global $LN;
    if (filesize($filename) > 1024 * 1024) {
        throw new exception($LN['error_filetoolarge']);
    }
    $image_size = getimagesize($filename);
    if ($image_size === FALSE) {
        throw new exception($LN['error_invalidimage']);
    }
    $image_width = $image_size[0];
    $image_height = $image_size[1];

    if ($image_width == 0 || $image_height == 0) {
        throw new exception($LN['error_invalidimage']);
    }
    $username = get_username($db, $userid);
    $dlpath = get_dlpath($db) . TMP_PATH;
    do { 
        $dlpath = find_unique_name($dlpath, $username . DIRECTORY_SEPARATOR, 'spotpost_image_' . $postid);
    } while (!mkdir($dlpath, 0775, TRUE));
    $image_file = find_unique_name($dlpath, $orig_filename, '', '.jpg', TRUE);
    move_uploaded_file($filename, $image_file);
    $db->update_query_2('spot_postinfo', array('image_file' => $image_file, 'image_width' => $image_width, 'image_height' => $image_height), '"id"=?', array($postid));
}

function handle_uploaded_file(DatabaseConnection $db, $userid)
{
    $type = get_request('type');
    $postid = get_request('post_id');
    // verify challenge
    list($filename, $orig_filename) = get_uploaded_files();

    if ($type == 'image') {
        if ($filename == '' || $orig_filename == '') {
            throw new exception($LN['error_imgfilemissing']);
        }
        add_image_to_spot_post($db, $userid, $filename, $orig_filename, $postid); 
    } elseif ($type == 'nzb') {
        if ($filename == '' || $orig_filename == '') {
            throw new exception($LN['error_nzbfilemissing']);
        }
        add_nzb_to_spot_post($db, $userid, $filename, $orig_filename, $postid); 
    } else {
        throw new exception('Unknown upload type');
    }
}

function insert_spot_post(DatabaseConnection $db, $userid)
{
    global $LN;
    
    $cat = get_request('category');
    $subject = get_request('subject');
    $url = get_request('url');
    $tag = get_request('tag');
    $description = get_request('description');
    $subcats = get_request('subcats');
    $nzb_file = get_request('nzb_file');
    $image_file = get_request('image_file');
    
    if (! (is_numeric($cat) && $cat >= 0 && $cat <= 3)) {
        throw new exception($LN['error_invalidcategory']);
    }

    if ($image_file == '') {
        throw new exception($LN['error_imgfilemissing']);
    }
    if ($nzb_file == '') {
        throw new exception($LN['error_nzbfilemissing']);
    }

    if (count($subcats) < 2 || $subcats == '') {
        throw new exception( $LN['error_nosubcats']);
    }
    if ($url != '' && substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://') {
        throw new exception($LN['buttons_invalidurl']);
    }
    
    if (strlen(trim($subject)) <= 2) { 
        throw new exception( $LN['error_subjectnofound']);
    }
    $description = db_compress($description);
    if (strlen(trim($description)) <= 24) { 
        throw new exception( $LN['error_nocontent']);
    }

    if (strlen(trim($description)) > 65536) { 
        throw new exception( $LN['error_toolong']);
    }

    $subcats = serialize ($subcats);
    $rndstr = generate_password(15);
    $messageid = generate_hash($rndstr);
    $poster_email = get_pref($db, 'poster_email', $userid);
    $poster_name = get_pref($db, 'poster_name', $userid);
    $vals = array(
        'userid' => $userid,
        'poster_name' => $poster_name,
        'category' => $cat,
        'subcat' => '',
        'subcats' => $subcats,
        'nzb_file' => '',
        'image_file' => '',
        'image_width' => '',
        'image_height' => '',
        'subject' => trim($subject),
        'tag' => trim($tag),
        'description' => $description,
        'url' => trim($url),
        'size' => 0,
        'status' => POST_READY,
        'message_id'=> $messageid,
    );
    $post_id = $db->insert_query_2('spot_postinfo', $vals, TRUE);

    $rprefs = load_config($db);
    $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);

    if (!$uc->is_connected()) {
        throw new exception($LN['error_urddconnect']);
    }
    $uc->post_spot($post_id);
    
    return $post_id;
}

function start_post_spot(DatabaseConnection $db, $userid)
{
    $postid = get_request('postid');
    if (!is_numeric($postid)) { 
        throw new exception($LN['error_invalidid']);
    }
    $rprefs = load_config($db);
    $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);

    if (!$uc->is_connected()) {
        throw new exception($LN['error_urddconnect']);
    }
    $uc->continue_cmd(get_command(urdd_protocol::COMMAND_POST_SPOT) . ' ' . $postid);
    die(json_encode(array('error' => 0)));
}

function cancel_post_spot(DatabaseConnection $db, $userid)
{
    $postid = get_request('postid');
    if (!is_numeric($postid)) { 
        throw new exception($LN['error_invalidid']);
    }

    $rprefs = load_config($db);
    $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);

    if (!$uc->is_connected()) {
        throw new exception($LN['error_urddconnect']);
    }
    $uc->cancel(get_command(urdd_protocol::COMMAND_POST_SPOT) . ' ' . $postid);
    die(json_encode(array('error' => 0)));
}

$cmd = get_request('cmd');
try {
    switch (strtolower($cmd)) {
        case 'upload_file':
            handle_uploaded_file($db, $userid);
            die(json_encode(array('error' => 0)));
            break;
        case 'category_info':
            $cat = get_request('category');
            $adult = urd_user_rights::is_adult($db, $userid);
            $subcats = SpotCategories::get_subcats($cat, $adult);
            $smarty->assign('subcats',    	    $subcats);
            $content = $smarty->fetch('ajax_subcats_info.tpl');
            die(json_encode(array('error' => 0, 'content'=>$content)));
            break;
        case 'start_post' :
            start_post_spot($db, $userid);
            break;
        case 'cancel_post' :
            cancel_post_spot($db, $userid);
            break;
        case 'post' :
            // image file and nzbfile
            $post_id = insert_spot_post($db, $userid);
            die(json_encode(array('error' => 0, 'post_id' => $post_id)));
            break;
        case 'show':
            init_smarty('', 0);
            if (!$smarty->getTemplateVars('urdd_online')) {
                throw new exception($LN['urdddisabled']);
            }
            $categories = SpotCategories::get_categories();
            $_categories = get_used_categories_spots($db);
            $categories = array();
            foreach ($_categories as $key => $cat) {
                $cat['name'] = $LN[$cat['name']];
                $categories[$key] = $cat;
            }

            uasort($categories, 'spot_name_cmp');
            $poster_email = $prefs['poster_email'];
            $poster_name = $prefs['poster_name'];
            $content = '';
            $subject = '';

            $smarty->assign('content',    	    $content);
            $smarty->assign('smileys',    	    get_smileys($smarty->getTemplateVars('IMGDIR')));
            $smarty->assign('subject',    	    $subject);
            $smarty->assign('poster_name',    	$poster_name);
            $smarty->assign('poster_email',    	$poster_email);
            $smarty->assign('categories',	    $categories);

            $content = $smarty->fetch('ajax_post_spot.tpl');
            die(json_encode(array('error' => 0, 'content'=>$content)));
            break;
        default:
            throw new exception($LN['error_novalidaction']);
    }
} catch (exception $e) {
    die(json_encode(array('error'=>$e->getMessage())));
}

