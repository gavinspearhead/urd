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
 * $LastChangedDate: 2014-06-28 23:05:24 +0200 (za, 28 jun 2014) $
 * $Rev: 3131 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: js.js 3131 2014-06-28 21:05:24Z gavinspearhead@gmail.com $
 */
"use strict";

var mousedown = 0;
var selected_text = "";
var text_counter = 0;
var mouse_click_time = 0;
var last_clicked_setid = false;

function jump(url, newwindow)
{
    if (newwindow) {
        window.open(url);
    } else {
        window.location = url;
    }
}

function get_selected_text()
{
    var s = (window.getSelection ? window.getSelection() : document.getSelection ? document.getSelection() : document.selection.createRange().text).toString();
    return String(s);
}

function set_selected()
{ 
    var s = get_selected_text();
    if ((!s || s.length == 0) && text_counter > 0) { 
        text_counter--; 
    } else {
        selected_text = s;
        text_counter = 1;
    }
}

function setvalbyid(id, val)
{
    $('#' + id).val(val);

}

function get_value_from_id(id, def)
{
    var val = $('#'+id).val();
    if (val === undefined) {
        return def;
    } else {
        return val;
    }
}

function init()
{
    // To keep track of the mouse button, used for the quickmenu:
    mousedown = 0;
    $(document).mousedown = function() { 
        ++mousedown;
        // Sanity check, sometimes it misses ups/downs!
        if (mousedown > 1) { mousedown = 1; }
    };
    $(document).mouseup = function() {
        --mousedown;
        // Sanity check, sometimes it misses ups/downs!
        if (mousedown < 0) { mousedown = 0; }
    };
    var urdd_status = $('#urdd_status').val();
    var msg = $('#urdd_message').val();
    if (urdd_status !== undefined && urdd_status == 0) {            
        set_message('message_bar', msg, 5000);
    } 
    update_quick_status();
    update_disk_status();
    $('#message_bar').click(function() { hide_message('message_bar', 0); } );  
    $('#scrollmenuright').click(function(e) { scroll_menu_right(e); } );
    $('#scrollmenuleft').click(function(e) { scroll_menu_left(e); } );
    $('#smalllogo').click(function() { jump('index.php'); } );
    $('#status_item').mouseover(function() { load_activity_status(); } );
    $('#topcontent').mouseup( function() { set_selected();} );
    $('#contentout').mouseover( function() { close_quickmenu();} );
}

function task_action(action, task)
{
    var url = "ajax_action.php";
    var challenge = get_value_from_id('challenge', '');
    var data = { 
        'cmd': action,
        'challenge': challenge,
        'task': task 
    };
    if (action !== null) {
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: data
        }).done( function(html) {
            update_tasks();
            update_message_bar(html);
        });
    }
}

function job_action(action, job)
{
    if (action !== null) {
        var challenge = get_value_from_id('challenge', '');
        $.ajax({
            type: 'post',
            url: "ajax_action.php",
            cache: false,
            data: {
                cmd: action,
                job : job,
                challenge: challenge 
            }
        }).done( function(html) {
            update_jobs();
            update_message_bar(html);
        });
    }
}

function control_action(action)
{
    var challenge = get_value_from_id('challenge', '');
    if (action !== null) {
        $.ajax({
            type: 'post',
            url: "ajax_action.php",
            cache: false,
            data: {
                cmd: action,
                challenge: challenge 
            }
        }).done( function(html) {
           update_message_bar(html);
        });
    }
}

function control_action_confirm(action, confirmmsg)
{
    show_confirm(confirmmsg, function() { 
            control_action(action);
        }
    );
}

function ng_action(action, id)
{
    var url = "ajax_action.php";
    var challenge = get_value_from_id('challenge', '');
   
    if (action !== null) {
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: {
                cmd: action,
                group: id,
                challenge: challenge 
            }
        }).done( function(html) {
            update_message_bar(html);
        });
    }
}

function ng_action_confirm(action, id, confirmmsg)
{
    var name = get_value_from_id("ng_id_" + id, null);
    if (name === null) {
        return;
    }
    confirmmsg = confirmmsg.replace('@@', name);
    show_confirm(confirmmsg, function () {
        ng_action(action, id);
    });
}

function set_basket_type(type)
{
    var challenge = get_value_from_id('challenge', '');
    $.ajax({
        type: 'post',
        url: 'ajax_processbasket.php',
        cache: false,
        data: {
            command: 'set',
            basket_type: type,
            challenge: challenge 
        }
    });
}

function get_basket_type()
{
    $.ajax({
        type: 'post',
        url: "ajax_processbasket.php",
        cache: false,
        data: {
            command: 'get' 
        }
   }).done(function(html) {
       var content = $.parseJSON(html);
       update_basket_display(content.basket_type);
   });
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
    var challenge = get_value_from_id('challenge', '');
    var save_category = get_value_from_id('save_category', ''); 
    var timestamp = get_value_from_id('timestamp', '');
    var dl_dir = get_value_from_id('dl_dir', '');
    if (dlsetname === '' && selected_text != '') {
        dlsetname = selected_text;
    }
    
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
            command: 'view',
            dlsetname: dlsetname,
            basket_type: basket_type,
            download_delay: timestamp,
            add_setname: add_setname,
            save_category: save_category,
            dl_dir: dl_dir,
            challenge: challenge 
        }
    }).done(function(html) {
        var content = $.parseJSON(html);
        if (content.error != 0) {
            set_message('message_bar', content.error, 5000);
            return;
        }

        content = $.trim(content.contents);
        if (basket_type != 2) { // the normal basket
            $('#basketdiv').html(content);
            if ($('#basketbuttondiv') !== undefined) {
                $('#minibasketdiv').addClass('hidden');
                if (content === '') {
                    $('#basketbuttondiv').addClass('hidden');
                } else {
                    $('#basketdiv').removeClass('hidden');
                    $('#basketbuttondiv').removeClass('hidden');
                }
            }
            $('#minibasketdiv').html('');
        } else {  // the mini basket
            $('#minibasketdiv').html(content);
            $('#basketdiv').addClass('hidden');
            if (content == '') {
                $('#minibasketdiv').addClass('hidden');
            } else {
                $('#minibasketdiv').removeClass('hidden');
            }
            $('#basketdiv').html('');
        }
         update_search_bar_height();
    });
}

function update_search_bar_height()
{
    var div_height = $('#searchbar').height();
    var menu_height = $('#pulldown_menu').height();
    var diff = div_height + menu_height + 7;
    if (diff > 50) { diff += 2; } 
    $('#topcontent').css( { "top": diff + 'px'});
    $('#contentout').height($(window).height() - diff);
}

function select_set(setID, type, theevent)
{
    // Remember this set for when the shift key is pressed, so we can toggle everything in between.
    // First see if shift was used and we need to toggle a bunch, before we overwrite the last_clicked_setid.
    // We also need to check if there is a valid last_clicked_setid to prevent bogus stuff.
    close_browse_divs();
    if (theevent.shiftKey && last_clicked_setid !== false) {
        toggle_group_of_sets(last_clicked_setid, setID, type);
        last_clicked_setid = false;
        document.getSelection().removeAllRanges(); //clean up the selected text
    } else {
        toggle_set(setID, type);
        last_clicked_setid = setID;
    }
}

function toggle_set(setID, type)
{
    var set = $('#set_' + setID);
    var command = '';
    var xstatus = 0;
    if (set.val() === '') {
        command = "add";
        xstatus = 0;
    } else {
        command = "del";
        xstatus = 1;
    }

    var dl_dir = get_value_from_id('dl_dir', '');
    var add_setname = get_value_from_id('add_setname', '');
    var timestamp = get_value_from_id('timestamp', '');
    var challenge = get_value_from_id('challenge', '');

    var url = "ajax_processbasket.php";

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
            setID: setID,
            type: type,
            command: command,
            timestamp: timestamp,
            add_setname: add_setname,
            challenge: challenge ,
            dl_dir: dl_dir
        }
   }).done(function() {
       if (xstatus === 0) {
            set.val('x');
            $('#divset_' + setID).toggleClass('setimgplus');
            $('#divset_' + setID).toggleClass('setimgminus');
        } else {
            set.val('');
            $('#divset_' + setID).toggleClass('setimgplus');
            $('#divset_' + setID).toggleClass('setimgminus');
        }

        update_basket_display();
   });
}

function set_as_downloaded_sets()
{
    $('input[name="set_ids[]"]').each(function() {
        var setID = $(this).val();
        var set = get_value_from_id('set_' + setID, '');
        if (set != '') {
            $('#base_row_' + setID).addClass('markedread');
        }
    });
}

function reset_sets()
{
    $('input[name="set_ids[]"]').each(function() {
        var setID = $(this).val();
        $('#divset_' + setID).addClass('setimgplus');
        $('#divset_' + setID).removeClass('setimgminus');
        $('#set_' + setID).val('');
    });
}

function submit_sort_log(val)
{
    var orderval = $('#order');
    var orderdir = $('#order_dir');
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
    var orderval = $('#order');
    var orderdir = $('#order_dir');
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
    var dir = get_value_from_id('dir', '');
    $('#offset').val(offset);
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
    show_confirm(msg, function () {
        submit_viewfiles_action(fileid, command);
    });
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
    if (command == 'up_nzb') {
        show_uploadnzb(dir, name);
    } else if (command == 'zip_dir') {
        var params = "cmd=" + command + 
            "&dir=" + encodeURIComponent(dir) + 
            "&filename=" + encodeURIComponent(name) + 
            "&challenge=" + encodeURIComponent(challenge);
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
        });
    } else {
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: {
                dir: dir,
                filename: name,
                cmd: command,
                challenge: challenge
                }
        }).done(function(html) {
               var x = $.parseJSON(html);
               show_files( { 'curdir':dir, 'reset_offset': false });
               set_message('message_bar', x.error, 5000);

        });
    }
}

function submit_order(val, def, fn)
{
    var orderval = $('#order');
    var orderdirval = $('#order_dir');
    if (orderval.val() == val) {
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

function submit_jobs_search(val, def)
{
    submit_order(val, def, load_jobs);
}

function submit_rss_search(val, def)
{
    submit_order(val, def, load_rss_feeds);
}

function submit_search_users(val, def)
{
    submit_order(val, def, show_users);
}

function submit_search_usenet_servers(val, def)
{
    submit_order(val, def, show_usenet_servers);
}

function submit_search_tasks(val, def)
{
    submit_order(val, def, load_tasks);
}

function submit_search_searchoptions(val, def)
{
    submit_order(val, def, show_buttons);
}

function load_transfers()
{
    var active_tab = get_value_from_id('active_tab', null);
    var url = "ajax_showtransfers.php";
    var data = null;
    if (active_tab !== null) {
        data = { active_tab: active_tab };
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done(function(html) {
        show_content_div_2(html, 'transfersdiv');
        update_search_bar_height();
    });
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
    $(content).html('');
    $(document).keydown(function() { } );
}

function _show_overlayed_content(html, style, content, back, close_button)
{
    $(content).html('');
    if (html.substr(0, 7) == ':error:') {
        set_message('message_bar', html.substr(7), 5000);
    } else {
        $(content).css({"width":"", "height":"", "margin": ""});
        $(content).html(html);
        $(content).removeClass();
        $(content).addClass(style);
        $(document).keydown(function(e) { if (e.which == 27 ) { hide_overlayed_content(); hide_overlayed_content2(); e.stopPropagation(); } } );
        $(content).click(function(e) { e.stopPropagation(); });
        $(close_button).click(function(e) { _hide_overlayed_content(content, back); e.stopPropagation(); });
        $(back).click(function(e) { _hide_overlayed_content(content, back); e.stopPropagation(); });
        $(back).show();
        $(content).show();
    }
}

function _overlayed_content_visible(content)
{
    return ($(content).css("display") != 'none');
}

function overlayed_content_visible()
{
    return _overlayed_content_visible('#overlay_content');
}

function show_overlayed_content_1(html, style)
{
    _show_overlayed_content(html, style, '#overlay_content', '#overlay_back', '#close_button');
}

function show_overlayed_content_2(html, style)
{
    _show_overlayed_content(html, style, '#overlay_content2', '#overlay_back2', '#close_button2');
}

function show_content_div_2(html, divid)
{
    if (html.substr(0, 7) == ':error:') {
        set_message('message_bar', html.substr(7), 5000);
    } else {
        $('#' + divid).html(html);
    }
}

function load_control()
{
    var url = "ajax_admincontrol.php";
    $.ajax({
        type: 'post',
        url: url,
        cache: false
    }).done(function(html) {
        show_content_div_2(html, 'controldiv');
        update_search_bar_height();
    });
}

function show_users(order, direction)
{
    var url = "ajax_edit_users.php";
    var orderval = get_value_from_id('order', '');
    var orderdirval = get_value_from_id('order_dir', '');
    var search = get_value_from_id('search', '');
    var data = { search : search, cmd: 'reload_users' };
    if (order === undefined) {
        order = orderval;
    }
    if (order !== undefined) {
        data.sort = order;
    }

    if (direction == null) {
        direction = orderdirval;
    }
    if (direction != null) {
        data.sort_dir = direction;
    }

    $.ajax({
        type: 'post',
        url: url,
        data: data,
        cache: false
    }).done(function(html) {
        show_content_div_2(html, 'usersdiv');
        update_search_bar_height();
    });
}

function blacklist_offset(offset)
{
    var which = $('#which').val();
    show_blacklist({ offset: offset, which : which } );
}

function show_blacklist(options)
{
    var url = "ajax_user_blacklist.php";
    var orderval = get_value_from_id('order', '');
    var orderdirval = get_value_from_id('order_dir', '');
    var search = get_value_from_id('search', '');
    var offset = get_value_from_id('offset', '0');
    var status_val = $('#status>option:selected').val();
    var which = get_value_from_id('which', '');
    var order, direction;
    var add_rows = 0;
    if (which == '') { 
        which = 'spots_blacklist'; 
    }
    var data = {
        cmd: 'load_blacklist',
        search: search,
        'status': status_val,
        which: which,
        offset: offset
    };

    if (options !== undefined) {
        if (options.which !== undefined) {
            data.which = options.which;
            $('#which').val(options.which);
        }
        if (options.order === undefined) {
            order = orderval;
        } else {
            order = options.order;
        }
        if (order !== undefined) {
            data.sort = order;
        }

        if (options.def_direction !== undefined && orderval != order) {
            direction = options.def_direction;
        } else if (orderdirval == 'asc') {
            direction = 'desc';
        } else {
            direction = 'asc';
        }
        if (direction != null) {
           data.sort_dir = direction;
        }
        if (options.offset !== undefined) {
            data.offset = options.offset;
        }
        var per_page = $('#perpage').val();
        if (options.add_rows != null) {
            data.only_rows = "1";
            data.perpage = per_page;
            add_rows = 1;
            offset = parseInt( $('#last_line').val());
            if (!$.isNumeric(offset)) { offset = 0; }
            data.offset = offset;
            $('#last_line').val(offset + parseInt(per_page));
        }
    }
   console.log(data, orderval, order, orderdirval); 
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        cache: false
    }).done(function(html) {
        var x = $.parseJSON(html);
        if (x.error == 0) { 
            if (add_rows == 0) {
                show_content_div_2(x.contents, 'usersdiv');
                update_search_bar_height();
            } else {
                $('#black_list_table>tbody tr').eq(-2).after(x.contents);
            }
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function show_files(options)
{
    var search = get_value_from_id('search', '');
    var dir = get_value_from_id('dir', '');
    var offset = get_value_from_id('offset', 0);
    var sort = get_value_from_id('order', '');
    var sortdir = get_value_from_id('order_dir', '');
    var add_rows = 0;
    var data = {
        cmd: 'show_files',
        search: search,
        offset: offset,
        dir: dir,
        sort: sort,
        sort_dir: sortdir
    };

    if (options != null) {
        if (options.curdir != null) {
            data.dir = options.curdir; 
        }
        if (options.reset_offset != null && options.reset_offset === true) {
            data.offset = 0;
        }
        if (options.add_rows != null && options.add_rows == 1) {
            var per_page = $('#perpage').val();
            add_rows = 1;
            data.only_rows = '1';
            data.perpage = per_page;
            offset = parseInt($('#last_line').val());
            if (!$.isNumeric(offset)) { offset = 0; }
            $('#last_line').val(offset+parseInt(per_page));
            data.offset = offset;
        }
    }
    $('#search').keypress( function(event) { do_keypress_viewfiles(event); } );
    var url = "ajax_editviewfiles.php";
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        cache: false
    }).done(function(html) {
        console.log(html);
        var x = $.parseJSON(html);
        if (x.error != 0) {
            set_message('message_bar', x.error, 5000);
            if (add_rows == 0) {
                $('#viewfilesdiv').html('');
            }
        } else {
            if (add_rows == 0) {
                show_content_div_2(x.contents, 'viewfilesdiv');
                update_widths('filenametd');
                $('#directory_top').html( $('#dir2').val());
                $('#contentout').scrollTop(0);
                update_search_bar_height();
            } else {
                $('#files_table>tbody tr').eq(-2).after(x.contents);
                update_widths("filenametd");
            }
        }
    });
}

function show_files_clean()
{
    return show_files( { 'curdir':null, 'reset_offset':true });
}

function show_buttons(order, direction)
{
    var url = "ajax_edit_searchoptions.php";
    var search = get_value_from_id('search', '');
    var data = { 
        cmd: 'show_buttons',
        search: search 
    };

    var orderval = get_value_from_id('order', 'name');
    var orderdirval = get_value_from_id('order_dir', 'asc');
    
    if (order === undefined) {
        order = orderval;
    }
    if (order !== undefined) {
        data.sort = order;
    }

    if (direction == null) {
        direction = orderdirval;
    }
    if (direction != null) {
        data.sort_dir = direction;
    }
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        cache: false
    }).done(function(html) {
        show_content_div_2(html, 'buttonsdiv');
        update_search_bar_height();
    });
}

function show_usenet_servers(order, direction)
{
    var url = "ajax_edit_usenet_servers.php";
    var search = get_value_from_id('search', '');
    var data = { 
        cmd: 'reload_servers',
        search: search 
    };
    var orderval = get_value_from_id('order', 'name');
    var orderdirval = get_value_from_id('order_dir', 'asc');
    
    if (order === undefined) {
        order = orderval;
    }
    if (order !== undefined) {
        data.sort = order;
    }

    if (direction == null) {
        direction = orderdirval;
    }
    if (direction != null) {
        data.sort_dir = direction;
    }
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        cache: false
    }).done(function(html) {
        show_content_div_2(html, 'usenetserversdiv');
        update_search_bar_height();
    });
}

