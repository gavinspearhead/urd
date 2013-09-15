{* Smarty *}{*
 *  This file is part of Urd.
 *  vim:ts=4:expandtab:cindent
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
 * $Id: ajax_admin_log.tpl 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 *}

{$up="<img src='$IMGDIR/small_up.png' alt=''>"}
{$down="<img src='$IMGDIR/small_down.png' alt=''>"}
{if $sort == "date"}{if $sort_dir=='desc'}{$date_sort=$up}{else}{$date_sort=$down}{/if}{else}{$date_sort=""}{/if}
{if $sort == "time"}{if $sort_dir=='desc'}{$time_sort=$up}{else}{$time_sort=$down}{/if}{else}{$time_sort=""}{/if}
{if $sort == "level"}{if $sort_dir=='desc'}{$level_sort=$up}{else}{$level_sort=$down}{/if}{else}{$level_sort=""}{/if}
{if $sort == "msg"}{if $sort_dir=='desc'}{$msg_sort=$up}{else}{$msg_sort=$down}{/if}{else}{$msg_sort=""}{/if}

<div class="log">
<table class="tasks">
<tr>
<th onclick="submit_sort_log('date')" class="head buttonlike round_left fixwidth6c">{$LN_log_date} {$date_sort}</th>
<th onclick="submit_sort_log('time')" class="head buttonlike fixwidth5c">{$LN_time} {$time_sort}</th>
<th onclick="submit_sort_log('level')" class="head buttonlike fixwidth6c">{$LN_log_level} {$level_sort}</th>
<th onclick="submit_sort_log('msg')" class="head buttonlike round_right">{$LN_log_msg} {$msg_sort}</th>
</tr>
{foreach $logs as $log}
<tr class="even content" onmouseover="javascript:ToggleClass(this,'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');">
<td>{$log.date|escape:htmlall}</td>
<td>{$log.time|escape:htmlall}</td>
<td>{$log.level|escape:htmlall}</td>
<td>{$log.msg|escape:htmlall}</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="centered highlight textback">{$LN_error_nologsfound}</td></tr>
{/foreach}
</table>
</div>
</div>

