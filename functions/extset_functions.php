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
 * $LastChangedDate: 2011-03-05 23:48:07 +0100 (Sat, 05 Mar 2011) $
 * $Rev: 2093 $
 * $Author: gavinspearhead $
 * $Id: web_functions.php 2093 2011-03-05 22:48:07Z gavinspearhead $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathxf = realpath(dirname(__FILE__));

require_once "$pathxf/autoincludes.php";

class urd_extsetinfo
{
    const SETTYPE_UNKNOWN = 0;
    const SETTYPE_MOVIE = 1;
    const SETTYPE_ALBUM = 2;
    const SETTYPE_IMAGE = 3;
    const SETTYPE_SOFTWARE = 4;
    const SETTYPE_TVSERIES = 5;
    const SETTYPE_EBOOK = 6;
    const SETTYPE_DOCUMENTARY = 7;
    const SETTYPE_GAME = 8;
    const SETTYPE_TVSHOW = 9;
    const SETTYPE_OTHER = 100;

    static $SETTYPES = array (
            self::SETTYPE_UNKNOWN, self::SETTYPE_MOVIE, self::SETTYPE_ALBUM,self::SETTYPE_IMAGE,
            self::SETTYPE_SOFTWARE, self::SETTYPE_TVSERIES, self::SETTYPE_EBOOK, self::SETTYPE_GAME,
            self::SETTYPE_DOCUMENTARY, self::SETTYPE_TVSHOW, self::SETTYPE_OTHER
    );

    public static function generate_set_info(array $extsetinfo)
    {
        global $LN;
        // This function determines what extsetinfo can be created for a set. Used for editing sets manually.

        // Generic fields:
        $fields = array('name');
        $addfields = array();

        // What fields do we show? Depends on the type of file. What files are there? Do we put it in the db or hardcode it?
        // Questions, questions! Let's start by hardcoding it for now.
        switch ($extsetinfo['binarytype']) {
            case self::SETTYPE_MOVIE :       $addfields = array('year', 'quality', 'score', 'movieformat', 'lang', 'sublang', 'audioformat', 'moviegenre', 'link', 'xrated', 'note', 'runtime'); break;
            case self::SETTYPE_ALBUM :       $addfields = array('year', 'quality', 'score', 'musicformat', 'musicgenre', 'link', 'xrated', 'note', 'runtime'); break;
            case self::SETTYPE_IMAGE :       $addfields = array('quality', 'score', 'imageformat', 'imagegenre', 'xrated', 'note'); break;
            case self::SETTYPE_SOFTWARE :    $addfields = array('quality', 'os', 'softwareformat', 'softwaregenre', 'lang', 'xrated', 'note'); break;
            case self::SETTYPE_TVSERIES :    $addfields = array('quality', 'score', 'movieformat', 'moviegenre', 'audioformat', 'episode', 'link', 'xrated', 'note', 'runtime'); break;
            case self::SETTYPE_EBOOK :       $addfields = array('author', 'score', 'quality', 'ebookformat', 'genericgenre'); break;
            case self::SETTYPE_TVSHOW :      $addfields = array('quality', 'score', 'movieformat', 'moviegenre', 'audioformat', 'episode', 'link', 'xrated', 'note', 'runtime'); break;
            case self::SETTYPE_DOCUMENTARY : $addfields = array('year', 'quality', 'score', 'movieformat', 'lang', 'sublang', 'audioformat', 'link', 'xrated', 'note'); break;
            case self::SETTYPE_GAME :        $addfields = array('quality', 'score','os', 'gameformat', 'gamegenre', 'lang', 'xrated', 'note'); break;
            case self::SETTYPE_OTHER :       $addfields = array('quality', 'genericgenre', 'xrated', 'note'); break;
            case self::SETTYPE_UNKNOWN :     $addfields = array('link'); break;
        }

        // Add extra fields regardless of type:
        $addfields[] = 'password';
        $addfields[] = 'copyright';

        // Combine:
        $fields = array_merge($fields,$addfields);
        // Order is important because it determines the order in which the fields are displayed when editing...


        // Example values:
        $rating = array('');
        for ($x = 1; $x <= 10; $x += 0.5) {
            $rating[] = $x;
        }

        $movieformat = array('', '720p', '1080i', '1080p', '2160p', '4320p', 'xvid', 'divx', 'bluray', 'avi', 'mp4', 'mpg', 'mov', 'wmv', 'black/white', 'H.264', 'dvd', 'vhs', 'other');
        $audioformat = array('', 'mono', 'stereo', 'Dolby Digital', 'DTS', 'Silent', 'other');
        $musicformat = array('', 'mp3', 'ogg', 'wma', 'ape', 'wav', 'flac', 'mpc', 'other');
        $imageformat = array('', 'jpg', 'png', 'bmp', 'psd', 'gif', 'raw', 'tiff', 'svg', 'other');
        $softwareformat = array('', 'iso', 'bin', 'zip', 'exe', 'msi', 'source', 'rpm', 'deb', 'rar', '7z', 'arc', 'zoo', 'arj', 'lha', 'other');
        $gameformat = array('', 'iso', 'bin', 'zip', 'exe', 'msi', 'source', 'rpm', 'deb', 'rar', '7z', 'arc', 'zoo', 'arj', 'lha', 'other');
        $ebookformat = array('', 'epub', 'mobi', 'txt', 'lit', 'pdf', 'html', 'audio', 'other');
        $os = array('', 'winall', 'windows', 'windows 64bit', 'windows mobile', 'linux', 'macos', 'bsd', 'symbian', 'iphone', 'android', 'os-independent', 'xbox', 'playstation', 'wii', 'other');

        $display = array();
        foreach ($fields as $field) {
            $displaytype = $edittype = $editvalues = '';

            switch ($field) {
                case 'name':		$displaytype = 'text';		$edittype = 'longtext';					break;
                case 'artist':		$displaytype = 'text';		$edittype = 'text';						break;
                case 'author':		$displaytype = 'text';		$edittype = 'text';						break;
                case 'lang':		$displaytype = 'text';		$edittype = 'text';						break;
                case 'sublang':		$displaytype = 'text';		$edittype = 'text';						break;
                case 'quality':		$displaytype = 'number';	$edittype = 'select';		$editvalues = $rating;		break;
                case 'movieformat':	$displaytype = 'text';		$edittype = 'select';		$editvalues = $movieformat;	break;
                case 'musicformat':	$displaytype = 'text';		$edittype = 'select';		$editvalues = $musicformat;	break;
                case 'imageformat':	$displaytype = 'text';		$edittype = 'select';		$editvalues = $imageformat;	break;
                case 'softwareformat':	$displaytype = 'text';	$edittype = 'select';		$editvalues = $softwareformat;	break;
                case 'audioformat':	$displaytype = 'text';		$edittype = 'select';		$editvalues = $audioformat;	break;
                case 'ebookformat':	$displaytype = 'text';		$edittype = 'select';		$editvalues = $ebookformat;	break;
                case 'gameformat':	$displaytype = 'text';		$edittype = 'select';		$editvalues = $gameformat;	break;
                case 'moviegenre':	$displaytype = 'text';		$edittype = 'text';						break;
                case 'gamegenre':	$displaytype = 'text';		$edittype = 'text';						break;
                case 'musicgenre':	$displaytype = 'text';		$edittype = 'text';						break;
                case 'softwaregenre':	$displaytype = 'text';	$edittype = 'text';		    			break;
                case 'os':	    	$displaytype = 'text';		$edittype = 'select';		$editvalues = $os;		break;
                case 'year':		$displaytype = 'text';		$edittype = 'text';						break;
                case 'genericgenre':	$displaytype = 'text';	$edittype = 'text';	    				break;
                case 'episode':		$displaytype = 'text';		$edittype = 'text';						break;
                case 'score':		$displaytype = 'number';	$edittype = 'select';		$editvalues = $rating;		break;
                case 'link':		$displaytype = 'url';		$edittype = 'longtext';					break;
                case 'xrated':		$displaytype = 'checkbox';	$edittype = 'checkbox';					break;
                case 'copyright':	$displaytype = 'checkbox';	$edittype = 'checkbox';					break;
                case 'password':	$displaytype = 'text';		$edittype = 'text';						break;
                case 'note':		$displaytype = 'text';		$edittype = 'longtext';					break;
                default:		    $displaytype = 'text';	    $edittype = 'text';                     break;
            }

            $value = isset($extsetinfo[$field]) ? $extsetinfo[$field] : '';

            $display[] = array(
                    'field' => $field,
                    'name' => $LN['browse_tag_' . $field],
                    'value' => $value,
                    'edit' => $edittype,
                    'display' => $displaytype,
                    'editvalues' => $editvalues);
        }

        return $display;
    }

