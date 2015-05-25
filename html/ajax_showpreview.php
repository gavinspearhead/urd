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
 * $LastChangedDate: 2014-05-30 00:49:17 +0200 (vr, 30 mei 2014) $
 * $Rev: 3077 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showpreview.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';

$pathajsp = realpath(dirname(__FILE__));

require_once "$pathajsp/../functions/ajax_includes.php";

// Display progress bar, if 100% complete then redirect to the dl link:
class preview_data
{
    public $finished = -1;
    public $progress = 0;
    public $path = '';
    public $files = array();
    public $filename = '';
    public $title_str = '';
    public $do_reload = 1;
    public $isnzb = 0;
    public $size = 0;
    public $done_size = 0;
    public $filetype = '';
}

function get_preview_data(DatabaseConnection $db, $dlid, $binary_id, $group_id, $userid)
{
    global $LN;
    $progress = get_download_progress($db, $dlid);
    if ($progress === FALSE) {
        throw new exception($LN['error_noqueue']);
    }
    $dl_info = get_download_info($db, $dlid);
    $status = $dl_info['status'];

    $preview_data = new preview_data();
    $preview_data->size = $dl_info['size'];
    list($size, $suffix) = format_size($preview_data->size, 'h', $LN['byte_short'], 1024);
    $preview_data->size = $size . $suffix;
    $preview_data->done_size = $dl_info['done_size'];
    list($size, $suffix) = format_size($preview_data->done_size, 'h', $LN['byte_short'], 1024);
    $preview_data->done_size = $size . $suffix;
    if ($status > DOWNLOAD_FINISHED && $status != DOWNLOAD_COMPLETE) {
        $preview_data->filename = $dl_info['name'];
        $preview_data->title_str = $preview_data->filename;
        $preview_data->do_reload = 0;
    } elseif ($status != DOWNLOAD_FINISHED) {
        $preview_data->finished = 0;
        $preview_data->progress = floor($progress);
        $preview_data->filename = $dl_info['name'];
        $preview_data->title_str = $preview_data->progress . '%' . ' - ' . $preview_data->filename;
    } else {
        $preview_data->do_reload = 0;
        $preview_data->finished = 1;
        $preview_data->progress = 100;
        $preview_data->path = $dl_info['destination'];
        $preview_data->title_str = $preview_data->progress . '%' . ' - ' . basename($preview_data->path);
        // First, do a manual check if we're not pointing to /tmp (this should always be to /preview)
        // It would be better if QUEUE_FINISHED was only set after it's moved to /preview but this is a workaround.

        $dlpath = get_dlpath($db);
        $previewpath = $dlpath . PREVIEW_PATH;
        if (substr($preview_data->path, 0, strlen($previewpath)) == $previewpath) {
            // Everything cool, put all preview files into $files:
            if ($handle = @opendir($preview_data->path)) {
                while (FALSE !== ($file = readdir($handle))) {
                    
                    if (!in_array($file, array('.', '..', URDD_DOWNLOAD_LOCKFILE))) {
                        $ext = strtolower(ltrim(strrchr($file, '.'), '.'));
                        if (is_image_file($ext)) { 
                            $filetype = 'image'; 
                        } elseif (is_text_file($ext)) { 
                            $filetype = 'text'; 
                        } elseif (is_nzb_file($ext)) {
                            $filetype = 'nzb';
                        } else { 
                            $filetype = 'other'; 
                        }
                        $preview_data->files[] = array('file'=>$file, 'filetype'=>$filetype);
                        if (is_nfo_file($ext) && get_config($db, 'parse_nfo') != 0) {
                            if ($binary_id != '' && $group_id != 0) {
                                try {
                                    urd_extsetinfo::do_magic_nfo_extsetinfo_file($db, $preview_data->path, $file, $binary_id, $group_id, $userid);
                                } catch (exception $e) {
                                    // ignore
                                }
                            }
                        }
                    }
                }
                closedir($handle);
                $filename = '';
                $filetype = '';
                $isnzb = 0;
                foreach($preview_data->files as $file) {
                    if ($file['filetype'] == 'nzb') { // we always prefer nzb files;
                        $filename = $file['file'];
                        $filetype = 'nzb';
                        break;
                    } elseif ($file['filetype'] == 'image') {
                        $filetype = 'image';
                        $filename = $file['file'];
                    } elseif ($file['filetype'] == 'text' && ($filename == '' || $filetype == 'other')) {
                        $filetype = 'text';
                        $filename = $file['file'];
                    } elseif ($filename == '') {
                        $filetype = 'other';
                        $filename = $file['file'];
                    }
                }
                $preview_data->filename = $filename;
                $preview_data->filetype = $filetype;
                $preview_data->isnzb = $isnzb;
            }
        } else {
            // Downloaded file has not yet been moved from /tmp to /preview:
            $preview_data->finished = 0;
            $preview_data->progress = 99;
            $preview_data->files = array();
            write_log('Reached preview page while download was not yet moved to /preview', LOG_NOTICE);
        }
    }

    return $preview_data;
}

try {
    $dlid = get_request('dlid', 0);
    $binary_id = get_request('binary_id', 0);
    $group_id = get_request('group_id', 0);

    $preview_data = get_preview_data($db, $dlid, $binary_id, $group_id, $userid);
    init_smarty();
    $smarty->assign('do_reload',	$preview_data->do_reload);
    $smarty->assign('finished',	    $preview_data->finished);
    $smarty->assign('path',		    $preview_data->path);
    $smarty->assign('filetype',		$preview_data->filetype);
    $smarty->assign('file',		    $preview_data->filename);
    $smarty->assign('title_str',	$preview_data->title_str);
    $smarty->assign('dlsize',	    $preview_data->size);
    $smarty->assign('done_size',    $preview_data->done_size);
    $smarty->assign('file_utf8',	utf8_encode($preview_data->filename));
    $smarty->assign('progress',	    $preview_data->progress);
    $smarty->assign('nroffiles',	count($preview_data->files));

    $contents = $smarty->fetch('ajax_preview.tpl');
    return_result(array('contents' => $contents, 'filetype'=>$preview_data->filetype, 'file'=> $preview_data ->path . $preview_data->filename ));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
