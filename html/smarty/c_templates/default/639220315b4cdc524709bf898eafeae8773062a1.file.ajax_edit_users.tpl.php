<?php /* Smarty version Smarty-3.1.14, created on 2013-09-10 23:49:22
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_edit_users.tpl" */ ?>
<?php /*%%SmartyHeaderCode:121323156152056b55d92286-11559639%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '639220315b4cdc524709bf898eafeae8773062a1' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_edit_users.tpl',
      1 => 1378849760,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '121323156152056b55d92286-11559639',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056b5605e644_68671278',
  'variables' => 
  array (
    'id' => 0,
    'LN_users_addnew' => 0,
    'LN_users_edit' => 0,
    'LN_username' => 0,
    'name' => 0,
    'text_box_size' => 0,
    'LN_fullname' => 0,
    'fullname' => 0,
    'LN_email' => 0,
    'email' => 0,
    'emailallowed' => 0,
    'LN_password' => 0,
    'LN_users_isadmin' => 0,
    'isadmin' => 0,
    'USER_ADMIN' => 0,
    'LN_users_rights' => 0,
    'LN_users_post' => 0,
    'LN_active' => 0,
    'isactive' => 0,
    'USER_ACTIVE' => 0,
    'LN_users_autodownload' => 0,
    'LN_users_fileedit' => 0,
    'LN_users_allow_erotica' => 0,
    'LN_users_allow_update' => 0,
    'LN_add' => 0,
    'LN_apply' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056b5605e644_68671278')) {function content_52056b5605e644_68671278($_smarty_tpl) {?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php if ($_smarty_tpl->tpl_vars['id']->value=='new') {?><?php echo $_smarty_tpl->tpl_vars['LN_users_addnew']->value;?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['LN_users_edit']->value;?>
<?php }?></div>
<div class="light">
<br/>
<div>
<input type="hidden" name="id" id="id" value="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"/></td>
</div>
<table class="hmid">
<tr><td colspan="2"><?php echo $_smarty_tpl->tpl_vars['LN_username']->value;?>
</td><td colspan="2"><input type="text" name="username" id="username" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['name']->value, ENT_QUOTES, 'UTF-8', true);?>
" size="<?php echo $_smarty_tpl->tpl_vars['text_box_size']->value;?>
"/></td></tr>
<tr><td colspan="2"><?php echo $_smarty_tpl->tpl_vars['LN_fullname']->value;?>
</td><td colspan="2"><input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['fullname']->value, ENT_QUOTES, 'UTF-8', true);?>
" size="<?php echo $_smarty_tpl->tpl_vars['text_box_size']->value;?>
"/></td></tr>
<tr><td colspan="2"><?php echo $_smarty_tpl->tpl_vars['LN_email']->value;?>
</td><td colspan="2"><input type="text" name="email" id="email" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['email']->value, ENT_QUOTES, 'UTF-8', true);?>
" size="<?php echo $_smarty_tpl->tpl_vars['text_box_size']->value;?>
"/></td></tr>
<?php if ($_smarty_tpl->tpl_vars['id']->value=='new'||$_smarty_tpl->tpl_vars['emailallowed']->value==0) {?>
<tr><td colspan="2"><?php echo $_smarty_tpl->tpl_vars['LN_password']->value;?>
</td><td colspan="2"> 
    <input type="text" name="password" id="password" size="<?php echo $_smarty_tpl->tpl_vars['text_box_size']->value;?>
"/>
</td> </tr>

<?php }?>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_users_isadmin']->value;?>
</td><td>
<?php if ($_smarty_tpl->tpl_vars['isadmin']->value==$_smarty_tpl->tpl_vars['USER_ADMIN']->value) {?><?php $_smarty_tpl->tpl_vars['_isadmin'] = new Smarty_variable(1, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['_isadmin'] = new Smarty_variable(0, null, 0);?><?php }?>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['_isadmin']->value),'name'=>"isadmin",'id'=>"isadmin"),$_smarty_tpl);?>


</td>

<td><?php echo $_smarty_tpl->tpl_vars['LN_users_rights']->value;?>
</td><td>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['rights']->value),'name'=>"seteditor",'id'=>"seteditor"),$_smarty_tpl);?>


</td>
</tr>

<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_users_post']->value;?>
</td><td>

<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['post']->value),'name'=>"post",'id'=>"post"),$_smarty_tpl);?>

</td>

<td><?php echo $_smarty_tpl->tpl_vars['LN_active']->value;?>
</td><td>
<?php if ($_smarty_tpl->tpl_vars['isactive']->value==$_smarty_tpl->tpl_vars['USER_ACTIVE']->value) {?><?php $_smarty_tpl->tpl_vars['isactive'] = new Smarty_variable(1, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['isactive'] = new Smarty_variable(0, null, 0);?><?php }?>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['isactive']->value),'name'=>"isactive",'id'=>"isactive"),$_smarty_tpl);?>


</td>

</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_users_autodownload']->value;?>
</td><td>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['autodownload']->value),'name'=>"autodownload",'id'=>"autodownload"),$_smarty_tpl);?>


</td>
<td><?php echo $_smarty_tpl->tpl_vars['LN_users_fileedit']->value;?>
</td><td>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['file_edit']->value),'name'=>"fileedit",'id'=>"fileedit"),$_smarty_tpl);?>


</td>
</tr>

<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_users_allow_erotica']->value;?>
</td><td>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['allow_erotica']->value),'name'=>"allow_erotica",'id'=>"allow_erotica"),$_smarty_tpl);?>

</td>
<td><?php echo $_smarty_tpl->tpl_vars['LN_users_allow_update']->value;?>
</td><td>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['allow_update']->value),'name'=>"allow_update",'id'=>"allow_update"),$_smarty_tpl);?>


</tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr><td colspan="4" class="centered">
<?php if ($_smarty_tpl->tpl_vars['id']->value=='new') {?>
	<input type="button" name="add" value="<?php echo $_smarty_tpl->tpl_vars['LN_add']->value;?>
" onclick="javascript:update_user();" class="submit"/>
<?php } else { ?>
	<input type="button" name="apply" value="<?php echo $_smarty_tpl->tpl_vars['LN_apply']->value;?>
" class="submit" onclick="javascript:update_user();"/>
<?php }?>
</td>
</tr>


</table>
</div>
<?php }} ?>