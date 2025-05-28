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
 * $Author: gavinspearhead $
 * $Id$
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

abstract class db_update_abs
{
    protected $quiet;
    protected $db;
    protected $html;
    private function output_line($text)
    {
        if ($this->html) {
            echo $text . '<br/>' . PHP_EOL;
        } else {
            echo $text .  PHP_EOL;
        }
    }

    public function __construct(DatabaseConnection $db, $quiet = TRUE, $html=FALSE)
    {
        $this->db = $db;
        $this->quiet = $quiet;
        $this->html = $html;
    } 

    /* converteert een "URD" datatype naar een mysql datatype */
    abstract public function swDtToNative($colType);

    /* converteert een mysql datatype naar een "URD" datatype */
    abstract public function nativeDtToSw($colInfo);

    /*
     * Add an index, kijkt eerst wel of deze index al bestaat,
     * $idxType kan danwel 'UNIQUE', PRIMARY danwel 'FULLTEXT' zijn
     */
    abstract public function addIndex($idxname, $idxType, $tablename, array $colList);

    /* dropt een index als deze bestaat */
    abstract public function dropIndex($idxname, $tablename);

    /* voegt een column toe, kijkt wel eerst of deze nog niet bestaat */
    abstract public function addColumn($colName, $tablename, $colType, $colDefault=NULL, $notNull=TRUE, $collation=NULL, $auto_inc=NULL);

    /* wijzigt een column - controleert *niet* of deze voldoet aan het prototype */
    abstract public function modifyColumn($colName, $tablename, $colType, $colDefault=NULL, $notNull=TRUE, $collation=NULL, $what=NULL, $auto_inc=NULL);

    /* dropt een kolom (mits db dit ondersteunt) */
    abstract public function dropColumn($colName, $tablename);

    /* controleert of een index bestaat */
    abstract public function indexExists($idxname, $tablename);

    /* controleert of een kolom bestaat */
    abstract public function columnExists($tablename, $colname);

    /* controleert of een tabel bestaat */
    abstract public function tableExists($tablename);

    abstract public function get_tables($tablename);

    /* controleert of een full text index bestaat */
    abstract public function ftsExists($ftsname, $tablename, array $colList);

    /* maakt een full text index aan */
    abstract public function createFts($ftsname, $tablename, array $colList);

    /* dropt en fulltext index */
    abstract public function dropFts($ftsname, $tablename, array $colList);

    /* geeft FTS info terug */
    abstract public function getFtsInfo($ftsname, $tablename, array $colList);

    /* ceeert een lege tabel met enkel een ID veld, collation kan UTF8 of ASCII zijn */
    abstract public function createTable($tablename, $index_field, $collation, $engine);

    /* creeert een foreign key constraint */
    abstract public function addForeignKey($tablename, $colname, $reftable, $refcolumn, $action);

    /* dropped een foreign key constraint */
    abstract public function dropForeignKey($tablename, $colname, $reftable, $refcolumn, $action);

    /* verandert een storage engine (concept dat enkel mysql kent :P ) */
    abstract public function alterStorageEngine($tablename, $engine);

    /* drop een table */
    abstract public function dropTable($tablename);

    /* rename een table */
    abstract public function renameTable($tablename, $newTableName);

    /* Geeft, in een afgesproken formaat, de index informatie terug */
    abstract public function getIndexInfo($idxname, $tablename, $type);

    /* Geeft, in een afgesproken formaat, de index formatie terug */
    abstract public function getColumnInfo($tablename, $colname);

    /* controleert of de index structuur hetzelfde is als de gewenste, zo niet, maak hem opnieuw aan */
    public function validateIndex($idxname, $type, $tablename, array $colList)
    {
        if (!$this->quiet) {
            $this->output_line("\tValidating index " . $idxname);
        }

        if (!$this->compareIndex($idxname, $type, $tablename, $colList)) {
# Drop de index
            if ($this->indexExists($idxname, $tablename)) {
                if (!$this->quiet) {
                    $this->output_line("\t\tDropping index " . $idxname);
                }
                $this->dropIndex($idxname, $tablename);
            }

            if (!$this->quiet) {
                $this->output_line("\t\tAdding index " . $idxname);
            }

# en creeer hem opnieuw
            $this->addIndex($idxname, $type, $tablename, $colList);
        }
    }

    /* controleert of de fulltext structuur hetzelfde is als de gewenste, zo niet, maak hem opnieuw aan */
    public function validateFts($ftsname, $tablename, array $colList)
    {
        if (!$this->quiet) {
            $this->output_line("\tValidating FTS " . $ftsname );
        }

        if (!$this->compareFts($ftsname, $tablename, $colList)) {
            # Drop de FTS
            if ($this->ftsExists($ftsname, $tablename, $colList)) {
                if (!$this->quiet) {
                    $this->output_line("\t\tDropping FTS " . $ftsname);
                }
                $this->dropFts($ftsname, $tablename, $colList);
            }

            if (!$this->quiet) {
                $this->output_line("\t\tAdding FTS " . $ftsname);
            }

            # en creeer hem opnieuw
            $this->createFts($ftsname, $tablename, $colList);
        }
    } 

