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
 * $Id: ajax_editviewfiles.tpl 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 *}

{extends file="popup.tpl"}
{block name=title}{$LN_edit_file} - {$filename|escape:htmlall|truncate:$maxstrlen:'...':false:true}{/block}
{block name=contents}
<table>
<tr>
<td>{$LN_filename}:
</td>
<td>
<input type="hidden" value="{$directory|escape:htmlall}" name="directory" id="directory_editfile"/>
<input type="hidden" value="{$filename|escape:htmlall}" name="oldfilename" id="oldfilename_editfile"/>
<input type="text" value="{$filename|escape:htmlall}" name="newfilename" id="newfilename_editfile" required placeholder="{$LN_filename}" size="{$textboxsize}"/>
</td>
</tr>
<tr>
<td>{$LN_rights}:
</td>
<td><input type="text" value="{$rights|escape:htmlall}" id="rights_editfile" required name="rights"/>
</td>
</tr>
<tr>
<td>{$LN_group}:
</td>
<td>{html_options name="group" id="group_editfile" options=$groups selected=$group}
</td>
</tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr>
<td class="centered" colspan="2">
<input type="button" value="{$LN_apply}" name="apply" id="apply_button" class="submit"/>
</td>
</tr>

</table>
{/block}
