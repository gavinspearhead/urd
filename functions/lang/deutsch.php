<?php
/*
*  This file is part of Urd.
*
 *  vim:ts=4:expandtab:cindent
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
* $LastChangedDate: $
* $Rev: $
* $Author: $
* $Id: $
*/

/* German language file for URD */

//	Syntax: All language variables are put in $LN, and consist of a lowercase,
//	 english description of the variable content (a-z, no other characters).
//	Capitals: Default should be the most common occurence, overriding the capitalization
//	 can be done via smarty's functions. (So don't create another variable with only different caps)
//	Examples:
//	{$articleTitle} 			= next x-men film, x3, delayed.
//	{$articleTitle|capitalize}		= Next X-Men Film, x3, Delayed.
//	{$articleTitle|capitalize:true}	= Next X-Men Film, X3, Delayed.
//	Other options are: upper, lower.

//	Language array:
$LN	= array();

$LN['byte']             = 'byte';
$LN['bytes']            = 'bytes';
$LN['byte_short']       = 'B';

$LN['ok']               = 'OK';
$LN['cancel']           = 'Abbrechen';
$LN['pause']            = 'Pausiern';
$LN['continue']         = 'Fortsetzen';
$LN['details']          = 'Details';
$LN['error']            = 'Fehler';
$LN['atonce']           = 'Sofort';
$LN['browse']           = 'Browse';

// Special:
$LN['urdname']			= 'URD';
$LN['decimalseparator'] = ',';
$LN['dateformat']       = 'd-m-Y';
$LN['dateformat2']      = 'd M Y';
$LN['dateformat3']      = 'd M';
$LN['timeformat']       = 'H:i:s';
$LN['timeformat2']      = 'H:i';

// This 'overwrites' the define values:
$LN['periods'][0]		= 'Kein Autoupdate';
$LN['periods'][11]		= 'Jeden Stunde';
$LN['periods'][12]		= 'Alle 3 Stunden';
$LN['periods'][1]		= 'Alle 6 Stunden';
$LN['periods'][13]		= 'Alle 12 Stunden';
$LN['periods'][2]		= 'Jeden Tag';
$LN['periods'][3]		= 'Jeden Montag';
$LN['periods'][4]		= 'Jeden Dienstag';
$LN['periods'][5]		= 'Jeden Mittwoch';
$LN['periods'][6]		= 'Jeden Donnerstag';
$LN['periods'][7]		= 'Jeden Freitag';
$LN['periods'][8]		= 'Jeden Samstag';
$LN['periods'][9]		= 'Jeden Sonntag';
$LN['periods'][10]		= 'Alle 4 Wochen';

$LN['month_names'][1]  = 'Januar';
$LN['month_names'][2]  = 'Februar';
$LN['month_names'][3]  = 'M&auml;rz';
$LN['month_names'][4]  = 'April';
$LN['month_names'][5]  = 'Mai';
$LN['month_names'][6]  = 'Juni';
$LN['month_names'][7]  = 'Juli';
$LN['month_names'][8]  = 'August';
$LN['month_names'][9]  = 'September';
$LN['month_names'][10] = 'Oktober';
$LN['month_names'][11] = 'November';
$LN['month_names'][12] = 'Dezember';

$LN['short_day_names'][1]		= 'Son';
$LN['short_day_names'][2]		= 'Mon';
$LN['short_day_names'][3]		= 'Die';
$LN['short_day_names'][4]		= 'Mit';
$LN['short_day_names'][5]		= 'Don';
$LN['short_day_names'][6]		= 'Fre';
$LN['short_day_names'][7]		= 'Sam';

$LN['short_month_names'][1]		= 'Jan';
$LN['short_month_names'][2]		= 'Feb';
$LN['short_month_names'][3]		= 'M&auml;r';
$LN['short_month_names'][4]		= 'Apr';
$LN['short_month_names'][5]		= 'Mai';
$LN['short_month_names'][6]		= 'Jun';
$LN['short_month_names'][7]		= 'Jul';
$LN['short_month_names'][8]		= 'Aug';
$LN['short_month_names'][9]		= 'Sep';
$LN['short_month_names'][10]	= 'Okt';
$LN['short_month_names'][11]	= 'Nov';
$LN['short_month_names'][12]	= 'Dez';

$LN['select']       = 'Selektier eine';
$LN['time']         = 'Zeit';

$LN['whitelisttag'] = 'W';
$LN['blacklisttag']     = 'S';
$LN['spamreporttag']    = 'S';

$LN['autoconfig']   = 'Autokonfigurieren';
$LN['autoconfig_ext']   = 'Autoconfig (erweitert)';
$LN['extended']     = 'erweitert';
$LN['all']          = 'alles';
$LN['since']        = 'seit';

$LN['expand']       = 'ausklappen';
$LN['reload']       = 'Nachladen';
$LN['disabled']     = 'deaktivert';
$LN['unknown']      = 'Unbekannt';
$LN['help']         = 'Hilfe';
$LN['sets']         = 'Sets';
$LN['active']       = 'Activ';

$LN['id']                   = 'ID';
$LN['pid']                  = 'PID';
$LN['server']               = 'Server';
$LN['start_time']           = 'Startzeit';
$LN['queue_time']           = 'Queuezeit';
$LN['recurrence']           = 'Wiederholung';
$LN['enabled']              = 'Aktiviert';
$LN['free_threads']         = 'Freie Threads';
$LN['total_free_threads']   = 'Total freie threads';
$LN['free_db_intensive_threads']   = 'Freie Datenbank-intensive Threads';
$LN['free_nntp_threads']    = 'Freie NNTP threads';

$LN['expire']       = 'Rausaltern';
$LN['update']	    = 'Aktualisieren';
$LN['purge']	    = 'L&ouml;schen';

$LN['CAPTCHA1']     = 'Captcha';
$LN['CAPTCHA2']     = '3 schwarze Symbole';

$LN['autoconfig_msg']       = 'Autoconfigure: It tries all servers in the list and sees if there is a server at the standard usenet ports (119 and 563), with or without ssl/tls. If it finds one it selects it; and updating server is selected if one is found that allows indexing ';
$LN['autoconfig_ext_msg']   = 'Extended autoconfigure: It tries all servers in the list and sees if there is a server at the standard usenet ports (119 and 563) and many other ports that may be used by usenet service providers (like 23, 80, 8080, 443), with or without ssl/tls. If it finds one it selects it; and updating server is selected if one is found that allows indexing ';

$LN['next']         = 'N&auml;chtse';
$LN['previous']     = 'Vorige';

$LN['add_search']           = 'Suche speicheren';
$LN['delete_search']        = 'Suche l&ouml;schen';
$LN['save_search_as']       = 'Suche speicheren wie';
$LN['saved']                = 'Gespeichert';
$LN['deleted']              = 'Gel&ouml;scht';

$LN['off']			= 'Aus';
$LN['on']			= 'An';
$LN['all']			= 'Alle';
$LN['preview']		= 'Vorschau';
$LN['temporary']	= 'Zeitweilig Dateien';
$LN['other']	    = 'Andere';
$LN['from']			= 'aus';
$LN['total']		= 'Total';

$LN['never']		= 'Nie';
// Time:
$LN['year']			= 'Jahr';
$LN['month']		= 'Monat';
$LN['week']			= 'Woche';
$LN['day']			= 'Tag';
$LN['hour']			= 'Stunde';
$LN['minute']		= 'Minute';
$LN['second']		= 'Sekunde';

$LN['years']		= 'Jahre';
$LN['months']	    = 'Monate';
$LN['weeks']		= 'Wochen';
$LN['days']			= 'Tage';
$LN['hours']		= 'Stunden';
$LN['minutes']		= 'Minuten';
$LN['seconds']		= 'Sekunden';

$LN['year_short']	= 'J';
$LN['month_short']	= 'M';
$LN['week_short']	= 'w';
$LN['day_short']	= 't';
$LN['hour_short']	= 'h';
$LN['minute_short']	= 'm';
$LN['second_short']	= 's';

// Menu:
$LN['menudownloads']	= 'Downloads';
$LN['menuuploads']      = 'Uploads';
$LN['menutransfers']    = '&Uuml;bertragung';
$LN['menubrowsesets']	= 'Angebot';
$LN['menugroupsearch']	= 'Sets&nbsp;in&nbsp;gruppen&nbsp;suchen';
$LN['menuspotssearch']  = 'Spots suchen';
$LN['menusearch']       = 'Such';
$LN['menursssearch']    = 'Search&nbsp;rss&nbsp;sets';
$LN['menunewsgroups']	= 'Newsgruppen';
$LN['menuviewfiles']	= 'Dateien&nbsp;ansehen';
$LN['menuviewfiles_downloads']	= 'Downloads';
$LN['menuviewfiles_previews']	= 'Vorschau&nbsp;dateien';
$LN['menuviewfiles_nzbfiles']	= 'NZB&nbsp;dateien';
$LN['menuviewfiles_scripts']	= 'Scripts';
$LN['menuviewfiles_posts']      = 'Posts';
$LN['menupreferences']	= 'Einstellungen';
$LN['menuadmin']		= 'Admin';
$LN['menuabout']		= '&Uuml;ber';
$LN['menumanual']		= 'Handbuch';
$LN['menusettings']		= 'Settings';
$LN['menuadminconfig']	= 'Konfiguration';
$LN['menuadmincontrol']	= 'Steuerung';
$LN['menuadminusenet']	= 'Usenet Server';
$LN['menuadminlog']		= 'Log';
$LN['menuadminjobs']	= 'Zeitgesteuerte Aufgaben';
$LN['menuadmintasks']	= 'Auftr&auml;ge';
$LN['menuadminusers']	= 'Benutzer';
$LN['menuadminbuttons']	= 'Suchen';
$LN['menuhelp']			= 'Hilfe';
$LN['menufaq']			= 'FAQ';
$LN['menulogout']		= 'Ausloggen';
$LN['menulogin']		= 'Einloggen';
$LN['menudebug']		= 'Debug';
$LN['menulicence']		= 'Lizenz';
$LN['menustats']		= 'Statistiken';
$LN['menuforum']		= 'Forum';
$LN['menuuserlists']            = 'Spotter lists';

//button texts
$LN['button_submit']	= 'Abschicken';
$LN['button_reset']		= 'Zur&uuml;cksetzen';
//$LN['button_']		= '';

$LN['advanced_search']	= 'Avanciert suchen';

// Stati:
$LN['statusidling']		= 'Im Leerlauf';
$LN['statusdiskspace']	= 'Freier Plattenplatz:';
$LN['statusrunningtasks']  = 'Aktive Auftr&auml;ge:';

$LN['enableurddfirst']  = 'Starten sie URDD zuerst';
// Version:
$LN['version']			= 'Version';
$LN['enableurdd']		= 'Klicken um URD zu Starten';
$LN['disableurdd']		= 'Klicken um URD zu Stoppen';
$LN['urddenabled']		= 'URDD ist online';
$LN['urddstarting']     = 'URDD fangt an';
$LN['urdddisabled']		= 'URDD ist offline';
$LN['versionuptodate']		= 'URD ist aktuell.';
$LN['versionoutdated']		= 'URD ist veraltet';
$LN['newversionavailable']	= 'Eine neue Hauptversion ist verf&uuml;gbar.';
$LN['bugfixedversion']		= 'Eine bugfix Version ist verf&uuml;gbar.';
$LN['newfeatureversion']	= 'Die neue Version enth&auml;lt zus&auml;tzliche Funktionen.';
$LN['otherversion']		    = 'Die neue Version enth&auml;lt nicht n&auml;her genannte &Auml;nderungen (??).';
$LN['securityfixavailable']	= 'Die neue Version enth&auml;lt wichtige Sicherheitsupdates.';
$LN['status'] 			    = 'Status';
$LN['activity']			    = 'Aktivit&auml;t';

// Tasks:
$LN['taskupdate']		= 'Aktualisiere';
$LN['taskpost']         = 'Posting';
$LN['taskpurge']		= 'L&ouml;sche';
$LN['taskexpire']		= 'Altere raus';
$LN['taskdownload']		= 'Runterladen';
$LN['taskcontinue']     = 'Fortsetzen';
$LN['taskpause']        = 'Pause';
$LN['taskunknown']      = 'Unbekannt';
$LN['taskadddata']		= 'Download data zuf&uuml;gen';
$LN['taskoptimise']		= 'Optimiere';
$LN['taskgrouplist']	= 'Hole Gruppenliste';
$LN['taskunparunrar']	= 'Packe aus';
$LN['taskcheckversion']	= '&Uuml;berpr&uuml;fe version';
$LN['taskgetsetinfo']	= 'Set info holen';
$LN['taskgetblacklist'] = 'Getting blacklist';
$LN['taskgetwhitelist'] = 'Getting whitelist';
$LN['tasksendsetinfo']	= 'Set info senden';
$LN['taskparsenzb']		= 'NZB einlesen';
$LN['taskmakenzb']		= 'NZB anmachen';
$LN['taskcleandir']		= 'Dirs bereinigen';
$LN['taskcleandb']		= 'Database bereinigen';
$LN['taskgensets']		= 'Sets machen f&uuml;r';
$LN['taskgetnfo']       = 'Getting NFO data';
$LN['taskgetspots']     = 'Getting spots';
$LN['taskgetspot_comments']     = 'Spots kommentare werden aktualisiert';
$LN['taskgetspot_reports']     = 'Spots Spam-Berichte werden aktualisiert';
$LN['taskgetspot_images']     = 'Spots Bilder werden aktualisiert';
$LN['taskexpirespots']  = 'Spots herausaltern';
$LN['taskpurgespots']   = 'Spots l&ouml;schen';
$LN['taskmergesets']	= 'Sets verbinden';
$LN['taskfindservers']  = 'Server Autokonfig';
$LN['taskdeleteset']    = 'Set l&ouml;schen';
$LN['taskset']          = 'Setting configuration';
$LN['taskpostspot']     = 'Posting spot';
$LN['taskpostmessage']  = 'Posting message';

$LN['eta']			    = 'ETA';
$LN['inuse']			= 'ist in Benutzung';
$LN['free']			    = 'ist frei';

// Generic:
$LN['isavailable']		= 'ist verf&uuml;gbar';
$LN['apply']			= 'Anwenden';
$LN['website']			= 'Website';
$LN['or']			    = 'oder';
$LN['submit']			= 'Absenden';
$LN['add']			    = 'Zuf&uuml;gen';
$LN['clear']			= 'Bereinigen';
$LN['reset']			= 'Zur&uuml;cksetzen';
$LN['search']			= 'Suche';
$LN['number']           = 'Number';
$LN['rename']			= 'Umbennen';
$LN['register']			= 'Registriere';
$LN['delete']			= 'L&ouml;sche';
$LN['delete_all']       = 'L&ouml;sche alle';

$LN['licence_title']	= 'Licence';

// Setinfo:
$LN['bin_unknown']		= 'Unbekannt';
$LN['bin_movie']		= 'Film';
$LN['bin_album']		= 'Album';
$LN['bin_image']		= 'Bild';
$LN['bin_software']		= 'Software';
$LN['bin_tvseries']		= 'TV Serie';
$LN['bin_ebook']		= 'eBuch';
$LN['bin_game']			= 'Spiel';
$LN['bin_documentary']	= 'Dokumentarfilm';
$LN['bin_tvshow']		= 'TV Programm';
$LN['bin_other']		= 'Anderes';