    public static function get_binary_types()
    {
        global $LN;

        $binarytypes = array(
                self::SETTYPE_UNKNOWN 	=> $LN['bin_unknown'],
                self::SETTYPE_MOVIE		=> $LN['bin_movie'],
                self::SETTYPE_ALBUM 		=> $LN['bin_album'],
                self::SETTYPE_IMAGE 		=> $LN['bin_image'],
                self::SETTYPE_SOFTWARE 	=> $LN['bin_software'],
                self::SETTYPE_TVSERIES 	=> $LN['bin_tvseries'],
                self::SETTYPE_EBOOK	 	=> $LN['bin_ebook'],
                self::SETTYPE_GAME	 	=> $LN['bin_game'],
                self::SETTYPE_DOCUMENTARY	=> $LN['bin_documentary'],
                self::SETTYPE_TVSHOW	 	=> $LN['bin_tvshow'],
                self::SETTYPE_OTHER 		=> $LN['bin_other']
                );

        return $binarytypes;
    }

    private static function transpose_spot_to_extset($spot_cat, $subcata, $subcatb, $subcatc, $subcatd, $subcate, array &$match)
    {
        switch ($spot_cat) {
            case 0:
                if (strpos($subcata, 'a0|') !== FALSE) {
                    $match['divx'] = 'a0';
                }
                if (strpos($subcata, 'a2|') !== FALSE) {
                    $match['.mpg'] = 'a2';
                }
                if (strpos($subcata, 'a6|') !== FALSE) {
                    $match['bluray'] = 'a6';
                }
                break;
            case 1:
                if (strpos($subcata, 'a0|') !== FALSE) {
                    $match['.mp3'] = 'a0';
                }
                if (strpos($subcata, 'a1|') !== FALSE) {
                    $match['.wma'] = 'a1';
                }
                if (strpos($subcata, 'a3|') !== FALSE) {
                    $match['.ogg'] = 'a3';
                }
                if (strpos($subcata, 'a7|') !== FALSE) {
                    $match['.ape'] = 'a7';
                }
                if (strpos($subcata, 'a8|') !== FALSE) {
                    $match['.flac'] = 'a8';
                }
                break;
            case 2:
                break;
            case 3:
                break;
        }
    }