function show_post_message(type, spotid)
{
    var url = "ajax_post_message.php";
    var data = { 
        type : type,
        cmd: 'show'
    };
    
    if (spotid !== null) {
        data.spotid = spotid;
        if (type == 'comment') {
            var rating = get_value_from_id('rating', '0');
            data.rating = rating;
        }
    }
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        cache: false
    }).done(function(html) {
        console.log(html);
        var x = $.parseJSON(html);

        if (x.error == 0) {
            show_overlayed_content_1(x.contents, 'popup700x400');
        } else {
            set_message('message_bar', x.error , 5000);
        }
    });
}

function show_uploadnzb(dir, name)
{
    var url = "ajax_show_upload.php";
    var data = {};
    if (dir !== undefined && name !== undefined) {
        data.dir = dir;
        data.filename = name;
    }
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        cache: false
    }).done(function(html) {
        if (html.substr(0, 7) != ':error:') {
            show_overlayed_content_1(html, 'popup525x300');
        } else {
            set_message('message_bar', html.substr(7), 5000);
        }
    });
}

function show_edit_post(postid)
{
    var url = "ajax_show_post.php";
    $.ajax({
        type: 'post',
        url: url,
        data: {
            cmd: 'showrename', 
            postid: postid
        },
        cache: false
    }).done(function(html) {

        if (html.substr(0, 7) != ':error:') {
            show_overlayed_content_1(html, 'popup700x400');
        } else {
            set_message('message_bar', html.substr(7), 5000);
        }
    });
}

function show_post()
{
    var url = "ajax_show_post.php";
    $.ajax({
        type: 'post',
        url: url,
        cache: false
    }).done(function(html) {
        if (html.substr(0, 7) != ':error:') {
            show_overlayed_content_1(html, 'popup700x400');
        } else {
            set_message('message_bar', html.substr(7), 5000);
        }
    });
}

function load_jobs(order, direction)
{
    var url = "ajax_adminjobs.php";
    var data = {};
    var orderval = get_value_from_id('order', '');
    var orderdirval = get_value_from_id('order_dir', 'asc');

    if (order === undefined) {
        order = orderval;
    }
    if (order != null) {
        data.sort = order;
    }
    if (direction === undefined) {
        direction = orderdirval;
    }
    if (direction != null){
        data.sort_dir = direction;
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        show_content_div_2(html, 'jobsdiv');
        update_widths("descr_td");
        update_search_bar_height();
    });
}

function load_tasks(order, direction, clear_offset)
{
    var url = "ajax_admintasks.php";
    var data = {};
    var orderval = get_value_from_id('order', '');
    var orderdirval = get_value_from_id('order_dir', '');
    var offsetval = get_value_from_id('offset', '');
    var tasksearch = get_value_from_id('tasksearch', '');
    var timeval = $('#time_select>option:selected').val();
    data.time= timeval;
    if (tasksearch != '') {
        data.tasksearch = tasksearch;
    }
    var statusval = $('#status_select>option:selected').val();
    data.status = statusval;
    if (order === undefined && orderval != '') {
        order = orderval;
    }
    if (order !== null) {
        data.sort= order;
    }
    if (direction === undefined && orderdirval != '') {
        direction = orderdirval;
    }
    if (direction != null) {
        data.sort_dir= direction;
    }
    if (offsetval != '' && clear_offset !== true) {
        data.offset = offsetval;
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        show_content_div_2(html, 'tasksdiv');
        update_widths("descr_td");
        update_widths("comment_td");
        update_search_bar_height();
    });
}

function load_tasks_no_offset(order, direction)
{
     load_tasks(order, direction, true);
}

function update_jobs()
{
    load_jobs();
    setTimeout(update_jobs, 5000);
}

function tasks_offset(offset)
{
    if (offset !== null) { 
        $('#offset').val(offset);
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
    $('#' + id).fadeOut(500, function () {
        $('#' + id).addClass("hidden");
        $('#' + id).fadeIn(0);
    });
}

var message_timeout = null;
function hide_message(id, timeout)
{
    if (message_timeout != null) {
        clearTimeout(message_timeout);
    }
    message_timeout = setTimeout("do_hide_message('" + id + "');", timeout);
}

function update_control()
{
    load_control();
    setTimeout(update_control, 4000);
}

function load_disk_status()
{
    var url = "ajax_showstatus.php";
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: { type: 'disk' }
    }).done( function(html) {
        if (html.substr(0,3) == 'OFF') {
            $('#disk_li').addClass('hidden');
        } else {
            $('#disk_li').removeClass('hidden');
            $('#status_disk').html(html);
        }
    }); 
}

var activity_status = 0;
function load_activity_status(force)
{
    if ((activity_status + 4000 ) >= (new Date().getTime() ) && force != 1 ) { 
        return;
    }
    activity_status = new Date().getTime();

    var url = "ajax_showstatus.php";
    $.ajax({ type: 'post', url: url, cache: false, data: { type: 'activity' } }).done( function(html) { $('#status_activity').html(html); }); 
}

function load_quick_status()
{
    var url = "ajax_showstatus.php";
    $.ajax({ type: 'post', url: url, cache: false, data: { type: 'quick' } }).done( function(html) { $('#status_msg').html(html); }); 
    $.ajax({ type: 'post', url: url, cache: false, data: { type: 'icon'  } }).done( function(html) { $('#smallstatus').html(html); }); 
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

function urd_search()
{
    var srch = get_selected_text();
    if (srch == '') {
        show_alert("Please select the search query before clicking this button.");
    } else {
        $('#select_groupid').prop("selectedIndex", 0);
        $('#select_feedid').prop("selectedIndex", 0);
        $('#flag').val('');
        $('#search').val(srch);
        load_sets({'offset':'0', 'setid':''});
    }
}

function add_search(type)
{
    var srch = get_selected_text();
    if (srch == '') {
        show_alert("Please select the search query before clicking this button.");
    } else {
        srch = srch.replace(/[\]\[_"+.]/g, ' ');
        var challenge = get_value_from_id('challenge', '');
        var url = 'ajax_action.php';
        $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
             cmd: 'add_search',
             value: srch,
             type: type,
             challenge: challenge
             }
        });
    }
}

function search_button(url, xname)
{
    var srch = get_selected_text();
    if (srch == '') {
        show_alert("Please select the search query before clicking this button.");
    } else {
        /* Remove common separators: */
        srch = srch.replace(/[\]\[_"+.']/g, ' ');
        srch = srch.replace(/^\s+/, '');
        url = url.replace(/\$q/, escape(srch));
        window.open(url, xname + 'window', '');
    }
}

function mark_read(setid, cmd, type)
{
    var url = "ajax_markread.php";
    var data = {
        cmd: cmd,
        setid: setid,
        type: type
    };
    if (cmd == 'wipe') {
        var challenge = get_value_from_id('challenge', '');
        data.challenge = challenge;
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function(html) {
        console.log(html);
        var content = $.parseJSON(html);
        if (content.error == 0) {
            var thetr = $('#base_row_' + setid);
            /* Only update display if backend succeeded: */
            if (cmd == 'markread') {
                thetr.toggleClass('markedread');
            } else if (cmd == 'interesting') {
                thetr.toggleClass('interesting');
                var thediv = $('#intimg_' + setid);
                thediv.toggleClass('sadicon'); 
                thediv.toggleClass('smileicon'); 
            } else if (cmd == 'hide' || cmd == 'unhide' || cmd == 'wipe') {
                thetr.toggleClass('hidden');
                if (cmd == 'wipe') {
                    var msg = get_value_from_id('deletedset', '');
                    set_message('message_bar', msg, 5000);
                }
            }
        } else {
            set_message('message_bar', content.error, 5000);
        }
    });
}

function update_message_reload_transfers(html)
{
    load_transfers();
    update_message_bar(html);
}

function post_edit(cmd, postid)
{
    var url = "ajax_editposts.php";
    var challenge = get_value_from_id('challenge');
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: { 
            cmd: cmd, 
            postid: postid, 
            challenge: challenge 
        }
    }).done( function (html) { 
        update_message_reload_transfers(html); 
    });
}

function transfer_edit(cmd, dlid)
{
    var url = "ajax_edittransfers.php";
    var challenge = get_value_from_id('challenge');
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: { 
            cmd: cmd,
            dlid: dlid,
            challenge: challenge 
        }
    }).done( function (html) { 
        update_message_reload_transfers(html); 
    });
}

function which_button(buttonval, e)
{
    var rightclick = false;
    if (!e) {
        e = window.event;
    }
    if (e.shiftKey) {
        rightclick = true;
    }

    close_browse_divs();
    if (buttonval == 'urddownload') {
        var url = "ajax_processbasket.php";
        var set_ids = new Array();
        $('input[name="set_ids[]"]').each(function() {
                set_ids.push($(this).val());
        });
        var data = { whichbutton : 'checksize', 'set_ids' : set_ids };
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: data
        }).done( function (html) {
            var content = $.parseJSON(html);
            if (content.error != 0) {
                show_confirm(content.message, function() {
                    process_whichbutton(buttonval, rightclick);
                });
                return;
            }
        });
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
    var url = "ajax_processbasket.php";
    var data = {
        whichbutton:buttonval,
        group:group_id,
        feed:feed_id,
        all: (rightclick ? 1:0),
        type:type,
        timestamp:timestamp,
        dlsetname:dlname,
        dl_dir:dl_dir,
        add_setname:add_setname,
        challenge: challenge
    };

    var set_ids = new Array();
    $('input[name="set_ids[]"]').each(function() {
        set_ids.push($(this).val());
    }
    ); 
    data.set_ids = set_ids;

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function (html) {
        var content = $.parseJSON(html);
        if (content.error == 0) {
            update_basket_display();
            set_message('message_bar', content.message, 5000);
            if (timestamp != null) {
                timestamp.value = '';
            }
            if (buttonval == 'urddownload') {
                set_as_downloaded_sets();
            }

            if (buttonval == 'mergesets' || buttonval == 'unmark_int_all' || buttonval == 'wipe_all' || buttonval == 'unmark_kill_all' || buttonval == 'mark_kill_all') { 
                load_sets();
            } else {
                reset_sets();
            }
        } else {
            set_message('message_bar', content.error, 5000);
        }
    });
}

function set_message(id, msg, timeout)
{ 
    if (msg == '') {
        $('#' + id).addClass('hidden');
    } else {
        $('#' + id).removeClass('hidden');
        $('#message_content').html(msg);
        $('#message_icon').click(function() { show_alert(msg);} );
        var boxwidth = $(document).width();
        var msgwidth = $('#' + id).width();
        $('#' + id).css('left', Math.round((boxwidth - msgwidth) /2));
        if (timeout > 0) {
            hide_message(id, timeout);
        }
    }
}

function blink_status()
{
    $('#status_item').addClass("menu_highlight");
    setTimeout(function() {
        $('#status_item').removeClass("menu_highlight");
    }, 2000);
}

function select_preview(binid, gid)
{
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_create_preview.php";
    var data = { 
        preview_bin_id : binid,
        preview_group_id : gid,
        challenge : challenge
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        var x = $.parseJSON(html);
        if (x.error == 0) {
            blink_status();
        } else {
            set_message('message_bar', x.error , 5000);
        }
    });
}

function show_preview(dlid, binary_id, group_id)
{
    var url = "ajax_showpreview.php";
    var data = { 
        dlid: dlid, 
        binary_id: binary_id,
        group_id: group_id
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        show_overlayed_content_1(html, 'popup700x400');
        var filetype = get_value_from_id('filetype', '');
        var file = get_value_from_id ('file', '');
        if (filetype == 'image') {
            show_image(file);
        } else if (filetype == 'text') {
            show_contents(file);
        } else {
            setTimeout(function () {
                var do_reload = $('#do_reload');
                if (overlayed_content_visible() && do_reload.val() === undefined) {
                    show_preview(dlid, binary_id, group_id);
                }
            }, 1000);
        }
    });
}

function delete_preview(dlid)
{
    var url = "ajax_action.php";
    var challenge = get_value_from_id('challenge');
    var data = { 
        cmd: 'delete_preview',
        dlid : dlid, 
        challenge : challenge 
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        load_activity_status(1);
        update_message_bar(html);
    });
}

function delete_blacklist(id, msg)
{
    var url = "ajax_action.php";
    var challenge = get_value_from_id('challenge');
    var which = get_value_from_id('which', '');
    var cmd = ''; 
    if (which == 'spots_blacklist') {
        cmd = 'delete_blacklist';
    } else if (which == 'spots_whitelist') {
        cmd = 'delete_whitelist';
    }
    var data = { 
        cmd : cmd,
        id : id ,
        challenge: challenge
    };
    var f = function () {
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: data
        }).done( function(html) {
            var x = $.parseJSON(html);
            if (x.error_code == 0) {
                if (x.action == 'delete') {
                    $("#item" + id).hide();
                } else if (x.action == 'update') {
                    $("#status"+id).html(x.data);
                }
            } else {
                update_message_bar(x.message);
            }
        });
    };

    if (msg === undefined) {
        f();
    } else {
        show_confirm(msg, f);
    }
}

function enable_blacklist(id)
{
    var url = "ajax_action.php";
    var challenge = get_value_from_id('challenge');
    var which = get_value_from_id('which', '');
    var cmd = ''; 
    if (which == 'spots_blacklist') {
        cmd = 'enable_blacklist';
    } else if (which == 'spots_whitelist') {
        cmd = 'enable_whitelist';
    }
    var data = { 
        cmd : cmd,
        id : id,
        challenge: challenge
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        var x = $.parseJSON(html);
        if (x.error_code == 0) {
            if (x.action == 'hide') {
                $("#item" + id).hide();
            } else if (x.action == 'update') {
                $("#status"+id).html(x.data);
            }
        } else {
            update_message_bar(x.message);
        }
    });
}

function update_message_bar(html) 
{ 
    if (html == "OK") {
        set_message('message_bar', '');
    } else if (html.substr(0,2) == "OK") {
        set_message('message_bar', html.substr(2), 5000);
    } else {
        if (html.substr(0, 7) == ':error:') {
            set_message('message_bar', html.substr(7), 5000);
        } else {
            set_message('message_bar', html, 5000);
        }
    }
}

