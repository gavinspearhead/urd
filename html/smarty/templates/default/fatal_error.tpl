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
 * $LastChangedDate: 2013-07-26 00:54:03 +0200 (vr, 26 jul 2013) $
 * $Rev: 2882 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: fatal_error.tpl 2882 2013-07-25 22:54:03Z gavinspearhead@gmail.com $
 *}
{if $showmenu != 0} 
{include file="head.tpl" title=$title}
{/if}

<div class="light">
<p>
{$msg}</p>
{if $link neq NULL and $link_msg neq NULL}
<a href="{$link}">{$link_msg}</a>
{/if}
</div>
{if isset($__message) && is_array($__message) && count($__message) > 0 }

<div id="overlay">
<div id="message">
<div class="closebutton buttonlike noborder fixedright down5" onclick="javascript:hide_overlay('{$closelink}');"> </div>
<div class="set_title centered">{$LN_fatal_error_title}</div>
<div id="hideoverlay"></div>

<div id="messagecontent" class="light">

{foreach $__message as $msg}
{$msg|escape:htmlall}<br/><br/>
{/foreach}

</div>
</div>
</div>
{/if}

{if $showmenu != 0} 
{include file="foot.tpl"}
{/if}
