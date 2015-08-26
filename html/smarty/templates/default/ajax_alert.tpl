{* Smarty *}{*
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
 * $LastChangedDate: 2011-01-15 01:03:01 +0100 (Sat, 15 Jan 2011) $
 * $Rev: 2027 $
 * $Author: gavinspearhead $
 * $Id: browse.tpl 2027 2011-01-15 00:03:01Z gavinspearhead $
 *}
<div class="set_title head centered">{$LN_fatal_error_title}</div>
<div id="alert_inner">
    <div id="alert_content">
        <div id="alert_message">{$msg}</div>
        <br/>
        <div id="alert_answer" class="centered">
            <input type="button" id="okbutton" value="&nbsp;{$LN_ok}&nbsp;" class="submitsmall"/>&nbsp;
{if $allow_cancel}
            <input type="button" id="cancelbutton" value="&nbsp;{$LN_cancel}&nbsp;" class="submitsmall"/>
{/if}
        </div>
    </div>
</div>

