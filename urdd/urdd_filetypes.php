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
 * $LastChangedDate: 2014-06-07 14:53:28 +0200 (za, 07 jun 2014) $
 * $Rev: 3081 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_functions.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}


class file_types {

    private function get_base($fn, $ext)
    {
        static $extensions = array (
                file_extensions::PAR_EXT => array('vol[0-9]+\+[0-9]+\.par2'),
                file_extensions::ZIP_EXT => array('zip'),
                file_extensions::ARJ_EXT => array('arj', 'a[0-9][0-9]'),
                file_extensions::ACE_EXT => array('ace', 'c[0-9][0-9]'),
                file_extensions::ZR7_EXT => array('7z', '7z\.[0-9][0-9][0-9]'),
                file_extensions::RAR_EXT => array('part[0-9]+\.rar', 'part[0-9]+\.r[0-9][0-9]', '\.r[0-9][0-9]', '\d{3}'),
                file_extensions::SFV_EXT => array('sfv', 'sfvmd5', 'csv', 'csv2', 'csv4', 'sha1', 'md5', 'bsdmd5', 'crc'),
                file_extensions::CAT_EXT => array("(?<!\.7z\.)\d{3}"),
                file_extensions::UUE_EXT => array('[0-9]+\.urd_uuencoded_part')
                );
        if (isset ($extensions[$ext])) {
            foreach (($extensions[$ext]) as $expr) {
                if (preg_match("/^(.*)\.$expr$/i", $fn, $fs)) {
                    return $fs[1];
                }
            }

            return substr($fn, 0, - (1 + strlen($ext))); // remove the $ext if no other match is found
        } else {
            return FALSE;
        }
    }

    private function split_filename($filename)
    {
        $pos = strrpos($filename, '.');
        if ($pos === FALSE) { // dot is not found in the filename

            return array($filename, ''); // no extension
        }
        $basename = substr($filename, 0, $pos);
        $extension = substr($filename, $pos + 1);

        return array($basename, $extension);
    }

    function get_par_rar_files(DatabaseConnection $db, $directory, &$all_files)
    {
        static $extensions = array (
                file_extensions::PAR_EXT => array('par2'),
                file_extensions::ZIP_EXT => array('zip'),
                file_extensions::ARJ_EXT => array('arj', 'a[0-9][0-9]'),
                file_extensions::ACE_EXT => array('ace', 'c[0-9][0-9]'),
                file_extensions::ZR7_EXT => array('7z', '7z\.[0-9][0-9][0-9]'),
                file_extensions::RAR_EXT => array('rar', 'r[0-9][0-9]'),
                file_extensions::SFV_EXT => array('sfv'),
                file_extensions::CAT_EXT => array("(?<!\.7z\.)\d{3}"),
                file_extensions::UUE_EXT => array('urd_uuencoded_part')
                );

        $files = new pr_list($directory);
        if (!is_dir($directory)) {
            return $files;
        }
        $d = dir($directory);
        $all_files = array();
        while (FALSE !== ($entry = $d->read())) {
            if (in_array($entry, array('.', '..', URDD_DOWNLOAD_LOCKFILE))) {
                continue;
            }
            $all_files[] = $entry;
            $mt = $this->match_mime_type($db, $directory . $entry); // find a mime type for the file
            if ($mt !== NULL && isset($extensions[$mt])) {
                $base = $this->get_base($entry, $mt); // try and find the base of the filename, which we use to match all the files against, to collect a set
                if ($base === FALSE) {
                    list($name) = $this->split_filename($entry, $extensions[$mt]); //otherwise take just the extension off and use that
                } else {
                    $name = $base;
                }
                $files->add($mt, $name, $entry);
            } else { // ok no mimetype determent, try by file extension
                foreach ($extensions as $ext => $expressions) {
                    foreach ($expressions as $expr) {
                        if (preg_match("/^.*\.$expr$/i", $entry) ) {
                            $base = $this->get_base($entry, $ext);
                            $files->add($ext, $base, $entry);
                            break;
                        }
                    }
                }
            }
        }
        $d->close();

        return $files;
    }

    private function match_mime_type(DatabaseConnection $db, $file)
    { // note: /x-* may not always be defined
        static $mime_types = array (
                file_extensions::PAR_EXT => 'application/x-par2',
                file_extensions::ZIP_EXT => 'application/zip',
                file_extensions::ARJ_EXT => 'application/x-arj',
                file_extensions::ACE_EXT => 'application/x-ace',
                file_extensions::ZR7_EXT => 'application/x-7z-compressed',
                file_extensions::RAR_EXT => 'application/x-rar',
                file_extensions::SFV_EXT => NULL,
                file_extensions::CAT_EXT => NULL,
                file_extensions::UUE_EXT => NULL
                );
        $mt = real_mime_content_type($db, $file, TRUE); // will use the file command... if available
        foreach ($mime_types as $ext => $type) {
            if ($mt == $type && $type !== NULL) {
                return $ext;
            }
        }

        return NULL;
    }
}