// View files:
$LN['files']			= 'Dateien';
$LN['viewfilesheading']	= 'Zeige an';
$LN['filename']			= 'Dateiname';
$LN['group']			= 'Gruppe';
$LN['rights']			= 'Rights';
$LN['edit_file']		= 'Editier Datei';
$LN['size'] 			= 'Gr&ouml;&szlig;e';
$LN['count'] 			= 'Zahl';
$LN['type'] 			= 'Typ';
$LN['modified']			= 'Ver&auml;ndert';
$LN['owner']            = 'Besitzer';
$LN['perms']            = 'Rechte';
$LN['actions']			= 'Aktionen';
$LN['uploaded']			= 'Uploaded';
$LN['viewfiles_title']	= 'Dateien ansehen';
$LN['viewfiles_download']	= 'lade als Archiv runter';
$LN['viewfiles_rename']		= 'umbenennen';
$LN['viewfiles_edit']       = 'editier';
$LN['viewfiles_newfile']    = 'Neues Datei';
$LN['viewfiles_savefile']   = 'Datei speichern';
$LN['viewfiles_tarnotset']	= 'Das tar Kommando ist nicht konfiguriert. Herunterladen von Archiven deaktiviert.';
$LN['viewfiles_compressfailed']	= 'Konnte Dateien nicht entpacken';
$LN['viewfiles_uploadnzb']	= 'Download von NZB';

$LN['viewfiles_type_audio']	= 'Audio';
$LN['viewfiles_type_excel']	= 'Excel';
$LN['viewfiles_type_exe']	= 'Exe';
$LN['viewfiles_type_flash']	= 'Flash';
$LN['viewfiles_type_html']	= 'HTML';
$LN['viewfiles_type_iso']	= 'ISO';
$LN['viewfiles_type_php']	= 'PHP';
$LN['viewfiles_type_source']	= 'Sourcecode';
$LN['viewfiles_type_picture']	= 'Bild';
$LN['viewfiles_type_ppt']	    = 'Powerpoint';
$LN['viewfiles_type_script']	= 'Script';
$LN['viewfiles_type_text']	= 'Text';
$LN['viewfiles_type_video']	= 'Video';
$LN['viewfiles_type_word']	= 'Word';
$LN['viewfiles_type_zip']	= 'Zip';
$LN['viewfiles_type_stylesheet']= 'Stylesheet';
$LN['viewfiles_type_icon']	= 'Ikoon';
$LN['viewfiles_type_db']	= 'DB';
$LN['viewfiles_type_folder']	= 'Folder';
$LN['viewfiles_type_file']	= 'Datei';
$LN['viewfiles_type_pdf']	= 'Pdf';
$LN['viewfiles_type_nzb']	= 'NZB';
$LN['viewfiles_type_par2']	= 'Par2';
$LN['viewfiles_type_sfv']	= 'SFV';
$LN['viewfiles_type_playlist']	= 'Playlist';
$LN['viewfiles_type_torrent']	= 'Torrent';
$LN['viewfiles_type_urdd_sh']	= 'URD script';
$LN['viewfiles_type_ebook']     = 'E-Buch';

$LN['user_lists_title'] = 'Spotter lists';
$LN['user_blacklist'] = 'Spots blacklists';
$LN['user_whitelist'] = 'Spots whitelist';
$LN['spotter_id'] = 'Spotter ID';
$LN['source_external']  = 'External';
$LN['source_user']      = 'User added';
$LN['global']           = 'Global';
$LN['personal']         = 'Personal';
$LN['active']           = 'Active';
$LN['disabled']         = 'Disabled';
$LN['nonactive']         = 'Nonactive';


// About:
$LN['about_title']	= '&Uuml;ber URD';
$LN['abouttext1']	= 'URD is a web-based application for downloading usenet binaries.  It is written entirely in PHP, yet uses some external tools to do some of the dirty CPU intensive work.  It stores all the information it needs in a generic database (like MySQL or PostGreSQL).  Articles that belong together are aggregated into sets. Downloading files requires only a few mouse clicks, and when the download is finished it can automatically be verified and extracted.  Downloading from usenet is as easy as using p2p software!';

$LN['abouttext2']	= 'A strong point of URD is that no external websites are required, as URD generates its own download information.  It is possible to create and download an NZB file from specified articles as well.';

$LN['abouttext3'] 	= 'URD is a backronym of Usenet Resource Downloader. The term URD is derived from Nordic cultures referring
to the Well of URD, which is the holy well, the Well Spring, the source of water for the world tree Yggdrasil.
The old English term for it is Wyrd. Conceptually the meaning of URD is closest to Fate.';

// Newsgroup
$LN['ng_title']			= 'Newsgruppen';
$LN['ng_posts']			= 'Artikel';
$LN['ng_lastupdated']	= 'Zuletzt aktualisiert';
$LN['ng_expire_time']	= 'Verfallszeit';
$LN['ng_gensets']		= 'Generate Sets';
$LN['ng_autoupdate']	= 'Automatisches Update';
$LN['ng_searchtext']	= 'Suche in allen Newsgrupppen';
$LN['ng_newsgroups']	= 'Newsgruppen';
$LN['ng_subscribed']	= 'Abonniert';
$LN['ng_tooltip_name']	= 'Der Name der Newsgruppe';
$LN['ng_tooltip_lastupdated']	= 'Wann wurde diese Gruppe zuletzt aktualisiert';
$LN['ng_tooltip_action']	= 'Update/Generate Sets/Expire/Purge';
$LN['ng_tooltip_expire']	= 'Anzahl Tage die Artikel in der Datenbank bleiben';
$LN['ng_tooltip_time']	    = 'Zeit an der der automatische Update l&auml;uft';
$LN['ng_tooltip_autoupdate']	= 'Die H&auml;ufigkeit mit der diese Gruppe aktualisiert wird';
$LN['ng_tooltip_posts']	    = 'Die Anzahl der Artikel in dieser Gruppe';
$LN['ng_tooltip_active']  	= 'Markiert wenn die Gruppe abonniert ist';
$LN['ng_visible']		    = 'Sichtbar';
$LN['ng_minsetsize']		= 'Min/Max Set Gr&ouml;&szlig;e';
$LN['ng_tooltip_visible']	= 'Ist die Gruppe sichtbar';
$LN['ng_tooltip_minsetsize']	= 'Mindestgr&ouml;&szlig;e und hochstegro&szlig;e f&uuml;r ein Set in MB damit es angezeigt wird';
$LN['ng_admin_minsetsize']      = 'Spam lower limit';
$LN['ng_admin_maxsetsize']  = 'Set upper limit';
$LN['ng_tooltip_admin_maxsetsize']    = 'The maximum size a set can have to be added to the database - add k, M, G as suffix, e.g. 100k or 25G';
$LN['ng_tooltip_admin_minsetsize']    = 'The minimum size a set must have to be added to the database - add k, M, G as suffix, e.g. 100k or 25G (spam control)';
$LN['ng_hide_empty']		= 'Leeren Gruppen nicht zeigen';
$LN['ng_adult']             = '18+';
$LN['ng_tooltip_adult']     = 'Only accessible when user has 18+ flag set';
//$LN['ng_tooltip_']		= '';

$LN['user_settings']    = 'Benutzer Einstellungen';
$LN['global_settings']  = 'Global Einstellungen';
$LN['failed']			= 'misslungen';
$LN['success']			= 'begonnen';
$LN['success2']			= 'gelungen';

// preferences
$LN['change_password']      = 'Passwort &auml;ndern ';
$LN['password_changed']     = 'Passwort ge&auml;ndert';
$LN['delete_account']       = 'Kennzeichen l&ouml;schen';
$LN['delete_account_msg']   = 'Kennzeichen l&ouml;schen';
$LN['account_deleted']      = 'Kennzeichen gel&ouml;scht';
$LN['pref_spot_spam_limit']      = 'Spam-Berichte Limit';
$LN['pref_spot_spam_limit_msg']  = 'The number of spam reports with which spots are not displayed';
$LN['pref_title']		    = 'Einstellungen';
$LN['pref_heading']		    = 'Pers&ouml;nliche Einstellungen';
$LN['pref_saved']		    = 'Pers. Einstellungen gespeichert';
$LN['pref_language'] 		= 'Sprache';
$LN['pref_template'] 		= 'Schablone';
$LN['pref_language_msg'] 	= 'Die Anzeigesprache f&uuml;r URD';
$LN['pref_stylesheet']      = 'Stylesheet';
$LN['pref_stylesheet_msg']  = 'The stylesheet used to display URD';
$LN['pref_template_msg'] 	= 'Das Layout f&uuml;r URD';
$LN['pref_index_page_msg']	= 'The default page to be shown after login';
$LN['pref_index_page']		= 'Die Standard-Site';
$LN['pref_login'] 		    = 'Einloggen';
$LN['pref_display'] 		= 'Anzeige';
$LN['pref_downloading'] 	= 'Herunterladen';
$LN['pref_spots']           = 'Spots';
$LN['pref_setcompleteness'] = 'Set Vollst&auml;ndigkeit';
$LN['pref_skip_int'] 		    = 'Interessante Sets nicht verbergen';
$LN['pref_skip_int_msg'] 		= 'Verberg interessante Sets nicht wann alle Sets l&ouml;schen geklickt werd';
$LN['pref_user_scripts']    = 'Run user scripts';
$LN['pref_user_scripts_msg']    = 'The user defined scripts that are run after completion of a download (note: scripts must end in .urdd_sh)';

$LN['pref_default_group']       = 'Standard-Gruppe';
$LN['pref_default_group_msg']   = 'Default group to select in the browse page';
$LN['pref_default_feed']        = 'Standard-Feed';
$LN['pref_default_feed_msg']    = 'Default feed to select in the rss sets page';
$LN['pref_default_spot']        = 'Standard Spot Suche';
$LN['pref_default_spot_msg']    = 'Default spot search to select in the spots page';
$LN['pref_level'] 		= 'Benutzer Erfahrungsniveau';
$LN['pref_level_msg']	= 'The more experience the user has the more options are shown in configuration (if admin) and preferences';
$LN['level_basic']		= 'Basis';
$LN['level_advanced']	= 'Avanciert';
$LN['level_master']		= 'Gro&szlig;meister';

$LN['pref_poster_email']     = 'Poster Email-Adresse';
$LN['pref_poster_name']      = 'Poster Name';
$LN['poster_name']           = 'Poster Name';
$LN['pref_poster_default_text'] = 'Standard message body';
$LN['pref_poster_default_text_msg'] = 'Standard message body used for posting spots, and comments';
$LN['pref_recovery_size']    = 'Percentage par2 Dateien';
$LN['pref_rarfile_size']     = 'Umvang der rar Dateien';
$LN['pref_poster_email_msg'] = 'The email address to use in the posted messages';
$LN['pref_poster_name_msg']  = 'The name to use in the posted messages';
$LN['pref_recovery_size_msg']= 'The percentage of recovery files (par2) that will be created (0 for no recovery files)';
$LN['pref_rarfile_size_msg'] = 'The size the rar files will have in kB (0 to disable rarring)';
$LN['pref_posting']     = 'Posting';
$LN['pref_download_delay']         = 'Download delay';
$LN['pref_download_delay_msg']     = 'The number of minutes the download is paused before starting';
$LN['username']			= 'Benutzername';
$LN['password']			= 'Passwort';
$LN['email']		    = 'E-mail-Adresse';
$LN['fullname']	    	= 'Voller Name';
$LN['newpw']			= 'Neues Passwort';
$LN['oldpw']			= 'Altes Passwort';
$LN['pref_maxsetname']		= 'Max Setnamen-L&auml;nge';
$LN['pref_setsperpage']		= 'Max Zeilen pro Seite';
$LN['pref_minsetsize']		= 'Min Setgr&ouml;&szlig;e in MB';
$LN['pref_maxsetsize']		= 'Max Setgr&ouml;&szlig;e in MB';
$LN['setsize']		    = 'Setgr&ouml;&szlig;e in MB';
$LN['maxage']			= 'Max. Alter in Tagen';
$LN['minage']			= 'Min. Alter in Tagen';
$LN['age']			    = 'Alter in Tagen';
$LN['rating']		    = 'Rating';
$LN['maxrating']		= 'Max. rating (0-10)';
$LN['minrating']		= 'Min. rating (0-10)';
$LN['complete']		    = 'Vollst&auml;ndigkeit %';
$LN['maxcomplete']		= 'Max. Vollst&auml;ndigkeit %';
$LN['mincomplete']		= 'Min. Vollst&auml;ndigkeit %';
$LN['pref_minngsize']		= 'Min Anzahl Artikel in Newsgruppen';
$LN['config_global_hiddenfiles']		= 'Zeige versteckte Dateien nicht';
$LN['config_global_hidden_files_list']= 'Liste versteckter Dateien';
$LN['pref_hiddenfiles']		= 'Zeige versteckte Dateien nicht';
$LN['pref_hidden_files_list']= 'Liste versteckter Dateien';
$LN['pref_defaultsort']		= 'Das Sortierfeld f&uuml;r die Sets';
$LN['pref_buttons']			= 'Suchfunktionen';
$LN['pref_unpar']			= 'Soll par2 automatisch laufen';
$LN['pref_download_par']     = 'Immer par2 Dateien downloaden';
$LN['pref_download_par_msg'] = 'When disabled only download par2 files if they are needed, otherwise always download them anyway';
$LN['pref_unrar']			= 'Soll automatisch entpackt werden';
$LN['pref_delete_files']		= 'L&ouml;sche Dateien nach unrar';
$LN['pref_mail_user']		= 'Sende Nachricht';
$LN['pref_show_subcats']     = 'Show subcats popup for spots';
$LN['pref_show_subcats_msg'] = 'Show a decscription of the subcatogries for a spot in a  popup';
$LN['pref_show_image']       = 'Bild anzeigen f&uuml;r Spots';
$LN['pref_show_image_msg']   = 'Bild anzeigen f&uuml;r Spots in erweitere Spot-Information';
$LN['pref_search_terms']		= 'Suchbegriffe f&uuml;r markierung';
$LN['pref_blocked_terms']	= 'Suchbegriffe f&uuml;r ausblendung';
$LN['spam_reports']             = 'Spam reports';
$LN['pref_use_auto_download']	    = 'Automatisch downloaden';
$LN['pref_use_auto_download_nzb']    = 'Automatisch downloaden wie NZB Datei';
$LN['pref_use_auto_download_nzb_msg']    = 'Automatisch downloaden basiert on Suchebegriffe';
$LN['pref_download_text_file']       = 'Download Artikel ohne Anh&auml;ngsel';

