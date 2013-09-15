<?php /* Smarty version Smarty-3.1.14, created on 2013-08-10 00:20:18
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_usenet_servers.tpl" */ ?>
<?php /*%%SmartyHeaderCode:43089182952056b225d87d8-54258519%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3ff819847a1ee619ae9135dd79ef1d8678e3b19d' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_usenet_servers.tpl',
      1 => 1367103342,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '43089182952056b225d87d8-54258519',
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
  'unifunc' => 'content_52056b2264ae90_27017142',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056b2264ae90_27017142')) {function content_52056b2264ae90_27017142($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>

<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h3>

<div class="hidden">
<input type="hidden" name="action_id" id="action_id" value=""/>
<input type="hidden" name="action" id="action" value=""/></div>

<div>
<input type="text" name="search" value="&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;" onfocus="javascript:clean_search('search');" id="search" size="30" onkeypress="javascript:submit_enter(event, show_usenet_servers);"/>
<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="submitsmall" onclick="javascript:show_usenet_servers();"/></div>
<br/>

<div id="usenetserversdiv">
<script type="text/javascript">
$(document).ready(function() {
    show_usenet_servers();
});
</script>
</div>
<div><br/></div>

<p>&nbsp;</p>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>