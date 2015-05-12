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
 * $LastChangedDate: 2011-01-03 20:13:22 +0100 (Mon, 03 Jan 2011) $
 * $Rev: 1990 $
 * $Author: gavinspearhead $
 * $Id: web_functions.php 1990 2011-01-03 19:13:22Z gavinspearhead $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathwf = realpath(dirname(__FILE__));

require_once "$pathwf/autoincludes.php";

class menu_item
{
    private $name;
    private $items;
    private $counter;
    private $type;
    private $url;
    private $settings;
    private $category;
    public function __construct(array $items, $name='', $type=urd_modules::URD_CLASS_GENERIC, $category='')
    {
        static $settings = NULL;
        if ($settings === NULL) {
            global $db;
            $this->settings = get_config($db, 'modules');
        }
        $this->name = $name;
        $this->items = $items;
        $this->counter = count($items);
        $this->type = $type;
        $this->url = '';
        $this->category = $category;
    }
    public function add(menu_item2 $item)
    {
        if (($this->settings & $item->get_type()) > 0) {
            $this->items[] = $item;
            $this->counter++;
            if ($this->url == '') {
                $this->url = $item->get_url();
            }
        }
    }
    public function get_url()
    {
        return $this->url;
    }
    public function get_items()
    {
        return $this->items;
    }
    public function get_name()
    {
        return $this->name;
    }
    public function get_type()
    {
        return $this->type;
    }
    public function get_count()
    {
        return $this->counter;
    }
    public function get_category()
    {
        return $this->category;
    }
    public function count_inc()
    {
        $this->counter++;
    }
}

class menu_item2
{
    private $url;
    private $type;
    private $name;
    private $link_type;
    private $message;
    public function __construct($url, $name, $type, $message='', $link_type='jump')
    {
        $this->name = $name;
        $this->url = $url;
        $this->type = $type;
        $this->link_type = $link_type;
        $this->message = $message;
    }
    public function get_url()
    {
        return $this->url;
    }
    public function get_link_type()
    {
        return $this->link_type;
    }
    public function get_name()
    {
        return $this->name;
    }
    public function get_type()
    {
        return $this->type;
    }
    public function get_message()
    {
        return $this->message;
    }
}

