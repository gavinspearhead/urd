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
* $LastChangedDate: 2008-07-18 19:51:05 +0200 (Fri, 18 Jul 2008) $
* $Rev: 1305 $
* $Author: gavinspearhead $
* $Id: english.php 1305 2008-07-18 17:51:05Z gavinspearhead $
*/

/* English language file for URD */

//  Syntax: All language variables are put in $LN, and consist of a lowercase,
//   english description of the variable content (a-z, no other characters).
//  Capitals: Default should be the most common occurence, overriding the capitalization
//   can be done via smarty's functions. (So don't create another variable with only different caps)
//  Examples:
//  {$articleTitle}             = next x-men film, x3, delayed.
//  {$articleTitle|capitalize}      = Next X-Men Film, x3, Delayed.
//  {$articleTitle|capitalize:true} = Next X-Men Film, X3, Delayed.
//  Other options are: upper, lower.

//  Language array:
$LN = array();

$LN['byte']             = 'byte';
$LN['bytes']            = 'bytes';
$LN['byte_short']       = 'B';

$LN['ok']               = 'OK';
$LN['cancel']           = 'Cancel';
$LN['pause']            = 'Pause';
$LN['continue']         = 'Continue';

$LN['details']          = 'Details';
$LN['error']            = 'Error';
$LN['atonce']           = 'At once';

// Special:
$LN['urdname']          = 'URD';
$LN['decimalseparator'] = '.';
$LN['dateformat']       = 'm/d/Y';
$LN['dateformat2']      = 'M d Y';
$LN['dateformat3']      = 'M d';
$LN['timeformat']       = 'H:i:s';
$LN['timeformat2']      = 'H:i';

// This 'overwrites' the define values:
$LN['periods'][0]       = 'No auto update';
$LN['periods'][11]      = 'Every hour';
$LN['periods'][12]      = 'Every 3 hours';
$LN['periods'][1]       = 'Every 6 hours';
$LN['periods'][13]      = 'Every 12 hours';
$LN['periods'][2]       = 'Every day';
$LN['periods'][3]       = 'Every Monday';
$LN['periods'][4]       = 'Every Tuesday';
$LN['periods'][5]       = 'Every Wednesday';
$LN['periods'][6]       = 'Every Thursday';
$LN['periods'][7]       = 'Every Friday';
$LN['periods'][8]       = 'Every Saturday';
$LN['periods'][9]       = 'Every Sunday';
$LN['periods'][10]      = 'Every 4 weeks';

$LN['autoconfig']       = 'Autoconfigure';
$LN['autoconfig_ext']   = 'Autoconfigure (extended)';
$LN['extended']         = 'extended';
$LN['reload']           = 'reload';
$LN['expand']           = 'expand';
$LN['advanced_search']  = 'Advanced search';
$LN['since']            = 'since';
$LN['disabled']         = 'disabled';
$LN['unknown']          = 'Unknown';
$LN['help']             = 'Help';
$LN['sets']             = 'sets';
$LN['active']           = 'Active';

$LN['id']                   = 'ID';
$LN['pid']                  = 'PID';
$LN['server']               = 'Server';
$LN['start_time']           = 'Start time';
$LN['queue_time']           = 'Queue time';
$LN['recurrence']           = 'Recurrence';
$LN['enabled']              = 'Enabled';
$LN['free_threads']         = 'Free threads';
$LN['total_free_threads']   = 'Total free threads';
$LN['free_db_intensive_threads']   = 'Free database intensive threads';
$LN['free_nntp_threads']    = 'Free NNTP threads';

$LN['expire']            = 'Expire';
$LN['update']            = 'Update';
$LN['purge']             = 'Purge';

$LN['short_day_names'][1]	= 'Sun';
$LN['short_day_names'][2]	= 'Mon';
$LN['short_day_names'][3]	= 'Tue';
$LN['short_day_names'][4]	= 'Wed';
$LN['short_day_names'][5]	= 'Thu';
$LN['short_day_names'][6]	= 'Fri';
$LN['short_day_names'][7]	= 'Sat';

$LN['CAPTCHA1']   = 'Captcha';
$LN['CAPTCHA2']   = '3 black symbols';

$LN['autoconfig_msg']       = 'Autoconfigure: It tries all servers in the list and sees if there is a server at the standard usenet ports (119 and 563), with or without ssl/tls. If it finds one it selects it; and updating server is selected if one is found that allows indexing';
$LN['autoconfig_ext_msg']   = 'Extended autoconfigure: It tries all servers in the list and sees if there is a server at the standard usenet ports (119 and 563) and many other ports that may be used by usenet service providers (like 23, 80, 8080, 443), with or without ssl/tls. If it finds one it selects it; and updating server is selected if one is found that allows indexing';

$LN['month_names'][1]   = 'January';
$LN['month_names'][2]   = 'February';
$LN['month_names'][3]   = 'March';
$LN['month_names'][4]   = 'April';
$LN['month_names'][5]   = 'May';
$LN['month_names'][6]   = 'June';
$LN['month_names'][7]   = 'July';
$LN['month_names'][8]   = 'August';
$LN['month_names'][9]   = 'September';
$LN['month_names'][10]  = 'October';
$LN['month_names'][11]  = 'November';
$LN['month_names'][12]  = 'December';

$LN['short_month_names'][1]   = 'Jan';
$LN['short_month_names'][2]   = 'Feb';
$LN['short_month_names'][3]   = 'Mar';
$LN['short_month_names'][4]   = 'Apr';
$LN['short_month_names'][5]   = 'May';
$LN['short_month_names'][6]   = 'Jun';
$LN['short_month_names'][7]   = 'Jul';
$LN['short_month_names'][8]   = 'Aug';
$LN['short_month_names'][9]   = 'Sep';
$LN['short_month_names'][10]  = 'Oct';
$LN['short_month_names'][11]  = 'Nov';
$LN['short_month_names'][12]  = 'Dec';

$LN['status']       = 'Status';
$LN['activity']     = 'Activity';
$LN['off']          = 'Off';
$LN['on']           = 'On';
$LN['all']          = 'All';
$LN['preview']      = 'Preview';
$LN['temporary']    = 'Temporary files';
$LN['other']        = 'Other';
$LN['from']         = 'from';
$LN['never']        = 'never';
$LN['total']        = 'Total';

$LN['select']       = 'Select one';
$LN['version']      = 'Version';

$LN['whitelisttag']     = 'W';
$LN['spamreporttag']    = 'S';
$LN['next']             = 'Next';
$LN['previous']         = 'Previous';

$LN['add_search']       = 'Save search';
$LN['delete_search']    = 'Delete search';
$LN['save_search_as']   = 'Save search as';
$LN['saved']            = 'Saved';
$LN['deleted']          = 'Deleted';

// Time:
$LN['year']         = 'Year';
$LN['month']        = 'Month';
$LN['week']         = 'Week';
$LN['day']          = 'Day';
$LN['hour']         = 'Hour';
$LN['minute']       = 'Minute';
$LN['second']       = 'Second';

$LN['years']        = 'Years';
$LN['months']       = 'Months';
$LN['weeks']        = 'Weeks';
$LN['days']         = 'Days';
$LN['hours']        = 'Hours';
$LN['minutes']      = 'Minutes';
$LN['seconds']      = 'Seconds';

$LN['year_short']   = 'Y';
$LN['month_short']  = 'M';
$LN['week_short']   = 'w';
$LN['day_short']    = 'd';
$LN['hour_short']   = 'h';
$LN['minute_short'] = 'm';
$LN['second_short'] = 's';

// Menu:
$LN['menudownloads']    = 'Downloads';
$LN['menuuploads']      = 'Uploads';
$LN['menutransfers']    = 'Transfers';
$LN['menubrowsesets']   = 'Supply';
$LN['menugroupsearch']  = 'Search&nbsp;group&nbsp;sets';
$LN['menuspotssearch']  = 'Search&nbsp;spots';
$LN['menursssearch']    = 'Search&nbsp;rss&nbsp;sets';
$LN['menusearch']       = 'Search';
$LN['menunewsgroups']   = 'Newsgroups';
$LN['menuviewfiles']    = 'View&nbsp;files';
$LN['menuviewfiles_downloads']  = 'Downloads';
$LN['menuviewfiles_previews']   = 'Preview&nbsp;files';
$LN['menuviewfiles_nzbfiles']   = 'NZB&nbsp;files';
$LN['menuviewfiles_scripts']    = 'Scripts';
$LN['menuviewfiles_posts']      = 'Posts';
$LN['menupreferences']          = 'Preferences';
$LN['menuadmin']        = 'Admin';
$LN['menuabout']        = 'About';
$LN['menumanual']       = 'Manual';
$LN['menuadminconfig']  = 'Configuration';
$LN['menuadmincontrol'] = 'Dashboard';
$LN['menuadminusenet']  = 'Usenet&nbsp;servers';
$LN['adminupdateblacklist'] = 'Update&nbsp;spots&nbsp;blacklist';
$LN['adminupdatewhitelist'] = 'Update&nbsp;spots&nbsp;whitelist';
$LN['menuadminlog']     = 'Log&nbsp;file';
$LN['menuadminjobs']    = 'Scheduled&nbsp;jobs';
$LN['menuadmintasks']   = 'Tasks';
$LN['menuadminusers']   = 'Users';
$LN['menuadminbuttons'] = 'Search&nbsp;options';
$LN['menuhelp']         = 'Help';
$LN['menufaq']          = 'FAQ';
$LN['menulicence']      = 'Licence';
$LN['menulogout']       = 'Log&nbsp;out&nbsp;';
$LN['menulogin']        = 'Log&nbsp;in';
$LN['menudebug']        = 'Debug&nbsp;information';
$LN['menustats']        = 'Statistics';
$LN['menuforum']        = 'Forum';

// Stati:
$LN['statusidling']         = 'Idling';
$LN['statusrunningtasks']   = 'Active tasks';
$LN['enableurddfirst']      = 'Enable URDD to change these settings';

// Version:
$LN['enableurdd']       = 'Click to turn on URDD';
$LN['disableurdd']      = 'Click to turn off URDD';
$LN['urddenabled']      = 'URDD is online';
$LN['urddstarting']     = 'URDD is starting up';
$LN['urdddisabled']     = 'URDD is offline';
$LN['versionuptodate']  = 'URD is up to date.';
$LN['versionoutdated']  = 'URD is outdated';
$LN['newversionavailable']  = 'A new major version is available.';
$LN['bugfixedversion']      = 'The new version contains bugfixes.';
$LN['newfeatureversion']    = 'The new version contains new features.';
$LN['otherversion']         = 'The new version has unspecified changes (??).';
$LN['securityfixavailable'] = 'The new version contains important security fixes.';

// Tasks:
$LN['taskupdate']       = 'Updating';
$LN['taskcontinue']     = 'Continue';
$LN['taskpause']        = 'Pause';
$LN['taskunknown']      = 'Unknown';
$LN['taskpost']         = 'Posting';
$LN['taskpurge']        = 'Purging';
$LN['taskexpire']       = 'Expiring';
$LN['taskdownload']     = 'Downloading';
$LN['taskoptimise']     = 'Optimising';
$LN['taskgrouplist']    = 'Downloading group list';
$LN['taskunparunrar']   = 'Extracting';
$LN['taskcheckversion'] = 'Checking version';
$LN['taskgetsetinfo']   = 'Getting set info';
$LN['tasksendsetinfo']  = 'Sending set info';
$LN['taskparsenzb']     = 'Reading NZB file';
$LN['taskmakenzb']      = 'Creating NZB file';
$LN['taskcleandir']     = 'Cleaning directories';
$LN['taskcleandb']      = 'Cleaning database';
$LN['taskgensets']      = 'Generating sets for';
$LN['taskadddata']      = 'Adding download data for';
$LN['taskmergesets']    = 'Merging sets';
$LN['taskfindservers']  = 'Server autoconfig';
$LN['taskgetnfo']       = 'Getting NFO data';
$LN['taskgetspots']     = 'Getting spots';
$LN['taskgetspot_comments']    = 'Getting spots comments';
$LN['taskgetspot_reports']     = 'Getting spots spam reports';
$LN['taskgetspot_images']     = 'Getting spots images';
$LN['taskgetblacklist'] = 'Getting blacklist';
$LN['taskgetwhitelist'] = 'Getting whitelist';
$LN['taskexpirespots']  = 'Expiring spots';
$LN['taskpurgespots']   = 'Purging spots';
$LN['taskpostmessage']  = 'Posting message';
$LN['taskdeleteset']    = 'Removing set';

$LN['eta']              = 'ETA';
$LN['inuse']            = 'is in use';
$LN['free']             = 'is free';

// Generic:
$LN['isavailable']      = 'is available';
$LN['apply']            = 'Apply';
$LN['website']          = 'Website';
$LN['or']               = 'or';
$LN['submit']           = 'Submit';
$LN['add']              = 'Add';
$LN['clear']            = 'Clear';
$LN['reset']            = 'Reset';
$LN['search']           = 'Search';
$LN['number']           = 'Number';
$LN['rename']           = 'Rename';
$LN['register']         = 'Register';
$LN['delete']           = 'Delete';
$LN['delete_all']       = 'Delete all';
$LN['time']             = 'Time';

// Setinfo:
$LN['bin_unknown']      = 'Unknown';
$LN['bin_movie']        = 'Movie';
$LN['bin_album']        = 'Album';
$LN['bin_image']        = 'Image';
$LN['bin_software']     = 'Software';
$LN['bin_tvseries']     = 'TV Series';
$LN['bin_ebook']        = 'eBook';
$LN['bin_game']         = 'Game';
$LN['bin_documentary']  = 'Documentary';
$LN['bin_tvshow']       = 'TV Show';
$LN['bin_other']        = 'Other';

