<?php /* Smarty version Smarty-3.1.14, created on 2013-08-27 22:40:00
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_log.tpl" */ ?>
<?php /*%%SmartyHeaderCode:510743980521d0ea0de9859-02816447%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '98045b1ce5a25ef417fe87939f7c299a91f22765' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_log.tpl',
      1 => 1374444181,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '510743980521d0ea0de9859-02816447',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_log_title' => 0,
    'sort_dir' => 0,
    'sort' => 0,
    'LN_log_lines' => 0,
    'lines' => 0,
    'LN_search' => 0,
    'search' => 0,
    'LN_log_level' => 0,
    'log_str' => 0,
    'log_level' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_521d0ea0eb8be6_13081877',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_521d0ea0eb8be6_13081877')) {function content_521d0ea0eb8be6_13081877($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.capitalize.php';
if (!is_callable('smarty_function_html_options')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.html_options.php';
?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>

<h3 class="title"><?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['LN_log_title']->value);?>
</h3>

<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['sort_dir']->value;?>
" id="sort_dir"/>
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['sort']->value;?>
" id="sort_order"/>
<div>
<?php echo $_smarty_tpl->tpl_vars['LN_log_lines']->value;?>
: <input type="text" id="lines" name="lines" value="<?php echo $_smarty_tpl->tpl_vars['lines']->value;?>
" size="6"/>
<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
: <input type="text" name="search" id="search" value="<?php if ($_smarty_tpl->tpl_vars['search']->value=='') {?>&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;<?php } else { ?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['search']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" onfocus="javascript:clean_search('search');" size="30"/>
<?php echo $_smarty_tpl->tpl_vars['LN_log_level']->value;?>
: 
<select name="log_level" id="log_level">
<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['log_str']->value,'selected'=>$_smarty_tpl->tpl_vars['log_level']->value),$_smarty_tpl);?>

</select>
<input type="submit" name="submit_button" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="vbot submitsmall" onclick="javascript:show_logs();"/>
</div>
<div><br/></div>

<div id="logdiv">
</div>
<script type="text/javascript">

$(document).ready(function() {
        show_logs();
});
</script>


<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>