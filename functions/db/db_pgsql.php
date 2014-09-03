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

class db_update_pgsql extends db_update_abs
{
    /*
     * optimaliseer/analyseer een aantal tables welke veel veranderen,
     * deze functie wijzigt geen data!
     */

    /* converteert een "URD" datatype naar een mysql datatype */
    public function swDtToNative($colType)
    {
        switch (strtoupper($colType)) {
            case 'INTEGER'				: $colType = 'integer'; break;
            case 'UNSIGNED INTEGER'		: $colType = 'bigint'; break;
            case 'BIGINTEGER'			: $colType = 'bigint'; break;
            case 'UNSIGNED BIGINTEGER'	: $colType = 'bigint'; break;
            case 'SMALLINTEGER'  		: $colType = 'smallint'; break;
            case 'UNSIGNED SMALLINTEGER': $colType = 'smallint'; break;
        }

        return $colType;
    } 

    /* converteert een mysql datatype naar een "URD" datatype */
    public function nativeDtToSw($colInfo)
    {
        switch (strtolower($colInfo)) {
            case 'smallint'			    : $colInfo = 'SMALLINTEGER'; break;
            case 'integer'				: $colInfo = 'INTEGER'; break;
            case 'bigint'				: $colInfo = 'BIGINTEGER'; break;
        }

        return $colInfo;
    } 

    /* controleert of een index bestaat */
    public function indexExists($idxname, $tablename)
    {
        $q = $this->db->execute_query("SELECT indexname FROM pg_indexes WHERE schemaname = CURRENT_SCHEMA() AND tablename = '$tablename' AND indexname = '$idxname'");
        return !empty($q);
    } 

