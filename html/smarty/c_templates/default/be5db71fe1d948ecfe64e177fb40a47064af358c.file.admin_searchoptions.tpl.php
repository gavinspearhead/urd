<?php /* Smarty version Smarty-3.1.14, created on 2013-08-17 16:53:26
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_searchoptions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12411135455206b55c48b7f0-03346195%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'be5db71fe1d948ecfe64e177fb40a47064af358c' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_searchoptions.tpl',
      1 => 1376174333,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12411135455206b55c48b7f0-03346195',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5206b55c4fdfc1_54412831',
  'variables' => 
  array (
    'title' => 0,
    'LN_buttons_title' => 0,
    'LN_search' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206b55c4fdfc1_54412831')) {function content_5206b55c4fdfc1_54412831($_smarty_tpl) {?>
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