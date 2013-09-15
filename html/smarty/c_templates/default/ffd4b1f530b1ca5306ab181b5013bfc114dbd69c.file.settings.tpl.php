<?php /* Smarty version Smarty-3.1.14, created on 2013-08-06 00:04:41
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:27372105952002179b79ae9-39838833%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ffd4b1f530b1ca5306ab181b5013bfc114dbd69c' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/settings.tpl',
      1 => 1373757135,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '27372105952002179b79ae9-39838833',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'heading' => 0,
    'source' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52002179bc8ff6_77750026',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52002179bc8ff6_77750026')) {function content_52002179bc8ff6_77750026($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>



<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['heading']->value;?>
</h3>

<script type="text/javascript">
$(document).ready(function() {
    load_prefs();
    }
);
    
</script>
<input type="hidden" id="source" name="source" value="<?php echo $_smarty_tpl->tpl_vars['source']->value;?>
"/>
<div id="settingsdiv"></div>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>