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
 * $LastChangedDate: 2012-10-25 22:07:20 +0200 (do, 25 okt 2012) $
 * $Rev: 2714 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: db.class.php 2714 2012-10-25 20:07:20Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathdbc = realpath(dirname(__FILE__));

require_once "$pathdbc/../config.php";
require_once "$pathdbc/config_functions.php";
require_once "$pathdbc/defines.php";
require_once "$pathdbc/urd_exceptions.php";
require_once "$pathdbc/db/urd_db_structure.php";
require_once "$pathdbc/libs/adodb/adodb-exceptions.inc.php";
require_once "$pathdbc/libs/adodb/adodb.inc.php";

class DatabaseConnection_mysql extends DatabaseConnection
{
    public function __construct($databasetype, $hostname, $port, $user, $pass, $database, $force_new_connection=FALSE, $persistent=FALSE, $dbengine='')
    {
        parent::__construct($databasetype, $hostname, $port, $user, $pass, $database, $force_new_connection=FALSE, $persistent=FALSE, $dbengine='');
    }
    public function get_dow_timestamp($from)
    {
        return "FROM_UNIXTIME($from, '%w')";
    }
    public function get_timestamp($from)
    {
        return "FROM_UNIXTIME($from)";
    }
    public function get_extract($what, $from)
    {
        return "EXTRACT($what FROM $from)";
    }
    public function get_greatest_function()
    {
        return 'GREATEST';
    }
    public function get_pattern_search_command($search_type)
    {
        $search_type = strtoupper($search_type);
        if ($search_type == 'LIKE') {
            return 'LIKE';
        } elseif ($search_type == 'REGEXP') {
            return 'REGEXP';
        } else {
            throw new exception ('Unknown search type');
        }
    }
    public function drop_database($dbase)
    {
        $sql = "DROP DATABASE IF EXISTS \"$dbase\"";
        $this->execute_query($sql);
    }

    public function drop_table($table)
    {
        $sql = "DROP TABLE IF EXISTS \"$table\"";
        $this->execute_query($sql);
    }
    public function drop_user($user, $host='localhost')
    {
        $this->escape($user);
        $host = ($host == 'localhost') ? 'localhost' : '%';
        $sql = "SELECT User FROM mysql.user WHERE User = '$user' AND Host = '$host'";
        $res = $this->execute_query($sql);
        if (isset($res[0]['User'])) {
            $sql = 'DROP USER "' . $user . '"@' . $host;
            $this->execute_query($sql);
        }
    }

    public function truncate_table($table)
    {
        $sql = "TRUNCATE TABLE \"$table\"";
        $res = $this->execute_query($sql);
    }

    public function create_dbuser($host, $user, $pass)
    {
        $this->escape($user);
        $this->escape($host);
        $this->escape($pass);
        $host = ($host == 'localhost') ? 'localhost' : '%';

        $sql = "CREATE USER \"$user\"@\"$host\" IDENTIFIED BY '$pass'";
        $this->execute_query($sql);
    }

    public function grant_rights($host, $dbname, $dbuser)
    {
        $host = ($host == 'localhost') ? 'localhost' : '%';
        $this->escape($host);
        $this->escape($dbname);
        $this->escape($dbuser);

        $sql = "GRANT ALL ON \"$dbname\".* TO \"$dbuser\"@\"$host\"";
        $this->execute_query($sql);
    }

    public function create_database($dbname)
    {
        $this->escape($dbname);
        $sql = "CREATE DATABASE \"$dbname\" DEFAULT CHARSET \"utf8\" DEFAULT COLLATE \"utf8_general_ci\"";
        $this->execute_query($sql);
    }

    public function optimise_table($table)
    {
        $sql = 'OPTIMIZE TABLE "' . $table . '"';
        $this->execute_query($sql);
        $sql = 'ANALYZE TABLE "' . $table . '"';
        $this->execute_query($sql);
    }
    protected function execute_if_exists($table, $qry)
    {
        // to do -- not needed?
        return FALSE;
    }
    public function get_tables()
    {
        return $this->execute_query('SHOW TABLES');
    }

    public function connect($force_new_connection=FALSE)
    {
        echo_debug("Connecting to {$this->databasetype}: {$this->databasename} @ {$this->uri}", DEBUG_DATABASE);

        try {
            ini_set('mysql.connect_timeout', 240);
            $databasename = $this->databasename;
            if ($this->databasetype == 'pdo_mysql') {
                $this->DB = ADONewConnection('pdo');
                $databasename = '';
            } else {
                $this->DB = ADONewConnection($this->databasetype);
            }
            if (!$this->force_new_connection || $force_new_connection) {
                $this->DB->Connect($this->uri, $this->username, $this->password, $databasename);
            } elseif (!$this->persistent) {
                $this->DB->NConnect($this->uri, $this->username, $this->password, $databasename);
            } else {
                $this->DB->PConnect($this->uri, $this->username, $this->password, $databasename);
            }
            $this->execute_query("SET sql_mode='ANSI_QUOTES'"); //Needed so we can use the same queries on postgres and mysql
            $this->execute_query('SET CHARACTER SET UTF8');
        } catch (exception $e) {
            throw new exception('Could not connect to database: ' . $e->getMessage());
        }
    }
    public function start_transaction()
    {
        $this->execute_query('START TRANSACTION');
    }
    public function commit_transaction()
    {
        return $this->execute_query('COMMIT');
    }

    public function lock(array $tableactions)
    {
        $qry = '';
        foreach ($tableactions as $table => $action) {
            $qry .= "$table $action, ";
        }

        $qry = rtrim($qry, ' ,');
        $this->execute_query('LOCK TABLE ' . $qry);
    }

    public function unlock()
    {
        return $this->execute_query('UNLOCK TABLE');
    }
    public function get_last_id()
    {
        return $this->DB->Insert_ID();
    }

}
