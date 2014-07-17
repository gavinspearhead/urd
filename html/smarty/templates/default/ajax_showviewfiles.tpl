{* Smarty *}
{*
 *  This file is part of Urd.
 *
 *  Urd is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  Urd is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. See the file "COPYING". If it does not
 *  exist, see <http://www.gnu.org/licenses/>.
 *
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showviewfiles.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}

{* Capture the skipper: *}
{capture assign=topskipper}
{if $lastpage neq 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages position=top js=submit_viewfiles_page extra_class="margin10"}
{else}<br/>
{/if}

{/capture}

{capture assign=bottomskipper}
{if $lastpage neq 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages position=bottom js=submit_viewfiles_page extra_class="margin10"}
{else}<br/>
{/if}

{/capture}

{$up="<img src='$IMGDIR/small_up.png' alt=''>"}
{$down="<img src='$IMGDIR/small_down.png' alt=''>"}
{if $sort == "name"}{if $sort_dir=='desc'}{$name_sort=$up}{else}{$name_sort=$down}{/if}{else}{$name_sort=""}{/if}
{if $sort == "type"}{if $sort_dir=='desc'}{$type_sort=$up}{else}{$type_sort=$down}{/if}{else}{$type_sort=""}{/if}
{if $sort == "mtime"}{if $sort_dir=='desc'}{$mtime_sort=$up}{else}{$mtime_sort=$down}{/if}{else}{$mtime_sort=""}{/if}
{if $sort == "size"}{if $sort_dir=='desc'}{$size_sort=$up}{else}{$size_sort=$down}{/if}{else}{$size_sort=""}{/if}
{if $sort == "perms"}{if $sort_dir=='desc'}{$perms_sort=$up}{else}{$perms_sort=$down}{/if}{else}{$perms_sort=""}{/if}
{if $sort == "owner"}{if $sort_dir=='desc'}{$owner_sort=$up}{else}{$owner_sort=$down}{/if}{else}{$owner_sort=""}{/if}
{if $sort == "group"}{if $sort_dir=='desc'}{$group_sort=$up}{else}{$group_sort=$down}{/if}{else}{$group_sort=""}{/if}


{capture assign=tableheader}
<table class="files" id="files_table">
<tr>
<th class="fixwidth1 head round_left">&nbsp;</th>
<th id="filenametd" class="head buttonlike" onclick="submit_sort_viewfiles('name')">{$LN_filename} {$name_sort}</th>
<th class="head fixwidth5 buttonlike" onclick="submit_sort_viewfiles('type')">{$LN_type} {$type_sort}</th>
<th class="head fixwidth6 buttonlike right" onclick="submit_sort_viewfiles('size')">{$LN_size} {$size_sort}</th>
<th class="head fixwidth8c buttonlike right" onclick="submit_sort_viewfiles('mtime')">{$LN_modified} {$mtime_sort}</th>
<th class="head fixwidth5 buttonlike center" onclick="submit_sort_viewfiles('perms')">{$LN_perms} {$perms_sort}</th>
<th class="head fixwidth5c buttonlike" onclick="submit_sort_viewfiles('owner')">{$LN_owner} {$owner_sort}</th>
<th class="head fixwidth5c buttonlike" onclick="submit_sort_viewfiles('group')">{$LN_group} {$group_sort}</th>
<th class="head round_right right fixwidth8">{$LN_actions}</th>
</tr>
{/capture}


{if $only_rows == 0}
{* And display it here and at the bottom: *}
    {$topskipper}
    {$tableheader}
{/if}

{$counter=$offset}

{foreach $files as $idx => $file}

{* Set the correct icon to $icon *}
{$size=$file->get_size()}
{$perms=$file->get_perms()}
{$ext=$file->get_type()}
{$icon=$file->get_icon()}
{$icon_ln=$file->get_icon_ln()}
{$name=$file->get_name()}
{$show_delete=$file->get_show_delete()}

{if $ext eq 'dir' and $name neq '..'}
	{$size_ext=$LN_files}
{else}
	{$size_ext=""}
{/if}

