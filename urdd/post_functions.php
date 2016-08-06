<?php
/**
/*  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2014-06-07 14:53:28 +0200 (za, 07 jun 2014) $
 * $Rev: 3081 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: post_functions.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function update_postinfo(DatabaseConnection $db, $postid, $status=NULL, $tmp_dir=NULL, $file_count= NULL)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($postid));
    $cols = array();
    if ($tmp_dir !== NULL) {
        $cols['tmp_dir'] = $tmp_dir;
    }
    if ($status !== NULL) {
        $cols['status'] = $status;
    }
    if ($file_count !== NULL) {
        $cols['file_count'] = $file_count;
    }
    $db->update_query_2('postinfo', $cols, '"id"=?', array($postid));
}

function create_rar_files(DatabaseConnection $db, action $item, $postid, $filesize_rar, &$dl_path1, $userid, $dir, $name)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($postid) && is_numeric($userid));
    $username = get_username($db, $userid);
    $dl_path_basis = get_dlpath($db);
    $id = 'post_' . mt_rand(1000, 9999);
    $dl_path1 = find_unique_name($dl_path_basis, TMP_PATH . $username . DIRECTORY_SEPARATOR, $id);

    $niceval = get_nice_value($db);
    echo_debug("Dir $dl_path1", DEBUG_SERVER);
    $rv = @create_dir($dl_path1, 0775);
    if ($rv === FALSE) {
        $status = QUEUE_FAILED;
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Rar failed');
        update_postinfo_status ($db, POST_RAR_FAILED, $postid, NULL);
        write_log("Failed to create directory $dl_path1", LOG_ERR);
        throw new exception ("Failed to create directory $dl_path1", POST_FAILURE);
    }

    $dir = my_escapeshellarg($dir);
    if ($filesize_rar > 0) {
        $rar_cmd = get_config($db, 'rar_path');
        $rar_cmd = my_escapeshellarg($rar_cmd);
        $rar_file = $dl_path1 . $name . '.rar';
        $rar_file = my_escapeshellarg($rar_file);
        $rar_opt = 'a -ed -inul -idp -m5 -r -ep1 -y'; // get from db
        $rar_size_option = "-v{$filesize_rar}k";

        $cmd_line = "nice -$niceval $rar_cmd $rar_opt $rar_size_option $rar_file $dir/* > /dev/null 2>&1";
        exec ($cmd_line, $output, $rv);
    }
    if ($rv != 0) {
        $status = QUEUE_FAILED;
        update_postinfo_status ($db, POST_RAR_FAILED, $postid, NULL);
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Rar failed');
        write_log('Creating rar files failed', LOG_ERR);
        throw new exception('Rar failed', POST_FAILURE);
    }
    copy_files($dir, $dl_path1, array('*.nzb', '*.nfo'), $dl_path_basis);
    $post_status = POST_RARRED;
    update_postinfo($db, $postid, POST_RARRED, $dl_path1);

    return $post_status;
}

function create_par_files(DatabaseConnection $db, action $item, $postid, $recovery_par, $dl_path1, $userid, $dir, $name)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($postid) && is_numeric($userid));
    $file_list1 = glob($dl_path1 . '*');
    $file_list_par = glob($dl_path1 . '*.par2');
    foreach ($file_list_par as $f) {// clean up possible old shit first
        unlink($f);
    }
    if ($file_list1 === FALSE || empty($file_list1)) {
        $status = QUEUE_FAILED;
        update_postinfo_status ($db, POST_PAR_FAILED, $postid, NULL);
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'No files found');
        throw new exception ('No files found', POST_FAILURE);
    }
    $niceval = get_nice_value($db);
    if ($recovery_par > 0) {
        $par2_cmd = get_config($db, 'unpar_path');
        $par2_cmd = my_escapeshellarg($par2_cmd);
        $par2_file = $dl_path1 . DIRECTORY_SEPARATOR . $name . '.par2';
        $par2_file = my_escapeshellarg($par2_file);
        $cmd_line = "nice -$niceval $par2_cmd c -q -r$recovery_par $par2_file $dl_path1/* > /dev/null 2>&1";
        exec ($cmd_line, $output, $rv);

        if ($rv != 0) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Par2 failed');
            update_postinfo_status ($db, POST_PAR_FAILED, $postid, NULL);
            throw new exception ('Par2 failed', POST_FAILURE);
        }
    }
    $file_list1 = glob($dl_path1 . '*');

    update_postinfo($db, $postid, POST_PARRED, $dl_path1);

    return $file_list1;
}

function create_yenc_files(DatabaseConnection $db, action $item, $postid, array $file_list1, $userid, $dir, $name)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($postid) && is_numeric($userid));
    $id = 'post_' . mt_rand(1000, 9999);
    $username = get_username($db, $userid);
    $dl_path_basis = get_dlpath($db);
    $dl_path2 = find_unique_name($dl_path_basis, TMP_PATH . $username . DIRECTORY_SEPARATOR, $id);
    echo_debug("Dir $dl_path2", DEBUG_SERVER);
    $rv = @create_dir($dl_path2, 0775);
    if ($rv === FALSE) {
        $status = QUEUE_FAILED;
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Yenc encoding failed');
        update_postinfo_status ($db, POST_YYENCODE_FAILED, $postid, NULL);
        write_log("Failed to create directory $dl_path2", LOG_ERR);
        throw new exception ("Failed to create directory $dl_path2", POST_FAILURE);
    }

    $yyencode_cmd = get_config ($db, 'yyencode_path');
    $yyencode_opt = get_config ($db, 'yyencode_pars');

    $db->delete_query('post_files', '"postid" = ?', array($postid));
    $rar_cnt = 0;
    foreach ($file_list1 as $rarfile) {
        $rar_cnt++;
        $dl_path3 = $dl_path2 . basename($rarfile) . DIRECTORY_SEPARATOR;
        $rv = @create_dir($dl_path3, 0775);
        if ($rv === FALSE) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Yyencode failed');
            update_postinfo_status ($db, POST_YYENCODE_FAILED, $postid, NULL);
            write_log("Failed to create directory $dl_path3", LOG_ERR);
            throw new exception ("Failed to create directory $dl_path3", POST_FAILURE);
        }
        $yy_path = my_escapeshellarg($dl_path3);
        $yy_rarfile = my_escapeshellarg($rarfile);
        $cmd_line = "$yyencode_cmd $yyencode_opt $yy_path $yy_rarfile > /dev/null 2>&1";
        exec ($cmd_line, $output, $rv);
        if ($rv != 0) {
            $status = QUEUE_FAILED;
            update_postinfo_status ($db, POST_YYENCODE_FAILED, $postid, NULL);
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'YYencode failed');
            throw new exception ('YYencode failed', POST_FAILURE);
        }
        $file_list2 = glob($dl_path3 . '*');
        if ($file_list2 === FALSE || empty($file_list2)) {
            write_log('No Files found', LOG_WARNING);
        }
        $cols = array ('postid', 'filename', 'file_idx', 'rarfile', 'rar_idx', 'status', 'size');
        $yy_cnt = 0;
        foreach ($file_list2 as $filename) {
            $yy_cnt++;
            $vals = array($postid, $filename, $yy_cnt, basename($rarfile), $rar_cnt, POST_READY, filesize($filename));
            $db->insert_query('post_files', $cols, $vals);
        }
    }
    update_postinfo($db, $postid, POST_YYENCODED, $dl_path2, $rar_cnt);
}

function convert_spot_to_xml($spot, $imageInfo, $nzbSegments) 
{
    $doc = new DOMDocument('1.0', 'utf-8');
    $doc->formatOutput = FALSE;

    $mainElm = $doc->createElement('Spotnet');
    $postingElm = $doc->createElement('Posting');
    $postingElm->appendChild($doc->createElement('Key', $spot['key']));
    if (array_key_exists('created', $spot) && strlen($spot['created']) > 0) {
        $postingElm->appendChild($doc->createElement('Created', $spot['created']));
    } else {
        $postingElm->appendChild($doc->createElement('Created', time()));
    } 
    $postingElm->appendChild($doc->createElement('Poster', $spot['poster']));
    $postingElm->appendChild($doc->createElement('Size', $spot['filesize']));

    if (strlen($spot['tag']) > 0) {
        $postingElm->appendChild($doc->createElement('Tag', $spot['tag']));
    } 

    /* Title element is enclosed in CDATA */
    $titleElm = $doc->createElement('Title');
    $titleElm->appendChild($doc->createCDATASection(htmlentities($spot['title'], ENT_NOQUOTES, 'UTF-8')));
    $postingElm->appendChild($titleElm);

    /* Description element is enclosed in CDATA */
    $descrElm = $doc->createElement('Description');
    $descrElm->appendChild($doc->createCDATASection(htmlentities(str_replace( array("\r\n", "\r", "\n"), "[br]", $spot['description']), ENT_NOQUOTES, 'UTF-8')));
    $postingElm->appendChild($descrElm);

    /* Website element ins enclosed in cdata section */
    $websiteElm = $doc->createElement('Website');
    $websiteElm->appendChild($doc->createCDATASection($spot['website']));
    $postingElm->appendChild($websiteElm);

    /*
     * Category contains both an textelement as nested elements, so
     * we do it somewhat different
     *   <Category>01<Sub>01a09</Sub><Sub>01b04</Sub><Sub>01c00</Sub><Sub>01d11</Sub></Category>
     */
    $categoryElm = $doc->createElement('Category');
    $categoryElm->appendChild($doc->createTextNode(str_pad($spot['category'], 2, '0', STR_PAD_LEFT)));

    foreach($spot['subcatlist'] as $subcat) {
        if (!empty($subcat)) {
            $categoryElm->appendChild($doc->createElement('Sub', str_pad($spot['category'], 2, '0', STR_PAD_LEFT) . $subcat[0] . str_pad(substr($subcat, 1), 2, '0', STR_PAD_LEFT)));
        } 
    } 
    $postingElm->appendChild($categoryElm);

    /*
     * We only support embedding the image on usenet, so 
     * we always use that
     *
     * 		<Image Width='1500' Height='1500'><Segment>4lnDJqptSMMifJpTgAc52@spot.net</Segment><Segment>mZgAC888A6EkfJpTgAJEX@spot.net</Segment></Image>
     */
    $imgElm = $doc->createElement('Image');
    $imgElm->setAttribute('Width', $imageInfo['width']);
    $imgElm->setAttribute('Height', $imageInfo['height']);
    foreach($imageInfo['segments'] as $segment) {
        $imgElm->appendChild($doc->createElement('Segment', $segment));
    } 
    $postingElm->appendChild($imgElm);

    /* Add the segments to the nzb file */
    $nzbElm = $doc->createElement('NZB');
    foreach($nzbSegments as $segment) {
        $nzbElm->appendChild($doc->createElement('Segment', $segment));
    } 
    $postingElm->appendChild($nzbElm);

    $mainElm->appendChild($postingElm);
    $doc->appendChild($mainElm);

    return $doc->saveXML($mainElm);
} 

