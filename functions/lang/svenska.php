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
* $LastChangedDate$
* $Rev$
* $Author$
* $Id$
*
 */

/* Swedish language file for URD */
/* Translation by Thorwak */

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
$LN['cancel']           = 'Avbryt';

$LN['pause']            = 'Paus';
$LN['continue']         = 'Forts&auml;tt';
$LN['details']          = 'Detaljer';
$LN['error']            = 'Fel';
$LN['atonce']           = 'At once';
$LN['browse']           = 'Browse';
// Special:
$LN['urdname']          = 'URD';
$LN['decimalseparator'] = '.';
$LN['dateformat']       = 'Y-m-d';
$LN['dateformat2']      = 'Y-m-d';
$LN['dateformat3']      = 'd M';
$LN['timeformat']       = 'H:i:s';
$LN['timeformat2']      = 'H:i';

// This 'overwrites' the define values:
$LN['periods'][0]       = 'Ingen auto-uppdatering';
$LN['periods'][11]      = 'Varje timme';
$LN['periods'][12]      = 'Var 3:e timme';
$LN['periods'][1]       = 'Var 6:e timme';
$LN['periods'][13]      = 'Var 12:e timme';
$LN['periods'][2]       = 'Varje dag';
$LN['periods'][3]       = 'M&aring;ndagar';
$LN['periods'][4]       = 'Tisdagar';
$LN['periods'][5]       = 'Onsdagar';
$LN['periods'][6]       = 'Torsdagar';
$LN['periods'][7]       = 'Fredagar';
$LN['periods'][8]       = 'L&ouml;rdagar';
$LN['periods'][9]       = 'S&ouml;ndagar';
$LN['periods'][10]      = 'Var 4:e vecka';

$LN['autoconfig']       = 'Autokonfigurera';
$LN['autoconfig']       = 'Autokonfigurera (Ut&ouml;kad)';
$LN['extended']         = 'Ut&ouml;kad';
$LN['reload']           = 'Uppdatera';
$LN['expand']           = 'Expandera';
$LN['all']              = 'alla';
$LN['since']            = 'sedan';

$LN['active']           = 'Activ';
$LN['help']             = 'Hj&auml;lp';
$LN['disabled']         = 'inaktiverad';
$LN['unknown']          = 'Ok&auml;nd';
$LN['sets']             = 'set';

$LN['CAPTCHA1']         = 'Captcha';
$LN['CAPTCHA2']         = '3 svarta symboler';

$LN['expire']        = 'Radera utg&aring;ngna artiklar';
$LN['update']        = 'Uppdatera';
$LN['purge']         = 'T&ouml;m fullst&auml;ndigt';

$LN['autoconfig_msg']       = 'Autokonfiguration: Testar alla servrar i listan och ser om det finns en server p&aring; standard-portarna f&ouml;r Usenet (119 och 563), med eller utan ssl/tls. Om den hittar en v&auml;ljs den; och uppdaterings-server v&auml;ljs om en hittas som till&aring;ter indexering.';
$LN['autoconfig_ext_msg']   = 'Ut&ouml;kad autokonfiguration: Testar alla servrar i listan och ser om det finns en server p&aring; standard-portarna f&ouml;r Usenet (119 och 563) och m&aring;nga andra portar som kan t&auml;nkas anv&auml;ndas av tillhandah&aring;llare av Usenet (s&aring;som 23, 80, 8080, 443), med eller utan ssl/tls. Om den hittar en v&auml;ljs den; och uppdaterings-server v&auml;ljs om en hittas som till&aring;ter indexering.';

$LN['add_search']           = 'Add search';
$LN['delete_search']        = 'Delete search';
$LN['save_search_as']       = 'Save search as';
$LN['saved']                = 'Sparada';
$LN['deleted']              = 'Raderat';

$LN['month_names'][1]       = 'Januari';
$LN['month_names'][2]       = 'Februari';
$LN['month_names'][3]       = 'Mars';
$LN['month_names'][4]       = 'April';
$LN['month_names'][5]       = 'Maj';
$LN['month_names'][6]       = 'Juni';
$LN['month_names'][7]       = 'Juli';
$LN['month_names'][8]       = 'Augusti';
$LN['month_names'][9]       = 'September';
$LN['month_names'][10]      = 'Oktober';
$LN['month_names'][11]      = 'November';
$LN['month_names'][12]      = 'December';

$LN['short_month_names'][1]   = 'Jan';
$LN['short_month_names'][2]   = 'Feb';
$LN['short_month_names'][3]   = 'Mar';
$LN['short_month_names'][4]   = 'Apr';
$LN['short_month_names'][5]   = 'Maj';
$LN['short_month_names'][6]   = 'Jun';
$LN['short_month_names'][7]   = 'Jul';
$LN['short_month_names'][8]   = 'Aug';
$LN['short_month_names'][9]   = 'Sep';
$LN['short_month_names'][10]  = 'Okt';
$LN['short_month_names'][11]  = 'Nov';
$LN['short_month_names'][12]  = 'Dec';

$LN['short_day_names'][1]	= 'S&ouml;n';
$LN['short_day_names'][2]	= 'M&aring;n';
$LN['short_day_names'][3]	= 'Tis';
$LN['short_day_names'][4]	= 'Ons';
$LN['short_day_names'][5]	= 'Tor';
$LN['short_day_names'][6]	= 'Fre';
$LN['short_day_names'][7]	= 'L&ouml;r';

$LN['time']        = 'Tid';
$LN['select']       = 'V&auml;lj';
$LN['whitelisttag'] = 'V';
$LN['blacklisttag']     = 'S';
$LN['spamreporttag']    = 'S';

$LN['off']          = 'Av';
$LN['on']           = 'P&aring;';
$LN['all']          = 'Alla';
$LN['preview']      = 'F&ouml;rhandsgranska';
$LN['temporary']    = 'Tillf&auml;lliga filer';
$LN['other']        = 'Annat';
$LN['from']         = 'fr&aring;n';
$LN['never']        = 'aldrig';
$LN['total']        = 'Totalt';

$LN['id']                   = 'ID';
$LN['pid']                  = 'PID';
$LN['server']               = 'Servrar';
$LN['start_time']           = 'Start time';
$LN['queue_time']           = 'Queue time';
$LN['recurrence']           = 'Upprepning';
$LN['enabled']              = 'Aktiverad';
$LN['free_threads']         = 'Free threads';
$LN['total_free_threads']   = 'Total free threads';
$LN['free_db_intensive_threads']   = 'Free database intensive threads';
$LN['free_nntp_threads']    = 'Free NNTP threads';

$LN['next']     = 'N&auml;sta';
$LN['previous'] = 'F&ouml;rra';

// Time:
$LN['year']     = '&Aring;r';
$LN['month']    = 'M&aring;nad';
$LN['week']     = 'Vecka';
$LN['day']      = 'Dag';
$LN['hour']     = 'Timme';
$LN['minute']   = 'Minut';
$LN['second']   = 'Sekund';

$LN['years']      = '&Aring;r';
$LN['months']     = 'M&aring;nader';
$LN['weeks']      = 'Veckor';
$LN['days']       = 'Dagar';
$LN['hours']      = 'Timmar';
$LN['minutes']    = 'Minuter';
$LN['seconds']    = 'Sekunder';

$LN['year_short']   = '&aring;r';
$LN['month_short']  = 'M';
$LN['week_short']   = 'v';
$LN['day_short']    = 'd';
$LN['hour_short']   = 't';
$LN['minute_short'] = 'm';
$LN['second_short'] = 's';

// Menu:
$LN['menudownloads']    = 'Nedladdningar';
$LN['menuuploads']      = 'Uppladdning';
$LN['menutransfers']    = '&Ouml;verf&ouml;ring';
$LN['menubrowsesets']   = 'Bibliotek';
$LN['menugroupsearch']  = 'S&ouml;k&nbsp;grupp-set';
$LN['menusearch']       = 'S&ouml;k';
$LN['menursssearch']    = 'S&ouml;k&nbsp;rss-set';
$LN['menuspotssearch']  = 'S&ouml;k&nbsp;spots';
$LN['menunewsgroups']   = 'Usenet-grupper';
$LN['menuviewfiles']    = 'Visa&nbsp;filer';
$LN['menuviewfiles_downloads']  = 'Nedladdade&nbsp;filer';
$LN['menuviewfiles_previews']   = 'F&ouml;rhandsgransknings-filer';
$LN['menuviewfiles_nzbfiles']   = 'NZB-filer';
$LN['menuviewfiles_scripts']    = 'Skript';
$LN['menuviewfiles_posts']      = 'Postningar';
$LN['menupreferences']  = 'Inst&auml;llningar';
$LN['menuadmin']        = 'Admin';
$LN['menuabout']        = 'Om&nbsp;URD';
$LN['menumanual']       = 'Manual';
$LN['menuadminconfig']  = 'Konfiguration';
$LN['menuadmincontrol'] = 'Kontrollpanel'; // dashboard
$LN['menuadminusenet']  = 'Usenet-servrar';
$LN['adminupdateblacklist'] = 'Update spots blacklist';
$LN['adminupdatewhitelist'] = 'Update spots whitelist';
$LN['menuadminlog']     = 'Loggar';
$LN['menuadminjobs']    = 'Jobb';
$LN['menuadmintasks']   = 'Aktiviteter';
$LN['menuadminusers']   = 'Anv&auml;ndare';
$LN['menuadminbuttons'] = 'S&ouml;k';
$LN['menuhelp']         = 'Hj&auml;lp';
$LN['menufaq']          = 'FAQ';
$LN['menulicence']      = 'Licens';
$LN['menulogout']       = 'Logga&nbsp;ut&nbsp;';
$LN['menulogin']        = 'Logga&nbsp;in';
$LN['menudebug']        = 'Fels&ouml;k';
$LN['menustats']        = 'Statistik';
$LN['menuforum']        = 'Forum';
$LN['menuuserlists']            = 'User lists';

$LN['advanced_search']  = 'Avancerad s&ouml;k';

// Stati:
$LN['statusidling']        = 'Inaktiv';
$LN['statusrunningtasks']  = 'Aktiva jobb';

$LN['enableurddfirst']  = 'Starta URDD f&ouml;r kunna &auml;ndra dessa inst&auml;llningar';
// Version:
$LN['version']          = 'Version';
$LN['enableurdd']       = 'Klicka f&ouml;r att starta URDD';
$LN['disableurdd']      = 'Klicka f&ouml;r att stoppa URDD';
$LN['urddenabled']      = 'URDD &auml;r ig&aring;ng';
$LN['urddstarting']     = 'URDD startar';
$LN['urdddisabled']     = 'URDD &auml;r stoppad';
$LN['versionuptodate']  = 'URD &auml;r av senaste version.';
$LN['versionoutdated']  = 'URD &auml;r f&ouml;raldrad.';
$LN['newversionavailable']  = 'En st&ouml;rre uppdatering finns tillg&auml;nglig.';
$LN['bugfixedversion']  = 'Den nya versionen inneh&aring;ller buggfixar.';
$LN['newfeatureversion']= 'Den nya versionen inneh&aring;ller nya funktioner.';
$LN['otherversion']     = 'Den nya versionen inneh&aring;ller ospecifierade &auml;ndringar (??).';
$LN['securityfixavailable'] = 'Den nya versionen inneh&aring;ller viktiga s&auml;kerhets-uppdateringar.';
$LN['status']           = 'Status';
$LN['activity']         = 'Aktivitet';

// Tasks:
$LN['taskupdate']       = 'Uppdaterar';
$LN['taskpost']         = 'Postar';
$LN['taskpurge']        = 'Raderar';
$LN['taskexpire']       = 'Raderar utg&aring;ngna artiklar';
$LN['taskdownload']     = 'Laddar ned';
$LN['taskcontinue']     = 'Forts&auml;tt';
$LN['taskpause']        = 'Paus';
$LN['taskunknown']      = 'Ok&auml;nd';
$LN['taskoptimise']     = 'Optimerar';
$LN['taskgrouplist']    = 'H&auml;mtar grupplista';
$LN['taskunparunrar']   = 'Packar upp';
$LN['taskcheckversion'] = 'Kontrollerar version';
$LN['taskgetsetinfo']   = 'H&auml;mta set-info';
$LN['taskgetblacklist'] = 'H&auml;mtar svartlista';
$LN['taskgetwhitelist'] = 'H&auml;mtar vitlista';
$LN['tasksendsetinfo']  = 'Skicka set-info';
$LN['taskparsenzb']     = 'L&auml;ser NZB-fil';
$LN['taskmakenzb']      = 'Skapar NZB-fil';
$LN['taskcleandir']     = 'St&auml;dar kataloger';
$LN['taskcleandb']      = 'St&auml;dar databasen';
$LN['taskgensets']      = 'Genererar set f&ouml;r';
$LN['taskadddata']      = 'Sammanst&auml;ller<br>nedladdningsdata f&ouml;r';
$LN['taskmergesets']    = 'Sammanfogar set';
$LN['taskfindservers']  = 'Autokonfigurera servrar';
$LN['taskgetnfo']       = 'H&auml;mtar NFO-data';
$LN['taskgetspots']     = 'H&auml;mtar spots';
$LN['taskgetspot_comments']     = 'Getting spots comments';
$LN['taskgetspot_reports']     = 'Getting spots spam reports';
$LN['taskgetspot_images']     = 'Getting spots images';
$LN['taskexpirespots']  = 'Raderar utg&aring;ngna spots';
$LN['taskpurgespots']   = 'T&ouml;mmer spots';
$LN['taskpostmessage']  = 'Postar ett meddelande';
$LN['taskdeleteset']    = 'Raderar set';
$LN['taskset']          = 'Setting configuration';

$LN['eta']          = 'ETA';
$LN['inuse']        = 'anv&auml;nds';
$LN['free']         = '&auml;r ledigt';

// Generic:
$LN['isavailable']      = '&auml;r tillg&auml;nglig';
$LN['apply']            = 'Spara';
$LN['website']          = 'Websida';
$LN['or']               = 'eller';
$LN['submit']           = 'Skicka';
$LN['add']              = 'L&auml;gg till';
$LN['clear']            = 'Rensa';
$LN['reset']            = '&Aring;terst&auml;ll';
$LN['search']           = 'S&ouml;k';
$LN['number']           = 'Antal';
$LN['rename']           = 'Byt namn';
$LN['register']         = 'Registrera';
$LN['delete']           = 'Radera';
$LN['delete_all']       = 'Radera alla';

// Setinfo:
$LN['bin_unknown']      = 'Ok&auml;nd';
$LN['bin_movie']        = 'Film';
$LN['bin_album']        = 'Album';
$LN['bin_image']        = 'Bild';
$LN['bin_software']     = 'Mjukvara';
$LN['bin_tvseries']     = 'TV-serie';
$LN['bin_ebook']        = 'eBook';
$LN['bin_game']         = 'Spel';
$LN['bin_documentary']  = 'Dokument&auml;r';
$LN['bin_tvshow']       = 'TV-show';
$LN['bin_other']        = '&Ouml;vrigt';

// View files:
$LN['files']            = 'filer';
$LN['viewfilesheading'] = 'Visar';
$LN['filename']         = 'Filnamn';
$LN['group']            = 'Grupp';
$LN['rights']           = 'R&auml;ttigheter';
$LN['size']             = 'Storlek';
$LN['count']            = 'Antal';
$LN['type']             = 'Typ';
$LN['modified']         = 'Modifierad';
$LN['owner']            = '&Auml;gare';
$LN['perms']            = 'R&auml;ttigheter';
$LN['actions']          = '&Aring;tg&auml;rder';
$LN['uploaded']         = 'Uppladdad';
$LN['edit_file']        = 'Editera fil';
$LN['viewfiles_title']  = 'Visa filer';
$LN['viewfiles_download']   = 'Ladda ned arkiv';
$LN['viewfiles_uploadnzb']  = 'Ladda ned fr&aring;n NZB';
$LN['viewfiles_rename']     = 'byt namn';
$LN['viewfiles_edit']       = 'Redigera';
$LN['viewfiles_newfile']    = 'Ny fil';
$LN['viewfiles_savefile']   = 'Spara fil';
$LN['viewfiles_tarnotset']  = 'Kommandot tar &auml;r inte konfigurerat. Arkiverade nedladdningar &auml;r inaktiverat.';
$LN['viewfiles_compressfailed'] = 'Filkomprimering misslyckades';

$LN['viewfiles_type_audio'] = 'Audio';
$LN['viewfiles_type_excel'] = 'Excel';
$LN['viewfiles_type_exe']   = 'Exe';
$LN['viewfiles_type_flash'] = 'Flash';
$LN['viewfiles_type_html']  = 'HTML';
$LN['viewfiles_type_iso']   = 'ISO';
$LN['viewfiles_type_php']   = 'PHP';
$LN['viewfiles_type_source']    = 'Source';
$LN['viewfiles_type_picture']   = 'Bild';
$LN['viewfiles_type_ppt']   = 'PPT';
$LN['viewfiles_type_script']    = 'Skript';
$LN['viewfiles_type_text']  = 'Text';
$LN['viewfiles_type_video'] = 'Video';
$LN['viewfiles_type_word']  = 'Word';
$LN['viewfiles_type_zip']   = 'Arkiv';
$LN['viewfiles_type_stylesheet']= 'Stilmall';
$LN['viewfiles_type_icon']  = 'Ikon';
$LN['viewfiles_type_db']    = 'DB';
$LN['viewfiles_type_folder']    = 'Folder';
$LN['viewfiles_type_file']  = 'Fil';
$LN['viewfiles_type_pdf']   = 'PDF';
$LN['viewfiles_type_nzb']   = 'NZB';
$LN['viewfiles_type_par2']  = 'Par2';
$LN['viewfiles_type_sfv']   = 'SFV';
$LN['viewfiles_type_playlist']  = 'spel-lista';
$LN['viewfiles_type_torrent']   = 'Torrent';
$LN['viewfiles_type_ebook']     = 'Ebook';
$LN['viewfiles_type_urdd_sh']   = 'URD skript';
$LN['user_lists_title'] = 'Spotter lists';
$LN['user_blacklist']   = 'Spots blacklists';
$LN['user_whitelist']   = 'Spots whitelist';
$LN['spotter_id']       = 'Spotter ID';
$LN['source_external']  = 'External';
$LN['source_user']      = 'User added';
$LN['global']           = 'Global';
$LN['personal']         = 'Personal';
$LN['active']           = 'Active';
$LN['disabled']         = 'Disabled';
$LN['nonactive']        = 'Nonactive';


