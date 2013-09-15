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
{include file="head.tpl" title=$title}
<br/>
{capture assign=selector}
<div class="pref_selector">{strip}
<ul class="tabs">
{if $show_download neq 0 } 
<li onclick="javascript:select_tab_transfers('downloads', 'transfers', 'downloads')" class="tab{if $active_tab == 'downloads'} tab_selected{/if}" id="downloads_bar">{$LN_transfers_downloads}
<input type="hidden" name="tabs" value="downloads"/>
</li>
{/if}
{if ($poster neq 0 || $isadmin neq 0) && $show_post neq 0 }
<li onclick="javascript:select_tab_transfers('uploads', 'transfers', 'uploads')" class="tab{if $active_tab == 'uploads'} tab_selected{/if}" id="uploads_bar">{$LN_transfers_posts}
<input type="hidden" name="tabs" value="uploads"/>
</li>
{/if}
</ul>
<input type="hidden" id="active_tab" value="{$active_tab}"/>
{/strip}
</div>
{/capture}

{$selector}
<div id="transfersdiv" class="prefix_transfers">
</div>

<script type="text/javascript">
$(document).ready(function() {
    update_transfers();
});
</script>

{include file="foot.tpl"}
