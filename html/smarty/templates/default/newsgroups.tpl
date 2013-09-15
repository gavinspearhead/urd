{* Smarty *}{*
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
 * $LastChangedDate: 2013-04-28 00:55:09 +0200 (zo, 28 apr 2013) $
 * $Rev: 2823 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: newsgroups.tpl 2823 2013-04-27 22:55:09Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}

{capture assign=submit}{strip}
<span class="ng_submit">
<input type="button" value="{$LN_apply}" class="submit" id="ng_apply" name='apply' onclick="javascript:group_update();"/>&nbsp;
</span>
{/strip}{/capture}
<div>
<br/>

<input type="text" name="search" value="{if $search == ''}&lt;{$LN_search}&gt;{else}{$search|escape:htmlall}{/if}" onfocus="if (this.value=='&lt;{$LN_search}&gt;') this.value='';" id="newsearch" size="30" onkeypress="javascript:submit_enter(event, load_groups);"/>
{urd_checkbox value="$search_all" name="search_all" id="search_all" data="$LN_ng_hide_empty"}
<input type="button" value="{$LN_search}" class="submitsmall" onclick="javascript:load_groups();"/>
<div class="floatright submit_button_right">{$submit}</div>

</div>
<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>
<h3 class="centered">{$LN_loading}</h3>
<br/>
</div>

<div id="groupsdiv">
</div>

<p>&nbsp;</p>
<script type="text/javascript">
$(document).ready(function() {
    load_groups();
});
</script>

<input type="hidden" name="type" id="type" value="groups"/>
<input type="hidden" id="ln_saved" value="{$LN_pref_saved}"/>
<input type="hidden" id="ln_failed" value="{$LN_failed}"/>


{include file="foot.tpl"}
