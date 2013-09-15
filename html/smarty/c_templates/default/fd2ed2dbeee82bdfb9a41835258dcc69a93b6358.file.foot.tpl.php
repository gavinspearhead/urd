<?php /* Smarty version Smarty-3.1.14, created on 2013-08-06 00:00:21
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/foot.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1954058232520020758d3942-96076551%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fd2ed2dbeee82bdfb9a41835258dcc69a93b6358' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/foot.tpl',
      1 => 1375738690,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1954058232520020758d3942-96076551',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '__show_time' => 0,
    'time_b' => 0,
    'time_a' => 0,
    'VERSION' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520020758f8a61_77945120',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520020758f8a61_77945120')) {function content_520020758f8a61_77945120($_smarty_tpl) {?><?php if (!is_callable('smarty_block_php')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/block.php.php';
if (!is_callable('smarty_function_math')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.math.php';
?><?php if ($_smarty_tpl->tpl_vars['__show_time']->value!='') {?><div class="xxsmall right"><?php $_smarty_tpl->_capture_stack[0][] = array('default', 'time_b', null); ob_start(); ?><?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_block_php(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
echo microtime(true);<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_php(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php echo smarty_function_math(array('equation'=>"x-y",'x'=>$_smarty_tpl->tpl_vars['time_b']->value,'y'=>$_smarty_tpl->tpl_vars['time_a']->value,'format'=>"%.4f"),$_smarty_tpl);?>
s</div></div><?php }?></div></div><script type="text/javascript">update_quick_status();update_disk_status();</script></div>
</body>
<!-- URD v<?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
 -->
</html>
<?php }} ?>