// View files:
$LN['files']            = 'files';
$LN['viewfilesheading'] = 'Viewing';
$LN['filename']         = 'File name';
$LN['group']            = 'Group';
$LN['rights']           = 'Rights';
$LN['size']             = 'Size';
$LN['count']            = 'Number';
$LN['type']             = 'Type';
$LN['modified']         = 'Modified';
$LN['owner']            = 'Owner';
$LN['perms']            = 'Rights';
$LN['actions']          = 'Actions';
$LN['uploaded']         = 'Uploaded';
$LN['edit_file']        = 'Edit file';
$LN['viewfiles_title']  = 'View files';
$LN['viewfiles_download']   = 'Download archive';
$LN['viewfiles_uploadnzb']  = 'Download from NZB';
$LN['viewfiles_rename']     = 'rename';
$LN['viewfiles_edit']       = 'edit';
$LN['viewfiles_newfile']    = 'New file';
$LN['viewfiles_savefile']   = 'Save file';
$LN['viewfiles_tarnotset']  = 'The tar command is not set. Downloading archives disabled.';
$LN['viewfiles_compressfailed'] = 'Failed to compress files';

$LN['viewfiles_type_audio'] = 'Audio';
$LN['viewfiles_type_excel'] = 'Excel';
$LN['viewfiles_type_exe']   = 'Exe';
$LN['viewfiles_type_flash'] = 'Flash';
$LN['viewfiles_type_html']  = 'HTML';
$LN['viewfiles_type_iso']   = 'ISO';
$LN['viewfiles_type_php']   = 'PHP';
$LN['viewfiles_type_source']    = 'Source';
$LN['viewfiles_type_picture']   = 'Image';
$LN['viewfiles_type_ppt']       = 'Presentation';
$LN['viewfiles_type_script']    = 'Script';
$LN['viewfiles_type_text']  = 'Text';
$LN['viewfiles_type_video'] = 'Video';
$LN['viewfiles_type_word']  = 'Word';
$LN['viewfiles_type_zip']   = 'Archive';
$LN['viewfiles_type_stylesheet']= 'Stylesheet';
$LN['viewfiles_type_icon']  = 'Icon';
$LN['viewfiles_type_db']    = 'Database';
$LN['viewfiles_type_folder']= 'Folder';
$LN['viewfiles_type_file']  = 'File';
$LN['viewfiles_type_pdf']   = 'PDF';
$LN['viewfiles_type_nzb']   = 'NZB';
$LN['viewfiles_type_par2']  = 'Par2';
$LN['viewfiles_type_sfv']   = 'SFV';
$LN['viewfiles_type_playlist']  = 'Playlist';
$LN['viewfiles_type_torrent']   = 'Torrent';
$LN['viewfiles_type_urdd_sh']   = 'URD script';
$LN['viewfiles_type_ebook']     = 'Ebook';

$LN['newcategory']          = 'New category';
$LN['nocategory']           = 'No category';
$LN['category']             = 'Category';
$LN['categories']           = 'Categories';
$LN['name']                 = 'Name';
$LN['editcategories']       = 'Edit categories';
$LN['ng_tooltip_category']  = 'Category';

// About:
$LN['about_title']  = 'About URD';
$LN['abouttext1']   = 'URD is a web-based application for downloading usenet binaries. It is written entirely in PHP, yet uses some external tools to do some of the dirty CPU intensive work. It stores all the information it needs in a generic database (like MySQL or PostGreSQL). URD supports indexing, using RSS feeds and using Spots. Articles that belong together are aggregated into sets. Downloading files requires only a few mouse clicks, and when the download is finished it can automatically be verified and extracted. Downloading from usenet is as easy as using p2p software!';

$LN['abouttext2']   = 'A strong point of URD is that no external websites are required, as URD generates its own download information. It is possible to create and download an NZB file from specified articles as well.';

$LN['abouttext3']   = 'URD is a backronym of Usenet Resource Downloader. The term URD is derived from Nordic cultures referring
    to the Well of URD, which is the holy well, the Well Spring, the source of water for the world tree Yggdrasil.
    The old English term for it is Wyrd. Conceptually the meaning of URD is closest to Fate.';

$LN['licence_title']        = 'Licence';

// Newsgroup
$LN['ng_title']             = 'Newsgroups';
$LN['ng_posts']             = 'Posts';
$LN['ng_lastupdated']       = 'Updated';
$LN['ng_expire_time']       = 'Expire';
$LN['ng_autoupdate']        = 'Automatic update';
$LN['ng_searchtext']        = 'Search in all available newsgroups';
$LN['ng_newsgroups']        = 'Newsgroups';
$LN['ng_subscribed']        = 'Subscribed';
$LN['ng_tooltip_name']      = 'The name of the newsgroup';
$LN['ng_tooltip_lastupdated']   = 'How long since this newsgroup was updated';
$LN['ng_tooltip_action']    = 'Update/Generate Sets/Expire/Purge';
$LN['ng_tooltip_expire']    = 'The number of days arcticles are kept in the database';
$LN['ng_tooltip_time']      = 'The time at which the auto update will run';
$LN['ng_tooltip_autoupdate']= 'The frequency with which this group is updated automatically';
$LN['ng_tooltip_posts']     = 'The number of articles in this group';
$LN['ng_tooltip_active']    = 'Checked if the newsgroup is subscribed';
$LN['ng_gensets']           = 'Generate sets';
$LN['ng_visible']           = 'Visible';
$LN['ng_minsetsize']        = 'Min/Max set size';
$LN['ng_tooltip_visible']   = 'Is the group visible';
$LN['ng_tooltip_minsetsize']= 'The minimum and maximum number of MB to show a set for this group (0 means no limit)';
$LN['ng_admin_minsetsize']  = 'Spam lower limit';
$LN['ng_admin_maxsetsize']  = 'Set upper limit';
$LN['ng_tooltip_admin_maxsetsize']    = 'The maximum size a set can have to be added to the database - add k, M, G as suffix, e.g. 100k or 25G';
$LN['ng_tooltip_admin_minsetsize']    = 'The minimum size a set must have to be added to the database - add k, M, G as suffix, e.g. 100k or 25G (spam control)';
$LN['ng_hide_empty']        = 'Hide empty groups';
$LN['ng_adult']             = '18+';
$LN['ng_tooltip_adult']     = 'Only accessible when user has 18+ flag set';

$LN['failed']           = 'failed';
$LN['success']          = 'started';
$LN['success2']         = 'success';

$LN['user_settings']    = 'User settings';
$LN['global_settings']  = 'Global settings';

// preferences
//
$LN['change_password']      = 'Change password';
$LN['password_changed']     = 'Password changed';
$LN['delete_account']       = 'Delete account';
$LN['delete_account_msg']   = 'Delete your account';
$LN['account_deleted']      = 'Account deleted';
$LN['pref_title']           = 'Preferences';
$LN['pref_heading']         = 'Personal preferences';
$LN['pref_saved']           = 'Preferences saved';
$LN['pref_language']        = 'Language';
$LN['pref_template']        = 'Template';
$LN['pref_stylesheet']      = 'Stylesheet';
$LN['pref_stylesheet_msg']  = 'The stylesheet used to display URD';
$LN['pref_language_msg']    = 'The language used to display URD';
$LN['pref_template_msg']    = 'The template used to display URD';
$LN['pref_index_page_msg']  = 'The default page to be shown after login';
$LN['pref_index_page']      = 'The default page';
$LN['pref_login']           = 'Login';
$LN['pref_display']         = 'Display';
$LN['pref_spot_spam_limit']      = 'Spam report limit';
$LN['pref_spot_spam_limit_msg']  = 'The number of spam reports with which spots are not displayed';
$LN['pref_downloading']     = 'Downloading';
$LN['pref_spots']           = 'Spots';
$LN['pref_setcompleteness'] = 'Set completeness';
$LN['pref_default_group']       = 'Default group';
$LN['pref_default_group_msg']   = 'Default group to select in the browse page';
$LN['pref_default_feed']        = 'Default feed';
$LN['pref_default_feed_msg']    = 'Default feed to select in the rss sets page';
$LN['pref_default_spot']        = 'Default spot search';
$LN['pref_default_spot_msg']    = 'Default spot search to select in the spots page';

$LN['pref_user_scripts']        = 'Run user scripts';
$LN['pref_user_scripts_msg']    = 'The user defined scripts that are run after completion of a download (note: scripts must end in .urdd_sh)';
$LN['pref_global_scripts']      = 'Run global scripts';
$LN['pref_global_scripts_msg']  = 'The globally defined scripts that are run after completion of a download (note: scripts must end in .urdd_sh)';

$LN['pref_poster_email']     = 'Poster email address';
$LN['pref_poster_name']      = 'Poster name';
$LN['poster_name']           = 'Poster name';
$LN['pref_recovery_size']    = 'Percentage par2 files';
$LN['pref_rarfile_size']     = 'Size of rar files';
$LN['pref_poster_email_msg'] = 'The email address to use in the posted messages';
$LN['pref_poster_name_msg']  = 'The name to use in the posted messages';
$LN['pref_recovery_size_msg']= 'The percentage of recovery files (par2) that will be created (0 for no recovery files)';
$LN['pref_rarfile_size_msg'] = 'The size the rar files will have in kB (0 to disable rarring)';
$LN['pref_posting']     = 'Posting';

$LN['pref_skip_int']         = 'Do not remove interesting sets';
$LN['pref_skip_int_msg']     = 'Do not hide interesting sets when clicking on the remove all sets button';
$LN['pref_level']       = 'User experience level';
$LN['pref_level_msg']   = 'The more experience the user has the more options are shown in configuration (if admin) and preferences';
$LN['level_basic']      = 'Basic';
$LN['level_advanced']   = 'Advanced';
$LN['level_master']     = 'Grandmaster';

$LN['pref_format_dl_dir']       = 'Download directory format';
$LN['pref_format_dl_dir_msg']   = 'Download directory format appended to the basic download name<br/>' .
    '%c: Category<br/>' .
    '%d: Day of the month<br>' .
    '%D: Date<br/>' .
    '%F: Month name (long)<br/>' .
    '%g: Group name<br/>' .
    '%G: Group ID<br/>' .
    '%m: Month (numeric)<br/>' .
    '%M: Month name (short)<br/>' .
    '%n: Set name<br/>' .
    '%N: Genre<br/>' .
    '%s: Download name<br/>' .
    '%u: User name<br/>' .
    '%w: Day of the week<br/>' .
    '%W: Week number<br/>' .
    '%y: Year (2 digits)<br/>' .
    '%Y: Year (4 digits)<br/>' .
    '%x: X-rated<br/>' .
    '%z: Day of the year<br/>';
$LN['pref_add_setname']         = 'Append setname to download directory';
$LN['pref_add_setname_msg']     = 'Append the setname to download directory in addition to the download directory format string';
$LN['pref_download_delay']      = 'Download delay';
$LN['pref_download_delay_msg']  = 'The number of minutes the download is paused before starting';
$LN['username']         = 'Username';
$LN['password']         = 'Password';
$LN['fullname']         = 'Full name';
$LN['email']            = 'Email address';
$LN['newpw']            = 'New password';
$LN['oldpw']            = 'Old password';
$LN['pref_maxsetname']       = 'Max set name length';
$LN['pref_setsperpage']      = 'Maximum number of lines per page';
$LN['pref_minsetsize']       = 'Min set size in MB';
$LN['pref_maxsetsize']       = 'Max set size in MB';
$LN['setsize']          = 'Set size in MB';
$LN['maxage']           = 'Max. age in days';
$LN['minage']           = 'Min. age in days';
$LN['age']              = 'Age in days';
$LN['rating']           = 'Rating (0-10)';
$LN['maxrating']        = 'Max. rating (0-10)';
$LN['minrating']        = 'Min. rating (0-10)';
$LN['complete']         = 'Complete %';
$LN['maxcomplete']      = 'Max. complete %';
$LN['mincomplete']      = 'Min. complete %';
$LN['pref_minngsize']        = 'Min. number of posts in newsgroups';
$LN['config_global_hiddenfiles']      = 'Do not show hidden files';
$LN['config_global_hidden_files_list']= 'List of hidden files';
$LN['pref_hidden_files_list']= 'List of hidden files';
$LN['pref_hiddenfiles']      = 'Do not show hidden files';
$LN['pref_defaultsort']      = 'The field that is used for sorting the sets';
$LN['pref_buttons']          = 'Search buttons';
$LN['pref_unpar']            = 'Automatic run par2';
$LN['pref_unrar']            = 'Automatic decompession of archives';
$LN['pref_delete_files']     = 'Delete files after unrar';
$LN['pref_mail_user']        = 'Send messages';
$LN['pref_show_subcats']     = 'Show subcategories popup for spots';
$LN['pref_show_subcats_msg'] = 'Show a decscription of the subcatogries for a spot in a popup';
$LN['pref_show_image']       = 'Show image for spots';
$LN['pref_show_image_msg']           = 'Show image for spots in extended spot information';
$LN['pref_use_auto_download']        = 'Automatically download';
$LN['pref_use_auto_download_msg']    = 'Automatically download based on search terms';
$LN['pref_use_auto_download_nzb']    = 'Automatically download as NZB file';
$LN['pref_use_auto_download_nzb_msg']    = 'Automatically download as NZB file based on search terms';
$LN['pref_download_text_file']       = 'Download articles without attachments';
$LN['pref_download_text_file_msg']   = 'Download articles text when no attachments is found in the articles';
$LN['pref_search_terms']             = 'Automatically highlight these terms';
$LN['pref_blocked_terms']            = 'Automatically hide these terms';
$LN['spam_reports']             = 'Spam reports';
$LN['pref_setcompleteness_msg'] = 'Sets with completeness percentage of at least this value will be shown is the browse page';
$LN['config_spots_blacklist']   = 'URL for spotter blacklist';
$LN['config_spots_whitelist']   = 'URL for spotter whitelist';
$LN['config_spots_blacklist_msg']   = 'URL that contains a list of IDs of spotters known to be abusers';
$LN['config_spots_whitelist_msg']   = 'URL that contains a list of IDs of spotters known to be valid spotters';
$LN['config_download_spots_images']      = 'Download images for spots';
$LN['config_download_spots_images_msg']  = 'Download images for spots when updating the spots';
$LN['config_download_spots_reports']      = 'Download spam reports for spots';
$LN['config_download_spots_reports_msg']  = 'Download spam reports for spots when updating the spots';
$LN['config_download_spots_comments']      = 'Download comments for spots';
$LN['config_download_spots_comments_msg']  = 'Download comments for spots when updating the spots';
$LN['config_spot_expire_spam_count']    = 'Spam count upper limit after which spots are expired';
$LN['config_spot_expire_spam_count_msg']    = 'Spots are automatically expired after spam count is exceeded for the spot (0 to disable)';
$LN['config_allow_robots']      = 'Allow robots';
$LN['config_allow_robots_msg']  = 'Allow robots to follow and index the URD webpages';
$LN['config_parse_nfo']         = 'Parse nfo files';
$LN['config_nice_value']        = 'Nice value';
$LN['config_nice_value_msg']    = 'Nice value for external programs such as par2 and rar';
$LN['config_max_dl_name']       = 'Maximum length download name';
$LN['config_max_dl_name_msg']   = 'The maximum length of the name used for downloads';
$LN['config_parse_nfo_msg']     = 'Parse nfo files when previewing them';
$LN['config_nntp_maxdlthreads']        = 'Maximum threads per download';
$LN['config_nntp_maxdlthreads_msg']    = 'Maximum number of threads per download (0 is no limit)';
$LN['config_replacement_str']   = 'Download name replacement text';
$LN['config_replacement_str_msg'] = 'Text to replace inappropriate characters in download name with';
$LN['config_maxexpire']	        = 'Maximum expire time';
$LN['config_maxexpire_msg']	    = 'The maximum number of days that can be set as the expire time for newsgroups and rss feeds';
$LN['config_max_login_count']	= 'Maximum failed login attempts';
$LN['config_max_login_count_msg']	= 'Maximum number of times an failed login may appear before the account gets locked';
$LN['config_maxheaders']	    = 'Maximum headers per batch';
$LN['config_maxheaders_msg']	= 'The maximum number of headers that are fetched in one batch';
$LN['config_group_filter']      = 'Newsgroup filter';
$LN['config_group_filter_msg']  = 'Filter for the newsgroups that will be included (use comma to separate items)';
$LN['config_extset_group']      = 'Newsgroup for extsetdata';
$LN['config_extset_group_msg']  = 'The newsgroup where extsetdata will be posted and read';
$LN['config_spots_group']       = 'Newsgroup for spots';
$LN['config_spots_reports_group']       = 'Newsgroup for spots spam reports';
$LN['config_spots_reports_group_msg']   = 'The newsgroup from which spots spam reports will be read';
$LN['config_spots_comments_group_msg']   = 'The newsgroup from which comments for spots will be read';
$LN['config_spots_comments_group']       = 'Newsgroup for spots comments';
$LN['config_spots_group_msg']   = 'The newsgroup where spots will be read';
$LN['config_ftd_group']         = 'Newsgroup for spots NZB files';
$LN['config_ftd_group_msg']     = 'The newsgroup where NZB files from spots can be found';
$LN['pref_mail_user_sets']          = 'Mail interesting sets';
$LN['pref_mail_user_sets_msg']      = 'Send a message if a new interesting set has been found';
$LN['config_poster_blacklist']      = 'Posters to black list';
$LN['config_poster_blacklist_msg']  = 'Posters whose name or email match with the regular expression on these lines are excluded from the sets database';

