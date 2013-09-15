<?php /* Smarty version Smarty-3.1.14, created on 2013-09-01 23:27:34
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19913106305200217aece420-22888653%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd6f8554c9aff3e9d4c3c57199e451bce823cbed0' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_settings.tpl',
      1 => 1378046269,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19913106305200217aece420-22888653',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5200217b335e55_75077231',
  'variables' => 
  array (
    'pref_list' => 0,
    'atab' => 0,
    'selector' => 0,
    'challenge' => 0,
    'pref' => 0,
    'blocks' => 0,
    'block' => 0,
    'level' => 0,
    'hidden' => 0,
    'popup_text' => 0,
    'b_type' => 0,
    'LN_password_weak' => 0,
    'LN_password_medium' => 0,
    'LN_password_strong' => 0,
    'LN_password_correct' => 0,
    'LN_password_incorrect' => 0,
    'name' => 0,
    'opts' => 0,
    'k' => 0,
    'q' => 0,
    'IMGDIR' => 0,
    'LN_error_error' => 0,
    'length' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5200217b335e55_75077231')) {function content_5200217b335e55_75077231($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.replace.php';
if (!is_callable('smarty_modifier_escape')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.escape.php';
if (!is_callable('smarty_modifier_capitalize')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.capitalize.php';
if (!is_callable('smarty_function_html_options')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.html_options.php';
?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'selector', null); ob_start(); ?>
<div class="pref_selector">

<div class="tabs">
<?php  $_smarty_tpl->tpl_vars['atab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['atab']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pref_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['atab']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['atab']->key => $_smarty_tpl->tpl_vars['atab']->value) {
$_smarty_tpl->tpl_vars['atab']->_loop = true;
 $_smarty_tpl->tpl_vars['atab']->index++;
 $_smarty_tpl->tpl_vars['atab']->first = $_smarty_tpl->tpl_vars['atab']->index === 0;
?>
<?php if ($_smarty_tpl->tpl_vars['atab']->value->length>0) {?>
<span id="<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['atab']->value->tabname,' ','');?>
_bar">
<span id="<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['atab']->value->tabname,' ','');?>
_bar_elem" onclick="javascript:select_tab_setting('<?php echo smarty_modifier_replace(strtr($_smarty_tpl->tpl_vars['atab']->value->tabname, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" )),' ','');?>
')"  class="tab<?php if ($_smarty_tpl->tpl_vars['atab']->first) {?> tab_selected<?php }?>" ><?php echo $_smarty_tpl->tpl_vars['atab']->value->name;?>

<input type="hidden" name="tabs" value="<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['atab']->value->tabname,' ','');?>
"/>
</span>
</span>
<?php }?>
<?php } ?>
</div>

</div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php echo $_smarty_tpl->tpl_vars['selector']->value;?>

<div class="prefix_prefs">
<input type="hidden" id="current_tab" name="current_tab" value=""/>
<input type="hidden" name="challenge" value="<?php echo $_smarty_tpl->tpl_vars['challenge']->value;?>
"/>
<input type="hidden" name="current_pref_level" id="current_pref_level"/>
<input type="hidden" value="" name="cmd" id="submittype"/>
</div>
<?php  $_smarty_tpl->tpl_vars['pref'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pref']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pref_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['pref']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['pref']->key => $_smarty_tpl->tpl_vars['pref']->value) {
$_smarty_tpl->tpl_vars['pref']->_loop = true;
 $_smarty_tpl->tpl_vars['pref']->index++;
 $_smarty_tpl->tpl_vars['pref']->first = $_smarty_tpl->tpl_vars['pref']->index === 0;
?>
<table class="preferences<?php if (!$_smarty_tpl->tpl_vars['pref']->first) {?> hidden<?php }?>" id="<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['pref']->value->tabname,' ','');?>
_tab">
<thead>
<tr>
<th colspan="2" class="head round_both">&nbsp;</th>
</tr>
</thead>
<?php $_smarty_tpl->tpl_vars['blocks'] = new Smarty_variable($_smarty_tpl->tpl_vars['pref']->value->value, null, 0);?>
<?php $_smarty_tpl->tpl_vars['length'] = new Smarty_variable(0, null, 0);?>
<?php  $_smarty_tpl->tpl_vars['block'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['block']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['blocks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['block']->key => $_smarty_tpl->tpl_vars['block']->value) {
$_smarty_tpl->tpl_vars['block']->_loop = true;
?>
<?php $_smarty_tpl->tpl_vars['b_type'] = new Smarty_variable($_smarty_tpl->tpl_vars['block']->value->get_type(), null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['block']->value->level>$_smarty_tpl->tpl_vars['level']->value) {?>
    <?php $_smarty_tpl->tpl_vars['hidden'] = new Smarty_variable("hidden", null, 0);?>
<?php } else { ?>
    <?php $_smarty_tpl->tpl_vars['length'] = new Smarty_variable(1, null, 0);?>
    <?php $_smarty_tpl->tpl_vars['hidden'] = new Smarty_variable('', null, 0);?>
<?php }?>
<tr class="<?php echo $_smarty_tpl->tpl_vars['hidden']->value;?>
 even content <?php if ($_smarty_tpl->tpl_vars['block']->value->tr_class!='') {?> <?php echo $_smarty_tpl->tpl_vars['block']->value->tr_class;?>
 <?php }?>" 
        <?php if ($_smarty_tpl->tpl_vars['block']->value->tr_id!='') {?>id="<?php echo $_smarty_tpl->tpl_vars['block']->value->tr_id;?>
" <?php }?>
        onmouseover="javascript:ToggleClass(this,'highlight2')" onmouseout="javascript:ToggleClass(this,'highlight2')" 
>
<?php $_smarty_tpl->tpl_vars['popup_text'] = new Smarty_variable(htmlspecialchars($_smarty_tpl->tpl_vars['block']->value->popup, ENT_QUOTES, 'UTF-8', true), null, 0);?>
<td class="settings vtop"<?php if ($_smarty_tpl->tpl_vars['popup_text']->value!='') {?><?php echo smarty_function_urd_popup(array('text'=>$_smarty_tpl->tpl_vars['popup_text']->value),$_smarty_tpl);?>
<?php }?>>
<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['block']->value->text;?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1!='') {?>
	<?php echo $_smarty_tpl->tpl_vars['block']->value->text;?>
:
<?php }?>
</td>
<td>
<?php if ($_smarty_tpl->tpl_vars['b_type']->value=="plain") {?> 
	<b><?php echo $_smarty_tpl->tpl_vars['block']->value->value;?>
 </b>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['b_type']->value=="password") {?>
	<input type="password" name="<?php echo $_smarty_tpl->tpl_vars['block']->value->name;?>
" id="<?php echo $_smarty_tpl->tpl_vars['block']->value->name;?>
" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['block']->value->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" size="<?php echo $_smarty_tpl->tpl_vars['block']->value->size;?>
" <?php echo $_smarty_tpl->tpl_vars['block']->value->javascript;?>
 />&nbsp;&nbsp; 
    <div class="floatright iconsizeplus sadicon buttonlike" onclick="javascript:toggle_show_password('<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->name, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');"></div>
    <span id="pw_message_<?php echo $_smarty_tpl->tpl_vars['block']->value->name;?>
" class="italic"></span>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['b_type']->value=="password_submit") {?>
<input type="button" id="<?php echo $_smarty_tpl->tpl_vars['block']->value->id;?>
" value="<?php echo $_smarty_tpl->tpl_vars['block']->value->value;?>
"/>
    <span id="pwweak" class="hidden italic"><br><?php echo $_smarty_tpl->tpl_vars['LN_password_weak']->value;?>
</span>
    <span id="pwmedium" class="hidden italic"><br><?php echo $_smarty_tpl->tpl_vars['LN_password_medium']->value;?>
</span>
    <span id="pwstrong" class="hidden italic"><br><?php echo $_smarty_tpl->tpl_vars['LN_password_strong']->value;?>
</span>
    <span id="pwcorrect" class="hidden italic"><br><?php echo $_smarty_tpl->tpl_vars['LN_password_correct']->value;?>
</span>
    <span id="pwincorrect" class="hidden italic"><br><?php echo $_smarty_tpl->tpl_vars['LN_password_incorrect']->value;?>
</span>
<script>
handle_passwords_change('<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->opw_id, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', '<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->npw_id1, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', '<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->npw_id2, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
','<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->tr_id, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', '<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->username, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');
</script>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['b_type']->value=="text") {?>
	<input type="text" name="<?php echo $_smarty_tpl->tpl_vars['block']->value->name;?>
" id="<?php echo $_smarty_tpl->tpl_vars['block']->value->id;?>
" 
    value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['block']->value->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" 
    size="<?php echo $_smarty_tpl->tpl_vars['block']->value->size;?>
" 
    <?php echo $_smarty_tpl->tpl_vars['block']->value->javascript;?>
 
    onchange="javascript:update_setting('<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->id, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', '<?php echo strtr($_smarty_tpl->tpl_vars['b_type']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');"/>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['b_type']->value=="checkbox") {?>
    <?php ob_start();?><?php echo strtr($_smarty_tpl->tpl_vars['block']->value->id, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
<?php $_tmp2=ob_get_clean();?><?php ob_start();?><?php echo strtr($_smarty_tpl->tpl_vars['b_type']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
<?php $_tmp3=ob_get_clean();?><?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['block']->value->toggle),'name'=>((string) $_smarty_tpl->tpl_vars['block']->value->name),'id'=>((string) $_smarty_tpl->tpl_vars['block']->value->id),'post_js'=>"update_setting('".$_tmp2."', '".$_tmp3."'); ".((string) $_smarty_tpl->tpl_vars['block']->value->javascript)),$_smarty_tpl);?>

<?php }?>
<?php if ($_smarty_tpl->tpl_vars['b_type']->value=="textarea") {?><?php $_smarty_tpl->tpl_vars['name'] = new Smarty_variable($_smarty_tpl->tpl_vars['block']->value->id, null, 0);?><input type="hidden" id="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
_orig_size" value="<?php echo $_smarty_tpl->tpl_vars['block']->value->rows;?>
"/><textarea name="<?php echo $_smarty_tpl->tpl_vars['block']->value->name;?>
" id="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
_select" rows="2" cols="<?php echo $_smarty_tpl->tpl_vars['block']->value->cols;?>
" <?php echo $_smarty_tpl->tpl_vars['block']->value->javascript;?>
onchange="javascript:update_setting('<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->id, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_select', '<?php echo strtr($_smarty_tpl->tpl_vars['b_type']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['block']->value->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</textarea>
    <div id="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
_collapse" class="floatright iconsize dynimgplus noborder buttonlike" onclick="javascript:collapse_select('<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
','rows');" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>((string) $_smarty_tpl->tpl_vars['LN_expand']->value)),$_smarty_tpl);?>
 >
    </div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['b_type']->value=="select") {?>
<?php $_smarty_tpl->tpl_vars['opts'] = new Smarty_variable($_smarty_tpl->tpl_vars['block']->value->options, null, 0);?>
<select name="<?php echo $_smarty_tpl->tpl_vars['block']->value->name;?>
" id="<?php echo $_smarty_tpl->tpl_vars['block']->value->id;?>
_select" 
onchange="javascript:update_setting('<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->id, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_select', '<?php echo strtr($_smarty_tpl->tpl_vars['b_type']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
'); <?php echo $_smarty_tpl->tpl_vars['block']->value->javascript;?>
">

<?php  $_smarty_tpl->tpl_vars['q'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['q']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['opts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['q']->key => $_smarty_tpl->tpl_vars['q']->value) {
$_smarty_tpl->tpl_vars['q']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['q']->key;
?>
<option value="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['k']->value, 'all');?>
"<?php if ($_smarty_tpl->tpl_vars['k']->value==$_smarty_tpl->tpl_vars['block']->value->selected) {?> selected="selected" <?php }?>><?php echo $_smarty_tpl->tpl_vars['q']->value;?>
</option>
<?php } ?>
</select>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['b_type']->value=="button") {?>
	<input type="button" class="submitsmall" name="<?php echo $_smarty_tpl->tpl_vars['block']->value->name;?>
" value="<?php echo $_smarty_tpl->tpl_vars['block']->value->value;?>
" <?php echo $_smarty_tpl->tpl_vars['block']->value->javascript;?>
 />
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['b_type']->value=="multiselect") {?>
    <?php $_smarty_tpl->tpl_vars['name'] = new Smarty_variable($_smarty_tpl->tpl_vars['block']->value->id, null, 0);?>
    <input type="hidden" id="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
_orig_size" value="<?php echo $_smarty_tpl->tpl_vars['block']->value->size;?>
"/>
    <select name="<?php echo $_smarty_tpl->tpl_vars['block']->value->name;?>
" id="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
_select" size="2" multiple="multiple" <?php echo $_smarty_tpl->tpl_vars['block']->value->javascript;?>
 
    onchange="javascript:update_setting('<?php echo strtr($_smarty_tpl->tpl_vars['name']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_select', '<?php echo strtr($_smarty_tpl->tpl_vars['b_type']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');"
    >
    <?php $_smarty_tpl->tpl_vars['opts'] = new Smarty_variable($_smarty_tpl->tpl_vars['block']->value->options_triple, null, 0);?>
    <?php  $_smarty_tpl->tpl_vars['q'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['q']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['opts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['q']->key => $_smarty_tpl->tpl_vars['q']->value) {
$_smarty_tpl->tpl_vars['q']->_loop = true;
?>
        <option value="<?php echo $_smarty_tpl->tpl_vars['q']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['q']->value['on']==1) {?> selected="selected" <?php }?> ><?php echo $_smarty_tpl->tpl_vars['q']->value['name'];?>
</option>
    <?php } ?>
    </select>
    <div id="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
_collapse" class="floatright iconsize dynimgplus noborder buttonlike" onclick="javascript:collapse_select('<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
','size');" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>smarty_modifier_capitalize(((string) $_smarty_tpl->tpl_vars['LN_expand']->value))),$_smarty_tpl);?>
 >
    </div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['b_type']->value=="period") {?>
    <?php $_smarty_tpl->tpl_vars['name'] = new Smarty_variable($_smarty_tpl->tpl_vars['block']->value->id, null, 0);?>
    <select name="<?php echo $_smarty_tpl->tpl_vars['block']->value->period_name;?>
" size="1" id="<?php echo $_smarty_tpl->tpl_vars['block']->value->period_name;?>
_select" class="update" <?php echo $_smarty_tpl->tpl_vars['block']->value->javascript;?>

    onchange="javascript:update_setting('<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->period_name, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_select', '<?php echo strtr($_smarty_tpl->tpl_vars['b_type']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', { 'time1':'<?php echo $_smarty_tpl->tpl_vars['block']->value->time1_name;?>
', 'time2':'<?php echo $_smarty_tpl->tpl_vars['block']->value->time2_name;?>
' <?php if ($_smarty_tpl->tpl_vars['block']->value->extra_name!='') {?> , 'extra':'<?php echo $_smarty_tpl->tpl_vars['block']->value->extra_name;?>
_select' <?php }?> } );"
    >
    <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['block']->value->period_keys,'output'=>$_smarty_tpl->tpl_vars['block']->value->period_texts,'selected'=>$_smarty_tpl->tpl_vars['block']->value->period_selected),$_smarty_tpl);?>
 
    </select> @
    <input type="text" id="<?php echo $_smarty_tpl->tpl_vars['block']->value->time1_name;?>
" name="<?php echo $_smarty_tpl->tpl_vars['block']->value->time1_name;?>
" id="<?php echo $_smarty_tpl->tpl_vars['block']->value->time1_name;?>
"  <?php if ($_smarty_tpl->tpl_vars['block']->value->time1_value>=0) {?>value="<?php echo $_smarty_tpl->tpl_vars['block']->value->time1_value;?>
"<?php }?> class="time" 
    onchange="javascript:update_setting('<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->period_name, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_select', '<?php echo strtr($_smarty_tpl->tpl_vars['b_type']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', { 'time1':'<?php echo $_smarty_tpl->tpl_vars['block']->value->time1_name;?>
', 'time2':'<?php echo $_smarty_tpl->tpl_vars['block']->value->time2_name;?>
' <?php if ($_smarty_tpl->tpl_vars['block']->value->extra_name!='') {?> , 'extra':'<?php echo $_smarty_tpl->tpl_vars['block']->value->extra_name;?>
_select' <?php }?> }  );"
    />:
    <input type="text" id="<?php echo $_smarty_tpl->tpl_vars['block']->value->time2_name;?>
" name="<?php echo $_smarty_tpl->tpl_vars['block']->value->time2_name;?>
" id="<?php echo $_smarty_tpl->tpl_vars['block']->value->time2_name;?>
" <?php if ($_smarty_tpl->tpl_vars['block']->value->time2_value!=='') {?>value="<?php echo sprintf("%02d",$_smarty_tpl->tpl_vars['block']->value->time2_value);?>
"<?php }?> class="time" 
    onchange="javascript:update_setting('<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->period_name, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_select', '<?php echo strtr($_smarty_tpl->tpl_vars['b_type']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', { 'time1':'<?php echo $_smarty_tpl->tpl_vars['block']->value->time1_name;?>
', 'time2':'<?php echo $_smarty_tpl->tpl_vars['block']->value->time2_name;?>
' <?php if ($_smarty_tpl->tpl_vars['block']->value->extra_name!='') {?> , 'extra':'<?php echo $_smarty_tpl->tpl_vars['block']->value->extra_name;?>
_select' <?php }?> }  );"
    />
    <?php if ($_smarty_tpl->tpl_vars['block']->value->extra_name!='') {?>
    <select name="<?php echo $_smarty_tpl->tpl_vars['block']->value->extra_name;?>
" size="1", id="<?php echo $_smarty_tpl->tpl_vars['block']->value->extra_name;?>
_select"
    onchange="javascript:update_setting('<?php echo strtr($_smarty_tpl->tpl_vars['block']->value->period_name, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_select', '<?php echo strtr($_smarty_tpl->tpl_vars['b_type']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', { 'time1':'<?php echo $_smarty_tpl->tpl_vars['block']->value->time1_name;?>
', 'time2':'<?php echo $_smarty_tpl->tpl_vars['block']->value->time2_name;?>
' <?php if ($_smarty_tpl->tpl_vars['block']->value->extra_name!='') {?> , 'extra':'<?php echo $_smarty_tpl->tpl_vars['block']->value->extra_name;?>
_select' <?php }?> }  );" >
        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['block']->value->extra_options,'selected'=>$_smarty_tpl->tpl_vars['block']->value->extra_selected),$_smarty_tpl);?>

    </select>
    <?php }?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['block']->value->error_msg['msg']!=" "&&$_smarty_tpl->tpl_vars['block']->value->error_msg['msg']!='') {?>
<img src="<?php echo $_smarty_tpl->tpl_vars['IMGDIR']->value;?>
/stop_mark.png" <?php echo smarty_function_urd_popup(array('text'=>mb_convert_encoding(htmlspecialchars(strtr($_smarty_tpl->tpl_vars['block']->value->error_msg['msg'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" )), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'),'caption'=>$_smarty_tpl->tpl_vars['LN_error_error']->value),$_smarty_tpl);?>
 alt="<?php echo $_smarty_tpl->tpl_vars['LN_error_error']->value;?>
" class="noborder"/>
<?php }?>
</td>
</tr>
<?php } ?>
<tr><td colspan="2" class="head">&nbsp;</td></tr>
</table>
<?php if ($_smarty_tpl->tpl_vars['length']->value==0) {?>
<script type="text/javascript">
$(document).ready(function() {
    add_class(document.getElementById('<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['pref']->value->tabname,' ','');?>
_bar'), 'hidden');
}) ;
</script>
<?php }?>

<?php } ?>
<div><br/></div>
<p>&nbsp;</p>


<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>