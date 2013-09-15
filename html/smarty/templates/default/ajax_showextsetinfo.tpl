{* Smarty *}
{*
 *  This file is part of Urd.
 *
 *  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showextsetinfo.tpl 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}

<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{$setname|escape}</div>
<div class="sets_inner" id="td_sets">

{if $srctype == 'error'}
<div>
{$LN_error_error}: {$message}
</div>
{elseif $srctype == 'edit'}
<div>
	<input type="hidden" id="extsetinfodisplay:{$setID}" value="edit"/>
	<form id="ext_setinfo_{$setID}" method="post">
	<div><input type="hidden" name="values[binarytype]" value="{$binarytype}"/></div>
<table class="set_details ">
		<tr class="comment"><td class="bold">
		{$LN_showsetinfo_typeofbinary}:
		</td><td class="extsetinput">
			<select onchange="SaveExtSetBinaryType('{$setID}',this,'save', {$type});" name="binarytype">
			{foreach $binarytypes as $binid=>$bintype}
				<option value="{$binid}" {if $binid == $binarytype}selected="selected"{/if} >{$bintype}</option>
			{/foreach}
			</select><br/>
		</td></tr>
	{foreach $display as $vals}
		<tr class="comment"><td class="bold">{$vals.name}:</td>
		<td class="extsetinput">
			{if $vals.edit == 'longtext'}<input size="60" type="text" name="values[{$vals.field|escape}]" value="{$vals.value|escape}"/>{/if}
			{if $vals.edit == 'text'}<input size="25" type="text" name="values[{$vals.field|escape}]" value="{$vals.value|escape}"/>{/if}
			{if $vals.edit == 'checkbox'}
				{* Checkboxes only return a value if they're checked, so let's have a default 0 value: *}
                {urd_checkbox value="{$vals.value}" name="values[{$vals.field|escape}]" id="values_{$vals.field|escape}"} 
                {/if}
			{if $vals.edit == 'select'}
			<select name="values[{$vals.field|escape}]">
				{foreach $vals.editvalues as $opt}
				<option value="{$opt}" {if $opt == $vals.value} selected="selected"{/if}>{$opt|capitalize}</option>
				{/foreach}
			</select>
			{/if}
		</td></tr>
	{/foreach}
    <tr class="comment"><td colspan="2">&nbsp;</td>
	<tr class="comment"><td colspan="2" class="center">
	<input type="button" value="{$LN_apply}" class="submit" name="submit_button" onclick="javascript:SaveExtSetInfo('{$setID}', {$type});"/>
	</td></tr>
	</table>
	</form>
</div>	
{else}

{capture assign=fileoverview}
{foreach $files as $file}
<tr class="small vbot">
<td class="preview" colspan="2">
<div class="inline iconsize previewicon buttonlike" onclick="select_preview('{$file.binaryID}','{$groupID}')" {urd_popup type="small" text=$LN_preview}></div>
	{$file.cleanfilename|escape}
    <div class="floatright">{$file.size}</div>
	</td>
</tr>
{/foreach}
{/capture}

{capture assign=extsetoverview}
	{$looped=0}
	{foreach $display as $vals}
	{if $vals.value != "0" && $vals.value != "" && $vals.value != "name"}
		{$looped="`$looped+1`"}
		<tr class="vtop small comment"><td class="nowrap bold">{$vals.name}:</td><td>
		{if $vals.display == 'text'}{$vals.value|escape}{/if}
		{if $vals.display == 'url'}<span class="buttonlike" onclick="javascript:jump('{$vals.value|escape:javascript}',1);">{$vals.value|escape}</span>{/if}
		{if $vals.display == 'number'}<b>{$vals.value|escape}</b>{/if}
		{if $vals.display == 'checkbox'}{if $vals.value == 1}Yes{else}No{/if}{/if}
		</td></tr>
	{/if}
	{/foreach}
{/capture}

<table class="set_details ">

<tr class="vtop small left comment"><td class="nowrap bold" >{$LN_showsetinfo_postedin}:</td>
	<td class="buttonlike" onclick="javascript: load_sets(
        {if $type == $USERSETTYPE_GROUP}
            { 'group_id':'group_{$groupID|escape:javascript}' }
        {else}   
            { 'feed_id':'feed_{$groupID|escape:javascript}' }
        {/if}    
      );">{$groupname|escape:html}
    </td></tr>
{if $fromnames neq ''}
<tr class="vtop small left comment"><td class="nowrap bold">{$LN_showsetinfo_postedby}:</td>
	<td>{$fromnames|escape:html}</td></tr> 
{/if}
<tr class="vtop small left comment"><td class="nowrap bold">{$LN_showsetinfo_size}:</td>
	<td>{if $binaries gt 0}{$binaries} {$LN_files}{if $articlesmax > 0} ({$LN_showsetinfo_shouldbe} {$articlesmax}){/if} - {/if}{if $totalsize gt 0 }{$totalsize}{else}?{/if}</td></tr>
{if $par2s neq ''}
	<tr class="vtop small left comment"><td class="nowrap bold">{$LN_showsetinfo_par2}</td>
	<td>{$par2s}</td></tr>
{/if}

{if $looped > 0}
{$extsetoverview}
{/if}


<tr class="comment"><td colspan="2"><br/></td></tr>

{$fileoverview}

</table>
{* Display: *}
{/if}
</div>
