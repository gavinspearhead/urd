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
 * $LastChangedDate: 2014-06-28 23:05:24 +0200 (za, 28 jun 2014) $
 * $Rev: 3131 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: fatal_error.tpl 3131 2014-06-28 21:05:24Z gavinspearhead@gmail.com $
 *}
{extends file="head.tpl"}
{if $showmenu != 0} 
{/if}
{block name=contents}

<div class="light">
{if $link != NULL and $link_msg != NULL}
<a href="{$link}">{$link_msg}</a>
{/if}
</div>
<script type="text/javascript">
$(document).ready(function() {
    show_alert("{$msg|escape:javascript}", function() { hide_overlay('{$closelink}'); });
});
</script>
{* 
    {if isset($__message) && is_array($__message) && count($__message) > 0 }

<div id="overlay">
<div id="message">
<div class="closebutton buttonlike noborder fixedright down5" id="close_link"></div>
<div class="set_title centered">{$LN_fatal_error_title}</div>
<div id="hideoverlay"></div>

<div id="messagecontent" class="light">
{foreach $__message as $m}
{$m} <br>
{/foreach}
</div>
</div>
</div>
{/if}
    *}

{/block}
{if $showmenu != 0} 
{/if}
