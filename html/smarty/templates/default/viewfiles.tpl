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
 * $Id: viewfiles.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}
{extends file="head.tpl"}
{block name=contents}
<div id="searchformdiv" class="hidden">
<h3 class="title">{$LN_viewfilesheading}: <span id="directory_top">{$directory|escape:htmlall|truncate:$maxstrlen:'...':false:true}</span></h3>
<div>
<input type="text" name="search" size="30" placeholder="{$LN_search}" id="search"/>
<input type="button" id="search_button" value="{$LN_search}" class="submitsmall"/>
</div>
</div>
<div id="viewfilesdiv">
<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>

<h3 class="centered">{$LN_loading_files}</h3>
</div>
</div>

{if $show_usenzb}
<div id="uploadnzbdiv" class="uploadnzboff"></div>
{/if}

<input type="hidden" id="perpage" value="{$perpage|escape}"/>
<input type="hidden" id="view_size" value=""/>

<script type="text/javascript">
$(document).ready(function() {
   $('#view_size').val($(window).width());
    show_files( { 'curdir':'{$directory|escape:javascript}' } );
    set_scroll_handler('#contentout', show_files);
    $('#searchbar').html( $('#searchformdiv').html());
    $('#searchformdiv').html('');
    $('#search_button').click( function() { show_files_clean(); });
    $('#search').keypress( function(e) { return submit_enter(e, show_files_clean); });
});
</script>

{/block}
