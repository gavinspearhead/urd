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
<div class="inline_block"><input type="text" id="minsetsize" name="minsetsize" size="4" value=""/>&nbsp;</div>
<div id="setsize" class="slider inline_block">&nbsp;</div>
<div class="inline_block"><input type="text" id="maxsetsize" name="maxsetsize" size="4" value=""/></div>
</div>

<div class="highlight2 menu_head">
{$LN_age}:
</div>
<div class="even menu_head down3">
<div class="inline_block"><input type="text" id="minage" name="minage" size="4" value=""/>&nbsp;</div>
<div id="setage" class="slider inline_block">&nbsp;</div>
<div class="inline_block"><input type="text" id="maxage" name="maxage" size="4" value=""/></div>
</div>

<div class="highlight2 menu_head">
{$LN_rating}:
</div>
<div class="even menu_head down3">
<div class="inline_block"><input type="text" id="minrating" name="minrating" size="4" value=""/>&nbsp;</div>
<div id="setrating" class="inline_block slider">&nbsp;</div>
<div class="inline_block"><input type="text" id="maxrating" name="maxrating" size="4" value=""/></div>
</div>

<div class="highlight2 menu_head">
{$LN_poster_name}:
</div>
<div class="even menu_head down3">
<input type="text" id="poster" name="poster" size="10" value="" placeholder="{$LN_poster_name}"/> &nbsp;

<select name="flag" class="search" id="flag">
    <option value="">{$LN_browse_allsets}</option>
    <option value="interesting">{$LN_browse_interesting}</option>
    <option value="read">{$LN_browse_downloaded}</option>
{if $show_makenzb neq 0}
<option value="nzb">{$LN_browse_nzb}</option>
{/if}
<option value="kill">{$LN_browse_killed}</option>
</select>&nbsp;<br/>
</div>

<div class="highlight2 center bold menu_head">{$LN_categories}
</div>

{foreach $subcat_list as $k1 => $item}
    <div style="height:22px"; class="{cycle values="ever, highlight2"}">
    <span onclick="$('#cat_items_{$k1}').toggleClass('hidden');"> &nbsp;{$item.name} </span>
        <div class="floatleft">
            {urd_checkbox name="cat_{$k1}" id="checkbox_cat_{$k1}" value="" post_js="uncheck_all('{$k1}');load_sets( { 'offset':'0', 'setid':'', 'category':'' } );"}
        </div>
    </div>
    <div id="cat_items_{$k1}" class="hidden">
    {foreach $item.subcats as $k2 => $si} 
        <div class="cats" style="margin-left:10px">
            <div onclick="javascript:fold_adv_search('subcat_button_{$k1}_{$k2}', 'subcat_items_{$k1}_{$k2}');" class="subcat_head">
                <div id="subcat_button_{$k1}_{$k2}" class="inline iconsize dynimgplus buttonlike"></div>&nbsp;{$si.name}
            </div>

        <div id="subcat_items_{$k1}_{$k2}" class="hidden ">
            {foreach $si.subcats as $k3 => $item2 }
                <div class="subcats" style="margin-left:10px">
                    {if $item2 != '??'}
                        {capture name=current assign=current}{$subcat_{$k1}_{$k2}_{$k3}|default:''}{/capture}
                        {urd_checkbox value="$current" name="subcat_{$k1}_{$k2}_{$k3}" id="subcat_{$k1}_{$k2}_{$k3}" data="$item2" tristate="1" } 
                    {/if}
                </div> 
            {/foreach}
        </div> 
        </div>
    {/foreach}
    </div>

{/foreach}
<div class="{cycle values='even, highlight2'}">
<br>
<input type="button" value="{$LN_reset}" id="reset_button" class="submitsmall"/>
</div>

</div>
