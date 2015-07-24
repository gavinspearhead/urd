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
 * $Id: newsgroups.tpl 3127 2014-06-27 22:14:27Z gavinspearhead@gmail.com $
 *}

{extends file="head.tpl"}
{block name=contents}
<div id="searchformdiv" class="hidden">

<h3 class="title">{$LN_ng_newsgroups|escape}</h3> 

<div>
<input type="text" name="search" placeholder="{$LN_search}" value="{$search|escape:htmlall}" id="newsearch" class="textbox18m"/>
<div class="cleartop3"> 
{urd_checkbox value="$search_all" name="search_all" id="search_all" data="$LN_ng_hide_empty" classes=""}
</div>
<input type="button" value="{$LN_search}" class="submitsmall" id="searchbutton"/>
</div>
</div>
<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>
<br/>
</div>

<div id="groupsdiv">
<h3 class="centered">{$LN_loading}</h3>
</div>

<input type="hidden" name="type" id="type" value="groups"/>
<input type="hidden" id="ln_saved" value="{$LN_pref_saved}"/>
<input type="hidden" id="ln_failed" value="{$LN_failed}"/>

<script type="text/javascript">
$(document).ready(function() {
    load_groups();
    $('#searchbar').html( $('#searchformdiv').html());
    $('#searchformdiv').html('');
    $('#searchbutton').click( function() { load_groups(); } );
    $('#newsearch').keypress( function(e) { return submit_enter(e, load_groups); } );
});
</script>

{/block}
