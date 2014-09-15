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
 * $LastChangedDate: 2014-06-27 17:26:47 +0200 (vr, 27 jun 2014) $
 * $Rev: 3121 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_show_usenet_servers.tpl 3121 2014-06-27 15:26:47Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}

<input type="hidden" name="order" id="order" value="{$sort}"/>
<input type="hidden" name="order_dir" id="order_dir" value="{$sort_dir}"/>

{$up="<img src='$IMGDIR/small_up.png' width='9' height='6' alt=''>"}
{$down="<img src='$IMGDIR/small_down.png' width='9' height='6' alt=''>"}
{if $sort == ""}{if $sort_dir=='desc'}{$_sort=$up}{else}{$_sort=$down}{/if}{else}{$_sort=""}{/if}
{if $sort == "name"}{if $sort_dir=='desc'}{$name_sort=$up}{else}{$name_sort=$down}{/if}{else}{$name_sort=""}{/if}
{if $sort == "priority"}{if $sort_dir=='desc'}{$priority_sort=$up}{else}{$priority_sort=$down}{/if}{else}{$priority_sort=""}{/if}
{if $sort == "posting"}{if $sort_dir=='desc'}{$posting_sort=$up}{else}{$posting_sort=$down}{/if}{else}{$posting_sort=""}{/if}
{if $sort == "threads"}{if $sort_dir=='desc'}{$threads_sort=$up}{else}{$threads_sort=$down}{/if}{else}{$threads_sort=""}{/if}
{if $sort == "connection"}{if $sort_dir=='desc'}{$connection_sort=$up}{else}{$connection_sort=$down}{/if}{else}{$connection_sort=""}{/if}
{if $sort == "authentication"}{if $sort_dir=='desc'}{$authentication_sort=$up}{else}{$authentication_sort=$down}{/if}{else}{$authentication_sort=""}{/if}
{if $sort == "username"}{if $sort_dir=='desc'}{$username_sort=$up}{else}{$username_sort=$down}{/if}{else}{$username_sort=""}{/if}
{if $sort == "indexing"}{if $sort_dir=='desc'}{$indexing_sort=$up}{else}{$indexing_sort=$down}{/if}{else}{$indexing_sort=""}{/if}

<table class="newsservers">
<tr>
<th onclick="javascript:submit_search_usenet_servers('priority', 'desc');" class="buttonlike uwider fixwidth3c head round_left">{$LN_usenet_priority} {$priority_sort}</th>
<th  onclick="javascript:submit_search_usenet_servers('indexing', 'asc');" class="uwider fixwidth3c head">{$LN_usenet_indexing} {$indexing_sort}</th>
{if $show_post}
<th onclick="javascript:submit_search_usenet_servers('posting', 'asc');" class="buttonlike uwider fixwidth3c head">{$LN_usenet_posting} {$posting_sort}</th>
{/if}
<th onclick="javascript:submit_search_usenet_servers('name', 'asc');" class="buttonlike uwider head">{$LN_name} {$name_sort}</th>
<th onclick="javascript:submit_search_usenet_servers('threads', 'asc');" class="buttonlike uwider fixwidth3c head">{$LN_usenet_threads} {$threads_sort}</th>
<th onclick="javascript:submit_search_usenet_servers('connection', 'asc');" class="buttonlike uwider fixwidth3c head">{$LN_usenet_connection} {$connection_sort}</th>
<th onclick="javascript:submit_search_usenet_servers('authentication', 'asc');" class="buttonlike uwider fixwidth3c head">{$LN_usenet_authentication} {$authentication_sort}</th>
<th onclick="javascript:submit_search_usenet_servers('username', 'asc');" class="buttonlike uwider head">{$LN_username} {$username_sort}</th>
<th class="head round_right right">{$LN_actions}</th>
</tr>

{foreach $usenet_servers as $usenet_server}

{capture assign="enable"}
<div class="floatleft status_light red buttonlike down2" onclick="javascript:usenet_action('enable_server', {$usenet_server->id})" {urd_popup type="small" text=$LN_usenet_enable|escape }></div>
{/capture}
{capture assign="disable"}
<div class="floatleft status_light green buttonlike down2" onclick="javascript:usenet_action('disable_server', {$usenet_server->id})" {urd_popup type="small" text=$LN_usenet_disable|escape }></div>
{/capture}

<tr name="content" class="even content server_{if $usenet_server->priority eq 0}disabled{else}enabled{/if}">
<td class="uwider">{if $usenet_server->priority eq 0}{$enable}{else}{$disable}{/if}
{if $usenet_server->priority neq 0}<div class="floatleft">&nbsp;{$usenet_server->priority|escape|truncate:$maxstrlen}</div>{/if}
</td>
<td class="fixwidth3c">
{urd_checkbox value="{if $usenet_server->id eq $primary}1{else}0{/if}" name="primary" id="primary_{$usenet_server->id}" post_js="{if $usenet_server->id neq $primary}usenet_action('set_preferred',{$usenet_server->id}){/if}"}
</td>
{if $show_post}
<td class="fixwidth3c">
{urd_checkbox value="{$usenet_server->posting}" name="posting" id="posting_{$usenet_server->id}" post_js="usenet_action({if $usenet_server->posting eq 1}'disable_posting'{else}'enable_posting'{/if}, {$usenet_server->id});"}
</td>
{/if}
<td>{$usenet_server->name|escape|truncate:$maxstrlen}</td>
<td class="fixwidth3c">{$usenet_server->threads|escape|truncate:$maxstrlen}</td>
<td class="fixwidth3c">{$usenet_server->connection|truncate:$maxstrlen}</td>
<td class="fixwidth3c" {urd_popup type="small" text=$LN_usenet_needsauthentication }> 
{urd_checkbox value="{if $usenet_server->authentication eq 1}1{else}0{/if}" name="need_auth" id="need_auth_{$usenet_server->id}" post_js="toggle_usenet_auth({$usenet_server->id}, 'need_auth_{$usenet_server->id}')"}
<td>
{if $usenet_server->authentication eq 1}
{$usenet_server->username|escape|truncate:$maxstrlen}
{/if}
</td>

<td>
<div class="floatright">
<div class="inline iconsizeplus editicon buttonlike" {urd_popup type="small" text=$LN_usenet_edit } onclick="javascript:edit_usenet_server({$usenet_server->id});"></div>
<div class="inline iconsizeplus deleteicon buttonlike" {urd_popup type="small" text=$LN_usenet_delete } onclick="javascript:usenet_action_confirm('delete_server',{$usenet_server->id}, '{$LN_delete} {$usenet_server->name|escape}?');"></div>
</div>
</td>
</tr>
{foreachelse}
<tr><td colspan="9" class="centered highlight even bold">{$LN_error_noserversfound}</td></tr>
{/foreach}
{if count($usenet_servers) > 12}
<tr><td colspan="9" class="feet round_both_bottom">&nbsp;</td>
{/if}
</table>
<div><br/></div>

