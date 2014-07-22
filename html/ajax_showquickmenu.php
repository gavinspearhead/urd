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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showquickmenu.php 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$__auth = 'silent';
$pathqm = realpath(dirname(__FILE__));

require_once "$pathqm/../functions/ajax_includes.php";

$subject = '';

// Process commands:
if (isset($_REQUEST['type']) && isset($_REQUEST['subject']) && isset($_REQUEST['srctype'])) {
    $type = get_request('type');
    $srctype = get_request('srctype');
    $subject = get_request('subject');
    $killflag = get_request('killflag');
    $isposter = urd_user_rights::is_poster($db, $userid);
    $buttons = get_search_buttons($db, $userid);
    $button_count = count($buttons);
    $items = array();
    switch ($type) {
    case 'setdetails':
        $selection = get_request('selection', 0);
        if ($selection == 1) {
            if ($button_count == 1) {
                $button = end($buttons); // the last will be the first
                $items[] = new QuickMenuItem('search' . $button['name'], $LN['quickmenu_setsearch'] . ' ' . $button['name'], 'searchbutton', $button);
            } elseif ($button_count > 1) {
                $items[] = new QuickMenuItem('setsearch', $LN['quickmenu_setsearch'], 'quickmenu', 'searchbuttons');
            }
            $items[] = new QuickMenuItem('add_search', $LN['quickmenu_add_search'], 'add_search');
            $items[] = new QuickMenuItem('add_block', $LN['quickmenu_add_block'], 'add_block');
        }
        break;
    case 'viewfiles':
        $selection = get_request('selection', 0);
        if ($selection == 1) {
            if ($button_count == 1) {
                $button = end($buttons); // the last will be the first
                $items[] = new QuickMenuItem('search' . $button['name'], $LN['quickmenu_setsearch'] . ' ' . $button['name'], 'searchbutton', $button);
            } elseif ($button_count > 1) {
                $items[] = new QuickMenuItem('setsearch', $LN['quickmenu_setsearch'], 'quickmenu', 'searchbuttons');
            }
            $items[] = new QuickMenuItem('setsearch', $LN['quickmenu_setsearch'] . ' ' . $LN['urdname'], 'urd_search');
            $items[] = new QuickMenuItem('add_search', $LN['quickmenu_add_search'], 'add_search');
            $items[] = new QuickMenuItem('add_block', $LN['quickmenu_add_block'], 'add_block');
        }
        break;
    case 'browse': // Display menu for the browse page items
        $selection = get_request('selection', 0);
        if ($selection == 1) {
            if ($button_count == 1) {
                $button = end($buttons); // the last will be the first
                $items[] = new QuickMenuItem('search' . $button['name'], $LN['quickmenu_setsearch'] . ' ' . $button['name'], 'searchbutton', $button);
            } elseif (count ($buttons) > 1) {
                $items[] = new QuickMenuItem('setsearch', $LN['quickmenu_setsearch'], 'quickmenu', 'searchbuttons');
            }
            $items[] = new QuickMenuItem('setsearch', $LN['quickmenu_setsearch'] . ' ' . $LN['urdname'], 'urd_search');
        }
        if ($srctype == USERSETTYPE_GROUP) {
            list($nfo_file_data, $img_file_data, $nzb_file_data, $sample_file_data) = find_special_file($db, $subject);
            if ($nfo_file_data !== FALSE) {
                $items[] = new QuickMenuItem('setpreviewnfo', $LN['quickmenu_setpreviewnfo'], 'nfopreview', $nfo_file_data);
            }
            if ($img_file_data !== FALSE) {
                $items[] = new QuickMenuItem('setpreviewimg', $LN['quickmenu_setpreviewimg'], 'imgpreview', $img_file_data);
            }
            if ($nzb_file_data !== FALSE && urd_modules::check_module_enabled($db, urd_modules::URD_CLASS_USENZB)) {
                $items[] = new QuickMenuItem('setpreviewnzb', $LN['quickmenu_setpreviewnzb'], 'nzbpreview', $nzb_file_data);
            }
            if ($sample_file_data !== FALSE) {
                $items[] = new QuickMenuItem('setpreviewvid', $LN['quickmenu_setpreviewvid'], 'vidpreview', $sample_file_data);
            }
        }

        $items[] = new QuickMenuItem('setshowesi', $LN['quickmenu_setshowesi'], 'quickdisplay', 'showextinfo');
        if ($srctype == USERSETTYPE_SPOT && $isposter) {
            $items[] = new QuickMenuItem('post_spot_comment', $LN['quickmenu_comment_spot'], 'post_spot_comment');
        }
        if (urd_user_rights::is_seteditor($db, $userid) /*&& $srctype != USERSETTYPE_SPOT*/) {
            $items[] = new QuickMenuItem('seteditesi', $LN['quickmenu_seteditesi'], 'quickdisplay', 'editextinfo');
            // $items[] = new QuickMenuItem('setguessesisafe',$LN['quickmenu_setguessesisafe'],'guessextsetinfosafe','editextinfo');
            if ($selection == 1) {
                $items[] = new QuickMenuItem('setguessesi', $LN['quickmenu_setguessesi'], 'guessextsetinfo');
                // For all sets in the basket?
                if (is_array($_SESSION['setdata']) && count($_SESSION['setdata']) > 1) {
                    $items[] = new QuickMenuItem('setbasketguessesi', $LN['quickmenu_setbasketguessesi'], 'guessbasketextsetinfo');
                }
            }
        }
        if ($selection == 1) {
            $items[] = new QuickMenuItem('add_search', $LN['quickmenu_add_search'], 'add_search');
            $items[] = new QuickMenuItem('add_block', $LN['quickmenu_add_block'], 'add_block');
        }

        if ($srctype == USERSETTYPE_SPOT && $isadmin) {
                $items[] = new QuickMenuItem('add_blacklist', $LN['quickmenu_addglobalblacklist'], 'add_blacklist_global');
                $items[] = new QuickMenuItem('add_whitelist', $LN['quickmenu_addglobalwhitelist'], 'add_whitelist_global');
        }
        if ($srctype == USERSETTYPE_SPOT) {
                $items[] = new QuickMenuItem('add_blacklist', $LN['quickmenu_addblacklist'], 'add_blacklist');
                $items[] = new QuickMenuItem('add_whitelist', $LN['quickmenu_addwhitelist'], 'add_whitelist');
        }
        if ($srctype == USERSETTYPE_SPOT && $isposter) {
                $items[] = new QuickMenuItem('report_spam', $LN['quickmenu_report_spam'], 'report_spam');
        }
        if (urd_user_rights::is_seteditor($db, $userid) && $srctype == USERSETTYPE_SPOT) {
            // todo $items[] = new QuickMenuItem('spotedit', $LN['quickmenu_editspot'], 'quickdisplay', 'editspot');
        }
        if ($killflag) {
            $items[] = new QuickMenuItem($subject, $LN['browse_resurrectset'], 'unhide_set');
        } else {
            $items[] = new QuickMenuItem($subject, $LN['browse_removeset'], 'hide_set');
        }

        if ($srctype == USERSETTYPE_RSS) {
            $items[] = new QuickMenuItem($subject, $LN['browse_savenzb'], 'follow_link');
        }
        if ($srctype == USERSETTYPE_GROUP) {
            $items[] = new QuickMenuItem('add_posterblacklist', $LN['quickmenu_addposterblacklist'], 'add_posterblacklist');
        }
        break;
    case 'setsearch': // Search buttons for a set
        foreach ($buttons as $button) {
            $items[] = new QuickMenuItem('search' . $button['name'], $button['name'], 'searchbutton', $button);
        }
        break;

    default:
        $smarty->assign('message', $LN['error_novalidaction']);
        break;
    }
} else {
    throw new exception($LN['error_invalidaction']);
}

