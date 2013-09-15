<?php /* Smarty version Smarty-3.1.14, created on 2013-09-10 23:52:49
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_edit_searchoptions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2725439575206b567d558b0-97450698%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4e99987ef8fb6d93183bc5690b4587b2dc321b66' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_edit_searchoptions.tpl',
      1 => 1378849967,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2725439575206b567d558b0-97450698',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5206b567e29122_83475988',
  'variables' => 
  array (
    'id' => 0,
    'LN_buttons_addbutton' => 0,
    'LN_buttons_editbutton' => 0,
    'LN_name' => 0,
    'button' => 0,
    'text_box_size' => 0,
    'LN_buttons_url' => 0,
    'LN_add' => 0,
    'LN_apply' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206b567e29122_83475988')) {function content_5206b567e29122_83475988($_smarty_tpl) {?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php if ($_smarty_tpl->tpl_vars['id']->value=='new') {?><?php echo $_smarty_tpl->tpl_vars['LN_buttons_addbutton']->value;?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['LN_buttons_editbutton']->value;?>
<?php }?> </div>
<div class="light">
<br/>
<br/>
<input type="hidden" name="id" id="id" value="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"/>

<table class="hmid">
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_name']->value;?>
:</td>
<td><input type="text" name="name" id="name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['button']->value->get_name(), ENT_QUOTES, 'UTF-8', true);?>
" size="<?php echo $_smarty_tpl->tpl_vars['text_box_size']->value;?>
"/></td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_buttons_url']->value;?>
:</td>
<td><input type="text" name="search_url" id="search_url" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['button']->value->get_url(), ENT_QUOTES, 'UTF-8', true);?>
" size="<?php echo $_smarty_tpl->tpl_vars['text_box_size']->value;?>
"/></td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" class="centered">
<?php if ($_smarty_tpl->tpl_vars['id']->value=='new') {?>
	<input type="button" name="add" value="<?php echo $_smarty_tpl->tpl_vars['LN_add']->value;?>
" class="submit" onclick="javascript:update_buttons();"/>
<?php } else { ?>
	<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_apply']->value;?>
" name="apply" class="submit" onclick="javascript:update_buttons();"/>
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['button']->value->get_id();?>
"/>
<?php }?>
</td>
</tr>
</table>
</div>
<?php }} ?>