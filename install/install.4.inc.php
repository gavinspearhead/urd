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
 * $Id: install.4.inc.php 3113 2014-06-22 21:53:13Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) { 
    die('This file cannot be accessed directly.');
}

// Check database account settings
$OUT .= '<tr><td colspan="2" class="install1">Checking database</td></tr>' . "\n";


$dbengine = get_request('dbengine');
$dbtype = get_request('dbtype');
$dbhost = get_request('dbhost');
$dbport = get_request('dbport');
$dbname = get_request('dbname');
$dbuser = get_request('dbuser');
$dbpass = get_request('dbpass');
$dbruser = get_request('dbruser');
$dbrpass = get_request('dbrpass');
$dbclear = isset($_REQUEST['dbclear']) ? TRUE : FALSE;
$dbuserclear = isset($_REQUEST['dbuserclear']) ? TRUE : FALSE;
$keystore_path = get_request('keystore_path');
$encryption_key = get_request('encryption_key');
$reuse_keystore = get_request('reuse_keystore');

if ($dbpass == '') {
    $dbpass = generate_password(32);
}

// Store in session:
$_SESSION['dbtype'] = $dbtype;
$_SESSION['dbhost'] = $dbhost;
$_SESSION['dbport'] = $dbport;
$_SESSION['dbname'] = $dbname;
$_SESSION['dbuser'] = $dbuser;
$_SESSION['dbpass'] = $dbpass;
$_SESSION['dbruser'] = $dbruser;
$_SESSION['dbrpass'] = $dbrpass;
$_SESSION['dbclear'] = $dbclear; 
$_SESSION['dbuserclear'] = $dbuserclear; 
$_SESSION['keystore_path'] = $keystore_path; 
$_SESSION['reuse_keystore'] = $reuse_keystore; 

// Sanity check:
$skip = FALSE;
if ($dbruser != '' && $dbuserclear && ($dbuser == 'root' || $dbuser == $dbruser)) {
	$OUT .= '<tr colspan="2"><td>This installer will not allow you to delete the database administrator (root) account ! Change \'Database username\' to something else or deselect \'Delete existing user\'!</td></tr>';
	$OUT .= '<tr colspan="2"><td><a onclick="LoadPage(3);">' . $refreshpic . '</a></td></tr>';
    $skip = TRUE;
    $dbuserclear = FALSE;
}

$db_created = TRUE;

