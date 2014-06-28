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
 * $LastChangedDate: 2012-06-03 16:36:10 +0200 (Sun, 03 Jun 2012) $
 * $Rev: 2534 $
 * $Author: gavinspearhead $
 * $Id: rsssets.tpl 2534 2012-06-03 14:36:10Z gavinspearhead $
 *}
{strip}
<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{$LN_add_search}</div>
<div id="savename_content">
{$LN_save_search_as}:<br/>
<input type="text" value="{$name|escape:htmlall}" id="savename_val" required placeholder="{$LN_name}" class="width300"/></p>
<div {if $categories_count == 0} class="hidden"{/if}>
{$LN_category}:<br/>
<select name="category" id="category_id">
    <option value=""></option>
    {foreach $categories as $item}	
    <option value="{$item.id}" {if $item.id == $save_category}selected="selected"{/if}>{$item.name|escape:htmlall}</option>
	{/foreach}
</select>
</div>
<div class="centered"><br/>
<input type="button" class="submitsmall" value="{$LN_apply}" onclick="javascript:{if $usersettype == $USERSETTYPE_SPOT}save_spot_search();{else}save_browse_search();{/if}"/>
</div>
</div>
{/strip}
