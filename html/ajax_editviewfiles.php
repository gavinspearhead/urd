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
 * $Id: ajax_editviewfiles.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */

define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';

$pathvf = realpath(dirname(__FILE__));

require_once "$pathvf/../functions/ajax_includes.php";

verify_access($db, urd_modules::URD_CLASS_VIEWFILES, FALSE, '', $userid, TRUE);

class file_icons
{
    private $icons;
    private $icons_inv;
    public function get_icons($idx)
    {
        return isset($this->icons[$idx]) ? $this->icons[$idx] : FALSE;
    }
    public function get_icons_inv($idx)
    {
        return isset($this->icons_inv[$idx]) ? $this->icons_inv[$idx] : FALSE;
    }

    public function __construct ()
    {
        $this->icons['audio'] = array('mp3', 'wma', 'ogg', 'flac', 'wav', 'ape', 'mpc','au', 'snd', 'oga', 'aiff', 'tak', 'aac', 'mid', 'mp4', 'm4a', 'ra');
        $this->icons['excel'] = array('xls', 'xlsx');
        $this->icons['exe'] = array('exe', 'com', 'bat', 'btm');
        $this->icons['flash'] = array('fla', 'flv', 'swf');
        $this->icons['html'] = array('htm', 'html', 'xhtml', 'tpl');
        $this->icons['iso'] = array('iso', 'img', 'bin', 'nrg', 'cue');
        $this->icons['php'] = array('php');
        $this->icons['source'] = array ('c', 'cc', 'cpp', 'h', 'hpp', 'pas', 'bas', 'pl');
        $this->icons['picture'] = array('jpg', 'jpeg', 'jpe', 'gif', 'bmp', 'png', 'svg', 'tiff', 'jpe', 'psd', 'ani');
        $this->icons['ppt'] = array('ppt', 'pps', 'ppsx', 'pptx');
        $this->icons['script'] = array('js');
        $this->icons['text'] = array('txt', 'text', 'nfo', 'log');
        $this->icons['video'] = array('avi', 'mpg', 'wmv', 'mpeg', 'asf', 'mkv', 'm4v',  'ogv', 'mov', 'vob');
        $this->icons['word'] = array('doc', 'rtf', 'docx');
        $this->icons['zip'] = array('zip', 'rar', 'ace', 'arj', 'lha', '7z', 'gz', 'tgz', 's7z', 'cab', 'ice','lzh', 'bz2', 'tbz2', 'zoo', 'arc', 'jar');
        $this->icons['stylesheet'] = array ('css');
        $this->icons['icon'] = array('ico');
        $this->icons['db'] = array('sql', 'db');
        $this->icons['pdf'] = array('pdf', 'epub', 'mobi');
        $this->icons['ebook'] = array('epub', 'mobi');
        $this->icons['nzb'] = array('nzb');
        $this->icons['par2'] = array('par2', 'par');
        $this->icons['sfv'] = array('sfv', 'sfvmd5', 'csv', 'csv2', 'csv4', 'sha1', 'md5', 'bsdmd5', 'crc');
        $this->icons['torrent'] = array('torrent');
        $this->icons['urdd_script'] = array(URDD_SCRIPT_EXT);
        $this->icons['playlist'] = array('m3u', 'pls', 'wpl', 'asx');

        // invert the icons array so that searching is faster
        $this-> icons_inv = array();
        foreach ($this->icons as $icon_type => $iconrange) {
            foreach ($iconrange as $ext) {
                $this->icons_inv[$ext] = $icon_type;
            }
        }
    }
}

