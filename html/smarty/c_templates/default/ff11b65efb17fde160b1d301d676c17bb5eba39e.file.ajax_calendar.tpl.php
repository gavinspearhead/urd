<?php /* Smarty version Smarty-3.1.14, created on 2013-09-02 22:37:48
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_calendar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18993066325224f71c0d5702-21862513%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ff11b65efb17fde160b1d301d676c17bb5eba39e' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_calendar.tpl',
      1 => 1378152728,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18993066325224f71c0d5702-21862513',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'month' => 0,
    'year' => 0,
    'previous_month' => 0,
    'LN_month_names' => 0,
    'next_month' => 0,
    'LN_short_day_names' => 0,
    'name' => 0,
    'dates' => 0,
    'week' => 0,
    'day' => 0,
    'selected_day' => 0,
    'today' => 0,
    'LN_hour' => 0,
    'LN_minute' => 0,
    'hour' => 0,
    'minute' => 0,
    'LN_atonce' => 0,
    'LN_ok' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5224f71c2c8282_04524032',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5224f71c2c8282_04524032')) {function content_5224f71c2c8282_04524032($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.capitalize.php';
?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button2"></div>
<div class="set_title centered">&nbsp;</div>

<div><br/>
<div id="leftcalendar" class="light">
<table class="centered">
<tr>
<td colspan="7" class="centered">
<span onclick="javascript:show_calendar(<?php echo $_smarty_tpl->tpl_vars['month']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['year']->value;?>
 - 1, 1)" class="buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>smarty_modifier_capitalize(((string) $_smarty_tpl->tpl_vars['LN_previous']->value)." ".((string) $_smarty_tpl->tpl_vars['LN_year']->value))),$_smarty_tpl);?>
>&lt;&lt;</span>
<span onclick="javascript:show_calendar(<?php echo $_smarty_tpl->tpl_vars['previous_month']->value[0];?>
, <?php echo $_smarty_tpl->tpl_vars['previous_month']->value[1];?>
,1 )" class="buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>smarty_modifier_capitalize(((string) $_smarty_tpl->tpl_vars['LN_previous']->value)." ".((string) $_smarty_tpl->tpl_vars['LN_month']->value))),$_smarty_tpl);?>
>&lt;</span> 
<span class="bold"> <?php echo $_smarty_tpl->tpl_vars['LN_month_names']->value[$_smarty_tpl->tpl_vars['month']->value];?>
 <?php echo $_smarty_tpl->tpl_vars['year']->value;?>
</span>
<span class="buttonlike" onclick="javascript:show_calendar(<?php echo $_smarty_tpl->tpl_vars['next_month']->value[0];?>
, <?php echo $_smarty_tpl->tpl_vars['next_month']->value[1];?>
, 1)" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>smarty_modifier_capitalize(((string) $_smarty_tpl->tpl_vars['LN_next']->value)." ".((string) $_smarty_tpl->tpl_vars['LN_month']->value))),$_smarty_tpl);?>
>&gt; </span>
<span class="buttonlike" onclick="javascript:show_calendar(<?php echo $_smarty_tpl->tpl_vars['month']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['year']->value;?>
 + 1),1" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>smarty_modifier_capitalize(((string) $_smarty_tpl->tpl_vars['LN_next']->value)." ".((string) $_smarty_tpl->tpl_vars['LN_year']->value))),$_smarty_tpl);?>
>&gt;&gt;</span>
</td></tr>
<tr>
<?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LN_short_day_names']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value) {
$_smarty_tpl->tpl_vars['name']->_loop = true;
?>
<th class="right"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</th>
<?php } ?>
</tr>
<?php  $_smarty_tpl->tpl_vars['week'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['week']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dates']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['week']->key => $_smarty_tpl->tpl_vars['week']->value) {
$_smarty_tpl->tpl_vars['week']->_loop = true;
?>
<tr>
<?php  $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['week']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['day']->key => $_smarty_tpl->tpl_vars['day']->value) {
$_smarty_tpl->tpl_vars['day']->_loop = true;
?>
<td class="right<?php if ($_smarty_tpl->tpl_vars['day']->value==$_smarty_tpl->tpl_vars['selected_day']->value&&$_smarty_tpl->tpl_vars['selected_day']->value!=0) {?> highlight3<?php }?>" id="day_<?php echo $_smarty_tpl->tpl_vars['day']->value;?>
">
<?php if ($_smarty_tpl->tpl_vars['day']->value!=0) {?>
<span class="buttonlike<?php if ($_smarty_tpl->tpl_vars['today']->value==$_smarty_tpl->tpl_vars['day']->value) {?> highlight<?php }?>" onclick="javascript:select_calendar(<?php echo $_smarty_tpl->tpl_vars['day']->value;?>
);">
<?php echo $_smarty_tpl->tpl_vars['day']->value;?>

</span>
<?php }?>
</td>
<?php } ?>
</tr> 
<?php } ?>
</table>
</div>
<div id="rightcalendar">
<div class="leftward" id="theleft">
<table>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_hour']->value;?>
:</td>
<td><div id="hours" style="width:100px"></div></td>
</tr>
<tr>
<td><div>&nbsp;</div></td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_minute']->value;?>
:</td>
<td><div id="minutes" style="width:100px"></div></td>
</tr>
<tr>
<td>
<div>
<input name="time" id="time1" type="text" value="<?php echo $_smarty_tpl->tpl_vars['hour']->value;?>
:<?php echo $_smarty_tpl->tpl_vars['minute']->value;?>
" size="5"/>
</div></td>
</tr>
</table>
</div>
</div>
</div>
<br/>
<br/>
<br/>
<div class="right" id="calendarbottom">
<input class="submit" type="button" name="submit_no_delay" value="<?php echo $_smarty_tpl->tpl_vars['LN_atonce']->value;?>
" onclick="javascript:submit_calendar('atonce');"/>
<input class="submit" type="button" name="submit" value="<?php echo $_smarty_tpl->tpl_vars['LN_ok']->value;?>
" onclick="javascript:submit_calendar();"/>
</div>
<input type="hidden" id="month" value="<?php echo $_smarty_tpl->tpl_vars['month']->value;?>
"/>
<input type="hidden" id="year" value="<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
"/>
<input type="hidden" id="day" value="<?php echo $_smarty_tpl->tpl_vars['selected_day']->value;?>
"/>
<input type="hidden" id="hour" value="<?php echo $_smarty_tpl->tpl_vars['hour']->value;?>
"/>
<input type="hidden" id="minute" value="<?php echo $_smarty_tpl->tpl_vars['minute']->value;?>
"/>
<input name="date" id="date1" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
-<?php echo $_smarty_tpl->tpl_vars['month']->value;?>
-<?php echo $_smarty_tpl->tpl_vars['selected_day']->value;?>
"/>
<?php }} ?>