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
 * $LastChangedDate: 2014-06-21 23:20:44 +0200 (za, 21 jun 2014) $
 * $Rev: 3105 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: db.class.php 3105 2014-06-21 21:20:44Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathdbc = realpath(dirname(__FILE__));

require_once "$pathdbc/file_functions.php";
require_once "$pathdbc/db/urd_db_structure.php";
//require_once "$pathdbc/db_mysql.php";
//require_once "$pathdbc/db_psql.php";
//require_once "$pathdbc/db_sqlite.php";

function connect_db($check_db=TRUE)
{
    assert(is_bool($check_db));
    global $pathdbc;
    $db_config = my_realpath("$pathdbc/../") . '/dbconfig.php';
    $res = @include $db_config;
    if (!$res) {
        throw new exception("Require file $db_config not found. Perhaps you need to reinstall URD.\n");
    }

    if (!isset($config['db_engine'])) {
        write_log('Database engine not set; using defaults', LOG_INFO);
        $config['db_engine'] = '';
    }
    if (!isset($config['databasetype'])) {
        throw new exception('database type not set');
    }
    try {
        switch ($config['databasetype']) {
        case 'mysql':
        case 'mysqli': // only for old db_configs
        case 'pdo_mysql': // only for old db_configs
            $db = new DatabaseConnection_mysql($config['databasetype'], $config['db_hostname'], (isset($config['db_port']) ? $config['db_port'] : ''),
                    $config['db_user'], $config['db_password'], $config['database'], $config['db_engine']);
            break;
        case 'postgres':
        case 'postgres9': // only for old db_configs
        case 'postgres8': // only for old db_configs
        case 'postgres7': // only for old db_configs
        case 'pdo_pgsql': // only for old db_configs
            $db = new DatabaseConnection_psql($config['databasetype'], $config['db_hostname'], (isset($config['db_port']) ? $config['db_port'] : ''),
                    $config['db_user'], $config['db_password'], $config['database'], $config['db_engine']);
            break;
        case 'sqlite':
        case 'pdo_sqlite': // only for old db_configs
            $db = new DatabaseConnection_sqlite($config['databasetype'], $config['db_hostname'], (isset($config['db_port']) ? $config['db_port'] : ''),
                    $config['db_user'], $config['db_password'], $config['database'], $config['db_engine']);
            break;
        default:
            throw new exception ('Database type not supported');
        }

        if ($check_db) {
            $db->check_db_version();
        }
    } catch (exception $e) {
        unset($db);
        throw $e;
    }
    return $db;
}

/* Database interface */
abstract class DatabaseConnection
{
    protected $databasetype;
    protected $databaseengine;
    protected $databasename;
    protected $hostname;
    protected $port;
    protected $uri;
    protected $DB;
    protected $username;
    protected $password;
    protected $result;
    protected $error_code;

    const MAX_INSERT_PARTS          = 200;
    const DB_LOCK_TIMEOUT_PREVIEW   = 2;
    const DB_LOCK_TIMEOUT_DEFAULT   = 15;
    const GENSETS_STEPSIZE          = 50;
    const QUERY_LOG_FILE            = '/tmp/urd_query.log';