    private static function guess_set_type(DatabaseConnection $db, $setid, $groupname, $originalsetname, $origin_type)
    {
        $result = array();
        $result[self::SETTYPE_MOVIE] = $result[self::SETTYPE_TVSERIES] = $result[self::SETTYPE_ALBUM] = $result[self::SETTYPE_IMAGE] = $result[self::SETTYPE_SOFTWARE] = 0;
        $result[self::SETTYPE_EBOOK] = $result[self::SETTYPE_GAME] = $result[self::SETTYPE_DOCUMENTARY] = $result[self::SETTYPE_TVSHOW] = $result[self::SETTYPE_UNKNOWN] = 0;

        // Check the groupname for hints:

        if ($origin_type == USERSETTYPE_SPOT) {
            /// must do something with subcats here TODO
            $spot_cat = $groupname[0];
            $subcata = $groupname[1];
            $subcatb = $groupname[2];
            $subcatc = $groupname[3];
            $subcatd = $groupname[4];
            $subcate = $groupname[5];
            if ($spot_cat == 0) {
                $result[self::SETTYPE_MOVIE] += 6;
                $result[self::SETTYPE_TVSERIES] += 6;
                $result[self::SETTYPE_TVSHOW] += 3;
                $result[self::SETTYPE_DOCUMENTARY] += 3;
                $result[self::SETTYPE_IMAGE] += 3;
                $result[self::SETTYPE_EBOOK] += 2;
                if (strpos($subcata, 'z0|')!== FALSE) {
                    $result[self::SETTYPE_MOVIE] +=10;
                }
                if (strpos($subcata, 'z1|')!== FALSE) {
                    $result[self::SETTYPE_TVSERIES] +=10;
                }
                if (strpos($subcata, 'z2|')!== FALSE) {
                    $result[self::SETTYPE_BOOK] += 10;
                }
                if (strpos($subcata, 'd6|')!== FALSE) {
                    $result[self::SETTYPE_DOCUMENTARY] +=10;
                }
                if (strpos($subcata, 'd11|')!== FALSE) {
                    $result[self::SETTYPE_TVSERIES] +=10;
                }
            } elseif ($spot_cat == 1) {
                $result[self::SETTYPE_ALBUM] += 10;
            } elseif ($spot_cat == 2) {
                $result[self::SETTYPE_GAME] += 10;
            } elseif ($spot_cat == 3) {
                $result[self::SETTYPE_SOFTWARE] += 10;
            }
        }

        $bintype = '';
        if ($origin_type != USERSETTYPE_SPOT) {
            $type = array();
            $type[self::SETTYPE_MOVIE] = array('movie', 'hdtv', 'xvid', 'divx', 'multimedia', 'dvd', 'vcd');
            $type[self::SETTYPE_TVSERIES] = array('movie', 'hdtv', 'xvid', 'divx', 'multimedia', 'dvd', 'vcd', 'tvseries', '.tv');
            $type[self::SETTYPE_ALBUM] = array('mp3', 'album', 'music', 'sounds', 'metal', 'rock', 'trance', 'dance', 'jazz');
            $type[self::SETTYPE_IMAGE] = array('images', 'pictures');
            $type[self::SETTYPE_SOFTWARE] = array('.x', '.core', 'boneless', 'warez', 'osx');
            $type[self::SETTYPE_EBOOK] = array('ebook', 'e-book');
            $type[self::SETTYPE_GAME] = array('game', 'xbox', 'playstation', 'wii', 'nintendo', 'ps3', 'ps2');
            $type[self::SETTYPE_DOCUMENTARY] = array('documentary', 'documentaries', 'reportages');
            $type[self::SETTYPE_TVSHOW] = array('xvid', 'divx', '.tv');

            foreach ($type as $bintype => $binarray) {
                foreach ($binarray as $groupstring) {
                    if (stripos($groupname, $groupstring) !== FALSE) {
                        $result[$bintype]++;
                    }
                }
            }
        }

        // Check the setname for tell-tale signs:
        // And store matches so we can use them for filling in data later on:
        unset($type);
        $type = array();
        $match = array();
        $type[self::SETTYPE_MOVIE] = array('.avi', '.mkv', '.mpg', '.mov', 'xvid', 'divx', '720p', '1080i', '1080p',  '2160p', '4320p', 'bluray', 'blu-ray', 'bd5', 'bd9', 'x264', 'dts', 'ac3', 'vhs', 'mp4');
        $type[self::SETTYPE_TVSERIES] = array('.avi', '.mkv', '.mpg', '.mov', 'xvid', 'divx', '720p', '1080i', '1080p', 'hdtv', 'season', 'dts', 'bd5', 'bd9', 'ac3', 'mp4');
        $type[self::SETTYPE_ALBUM] = array('.mp3', '.m3u', '.flac', '.wma', '.ogg', '.ape', '.wav', '.aac');
        $type[self::SETTYPE_IMAGE] = array('.jpg', '.png', '.gif', '.bmp', '.jpeg', '.tiff');
        $type[self::SETTYPE_SOFTWARE] = array('.iso', '.nrg', '.zip', 'osx');
        $type[self::SETTYPE_EBOOK] = array('.pdf', '.prc', '.kml', 'ebook', 'e-book', 'magazine', 'isbn', 'epub', 'mobi');
        $type[self::SETTYPE_GAME] = array('.iso', 'xbox', 'playstation', 'wii', 'nintendo');
        $type[self::SETTYPE_DOCUMENTARY] = array('.avi', '.mkv', '.mpg', '.mov', 'xvid', 'divx', '720p', '1080i', '1080p', 'hdtv', 'mp4', '2160p', '4320p',);
        $type[self::SETTYPE_TVSHOW] = array('.avi', '.mkv', '.mpg', '.mov', 'xvid', 'divx', '720p', '1080i', '1080p', 'hdtv');

        foreach ($type as $bintype => $binarray) {
            foreach ($binarray as $setstring) {
                if (stripos($originalsetname, $setstring) !== FALSE) {
                    $result[$bintype] += 2;
                    $match[$setstring] = TRUE;
                }
            }
        }

        // Adding an extra series-check by looking for sXXeXX strings:
        if (preg_match('/s([0-9]{1,2})[._ ]{0,1}e([0-9]{1,2})/i', $originalsetname, $prmatch)) {
            $result[self::SETTYPE_TVSERIES] += 5;
            $match['episode'] = 's' . $prmatch[1] . 'e' . $prmatch[2];
        } elseif (preg_match('/[^a-z0-9]([0-9]{1,2})x([0-9]{2})[^0-9]/i', $originalsetname, $prmatch)) {
            $result[self::SETTYPE_TVSERIES] += 4;
            $match['episode'] = 's' . $prmatch[1] . 'e' . $prmatch[2];
        } elseif (preg_match('/s([0-9]{1,2})[._ ]{0,1}d([0-9]{1,2})/i', $originalsetname, $prmatch)) {
            $result[self::SETTYPE_TVSERIES] += 4;
            $match['episode'] = 's' . $prmatch[1] . 'd' . $prmatch[2];
        } else {
            // If there's no episode thing, it's probably not a series or tvshow
            $result[self::SETTYPE_TVSERIES] -= 2;
        }

        // Look for year info, probably a movie or album then:
        if (preg_match('/[^0-9](19[0-9]{2}|20[012][0-9])[^0-9]/', $originalsetname, $prmatch)) {
            $result[self::SETTYPE_MOVIE] += 2;
            $result[self::SETTYPE_ALBUM] += 2;
            $match['year'] = $prmatch[1];
        }
        // Does it have a YYYYxMMxDD string or DDxMMxYYYY string?
        if (preg_match('/[^0-9](19[0-9]{2}|20[012][0-9])[^0-9]([0-9]{1,2})[^0-9]([0-9]{1,2})[^0-9]/', $originalsetname, $prmatch)) {
            $result[self::SETTYPE_TVSHOW] += 8;

            // Trim leading 0's:
            $cleanprmatch[] = array();
            foreach ($prmatch as $key => $mitem) {
                $cleanprmatch[$key] = ((int) $mitem < 10 ? '0' : '') . (int) $mitem;
            }
            $match['episode'] = $cleanprmatch[3] . '-' . $cleanprmatch[2] . '-' . $cleanprmatch[1];
        } elseif (preg_match('/[^0-9]([0-9]{1,2})[^0-9]([0-9]{1,2})[^0-9](19[0-9]{2}|20[012][0-9])/', $originalsetname, $prmatch)) {
            $result[self::SETTYPE_TVSHOW] += 8;

            // Trim leading 0's:
            $cleanprmatch[] = array();
            foreach ($prmatch as $key => $mitem) {
                $cleanprmatch[$key] = ((int) $mitem < 10 ? '0' : '') . (int) $mitem;
            }
            $match['episode'] = $cleanprmatch[1] . '-' . $cleanprmatch[2] . '-' . $prmatch[3];
        } else {
            $result[self::SETTYPE_TVSHOW] -= 1;
        }

        // Process by assuming the highest result is the right binarytype:
        arsort($result);
        list($key, $val) = each($result);

        return array($key, $val, $match);
    }

