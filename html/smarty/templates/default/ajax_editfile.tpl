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
 * $Id: ajax_editfile.tpl 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 *}
{extends file="popup.tpl"}
{block name=title}
{if $new_file}
    {$LN_viewfiles_newfile}
{else}
    {$LN_edit_file} - {$filename|escape:htmlall|truncate:$maxstrlen:'...':false:true}
{/if}

{/block}
{block name=contents}

<div class="padding10">
{if isset($message) && $message != ''}
    {$message}
{else}
    <br/>
    <table>
    <tr>
    {if $new_file}
        <td>{$LN_filename}: </td>
        <td >
        <input type="text" value="{$filename|escape:htmlall}" name="filename" id="filename_editfile" required placeholder="{$LN_filename}" size="40"/>
        {urd_checkbox name="newdir" id="newdir" post_js="toggle_textarea('filecontents_editfile', 'newdir');"} {$LN_post_directory}
        <input type="hidden" value="new" name="newfile" id="newfile"/>
        <input type="hidden" value="{$LN_error_needfilenames}" name="filename_err" id="filename_err"/>
        </td></tr>
        <tr>
        <td colspan="2">
    {else}
        <td colspan="2">
        <input type="hidden" value="{$filename|escape:htmlall}" name="filename" id="filename_editfile"/>
    {/if}
    <input type="hidden" value="{$directory|escape:htmlall}" name="directory" id="directory_editfile"/>
    <textarea class="filecontents" name="filecontents" id="filecontents_editfile" required>{$file_contents}</textarea>
    </td></tr>
    <tr><td colspan="2"><input type="button" class="submitsmall floatright" id="submit_button" value="{$LN_viewfiles_savefile}"/>
    </td>
    </tr>
    </table>
    </div>
{/if}
{/block}
