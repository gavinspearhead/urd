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
 * $Id: ajax_post_message.tpl 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}
{extends file="popup.tpl"}
{block name=title}{$LN_transfers_post_spot}{/block}
{block name=contents}

<div class="light">
<form id="post_spot_form">
<table class="">
<tr id="category_row">
<td>{$LN_category}</td>
<td>
<select id="category" name="category">
{foreach $categories as $cat}
    <option value="{$cat.id}">{$cat.name}</option>
{/foreach}
</select>

</td>
</tr>
<tr><td>{$LN_browse_subject}</td><td><input type="text" name="subject" id="subject" class="width300" required placeholder="{$LN_browse_subject}"/></td></tr>
<tr><td>{$LN_spots_tag}</td><td><input type="text" name="tag" id="tag" class="width300" placeholder="{$LN_spots_tag}"/></td></tr>
<tr><td>{$LN_feeds_url}</td><td><input type="text" name="weburl" id="weburl" class="width300" placeholder="http://"/></td></tr>
<tr><td>{$LN_tasks_description}</td><td><textarea type="text" name="description" id="description" rows="8" class="width300" required>{$content}</textarea></td>
<td>
{foreach $smileys as $smiley}
<img src="{$IMGDIR}/smileys/{$smiley}.gif" alt="{$smiley}" name="{$smiley}" title="{$smiley}" class="button" onclick="javascript:add_text('[img={$smiley|escape:javascript}]', $('#description'));">
{/foreach}
</td>
</tr>
<tr><td>{$LN_NZB_file}</td><td colspan="2">
<input type="text" name="_nzbfile" id="_nzbfile" style="width:150px;"/>
<input type="file" name="nzbfile" id="nzbfile" style="display:none"/>
<input type="button" id="nzb_upload" class="submitsmall" value="{$LN_browse}"/> <progress id="progress_nzb"></progress>
</td></tr>
<tr><td>{$LN_image_file}</td><td colspan="2">
<input type="text" name="_imagefile" id="_imagefile" style="width:150px;"/>
<input type="file" name="imagefile" id="imagefile" style="display:none"/>
<input type="button" class="submitsmall" id="image_upload" value="{$LN_browse}"/> <progress id="progress_image"></progress>
</td></tr>
</table>
<table>
<tr id="subcats"></tr>
</table>
</form>
<div class="centered">
<input type="submit" value="{$LN_post_post}" id="post_spot" class="submit"/>
{/block}
