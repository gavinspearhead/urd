<?php /* Smarty version Smarty-3.1.14, created on 2013-09-04 23:39:01
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_admin_users.tpl" */ ?>
<?php /*%%SmartyHeaderCode:105023492952056b45a0c493-46991040%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3f47e5efbe070f800a7e124c0f2c6abce1d6da26' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_admin_users.tpl',
      1 => 1378156929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '105023492952056b45a0c493-46991040',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056b460443a0_10533494',
  'variables' => 
  array (
    'sort' => 0,
    'sort_dir' => 0,
    'up' => 0,
    'down' => 0,
    'id_sort' => 0,
    'LN_username' => 0,
    'name_sort' => 0,
    'LN_fullname' => 0,
    'fullname_sort' => 0,
    'LN_email' => 0,
    'email_sort' => 0,
    'LN_users_last_active' => 0,
    'last_active_sort' => 0,
    'LN_users_isadmin' => 0,
    'isadmin_sort' => 0,
    'LN_users_rights' => 0,
    'rights_sort' => 0,
    'LN_users_post' => 0,
    'LN_active' => 0,
    'active_sort' => 0,
    'LN_actions' => 0,
    'users' => 0,
    'user' => 0,
    'maxstrlen' => 0,
    'USER_ADMIN' => 0,
    'LN_users_rights_help' => 0,
    'LN_users_post_help' => 0,
    'USER_ACTIVE' => 0,
    'LN_users_edit' => 0,
    'emailallowed' => 0,
    'LN_users_resetpw' => 0,
    'LN_users_delete' => 0,
    'LN_delete' => 0,
    'LN_error_nousersfound' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056b460443a0_10533494')) {function content_52056b460443a0_10533494($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.truncate.php';
if (!is_callable('smarty_modifier_capitalize')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.capitalize.php';
?>


<div>
<input type="hidden" name="order" id="order" value="<?php echo $_smarty_tpl->tpl_vars['sort']->value;?>
"/>
<input type="hidden" name="order_dir" id="order_dir" value="<?php echo $_smarty_tpl->tpl_vars['sort_dir']->value;?>
"/>
</div>


<?php $_smarty_tpl->tpl_vars['up'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_up.png' alt=''>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['down'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_down.png' alt=''>", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=='') {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="id") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['id_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['id_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['id_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="fullname") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['fullname_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['fullname_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['fullname_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="email") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['email_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['email_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['email_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="last_active") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['last_active_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['last_active_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['last_active_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="isadmin") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['isadmin_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['isadmin_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['isadmin_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="rights") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['rights_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['rights_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['rights_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="active") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['active_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['active_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['active_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="name") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable('', null, 0);?><?php }?>


<table class="articles">
<tr>
<th onclick="javascript:submit_search_users('id', 'asc');" class="buttonlike head round_left"># <?php echo $_smarty_tpl->tpl_vars['id_sort']->value;?>
</th>
<th onclick="javascript:submit_search_users('name', 'asc');" class="buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_username']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['name_sort']->value;?>
</th>
<th onclick="javascript:submit_search_users('fullname', 'asc');" class="buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_fullname']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['fullname_sort']->value;?>
</th>
<th onclick="javascript:submit_search_users('email', 'asc');" class="buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_email']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['email_sort']->value;?>
</th>
<th onclick="javascript:submit_search_users('last_active', 'asc');" class="buttonlike head"><?php echo $_smarty_tpl->tpl_vars['LN_users_last_active']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['last_active_sort']->value;?>
</th>
<th onclick="javascript:submit_search_users('isadmin', 'asc');" class="buttonlike center head"><?php echo $_smarty_tpl->tpl_vars['LN_users_isadmin']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['isadmin_sort']->value;?>
</th>
<th onclick="javascript:submit_search_users('rights', 'asc');" class="buttonlike center head"><?php echo $_smarty_tpl->tpl_vars['LN_users_rights']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['rights_sort']->value;?>
</th>
<th class="center head"><?php echo $_smarty_tpl->tpl_vars['LN_users_post']->value;?>
</th>
<th onclick="javascript:submit_search_users('active', 'asc');" class="buttonlike center head"><?php echo $_smarty_tpl->tpl_vars['LN_active']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['active_sort']->value;?>
</th>
<th class="head round_right right"><?php echo $_smarty_tpl->tpl_vars['LN_actions']->value;?>
</th>
</tr>
<?php  $_smarty_tpl->tpl_vars['user'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['user']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['users']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['user']->key => $_smarty_tpl->tpl_vars['user']->value) {
$_smarty_tpl->tpl_vars['user']->_loop = true;
?>
<tr class="even content" onmouseover="javascript:ToggleClass(this,'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');">
	<td><?php echo smarty_modifier_truncate(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->id, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</td>
	<td><?php echo smarty_modifier_truncate(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->username, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</td>
	<td><?php echo smarty_modifier_truncate(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->fullname, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</td>
	<td><?php echo smarty_modifier_truncate(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->email, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</td>
	<td><?php echo htmlspecialchars(smarty_modifier_capitalize($_smarty_tpl->tpl_vars['user']->value->last_active), ENT_QUOTES, 'UTF-8', true);?>
</td>
	<td class="center" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_users_isadmin']->value),$_smarty_tpl);?>
>
    <?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['user']->value->admin==$_smarty_tpl->tpl_vars['USER_ADMIN']->value) {?><?php echo "1";?><?php } else { ?><?php echo "0";?><?php }?><?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['user']->value->admin==$_smarty_tpl->tpl_vars['USER_ADMIN']->value) {?><?php echo "0";?><?php } else { ?><?php echo "1";?><?php }?><?php $_tmp2=ob_get_clean();?><?php echo smarty_function_urd_checkbox(array('value'=>$_tmp1,'name'=>"user_is_admin",'id'=>"user_".((string) $_smarty_tpl->tpl_vars['user']->value->id)."_is_admin",'post_js'=>"user_update_setting(".((string) $_smarty_tpl->tpl_vars['user']->value->id).", 'admin', ".$_tmp2.");"),$_smarty_tpl);?>

</td>
	<td class="center" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_users_rights_help']->value),$_smarty_tpl);?>
>
<?php ob_start();?><?php if (isset($_smarty_tpl->tpl_vars['user']->value->rights['c'])&&$_smarty_tpl->tpl_vars['user']->value->rights['c']==1) {?><?php echo "1";?><?php } else { ?><?php echo "0";?><?php }?><?php $_tmp3=ob_get_clean();?><?php ob_start();?><?php if (isset($_smarty_tpl->tpl_vars['user']->value->rights['c'])&&$_smarty_tpl->tpl_vars['user']->value->rights['c']==1) {?><?php echo "0";?><?php } else { ?><?php echo "1";?><?php }?><?php $_tmp4=ob_get_clean();?><?php echo smarty_function_urd_checkbox(array('value'=>$_tmp3,'name'=>"user_is_setedit",'id'=>"user_".((string) $_smarty_tpl->tpl_vars['user']->value->id)."_is_setedit",'post_js'=>"user_update_setting(".((string) $_smarty_tpl->tpl_vars['user']->value->id).", 'set_editor', ".$_tmp4.");"),$_smarty_tpl);?>

	
	</td>
	<td class="center" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_users_post_help']->value),$_smarty_tpl);?>
 >
    <?php ob_start();?><?php if (isset($_smarty_tpl->tpl_vars['user']->value->rights['p'])&&$_smarty_tpl->tpl_vars['user']->value->rights['p']==1) {?><?php echo "1";?><?php } else { ?><?php echo "0";?><?php }?><?php $_tmp5=ob_get_clean();?><?php ob_start();?><?php if (isset($_smarty_tpl->tpl_vars['user']->value->rights['p'])&&$_smarty_tpl->tpl_vars['user']->value->rights['p']==1) {?><?php echo "0";?><?php } else { ?><?php echo "1";?><?php }?><?php $_tmp6=ob_get_clean();?><?php echo smarty_function_urd_checkbox(array('value'=>$_tmp5,'name'=>"user_post",'id'=>"user_".((string) $_smarty_tpl->tpl_vars['user']->value->id)."_post",'post_js'=>"user_update_setting(".((string) $_smarty_tpl->tpl_vars['user']->value->id).", 'posting', ".$_tmp6.");"),$_smarty_tpl);?>

    
	</td>
	<td class="center" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_active']->value),$_smarty_tpl);?>
 >
    <?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['user']->value->active==$_smarty_tpl->tpl_vars['USER_ACTIVE']->value) {?><?php echo "1";?><?php } else { ?><?php echo "0";?><?php }?><?php $_tmp7=ob_get_clean();?><?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['user']->value->active==$_smarty_tpl->tpl_vars['USER_ACTIVE']->value) {?><?php echo "0";?><?php } else { ?><?php echo "1";?><?php }?><?php $_tmp8=ob_get_clean();?><?php echo smarty_function_urd_checkbox(array('value'=>$_tmp7,'name'=>"user_is_active",'id'=>"user_".((string) $_smarty_tpl->tpl_vars['user']->value->id)."_is_active",'post_js'=>"user_update_setting(".((string) $_smarty_tpl->tpl_vars['user']->value->id).", 'active', ".$_tmp8.");"),$_smarty_tpl);?>

    </td>
	<td><div class="floatright">

    <div class="inline iconsizeplus editicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_users_edit']->value),$_smarty_tpl);?>
 onclick="javascript:user_action('edit',<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
);"></div>
	<?php if ($_smarty_tpl->tpl_vars['emailallowed']->value!=0) {?> 
    <div class="inline iconsizeplus mailicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_users_resetpw']->value),$_smarty_tpl);?>
 onclick="javascript:user_action('resetpw',<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
);"></div>
	<?php }?>
    <div class="inline iconsizeplus deleteicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_users_delete']->value),$_smarty_tpl);?>
 onclick="javascript:user_action_confirm('delete',<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
, '<?php echo $_smarty_tpl->tpl_vars['LN_delete']->value;?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->username, ENT_QUOTES, 'UTF-8', true);?>
?');"></div>
    </div>
	</td>
</tr>
<?php }
if (!$_smarty_tpl->tpl_vars['user']->_loop) {
?>
<tr><td colspan="9" class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_nousersfound']->value;?>
</td></tr>
<?php } ?>
</table>
<?php }} ?>