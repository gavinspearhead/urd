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
 * $LastChangedDate: 2011-07-31 00:32:07 +0200 (Sun, 31 Jul 2011) $
 * $Rev: 2283 $
 * $Author: gavinspearhead $
 * $Id: settings.tpl 2283 2011-07-30 22:32:07Z gavinspearhead $
 *}
{strip}
{if $usersettype == $USERSETTYPE_SPOT}
<select id="saved_search" onchange="javascript:update_spot_searches();">
<option value=""></option>
{foreach $saved_searches as $saved_search}
<option value="{$saved_search}" {if $current == $saved_search}selected="selected"{/if}>{$saved_search}&nbsp;</option>
{/foreach}
</select>
{else if $usersettype == $USERSETTYPE_GROUP || $usersettype == $USERSETTYPE_RSS }
<select id="saved_search" onchange="javascript:update_browse_searches();">
<option value=""></option>
{foreach $saved_searches as $saved_search}
<option value="{$saved_search}" {if $saved_search == $current}selected="selected"{/if}>{$saved_search}&nbsp;</option>
{/foreach}
</select>
{else}
not found
{/if}
{/strip}