function create_spot_data($db, array $spot_db_data, $userid, $nzb_segments, $image_segments)
{
    $spot_signing = Services_Signing_Base::factory();
    $server_privatekey = get_config($db, 'privatekey');
    $user_privatekey = get_user_private_key($db, $userid);
    $user_publickey = get_user_public_key($db, $userid);
    if (substr(sha1($spot_db_data['message_id']), 0, 4) != '0000') {
        /// some error
    }
    $key = 7;
    $cat = $spot_db_data['category'] + 1;
    $subcats = unserialize($spot_db_data['subcats']);
    $subcats_str = '';
    foreach($subcats as $sc) {
        $subcats_str .= $sc[0] . str_pad(substr($sc, 1), 2, '0', STR_PAD_LEFT);
    }
    $messageid = $spot_db_data['message_id'];
    $description = $spot_db_data['description'];
    $url = $spot_db_data['url'];
    $size = $spot_db_data['size'];
    $tag = $spot_db_data['tag'];
    $subject = $spot_db_data['subject'];
    $poster_name = $spot_db_data['poster_name'];
    $user_signature = $spot_signing->signMessage($user_privatekey, $messageid);
		/*
		 * Create the spotnet from header part accrdoing to the following structure:
         *   From: [Nickname] <[PUBLICKEY-MODULO.USERSIGNATURE]@[CAT][KEY-ID][SUBCAT].[SIZE].[RANDOM].[DATE].[CUSTOM-ID].[CUSTOM-VALUE].[SIGNATURE]>
         */
    $spot = array(
        'key'=> $key,
        'created'=> time(),
        'poster'=> $poster_name,
        'filesize'=> $size,
        'tag'=> $tag,
        'category'=> $cat,
        'subcatlist'=> $subcats,
        'title'=> $subject,
        'description'=> $description,
        'website'=> $url,
    );

    $image_info = array ('segments' => $image_segments, 'width'=> $spot_db_data['image_width'], 'height' => $spot_db_data['image_height']);

    $xml = convert_spot_to_xml($spot, $image_info, $nzb_segments);

    $spot_hdr = $cat . $key . $subcats_str. '.' . $size . '.' . 10 . '.' . time() . '.' . generate_password(5) . '.' . generate_password(3) . '.';
    if ($tag != '') {
        $subject .=  '|' . $tag;
    }

    $server_signature = $spot_signing->signMessage($server_privatekey, $messageid);
  	$header_signature = $spot_signing->signMessage($user_privatekey, $subject . $spot_hdr . $poster_name);
    $xml_signature = $spot_signing->signMessage($user_privatekey, $xml);
    $header_data['X-Server-Signature'] = prepare_base64($server_signature['signature']);
    $header_data['X-Server-Key'] = pubkey_to_xml($server_signature['publickey']);
    $from = $poster_name . ' <' . prepare_base64($user_signature['publickey']['modulo']) . '.' . prepare_base64($user_signature['signature']) . '@';
    $spot_hdr = $from . $spot_hdr . prepare_base64($header_signature['signature']) . '>';
    $header_data['X-User-Signature'] = prepare_base64($user_signature['signature']);
    $header_data['X-User-Key'] = pubkey_to_xml($user_signature['publickey']);
    $header_data['X-No-Archive'] = 'yes';
    $header_data['From'] = $spot_hdr;
    $tmp_xml = explode("\r\n", safe_chunk($xml, 900));
    foreach($tmp_xml as $xml_chunk) {
        if (strlen(trim($xml_chunk)) > 0) {
            $header_data['X-XML'][] = $xml_chunk;
        }
    } 
    $header_data['X-XML-Signature'] = prepare_base64($xml_signature['signature']);
    $header_data['Message-ID'] = $messageid;
    
    return $header_data;
}