$LN['pref_download_text_file_msg']   = 'Download message text when no attachments is found in the message';
$LN['config_spots_max_categories']   = 'Max. number of categories per spot';
$LN['config_spots_max_categories_msg']   = 'Spots with more than this number of categories are rejected (0 to disable)';
$LN['config_spots_blacklist']   = 'URL for spotter blacklist';
$LN['config_spots_blacklist_msg']   = 'URL that contains a list of IDs of spotters known to be abusers';
$LN['config_spots_whitelist']   = 'URL for spotter whitelist';
$LN['config_spots_whitelist_msg']   = 'URL that contains a list of IDs of spotters known to be valid users';
$LN['config_download_spots_images']      = 'Download Bilder f&uuml;r Spots';
$LN['config_download_spots_images_msg']  = 'Download Bilder f&uuml;r Spots wenn Spots aktualisiert werden ';
$LN['config_download_spots_comments']     = 'Download Kommentare f&uuml;r Spots';
$LN['config_download_spots_comments_msg'] = 'Download Kommentare f&uuml;r Spots wenn Spots aktualisiert werden';
$LN['config_download_spots_reports']      = 'Download Spam-Berichte f&uuml;r Spots';
$LN['config_download_spots_reports_msg']  = 'Download Spam-Berichte f&uuml;r Spots wenn Spots aktualisiert werden';
$LN['config_spot_expire_spam_count']    = 'Spam count upper limit after which spots are expired';
$LN['config_spot_expire_spam_count_msg']    = 'Spots are automatically expired after spam count is exceeded for the spot (0 to disable)';
$LN['config_allow_robots']      = 'Robots zulassen';
$LN['config_allow_robots_msg']  = 'Allow robots to follow and index the URD webpages';
$LN['config_parse_nfo']		    = 'Analysier nfo Datein';
$LN['config_parse_nfo_msg']	    = 'Analysier nfo-Datei am Vorschau';
$LN['config_max_dl_name']	    = 'Maximal download Name';
$LN['config_max_dl_name_msg']	= 'Die maximale L&auml;nge des Namens f&uuml;r Downloads';
$LN['config_nice_value']        = 'Nice wert';
$LN['config_nice_value_msg']    = 'Nice value for external programs such as par2 and rar';
$LN['pref_subs_lang_msg']            = 'Languages for which subtitles will be sought (two letter codes, separated by commas, leave blank to disable)';
$LN['pref_subs_lang']                = 'Untertitelzahl';
$LN['config_maxexpire']	        = 'Maximal Erl&ouml;schzeit';
$LN['config_max_login_count']	= 'Maximal fehlgeschlagen Einlogvesuchen';
$LN['config_max_login_count_msg'] = 'Maximum number of times an failed login may appear before the account gets locked';
$LN['config_maxexpire_msg']	    = 'The maximum number of days that can be set as the expire time for newsgroups and rss feeds';
$LN['config_maxheaders']	    = 'Maximal Artikel pro Ladung';
$LN['config_maxheaders_msg']	= 'The maximum number of headers that are fetched in one batch';
$LN['config_group_filter']      = 'Newsgruppen filter';
$LN['config_group_filter_msg']  = 'Filter f&uuml;r die gruppen die inkludiert werden ';
$LN['config_ftd_group']         = 'Newsgruppe fur Spots NZB Dateien';
$LN['config_ftd_group_msg']     = 'The newsgroup where NZB files from spots can be found';
$LN['config_spots_comments_group_msg']  = 'The newsgroup where comments for spots will be read';
$LN['config_spots_comments_group']      = 'Newsgroup for spots comments';
$LN['config_spots_reports_group']       = 'Newsgroup for spots spam reports';
$LN['config_spots_reports_group_msg']   = 'The newsgroup from which spots spam reports will be read';
$LN['config_spots_group']       = 'Newsgruppe f&uuml;r Spots';
$LN['config_spots_group_msg']   = 'The newsgroup where spots will be read';
$LN['config_extset_group']      = 'Newsgruppe f&uuml;r extsetdata';
$LN['config_extset_group_msg']  = 'The newsgroup where extsetdata will be posted and read';

$LN['config_modules']         = 'Module';
$LN['config_module_groups']   = 'Gruppenindexierung';
$LN['config_module_makenzb']  = 'Erstellung NZB-dateien';
$LN['config_module_usenzb']   = 'NZB-dateien einf&uuml;hren';
$LN['config_module_post']     = 'Hochladen';
$LN['config_module_spots']    = 'Spots';
$LN['config_module_rss']      = 'RSS feeds';
$LN['config_module_sync']     = 'Synchronisieren Erweitere Setinformation';
$LN['config_module_download'] = 'Download von Newsgruppen';
$LN['config_module_viewfiles'] = 'Dateien ansehen';

$LN['config_index_page_root_msg']	= 'The default page to be shown after login';
$LN['config_index_page_root']		= 'Die Standard-Site';

$LN['config_poster_blacklist']         = 'Posters to black list';
$LN['config_poster_blacklist_msg']     = 'Posters whose name or email match with the regular expression on these line are excluded from the sets database';
$LN['config_module_groups_msg']   = 'Indexing groups';
$LN['config_module_makenzb_msg']  = 'Support for creating NZB files';
$LN['config_module_usenzb_msg']   = 'Support for downloading from NZB files';
$LN['config_module_post_msg']     = 'Posting to groups';
$LN['config_module_spots_msg']    = 'Reading spots from the newsgroup server';
$LN['config_module_rss_msg']      = 'RSS feeds support';
$LN['config_module_sync_msg']     = 'Synchronising extended set information';
$LN['config_module_download_msg'] = 'Downloading from newsgroups';
$LN['config_module_viewfiles_msg'] = 'Interne Dateibrowser';

$LN['config_urdd_uid']      = 'Benutzer ID des Urdd';
$LN['config_urdd_gid']      = 'Gruppe ID des urdd';
$LN['config_urdd_uid_msg']  = 'The user ID to which urdd will change when started as root (leave blank for no changing)';
$LN['config_urdd_gid_msg']  = 'The group ID to which urdd will change when started as root (leave blank for no changing)';

$LN['config_queue_size']     = 'Queue Umvang';
$LN['config_queue_size_msg'] = 'Maximum number of tasks that can be in the queue';
$LN['config_nntp_maxdlthreads']     = 'Max threads per download';
$LN['config_nntp_maxdlthreads_msg'] = 'Maximum number threads per download (0 is no limit)';
$LN['pref_basket_type']          = 'Download basket type';
$LN['pref_basket_type_msg']      = 'The type of download basket that is used by default';
$LN['basket_type_small']    = 'Compact';
$LN['basket_type_large']    = 'Extended';
$LN['pref_search_type']		     = 'Suchtyp';
$LN['pref_search_type_msg']	     = 'Das database suchtyp das werd benutzt bei the search terms matching';
$LN['search_type_like']      = 'Einfage Jokerzeichen (LIKE)';
$LN['search_type_regexp']    = 'Regular expression matching (REGEXP)';

$LN['pref_format_dl_dir']        = 'Download directory Format';
$LN['pref_format_dl_dir_msg']    = 'Download directory format appended to the basic download name<br/>' .
    '%c: Kategorie<br/>' .
    '%D: Date<br/>' .
    '%d: Day of the month<br>' .
    '%F: Month name (long)<br/>' .
    '%g: Group name<br/>' .
    '%G: Group ID<br/>' .
    '%m: Month (numeric<br/>' .
    '%M: Month name (short)<br/>' .
    '%n: Setname<br/>' .
    '%s: Download name<br/>' .
    '%u: Username<br/>' .
    '%w: Day of the week<br/>' .
    '%W: Week number<br/>' .
    '%x: X-rated<br/>' .
    '%y: year (2 digits)<br/>' .
    '%Y: year (4 digits)<br/>' .
    '%z: Day of the year<br/>';

$LN['pref_add_setname']         = 'Append setname to download directory';
$LN['pref_add_setname_msg']     = 'Append the setname to download directory in addition to the download directory format string';
$LN['pref_setcompleteness_msg']	= 'Sets mit Vollst&auml;ndigkeit in Prozent mit mindestens diesem Wert werden in der Browse-Seite angezeigt';

$LN['username_msg']		    = 'Der Nuzer mit dem Sie angemeldet sind';
$LN['newpw1_msg']		    = 'Das neue Passwort';
$LN['newpw2_msg']		    = 'Das neue Passwort (Wiederholung)';
$LN['oldpw_msg']		    = 'Das aktuelle Passwort';
$LN['pref_maxsetname_msg']		= 'The maximum size of a setname to be displayed on a page';
$LN['pref_setsperpage_msg']		= 'The number of sets that will be displayed on one page';
$LN['pref_minsetsize_msg']		= 'The minimum size a set must have to show in the overview; smaller sets are ignored';
$LN['pref_maxsetsize_msg']		= 'The maximum size a set must have to show in the overview; larger sets are ignored';
$LN['pref_minngsize_msg']		= 'The minimum number of posts a newsgroup must have to show in the overview';
$LN['pref_hiddenfiles_msg']		= 'When enabled hidden files will not be shown in the files viewer';
$LN['config_global_hiddenfiles_msg']		= 'When enabled hidden files will not be shown in the files viewer';
$LN['config_global_hidden_files_list_msg']	= 'List of files that will be hidden in the files viewer. Separate by newlines, use * and ? as wildcards';
$LN['pref_hidden_files_list_msg']	= 'List of files that will be hidden in the files viewer. Separate by newlines, use * and ? as wildcards';

$LN['pref_defaultsort_msg']	= 'The field that is used for sorting the sets';
$LN['pref_buttons_msg']		= 'Search options in the browse section';
$LN['pref_unpar_msg']		= 'When enabled and the set contains par2 files these will be automatically used to verify and if needed to correct the downloaded files';
$LN['pref_unrar_msg']		= 'When enabled all rar archives will be automatically extracted';
$LN['pref_delete_files_msg']	= 'When enabled and the rar command was successful, all rar and par2 files will be removed';
$LN['pref_mail_user_msg']	= 'Send a message if a download has completed';
$LN['pref_search_terms_msg']	= 'Automatically match these search terms against all subscribed groups and mark as interesting(separate by newlines) ';
$LN['pref_blocked_terms_msg']= 'Automatically match these search terms against all subscribed groups and hide them (separate by newlines)';

$LN['pref_mail_user_sets']       = 'Benachrichtig interesante Sets';
$LN['pref_mail_user_sets_msg']   = 'Send a message if a new interesting set has been found';
$LN['descending']		    = 'Absteigend';
$LN['ascending']		    = 'Aufsteigend';

$LN['settings_imported']	= 'Einstellungen importiert';
$LN['settings_import']		= 'Importier Einstellungen';
$LN['settings_export']		= 'Exportier Einstellungen';
$LN['settings_import_file']	= 'Importier Einstellungendatei';
$LN['settings_notfound']	= 'Datei nicht gefunden oder kein Einstellungen gefunden';
$LN['settings_upload']		= 'Hochlad Einstellungen';
$LN['settings_filename']	= 'Dateiname';

$LN['import_servers']	= 'Importier servers';
$LN['export_servers']	= 'Exportier servers';
$LN['import_groups']	= 'Importier Gruppen';
$LN['export_groups']	= 'Exportier Gruppen';
$LN['import_feeds']		= 'Importier Feeds';
$LN['export_feeds']		= 'Exportier Feeds';
$LN['import_users']		= 'Importier Benutzers';
$LN['export_users']		= 'Exportier Benutzers';
$LN['import_buttons']	= 'Importier Suchm&ouml;gligkeiten';
$LN['export_buttons']	= 'Exportier Suchm&ouml;gligkeiten';
$LN['import_spots_blacklist']		= 'Import spots blacklist';
$LN['export_spots_blacklist']		= 'Export spots blacklist';
$LN['import_spots_whitelist']		= 'Import spots whitelist';
$LN['export_spots_whitelist']		= 'Export spots whitelist';

$LN['pref_use_auto_download_msg']	= 'Automatisch downloaden basiert auf Suchbegriffe';
// pref errors
$LN['error_pwmatch']		= 'Passw&ouml;rten nicht gleich';
$LN['error_pwincorrect']	= 'Passwort falsch';
$LN['error_pwusername']		= 'Passwort &auml;hnelt den Benutzername zu viel';
$LN['error_pwlength']		= 'Passwort zu kurz; gib mindestens '. MIN_PASSWORD_LENGTH . ' Zeichen';
$LN['error_pwsimple']		= 'Passwort zu einfag, use a mix of upper and lower case characters, numbers and other characters';
$LN['error_captcha']        = 'CAPTCHA falsch';
$LN['error_nocontent']      = 'Message too short';
$LN['error_toolong']        = 'Message too long';
$LN['error_filetoolarge']   = 'File too large';
$LN['error_nosubcats']      = 'No subcategories selected';
$LN['error_nzbfilemissing'] = 'NZB file missing';
$LN['error_invalidcategory'] = 'Invalid Category';
$LN['error_imgfilemissing'] = 'Image file missing';

$LN['error_onlyforgrops'] 	= 'Only works for groups';
$LN['error_onlyoneset'] 	= 'Requires more than one set to be in the basket';

