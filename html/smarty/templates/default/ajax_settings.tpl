{* Smarty *}{*
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
 * $LastChangedDate: 2014-06-22 19:54:35 +0200 (zo, 22 jun 2014) $
 * $Rev: 3109 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_settings.tpl 3109 2014-06-22 17:54:35Z gavinspearhead@gmail.com $
 *}
{* used for both prefs and admin_config !! *}

{capture assign=selector}{strip}
<div class="pref_selector">

<div class="tabs">
{foreach $pref_list as $atab}
{if $atab->length > 0}
<span id="{$atab->tabname|replace:' ':''}_bar" name="a_tab">
<span id="{$atab->tabname|replace:' ':''}_bar_elem" onclick="javascript:select_tab_setting('{$atab->tabname|escape:javascript|replace:' ':''}')" class="tab{if ($current_tab == '' and $atab@first) or $current_tab==$atab->tabname} tab_selected{/if}" >{$atab->name}
<input type="hidden" name="tabs" value="{$atab->tabname|replace:' ':''}"/>
</span>
</span>
{/if}
{/foreach}
</div>
</div>
{/strip}
{/capture}

{$selector}
<div class="prefix_prefs">
<input type="hidden" id="current_tab" name="current_tab" value="{$current_tab|escape}"/>
<input type="hidden" name="challenge" value="{$challenge|escape}"/>
<input type="hidden" name="current_pref_level" id="current_pref_level"/>
<input type="hidden" value="" name="cmd" id="submittype"/>
</div>

{foreach $pref_list as $pref}
{assign var="ct" value="{$current_tab}_tab"}
<table class="preferences{if not ($current_tab == '' and $pref@first) and $current_tab != $pref->tabname} hidden{/if}" id="{$pref->tabname|replace:' ':''}_tab">
<thead>
<tr>
<th colspan="2" class="head round_both">&nbsp;</th>
</tr>
</thead>
{$blocks=$pref->value}
{$length=0}
{foreach $blocks as $block}
{$b_type=$block->get_type()}
{if $block->level > $level}
    {$hidden="hidden"}
{else}
    {$length=1}
    {$hidden=""}
{/if}
<tr name="content" class="{$hidden} even content {if $block->tr_class != ''} {$block->tr_class} {/if}" 
        {if $block->tr_id != ''}id="{$block->tr_id}" {/if}
>
{$popup_text=$block->popup|escape}
<td class="settings vtop"{if $popup_text!=''}{urd_popup text=$popup_text}{/if}>
{if $b_type == "custom_text"}
{$block->text}: 
<input type="text" name="custom_{$block->id}_name" id="custom_{$block->id}_name" value="{if $block->name != '__new'}{$block->name|escape:htmlall}{/if}" size="{$block->size}" onchange="javascript:update_setting('{$block->id|escape:javascript}', '{$b_type|escape:javascript}', { 'original_name': '{$block->id}', 'source':'name', 'fn':'load_prefs();' } );"/>
{else}
    {if {$block->text} != ''}
        {$block->text}:
    {/if}
{/if}
</td>
<td>
{if $b_type == "plain"} 
	<b>{$block->value|escape}</b>
{/if}
{if $b_type == "custom_text"}
    <input type="text" name="custom_{$block->id}_value" id="custom_{$block->id}_value" value="{$block->value|escape:htmlall}" size="{$block->size}" onchange="javascript:update_setting('{$block->id|escape:javascript}', '{$b_type|escape:javascript}', { 'original_name': '{$block->id}', 'source':'value', 'fn':'load_prefs();' } );"/>
    {if $block->id != '__new'}
        <div id="{$name}_collapse" class="floatright iconsize deleteicon noborder buttonlike" onclick="javascript:delete_setting('{$block->id|escape:javascript}');" {urd_popup type="small" text="$LN_delete"} >
    {/if}
{/if}
{if $b_type == "password"}
	<input type="password" name="{$block->name}" id="{$block->id}" value="{$block->value|escape:htmlall}" size="{$block->size}" {$block->javascript} /> 
    <div class="floatright iconsizeplus sadicon buttonlike" onclick="javascript:toggle_show_password('{$block->name|escape:javascript}');"></div>
    <span id="pw_message_{$block->name}" class="italic"></span>
{/if}
{if $b_type == "password_submit"}
<input type="button" id="{$block->id}" value="{$block->value|escape}"/>
    <span id="pwweak" class="hidden italic"><br>{$LN_password_weak}</span>
    <span id="pwmedium" class="hidden italic"><br>{$LN_password_medium}</span>
    <span id="pwstrong" class="hidden italic"><br>{$LN_password_strong}</span>
    <span id="pwcorrect" class="hidden italic"><br>{$LN_password_correct}</span>
    <span id="pwincorrect" class="hidden italic"><br>{$LN_password_incorrect}</span>
<script>
handle_passwords_change('{$block->opw_id|escape:javascript}', '{$block->npw_id1|escape:javascript}', '{$block->npw_id2|escape:javascript}','{$block->tr_id|escape:javascript}', '{$block->username|escape:javascript}');
</script>
{/if}
{if $b_type == "text"}
	<input type="text" name="{$block->name}" id="{$block->id}" 
    value="{$block->value|escape:htmlall}" 
    size="{$block->size}" 
    {$block->javascript} 
    onchange="javascript:update_setting('{$block->id|escape:javascript}', '{$b_type|escape:javascript}');"/>
{/if}
{if $b_type == "email"}
	<input type="email" name="{$block->name}" id="{$block->id}" 
    value="{$block->value|escape:htmlall}" 
    size="{$block->size}" 
    {$block->javascript} 
    onchange="javascript:update_setting('{$block->id|escape:javascript}', '{$b_type|escape:javascript}');"/>
{/if}