function buttons_action_confirm(action, uid, msg)
{
    show_confirm(msg, function () {
        buttons_action(action, uid);
    });
}

function buttons_action(action, uid)
{
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_edit_searchoptions.php";
    var data = { 
        id : uid, 
        cmd : action,
        challenge : challenge 
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        if (action == 'edit') {
            show_overlayed_content_1(html, 'popup525x300');
        } else {
            show_buttons();
            update_message_bar(html);
        }
    });
}

function user_update_setting(uid, action, value)
{
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_edit_users.php";
    var data = { 
        id : uid, 
        cmd : 'update_setting', 
        action : action, 
        value : value,
        challenge : challenge 
    };
     $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        show_users();
        update_message_bar(html);
    });
}

function user_action_confirm(action, uid, msg)
{
    show_confirm(msg, function() {
        user_action(action, uid);
    });
}

function user_action(action, uid)
{
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_edit_users.php";
    var data = {
        id : uid,
        cmd : action,
        challenge : challenge 
    };
    $.ajax({
        type : 'post',
        url : url,
        cache : false,
        data : data
    }).done( function(html) {
        if (action == 'edit') {
            show_overlayed_content_1(html, 'popup700x400');
        } else {
            show_users();
            update_message_bar(html);
        }
    });
}

function usenet_action_confirm(action, uid, msg)
{
    show_confirm(msg, function() {
        usenet_action(action, uid);
    });
}

function usenet_action(action, uid)
{
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_edit_usenet_servers.php";
    var data = {
        id : uid,
        cmd : action,
        challenge : challenge 
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        show_usenet_servers();
        update_message_bar(html);
    });
}

function upload_handler(url, fn)
{
    var challenge = get_value_from_id('challenge', '');
    var command = get_value_from_id('command', '');
    var referrer = get_value_from_id('referrer', '');
    $('#submit_form').click(function(e) {
        var file = document.getElementById('files').files[0];
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function(e) {
            if (4 == this.readyState) {
                fn(xhr);
            }
        };
        var fd = new FormData();
        fd.append('challenge', challenge);
        fd.append('cmd', command);
        fd.append('filename', file);
        xhr.open('post', referrer, true);
        xhr.send(fd);
    });
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
    var data = { 
        referrer: referrer,
        challenge: challenge 
    };
    if (command !== null) {
        data.cmd = command;
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        show_overlayed_content_1(html, 'popup525x300');
        upload_handler(url, function(xmlHttp2) {
            if (xmlHttp2.responseText == 'OK') {
                hide_overlayed_content();
                reload_page(referrer);
            } else {
                update_message_bar(xmlHttp2.responseText);
            }
        });
    });
}

function hide_popup(itemname, baseclass)
{
    $('#' + itemname).removeClass(baseclass + 'on');
    $('#' + itemname).addClass(baseclass + 'off');
}

function show_rename_transfer(dlid)
{
    var url = "ajax_edittransfers.php";
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: { 
            cmd: 'showrename',
            dlid: dlid 
        }
    }).done( function(html) {
        show_overlayed_content_1(html, 'popup700x400');
    });
}

function edit_group(id)
{
    var url = "ajax_editgroup.php";
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: { 
            cmd: 'showeditgroup', 
            id: id
        }
    }).done( function(html) {
        show_overlayed_content_1(html, 'popup700x400');
        $('#group_name').focus();
        display_timebox('group_refresh_period');
    });
}

function edit_rss(id)
{
    var url = "ajax_editrss.php";
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: { 
            cmd: 'showeditrss', 
            id: id 
        }
    }).done( function(html) {
        show_overlayed_content_1(html, 'popup700x400');
        $('#rss_name').focus();
        display_timebox('rss_refresh_period');
    });
}

function edit_usenet_server(id, only_auth)
{
    var url = "ajax_edit_usenet_servers.php";
    var data = { cmd : 'showeditusenetserver', id : id, only_auth : (only_auth ? "1" : "0") };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        if (html.substr(0, 7) == ':error:') {
            set_message('message_bar', html.substr(7), 5000);
        } else {
            show_overlayed_content_1(html, (only_auth?'popup525x300':'popup700x400'));
        }
    });
}

function update_group()
{
    var id = get_value_from_id('id', '');
    var group_adult = get_value_from_id('group_adult', '');
    var group_minsetsize = get_value_from_id('group_minsetsize', '');
    var group_maxsetsize = get_value_from_id('group_maxsetsize', '');
    var group_time1 = get_value_from_id('group_time1', '');
    var group_time2 = get_value_from_id('group_time2', '');
    var group_expire = get_value_from_id('group_expire', '');
    var group_subscribed = get_value_from_id('group_subscribed', '');
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_editgroup.php";
    var data = {
        cmd: 'update_group',
        id : id,
        group_adult: group_adult, 
        group_time1: group_time1,
        group_maxsetsize: group_maxsetsize, 
        group_minsetsize: group_minsetsize,
        group_time2: group_time2,
        group_refresh_period: $('#group_refresh_period>option:selected').val(),
        group_expire: group_expire,
        group_subscribed: group_subscribed,
        challenge:challenge 
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        if (html == 'OK') {
            hide_overlayed_content();
        }
        update_message_bar(html);
        load_groups();
    });
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
    var rss_expire = get_value_from_id('rss_expire', '');
    var rss_subscribed = get_value_from_id('rss_subscribed', '');
    var challenge = get_value_from_id('challenge', '');
    var url = "ajax_editrss.php";
    var data = { 
        cmd:'update_rss',
        id:id,
        rss_name:rss_name,
        rss_adult:rss_adult,
        rss_url:rss_url,
        rss_time1:rss_time1,
        rss_time2:rss_time2, 
        rss_refresh_period: $('#rss_refresh_period>option:selected').val(),
        rss_password:rss_password,
        rss_username:rss_username, 
        rss_expire:rss_expire,
        rss_subscribed: rss_subscribed,
        challenge:challenge
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        if (html == 'OK') {
            hide_overlayed_content();
        }
        update_message_bar(html);
        load_rss_feeds();
    });
}

function update_buttons()
{
    var id = get_value_from_id('id');
    var challenge = get_value_from_id('challenge');
    var name = get_value_from_id('name');
    var search_url = get_value_from_id('search_url');
    var url = "ajax_edit_searchoptions.php";
    var data = { 
        cmd: 'update_button',
        id : id,
        name : name,
        search_url : search_url,
        challenge : challenge 
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        if (html == 'OK') {
            hide_overlayed_content();
            show_buttons();
        }
        update_message_bar(html);
    });
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
    var data = { 
        cmd : 'update_user',
        id: id,
        username: username,
        password: password,
        fullname: fullname,
        email: email,
        isactive: isactive,
        post: post,
        isadmin: isadmin,
        autodownload: autodownload,
        allow_erotica: allow_erotica,
        allow_update: allow_update,
        seteditor: seteditor,
        fileedit: fileedit,
        challenge: challenge 
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        if (html == 'OK') {
            hide_overlayed_content();
            show_users();
        }
        update_message_bar(html);
    });
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
    var data = {
        cmd :'update_usenet_server', 
        id:id,
        name:name,
        hostname:hostname,
        username:username,
        port:port,
        secure_port:sec_port,
        password:password,
        authentication:authentication,
        priority:priority,
        connection:connection,
        threads:threads,
        challenge:challenge,
        compressed_headers:compressed_headers,
        posting:posting
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        if (html == 'OK') {
            hide_overlayed_content();
            show_usenet_servers();
        }
        update_message_bar(html);
    });
}

function post_message()
{
    var subject = get_value_from_id('subject');
    var type = get_value_from_id('type');
    var reference = get_value_from_id('reference');
    var rating = get_value_from_id('rating');
    var postername = get_value_from_id('postername');
    var posteremail = get_value_from_id('posteremail');
    var challenge = get_value_from_id('challenge');
    var message = get_value_from_id('messagetext');
    var url = "ajax_post_message.php";
    var data = { 
        cmd: 'post',
        subject: subject,
        postername: postername,
        posteremail: posteremail,
        message: message,
        rating: rating,
        reference: reference,
        type: type,
        groupid: $('#groupid>option:selected').val(),
        challenge: challenge 
    };
     $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        var x = $.parseJSON(html);
        if (x.error == 0) {
            hide_overlayed_content();
            set_message('message_bar', x.message, 5000);
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function create_post()
{
    var subject = get_value_from_id('subject');
    var postername = get_value_from_id('postername');
    var posteremail = get_value_from_id('posteremail');
    var recovery = get_value_from_id('recovery');
    var filesize = get_value_from_id('filesize');
    var postid = get_value_from_id('postid');
    var delete_files = get_value_from_id('delete_files');
    var timestamp = get_value_from_id('timestamp');
    var challenge = get_value_from_id('challenge');
    var url = "ajax_process_post.php";
    var data = {
        cmd:'post',
        subject: subject,
        postername: postername,
        posteremail: posteremail,
        delete_files: delete_files,
        recovery: recovery,
        groupid: $('#groupid>option:selected').val(),
        groupid_nzb: $('#groupid_nzb>option:selected').val(),
        directory: $('#directory>option:selected').val(),
        postid: postid,
        filesize: filesize,
        challenge: challenge, 
        timestamp: timestamp 
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
            if (html == 'OK') {
            hide_overlayed_content();
        }
        update_message_reload_transfers(html);
    });
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
    var data = { 
        cmd : 'rename',
        dlid: dlid ,
        dlname: dlname,
        dlpass: dlpass,
        'delete': deletef,
        unrar: unrar,
        subdl: subdl,
        dl_dir: dl_dir,
        add_setname: add_setname,
        unpar: unpar,
        starttime: starttime,
        challenge: challenge
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        if (html == 'OK') {
            hide_overlayed_content();
        }
        update_message_reload_transfers(html);
    });
}

function do_submit(name)
{
    $('#' + name).submit();
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
    var data = { 
        cmd: 'show_rename',
        dir: dir, 
        filename:name,
        challenge: challenge 
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        var x = $.parseJSON(html);
        if (x.error == 0 ) {
            show_overlayed_content_1(x.contents, 'popup525x300');
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function update_filename()
{
    var challenge = get_value_from_id('challenge');
    var directory = get_value_from_id('directory_editfile');
    var oldfilename = get_value_from_id('oldfilename_editfile');
    var newfilename = get_value_from_id('newfilename_editfile');
    var rights = get_value_from_id('rights_editfile');
    var group = get_value_from_id('group_editfile');
    var url = "ajax_editviewfiles.php";
    var data = {
        cmd : 'do_rename',
        dir: directory, 
        oldfilename: oldfilename,
        newfilename: newfilename,
        challenge: challenge,
        rights: rights,
        group: group 
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        var x = $.parseJSON(html);
        if (x.error == 0 ) {
            hide_overlayed_content();
            show_files( { 'curdir':directory, 'reset_offset': false });
            set_message('message_bar', x.message, 5000);
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function update_quick_menu_images()
{
    // Loop all "quickmenuitem_x" divs:
    var themaindiv = $('#quickmenu');
    var theinnerdiv = $('#quickmenuinner');

    // Assuming 400x10
    var height = 18,
        i;
    var thediv;

    // Number of items to show:
    var quicks = $('#nrofquickmenuitems').val();
    if (quicks <= 0) {
        close_quickmenu();
        return;
    }
    for (i = 1; i <= quicks; i++) {
        thediv = $('#quickmenuitem_' + i);
        thediv.css('left', '0px');
        thediv.css('top',( (i - 1) * height) + 'px');
    }
    theinnerdiv.css('marginTop', '10px');
    theinnerdiv.css('height', (quicks * height) + 'px');
    themaindiv.css('height', (quicks * height) + 'px');
}

function close_quickmenu()
{
    hide_popup('quickmenu', 'quickmenu');
}

function show_quickmenu(type, subject, srctype, e)
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
    $('#quickmenu').addClass('quickmenuon');
    $('#quickmenu').removeClass('quickmenuoff');
    var selection = get_selected_text();
    selection = (selection === '') ? "0" : "1";

    var killflag = get_value_from_id('killflag');
    // Fill menu
    var url = "ajax_showquickmenu.php";
    
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: { 
            type : type, 
            srctype : srctype,
            killflag : killflag, 
            selection : selection,
            subject : subject 
        } 
    }).done(function(html) {
        $('#quickmenu').html(html);
        update_quick_menu_images();
    });

    // Loading.
    $('#quickmenu').html("");

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
    var divwidth = $('#quickmenu').outerWidth();
    var divheight = $('#quickmenu').outerHeight();
    $('#quickmenu').css('zindex', "10000000");

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

    $('#quickmenu').css('left', newdivleft + 'px');
    $('#quickmenu').css('top', newdivtop + 'px');
    return true;
}

function show_quick_display(srctype, subject, e, type)
{
    // Fill menu
    var url = "ajax_showquickdisplay.php";
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data:{ 
            type : type, 
            srctype : srctype,
            subject : subject 
        } 
    }).done( function(html) {
        show_overlayed_content_1(html, 'quickwindowon');
        // we increase the size beyond the default if div is not large enough for the contents
        var height = Math.floor($(window).height() * 0.9);
        var used_height = $('#td_sets').get(0).scrollHeight;
        var max_height = $('#td_sets').css('max-height').replace('px', '');

        if ((used_height > max_height && height > max_height)) {
            var width = Math.floor($(window).width() * 0.75);
            $('#overlay_content').css('width', width);
            $('#overlay_content').css('height', height);
            $('#overlay_content').css('marginTop', (- Math.floor(height / 2)));
            $('#overlay_content').css('marginLeft', (- Math.floor(width / 2)));
            $('#overlay_content').css('top', '50%');
            $('#overlay_content').css('left', '50%');
            var title_height = $('#text_title').outerHeight() + 28;
            var inner_height = $('#overlay_content').innerHeight();
            $('#td_sets').css('height', inner_height - title_height);
            $('#td_sets').css('max-height', inner_height - title_height);
        }
        $('#td_sets').scrollTop(0);
    });
}

function guess_extset_info_safe(setID, type)
{
    var setname = get_selected_text();
    if (setname === '') {
        show_alert("Please select the set name before clicking this button.");
    } else {
        var url = "ajax_showquickdisplay.php";
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: { 
                type : type, 
                srctype : 'setguessesisafe',
                setname : setname,
                subject : setID 
            } 
        }).done( function() {
            show_quick_display('seteditesi', setID, '', type);
        });
    }
}

function guess_basket_extset_info(setID, type)
{
    var setname = get_selected_text();
    if (setname === '') {
        show_alert("Please select the set name before clicking this button.");
    } else {
        var url = "ajax_showquickdisplay.php";
        var data = { 
            srctype: 'setbasketguessesi', 
            subject: 'undefined',
            type : 'undefined', 
            setname : setname
        }; 
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: data
        }).done( function() {
            // Reload to show the new info:
            load_sets();
            close_quickmenu();
        });
    }
    return true;
}

function guess_extset_info(setID, type)
{
    var setname = get_selected_text();
    if (setname == '') {
        show_alert("Please select the set name before clicking this button.");
    } else {
        var url = "ajax_showquickdisplay.php";
        var data = { 
            srctype: 'setguessesi',
            subject: setID,
            type: type,
            setname: setname
        };
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: data
        }).done( function(html) {
            close_quickmenu();
            // Also echo the new setname into the TD:
            if (html !== '') {
                $('#td_set_' + setID).html('<div class="donotoverflowdamnit">' + html + '</div>');
                update_widths('browsesubjecttd');
            }
        });
    }
}

function save_extset_binary_type(setID, sel, srctype, type)
{
    var binarytype = sel.options[sel.selectedIndex].value;
    var url = "ajax_showquickdisplay.php";
    var data = { 
        srctype : 'setsavebintype', 
        subject : setID,
        type: type, 
        'values[binarytype]' : binarytype
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        show_quick_display('seteditesi', setID, '', type);
    });
}

function save_extset_info(setID, type)
{
    var formname = 'ext_setinfo_' + setID;
    var url = "ajax_showquickdisplay.php";
    var data = { 
        srctype : 'setsaveesi', 
        subject : setID,
        type : type
    };
    for (var i = 0; i < document.forms[formname].elements.length; i++) {
        data [ document.forms[formname].elements[i].name ] = document.forms[formname].elements[i].value;
    }
    hide_overlayed_content();
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        // Also echo the new setname into the TD
        var thetd = $('#td_set_' + setID);
        if (html !== '') {
            thetd.html(html);
        }
    });
}

