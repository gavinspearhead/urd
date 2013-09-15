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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: install.funcs.inc.php 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 */

// Checking functions (in functions to keep main code tidy)
// Remember, true or 1 = OK!!

// we search these locations if 'which' doesn't help
$default_locations = array (
	'/usr/bin/',
	'/usr/local/bin/',	
	'/bin/',
	'/sbin',
	'/usr/sbin/',
	'/usr/local/sbin/',
    '/opt/bin/',
    './' // current directory ??
// more options? For Mac?
	);


function CheckPHPVersion()
{
	return version_compare(PHP_VERSION, MIN_PHP_VERSION, '>=');
}


function CheckPHPVersion_rec()
{
	return version_compare(PHP_VERSION, RECOMMENDED_PHP_VERSION, '>=');
}


function check_timezone($cli, $php_path=NULL)
{
    if ($cli) {
        if ($php_path == NULL) {
            $php_path = 'php';
        }

        exec("$php_path -r " . '"echo ini_get(\"date.timezone\");"', $output);

        if (!is_array($output)) {
            return FALSE;
        }
        $tmp = trim($output[0]);
    } else {
        $tmp = ini_get('date.timezone');
    }
    if ($tmp == '') {
        return FALSE;
    } else {
        return TRUE;
    }
}


function CheckPHPExec()
{
	$tmp = ini_get('disable_functions');
	if (!is_array($tmp)) $tmp = array($tmp);

	foreach ($tmp as $fn)
		if ($fn == 'exec') 
			return FALSE;

	return TRUE;
}



function CheckPHPSafeMode()
{
	$tmp = ini_get('safe_mode');
	if ($tmp == 'on') {
        return FALSE;
    }
	return TRUE;
}

function get_php_ini_path($cli, $php_path=NULL)
{
    if ($cli) {
        if ($php_path == NULL) $php_path = 'php';
        exec("$php_path -r 'echo php_ini_loaded_file();'",$output);
        return $output[0];
    } else {
        return php_ini_loaded_file();
    }
}


function check_disabled_classes($php_path)
{
    if ($php_path == NULL) $php_path = 'php';
	exec("$php_path -r " . '"echo ini_get(\"disable_classes\") . \"\n\";"', $output);
	if (!is_array($output)) 
        return FALSE;
    $tmp = trim($output[0]);
	if ($tmp != '') {
        return FALSE;
    }
	return TRUE;
}


function check_disabled_functions($php_path)
{
    if ($php_path == NULL) $php_path = 'php';
	exec("$php_path -r " . '"echo ini_get(\"disable_functions\") . \"\n\";"', $output);
	if (!is_array($output)) {
        return FALSE;
    }
    $tmp = trim($output[0]);
	if ($tmp != '') {
        return FALSE;
    }
	return TRUE;
}


function check_open_basedir($php_path)
{
    if ($php_path == NULL) $php_path = 'php';
	exec("$php_path -r " . '"echo ini_get(\"open_basedir\") . \"\n\";"', $output);
	if (!is_array($output)) {
        return FALSE;
    }
    $tmp = trim($output[0]);
	if ($tmp != '') {
        return FALSE;
    } 
    
    $obd = ini_get("open_basedir");
    if ($obd != '') { 
        return FALSE;
    }

	return TRUE;
}


function CheckPHPMemory($php_path)
{
	$output = '';
	exec("$php_path -r " . '"echo ini_get(\"memory_limit\") . \"\n\";"', $output);
	if (!is_array($output)) 
		return FALSE;
	$mem = $output[0];
	// We expect it to be in MB. (M).
	$mem = str_replace('M','',$mem);
	if ($mem >= 128) 
        return TRUE;
    if ($mem < 0) 
        return TRUE;
	return FALSE;
}


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


function CheckPHPCLI(&$php_path)
{
	$v = exec ('which php', $foo, $rv);
	if ($rv != 0) 
		return check_locations('php', $php_path);
	else 
		$php_path = $v;
	return TRUE;
}


