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
 * $LastChangedDate: 2014-05-29 01:03:02 +0200 (do, 29 mei 2014) $
 * $Rev: 3058 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: parse_nfo.php 3058 2014-05-28 23:03:02Z gavinspearhead@gmail.com $
 */
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class nfo_parser
{
    private static function match_iafd_link($line)
    {
        $rv = preg_match('/(http\:\/\/.*iafd\.com\/[\w?\-\/?+%-=]*)/i', $line, $matches);
        if ($rv) {
            return trim($matches[1]);
        } else {
            return FALSE;
        }
    }

    private static function match_imdb_link($line)
    {
        $rv = preg_match('/(http\:\/\/.*imdb\.(com|de|es|pt|fr|it)\/[\w?\-\/?]*)/i', $line, $matches);
        if ($rv) {
            return trim($matches[1]);
        } else {
            return FALSE;
        }
    }

    private static function match_tvrage_link($line)
    {
        $rv = preg_match('/(http\:\/\/.*\.tvrage\.com\/\w*)/i', $line, $matches);
        if ($rv) {
            return trim($matches[1]);
        } else {
            return FALSE;
        }
    }

    private static function match_tvcom_link($line)
    {
        $rv = preg_match('/(http\:\/\/.*\.tv\.com\/[\w.\/\-]*)/i', $line, $matches);
        if ($rv) {
            return trim($matches[1]);
        } else {
            return FALSE;
        }
    }

    private static function match_title($line)
    {
        $rv = preg_match('/(\btitle\b|file\s?name\b|\benglish name\b|\bname\b)[\s:\-.\[]*((([\w\s.:\'\-]+)[,|\/]*)+)/i', $line, $matches);
        if ($rv && isset($matches[2]) && !stristr($line, 'subtitle') && !stristr($line, 'imdb.com')) {
            $match = str_ireplace('.avi', '', $matches[2]);
            $match = str_ireplace('.mkv', '', $match);
            $match = str_ireplace('.', ' ', $match);

            return trim($match);
        } else {
            return FALSE;
        }
    }

    private static function match_serietitle($line)
    {
        $rv = preg_match('/(\bserie\b)[\s:\-.\[]*((([\w.:\']+)[\s,|\/]*)+)/i', $line, $matches);
        if ($rv && isset($matches[2])) {
            $match = str_ireplace('.avi', '', $matches[2]);

            return trim($match);
        } else {
            return FALSE;
        }
    }

    private static function match_duration($line)
    {
        $rv = preg_match('/(\bruntime\b|\bduration\b|\blaufzeit\b|\bdauer\b)[a-z:\s\-.\[]*((\d+[:.]\d+([:.]\d+)*)|((\d+\s*hr?s?\s*)?\d+\s*mi?n?\s*(\d+\s*se?c?)?))/i', $line, $matches);
        if ($rv && isset($matches[2])) {
            $match = trim($matches[2]);
            if ($match != '') {
                return trim($matches[2]);
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    private static function match_url($line)
    { // note the ?: means don't capture; ?| means collapse two option to one match
        $rv = preg_match('!(?:\borigin\b|\burl\b|\bwebsite\b|\bsite\b|\bsource\b)(?|.*(https?://[\w\d\-/]+\.[\w\d\-/.]+)|[\s:\-.\[]*((?:(?:[\w]+)[\s.:,|\-\/]*)+))!i', $line, $matches);
        if ($rv && isset($matches[1])) {
            $url = trim($matches[1]);
            if (strstr($url, ' ')) {  //assume that URLs don't contain spaces

                return FALSE;
            }
            if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://') {
                $url = 'http://' . $url;
            }
            $host = @parse_url($url, PHP_URL_HOST);
            if (strpos($host, '.')) { // must at least contain one .
                $addr = gethostbyname($host);
                if ($addr !== FALSE) {
                    return $url;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    private static function match_subtitles($line)
    {
        $rv = preg_match('/(\bsubs\b|\bsubtitles\b)[\s:\-.\[]*((([\w]+)[\s,|\/]*)+)/i', $line, $matches);
        if ($rv && isset($matches[2]) && !stristr($matches[2], 'vobsub')) {
            return trim($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function match_rating($line)
    {
        $rv = preg_match('/(\bimdb[\s-]?rating\b|\bscore\b|\bimdb\b|\b\rating\b)[\s:\-.\[(]*(\d+(\.\d+)?)/i', $line, $matches);
        if ($rv && isset($matches[2])) {
            return round_rating($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function match_genre($line)
    {
        $rv = preg_match('/(\bgenre\b|\bcategory\b)[\s:\-.\[]*((([\w]+)[\s,|\/]*)+)/i', $line, $matches);
        if ($rv && isset($matches[2])) {
            return trim($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function match_language($line)
    {
        $rv = preg_match('/(\blanguage\b)[\s:\-.\[]*((([\w]+)[\s,|\/]*)+)/i', $line, $matches);
        if ($rv && isset($matches[2])) {
            return trim($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function match_year($line)
    {
        $rv = preg_match('/(\byear\b)[\s:\-.\[]*(\d{2,4})/i', $line, $matches);
        if ($rv && isset($matches[2]) && is_numeric($matches[2])) {
            return trim($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function match_albumtitle($line)
    {
        $rv = preg_match('/(\balbum\b|\btitle\b|\btitel\b)[\s:\-.\[]*((([\w]+)[\s,|\/()!<>]*)+)/i', $line, $matches);
        if ($rv && isset($matches[2])) {
            return trim($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function match_band($line)
    {
        $rv = preg_match('/(\bband\b|\bartists?\b)[\s:\-.\[]*((([\w]+)[\s,|\/!<>()]*)+)/i', $line, $matches);
        if ($rv && isset($matches[2])) {
            return trim($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function match_software_os($line)
    {
        $rv = preg_match('/(\bos type\b|\boperating systems?\b|\bos\b)[\s:\-.\[]*((([\w]+)[\s,|\/]*)+)/i', $line, $matches);
        if ($rv && isset( $matches[2])) {
            return trim($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function match_software_format($line)
    {
        $rv = preg_match('/(\bformat\b)[\s:\-.\[]*((([\w]+)[\s,|\/]*)+)/i', $line, $matches);
        if ($rv && isset($matches[2])) {
            return trim($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function match_software_genre($line)
    {
        $rv = preg_match('/(\bsoftware type\b|\bsoftware genre\b|\bsoftware category\b|\bgame type\b)[\s:\-.\[]*((([\w]+)[\s,|\/]*)+)/i', $line, $matches);
        if ($rv && isset($matches[2])) {
            if (stristr($matches[1], 'game')) {
                $matches[2] = 'Game: ' . $matches[2];
            }

            return trim($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function match_format($line)
    {
        $rv = preg_match('/(\bformat\b)[\s:\-.\[]*((([\w]+)[\s,|\/]*)+)/i', $line, $matches);
        if ($rv && isset($matches[2])) {
            return trim($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function match_softwaretitle($line)
    {
        $rv = preg_match('/(\bsoftware name\b|\btitle\b|\btitel\b|\bname\b)[\s:\-.\[]*((([\w]+)[\s,|\/]*)+)/i', $line, $matches);
        if ($rv && isset($matches[2])) {
            return trim($matches[2]);
        } else {
            return FALSE;
        }
    }

    private static function set_info(array &$file_nfo, $index, $value)
    {
        if ((!isset($file_nfo[$index]) || strlen($file_nfo[$index]) < 2) && strlen($value) > 2) {
            $file_nfo[$index] = trim($value);
            $pos = strpos($file_nfo[$index], '   ');
            if ($pos > 10) {
                $file_nfo[$index] = trim(substr($file_nfo[$index], 0, $pos));
            }
        }
    }

    private static function parse_tvcom_info($movie_link)
    {
        $f = @fopen($movie_link, 'r');
        if ($f === FALSE) {
            echo_debug('Cannot open link: ' . $movie_link, LOG_INFO);

            return FALSE;
        }
        $got_title = $got_rating = $got_year = FALSE;
        $rv = array('name'=>NULL, 'rating'=>NULL, 'year'=>NULL);
        while (($line = fgets($f)) !== FALSE) {
            if (!$got_title && preg_match('/<title>(.*)at TV.com<\/title>/i', $line, $matches)) {
                $name = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
                str_replace('"', '', $name);
                $rv['name'] = utf8_decode($name);
                $got_title = TRUE;
            } elseif (!$got_rating && preg_match('/<span class="number">([0-9]{1,2})\.?(\d)?<\/span>/i', $line, $matches)) {
                $decimals = $matches[2];
                if ($decimals >= 3 && $decimals <= 7) {
                    $decimals = 5;
                } else {
                    $decimals = 0;
                }
                $rv['rating'] = $matches[1]. ".$decimals";
                $got_rating = TRUE;
            } elseif (!$got_year && preg_match('/<p>\w+day\s+\w+\s+\d+,\s+([0-9]{4})<\/p>/i', $line, $matches)) {
                $rv['year'] = $matches[1];
                $got_year = TRUE;
            } elseif (!$got_year && preg_match('/<div class="airdate">Aired:\s+\d+\/\d+\/(\d+)\s*<\/div>/i', $line, $matches)) {
                $year = $matches[1];
                $got_year = TRUE;
                if ($year < 30) {
                    $year += 2000;
                } elseif ($year < 100) {
                    $year += 1900;
                }
                $rv['year'] = $year;
            }
            if ($got_rating && $got_title && $got_year) {
                break;
            }
        }
        fclose($f);

        return $rv;
    }

    private static function parse_tvrage_info($movie_link)
    {
        $f = @fopen($movie_link, 'r');
        if ($f === FALSE) {
            return FALSE;
        }
        $got_title = $got_rating = FALSE;
        $rv = array ('name'=>NULL, 'rating'=>NULL);
        while (($line = fgets($f)) !== FALSE) {
            if (!$got_title && preg_match('/<h3[\w\s\'\"\=]*>(.*)\s\(\d+\s*Fans\).*<\/h3>/i', $line, $matches)) {
                $name = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
                str_replace('"', '', $name);
                $rv['name'] = utf8_decode($name);
                $got_title = TRUE;
            } elseif (!$got_rating && preg_match('/(\d{1,2})\.(\d)\/10/i', $line, $matches)) {
                $decimals = $matches[2];
                if ($decimals >= 3 && $decimals <= 7) {
                    $decimals = 5;
                } else {
                    $decimals = 0;
                }
                $rv['rating'] = $matches[1]. ".$decimals";
                $got_rating = TRUE;
            }
            if ($got_rating && $got_title) {
                break;
            }
        }
        fclose($f);

        return $rv;
    }

    private static function parse_imdb_info($movie_link)
    {
        $f = @fopen($movie_link, 'r');
        if ($f === FALSE) {
            return FALSE;
        }
        $got_title = $got_rating = $got_genre = $next_is_genre = FALSE;
        $rv = array ('name'=>NULL, 'year'=>NULL, 'rating'=>NULL, 'genre'=>NULL);
        while (($line = fgets($f)) !== FALSE) {
            if (!$got_title && preg_match('/<title>(.*)\(.*(\d{4}).*\).*<\/title>/i', $line, $matches)) {
                $name = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
                str_replace('"', '', $name);
                $rv['name'] = utf8_decode($name);
                $rv['year'] = $matches[2];
                $got_title = TRUE;
            } elseif (!$got_rating && preg_match('/>([0-9]{1,2})\.([0-9]).*\/10/i', $line, $matches)) {
                $decimals = (int) $matches[2];
                $value = (int) $matches[1];
                if ($decimals >= 3 && $decimals <= 7) {
                    $decimals = 5;
                } else {
                    if ($decimals > 7) {
                        $value += 1;
                    }
                    $decimals = 0;
                }
                $rv['rating'] = "$value.$decimals";
                $got_rating = TRUE;
            } elseif (!$got_genre && preg_match('/Genres?:<\/h[54]>/iU', $line)) {
                $next_is_genre = 2;
            } elseif ($next_is_genre > 0 && !$got_genre) {
                $line = trim(strip_tags($line));
                $line = html_entity_decode($line);
                $line = preg_replace('/([^a-z| ])|(see)|(more)/i', '',  $line);
                if ($line != '') {
                    $rv['genre'] = trim(str_replace(' more', '', strip_tags($line)));
                    $next_is_genre = 0;
                    $got_genre = TRUE;
                } else {
                    $next_is_genre--;
                }
            }
            if ($got_rating && $got_title && $got_genre) {
                break;
            }
        }
        fclose($f);

        return $rv;
    }

    public static function find_info($str, $key, $dont_follow = FALSE)
    {
        $file_nfo = $fn = [];
        switch ($key) {
        case urd_extsetinfo::SETTYPE_MOVIE:
            $fn[] = array('match_imdb_link', 'link');
            $fn[] = array('match_iafd_link', 'link');
            $fn[] = array('match_url', 'link');
            $fn[] = array('match_genre', 'moviegenre');
            $fn[] = array('match_title', 'name');
            $fn[] = array('match_subtitles', 'sublang');
            $fn[] = array('match_language', 'lang');
            $fn[] = array('match_title', 'year');
            $fn[] = array('match_format', 'movieformat');
            $fn[] = array('match_rating', 'score');
            $fn[] = array('match_duration', 'runtime');
            break;
        case urd_extsetinfo::SETTYPE_TVSERIES:
            $fn[] = array('match_imdb_link', 'link');
            $fn[] = array('match_tvrage_link', 'link');
            $fn[] = array('match_tvcom_link', 'link');
            $fn[] = array('match_url', 'link');
            $fn[] = array('match_genre', 'moviegenre');
            $fn[] = array('match_serietitle', 'name');
            $fn[] = array('match_title', 'name');
            $fn[] = array('match_subtitles', 'sublang');
            $fn[] = array('match_language', 'lang');
            $fn[] = array('match_title', 'year');
            $fn[] = array('match_format', 'movieformat');
            $fn[] = array('match_rating', 'score');
            $fn[] = array('match_duration', 'runtime');
            break;
        case urd_extsetinfo::SETTYPE_ALBUM:
            $fn[] = array('match_url', 'link');
            $fn[] = array('match_genre', 'musicgenre');
            $fn[] = array('match_albumtitle', 'name');
            $fn[] = array('match_year', 'year');
            $fn[] = array('match_band', 'band');
            $fn[] = array('match_format', 'musicformat');
            $fn[] = array('match_rating', 'score');
            $fn[] = array('match_duration', 'runtime');
            break;
        case urd_extsetinfo::SETTYPE_SOFTWARE:
            $fn[] = array('match_url', 'link');
            $fn[] = array('match_softwaretitle', 'name');
            $fn[] = array('match_software_os', 'os');
            $fn[] = array('match_language', 'lang');
            $fn[] = array('match_software_format', 'softwareformat');
            $fn[] = array('match_software_genre', 'softwaregenre');
            break;
        case urd_extsetinfo::SETTYPE_EBOOK: // usually no nfo files :(
            $fn[] = array('match_url', 'link');
            $fn[] = array('match_format', 'ebookformat');
            break;
        case urd_extsetinfo::SETTYPE_GAME:
            $fn[] = array('match_url', 'link');
            $fn[] = array('match_softwaretitle', 'name');
            $fn[] = array('match_software_os', 'os');
            $fn[] = array('match_language', 'lang');
            $fn[] = array('match_software_format', 'gameformat');
            $fn[] = array('match_software_genre', 'gamegenre');
            break;
        case urd_extsetinfo::SETTYPE_DOCUMENTARY:
            $fn[] = array('match_imdb_link', 'link');
            $fn[] = array('match_url', 'link');
            $fn[] = array('match_genre', 'moviegenre');
            $fn[] = array('match_title', 'name');
            $fn[] = array('match_subtitles', 'sublang');
            $fn[] = array('match_language', 'lang');
            $fn[] = array('match_title', 'year');
            $fn[] = array('match_rating', 'score');
            $fn[] = array('match_duration', 'runtime');
            break;
        case urd_extsetinfo::SETTYPE_TVSHOW:
            $fn[] = array('match_url', 'link');
            $fn[] = array('match_serietitle', 'name');
            $fn[] = array('match_title', 'name');
            $fn[] = array('match_subtitles', 'sublang');
            $fn[] = array('match_language', 'lang');
            $fn[] = array('match_title', 'year');
            $fn[] = array('match_rating', 'score');
            $fn[] = array('match_duration', 'runtime');
            break;

        case urd_extsetinfo::SETTYPE_IMAGE:
        default:
            break;
        }

        foreach ($str as $line) {
            foreach ($fn as $f) {
                $tmp = $f[0];
                nfo_parser::set_info($file_nfo, $f[1], nfo_parser::$tmp($line));
            }
        }
        if (!$dont_follow) {
            nfo_parser::get_link_info($file_nfo);
        }

        if ($key == urd_extsetinfo::SETTYPE_ALBUM) {
            if (isset($file_nfo['name']) && isset($file_nfo['band'])) {
                $file_nfo['name'] = $file_nfo['band'] . ' - ' . $file_nfo['name'];
            } elseif (isset($file_nfo['band'])) {
                $file_nfo['name'] = $file_nfo['band'];
            }
        }

        return $file_nfo;
    }

    public static function get_link_info(array &$file_nfo)
    {
        if (isset($file_nfo['link']) && preg_match('/imdb.(com|de|fr|es|it)/i', $file_nfo['link']) !== FALSE) {
            $rv = nfo_parser::parse_imdb_info($file_nfo['link']);
            if ($rv['name'] !== NULL) {
                $file_nfo['name'] = $rv['name'];
            }
            if ($rv['year'] !== NULL) {
                $file_nfo['year'] = $rv['year'];
            }
            if ($rv['rating'] !== NULL) {
                $file_nfo['score'] = $rv['rating'];
            }
            if ($rv['genre'] !== NULL) {
                $file_nfo['moviegenre'] = $rv['genre'];
            }
        }
        if (isset($file_nfo['link']) && strpos($file_nfo['link'], 'tvrage.com') !== FALSE) {
            $rv = nfo_parser::parse_tvrage_info($file_nfo['link']);
            if ($rv['name'] !== NULL) {
                $file_nfo['name'] = $rv['name'];
            }
            if ($rv['rating'] !== NULL) {
                $file_nfo['score'] = $rv['rating'];
            }
        }
        if (isset($file_nfo['link']) && strpos($file_nfo['link'], 'tv.com') !== FALSE) {
            $rv = nfo_parser::parse_tvcom_info($file_nfo['link']);
            if ($rv['name'] !== NULL) {
                $file_nfo['name'] = $rv['name'];
            }
            if ($rv['rating'] !== NULL) {
                $file_nfo['score'] = $rv['rating'];
            }
            if ($rv['year'] !== NULL) {
                $file_nfo['year'] = $rv['year'];
            }
        }
    }

    public static function parse_nfo_file($filename, $key)
    {
        $contents = file($filename);
        $info = nfo_parser::find_info($contents, $key, FALSE);

        return $info;
    }

}
