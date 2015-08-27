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
 * $LastChangedDate: 2014-06-26 00:01:04 +0200 (do, 26 jun 2014) $
 * $Rev: 3116 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_preview.tpl 3116 2014-06-25 22:01:04Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}
{block name=header}
<div class="closebutton buttonlike noborder fixedright down5" id="{block name="close_button_id"}close_button{/block}"></div>
<div class="set_title head centered">{block name="title"}&nbsp;{/block}</div>
{/block}
<div class="light">
{block name="contents"}
<br/><br/>
<div class="center bold">{$LN_loading}</div>
{/block}
</div>
</div>