// About:
$LN['about_title']  = 'Om URD';

$LN['abouttext1']   = 'URD &auml;r en web-baserad applikation f&ouml;r att ladda ned bin&auml;ra filer fr&aring;n Usenet. Den &auml;r skriven helt i PHP, men anv&auml;nder samtidigt vissa externa program f&ouml;r att utf&ouml;ra en del av det tyngsta, mest CPU-intensiva arbetet.  Den lagrar all information den beh&ouml;ver i en generell databas (som MySQL eller PostGreSQL).  Artiklar som h&ouml;r ihop sl&aring;s samman till ett <I>set</I>. Nedladdning av filer kr&auml;ver endast ett f&aring;tal mus-klick, och n&auml;r en nedladdning &auml;r f&auml;rdig kan den automatiskt felkontrolleras samt packas upp.  Att ladda ned fr&aring;n Usenet &auml;r lika enkelt som att anv&auml;nda ett P2P-program!';

$LN['abouttext2']   = 'En stor f&ouml;rdel med URD &auml;r att inga externa websiter beh&ouml;vs, eftersom URD genererar sin egen nedladdnings-information.  Det &auml;r &auml;ven m&ouml;jligt att skapa och ladda ned en NZB-fil fr&aring;n angivna artiklar.';

$LN['abouttext3']   = 'URD &auml;r en backronym av Usenet Resource Downloader. Termen URD h&auml;rstammar fr&aring;n nordiska kulturer refererande till <a href="http://sv.wikipedia.org/wiki/Urdarbrunnen" title="Urdarbrunnen i Wikipedia">Urds brunn</a>, som &auml;r en helig brunn, &ouml;desbrunnen, och vattenk&auml;llan f&ouml;r v&auml;rldstr&auml;det <a href="http://sv.wikipedia.org/wiki/Yggdrasil" title="Yggdrasil i Wikipedia">Yggdrasil</a>.  Den gamla Engelska termen &auml;r Wyrd. Den n&auml;rmaste betydelsen av URD &auml;r &Ouml;det.';

$LN['licence_title']  = 'Licens';

// Newsgroup
$LN['ng_title']       = 'Usenet-grupper';
$LN['ng_posts']       = 'Artiklar';
$LN['ng_lastupdated'] = 'Senast Uppdaterad';
$LN['ng_expire_time'] = 'Max &Aring;lder';
$LN['ng_autoupdate']  = 'Automatisk uppdatering';
$LN['ng_searchtext']  = 'S&ouml;k i alla tillg&auml;ngliga Usenet-grupper';
$LN['ng_newsgroups']  = 'Usenet-grupper';
$LN['ng_subscribed']  = 'Prenumerationer';
$LN['ng_tooltip_name']      = 'Namnet p&aring; Usenet-gruppen';
$LN['ng_tooltip_lastupdated']   = 'Hur l&auml;nge sedan Usenet-gruppen senast uppdaterades';
$LN['ng_tooltip_action']    = 'Uppdatera/Generera set/Rensa utg&aring;nget/T&ouml;m';
$LN['ng_tooltip_expire']    = 'Antal dagar artiklar sparas i databasen innan de ses som utg&aring;ngna och kan raderas';
$LN['ng_tooltip_time']      = 'Tidpunkten n&auml;r den automatiska uppdateringen ska k&ouml;ras';
$LN['ng_tooltip_autoupdate']    = 'Hur ofta den automatiska uppdateringen f&ouml;r den h&auml;r Usenet-gruppen ska k&ouml;ras';
$LN['ng_tooltip_posts']     = 'Antal artiklar i den h&auml;r Usenet-gruppen';
$LN['ng_tooltip_active']    = 'Ibockad om du prenumerar p&aring; den h&auml;r Usenet-gruppen';
$LN['ng_gensets']           = 'Generera set';
$LN['ng_visible']           = 'Synlig';
$LN['ng_minsetsize']        = 'Min/Max set-storlek';
$LN['ng_admin_minsetsize']  = 'Spam lower limit';
$LN['ng_admin_maxsetsize']  = 'Set upper limit';
$LN['ng_tooltip_admin_maxsetsize']    = 'The maximum size a set can have to be added to the database - add k, M, G as suffix, e.g. 100k or 25G';
$LN['ng_tooltip_admin_minsetsize']    = 'The minimum size a set must have to be added to the database - add k, M, G as suffix, e.g. 100k or 25G (spam control)';
$LN['ng_tooltip_visible']   = 'Den h&auml;r gruppen &auml;r synlig';
$LN['ng_tooltip_minsetsize']    = 'Minsta respektive st&ouml;rsta antalet MB i ett set att visa f&ouml;r den h&auml;r Usenet-gruppen (0 = ingen begr&auml;nsning)';
$LN['ng_hide_empty']        = 'G&ouml;m tomma Usenet-grupper';
$LN['ng_adult']             = '18+';
$LN['ng_tooltip_adult']     = 'Only accessible when user has 18+ flag set';
$LN['failed']               = 'misslyckad';
$LN['success']              = 'startad';
$LN['success2']             = 'lyckad';

$LN['user_settings']        = 'Anv&auml;ndar-inst&auml;llningar';
$LN['global_settings']      = 'Globala inst&auml;llningar';

// preferences
$LN['change_password']      = 'Change password';
$LN['password_changed']     = 'Password changed';
$LN['delete_account']       = 'Radera konto';
$LN['delete_account_msg']   = 'Radera konto';
$LN['account_deleted']      = 'Konto raderat';
$LN['pref_title']           = 'Inst&auml;llningar';
$LN['pref_heading']         = 'Personliga inst&auml;llningar';
$LN['pref_saved']           = 'Inst&auml;llningar sparade';
$LN['pref_language']        = 'Spr&aring;k';
$LN['pref_template']        = 'Mall';
$LN['pref_language_msg']    = 'Spr&aring;ket som anv&auml;nds i URD';
$LN['pref_template_msg']    = 'Layout-mallen som anv&auml;nds i URD';
$LN['pref_index_page_msg']  = 'Standardsida att visa efter login';
$LN['pref_index_page']      = 'Standardsida';
$LN['pref_stylesheet']      = 'Stilmall';
$LN['pref_stylesheet_msg']  = 'Stilmallen som anv&auml;nds f&ouml;r att visa URD';
$LN['pref_login']           = 'Login';
$LN['pref_display']         = 'Visa';
$LN['pref_downloading']     = 'Nedladdning';
$LN['pref_spots']           = 'Spots';
$LN['pref_setcompleteness'] = 'Procentsats komplett set';

$LN['pref_default_group']       = 'Default group';
$LN['pref_default_group_msg']   = 'Default group to select in the browse page';
$LN['pref_default_feed']        = 'Default feed';
$LN['pref_default_feed_msg']    = 'Default feed to select in the rss sets page';
$LN['pref_default_spot']        = 'Default spot search';
$LN['pref_default_spot_msg']    = 'Default spot search to select in the spots page';
$LN['pref_user_scripts']        = 'K&ouml;r anv&auml;ndar-skript';
$LN['pref_user_scripts_msg']    = 'Anv&auml;ndar-skripten som k&ouml;rs efter en avslutad nedladdning (Obs: Skriptnamn m&aring;ste sluta p&aring; .urdd_sh)';
$LN['pref_global_scripts']      = 'K&ouml;r globala skript';
$LN['pref_global_scripts_msg']  = 'De globalt definierade skripten som k&ouml;rs efter en avslutad nedladdning (Obs: Skriptnamn m&aring;ste sluta p&aring; .urdd_sh)';

$LN['pref_poster_email']         = 'Postarens email-adress';
$LN['pref_poster_name']          = 'Postarens namn';
$LN['poster_name']               = 'Postarens namn';
$LN['pref_recovery_size']        = 'Andel par2-files, procent';
$LN['pref_rarfile_size']         = 'Storlek p&aring; RAR-filer';
$LN['pref_poster_email_msg']     = 'Email-adressen som ska anv&auml;ndas i de postade meddelandena';
$LN['pref_poster_name_msg']      = 'Namnet som ska anv&auml;ndas i de postade meddelandena';
$LN['pref_recovery_size_msg']    = 'Andelen reparations-filer (par2) som ska skapas, i procent (0 f&ouml;r att inte skapa reparations-filer)';
$LN['pref_rarfile_size_msg']     = 'Storleken p&aring; RAR-filerna som ska skapas i kB (0 f&ouml;r att inte RARa)';
$LN['pref_posting']              = 'Postning';

$LN['pref_skip_int']         = 'Tag inte bort intressanta set';
$LN['pref_skip_int_msg']     = 'G&ouml;m inte intressanta set n&auml;r n&auml;r man klickar p&aring; radera all set-data';
$LN['pref_level']       = 'Anv&auml;ndarens erfarenhets-niv&aring;';
$LN['pref_level_msg']   = 'Ju mer erfarenhet anv&auml;ndaren har desto mer val visas i konfiguration (om admin) och inst&auml;llningar';
$LN['level_basic']      = 'Standard';
$LN['level_advanced']   = 'Avancerad';
$LN['level_master']     = 'Storm&auml;stare';

$LN['pref_format_dl_dir']        = 'Format nedladdnings-katalog';
$LN['pref_format_dl_dir_msg']    = 'Format p&aring; text som l&auml;ggs till det grundl&auml;ggande namnet p&aring; katalogen d&auml;r nedladdningen sparas<br/>' .
    '%c: Kategori<br/>' .
    '%D: Datum<br/>' .
    '%d: Dagen i m&aring;naden<br>' .
    '%F: M&aring;nad (namn, l&aring;ngt)<br/>' .
    '%g: Grupp-namn<br/>' .
    '%G: Grupp-ID<br/>' .
    '%m: M&aring;nad (numeriskt)<br/>' .
    '%M: M&aring;nad (namn, kort)<br/>' .
    '%n: Set-namn' .
    '%s: Download name' .
    '%u: Username' .
    '%w: Veckodag<br/>' .
    '%W: Vecka p&aring; &aring;ret<br/>' .
    '%y: &Aring;r (2 siffror)<br/>' .
    '%Y: &Aring;r (4 siffror)<br/>' .
    '%x: X-rated<br/>' .
    '%z: Dag p&aring; &aring;ret<br/>' ;
$LN['pref_add_setname']         = 'L&auml;gg till setnamn till nedladdningskatalog';
$LN['pref_add_setname_msg']     = 'L&auml;gg till setets namn till nedladdningskatalogen (ut&ouml;ver den vanliga formatstr&ouml;ngen f&ouml;r nedladdningskataloger)';

$LN['pref_download_delay']         = 'F&ouml;rdr&ouml;j nedladdning';
$LN['pref_download_delay_msg']     = 'Antalet minuter en nedladdning &auml;r pausad innan den startar';

$LN['username']         = 'Anv&auml;ndarnamn';
$LN['fullname']         = 'Fullst&auml;ndigt namn';
$LN['password']         = 'L&ouml;senord';
$LN['newpw']            = 'Nytt l&ouml;senord';
$LN['oldpw']            = 'Gammalt l&ouml;senord';
$LN['email']            = 'Email-adress';
$LN['pref_maxsetname']       = 'Max l&auml;ngd p&aring; set-namn';
$LN['pref_setsperpage']      = 'Max antal rader per sida';
$LN['pref_minsetsize']       = 'Min. set-storlek i MB';
$LN['pref_maxsetsize']       = 'Max. set-storlek i MB';
$LN['setsize']          = 'Set-storlek i MB';
$LN['maxage']           = 'Max. &aring;lder i dagar';
$LN['minage']           = 'Min. &aring;lder i dagar';
$LN['age']              = '&Aring;lder i dagar';
$LN['rating']           = 'Betyg';
$LN['maxrating']        = 'Max. betyg (0-10)';
$LN['minrating']        = 'Min. betyg (0-10)';
$LN['complete']         = 'Fulltalighet, procent';
$LN['pref_maxcomplete']      = 'Max. fulltalighet, procent';
$LN['pref_mincomplete']      = 'Min. fulltalighet, procent';
$LN['pref_minngsize']        = 'Min. antal artiklar i Usenet-grupp';
$LN['config_global_hiddenfiles']        = 'Visa inte dolda filer';
$LN['config_global_hidden_files_list']  = 'Lista &ouml;ver dolda filer';
$LN['pref_hiddenfiles']             = 'Visa inte dolda filer';
$LN['pref_hidden_files_list']       = 'Lista &ouml;ver dolda filer';
$LN['pref_defaultsort']      = 'F&auml;ltet som anv&auml;nds f&ouml;r att sortera seten';
$LN['pref_buttons']          = 'S&ouml;k-knappar i bl&auml;ddrings-sektionen';
$LN['pref_unpar']            = 'K&ouml;r par2 automatiskt';
$LN['pref_download_par']     = 'Ladda alltid ned par2-filer';
$LN['pref_download_par_msg'] = 'Aktivera f&ouml;r att alltid ladda ned alla par2-filer, annars laddas de endast ned vid behov';
$LN['pref_unrar']            = 'Packa upp arkiv automatiskt';
$LN['pref_delete_files']     = 'Radera filer efter unrar';
$LN['pref_mail_user']        = 'Skicka meddelanden';
$LN['pref_show_subcats']     = 'Show subcats popup for spots';
$LN['pref_show_subcats_msg'] = 'Show a decscription of the subcatogries for a spot in a  popup';
$LN['pref_show_image']       = 'Show image for spots';
$LN['pref_show_image_msg']   = 'Show image for spots in extended spot information';
$LN['pref_use_auto_download']    = 'Ladda ned automatiskt';
$LN['pref_download_text_file']   = 'Ladda ned artiklar utan bilagor';
$LN['pref_use_auto_download_nzb']     = 'Automatisk nedladdning som NZB-fil';
$LN['pref_use_auto_download_nzb_msg'] = 'Ladda ned automatiskt baserat p&aouml; s&ouml;ktermer';
$LN['pref_download_text_file_msg']    = 'Ladda ned artikel-text &auml;ven om inga bilagor hittas till meddelandet';
$LN['pref_search_terms']         = 'S&ouml;k-termer';
$LN['pref_blocked_terms']        = 'Blockerade termer';
$LN['spam_reports']              = 'Spam reports';
$LN['pref_spot_spam_limit']      = 'Spam report limit';
$LN['pref_spot_spam_limit_msg']  = 'The number of spam reports with which spots are not displayed';
$LN['pref_setcompleteness_msg']     = 'Set som &auml;r kompletta till minst denna procentsats kommer visas p&aring; bl&auml;ddrings-sidan';
$LN['config_spots_whitelist']       = 'URL for spotter whitelist';
$LN['config_spots_max_categories']   = 'Max. number of categories per spot';
$LN['config_spots_max_categories_msg']   = 'Spots with more than this number of categories are rejected (0 to disable)';
$LN['config_spots_whitelist_msg']   = 'URL that contains a list of IDs of spotters known to be valid users';
$LN['config_spots_blacklist']       = 'URL for spotter blacklist';
$LN['config_spots_blacklist_msg']   = 'URL that contains a list of IDs of spotters known to be abusers';
$LN['config_download_spots_images']         = 'Download images for spots';
$LN['config_download_spots_images_msg']     = 'Download images for spots when updating the spots';
$LN['config_download_spots_comments']       = 'Download comments for spots';
$LN['config_download_spots_comments_msg']   = 'Download comments for spots when updating the spots';
$LN['config_download_spots_reports']        = 'Download spam reports for spots';
$LN['config_download_spots_reports_msg']    = 'Download spam reports for spots when updating the spots';
$LN['config_spot_expire_spam_count']    = 'Spam count upper limit after which spots are expired';
$LN['config_spot_expire_spam_count_msg']    = 'Spots are automatically expired after spam count is exceeded for the spot (0 to disable)';
$LN['config_allow_robots']                  = 'Allow robots';
$LN['config_allow_robots_msg']              = 'Allow robots to follow and index the URD webpages';
$LN['config_parse_nfo']                     = 'Tolka nfo-filer';
$LN['config_max_dl_name']                   = 'Maximal namnl&auml;ngd p&aring; nedladdningar';
$LN['config_max_dl_name_msg']   = 'Den maximala l&auml;ngden p&aring namnet som anv&auml;nds f&ouml;r nedladdningar';
$LN['config_parse_nfo_msg']  = 'Tolka nfo-filer n&auml;r de f&ouml;rhandsgranskas';
$LN['config_nice_value']     = 'Prioritet (nice-v&auml;rde)';
$LN['config_nice_value_msg'] = 'Prioritet (nice-v&auml;rde) p&aring; externa program som par2 och rar';
$LN['config_maxexpire']	     = 'Max artikel&aring;lder';
$LN['config_maxexpire_msg']  = 'Det maximala antalet dagar som kan s&auml;ttas som Max &Aring;lder f&ouml;r Usenet-grupper och RSS-fl&ouml;den';
$LN['config_max_login_count']	= 'Maximalt antal misslyckade inloggningsf&ouml;rs&ouml;k';
$LN['config_max_login_count_msg']	= 'Maximalt antal misslyckade inloggningsf&ouml;rs&ouml;k innan kontot sp&auml;rras';
$LN['config_maxheaders']            = 'Max artikel-huvuden per batch';
$LN['config_maxheaders_msg']        = 'Det maximala antalet artikel-huvuden som h&auml;mtas i en och samma batch';
$LN['pref_subs_lang_msg']           = 'Spr&aring;k f&ouml;r vilka undertexter kommer letas efter (tv&aring;-bokstavskoder, kommaseparerade, l&auml;mna blankt f&ouml;r att st&auml;nga av)';
$LN['pref_subs_lang']               = 'Undertexts-spr&aring;k';
$LN['config_replacement_str']       = 'Ers&auml;ttningstext nedladdningsnamn';
$LN['config_replacement_str_msg']   = 'Text att ers&auml;tta ol&auml;mpliga tecken med i nedladdningsnamn';
$LN['config_nntp_maxdlthreads']     = 'Max tr&aring;dar per nedladdning';
$LN['config_nntp_maxdlthreads_msg'] = 'Det maximala antalet samtidiga tr&aring;dar per nedladdning (0 betyder obegr&auml;nsat)';
$LN['config_group_filter']      = 'Usenet-gruppsfilter';
$LN['config_group_filter_msg']  = 'Filter f&ouml;r vilka Usenetgrupper som ska inkluderas';
$LN['config_extset_group']      = 'Grupp f&ouml;r extset-data';
$LN['config_extset_group_msg']  = 'Usenet-gruppen d&auml;r extset-data postas och l&auml;ses';
$LN['config_queue_size']        = 'K&ouml;storlek';
$LN['config_queue_size_msg']    = 'Maximalt antal aktiviteter som kan finnas i k&ouml;n';
$LN['config_spots_comments_group_msg']  = 'The newsgroup where comments for spots will be read';
$LN['config_spots_comments_group']      = 'Newsgroup for spots comments';
$LN['config_spots_reports_group']       = 'Newsgroup for spots spam reports';
$LN['config_spots_reports_group_msg']   = 'The newsgroup from which spots spam reports will be read';
$LN['config_spots_group']       = 'Newsgroup for spots';
$LN['config_spots_group_msg']   = 'The newsgroup where spots will be read';
$LN['config_ftd_group']         = 'Newsgroup for spots NZB files';
$LN['config_ftd_group_msg']     = 'The newsgroup where NZB files from spots can be found';
$LN['config_poster_blacklist']         = 'Posters to black list';
$LN['config_poster_blacklist_msg']     = 'Posters whose name or email match with the regular expression on these line are excluded from the sets database';
$LN['config_index_page_root_msg']  = 'Standardsida att visa efter login';
$LN['config_index_page_root']      = 'Standardsida';

