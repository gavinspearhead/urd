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
 * $LastChangedDate: 2012-09-30 00:55:22 +0200 (zo, 30 sep 2012) $
 * $Rev: 2701 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: getfile.tpl 2701 2012-09-29 22:55:22Z gavinspearhead@gmail.com $
 *}
{extends file="popup.tpl"}
{block name=title}{$title|escape:htmlall} ({$size}) {$current + 1}/{$lastidx + 1}{/block}
{block name=contents}

<div class="center down3">
<img src="{$url}" id="overlay_image" alt="" {if $width gt 0}width="{$width}"{/if} {if $height gt 0}height="{$height}"{/if} onclick="javascript:jump('{$url}');" class="buttonlike noborder"/>

<div class="centered">
{if $firstidx != -1}
<div class="firsticon iconsize inline buttonlike" onclick="javascript:show_image('{$first|escape:javascript}', {$firstidx});"></div>
{/if}
{if $previousidx != -1}
<div class="previousicon iconsize inline buttonlike" onclick="javascript:show_image('{$previous|escape:javascript}', {$previousidx});"></div>
{/if}

{if $preview != 1}
<div class="foldericon iconsize inline buttonlike" onclick="hide_overlayed_content();"></div>
{/if}

{if $nextidx != -1}
<div class="playicon iconsize inline buttonlike" onclick="javascript:show_image('{$next|escape:javascript}', {$nextidx});"></div>
{/if}
{if $lastidx != -1}
<div class="lasticon iconsize inline buttonlike" onclick="javascript:show_image('{$last|escape:javascript}', {$lastidx});"></div>
{/if}
</div>
</div>

{/block}
