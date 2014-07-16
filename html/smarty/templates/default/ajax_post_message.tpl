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

<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{$LN_post_message}</div>
<div class="light">
<br/>
<input type="hidden" id="type" value="{$type|escape:htmlall}"/>
<input type="hidden" id="reference" value="{$reference|escape:htmlall}"/>
<table class="hmid">
<tr {if $type!='group'} class="hidden"{/if} ><td {urd_popup type="small" text=$LN_post_newsgroupext2}>{$LN_post_newsgroup}:</td>
<td>
<select name="newsgroup" id="groupid" class="width300">
{html_options options=$groups selected=$groupid}
</select>
</td></tr>
<tr><td {urd_popup type="small" text=$LN_post_subjectext2}>{$LN_post_subject}:</td><td><input type="text" name="subject" id="subject" class="width300" value="{$subject|escape:htmlall}" required placeholder="{$LN_post_subject}"/></td></tr>
<tr><td {urd_popup type="small" text=$LN_post_posternameext}>{$LN_post_postername}:</td><td><input type="text" name="postername" id="postername"value="{$poster_name|escape:htmlall}" class="width300" required placeholder="{$LN_post_postername}"/></td></tr>
<tr><td {urd_popup type="small" text=$LN_post_posteremailext}>{$LN_post_posteremail}:</td><td><input type="email" name="posteremail" id="posteremail" value="{$poster_email|escape:htmlall}" class="width300" required placeholder="{$LN_post_posteremail}"/></td></tr>
{if $type == 'comment'} 
<tr><td {urd_popup type="small" text=$LN_post_newsgroupext2}>{$LN_rating}:</td><td>
<select name="rating" id="rating" class="width300">
{html_options options=$ratings selected=$groupid}
</select>
{/if}
<tr><td {urd_popup type="small" text=$LN_post_messagetextext}>{$LN_post_messagetext}:</td><td><textarea name="messagetext" id="messagetext" rows="8" class="width300" placeholder="{$LN_post_messagetext}" required>{$content|escape:htmlall}</textarea>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" class="centered"><input type="submit" value="{$LN_post_post}" class="submit" onclick="javascript:post_message();"/></td></tr>

</table>
</div>