class menu
{
    private $items;
    private $settings;
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->items = array();
    }
    public function add(menu_item $item)
    {
        if ($item->get_count() > 0 && ($item->get_type() & $this->settings) > 0)
            $this->items[] = $item;
    }
    public function get_items()
    {
        return $this->items;
    }
    static function generate_menu(DatabaseConnection $db, array $LN, $isadmin, $custom_menu, $userid)
    {
        assert(is_numeric($userid));
        list ($update_status, $status_newversion, $status_bugfix, $status_newfeature, $status_security, $status_other) = check_for_update($db);
        $do_viewfiles = FALSE; // don't show viewfiles if dbpath isn't set yet
        // Default directory for viewfiles:
        try {
            if (isset($db)) {
                $dlpath = get_dlpath($db);
                add_dir_separator($dlpath);
                $do_viewfiles = TRUE;
            }
        } catch (exception $e) {
            $do_viewfiles = FALSE;
        }

        if (isset($_SESSION['urd_username'], $_SESSION['urd_pass'])) {
            $username = $_SESSION['urd_username'];
        } elseif (isset($_COOKIE['urd_username'], $_COOKIE['urd_pass'])) { // it's always in the session but check the cookie anyway
            $username = $_COOKIE['urd_username'];
        }
        $menu = new menu(get_config($db, 'modules'));
        $item = new menu_item(array(), $LN['menutransfers']);
        $item->add(new menu_item2('transfers.php?active_tab=downloads', $LN['menudownloads'], urd_modules::URD_CLASS_DOWNLOAD | urd_modules::URD_CLASS_USENZB));
        if ($isadmin || urd_user_rights::is_poster($db, $userid)) {
            $item->add(new menu_item2('transfers.php?active_tab=uploads', $LN['menuuploads'], urd_modules::URD_CLASS_POST));
        }
        $item->add(new menu_item2('stats.php', $LN['menustats'], urd_modules::URD_CLASS_GENERIC));
        $menu->add($item);

        $item = new menu_item(array(), $LN['menubrowsesets']);
        $item->add(new menu_item2('spots.php', $LN['menuspots'], urd_modules::URD_CLASS_SPOTS));
        $item->add(new menu_item2('browse.php', $LN['menugroupsets'], urd_modules::URD_CLASS_GROUPS));
        $item->add(new menu_item2('rsssets.php', $LN['menursssets'], urd_modules::URD_CLASS_RSS));
        $item->add(new menu_item2('search.php', $LN['menusearch'], urd_modules::URD_CLASS_RSS | urd_modules::URD_CLASS_GROUPS | urd_modules::URD_CLASS_SPOTS));
        $menu->add($item);

        if ($do_viewfiles === TRUE && isset($username)) {
            $pv_path = urlencode($dlpath . PREVIEW_PATH . $username);
            $done_path = urlencode($dlpath . DONE_PATH . $username);
            $nzb_path = urlencode($dlpath . NZB_PATH . $username);
            $scripts_path = urlencode($dlpath . SCRIPTS_PATH . $username);
            $post_path = urlencode($dlpath . POST_PATH . $username);
            $item = new menu_item (array(), $LN['menuviewfiles'], urd_modules::URD_CLASS_VIEWFILES);
            $item->add(new menu_item2('viewfiles.php?dir=' . $done_path, $LN['menuviewfiles_downloads'], urd_modules::URD_CLASS_DOWNLOAD|urd_modules::URD_CLASS_USENZB));
            $item->add(new menu_item2('viewfiles.php?dir=' . $nzb_path, $LN['menuviewfiles_nzbfiles'], urd_modules::URD_CLASS_MAKENZB));
            $item->add(new menu_item2('viewfiles.php?dir=' . $pv_path, $LN['menuviewfiles_previews'], urd_modules::URD_CLASS_DOWNLOAD));
            $item->add(new menu_item2('viewfiles.php?dir=' . $post_path, $LN['menuviewfiles_posts'], urd_modules::URD_CLASS_POST));
            $item->add(new menu_item2('viewfiles.php?dir=' . $scripts_path, $LN['menuviewfiles_scripts'], urd_modules::URD_CLASS_GENERIC));
            $menu->add($item);
        }

        $item = new menu_item(array(), $LN['menu_overview']);
        $item->add(new menu_item2('prefs.php', $LN['menupreferences'], urd_modules::URD_CLASS_GENERIC));
        $item->add(new menu_item2('newsgroups.php', $LN['menunewsgroups'], urd_modules::URD_CLASS_GROUPS));
        $item->add(new menu_item2('rssfeeds.php', $LN['menurssfeeds'], urd_modules::URD_CLASS_RSS));
        $item->add(new menu_item2('user_blacklist.php', $LN['menuuserlists'], urd_modules::URD_CLASS_SPOTS));
        $menu->add($item);

        if (isset($isadmin) && $isadmin) {
            $item = new menu_item(array(), $LN['menuadmin']);
            $item->add(new menu_item2('admin_config.php', $LN['menuadminconfig'], urd_modules::URD_CLASS_GENERIC));
            $item->add(new menu_item2('admin_usenet_servers.php', $LN['menuadminusenet'], urd_modules::URD_CLASS_GENERIC));
            $item->add(new menu_item2('admin_users.php', $LN['menuadminusers'], urd_modules::URD_CLASS_GENERIC));
            $item->add(new menu_item2('admin_searchoptions.php', $LN['menuadminbuttons'], urd_modules::URD_CLASS_GENERIC));
            $item->add(new menu_item2('admin_tasks.php', $LN['menuadmintasks'], urd_modules::URD_CLASS_GENERIC));
            $item->add(new menu_item2('admin_jobs.php', $LN['menuadminjobs'], urd_modules::URD_CLASS_GENERIC));
            $item->add(new menu_item2('admin_control.php', $LN['menuadmincontrol'], urd_modules::URD_CLASS_GENERIC));
            $item->add(new menu_item2('admin_log.php', $LN['menuadminlog'], urd_modules::URD_CLASS_GENERIC));
            $menu->add($item);
        }

        if ($custom_menu !== NULL) {
            foreach ($custom_menu as $k1 => $s1) {
                $item = new menu_item(array(), $LN[$k1]);
                foreach ($s1 as $s2) {
                    $ln_val = isset($LN[$s2->get_name()]) ? $LN[$s2->get_name()] : '???';
                    $item->add(new menu_item2($s2->get_url(), $ln_val, $s2->get_type(), $s2->get_message(), $s2->get_link_type()));
                }
                $menu->add($item);
            }
        }

        $item = new menu_item(array(), $LN['menuhelp']);
        $item->add(new menu_item2('manual.php', $LN['menumanual'], urd_modules::URD_CLASS_GENERIC));
        $item->add(new menu_item2('faq.php', $LN['menufaq'], urd_modules::URD_CLASS_GENERIC));
        $item->add(new menu_item2('http://urdland.com/forum/', $LN['menuforum'], urd_modules::URD_CLASS_GENERIC, '', 'jumpext'));
        $item->add(new menu_item2('about.php', $LN['menuabout'], urd_modules::URD_CLASS_GENERIC));
        $item->add(new menu_item2('licence.php', $LN['menulicence'], urd_modules::URD_CLASS_GENERIC));
        $item->add(new menu_item2('debug.php', $LN['menudebug'], urd_modules::URD_CLASS_GENERIC));
        $menu->add($item);

        if ($update_status > 0) {
            $item = new menu_item(array(), $LN['versionoutdated'], urd_modules::URD_CLASS_GENERIC, 'activity');
            if ($status_security) {
                $item->add(new menu_item2('', $LN['securityfixavailable'], urd_modules::URD_CLASS_GENERIC, '', 'none'));
            }
            if ($status_other) {
                $item->add(new menu_item2('', $LN['otherversion'], urd_modules::URD_CLASS_GENERIC, '', 'none'));
            }
            if ($status_bugfix) {
                $item->add(new menu_item2('', $LN['bugfixedversion'], urd_modules::URD_CLASS_GENERIC, '', 'none'));
            }
            if ($status_newfeature) {
                $item->add(new menu_item2('', $LN['newfeatureversion'], urd_modules::URD_CLASS_GENERIC, '', 'none'));
            }
            if ($status_newversion) {
                $item->add(new menu_item2('', $LN['newversionavailable'], urd_modules::URD_CLASS_GENERIC, '', 'none'));
            }
            $item->count_inc(); // hack to make the menu think it needs a submenu (single itemed lists get a menu item itself)
            $menu->add($item);
        }

        $item = new menu_item (array());
        if (isset($username)) {
            $item->add(new menu_item2('logout.php', $LN['menulogout'] . ' (' . htmlentities($username) . ')', urd_modules::URD_CLASS_GENERIC));
        } else {
            $item->add(new menu_item2('login.php', $LN['menulogin'], urd_modules::URD_CLASS_GENERIC));
        }
        $menu->add($item);

        return $menu;
    }
}

