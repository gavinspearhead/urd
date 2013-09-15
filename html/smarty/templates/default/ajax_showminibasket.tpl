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
 * $LastChangedDate: 2013-08-04 23:53:54 +0200 (zo, 04 aug 2013) $
 * $Rev: 2888 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showminibasket.tpl 2888 2013-08-04 21:53:54Z gavinspearhead@gmail.com $
 *}

{strip}
{if $nrofsets == 0}
0
{else} 
<div class="inline iconsizeplus buttonlike cleartop3 downicon noborder" {urd_popup type="small" text=$LN_browse_download } onclick="javascript:Whichbutton('urddownload', event);"></div>&nbsp;
<span onclick="javascript:update_basket_display(1);">
{$nrofsets} {$LN_sets} - {$totalsize}&nbsp;</span>
<div class="inline"> 
<div class="inline iconsizeplus buttonlike cleartop3 purgeicon noborder" {urd_popup type="small" text=$LN_browse_emptylist } onclick="javascript:Whichbutton('clearbasket', event);"/></div>
</div>
<input name="timestamp" id="timestamp" type="hidden" value="{$download_delay}"/> 
<input name="dl_dir" id="dl_dir" type="hidden" value="{$dl_dir|escape:htmlall}"/>
<input name="add_setname" id="add_setname" type="hidden" value="{$add_setname}"/>
<input name="dlsetname" id="dlsetname" type="hidden" value="{$dlsetname|escape:htmlall}"/>
{/if}
{/strip}

