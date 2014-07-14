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
 * $Id: ajax_admintasks.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}

{capture assign=topskipper}{strip}
{if count($pages) > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages class=ps js=tasks_offset extra_class="margin10"}
{else}<br/>
{/if}
{/strip}
{/capture}

{* Making a 'top' and a 'bottom' skipper: *}
{capture assign=bottomskipper}{strip}
{if count($pages) > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages class=psb js=tasks_offset extra_class="margin10"}
{else}<br/>
{/if}
{/strip}
{/capture}

{$up="<img src='$IMGDIR/small_up.png' alt=''>"}
{$down="<img src='$IMGDIR/small_down.png' alt=''>"}
{if $sort == "description"}{if $sort_dir=='desc'}{$description_sort=$up}{else}{$description_sort=$down}{/if}{else}{$description_sort=""}{/if}
{if $sort == "progress"}{if $sort_dir=='desc'}{$progress_sort=$up}{else}{$progress_sort=$down}{/if}{else}{$progress_sort=""}{/if}
{if $sort == "ETA"}{if $sort_dir=='desc'}{$ETA_sort=$up}{else}{$ETA_sort=$down}{/if}{else}{$ETA_sort=""}{/if}
{if $sort == "status"}{if $sort_dir=='desc'}{$status_sort=$up}{else}{$status_sort=$down}{/if}{else}{$status_sort=""}{/if}
{if $sort == "starttime"}{if $sort_dir=='desc'}{$starttime_sort=$up}{else}{$starttime_sort=$down}{/if}{else}{$starttime_sort=""}{/if}
{if $sort == "lastupdate"}{if $sort_dir=='desc'}{$lastupdate_sort=$up}{else}{$lastupdate_sort=$down}{/if}{else}{$lastupdate_sort=""}{/if}
{if $sort == "comment"}{if $sort_dir=='desc'}{$comment_sort=$up}{else}{$comment_sort=$down}{/if}{else}{$comment_sort=""}{/if}


<input type="hidden" name="offset" id="offset" value="{$offset|escape:htmlall}"/>
<input type="hidden" name="order" id="order" value="{$sort|escape:htmlall}"/>
<input type="hidden" name="order_dir" id="order_dir" value="{$sort_dir|escape:htmlall}"/>
{$topskipper}
<table class="tasks">
<tr>
<th id="descr_td" onclick="javascript:submit_search_tasks('description', 'asc');" class="buttonlike head round_left">{$LN_tasks_description} {$description_sort}</th>
<th onclick="javascript:submit_search_tasks('progress', 'asc');" class="fixwidth5 buttonlike head">{$LN_tasks_progress} {$progress_sort}</th>
<th onclick="javascript:submit_search_tasks('ETA', 'asc');" class="fixwidth6 buttonlike head">{$LN_eta} {$ETA_sort}</th>
<th onclick="javascript:submit_search_tasks('status', 'asc');" class="fixwidth5c buttonlike head">{$LN_status} {$status_sort}</th>
<th onclick="javascript:submit_search_tasks('starttime', 'asc');" class="fixwidth9 buttonlike head">{$LN_tasks_added} {$starttime_sort}</th>
<th onclick="javascript:submit_search_tasks('lastupdate', 'asc');" class="fixwidth9 buttonlike head">{$LN_tasks_lastupdated} {$lastupdate_sort}</th>
<th id="comment_td" onclick="javascript:submit_search_tasks('comment', 'asc');" class="buttonlike head">{$LN_tasks_comment} {$comment_sort}</th>
<th class="fixwidth5 right head round_right">{$LN_actions}</th>
</tr>

{foreach $alltasks as $task}
<tr class="even content" onmouseover="javascript:$(this).toggleClass('highlight2');" onmouseout="javascript:$(this).toggleClass('highlight2');">
<td><div class="donotoverflowdamnit" {urd_popup text="`$task.description` `$task.arguments`" type=small}>{$task.description} <b>{$task.arguments}</b></div></td>
<td class="right">{$task.progress}%</td>
<td class="right">{$task.eta}</td>
<td>{$task.status}</td>
<td class="right">{$task.added}</td>
<td class="right">{$task.lastupdated}</td>
<td><div class="donotoverflowdamnit" {urd_popup text="`$task.comment`." type=small}>{$task.comment}</div></td>
<td>
{if $urdd_online eq 1}
<div class="floatright">
{if $task.raw_status == 'Queued' or $task.raw_status == 'Paused' or $task.raw_status == 'Running'}
    <div class="inline iconsizeplus killicon buttonlike" {urd_popup type="small" text=$LN_cancel} onclick="javascript:task_action('cancel', '{$task.urdd_id|escape:javascript}');"></div>
{/if}
{if $task.raw_status == 'Paused'}
    <div class="inline iconsizeplus playicon buttonlike" {urd_popup type="small" text=$LN_transfers_linkstart} onclick="javascript:task_action('continue', '{$task.urdd_id|escape:javascript}');"></div>
{/if}
{if $task.raw_status == 'Running'}
    <div class="inline iconsizeplus pauseicon buttonlike" {urd_popup type="small" text=$LN_pause} onclick="javascript:task_action('pause', '{$task.urdd_id|escape:javascript}');"></div>
{/if}
    <div class="inline iconsizeplus deleteicon buttonlike" {urd_popup type="small" text=$LN_delete} onclick="javascript:task_action('cancel', '{$task.urdd_id|escape:javascript}'); task_action('delete_task', '{$task.queue_id|escape:javascript}');"></div>
</div>
{/if}
</tr>
{foreachelse}
<tr><td colspan="8" class="centered highlight even bold">{$LN_error_notasksfound}</td></tr>
{/foreach}
</table>
{$bottomskipper}