    public static function guess_more_data($val, $key, $newsetname, $groupname, array $match, array &$save, $origin_type)
    {/// need to match to spots too
        if ($origin_type == USERSETTYPE_SPOT) {
            $spot_cat = $groupname[0];
            $subcata = $groupname[1];
            $subcatb = $groupname[2];
            $subcatc = $groupname[3];
            $subcatd = $groupname[4];
            $subcate = $groupname[5];

            self::transpose_spot_to_extset($spot_cat, $subcata, $subcatb, $subcatc, $subcatd, $subcate, $match);
        }

        if ($val !== 0) {// If we don't know the filetype, we only set the name.
            switch ($key) {
                case self::SETTYPE_MOVIE:
                    if (isset($match['720p']))	$save['quality'] = '9';
                    if (isset($match['1080i']))	$save['quality'] = '9.5';
                    if (isset($match['1080p']))	$save['quality'] = '10';
                    if (isset($match['bluray']))	$save['movieformat'] = 'bluray';
                    if (isset($match['blu-ray']))	$save['movieformat'] = 'bluray';
                    if (isset($match['720p']))	$save['movieformat'] = '720p';
                    if (isset($match['1080i']))	$save['movieformat'] = '1080i';
                    if (isset($match['1080p']))	$save['movieformat'] = '1080p';
                    if (isset($match['.avi']))	$save['movieformat'] = 'avi';
                    if (isset($match['xvid']))	$save['movieformat'] = 'xvid';
                    if (isset($match['divx']))	$save['movieformat'] = 'divx';
                    if (isset($match['vhs']))	$save['movieformat'] = 'vhs';
                    if (isset($match['.mpg']))	$save['movieformat'] = 'mpg';
                    if (isset($match['.mov']))	$save['movieformat'] = 'mov';
                    if (isset($match['bd5']))	$save['movieformat'] = 'bluray';
                    if (isset($match['bd9']))	$save['movieformat'] = 'bluray';
                    if (isset($match['dts']))	$save['audioformat'] = 'DTS';
                    if (isset($match['ac3']))	$save['audioformat'] = 'Dolby Digital';
                    if (isset($match['year']))	$save['year'] = $match['year'];
                    break;
                case self::SETTYPE_TVSERIES:
                    if (isset($match['720p']))	$save['quality'] = '9';
                    if (isset($match['1080i']))	$save['quality'] = '9.5';
                    if (isset($match['1080p']))	$save['quality'] = '10';
                    if (isset($match['bluray']))	$save['movieformat'] = 'bluray';
                    if (isset($match['blu-ray']))	$save['movieformat'] = 'bluray';
                    if (isset($match['720p']))	$save['movieformat'] = '720p';
                    if (isset($match['1080i']))	$save['movieformat'] = '1080i';
                    if (isset($match['1080p']))	$save['movieformat'] = '1080p';
                    if (isset($match['.avi']))	$save['movieformat'] = 'avi';
                    if (isset($match['xvid']))	$save['movieformat'] = 'xvid';
                    if (isset($match['divx']))	$save['movieformat'] = 'divx';
                    if (isset($match['.mpg']))	$save['movieformat'] = 'mpg';
                    if (isset($match['.mov']))	$save['movieformat'] = 'mov';
                    if (isset($match['bd5']))	$save['movieformat'] = 'bluray';
                    if (isset($match['bd9']))	$save['movieformat'] = 'bluray';
                    if (isset($match['dts']))	$save['audioformat'] = 'DTS';
                    if (isset($match['episode']))	$save['episode'] = $match['episode'];
                    break;
                case self::SETTYPE_ALBUM:
                    if (isset($match['.mp3']))	$save['musicformat'] = 'mp3';
                    if (isset($match['.m3u']))	$save['musicformat'] = 'mp3';
                    if (isset($match['.flac']))	$save['musicformat'] = 'flac';
                    if (isset($match['.wma']))	$save['musicformat'] = 'wma';
                    if (isset($match['.ogg']))	$save['musicformat'] = 'ogg';
                    if (isset($match['.ape']))	$save['musicformat'] = 'ape';
                    if (isset($match['year']))	$save['year'] = $match['year'];
                    break;
                case self::SETTYPE_TVSHOW:
                    if (isset($match['720p']))	$save['quality'] = '9';
                    if (isset($match['1080i']))	$save['quality'] = '9.5';
                    if (isset($match['1080p']))	$save['quality'] = '10';
                    if (isset($match['bluray']))	$save['movieformat'] = 'bluray';
                    if (isset($match['blu-ray']))	$save['movieformat'] = 'bluray';
                    if (isset($match['720p']))	$save['movieformat'] = '720p';
                    if (isset($match['1080i']))	$save['movieformat'] = '1080i';
                    if (isset($match['1080p']))	$save['movieformat'] = '1080p';
                    if (isset($match['.avi']))	$save['movieformat'] = 'avi';
                    if (isset($match['xvid']))	$save['movieformat'] = 'xvid';
                    if (isset($match['divx']))	$save['movieformat'] = 'divx';
                    if (isset($match['.mpg']))	$save['movieformat'] = 'mpg';
                    if (isset($match['.mov']))	$save['movieformat'] = 'mov';
                    if (isset($match['bd5']))	$save['movieformat'] = 'bluray';
                    if (isset($match['bd9']))	$save['movieformat'] = 'bluray';
                    if (isset($match['dts']))	$save['audioformat'] = 'DTS';
                    if (isset($match['episode']))	$save['episode'] = $match['episode'];
                    break;
                case self::SETTYPE_IMAGE:
                case self::SETTYPE_EBOOK:
                case self::SETTYPE_GAME:
                case self::SETTYPE_DOCUMENTARY:
                case self::SETTYPE_SOFTWARE:
                    break;
            }

        }
        // Set the binarytype:
        $save['binarytype'] = $key;
        if ($newsetname != '') {
            // Here's where HAL comes in.
            // Setname probably has underscores or periods that should be replaced by blanks:
            $newsetname = str_replace(array('.', '_'), ' ', $newsetname);

            // Save the new setname:
            $save['name'] = trim($newsetname, "- \x0b\n\t\0\r");
        }
        if ($origin_type != USERSETTYPE_SPOT) {
            if (check_xrated($groupname)) {
                $save['xrated'] = 1;
            }
        } else {
            $spot_cat = $groupname[0];
            $subcata = $groupname[1];
            $subcatb = $groupname[2];
            $subcatc = $groupname[3];
            $subcatd = $groupname[4];
            $subcate = $groupname[5];
            if ($spot_cat == 0) {
                if (strpos($subcate, 'z5|')!== FALSE || strpos($subcatd, 'd23|') !== FALSE
                        || strpos($subcatd,'d24|')!== FALSE || strpos($subcatd, 'd25|') !== FALSE
                        || strpos($subcatd, 'd26|')!== FALSE || strpos($subcatd, 'd72|')!== FALSE
                        || strpos($subcatd, 'd73|')!== FALSE
                        || strpos($subcatd, 'd74|')!== FALSE || strpos($subcatd, 'd75|') !== FALSE
                        || strpos($subcatd, 'd76|')!== FALSE || strpos($subcatd, 'd77|')!== FALSE
                        || strpos($subcatd, 'd78|')!== FALSE || strpos($subcatd, 'd79|') !== FALSE
                        || strpos($subcatd, 'd80|') !== FALSE|| strpos($subcatd, 'd81|') !== FALSE
                        || strpos($subcatd, 'd82|')!== FALSE || strpos($subcatd, 'd83|')!== FALSE
                        || strpos($subcatd, 'd84|')!== FALSE || strpos($subcatd, 'd85|')!== FALSE
                        || strpos($subcatd, 'd86|')!== FALSE || strpos($subcatd, 'd87|')!== FALSE
                        || strpos($subcatd, 'd88|')!== FALSE || strpos($subcatd, 'd89|')!== FALSE){
                    $save['xrated'] = 1;
                }
            }
        }
    }

