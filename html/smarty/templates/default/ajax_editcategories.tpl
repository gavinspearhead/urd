{* Smarty *}
{*
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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_editcategories.tpl 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}
<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{$LN_editcategories} </div>

<div class="light">

<br/>
<table class="hmid">
<tr><td>{$LN_category}:</td>
<td>
<input type="hidden" name="cat_id" id="cat_id" value="new"/>
<select name="category" id="category_id" onchange="javascript:get_category_name();">
    <option value="new">{$LN_newcategory}</option>
    {foreach $categories as $item}		
    <option value="{$item.id}">{$item.name|escape:htmlall}</option>
	{/foreach}
    </select>
</td></tr>
<tr><td>{$LN_name}:</td><td><input type="text" name="cat_name" id="cat_name" value="" size="{$text_box_size}"/></td></tr>
<tr><td colspan="2" class="centered"><br/>
<input type="button" name="add" value="{$LN_apply}" onclick="javascript:update_category();" class="submit"/>
<input type="button" name="delete" value="{$LN_delete}" class="submit" onclick="javascript:delete_category();" />
</td>
</tr>

</table>
</div>
</div>
