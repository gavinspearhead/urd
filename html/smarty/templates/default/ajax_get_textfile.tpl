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

<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered" id="text_title">{$title|escape:htmlall} ({$size})</div>

<div class="overflow" id="inner_content">
<pre>
{$output}
</pre>
</div>
</div>

