<?php /* Smarty version Smarty-3.1.14, created on 2013-08-10 00:20:52
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_users.tpl" */ ?>
<?php /*%%SmartyHeaderCode:97011481452056b44c03407-41541843%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aa9cbe40d9b04c30a60df7d80ef888a73e6842af' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_users.tpl',
      1 => 1367103342,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '97011481452056b44c03407-41541843',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_search' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056b44c745d1_38023444',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056b44c745d1_38023444')) {function content_52056b44c745d1_38023444($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>

<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h3>
<div>
<input type="text" name="search" value="&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;" onfocus="javascript:clean_search('search');" id="search" size="30" onkeypress="javascript:submit_enter(event, show_users);"/>
<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="submitsmall" onclick="javascript:show_users();"/></div>
<br/>

<div id="usersdiv">
<script type="text/javascript">
$(document).ready(function() {
    show_users();
});
</script>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>