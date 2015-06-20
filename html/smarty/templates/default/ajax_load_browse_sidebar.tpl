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
 * $LastChangedDate: 2011-03-10 19:37:28 +0100 (Thu, 10 Mar 2011) $
 * $Rev: 2102 $
 * $Author: gavinspearhead $
 * $Id: ajax_browse.tpl 2102 2011-03-10 18:37:28Z gavinspearhead $
 *}

<div id="sidebar_contents">

<div class="even center bold" id="adv_search_head">
{$LN_advanced_search}
</div>

<div class="highlight2 menu_head">
{$LN_setsize}:
</div>
<div class="even menu_head down3">
<div class="inline"><input type="text" id="minsetsize" name="minsetsize" class="sidebar" value=""/>&nbsp;</div>
<div id="setsize" class="inline slider">&nbsp;</div>
<div class="inline"><input type="text" id="maxsetsize" name="maxsetsize" class="sidebar" value=""/></div>
</div>

<div class="highlight2 menu_head">
{$LN_age}:
</div>
<div class="even menu_head down3">
<div class="inline"><input type="text" id="minage" name="minage" class="sidebar" value=""/>&nbsp;</div>
<div id="setage" class="inline slider">&nbsp;</div>
<div class="inline"><input type="text" id="maxage" name="maxage" class="sidebar"value=""/></div>
</div>

<div class="highlight2 menu_head">
{$LN_rating}:</div>
<div class="even menu_head down3">
<div class="inline"><input type="text" id="minrating" name="minrating" class="sidebar" value=""/>&nbsp;</div>
<div id="setrating" class="inline slider">&nbsp;</div>
<div class="inline"><input type="text" id="maxrating" name="maxrating" class="sidebar" value=""/></div>
</div>

<div class="highlight2 menu_head">
{$LN_complete}:
</div>
<div class="even menu_head down3">
<div class="inline"><input type="text" id="mincomplete" name="mincomplete" class="sidebar" value=""/>&nbsp;</div>
<div id="setcomplete" class="inline slider">&nbsp;</div>
<div class="inline"><input type="text" id="maxcomplete" name="maxcomplete" class="sidebar" value=""/></div>
</div>

<div class="highlight2 menu_head">

<select name="flag" class="search" id="flag">
    <option value="">{$LN_browse_allsets}</option>
    <option value="interesting">{$LN_browse_interesting}</option>
    <option value="read">{$LN_browse_downloaded}</option>
{if $show_makenzb != 0}
<option value="nzb">{$LN_browse_nzb}</option>
{/if}
<option value="kill">{$LN_browse_killed}</option>
</select>&nbsp;<br/>
</div>

<div class="even">
<br>
<input type="button" value="{$LN_add_search}" id="save_search_button" class="submitsmall"/>
<input type="button" value="{$LN_reset}" id="reset_button" class="submitsmall"/>
</div>

</div>
