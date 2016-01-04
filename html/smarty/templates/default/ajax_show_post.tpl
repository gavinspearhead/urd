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

{extends file="popup.tpl"}
{block name=title}{$LN_post_post}{/block}
{block name=contents}

<div class="light">
<input type="hidden" name="postid" id="postid" value="{$postid}"/>

{if $readonly == 1}{$readonlystr=' readonly="readonly" '}{$disabledstr=' disabled="disabled" '}{else}{$readonlystr=''}{$disabledstr=''}{/if}
<br/>
<table class="hmid">

<tr><td class="nowrap bold">{$LN_post_directory}:</td><td>
<select name="directory" id="directory" {urd_popup type="small" text=$LN_post_directoryext} class="textbox28m" {$disabledstr} required>
{$cnt=0}
{foreach $dirs as $name}
    <option value="{$name}" {if $name==$dir }selected="selected"{$cnt=1}{/if}>{$name}</option>
{/foreach}
{if $cnt == 0 && $dir !=''}
    <option value="{$dir}" selected="selected">{$dir}</option>
{elseif $cnt == 0 && $dir ==''}
    <option value="" selected="selected">{$LN_select}</option>
{/if}
</select>

</td></tr>
<tr><td class="nowrap bold" {urd_popup type="small" text=$LN_post_newsgroupext}>{$LN_post_newsgroup}:</td>
<td><select name="newsgroup" id="groupid" class="textbox28m" {$disabledstr}>
    <option value="" {if !isset($group) || $group == ''}selected="selected"{/if}>{$LN_select}</option>
    {html_options options=$groups selected=$group}
</select></td></tr>
<tr><td class="nowrap bold" {urd_popup type="small" text=$LN_post_newsgroupext} >{$LN_post_newsgroup} NZB:</td>
<td><select name="newsgroup_nzb" id="groupid_nzb" class="textbox28m" {$disabledstr}>
    <option value="" {if (!isset($group_nzb) || $group_nzb == '') && $default_nzb_group === NULL}selected="selected"{/if}>{$LN_select}</option>
    {if $default_nzb_group !== NULL} 
    <option value="{$default_nzb_group.group_id}" {if !isset($group_nzb) || $group_nzb == $default_nzb_group.group_id || $group_nzb == ''}selected="selected"{/if}>{$default_nzb_group.group_name}</option>
    {/if}

{foreach $groups as $id=>$name}
    <option value="{$id}" {if $id == $group_nzb}selected="selected"{/if}>{$name}</option>
{/foreach}
</select></td></tr>
<tr><td class="nowrap bold" {urd_popup type="small" text=$LN_post_subjectext} >{$LN_post_subject}:</td>
<td><input type="text" name="subject" id="subject" class="textbox28m" value="{$subject|escape}" {$readonlystr} required/></td></tr>
<tr><td class="nowrap bold" {urd_popup type="small" text=$LN_post_posternameext} >{$LN_post_postername}:</td>
<td><input type="text" name="postername" id="postername"class="textbox28m" value="{$poster_name|escape}" {$readonlystr} required/></td></tr>
<tr><td class="nowrap bold" {urd_popup type="small" text=$LN_post_posteremailext}>{$LN_post_posteremail}:</td>
<td><input type="email" name="posteremail" id="posteremail" class="textbox28m" value="{$poster_email|escape}" {$readonlystr} required/></td></tr>
<tr><td class="nowrap bold" {urd_popup type="small" text=$LN_post_recoveryext} >{$LN_post_recovery}:</td>
<td><input type="text" name="recovery" id="recovery" {urd_popup type="small" text=$LN_post_recoveryext} value="{$recovery_size|escape}" class="textbox4m" {$readonlystr} required/>%</td></tr>
<tr><td class="nowrap bold" {urd_popup type="small" text=$LN_post_rarfilesext}>{$LN_post_rarfiles}:</td>
<td><input type="text" name="filesize" id="filesize" {urd_popup type="small" text=$LN_post_rarfilesext} value="{$rarfile_size|escape}" class="textbox4m" {$readonlystr} required/> k{$LN_byte_short}</td></tr>
<tr><td class="nowrap bold">{$LN_post_delete_files}:</td>
<td> {urd_checkbox value="$delete_files" name="delete_files" id="delete_files" readonly=$readonly}</td></tr>
<tr><td class="nowrap bold">{$LN_browse_schedule_at}:</td>
<td><input id="timestamp" name="timestamp" type="text" value="{$start_time|escape}" class="textbox28m" {$readonlystr}/></td></tr>
{if $readonly == 0}
<!--tr><td>&nbsp;</td></tr-->
<tr><td colspan="2" class="centered"><input type="submit" value="{$LN_post_post}" id="submit_button" class="submitsmall"/></td></tr>
{/if}

</table>
<div id="calendardiv" class="calendaroff">
</div>
</div>

{/block}

