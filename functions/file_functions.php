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
 * $LastChangedDate: 2011-04-05 20:00:36 +0200 (Tue, 05 Apr 2011) $
 * $Rev: 2113 $
 * $Author: gavinspearhead $
 * $Id: functions.php 2113 2011-04-05 18:00:36Z gavinspearhead $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function my_realpath($odir)
{ // realpath returns FALSE if the directory doesn't exist, which is nasty. This one throws
    global $LN;
    $dir = realpath($odir);
    if ($dir === FALSE) {
        throw new exception ($LN['error_dirnotfound'] . ": $odir", ERR_PATH_NOT_FOUND);
    }

    return $dir;
}

function cleanup_dir($directory, array $files)
{ // remove files left by par2
    assert($directory != '');
    foreach ($files as $f) {
        $fn = $directory . DIRECTORY_SEPARATOR . $f . '.1';
        if (file_exists ($fn)) {
            unlink($fn);
        }
    }
}

function my_escapeshellcmd($str, $hide_space=TRUE)
{ // alias

    return my_escapeshellarg($str, $hide_space);
}

function my_escapeshellarg($str, $hide_space=TRUE)
{ // you really have to do everything yourself in php...
    // note that this isn't a proper command line escape thingie as it doesn't handle piping and quoting by it self. It just escapes all the naughty characters.
    $chars = array('\\', '#', '&', ';', '`', '|', '*', '?', '~', '<', '>', '^', '(', ')', '[', ']', '{', '}', '$', '\x0A', '\xFF', '\'', '"');
    $sub_chars = array('\\\\', '\\#', '\\&', '\\;', '\\`', '\\|', '\\*', '\\?', '\\~', '\\<', '\\>', '\\^', '\\(', '\\)', '\\[', '\\]', '\\{', '\\}', '\\$', '\\\x0A', '\\\xFF', '\\\'', '\\"');
    if ($hide_space) {
        $chars[] = ' ';
        $sub_chars[] = '\\ ';
    }
    return str_replace($chars, $sub_chars, $str);
}

function add_dir_separator(&$path)
{
    if (substr($path, -1) != DIRECTORY_SEPARATOR) {
        $path .= DIRECTORY_SEPARATOR;
    }
}

function delete_files($files, $dir)
{
    add_dir_separator($dir);
    foreach ($files as $f) {
        $fn = $dir . $f;
        $res = unlink ($fn);
        if ($res === FALSE) {
            write_log("Could not delete '$fn'", LOG_NOTICE);
        } else {
            echo_debug("Deleted file: '$fn'", DEBUG_WORKER);
        }
    }
}

function get_dlpath(DatabaseConnection $db)
{
    global $LN;
    $dlpath = get_config($db, 'dlpath');
    add_dir_separator($dlpath);
    clearstatcache();
    if (!is_dir($dlpath)) {
        throw new exception ($LN['error_nodlpath'] . " ($dlpath)", ERR_PATH_NOT_FOUND);
    }
    if (!is_writable($dlpath)) {
        throw new exception ($LN['error_dlpathnotwritable'] . " ($dlpath)", ERR_PATH_NOT_WRITABLE);
    }
    // check exists dir otherwise make it
    return $dlpath;
}

function find_name(DatabaseConnection $db, $string)
{
    $string = simplify_chars($string);
    if (preg_match_all("/\"(.+)\"/U", $string, $vars)) {
        $x = count($vars[1]) - 1;
        if (isset($vars[1][$x])) {
            $filename = $vars[1][$x];
        }
    } else {
        $filename = $string;
    }

    $filename = preg_replace("/(\.r[0-9]{2})|(\.sfv)|(\.nfo)|(\.mp3)|(\.jpg)|(\.nzb)|(\.par2)|(\.7z)|(\.ace)|(\.arj)|(\.gz)|(\.tar)|(.tgz)|(\.zip)|(.vol[0-9]+-[0-9]+\.PAR2)|(\.part[0-9]+\.rar)|(\.processing)/i", '', $filename);
    $filename = trim(sanitise_download_name($db, $filename));

    if (strlen($filename) < 5) {
        $filename .= ' ' . mt_rand(10000, 99999);
    }

    return $filename;
}

