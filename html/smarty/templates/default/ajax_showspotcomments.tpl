{* Smarty *}
{*
 *  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2011-01-15 01:03:01 +0100 (Sat, 15 Jan 2011) $
 * $Rev: 2027 $
 * $Author: gavinspearhead $
 * $Id: browse.tpl 2027 2011-01-15 00:03:01Z gavinspearhead $
 *}

{function name=do_show_comments comments=''}
    {foreach $comments as $comment}
    <tr class="comment_poster"><td colspan="2">
    <div class="floatleft">
    {if $comment.user_avatar != ''}<img class="floatleft" src="{$comment.user_avatar}"/>&nbsp; {/if}
    {$LN_showsetinfo_postedby}: {$comment.from|escape} ({$comment.userid|escape})&nbsp; </div>
    <div class="floatright"> @ {$comment.stamp|escape}</div>
    <div class="inline iconsizeplus deleteicon buttonlike" onclick="javascript:add_blacklist('{$comment.userid|escape:javascript}', 'spotterid');" {urd_popup type="small" text=$LN_quickmenu_addblacklist }></div>
    </td></tr>
    <tr class="comment"><td colspan="2">

    {$comment.comment}
    </td></tr>
    <tr class="comment"><td colspan="2"><br/></td></tr>
    {/foreach}
{/function}

{do_show_comments comments=$comments}

