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
 * $LastChangedDate: 2011-07-04 23:51:30 +0200 (Mon, 04 Jul 2011) $
 * $Rev: 2245 $
 * $Author: gavinspearhead $
 * $Id: statistics.tpl 2245 2011-07-04 21:51:30Z gavinspearhead $
 *}
{$thisyear=$smarty.now|date_format:"Y"}
{$thismonth=$smarty.now|date_format:"m"}

{if $type == 'activity'}
    {if $period == 'years'}
        {* Creating the divs as the basis for copy/pasting in javascript later on *}
        <div id="template_overview" >
        {foreach $subtypes as $subtype}
            <canvas id="years_{$subtype}_size"></canvas>
            <canvas id="years_{$subtype}_count"></canvas>
      <script>
            load_plot('years_{$subtype}_size', 'activity', { 'period': 'years', 'source': 'size', 'subtype': '{$subtype|escape:javascript}' });
            load_plot('years_{$subtype}_count', 'activity', { 'period': 'years', 'source': 'count', 'subtype': '{$subtype|escape:javascript}' });
      </script>
            <br/>
        {/foreach}
        </div> 
    {else if $period == 'months'}
        <div id="template_{$year}">
        {foreach $subtypes as $subtype}
            <canvas id="month_{$subtype}_size" onclick="javascript:select_tab_stats({$year}, 'activity','{$year}', 'days', 'size', '{$subtype}');"></canvas>
            <canvas id="month_{$subtype}_count" onclick="javascript:select_tab_stats({$year}, 'activity','{$year}', 'days', 'count', '{$subtype}');"></canvas>
      <script>
            load_plot('month_{$subtype}_size', 'activity', { 'period': 'months', 'year': {$year|escape:javascript}, 'source': 'size', 'subtype': '{$subtype|escape:javascript}' });
            load_plot('month_{$subtype}_count', 'activity', { 'period': 'months', 'year': {$year|escape:javascript}, 'source': 'count', 'subtype': '{$subtype|escape:javascript}' });
      </script>
            <br/>
        {/foreach}
        </div>
    {else if $period == 'days'}
        <div id="template_{$year}">
        {if $year == $thisyear}{$endcnt=$thismonth}{else}{$endcnt=12}{/if}
        {for $cnt=1 to $endcnt}
        <canvas id="c_{$cnt}_{$subtype}_count"></canvas>
      <script>
            load_plot('c_{$cnt}_{$subtype}_count', 'activity', { 'period': 'days', 'month': {$cnt},  'year': {$year|escape:javascript}, 'source': 'size', 'subtype': '{$subtype|escape:javascript}' });
      </script>
        {if $cnt is even}<br/>{/if}
        {/for}
        </div>
    {/if}
{else if $type == 'spots_details'}
<table>
<tr><td valign="top">
      <canvas id="spots_details_month"></canvas>
      <canvas id="spots_details_dow"></canvas>
      <canvas id="spots_details_1a"></canvas>
      <canvas id="spots_details_1b"></canvas>
      <canvas id="spots_details_1c"></canvas>
      <canvas id="spots_details_1d"></canvas>
      <canvas id="spots_details_1z"></canvas>
      <canvas id="spots_details_3a"></canvas>
      <canvas id="spots_details_3b"> </canvas>
      <canvas id="spots_details_2c"></canvas>
      <script>
            load_plot('spots_details_month', 'spots_details', { 'period': 'month' });
            load_plot('spots_details_dow', 'spots_details', { 'period': 'dow' });
            load_plot('spots_details_1a', 'spots_subcat', { 'cat': 1, 'subcat': 'a'});
            load_plot('spots_details_1b', 'spots_subcat', { 'cat': 1, 'subcat': 'b'});
            load_plot('spots_details_1c', 'spots_subcat', { 'cat': 1, 'subcat': 'c'});
            load_plot('spots_details_1d', 'spots_subcat', { 'cat': 1, 'subcat': 'd'});
            load_plot('spots_details_1z', 'spots_subcat', { 'cat': 1, 'subcat': 'z'});
            load_plot('spots_details_3a', 'spots_subcat', { 'cat': 3, 'subcat': 'a'});
            load_plot('spots_details_3b', 'spots_subcat', { 'cat': 3, 'subcat': 'b'});
            load_plot('spots_details_2c', 'spots_subcat', { 'cat': 2, 'subcat': 'c'});
      </script>
</td>
<td valign="top">
      <canvas id="spots_details_week"></canvas>
      <canvas id="spots_details_hour"></canvas>
      <canvas id="spots_details_0a"></canvas>
      <canvas id="spots_details_0b"></canvas>
      <canvas id="spots_details_0c"></canvas>
      <canvas id="spots_details_0z"></canvas>
      <canvas id="spots_details_0d"></canvas>
      <canvas id="spots_details_2a"></canvas>
      <canvas id="spots_details_2b"></canvas>

      <script>
            load_plot('spots_details_week', 'spots_details', { 'period': 'week' });
            load_plot('spots_details_hour', 'spots_details', { 'period': 'hour' });
            load_plot('spots_details_0a', 'spots_subcat', { 'cat': 0, 'subcat': 'a'});
            load_plot('spots_details_0b', 'spots_subcat', { 'cat': 0, 'subcat': 'b'});
            load_plot('spots_details_0c', 'spots_subcat', { 'cat': 0, 'subcat': 'c'});
            load_plot('spots_details_0z', 'spots_subcat', { 'cat': 0, 'subcat': 'z'});
            load_plot('spots_details_0d', 'spots_subcat', { 'cat': 0, 'subcat': 'd'});
            load_plot('spots_details_2a', 'spots_subcat', { 'cat': 2, 'subcat': 'a'});
            load_plot('spots_details_2b', 'spots_subcat', { 'cat': 2, 'subcat': 'b'});
      </script>

  </td>
</tr>
</table>

{else if $type == 'supply'}
    {if $period == 'month'}
        {$cnt=0}
        {foreach $years as $year}
            <canvas id="supply_{$year}" onclick="javascript:select_tab_stats('supply', 'supply', '{$year}', 'day');"></canvas>
             <script>
                load_plot('supply_{$year}', 'supply', { 'period': 'month', 'year': '{$year}' });
            </script>
            {$cnt=$cnt+1}
            {if $cnt is even}<br/>{/if}
        {/foreach}

{else if $period == 'day'}
    {if $year == $thisyear}{$endcnt=$thismonth}{else}{$endcnt=12}{/if}
    {for $cnt=1 to $endcnt}
        <canvas id="supply_{$year}_{$cnt}"></canvas>
             <script>
                load_plot('supply_{$year}_{$cnt}', 'supply', { 'period': 'day', 'year': '{$year}', 'month': {$cnt} });
            </script>
        {if $cnt is even}<br/>{/if}
    {/for}
    {if $endcnt is odd}<img src="creategraph.php?type=blank&amp;width={$width|escape}" alt=""/>{/if}
    {else}
        <canvas id="spots_details" onclick="javascript:select_tab_stats('supply', 'spots_details');"></canvas>
        <canvas id="supply_year" onclick="javascript:select_tab_stats('supply', 'supply', null, 'month');"></canvas>
        <script>
            load_plot('spots_details', 'spots_details');
            load_plot('supply_year', 'supply', { 'period' : 'year'});
        </script>


    {/if}
{/if}
