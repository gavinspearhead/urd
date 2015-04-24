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
 * $LastChangedDate: 2012-07-26 23:18:57 +0200 (Thu, 26 Jul 2012) $
 * $Rev: 2606 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_processbasket.php 2606 2012-07-26 21:18:57Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class stored_files
{
    private static $cache_dir = '';
    private static $file_list = NULL;

    public static function set_cache_dir($path)
    {
        self::$cache_dir = $path;
    }

    private static function write_to_cache($path, array $file_list)
    {
        $id = md5($path);
        self::$file_list = $file_list;
        $filename = self::$cache_dir . DIRECTORY_SEPARATOR . $id;
        file_put_contents($filename, serialize($file_list));
        chmod($filename, 0600);
    }

    private static function read_from_cache($path)
    {
        $id = md5($path);
        $filename = self::$cache_dir . DIRECTORY_SEPARATOR . $id;
        if (file_exists($filename)) {
            self::$file_list = unserialize (file_get_contents($filename));
        } else {
            self::$file_list = NULL;
        }

        return self::$file_list;
    }

    private static function touch_path()
    {
        $_SESSION['last_checked_path'] = time();
    }

    public static function garbage_collect()
    {
        if (isset($_SESSION['last_checked_path']) && $_SESSION['last_checked_path'] + 300 < time()) {
            self::reset();
        }
    }

    public static function check_path($path)
    {
        add_dir_separator($path);
        if (!isset($_SESSION['stored_file_list_path'])) {
            return FALSE;
        }
        self::touch_path();

        return ($_SESSION['stored_file_list_path'] == $path);
    }

    public static function store_file(array $file_list)
    {
        reset($file_list);
        $f = current($file_list);
        if ($f !== FALSE) {
            $path = $f->get_path();
            add_dir_separator($path);
            foreach ($file_list as $k => &$f) {
                $f->set_index($k);
            }

            self::write_to_cache($path, $file_list);
            $_SESSION['stored_file_list_path'] = $path;
            self::touch_path();
        }
    }
    public static function index_exists($idx)
    {
        self::touch_path();
        if (self::$file_list === NULL) {
            self::read_from_cache($_SESSION['stored_file_list_path']);
        }
        if (self::$file_list !== NULL) {
            return isset(self::$file_list[$idx]);
        } else {
            return FALSE;
        }
    }
    public static function get_file($idx)
    {
        if (self::$file_list === NULL) {
            self::read_from_cache($_SESSION['stored_file_list_path']);
        }

        if (isset(self::$file_list[$idx])) {
            $file = self::$file_list[$idx];
            self::touch_path();

            return $file->get_path() . $file->get_name();
        }

        return FALSE;
    }
    public static function reset()
    {
        self::$file_list = $_SESSION['stored_file_list_path'] = $_SESSION['last_checked_path'] = NULL;
    }
    public static function find_first($idx, $type='')
    {
        self::touch_path();
        $fn = '';

        if (self::$file_list === NULL) {
            self::read_from_cache($_SESSION['stored_file_list_path']);
        }
        if ($type == 'image') {
            $fn = 'is_image_file';
        } elseif ($type == 'text') {
            $fn = 'is_text_file';
        } else {
            return ($idx > 0) ? 0 : -1;
        }
        for ($i = 0; $i < $idx; $i++) {
            if (!isset(self::$file_list[$i])) {
                continue;
            }
            $file = self::$file_list[$i]->get_name();
            $ext = ltrim(strrchr($file, '.'), '.');
            if ($fn($ext)) {
               return $i;
            }
        }

        return -1;
    }
    public static function find_previous($idx, $type='')
    {
        self::touch_path();
        $fn = '';
        if (self::$file_list === NULL) {
            self::read_from_cache($_SESSION['stored_file_list_path']);
        }
        if ($type == 'image') {
            $fn = 'is_image_file';
        } elseif ($type == 'text') {
            $fn = 'is_text_file';
        } else {
            return ($idx >= 0) ? ( $idx - 1) : -1;
        }
        for ($i = $idx - 1; $i >= 0; $i--) {
            if (!isset(self::$file_list[$i])) {
                continue;
            }
            $file = self::$file_list[$i]->get_name();
            $ext = ltrim(strrchr($file, '.'), '.');
            if ($fn($ext)) {
                return $i;
            }
        }

        return -1;
    }

    public static function find_last($idx, $type='')
    {
        self::touch_path();
        if (self::$file_list === NULL) {
            self::read_from_cache($_SESSION['stored_file_list_path']);
        }
        $len = count(self::$file_list);
        $fn = '';
        if ($type == 'image') {
            $fn = 'is_image_file';
        } elseif ($type == 'text') {
            $fn = 'is_text_file';
        } else {
            return ($idx < $len) ? ($len-1) : -1;
        }
        $rv = -1;
        for ($i = $idx + 1; $i < $len; $i++) {
            if (!isset(self::$file_list[$i])) {
                continue;
            }
            $file = self::$file_list[$i]->get_name();
            $ext = ltrim(strrchr($file, '.'), '.');
            if ($fn($ext)) {
                $rv = $i;
            }
        }

        return $rv;

    }
    public static function find_next($idx, $type='')
    {
        self::touch_path();
        if (self::$file_list === NULL) {
            self::read_from_cache($_SESSION['stored_file_list_path']);
        }
        $len = count(self::$file_list);
        $fn = '';
        if ($type == 'image') {
            $fn = 'is_image_file';
        } elseif ($type == 'text') {
            $fn = 'is_text_file';
        } else {
            return ($idx < $len) ? ($idx+1) : -1;
        }
        for ($i = $idx + 1; $i < $len; $i++) {
            if (!isset(self::$file_list[$i])) {
                continue;
            }
            $file = self::$file_list[$i]->get_name();
            $ext = ltrim(strrchr($file, '.'), '.');
            if ($fn($ext)) {
                return $i;
            }
        }

        return -1;
    }
}

