<?php
define('ORIGINAL_PAGE', '');

function echo_debug($e, $b) {echo $e . "\n"; }
function echo_debug_function() {}
function write_log($a, $b) { echo $a . "\n"; }
error_reporting(E_ALL | E_STRICT| E_DEPRECATED);

include '../db.class.php.inc';
include 'db_abs.php';
include 'db_mysql.php';
include 'db_pgsql.php';
include 'db_sqlite.php';
include 'urd_db_structure.php';

$groupid = '2179';
/*
$qry[] = "DROP TABLE IF EXISTS \"binaries_$groupid\"";
$qry[] = "CREATE TABLE \"binaries_$groupid\" ( "
    .    "\"binaryID\" char(32) NOT NULL default '', "
    .    "\"subject\" varchar(512) NOT NULL default '', "
    .    "\"date\" int(16) unsigned NOT NULL default '0', "
    .    "\"bytes\" bigint(16) unsigned NOT NULL default '0', "
    .    "\"totalParts\" int(16) unsigned NOT NULL default '0', "
    .    "\"setID\" char(32) NOT NULL default '0', "
    .    "\"dirty\" tinyint(1) unsigned NOT NULL default '0', "
    .    "PRIMARY KEY (\"binaryID\"),"
    .    "KEY \"dirty_idx\" (\"dirty\"),"
    .    "KEY \"setID_idx\" (\"setID\") "
    .    ') ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';
$qry[] = "DROP TABLE IF EXISTS \"parts_$groupid\"";
$qry[] = "CREATE TABLE \"parts_$groupid\" ("
    .    "\"ID\" bigint(16) unsigned NOT NULL auto_increment,"
    .    "\"binaryID\" char(32) NOT NULL default '',"
    .    "\"messageID\" varchar(255) NOT NULL default '',"
    .    "\"subject\" varchar(512) NOT NULL default '',"
    .    "\"fromname\" varchar(512) NOT NULL default '',"
    .    "\"date\" int(16) unsigned NOT NULL default '0',"
    .    "\"partnumber\" bigint(16) unsigned NOT NULL default '0',"
    .    "\"size\" bigint(16) unsigned NOT NULL default '0',"
    .    "PRIMARY KEY (\"ID\"),"
    .    "KEY \"binaryID_idx\" (\"binaryID\"),"
    .    "KEY \"date_idx\" (\"date\")"
    .    ') ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COLLATE=utf8_general_ci';
*/
                $qry[] = "CREATE TABLE \"binaries_$groupid\" ("
                    . " \"binaryID\" character(32) NOT NULL DEFAULT '',"
                    . " \"subject\" character varying(512) DEFAULT '' NOT NULL,"
                    . " \"date\" bigint DEFAULT '0' NOT NULL,"
                    . " \"bytes\" bigint DEFAULT '0' NOT NULL,"
                    . " \"totalParts\" bigint DEFAULT '0' NOT NULL,"
                    . " \"setID\" character(32) DEFAULT '0' NOT NULL,"
                    . " \"dirty\" smallint DEFAULT '0' NOT NULL, "
                    . " PRIMARY KEY (\"binaryID\")"
                    . ')';
                $qry[] = "CREATE INDEX \"setID_{$groupid}_idx\" ON \"binaries_$groupid\" (\"setID\")";
                $qry[] = "CREATE INDEX \"dirty_b_{$groupid}_idx\" ON \"binaries_$groupid\" (\"dirty\")";

                $qry[] = "CREATE TABLE \"parts_$groupid\" ("
                    . " \"ID\" bigserial NOT NULL,"
                    . " \"binaryID\" character(32) DEFAULT '' NOT NULL,"
                    . " \"messageID\" character varying(255) DEFAULT '' NOT NULL,"
                    . " \"subject\" character varying(512) DEFAULT '' NOT NULL,"
                    . " \"fromname\" character varying(512) DEFAULT '' NOT NULL,"
                    . " \"date\" bigint DEFAULT '0' NOT NULL,"
                    . " \"partnumber\" bigint DEFAULT '0' NOT NULL,"
                    . " \"size\" bigint DEFAULT '0' NOT NULL,"
                    . " PRIMARY KEY (\"ID\")"
                    . ')';
                $qry[] = "CREATE INDEX \"binaryID_{$groupid}_idx\" ON \"parts_$groupid\" (\"binaryID\")";
                $qry[] = "CREATE INDEX \"date_{$groupid}_idx\" ON \"parts_$groupid\" (\"date\")";

//$dbtype = 'mysqli';
//$dbtype = 'pdo_sqlite';
 $dbtype = 'postgres8';

//
try {
//$db = new DatabaseConnection('mysqli', 'localhost', '', 'root', 'utsotbm0' , 'urd_test', FALSE, FALSE);
$db = new DatabaseConnection($dbtype, 'localhost', '', 'harm', '' , 'urd_test', FALSE, FALSE);
    //$db = new DatabaseConnection('mysqli', 'localhost', '', 'root', 'utsotbm0' , 'urd_test', FALSE, FALSE);
  //  $db = new DatabaseConnection($dbtype, 'localhost', '', 'harm', '' , 'urd_test', FALSE, FALSE);
    //$db = new DatabaseConnection($dbtype, 'localhost', '', 'urd_user', '' , '/tmp/urd_test.db', TRUE, TRUE);
} catch (exception $e) {
    var_dump($e);
}

//foreach($qry as $q) $db->execute_query($q);
//die;

$sdb = create_db_updater($dbtype, $db, FALSE);

$urd_db = create_db_structure($sdb);

//$sdb->updateSchema() ;
//
//$urd_db->validate($sdb);
//$urd_db->dump();
