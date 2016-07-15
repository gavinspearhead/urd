<?php
/**
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
 * $LastChangedDate: 2014-05-29 01:03:02 +0200 (do, 29 mei 2014) $
 * $Rev: 3058 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: debug.php 3058 2014-05-28 23:03:02Z gavinspearhead@gmail.com $
 */


define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathab = realpath(dirname(__FILE__));

require_once "$pathab/../functions/html_includes.php";

function filter_secret_data($arr)
{
    foreach ($arr as $k => $p) {
        if (is_array($p)) { 
            $arr[$k] = filter_secret_data($p);
        }
        if (strstr($k, 'password') || strstr($k, 'privatekey'))
            $arr[$k] = 'xxxxxx';
    }
    return $arr;
}

function print_perms($perms)
{
    if (($perms & 0xC000) == 0xC000) {
        // Socket
        $info = 's';
    } elseif (($perms & 0xA000) == 0xA000) {
        // Symbolic Link
        $info = 'l';
    } elseif (($perms & 0x8000) == 0x8000) {
        // Regular
        $info = '-';
    } elseif (($perms & 0x6000) == 0x6000) {
        // Block special
        $info = 'b';
    } elseif (($perms & 0x4000) == 0x4000) {
        // Directory
        $info = 'd';
    } elseif (($perms & 0x2000) == 0x2000) {
        // Character special
        $info = 'c';
    } elseif (($perms & 0x1000) == 0x1000) {
        // FIFO pipe
        $info = 'p';
    } else {
        // Unknown
        $info = 'u';
    }

    // Owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ?
        (($perms & 0x0800) ? 's' : 'x' ) :
        (($perms & 0x0800) ? 'S' : '-'));

    // Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ?
        (($perms & 0x0400) ? 's' : 'x' ) :
        (($perms & 0x0400) ? 'S' : '-'));

    // World
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ?
        (($perms & 0x0200) ? 't' : 'x' ) :
        (($perms & 0x0200) ? 'T' : '-'));

    return $info;
}

function debug_dump_str(array $var, array $highlights=array())
{
    foreach ($var as $line) {
        $line = htmlentities($line);
        foreach ($highlights as $h) {
            $line = str_ireplace($h[0], '<font color="' . $h[1]. "\">{$h[0]}</font>", $line);
        }
        echo $line . '<br/>';
    }
    echo '<p>';
}

function _debug_dump_str_key(array $var, array $highlights=array())
{
    foreach ($var as $key => $line) {
        //if (is_object($line)) {echo "AOEUAEUAOEUAOEUA44444";};
        if (is_array($line) ) {
            echo "<tr><td>\n";
            _debug_dump_str_key($line, $highlights);
            echo "</tr>\n";
            echo "<tr><td>&nbsp;</tr>\n";
        } else {
            $line = htmlentities($line);
            foreach ($highlights as $h) {
                $line = str_ireplace($h[0], '<font color="' . $h[1]. "\">{$h[0]}</font>" , $line);
            }
            echo '<tr><td>' . htmlentities($key) . '</td><td>' . $line . "</td>\n";
            echo "</tr>\n";
        }
    }
}

function debug_dump_str_key(array $var, array $highlights=array())
{
    echo "<table>\n";
    _debug_dump_str_key($var, $highlights);
    echo "</table><p>\n";
}

function get_urd_path()
{
    $path = realpath(dirname(__FILE__) . '/..');

    return $path;
}

function debug_dump($var)
{
    echo '<pre>'; // This is for correct handling of newlines
    ob_start();
    var_dump($var);
    $a = ob_get_contents();
    ob_end_clean();
    echo htmlspecialchars($a, ENT_QUOTES); // Escape every HTML special chars (especially > and < )
    echo '</pre>';
}

$default_locations = array (
    '/usr/bin/',
    '/usr/local/bin/',
    '/bin/',
    '/sbin',
    '/usr/sbin',
    '/usr/local/sbin/',
    '/opt/bin',
    './' // current directory ??
// more options? For Mac?
    );

function check_locations($file, &$found_path)
{
    global $default_locations;
    foreach ($default_locations as $l) {
        $f = $l . $file;
        if (file_exists ($f) && is_executable($f)) {
            $found_path = $f;

            return TRUE;
        }
    }
    $found_path = '';

    return FALSE;
}

function get_svn_rev()
{
    $v = exec ('which svnversion', $foo, $rv);
    if ($rv == 0) {
        $svnversion_path = $v;
        $rv = TRUE;
    }
    if (!$rv) { 
        return FALSE; 
    }
    $v = exec($svnversion_path, $foo, $rv);
    if (!isset($foo[1])) { 
        return FALSE; 
    }
    return $foo[1];
}

