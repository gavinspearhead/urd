<?php /* Smarty version Smarty-3.1.14, created on 2013-08-18 00:06:15
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_savename.tpl" */ ?>
<?php /*%%SmartyHeaderCode:574686466520ff24edc7744-01457172%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '125bdbf1487e263e574b3d43c137fd7eeaa3cf47' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_savename.tpl',
      1 => 1376777050,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '574686466520ff24edc7744-01457172',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520ff24ee8e6d8_06659628',
  'variables' => 
  array (
    'LN_add_search' => 0,
    'LN_save_search_as' => 0,
    'name' => 0,
    'categories_count' => 0,
    'LN_category' => 0,
    'categories' => 0,
    'item' => 0,
    'save_category' => 0,
    'LN_apply' => 0,
    'usersettype' => 0,
    'USERSETTYPE_SPOT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520ff24ee8e6d8_06659628')) {function content_520ff24ee8e6d8_06659628($_smarty_tpl) {?>
<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div><div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['LN_add_search']->value;?>
</div><div id="savename_content"><?php echo $_smarty_tpl->tpl_vars['LN_save_search_as']->value;?>
:<br/><input type="text" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['name']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" id="savename_val"/></p><div <?php if ($_smarty_tpl->tpl_vars['categories_count']->value==0) {?> class="hidden"<?php }?>><?php echo $_smarty_tpl->tpl_vars['LN_category']->value;?>
:<br/><select name="category" id="category_id"><option value=""></option><?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['item']->value['id']==$_smarty_tpl->tpl_vars['save_category']->value) {?>selected="selected"<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option><?php } ?></select></div><div class="centered"><br/><input type="button" class="submitsmall" value="<?php echo $_smarty_tpl->tpl_vars['LN_apply']->value;?>
" onclick="javascript:<?php if ($_smarty_tpl->tpl_vars['usersettype']->value==$_smarty_tpl->tpl_vars['USERSETTYPE_SPOT']->value) {?>save_spot_search();<?php } else { ?>save_browse_search();<?php }?>"/></div></div>
<?php }} ?>