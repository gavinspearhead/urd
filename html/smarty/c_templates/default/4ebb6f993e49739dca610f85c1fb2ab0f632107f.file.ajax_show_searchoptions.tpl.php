<?php /* Smarty version Smarty-3.1.14, created on 2013-09-04 23:39:12
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_show_searchoptions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20818980605206b55d568735-36905082%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4ebb6f993e49739dca610f85c1fb2ab0f632107f' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_show_searchoptions.tpl',
      1 => 1378156929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20818980605206b55d568735-36905082',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5206b55d9f6234_12653870',
  'variables' => 
  array (
    'sort' => 0,
    'sort_dir' => 0,
    'up' => 0,
    'down' => 0,
    'LN_name' => 0,
    'name_sort' => 0,
    'LN_buttons_url' => 0,
    'search_url_sort' => 0,
    'LN_actions' => 0,
    'buttons' => 0,
    'button' => 0,
    'maxstrlen' => 0,
    'LN_buttons_clicktest' => 0,
    'LN_buttons_edit' => 0,
    'LN_delete' => 0,
    'LN_error_nosearchoptionsfound' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206b55d9f6234_12653870')) {function content_5206b55d9f6234_12653870($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.truncate.php';
if (!is_callable('smarty_modifier_replace')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.replace.php';
?>


<?php $_smarty_tpl->tpl_vars['up'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_up.png' alt=''/>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['down'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_down.png' alt=''/>", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="name") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="search_url") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['search_url_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['search_url_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['search_url_sort'] = new Smarty_variable('', null, 0);?><?php }?>

<table class="articles">
<tr>
<th onclick="javascript:show_buttons('name');" class="head buttonlike round_left"><?php echo $_smarty_tpl->tpl_vars['LN_name']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['name_sort']->value;?>
</th>
<th onclick="javascript:show_buttons('search_url');" class="head buttonlike"><?php echo $_smarty_tpl->tpl_vars['LN_buttons_url']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['search_url_sort']->value;?>
</th>
<th class="head right round_right"><?php echo $_smarty_tpl->tpl_vars['LN_actions']->value;?>
</th>
</tr>

<?php  $_smarty_tpl->tpl_vars['button'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['button']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['buttons']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['button']->key => $_smarty_tpl->tpl_vars['button']->value) {
$_smarty_tpl->tpl_vars['button']->_loop = true;
?>
<tr class="even content" onmouseover="javascript:ToggleClass(this,'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');">
<td><?php echo smarty_modifier_truncate(htmlspecialchars($_smarty_tpl->tpl_vars['button']->value->get_name(), ENT_QUOTES, 'UTF-8', true),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</td>
<td class="buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_buttons_clicktest']->value),$_smarty_tpl);?>
 onclick="javascript:jump('<?php echo strtr(smarty_modifier_replace($_smarty_tpl->tpl_vars['button']->value->get_url(),"\$q","test"), array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', true);"><?php echo smarty_modifier_truncate(htmlspecialchars($_smarty_tpl->tpl_vars['button']->value->get_url(), ENT_QUOTES, 'UTF-8', true),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</td>
<td>
<div class="floatright">
<div class="inline iconsizeplus editicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_buttons_edit']->value),$_smarty_tpl);?>
 onclick="javascript:buttons_action('edit',<?php echo $_smarty_tpl->tpl_vars['button']->value->get_id();?>
);"></div>
<div class="inline iconsizeplus deleteicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_delete']->value),$_smarty_tpl);?>
 onclick="javascript:buttons_action_confirm('delete_button', <?php echo $_smarty_tpl->tpl_vars['button']->value->get_id();?>
, '<?php echo $_smarty_tpl->tpl_vars['LN_delete']->value;?>
 <?php echo strtr($_smarty_tpl->tpl_vars['button']->value->get_name(), array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');"></div>
</div>
</td>
</tr>
<?php }
if (!$_smarty_tpl->tpl_vars['button']->_loop) {
?>
<tr><td colspan="8" class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_nosearchoptionsfound']->value;?>
</td></tr>
<?php } ?>
</table>
<input type="hidden" name="sort_dir" id="sort_dir" value="<?php echo $_smarty_tpl->tpl_vars['sort_dir']->value;?>
"/>
<input type="hidden" name="sort" id="sort" value="<?php echo $_smarty_tpl->tpl_vars['sort']->value;?>
"/>

<?php }} ?>