$LN['config_index_page_root_msg']   = 'The default page to be shown after login';
$LN['config_index_page_root']       = 'The default page';
$LN['config_queue_size']            = 'Queue size';
$LN['config_queue_size_msg']        = 'Maximum number of tasks that can be in the queue';
$LN['pref_subs_lang_msg']           = 'Languages for which subtitles will be sought (two letter codes, separated by commas, leave blank to disable)';
$LN['pref_subs_lang']               = 'Subtitle languages';
$LN['config_keystore_path']         = 'Location of the key store';
$LN['config_keystore_path_msg']     = 'The directory where the key store will be placed';

$LN['username_msg']                 = 'The user as which you are logged in';
$LN['newpw1_msg']                   = 'Your new password';
$LN['newpw2_msg']                   = 'Your new password again';
$LN['oldpw_msg']                    = 'Your current password';
$LN['pref_maxsetname_msg']          = 'The maximum size of a setname to be displayed on a page';
$LN['pref_setsperpage_msg']         = 'The number of sets that will be displayed on one page';
$LN['pref_minsetsize_msg']          = 'The minimum size a set must have to show in the overview; smaller sets are ignored';
$LN['pref_maxsetsize_msg']          = 'The maximum size a set must have to show in the overview; larger sets are ignored';
$LN['pref_minngsize_msg']           = 'The minimum number of posts a newsgroup must have to show in the overview';
$LN['pref_hiddenfiles_msg']         = 'When enabled hidden files will not be shown in the files viewer';
$LN['config_global_hiddenfiles_msg']        = 'When enabled hidden files will not be shown in the files viewer';
$LN['config_global_hidden_files_list_msg']  = 'List of files that will be hidden in the files viewer. Separate by newlines, use * and ? as wildcards';
$LN['pref_hidden_files_list_msg']           = 'List of files that will be hidden in the files viewer. Separate by newlines, use * and ? as wildcards';

$LN['pref_defaultsort_msg']  = 'The field that is used for sorting the sets';
$LN['pref_buttons_msg']      = 'Search buttons in the browse section';
$LN['pref_download_par']     = 'Always download par2 files';
$LN['pref_download_par_msg'] = 'When disabled only download par2 files if they are needed, otherwise always download them anyway';
$LN['pref_unpar_msg']        = 'When enabled and the set contains par2 files these will be automatically used to verify and if needed to correct the downloaded files';
$LN['pref_unrar_msg']        = 'When enabled all rar archives will be automatically extracted';
$LN['pref_delete_files_msg'] = 'When enabled and the rar command was successful, all rar and par2 files will be removed';
$LN['pref_mail_user_msg']    = 'Send a message if a download has completed';
$LN['pref_search_terms_msg'] = 'Automatically match these search terms against all subscribed groups (separate by newlines) and highlight them';
$LN['pref_blocked_terms_msg']= 'Automatically match these search terms against all subscribed groups (separate by newlines) and hide them';

$LN['descending']       = 'Descending';
$LN['ascending']        = 'Ascending';

$LN['pref_basket_type']         = 'Download basket type';
$LN['pref_basket_type_msg']     = 'The type of download basket that is used by default';
$LN['basket_type_small']        = 'Compact';
$LN['basket_type_large']        = 'Extended';
$LN['pref_search_type']         = 'Search type';
$LN['pref_search_type_msg']     = 'The database search type that is used by the search terms matching';
$LN['search_type_like']     = 'Simple wildcards (LIKE)';
$LN['search_type_regexp']   = 'Regular expressions (REGEXP)';

$LN['settings_imported']	= 'Settings imported';
$LN['settings_import']		= 'Import settings';
$LN['settings_export']		= 'Export settings';
$LN['settings_import_file']	= 'Import settings from file';
$LN['settings_notfound']	= 'File not found or no settings found';
$LN['settings_upload']		= 'Upload settings';
$LN['settings_filename']	= 'File name';

$LN['import_servers']		= 'Import servers';
$LN['export_servers']		= 'Export servers';
$LN['import_groups']		= 'Import groups';
$LN['export_groups']		= 'Export groups';
$LN['import_feeds']		    = 'Import feeds';
$LN['export_feeds']		    = 'Export feeds';
$LN['import_users']		    = 'Import users';
$LN['export_users']		    = 'Export users';
$LN['import_buttons']		= 'Import search options';
$LN['export_buttons']		= 'Export search options';

$LN['config_modules']         = 'Modules';
$LN['config_module_groups']   = 'Indexing groups';
$LN['config_module_makenzb']  = 'Creating NZB files';
$LN['config_module_usenzb']   = 'Importing NZB files';
$LN['config_module_post']     = 'Posting to groups';
$LN['config_module_spots']    = 'Reading spots';
$LN['config_module_rss']      = 'RSS feeds support';
$LN['config_module_sync']     = 'Synchronising extended set information';
$LN['config_module_download'] = 'Downloading from newsgroups';
$LN['config_module_viewfiles'] = 'File browser';

$LN['config_module_groups_msg']   = 'Indexing groups';
$LN['config_module_makenzb_msg']  = 'Support for creating NZB files';
$LN['config_module_usenzb_msg']   = 'Support for downloading from NZB files';
$LN['config_module_spots_msg']    = 'Reading spots from the newsgroup server';
$LN['config_module_post_msg']     = 'Posting to groups';
$LN['config_module_rss_msg']      = 'RSS feeds support';
$LN['config_module_sync_msg']     = 'Synchronising extended set information';
$LN['config_module_download_msg'] = 'Downloading from newsgroups';
$LN['config_module_viewfiles_msg'] = 'Internal file browser';

$LN['config_urdd_uid']      = 'User ID of urdd';
$LN['config_urdd_gid']      = 'Group ID of urdd';
$LN['config_urdd_uid_msg']  = 'The user ID to which urdd will change when started as root (leave blank for no changing)';
$LN['config_urdd_gid_msg']  = 'The group ID to which urdd will change when started as root (leave blank for no changing)';

$LN['transfers_add_setname']         = 'Append setname to download directory';

// pref errors
$LN['error_pwmatch']        = 'Passwords do not match';
$LN['error_pwincorrect']    = 'Password incorrect';
$LN['error_pwusername']     = 'Password looks too much like the username';
$LN['error_pwlength']       = 'Password too short; at least '. MIN_PASSWORD_LENGTH . ' characters required';
$LN['error_pwsimple']       = 'Password too simple, use a mix of upper and lower case characters, numbers and other characters';
$LN['error_captcha']        = 'CAPTCHA incorrect';

$LN['error_feedexists']     = 'An RSS feed with that name already exists';
$LN['error_encryptedrar']   = 'Encrypted rar file';
$LN['error_usercancel']     = 'Cancelled by user';
$LN['error_onlyforgrops'] 	= 'Only works for groups';
$LN['error_onlyoneset'] 	= 'Requires more than one set to be in the basket';

$LN['error_linknotfound'] 	= 'Link not found';
$LN['error_nzbfailed'] 	    = 'Importing NZB file failed';
$LN['error_downloadnotfound'] 	= 'Download not found';
$LN['error_toomanybuttons']     = 'Too many search options';
$LN['error_invalidbutton']      = 'Invalid search options';
$LN['error_invalidpassword']    = 'Invalid password';
$LN['error_userexists']         = 'User already exists';
$LN['error_acctexpired']        = 'Account expired';
$LN['error_notleftblank']       = 'May not be left blank';
$LN['error_invalidvalue']       = 'Invalid value';
$LN['error_urlstart']           = 'The url needs to start with http:// and end with a /';
$LN['error_error']              = 'Error';
$LN['error_invaliddir']         = 'Invalid directory';
$LN['error_notmakedir']         = 'Could not create directory';
$LN['error_notmaketmpdir']      = 'Could not create tmp directory';
$LN['error_notmakepreviewdir']  = 'Could not create preview directory';
$LN['error_dirnotwritable']     = 'Directory not writable';
$LN['error_notestfile']         = 'Could not create test files';
$LN['error_mustbemore']         = 'must be more than';
$LN['error_mustbeless']         = 'must be less than or equal to';
$LN['error_filenotexec']        = 'The file cannot be found or is not executable by the webserver';
$LN['error_noremovedir']        = 'Cannot remove directory';
$LN['error_noremovefile']       = 'Cannot remove file';
$LN['error_noremovefile2']      = 'Cannot remove file; directory not writable';
$LN['error_nodeleteroot']       = 'Cannot delete root user';
$LN['error_nosetids']           = 'No setIDs given!';
$LN['error_invalidstatus']      = 'Invalid status value supplied';
$LN['error_invaliduserid']      = 'Invalid userID';
$LN['error_groupnotfound']      = 'Group not found';
$LN['error_invalidgroupid']     = 'Invalid group ID specified';
$LN['error_couldnotreadargs']   = 'Could not read cmd args (register_argc_argv=Off?)';
$LN['error_resetnotallowed']    = 'Not allowed to reset configuration';
$LN['error_prefnotfound']       = 'Preference not found';
$LN['error_invalidfilename']    = 'Invalid file name';
$LN['error_fileexists']         = 'File already exists';
$LN['error_cannotrename']       = 'Cannot rename file';
$LN['error_needfilenames']      = 'File name needed';
$LN['error_usenetserverexists'] = 'A server with that name already exists';
$LN['error_missingconnection']  = 'Invalid connection type given';
$LN['error_missingthreads']     = 'Threads must be given';
$LN['error_missinghostname']    = 'Hostname must be entered';
$LN['error_missingname']        = 'Name must be entered';
$LN['error_needatleastoneport'] = 'At least one port number must be given';
$LN['error_needsecureport']     = 'Secure port needed for encrypted connection';
$LN['error_nosuchserver']       = 'Server does not exist';
$LN['error_invalidaction']      = 'Unknown action';
$LN['error_nameexists']         = 'A usenet server with that name already exists';
$LN['error_diskfull']           = 'Insufficient disk space expected to complete download';
$LN['error_invalidsetid']       = 'Invalid set ID given';
$LN['error_couldnotsendmail']   = 'Could not send message';
$LN['error_filetoolarge']       = 'File too large to download';
$LN['error_preview_size_exceeded']      = 'File too large to preview';
$LN['error_post_not_found']     = 'Post not found';
$LN['error_pwresetnomail']      = 'Password reset, but could not send email';
$LN['error_userupnomail']       = 'User updated, but could not send email';
$LN['error_nowrite']            = 'Could not write file';
$LN['error_namenotfound']       = 'Name not found';
$LN['error_nameexists']         = 'Search name already exists';
$LN['error_missingparameter']   = 'Missing parameter';
$LN['error_nouploaddata']       = 'No content found in';

$LN['error_nosetsfound']            = 'No sets found';
$LN['error_nousersfound']           = 'No users found';
$LN['error_noserversfound']         = 'No servers found';
$LN['error_nouploadsfound']         = 'No uploads found';
$LN['error_nodownloadsfound']       = 'No downloads found';
$LN['error_nogroupsfound']          = 'No groups found';
$LN['error_nosearchoptionsfound']   = 'No search options found';
$LN['error_nofeedsfound']           = 'No feeds found';
$LN['error_notasksfound']           = 'No tasks found';
$LN['error_nojobsfound']            = 'No jobs found';
$LN['error_nologsfound']            = 'No logs found';

$LN['error_schedulesnotset']        = 'Schedules could not be set';
$LN['error_unknowntype']            = 'Unknown type';
$LN['error_emptybasket']            = 'Empty basket';