function post_message(DatabaseConnection $db, action $item, $poster_headers, $message, $groupid, $subject, $poster_id, $poster_name)
{
    $useragent = urd_version::get_urd_name() . ' ' . urd_version::get_version();
    $group_name = group_name($db, $groupid);
    $header = array(
            'From'          => "From: $poster_name <$poster_id>",
            'Newsgroups'    => "Newsgroups: $group_name",
            'Subject'       => "Subject: $subject",
            'User-Agent'    => "User-Agent: $useragent",
            'x-newsreader'  => "X-Newsreader: $useragent",
            );
    if (is_array($poster_headers)) {
        foreach ($poster_headers as $k => $h) {
            if (is_array($h)) { 
                foreach ($h as $i => $v) {
                    $header[ "$k;$i" ] = "$k: $v";
                }
            } else {
                $header[ $k ] = "$k: $h";
            }
        }
    }
    $header = implode("\r\n", $header) . "\r\n\r\n";
    $server_id = $item->get_preferred_server();
    $nntp = connect_nntp($db, $server_id);
    $nntp->select_group($groupid, $code);
    $article = wordwrap($message, 70, "\r\n", FALSE);
    $nntp->post_article(array($header, $article));
    $nntp->disconnect();
}

function do_post_spot(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    try {
        mt_srand ();
        $args = $item->get_args();
        if (!is_numeric($args)) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'No spot post ID given');
            update_postinfo_status ($db, POST_ERROR, $postid, NULL);
            throw new exception ('No post ID given', POST_FAILURE);
        }

        $postid = $args;
        $sql = '* FROM spot_postinfo WHERE "id"=?';
        $res = $db->select_query($sql, 1, array($postid));
        if (!isset($res[0])) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Invalid spot post ID given');
            update_postinfo_status ($db, POST_ERROR, $postid, NULL);
            throw new exception ('Invalid spot post ID given', POST_FAILURE);
        }
        $res[0]['description'] = db_decompress($res[0]['description']);
        $userid = $item->get_userid();

        $bin_group = get_config($db, 'ftd_group'); // for images and nzbs
        $spots_group = get_config($db, 'spots_group'); // for the spot itself

        $bin_group_id = get_all_group_by_name($db, $bin_group);
        $spots_group_id = get_all_group_by_name($db, $spots_group);
        $server_id = $item->get_active_server();
        if ($server_id == 0) {
            $server_id = $item->get_preferred_server();
        }
        $nntp = connect_nntp($db, $server_id);
        $nntp->select_group($bin_group_id, $code);
        $nzb = file_get_contents($res[0]['nzb_file']);
        if ($nzb === FALSE) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Invalid nzb');
            throw new exception('File not found: ' . $nzb);
        }
        $image = file_get_contents($res[0]['image_file']);
        if ($image === FALSE) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Invalid image');
            throw new exception('File not found: ' . $image);
        }

        $nzb_segments = post_binary_data($db, $res[0]['poster_name'], 'urd@spot.net', $bin_group, $nntp, gzdeflate($nzb));
        $image_segments = post_binary_data($db, $res[0]['poster_name'], 'urd@spot.net', $bin_group, $nntp, $image);
        $poster_headers = create_spot_data($db, $res[0], $userid, $nzb_segments, $image_segments);
        post_message($db, $item, $poster_headers, $res[0]['description'], $spots_group_id, $res[0]['subject'], 'urd@spot.net', $res[0]['poster_name']);
        $status = QUEUE_FINISHED;
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Complete'); 
        add_stat_data($db, stat_actions::POST_SPOT_COUNT, 1, $userid);
    } catch (exception $e) {
        $comment = $e->getMessage();
        write_log('Posting spot failed ' . $comment, LOG_WARNING);
        if ($e->getcode() == ERR_NNTP_AUTH_FAILED) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);
            $err_code = NNTP_AUTH_ERROR;
        } elseif ($e->getcode() == ERR_GROUP_NOT_FOUND) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, $comment);
            $err_code = GROUP_NOT_FOUND;
        } elseif ($e->getcode() == ERR_NO_ACTIVE_SERVER) {
            $err_code = CONFIG_ERROR;
        } else {
            $err_code = NNTP_NOT_CONNECTED_ERROR;
        }
        return $err_code;
    }
    return NO_ERROR;
}

