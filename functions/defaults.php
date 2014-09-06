<?php
/**
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
 * $LastChangedDate: 2014-05-29 01:03:02 +0200 (do, 29 mei 2014) $
 * $Rev: 3058 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: defaults.php 3058 2014-05-28 23:03:02Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function get_default_prefs()
{
    $prefArray = array();
    $prefArray['maxsetname']        = 80;		// nr of chars in a set name, also used for many other fields in the pages
    $prefArray['setsperpage']       = DEFAULT_PER_PAGE;	// number of lines per  page
    $prefArray['minsetsize']        = 0;		// min size in MB per set to be shown
    $prefArray['maxsetsize']        = 0;		// max size in MB per set to be shown
    $prefArray['minngsize']         = 1;		// min nr of post in a ng to be shown
    $prefArray['unrar']             = 1;		// auto unrar
    $prefArray['hiddenfiles']       = 0;		// show hidden files in view files
    $prefArray['hidden_files_list'] = serialize(array());	// list of files to be hidden
    $prefArray['unpar']             = 1;		// auto unpar
    $prefArray['download_par']      = 1;		// download parfiles even tho may not be needed
    $prefArray['search_terms']      = serialize(array());	// auto search terms after update is run
    $prefArray['blocked_terms']     = serialize(array());	// auto search terms after update is run
    $prefArray['delete_files']      = 1;		// delete redundant files after unpar/rar is complete
    $prefArray['defaultsort']       = 'date DESC';  // sort of the sets page
    $prefArray['mail_user']         = 0;		// mail user when a dl is complete
    $prefArray['mail_user_sets']    = 0;		// mail user when an interesting set is found
    $prefArray['template']          = DEFAULT_TEMPLATE;	// the template used by the user
    $prefArray['language']          = DEFAULT_LANGUAGE;	// the language used by the user
    $prefArray['index_page']        = DEFAULT_INDEX_PAGE;	// the website the user is pointed to when going to the index file
    $prefArray['setcompleteness']   = 0;	// the size of a set in % before it is shown on the browse page
    $prefArray['pref_level']        = user_levels::CONFIG_LEVEL_BASIC; // the user experience level
    $prefArray['search_type']       = 'LIKE';     // search type for set matching
    $prefArray['use_auto_download'] = 0;       // auto downloading based on search terms
    $prefArray['use_auto_download_nzb'] = 0;       // auto downloading based on search terms as an nzb file
    $prefArray['global_scripts']    = '';	// enabled global scripts
    $prefArray['user_scripts']      = '';	// enabled user scripts
    $prefArray['skip_int']          = 0;	// enabled user scripts
    for ($i = 1; $i <= button::MAX_SEARCH_BUTTONS; $i++) {
        $prefArray['button'.$i] = 'none';		// the buttons
    }
    $prefArray['spot_spam_limit']   = 0;
    $prefArray['rarfile_size']      = 1000;
    $prefArray['recovery_size']     = 5;
    $prefArray['download_delay']    = 0; // number of seconds the download gets delayed
    $prefArray['poster_name']       = '';
    $prefArray['poster_email']      = '';
    $prefArray['poster_default_text'] = '';
    $prefArray['format_dl_dir']     = '';
    $prefArray['add_setname']       = 1;
    $prefArray['show_image']        = 0;
    $prefArray['show_subcats']      = 0;
    $prefArray['default_group']     = 0;
    $prefArray['default_spot']      = '';
    $prefArray['default_feed']      = 0;
    $prefArray['stylesheet']        = DEFAULT_STYLESHEET;
    $prefArray['subs_lang']         = 'en';       //subtitle languages
    $prefArray['download_text_file']    = 1;
    $prefArray['cancel_crypted_rars']   = 0; // Check for encrypted RARs when downloading, and cancel DL if found
    $prefArray['saved_spot_searches']   = serialize(array());  // not used in prefs !!!
    $prefArray['basket_type']           = basket_type::LARGE;

    return $prefArray;
}

function get_default_config()
{
    $pathc = realpath(dirname(__FILE__) . '/../');
    add_dir_separator($pathc);

    $prefArray_root = array();
    $prefArray_root['unrar_path']    = '/usr/bin/rar';
    $prefArray_root['rar_path']      = '/usr/bin/rar';
    $prefArray_root['un7zr_path']    = '/usr/bin/7zr';
    $prefArray_root['unzip_path']    = '/usr/bin/unzip';
    $prefArray_root['gzip_path']     = '/bin/gzip';
    $prefArray_root['unace_path']    = '/usr/bin/ace';
    $prefArray_root['tar_path']      = '/bin/tar';
    $prefArray_root['unarj_path']    = '/usr/bin/arj';
    $prefArray_root['subdownloader_path'] = '/usr/bin/subdownloader';
    $prefArray_root['file_path']     = '/usr/bin/file';
    $prefArray_root['unpar_path']    = '/usr/bin/par2';
    $prefArray_root['cksfv_path']    = '/usr/bin/cksfv';
    $prefArray_root['yydecode_path'] = '/usr/bin/yydecode';
    $prefArray_root['yyencode_path'] = '/usr/local/bin/yencode';
    $prefArray_root['trickle_path']  = '/usr/bin/trickle';
    // Is this the installation? Then $_SESSION contains the actual paths:
    if (isset($_SESSION['7zip'])) { $prefArray_root['un7zr_path']           = $_SESSION['7zip'];}
    if (isset($_SESSION['tar'])) { $prefArray_root['tar_path']              = $_SESSION['tar']; }
    if (isset($_SESSION['unrar'])) { $prefArray_root['unrar_path']          = $_SESSION['unrar']; }
    if (isset($_SESSION['rar'])) { $prefArray_root['rar_path']              = $_SESSION['rar']; }
    if (isset($_SESSION['unzip'])) { $prefArray_root['unzip_path']          = $_SESSION['unzip']; }
    if (isset($_SESSION['gzip'])) { $prefArray_root['gzip_path']            = $_SESSION['gzip']; }
    if (isset($_SESSION['unace'])) { $prefArray_root['unace_path']          = $_SESSION['unace']; }
    if (isset($_SESSION['file'])) { $prefArray_root['file_path']            = $_SESSION['file']; }
    if (isset($_SESSION['unarj'])) { $prefArray_root['unarj_path']          = $_SESSION['unarj']; }
    if (isset($_SESSION['par2'])) { $prefArray_root['unpar_path']           = $_SESSION['par2']; }
    if (isset($_SESSION['cksfv'])) { $prefArray_root['cksfv_path']          = $_SESSION['cksfv']; }
    if (isset($_SESSION['yydecode'])) { $prefArray_root['yydecode_path']    = $_SESSION['yydecode']; }
    if (isset($_SESSION['yyencode'])) { $prefArray_root['yyencode_path']    = $_SESSION['yyencode']; }
    if (isset($_SESSION['trickle'])) { $prefArray_root['trickle_path']      = $_SESSION['trickle']; }
    if (isset($_SESSION['subdownloader'])) { $prefArray_root['subdownloader_path']  = $_SESSION['subdownloader']; }

    $prefArray_root['urdd_path'] = $pathc . 'urdd.sh'; // location where urdd is stored
    if (isset($_SESSION['urdd'])) {
        $prefArray_root['urdd_path']  = $_SESSION['urdd'];
    }
    $prefArray_root['urdd_host']    = 'localhost';	// hostname of urdd
    $prefArray_root['shaping']      = 0;			// enable traffic shaping with trickle
    $prefArray_root['auto_expire']  = 1;		// expire msgs after update
    $prefArray_root['dlpath']       = '';			// the path to store files
    $prefArray_root['pidpath']      = $pathc . 'urdd/pid/';			// the path to the PID file
    if (isset($_SESSION['urddownloaddir'])) {
        $prefArray_root['dlpath']   = realpath($_SESSION['urddownloaddir']);
        add_dir_separator($prefArray_root['dlpath']);
    }
    $prefArray_root['baseurl']          = '';			// the URL of the webserver
    if (isset($_SESSION['baseurl'])) {
        $prefArray_root['baseurl']      = $_SESSION['url'];
    }
    $prefArray_root['auto_reg']             = 0;		// activate registered users after email confirmation
    $prefArray_root['urdd_port']            = URDD_PORT;
    $prefArray_root['urdd_restart']         = 1;		// restart active tasks after a restart of urdd
    $prefArray_root['urdd_daemonise']       = 1;		// start urdd as a daemon process
    $prefArray_root['urdd_maxthreads']      = MAX_THREADS;
    $prefArray_root['nntp_maxdlthreads']    = 0; // max threads per download
    $prefArray_root['admin_email']  = '';		// email of the urd admin
    if (isset($_SESSION['urdusermail'])) {
        $prefArray_root['admin_email']      = $_SESSION['urdusermail'];
    }
    $prefArray_root['default_expire_time']  = DEFAULT_EXPIRE_TIME; // in days for a newsgroup
    $prefArray_root['expire_incomplete']    = 0; // in days for a newsgroup
    $prefArray_root['expire_percentage']    = 80; // in days for a newsgroup
    $prefArray_root['users_clean_age']      = 0; // delete users after so many days of inactivity
    $prefArray_root['maxdl']                = 400;	// shaping max dl
    $prefArray_root['maxul']                = 100; // shaping max up
    $prefArray_root['URD_version']          = urd_version::get_version();	// current version of urd
    $prefArray_root['period_cu']            = 0;		// clean dir interval in days
    $prefArray_root['time1_cu']             = 0;		// " hours
    $prefArray_root['time2_cu']             = 0;		// " minutes
    $prefArray_root['period_cd']            = 0;		// clean dir interval in days
    $prefArray_root['time1_cd']             = 0;		// " hours
    $prefArray_root['time2_cd']             = 0;		// " minutes
    $prefArray_root['dir_cd']               = '';			// which dirs
    $prefArray_root['period_opt']           = 3;		// optimise db interval in days
    $prefArray_root['time1_opt']            = 2;		// hours
    $prefArray_root['time2_opt']            = 15;		// mins
    $prefArray_root['period_ng']            = 0;		// update the ng list interval in days
    $prefArray_root['time1_ng']             = 0;		// hours
    $prefArray_root['time2_ng']             = 0;		// mins
    $prefArray_root['register']             = 0;		// enable user registration
    $prefArray_root['run_update']           = 0;		// the username as which the first ng update will run
    $prefArray_root['update_version']       = '';		// the newest version of urd ready for dl
    $prefArray_root['update_text']          = '';		// text field with newest version
    $prefArray_root['update_type']          = 0;		// type of update, bugfix, security fix etc
    $prefArray_root['time1_update']         = '12';		// hours update urd
    $prefArray_root['time2_update']         = '00';		// mins update urd
    $prefArray_root['period_update']        = 8;		// interval update urd in days
    $prefArray_root['period_getspots']      = 1;		// interval update urd in days
    $prefArray_root['time1_getspots']       = '1';		// hours update urd
    $prefArray_root['time2_getspots']       = '15';		// mins update urd
    $prefArray_root['period_getspots_blacklist']    = 2;		// interval update urd in days
    $prefArray_root['time1_getspots_blacklist']     = '1';		// hours update urd
    $prefArray_root['time2_getspots_blacklist']     = '10';		// mins update urd
    $prefArray_root['period_getspots_whitelist']    = 2;		// interval update urd in days
    $prefArray_root['time1_getspots_whitelist']     = '1';		// hours update urd
    $prefArray_root['time2_getspots_whitelist']     = '10';		// mins update urd
    $prefArray_root['global_hiddenfiles']           = 0;		// show hidden files in view files
    $prefArray_root['global_hidden_files_list']     = serialize(array());  // list of files to be hidden
    $prefArray_root['default_template']             = DEFAULT_TEMPLATE;   // the template used
    $prefArray_root['default_stylesheet']           = DEFAULT_STYLESHEET;   // the template used
    $prefArray_root['log_level']                    = LOG_INFO;        // the log level for output to the logfile
    $prefArray_root['group']                        = '';			// the system group downloaded files are chgrp'ed to
    $prefArray_root['scheduler']                    = 'on';		// whether the scheduler is on
    $prefArray_root['usenet_server']                = '';		// not used?
    $prefArray_root['permissions']                  = '0644';	// the permission downloaded files will have
    $prefArray_root['default_language']             = DEFAULT_LANGUAGE;// the default language for URD
    $prefArray_root['maxbuttons']                   = button::MAX_SEARCH_BUTTONS; // the maximum number of buttons shown on the browse page
    $prefArray_root['period_sendinfo']              = 0;		// the frequency setinfo will be merged into the central repository
    $prefArray_root['period_getinfo']               = 0;		// the frequency setinfo will be gotten frmo the central repository
    $prefArray_root['time1_sendinfo']               = 0;		// hours to send setinfo
    $prefArray_root['time1_getinfo']                = 0;		// hours to get setinfo
    $prefArray_root['time2_sendinfo']               = 0;		// minutes to send setinfo
    $prefArray_root['time2_getinfo']                = 0;		// minutes to get setinf
    $prefArray_root['sendmail']                     = 0;		// whether email msgs may be send
    $prefArray_root['index_page_root']              = DEFAULT_INDEX_PAGE;	// the default page where the index file links to
    $prefArray_root['preferred_server']             = 0;	// the server used for downloading headers from
    $prefArray_root['webdownload']                  = 0;		// allow downloading tarballs in the web interface
    $prefArray_root['webeditfile']                  = 0;		// allow editting files in the web interface
    $prefArray_root['maxfilesize']                  = 0;		// the max size getinfo allows to access (in kB)
    $prefArray_root['maxpreviewsize']               = MAX_PREVIEW_SIZE;		// the max size allowed to preview (in kB)
    $prefArray_root['socket_timeout']               = socket::DEFAULT_SOCKET_TIMEOUT;		// the max size getinfo allows to access (in kB)
    $prefArray_root['urdd_connection_timeout']      = socket::DEFAULT_SOCKET_TIMEOUT;		// the max size getinfo allows to access (in kB)
    $prefArray_root['nntp_maxthreads']              = MAX_NNTP_THREADS;
    $prefArray_root['db_intensive_maxthreads']      = MAX_DB_INTENSIVE_THREADS;
    $prefArray_root['auto_download']            = 0;
    $prefArray_root['urdd_pars']                = '';
    $prefArray_root['unpar_pars']               = 'r -q';
    $prefArray_root['par_pars']                 = 'c -q';
    $prefArray_root['unrar_pars']               = 'x -y -kb -c- -idp';
    $prefArray_root['rar_pars']                 = 'a -ed -inul -idp -m5 -r -ep1 -y';
    $prefArray_root['unace_pars']               = 'x -y';
    $prefArray_root['un7zr_pars']               = 'x -y -bd';
    $prefArray_root['unarj_pars']               = 'x -y -i';
    $prefArray_root['subdownloader_pars']       = '-c -q --rename-subs -D';
    $prefArray_root['unzip_pars']               = '-qq -n';
    $prefArray_root['gzip_pars']                = '-q -c';
    $prefArray_root['yydecode_pars']            = '-e -b -f';
    $prefArray_root['yyencode_pars']            = '-q -m130000 -o';
    $prefArray_root['check_nntp_connections']   = 1;
    $prefArray_root['nntp_all_servers']         = 0;
    $prefArray_root['allow_global_scripts']     = 0;
    $prefArray_root['allow_user_scripts']       = 0;
    $prefArray_root['clean_dir_age']            = 3;
    $prefArray_root['clean_db_age']             = 3;    
    $prefArray_root['max_dl_name']              = MAX_DL_NAME;
    $prefArray_root['total_max_articles']       = 0; // total amount of articles an update will download in one go
    $prefArray_root['auto_login']               = '';
    $prefArray_root['compress_nzb']             = 0;
    $prefArray_root['connection_timeout']       = SERVER_CONNECTION_TIMEOUT;
    $prefArray_root['socket_timeout']           = socket::DEFAULT_SOCKET_TIMEOUT;
    $prefArray_root['nice_value']               = 0;
    $prefArray_root['maxheaders']               = MAX_HEADERS; // total amount of headers gotten in one update batch
    $prefArray_root['replacement_str']          = '_';
    $prefArray_root['maxexpire']                = MAX_EXPIRE_TIME;
    $prefArray_root['group_filter']             = URD_NNTP::GROUP_FILTER;
    $prefArray_root['queue_size']               = QUEUE_SIZE;
    $prefArray_root['extset_group']             = 'alt.binaries.test.yenc';
    $prefArray_root['urdd_startup']             = 0;
    $prefArray_root['max_login_count']          = 0;
    $prefArray_root['clickjack']                = 1; // click jack prevention
    $prefArray_root['need_challenge']           = 0; 
    $prefArray_root['period_cdb']               = 0;
    $prefArray_root['time1_cdb']                = 0;
    $prefArray_root['time2_cdb']                = 0;
    $prefArray_root['parse_nfo']                = 1;//parse nfo files when previewing
    $prefArray_root['auto_getnfo']              = 1;		// get nfo files after update
    $prefArray_root['follow_link']              = 0;// follow links in nfo files while updating
    $prefArray_root['keep_interesting']         = 0; // keep interesting articles on expiring
    $prefArray_root['spots_group']              = SPOTS_GROUPS::SPOTS; // group where spots are to be loaded from
    $prefArray_root['spots_reports_group']      = SPOTS_GROUPS::REPORTS; // group where spots reports are to be loaded from
    $prefArray_root['spots_comments_group']     = SPOTS_GROUPS::COMMENTS; // group where spots comments are to be loaded from
    $prefArray_root['ftd_group']                = SPOTS_GROUPS::NZBS; // group where NZBs from spots are to be loaded from
    $prefArray_root['download_comment_avatar']  = 0;
    $prefArray_root['download_spots_comments']  = 0;
    $prefArray_root['download_spots_reports']   = 1;
    $prefArray_root['download_spots_images']    = 1;
    $prefArray_root['spots_max_categories']     = 0;
    $prefArray_root['spots_blacklist']          = 'http://jij.haatmij.nl/spotnet/blacklist.txt'; //black list with spotterIds known for spamming etc
    $prefArray_root['spots_whitelist']          = 'http://jij.haatmij.nl/spotnet/whitelist.txt'; //white list with spotterIds known for solid spots

    $prefArray_root['settype_' . urd_extsetinfo::SETTYPE_UNKNOWN ]      = '%C %P %n';
    $prefArray_root['settype_' . urd_extsetinfo::SETTYPE_MOVIE ]        = '%C %P %n %(y) %m %a %l %s %[x] %N ';
    $prefArray_root['settype_' . urd_extsetinfo::SETTYPE_DOCUMENTARY ]  = '%C %P %n %(y) %m %a %l %s %[x] %N ';
    $prefArray_root['settype_' . urd_extsetinfo::SETTYPE_ALBUM ]        = '%C %P %n %(y) %f %g %N';
    $prefArray_root['settype_' . urd_extsetinfo::SETTYPE_TVSERIES ]     = '%C %P %n %e %m %a %[x] %N ';
    $prefArray_root['settype_' . urd_extsetinfo::SETTYPE_SOFTWARE ]     = '%C %P %n %(o) %N';
    $prefArray_root['settype_' . urd_extsetinfo::SETTYPE_GAME ]         = '%C %P %n %(o) %N';
    $prefArray_root['settype_' . urd_extsetinfo::SETTYPE_EBOOK ]        = '%C %P %n - %A %(y) %f %g ';
    $prefArray_root['settype_' . urd_extsetinfo::SETTYPE_IMAGE ]        = '%C %P %n %f %g %[x] %N';
    $prefArray_root['settype_' . urd_extsetinfo::SETTYPE_TVSHOW ]       = '%C %P %e %n %m %a %[x] %N ';
    $prefArray_root['settype_' . urd_extsetinfo::SETTYPE_OTHER ]        = '%C %P %n';

    $prefArray_root['modules']  =
        urd_modules::URD_CLASS_GENERIC |
        urd_modules::URD_CLASS_GROUPS |
        urd_modules::URD_CLASS_MAKENZB |
        urd_modules::URD_CLASS_POST |
        urd_modules::URD_CLASS_RSS |
        urd_modules::URD_CLASS_SYNC |
        urd_modules::URD_CLASS_DOWNLOAD |
        urd_modules::URD_CLASS_VIEWFILES |
        urd_modules::URD_CLASS_USENZB |
        urd_modules::URD_CLASS_SPOTS;

    $prefArray_root['urdd_uid']                     = 'urd';
    $prefArray_root['urdd_gid']                     = 'urd';
    $prefArray_root['poster_blacklist']             = serialize(array());
    $prefArray_root['allow_robots']                 = '0';
    $prefArray_root['spots_expire_time']            = DEFAULT_SPOTS_EXPIRE_TIME;

    $prefArray_root['mail_account_activated']       = 'account_activated.tpl';
    $prefArray_root['mail_account_disabled']        = 'account_disabled.tpl';
    $prefArray_root['mail_activate_account']        = 'activate_account.tpl';
    $prefArray_root['mail_download_status']         = 'download_status.tpl';
    $prefArray_root['mail_new_interesting_sets']    = 'new_interesting_sets.tpl';
    $prefArray_root['mail_new_preferences']         = 'new_preferences.tpl';
    $prefArray_root['mail_new_user']                = 'new_user.tpl';
    $prefArray_root['mail_password_reset']          = 'password_reset.tpl';

    $prefArray_root['use_encrypted_passwords']      = '1';
    $prefArray_root['keystore_path']                = '';
    $prefArray_root['spot_expire_spam_count']       = '0';

    $prefArray_root['privatekey']                   = ''; // for spots comment posting
    $prefArray_root['publickey']                    = ''; // for spots comment posting

    $prefArray_root['db_version']                   = DB_VERSION;

    return $prefArray_root;
}

class SPOTS_GROUPS
{
    const SPOTS        = 'free.pt';
    const COMMENTS     = 'free.usenet';
    const REPORTS      = 'free.willey';
    const NZBS         = 'alt.binaries.ftd';

    public static function get_hidden_groups()
    {
        return array(self::SPOTS, self::COMMENTS, self::REPORTS);
    }
}