// Admin pages:
$LN['adminshutdown']        = 'Shut down the URD Daemon';
$LN['adminrestart']	    	= 'Restart URD Daemon';
$LN['adminpause']           = 'Pause all activities';
$LN['admincontinue']        = 'Continue all activities';
$LN['adminclear']           = 'Clear all downloads';
$LN['admincleandb']         = 'Clear ALL volatile information';
$LN['adminremoveready']     = 'Clear only completed download information';
$LN['adminpoweron']         = 'Power on the URD Daemon';
$LN['adminupdatenglist']    = 'Update the newsgroup list';
$LN['adminupdateallngs']    = 'Update all newsgroups';
$LN['admingensetsallngs']   = 'Generate sets for all newsgroups';
$LN['adminexpireallngs']    = 'Expire all newsgroups';
$LN['adminpurgeallngs']     = 'Purge all newsgroups';
$LN['adminexpireallrss']    = 'Expire all feeds';
$LN['adminpurgeallrss']     = 'Purge all feeds';
$LN['adminupdateallrss']    = 'Update all feeds';
$LN['adminoptimisedb']      = 'Optimise the database';
$LN['admincheckversion']    = 'Check URD version';
$LN['admingetsetinfo']      = 'Get set information';
$LN['adminsendsetinfo']     = 'Send set information';
$LN['admincleandir']        = 'Clean directories';
$LN['adminfindservers']     = 'Autoconfig usenet servers';
$LN['adminfindservers_ext'] = 'Autoconfig usenet servers (extended)';
$LN['adminexport_all']      = 'Export all settings';
$LN['adminimport_all']      = 'Import all settings';
$LN['adminupdate_spots']    = 'Update spots';
$LN['adminupdate_spotscomments']    = 'Update spots comments';
$LN['adminupdate_spotsimages']    = 'Update spots images';
$LN['adminexpire_spots']    = 'Expire spots';
$LN['adminpurge_spots']     = 'Purge spots';

// register
$LN['reg_disabled']     = 'Registration is disabled';
$LN['reg_title']        = 'Account registration';
$LN['reg_codesent']     = 'Your activation code has been sent';
$LN['reg_status']       = 'Registration status';
$LN['reg_activated']    = 'Your account is activated. Proceed to';
$LN['reg_activated_link'] = 'log in';
$LN['reg_pending']      = 'Your account is pending. Please wait until the admin enables you.';
$LN['reg_form']         = 'Fill in the form to obtain an account';
$LN['reg_again']        = 'again';
//$LN['reg_']           = '';

//admin controls
$LN['control_title']        = 'Daemon Control';
$LN['control_options']      = 'Options';
$LN['control_jobs']         = 'Jobs';
$LN['control_threads']      = 'Threads';
$LN['control_queue']        = 'Queue';
$LN['control_servers']      = 'Servers';
$LN['control_uptime']       = 'Uptime';
$LN['control_load']         = 'System load';
$LN['control_diskspace']    = 'Disk space';
$LN['control_cancelall']    = 'Cancel all tasks';
//$LN['control_']           = '';

/// posting
$LN['post_subject']         = 'Subject';
$LN['post_delete_files']    = 'Delete files';
$LN['post_delete_filesext'] = 'Delete temporary files created (e.g. rar and par2 files)';
$LN['post_postername']      = 'Name of poster';
$LN['post_posteremail']     = 'Email address of poster';
$LN['post_recovery']        = 'Recovery percentage';
$LN['post_rarfiles']        = 'Rarfile size';
$LN['post_newsgroup']       = 'Newsgroup';
$LN['post_post']            = 'Upload';
$LN['post_directory']       = 'Directory';
$LN['post_directoryext']    = 'The directory that will be uploaded';
$LN['post_subjectext']      = 'The subject line in the messages';
$LN['post_posternameext']   = 'The name of the poster in the messages (from)';
$LN['post_posteremailext']  = 'The email address of the poster in the messages (from)';
$LN['post_recoveryext']     = 'The percentage of par2 files to generate';
$LN['post_rarfilesext']     = 'The size of the compressed rar files in kilobytes';
$LN['post_newsgroupext']    = 'The newsgroup the messages will be posted to';

//admin jobs

$LN['jobs_title']       = 'Scheduled jobs';
$LN['jobs_command']     = 'Command';
$LN['jobs_period']      = 'Period';
$LN['jobs_user']        = 'User';

// admin tasks
$LN['tasks_title']          = 'Tasks';
$LN['tasks_description']    = 'Description';
$LN['tasks_progress']       = 'Progress';
$LN['tasks_added']          = 'Added';
$LN['tasks_lastupdated']    = 'Last updated';
$LN['tasks_comment']        = 'Comment';
//$LN['tasks_']             = '';

// admin config
$LN['config_title']         = 'Configuration';
$LN['config_setinfo']       = 'Set updating';
$LN['config_urdd_head']     = 'URD Daemon';
$LN['config_nntp_maxthreads']       = 'Maximum number of NNTP connections';
$LN['config_urdd_maxthreads']       = 'Maximum number of total threads';
$LN['config_default_expire_time']   = 'Default expire time (in days)';
$LN['config_spots_expire_time']     = 'Expire time for spots (in days)';
$LN['config_spots_expire_time_msg'] = 'Expire time for spots (in days); note this overwrites the values set for the respective newsgroup';
$LN['config_expire_incomplete']     = 'Expire time for incomplete sets (in days, 0 to disable)';
$LN['config_expire_percentage']     = 'Percentage completeness for early expiration of sets';
$LN['config_auto_expire']           = 'Expire after update';
$LN['pref_cancel_crypted_rars']     = 'Cancel encrypted downloads';
$LN['config_auto_getnfo']	        = 'Auto-download of nfo files';
$LN['config_auto_getnfo_msg']       = 'Automatically download and parse nfo files after updating a newsgroup';
$LN['config_period_getspots']	    = 'Download spots';
$LN['config_period_getspots_msg']	= 'Schedule when spots will be downloaded';
$LN['config_period_getspots_blacklist']	    = 'Download spots blacklist';
$LN['config_period_getspots_blacklist_msg']	= 'Schedule when the spots blacklist will be downloaded';
$LN['config_period_getspots_whitelist']	    = 'Download spots whitelist';
$LN['config_period_getspots_whitelist_msg']	= 'Schedule when the spots whitelist will be downloaded';
$LN['config_clickjack']             = 'Enable clickjack prevention';
$LN['config_clickjack_msg']         = 'Enable clickjack prevention to ensure that URD is only accessed in a full page and not in a frame';
$LN['config_need_challenge']        = 'Enable XSS prevention';
$LN['config_need_challenge_msg']    = 'Enable cross-site scripting prevention to ensure that URD functions cannot be exploited from other sites';
$LN['config_use_encrypted_passwords'] = 'Store usenet passwords encrypted';
$LN['config_use_encrypted_passwords_msg'] = 'Passwords are stored in an encrypted format; using a keystore separate file to store the key';

$LN['config_pidpath']       = 'Location of the PID file';
$LN['config_pidpath_msg']   = 'The location of the PID file used to prevent starting multiple instances of URDD (leave blank for none)';
$LN['config_dlpath']        = 'Download directory';
$LN['config_dlpath_msg']    = 'The path where to URD will download all the files';
$LN['config_urdd_host']     = 'URDD hostname';
$LN['config_urdd_port']     = 'URDD port';
$LN['config_urdd_restart']       = 'Restart old tasks';
$LN['config_urdd_daemonise']     = 'Start URDD as a background process';
$LN['config_urdd_daemonise_msg'] = 'Start URDD as a background process (daemon)';
$LN['config_admin_email']   = 'Administrator email';
$LN['config_baseurl']       = 'Base url';
$LN['config_shaping']       = 'Enable traffic shaping';
$LN['config_maxdl']         = 'Maximum download bandwidth (kB/s) per connection';
$LN['config_maxul']         = 'Maximum upload bandwidth (kB/s) per connection';
$LN['config_maxfilesize']   = 'Maximum filesize to view in viewfiles';
$LN['config_maxpreviewsize']= 'Maximum filesize to preview';
$LN['config_register']      = 'Permit registration';
$LN['config_auto_reg']      = 'Automatically accept account';
$LN['config_urdd_path']          = 'urdd';
$LN['config_unpar_path']         = 'par2';
$LN['config_unrar_path']         = 'unrar';
$LN['config_rar_path']           = 'rar';
$LN['config_unace_path']         = 'unace';
$LN['config_tar_path']           = 'tar';
$LN['config_un7zr_path']         = 'un7za';
$LN['config_unzip_path']         = 'unzip';
$LN['config_gzip_path']          = 'gzip';
$LN['config_unarj_path']         = 'unarj';
$LN['config_subdownloader_path'] = 'subdownloader';
$LN['config_subdownloader_path_msg']  = 'The path where the program subdownloader can be found (optional)';
$LN['config_file_path']          = 'file';
$LN['config_yydecode_path']      = 'yydecode';
$LN['config_yyencode_path']      = 'yyencode';
$LN['config_cksfv_path']         = 'cksfv';
$LN['config_trickle_path']       = 'trickle';
$LN['config_period_update']      = 'Check for updates of URD';
$LN['config_period_opt']         = 'Optimise database';
$LN['config_period_ng']          = 'Update newsgroup list';
$LN['config_period_cd']          = 'Clean preview and tmp directory';
$LN['config_period_cu']          = 'Period of inactive users';
$LN['config_period_cu_msg']      = 'Period of inactivity of non-admin users after which they will be removed in days';
$LN['config_users_clean_age']      = 'Clean inactive users';
$LN['config_users_clean_age_msg']  = 'Clean inactive, non-admin users after a period of inactivity (in days)';
$LN['config_clean_dir_age']     = 'Age of removed files';
$LN['config_clean_dir_age_msg'] = 'The age a file must have before it is removed by the clean dir command (in days)';
$LN['config_clean_db_age']      = 'Age of volatile database info';
$LN['config_clean_db_age_msg']  = 'The age a database information must have before it is removed by the clean database command (in days; 0 is disabled)';
$LN['config_period_cdb']        = 'Clean database of volatile information';
$LN['config_scheduler']         = 'URDD Scheduler';
$LN['config_networking']        = 'Networking';
$LN['config_extprogs']          = 'Programs';
$LN['config_maintenance']       = 'Maintenance';
$LN['config_globalsettings']    = 'Global settings';
$LN['config_notifysettings']    = 'Notify settings';
$LN['config_webdownload']       = 'Allow download in web interface';
$LN['config_webeditfile']	    = 'Allow editing files in web interface';
$LN['config_webeditfile_msg']	= 'Users can edit files in the view files page';
$LN['config_socket_timeout']    = 'Socket timeout';
$LN['config_socket_timeout_msg']= 'The number of seconds after which a socket will timeout and the connection is closed';
$LN['config_urdd_connection_timeout']       = 'URDD connection timeout';
$LN['config_urdd_connection_timeout_msg']   = 'The number of seconds after which a connection to URDD will timeout and is closed; defaults to 30';
$LN['config_auto_download']                 = 'Allow automatic downloading';
$LN['config_check_nntp_connections']        = 'Check usenet connections at startup';
$LN['config_check_nntp_connections_msg']    = 'Select the number of possible concurrent connections to an NNTP server automatically at startup';
$LN['config_db_intensive_maxthreads']       = 'Maximum database intensive threads';
$LN['config_db_intensive_maxthreads_msg']   = 'The maximum number of threads that require heavy access to the database';
$LN['config_auto_login']                    = 'Automatically login in as';
$LN['config_auto_login_msg']                = 'Automatically login in as the specified user. Leave blank to disable';

$LN['config_allow_global_scripts_msg']      = 'Allow scripts set by administrators to run after completion of a download';
$LN['config_allow_global_scripts']          = 'Allow global scripts';
$LN['config_allow_user_scripts_msg']        = 'Allow scripts set by users to run after completion of a download';
$LN['config_allow_user_scripts']            = 'Allow user scripts';

$LN['config_compress_nzb']      = 'Compress NZB files';
$LN['config_compress_nzb_msg']  = 'Compress NZB files after downloading them';
$LN['config_urdd_pars']         = 'urdd';
$LN['config_unpar_pars']        = 'par2';
$LN['config_unrar_pars']        = 'unrar';
$LN['config_rar_pars']          = 'rar';
$LN['config_unace_pars']        = 'unace';
$LN['config_tar_pars']          = 'tar';
$LN['config_un7zr_pars']        = 'un7za';
$LN['config_unzip_pars']        = 'unzip';
$LN['config_gzip_pars']         = 'gzip';
$LN['config_unarj_pars']        = 'unarj';
$LN['config_yydecode_pars']     = 'yydecode';
$LN['config_yyencode_pars']     = 'yyencode';

$LN['config_subdownloader_pars'] 		= 'subdownloader';
$LN['config_subdownloader_pars_msg'] 	= 'subdownloader parameters';
$LN['config_webdownload_msg']           = 'Users can download files as tarballs in view files page';
$LN['config_maxfilesize_msg']           = 'The maximum filesize to view in viewfiles in kB (0 for no limit)';
$LN['config_maxpreviewsize_msg']        = 'The maximum filesize to preview in kB (0 for no limit)';
$LN['config_mail_account_activated']        = 'Account activated message';
$LN['config_mail_account_activated_msg']    = 'Mail sent to the user when the account has been activated';

$LN['config_mail_account_activated']        = 'Account activated message';
$LN['config_mail_account_activated_msg']    = 'Mail sent to the user when the account has been activated';
$LN['config_mail_account_disabled']         = 'Account disabled message';
$LN['config_mail_account_disabled_msg']     = 'Mail sent to the user when the account has been disabled';
$LN['config_mail_activate_account']         = 'Activate account message';
$LN['config_mail_activate_account_msg']     = 'Mail sent to the user when the account has to be activated';
$LN['config_mail_download_status']          = 'Download status message';
$LN['config_mail_download_status_msg']      = 'Mail sent to the user when the download was finished';
$LN['config_mail_new_interesting_sets']     = 'New interensting sets message';
$LN['config_mail_new_interesting_sets_msg'] = 'Mail sent to the user when new sets have been marked as interesting';
$LN['config_mail_new_preferences']          = 'New preferences message';
$LN['config_mail_new_preferences_msg']      = 'Mail sent to the user when a new preference has been added to URD';
$LN['config_mail_new_user']                 = 'New user message';
$LN['config_mail_new_user_msg']             = 'Mail sent to the administrator when a new user has registered';
$LN['config_mail_password_reset']           = 'Password reset message';
$LN['config_mail_password_reset_msg']       = 'Mail sent to the user with the new password';

