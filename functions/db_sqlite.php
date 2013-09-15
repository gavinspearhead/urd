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

function sqlite_regexp($regex, $str)
{
    return (preg_match("/$regex/i", $str)) ? TRUE : FALSE;
}

function sqlite_extract($what, $from)
{
    $date = strtotime($from);
    switch ($what) {
    case 'year':
        return date('Y', $date);
        break;
    case 'month':
        return date('n', $date);
        break;
    }

    return '';
}

class DatabaseConnection_sqlite extends DatabaseConnection
{
    public function __construct($databasetype, $hostname, $port, $user, $pass, $database, $force_new_connection=FALSE, $persistent=FALSE, $dbengine='')
    {
        parent::__construct($databasetype, $hostname, $port, $user, $pass, $database, $force_new_connection=FALSE, $persistent=FALSE, $dbengine='');
    }
    public function get_dow_timestamp($from)
    {
        return "strftime('%w', time($from), 'unixepoch')";  // needs to check TODO
    }
    public function get_timestamp($from)
    {
        return "strftime('%s', time($from), 'unixepoch')";  // needs to check TODO
    }
    public function get_extract($what, $from)
    {
        return "EXTRACT('$what', $from)";
    }

    public function get_greatest_function()
    {
        return 'MAX';
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
        unlink($dbase);
    }

    public function drop_table($table)
    {
        $sql = "DROP TABLE IF EXISTS \"$table\"";
        $this->execute_query($sql);
    }

    public function truncate_table($table)
    {
        $res = $this->delete_query($table);
    }
    public function drop_user($user, $host='localhost')
    {
        // do nothing; no user auth in sqlite
    }

    public function create_dbuser($host, $user, $pass)
    {
        // do nothing; no user needed
    }

    public function grant_rights($host, $dbname, $dbuser)
    {
        // do nothing; no rights needed
    }

    public function create_database($dbname)
    {
        $this->escape($dbname);
        if (!file_exists($dbname)) {
            $this->DB->Connect($dbname);
        }
        if (file_exists($dbname)) {
            chmod($dbname, 0660);
        }
    }

    public function optimise_table($table)
    {
        $this->escape($table);
        $sql = 'VACUUM';
        $this->execute_query($sql);
    }
    protected function execute_if_exists($table, $qry)
    {
        // todo if needed
    }
    public function get_tables()
    {
        // todo
    }

    public function connect($force_new_connection=FALSE)
    {
        echo_debug("Connecting to {$this->databasetype}: {$this->databasename} @ {$this->uri}", DEBUG_DATABASE);
        try {
            if ($this->databasename == '') {
                throw new exception('Database name must be provided');
            }

            $this->DB = ADONewConnection('pdo');
            if (!$this->force_new_connection || $force_new_connection) {
                $this->DB->Connect('sqlite:' . $this->databasename);
            } elseif (!$this->persistent) {
                $this->DB->NConnect('sqlite:' . $this->databasename);
            } else {
                $this->DB->PConnect('sqlite:' . $this->databasename);
            }
            $this->DB->_create_function('REGEXP', 'sqlite_regexp', 2);
            $this->DB->_create_function('EXTRACT', 'sqlite_extract', 2);
            $this->execute_query('PRAGMA synchronous=OFF');
        } catch (exception $e) {
            throw new exception('Could not connect to database: ' . $e->getMessage());
        }
    }
    public function start_transaction()
    {
        $this->execute_query('BEGIN EXCLUSIVE TRANSACTION');
    }
    public function commit_transaction()
    {
        return $this->execute_query('COMMIT TRANSACTION');
    }

    public function lock(array $tableactions)
    {
        $this->execute_query('BEGIN EXCLUSIVE TRANSACTION');
        // todo
    }

    public function unlock()
    {
        return $this->execute_query('COMMIT TRANSACTION');
    }
    public function get_last_id()
    {
        return $this->DB->Insert_ID();
    }

}
