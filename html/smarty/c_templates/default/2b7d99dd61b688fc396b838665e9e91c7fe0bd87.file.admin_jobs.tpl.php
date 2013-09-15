<?php /* Smarty version Smarty-3.1.14, created on 2013-08-06 00:05:02
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_jobs.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4115726615200218eab0772-11638783%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2b7d99dd61b688fc396b838665e9e91c7fe0bd87' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/admin_jobs.tpl',
      1 => 1367103342,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4115726615200218eab0772-11638783',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_jobs_title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5200218eafd624_03822829',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5200218eafd624_03822829')) {function content_5200218eafd624_03822829($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_jobs_title']->value;?>
</h3>

<div id="jobsdiv">
</div>

<script type="text/javascript">
$(document).ready(function() {
    update_jobs();
});
</script>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>