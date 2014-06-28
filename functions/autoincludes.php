<?php

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
 * $LastChangedDate: 2014-06-07 14:53:28 +0200 (za, 07 jun 2014) $
 * $Rev: 3081 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: autoincludes.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function urd_autoload($class)
{
    $pathai = realpath(dirname(__FILE__));
    switch ($class) {
    case 'user_status': $file = 'defines.php'; break;
    case 'challenge': $file = 'challenge.php'; break;
    case 'keystore': $file = 'keystore_functions.php'; break;
    case 'nzb_poller': $file = '../urdd/nzb_poller.class.php'; break;
    case 'download_type': $file = '../urdd/download_type.class.php'; break;
    case 'urd_mail': $file = 'mail_functions.php'; break;
    case 'urdd_rss' : $file = 'extset_functions.php'; break;
    case 'urdd_extsetinfo' : $file = '../urdd/urdd_rss.php'; break;
    case 'urdd_protocol' : $file = '../urdd/urdd_protocol.php'; break;
    case 'saved_searches' : $file = 'saved_searches.php'; break;
    case 'SpotCategories' : $file = 'spots_categories.php'; break;
    case 'button': $file = 'buttons.php'; break;
    case 'button_c': $file = '../html/admin_buttons.php'; break;
    case 'get_opt': $file = 'libs/getopt.php'; break;
    case 'socket': $file = 'libs/socket.php'; break;
    case 'Base_NNTP_Client': $file = 'libs/base_nntp_client.php'; break;
    case 'NNTP_Client' : $file = 'libs/nntp_client.php'; break;
    case 'action': $file = '../urdd/action.php'; break;
    case 'queue': $file = '../urdd/queue.php'; break;
    case 'schedule':
    case 'job': $file = '../urdd/schedule.php'; break;
    case 'thread_list':
    case 'thread': $file = '../urdd/thread.php'; break;
    case 'connection':
    case 'conn_list':  $file = '../urdd/urdd_connection.php'; break;
    case 'URD_NNTP': $file = '../urdd/nntp.class.php'; break;
    case 'menu_item':
    case 'menu_item2':
    case 'menu': $file = 'menu.php'; break;
    case 'command': $file = '../urdd/urdd_command.php'; break;
    case 'TableSetData':
    case 'TableBinaries':
    case 'TableParts':
    case 'TableGroups' : $file = '../urdd/group_functions.php'; break;
    case 'DatabaseConnection': $file = 'db.class.php'; break;
    case 'pr_file':
    case 'pr_list': $file = 'pr_file.php'; break;
    case 'file_list':
    case 'stored_files':
    case 'a_file': $file = 'stored_files.php'; break;
    case 'urdd_client': $file = '../urdd/urdd_client.php'; break;
    case 'server_data': $file = '../urdd/server_data.php'; break;
    case 'usenet_servers':
    case 'usenet_server': $file = '../urdd/urdd_usenet_servers.php'; break;
    case 'usenet_servers_c': $file = '../html/admin_usenet_servers.php'; break;
    case 'periods_c':
    case 'period_c': $file = 'periods.php'; break;
    case 'Smarty': $file = 'libs/smarty/libs/Smarty.class.php'; break;
    case 'user_levels':
    case 'pref_line' : $file = 'pref_functions.php'; break;
    case 'QuickMenuItem' : $file = '../html/ajax_showquickmenu.php'; break;
    case 'Downloadinfo' : $file = '../html/ajax_showtransfers.php'; break;
    case 'Mail_mimeDecode' : $file = 'libs/mimedecode.php'; break;
    case 'module' : 
    case 'urd_modules' : $file = 'module_functions.php'; break;
    case 'nfo_parser' : $file = 'parse_nfo.php'; break;
    case 'saved_searches' : $file = 'saved_searches.php'; break;
    case 'sets_marking' : $file = 'sets_marking.php'; break;
    case 'urd_version' : $file = 'urdversion.php'; break;
    case 'urd_help' : $file = 'urdd_help.php'; break;
    case 'urdd_rss' : $file = 'urdd_rss.php'; break;
    case 'UbbParse' : $file = 'libs/ubbparse.php'; break;
    case 'SpotSigning': $file = 'libs/spot_signing.php'; break;
    case 'SpotParser' : $file = 'libs/spotparser.php'; break;
    case 'urd_xml_writer' :
    case 'urd_xml_reader' : $file = 'xml_functions.php'; break;
    case 'exception_nntp_connect': $file = 'urd_exceptions.php'; break;
    case 'urd_user_rights': $file = 'user_functions.php'; break;
    case 'urdd_sockets': $file = '../urdd/urdd_sockets.php'; break;
    case 'test_result_list':
    case 'test_result': $file = '../urdd/urdd_test.php'; break;
    case 'config_cache': $file = 'config_functions.php'; break;
    case 'DatabaseConnection_mysql': $file = 'db_mysql.php'; break;
    case 'DatabaseConnection_psql': $file = 'db_psql.php'; break;
    case 'DatabaseConnection_sqlite': $file = 'db_sqlite.php'; break;
    case 'urd_magic_crap': $file = 'fix_magic.php'; break;
    case 'urd_table' :
    case 'urd_column' :
    case 'urd_index' :
    case 'urd_db_structure' :
    case 'urd_database' : $file = 'db/urd_db_structure.php'; break;
    case 'urd_extsetinfo' :  $file = 'extset_functions.php'; break;
    default :
        // maybe need to change this a bit... as more autoloaders may be chained
        //throw new exception ("Class not found: $class");
        return;
    }
    require_once $pathai . '/' . $file;
}

spl_autoload_register('urd_autoload');
