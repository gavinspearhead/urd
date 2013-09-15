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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_show_searchoptions.tpl 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 *}

{$up="<img src='$IMGDIR/small_up.png' alt=''/>"}
{$down="<img src='$IMGDIR/small_down.png' alt=''/>"}
{if $sort == "name"}{if $sort_dir=='desc'}{$name_sort=$up}{else}{$name_sort=$down}{/if}{else}{$name_sort=""}{/if}
{if $sort == "search_url"}{if $sort_dir=='desc'}{$search_url_sort=$up}{else}{$search_url_sort=$down}{/if}{else}{$search_url_sort=""}{/if}

<table class="articles">
<tr>
<th onclick="javascript:show_buttons('name');" class="head buttonlike round_left">{$LN_name} {$name_sort}</th>
<th onclick="javascript:show_buttons('search_url');" class="head buttonlike">{$LN_buttons_url} {$search_url_sort}</th>
<th class="head right round_right">{$LN_actions}</th>
</tr>

{foreach $buttons as $button}
<tr class="even content" onmouseover="javascript:ToggleClass(this,'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');">
<td>{$button->get_name()|escape|truncate:$maxstrlen}</td>
<td class="buttonlike" {urd_popup type="small" text=$LN_buttons_clicktest} onclick="javascript:jump('{$button->get_url()|replace:"\$q":"test"|escape:javascript}', true);">{$button->get_url()|escape|truncate:$maxstrlen}</td>
<td>
<div class="floatright">
<div class="inline iconsizeplus editicon buttonlike" {urd_popup type="small" text=$LN_buttons_edit } onclick="javascript:buttons_action('edit',{$button->get_id()});"></div>
<div class="inline iconsizeplus deleteicon buttonlike" {urd_popup type="small" text=$LN_delete } onclick="javascript:buttons_action_confirm('delete_button', {$button->get_id()}, '{$LN_delete} {$button->get_name()|escape:'javascript'}');"></div>
</div>
</td>
</tr>
{foreachelse}
<tr><td colspan="8" class="centered highlight textback">{$LN_error_nosearchoptionsfound}</td></tr>
{/foreach}
</table>
<input type="hidden" name="sort_dir" id="sort_dir" value="{$sort_dir}"/>
<input type="hidden" name="sort" id="sort" value="{$sort}"/>

