<?php /* Smarty version Smarty-3.1.14, created on 2013-08-29 23:16:14
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/update_db.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1521838039521fba1e8dfcb2-76160237%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5a8291fa431265b7a20afa3cf02a9609ebfec187' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/update_db.tpl',
      1 => 1366489953,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1521838039521fba1e8dfcb2-76160237',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CSSDIR' => 0,
    'stylesheet' => 0,
    'LN_update_database' => 0,
    'JSDIR' => 0,
    'LN_loading' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_521fba1e9ac8a8_18373701',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_521fba1e9ac8a8_18373701')) {function content_521fba1e9ac8a8_18373701($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.replace.php';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
/_basic.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
/<?php if ($_smarty_tpl->tpl_vars['stylesheet']->value!='') {?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['stylesheet']->value,".css",'');?>
/<?php echo $_smarty_tpl->tpl_vars['stylesheet']->value;?>
.css<?php } else { ?>light/light.css<?php }?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
/<?php if ($_smarty_tpl->tpl_vars['stylesheet']->value!='') {?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['stylesheet']->value,".css",'');?>
<?php } else { ?>/light<?php }?>/jquery-ui.css" type="text/css"/>
<link rel="SHORTCUT ICON" href="favicon.ico" type="image/x-icon"/>
<title><?php echo $_smarty_tpl->tpl_vars['LN_update_database']->value;?>
</title>
<meta http-equiv="Content-Language" content="en-us"/>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
<meta name="resource-type" content="document"/>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['JSDIR']->value;?>
/js.js"></script>
</head>
<body>
<div class="Message hidden attop" id="message_bar"></div>
<p></p>
<div id="contentout">
<div id="textcontent">
<div class="closebutton buttonlike noborder fixedright down5" onclick="javascript:history.go(-1);"></div>
<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_update_database']->value;?>
</h3>
<div id="updatedbdiv">
<?php echo $_smarty_tpl->tpl_vars['LN_loading']->value;?>

</div>
<script type="text/javascript">
start_updatedb();
</script>
</div>
</div>
</body>
</html>
<script type="text/javascript">
</script>



<?php }} ?>