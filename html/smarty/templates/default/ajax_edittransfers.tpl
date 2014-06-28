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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_edittransfers.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}
<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{$LN_transfers_details}</div>
<div class="light padding10">
<br/>
<input type="hidden" name="dlid" id="dlid" value="{$id|escape:htmlall}" />
<table class="renametransfer hmid">
<tr><td>{$LN_transfers_name}:</td><td><input type="text" class="width300" name="dlname" id="dlname" value="{$oldname|escape}" autofocus="autofocus"/></td></tr>
<tr><td>{$LN_transfers_archpass}:</td><td><input type="text" class="width300" name="dlpass" id="dlpass" value="{$oldpw|escape}"/></td></tr>
<tr><td>{$LN_browse_download_dir}:</td><td>

<span id="dl_dir_span">
    <div class="floatleft"><input name="dl_dir" id="dl_dir" type="text" value="{$dl_dir|escape:htmlall}"  class="width300" {if $dldir_noedit eq 1}readonly="readonly"{/if}/>&nbsp;</div>
    {if $dldir_noedit != 1}<div class="foldericon iconsize floatleft" onclick="$('#dir_select_span').toggleClass('hidden'); $('#dl_dir_span').toggleClass('hidden');"></div>{/if}
</span>
<span id="dir_select_span" class="hidden">
<select class="width300" id="dir_select" onchange="select_dir('dir_select', 'dl_dir');">
<option value=""></option>
{foreach $directories as $directory}
<option value="{$directory|escape:htmlall}">{$directory|escape:htmlall}</option>
{/foreach}
</select>
</span>

</td></tr>
<tr><td>{$LN_browse_schedule_at}:</td><td>

<input type="text" class="width300" name="starttime" id="timestamp" value="{$starttime|escape}" {if $starttime_noedit eq 1}readonly="readonly"{/if}
{if $starttime_noedit neq 1}onclick="javascript:show_calendar(null, null, null);" onkeyup="javascript:hide_popup('calendardiv', 'calendar');"{/if}
/>
</td></tr>
<tr><td colspan="2"><br/>
{urd_checkbox value="$oldunpar" name="unpar" id="unpar" data=$LN_transfers_unpar}
{urd_checkbox value="$oldunrar" name="unrar" id="unrar" data=$LN_transfers_unrar}
{urd_checkbox value="$olddelete" name="delete" id="delete_files" data=$LN_transfers_deletefiles}
{urd_checkbox value="$oldsubdl" name="subdl" id="subdl" data=$LN_transfers_subdl}
</td></tr>
<tr><td colspan="2">
{urd_checkbox value="$add_setname" name="add_setname" id="add_setname" data=$LN_transfers_add_setname }
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" class="centered">
	<input type="button" onclick="rename_transfer();" {urd_popup type="small" text=$LN_apply} name="submit_button" value="{$LN_apply}" class="submit"/> 
</td></tr>
</table>

</div>
<div id="calendardiv" class="calendaroff">
</div>