$LN['config_modules']         = 'Moduler';
$LN['config_module_groups']   = 'Indexera Usenet-grupper';
$LN['config_module_makenzb']  = 'Skapa NZB-filer';
$LN['config_module_usenzb']   = 'Importera NZB-filer';
$LN['config_module_post']     = 'Postning till grupper';
$LN['config_module_spots']    = 'Reading spots';
$LN['config_module_rss']      = 'RSS-kanaler';
$LN['config_module_sync']     = 'Synkronisera extset-information';
$LN['config_module_download'] = 'Nedladdning fr&aring;n Usenet-grupper';
$LN['config_module_viewfiles'] = 'Utforskare';

$LN['config_module_groups_msg']   = 'Indexera Usenet-grupper';
$LN['config_module_makenzb_msg']  = 'St&ouml;d f&ouml;r att skapa NZB-filer';
$LN['config_module_usenzb_msg']   = 'St&ouml;d f&ouml;r att ladda ned med hj&auml;lp av NZB files';
$LN['config_module_post_msg']     = 'Postning till Usenet-grupper';
$LN['config_module_spots_msg']    = 'Reading spots from the newsgroup server';
$LN['config_module_rss_msg']      = 'St&ouml;d f&ouml;r RSS-kanaler';
$LN['config_module_sync_msg']     = 'Synkronisera ut&ouml;kad set-information';
$LN['config_module_download_msg'] = 'Nedladdning fr&aring;n Usenet-grupper';
$LN['config_module_viewfiles_msg'] = 'Inbyggd fil-bl&auml;ddrare';

$LN['config_urdd_uid'] = 'Anv&auml;ndar-ID f&ouml;r urdd';
$LN['config_urdd_gid'] = 'Grupp-ID f&ouml;r urdd';
$LN['config_urdd_uid_msg'] = 'Det anv&auml;ndar-ID som urdd &auml;ndrar till om den startas som root (l&auml;mna blankt f&ouml;r att inte &auml;ndra)';
$LN['config_urdd_gid_msg'] = 'Det grupp-ID som urdd &auml;ndrar till om den startas som root (l&auml;mna blankt f&ouml;r att inte &auml;ndra)';

$LN['username_msg']     = 'Anv&auml;ndaren som du &auml;r inloggad som';
$LN['newpw1_msg']       = 'Ditt nya l&ouml;senord';
$LN['newpw2_msg']       = 'Ditt nya l&ouml;senord igen';
$LN['oldpw_msg']        = 'Ditt nuvarande l&ouml;senord';
$LN['pref_maxsetname_msg']       = 'Den maximala storleken p&aring; ett set-namn att visa p&aring; en sida';
$LN['pref_setsperpage_msg']      = 'Antalet set att visa per sida';
$LN['pref_minsetsize_msg']       = 'Den minsta storleken ett set m&aring;ste ha f&ouml;r att visas i &ouml;versikten; mindre set ignoreras';
$LN['pref_maxsetsize_msg']       = 'Den st&ouml;rsta storleken ett set f&aring;r ha f&ouml;r att visas i &ouml;versikten; st&ouml;rre set ignoreras';
$LN['pref_minngsize_msg']        = 'Det minsta antalet artiklar en Usenet-grupp m&aring;ste ha f&ouml;r att visas i &ouml;versikten';
$LN['pref_hiddenfiles_msg']      = 'N&auml;r detta &auml;r aktiverat visas inte dolda filer i fil-visaren';
$LN['pref_hidden_files_list_msg']    = 'Lista &ouml;ver filer som kommer d&ouml;ljas i fil-visaren. Separera med radbyte, anv&auml;nd * och ? som joker-tecken';
$LN['config_global_hiddenfiles_msg']      = 'N&auml;r detta &auml;r aktiverat visas inte dolda filer i fil-visaren';
$LN['config_global_hidden_files_list_msg']    = 'Lista &ouml;ver filer som kommer d&ouml;ljas i fil-visaren. Separera med radbyte, anv&auml;nd * och ? som joker-tecken';

$LN['pref_use_auto_download_msg']    = 'Ladda ned automatiskt baserat p&aring; s&ouml;ktermer';

$LN['pref_defaultsort_msg']  = 'F&auml;ltet som anv&auml;nds f&ouml;r att sortera seten';
$LN['pref_buttons_msg']      = 'S&ouml;k-knappar som finns tillg&auml;ngliga i bl&auml;ddrings-sektionen';
$LN['pref_unpar_msg']        = 'N&auml;r aktiverat och setet inneh&aring;ller par2-filer kommer dessa automatiskt anv&auml;nas f&ouml;r att kontrollera och om n&ouml;dv&auml;ndigt reparera de nedladdade filerna';
$LN['pref_unrar_msg']        = 'N&auml;r aktiverat kommer alla rar-arkiv automatiskt packas upp';
$LN['pref_delete_files_msg'] = 'N&auml;r aktiverat och rar-kommandot lyckades kommer alla rar- och par2-filer raderas';
$LN['pref_mail_user_msg']    = 'Skicka ett meddelande n&auml;r en nedladdning har slutf&ouml;rts';
$LN['pref_search_terms_msg'] = 'Matcha automatiskt dessa s&ouml;ktermer mot alla grupper du prenumererar p&aring; (separera med radbyte) och markera dem';
$LN['pref_blocked_terms_msg']    = 'Matcha automatiskt dessa s&ouml;ktermer mot alla grupper du prenumererar p&aring; (separera med radbyte) och g&ouml;m dem';

$LN['pref_mail_user_sets']       = 'Maila intressanta set';
$LN['pref_mail_user_sets_msg']   = 'Skicka ett meddelande om ett intressant set har hittats';
$LN['descending']           = 'Fallande';
$LN['ascending']            = 'Stigande';

$LN['pref_basket_type']          = 'Download basket type';
$LN['pref_basket_type_msg']      = 'The type of download basket that is used by default';
$LN['basket_type_small']    = 'Compact';
$LN['basket_type_large']    = 'Extended';
$LN['pref_search_type']          = 'S&ouml;k-typ';
$LN['pref_search_type_msg']      = 'S&ouml;ktypen som anv&auml;nds i databasen f&ouml;r matchning av s&ouml;ktermer';
$LN['search_type_like']     = 'Simpel matchning p&aring; jokertecken (LIKE)';
$LN['search_type_regexp']   = 'Matchning p&aring; regulj&auml;ra uttryck (REGEXP)';

$LN['settings_imported']	= 'Inst&auml;llningar importerade';
$LN['settings_import']		= 'Importera inst&auml;llningar';
$LN['settings_export']		= 'Exportera inst&auml;llningar';
$LN['settings_import_file']	= 'Importera inst&auml;llningar fr&aring;n fil';
$LN['settings_notfound']	= 'Hittade inte filen, eller inga inst&auml;llningar funna';
$LN['settings_upload']		= 'Ladda upp inst&auml;llningar';
$LN['settings_filename']	= 'Filnamn';

$LN['import_servers']	= 'Importera servern';
$LN['export_servers']	= 'Exportera servern';
$LN['import_groups']	= 'Importera Usenet-grupper';
$LN['export_groups']	= 'Exportera Usenet-grupper';
$LN['import_feeds']		= 'Importera RSS-kanaler';
$LN['export_feeds']		= 'Exportera RSS-kanaler';
$LN['import_users']		= 'Importera anv&auml;ndare';
$LN['export_users']		= 'Exportera anv&auml;ndare';
$LN['import_buttons']	= 'Importera knappar';
$LN['export_buttons']	= 'Exportera knappar';
$LN['import_spots_blacklist']		= 'Import spots blacklist';
$LN['export_spots_blacklist']		= 'Export spots blacklist';
$LN['import_spots_whitelist']		= 'Import spots whitelist';
$LN['export_spots_whitelist']		= 'Export spots whitelist';

// pref errors
$LN['error_pwmatch']        = 'L&ouml;senorden st&auml;mmer inte &ouml;verens';
$LN['error_pwincorrect']    = 'Felaktigt L&ouml;senord';
$LN['error_pwusername']     = 'L&ouml;senordet &auml;r alltf&ouml;r likt anv&auml;ndarnamnet';
$LN['error_pwlength']       = 'F&ouml;r kort l&ouml;senord; minst '. MIN_PASSWORD_LENGTH . ' tecken kr&auml;vs';
$LN['error_pwsimple']       = 'L&ouml;senordet &auml;r alltf&ouml;r simpelt, anv&auml;nd en mix av gemener och versaler, siffror och andra tecken';
$LN['error_captcha']        = 'CAPTCHA felaktig';

$LN['error_onlyforgrops'] 	= 'Only works for groups';
$LN['error_onlyoneset'] 	= 'Requires more than one set to be in the basket';

$LN['error_feedexists']     = 'A RSS feed with that name already exists';
$LN['error_encryptedrar']       = 'Encrypted rar file';
$LN['error_usercancel']         = 'Cancelled by user';
$LN['error_downloadnotfound']   = 'Nedladdningen hittades inte';
$LN['error_linknotfound'] 	= 'Link not found';
$LN['error_nzbfailed'] 	    = 'Importing NZB file failed';
$LN['error_toomanybuttons']     = 'F&ouml;r m&aring;nga s&ouml;k-knappar';
$LN['error_invalidbutton']      = 'Felaktig s&ouml;k-knapp';
$LN['error_invalidemail']       = 'Felaktig email-adress';
$LN['error_invalidpassword']    = 'Felaktigt l&ouml;senord';
$LN['error_userexists']         = 'Anv&auml;ndaren existerar redan';
$LN['error_acctexpired']        = 'Kontot har g&aring;tt ut';
$LN['error_notleftblank']       = 'F&aring;r inte l&auml;mnas blankt';
$LN['error_invalidvalue']       = 'Felaktigt v&auml;rde';
$LN['error_urlstart']           = 'URLen m&aring;ste b&ouml;rja med http:// och sluta med /';
$LN['error_error']              = 'Fel';
$LN['error_invaliddir']         = 'Felaktig katalog';
$LN['error_notmakedir']         = 'Kunde inte skapa katalog';
$LN['error_notmaketmpdir']      = 'Kunde inte skapa tmp-katalog';
$LN['error_notmakepreviewdir']  = 'Kunde inte skapa f&ouml;rhandsgransknings-katalog';
$LN['error_dirnotwritable']     = 'Katalogen &auml;r inte skrivbar';
$LN['error_notestfile']         = 'Kunde inte skapa test-filer';
$LN['error_mustbemore']         = 'm&aring;ste vara mer &auml;n';
$LN['error_mustbeless']         = 'm&aring;ste vara mindre &auml;n eller lika med';
$LN['error_filenotexec']        = 'Filen kan inte hittas, eller &auml;r inte exekverbar f&ouml;r webservern';
$LN['error_noremovedir']        = 'Kan inte ta bort katalog';
$LN['error_noremovefile']       = 'Kan inte ta bort fil';
$LN['error_noremovefile2']      = 'Kan inte ta bort fil; katalog inte skrivbar';
$LN['error_nodeleteroot']       = 'Kan inte radera root-anv&auml;ndaren';
$LN['error_nosetids']           = 'Inga setID&#39;s givna!';
$LN['error_invalidstatus']      = 'Invalid status value supplied';
$LN['error_invalidstatus']      = 'Felaktigt status-v&auml;rde l&auml;mnat';
$LN['error_invaliduserid']      = 'Felaktigt anv&auml;ndar-ID';
$LN['error_groupnotfound']      = 'gruppen hittades inte';
$LN['error_invalidgroupid']     = 'Felaktigt grupp-ID angivet';
$LN['error_couldnotreadargs']   = 'Kunde inte l&auml;sa  cmd args (register_argc_argv=Off?)';
$LN['error_resetnotallowed']    = 'Inte till&aring;tet att nollst&auml;lla konfigurationen';
$LN['error_prefnotfound']       = 'Inst&auml;llning hittades inte';
$LN['error_invalidfilename']    = 'Felaktigt filnamn';
$LN['error_fileexists']         = 'Filen existerar redan';
$LN['error_cannotrename']       = 'Kan inte byta namn p&aring; fil';
$LN['error_needfilenames']      = 'Filnamn beh&ouml;vs';
$LN['error_usenetserverexists'] = 'En server med det namnet finns redan';
$LN['error_missingconnection']  = 'Felaktig anslutnings-typt angiven';
$LN['error_missingthreads']     = 'Tr&aring;dar m&aring;ste anges';
$LN['error_missinghostname']    = 'V&auml;rdnamn m&aring;ste anges';
$LN['error_missingname']        = 'Namn m&aring;ste anges';
$LN['error_needatleastoneport'] = '&Aringtminstone ett portnummer m&aring;ste anges';
$LN['error_needsecureport']     = 'S&auml;ker port m&aring;ste anges f&ouml;r krypterad anslutning';
$LN['error_nosuchserver']       = 'Servern finns inte';
$LN['error_invalidaction']      = 'Ok&auml;nd &aring;tg&auml;rd';
$LN['error_nameexists']         = 'En usenet-server med det namnet finns redan';
$LN['error_diskfull']           = 'Otillr&auml;ckligt diskutrymme f&ouml;rv&auml;ntas f&ouml;r att slutf&ouml;ra nedladdning';
$LN['error_invalidsetid']       = 'Ogiltigt set-ID angivet';
$LN['error_couldnotsendmail']   = 'Could not send message';
$LN['error_filetoolarge']       = 'Filen &auml;r f&ouml;r stor f&ouml;r att laddas ned';
$LN['error_preview_size_exceeded']      = 'Filen &auml;r f&ouml;r stor f&ouml;r att f&ouml;rhandsgranskas';
$LN['error_post_not_found']     = 'Postningen hittades inte';
$LN['error_pwresetnomail']      = 'L&ouml;senord nollst&auml;llt, men epost kunde inte skickas';
$LN['error_userupnomail']       = 'Anv&auml;ndare uppdaterad, men epost kunde inte skickas';
$LN['error_groupnotfound']  = 'Gruppen existerar inte';
$LN['error_subjectnofound'] = '&Auml;mne saknas';
$LN['error_posternotfound'] = 'Postarens epostaddress saknas';
$LN['error_invalidrecsize'] = 'Felaktig storlek p&aring; reparations-filer';
$LN['error_invalidrarsize'] = 'Felaktig storlek p&aring; rar-filer';
$LN['error_namenotfound']   = 'Postarens namn saknas';
$LN['error_searchnamenotfound']     = 'Name not found';
$LN['error_nowrite']                = 'Kunde inte skriva till fil';

$LN['error_noserversfound']         = 'No servers found';
$LN['error_nouploadsfound']         = 'No uploads found';
$LN['error_nodownloadsfound']       = 'No downloads found';
$LN['error_nogroupsfound']          = 'No groups found';
$LN['error_nosearchoptionsfound']   = 'No search options found';
$LN['error_nofeedsfound']           = 'No feeds found';
$LN['error_notasksfound']           = 'No tasks found';
$LN['error_nojobsfound']            = 'No jobs found';
$LN['error_nologsfound']            = 'No logs found';

$LN['error_spotnotfound']       = 'Spot not found';
$LN['error_setnotfound']        = 'Set not found';
$LN['error_binariesnotfound']   = 'Could not find binaries';
$LN['error_nameexists']         = 'Search name already exists';
$LN['error_missingparameter']   = 'Missing parameter';
$LN['error_nouploaddata']       = 'No content to upload found in';
$LN['error_namenotfound']       = 'Name not found';
$LN['error_invalidimage']       = 'Not a valid image';

$LN['error_schedulesnotset']    = 'Schedules could not be set';
$LN['error_unknowntype']        = 'Unknown type';
$LN['error_emptybasket']        = 'Empty basket';

// Admin pages:
$LN['adminshutdown']    = 'St&auml;ng ned URD-daemonen';
$LN['adminrestart']		= 'Starta om URD-Daemonen';
$LN['adminpause']       = 'Pausa alla aktiviteter';
$LN['admincontinue']    = '&Aring;teruppta alla aktiviteter';
$LN['adminclear']       = 'Rensa alla nedladdningar';
$LN['admincleandb']     = 'Rensa ALL flyktig information i databasen';
$LN['adminremoveready'] = 'Rensa endast slutf&ouml;rd nedladdnings-information';
$LN['adminpoweron']     = 'Starta URD-Daemonen';
$LN['adminupdatenglist']    = 'Uppdatera listan &ouml;ver Usenet-grupper';
$LN['adminupdateallngs']    = 'Uppdatera alla Usenet-grupper';
$LN['admingensetsallngs']   = 'Generera set f&ouml;r alla Usenet-grupper';
$LN['adminexpireallngs']    = 'Rensa utg&aring;ngna artiklar i alla Usenet-grupper';
$LN['adminpurgeallngs']     = 'T&ouml;m alla Usenet-grupper';
$LN['adminexpireallrss']    = 'Rensa utg&aring;ngna objekt i alla RSS-kanaler';
$LN['adminpurgeallrss']     = 'T&ouml;m alla RSS-kanaler';
$LN['adminupdateallrss']    = 'Uppdatera alla RSS-kanaler';
$LN['adminoptimisedb']      = 'Optimera databasen';
$LN['admincheckversion']    = 'Kontrollera  version av URD';
$LN['admingetsetinfo']      = 'H&auml;mta set-information';
$LN['adminsendsetinfo']     = 'Skicka set-information';
$LN['admincleandir']        = 'Rensa katalogen';
$LN['adminfindservers']     = 'Autokonfigurera Usenet-servrar';
$LN['adminfindservers_ext'] = 'Autokonfigurera Usenet-servrar (Ut&ouml;kad)';
$LN['adminexport_all']      = 'Exportera alla inst&auml;llningar';
$LN['adminimport_all']      = 'Importera alla inst&auml;llningar';
$LN['adminupdate_spots']    = 'Update spots';
$LN['adminupdate_spotscomments']    = 'Update spots comments';
$LN['adminupdate_spotsimages']      = 'Update spots images';
$LN['adminexpire_spots']    = 'Expire spots';
$LN['adminpurge_spots']     = 'Purge spots';

