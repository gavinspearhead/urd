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
 * $LastChangedDate: 2014-06-22 23:53:13 +0200 (zo, 22 jun 2014) $
 * $Rev: 3113 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: install.3.inc.php 3113 2014-06-22 21:53:13Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) { 
    die('This file cannot be accessed directly.');
}

// Check for database settings: ask account info

// Were these values entered before? If so, we remember:
$dbtype = isset($_SESSION['dbtype']) ? $_SESSION['dbtype'] : '';
$dbhost = isset($_SESSION['dbhost']) ? $_SESSION['dbhost'] : 'localhost';
$dbport = isset($_SESSION['dbport']) ? $_SESSION['dbport'] : '';
$dbname = isset($_SESSION['dbname']) ? $_SESSION['dbname'] : 'urddb';
$dbuser = isset($_SESSION['dbuser']) ? $_SESSION['dbuser'] : 'urd_user';
$dbpass = isset($_SESSION['dbpass']) ? $_SESSION['dbpass'] : '';
$dbruser = isset($_SESSION['dbruser']) ? $_SESSION['dbruser'] : 'root';
$dbrpass = isset($_SESSION['dbrpass']) ? $_SESSION['dbrpass'] : '';
$dbclear = isset($_SESSION['dbclear']) ? $_SESSION['dbclear'] : '';
$dbuserclear = isset($_SESSION['dbuserclear']) ? $_SESSION['dbuserclear'] : '';
$dbengine = isset($_SESSION['dbengine']) ? $_SESSION['dbengine'] : '';
$keystore_default_path = isset($_SESSION['keystore_path']) ? $_SESSION['keystore_path'] : realpath('../');
$reuse_keystore = isset($_SESSION['reuse_keystore']) ? $_SESSION['reuse_keystore'] : '';


// If this is an upgrade, we have a dbconfig.php... use these values instead:
@include('dbconfig.php');
if (isset($config['databasetype'])) {
	$dbtype = $config['databasetype'];
    $dbname = $config['database'];
    $dbuser = $config['db_user'];
    $dbpass = $config['db_password'];
    $dbhost = $config['db_hostname'];
    $dbport = $config['db_port'];
    $dbengine = $config['db_engine'];
}

$dbtype = htmlentities($dbtype);
$dbname = htmlentities($dbname);
$dbuser = htmlentities($dbuser);
$dbpass = htmlentities($dbpass);
$dbhost = htmlentities($dbhost);
$dbport = htmlentities($dbport);
$dbengine = htmlentities($dbengine);

$dbclear = $dbclear ? 'CHECKED' : '';
$dbuserclear = $dbuserclear ? 'CHECKED' : '';
$reuse_keystore = $reuse_keystore ? 'CHECKED' : '';

if ($dbhost == '') { 
    $dbhost = 'localhost';
}

$OUT .= '<tr><td colspan="2" class="install1">Database settings</td></tr>' . "\n";

$dbs = array();
   
if (extension_loaded('pdo_mysql')) {
    $dbs[] = array ('pdo_mysql', 'Mysql (PDO)');
}
if (extension_loaded('mysqli')) {
    $dbs[] = array ('mysqli', 'MySQL Improved (deprecated');
}
if (extension_loaded('mysql')) {
    $dbs[] = array ('mysql', 'MySQL (deprecated');
}
if (extension_loaded('pdo_pgsql')) {
    $dbs[] = array ('pdo_pgsql', 'Postgresql (PDO)');
}
if (extension_loaded('pgsql')) {
    $dbs[] = array ('postgres9', 'Postgres 9 (deprecated)');
    $dbs[] = array ('postgres8', 'Postgres 8 (deprecated');
    $dbs[] = array ('postgres7', 'Postgres 7 (deprecated');
}
if (extension_loaded('pdo_sqlite')) {
    $dbs[] = array ('pdo_sqlite', 'SQLite (PDO)');
}

