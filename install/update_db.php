<?php
$html = TRUE;
if (php_sapi_name() == 'cli') {
    $html = FALSE;
} 

if ($html) {
    if (!defined('ORIGINAL_PAGE')) {
        die('This file cannot be accessed directly.');
    }
} elseif (!defined('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', '');
}

if (!function_exists('echo_debug')) { function echo_debug() {} };
if (!function_exists('echo_debug_function')) { function echo_debug_function() {} };
if (!function_exists('write_log')) { function write_log($a, $b) { echo $a . "\n"; } };

$pathudb = realpath(dirname(__FILE__));
require_once "$pathudb/../functions/defines.php";
require_once "$pathudb/../functions/config_functions.php";
require_once "$pathudb/../functions/file_functions.php";
require_once "$pathudb/../functions/db.class.php";
require_once "$pathudb/../functions/db/db_abs.php";
require_once "$pathudb/../functions/db/db_mysql.php";
require_once "$pathudb/../functions/db/db_pgsql.php";
require_once "$pathudb/../functions/db/db_sqlite.php";
require_once "$pathudb/../functions/db/urd_db_structure.php";
require_once "$pathudb/../dbconfig.php";


switch($config['databasetype']) {
    case 'mysql':
    case 'mysqli':
    case 'pdo_mysql':
        $db = new DatabaseConnection_mysql($config['databasetype'], $config['db_hostname'], (isset($config['db_port']) ? $config['db_port'] : ''), 
                $config['db_user'], $config['db_password'], $config['database'], FALSE, FALSE);
        break;
    case 'postgres' :
    case 'pdo_pgsql':
    case 'postgres9':
    case 'postgres8':
    case 'postgres7':
        $db = new DatabaseConnection_psql($config['databasetype'], $config['db_hostname'], (isset($config['db_port']) ? $config['db_port'] : ''), 
                $config['db_user'], $config['db_password'], $config['database'], FALSE, FALSE);
        break;
    case 'pdo_sqlite':
    case 'sqlite':
        $db = new DatabaseConnection_sqlite($config['databasetype'], $config['db_hostname'], (isset($config['db_port']) ? $config['db_port'] : ''), 
                $config['db_user'], $config['db_password'], $config['database'], FALSE, FALSE);
        break;
    case 'mysqlt':
    default:
        throw new exception ('Database type not supported y');
}

if (!isset($quiet) ) { 
    $quiet = FALSE;
}
$engine = isset($config['db_engine']) ? $config['db_engine'] : '';
$sdb = urd_db_structure::create_db_updater($config['databasetype'], $db, $quiet, $html);
$urd_db = urd_db_structure::create_db_structure($sdb, $engine);