function normalise_dir(DatabaseConnection $db, $dir, $username, $is_admin)
{
    global $LN;
    $dlpath = get_dlpath($db);
    if ($dir == '') {
        $cmd = 'show_files'; // if dir is not set we don't want to take any action --> so show files only
        $done_path = my_realpath($dlpath . DONE_PATH . $username) . DIRECTORY_SEPARATOR;
        $dirname = $done_path;
    } else {
        $dirname = my_realpath($dir) . DIRECTORY_SEPARATOR;
        if ($dirname == '' || $dirname === FALSE) {
            throw new exception($LN['viewfiles_filenotpermitted'], ERR_ACCESS_DENIED);
        }
        if ($is_admin) {
            if (substr($dirname, 0, strlen($dlpath)) != $dlpath) {
                $cmd = 'show_files'; // if dir is not set we don't want to take any action
                $dirname = $dlpath;
            }
        } else {
            $done_path = my_realpath($dlpath . DONE_PATH . $username) . DIRECTORY_SEPARATOR;
            $preview_path = my_realpath($dlpath . PREVIEW_PATH . $username) . DIRECTORY_SEPARATOR;
            $nzb_path = my_realpath($dlpath . NZB_PATH . $username) . DIRECTORY_SEPARATOR;
            $post_path = my_realpath($dlpath . POST_PATH . $username) . DIRECTORY_SEPARATOR;
            $scripts_path = my_realpath($dlpath . SCRIPTS_PATH . $username) . DIRECTORY_SEPARATOR;

            if (substr($dirname, 0, strlen($done_path)) != $done_path &&
                substr($dirname, 0, strlen($preview_path)) != $preview_path &&
                substr($dirname, 0, strlen($scripts_path)) != $scripts_path &&
                substr($dirname, 0, strlen($nzb_path)) != $nzb_path &&
                substr($dirname, 0, strlen($post_path)) != $post_path) {
                    $cmd = 'show_files'; // if dir is not set we don't want to take any action
                    $dirname = $done_path;
            }
        }
    }
    add_dir_separator($dirname);

    return $dirname;
}

function match_files($filename, $file_list)
{
    foreach ($file_list as $f) {
        if (fnmatch($f, $filename, FNM_CASEFOLD)) {
            return TRUE;
        }
    }

    return FALSE;
}


class file_list
{
    private $files; // array
    private $dir; // sort direction
    private $total;
    private $path;
    public function __construct()
    {
        $this->total = 0;
        $this->files = array();
        $this->dir = 1;
    }
    public function get_total()
    {
        return $this->total;
    }
    protected function add_file($path, $file_name, $show_delete=TRUE)
    {
        global $icons;
        $path_filename = $path.$file_name;
        $nfo_link = '';
        $stats = @stat($path_filename);
        $perms = $stats['mode'];
        $owner = $stats['uid'];
        $group = $stats['gid'];
        $mtime = $stats['mtime'];
        $owner = posix_getgrgid($owner);
        $owner = $owner['name'];
        $group = posix_getgrgid($group);
        $group = $group['name'];
        if (is_dir($path_filename)) {
            $glob_path = preg_quote($path_filename);
            $filetype = 'dir';
            $icon = 'folder';
            $size = count(glob($glob_path . '/*', GLOB_NOSORT));
            $nfo = glob($glob_path . '/*.nfo', GLOB_NOSORT);
            if (isset($nfo[0])) {
                $nfo_link = $nfo[0];
            }
        } else {
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $icon = $filetype = $icons->get_icons_inv($ext);
            if ($icon === FALSE) {
                $filetype = 'unknown';
                $icon = 'file';
            }
            $size = real_file_size($path_filename);
        }
        $this->files[] = new a_file($file_name, $filetype, $size, $mtime, $icon, $path, $perms, $owner, $group, $show_delete, $nfo_link);
    }

