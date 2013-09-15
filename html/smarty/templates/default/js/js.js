/*
 *  This file is part of Urd.
 *  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: js.js 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 */
"use strict";

var mousedown = 0;
var selected_text = "";
var text_counter = 0;


function set_selected()
{ 
    var s = (window.getSelection ? window.getSelection() : document.getSelection ? document.getSelection() : document.selection.createRange().text).toString();
    var len = s.length;
    if ((!s || s.length == 0) && text_counter > 0) { 
        text_counter --; 
    } else {
        selected_text = s;
        text_counter = 1;
    }
}


function __appendError(str)
{
    throw new Error("DEBUG: " + str);
}


function setvalbyid(id, val)
{
    var elem = document.getElementById(id);
    if (elem !== null) { 
        elem.value = val; 
    }
}


function setselectbyid(id, val)
{
    var elem = document.getElementById(id);
    if (elem === null) { 
        return;
    } else { 
        for (var i=0; i < elem.length; i++) {
            if (elem[i].value == val) {
                elem.selectedIndex = i; 
                return;
            }
        }
    }
}


function console_log(str)
{
   setTimeout("__appendError('" + str + "')", 1);
}


function get_value_from_id(id, def)
{
    var val = document.getElementById(id);
    if (val === null) {
        if (def === null) {
            return null;
        } else {
            return def;
        }
    } else {
        return val.value;
    }
}


function do_command(command, name, message)
{
    var group_id, form;
    if (command == 'update_ng') { // we are in a browsepage

        group_id = document.getElementById('select_groupid');
        if (group_id !== null) {
            group_id = group_id.options[group_id.selectedIndex].value;
        }
        if (group_id === null || group_id == '') {
            control_action('updatearticles');
        } else {
            ng_action('updategroup', group_id);
        }

    } else if (command == 'expire_ng') {
        group_id = document.getElementById('select_groupid');
        if (group_id !== null) {
            group_id = group_id.options[group_id.selectedIndex].value;
        }
        if (group_id === null || group_id == '') {
            control_action('expirearticles');
        } else {
            ng_action('expiregroup', group_id);
        }
    } else if (command == 'purge_ng') {
        group_id = document.getElementById('select_groupid');
        if (group_id !== null) {
            group_id = group_id.options[group_id.selectedIndex].value;
        }
        if (group_id === null || group_id == '') {
            control_action_confirm('purgearticles', message + '?');
        } else {
            ng_action_confirm('purgegroup', group_id, message + ' @@?');
        }
    } else if (command == 'gensets_ng') {
        group_id = document.getElementById('select_groupid');
        if (group_id !== null) {
            group_id = group_id.options[group_id.selectedIndex].value;
        }
        if (group_id === null || group_id == '') {
            control_action('gensetsarticles');
        } else {
            ng_action('gensetsgroup', group_id);
        }

    } else if (command == 'update_rss') { // we are in a browsepage
        group_id = document.getElementById('select_feedid');
        if (group_id !== null) {
            group_id = group_id.options[group_id.selectedIndex].value;
        }
        if (group_id === null || group_id == '') {
            control_action('updaterssall');
        } else {
            ng_action('updaterss', group_id);
        }

    } else if (command == 'expire_rss') {
        group_id = document.getElementById('select_feedid');
        if (group_id !== null) {
            group_id = group_id.options[group_id.selectedIndex].value;
        }
        if (group_id === null || group_id === '') {
            control_action('expirerssall');
        } else {
           ng_action('expirerss', group_id);
        }
    } else if (command == 'purge_rss') {
        group_id = document.getElementById('select_feedid');
        if (group_id !== null) {
            group_id = group_id.options[group_id.selectedIndex].value;
        }
        if (group_id === null || group_id === '') {
            control_action_confirm('purgerssall', message +'?');
        } else {
            ng_action_confirm('purgerss', group_id, message + ' @@?');
        }
    } else if (command == 'updatespotscomments') {
        control_action('updatespotscomments');
    } else if (command == 'updatespotsimages') {
        control_action('updatespotsimages');
    } else if (command == 'updatespots') {
        control_action('updatespots');
    } else if (command == 'expirespots') {
        control_action('expirespots');
    } else if (command == 'purgespots') {
        control_action_confirm('purgespots', message + '?');
    } else if (command == 'editcategories') {
        edit_categories();
    } else if (command == 'add_button') {
        buttons_action('edit', 'new');
    } else if (command == 'import_buttons') {
        show_popup_remote('ajax_edit_searchoptions', 'import_settings');
    } else if (command == 'export_buttons') {
        jump('ajax_edit_searchoptions.php?cmd=export_settings');
    } else if (command == 'add_user') {
        user_action('edit', 'new');
    } else if (command == 'export_users') {
        jump('ajax_edit_users.php?cmd=export_settings');
    } else if (command == 'import_users') {
        show_popup_remote('ajax_edit_users', 'import_settings');
    } else if (command == 'add_server') {
        edit_usenet_server('new', false);
    } else if (command == 'autoconfig') {
        control_action('findservers');
    } else if (command == 'autoconfig_ext') {
        control_action('findservers_ext');
    } else if (command == 'import_servers') {
        show_popup_remote('ajax_edit_usenet_servers', 'import_settings');
    } else if (command == 'export_servers') {
        jump('ajax_edit_usenet_servers.php?cmd=export_settings');
    } else if (command == 'import_groups') {
        show_popup_remote('ajax_groups', 'load_settings');
    } else if (command == 'export_groups') {
        group_export();
    } else if (command == 'export_rss') {
        rss_feeds_export();
    } else if (command == 'export_config') {
        config_export();
    } else if (command == 'import_config') {
        show_popup_remote('ajax_admin_config', 'load_settings');
    } else if (command == 'reset_config') {
        reset_prefs(message); 
    } else if (command == 'export_prefs') {
        user_settings_export();
    } else if (command == 'import_prefs') {
        show_popup_remote('ajax_prefs', 'load_settings');
    } else if (command == 'reset_prefs') {
        reset_prefs(message); 
    } else if (command == 'import_rss') {
        show_popup_remote('ajax_rss_feeds', 'load_settings');
    } else if (command == 'new_file') {
        edit_file('');
    } else if (command == 'new_rss') {
        edit_rss('new');
    } else if (command == 'optimise') {
        control_action('optimise');
    } else if (command == 'sendsetinfo') {
        control_action('sendsetinfo');
    } else if (command == 'getsetinfo') {
        control_action('getsetinfo');
    } else if (command == 'cleandir') {
        control_action('cleandir');
    } else if (command == 'checkversion') {
        control_action('checkversion');
    } else if (command == 'export_all_settings') {
        jump('ajax_action.php?cmd=export_all');
    } else if (command == 'import_all_settings') {
        show_popup_remote('action', 'import_all');
    } else if (command == 'updategroups') {
        control_action('updategroups');
    } else if (command == 'updateblacklist') {
        control_action('updateblacklist');
    } else if (command == 'updatewhitelist') {
        control_action('updatewhitelist');
    } else if (command == 'postmessage') {
        show_post_message();
    } else if (command == 'continueall') {
        control_action('continue_all');
    } else if (command == 'pauseall') {
        control_action('pause_all');
    } else if (command == 'getnzb') {
        show_uploadnzb();
    } else if (command == 'post') {
        show_post();
    } else if (command == 'cleandb') {
        control_action('cleandb');
    } else if (command == 'cleanall') {
        control_action('cleanall');
    } else if (command == 'cancelall') {
        control_action('cancelall');
    } else if (command == 'shutdown') {
        control_action('poweroff');
    } else if (command == 'reload') {
        control_action('restart');
    } else if (command == 'add_search') {
        show_savename();
    } else if (command == 'delete_search') {
        delete_search_confirm();
    } else {
        show_alert('Unknown command: ' + command);
    }
} 


function init()
{
    // To keep track of the mouse button, used for the quickmenu:
    mousedown = 0;
    document.body.onmousedown = function() { 
        ++mousedown;
        // Sanity check, sometimes it misses ups/downs!
        if (mousedown > 1) { mousedown = 1; }
    };
    document.body.onmouseup = function() {
        --mousedown;
        // Sanity check, sometimes it misses ups/downs!
        if (mousedown < 0) { mousedown = 0; }
    };
    var urdd_status = document.getElementById('urdd_status');
    var msg = get_value_from_id('urdd_message', '');
    if (urdd_status !== null && urdd_status.value == 0) {            
        set_message('message_bar', msg, 5000);
    } 
}


function GetXmlHttpObject()
{
    var xmlHttp = null;
    try {
        // Firefox, Opera 8.0+, Safari
        xmlHttp = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer
        try {
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (ee) {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    if (xmlHttp === null) {
        alert("Your browser does not support AJAX!");
        return null;
    } 
    return xmlHttp;
}


function SendXMLHTTPPOST(url, params, action)
{
    var xmlHttp=GetXmlHttpObject();
    if (params === '') {
        params = params + '__sid=' + encodeURIComponent(String(Math.random()));
    } else {
        params = params + '&__sid=' + encodeURIComponent(String(Math.random()));
    }

    xmlHttp.open("POST", url, true);
    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttp.setRequestHeader("Content-length", params.length);
    xmlHttp.setRequestHeader("Connection", "close");
    if (action !== null) {
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                action(xmlHttp);
            }
        };
    }

    xmlHttp.send(params);
    return xmlHttp;
}


function SendXMLHTTPGET(url, params, action)
{
    var xmlHttp = GetXmlHttpObject();

    if (params === '') {
        params = '__sid=' +encodeURIComponent(String(Math.random()));
    } else {
        params = params + '&__sid=' +encodeURIComponent(String(Math.random()));
    }
    url = url + '?' + params;
    if (action !== null) {
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                action(xmlHttp);
            }
        };
    }
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
    return xmlHttp;
}


function task_action(action, task)
{
    var url = "ajax_action.php";
    var challenge = get_value_from_id('challenge', '');
    var params = 'cmd=' + encodeURIComponent(action)+
        "&challenge=" + encodeURIComponent(challenge)+
        "&task=" + encodeURIComponent(task);

    if (action !== null) {
        SendXMLHTTPPOST(url, params, function(xmlHttp) {
            update_tasks();
            if (xmlHttp.responseText.substr(0,2) == "OK") {
                set_message('message_bar', xmlHttp.responseText.substr(2) , 5000);
            } else if (xmlHttp.responseText.substr(0, 7) == ':error:') {
                set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
            } else {
                set_message('message_bar', xmlHttp.responseText, 5000);
            }
            }
        );
    }
}


function job_action(action, job)
{
    var url = "ajax_action.php";
    var challenge = get_value_from_id('challenge', '');
    var params = 'cmd=' + encodeURIComponent(action) +
        "&challenge=" + encodeURIComponent(challenge) +
        "&job=" + encodeURIComponent(job);

    if (action !== null) {
        SendXMLHTTPPOST(url, params, function(xmlHttp) {
                update_jobs();
                if (xmlHttp.responseText.substr(0,2) == "OK") {
                    set_message('message_bar', xmlHttp.responseText.substr(2) , 5000);
                } else if (xmlHttp.responseText.substr(0, 7) == ':error:') {
                    set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
                } else {
                    set_message('message_bar', xmlHttp.responseText, 5000);
                }
            }
        );
    }
}


function control_action_confirm(action, confirmmsg)
{
    var resp = show_confirm(confirmmsg, function() { 
            control_action(action);
        }
    );
}


function control_action(action)
{
    var url = "ajax_action.php";
    var challenge = get_value_from_id('challenge', '');
    var params = 'cmd=' + encodeURIComponent(action) + 
        "&challenge=" + encodeURIComponent(challenge);
    if (action !== null) {
        SendXMLHTTPPOST(url, params, function(xmlHttp) {
                if (xmlHttp.responseText.substr(0,2) == "OK") {
                    set_message('message_bar', xmlHttp.responseText.substr(2) , 5000);
                } else if (xmlHttp.responseText.substr(0, 7) == ':error:') {
                    set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
                } else {
                    set_message('message_bar', xmlHttp.responseText, 5000);
                }
            }
        );
    }
}


function ng_action_confirm(action, id, confirmmsg)
{
    var name = get_value_from_id("ng_id_" + id, null);
    if (name === null) {
        return;
    }
    confirmmsg = confirmmsg.replace('@@', name);
    var resp = show_confirm(confirmmsg, function () {
            ng_action(action, id);
        }
    );
}


function ng_action(action, id)
{
    var url = "ajax_action.php";
    var challenge = get_value_from_id('challenge', '');
    var params = 'cmd=' + encodeURIComponent(action)
        + "&group=" + encodeURIComponent(id) 
        +"&challenge=" + encodeURIComponent(challenge);

    if (action !== null) {
        SendXMLHTTPPOST(url, params, function(xmlHttp) {
                if (xmlHttp.responseText.substr(0,2) == "OK") {
                    set_message('message_bar', xmlHttp.responseText.substr(2), 5000);
                } else if (xmlHttp.responseText.substr(0, 7) == ':error:') {
                    set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
                } else {
                    set_message('message_bar', xmlHttp.responseText, 5000);
                }
            }
        );
    }
}


function remove_class(item, classname)
{
    $(item).removeClass(classname);
}


function add_class(item, classname)
{
    $(item).addClass(classname);
}


function ToggleClass(item, classname)
{
    $(item).toggleClass(classname);
}


function ToggleClassById(itemname, classname)
{
    $('#' + itemname).toggleClass(classname);

}


function fast_trim (str) 
{
    return $.trim(str);
}


function set_basket_type(type)
{
    var url = "ajax_processbasket.php";
    var params = "command=set&basket_type=" + encodeURIComponent(type);
    SendXMLHTTPPOST(url, params, function(xmlHttp) { } );
}


function get_basket_type()
{
    var url = "ajax_processbasket.php";
    var params = "command=get";
    var type = '';
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            type = xmlHttp.responseText;
            update_basket_display(type);
        } 
    );
}


function update_basket_display(basket_type)
{
        if (basket_type != 1 && basket_type != 2) {
            get_basket_type();
            return;
        }
        var url = "ajax_processbasket.php";
        var add_setname = get_value_from_id('add_setname', ''); 
        var dlsetname = get_value_from_id('dlsetname', ''); 
        var category = get_value_from_id('save_category', ''); 
        var timestamp = get_value_from_id('timestamp', '');
        var dl_dir = get_value_from_id('dl_dir', '');

        if (dlsetname === '' && selected_text != '') {
            dlsetname = selected_text;
        }
        var params = "command=view&dlsetname=" + encodeURIComponent(dlsetname);
        params = params + '&save_category=' + encodeURIComponent(category);
        params = params + '&basket_type=' + encodeURIComponent(basket_type);
        params = params + "&download_delay=" + encodeURIComponent(timestamp);
        params = params + "&add_setname=" + encodeURIComponent(add_setname);
        params = params + "&dl_dir=" + encodeURIComponent(dl_dir);
        SendXMLHTTPPOST(url, params, function(xmlHttp) {
                var content = xmlHttp.responseText;
                var basketdiv = document.getElementById('basketdiv');
                var minibasket = document.getElementById('minibasketdiv');
                if (basket_type != 2) {
                    basketdiv.innerHTML = content;
                    content = fast_trim(content);
                    if (document.getElementById('basketbuttondiv') !== null) {
                        add_class(minibasket,'hidden');
                        var bbdiv = document.getElementById('basketbuttondiv');
                        if (content === '') {
                            add_class(bbdiv, 'hidden');
                        } else {
                            remove_class(basketdiv, 'hidden');
                            remove_class(bbdiv, 'hidden');
                        }
                    }
                    minibasket.innerHTML = '';
                } else {
                    minibasket.innerHTML = content;
                    add_class(basketdiv, 'hidden');
                    if (content == '') {
                        add_class(minibasket, 'hidden');
                    } else {
                        remove_class(minibasket, 'hidden');
                    }
                    basketdiv.innerHTML = '';
                }
        }
        );
}


var LastClickedSetID;

function SelectSet(setID, type, theevent)
{
    // Remember this set for when the shift key is pressed, so we can toggle everything in between.
    // First see if shift was used and we need to toggle a bunch, before we overwrite the LastClickedSetID.
    // We also need to check if there is a valid LastClickedSetID to prevent bogus stuff.
    close_browse_divs();
    if (theevent.shiftKey && typeof(LastClickedSetID) != 'undefined' ) {
        ToggleGroupOfSets(LastClickedSetID,setID,type);
    } else {
        ToggleSet(setID, type);
    }
    LastClickedSetID = setID;
}


function ToggleSet(setID, type)
{
    var set = document.getElementById('set_' + setID);
    var button = document.getElementById('divset_' + setID);
    var params = '';
    var xstatus = 0;

    if (set.value === '') {
        xstatus = 0;
        params = "command=add";
    } else {
        xstatus = 1;
        params = "command=del";
    }

    if (xstatus === 0) {
        set.value = 'x';
        ToggleClass(button, 'setimgplus');
        ToggleClass(button, 'setimgminus');
    } else {
        set.value = "";
        ToggleClass(button, 'setimgplus');
        ToggleClass(button, 'setimgminus');
    }
    var dl_dir = get_value_from_id('dl_dir', '');
    var add_setname = get_value_from_id('add_setname', '');
    var timestamp = get_value_from_id('timestamp', '');

    var url = "ajax_processbasket.php";
    params = params 
        + "&setID=" + encodeURIComponent(setID) 
        + "&type=" + encodeURIComponent(type) 
        + "&dl_dir=" + encodeURIComponent(dl_dir)
        + "&timestamp=" + encodeURIComponent(timestamp)
        + "&add_setname=" + encodeURIComponent(add_setname);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            update_basket_display();
        }
    );
}


function set_as_downloaded_sets()
{
    var sets = document.getElementsByName("set_ids[]");
    for (var i = 0; i < sets.length; i++) {
        var setID = sets.item(i).value;
        var row = document.getElementById('base_row_'+setID);
        var set = get_value_from_id('set_'+setID, '');
        if (set != '') {
            add_class(row, 'markedread');
        }
    }
}


function reset_sets()
{
    var sets = document.getElementsByName("set_ids[]");
    for (var i = 0; i < sets.length; i++) {
        var setID = sets.item(i).value;
        var set = document.getElementById('set_'+setID);
        var button = document.getElementById('divset_'+setID);
        add_class(button, 'setimgplus');
        remove_class(button, 'setimgminus');
        set.value = "";
    }
}


function submit_sort_log(val)
{
    var orderval =$('#sort_order');
    var orderdir = $('#sort_dir');
    if (orderval.val() == val) {
        if (orderdir.val() == 'asc') {
            orderdir.val('desc');
        } else {
            orderdir.val('asc');
        }
    } else {
        orderval.val(val);
        orderdir.val('asc');
    }
    show_logs();
}


function submit_sort_viewfiles(val)
{
    var orderval =$('#searchorder');
    var orderdir = $('#searchdir');
    var dir = get_value_from_id('dir', '');
    if (orderval.val() == val) {
        if (orderdir.val() == 'asc') {
            orderdir.val('desc');
        } else {
            orderdir.val('asc');
        }   
    } else {
        orderval.val(val);
        orderdir.val('asc');
    }
    show_files( { 'curdir':dir, 'reset_offset': true });
}


function submit_viewfiles_page(offset)
{
    var offsetval = $('#offset');
    var dir = get_value_from_id('dir', '');
    offsetval.val(offset);
    show_files( { 'curdir':dir, 'reset_offset': false });
}


function escape_tags(str)
{
    str = str.replace(/&/g, '&amp;');
    str = str.replace(/</g, '&lt;');
    str = str.replace(/>/g, '&gt;');
    return str;
}


function submit_viewfiles_action_confirm(fileid, command, msg)
{
    var name = get_value_from_id(fileid, null);
    if (name == null) {
        return;
    }
    msg = msg.replace('@@', escape_tags(name));
    var resp = show_confirm(msg, function () {
            submit_viewfiles_action(fileid, command);
        }
    );
}


function submit_viewfiles_action(fileid, command)
{
    var challenge = get_value_from_id('challenge', '');
    var name = get_value_from_id(fileid, null);
    var dir = get_value_from_id('dir', null);
    if (name == null || dir == null) {
        return;
    }
    var url = "ajax_editviewfiles.php";
    var params = "cmd="+command+"&dir=" + encodeURIComponent(dir) + "&filename=" + encodeURIComponent(name) + "&challenge=" + encodeURIComponent(challenge);
    if (command == 'up_nzb') {
        show_uploadnzb(dir, name);
    } else if (command == 'zip_dir') {
        var id = 'iframe_' + String(Math.round(Math.random()* 10000));
        $('<iframe id="' + id + '" name="iframe" style="top:200px;">').appendTo('body');
        $('#' + id).attr('src', url + '?' + params);
        $('#' + id).hide();
        $('#' + id).load( function() {
            var msg = document.getElementById(id).contentWindow.document.body.innerHTML;
            $('#' + id).remove();
            if (msg.substr(0, 7) == ':error:') {
                set_message('message_bar', msg.substr(7), 5000);
            }
        }

        );
    } else {
        SendXMLHTTPPOST(url, params, function(xmlHttp) {
                show_files( { 'curdir':dir, 'reset_offset': false });
                update_message_bar(xmlHttp);
            }
        );
    }
}