if ($dbruser != '' && $dbtype != 'sqlite') { //  we need to log in as root and create the user and database:
    $OUT .= '<tr><td class="install2">Database root login</td>';
    try {
        if ($dbname == '') { 
            throw new exception('Database name must be provided');
        }
        if ($dbtype == 'mysql') {
            $db = new DatabaseConnection_mysql($dbtype, $dbhost, $dbport, $dbruser, $dbrpass, '', TRUE);
        } elseif ($dbtype == 'postgres') {
            $db = new DatabaseConnection_psql($dbtype, $dbhost, $dbport, $dbruser, $dbrpass, '', TRUE);
        } else {
            throw new exception('Wrong database type');
        }
        $rv_rdb = TRUE;
    } catch (exception $e) {
        $rv_rdb = FALSE;
        $error = $e->getMessage();
        $db_created = FALSE;
    }
    $OUT .= GenRetVal($rv_rdb);
    if ($rv_rdb == FALSE) {
        $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
    }
    // If database already exists, clear remove it ?
    if ($dbclear) {
        $OUT .= '<tr><td class="install2">Deleting old database</td>';
        try{
            if ($rv_rdb === FALSE) {
                throw new exception('Administrator (root) login required');
            }
            $db->drop_database($dbname);
            $rv_deldb = TRUE;
        } catch (exception $e) {
            $rv_deldb = FALSE;
            $error = $e->getMessage();
            $db_created = FALSE;
        }
        $OUT .= GenRetVal($rv_deldb);
        if ($rv_deldb == FALSE) {
            $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
        }
    }
    // If the database user already exists, clear remove it?
    if ($dbuserclear) {
        $OUT .= '<tr><td class="install2">Deleting old user</td>';

        try {
            if ($rv_rdb === FALSE) {
                throw new exception('Administrator (root) login required');
            }
            $db->drop_user($dbuser, $dbhost);
            $rv_delu = TRUE;
        } catch (exception $e) {
            $rv_delu = FALSE;
            $error = $e->getMessage();
            $db_created = FALSE;
        }
        $OUT .= GenRetVal($rv_delu);
        if ($rv_delu == FALSE) {
            $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
        }
    }

    $OUT .= '<tr><td class="install2">Database creation</td>';
    try{
        if ($rv_rdb === FALSE) {
            throw new exception('Database administrator (root) login required');
        }
        $db->create_database($dbname);
        $rv_cdb = TRUE;
    } catch (exception $e) {
        $rv_cdb = FALSE;
        $db_created = FALSE;
        $error = $e->getMessage();
    }
    $OUT .= GenRetVal($rv_cdb);
    if ($rv_cdb == FALSE) {
        $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
    }

    $OUT .= '<tr><td class="install2">Database account creation</td>';
    try{
        if ($rv_rdb === FALSE) {
            throw new exception('Database administrator (root) login required');
        }
        if ($rv_cdb === FALSE) {
            throw new exception('Database not created');
        }
        if ($dbuser != $dbruser) {
            $db->create_dbuser($dbhost, $dbuser, $dbpass);
        }
        $rv_cua = TRUE;
    } catch (exception $e) {
        $rv_cua = FALSE;
        $db_created = FALSE;
        $error = $e->getMessage();
    }
    $OUT .= GenRetVal($rv_cua);

    if ($rv_cua == FALSE) {
        $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
    }
    $OUT .= '<tr><td class="install2">Database account privileges</td>';
    try{
        if ($rv_rdb === FALSE) {
            throw new exception('Database administrator (root) login required');
        }
        if ($rv_cdb === FALSE) {
            throw new exception('Database not created');
        }
        if ($rv_cua === FALSE) {
            throw new exception('Database user not created');
        }
        if ($dbuser != $dbruser) {
            $db->grant_rights($dbhost, $dbname, $dbuser);
        }
        $rv_cup = TRUE;
    } catch (exception $e) {
        $rv_cup = FALSE;
        $error = $e->getMessage();
        $db_created = FALSE;
    }
    $OUT .= GenRetVal($rv_cup);
    if ($rv_cup == FALSE) {
        $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
    }
}


if ($dbtype == 'sqlite') {
    $OUT .= '<tr><td class="install2">Database creation</td>';
    try {
        if (file_exists($dbname)) {
            $rv = @unlink($dbname);
            if ($rv === FALSE) {
                throw new exception('Could not delete database');
            }
        }
        $db = new DatabaseConnection_sqlite($dbtype, $dbhost, $dbport, $dbuser, $dbpass, $dbname, TRUE);
        $rv_deldb = TRUE;
    } catch (exception $e) {
        $rv_deldb = FALSE;
        $error = $e->getMessage();
    }
    $OUT .= GenRetVal($rv_deldb);
    if ($rv_deldb == FALSE) {
        $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
    }
}

