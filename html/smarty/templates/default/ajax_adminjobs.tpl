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
 * $Id: ajax_adminjobs.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}

{$up="<img src='$IMGDIR/small_up.png width='9' height='6'' alt=''>"}
{$down="<img src='$IMGDIR/small_down.png' width='9' height='6' alt=''>"}
{if $sort == "command"}{if $sort_dir=='desc'}{$command_sort=$up}{else}{$command_sort=$down}{/if}{else}{$command_sort=""}{/if}
{if $sort == "username"}{if $sort_dir=='desc'}{$username_sort=$up}{else}{$username_sort=$down}{/if}{else}{$username_sort=""}{/if}
{if $sort == "at_time"}{if $sort_dir=='desc'}{$at_time_sort=$up}{else}{$at_time_sort=$down}{/if}{else}{$at_time_sort=""}{/if}
{if $sort == "interval"}{if $sort_dir=='desc'}{$interval_sort=$up}{else}{$interval_sort=$down}{/if}{else}{$interval_sort=""}{/if}

<input type="hidden" name="order" id="order" value="{$sort|escape:htmlall}"/>
<input type="hidden" name="order_dir" id="order_dir" value="{$sort_dir|escape:htmlall}"/>

<table class="tasks">
<tr name="content">
<th id="descr_td" onclick="javascript:submit_jobs_search('command', 'asc');" class="buttonlike head round_left">{$LN_jobs_command} {$command_sort}</th>
<th onclick="javascript:submit_jobs_search('username', 'asc');" class="fixwidth8c buttonlike head">{$LN_jobs_user} {$username_sort}</th>
<th onclick="javascript:submit_jobs_search('at_time', 'asc');" class="fixwidth9 buttonlike head">{$LN_time} {$at_time_sort}</th>
<th onclick="javascript:submit_jobs_search('interval', 'asc');" class="fixwidth5 buttonlike head">{$LN_jobs_period} {$interval_sort}</th>
<th class="fixwidth5 round_right head">{$LN_actions}</th>
</tr>

{foreach $alljobs as $job}
<tr class="even content">
<td><div class="donotoverflowdamnit">{$job.task} <b>{$job.arg}</b></div></td>
<td>{$job.user}</td>
<td class="right">{$job.time}</td>
<td class="right">{$job.period}</td>
<td>
{if $urdd_online != 0}
    <div class="floatright iconsize killicon buttonlike" {urd_popup type="small" text=$LN_cancel}  onclick="javascript:job_action('unschedule', '{$job.cmd|escape:javascript}');"></div>
{else}&nbsp;{/if}
</td>
</tr>
{foreachelse}
<tr><td colspan="5" class="centered highlight even bold">{$LN_error_nojobsfound}</td></tr>
{/foreach}
{if count($alljobs) > 12}
<tr><td colspan="5" class="feet round_both_bottom">&nbsp;</td>
{/if}
</table>

