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
 * $LastChangedDate: 2014-04-13 00:28:40 +0200 (zo, 13 apr 2014) $
 * $Rev: 3029 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_quickmenu.tpl 3029 2014-04-12 22:28:40Z gavinspearhead@gmail.com $
 *}

{* Ajax page, doesn't need a head/foot tpl *}
<div id="quickmenuinner">
{foreach $items as $link=>$item name="qm"}
{strip}
<div id="quickmenuitem_{$item@iteration}" class="quickmenuitem">
{* Action? *}
{if $item->type == 'quickmenu'}
<button class="quickmenubutton" onclick="javascript:show_quickmenu('{$item->id}','{$subject}', '{$srctype}', event); return false;">
{/if}

{if $item->type == 'quickdisplay'}
<button class="quickmenubutton" onclick="javascript:show_quick_display( { srctype:'{$item->id}', subject:'{$subject}', type:'{$srctype}'});close_quickmenu(); return false;">
{/if}

{if $item->type == 'newpage'}
<button class="quickmenubutton" onclick="javascript:document.location.href='{$item->extra}'" class="buttonlike">
{/if}

{if $item->type == 'searchbutton'}
<button class="quickmenubutton" onclick="javascript:search_button('{$item->extra.search_url}','{$item->extra.name|escape:javascript}');close_quickmenu(); return false;"> 
{/if}

{if $item->type == 'nfopreview' || $item->type == 'imgpreview'|| ($item->type == 'nzbpreview' && $show_usenzb != 0 && $show_download != 0) || $item->type == 'vidpreview' }
<button class="quickmenubutton" onclick="javascript:select_preview('{$item->extra.binaryID}','{$item->extra.groupID}');close_quickmenu();">
{/if}

{if $item->type == 'guessextsetinfosafe'}
<button class="quickmenubutton" onclick="javascript:guess_extSet_info_safe('{$subject}', {$srctype});">
{/if}

{if $item->type == 'guessbasketextsetinfo'}
<button class="quickmenubutton" onclick="javascript:guess_basket_extset_info('{$subject}', {$srctype});">
{/if}

{if $item->type == 'guessextsetinfo'}
<button class="quickmenubutton" onclick="javascript:guess_extset_info('{$subject}', {$srctype})">
{/if}

{if $item->type == 'report_spam'}
<button class="quickmenubutton" onclick="javascript:report_spam('{$subject}');close_quickmenu();">
{/if}

{if $item->type == 'post_spot_comment'}
<button class="quickmenubutton" onclick="javascript:post_spot_comment('{$subject}');close_quickmenu();">
{/if}
{if $item->type == 'add_blacklist'}
<button class="quickmenubutton" onclick="javascript:add_blacklist('{$subject}');close_quickmenu();" >
{/if}
{if $item->type == 'add_posterblacklist'}
<button class="quickmenubutton" onclick="javascript:add_poster_blacklist('{$subject}');close_quickmenu();" >
{/if}
{if $item->type == 'add_blacklist_global'}
<button class="quickmenubutton" onclick="javascript:add_blacklist('{$subject}', '', 'global');close_quickmenu();">
{/if}

{if $item->type == 'add_whitelist'}
<button class="quickmenubutton" onclick="javascript:add_whitelist('{$subject}');close_quickmenu();">
{/if}
{if $item->type == 'add_whitelist_global'}
<button class="quickmenubutton" onclick="javascript:add_whitelist('{$subject}', '', 'global');close_quickmenu();">
{/if}

{if $item->type == 'add_search'}
<button class="quickmenubutton" onclick="javascript:add_search('search');close_quickmenu();">
{/if}

{if $item->type == 'add_block'}
<button class="quickmenubutton" onclick="javascript:add_search('block');close_quickmenu();">
{/if}

{if $item->type == 'urd_search'}
<button class="quickmenubutton" onclick="javascript:urd_search();close_quickmenu();">
{/if}

{if $item->type == 'hide_set'}
<button class="quickmenubutton" onclick="javascript:mark_read('{$item->id}', 'hide', '{$srctype}');close_quickmenu();">
{/if}

{if $item->type == 'unhide_set'}
<button class="quickmenubutton" onclick="javascript:mark_read('{$item->id}', 'unhide', '{$srctype}');close_quickmenu();">
{/if}

{if $item->type == 'follow_link'}
<button class="quickmenubutton" onclick="javascript:follow_link('{$item->id}', 'unhide', '{$srctype}');close_quickmenu();">
{/if}

{if $item->type == 'mail_set'}
<button class="quickmenubutton" onclick="javascript:mail_set('{$subject}', '{$srctype}');close_quickmenu();">
{/if}

{$item->name}
{if $item->submenu} <div class="floatright">&gt;</div>
{/if}
</button>
{/strip}
</div>
{/foreach}
</div>
<input type="hidden" id="blacklist_confirm_msg" value="{$LN_blacklist_spotter}"/>
<input type="hidden" id="whitelist_confirm_msg" value="{$LN_whitelist_spotter}"/>
<input type="hidden" id="nrofquickmenuitems" value="{$smarty.foreach.qm.total}"/>
{if isset($message) && $message !== ''}
<h2>{$message}</h2>
{/if}
