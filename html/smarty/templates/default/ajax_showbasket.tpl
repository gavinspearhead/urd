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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showbasket.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}

{* Show added sets: *}
{capture assign="leftbuttons"}
{*strip*}
<div id="basketbuttondiv" class="hidden">
<table class="basketbuttons">
<tr>
{if $show_download neq 0}
<td class="nowrap vcenter">
<div class="floatleft buttonlike basketbuttonsize noborder downloadbutton" {urd_popup type="small" text=$LN_browse_download } onclick="javascript:whichbutton('urddownload', event);"/></div>
{/if}
{if $show_makenzb neq 0}
<div class="floatleft buttonlike basketbuttonsize noborder getnzbbutton" {urd_popup type="small" text=$LN_browse_savenzb } onclick="javascript:whichbutton('getnzb', event);" /></div>
{/if}
{if $show_merge}
<div class="floatleft buttonlike basketbuttonsize noborder mergebutton" {urd_popup type="small" text=$LN_browse_mergesets } onclick="javascript:whichbutton('mergesets', event);"/></div>
{/if}

<div class="floatleft buttonlike basketbuttonsize noborder clearbutton" {urd_popup type="small" text=$LN_browse_emptylist } onclick="javascript:whichbutton('clearbasket', event);"/></div>
{if $show_download neq 0}
<td class="vcenter"><div class="floatleft">
{$LN_browse_schedule_at}:&nbsp;<input name="timestamp" id="timestamp" type="text" value="{$download_delay|escape}" size="20" onclick="javascript:show_calendar();" onkeyup="javascript:hide_popup('calendardiv', 'calendar');" onchange="javascript:update_basket_display(1);"/></div></td>
<td class="vcenter"><div class="down4">
{urd_checkbox value="$add_setname" name="add_setname" id="add_setname" before="1" data="$LN_browse_add_setname:&nbsp;" post_js="update_basket_display(1);"} 
</div>
</td>
<td class="vcenter"><div class="floatleft">{$LN_browse_download_dir}:&nbsp;</div></td>
<td class="vcenter">
<span id="dl_dir_span">
    <div class="floatleft down2"><input name="dl_dir" id="dl_dir" type="text" value="{$dl_dir|escape:htmlall}" class="width300" onchange="javascript:update_basket_display(1);"/>&nbsp;</div>
    <div class="foldericon iconsizeplus floatleft" onclick="$('#dir_select_span').toggleClass('hidden'); $('#dl_dir_span').toggleClass('hidden');"></div>
</span>
<span id="dir_select_span" class="hidden">
<select id="dir_select" onchange="select_dir('dir_select', 'dl_dir');" class="width300">
<option value=""></option>
{foreach $directories as $directory}
<option value="{$directory|escape:htmlall}">{$directory|escape:htmlall}</option>
{/foreach}
</select>
</span>
</td>

{/if}
</tr>
</table>
</div>
{*/strip*}
{/capture}

{strip}
{capture assign="basket"}
<table class="baskettable">
<tr><td>
	<div id="innerbasketdiv"> 
	<table class="innerbaskettable">
	<tr><td>{$LN_basket_setname}: <input name="dlsetname" id="dlsetname" type="text" value="{$dlsetname|escape:htmlall}" size="55" onchange="javascript:update_basket_display(2);"/></td>
    <td class="basketright nowrap bold">{$LN_basket_totalsize}:</td>
    <td class="basketright nowrap bold">{$totalsize|escape}</td>
    <td width="25px">
<div class="closebutton buttonlike noborder" onclick="javascript:update_basket_display(2);" ></div></td>
    </tr>
	{$totalsize='0'}
	{foreach $addedsets as $q name=loopies}
	<tr><td class="basketleft" colspan="2">{$q.subject|truncate:$maxstrlen:"..."|escape:htmlall}</td><td class="basketright nowrap">{if $q.size == 0}?{else}{$q.size|escape}{/if}</td></tr>
	{$totalsize=$totalsize+$q.size}
	{/foreach}
	</table>
	</div>
</td>
<td>
</td>
</tr>
</table>
</div>
{/capture}
{/strip}


{if $smarty.foreach.loopies.total > 0}
<div class="light">
<table width="100%" class="browsetoptable">
<tr><td colspan="3" class="minimalistic">{$basket}</td></tr>
<tr><td class="leftbut minimalistic">{$leftbuttons}</td></tr>
</table>
<input type="hidden" name="error_diskfull" id="error_diskfull" value="{$LN_error_diskfull}"/>
</div>
{/if}