function rmdirtree($dirname, $age=0, $delete_top_dir = FALSE) // age in seconds // topdir can go??
{
    assert(is_numeric($age) && is_bool($delete_top_dir));
    global $LN;
    $error = '';
    $count = $up_count = 0;
    clearstatcache();
    if (!is_dir($dirname)) {
        return array (FALSE, '');    //Return false if attempting to operate on a file
    }
    if (!is_writable($dirname)) {   //Operate on dirs only
        throw new exception ($LN['error_noremovefile2'] . ': ' . $dirname, ERR_PATH_NOT_FOUND);
    }
    add_dir_separator($dirname);
    $handle = opendir($dirname);
    while (FALSE !== ($file = readdir($handle))) {
        if ($file == '.' || $file == '..') {  // Ignore . and ..
            continue;
        }
        try {
            $path = $dirname . $file;
            if (is_dir($path)) {    //Recurse if subdir, Delete if file
                if (is_writable($path) && is_executable($path)) {
                    list ($up_count, $error_in) = rmdirtree($path, $age, FALSE);
                    if ($up_count == 0) {
                        $rv = @rmdir($path);    //Remove dir
                        if ($rv === FALSE) {
                            $error .= $LN['error_noremovefile2'] . ': ' . $dirname;
                        }
                    } else {
                        $count++;
                    }

                    $error .= $error_in;
                } else {
                    throw new exception($LN['error_noremovefile2'] . ': ' . $path, ERR_PATH_NOT_FOUND);
                }
            } else {
                $mtime = filemtime($path);
                $now = time();
                if ($age == 0 || (($now - $mtime) > $age)) {
                    $rv = @unlink($path);
                    if ($rv === FALSE) {
                        throw new exception($LN['error_noremovefile'] . ': '. $path, ERR_FILE_NOT_FOUND);
                    }
                } else {
                    $count++;
                }
            }
        } catch (exception $e) {
            $error .= $e->getMessage() . ', ';
        }
    }
    closedir($handle);
    if ($delete_top_dir === TRUE) {
        $rv = @rmdir($dirname);
        if ($rv === FALSE) {
            throw new exception($LN['error_noremovedir'] . ': '. $dirname, ERR_PATH_NOT_FOUND);
        }
    }

    return array($count, $error);
}

function get_all_paths($base, $user='', $with_cache=FALSE)
{
    static $paths = array(TMP_PATH, DONE_PATH, PREVIEW_PATH, NZB_PATH, SPOOL_PATH, SCRIPTS_PATH, POST_PATH);
    $path_list = array();
    foreach ($paths as $p) {
        $path = $base . $p . $user;
        add_dir_separator($path);
        $path_list[] = $path;
    }
    // add the cache paths manually; they don't get user specific
    if ($with_cache) {
        $path_list[] = $base . CACHE_PATH;
        $path_list[] = $base . MAGPIE_CACHE_PATH;
        $path_list[] = $base . IMAGE_CACHE_PATH;
        $path_list[] = $base . FILELIST_CACHE_PATH;
    }

    return $path_list;
}

function create_dir($path, $perms)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $rv = TRUE;
    if (!is_dir($path)) {
        write_log("Creating directory $path", LOG_INFO);
        $rv = @mkdir($path, $perms, TRUE);
        @chmod($path, $perms);
    }

    return $rv;
}

function test_file_creation($path)
{
    $rand = '.urd_test_file' . mt_rand(10000, 99999);
    $rv = touch($path . $rand); // create a test file
    if ($rv === FALSE) {
        throw new exception("Could not create test file in directory $path", ERR_CONFIG_ERROR); // XXX
    }
    unlink($path . $rand); // remove test file
}

function is_cache_dir(DatabaseConnection $db, $path)
{
    try {
        $dlpath = get_dlpath($db);
        $cache_path = my_realpath($dlpath . CACHE_PATH);
        if (substr($path, 0, strlen($cache_path)) == $cache_path) {
            return TRUE;
        } else {
            return FALSE;
        }
    } catch (exception $e) {
        return FALSE;
    }
}