    public function read_dir($path, DatabaseConnection $db, $may_delete=FALSE, $search = '')
    {
        global $hiddenfiles, $LN;
        $cnt = 0;
        $dir = @opendir($path);
        if ($dir === FALSE) {
            throw new exception($LN['error_dirnotfound'] . ' ' . htmlentities("($path)", ENT_QUOTES, 'UTF-8'));
        }
        $this->path = $path;
        $show_delete = ($path != get_dlpath($db)) && $may_delete;

        while (FALSE !== ($entry = readdir($dir))) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            if (match_files($entry, $hiddenfiles)) {
                continue;
            }
            if ($search != '') {
                $search_terms = split_args($search);
                foreach ($search_terms as $s) {
                    $s = trim($s);
                    if ($s == '') {
                        continue;
                    }
                    if ($s[0] == '-') {
                        $s = ltrim($s, '-');
                        if (fnmatch("*$s*", $entry, FNM_CASEFOLD) !== FALSE) {
                            continue 2;
                        }
                    } else {
                        if (fnmatch("*$s*", $entry, FNM_CASEFOLD) === FALSE) {
                            continue 2;
                        }
                    }
                }
            }
            $cnt++;
            $this->add_file($path, $entry, $show_delete);
        }
        closedir($dir);
        $this->total = $cnt;
    }
    private function sort_owner(a_file $a, a_file $b)
    {
        return $this->gen_sort_str($a, $b, 'get_type');
    }
    private function sort_group(a_file $a, a_file $b)
    {
        return $this->gen_sort_str($a, $b, 'get_group');
    }
    private function sort_name(a_file $a, a_file $b)
    {
        return $this->gen_sort_str($a, $b, 'get_name');
    }
    private function cmp ($a, $b)
    {
        if ($a == $b) {
            return 0;
        } else {
            return $this->dir * ($a < $b ? -1 : 1);
        }
    }
    private function sort_size(a_file $a, a_file $b)
    {
        return $this->gen_sort($a, $b, 'get_size_unformated');
    }
    private function gen_sort(a_file $a, a_file $b, $method)
    {
        if ($a->get_type() == 'dir' && $b->get_type() == 'dir') {
            return $this->cmp($a->$method(), $b->$method());
        } elseif ($a->get_type() == 'dir') {
            return -1;
        } elseif ($b->get_type() == 'dir') {
            return 1;
        } else {
            return $this->cmp($a->$method(), $b->$method());
        }
    }
    private function gen_sort_str(a_file $a, a_file $b, $method)
    {
        if ($a->get_type() == 'dir' && $b->get_type() == 'dir') {
            return $this->dir * strnatcasecmp($a->$method(), $b->$method());
        } elseif ($a->get_type() == 'dir') {
            return -1;
        } elseif ($b->get_type() == 'dir') {
            return 1;
        } else {
            return $this->dir * strnatcasecmp($a->$method(), $b->$method());
        }
    }

    private function sort_mtime(a_file $a, a_file $b)
    {
        return $this->gen_sort($a, $b, 'get_mtime_unformated');
    }
    private function sort_perms(a_file $a, a_file $b)
    {
        return $this->gen_sort($a, $b, 'get_perms_num');
    }
    private function sort_type(a_file $a, a_file $b)
    {
        return $this->gen_sort($a, $b, 'get_type');
    }
    public function get_files($sort_key, $start, $count=NULL, $sort_dir = 1, $add_parent_dir=TRUE)
    {
        $this->dir = ($sort_dir == -1) ? -1 : 1;
        switch ($sort_key) {
        default:
        case 'default':
        case 'name':
            usort($this->files, array($this, 'sort_name'));
            break;
        case 'owner':
            usort($this->files, array($this, 'sort_owner'));
            break;
        case 'group':
            usort($this->files, array($this, 'sort_group'));
            break;
        case 'perms':
            usort($this->files, array($this, 'sort_perms'));
            break;
        case 'size':
            usort($this->files, array($this, 'sort_size'));
            break;
        case 'mtime':
            usort($this->files, array($this, 'sort_mtime'));
            break;
        case 'type':
            usort($this->files, array($this, 'sort_type'));
            break;
        }

        stored_files::store_file($this->files);
        if ($add_parent_dir) {
            $up_dir = new a_file ('..', 'dir', '', '', 'folder', $this->path, NULL, NULL, NULL, FALSE, '');
            $file_list = array_merge(array ($up_dir), array_slice($this->files, $start, $count));
        } else {
            $file_list = array_slice($this->files, $start, $count);
        }

        return $file_list;
    }
}