function submit_delete_file(path, file)
{
    var action = $('#action');
    var filename = $('#filename');
    action.val('delete_file');
    filename.val(file);
    var searchform = document.getElementById('searchform');
    searchform.submit();
}


function submit_order(val, def, fn)
{
    var orderval = $('#order');
    var orderdirval = $('#order_dir');
    if (orderval.value == val) {
        if (orderdirval.val() == 'desc') {
            orderdirval.val('asc');
        } else {
            orderdirval.val('desc');
        }
    } else {
        orderdirval.val(def);
    }
    orderval.val(val);
    fn(orderval.val(), orderdirval.val());
}


function submit_rss_search(val, def)
{
    submit_order(val, def, load_rss_feeds);
}


function submit_search_users(val, def)
{
    submit_order(val, def, show_users);
}


function submit_search_jobs(val, def)
{
    submit_order(val, def, load_jobs);
}


function submit_search_tasks(val, def)
{
    submit_order(val, def, load_tasks);
}


function load_transfers()
{
    var msg_id = document.getElementById('message_bar');
    var active_tab = get_value_from_id('active_tab', null);
    var url = "ajax_showtransfers.php";
    var params = '';
    if (active_tab !== null) {
        params = params + "active_tab=" + encodeURIComponent(active_tab);
    }
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            show_content_div(xmlHttp, 'transfersdiv');
        }
    );
}


var update_transfer_interval=null;
function update_transfers()
{
    // call ajax, restart in 4 seconds
    load_transfers();
    if (update_transfer_interval !== null) {
        clearInterval(update_transfer_interval);
    }
    update_transfer_interval = setInterval(load_transfers, 4000);
}


function hide_overlayed_content()
{
    _hide_overlayed_content('#overlay_content', '#overlay_back');
}
    
 
function hide_overlayed_content2()
{
    _hide_overlayed_content('#overlay_content2', '#overlay_back2');
}


function _hide_overlayed_content(content, back)
{
    $(back).hide();
    $(content).hide();
    $(document).keydown(function(event) { }  ) ;
}


function _show_overlayed_content(xmlHttp, style, content, back, close_button)
{
    $(content).html('');
    if (xmlHttp.readyState==4) { 
        if (xmlHttp.responseText.substr(0, 7) == ':error:') {
            set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
        } else {
            $(content).scrollTop(0);
            $(content).css({"width":"", "height":"", "margin": ""});
            $(content).html(xmlHttp.responseText);
            $(content).removeClass();
            $(content).addClass(style);
            $(document).keydown(function(e) { if (e.which == 27 ) { _hide_overlayed_content(content, back); } } ) ;
            $(content).click(function(e) { e.stopPropagation(); });
            $(close_button).click(function(e) { _hide_overlayed_content(content, back); e.stopPropagation(); });
            $(back).click(function(e) { _hide_overlayed_content(content, back); e.stopPropagation(); });
            $(back).show();
            $(content).show();
        }
    }
}

function _overlayed_content_visible(content)
{
    return($(content).css("display") != 'none');
}


function overlayed_content_visible()
{
    return _overlayed_content_visible('#overlay_content')
}


function show_overlayed_content(xmlHttp, style)
{
    _show_overlayed_content(xmlHttp, style, '#overlay_content', '#overlay_back', '#close_button');
}


function show_overlayed_content2(xmlHttp, style)
{
    _show_overlayed_content(xmlHttp, style, '#overlay_content2', '#overlay_back2', '#close_button2');
}

   
function show_content_div(xmlHttp, divid)
{
    if (xmlHttp.readyState==4) { 
        if (xmlHttp.responseText.substr(0, 7) == ':error:') {
            set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
        } else {
            $('#' + divid).html(xmlHttp.responseText);
        }
    }
}


function load_control()
{
    var url = "ajax_admincontrol.php";
    SendXMLHTTPGET(url, '', function(xmlHttp) {
            show_content_div(xmlHttp, 'controldiv');
        }
    );
}


function show_users(order, direction)
{        
    var url = "ajax_edit_users.php";
    var params = "cmd=reload_users";
    var orderval = document.getElementById('order');
    var orderdirval = document.getElementById('order_dir');
    var search = clean_search_value('search', '');
    params = params + '&search=' + encodeURIComponent(search);
    if (order == null && orderval != null) {
        order = orderval.value;
    }
    if (order != null) {
        params = params + "&sort="+encodeURIComponent(order) ;
    }

    if (direction == null && orderdirval != null) {
        direction = orderdirval.value;
    }
    if (direction != null) {
        params = params + "&sort_dir="+encodeURIComponent(direction);
    }
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            show_content_div(xmlHttp, 'usersdiv');
        }
    );
}


function show_files_clean()
{
   return show_files( { 'curdir':null, 'reset_offset':true});
}


function show_files(options)
{
    var search = clean_search_value('search', '');
    var dir = get_value_from_id('dir', '');
    var offset = get_value_from_id('offset', 0);
    var sort = get_value_from_id('searchorder', '');
    var sortdir = get_value_from_id('searchdir', '');
    var add_rows = 0;
    var params = "cmd=show_files";
    if (options != null) {
        if (options.curdir != null) {
            dir = options.curdir;
        }
        if (options.reset_offset === true) {
            offset = 0;
        }
        if (options.add_rows == 1) {
            var per_page = $('#perpage').val();
            add_rows = 1;
            params = params + "&only_rows=" + encodeURIComponent("1");
            params = params + "&perpage=" + encodeURIComponent(per_page);
            add_rows = 1;
            offset = parseInt($('#last_line').val());
            $('#last_line').val(offset+parseInt(per_page));
        }
    }
    $('#search').focus( function () { clean_search('search'); } ) ;
    $('#search').keypress( function(event) { do_keypress_viewfiles(event)} ) ;
    var url = "ajax_editviewfiles.php";
    params = params + "&search=" + encodeURIComponent(search) + 
        "&offset=" + encodeURIComponent(offset) +
        "&dir=" + encodeURIComponent(dir) +
        "&sort=" + encodeURIComponent(sort) +
        "&sort_dir=" + encodeURIComponent(sortdir); 
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            if (xmlHttp.responseText.substr(0, 7) == ':error:') {
                set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
                if (add_rows == 0) {
                    $('#viewfilesdiv').html('');
                }
            } else {
                if (add_rows == 0) {
                    show_content_div(xmlHttp, 'viewfilesdiv');
                    update_widths('filenametd');
    //                var dir = document.getElementById('dir2');
    //                var dir_top = document.getElementById('directory_top');
                    $('#directory_top').html( $('#dir2').val() );
                    $('#contentout').scrollTop(0);
                } else {
                    $('#files_table > tbody:last').append(xmlHttp.responseText);
                    update_widths("filenametd");
                }
            } 
        }
    );
}


function show_buttons(order, direction)
{        
    var url = "ajax_edit_searchoptions.php";
    var params = "cmd=show_buttons";
    var search = clean_search_value('search', '');
    params = params + "&search=" + encodeURIComponent(search) ;

    var orderval = document.getElementById('sort');
    var orderdirval = document.getElementById('sort_dir');
    
    if (order === null && orderval !== null) {
        order = orderval.value;
    } else if (order === null && orderval === null) {
        order = 'name';
    } else {
        if (orderval !== null && order == orderval.value) {
            if (orderdirval.value == 'desc') {
                direction = 'asc';
            } else {
                direction = 'desc';
            }
        } else {
            direction = 'asc';
        }
    }

    params = params + "&sort="+encodeURIComponent(order);
    if (direction !== null) {
        params = params + "&sort_dir="+encodeURIComponent(direction);
    } else if (orderdirval !== null) {
        params = params + "&sort_dir="+encodeURIComponent(orderdirval.value);
    }

    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            show_content_div(xmlHttp, 'buttonsdiv');
        }
    );
}


function show_usenet_servers(order, direction)
{        
    var url = "ajax_edit_usenet_servers.php";
    var params = "cmd=reload_servers";
    var search = clean_search_value('search', '');
    params = params + "&search=" + encodeURIComponent(search) ;
    var orderval = document.getElementById('order');
    var orderdirval = document.getElementById('order_dir');
    
    if (order == null && orderval != null) {
        order = orderval.value;
    } else if (order == null && orderval == null) {
        order = 'name';
    } else {
        if (orderval != null && order == orderval.value) {
            if (orderdirval.value == 'desc') {
                direction = 'asc';
            } else {
                direction = 'desc';
            }
        } else {
            direction = 'asc';
        }
    }

    params = params + "&sort="+encodeURIComponent(order);
    if (direction != null) {
        params = params + "&sort_dir="+encodeURIComponent(direction);
    } else if (orderdirval != null) {
        params = params + "&sort_dir="+encodeURIComponent(orderdirval.value);
    }
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            show_content_div(xmlHttp, 'usenetserversdiv');
        }
    );
}


function show_post_message(groupid, spotid)
{        
    var url = "ajax_post_message.php";
    var params = '';
    var group_id = document.getElementById('select_groupid');
    if (group_id !== null) {
        group_id = group_id.options[group_id.selectedIndex].value;
        params = params + "groupid="+group_id;
    }
    if (spotid !== null) { 
        params = params + "spotid="+spotid;
        params = params + "&cmd=report";
    }
    params=params+"&cmd=show";
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            if (xmlHttp.responseText.substr(0, 7) != ':error:') {
                show_overlayed_content(xmlHttp, 'popup700x400');
            } else {
                set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
            }

        }
    );
}


function show_uploadnzb(dir, name)
{        
    var url = "ajax_show_upload.php";
    var params = '';
    if (dir != null && name != null) {
        params = "dir=" + encodeURIComponent(dir) + "&filename=" +encodeURIComponent(name);
    }
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            if (xmlHttp.responseText.substr(0, 7) != ':error:') {
                show_overlayed_content(xmlHttp, 'popup525x300');
            } else {
                set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
            }
        }
    );
}


function ShowEditPost(postid)
{
    var url = "ajax_show_post.php";
    var params = "cmd=showrename&postid=" + encodeURIComponent(postid);
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            if (xmlHttp.responseText.substr(0, 7) != ':error:') {
                show_overlayed_content(xmlHttp, 'popup700x400');
            } else {
                set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
            }
        }
    );
}


function show_post()
{        
    var url = "ajax_show_post.php";
    SendXMLHTTPGET(url, '', function(xmlHttp) { 
            if (xmlHttp.responseText.substr(0, 7) != ':error:') {
                show_overlayed_content(xmlHttp, 'popup700x400');
            } else {
                set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
            }
        }
    );
}


function load_jobs(order, direction)
{
    var url = "ajax_adminjobs.php";
    var params = '';
    var orderval = get_value_from_id('order', '');
    var orderdirval = document.getElementById('order_dir');
    if (order != null) {
        params = params+"&sort="+encodeURIComponent(order);
    } else if (orderval != '' && order == null) {
        order = orderval;
        params = params+"&sort="+encodeURIComponent(order);
    }

    if (direction == null && orderdirval != null) {
        direction = orderdirval.value;
    }
    if (direction != null) {
        params=params+"&sort_dir="+encodeURIComponent(direction);
    }

    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            show_content_div(xmlHttp, 'jobsdiv');
            update_widths("descr_td");
        }
    );
}


function load_tasks_no_offset(order, direction)
{
     load_tasks(order, direction, true);
}


function load_tasks(order, direction, clear_offset)
{
    var url = "ajax_admintasks.php";
    var params = '';
    var orderval = document.getElementById('order');
    var offsetval = document.getElementById('offset');
    var statusval = document.getElementById('status_select');
    var timeval = document.getElementById('time_select');
    var tasksearch = document.getElementById('tasksearch');
    var orderdirval = document.getElementById('order_dir');
    if (timeval !== null ){
        timeval = timeval.options[timeval.selectedIndex].value;
        params=params+"&time="+encodeURIComponent(timeval);
    }
    if (tasksearch !== null && tasksearch.name != '_search'){
        tasksearch = tasksearch.value;
        params=params+"&tasksearch="+encodeURIComponent(tasksearch);
    }
    if (statusval !== null){
        statusval = statusval.options[statusval.selectedIndex].value;
        params=params+"&status="+encodeURIComponent(statusval);
    }
    if (order === null && orderval !== null) {
        order = orderval.value;
    }
    if (order !== null) {
        params=params+"&sort="+encodeURIComponent(order);
    }
    if (direction === null && orderdirval !== null) {
        direction = orderdirval.value;
    }
    if (direction !== null) {
        params=params+"&sort_dir="+encodeURIComponent(direction);
    }
    if (offsetval !== null && clear_offset !== true) {
        params = params + '&offset=' + encodeURIComponent(offsetval.value);
    }
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            show_content_div(xmlHttp, 'tasksdiv');
            update_widths("descr_td");
            update_widths("comment_td");
        }
    );
}


function update_jobs()
{
    load_jobs();
    setTimeout(update_jobs, 5000);
}

function tasks_offset(offset)
{
    if (offset !== null) { 
        var offsetval = document.getElementById('offset');
        offsetval.value = offset;
    }
    load_tasks(null, null);
}


function update_tasks()
{
    load_tasks(null, null);
    setTimeout(update_tasks, 4500);
}


function do_hide_message(id)
{
    var item = document.getElementById(id);
    $('#' + id).fadeOut(500, function () {
            add_class(item, "hidden");
            $('#' + id).fadeIn(0);
    } );
}


//var message_timeout = new Array();
var message_timeout = null;
function hide_message(id, timeout)
{
    //if (message_timeout[id] != null) {
    if (message_timeout != null) {
        clearTimeout(message_timeout);
    }
    //message_timeout[id] = setTimeout("do_hide_message('" + id+ "');", timeout);
    message_timeout = setTimeout("do_hide_message('" + id+ "');", timeout);
}


function update_control()
{
    load_control();
    setTimeout(update_control, 4000);
}


function ShowStatus(xmlHttp)
{
    if (xmlHttp.readyState==4) { 
        var content = xmlHttp.responseText;
        document.getElementById('urdstatus').innerHTML = content + '<br/>';
    }
}


function load_disk_status()
{
    var url = "ajax_showstatus.php";
    SendXMLHTTPGET(url, 'type=disk', function(xmlHttp) {
        var disk_li = document.getElementById('disk_li');
        var urdstatdiv = document.getElementById('status_disk');
        if (xmlHttp.responseText.substr(0,3) == 'OFF') {
            add_class(disk_li, "hidden");
        } else {
            remove_class(disk_li, "hidden");
            urdstatdiv.innerHTML = xmlHttp.responseText;
        }
        }
    ); 
}


var activity_status = 0;
function load_activity_status(force)
{
    if ((activity_status + 4000 ) >= (new Date().getTime() ) && force != 1 ) { 
        return;
    }
    activity_status = new Date().getTime();
    var url = "ajax_showstatus.php";
    SendXMLHTTPGET(url, 'type=activity', function(xmlHttp){
            $('#activity_status').html(xmlHttp.responseText);
        }
    ); 

}


function load_quick_status()
{
    var url = "ajax_showstatus.php";
    SendXMLHTTPGET(url, 'type=quick', function(xmlHttp){
            $('#status_msg').html(xmlHttp.responseText);
        }
    ); 
    SendXMLHTTPGET(url, 'type=icon', function(xmlHttp){
            $('#smallstatus').html(xmlHttp.responseText);
        }
    ); 
}


function update_activity_status()
{
    // call ajax, restart in 4 seconds
    load_activity_status();
    setInterval(load_activity_status, 4000);
}


function update_disk_status()
{
    // call ajax, restart in 10 seconds
    load_disk_status();
    setInterval(load_disk_status, 10000);
}


function update_quick_status()
{
    // call ajax, restart in 4 seconds
    load_quick_status();
    setInterval(load_quick_status, 4000);
}


function DisplaySetInfo(setid, xmlHttp)
{
    var thediv = document.getElementById('div_'+setid);
    thediv.innerHTML = xmlHttp.responseText;
    thediv.className = "setinfo";
    var thetr = document.getElementById('tr_set_'+setid);
    thetr.className = "";
    //set the last div id for reference so on reload we have it and can re-open 
    $('#lastdivid').val(setid);
}


function EditExtSetInfo(setid)
{
    // Do we clear or do we display?
    var url = "ajax_showsetinfo.php";
    var params = "edit=1&setID=" + encodeURIComponent(setid);

    SendXMLHTTPGET(url, params, function(xmlHttp) { 
         DisplaySetInfo(setid, xmlHttp);
    }
    );
}


function urd_search()
{
    var srch = (window.getSelection ? window.getSelection() : document.getSelection ? document.getSelection() : document.selection.createRange().text);
    srch = String(srch);
    if (srch === '') {
        show_alert("Please select the search query before clicking this button.");
    } else {
        var search = document.getElementById('search');
        var flag = document.getElementById('flag');
        var group_id = document.getElementById('select_groupid');
        var feed_id = document.getElementById('select_feedid');
        if(group_id !== null) {
           group_id.selectedIndex = 0;
        }
        if(feed_id !== null) {
           feed_id.selectedIndex = 0;
        }
        flag.value = '';
        search.value = srch;
        load_sets({'offset':'0', 'setid':''});
    }
}


function add_search(type)
{
    var srch = (window.getSelection ? window.getSelection() : document.getSelection ? document.getSelection() : document.selection.createRange().text);
    srch = String(srch);
    if (srch === '') {
        show_alert("Please select the search query before clicking this button.");
    } else {
        srch = srch.replace(/[\]\[_"+.]/g, ' ');
        var challenge = get_value_from_id('challenge', '');
        var url = 'ajax_action.php';
        var param = 'cmd=add_search&value='+encodeURIComponent(srch)+'&type='+encodeURIComponent(type)+"&challenge="+challenge;
        SendXMLHTTPPOST(url, param, null); 
    }
}


function search_button(url, xname)
{
    var srch = (window.getSelection ? window.getSelection() : document.getSelection ? document.getSelection() : document.selection.createRange().text);
    srch = String(srch);
    if (srch === '') {
        show_alert("Please select the search query before clicking this button.");
    } else {
        /* Remove common separators: */
        srch = srch.replace(/[\]\[_"+.']/g, ' ');
        srch = srch.replace(/^\s+/, '');
        url = url.replace(/\$q/, escape(srch));
        window.open(url, xname + 'window', '');
    }
}


function toggle_hide(id, class1)
{
    ToggleClassById(id, class1);
}


function markRead(setid, cmd, type)
{
    var url = "ajax_markread.php";
    var params = "cmd="+cmd+"&setid="+encodeURIComponent(setid)+"&type="+encodeURIComponent(type);
    if (cmd == 'wipe') {
        var challenge = get_value_from_id('challenge', '');
        params = params + '&challenge=' + challenge;
    }
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
        if (xmlHttp.responseText == 'OK') {
            var thetr = document.getElementById('base_row_'+setid);
            /* Only update display if backend succeeded: */
            if (cmd == 'markread') {
                ToggleClass(thetr, 'markedread');
            } else if (cmd == 'interesting') {
                ToggleClass(thetr,'interesting');
                var thediv = document.getElementById('intimg_'+setid);
                /*var buttonint = document.getElementById('buttonint').value;
                var buttonunint = document.getElementById('buttonunint').value;*/
                ToggleClass(thediv, 'sadicon'); 
                ToggleClass(thediv, 'smileicon'); 
            } else if (cmd == 'hide' || cmd == 'unhide' || cmd == 'wipe') {
                ToggleClass(thetr, 'hidden');
                if (cmd == 'wipe') {
                    var msg = get_value_from_id('deletedset', '');
                    set_message('message_bar', msg, 5000);
                }
            }
        } else {
            update_message_bar(xmlHttp);
        }
        }
    );
}


function update_message_reload_transfers(xmlHttp)
{
    load_transfers();
    update_message_bar(xmlHttp);
}


function post_edit(cmd, postid)
{
    var url = "ajax_editposts.php";
    var challenge = get_value_from_id('challenge') ;
    var params = "cmd=" + encodeURIComponent(cmd) + "&postid=" + encodeURIComponent(postid) + "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, update_message_reload_transfers);
}


function transfer_edit(cmd, dlid)
{
    var url = "ajax_edittransfers.php";
    var challenge = get_value_from_id('challenge') ;
    var params = "cmd=" + encodeURIComponent(cmd) + "&dlid=" + encodeURIComponent(dlid) + "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, update_message_reload_transfers);
}


function Whichbutton(buttonval, e)
{
    var rightclick = false;
    if (!e) {
        e = window.event;
    }
    if (e.shiftKey) {
        rightclick = true;
    }

    var sets = document.getElementsByName("set_ids[]");
    var url = "ajax_processbasket.php";
   
    close_browse_divs();
    if (buttonval == 'urddownload') {
        var params = "whichbutton=checksize"; 

        for(var i = 0; i < sets.length; i++) {
            params = params + '&set_ids[]=' + encodeURIComponent(sets.item(i).value);
        }
        SendXMLHTTPPOST(url, params, function(xmlHttp) {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var content = xmlHttp.responseText;
                content = fast_trim(content);
                if (content.substr(0,2) != 'OK') {
                    show_confirm(content, function() {
                        process_whichbutton(buttonval, rightclick);
                    }
                    );
                    return;
                } 
            }
        }
        );
    }
    process_whichbutton(buttonval, rightclick);
}


function process_whichbutton(buttonval, rightclick)
{
    var challenge = get_value_from_id('challenge');
    var group_id = get_value_from_id('group_id');
    var feed_id = get_value_from_id('feed_id');
    var type = get_value_from_id('type');
    var timestamp = get_value_from_id('timestamp');
    var dlname = get_value_from_id('dlsetname');
    var dl_dir = get_value_from_id('dl_dir');
    var add_setname = get_value_from_id('add_setname');
    var sets = document.getElementsByName("set_ids[]");
    var url = "ajax_processbasket.php";
    var params = "whichbutton=" + encodeURIComponent(buttonval)
        +"&group=" + encodeURIComponent(group_id)
        +"&feed=" + encodeURIComponent(feed_id)
        +"&all=" + (rightclick ? 1:0)
        +"&type=" + encodeURIComponent(type)
        +"&timestamp=" + encodeURIComponent(timestamp)
        +"&dlsetname=" + encodeURIComponent(dlname)
        +"&dl_dir=" + encodeURIComponent(dl_dir)
        +"&add_setname=" + encodeURIComponent(add_setname)
        +"&challenge=" + challenge;
    for(var i = 0; i < sets.length; i++) {
        params = params + '&set_ids[]=' + encodeURIComponent(sets.item(i).value);
    }

    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var content = xmlHttp.responseText;
                content = fast_trim(content);
                if (content.substr(0,2) == 'OK') {
                    update_basket_display();
                    set_message('message_bar', content.substr(2) + (content.substr(2) !== ''? '<br/>' : ''), 5000);
                    if (timestamp != null) {
                        timestamp.value = '';
                    }
                    if (buttonval == 'urddownload') {
                        set_as_downloaded_sets();
                    }

                    if (buttonval == 'mergesets' || buttonval == 'unmark_int_all' || buttonval == 'wipe_all' || buttonval == 'unmark_kill_all' || buttonval == 'mark_kill_all') { 
                        load_sets();
                        if (buttonval == 'wipe_all') {
                            var msg = get_value_from_id('deletedsets');
                            set_message('message_bar', msg, 5000);
                        }
                    } else {
                        reset_sets();
                    }
                } else {
                    set_message('message_bar', content + '<br/>', 5000);
                }
            }
        }
    );
}