function remove_rss(id, msg)
{
    show_confirm(msg, function() {
        var challenge = get_value_from_id('challenge');
        $.ajax({
            type: 'post',
            url: 'ajax_editrss.php',
            cache: false,
            data: { 
                cmd: 'delete', 
                challenge: challenge 
            } 
        }).done( function(html) {
            if (html.substr(0, 7) == ':error:') {
                set_message('message_bar', html.substr(7), 5000);
            } else {
                load_subscriptions(html);
            }
        });
    });
}

function confirm_delete_account(id, msg)
{
    var challenge = get_value_from_id('challenge');
    show_confirm(msg, function () {
        $.ajax({
            type: 'post',
            url: 'ajax_delete_account.php',
            cache: false,
            data: {
                delete_account: 1,
                challenge: challenge 
            } 
        }).done( function(html) {
            var x = $.parseJSON(html);
            if (x.error == 0) {
                show_alert(x.message);
                setTimeout(function (){ jump('logout.php'); } ,5000);
            } else {
                set_message('message_bar', x.error, 5000);
            }
        });
    });
    return true;
}

function fold_transfer(id, type)
{
    // id = global or ready/active/finished/error/etc...
    // type = down/post
    var url = 'ajax_update_session.php';
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: { 
            'var' : id, 
            type : type 
        } 
    }); 
    $('#' + id + type).toggleClass('dynimgplus');
    $('#' + id + type).toggleClass('dynimgminus');

    if (type == 'post') {
        $('#data_post_' + id).toggleClass('hidden');
    } else {
        $('#data_down_' + id).toggleClass('hidden');
    }
}

function toggle_group_of_sets(startset, stopset, type)
{
    var inrange = false;
    var thissetvalue = null;
    var toggleitems = [];

    $("input[name='setdata[]']").each( function() {
        // We want to toggle the last set as well, but definitely not the first one 
        // (it got toggled when the user clicked it, don't toggle it back)

        // Also, setdata[] stuff always starts with "set_"!

        thissetvalue = $(this).attr('id').substr(4);
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
        if ((inrange === true && thissetvalue !== startset) ||  thissetvalue === stopset) {
            toggleitems.push(thissetvalue);
        }
    });

    // Now do the actual toggling
    $.each(toggleitems, function(key, val) {
        toggle_set(val, type);

    });
}

function fold_adv_search(button_id, id)
{
    $('#' + button_id).toggleClass('dynimgplus');
    $('#' + button_id).toggleClass('dynimgminus');
    $('#' + id).toggleClass('hidden');
    update_search_bar_height();
}

function clear_form(formId, except) 
{ 
    $("#" +formId).find(":input").each(function()  {
        var type = $(this).prop("type");
        if (type == 'text' || type == 'select-one' || type == 'textarea') { 
            $(this).val('');
        }
    });
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
    var orig_size = $('#' + name + '_orig_size').val();
    var sel = $('#' + name);
    var size;
    if (par == 'size') {
        size = parseInt(sel.attr('size'));
    } else if (par == 'rows') {
        size = parseInt(sel.attr('rows'));
    }
    if (size >= orig_size) {
        size = 2;
    } else {
        size = orig_size;
    }

    $('#' + name + '_collapse').toggleClass('dynimgminus');
    $('#' + name + '_collapse').toggleClass('dynimgplus');
    if (par == 'size') {
        sel.attr('size', size);
    } else if (par == 'rows') {
        sel.attr('rows', size);
    }
}

function submit_language_login()
{
    var change = $('#language_change');
    var curr_language = $('#curr_language').val();
    var myform = $('#urd_login_form');
    if (change !== null ) {
        var langval = $('#language_select>option:selected').val();
        change.val(1);
        if (curr_language === undefined || curr_language.value != langval) {
            curr_language.value = langval;
            myform.submit();
        }
    }
}

function submit_upload()
{
    // need to rewrite to do proper error handling
    var src_remote = get_value_from_id('url'); // its a url we post, to be gotten by the server
    var src_local = get_value_from_id('upfile'); // it's a local file we upload to the server
    var iframe_id = 'iframe_' + String(Math.round(Math.random()* 10000));

    $('<iframe id="' + iframe_id + '" name="' + iframe_id + '" style="margin-top:200px;">').appendTo('body');
    $('#' + iframe_id).hide();
    if (src_remote != '') {
        $('#parseform').attr('target', iframe_id);// the iframe swallows the upload, so the page does not have to reload
        $('#timestamp1').val(get_value_from_id('timestamp'));
        $('#add_setname1').val(get_value_from_id('add_setname'));
        $('#setname1').val(get_value_from_id('setname'));
        $('#dl_dir1').val(get_value_from_id('dl_dir'));
        $('#parseform').submit();
        hide_overlayed_content();
    } else if (src_local != '') {
        $('#uploadform').attr('target', iframe_id);// the iframe swallows the upload, so the page does not have to reload
        $('#add_setname2').val(get_value_from_id('add_setname'));
        $('#dl_dir2').val(get_value_from_id('dl_dir'));
        $('#setname2').val(get_value_from_id('setname'));
        $('#timestamp2').val(get_value_from_id('timestamp'));
        $('#uploadform').submit();
        hide_overlayed_content();
    } else {
        return false;
    }
    var i = 0;
    var poll_iframe = function() {
        i++;
        if (document.getElementById(iframe_id).contentWindow.document.body != null) {
            var msg = document.getElementById(iframe_id).contentWindow.document.body.innerHTML;
            if (msg != null && msg != '') {
                i = 21;
                $('#' + iframe_id).remove();
                update_message_bar(msg);
            }
        }
        if (i < 20) { 
            setTimeout(poll_iframe, 200);
        }
    };
    poll_iframe();
    return true;
}

function show_auth()
{
    var need_auth = get_value_from_id('needauthentication');

    if (need_auth==1) {
        $('#authpass').removeClass('hidden');
        $('#authuser').removeClass('hidden');
    } else {
        $('#authpass').addClass('hidden');
        $('#authuser').addClass('hidden');
    }
}

function edit_file(fileid)
{
    console.log('foo');
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
        if (name == '' || dir == '') {
            return;
        }
    }
    var url = "ajax_editviewfiles.php";
    var data = { 
        cmd : cmd,
        dir :dir,
        filename:name,
        challenge: challenge 
    };

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function(html) {
        console.log(html);
        var x = $.parseJSON(html);
        if (x.error == 0) {
            show_overlayed_content_1(x.contents, 'popup700x400');
            $('#filename_editfile').focus();
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function save_file()
{
    var challenge = get_value_from_id('challenge');
    var name = get_value_from_id('filename_editfile');
    var dir = get_value_from_id('directory_editfile');
    var filename_err = get_value_from_id('filename_err');
    var newfile = $('#newfile').val();
    var contents = get_value_from_id('filecontents_editfile');
    var newdir = get_value_from_id('newdir', '0');
    if (dir == '' && contents == '') {
        return false;
    }
    if (name == '') {
        console.log('aoeuao');
        set_message('message_bar', filename_err, 5000);
        return false;
    }
    newfile = (newfile == 'new') ? "1" : "0";
    var url = "ajax_editviewfiles.php";
    var data = { 
        cmd: 'save_file',
        dir : dir, 
        filename : name,
        file_contents : contents,
        newfile : newfile,
        newdir : newdir,
        challenge : challenge
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function(html) {
        var x = $.parseJSON(html);
        if (x.error == 0) {
            hide_overlayed_content();
            show_files( { 'curdir' : null, 'reset_offset': false });
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
    return true;
}

function edit_categories()
{
    var challenge = get_value_from_id('challenge');
    var url = "ajax_editcategory.php";
    var data = { 
        cmd : 'edit',
        challenge: challenge
    };
    close_browse_divs();

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function(html) {
        show_overlayed_content_1(html,'popup525x300');
        $('#cat_name').focus();
    });
}

function get_category_name()
{
    var cat_name = $('#cat_name');
    var cat_id = $('#cat_id');

    var idx = $('#category_id>option:selected').val();
    var url = "ajax_editcategory.php";
    var data = { 
        cmd : 'get_name', 
        id :idx 
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function(html) {
        if (html != '__error__') {
            cat_name.val(html);
            cat_id.val(idx); 
        } else {
            cat_name.val('');
            cat_id.val('new'); 
        }
    });
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
    var data = { 
        cmd : 'update_category', 
        id:cat_id,
        name:cat_name,
        challenge:challenge
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function() {
        load_subscriptions();
        hide_overlayed_content();
        toggle_table('groupstable', 'user', 'admin');
    });
}

function delete_category()
{
    var challenge = get_value_from_id('challenge');
    var cat_id = get_value_from_id('cat_id');
    var url = "ajax_editcategory.php";
    if (cat_id == '') {
        return;
    }
    var data = { 
        cmd : 'delete_category',
        id : cat_id,
        challenge :challenge
    };

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function() {
        load_subscriptions();
    });
}

function show_calendar(month, year, clear_time)
{
    var timestamp = get_value_from_id('timestamp');
    var url = "ajax_calendar.php";
    var _minute = new Date().getMinutes();
    var _hour = new Date().getHours();
    var data = { 
        cmd : 'show_calendar', 
        timestamp : timestamp
    };
    if (month !== null) {
        data.month = month;
    }
    if (year !== null) {
        data.year = year;
    }
    if (clear_time !== null) {
        var hour = $('#hour');
        var minute = $('#minute');
        if (hour !== null) {
            hour = hour.val();
            _hour = data.hour = hour;
        }
        if (minute !== null) {
            minute = minute.val();
            _minute = data.minute = minute;
        }
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function(html) {
        show_overlayed_content_2(html, 'calendardiv');
        $(function() {
            _hour =$("#hour").val();
            _minute = $("#minute").val();
        $('#hours').slider(
            {
                min: 0,
                max: 23,
                value: _hour,
                slide: function(event, ui) { 
                    $("#hour").val(ui.value);
                    $("#time1").val(ui.value + ':' + $("#minute").val());
                }
            }
            );
        $('#minutes').slider(
            {
                min: 0,
                max: 59,
                value: _minute,
                slide: function(event, ui) { 
                    $("#minute").val(ui.value);
                    $("#time1").val($("#hour").val() + ':' + $("#minute").val());
                }
            });
        });
    });
}

function submit_calendar(none)
{
    if (none == 'atonce') {
        $('#timestamp').val(' ');
    } else { 
        $('#timestamp').val($('#date1').val() + ' ' + $('#time1').val());
    }
    if ($('#basketbuttondiv') !== undefined) {
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
    $('#day_' + day).toggleClass('highlight3');
    if (old_day != 0) {
        $('#day_' + old_day).toggleClass('highlight3');
    }
    $('#day').val(day);
    $('#date1').val(year + '-' + month + '-' + day);
}

function clear_checkbox(id)
{
    var box = $('#' + id);
    var img = $('#' + id + '_img');
    if (box != null && img != null) {
        box.val(0);
        img.removeClass('checkbox_on checkbox_tri');
        img.addClass('checkbox_off');
    }
}

function set_checkbox(id, val)
{
    var box = $('#' + id);
    var img = $('#' + id + '_img');
    if (box != null && img != null) {
        box.val(val);
        img.removeClass('checkbox_on checkbox_tri checkbox_off');
        if (val == 1) {
            img.addClass('checkbox_on');
        } else if (val == 2) {
            img.addClass('checkbox_tri');
        } else {
            img.addClass('checkbox_off');
        }
    }
}

function clear_all_checkboxes(cat)
{
    var name = 'subcat_';
    if (cat !== null) {
        name = name + cat;
    } 
    $('input[name^="' + name + '"]').each(function() {
        clear_checkbox($(this).attr('id'));
    });
}

function update_adult(type, id)
{
    var box = get_value_from_id('adult_' + id);
    var challenge = get_value_from_id('challenge');
    var url, data;
    if (type == 'group') { 
        url = "ajax_groups.php";
        data = { 
            cmd : 'toggle_adult',
            group_id :id,
            value: box,
            challenge: challenge
        };
    } else if (type == 'rss') {
        url = "ajax_rss_feeds.php";
        data = { 
            cmd : 'toggle_adult',
            feed_id : id,
            value : box,
            challenge : challenge
        };
    } else {
        return;
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function(html) {
        update_message_bar(html);
    });
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
    $('#page_tab').val(scope_on);
    if (scope_on == 'admin') {
         $('#button_user').removeClass('tab_selected');
        $('#button_global').addClass('tab_selected');
    } else {
        $('#button_user').addClass('tab_selected');
        $('#button_global').removeClass('tab_selected');
    }
    
    $('#' + table_id + " tr").each( function() {
        $(this).children("td, th").each( function() { 
            if ($(this).hasClass(scope_off)) {
                $(this).addClass('hidden');
                $('#page1').val(scope_on);
            }
            if ($(this).hasClass(scope_on)) {
                $(this).removeClass('hidden');
            }
        });
    });
}

function select_tab_setting(tab, session_var, session_val)
{
    if (session_var !== null && session_val !== null) {
        var url = 'ajax_update_session.php';
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: { 
                'var' : session_val, 
                type : session_val 
            } 
        }); 
    }

    $('input[name="tabs"]').each( function () {
        var content = $('#' + $(this).val() + '_tab');
        if (!content.hasClass('hidden')) {
            content.addClass('hidden');
        }
        $('#' + $(this).val() + '_bar_elem').removeClass('tab_selected');
        $('#' + $(this).val() + '_bar').removeClass('tab_selected');
    });

    $('#current_tab').val(tab);
    $('#' + tab + '_tab').removeClass('hidden');
    $('#' + tab + '_bar_elem').addClass('tab_selected');
}

function select_tab_transfers(tab, session_var, session_val)
{
    if (session_var !== null && session_val !== null) {
        var url = 'ajax_update_session.php';
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: { 
                'var' : session_val,
                type : session_val 
            }
        }); 
    }
    $('input[name="tabs"]').each( function () {
        $('#' + $(this).val() + '_bar').removeClass('tab_selected');
    });

    $('#active_tab').val(tab);
    $('#' + tab + '_bar').addClass('tab_selected');
    update_transfers();
}

function select_tab_blacklist(tab)
{
    $('input[name="tabs"]').each( function () {
        $('#' + $(this).val() + '_bar').removeClass('tab_selected');
    });
    $('#active_tab').val(tab);
    $('#' + tab + '_bar').addClass('tab_selected');
    if (tab == 'whitelist') {
        show_blacklist({ 'which':'spots_whitelist', 'order':'spotter_id', 'def_direction':'asc', 'offset':0});
    } else if (tab == 'blacklist') {
        show_blacklist({ 'which':'spots_blacklist', 'order':'spotter_id', 'def_direction':'asc', 'offset':0});
    } 
}

function select_tab_stats(tab, type, year, period, source, subtype)
{
    if (tab == 'spots_details') {
        type = 'spots_details';
        tab = 'supply';
        period = subtype = source = year = null;
    } else if (tab == 'supply' && type != 'spots_details' && period == null) {
        type = 'supply';
        period = subtype = source = year = null;
    } else if (tab == 'supply_details') {
        type = 'supply';
        tab = 'supply';
        period = 'month';
        subtype = source = year = null;
    }

    var width = ($(window).width()) / 2.2;
    var data = { type : type , width : String (width) };
    if (year != null) {
        data.year = year; 
    }
    if (period != null) {
        data.period = period; 
    }
    if (subtype != null) {
        data.subtype = subtype; 
    }
    if (source != null) {
        data.source = source; 
    }
    $.ajax({
        type: 'post',
        url: 'ajax_stats.php',
        cache: false,
        data: data
    }).done( function(html) {
        $('#show_stats').html(html);
        var x = document.getElementsByName('tabs');
        for (var i = 0; i < x.length; i++) {
            $('#' + x[i].value + '_bar').removeClass('tab_selected');
        }
        $('#' +tab + '_bar').addClass('tab_selected');
        $('#selected').val(type);
        update_search_bar_height();
    });
}