function do_prepare_post(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    mt_srand ();
    $args = $item->get_args();
    if (!is_numeric($args)) {
        $status = QUEUE_FAILED;
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'No post ID given');
        update_postinfo_status ($db, POST_ERROR, $postid, NULL);
        throw new exception ('No post ID given', POST_FAILURE);
    }

    $postid = $args;
    $sql = '* FROM postinfo WHERE "id"=?';
    $res = $db->select_query($sql, 1, array($postid));
    if ($res === FALSE || $res == array()) {
        $status = QUEUE_FAILED;
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Invalid post ID given');
        update_postinfo_status ($db, POST_ERROR, $postid, NULL);
        throw new exception ('Invalid post ID given', POST_FAILURE);
    }
    $row = $res[0];
    $delete_files = $row['delete_files'];
    $subject = $row['subject'];
    $name = $subject; // better name for rar files ...
    $name = str_replace(' ', '_', $name); // file names don't contain spaces but underscore
    $name = preg_replace('/[^A-Za-z0-9_.]/', '', $name); // replace all fancy characters by nothing

    $userid = $item->get_userid();
    $dir = $row['src_dir'];
    $size = dirsize($dir);
    update_postinfo_size($db, $postid, $size);
    $post_status = $row['status'];
    $filesize_rar = $row['filesize_rar'];
    $recovery_par = $row['recovery_par'];
    // check dir subdir of dlpath
    if ($post_status < POST_RARRED) {
        $post_status = create_rar_files($db, $item, $postid, $filesize_rar, $dl_path1, $userid, $dir, $name);
    } else {
        $dl_path1 = $row['tmp_dir'];
    }

    update_queue_status($db, $item->get_dbid(), NULL, 0, 1, 'RAR complete');

    if ($post_status < POST_PARRED) {
        $file_list1= create_par_files($db, $item, $postid, $recovery_par, $dl_path1, $userid, $dir, $name);
    } else {
        $dl_path1 = $row['tmp_dir'];
        $file_list1 = glob($dl_path1 . '*');
    }
    update_queue_status($db, $item->get_dbid(),NULL, 0, 2, 'PAR2 complete');

    if ($post_status < POST_YYENCODED) {
        $file_list1 = create_yenc_files($db, $item, $postid, $file_list1, $userid, $dir, $name);
    }
    $status = QUEUE_FINISHED;
    if ($delete_files > 0) {
        rmdirtree($dl_path1, 0, TRUE);
    }

    update_queue_status($db, $item->get_dbid(), $status, 0, 3, 'Complete');
}

