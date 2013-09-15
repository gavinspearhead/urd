<?php /* Smarty version Smarty-3.1.14, created on 2013-08-28 23:09:09
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_preview.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10081527985205614fa19ab3-92667022%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9e9a076f85350a2be76180ebc19e73e91a05b5e4' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_preview.tpl',
      1 => 1377699726,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10081527985205614fa19ab3-92667022',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5205614fc3d248_37732201',
  'variables' => 
  array (
    'title_str' => 0,
    'finished' => 0,
    'progress' => 0,
    'done_size' => 0,
    'dlsize' => 0,
    'nroffiles' => 0,
    'LN_preview_failed' => 0,
    'do_reload' => 0,
    'filetype' => 0,
    'path' => 0,
    'file' => 0,
    'isnzb' => 0,
    'LN_preview_autodisp' => 0,
    'LN_preview_autofail' => 0,
    'file_utf8' => 0,
    'LN_preview_view' => 0,
    'LN_uploaded' => 0,
    'LN_preview_nzb' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5205614fc3d248_37732201')) {function content_5205614fc3d248_37732201($_smarty_tpl) {?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['title_str']->value;?>
</div>
    <div class="centered">
<?php if ($_smarty_tpl->tpl_vars['finished']->value==0) {?>
    <div class="waitingimg centered"></div>
	<br/>
    <div class="centered inline">
    <span>
    <?php echo smarty_function_urd_progress(array('width'=>240,'complete'=>$_smarty_tpl->tpl_vars['progress']->value,'done'=>'progress_done','remain'=>'progress_remain'),$_smarty_tpl);?>

    <span class="progress floatleft">&nbsp;<?php echo $_smarty_tpl->tpl_vars['progress']->value;?>
%</span><br><span class="progress"> <?php echo $_smarty_tpl->tpl_vars['done_size']->value;?>
 / <?php echo $_smarty_tpl->tpl_vars['dlsize']->value;?>
</span>
    </span>
    <input type="hidden" id="title_str" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['title_str']->value, ENT_QUOTES, 'UTF-8', true);?>
"/>
    </div>
<?php } elseif ($_smarty_tpl->tpl_vars['finished']->value==-1||$_smarty_tpl->tpl_vars['nroffiles']->value==0) {?>
	<?php echo $_smarty_tpl->tpl_vars['LN_preview_failed']->value;?>
 
	<p>
    <input type="hidden" id="title_str" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['title_str']->value, ENT_QUOTES, 'UTF-8', true);?>
"/>
	</p>
<?php } else { ?>
    <div class="popup_wrapper700x400">
    <div class="popup_centered700x400">
	<?php if ($_smarty_tpl->tpl_vars['nroffiles']->value==1) {?> 
        <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['do_reload']->value;?>
" id="do_reload"/>
		<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['filetype']->value;?>
" id="filetype"/>
		<input type="hidden" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['file']->value, ENT_QUOTES, 'UTF-8', true);?>
" id="file"/>
		<?php if ($_smarty_tpl->tpl_vars['isnzb']->value==0) {?>
            <p>
			<?php echo $_smarty_tpl->tpl_vars['LN_preview_autodisp']->value;?>
<br/>
            </p>
			<?php echo $_smarty_tpl->tpl_vars['LN_preview_autofail']->value;?>
:<br/>
            <?php if ($_smarty_tpl->tpl_vars['filetype']->value=='image') {?>
                <span class="buttonlike" onclick="javascript:show_image('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['file']->value, ENT_QUOTES, 'UTF-8', true);?>
')";><?php echo $_smarty_tpl->tpl_vars['file_utf8']->value;?>
</span>
            <?php } elseif ($_smarty_tpl->tpl_vars['filetype']->value=='text') {?>
                <span class="buttonlike" onclick="javascript:show_contents('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['file']->value, ENT_QUOTES, 'UTF-8', true);?>
')";><?php echo $_smarty_tpl->tpl_vars['file_utf8']->value;?>
</span>
            <?php } else { ?>
                <span class="buttonlike" onclick="javascript:jump('getfile.php?preview=1&amp;file=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['file']->value, ENT_QUOTES, 'UTF-8', true);?>
')";><?php echo $_smarty_tpl->tpl_vars['file_utf8']->value;?>
</span>
            <?php }?>
            <input type="hidden" id="title_str" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['title_str']->value, ENT_QUOTES, 'UTF-8', true);?>
"/>
		<?php } else { ?>
			<?php echo $_smarty_tpl->tpl_vars['LN_preview_view']->value;?>
:<br/>
			<span class="buttonlike" onclick="javascript:jump('getfile.php?preview=1&amp;file=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['file']->value, ENT_QUOTES, 'UTF-8', true);?>
')";><?php echo $_smarty_tpl->tpl_vars['file_utf8']->value;?>
</span><br/>
			<p>
            <input type="hidden" id="uploaded_text" value="<?php echo $_smarty_tpl->tpl_vars['LN_uploaded']->value;?>
"/>
            <input type="hidden" id="uploaded_file" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['file']->value, ENT_QUOTES, 'UTF-8', true);?>
"/>
			<?php echo $_smarty_tpl->tpl_vars['LN_preview_nzb']->value;?>
:<br/>
			<span class="buttonlike" onclick="javascript:open_hidden_link('parsenzb.php?preview=1&amp;file=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['file']->value, ENT_QUOTES, 'UTF-8', true);?>
')";><?php echo $_smarty_tpl->tpl_vars['file_utf8']->value;?>
</span></p>
            <input type="hidden" id="title_str" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['title_str']->value, ENT_QUOTES, 'UTF-8', true);?>
"/>
		<?php }?>
	<?php } else { ?>
		<?php echo $_smarty_tpl->tpl_vars['LN_preview_autodisp']->value;?>
<br/>
        <input type="hidden" id="title_str" value=""/>
		<?php echo $_smarty_tpl->tpl_vars['LN_preview_autofail']->value;?>
:<br/>
		<span class="buttonlike" onclick="javascript:jump('viewfiles.php?dir=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8', true);?>
');"><?php echo $_smarty_tpl->tpl_vars['path']->value;?>
</span>
		<input type="hidden" name="redirect" id="redirect" value="viewfiles.php?dir=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8', true);?>
"/>
	<?php }?>
    </div>
    </div>
<?php }?>
<p>

</p>
</div>
<?php }} ?>