function show_help(msg, header, th)
{
    $('#helpheader').html(header);
    $('#helpbody').html(msg);
    var pos = th.offset();
    $('#helpwrapper').removeClass('hidden');
    $('#helpwrapper').css('max-width', $(window).width()/2);
    var h = $(window).height();
    var w = $(window).width();
    var s = $('#helptext').outerHeight();
    var new_pos, ac, rc;
    if ( pos.top + s + 30 < h ) {
        new_pos = pos.top + th.outerHeight() + 6; // +6 to allow for the callout
        rc = 'bubble_bottom';
        ac = 'bubble_top';
    } else {
        new_pos = pos.top - s - 6;
        ac = 'bubble_bottom';
        rc = 'bubble_top';
    }
    $('#helpwrapper').css('top', new_pos);
    $('#helpwrapper').css('left', Math.round(w / 4));
    $('#helpwrapper').outerHeight($('#helptext').outerHeight());
    $('#helpwrapper').removeClass(rc);
    $('#helpwrapper').addClass(ac);
    $('#helptext').css('left', 0);
}

function hide_help()
{
    $('#helpwrapper').addClass('hidden');
}

function show_small_help(msg, ev)
{
    if (!ev) {
        ev = window.event;
    }

    var posx = ev.clientX;
    var posy = ev.clientY;
    $('#smallhelp').html(wordwrap(msg));
    // if the mouse is too much to the right we simple move the tooltip to the left. 400 seems a resonable size 
    // tooltip is fixed size anyway
    if (posx > 400) {
        posx -= 120;
    }
    $('#smallhelp').css('left', (posx + 1) + "px");
    $('#smallhelp').css('top' , (posy + 1) + "px");
    $('#smallhelp').css('zIndex' , 10000000);
    $('#smallhelp').removeClass('hidden');
}

function hide_small_help()
{
    $('#smallhelp').addClass('hidden');
}

function load_sets(options)
{
    close_browse_divs();
    var type = get_value_from_id('type');
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

function close_browse_divs()
{
    close_quickmenu();
    hide_small_help();
    hide_help();
}

function get_subcats_from_form(searchform)
{
    var data = {}, name, value;
    $('input[name^="subcat_"]').each (function() {
        name = $(this).attr('name');
        value = $(this).val();
        data [ name ] = value;
    });

    return data;
}

function update_search_names(name)
{
    var url = "ajax_saved_searches.php";
    var type = get_value_from_id('usersettype', '');
    var data = { 
        cmd: 'names',
        type: type,
        current: name 
    };
     $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function(html) {
            console.log(html);
        var x = $.parseJSON(html);
        if (x.error != 0 || x.count == 0) {
            $('#save_search_outer').addClass('hidden');
            $('#save_search_span').html('');
        } else {
            $('#save_search_span').html(x.contents);
            $('#save_search_outer').removeClass('hidden');
        }
    });
}

function show_savename()
{
    var url = 'ajax_saved_searches.php';
    var save_name = $('#saved_search>option:selected').val();
    var type = get_value_from_id('usersettype', '');
    var data = { 
        type : type,
        name: save_name,
        cmd : 'show'
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function(html) {
            console.log(html);
        var x = $.parseJSON(html);
        show_overlayed_content_1(x.contents, 'savenamediv');
        $('#savename_val').focus();
    });
}

function delete_search_confirm()
{
    if ( $('#saved_search').prop('selectedIndex') == 0) {
        return;
    }

    var sname = $('#saved_search>option:selected').val();
    var msg = get_value_from_id('ln_delete_search', 'Delete');
    show_confirm(msg + ': "' + sname + '"', delete_search);
}

function delete_search()
{
    if ($('#saved_search').prop('selectedIndex') == 0) {
        return;
    }
    var sname = $('#saved_search>option:selected').val();
    var url = "ajax_saved_searches.php";

    var type = get_value_from_id('usersettype', '');
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: { 
            type: type, 
            cmd: 'delete',
            name: sname
        } 
    }).done( function(html) {
            console.log(html);
        var x = $.parseJSON(html);
        if (x.error == 0) {
            update_search_names('');
            set_message('message_bar', x.message , 5000);
        } else {
            set_message('message_bar', x.error , 5000);
        }
    });
}

function save_browse_search()
{
    var url = "ajax_saved_searches.php";
    var data = { cmd : 'save'};
    var search = get_value_from_id('search', '');
    var save_category = $('#category_id>option:selected').val();
    var type = get_value_from_id('usersettype', '');
    var sname = get_value_from_id('savename_val', '');
    var flag = $('#flag>option:selected').val();
    var group = $('#select_groupid>option:selected').val();
    var feed = $('#select_feedid>option:selected').val();
    var minsetsize = get_value_from_id('minsetsize','');
    var maxsetsize = get_value_from_id('maxsetsize','');
    var minage = get_value_from_id('minage','');
    var maxage = get_value_from_id('maxage','');
    var mincomplete = get_value_from_id('mincomplete','');
    var maxcomplete = get_value_from_id('maxcomplete','');
    var minrating = get_value_from_id('minrating','');
    var maxrating = get_value_from_id('maxrating','');
    data.feed = feed;
    data.group = group;
    data.name = sname;
    data.save_category = save_category;
    data.cat = "0";
    data.flag = flag;
    data.minsetsize = minsetsize;
    data.maxsetsize = maxsetsize;
    data.minage = minage;
    data.maxage = maxage;
    data.minrating = minrating;
    data.maxrating = maxrating;
    data.mincomplete = mincomplete;
    data.maxcomplete = maxcomplete;
    data.type = type;
    data.search = search;
    hide_overlayed_content();
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
            console.log(html);
        var x = $.parseJSON(html);
        if (x.error == 0) {
            update_search_names(sname);
            set_message('message_bar', x.contents, 5000);
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function save_spot_search()
{
    var save_category = $('#category_id>option:selected').val();
    var data = get_subcats_from_form('searchform');
    var url = "ajax_saved_searches.php";
    var search = get_value_from_id('search', '');
    var sname = get_value_from_id('savename_val', '');
    var cat = get_selected_cat();
    var type = get_value_from_id('usersettype', '');
    var flag = $('#flag>option:selected').val();
    var minsetsize = get_value_from_id('minsetsize','');
    var maxsetsize = get_value_from_id('maxsetsize','');
    var minage = get_value_from_id('minage','');
    var maxage = get_value_from_id('maxage','');
    var poster = get_value_from_id('poster','');
    data.name = sname;
    data.save_category = save_category;
    data.cat = cat;
    data.flag = flag;
    data.minsetsize = minsetsize;
    data.maxsetsize = maxsetsize;
    data.minage = minage;
    data.poster = poster;
    data.maxage = maxage;
    data.cmd = 'save';
    data.type = type;
    data.search = search;
    hide_overlayed_content();
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
            console.log(html);
        var x = $.parseJSON(html);
        if (x.error == 0) {
            update_search_names(sname);
            set_message('message_bar', x.message, 5000);
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function update_browse_searches(name)
{
    var url = "ajax_saved_searches.php";
    if (name == null || name == '') {
        if ($('#saved_search').prop('selectedIndex') == 0 || name == '') {
            clear_form("searchform");
            clear_form("sidebar_contents");
            update_search_names('');
            load_sets();
            return;
        }
        name = $('#saved_search>option:selected').val();
    } 
    var type = get_value_from_id('usersettype', '');
   
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: { 
            type : type,
            name : name,
            cmd : 'get', 
            cat: 0 
        } 
    }).done( function(html) {
            console.log(html);
        var x = $.parseJSON(html);
        $('#save_category').val('');
        if (x.error == 0) {
            if (x.count > 0) {
                update_search_names(name);
                clear_form("searchform");
                clear_form("sidebar_contents");
                var sc = x.options;
                $.each(sc, function(key, val) {
                    if (key == 'minsetsize') { setvalbyid('minsetsize', val); }
                    else if (key == 'maxsetsize') { setvalbyid('maxsetsize', val); }
                    else if (key == 'maxage') { setvalbyid('maxage', val); }
                    else if (key == 'minage') { setvalbyid('minage', val); }
                    else if (key == 'maxcomplete') { setvalbyid('maxcomplete', val); }
                    else if (key == 'mincomplete') { setvalbyid('mincomplete', val); }
                    else if (key == 'maxrating') { setvalbyid('maxrating', val); }
                    else if (key == 'minrating') { setvalbyid('minrating', val); }
                    else if (key == 'flag') { setvalbyid('flag', val); }
                    else if (key == 'group') { setvalbyid('select_groupid', val); }
                    else if (key == 'feed') { setvalbyid('select_feedid', val); }
                    else if (key == 'search') { setvalbyid('search', val);}
                    else if (key == 'category') { setvalbyid('save_category', val); }
                });
                load_sets({'offset':'0', 'setid':''});
            } else  {
                clear_form("searchform");
                load_sets({'offset':'0', 'setid':''});
            }
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function update_spot_searches(name)
{
    var url = "ajax_saved_searches.php";
    if (name == null) {
        if ($('#saved_search').prop('selectedIndex') == 0) {
            clear_form("searchform");
            clear_form("sidebar_contents");
            clear_all_checkboxes(null); 
            uncheck_all(null) ;
            update_search_names('');
            load_sets();
            return;
        }
        name = $('#saved_search>option:selected').val();
    } 
    var type = get_value_from_id('usersettype', '');
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data:{
            type : type,
            name : name, 
            cmd : 'get' 
        } 
    }).done( function(html) {
        console.log(html);
        var x = $.parseJSON(html);
        setvalbyid('save_category', '');
        update_search_names(name);
        if (x.error == 0) {
            if (x.count > 0) {

            clear_form("searchform");
            clear_form("sidebar_contents");
            var sc = x.options;
            var cat;
            clear_all_checkboxes(null); 
            uncheck_all(null);
            $.each(sc, function(key,val) {
                if (key == 'minsetsize')      { setvalbyid('minsetsize', val); }
                else if (key == 'maxsetsize') { setvalbyid('maxsetsize', val); }
                else if (key == 'maxage')     { setvalbyid('maxage', val); }
                else if (key == 'minage')     { setvalbyid('minage', val); }
                else if (key == 'category')   { setvalbyid('save_category', val); }
                else if (key == 'poster')     { setvalbyid('poster', val); }
                else if (key == 'flag')       { setvalbyid('flag', val); }
                else if (key == 'cat')        { cat = val; set_checkbox('checkbox_cat_' + val, 1);}
                else if (key == 'search')     { setvalbyid('search', val);}
                else if (key == 'subcats') {
                        $.each(val, function (s_key, s_val) { 
                            var sc1 = s_key[0];
                            var sc2 = s_key.substr(1);
                            set_checkbox('subcat_' + cat + '_' + sc1 + '_' + sc2, s_val);
                        });
                    }
                });
            load_sets( {'offset':'0', 'setid':'' });
            } else  {
                clear_form("searchform");
                load_sets({'offset':'0', 'setid':''});
            }
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function get_selected_cat()
{
    for (var i=0; i<4; i++) {
        if ($('#checkbox_cat_' + i).val() == 1) {
            return i;
        }
    }
    return '';
}

function load_spots(options)
{ 
    var search = get_value_from_id('search','');
    var minsetsize = get_value_from_id('minsetsize','');
    var maxsetsize = get_value_from_id('maxsetsize','');
    var minage = get_value_from_id('minage','');
    var maxage = get_value_from_id('maxage','');
    var minrating = get_value_from_id('minrating','');
    var maxrating = get_value_from_id('maxrating','');
    var offset = get_value_from_id('offset','');
    var spotid = get_value_from_id('spotid','');
    var poster = get_value_from_id('poster','');
    var order = get_value_from_id('searchorder','');
    var cat_id = get_selected_cat();
    var data = get_subcats_from_form('searchform');
    var flag = $('#flag>option:selected').val();
    var per_page = $('#perpage').val();
    var add_rows = 0;
    if (options !== undefined) {
        if (options.add_rows !== undefined) {
            add_rows = 1;
            data.only_rows = 1;
            data.perpage = per_page;
            offset = parseInt( $('#last_line').val());
            if (!$.isNumeric(offset)) { offset = 0; }
            $('#last_line').val(offset + parseInt(per_page));
        }
        if (options.minsetsize !== undefined) {
            minsetsize = options.minsetsize;
        }
        if (options.maxsetsize !== undefined) {
            maxsetsize = options.maxsetsize;
        }
        if (options.minage !== undefined) {
            minage = options.minage;
        }
        if (options.maxage !== undefined) {
            maxage = options.maxage;
        }
        if (options.minrating !== undefined) {
            minrating = options.minrating;
        }
        if (options.maxrating !== undefined) {
            maxrating = options.maxrating;
        }
        if (options.order !== undefined) {
            order = options.order;
        }
        if (options.flag !== undefined) {
            flag = options.flag;
        }
        if (options.offset !== undefined) {
            offset = options.offset;
        }
        if (options.spot_cat !== undefined) {
            //spot categories
            cat_id = options.spot_cat;
        }
        if (options.setid !== undefined) {
            spotid = options.setid;
        }
        if (options.next !== undefined) {
            cat_id = options.next;
        }
        if (options.category !== undefined) {
            // user defined categories
            $('#save_category').val(options.category);
        }
        if (options.search !== undefined) {
            search = options.search;
            $('#search').val(search);
        }
        if (options.poster !== undefined) {
            poster = options.poster;
            $('#poster').val(poster);
        }
        if (options.subcat !== undefined) {
            data [ options.subcat ] = "1";
        }
    }
    if (add_rows == 0) {
        $('#waitingdiv').removeClass('hidden');
        $('#setsdiv').addClass('hidden');
    }
    var url = "ajax_spots.php";
    data.search = search;
    data.minsetsize = minsetsize;
    data.maxsetsize = maxsetsize;
    data.minrating = minrating;
    data.maxrating = maxrating;
    data.minage = minage;
    data.maxage = maxage;
    data.poster = poster;
    data.categoryID =cat_id;
    data.offset = offset;
    data.spotid = spotid;
    data.flag = flag;
    data.order = order;
    hide_overlayed_content();
    console.log(data);
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data 
    }).done( function(html) {
        console.log(html);
        var x = $.parseJSON(html);
        $('#minage').val(x.minage);        
        $('#maxage').val(x.maxage);        
        $('#minrating').val(x.minrating);        
        $('#maxrating').val(x.maxrating);        
        $('#minsetsize').val(x.minsetsize);        
        $('#maxsetsize').val(x.maxsetsize);        
        $('#flag').val(x.flag);        
        $('#poster').val(x.poster);        
        init_spot_sliders();
        if (add_rows == 0) {
            $('#waitingdiv').addClass('hidden');
            $('#setsdiv').removeClass('hidden');
            show_content_div_2(x.content, 'setsdiv');
            set_checkbox('checkbox_cat_' + cat_id, 1); 
            uncheck_all(cat_id);
            update_rss_url();
            update_widths("browsesubjecttd");
        } else {
            if (x.error == 0) {
                $('#spots_table>tbody tr').eq(-2).after(x.content);
                update_widths("browsesubjecttd");
            }
        }
    });
}

function load_groupsets(options)
{
    var data = {};
    var search = get_value_from_id('search','');
    var minsetsize = get_value_from_id('minsetsize','');
    var maxsetsize = get_value_from_id('maxsetsize','');
    var minrating = get_value_from_id('minrating','');
    var maxrating = get_value_from_id('maxrating','');
    var minage = get_value_from_id('minage','');
    var maxage = get_value_from_id('maxage','');
    var mincomplete = get_value_from_id('mincomplete','');
    var maxcomplete = get_value_from_id('maxcomplete','');
    var offset = get_value_from_id('offset','');
    var setid = get_value_from_id('setid','');
    var order = get_value_from_id('searchorder','');
    var flag = $('#flag>option:selected').val();
    var group_id = $('#select_groupid>option:selected').val();
    var per_page = $('#perpage').val();
    var add_rows = 0;
    if (options !== undefined) {
        if (options.add_rows !== undefined) {
            add_rows = 1;
            data.only_rows = 1;
            data.perpage = per_page;
            offset = parseInt( $('#last_line').val());
            if (!$.isNumeric(offset)) { offset = 0; }
            $('#last_line').val(offset + parseInt(per_page));
        }
        if (options.minsetsize !== undefined) {
            minsetsize = options.minsetsize;
        }
        if (options.maxsetsize !== undefined) {
            maxsetsize = options.maxsetsize;
        }
        if (options.mincomplete !== undefined) {
            mincomplete = options.mincomplete;
        }
        if (options.maxcomplete !== undefined) {
            maxcomplete = options.maxcomplete;
        }
        if (options.minage !== undefined) {
            minage = options.minage;
        }
        if (options.maxage !== undefined) {
            maxage = options.maxage;
        }
        if (options.minrating !== undefined) {
            minrating = options.minrating;
        }
        if (options.maxrating !== undefined) {
            maxrating = options.maxrating;
        }
        if (options.order !== undefined) {
            order = options.order;
        }
        if (options.flag !== undefined) {
            flag = options.flag;
        }
        if (options.group_id !== undefined) {
            group_id = options.group_id;
        }
        if (options.offset !== undefined) {
            offset = options.offset;
        }
        if (options.setid !== undefined) {
            setid = options.setid;
        }
        if (options.next !== undefined) {
            group_id = options.next;
        }
        if (options.category !== undefined) {
            $('#save_category').val( options.category);
        }
        if (options.order !== undefined) {
            order = options.order;
        }
    }

    if (add_rows == 0) {
        $('#waitingdiv').removeClass('hidden');
        $('#setsdiv').addClass('hidden');
    }
    var url = "ajax_browse.php";

    data.search = search;
    data.minsetsize = minsetsize;
    data.maxsetsize = maxsetsize;
    data.minrating = minrating;
    data.maxrating = maxrating;
    data.minage = minage;
    data.maxage = maxage;
    data.mincomplete = mincomplete;
    data.maxcomplete = maxcomplete;
    data.groupID = group_id;
    data.offset = offset;
    data.setid = setid;
    data.flag = flag;
    data.order = order;
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done(function(html) {
        var x = $.parseJSON(html);
        $('#minage').val(x.minage);        
        $('#maxage').val(x.maxage);        
        $('#minrating').val(x.minrating);        
        $('#maxrating').val(x.maxrating);        
        $('#minsetsize').val(x.minsetsize);        
        $('#maxsetsize').val(x.maxsetsize);        
        $('#flag').val(x.flag);        
        init_browse_sliders();
        if (add_rows == 0) {
            $('#waitingdiv').addClass('hidden');
            $('#setsdiv').removeClass('hidden');
            show_content_div_2(x.content, 'setsdiv');
            $('#group_id').val(group_id);
            update_rss_url();
            setvalbyid('select_groupid', group_id);
            update_widths("browsesubjecttd");
        } else {
            if (x.error == 0) {
                $('#sets_table>tbody tr').eq(-2).after(x.content);
                update_widths("browsesubjecttd");
            }
        }
    });
}

function change_sort_order(val, default_sort)
{
    if (default_sort === undefined) {
        default_sort = 'asc';
    } else {
        default_sort = ($.trim(default_sort) == 'asc') ? 'asc' : 'desc';
    } 
    var orderval = '';
    var _oldval = $.trim($('#searchorder').val().toLowerCase());
    var oldval = _oldval.substr(0, _oldval.indexOf(' '));
    var olddir = $.trim(_oldval.substr(_oldval.indexOf(' ')));
    val = $.trim(val);
    if (oldval == val) {
        if (olddir == 'asc') {
            orderval = val + ' desc';
        } else {
            orderval = val + ' asc';
        }
    } else {
        orderval = val + ' ' + default_sort;
    }
    $('#searchorder').val(orderval);
    load_sets( { 'order': orderval } );
}

function set_offset(offset)
{
    load_sets( {'offset' : offset } );
}

function select_next_search(selector, cnt)
{ 
    var idx = $('#' + selector).prop("selectedIndex") + cnt;
    var max = $('#' + selector + '> option').length;
    if (idx >= 0 && idx < max) {
        var type = get_value_from_id('type');
        var val = $('#' + selector + ' option').eq(idx).val();
        if (type == 'groups') {
            update_browse_searches(val);
        } else if (type == 'spots') {
            update_spot_searches(val);
        } else {
            update_browse_searches(val);
        }
    }
}

function select_next(selector, cnt)
{
    var idx = $('#' + selector).prop("selectedIndex") + cnt;
    var max = $('#' + selector + '> option').length;
    if (idx >= 0 && idx < max) {
        $('#' + selector).prop("selectedIndex", idx);
        load_sets({'next': $('#' + selector + '>option:selected').val(), 'offset' : 0});
    } 
}

function load_rsssets(options)
{
    var data = {};
    var search = get_value_from_id('search', '');
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
    var setid = get_value_from_id('setid', '');
    var per_page = $('#perpage').val();
    var add_rows = 0;
    var flag = $('#flag>option:selected').val(); 
    var feed_id = $('#select_feedid>option:selected').val();

    if (options !== undefined) {
        if (options.add_rows !== undefined) {
            add_rows = 1;
            data.only_rows = 1;
            data.perpage = per_page;
            offset = parseInt( $('#last_line').val());
            if (!$.isNumeric(offset)) { offset = 0; }
            $('#last_line').val(offset + parseInt(per_page));
        }
        if (options.minsetsize !== undefined) {
            minsetsize = options.minsetsize;
        }
        if (options.maxsetsize !== undefined) {
            maxsetsize = options.maxsetsize;
        }
        if (options.minage !== undefined) {
            minage = options.minage;
        }
        if (options.maxage !== undefined) {
            maxage = options.maxage;
        }
        if (options.minrating !== undefined) {
            minrating = options.minrating;
        }
        if (options.maxrating !== undefined) {
            maxrating = options.maxrating;
        }
        if (options.order !== undefined) {
            order = options.order;
        }
        if (options.flag !== undefined) {
            flag = options.flag;
        }
        if (options.feed_id !== undefined) {
            feed_id = options.feed_id;
        }
        if (options.offset !== undefined) {
            offset = options.offset;
        }
        if (options.setid !== undefined) {
            setid = options.setid;
        }
        if (options.next !== undefined) {
            feed_id = options.next;
        }
        if (options.category !== undefined) {
            $('#save_category').val(options.category);
        }
    }

    if (add_rows == 0) {
        $('#waitingdiv').removeClass('hidden');
        $('#setsdiv').addClass('hidden');
    }
    var url = "ajax_rsssets.php";
    data.search = search;
    data.minsetsize = minsetsize;
    data.maxsetsize = maxsetsize;
    data.minrating = minrating;
    data.maxrating = maxrating;
    data.minage = minage;
    data.maxage = maxage;
    data.mincomplete = mincomplete;
    data.maxcomplete = maxcomplete;
    data.feed_id = feed_id;
    data.offset = offset;
    data.order = order;
    data.setid = setid;
    data.flag = flag;
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done(function(html) {
        var x = $.parseJSON(html);
        $('#minage').val(x.minage);        
        $('#maxage').val(x.maxage);        
        $('#minrating').val(x.minrating);        
        $('#maxrating').val(x.maxrating);        
        $('#minsetsize').val(x.minsetsize);        
        $('#maxsetsize').val(x.maxsetsize);        
        $('#flag').val(x.flag);
        init_rss_sliders();
        if (add_rows == 0) {
            show_content_div_2(x.content, 'setsdiv');
            $('#feed_id').val(feed_id);
            $('#waitingdiv').addClass('hidden');
            $('#setsdiv').removeClass('hidden');
            update_rss_url();
            setvalbyid('select_feedid', feed_id);
            update_widths("browsesubjecttd");
        } else {
            if (x.error == 0) {
                $('#sets_table>tbody tr').eq(-2).after(x.content);
                update_widths("browsesubjecttd");
            }
        }
    });
}

function update_rss_url()
{
    var rss_url = get_value_from_id('rss_url', '');
    $('#rss_id').attr('href', rss_url);
}

function update_widths(the_id)
{
    var oritextwidth = $('#' + the_id).outerWidth();
    // First set all elements to the CURRENT width, this increases the TD size because of padding:
    $('div[class~="donotoverflowdamnit"]').each(function() { $(this).width(oritextwidth + 'px'); });

    // Can determine the padding by comparing new size with original size:
    var newtextwidth = $('#' + the_id).outerWidth();
    var padding = newtextwidth - oritextwidth;
    var correctedtextwidth = oritextwidth - padding;
    if (padding > 50) { return; } // dirty quick fix....
    // Set it to the correct size, minus the padding that will be auto-added:
    $('div[class~="donotoverflowdamnit"]').each(function() { $(this).width(correctedtextwidth + 'px'); });
}

function wordwrap(msg)
{
    msg = msg.replace("_", "<wbr/>_");
    msg = msg.replace("-", "<wbr/>-");
    msg = msg.replace(".", "<wbr/>.");
    if (msg.length > 25 && msg.search("<wbr/>") < 0) {
        msg = insert_at_every(msg, "<wbr/>", 25);
    }
    return msg;
}

function show_alert(msg)
{
    var url = 'ajax_alert.php';
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data:{ msg : msg } 
    }).done( function(html) {
        show_overlayed_content_1(html, 'alertdiv');
        
        $('#okbutton').click( function() {
            hide_overlayed_content();
        });

        var cancelbutton = $('#cancelbutton');
        if (cancelbutton !== undefined) {
            hide_overlayed_content();
        }
    });
}

function insert_at_every(str, ins, pos)
{
    var l = str.length,
        i = 0,
        res = '';

    while((i + pos) < l) {
        res = res + str.substr(i, pos) + ins;
        i += pos;
    }
    res = res + str.substr(i); 
    return res;
}

function show_confirm(msg, fn)
{
    var url = 'ajax_alert.php';
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data:{ msg : msg, allow_cancel : 1 } 
    }).done( function(html) {
        show_overlayed_content_1(html, 'alertdiv');
        $('#cancelbutton').click (function() { hide_overlayed_content(); });
        $('#okbutton').click( function() {
            hide_overlayed_content();
            fn(); // set what we should run on ok button press
        });
    });
}

function confirm_reset(msg, form)
{
    return show_confirm(msg, function() {
        return form_submit(form, 'reset');
    });
}

function form_submit(form, submittype)
{
    $('#submittype').val(submittype);
    return form.submit();
}

function load_groups(options)
{
    var url = "ajax_groups.php";
    var search = get_value_from_id('newsearch','');
    var order = get_value_from_id('order','');
    var order_dir = get_value_from_id('order_dir','');
    var page = get_value_from_id('page','');
    var page_tab = get_value_from_id('page_tab','');
    var cmd = 'show';
    var searchall = get_value_from_id('search_all','');

    $('#waitingdiv').removeClass('hidden');
    $('#groupsdiv').addClass('hidden');
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
    var data = {
        search:search,
       cmd:cmd,
       order:order,
       order_dir:order_dir,
       offset:page,
       search_all:searchall 
    };
    if (page_tab != '') {
        data.page_tab = page_tab;
    }

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        show_content_div_2(html, 'groupsdiv');

        var urddonline = get_value_from_id('urddonline',0);
        $('#waitingdiv').addClass('hidden');
        $('#groupsdiv').removeClass('hidden');
        if (urddonline != 1) {
            $('#ng_apply').addClass('hidden');
        } else {
            $('#ng_apply').removeClass('hidden');
        }
        update_search_bar_height();
    });
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

    $('#waitingdiv').removeClass('hidden');
    $('#rss_feeds_div').addClass('hidden');
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
    var data = {
        search:search,
        cmd:cmd,
        order:order,
        order_dir:order_dir,
        offset:page,
        search_all:search_all
    };
    if (page_tab != '') {
        data.page_tab = page_tab;
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        show_content_div_2(html, 'rss_feeds_div');
        var urddonline = get_value_from_id('urddonline',0);
        $('#waitingdiv').addClass('hidden');
        $('#rss_feeds_div').removeClass('hidden');
        if (urddonline != 1) {
            $('#rss_apply').addClass('hidden');
            $('#rss_new').addClass('hidden');
        } else {
            $('#rss_apply').removeClass('hidden');
            $('#rss_new').removeClass('hidden');
        }
        update_search_bar_height();
    });
}

