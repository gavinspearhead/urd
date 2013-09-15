<?php /* Smarty version Smarty-3.1.14, created on 2013-08-10 23:02:16
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/barehead.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20445084105206aa58a51597-70940235%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd2503cf7c83ad1d168e2c4f837464f96f7923c7b' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/barehead.tpl',
      1 => 1374785601,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20445084105206aa58a51597-70940235',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'allow_robots' => 0,
    'JSDIR' => 0,
    'CSSDIR' => 0,
    'stylesheet' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5206aa58aec935_72016984',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206aa58aec935_72016984')) {function content_5206aa58aec935_72016984($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.capitalize.php';
if (!is_callable('smarty_modifier_replace')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.replace.php';
?><!DOCTYPE html>
<html>
<head>
<title><?php echo htmlspecialchars(smarty_modifier_capitalize($_smarty_tpl->tpl_vars['title']->value), ENT_QUOTES, 'UTF-8', true);?>
</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
<?php if (!$_smarty_tpl->tpl_vars['allow_robots']->value) {?>
<meta name="robots" content="noindex, nofollow"/>
<?php }?>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['JSDIR']->value;?>
/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['JSDIR']->value;?>
/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['JSDIR']->value;?>
/js.js"></script>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
/_basic.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
/<?php if ($_smarty_tpl->tpl_vars['stylesheet']->value!='') {?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['stylesheet']->value,".css",'');?>
/<?php echo $_smarty_tpl->tpl_vars['stylesheet']->value;?>
.css<?php } else { ?>light/light.css<?php }?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
/<?php if ($_smarty_tpl->tpl_vars['stylesheet']->value!='') {?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['stylesheet']->value,".css",'');?>
<?php } else { ?>/light<?php }?>/jquery-ui.css" type="text/css"/>
<!--[if IE]>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
/_iehacks.css" type="text/css"/>
<![endif]--> 
<link rel="SHORTCUT ICON" href="favicon.ico" type="image/x-icon"/>

</head>
<body>
<div class="Message hidden" id="message_bar" onclick="javascript:hide_message('message_bar', 0);"></div>
<div id="overlay_back">
<div id="overlay_content"></div>
</div>
<?php }} ?>