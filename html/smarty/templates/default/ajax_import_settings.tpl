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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_import_settings.tpl 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 *}
<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{$LN_settings_import_file} </div>
<br/>
<div class="light">
<form method='post' enctype='multipart/form-data' action='{$referrer}' id='uploadform'>
<div>
<br/>
<table class="hmid">
<tr>
<td>{$LN_settings_filename}:</td>
<td><input type="file" name="filename" id="files" size="60"/></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td class="centered" colspan="2">
<input type="button" class="submitsmall" name="load_settings" id="submit_form" value="{$LN_settings_upload}"/>
<input type="hidden" name="cmd" id="command" value="{$command}"/>
<input type="hidden" name="challenge" value="{$challenge}"/>
<input type="hidden" name="referrer" id="referrer" value="{$referrer}"/>
</td>
</tr>
</table>
</div>
</form>
</div>
