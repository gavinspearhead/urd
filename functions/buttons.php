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

class button
{
    private $name;
    private $url;

    const MAX_SEARCH_BUTTONS = 10;

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

function insert_default_buttons(DatabaseConnection $db)
{
    $default_buttons = array (
        new button('All4Divx',                  'http://www.all4divx.com/subtitles/$q/any/1'),
        new button('Answers',                   'http://www.answers.com/$q'),
        new button('Baidu',                     'http://www.baidu.com/s?wd=$q&rsv_bp=0&rsv_spt=3'),
        new button('Bing',                      'https://www.bing.com/search?q=$q'),
        new button('Binsearch',                 'https://www.binsearch.info/?q=$q&max=25&adv_age=600&server='),
        new button('Dictionary',                'http://dictionary.reference.com/browse/$q'),
        new button('DuckDuckGo',                'https://duckduckgo.com/?q=$q&t=lm'),
        new button('Ebay',                      'http://search.ebay.com/$q'),
        new button('Facebook',                  'https://www.facebook.com/search/results.php?q=$q&init=quick'),
        new button('Film Totaal',               'http://www.filmtotaal.nl/search.php?q=$q'),
        new button('Filmstarts',                'http://www.filmstarts.de/suche/?q=$q'),
        new button('FreeDB',                    'http://www.freedb.org/freedb_search.php?words=$q&allfields=NO&fields=artist&fields=title&allcats=YES&grouping=none'),
        new button('Google',                    'https://www.google.com/search?q=$q'),
        new button('Google Images',             'https://www.google.com/search?q=$q&tbm=isch'),
        new button('Google Maps',               'https://www.google.com/maps?q=$q'),
        new button('IMDB',                      'https://www.google.com/search?btnI&amp;q=site:imdb.com%20%22$q%22'),
        new button('MetalArchives',             'http://www.metal-archives.com/search.php?string=$q&searchfrom=main&type=band'),
        new button('Movie Meter',               'http://www.moviemeter.nl/film/search/$q#results'),
        new button('MusicBrainz',               'https://musicbrainz.org/search/textsearch.html?query=$q&type=artist&an=1&as=1'),
        new button('NzbIndex',                  'https://www.nzbindex.com/search/?q=$q'),
        new button('Nzb.cc',                    'https://www.nzb.cc/#$q'),
        new button('OpenSubs',                  'https://www.google.com/search?btnI&q=site:opensubtitles.org%20%22$q%22'),
        new button('RLS',                       'http://www.rlslog.net/?s=$q'),
        new button('Rotten Tomatoes',           'http://www.rottentomatoes.com/search/full_search.php?search=$q'),
        new button('TV Rage',                   'https://www.tvrage.com/search.php?search=$q'),
        new button('Twitter',                   'https://twitter.com/search/$q'),
        new button('Wikipedia (English)',       'https://en.wikipedia.org/wiki/Special:Search?search=$q'),
        new button('Wikipedia (Nederlands)',    'https://nl.wikipedia.org/wiki/Special:Search?search=$q'),
        new button('Wikipedia (Deutsch)',       'https://de.wikipedia.org/wiki/Special:Search?search=$q'),
        new button('Wikipedia (Francais)',      'https://fr.wikipedia.org/wiki/Special:Search?search=$q'),
        new button('Wikipedia (Svenska)',       'https://sv.wikipedia.org/wiki/Special:Search?search=$q'),
        new button('Yahoo',                     'https://search.yahoo.com/search?p=$q'),
        new button('Youtube',                   'https://www.google.com/search?btnI&q=site:youtube.com%20$q'),
    );

    foreach ($default_buttons as $b) {
        add_button($db, $b);
    }
}

function add_button(DatabaseConnection $db, button $b)
{
    $db->insert_query('searchbuttons', array('name', 'search_url'), array($b->get_name(), $b->get_url()));
}

function update_button(DatabaseConnection $db, $name, $search_url, $id)
{
    assert(is_numeric($id));
    $db->update_query_2('searchbuttons', array('name'=>trim($name), 'search_url'=>trim($search_url)), '"id"=?', array($id));
}

function delete_button(DatabaseConnection$db, $id)
{
    assert(is_numeric($id));
    $db->delete_query('searchbuttons', '"id"=?', array($id));
}