// register
$LN['reg_disabled']     = 'Registrering &auml;r inaktiverat';
$LN['reg_title']        = 'Konto-registrering';
$LN['reg_codesent']     = 'Din aktiverings-kod har skickats';
$LN['reg_status']       = 'Registrerings-status';
$LN['reg_activated']    = 'Ditt konto &auml;r aktiverat. Forts&auml;tt til';
$LN['reg_activated_link']  = 'log in';
$LN['reg_pending']      = 'Ditt konto &auml;r under behandling. V&auml;nligen v&auml;nta tills administrat&ouml;ren godk&auml;nt dig.';
$LN['reg_form']         = 'Fyll i formul&auml;ret f&ouml;r att ans&ouml;ka om ett konto';
$LN['reg_again']        = 'igen';

//admin controls
$LN['control_title']    = 'Daemon-kontroll';
$LN['control_options']  = 'Alternativ';
$LN['control_jobs']     = 'Jobb';
$LN['control_threads']  = 'Tr&aring;dar';
$LN['control_queue']    = 'K&ouml;';
$LN['control_servers']  = 'Servrar';
$LN['control_uptime']   = 'Upptid';
$LN['control_load']     = 'System-last';
$LN['control_diskspace']    = 'Disk space';

$LN['control_cancelall']    = 'Cancel all tasks';
/// posting
$LN['post_subject']             = '&Auml;mne';
$LN['post_delete_files']        = 'Radera filer';
$LN['post_delete_filesext']     = 'Radera tillf&auml;lliga filer som skapats (t ex rar- och par2-files)';
$LN['post_postername']          = 'Postarens namn';
$LN['post_posteremail']         = 'Postarens email-adress';
$LN['post_recovery']            = 'Procentsats f&ouml;r reparation';
$LN['post_rarfiles']            = 'Storlek p&auml; rar-fil';
$LN['post_newsgroup']           = 'Usenet-grupp';
$LN['post_post']                = 'Ladda upp';
$LN['post_directory']           = 'Katalog';
$LN['post_directoryext']        = 'Katalogen som ska laddas upp';
$LN['post_subjectext']          = '&Auml;mnes-raden i meddelandena';
$LN['post_posternameext']       = 'Namnet p&aring; postaren i meddelandena (from)';
$LN['post_posteremailext']      = 'Postarens email-addres i meddelandena (from)';
$LN['post_recoveryext']         = 'Hur mycket par2-filer som ska genereras, i procent';
$LN['post_rarfilesext']         = 'Storleken p&aring; de komprimerade rar-filerna i kilobyte';
$LN['post_newsgroupext']        = 'Usenet-gruppen som meddelandena ska postas till';

//admin jobs

$LN['jobs_title']       = 'Schemalagda jobb';
$LN['jobs_command']     = 'Kommando';
$LN['jobs_time']        = 'Tidpunkt';
$LN['jobs_period']      = 'Frekvens';
$LN['jobs_user']        = 'Anv&auml;ndare';

// admin tasks
$LN['tasks_title']          = 'Aktiviteter';
$LN['tasks_description']    = 'Beskrivning';
$LN['tasks_progress']       = 'Utf&ouml;rt';
$LN['tasks_added']          = 'Skapad';
$LN['tasks_lastupdated']    = 'Uppdaterad';
$LN['tasks_comment']        = 'Kommentar';

// admin config
$LN['config_title']         = 'Konfiguration';
$LN['config_setinfo']       = 'Set-uppdatering';
$LN['config_urdd_head']     = 'URD-Daemon';
$LN['config_nntp_maxthreads']       = 'Max antal NNTP-anslutningar';
$LN['config_urdd_maxthreads']       = 'Max antal tr&aring;dar totalt';
$LN['config_spots_expire_time']     = 'Expire time for spots (in days)';
$LN['config_spots_expire_time_msg'] = 'Expire time for spots (in days); note this overwrites the values set for the respective newsgroup';
$LN['config_default_expire_time']   = 'F&ouml;rvald giltighetstid (i dagar)';
$LN['config_expire_incomplete']     = 'Giltighetstid f&ouml;r inkompletta set (i dagar, 0 = anv&auml;nd inte)';
$LN['config_expire_percentage']     = 'Gr&auml;nsen f&ouml;r inkompletta set att radera (procent)';
$LN['config_auto_expire']           = 'Rensa utg&aring;ngna artiklar efter uppdatering';
$LN['config_auto_getnfo']	        = 'Auto-download of nfo files';
$LN['config_auto_getnfo_msg']       = 'Automatically download and parse nfo files after updating a newsgroup';
$LN['config_period_getspots']	    = 'Download spots';
$LN['config_period_getspots_msg']	= 'Download spots';
$LN['config_period_getspots_whitelist']	    = 'Download spots whitelist';
$LN['config_period_getspots_whitelist_msg']	= 'Schedule when the spots whitelist will be downloaded';
$LN['config_period_getspots_blacklist']	    = 'Download spots blacklist';
$LN['config_period_getspots_blacklist_msg']	= 'Schedule when the spots blacklist will be downloaded';
$LN['pref_cancel_crypted_rars'] = 'Avbryt krypterade nedladdningar';
$LN['config_dlpath']            = 'Lagra filer h&auml;r';
$LN['config_urdd_host']         = 'URDD v&auml;rdnamn';
$LN['config_urdd_port']         = 'URDD port';
$LN['config_urdd_restart']      = 'Starta om gamla aktiviteter';
$LN['config_urdd_daemonise']     = 'Start URDD as a background process';
$LN['config_urdd_daemonise_msg'] = 'Start URDD as a background process (daemon)';
$LN['config_admin_email']    = 'Administrat&ouml;rens email-adress';
$LN['config_baseurl']       = 'Bas-url';
$LN['config_shaping']       = 'Anv&auml;nd bandbredds-begr&auml;nsning';
$LN['config_maxdl']         = 'Max bandbredd (kB/s) per anslutning vid nedladdning';
$LN['config_maxul']         = 'Max bandbredd (kB/s) per anslutning vid uppladdning';
$LN['config_maxfilesize']   = 'Max storlek p&aring; filer, att visa i &#34;Visa filer&#34;';
$LN['config_maxpreviewsize']= 'Max storlek p&aring; filer, att f&ouml;rhandsgranska';
$LN['config_register']      = 'Till&aring;t registrering';
$LN['config_auto_reg']      = 'Godk&auml;nn konton automatiskt';
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
$LN['config_subdownloader_path_msg']   = 'Fullst&auml;ndig s&ouml;kv&auml;g till programmet subdownloader (frivilligt)';
$LN['config_file_path']          = 'file';
$LN['config_yydecode_path']      = 'yydecode';
$LN['config_yyencode_path']      = 'yyencode';
$LN['config_cksfv_path']         = 'cksfv';
$LN['config_trickle_path']       = 'trickle';
$LN['config_period_update']      = 'Kontrollera om det finns en nyare version av URD';
$LN['config_period_opt']         = 'Optimera databas';
$LN['config_period_ng']          = 'Uppdatera listan &ouml;ver Usenet-grupper';
$LN['config_period_cd']          = 'Rensa tempor&auml;ra filer och f&ouml;rhandsgransknings-katalogen';
$LN['config_period_cu']          = 'Period of inactive users';
$LN['config_period_cu_msg']      = 'Period of inactivity of non-admin users after which they will be removed in days';
$LN['config_users_clean_age']    = 'Clean inactive users';
$LN['config_users_clean_age_msg']  = 'Clean inactive, non-admin users after a period of inactivity (in days)';
$LN['config_clean_dir_age']     = '&Aring;lder p&aring; filer att radera';
$LN['config_clean_dir_age_msg'] = '&Aring;ldern en fil m&aring;ste ha uppn&aring;tt innan den kan raderas av kommandot &#34;' . $LN['config_period_cd'] . '&#34 (i dagar)';
$LN['config_clean_db_age']      = '&Aring;lder p&aring; flyktig databas-info att radera';
$LN['config_period_cdb']        = 'Rensa flyktig information fr&aring;n databasen';
$LN['config_clean_db_age_msg']  = '&Aring;ldern information i databasen m&aring;ste ha uppn&aring;tt innan den kan raderas av kommandot &#34;' . $LN['config_period_cdb'] . '&#34 (i dagar, 0 = anv&auml;nd inte)';
$LN['config_scheduler']     = 'URDD Schemal&auml;ggare';
$LN['config_networking']    = 'N&auml;tverk';
$LN['config_extprogs']      = 'Program';
$LN['config_maintenance']   = 'Underh&aring;ll';
$LN['config_globalsettings']    = 'Globala inst&auml;llningar';
$LN['config_notifysettings']    = 'Notify settings';
$LN['config_webdownload']       = 'Till&aring;t nedladdningar i web-interfacet';
$LN['config_webeditfile']	    = 'Till&aring;t editering av filer i web-interface';
$LN['config_webeditfile_msg']	= 'Anv&auml;ndare kan editera filer i visa filer-sidan';
$LN['config_socket_timeout']    = 'Timeout anslutningar';
$LN['config_urdd_connection_timeout']    = 'URDD connection timeout';
$LN['config_urdd_connection_timeout_msg']= 'The number of seconds after which a connection to URDD will timeout and is closed; defaults to 30';
$LN['config_auto_download']             = 'Till&aring;t automatisk nedladdning';
$LN['config_check_nntp_connections']    = 'Kontrollera Usenet-anslutningar vid uppstart';
$LN['config_check_nntp_connections_msg']= 'Testa det konfigurerade antalet m&ouml;jliga samtidiga anslutningar till en NNTP-server automatiskt vid uppstart';
$LN['config_nntp_all_servers']              = 'Allow downloads to run on all servers concurrently';
$LN['config_nntp_all_servers_msg']          = 'Allow downloads to run with the maximum number of NNTP threads on all enabled servers, instead of sticking to one server per download';
$LN['config_db_intensive_maxthreads']      = 'Max antal databas-intensiva tr&aring;dar';
$LN['config_db_intensive_maxthreads_msg']  = 'Det maximala antalet samtidiga tr&aring;dar som orsakar tung belastning p&aring; databasen';

$LN['config_auto_login']      = 'Logga in automatiskt som';
$LN['config_auto_login_msg']  = 'Logga automatiskt in som den angivna anv&auml;ndaren. L&auml;mna blankt f&ouml;r att inte anv&auml;nda detta.';

$LN['config_allow_global_scripts_msg']      = 'Till&aring;t globala skript';
$LN['config_allow_global_scripts']          = 'Till&aring;t skript angivna av administrat&ouml;rer att k&ouml;ras efter att nedladdningar slutf&ouml;rts';
$LN['config_allow_user_scripts_msg']        = 'Till&aring;t anv&auml;ndar-definierade skript';
$LN['config_allow_user_scripts']            = 'Till&aring;t skript angivna av anv&auml;ndare att k&ouml;ras efter att nedladdningar slutf&ouml;rts';

$LN['config_compress_nzb']      = 'Komprimera NZB-filer';
$LN['config_compress_nzb_msg']  = 'Komprimera NZB-filer efter att de laddats ned';
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
$LN['config_subdownloader_pars_msg'] 	= 'parametrar till subdownloader';

$LN['config_webdownload_msg']       = 'Anv&auml;ndare kan ladda ned filer som tar-arkiv fr&aring;n &#34;' . $LN['viewfiles_title'] . '&#34-sidan';
$LN['config_maxfilesize_msg']       = 'Den maximala storleken p&aring; filer att visa i &#34;' . $LN['viewfiles_title'] . '&#34, 0 f&ouml;r obegr&auml;nsat';
$LN['config_maxpreviewsize_msg']    = 'Den maximala storleken p&aring; filer, i kB, att f&ouml;rhandsgranska (0 f&ouml;r obegr&auml;nsat)';
$LN['config_default_stylesheet']     = 'F&ouml;rvald stilmall';
$LN['config_default_stylesheet_msg'] = 'Stilmallen som anv&auml;nds n&auml;r ingen annan valts, eller om den valda inte kan hittas';

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
$LN['config_mail_password_reset_msg']       = 'Mail sent to the user the new password';

$LN['config_default_template']   = 'F&ouml;rvald layout-mall';
$LN['config_default_template_msg']   = 'Layout-mallen som anv&auml;nds n&auml;r ingen valts, eller om den valda inte kan hittas';
$LN['config_default_language_msg']   = 'Spr&aring;ket som anv&auml;nds n&auml;r inget valts, eller om det valda inte kan hittas';
$LN['config_scheduler_msg']     = 'Anv&auml;nd schemal&auml;ggning av automatiska jobb i URDD';
$LN['config_default_language']   = 'F&ouml;rvalt spr&aring;k';
$LN['config_log_level']     = 'Log-niv&aring;';
$LN['config_permissions_msg']   = 'F&ouml;rvalda r&auml;ttigheter p&aring; nedladdade filer';
$LN['config_permissions']   = 'Filr&auml;ttigheter nedladdade filer';
$LN['config_group']         = 'Grupp';
$LN['config_group_msg']     = 'Gruppen som &auml;ger alla nedladdade filer';
$LN['config_maxbuttons']    = 'Maximalt antal s&ouml;k-knappar';
$LN['config_maxbuttons_msg']    = 'Det maximala antalet s&ouml;k-knappar som visas p&aring; &#34;' . $LN['menubrowsesets'] . '&#34-sidan';
$LN['config_nntp_maxthreads_msg']  = 'Antalet parallella anslutningar som URD-daemonen f&aring;r anv&auml;nda';
$LN['config_urdd_maxnthreads_msg']   = 'Antalet parallella aktiviteter som URD-damonen f&aring;r utf&ouml;ra';
$LN['config_default_expire_time_msg']    = 'Det f&ouml;rvalda antalet dagar innan ett set behandlas som utg&aring;nget';
$LN['config_expire_incomplete_msg']    = 'Det f&ouml;rvalda antalet dagar innan ett icke komplett set behandlas som utg&aring;nget';
$LN['config_expire_percentage_msg']    = 'Set med h&ouml;gst denna andel existerande delar kan anses inkompletta och raderas som utg&aring;ngna i f&ouml;rtid';
$LN['config_auto_expire_msg']       = 'Utg&aring;ngna artiklar kommer raderas automtatiskt efter en slutf&ouml;rd uppdatering';
$LN['pref_cancel_crypted_rars_msg']  = 'Analysera filer under tiden de laddas ned, och avbryt nedladdningen om en krypterad RAR-fil p&aring;tr&auml;ffas (om l&ouml;senordet inte &auml;r k&auml;nt)';
$LN['config_dlpath_msg']    = 'Fullst&auml;ndig s&ouml;kv&auml;g dit URD kommer ladda ned alla filer';
$LN['config_clickjack']     = 'Enable clickjack prevention';
$LN['config_clickjack_msg'] = 'Enable clickjack prevention to ensure that URD is only accessed in a full page and not in a frame';
$LN['config_need_challenge']     = 'Enable XSS prevention';
$LN['config_need_challenge_msg'] = 'Enable cross-site scripting prevention to ensure that URD functions cannot be exploited from other sites';
$LN['config_use_encrypted_passwords']     = 'Store usenet account passwords encrypted';
$LN['config_use_encrypted_passwords_msg'] = 'Passwords are stored in an encrypted format; using a keystore separate file to store the key';
$LN['config_keystore_path']         = 'Location of the key store';
$LN['config_keystore_path_msg']     = 'The directory where the key store will be placed';
$LN['config_pidpath']       = 'Location of the PID file';
$LN['config_pidpath_msg']   = 'The location of the PID file used to prevent starting multiple instances of URDD (leave blank for none)';
$LN['config_urdd_host_msg']  = 'V&auml;rdnamnet eller IP-adressen till URD-daemonen; f&ouml;rvalt &auml;r localhost (OBS: IPv6 adressers m&aring;ste omges av [] t ex [::1])';
$LN['config_urdd_port_msg']  = 'Portnumret till URD-daemonen; f&ouml;rvalt &auml;r 11666';
$LN['config_urdd_restart_msg']   = 'Aktiviteter som k&ouml;rdes n&auml;r URD-daemonen kraschade kommer startas om automatiskt om denna ruta &auml;r ibockad';
$LN['config_admin_email_msg']    = 'Email-adressen till administrat&ouml;ren';
$LN['config_baseurl_msg']   = 'Bas-URLen till din URD-websida';
$LN['config_shaping_msg']   = 'Begr&auml;nsa bandbredden som URD-daemonen f&aring;r anv&auml;nda';
$LN['config_maxdl_msg']     = 'Den maximala bandbredden URD-daemonen f&aring;r anv&auml;nda f&ouml;r att ladda ned fr&aring;n Usenet-servern';
$LN['config_maxul_msg']     = 'Den maximala bandbredden URD-daemonen f&aring;r anv&auml;nda f&ouml;r att ladda upp till Usenet-servern';
$LN['config_register_msg']  = 'Om denna ruta &auml;r markerad kan nya anv&auml;ndare registrera sig fr&aring;n inloggnings-sidan';
$LN['config_auto_reg_msg']    = 'Om denna ruta inte &auml;r markerad m&aring;ste administrat&ouml;ren manuellt godk&auml;nna nya konton, &auml;r den markerad sker det automatiskt';
$LN['config_urdd_path_msg']      = 'Fullst&auml;ndig s&ouml;kv&auml;g till URD-daemonens start-skript (urdd.sh)';
$LN['config_unpar_path_msg']      = 'Fullst&auml;ndig s&ouml;kv&auml;g till par2-programmet (frivilligt)';
$LN['config_unrar_path_msg']     = 'Fullst&auml;ndig s&ouml;kv&auml;g till rar- eller unrar-programmet f&ouml;r uppackning (frivilligt)';
$LN['config_rar_path_msg']       = 'Fullst&auml;ndig s&ouml;kv&auml;g till rar-programmet f&ouml;r nedpackning (frivilligt)';
$LN['config_tar_path_msg']       = 'Fullst&auml;ndig s&ouml;kv&auml;g till tar-programmet (frivilligt)';
$LN['config_unace_path_msg']     = 'Fullst&auml;ndig s&ouml;kv&auml;g till unace-programmet (frivilligt)';
$LN['config_un7zr_path_msg']     = 'Fullst&auml;ndig s&ouml;kv&auml;g till 7za-, 7zr- eller 7z-programmet (frivilligt)';
$LN['config_unzip_path_msg']     = 'Fullst&auml;ndig s&ouml;kv&auml;g till unzip-programmet (frivilligt)';
$LN['config_gzip_path_msg']      = 'Fullst&auml;ndig s&ouml;kv&auml;g till gzip-programmet (frivilligt)';
$LN['config_unarj_path_msg']     = 'Fullst&auml;ndig s&ouml;kv&auml;g till unarj-programmet (frivilligt)';
$LN['config_file_path_msg']      = 'Fullst&auml;ndig s&ouml;kv&auml;g till file-programmet';
$LN['config_yydecode_path_msg']  = 'Fullst&auml;ndig s&ouml;kv&auml;g till yydecode-programmet';
$LN['config_yyencode_path_msg']  = 'Fullst&auml;ndig s&ouml;kv&auml;g till yyencode-programmet (frivilligt)';
$LN['config_cksfv_path_msg']     = 'Fullst&auml;ndig s&ouml;kv&auml;g till cksfv-programmet (frivilligt)';
$LN['config_trickle_path_msg']   = 'Fullst&auml;ndig s&ouml;kv&auml;g till trickle-programmet (frivilligt)';
$LN['config_period_update_msg']  = 'Hur ofta tillg&auml;ngligheten av en ny version av URD kontrolleras';
$LN['config_period_opt_msg']        = 'Hur ofta databasen optimeras';
$LN['config_period_ng_msg']     = 'Hur ofta listan &ouml;ver Usenet-grupper uppdateras';
$LN['config_period_cd_msg']     = 'Hur ofta /preview- och /tmp-katalogerna rensas';
$LN['config_period_cdb_msg']    = 'Hur ofta flyktig information rensas ut fr&aring;n databasen';
$LN['config_log_level_msg']     = 'Hur mycket log-information URD-daemonen ger ifr&aring;n sig';
$LN['config_period_sendinfo']      = 'Skicka set-infomation';
$LN['config_period_sendinfo_msg']  = 'Skicka information till URDland.com';
$LN['config_period_getinfo']       = 'H&auml;mta set-information';
$LN['config_period_getinfo_msg']   = 'H&auml;mta information fr&aring;n URDland.com';
$LN['config_keep_interesting']     = 'Spara intressanta artiklar n&auml;r de g&aring;tt ut';
$LN['config_keep_interesting_msg'] = 'Spara artiklar markerade som intressanta &auml;ven efter att de &auml;r f&ouml;r&aring;ldrade n&auml;r f&ouml;r&aring;ldrade set rensas ut';

