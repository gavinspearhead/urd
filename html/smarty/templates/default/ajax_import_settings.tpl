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
 * $LastChangedDate: 2014-06-15 00:41:23 +0200 (zo, 15 jun 2014) $
 * $Rev: 3095 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_import_settings.tpl 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 *}
 {extends file="popup.tpl"}
{block name=title}{$LN_settings_import_file}{/block}
{block name=contents}

<br/>
<div class="light">
<form id='uploadform'>
<div>
<br/>
<table class="hmid">
<tr>
<td class="nowrap bold">{$LN_settings_filename}:</td>
</tr><tr>
<td>
<input type="file" name="filename" id="files" class="hidden"/>
<input type="text" name="_filename" id="_files" placeholder="{$LN_settings_filename}" required class="textbox18m"/>
<input type="button" class="submitsmall" id="browse_button" value="{$LN_browse}"/>
<progress id="progress_import"></progress>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td class="centered" colspan="2">
<input type="button" class="submitsmall" name="load_settings" id="submit_form" value="{$LN_settings_upload}"/>
<input type="hidden" name="cmd" id="command" value="{$command|escape:htmlall}"/>
<input type="hidden" name="challenge" value="{$challenge|escape:htmlall}"/>
<input type="hidden" name="referrer" id="referrer" value="{$referrer|escape:htmlall}"/>
</td>
</tr>
</table>
</div>
</form>
</div>
{/block}