function CheckPHPRegGlobals()
{
	$tmp = ini_get('register_globals');
	if ($tmp) return FALSE;
	return TRUE;
}



function CheckPHPOpenssl($php_path)
{
	if ($php_path == NULL) $php_path = 'php';
	exec("$php_path -m",$output);
	if (is_array($output))
		foreach ($output as $row)
			if ($row == 'openssl') return TRUE;

	return FALSE;
}

function CheckPHPgd()
{
	return extension_loaded('gd');
}

function CheckPHPposix($php_path)
{
	if ($php_path == NULL) $php_path = 'php';
	exec("$php_path -m", $output);
	if (is_array($output))
		foreach ($output as $row)
			if ($row == 'posix') return TRUE;

	return FALSE;
}


function CheckPHPsockets($php_path)
{
	if ($php_path == NULL) $php_path = 'php';
	exec("$php_path -m",$output);
	if (is_array($output))
		foreach ($output as $row)
			if ($row == 'sockets') return true;

	return FALSE;
}


function CheckPHPSPL($php_path)
{
	if ($php_path == NULL) $php_path = 'php';
	exec("$php_path -m",$output);
	if (is_array($output))
		foreach ($output as $row)
			if ($row == 'SPL') return TRUE;

	return FALSE;
}


function CheckPHPpcntl($php_path)
{
	if ($php_path == NULL) $php_path = 'php';
	exec("$php_path -m",$output);
	if (is_array($output))
		foreach ($output as $row)
			if ($row == 'pcntl') return TRUE;

	return FALSE;
}


function CheckPHPXMLRPC()
{
	return extension_loaded('xmlrpc');
}

function CheckPHPXMLRW()
{
	return extension_loaded('xmlreader') && extension_loaded('xmlwriter');
}

function CheckPHPGMP()
{
	return extension_loaded('gmp');
}


function CheckPHPCURL()
{
	return extension_loaded('curl');
}	


function CheckAdodb()
{
	@include_once '../functions/libs/adodb/adodb-exceptions.inc.php';
	@include_once '../functions/libs/adodb/adodb.inc.php';
	if (class_exists('ADODB_Exception') && class_exists('ADOConnection')) {
        return TRUE;
    }
	return FALSE;
}


function CheckYydecode()
{
	exec('which yydecode', $foo, $rv);
	if ($rv != 0) {
		return check_locations('yydecode', $_SESSION['yydecode']);
    } else {
		// check for version? is it the right tool anyway?
		$_SESSION['yydecode'] = isset($foo[0]) ? $foo[0] : '';
		return TRUE;
	}
}


function CheckSubDl()
{
	exec('which subdownloader', $foo, $rv);
	if ($rv != 0) {
		return check_locations('subdownloader', $_SESSION['subdownloader']);
	} else {
		$_SESSION['subdownloader'] = isset($foo[0]) ? $foo[0] : '';
		return TRUE;
	}
}


function CheckPar2()
{
	exec ('which par2', $foo, $rv);
	if ($rv != 0) {
		return check_locations('par2', $_SESSION['par2']);
	} else {
		$_SESSION['par2'] = isset($foo[0]) ? $foo[0] : '';
		return TRUE;
	}
}


function CheckUnRar()
{
	exec ('which unrar', $foo1, $rv1);
	exec ('which rar', $foo2, $rv2);
	if ($rv2 == 0 && isset($foo2[0])  ) {
		$_SESSION['unrar'] = $foo2[0];
		return TRUE;
	} elseif ($rv1 == 0 && isset($foo1[0])) {
		$_SESSION['unrar'] = $foo1[0];
		return TRUE;
	} else {
		if (!check_locations('rar', $_SESSION['unrar']))
			return check_locations('unrar', $_SESSION['unrar']);
		return TRUE;
	}
}


function CheckRar()
{
	exec ('which rar', $foo2, $rv2);
	if ($rv2 == 0 && isset($foo2[0])  ) {
		$_SESSION['rar'] = $foo2[0];
		return TRUE;
	} else {
		return check_locations('rar', $_SESSION['unrar']);
	}
}

