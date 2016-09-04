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
 * $LastChangedDate: 2012-05-05 00:29:50 +0200 (Sat, 05 May 2012) $
 * $Rev: 2512 $
 * $Author: gavinspearhead $
 * $Id: install.7.inc.php 2512 2012-05-04 22:29:50Z gavinspearhead $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

// Select the first usenet server
$hostname = isset($_SESSION['hostname']) ? $_SESSION['hostname'] : '';
$port = isset($_SESSION['port']) ? $_SESSION['port'] : '119';
$connection = isset($_SESSION['connection']) ? $_SESSION['connection'] : '';
$username =isset($_SESSION['username']) ? $_SESSION['username'] : '';
$password = isset($_SESSION['password']) ? $_SESSION['password'] : '';
$server_id = isset($_SESSION['server_id']) ? $_SESSION['server_id'] : '';

$OUT .= '<tr><td colspan="2" class="install1">Selecting usenet server</td></tr>' . "\n";

$db = connect_db(FALSE);

$servers = get_all_usenet_servers($db, FALSE);

foreach($servers as $server) {
    $OUT.= "<input type=\"hidden\" name=\"server_{$server['id']}\" id=\"server_{$server['id']}\"value=\"{$server['name']}|{$server['hostname']}|{$server['port']}|{$server['secure_port']}|{$server['connection']}\"/>\n";
}
$OUT .= <<<USENET1

<tr><td class="install2">Usenet Server:</td><td class="install3">
<select name="server_id" id="server_id" onchange="javascript:fill_in_usenet_form();">
<option value="">New Server</option>

USENET1;

foreach($servers as $server) {
    $OUT .= "<option value=\"{$server['id']}\"" . ($server['id'] ==  $server_id ? 'selected="selected"' : '') . ">{$server['name']}</option>\n";
}

$connection = strtolower($connection);
$off_conn = $ssl_conn = $tls_conn = '';
if ($connection == 'off') { $off_conn = 'selected="selected"'; } 
if ($connection == 'ssl') { $ssl_conn = 'selected="selected"'; } 
if ($connection == 'tls') { $tls_conn = 'selected="selected"'; } 

$OUT .= <<<USENET2
</select>
<input type="hidden" id="serverid" value ="">
<tr><td class="install2">Hostname :</td><td class="install3">
<input type="text" name="hostname" id="hostname" value="$hostname"></td></tr>
<tr><td class="install2">Port:</td><td class="install3">
<input type="text" name="port" id="port"value="$port"></td></tr>
<tr><td class="install2">Secure connection type:</td><td class="install3">
<select name="connection" id="connection">
<option value="off" $off_conn>Off</option>
<option value="ssl" $ssl_conn>SSL</option>
<option value="tls" $tls_conn>TLS</option>
</select>
</td></tr>

<tr><td class="install2"><br/>Leave username and password blank if authentication is not needed.<br/></td></tr>
<tr><td class="install2">Username:
</td><td class="install3">
<input type="text" name="username" value="$username"></td></tr>
<tr><td class="install2">Password:</td><td class="install3">
<input type="password" name="password" id="password" value="$password">&nbsp;<span onclick="javascript:toggle_show_password('password');">$showpasspic</span></td></tr>
USENET2;
$OUT .= '<tr colspan="2"><td><a onclick="LoadPage(8);">'.$continuepic.'</a></td></tr>';