$LN['config_default_stylesheet']         = 'Default stylesheet';
$LN['config_default_stylesheet_msg']     = 'The stylesheet used when none is selected or one cannot be found';
$LN['config_default_template']           = 'Default template';
$LN['config_default_template_msg']       = 'The template used when none is selected or one cannot be found';
$LN['config_default_language_msg']       = 'The language used when none is selected or one cannot be found';
$LN['config_default_language']           = 'Default language';
$LN['config_scheduler_msg']              = 'Enable scheduling of automatic jobs in URDD';
$LN['config_log_level']                  = 'Log level';
$LN['config_permissions_msg']            = 'Default permissions for downloaded files';
$LN['config_permissions']       = 'Download permissions';
$LN['config_group']             = 'Group';
$LN['config_group_msg']         = 'The system group for all downloaded files';
$LN['config_maxbuttons']        = 'Maximum number of search options';
$LN['config_maxbuttons_msg']    = 'The maximum number of search options that are shown on the browse page';
$LN['config_nntp_maxthreads_msg']       = 'The number of parallel connections that the URD daemon can use';
$LN['config_urdd_maxthreads_msg']       = 'The number of parallel tasks that the URD daemon will carry out';
$LN['config_default_expire_time_msg']   = 'The default number of days after which sets will be regarded expired';
$LN['config_expire_incomplete_msg']     = 'The default number of days after which incomplete sets will be regarded expired';
$LN['config_expire_percentage_msg']     = 'The upperbound percentage a set may have to be regarded incomplete for early expiration';
$LN['config_auto_expire_msg']           = 'Old messages will be removed after an update is completed';
$LN['pref_cancel_crypted_rars_msg']     = 'Analyse files as they are downloaded, and cancel the download if an encrypted RAR file is detected and the password is not known';
$LN['config_urdd_host_msg']             = 'The hostname or IP address of the URD daemon; defaults to localhost (note IPv6 addresses need to be enclosed by [] e.g. [::1])';
$LN['config_urdd_port_msg']             = 'The port number of the URD daemon; defaults to 11666';
$LN['config_urdd_restart_msg']          = 'Tasks that were running when the URD daemon crashed will be restarted if this button is checked';
$LN['config_admin_email_msg']           = 'The email address of the administrator';
$LN['config_baseurl_msg']       = 'The base URL of your URD website';
$LN['config_shaping_msg']       = 'Use traffic shaping to limit the bandwidth used by urdd';
$LN['config_maxdl_msg']         = 'The maximum bandwidth the URD daemon will use to download from the news server';
$LN['config_maxul_msg']         = 'The maximum bandwidth the URD daemon will use to upload to the news server';
$LN['config_register_msg']      = 'If checked registration by users is possible from the login page';
$LN['config_auto_reg_msg']      = 'If not checked the administrator has to permit the account manually, otherwise the account is accepted automatically';
$LN['config_urdd_path_msg']     = 'The path where the URD daemon start up file can be found (urdd.sh)';
$LN['config_unpar_path_msg']     = 'The path where the program par2 can be found (optional)';
$LN['config_unrar_path_msg']     = 'The path where the program rar or unrar can be found for extraction (optional)';
$LN['config_rar_path_msg']       = 'The path where the program rar can be found for compression (optional)';
$LN['config_tar_path_msg']       = 'The path where the program tar can be found (optional)';
$LN['config_unace_path_msg']     = 'The path where the program unace can be found (optional)';
$LN['config_un7zr_path_msg']     = 'The path where the program 7za, 7zr or 7z can be found (optional)';
$LN['config_unzip_path_msg']     = 'The path where the program unzip can be found (optional)';
$LN['config_gzip_path_msg']      = 'The path where the program gzip can be found (optional)';
$LN['config_unarj_path_msg']     = 'The path where the program unarj can be found (optional)';
$LN['config_file_path_msg']      = 'The path where the program file can be found';
$LN['config_yydecode_path_msg']  = 'The path where the program yydecode can be found';
$LN['config_yyencode_path_msg']  = 'The path where the program yyencode can be found';
$LN['config_cksfv_path_msg']     = 'The path where the program cksfv can be found (optional)';
$LN['config_trickle_path_msg']   = 'The path where the program trickle can be found (optional)';
$LN['config_period_update_msg']  = 'The frequency with which the availability of a new version is checked';
$LN['config_period_opt_msg']     = 'The frequency with which the database is optimised';
$LN['config_period_ng_msg']      = 'The frequency with which the newsgroup list is updated';
$LN['config_period_cd_msg']      = 'The frequency with which the /preview and /tmp directory are cleared';
$LN['config_period_cdb_msg']     = 'The frequency with which the volatile information is removed from the database';
$LN['config_log_level_msg']      = 'The log level of the URD daemon';
$LN['config_period_sendinfo']      = 'Send set infomation';
$LN['config_period_sendinfo_msg']  = 'Send information to URDland.com';
$LN['config_period_getinfo']       = 'Get set information';
$LN['config_period_getinfo_msg']   = 'Get information from URDland.com';
$LN['config_keep_interesting']     = 'Keep interesting articles on expire';
$LN['config_keep_interesting_msg'] = 'Keep articles marked interesting when expiring sets';
$LN['config_auto_download_msg']    = 'Permit users to automatically download based on search terms';
$LN['config_sendmail']             = 'Allow e-mails to be sent';
$LN['config_sendmail_msg']         = 'If checked, e-mails may be sent for things like forgotten and resetting passwords, completed downloads.';
$LN['config_follow_link']          = 'Follow links in NFO files when updating';
$LN['config_follow_link_msg']      = 'If checked, links in NFO files are automatically parsed after group updating';
$LN['config_total_max_articles']        = 'Maximum articles downloaded per update';
$LN['config_total_max_articles_msg']    = 'Maximum number of articles that is downloaded per update (0 is no limit)';

$LN['config_prog_params']           = 'Parameters';

$LN['config_urdd_pars_msg']         = 'urdd parameters';
$LN['config_unpar_pars_msg']        = 'par2 parameters';
$LN['config_unrar_pars_msg']        = 'rar extraction parameters';
$LN['config_rar_pars_msg']          = 'rar compression parameters';
$LN['config_unace_pars_msg']        = 'unace parameters';
$LN['config_tar_pars_msg']          = 'tar parameters';
$LN['config_un7zr_pars_msg']        = 'un7za parameters';
$LN['config_unzip_pars_msg']        = 'unzip parameters';
$LN['config_gzip_pars_msg']         = 'gzip parameters';
$LN['config_unarj_pars_msg']        = 'unarj parameters';
$LN['config_yydecode_pars_msg']     = 'yydecode parameters';
$LN['config_yyencode_pars_msg']     = 'yyencode parameters';

$LN['config_perms']['none'] = 'Do not change';
$LN['config_perms']['0400'] = 'Owner read only (0400)';
$LN['config_perms']['0400'] = 'Owner read only (0400)';
$LN['config_perms']['0440'] = 'Owner and group read only (0440)';
$LN['config_perms']['0444'] = 'Read everybody (0444)';
$LN['config_perms']['0600'] = 'Owner read &amp; write (0600)';
$LN['config_perms']['0640'] = 'Owner read &amp; write, group read only (0640)';
$LN['config_perms']['0644'] = 'Owner read &amp; write, rest read only (0644)';
$LN['config_perms']['0660'] = 'Owner and group read &amp; write (0660)';
$LN['config_perms']['0664'] = 'Owner and group read &amp; write, rest read only (0664)';
$LN['config_perms']['0666'] = 'Read &amp; write everybody (0666)';

// admin log
$LN['log_title']        = 'Log file';
$LN['log_nofile']       = 'No log file found';
$LN['log_seekrror']     = 'Could not read entire file';
$LN['log_unknownerror'] = 'An unexpected error occurred';
$LN['log_header']       = 'Log info';
$LN['log_date']         = 'Date';
$LN['log_level']        = 'Level';
$LN['log_msg']          = 'Message';
$LN['log_lines']        = 'Lines';
$LN['log_notopenlogfile']   = 'Could not open log file';

// FAQ
$LN['faq_title']        = 'FAQ';

//Manual
$LN['manual_title']     = 'Manual';

//admin users
$LN['users_title']          = 'users';
$LN['users_isadmin']        = 'Admin';
$LN['users_autodownload']   = 'Allow autodownload';
$LN['users_fileedit']       = 'Edit files';
$LN['users_post']           = 'Uploader';
$LN['users_post_help']      = 'This user may post to the news server';
$LN['users_resetpw']        = 'Reset and mail password';
$LN['users_edit']           = 'Modify user';
$LN['users_allow_erotica']  = 'Allow Adult content';
$LN['users_allow_update']   = 'Allow updating databases';
$LN['users_addnew']         = 'Add new user';
$LN['users_delete']         = 'Delete user';
$LN['users_rights']         = 'Set editor';
$LN['users_rights_help']    = 'Allows this user to edit set information in the Browse page';
$LN['users_last_active']    = 'Last active';

$LN['error_noadmin']            = 'No administrator privileges';
$LN['error_accessdenied']       = 'Access denied';
$LN['error_invalidfullname']    = 'Invalid fullname';
$LN['error_invalidusername']    = 'Invalid username';
$LN['error_invalidemail']       = 'Invalid email address';
$LN['error_userexists']         = 'User already exists';
$LN['error_invalidid']          = 'Invalid ID given';
$LN['error_nosuchuser']         = 'User does not exists';
$LN['error_nouserid']           = 'No user ID given';
$LN['error_invalidchallenge']   = 'Possibly a cross site request forgery has been carried out. Action was cancelled. (Press reload and try again)';
$LN['error_toomanydays']        = 'There are only 24 hours a day';
$LN['error_toomanymins']        = 'There are only 60 minutes in an hour';
$LN['error_bogusexptime']       = 'Bogus expiry time entered';
$LN['error_invalidupdatevalue'] = 'Invalid update value received';
$LN['error_nodlpath']           = 'Download path not set';
$LN['error_dlpathnotwritable']  = 'Download path not writable';
$LN['error_setithere']          = 'Set it here';
$LN['error_nousers']            = 'No users found, please re-run the install script';
$LN['error_filenotallowed']     = 'Not allowed to access file';
$LN['error_filenotfound']       = 'File not found';
$LN['error_filereaderror']      = 'File could not be read';
$LN['error_dirnotfound']        = 'Cannot open directory';
$LN['error_unknown_sort']       = 'Unknown sort order';
$LN['error_invalidlinescount']  = 'Lines must be numeric';
$LN['error_urddconnect']        = 'Could not connect to URD daemon';
$LN['error_createdlfailed']     = 'Could not create download';
$LN['error_setsnumberunknown']  = 'Could not determine total number of sets';
$LN['error_noqueue']        = 'No queue found';
$LN['error_novalidaction']  = 'No valid action found';
$LN['error_readnzbfailed']  = 'Could not read in NZB file';
$LN['error_nopartsinnzb']   = 'No parts identified in NZB file';
$LN['error_invalidgroup']   = 'Invalid group; group name must exist in /etc/group';
$LN['error_notanumber']     = 'Not a number';
$LN['error_cannotchmod']    = 'Changing access rights not permitted';
$LN['error_cannotchgrp']    = 'Changing group is not permitted';
$LN['error_groupnotfound']  = 'Group does not exist';
$LN['error_subjectnofound'] = 'Subject missing';
$LN['error_posternotfound'] = 'Poster email missing';
$LN['error_invalidrecsize'] = 'Invalid recovery size';
$LN['error_invalidrarsize'] = 'Invalid rar file size';
$LN['error_namenotfound']   = 'Poster name missing';
$LN['error_searchnamenotfound'] = 'Name not found';
$LN['error_spotnotfound']       = 'Spot not found';
$LN['error_setnotfound']        = 'Set not found';
$LN['error_binariesnotfound']   = 'Could not find binaries';
$LN['error_invalidimage']       = 'Not a valid image';

// NZB parse
$LN['nzb_title']        = 'URD NZB Parser';

// Transfers
$LN['transfers_title']          = 'Downloads';
$LN['transfers_importnzb']      = 'Import NZB file';
$LN['transfers_import']         = 'Import';
$LN['transfers_clearcompleted'] = 'Clear completed';
$LN['transfers_pauseall']       = 'Pause all';
$LN['transfers_continueall']    = 'Continue all';
$LN['transfers_nzblocation']    = 'Remote NZB file location';
$LN['transfers_nzblocationext'] = 'This can be a URL (starting with http://) or a local file location (e.g. /tmp/file.nzb';
$LN['transfers_nzbupload']      = 'Upload a local NZB file';
$LN['transfers_nzbuploadext']   = 'In case the NZB file is on your local computer, you can upload it to the URD server';
$LN['transfers_uploadnzb']      = 'Upload an NZB file';
$LN['transfers_runparrar']      = 'Run par2 and unrar';

$LN['transfers_status_removed']     = 'Removed';
$LN['transfers_status_ready']       = 'About to start';
$LN['transfers_status_queued']      = 'Queued';
$LN['transfers_status_active']      = 'Downloading';
$LN['transfers_status_finished']    = 'Finished';
$LN['transfers_status_postactive']  = 'Posting';
$LN['transfers_status_cancelled']   = 'Cancelled';
$LN['transfers_status_yyencodefailed'] = 'Yenc encoding failed';
$LN['transfers_status_paused']      = 'Paused';
$LN['transfers_status_stopped']     = 'Stopped';
$LN['transfers_status_shutdown']    = 'Shutting down';
$LN['transfers_status_error']       = 'Error';
$LN['transfers_status_complete']    = 'Processing';
$LN['transfers_status_unrarfailed'] = 'Extract failed';
$LN['transfers_status_rarfailed']   = 'Compression failed';
$LN['transfers_status_failed']      = 'Failed';
$LN['transfers_status_running']     = 'Active';
$LN['transfers_status_crashed']     = 'Crashed';
$LN['transfers_status_par2failed']  = 'Par2 failed';
$LN['transfers_status_cksfvfailed'] = 'Cksfv failed';
$LN['transfers_status_dlfailed']    = 'Missing articles';

