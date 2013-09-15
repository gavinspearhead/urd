<?php /* Smarty version Smarty-3.1.14, created on 2013-09-01 00:28:14
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_editcategories.tpl" */ ?>
<?php /*%%SmartyHeaderCode:49995522952226dfe9717c6-19077316%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a5d90442c5ddc309aa93917df431ab49dde81b81' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_editcategories.tpl',
      1 => 1366653088,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '49995522952226dfe9717c6-19077316',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'LN_editcategories' => 0,
    'LN_category' => 0,
    'LN_newcategory' => 0,
    'categories' => 0,
    'item' => 0,
    'LN_name' => 0,
    'text_box_size' => 0,
    'LN_apply' => 0,
    'LN_delete' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52226dfea10ff4_92547487',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52226dfea10ff4_92547487')) {function content_52226dfea10ff4_92547487($_smarty_tpl) {?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['LN_editcategories']->value;?>
 </div>

<div class="light">

<br/>
<table>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_category']->value;?>
:</td>
<td>
<input type="hidden" name="cat_id" id="cat_id" value="new"/>
<select name="category" id="category_id" onchange="javascript:get_category_name();">
    <option value="new"><?php echo $_smarty_tpl->tpl_vars['LN_newcategory']->value;?>
</option>
    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>		
    <option value="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
	<?php } ?>
    </select>
</td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_name']->value;?>
:</td><td><input type="text" name="cat_name" id="cat_name" value="" size="<?php echo $_smarty_tpl->tpl_vars['text_box_size']->value;?>
"/></td></tr>
<tr><td colspan="2" class="centered"><br/>
<input type="button" name="add" value="<?php echo $_smarty_tpl->tpl_vars['LN_apply']->value;?>
" onclick="javascript:update_category();" class="submit"/>
<input type="button" name="delete" value="<?php echo $_smarty_tpl->tpl_vars['LN_delete']->value;?>
" class="submit" onclick="javascript:delete_category();" />
</td>
</tr>

</table>
</div>
</div>
<?php }} ?>