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
 * $Id: urdd_functions.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function get_base($fn, $ext)
{
    static $extensions = array (
        file_extensions::PAR_EXT => array('vol[0-9]+\+[0-9]+\.par2'),
        file_extensions::ZIP_EXT => array('zip'),
        file_extensions::ARJ_EXT => array('arj', 'a[0-9][0-9]'),
        file_extensions::ACE_EXT => array('ace', 'c[0-9][0-9]'),
        file_extensions::ZR7_EXT => array('7z', '7z\.[0-9][0-9][0-9]'),
        file_extensions::RAR_EXT => array('part[0-9]+\.rar', 'part[0-9]+\.r[0-9][0-9]', '\.r[0-9][0-9]', '\d{3}'),
        file_extensions::SFV_EXT => array('sfv', 'sfvmd5', 'csv', 'csv2', 'csv4', 'sha1', 'md5', 'bsdmd5', 'crc'),
        file_extensions::CAT_EXT => array("(?<!\.7z\.)\d{3}"),
        file_extensions::UUE_EXT => array('[0-9]+\.urd_uuencoded_part')
    );
    if (isset ($extensions[$ext])) {
        foreach (($extensions[$ext]) as $expr) {
            if (preg_match("/^(.*)\.$expr$/i", $fn, $fs)) {
                return $fs[1];
            }
        }

        return substr($fn, 0,  - (1 + strlen($ext))); // remove the $ext if no other match is found
    } else {
        return FALSE;
    }
}

function split_filename($filename)
{
    $pos = strrpos($filename, '.');
    if ($pos === FALSE) { // dot is not found in the filename

        return array($filename, ''); // no extension
    }
    $basename = substr($filename, 0, $pos);
    $extension = substr($filename, $pos + 1);

    return array($basename, $extension);
}

function get_par_rar_files(DatabaseConnection $db, $directory, &$all_files)
{
    static $extensions = array (
        file_extensions::PAR_EXT => array('par2'),
        file_extensions::ZIP_EXT => array('zip'),
        file_extensions::ARJ_EXT => array('arj', 'a[0-9][0-9]'),
        file_extensions::ACE_EXT => array('ace', 'c[0-9][0-9]'),
        file_extensions::ZR7_EXT => array('7z', '7z\.[0-9][0-9][0-9]'),
        file_extensions::RAR_EXT => array('rar', 'r[0-9][0-9]'),
        file_extensions::SFV_EXT => array('sfv'),
        file_extensions::CAT_EXT => array("(?<!\.7z\.)\d{3}"),
        file_extensions::UUE_EXT => array('urd_uuencoded_part')
    );

    $files = new pr_list($directory);
    if (!is_dir($directory)) {
        return $files;
    }
    $d = dir($directory);
    $all_files = array();
    while (FALSE !== ($entry = $d->read())) {
        if (in_array($entry, array('.', '..', URDD_DOWNLOAD_LOCKFILE))) {
            continue;
        }
        $all_files[] = $entry;
        $mt = match_mime_type($db, $directory . $entry); // find a mime type for the file
        if ($mt !== NULL && isset($extensions[$mt])) {
            $base = get_base($entry, $mt); // try and find the base of the filename, which we use to match all the files against, to collect a set
            if ($base === FALSE) {
                list($name) = split_filename($entry, $extensions[$mt]); //otherwise take just the extension off and use that
            } else {
                $name = $base;
            }
            $files->add($mt, $name, $entry);
        } else { // ok no mimetype determent, try by file extension
            foreach ($extensions as $ext => $expressions) {
                foreach ($expressions as $expr) {
                    if (preg_match("/^.*\.$expr$/i", $entry) ) {
                        $base = get_base($entry, $ext);
                        $files->add($ext, $base, $entry);
                        break;
                    }
                }
            }
        }
    }
    $d->close();

    return $files;
}

function match_mime_type(DatabaseConnection $db, $file)
{ // note: /x-* may not always be defined
    static $mime_types = array (
        file_extensions::PAR_EXT => 'application/x-par2',
        file_extensions::ZIP_EXT => 'application/zip',
        file_extensions::ARJ_EXT => 'application/x-arj',
        file_extensions::ACE_EXT => 'application/x-ace',
        file_extensions::ZR7_EXT => 'application/x-7z-compressed',
        file_extensions::RAR_EXT => 'application/x-rar',
        file_extensions::SFV_EXT => NULL,
        file_extensions::CAT_EXT => NULL,
        file_extensions::UUE_EXT => NULL
    );
    $mt = real_mime_content_type($db, $file, TRUE); // will use the file command... if available
    foreach ($mime_types as $ext => $type) {
        if ($mt == $type && $type !== NULL) {
            return $ext;
        }
    }

    return NULL;
}

