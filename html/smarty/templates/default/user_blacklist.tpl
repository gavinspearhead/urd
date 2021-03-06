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
 * $LastChangedDate: 2013-12-07 17:40:41 +0100 (za, 07 dec 2013) $
 * $Rev: 2972 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: admin_users.tpl 2972 2013-12-07 16:40:41Z gavinspearhead@gmail.com $
 *}
{extends file="head.tpl"}
{block name=contents}
<div id="searchformdiv" class="hidden">
<h3 class="title">{$title}</h3>
<div>
<input type="text" name="search" placeholder="{$LN_search}" id="search" class="textbox18m"/>
<select id="status" name="status" class="textbox10m">
<option value="all" {if $status == ''}selected="selected"{/if}>{$LN_all}</option>
<option value="active" {if $status == 'active'}selected="selected"{/if}>{$LN_active}</option>
<option value="nonactive" {if $status == 'nonactive'}selected="selected"{/if}>{$LN_nonactive}</option>
<option value="disabled" {if $status == 'disabled'}selected="selected"{/if}>{$LN_disabled}</option>
</select>
<input type="button" value="{$LN_search}" id="search_button" class="submitsmall"/></div>
</div>

<input type="hidden" id="perpage" value="{$perpage|escape}"/>
<input type="hidden" id="which" value=""/>
<input type="hidden" name="offset" id="offset" value="{$offset|escape}"/>

<div id="usersdiv">
<script type="text/javascript">
$(document).ready(function() {
    set_scroll_handler('#contentout', show_blacklist);
    show_blacklist({ 'which':'spots_blacklist'});
    $('#searchbar').html( $('#searchformdiv').html());
    $('#searchformdiv').html('');
    $('#search').keypress( function(e) { return submit_enter(e, show_blacklist); });
    $('#search_button').click( function() { show_blacklist(); });
});
</script>
</div>

{/block}
