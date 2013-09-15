<?php /* Smarty version Smarty-3.1.14, created on 2013-08-26 22:38:56
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_tasks.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19765944885205684528d534-02773308%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0a4f187f6cdc9f7fbf099ece51873638c810b5cc' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_tasks.tpl',
      1 => 1377294146,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19765944885205684528d534-02773308',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056845316487_96131087',
  'variables' => 
  array (
    'title' => 0,
    'LN_tasks_title' => 0,
    'allstatus' => 0,
    'alltimes' => 0,
    'LN_search' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056845316487_96131087')) {function content_52056845316487_96131087($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.html_options.php';
?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_tasks_title']->value;?>
</h3>

<select name="status" id="status_select" size="1">
<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['allstatus']->value),$_smarty_tpl);?>

</select>
<select name="time" id="time_select" size="1">
<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['alltimes']->value),$_smarty_tpl);?>

</select>

<input type="text" name="_search" value="&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;" id="tasksearch" onclick="javascript:clean_input('tasksearch', 'search')" onkeypress="javascript:submit_enter(event, load_tasks_no_offset);"/>
<input type="button" onclick="javascript:load_tasks_no_offset(null, null);" id="search" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="submitsmall"/>

<div id="tasksdiv">
</div>

<script type="text/javascript">
$(document).ready(function() {
    update_tasks();
});
</script>

<p>&nbsp;</p>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>