$LN['transfers_linkview']   = 'View files';
$LN['transfers_linkstart']  = 'Start';
$LN['transfers_linkedit']   = 'Edit properties';
$LN['transfers_details']    = 'Transfer details';
$LN['transfers_name']       = 'Download name';
$LN['transfers_archpass']   = 'Archive password';
$LN['transfers_head_started']   = 'Started';
$LN['transfers_head_dlname']    = 'Download name';
$LN['transfers_head_progress']  = 'Progress';
$LN['transfers_head_username']  = 'User';
$LN['transfers_head_speed']     = 'Speed';
$LN['transfers_head_options']   = 'Options';
$LN['transfers_unrar']          = 'Unrar';
$LN['transfers_unpar']          = 'Unpar';
$LN['transfers_deletefiles']    = 'Delete files';
$LN['transfers_subdl']          = 'Download subtitles';
$LN['transfers_badrarinfo']     = 'View the rar log';
$LN['transfers_badparinfo']     = 'View the par2 log';

$LN['transfers_status_rarred']  = 'Rarred';
$LN['transfers_status_par2ed']  = 'Par2 created';
$LN['transfers_status_yyencoded'] = 'Yenc encoded';
$LN['transfers_head_subject']   = 'Subject';
$LN['transfers_posts']          = 'Uploads';
$LN['transfers_post']           = 'Upload';
$LN['transfers_downloads']      = 'Downloads';

// Fatal error
$LN['fatal_error_title']    = 'Message';

// admin_buttons
$LN['buttons_title']        = 'Search options';
$LN['buttons_url']          = 'Search URL';
$LN['buttons_edit']         = 'Edit';
$LN['buttons_editbutton']   = 'Modify search option';
$LN['buttons_addbutton']    = 'Add a new search option';
$LN['buttons_test']         = 'Test';
$LN['buttons_nobuttonid']   = 'No search option ID given';
$LN['buttons_invalidname']  = 'Invalid name given';
$LN['buttons_invalidurl']   = 'Invalid search URL provided';
$LN['buttons_clicktest']    = 'Click to test';
$LN['buttons_buttonexists'] = 'A search option with that name already exists';
$LN['buttons_buttonnotfound']   = 'Search option not found';

// login
$LN['login_title']          = 'Please log in';
$LN['login_title2']         = 'Log in to access';
$LN['login_jserror']        = 'Javascript is required for the URD interface to work correctly. Please enable.';
$LN['login_oneweek']        = 'For one week';
$LN['login_onemonth']       = 'For one month';
$LN['login_oneyear']        = 'For one year';
$LN['login_forever']        = 'Forever';
$LN['login_closebrowser']   = 'Until I close the browser';
$LN['login_login']          = 'Log in';
$LN['login_remember']       = 'Remember me';
$LN['login_bindip']         = 'Bind session to IP address';
$LN['login_forgot_password']= 'I forgot my password';
$LN['login_register']       = 'I want to create an account';
$LN['login_failed']         = 'Your username/password combination was incorrect';

// browse
$LN['browse_allsets']       = 'All sets';
$LN['browse_interesting']   = 'Interesting';
$LN['browse_killed']        = 'Hidden';
$LN['browse_nzb']           = 'NZB created';
$LN['browse_downloaded']    = 'Downloaded';
$LN['browse_addedsets']     = 'Added sets';
$LN['browse_deletedsets']   = 'Deleted sets';
$LN['browse_deletedset']    = 'Deleted set';
$LN['browse_allgroups']     = 'All groups';
$LN['browse_searchsets']    = 'Search in sets';
$LN['browse_addtolist']     = 'Add to list';
$LN['browse_emptylist']     = 'Empty list';
$LN['browse_savenzb']       = 'Save NZB file';
$LN['browse_download']      = 'Download';
$LN['browse_subject']       = 'Subject';
$LN['browse_age']           = 'Age';
$LN['browse_followlink']    = 'Jump to link';
$LN['browse_percent']       = '%';
$LN['browse_removeset']     = 'Hide this set';
$LN['browse_deleteset']     = 'Delete this set';
$LN['browse_resurrectset']  = 'Bring back this set';
$LN['browse_toggleint']     = 'Toggle interesting';
$LN['browse_schedule_at']   = 'Run at';
$LN['browse_download_dir']  = 'Download directory';
$LN['browse_add_setname']   = 'Add setname';
$LN['browse_mergesets']     = 'Merge sets';
$LN['browse_invalid_timestamp'] = 'Invalid timestamp';
$LN['browse_userwhitelisted']   = 'User is on the whitelist';

$LN['NZB_created']          = 'NZB file created';

// ParseNZB
$LN['parsenzb_error']       = 'If you see this, something has gone wrong. Sorry!';

// Preview
$LN['preview_autodisp']     = 'File(s) should be displayed automatically.';
$LN['preview_autofail']     = 'If not, you can click this link';
$LN['preview_view']         = 'Click here to view the NZB file';
$LN['preview_header']       = 'Downloading preview';
$LN['preview_nzb']          = 'To start downloading directly from this NZB file, click this link';
$LN['preview_title']        = 'URD preview page';
$LN['preview_failed']       = 'Preview failed';
$LN['preview_close']        = 'Close window';

// FAQ
$LN['faq_content'][1] = array ('What is URD',  'URD is a programme to download binaries from usenet (newsgroups) with a web based interface. It'
                    .' is written entirely in PHP, although it also makes use of a few external proggrams to do some'
                    .' of the CPU intensive work. It stores all the information it needs in a generic database'
                    .' (like MySQL, or PostGreSQL). Articles will be aggregated into sets that appear to belong together.'
                    .' Downloading requires only a few mouse clicks. An NZB file can also be created. When the download is'
                    .' finished it can automatically verify the par2 or sfv files and decompress the results.'
                    .' In the background URD uses a download program called the URD Daemon (URDD). This daemon handles nearly'
                    .' all of the interaction with the newsgroups, the sets and the downloads.'
                    .' URD is licenced under GPL 3. See the file COPYING for details on the licence.');
$LN['faq_content'][2] = array('Where does the name come from', 'URD is a backronym of Usenet Resource Downloader. The term URD is derived from Nordic cultures'
                    .' referring to the Well of URD, which is the holy well, the Well Spring, the source of water for the world tree'
                    .' Yggdrasil. The old English term for it is Wyrd. Conceptually the meaning of URD is closest to Fate.');

$LN['faq_content'][3] = array('What in case it does not work', 'First, check your settings and see if you can get a connection to the NNTP server. Check the apache log and'
    .' URD log (default: /tmp/urdd.log). If it is a bug, please report it at the google code website. See <a href="http://sourceforge.net/projects/urd/">the URD sourceforge page</a>. '
    . 'Otherwise discuss it at the forum. See <a href="http://www.urdland.com/forum/">URD land</a>.');

$LN['faq_content'][4]   = array('Does URD support SSL', 'Yes, from version 0.4 it does.');
$LN['faq_content'][5]   = array('Does URD support authenticated connections to the newsserver', 'Yes.');
$LN['faq_content'][6]   = array('Can you add this really cool feature', 'Please fill in a feature request and we will consider it. Maybe it ends up in the next version. See the feature requests at <a href="http://sourceforge.net/tracker/?group_id=204007&amp;atid=987882">SourceForge</a>.');
$LN['faq_content'][7]   = array('Can the urdd daemon run on a different machine then the web interface', 'Technically urdd consists of three parts that can be installed on separate machines<ul><li>The database</li><li>URDD</li><li>The web interface</li></ul> However this has not been tested yet.');
$LN['faq_content'][8]   = array('Can URD work with NZB files', 'Yes. There are several options to work with NZB files in URD. First of all to use NZB files to download from. In the download page is a possibility to upload a locally stored NZB file. On the same page is also a possibility to provide an external link to an NZB file. Then some newsgroups also post NZB files; using the preview function on an NZB file, will give you the option to directly download from that file. Finally in view files there is an upload button in the actions part for NZB files as well. Outside the web side, you can use a special directory named spool/username that where you can put an NZB file and it will be used to download from. But there is more. URD can also be used to create NZB files from the indexes it has created so you can share it with others. This works the same as actually downloading in the browse page, but you click the NZB button instead. It will be stored in the download subdirectory name nzb/username.');
$LN['faq_content'][9]   = array('How do I upgrade URD to a new version', 'Currently there is no automatic way to do it. Basically this means you have to run the install script of the new version and either chose a different database name, or check the delete existing database and user box.');
$LN['faq_content'][10]  = array('What licence does URD use', 'Most of the code is GPL v3. Some parts are borrowed from other projects and have another licence.');
$LN['faq_content'][11]  = array('Should I use the download tarball or use subversion to get URD', 'It is strongly recommended to use the officially released tarballs and not subversion. The subversion source may be not work at or have half implemented features. There are mostly like nightly builds. So please download the official releases.');
$LN['faq_content'][12]  = array('My question is not here. What now?', 'Please leave a message at the forum at <a href="http://www.URDland.com/forum/">Urdland</a>.');
$LN['faq_content'][13]  = array('I would like to donate to this project. How?', 'Awesome! A token of appreciation is always very much welcomed, we do not have too many expenses but hosting does cost some 50 euros per year. The easiest way for us would be through PayPal. Theres a donate button <a href="http://urdland.com/cms/component/option,com_wrapper/Itemid,33/">here</a>. If you want to use a different method, please send us an email at "dev@ urdland . com" or PM on the forum and we will exchange information such as addresses or bank account numbers.');

$LN['manual_content'][1] = array ('General', 'Most parts of the URD website have immediate help in the form of popups. Hovering over a link or a text will show this help function.');

$LN['manual_content'][2] = array ('Newsgroups', 'After installation you can log in to your URD web interface and click on newsgroups and search for the newsgroup you wish to subscribe to. If there are no newsgroups found go to the admin panel and click "Update newsgroup list". If that does not help check the preferences. In the newsgroup overview the expire column shows the number of days after which articles will expire. It is also possible to automatically update the newsgroup. Select the period and enter the time at which the update will take place and press the go button. Removing a scheduled update can be done by removing the time and pressing the go button.');

$LN['manual_content'][3] = array ('RSS Feeds', 'Alternatively you can subscribe to RSS feeds as well. Feeds have to be added first clickng add new and entering the required information, including the URL of the RSS feed. Further operation is quite similar to newsgroups.');

$LN['manual_content'][4] = array ('Browsing', 'After the update is complete, go to "browse sets" which shows the available sets. Click on the "?" in front of the set shows the details of the set. The small "+" selects a set to download. After selecting sets, press the "\/" button to start the download. The NZB button saves the selected sets as an NZB file. The "x" deselects the sets. The buttons on the right can be used to lookup more information on a set. First select the text on a set than click one of the buttons to open a new window or tab with the search resuls. The edit button can be used to add more information to the set and can be shared with other users.');

$LN['manual_content'][5] = array ('Downloads', 'When a download has been started its progress can be seen in downloads section. This will also show the status of the download. A direct link to the download directory is provided there. And the download can also be renamed, paused, cancelled, restarted and so on.');

$LN['manual_content'][6] = array ('View files', 'Through the view files tab, all the downloaded files are visible and can be browsed and deleted.');

$LN['manual_content'][7] = array ('Admin', 'The Admin tab can be used for most administrative functions like starting or stopping the URD daemon, cancelling or pausing all actions, remove the tasks from the database. It can also be used to update all the newsgroup or expire all old messages in newsgroups, manage the users and optimise the database. Furthermore it gives an overview of the recent tasks and the status of the URDD daemon. The configuration of URD can also be found here.');

$LN['manual_content'][8] = array ('Configuration','This page is used to manage most settings of urdd');
$LN['manual_content'][9] = array ('Usenet servers','Here you can configure the usenet servers. There are two ways a usenet server can be used. 1. as a binary download servers which is controlled by the enable/disable button. More than 1 of these can be selected. 2. as an indexing server, for which only one may be active. It is selected with the primary checkbox');
$LN['manual_content'][10] = array ('Control','Here you can apply some basic actions to urdd, like shutting down or starting up, cleaning the database, removing all newsgroups and so on');
$LN['manual_content'][11] = array ('Tasks','This provides an overview of all running or queued tasks');
$LN['manual_content'][12] = array ('Jobs','URDD can schedule tasks to execute them on a given time or date, here is an overview of all scheduled tasks');
$LN['manual_content'][13] = array ('Users','The users page is for user account management, to modify the rights, to add or delete or to deactivate a user');
$LN['manual_content'][14] = array ('Buttons','These are the search buttons as placed on the browse page. The search URL should contain a $q, which will be replaced with the search string');
$LN['manual_content'][15] = array ('Log','Here you can see the URD log file, search it and so on. Check this in case an error occurred.');

$LN['manual_content'][16] = array ('Preferences', 'The Preferences tab can be used to modify most user settings.');

$LN['manual_content'][17] = array ('Status overview', 'On the left of the screen there always is a status overview with the status of the URD daemon, online or offline, the current tasks and the available disk space. Also the logged in user name is shown. This will also show if there is a newer version of URD available.');

$LN['manual_content'][18] = array ('It does not work', 'First, check your settings and see if you can get a connection to the NNTP server. Rerun the action with the log level set to debug and check the apache log and URD log (default: /tmp/urd.log). If it is a bug, please report it at the google code website. Otherwise discuss it at the <a href="http://www.urdland.com/forum/">URDland forum</a>. Please add as much as possible information in case of reporting bugs or other problems, including relevant log file entries, error messages and settings. The <a href="debug.php">debug page</a> can also be used to collect all information from the URD daemon.');

// ajax_showsetinfo:
$LN['showsetinfo_postedin'] = 'Posted in';
$LN['showsetinfo_postedby'] = 'Posted by';
$LN['showsetinfo_size']     = 'Total size';
$LN['showsetinfo_shouldbe'] = 'Should be';
$LN['showsetinfo_par2']     = 'Par2';
$LN['showsetinfo_setname']  = 'Set name';
$LN['showsetinfo_typeofbinary'] = 'Type of binary';