class a_file
{
    private $name;
    private $type;
    private $size;
    private $mtime;
    private $icon;
    private $readable_size;
    private $path;
    private $perms; // as string
    private $perms_num; // as number
    private $group;
    private $owner;
    private $show_delete;
    private $show_edit;
    private $nfo_link;
    private $index;

    public function __construct($n, $t, $s, $m, $i, $p, $prm, $o, $g, $d, $nl)
    {
        global $LN;
        //$this->name = utf8_decode($n);
        $this->name = utf8_encode($n);
        $this->type = $t;
        $this->size = $s;
        if ($s == '') {
            $this->readable_size = $s;
        } else {
            list($_size, $suffix) = format_size($s, 'h', $LN['byte_short'], 1024, 1);
            $this->readable_size = $_size . ' ' . $suffix;
        }
        $this->mtime = $m;
        $this->icon = $i;
        $this->path = $p;
        $this->show_delete = $d;
        $this->owner = $o;
        $this->group = $g;
        $this->show_edit = (in_array($i, array('urdd_script', 'html', 'text'))) ? TRUE : FALSE;
        $this->perms_num = $prm;
        $this->perms = '';
        $this->nfo_link = utf8_decode($nl);
        if ($prm !== NULL) {
            $this->perms = perms_to_string($prm);
        }

    }
    public function get_path()
    {
        return $this->path;
    }
    public function get_index()
    {
        return $this->index;
    }
    public function set_index($idx)
    {
        $this->index = $idx;
    }
    public function get_show_edit()
    {
        return $this->show_edit;
    }
    public function get_show_delete()
    {
        return $this->show_delete;
    }
    public function set_show_delete($del)
    {
        if (is_bool($del)) {
            $this->show_delete = $del;
        }
    }

    public function get_size()
    {
        if ($this->size === '') {
            return '';
        }
        if ($this->type == 'dir') {
            return $this->size;
        } else {
            return $this->readable_size;
        }
    }

    public function get_nfo_link()
    {
        return $this->nfo_link;
    }
    public function get_group()
    {
        return $this->group;
    }
    public function get_owner()
    {
        return $this->owner;
    }
    public function get_name()
    {
        return $this->name;
    }
    public function get_perms_num()
    {
        return $this->perms_num;
    }
    public function get_perms()
    {
        return $this->perms;
    }
    public function get_type()
    {
        return $this->type;
    }
    public function get_size_unformated()
    {
        return $this->size;
    }
    public function get_mtime_unformated()
    {
        return $this->mtime;
    }
    public function get_mtime()
    {
        global $LN;
        $format = $LN['dateformat2'] . ' ' . $LN['timeformat2'];

        return ($this->mtime == '') ? '': date ($format, $this->mtime);
    }
    public function get_icon()
    {
        return $this->icon;
    }
    public function get_icon_ln()
    {
        global $LN;
        switch ($this->icon) {
            case 'audio':	return $LN['viewfiles_type_audio']; break;
            case 'ebook':	return $LN['viewfiles_type_ebook']; break;
            case 'excel':	return $LN['viewfiles_type_excel']; break;
            case 'exe':	    return $LN['viewfiles_type_exe']; break;
            case 'flash':	return $LN['viewfiles_type_flash']; break;
            case 'html':	return $LN['viewfiles_type_html']; break;
            case 'iso':	    return $LN['viewfiles_type_iso']; break;
            case 'php':	    return $LN['viewfiles_type_php']; break;
            case 'source':	return $LN['viewfiles_type_source']; break;
            case 'picture':	return $LN['viewfiles_type_picture']; break;
            case 'ppt':	    return $LN['viewfiles_type_ppt']; break;
            case 'script':	return $LN['viewfiles_type_script']; break;
            case 'text':	return $LN['viewfiles_type_text']; break;
            case 'video':	return $LN['viewfiles_type_video']; break;
            case 'word':	return $LN['viewfiles_type_word']; break;
            case 'zip':	    return $LN['viewfiles_type_zip']; break;
            case 'stylesheet': return $LN['viewfiles_type_stylesheet']; break;
            case 'icon':	return $LN['viewfiles_type_icon']; break;
            case 'db':	    return $LN['viewfiles_type_db']; break;
            case 'folder':	return $LN['viewfiles_type_folder']; break;
            case 'file':	return $LN['viewfiles_type_file']; break;
            case 'pdf':	    return $LN['viewfiles_type_pdf']; break;
            case 'nzb':	    return $LN['viewfiles_type_nzb']; break;
            case 'par2':	return $LN['viewfiles_type_par2']; break;
            case 'sfv':	    return $LN['viewfiles_type_sfv']; break;
            case 'playlist':return $LN['viewfiles_type_playlist']; break;
            case 'torrent': return $LN['viewfiles_type_torrent']; break;
            case 'urdd_script': return $LN['viewfiles_type_urdd_sh']; break;
            default:	    return '?' . $this->icon . '?'; break;
        }
    }
} // a_file