function set_message(id, msg, timeout)
{ 
    var msg_id = document.getElementById(id);
    if (msg === '') {
        add_class(msg_id, 'hidden');
    } else {
        remove_class(msg_id, 'hidden');
        $('#' + id).html(msg);
        var boxwidth = $(document).width();
        $('#' + id).width(boxwidth - 24); 
        if (timeout > 0) {
            hide_message(id, timeout);
        }
    }
}


function blink_status(dlid, binary_id, group_id)
{
    $('#status_item').addClass("menu_highlight");
    setTimeout(function() {
        $('#status_item').removeClass("menu_highlight");
    } , 2000);
}



function load_preview(dlid, binary_id, group_id)
{
    var url = "ajax_showpreview.php";
    var params = "dlid=" + encodeURIComponent(dlid)
        + "&binary_id=" + encodeURIComponent(binary_id)
        + "&group_id=" + encodeURIComponent(group_id);
    SendXMLHTTPGET(url, params, function (xmlHttp) {
                $('previewdiv').html('<br/>' + xmlHttp.responseText);
                var title_str = get_value_from_id('title_str', '');
                document.title = title_str;
        }
    );
}


function select_preview(binid, gid)
{
    var chall = get_value_from_id('challenge') ;
    var url = "ajax_create_preview.php";
    var params="preview_bin_id=" + encodeURIComponent(binid) +
        "&preview_group_id=" + encodeURIComponent(gid) +
        "&challenge=" + encodeURIComponent(chall);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        var content = xmlHttp.responseText;
        if (!isNaN(content)) {
            blink_status(content, binid, gid);
        } else {
            set_message('message_bar', content , 5000);
        }
    }
    );
}


function update_preview(dlid, binary_id, group_id)
{
    // Check if there is a input called 'redirect', if so redirect, else refresh.
    var redir = document.getElementById('redirect');
    if (redir !== null) {
        document.location.href = redir.value;
    } else {
        var do_reload = document.getElementById('do_reload');
        if (do_reload === null) {
            // call ajax, restart in 1 seconds
            load_preview(dlid, binary_id, group_id);
            setTimeout('update_preview(' + dlid + ', "' + binary_id + '", ' + group_id + ')', 1000);
        } else {
            var file = $('#file').val();
            if ( $('#filetype').val() == 'image') {
                show_image(file);
            } else if ( $('#filetype').val() == 'text') {
                show_contents(file);
            }
        }

    } 
}


function show_preview(dlid, binary_id, group_id)
{
    var url = "ajax_showpreview.php";
    var params = "dlid=" + encodeURIComponent(dlid)
        + "&binary_id=" + encodeURIComponent(binary_id)
        + "&group_id=" + encodeURIComponent(group_id);
    SendXMLHTTPGET(url, params, function (xmlHttp) {
            show_overlayed_content(xmlHttp, 'popup700x400');
            var filetype = get_value_from_id('filetype', '');
            var file = get_value_from_id ('file', '');
            if (filetype == 'image') {
                show_image(file);
            } else if (filetype == 'text') {
                show_contents(file);
            } else {
                setTimeout(function () {
                        var do_reload = document.getElementById('do_reload');
                        if (overlayed_content_visible() && do_reload === null) {
                            show_preview(dlid, binary_id, group_id);
                        }
                    } , 1000);
            }
        }
    );
}


function delete_preview(dlid)
{
    var url = "ajax_action.php";
    var challenge = get_value_from_id('challenge');
    var params = "cmd=" + encodeURIComponent('delete_preview')
        + "&dlid=" + encodeURIComponent(dlid) 
        + "&challenge="+encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            load_activity_status(1);
            update_message_bar(xmlHttp);
            }
   );
}


function update_message_bar(xmlHttp) 
{ 
    if (xmlHttp.responseText == "OK") {
        set_message('message_bar', '');
    } else if (xmlHttp.responseText.substr(0,2) == "OK") {
        set_message('message_bar', xmlHttp.responseText.substr(2), 5000);
    } else {
        if (xmlHttp.responseText.substr(0, 7) == ':error:') {
            set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
        } else {
            set_message('message_bar', xmlHttp.responseText, 5000);
        }
    }
}


function TransfersButton(opt)
{
    if (opt == 'getnzb') {
        return;
    } else if (opt == 'post') {
        show_post();
        return;
    }

    // All other options require ajax:
    var challenge = get_value_from_id('challenge');
    var url = "ajax_action.php";
    var params= "cmd=" + encodeURIComponent(opt) + "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, update_message_bar);
}


function buttons_action_confirm(action, uid, msg)
{
    var resp = show_confirm(msg, function () {
        buttons_action(action, uid);
    }
    );
}   


function buttons_action(action, uid)
{
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_edit_searchoptions.php";
    var params = "id="+encodeURIComponent(uid)+
        "&cmd="+encodeURIComponent(action) +
        "&challenge=" +encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        if (action == 'edit') {
            show_overlayed_content(xmlHttp, 'popup525x300');
        } else {
            show_buttons();
            update_message_bar(xmlHttp);
        }
    }
    );
}


function user_update_setting(uid, action, value)
{
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_edit_users.php";
    var params = "id="+encodeURIComponent(uid)+
        "&cmd="+encodeURIComponent('update_setting') +
        "&action="+encodeURIComponent(action) +
        "&value="+encodeURIComponent(value) +
        "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        show_users();
        update_message_bar(xmlHttp);
    }
    );
}


function user_action_confirm(action, uid, msg)
{
    var resp = show_confirm(msg, function() {
        user_action(action, uid);
    }
    );
}


function user_action(action, uid)
{
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_edit_users.php";
    var params = "id="+encodeURIComponent(uid)+
        "&cmd="+encodeURIComponent(action) +
        "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        if (action == 'edit') {
            show_overlayed_content(xmlHttp, 'popup700x400');
        } else {
            show_users();
            update_message_bar(xmlHttp);
        }
    }
    );
}


function usenet_action_confirm(action, uid, msg)
{
    var resp = show_confirm(msg, function() {
        usenet_action(action, uid);
    }
    );
}


function usenet_action(action, uid)
{
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_edit_usenet_servers.php";
    var params = 
        "id="+encodeURIComponent(uid)+
        "&cmd="+encodeURIComponent(action)+ 
        "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        show_usenet_servers();
        update_message_bar(xmlHttp);
    }
    );
}


function upload_settings_form()
{
    var challenge = get_value_from_id('challenge', '');
    var command = get_value_from_id('command', '');
    var referrer = get_value_from_id('referrer', '');
    var params = '';
    var url = referrer;
    params = params + 'cmd=' + encodeURIComponent(command) 
        + "&challenge=" + encodeURIComponent(challenge);
}


function upload_handler(url, fn)
{
    var challenge = get_value_from_id('challenge', '');
    var command = get_value_from_id('command', '');
    var referrer = get_value_from_id('referrer', '');
    var params = '';
    $('#submit_form').click(function(e) {
            var file = document.getElementById('files').files[0];
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(e) {
                if ( 4 == this.readyState ) {
                    fn(xhr);
                }
            };
            var fd = new FormData;
            fd.append('challenge', challenge);
            fd.append('cmd', command);
            fd.append('filename', file);
            xhr.open('post', referrer, true);
            xhr.send(fd);
        }
    );
}



function reload_page(referrer)
{
    if (referrer == 'ajax_edit_searchoptions') {
        show_buttons();
    } else if (referrer == 'ajax_edit_usenet_servers') {
        show_usenet_servers();
    } else if (referrer == 'ajax_edit_users') {
        show_users();
    } else if (referrer == 'ajax_rss_feeds') {
        load_rss_feeds();
    } else if (referrer == 'ajax_groups') {
        load_groups();
    } else if (referrer == 'ajax_admin_config') {
        window.location = 'admin_config.php';
    } else if (referrer == 'ajax_prefs') {
        window.location = 'prefs.php';
    }
}


function show_popup_remote(referrer, command)
{
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_import_settings.php";
    var params = 'referrer=' + encodeURIComponent(referrer) +
        "&challenge=" + encodeURIComponent(challenge);
    if (command !== null) {
        params = params + '&cmd=' + encodeURIComponent(command);
    }

    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            show_overlayed_content(xmlHttp, 'popup525x300');
            upload_handler(url, function(xmlHttp2) {
                    if (xmlHttp2.responseText == 'OK') {
                        hide_overlayed_content();
                        reload_page(referrer);
                    } else {
                        update_message_bar(xmlHttp2);
                    }
                }
            );
        }
    );
}


function hide_popup(itemname, baseclass)
{
    var item = document.getElementById(itemname); 
    remove_class(item, baseclass + 'on');
    add_class(item, baseclass + 'off');
}


function show_rename_transfer(dlid)
{
    var url = "ajax_edittransfers.php";
    var params = "cmd=showrename&dlid=" + encodeURIComponent(dlid);
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
          show_overlayed_content(xmlHttp, 'popup700x400');
    }
    );
}


function edit_group(id)
{
    var url = "ajax_editgroup.php";
    var params = "cmd=showeditgroup&id=" + encodeURIComponent(id);
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
        show_overlayed_content(xmlHttp, 'popup700x400');
        $('#group_name').focus();
    }
    );
}


function edit_rss(id)
{
    var url = "ajax_editrss.php";
    var params = "cmd=showeditrss&id=" + encodeURIComponent(id);
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
        show_overlayed_content(xmlHttp, 'popup700x400');
        $('#rss_name').focus();
    }
    );
}


function edit_usenet_server(id, only_auth)
{
    var url = "ajax_edit_usenet_servers.php";
    var params = "cmd=showeditusenetserver" +
        "&id=" + encodeURIComponent(id)+
        "&only_auth=" + encodeURIComponent(only_auth?"1":"0");
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            if (xmlHttp.responseText.substr(0, 7) == ':error:') {
                set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
            } else {
                show_overlayed_content(xmlHttp, (only_auth?'popup525x300':'popup700x400'));
            }
        }
    );
}


function update_group()
{
    var id = get_value_from_id('id', '');
    var group_adult = get_value_from_id('group_adult', '');
    var group_minsetsize = get_value_from_id('group_minsetsize', '');
    var group_maxsetsize = get_value_from_id('group_maxsetsize', '');
    var group_time1 = get_value_from_id('group_time1', '');
    var group_time2 = get_value_from_id('group_time2', '');
    var group_period =  document.getElementById('group_refresh_period', '');
    var group_expire = get_value_from_id('group_expire', '');
    var group_subscribed = get_value_from_id('group_subscribed', '');
    var challenge = get_value_from_id('challenge', '');

    var url = "ajax_editgroup.php";
    var params = "cmd=update_group&id=" +encodeURIComponent(id) + 
        "&group_adult=" + encodeURIComponent(group_adult) + 
        "&group_time1=" + encodeURIComponent(group_time1) + 
        "&group_maxsetsize=" + encodeURIComponent(group_maxsetsize) + 
        "&group_minsetsize=" + encodeURIComponent(group_minsetsize) + 
        "&group_time2=" + encodeURIComponent(group_time2) + 
        "&group_refresh_period=" + encodeURIComponent(group_period.options[group_period.selectedIndex].value) + 
        "&group_expire=" + encodeURIComponent(group_expire) + 
        "&group_subscribed=" + group_subscribed +
        "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        if (xmlHttp.responseText == 'OK') {
            hide_overlayed_content();
        }
        update_message_bar(xmlHttp);
        load_groups();
    }
    );
}


function update_rss()
{
    var id = get_value_from_id('id', '');
    var rss_url = get_value_from_id('rss_url', '');
    var rss_adult = get_value_from_id('rss_adult', '');
    var rss_username = get_value_from_id('rss_username', '');
    var rss_password = get_value_from_id('rss_password', '');
    var rss_name = get_value_from_id('rss_name', '');
    var rss_time1 = get_value_from_id('rss_time1', '');
    var rss_time2 = get_value_from_id('rss_time2', '');
    var rss_period =  document.getElementById('rss_refresh_period', '');
    var rss_expire = get_value_from_id('rss_expire', '');
    var rss_subscribed = get_value_from_id('rss_subscribed', '');
    var challenge = get_value_from_id('challenge', '');

    var url = "ajax_editrss.php";
    var params = "cmd=update_rss&id=" +encodeURIComponent(id) + 
        "&rss_name=" + encodeURIComponent(rss_name) + 
        "&rss_adult=" + encodeURIComponent(rss_adult) + 
        "&rss_url=" + encodeURIComponent(rss_url) + 
        "&rss_time1=" + encodeURIComponent(rss_time1) + 
        "&rss_time2=" + encodeURIComponent(rss_time2) + 
        "&rss_refresh_period=" + encodeURIComponent(rss_period.options[rss_period.selectedIndex].value) + 
        "&rss_password=" + encodeURIComponent(rss_password) + 
        "&rss_username=" + encodeURIComponent(rss_username) + 
        "&rss_expire=" + encodeURIComponent(rss_expire) + 
        "&rss_subscribed=" + rss_subscribed +
        "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        if (xmlHttp.responseText == 'OK') {
            hide_overlayed_content();
        }
        update_message_bar(xmlHttp);
        load_rss_feeds();
    }
    );
}


function update_buttons()
{
    var id = get_value_from_id('id');
    var challenge = get_value_from_id('challenge');
    var name = get_value_from_id('name');
    var search_url = get_value_from_id('search_url');
    var url = "ajax_edit_searchoptions.php";
    var params = "cmd=update_button&id=" + encodeURIComponent(id) +
        "&name=" + encodeURIComponent(name) +
        "&search_url="+encodeURIComponent(search_url) + 
        "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        if (xmlHttp.responseText == 'OK') {
            hide_overlayed_content();
            show_buttons();
        }
        update_message_bar(xmlHttp);
    }
    );
}


function update_user()
{
    var id = get_value_from_id('id');
    var challenge = get_value_from_id('challenge');
    var username = get_value_from_id('username');
    var fullname = get_value_from_id('fullname');
    var email = get_value_from_id('email');
    var password = get_value_from_id('password');
    var isactive = get_value_from_id('isactive');
    var autodownload = get_value_from_id('autodownload');
    var seteditor = get_value_from_id('seteditor');
    var fileedit = get_value_from_id('fileedit');
    var allow_update = get_value_from_id('allow_update');
    var allow_erotica = get_value_from_id('allow_erotica');
    var post = get_value_from_id('post');
    var isadmin = get_value_from_id('isadmin');
    var url = "ajax_edit_users.php";
    var params = "cmd=update_user&id=" + encodeURIComponent(id) + 
        "&username=" + encodeURIComponent(username) +  
        "&password=" + encodeURIComponent(password) + 
        "&fullname="+encodeURIComponent(fullname) + 
        "&email="+encodeURIComponent(email) + 
        "&isactive="+encodeURIComponent(isactive) + 
        "&post="+encodeURIComponent(post) + 
        "&isadmin="+encodeURIComponent(isadmin) + 
        "&autodownload="+encodeURIComponent(autodownload) + 
        "&allow_erotica="+encodeURIComponent(allow_erotica) + 
        "&allow_update="+encodeURIComponent(allow_update) + 
        "&seteditor="+encodeURIComponent(seteditor) + 
        "&fileedit="+encodeURIComponent(fileedit) + 
        "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        if (xmlHttp.responseText == 'OK') {
            hide_overlayed_content();
            show_users();
        }
        update_message_bar(xmlHttp);
    }
    );
}


function update_usenet_server()
{
    var id = get_value_from_id('id');
    var challenge = get_value_from_id('challenge');
    var name = get_value_from_id('name');
    var port = get_value_from_id('port');
    var sec_port = get_value_from_id('sec_port');
    var hostname = get_value_from_id('hostname');
    var username = get_value_from_id('username');
    var password = get_value_from_id('password');
    var authentication = get_value_from_id('needauthentication');
    var priority = get_value_from_id('priority');
    var connection = get_value_from_id('connection');
    var compressed_headers = get_value_from_id('compressed_headers');
    var threads = get_value_from_id('threads');
    var posting = get_value_from_id('posting');
    var url = "ajax_edit_usenet_servers.php";
    var params = "cmd=update_usenet_server&id=" +encodeURIComponent(id) + 
        "&name=" + encodeURIComponent(name) +
        "&hostname=" + encodeURIComponent(hostname) +
        "&username=" + encodeURIComponent(username) + 
        "&port=" + encodeURIComponent(port) + 
        "&secure_port=" + encodeURIComponent(sec_port) +
        "&password=" + encodeURIComponent(password) + 
        "&authentication=" + encodeURIComponent(authentication) + 
        "&priority=" + encodeURIComponent(priority) + 
        "&connection=" + encodeURIComponent(connection) +
        "&threads=" + encodeURIComponent(threads) + 
        "&challenge=" + encodeURIComponent(challenge) +  
        "&compressed_headers=" + encodeURIComponent(compressed_headers) + 
        "&posting=" + encodeURIComponent(posting);
    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        if (xmlHttp.responseText == 'OK') {
            hide_overlayed_content();
            show_usenet_servers();
        }
        update_message_bar(xmlHttp);
    }
    );
}


function post_message()
{
    var subject = get_value_from_id('subject');
    var postername = get_value_from_id('postername');
    var posteremail = get_value_from_id('posteremail');
    var challenge = get_value_from_id('challenge');
    var message = get_value_from_id('messagetext');
    var groupid = document.getElementById('groupid');
    var url = "ajax_post_message.php";
    var params = "cmd=post&subject=" + encodeURIComponent(subject)
        + "&postername=" + encodeURIComponent(postername)
        + "&posteremail=" + encodeURIComponent(posteremail)
        + "&message=" + encodeURIComponent(message)
        + "&groupid=" + encodeURIComponent(groupid.options[groupid.selectedIndex].value)
        + "&challenge=" + encodeURIComponent(challenge);  

    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        if (xmlHttp.responseText.substr(0,2) == 'OK') {
            hide_overlayed_content();
        }
        update_message_bar(xmlHttp);
    }
    );
}


