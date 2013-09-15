<?php /* Smarty version Smarty-3.1.14, created on 2013-09-14 00:15:14
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_edit_usenet_servers.tpl" */ ?>
<?php /*%%SmartyHeaderCode:135991403052056b2db88301-66313613%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cc929544d47b618f84c26d25cea6270391f1ebbd' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_edit_usenet_servers.tpl',
      1 => 1378854306,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '135991403052056b2db88301-66313613',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056b2e01f808_05556007',
  'variables' => 
  array (
    'id' => 0,
    'LN_usenet_addserver' => 0,
    'LN_usenet_editserver' => 0,
    'only_auth' => 0,
    'auth_class' => 0,
    'LN_usenet_name_msg' => 0,
    'LN_name' => 0,
    'name' => 0,
    'text_box_size' => 0,
    'LN_usenet_hostname_msg' => 0,
    'LN_usenet_hostname' => 0,
    'hostname' => 0,
    'LN_usenet_port_msg' => 0,
    'LN_usenet_port' => 0,
    'port' => 0,
    'number_box_size' => 0,
    'LN_usenet_secport_msg' => 0,
    'LN_usenet_secport' => 0,
    'sec_port' => 0,
    'LN_usenet_connectiontype_msg' => 0,
    'LN_usenet_connectiontype' => 0,
    'connection_types' => 0,
    'connection' => 0,
    'LN_usenet_needsauthentication_msg' => 0,
    'LN_usenet_needsauthentication' => 0,
    'authentication' => 0,
    'LN_usenet_username_msg' => 0,
    'LN_username' => 0,
    'username' => 0,
    'LN_usenet_password_msg' => 0,
    'LN_password' => 0,
    'password' => 0,
    'LN_usenet_nrofthreads_msg' => 0,
    'LN_usenet_nrofthreads' => 0,
    'threads' => 0,
    'LN_usenet_priority_msg' => 0,
    'LN_usenet_priority' => 0,
    'priority' => 0,
    'LN_usenet_compressed_headers_msg' => 0,
    'LN_usenet_compressed_headers' => 0,
    'show_post' => 0,
    'LN_usenet_posting' => 0,
    'LN_add' => 0,
    'LN_apply' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056b2e01f808_05556007')) {function content_52056b2e01f808_05556007($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.html_options.php';
?>



<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php if ($_smarty_tpl->tpl_vars['id']->value=='new') {?><?php echo $_smarty_tpl->tpl_vars['LN_usenet_addserver']->value;?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['LN_usenet_editserver']->value;?>
<?php }?></div>
<?php if ($_smarty_tpl->tpl_vars['only_auth']->value) {?><?php $_smarty_tpl->tpl_vars['auth_class'] = new Smarty_variable("hidden", null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['auth_class'] = new Smarty_variable('', null, 0);?><?php }?>

<div class="light">
<br/>
<input type="hidden" name="id" id="id" value="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"/></td>
<table class="hmid">

<tr class="<?php echo $_smarty_tpl->tpl_vars['auth_class']->value;?>
">
<td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_name_msg']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_name']->value;?>
:</td>
<td colspan="3"><input type="text" name="name" id="name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['name']->value, ENT_QUOTES, 'UTF-8', true);?>
" size="<?php echo $_smarty_tpl->tpl_vars['text_box_size']->value;?>
"/></td>
</tr>
<tr class="<?php echo $_smarty_tpl->tpl_vars['auth_class']->value;?>
">
<td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_hostname_msg']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_usenet_hostname']->value;?>
:</td>
<td colspan="3"><input type="text" name="hostname" id="hostname" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hostname']->value, ENT_QUOTES, 'UTF-8', true);?>
" size="<?php echo $_smarty_tpl->tpl_vars['text_box_size']->value;?>
"/></td>
</tr>
<tr class="<?php echo $_smarty_tpl->tpl_vars['auth_class']->value;?>
">
<td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_port_msg']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_usenet_port']->value;?>
:</td>
<td ><input type="text" name="port" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['port']->value, ENT_QUOTES, 'UTF-8', true);?>
" id="port" size="<?php echo $_smarty_tpl->tpl_vars['number_box_size']->value;?>
"/></td>
<td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_secport_msg']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_usenet_secport']->value;?>
:</td>
<td> <input type="text" id="sec_port" name="secure_port" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sec_port']->value, ENT_QUOTES, 'UTF-8', true);?>
" size="<?php echo $_smarty_tpl->tpl_vars['number_box_size']->value;?>
"/> </td>
</tr>
<tr class="<?php echo $_smarty_tpl->tpl_vars['auth_class']->value;?>
">
<td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_connectiontype_msg']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_usenet_connectiontype']->value;?>
:</td>
<td> <?php echo smarty_function_html_options(array('name'=>"connection",'id'=>"connection",'options'=>$_smarty_tpl->tpl_vars['connection_types']->value,'selected'=>$_smarty_tpl->tpl_vars['connection']->value),$_smarty_tpl);?>
</td>
</tr>
<tr><td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_needsauthentication_msg']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
>
<?php echo $_smarty_tpl->tpl_vars['LN_usenet_needsauthentication']->value;?>
:</td>
<td colspan="3">
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['authentication']->value),'name'=>"authentication",'id'=>"needauthentication",'post_js'=>"show_auth();"),$_smarty_tpl);?>

</tr>
<tr id="authuser" class="<?php if ($_smarty_tpl->tpl_vars['authentication']->value!=1) {?>hidden<?php }?>">
<td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_username_msg']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_username']->value;?>
:</td>
<td colspan="3"><input type="text" name="username" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['username']->value, ENT_QUOTES, 'UTF-8', true);?>
" id="username" size="<?php echo $_smarty_tpl->tpl_vars['text_box_size']->value;?>
"/></td>
</tr>
<tr id="authpass" class="<?php if ($_smarty_tpl->tpl_vars['authentication']->value!=1) {?>hidden<?php }?>">
<td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_password_msg']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_password']->value;?>
:</td>
<td colspan="3"><input type="password" name="password" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['password']->value, ENT_QUOTES, 'UTF-8', true);?>
" id="password" size="<?php echo $_smarty_tpl->tpl_vars['text_box_size']->value;?>
"/> &nbsp;&nbsp; 
    <div class="floatright iconsizeplus sadicon buttonlike" onclick="javascript:toggle_show_password('password');"
    </td>
</tr>

<tr class="<?php echo $_smarty_tpl->tpl_vars['auth_class']->value;?>
">
<td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_nrofthreads_msg']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_usenet_nrofthreads']->value;?>
:</td>
<td><input type="text" name="threads" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['threads']->value, ENT_QUOTES, 'UTF-8', true);?>
" id="threads" size="<?php echo $_smarty_tpl->tpl_vars['number_box_size']->value;?>
"/></td>
<td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_priority_msg']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_usenet_priority']->value;?>
:</td>
<td><input type="text" name="priority" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['priority']->value, ENT_QUOTES, 'UTF-8', true);?>
" id="priority" size="<?php echo $_smarty_tpl->tpl_vars['number_box_size']->value;?>
"/></td></tr>
<tr class="<?php echo $_smarty_tpl->tpl_vars['auth_class']->value;?>
">
<td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_compressed_headers_msg']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_usenet_compressed_headers']->value;?>
:</td>
<td>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['compressed_headers']->value),'name'=>"compressed_headers",'id'=>"compressed_headers"),$_smarty_tpl);?>

</td>
<?php if ($_smarty_tpl->tpl_vars['show_post']->value) {?>
<td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_posting']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
>
<?php echo $_smarty_tpl->tpl_vars['LN_usenet_posting']->value;?>
:</td>
<td>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['posting']->value),'name'=>"posting",'id'=>"posting"),$_smarty_tpl);?>

</td>
<?php }?>
</tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr><td colspan="4" class="centered">
<?php if ($_smarty_tpl->tpl_vars['id']->value=='new') {?>
	<input type="button" name="add" value="<?php echo $_smarty_tpl->tpl_vars['LN_add']->value;?>
" onclick="javascript:update_usenet_server();" class="submit"/>
<?php } else { ?>
	<input type="button" name="apply" value="<?php echo $_smarty_tpl->tpl_vars['LN_apply']->value;?>
" class="submit" onclick="javascript:update_usenet_server();"/>
<?php }?>
</td>
</tr>
</table>

</div>

<?php }} ?>