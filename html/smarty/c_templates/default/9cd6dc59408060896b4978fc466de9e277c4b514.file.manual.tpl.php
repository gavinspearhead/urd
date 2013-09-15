<?php /* Smarty version Smarty-3.1.14, created on 2013-08-09 22:46:39
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/manual.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11962938375205552fc41ca1-76498644%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9cd6dc59408060896b4978fc466de9e277c4b514' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/manual.tpl',
      1 => 1342733936,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11962938375205552fc41ca1-76498644',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_manual_content' => 0,
    'i' => 0,
    'a' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5205552fdb77c2_59171166',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5205552fdb77c2_59171166')) {function content_5205552fdb77c2_59171166($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<div id="textcontent">
<div class="urdlogo2 floatright noborder buttonlike" onclick="javascript:jump('index.php');"></div>
<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h3>

<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable("1", null, 0);?>
<?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['a']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LN_manual_content']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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