function get_svn_latest_rev()
{
    $v = exec ('which svn', $foo, $rv);
    if ($rv != 0) {
        $rv = check_locations('svn', $svn_path);
    } else {
        $svn_path = $v;
        $rv = TRUE;
    }
    if (!$rv) return FALSE;
    $v = exec("$svn_path info -r HEAD | grep Revision ", $foo, $rv);
    foreach ($foo as $line) {
        if (preg_match('/Revision: (\d+)/', $line, $matches)) {
            return $matches[1];
        }
    }

    return '';
}

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">';
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
echo '<head><title>URD - Debug output</title>';
echo '<meta http-equiv="Content-Language" content="en-us"/>';
echo '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>';
echo '<meta name="resource-type" content="document"/>';
echo "<style type=\"text/css\" >\n";
echo 'body';
echo '{';
echo '	background-color: black;';
echo '	color: white;';
echo '	margin: 0px;';
echo '  padding: 0px;';
echo '	font-family: Geneva, Arial, Helvetica, sans-serif;';
echo '   font-size: small;';
echo '}';

echo "h1,.h1 { font-family: Verdana; font-size: 14pt; font-weight: bold;  } h2,.h2 { font-family: Verdana; font-size: 12pt; font-weight: bold; } h3,.h3 { font-family: Verdana; font-size: 10pt; font-weight: bold; }";

echo "</style></head>\n";

echo "<body>\n";
echo "<h1>URD - Debug output</h1>\n";

echo "<br/>--------------- copy from here ----------------<br/>\n\n";

echo "<h2>PHP Settings</h2>\n";

$prefs = load_config($db);

$settings = array();

$settings['System name'] = implode(' ', posix_uname());
$settings['PHP_version'] = phpversion() . ' (should be at least 5.1.2)';
$settings['Loaded php.ini file'] = php_ini_loaded_file();
$settings['Browser agent'] = $_SERVER['HTTP_USER_AGENT'];
$settings['database type'] = $db->get_databasetype();
$db_srv_version = $db->get_database_server_version();
$db_srv_driver = $db->get_database_server_driver();
$settings['database driver'] = $db_srv_driver;
$settings['database version'] = $db_srv_version;
$settings['URD Database version'] = $prefs['db_version'];
$settings['Smarty version'] = Smarty::SMARTY_VERSION;

debug_dump_str_key($settings);
$settings = array();
$s = get_svn_rev();
$sl = get_svn_latest_rev();
if ($s != '') {
    $settings['URD svn revision'] = $s;
    $settings['URD latest revision'] = $sl;
    $highlight = array(array($s, 'red'), array($sl, 'red'));
} else {
    $settings['URD svn revision'] = 'No svn used';
    $highlight = array();
}
debug_dump_str_key($settings, $highlight);

$settings = array();

$settings['Safe_mode'] =  (ini_get('safe_mode') ? "<font color='red'>on</font>":"off" ). " (should be off)";
$settings['Magic_quotes_gpc'] =   (get_magic_quotes_gpc() ? "<font color='red'>on</font>":"off" ). " (should be off)\n";
$settings['Safe_mode_gid'] =  (ini_get('safe_mode_gid') ? "<font color='red'>on</font>":"off" ). " (should be off)\n";
$settings['Register_globals'] =  (ini_get('register_globals')? "<font color='red'>on</font>":"off") . " (should be off)\n";
debug_dump_str_key($settings, array(array('on', 'red'), array('off', 'green')));
$settings = array();
$settings['File_uploads'] =  (ini_get('file_uploads') ? "on":"off" ). " (should be on)\n";
$settings['Allow_url_fopen'] =  (ini_get('allow_url_fopen') ? "on":"off" ) . " (should be on)\n";
debug_dump_str_key($settings, array(array('on', 'green'), array('off', 'red')));
$settings = array();
$settings['Post_max_size'] =  ini_get('post_max_size') . " (Should be 2M or more)\n";
$settings['Upload_max_filesize'] =  ini_get('upload_max_filesize') . " (should be 2M or more)\n";
$settings['Memory_limit'] =  ini_get('memory_limit') . " (should be 128M or more)\n";
debug_dump_str_key($settings);
$settings = array();
debug_dump_str_key($settings, array(array('on', 'green'), array('off', 'red')));

$settings = array();
$settings['date.timezone'] =  (ini_get('date.timezone') ) . " (should not be empty)\n";
debug_dump_str_key($settings, array(array('', 'green'), array('off', 'red')));
$settings = array();
echo "<p>These settings may cause trouble; empty values should always work tho:</p>\n";
$settings['Open_basedir'] =  "'" .ini_get('open_basedir') . "'" ." \n";
$settings['Disable_functions'] = "'" . ini_get('disable_functions') ."'" . "\n";
$settings['Disable_classes'] = "'" . ini_get('disable_classes') ."'" . "\n";

debug_dump_str_key($settings);
$settings = array();
echo "<h2>Some basic system settings useful for debugging download directory problems</h2>";

$apache_info = posix_getpwuid(posix_geteuid());
$a_gid = posix_getgrgid(posix_getegid());
$a_userid = $apache_info['name'] . ' (' . $apache_info['uid'] . ')';
$a_groupid = $a_gid['name'] . ' (' . $apache_info['gid'] . ')';
$settings['Apache userid'] = $a_userid;
$settings['Apache groupid'] = $a_groupid;
$settings['URD Install path'] = get_urd_path();

