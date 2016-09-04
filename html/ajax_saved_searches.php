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
 * $LastChangedDate: 2011-06-26 15:46:54 +0200 (Sun, 26 Jun 2011) $
 * $Rev: 2226 $
 * $Author: gavinspearhead $
 * $Id: prefs.php 2226 2011-06-26 13:46:54Z gavinspearhead $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathpr = realpath(dirname(__FILE__));

require_once "$pathpr/../functions/ajax_includes.php";
require_once "$pathpr/../functions/pref_functions.php";

function get_options()
{
    $options = $_options = array();
    $_options['minage']         = get_request('minage', '');
    $_options['maxage']         = get_request('maxage', '');
    $_options['minsetsize']     = get_request('minsetsize', '');
    $_options['maxsetsize']     = get_request('maxsetsize', '');
    $_options['minrating']      = get_request('minrating', '');
    $_options['maxrating']      = get_request('maxrating', '');
    $_options['mincomplete']    = get_request('mincomplete', '');
    $_options['maxcomplete']    = get_request('maxcomplete', '');
    $_options['group']          = get_request('group', '');
    $_options['feed']           = get_request('feed', '');
    $_options['flag']           = get_request('flag', '');
    $_options['cat']            = get_request('cat', '');
    $_options['search']         = get_request('search', '');
    $_options['poster']         = get_request('poster', '');

   // strip all that have no value set
    foreach ($_options as $k => $v) {
        if ($v != '') {
            $options[ $k ] = $v;
        }
    }

    return $options;
}

try {
    $cmd = trim(get_request('cmd', ''));
    $name = trim(get_request('name', NULL));
    $type = trim(get_request('type', ''));

    if ($cmd == '' || $type == '') {
        throw new exception($LN['error_missingparameter'] );
    }
    $saved_searches  = new saved_searches($userid);

    $categories = get_categories($db, $userid);
    $category_array[''] = '';
    foreach ($categories as $cat) {
        $category_array[$cat['id']] = $cat['name'];
    }

    switch ($cmd) {
        case 'default':
            throw new exception($LN['error_novalidaction']);
            break;
        case 'save':
            // saves the current selected subcats to a name, category combination
            if ($name === NULL || !is_string($name) || $name == '') {
                throw new exception($LN['error_missingparameter']);
            }
            $options = get_options();
            $category_id = get_request('save_category', '');

            $category = '';
            if (isset($categories[$category_id]) ) {
                $category = $categories[$category_id]['name'];
            }

            list($subcats, $not_subcats) = get_subcats_requests();

            $lists = array();
            foreach ($subcats as $sc) {
                $sc_name = $sc[1] . $sc[2];
                $lists[ $sc_name ] = $sc['value'];
            }

            foreach ($not_subcats as $sc) {
                $sc_name = $sc[1] . $sc[2];
                $lists[ $sc_name ] = $sc['value'];
            }

            $saved_searches->load($db);
            try {
                $saved_searches->store($name, $lists, $type, $options, $category);
                $saved_searches->save($db);
            } catch (exception $e) {
                throw new exception($LN['error_nameexists']);
            }
            return_result(array('message'=> $LN['saved'] . ' "' . htmlentities($name) . '"' ));
            break;
        case 'get':
            // gets the subcat values for a given name, category combi
            if ($name === NULL) {
                throw new exception($LN['error_missingparameter']);
            }
            if ($name == '') {
                return_result(array('error'=>0, 'count'=> 0));
            }
            $saved_searches->load($db);
            try {
                $option = $saved_searches->get_search($name, $type);
            } catch (exception $e) {
                throw new exception($LN['error_searchnamenotfound']. "$name $type");
            }
            $options = $option['options'];
            $options['subcats'] = $option['subcats'];
            $options['category'] = $option['category'];
            return_result(array('options'=> $options, 'count'=> count($option)));
            break;
        case 'names':
            $saved_searches->load($db);
            $names = array();
            $current = get_request('current', '');
            $cat = get_request('cat', '');
            foreach ($saved_searches->get_names($type) as $k => $v) {
                $names[$k] = (utf8_decode($v));
            }
            if (count($names) == 0) {
                return_result(array('count'=>0));
            }
            init_smarty();
            natcasesort($names);
            $smarty->assign(array(
                'saved_searches'=>	$names,
                'current'=>	        (utf8_decode($current)),
                'usersettype'=>		$type,
                'cat'=>		        $cat,
                'USERSETTYPE_RSS'=>  USERSETTYPE_RSS,
                'USERSETTYPE_SPOT'=> USERSETTYPE_SPOT,
                'USERSETTYPE_GROUP'=>USERSETTYPE_GROUP));

            $contents = $smarty->fetch('ajax_spot_search.tpl');
            return_result(array('contents' => $contents, 'count'=>count($names))); 
            break;
        case 'delete':
            // removes a certain name, category combination
            if ($name === NULL || !is_string($name) || $name == '') {
                throw new exception($LN['error_missingparameter']);
            }
            $saved_searches->load($db);
            try {
                $saved_searches->delete($name, $type);
                $saved_searches->save($db);
            } catch (exception $e) {
                throw new exception($LN['error_searchnamenotfound']);
            }
            return_result(array('message' => $LN['deleted'] . ' "' . htmlentities($name) . '"'));
            break;
        case 'show':
            init_smarty();
            $saved_searches->load($db);
            try {
                $saved_search = $saved_searches->get_search($name, $type);
                $category_id = category_by_name($db, $saved_search['category'], $userid);
            } catch (exception $e) {
                $saved_search = '';
                $category_id = '';
            }

            $smarty->assign(array(
                'usersettype'=>		$type,
                'name'=>     		$name,
                'save_category'=>	$category_id,
                'categories'=>		$categories,
                'categories_count'=>	count($categories)));
            $contents = $smarty->fetch('ajax_savename.tpl');
            return_result(array('contents' => $contents));
    }
    return_result();
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