try { 
    $title = $LN['urdname'] . ' - ' . $LN['viewfiles_title'];
    $icons = new file_icons();
    $rprefs = load_config($db);

    $is_admin = urd_user_rights::is_admin($db, $userid);
    $is_fileeditor = urd_user_rights::is_file_editor($db, $userid);

    $hiddenfiles = $hiddenfiles_root = array();
    if ($prefs['hiddenfiles']) {
        $hiddenfiles = get_hiddenfiles(get_pref($db, 'hidden_files_list', $userid));
    }
    if ($rprefs['global_hiddenfiles']) {
        $hiddenfiles_root = get_hiddenfiles(get_config($db, 'global_hidden_files_list'));
    }

    $hiddenfiles = array_merge($hiddenfiles, $hiddenfiles_root);

    stored_files::set_cache_dir(get_config($db, 'dlpath') . DIRECTORY_SEPARATOR . FILELIST_CACHE_PATH);

    $cmd = trim(get_request('cmd', ''));
    $dir = get_request('dir', '');

    try {
        $dirname = normalise_dir($db, $dir, $username, $is_admin);
    } catch (exception $e) {
        if ($e->getCode() == ERR_PATH_NOT_FOUND) {
            throw new exception($LN['error_dirnotfound'] . ' ' . $dir);
        } else {
            throw new exception($LN['error_nodlpath'], 'admin_config.php', $LN['error_setithere']);
        }
    }

    $search = get_request('search', '');

    $submitbutton = get_request('submitbutton', '');
    $offset = get_request('offset', 0);
    if ($submitbutton != '') {// reset the page skipper if the search button is pressed
        $offset = 0;
    }
    $sort = get_request('sort', 'name');
    $sort_dir = get_request('sort_dir', 'asc');
    $perpage = get_maxperpage($db, $userid);
    $perpage = get_request('perpage', $perpage);
    $only_rows  = get_request('only_rows', 0);
    $rename_file = NULL;
    if ($sort == '') {
        if (isset($_SESSION['viewfiles']['sort'])) {
            $sort = $_SESSION['viewfiles']['sort'];
        } else {
            $sort = 'name';
        }
    }
    if ($sort_dir == '') {
        if (isset($_SESSION['viewfiles']['sort_dir'])) {
            $sort_dir = $_SESSION['viewfiles']['sort_dir'];
        } else {
            $sort_dir = 'asc';
        }
    }

    $_SESSION['viewfiles']['sort'] = $sort;
    $_SESSION['viewfiles']['sort_dir'] = $sort_dir;
    $currentdir = $dirname;

    $new_file = FALSE;
    switch (strtolower($cmd)) {
        case 'new_file':
            $new_file = TRUE;
        case 'edit_file':
            $allow_edit = $rprefs['webeditfile'] && ($is_fileeditor);
            if (!$allow_edit) {
                throw new exception($LN['error_filenotallowed'] );
            }
            if (!$new_file) {
                $filename = get_post('filename', '');
                if ($filename == '') {
                    throw new exception($LN['error_needfilenames']);
                }
                $fullname = $dirname.$filename;
                if (filesize($fullname) > ($rprefs['maxfilesize']) && $rprefs['maxfilesize'] != 0) {
                    throw new exception($LN['error_filetoolarge'] . ': ' .  $filename);
                }
                $file_contents = @file_get_contents($fullname);
                if ($file_contents === FALSE || ($file_contents == '' && filesize($fullname) > 0)) {
                    throw new exception($LN['error_filereaderror'] . ': ' . $filename);
                }
                $_SESSION['viewfiles']['file_edit'] = $fullname;
            } else {
                $file_contents = $filename = '';
                $_SESSION['viewfiles']['file_edit'] = '';
            }
            init_smarty();
            $smarty->assign(array(
                'error'=>            '',
                'textboxsize'=>		TEXT_BOX_SIZE,
                'directory'=>		$currentdir,
                'new_file'=>		    $new_file,
                'file_contents'=>	htmlentities($file_contents),
                'maxstrlen'=>		((int) $prefs['maxsetname'])/3,
                'filename'=>		    $filename));
            $contents =  $smarty->fetch('ajax_editfile.tpl');
            return_result(array('contents'=>$contents));
            break;

        case 'save_file':
            $allow_edit = $rprefs['webeditfile'] && ($is_fileeditor || $is_admin);
            if (!$allow_edit) {
                throw new exception($LN['error_filenotallowed']);
            }
            challenge::verify_challenge($_POST['challenge']);
            $filename = get_post('filename', '');
            if ($filename == '') {
                throw new exception($LN['error_needfilenames']);
            }
            if (strpos($filename, DIRECTORY_SEPARATOR) !== FALSE) {
                throw new exception($LN['error_invalidfilename']);
            }
            $newdir = (get_post('newdir', 0) == 1) ? TRUE: FALSE;
            $newfile = get_post('newfile', 0);
            $fullname = $dirname.$filename;
            $contents = get_post('file_contents', NULL);
            if ($contents === NULL) {
                throw new exception($LN['error_invalidvalue']);
            }
            if ($newfile == 1) {
                if (file_exists($fullname)) {
                    throw new exception($LN['error_fileexists']);
                }
            } else {
                if ($_SESSION['viewfiles']['file_edit'] != $fullname) {
                    throw new exception($LN['error_filenotallowed']);
                }
            }
            if ($newdir) {
                $rv = @mkdir($fullname);
                if ($rv === FALSE) {
                    throw new exception($LN['error_notmakedir']);
                }
            } else {
                $rv = @file_put_contents($fullname, $contents, LOCK_EX);
                if ($rv === FALSE) {
                    throw new exception($LN['error_nowrite']);
                }
            }
            break;

        case 'delete_file':
            if (!$is_admin && !$is_fileeditor) {
                throw new exception($LN['error_filenotallowed']);
            }
            challenge::verify_challenge($_POST['challenge']);
            //delete a single file
            $file = get_post('filename','');
            if ($file == '') {
                throw new exception($LN['error_needfilenames']);
            }
            $rv = FALSE;
            if (strpos($file, DIRECTORY_SEPARATOR) === FALSE) {
                $rv = @unlink($dirname . $file);
            }
            if ($rv === FALSE) {
                throw new exception($LN['error_noremovefile']. ': ' . htmlentities($file));
            }
            return_result(array('message'=> ($LN['deleted'] . ' ' . htmlentities($dirname . $file, ENT_QUOTES, 'UTF-8'))));
            break;
        case 'delete_dir':
            if (!$is_admin && !$is_fileeditor) {
                throw new exception($LN['error_filenotallowed']);
            }
            challenge::verify_challenge($_POST['challenge']);
            // recursively delete files and dirs
            $subdir = get_post('filename', '');
            if ($subdir == '') {
                throw new exception($LN['error_needfilenames']);
            }
            $rv = TRUE;
            $error = '';
            if (strpos($subdir, DIRECTORY_SEPARATOR) === FALSE && $subdir != '..' && $subdir != '.') {
                try {
                    list ($count, $error) = rmdirtree($dirname . $subdir, 0, TRUE);
                    if ($error == '') {
                        $rv = TRUE;
                    }
                } catch (exception $e) {
                    throw new exception($e->getMessage());
                }
            } else {
                $rv = FALSE;
            }
            if ($rv === FALSE) {
                throw new exception($LN['error_noremovefile'] . " $error $subdir");
            }
            if ($error != '') {
                throw new exception($error);
            }
            return_result(array('message'=> ($LN['deleted'] . ' ' . htmlentities($dirname . $subdir, ENT_QUOTES, 'UTF-8'))));
            break;
        case 'zip_dir': // not very neat now....
            if ($rprefs['webdownload'] != 1) {
                throw new exception($LN['error_filenotallowed']);
            }
            challenge::verify_challenge($_GET['challenge']);
            $tar_cmd = my_escapeshellcmd (get_config($db,'tar_path'));
            if ($tar_cmd != 'off' && $tar_cmd != '') {
                $subdir = get_request('filename');
                if (strpos($subdir, DIRECTORY_SEPARATOR) === FALSE && strstr($subdir, '..') === FALSE) {
                    $subdir_dl = preg_replace ('/[^a-z0-9_\-.]+/i', '_', $subdir);

                    $path = my_escapeshellarg ($dirname);
                    $filename = my_escapeshellarg($subdir);
                    set_time_limit(3600);
                    @ob_end_flush(); // turn off output buffering otherwise large files will be hit by the memory limit
                    $pipe = popen("$tar_cmd -cz -C $path $filename 2>> /tmp/tar", 'r');
                    if ($pipe === FALSE) {
                        throw new exception($LN['viewfiles_compressfailed']);
                    }
                    $size = 0;
                    header('Content-type: application/x-gtar');
                    header('Content-Description: URD Generated Data');
                    header("Content-Disposition: attachment; filename=\"$subdir_dl.tgz\"");

                    while (!feof($pipe)) {
                        $output = fread($pipe, 10240);
                        if ($output === FALSE) {
                            throw new exception($LN['error_filereaderror']);
                        } else {
                            $size += count($output);
                            echo $output;
                        }
                    }
                    pclose($pipe);
                    add_stat_data($db, stat_actions::WEBVIEW, $size, $userid);
                    die;
                } else {
                    throw new exception( $LN['error_accessdenied']);
                }
            } else {
                throw new exception($LN['viewfiles_tarnotset']);
            }
            die;
            break;
        case 'show_files':
            $files = new file_list;
            $files->read_dir($currentdir, $db, $is_admin || $is_fileeditor, $search);
            $dir = (strtolower($sort_dir) == 'desc')? -1: 1;
            list($pages, $currentpage, $lastpage) = get_pages($files->get_total(), $perpage, $offset);

            $tar_cmd = my_escapeshellcmd(get_config($db,'tar_path'));
            $use_tar = (int) (($tar_cmd != 'off' && $tar_cmd != '') && ($rprefs['webdownload'] == 1));

            $allow_edit = $rprefs['webeditfile'] && $is_fileeditor;

            init_smarty();
            $smarty->assign(array(
                'allow_edit'=>	$allow_edit,
                'search'=>		$search,
                'pages'=>		$pages,
                'use_tar'=>		$use_tar,
                'currentpage'=>	$currentpage,
                'lastpage'=>		$lastpage,
                'sort'=>			$sort,
                'sort_dir'=> 	$sort_dir,
                'offset'=> 		$offset,
                'directory'=>	$currentdir,
                'only_rows'=>    $only_rows,
                'files'=>		$ff = $files->get_files($sort, $offset, $perpage, $dir, ($only_rows == 0)),
                'last_line'=>    $offset + count($ff)));
            $contents = $smarty->fetch('ajax_showviewfiles.tpl');
            return_result(array('contents'=>$contents));
            break;
        case 'show_rename':
            $filename = get_post('filename', '');
            if ($filename == '') {
                throw new exception($LN['error_needfilenames']);
            }
            $fullname = $dirname.$filename;
            $rights = substr(sprintf('%o', fileperms($fullname)), -4);
            $group = posix_getgrgid(filegroup($fullname));
            $group = $group['name'];
            $groups_orig = read_system_groups();
            sort($groups_orig);
            foreach ($groups_orig as $g) {
                $groups["$g"] = $g;
            }

            init_smarty();
            $smarty->assign(array(
                'textboxsize'=>	TEXT_BOX_SIZE,
                'directory'=>	$currentdir,
                'maxstrlen'=>	((int) $prefs['maxsetname'])/3,
                'filename'=>		$filename,
                'rights'=> 		$rights,
                'group'=> 		$group,
                'groups'=> 		$groups));
            $contents = $smarty->fetch('ajax_editviewfiles.tpl');

            return_result(array('contents'=>$contents));
            die;
            break;
        case 'do_rename':
            challenge::verify_challenge($_POST['challenge']);
            $name = get_post('oldfilename', '');
            $newname = get_post('newfilename', '');
            $rights = get_post('rights', '');
            $group = get_post('group', '');
            if ($name != $newname) {
                if ($name == '' || $newname == '') {
                    throw new exception($LN['error_needfilenames']);
                }
                if (strpos($name, DIRECTORY_SEPARATOR) !== FALSE || strpos($newname, '/') !== FALSE) {
                    throw new exception($LN['error_invalidfilename']);
                }

                clearstatcache();
                if (file_exists($dirname.$newname)) {
                    throw new exception($LN['error_fileexists']);
                }
                $rv = @rename($dirname . $name, $dirname . $newname);
                if ($rv === FALSE) {
                    throw new exception($LN['error_cannotrename']);
                }
            }
            $fullname = $dirname.$newname;
            $currights = substr(sprintf('%o', fileperms($fullname)), -4);
            if ($currights != $rights || $rights == '') {
                $rv = @chmod ($fullname, octdec($rights));
                if ($rv === FALSE) {
                    throw new exception($LN['error_cannotchmod']);
                }
            }
            $curgroup = posix_getgrgid(filegroup($fullname));
            $curgroup = $curgroup['name'];
            if ($group != $curgroup || $group == '') {
                $rv = @chgrp($fullname, $group);
                if ($rv === FALSE) {
                    throw new exception($LN['error_cannotchgrp']);
                }
            }
            break;
        case 'up_nzb':
            challenge::verify_challenge($_POST['challenge']);
            try {
                $rprefs = load_config($db);
                $uc = new urdd_client($db, $rprefs['urdd_host'], $rprefs['urdd_port'], $userid);
                $uc->parse_nzb($_POST['dir'] . $_POST['filename']);
                $uc->disconnect();
            } catch (exception $e) {
                throw new exception($e->getMessage());
            }
             return_result(array('message'=> ($LN['uploaded'] . ' ' . htmlentities($_POST['dir'] . $_POST['filename'], ENT_QUOTES, 'UTF-8'))));
            break;
        default:
            throw new exception($LN['error_invalidaction'] . " $cmd");
            break;
    }
    return_result();
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
