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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_show_searchoptions.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}

{$up="<img src='$IMGDIR/small_up.png' alt=''/>"}
{$down="<img src='$IMGDIR/small_down.png' alt=''/>"}
{if $sort == "name"}{if $sort_dir=='desc'}{$name_sort=$up}{else}{$name_sort=$down}{/if}{else}{$name_sort=""}{/if}
{if $sort == "search_url"}{if $sort_dir=='desc'}{$search_url_sort=$up}{else}{$search_url_sort=$down}{/if}{else}{$search_url_sort=""}{/if}

<table class="articles">
<tr>
<th onclick="javascript:submit_search_searchoptions('name', 'asc');" class="head buttonlike round_left">{$LN_name} {$name_sort}</th>
<th onclick="javascript:submit_search_searchoptions('search_url', 'asc');" class="head buttonlike">{$LN_buttons_url} {$search_url_sort}</th>
<th class="head right round_right">{$LN_actions}</th>
</tr>

{foreach $buttons as $button}
<tr class="even content" onmouseover="javascript:$(this).toggleClass('highlight2');" onmouseout="javascript:$(this).toggleClass('highlight2');">
<td>{$button->get_name()|escape|truncate:$maxstrlen}</td>
<td ><span class="buttonlike" {urd_popup type="small" text=$LN_buttons_clicktest} onclick="javascript:jump('{$button->get_url()|replace:"\$q":"test"|escape:javascript}', true);">{$button->get_url()|escape|truncate:$maxstrlen}</span></td>
<td>
<div class="floatright">
<div class="inline iconsizeplus editicon buttonlike" {urd_popup type="small" text=$LN_buttons_edit } onclick="javascript:buttons_action('edit',{$button->get_id()});"></div>
<div class="inline iconsizeplus deleteicon buttonlike" {urd_popup type="small" text=$LN_delete } onclick="javascript:buttons_action_confirm('delete_button', {$button->get_id()}, '{$LN_delete} {$button->get_name()|escape:'javascript'}');"></div>
</div>
</td>
</tr>
{foreachelse}
<tr><td colspan="8" class="centered highlight even bold">{$LN_error_nosearchoptionsfound}</td></tr>
{/foreach}
</table>
<input type="hidden" name="order_dir" id="order_dir" value="{$sort_dir|escape}"/>
<input type="hidden" name="order" id="order" value="{$sort|escape}"/>