{if $b_type == "checkbox"}
    {urd_checkbox value="{$block->toggle}" name="{$block->name}" id="{$block->id}" post_js="update_setting('{$block->id|escape:javascript}', '{$b_type|escape:javascript}'); {$block->javascript}"}
{/if}
{if $b_type == "textarea"}{strip}
    {$name=$block->id}
    <input type="hidden" id="{$name}_orig_size" value="{$block->rows}"/>
    <textarea name="{$block->name}" id="{$name}" rows="2" cols="{$block->cols}" {$block->javascript}
    onchange="javascript:update_setting('{$block->id|escape:javascript}', '{$b_type|escape:javascript}');"
    >{$block->value|escape:htmlall} 
    </textarea>{/strip}
    <div id="{$name}_collapse" class="floatright iconsize dynimgplus noborder buttonlike" onclick="javascript:collapse_select('{$name}','rows');" {urd_popup type="small" text="$LN_expand"} >
    </div>
{/if}
{if $b_type == "select"}
{$opts=$block->options}
{$js=$block->javascript|escape:javascript}
<select name="{$block->name}" id="{$block->id}" 
onchange="javascript:update_setting('{$block->id|escape:javascript}', '{$b_type|escape:javascript}' {if $js!=''}, { 'fn':'{$js}' } {/if});">
{foreach $opts as $k => $q}
<option value="{$k|escape:all}"{if $k == $block->selected } selected="selected"{/if}>{$q}</option>
{/foreach}
</select>
{/if}
{if $b_type == "button"}
	<input type="button" class="submitsmall" name="{$block->name}" value="{$block->value|escape}" {$block->javascript} />
{/if}
{if $b_type == "multiselect"}
    {$name=$block->id}
    <input type="hidden" id="{$name}_orig_size" value="{$block->size}"/>
    <select name="{$block->name}" id="{$name}" size="2" multiple="multiple" {$block->javascript} 
    onchange="javascript:update_setting('{$name|escape:javascript}', '{$b_type|escape:javascript}');"
    >
    {$opts=$block->options_triple}
    {foreach $opts as $q}
        <option value="{$q.id}" {if $q.on == 1} selected="selected" {/if} >{$q.name}</option>
    {/foreach}
    </select>
    <div id="{$name}_collapse" class="floatright iconsize dynimgplus noborder buttonlike" onclick="javascript:collapse_select('{$name}','size');" {urd_popup type="small" text="$LN_expand"|capitalize} >
    </div>
{/if}
{if $b_type == "period"}
    {$name=$block->id}
    <select name="{$block->period_name}" size="1" id="{$block->period_name}" class="update" {$block->javascript}
    onchange="javascript:update_setting('{$block->period_name|escape:javascript}', '{$b_type|escape:javascript}', { 'time1':'{$block->time1_name}', 'time2':'{$block->time2_name}' {if $block->extra_name != ""} , 'extra':'{$block->extra_name}' {/if} } );"
    >
    {html_options values=$block->period_keys output=$block->period_texts selected=$block->period_selected } 
    </select> @
    <input type="text" id="{$block->time1_name}" name="{$block->time1_name}" id="{$block->time1_name}"  {if $block->time1_value ge 0}value="{$block->time1_value}"{/if} class="time" 
    onchange="javascript:update_setting('{$block->period_name|escape:javascript}', '{$b_type|escape:javascript}', { 'time1':'{$block->time1_name}', 'time2':'{$block->time2_name}' {if $block->extra_name != ""} , 'extra':'{$block->extra_name}' {/if} }  );"
    />:
    <input type="text" id="{$block->time2_name}" name="{$block->time2_name}" id="{$block->time2_name}" {if $block->time2_value !== ''}value="{$block->time2_value|string_format:"%02d"}"{/if} class="time" 
    onchange="javascript:update_setting('{$block->period_name|escape:javascript}', '{$b_type|escape:javascript}', { 'time1':'{$block->time1_name}', 'time2':'{$block->time2_name}' {if $block->extra_name != ""} , 'extra':'{$block->extra_name}' {/if} }  );"
    />
    {if $block->extra_name != ""}
    <select name="{$block->extra_name}" size="1", id="{$block->extra_name}"
    onchange="javascript:update_setting('{$block->period_name|escape:javascript}', '{$b_type|escape:javascript}', { 'time1':'{$block->time1_name}', 'time2':'{$block->time2_name}' {if $block->extra_name != ""} , 'extra':'{$block->extra_name}' {/if} } );" >
        {html_options options=$block->extra_options selected=$block->extra_selected }
    </select>
    {/if}
{/if}
{if $block->error_msg.msg != " " && $block->error_msg.msg != ""}
<img src="{$IMGDIR}/stop_mark.png" id="stop_mark_{$block->id}" {urd_popup text=$block->error_msg.msg|escape:javascript|escape:htmlall caption=$LN_error_error } alt="{$LN_error_error}" class="noborder"/>
{/if}
</td>
</tr>
{/foreach}
<tr><td colspan="2" class="feet round_both_bottom">&nbsp;</td></tr>
</table>
{if $length == 0}
<script type="text/javascript">
$(document).ready(function() {
    $('#' + '{$pref->tabname|replace:' ':''}_bar').addClass('hidden');
}) ;
</script>
{/if}

{/foreach}
<div><br/></div>
<p>&nbsp;</p>

