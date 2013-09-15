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
 * $LastChangedDate: 2013-08-04 00:07:36 +0200 (zo, 04 aug 2013) $
 * $Rev: 2885 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: pr_file.php 2885 2013-08-03 22:07:36Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly');
}

class pr_file
{
    public $ext;  // extension
    public $base; // basename
    public $files; // file set

    public function __construct ($ext, $base, $filename)
    {
        assert ($ext != '' && $filename != '');
        $this->ext = $ext;
        $this->base = $base;
        $this->files [] = $filename;
    }
}

class pr_list
{
    public $file_list;
    public $dir;
    public function __construct($dir)
    {
        assert($dir != '');
        $this->file_list = array();
        $this->dir = $dir;
    }

    public function add ($ext, $base, $filename)
    {
        assert ($ext != '' && $filename != '');
        foreach ($this->file_list as $f) {
            if (($f->ext == $ext) && ($f->base == $base)) {
                $f->files[] = $filename;

                return ;
            }
        }
        $this->file_list[] = new pr_file($ext, $base, $filename);
    }
    public function delete_files($par=FALSE, $archives=FALSE, $sfv=FALSE, $uue=FALSE, $cat=FALSE)
    {
        assert(is_bool($par) && is_bool($archives) && is_bool($sfv) && is_bool($uue) && is_bool($cat));
        foreach ($this->file_list as $f) {
            if ((($archives === TRUE) && in_array($f->ext, file_extensions::$archives)) ||
                (($par === TRUE) && ($f->ext == file_extensions::PAR_EXT)) ||
                (($cat === TRUE) && ($f->ext == file_extensions::CAT_EXT)) ||
                (($uue === TRUE) && ($f->ext == file_extensions::UUE_EXT)) ||
                (($sfv === TRUE) && ($f->ext == file_extensions::SFV_EXT)))
                delete_files($f->files, $this->dir);
        }
    }
}
