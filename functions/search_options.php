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
 * $Id: buttons.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathbut = realpath(dirname(__FILE__));

require_once "$pathbut/autoincludes.php";

class search_option
{
    private $name;
    private $url;

    const MAX_SEARCH_OPTIONS = 10;

    public function __construct($n, $u)
    {
        $this->name = trim($n);
        $this->url = trim($u);
    }
    public function get_name()
    {
        return $this->name;
    }
    public function get_url()
    {
        return $this->url;
    }
}

function insert_default_search_options(DatabaseConnection $db)
{
    $default_search_options = array (
        new search_option('All4Divx',                  'http://www.all4divx.com/subtitles/$q/any/1'),
        new search_option('Answers',                   'http://www.answers.com/$q'),
        new search_option('Baidu',                     'http://www.baidu.com/s?wd=$q&rsv_bp=0&rsv_spt=3'),
        new search_option('Bing',                      'https://www.bing.com/search?q=$q'),
        new search_option('Binsearch',                 'https://www.binsearch.info/?q=$q&max=25&adv_age=600&server='),
        new search_option('Dictionary',                'http://dictionary.reference.com/browse/$q'),
        new search_option('DuckDuckGo',                'https://duckduckgo.com/?q=$q&t=lm'),
        new search_option('Ebay',                      'http://search.ebay.com/$q'),
        new search_option('Facebook',                  'https://www.facebook.com/search/results.php?q=$q&init=quick'),
        new search_option('Film Totaal',               'http://www.filmtotaal.nl/search.php?q=$q'),
        new search_option('Filmstarts',                'http://www.filmstarts.de/suche/?q=$q'),
        new search_option('FreeDB',                    'http://www.freedb.org/freedb_search.php?words=$q&allfields=NO&fields=artist&fields=title&allcats=YES&grouping=none'),
        new search_option('Google',                    'https://www.google.com/search?q=$q'),
        new search_option('Google Images',             'https://www.google.com/search?q=$q&tbm=isch'),
        new search_option('Google Maps',               'https://www.google.com/maps?q=$q'),
        new search_option('IMDB',                      'https://www.google.com/search?btnI&amp;q=site:imdb.com%20%22$q%22'),
        new search_option('MetalArchives',             'http://www.metal-archives.com/search.php?string=$q&searchfrom=main&type=band'),
        new search_option('Movie Meter',               'http://www.moviemeter.nl/film/search/$q#results'),
        new search_option('MovieDB',                   'http://www.themoviedb.org/search?query=$q'),
        new search_option('MusicBrainz',               'https://musicbrainz.org/search/textsearch.html?query=$q&type=artist&an=1&as=1'),
        new search_option('NzbIndex',                  'https://www.nzbindex.com/search/?q=$q'),
        new search_option('Nzb.cc',                    'https://www.nzb.cc/#$q'),
        new search_option('OpenSubs',                  'https://www.google.com/search?btnI&q=site:opensubtitles.org%20%22$q%22'),
        new search_option('RLS',                       'http://www.rlslog.net/?s=$q'),
        new search_option('Rotten Tomatoes',           'http://www.rottentomatoes.com/search/full_search.php?search=$q'),
        new search_option('Start Page',                'https://startpage.com/eng/advanced-search.html?&cat=web&query=$q'),
        new search_option('TV Rage',                   'https://www.tvrage.com/search.php?search=$q'),
        new search_option('Twitter',                   'https://twitter.com/search/$q'),
        new search_option('Wikipedia (English)',       'https://en.wikipedia.org/wiki/Special:Search?search=$q'),
        new search_option('Wikipedia (Nederlands)',    'https://nl.wikipedia.org/wiki/Special:Search?search=$q'),
        new search_option('Wikipedia (Deutsch)',       'https://de.wikipedia.org/wiki/Special:Search?search=$q'),
        new search_option('Wikipedia (Francais)',      'https://fr.wikipedia.org/wiki/Special:Search?search=$q'),
        new search_option('Wikipedia (Svenska)',       'https://sv.wikipedia.org/wiki/Special:Search?search=$q'),
        new search_option('Yahoo',                     'https://search.yahoo.com/search?p=$q'),
        new search_option('Youtube',                   'https://www.google.com/search?btnI&q=site:youtube.com%20$q'),
    );

    foreach ($default_search_options as $b) {
        add_search_option($db, $b);
    }
}

function add_search_option(DatabaseConnection $db, search_option $b)
{
    $db->insert_query('searchbuttons', array('name', 'search_url'), array($b->get_name(), $b->get_url()));
}

function update_search_option(DatabaseConnection $db, $name, $search_url, $id)
{
    assert(is_numeric($id));
    $db->update_query_2('searchbuttons', array('name'=>trim($name), 'search_url'=>trim($search_url)), '"id"=?', array($id));
}

function delete_search_option(DatabaseConnection$db, $id)
{
    assert(is_numeric($id));
    $db->delete_query('searchbuttons', '"id"=?', array($id));
}

function clear_all_search_options(DatabaseConnection $db)
{
    try {
        $search_options = get_all_search_options($db);
        foreach ($search_options as $search_option) {
            delete_search_option($db, $search_option['id']);
        }
    } catch (exception $e) {
    }
}

function set_all_search_options(DatabaseConnection $db, array $search_options)
{
    foreach ($search_options as $search_option) {
        add_search_option($db, new search_option($search_option['name'], $search_option['search_url']));
    }
}

function get_all_search_options(DatabaseConnection $db)
{
    $res = $db->select_query('* FROM searchbuttons WHERE "id" > 0 ORDER BY "name" ASC');
    if ($res === FALSE) {
        throw new exception('Cannot find any search options');
    }

    return $res;
}

