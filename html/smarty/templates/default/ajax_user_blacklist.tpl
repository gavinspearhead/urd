{* Smarty *}
{*
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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_admin_users.tpl 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 *}
{capture assign=topskipper}{strip}
<div class="ng_selector">
{if count($pages) > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages class=ps js=blacklist_offset extra_class="margin10"}
{/if}
</div>
{/strip}
{/capture}

{capture assign=tab_selector}
<div class="pref_selector">{strip}
<ul class="tabs">
<li onclick="javascript:select_tab_blacklist('blacklist')" class="tab{if $active_tab == 'blacklist'} tab_selected{/if}" id="blacklist_bar">{$LN_user_blacklist}
<input type="hidden" name="tabs" value="blacklist"/>
</li>
<li onclick="javascript:select_tab_blacklist('whitelist')" class="tab{if $active_tab == 'whitelist'} tab_selected{/if}" id="whitelist_bar">{$LN_user_whitelist}
<input type="hidden" name="tabs" value="whitelist"/>
</li>
</ul>
<input type="hidden" id="active_tab" value="{$active_tab}"/>
{/strip}
</div>
{/capture}

{* Making a 'top' and a 'bottom' skipper: *}
{capture assign=bottomskipper}{strip}
<div class="ng_selector">
{if count($pages) > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages class=psb js=blacklist_offset extra_class="margin10"}
{/if}
</div>
{/strip}
{/capture}

{$up="<img src='$IMGDIR/small_up.png' alt=''>"}
{$down="<img src='$IMGDIR/small_down.png' alt=''>"}
{if $sort == ""}{if $sort_dir=='desc'}{$_sort=$up}{else}{$_sort=$down}{/if}{else}{$_sort=""}{/if}
{if $sort == "spotter_id"}{if $sort_dir=='desc'}{$spotter_id_sort=$up}{else}{$spotter_id_sort=$down}{/if}{else}{$spotter_id_sort=""}{/if}
{if $sort == "source"}{if $sort_dir=='desc'}{$source_sort=$up}{else}{$source_sort=$down}{/if}{else}{$source_sort=""}{/if}
{if $sort == "username"}{if $sort_dir=='desc'}{$username_sort=$up}{else}{$username_sort=$down}{/if}{else}{$username_sort=""}{/if}
{if $sort == "status"}{if $sort_dir=='desc'}{$status_sort=$up}{else}{$status_sort=$down}{/if}{else}{$status_sort=""}{/if}
{capture assign=tableheader}
<table class="newsgroups" id="black_list_table">
<tr>
<th class="head round_left">#</th>
<th onclick="javascript:show_blacklist( { 'order' : 'spotter_id', 'def_direction' : 'asc' } );" class="buttonlike head">{$LN_spotter_id} {$spotter_id_sort}</th>
<th onclick="javascript:show_blacklist( { 'order' : 'username', 'def_direction' : 'asc' } );" class="buttonlike head">{$LN_username} {$username_sort}</th>
<th onclick="javascript:show_blacklist( { 'order' : 'source', 'def_direction' : 'asc' } );" class="buttonlike head">{$LN_spots_source} {$source_sort}</th>
<th onclick="javascript:show_blacklist( { 'order' : 'status', 'def_direction' : 'asc' } );" class="buttonlike head">{$LN_status} {$status_sort}</th>
<th class="head round_right right">{$LN_actions}</th>
</tr>
{/capture}

{if $only_rows == 0}
<div id="ng_headerbox">
{$topskipper}
{$tab_selector}
</div>
{$tableheader}
{/if}

{foreach $blacklist as $item}
<tr class="even content" onmouseover="javascript:$(this).toggleClass('highlight2');" onmouseout="javascript:$(this).toggleClass('highlight2');" id="item{$item.id}">
	<td>{$item.number|escape:htmlall|truncate:$maxstrlen}</td>
	<td>
{if $active_tab == 'whitelist'}
     <div class="highlight_whitelist inline center width15">{$LN_whitelisttag}</div>
{else if $active_tab == 'blacklist'}
     <div class="highlight_spam inline center width15">{$LN_blacklisttag}</div>
{/if} 
    {$item.spotter_id|escape:htmlall|truncate:$maxstrlen}
    </td>
	<td>{if $item.userid == 0}{$LN_users_isadmin}{else}{$item.username|escape:htmlall|truncate:$maxstrlen}{/if}</td>
	<td> {if $item.source == $list_external}{$LN_source_external}{else}{$LN_source_user}{/if} </td>
	<td id="status{$item.id}">{if $item.status == $status_active}{$LN_active}
        {else if $item.status == $status_nonactive}{$LN_nonactive}
        {else if $item.status == $status_disabled}{$LN_disabled}
        {/if}
        </td>
    <td><div class="floatright">
    {if $item.source == $list_external && $item.status != $status_active}
        <div class="inline iconsizeplus playicon buttonlike" {urd_popup type="small" text=$LN_users_enable } onclick="javascript:enable_blacklist('{$item.id}');"></div>
    {/if}
    {if $item.source == $list_external}
        <div class="inline iconsizeplus deleteicon buttonlike" {urd_popup type="small" text=$LN_users_disable } onclick="javascript:delete_blacklist('{$item.id}');"></div>
    {else} 
        <div class="inline iconsizeplus deleteicon buttonlike" {urd_popup type="small" text=$LN_users_delete } onclick="javascript:delete_blacklist('{$item.id}', '{$LN_delete} {$item.spotter_id|escape}?');"></div>
    {/if}
    </div>
	</td>
</tr>
{foreachelse}
<tr><td colspan="9" class="centered highlight even bold">{$LN_error_nousersfound}</td></tr>
{/foreach}

{if $only_rows == 0}
{if count($blacklist) > 12}
<tr><td colspan="6" class="feet round_both_bottom">&nbsp;</td>
</tr>
{/if}

    </table>
    {$bottomskipper}
    <br/>
    <div>
    <input type="hidden" name="order" id="order" value="{$sort|escape}}"/>
    <input type="hidden" name="order_dir" id="order_dir" value="{$sort_dir|escape}}"/>
    <input type="hidden" id="last_line" value="{$item.number}"/>
    </div>
{/if}