function connect_nntp(DatabaseConnection $db, $id = FALSE)
{ // TODO fix
    echo_debug_function(DEBUG_MAIN, __FUNCTION__);
    if ($id === FALSE) { // should not happen
        write_log('No ServerID given (1) ... dying', LOG_ERR);
        throw new exception ('No Server ID given', ERR_NO_ACTIVE_SERVER);
    } elseif ($id == 0) { // should not happen
        write_log('No ServerID given (2) ... dying', LOG_ERR);
        throw new exception ('No Server ID given', ERR_NO_ACTIVE_SERVER);
    } 
    assert(is_numeric($id));
    $usenet_server = $id;
    echo_debug("Using server $usenet_server", DEBUG_WORKER);
    $usenet_config = get_usenet_server($db, $usenet_server, FALSE);
    $timeout = get_config($db, 'socket_timeout', -1);
    if ($timeout <= 0) {
        write_log('Invalid socket timeout set', LOG_WARNING);
        $timeout = socket::DEFAULT_SOCKET_TIMEOUT;
    }
    try {
        $nzb = new URD_NNTP($db, $usenet_config['hostname'], $usenet_config['connection'], $usenet_config['port'], $timeout);
        $nzb->connect($usenet_config['authentication']?TRUE:FALSE, $usenet_config['username'], $usenet_config['password']);

        return $nzb;
    } catch (exception $e) {
        $nzb->disconnect();
        unset($nzb);
        throw $e;
    }
}

function set_dirpermissions($dir, $np, $ndp)
{
    assert($dir != '');
    $files = glob ($dir . DIRECTORY_SEPARATOR . '*', GLOB_NOSORT);
    foreach ($files as $f) {
        if (is_dir($f)) {
            $rv = @chmod ($f, $ndp);
            if ($rv === FALSE) {
                write_log("Can't chmod directory: $f", LOG_ERR);
            }
            set_dirpermissions($f, $np, $ndp);
        } else {
            $rv = @chmod ($f, $np);
            if ($rv === FALSE) {
                write_log("Can't chmod directory: $f", LOG_ERR);
            }
        }
    }
}

function set_permissions(DatabaseConnection $db, $dir)
{
    assert($dir != '');
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    try {
        $perm = get_config($db, 'permissions');
    } catch (exception $e) {
        write_log('Cannot set permission; db value not set', LOG_NOTICE);

        return;
    }
    if ($perm == '') {
        return;
    }

    if (strlen($perm) == 4) {
        $u = $perm[1];
        $g = $perm[2];
        $w = $perm[3];
        $np = octdec($perm) & 0777;
        $ndp = $np; // directory permissions they always get x permissions if they have at least a non 0 value
        if ($g > 0) {
            $ndp |= 0010;
        }
        if ($w > 0) {
            $ndp |= 0001;
        }
        if ($u > 0) {
            $ndp |= 0100;
        }
        if (is_dir($dir)) {
            set_dirpermissions($dir, $np, $ndp);
            $rv = @chmod($dir, $ndp);
            if ($rv === FALSE) {
                write_log("Can't chmod directory: $dir", LOG_ERR);
            }
        } else {
            $rv = @chmod($dir, $np);
            if ($rv === FALSE) {
                write_log("Can't chmod directory: $dir", LOG_ERR);
            }
        }

    } else {
        write_log('Cannot set permission; incorrect value "' . $perm . '"' , LOG_NOTICE);

        return;
    }
}

function move_file_to_nzb(DatabaseConnection $db, $dlid, $filename, $dlpath, $basename, $ext, $userid)
{
    assert($filename != '' && is_numeric($userid) && (is_numeric($dlid)|| is_null($dlid)));

    $username = get_username($db, $userid);
    $from = $filename;

    $dlpath = get_dlpath($db);
    $user_dlpath = $dlpath . NZB_PATH . $username . DIRECTORY_SEPARATOR;
    add_dir_separator($user_dlpath);
    if (!is_dir($user_dlpath)) { // if the user specific dir does not exist, create it
        $rv = mkdir($user_dlpath, 0775, TRUE);
        if ($rv === FALSE) {
            write_log("Could not create directory $user_dlpath", LOG_ERR);

            return;
        }
    }
    if (!is_writeable($user_dlpath)) {
        $rv = @chmod($user_dlpath, 0775); // sometimes mkdir doesn't set the perms correctly (due to umask??), make sure it is set correctly now
        if ($rv === FALSE) {
            write_log("Can't chmod directory: $user_dlpath", LOG_ERR);

            return;
        }
    }

    $to = find_unique_name($user_dlpath, '', $basename, $ext, TRUE);
    $rv = rename($from, $to);
    if ($rv === FALSE) {
        write_log("Could not move directory $to", LOG_ERR);
    } else {
        if (!is_null($dlid)) {
            set_download_destination($db, $dlid, $to);
        }
    }

    return $to;
}


