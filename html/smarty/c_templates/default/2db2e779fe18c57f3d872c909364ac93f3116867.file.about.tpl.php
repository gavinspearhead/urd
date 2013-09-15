<?php /* Smarty version Smarty-3.1.14, created on 2013-08-10 00:37:23
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/about.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8719904452056f23a34476-60634450%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2db2e779fe18c57f3d872c909364ac93f3116867' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/about.tpl',
      1 => 1342733936,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8719904452056f23a34476-60634450',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_urdname' => 0,
    'VERSION' => 0,
    'status' => 0,
    'copyright' => 0,
    'LN_website' => 0,
    'url' => 0,
    'LN_abouttext1' => 0,
    'LN_abouttext2' => 0,
    'LN_abouttext3' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056f23b5b701_85107784',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056f23b5b701_85107784')) {function content_52056f23b5b701_85107784($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<div id="textcontent">
<div class="urdlogo2 floatright noborder buttonlike" onclick="javascript:jump('index.php');"></div>
<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_urdname']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
)</h3>

<p><?php echo $_smarty_tpl->tpl_vars['copyright']->value;?>
</p>

<p><?php echo $_smarty_tpl->tpl_vars['LN_website']->value;?>
: <a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['url']->value;?>
</a></p>

<p><?php echo $_smarty_tpl->tpl_vars['LN_abouttext1']->value;?>
</p>
<p><?php echo $_smarty_tpl->tpl_vars['LN_abouttext2']->value;?>
</p>
<p><?php echo $_smarty_tpl->tpl_vars['LN_abouttext3']->value;?>
</p>
</div>
<br/>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>