    public static function save_extsetinfo(DatabaseConnection $db, $setid, $userid, array $namevalues, $type, $overwrite = TRUE)
    {
        global $LN;
        assert (is_numeric($userid) && is_bool($overwrite));
        if (!urd_user_rights::is_seteditor($db, $userid)) {
            throw new exception($LN['error_accessdenied']);
        }

        // Save changes:
        $newsetname = self::generate_set_name($db, $namevalues);
        foreach ($namevalues as $name => $value_x) {
            $value = (trim($value_x));
            $fieldEmpty = ($value == ''); // Empty field?

            // Does info exist?
            $sql = '* FROM extsetdata WHERE "setID"=? AND "name"=? AND "type"=?';
            $res = $db->select_query($sql, 1, array($setid, $name, $type));
            // Check if the field was empty, and if the stored field exists

            if ($res === FALSE && !$fieldEmpty) {
                // New data:
                $db->insert_query('extsetdata', array('setID', 'name', 'value', 'committed', 'type'), array($setid, $name, $value, ESI_NOT_COMMITTED, $type) );
            } elseif ($res !== FALSE && ($overwrite === TRUE || (isset($res[0]['value']) && trim($res[0]['value']) == ''))) {
                // OK, something existed:
                $db->update_query_2('extsetdata', array('value'=>$value, 'committed'=>ESI_NOT_COMMITTED), '"setID"=? AND "name"=? AND "type"=?', array($setid, $name, $type));
            } elseif ($res !== FALSE && $overwrite === FALSE) {
                // skip it but set the value in the array to the original value
                if (isset($res[0][$name])) {
                    $namevalues[ $res[0][$name] ] = utf8_decode($res[0]['value']);
                }
            }
        }
        // Update setname:

        $sql = '"setID" FROM extsetdata WHERE "setID"=? AND "name"=? AND "type"=?';
        $res = $db->select_query($sql, 1, array($setid, 'setname', $type));
        if ($res === FALSE) {
            $db->insert_query('extsetdata', array('setID', 'name', 'value', 'committed', 'type'), array($setid, 'setname', $newsetname, 0, $type));
        } else {
            $db->update_query_2('extsetdata', array('value'=>$newsetname, 'committed'=>ESI_NOT_COMMITTED), '"setID"=? AND "name"=? AND "type"=?', array($setid, 'setname', $type));
        }

        return $newsetname;
    }

    public static function do_magic_nfo_extsetinfo_file(DatabaseConnection $db, $path, $file, $binary_id, $group_id, $userid)
    {
        //	here we try to guess ext set info from the nfo file
        assert (is_numeric($group_id) && $binary_id != '' && is_numeric($userid));
        $db->escape($group_id, FALSE);
        $sql = "\"setID\" FROM binaries_$group_id WHERE \"binaryID\"=?";
        $rv = $db->select_query($sql, 1, array($binary_id));
        if ($rv === FALSE) {
            return;
        }

        $setid = $rv[0]['setID'];
        $origine_type = USERSETTYPE_GROUP;
        try {
            list($originalsetname, $groupname, $size) = get_set_info($db, $setid, $origine_type);
        } catch (exception $e) {
            return;
        }
        list($key, $val, $match) = self::guess_set_type($db, $setid, $groupname, $originalsetname, $size);
        if ($val == 0) {
            $key = self::guess_type_by_size($size);
        }
        $save = nfo_parser::parse_nfo_file($path . $file, $key);
        self::guess_more_data($val, $key, '', $groupname, $match, $save, $origine_type);
        $save = array_map('utf8_encode', $save);
        self::store_ext_setdata($db, $setid, $save, $origine_type, ESI_NOT_COMMITTED);
        self::save_extsetinfo($db, $setid, $userid, $save, $origine_type, FALSE);
    }

    private static function guess_type_by_size($size)
    {
        assert(is_numeric($size));
        if ($size > (500 * 1024 *1024)) {
            $key = self::SETTYPE_MOVIE;
        } elseif ($size > (300 * 1024 * 1024)) {
            $key = self::SETTYPE_TVSERIES;
        } elseif ($size > (60 * 1024 * 1024)) {
            $key = self::SETTYPE_ALBUM;
        } else {
            $key = self::SETTYPE_IMAGE;
        }

        return $key;
    }

    // partly redundant with ajax_showpreview xxx
    public static function do_magic_nfo_extsetinfo_contents(DatabaseConnection $db, $contents, $binary_id, $group_id, $userid, $setid)
    {
        //	here we try to guess ext set info from the nfo file
        //
        assert(is_numeric($group_id) && $binary_id != '' && is_numeric($userid));

        $origin_type = USERSETTYPE_GROUP;

        list($originalsetname, $groupname, $size) = get_set_info($db, $setid, $origin_type);
        list($key, $val, $match) = self::guess_set_type($db, $setid, $groupname, $originalsetname, $origin_type);

        if ($val == 0) {
            $key = self::guess_type_by_size($size);
        }
        $dont_follow = (get_config($db, 'follow_link') == 0 ) ? TRUE : FALSE;
        //$dont_follow = FALSE;
        $save = nfo_parser::find_info($contents, $key, $dont_follow);
        self::guess_more_data($val, $key, '', $groupname, $match, $save, $origin_type);
        $save = array_map('utf8_encode', $save);
        self::store_ext_setdata($db, $setid, $save, $origin_type, ESI_NOT_COMMITTED);
        self::save_extsetinfo($db, $setid, $userid, $save, $origin_type, FALSE);
    }

