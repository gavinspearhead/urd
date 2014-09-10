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
 * $LastChangedDate: 2014-06-15 00:41:23 +0200 (zo, 15 jun 2014) $
 * $Rev: 3095 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_show_upload.tpl 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}
<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{$LN_transfers_uploadnzb}</div>
<br/>
<div class="light padding10">
<table class="upload hmid">
<tr><td>
<form method="post" action="parsenzb.php" id='parseform'>
<div>
<input type="hidden" name="timestamp" value="" id='timestamp1'/> 
<input type="hidden" name="challenge" value="{$challenge|escape}"/>
<input type="hidden" name="dl_dir" value="" id='dl_dir1'/> 
<input type="hidden" name="add_setname" id="add_setname1"/>
<input type="hidden" name="setname" id="setname1"/>
	{$LN_transfers_nzblocation}:<br/>
    {if $localfile != ''}
	<input type="text" name="file" id="url" size="30" placeholder="{$LN_filename}" value="{$localfile|escape}" {urd_popup type="small" text=$LN_transfers_nzblocationext} autofocus="autofocus"/>
    {else}
	<input type="text" name="url" id="url" size="30" placeholder="" {urd_popup type="small" text=$LN_transfers_nzblocationext}/>
    {/if}
	</div>
</form>
</td>
<td>&nbsp;</td>
<td>
<form method='post' enctype='multipart/form-data' action='upload.php' id='uploadform'>
<div>
<input type="hidden" name="timestamp" value="" id='timestamp2'/> 
<input type="hidden" name="challenge" value="{$challenge|escape}"/>
<input type="hidden" name="dl_dir" value="" id='dl_dir2'/> 
<input type="hidden" name="add_setname" id="add_setname2"/>
<input type="hidden" name="setname" id="setname2"/>
{$LN_transfers_nzbupload}:<br/>

<input type="text" name="_upfile" id="_upfile" style="width:150px;" {urd_popup type="small" text=$LN_transfers_nzbuploadext}/>
<input type="file" name="upfile" id="upfile" style="display:none" />
<input type="button" id="browse" class="submitsmall" value="{$LN_browse}"/>
</div>
</form>
</td>
</tr>
<tr>
<td>{$LN_basket_setname}:<br/><input name="setname" id="setname" type="text" value="{$setname|escape}" size="30"/></td><td>&nbsp;</td>
<td class="vtop">{$LN_browse_schedule_at}:<br/><input name="timestamp" id="timestamp" type="text" value="{$download_delay|escape}" size="20"/></td></tr>
<tr><td colspan="3">
{$LN_browse_download_dir}:<br/>
<div id="dl_dir_span">
    <div class="floatleft"><input name="dl_dir" id="dl_dir" type="text" value="{$dl_dir|escape:htmlall}" class="width300"/>&nbsp;</div>
    <div class="foldericon iconsize floatleft" id="toggle_button"></div>
</div>
<div id="dir_select_span" class="hidden">
<select id="dir_select" class="width300">
<option value=""></option>
{foreach $directories as $directory}
    <option value="{$directory|escape:htmlall}">{$directory|escape:htmlall}</option>
{/foreach}
</select>
</div>

</td></tr>
<tr><td>
{urd_checkbox value="$add_setname" name="add_setname" id="add_setname" data="$LN_browse_add_setname"} 
</td>
<tr><td colspan="3">&nbsp;</td></tr>

<tr><td class="vbot centered" colspan="3"><input type="button" id="submit_button" value="{$LN_transfers_import}" class="submit"/></td></tr>
</table>
</div>
<div id="calendardiv" class="calendaroff">
</div>
