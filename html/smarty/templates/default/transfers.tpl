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
 * $LastChangedDate: 2013-04-28 00:55:09 +0200 (zo, 28 apr 2013) $
 * $Rev: 2823 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: transfers.tpl 2823 2013-04-27 22:55:09Z gavinspearhead@gmail.com $
 *}
{extends file="head.tpl"}
{block name=contents}
<br/>
{capture assign=selector}
<div class="pref_selector">{strip}
<ul class="tabs">
{if $show_download != 0 } 
<li class="tab{if $active_tab == 'downloads'} tab_selected{/if}" id="downloads_bar">{$LN_transfers_downloads}
<input type="hidden" name="tabs" value="downloads"/>
</li>
{/if}
{if ($poster != 0 || $isadmin != 0) && $show_post != 0 }
<li class="tab{if $active_tab == 'uploads'} tab_selected{/if}" id="uploads_bar">{$LN_transfers_posts}
<input type="hidden" name="tabs" value="uploads"/>
</li>
{/if}
</ul>
<input type="hidden" id="active_tab" value="{$active_tab}"/>
{/strip}
</div>
{/capture}

<div id="searchformdiv" class="hidden">

<input type="text" name="_search" placeholder="{$LN_search}" id="transfer_search"/>
<input type="button" id="search" value="{$LN_search}" class="submitsmall"/>
</div>

{$selector}
<div id="transfersdiv" class="prefix_transfers">
</div>

<script type="text/javascript">
$(document).ready(function() {
    update_transfers();
    $('#searchbar').html( $('#searchformdiv').html());
    $('#uploads_bar').click(function() { select_tab_transfers('uploads', 'transfers', 'uploads'); });
    $('#downloads_bar').click(function() { select_tab_transfers('downloads', 'transfers', 'downloads'); });
    $('#search').click(function() { load_transfers(); } );
    $('#transfer_search').keypress(function(e) { return submit_enter(e, load_transfers); } );
});
</script>

{/block}