function create_post()
{
    var subject =get_value_from_id('subject');
    var postername = get_value_from_id('postername');
    var posteremail = get_value_from_id('posteremail');
    var recovery = get_value_from_id('recovery');
    var filesize = get_value_from_id('filesize');
    var postid = get_value_from_id('postid');
    var groupid = document.getElementById('groupid');
    var directory = document.getElementById('directory');
    var delete_files = get_value_from_id('delete_files');
    var timestamp = get_value_from_id('timestamp');
    var challenge = get_value_from_id('challenge');
    var url = "ajax_process_post.php";
    var params = "cmd=post&subject=" + encodeURIComponent(subject)
        + "&postername=" + encodeURIComponent(postername)
        + "&posteremail=" + encodeURIComponent(posteremail)
        + "&delete_files=" + encodeURIComponent(delete_files)
        + "&recovery=" + encodeURIComponent(recovery)
        + "&groupid=" + encodeURIComponent(groupid.options[groupid.selectedIndex].value)
        + "&directory=" + encodeURIComponent(directory.options[directory.selectedIndex].value)
        + "&postid=" + encodeURIComponent(postid)
        + "&filesize=" + encodeURIComponent(filesize)
        + "&challenge=" + encodeURIComponent(challenge)  
        + "&timestamp=" + encodeURIComponent(timestamp);

    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
            if (xmlHttp.responseText == 'OK') {
            hide_overlayed_content();
        }
        update_message_reload_transfers(xmlHttp);
    }
    );
}


function rename_transfer()
{
    var dlid = get_value_from_id('dlid');
    var dlname = get_value_from_id('dlname');
    var dlpass = get_value_from_id('dlpass');
    var unrar = get_value_from_id('unrar');
    var unpar = get_value_from_id('unpar');
    var subdl = get_value_from_id('subdl');
    var starttime = get_value_from_id('timestamp');
    var dl_dir = get_value_from_id('dl_dir');
    var deletef = get_value_from_id('delete_files');
    var add_setname = get_value_from_id('add_setname');
    var challenge = get_value_from_id('challenge');

    var url = "ajax_edittransfers.php";
    var params = "cmd=rename" 
        + "&dlid=" + encodeURIComponent(dlid) 
        + "&dlname=" + encodeURIComponent(dlname)
        + "&dlpass=" + encodeURIComponent(dlpass)
        + "&delete=" + encodeURIComponent(deletef)
        + "&unrar=" + encodeURIComponent(unrar)
        + "&subdl=" + encodeURIComponent(subdl)
        + "&dl_dir=" + encodeURIComponent(dl_dir)
        + "&add_setname=" + encodeURIComponent(add_setname)
        + "&unpar=" + encodeURIComponent(unpar)
        + "&starttime=" + encodeURIComponent(starttime)
        + "&challenge=" + encodeURIComponent(challenge);

    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        if (xmlHttp.responseText == 'OK') {
            hide_overlayed_content();
        }
        
        update_message_reload_transfers(xmlHttp);
    }
    );
}


function do_submit_feed(name, feed)
{
    var feedid = document.getElementById('select_feedid');
    feedid.value = feed;
    do_submit(name);
}


function clean_search(search_id)
{
    var search = document.getElementById(search_id, '');
    var search_str = get_value_from_id('search_str', '');
    if (search.value == search_str) {
        search.value = '';
    }
}


function do_submit(name)
{
    var searchform = document.getElementById(name);
    searchform.submit();
}


function do_submit_repost(name)
{
    var theform = document.getElementById(name);
    var url = theform.action;
    var params = '';
    for(var i=0; i < theform.elements.length; i++) {
        params = params + '&' + encodeURIComponent(theform.elements[i].name) + '=' + encodeURIComponent(theform.elements[i].value);
    }

    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        update_message_bar(xmlHttp);
    }
    );
    jump(url);
}


function follow_link(id)
{
    var name = get_value_from_id("link_" + id, '');
    if (name !== '') {
        jump(name);
    }
}


function view_files_follow_link(e, type, fileid, idx)
{
    var d = new Date();
    if (mouse_click_time > 0 && (d.getTime() - mouse_click_time) > 300) {
        return false;
    }
    mouse_click_time = 0;
    var name = get_value_from_id(fileid);
    var dir = get_value_from_id('dir');

    if (name == '' || dir == '') { 
        return false;
    }
    if (e.which == 3) {
        return false;
    }
    var path = dir + name;
    if (type == 'dir') {
        jump('viewfiles.php?dir=' + encodeURIComponent(path), e.which == 2);
    } else if (type == 'picture') {
        show_image(path, idx);
    } else if (type == 'text') {
        show_contents(path, idx);
    } else {
        jump('getfile.php?idx=' + encodeURIComponent(idx) + '&file=' + encodeURIComponent(path), e.which == 2);
    }
    return true;
}


function rename_file_form(fileid)
{
    var challenge = get_value_from_id('challenge');
    var name = get_value_from_id(fileid);
    var dir = get_value_from_id('dir');
    if (name == '' || dir == '') {
        return;
    }

    var url = "ajax_editviewfiles.php";
    var params = "cmd=show_rename&dir=" + encodeURIComponent(dir) +
        "&filename=" +encodeURIComponent(name) +
        "&challenge=" + encodeURIComponent(challenge);

    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        show_overlayed_content(xmlHttp, 'popup525x300');
    }
    );
}


function update_filename()
{
    var challenge = get_value_from_id('challenge');
    var directory = get_value_from_id('directory_editfile');
    var oldfilename = get_value_from_id('oldfilename_editfile');
    var newfilename = get_value_from_id('newfilename_editfile');
    var rights = get_value_from_id('rights_editfile');
    var group = get_value_from_id('group_editfile');
    var url ="ajax_editviewfiles.php";
    var params = "cmd=do_rename&dir=" + encodeURIComponent(directory) 
        + "&oldfilename=" +encodeURIComponent(oldfilename)
        + "&newfilename=" +encodeURIComponent(newfilename)
        + "&challenge=" + encodeURIComponent(challenge)
        + "&rights=" + encodeURIComponent(rights)
        + "&group=" + encodeURIComponent(group);

    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        hide_overlayed_content();
        show_files( { 'curdir':directory, 'reset_offset': false });
        update_message_bar(xmlHttp);
    }
    );
}



function update_quick_menu_images()
{
    // Loop all "quickmenuitem_x" divs:
    var themaindiv = document.getElementById('quickmenu');
    var theinnerdiv = document.getElementById('quickmenuinner');
    var divwidth = themaindiv.offsetWidth;
    var divheight = themaindiv.offsetHeight;

    // Assuming 400x10
    var width = 400;
    var height = 18;
    var offsety = 0;

    // Number of items to show:
    var quicks = document.getElementById('nrofquickmenuitems').value;
    if (quicks <= 0) {
        CloseQuickMenu();
        return;
    }
    var thediv;
    for (var i = 1; i <= quicks; i++) {
        thediv = document.getElementById('quickmenuitem_' + i);
        thediv.style.left = '0px';
        thediv.style.top = ( (i - 1) * height) + 'px';
    }
    theinnerdiv.style.marginTop = '10px';
    theinnerdiv.style.height = (quicks * height) + 'px';
}


function CloseQuickMenu()
{
    hide_popup('quickmenu', 'quickmenu');
}



function ShowQuickMenu(type, subject, srctype, e)
{
    var rightclick;
    if (!e) {
        e = window.event;
    } else if (e.which) {
        rightclick = (e.which == 3);
    } else if (e.button) {
        rightclick = (e.button == 2);
    }

    // We don't show the quickmenu if it was a right mouse button
    if (rightclick) { 
        return false; 
    }
    // Nor if there's a mouse button pressed.. we'll wait till the user lets go:
    if (mousedown) { 
        return false;
    }
    
    // Create an overlay div
    var thediv = document.getElementById('quickmenu');
    add_class(thediv,'quickmenuon');
    remove_class(thediv,'quickmenuoff');
    var selection = (window.getSelection ? window.getSelection() : document.getSelection ? document.getSelection() : document.selection.createRange().text);
    selection = String(selection);
    selection = (selection === '') ? "0" : "1";

    var killflag = get_value_from_id('killflag');
    // Fill menu
    var url = "ajax_showquickmenu.php";
    var params = "type="+encodeURIComponent(type)+
        "&srctype="+encodeURIComponent(srctype)+
        "&killflag="+encodeURIComponent(killflag)+
        "&selection="+encodeURIComponent(selection)+
        "&subject="+encodeURIComponent(subject);

    SendXMLHTTPGET(url, params, function(xmlHttp) { 
        thediv.innerHTML = xmlHttp.responseText;
        update_quick_menu_images();
    }
    );

    // Loading.
    thediv.innerHTML = "";

    // Make sure it's displayed around the cursor:
    var posx = 0;
    var posy = 0;

    if (e.pageX || e.pageY) {
        posx = e.pageX;
        posy = e.pageY;
    } else if (e.clientX || e.clientY) {
        posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
        posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
    }

    // Sizes:
    var divwidth = thediv.offsetWidth;
    var divheight = thediv.offsetHeight;
    thediv.style.zindex = "10000000";

    // Place invisible div somewhat below/left of the mouse also so it won't hide after moving 1 pixel away
    var newdivleft = posx - 30;
    var newdivtop = posy - 10;

    // Make sure we don't exceed edges:
    if (newdivleft < 0) {
        newdivleft = 0;
    }
    if (newdivleft + divwidth > document.body.offsetWidth) {
        newdivleft = document.body.offsetWidth - divwidth;
    }
    if (newdivtop < 0) {
        newdivtop = 0;
    }
    if (newdivtop + divheight > window.innerHeight) {
        newdivtop = window.innerHeight - divheight;
    }

    thediv.style.left = newdivleft + 'px';
    thediv.style.top = newdivtop + 'px';
    return true;
}


function ShowQuickDisplay(srctype, subject, e, type)
{
    // Fill menu
    var url = "ajax_showquickdisplay.php";
    var params = "type=" + encodeURIComponent(type) +
        "&srctype=" + encodeURIComponent(srctype) +
        "&subject=" + encodeURIComponent(subject);
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            show_overlayed_content(xmlHttp, 'quickwindowon');
            $('#td_sets').scrollTop(0);
    }
    );
}


function GuessExtSetInfoSafe(setID, type)
{
    var setname = (window.getSelection ? window.getSelection() : document.getSelection ? document.getSelection() : document.selection.createRange().text);
    setname = String(setname);
    if (setname === '') {
        show_alert("Please select the set name before clicking this button.");
    } else {
        var url = "ajax_showquickdisplay.php";
        var params = "srctype=setguessesisafe&subject=" +encodeURIComponent(setID) 
            + "&type=" + encodeURIComponent(type)
            + "&setname=" + encodeURIComponent( setname);
        SendXMLHTTPGET(url, params, function(xmlHttp) { 
            ShowQuickDisplay('seteditesi',setID,'', type);
        }
        );
    }
}


function Reload()
{
    var url = document.location.href;       
    window.location = url;
}


function GuessBasketExtSetInfo(setID, type)
{
    var setname = (window.getSelection ? window.getSelection() : document.getSelection ? document.getSelection() : document.selection.createRange().text);
    setname = String(setname);
    if (setname === '') {
        show_alert("Please select the set name before clicking this button.");
    } else {
        var url = "ajax_showquickdisplay.php";
        var params = "srctype=setbasketguessesi" + 
            "&subject=undefined" +
            "&type=undefined" + 
            "&setname=" +encodeURIComponent(setname) ; 
        SendXMLHTTPGET(url, params, function(xmlHttp) { 
            // Reload to show the new info:
            load_sets();
            CloseQuickMenu();
        }
        );
    }
    return true;
}


function GuessExtSetInfo(setID, type)
{
    var setname = (window.getSelection ? window.getSelection() : document.getSelection ? document.getSelection() : document.selection.createRange().text);
    setname = String(setname);
    if (setname == '') {
        show_alert("Please select the set name before clicking this button.");
    } else {
        var url = "ajax_showquickdisplay.php";
        var params = "srctype=setguessesi&subject="+setID+"&type="+type+"&setname="+setname;
        SendXMLHTTPGET(url, params, function(xmlHttp) { 
            CloseQuickMenu();
            // Also echo the new setname into the TD:
            var thetd = document.getElementById('td_set_' + setID);
            if (xmlHttp.responseText !== '') {
                thetd.innerHTML = '<div class="donotoverflowdamnit">' +  xmlHttp.responseText + '</div>';
                update_widths('browsesubjecttd');
            }
        }
        );
    }
}


function SaveExtSetBinaryType(setID,sel, srctype, type)
{
    var binarytype = sel.options[sel.selectedIndex].value;
    var url = "ajax_showquickdisplay.php";
    var params = "srctype=setsavebintype" 
        + "&subject=" + encodeURIComponent(setID) +
        "&type="+encodeURIComponent(type)+ 
        "&values[binarytype]=" +encodeURIComponent( binarytype);
    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        ShowQuickDisplay('seteditesi',setID,'', type);
    }
    );
}


function SaveExtSetInfo(setID, type)
{
    hide_overlayed_content();
    var formname = 'ext_setinfo_' + setID;
    var url = "ajax_showquickdisplay.php";
    var params = "srctype=setsaveesi" + 
        "&subject=" + encodeURIComponent(setID) +
        "&type=" + encodeURIComponent(type);
    for (var i = 0; i<document.forms[formname].elements.length; i++) {
        params = params + '&' + encodeURIComponent(document.forms[formname].elements[i].name) + '=' + encodeURIComponent(document.forms[formname].elements[i].value);
    }

    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        //CloseQuickDisplay();
        // Also echo the new setname into the TD:
        var thetd = document.getElementById('td_set_' + setID);
        if (xmlHttp.responseText !== '') {
            thetd.innerHTML = xmlHttp.responseText;
        }
    }
    );
}


function remove_rss(id, msg)
{
    var resp = show_confirm(msg, function() {
        var challenge = get_value_from_id('challenge');
        SendXMLHTTPPOST('ajax_editrss.php', 'cmd=delete&id='+id+'&challenge='+encodeURIComponent(challenge), function(xmlHttp) {
            if (xmlHttp.responseText.substr(0, 7) == ':error:') {
                set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
            } else {
                load_subscriptions(xmlHttp);
            }
        }
        );
    }
    );
}


function confirm_delete_account(id, msg)
{
    var challenge = get_value_from_id('challenge');
    var resp = show_confirm(msg, function () {
        SendXMLHTTPPOST('ajax_delete_account.php', 'delete_account=1&challenge='+encodeURIComponent(challenge), function(xmlHttp) {
            if (xmlHttp.responseText.substr(0,2) == 'OK') {
                show_alert(xmlHttp.responseText.substr(2));
                setTimeout(function (){ jump('logout.php'); } ,5000);
            } else if (xmlHttp.responseText.substr(0,7) == ':error:') {
                set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
            } else {
                set_message('message_bar', xmlHttp.responseText, 5000);
                }
        }
        );
    }
    );
    return true;
}


function fold_transfer(id, type)
{
    // id = global or ready/active/finished/error/etc...
    // type = down/post
    SendXMLHTTPGET('ajax_update_session.php', 'var='+encodeURIComponent(id)+'&type='+encodeURIComponent(type), null);
    ToggleClassById(id + type, 'dynimgplus');
    ToggleClassById(id + type, 'dynimgminus');

    if (type == 'post') {
        ToggleClassById('data_post_'+id, 'hidden');
    } else {
        ToggleClassById('data_down_'+id, 'hidden');
    }
}


function ToggleGroupOfSets(startset, stopset, type)
{
    var sets = document.getElementsByName("setdata[]");
    var inrange = false;
    var thissetvalue = null;
    var toggleitems = [];

    for (var i = 0; i < sets.length; i++) {
        // We want to toggle the last set as well, but definitely not the first one 
        // (it got toggled when the user clicked it, don't toggle it back)

        // Also, setdata[] stuff always starts with "set_"!
        thissetvalue = sets[i].id.substr(4);

        // Looping through all sets, we will encounter startset and stopset.
        // And not necessarily in that order!

        // Mark all sets between (and including) startset and stopset as toggleitems
        // At the end, de-toggle the startset as that one has already been toggled
        // before this function was called.

        // We'll allow the start/stop sets to be reversed... robustness pwnz.
        if (thissetvalue == startset || thissetvalue == stopset) {
            // We are now either in or out of range:
            if (inrange === false) {
                inrange = true;
            } else {
                inrange = false;
            }
        }

        // If in range, toggle:
        // If startset, do not toggle (already toggled before):
        // If stopset, always toggle:
        if ((inrange === true && thissetvalue !== startset) || thissetvalue === stopset) {
            toggleitems.push(thissetvalue);
        }
    }

    // Now do the actual toggling
    for (var i = 0; i < toggleitems.length; i++) {
        ToggleSet(toggleitems[i], type);
    }
}


function fold_adv_search(button_id, id)
{    
    ToggleClassById(button_id, 'dynimgplus');
    ToggleClassById(button_id, 'dynimgminus');
    ToggleClassById(id, 'hidden');
}


function clear_form(formId) 
{ 
    var form = document.getElementById(formId);
    var type = null;
    for (var y = 0 ; y < form.elements.length; y++) {
        type = form.elements[y].type;
        switch(type) {
            case "hidden":
            case "text":
            case "textarea":
            case "password":
                form.elements[y].value = "";
                break;
            case "radio":
            case "checkbox":
                form.elements[y].checked = "";
                break;
            case "select-one":
                form.elements[y].options[0].selected = true;
                break;
            case "select-multiple":
                for (var z=0; z<form.elements[y].options.length; z++){
                    form.elements[y].options[z].selected = false;
                }
                break;
            default: 
                break;
        }
    }
}


function do_keypress_viewfiles(e)
{
    if (e.which == 13) {
        show_files_clean();
    }
    return true;
}


function collapse_select(name, par)
{
    var orig_size = document.getElementById(name+'_orig_size');
    var sel = document.getElementById(name+'_select');
    var size;
    if (par == 'size') {
        size = sel.size;
    } else if (par == 'rows') {
        size = sel.rows;
    }
    if (size >= orig_size.value) {
        size = 2;
    } else {
        size = orig_size.value;
    }

    ToggleClassById(name + '_collapse', 'dynimgminus');
    ToggleClassById(name + '_collapse', 'dynimgplus');
    if (par == 'size') {
        sel.size = size;
    } else if (par == 'rows') {
        sel.rows = size;
    }
}


function submit_language_login()
{
    var lang = document.getElementById('language_select');
    var change = document.getElementById('language_change');
    var curr_language = document.getElementById('curr_language');
    var myform = document.getElementById('urd_login_form');
    if (lang !== null ) {
        var langval = lang.options[lang.selectedIndex].value;
        change.value = 1;
        if (curr_language === null || curr_language.value != langval) {
            curr_language.value = langval;
            myform.submit();
        }
    }
}


function clean_input(id, name)
{
    var input = document.getElementById(id);
    if (input !== null && input.name != name){
        input.value = "";
        var newname = input.name;
        newname = newname.replace("_", "");
        input.name = newname;
    }
}


function submit_upload()
{

    // need to rewrite to do proper error handling
    var src_remote = get_value_from_id('url'); // its a url we post, to be gotten by the server
    var src_local = get_value_from_id('upfile'); // it's a local file we upload to the server
    var dl_dir = get_value_from_id('dl_dir');
    var uploaded_text = get_value_from_id('uploaded_text');
    var iframe_id = 'iframe_' + String(Math.round(Math.random()* 10000));

    $('<iframe id="' + iframe_id + '" name="' + iframe_id + '" style="margin-top:200px;">').appendTo('body');
   $('#' + iframe_id).hide();
    if (src_remote != '') {
        document.getElementById('parseform').target = iframe_id; // the iframe swallows the upload, so the page does not have to reload
        document.getElementById('timestamp1').value = get_value_from_id('timestamp');
        document.getElementById('add_setname1').value = get_value_from_id('add_setname');
        document.getElementById('setname1').value = get_value_from_id('setname');
        document.getElementById('dl_dir1').value = get_value_from_id('dl_dir');
        document.getElementById('parseform').submit();
        hide_overlayed_content();
    } else if (src_local != '') {
        document.getElementById('uploadform').target = iframe_id; // the iframe swallows the upload, so the page does not have to reload
        document.getElementById('add_setname2').value = get_value_from_id('add_setname');
        document.getElementById('dl_dir2').value = get_value_from_id('dl_dir');
        document.getElementById('setname2').value = get_value_from_id('setname');
        document.getElementById('timestamp2').value = get_value_from_id('timestamp');
        document.getElementById('uploadform').submit();
        hide_overlayed_content();
    } else {
        return false;
    }
  //  $('#' + iframe_id).load( function() {
    var i = 0;
    var poll_iframe = function() {
        i++;
        var msg = document.getElementById(iframe_id).contentWindow.document.body.innerHTML;
        if (msg != null && msg != '') {
            i=21;
            $('#' + iframe_id).remove();
            if (msg == 'OK') {
                set_message('message_bar', '', 5000);
            } else if (msg.substr(0,2) == 'OK') {
                set_message('message_bar', msg.substr(2), 5000);
            } else if (msg.substr(0,7) == ':error:') {
                set_message('message_bar', msg.substr(7), 5000);
            } else {
                set_message('message_bar', msg, 5000);
            }
        }
        if (i < 20) { 
            setTimeout(poll_iframe, 200);
        }
    }
    poll_iframe();
    return true;
}