    const DIRTY = 1;
    const CLEAN = 0;
    // Dirty flags:
    const CONSISTENT =    0;
    const BINARYCHANGED = 1;
    const SETCHANGED =    2;
    public function __construct ($databasetype, $hostname, $port, $user, $pass, $database, $dbengine='')
    {
        $this->error_code = 0;
        $this->databasetype = $databasetype;
        $this->databaseengine = $dbengine;
        $this->databasename = $database;
        $this->hostname = $hostname;
        $this->port = (int) $port;
        $this->username = $user;
        $this->password = $pass;
        // We do store the sensitive information.  ;-)
        $this->connect();
        $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    protected function set_emulate_prepare($emulate)
    {
        $this->DB->setAttribute(PDO::ATTR_EMULATE_PREPARES,$emulate);
    }
    public function __destruct()
    {
        $this->disconnect();
    }
    public function check_db_version()
    {
        $ver = get_config($this, 'db_version', '-1');
        if ($ver != DB_VERSION) {
            echo_debug("DB Version not OK: $ver - should be: " . DB_VERSION , DEBUG_DATABASE);
            if (php_sapi_name() == 'cli') {
                throw new exception_db_version('Database outdated run php install/update_db.php');
            } else {
                global $pathdbc;
                require_once "$pathdbc/web_functions.php";
                config_cache::clear_all();
                redirect('../html/update_db.php');
            }
        } else {
            echo_debug("DB Version OK: $ver", DEBUG_DATABASE);
        }
    }
    public function get_databaseengine()
    {
        return $this->databaseengine;
    }
    public function get_databasetype()
    {
        return $this->databasetype;
    }
    public function get_database_server_info()
    {
        return $this->DB->getAttribute(PDO::ATTR_SERVER_INFO);
    }
    public function get_database_server_version()
    {
        return $this->DB->getAttribute(PDO::ATTR_SERVER_VERSION);
    }
    public function get_database_server_driver()
    {
        return $this->DB->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    public function disconnect()
    {
        $this->DB = NULL;
    }
    public function is_connected()
    {
       return (is_a($this->DB, 'PDO'));
    }

    public function set_fetch_mode($mode = PDO::FETCH_ASSOC)
    {
        $rv = $this->DB->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE);
        $this->DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $mode);
        return $rv;
    }

    abstract public function truncate_table($table);
    abstract public function get_dow_timestamp($from);
    abstract public function get_timestamp($from);
    abstract public function get_extract($what, $from);
    abstract public function get_greatest_function();
    abstract public function get_pattern_search_command($search_type);
    abstract public function start_transaction();
    abstract public function commit_transaction();
    abstract public function lock(array $tableactions);
    abstract public function unlock();
    abstract public function drop_database($dbase);
    abstract public function drop_user($user, $host='localhost');
    abstract public function create_dbuser($host, $user, $pass);
    abstract public function grant_rights($host, $dbname, $dbuser);
    abstract public function create_database($dbname);
    abstract public function optimise_table($table);
    abstract public function get_tables();
    abstract public function connect();
    abstract public function create_function($function_name, $callback, $num_args);
    abstract protected function execute_if_exists($table, $qry);
    //abstract protected function select_limit($sql, $num_rows=-1, $offset=-1, $inputarr=FALSE);

    public function get_error_code()
    {
         return $this->error_code;
    }
    protected function select_limit($sql, $num_rows=-1, $offset=-1, $inputarr=FALSE)
    {
        if (is_numeric($num_rows) && $num_rows > 0) {
            $sql .= " LIMIT $num_rows";
            if (is_numeric($offset) && $offset > 0) {
                $sql .= " OFFSET $offset";
            }
        }
        $this->set_emulate_prepare(FALSE);
        $rv = $this->execute_query($sql, $inputarr);
        $this->set_emulate_prepare(TRUE);
        return $rv;
    }
        
    private function _execute($query, $values=FALSE)
    {
        if (is_array($values)) {
            if (is_array(reset($values))) {  // get the first element; if it is an array, we assume we are doing bulk inserts
                foreach ($values as $v) {
                    $query->execute($v);
                }
            } else { // otherwise its justa  bind set
                $query->execute($values);
            }
        } else { // or no bound parameters are given anyway
            $query->execute();
        }
    }