function create_user_dirs(DatabaseConnection $db, $base, $user)
{
    global $LN;
    assert(is_string($base) && is_dir($base) && is_string($user));
    $paths = get_all_paths($base, $user, FALSE);
    if (!is_array($paths)) {
        return;
    }
    foreach ($paths as $p) {
        $rv = create_dir($p, 0775);
        if ($rv === FALSE) {
            throw new exception($LN['error_notmakedir'] . " $p");
        }
        set_group($db, $p); // change the group if config is set
        if ((!is_dir($p) || !is_writable($p))) {// check if it valid now
            throw new exception($LN['error_dirnotfound'] . ": $p", ERR_CONFIG_ERROR);
        }
        test_file_creation($p);
    }
}

function create_required_user_dirs(DatabaseConnection $db, $base)
{
    global $LN;
    $users = get_all_users($db);
    if (!is_array($users)) {
        return;
    }
    foreach ($users as $user) {
        $path = $base . $user;
        $rv = create_dir($path, 0775);
        if ($rv === FALSE) {
            throw new exception($LN['error_notmakedir'] . " $path");
        }
        set_group($db, $path);
        if ((!is_dir($path) || !is_writable($path))) { // check if it valid now
            throw new exception($LN['error_dirnotfound'] . ": $path", ERR_CONFIG_ERROR);
        }
    }
}

function set_dirgroups($dir, $group)
{
    assert(is_string($dir) && $dir != '' && $group != '');
    $files = glob($dir . '/*', GLOB_NOSORT);
    if ($files === FALSE) {
        return;
    }
    foreach ($files as $f) {
        ch_group($f, $group);
        if (is_dir($f)) {
            set_dirgroups($f, $group);
        }
    }
}

function set_group(DatabaseConnection $db, $dir)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    try {
        $group = get_config($db, 'group');
    } catch (exception $e) {
        write_log('Cannot set group; db value not set', LOG_NOTICE);
        return;
    }
    if ($group == '') { // if it is blank we don't set it
        return;
    }
    if (posix_getgrnam($group) === FALSE) { // check if it is a valid group
        write_log('Invalid group specifid. Check /etc/groups', LOG_ERR);

        return;
    }
    ch_group($dir, $group); // change the group on the path
    set_dirgroups($dir, $group); // and do it recuresively
}

function copy_files($src, $dest, array $files, $match_path = NULL)
{
    // copy filse from dir src to dir dest, matching file name specs in $files; where src and dest must be a subdir of match_path
    $src = my_realpath($src);
    $dest = my_realpath($dest);
    if ($match_path !== NULL) {
        $match_path = my_realpath($match_path);
        if ($match_path == FALSE || substr($src, 0, strlen($match_path)) != $match_path ||substr($dest, 0, strlen($match_path)) != $match_path ) {
            return FALSE;
        }
    }
    if ($src == FALSE || $dest == FALSE) {
        return FALSE;
    }
    foreach ($files as $f) {
        $filenames = glob($src . DIRECTORY_SEPARATOR . $f);
        foreach ($filenames as $filename) {
            if ($match_path !== NULL && substr($filename, 0, strlen($match_path)) != $match_path) {
                return FALSE;
            }
            if (is_file($filename)) {
                copy($filename, $dest . DIRECTORY_SEPARATOR . basename($filename));
            }
        }
    }

    return TRUE;
}

function get_magpie_cache_dir(DatabaseConnection $db)
{
    $pathu = get_dlpath($db);
    $path = my_realpath("$pathu/" . MAGPIE_CACHE_PATH);

    return $path;
}

function real_file_size($file)
{// filesizes for large files aren't handled properly by PHP (douchebags!) so use an external command instead
    // fortunately all *nix version use stat in the same way :(
    if (!file_exists($file)) {
        return -1;
    }
    switch (PHP_OS) {
    case 'Linux':
        $file = my_escapeshellarg($file);
        $fs = exec ("stat -c %s $file", $ov);
        break;
    case 'FreeBSD':
        $file = my_escapeshellarg($file);
        $fs = exec ("stat -f %z $file", $ov);
        break;
    case 'WIN':
    default:
        $fs = filesize($file);
        break;
    }

    return $fs;
}

