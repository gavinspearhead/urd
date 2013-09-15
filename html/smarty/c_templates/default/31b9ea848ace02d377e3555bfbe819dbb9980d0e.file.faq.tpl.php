<?php /* Smarty version Smarty-3.1.14, created on 2013-08-10 00:37:21
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/faq.tpl" */ ?>
<?php /*%%SmartyHeaderCode:116699900052056f212cf1c8-41828206%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '31b9ea848ace02d377e3555bfbe819dbb9980d0e' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/faq.tpl',
      1 => 1342733936,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '116699900052056f212cf1c8-41828206',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_faq_title' => 0,
    'LN_faq_content' => 0,
    'i' => 0,
    'a' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056f21372a15_69422450',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056f21372a15_69422450')) {function content_52056f21372a15_69422450($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<div id="textcontent">
<div class="urdlogo2 floatright noborder buttonlike" onclick="javascript:jump('index.php');"></div>
<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_faq_title']->value;?>
</h3>

<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable("1", null, 0);?>
<?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['a']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LN_faq_content']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value) {
$_smarty_tpl->tpl_vars['a']->_loop = true;
?>
<h3><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
. <?php echo $_smarty_tpl->tpl_vars['a']->value[0];?>
</h3>
<?php echo $_smarty_tpl->tpl_vars['a']->value[1];?>

<br/>
<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(((string) $_smarty_tpl->tpl_vars['i']->value+1), null, 0);?>
<?php } ?>

</div>
<br/>
<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>