    public function execute_query($sql, $values=FALSE)
    {
        $this->error_code = 0;
        if (defined('QUERY_LOG')) {
            echo_debug_file(self::QUERY_LOG_FILE, $sql, $values, ORIGINAL_PAGE);
        }
        $this->result = NULL;
        if (!$this->is_connected()) {
            $this->connect();
        }
        $query = $this->DB->prepare($sql);
        try {
            $this->_execute($query, $values);
        } catch (exception $e) {
            $errCode = $e->getCode();
            $this->error_code = $errCode;
            //echo_debug("Database problem: $errCode - " . $e->getMessage(), DEBUG_MAIN);
            // MySQL error codes (http://dev.mysql.com/doc/refman/5.1/en/error-messages-client.html)
            // 1062: Insert failed, duplicate key
            // 2006: Server has gone away
            // 1100: Table was not locked
            // 2013: Lost connection to MySQL server during query

            // PGSql error codes
            // -5: Insert failed, duplicate key

            // PDO 

            // If connection was lost, try to reconnect and then do query again:
            $dbtype = $this->databasetype;
            if ((($dbtype == 'mysql') && ($errCode == 2006 || $errCode == 2013)) ||
                (($dbtype == 'postgres' ) && ($errCode != -5))) {// sqlite should not lose connections
                write_log("Database problem: ( $errCode ) " . $e->getMessage(), LOG_WARNING);
                echo_debug_trace($e, DEBUG_DATABASE);
                try {
                    $this->connect(TRUE);
                    $this->_execute($query, $values);
                } catch (exception $e1) {
                    write_log("Database problem: ($errCode) " . $e1->getMessage(), LOG_ERR);
                    echo_debug_trace($e1, DEBUG_DATABASE);
                    throw new exception("Could not execute SQL query \"$sql\" " . $e1->getMessage());
                }
            } elseif ((($dbtype == 'mysql') && ($errCode == 1062 || $errCode == 23000)) ||(strpos($e->getMessage(),'duplicate key value violates unique constraint') !== FALSE)) {
                // ignore duplicate key error messages, they'll happen with inserting group data :/
                echo_debug("Database problem: ($errCode) " . $e->getMessage(), DEBUG_DATABASE);
                return FALSE;
            } else {
                write_log("Database Problem: ($errCode) " . $e->getMessage(), LOG_ERR);
                throw new exception("Could not execute SQL query \"$sql\" " . $e->getMessage());
            }
        }
        if (defined('QUERY_LOG')) {
            echo_debug_file(self::QUERY_LOG_FILE, 'done', FALSE, ORIGINAL_PAGE);
        }
        try {
            $rv = $query->fetchAll();
            if ($rv == array()) {
                return FALSE;
            }
        } catch(exception $e) {
            return FALSE;
        }
        return $rv;
    }

    public function update_query($table, array $columns, array $values, $where='', $input_arr=FALSE)
    {
        $col_str = '';
        reset($columns);
        if (count($columns) != count($values)) {
            throw new exception ('Could not execute update query; columns and values do not match');
        }
        foreach($columns as $c) {
            $col_str .= "\"$c\"=?, ";
        }
        if ($input_arr !== FALSE) {
            $values = array_merge($values, $input_arr);
        }
        $col_str = rtrim($col_str, ', ');
        $query = "UPDATE $table SET $col_str";
        if ($where != '') {
            $query .= " WHERE $where";
        }
        $this->set_emulate_prepare(FALSE);
        $this->execute_query($query, $values);
        $this->set_emulate_prepare(TRUE);
    }
    public function update_query_2($table, array $values, $where='', $input_arr=FALSE)
    {
        $col_str = '';
        $vals = array();
        foreach($values as $c=>$v) {
            $col_str .= "\"$c\"=?, ";
            $vals[] = $v;
        }
        if ($input_arr !== FALSE) {
            $vals = array_merge($vals, $input_arr);
        }
        $col_str = rtrim($col_str, ', ');
        $query = "UPDATE $table SET $col_str";
        if ($where != '') {
            $query .= " WHERE $where";
        }
        $this->set_emulate_prepare(FALSE);
        $this->execute_query($query, $vals);
        $this->set_emulate_prepare(TRUE);
    }

    public function insert_query($table, array $columns, array $values, $get_last_ID=FALSE)
    {
    /* values can be an array of values or an array of an array of values
       array (1, 2, 3) or
       array (array(1, 2, 4), array (3, 4, 5)) each inside array is one row
     */
        assert(is_string($table) && assert(is_bool($get_last_ID)));

        if ($values == array() ) {
            return FALSE;
        }

        $col_str = $val_str = '';

        foreach ($columns as $col) {
            $col_str .= "\"$col\", ";
            $val_str .= '?, ';
        }
        $col_str = rtrim($col_str, ', ');
        $val_str = rtrim($val_str, ', ');

        $sql = "INSERT INTO $table ($col_str) VALUES ($val_str)";
        try {
            $this->set_emulate_prepare(FALSE);
            $this->execute_query($sql, array_values($values));
            $this->set_emulate_prepare(TRUE);
            if ($get_last_ID) {
                return $this->get_last_id();
            } else {
                return FALSE;
            }
        } catch (exception $e) {
            write_log("Could not execute SQL query \"$sql\" " . $e->getMessage(), LOG_INFO);
            throw new exception("Could not execute SQL query \"$sql\" " . $e->getMessage());
        }
    }