function show_auth()
{
    var need_auth = get_value_from_id('needauthentication');
    var auth_pass = $('#authpass');
    var auth_user = $('#authuser');

    if (need_auth==1) {
        remove_class(auth_pass, 'hidden');
        remove_class(auth_user, 'hidden');
    } else {
        add_class(auth_pass, 'hidden');
        add_class(auth_user, 'hidden');
    }
}


function edit_file(fileid)
{
    var cmd, name;
    var challenge = get_value_from_id('challenge');
    var dir = get_value_from_id('dir', '');
    if (fileid == '') {
        cmd = 'new_file';
        if (dir == '') {
            return;
        }
        name = '';
    } else {
        name = get_value_from_id(fileid);
        cmd = 'edit_file';
        if (name == ''|| dir == '') {
            return;
        }
    }
    var url = "ajax_editviewfiles.php";
    var params = "cmd="+cmd+"&dir=" + encodeURIComponent(dir) + "&filename=" +encodeURIComponent(name) + "&challenge=" + encodeURIComponent(challenge);

    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            show_overlayed_content(xmlHttp, 'popup700x400');
            $('#filename_editfile').focus();
            }
            );
}


function save_file()
{
    var challenge = get_value_from_id('challenge');
    var name = get_value_from_id('filename_editfile');
    var dir = get_value_from_id('directory_editfile');
    var filename_err = get_value_from_id('filename_err');
    var newfile = document.getElementById('newfile');
    var contents = get_value_from_id('filecontents_editfile');
    var newdir = get_value_from_id('newdir', '0');
    if (dir == '' || contents == '') {
        return false;
    }
    if (name == '') {
        show_alert(filename_err);
        return false;
    }
    newfile = (newfile === null) ? "0" : "1";
    var url = "ajax_editviewfiles.php";
    var params = "cmd=save_file&dir=" + encodeURIComponent(dir) + "&filename=" +encodeURIComponent(name) 
        + "&file_contents="+ encodeURIComponent(contents)
        + "&newfile="+ encodeURIComponent(newfile)
        + "&newdir="+ encodeURIComponent(newdir)
        + "&challenge=" + encodeURIComponent(challenge);

    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        if (xmlHttp.responseText == 'OK') {
            hide_overlayed_content();
            show_files( { 'curdir':null, 'reset_offset': false });
        } else {
            update_message_bar(xmlHttp);
        }
    }
    );
    return true;
}


function edit_categories()
{
    var challenge = get_value_from_id('challenge');
    var url = "ajax_editcategory.php";
    var params = "cmd=edit"
        + "&challenge=" + encodeURIComponent(challenge);
    close_browse_divs();

    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            show_overlayed_content(xmlHttp,'popup525x300');
        $('#cat_name').focus();
    }
    );
}


function get_category_name()
{
    var cat_name = document.getElementById('cat_name');
    var cat_id = document.getElementById('cat_id');
    var idx = document.getElementById('category_id');

    idx = idx.options[idx.selectedIndex].value;
    var url = "ajax_editcategory.php";
    var params = "cmd=get_name"
        + "&id=" + encodeURIComponent(idx);
    SendXMLHTTPPOST(url, params, function(xmlHttp) { 
        var rv = xmlHttp.responseText;
        if (rv != '__error__') {
            cat_name.value = rv;
            cat_id.value = idx; 
        } else {
            cat_name.value = '';
            cat_id.value= 'new'; 
        }
    }
    );
}


function update_category()
{
    var challenge = get_value_from_id('challenge');
    var cat_id = get_value_from_id('cat_id');
    var cat_name = get_value_from_id('cat_name');
    var url = "ajax_editcategory.php";
    if (cat_name == '' || cat_id == '') {
        return;
    }
    var params = "cmd=update_category"
        + "&id=" + encodeURIComponent(cat_id)
        + "&name=" + encodeURIComponent(cat_name)
        + "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        load_subscriptions();
        hide_overlayed_content();
        toggle_table('groupstable', 'user', 'admin');
    }
    );
}


function delete_category()
{
    var challenge = get_value_from_id('challenge');
    var cat_id = get_value_from_id('cat_id');
    var url = "ajax_editcategory.php";
    if (cat_id == '') {
        return;
    }
    var params = "cmd=delete_category"
        + "&id=" + encodeURIComponent(cat_id)
        + "&challenge=" + encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        load_subscriptions();
    }
    );
}


function show_calendar(month, year, clear_time)
{
    var timestamp = get_value_from_id('timestamp');
    var url = "ajax_calendar.php";
    var _minute = new Date().getMinutes();
    var _hour = new Date().getHours();
    var params = "cmd=show_calendar"
        + "&timestamp="+encodeURIComponent(timestamp);
    if (month !== null) {
        params = params + "&month="+encodeURIComponent(month);
    }
    if (year !== null) {
        params = params + "&year="+encodeURIComponent(year);
    }
    if (clear_time !== null) {
        var hour = document.getElementById('hour');
        var minute = document.getElementById('minute');
        if (hour !== null) {
            hour = hour.value;
            params = params + "&hour="+encodeURIComponent(hour);
            _hour = hour;
        }
        if (minute !== null) {
            minute = minute.value;
            params = params + "&minute="+encodeURIComponent(minute);
            _minute = minute;
        }
    }
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        show_overlayed_content2(xmlHttp, 'calendardiv');
        $(function() {
            _hour =$("#hour").val();
            _minute = $("#minute").val();
        $('#hours').slider(
            {
                min: 0,
                max: 23,
                value: _hour,
                slide: function(event, ui) { 
                    $("#hour").val( ui.value );
                    $("#time1").val( ui.value + ':' +  $("#minute").val());
                }
            }
            );
        $('#minutes').slider(
            {
                min: 0,
                max: 59,
                value: _minute,
                slide: function(event, ui) { 
                    $("#minute").val( ui.value );
                    $("#time1").val( $("#hour").val() + ':' +  $("#minute").val());
                }
            }
            );
        }
        );

    }
    );
}


function submit_calendar(none)
{
    if (none != null) {
        $('#timestamp').val(' ');

    } else { 
        $('#timestamp').val($('#date1').val() + ' ' + $('#time1').val());
    }
    if (document.getElementById('basketbuttondiv') !== null) {
        // we're in the basket so we need to update it to store the values
        update_basket_display(1);
    }
    hide_overlayed_content2();
}


function select_calendar(day)
{
    var month = get_value_from_id('month');
    var year = get_value_from_id('year');
    var old_day = get_value_from_id('day', 0);
    var old_day2 = document.getElementById('day_' + old_day);
    var new_day = document.getElementById('day_' + day);
    ToggleClass(new_day, 'highlight3');
    if (old_day != 0) {
        ToggleClass(old_day2, 'highlight3');
    }
    var date1 = document.getElementById('date1');
    document.getElementById('day').value = day;
    date1.value = year + '-' + month + '-' + day;
}


function clear_checkbox(id)
{
    var box = document.getElementById(id);
    var img = document.getElementById(id + '_img');
    if (box != null && img != null) {
        box.value = 0;
        remove_class(img, 'checkbox_on');
        remove_class(img, 'checkbox_tri');
        add_class(img, 'checkbox_off');
    }
}


function set_checkbox(id, val)
{
    var box = document.getElementById(id);
    var img = document.getElementById(id + '_img');
    if (box != null && img != null) {
        box.value = val;
        remove_class(img, 'checkbox_on');
        remove_class(img, 'checkbox_tri');
        remove_class(img, 'checkbox_off');
        if (val == 1) {
            add_class(img, 'checkbox_on');
        } else if (val == 2) {
            add_class(img, 'checkbox_tri');
        } else {
            add_class(img, 'checkbox_off');
        }
    }
}


function clear_all_checkboxes(cat)
{
    var check_boxes = document.getElementsByTagName('input');
    if (cat === null) { // clear all checkboxes
        for(var i=0; i < check_boxes.length; i++) {
            if (check_boxes[i].name.substr(0, 7) == 'subcat_' ) {
                clear_checkbox(check_boxes[i].id);
            }
        }
    } else { // clear only those in a specific category
        for(var i=0; i<check_boxes.length; i++) {
            if (check_boxes[i].name.substr(0, 8) == 'subcat_' + cat) {
                clear_checkbox(check_boxes[i].id);
            }
        }
    }
}


function update_adult(type, id)
{
    var box = get_value_from_id('adult_' + id);
    var challenge = get_value_from_id('challenge');
    var url, params;
    if (type == 'group') { 
        url = "ajax_groups.php";
        params = "cmd=toggle_adult"
        + "&group_id=" + encodeURIComponent(id)
        + "&value=" + encodeURIComponent(box)
        + "&challenge=" + encodeURIComponent(challenge);
    } else if (type == 'rss') {
        url = "ajax_rss_feeds.php";
        params = "cmd=toggle_adult"
        + "&feed_id=" + encodeURIComponent(id)
        + "&value=" + encodeURIComponent(box)
        + "&challenge=" + encodeURIComponent(challenge);
    } else {
        return;
    }
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        if (xmlHttp.responseText.substr(0,2) != 'OK') {
            update_message_bar(xmlHttp);
        }
    }
    );
}


function change_checkbox(id, tristate)
{
    var box = $('#' + id);
    var img = $('#' + id + '_img');
    if (box === null || img === null) { 
        return null;
    }
    if (box.val() == 1) {
        if (tristate != null) {
            box.val(2);
            set_checkbox(id, 2);
        } else {
            box.val(0);
            set_checkbox(id, 0);
        }
    } else if (box.val() == 2) {
        box.val(0);
        set_checkbox(id, 0);
    } else {
        box.val(1);
        set_checkbox(id, 1);
    }
    return box.val();
}


function toggle_table(table_id, scope_off, scope_on)
{
    var table = document.getElementById(table_id);
    var page_tab = document.getElementById('page_tab');
    var page1 = document.getElementById('page1');
    var global = document.getElementById('button_global');
    var user = document.getElementById('button_user');
    var rowcount = table.rows.length;
    var colcount = 0;
    page_tab.value = scope_on;
    if (scope_on == 'admin') {
        remove_class(user, 'tab_selected');
    } else {
        add_class(user, 'tab_selected');
    }
    if (scope_on == 'admin') {
        add_class(global, 'tab_selected');
    } else {
        remove_class(global, 'tab_selected');
    }

    for (var i = 0; i < rowcount; i++) {
        colcount = table.rows[i].cells.length;
        for (var j = 0; j < colcount ; j++) {
            if (has_class(table.rows[i].cells[j], scope_off)) {
                add_class(table.rows[i].cells[j], 'hidden');
                page1.value = scope_on;
            }
            if (has_class(table.rows[i].cells[j], scope_on)) {
                remove_class(table.rows[i].cells[j], 'hidden');
            }
        }
    }
}


function has_class(item, classname)
{
    return $(item).hasClass(classname);
}


function print_r(x, max, sep, l) 
{
    l = l || 0;
    max = max || 10;
    sep = sep || ' ';

    if (l > max) {
        return "[WARNING: Too much recursion]\n";
    }
    var i, r = '', t = typeof x, tab = ''; 
    if (x === null) {
        r += "(null)\n";
    } else if (t == 'object') {
        l++;
        for (i = 0; i < l; i++) {
            tab += sep;
        }
        if (x && x.length) {
            t = 'array';
        }
        r += '(' + t + ") :\n";
        for (i in x) {
            try {
                r += tab + '[' + i + '] : ' + print_r(x[i], max, sep, (l + 1));
            } catch(e) {
                return "[ERROR: " + e + "]\n";
            }
        }
    } else {
        if (t == 'string') {
            if (x == '') {
                x = '(empty)';
            }
        }
        r += '(' + t + ') ' + x + "\n";
    }
    return r;
}


function select_tab_setting(tab, session_var, session_val)
{
    if (session_var !== null && session_val !== null) {
        SendXMLHTTPGET('ajax_update_session.php', 'var='+encodeURIComponent(session_val)+'&type='+encodeURIComponent(session_var), null);
    }
    var x = document.getElementsByName('tabs');
    for (var i = 0; i < x.length; i++) {
        var content = document.getElementById(x[i].value + '_tab');
        var button = document.getElementById(x[i].value + '_bar');
        var button_elem = document.getElementById(x[i].value + '_bar_elem');
        if (!has_class(content, 'hidden')) {
            add_class(content, 'hidden');
        }
        remove_class(button_elem, 'tab_selected');
        remove_class(button, 'tab_selected');
    }
    var id = document.getElementById(tab + '_tab');
    var cur_tab = document.getElementById('current_tab');
    var bar = document.getElementById(tab + '_bar_elem');
    cur_tab.value = tab;
    remove_class(id, 'hidden');
    add_class(bar, 'tab_selected');
}


function select_tab_transfers(tab, session_var, session_val)
{
    if (session_var !== null && session_val !== null) {
        SendXMLHTTPGET('ajax_update_session.php', 'var='+encodeURIComponent(session_val)+'&type='+encodeURIComponent(session_var), null);
    }
    var x = document.getElementsByName('tabs');
    for (var i = 0; i < x.length; i++) {
        var button = document.getElementById(x[i].value + '_bar');
        remove_class(button, 'tab_selected');
    }
    var cur_tab = document.getElementById('active_tab');
    cur_tab.value = tab;
    var id = document.getElementById(tab + '_bar');
    add_class(id, 'tab_selected');
    update_transfers();
}


function get_window_height()
{
    return $(window).height();
}


function get_window_width()
{
    return $(window).width();
}


function select_tab_stats(tab, type, year, period, source, subtype)
{
    var oktab = document.getElementById(tab + '_bar');
    var show_stats = document.getElementById('show_stats');
    var selected = document.getElementById('selected');
    var url = 'ajax_stats.php';
    if (tab == 'spots_details') {
        type = 'spots_details';
        tab = 'supply';
        period = subtype = source = year = null;
    } else if (tab == 'supply' && type != 'spots_details' && period == null) {
        type = 'supply';
        period = subtype = source = year = null;
    } else if (tab == 'supply_details' ) {
        type = 'supply';
        tab = 'supply';
        period = 'month';
        subtype = source = year = null;
    }

    var width = (get_window_width() ) / 2.2;
    var params = 'type=' + encodeURIComponent(type);
    params = params + '&width=' + encodeURIComponent(String(width));
    if (year != null) {
        params = params + '&year=' + encodeURIComponent(year);
    }
    if (period != null) {
        params = params + '&period=' + encodeURIComponent(period);
    }
    if (subtype != null) {
        params = params + '&subtype=' + encodeURIComponent(subtype);
    }
    if (source != null) {
        params = params + '&source=' + encodeURIComponent(source);
    }
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
        show_stats.innerHTML = xmlHttp.responseText;
        var x = document.getElementsByName('tabs');
        for (var i = 0; i < x.length; i++) {
            var button = document.getElementById(x[i].value + '_bar');
            remove_class(button, 'tab_selected');
        }
        add_class(oktab, 'tab_selected');
        selected.value = type;
    }
    );
}


function show_help(msg, header)
{
    remove_class($('#helpwrapper'), 'hidden');
    $('#helpheader').html(header);
    $('#helpbody').html(msg);
}


function hide_help()
{
    add_class($('#helpwrapper'), 'hidden');
}


var smallhelp_delay=null;
function show_small_help(msg, ev)
{
    var thediv = document.getElementById('smallhelp');
    if (!ev) {
        ev = window.event;
    }

    var posx = ev.clientX ;
    var posy = ev.clientY;
    thediv.innerHTML = wordwrap(msg);
    // if the mouse is too much to the right we simple move the tooltip to the left. 400 seems a resonable size 
    // tooltip is fixed size anyway
    if (posx > 400) {
        posx -= 120;
    }
    thediv.style.left = (posx + 1) + "px";
    thediv.style.top = (posy + 1) + "px";
    thediv.style.marginleft = "50%";
    remove_class(thediv, 'hidden');
}


function _hide_small_help()
{
    if(smallhelp_delay !== null) {
        return;
    }
    smallhelp_delay = setTimeout(_hide_small_help, 10);
}


function hide_small_help()
{
    var help_div = document.getElementById('smallhelp');
    add_class(help_div, 'hidden');
    smallhelp_delay = null;
}


function load_sets(options)
{
    var type = get_value_from_id('type');
    close_browse_divs();
    if (type == 'groups') {
        load_groupsets(options);
    } else if (type == 'spots') {
        load_spots(options);
    } else {
        load_rsssets(options);
    }
}


function load_subscriptions(options)
{
    var type = get_value_from_id('type');
    if (type == 'groups') {
        load_groups(options);
    } else {
        load_rss_feeds(options);
    }
}


function select_update(selector, value)
{
    var group_id = document.getElementById(selector);
    if (group_id === null) {
        return; 
    }
    for(var index = 0; index < group_id.options.length; index++) {
        if (group_id.options[index].value == value) {
            group_id.selectedIndex = index;
            return;
        }
    }
}


function close_browse_divs()
{
    CloseQuickMenu();
    hide_small_help();
    hide_help();
}


function get_subcats_from_form(searchform)
{
    var params = '', name, value;
    var form = document.getElementById(searchform);
    if (form === null) { return '';}
    for(var i=0; i < form.elements.length; i++) {
        name = form.elements[i].name;
        value = form.elements[i].value;
        if (value > 0) {
            params = params + '&' + name + '=' + value;
        }
    }
    return params;
}


function explode(delimiter, string, limit) {
    // Splits a string on string separator and return array of components. If limit is positive only limit number of components is returned. If limit is negative all components except the last abs(limit) are returned.  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/explode    // +     original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: kenneth
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: d3x
    // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)    // *     example 1: explode(' ', 'Kevin van Zonneveld');
    // *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
    // *     example 2: explode('=', 'a=bc=d', 2);
    // *     returns 2: ['a', 'bc=d']
    var emptyArray = {  0: '' };
 
    // third argument is not required
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {        
        return null;
    }
 
    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;    
    }
 
    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
        return emptyArray;
    } 
    if (delimiter === true) {
        delimiter = '1';
    }
     if (!limit) {
        return string.toString().split(delimiter.toString());
    } else {
        // support for limit argument
        var splitted = string.toString().split(delimiter.toString());        
        var partA = splitted.splice(0, limit - 1);
        var partB = splitted.join(delimiter.toString());
        partA.push(partB);
        return partA;
    }
}


function update_search_names(name)
{
    var url = "ajax_saved_searches.php";
    var type =  get_value_from_id('usersettype', '');
    var params = "&cmd=names";
    params = params + "&type=" + encodeURIComponent(type);
    params = params + "&current=" + encodeURIComponent(name);
    SendXMLHTTPGET(url, params, function(xmlHttp) {
        var save_name_id = document.getElementById('save_search_span');
        if (xmlHttp.responseText.substr(0,5) == 'EMPTY') {
            add_class(document.getElementById('save_search_outer'), 'hidden');
            save_name_id.innerHTML = '';
        } else {
            save_name_id.innerHTML = xmlHttp.responseText;
            remove_class(document.getElementById('save_search_outer'), 'hidden');
        }
    }
    );
}


function show_savename()
{
    var url = 'ajax_saved_searches.php';
    var save_name = '';
    var save_name_id = document.getElementById('saved_search');
    if (save_name_id != null) {
        save_name = save_name_id.options[ save_name_id.selectedIndex ].value;
    } 
    var type = get_value_from_id('usersettype', '');
    var params = 'type=' + encodeURIComponent(type);
    params = params +'&name=' + encodeURIComponent(save_name);
    params = params +'&cmd=show';
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            show_overlayed_content(xmlHttp, 'savenamediv');
            $('#savename_val').focus();
        }
    );
}


