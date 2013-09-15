<?php /* Smarty version Smarty-3.1.14, created on 2013-08-06 00:03:55
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/statistics.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10100604945200214b7d52d5-36557736%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6e0f0bb7eb18856f26ebfcbd45809d0cc62f940b' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/statistics.tpl',
      1 => 1370040730,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10100604945200214b7d52d5-36557736',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'thisyear' => 0,
    'tab' => 0,
    'years' => 0,
    'year' => 0,
    'LN_stats_overview' => 0,
    'LN_menubrowsesets' => 0,
    'selector' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5200214b85be16_95148821',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5200214b85be16_95148821')) {function content_5200214b85be16_95148821($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>

<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h3>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'selector', null); ob_start(); ?>
<div class="pref_selector"><input type="hidden" id="selected" value="<?php echo $_smarty_tpl->tpl_vars['thisyear']->value;?>
"/><input type="hidden" id="tab" value="<?php echo $_smarty_tpl->tpl_vars['tab']->value;?>
"/><ul class="tabs"><?php  $_smarty_tpl->tpl_vars['year'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['year']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['years']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['year']->key => $_smarty_tpl->tpl_vars['year']->value) {
$_smarty_tpl->tpl_vars['year']->_loop = true;
?><li onclick="javascript:select_tab_stats('<?php echo strtr($_smarty_tpl->tpl_vars['year']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', 'activity', '<?php echo strtr($_smarty_tpl->tpl_vars['year']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', 'months')" class="tab" id="<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
_bar"><?php echo $_smarty_tpl->tpl_vars['year']->value;?>
<input type="hidden" name="tabs" value="<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
"/></li><?php } ?><li onclick="javascript:select_tab_stats('activity', 'activity', null, 'years')" class="tab" id="activity_bar"><?php echo $_smarty_tpl->tpl_vars['LN_stats_overview']->value;?>
<input type="hidden" name="tabs" value="activity"/></li><li onclick="javascript:select_tab_stats('supply', 'supply')" class="tab" id="supply_bar"><?php echo $_smarty_tpl->tpl_vars['LN_menubrowsesets']->value;?>
<input type="hidden" name="tabs" value="supply"/></li></ul></div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<div id="ng_headerbox" class="newsgroups">
<?php echo $_smarty_tpl->tpl_vars['selector']->value;?>

</div>

<table class="statistics" id="stats_table">
<tr><td>
<div id="show_stats">
</div>
</td></tr></table>

<script type="text/javascript">
$(document).ready(function() {
    select_tab_stats('<?php echo strtr($_smarty_tpl->tpl_vars['thisyear']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', 'activity', '<?php echo strtr($_smarty_tpl->tpl_vars['thisyear']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', 'months');
});
</script>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>