if ($dbs == array()) {
    $OUT .= '<tr><td class="install2">No database driver installed (try sudo apt-get install php5-mysql)</td>';
	$OUT .= '<tr colspan="2"><td><a onclick="LoadPage(3);">' . $refreshpic . '</a></td></tr>';
} else {
$OUT .= <<<SELECTDB
<tr><td class="install2">Select your database:</td><td class="install3">
<select name="dbtype" id="dbtype" onchange="javascript:update_database_input_fields();">
SELECTDB;
foreach($dbs as $l) {
	$OUT .= '<option ' . (($l[0] == $dbtype) ? 'selected="selected"' : '') . " value=\"{$l[0]}\">{$l[1]}</option>\n";
} 
$showdbe = (in_array(strtolower($dbtype) , array('mysql', 'mysqli', 'pdo_mysql'))) ? '' : " style=\"display:hidden\" ";
$dbinno = ($dbengine == 'innodb') ? 'selected="selected"' : '';
$dbmyisam = ($dbengine == 'myisam') ? 'selected="selected"' : '';
$dbdef = ($dbengine != 'myisam' && $dbengine != 'innodb') ? 'selected="selected"' : '';

$OUT .= <<<SELECTDB2
</select></td></tr>
<tr id="dbengine" $showdbe><td class="install2">Database Engine (MyIsam typically has best performance with URD)</td><td class="install3">
<select name="dbengine">
<option value="" $dbdef>Default</option>
<option value="innodb" $dbmyisam>InnoDB</option>
<option value="myisam" $dbdef>MyIsam</option>
</select></td>
</tr>
<tr id="hostname"><td class="install2">Database hostname:</td><td class="install3">
<input type="text" id="dbhost" name="dbhost" value="$dbhost"></td></tr>
<tr id="port"><td class="install2">Database port number (blank for default):</td><td class="install3">
<input type="text" name="dbport" id="dbport" value="$dbport"></td></tr>
<tr><td class="install2">Database name (or filename in case of SQLite):</td><td class="install3">
<input type="text" name="dbname" id="dbname" value="$dbname"></td></tr>
<tr id="dbusername"><td class="install2">Database username:</td><td class="install3">
<input type="text" name="dbuser" id="dbuser" value="$dbuser"></td></tr>
<tr id="dbpassword"><td class="install2">Database password (leave blank to generate one):</td><td class="install3">
<input id="dbpass" type="password" name="dbpass" id="dbpass" value="$dbpass"> <span onclick="javascript:toggle_show_password('dbpass');">$showpasspic</span>
<br></td></tr>
<tr><td colspan="2"></td></tr>
<tr><td colspan="2"></td></tr>
<tr id="dbmysqlreset"><td></td><td>Instructions to reset password for <a target="_new" href="https://dev.mysql.com/doc/refman/5.0/en/resetting-permissions.html">Mysql</a>.</tr>
<tr id="dbroot"><td class="install2">Database administrator (root) username:</td><td class="install3">
<input type=text name="dbruser" id="dbruser" value="$dbruser"></td></tr>
<tr id="dbrootpw"><td class="install2">Database administrator (root) password:</td><td class="install3">
<input type="password" name="dbrpass" id="dbrpass" value="$dbrpass"> <span onclick="javascript:toggle_show_password('dbrpass');">$showpasspic</span>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td class="install1">In case you overwrite an existing URD installation:</td></tr>
<tr><td class="install2">Delete existing database:</td><td class="install3">
<input type="checkbox" name="dbclear" $dbclear></td></tr>
<tr><td class="install2">Delete existing database user:</td><td class="install3">
<input type="checkbox" name="dbuserclear" $dbuserclear></td></tr>
<tr><td colspan="2"><br/></td></tr>
<tr><td class="install2">Location of the key store for database encryption of passwords</td>
<td class="install3"><input type="text" name="keystore_path" value="$keystore_default_path" size="60"></td></tr>
<tr><td colspan="2" class="install1">Setting key for database encryption of passwords</td></tr>
<tr><td class="install2">Encryption key (leave blank to generate one)</td><td class="install3"><input type="password" name="encryption_key" value="">&nbsp;<span onclick="javascript:toggle_show_password('password');">$showpasspic</span></td></tr></td></tr>
<tr><td class="install2">Reuse existing keystore:</td><td class="install3">
<input type="checkbox" name="reuse_keystore" $reuse_keystore></td></tr>
<tr colspan="3" id="continue_button"><td><a onclick="show_message('Creating database... please wait');LoadPage(4); hide_button('continue_button');">$continuepic</a></td></tr>
SELECTDB2;

}