function rss_feeds_page(page_offset)
{
    load_rss_feeds({page: page_offset});
}

function group_page(page_offset)
{
    load_groups({page: page_offset});
}


function show_contents(file, idx)
{
    var url = 'ajax_get_textfile.php';
    var challenge = get_value_from_id('challenge', '');
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
        file: file,
        idx: idx,
        challenge: challenge 
    }
    }).done( function(html) {
        show_overlayed_content_1(html, 'popup700x400');
        var width = Math.floor($(window).width() * 0.9);
        var height = Math.floor($(window).height() * 0.9);
        $('#overlay_content').css({
            width: width,
            height: height,
            marginTop: (-Math.floor(height / 2)),
            marginLeft: (- Math.floor(width / 2)),
            'top': '50%',
            'left': '50%'
        });
        var title_height = $('#text_title').height() + 14;
        $('#inner_content').css('height', height - title_height);
    });
}

function show_image(file, idx)
{
    var url = 'ajax_get_image.php';
    var challenge = get_value_from_id('challenge', '');
    var preview = get_value_from_id('preview', '');
    var width = Math.floor($(window).width() * 0.9);
    var height = Math.floor($(window).height() * 0.9);

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
            file: file,
            preview: preview,
            idx: idx,
            width : String(width-110),
            height: String(height-110),
            challenge: challenge 
        }
    }).done( function(html) {
        if (preview != 0) { 
            $('#textcontent').html(html);
        } else {
            show_overlayed_content_1(html, 'popup700x400');
            var width = $('#overlay_image').width()+110;
            var height = $('#overlay_image').height()+100;
            $('#overlay_content').css( {
                width: width,
                height: height,
                marginTop: (-Math.floor(height / 2)),
                marginLeft: (- Math.floor(width / 2)),
                'top': '50%',
                'left': '50%'
            });
        }
    });
}

function config_export()
{
    var url = 'ajax_admin_config.php';
    var params = "cmd=" + encodeURIComponent('export_settings');

    var elemIF = document.createElement("iframe");
    elemIF.src = url + '?' + params;
    elemIF.style.display = "none";
    document.body.appendChild(elemIF);
}

function user_settings_export()
{
    var url = 'ajax_prefs.php';
    var params = "cmd=" + encodeURIComponent('export_settings');

    var elemIF = document.createElement("iframe");
    elemIF.src = url + '?' + params;
    elemIF.style.display = "none";
    document.body.appendChild(elemIF);
}

function rss_feeds_export()
{
    var url = 'ajax_rss_feeds.php';
    var params = "cmd=" + encodeURIComponent('export');

    var elemIF = document.createElement("iframe");
    elemIF.src = url + '?' + params;
    elemIF.style.display = "none";
    document.body.appendChild(elemIF);
}