// download basket
$LN['basket_totalsize']     = 'Total size';
$LN['basket_setname']       = 'Download name';

// usenet servers
$LN['usenet_title']             = 'Usenet servers';
$LN['usenet_hostname']          = 'Hostname';
$LN['usenet_port']              = 'Port';
$LN['usenet_secport']           = 'Secure port';
$LN['usenet_authentication']    = 'Auth';
$LN['usenet_threads']           = 'Connections';
$LN['usenet_connection']        = 'Encryption';
$LN['usenet_needsauthentication']       = 'Needs authentication';
$LN['usenet_addnew']            = 'Add new';
$LN['usenet_nrofthreads']       = 'Number of connections';
$LN['usenet_connectiontype']    = 'Encryption type';
$LN['usenet_name_msg']          = 'The name under which the usenet server will be known';
$LN['usenet_hostname_msg']      = 'The host name of the usenet server (note: IPv6 addresses must be enclosed by [])';
$LN['usenet_port_msg']          = 'The port number of the usenet server for unencrypted connections';
$LN['usenet_secport_msg']       = 'The port number of the usenet server if connected by SSL or TLS';
$LN['usenet_needsauthentication_msg']       = 'Tag if the usenet server requires authentication';
$LN['usenet_username_msg']                  = 'The username needed if authentication to the usenet server is required';
$LN['usenet_password_msg']                  = 'The password needed if authentication to the usenet server is required';
$LN['usenet_nrofthreads_msg']               = 'The maximum number of threads that will be run in parallel on this server';
$LN['usenet_connectiontype_msg']            = 'The encryption that is used for the connection to the usenet server';
$LN['usenet_priority']      = 'Priority';
$LN['usenet_priority_msg']  = 'Priority: 1 highest; 100 lowest; 0 disabled';
$LN['usenet_enable']        = 'Enable';
$LN['usenet_disable']       = 'Disable';
$LN['usenet_delete']        = 'Delete server';
$LN['usenet_edit']          = 'Edit server';
$LN['usenet_preferred_msg'] = 'This is the primary server, used to index groups';
$LN['usenet_set_preferred_msg'] = 'Use this server as the primary server to index groups';
$LN['usenet_indexing']      = 'Indexing';
$LN['usenet_addserver']     = 'Add a new usenet server';
$LN['usenet_editserver']    = 'Modify a usenet server';
$LN['usenet_compressed_headers']        = 'Use compressed headers';
$LN['usenet_compressed_headers_msg']    = 'Use compressed headers for updating groups. May not be supported by all servers. Check for the XZVER command.';
$LN['usenet_posting']                   = 'Posting';
$LN['usenet_posting_msg']               = 'Allow posting';

$LN['usenet_preferred']     = 'Preferred';
$LN['usenet_set_preferred'] = 'Set preferred';

$LN['forgot_title']         = 'Forgot password';
$LN['forgot_sent']          = 'Password sent';
$LN['forgot_mail']          = 'Send';

$LN['browse_tag_setname']       = 'Set name';
$LN['browse_tag_name']          = 'Name';
$LN['browse_tag_year']          = 'Year';
$LN['browse_tag_lang']          = 'Audio language';
$LN['browse_tag_sublang']       = 'Subtitle language';
$LN['browse_tag_artist']        = 'Artist';
$LN['browse_tag_quality']       = 'Quality';
$LN['browse_tag_runtime']       = 'Runtime';
$LN['browse_tag_movieformat']   = 'Movie format';
$LN['browse_tag_audioformat']   = 'Audio format';
$LN['browse_tag_musicformat']   = 'Music format';
$LN['browse_tag_imageformat']   = 'Image format';
$LN['browse_tag_softwareformat']= 'Software format';
$LN['browse_tag_gameformat']    = 'Game format';
$LN['browse_tag_gamegenre']     = 'Game genre';
$LN['browse_tag_moviegenre']    = 'Movie genre';
$LN['browse_tag_musicgenre']    = 'Music genre';
$LN['browse_tag_imagegenre']    = 'Image genre';
$LN['browse_tag_softwaregenre'] = 'Software genre';
$LN['browse_tag_os']            = 'Operating system';
$LN['browse_tag_genericgenre']  = 'Genre';
$LN['browse_tag_episode']       = 'Episode';
$LN['browse_tag_moviescore']    = 'Movie rating';
$LN['browse_tag_score']         = 'Rating';
$LN['browse_tag_musicscore']    = 'Music rating';
$LN['browse_tag_movielink']     = 'Movie link';
$LN['browse_tag_link']          = 'Link';
$LN['browse_tag_musiclink']     = 'Music link';
$LN['browse_tag_serielink']     = 'Series link';
$LN['browse_tag_xrated']        = 'X-Rated';
$LN['browse_tag_note']          = 'Comments';
$LN['browse_tag_author']        = 'Author';
$LN['browse_tag_ebookformat']   = 'eBook format';
$LN['browse_tag_password']      = 'Unrar password';
$LN['browse_tag_copyright']     = 'Copyright protected';

$LN['quickmenu_setsearch']      = 'Search';
$LN['quickmenu_addblacklist']   = 'Add spotter to blacklist';
$LN['quickmenu_report_spam']    = 'Report spot as spam';
$LN['quickmenu_editspot']       = 'Edit spot';
$LN['quickmenu_setshowesi']     = 'Show set info';
$LN['quickmenu_seteditesi']     = 'Edit set info';
$LN['quickmenu_setguessesi']    = 'Guess set info';
$LN['quickmenu_setbasketguessesi']= 'Guess set info for everything in the download-basket';
$LN['quickmenu_setguessesisafe']= 'Guess set info and validate';
$LN['quickmenu_setpreviewnfo']  = 'Preview NFO file';
$LN['quickmenu_setpreviewimg']  = 'Preview image file';
$LN['quickmenu_setpreviewnzb']  = 'Preview NZB file';
$LN['quickmenu_setpreviewvid']  = 'Preview Video file';
$LN['quickmenu_add_search']     = 'Automatically highlight';
$LN['quickmenu_add_block']      = 'Automatically hide';

$LN['stats_title']  = 'Statistics';
$LN['stats_dl']     = 'Downloads';
$LN['stats_pv']     = 'Previews';
$LN['stats_im']     = 'Imported NZB files';
$LN['stats_gt']     = 'Downloaded NZB files';
$LN['stats_wv']     = 'Web views';
$LN['stats_ps']     = 'Posts';
$LN['stats_total']  = 'Total size';
$LN['stats_number'] = 'Counter';
$LN['stats_user']   = 'User';
$LN['stats_overview']   = 'Overall';

$LN['stats_spotsbymonth']   = 'Spots per month';
$LN['stats_spotsbyweek']    = 'Spots per week';
$LN['stats_spotsbyhour']    = 'Spots per hour';
$LN['stats_spotsbydow']     = 'Spots per day of the week';

$LN['feeds_title']          = 'RSS feeds';
$LN['feeds_rss']            = 'RSS feeds';
$LN['feeds_auth']           = 'Auth';
$LN['feeds_tooltip_active'] = 'RSS feed is active';
$LN['feeds_tooltip_name']   = 'Name of the RSS feed';
$LN['feeds_tooltip_posts']  = 'Number of links in the RSS feed';
$LN['feeds_tooltip_lastupdated']= 'Last updated time';
$LN['feeds_tooltip_expire'] = 'Expire time in days';
$LN['feeds_tooltip_visible']= 'RSS is visible';
$LN['feeds_tooltip_auth']   = 'RSS feeds server requires authentication';
$LN['feeds_tooltip_uepev']	= 'Edit/Update/Expire/Purge/Delete';
$LN['feeds_lastupdated']    = 'Last updated';
$LN['feeds_expire_time']    = 'Expire time';
$LN['feeds_visible']        = 'Visible';
$LN['feeds_tooltip_autoupdate'] = 'Automatically update';
$LN['feeds_autoupdate']     = 'Auto update';
$LN['feeds_searchtext']     = 'Search in all available RSS feeds';
$LN['feeds_url']            = 'URL';
$LN['feeds_tooltip_url']    = 'URL';
$LN['feeds_edit']           = 'Edit';
$LN['feeds_addfeed']        = 'Add a new RSS feed';
$LN['feeds_editfeed']       = 'Modify feed';
$LN['feeds_allgroups']      = 'All feeds';
$LN['feeds_hide_empty']     = 'Hide inactive feeds';
$LN['menurssfeeds']         = 'RSS Feeds';
$LN['menuspots']            = 'Spots';
$LN['menu_overview']        = 'Settings';
$LN['menursssets']          = 'RSS sets';
$LN['menugroupsets']        = 'Group sets';

$LN['error_invalidfeedid']  = 'Invalid feed ID';
$LN['error_feednotfound']   = 'Feed not found';
$LN['config_formatstrings'] = 'Format strings';
$LN['config_formatstring']  = 'Format string for';

$LN['post_message']         = 'Post a message';
$LN['post_messagetext']     = 'Message text';
$LN['post_messagetextext']  = 'The content of the message to post';
$LN['post_newsgroupext2']   = 'The newsgroup to which the message will be posted';
$LN['post_subjectext2']     = 'The subject line in the message';

$LN['settype'][SETTYPE_UNKNOWN]     = $LN['config_formatstring'] . ' Unknown';
$LN['settype'][SETTYPE_MOVIE]       = $LN['config_formatstring'] . ' Movie';
$LN['settype'][SETTYPE_ALBUM]       = $LN['config_formatstring'] . ' Album';
$LN['settype'][SETTYPE_IMAGE]       = $LN['config_formatstring'] . ' Image';
$LN['settype'][SETTYPE_SOFTWARE]    = $LN['config_formatstring'] . ' Software';
$LN['settype'][SETTYPE_TVSERIES]    = $LN['config_formatstring'] . ' TV series';
$LN['settype'][SETTYPE_EBOOK]       = $LN['config_formatstring'] . ' Ebook';
$LN['settype'][SETTYPE_GAME]        = $LN['config_formatstring'] . ' Game';
$LN['settype'][SETTYPE_TVSHOW]      = $LN['config_formatstring'] . ' TV Show';
$LN['settype'][SETTYPE_DOCUMENTARY] = $LN['config_formatstring'] . ' Documentary';
$LN['settype'][SETTYPE_OTHER]       = $LN['config_formatstring'] . ' Other';

$LN['settype_syntax'] = '%(n.mc); where <i>()</i> indicates an optional enclosure, can be (), [] or {}; <i>n</i> an optional padding value, <i>.m</i> an optional maximum length value, <i>c</i> a required character designated below (use %% to display a %, also see the php documentation an sprintf):<br/><br/>';

$LN['settype_msg'][SETTYPE_UNKNOWN]     = $LN['settype_syntax'] . 'Unknown settype:<br/>%n: name<br/>%t: set type<br/>%T: type dependent icon <br/>%P: password protected<br/>%C: copyrighted material';
$LN['settype_msg'][SETTYPE_MOVIE]       = $LN['settype_syntax'] . 'Movie settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%y: year<br/>%m: movie format<br/>%a: audio format<br/>%l: language<br/>%s: subtitle language<br/>%x: x-rated<br/>%N: notes<br/>%q: quality<br/>%P: password protected<br/>%C: copyrighted material <br/>';
$LN['settype_msg'][SETTYPE_ALBUM]       = $LN['settype_syntax'] . 'Album settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%y: year <br/>%f: format<br/>%g: genre<br/>%N: notes<br/>%q: quality<br/>%P: password protected<br/>%C: copyrighted material <br/>';
$LN['settype_msg'][SETTYPE_IMAGE]       = $LN['settype_syntax'] . 'Image settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon <br/>%f: format<br/>%g: genre<br/>%N: notes<br/>%q: quality<br/>%x: x-rated<br/>%P: password protected<br/>%C: copyrighted material';
$LN['settype_msg'][SETTYPE_SOFTWARE]    = $LN['settype_syntax'] . 'Software settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%o: operating system <br/>%q: quality<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material';
$LN['settype_msg'][SETTYPE_TVSERIES]    = $LN['settype_syntax'] .  'TV series settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%e: episode<br/>%m: movie format<br/>%a: audio format<br/>%x: x-rated<br/>%q: quality<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material';
$LN['settype_msg'][SETTYPE_EBOOK]       = $LN['settype_syntax'] . 'Ebook settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%A: author<br/>%y: year<br/>%f: format<br/>%q: quality<br/>%g: genre<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material';
$LN['settype_msg'][SETTYPE_GAME]        = $LN['settype_syntax'] . 'Game settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%A: author<br/>%y: year<br/>%f: format<br/>%q: quality<br/>%g: genre<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material';
$LN['settype_msg'][SETTYPE_TVSHOW]      = $LN['settype_syntax'] . 'TV show settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%m: movie format<br/>%y: year<br/>%e: episode<br/>%f: format<br/>%q: quality<br/>%g: genre<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material';
$LN['settype_msg'][SETTYPE_DOCUMENTARY] = $LN['settype_syntax'] . 'Documentary settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%A: author<br/>%y: year<br/>%f: format<br/>%q: quality<br/>%g: genre<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material';
$LN['settype_msg'][SETTYPE_OTHER]       = $LN['settype_syntax'] . 'Other settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material';

$LN['loading_files']        = 'Loading files... please wait';
$LN['loading']              = 'Loading... please wait';

$LN['spots_allcategories']          = 'All categories';
$LN['spots_allsubcategories']       = 'All subcategories';
$LN['spots_tag']                    = 'Tag';
$LN['spots_subcategories']          = 'Subcategories';
$LN['pref_spots_category_mapping']       = 'Spots category mapping for';
$LN['pref_spots_category_mapping_msg']   = 'Spots category mapping to URD categories';

$LN['spots_other']       = 'Other';
$LN['spots_all']         = 'Everything';

$LN['spots_image']       = 'Image';
$LN['spots_sound']       = 'Sound';
$LN['spots_game']        = 'Game';
$LN['spots_application'] = 'Application';
$LN['spots_format']      = 'Format';
$LN['spots_source']      = 'Source';
$LN['spots_language']    = 'Language';
$LN['spots_genre']       = 'Genre';
$LN['spots_bitrate']     = 'Bit rate';
$LN['spots_platform']    = 'Platform';
$LN['spots_type']        = 'Type';

