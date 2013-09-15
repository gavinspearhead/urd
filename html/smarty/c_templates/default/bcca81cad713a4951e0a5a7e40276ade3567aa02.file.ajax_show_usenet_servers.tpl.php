<?php /* Smarty version Smarty-3.1.14, created on 2013-09-04 23:38:52
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_show_usenet_servers.tpl" */ ?>
<?php /*%%SmartyHeaderCode:70249231852056b233d01b1-05816794%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bcca81cad713a4951e0a5a7e40276ade3567aa02' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_show_usenet_servers.tpl',
      1 => 1378156929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '70249231852056b233d01b1-05816794',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056b238c8717_48368832',
  'variables' => 
  array (
    'sort' => 0,
    'sort_dir' => 0,
    'up' => 0,
    'down' => 0,
    'LN_usenet_priority' => 0,
    'priority_sort' => 0,
    'LN_usenet_indexing' => 0,
    'indexing_sort' => 0,
    'show_post' => 0,
    'LN_usenet_posting' => 0,
    'posting_sort' => 0,
    'LN_name' => 0,
    'name_sort' => 0,
    'LN_usenet_threads' => 0,
    'threads_sort' => 0,
    'LN_usenet_connection' => 0,
    'connection_sort' => 0,
    'LN_usenet_authentication' => 0,
    'authentication_sort' => 0,
    'LN_username' => 0,
    'username_sort' => 0,
    'LN_actions' => 0,
    'usenet_servers' => 0,
    'usenet_server' => 0,
    'LN_usenet_enable' => 0,
    'LN_usenet_disable' => 0,
    'enable' => 0,
    'disable' => 0,
    'maxstrlen' => 0,
    'primary' => 0,
    'LN_usenet_needsauthentication' => 0,
    'LN_usenet_edit' => 0,
    'LN_usenet_delete' => 0,
    'LN_delete' => 0,
    'LN_error_noserversfound' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056b238c8717_48368832')) {function content_52056b238c8717_48368832($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.truncate.php';
?>



<input type="hidden" name="order" id="order" value="<?php echo $_smarty_tpl->tpl_vars['sort']->value;?>
"/>
<input type="hidden" name="order_dir" id="order_dir" value="<?php echo $_smarty_tpl->tpl_vars['sort_dir']->value;?>
"/>

<?php $_smarty_tpl->tpl_vars['up'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_up.png' alt=''>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['down'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_down.png' alt=''>", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=='') {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="name") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="priority") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['priority_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['priority_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['priority_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="posting") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['posting_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['posting_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['posting_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="threads") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['threads_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['threads_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['threads_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="connection") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['connection_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['connection_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['connection_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="authentication") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['authentication_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['authentication_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['authentication_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="username") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['username_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['username_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['username_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="indexing") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['indexing_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['indexing_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['indexing_sort'] = new Smarty_variable('', null, 0);?><?php }?>


<table class="newsservers">
<tr>
<th onclick="javascript:show_usenet_servers('priority', 'desc');" class="buttonlike uwider fixwidth3c head round_left"><?php echo $_smarty_tpl->tpl_vars['LN_usenet_priority']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['priority_sort']->value;?>
</th>
<th  onclick="javascript:show_usenet_servers('indexing', 'asc');" class="uwider fixwidth3c head"><?php echo $_smarty_tpl->tpl_vars['LN_usenet_indexing']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['indexing_sort']->value;?>
</th>
<?php if ($_smarty_tpl->tpl_vars['show_post']->value) {?>
<th onclick="javascript:show_usenet_servers('posting', 'asc');" class="buttonlike uwider fixwidth3c head"><?php echo $_smarty_tpl->tpl_vars['LN_usenet_posting']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['posting_sort']->value;?>
</th>
<?php }?>
<th onclick="javascript:show_usenet_servers('name', 'asc');" class="buttonlike uwider head"><?php echo $_smarty_tpl->tpl_vars['LN_name']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['name_sort']->value;?>
</th>
<th onclick="javascript:show_usenet_servers('threads', 'asc');" class="buttonlike uwider fixwidth3c head"><?php echo $_smarty_tpl->tpl_vars['LN_usenet_threads']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['threads_sort']->value;?>
</th>
<th onclick="javascript:show_usenet_servers('connection', 'asc');" class="buttonlike uwider fixwidth3c head"><?php echo $_smarty_tpl->tpl_vars['LN_usenet_connection']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['connection_sort']->value;?>
</th>
<th onclick="javascript:show_usenet_servers('authentication', 'asc');" class="buttonlike uwider fixwidth3c head"><?php echo $_smarty_tpl->tpl_vars['LN_usenet_authentication']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['authentication_sort']->value;?>
</th>
<th onclick="javascript:show_usenet_servers('username', 'asc');" class="buttonlike uwider head"><?php echo $_smarty_tpl->tpl_vars['LN_username']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['username_sort']->value;?>
</th>
<th class="head round_right right"><?php echo $_smarty_tpl->tpl_vars['LN_actions']->value;?>
</th>
</tr>

<?php  $_smarty_tpl->tpl_vars['usenet_server'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['usenet_server']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['usenet_servers']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['usenet_server']->key => $_smarty_tpl->tpl_vars['usenet_server']->value) {
$_smarty_tpl->tpl_vars['usenet_server']->_loop = true;
?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', "enable", null); ob_start(); ?>
<div class="floatleft light_red iconsizeplus noborder" onclick="javascript:usenet_action('enable_server',<?php echo $_smarty_tpl->tpl_vars['usenet_server']->value->id;?>
)" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_enable']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
></div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', "disable", null); ob_start(); ?>
<div class="floatleft light_green iconsizeplus noborder" onclick="javascript:usenet_action('disable_server',<?php echo $_smarty_tpl->tpl_vars['usenet_server']->value->id;?>
)" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>htmlspecialchars($_smarty_tpl->tpl_vars['LN_usenet_disable']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
></div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<tr class="even content server_<?php if ($_smarty_tpl->tpl_vars['usenet_server']->value->priority==0) {?>disabled<?php } else { ?>enabled<?php }?>" onmouseover="javascript:ToggleClass(this,'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');">
<td class="uwider"><?php if ($_smarty_tpl->tpl_vars['usenet_server']->value->priority==0) {?><?php echo $_smarty_tpl->tpl_vars['enable']->value;?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['disable']->value;?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['usenet_server']->value->priority!=0) {?><div class="floatleft"><?php echo smarty_modifier_truncate(htmlspecialchars($_smarty_tpl->tpl_vars['usenet_server']->value->priority, ENT_QUOTES, 'UTF-8', true),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</div><?php }?>
</td>
<td class="fixwidth3c">
<?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['usenet_server']->value->id==$_smarty_tpl->tpl_vars['primary']->value) {?><?php echo "1";?><?php } else { ?><?php echo "0";?><?php }?><?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['usenet_server']->value->id!=$_smarty_tpl->tpl_vars['primary']->value) {?><?php echo "usenet_action('set_preferred',";?><?php echo (string) $_smarty_tpl->tpl_vars['usenet_server']->value->id;?><?php echo ")";?><?php }?><?php $_tmp2=ob_get_clean();?><?php echo smarty_function_urd_checkbox(array('value'=>$_tmp1,'name'=>"primary",'id'=>"primary_".((string) $_smarty_tpl->tpl_vars['usenet_server']->value->id),'post_js'=>$_tmp2),$_smarty_tpl);?>

</td>
<?php if ($_smarty_tpl->tpl_vars['show_post']->value) {?>
<td class="fixwidth3c">
<?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['usenet_server']->value->posting==1) {?><?php echo "'disable_posting'";?><?php } else { ?><?php echo "'enable_posting'";?><?php }?><?php $_tmp3=ob_get_clean();?><?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['usenet_server']->value->posting),'name'=>"posting",'id'=>"posting_".((string) $_smarty_tpl->tpl_vars['usenet_server']->value->id),'post_js'=>"usenet_action(".$_tmp3.", ".((string) $_smarty_tpl->tpl_vars['usenet_server']->value->id).");"),$_smarty_tpl);?>

</td>
<?php }?>
<td><?php echo smarty_modifier_truncate(htmlspecialchars($_smarty_tpl->tpl_vars['usenet_server']->value->name, ENT_QUOTES, 'UTF-8', true),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</td>
<td class="fixwidth3c"><?php echo smarty_modifier_truncate(htmlspecialchars($_smarty_tpl->tpl_vars['usenet_server']->value->threads, ENT_QUOTES, 'UTF-8', true),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</td>
<td class="fixwidth3c"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['usenet_server']->value->connection,$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</td>
<td class="fixwidth3c" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_usenet_needsauthentication']->value),$_smarty_tpl);?>
> 
<?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['usenet_server']->value->authentication==1) {?><?php echo "1";?><?php } else { ?><?php echo "0";?><?php }?><?php $_tmp4=ob_get_clean();?><?php echo smarty_function_urd_checkbox(array('value'=>$_tmp4,'name'=>"need_auth",'id'=>"need_auth_".((string) $_smarty_tpl->tpl_vars['usenet_server']->value->id),'post_js'=>"toggle_usenet_auth(".((string) $_smarty_tpl->tpl_vars['usenet_server']->value->id).", 'need_auth_".((string) $_smarty_tpl->tpl_vars['usenet_server']->value->id)."')"),$_smarty_tpl);?>

<td>
<?php if ($_smarty_tpl->tpl_vars['usenet_server']->value->authentication==1) {?>
<?php echo smarty_modifier_truncate(htmlspecialchars($_smarty_tpl->tpl_vars['usenet_server']->value->username, ENT_QUOTES, 'UTF-8', true),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>

<?php }?>
</td>

<td>
<div class="floatright">
<div class="inline iconsizeplus editicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_usenet_edit']->value),$_smarty_tpl);?>
 onclick="javascript:edit_usenet_server(<?php echo $_smarty_tpl->tpl_vars['usenet_server']->value->id;?>
);"></div>
<div class="inline iconsizeplus deleteicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_usenet_delete']->value),$_smarty_tpl);?>
 onclick="javascript:usenet_action_confirm('delete_server',<?php echo $_smarty_tpl->tpl_vars['usenet_server']->value->id;?>
, '<?php echo $_smarty_tpl->tpl_vars['LN_delete']->value;?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['usenet_server']->value->name, ENT_QUOTES, 'UTF-8', true);?>
?');"></div>
</div>
</td>
</tr>
<?php }
if (!$_smarty_tpl->tpl_vars['usenet_server']->_loop) {
?>
<tr><td colspan="9" class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_noserversfound']->value;?>
</td></tr>
<?php } ?>
</table>
<div><br/></div>



<?php }} ?>