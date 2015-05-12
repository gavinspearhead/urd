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
 * $LastChangedDate: 2011-03-06 00:35:58 +0100 (Sun, 06 Mar 2011) $
 * $Rev: 2094 $
 * $Author: gavinspearhead $
 * $Id: ajax_browse.php 2094 2011-03-05 23:35:58Z gavinspearhead $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class saved_searches
{
    // format saved seach
    // 'subcats => array( subcat, value); value = 0, 1, 2 (dontcare, must be on, must be off)
    // 'options'=> array ("maxsetsize"=> maxsetsize, "minsetsize"=>minsetsize) etc
    // 'category'=> string

    private $saved_searches = array();
    private $userid = null;

    public function __construct($userid)
    {
        assert(is_numeric($userid));
        $this->userID = $userid;
    }

    public function store($name, array $subcats, $type, array $options, $category='')
    {
        if (isset($saved_searches[ $name ]) && ($saved_searches[ $name ]['type'] != $type)) {
            throw new exception("Cannot add $name - already exists");
        }
        $this->saved_searches[ $name ] = array('subcats' => $subcats, 'type' => $type, 'options' => $options, 'category' => $category);
    }
    public function load(DatabaseConnection $db)
    {
        $saved_searches = get_pref($db, 'saved_spot_searches', $this->userID, array());
        $this->saved_searches = unserialize($saved_searches);
        if (!is_array($this->saved_searches) ) { return; }
        // temp debug code
        foreach ($this->saved_searches as &$item) {
            if (!isset($item['type'])) {
                $item['type'] = USERSETTYPE_SPOT;
            }
            if (!isset($item['options'])) {
                $item['options'] = array();
            }
            if (!isset($item['category'])) {
                $item['category'] = '';
            }
            if (!isset($item['options']['cat']) && isset($item['cat'])) {
                $item['options']['cat'] = $item['cat'];
                unset($item['cat']);
            }
            if (!isset($item['options']['search']) && isset($item['search'])) {
                $item['options']['search'] = $item['search'];
                unset($item['search']);
            }
        }
        $this->save($db);
        // end temp debug code
    }
    public function save(DatabaseConnection $db)
    {
        if (!is_array($this->saved_searches) ) {
            $this->saved_searches = array();
        }
        $saved_searches = serialize($this->saved_searches);
        $saved_searches = set_pref($db, 'saved_spot_searches', $saved_searches, $this->userID);
    }
    public function get_search($name, $type)
     {
        global $LN;
        if (isset($this->saved_searches[$name]) && ($this->saved_searches[ $name ]['type'] == $type)) {
            return $this->saved_searches[$name];
        } else {
            throw new exception ($LN['error_searchnamenotfound'] . ": $name");
        }
     }
    public function get_search_by_name($name, $type)
    {
        global $LN;
        if (isset($this->saved_searches[$name]) && ($this->saved_searches[ $name ]['type'] == $type)) {
            return $this->saved_searches[ $name ];
        } else {
            throw new exception ($LN['error_searchnamenotfound'] . ": $name");
        }
    }

    public function get()
    {
        if (!is_array($this->saved_searches) ) {
            $this->saved_searches = array();
        }

        return $this->saved_searches;
    }
    public function delete($name, $type)
    {
        global $LN;
        if (!isset($this->saved_searches[ $name ]) || ($this->saved_searches[ $name ]['type'] != $type)) {
            throw new exception ($LN['error_searchnamenotfound'] . ": $name");
        }
        unset($this->saved_searches[ $name ]);
    }
    public function get_names($type)
    {
        $names = array();
        if (!is_array($this->saved_searches) ) { return array(); }
        foreach ($this->saved_searches as $key => $item) {
            if ($item['type'] == $type) {
                $names[] = $key;
            }
        }
        natcasesort($names);

        return $names;
    }
    public function get_all_names($type=NULL)
    {
        $names = array();
        if (!is_array($this->saved_searches) ) { return array(); }
        foreach ($this->saved_searches as $key => $item) {
            if ($type === NULL || $item['type'] == $type) {
                $names[$key] = $key;
            }
        }
        natcasesort($names);

        return $names;
    }
}
