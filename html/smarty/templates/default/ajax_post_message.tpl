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
 * $Id: ajax_post_message.tpl 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}

<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{$LN_post_message}</div>
<div class="light">
<br/>
<table class="hmid">
<tr><td>{$LN_post_newsgroup}:</td>
<td>
<select name="newsgroup" id="groupid" {urd_popup type="small" text=$LN_post_newsgroupext2} class="width300">
{html_options options=$groups selected=$groupid}
</select></td></tr>
<tr><td>{$LN_post_subject}:</td><td><input type="text" name="subject" id="subject" {urd_popup type="small" text=$LN_post_subjectext2} class="width300" value="{$subject}"/></td></tr>
<tr><td>{$LN_post_postername}:</td><td><input type="text" name="postername" id="postername" {urd_popup type="small" text=$LN_post_posternameext} value="{$poster_name}" class="width300"/></td></tr>
<tr><td>{$LN_post_posteremail}:</td><td><input type="text" name="posteremail" id="posteremail" {urd_popup type="small" text=$LN_post_posteremailext} value="{$poster_email}" class="width300"/></td></tr>
<tr><td>{$LN_post_messagetext}:</td><td><textarea name="messagetext" id="messagetext" rows="8" class="width300" {urd_popup type="small" text=$LN_post_messagetextext} >{$content}</textarea>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" class="centered"><input type="button" value="{$LN_post_post}" class="submit" onclick="javascript:post_message();"/></td></tr>

</table>
</div>