    public static function check_extset_link_exists(DatabaseConnection $db, $setid, $type)
    {
        $search_type = $db->get_pattern_search_command('REGEXP');
        $sql = " * FROM extsetdata WHERE \"setID\" = ? AND \"type\"=? AND \"name\" = ? AND \"value\" $search_type ?";
        $res = $db->select_query($sql, 1, array($setid, $type, 'link', '^https?://'));

        return $res !== FALSE;
    }

    public static function guess_extsetinfo_safe(DatabaseConnection $db, $setid, $origin_type, $userid)
    {
        global $LN;
        assert (in_array($origin_type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS)) && is_numeric($userid));
        if (!urd_user_rights::is_seteditor($db, $userid)) {
            return ($LN['error_accessdenied']);
        }

        $save = array();

        // Get info (original set name and group name) for this setID:
        list($originalsetname, $groupname) = get_set_info($db, $setid, $origin_type);
        // Guess the binarytype, this returns an array of the binarytype, score, and an array of matching keywords
        list($bintype, $score, $match) = self::guess_set_type($db, $setid, $groupname, $originalsetname);

        // This is the setname given by the $bintype,$score, $matchuser:
        $newsetname = get_request('setname', '');
        self::guess_more_data($score, $bintype, $newsetname, $groupname, $match, $save, $origin_type);

