<?php
/*
 * This file is part of Urd.
 * vim:ts=4:expandtab:cindent
 *
 * Urd is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * Urd is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. See the file "COPYING". If it does not
 * exist, see <http://www.gnu.org/licenses/>.
 *
 * $LastChangedDate: 2011-09-08 22:22:30 +0200 (Thu, 08 Sep 2011) $
 * $Rev: 2324 $
 * $Author: gavinspearhead $
 * $Id: db.class.php 2324 2011-09-08 20:22:30Z gavinspearhead $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathudbs = realpath(dirname(__FILE__));
require_once "$pathudbs/db_abs.php";
require_once "$pathudbs/db_mysql.php";
require_once "$pathudbs/db_pgsql.php";
require_once "$pathudbs/db_sqlite.php";

class urd_database
{
    private $tables;
    private $engine;
    public function __construct($engine)
    {
        $this->tables = array();
        $this->engine = $engine;
    }
    public function add(urd_table $table)
    {
        $table->set_engine ($this->engine);
        $this->tables[] = $table;
    }
    public function validate(db_update_abs $db_update)
    {
        foreach ($this->tables as $tbl) {
            $tbl->validate($db_update);
        }
    }
    public function add_tables(db_update_abs $db_update)
    {
        foreach ($this->tables as $tbl) {
            $tbl->add_table($db_update);
        }
    }
    public function dump()
    {
        foreach ($this->tables as $tbl) {
            $tbl->dump();
        }
    }
}

class urd_table
{
    private $tablename;
    private $auto_inc_column;
    private $collation;
    private $columns;
    private $indexes;
    private $drop_columns;
    private $drop_indexes;
    private $engine;

    public function __construct($name, $auto_inc_col, $collation)
    {
        $this->tablename = $name;
        $this->auto_inc_column = $auto_inc_col;
        $this->collation = $collation;
        $this->columns = array();
        $this->indexes = array();
        $this->drop_indexes = array();
        $this->drop_columns = array();
        $this->engine = '';
    }
    public function dump()
    {
        echo $this->tablename . ' ' . $this->collation . "\n";
        foreach ($this->columns as $c) {
            $c->dump();
        }
        foreach ($this->indexes as $c) {
            $c->dump();
        }
        foreach ($this->drop_columns as $c) {
            echo "\tDROP COLUMN $c\n";
        }
        foreach ($this->drop_indexes as $c) {
            echo "\tDROP INDEX $c\n";
        }
    }

    public function set_engine($engine)
    {
        $this->engine = $engine;
    }
    public function add_table(db_update_abs $db_update)
    {
        if ($this->auto_inc_column !== NULL) {
            $db_update->createTable($this->tablename, $this->auto_inc_column, $this->collation, $this->engine);
        }
        foreach ($this->columns as $col) {
            $col->add_column($db_update, $this->tablename);
        }
        foreach ($this->drop_columns as $col) {
            $db_update->dropColumn($col, $this->tablename);
        }
        foreach ($this->drop_indexes as $id) {
            $db_update->dropIndex($id, $this->tablename);
        }
        foreach ($this->indexes as $idx) {
            $idx->add_index($db_update, $this->tablename);
        }
    }
    public function validate(db_update_abs $db_update)
    {
        if ($this->auto_inc_column !== NULL) {
            $db_update->createTable($this->tablename, $this->auto_inc_column, $this->collation, $this->engine);
        }
        foreach ($this->columns as $col) {
            $col->validate($db_update, $this->tablename);
        }
        foreach ($this->drop_columns as $col) {
            $db_update->dropColumn($col, $this->tablename);
        }
        foreach ($this->drop_indexes as $id) {
            $db_update->dropIndex($id, $this->tablename);
        }
        foreach ($this->indexes as $idx) {
            $idx->validate($db_update, $this->tablename);
        }
    }

    public function drop_index($idx)
    {
        $this->drop_indexes[] = $idx;
    }
    public function drop_column($col)
    {
        $this->drop_columns[] = $col;
    }
    public function add_column(urd_column $col)
    {
        $this->columns[] = $col;
    }
    public function add_index(urd_index $idx)
    {
        $this->indexes[] = $idx;
    }
}

class urd_column
{
    private $column_name;
    private $type;
    private $default;
    private $notnull;
    private $collation;
    private $auto_inc;

    public function __construct ($col, $type, $def, $notnull, $collation, $auto_inc)
    {
        $this->column_name = $col;
        $this->type = $type;
        $this->default = $def;
        $this->notnull = $notnull;
        $this->collation = $collation;
        $this->auto_inc = $auto_inc;
    }
    public function dump()
    {
        echo "\tCOLUMN:\t" . $this->column_name . " " . $this->type . ' DEFAULT ' . $this->default . ' ' . ($this->notnull ? 'NOT NULL ':'NULL') . ' ' . $this->collation . "\n";
    }

    public function validate(db_update_abs $db_update, $table_name)
    {
        $db_update->validateColumn($this->column_name, $table_name, $this->type, $this->default, $this->notnull, $this->collation, $this->auto_inc);
    }
    public function add_column(db_update_abs $db_update, $table_name)
    {
        $db_update->addColumn($this->column_name, $table_name, $this->type, $this->default, $this->notnull, $this->collation, $this->auto_inc);
    }
}

class urd_index
{
    private $index_name;
    private $columns;
    private $type;
    public function __construct ($idx, $type, array $cols)
    {
        $this->index_name = $idx;
        $this->columns = $cols;
        $this->type = $type;
    }
    public function dump()
    {
        echo "\tINDEX:\t" . $this->index_name . ' ' . $this->type . ' ' .implode (', ', $this->columns) . "\n";
    }
    public function validate(db_update_abs $db_update, $table_name)
    {
        $db_update->validateIndex($this->index_name, $this->type, $table_name, $this->columns);
    }
    public function add_index(db_update_abs $db_update, $table_name)
    {
        $db_update->addIndex($this->index_name, $this->type, $table_name, $this->columns);
    }
}

class urd_db_structure {
    static function create_db_structure(db_update_abs $db_update, $engine)
    {
        $urd_db = new urd_database($engine);

        $t = new urd_table('groups', 'ID', 'utf8');
        $t->add_column(new urd_column('ID', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('server_ID', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('name', 'VARCHAR(255)', '', TRUE, 'utf8', '' ));
        $t->add_column(new urd_column('last_updated', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('active', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('adult', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('description', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('postcount', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('last_record', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('first_record', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('mid_record', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('extset_update', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('expire', 'UNSIGNED INTEGER', '7', TRUE, '', ''));
        $t->add_column(new urd_column('refresh_time', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('refresh_period', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('setcount', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('minsetsize', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('maxsetsize', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('groups_prim', 'PRIMARY', array('ID')));
        $t->add_index(new urd_index('groups_name', '', array('name')));
        $t->add_index(new urd_index('idx_active', '', array('active')));
        $urd_db->add($t);

        $t = new urd_table('setdata', 'tmp', 'utf8');
        $t->add_column(new urd_column('ID', 'CHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('groupID', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('subject', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('articlesmax', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('binaries', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('date', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('size', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('idx_ID', 'PRIMARY', array('ID')));
        $t->add_index(new urd_index('idx_groupID', '', array('groupID')));
        $t->add_index(new urd_index('idx_date', '', array('date')));
        $t->drop_column('tmp');
        $urd_db->add($t);

        $t = new urd_table('extsetdata', 'tmp', 'utf8');
        $t->add_column(new urd_column('setID', 'CHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('name', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('value', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('committed', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('type', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('idx_ext_set_prim', 'PRIMARY', array('setID', 'name', 'type')));
        $t->add_index(new urd_index('idx_setid_extset', '', array('setID')));
        $t->add_index(new urd_index('idx_name', '', array('name')));
        $t->add_index(new urd_index('idx_type', '', array('type')));
        $t->drop_column('tmp');
        $urd_db->add($t);

        $t = new urd_table('preferences', 'ID', 'utf8');
        $t->add_column(new urd_column('ID', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('userID', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('option', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('value', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_index(new urd_index('pref_prim', 'PRIMARY', array('ID')));
        $t->add_index(new urd_index('idx_option', '', array('option')));
        $t->add_index(new urd_index('idx_userID', '', array('userID')));
        $urd_db->add($t);

        $t = new urd_table('usersetinfo', 'tmp', 'utf8');
        $t->add_column(new urd_column('setID', 'CHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('userID', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('type', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('statusread', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('statuskill', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('statusint', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('statusnzb', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('idx_setid', 'PRIMARY', array('setID', 'userID', 'type')));
        $t->add_index(new urd_index('idx_usi_userID', '', array('userID')));
        $t->drop_column('tmp');
        $urd_db->add($t);

        $t = new urd_table('downloadinfo', 'ID', 'utf8');
        $t->add_column(new urd_column('ID', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('name', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('password', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('size', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('done_size', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('stat_id', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('unpar', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('unrar', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('subdl', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('delete_files', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('first_run', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('status', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', '')); // check for size
        $t->add_column(new urd_column('destination', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('dl_dir', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('start_time', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('add_setname', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('position', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('preview', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', '')); // check for size
        $t->add_column(new urd_column('hidden', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('groupid', 'UNSIGNED BIGINTEGER', '0', TRUE, '', '')); // why ??
        $t->add_column(new urd_column('download_par', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('lock', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('comment', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_index(new urd_index('dlinfo_prim', 'PRIMARY', array('ID')));
        $t->drop_column('username');
        $urd_db->add($t);

        $t = new urd_table('users', 'ID', 'utf8');
        $t->add_column(new urd_column('ID', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('name', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('fullname', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('email', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('pass', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('salt', 'VARCHAR(16)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('isadmin', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('active', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('rights', 'CHAR(8)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('ipaddr', 'VARCHAR(80)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('last_login', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('last_active', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('token', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('regtime', 'BIGINTEGER', '0', TRUE, '', '')); // why ??
        $t->add_column(new urd_column('failed_login_count', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('failed_login_time', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('privatekey', 'TEXT', '', TRUE, 'ascii', ''));
        $t->add_column(new urd_column('publickey', 'TEXT', '', TRUE, 'ascii', ''));
        $t->add_index(new urd_index('user_prim', 'PRIMARY', array('ID')));
        $t->add_index(new urd_index('idx_username', 'UNIQUE', array('name')));
        $urd_db->add($t);

        $t = new urd_table('downloadarticles', 'ID', 'utf8');
        $t->add_column(new urd_column('ID', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('downloadID', 'UNSIGNED BIGINTEGER', '0', TRUE, '', '')); // why ??
        $t->add_column(new urd_column('groupID', 'UNSIGNED BIGINTEGER', '0', TRUE, '', '')); // why ??
        $t->add_column(new urd_column('binaryID', 'CHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('partnumber', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('messageID', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('name', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('size', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('stat_id', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('status', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', '')); // check for size
        $t->add_index(new urd_index('dlart_prim', 'PRIMARY', array('ID')));
        $t->add_index(new urd_index('idx_downloadID', '', array('downloadID')));
        $t->add_index(new urd_index('idx_status', '', array('status')));
        $urd_db->add($t);

        $t = new urd_table('queueinfo', 'ID', 'utf8');
        $t->add_column(new urd_column('ID', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('priority', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', '')); // check for size
        $t->add_column(new urd_column('description', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('command_id', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('urdd_id', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('progress', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', '')); // check for size
        $t->add_column(new urd_column('ETA', 'UNSIGNED INTEGER', '0', TRUE, '', '')); // check for size
        $t->add_column(new urd_column('comment', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('username', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('userid', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('paused', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('status', 'VARCHAR(25)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('starttime', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('lastupdate', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('restart', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('q_prim', 'PRIMARY', array('ID')));
        $t->add_index(new urd_index('idx_queue_status', '', array('status')));
//        $t->add_index(new urd_index('idx_desc_status', '', array('description(80)')));
        $t->drop_column('directory');
        $t->drop_index('idx_desc_status');
        $urd_db->add($t);

        $t = new urd_table('schedule', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('command', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('at_time', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('interval', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('sched_prim', 'PRIMARY', array('id')));
        $t->drop_column('username');
        $urd_db->add($t);

        $t = new urd_table('searchbuttons', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('search_url', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('name', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_index(new urd_index('search_prim', 'PRIMARY', array('id')));
        $t->drop_column('image_url');
        $urd_db->add($t);

        $t = new urd_table('usergroupinfo', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('groupid', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('visible', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('minsetsize', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('maxsetsize', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('category', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('last_update_seen', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('ugrpinfo_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('idx_ugroupid', '', array('groupid')));
        $t->add_index(new urd_index('idx_ugcategory', '', array('category')));
        $urd_db->add($t);

        $t = new urd_table('usenet_servers', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('username', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('password', 'VARCHAR(1024)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('threads', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('name', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('hostname', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('port', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('secure_port', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('authentication', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('posting', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('compressed_headers', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('priority', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('connection', 'VARCHAR(20)', 'off', TRUE, 'utf8', '')); // fix quotes
        $t->add_index(new urd_index('srv_prim', 'PRIMARY', array('id')));
        $t->drop_column('retention');
        $t->drop_column('active');
        $urd_db->add($t);

        $t = new urd_table('stats', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('action', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('value', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('timestamp', 'timestamp', 'NOW', TRUE, '', '')); // default value
        $t->add_index(new urd_index('stats_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('stats_act', '', array('action')));
        $urd_db->add($t);

        $t = new urd_table('rss_urls', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('name', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('url', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('adult', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('subscribed', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('expire', 'UNSIGNED INTEGER', '7', TRUE, '', ''));
        $t->add_column(new urd_column('refresh_time', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('refresh_period', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('feedcount', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('last_updated', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('extset_update', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('username', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('password', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_index(new urd_index('rssurl_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('idx_subscribed', '', array('subscribed')));
        $urd_db->add($t);

        $t = new urd_table('rss_sets', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('setid', 'CHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('rss_id', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('setname', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('timestamp', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('description', 'VARCHAR(1024)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('summary', 'VARCHAR(1024)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('size', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('nzb_link', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
        $t->add_index(new urd_index('rssset_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('idx_rsssetid', '', array('setid')));
        $t->add_index(new urd_index('idx_rsssid', '', array('rss_id')));
        $t->add_index(new urd_index('idx_rsstimestamp', '', array('timestamp')));
        $urd_db->add($t);

        $t = new urd_table('userfeedinfo', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('feedid', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('visible', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('minsetsize', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('maxsetsize', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('category', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('last_update_seen', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('ufi_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('idx_feedid', '', array('feedid')));
        $urd_db->add($t);

        $t = new urd_table('merged_sets', 'tmp', 'utf8');
        $t->add_column(new urd_column('new_setid', 'CHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('old_setid', 'CHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('type', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('committed', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('merge_prim', 'PRIMARY', array('new_setid', 'old_setid', 'type')));
        $t->drop_column('tmp');
        $urd_db->add($t);
        
        $t = new urd_table('spot_postinfo', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('poster_name', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('category', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('subcat', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('subcats', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('nzb_file', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('message_id', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('image_file', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('image_width', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('image_height', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('subject', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('tag', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('description', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('url', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('stat_id', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('size', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('start_time', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('status', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('postsi_prim', 'PRIMARY', array('id')));
        $t->drop_column('poster_id');
        $urd_db->add($t);

        $t = new urd_table('postinfo', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('groupid', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('groupid_nzb', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('poster_id', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('poster_name', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('src_dir', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('tmp_dir', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('nzb_file', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('image_file', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('subject', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('recovery_par', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('filesize_rar', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('delete_files', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('stat_id', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('file_count', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('size', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('start_time', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('status', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('posti_prim', 'PRIMARY', array('id')));
        $urd_db->add($t);

        $t = new urd_table('post_files', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('postid', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('filename', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('file_idx', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('articleid', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('rarfile', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('rar_idx', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('size', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('status', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('postf_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('idx_post_id', '', array('postid')));
        $t->add_index(new urd_index('idx_post_status', '', array('status')));
        $urd_db->add($t);

        $t = new urd_table('post_messages', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('groupid', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('subject', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('poster_id', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('poster_name', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('headers', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('message', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_index(new urd_index('postm_prim', 'PRIMARY', array('id')));
        $urd_db->add($t);

        $t = new urd_table('categories', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('name', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_index(new urd_index('cat_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('cat_userid', '', array('userid')));
        $urd_db->add($t);

        $t = new urd_table('nfo_files', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('setID', 'CHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('binaryID', 'CHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('groupID', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('nfo_prim', 'PRIMARY', array('id')));
        $urd_db->add($t);

        $t = new urd_table('spot_reports', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('message_id', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('spotid', 'CHAR(32)', '0', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('reference', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('stamp', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('spotreport_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('spotreport_stamp', '', array('stamp')));
        $t->add_index(new urd_index('spotreport_spotid', '', array('spotid')));
        $urd_db->add($t);

        $t = new urd_table('spot_whitelist', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('spotter_id', 'VARCHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('source', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('status', 'UNSIGNED INTEGER', '1', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('spotwl_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('spotwlid', '', array('spotter_id')));
        $urd_db->add($t);

        $t = new urd_table('spot_blacklist', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('spotter_id', 'VARCHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('source', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('status', 'UNSIGNED INTEGER', '1', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('spotbl_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('spotblid', '', array('spotter_id')));
        $urd_db->add($t);

        $t = new urd_table('spot_messages', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('message_id', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_index(new urd_index('spotm_prim', 'PRIMARY', array('id')));
        $urd_db->add($t);

        $t = new urd_table('spot_tmp', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $urd_db->add($t);

        $t = new urd_table('spot_images', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('spotid', 'CHAR(32)', '0', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('image', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('image_file', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('stamp', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('fetched', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
        $t->add_index(new urd_index('spoti_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('spoti_spotid', '', array('spotid')));
        $t->add_index(new urd_index('spoti_stamp', '', array('stamp')));
        $urd_db->add($t);

        $t = new urd_table('spots', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('messageid', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('spotid', 'CHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('spotter_id', 'VARCHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('category', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('subcat', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('poster', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('title', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('tag', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('description', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('nzbs', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('subcata', 'VARCHAR(128)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('subcatb', 'VARCHAR(128)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('subcatc', 'VARCHAR(128)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('subcatd', 'VARCHAR(128)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('subcatz', 'VARCHAR(128)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('url', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('stamp', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('size', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('reports', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('comments', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('rating', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('rating_count', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('reference', 'VARCHAR(32)', '', TRUE, 'utf8', ''));
        $t->add_index(new urd_index('spots_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('spot_stamp', '', array('stamp')));
        $t->add_index(new urd_index('spot_size', '', array('size')));
        $t->add_index(new urd_index('spot_spotid', 'UNIQUE', array('spotid')));
        $t->add_index(new urd_index('spot_cat', '', array('category')));
        $t->add_index(new urd_index('spot_msgid', '', array('messageid')));
        $t->drop_column('image');
        $urd_db->add($t);

        $t = new urd_table('spot_comments', 'id', 'utf8');
        $t->add_column(new urd_column('id', 'BIGSERIAL', '', TRUE, '', ''));
        $t->add_column(new urd_column('message_id', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('spotid', 'CHAR(32)', '0', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('comment', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('from', 'VARCHAR(128)', '', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('stamp', 'BIGINTEGER', '0', TRUE, '', ''));
        $t->add_column(new urd_column('userid', 'VARCHAR(32)', '0', TRUE, 'utf8', ''));
        $t->add_column(new urd_column('user_avatar', 'TEXT', '', TRUE, 'utf8', ''));
        $t->add_index(new urd_index('spotc_prim', 'PRIMARY', array('id')));
        $t->add_index(new urd_index('spotc_stamp', '', array('stamp')));
        $t->add_index(new urd_index('spotc_spotid', '', array('spotid')));
        $urd_db->add($t);
        $part_tables = $db_update->get_tables('parts_%');
        $bin_tables = $db_update->get_tables('binaries_%');
        foreach ($part_tables as $tn) {
            $t = new urd_table($tn, NULL, 'utf8');
            $t->add_column(new urd_column('ID', 'BIGSERIAL', '', TRUE, '', ''));
            $t->add_column(new urd_column('binaryID', 'CHAR(32)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('messageID', 'VARCHAR(255)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('subject', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('fromname', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('date', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
            $t->add_column(new urd_column('partnumber', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
            $t->add_column(new urd_column('size', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
            $t->add_index(new urd_index('idx_binaries_id_' . $tn, 'PRIMARY', array('ID')));
            $t->add_index(new urd_index('idx_binaryID_' . $tn, '', array('binaryID')));
            $t->add_index(new urd_index('idx_date_' . $tn, '', array('date')));
            $t->drop_column('dirty');
            $t->drop_index('dirty_idx');
            $t->drop_index('binaryID_idx');
            $t->drop_index('date_idx');
            $urd_db->add($t);
        }
        foreach ($bin_tables as $tn) {
            $t = new urd_table($tn, NULL, 'utf8');
            $t->add_column(new urd_column('binaryID', 'CHAR(32)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('setID', 'CHAR(32)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('subject', 'VARCHAR(512)', '', TRUE, 'utf8', ''));
            $t->add_column(new urd_column('date', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
            $t->add_column(new urd_column('bytes', 'UNSIGNED BIGINTEGER', '0', TRUE, '', ''));
            $t->add_column(new urd_column('totalParts', 'UNSIGNED INTEGER', '0', TRUE, '', ''));
            $t->add_column(new urd_column('dirty', 'UNSIGNED SMALLINTEGER', '0', TRUE, '', ''));
            $t->add_index(new urd_index($tn . '_pkey', 'PRIMARY', array('binaryID')));
            $t->add_index(new urd_index('idx_dirty_'. $tn, '', array('dirty')));
            $t->add_index(new urd_index('idx_setID_'. $tn, '', array('setID')));
            $t->drop_column('fromname');
            $t->drop_index('dirty_idx');
            $t->drop_index('setID_idx');
            $urd_db->add($t);
        }

        $urd_db->validate($db_update);
        $db_update->confirm_version();
        config_cache::clear_all();
        return $urd_db;
    }

    static function create_db_updater($dbtype, DatabaseConnection $db, $quiet=TRUE, $html=FALSE)
    {
        switch ($dbtype) {
            case 'mysql':
            case 'mysqlt':
            case 'mysqli':
            case 'pdo_mysql':
                $sdb = new db_update_mysql($db, $quiet, $html);
                break;

            case 'postgres':
            case 'postgres9':
            case 'postgres8':
            case 'postgres7':
            case 'pdo_pgsql':
                $sdb = new db_update_pgsql($db, $quiet, $html);
                break;
            case 'pdo_sqlite':
            case 'sqlite':
                $sdb = new db_update_sqlite($db, $quiet, $html);
                break;
            default:
                throw new exception ('Database type not supported');
        }

        return $sdb;
    }
}