    /* controleert of een column bestaat */
    public function columnExists($tablename, $colname)
    {

        $q = $this->db->execute_query("SELECT column_name FROM information_schema.columns
                WHERE table_schema = CURRENT_SCHEMA() AND table_name = '$tablename' AND column_name = '$colname'");

        return !empty($q);
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
    } 

    public function check_primary($tablename)
    {
        $q = $this->db->execute_query('SELECT schemaname, tablename, indexname, contype FROM pg_indexes LEFT JOIN pg_constraint ON indexname = conname '.
                "WHERE schemaname = CURRENT_SCHEMA() AND tablename = '$tablename' AND contype='p'");
        if (is_array($q)) {
            return $q[0]['indexname'];
        } else {
            return FALSE;
        }
    }

    /* Add an index, kijkt eerst wel of deze index al bestaat */
    public function addIndex($idxname, $idxType, $tablename, array $colList)
    {
        $this->normalise_index($colList);
        if (!$this->indexExists($idxname, $tablename)) {
            switch ($idxType) {
                case 'UNIQUE': 
                    $this->db->execute_query('CREATE UNIQUE INDEX "' . $idxname . '" ON ' . $tablename . '("' . implode('","', $colList) . '")');
                    break;

                case 'FULLTEXT' : 
                    $this->db->execute_query('CREATE INDEX "' . $idxname . '" ON ' . $tablename . " USING gin(to_tsvector('dutch', \"" . implode('","', $colList) . '"))');
                    break;

                case 'PRIMARY' :
                    $primkey = $this->check_primary($tablename);
                    if ($primkey) {
                        $this->db->execute_query('ALTER TABLE ' . $tablename . " DROP CONSTRAINT \"$primkey\"");
                    }
                    $this->db->execute_query('ALTER TABLE ' . $tablename . " ADD CONSTRAINT \"$idxname\" PRIMARY KEY (\"" . implode('","', $colList) . '")');
                    break;
                default	: 
                    $this->db->execute_query('CREATE INDEX "' . $idxname . '" ON ' . $tablename . '("' . implode('","', $colList) . '")');

            }
        }
    } 

    /* dropt een index als deze bestaat */
    public function dropIndex($idxname, $tablename)
    {
# Check eerst of de tabel bestaat, anders kan
# indexExists mislukken en een fatal error geven
        if (!$this->tableExists($tablename)) {
            return;
        }

        if ($this->indexExists($idxname, $tablename)) {
            $this->db->execute_query('DROP INDEX "' . $idxname . '"');
        }
    }

    /* voegt een column toe, kijkt wel eerst of deze nog niet bestaat */
    public function addColumn($colName, $tablename, $colType, $colDefault=NULL, $notNull=TRUE, $collation=NULL, $auto_inc=NULL)
    {
        if (!$this->columnExists($tablename, $colName)) {

            $def_setting = $colDefault;
            if (strtolower($colType) == 'timestamp') {
                switch (strtolower($colDefault)) {
                    case 'now' : $def_setting = 'DEFAULT now()';
                                 break;
                }
            } elseif (!is_null($colDefault) || $notNull) {
                if (!in_array(strtoupper($colType), array('BIGSERIAL', 'SERIAL'))) {
                    $def_setting = 'DEFAULT \'' . $colDefault . "'";
                }
            }

# converteer het kolom type naar het type dat wij gebruiken
            $colType = $this->swDtToNative($colType);

# Enkel pgsql 9.1 (op dit moment beta) ondersteunt per column collation,
# dus daar doen we voor nu niks mee.
            switch (strtolower($collation)) {
                case 'utf8'		:
                case 'ascii'	:
                case ''			: $colSetting = ''; 
                                  break;
                default			: throw new Exception('Invalid collation setting');
            }

# en zet de 'NOT NULL' om naar een string
            switch ($notNull) {
                case TRUE		: $nullStr = 'NOT NULL'; 
                                  break;
                default			: $nullStr = '';
            }
            $this->db->execute_query('ALTER TABLE ' . $tablename . ' ADD COLUMN "' . $colName . '" ' . $colType . ' ' . $colSetting . ' ' . $def_setting . ' ' . $nullStr . ' ' . $auto_inc);
        }
    }
    public function check_sequence($seq)
    {
        $q = $this->db->execute_query("SELECT * FROM pg_statio_all_sequences WHERE relname = '$seq' AND schemaname = CURRENT_SCHEMA()");
        if (is_array($q)) return TRUE;
        else return FALSE;
    }

    /* wijzigt een column - controleert *niet* of deze voldoet aan het prototype */
    public function modifyColumn($colName, $tablename, $colType, $colDefault=NULL, $notNull=TRUE, $collation=NULL, $what=NULL, $auto_inc=NULL)
    {
# zet de DEFAULT waarde

        $def_setting = $colDefault;
        if ($colType == 'timestamp') {
            switch (strtolower($colDefault)) {
                case 'now' : $def_setting = 'DEFAULT now()';
                             break;
            }
        } elseif (strlen($colDefault) != 0) {
            $def_setting = 'DEFAULT \'' . $colDefault . "'";
        }

# converteer het kolom type naar het type dat wij gebruiken
        $colType = $this->swDtToNative($colType);
        if (strtoupper($colType) == 'BIGSERIAL') {
            if (!$this->check_sequence($tablename . '_' . $colName . '_seq')) {
                $this->db->execute_query('CREATE SEQUENCE ' . $tablename . '_' . $colName . '_seq' );
            }
            $this->db->execute_query('ALTER TABLE ' . $tablename . ' ALTER COLUMN "' . $colName . '" TYPE BIGINT, ALTER COLUMN "' . $colName . '" SET DEFAULT nextval(\'' . $tablename . '_' . $colName . '_seq\')');

                    return;
                    } else  if (strtoupper($colType) == 'SERIAL') {
                    try {
                    $this->db->execute_query('CREATE SEQUENCE ' . $tablename . '_' . $colName . '_seq' );
                    } catch (exception $e) {
                    // ignore
                    }
                    $this->db->execute_query('ALTER TABLE ' . $tablename . ' ALTER COLUMN "' . $colName . '" TYPE INTEGER, ALTER COLUMN "' . $colName . '" SET DEFAULT nextval(\'' . $tablename . '_' . $colName . '_seq\' )');
                    }

# Enkel pgsql 9.1 (op dit moment beta) ondersteunt per column collation,
# dus daar doen we voor nu niks mee.
                        switch (strtolower($collation)) {
                        case 'utf8'		:
                        case 'ascii'	:
                        case ''			: $colSetting = ''; break;
                        default			: throw new Exception('Invalid collation setting');
                        }
# en zet de 'NOT NULL' om naar een string
                        switch ($notNull) {
                        case TRUE		: $nullStr = 'NOT NULL'; break;
                        default			: $nullStr = '';
                        }

# zet de koloms type
                        $this->db->execute_query('ALTER TABLE ' . $tablename . ' ALTER COLUMN "' . $colName . '" TYPE ' . $colType);

# zet de default value
                        if (strlen($def_setting) > 0) {
                            $this->db->execute_query('ALTER TABLE ' . $tablename . ' ALTER COLUMN "' . $colName . '" SET ' . $def_setting);
                        } else {
                            $this->db->execute_query('ALTER TABLE ' . $tablename . ' ALTER COLUMN "' . $colName . '" DROP DEFAULT');
                        }

# en zet de null/not-null constraint
                        if (strlen($notNull) > 0) {
                            $this->db->execute_query('ALTER TABLE ' . $tablename . ' ALTER COLUMN "' . $colName . '" SET NOT NULL');
                        } else {
                            $this->db->execute_query('ALTER TABLE ' . $tablename . ' ALTER COLUMN "' . $colName . '" DROP NOT NULL');
                        }
    } 

    /* dropt een kolom (mits db dit ondersteunt) */
    public function dropColumn($colName, $tablename)
    {
        if ($this->columnExists($tablename, $colName)) {
            $this->db->execute_query('ALTER TABLE ' . $tablename . ' DROP COLUMN "' . $colName . '"');
        }
    } 

    /* controleert of een tabel bestaat */
    public function tableExists($tablename)
    {
        $q = $this->db->execute_query("SELECT tablename FROM pg_tables WHERE schemaname = CURRENT_SCHEMA() AND (tablename = '$tablename')");

        return !empty($q);
    } 

    /* creeert een lege tabel met enkel een ID veld, collation kan UTF8 of ASCII zijn */
    public function createTable($tablename, $index_field, $collation, $engine)
    {
        if (!$this->tableExists($tablename)) {
# Enkel pgsql 9.1 (op dit moment beta) ondersteunt per column collation,
# dus daar doen we voor nu niks mee.
            switch (strtolower($collation)) {
                case 'utf8'		:
                case 'ascii'	:
                case ''			: $colSetting = ''; break;
                default			: throw new Exception('Invalid collation setting');
            }

            $this->db->execute_query('CREATE TABLE "' . $tablename . "\" (\"$index_field\" BIGSERIAL PRIMARY KEY) " . $colSetting);
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
        return FALSE;
    } 

    /* rename een table */
    public function renameTable($tablename, $newTableName)
    {
        $this->db->execute_query('ALTER TABLE ' . $tablename . ' RENAME TO ' . $newTableName);
    } 

    /* dropped een foreign key constraint */
    public function dropForeignKey($tablename, $colname, $reftable, $refcolumn, $action)
    {
        /* SQL from http://stackoverflow.com/questions/1152260/postgres-sql-to-list-table-foreign-keys */
        $q = $this->db->execute_query("SELECT
                tc.constraint_name AS CONSTRAINT_NAME,
                tc.table_name AS TABLE_NAME,
                tc.constraint_schema AS TABLE_SCHEMA,
                kcu.column_name AS COLUMN_NAME,
                ccu.table_name AS REFERENCED_TABLE_NAME,
                ccu.column_name AS REFERENCED_COLUMN_NAME
                FROM
                information_schema.table_constraints AS tc
                JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
                JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name
                WHERE constraint_type = 'FOREIGN KEY'
                AND tc.TABLE_SCHEMA = CURRENT_SCHEMA()
                AND tc.TABLE_NAME = '$tablename'
                AND kcu.COLUMN_NAME = '$colname'
                AND ccu.table_name = '$reftable'
                AND ccu.column_name = '$refcolumn'");
        if (!empty($q)) {
            foreach ($q as $res) {
                $this->db->execute_query('ALTER TABLE ' . $tablename . " DROP FOREIGN KEY " . $res['CONSTRAINT_NAME']);
            }
        }
    } 

    /* creeert een foreign key constraint */
    public function addForeignKey($tablename, $colname, $reftable, $refcolumn, $action)
    {
        /* SQL from http://stackoverflow.com/questions/1152260/postgres-sql-to-list-table-foreign-keys */
        $q = $this->db->execute_query("SELECT
                tc.constraint_name AS CONSTRAINT_NAME,
                tc.table_name AS TABLE_NAME,
                tc.constraint_schema AS TABLE_SCHEMA,
                kcu.column_name AS COLUMN_NAME,
                ccu.table_name AS REFERENCED_TABLE_NAME,
                ccu.column_name AS REFERENCED_COLUMN_NAME
                FROM
                information_schema.table_constraints AS tc
                JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
                JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name
                WHERE constraint_type = 'FOREIGN KEY'
                AND tc.TABLE_SCHEMA = CURRENT_SCHEMA()
                AND tc.TABLE_NAME = '$tablename'
                AND kcu.COLUMN_NAME = '$colname'
                AND ccu.table_name = '$reftable'
                AND ccu.column_name = '$refcolumn'");
        if (empty($q)) {
            $this->db->execute_query('ALTER TABLE ' . $tablename . ' ADD FOREIGN KEY (' . $colname . ")
                    REFERENCES " . $reftable . ' (' . $refcolumn . ') ' . $action);
        }
    } 

    /* Geeft, in een afgesproken formaat, de column formatie terug */
    public function getColumnInfo($tablename, $colname)
    {
        $q = $this->db->execute_query("SELECT column_name AS \"COLUMN_NAME\",
                column_default AS \"COLUMN_DEFAULT\",
                is_nullable AS \"IS_NULLABLE\",
                data_type AS \"DATA_TYPE\",
                numeric_precision AS \"NUMERIC_PRECISION\",
                CASE
                WHEN (data_type = 'character varying') THEN 'varchar(' || character_maximum_length || ')'
                    WHEN (data_type = 'character') THEN 'char(' || character_maximum_length || ')'
                        WHEN (data_type = 'integer') THEN 'integer'
                        WHEN (data_type = 'bigint') THEN 'bigint'
                        WHEN (data_type = 'smallint') THEN 'smallint'
                        WHEN (data_type = 'text') THEN 'text'
                        WHEN (data_type = 'timestamp without time zone') THEN 'timestamp'
                        END as \"COLUMN_TYPE\",
                        character_set_name AS \"CHARACTER_SET_NAME\",
                        collation_name AS \"COLLATION_NAME\"
                        FROM information_schema.COLUMNS
                        WHERE TABLE_SCHEMA = CURRENT_SCHEMA()
                        AND TABLE_NAME = '$tablename'
                        AND COLUMN_NAME = '$colname'");

                    if (!empty($q)) {
                    $q = $q[0];
                    $q['EXTRA'] = '';
                    $q['NOTNULL'] = ($q['IS_NULLABLE'] != 'YES');

# converteer het default waarde naar iets anders
                    if ((strlen($q['COLUMN_DEFAULT']) == 0) && (is_string($q['COLUMN_DEFAULT']))) {
                        $q['COLUMN_DEFAULT'] = "''";
                    }
                    if ($q['COLUMN_DEFAULT'] == 'now()' && $colname == 'timestamp') {
                        $q['COLUMN_DEFAULT'] = 'NOW';
                    }

                    if (substr($q['COLUMN_DEFAULT'], 0, 8) == ('nextval' . '(')) {
                        $q['COLUMN_DEFAULT'] = '';
                        $q['EXTRA'] = 'auto_increment';
                        if ($q['COLUMN_TYPE'] == 'bigint') {
                            $q['COLUMN_TYPE'] = 'BIGSERIAL';
                        } elseif ($q['COLUMN_TYPE'] == 'integer') {
                            $q['COLUMN_TYPE'] = 'SERIAL';
                        }
                    }
# pgsql typecast de default waarde standaard, maar
# wij gaan daar niet van uit, dus strip dat
                    if (strpos($q['COLUMN_DEFAULT'], ':') !== FALSE) {
                        $elems = explode(':', $q['COLUMN_DEFAULT']);
                        $q['COLUMN_DEFAULT'] = trim($elems[0], "'");
                    }
                    }

                    return $q;
    } 

    /* Geeft, in een afgesproken formaat, de index informatie terug */
    public function getIndexInfo($idxname, $tablename, $type)
    {
        $q = $this->db->execute_query("SELECT * FROM pg_indexes WHERE schemaname = CURRENT_SCHEMA() AND tablename = '$tablename' AND indexname = '$idxname'");
        if (empty($q)) {
            return array();
        }
# er is maar 1 index met die naam
        $q = $q[0];

        $p = $this->db->execute_query("SELECT contype FROM pg_constraint WHERE conname ='$idxname'");
        $primary = (isset( $p[0]['contype']) && $p[0]['contype'] == 'p');
# eerst kijken we of de index unique gemarkeerd is
        $tmpAr = explode(" ", $q['indexdef']);
        $isNotUnique = (strtolower($tmpAr[1]) != 'unique');

# vraag nu de kolom lijst op, en explode die op commas
        preg_match_all("/\((.*)\)/", $q['indexdef'], $tmpAr);

        $colList = explode(",", $tmpAr[1][0]);
        $colList = array_map('trim', $colList);

# gin indexes (fulltext search) mogen maar 1 kolom beslaan, dus daar maken we
# een uitzondering voor
        $idxInfo = array();
        if (stripos($tmpAr[1][0], 'to_tsvector') === FALSE) {
            for ($i = 0; $i < count($colList); $i++) {
                $idxInfo[] = array('column_name' => trim($colList[$i], '"\''),
                        'non_unique' => (int) $isNotUnique,
                        'index_type' => 'BTREE',
                        'primary' => $primary
                        );
            }
        } else {
# extract de kolom naam
            preg_match_all("/\((.*)\)/U", $colList[1], $tmpAr);

# en creer de indexinfo
            $idxInfo[] = array('column_name' => trim($tmpAr[1][0], '\'"'),
                    'non_unique' => (int) $isNotUnique,
                    'index_type' => 'FULLTEXT',
                    'primary' => $primary
                    );
        }

        return $idxInfo;
    }

    public function get_tables($tablename)
    {
        $q = $this->db->execute_query("SELECT tablename FROM pg_tables WHERE schemaname = CURRENT_SCHEMA() AND (tablename LIKE '$tablename')");
        if (!$q) return array();
        $tables = array();
        foreach ($q as $t) {
            $tables[] = $t['tablename'];
        }

        return $tables;
    }

    public function normalise_index(array &$colList)
    {
        $colList = preg_replace('/\([0-9]+\)/', '', $colList);
    }
} 
