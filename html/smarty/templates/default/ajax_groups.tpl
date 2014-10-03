{* Smarty *}
{*
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
 * $LastChangedDate: 2010-12-26 19:09:56 +0100 (zo, 26 dec 2010) $
 * $Rev: 1965 $
 * $Author: gavinspearhead $
 * $Id: newsgroups.tpl 1965 2010-12-26 18:09:56Z gavinspearhead $
 *}

{if $page_tab=="admin" && $isadmin} 
{$admin_hidden=""}
{$user_hidden="hidden"}
{else} 
{$admin_hidden="hidden"}
{$user_hidden=""}
{/if}

{* Capture the top skipper: *}
{capture assign=topskipper}{strip}
<div class="ng_selector">
{if $lastpage > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages position=top js=group_page}
{/if}
</div>
{/strip}
{/capture}

{* Capture the bottom skipper: *}
{capture assign=bottomskipper}{strip}
<div class="ng_selector">
{if $lastpage > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages position=bottom js=group_page}
{/if}
</div>
{/strip}
{/capture}

{capture assign=selector}{strip}
<div class="pref_selector">
<ul class="tabs">
<li id="button_global" class="tab{if $page_tab == 'admin'} tab_selected{/if}">{$LN_global_settings}</li>
<li id="button_user" class="tab{if $page_tab != 'admin'} tab_selected{/if}">{$LN_user_settings}
<input type="hidden" id="page_tab" value="{$page_tab|escape:htmlall}"/></li>
</ul>
</div>
{/strip}
{/capture}

<div class="hidden">
<input type="hidden" name="page" id="page1" value="{$page_tab|escape:htmlall}"/>
<input type="hidden" id="order" name="order" value="{$sort|escape}"/>
<input type="hidden" id="order_dir" name="order_dir" value="{$sort_dir|escape}"/>
</div>

{* we use the class parameter to toggle which we will show
    general: always show
    admin: global settings only editable by an admin
    user: user specific settings editable per user
*}

<div id="ng_headerbox">
{$topskipper}
{$selector} 
</div>

{$up="<img src='$IMGDIR/small_up.png' width='9' height='6' alt=''/>"}
{$down="<img src='$IMGDIR/small_down.png' width='9' height='6' alt=''/>"}
{if $sort == "name"}{if $sort_dir=='desc'}{$name_sort=$up}{else}{$name_sort=$down}{/if}{else}{$name_sort=""}{/if}
{if $sort == "active"}{if $sort_dir=='desc'}{$active_sort=$up}{else}{$active_sort=$down}{/if}{else}{$active_sort=""}{/if}
{if $sort == "category"}{if $sort_dir=='desc'}{$category_sort=$up}{else}{$category_sort=$down}{/if}{else}{$category_sort=""}{/if}
{if $sort == "postcount"}{if $sort_dir=='desc'}{$postcount_sort=$up}{else}{$postcount_sort=$down}{/if}{else}{$postcount_sort=""}{/if}
{if $sort == "adult"}{if $sort_dir=='desc'}{$adult_sort=$up}{else}{$adult_sort=$down}{/if}{else}{$adult_sort=""}{/if}
{if $sort == "last_updated"}{if $sort_dir=='desc'}{$last_updated_sort=$up}{else}{$last_updated_sort=$down}{/if}{else}{$last_updated_sort=""}{/if}
{if $sort == "expire"}{if $sort_dir=='desc'}{$expire_sort=$up}{else}{$expire_sort=$down}{/if}{else}{$expire_sort=""}{/if}
{if $sort == "admin_minsetsize"}{if $sort_dir=='desc'}{$admin_minsetsize_sort=$up}{else}{$admin_minsetsize_sort=$down}{/if}{else}{$admin_minsetsize_sort=""}{/if}
{if $sort == "admin_maxsetsize"}{if $sort_dir=='desc'}{$admin_maxsetsize_sort=$up}{else}{$admin_maxsetsize_sort=$down}{/if}{else}{$admin_maxsetsize_sort=""}{/if}
{if $sort == "visible"}{if $sort_dir=='desc'}{$visible_sort=$up}{else}{$visible_sort=$down}{/if}{else}{$visible_sort=""}{/if}
{if $sort == "minsetsize"}{if $sort_dir=='desc'}{$minsetsize_sort=$up}{else}{$minsetsize_sort=$down}{/if}{else}{$minsetsize_sort=""}{/if}
{if $sort == "refresh_period"}{if $sort_dir=='desc'}{$refresh_period_sort=$up}{else}{$refresh_period_sort=$down}{/if}{else}{$refresh_period_sort=""}{/if}
{if $sort == "refresh_time"}{if $sort_dir=='desc'}{$refresh_time_sort=$up}{else}{$refresh_time_sort=$down}{/if}{else}{$refresh_time_sort=""}{/if}
{if $sort == ""}{if $sort_dir=='desc'}{$_sort=$up}{else}{$_sort=$down}{/if}{else}{$_sort=""}{/if}