function get_files_from_db(DatabaseConnection $db, $postid)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    assert(is_numeric($postid));
    static $lock_array = array('post_files' => 'write'); // for the article table
    $filenames = array();
    $ids = array();
    try {
        $db->lock($lock_array);
        $res = $db->select_query('* FROM post_files WHERE "status"=?', batch_size::POST_BATCH_SIZE, array(POST_READY));
        if ($res === FALSE) {
            $db->unlock();
            return array(); // where done
        }

        foreach ($res as $row) {
            $ids[] = $row['id'];
            $filenames[] = array('id' => $row['id'], 'filename' => $row['filename'], 'rarfile' => $row['rarfile'], 'rar_idx' => $row['rar_idx'], 'file_idx' => $row['file_idx']);
        }
        if (count($ids) > 0) {
            $db->update_query_2('post_files', array('status'=>POST_ACTIVE), '"id" IN (' . str_repeat('?,', count($ids) - 1) . '?)', $ids);
        }
        $db->unlock();
    } catch (exception $e) {
        $db->unlock();
        throw $e;
    }

    return $filenames;
}

function do_post_batch(DatabaseConnection $db, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    try {
        $args = $item->get_args();
        $postid = $args;
        $res = $db->select_query('* FROM postinfo WHERE "id"=?', 1, array($postid));
        if (!isset($res[0])) {
            $status = QUEUE_FAILED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Invalid post ID given');
            throw new exception ('Invalid post ID given', POST_FAILURE);
        }
        $row = $res[0];

        $dbid = $item->get_dbid();
        $group = $row['groupid'];
        $group_name = group_name($db, $group);
        $postername = $row['poster_name'];
        $poster_email = $row['poster_id'];
        $file_count = $row['file_count'];
        $subject = $row['subject'];

        $stat_id = get_stat_id($db, $postid, TRUE);
        $useragent = urd_version::get_urd_name() . ' ' . urd_version::get_version();
        $total_ready = get_post_articles_count_status($db, $postid, DOWNLOAD_READY);
        $total_count = get_post_articles_count($db, $postid);
        $done_start = $total_count - $total_ready;
        $server_id = $item->get_active_server();
        if ($server_id == 0) {
            $server_id = $item->get_preferred_server();
        }
        $nntp = connect_nntp($db, $server_id);
        $nntp->select_group($group, $code);

        $rarfile_count = get_rar_files($db, $postid);
        $post_status = POST_ACTIVE;
        update_postinfo_status ($db, $post_status, $postid, NULL);
        $header_template = array(
            'from'          => "From: $postername <$poster_email>",
            'newsgroups'    => "Newsgroups: $group_name",
            'subject'       => 'Subject: ',
            'user-agent'    => "User-Agent: $useragent",
            'content-type'  => 'Content-Type: text/plain; charset=ISO-8859-1',
            'content-transfer-encoding' => 'Content-Transfer-Encoding: 8bit',
            'x-newsreader'  => "X-Newsreader: $useragent",
            'x-no-archive'  => 'X-No-Archive: yes'
        );
        $success_count = $failed_count = $done_count = 0;
        $b_time = microtime(TRUE);
        for (;;) {
            $files = get_files_from_db($db, $postid);
            if ($files === array()) {
                break;
            }

            foreach ($files as $a_file) {
                $rarfile = $a_file['rarfile'];
                $rar_idx = $a_file['rar_idx'];
                $file_idx = $a_file['file_idx'];
                $filename = $a_file['filename'];
                if (isset($rarfile_count[$rarfile])) {
                    $rar_max_count = $rarfile_count[$rarfile];
                } else {
                    write_log("Sending wrong file? $rarfile", LOG_WARNING);
                    continue;
                }
                $header = $header_template;
                $header['subject'] .= "\"$subject\" [$rar_idx/$file_count] " . $rarfile . " ($file_idx/$rar_max_count)";
                $header = implode("\r\n", $header) . "\r\n\r\n";
                try {
                    $article = file_get_contents($filename) . "\r\n";
                    echo_debug("Posting $filename", DEBUG_SERVER);
                    $bytes = strlen($article);
                    $articleid = $nntp->post_article(array($header, $article));
                    unset($article, $header); 
                    $success_count++;
                    update_dlstats($db, $stat_id, $bytes);
                    $art_status = POST_FINISHED;
                    unlink($filename);
                } catch (exception $e) {
                    write_log('Posting article_failed ' . $e->getMessage(), LOG_ERR);
                    $articleid = '';
                    $art_status = POST_FAILED;
                    $failed_count++;
                }
                $db->update_query_2('post_files', array('status'=>$art_status, 'articleid'=> $articleid), '"id"=?', array($a_file['id']));
                $sql = 'count(*) AS cnt FROM post_files WHERE "status" IN (:stat1, :stat2) AND "postid"=:postid';
                $rv = $db->select_query($sql, array(':stat1'=>POST_FINISHED, ':stat2'=>POST_FAILED,':postid'=> $postid));
                if ($res === FALSE) {
                    throw new exception('Post not found?', POST_FAILURE);
                }
                $done_count = $rv[0]['cnt'];
                $remain = $total_count - $done_count;
                $f_time = microtime(TRUE);
                $time_diff = $f_time - $b_time;
                $done_ready = $done_count - $done_start;
                $percentage = ($total_count > 0 ) ? floor(100 * ($done_count / $total_count)) : 0;
                $eta = ($done_count > 0) ? (round(($remain * $time_diff) / $done_ready)) : 0;
                $speed = ($time_diff > 0) ? (round($bytes / $time_diff)) : 0;
                store_ETA($db, $eta, $percentage, $speed, $dbid);
            }
        }
        $nntp->disconnect();
        $status = QUEUE_FINISHED;
        write_log("Posted $success_count article to newsgroup, $failed_count articles failed", LOG_INFO);
        update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'Complete');
    } catch (exception $e) {
        return NNTP_NOT_CONNECTED_ERROR;
    }
}