$LN['error_feedexists']     = 'An RSS feed with that name already exists';
$LN['error_encryptedrar']       = 'Encrypted rar file';
$LN['error_usercancel']         = 'Cancelled by user';
$LN['error_toomanybuttons']		= 'Zu viel Suchm&ouml;gligkeiten';
$LN['error_invalidbutton']		= 'Ung&uuml;ltige Suchm&ouml;gligkeiten';
$LN['error_invalidemail']		= 'Ung&uuml;ltige E-Mail-Adresse';
$LN['error_invalidpassword']	= 'Falsches passwort';
$LN['error_userexists']		 	= 'Benutzer besteht schon';
$LN['error_acctexpired'] 		= 'Account expired';
$LN['error_notleftblank'] 		= 'Darf nicht leer gelassen werden';
$LN['error_invalidvalue'] 		= 'Falsche Daten';
$LN['error_urlstart'] 			= 'Die URL muss mit http:// beginnen und mit einem / am Ende';
$LN['error_error'] 			    = 'Fehler';
$LN['error_downloadnotfound'] 	= 'Download nicht gefunden';
$LN['error_linknotfound'] 	= 'Link not found';
$LN['error_nzbfailed'] 	    = 'Importing NZB file failed';
$LN['error_invaliddir'] 		= 'Invalid directory';
$LN['error_notmakedir'] 		= 'Could not make directory';
$LN['error_notmaketmpdir']		= 'Could not make tmp directory';
$LN['error_notmakepreviewdir'] 	= 'Could not make preview directory';
$LN['error_dirnotwritable'] 	= 'Directory not writable';
$LN['error_notestfile'] 		= 'Could not create test files';
$LN['error_mustbemore'] 		= 'muss mehr sein als';
$LN['error_mustbeless'] 		= 'muss kleiner sein als';
$LN['error_filenotexec'] 		= 'The file cannot be found or is not executable by the webserver';
$LN['error_noremovedir']        = 'Cannot remove directory';
$LN['error_noremovefile'] 		= 'Kann Datei nicht l&ouml;sen';
$LN['error_noremovefile2'] 		= 'Kann Datei nicht l&ouml;sen; directory not writable';
$LN['error_nodeleteroot'] 		= 'Cannot delete root user';
$LN['error_nosetids'] 			= 'No setIDs given!';
$LN['error_invalidstatus'] 		= 'Invalid status value supplied';
$LN['error_invaliduserid'] 		= 'Falsche userID';
$LN['error_groupnotfound'] 		= 'Gruppe nicht gefunden';
$LN['error_invalidgroupid'] 	= 'Invalid group ID specified';
$LN['error_couldnotreadargs']	= 'Could not read command arguments (register_argc_argv=Off?)';
$LN['error_resetnotallowed'] 	= 'Not allowed to reset configuration';
$LN['error_prefnotfound'] 		= 'Preference not found';
$LN['error_invalidfilename'] 	= 'Falsche Dateiname';
$LN['error_fileexists'] 		= 'Datei besteht schon';
$LN['error_cannotrename'] 		= 'Cannot rename file';
$LN['error_needfilenames'] 		= 'Dateiname benötigt';
$LN['error_usenetserverexists']	= 'Ein Server mit diesem Namen besteht schon';
$LN['error_missingconnection'] 	= 'Ung&uuml;ltige Verbindung Typ gegeben';
$LN['error_missingthreads'] 	= 'Threads must be given';
$LN['error_missinghostname']	= 'Der Hostame muss angegeben werden';
$LN['error_missingname']		= 'Der Name muss angegeben werden';
$LN['error_needatleastoneport'] = 'Mindestens eine Port-Nummer muss angegeben werden';
$LN['error_needsecureport'] 	= 'Secure port needed for encrypted connection';
$LN['error_nosuchserver'] 		= 'Server besteht nicht';
$LN['error_invalidaction']      = 'Unbekannte Aktion';
$LN['error_nameexists']         = 'Ein usenet Server mit diese Name besteht shown';
$LN['error_diskfull']           = 'Unzureichendes Speicherplatz erwartet um den download zu komplettieren';
$LN['error_invalidsetid']       = 'Invalid Set ID given';
$LN['error_couldnotsendmail']   = 'Could not send message';
$LN['error_filetoolarge']       = 'Datei zu gross zum download';
$LN['error_preview_size_exceeded'] = 'Datei zu gross, um eine Vorschau';
$LN['error_post_not_found'] = 'Post nicht gefunden';
$LN['error_pwresetnomail']  = 'Passwort zur&uuml;ckgesetzt, aber konnte kein E-mail schicken';
$LN['error_userupnomail']   = 'Benutzer aktualisiert, aber konnte kein E-mail schicken';
$LN['error_groupnotfound']  = 'Gruppe besteht nicht';
$LN['error_subjectnofound'] = 'Thema fehlt';
$LN['error_posternotfound'] = 'Poster E-Mail-Adresse fehlt';
$LN['error_invalidrecsize'] = 'Invalid recovery size';
$LN['error_invalidrarsize'] = 'Invalid rar file size';
$LN['error_namenotfound']   = 'Poster Name fehlt';
$LN['error_nosetsfound']    = 'Kein sets gefunden';
$LN['error_nousersfound']   = 'Kein Benutzers gefunden';
$LN['error_nowrite']        = 'Konnte Datei nicht speichern';
$LN['error_searchnamenotfound'] = 'Name nicht gefunden';
$LN['error_missingparameter']   = 'Fehlenden Parameter';
$LN['error_nouploaddata']       = 'No content to upload found in';
$LN['error_nameexists']         = 'Suchname besteht schon';
//$LN['error_'] = '';

$LN['error_noserversfound']         = 'Kein Servers gefunden';
$LN['error_nouploadsfound']         = 'Kein Uploads gefunden';
$LN['error_nodownloadsfound']       = 'Kein Downloads gefunden';
$LN['error_nogroupsfound']          = 'Kein gruppen gefunden';
$LN['error_nosearchoptionsfound']   = 'Kein Suchoptionen gefunden';
$LN['error_nofeedsfound']           = 'Kein Feeds gefunden';
$LN['error_notasksfound']           = 'Kein Auftr&auml;gen gefunden';
$LN['error_nojobsfound']            = 'Kein Aufgaben gefunden';
$LN['error_nologsfound']            = 'Kein Logs gefunden';

$LN['error_spotnotfound']       = 'Spot nicht gefunden';
$LN['error_setnotfound']        = 'Set nicht gefunden';
$LN['error_binariesnotfound']   = 'Binaries nicht gefunden';
$LN['error_invalidimage']       = 'Kein korrect Bild';

$LN['error_schedulesnotset']    = 'Schedules konnten nicht gesetzt werden';
$LN['error_unknowntype']        = 'Unbekanntes Typ';
$LN['error_emptybasket']        = 'Basket leer';

// Admin pages:
$LN['adminshutdown']	= 'URD Daemon beenden';
$LN['adminrestart']		= 'URD Daemon wiederanlaufen';
$LN['adminpause']		= 'Alle Aktivit&auml;ten anhalten';
$LN['admincontinue']	= 'Alle Aktivit&auml;ten wieder aufnehmen';
$LN['adminclear']		= 'Bereinigen aller Downloads';
$LN['admincleandb']		= 'Bereinigen ALLER fl&uuml;chtiger Daten';
$LN['adminremoveready']	= 'Bereinigen Info &uuml;ber heruntergeladene Dateien';
$LN['adminpoweron']		= 'Start des URD Daemon';
$LN['adminupdatenglist']	= 'Newsgruppenliste aktualisieren';
$LN['adminupdateblacklist'] = 'Update spots blacklist';
$LN['adminupdatewhitelist'] = 'Update spots whitelist';
$LN['admingensetsallngs']	= 'Generier Sets f&uuml; alle Newsgruppen';
$LN['adminupdateallngs']	= 'Alle Newsgruppen aktualisieren';
$LN['adminexpireallngs']	= 'Herausaltern in allen Newsgruppen';
$LN['adminpurgeallngs']		= 'L&ouml;schen in allen Newsgruppen';
$LN['adminoptimisedb']		= 'Datenbank optimieren';
$LN['admincheckversion']	= 'Auf neue URD-Version pr&uuml;fen';
$LN['admingetsetinfo']		= 'Set-Information herunterladen';
$LN['adminsendsetinfo']		= 'Set-Informationen  senden';
$LN['admincleandir']		= 'Bereinigen des Verzeichnisses';
$LN['adminexpireallrss']	= 'Alle Feeds herausaltern';
$LN['adminpurgeallrss']		= 'Alle Feeds l&ouml;schen';
$LN['adminupdateallrss']	= 'Alle Feeds aktualisieren';
$LN['adminfindservers']     = 'Autokonfigurier Usenet Servers';
$LN['adminfindservers_ext'] = 'Autokonfigurier Usenet Servers (erweitert)';
$LN['adminexport_all']      = 'Exportier alle Einstellungen';
$LN['adminimport_all']      = 'Importier alle Einstellungen';
$LN['adminupdate_spots']    = 'Spots aktualisieren';
$LN['adminupdate_spotscomments']    = 'Update spots comments';
$LN['adminupdate_spotsimages']    = 'Update spots images';
$LN['adminexpire_spots']    = 'Spots Herausaltern';
$LN['adminpurge_spots']     = 'Spots L&ouml;schen';

// register
$LN['reg_disabled']		= 'Einschreibung ist deaktivert';
$LN['reg_title']		= 'Einschreibung';
$LN['reg_codesent']		= 'Ihr Aktivierungskennzeichen gesandt';
$LN['reg_status']		= 'Einschreibung Status';
$LN['reg_activated']	= 'Ihr Kennzeichen ist aktiviert. Geh weiter zu';
$LN['reg_activated_/link']= 'Einloggung';
$LN['reg_pending']		= 'Ihr Kennzeichen ist anh&auml;ngig. Warte bis eine Admin es aktiviert.';
$LN['reg_form']			= 'F&uuml;ll das Formular aus vor ein Kennzeichen';
$LN['reg_username']		= 'Benutzer Name';
$LN['reg_fullname']		= 'Voller Name';
$LN['reg_email']		= 'E-mail';
$LN['reg_password']		= 'Passwort';
$LN['reg_again']		= 'wieder';

//admin controls
$LN['control_title']		= 'Daemon Steuerung';
$LN['control_options']		= 'Optionen';
$LN['control_jobs']		    = 'Aufgaben';
$LN['control_threads']		= 'Threads';
$LN['control_queue']		= 'Queue';
$LN['control_servers']		= 'Servers';
$LN['control_uptime']		= 'Betriebszeit';
$LN['control_load']         = 'Systembelastung';
$LN['control_diskspace']    = 'Disk space';

$LN['control_cancelall']    = 'Alle Auftr&auml;gen abbrechen';
/// posting
$LN['post_subject']         = 'Thema';
$LN['post_delete_files']    = 'Dateien l&ouml;schen';
$LN['post_delete_filesext'] = 'Delete temporary files created (e.g. rar and par2 files)';
$LN['post_postername']      = 'Hochladername';
$LN['post_posteremail']     = 'Email-Adresse des Absenders';
$LN['post_recovery']        = 'Recovery percentage';
$LN['post_rarfiles']        = 'Rarfile size';
$LN['post_newsgroup']       = 'Newsgruppe';
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
$LN['jobs_title'] 		= 'Zeitgesteurte Aufgaben';
$LN['jobs_command'] 	= 'Kommando';
$LN['jobs_period'] 		= 'Periode';
$LN['jobs_user'] 		= 'Benutzer';

// admin tasks
$LN['tasks_title'] 		    = 'Auftr&auml;ge';
$LN['tasks_description'] 	= 'Beschreibung';
$LN['tasks_progress'] 		= 'Fortschritt';
$LN['tasks_added'] 		    = 'Zugef&uuml;gt';
$LN['tasks_lastupdated'] 	= 'Zuletzt aktualisiert';
$LN['tasks_comment'] 		= 'Kommentar';

// admin config
$LN['config_title']		    = 'Konfiguration';
$LN['config_setinfo']		= 'Set Aktualisierung';
$LN['config_urdd_head']		= 'URD Daemon';
$LN['config_nntp_maxthreads'] 		= 'Maximum number of NNTP connections';
$LN['config_urdd_maxthreads'] 	= 'Maximum number of total threads';
$LN['config_spots_expire_time']  = 'Expire time for sets (in days)';
$LN['config_spots_expire_time_msg']  = 'Expire time for sets (in days); note this overwrites the values set for the respective newsgroup';
$LN['config_default_expire_time'] 		= 'Standard Erlosch Zeit (in Tage)';
$LN['config_expire_incomplete'] = 'Default expire time for incomplete sets (in days, 0 to disable)';
$LN['config_expire_percentage'] = 'Percentage completeness for early expiration of sets';
$LN['config_auto_expire']	= 'Erlosch nach Aktualiserung';
$LN['config_auto_expire_msg']= 'Old messages will be removed after an update is completed';
$LN['config_auto_getnfo']	= 'Auto-download of nfo files';
$LN['config_auto_getnfo_msg']= 'Automatically download and parse nfo files after updating a newsgroup';
$LN['config_period_getspots']	    = 'Download spots';
$LN['config_period_getspots_msg']	= 'Download spots';
$LN['config_period_getspots_blacklist']	    = 'Download spots blacklist';
$LN['config_period_getspots_blacklist_msg']	= 'Schedule when the spots blacklist will be downloaded';
$LN['config_period_getspots_whitelist']	    = 'Download spots whitelist';
$LN['config_period_getspots_whitelist_msg']	= 'Schedule when the spots whitelist will be downloaded';
$LN['pref_cancel_crypted_rars'] = 'Verschlusselte downloads abbrechen';
$LN['config_clickjack']     = 'Enable clickjack prevention';
$LN['config_clickjack_msg'] = 'Enable clickjack prevention to ensure that URD is only accessed in a full page and not in a frame';
$LN['config_need_challenge']     = 'Enable XSS prevention';
$LN['config_need_challenge_msg'] = 'Enable cross-site scripting prevention to ensure that URD functions cannot be exploited from other sites';
$LN['config_use_encrypted_passwords'] = 'Store usenet account passwords encrypted';
$LN['config_use_encrypted_passwords_msg'] = 'Passwords are stored in an encrypted format; using a keystore separate file to store the key';
$LN['config_keystore_path']         = 'Location of the key store';
$LN['config_keystore_path_msg']     = 'The directory where the key store will be placed';
$LN['config_dlpath'] 		= 'Datei hier speichern';
$LN['config_pidpath']       = 'Location of the PID file';
$LN['config_pidpath_msg']   = 'The location of the PID file used to prevent starting multiple instances of URDD (leave blank for none)';
$LN['config_urdd_host'] 	= 'URDD hostname';
$LN['config_urdd_port'] 		= 'URDD port';
$LN['config_urdd_restart'] 		= 'Alte Auftr&auml;ge neu starten';
$LN['config_urdd_daemonise']     = 'Start URDD as a background process';
$LN['config_urdd_daemonise_msg'] = 'Start URDD as a background process (daemon)';
$LN['config_admin_email'] 	= 'Administrator Email-Adresse';
$LN['config_baseurl'] 	    = 'Basis URL';
$LN['config_shaping'] 	    = 'Enable traffic shaping';
$LN['config_maxdl']		    = 'Max download bandwidth (kB/s) per connection';
$LN['config_maxul']		    = 'Max upload bandwidth (kB/s) per connection';
$LN['config_register'] 		= 'Einschreibung zulassen';
$LN['config_auto_reg'] 	= 'Automatically accept account';
$LN['config_urdd_path'] 		= 'urdd';
$LN['config_unpar_path'] 		= 'par2';
$LN['config_unrar_path'] 	= 'unrar';
$LN['config_unace_path'] 	= 'unace';
$LN['config_tar_path'] 		= 'tar';
$LN['config_rar_path'] 		= 'rar';
$LN['config_un7zr_path'] 	= 'un7za';
$LN['config_gzip_path'] 		= 'gzip';
$LN['config_unzip_path']     = 'unzip';
$LN['config_unarj_path'] 	= 'unarj';
$LN['config_subdownloader_path']         = 'subdownloader';
$LN['config_subdownloader_path_msg']     = 'The path where the program subdownloader can be found (optional)';
$LN['config_file_path'] 		    = 'file';
$LN['config_yydecode_path'] 		= 'yydecode';
$LN['config_yyencode_path'] 		= 'yyencode';
$LN['config_cksfv_path'] 		= 'cksfv';
$LN['config_trickle_path'] 		= 'trickle';
$LN['config_period_update'] 	= 'Check for updates of URD';
$LN['config_period_opt'] 	= 'Optimalisier Database';
$LN['config_period_ng'] 	= 'Aktualiser newsgruppen Liste';
$LN['config_period_cd'] 		= 'Clean preview and tmp directory';
$LN['config_period_cu']            = 'Period of inactive users';
$LN['config_period_cu_msg']        = 'Period of inactivity of non-admin users after which they will be removed in days ';
$LN['config_users_clean_age']       = 'Clean inactive users';
$LN['config_users_clean_age_msg']   = 'Clean inactive, non-admin users after a period of inactivity (in days)';
$LN['config_period_cdb'] 		= 'Clean database of volatile information';
$LN['config_scheduler']		= 'URDD Steuerprogramm';
$LN['config_networking']	= 'Networking';
$LN['config_extprogs']		= 'Programmen';
$LN['config_maintenance']	= 'Unterhalt';
$LN['config_socket_timeout']= 'Socket timeout';
$LN['config_socket_timeout_msg']= 'The number of seconds after which a socket will timeout and the connection is closed';
$LN['config_urdd_connection_timeout']    = 'URDD connection timeout';
$LN['config_urdd_connection_timeout_msg']= 'The number of seconds after which a connection to URDD will timeout and is closed; defaults to 30';
$LN['config_auto_download']	= 'Allow automatic downloading';
$LN['config_db_intensive_maxthreads']	    = 'Maximum database intesive threads';
$LN['config_db_intensive_maxthreads_msg']	= 'The maximum number of threads that require heavy access to the database';
$LN['config_check_nntp_connections']	= 'Check usenet connections at startup';
$LN['config_check_nntp_connections_msg']= 'Select the number of possible concurrent connections to an NNTP server automatically at startup';
$LN['config_nntp_all_servers']              = 'Allow downloads to run on all servers concurrently';
$LN['config_nntp_all_servers_msg']          = 'Allow downloads to run with the maximum number of NNTP threads on all enabled servers, instead of sticking to one server per download';
$LN['config_clean_dir_age'] 	    = 'Age of removed files';
$LN['config_clean_dir_age_msg']	    = 'The age a file must have before it is removed by the clean dir command (in days)';
$LN['config_clean_db_age'] 	        = 'Age of volatile database info ';
$LN['config_clean_db_age_msg']	    = 'The age a database information must have before it is removed by the clean database command (in days; 0 is disabled)';
$LN['config_keep_interesting']		        = 'Keep interesting articles on expire';
$LN['config_keep_interesting_msg']	        = 'Keep articles marked interesting when expiring sets';
$LN['config_replacement_str']       = 'Download name replacement text';
$LN['config_replacement_str_msg']   = 'Text to replace inappropriate characters in download name with';