function dirsize($path)
{
    /* Updated from Aidan Lister <aidan@php.net>'s code */
    $size = 0;
    add_dir_separator($path);
    // Sanity check
    if (is_file($path)) {
        return real_file_size($path);
    } elseif (!is_dir($path)) {
        return FALSE;
    }

    // Iterate queue
    $queue = array($path);
    for ($i = 0, $j = count($queue); $i < $j; ++$i) {
        // Open directory
        //$parent = $i;
        if (is_dir($queue[$i]) && $dir = @dir($queue[$i])) {
            $subdirs = array();
            while (FALSE !== ($entry = $dir->read())) {
                // Skip pointers
                if ($entry == '.' || $entry == '..') {
                    continue;
                }

                // Get list of directories or filesizes
                $path = $queue[$i] . $entry;
                if (is_dir($path)) {
                    $path .= DIRECTORY_SEPARATOR;
                    $subdirs[] = $path;
                } elseif (is_file($path)) {
                    $size += real_file_size($path);
                }
            }

            // Add subdirectories to start of queue
            unset($queue[0]);
            $queue = array_merge($subdirs, $queue);

            // Recalculate stack size
            $i = -1;
            $j = count($queue);

            // Clean up
            $dir->close();
            unset($dir);
        }
    }

    return $size;
}


/* read last X lines of a given text file
   $filename = full path + filename (i.e. /home/marco/file.txt)
   $lines    = number of lines (i.e. 10)
 */
function read_last_lines($filename, $maxlines, &$error, $match, $min_log_level)
{
    global $log_str;
    assert(is_numeric($maxlines));
    /* freely customisable number of lines read per time*/
    $bufferlength = 5000;

    $handle = @fopen($filename, 'r');
    if (!$handle) {
        $error = URD_FILENOTFOUND;

        return -1;
    }

    /*get the file size with a trick*/
    fseek($handle, 0, SEEK_END);
    $filesize = ftell($handle);

    /*don't want to get past the start-of-file*/
    $position = - min($bufferlength, $filesize);
    $aliq = '';
    $lines = array();

    while ($maxlines > 0) {
        if (fseek($handle, $position, SEEK_END)) {  /* should not happen but it's better if we check it*/
            $error = URD_SEEKERROR;
            fclose($handle);

            return $lines;
        }

        /* big read*/
        $buffer = fread($handle, $bufferlength);

        /* small split*/
        $tmp2 = explode("\n", $buffer);

        /*previous read could have stored a partial line in $aliq*/
        if ($aliq != '') {
            /*concatenate current last line with the piece left from the previous read*/
            $cnt = count($tmp2);
            if ($cnt > 0) {
                $tmp2[$cnt - 1] .= $aliq;
            } else {
                $tmp2[] = $aliq;
            }
        }

        /*drop first line because it may not be complete*/
        $aliq = array_shift($tmp2);

        $tmp = array();
        $read = 0;
        foreach ($tmp2 as $line) {
            if ($match != '' && stripos($line, $match) === FALSE) {
                continue;
            }
            if ($min_log_level !== FALSE) {

                $t = explode (' ', $line);
                if (!isset($t[5])) {
                    continue;
                }
                $log_level = array_search($t[5], $log_str);
                if ($log_level === FALSE || $log_level > $min_log_level) {
                    continue;
                }
            }
            $tmp[] = $line;
            $read++;
        }
        if ($read >= $maxlines) {   /*have read too much!*/
            $tmp2 = array_slice($tmp, $read-$maxlines);
            /* merge it with the array which will be returned by the function*/
            $lines = array_merge($tmp2, $lines);

            /* break the cycle*/
            $maxlines = 0;
        } elseif (-$position >= $filesize) {  /* haven't read enough but arrived at the start of file*/

            //get back $aliq which contains the very first line of the file
            if (!is_array($aliq)) {
                $aliq = array();
            }
            if (!is_array($tmp)) {
                $tmp = array();
            }
            $lines = array_merge($aliq, $tmp, $lines);

            //force it to stop reading
            $maxlines = 0;

        } else {   /*continue reading...*/

            //add the freshly grabbed lines on top of the others
            $lines = array_merge($tmp, $lines);
            $maxlines -= $read;

            //next time we want to read another block
            $position -= $bufferlength;

            //don't want to get past the start of file
            $position = max($position, -$filesize);
        }
    }
    fclose($handle);
    $error = URD_NOERROR;

    return $lines;
}

