<?php /* Smarty version Smarty-3.1.14, created on 2013-09-03 22:50:43
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_admintasks.tpl" */ ?>
<?php /*%%SmartyHeaderCode:608224499520568462a4ab6-63875518%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1df917041e25df64102227066067047172077b14' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_admintasks.tpl',
      1 => 1378156929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '608224499520568462a4ab6-63875518',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520568466ee373_44590136',
  'variables' => 
  array (
    'pages' => 0,
    'currentpage' => 0,
    'lastpage' => 0,
    'sort' => 0,
    'sort_dir' => 0,
    'up' => 0,
    'down' => 0,
    'offset' => 0,
    'topskipper' => 0,
    'LN_tasks_description' => 0,
    'description_sort' => 0,
    'LN_tasks_progress' => 0,
    'progress_sort' => 0,
    'LN_eta' => 0,
    'ETA_sort' => 0,
    'LN_status' => 0,
    'status_sort' => 0,
    'LN_tasks_added' => 0,
    'starttime_sort' => 0,
    'LN_tasks_lastupdated' => 0,
    'lastupdate_sort' => 0,
    'LN_tasks_comment' => 0,
    'comment_sort' => 0,
    'LN_actions' => 0,
    'alltasks' => 0,
    'task' => 0,
    'urdd_online' => 0,
    'LN_cancel' => 0,
    'LN_transfers_linkstart' => 0,
    'LN_pause' => 0,
    'LN_delete' => 0,
    'LN_error_notasksfound' => 0,
    'bottomskipper' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520568466ee373_44590136')) {function content_520568466ee373_44590136($_smarty_tpl) {?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'topskipper', null); ob_start(); ?><?php if (count($_smarty_tpl->tpl_vars['pages']->value)>1) {?><?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'ps','js'=>'tasks_offset','extra_class'=>"margin10"),$_smarty_tpl);?>
<?php } else { ?><br/><?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'bottomskipper', null); ob_start(); ?><?php if (count($_smarty_tpl->tpl_vars['pages']->value)>1) {?><?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'psb','js'=>'tasks_offset','extra_class'=>"margin10"),$_smarty_tpl);?>
<?php } else { ?><br/><?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->tpl_vars['up'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_up.png' alt=''>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['down'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_down.png' alt=''>", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="description") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['description_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['description_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['description_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="progress") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['progress_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['progress_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['progress_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="ETA") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['ETA_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['ETA_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['ETA_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="status") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['status_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['status_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['status_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="starttime") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['starttime_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['starttime_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['starttime_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="lastupdate") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['lastupdate_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['lastupdate_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['lastupdate_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="comment") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['comment_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['comment_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['comment_sort'] = new Smarty_variable('', null, 0);?><?php }?>


<input type="hidden" name="offset" id="offset" value="<?php echo $_smarty_tpl->tpl_vars['offset']->value;?>
"/>
<input type="hidden" name="order" id="order" value="<?php echo $_smarty_tpl->tpl_vars['sort']->value;?>
"/>
<input type="hidden" name="order_dir" id="order_dir" value="<?php echo $_smarty_tpl->tpl_vars['sort_dir']->value;?>
"/>
<?php echo $_smarty_tpl->tpl_vars['topskipper']->value;?>

<table class="tasks">
<tr>
<th id="descr_td" onclick="javascript:submit_search_tasks('description', 'asc');" class="buttonlike head round_left"><?php echo $_smarty_tpl->tpl_vars['LN_tasks_description']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['description_sort']->value;?>
</th>
<th onclick="javascript:submit_search_tasks('progress', 'asc');" class="fixwidth5 buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_tasks_progress']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['progress_sort']->value;?>
</th>
<th onclick="javascript:submit_search_tasks('ETA', 'asc');" class="fixwidth6 buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_eta']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['ETA_sort']->value;?>
</th>
<th onclick="javascript:submit_search_tasks('status', 'asc');" class="fixwidth5c buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_status']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['status_sort']->value;?>
</th>
<th onclick="javascript:submit_search_tasks('starttime', 'asc');" class="fixwidth9 buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_tasks_added']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['starttime_sort']->value;?>
</th>
<th onclick="javascript:submit_search_tasks('lastupdate', 'asc');" class="fixwidth9 buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_tasks_lastupdated']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['lastupdate_sort']->value;?>
</th>
<th id="comment_td" onclick="javascript:submit_search_tasks('comment', 'asc');" class="buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_tasks_comment']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['comment_sort']->value;?>
</th>
<th class="fixwidth5 right head round_right"><?php echo $_smarty_tpl->tpl_vars['LN_actions']->value;?>
</th>
</tr>

<?php  $_smarty_tpl->tpl_vars['task'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['task']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['alltasks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['task']->key => $_smarty_tpl->tpl_vars['task']->value) {
$_smarty_tpl->tpl_vars['task']->_loop = true;
?>
<tr class="even content" onmouseover="javascript:ToggleClass(this,'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');">
<td><div class="donotoverflowdamnit" <?php echo smarty_function_urd_popup(array('text'=>((string) $_smarty_tpl->tpl_vars['task']->value['description'])." ".((string) $_smarty_tpl->tpl_vars['task']->value['arguments']),'type'=>'small'),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['task']->value['description'];?>
 <b><?php echo $_smarty_tpl->tpl_vars['task']->value['arguments'];?>
</b></div></td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['task']->value['progress'];?>
%</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['task']->value['eta'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['task']->value['status'];?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['task']->value['added'];?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['task']->value['lastupdated'];?>
</td>
<td><div class="donotoverflowdamnit" <?php echo smarty_function_urd_popup(array('text'=>((string) $_smarty_tpl->tpl_vars['task']->value['comment']).".",'type'=>'small'),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['task']->value['comment'];?>
</div></td>
<td>
<?php if ($_smarty_tpl->tpl_vars['urdd_online']->value==1) {?>
<div class="floatright">
<?php if ($_smarty_tpl->tpl_vars['task']->value['raw_status']=='Queued'||$_smarty_tpl->tpl_vars['task']->value['raw_status']=='Paused'||$_smarty_tpl->tpl_vars['task']->value['raw_status']=='Running') {?>
    <div class="inline iconsizeplus killicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_cancel']->value),$_smarty_tpl);?>
 onclick="javascript:task_action('cancel', '<?php echo strtr($_smarty_tpl->tpl_vars['task']->value['urdd_id'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');"></div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['task']->value['raw_status']=='Paused') {?>
    <div class="inline iconsizeplus playicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_linkstart']->value),$_smarty_tpl);?>
 onclick="javascript:task_action('continue', '<?php echo strtr($_smarty_tpl->tpl_vars['task']->value['urdd_id'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');"></div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['task']->value['raw_status']=='Running') {?>
    <div class="inline iconsizeplus pauseicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_pause']->value),$_smarty_tpl);?>
 onclick="javascript:task_action('pause', '<?php echo strtr($_smarty_tpl->tpl_vars['task']->value['urdd_id'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');"></div>
<?php }?>
    <div class="inline iconsizeplus deleteicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_delete']->value),$_smarty_tpl);?>
 onclick="javascript:task_action('cancel', '<?php echo strtr($_smarty_tpl->tpl_vars['task']->value['urdd_id'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
'); task_action('delete_task', '<?php echo strtr($_smarty_tpl->tpl_vars['task']->value['queue_id'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');"></div>
</div>
<?php }?>
</tr>
<?php }
if (!$_smarty_tpl->tpl_vars['task']->_loop) {
?>
<tr><td colspan="8" class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_notasksfound']->value;?>
</td></tr>
<?php } ?>
</table>
<?php echo $_smarty_tpl->tpl_vars['bottomskipper']->value;?>

<?php }} ?>