    /* controleert of de index structuur hetzelfde is als de gewenste, zo niet, maak hem opnieuw aan */
    public function validateColumn($colName, $tablename, $colType, $colDefault, $notNull, $collation, $auto_inc)
    {
        if (!$this->quiet) {
            $this->output_line("\tValidating " . $tablename . "(" . $colName . ")");
        }

        $compResult = $this->compareColumn($colName, $tablename, $colType, $colDefault, $notNull, $collation, $auto_inc);
        if ($compResult !== TRUE) {
            if ($this->columnExists($tablename, $colName)) {
                if (!$this->quiet) {
                    $this->output_line("\t\tModifying column " . $colName . " (" . $compResult . ") on " . $tablename);
                }
                $this->modifyColumn($colName, $tablename, $colType, $colDefault, $notNull, $collation, $compResult, $auto_inc);
            } else {
                if (!$this->quiet) {
                    $this->output_line("\t\tAdding column " . $colName . "(" . $colType . ") to " . $tablename);
                }
                $this->addColumn($colName, $tablename, $colType, $colDefault, $notNull, $collation, $auto_inc);
            }
        }
    }

    /* vergelijkt een column met de gewenste structuur */
    public function compareColumn($colName, $tablename, $colType, $colDefault, $notNull, $collation, $auto_inc)
    {
        # Vraag nu de column informatie op
        $q = $this->getColumnInfo($tablename, $colName);
        # Als de column helemaal niet gevonden wordt..
        if (empty($q)) {
            return FALSE;
        }
        # controleer het type
        if (strtolower($q['COLUMN_TYPE']) != strtolower($this->swDtToNative($colType))) {
            #die();

            return 'type';
        }
        # controleer default
        if (strtolower($q['COLUMN_DEFAULT']) !== strtolower($colDefault) && ($colType != 'BIGSERIAL') && ($colType != 'SERIAL')) {
            return 'default';
        }

        # controleer NOT NULL setting
        if (strtolower($q['NOTNULL']) != $notNull) {
            return 'not null';
        }
        # controleer character set name
        if ((strtolower($q['CHARACTER_SET_NAME']) != $collation) && ($q['CHARACTER_SET_NAME'] != NULL)) {
            return 'charset';
        }

        if ($auto_inc == 'auto_increment' && strcasecmp($auto_inc, $q['EXTRA']) != 0) {
            return 'extra';
        }

        return TRUE;
    }
    abstract public function normalise_index( array &$colList);

    /* vergelijkt een index met de gewenste structuur */
    public function compareIndex($idxname, $type, $tablename, $colList)
    {
        # Vraag nu de index informatie op
        $this->normalise_index($colList);
        $q = $this->getIndexInfo($idxname, $tablename, $type);
        # Als het aantal kolommen niet gelijk is
        if (count($q) != count($colList)) {
            return FALSE;
        }
        # we lopen vervolgens door elke index kolom heen, en vergelijken
        # dan of ze in dezelfde volgorde staan en dezelfde eigenschappen hebben
        for ($i = 0; $i < count($colList); $i++) {
            $same = TRUE;
            if ($colList[$i] != $q[$i]['column_name']) {
                $same = FALSE;
            }

            syslog(LOG_ALERT, "$idxname, $type, $tablename, $colList");
            if ($same) {
                switch (strtolower($type)) {
                case 'primary'      : $same = ($q[$i]['primary'] == TRUE); break;
                case 'fulltext'		: $same = (strtolower($q[$i]['index_type']) == 'fulltext'); break;
                case 'unique'		: $same = ($q[$i]['non_unique'] == 0); break;
                case ''				: $same = (strtolower($q[$i]['index_type']) != 'fulltext') && ($q[$i]['non_unique'] == 1);
                }
            }

            if (!$same) {
                #	die();

                return FALSE;
            }
        }

        return TRUE;
    }

    /* vergelijkt een FTS met de gewenste structuur */
    public function compareFts($ftsname, $tablename, array $colList)
    {
        # Vraag nu de FTS informatie op
        $q = $this->getFtsInfo($ftsname, $tablename, $colList);

        # Als het aantal kolommen niet gelijk is
        if (count($q) != count($colList)) {
            return FALSE;
        }

        # we loopen vervolgens door elke index kolom heen, en vergelijken
        # dan of ze in dezelfde volgorde staan en dezelfde eigenschappen hebben
        for ($i = 0; $i < count($colList); $i++) {
            if ($colList[$i + 1] != $q[$i]['column_name']) {
                return FALSE;
            }
        }

        return TRUE;
    } 
    public function confirm_version()
    {
        $ver = DB_VERSION;
        set_config($this->db, 'db_version', $ver);
    }
}
