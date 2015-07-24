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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showminibasket.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}

{strip}
{if $nrofsets == 0}
0
{else} 
<div class="inline"> 
<div class="inline iconsizeplus buttonlike cleartop3 downicon noborder" {urd_popup type="small" text=$LN_browse_download } id="download_button"></div>&nbsp;
<span id="change_basket" class="buttonlike">
{$nrofsets} {$LN_sets} - {$totalsize}&nbsp;</span>
<div class="inline"> 
<div class="inline iconsizeplus buttonlike cleartop3 foldericon noborder" {urd_popup type="small" text=$LN_browse_savenzb } id="nzb_button"/></div>
</div>&nbsp;
<div class="inline iconsizeplus buttonlike cleartop3 purgeicon noborder" {urd_popup type="small" text=$LN_browse_emptylist } id="clear_button"/></div>
</div>
<input name="timestamp" id="timestamp" type="hidden" value="{$download_delay|escape:htmlall}"/> 
<input name="dl_dir" id="dl_dir" type="hidden" value="{$dl_dir|escape:htmlall}"/>
<input name="add_setname" id="add_setname" type="hidden" value="{$add_setname|escape:htmlall}"/>
<input name="dlsetname" id="dlsetname" type="hidden" value="{$dlsetname|escape:htmlall}"/>
</div>
{/if}
{/strip}