        // Should now save the values that we could find. They shouldn't be sent to urdland, as it
        // could be totally wrong. Even if the user changes the binarytype and puts in the correct
        // values, the old ones would still exist if they didn't apply to the new binarytype.
        // We can't expect the user to clear the wrong data before switching to the right bintype.
        // So we save them with the 'do not commit' flag and when the user applies, they get changed to 'commit'.
        self::store_ext_setdata($db, $setid, $save, $origin_type, ESI_COMMITTED, TRUE);
        // We only save when there wasn't an existing value.. we're just guessing and if someone entered something, it's probably better.
    }

    public static function guess_extsetinfo(DatabaseConnection $db, $setid, $origin_type, $userid)
    {
        global $LN;
        assert (in_array($origin_type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)) && is_numeric($userid));
        if (!urd_user_rights::is_seteditor($db, $userid)) {
            die_html($LN['error_accessdenied']);
        }
        $save = array();

        // Get info (original set name and group name) for this setID:
        list($originalsetname, $groupname) = get_set_info($db, $setid, $origin_type);
        // Guess the binarytype, this returns an array of the binarytype, score, and an array of matching keywords
        list($bintype, $score, $match) = self::guess_set_type($db, $setid, $groupname, $originalsetname, $origin_type);
        // This is the setname given by the user:
        $newsetname = get_request('setname', '');
        self::guess_more_data($score, $bintype, $newsetname, $groupname, $match, $save, $origin_type);

        // Should now save the values that we could find. They will be sent to urdland,
        // we are assuming that these values are correct. The user should make sure of it but on
        // a corrective basis instead of preventive.
        $newcompletesetname = self::store_ext_setdata($db, $setid, $save, $origin_type, ESI_NOT_COMMITTED, TRUE);
        // We only save when there wasn't an existing value.. we're just guessing and if someone entered something, it's probably better.
        return $newcompletesetname;
    }

    public static function basketguess_extsetinfo(DatabaseConnection $db, $setid, $origin_type, $userid)
    {
        global $LN;
        assert (in_array($origin_type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)) && is_numeric($userid));
        if (!urd_user_rights::is_seteditor($db, $userid)) {
            die_html($LN['error_accessdenied']);
        }

        // setID is irrelevant, we do everything in the basket.
        // origin_type is irrelevant, depends on the thing in the basket.

        $save = array();

        // We should have stuff in the basket, just a check:
        if (!isset($_SESSION['setdata']) || !is_array($_SESSION['setdata'])) {
            die_html($LN['error_emptybasket']);
        }
        foreach ($_SESSION['setdata'] as $set) {
            $setid = $set['setid'];
            $origin_type = USERSETTYPE_GROUP;
            if ($set['type'] == 'rss') {
                $origin_type = USERSETTYPE_RSS;
            }

            // Get info (original set name and group name) for this setID:
            list($originalsetname, $groupname) = get_set_info($db, $setid, $origin_type);
            // Guess the binarytype, this returns an array of the binarytype, score, and an array of matching keywords
            list($bintype, $score, $match) = self::guess_set_type($db, $setid, $groupname, $originalsetname);

            // This is the setname given by the user:
            $newsetname = get_request('setname', '');
            self::guess_more_data($score, $bintype, $newsetname, $groupname, $match, $save);

            // Should now save the values that we could find. They will be sent to urdland,
            // we are assuming that these values are correct. The user should make sure of it but on
            // a corrective basis instead of preventive.
            self::store_ext_setdata($db, $setid, $save, $origin_type, ESI_NOT_COMMITTED, TRUE);
            // We only save when there wasn't an existing value.. we're just guessing and if someone entered something, it's probably better.
        }
    }

    public static function generate_set_name(DatabaseConnection $db, array $namevalues)
    {
        return (trim(self::generate_set_name_1($db, $namevalues), "- \x0b\n\t\0\r"));
    }

    public static function generate_set_name_1(DatabaseConnection $db, array $namevalues)
    {
        // When empty value is returned, the original set-name will be used.
        if (!isset($namevalues['name'])) {
            return '';
        }
        if (!isset($namevalues['binarytype'])) {
            $namevalues['binarytype'] = self::SETTYPE_UNKNOWN;
        }
        $format_string = get_config($db, 'settype_'. $namevalues['binarytype']);
        // language = ????
        if ($format_string == '') {
            $format_string = '%T %n';
        }

        switch ($namevalues['binarytype']) {
            case self::SETTYPE_MOVIE:
                $chars = 'TtnylsmaNqxPC';
                $name = GetAV($namevalues, 'name');
                $year = GetAV($namevalues, 'year');
                $lang = GetAV($namevalues, 'lang');
                $sublang = GetAV($namevalues, 'sublang');
                $movieformat = GetAV($namevalues, 'movieformat');
                $audioformat = GetAV($namevalues, 'audioformat');
                $note = GetAV($namevalues, 'note');
                $quality = GetAV($namevalues, 'quality');
                $xxx = GetAV($namevalues, 'xrated') == 1 ? 'XXX' : '';
                $password = GetAV($namevalues, 'password');
                $copyright = GetAV($namevalues, 'copyright');
                $values['P'] = ($password != '' ? 'PW' : '');
                $values['C'] = ($copyright == 1 ? '(c)' : '');
                $values['P'] = ($password != '' ? ':_img_pw:' : '');
                $values['C'] = ($copyright == 1 ? ':_img_copyright:' : '');
                $values['n'] = $name;   // name
                $values['y'] = $year;   //
                $values['l'] = $lang;   //
                $values['s'] = $sublang;
                $values['m'] = $movieformat;
                $values['a'] = $audioformat;
                $values['N'] = $note;   //
                $values['x'] = $xxx;   //
                $values['q'] = $quality;   //
                $values['t'] = 'Movie';   // type
               // $values['T'] = ':_img_movie:';   //  image placeholder
                break;
            case self::SETTYPE_ALBUM:
                $chars = 'TntyqfgNPC';
                $name = GetAV($namevalues, 'name');
                $year = GetAV($namevalues, 'year');
                $format = GetAV($namevalues, 'musicformat');
                $genre = GetAV($namevalues, 'musicgenre');
                $note = GetAV($namevalues, 'note');
                $quality = GetAV($namevalues, 'quality');
                $password = GetAV($namevalues, 'password');
                $copyright = GetAV($namevalues, 'copyright');
                $values['P'] = ($password != '' ? ':_img_pw:' : '');
                $values['C'] = ($copyright == '1' ? ':_img_copyright:' : '');
                $values['n'] = $name;
                $values['y'] = $year;
                $values['f'] = $format;
                $values['q'] = $quality;
                $values['g'] = $genre;
                $values['N'] = $note;
                $values['t'] = 'Album';   // type
              //  $values['T'] = ':_img_album:';   //  image placeholder
                break;
            case self::SETTYPE_IMAGE:
                $chars = 'TtnfgxqNPC';
                $name = GetAV($namevalues, 'name');
                $format= GetAV($namevalues, 'imageformat');
                $genre = GetAV($namevalues, 'imagegenre');
                $xxx = GetAV($namevalues, 'xrated') == 1 ? 'XXX' : '';
                $quality = GetAV($namevalues, 'quality');
                $note = GetAV($namevalues, 'note');
                $password = GetAV($namevalues, 'password');
                $copyright = GetAV($namevalues, 'copyright');
                $values['P'] = ($password != '' ? 'PW' : '');
                $values['C'] = ($copyright == '1' ? '(c)' : '');
                $values['n'] = $name;
                $values['f'] = $format;
                $values['g'] = $genre;
                $values['x'] = $xxx;
                $values['N'] = $note;
                $values['q'] = $quality;
                $values['t'] = 'Image';   // type
              //  $values['T'] = ':_img_image:';   //  image placeholder
                break;
            case self::SETTYPE_SOFTWARE:
                $chars = 'TnotNqxPC';
                $name = GetAV($namevalues, 'name');
                $os = GetAV($namevalues, 'os');
                $note = GetAV($namevalues, 'note');
                $xxx = GetAV($namevalues, 'xrated') == 1 ? 'XXX' : '';
                $quality = GetAV($namevalues, 'quality');
                $password = GetAV($namevalues, 'password');
                $copyright = GetAV($namevalues, 'copyright');
                $values['P'] = ($password != '' ? ':_img_pw:' : '');
                $values['C'] = ($copyright == '1' ? ':_img_copyright:' : '');
                $values['n'] = $name;
                $values['o'] = $os;
                $values['q'] = $quality;
                $values['x'] = $xxx;   //
                $values['N'] = $note;
                $values['t'] = 'Software';   // type
             //   $values['T'] = ':_img_software:';   // image placeholder
                break;
            case self::SETTYPE_TVSERIES:
                $chars = 'TtnemqaxNyPC';
                $name = GetAV($namevalues, 'name');
                $year = GetAV($namevalues, 'year');
                $eps = GetAV($namevalues, 'episode');
                $movieformat = GetAV($namevalues, 'movieformat');
                $audioformat = GetAV($namevalues, 'audioformat');
                $xxx = GetAV($namevalues, 'xrated') == 1 ? 'XXX' : '';
                $note = GetAV($namevalues, 'note');
                $quality = GetAV($namevalues, 'quality');

                $password = GetAV($namevalues, 'password');
                $copyright = GetAV($namevalues, 'copyright');
                $values['P'] = ($password != '' ? ':_img_pw:' : '');
                $values['C'] = ($copyright == '1' ? ':_img_copyright:' : '');
                $values['n'] = $name;   // name
                $values['y'] = $year;
                $values['e'] = $eps;
                $values['m'] = $movieformat;
                $values['a'] = $audioformat;
                $values['N'] = $note;   //
                $values['q'] = $quality;
                $values['x'] = $xxx;   //
                $values['t'] = 'Series';   // type
              //  $values['T'] = ':_img_series:';   //  image placeholder
                break;
            case self::SETTYPE_EBOOK:
                $chars = 'TtnAfqgxyPC';
                $name = GetAV($namevalues, 'name');
                $author = GetAV($namevalues, 'author');
                $format = GetAV($namevalues, 'ebookformat');
                $xxx = GetAV($namevalues, 'xrated') == 1 ? 'XXX' : '';
                $genre = GetAV($namevalues, 'genericgenre');
                $year = GetAV($namevalues, 'year');
                $note = GetAV($namevalues, 'note');
                $quality = GetAV($namevalues, 'quality');

                $password = GetAV($namevalues, 'password');
                $copyright = GetAV($namevalues, 'copyright');
                $values['P'] = ($password != '' ? ':_img_pw:' : '');
                $values['C'] = ($copyright == '1' ? ':_img_copyright:' : '');
                $values['n'] = $name;   // name
                $values['q'] = $quality;
                $values['y'] = $year;
                $values['f'] = $format;
                $values['g'] = $genre;
                $values['N'] = $note;
                $values['A'] = $author;
                $values['x'] = $xxx;
                $values['t'] = 'Ebook';   // type
              //  $values['T'] = ':_img_ebook:';   //  image placeholder
                break;
            case self::SETTYPE_GAME:
                $chars = 'TnotNqxPC';
                $name = GetAV($namevalues, 'name');
                $os = GetAV($namevalues, 'os');
                $note = GetAV($namevalues, 'note');
                $xxx = GetAV($namevalues, 'xrated') == 1 ? 'XXX' : '';
                $quality = GetAV($namevalues, 'quality');
                $password = GetAV($namevalues, 'password');
                $copyright = GetAV($namevalues, 'copyright');
                $values['P'] = ($password != '' ? ':_img_pw:' : '');
                $values['C'] = ($copyright == '1' ? ':_img_copyright:' : '');
                $values['n'] = $name;
                $values['o'] = $os;
                $values['q'] = $quality;
                $values['x'] = $xxx;   //
                $values['N'] = $note;
                $values['t'] = 'Game';   // type
             //   $values['T'] = ':_img_game:';   //  image placeholder
                break;
            case self::SETTYPE_DOCUMENTARY:
                $chars = 'TtnylsmaNqxPC';
                $name = GetAV($namevalues, 'name');
                $year = GetAV($namevalues, 'year');
                $lang = GetAV($namevalues, 'lang');
                $sublang = GetAV($namevalues, 'sublang');
                $movieformat = GetAV($namevalues, 'movieformat');
                $audioformat = GetAV($namevalues, 'audioformat');
                $note = GetAV($namevalues, 'note');
                $quality = GetAV($namevalues, 'quality');
                $xxx = GetAV($namevalues, 'xrated') == 1 ? 'XXX' : '';

                $password = GetAV($namevalues, 'password');
                $copyright = GetAV($namevalues, 'copyright');
                $values['P'] = ($password != '' ? ':_img_pw:' : '');
                $values['C'] = ($copyright == '1' ? ':_img_copyright:' : '');
                $values['n'] = $name;   // name
                $values['y'] = $year;   //
                $values['l'] = $lang;   //
                $values['s'] = $sublang;   //
                $values['m'] = $movieformat;
                $values['a'] = $audioformat;
                $values['N'] = $note;   //
                $values['x'] = $xxx;   //
                $values['q'] = $quality;   //
                $values['t'] = 'Documentary';   // type
             //   $values['T'] = ':_img_documentary:';   //  image placeholder
                break;
            case self::SETTYPE_TVSHOW:
                $chars = 'TtnmaxeNyqPC';
                $name = GetAV($namevalues, 'name');
                $year = GetAV($namevalues, 'year');
                $eps = GetAV($namevalues, 'episode');
                $quality = GetAV($namevalues, 'quality');
                $movieformat = GetAV($namevalues, 'movieformat');
                $audioformat = GetAV($namevalues, 'audioformat');
                $eps = GetAV($namevalues, 'episode');
                $xxx = GetAV($namevalues, 'xrated') == 1 ? 'XXX' : '';
                $note = GetAV($namevalues, 'note');

                $password = GetAV($namevalues, 'password');
                $copyright = GetAV($namevalues, 'copyright');
                $values['P'] = ($password != '' ? ':_img_pw:' : '');
                $values['C'] = ($copyright == '1' ? ':_img_copyright:' : '');
                $values['n'] = $name;   // name
                $values['y'] = $year;
                $values['e'] = $eps;
                $values['m'] = $movieformat;   // name
                $values['a'] = $audioformat;   // name
                $values['N'] = $note;   // name
                $values['x'] = $xxx;   // name
                $values['q'] = $quality;   // name
                $values['t'] = 'TVShow';   // string
            //    $values['T'] = ':_img_tvshow:';   // image placeholder
                break;
            case self::SETTYPE_UNKNOWN:
            case self::SETTYPE_OTHER:
            default:
                $chars = 'ntTPC';
                $password = GetAV($namevalues, 'password');
                $copyright = GetAV($namevalues, 'copyright');
                $values['P'] = ($password != '' ? ':_img_pw:' : '');
                $values['C'] = ($copyright == '1' ? ':_img_copyright:' : '');
                $values['n'] = GetAV($namevalues, 'name');   // name
                $values['t'] = 'Unknown';   // type
             //   $values['T'] = ':_img_unknown:';   //  image placeholder
                break;
        }

        return format_setname($format_string, $chars, $values);
    }

    public static function store_ext_setdata(DatabaseConnection $db, $setid, array $save, $type, $commit = ESI_COMMITTED, $overwrite=FALSE)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        // Generate the setname:
        $newsetname = self::generate_set_name($db, $save);
        if ($newsetname == '') {
            return FALSE; // We have no idea what kind of upload this is. So we don't set anything.
        }
        // Save the individual extsetinfo's:
        foreach ($save as $name => $value) {
            if ($name == 'MERGE_SET') {
                store_merge_sets_data($db, trim($value), $setid, $type, $commit);
            } else {
                $value_x = trim($value);
                // Does info exist?
                $sql = '* FROM extsetdata WHERE "setID"=? AND "name"=? AND "type"=?';
                $res = $db->select_query($sql, 1, array($setid, $name, $type));

                // Check if the stored field exists
                if ($res === FALSE) {
                    $db->insert_query('extsetdata', array('setID', 'name', 'value', 'committed', 'type'), array($setid, $name, $value_x, $commit, $type) );
                } elseif ($overwrite === TRUE) {
                    $db->update_query_2('extsetdata', array('value'=>$value_x, 'committed'=> $commit), '"setID" = ? AND "name" = ? AND "type" = ?', array($setid, $name, $type));
                }
            }
        }

        // Store the new setname based on the extsetinfo:
        $sql = '* FROM extsetdata WHERE "setID"=? AND "name"=? AND "type"=?';
        $res = $db->select_query($sql, 1, array($setid, 'setname', $type));
        if ($res === FALSE) {
            $db->insert_query('extsetdata', array('setID', 'name', 'value', 'committed', 'type'), array($setid, 'setname', $newsetname, $commit, $type));
        } else {
            $db->update_query('extsetdata', array('value'=>$newsetname, 'committed'=>$commit), '"setID" = ? AND "name" = ? AND "type" = ?', array($setid, 'setname', $type));
        }
        // Return newsetname so we can use ajax to show it in the browse page without reloading it.
        return $newsetname; // Unsafe version, we don't want the added quotes thank you.
    }

    public function add_ext_setdata(DatabaseConnection $db, $setid, array $save, $type, $commit = ESI_COMMITTED, $overwrite=FALSE)
    {
        foreach ($save as $name => $value) {
            $value_x = trim($value);
            // Does info exist?
            $sql = '* FROM extsetdata WHERE "setID"=? AND "name"=? AND "type"=?';
            $res = $db->select_query($sql, 1, array($setid, $name, $type));

            // Check if the stored field exists
            if ($res === FALSE) {
                $db->insert_query('extsetdata', array('setID', 'name', 'value', 'committed', 'type'), array($setid, $name, $value_x, $commit, $type));
            } elseif ($overwrite === TRUE) {
                $db->update_query_2('extsetdata', array('value'=>$value_x, 'committed'=>$commit), '"setID" = ? AND "name" = ? AND "type" = ?', array($setid, $name, $type));
            }
        }
    }
}
