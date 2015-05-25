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
 * $LastChangedDate: 2011-05-19 23:43:23 +0200 (Thu, 19 May 2011) $
 * $Rev: 2164 $
 * $Author: gavinspearhead $
 * $Id: stats.php 2164 2011-05-19 21:43:23Z gavinspearhead $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
$__auth = 'silent';
$pathastat = realpath(dirname(__FILE__));

require_once "$pathastat/../functions/ajax_includes.php";

try {
    $type = get_request('type', '');
    $subtype = get_request('subtype', '');
    $period = get_request('period', '');
    $year = get_request('year', '');
    $source = get_request('source', '');
    $width = floor(get_request('width', 0));

    if (!is_numeric($width) || $width < 400) {
        $width = 400;
    }

    $types_id = urd_modules::get_stats_enabled_modules($db);
    $nametypes = array();
    $nametypes[stat_actions::DOWNLOAD] = 'stats_dl';
    $nametypes[stat_actions::PREVIEW]  = 'stats_pv';
    $nametypes[stat_actions::IMPORTNZB]= 'stats_im';
    $nametypes[stat_actions::GETNZB]   = 'stats_gt';
    $nametypes[stat_actions::WEBVIEW]  = 'stats_wv';
    $nametypes[stat_actions::POST]     = 'stats_ps';

    $types_txt = array();
    foreach ($types_id as $a_type) {
        $types_txt[] = $nametypes[$a_type];
    }

    $years = get_stats_years($db, $userid, $isadmin);
    if (!empty($years)) {
        $thisyear = max($years);
    } else {
        $thisyear = date('Y');
    }

    init_smarty();
    $smarty->assign('years',            $years);
    $smarty->assign('thisyear',         $thisyear);
    $smarty->assign('subtypes',         $types_txt);
    $smarty->assign('year',             $year);
    $smarty->assign('width',            $width);
    $smarty->assign('type',             $type);
    $smarty->assign('subtype',          $subtype);
    $smarty->assign('source',           $source);
    $smarty->assign('period',           $period);
    $contents = $smarty->fetch('ajax_stats.tpl');
    return_result(array('contents' => $contents));
} catch (exception $e) {
    return_result(array('error' => $e->getMessage()));
}