$LN['config_auto_download_msg'] = 'Till&aring;t anv&auml;ndare att ladda ned filer automatiskt baserat p&aring; s&ouml;ktermer';
$LN['config_socket_timeout_msg']= 'Antal sekunder innan en anslutning l&ouml;per ut och st&auml;ngs; f&ouml;rvalt &auml;r 30';
$LN['config_sendmail']          = 'Till&aring;t att email skickas';
$LN['config_sendmail_msg']      = 'Om detta &auml;r valt s&aring; kan email skickas f&ouml;r saker som t ex gl&ouml;mda l&ouml;senord, &aring;terst&auml;llning av l&ouml;senord samt slutf&ouml;rda nedladdningar.';
$LN['config_follow_link']       = 'Follow links in NFO files when updating';
$LN['config_follow_link_msg']   = 'If checked, links in NFO files are automatically parsed after group updating';

$LN['config_total_max_articles']        = 'Max artiklar per uppdatering';
$LN['config_total_max_articles_msg']    = 'Max antal artiklar som kan laddas ned per uppdatering (0 betyder obegr&auml;nsat)';

$LN['config_prog_params']           = 'Argument';

$LN['config_urdd_pars_msg']         = 'Argument till urdd';
$LN['config_unpar_pars_msg']        = 'Argument till par2';
$LN['config_unrar_pars_msg']        = 'Argument till rar f&ouml;r uppackning';
$LN['config_rar_pars_msg']          = 'Argument till rar f&ouml;r nedackning';
$LN['config_unace_pars_msg']        = 'Argument till unace';
$LN['config_tar_pars_msg']          = 'Argument till tar';
$LN['config_un7zr_pars_msg']        = 'Argument till un7za';
$LN['config_unzip_pars_msg']        = 'Argument till unzip';
$LN['config_gzip_pars_msg']         = 'Argument till gzip';
$LN['config_unarj_pars_msg']        = 'Argument till unarj';
$LN['config_yydecode_pars_msg']     = 'Argument till yydecode';
$LN['config_yyencode_pars_msg']     = 'Argument till yyencode';

$LN['config_perms']['none'] = '&Auml;ndra inte';
$LN['config_perms']['0400'] = '&Auml;gare l&auml;s-r&auml;ttighet (0400)';
$LN['config_perms']['0440'] = '&Auml;gare och grupp l&auml;sr&auml;ttighet (0440)';
$LN['config_perms']['0444'] = 'L&auml;sr&auml;ttighet f&ouml;r alla (0444)';
$LN['config_perms']['0600'] = '&Auml;gare l&auml;s- och skriv-r&auml;ttighet (0600)';
$LN['config_perms']['0640'] = '&Auml;gare l&auml;s- och skriv-r&auml;ttighet, grupp l&auml;s-r&auml;ttighet (0640)';
$LN['config_perms']['0644'] = '&Auml;gare l&auml;s- och skriv-r&auml;ttighet, &ouml;vriga l&auml;s-r&auml;ttighet (0644)';
$LN['config_perms']['0660'] = '&Auml;gare och grupp l&auml;s- och skriv-r&auml;ttighet (0660)';
$LN['config_perms']['0664'] = '&Auml;gare och grupp l&auml;s- och skriv-r&auml;ttighet, &ouml;vriga l&auml;s-r&auml;ttighet (0664)';
$LN['config_perms']['0666'] = 'L&auml;s- och skriv-r&auml;ttighet f&ouml;r alla (0666)';

// admin log
$LN['log_title']        = 'Logg-fil';
$LN['log_nofile']       = 'Ingen logg-fil funnen';
$LN['log_seekerror']    = 'Kunde inte l&auml;sa hela filen';
$LN['log_unknownerror'] = 'Ett ov&auml;ntat fel uppstod';
$LN['log_header']       = 'Logg-information';
$LN['log_date']         = 'Datum';
$LN['log_level']        = 'Niv&aring;';
$LN['log_msg']          = 'Meddelande';
$LN['log_notopenlogfile']   = 'Kunde inte &ouml;ppna logg-filen';
$LN['log_lines']        = 'Rader';

// FAQ
$LN['faq_title']        = 'FAQ';

//Manual
$LN['manual_title']     = 'Manual';

//admin users
$LN['users_title']          = 'anv&auml;ndare';
$LN['users_isadmin']        = 'Administrat&ouml;r';
$LN['users_autodownload']   = 'Till&aring;t automatisk nedladdning';
$LN['users_fileedit']       = 'Edit files';
$LN['users_post']           = 'Uppladdare';
$LN['users_post_help']      = 'Den h&auml;r anv&auml;ndaren f&aring;r lov att posta till Usenet-servern';
$LN['users_resetpw']        = '&Aring;terst&auml;ll och skicka l&ouml;senord';
$LN['users_edit']           = 'Redigera anv&auml;ndare';
$LN['users_addnew']         = 'L&auml;gg till en ny anv&auml;ndare';
$LN['users_delete']         = 'Radera anv&auml;ndare';
$LN['users_enable']         = 'Enable user';
$LN['users_disable']        = 'Disable user';
$LN['users_rights']         = 'Set-redakt&ouml;r';
$LN['users_rights_help']    = 'Till&aring;ter att den h&auml;r anv&auml;ndaren redigerar set-information i bl&auml;ddrings-sektionen';
$LN['users_last_active']    = 'Aktiv';
$LN['users_allow_erotica']  = 'Allow Adult content';
$LN['users_allow_update']   = 'Allow updating databases';

$LN['error_nocontent']      = 'Message too short';
$LN['error_toolong']        = 'Message too long';
$LN['error_noadmin']        = 'Inga administrat&ouml;rs-r&auml;attigheter';
$LN['error_accessdenied']   = '&Aring;tkomst nekad';
$LN['error_invalidfullname']    = 'Ogiltigt fullst&auml;ndigt namn';
$LN['error_invalidusername']    = 'Ogiltigt anv&auml;ndar-namn';
$LN['error_userexists']     = 'Anv&auml;ndaren existerar redan';
$LN['error_invalidid']      = 'Ogiltigt ID angivet';
$LN['error_nosuchuser']     = 'Anv&auml;ndaren existerar inte';
$LN['error_nouserid']       = 'Inget anv&auml;ndar-ID angivet';
$LN['error_invalidchallenge']  = 'Eventellt har en f&ouml;rfalskad cross-site-f&ouml;rfr&aring;gan utf&ouml;rts. F&ouml;rfr&aring;gan avbr&ouml;ts. (Ladda om sidan och f&ouml;rs&ouml;k igen)'; // Crappy Swinglish, FIXME
$LN['error_toomanydays']    = 'Det finns bara 24 timmar per dygn';
$LN['error_toomanymins']    = 'Det finns bara 60 minuter per timme';
$LN['error_bogusexptime']   = 'Ogiltig l&ouml;ptid angiven';
$LN['error_invalidupdatevalue'] = 'Ogitligt uppdaterings-v&auml;rde mottaget';
$LN['error_nodlpath']       = 'Nedladdnings-s&ouml;kv&auml;g inte angiven';
$LN['error_dlpathnotwritable']  = ' Nedladdnings-s&ouml;kv&auml;gen &auml;r inte skrivbar';
$LN['error_setithere']      = 'Ange h&auml;r';
$LN['error_nousers']        = 'Inga anva&uml;ndare funna, v&auml;nligen k&ouml;r installations-skriptet igen';
$LN['error_filenotallowed'] = '&Aring;tkomst till fil nekad';
$LN['error_filenotfound']   = 'Fil inte funnen';
$LN['error_filereaderror']  = 'Fil kunde inte l&auml;sas';
$LN['error_dirnotfound']    = 'Kan inte &ouml;ppna katalog';
$LN['error_unknown_sort']   = 'Ok&auml;nd sorterings-ordning';
$LN['error_invalidlinescount']  = 'Radantalet m&aring;ste vara numeriskt';
$LN['error_urddconnect']    = 'Kunde inte ansluta till URD-daemonen';
$LN['error_createdlfailed'] = 'Kunde inte skapa nedladdning';
$LN['error_setsnumberunknown']  = 'Kunde inte fastst&auml;lla det totala antalet set';
$LN['error_noqueue']        = 'Ingen k&ouml; hittades...';
$LN['error_novalidaction']  = 'Ingen giltig aktivitet hittades.';
$LN['error_readnzbfailed']  = 'Kunde inte l&auml;sa in NZB-fil';
$LN['error_nopartsinnzb']   = 'Inga delar kunde hittas i NZB-filen';
$LN['error_invalidgroup']   = 'Ogiltig grupp; Grupp-namnet m&aring;ste existera i /etc/group';
$LN['error_notanumber']     = 'Inte ett heltal';
$LN['error_cannotchmod']    = 'F&ouml;r&auml;ndring av &aring;tkomst-r&auml;ttigheter till&aring;ts inte';
$LN['error_cannotchgrp']    = 'F&ouml;r&auml;ndring av grupp till&aring;ts inte';

// Transfers
$LN['transfers_title']          = 'Nedladdningar';
$LN['transfers_importnzb']      = 'Importera NZB-fil';
$LN['transfers_import']         = 'Importera';
$LN['transfers_clearcompleted'] = 'Rensa nedladdningar';
$LN['transfers_pauseall']       = 'Pausa nedladdningar';
$LN['transfers_continueall']    = 'Forts&auml;tt nedladdningar';
$LN['transfers_nzblocation']    = 'S&ouml;kv&auml;g till fj&auml;rr-NZB';
$LN['transfers_nzblocationext'] = 'Detta kan vara en URL (b&ouml;rja med http://) eller en lokal fil-s&ouml;kv&auml;g (t ex. /tmp/file.nzb';
$LN['transfers_nzbupload']      = 'Ladda upp en lokal NZB-fil';
$LN['transfers_nzbuploadext']   = 'Om du har en NZB-fil p&aring; din lokala dator s&aring; kan du ladda upp den till URD-servern';
$LN['transfers_uploadnzb']      = 'Ladda upp en NZB-fil';  // FIXME differs how exactly from transfers_nzbupload?
$LN['transfers_runparrar']      = 'K&ouml;r par2 och unrar';
$LN['transfers_add_setname']    = 'L&auml;gg till setnamn till nedladdningskatalog';

$LN['transfers_status_removed'] = 'Borttaget';
$LN['transfers_status_ready']   = 'Klar att starta';
$LN['transfers_status_queued']  = 'I k&ouml;';
$LN['transfers_status_active']  = 'Laddas ned';
$LN['transfers_status_finished']= 'Slutf&ouml;rt';
$LN['transfers_status_postactive']  = 'Postar';
$LN['transfers_status_cancelled'] = 'Avbrutet';
$LN['transfers_status_yyencodefailed'] = 'Yenc-enkodning misslyckades';
$LN['transfers_status_paused']  = 'Paus';
$LN['transfers_status_stopped'] = 'Stoppat';
$LN['transfers_status_shutdown']= 'St&auml;nger ned';
$LN['transfers_status_error']   = 'Fel';
$LN['transfers_status_complete'] = 'Behandlar';
$LN['transfers_status_rarfailed'] = 'Komprimering misslyckades';
$LN['transfers_status_unrarfailed'] = 'Uppackning misslyckades';
$LN['transfers_status_failed'] = 'Misslyckades';
$LN['transfers_status_running'] = 'Aktiv';
$LN['transfers_status_crashed'] = 'Krashad';
$LN['transfers_status_par2failed'] = 'Par2 misslyckades';
$LN['transfers_status_cksfvfailed'] = 'Cksfv misslyckades';
$LN['transfers_status_dlfailed'] = 'Artiklar saknas';

$LN['transfers_linkview']   = 'Visa filer';
$LN['transfers_linkstart']  = 'Starta';
$LN['transfers_linkedit']   = 'Redigera';
$LN['transfers_details']    = '&Ouml;verf&ouml;rings-detaljer';
$LN['transfers_name']       = 'Namn p&aring; nedladdning';
$LN['transfers_archpass']   = 'Arkiv-l&ouml;senord';
$LN['transfers_head_started']   = 'Startad';
$LN['transfers_head_dlname']    = 'Nedladdningsnamn';
$LN['transfers_head_progress']  = 'Status';
$LN['transfers_head_username']  = 'Anv&auml;ndare';
$LN['transfers_head_speed'] = 'Hastighet';
$LN['transfers_head_options']   = 'Inst&auml;llningar';
$LN['transfers_unrar']      = 'Unrar';
$LN['transfers_unpar']      = 'Unpar';
$LN['transfers_deletefiles']    = 'Radera filer';
$LN['transfers_subdl']      = 'Ladda ned undertexter';
$LN['transfers_badrarinfo'] = 'Visa rar-loggen';
$LN['transfers_badparinfo'] = 'Visa par2-loggen';

$LN['transfers_status_rarred']      = 'Rar-arkiv skapat';
$LN['transfers_status_par2ed']      = 'Par2 skapad';
$LN['transfers_status_yyencoded']   = 'Yenc-kodad';
$LN['transfers_head_subject']       = '&Auml;mne';
$LN['transfers_posts']      = 'Uppladdningar';
$LN['transfers_post']       = 'Ladda upp';
$LN['transfers_downloads']  = 'Nedladdningar';

// Fatal error
$LN['fatal_error_title']    = 'Meddelande';

// admin_buttons
$LN['buttons_title']        = 'S&ouml;k-alternativ';
$LN['buttons_url']          = 'S&ouml;k-URL';
$LN['buttons_edit']         = 'Redigera';
$LN['buttons_editbutton']   = 'Modifiera knapp';
$LN['buttons_addbutton']    = 'L&auml;gg till en ny knapp';
$LN['buttons_test']         = 'Testa';
$LN['buttons_nobuttonid']   = 'Inget knapp-ID angivet';
$LN['buttons_invalidname']  = 'Ogiltigt namn angivet';
$LN['buttons_invalidurl']   = 'Ogiltig s&ouml;k-URL angiven';
$LN['buttons_clicktest']    = 'Klicka f&ouml;r att testa';
$LN['buttons_buttonexists'] = 'Det finns redan en knapp med det h&auml;r namnet';
$LN['buttons_buttonnotfound']   = 'Knappen hittades inte';

// login
$LN['login_title']          = 'Inloggning';
$LN['login_title2']         = 'V&auml;lkommen till';
$LN['login_jserror']        = 'Javascript kr&auml;vs fouml;r att URD-interfacet ska fungera ordentligt. V&auml;nligen aktivera detta.';
$LN['login_oneweek']        = 'I en vecka';
$LN['login_onemonth']       = 'I en m&aring;nad';
$LN['login_oneyear']        = 'I ett &aring;r';
$LN['login_forever']        = 'F&ouml;r alltid';
$LN['login_closebrowser']   = 'Tills jag st&auml;nger webl&auml;saren';
$LN['login_login']          = 'Logga in';
$LN['login_remember']       = 'Kom ih&aring;g mig';
$LN['login_bindip']         = 'Koppla sessionen till IP-adress';
$LN['login_forgot_password']    = 'Jag har gl&ouml;mt mitt l&ouml;senord';
$LN['login_register']       = 'Jag vill skapa ett konto';