function restart_post(DatabaseConnection $db, $command, server_data &$servers, $userid, $id, $priority=NULL)
{
    assert(is_numeric($id));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    if ($command == urdd_protocol::COMMAND_POST) {
        $item = new action(urdd_protocol::COMMAND_POST, $id, $userid, FALSE);
    } else {
        $item = new action(urdd_protocol::COMMAND_START_POST, $id, $userid, FALSE);
    }
    if ($servers->has_equal($item)) {
        return urdd_protocol::get_response(406);
    }
    $dl_status = POST_ACTIVE;
    $ready_status = POST_READY;
    update_postinfo_status ($db, $ready_status, $id, $dl_status);
    update_post_articles($db, $ready_status, $id, $dl_status);
    if ($item->is_paused()) {
        $status = POST_PAUSED;
        update_postinfo_status ($db, $status, $id);
    }
    echo_debug("Re-starting post $id", DEBUG_SERVER);

    $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
    if ($res === FALSE) {
        throw new exception_queue_failed('Could not queue item');
    }
    $id_str = "[{$item->get_id()}] ";

    return sprintf (urdd_protocol::get_response(210), $id, $id_str);
}

function cleanup_post(DatabaseConnection $db, $postid)
{
    assert(is_numeric($postid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $res = $db->delete_query('post_files', '"postid"=?', array($postid));
    $res = $db->select_query('"tmp_dir" FROM postinfo WHERE "id"=?', array($postid));
    if (!isset($res[0]['tmp_dir'])) {
        write_log('Could not find setting for tmp dir in post', LOG_ERR);

        return;
    }
    $dir = $res[0]['tmp_dir'];
    $dlpath = get_dlpath($db);
    if (strpos($dlpath, $dir) !== 0) {
        rmdirtree($dir, 0, TRUE);
    } else {
        write_log ('Unknown tmp dir ' . $dir, LOG_WARNING); 
    }
}

function write_posted_file_to_nzb(DatabaseConnection $db, $file, $rarfile, $postid, $group)
{
    $res = $db->select_query('"articleid", "file_idx", "size", "rar_idx" FROM post_files WHERE "rarfile"=? AND "postid"=? ORDER BY "file_idx"', array($rarfile, $postid));
    $total_parts = count($res);
    $date = time();
    $name = preg_replace("/[^a-zA-Z0-9\(\)\! .]/", '', str_replace('"', '', $rarfile));
    $rar_idx = $res[0]['rar_idx'];
    $file_idx = 1;
    $str  = "<file poster=\"who@no.com\" date=\"$date\" subject=\"$name $file_idx of $rar_idx (1/{$total_parts})\">\n";
    $str .= "<groups>\n";
    $str .= "<group>$group</group>\n";
    $str .= "</groups>\n";
    $str .= "<segments>\n";
    foreach($res as $row) {
        $size = $row['size'];
        $messageID = $row['articleid'];
        $idx = $row['file_idx'];
        $str .= "<segment bytes=\"$size\" number=\"$idx\">$messageID</segment>\n";
    }

    $str .= "</segments>\n";
    $str .= "</file>\n";
    fwrite($file, $str);
}

function create_nzb_from_post(DatabaseConnection $db, $postid)
{
    $res = $db->select_query('"groupid", "userid", "subject" FROM postinfo WHERE "id"=?', 1, array($postid));
    if (!isset($res[0]['groupid'])) {
        write_log('Group not found', LOG_WARNING);
    }
    $groupid = $res[0]['groupid'];
    $userid = $res[0]['userid'];
    $subject = $res[0]['subject'];
    $username = get_username($db, $userid);
    $group = get_all_group_by_id ($db, $groupid);
    $base_dlpath = get_dlpath($db);
    $dlpath = $base_dlpath . NZB_PATH . $username . DIRECTORY_SEPARATOR;
    $file_name = sanitise_download_name($db, $subject);
    $nzb_file = find_unique_name($dlpath, '', $file_name, '.nzb', TRUE);
    $file = fopen($nzb_file, 'w+');
    $size = 0;
    $str = '<' . '?' . 'xml version="1.0" encoding="us-ascii"' . '?' . '>' . "\n"; // screws up syntax highlighting... hence the wacky sequence of < and ? and ? and > ... DO NOT CHANGE!
    $str .= '<!DOCTYPE nzb PUBLIC "-//newzBin//DTD NZB 1.0//EN" "http://www.newzbin.com/DTD/nzb/nzb-1.0.dtd">'. "\n";
    $str .= '<nzb xmlns="http://www.newzbin.com/DTD/2003/nzb">' . "\n"; // Note this URL is dead!
    $str .= '<!-- Created by ' . urd_version::get_urd_name() . ' ' . urd_version::get_version() . ' : http://www.urdland.com : The web-based usenet resource downloader. -->' . "\n";
    $size += fwrite($file, $str);

    $res = $db->select_query('DISTINCT "rarfile" FROM post_files WHERE postid=?', array($postid));
    foreach($res as $row) {
        write_posted_file_to_nzb($db, $file, $row['rarfile'], $postid, $group);
    }
    
    $str = '</nzb>' . "\n";
    $size += fwrite($file, $str);
    fclose($file);
    $done = move_file_to_nzb($db, NULL, $nzb_file, $dlpath, $file_name, '.nzb', $userid);
    set_permissions($db, $done); // setting permissions must be last otherwise we may not be able to move the file
    set_group($db, $done);
    $db->update_query_2('postinfo', array('nzb_file'=> $done), '"id"=?', array($postid));
}

function complete_post(DatabaseConnection $db, server_data &$servers, action $item, $status)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    if (!$servers->has_equal($item)) {
        echo_debug("Last post (STATUS $status)", DEBUG_SERVER);
        $postid = $item->get_args();
        $sql = 'count(*) AS cnt FROM post_files WHERE "postid"=? AND "status"=?';
        $res = $db->select_query($sql, array($postid, POST_FAILED) );
        $failed = $res[0]['cnt'];
        $sql = 'count(*) AS cnt FROM post_files WHERE "postid"=? AND "status"=?';
        $res = $db->select_query($sql, array($postid, POST_FINISHED));
        $finished = $res[0]['cnt'];
        $sql = 'count(*) AS cnt FROM post_files WHERE "postid"=? AND "status"=?';
        $res = $db->select_query($sql, array($postid, POST_ACTIVE));
        $active = $res[0]['cnt'];
        $sql = 'count(*) AS cnt FROM post_files WHERE "postid"=? AND "status"=?';
        $res = $db->select_query($sql, array($postid, POST_READY));
        $queued = $res[0]['cnt'];
        write_log("Posted articles: Q: $queued, D: $finished, F: $failed, A:$active", LOG_INFO);

        if ($status == DOWNLOAD_FINISHED || $status == DOWNLOAD_QUEUED || $status == DOWNLOAD_ACTIVE || $status == DOWNLOAD_READY) {
            if (($queued + $active) > 0) {
                // there are still things to download left
                // possibly a pause interrupted things
                $db->update_query_2('post_files', array('status' => POST_READY), '"postid"=? AND "status" IN (?, ?)', array($postid, POST_FAILED, POST_ACTIVE));
                $servers->queue_push($db, $item, FALSE);

                return;
            } elseif ($failed > 0) {
                write_log("Could not post all articles $failed failed", LOG_WARNING);
                $done_status = POST_FAILED;
            } else {
                $done_status = POST_FINISHED;
            }
            create_nzb_from_post($db, $postid);
            post_nzb($db, $item, $postid);
            cleanup_post($db, $postid);
            $qstatus = QUEUE_FINISHED;
            update_queue_status($db, $item->get_dbid(), $qstatus, 0, 100);
            echo_debug('post status finished', DEBUG_SERVER);
            // todo create NZB file from article ID

            update_postinfo_status ($db, $done_status, $postid);
            echo_debug("Post complete $postid", DEBUG_SERVER);
        } elseif ($status == DOWNLOAD_CANCELLED) {
            $done_status = POST_CANCELLED; // a cancel is permanent
            update_postinfo_status ($db, $done_status, $postid);
        } else {
            echo_debug("Unhandled status of download = $status", DEBUG_SERVER);
        }
    }
}

function post_nzb(DatabaseConnection $db, action $item, $postid)
{
    $res = $db->select_query('"nzb_file", "groupid_nzb", "poster_id", "poster_name" FROM postinfo WHERE "id"=?', 1, array($postid));
    if (!isset($res[0]['nzb_file']) || $res[0]['nzb_file'] == '') {
        write_log('No nzb file found');
        return;
    }
    $group_id = $res[0]['groupid_nzb'];
    $group_name = group_name($db, $group_id);
    $poster_name = $res[0]['poster_name'];
    $poster_email = $res[0]['poster_id'];
    $data = file_get_contents($res[0]['nzb_file']);
    $server_id = $item->get_active_server();
    if ($server_id == 0) {
        $server_id = $item->get_preferred_server();
    }
    $nntp = connect_nntp($db, $server_id);
    $nntp->select_group($group_id, $code);
    post_binary_data($db, $poster_name, $poster_email, $group_name, $nntp, $data);
}

function post_image(DatabaseConnection $db, action $item, $postid)
{
    $res = $db->select_query('"nzb_file", "groupid_nzb", "poster_id", "poster_name" FROM postinfo WHERE "id"=?', 1, array($postid));
    if (!isset($res[0]['nzb_file']) || $res[0]['nzb_file'] == '') {
        write_log('No nzb file found');
        return;
    }
    $group_id = $res[0]['groupid_nzb'];
    $group_name = group_name($db, $group_id);
    $poster_name = $res[0]['poster_name'];
    $poster_email = $res[0]['poster_id'];
    $data = file_get_contents($res[0]['nzb_file']);
    $server_id = $item->get_active_server();
    if ($server_id == 0) {
        $server_id = $item->get_preferred_server();
    }
    $nntp = connect_nntp($db, $server_id);
    $nntp->select_group($group_id, $code);
    post_binary_data($db, $poster_name, $poster_email, $group_name, $nntp, $data);
}

function safe_chunk($data, $maxLen, $end = "\r\n") 
{
    /*
     * We have to protect ourselves against having
     * only spaces in the stream, so we start with
     * the half of $maxLen, and work ourway up
     */
    $minLength = ceil($maxLen / 2);
    $totalChunk = '';

    while (strlen($data) > 0) {
        $sChunk = substr($data, 0, $minLength);
        $eChunk = substr($data, $minLength, $minLength);

        $eChunkLen = strlen($eChunk);
        while ((substr($eChunk, $eChunkLen - 1, 1) == ' ') && ($eChunkLen > 0)) {
            $eChunkLen--;
        } // while

        $totalChunk .= $sChunk . substr($eChunk, 0, $eChunkLen) . $end;
        $data = substr($data, strlen($sChunk . substr($eChunk, 0, $eChunkLen)));
    } // while

    return $totalChunk;
} // safe_chunk

function post_binary_data(DatabaseConnection $db, $postername, $poster_email, $group_name, $nntp, $data)
{
    $useragent = urd_version::get_urd_name() . ' ' . urd_version::get_version();
    $articles = array();

    $header_template = array(
        'from'          => "From: $postername <$poster_email>",
        'newsgroups'    => "Newsgroups: $group_name",
        'subject'       => 'Subject: ',
        'user-agent'    => "User-Agent: $useragent",
        'content-type'  => 'Content-Type: text/plain; charset=ISO-8859-1',
        'content-transfer-encoding' => 'Content-Transfer-Encoding: 8bit',
        'x-newsreader'  => "X-Newsreader: $useragent",
        'x-no-archive'  => 'X-No-Archive: yes'
    );

    $max_size = 1024 * 1024;
    while (strlen($data) > 0) {
        $data_part = substr($data, 0, $max_size - 1);
        $data = substr($data, $max_size - 1);
        $article = safe_chunk(special_zip_str($data_part), 900);

        $header = $header_template;
        $header['subject'] .= md5($data);
        $header = implode("\r\n", $header) . "\r\n\r\n";
        $articles[] = $nntp->post_article(array($header, $article));
    }
    return $articles;
}