$LN['spots_film']        = 'Film';
$LN['spots_series']      = 'Series';
$LN['spots_book']        = 'Book';
$LN['spots_erotica']     = 'Erotica';

$LN['spots_divx']      = 'DivX';
$LN['spots_wmv']       = 'WMV';
$LN['spots_mpg']       = 'MPG';
$LN['spots_dvd5']      = 'DVD5';
$LN['spots_hdother']   = 'HD other';
$LN['spots_ebook']     = 'E-book';
$LN['spots_bluray']    = 'Blu-ray';
$LN['spots_hddvd']     = 'HD DVD';
$LN['spots_wmvhd']     = 'WMVHD';
$LN['spots_x264hd']    = 'x264HD';
$LN['spots_dvd9']      = 'DVD9';
$LN['spots_cam']       = 'Cam';
$LN['spots_svcd']      = '(S)VCD';
$LN['spots_promo']     = 'Promo';
$LN['spots_dvd']       = 'DVD';
$LN['spots_tv']        = 'TV';
$LN['spots_satellite'] = 'Satellite';
$LN['spots_r5']        = 'R5';
$LN['spots_telecine']  = 'Telecine';
$LN['spots_telesync']  = 'Telesync';
$LN['spots_scan']      = 'Scan';

$LN['spots_subs_non']      = 'No subtitles';
$LN['spots_subs_nl_ext']   = 'Dutch subtitles (external)';
$LN['spots_subs_nl_incl']  = 'Dutch subtitles (hardcoded)';
$LN['spots_subs_eng_ext']  = 'English subtitles (external)';
$LN['spots_subs_eng_incl'] = 'English subtitles (hardcoded)';
$LN['spots_subs_nl_opt']   = 'Dutch subtitles (optional)';
$LN['spots_subs_eng_opt']  = 'English subtitles (optional)';
$LN['spots_false']         = 'False';
$LN['spots_lang_eng']      = 'English speech';
$LN['spots_lang_nl']       = 'Dutch speech';
$LN['spots_lang_ger']      = 'German speech';
$LN['spots_lang_fr']       = 'French speech';
$LN['spots_lang_es']       = 'Spanish speech';
$LN['spots_lang_asian']    = 'Asian speech';

$LN['spots_action']       = 'Action';
$LN['spots_adventure']    = 'Adventure';
$LN['spots_animation']    = 'Animation';
$LN['spots_cabaret']      = 'Cabaret';
$LN['spots_comedy']       = 'Comedy';
$LN['spots_crime']        = 'Crime';
$LN['spots_documentary']  = 'Documentary';
$LN['spots_drama']        = 'Drama';
$LN['spots_family']       = 'Family';
$LN['spots_fantasy']      = 'Fantasy';
$LN['spots_filmnoir']     = 'Film Noir';
$LN['spots_tvseries']     = 'TV Series';
$LN['spots_horror']       = 'Horror';
$LN['spots_music']        = 'Music';
$LN['spots_musical']      = 'Musical';
$LN['spots_mystery']      = 'Mystery';
$LN['spots_romance']      = 'Romance';
$LN['spots_scifi']        = 'Science fiction';
$LN['spots_sport']        = 'Sport';
$LN['spots_short']        = 'Short film';
$LN['spots_thriller']     = 'Thriller';
$LN['spots_war']          = 'War';
$LN['spots_western']      = 'Western';
$LN['spots_ero_hetero']   = 'Erotica (hetero)';
$LN['spots_ero_gaymen']   = 'Erotica (gay)';
$LN['spots_ero_lesbian']  = 'Erotica (lesbian)';
$LN['spots_ero_bi']       = 'Erotica (bisexual)';
$LN['spots_asian']        = 'Asian';
$LN['spots_anime']        = 'Anime';
$LN['spots_cover']        = 'Cover';
$LN['spots_comics']       = 'Comics';
$LN['spots_cartoons']     = 'Cartoons';
$LN['spots_children']     = 'Children';

$LN['spots_album']         = 'Album';
$LN['spots_liveset']       = 'Live set';
$LN['spots_podcast']       = 'Podcast';
$LN['spots_audiobook']     = 'Audiobook';

$LN['spots_mp3']           = 'MP3';
$LN['spots_wma']           = 'WMA';
$LN['spots_wav']           = 'WAV';
$LN['spots_ogg']           = 'OGG';
$LN['spots_eac']           = 'EAC';
$LN['spots_dts']           = 'DTS';
$LN['spots_aac']           = 'AAC';
$LN['spots_ape']           = 'APE';
$LN['spots_flac']          = 'FLAC';
$LN['spots_cd']            = 'CD';
$LN['spots_radio']         = 'Radio';
$LN['spots_compilation']   = 'Compilation';
$LN['spots_retail']        = 'Retail';
$LN['spots_vinyl']         = 'Vinyl';
$LN['spots_stream']        = 'Stream';
$LN['spots_variable']      = 'Variable';
$LN['spots_96kbit']        = '96 kbit';
$LN['spots_lt96kbit']      = '&lt;96 kbit';
$LN['spots_128kbit']       = '128 kbit';
$LN['spots_160kbit']       = '160 kbit';
$LN['spots_192kbit']       = '192 kbit';
$LN['spots_256kbit']       = '256 kbit';
$LN['spots_320kbit']       = '320kbit';
$LN['spots_lossless']      = 'Lossless';

$LN['spots_blues']         = 'Blues';
$LN['spots_compilation']   = 'Compilation';
$LN['spots_cabaret']       = 'Cabaret';
$LN['spots_dance']         = 'Dance';
$LN['spots_various']       = 'Various';
$LN['spots_hardcore']      = 'Hardcore';
$LN['spots_international'] = 'International';
$LN['spots_jazz']          = 'Jazz';
$LN['spots_children']      = 'Juvenile';
$LN['spots_classical']     = 'Classical';
$LN['spots_smallarts']     = 'Small arts';
$LN['spots_netherlands']   = 'Dutch';
$LN['spots_newage']        = 'New Age';
$LN['spots_pop']           = 'Pop';
$LN['spots_soul']          = 'R&amp;B';
$LN['spots_hiphop']        = 'Hiphop';
$LN['spots_reggae']        = 'Reggae';
$LN['spots_religious']     = 'Religious';
$LN['spots_rock']          = 'Rock';
$LN['spots_soundtracks']   = 'Soundtrack';
$LN['spots_hardstyle']     = 'Hardstyle';
$LN['spots_asian']         = 'Asian';
$LN['spots_disco']         = 'Disco';
$LN['spots_oldschool']     = 'Old school';
$LN['spots_metal']         = 'Metal';
$LN['spots_country']       = 'Country';
$LN['spots_dubstep']       = 'Dubstep';
$LN['spots_nederhop']      = 'Nederhop';
$LN['spots_dnb']           = 'DnB';
$LN['spots_electro']       = 'Electro';
$LN['spots_folk']          = 'Folk';
$LN['spots_soul']          = 'Soul';
$LN['spots_trance']        = 'Trance';
$LN['spots_balkan']        = 'Balkan';
$LN['spots_techno']        = 'Techno';
$LN['spots_ambient']       = 'Ambient';
$LN['spots_latin']         = 'Latin';
$LN['spots_live']          = 'Live';

$LN['spots_windows']       = 'Windows';
$LN['spots_mac']           = 'Macintosh';
$LN['spots_linux']         = 'Linux';
$LN['spots_navigation']    = 'Navigation';
$LN['spots_os2']           = 'OS/2';
$LN['spots_playstation']   = 'Playstation';
$LN['spots_playstation2']  = 'Playstation 2';
$LN['spots_playstation3']  = 'Playstation 3';
$LN['spots_psp']           = 'PSP';
$LN['spots_xbox']          = 'Xbox';
$LN['spots_xbox360']       = 'Xbox 360';
$LN['spots_gameboy']       = 'Gameboy';
$LN['spots_gamecube']      = 'Gamecube';
$LN['spots_nintendods']    = 'Nintendo DS';
$LN['spots_nintendowii']   = 'Nintendo Wii';
$LN['spots_windowsphone']  = 'Windows Phone';
$LN['spots_ios']           = 'iOS';
$LN['spots_android']       = 'Android';
$LN['spots_nintendo3ds']   = 'Nintendo 3DS';

$LN['spots_rip']           = 'Rip';
$LN['spots_retail']        = 'Retail';
$LN['spots_addon']         = 'Add-on';
$LN['spots_patch']         = 'Patch';
$LN['spots_crack']         = 'Crack';
$LN['spots_iso']           = 'ISO';
$LN['spots_action']        = 'Action';
$LN['spots_adventure']     = 'Adventure';
$LN['spots_strategy']      = 'Strategy';
$LN['spots_roleplay']      = 'Roleplay';
$LN['spots_simulation']    = 'Simulation';
$LN['spots_race']          = 'Race';
$LN['spots_flying']        = 'Flying';
$LN['spots_shooter']       = 'First Person Shooter';
$LN['spots_platform']      = 'Platform';
$LN['spots_sport']         = 'Sports';
$LN['spots_children']      = 'Children / juvenile';
$LN['spots_puzzle']        = 'Puzzle';
$LN['spots_boardgame']     = 'Boardgame';
$LN['spots_cards']         = 'Cards';
$LN['spots_education']     = 'Education';
$LN['spots_music']         = 'Music';
$LN['spots_family']        = 'Family';

$LN['spots_audioedit']     = 'Sound editing';
$LN['spots_videoedit']     = 'Video editing';
$LN['spots_graphics']      = 'Graphical design';
$LN['spots_cdtools']       = 'CD tools';
$LN['spots_mediaplayers']  = 'Media players';
$LN['spots_rippers']       = 'Rippers and encoders';
$LN['spots_plugins']       = 'Plugins';
$LN['spots_database']      = 'Databases';
$LN['spots_email']         = 'E-mail software';
$LN['spots_photo']         = 'Photo editors';
$LN['spots_screensavers']  = 'Screensavers';
$LN['spots_skins']         = 'Skins software';
$LN['spots_drivers']       = 'Drivers';
$LN['spots_browsers']      = 'Browsers';
$LN['spots_downloaders']   = 'Download managers';
$LN['spots_filesharing']   = 'Filesharing software';
$LN['spots_usenet']        = 'Usenet software';
$LN['spots_rss']           = 'RSS software';
$LN['spots_ftp']           = 'FTP software';
$LN['spots_firewalls']     = 'Firewalls';
$LN['spots_antivirus']     = 'Anti-virus';
$LN['spots_antispyware']   = 'Anti-spyware';
$LN['spots_optimisation']  = 'Optimisation software';
$LN['spots_security']      = 'Security software';
$LN['spots_system']        = 'System software';
$LN['spots_educational']   = 'Educational';
$LN['spots_office']        = 'Office';
$LN['spots_internet']      = 'Internet';
$LN['spots_communication'] = 'Communication';
$LN['spots_development']   = 'Development';
$LN['spots_spotnet']       = 'Spotnet';

$LN['spots_daily']          = 'Newspaper';
$LN['spots_magazine']       = 'Magazine';
$LN['spots_comic']          = 'Comic';
$LN['spots_study']          = 'Study';
$LN['spots_business']       = 'Business';
$LN['spots_economy']        = 'Economy';
$LN['spots_computer']       = 'Computer';
$LN['spots_hobby']          = 'Hobby';
$LN['spots_cooking']        = 'Cooking';
$LN['spots_crafts']         = 'Crafts';
$LN['spots_needlework']     = 'Needlework';
$LN['spots_health']         = 'Health';
$LN['spots_history']        = 'History';
$LN['spots_psychology']     = 'Psychology';
$LN['spots_science']        = 'Science';
$LN['spots_woman']          = 'Woman';
$LN['spots_religion']       = 'Religion';
$LN['spots_novel']          = 'Novel';
$LN['spots_biography']      = 'Biography';
$LN['spots_detective']      = 'Detective';
$LN['spots_animals']        = 'Animals';
$LN['spots_humour']         = 'Humour';
$LN['spots_travel']         = 'Travel';
$LN['spots_truestory']      = 'True story';
$LN['spots_nonfiction']     = 'Non-fiction';
$LN['spots_politics']       = 'Politics';
$LN['spots_poetry']         = 'Poetry';
$LN['spots_fairytale']      = 'Fairytales';
$LN['spots_technical']      = 'Technical';
$LN['spots_art']            = 'Art';
$LN['spots_bi']             = 'Erotica: Bisexual';
$LN['spots_lesbo']          = 'Erotica: Lesbian';
$LN['spots_homo']           = 'Erotica: Gay';
$LN['spots_hetero']         = 'Erotica: Straight';
$LN['spots_amateur']        = 'Erotica: Amateur';
$LN['spots_groep']          = 'Erotica: Group';
$LN['spots_pov']            = 'Erotica: POV';
$LN['spots_solo']           = 'Erotica: Solo';
$LN['spots_teen']           = 'Erotica: Teens';
$LN['spots_soft']           = 'Erotica: Soft';
$LN['spots_fetish']         = 'Erotica: Fetish';
$LN['spots_mature']         = 'Erotica: Mature';
$LN['spots_fat']            = 'Erotica: Fat';
$LN['spots_sm']             = 'Erotica: S&amp;M';
$LN['spots_rough']          = 'Erotica: Rough';
$LN['spots_black']          = 'Erotica: Black';
$LN['spots_hentai']         = 'Erotica: Hentai';
$LN['spots_outside']        = 'Erotica: Outdoor';

$LN['update_database']      = 'Update database';

  
$LN['dashboard_max_nntp']      = 'Maximum number of NNTP connections';
$LN['dashboard_max_threads']   = 'Maximum number of total threads';
$LN['dashboard_max_db_intensive']	    = 'Maximum database intesive threads';


$LN['password_weak']        = 'Password strength: weak';
$LN['password_medium']      = 'Password strength: medium';
$LN['password_strong']      = 'Password strength: strong';
$LN['password_correct']     = 'Passwords match';
$LN['password_incorrect']   = 'Passwords do not match';

if (isset($smarty)) { // don't do the smarty thing if we read it from urdd
    foreach ($LN as $key => $word) {
        $LN2['LN_' . $key] = $word;
    }
    $smarty->assign($LN2);
    unset($LN2);
}