function get_index_page_array($is_admin, array $modules)
{
    global $LN;
    $index_page_array1 = $index_page_admin_array = array ();
    if ($modules[urd_modules::URD_CLASS_GROUPS]) {
        $index_page_array1['browse'] = $LN['menugroupsets'];
        $index_page_array1['browse_no_data'] = $LN['menugroupsearch'];
    }
    if ($modules[urd_modules::URD_CLASS_RSS]) {
        $index_page_array1['rsssets'] = $LN['menursssets'];
        $index_page_array1['rsssets_no_data'] = $LN['menursssearch'];
    }
    if ($modules[urd_modules::URD_CLASS_SPOTS]) {
        $index_page_array1['spots'] = $LN['menuspots'];
        $index_page_array1['user_blacklist'] = $LN['menuuserlists'];
    }
    if ($modules[urd_modules::URD_CLASS_RSS] || $modules[urd_modules::URD_CLASS_GROUPS] || $modules[urd_modules::URD_CLASS_SPOTS]) {
        $index_page_array1['search'] = $LN['menusearch'];
    }
    if ($modules[urd_modules::URD_CLASS_DOWNLOAD] || $modules[urd_modules::URD_CLASS_POST]) {
        $index_page_array1['transfers'] = $LN['menudownloads'];
    }
    if ($modules[urd_modules::URD_CLASS_GROUPS]) {
        $index_page_array1['newsgroups'] = $LN['menunewsgroups'];
    }
    if ($modules[urd_modules::URD_CLASS_RSS]) {
        $index_page_array1['rssfeeds'] = $LN['menurssfeeds'];
    }
    if ($modules[urd_modules::URD_CLASS_VIEWFILES]) {
        $index_page_array1['viewfiles'] = $LN['menuviewfiles'];
    }
    $index_page_array1['prefs']	= $LN['menupreferences'];
    $index_page_array1['stats'] = $LN['menustats'];

    if ($is_admin) {
        $index_page_admin_array = array (
            'admin_config'          => $LN['menuadminconfig'],
            'admin_usenet_servers'  => $LN['menuadminusenet'],
            'admin_control'         => $LN['menuadmincontrol'],
            'admin_log'             => $LN['menuadminlog'],
            'admin_jobs'            => $LN['menuadminjobs'],
            'admin_tasks'           => $LN['menuadmintasks'],
            'admin_users'           => $LN['menuadminusers'],
            'admin_buttons'         => $LN['menuadminbuttons']
        );
    }

    $index_page_array2 = array(
        'manual'        => $LN['menuhelp'],
        'faq'           => $LN['menufaq'],
        'about.php'     => $LN['menuabout'],
        'licence'       => $LN['menulicence'],
        'debug'         => $LN['menudebug']
    );

    $index_page_array = array_merge($index_page_array1, $index_page_admin_array, $index_page_array2);
    asort($index_page_array);

    return $index_page_array;
}


