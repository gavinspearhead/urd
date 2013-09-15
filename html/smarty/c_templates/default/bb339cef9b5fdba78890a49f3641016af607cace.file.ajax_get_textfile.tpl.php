<?php /* Smarty version Smarty-3.1.14, created on 2013-08-09 23:45:10
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_get_textfile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:706855126520562e61d7585-61204519%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bb339cef9b5fdba78890a49f3641016af607cace' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_get_textfile.tpl',
      1 => 1368825457,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '706855126520562e61d7585-61204519',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'size' => 0,
    'output' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520562e62640e3_72733272',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520562e62640e3_72733272')) {function content_520562e62640e3_72733272($_smarty_tpl) {?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered" id="text_title"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['title']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 (<?php echo $_smarty_tpl->tpl_vars['size']->value;?>
)</div>

<div class="overflow" id="inner_content">
<pre>
<?php echo $_smarty_tpl->tpl_vars['output']->value;?>

</pre>
</div>
</div>

<?php }} ?>