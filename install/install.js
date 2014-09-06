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
 * $LastChangedDate: 2012-09-09 00:59:41 +0200 (zo, 09 sep 2012) $
 * $Rev: 2660 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: js.js 2660 2012-09-08 22:59:41Z gavinspearhead@gmail.com $
 */

'use strict';

function LoadPage(pagenumber)
{
    var installform = document.forms[0];
    var pagenr = document.getElementById('page');
    pagenr.value = pagenumber;
    installform.submit();
}

function toggle_show_password(id)
{
    var pass = document.getElementById(id);
    if (pass.type == 'password') {
        pass.type = 'text';
    } else {
        pass.type = 'password';
    }
}

function update_database_input_fields()
{
    var dbt = document.getElementById('dbtype');
    var type = dbt.options[dbt.selectedIndex].value;
    type = type.toLowerCase();
    change_display_dbengine(type);
    if (type == 'sqlite') {
        hide_data_for_sqlite();
    } else {
        show_data_for_sqlite();
    }
}

function change_display_dbengine(type)
{
    if (type == 'mysql') {
        $('#dbengine').show();
    } else {
        $('#dbengine').hide();
    }
}

function show_data_for_sqlite()
{
    $('#hostname').show();
    $('#port').show();
    $('#dbusername').show();
    $('#dbpassword').show();
    $('#dbroot').show();
    $('#dbrootpw').show();
    $('#dbmysqlreset').show();
}

function hide_data_for_sqlite()
{
    $('#dbhost').val('');
    $('#dbname').val('');
    $('#dbport').val('');
    $('#dbuser').val('');
    $('#dbpass').val('');
    $('#dbruser').val('');
    $('#dbrpass').val('');
    $('#hostname').hide();
    $('#port').hide();
    $('#dbusername').hide();
    $('#dbpassword').hide();
    $('#dbroot').hide();
    $('#dbrootpw').hide();
    $('#dbmysqlreset').hide();
}

function hide_button(id)
{
    var div = document.getElementById(id);
    div.style.display = 'none';
}

function show_message(msg)
{
    var div = document.getElementById('message');
    div.style.display = 'block';
    div.innerHTML = msg;
}

function GeneratePassword(length)
{
    var sPassword = '', i;

    for (i = 0; i < length; i++) {
        var numI = getRandomNum();
        while (checkPunc(numI)) { numI = getRandomNum(); }
        sPassword = sPassword + String.fromCharCode(numI);
    }
    return sPassword;
}

function getRandomNum() {

    // between 0 - 1
    var rndNum = Math.random();

    // rndNum from 0 - 1000
    rndNum = parseInt(rndNum * 1000);

    // rndNum from 33 - 127
    rndNum = (rndNum % 94) + 33;

    return rndNum;
}

function checkPunc(num) {

    if ((num >= 33) && (num <= 47)) { return true; }
    if ((num >= 58) && (num <= 64)) { return true; }
    if ((num >= 91) && (num <= 96)) { return true; }
    if ((num >= 123) && (num <= 126)) { return true; }

    return false;
}

function set_random_password(dbpass)
{
    $('#' + dbpass).val(GeneratePassword(12));
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

function fill_in_usenet_form()
{
    var server_id = document.getElementById('server_id');
    server_id = server_id.options[server_id.selectedIndex].value;
    var server_data = get_value_from_id('server_' + server_id, '');
    if (server_data != '') {
        var arr = server_data.split('|');
        var name = arr[0];
        var host = arr[1];
        var port = arr[2];
        var sport = arr[3];
        var conn = arr[4];
        var host_id = document.getElementById('hostname');
        var port_id = document.getElementById('port');
        var conn_id = document.getElementById('connection');
        var serverid = document.getElementById('serverid');
        host_id.value = host;
        serverid = server_id;
        if (conn == 'off') {
            port_id.value = port;
        } else {
            port_id.value = sport;
        }
        for (var i = 0; i < conn_id.length; i++) {
            if (conn_id.options[i].value.toLowerCase() == conn.toLowerCase()) {
                conn_id.selectedIndex = i;
                continue;
            }
        }
    }
}

function levenshtein(s1, s2) 
{
    // http://kevin.vanzonneveld.net
    // +   original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
    // +   bugfixed by: Onno Marsman
    // +   revised by: Andrea Giammarchi (http://webreflection.blogspot.com)
    // + reimplemented by: Brett Zamir (http://brett-zamir.me)
    // + reimplemented by: Alexander M Beedie
    // *  example 1: levenshtein('Kevin van Zonneveld', 'Kevin van Sommeveld');
    // *  returns 1: 3
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

function check_weak_pw(password, username)
{
    if (password.length < 6) { return 0; }
    if (password.length < 8) { return 1; }
    var score = 0;
    if (password.length >= 8) { score++; }
    if (password.length >= 10) { score++; }
    if (password.length >= 15) { score++; }
    if (password.length >= 20) { score++; }
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

function check_password_strength(npw_id1, npw_id2, username_id)
{
    var fn = function() {
        var pw;
        var npw1 = $('#' + npw_id1).val();
        var npw2 = $('#' + npw_id2).val();
        var pwd = $('#' + npw_id2);
        if (npw2 == '') {
            pwd.removeClass('passwordincorrect');
            pwd.removeClass('passwordcorrect');
            $('#password_incorrect').html('');
        } else if (npw1 != '' && npw2 != '' && npw1 == npw2) {
            pwd.removeClass('passwordincorrect');
            pwd.addClass('passwordcorrect');
            $('#password_incorrect').html('<i>Passwords match</i>');
        } else {
            if (npw2 != '' && npw1 != npw2) {
                pwd.removeClass('passwordcorrect');
                pwd.addClass('passwordincorrect');
                $('#password_incorrect').html('<i>Passwords don\'t match</i>');
            }
        }
        if (npw1 != '') {
            var username = $('#' + username_id).val();
            var weak_pw = check_weak_pw(npw1, username);
            pw = $('#' + npw_id1);

            if (weak_pw <= 5) {
                pw.removeClass('passwordmedium');
                pw.removeClass('passwordstrong');
                pw.addClass('passwordweak');
                $('#urdd_pass_weak').html('<i>Password strength: Weak</i>');
            } else if (weak_pw <= 7) {
                pw.removeClass('passwordweak');
                pw.removeClass('passwordstrong');
                pw.addClass('passwordmedium');
                $('#urdd_pass_weak').html('<i>Password strength: Medium</i>');
            }
            else if (weak_pw > 7) {
                pw.removeClass('passwordweak');
                pw.removeClass('passwordmedium');
                pw.addClass('passwordstrong');
                $('#urdd_pass_weak').html('<i>Password strength: Strong</i>');
            }
        } else if (npw1 == '') {
            pw = $('#' + npw_id1);
            pw.removeClass('passwordstrong');
            pw.removeClass('passwordweak');
            pw.removeClass('passwordmedium');
            $('#urdd_pass_weak').html('');
        }
    };

    $('#' + npw_id1).on('mouseup', fn);
    $('#' + npw_id1).on('keyup', fn);
    $('#' + npw_id2).on('keyup', fn);
    $('#' + npw_id2).on('mouseup', fn);
}