function group_export()
{
    var url = 'ajax_groups.php';
    var params = "cmd=" + encodeURIComponent('export');

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


function fold_details(button_id, divid)
{
    fold_adv_search(button_id, divid);
    $.ajax({
        url: 'ajax_update_session.php',
        type: 'post',
        cache: false,
        data: { type : 'control' }
    });
}
function submit_enter2 (e, id)
{
    if (e.which == 13) {
        $('#' + id).click();
        e.stopPropagation();
        return false;
    } else {
        return true;
    }
}


function submit_enter(e, fn, vars)
{
    if (e.which == 13) {
        fn(vars);
        e.stopPropagation();
        return false;
    } else {
        return true;
    }
}

function set_mouse_click()
{
    var d = new Date();
    mouse_click_time = d.getTime();
}

function start_quickmenu(str, sid, type, e)
{
    setTimeout(
        function () {
            show_quickmenu(str, sid, type, e);
        }, 200);
}

function do_select_subcat()
{
    var cat = $('#select_catid>option:selected').val();

    if ($.isNumeric(parseInt(cat, 10))) {
        $('#subcatbutton').removeClass('invisible');
    } else {
        $('#subcatbutton').addClass('invisible');
    }
}

function show_subcat_selector()
{
    var cat = $('#select_catid>option:selected').val();
    var subcat = $('#subcat_selector_'+cat);
    close_browse_divs();

    $('div').each(function () {
        var id = $(this).attr('id');
        if (id !== undefined && id.substr(0,16) == 'subcat_selector_') {
            $(this).hide();
        }
    });

    if (cat != '' && subcat !== undefined) {
        subcat.css('zIndex', 1000001);
        subcat.wrap('<div id="overlay_back3"/>');
        $('#overlay_back3').css('zIndex', 1000000);
        subcat.click(function(e) { e.stopPropagation(); });
        $('#overlay_back3').click(function(e) { close_subcat_selector(); });
        $('#overlay_back3').show();
        subcat.show();
        subcat.removeClass('hidden');
    }
}

function close_subcat_selector()
{
    $('div').each(function () {
        var id = $(this).attr('id');
        if (id !== undefined && id.substr(0,16) == 'subcat_selector_') {
            $(this).hide();
            if ($(this).parent().is("div") && $(this).parent().attr('id') == 'overlay_back3') {
                $(this).unwrap();
            }
        }
    });
    $('#overlay_back2').hide();
}

var leftmenumargin = 0;
function scroll_menu(e, dir)
{
    if (!e) {
        e = window.event;
    }
    if (e.shiftKey) {
        leftmenumargin = 0;
    } else {
        var step = 110;
        leftmenumargin = leftmenumargin + (dir * step);
    }
    $('#pulldown').css('marginLeft', leftmenumargin + 'px');
}

function scroll_menu_left(e) { scroll_menu(e, -1); }
function scroll_menu_right(e) { scroll_menu(e, 1); }

function basename(path) 
{
    return path.replace(/\\/g,'/').replace( /.*\//, '');
}

function is_letter(c)
{
    return ((c >= 'a' && c <= 'z') || (c >= 'A' && c <= 'Z'));
}

function is_num(c)
{
    return (c >= '0' && c <= '9');
}

function fix_chars(str)
{
    var last = '.';
    var rstr = '';
    for (var i = 0; i < str.length; i++) {
        if (!is_letter(last) && !is_num(last)) {
            if (str[i] != last) {
                rstr += str[i].toUpperCase();
            }
        } else {
            rstr += str[i];
        }
        last = str[i];
    }
    return rstr;
}

function update_setname(id)
{
    var newsetname = get_value_from_id(id, '');
    var tmpsetname = basename(newsetname);
    if (tmpsetname.lastIndexOf('.') > 0) {
        tmpsetname = tmpsetname.substr(0, tmpsetname.lastIndexOf('.'));
    }
    tmpsetname = fix_chars(tmpsetname);
    $('#setname').val(tmpsetname);
}

function add_whitelist(id, type)
{
    var url = 'ajax_action.php';
    var data = { cmd: 'add_whitelist' };
    var challenge = get_value_from_id('challenge', '');
    if (type == 'spotterid') {
        data.spotterid = id;
    } else {
        data.spotid = id;
    }
    data.challenge = challenge;
    var confirmmsg = get_value_from_id('whitelist_confirm_msg', 'Add spotter to whitelist?');
    show_confirm(confirmmsg, function() { 
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: data
        }).done( function(html) {
            if (html != 'OK') {
                set_message('message_bar', html, 5000);
            } else {
                set_message('message_bar', html, 5000);
            }
        });
    });
}

function add_poster_blacklist(id)
{
    var url = 'ajax_action.php';
    var challenge = get_value_from_id('challenge', '');
    var data = { 
        cmd:'add_poster_blacklist',
        challenge : challenge,
        setid: id 
    };
    var confirmmsg = get_value_from_id('blacklist_confirm_msg', 'Add poster to blacklist?');
    show_confirm(confirmmsg, function() { 
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: data
        }).done( function(html) {
            if (html != 'OK') {
            set_message('message_bar', html, 5000);
            } else {
            set_message('message_bar', html, 5000);
            }
        });
    });
}

function add_blacklist(id, type, global)
{
    var url = 'ajax_action.php';
    var challenge = get_value_from_id('challenge', '');
    var data = { cmd: 'add_blacklist' };
    if (type == 'spotterid') {
        data.spotterid = id;
    } else {
        data.spotid = id;
    }
    if (global !== undefined && global == 'global') {
        data.global = global;
    }
    data.challenge = challenge;
    var confirmmsg = get_value_from_id('blacklist_confirm_msg', 'Add spotter to blacklist?');
    show_confirm(confirmmsg, function() { 
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: data
        }).done( function(html) {
            if (html != 'OK') {
                set_message('message_bar', html, 5000);
            } else {
                set_message('message_bar', html, 5000);
            }
        });
    });
}

function report_spam(spotid)
{
    show_post_message('report', spotid);
}

function post_spot_comment(spotid)
{
    show_post_message('comment', spotid);
}

function select_dir(dir_select, dl_dir)
{
    var dldir = $('#' + dl_dir);
    var dirselect = $('#' + dir_select);
    $('#dir_select_span').toggleClass('hidden');
    $('#dl_dir_span').toggleClass('hidden');
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
    $('#overlay').addClass("hidden");
    if (closelink == 'back') {
        history.go(-1);
    } else if (closelink == 'close') {
        window.close();
    }
}

function toggle_textarea(ta_id, checkboxid)
{
    $('#' + ta_id).toggleClass('hidden'); 
}

function start_updatedb()
{
    $.ajax({
        type: 'post',
        url: 'ajax_update_db.php',
        cache: false,
        data: { }
    } ).done( function(html) {
        $('#updatedbdiv').html(html);
    });
}

function init_slider(minv, maxv, slidediv, minbox, maxbox)
{
    var minb = minv;
    var maxb = maxv;
    if ($.isNumeric($(minbox).val())) { minb = $(minbox).val(); }
    if ($.isNumeric($(maxbox).val())) { maxb = $(maxbox).val(); }
    // we set the default here -- if the value == "" it is set to 0
    $(maxbox).val(maxb);
    $(minbox).val(minb);
    $(function() {
        $(slidediv).slider( {
            range: true,
            min: parseInt(minv),
            max: parseInt(maxv),
            values: [ parseInt(minb), parseInt(maxb) ],
            slide: function(event, ui) { 
                $(minbox).val( ui.values[0] );
                $(maxbox).val( ui.values[1] );
            }
        });
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
            $(new_img).attr({
                width : maxWidth,
                height : (height * ratio)
                });
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
        $('#overlay_content2').css( {
            width: width,
            height: height,
            marginTop: (- Math.floor(height / 2)),
            marginLeft: (- Math.floor(width / 2)),
            'top': '50%',
            'left': '50%'
        });
        $('#overlay_wrap2').css({
            width : (width + 20),
            height: (height + 20),
            marginTop: (- Math.floor((height + 20) / 2)),
            marginLeft: (- Math.floor((width + 20) / 2)),
            'top': '50%',
            'left': '50%'
        });
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
        var divTotalHeight = $(id).get(0).scrollHeight + 
            parseInt($(id).css('padding-top'), 10) + 
            parseInt($(id).css('padding-bottom'), 10);

        if ((scrollPosition + 1) >= divTotalHeight) {
            fn( { 'add_rows':'1' } );
        }
    });
}

function delete_setting(name)
{
    var url = '';
    var source = $('#source').val();
    if (source == 'prefs') { 
        url = 'ajax_prefs.php';
    } else if (source == 'config') {
        url = 'ajax_admin_config.php';
    }
    var challenge = get_value_from_id('challenge', '');

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
        cmd : 'delete',
        challenge : challenge,
        option: name }
        }).done( function(html) {
            console.log(html);
            var x = $.parseJSON(html);

            if (x.error == 0) {
                set_message('message_bar', x.message, 5000);
            } else {
                set_message('message_bar', x.error, 5000);
            }
            load_prefs();
    });
}

var update_setting_timeout = null;
var update_id = null;

function update_setting(id, type, optionals)
{
    var source = $('#source').val();
    var challenge = get_value_from_id('challenge', '');
    var url = '';
    var data = { cmd : 'set' };
    if (source == 'prefs' || option == 'pref_level') { 
        url = 'ajax_prefs.php';
    } else if (source == 'config') {
        url = 'ajax_admin_config.php';
    }
    var timeout = 0;
    var option, value;

    if (type == 'select') {
        option = $('#' + id).attr('name');
        value = $('#' + id + '>option:selected').val();
    } else if (type == 'period') {
        if (update_setting_timeout !== null && update_id == id) {
            clearTimeout(update_setting_timeout);
            update_id = null;
            update_setting_timeout = null;
        }
        option = $('#' + id ).attr('name');
        value = $('#' + id + ' :selected').val();
        data.time1 = $('#' + optionals.time1).val();
        data.time2 = $('#' + optionals.time2).val();

        if (optionals.extra != null) {
            data.extra = $('#' + optionals.extra).val();
        }
        timeout = 1200;
    } else if (type == 'custom_text') {
        option = $('#' + 'custom_' + id + '_name').val();
        if (option == '') { return; }
        value = $('#' + 'custom_' + id + '_value').val();
        if (optionals.source == 'name' && value == '') { return; }
        var orig_name = optionals.original_name;
        data.original_name = orig_name;

    } else if (type == 'multiselect') {
        // cleartimeout
        if (update_setting_timeout !== null && update_id == id) {
            clearTimeout(update_setting_timeout);
            update_setting_timeout = null;
            update_id = null;
        }
        option = $('#' + id).attr('name');
        value = $('#' + id).val().join(':');
        timeout = 800;
    } else {
        option = $('#' + id).attr('name');
        value = $('#' + id).val();
    }
    data.challenge = challenge;
    data.option = option;
    data.value = value;
    data.type = type;
    var send_data = function() {
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: data
        }).done( function(html) {
            console.log(html);
            var x = $.parseJSON(html);
            if (x.error == 0) {
                $('#stop_mark_' + id).hide();
                set_message('message_bar', x.message, 5000);
            } else  {
                set_message('message_bar', x.error, 5000);
            }
            if (optionals != null && optionals.fn != null) {
                eval(optionals.fn);
            }
        });
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
        var pw;
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
            pw = $('#' + npw_id1);

            if (weak_pw <= 5) {
                pw.removeClass('passwordmedium passwordstrong');
                pw.addClass('passwordweak');
                $('#pwweak').show();
                $('#pwmedium').hide();
                $('#pwstrong').hide();
            } else if (weak_pw <= 7) {
                pw.removeClass('passwordweak passwordstrong');
                pw.addClass('passwordmedium');
                $('#pwweak').hide();
                $('#pwmedium').show();
                $('#pwstrong').hide();
            } else if (weak_pw > 7) {
                pw.removeClass('passwordweak passwordmedium');
                pw.addClass('passwordstrong');
                $('#pwweak').hide();
                $('#pwmedium').hide();
                $('#pwstrong').show();
            }
        } else if (npw1 == '') {
            pw = $('#' + npw_id1);
            pw.removeClass('passwordstrong passwordweak passwordmedium');
        }
    };

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
        var pw, pw_msg;
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
            pwd.removeClass('passwordincorrect passwordcorrect');
        }

        if (npw1 != '') {
            var weak_pw = check_weak_pw(npw1, username);
            pw = $('#' + npw_id1);
            pw_msg = $('#pw_message_' + npw_id1);

            if (weak_pw <= 5) {
                pw.removeClass('passwordmedium passwordstrong');
                pw.addClass('passwordweak');
                pw_msg.html($('#pwweak').html());
            } else if (weak_pw <= 7) {
                pw.removeClass('passwordweak passwordstrong');
                pw.addClass('passwordmedium');
                pw_msg.html($('#pwmedium').html());
            } else if (weak_pw > 7) {
                pw.removeClass('passwordweak passwordmedium');
                pw.addClass('passwordstrong');
                pw_msg.html($('#pwstrong').html());
            }
        } else if (npw1 == '') {
            pw_msg = $('#pw_message_' + npw_id1);
            pw = $('#' + npw_id1);
            pw.removeClass('passwordstrong passwordweak passwordmedium');
            pw_msg.html("");
        }
    };
    var change_password = function () {
        var npw1 = $('#' + npw_id1).val();
        var npw2 = $('#' + npw_id2).val();
        var opw = $('#' + opw_id).val();
        var challenge = get_value_from_id('challenge', '');

        var url = 'ajax_prefs.php';
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: {
                cmd: 'change_password',
                oldpass: opw,
                newpass1: npw1,
                newpass2: npw2,
                challenge: challenge
        }
        }).done( function(html) {
            console.log(html);
            var x = $.parseJSON(html);
            if (x.error == 0) {
                set_message('message_bar', x.message, 5000);
            } else  {
                set_message('message_bar', x.error, 5000);
            }
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
    var current_tab = $('#current_tab').val();
    if (current_tab === undefined) { 
        current_tab = '';
    }
    if (source == 'prefs') { 
        url = 'ajax_prefs.php';
    } else if (source == 'config') {
        url = 'ajax_admin_config.php';
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
            cmd : 'show',
            current_tab: current_tab
        }
    }).done( function(html) {
            console.log(html);
        var x = $.parseJSON(html);
        show_content_div_2(x.contents, 'settingsdiv');
        update_search_bar_height();
    });
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
    show_confirm(msg, function() {
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: {
                cmd : 'reset',
                challenge: challenge,
            }
        }).done( function(html) {
            console.log(html);
            var x = $.parseJSON(html);
            if (x.error == 0) {
                load_prefs();
                set_message('message_bar', x.message, 5000);
            } else {
                set_message('message_bar', x.error, 5000);
                show_content_div_2(x.contents, 'settingsdiv');
            }
        });
    });
}

function change_stylesheet(id)
{
    var cssdir = $('#cssdir').val();
    var stylesheet= $('#' + id).val();
    stylesheet = cssdir + '/' + stylesheet + '/' + stylesheet + '.css';
    $('#urd_css').attr('href', stylesheet);
} 

function show_logs()
{
    var url = 'ajax_admin_log.php';
    var challenge = get_value_from_id('challenge', '');
    var sort_order = get_value_from_id('order','');
    var sort_dir = get_value_from_id('order_dir','');
    var lines = get_value_from_id('lines','');
    var level = $('#log_level').val();
    var search = $('#search').val();
    var data = {
        foo: sort_order,
        lines: lines,
        log_level: level,
        challenge: challenge,
        search: search,
        sort_order: sort_order,
        sort_dir: sort_dir
    };
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done(function(html) {
        var x = $.parseJSON(html);
        if (x.error == 0) {
            show_content_div_2(x.contents, 'logdiv');
            update_search_bar_height();
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function levenshtein(s1, s2) 
{
    // http://kevin.vanzonneveld.net
    // +   original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
    // +   bugfixed by: Onno Marsman
    // +   revised by: Andrea Giammarchi (http://webreflection.blogspot.com)
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
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
            username: username,
            email: email,
            password1: pass1,
            password2: pass2,
            fullname: fullname,
            register_captcha: captcha,
            submit_button: 1
        }
    }).done( function(html) {
        if (html == "OK") {
            $('#form').hide();
            $('#sent').show();
        } else {
            if (html.substr(0, 7) == ':error:') {
                set_message('message_bar', html.substr(7), 5000);
            }
        }
    });
}

function submit_forgot_password()
{
    var username= $('#username').val();
    var email= $('#email').val();
    var url = 'ajax_forgot_password.php';

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
            username: username,
            email: email
        }
    }).done( function(html) {
        if (html == "OK") {
            $("#sent_table").show();
            $("#form_table").hide();
        } else {
            if (html.substr(0, 7) == ':error:') {
                set_message('message_bar', html.substr(7), 5000);
            }
        }
    });
}

function reload_prefs()
{
    window.location = "prefs.php";
}

function display_timebox(id)
{
    if ($('#' + id + '>option:selected').val() == 0) {
        $('#timebox1').hide();
        $('#timebox2').hide();
    } else {
        $('#timebox1').show();
        $('#timebox2').show();
    }
}

function subscribe_rss(feedid)
{
    var challenge = get_value_from_id('challenge', '');
    var active = $('#rssfeed_' + feedid).val();
    var data;
    if (active == 1) { 
        data = {
            cmd : 'subscribe',
            challenge: challenge,
            feedid: feedid,
            period:$('#period_' + feedid + '>option:selected').val(),
            time1: $('#time1_' + feedid).val(),
            time2: $('#time2_' + feedid).val(),
            adult: $('#adult_' + feedid).val(),
            expire: $('#expire_' + feedid).val(),
        };
    } else {
        data = {
            cmd : 'unsubscribe',
            challenge: challenge,
            feedid: feedid
        };
    }
    var url = 'ajax_rss_feeds.php';
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        update_message_bar(html);
    });
}

function subscribe_ng(groupid)
{
    var challenge = get_value_from_id('challenge', '');
    var active = $('#newsgroup_' + groupid).val();
    var data;
    if (active == 1) { 
        data = {
            cmd : 'subscribe',
            challenge: challenge,
            groupid: groupid,
            period:$('#period_' + groupid + '>option:selected').val(),
            time1: $('#time1_' + groupid).val(),
            time2: $('#time2_' + groupid).val(),
            adult: $('#adult_' + groupid).val(),
            expire: $('#expire_' + groupid).val(),
            admin_minsetsize: $('#minsetsize_' + groupid).val(),
            admin_maxsetsize: $('#maxsetsize_' + groupid).val(),
        };
    } else {
        data = {
            cmd : 'unsubscribe',
            challenge: challenge,
            groupid: groupid
        };
    }
    var url = 'ajax_groups.php';
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        update_message_bar(html);
    });
}

