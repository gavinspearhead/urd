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
            <img src="creategraph.php?period=years&amp;subtype={$subtype|escape}&amp;source=size&amp;type=activity&amp;width={$width|escape}" alt=""/>
            <img src="creategraph.php?period=years&amp;subtype={$subtype|escape}&amp;source=count&amp;type=activity&amp;width={$width|escape}" alt=""/>
            <br/>
        {/foreach}
        </div> 
    {else if $period == 'months'}
        <div id="template_{$year}">
        {foreach $subtypes as $subtype}
            <img class="buttonlike" src="creategraph.php?period=months&amp;year={$year|escape}&amp;subtype={$subtype|escape}&amp;source=size&amp;type=activity&amp;width={$width|escape}" 
                    alt="" onclick="javascript:select_tab_stats({$year}, 'activity','{$year}', 'days', 'size', '{$subtype}');"/>
            <img class="buttonlike" src="creategraph.php?period=months&amp;year={$year|escape}&amp;subtype={$subtype|escape}&amp;source=count&amp;type=activity&amp;width={$width|escape}"
                    alt="" onclick="javascript:select_tab_stats({$year}, 'activity','{$year}', 'days', 'count', '{$subtype}');"/>
            <br/>
        {/foreach}
        </div>
    {else if $period == 'days'}
        <div id="template_{$year}">
        {if $year == $thisyear}{$endcnt=$thismonth}{else}{$endcnt=12}{/if}
        {for $cnt=1 to $endcnt}
        <img src="creategraph.php?period=days&amp;year={$year|escape}&amp;month={$cnt}&amp;subtype={$subtype|escape}&amp;source={$source|escape}&amp;type=activity&amp;width={$width|escape}" alt=""/>
        {if $cnt is even}<br/>{/if}
        {/for}
        {if $endcnt is odd}<img src="creategraph.php?period=blank&amp;width={$width|escape}" alt=""/>{/if}
        </div>
    {/if}
{else if $type == 'spots_details'}
<table>
<tr><td valign="top">
    <img src="creategraph.php?type=spots_details&amp;period=month&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_details&amp;period=dow&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=1&amp;subcat=b&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=1&amp;subcat=d&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=1&amp;subcat=a&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=1&amp;subcat=c&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=1&amp;subcat=z&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=3&amp;subcat=a&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=3&amp;subcat=b&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=2&amp;subcat=b&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=2&amp;subcat=c&amp;width={$width|escape}" alt=""/>
</td>
<td valign="top">
    <img src="creategraph.php?type=spots_details&amp;period=week&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_details&amp;period=hour&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=0&amp;subcat=a&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=0&amp;subcat=c&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=0&amp;subcat=b&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=0&amp;subcat=z&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=0&amp;subcat=d&amp;width={$width|escape}" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=2&amp;subcat=a&amp;width={$width|escape}" alt=""/>
</td>
</tr>
</table>


{else if $type == 'supply'}
    {if $period == 'month'}
        {$cnt=0}
        {foreach $years as $year}
            <img class="buttonlike" src="creategraph.php?type=supply&amp;period=month&year={$year|escape}&amp;width={$width|escape}" alt="" onclick="javascript:select_tab_stats('supply', 'supply', '{$year}', 'day');"/>
            {$cnt=$cnt+1}
            {if $cnt is even}<br/>{/if}
        {/foreach}
        {if $cnt is odd}<img src="creategraph.php?period=blank&amp;width={$width|escape}" alt=""/>{/if}

{else if $period == 'day'}
    {if $year == $thisyear}{$endcnt=$thismonth}{else}{$endcnt=12}{/if}
    {for $cnt=1 to $endcnt}
        <img src="creategraph.php?type=supply&amp;period=day&year={$year|escape}&month={$cnt}&amp;width={$width|escape}" alt=""/>
        {if $cnt is even}<br/>{/if}
    {/for}
    {if $endcnt is odd}<img src="creategraph.php?type=blank&amp;width={$width|escape}" alt=""/>{/if}
    {else}
        <img class="buttonlike" src="creategraph.php?type=spots_details&amp;width={$width|escape}" alt="" onclick="javascript:select_tab_stats('supply', 'spots_details');"/>
        <img class="buttonlike" src="creategraph.php?type=supply&amp;period=year&amp;width={$width|escape}" alt="" onclick="javascript:select_tab_stats('supply', 'supply', null, 'month');"/><br/>
    {/if}
{/if}
