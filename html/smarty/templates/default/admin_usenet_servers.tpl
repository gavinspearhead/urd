{* Smarty *}{*
 *  This file is part of Urd.
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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: admin_usenet_servers.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}

{extends file="head.tpl"}
{block name=contents}
<div id="searchformdiv" class="hidden">
<h3 class="title">{$title}</h3>

<div class="hidden">
<input type="hidden" name="action_id" id="action_id" value=""/>
<input type="hidden" name="action" id="action" value=""/></div>

<div>
<input type="text" name="search" placeholder="{$LN_search}" id="search"  class="textbox18m"/>
<input type="button" value="{$LN_search}" id="search_button" class="submitsmall"/></div>
<input type="hidden" id="view_size" value=""/>
</div>

<div id="usenetserversdiv">
</div>
<div><br/></div>

<p>&nbsp;</p>

<script type="text/javascript">
$(document).ready(function() {
   $('#view_size').val($(window).width());
    show_usenet_servers();
    $('#searchbar').html( $('#searchformdiv').html());
    $('#searchformdiv').html('')
    $('#search').keypress( function(e) { return submit_enter(e, show_usenet_servers); });
    $('#search_button').click( function() { show_usenet_servers(); });
});
</script>
{/block}

