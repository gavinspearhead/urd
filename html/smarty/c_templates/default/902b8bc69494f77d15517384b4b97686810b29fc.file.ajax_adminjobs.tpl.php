<?php /* Smarty version Smarty-3.1.14, created on 2013-08-30 23:40:52
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_adminjobs.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20905628645200218fb67b05-46285572%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '902b8bc69494f77d15517384b4b97686810b29fc' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_adminjobs.tpl',
      1 => 1377643682,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20905628645200218fb67b05-46285572',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5200218fc684c8_62829748',
  'variables' => 
  array (
    'sort' => 0,
    'sort_dir' => 0,
    'up' => 0,
    'down' => 0,
    'LN_jobs_command' => 0,
    'command_sort' => 0,
    'LN_jobs_user' => 0,
    'username_sort' => 0,
    'LN_time' => 0,
    'at_time_sort' => 0,
    'LN_jobs_period' => 0,
    'interval_sort' => 0,
    'LN_actions' => 0,
    'alljobs' => 0,
    'job' => 0,
    'urdd_online' => 0,
    'LN_cancel' => 0,
    'LN_error_nojobsfound' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5200218fc684c8_62829748')) {function content_5200218fc684c8_62829748($_smarty_tpl) {?>


<?php $_smarty_tpl->tpl_vars['up'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_up.png' alt=''>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['down'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_down.png' alt=''>", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="command") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['command_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['command_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['command_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="username") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['username_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['username_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['username_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="at_time") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['at_time_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['at_time_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['at_time_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="interval") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['interval_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['interval_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['interval_sort'] = new Smarty_variable('', null, 0);?><?php }?>

<input type="hidden" name="order" id="order" value="<?php echo $_smarty_tpl->tpl_vars['sort']->value;?>
"/>
<input type="hidden" name="order_dir" id="order_dir" value="<?php echo $_smarty_tpl->tpl_vars['sort_dir']->value;?>
"/>

<table class="tasks">
<tr>
<th id="descr_td" onclick="javascript:submit_search_jobs('command', 'asc');" class="buttonlike head round_left"><?php echo $_smarty_tpl->tpl_vars['LN_jobs_command']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['command_sort']->value;?>
</th>
<th onclick="javascript:submit_search_jobs('username', 'asc');" class="fixwidth8c buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_jobs_user']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['username_sort']->value;?>
</th>
<th onclick="javascript:submit_search_jobs('at_time', 'asc');" class="fixwidth9 buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_time']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['at_time_sort']->value;?>
</th>
<th onclick="javascript:submit_search_jobs('interval', 'asc');" class="fixwidth5 buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_jobs_period']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['interval_sort']->value;?>
</th>
<th class="fixwidth5 round_right head"><?php echo $_smarty_tpl->tpl_vars['LN_actions']->value;?>
</th>
</tr>

<?php  $_smarty_tpl->tpl_vars['job'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['job']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['alljobs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['job']->key => $_smarty_tpl->tpl_vars['job']->value) {
$_smarty_tpl->tpl_vars['job']->_loop = true;
?>
<tr class="even content" onmouseover="javascript:ToggleClass(this,'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');">
<td><div class="donotoverflowdamnit"><?php echo $_smarty_tpl->tpl_vars['job']->value['task'];?>
 <b><?php echo $_smarty_tpl->tpl_vars['job']->value['arg'];?>
</b></div></td>
<td><?php echo $_smarty_tpl->tpl_vars['job']->value['user'];?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['job']->value['time'];?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['job']->value['period'];?>
</td>
<td>
<?php if ($_smarty_tpl->tpl_vars['urdd_online']->value!=0) {?>
    <div class="floatright iconsize killicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_cancel']->value),$_smarty_tpl);?>
  onclick="javascript:job_action('unschedule', '<?php echo strtr($_smarty_tpl->tpl_vars['job']->value['cmd'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');"></div>
<?php } else { ?>&nbsp;<?php }?>
</td>
</tr>
<?php }
if (!$_smarty_tpl->tpl_vars['job']->_loop) {
?>
<tr><td colspan="5" class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_nojobsfound']->value;?>
</td></tr>
<?php } ?>
</table>

<?php }} ?>