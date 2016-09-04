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
 * $LastChangedDate: 2014-06-26 00:01:04 +0200 (do, 26 jun 2014) $
 * $Rev: 3116 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_preview.tpl 3116 2014-06-25 22:01:04Z gavinspearhead@gmail.com $
 *}
{extends file="popup.tpl"}
{block name=title}{$title_str}{/block}
{block name=contents}
    <div class="centered">
{if $finished == 0}
    <div class="waitingimg centered"></div>
	<br/>
    <div class="centered inline">
    <span>
    {urd_progressbar width=240 complete=$progress done=progress_done remain=progress_remain colour="green" background="grey"}
    <span class="progress floatright">&nbsp;{$progress}%</span><br><span class="progress"> {$done_size} / {$dlsize}</span>
    </span>
    <input type="hidden" id="title_str" value="{$title_str|escape}"/>
    </div>
{elseif $finished == -1 || $nroffiles == 0}
	{$LN_preview_failed} 
	<p>
    <input type="hidden" id="title_str" value="{$title_str|escape}"/>
	</p>
{else}
    <div class="popup_wrapper700x400">
    <div class="popup_centered700x400">
	{if $nroffiles == 1} 
        <input type="hidden" value="{$do_reload|escape}" id="do_reload"/>
		<input type="hidden" value="{$filetype|escape}" id="filetype"/>
		<input type="hidden" value="{$path|escape}{$file|escape}" id="file"/>
		{if $filetype != 'nzb'}
            <p>
			{$LN_preview_autodisp}<br/>
            </p>
			{$LN_preview_autofail}:<br/>
            {if $filetype == 'image'}
                <span class="buttonlike" onclick="javascript:show_image('{$path|escape}{$file|escape}')";>{$file_utf8}</span>
            {elseif $filetype == 'text'}
                <span class="buttonlike" onclick="javascript:show_contents('{$path|escape}{$file|escape}')";>{$file_utf8}</span>
            {else}
                <span class="buttonlike" onclick="javascript:jump('getfile.php?preview=1&amp;file={$path|escape}{$file|escape}')";>{$file_utf8}</span>
            {/if}
            <input type="hidden" id="title_str" value="{$title_str|escape}"/>
		{else}
			{$LN_preview_view}:<br/>
			<span class="buttonlike" onclick="javascript:jump('getfile.php?preview=1&amp;file={$path|escape}{$file|escape}')";>{$file_utf8}</span><br/>
			<p>
            <input type="hidden" id="uploaded_text" value="{$LN_uploaded}"/>
            <input type="hidden" id="uploaded_file" value="{$file|escape}"/>
			{$LN_preview_nzb}:<br/>
			<span class="buttonlike" onclick="javascript:open_hidden_link('parsenzb.php?preview=1&amp;file={$path|escape}{$file|escape}')";>{$file_utf8}</span></p>
            <input type="hidden" id="title_str" value="{$title_str|escape}"/>
		{/if}
	{else}
		{$LN_preview_autodisp}<br/>
        <input type="hidden" id="title_str" value=""/>
		{$LN_preview_autofail}:<br/>
		<span class="buttonlike" onclick="javascript:jump('viewfiles.php?dir={$path|escape}');">{$path|escape}</span>
		<input type="hidden" name="redirect" id="redirect" value="viewfiles.php?dir={$path|escape}"/>
	{/if}
    </div>
    </div>
{/if}
<p>
</p>
{/block}