<table class="newsgroups" id="groupstable">
<tr>
<th class="general head round_left">&nbsp;</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_active } class="general buttonlike head nowrap" onclick="javascript:load_groups( { order: 'active', defsort: 'desc' } );">&nbsp;{$active_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_name } id="browsesubjecttd" class="width100p general buttonlike head nowrap" onclick="javascript:load_groups( { order: 'name', defsort: 'desc' } );">{$LN_name} {$name_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_category } class="{$user_hidden} center user buttonlike head nowrap" onclick="javascript:load_groups( { order : 'category', defsort: 'asc' } );">{$LN_category} {$category_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_posts } class="center general buttonlike head nowrap" onclick="javascript:load_groups( { order : 'postcount', defsort: 'desc' } );">{$LN_ng_posts} {$postcount_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_adult } class="{$admin_hidden} admin buttonlike center nowrap head" onclick="javascript:load_groups( { order: 'adult', defsort: 'desc' } );">{$LN_ng_adult} {$adult_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_lastupdated } class="center general buttonlike head nowrap" onclick="javascript:load_groups( { order : 'last_updated', defsort: 'desc' } );">{$LN_ng_lastupdated} {$last_updated_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_expire } class="{$admin_hidden} center admin buttonlike nowrap head" onclick="javascript:load_groups( { order : 'expire', defsort: 'desc' } );">{$LN_ng_expire_time} {$expire_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_admin_minsetsize } class="{$admin_hidden} center admin head nowrap buttonlike" onclick="javascript:load_groups( { order : 'admin_minsetsize', defsort: 'desc' } );">{$LN_ng_admin_minsetsize} {$admin_minsetsize_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_admin_maxsetsize } class="{$admin_hidden} center admin head buttonlike nowrap {if $isadmin == 0 or $urdd_online == 0 }round_right{/if}" onclick="javascript:load_groups( { order : 'admin_maxsetsize', defsort: 'desc' } );">{$LN_ng_admin_maxsetsize} {$admin_maxsetsize_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_visible } class="{$user_hidden} center user buttonlike head nowrap" onclick="javascript:load_groups( { order : 'visible', defsort: 'desc' } );">{$LN_ng_visible} {$visible_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_minsetsize } class="{$user_hidden} center user buttonlike head nowrap round_right" onclick="javascript:load_groups( { order : 'minsetsize', defsort: 'desc' } );">{$LN_ng_minsetsize} {$minsetsize_sort}</th>
{if $isadmin != 0 and $urdd_online != 0 }
<th {urd_popup type="small" text=$LN_ng_tooltip_autoupdate } class="{$admin_hidden} admin buttonlike head nowrap" onclick="javascript:load_groups( { order : 'refresh_period', defsort: 'desc' } );">{$LN_ng_autoupdate} {$refresh_period_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_time } class="{$admin_hidden} admin buttonlike head fixwidth5 nowrap" onclick="javascript:load_groups( { order : 'refresh_time', defsort: 'desc' } );">@ {$LN_time} {$refresh_time_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_action } class="{$admin_hidden} admin head round_right fixwidth8 nowrap">{$LN_actions}</th>
{/if}
</tr>

{foreach $allgroups as $group}
<tr name="content" class="even content">
<td class="general">{$group.number} </td>
<td class="general">
{urd_checkbox value="{$group.active_val}" name="newsgroup[{$group.id}]" id="newsgroup_{$group.id}" readonly="{$isadmin eq 0 || $urdd_online eq 0}" post_js="subscribe_ng('{$group.id}');"} 
<input type="hidden" id="ng_id_{$group.id}" value="{$group.name|escape:htmlall}"/>
</td>
{if $group.description != ''}
{$space='<br/>'}
{$tooltip="`$group.long_name``$space``$group.description`" }
{else}
{$tooltip="`$group.long_name`"}
{/if}
<td {if $tooltip != ''}{urd_popup text=$tooltip|escape }{/if} class="general" > <div class="donotoverflowdamnit inline">
<span {if $group.active_val == $NG_SUBSCRIBED} class="buttonlike" onclick="javascript:jump('browse.php?groupID=group_{$group.id}');"{/if}>
{$group.name|escape:htmlall}</span></div>
</td>
<td class="{$user_hidden} user center">
<select name="category[{$group.id}]" id="category_{$group.id}" onchange="javascript:update_category('groups', {$group.id});">
    <option value="0">{$LN_nocategory}</option>
    {foreach $categories as $item}		
        <option {if $item.id == $group.category }selected="selected"{/if} value="{$item.id}">{$item.name|escape:htmlall}</option>
	{/foreach}
</select>
    </td>
<td class="general right">{$group.postcount}</td>
<td class="{$admin_hidden} admin center">

