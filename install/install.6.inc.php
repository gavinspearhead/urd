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
 * $LastChangedDate: 2014-06-27 23:58:52 +0200 (vr, 27 jun 2014) $
 * $Rev: 3125 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: install.6.inc.php 3125 2014-06-27 21:58:52Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) { die('This file cannot be accessed directly.'); }

// Finish up
$urduser = get_request('urduser');
$urdpass1 = get_request('urdpass1');
$urdpass2 = get_request('urdpass2');
$urdfullname = get_request('urdfullname');
$urdemail = get_request('urdemail');
$urddownloaddir = get_request('urddownloaddir');
$urdupdate = (get_request('urdupdatecheck', FALSE) == FALSE) ? FALSE : TRUE;

// Store in session:
$_SESSION['urdusername'] = $urduser;
$_SESSION['urdusermail'] = $urdemail;
$_SESSION['urduserfull'] = $urdfullname;
$_SESSION['urduserpass1'] = $urdpass1;
$_SESSION['urduserpass2'] = $urdpass2;
$_SESSION['urdupdatecheck'] = $urdupdate;
$_SESSION['urddownloaddir'] = $urddownloaddir;

get_urdd_path();
get_url();

// This SHOULD work now:
$OUT .= '<tr><td class="install2">Connecting to db using stored settings</td>';
try{
	$db = connect_db(FALSE);
	$rv_cdb = TRUE;
} catch (exception $e) {
	$rv_cdb = FALSE;
	$error = $e->getMessage();
}
$OUT .= GenRetVal($rv_cdb);
if ($rv_cdb == FALSE) {
    $OUT .= ShowHelp($error);
}

// Checking user/password:
$OUT .= '<tr><td class="install2">Username valid</td>';
try {
    $rv_un = valid_username($urduser, 2);
} catch (exception $e) {
    $rv_un = FALSE;
	$error = $e->getMessage() . '. ';
} 

$OUT .= GenRetVal($rv_un === TRUE, $rv_uv);
if (!$rv_uv) {
    $OUT .= ShowHelp("{$error} Please enter a username ('root' is unacceptable). Only letters, numbers and underscore is accepted, minimally 3 characters");
}

$OUT .= '<tr><td class="install2">Fullname valid</td>';
$OUT .= GenRetVal($urdfullname != '', $rv_ufn);
if (!$rv_ufn) { $OUT .= ShowHelp('Please enter your full name'); }

$OUT .= '<tr><td class="install2">Email valid</td>';
$OUT .= GenRetVal(verify_email($urdemail), $rv_uem);
if (!$rv_uem) $OUT .= ShowHelp('Please enter a valid email address');

$OUT .= '<tr><td class="install2">Both passwords the same</td>';
$OUT .= GenRetVal($urdpass1 == $urdpass2, $rv_ps);

$OUT .= '<tr><td class="install2">Password acceptable</td>';
$rv_pe = verify_password($urdpass1);
$OUT .= GenRetVal($rv_pe === TRUE,$rv_pex);
if (!$rv_pe) $OUT .= ShowHelp($rv_pe);

$rv_dd = is_dir($urddownloaddir) && is_writable($urddownloaddir);
$OUT .= '<tr><td class="install2">Download directory</td>';
$OUT .= GenRetVal($rv_dd === TRUE,$rv_dd);
if (!$rv_dd) { 
    $OUT .= ShowHelp('Download directory not found or not writeable.');
}

$OUT .= '<tr><td class="install2">Creating URD user account</td>';
try{
	if ($rv_cdb === FALSE) {
		throw new exception('Database connection required.');
    }
	if (!$rv_pex || !$rv_ps || !$rv_uem || !$rv_ufn || !$rv_uv || !$rv_pe || !$rv_dd) {
		throw new exception('Valid data required');
    }
    // Make user with admin rights:
	add_user($db, $urduser, $urdfullname, $urdemail, $urdpass1, user_status::USER_ADMIN, user_status::USER_ACTIVE, 'CRUDPAE');
    //
    // overwrite with settings from the install
    set_config($db, 'run_update', $urduser); // run updategroups at startup as user just created
    set_config($db, 'dlpath', $urddownloaddir);
    set_config($db, 'admin_email', $urdemail);
    set_config($db, 'urdd_path', get_session('urdd'));
    set_config($db, 'url', get_session('url'));
    set_config($db, 'default_language', detect_language() . '.php');

    set_config($db, 'yydecode_path', get_session('yydecode'));
    set_config($db, 'subdownloader_path', get_session('subdownloader'));
    set_config($db, 'unrar_path', get_session('unrar'));
    set_config($db, 'par2_path', get_session('par2'));
    set_config($db, 'rar_path', get_session('rar'));
    set_config($db, 'tar_path', get_session('tar'));
    set_config($db, 'trickle_path', get_session('trickle'));
    set_config($db, 'cksfv_path', get_session('cksfv'));
    set_config($db, '7zip_path', get_session('7zip'));
    set_config($db, 'gzip_path', get_session('gzip'));
    set_config($db, 'unzip_path', get_session('unzip'));
    set_config($db, 'file_path', get_session('file'));
    set_config($db, 'yyencode_path', get_session('yyencode'));
    set_config($db, 'unace_path', get_session('unace'));
    set_config($db, 'unarj_path', get_session('unarj'));

	$rv_cu = TRUE;
} catch (exception $e) {
	$rv_cu = FALSE;
	$error = $e->getMessage();
}
$OUT .= GenRetVal($rv_cu);
if ($rv_cu === FALSE) {
	$OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
}
// schedule version checks:
$rv_uu = TRUE;
if ($urdupdate) {
	$OUT .= '<tr><td class="install2">Scheduling weekly version checks</td>';
	try{
		if ($rv_cdb === FALSE) {
            throw new exception('Database connection required');
        }
		if (!$rv_pex || !$rv_ps || !$rv_uem || !$rv_ufn || !$rv_uv || !$rv_cu || !$rv_pe || !$rv_dd) {
            throw new exception('Valid data required');
        }
        set_config($db, 'period_update', 8);

        $rv_uu = TRUE;
	} catch (exception $e) {
		$rv_uu = FALSE;
		$error = $e->getMessage();
	}
	$OUT .= GenRetVal($rv_uu);
	if ($rv_uu === FALSE) {
        $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
    }
} else {
    set_config($db, 'period_update', 0);
}
$userid = get_userid($db, $urduser);
if (is_numeric($userid)) {
    add_default_schedules($db, $userid);
}

// Config file:
$OUT .= '<tr><td class="install2">Generating config file</td>';
try{
    if (!$rv_pex || !$rv_ps || !$rv_uem || !$rv_ufn || !$rv_uv) {
        throw new exception('Valid data required'); 
    }
    $rv_ccf = copy('config.php.default', '../config.php');

} catch (exception $e) {
	$rv_ccf = FALSE;
	$error = $e->getMessage();
}
$OUT .= GenRetVal($rv_ccf);
if ($rv_ccf === FALSE)
	$OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';

// Finish up!
if ($rv_ccf && $rv_cu && $rv_cdb && $rv_pe && $rv_ps && $rv_uv && $rv_uem && $rv_ufn && $rv_uu && $rv_dd) {

    $OUT .= '<tr colspan="2"><td><a onclick="LoadPage(7);">'.$continuepic.'</a></td></tr>';
	// Tell user to start urdd after setting the preferences

} else {
	$OUT .= '<tr colspan="2"><td>Please try to correct any error you might see.</td></tr>';
	$OUT .= '<tr colspan="2"><td><a onclick="LoadPage(5);">'.$refreshpic.'</a></td></tr>';
}