$settings['download_dir'] = get_dlpath($db);
if (trim($settings['download_dir']) == '') {
    echo "<font color='red'>ERROR: DL path should not be empty! Please set in admin/config</font>";
}
$perms = fileperms($settings['download_dir']);
$settings['dl_dir_rights'] = print_perms($perms) . ' (' . substr(sprintf('%o', $perms), -4) . ')';
$settings['URDD groups setting'] = $prefs['group'];
$settings['URDD permissions setting'] = $prefs['permissions'];

debug_dump_str_key($settings);
echo "<h2>To fix problems to do with download directory settings</h2>";
echo <<<DLSOLV
<pre>
On the command line type:
# sudo chgrp -R {$a_gid['name']} '{$settings['download_dir']}'
# sudo chmod -R g+rwx '{$settings['download_dir']}'
<p>
Neither of the commands should give any errors.
Restart URDD. In case you still run in to trouble re-run the commands again after starting URDD.
Also double check the setting value for group in admin/config ("URDD groups setting" above) and the
permissions setting in admin/config ("URDD permissions setting" above).

These values should be either blank or
group = {$a_gid['name']}
permissions = 0664
</pre>
DLSOLV;

echo "<h2>Debug output of URD settings</h2>\n\n";

$uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);

if ($isadmin) {
    echo "<h3>Global configuration</h3>\n";
    $prefs = filter_secret_data($prefs);
    ksort($prefs);
    debug_dump_str_key($prefs);
}

echo "<h3>User preferences</h3>\n";
$uprefs = load_prefs($db, $userid);
$uprefs = filter_secret_data($uprefs);
ksort($uprefs);
debug_dump_str_key($uprefs);

echo "<h3>Users</h3>\n";
$users = get_all_users_full($db);

$u_data = array();
foreach ($users as $k => $u) {
    $u_data[ $k . ':name'   ] = $u['name'];
    $u_data[ $k . ':active'   ] = $u['active'];
    $u_data[ $k . ':isadmin'   ] = $u['isadmin'];
    $u_data[ $k . ':rights'   ] = $u['rights'];
}

ksort($u_data);
debug_dump_str_key($u_data);

echo "<h3>Usenet Servers</h3>\n";
$u_servers = get_all_usenet_servers($db, FALSE);
foreach ($u_servers as $k => $p) {
    foreach ($p as $k1 => $p1) {
        if (strstr($k1, 'password') && $p1 != '') {
            $u_servers[$k][$k1] = 'xxxxxx';
        }
    }
}

ksort($u_servers);
debug_dump_str_key($u_servers);

try {
    if ($uc->is_connected()) {
        echo "<h3>URDD startup test results</h3>\n";

        $tests = ($uc->show('tests'));
        $tests2 = array();
        foreach ($tests as $t) {
            $tmp = explode(':  ', $t, 2);
            if (!isset($tmp[0]) || !isset($tmp[1])) {
                continue;
            }
            $tests2["{$tmp[0]}"] = $tmp[1];
        }

        debug_dump_str_key($tests2, array (array('Succeeded', 'green'), array('Failed', 'red')));

        echo "<h3>URDD settings: Daemon online</h3>\n";
        $urdd_status = $uc->show('status');
        if ($urdd_status !== FALSE) {
            echo "<h3>URDD Status</h3>\n";
            debug_dump_str($urdd_status);
        } else {
            echo "<h3>URDD status not read: No response received</h3>\n";
        }

        $urdd_cfg = $uc->show('config');
        if ($urdd_cfg !== FALSE) {
            echo "<h3>URDD settings</h3>\n";
            debug_dump_str($urdd_cfg);
        } else {
            echo "<h3>URDD settings not read: No response received</h3>\n";
        }
        $urdd_vrs = $uc->version();
        if ($urdd_vrs !== FALSE) {
            echo "<h3>URDD version</h3>\n";
            debug_dump_str($urdd_vrs);
        } else {
            echo "<h3>URDD version not read: No response received</h3>\n";
        }
        $urdd_all = $uc->show('all');
        if ($urdd_all !== FALSE) {
            echo "<h3>URDD jobs, queue, threads, users</h3>\n";
            debug_dump_str($urdd_all);
        } else {
            echo "<h3>URDD settings not read: No response received</h3>\n";
        }
    } else {
        echo "<h3>URDD settings not read: Daemon offline</h3>\n";
    }
} catch (exception $e) {
    echo "<h3>URDD settings not read: Exception occurred</h3>\n\n";
}

echo "<h3>Session info</h3>\n";
$session_data = filter_secret_data($_SESSION);
sort($session_data);
debug_dump_str_key($session_data);

echo "<h3>Smarty test</h3>\n";
init_smarty(0);
$smarty->testInstall();

echo "<br/>--------------- copy to here ----------------<br/>\n";
echo "</body>\n";
