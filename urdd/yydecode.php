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
 * $LastChangedDate: 2013-01-09 23:58:01 +0100 (wo, 09 jan 2013) $
 * $Rev: 2749 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_functions.php 2749 2013-01-09 22:58:01Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    //die('This file cannot be accessed directly.');
}

$directory = './';

function ydecode_line(&$line)
{
    global $directory;
    static $state = 0; // 0 = start, 1= ybegin found, 2=ypart found, 3= data found, 4=end found, 6= uuencoded string found
    static $begin = 0;
    static $name = '';
    static $file = NULL;
    static $decoded = '';
    switch ($state) {
        case 0:
            if (stripos($line, '=ybegin') !== FALSE) {
                preg_match('/part=(\d+)/', $line, $match_part);
                $part = $match_part[1];
                preg_match('/name=(.*)/', $line, $match_name);
                $name = trim($match_name[1]);
                $state = 1;
                $file = fopen($directory . basename($name), 'c');
                stream_set_write_buffer($file, 65536);
                if (!$file) {
                    die('Could not open file ' . basename($name));
                }
            } elseif (preg_match('/begin [0-7]{3} (.*)/', $line, $matches) == 1) {
                // uu encoded file
                if (isset($matches[1])) {
                    $name = $matches[1];
                    $state = 6;
                    $file = fopen($directory . basename($name), 'c');
                    stream_set_write_buffer($file, 65536);
                    if (!$file) {
                        die('Could not open file ' . basename($name));
                    }
                }
            } else {
            }
            break;
        case 1:
            if (stripos($line, '=ypart') !== FALSE) {
                preg_match('/begin=(\d+)/', $line, $match_begin);
                $begin = $match_begin[1] - 1;
                $state = 2;
                $var = fseek($file, $begin, SEEK_SET);
                break;

            } else {
                // we assume there is no ypart header
                $begin = 0;
                $state = 2;
                $var = fseek($file, $begin, SEEK_SET);
                // fall through
            }
        case 2:
            if (isset($line[4]) && substr_compare($line, '=yend', 0, 5) == 0) {
                @fclose($file);
                $state = 0;
            } else {
                $l = (int) strlen($line);
                for ($i = (int) 0; $i < $l; ++$i) {
                    if ($line[$i] == '=') {
                        $decoded .= chr(ord($line[++$i]) - 106);
                    } else {
                        $decoded .= chr(ord($line[$i]) - 42);
                    }
                }
                fwrite($file, $decoded);
                $decoded = '';
            }
            break;
        case 3:
            fclose($file);
            $state = 0;
            break;
        case 4:
            break;
        case 6:
            if ($line == 'end') {
                fclose($file);
                $state = 0;
            } else {
                $decoded = convert_uudecode($line);
                fwrite($file, $decoded);
            }
            break;
    }
}

set_time_limit(0);

$file = '';
for ($i=1; $i < $argc; $i++) {
    if ($argv[$i][0] == '-' && isset($argv[$i][1])) {
        switch ($argv[$i][1]) {
            case 'D':
                $directory = substr($argv[$i], 2);
                break;
            case 'e':
            case 'b':
            case 'f':
                break;
            default:
                die('Unknown parameter');
        }
    } else {
        $file = $argv[1];
    }
}

if ($file == '' || $file == '-') {
    $input_file = fopen('php://stdin', 'r');
} else {
    $input_file = fopen($file, 'r');
}

$err_level = error_reporting(0); // so we don't get any write errors, saves a @
while (!feof($input_file)) {
    $line = rtrim(fgets($input_file), "\n\r");
    if ($line === FALSE) {
        break;
    }
    ydecode_line($line);
}
