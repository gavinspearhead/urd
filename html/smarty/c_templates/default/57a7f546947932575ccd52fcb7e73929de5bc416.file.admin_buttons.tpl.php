<?php /* Smarty version Smarty-3.1.14, created on 2013-08-06 00:04:47
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_buttons.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10728143355200217f6c4208-31477010%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '57a7f546947932575ccd52fcb7e73929de5bc416' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_buttons.tpl',
      1 => 1367103342,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10728143355200217f6c4208-31477010',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_buttons_title' => 0,
    'LN_search' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5200217f718ca3_92533773',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5200217f718ca3_92533773')) {function content_5200217f718ca3_92533773($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>

<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_buttons_title']->value;?>
</h3>

<div>
<input type="text" name="search" value="&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;" onfocus="javascript:clean_search('search');" id="search" size="30" onkeypress="javascript:submit_enter(event, show_buttons);"/>
<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="submitsmall" onclick="javascript:show_buttons();"/></div>
<br/>

<script type="text/javascript">
$(document).ready(function() {
        show_buttons(); 
});
</script>

<div id="buttonsdiv">
</div>



<div><br/></div>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>