$LN['config_compress_nzb']          = 'Komprimier NZB Dateien';
$LN['config_compress_nzb_msg']      = 'Compress NZB files after downloading them';
$LN['config_total_max_articles']    = 'Maximum articles downloaded per update';
$LN['config_total_max_articles_msg']	= 'Maximum number of articles that is downloaded per update (0 is no limit)';
$LN['config_webdownload']	        = 'Allow download in web interface';
$LN['config_webdownload_msg']	    = 'Users can download files as tarballs in view files page';
$LN['config_webeditfile']	        = 'Allow editing files in web the interface';
$LN['config_webeditfile_msg']	    = 'Users can edit files in the view files page';
$LN['config_globalsettings']	    = 'Global Einstellungen';
$LN['config_notifysettings']    = 'Notify settings';
$LN['config_default_stylesheet']     = 'Standard stylesheet';
$LN['config_default_stylesheet_msg'] = 'The stylesheet used when none is selected or one cannot be found';

$LN['config_mail_account_activated']        = 'Account activated message';
$LN['config_mail_account_activated_msg']    = 'Mail sent to the user when the account has been activated';
$LN['config_mail_activate_account']         = 'Activate account message';
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
$LN['config_mail_password_reset_msg']       = 'Mail sent to the user when the new password';

$LN['config_default_template']	= 'Standard template';
$LN['config_default_template_msg']	= 'The template used when none is selected or one cannot be found';
$LN['config_default_language_msg']	= 'The language used when none is selected or one cannot be found';
$LN['config_default_language']	= 'Standard Sprache';
$LN['config_scheduler_msg']		= 'Enable scheduling of automatic jobs in URDD';
$LN['config_log_level']		    = 'Log niveau';
$LN['config_permissions_msg']	= 'Default permissions for downloaded files';
$LN['config_permissions']	    = 'Download permissions';
$LN['config_group']		        = 'Gruppe';
$LN['config_group_msg']		    = 'The group for all downloaded files';
$LN['config_maxfilesize_msg']	= 'The max filesize to view in viewfiles in kB, 0 for no limit';
$LN['config_maxpreviewsize_msg']	= 'The max filesize to preview in kB, 0 for no limit';
$LN['config_maxbuttons']	= 'Maximal zahl Suchm&ouml;gligkeiten';
$LN['config_maxbuttons_msg']	= 'The maximum number of search options that are shown on the browse page';
$LN['config_nntp_maxthreads_msg'] 	= 'The number of parallel connections that the URD daemon can use';
$LN['config_maxfilesize']	= 'Max filesize to view in viewfiles';
$LN['config_maxpreviewsize']    = 'Max filesize to preview';
$LN['config_urdd_maxthreads_msg'] 	= 'The number of parallel tasks that the URD daemon will carry out';
$LN['config_default_expire_time_msg'] 	= 'The default number of days after which sets will be regarded expired';
$LN['config_expire_incomplete_msg']     = 'The default number of days after which incomplete sets will be regarded expired';
$LN['config_expire_percentage_msg']     = 'The upperbound percentage a set may have to be regarded incomplete for early expiration';
$LN['pref_cancel_crypted_rars_msg']   = 'Analyze files as they are downloaded, and cancel the download if an encrypted RAR file is detected (if the password is not known)';
$LN['config_dlpath_msg'] 	            = 'The path where to URD will download all the files';
$LN['config_urdd_host_msg'] 	        = 'The hostname or IP address of the URD daemon; defaults to localhost (note IPv6 addresses need to be enclosed by [] e.g. [::1])';
$LN['config_urdd_port_msg'] 	= 'The port number of the URD daemon; defaults to 11666';
$LN['config_urdd_restart_msg'] 	= 'Tasks that were running when the URD daemon crashed will be restarted if this button is checked';
$LN['config_admin_email_msg'] 	= 'Die E-mail-Adress des Administrators';
$LN['config_baseurl_msg'] 	= 'The base URL of your URD website';
$LN['config_shaping_msg'] 	= 'Use traffic shaping to limit the bandwidth used by urdd';
$LN['config_maxdl_msg']		= 'The maximum bandwidth the URD daemon will use to download from the news server';
$LN['config_maxul_msg']		= 'The maximum bandwidth the URD daemon will use to upload to the news server';
$LN['config_register_msg'] 	= 'If checked registration by users is possible from the login page';
$LN['config_auto_reg_msg'] 	= 'If not checked the administrator has to permit the account manually, otherwise the account is accepted automatically';
$LN['config_urdd_path_msg'] 		= 'The path where the URD daemon start up file can be found (urdd.sh)';
$LN['config_unpar_path_msg'] 		= 'The path where the program par2 can be found (optional)';
$LN['config_unrar_path_msg'] 	= 'The path where the program rar or unrar can be found for extraction (optional)';
$LN['config_rar_path_msg'] 	    = 'The path where the program rar can be found for compression (optional)';
$LN['config_tar_path_msg'] 		= 'The path where the program tar can be found (optional)';
$LN['config_unace_path_msg'] 	= 'The path where the program unace can be found (optional)';
$LN['config_un7zr_path_msg'] 	= 'The path where the program 7za, 7zr or 7z can be found (optional)';
$LN['config_unzip_path_msg'] 	= 'The path where the program unzip can be found (optional)';
$LN['config_gzip_path_msg'] 	    = 'The path where the program gzip can be found (optional)';
$LN['config_unarj_path_msg'] 	= 'The path where the program unarj can be found (optional)';
$LN['config_file_path_msg'] 		= 'The path where the program file can be found';
$LN['config_yydecode_path_msg'] 	= 'The path where the program yydecode can be found';
$LN['config_yyencode_path_msg'] 	= 'The path where the program yyencode can be found';
$LN['config_cksfv_path_msg'] 	= 'The path where the program cksfv can be found (optional)';
$LN['config_trickle_path_msg'] 	= 'The path where the program trickle can be found (optional)';
$LN['config_period_update_msg'] 	= 'The frequency with which the availability of a new version is checked';
$LN['config_period_opt_msg'] 	= 'The frequency with which the database is optimised';
$LN['config_period_ng_msg'] 	= 'The frequency with which the newsgroup list is updated';
$LN['config_period_cd_msg'] 	= 'The frequency with which the preview and tmp directory are cleared';
$LN['config_period_cdb_msg'] 	= 'The frequency with which the volatile information is removed from the database';
$LN['config_log_level_msg']	= 'The log level of the URD daemon';
$LN['config_period_sendinfo']	    = 'Send setinfo';
$LN['config_period_sendinfo_msg']	= 'Send information to URDland.com';
$LN['config_period_getinfo']	    = 'Get setinfo';
$LN['config_period_getinfo_msg']	= 'Get information from URDland.com';
$LN['config_sendmail']		    = 'Allow e-mails to be sent';
$LN['config_sendmail_msg']	    = 'If checked, e-mails may be sent for things like forgotten and resetting passwords, completed downloads.';
$LN['config_follow_link']       = 'Follow links in NFO files when updating';
$LN['config_follow_link_msg']   = 'If checked, links in NFO files are automatically parsed after group updating';
$LN['config_auto_download_msg']	= 'Permit users to automatically download based on search terms';

$LN['config_allow_global_scripts_msg'] 	= 'Allow scripts set by administrators to run after completion of a download (note: scripts must end in .urdd_sh)';
$LN['config_allow_global_scripts']	    = 'Globale skripte erlauben';
$LN['config_allow_user_scripts_msg'] 		= 'Allow scripts set by users to run after completion of a download (note: scripts must end in .urdd_sh)';
$LN['config_allow_user_scripts']		    = 'Benutzer skripte erlauben';

$LN['config_perms']['none'] = 'Nicht &auml;ndern';
$LN['config_perms']['0400'] = 'Eigent&uuml;mer read only (0400)';
$LN['config_perms']['0440'] = 'Eigent&uuml;mer und Gruppe read only (0440)';
$LN['config_perms']['0444'] = 'Jeder Lesen (0444)';
$LN['config_perms']['0600'] = 'Eigent&uuml;mer Lesen &amp; Schreiben (0600)';
$LN['config_perms']['0640'] = 'Eigent&uuml;mer Lesen &amp; Schreiben, Gruppe read only (0640)';
$LN['config_perms']['0644'] = 'Eigent&uuml;mer Lesen &amp; Schreiben, Rest read only (0644)';
$LN['config_perms']['0660'] = 'Eigent&uuml;mer und Gruppe Lesen &amp; Schreiben (0660)';
$LN['config_perms']['0664'] = 'Eigent&uuml;mer und Gruppe Lesen &amp; Schreiben, Rest read only (0664)';
$LN['config_perms']['0666'] = 'Jeder Lesen und Schreiben (0666)';

$LN['config_prog_params'] 		= 'Programmeparameters';

$LN['config_urdd_pars'] 		= 'urdd';
$LN['config_unpar_pars'] 		= 'par2';
$LN['config_unrar_pars'] 		= 'unrar';
$LN['config_rar_pars'] 		    = 'rar';
$LN['config_unace_pars'] 		= 'unace';
$LN['config_tar_pars'] 			= 'tar';
$LN['config_un7zr_pars'] 		= 'un7za';
$LN['config_unzip_pars'] 		= 'unzip';
$LN['config_gzip_pars'] 		= 'gzip';
$LN['config_unarj_pars'] 		= 'unarj';
$LN['config_yydecode_pars'] 	= 'yydecode';
$LN['config_yyencode_pars'] 	= 'yyencode';
$LN['config_subdownloader_pars'] 	 = 'subdownloader';
$LN['config_subdownloader_pars_msg'] = 'subdownloader parameters';

$LN['config_urdd_pars_msg'] 	= 'urdd parameters';
$LN['config_unpar_pars_msg'] 	= 'par2 parameters';
$LN['config_unrar_pars_msg']    = 'unrar parameters';
$LN['config_rar_pars_msg'] 	    = 'rar parameters';
$LN['config_unace_pars_msg']	= 'unace parameters';
$LN['config_tar_pars_msg'] 	    = 'tar parameters';
$LN['config_un7zr_pars_msg'] 	= 'un7za parameters';
$LN['config_gzip_pars_msg'] 	= 'gzip parameters';
$LN['config_unarj_pars_msg'] 	= 'unarj parameters';
$LN['config_yydecode_pars_msg']	= 'yydecode parameters';
$LN['config_yyencode_pars_msg'] = 'yyencode parameters';
$LN['config_unzip_pars_msg'] 	= 'unzip parameters';

$LN['config_auto_login']      = 'Automatisch anmelden wie';
$LN['config_auto_login_msg']  = 'Automatisch wie der angegebene Benutzer anmelden. Freilassen um deaktivieren';

// admin log
$LN['log_title']		= 'Log Datei';
$LN['log_nofile']		= 'Kein Log datei gefunden';
$LN['log_seekerror']	= 'Could not read entire file';
$LN['log_unknownerror']	= 'An unexpected error occurred';
$LN['log_header']		= 'Log info';
$LN['log_date']			= 'Date';
$LN['log_level']		= 'Niveau';
$LN['log_msg']			= 'Nachricht';
$LN['log_notopenlogfile']	= 'Could not open logfile';
$LN['log_lines']		= 'Linien';

// FAQ
$LN['faq_title'] 		= 'FAQ';

//Manual
$LN['manual_title'] 	= 'Handbuch';

//admin users
$LN['users_title']		= 'Anwender';
$LN['users_addnew']		= 'Neue Nutzer zuf&uuml;gen';
$LN['users_isadmin']	= 'Admin';
$LN['users_autodownload']   = 'Allow autodownload';
$LN['users_fileedit']       = 'Datei bearbeiten';
$LN['users_post']       = 'Hochlader';
$LN['users_post_help']  = 'Dieser Benutzer kann auf den Newsserver hochladen';
$LN['users_allow_erotica']  = 'Allow Adult content';
$LN['users_allow_update']   = 'Allow updating databases';
$LN['users_add']		= 'Zuf&uuml;gen';
$LN['users_resetpw']	= 'Passwort zur&uuml;setzen und zuschicken';
$LN['users_edit']		= 'Nutzer &auml;ndern';
$LN['users_delete']		= 'Nutzer l&ouml;schen';
$LN['users_enable']         = 'Enable user';
$LN['users_disable']         = 'Disable user';
$LN['users_rights'] 	= 'Set Bearbeiter';
$LN['users_rights_help'] 	= 'Erlaubt dem Nuzer Set-Unformationen in der Browse-Seite zu editieren';
$LN['users_last_active'] 	= 'Zuletzt activ';

$LN['error_noadmin']		= 'No administrator privileges';
$LN['error_accessdenied']   = 'Zugriff verweigert';
$LN['error_invalidfullname']	= 'Invalid fullname';
$LN['error_invalidusername']	= 'Benutzername ungültig';
$LN['error_userexists']	    = 'User already exists';
$LN['error_invalidid']		= 'Invalid ID given';
$LN['error_nosuchuser']	    = 'Benutzer besteht nicht';
$LN['error_nouserid']		= 'No user ID given';
$LN['error_invalidchallenge']	= 'Your session has expired. Please relead the page and try again. The action is cancelled (Press reload and try again).';
$LN['error_toomanydays']	= 'Es gibt nur 24 Stunden pro Tag';
$LN['error_toomanymins']	= 'Es gibt nur 60 minuten pro Stunde';
$LN['error_bogusexptime']	= 'Bogus expiry time entered';
$LN['error_invalidupdatevalue']= 'Invalid update value received';
$LN['error_nodlpath']		= 'Download path not set';
$LN['error_dlpathnotwritable']	= 'Download path not writable';
$LN['error_setithere']		= 'Set it here';
$LN['error_nousers']		= 'No users found, please re-run the install script';
$LN['error_filenotallowed']	= 'Not allowed to access file';
$LN['error_filenotfound']	= 'Datei nicht gefunden';
$LN['error_filereaderror']	= 'File could not be read';
$LN['error_dirnotfound']	= 'Cannot open directory';
$LN['error_unknown_sort']	= 'Unknown sort order';
$LN['error_invalidlinescount']	= 'Lines must be numeric';
$LN['error_urddconnect']	= 'Could not connect to URD daemon';
$LN['error_createdlfailed']	= 'Could not create download';
$LN['error_setsnumberunknown']	= 'Could not determine total number of sets';
$LN['error_noqueue']		= 'No queue found...';
$LN['error_novalidaction']	= 'No valid action found.';
$LN['error_readnzbfailed']	= 'Could not read in NZB file';
$LN['error_nopartsinnzb']	= 'No parts identified in NZB file';
$LN['error_invalidgroup']	= 'Invalid group; group name must exist in /etc/group';
$LN['error_cannotchmod']	= 'Changing access rights not permitted';
$LN['error_cannotchgrp']	= 'Changing group is not permitted';
$LN['error_notanumber']	    = 'Kein Zahl';