<tr class="even content" onmouseover="javascript:$(this).toggleClass('highlight2');" onmouseout="javascript:$(this).toggleClass('highlight2');" onmouseup="javascript:start_quickmenu('viewfiles','', null, event);">
<td><img class="noborder" src="{$IMGDIR}/file_icons/{$icon}.png" width="16" height="16" alt="{$icon|capitalize}" {urd_popup type="small" text=$icon_ln|capitalize} /></td>
<td class="buttonlike" onmouseup="javascript:view_files_follow_link(event, '{$file->get_type()}', 'file{$counter}', '{$file->get_index()}');return false;" onmousedown="set_mouse_click();">
<div class="donotoverflowdamnit">
<input type="hidden" name="file{$counter}" id="file{$counter}" value="{$name|escape:htmlall}"/>
{$name|escape:htmlall} </div>
</td>
<td>{$icon_ln}</td>
<td class="right">{$size|escape} {$size_ext|escape}</td>
<td class="right">{$file->get_mtime()|escape}</td>
<td class="center">{$perms|escape}</td>
<td>{$file->get_owner()|escape}</td>
<td>{$file->get_group()|escape}</td>
<td>
{if $name neq '..'}
<div class="floatright">
{if $file->get_nfo_link() != ''} 
<div class="floatleft iconsizeplus followicon buttonlike" {urd_popup type="small" text=$LN_quickmenu_setpreviewnfo left=true} onclick="javascript:show_contents('{$file->get_nfo_link()|escape:javascript}', 0);"></div>
{/if}

{if $allow_edit && $file->get_show_edit()}
    <div class="floatleft iconsizeplus editicon buttonlike" {urd_popup type="small" text=$LN_viewfiles_edit|capitalize left=true} onclick="javascript:edit_file('file{$counter}');"></div>
{else}
    <div class="floatleft iconsizeplus"></div>
{/if}

{if $icon eq 'nzb'}
    <div class="floatleft iconsizeplus playicon buttonlike" {urd_popup type="small" text=$LN_viewfiles_uploadnzb left=true} onclick="submit_viewfiles_action('file{$counter}', 'up_nzb')"></div>
{else}
    <div class="floatleft iconsizeplus"></div>
{/if}

<div class="floatleft iconsizeplus foldericon buttonlike" {urd_popup type="small" text=$LN_viewfiles_rename|capitalize left=true} onclick="javascript:rename_file_form('file{$counter}');"></div>

{if $use_tar != 0}	
    <div class="floatleft iconsizeplus downicon buttonlike" {urd_popup type="small" text=$LN_viewfiles_download left=true} onclick="submit_viewfiles_action('file{$counter}', 'zip_dir')"></div>
{else}
    <div class="floatleft iconsizeplus"></div>
{/if}
{if $show_delete}
    <div class="floatleft iconsizeplus deleteicon buttonlike" onclick="submit_viewfiles_action_confirm('file{$counter}', {if $ext == 'dir'}'delete_dir'{else}'delete_file'{/if}, '{$LN_delete} \'@@\'?')" {urd_popup type="small" text=$LN_delete left=true} ></div>
{else}
    <div class="floatleft iconsizeplus"></div>
{/if}
</div>
{/if}
</td>
</tr>
{$counter=$counter+1}
{/foreach}

{if $only_rows == 0}
{if count($files) > 12}
<tr><td colspan="9" class="feet round_both_bottom">&nbsp;</td>
</tr>
{/if}
    </table>
    {$bottomskipper}
    <div><br/></div>
<div>
<input type="hidden" name="offset" id="offset" value="{$offset|escape}"/>
<input type="hidden" name="dir" value="{$directory|escape:htmlall}" id="dir"/>
<input type="hidden" name="dir2" value="{$directory|escape:htmlall|escape}" id="dir2"/>
<input type="hidden" name="sort_dir" value="{$sort_dir|escape}" id="order_dir"/>
<input type="hidden" name="sort" value="{$sort|escape}" id="order"/>
<input type="hidden" name="filename" value="" id="filename"/>
<input type="hidden" id="last_line" value="{$last_line|escape}"/>
</div>

{/if}