{urd_checkbox value="{$group.adult}" name="adult[{$group.id}]" id="adult_{$group.id}" readonly="{$isadmin eq 0}" post_js="update_adult('group', '{$group.id}')"} 
</td>
<td class="general right">{$group.lastupdated}</td>
<td class="{$admin_hidden} admin right"><input type="text" size="2" value="{$group.expire|escape:htmlall}" id="expire_{$group.id}" name="expire[{$group.id}]" {if $isadmin != 1 or $urdd_online != 1} readonly="readonly"{/if} onchange="javascript:update_ng_value('groups', 'expire', {$group.id});"/></td>
<td class="{$admin_hidden} admin right"><input type="text" size="4" value="{$group.admin_minsetsize|escape:htmlall}" id="minsetsize_{$group.id}" name="admin_minsetsize[{$group.id}]" {if $isadmin != 1 or $urdd_online != 1} readonly="readonly"{/if} onchange="javascript:update_ng_value('groups', 'minsetsize', {$group.id});"/></td>
<td class="{$admin_hidden} admin right"><input type="text" size="4" value="{$group.admin_maxsetsize|escape:htmlall}" id="maxsetsize_{$group.id}" name="admin_maxsetsize[{$group.id}]" {if $isadmin != 1 or $urdd_online != 1} readonly="readonly"{/if} onchange="javascript:update_ng_value('groups', 'maxsetsize', {$group.id});"/></td>
<td class="{$user_hidden} user center">
{urd_checkbox value="{$group.visible}" name="visible[{$group.id}]" id="visible_{$group.id}" post_js="toggle_visibility('group','{$group.id}');"} 
</td>
<td class="{$user_hidden} user center"><input type="text" size="2" value="{$group.minsetsize|escape:htmlall}" name="minsetsize[{$group.id}]" id="user_minsetsize_{$group.id}" onchange="javascript:update_user_ng_value('groups', 'user_minsetsize', {$group.id});"/>
<input type="text" size="2" value="{$group.maxsetsize}" id="user_maxsetsize_{$group.id}" name="maxsetsize[{$group.id}]" onchange="javascript:update_user_ng_value('groups', 'user_maxsetsize', {$group.id});"/>
</td>

{if $isadmin != 0 and $urdd_online != 0}
<td class="{$admin_hidden} admin center"> 
<select name="period[{$group.id}]" id="period_{$group.id}" size="1" class="update" onchange="javascript:update_ng_time('groups', {$group.id});">
{html_options values=$periods_keys output=$periods_texts selected=$group.select}
</select>
</td>
<td class="nowrap {$admin_hidden} admin center">@ <input type="text" id="time1_{$group.id}" name="time1[{$group.id}]" value="{$group.time1|escape:htmlall}" class="time" onchange="javascript:update_ng_time('groups', {$group.id});"/>:<input type="text" id="time2_{$group.id}" class="time" name="time2[{$group.id}]" value="{if isset($group.time2) }{$group.time2|string_format:"%02d"}{/if}" onchange="javascript:update_ng_time('groups', {$group.id});"/>
</td>
{if $group.active_val == $NG_SUBSCRIBED and $isadmin != 0 and $urdd_online != 0} 
<td class="{$admin_hidden} right admin nowrap">
<div>
<div class="floatright">
<div class="inline iconsizeplus editicon buttonlike" {urd_popup type="small" text=$LN_feeds_edit } onclick="javascript:edit_group({$group.id});"></div>
<div class="inline iconsizeplus upicon buttonlike" {urd_popup type="small" text=$LN_update } onclick="javascript:ng_action('updategroup', {$group.id});"></div>
<div class="inline iconsizeplus gensetsicon buttonlike" {urd_popup type="small" text=$LN_ng_gensets } onclick="javascript:ng_action('gensetsgroup', {$group.id});"></div>
<div class="inline iconsizeplus killicon buttonlike" {urd_popup type="small" text=$LN_expire } onclick="javascript:ng_action('expiregroup', {$group.id});"></div>
<div class="inline iconsizeplus deleteicon buttonlike" {urd_popup type="small" text=$LN_purge } onclick="javascript:ng_action_confirm('purgegroup', {$group.id}, '{$LN_purge} \'@@\'?');"></div>
</div>
</div>
</td>
{else} 
	{if $group.active_val != $NG_SUBSCRIBED and $isadmin == 1 and $urdd_online != 0}
        <td class="{$admin_hidden} admin">
        <div class="floatright">
        <div class="inline iconsizeplus editicon buttonlike" {urd_popup type="small" text=$LN_feeds_edit } onclick="javascript:edit_group({$group.id});"></div>
        </div>
        </td>
	{/if}
{/if}
{/if}
</tr>
{foreachelse}
<tr><td colspan="15" class="centered highlight even bold">{$LN_error_nogroupsfound}</td></tr>
{/foreach}
{if count($allgroups) > 12}
<tr><td colspan="15" class="feet round_both_bottom">&nbsp;</td>
</tr>
{/if}

</table>
{$bottomskipper}

<div>
<br/> 
</div>
