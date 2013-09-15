<?php /* Smarty version Smarty-3.1.14, created on 2013-09-11 00:07:10
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showextsetinfo.tpl" */ ?>
<?php /*%%SmartyHeaderCode:205407146152002176bd76e6-50262991%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '10517703ce75e833a87f1fc7a6dc215c1d0dda86' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showextsetinfo.tpl',
      1 => 1378849282,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '205407146152002176bd76e6-50262991',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52002176dfe8d7_93228730',
  'variables' => 
  array (
    'setname' => 0,
    'srctype' => 0,
    'LN_error_error' => 0,
    'message' => 0,
    'setID' => 0,
    'binarytype' => 0,
    'LN_showsetinfo_typeofbinary' => 0,
    'type' => 0,
    'binarytypes' => 0,
    'binid' => 0,
    'bintype' => 0,
    'display' => 0,
    'vals' => 0,
    'opt' => 0,
    'LN_apply' => 0,
    'files' => 0,
    'file' => 0,
    'groupID' => 0,
    'LN_preview' => 0,
    'looped' => 0,
    'LN_showsetinfo_postedin' => 0,
    'USERSETTYPE_GROUP' => 0,
    'groupname' => 0,
    'fromnames' => 0,
    'LN_showsetinfo_postedby' => 0,
    'LN_showsetinfo_size' => 0,
    'binaries' => 0,
    'LN_files' => 0,
    'articlesmax' => 0,
    'LN_showsetinfo_shouldbe' => 0,
    'totalsize' => 0,
    'par2s' => 0,
    'LN_showsetinfo_par2' => 0,
    'extsetoverview' => 0,
    'fileoverview' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52002176dfe8d7_93228730')) {function content_52002176dfe8d7_93228730($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.capitalize.php';
?>



<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['setname']->value, ENT_QUOTES, 'UTF-8', true);?>
</div>
<div class="sets_inner" id="td_sets">

<?php if ($_smarty_tpl->tpl_vars['srctype']->value=='error') {?>
<div>
<?php echo $_smarty_tpl->tpl_vars['LN_error_error']->value;?>
: <?php echo $_smarty_tpl->tpl_vars['message']->value;?>

</div>
<?php } elseif ($_smarty_tpl->tpl_vars['srctype']->value=='edit') {?>
<div>
	<input type="hidden" id="extsetinfodisplay:<?php echo $_smarty_tpl->tpl_vars['setID']->value;?>
" value="edit"/>
	<form id="ext_setinfo_<?php echo $_smarty_tpl->tpl_vars['setID']->value;?>
" method="post">
	<div><input type="hidden" name="values[binarytype]" value="<?php echo $_smarty_tpl->tpl_vars['binarytype']->value;?>
"/></div>
<table class="set_details ">
		<tr class="comment"><td class="bold">
		<?php echo $_smarty_tpl->tpl_vars['LN_showsetinfo_typeofbinary']->value;?>
:
		</td><td class="extsetinput">
			<select onchange="SaveExtSetBinaryType('<?php echo $_smarty_tpl->tpl_vars['setID']->value;?>
',this,'save', <?php echo $_smarty_tpl->tpl_vars['type']->value;?>
);" name="binarytype">
			<?php  $_smarty_tpl->tpl_vars['bintype'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bintype']->_loop = false;
 $_smarty_tpl->tpl_vars['binid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['binarytypes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['bintype']->key => $_smarty_tpl->tpl_vars['bintype']->value) {
$_smarty_tpl->tpl_vars['bintype']->_loop = true;
 $_smarty_tpl->tpl_vars['binid']->value = $_smarty_tpl->tpl_vars['bintype']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['binid']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['binid']->value==$_smarty_tpl->tpl_vars['binarytype']->value) {?>selected="selected"<?php }?> ><?php echo $_smarty_tpl->tpl_vars['bintype']->value;?>
</option>
			<?php } ?>
			</select><br/>
		</td></tr>
	<?php  $_smarty_tpl->tpl_vars['vals'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['vals']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['display']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['vals']->key => $_smarty_tpl->tpl_vars['vals']->value) {
$_smarty_tpl->tpl_vars['vals']->_loop = true;
?>
		<tr class="comment"><td class="bold"><?php echo $_smarty_tpl->tpl_vars['vals']->value['name'];?>
:</td>
		<td class="extsetinput">
			<?php if ($_smarty_tpl->tpl_vars['vals']->value['edit']=='longtext') {?><input size="60" type="text" name="values[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['field'], ENT_QUOTES, 'UTF-8', true);?>
]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
"/><?php }?>
			<?php if ($_smarty_tpl->tpl_vars['vals']->value['edit']=='text') {?><input size="25" type="text" name="values[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['field'], ENT_QUOTES, 'UTF-8', true);?>
]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
"/><?php }?>
			<?php if ($_smarty_tpl->tpl_vars['vals']->value['edit']=='checkbox') {?>
				
                <?php ob_start();?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['field'], ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['field'], ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp2=ob_get_clean();?><?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['vals']->value['value']),'name'=>"values[".$_tmp1."]",'id'=>"values_".$_tmp2),$_smarty_tpl);?>
 
                <?php }?>
			<?php if ($_smarty_tpl->tpl_vars['vals']->value['edit']=='select') {?>
			<select name="values[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['field'], ENT_QUOTES, 'UTF-8', true);?>
]">
				<?php  $_smarty_tpl->tpl_vars['opt'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['opt']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['vals']->value['editvalues']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['opt']->key => $_smarty_tpl->tpl_vars['opt']->value) {
$_smarty_tpl->tpl_vars['opt']->_loop = true;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['opt']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['opt']->value==$_smarty_tpl->tpl_vars['vals']->value['value']) {?> selected="selected"<?php }?>><?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['opt']->value);?>
</option>
				<?php } ?>
			</select>
			<?php }?>
		</td></tr>
	<?php } ?>
    <tr class="comment"><td colspan="2">&nbsp;</td>
	<tr class="comment"><td colspan="2" class="center">
	<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_apply']->value;?>
" class="submit" name="submit_button" onclick="javascript:SaveExtSetInfo('<?php echo $_smarty_tpl->tpl_vars['setID']->value;?>
', <?php echo $_smarty_tpl->tpl_vars['type']->value;?>
);"/>
	</td></tr>
	</table>
	</form>
</div>	
<?php } else { ?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'fileoverview', null); ob_start(); ?>
<?php  $_smarty_tpl->tpl_vars['file'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['file']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['file']->key => $_smarty_tpl->tpl_vars['file']->value) {
$_smarty_tpl->tpl_vars['file']->_loop = true;
?>
<tr class="small vbot">
<td class="preview" colspan="2">
<div class="inline iconsize previewicon buttonlike" onclick="select_preview('<?php echo $_smarty_tpl->tpl_vars['file']->value['binaryID'];?>
','<?php echo $_smarty_tpl->tpl_vars['groupID']->value;?>
')" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_preview']->value),$_smarty_tpl);?>
></div>
	<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['file']->value['cleanfilename'], ENT_QUOTES, 'UTF-8', true);?>

    <div class="floatright"><?php echo $_smarty_tpl->tpl_vars['file']->value['size'];?>
</div>
	</td>
</tr>
<?php } ?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'extsetoverview', null); ob_start(); ?>
	<?php $_smarty_tpl->tpl_vars['looped'] = new Smarty_variable(0, null, 0);?>
	<?php  $_smarty_tpl->tpl_vars['vals'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['vals']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['display']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['vals']->key => $_smarty_tpl->tpl_vars['vals']->value) {
$_smarty_tpl->tpl_vars['vals']->_loop = true;
?>
	<?php if ($_smarty_tpl->tpl_vars['vals']->value['value']!="0"&&$_smarty_tpl->tpl_vars['vals']->value['value']!=''&&$_smarty_tpl->tpl_vars['vals']->value['value']!="name") {?>
		<?php $_smarty_tpl->tpl_vars['looped'] = new Smarty_variable(((string) $_smarty_tpl->tpl_vars['looped']->value+1), null, 0);?>
		<tr class="vtop small comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['vals']->value['name'];?>
:</td><td>
		<?php if ($_smarty_tpl->tpl_vars['vals']->value['display']=='text') {?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['vals']->value['display']=='url') {?><span class="buttonlike" onclick="javascript:jump('<?php echo strtr($_smarty_tpl->tpl_vars['vals']->value['value'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
',1);"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
</span><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['vals']->value['display']=='number') {?><b><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
</b><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['vals']->value['display']=='checkbox') {?><?php if ($_smarty_tpl->tpl_vars['vals']->value['value']==1) {?>Yes<?php } else { ?>No<?php }?><?php }?>
		</td></tr>
	<?php }?>
	<?php } ?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<table class="set_details ">

<tr class="vtop small left comment"><td class="nowrap bold" ><?php echo $_smarty_tpl->tpl_vars['LN_showsetinfo_postedin']->value;?>
:</td>
	<td class="buttonlike" onclick="javascript: load_sets(
        <?php if ($_smarty_tpl->tpl_vars['type']->value==$_smarty_tpl->tpl_vars['USERSETTYPE_GROUP']->value) {?>
            { 'group_id':'group_<?php echo strtr($_smarty_tpl->tpl_vars['groupID']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
' }
        <?php } else { ?>   
            { 'feed_id':'feed_<?php echo strtr($_smarty_tpl->tpl_vars['groupID']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
' }
        <?php }?>    
      );"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['groupname']->value, ENT_QUOTES, 'UTF-8', true);?>

    </td></tr>
<?php if ($_smarty_tpl->tpl_vars['fromnames']->value!='') {?>
<tr class="vtop small left comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_showsetinfo_postedby']->value;?>
:</td>
	<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['fromnames']->value, ENT_QUOTES, 'UTF-8', true);?>
</td></tr> 
<?php }?>
<tr class="vtop small left comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_showsetinfo_size']->value;?>
:</td>
	<td><?php if ($_smarty_tpl->tpl_vars['binaries']->value>0) {?><?php echo $_smarty_tpl->tpl_vars['binaries']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['LN_files']->value;?>
<?php if ($_smarty_tpl->tpl_vars['articlesmax']->value>0) {?> (<?php echo $_smarty_tpl->tpl_vars['LN_showsetinfo_shouldbe']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['articlesmax']->value;?>
)<?php }?> - <?php }?><?php if ($_smarty_tpl->tpl_vars['totalsize']->value>0) {?><?php echo $_smarty_tpl->tpl_vars['totalsize']->value;?>
<?php } else { ?>?<?php }?></td></tr>
<?php if ($_smarty_tpl->tpl_vars['par2s']->value!='') {?>
	<tr class="vtop small left comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_showsetinfo_par2']->value;?>
</td>
	<td><?php echo $_smarty_tpl->tpl_vars['par2s']->value;?>
</td></tr>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['looped']->value>0) {?>
<?php echo $_smarty_tpl->tpl_vars['extsetoverview']->value;?>

<?php }?>


<tr class="comment"><td colspan="2"><br/></td></tr>

<?php echo $_smarty_tpl->tpl_vars['fileoverview']->value;?>


</table>

<?php }?>
</div>
<?php }} ?>