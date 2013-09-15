<?php /* Smarty version Smarty-3.1.14, created on 2013-08-10 22:57:04
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_control.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18465586115206a920787577-54484836%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '962554d9e3ef3f8f7f7284f2ea97b1e207a08f76' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_control.tpl',
      1 => 1367103342,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18465586115206a920787577-54484836',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_control_title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5206a920879a73_81509771',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206a920879a73_81509771')) {function content_5206a920879a73_81509771($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<div id="controlcontent">
<div class="urdlogo2 floatright noborder buttonlike" onclick="javascript:jump('index.php');"></div>
<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_control_title']->value;?>
</h3>
<div id="controldiv" class="minsize100">
</div>

<script type="text/javascript">
$(document).ready(function() {
    update_control();
});
</script>

</div>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>