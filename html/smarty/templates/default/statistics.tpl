{* Smarty *}{*
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
 * $Id: statistics.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}
{extends file="head.tpl"}
{block name=contents}
<h3 class="title">{$title}</h3>

{capture assign=selector}
<div class="pref_selector">{strip}
<input type="hidden" id="selected" value="{$thisyear}"/>
<input type="hidden" id="tab" value="{$tab}"/>
<ul class="tabs">
{foreach $years as $year}
<li onclick="javascript:select_tab_stats('{$year|escape:javascript}', 'activity', '{$year|escape:javascript}', 'months')" class="tab" id="{$year|escape}_bar">{$year|escape}
<input type="hidden" name="tabs" value="{$year|escape}"/>
</li>
{/foreach}
<li onclick="javascript:select_tab_stats('activity', 'activity', null, 'years')" class="tab" id="activity_bar">{$LN_stats_overview}
<input type="hidden" name="tabs" value="activity"/></li>
<li onclick="javascript:select_tab_stats('supply', 'supply')" class="tab" id="supply_bar">{$LN_menubrowsesets}
<input type="hidden" name="tabs" value="supply"/></li>
</ul>
</div>
{/strip}
{/capture}

{* Creating the divs as the basis for copy/pasting in javascript later on *}
<div id="ng_headerbox" class="newsgroups">
{$selector}
</div>

<table class="statistics" id="stats_table">
<tr><td>
<div id="show_stats">
</div>
</td></tr></table>

<script type="text/javascript">
$(document).ready(function() {
    select_tab_stats('{$thisyear|escape:javascript}', 'activity', '{$thisyear|escape:javascript}', 'months');
});
</script>

{/block}
