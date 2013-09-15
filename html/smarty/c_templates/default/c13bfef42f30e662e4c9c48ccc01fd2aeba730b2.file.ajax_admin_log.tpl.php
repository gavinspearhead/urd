<?php /* Smarty version Smarty-3.1.14, created on 2013-09-06 23:57:11
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_admin_log.tpl" */ ?>
<?php /*%%SmartyHeaderCode:233601274521d0ebd0056c3-77460911%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c13bfef42f30e662e4c9c48ccc01fd2aeba730b2' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_admin_log.tpl',
      1 => 1378156929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '233601274521d0ebd0056c3-77460911',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_521d0ebd1cd353_11289463',
  'variables' => 
  array (
    'sort' => 0,
    'sort_dir' => 0,
    'up' => 0,
    'down' => 0,
    'LN_log_date' => 0,
    'date_sort' => 0,
    'LN_time' => 0,
    'time_sort' => 0,
    'LN_log_level' => 0,
    'level_sort' => 0,
    'LN_log_msg' => 0,
    'msg_sort' => 0,
    'logs' => 0,
    'log' => 0,
    'LN_error_nologsfound' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_521d0ebd1cd353_11289463')) {function content_521d0ebd1cd353_11289463($_smarty_tpl) {?>

<?php $_smarty_tpl->tpl_vars['up'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_up.png' alt=''>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['down'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_down.png' alt=''>", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="date") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['date_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['date_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['date_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="time") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['time_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['time_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['time_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="level") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['level_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['level_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['level_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="msg") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['msg_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['msg_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['msg_sort'] = new Smarty_variable('', null, 0);?><?php }?>

<div class="log">
<table class="tasks">
<tr>
<th onclick="submit_sort_log('date')" class="head buttonlike round_left fixwidth6c"><?php echo $_smarty_tpl->tpl_vars['LN_log_date']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['date_sort']->value;?>
</th>
<th onclick="submit_sort_log('time')" class="head buttonlike fixwidth5c"><?php echo $_smarty_tpl->tpl_vars['LN_time']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['time_sort']->value;?>
</th>
<th onclick="submit_sort_log('level')" class="head buttonlike fixwidth6c"><?php echo $_smarty_tpl->tpl_vars['LN_log_level']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['level_sort']->value;?>
</th>
<th onclick="submit_sort_log('msg')" class="head buttonlike round_right"><?php echo $_smarty_tpl->tpl_vars['LN_log_msg']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['msg_sort']->value;?>
</th>
</tr>
<?php  $_smarty_tpl->tpl_vars['log'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['log']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['logs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['log']->key => $_smarty_tpl->tpl_vars['log']->value) {
$_smarty_tpl->tpl_vars['log']->_loop = true;
?>
<tr class="even content" onmouseover="javascript:ToggleClass(this,'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');">
<td><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['log']->value['date'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td>
<td><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['log']->value['time'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td>
<td><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['log']->value['level'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td>
<td><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['log']->value['msg'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td>
</tr>
<?php }
if (!$_smarty_tpl->tpl_vars['log']->_loop) {
?>
<tr><td colspan="4" class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_nologsfound']->value;?>
</td></tr>
<?php } ?>
</table>
</div>
</div>

<?php }} ?>