function delete_search_confirm()
{
    var save_name_id = document.getElementById('saved_search');
    if (save_name_id.selectedIndex == 0) {
        return;
    }

    var sname = save_name_id.options[ save_name_id.selectedIndex].value;
    var msg = get_value_from_id('ln_delete_search', 'Delete');
    show_confirm(msg + ': "' + sname + '"', delete_search);
}


function delete_search()
{
    var save_name_id = document.getElementById('saved_search');
    var sname = save_name_id.options[ save_name_id.selectedIndex].value;
     
    if (save_name_id.selectedIndex == 0) {
        return;
    }
    var url = "ajax_saved_searches.php";

    var params = "name=" + encodeURIComponent(sname);
    var type = get_value_from_id('usersettype', '');
    params = params + "&type=" + encodeURIComponent(type);
    params = params + "&cmd=delete";
    SendXMLHTTPGET(url, params, function(xmlHttp) 
        { 
            if (xmlHttp.responseText.substr(0,2) == 'OK') {
                update_search_names('');
                set_message('message_bar', xmlHttp.responseText.substr(2) , 5000);
            } else {
                update_message_bar(xmlHttp);
            }
        }
    );
}


function save_browse_search()
{
    hide_overlayed_content();
    var save_name_id = document.getElementById('saved_search');
    var category_id = document.getElementById('category_id');
    var url = "ajax_saved_searches.php";
    var params = 'cmd=save';
    var save_category='';
    var search = get_value_from_id('search', '');
    if (category_id !== null) {
        save_category = category_id.options[ category_id.selectedIndex ].value;
    }
    var type = get_value_from_id('usersettype', '');
    var sname = get_value_from_id('savename_val', '');

    var flagid = document.getElementById('flag');
    var flag = flagid.options[flagid.selectedIndex].value;
    var groupid = document.getElementById('select_groupid');
    if (groupid !== null) {
        var group = groupid.options[groupid.selectedIndex].value;
        params = params + "&group=" + encodeURIComponent(group);
    }
    var feedid = document.getElementById('select_feedid');
    if (feedid !== null) {
        var feed = feedid.options[feedid.selectedIndex].value;
        params = params + "&feed=" + encodeURIComponent(feed);
    }
    var minsetsize = get_value_from_id('minsetsize','');
    var maxsetsize = get_value_from_id('maxsetsize','');
    var minage = get_value_from_id('minage','');
    var maxage = get_value_from_id('maxage','');
    var mincomplete = get_value_from_id('mincomplete','');
    var maxcomplete = get_value_from_id('maxcomplete','');
    var minrating = get_value_from_id('minrating','');
    var maxrating = get_value_from_id('maxrating','');
    params = params + "&name=" + encodeURIComponent(sname);
    params = params + "&save_category=" + encodeURIComponent(save_category);
    params = params + "&cat=" + encodeURIComponent("0");
    params = params + "&flag=" + encodeURIComponent(flag);
    params = params + "&minsetsize=" + encodeURIComponent(minsetsize);
    params = params + "&maxsetsize=" + encodeURIComponent(maxsetsize);
    params = params + "&minage=" + encodeURIComponent(minage);
    params = params + "&maxage=" + encodeURIComponent(maxage);
    params = params + "&minrating=" + encodeURIComponent(minrating);
    params = params + "&maxrating=" + encodeURIComponent(maxrating);
    params = params + "&mincomplete=" + encodeURIComponent(mincomplete);
    params = params + "&maxcomplete=" + encodeURIComponent(maxcomplete);
    params = params + "&type=" + encodeURIComponent(type);
    params = params + "&search=" + encodeURIComponent(search);
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            if (xmlHttp.responseText.substr(0,2) == 'OK') {
                update_search_names(sname);
                set_message('message_bar', xmlHttp.responseText.substr(2) , 5000);
            } else {
                update_message_bar(xmlHttp);
            }
        } 
    );
}


function save_spot_search()
{
    var save_name_id = document.getElementById('saved_search');
    var category_id = document.getElementById('category_id');
    var save_category = '';
    if (category_id !== null) {
        save_category = category_id.options[ category_id.selectedIndex ].value;
    }
    var subcats = get_subcats_from_form('searchform');
    var url = "ajax_saved_searches.php";
    var search = get_value_from_id('search', '');
    var sname = get_value_from_id('savename_val', '');
    var cat_id = document.getElementById('select_catid');
    var cat = '';
    if (cat_id != null) {
        var cat = cat_id.options[cat_id.selectedIndex].value;
    }

    var type = get_value_from_id('usersettype', '');
    var flagid = document.getElementById('flag');
    var flag = flagid.options[flagid.selectedIndex].value;
    var minsetsize = get_value_from_id('minsetsize','');
    var maxsetsize = get_value_from_id('maxsetsize','');
    var minage = get_value_from_id('minage','');
    var maxage = get_value_from_id('maxage','');
    var poster = get_value_from_id('poster','');
    var params = "name=" + encodeURIComponent(sname);
    params = params + "&save_category=" + encodeURIComponent(save_category);
    params = params + "&cat=" + encodeURIComponent(cat);
    params = params + "&flag=" + encodeURIComponent(flag);
    params = params + "&minsetsize=" + encodeURIComponent(minsetsize);
    params = params + "&maxsetsize=" + encodeURIComponent(maxsetsize);
    params = params + "&minage=" + encodeURIComponent(minage);
    params = params + "&poster=" + encodeURIComponent(poster);
    params = params + "&maxage=" + encodeURIComponent(maxage);
    params = params + "&cmd=save";
    params = params + "&type=" + encodeURIComponent(type);
    params = params + "&search=" + encodeURIComponent(search);
    params = params + subcats;
    hide_overlayed_content();
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            if (xmlHttp.responseText.substr(0,2) == 'OK') {
            update_search_names(sname);
            set_message('message_bar', xmlHttp.responseText.substr(2) , 5000);
            } else {
            update_message_bar(xmlHttp);
            }
            } 
            );
}


function update_browse_searches(name)
{
    var url = "ajax_saved_searches.php";
    var params = "cmd=get";
    if (name == null ) {
        var save_name_id = document.getElementById('saved_search');

        if (save_name_id.selectedIndex == 0) {
            clear_form("searchform");
            load_sets();
            return;
        }
        name = save_name_id.options[save_name_id.selectedIndex].value;
    } 

    var type = get_value_from_id('usersettype', '');
    params = params + "&type=" + encodeURIComponent(type);
    params = params + "&cat=" + encodeURIComponent(0);
    params = params + "&name=" + encodeURIComponent(name);

    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            var response = xmlHttp.responseText;
            if (response.substr(0, 2) == 'OK' && response.length > 2)  {
            clear_form("searchform");
            update_search_names(name);
            response = response.substr(2);
            var sc_arr = explode('|', response);
            var sw, sc;
            for (var i=0; i < sc_arr.length; i++) {
            if (sc_arr[i] == '') { continue; }
            sc = explode (':', sc_arr[i], 2);

            if (sc[0] == 'minsetsize') { setvalbyid('minsetsize', sc[1]); }
            else if (sc[0] == 'maxsetsize') { setvalbyid('maxsetsize', sc[1]); }
            else if (sc[0] == 'maxage') { setvalbyid('maxage', sc[1]); }
            else if (sc[0] == 'minage') { setvalbyid('minage', sc[1]); }
            else if (sc[0] == 'maxcomplete') { setvalbyid('maxcomplete', sc[1]); }
            else if (sc[0] == 'mincomplete') { setvalbyid('mincomplete', sc[1]); }
            else if (sc[0] == 'maxrating') { setvalbyid('maxrating', sc[1]); }
            else if (sc[0] == 'minrating') { setvalbyid('minrating', sc[1]); }
            else if (sc[0] == 'flag') { setselectbyid('flag', sc[1]); }
            else if (sc[0] == 'group') { setselectbyid('select_groupid', sc[1]); }
            else if (sc[0] == 'feed') { setselectbyid('select_feedid', sc[1]); }
            else if (sc[0] == 'search') { setvalbyid('search', sc[1]);}
            else if (sc[0] == 'category') { setvalbyid('save_category', sc[1]); }
            }
            load_sets({'offset':'0', 'setid':''});
            } else if (response == 'OK')  {
                clear_form("searchform");
                load_sets({'offset':'0', 'setid':''});
            } else {
                update_message_bar(xmlHttp);
            }
    }
    );
}


function update_spot_searches(name)
{
    var url = "ajax_saved_searches.php";
    var params = "cmd=get";
    if (name == null ) {
        var save_name_id = document.getElementById('saved_search');
        if (save_name_id.selectedIndex == 0) {
            clear_form("searchform");
            load_sets();
            return;
        }
        name = save_name_id.options[save_name_id.selectedIndex].value;
    } 
    var type = get_value_from_id('usersettype', '');
    params = params + "&type=" + encodeURIComponent(type);
    params = params + "&name=" + encodeURIComponent(name);
    SendXMLHTTPGET(url, params, function(xmlHttp) 
            { 
            var response = xmlHttp.responseText;
            if (response.substr(0, 2) == 'OK' && response.length > 2)  {
            clear_form("searchform");
            update_search_names(name);
            response = response.substr(2);
            var sc_arr = explode('|', response);
            var searchval = sc_arr[0];
            var sw, sc1, sc2, sc, img, cat;
            clear_all_checkboxes(null); 
            for (var i=0; i < sc_arr.length; i++) {
            if (sc_arr[i] == '') { continue; }
            sc = explode (':', sc_arr[i], 2);
            if (sc[0] == 'minsetsize')      { setvalbyid('minsetsize', sc[1]); }
            else if (sc[0] == 'maxsetsize') { setvalbyid('maxsetsize', sc[1]); }
            else if (sc[0] == 'maxage')     { setvalbyid('maxage', sc[1]); }
            else if (sc[0] == 'minage')     { setvalbyid('minage', sc[1]); }
            else if (sc[0] == 'category')   { setvalbyid('save_category', sc[1]); }
            else if (sc[0] == 'poster')     { setvalbyid('poster', sc[1]); }
            else if (sc[0] == 'flag')       { setselectbyid('minage', sc[1]); }
            else if (sc[0] == 'cat')        { cat = sc[1]; setselectbyid('select_catid', sc[1]); }
            else if (sc[0] == 'search')     { setvalbyid('search', sc[1]);}
            else {
                sc1 = sc[0][0];
                sc2 = sc[0].substr(1);
                sw = sc[1];
                set_checkbox('subcat_' + cat + '_' + sc1 + '_' + sc2, sw);
            }
            }
            load_sets( {'offset':'0', 'setid':'' });
            } else if (response == 'OK')  {
                clear_form("searchform");
                load_sets({'offset':'0', 'setid':''});
            } else {
                update_message_bar(xmlHttp);
            }
            }
    );
}


function load_spots(options)
{ 
    var params = '';
    var search = clean_search_value('search','');
    var minsetsize = get_value_from_id('minsetsize','');
    var maxsetsize = get_value_from_id('maxsetsize','');
    var minage = get_value_from_id('minage','');
    var maxage = get_value_from_id('maxage','');
    var offset = get_value_from_id('offset','');
    var spotid = get_value_from_id('spotid','');
    var poster = get_value_from_id('poster','');
    var order = get_value_from_id('searchorder','');
    var old_cat_id = document.getElementById('cat_id');
    var search_id = document.getElementById('search');
    var save_category = document.getElementById('save_category');
    var sets_div = document.getElementById('setsdiv');
    var waiting_div = document.getElementById('waitingdiv');
    var poster_id = document.getElementById('poster');
    var subcatbutton_id = document.getElementById('subcatbutton');
    var cat_id = document.getElementById('select_catid');
    if (cat_id !== null) {
        var cat = cat_id.options[cat_id.selectedIndex].value;
    }
    var subcats = get_subcats_from_form('searchform');
    var flag = document.getElementById('flag');
    if(flag != null) {
        flag = flag.options[flag.selectedIndex].value;
    } else {
        flag = '';
    }
    if (cat_id != null) {
        cat_id = cat_id.options[cat_id.selectedIndex].value;
    } else {
        cat_id = old_cat_id.value;
    }
    var add_rows = 0;
    var per_page = $('#perpage').val();
    if (options != null) {
        if (options.add_rows != null) {
            params = params + "&only_rows=" + encodeURIComponent("1");
            params = params + "&perpage=" + encodeURIComponent(per_page);
            add_rows = 1;
            offset = parseInt( $('#last_line').val());
            $('#last_line').val(offset+parseInt(per_page));
        }
        if (options.offset != null) {
            offset = options.offset;
        }
        if (options.spot_cat != null) {
            //spot categories
            cat_id = options.spot_cat;
        }
        if (options.setid != null) {
            spotid = options.setid;
        }
        if (options.next != null) {
            cat_id = options.next;
        }
        if (options.category != null) {
            // user defined categories
            save_category.value = options.category;
        }
        if (options.search != null) {
            search = options.search;
            search_id.value= search;
        }
        if (options.poster != null) {
            poster = options.poster;
            poster_id.value = poster;
        }
        if (options.subcat != null) {
            subcats = '&' + options.subcat + '=1';
        }
    }
    if (add_rows == 0) {
        remove_class(waiting_div, 'hidden');
        add_class(sets_div, 'hidden');
    }
    var url = "ajax_spots.php";
    params = params + "&search=" + encodeURIComponent(search)
        + "&minsetsize=" + encodeURIComponent(minsetsize)
        + "&maxsetsize=" + encodeURIComponent(maxsetsize)
        + "&minage=" + encodeURIComponent(minage)
        + "&maxage=" + encodeURIComponent(maxage)
        + "&poster=" + encodeURIComponent(poster)
        + "&categoryID=" + encodeURIComponent(cat_id)
        + "&offset=" + encodeURIComponent(offset)
        + "&spotid=" + encodeURIComponent(spotid)
        + "&order=" + encodeURIComponent(order)
        + "&flag=" + encodeURIComponent(flag);
    params = params + subcats;
    close_subcat_selector();
    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            if (add_rows == 0) {
                show_content_div(xmlHttp, 'setsdiv');
                if (!isNaN(parseInt(cat_id, 10))) {
                    remove_class(subcatbutton_id, 'invisible');
                } else {
                    add_class(subcatbutton_id, 'invisible');
                }

                old_cat_id.value = cat_id;
                update_rss_url();
                add_class(waiting_div, 'hidden');
                remove_class(sets_div, 'hidden');
                select_update('select_catid', cat_id);
                update_widths("browsesubjecttd");
            } else {
                if (xmlHttp.responseText.substr(0, 7) != ':error:') {
                    $('#spots_table > tbody:last').append(xmlHttp.responseText);
                    update_widths("browsesubjecttd");
                }
            }
        }
    );
}


function clean_search_value(search_id, default_value)
{
    var search = get_value_from_id(search_id, default_value);
    var search_str = get_value_from_id('search_str', '');
    if (search == search_str) {
        search = '';
    }
    return search;
}


function load_groupsets(options)
{
    var params = '';
    var search = clean_search_value('search','');
    var minsetsize = get_value_from_id('minsetsize','');
    var maxsetsize = get_value_from_id('maxsetsize','');
    var minrating = get_value_from_id('minrating','');
    var maxrating = get_value_from_id('maxrating','');
    var minage = get_value_from_id('minage','');
    var maxage = get_value_from_id('maxage','');
    var mincomplete = get_value_from_id('mincomplete','');
    var save_category = document.getElementById('save_category');
    var maxcomplete = get_value_from_id('maxcomplete','');
    var offset = get_value_from_id('offset','');
    var setid = get_value_from_id('setid','');
    var order = get_value_from_id('searchorder','');
    var old_group_id = document.getElementById('group_id');
    var sets_div = document.getElementById('setsdiv');
    var waiting_div = document.getElementById('waitingdiv');

    var flag = document.getElementById('flag');
    if(flag != null) {
        flag = flag.options[flag.selectedIndex].value;
    } else {
        flag = '';
    }
    var group_id = document.getElementById('select_groupid');
    if(group_id != null) {
        group_id = group_id.options[group_id.selectedIndex].value;
    } else {
        group_id = old_group_id.value;
    }
    var per_page = $('#perpage').val();
    var add_rows = 0;
    if (options != null) {
        if (options.add_rows != null) {
            add_rows = 1;
            params = params + "&only_rows=" + encodeURIComponent('1');
            params = params + "&perpage=" + encodeURIComponent(per_page);
            offset = parseInt( $('#last_line').val());
            $('#last_line').val(offset + parseInt(per_page));
        }

        if (options.group_id != null) {
            group_id = options.group_id;
        }

        if (options.offset != null) {
            offset = options.offset;
        }
        if (options.setid != null) {
            setid = options.setid;
        }
        if (options.next != null) {
            group_id = options.next;
        }
        if (options.category != null) {
            save_category.value = options.category;
        }

    }
    var url = "ajax_browse.php";

    params = params + "&search=" + encodeURIComponent(search)
        + "&minsetsize=" + encodeURIComponent(minsetsize)
        + "&maxsetsize=" + encodeURIComponent(maxsetsize)
        + "&minrating=" + encodeURIComponent(minrating)
        + "&maxrating=" + encodeURIComponent(maxrating)
        + "&minage=" + encodeURIComponent(minage)
        + "&maxage=" + encodeURIComponent(maxage)
        + "&mincomplete=" + encodeURIComponent(mincomplete)
        + "&maxcomplete=" + encodeURIComponent(maxcomplete)
        + "&groupID=" + encodeURIComponent(group_id)
        + "&offset=" + encodeURIComponent(offset)
        + "&setid=" + encodeURIComponent(setid)
        + "&order=" + encodeURIComponent(order)
        + "&flag=" + encodeURIComponent(flag);

    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            if (add_rows == 0) {
                remove_class(waiting_div, 'hidden');
                add_class(sets_div, 'hidden');
                show_content_div(xmlHttp, 'setsdiv');
                old_group_id.value = group_id;
                update_rss_url();
                add_class(waiting_div, 'hidden');
                remove_class(sets_div, 'hidden');
                select_update('select_groupid', group_id);
                update_widths("browsesubjecttd");
            } else {
                $('#sets_table > tbody:last').append(xmlHttp.responseText);
                update_widths("browsesubjecttd");
            }
        }
    );
}


function change_sort_order(val)
{
    var orderval = document.getElementById('searchorder');
    var oldval = orderval.value.toLowerCase();
    oldval = oldval.replace(' asc', '');
    if (oldval == val) {
        orderval.value = val + ' desc';
    } else {
        orderval.value = val + ' asc';
    }
    load_sets();
}


function set_offset(offset)
{
    load_sets( {'offset' : offset } );
}


function select_next_search(selector_id, cnt)
{
    var selector = document.getElementById(selector_id);
    var type = get_value_from_id('type');
    if (selector !== null) {
        if (selector.options[selector.selectedIndex+cnt] !== null) {
            if (type == 'groups') {
                update_browse_searches(selector.options[selector.selectedIndex + cnt].value);
            } else if (type == 'spots') {
                update_spot_searches(selector.options[selector.selectedIndex + cnt].value);
            } else {
                update_browse_searches(selector.options[selector.selectedIndex + cnt].value);
            }
        }
    }
}


function select_next(selector, cnt)
{
    var group_id = document.getElementById(selector);
    if (group_id !== null) {
        if (group_id.options[group_id.selectedIndex+cnt] !== null) {
            load_sets({'next':group_id.options[group_id.selectedIndex+cnt].value, 'offset':0});
        }
    }
}


