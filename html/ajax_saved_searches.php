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

$cmd = get_request('cmd', '');
$name = get_request('name', NULL);
$type = get_request('type', '');

if ($cmd == ''  || $type == '') {
    throw new exception($LN['error_missingparameter'] );
}
$saved_searches  = new saved_searches($userid);

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
    $search = get_request('search', '');
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
    die_html('OK' . $LN['saved'] . ' "'  . htmlentities($name) . '"');
    break;
case 'get':
    // gets the subcat values for a given name, category combi
    if ($name === NULL) {
        throw new exception($LN['error_missingparameter']);
    }
    if ($name == '') {
        die_html('OK');
    }
    $saved_searches->load($db);
    try {
        $option = $saved_searches->get_search($name, $type);
    } catch (exception $e) {
        throw new exception($LN['error_searchnamenotfound']);
    }
    $str = '';
    $options = $option['options'];
    foreach ($options as $key => $opt) {
        $str .= $key . ':' . $opt . '|';
    }
    // we return the values as a3:1|b4:2|a1:1|
    foreach ($option['subcats'] as $key => $opt) {
        //$s = substr($key, 1);
        $opt = str_replace('|', '', $opt);
        $str .= $key . ':' . $opt . '|';
    }
    $str .= 'category:' . $option['category'];
    die_html('OK' . $str);
    break;
case 'names':
    $saved_searches->load($db);
    $names = array();
    $current = get_request('current', '');
    $cat = get_request('cat', '');
    foreach ($saved_searches->get_names($type) as $k => $v) {
        $names[$k] = htmlentities(utf8_decode($v));
    }
    if (count($names) == 0) {
        throw new exception($LN['error_searchnamenotfound']);
    }

    init_smarty('', 0);
    natcasesort($names);
    $smarty->assign('saved_searches',	$names);
    $smarty->assign('current',	        htmlentities(utf8_decode($current)));
    $smarty->assign('usersettype',		$type);
    $smarty->assign('cat',		        $cat);
    $smarty->assign('USERSETTYPE_RSS',  USERSETTYPE_RSS);
    $smarty->assign('USERSETTYPE_SPOT', USERSETTYPE_SPOT);
    $smarty->assign('USERSETTYPE_GROUP',USERSETTYPE_GROUP);

    $smarty->display('ajax_spot_search.tpl');
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
        throw new exception($LN['error_searchnamenotfound'] );
    }
    die_html('OK' . $LN['deleted']);
    break;
case 'show':
    init_smarty('', 0);
    $saved_searches->load($db);
    try {
        $saved_search = $saved_searches->get_search($name, $type);
        $category_id = category_by_name($db, $saved_search['category'], $userid);
    } catch (exception $e) {
        $saved_search = '';
        $category_id = '';
    }

    $smarty->assign('usersettype',		$type);
    $smarty->assign('name',     		$name);
    $smarty->assign('save_category',	$category_id);
    $smarty->assign('categories',		$categories);
    $smarty->assign('categories_count',	count($categories));
    $smarty->assign('USERSETTYPE_RSS',  USERSETTYPE_RSS);
    $smarty->assign('USERSETTYPE_SPOT', USERSETTYPE_SPOT);
    $smarty->assign('USERSETTYPE_GROUP',USERSETTYPE_GROUP);
    $smarty->display('ajax_savename.tpl');
}