function update_ng_value(type, option, group_id)
{
    var url, data;
    var challenge = get_value_from_id('challenge', '');
    var cmd = 'set_value';
    if (option == 'expire') {
        cmd = 'set_plain_value';
    }
    if (type == 'groups') {
        url = 'ajax_groups.php';
        data = {
            cmd: cmd,
            group_id : group_id,
            challenge: challenge,
            option : option,
            value: $('#' + option + '_' + group_id).val()
        };
    } else {
        url = 'ajax_rss_feeds.php';
        data = {
             cmd: cmd,
             feed_id : group_id,
             option : option,
             challenge: challenge,
             value: $('#' + option + '_' + group_id).val()
        };
    }

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        update_message_bar(html);
        });
    }

function toggle_visibility(type, group_id)
{
    var url, data;
    var challenge = get_value_from_id('challenge', '');
    if (type == 'groups') {
        url = 'ajax_groups.php';
        data = {
             cmd: 'toggle_visibility',
             group_id : group_id,
             challenge: challenge,
             visibility: $('#visible_' + group_id).val()
        };
    } else {
        url = 'ajax_rss_feeds.php';
        data = {
            cmd: 'toggle_visibility',
            feed_id : group_id,
            challenge: challenge,
            visibility: $('#visible_' + group_id).val()
        };
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done(function(html) {
        update_message_bar(html);
    });
}

function update_user_ng_value(type, option, group_id)
{
    var url, data;
    var challenge = get_value_from_id('challenge', '');
    if (type == 'groups') {
        url = 'ajax_groups.php';
        data = {
             cmd: 'set_user_value',
             group_id : group_id,
             option : option,
             challenge: challenge,
             value: $('#' + option + '_' + group_id).val()
        };
    } else {
        url = 'ajax_rss_feeds.php';
        data = {
            cmd: 'set_user_value',
            feed_id : group_id,
            option : option,
            challenge: challenge,
            value: $('#' + option + '_' + group_id).val()
        };
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: data
    }).done( function(html) {
        update_message_bar(html);
    });
}

function update_category(type, group_id)
{
    var url, data;
    var challenge = get_value_from_id('challenge', '');
    if (type == 'groups') {
        url = 'ajax_groups.php';
        data = { 
            cmd: 'set_plain_user_value',
            group_id : group_id,
            option : 'category',
            challenge: challenge,
            value: $('#category_' + group_id + '>option:selected').val()
        };
    } else {
        url = 'ajax_rss_feeds.php';
        data = {
            cmd: 'set_plain_user_value',
            feed_id : group_id,
            option : 'category',
            challenge: challenge,
            value: $('#category_' + group_id + '>option:selected').val()
        };
    }
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data:  data
    }).done( function(html) {
        update_message_bar(html);
    });
}

var update_ng_setting_timeout = null;
var update_ng_id = null;

function update_ng_time(type,  group_id)
{
    var url, data;
    var challenge = get_value_from_id('challenge', '');
    if (type == 'groups') {
        url = 'ajax_groups.php';
        data = { 
             cmd: 'set_update_time',
             group_id : group_id,
             challenge: challenge,
             time1: $('#time1_' + group_id).val(),
             time2: $('#time2_' + group_id).val(),
             period: $('#period_'+ group_id + '>option:selected').val()
        };
    } else {
        url = 'ajax_rss_feeds.php';
        data = {
            cmd: 'set_update_time',
            feed_id : group_id,
            challenge: challenge,
            time1: $('#time1_' + group_id).val(),
            time2: $('#time2_' + group_id).val(),
            period: $('#period_'+ group_id + '>option:selected').val()
        };
    }

    var timeout = 0;
    if (update_ng_setting_timeout != null && update_ng_id == group_id) {
        clearTimeout(update_ng_setting_timeout);
        update_ng_id = null;
        update_ng_setting_timeout = null;
    }
    timeout = 2500;
    var send_data = function() {
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: data
        }).done( function(html) {
            if ($('#period_'+ group_id + '>option:selected').val() == 0) {
                $('#time1_' + group_id).val('');
                $('#time2_' + group_id).val('');
            }
            update_message_bar(html);
        });
    };
    if (timeout > 0) {
        update_ng_id = group_id;
        update_ng_setting_timeout = setTimeout(send_data, timeout);
    } else {
        send_data();
    }
}

function show_post_spot()
{
    var url = "ajax_post_spot.php";

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
            cmd: 'show'
        }
    }).done(function(html) {
        var x = $.parseJSON(html);
        if (x.error == 0) {
            show_overlayed_content_1(x.content, 'popup700x400');
            change_spotsubcats();
            $('#progress_image').hide();
            $('#progress_nzb').hide();
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });

}

function change_spotsubcats()
{
    var url = "ajax_post_spot.php";

    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
            cmd: 'category_info',
            category: $('#category>option:selected').val() 
        }
        }).done(function(html) {
            var x = $.parseJSON(html);
            if (x.error == 0) {
            $('#subcats').html(x.content);
            var height = 500, width = 700;
            $('#overlay_content').css( {
                width: width,
                height: height,
                marginTop: (-Math.floor(height / 2)),
                marginLeft: (- Math.floor(width / 2)),
                'top': '50%',
                'left': '50%'
            });
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function progressHandlingFunction(e, id)
{
    if(e.lengthComputable){
        $(id).attr({value:e.loaded,max:e.total});
    }
}

function upload_file(id, type, post_id, fn)
{
    var fd = new FormData();
    var challenge = get_value_from_id('challenge', '');
    var file = $('#' + id)[0].files[0];
    if (file === undefined) {
        fn(-1);
        return false;
    }
    fd.append('challenge', challenge);
    fd.append('cmd', 'upload_file');
    fd.append('type', type);
    fd.append('post_id', post_id);
    fd.append('upfile', file);
    $('#progress_' + type).show();
    $.ajax({
        url: 'ajax_post_spot.php',  //Server script to process data
        type: 'POST',
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) { // Check if upload property exists
                myXhr.upload.addEventListener('progress', function(e) { progressHandlingFunction(e, '#progress_' + type); }, false); // For handling the progress of the upload
            }
            return myXhr;
        },
        data: fd,
//Options to tell jQuery not to process data or worry about content-type.
        cache: false,
        contentType: false,
        processData: false
    }).done(function(html) {
        var x = $.parseJSON(html);
        if (x.error != 0) {
            set_message('message_bar', x.error, 5000);
            fn(-1);
        } else {
            fn(1);
        }
    });
}

function post_spot()
{
    var url = "ajax_post_spot.php";
    var cat = $('#category>option:selected').val();
    var subject = $('#subject').val();
    var tag = $('#tag').val();
    var weburl = $('#weburl').val();
    var description = $('#description').val();
    var subcats = {};
    $('select[name="subcats_select"] > option:selected').each(function () {
            subcats [ $(this).val() ] = $(this).val();
    });
    // upload nzb
    // upload image
    var nzb = $('#nzbfile').val();
    var img = $('#imagefile').val();
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
            cmd: 'post',
            category: cat,
            subject : subject,
            tag : tag,
            url : weburl,
            description : description,
            subcats: subcats,
            nzb_file : nzb,
            image_file : img
        }
    }).done(function(html) {
        var x = $.parseJSON(html);
    
        if (x.error == 0) {
            var rv1 = 0;
            var rv2 = 0;
            upload_file('nzbfile', 'nzb', x.post_id, function(rv) { rv1 = rv; });
            upload_file('imagefile', 'image', x.post_id, function(rv) { rv2 = rv; });
            var counter = 0;
            var test_f = function() {
                if (rv1 < 0 || rv2 < 0 || counter > 120) {
                    cancel_post(x.post_id);
                } else if (rv1 > 0 && rv2 > 0) {
                    start_post(x.post_id);
                    hide_overlayed_content();
                    if (x.message !== undefined) {
                        set_message('message_bar', x.message, 5000);
                    }
        // close popup which we won't do because we have to fill in the stuff over and over again.
                } else {
                    counter++;
                    setTimeout(test_f, 500);
                }
            };
            setTimeout(test_f, 500);
        } else {
            set_message('message_bar', x.error, 5000);
        }
    });
}

function cancel_post(post_id)
{
    var url = "ajax_post_spot.php";
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
            cmd: 'start_post',
            postid: post_id,
        }
    }).done(function(html) {
        var r = $.parseJSON(html);
        if (r.error != 0) {
            set_message('message_bar', r.error, 5000);
        }
    });
}

function start_post(post_id)
{
    var url = "ajax_post_spot.php";
    $.ajax({
        type: 'post',
        url: url,
        cache: false,
        data: {
            cmd: 'start_post',
            postid: post_id
        }
    }).done(function(html) {
        var r = $.parseJSON(html);
        if (r.error != 0) {
            set_message('message_bar', r.error, 5000);
        }
    });
}

function add_text(text, elem)
{
    elem.val( elem.val() + text);
}

var sidebar = 0;
function show_sidebar(display)
{
    var side_bar_width = 250;

    if (sidebar || display === false) {
        $('#contentleft').outerWidth(0);
        $('#contentleft').css('margin-left', '-10000px');
        $('#topcontent').css('left', 0);
        $('#searchbar').css('left', 0);
        $('#topcontent').width("100%");
        sidebar = 0;
        $('#sidebar_button').text('>>');
    } else {
        $('#contentleft').css('margin-left', '0px');
        $('#contentleft').css('height', Math.round($(window).height() - 22));
        $('#contentleft').outerWidth(side_bar_width);
        $('#topcontent').css('left', side_bar_width);
        $('#searchbar').css('left', side_bar_width);
        $('#topcontent').width($('#topcontent').width() - side_bar_width);
        sidebar = 1;
        $('#sidebar_button').text('<<');
    }
    $('#sbdiv').css('padding-top', (Math.round($(window).height() - 50 - 22) / 2));
    $('#sidebar_button').css('padding-top', 17);
    $('#sidebar_button').innerHeight(50);

    $('#content').css('display', 'none');
    $('#content').css('display', 'block');
    // don't know why we need it ... but... 
    $('div[class~="donotoverflowdamnit"]').each(function() { $(this).width(10 + 'px'); });
    update_widths('browsesubjecttd');
}

function init_browse_sliders()
{
    init_slider(0, $('#maxcompletelimit').val(), "#setcomplete", "#mincomplete", "#maxcomplete");
    init_slider(0, $('#maxsetsizelimit').val(), "#setsize", "#minsetsize", "#maxsetsize");
    init_slider(0, $('#maxagelimit').val(), "#setage", "#minage", "#maxage");
    init_slider(0, $('#maxratinglimit').val(), "#setrating", "#minrating", "#maxrating");
}

function init_spot_sliders()
{
    init_slider(0, $('#maxsetsizelimit').val(), "#setsize", "#minsetsize", "#maxsetsize");
    init_slider(0, $('#maxagelimit').val(), "#setage", "#minage", "#maxage");
    init_slider(0, $('#maxratinglimit').val(), "#setrating", "#minrating", "#maxrating");
}

function init_rss_sliders()
{
    init_slider(0, $('#maxsetsizelimit').val(), "#setsize", "#minsetsize", "#maxsetsize");
    init_slider(0, $('#maxratinglimit').val(), "#setrating", "#minrating", "#maxrating");
    init_slider(0, $('#maxagelimit').val(), "#setage", "#minage", "#maxage");
}

function load_side_bar(fn)
{
    var type = $('#type').val();
    var url;
    $('#sidebar_button').css('display', 'block');
    if (type == 'spots') {
        url = "ajax_load_spot_sidebar.php";
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
        }).done(function(html) {
            var r = $.parseJSON(html);
            if (r.error != 0) {
                set_message('message_bar', r.error, 5000);
            } else {
                $('#left_content').html(r.contents);
                $('#reset_button').click(function() {
                    clear_form("sidebar_contents");
                    clear_form("searchform");
                    init_spot_sliders();
                });
                show_sidebar(false);
                fn();
            }
        });
    } else if (type == 'groups') {
        url = "ajax_load_browse_sidebar.php";
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
        }).done(function(html) {
            var r = $.parseJSON(html);
            if (r.error != 0) {
                set_message('message_bar', r.error, 5000);
            } else {
                $('#left_content').html(r.contents);
                $('#reset_button').click(function() {
                    clear_form("sidebar_contents");
                    clear_form("searchform");
                    init_browse_sliders();
                });
                show_sidebar(false);
                fn();
            }
        });
    } else if (type == 'rss') {
        url = "ajax_load_rss_sidebar.php";
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
        }).done(function(html) {
            var r = $.parseJSON(html);
            if (r.error != 0) {
                set_message('message_bar', r.error, 5000);
            } else {
                $('#left_content').html(r.contents);
                $('#reset_button').click(function() {
                    clear_form("sidebar_contents");
                    clear_form("searchform");
                    init_rss_sliders();
                });
                show_sidebar(false);
                fn();
            }
        });
    }
}

function uncheck_all(cat)
{
    $('input[name^="cat_"]').each (function() {
        if ($(this).attr('id') != "checkbox_cat_" + cat) {
            set_checkbox($(this).attr('id'), 0);
        }
    });
}

function do_command(command, message)
{
    var group_id;
    if (command == 'update_ng') { // we are in a browsepage
        group_id = $('#select_groupid>option:selected').val();
        if (group_id == '') {
            control_action('updatearticles');
        } else {
            ng_action('updategroup', group_id);
        }

    } else if (command == 'expire_ng') {
        group_id = $('#select_groupid>option:selected').val();
        if (group_id == '') {
            control_action('expirearticles');
        } else {
            ng_action('expiregroup', group_id);
        }
    } else if (command == 'purge_ng') {
        group_id = $('#select_groupid>option:selected').val();
        if (group_id == '') {
            control_action_confirm('purgearticles', message + '?');
        } else {
            ng_action_confirm('purgegroup', group_id, message + ' @@?');
        }
    } else if (command == 'gensets_ng') {
        group_id = $('#select_groupid>option:selected').val();
        if (group_id == '') {
            control_action('gensetsarticles');
        } else {
            ng_action('gensetsgroup', group_id);
        }
    } else if (command == 'update_rss') { // we are in a browsepage
        group_id = $('#select_feedid>option:selected').val();
        if (group_id == '') {
            control_action('updaterssall');
        } else {
            ng_action('updaterss', group_id);
        }
    } else if (command == 'expire_rss') {
        group_id = $('#select_feedid>option:selected').val();
        if (group_id == '') {
            control_action('expirerssall');
        } else {
           ng_action('expirerss', group_id);
        }
    } else if (command == 'purge_rss') {
        group_id = $('#select_feedid>option:selected').val();
        if (group_id == '') {
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
    } else if (command == 'import_spots_blacklist') {
        show_popup_remote('ajax_user_blacklist', 'import_settings_blacklist');
    } else if (command == 'import_spots_whitelist') {
        show_popup_remote('ajax_user_blacklist', 'import_settings_whitelist');
    } else if (command == 'export_servers') {
        jump('ajax_edit_usenet_servers.php?cmd=export_settings');
    } else if (command == 'import_groups') {
        show_popup_remote('ajax_groups', 'load_settings');
    } else if (command == 'export_spots_blacklist') {
        jump('ajax_user_blacklist.php?cmd=export_settings&list=black');
    } else if (command == 'export_spots_whitelist') {
        jump('ajax_user_blacklist.php?cmd=export_settings&list=white');
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
        show_popup_remote('ajax_action', 'import_all');
    } else if (command == 'updategroups') {
        control_action('updategroups');
    } else if (command == 'updateblacklist') {
        control_action('updateblacklist');
    } else if (command == 'updatewhitelist') {
        control_action('updatewhitelist');
    } else if (command == 'postmessage') {
        show_post_message();
    } else if (command == 'postcomment') {
        post_spot_comment();
    } else if (command == 'continueall') {
        control_action('continue_all');
    } else if (command == 'pauseall') {
        control_action('pause_all');
    } else if (command == 'getnzb') {
        show_uploadnzb();
    } else if (command == 'post') {
        show_post();
    } else if (command == 'post_spot') {
        show_post_spot();
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