function load_rsssets(options)
{
    var params = '';
    var search = clean_search_value('search', '');
    var minsetsize = get_value_from_id('minsetsize', '');
    var maxsetsize = get_value_from_id('maxsetsize', '');
    var minrating = get_value_from_id('minrating', '');
    var maxrating = get_value_from_id('maxrating', '');
    var minage = get_value_from_id('minage', '');
    var maxage = get_value_from_id('maxage', '');
    var mincomplete = get_value_from_id('mincomplete', '');
    var maxcomplete = get_value_from_id('maxcomplete', '');
    var offset = get_value_from_id('offset', '');
    var order = get_value_from_id('searchorder', '');
    var old_feed_id = document.getElementById('feed_id');
    var setid = get_value_from_id('setid', '');
    var save_category = document.getElementById('save_category');
    var feed_id = document.getElementById('select_feedid');
    var flag = document.getElementById('flag');
    var waiting_div = document.getElementById('waitingdiv');
    var sets_div = document.getElementById('setsdiv');
    var per_page = $('#perpage').val();
    var add_rows = 0;

    if(flag != null) {
        flag = flag.options[flag.selectedIndex].value;
    } else {
        flag = '';
    }
    if(feed_id != null) {
        feed_id = feed_id.options[feed_id.selectedIndex].value;
    } else {
        feed_id = old_feed_id;
    }

    if (options != null) {
        if (options.add_rows != null) {
            add_rows = 1;
            params = params + "&only_rows=" + encodeURIComponent('1');
            params = params + "&perpage=" + encodeURIComponent(per_page);
            offset = parseInt( $('#last_line').val());
            $('#last_line').val(offset + parseInt(per_page));
        }

        if (options.feed_id != null) {
            feed_id = options.feed_id;
        }

        if (options.offset != null) {
            offset = options.offset;
        }
        if (options.setid != null) {
            setid = options.setid;
        }
        if (options.next != null) {
            feed_id = options.next;
        }
        if (options.category != null) {
            save_category.value = options.category;
        }
    }

    var url = "ajax_rsssets.php";

    params = params + "&search=" + encodeURIComponent(search)
        + "&minsetsize=" + encodeURIComponent(minsetsize)
        + "&maxsetsize=" + encodeURIComponent(maxsetsize)
        + "&minrating=" + encodeURIComponent(minrating)
        + "&maxrating=" + encodeURIComponent(maxrating)
        + "&minage=" + encodeURIComponent(minage)
        + "&maxage=" + encodeURIComponent(maxage)
        + "&mincomplete=" + encodeURIComponent(mincomplete)
        + "&maxcomplete=" + encodeURIComponent(maxcomplete)
        + "&feed_id=" + encodeURIComponent(feed_id)
        + "&offset=" + encodeURIComponent(offset)
        + "&order=" + encodeURIComponent(order)
        + "&setid=" + encodeURIComponent(setid)
        + "&flag=" + encodeURIComponent(flag);

    SendXMLHTTPGET(url, params, function(xmlHttp) { 
            if (add_rows == 0) {
                show_content_div(xmlHttp, 'setsdiv');
                old_feed_id.value = feed_id;

                add_class(waiting_div, 'hidden');
                remove_class(sets_div, 'hidden');
                update_rss_url();
                select_update('select_feedid', feed_id);
                update_widths("browsesubjecttd");
            } else {
                $('#sets_table > tbody:last').append(xmlHttp.responseText);
                update_widths("browsesubjecttd");
            }
        }
    );
}


function update_rss_url()
{
    var rss_url = get_value_from_id('rss_url', '');
    var rss = document.getElementById('rss_id');
    rss.href = rss_url;
}


/*
   Developed by Robert Nyman, http://www.robertnyman.com
   Code/licensing: http://code.google.com/p/getelementsbyclassname/
 */	
function getElementsByClassName(className, tag, elm)
{
    if (document.getElementsByClassName) {
        getElementsByClassName = function (className, tag, elm) {
            elm = elm || document;
            var elements = elm.getElementsByClassName(className),
                nodeName = (tag)? new RegExp("\\b" + tag + "\\b", "i") : null,
                returnElements = [],
                current;
            for(var i=0, il=elements.length; i<il; i+=1){
                current = elements[i];
                if(!nodeName || nodeName.test(current.nodeName)) {
                    returnElements.push(current);
                }
            }
            return returnElements;
        };
    }
    else if (document.evaluate) {
        getElementsByClassName = function (className, tag, elm) {
            tag = tag || "*";
            elm = elm || document;
            var classes = className.split(" "),
                classesToCheck = "",
                xhtmlNamespace = "http://www.w3.org/1999/xhtml",
                namespaceResolver = (document.documentElement.namespaceURI === xhtmlNamespace)? xhtmlNamespace : null,
                returnElements = [],
                elements,
                node;
            for(var j=0, jl=classes.length; j<jl; j+=1){
                classesToCheck += "[contains(concat(' ', @class, ' '), ' " + classes[j] + " ')]";
            }
            try	{
                elements = document.evaluate(".//" + tag + classesToCheck, elm, namespaceResolver, 0, null);
            }
            catch (e) {
                elements = document.evaluate(".//" + tag + classesToCheck, elm, null, 0, null);
            }
            while ((node = elements.iterateNext())) {
                returnElements.push(node);
            }
            return returnElements;
        };
    }
    else {
        getElementsByClassName = function (className, tag, elm) {
            tag = tag || "*";
            elm = elm || document;
            var classes = className.split(" "),
                classesToCheck = [],
                elements = (tag === "*" && elm.all)? elm.all : elm.getElementsByTagName(tag),
                current,
                returnElements = [],
                match;
            for(var k=0, kl=classes.length; k<kl; k+=1){
                classesToCheck.push(new RegExp("(^|\\s)" + classes[k] + "(\\s|$)"));
            }
            for(var l=0, ll=elements.length; l<ll; l+=1){
                current = elements[l];
                match = false;
                for(var m=0, ml=classesToCheck.length; m<ml; m+=1){
                    match = classesToCheck[m].test(current.className);
                    if (!match) {
                        break;
                    }
                }
                if (match) {
                    returnElements.push(current);
                }
            }
            return returnElements;
        };
    }
    return getElementsByClassName(className, tag, elm);
};


function update_widths(the_id)
{
    var oritextwidth = document.getElementById(the_id).offsetWidth;
    // First set all elements to the CURRENT width, this increases the TD size because of padding:
    var setelements = getElementsByClassName('donotoverflowdamnit'); 
    for (var i = 0; i < setelements.length; i++) {
        setelements[i].style.width= oritextwidth + 'px';
    }

    // Can determine the padding by comparing new size with original size:
    var newtextwidth = document.getElementById(the_id).offsetWidth;
    var padding = newtextwidth - oritextwidth;
    var correctedtextwidth = oritextwidth - padding;
    if (padding > 50) { return; } // dirty quick fix....
    // Set it to the correct size, minus the padding that will be auto-added:
    for (var i = 0; i < setelements.length; i++) {
        setelements[i].style.width= correctedtextwidth + 'px';
    }
}


function hover_skipper(element, on)
{
    ToggleClass(element, on);
}


function wordwrap(msg)   
{
    msg = str_replace("_", "<wbr/>_", msg);
    msg = str_replace("-", "<wbr/>-", msg);
    msg = str_replace(".", "<wbr/>.", msg);
    if (msg.length > 25 && msg.search("<wbr/>") < 0) {
        msg = insert_at_every(msg, "<wbr/>", 25);
    }
    return msg;
}


function show_alert(msg)
{
    var url = 'ajax_alert.php';
    msg = wordwrap(msg);
    var params = 'msg=' + encodeURIComponent(msg);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        show_overlayed_content2(xmlHttp, 'alertdiv');
        $('#okbutton').click( function() {
            hide_overlayed_content2();
        }
        );
        var cancelbutton = document.getElementById('cancelbutton');
        if (cancelbutton !== null) {
            hide_overlayed_content2();
        }
    }
    );
}


function str_replace(search, replace, subject, count) 
{ 
    var i = 0,
        j = 0,
        temp = '',
        repl = '',
        sl = 0,
        fl = 0,
        f = [].concat(search),
        r = [].concat(replace),
        s = subject,
        ra = Object.prototype.toString.call(r) === '[object Array]',
        sa = Object.prototype.toString.call(s) === '[object Array]';
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }

    for (i = 0, sl = s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j = 0, fl = f.length; j < fl; j++) {
            temp = s[i] + '';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length - s[i].length) / f[j].length;
            }
        }
    }
    return sa ? s : s[0];
}


function insert_at_every(str, ins, pos)
{
    var l = str.length,
        i = 0,
        res = '';

    while((i+pos) < l) {
        res = res + str.substr(i, pos) + ins;
        i += pos;
    }
    res = res + str.substr(i); 
    return res;
}


function show_confirm(msg, fn)
{
    var url = 'ajax_alert.php';
    var params = 'msg=' + encodeURIComponent(msg)
        + "&allow_cancel=1";
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            show_overlayed_content(xmlHttp, 'alertdiv');
            $('#cancelbutton').click (function() {
                hide_overlayed_content();
            });
            $('#okbutton').click( function() {
                hide_overlayed_content();
                fn(); // set what we should run on ok button press
            });
        }
    );
}


function confirm_reset(msg, form)
{
    return show_confirm(msg, function() {
            return form_submit(form, 'reset');
         }
    );
}

function form_submit_by_id(formid, submittype)
{
    var form = document.getElementById(formid);
    form_submit(form, submittype);
}


function form_submit(form, submittype)
{
    $('#submittype').val(submittype);
    return form.submit();
}


function load_groups(options)
{
    var url = "ajax_groups.php";
    var search = clean_search_value('newsearch','');
    var order = get_value_from_id('order','');
    var order_dir = get_value_from_id('order_dir','');
    var page = get_value_from_id('page','');
    var page_tab = get_value_from_id('page_tab','');
    var cmd = 'show';
    var searchall = get_value_from_id('search_all','');
    var params = '';
    var waiting_div = document.getElementById('waitingdiv');
    var groups_div = document.getElementById('groupsdiv');

    remove_class(waiting_div, 'hidden');
    add_class(groups_div, 'hidden');
    if (options != null) {
        if (options.page != null) {
            page = options.page;
        }
        if (options.order != null) {
            order = options.order;
        }
        if (options.cmd != null) {
            order = options.cmd;
        }
        if (options.defsort != null) {
            if (order_dir == '') { // default value
                order_dir = options.defsort;
            } else if (order_dir == 'asc') {
                order_dir = 'desc';
            } else {
                order_dir = 'asc';
            }
        }
    }

    params = params + "search=" + encodeURIComponent(search)
     + "&cmd=" + encodeURIComponent(cmd)
     + "&order=" + encodeURIComponent(order)
     + "&order_dir=" + encodeURIComponent(order_dir)
     + "&offset=" + encodeURIComponent(page)
     + "&search_all=" + encodeURIComponent(searchall);
    if (page_tab != '') {
        params = params + "&page_tab=" + encodeURIComponent(page_tab);
    }

    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            show_content_div(xmlHttp, 'groupsdiv');

            var urddonline = get_value_from_id('urddonline',0);
            var item = document.getElementById('ng_apply');
            add_class(waiting_div, 'hidden');
            remove_class(groups_div, 'hidden');
            if (urddonline != 1) {
                add_class(item, 'hidden');
            } else {
                remove_class(item, 'hidden');
            }

        }
    );
}


function load_rss_feeds(options)
{
    var url = "ajax_rss_feeds.php";
    var search = get_value_from_id('newsearch','');
    var order = get_value_from_id('order','');
    var order_dir = get_value_from_id('order_dir','');
    var page = get_value_from_id('page','');
    var page_tab = get_value_from_id('page_tab','');
    var cmd = 'show';
    var search_all = get_value_from_id('search_all','');
    var params = '';
    var waiting_div = document.getElementById('waitingdiv');
    var rss_feeds_div = document.getElementById('rss_feeds_div');

    remove_class(waiting_div, 'hidden');
    add_class(rss_feeds_div, 'hidden');
    if (options != null) {
        if (options.page != null) {
            page = options.page;
        }
        if (options.order != null) {
            order = options.order;
        }
        if (options.cmd != null) {
            order = options.cmd;
        }
        if (options.defsort != null) {
            if (order_dir == '') { // default value
                order_dir = options.defsort;
            } else if (order_dir == 'asc') {
                order_dir = 'desc';
            } else {
                order_dir = 'asc';
            }
        }
    }
    params = params + "search=" + encodeURIComponent(search)
     + "&cmd=" + encodeURIComponent(cmd)
     + "&order=" + encodeURIComponent(order)
     + "&order_dir=" + encodeURIComponent(order_dir)
     + "&offset=" + encodeURIComponent(page)
     + "&search_all=" + encodeURIComponent(search_all);

    if (page_tab != '') {
        params = params + "&page_tab=" + encodeURIComponent(page_tab);
    }
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            show_content_div(xmlHttp, 'rss_feeds_div');
            var urddonline = get_value_from_id('urddonline',0);
            var item = document.getElementById('rss_apply');
            var item2 = document.getElementById('rss_new');
            add_class(waiting_div, 'hidden');
            remove_class(rss_feeds_div, 'hidden');
            if (urddonline != 1) {
                add_class(item, 'hidden');
                add_class(item2, 'hidden');
            } else {
                remove_class(item, 'hidden');
                remove_class(item2, 'hidden');
            }
        }
    );
}


function rss_feeds_page(page_offset)
{
    load_rss_feeds({page: page_offset});
}


function group_page(page_offset)
{
    load_groups({page: page_offset});
}


function rss_feeds_update()
{
    var theform = document.getElementById('rssfeedsform');
    var url = 'ajax_rss_feeds.php';
    var params = '';
    var challenge = get_value_from_id('challenge', '');
    params = params + "cmd=" + encodeURIComponent('update');
    for(var i=0; i< theform.elements.length; i++) {
        params = params + '&' + encodeURIComponent(theform.elements[i].name) + '=' + encodeURIComponent(theform.elements[i].value);
    }
    params = params + '&challenge='+encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            var msg = xmlHttp.responseText;
            if (msg.substr(0,2) == 'OK') {
                load_rss_feeds();
                set_message('message_bar', msg.substr(2), 5000);
            } else if (msg.substr(0,7) == ':error:') {
                set_message('message_bar', msg.substr(7), 5000);
            } else {
                set_message('message_bar', msg, 5000);
            }
        }
    );
}

function group_update()
{
    var theform = document.getElementById('newsgroupform');
    var url = 'ajax_groups.php';
    var params = '';
    var challenge = get_value_from_id('challenge', '');
    params = params + "cmd=" + encodeURIComponent('update');
    for (var i=0; i< theform.elements.length; i++) {
        params = params + '&' + encodeURIComponent(theform.elements[i].name) + '=' + encodeURIComponent(theform.elements[i].value);
    }
    params = params + '&challenge='+encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            var msg = xmlHttp.responseText;
            if (msg.substr(0,2) == 'OK') {
                load_groups();
                set_message('message_bar', msg.substr(2), 5000);
            } else if (msg.substr(0,7) == ':error:') {
                set_message('message_bar', msg.substr(7), 5000);
            } else {
                set_message('message_bar', msg, 5000);
            }
        }
    );
}


function show_contents(file, idx)
{
    var url = 'ajax_get_textfile.php';
    var params = '';
    var challenge = get_value_from_id('challenge', '');

    params = params + "file=" + encodeURIComponent(file);
    params = params + "&idx=" + encodeURIComponent(idx);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
                show_overlayed_content(xmlHttp, 'popup700x400');
                var width = $(window).width() * 0.9;
                var height = $(window).height() * 0.9;
                $('#overlay_content').css('width', width);
                $('#overlay_content').css('height', height);
                $('#overlay_content').css('marginTop', (- Math.floor(height / 2)));
                $('#overlay_content').css('marginLeft', (- Math.floor(width / 2)));
                $('#overlay_content').css('top', '50%');
                $('#overlay_content').css('left', '50%');
                var title_height = $('#text_title').height() + 14;
                $('#inner_content').css('height', height - title_height);
        }
    );
}


function show_image(file, idx)
{
    var url = 'ajax_get_image.php';
    var params = '';
    var challenge = get_value_from_id('challenge', '');
    var preview = get_value_from_id('preview', '');
    var width = get_window_width() * 0.9;
    var height = get_window_height() * 0.9;

    params = params + "file=" + encodeURIComponent(file);
    params = params + "&preview=" + encodeURIComponent(preview);
    params = params + "&idx=" + encodeURIComponent(idx);
    params = params + "&width=" + encodeURIComponent(String(width-110));
    params = params + "&height=" + encodeURIComponent(String(height-110));
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            if (preview != 0) { 
                $('#textcontent').html(xmlHttp.responseText);
            } else {
                show_overlayed_content(xmlHttp, 'popup700x400');
                var width = $('#overlay_image').width()+110;
                var height = $('#overlay_image').height()+100;
                $('#overlay_content').css('width', width);
                $('#overlay_content').css('height', height);
                $('#overlay_content').css('marginTop', (- Math.floor(height / 2)));
                $('#overlay_content').css('marginLeft', (- Math.floor(width / 2)));
                $('#overlay_content').css('top', '50%');
                $('#overlay_content').css('left', '50%');
            }
        }
    );
}
function config_export()
{
    var url = 'ajax_admin_config.php';
    var params = '';
    params = params + "cmd=" + encodeURIComponent('export_settings');

    var elemIF = document.createElement("iframe");
    elemIF.src = url + '?' + params;
    elemIF.style.display = "none";
    document.body.appendChild(elemIF);
}


function user_settings_export()
{
    var url = 'ajax_prefs.php';
    var params = '';
    params = params + "cmd=" + encodeURIComponent('export_settings');

    var elemIF = document.createElement("iframe");
    elemIF.src = url + '?' + params;
    elemIF.style.display = "none";
    document.body.appendChild(elemIF);
}

function rss_feeds_export()
{
    var url = 'ajax_rss_feeds.php';
    var params = '';
    params = params + "cmd=" + encodeURIComponent('export');

    var elemIF = document.createElement("iframe");
    elemIF.src = url + '?' + params;
    elemIF.style.display = "none";
    document.body.appendChild(elemIF);
}


function group_export()
{
    var url = 'ajax_groups.php';
    var params = '';
    params = params + "cmd=" + encodeURIComponent('export');

    var elemIF = document.createElement("iframe");
    elemIF.src = url + '?' + params;
    elemIF.style.display = "none";
    document.body.appendChild(elemIF);
}


function open_hidden_link(url)
{
    var elemIF = document.createElement("iframe");
    elemIF.style.display = "none";
    elemIF.src = url;
    document.body.appendChild(elemIF);
    var uploaded_text = get_value_from_id('uploaded_text');
    var uploaded_file = get_value_from_id('uploaded_file');
    set_message('message_bar', uploaded_text + ': ' + uploaded_file, 5000); 
}



function jump(url, newwindow)
{
    if (newwindow) {
        window.open(url);
    } else {
        window.location = url;
    }
}


function fold_details(button_id, divid)
{
    fold_adv_search(button_id, divid);
    SendXMLHTTPGET('ajax_update_session.php', 'type='+encodeURIComponent('control'), null);
}


function submit_enter(e, fn, vars)
{
    
    if (e.which == 13) {
        fn(vars);
        return false;
    } else {
        return true;
    }
}


var mouse_click_time = 0;
function set_mouse_click()
{
    var d = new Date();
    mouse_click_time = d.getTime();
}


function start_quickmenu(str, sid, type, e)
{
    setTimeout(
        function () {
            ShowQuickMenu(str, sid, type, e);
        }, 200);
}


function do_select_subcat()
{      
    var cat_id = document.getElementById('select_catid');
    var cat = cat_id.options[cat_id.selectedIndex].value;
    var sc;
    var subcats = document.getElementsByName('subcat[]');
    var subcat = document.getElementById('subcat_selector_'+cat);

    var subcatbutton_id = document.getElementById('subcatbutton');
    if (!isNaN(parseInt(cat, 10))) {
        remove_class(subcatbutton_id, 'invisible');
    } else {
        add_class(subcatbutton_id, 'invisible');
    }
}


function show_subcat_selector()
{
    var cat_id = document.getElementById('select_catid');
    var cat = cat_id.options[cat_id.selectedIndex].value;
    var subcat = document.getElementById('subcat_selector_'+cat);
    var subcats = document.getElementsByTagName('div');
    var sc;
    close_browse_divs();
    for(var i = 0; i < subcats.length; i++) {
        sc = subcats[i];
        if (sc.id.substr(0, 16) == 'subcat_selector_') {
            add_class(subcats[i], 'hidden');
        }
    }

    if (cat != '' && subcat !== null) {
        $('#subcat_selector_'+cat).css('zIndex', 1000001);
        $('#subcat_selector_'+cat).wrap('<div id="overlay_back3"/>');
        $('#overlay_back3').css('zIndex', 1000000);
        $('#subcat_selector_'+cat).click(function(e) {  e.stopPropagation() }) ;
        $('#overlay_back3').click(function(e) { close_subcat_selector() ;  }) ;
        $('#overlay_back3').show();
        remove_class(subcat, 'hidden');
    }
}


function close_subcat_selector()
{
    var subcats = document.getElementsByTagName('div');
    var sc;
    for(var i = 0; i < subcats.length; i++) {
        sc = subcats[i];
        if (sc.id.substr(0, 16) == 'subcat_selector_') {
            add_class(subcats[i], 'hidden');
            if ($(subcats[i]).parent().is("div") && $(subcats[i]).parent().attr('id') == 'overlay_back3') {
                $(subcats[i]).unwrap();
            }
        }
    }
    $('#overlay_back2').hide();
}


var leftmenumargin = 0;
function scroll_menu_left(e)
{
    if (!e) {
        e = window.event;
    }
    if (e.shiftKey) {
        leftmenumargin = 0;
    } else {
        var step = 110;
        leftmenumargin = leftmenumargin - step;
    }
    document.getElementById('pulldown').style.marginLeft = leftmenumargin + 'px';
}


function scroll_menu_right(e)
{
    if (!e) {
        e = window.event;
    }
    if (e.shiftKey) {
        leftmenumargin = 0;

    } else {
        var step = 110;
        leftmenumargin = leftmenumargin + step;
    }
    document.getElementById('pulldown').style.marginLeft = leftmenumargin + 'px';
}


