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

class db_update_sqlite extends db_update_abs
{
    /*
     * optimaliseer/analyseer een aantal tables welke veel veranderen,
     * deze functie wijzigt geen data!
       */
        /* converteert een "spotweb" datatype naar een mysql datatype */
    public function swDtToNative($colType)
    {
        switch (strtoupper($colType)) {
            case 'INTEGER'				: $colType = 'INTEGER'; break;
            case 'UNSIGNED INTEGER'		: $colType = 'INTEGER'; break;
            case 'BIGINTEGER'			: $colType = 'BIGINT'; break;
            case 'UNSIGNED BIGINTEGER'	: $colType = 'BIGINT'; break;
            case 'BOOLEAN'				: $colType = 'BOOLEAN'; break;
            case 'SMALLINTEGER'  		: $colType = 'INTEGER'; break;
            case 'UNSIGNED SMALLINTEGER': $colType = 'INTEGER'; break;
        }

        return $colType;
    } # swDtToNative

    /* converteert een mysql datatype naar een "spotweb" datatype */
    public function nativeDtToSw($colInfo)
    {
        return $colInfo;
    } # nativeDtToSw

    /* controleert of een index bestaat */
    public function indexExists($idxname, $tablename)
    {
        $q = $this->db->execute_query('PRAGMA index_info(' . $idxname . ')');

        return !empty($q);
    } # indexExists

    /* controleert of een column bestaat */
    public function columnExists($tablename, $colname)
    {
        $q = $this->db->execute_query('PRAGMA table_info(' . $tablename . ')');

        $foundCol = FALSE;
        foreach ($q as $row) {
            if ($row['name'] == $colname) {
                $foundCol = TRUE;
                break;
            }
        }

        return $foundCol;
    } # columnExists

    /* controleert of een full text index bestaat */
    public function ftsExists($ftsname, $tablename, array $colList)
    {
        foreach ($colList as $colName) {
            $colInfo = $this->getColumnInfo($ftsname, $colName);

            if (empty($colInfo)) {
                return FALSE;
            }
        }
    } # ftsExists