    public function insert_query_2($table, array $values, $get_last_ID=FALSE)
    {
    /* values can be an array of values or an array of an array of values
       array (1, 2, 3) or
       array (array(1, 2, 4), array (3, 4, 5)) each inside array is one row
     */
        assert(is_string($table) && assert(is_bool($get_last_ID)));

        if ($values == array() ) {
            return FALSE;
        }

        $col_str = $val_str = '';
        $vals = array();

        foreach ($values as $col => $val) {
            $col_str .= "\"$col\", ";
            $vals[] = $val;
        }
        $col_str = rtrim($col_str, ', ');
        $val_str = str_repeat('?,', count($values) - 1) . '?';

        $sql = "INSERT INTO $table ($col_str) VALUES ($val_str)";
        try {
            $this->set_emulate_prepare(FALSE);
            $this->execute_query($sql, array_values($vals));
            $this->set_emulate_prepare(TRUE);
            if ($get_last_ID) {
                return $this->get_last_id();
            } else {
                return FALSE;
            }
        } catch (exception $e) {
            throw new exception("Could not execute SQL query \"$sql\" " . $e->getMessage());
        }
    }
    public function escape(&$object, $quoted=FALSE)
    {
        assert(is_bool($quoted));
        if (is_array($object) || is_object($object)) {
            foreach ($object as &$item) {
                $this->escape($item, $quoted);
            }
        } else {
            $object = $this->DB->quote($object);
            if (!$quoted) {
                $object = substr($object, 1, -1);
            }
        }
    }

    public function get_last_id()
    {
        return $this->DB->lastInsertId(); 
    }

    public function select_query($qry, $num_rows=-1, $offset=-1, $inputarr=FALSE)
    {
        if (is_array($num_rows)) {
            $inputarr = $num_rows; 
            $num_rows = -1;
            $offset = -1;
        } elseif (is_array($offset)) {
            $inputarr = $offset; 
            $offset = -1;
        }

        assert(is_numeric($num_rows) && is_numeric($offset) && is_string($qry));
        $this->result = NULL;
        $sql = "SELECT $qry";
        if (defined('QUERY_LOG')) {
            echo_debug_file(self::QUERY_LOG_FILE, $sql . " ($num_rows, $offset) ", FALSE, ORIGINAL_PAGE);
        }
        if (!$this->is_connected()) {
            $this->connect();
        }
        try {
            $this->result = $this->select_limit($sql, $num_rows, $offset, $inputarr);
        } catch (exception $e) {
            $errCode = $e->getCode();
            write_log("Database problem: $errCode - " . $e->getMessage(), LOG_INFO);
            // MySQL error codes (http://dev.mysql.com/doc/refman/5.1/en/error-messages-client.html)
            // 1062: Insert failed, duplicate key
            // 2006: Server has gone away
            // 1100: Table was not locked
            // 2013: Lost connection to MySQL server during query

            // PGSql error codes
            // -5: Insert failed, duplicate key

            // If connection was lost, try to reconnect and then do query again:
            $dbtype = $this->databasetype;
            if ((($dbtype == 'mysql' ) && ($errCode == 2006 || $errCode == 2013)) ||
                (($dbtype == 'postgres') && ($errCode != -5))) {// sqlite should not lose connections
                write_log("Database problem: ( $errCode ) " . $e->getMessage(), LOG_WARNING);
                try {
                    $this->connect(TRUE);
                    $this->result = $this->select_limit($sql, $num_rows, $offset, $inputarr);
                } catch (exception $e) {
                    write_log("Database problem: ( $errCode ) " . $e->getMessage(), LOG_ERR);
                    throw new exception("Could not execute SQL select query \"$sql\" " . $e->getMessage());
                }
            } else {
                throw new exception("Could not execute SQL select query \"$sql\" " . $e->getMessage());
            }
        }
        if (defined('QUERY_LOG')) {
            echo_debug_file(self::QUERY_LOG_FILE, 'done', FALSE, ORIGINAL_PAGE);
        }
        return $this->result;
    }

    public function delete_query($table, $where='', $input_arr=FALSE)
    {
        $sql = "DELETE FROM $table";
        if ($where != '') {
            $sql .= " WHERE $where";
        }

        return $this->execute_query($sql, $input_arr);
    }
}