/* Type can be:
    - quickmenu
    - quickdisplay
    - newpage
   This can be used by the template to determine how to treat a click on the buttom (new menu, display or new page)
   Extra can be the URL (newpage), or a button array (searchbutton).
*/

class QuickMenuItem
{
    public $id;
    public $name;
    public $type;
    public $extra;

    public function __construct($id,$name, $type, $extra='')
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->extra = $extra;
    }
}

function get_search_buttons(DatabaseConnection $db, $userid)
{
    assert(is_numeric($userid));
    $activebuttons = array();
    $max_buttons = get_config($db, 'maxbuttons', button::MAX_SEARCH_BUTTONS);
    $buttons = array();
    for ($i = 1; $i <= $max_buttons; $i++) {
        $b = get_pref($db, "button$i", $userid, 'none');
          if ($b != 'none') {
            $buttons[] = $b;
        }
    }
    if (count($buttons) == 0) {
        return array();
    }
    $ids = array();
    foreach ($buttons as $button) {
        $ids[] = $button;
    }
    if (count($ids) > 0 ) {
        $qry = '* FROM searchbuttons WHERE "id" IN (' . str_repeat('?,', count($ids) - 1) . '?) ORDER BY "name"';
        $res = $db->select_query($qry, $ids);
        if ($res === FALSE) {
            return array();
        }
        foreach ($res as $row) {
            $row['name'] = htmlentities(utf8_decode($row['name']));
            $activebuttons[] = $row;
        }
    }

    return $activebuttons;
}

