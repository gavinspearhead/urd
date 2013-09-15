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

class DatabaseConnection_psql extends DatabaseConnection
{
    public function __construct($databasetype, $hostname, $port, $user, $pass, $database, $force_new_connection=FALSE, $persistent=FALSE, $dbengine='')
    {
        parent::__construct($databasetype, $hostname, $port, $user, $pass, $database, $force_new_connection=FALSE, $persistent=FALSE, $dbengine='');
    }
    public function get_dow_timestamp($from)
    {
        return "EXTRACT(DOW FROM to_timestamp($from))"; // todo check
    }
    public function get_timestamp($from)
    {
        return "to_timestamp($from)"; // todo check
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
            return '~~*';
        } elseif ($search_type == 'REGEXP') {
            return '~*';
        } else {
            throw new exception ('Unknown search type');
        }
    }

    public function drop_database($dbase)
    {
        $sql = "SELECT COUNT(*) AS cnt FROM pg_catalog.pg_database WHERE datname = '$dbase'";
        $res = $this->execute_query($sql);
        if (isset($res[0]['cnt']) && ($res[0]['cnt'] > 0)) {
            $sql = "DROP DATABASE \"$dbase\"";
            $this->DB->execute($sql);
        }
    }

    public function drop_table($table)
    {
        $sql = "DROP TABLE IF EXISTS \"$table\"";
        $this->execute_query($sql);
    }

    public function drop_user($user, $host='localhost')
    {
        $sql = "SELECT COUNT(*) AS cnt FROM pg_catalog.pg_user WHERE usename = '$user'";
        $res = $this->execute_query($sql);
        if (isset($res[0]['cnt']) && ($res[0]['cnt'] > 0)) {
            $sql = 'DROP USER ' . $user;
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
        $this->escape($pass);

        $sql = "CREATE USER \"$user\" WITH PASSWORD '$pass'";
        $this->execute_query($sql);
    }

    public function grant_rights($host, $dbname, $dbuser)
    {
        $this->escape($dbname);
        $this->escape($dbuser);

        $sql = "ALTER DATABASE \"$dbname\" OWNER TO \"$dbuser\"";
        $this->execute_query($sql);
    }

    public function create_database($dbname)
    {
        $this->escape($dbname);
        $sql = "CREATE DATABASE \"$dbname\"";
        $this->execute_query($sql);
    }

    public function optimise_table($table)
    {
        $this->escape($table);
        if ($table == '__ALL__') {
            $sql = 'VACUUM FULL ANALYZE ';
        } else {
            $sql = 'VACUUM FULL ANALYZE "' . $table . '"';
        }
        $this->execute_query($sql);
    }
    protected function execute_if_exists($table, $qry)
    {
        $sql = "SELECT \"relname\" FROM pg_class WHERE relname = '$table';";
        $res = $this->execute_query($sql);
        if ($res !== FALSE) {
            return $this->execute_query($qry);
        } else {
            return FALSE;
        }
    }
    public function get_tables()
    {
        return array (0=>array(0=>'__ALL__')); // postfix doesn't have a show tables equivalent. We use a trick here: we return all and for optimising we run the vacuum full without a table name. Nasty? yes. Works? yes.
    }

    public function connect($force_new_connection=FALSE)
    {
        echo_debug("Connecting to {$this->databasetype}: {$this->databasename} @ {$this->uri}", DEBUG_DATABASE);
        try {
            switch (strtolower($this->databasetype)) {
                case 'postgres7':
                case 'postgres8':
                case 'postgres9':
                    $this->DB = NewADOConnection($this->databasetype);
                    $this->DB->NConnect($this->uri, $this->username, $this->password, $this->databasename); // psql always reconnects... or weird shit happens
                    break;
                case 'pdo_pgsql':
                    $this->DB = ADONewConnection('pdo');
                    $this->DB->NConnect($this->uri, $this->username, $this->password); // psql always reconnects... or weird shit happens
                    break;
                case 'postgres':
                case 'postgres64':
                default :
                    throw new exception ("Database type {$this->databasetype} not yet supported");
                    break;
            }
        } catch (exception $e) {
            throw new exception('Could not connect to database: ' . $e->getMessage());
        }
    }

    public function start_transaction()
    {
        $this->execute_query('BEGIN WORK');
    }
    public function commit_transaction()
    {
        return $this->execute_query('COMMIT WORK');
    }

    public function lock(array $tableactions)
    {
        $this->execute_query('BEGIN WORK');
        foreach ($tableactions as $table => $action) { // XXX todo translate mysql locks to pg --> read, write, ...
            $this->execute_query("LOCK TABLE $table IN EXCLUSIVE MODE");
        }
    }

    public function unlock()
    {
        return $this->execute_query('COMMIT WORK');
    }
    public function get_last_id()
    {
        $res = $this->execute_query('SELECT lastval() AS lv');
        if (isset($res[0]['lv'])) {
            return $res[0]['lv'];
        } else {
            return FALSE;
        }
    }

}
