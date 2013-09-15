<?php /* Smarty version Smarty-3.1.14, created on 2013-08-06 00:04:36
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showminibasket.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9803686555200217486eb56-94185443%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f6ad1daa1cbf4909e51b8b329ad5b70a7fc113ae' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showminibasket.tpl',
      1 => 1375653238,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9803686555200217486eb56-94185443',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'nrofsets' => 0,
    'LN_browse_download' => 0,
    'LN_sets' => 0,
    'totalsize' => 0,
    'LN_browse_emptylist' => 0,
    'download_delay' => 0,
    'dl_dir' => 0,
    'add_setname' => 0,
    'dlsetname' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520021748e3955_83299662',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520021748e3955_83299662')) {function content_520021748e3955_83299662($_smarty_tpl) {?>


<?php if ($_smarty_tpl->tpl_vars['nrofsets']->value==0) {?>0<?php } else { ?><div class="inline iconsizeplus buttonlike cleartop3 downicon noborder" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_download']->value),$_smarty_tpl);?>
 onclick="javascript:Whichbutton('urddownload', event);"></div>&nbsp;<span onclick="javascript:update_basket_display(1);"><?php echo $_smarty_tpl->tpl_vars['nrofsets']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['LN_sets']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['totalsize']->value;?>
&nbsp;</span><div class="inline"><div class="inline iconsizeplus buttonlike cleartop3 purgeicon noborder" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_emptylist']->value),$_smarty_tpl);?>
 onclick="javascript:Whichbutton('clearbasket', event);"/></div></div><input name="timestamp" id="timestamp" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['download_delay']->value;?>
"/><input name="dl_dir" id="dl_dir" type="hidden" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['dl_dir']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/><input name="add_setname" id="add_setname" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['add_setname']->value;?>
"/><input name="dlsetname" id="dlsetname" type="hidden" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['dlsetname']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/><?php }?>

<?php }} ?>