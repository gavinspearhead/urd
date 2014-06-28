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
 * $LastChangedDate: 2014-06-14 01:20:27 +0200 (za, 14 jun 2014) $
 * $Rev: 3094 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_show_post.tpl 3094 2014-06-13 23:20:27Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}
<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{$LN_post_post}</div>
<div class="light">
<input type="hidden" name="postid" id="postid" value="{$postid}"/>

{if $readonly == 1}{$readonlystr=' readonly="readonly" '}{$disabledstr=' disabled="disabled" '}{else}{$readonlystr=''}{$disabledstr=''}{/if}
<br/>
<table class="hmid">
<tr><td>{$LN_post_directory}:</td><td>
<select name="directory" id="directory" {urd_popup type="small" text=$LN_post_directoryext} class="width300" {$disabledstr} required>
{$cnt=0}
{foreach $dirs as $name }
    <option value="{$name}" {if $name==$dir }selected="selected"{$cnt=1}{/if}>{$name}</option>
{/foreach}
{if $cnt == 0}
    <option value="{$dir}" selected="selected">{$dir}</option>
{/if}
</select>

</td></tr>
<tr><td {urd_popup type="small" text=$LN_post_newsgroupext} >{$LN_post_newsgroup}:</td><td><select name="newsgroup" id="groupid" class="width300" {$disabledstr}>
    <option value="" {if !isset($id) || $id == ''}selected="selected"{/if}>{$LN_select}</option>
{foreach $groups as $id=>$name}
    <option value="{$id}" {if $id == $group}selected="selected"{/if}>{$name}</option>
{/foreach}
</select></td></tr>
<tr><td {urd_popup type="small" text=$LN_post_subjectext} >{$LN_post_subject}:</td><td><input type="text" name="subject" id="subject" class="width300" value="{$subject|escape}" {$readonlystr} required/></td></tr>
<tr><td {urd_popup type="small" text=$LN_post_posternameext} >{$LN_post_postername}:</td><td><input type="text" name="postername" id="postername"class="width300" value="{$poster_name|escape}" {$readonlystr} required/></td></tr>
<tr><td {urd_popup type="small" text=$LN_post_posteremailext}>{$LN_post_posteremail}:</td><td><input type="email" name="posteremail" id="posteremail" class="width300" value="{$poster_email|escape}" {$readonlystr} required/></td></tr>
<tr><td {urd_popup type="small" text=$LN_post_recoveryext} >{$LN_post_recovery}:</td><td><input type="text" name="recovery" id="recovery" {urd_popup type="small" text=$LN_post_recoveryext} value="{$recovery_size|escape}" class="width60" {$readonlystr} required />%</td></tr>
<tr><td {urd_popup type="small" text=$LN_post_rarfilesext}>{$LN_post_rarfiles}:</td><td><input type="text" name="filesize" id="filesize" {urd_popup type="small" text=$LN_post_rarfilesext} value="{$rarfile_size|escape}" class="width60" {$readonlystr} required/></td></tr>
<tr><td> {$LN_post_delete_files}:</td> <td> {urd_checkbox value="$delete_files" name="delete_files" id="delete_files" readonly=$readonly} </td></tr>
<tr><td>{$LN_browse_schedule_at}:</td><td><input id="timestamp" name="timestamp" type="text" value="{$start_time|escape}" class="width300" {if $readonly == 0 } onclick="javascript:show_calendar(null, null, null);" onkeyup="javascript:hide_popup('calendardiv', 'calendar');"{/if} {$readonlystr}/></td></tr>
{if $readonly == 0}
<tr><td>&nbsp;</td></tr>
<tr><td colspan="2" class="centered"><input type="submit" value="{$LN_post_post}" class="submit" onclick="javascript:create_post();"/></td></tr>
{/if}

</table>
<div id="calendardiv" class="calendaroff">
</div>
</div>