$LN['login_failed']         = 'Ditt anv&auml;ndarnamn och/eller l&ouml;senord var felaktigt';

// browse
$LN['browse_allsets']       = 'Alla set';
$LN['browse_interesting']   = 'Intressanta';
$LN['browse_killed']        = 'Dolda';
$LN['browse_nzb']           = 'NZB-baserade';
$LN['browse_downloaded']    = 'Nedladdade';
$LN['browse_addedsets']     = 'Tillagda set';
$LN['browse_allgroups']     = 'Alla Usenet-grupper';
$LN['browse_searchsets']    = 'S&ouml;k bland set';
$LN['browse_addtolist']     = 'L&auml;gg till i lista';
$LN['browse_emptylist']     = 'T&ouml;m lista';
$LN['browse_savenzb']       = 'Spara NZB-fil';
$LN['browse_download']      = 'Ladda ned';
$LN['browse_subject']       = '&Auml;mne';
$LN['browse_age']           = '&Aring;lder';
$LN['browse_followlink']    = 'Hoppa till l&auml;nk';
$LN['browse_percent']       = '%';
$LN['browse_removeset']     = 'G&ouml;m detta set';
$LN['browse_deleteset']     = 'Radera detta set';
$LN['browse_deletedsets']   = 'Deleted sets';
$LN['browse_deletedset']    = 'Deleted set';
$LN['browse_resurrectset']  = 'Tag tillbaka detta set';
$LN['browse_toggleint']     = 'Markera som intressant av/p&aring;';
$LN['browse_schedule_at']   = 'K&ouml;r vid';
$LN['browse_invalid_timestamp'] = 'Felaktig tids-angivelse';
$LN['browse_mergesets']     = 'Sl&aring; ihop set';
$LN['browse_download_dir']  = 'Download directory';
$LN['browse_add_setname']   = 'Add setname';
$LN['browse_userwhitelisted'] = 'User is on the whitelist';

$LN['NZB_created']          = 'NZB-fil skapad';

// Preview
$LN['preview_autodisp']     = 'Filen/filerna ska visas automatiskt.';
$LN['preview_autofail']     = 'Annars kan du klicka p&aring; den h&auml;r l&auml;nken';
$LN['preview_view']         = 'Klicka h&auml;r f&ouml;r att visa NZB-filen';
$LN['preview_header']       = 'Laddar ned f&ouml;rhandsgranskning';
$LN['preview_nzb']          = 'F&ouml;r att starta nedladdning direkt med hj&auml;lp av denna NZB-fil, klicka p&auml; denna l&auml;nken';
$LN['preview_failed']       = 'F&ouml;rhandsgranskning misslyckades';

// FAQ
$LN['faq_content'][1] = array ('Vad &auml;r URD f&ouml;r n&aring;got?',  'URD &auml;r ett program f&ouml;r att ladda ned bin&auml;rer fr&aring;n Usenet (newsgroups) med ett web-interface.'
    .' Det &auml;r skrivet helt i PGP, &auml;ven om det ocks&aring; anv&auml;nder n&aring;gra externa program f&ouml;r att g&ouml;ra en del'
    .' av det CPU-intensiva arbetet. Det lagrar all information det beh&ouml;ver i en generell databas'
    .' (som MySQL, eller PostGreSQL). Artiklar kommer sl&aring;s ihop till set som upplevs h&ouml;ra ihop.'
    .' Nedladdning kr&auml;ver endast n&aring;gra f&aring; musklick. En NZB-fil kan ocks&aring; skapas. N&auml;r nedladdningen'
    .' slutf&ouml;rts kan den automatiskt kontrollera par2- eller sfv-filer samt packa upp resultatet.'
    .' I bakgrunden anv&auml;nder URD ett nedladdnings-program som kallas URD-Daemonen (URDD). Den h&auml;r daemonen hanterar n&auml;stan'
    .' all interaktion med Usenet-grupperna, seten och nedladdningarna'
    .' URD licensieras under GPL 3. Se filen COPYING f&ouml;r detaljer ang&aring;ende licensen.<br>');

$LN['faq_content'][2] = array('Var kommer namnet ifr&aring;n?', 'URD &auml;r en backronym av Usenet Resource Downloader. Termen URD h&auml;rstammar fr&aring;n nordiska kulturer'
    .' refererande till <a href="http://sv.wikipedia.org/wiki/Urdarbrunnen" title="Urdarbrunnen i Wikipedia">Urds brunn</a>, som &auml;r en helig brunn, '
    .' &ouml;desbrunnen, och vattenk&auml;llan f&ouml;r v&auml;rldstr&auml;det'
    .' <a href="http://sv.wikipedia.org/wiki/Yggdrasil" title="Yggdrasil i Wikipedia">Yggdrasil</a>. '
    .' Den gamla Engelska termen &auml;r Wyrd. Den n&auml;rmaste betydelsen av URD &auml;r &Ouml;det.<br>');

$LN['faq_content'][3] = array('Vad g&ouml;r jag om det inte fungerar?', 'Kontrollera f&ouml;rst dina inst&auml;llningar och se om du kan ansluta till NNTP-servern.'
    .' Titta i Apache-loggen (eller loggen f&ouml;r den webserver du anv&auml;nder) och URD-loggen (normalt /tmp/urd.log). Om det &auml;r en bugg,'
    .' var sn&auml;ll och rapportera den p&aring; websiten hos Sourceforge. Se <a href="http://sourceforge.net/projects/urd/">URDs sourceforge-sida</a>.'
    .' Annars kan du diskutera p&aring; forumet. Se <a href="http://www.urdland.com/forum/">URD land</a>.<br>');

$LN['faq_content'][4] = array('St&ouml;der URD SSL?', 'Ja, fr&aring;n och med version 0.4.<br>');

$LN['faq_content'][5] = array('St&ouml;der URD autentiserade anslutningar till Usenet-servern?', 'Ja.<br>');

$LN['faq_content'][6] = array('Kan ni l&auml;gga till den h&auml;r fantastiska funktionen jag kommit p&aring;?', 'Skicka in en f&ouml;rfr&aring;gan s&aring; ska vi &ouml;verv&auml;ga saken. Kanske blir det en del av n&auml;sta version. Se &#34;feature requests&#34; h&auml;r: <a href="http://sourceforge.net/tracker/?group_id=204007&amp;atid=987882">SourceForge</a>.<br>');

$LN['faq_content'][7] = array('Kan urdd-daemonen k&ouml;ras p&aring; en annan maskin &auml;n web-interfacet?', 'Rent tekniskt best&aring;r URD av tre delar som '
    .' kan installera p&aring; separata maskiner, <ul><li>Databasen</li><li>URDD</li><li>Web-interfacet</li></ul> Detta &auml;r dock inte testat &auml;nnu.<br>');

$LN['faq_content'][8] = array('Kan URD arbeta med NZB-filer?', 'Ja. Det finns flera s&auml;tt att arbeta med NZB-filer i URD. F&ouml;rst och fr&auml;mst att anv&auml;nda NZB-filer'
    .' att ladda ned med. P&aring; nedladdnings-sidan finns det m&ouml;jlighet att ladda upp en lokalt lagrad NZB-fil. P&aring; samma sida finns &auml;ven m&ouml;jlighet'
    .' att ange en extern l&auml;nk till en NZB-fil. Vidare postar vissa Usenet-grupper NZB-filer; anv&auml;ndandet av funktionen f&ouml;rhandsgranskning p&aring; en'
    .' NZB-fil ger dig m&ouml;jligheten att ladda ned direkt med hj&auml;lp av den filen. Slutligen, i "visa filer", finns &auml;ven det en uppladdnings-knapp bland valen'
    .' f&ouml;r NZB-filer. Utanf&ouml;r web-delen kan du anv&auml;nda en speciell katalog som heter spool/anv&auml;ndarnamn d&auml;r du kan l&auml;gga en NZB-fil och den kommer'
    .' d&aring; att anv&auml;ndas f&ouml;r nedladdning. Men det finns mer. URD kan ocks&aring; anv&auml;ndas f&ouml;r att skapa egna NZB-filer fr&aring;n index som det har'
    .' lagrat. Dessa kan delas med andra. Detta fungerar p&aring; samma s&auml;tt som nedladdningar fr&aring;n Bl&auml;ddrings-sidan, men du klickar p&aring; NZB-knappen'
    .' ist&auml;llet. NZB-filen kommer sparars i en underkatalog till nedladdnings-katalogen som heter nzb/anv&auml;ndarnamn.<br>');

$LN['faq_content'][9] = array('Hur graderar jag upp till en ny version?', 'F&ouml;r n&auml;rvarande finns det inget automatiskt s&auml;tt att g&ouml;ra detta. Kortfattat inneb&auml;r'
    .' detta att du m&aring;ste k&ouml;ra installations-skriptet f&ouml;r den nya versionen och antingen v&auml;lja en ny databas, eller v&auml;lja att radera den'
    .' befintliga anv&auml;ndaren och databasen.<br>');

$LN['faq_content'][10] = array('Hur licensieras URD?', 'F&ouml;r majoriteten av koden g&auml;ller GPL v3. Vissa delar &auml;r l&aring;nade fr&aring;n andra projekt och har en annan licens.<br>');

$LN['faq_content'][11] = array('B&ouml;r jag ladda ned tar-arkivet eller anv&auml;nda subversion (svn) f&ouml;r att f&aring; tag p&aring; URD? ', 'Vi rekommenderar starkt att'
    .' de officiellt sl&auml;ppta tar-arkiven anv&auml;nds och inte subversion. K&auml;llkoden fr&aring;n subversion fungerar eventuellt inte ordentligt,'
    .' och kan inneh&aring;lla endast delvis implementerade funktioner. Detta &auml;r ungef&auml;r som att anv&auml;nda nattliga, automatiskt kompilerade'
    .' program. S&aring; var sn&auml;ll och anv&auml;nd de officiellt sl&auml;ppta versionerna.<br>');

$LN['faq_content'][12] = array('Min fr&aring;ga finns inte med. Vad g&ouml;r jag nu?', 'L&auml;mna g&auml;rna ett meddelande p&aring; forumet som finns h&auml;r:'
    .' <a href="http://www.URDland.com/forum/">Urdland</a>.<br>');

$LN['faq_content'][13] = array('Jag skulle vilja donera till det h&auml;r projektet. Hur g&ouml;r jag?', 'Fantastiskt! Ett tecken p&aring; uppskattning &auml;r alltid '
    .' v&auml;ldigt v&auml;lkommet. Vi har inte alltf&ouml;r mycket utgifter, men v&aring;r serverplats kostar oss 50 euro per &aring;r. Det l&auml;ttaste s&auml;ttet'
    .' f&ouml;r v&aring;r del vore att anv&auml;ndea PayPal. Det finns en donations-knapp <a href="http://urdland.com/cms/component/option,com_wrapper/Itemid,33/">h&auml;r</a>.'
    .' Om du vill anv&auml;nda dig av en annan betalningsmetid s&aring; var sn&auml;ll och skicka ett mail till "dev@ urdland . com", eller skicka'
    .' ett PM p&aring; forumet s&aring; kan vi utbyta information s&aring;som adresser eller bankkonto-nummer.<br>');

$LN['manual_content'][1] = array ('Allm&auml;nt', 'De flesta delarna av URDs websida har omedelbart tillg&auml;nglig hj&auml;lp i form av'
    .' popuper. N&auml;r du h&aring;ller muspekaren &ouml;ver en l&auml;nk eller text visas hj&auml;lptexter.<br>');

$LN['manual_content'][2] = array ('Usenet-grupper', 'Efter installationen kan du logga in i URDs web-interface och klicka'
    .' p&aring; Usenet-grupper och s&ouml;ka efter grupper du vill prenumerera p&aring;. Om inga Usenet-grupper hittas s&aring; g&aring; till'
    .' Admin->Kontrollpanel och klicka p&aring; "Uppdatera listan &ouml;ver Usenet-grupper". Om det inte hj&auml;lper s&aring; kontrollera'
    .' inst&auml;llningarna. I listan &ouml;ver Usenet-grupper anger kolumnen "Max &aring;lder" efter hur m&aring;nga dagar artiklar raderas.'
    .' Det &auml;r ocks&aring; m&ouml;jligt att automatiskt uppdatera Usenet-gruppen. V&auml;lj &ouml;nskat intervall och ange klockslag n&auml;r'
    .' uppdateringen ska utf&ouml;ras och klicka p&aring; Spara.<br>');

$LN['manual_content'][3] = array ('RSS-kanaler', 'Du kan prenumerera p&aring; RSS-kanaler ocks&aring;. RSS-kanaler m&aring;ste f&ouml;rst l&auml;ggas till genom att att klicka p&aring; &#34;L&auml;gg till ny&#34; och sedan anges n&ouml;dv&auml;ndig information s&aring;som RSS-kanalens URL. I &Ouml;vrigt fungerar RSS-kanaler ungef&auml;r som Usenet-grupper.<br>');

$LN['manual_content'][4] = array ('Bl&auml;ddring', 'Efter att uppdateringen &auml;r klar, g&aring; till "Bl&auml;ddra bland set" vilket visar de set'
    .' som finns tillg&auml;ngliga. Klicka p&aring; setet och v&auml;lj "Visa set-info" i popup-menyn f&ouml;r detaljerad information om ett set.'
    .' L&auml;gg till ett set f&ouml;r nedladdning genom att klicka p&aring; "+" framf&ouml;r setet. Klicka sedan p&aring; "\/"-knappen f&ouml;r att b&ouml;rja'
    .' ladda ned valda set. NZB-knappen sparar det valda setet som en NZB-fil. "X" t&ouml;mmer nedladdnigslistan.'
    .' Genom att klicka p&aring; ett set f&aring;r du upp flera val f&ouml;r att f&aring; ytterligare information om ett set eller redigera det.'
    .' Information som du l&auml;gger till till ett set kan delas med andra genom urdland.com om du aktiverat denna funktion.<br>');

$LN['manual_content'][5] = array ('Nedladdning', 'N&auml;r en nedladdning har p&aring;b&ouml;rjats kan man se dess framsteg i Nedladdnings-sektionen.'
    .' Denna visar ocks&aring; statues p&aring; nedladdnignen. En direkt-l&auml;nk till nedladdnings-katalogen tillhandah&aring;lls h&auml;r.'
    .' Nedladdningen kan ocks&aring; avbrytas, pausas, bytas namn p&aring;, startas om och s&aring; vidare.<br>');

$LN['manual_content'][6] = array ('Visa filer', 'Genom "Visa filer" kan man bl&auml;ddra bland nedladdade filer, radera dem med mera.<br>');

$LN['manual_content'][7] = array ('Admin', 'Admin-fliken kan anv&auml;ndas f&ouml;r de flesta administrativa funktionerna, s&aring;som att starta eller stoppa URD-daemonen, avbryta eller pausa samtliga at&aring;g&auml;rder, ta bort jobb fr&aring;n databasen. Den kan ocks&aring; anv&auml;ndas f&ouml;r att uppdatera samtliga Usenet-grupper eller plocka borta alla f&ouml;r&aring;ldrade meddelanden i dessa, hantera anv&auml;ndare eller optimera databasen. Vidare ger den en &ouml;verblick av nyligen utf&ouml;rda aktiviteter och statusen p&auml; URD-daemonen. Konfigurationen av URD finns ocks&aring; h&auml;r.<br>');