// Transfers
$LN['transfers_title']		    = 'Downloads';
$LN['transfers_importnzb']	    = 'NZB Datei importieren';
$LN['transfers_import']		    = 'Importieren';
$LN['transfers_clearcompleted']	= 'Bereinigen Downloads';
$LN['transfers_pauseall']	    = 'Alle pausieren';
$LN['transfers_continueall']	= 'Alle fortsetzen';
$LN['transfers_nzblocation']	= 'NZB remote Speicherort';
$LN['transfers_nzblocationext']	= 'This can be a URL (starting with http://) or a local file location (e.g. /tmp/file.nzb';
$LN['transfers_nzbupload']	    = 'Ein lokales NZB hochladen';
$LN['transfers_nzbuploadext']	= 'In case the NZB file is on your local computer, you can upload it to the URD server';
$LN['transfers_uploadnzb']	    = 'NZB hochladen';
$LN['transfers_runparrar']      = 'Lauf par2 und unrar';
$LN['transfers_add_setname']         = 'Append setname to download directory';

$LN['transfers_status_ready']	    = 'Vorbereiten zum Start';
$LN['transfers_status_removed']     = 'gel&ouml;scht';
$LN['transfers_status_postactive']  = 'Posting';
$LN['transfers_status_queued']	    = 'Warteschlange';
$LN['transfers_status_active']	    = 'Lade herunter';
$LN['transfers_status_finished']    = 'Beendet';
$LN['transfers_status_cancelled']   = 'Abgebrochen';
$LN['transfers_status_paused']	    = 'Angehalten';
$LN['transfers_status_stopped']	    = 'Gestoppt';
$LN['transfers_status_shutdown']    = 'Beende';
$LN['transfers_status_error']	    = 'Fehler';
$LN['transfers_status_yyencodefailed'] = 'Yenc encoding fehlgeschlagen';
$LN['transfers_status_complete']    = 'Verarbeite';
$LN['transfers_status_rarfailed']   = 'Einpacken fehlgeschlagen';
$LN['transfers_status_unrarfailed'] = 'Auspacken fehlgeschlagen';
$LN['transfers_status_par2failed']  = 'Par2 fehlgeschlagen';
$LN['transfers_status_cksfvfailed'] = 'Cksfv fehlgeschlagen';
$LN['transfers_status_dlfailed']    = 'Articles verschwunden';
$LN['transfers_status_failed']      = 'Misslungen';
$LN['transfers_status_running']     = 'Active';
$LN['transfers_status_crashed']     = 'Crashed';

$LN['transfers_linkview']	= 'Dateien ansehen';
$LN['transfers_linkstart']	= 'Start';
$LN['transfers_linkedit']	= 'Eigenschaften editieren';
$LN['transfers_details']	= 'Transfer Details';
$LN['transfers_name']		= 'Download Name';
$LN['transfers_archpass']	= 'Archiv Passwort';
$LN['transfers_head_started']	= 'Gestartet';
$LN['transfers_head_dlname']	= 'Name des Downloads';
$LN['transfers_head_progress']	= 'Fortschritt';
$LN['transfers_head_speed'] 	= 'Geschw.';
$LN['transfers_head_username']	= 'Benutzer';
$LN['transfers_head_options']	= 'Optionen';
$LN['transfers_unrar']	        = 'Unrar';
$LN['transfers_unpar']	        = 'Unpar';
$LN['transfers_deletefiles']	= 'L&ouml;sche Dateien';
$LN['transfers_subdl']          = 'Untertitel laden';
$LN['transfers_badrarinfo']     = 'Die rar Log ansehe';
$LN['transfers_post']           = 'Hochladen';
$LN['transfers_post_spot']      = 'Post spot';
$LN['transfers_badparinfo']     = 'Die par2 Log ansehen';

$LN['transfers_status_rarred']  = 'Gerart';
$LN['transfers_status_par2ed']  = 'Par2 gemacht';
$LN['transfers_status_yyencoded'] = 'Yenc encodiert';
$LN['transfers_head_subject']   = 'Thema';
$LN['transfers_posts']          = 'Uploads';
$LN['transfers_downloads']      = 'Downloads';
$LN['spots_post_started']       = 'Spot is being posted';
// Fatal error
$LN['fatal_error_title']	    = 'Meldung';

// admin_buttons
$LN['buttons_title']    = 'Suchfunktionen';
$LN['buttons_url']		= 'Suche URL';
$LN['buttons_edit']		= '&Auml;ndern';
$LN['buttons_test']		= 'Test';
$LN['buttons_nobuttonid']	= 'No Suchfunktionen ID given';
$LN['buttons_invalidname']	= 'Ung&uuml;ltige Name angegeben';
$LN['buttons_invalidurl']	= 'Ung&uuml;ltige URL angegeben';
$LN['buttons_clicktest']	= 'Anklicken zum pr&uuml;fen';
$LN['buttons_buttonexists']	= 'Es gibt schon eine Such-URL mit diese Name';
$LN['buttons_buttonnotfound']	= 'Es gibt keine Such-URL mit diese Name';
$LN['buttons_editbutton']	= 'Suchfunktion editiern';
$LN['buttons_addbutton']	= 'Ein nueue Such-URL zuf&uuml;gen';

// login
$LN['login_title']		= 'Bitte einloggen';
$LN['login_title2']		= 'Einloggen um Zugriff zu erhalten';
$LN['login_jserror']	= 'Javascript wird ben&ouml;tigt. Bitte aktivieren.';
$LN['login_oneweek']	= 'F&uuml;r eine Woche';
$LN['login_onemonth']	= 'F&uuml;r einen Monat';
$LN['login_oneyear']	= 'F&uuml;r ein Jahr';
$LN['login_forever']	= 'F&uuml;r immer';
$LN['login_closebrowser']  = 'Bis zum Beenden des Browsers';
$LN['login_login']		   = 'Einloggen';
$LN['login_remember']	= 'Eingeloggt bleiben';
$LN['login_bindip'] 	= 'Sitzung an die IP binden';
$LN['login_forgot_password']	= 'Passwort vergessen';
$LN['login_register']   = 'Account anmachen';
$LN['login_failed']     = 'Ihr Benutzername/Passwort Kombination war falsch';

// browse
$LN['browse_allsets']		= 'Alle Sets';
$LN['browse_interesting']	= 'Interessante';
$LN['browse_killed']		= 'Verstekt';
$LN['browse_nzb']		    = 'NZB gemacht';
$LN['browse_downloaded']	= 'Heruntergeladen';
$LN['browse_addedsets']		= 'Zugef&uuml;gte Sets';
$LN['browse_allgroups']		= 'Alle Newsgruppen';
$LN['browse_searchsets']	= 'Suchen in Sets';
$LN['browse_addtolist']		= 'Zu Liste hinzuf&uuml;gen';
$LN['browse_deletedsets']   = 'Sets gel&oumlscht';
$LN['browse_deletedset']    = 'Set gel&oumlscht';
$LN['browse_emptylist']		= 'Leere Liste';
$LN['browse_savenzb']		= 'Speicher NZB Datei';
$LN['browse_download']		= 'Lade runter';
$LN['browse_subject']		= 'Betreff';
$LN['browse_age']		    = 'Alter';
$LN['browse_followlink']	= 'Direct zum Link';
$LN['browse_percent']		= '%';
$LN['browse_removeset']		= 'Zeig dieses Set nicht';
$LN['browse_deleteset']     = 'L&ouml;sche dieses Set';
$LN['browse_resurrectset']	= 'Bring dieses set zur&uuml;ck';
$LN['browse_toggleint']		= 'Interesant umschalten';
$LN['browse_schedule_at']	= 'Starte um';
$LN['browse_invalid_timestamp'] = 'Ung&uuml;ltige Zeitangabe';
$LN['NZB_created']		    = 'NZB Datei geschaffen';
$LN['NZB_file']		        = 'NZB Datei';
$LN['Image_file']           = 'Bild Datei';
$LN['browse_mergesets']		= 'Sets verschmelzen';
$LN['browse_userwhitelisted'] = 'User is on the whitelist';

$LN['browse_download_dir']  = 'Download directory';
$LN['browse_add_setname']   = 'Setname zuf&uuml;gen';

// Preview
$LN['preview_autodisp']		= 'Dateien sollten autmatisch angezeigt werden.';
$LN['preview_autofail']		= 'Wenn nicht, diesen Link w&auml;len';
$LN['preview_view']		    = 'Click here to view the NZB file';
$LN['preview_header']		= 'Vorschau wird runtergeladen';
$LN['preview_nzb']		    = 'To start downloading directly from this NZB file, click this link';
$LN['preview_failed']		= 'Vorschau hat gefehlt';

// FAQ
$LN['faq_content'][1] = array ('What is URD', 'URD is a programme to download binaries from usenet (newsgroups) with a web based interface. It'
                    .' is written entirely in PHP, although it also makes use of a few external proggrams to do some'
                    .' of the CPU intensive work. It stores all the information it needs in a generic database'
                    .' (like MySQL, or PostGreSQL). Articles will be aggregated into sets that appear to belong together.'
                    .' Downloading requires only a few mouse clicks. An NZB file can also be created. When the download is'
                    .' finished it can automatically verify the par2 or sfv files and decompress the results.'
                    .' In the background URDD uses a download program called the URD Daemon (URDD). This daemon handles nearly'
                    .' all of the interaction with the newsgroups, the sets and the downloads.'
                    .' URD is licenced under GPL 3. See the file COPYING for details on the licence.');
$LN['faq_content'][2] = array ('Where does the name come from', 'URD is a backronym of Usenet Resource Downloader. The term URD is derived from Nordic cultures'
                    .' referring to the Well of URD, which is the holy well, the Well Spring, the source of water for the world tree'
                    .' Yggdrasil. The old English term for it is Wyrd. Conceptually the meaning of URD is closest to Fate.');

$LN['faq_content'][3] = array ('What it case it does not work', 'First, check your settings and see if you can get a connection to the NNTP server. Check the apache log and'
    .' URD log (default: /tmp/urd.log). If it is a bug, please report it at the google code website. See <a href="http://sourceforge.net/projects/urd/">the URD google code page</a>. '
    . 'Otherwise discuss it at the forum. See <a href="http://www.urdland.com/forum/">URD land</a>.');

$LN['faq_content'][4] = array ('Does URD support SSL', 'Yes, from version 0.4 it does.');
$LN['faq_content'][5] = array ('Does URD support authenticated connections to the newsserver', 'Yes.');
$LN['faq_content'][6] = array ('Can you add this really cool feature', 'Please fill in a feature request and we&#39;ll consider it. Maybe it ends up in the next version. See the feature requests at <a href="http://sourceforge.net/tracker/?group_id=204007&amp;atid=987882">SourceForge</a>.');
$LN['faq_content'][7] = array ('Can the urdd daemon run on a different machine then the web interface', 'Technically urdd consists of three parts that can be installed on separate machines<ul><li>The database</li><li>URDD</li><li>The web interface</li></ul> However this has not been tested yet.');
$LN['faq_content'][8] = array('Can URD work with NZB files', 'Yes. There are several options to work with NZB files in URD. First of all to use NZB files to download from. In the download page is a possibility to upload a locally stored NZB file. On the same page is also a possibility to provide an external link to an NZB file. Then some newsgroups also post NZB files; using the preview function on an NZB file, will give you the option to directly download from that file. Finally in view files there is an upload button in the actions part for NZB files as well. Outside the web side, you can use a special directory named spool/username that where you can put an NZB file and it will be used to download from. But there is more. URD can also be used to create NZB files from the indexes it has created so you can share it with others. This works the same as actually downloading in the browse page, but you click the NZB button instead. It will be stored in the download subdirectory name nzb/username.');
$LN['faq_content'][9] = array ('How do I upgrade URD to a new version', 'Currently there is no automatic way to do it. Basically this means you have to run the install script of the new version and either chose a different database name, or check the delete existing database and user box.');
$LN['faq_content'][10] = array ('What licence does URD use', 'Most of the code is GPL v3. Some parts are borrowed from other projects and have another licence.');
$LN['faq_content'][11] = array ('Should I use the download tarball or use subversion to get URD', 'It is strongly recommended to use the officially released tarballs and not subversion. The subversion source may be not work at or have half implemented features. There are mostly like nightly builds. So please download the official releases.');
$LN['faq_content'][12] = array ('My question is not here. What now?', 'Please leave a message at the forum at <a href="http://www.URDland.com/forum/">Urdland</a>.');
$LN['faq_content'][13] = array('I would like to donate to this project. How?', 'Awesome! A token of appreciation is always very much welcomed, we do not have too many expenses but hosting does cost some 50 euros per year. The easiest way for us would be through PayPal. Theres a donate button <a href="http://urdland.com/cms/component/option,com_wrapper/Itemid,33/">here</a>. If you want to use a different method, please send us an email at "dev@ urdland . com" or PM on the forum and we will exchange information such as addresses or bank account numbers.');

$LN['manual_content'][1] = array ('General', 'Most parts of the URD website have immediate help in the form of popups. Hovering over a link or a text will show this help function.');

$LN['manual_content'][2] = array ('Newsgroups', 'After installation you can log in to your URD web interface and click on newsgroups and search for the newsgroup you wish to subscribe to. If there are no newsgroups found go to the admin panel and click "Update newsgroup list". If that does not help check the preferences. In the newsgroup overview the expire column shows the number of days after which articles will expire. It is also possible to automatically update the newsgroup. Enter a number, select "days", "hours" or "weeks" and enter the time at which the update will take place and press the go button. Removing a scheduled update can be done by removing the time and pressing the go button.');

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
$LN['manual_content'][14] = array ('Search options','These are the search options as placed on the browse page. The search URL should contain a $q, which will be replaced with the search string');
$LN['manual_content'][15] = array ('Log','Here you can see the URD log file, search it and so on. Check this in case an error occurred.');

$LN['manual_content'][16] = array ('Preferences', 'The Preferences tab can be used to modify most user settings.');

$LN['manual_content'][17] = array ('Status overview', 'On the left of the screen there always is a status overview with the status of the URD daemon, online or offline, the current tasks and the available disk space. Also the logged in user name is shown. This will also show if there is a newer version of URD available.');

$LN['manual_content'][18] = array ('It does not work, now what?', 'First, check your settings and see if you can get a connection to the NNTP server. Rerun the action with the log level set to debug and check the apache log and URD log (default: /tmp/urd.log). If it is a bug, please report it at the google code website. Otherwise discuss it at the <a href="http://www.urdland.com/forum/">URDland forum</a>. Please add as much as possible information in case of reporting bugs or other problems, including relevant log file entries, error messages and settings. The <a href="debug.php">debug page</a> can also be used to collect all information from the URD daemon.');

