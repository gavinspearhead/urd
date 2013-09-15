<?php /* Smarty version Smarty-3.1.14, created on 2013-08-09 23:43:52
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/viewfiles.tpl" */ ?>
<?php /*%%SmartyHeaderCode:47655323052056298bcfe05-06340088%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7a08a40a66db0e14ea3d8578864476579bd5be54' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/viewfiles.tpl',
      1 => 1373322628,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '47655323052056298bcfe05-06340088',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_viewfilesheading' => 0,
    'directory' => 0,
    'maxstrlen' => 0,
    'LN_search' => 0,
    'LN_loading_files' => 0,
    'show_usenzb' => 0,
    'perpage' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056298cbeb36_32536210',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056298cbeb36_32536210')) {function content_52056298cbeb36_32536210($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.truncate.php';
?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_viewfilesheading']->value;?>
: <span id="directory_top"><?php echo smarty_modifier_truncate(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['directory']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'),$_smarty_tpl->tpl_vars['maxstrlen']->value,'...',false,true);?>
</span></h3>
<div>
<input type="text" name="search" size="30" value="&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;" id="search"/>
<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="submitsmall" onclick="javascript:show_files_clean();"/>
</div>
<div id="viewfilesdiv">

<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>

<h3 class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_loading_files']->value;?>
</h3>
</div>
<script type="text/javascript">
$(document).ready(function() {
    show_files( { 'curdir':'<?php echo strtr($_smarty_tpl->tpl_vars['directory']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
' } );
    set_scroll_handler('#contentout', show_files);
});
</script>
</div>

<?php if ($_smarty_tpl->tpl_vars['show_usenzb']->value) {?>
<div id="uploadnzbdiv" class="uploadnzboff">
</div>
<?php }?>

<input type="hidden" id="perpage" value="<?php echo $_smarty_tpl->tpl_vars['perpage']->value;?>
"/>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>