$LN['manual_content'][8] = array ('Konfiguration', 'Denna sidan anv&auml;nds f&ouml;r att konfigurera de flesta av URDDs inst&auml;llningar<br>
    ');
$LN['manual_content'][9] = array ('Usenet-servrar', 'H&auml;r kan du konfigurera Usenet-servrarna. Det finns tv&aring; s&auml;tt att anv&auml;nda en Usenet-server. 1. Som en nedladdnings-server f&ouml;r bin&auml;rer som kontrolleras med en Av/P&aring;-knapp. Fler &auml;n 1 av dessa kan v&auml;ljas. 2. Som en indexerings-server, endast en av dessa kan vara aktiv. Den v&auml;ljs genom en klick-ruta.<br>');

$LN['manual_content'][10] = array ('Kontrollpanel','H&auml;r kan du utf&ouml;ra vissa grundl&auml;ggande funktioner f&ouml;r urdd, som att stoppa eller starta den, st&auml;da databasen, t&ouml;mma alla Usenet-grupper osv.<br>');
$LN['manual_content'][11] = array ('Aktiviteter','Detta tillhandah&aring;ller en &ouml;verblick av alla aktiva eller uppk&ouml;ade aktiviteter.<br>');

$LN['manual_content'][12] = array ('Jobb', 'URDD kan schemal&auml;gga jobb f&ouml;r senare exekvering, h&auml;r finns en &ouml;verblick av jobben.<br>');

$LN['manual_content'][13] = array ('Anv&auml;ndare', 'Sidan Anv&auml;ndare &auml;r till f&ouml;r konto-hantering; r&auml;ttighets-modifiering samt f&ouml;r att l&auml;gga till, ta bort eller deaktivera en anv&auml;ndare.<br>');

$LN['manual_content'][14] = array ('Knappar', 'Detta &auml;r s&ouml;k-knapparna som finns p&aring; Bl&auml;ddrings-sidan. S&ouml;k-URLen ska inneh&aring;lla &#34;$q&#34;, vilket kommer ers&auml;ttas med s&ouml;k-str&auml;ngen.<br>');

$LN['manual_content'][15] = array ('Logg','H&auml;r kan du se URDs logg-fil, s&ouml;ka i den osv. Titta h&auml;r ifall ett fel uppst&aring;r.<br>');

$LN['manual_content'][16] = array ('Inst&auml;llningar', 'Fliken Inst&auml;llningar kan anv&auml;ndas f&ouml;r att &auml;ndra p&aring; de flesta anv&auml;ndar-inst&auml;llningarna.<br>');

$LN['manual_content'][17] = array ('Status-&ouml;versikt', 'P&aring; v&auml;nstra sidan av sk&auml;rmen finns alltid en status-&ouml;versikt med statusen f&ouml;r URD-daemonen, av eller p&aring;, aktuella aktiviteter samt tillg&auml;ngligt diskutrymme. Den inloggade anv&auml;ndarens namn visas ocks&aring;. H&auml;r kan man ocks&aring; se om det finns en nyare version av URD tillg&auml;nglig.<br>');

$LN['manual_content'][18] = array ('Det fungerar inte', 'Kontrollera f&ouml;rst dina inst&auml;llningar och se om du kan ansluta till NNTP-servern. G&ouml;r om samma sak igen med logg-niv&aring;n satt till debug, titta i Apache-loggen (eller loggen f&ouml;r den webserver du anv&auml;nder) och URD-loggen (normalt /tmp/urd.log). Om det &auml;r en bugg, var sn&auml;ll och rapportera den p&aring; websiten hos Sourceforge. Se <a href="http://sourceforge.net/projects/urd/">URDs sourceforge-sida</a>. Annars kan du diskutera p&aring; forumet. Se <a href="http://www.urdland.com/forum/">URD land</a>. T&auml;nk p&aring; att l&auml;gga till s&aring; mycket information som m&ouml;jligt om du rapporterar en bugg eller andra problem, s&aring;som relevanta logg-utdrag, felmeddelanden och inst&auml;llningar. <a href="debug.php">Debug-sidan</a> kan ocks&aring; anv&auml;ndas f&ouml;r att samla all information fr&aring;n URD-daemonen.<br>');

// ajax_showsetinfo:
$LN['showsetinfo_postedin'] = 'Postad i';
$LN['showsetinfo_postedby'] = 'Postad av';
$LN['showsetinfo_size']     = 'Total storlek';
$LN['showsetinfo_shouldbe'] = 'Borde vara';
$LN['showsetinfo_par2']     = 'Par2';
$LN['showsetinfo_setname']  = 'Set-namn';
$LN['showsetinfo_typeofbinary'] = 'Typ av bin&auml;r';

// download basket
$LN['basket_totalsize']     = 'Total storlek';
$LN['basket_setname']       = 'Namn p&aring; nedladdning';

// usenet servers
$LN['usenet_title']          = 'Usenet-servrar';
$LN['usenet_name']           = 'Namn';
$LN['usenet_hostname']       = 'V&auml;rdnamn';
$LN['usenet_port']           = 'Port';
$LN['usenet_secport']        = 'S&auml;ker port';
$LN['usenet_authentication'] = 'Inloggning';
$LN['usenet_threads']        = 'Anslutningar';
$LN['usenet_connection']     = 'Kryptering';
$LN['usenet_needsauthentication']   = 'Kr&auml;ver autentisering';
$LN['usenet_addnew']         = 'L&auml;gg till ny';
$LN['usenet_nrofthreads']    = 'Antal anslutningar';
$LN['usenet_connectiontype'] = 'Typ av kryptering';
$LN['usenet_name_msg']       = 'Namnet du vill ska referera till den h&auml;r servern';
$LN['usenet_hostname_msg']   = 'V&auml;rdnamn eller IP-adress p&auml; servern (OBS: IPv6-adresser m&aring;ste omges av [])';
$LN['usenet_port_msg']       = 'Portnumret p&aring; Usenet-servern f&ouml;r okrypterade anslutningar';
$LN['usenet_secport_msg']    = 'Portnumret p&aring; Usenet-servern f&ouml;r krypterade anslutningar(SSL eller TLS)';
$LN['usenet_needsauthentication_msg'] = 'Klicka i om servern kr&auml;ver autentisering';
$LN['usenet_username_msg']   = 'Anv&auml;ndarnamnet som kr&auml;vs om Usenet-servern kr&auml;ver autentisering';
$LN['usenet_password_msg']   = 'L&ouml;senordet som kr&auml;vs om Usenet-servern kr&auml;ver autentisering';
$LN['usenet_nrofthreads_msg']       = 'Det h&ouml;gsta antalet tr&aring;dar som f&aring;r k&ouml;ras samtidigt p&aring; den h&auml;r servern';
$LN['usenet_connectiontype_msg']    = 'Krypterings-typen som anv&auml;nds vid anslutning till den h&auml;r Usenet-servern';
$LN['usenet_priority']      = 'Prioritet';
$LN['usenet_priority_msg']  = 'Prioritet: 1 h&ouml;gst; 100 l&ouml;gst; 0 avst&auml;ngd';
$LN['usenet_enable']        = 'Anv&auml;nd';
$LN['usenet_disable']       = 'Anv&auml;nd inte';
$LN['usenet_delete']        = 'Radera server';
$LN['usenet_edit']          = 'Editera server';
$LN['usenet_preferred_msg'] = 'Det h&auml;r &auml;r den prim&auml;ra servern, anv&auml;nds f&ouml;r att indexera grupper';
$LN['usenet_set_preferred_msg'] = 'Anv&auml;nd den h&auml;r servern f&ouml;r att indexera grupper';
$LN['usenet_indexing']          = 'Indexering';
$LN['usenet_addserver']         = 'L&auml;gg till en ny Usenet-server';
$LN['usenet_editserver']        = 'Redigera en Usenet-server';
$LN['usenet_compressed_headers']        = 'Anv&auml;nd komprimerade artikel-huvuden';
$LN['usenet_compressed_headers_msg']    = 'Anv&auml;nd komprimerade artikel-huvuden (&#34;headers&#34;) f&ouml;r att uppdatera grupper. St&ouml;ds inte av alla servrar. Titta efter XZVER-kommandot.';
$LN['usenet_posting']       = 'Postning';
$LN['usenet_posting_msg']   = 'Till&aring;t postning';

$LN['usenet_preferred']     = 'F&ouml;redra';
$LN['usenet_set_preferred'] = 'S&auml;tt som f&ouml;redragen';

$LN['forgot_title']     = 'Gl&ouml;mt l&ouml;senord';
$LN['forgot_sent']      = 'L&ouml;senord skickat';
$LN['forgot_mail']      = 'Skicka';

$LN['browse_tag_setname']   = 'Set-namn';
$LN['browse_tag_name']      = 'Namn';
$LN['browse_tag_year']      = '&Aring;r';
$LN['browse_tag_lang']      = 'Ljud-spr&aring;k';
$LN['browse_tag_sublang']   = 'Undertext-spr&aring;k';
$LN['browse_tag_artist']    = 'Artist';
$LN['browse_tag_quality']   = 'Kvalitet';
$LN['browse_tag_runtime']   = 'Runtime';
$LN['browse_tag_movieformat']   = 'Film-format';
$LN['browse_tag_audioformat']   = 'Ljud-format';
$LN['browse_tag_musicformat']   = 'Musik-format';
$LN['browse_tag_imageformat']   = 'Bild-format';
$LN['browse_tag_softwareformat']= 'Mjukvaru-format';
$LN['browse_tag_gameformat']    = 'Spel-format';
$LN['browse_tag_gamegenre']     = 'Spel-genre';
$LN['browse_tag_moviegenre']    = 'Film-genre';
$LN['browse_tag_musicgenre']    = 'Musik-genre';
$LN['browse_tag_imagegenre']    = 'Bild-genre';
$LN['browse_tag_softwaregenre'] = 'Mjukvaru-genre';
$LN['browse_tag_os']            = 'Operativsystem';
$LN['browse_tag_genericgenre']  = 'Genre';
$LN['browse_tag_episode']       = 'Avsnitt';
$LN['browse_tag_moviescore']    = 'Film-betyg';
$LN['browse_tag_score']         = 'Betyg';
$LN['browse_tag_musicscore']    = 'Musik-betyg';
$LN['browse_tag_movielink']     = 'Film-l&auml;nk';
$LN['browse_tag_link']          = 'L&auml;nk';
$LN['browse_tag_musiclink']     = 'Musik-l&auml;nk';
$LN['browse_tag_serielink']     = 'Serie-l&auml;nk';
$LN['browse_tag_xrated']        = 'Bfbj.';
$LN['browse_tag_note']          = 'Kommentarer';
$LN['browse_tag_author']        = 'Upphovsman';
$LN['browse_tag_ebookformat']   = 'eBook-format';
$LN['browse_tag_password']      = 'L&ouml;senord';
$LN['browse_tag_copyright']     = 'Upphovsr&auml;ttskyddad';

$LN['quickmenu_setsearch']      = 'S&ouml;k';
$LN['quickmenu_addblacklist']   = 'Add spotter to blacklist';
$LN['quickmenu_addposterblacklist']   = 'Add poster to blacklist';
$LN['quickmenu_addglobalblacklist']   = 'Add spotter to global blacklist';
$LN['quickmenu_addglobalwhitelist']   = 'Add spotter to global whitelist';
$LN['quickmenu_addwhitelist']   = 'Add spotter to whitelist';
$LN['quickmenu_report_spam']    = 'Report spot as spam';
$LN['quickmenu_comment_spot']   = 'Post comment on spot';
$LN['quickmenu_editspot']       = 'Redigera spot';
$LN['quickmenu_setshowesi']     = 'Visa set-info';
$LN['quickmenu_seteditesi']     = 'Redigera set-info';
$LN['quickmenu_setguessesi']    = 'Gissa set-info';
$LN['quickmenu_setbasketguessesi']= 'Gissa set-info f&ouml;r allt i nedladdnings-korgen';
$LN['quickmenu_setguessesisafe']= 'Gissa set-info och validera';
$LN['quickmenu_setpreviewnfo']  = 'F&ouml;rhandsgranska NFO-fil';
$LN['quickmenu_setpreviewimg']  = 'F&ouml;rhandsgranska bild-fil';
$LN['quickmenu_setpreviewnzb']  = 'F&ouml;rhandsgranska NZB-fil';
$LN['quickmenu_setpreviewvid']  = 'F&ouml;rhandsgranska video-fil';
$LN['quickmenu_add_search']     = 'Automatiskt markera';
$LN['quickmenu_add_block']      = 'Automatiskt d&ouml;lja';

$LN['blacklist_spotter']        = 'Blacklist spotter?';
$LN['whitelist_spotter']        = 'Whitelist spotter?';

$LN['stats_title']  = 'Statistik';
$LN['stats_dl']     = 'Nedladdningar';
$LN['stats_pv']     = 'F&ouml;rhandsgranskningar';
$LN['stats_im']     = 'Importerade NZB-filer';
$LN['stats_gt']     = 'Nedladdade NZB-filer';
$LN['stats_wv']     = 'Web-visningar';
$LN['stats_ps']     = 'Postningar';
$LN['stats_total']  = 'Total storlek';
$LN['stats_number'] = 'R&auml;knare';
$LN['stats_user']   = 'Anv&auml;ndare';
$LN['stats_overview']   = '&Ouml;verblick';

$LN['stats_spotsbymonth']   = 'Spots per month';
$LN['stats_spotsbyweek']    = 'Spots per week';
$LN['stats_spotsbyhour']    = 'Spots per hour';
$LN['stats_spotsbydow']     = 'Spots per day of the week';

$LN['feeds_title']  = 'RSS-kanaler';
$LN['feeds_rss']    = 'RSS-kanaler';
$LN['feeds_auth']   = 'Inloggning';
$LN['feeds_tooltip_active'] = 'RSS-kanal &auml;r aktiv';
$LN['feeds_tooltip_name']   = 'Namn p&aring; RSS-kanal';
$LN['feeds_tooltip_posts']  = 'Antal l&auml;nkar i RSS-kanalen';
$LN['feeds_tooltip_lastupdated']= 'Uppdaterad senast';
$LN['feeds_tooltip_expire'] = 'Tid i dagar innan objekt i RSS-kanalen anses f&ouml;r&aring;ldrade';
$LN['feeds_tooltip_visible']    = 'RSS-kanal &auml;r synlig';
$LN['feeds_tooltip_auth']   = 'RSS-kanalens server kr&auml;ver autentisering';
$LN['feeds_lastupdated']    = 'Senast uppdaterad';
$LN['feeds_expire_time']    = 'Max &aring;lder';
$LN['feeds_visible']        = 'Synlig';
$LN['feeds_tooltip_autoupdate'] = 'Uppdatera automatiskt';
$LN['feeds_autoupdate']     = 'Uppdatera automatiskt';
$LN['feeds_searchtext']     = 'S&ouml;k i alla tillg&auml;ngliga RSS-kanaler';
$LN['feeds_url']            = 'URL';
$LN['feeds_tooltip_url']    = 'URL';
$LN['feeds_tooltip_uepev']  = 'Editera/Uppdatera/Sl&auml;ng f&ouml;r&aring;ldrat/T&ouml;m/Radera';
$LN['feeds_edit']           = 'Editera';
$LN['feeds_addfeed']        = 'L&auml;gg till en ny RSS-kanal';
$LN['feeds_editfeed']       = 'Modifera RSS-kanal';
$LN['feeds_allgroups']      = 'Alla RSS-kanaler';
$LN['feeds_hide_empty']     = 'G&ouml;m inaktiva RSS-kanaler';
$LN['menurssfeeds']         = 'RSS-kanaler';
$LN['menuspots']            = 'Spots';
$LN['menu_overview']        = 'Inst&auml;llningar';
$LN['menursssets']          = 'RSS-set';
$LN['menugroupsets']        = 'Grupp-set';

$LN['error_invalidfeedid']  = 'Ogiltigt RSS-ID';
$LN['error_feednotfound']   = 'RSS-kanalen hittades inte';
$LN['error_nosetsfound']    = 'Inga set funna';
$LN['error_nousersfound']   = 'Inga anv&auml;ndare funna';

$LN['config_formatstrings'] = 'Formatstr&auml;ngar';
$LN['config_formatstring']  = 'Formatstr&auml;ng f&ouml;r';

$LN['newcategory']          = 'Ny kategori';
$LN['nocategory']           = 'Ingen kategori';
$LN['category']             = 'Kategori';
$LN['categories']           = 'kategorier';
$LN['name']                 = 'Namn';
$LN['editcategories']       = 'Editera kategorier';
$LN['ng_tooltip_category']  = 'Kategori';

$LN['post_message']         = 'Posta ett meddelande';
$LN['post_messagetext']     = 'Meddelandets text';
$LN['post_messagetextext']  = 'Inneh&aring;llet i meddelandet som ska postas';
$LN['post_newsgroupext2']   = 'Usenet-gruppen som meddelandet ska postas i';
$LN['post_subjectext2']     = '&Auml;mnesraden i meddelandet';

$LN['settype'][urd_extsetinfo::SETTYPE_UNKNOWN]     = $LN['config_formatstring'] . ' Ok&auml;nd';
$LN['settype'][urd_extsetinfo::SETTYPE_MOVIE]       = $LN['config_formatstring'] . ' Film';
$LN['settype'][urd_extsetinfo::SETTYPE_ALBUM]       = $LN['config_formatstring'] . ' Album';
$LN['settype'][urd_extsetinfo::SETTYPE_IMAGE]       = $LN['config_formatstring'] . ' Bild';
$LN['settype'][urd_extsetinfo::SETTYPE_SOFTWARE]    = $LN['config_formatstring'] . ' Mjukvara';
$LN['settype'][urd_extsetinfo::SETTYPE_TVSERIES]    = $LN['config_formatstring'] . ' TV-serie';
$LN['settype'][urd_extsetinfo::SETTYPE_EBOOK]       = $LN['config_formatstring'] . ' Ebook';
$LN['settype'][urd_extsetinfo::SETTYPE_GAME]        = $LN['config_formatstring'] . ' Spel';
$LN['settype'][urd_extsetinfo::SETTYPE_TVSHOW]      = $LN['config_formatstring'] . ' TV-Show';
$LN['settype'][urd_extsetinfo::SETTYPE_DOCUMENTARY] = $LN['config_formatstring'] . ' Dokument&auml;r';
$LN['settype'][urd_extsetinfo::SETTYPE_OTHER]       = $LN['config_formatstring'] . ' &Ouml;vrigt';

$LN['settype_syntax'] = '%(n.mc); d&auml;r <i>()</i> &auml;r en frivillig inkapsling, kan vara (), [] eller {}; <i>n</i> ett frivilligt utfyllnadsv&auml;rde, <i>.m</i> ett frivilligt v&auml;rde som anger maximal l&auml;ngd, <i>c</i> ett n&ouml;dv&auml;ndigt tecken ur listan nedan (anv&auml;nd %% f&ouml;r att visa ett %, se &auml;ven PHP-dokumentationen om sprintf):<br/><br/>';

$LN['settype_msg'][urd_extsetinfo::SETTYPE_UNKNOWN] = $LN['settype_syntax'] . 'Set av typen Ok&auml;nd: <br/>%n: namn<br/>%t: set-typ<br/>%T: typ-specifik ikon<br/>%P: l&ouml;senords-skyddad<br/>%C: upphovsr&auml;tts-skyddat material';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_MOVIE] = $LN['settype_syntax'] . 'Set av typen Film: <br/>%n: namn<br/>%t: set-typ<br/>%T: typ-specifik ikon<br/>%y: &aring;r<br/>%m: film-format<br/>%a: ljud-format<br/>%l: spr&aring;k<br/>%s: spr&aring;k undertext<br/>%x: bfbj.<br/>%N: noteringar<br/>%q: kvalitet<br/>%P: l&ouml;senords-skyddad<br/>%C: upphovsr&auml;tts-skyddat material <br/>';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_ALBUM] = $LN['settype_syntax'] . 'Set av typen Album: <br/>%n: namn<br/>%t: set-typ<br/>%T: typ-specifik ikon<br/>%y: &aring;r <br/>%f: format<br/>%g: genre<br/>%N: noteringar<br/>%q: kvalitet<br/>%P: l&ouml;senords-skyddad<br/>%C: upphovsr&auml;tts-skyddat material <br/>';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_IMAGE] = $LN['settype_syntax'] . 'Set av typen Bild: <br/>%n: namn<br/>%t: set-typ<br/>%T: typ-specifik ikon <br/>%f: format<br/>%g: genre<br/>%N: noteringar<br/>%q: kvalitet<br/>%x: bfbj.<br/>%P: l&ouml;senords-skyddad<br/>%C: upphovsr&auml;tts-skyddat material';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_SOFTWARE] = $LN['settype_syntax'] . 'Set av typen Mjukvara: <br/>%n: namn<br/>%t: set-typ<br/>%T: typ-specifik ikon<br/>%o: operativ-system <br/>%q: kvalitet<br/>%N: noteringar<br/>%P: l&ouml;senords-skyddad<br/>%C: upphovsr&auml;tts-skyddat material';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_TVSERIES] = $LN['settype_syntax'] .  'Set av typen TV-serie: <br/>%n: namn<br/>%t: set-typ<br/>%T: typ-specifik ikon<br/>%e: avsnitt<br/>%m: film-format<br/>%a: ljud-format<br/>%x: bfbj.<br/>%q: kvalitet<br/>%N: noteringar<br/>%P: l&ouml;senords-skyddad<br/>%C: upphovsr&auml;tts-skyddat material';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_EBOOK] = $LN['settype_syntax'] . 'Set av typen Ebook: <br/>%n: namn<br/>%t: set-typ<br/>%T: typ-specifik ikon<br/>%A: utgivare<br/>%y: &aring;r<br/>%f: format<br/>%q: kvalitet<br/>%g: genre<br/>%N: noteringar<br/>%P: l&ouml;senords-skyddad<br/>%C: upphovsr&auml;tts-skyddat material';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_GAME] = $LN['settype_syntax'] . 'Set av typen Spel: <br/>%n: namn<br/>%t: set-typ<br/>%T: typ-specifik ikon<br/>%A: utgivare<br/>%y: &aring;r<br/>%f: format<br/>%q: kvalitet<br/>%g: genre<br/>%N: noteringar<br/>%P: l&ouml;senords-skyddad<br/>%C: upphovsr&auml;tts-skyddat material';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_TVSHOW] =$LN['settype_syntax'] . 'Set av typen TV-Show: <br/>%n: namn<br/>%t: set-typ<br/>%T: typ-specifik ikon<br/>%m: film-format<br/>%y: &aring;r<br/>%e: avsnitt<br/>%f: format<br/>%q: kvalitet<br/>%g: genre<br/>%N: noteringar<br/>%P: l&ouml;senords-skyddad<br/>%C: upphovsr&auml;tts-skyddat material';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_DOCUMENTARY] = $LN['settype_syntax'] . 'Set av typen Dokument&auml;r: <br/>%n: namn<br/>%t: set-typ<br/>%T: typ-specifik ikon<br/>%A: utgivare<br/>%y: &aring;r<br/>%f: format<br/>%q: kvalitet<br/>%g: genre<br/>%N: noteringar<br/>%P: l&ouml;senords-skyddad<br/>%C: upphovsr&auml;tts-skyddat material';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_OTHER] = $LN['settype_syntax'] . 'Set av typen &Ouml;vrigt: <br/>%n: namn<br/>%t: set-typ<br/>%T: typ-specifik ikon<br/><br/>%N: noteringar<br/>%P: l&ouml;senords-skyddad<br/>%C: upphovsr&auml;tts-skyddat material';