function CheckTar()
{
	exec ('which tar', $foo, $rv);
	if ($rv != 0) {
		return check_locations('tar', $_SESSION['tar']);
	} else {
		$_SESSION['tar'] = isset($foo[0]) ? $foo[0] : '';
		return TRUE;
	}
}


function CheckTrickle()
{
	exec ('which trickle', $foo, $rv);
	if ($rv != 0) {
		return check_locations('trickle', $_SESSION['trickle']);
	} else {
		$_SESSION['trickle'] = isset($foo[0]) ? $foo[0] : '';
		return TRUE;
	}
}


function CheckCksfv()
{
	exec ('which cfv', $foo1, $rv1);
	exec ('which cksfv', $foo2, $rv2);
	if ($rv1 != 0 && $rv2 != 0) {
		if (!check_locations('cfv', $_SESSION['cksfv']))
			return check_locations('cksfv', $_SESSION['cksfv']);
		return TRUE;
	} elseif ($rv1 == 0 && isset($foo1[0])) {
		$_SESSION['cksfv'] = $foo1[0];
		return TRUE;
	} elseif ($rv2 == 0 && isset($foo2[0])){
		$_SESSION['cksfv'] =  $foo2[0];
		return TRUE;
	} else
		return FALSE;
}


function Check7zip()
{
	exec ('which 7za', $foo1, $rv1); // first try 7za as 7zr does not (always??) work
	exec ('which 7zr', $foo2, $rv2); // if we can't find 7za, try 7zr anyway
	exec ('which 7z', $foo3, $rv3); // if we can't find 7za, 7zr, maybe 7z is installed - try that one 
	
	if ($rv1 == 0 && isset($foo1[0]) )  {
		$_SESSION['7zip'] = $foo1[0] ;
		return TRUE;
	} elseif ($rv2 == 0 && isset($foo2[0]) ) {
		$_SESSION['7zip'] = $foo2[0] ;
		return TRUE;
	} elseif ($rv3 == 0 &&  isset($foo3[0])) {
		$_SESSION['7zip'] = $foo3[0] ;
		return TRUE;
	} else {
		$_SESSION['7zip'] = '';
		if (check_locations('7za', $_SESSION['7zip']))
			return TRUE;
		elseif (check_locations('7zr', $_SESSION['7zip']))
			return TRUE;
		else 
			return check_locations('7z', $_SESSION['7zip']);
	}
}


function CheckGzip()
{
	exec ('which gzip', $foo, $rv);
	if ($rv != 0) {
		return check_locations('gzip', $_SESSION['gzip']);
	} else {
		$_SESSION['gzip'] = isset($foo[0]) ? $foo[0] : '';
		return TRUE;
	}
}


function CheckZip()
{
	exec ('which unzip', $foo, $rv);
	if ($rv != 0) {
		return check_locations('unzip', $_SESSION['unzip']);
	} else {
		$_SESSION['unzip'] = isset($foo[0]) ? $foo[0] : '';
		return TRUE;
	}
}


function CheckFile()
{
	exec ('which file', $foo, $rv);
	if ($rv != 0) {
		return check_locations('file', $_SESSION['file']);
	} else {
		$_SESSION['file'] = isset($foo[0]) ? $foo[0] : '';
		return TRUE;
	}
}


function CheckYencode()
{
	exec ('which yencode', $foo, $rv);
	if ($rv != 0) {
		return check_locations('yencode', $_SESSION['yyencode']);
	} else {
		$_SESSION['yyencode'] = isset($foo[0]) ? $foo[0] : '';
		return TRUE;
	}
}


function CheckAce()
{
	exec ('which unace', $foo, $rv);
	if ($rv != 0) {
		return check_locations('unace', $_SESSION['unace']);
	} else {
		$_SESSION['unace'] = isset($foo[0]) ? $foo[0] : '';
		return TRUE;
	}
}


