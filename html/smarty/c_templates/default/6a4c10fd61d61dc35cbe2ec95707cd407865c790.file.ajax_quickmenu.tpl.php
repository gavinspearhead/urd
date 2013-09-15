<?php /* Smarty version Smarty-3.1.14, created on 2013-08-06 00:04:37
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_quickmenu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:190287114652002175ba7d55-56186867%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6a4c10fd61d61dc35cbe2ec95707cd407865c790' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_quickmenu.tpl',
      1 => 1372888561,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '190287114652002175ba7d55-56186867',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'items' => 0,
    'item' => 0,
    'subject' => 0,
    'srctype' => 0,
    'show_usenzb' => 0,
    'show_download' => 0,
    'message' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52002175d1fe83_94657424',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52002175d1fe83_94657424')) {function content_52002175d1fe83_94657424($_smarty_tpl) {?>



<div id="quickmenuinner">
<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['link'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['item']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['item']->iteration=0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["qm"]['total'] = $_smarty_tpl->tpl_vars['item']->total;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['link']->value = $_smarty_tpl->tpl_vars['item']->key;
 $_smarty_tpl->tpl_vars['item']->iteration++;
?>
<div id="quickmenuitem_<?php echo $_smarty_tpl->tpl_vars['item']->iteration;?>
" class="quickmenuitem"><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='quickmenu') {?><button class="quickmenubutton" onclick="javascript:ShowQuickMenu('<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
','<?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
', <?php echo $_smarty_tpl->tpl_vars['srctype']->value;?>
,event); return false;"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='quickdisplay') {?><button class="quickmenubutton" onclick="javascript:ShowQuickDisplay('<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
','<?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
',event, <?php echo $_smarty_tpl->tpl_vars['srctype']->value;?>
);CloseQuickMenu(); return false;"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='newpage') {?><button class="quickmenubutton" onclick="javascript:document.location.href='<?php echo $_smarty_tpl->tpl_vars['item']->value->extra;?>
'" class="buttonlike"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='searchbutton') {?><button class="quickmenubutton" onclick="javascript:search_button('<?php echo $_smarty_tpl->tpl_vars['item']->value->extra['search_url'];?>
','<?php echo strtr($_smarty_tpl->tpl_vars['item']->value->extra['name'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');CloseQuickMenu(); return false;"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='nfopreview'||$_smarty_tpl->tpl_vars['item']->value->type=='imgpreview'||($_smarty_tpl->tpl_vars['item']->value->type=='nzbpreview'&&$_smarty_tpl->tpl_vars['show_usenzb']->value!=0&&$_smarty_tpl->tpl_vars['show_download']->value!=0)||$_smarty_tpl->tpl_vars['item']->value->type=='vidpreview') {?><button class="quickmenubutton" onclick="javascript:select_preview('<?php echo $_smarty_tpl->tpl_vars['item']->value->extra['binaryID'];?>
','<?php echo $_smarty_tpl->tpl_vars['item']->value->extra['groupID'];?>
');CloseQuickMenu();"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='guessextsetinfosafe') {?><button class="quickmenubutton" onclick="javascript:GuessExtSetInfoSafe('<?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
', <?php echo $_smarty_tpl->tpl_vars['srctype']->value;?>
);"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='guessbasketextsetinfo') {?><button class="quickmenubutton" onclick="javascript:GuessBasketExtSetInfo('<?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
', <?php echo $_smarty_tpl->tpl_vars['srctype']->value;?>
);"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='guessextsetinfo') {?><button class="quickmenubutton" onclick="javascript:GuessExtSetInfo('<?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
', <?php echo $_smarty_tpl->tpl_vars['srctype']->value;?>
)"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='report_spam') {?><button class="quickmenubutton" onclick="javascript:report_spam('<?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
');CloseQuickMenu();"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='add_blacklist') {?><button class="quickmenubutton" onclick="javascript:add_blacklist('<?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
');CloseQuickMenu();"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='add_search') {?><button class="quickmenubutton" onclick="javascript:add_search('search');CloseQuickMenu();"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='add_block') {?><button class="quickmenubutton" onclick="javascript:add_search('block');CloseQuickMenu();"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='urd_search') {?><button class="quickmenubutton" onclick="javascript:urd_search();CloseQuickMenu();"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='hide_set') {?><button class="quickmenubutton" onclick="javascript:markRead('<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
', 'hide', '<?php echo $_smarty_tpl->tpl_vars['srctype']->value;?>
');CloseQuickMenu();"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='unhide_set') {?><button class="quickmenubutton" onclick="javascript:markRead('<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
', 'unhide', '<?php echo $_smarty_tpl->tpl_vars['srctype']->value;?>
');CloseQuickMenu();"><?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value->type=='follow_link') {?><button class="quickmenubutton" onclick="javascript:follow_link('<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
', 'unhide', '<?php echo $_smarty_tpl->tpl_vars['srctype']->value;?>
');CloseQuickMenu();"><?php }?><?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
</button>
</div>
<?php } ?>
</div>
<input type="hidden" id="nrofquickmenuitems" value="<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['qm']['total'];?>
"/>
<?php if (isset($_smarty_tpl->tpl_vars['message']->value)&&$_smarty_tpl->tpl_vars['message']->value!=='') {?>
<h2><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</h2>
<?php }?>
<?php }} ?>