$rv_db = FALSE;
if ($db_created) {
    $OUT .= '<tr><td class="install2">Database user connection</td>';

    try {
        if ($dbtype == 'mysql') {
            $db = new DatabaseConnection_mysql($dbtype, $dbhost, $dbport, $dbuser, $dbpass, $dbname, TRUE);
        } elseif ($dbtype == 'postgres' ) {
            $db = new DatabaseConnection_psql($dbtype, $dbhost, $dbport, $dbuser, $dbpass, $dbname, TRUE);
        } elseif ($dbtype == 'sqlite') {
            $db = new DatabaseConnection_sqlite($dbtype, $dbhost, $dbport, $dbuser, $dbpass, $dbname, TRUE);
        } else {
            throw new exception ('Wrong database type');
        }
        $rv_db = TRUE;
    } catch (exception $e) {
        $error = $e->getMessage();
    }
    $OUT .= GenRetVal($rv_db);
    if ($rv_db === FALSE)
        $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';

    if($dbruser != '' || $dbtype == 'sqlite') {
        $OUT .= '<tr><td class="install2">Creating tables</td>';
        try{
            if ($rv_db === FALSE) {
                throw new exception('User login required');
            }
            $sdb = urd_db_structure::create_db_updater($dbtype, $db, TRUE);
            $urd_db = urd_db_structure::create_db_structure($sdb, $dbengine);
            $rv_ct = TRUE;
        } catch (exception $e) {
            $rv_ct = FALSE;
            $error = $e->getMessage();
        }
        $OUT .= GenRetVal($rv_ct);
        if ($rv_ct == FALSE) {
            $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
        }
        // Load default DB
        $OUT .= '<tr><td class="install2">Creating URD root account</td>';
        try {
            if ($rv_db === FALSE) { 
                throw new exception('User login required');
            }
            // Make root user:
            create_root($db);
            $rv_cr = TRUE;
        } catch (exception $e) {
            $rv_cr = FALSE;
            $error = $e->getMessage();
        }
        $OUT .= GenRetVal($rv_cr);
        if ($rv_cr == FALSE) {
            $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
        }
    } else {
        $rv_ct = TRUE;
        $rv_cr = TRUE;
    }

    // Saving the DB data
    if ($rv_db && $rv_ct && $rv_cr) {
        $OUT .= '<tr><td class="install2">Saving DB information</td>';
        $rv_sdi = FALSE;
        try {
            WriteDBConfig($dbtype, $dbuser, $dbpass, $dbname, $dbhost, $dbport, $dbengine);
            $rv_sdi = TRUE;
        } catch (exception $e) {
            $error = $e->getMessage();
        }
        $OUT .= GenRetVal($rv_sdi);

        if ($rv_sdi == FALSE)
            $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
    }

    if ($rv_db && $rv_ct && $rv_cr) {
        try {
            $OUT .= '<tr><td class="install2">Creating keystore:</td>';
            set_config($db, 'keystore_path', $keystore_path);
            keystore::create_keystore($db, $encryption_key, $reuse_keystore);
            $rv_kst = TRUE;
        } catch (exception $e) {
            $rv_kst = FALSE;
            $error = $e->getMessage();
        }
        $OUT .= GenRetVal($rv_kst);
        if ($rv_kst === FALSE) {
            $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
        }
    }

    // Storing default usenet servers:
    if ($rv_db && $rv_ct && $rv_cr) {
        $OUT .= '<tr><td class="install2">Storing default usenet servers</td>';
        $rv_cus = FALSE;
        try {
            create_usenet_servers($db);
            $rv_cus = TRUE;
        } catch (exception $e) {
            $error = $e->getMessage();
        }
        $OUT .= GenRetVal($rv_cus);

        if ($rv_cus == FALSE) {
            $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
        }
    }

    // Storing default search options:
    if ($rv_db && $rv_ct && $rv_cr) {
        $OUT .= '<tr><td class="install2">Storing default search options</td>';
        $rv_idb = FALSE;
        try{
            insert_default_options($db);
            $rv_idb = TRUE;
        } catch (exception $e) {
            $error = $e->getMessage();
        }
        $OUT .= GenRetVal($rv_idb);

        if ($rv_idb == FALSE) {
            $OUT .= '<tr colspan="2"><td><span class="info">' . $error . '</span></td></tr>';
        }
    }
}

if ($rv_db && $rv_ct && $rv_cr && $rv_sdi && $rv_cus && $rv_kst) {
    $OUT .= '<tr colspan="2"><td><a onclick="LoadPage(5);">'.$continuepic.'</a>';
    $OUT .= '<a onclick="LoadPage(3);">'.$refreshpic.'</a></td></tr>';
} else {
    $OUT .= 'Please make sure you have the correct database details.<br/>';
    $OUT .= '<tr colspan="2"><td><a onclick="LoadPage(3);">'.$refreshpic.'</a></td></tr>';
}