function CheckArj()
{
    exec ('which arj', $foo, $rv);
    exec ('which unarj', $foo2, $rv2);
    if ($rv == 0) {
        $_SESSION['unarj'] = isset($foo[0]) ? $foo[0] : '';
        return TRUE;
    } if ($rv2 == 0) {
        $_SESSION['unarj'] = isset($foo2[0]) ? $foo2[0] : '';
        return TRUE;
    } else {
        if (check_locations('arj', $_SESSION['unarj'])) {
            return TRUE;
        } else {
            return check_locations('unarj', $_SESSION['unarj']);
        }
    }
}


function WriteDBConfig($dbtype,$dbuser,$dbpass,$dbname,$dbhost, $dbport)
{
	$file = fopen('../dbconfig.php', 'w');
    if (!$file) {
        throw new exception("Couldn't create dbconfig.php file");
    }

    $dbname = str_replace('\'', '\\\'', $dbname);
    $dbuser = str_replace('\'', '\\\'', $dbuser);
    $dbpass = str_replace('\'', '\\\'', $dbpass);
    $dbhost = str_replace('\'', '\\\'', $dbhost);
    $dbport = str_replace('\'', '\\\'', $dbport);
    $dbengine = str_replace('\'', '\\\'', $dbengine);

	$CONTENT = <<<CONT
<?php
	\$config['databasetype'] = '$dbtype';
	\$config['database'] = '$dbname';
	\$config['db_user'] = '$dbuser';
	\$config['db_password'] = '$dbpass';
	\$config['db_hostname'] = '$dbhost';
	\$config['db_port'] = '$dbport';
	\$config['db_engine'] = '$dbengine';

CONT;
	fwrite($file, $CONTENT);
	fclose($file);
}



function create_database_structure($database_type)
{
	switch (strtolower($database_type)) {
		case 'postgres8':
		case 'postgres7':
			$file = 'URD_pgsql_db.sql';
			break;
		case 'mysql':
		case 'mysqli':
		case 'mysqlt':
			$file = 'URD_mysql_db.sql';
			break;
        case 'pdo_sqlite':
            $file = 'URD_sqlite_db.sql';
            break;
		case 'postgres':
		case 'postgres64':
		default :
			throw new exception ("Database type {$database_type} not yet supported");
			break;
	}
	$content = file_get_contents($file);
	if ($content === FALSE) 
		throw new exception("URD SQL file not found.");

	// Strip all comments:
	$lines = explode("\n",$content);
	$newlines = '';
	foreach ($lines as $line) {
		if (strpos($line,'--')!==0 && trim($line)!='') 
			$newlines .= $line . ' ';
	}
	$commands = explode(';',$newlines);
	array_pop($commands); // Removes the last empty line
	return $commands;
}


// Simple function to generate 'OK' or 'Failed':
function GenRetVal($ok, &$rv='')
{
	$ans = 'Failed';
	$rv = $ok;
    if ($ok === TRUE) {
        $ans = 'OK';
    }
	return "<td class=\"install_$ans\">$ans</td></tr>\n";
}


// Help:
function ShowHelp($mesg)
{
	return "<tr><td colspan=\"2\"><span class=\"info\">$mesg</span></td></tr>\n";
}


function get_url()
{
	$url = 'http';
	if (isset ($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $url .= 's';
    }
	$url .= '://';
	$url .= $_SERVER['SERVER_NAME'];
	if ($_SERVER['SERVER_PORT'] != '80') 
		$url .= ':'.$_SERVER['SERVER_PORT'];
	$url .= $_SERVER['REQUEST_URI'];
	$url = preg_replace('/install\/install\.php/', '', $url);
	$_SESSION['url'] = $url;
	return $url;
}


function get_urdd_path()
{
	$path = realpath(dirname(__FILE__) . '/..');
	$urdd = $path . '/urdd.sh';
	if (file_exists($urdd)) {
		$_SESSION['urdd'] = $urdd;
		return $urdd;
	} else {
        return '';
    }
}