    /* maakt een full text index aan */
    public function createFts($ftsname, $tablename, array $colList)
    {
        # Drop eerst eventuele tabellen en dergelijke mochten die
        # al bestaan maar niet aan de voorwaarden voldoen
        $this->dropTable($ftsname);
        $this->db->execute_query('DROP TRIGGER IF EXISTS ' . $ftsname . '_insert');

        # en create de tabel opneiuw
        $this->db->execute_query('CREATE VIRTUAL TABLE ' . $ftsname . ' USING FTS3(' . implode(',', $colList) . ', tokenize=porter)');

        $this->db->execute_query('INSERT INTO ' . $ftsname . '(rowid, ' . implode(',', $colList) . ') SELECT rowid,' . implode(',', $colList) . ' FROM ' . $tablename);
        $this->db->execute_query('CREATE TRIGGER ' . $ftsname . '_insert AFTER INSERT ON ' . $tablename . " FOR EACH ROW
                                BEGIN
                                   INSERT INTO " . $ftsname . "(rowid," . implode(',', $colList) . ') VALUES (new.rowid, new.' . implode(', new.', $colList) . ");
                                END");
    } # createFts

    /* dropt en fulltext index */
    public function dropFts($ftsname, $tablename, array $colList)
    {
        $this->dropTable($ftsname);
    } # dropFts

    /* geeft FTS info terug */
    public function getFtsInfo($ftsname, $tablename, array $colList)
    {
        $ftsList = array();

        foreach ($colList as $num => $col) {
            $tmpColInfo = $this->getColumnInfo($ftsname, $col);

            if (!empty($tmpColInfo)) {
                $tmpColInfo['column_name'] = $tmpColInfo['COLUMN_NAME'];
                $ftsList[] = $tmpColInfo;
            }
        }

        return $ftsList;
    } # getFtsInfo

    /* Add an index, kijkt eerst wel of deze index al bestaat */
    public function addIndex($idxname, $idxType, $tablename, array $colList)
    {
        $this->normalise_index($colList);
        if (!$this->indexExists($idxname, $tablename)) {

            $this->db->execute_query('PRAGMA synchronous = OFF;');

            switch (strtolower($idxType)) {
                case ''		  : $this->db->execute_query('CREATE INDEX ' . $idxname . ' ON ' . $tablename . '(' . implode(',', $colList) . ')'); break;
                case 'unique'  : $this->db->execute_query('CREATE UNIQUE INDEX ' . $idxname . ' ON ' . $tablename . '(' . implode(',', $colList) . ')'); break;
            }
        }
    } # addIndex

    /* dropt een index als deze bestaat */
    public function dropIndex($idxname, $tablename)
    {
        # Check eerst of de tabel bestaat, anders kan
        # indexExists mislukken en een fatal error geven
        if (!$this->tableExists($tablename)) {
            return;
        }

        if ($this->indexExists($idxname, $tablename)) {
            $this->db->execute_query('DROP INDEX ' . $idxname);
        }
    } # dropIndex

    /* voegt een column toe, kijkt wel eerst of deze nog niet bestaat */
    public function addColumn($colName, $tablename, $colType, $colDefault=NULL, $notNull=TRUE, $collation=NULL, $auto_inc=NULL)
    {
        if (!$this->columnExists($tablename, $colName)) {
            # zet de DEFAULT waarde
            if (strlen($colDefault) != 0) {
                $colDefault = 'DEFAULT ' . $colDefault;
            } elseif ($notNull) {
                $colDefault = 'DEFAULT ""';
            }

            # Collation doen we niet in sqlite
            $colSetting = '';

            # converteer het kolom type naar het type dat wij gebruiken
            $colType = $this->swDtToNative($colType);

            # en zet de 'NOT NULL' om naar een string
            switch ($notNull) {
                case true		: $nullStr = 'NOT NULL'; break;
                default			: $nullStr = '';
            }
            $this->db->execute_query("ALTER TABLE '" . $tablename .  "' ADD COLUMN '" . $colName . "' " . $colType . ' ' . $colSetting . ' ' . $colDefault . ' ' . $nullStr);
        }
    } # addColumn

    /* dropt een kolom (mits db dit ondersteunt) */
    public function dropColumn($colName, $tablename)
    {
        if ($this->columnExists($tablename, $colName)) {
            //throw new Exception('Dropping of columns is not supported in sqlite');
            // we simply ignore it, as Dropping of columns is not supported in sqlite

        }
    } # dropColumn

    /* controleert of een tabel bestaat */
    public function tableExists($tablename)
    {
        $q = $this->db->execute_query('PRAGMA table_info(' . $tablename . ')');

        return !empty($q);
    } # tableExists

    /* ceeert een lege tabel met enkel een ID veld, collation kan UTF8 of ASCII zijn */
    public function createTable($tablename, $index_field, $collation, $engine)
    {
        if (!$this->tableExists($tablename)) {
            $this->db->execute_query('CREATE TABLE ' . $tablename . " ('$index_field' INTEGER PRIMARY KEY ASC)");
        }
    } # createTable

    /* drop een table */
    public function dropTable($tablename)
    {
        if ($this->tableExists($tablename)) {
            $this->db->execute_query('DROP TABLE ' . $tablename);
        }
    } # dropTable

    /* verandert een storage engine (concept dat enkel mysql kent :P ) */
    public function alterStorageEngine($tablename, $engine)
    {
        return; // null operatie
    } # alterStorageEngine

    /* creeert een foreign key constraint */
    public function addForeignKey($tablename, $colname, $reftable, $refcolumn, $action)
    {
        return; // null
    } # addForeignKey

    /* dropped een foreign key constraint */
    public function dropForeignKey($tablename, $colname, $reftable, $refcolumn, $action)
    {
        return; // null
    } # dropForeignKey

    /* rename een table */
    public function renameTable($tablename, $newTableName)
    {
        $this->db->execute_query('ALTER TABLE ' . $tablename . ' RENAME TO ' . $newTableName);
    } # renameTable

    /* wijzigt een column - controleert *niet* of deze voldoet aan het prototype */
    public function modifyColumn($colName, $tablename, $colType, $colDefault=NULL, $notNull=TRUE, $collation=NULL, $what=NULL, $auto_inc=NULL)
    {
        # als het de NOT NULL is of de charset, dan negeren we de gevraagde wijziging
        if (($what == 'not null') || ($what == 'charset') | ($what == 'default')) {
            return;
        }

        # sqlite kent niet echt types, dus ook dat vinden we niet erg
        if ($what == 'type') {
            return;
        }

        throw new Exception('Changing columns not supported');

    } # modifyColumn

    /* Geeft, in een afgesproken formaat, de index formatie terug */
    public function getColumnInfo($tablename, $colname)
    {
        # sqlite kent niet echt een manier om deze informatie in z'n geheel terug te geven,
        # we vragen dus de index op en manglen hem vervolgens zodat het beeld klopt
        $q = $this->db->execute_query("PRAGMA table_info('" . $tablename . "')");

        # find the keyname
        $colIndex = -1;
        for ($i = 0; $i < count($q); $i++) {
            if ($q[$i]['name'] == $colname) {
                $colIndex = $i;
                break;
            }
        }

        # als de kolom niet gevonden is, geef dit ook terug
        if ($colIndex < 0) {
            return array();
        }

        # en vertaal de sqlite info naar het mysql-achtige formaat
        $colInfo = array();
        $colInfo['COLUMN_NAME'] = $colname;
        $colInfo['COLUMN_DEFAULT'] = $q[$colIndex]['dflt_value'];
        if ($colInfo['COLUMN_DEFAULT'] == '""') {
            $colInfo['COLUMN_DEFAULT'] = '';
        }
        $colInfo['NOTNULL'] = $q[$colIndex]['notnull'];
        $colInfo['COLUMN_TYPE'] = $this->nativeDtToSw($q[$colIndex]['type']);
        $colInfo['CHARACTER_SET_NAME'] = '';
        $colInfo['COLLATION_NAME'] = '';

        return $colInfo;
    } # getColumnInfo

    /* Geeft, in een afgesproken formaat, de index informatie terug */
    public function getIndexInfo($idxname, $tablename, $type)
    {
        # sqlite kent niet echt een manier om deze informatie in z'n geheel terug te geven,
        # we vragen dus de index op en manglen hem vervolgens zodat het beeld klopt
        $q = $this->db->execute_query(
                "SELECT * FROM sqlite_master
                 WHERE type = 'index'
                 AND name = '" . $idxname . "'
                 AND tbl_name = '" . $tablename . "'");
        if (empty($q)) {
            return array();
        }

        # er is maar 1 index met die naam
        $q = $q[0];

        # eerst kijken we of de index unique gemarkeerd is
        $tmpAr = explode(' ', $q['sql']);
        $isNotUnique = (strtolower($tmpAr[1]) != 'unique');

        # vraag nu de kolom lijst op, en explode die op commas
        preg_match_all("/\((.*)\)/", $q['sql'], $tmpAr);
        $colList = explode(',', $tmpAr[1][0]);
        $colList = array_map('trim', $colList);

        # en nu bouwen we een array aan het formaat wat er verwacht wordt
        $idxInfo = array();
        for ($i = 0; $i < count($colList); $i++) {
            $idxInfo[] = array('column_name' => $colList[$i],
                               'non_unique' => (int) $isNotUnique,
                               'index_type' => 'BTREE'
                        );
        }

        return $idxInfo;
    }

    public function get_tables($tablename)
    {
        $sql ="SELECT name FROM sqlite_master WHERE type='table' AND name LIKE '$tablename'";
        $res = $this->db->execute_query($sql);
        if (!$res) return array();
        return $res;

    }
    public function normalise_index(array &$colList)
    {
        $colList = preg_replace('/\([0-9]+\)/', '', $colList);
    }
}