function move_download_to_done(DatabaseConnection $db, action $item, $dlid, $userid, $type = 'DOWNLOAD')
{
    assert(is_numeric($dlid) && is_numeric($userid));
    $preview = $item->get_preview();
    $dlname = get_download_name($db, $dlid);
    $groupid = get_groupid_dlinfo($db, $dlid);
    $username = get_username($db, $userid);
    list($user_dlpath, $dlpath, $add_setname) = get_user_dlpath($db, $preview, $groupid, USERSETTYPE_GROUP, $userid, $dlname, $type = 'DOWNLOAD');
    if (!$preview) {
        list($dl_dir, $add_setname) = get_dl_dir($db, $dlid);
    } else {
        $dl_dir = '';
        $add_setname = 1;
    }
    if ($dl_dir != '') {
        $base_dlpath = get_dlpath($db);
        $base_dlpath = $base_dlpath . DONE_PATH . $username . DIRECTORY_SEPARATOR;
        $user_dlpath = $base_dlpath . $dl_dir;
    }
    $from = get_download_destination($db, $dlid);
    if (!is_dir($user_dlpath)) { // if the user specific dir does not exist, create it
        $rv = mkdir($user_dlpath, 0775, TRUE);
        if ($rv === FALSE) {
            write_log("Could not create directory $user_dlpath", LOG_ERR);

            return;
        }
    }

    if (!is_writeable($user_dlpath)) {
        $rv = @chmod($user_dlpath, 0775); // sometimes mkdir doesn't set the perms correctly (due to umask??), make sure it is set correctly now
        if ($rv === FALSE) {
            write_log("Can't chmod directory: $user_dlpath", LOG_ERR);

            return;
        }
    }

    if (!$add_setname) {
        $rv = TRUE;
        foreach (glob("$from*", GLOB_NOSORT) as $a_file) {
            echo_debug("Moving $from* to $user_dlpath*", DEBUG_SERVER);
            $rv = rename($a_file, $user_dlpath . DIRECTORY_SEPARATOR . basename($a_file)) && $rv;
        }
        $to = $user_dlpath;
    } else {
        $to = find_unique_name($user_dlpath, '', $dlpath);
        echo_debug("Moving $from to $to", DEBUG_SERVER);
        $rv = rename($from, $to);
    }
    if ($rv === FALSE) {
        write_log('Could not move directory', LOG_ERR);
    } else {
        set_download_destination($db, $dlid, $to);
    }
}


function get_timeout(action $item)
{ // in minutes;
    $cnt = $item->get_counter();
    if ($cnt < 100) {
        return 1;
    } elseif ($cnt > 100) {
        return 2;
    } elseif ($cnt > 500) {
        return 3;
    } elseif ($cnt > 1000) {
        return 5;
    } elseif ($cnt > 2500) {
        return 10;
    } elseif ($cnt > 5000) {
        return 30;
    }
}


function reschedule_locked_item(DatabaseConnection $db, server_data &$servers, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    try {
        echo_debug('Dl still locked, pausing', DEBUG_SERVER);
        $command = $item->get_command();
        $args = $item->get_args();
        $item->pause(TRUE, user_status::SUPER_USERID);
        $servers->queue_push($db, $item, FALSE);
        $item_unpause = new action (urdd_protocol::COMMAND_CONTINUE, "$command $args", $item->get_userid(), TRUE);
        $offset = $item->get_preview() ? DatabaseConnection::DB_LOCK_TIMEOUT_PREVIEW : DatabaseConnection::DB_LOCK_TIMEOUT_DEFAULT;
        $job = new job($item_unpause, time() + $offset, NULL); //try again in XX secs
        $servers->add_schedule($db, $job);
    } catch (exception $e) {
        echo_debug_trace($e, DEBUG_SERVER);
        throw $e;
    }
}


function schedule_locked_item(DatabaseConnection $db, server_data &$servers, action $item)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    echo_debug('Dl still locked, pausing', DEBUG_SERVER);
    $command = $item->get_command();
    $args = $item->get_args();
    $item->pause(TRUE, user_status::SUPER_USERID);
    $item_unpause = new action (urdd_protocol::COMMAND_CONTINUE, "$command $args", $item->get_userid(), TRUE);
    $offset = $item->get_preview() ? DatabaseConnection::DB_LOCK_TIMEOUT_PREVIEW : DatabaseConnection::DB_LOCK_TIMEOUT_DEFAULT;
    $job = new job($item_unpause, time() + $offset, NULL); //try again in 30 secs
    $servers->add_schedule($db, $job);
}


