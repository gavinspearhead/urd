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
 * $LastChangedDate: 2013-07-03 23:55:57 +0200 (wo, 03 jul 2013) $
 * $Rev: 2860 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_quickmenu.tpl 2860 2013-07-03 21:55:57Z gavinspearhead@gmail.com $
 *}

{* Ajax page, doesn't need a head/foot tpl *}
<div id="quickmenuinner">
{foreach $items as $link=>$item name="qm"}
{strip}
<div id="quickmenuitem_{$item@iteration}" class="quickmenuitem">

{* Action? *}
{if $item->type == 'quickmenu'}
<button class="quickmenubutton" onclick="javascript:ShowQuickMenu('{$item->id}','{$subject}', {$srctype},event); return false;">
{/if}

{if $item->type == 'quickdisplay'}
<button class="quickmenubutton" onclick="javascript:ShowQuickDisplay('{$item->id}','{$subject}',event, {$srctype});CloseQuickMenu(); return false;">
{/if}

{if $item->type == 'newpage'}
<button class="quickmenubutton" onclick="javascript:document.location.href='{$item->extra}'" class="buttonlike">
{/if}

{if $item->type == 'searchbutton'}
<button class="quickmenubutton" onclick="javascript:search_button('{$item->extra.search_url}','{$item->extra.name|escape:javascript}');CloseQuickMenu(); return false;"> 
{/if}

{if $item->type == 'nfopreview' || $item->type == 'imgpreview'|| ($item->type == 'nzbpreview' && $show_usenzb neq 0 && $show_download neq 0) || $item->type == 'vidpreview' }
<button class="quickmenubutton" onclick="javascript:select_preview('{$item->extra.binaryID}','{$item->extra.groupID}');CloseQuickMenu();">
{/if}

{if $item->type == 'guessextsetinfosafe'}
<button class="quickmenubutton" onclick="javascript:GuessExtSetInfoSafe('{$subject}', {$srctype});">
{/if}

{if $item->type == 'guessbasketextsetinfo'}
<button class="quickmenubutton" onclick="javascript:GuessBasketExtSetInfo('{$subject}', {$srctype});">
{/if}

{if $item->type == 'guessextsetinfo'}
<button class="quickmenubutton" onclick="javascript:GuessExtSetInfo('{$subject}', {$srctype})">
{/if}

{if $item->type == 'report_spam'}
<button class="quickmenubutton" onclick="javascript:report_spam('{$subject}');CloseQuickMenu();">
{/if}

{if $item->type == 'add_blacklist'}
<button class="quickmenubutton" onclick="javascript:add_blacklist('{$subject}');CloseQuickMenu();">
{/if}

{if $item->type == 'add_search'}
<button class="quickmenubutton" onclick="javascript:add_search('search');CloseQuickMenu();">
{/if}

{if $item->type == 'add_block'}
<button class="quickmenubutton" onclick="javascript:add_search('block');CloseQuickMenu();">
{/if}

{if $item->type == 'urd_search'}
<button class="quickmenubutton" onclick="javascript:urd_search();CloseQuickMenu();">
{/if}

{if $item->type == 'hide_set'}
<button class="quickmenubutton" onclick="javascript:markRead('{$item->id}', 'hide', '{$srctype}');CloseQuickMenu();">
{/if}

{if $item->type == 'unhide_set'}
<button class="quickmenubutton" onclick="javascript:markRead('{$item->id}', 'unhide', '{$srctype}');CloseQuickMenu();">
{/if}

{if $item->type == 'follow_link'}
<button class="quickmenubutton" onclick="javascript:follow_link('{$item->id}', 'unhide', '{$srctype}');CloseQuickMenu();">
{/if}

{$item->name}

</button>
{/strip}
</div>
{/foreach}
</div>
<input type="hidden" id="nrofquickmenuitems" value="{$smarty.foreach.qm.total}"/>
{if isset($message) && $message !== ''}
<h2>{$message}</h2>
{/if}