// ajax_showsetinfo:
$LN['showsetinfo_postedin']	= 'Posted in';
$LN['showsetinfo_postedby']	= 'Posted by';
$LN['showsetinfo_size']		= 'Total Umvang';
$LN['showsetinfo_shouldbe']	= 'Muss sein';
$LN['showsetinfo_par2']		= 'Par2';
$LN['showsetinfo_setname']	= 'Setname';
$LN['showsetinfo_typeofbinary'] = 'Type of binary';

// download basket
$LN['basket_totalsize']		= 'Gesamtgr&ouml;&szlig;e';
$LN['basket_setname']		= 'Downloadname';

// usenet servers
$LN['usenet_title']		= 'Usenet Servers';
$LN['usenet_hostname']	= 'Hostname';
$LN['usenet_port']		= 'Port';
$LN['usenet_secport']	= 'Secure port';
$LN['usenet_authentication']= 'Auth';
$LN['usenet_threads']		= 'Verbindungen';
$LN['usenet_connection']	= 'Verschl&uuml;sselung';
$LN['usenet_needsauthentication']	= 'Ben&ouml;tigt Login';
$LN['usenet_addnew']		= 'Neuen hinzuf&uuml;gen';
$LN['usenet_nrofthreads']	= 'Anzahl der Connections';
$LN['usenet_connectiontype']= 'Connectiontype';
$LN['usenet_name_msg']		= 'The name under which the usenet server will be known';
$LN['usenet_hostname_msg']	= 'The host name of the usenet server (note: IPv6 addresses must be enclosed by [])';
$LN['usenet_port_msg']		= 'The port number of the usenet server for unencrypted connections';
$LN['usenet_secport_msg']	= 'The port number of the usenet server if connected by SSL or TLS';
$LN['usenet_needsauthentication_msg']		= 'Tag if the usenet server requires authentication';
$LN['usenet_username_msg']	= 'The username needed if authentication to the usenet server is required';
$LN['usenet_password_msg']	= 'The password needed if authentication to the usenet server is required';
$LN['usenet_nrofthreads_msg']	    = 'The maximum number of connections that will be run in parallel on this server';
$LN['usenet_connectiontype_msg']	= 'The encryption that is used for the connection to the usenet server';
$LN['usenet_priority']		= 'Priorit&auml;t';
$LN['usenet_priority_msg']	= 'Priorit&auml;t: 1 h&ouml;chste; 100 niedrigste; 0 deaktiviert';
$LN['usenet_enable']		= 'Aktiveren';
$LN['usenet_disable']		= 'Deaktiveren';
$LN['usenet_delete']		= 'L&ouml;sche Server';
$LN['usenet_edit']		    = 'Editiere Server';
$LN['usenet_preferred_msg']	= 'Das ist der prim&auml;re Server, indexiert Newsgruppen';
$LN['usenet_set_preferred_msg']	= 'Diesen als prim&auml;ren Server w&auml;hlen';
$LN['usenet_indexing']      = 'Indexing';
$LN['usenet_addserver']		= 'Neue usenet Server zuf&uuml;gen';
$LN['usenet_editserver']	= 'Editier usenet Server';
$LN['usenet_compressed_headers']        = 'Use compressed headers';
$LN['usenet_compressed_headers_msg']    = 'Use compressed headers for updating groups. May not be supported by all servers. Check for the XZVER command.';
$LN['usenet_posting']          = 'Hochladen';
$LN['usenet_posting_msg']      = 'Hochladen zulassen';

$LN['usenet_preferred']		= 'Bevorzugt';
$LN['usenet_set_preferred']	= 'bevorzugen';

$LN['forgot_title']		= 'Passwort vergessen';
$LN['forgot_sent']		= 'Passwort versandt';
$LN['forgot_mail']		= 'Senden';

$LN['browse_tag_setname']	= 'Setname';
$LN['browse_tag_year']      = 'Jahr';
$LN['browse_tag_name']		= 'Name';
$LN['browse_tag_lang']		= 'Audio Sprache';
$LN['browse_tag_sublang']	= 'Untertitelsprache';
$LN['browse_tag_artist']	= 'Artist';
$LN['browse_tag_quality']	= 'Qualit&auml;t';
$LN['browse_tag_runtime']   = 'Laufzeit';
$LN['browse_tag_movieformat']	= 'Film Format';
$LN['browse_tag_audioformat']	= 'Audio Format';
$LN['browse_tag_musicformat']	= 'Musik Format';
$LN['browse_tag_imageformat']	= 'Bild Format';
$LN['browse_tag_softwareformat']= 'Software Format';
$LN['browse_tag_gameformat']    = 'Spiel format';
$LN['browse_tag_gamegenre']	    = 'Spiel Genre';
$LN['browse_tag_moviegenre']	= 'Film Genre';
$LN['browse_tag_musicgenre']	= 'Musik Genre';
$LN['browse_tag_imagegenre']	= 'Bild Genre';
$LN['browse_tag_softwaregenre']	= 'Software Genre';
$LN['browse_tag_os']		    = 'Betriebssystem';
$LN['browse_tag_genericgenre']	= 'Genre';
$LN['browse_tag_episode']	    = 'Episode';
$LN['browse_tag_moviescore']	= 'Film Rating';
$LN['browse_tag_score']		    = 'Rating';
$LN['browse_tag_musicscore']	= 'Musik Rating';
$LN['browse_tag_movielink']	    = 'Film Link';
$LN['browse_tag_link']		    = 'Film Link';
$LN['browse_tag_musiclink']	    = 'Musik Link';
$LN['browse_tag_serielink']	    = 'Series Link';
$LN['browse_tag_xrated']	    = 'X-Rated';
$LN['browse_tag_note']		    = 'Kommentare';
$LN['browse_tag_author']        = 'Autor';
$LN['browse_tag_ebookformat']   = 'eBuch Format';
$LN['browse_tag_password']      = 'Passwort';
$LN['browse_tag_copyright']     = 'Urheberrecht';

$LN['quickmenu_setsearch']      = 'S&uuml;ch';
$LN['quickmenu_addblacklist']   = 'Add spotter to blacklist';
$LN['quickmenu_addposterblacklist']   = 'Add poster to blacklist';
$LN['quickmenu_addglobalblacklist']   = 'Add spotter to global blacklist';
$LN['quickmenu_addglobalwhitelist']   = 'Add spotter to global whitelist';
$LN['quickmenu_addwhitelist']   = 'Add spotter to whitelist';
$LN['quickmenu_report_spam']    = 'Report spot as spam';
$LN['quickmenu_comment_spot']   = 'Post comment on spot';
$LN['quickmenu_editspot']       = 'Bearbeit Spot';
$LN['quickmenu_setshowesi']     = 'Zeig Set Info';
$LN['quickmenu_seteditesi']     = 'Bearbeit Set Info';
$LN['quickmenu_setguessesi']    = 'Set Info erraten';
$LN['quickmenu_setbasketguessesi']= 'Errate Set Info f&uuml;r alles im Download-basket';
$LN['quickmenu_setguessesisafe']= 'Erate Set Info und validier';
$LN['quickmenu_setpreviewnfo']  = 'Vorschau NFO Datei';
$LN['quickmenu_setpreviewimg']  = 'Vorschau Bild Datei';
$LN['quickmenu_setpreviewnzb']  = 'Vorschau NZB Datei';
$LN['quickmenu_setpreviewvid']  = 'Vorschau Video Datie';
$LN['quickmenu_add_search']     = 'Automatisch markieren';
$LN['quickmenu_add_block']      = 'Automatisch ausblenden';

$LN['blacklist_spotter']        = 'Blacklist spotter?';
$LN['whitelist_spotter']        = 'Whitelist spotter?';

$LN['stats_title'] = 'Statistiken';
$LN['stats_dl']	= 'Downloads';
$LN['stats_pv']	= 'Vorschauen';
$LN['stats_im']	= 'Importierte NZB Dateien';
$LN['stats_gt']	= 'Heruntergeladn NZB Dateien';
$LN['stats_wv']	= 'Web Ansichtsen';
$LN['stats_ps'] = 'Uploads';
$LN['stats_total']	= 'Totales Gro&szlig;e';
$LN['stats_number']	= 'Anzahl';
$LN['stats_user']	= 'Benutzer';
$LN['stats_overview']	= '&Uuml;bersicht';

$LN['stats_spotsbymonth'] = 'Spots pro Monat';
$LN['stats_spotsbyweek'] = 'Spots pro Woche';
$LN['stats_spotsbyhour'] = 'Spots pro Stunde';
$LN['stats_spotsbydow'] = 'Spots pro Tag der Woche';

$LN['feeds_title']	= 'RSS feeds';
$LN['feeds_rss']	= 'RSS feeds';
$LN['feeds_auth']	= 'Auth';
$LN['feeds_tooltip_active']		= 'RSS feed ist active';
$LN['feeds_tooltip_name']		= 'Name der RSS feed';
$LN['feeds_tooltip_posts']		= 'Number of links in the RSS feed';
$LN['feeds_tooltip_lastupdated']= 'Last updated time';
$LN['feeds_tooltip_expire']		= 'Expire time in days';
$LN['feeds_tooltip_visible']	= 'RSS ist sichtbar';
$LN['feeds_tooltip_auth']		= 'RSS Feeds server requires authentication';
$LN['feeds_posts']		    = 'Gro&szlig;e';
$LN['feeds_lastupdated']	= 'Zuletzt aktualisiert';
$LN['feeds_expire_time']	= 'Erlosch Zeit';
$LN['feeds_visible']		= 'Sichtbar';
$LN['feeds_tooltip_action']	= 'Actions';
$LN['feeds_tooltip_autoupdate']	= 'Automatically aktualisieren';
$LN['feeds_autoupdate']	= 'Auto aktualisieren';
$LN['feeds_searchtext']	= 'In alle RSS feeds suchen';
$LN['feeds_url']		= 'URL';
$LN['feeds_tooltip_url']	= 'URL';
$LN['feeds_tooltip_uepev']	= '&Auml;ndern/Aktualisieren/L&ouml;schen/Erloschen/Entleren';
$LN['feeds_edit']		= '&Auml;ndern';
$LN['feeds_addfeed']	= 'Ein neue Feed zuf&uuml;gen';
$LN['feeds_editfeed']	= 'Feed &auml;ndern';
$LN['feeds_allgroups']	= 'Alle Feeds';
$LN['feeds_hide_empty']	= 'Versteck inaktive Feeds';
$LN['menurssfeeds'] 	= 'RSS Feeds';
$LN['menuspots']        = 'Spots';
$LN['menu_overview'] 	= 'Pr&auml;ferenzen';
$LN['menursssets'] 	    = 'RSS sets';
$LN['menugroupsets'] 	= 'Gruppen sets';

$LN['error_invalidfeedid']  = 'Ung&uuml;ltig feed ID';
$LN['error_feednotfound']   = 'Feed nicht gefunden';
$LN['config_formatstrings']	= 'Format Zeichenketten';
$LN['config_formatstring']	= 'Format Zeichenketteng f&uuml;r';

$LN['newcategory']          = 'Neue Kategorie';
$LN['nocategory']           = 'Kein Kategorie';
$LN['category']             = 'Kategorie';
$LN['categories']           = 'Kategorien';
$LN['name']                 = 'Name';
$LN['editcategories']       = 'Kategorie &auml;ndern';
$LN['ng_tooltip_category']  = 'Kategorie';

$LN['post_message']         = 'Ein nachricht hochladen';
$LN['post_messagetext']     = 'Nachricht text';
$LN['post_messagetextext']  = 'The content of the message to post';
$LN['post_newsgroupext2']   = 'The newsgroup the message will be posted to';
$LN['post_subjectext2']     = 'The subject line in the message';

$LN['settype'][urd_extsetinfo::SETTYPE_UNKNOWN] = $LN['config_formatstring'] . ' Unbekannt';
$LN['settype'][urd_extsetinfo::SETTYPE_MOVIE]   = $LN['config_formatstring'] .  ' Film';
$LN['settype'][urd_extsetinfo::SETTYPE_ALBUM]   = $LN['config_formatstring'] .  ' Album';
$LN['settype'][urd_extsetinfo::SETTYPE_IMAGE]   = $LN['config_formatstring'] .  ' Abbildung';
$LN['settype'][urd_extsetinfo::SETTYPE_SOFTWARE] = $LN['config_formatstring'] .  ' Software';
$LN['settype'][urd_extsetinfo::SETTYPE_TVSERIES] = $LN['config_formatstring'] . ' Fernsehserie';
$LN['settype'][urd_extsetinfo::SETTYPE_EBOOK]   = $LN['config_formatstring'] . ' Ebuch';
$LN['settype'][urd_extsetinfo::SETTYPE_GAME]    = $LN['config_formatstring'] . ' Spiel';
$LN['settype'][urd_extsetinfo::SETTYPE_TVSHOW]  = $LN['config_formatstring'] . ' Fernsehsendung';
$LN['settype'][urd_extsetinfo::SETTYPE_DOCUMENTARY] = $LN['config_formatstring'] . ' Dokumentarfilm';
$LN['settype'][urd_extsetinfo::SETTYPE_OTHER]   = $LN['config_formatstring'] . ' Anders';

$LN['settype_syntax'] = '%(n.mc); where <i>()</i> indicates an optional enclosure, can be (), [] or {}; <i>n</i> an optional padding value, <i>.m</i> an optional maximum length value, <i>c</i> a required character designated below (use %% to display a %, also see the php documentation an sprintf):<br/><br/>';

$LN['settype_msg'][urd_extsetinfo::SETTYPE_UNKNOWN] = $LN['settype_syntax'] . 'Unknown settype:<br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%P: password protected<br/>%C: copyrighted material ';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_MOVIE] = $LN['settype_syntax'] . 'Movie settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%y: year<br/>%m: movie format<br/>%a: audio format<br/>%l: language<br/>%s: subtitle language<br/>%x: x-rated<br/>%N: notes<br/>%q: quality<br/>%P: password protected<br/>%C: copyrighted material <br/>';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_ALBUM] = $LN['settype_syntax'] . 'Album settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%y: year <br/>%f: format<br/>%g: genre<br/>%N: notes<br/>%q: quality<br/>%P: password protected<br/>%C: copyrighted material <br/>';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_IMAGE] = $LN['settype_syntax'] . 'Image settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon <br/>%f: format<br/>%g: genre<br/>%N: notes<br/>%q: quality<br/>%x: x-rated<br/>%P: password protected<br/>%C: copyrighted material ';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_SOFTWARE] = $LN['settype_syntax'] . 'Software settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%o: Operating system <br/>%q: quality<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material ';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_TVSERIES] = $LN['settype_syntax'] .  'TV series settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%e: Episode<br/>%m: movie format<br/>%a: audio format<br/>%x: x-rated<br/>%q: quality<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material ';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_EBOOK] = $LN['settype_syntax'] . 'Ebook settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%A: Author<br/>%y: Year<br/>%f: format<br/>%q: quality<br/>%g: genre<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material ';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_GAME] = $LN['settype_syntax'] . 'Game settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%A: Author<br/>%y: Year<br/>%f: format<br/>%q: quality<br/>%g: genre<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material ';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_TVSHOW] = $LN['settype_syntax'] . 'TV Show settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%m: movie format<br/>%y: Year<br/>%e: episode<br/>%f: format<br/>%q: quality<br/>%g: genre<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material ';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_DOCUMENTARY] = $LN['settype_syntax'] . 'Documentary settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>>%A: Author<br/>%y: Year<br/>%f: format<br/>%q: quality<br/>%g: genre<br/>%N: notes<br/>%P: password protected<br/>%C: copyrighted material ';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_OTHER] = $LN['settype_syntax'] . 'Other settype: <br/>%n: name<br/>%t: set type<br/>%T: type dependent icon<br/>%P: password protected<br/>%C: copyrighted material <br/>';

