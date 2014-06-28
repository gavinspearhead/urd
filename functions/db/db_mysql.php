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
 * $LastChangedDate$
 * $Rev$
 * $Author$
 * $Id$
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class db_update_mysql extends db_update_abs
{
    /*
     * optimaliseer/analyseer een aantal tables welke veel veranderen,
     * deze functie wijzigt geen data!
     */

    /* converteert een "URD" datatype naar een mysql datatype */
    public function swDtToNative($colType)
    {
        switch (strtoupper($colType)) {
        case 'INTEGER'				: $colType = 'int(11)'; break;
        case 'UNSIGNED INTEGER'		: $colType = 'int(10) unsigned'; break;
        case 'BIGINTEGER'			: $colType = 'bigint(20)'; break;
        case 'UNSIGNED BIGINTEGER'	: $colType = 'bigint(20) unsigned'; break;
        case 'SMALLINTEGER'  		: $colType = 'tinyint(3)'; break;
        case 'UNSIGNED SMALLINTEGER': $colType = 'tinyint(4) unsigned'; break;
        }

        return $colType;
    } # swDtToNative

    /* converteert een mysql datatype naar een "URD" datatype */
    public function nativeDtToSw($colInfo)
    {
        switch (strtolower($colInfo)) {
        case 'tinyint(3)'			: $colInfo = 'SMALLINTEGER'; break;
        case 'tinyint(4) unsigned'	: $colInfo = 'UNSIGNED SMALLINTEGER'; break;
        case 'int(11)'				: $colInfo = 'INTEGER'; break;
        case 'int(10) unsigned'		: $colInfo = 'UNSIGNED INTEGER'; break;
        case 'bigint(20)'			: $colInfo = 'BIGINTEGER'; break;
        case 'bigint(20) unsigned'	: $colInfo = 'UNSIGNED BIGINTEGER'; break;
        }

        return $colInfo;
    } # nativeDtToSw

    /* controleert of een index bestaat */
    public function indexExists($idxname, $tablename)
    {
        $q = $this->db->execute_query('SHOW INDEXES FROM ' . $tablename . " WHERE key_name = '$idxname'");

        return !empty($q);
    } # indexExists

    /* controleert of een column bestaat */
    public function columnExists($tablename, $colname)
    {
        $q = $this->db->execute_query('SHOW COLUMNS FROM ' . $tablename . " WHERE Field = '$colname'");

        return !empty($q);
    } # columnExists

    private function create_indexlist($cols)
    {
        $new_cols = array();
        foreach ($cols as $col) {
            preg_match('/([[:alpha:]_]+)/', $col, $m1);
            $name = (isset($m1[1])? $m1[1]: '');
            preg_match('/\(([[:digit:]]+)\)/', $col, $m2);
            $len = (isset($m2[1])? $m2[1]: '');

            $c = '"' .  $name . '"';
            if ($len != '') {
                $c .= "($len) ";
            }
            $new_cols[] = $c;
        }

        return implode(',', $new_cols);
    }

    /* Add an index, kijkt eerst wel of deze index al bestaat */
    public function addIndex($idxname, $idxType, $tablename, array $colList)
    {
        $cols = $this->create_indexlist($colList);
        if (!$this->indexExists($idxname, $tablename)) {
            if ($idxType == 'UNIQUE') {
                $this->db->execute_query('ALTER IGNORE TABLE ' . $tablename . ' ADD ' . $idxType . ' INDEX ' . $idxname . "( $cols );");
            } elseif ($idxType == 'PRIMARY') {
                if ($this->indexExists('PRIMARY', $tablename)) {
                    $this->db->execute_query('ALTER TABLE ' . $tablename . " DROP PRIMARY KEY, ADD PRIMARY KEY ( $cols );");
                } else {
                    $this->db->execute_query('ALTER TABLE ' . $tablename . " ADD PRIMARY KEY ($cols);");
                }
            } else {
                $this->db->execute_query('ALTER TABLE ' . $tablename . ' ADD ' . $idxType . ' INDEX ' . $idxname . "( $cols);");
            } 
        }
    } 

    /* controleert of een full text index bestaat */
    public function ftsExists($ftsname, $tablename, array $colList)
    {
        foreach ($colList as $num => $col) {
            $indexInfo = $this->getIndexInfo($ftsname . '_' . $num, $tablename);

            if ((empty($indexInfo)) || (strtolower($indexInfo[0]['column_name']) != strtolower($col))) {
                return FALSE;
            }
        }

        return TRUE;
    } 

    /* maakt een full text index aan */
    public function createFts($ftsname, $tablename, array $colList)
    {
        foreach ($colList as $num => $col) {
            $indexInfo = $this->getIndexInfo($ftsname . '_' . $num, $tablename);

            if ((empty($indexInfo)) || (strtolower($indexInfo[0]['column_name']) != strtolower($col))) {
                $this->dropIndex($ftsname . '_' . $num, $tablename);
                $this->addIndex($ftsname . '_' . $num, 'FULLTEXT', $tablename, array($col));
            }
        }
    } 

    /* dropt en fulltext index */
    public function dropFts($ftsname, $tablename, array $colList)
    {
        foreach ($colList as $num => $col) {
            $this->dropIndex($ftsname . '_' . $num, $tablename);
        }
    } 

    /* geeft FTS info terug */
    public function getFtsInfo($ftsname, $tablename, array $colList)
    {
        $ftsList = array();

        foreach ($colList as $num => $col) {
            $tmpIndex = $this->getIndexInfo($ftsname . '_' . $num, $tablename);

            if (!empty($tmpIndex)) {
                $ftsList[] = $tmpIndex[0];
            }
        }

        return $ftsList;
    } # getFtsInfo

    /* dropt een index als deze bestaat */
    public function dropIndex($idxname, $tablename)
    {
        # Check eerst of de tabel bestaat, anders kan
        # indexExists mislukken en een fatal error geven
        if (!$this->tableExists($tablename)) {
            return;
        }
        if ($this->indexExists($idxname, $tablename)) {
            if ($idxname == 'PRIMARY') {
                $this->db->execute_query("ALTER TABLE $tablename DROP PRIMARY KEY");
            } else {
                $this->db->execute_query('DROP INDEX ' . $idxname . ' ON ' . $tablename);
            }
        }
    } 

    /* voegt een column toe, kijkt wel eerst of deze nog niet bestaat */
    public function addColumn($colName, $tablename, $colType, $colDefault=NULL, $notNull=TRUE, $collation=NULL, $auto_inc=NULL)
    {
        if (!$this->columnExists($tablename, $colName)) {
            # zet de DEFAULT waarde
            #
            $def_setting = $colDefault;
            if (strtolower($colType) == 'timestamp') {
                switch (strtolower($colDefault)) {
                case 'now' : $def_setting = 'DEFAULT CURRENT_TIMESTAMP';
                break;
                }
            } elseif (!is_null($colDefault)) {
                $def_setting = 'DEFAULT \'' . $colDefault . "'";
            }
            # converteer het kolom type naar het type dat wij gebruiken
            $colType = $this->swDtToNative($colType);
            if (strtoupper($colType) == 'BIGSERIAL') {
                $colType = 'BIGINT(20) UNSIGNED';
                $auto_inc = 'auto_increment';
                $def_setting = '';
            } else  if (strtoupper($colType) == 'SERIAL') {
                $colType = 'INT(10) UNSIGNED';
                $auto_inc = 'auto_increment';
                $def_setting = '';
            }

            # Zet de collation om naar iets dat we begrijpen
            switch (strtolower($collation)) {
            case 'utf8'		: $colSetting = 'CHARACTER SET utf8 COLLATE utf8_general_ci'; break;
            case 'ascii'	: $colSetting = 'CHARACTER SET ascii'; break;
            case ''			: $colSetting = ''; break;
            default			: throw new exception('Invalid collation setting');
            }

            # en zet de 'NOT NULL' om naar een string
            switch ($notNull) {
            case TRUE		: $nullStr = 'NOT NULL'; break;
            default			: $nullStr = '';
            }

            $this->db->execute_query('ALTER TABLE ' . $tablename . ' ADD COLUMN("' . $colName . "\" " . $colType . ' ' . $colSetting . ' ' . $def_setting . ' ' . $nullStr . ' ' . $auto_inc . ')');
        }
    } 

    /* wijzigt een column - controleert *niet* of deze voldoet aan het prototype */
    public function modifyColumn($colName, $tablename, $colType, $colDefault=NULL, $notNull=TRUE, $collation=NULL, $what=NULL, $auto_inc=NULL)
    {
        # zet de DEFAULT waarde
        #
        $def_setting = $colDefault;
        if ($colType == 'timestamp') {
            switch (strtolower($colDefault)) {
            case 'now' : $def_setting = 'DEFAULT CURRENT_TIMESTAMP';
            break;
            }
        } elseif (!is_null($colDefault)) {
            $def_setting = 'DEFAULT \'' . $colDefault . "'";
        }
        if (strtoupper($colType) == 'BIGSERIAL') {
            $colType = 'BIGINT(20) UNSIGNED';
            $auto_inc = 'auto_increment';
            $def_setting = '';
        } else  if (strtoupper($colType) == 'SERIAL') {
            $colType = 'INT(10) UNSIGNED';
            $auto_inc = 'auto_increment';
            $def_setting = '';
        }

        # converteer het kolom type naar het type dat wij gebruiken
        $colType = $this->swDtToNative($colType);

        # Zet de collation om naar iets dat we begrijpen
        switch (strtolower($collation)) {
        case 'utf8'		: $colSetting = 'CHARACTER SET utf8 COLLATE utf8_general_ci'; break;
        case 'ascii'	: $colSetting = 'CHARACTER SET ascii'; break;
        case ''			: $colSetting = ''; break;
        default			: throw new Exception('Invalid collation setting');
        }

        # en zet de 'NOT NULL' om naar een string
        switch ($notNull) {
        case TRUE		: $nullStr = 'NOT NULL'; break;
        default			: $nullStr = '';
        }

        $this->db->execute_query('ALTER TABLE ' . $tablename .  " MODIFY COLUMN \"" . $colName . "\" " . $colType . " " . $colSetting . " " . $def_setting . " " . $nullStr . " " . $auto_inc );
        echo ('ALTER TABLE ' . $tablename .  " MODIFY COLUMN \"" . $colName . "\" " . $colType . " " . $colSetting . " " . $def_setting . " " . $nullStr . " " . $auto_inc );
    }

    /* dropt een kolom (mits db dit ondersteunt) */
    public function dropColumn($colName, $tablename)
    {
        if ($this->columnExists($tablename, $colName)) {
            $this->db->execute_query('ALTER TABLE ' . $tablename . ' DROP COLUMN ' . $colName);
        }
    } 

    /* controleert of een tabel bestaat */
    public function tableExists($tablename)
    {
        $q = $this->db->execute_query("SHOW TABLES LIKE '$tablename'");

        return !empty($q);
    } 

    /* ceeert een lege tabel met enkel een ID veld, collation kan UTF8 of ASCII zijn */
    public function createTable($tablename, $index_field, $collation, $engine)
    {
        if (!$this->tableExists($tablename, $index_field)) {
            switch (strtolower($collation)) {
            case 'utf8'		: $colSetting = 'CHARSET=utf8 COLLATE=utf8_general_ci'; break;
            case 'ascii'	: $colSetting = 'CHARSET=ascii'; break;
            default			: throw new Exception('Invalid collation setting');
            }
            switch (strtolower($engine)) {
            default         : throw new Exception('Invalid engine setting');
            case ''         : $enginesetting = ''; break;
            case 'myisam'   : $enginesetting = 'ENGINE MyIsam'; break;
            case 'innodb'   : $enginesetting = 'ENGINE InnoDB'; break;
            }
            $this->db->execute_query("CREATE TABLE $tablename ($index_field BIGINT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT) $colSetting $enginesetting");
        }
    } 

    /* drop een table */
    public function dropTable($tablename)
    {
        if ($this->tableExists($tablename)) {
            $this->db->execute_query('DROP TABLE ' . $tablename);
        }
    } 

    /* verandert een storage engine (concept dat enkel mysql kent :P ) */
    public function alterStorageEngine($tablename, $engine)
    {
        $q = $this->db->execute_query("SELECT ENGINE
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$tablename'");

        if (strtolower($q) != strtolower($engine)) {
            $this->db->execute_query("ALTER TABLE $tablename ENGINE= $engine");
        }
    } 

    /* rename een table */
    public function renameTable($tablename, $newTableName)
    {
        $this->db->execute_query('RENAME TABLE ' . $tablename . ' TO ' . $newTableName);
    } # renameTable

    /* dropped een foreign key constraint */
    public function dropForeignKey($tablename, $colname, $reftable, $refcolumn, $action)
    {
        $q = $this->db->execute_query("SELECT CONSTRAINT_NAME FROM information_schema.key_column_usage
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '" . $tablename . "'
            AND COLUMN_NAME = '" . $colname . "'
            AND REFERENCED_TABLE_NAME = '" . $reftable . "'
            AND REFERENCED_COLUMN_NAME = '" . $refcolumn . "'");
        if (!empty($q)) {
            foreach ($q as $res) {
                $this->db->execute_query('ALTER TABLE ' . $tablename . ' DROP FOREIGN KEY ' . $res['CONSTRAINT_NAME']);
            }
        }
    } # dropForeignKey

    /* creeert een foreign key constraint */
    public function addForeignKey($tablename, $colname, $reftable, $refcolumn, $action)
    {
        $q = $this->db->execute_query("SELECT * FROM information_schema.key_column_usage
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '$tablename'
            AND COLUMN_NAME = '$colname'
            AND REFERENCED_TABLE_NAME = '$reftable'
            AND REFERENCED_COLUMN_NAME = '$refcolumn'");
        if (empty($q)) {
            $this->db->execute_query('ALTER TABLE ' . $tablename . ' ADD FOREIGN KEY (' . $colname . ') REFERENCES ' . $reftable . ' (' . $refcolumn . ') ' . $action);
        }
    } # addForeignKey

    /* Geeft, in een afgesproken formaat, de index formatie terug */
    public function getColumnInfo($tablename, $colname)
    {
        $q = $this->db->execute_query("SELECT COLUMN_NAME,
            COLUMN_DEFAULT,
            IS_NULLABLE,
            COLUMN_TYPE,
            CHARACTER_SET_NAME,
            COLLATION_NAME,
            EXTRA
            FROM information_schema.COLUMNS
            WHERE TABLE_NAME = '$tablename'
            AND COLUMN_NAME = '$colname'
            AND TABLE_SCHEMA = DATABASE()");
        if (!empty($q)) {
            $q = $q[0];
            $q['NOTNULL'] = ($q['IS_NULLABLE'] != 'YES');
            # converteer het default waarde naar iets anders
            if ((strlen($q['COLUMN_DEFAULT']) == 0) && (is_string($q['COLUMN_DEFAULT']))) {
                $q['COLUMN_DEFAULT'] = "''";
            }

            if (strcasecmp($q['COLUMN_DEFAULT'], 'current_timestamp') == 0 && $q['COLUMN_TYPE'] == 'timestamp') {
                $q['COLUMN_DEFAULT'] = 'NOW';
            }
            if (strcasecmp($q['COLUMN_DEFAULT'], 'NULL') == 0) {
                $q['COLUMN_DEFAULT'] = NULL;
            }
            if (strcasecmp($q['COLUMN_DEFAULT'], '\'\'') == 0) {
                $q['COLUMN_DEFAULT'] = '';
            }
            if (strtolower($q['EXTRA']) == 'auto_increment') {
                if (strtolower($q['COLUMN_TYPE']) == 'bigint(20) unsigned') {
                    $q['COLUMN_TYPE'] = 'BIGSERIAL';
                }
                if (strtolower($q['COLUMN_TYPE']) == 'int(10) unsigned') {
                    $q['COLUMN_TYPE'] = 'SERIAL';
                }
            }
        }

        return $q;
    } 

    /* Geeft, in een afgesproken formaat, de index informatie terug */
    public function getIndexInfo($idxname, $tablename, $type)
    {
        if (strtolower($type) == 'primary') {
            $idxname = 'PRIMARY';
        }
        $q = $this->db->execute_query("SELECT
            column_name,
            non_unique,
            lower(index_type) as index_type
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
            AND table_name = '$tablename'
            AND index_name = '$idxname'
            ORDER BY seq_in_index");
        if (!is_array($q)) {
            return $q;
        }

        foreach ($q as &$_q) {
            $_q['primary'] = (strtolower($type) == 'primary') ? TRUE : FALSE;
        }

        return $q;
    } 

    public function get_tables($tablename)
    {
        $q = $this->db->execute_query("SHOW TABLES LIKE '$tablename'");
        if (!$q) return array();
        $tables = array();
        foreach ($q as $t) {
            reset($t);
            $tables[] = current($t);
        }

        return $tables;
    }

    public function normalise_index(array &$colList)
    {
    }
}
