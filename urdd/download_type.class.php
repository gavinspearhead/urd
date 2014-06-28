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
 * $LastChangedDate: 2013-07-03 23:22:21 +0200 (wo, 03 jul 2013) $
 * $Rev: 2859 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: download_functions.php 2859 2013-07-03 21:22:21Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

define ('RAR_MARKHEADER_SIGN_', 'Rar!' . chr(0x1a) . chr(0x07) . chr(0x00)); // nasty


class download_type
{
    const RARSCAN_QUEUESIZE = 15;  // Number of article entries in the chunk meta data queue, gives yydecode time to write data before we read it. Per DL thread.

    // encoding types
    const TYPE_UUENCODED    = 1;
    const TYPE_YYENCODED    = 2;
    const TYPE_XXENCODED    = 3;
    const TYPE_MIMEENCODED  = 4;
    const TYPE_UNKNOWN      = 255;

    const MAX_ART_LINE_COUNT = 50;
    const RAR_MARKHEADER_SIGN = RAR_MARKHEADER_SIGN_;  // RAR main header signature

    private static $chunk_buffer = array();
    private static $buffer_size = self::RARSCAN_QUEUESIZE;

    public static function get_download_type(array &$article)
    {
        /*$possible_xxencode = */
        $possible_uuencode = FALSE;
        $cnt = 0;
        foreach ($article as $line) {
            if (!isset($line[0])) { // empty line
                continue;
            } elseif (isset($line[1]) && substr_compare($line, '=y', 0, 2) == 0) {
                return self::TYPE_YYENCODED;
            } elseif ($line[0] == 'M') {
                if ($possible_uuencode === TRUE) {
                    return self::TYPE_UUENCODED;
                } else {
                    $possible_uuencode = TRUE;
                }
        /*    } elseif ($line[0] == 'h') {
                // if it is 'h' it is XXencoded... do we need that? does anyone use XXencode?
                if ($possible_xxencode === TRUE) {
                    return self::TYPE_XXENCODED;
                } else {
                    $possible_xxencode = TRUE;
                }*/
            } elseif ($cnt < 5 && stripos($line, 'This is a multipart message in MIME format.') !== FALSE) {
                return self::TYPE_MIMEENCODED;
            } // else dunno - need to look further
            ++$cnt;
            if ($cnt > self::MAX_ART_LINE_COUNT) {
                break;
            }
        }

        return self::TYPE_UNKNOWN;
    }

    public static function check_for_encrypted_rar(array $art, $dir)
    {
        $filechunkstart = $filechunkend = $filechunksize = $filename = NULL;
        $cnt = 0;

        // get filename, offset and chunk size from article
        foreach ($art as $line) {
            if (!isset($line[0])) {
                continue;
            }
            if (isset($line[1]) && substr_compare($line, '=y', 0, 2) == 0) {
                if (preg_match('/name=(.*)$/', $line, $matches)) {
                    $filename = $matches[1];
                }
                if (preg_match('/begin=(\d+)/', $line, $matches)) {
                    $filechunkstart = ($matches[1]) - 1; // yenc vars not 0-based
                }
                if (preg_match('/end=(\d+)/', $line, $matches)) {
                   $filechunkend = ($matches[1]) - 1; // yenc vars not 0-based
                }

                if (($filename !== NULL) && ($filechunkstart !== NULL) && ($filechunkend !== NULL)) {
                    break;
                }
                if (++$cnt > self::MAX_ART_LINE_COUNT) {
                    echo_debug('Could not locate needed chunk data in article header to analyse block', DEBUG_SERVER);

                    return FALSE;
                }
            }
        }

        $filechunksize = $filechunkend - $filechunkstart;
        $fileoffset = max(0, $filechunkstart - 50); // Try to read 50 chars before current block to catch headers over split articles
        $bufcnt = array_push(self::$chunk_buffer, ($dir . $filename), $fileoffset, $filechunksize); // Save chunk metadata last in queue buffer for later use
        if ($bufcnt < self::$buffer_size) { // We will not start analyzing anything until buffer is big enough - yydecode thread may not have had time to write to disk yet
            return FALSE;
        }

        // Now we read the oldest record from the buffer and process it - yydecode should have had time to write do disk by now.
        $filename      = array_shift(self::$chunk_buffer);
        $fileoffset    = array_shift(self::$chunk_buffer);
        $filechunksize = array_shift(self::$chunk_buffer);
        $data = file_get_contents($filename, FALSE, NULL, $fileoffset, $filechunksize + 50); // OK to add compensating 50 since it won't read past EOF anyway
        if ($data === FALSE) {
            write_log("Could not read file: $filename", LOG_WARNING);

            return FALSE;
        }
        $chunksizeread = strlen($data);
        if ($chunksizeread < $filechunksize) {
            write_log("Unexpected chunk size read: $chunksizeread, expected (at least) $filechunksize. File: $filename Offset: $fileoffset", LOG_WARNING);
            write_log('If you see this, try increasing RARSCAN_QUEUESIZE slightly since it may mean yydecode is falling behind', LOG_WARNING);

            return FALSE;
            //   } else {
            // echo_debug("Data chunk OK. File: $filename Offset: $fileoffset Read chunk size: $chunksizeread", DEBUG_SERVER);
        }
        // Loop through any "Rar!..." matches in read data and look for encryption flags if header found
        $dataoffset = strpos($data, self::RAR_MARKHEADER_SIGN);
        while ($dataoffset !== FALSE) {
            echo_debug("Rar header found in file $filename at data offset $dataoffset", DEBUG_SERVER);
            if ($dataoffset >= $filechunksize - 25) { // Safety - we may end up reading memory past end of string. We'll catch this next round instead

                return FALSE;
            }
            if ((ord($data[$dataoffset + 10]) & 0x80) === 0x80) {
                echo_debug("Rar block encryption detected in file $filename, header at file offset " . ($dataoffset + $fileoffset), DEBUG_SERVER);

                return TRUE;
            }
            if ((ord($data[$dataoffset + 23]) & 0x04) === 0x04) {
                echo_debug("Rar file encryption detected in file $filename, header at file offset " . ($dataoffset + $fileoffset), DEBUG_SERVER);

                return TRUE;
            }
            $dataoffset = strpos($data, self::RAR_MARKHEADER_SIGN, $dataoffset + 7); // Continue searching right after last "Rar!...", there may be more. Also, fake Rar!... headers won't fool us :P
        }

        return FALSE; // No encryption flags found if we get here
    }
}