function basename(path) 
{
    return path.replace(/\\/g,'/').replace( /.*\//, '' );
}


function update_setname(id)
{
    var newsetname = get_value_from_id(id, '');
    var oldsetname = document.getElementById('setname');
    var tmpsetname = basename(newsetname);
    if (tmpsetname.lastIndexOf('.') > 0) {
        tmpsetname = tmpsetname.substr(0, tmpsetname.lastIndexOf('.'));
    }
    oldsetname.value = tmpsetname;
}


function add_blacklist(spotid)
{
    var url = 'ajax_action.php';
    var params = '';
    var challenge = get_value_from_id('challenge', '');
    params = params + "cmd=" + encodeURIComponent('add_blacklist');
    params = params + "&spotid=" + encodeURIComponent(spotid);
    params = params + '&challenge='+encodeURIComponent(challenge);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
        var msg = xmlHttp.responseText;
        if (msg != 'OK') {
            set_message('message_bar', msg, 5000);
        } else {
            set_message('message_bar', msg, 5000);
        }
    }
    );
}


function report_spam(spotid)
{
    show_post_message(null, spotid);
}


function select_dir(dir_select, dl_dir)
{
    var dldir = $('#' + dl_dir);
    var dirselect = $('#' + dir_select);
    toggle_hide('dir_select_span', 'hidden');
    toggle_hide('dl_dir_span', 'hidden');
    if (dldir === null || dirselect === null) {
        return;
    }
    if (dirselect.val() != '') {
        dldir.val( dirselect.val() + '/');
        update_basket_display('');
    }
}


function toggle_show_password(id)
{
	var pass = $('#' +id);
    if (pass.attr('type') == "password") { 
        pass.prop("type", "text"); 
    } else { 
        pass.prop("type", "password"); 
    } 
}


function hide_overlay(closelink)
{
    var overlaydiv =document.getElementById('overlay'); 
    add_class(overlaydiv, "hidden");
    if (closelink == 'back') {
        history.go(-1);
    } else if (closelink == 'close') {
        window.close();
    }
}


function show_overlay()
{
    var overlaydiv = document.getElementById('overlay'); 
    remove_class(overlaydiv, "hidden");
}


function toggle_textarea(ta_id, checkboxid)
{
    var checked = get_value_from_id(checkboxid);
    var ta = document.getElementById(ta_id); 
    ToggleClass(ta, 'hidden');
}


function start_updatedb()
{
    var div = document.getElementById('updatedbdiv'); 
    var url = 'ajax_update_db.php';
    var params = '';
    var xmlHttp = GetXmlHttpObject();
    
    xmlHttp.open("POST", url, true);
    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttp.setRequestHeader("Content-length", params.length);
    xmlHttp.setRequestHeader("Connection", "close");
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState ==3 || xmlHttp.readyState == 4)
        div.innerHTML = xmlHttp.responseText;
    }
    xmlHttp.send(params);
}


function init_slider(minv, maxv, slidediv, minbox, maxbox)
{
    $(function() {
        $(slidediv).slider(
            {
                range: true,
                min: minv,
                max: maxv,
                values: [ $(minbox).val(), $(maxbox).val()  ],
                slide: function(event, ui) { 
                    $(minbox).val( ui.values[0] );
                    $(maxbox).val( ui.values[1] );
                    }
                
            }
            );
    });
}


function show_spot_image(url)
{
    var new_img = new Image();
    new_img.src = url;
    $(new_img).load(function() {
            var maxWidth = Math.floor($(window).width() * 0.9);
            var maxHeight = Math.floor($(window).height() * 0.9);
            var ratio = 0;
            var width = $(new_img).width();
            var height = $(new_img).height();
            if (width > maxWidth) {
                ratio = (maxWidth / width);
                $(new_img).attr( {
                    width : maxWidth,
                    height : (height * ratio)
                }
                );
                height = (height * ratio);
                width = (width * ratio);
            }
            if (height > maxHeight) {
                ratio = (maxHeight / height);
                $(new_img).attr({
                    height : maxHeight,
                    width : (width * ratio)
                    });
                height = (height * ratio);
                width = (width * ratio);
            }
            $('#overlay_content2').wrap('<div id="overlay_wrap2" class="popup525x300"/>');
            $('#overlay_content2').css('width', width);
            $('#overlay_content2').css('height', height);
            $('#overlay_content2').css('marginTop', (- Math.floor(height / 2)));
            $('#overlay_content2').css('marginLeft', (- Math.floor(width / 2)));
            $('#overlay_content2').css('top', '50%');
            $('#overlay_content2').css('left', '50%');
            $('#overlay_wrap2').css('width', (width + 20));
            $('#overlay_wrap2').css('height', (height + 20));
            $('#overlay_wrap2').css('marginTop', (- Math.floor((height + 20) / 2)));
            $('#overlay_wrap2').css('marginLeft', (- Math.floor((width + 20) / 2)));
            $('#overlay_wrap2').css('top', '50%');
            $('#overlay_wrap2').css('left', '50%');
    });

    $('#overlay_back2').mouseup(function() { 
        if ($('#overlay_content2').parent().is('div') && $('#overlay_content2').parent().attr('id') == 'overlay_wrap2') { 
            $('#overlay_content2').unwrap('<div/>');
        }
        $('#overlay_content2').hide(); 
        $('#overlay_back2').hide();
    });
    $('#overlay_content2').html(new_img);
    $('#overlay_back2').show();
    $('#overlay_content2').show();
}


function toggle_usenet_auth(id, checkbox_id) 
{
    var new_val = $("#" + checkbox_id).val();
    if (new_val == 1) {
        edit_usenet_server(id, true);
    } else {
        usenet_action('disable_auth', id);
    }
}


function set_scroll_handler(id, fn)
{
    $(id).scroll(function() {
        var scrollPosition = $(id).scrollTop() + $(id).innerHeight();
        var divTotalHeight = $(id).get(0).scrollHeight 
                      + parseInt($(id).css('padding-top'), 10) 
                      + parseInt($(id).css('padding-bottom'), 10) ;

                      if ((scrollPosition + 1) >= divTotalHeight) {
                fn( { 'add_rows':'1' } );
            }
        }
    );
}


var update_setting_timeout = null;
var update_id = null;
function update_setting(id, type, optionals)
{
    var source = $('#source').val();
    var challenge = get_value_from_id('challenge', '');
    var url = '';
    var params = 'cmd=set';
    var option = $('#' + id).attr('name');
    if (source == 'prefs' || option == 'pref_level') { 
        url = 'ajax_prefs.php';
    } else if (source == 'config') {
        url = 'ajax_admin_config.php';
    }
    var timeout = 0;
    if (type == 'select') {
        var value = $('#' + id + ' :selected').val();
    } else if (type == 'period') {
        if (update_setting_timeout !== null && update_id == id) {
            clearTimeout(update_setting_timeout);
            update_id = null;
            update_setting_timeout = null;
        }
        var value = $('#' + id + ' :selected').val();
        params = params + '&time1=' + encodeURIComponent($('#' + optionals.time1).val());
        params = params + '&time2=' + encodeURIComponent($('#' + optionals.time2).val());
        if (optionals.extra != null) {
            params = params + '&extra='+ encodeURIComponent($('#' + optionals.extra).val());
        }
        timeout = 1500;
    } else if (type == 'multiselect') {
        // cleartimeout
        if (update_setting_timeout !== null && update_id == id) {
            clearTimeout(update_setting_timeout);
            update_setting_timeout = null;
            update_id = null;
        }
        var value =  $('#' + id).val().join(':');
        timeout = 1000;
    } else {
        var value = $('#' + id).val();
    }
    params = params
        + '&challenge='+ encodeURIComponent(challenge)
        + '&option=' + encodeURIComponent(option)
        + '&value=' + encodeURIComponent(value)
        + '&type=' + encodeURIComponent(type);
    var send_data = function() {  
                SendXMLHTTPPOST(url, params, function(xmlHttp) {
                update_message_bar(xmlHttp);
            }
        );
    };
    if (timeout > 0) {
        update_id = id;
        update_setting_timeout = setTimeout(send_data, timeout);
    } else {
        send_data();
    }
}


function check_weak_pw(password, username)
{
    if (password.length < 6) { return 0; }
    if (password.length < 8) { return 1; }
    var score = 0;
    if (password.length >= 8) { score ++; }
    if (password.length >= 10) { score ++; }
    if (password.length >= 15) { score ++;} 
    if (password.length >= 20) { score ++; }
    if (levenshtein(password, username) <= 6) { return 0; }
    var numcount = password.match(/\d+/g);
    numcount = (numcount) ? numcount.length : 0;
    
    score += numcount;

    var smcount = password.match(/[a-z]+/g);
    smcount = (smcount) ? smcount.length : 0;
    score += smcount;
        
    var capcount = password.match(/[A-Z]+/g);
    capcount = (capcount) ? capcount.length : 0;
    score += capcount;

    var symcount = password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,£,(,)]+/g);
    symcount = (symcount) ? symcount.length : 0;
    score += symcount;

    return score;
}


function handle_passwords_register(npw_id1, npw_id2, username_id)
{
    var fn = function() {
            var npw1 = $('#' + npw_id1).val();
            var npw2 = $('#' + npw_id2).val();
            var pwd = $('#' + npw_id2);
            if (npw2 == ''){
                pwd.removeClass('passwordincorrect');
                pwd.removeClass('passwordcorrect');

                $('#pwcorrect').hide();
                $('#pwincorrect').hide();
            } else if (npw1 != '' && npw2 != '' && npw1 == npw2) {
                pwd.removeClass('passwordincorrect');
                pwd.addClass('passwordcorrect');
                $('#pwcorrect').show();
                $('#pwincorrect').hide();
            } else {
                if (npw2 != '' && npw1 != npw2) {
                    pwd.removeClass('passwordcorrect');
                    pwd.addClass('passwordincorrect');
                    $('#pwcorrect').hide();
                    $('#pwincorrect').show();
                }
            }
            if (npw1 != '') {
                var username = $('#' + username_id).val();
                var weak_pw = check_weak_pw(npw1, username);
                var pw = $('#' + npw_id1);

                if (weak_pw <= 5) {
                    pw.removeClass('passwordmedium');
                    pw.removeClass('passwordstrong');
                    pw.addClass('passwordweak');
                    $('#pwweak').show();
                    $('#pwmedium').hide();
                    $('#pwstrong').hide();
                } else if (weak_pw <= 7) {
                    pw.removeClass('passwordweak');
                    pw.removeClass('passwordstrong');
                    pw.addClass('passwordmedium');
                    $('#pwweak').hide();
                    $('#pwmedium').show();
                    $('#pwstrong').hide();
                } else if (weak_pw > 7) {
                    pw.removeClass('passwordweak');
                    pw.removeClass('passwordmedium');
                    pw.addClass('passwordstrong');
                    $('#pwweak').hide();
                    $('#pwmedium').hide();
                    $('#pwstrong').show();
                }
            } else if (npw1 == '') {
                var pw = $('#' + npw_id1);
                    pw.removeClass('passwordstrong');
                    pw.removeClass('passwordweak');
                    pw.removeClass('passwordmedium');
            }
        }
   
    $('#pwcorrect').hide();
    $('#pwincorrect').hide();
    $('#pwweak').hide();
    $('#pwmedium').hide();
    $('#pwstrong').hide();
    $('#' + npw_id1).on('mouseup', fn);
    $('#' + npw_id1).on('keyup', fn);
    $('#' + npw_id2).on('keyup', fn);
    $('#' + npw_id2).on('mouseup', fn);
}


function handle_passwords_change(opw_id, npw_id1, npw_id2, sub_id, username)
{
            
    $('#' +sub_id).hide();
    var fn = function() {
            var npw1 = $('#' + npw_id1).val();
            var npw2 = $('#' + npw_id2).val();
            var opw = $('#' + opw_id).val();
            var pwd = $('#' + npw_id2);
            var pwd_msg = $('#pw_message_' + npw_id2);
            if (npw2 == ''){
                $('#' + sub_id).hide();
                pwd_msg.html('');
            } else if (opw != '' && npw1 != '' && npw2 != '' && npw1 == npw2) {
                $('#' + sub_id).show();
            } else {
                $('#' + sub_id).hide();
            }
            if (npw1 != '' && npw2 != '' && npw1 == npw2) {
                pwd_msg.html($('#pwcorrect').html());
                pwd.removeClass('passwordincorrect');
                pwd.addClass('passwordcorrect');
            } else if (npw2 != '' && npw1 != npw2) {
                pwd.removeClass('passwordcorrect');
                pwd.addClass('passwordincorrect');
                pwd_msg.html($('#pwincorrect').html());
            } else {
                pwd_msg.html('');
                pwd.removeClass('passwordincorrect');
                pwd.removeClass('passwordcorrect');
            }


            if (npw1 != '') {
                var weak_pw = check_weak_pw(npw1, username);
                var pw = $('#' + npw_id1);
                var pw_msg = $('#pw_message_' + npw_id1);

                if (weak_pw <= 5) {
                    pw.removeClass('passwordmedium');
                    pw.removeClass('passwordstrong');
                    pw.addClass('passwordweak');
                    pw_msg.html($('#pwweak').html());
                } else if (weak_pw <= 7) {
                    pw.removeClass('passwordweak');
                    pw.removeClass('passwordstrong');
                    pw.addClass('passwordmedium');
                    pw_msg.html($('#pwmedium').html());
                } else if (weak_pw > 7) {
                    pw.removeClass('passwordweak');
                    pw.removeClass('passwordmedium');
                    pw.addClass('passwordstrong');
                    pw_msg.html($('#pwstrong').html());
                }
            } else if (npw1 == '') {
                var pw = $('#' + npw_id1);
                pw.removeClass('passwordstrong');
                pw.removeClass('passwordweak');
                pw.removeClass('passwordmedium');
                pw_msg.html("");
            }
        }
    var change_password = function () {
        var npw1 = $('#' + npw_id1).val();
        var npw2 = $('#' + npw_id2).val();
        var opw = $('#' + opw_id).val();
        var challenge = get_value_from_id('challenge', '');

        var params = '';
        var url = 'ajax_prefs.php';
        params = params + 'cmd=change_password'
            + '&challenge='+ encodeURIComponent(challenge)
            + '&oldpass=' + encodeURIComponent(opw)
            + '&newpass1=' + encodeURIComponent(npw1)
            + '&newpass2=' + encodeURIComponent(npw2);
         
        SendXMLHTTPPOST(url, params, function(xmlHttp) {
                update_message_bar(xmlHttp);
        });
    };

    $('#' + opw_id).on('keyup', fn);
    $('#' + opw_id).on('mouseup', fn);
    $('#' + npw_id1).on('mouseup', fn);
    $('#' + npw_id1).on('keyup', fn);
    $('#' + npw_id2).on('keyup', fn);
    $('#' + npw_id2).on('mouseup', fn);
    $('#' + sub_id).click(change_password);
}


function load_prefs()
{
    var url= '';
    var source = $('#source').val();
    if (source == 'prefs') { 
        url = 'ajax_prefs.php';
    } else if (source == 'config') {
        url = 'ajax_admin_config.php';
    }
    var params = 'cmd=show';
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            show_content_div(xmlHttp, 'settingsdiv');
        }
    );
}


function reset_prefs(msg)
{
    var url= '';
    var source = $('#source').val();
    if (source == 'prefs') { 
        url = 'ajax_prefs.php';
    } else if (source == 'config') {
        url = 'ajax_admin_config.php';
    }
    var challenge = get_value_from_id('challenge', '');
    var params = 'cmd=reset';
    params = params + '&challenge='+ encodeURIComponent(challenge)
    show_confirm(msg, function() {
            SendXMLHTTPPOST(url, params, function(xmlHttp) {
                    if (xmlHttp.responseText.substr(0,2) == "OK") {
                        load_prefs();
                    }
                    update_message_bar(xmlHttp);
                    show_content_div(xmlHttp, 'settingsdiv');
                }
            );
         }
    );
}


function change_stylesheet(id)
{
    var cssdir = $('#cssdir').val();
    var stylesheet= $('#' + id + '_select' ).val();
    stylesheet = cssdir + '/' + stylesheet + '/' + stylesheet + '.css';
    $('#urd_css').attr('href', stylesheet);
} 


function show_logs(options)
{
    var url = 'ajax_admin_log.php';
    var challenge = get_value_from_id('challenge', '');
    var sort_order = get_value_from_id('sort_order','');
    var sort_dir = get_value_from_id('sort_dir','');
    var lines = get_value_from_id('lines','');
    var level = $('#log_level').val();
    var search = $('#search').val();
    var params = "lines=" + encodeURIComponent(lines)
     + "&log_level=" + encodeURIComponent(level)
     + '&challenge='+encodeURIComponent(challenge)
     + "&search=" + encodeURIComponent(search)
     + "&sort_dir=" + encodeURIComponent(sort_dir)
     + "&sort=" + encodeURIComponent(sort_order);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            show_content_div(xmlHttp, 'logdiv');
        }
    );
}


function levenshtein(s1, s2) 
{
    // http://kevin.vanzonneveld.net
    // +            original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
    // +            bugfixed by: Onno Marsman
    // +             revised by: Andrea Giammarchi (http://webreflection.blogspot.com)
    // + reimplemented by: Brett Zamir (http://brett-zamir.me)
    // + reimplemented by: Alexander M Beedie
    // *                example 1: levenshtein('Kevin van Zonneveld', 'Kevin van Sommeveld');
    // *                returns 1: 3
    if (s1 == s2) {
        return 0;
    }

    var s1_len = s1.length;
    var s2_len = s2.length;
    if (s1_len === 0) {
        return s2_len;
    }
    if (s2_len === 0) {
        return s1_len;
    }

    // BEGIN STATIC
    var split = false;
    try {
        split = !('0')[0];
    } catch (e) {
        split = true; // Earlier IE may not support access by string index
    }
    // END STATIC
    if (split) {
        s1 = s1.split('');
        s2 = s2.split('');
    }

    var v0 = new Array(s1_len + 1);
    var v1 = new Array(s1_len + 1);

    var s1_idx = 0,
        s2_idx = 0,
        cost = 0;
    for (s1_idx = 0; s1_idx < s1_len + 1; s1_idx++) {
        v0[s1_idx] = s1_idx;
    }
    var char_s1 = '',
        char_s2 = '';
    for (s2_idx = 1; s2_idx <= s2_len; s2_idx++) {
        v1[0] = s2_idx;
        char_s2 = s2[s2_idx - 1];

        for (s1_idx = 0; s1_idx < s1_len; s1_idx++) {
            char_s1 = s1[s1_idx];
            cost = (char_s1 == char_s2) ? 0 : 1;
            var m_min = v0[s1_idx + 1] + 1;
            var b = v1[s1_idx] + 1;
            var c = v0[s1_idx] + cost;
            if (b < m_min) {
                m_min = b;
            }
            if (c < m_min) {
                m_min = c;
            }
            v1[s1_idx + 1] = m_min;
        }
        var v_tmp = v0;
        v0 = v1;
        v1 = v_tmp;
    }
    return v0[s1_len];
}


function submit_registration()
{
    var username= $('#username').val();
    var email= $('#email').val();
    var pass1= $('#pass1').val();
    var pass2= $('#pass2').val();
    var fullname= $('#fullname').val();
    var captcha= $('#captcha').val();
    var url = 'ajax_register.php';
    var params = "username=" + encodeURIComponent(username)
        + "&email=" + encodeURIComponent(email)
        + "&password1=" + encodeURIComponent(pass1)
        + '&password2='+encodeURIComponent(pass2)
        + "&fullname=" + encodeURIComponent(fullname)
        + "&register_captcha=" + encodeURIComponent(captcha)
        + "&submit_button=1";
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                if (xmlHttp.responseText == "OK") {
                    $('#form').hide();
                    $('#sent').show();
                } else {
                    if (xmlHttp.responseText.substr(0, 7) == ':error:') {
                        set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
                    }
                }
            }
        }
    );
}


function submit_forgot_password()
{
    var username= $('#username').val();
    var email= $('#email').val();
    var challenge = get_value_from_id('challenge', '');
    var url = 'ajax_forgot_password.php';
    var params = "username=" + encodeURIComponent(username)
        + "&email=" + encodeURIComponent(email);
    SendXMLHTTPPOST(url, params, function(xmlHttp) {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                if (xmlHttp.responseText == "OK") {
                    $("#sent_table").show();
                    $("#form_table").hide();
                } else {
                    if (xmlHttp.responseText.substr(0, 7) == ':error:') {
                        set_message('message_bar', xmlHttp.responseText.substr(7), 5000);
                    }
                }

            }
        }
    );
}

