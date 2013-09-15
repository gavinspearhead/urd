<?php /* Smarty version Smarty-3.1.14, created on 2013-08-09 23:38:27
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_get_image.tpl" */ ?>
<?php /*%%SmartyHeaderCode:64439589352056153c10f49-58337985%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '341e21a5b2c6c99672a2aa108d937fc63c8e4f80' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_get_image.tpl',
      1 => 1374016522,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '64439589352056153c10f49-58337985',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'size' => 0,
    'url' => 0,
    'width' => 0,
    'height' => 0,
    'firstidx' => 0,
    'first' => 0,
    'previousidx' => 0,
    'previous' => 0,
    'preview' => 0,
    'nextidx' => 0,
    'next' => 0,
    'lastidx' => 0,
    'last' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056153d3b0a0_09203247',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056153d3b0a0_09203247')) {function content_52056153d3b0a0_09203247($_smarty_tpl) {?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['title']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 (<?php echo $_smarty_tpl->tpl_vars['size']->value;?>
)</div>

<div class="center down3">
<img src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
" id="overlay_image" alt="" <?php if ($_smarty_tpl->tpl_vars['width']->value>0) {?>width="<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
"<?php }?> <?php if ($_smarty_tpl->tpl_vars['height']->value>0) {?>height="<?php echo $_smarty_tpl->tpl_vars['height']->value;?>
"<?php }?> onclick="javascript:jump('<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
');" class="buttonlike noborder"/>

<div class="centered">
<?php if ($_smarty_tpl->tpl_vars['firstidx']->value!=-1) {?>
<div class="firsticon iconsize inline buttonlike" onclick="javascript:show_image('<?php echo strtr($_smarty_tpl->tpl_vars['first']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
',<?php echo $_smarty_tpl->tpl_vars['firstidx']->value;?>
  );"></div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['previousidx']->value!=-1) {?>
<div class="previousicon iconsize inline buttonlike" onclick="javascript:show_image('<?php echo strtr($_smarty_tpl->tpl_vars['previous']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', <?php echo $_smarty_tpl->tpl_vars['previousidx']->value;?>
);"></div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['preview']->value!=1) {?>
<div class="foldericon iconsize inline buttonlike" onclick="hide_overlayed_content();"></div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['nextidx']->value!=-1) {?>
<div class="playicon iconsize inline buttonlike" onclick="javascript:show_image('<?php echo strtr($_smarty_tpl->tpl_vars['next']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', <?php echo $_smarty_tpl->tpl_vars['nextidx']->value;?>
);"></div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['lastidx']->value!=-1) {?>
<div class="lasticon iconsize inline buttonlike" onclick="javascript:show_image('<?php echo strtr($_smarty_tpl->tpl_vars['last']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', <?php echo $_smarty_tpl->tpl_vars['lastidx']->value;?>
);"></div>
<?php }?>
</div>
</div>

<?php }} ?>