function check_for_update(DatabaseConnection $db)
{
    $versionchecking = $status_newversion =  $status_bugfix = $status_newfeature = $status_other = $status_security = 0;
    $status = 0;
    if (get_config($db, 'period_update') > 0) {
        $versionchecking = 1;
        // New version info:
        $newversionnumber = get_config($db, 'update_version');
        $update_type = (int) get_config($db, 'update_type');
        if ($update_type !== update_types::NO_UPDATE && version_compare($newversionnumber, urd_version::get_version(), '>')) {  // There is a version available AND it's a higher version number
            if (($update_type & update_types::NEW_VERSION) != 0) {
                $status_newversion = 1;
                $status = 1;
            }
            if (($update_type & update_types::BUG_FIX) != 0) {
                $status_bugfix = 1;
                $status = 1;
            }
            if (($update_type & update_types::NEW_FEATURE) != 0) {
                $status_newfeature = 1;
                $status = 1;
            }
            if (($update_type & update_types::OTHER) != 0) {
                $status_other = 1;
                $status = 2;
            }
            if (($update_type & update_types::SECURITY_FIX) != 0) {
                $status_security = 1;
                $status = 2;
            }
        }
    }

    return array($status, $status_newversion, $status_bugfix, $status_newfeature, $status_security, $status_other);
}