$LN['loading_files']        = 'Laddar filer... v&auml;nligen v&auml;nta';
$LN['loading']              = 'Laddar... v&auml;nligen v&auml;nta';

$LN['spots_allcategories']      = 'Alla kategorier';
$LN['spots_allsubcategories']   = 'Alla subkategorier';
$LN['spots_subcategories']      = 'Subkategorier';
$LN['spots_tag']                = 'Tag';
$LN['pref_spots_category_mapping']      = 'Spots category mapping for';
$LN['pref_spots_category_mapping_msg']  = 'Spots category mapping to URD categories';

$LN['pref_custom_values']       = 'Custom values';
$LN['pref_custom']              = 'Custom value';
$LN['config_custom']            = 'Custom value';
$LN['pref_custom_msg']          = 'Custom values that can be used in scripts';
$LN['spots_other']       = 'Annat';
$LN['spots_all']         = 'Allt';

$LN['spots_image']       = 'Bild';
$LN['spots_sound']       = 'Ljud';
$LN['spots_game']        = 'Spel';
$LN['spots_application'] = 'Program';
$LN['spots_format']      = 'Format';
$LN['spots_source']      = 'Source';
$LN['spots_language']    = 'Spr&aring;k';
$LN['spots_genre']       = 'Genre';
$LN['spots_bitrate']     = 'Bitrate';
$LN['spots_platform']    = 'Plattform';
$LN['spots_type']        = 'Typ';

$LN['spots_album']        = 'Album';
$LN['spots_liveset']      = 'Live set';
$LN['spots_podcast']      = 'Podcast';
$LN['spots_audiobook']    = 'Audiobook';
$LN['spots_film']         = 'Film';
$LN['spots_series']       = 'Serier';
$LN['spots_book']         = 'Bok';
$LN['spots_erotica']      = 'Erotik';
$LN['spots_divx']         = 'DivX';
$LN['spots_wmv']          = 'WMV';
$LN['spots_mpg']          = 'MPG';
$LN['spots_dvd5']      = 'DVD5';
$LN['spots_hdother']   = 'HD annat';
$LN['spots_ebook']     = 'E-book';
$LN['spots_bluray']    = 'Blu-ray';
$LN['spots_hddvd']     = 'HD DVD';
$LN['spots_wmvhd']     = 'WMVHD';
$LN['spots_x264hd']    = 'x264HD';
$LN['spots_dvd9']      = 'DVD9';
$LN['spots_cam']       = 'Cam';
$LN['spots_svcd']      = '(S)VCD';
$LN['spots_promo']     = 'Promo';
$LN['spots_retail']    = 'Retail';
$LN['spots_tv']        = 'TV';
$LN['spots_satellite'] = 'Satelliet';
$LN['spots_r5']        = 'R5';
$LN['spots_telecine']  = 'Telecine';
$LN['spots_telesync']  = 'Telesync';
$LN['spots_scan']      = 'Scan';

$LN['spots_subs_non']     = 'Inga undertexter';
$LN['spots_subs_nl_ext']  = 'Holl&auml;ndska undertexter (extern)';
$LN['spots_subs_nl_incl'] = 'Holl&auml;ndska undertexter (h&aring;rdkodat)';
$LN['spots_subs_eng_ext'] = 'Engelska undertexter (extern)';
$LN['spots_subs_eng_incl']= 'Engelska undertexter (h&aring;rdkodat)';
$LN['spots_subs_nl_opt']  = 'Holl&auml;ndska undertexter (fakultativ)';
$LN['spots_subs_eng_opt'] = 'Engelska undertexter (optional)';
$LN['spots_false']        = 'False';
$LN['spots_lang_eng']     = 'English speech';
$LN['spots_lang_nl']      = 'Dutch speech';
$LN['spots_lang_ger']     = 'German speech';
$LN['spots_lang_fr']      = 'French speech';
$LN['spots_lang_es']      = 'Spanish speech';
$LN['spots_lang_asian']   = 'Asian speech';

$LN['spots_action']      = 'Action';
$LN['spots_adventure']   = '&Auml;ventyrs';
$LN['spots_animation']   = 'Tecknad';
$LN['spots_cabaret']     = 'Cabaret';
$LN['spots_comedy']      = 'Komedi';
$LN['spots_crime']       = 'Crime';
$LN['spots_documentary'] = 'dokument&auml;r';
$LN['spots_drama']       = 'Drama';
$LN['spots_family']      = 'Familje';
$LN['spots_fantasy']     = 'Fantasy';
$LN['spots_filmnoir']    = 'Film Noir';
$LN['spots_tvseries']    = 'TV Serie';
$LN['spots_horror']      = 'Skr&auml;ck';
$LN['spots_music']       = 'Music';
$LN['spots_musical']     = 'Musical';
$LN['spots_mystery']     = 'Mystery';
$LN['spots_romance']     = 'Romantik';
$LN['spots_scifi']       = 'Science fiction';
$LN['spots_sport']       = 'Sport';
$LN['spots_short']       = 'Kortfilm';
$LN['spots_thriller']    = 'Thriller';
$LN['spots_war']         = 'Kriget';
$LN['spots_western']     = 'Western';
$LN['spots_ero_hetero']  = 'Erotik (hetero)';
$LN['spots_ero_gaymen']  = 'Erotik (homosexuell)';
$LN['spots_ero_lesbian'] = 'Erotik (lesbiskt)';
$LN['spots_ero_bi']      = 'Erotik (bisexuellt)';
$LN['spots_asian']       = 'Asian';
$LN['spots_anime']       = 'Anime';
$LN['spots_cover']       = 'Omslag';
$LN['spots_comics']      = 'Comics';
$LN['spots_cartoons']    = 'Tecknat';
$LN['spots_children']    = 'Barn';

$LN['spots_mp3']         = 'MP3';
$LN['spots_wma']         = 'WMA';
$LN['spots_wav']         = 'WAV';
$LN['spots_ogg']         = 'OGG';
$LN['spots_eac']         = 'EAC';
$LN['spots_dts']         = 'DTS';
$LN['spots_aac']         = 'AAC';
$LN['spots_ape']         = 'APE';
$LN['spots_flac']        = 'FLAC';
$LN['spots_cd']          = 'CD';
$LN['spots_radio']       = 'Radio';
$LN['spots_compilation'] = 'Compilation';
$LN['spots_dvd']         = 'DVD';
$LN['spots_vinyl']       = 'Vinyl';
$LN['spots_stream']      = 'Stream';
$LN['spots_variable']    = 'Variable';
$LN['spots_96kbit']      = '96 kbit';
$LN['spots_lt96kbit']    = '&lt;96 kbit';
$LN['spots_128kbit']     = '128 kbit';
$LN['spots_160kbit']     = '160 kbit';
$LN['spots_192kbit']     = '192 kbit';
$LN['spots_256kbit']     = '256 kbit';
$LN['spots_320kbit']     = '320 kbit';
$LN['spots_lossless']    = 'Lossless';

$LN['spots_blues']        = 'Blues';
$LN['spots_compilation']  = 'Compilation';
$LN['spots_cabaret']      = 'Cabaret';
$LN['spots_dance']        = 'Dance';
$LN['spots_various']      = 'Various';
$LN['spots_hardcore']     = 'Hardcore';
$LN['spots_international']= 'International';
$LN['spots_jazz']         = 'Jazz';
$LN['spots_children']     = 'Ungdom';
$LN['spots_classical']    = 'Classical';
$LN['spots_smallarts']    = 'Small arts';
$LN['spots_netherlands']  = 'Dutch';
$LN['spots_newage']       = 'New Age';
$LN['spots_pop']          = 'Pop';
$LN['spots_soul']         = 'R&amp;B';
$LN['spots_hiphop']       = 'Hiphop';
$LN['spots_reggae']       = 'Reggae';
$LN['spots_religious']    = 'Religious';
$LN['spots_rock']         = 'Rock';
$LN['spots_soundtracks']  = 'Soundtrack';
$LN['spots_hardstyle']    = 'Hardstyle';
$LN['spots_asian']        = 'Asian';
$LN['spots_disco']        = 'Disco';
$LN['spots_oldschool']    = 'Old school';
$LN['spots_metal']        = 'Metal';
$LN['spots_country']      = 'Country';
$LN['spots_dubstep']      = 'Dubstep';
$LN['spots_nederhop']     = 'Nederhop';
$LN['spots_dnb']          = 'DnB';
$LN['spots_electro']      = 'Electro';
$LN['spots_folk']       = 'Folk';
$LN['spots_soul']       = 'Soul';
$LN['spots_trance']     = 'Trance';
$LN['spots_balkan']     = 'Balkan';
$LN['spots_techno']     = 'Techno';
$LN['spots_ambient']    = 'Ambient';
$LN['spots_latin']      = 'Latin';
$LN['spots_live']       = 'Live';

$LN['spots_windows']      = 'Windows';
$LN['spots_mac']          = 'Macintosh';
$LN['spots_linux']        = 'Linux';
$LN['spots_navigation']   = 'Navigation';
$LN['spots_os2']          = 'OS/2';
$LN['spots_playstation']  = 'Playstation';
$LN['spots_playstation2'] = 'Playstation 2';
$LN['spots_playstation3'] = 'Playstation 3';
$LN['spots_psp']          = 'PSP';
$LN['spots_xbox']         = 'Xbox';
$LN['spots_xbox360']      = 'Xbox 360';
$LN['spots_gameboy']      = 'Gameboy';
$LN['spots_gamecube']     = 'Gamecube';
$LN['spots_nintendods']   = 'Nintendo DS';
$LN['spots_nintendo3ds']  = 'Nintendo 3DS';
$LN['spots_nintendowii']  = 'Nintendo Wii';
$LN['spots_windowsphone'] = 'Windows Phone';
$LN['spots_ios']          = 'iOS';
$LN['spots_android']      = 'Android';

$LN['spots_rip']          = 'Rip';
$LN['spots_retail']       = 'Retail';
$LN['spots_addon']        = 'Add-on';
$LN['spots_patch']        = 'Patch';
$LN['spots_crack']        = 'Crack';
$LN['spots_iso']          = 'ISO';
$LN['spots_action']       = 'Action';
$LN['spots_adventure']    = '&Auml;ventyr';
$LN['spots_strategy']     = 'Strategi';
$LN['spots_roleplay']     = 'Rollspel';
$LN['spots_simulation']   = 'Simulering';
$LN['spots_race']         = 'Race';
$LN['spots_flying']       = 'Flyg';
$LN['spots_shooter']      = 'First Person Shooter';
$LN['spots_platform']     = 'Plattform';
$LN['spots_sport']        = 'Sports';
$LN['spots_children']     = 'Barn / ungdom';
$LN['spots_puzzle']       = 'Puzzle';
$LN['spots_boardgame']    = 'Br&auml;dspel';
$LN['spots_cards']        = 'Kort';
$LN['spots_education']    = 'Education';
$LN['spots_music']        = 'Musik';
$LN['spots_family']       = 'Familj';

$LN['spots_audioedit']    = 'Sound editing';
$LN['spots_videoedit']    = 'Video editing';
$LN['spots_graphics']     = 'Graphical design';
$LN['spots_cdtools']      = 'CD-verktyg';
$LN['spots_mediaplayers'] = 'Mediaspelare';
$LN['spots_rippers']      = 'Rippers och encoders';
$LN['spots_plugins']      = 'Plugins';
$LN['spots_database']     = 'Databaser';
$LN['spots_email']        = 'Epost-program';
$LN['spots_photo']        = 'Foto-editorer';
$LN['spots_screensavers'] = 'Sk&auml;rmsl&auml;ckare';
$LN['spots_skins']        = 'Skins software';
$LN['spots_drivers']      = 'Drivrutiner';
$LN['spots_browsers']     = 'Browsers';
$LN['spots_downloaders']  = 'Download managers';
$LN['spots_filesharing']  = 'Filesharing software';
$LN['spots_usenet']       = 'Usenet-program';
$LN['spots_rss']          = 'RSS-program';
$LN['spots_ftp']          = 'FTP-program';
$LN['spots_firewalls']    = 'Firewalls';
$LN['spots_antivirus']    = 'Anti-virus';
$LN['spots_antispyware']  = 'Anti-spyware';
$LN['spots_optimisation'] = 'Optimerings-program';
$LN['spots_security']     = 'S&auml;kerhets-program';
$LN['spots_system']       = 'System-program';
$LN['spots_educational']  = 'Utbildning';
$LN['spots_office']       = 'Kontor';
$LN['spots_internet']           = 'Internet';
$LN['spots_communication']      = 'Kommunikation';
$LN['spots_development']        = 'Utveckling';
$LN['spots_spotnet']            = 'Spotnet';
$LN['spots_']                   = '';

$LN['spots_daily'] = 'Dagstidning';
$LN['spots_magazine'] = 'Tidskrifter';
$LN['spots_comic'] = 'Serier';
$LN['spots_study']  = 'Studier';
$LN['spots_business'] = 'Aff&auml;rer';
$LN['spots_economy'] = 'Ekonomi';
$LN['spots_computer'] = 'Datorer';
$LN['spots_hobby'] = 'Hobby';
$LN['spots_cooking'] = 'Matlagning';
$LN['spots_crafts'] = 'Hantverk'; // whatsthis? handy craft?
$LN['spots_needlework'] = 'S&ouml;mnad';
$LN['spots_health'] = 'H&auml;lsa';
$LN['spots_history'] = 'Historia';
$LN['spots_psychology'] = 'Psykologi';
$LN['spots_science'] = 'Vetenskap';
$LN['spots_woman'] = 'Kvinna';
$LN['spots_religion'] = 'Religion';
$LN['spots_novel'] = 'Roman';
$LN['spots_biography'] = 'Biografi';
$LN['spots_detective'] = 'D&auml;ckare';
$LN['spots_animals'] = 'Djur';
$LN['spots_humour'] = 'Humor';
$LN['spots_travel'] = 'Resor';
$LN['spots_truestory'] = 'Sann historia';
$LN['spots_nonfiction'] = 'Non fiction';
$LN['spots_politics'] = 'Politik';
$LN['spots_poetry'] = 'Poesi';
$LN['spots_fairytale'] = 'Sagor';
$LN['spots_technical'] = 'Teknisk';
$LN['spots_art'] = 'Konst';
$LN['spots_bi'] = 'Erotik: Bisexuellt';
$LN['spots_lesbo'] = 'Erotik: Lesbiskt';
$LN['spots_homo'] = 'Erotik: Gay';
$LN['spots_hetero'] = 'Erotik: Straight';
$LN['spots_amateur'] = 'Erotik: Amature';
$LN['spots_groep'] = 'Erotik: Grupp';
$LN['spots_pov'] = 'Erotik: POV';
$LN['spots_solo'] = 'Erotik: Solo';
$LN['spots_teen'] = 'Erotik: Teens';
$LN['spots_soft'] = 'Erotik: Soft';
$LN['spots_fetish'] = 'Erotik: Fetisch';
$LN['spots_mature'] = 'Erotik: Mature';
$LN['spots_fat'] = 'Erotik: Fat';
$LN['spots_sm'] = 'Erotik: S and M';
$LN['spots_rough'] = 'Erotik: Rough';
$LN['spots_black'] = 'Erotik: Black';
$LN['spots_hentai'] = 'Erotik: Hentai';
$LN['spots_outside'] = 'Erotik: Outside';

$LN['update_database']      = 'Update database';

$LN['password_weak']        = 'Password strength: weak';
$LN['password_medium']      = 'Password strength: medium';
$LN['password_strong']      = 'Password strength: strong';
$LN['password_correct']     = 'Passwords match';
$LN['password_incorrect']   = 'Passwords do not match';

$LN['dashboard_max_nntp']      = 'Max antal NNTP-anslutningar';
$LN['dashboard_max_threads']   = 'Max antal tr&aring;dar totalt';
$LN['dashboard_max_db_intensive']	    = 'Max antal databas-intensiva tr&aring;dar';


if (isset($smarty)) { // don't do the smarty thing if we read it from urdd
    foreach ($LN as $key => $word) {
        $LN2['LN_' . $key] = $word;
    }
    $smarty->assign($LN2);
    unset($LN2);
}