$LN['loading_files']  = 'Datein wirden geladen... bitte warten';
$LN['loading']        = 'Wird geladen... bitte warten';

$LN['spots_allcategories']      = 'Alle Kategorien';
$LN['spots_allsubcategories']   = 'Alle Subkategorien';
$LN['spots_subcategories']      = 'Subkategorien';
$LN['spots_tag']                = 'Tag';
$LN['pref_spots_category_mapping']   = 'Spots category mapping for';
$LN['pref_spots_category_mapping_msg']   = 'Spots category mapping to URD categories';

$LN['pref_custom_values']       = 'Custom values';
$LN['pref_custom']              = 'Custom value';
$LN['config_custom']            = 'Custom value';
$LN['pref_custom_msg']          = 'Custom values that can be used in scripts';
$LN['spots_other']         = 'Anders';
$LN['spots_all']           = 'Alles';
$LN['spots_image']         = 'Bild';
$LN['spots_sound']         = 'Laud';
$LN['spots_game']          = 'Spiel';
$LN['spots_application']   = 'Applikation';
$LN['spots_format']        = 'Formaat';
$LN['spots_source']        = 'Origin';
$LN['spots_language']      = 'Zahl';
$LN['spots_genre']         = 'Genre';
$LN['spots_bitrate']       = 'Bitrate';
$LN['spots_platform']      = 'Platform';
$LN['spots_type']          = 'Typ';

$LN['spots_film']          = 'Film';
$LN['spots_series']        = 'Reihe';
$LN['spots_book']          = 'Buch';
$LN['spots_erotica']       = 'Erotik';

$LN['spots_album']         = 'Album';
$LN['spots_liveset']       = 'Live set';
$LN['spots_podcast']       = 'Podcast';
$LN['spots_audiobook']     = 'H&ouml;buch';
$LN['spots_divx']       = 'DivX';
$LN['spots_wmv']        = 'WMV';
$LN['spots_mpg']        = 'MPG';
$LN['spots_dvd5']       = 'DVD5';
$LN['spots_hdother']    = 'HD Anders';
$LN['spots_ebook']      = 'E-buch';
$LN['spots_bluray']     = 'Blu-ray';
$LN['spots_hddvd']      = 'HD DVD';
$LN['spots_wmvhd']      = 'WMVHD';
$LN['spots_x264hd']     = 'x264HD';
$LN['spots_dvd9']       = 'DVD9';
$LN['spots_cam']        = 'Cam';
$LN['spots_svcd']       = '(S)VCD';
$LN['spots_promo']      = 'Promo';
$LN['spots_retail']     = 'Retail';
$LN['spots_tv']         = 'Fernseh';
$LN['spots_satellite']  = 'Satellit';
$LN['spots_r5']         = 'R5';
$LN['spots_telecine']   = 'Telecine';
$LN['spots_telesync']   = 'Telesync';
$LN['spots_scan']       = 'Scan';

$LN['spots_subs_non']       = 'Kein Untertitel';
$LN['spots_subs_nl_ext']    = 'Niederlandisch Untertitel (externe)';
$LN['spots_subs_nl_incl']   = 'Niederlandisch Untertitel (hardcoded)';
$LN['spots_subs_eng_ext']   = 'Englische Untertitel (externe)';
$LN['spots_subs_eng_incl']  = 'Englische Untertitel (hardcoded)';
$LN['spots_subs_nl_opt']    = 'Niederlandisch Untertitel (optional)';
$LN['spots_subs_eng_opt']   = 'Englisch Untertitel (optional)';
$LN['spots_false']          = 'Falsch';
$LN['spots_lang_eng']       = 'Englisch gesprochen';
$LN['spots_lang_nl']        = 'Niederlandisch gesprochen';
$LN['spots_lang_ger']       = 'Deutsch gesprochen';
$LN['spots_lang_fr']        = 'Franz&ouml;sisch gesprochen';
$LN['spots_lang_es']        = 'Spanisch gesprochen';
$LN['spots_lang_asian']     = 'Asiatisch gesprochen';

$LN['spots_action']        = 'Aktion';
$LN['spots_adventure']     = 'Abenteuer';
$LN['spots_animation']     = 'Animation';
$LN['spots_cabaret']       = 'Kabarett';
$LN['spots_comedy']        = 'Kom&ouml;die';
$LN['spots_crime']         = 'Krimi';
$LN['spots_documentary']   = 'Dokumentarfilm';
$LN['spots_drama']         = 'Drama';
$LN['spots_family']        = 'Familie';
$LN['spots_fantasy']       = 'Fantasie';
$LN['spots_filmnoir']      = 'Film Noir';
$LN['spots_tvseries']      = 'TV Reihe';
$LN['spots_horror']        = 'Horror';
$LN['spots_music']         = 'Musik';
$LN['spots_musical']       = 'Musical';
$LN['spots_mystery']       = 'Mystery';
$LN['spots_romance']       = 'Romantik';
$LN['spots_scifi']         = 'Science-fiction';
$LN['spots_sport']         = 'Sport';
$LN['spots_short']         = 'Kurzfilm';
$LN['spots_thriller']      = 'Thriller';
$LN['spots_war']           = 'Krieg';
$LN['spots_western']       = 'Western';
$LN['spots_ero_hetero']    = 'Erotik (Hetero)';
$LN['spots_ero_gaymen']    = 'Erotik (Homosexuelle)';
$LN['spots_ero_lesbian']   = 'Erotik (Lesbe)';
$LN['spots_ero_bi']        = 'Erotik (bisexuell)';
$LN['spots_asian']         = 'Asiatisch';
$LN['spots_anime']         = 'Anime';
$LN['spots_cover']         = 'Cover';
$LN['spots_comics']        = 'Comics';
$LN['spots_cartoons']      = 'Cartoons';
$LN['spots_children']      = 'Kinder';

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
$LN['spots_compilation']   = 'Kompilation';
$LN['spots_dvd']           = 'DVD';
$LN['spots_vinyl']         = 'Vinyl';
$LN['spots_stream']        = 'Stream';
$LN['spots_variable']      = 'Variabel';
$LN['spots_96kbit']        = '96 kbit';
$LN['spots_lt96kbit']      = '&lt;96 kbit';
$LN['spots_128kbit']       = '128 kbit';
$LN['spots_160kbit']       = '160 kbit';
$LN['spots_192kbit']       = '192 kbit';
$LN['spots_256kbit']       = '256 kbit';
$LN['spots_320kbit']       = '320kbit';
$LN['spots_lossless']      = 'Verlustfrei';

$LN['spots_blues']         = 'Blues / Folk';
$LN['spots_compilation']   = 'Kompilation';
$LN['spots_cabaret']       = 'Kabarett';
$LN['spots_dance']         = 'Dance';
$LN['spots_various']       = 'Divers';
$LN['spots_hardcore']      = 'Hardcore';
$LN['spots_international'] = 'International';
$LN['spots_jazz']          = 'Jazz';
$LN['spots_children']      = 'Kinder / jugend';
$LN['spots_classical']     = 'Klassik';
$LN['spots_smallarts']     = 'Kleinkunst';
$LN['spots_netherlands']   = 'Niederlandisch';
$LN['spots_newage']        = 'New Age';
$LN['spots_pop']           = 'Pop';
$LN['spots_soul']          = 'R&amp;B';
$LN['spots_hiphop']        = 'Hiphop';
$LN['spots_reggae']        = 'Reggae';
$LN['spots_religious']     = 'Religi&oumls';
$LN['spots_rock']          = 'Rock';
$LN['spots_soundtracks']   = 'Soundtrack';
$LN['spots_hardstyle']     = 'Hardstyle';
$LN['spots_asian']         = 'Asiatisch';
$LN['spots_disco']         = 'Disco';
$LN['spots_oldschool']     = 'Old school';
$LN['spots_metal']         = 'Metal';
$LN['spots_country']       = 'Country';
$LN['spots_dubstep']       = 'Dubstep';
$LN['spots_nederhop']      = 'Nederhop';
$LN['spots_dnb']           = 'DnB';
$LN['spots_electro']       = 'Electro';
$LN['spots_folk']       = 'Folk';
$LN['spots_soul']       = 'Soul';
$LN['spots_trance']     = 'Trance';
$LN['spots_balkan']     = 'Balkan';
$LN['spots_techno']     = 'Techno';
$LN['spots_ambient']    = 'Ambient';
$LN['spots_latin']      = 'Latin';
$LN['spots_live']       = 'Live';

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
$LN['spots_nintendo3ds']   = 'Nintendo 3DS';
$LN['spots_windowsphone']  = 'Windows Phone';
$LN['spots_ios']           = 'iOS';
$LN['spots_android']       = 'Android';

$LN['spots_rip']           = 'Rip';
$LN['spots_retail']        = 'Retail';
$LN['spots_addon']         = 'Add-on';
$LN['spots_patch']         = 'Patch';
$LN['spots_crack']         = 'Crack';
$LN['spots_iso']           = 'ISO';
$LN['spots_action']        = 'Aktion';
$LN['spots_adventure']     = 'Abenteur';
$LN['spots_strategy']      = 'Strategie';
$LN['spots_roleplay']      = 'Rollenspiel';
$LN['spots_simulation']    = 'Simulation';
$LN['spots_race']          = 'Autorennen';
$LN['spots_flying']        = 'Fliegen';
$LN['spots_shooter']       = 'First Person Shooter';
$LN['spots_platform']      = 'Platform';
$LN['spots_sport']         = 'Sports';
$LN['spots_children']      = 'Kinder / jugend';
$LN['spots_puzzle']        = 'Puzzle';
$LN['spots_boardgame']     = 'Brettspiel';
$LN['spots_cards']         = 'Karten';
$LN['spots_education']     = 'Bildung';
$LN['spots_music']         = 'Musik';
$LN['spots_family']        = 'Familie';

$LN['spots_audioedit']     = 'Sound editing';
$LN['spots_videoedit']     = 'Video editing';
$LN['spots_graphics']      = 'grafische Gestaltung';
$LN['spots_cdtools']       = 'CD tools';
$LN['spots_mediaplayers']  = 'Media players';
$LN['spots_rippers']       = 'Rippers und encoders';
$LN['spots_plugins']       = 'Plugins';
$LN['spots_database']      = 'Datenbank';
$LN['spots_email']         = 'E-mail software';
$LN['spots_photo']         = 'Foto editors';
$LN['spots_screensavers']  = 'Bildschirmschoner';
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
$LN['spots_optimisation']  = 'Optimierungssoftware';
$LN['spots_security']      = 'Sicherheitsoftware';
$LN['spots_system']        = 'Systemsoftware';
$LN['spots_educational']   = 'Bildung';
$LN['spots_office']        = 'Office';
$LN['spots_internet']      = 'Internet';
$LN['spots_communication'] = 'Kommunikation';
$LN['spots_development']   = 'Entwicklung';
$LN['spots_spotnet']       = 'Spotnet';
//$LN['spots_']              = '';

$LN['spots_daily']          = 'Zeitung';
$LN['spots_magazine']       = 'Magazin';
$LN['spots_comic']          = 'Comic';
$LN['spots_study']          = 'Studie';
$LN['spots_business']       = 'Gesch&auml;ft';
$LN['spots_economy']        = 'Wirtschaft';
$LN['spots_computer']       = 'Computer';
$LN['spots_hobby']          = 'Hobby';
$LN['spots_cooking']        = 'Kochen';
$LN['spots_crafts']         = 'Handwerk';
$LN['spots_needlework']     = 'Handarbeit';
$LN['spots_health']         = 'Gesundheit';
$LN['spots_history']        = 'Vergangenheit';
$LN['spots_psychology']     = 'Psychologie';
$LN['spots_science']        = 'Wissenschaft';
$LN['spots_woman']          = 'Frau';
$LN['spots_religion']       = 'Religion';
$LN['spots_novel']          = 'Roman';
$LN['spots_biography']      = 'Biografie';
$LN['spots_detective']      = 'Krimi';
$LN['spots_animals']        = 'Tiere';
$LN['spots_humour']         = 'Humor';
$LN['spots_travel']         = 'Reisen';
$LN['spots_truestory']      = 'Wahre Geschichte';
$LN['spots_nonfiction']     = 'Non fiction';
$LN['spots_politics']       = 'Politik';
$LN['spots_poetry']         = 'Gedichte';
$LN['spots_fairytale']      = 'M&auml;rchen';
$LN['spots_technical']      = 'Technisch';
$LN['spots_art']            = 'Kunst';
$LN['spots_bi']             = 'Erotik: Biseksuell';
$LN['spots_lesbo']          = 'Erotik: Lesbe';
$LN['spots_homo']           = 'Erotik: Homoseksuell';
$LN['spots_hetero']         = 'Erotik: Hetero';
$LN['spots_amateur']        = 'Erotik: Amateur';
$LN['spots_groep']          = 'Erotik: Gruppe';
$LN['spots_pov']            = 'Erotik: POV';
$LN['spots_solo']           = 'Erotik: Solo';
$LN['spots_teen']           = 'Erotik: Teens';
$LN['spots_soft']           = 'Erotik: Soft';
$LN['spots_fetish']         = 'Erotik: Fetisch';
$LN['spots_mature']         = 'Erotik: Alt';
$LN['spots_fat']            = 'Erotik: Fett';
$LN['spots_sm']             = 'Erotik: Sadomasochismus';
$LN['spots_rough']          = 'Erotik: Rau';
$LN['spots_black']          = 'Erotik: Schwarz';
$LN['spots_hentai']         = 'Erotik: Hentai';
$LN['spots_outside']        = 'Erotik: Im Freien';

$LN['update_database']      = 'Database aktualiseren';

$LN['password_weak']        = 'Passwort St&auml;rke: schwach';
$LN['password_medium']        = 'Passwort St&auml;rkestrength: mittel';
$LN['password_strong']        = 'Passwort St&auml;rkestrength: stark';
$LN['password_correct']        = 'Passw&ouml;rter stimmen &uuml;berein';
$LN['password_incorrect']        = 'Passw&ouml;rter stimmen nicht &uuml;berein';

$LN['dashboard_max_nntp']      = 'Maximum number of NNTP connections';
$LN['dashboard_max_threads']   = 'Maximum number of total threads';
$LN['dashboard_max_db_intensive']	    = 'Maximum database intesive threads';


if (isset($smarty)) { // don't do the smarty thing if we read it from urdd
    foreach ($LN as $key => $word) {
        $LN2['LN_' . $key] = $word;
    }
    $smarty->assign($LN2);
    unset($LN2);
}
