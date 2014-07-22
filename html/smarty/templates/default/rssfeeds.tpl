{* Smarty *}{*
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
 * $LastChangedDate: 2014-06-28 00:14:27 +0200 (za, 28 jun 2014) $
 * $Rev: 3127 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: rssfeeds.tpl 3127 2014-06-27 22:14:27Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}

<div id="searchformdiv" class="hidden">
{strip}
<h3 class="title">{$LN_feeds_rss|escape}</h3>
{/strip}
<div>
<input type="text" name="search" placeholder="{$LN_search}" value="{$search|escape:htmlall}" id="newsearch" size="30"/>
<div class="cleartop3"> 
{urd_checkbox value="$search_all" name="search_all" id="search_all" data="$LN_feeds_hide_empty"}
</div>
&nbsp;
<input type="button" value="{$LN_search}" id="searchbutton" class="submitsmall"/>
</div>
</div>

<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>
<h3 class="centered">{$LN_loading}</h3>
</div>

<div id="rss_feeds_div"></div>

<script type="text/javascript">
$(document).ready(function() {
    load_rss_feeds();
    $('#searchbar').html( $('#searchformdiv').html());
    $('#searchformdiv').html('');
    $('#searchbutton').click( function() { load_rss_feeds(); } );
    $('#newsearch').keypress( function(e) { return submit_enter(e, load_rss_feeds); } );
});
</script>

<p>&nbsp;</p>
<input type="hidden" name="type" id="type" value="rss"/>
<input type="hidden" id="ln_saved" value="{$LN_pref_saved}"/>
<input type="hidden" id="ln_failed" value="{$LN_failed}"/>

{include file="foot.tpl"}
