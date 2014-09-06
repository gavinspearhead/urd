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
 * $LastChangedDate: 2013-01-26 17:02:25 +0100 (za, 26 jan 2013) $
 * $Rev: 2768 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: install.6.inc.php 2768 2013-01-26 16:02:25Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) { die('This file cannot be accessed directly.'); }

$connection_types = array (
    'off', 
    'ssl', 
    'tls'
);

// Finish up
$hostname       = get_request('hostname');
$port           = get_request('port');
$connection     = get_request('connection');
$username       = get_request('username');
$password       = get_request('password');
$server_id      = get_request('server_id');

// Store in session:
$_SESSION['hostname']   = $hostname;
$_SESSION['port']       = $port;
$_SESSION['connection'] = $connection;
$_SESSION['username']   = $username;
$_SESSION['password']   = $password;
$_SESSION['server_id']  = $server_id;

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

$OUT .= '<tr><td class="install2">Hostname valid:</td>';
$OUT .= GenRetVal($hostname != '', $rv_hn);
if (!$rv_hn) {
    $OUT .= ShowHelp('Please enter the correct name (typically similar likenews.ISP.com)'); 
}

$OUT .= '<tr><td class="install2">Port number valid:</td>';
$OUT .= GenRetVal(is_numeric($port) && $port <= 65535 && $port > 0, $rv_pt);
if (!$rv_pt) {
    $OUT .= ShowHelp('Please enter a valid port number (119 is the default usenet port; 563 for secure usenet)'); 
}

$OUT .= '<tr><td class="install2">Connection type valid:</td>';
$OUT .= GenRetVal(in_array(strtolower($connection), $connection_types), $rv_conn);
if (!$rv_conn) { 
    $OUT .= ShowHelp('Please select one of "Off", "SSL" or "TLS"');
}

$OUT .= '<tr><td class="install2">Authentication valid:</td>';
$auth = ($password == '' && $username == '') || ($password != '' && $username != '');
$OUT .= GenRetVal($auth, $rv_auth);
if (!$rv_auth) { 
    $OUT .= ShowHelp('Please enter both password and username if authentication is required, otherwise leave both blank.'); 
}

$authentication = 0;  
if ($username != '' && $password != '') { 
    $authentication = 1; 
}
if ($connection == 'off') { 
    $secure_port = 0; 
} else {  
    $secure_port = $port; 
    $port = 0; 
} 

// Finish up!
if ($rv_hn && $rv_pt && $rv_conn && $rv_auth && $rv_cdb) {
    if ($server_id == '') {
        //insert usenet server 
        $name = 'My server';
        $threads = 4;
        $server_id = add_usenet_server($db, $name, $hostname, $port, $secure_port, $threads, $connection, $authentication, $username, $password);
        $rv_server_id = is_numeric($server_id);
        set_config($db, 'preferred_server', $server_id);

        $OUT .= '<tr><td class="install2">Inserting usenet server</td>';
    } else {
        $rv_server_id = smart_update_usenet_server($db, $server_id, array ('hostname'=>$hostname, 'port'=>$port, 'secure_port'=>$secure_port, 'connection'=> $connection, 'authentication'=>$authentication, 'username'=>$username, 'password'=>$password, 'priority'=>DEFAULT_USENET_SERVER_PRIORITY, 'compressed_headers'=>FALSE, 'posting'=>FALSE));
        set_config($db, 'preferred_server', $server_id);
        $OUT .= '<tr><td class="install2">Updating usenet server</td>';
    }
}

if ($rv_hn && $rv_pt && $rv_conn && $rv_auth && $rv_cdb) {
    // Generate a .installed file, and check for that at the start (die if exists)
    // to prevent hackers from creating a clean install with default password or such.
    $OUT .= '<tr><td class="install2">Finishing installation by generating .installed file</td>';
    try{
        // Doesn't really need to be a try/catch; touch returns true or false when failed.
        $rv_cif = @touch('../.installed');
        file_put_contents('../.installed', 'URD ' . urd_version::get_version() . "\n" . date ('c') . "\n");
    } catch (exception $e) {
        $rv_cif = FALSE;
        $error = $e->getMessage();
    }
    $OUT .= GenRetVal($rv_cif);
    if ($rv_cif === FALSE) {
        $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
    }
    
    // Tell user to start urdd after setting the preferences
    $OUT .= '<tr colspan="2"><td><h2>Congratulations!</h2>It seems the installation has completed successfully. Please 
        continue to the configuration page to enter the settings, and when complete start the URD Daemon (either through the website or via ./urdd.sh).
        <br/>&nbsp;<br/><br/><a href="../html/admin_usenet_servers.php">Continue to the usenet server configuration</a></td></tr>
        If you need some support (be it mental or technical), visit us at <a href="http://www.urdland.com/forum">the URD forum</a></td></tr>';

} else {
	$OUT .= '<tr colspan="2"><td>Please try to correct any error you might see.</td></tr>';
	$OUT .= '<tr colspan="2"><td><a onclick="LoadPage(7);">'.$refreshpic.'</a></td></tr>';
}