function find_special_file(DatabaseConnection $db, $setID)
{
    $sql = '* FROM setdata WHERE "ID"=?';
    $res = $db->select_query($sql, 1, array($setID));
    if ($res === FALSE) {
        return array(FALSE, FALSE, FALSE, FALSE);
    }
    $search_type = $db->get_pattern_search_command('REGEXP');
    $rv1 = $rv2 = $rv3 = $rv4 = FALSE;

    $groupID = $res[0]['groupID'];
    $sql = "* FROM binaries_$groupID WHERE \"setID\"=? AND \"subject\" $search_type ? ";
    $res = $db->select_query($sql, 1, array($setID, '.*[.](jpg|gif|png|jpeg)([^.].*)?$'));
    if ($res !== FALSE) {
        $rv1 = array('binaryID' => $res[0]['binaryID'], 'groupID' => $groupID);
    }
    $sql = "* FROM binaries_$groupID WHERE \"setID\"=? AND \"subject\" $search_type ?";
    $res = $db->select_query($sql, 1, array($setID, '.*[.]nfo([^.].*)?$'));
    if ($res !== FALSE) {
        $rv2 = array('binaryID' => $res[0]['binaryID'], 'groupID' => $groupID);
    }
    $sql = "* FROM binaries_$groupID WHERE \"setID\"=? AND \"subject\" $search_type ?";
    $res = $db->select_query($sql, 1, array($setID, '.*[.]nzb([^.].*)?$'));
    if ($res !== FALSE) {
        $rv3 = array('binaryID' => $res[0]['binaryID'], 'groupID' => $groupID);
    }
    $sql = "* FROM binaries_$groupID WHERE \"setID\"=? AND \"subject\" $search_type ?";
    $res = $db->select_query($sql, 1, array($setID, 'sample.*[.](wmv|mpg|mp4|avi|mkv|mov|flv)([^.].*)?$'));
    if ($res !== FALSE) {
        $rv4 = array('binaryID' => $res[0]['binaryID'], 'groupID' => $groupID);
    }

    return array($rv2, $rv1, $rv3, $rv4);
}

init_smarty('', 0);
$smarty->assign('items',	 $items);
$smarty->assign('srctype',   $srctype);     // group or rss or spot
$smarty->assign('subject',   $subject);

$smarty->display('ajax_quickmenu.tpl');