function urdd_kill($pid, $signal)
{
    $r = posix_kill($pid, $signal);
    if ($r === FALSE) {
        $ec = posix_get_last_error();
        $msg = '';
        if ($ec != 0) {
            $msg = posix_strerror($ec);
        }
        throw new exception('Kill failed: ' . $msg);
    }
}

function update_thread_status(DatabaseConnection $db, action $item, $dl_status, $post_status)
{
    $cmd = $item->get_command();
    $dlid = $item->get_args();
    if (compare_command($cmd, urdd_protocol::COMMAND_DOWNLOAD) || compare_command($cmd, urdd_protocol::COMMAND_DOWNLOAD_ACTION)) {
        update_dlinfo_status($db, $dl_status, $dlid);
    } elseif (compare_command($cmd, urdd_protocol::COMMAND_START_POST) || compare_command($cmd, urdd_protocol::COMMAND_POST_ACTION) || compare_command($cmd, urdd_protocol::COMMAND_POST)) {
        update_postinfo_status($db, $post_status, $dlid);
    }
}

function load_whitelist(DatabaseConnection $db, $source = NULL)
{
    $Qsource = '';
    $input_arr = array();
    if ($source !== NULL) {
        $Qsource = ' AND "source" = ?';
        $input_arr[] = $source;
    }
    $sql = "\"spotter_id\" FROM spot_whitelist WHERE 1=1 $Qsource";
    $res = $db->select_query($sql, $input_arr);
    if ($res === FALSE) {
        return array();
    }
    $whitelist = array();
    foreach ($res as $row) {
        $whitelist[$row['spotter_id']] = 0;
    }

    return $whitelist;
}

function yenc_decode($string)
{
    $encoded = array();

    // Extract the yEnc string itself.
    preg_match("/^(=ybegin.*=yend[^$]*)$/ims", $string, $encoded);
    if (!isset($encoded[1])) {
        return FALSE;
    }

    $encoded = $encoded[1];

    // Remove the header and trailer from the string before parsing it.
    $encoded = preg_replace("/(^=ybegin.*\\n)/im", '', $encoded, 1);
    $encoded = preg_replace("/(^=ypart.*\\n)/im", '', $encoded, 1);
    $encoded = preg_replace("/(^=yend.*)/im", '', $encoded, 1);

    // Remove linebreaks from the string.
    $encoded = trim(str_replace(array("\n", "\r"), '', $encoded));

    // Decode
    $decoded = '';
    $l = strlen($encoded);
    for ( $i = (int) 0; $i < $l; ++$i) {
        if ($encoded[$i] == '=') {
            ++$i;
            $decoded .= chr((ord($encoded[$i]) - 64) - 42);
        } else {
            $decoded .= chr(ord($encoded[$i]) - 42);
        }
    }

    return $decoded;
}

function cleanup_download_articles(DatabaseConnection $db, $dlid)
{
    assert(is_numeric($dlid));
    delete_download_article($db, $dlid, DOWNLOAD_COMPLETE);
    delete_download_article($db, $dlid, DOWNLOAD_FAILED);
}

function delete_download_article(DatabaseConnection $db, $dlid, $status)
{
    assert(is_numeric($dlid));
    $db->delete_query('downloadarticles', '"downloadID"=? AND "status"=?', array($dlid, $status));
}

function shutdown_urdd(DatabaseConnection $db, server_data &$servers)
{
    write_log('Shutting down urdd.', LOG_NOTICE);
    set_config($db, 'urdd_startup', '0');
    do_cancel_all($db, $servers, user_status::SUPER_USERID, TRUE);
    urdd_exit(NO_ERROR);
}

function restart_urdd(DatabaseConnection $db, server_data &$servers)
{
    write_log('Restarting urdd.', LOG_NOTICE);
    do_cancel_all($db, $servers, user_status::SUPER_USERID, TRUE);
    posix_kill(posix_getpid(), SIGUSR1);
}

function check_deprecated_db()
{
    global $db;
    $dbtype = $db->get_databasetype(); 
    switch($dbtype) { 
        case 'pdo_mysql':
        case 'mysqli':
            write_log("Database type {$dbtype} is deprecated. Please change the setting \$config['databasetype'] to mysql in dbconfig.php", LOG_WARNING);
            break;
        case 'pdo_pgsql':
        case 'postgres9':
        case 'postgres8':
        case 'postgres7':
            write_log("Database type {$dbtype} is deprecated. Please change the setting \$config['databasetype'] to postgres in dbconfig.php", LOG_WARNING);
            break;
        case 'pdo_sqlite':
            write_log("Database type {$dbtype} is deprecated. Please change the setting \$config['databasetype'] to sqlite in dbconfig.php", LOG_WARNING);
            break;
    }
}

