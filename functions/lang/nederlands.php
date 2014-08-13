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
*/

/* Dutch language file for URD  */

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
$LN['cancel']           = 'Annuleer';
$LN['pause']            = 'Pauzeer';
$LN['continue']         = 'Doorgaan';

$LN['details']          = 'Details';
$LN['error']            = 'Fout';
$LN['atonce']           = 'Meteen';
$LN['browse']           = 'Bladeren';
// Special:
$LN['urdname']          = 'URD';
$LN['decimalseparator'] = ',';
$LN['dateformat']       = 'd-m-Y';
$LN['dateformat2']      = 'd M Y';
$LN['dateformat3']      = 'd M';
$LN['timeformat']       = 'H:i:s';
$LN['timeformat2']      = 'H:i';

$LN['time']             = 'Tijd';

$LN['add_search']       = 'Zoekopdracht opslaan';
$LN['delete_search']    = 'Zoekopdracht verwijderen';
$LN['save_search_as']   = 'Zoekopdracht opslaan als';

$LN['expire']           = 'Wissen na';
$LN['gensets']          = 'Genereer set info';
$LN['update']           = 'Updaten';
$LN['purge']            = 'Legen';

// This 'overwrites' the define values:
$LN['periods'][0]       = 'Geen update';
$LN['periods'][11]      = 'Elk uur';
$LN['periods'][12]      = 'Elke 3 uur';
$LN['periods'][1]       = 'Elke 6 uur';
$LN['periods'][13]      = 'Elke 12 uur';
$LN['periods'][2]       = 'Elke dag';
$LN['periods'][3]       = 'Elke maandag';
$LN['periods'][4]       = 'Elke dinsdag';
$LN['periods'][5]       = 'Elke woensdag';
$LN['periods'][6]       = 'Elke donderdag';
$LN['periods'][7]       = 'Elke vrijdag';
$LN['periods'][8]       = 'Elke zaterdag';
$LN['periods'][9]       = 'Elke zondag';
$LN['periods'][10]      = 'Elke 4 weken';

$LN['month_names'][1]       = 'Januari';
$LN['month_names'][2]       = 'Februari';
$LN['month_names'][3]       = 'Maart';
$LN['month_names'][4]       = 'April';
$LN['month_names'][5]       = 'Mei';
$LN['month_names'][6]       = 'Juni';
$LN['month_names'][7]       = 'Juli';
$LN['month_names'][8]       = 'Augustus';
$LN['month_names'][9]       = 'September';
$LN['month_names'][10]      = 'Oktober';
$LN['month_names'][11]      = 'November';
$LN['month_names'][12]      = 'December';

$LN['short_month_names'][1]   = 'Jan';
$LN['short_month_names'][2]   = 'Feb';
$LN['short_month_names'][3]   = 'Mrt';
$LN['short_month_names'][4]   = 'Apr';
$LN['short_month_names'][5]   = 'Mei';
$LN['short_month_names'][6]   = 'Jun';
$LN['short_month_names'][7]   = 'Jul';
$LN['short_month_names'][8]   = 'Aug';
$LN['short_month_names'][9]   = 'Sep';
$LN['short_month_names'][10]  = 'Okt';
$LN['short_month_names'][11]  = 'Nov';
$LN['short_month_names'][12]  = 'Dec';

$LN['short_day_names'][1]	= 'Zo';
$LN['short_day_names'][2]	= 'Ma';
$LN['short_day_names'][3]	= 'Di';
$LN['short_day_names'][4]	= 'Wo';
$LN['short_day_names'][5]	= 'Do';
$LN['short_day_names'][6]	= 'Vr';
$LN['short_day_names'][7]	= 'Za';

$LN['autoconfig']   = 'Autoconfigureer';
$LN['autoconfig_ext']   = 'Autoconfigureer (uitgebreid)';
$LN['extended']     = 'extended';
$LN['since']        = 'sinds';
$LN['select']       = 'Selecteer een';
$LN['disabled']     = 'uitgeschakeld';
$LN['unknown']      = 'Onbekend';
$LN['help']         = 'Help';
$LN['saved']        = 'Opgeslagen';
$LN['deleted']      = 'Verwijderd';
$LN['sets']         = 'sets';
$LN['reload']       = 'herladen';
$LN['CAPTCHA1']     = 'Captcha';
$LN['CAPTCHA2']     = '3 zwarte tekens';
$LN['active']       = 'Actief';
$LN['whitelisttag'] = 'W';
$LN['blacklisttag']     = 'Z';
$LN['spamreporttag']    = 'S';

$LN['id']                   = 'ID';
$LN['pid']                  = 'PID';
$LN['server']               = 'Server';
$LN['start_time']           = 'Start tijd';
$LN['queue_time']           = 'Queue tijd';
$LN['recurrence']           = 'Herhaling';
$LN['enabled']              = 'Aangezet';
$LN['free_threads']         = 'Beschikbare draadjes';
$LN['total_free_threads']   = 'Totaal beschikbare draadjes';
$LN['free_db_intensive_threads']   = 'Beschikbare database intensieve draadjes';
$LN['free_nntp_threads']    = 'Beschikbare NNTP draadjes';

$LN['autoconfig_msg']       = 'Autoconfiguratie: URDD probeert alle servers in de lijst op de standaard usenet poorten (119 en 563), met of zonder SSL/TLS. Als het een vindt, zet URDD deze aan; een updating server wordt geselectered als de server indexering toestaat';
$LN['autoconfig_ext_msg']   = 'Autoconfiguratie: URDD probeert alle servers in de lijst op de standaard usenet poorten (119 en 563) en een aantal andere poorten die door usenet providers worden gebruikt (o.a. 23, 80, 443, 8080), met of zonder ssl/tls. Als het een vindt, zet URDD deze aan; een updating server wordt geselectered als de server indexering toestaat';

$LN['next']     = 'Volgende';
$LN['previous'] = 'Vorige';

$LN['off']      = 'Uit';
$LN['on']       = 'Aan';
$LN['all']      = 'Alle';
$LN['other']    = 'Anders';
$LN['total']    = 'Totaal';

$LN['from']     = 'uit';
$LN['preview']  = 'Preview';
$LN['temporary']= 'Tijdelijke bestanden';
$LN['never']    = 'Nooit';
$LN['expand']   = 'uitvouwen';

$LN['advanced_search']      = 'Geavanceerd zoeken';
// Time:
$LN['year']         = 'Jaar';
$LN['month']        = 'Maand';
$LN['week']         = 'Week';
$LN['day']          = 'Dag';
$LN['hour']         = 'Uur';
$LN['minute']       = 'Minuut';
$LN['second']       = 'Seconde';

$LN['years']        = 'jaren';
$LN['months']       = 'maanden';
$LN['weeks']        = 'weken';
$LN['days']         = 'dagen';
$LN['hours']        = 'uren';
$LN['minutes']      = 'minuten';
$LN['seconds']      = 'seconden';

$LN['year_short']   = 'J';
$LN['month_short']  = 'M';
$LN['week_short']   = 'w';
$LN['day_short']    = 'd';
$LN['hour_short']   = 'u';
$LN['minute_short'] = 'm';
$LN['second_short'] = 's';

// Menu:
$LN['menudownloads']    = 'Downloads';
$LN['menuuploads']      = 'Uploads';
$LN['menutransfers']    = 'Overdrachten';
$LN['menubrowsesets']   = 'Aanbod';
$LN['menugroupsearch']  = 'Sets&nbsp;zoeken&nbsp;in&nbsp;groepen';
$LN['menuspotssearch']  = 'Spots zoeken';
$LN['menusearch']       = 'Zoeken';
$LN['menursssearch']    = 'Sets&nbsp;zoeken&nbsp;in&nbsp;rss-bronnen';
$LN['menunewsgroups']   = 'Nieuwsgroepen';
$LN['menuviewfiles']    = 'Bestanden';
$LN['menuviewfiles_downloads']  = 'Downloads';
$LN['menuviewfiles_previews']   = 'Preview&nbsp;bestanden';
$LN['menuviewfiles_nzbfiles']   = 'NZB&nbsp;bestanden';
$LN['menuviewfiles_scripts']    = 'Scripts';
$LN['menuviewfiles_posts']      = 'Uploads';
$LN['menupreferences']  = 'Voorkeuren';
$LN['menuadmin']        = 'Beheer';
$LN['menuabout']        = 'Over';
$LN['menumanual']       = 'Handleiding';
$LN['menusettings']     = 'Instellingen';
$LN['menuadminconfig']  = 'Configuratie';
$LN['menuadmincontrol'] = 'Dashbord';
$LN['menuadminusenet']  = 'Usenet servers';
$LN['menuadminlog']     = 'Logs';
$LN['menuadminjobs']    = 'Agenda';
$LN['menuadmintasks']   = 'Taken';
$LN['menuadminusers']   = 'Gebruikers';
$LN['menuadminbuttons'] = 'Zoekopties';
$LN['menuhelp']         = 'Help';
$LN['menufaq']          = 'FAQ';
$LN['menulogout']       = 'Log&nbsp;uit&nbsp;';
$LN['menulogin']        = 'Log&nbsp;in';
$LN['menudebug']        = 'Debug';
$LN['menulicence']      = 'Licentie';
$LN['menustats']        = 'Statistieken';
$LN['menuforum']        = 'Forum';
$LN['menuuserlists']    = 'Spotter lists';

// Stati:
$LN['statusidling']         = 'Niksen';
$LN['statusrunningtasks']   = 'Active taken';

$LN['enableurddfirst']  = 'Zet URDD aan om deze settings te wijzigen';
// Version:
$LN['version']          = 'Versie';
$LN['enableurdd']       = 'Zet URDD aan';
$LN['disableurdd']      = 'Zet URDD uit';
$LN['urddenabled']      = 'URDD is online';
$LN['urddstarting']     = 'URDD start op';
$LN['urdddisabled']     = 'URDD is uitgeschakeld';
$LN['versionuptodate']  = 'URD is up-to-date.';
$LN['versionoutdated']  = 'URD is verouderd';
$LN['newversionavailable']  = 'Een nieuwe versie is beschikbaar';
$LN['bugfixedversion']      = 'De nieuwe versie lost enkele problemen op.';
$LN['newfeatureversion']    = 'De nieuwe versie heeft nieuwe mogelijkheden.';
$LN['otherversion']         = 'De nieuwe versie heeft overige verbeteringen.';
$LN['securityfixavailable'] = 'De nieuwe versie heeft belangrijke verbeteringen t.a.v. beveiliging.';
$LN['status']               = 'Status';
$LN['activity']             = 'Activiteit';

// Tasks:
$LN['taskupdate']       = 'Updaten';
$LN['taskpost']         = 'Posten';
$LN['taskpurge']        = 'Legen';
$LN['taskexpire']       = 'Opschonen';
$LN['taskadddata']      = 'Downloaddata toevoegen';
$LN['taskdownload']     = 'Downloaden';
$LN['taskcontinue']     = 'Doorgaan';
$LN['taskpause']        = 'Pauzeer';
$LN['taskunknown']      = $LN['unknown'];
$LN['taskoptimise']     = 'Optimaliseren';
$LN['taskgrouplist']    = 'Downloaden van nieuwsgroepenlijst';
$LN['taskunparunrar']   = 'Uitpakken';
$LN['taskcheckversion'] = 'Versie controleren';
$LN['taskgetsetinfo']   = 'Ophalen set info';
$LN['taskgetblacklist'] = 'Ophalen zwarte lijst';
$LN['taskgetwhitelist'] = 'Ophalen witte lijst';
$LN['tasksendsetinfo']  = 'Versturen set info';
$LN['taskparsenzb']     = 'NZB bestand inlezen';
$LN['taskmakenzb']      = 'NZB bestand maken';
$LN['taskcleandir']     = 'Directories opschonen';
$LN['taskcleandb']      = 'Database opschonen';
$LN['taskgensets']      = 'Sets genereren voor';
$LN['taskmergesets']    = 'Sets samenvoegen';
$LN['taskfindservers']  = 'Server autoconfiguratie';
$LN['taskpostmessage']  = 'Bericht verzenden';
$LN['taskpostspot']     = 'Spot posten';
$LN['taskgetspots']     = 'Spots ophalen';
$LN['taskgetspot_comments']     = 'Spots commentaren ophalen';
$LN['taskgetspot_reports']      = 'Spot spam rapporten ophalen';
$LN['taskgetspot_images']       = 'Spot afbeeldingen ophalen';
$LN['taskexpirespots']  = 'Spots opschonen';
$LN['taskpurgespots']   = 'Spots verwijderen';
$LN['taskgetnfo']       = 'NFO data ophalen';
$LN['taskdeleteset']    = 'Set verwijderen';
$LN['taskset']          = 'Configuratie aanpassen';

$LN['eta']              = 'ETA';
$LN['inuse']            = 'is in gebruik';
$LN['free']             = 'is beschikbaar';

// Generic:
$LN['isavailable']      = 'is beschikbaar';
$LN['apply']            = 'Toepassen';
$LN['website']          = 'Website';
$LN['or']               = 'of';
$LN['submit']           = 'Verstuur';
$LN['add']              = 'Voeg toe';
$LN['clear']            = 'Wis';
$LN['reset']            = 'Herstel';
$LN['search']           = 'Zoek';
$LN['number']           = 'Aantal';
$LN['rename']           = 'Wijzig';
$LN['register']         = 'Registreer';
$LN['delete']           = 'Verwijder';
$LN['delete_all']       = 'Verwijder alle';

// Setinfo:
$LN['bin_unknown']      = $LN['unknown'];
$LN['bin_movie']        = 'Film';
$LN['bin_album']        = 'Album';
$LN['bin_image']        = 'Afbeelding';
$LN['bin_software']     = 'Software';
$LN['bin_tvseries']     = 'TV Serie';
$LN['bin_ebook']        = 'eBook';
$LN['bin_game']         = 'Spel';
$LN['bin_documentary']  = 'Documentaire';
$LN['bin_tvshow']       = 'TV programma';
$LN['bin_other']        = 'Overig';

// View files:
$LN['files']            = 'bestanden';
$LN['viewfilesheading'] = 'U ziet';
$LN['filename']         = 'Bestandsnaam';
$LN['group']            = 'Groep';
$LN['rights']           = 'Rechten';
$LN['size']             = 'Grootte';
$LN['count']            = 'Aantal';
$LN['type']             = 'Type';
$LN['modified']         = 'Gewijzigd';
$LN['owner']            = 'Eigenaar';
$LN['perms']            = 'Rechten';
$LN['edit_file']        = 'Wijzig bestand';
$LN['actions']          = 'Acties';
$LN['uploaded']         = 'Verzonden';
$LN['viewfiles_title']  = 'Bestanden';
$LN['viewfiles_download']   = 'Download gecomprimeerd';
$LN['viewfiles_rename']     = 'Hernoem';
$LN['viewfiles_edit']       = 'Bewerken';
$LN['viewfiles_newfile']    = 'Nieuw bestand';
$LN['viewfiles_savefile']   = 'Bestand opslaan';
$LN['viewfiles_tarnotset']  = 'Tar is niet geconfigueerd. Gecomprimeerd downloaden is niet beschikbaar';
$LN['viewfiles_compressfailed'] = 'Compressie is mislukt';
$LN['viewfiles_uploadnzb']  = 'Download van NZB';

$LN['viewfiles_type_audio'] = 'Geluid';
$LN['viewfiles_type_excel'] = 'Excel';
$LN['viewfiles_type_exe']   = 'Exe';
$LN['viewfiles_type_flash'] = 'Flash';
$LN['viewfiles_type_html']  = 'HTML';
$LN['viewfiles_type_iso']   = 'ISO';
$LN['viewfiles_type_php']   = 'PHP';
$LN['viewfiles_type_source']    = 'Broncode';
$LN['viewfiles_type_picture']   = 'Plaatje';
$LN['viewfiles_type_ppt']       = 'Powerpoint';
$LN['viewfiles_type_script']    = 'Script';
$LN['viewfiles_type_text']  = 'Tekst';
$LN['viewfiles_type_video'] = 'Video';
$LN['viewfiles_type_word']  = 'Word';
$LN['viewfiles_type_zip']   = 'Archief';
$LN['viewfiles_type_stylesheet']= 'Stylesheet';
$LN['viewfiles_type_icon']  = 'Icoon';
$LN['viewfiles_type_db']    = 'DB';
$LN['viewfiles_type_folder']    = 'Map';
$LN['viewfiles_type_file']  = 'Bestand';
$LN['viewfiles_type_pdf']   = 'PDF';
$LN['viewfiles_type_nzb']   = 'NZB';
$LN['viewfiles_type_par2']  = 'Par2';
$LN['viewfiles_type_sfv']   = 'SFV';
$LN['viewfiles_type_playlist']  = 'Speellijst';
$LN['viewfiles_type_urdd_sh']   = 'URD script';
$LN['viewfiles_type_torrent']   = 'Torrent';
$LN['viewfiles_type_ebook']     = 'Eboek';

$LN['user_lists_title']     = 'Spotter lijsten';
$LN['user_blacklist']       = 'Zwarte lijst spotters';
$LN['user_whitelist']       = 'Witte lijst spotters';
$LN['spotter_id']           = 'Spotter ID';
$LN['source_external']      = 'Extern';
$LN['source_user']          = 'Gebruiker';
$LN['global']               = 'Globaal';
$LN['personal']             = 'Persoonlijk';
$LN['active']               = 'Actief';
$LN['disabled']             = 'Uitgeschakeld';
$LN['nonactive']            = 'Inactief';


// About:
$LN['about_title']  = 'Over URD';
$LN['abouttext1']   = 'URD is een web-gebaseerde applicatie voor het downloaden van usenet binaries.  Het is helemaal in PHP geschreven, maar gebruikt enkele externe tools om het CPU intensive werk te doen. Het slaat alle informatie op in een generieke database (zoals MySQL of PostgreSQL. Berichten die bij elkaar horen worden gegroepeerd in sets. Downloaden ervan vereist maar enkele muisklikken en als de download klaar is kunnen de bestanden automatisch worden gecontroleerd en uitgepakt. Downloaden van usenet is nu net zo eenvoudig als p2p software.';

$LN['abouttext2']   = 'Een sterk punt van URD is dat geen externe websites nodig zijn, URD genereert alle informatie die nodig is om te downloaden zelf. Het is ook mogelijk om een NZB file te maken en te importeren.';

$LN['abouttext3']   = 'URD is een backroniem voor Usenet Resource Downloader. De term URD is afgeleid van de Noorse culturen refererend aan de bron van URD, welke een heilige bron is en het water levert voor de wereld boom Yggdrasil. De oud-Engelse term is Wyrd. Conceptueel is de betekenis van URD gelijk aan lot.';

// Nieuwsgroep
$LN['ng_title']             = 'Nieuwsgroepen';
$LN['ng_posts']             = 'Berichten';
$LN['ng_lastupdated']       = 'Laatste update';
$LN['ng_expire_time']       = 'Schoon op';
$LN['ng_autoupdate']        = 'Automatisch updaten';
$LN['ng_searchtext']        = 'Zoek in alle beschikbare nieuwsgroepen';
$LN['ng_newsgroups']        = 'Nieuwsgroepen';
$LN['ng_adult']             = '18+';
$LN['ng_tooltip_adult']     = 'Alleen toegankelijk wanneer de gebruiker als 18+ is aangemeld';

$LN['ng_subscribed']        = 'Geabonneerd';
$LN['ng_tooltip_name']      = 'De naam van de nieuwsgroep';
$LN['ng_tooltip_lastupdated']   = 'Tijd sinds de laatste update';
$LN['ng_tooltip_action']    = 'Updaten/Genereer Sets/Opschonen/Legen';
$LN['ng_tooltip_expire']    = 'Het aantal dagen dat een artikel in de database blijft staan';
$LN['ng_tooltip_time']      = 'Het tijdstip waarop een automatische update wordt uitgevoerd';
$LN['ng_tooltip_autoupdate']    = 'De frequentie waarmee deze groep wordt geupdated';
$LN['ng_tooltip_posts']     = 'Het aantal artikelen in deze groep';
$LN['ng_tooltip_active']    = 'Aangevinkt: Deze nieuwsgroep is geabonneerd';
$LN['ng_visible']           = 'Tonen';
$LN['ng_gensets']           = 'Genereer sets';
$LN['ng_minsetsize']        = 'Min/Max setgrootte';
$LN['ng_admin_minsetsize']  = 'Spam ondergrens';
$LN['ng_admin_maxsetsize']  = 'Set bovengrens';
$LN['ng_tooltip_admin_maxsetsize']    = 'De maximale grootte een mag hebben om aan de database te worden toegevoegd; voeg k, M, G achteraan toe, bijv. 100k of 25G';
$LN['ng_tooltip_admin_minsetsize']    = 'De minimale grootte een set moet hebben om aan de database te worden toegevoegd; voeg k, M, G achteraan toe, bijv. 100k of 25G (spam preventie)';
$LN['ng_tooltip_visible']   = 'Is de nieuwsgroep zichtbaar';
$LN['ng_tooltip_minsetsize']    = 'De minimale en maximale set-grootte om een set in deze nieuwsgroep te tonen';
$LN['ng_hide_empty']        = 'Lege groepen verbergen';
//$LN['ng_tooltip_']        = '';

$LN['failed']               = 'mislukt';
$LN['success']              = 'gestart';
$LN['success2']             = 'gelukt';

$LN['user_settings']        = 'Gebruikers instellingen';
$LN['global_settings']      = 'Globale instellingen';

// preferences
$LN['change_password']      = 'Verander wachtwoord';
$LN['password_changed']     = 'Wachtwoord veranderd';
$LN['delete_account']       = 'Verwijder account';
$LN['delete_account_msg']   = 'Verwijder account';
$LN['account_deleted']      = 'Account verwijderd';
$LN['pref_spot_spam_limit']      = 'Spam rapport limiet';
$LN['pref_spot_spam_limit_msg']  = 'Het aantal spam rapportages waarna de spot niet meer wordt getoond';
$LN['pref_title']           = 'Voorkeuren';
$LN['pref_heading']         = 'Persoonlijke voorkeuren';
$LN['pref_saved']           = 'Voorkeuren opgeslagen';
$LN['pref_language']        = 'Taal';
$LN['pref_template']        = 'Sjabloon';
$LN['pref_stylesheet']      = 'Stylesheet';
$LN['pref_stylesheet_msg']  = 'De stylesheet waarmee URD wordt weergegeven';
$LN['pref_language_msg']    = 'De taal waarin URD wordt weergegeven';
$LN['pref_template_msg']    = 'Het sjabloon waarin URD wordt weergegeven';
$LN['pref_login']           = 'Inlog gegevens';
$LN['pref_index_page_msg']  = 'De standaard pagina die wordt getoond na een login';
$LN['pref_index_page']      = 'De standaard pagina';
$LN['pref_display']         = 'Weergave';
$LN['pref_downloading']     = 'Downloaden';
$LN['pref_spots']           = 'Spots';
$LN['pref_setcompleteness'] = 'Set compleetheid';
$LN['pref_setcompleteness_msg']  = 'Sets die tenminste dit percentage compleet zijn worden getoond';
$LN['pref_skip_int']             = 'Verberg interessante sets niet';
$LN['pref_skip_int_msg']         = 'Verberg interessante sets niet wanneer op de verwijder alle sets knop wordt gedrukt';

$LN['pref_default_group']        = 'Standaard groep';
$LN['pref_default_group_msg']    = 'Standaard groep die wordt geselecteerd in de browse pagina';
$LN['pref_default_feed']         = 'Standaard RSS bron';
$LN['pref_default_feed_msg']     = 'Standaard bron die wordt geselecteerd in de rss sets pagina';
$LN['pref_default_spot']         = 'Standaard spot zoekopdracht';
$LN['pref_default_spot_msg']     = 'Standaard spot zoekopdracht die wordt geselecteerd in de spots pagina';
$LN['pref_poster_email']         = 'Poster e-mail adres';
$LN['poster_name']               = 'Poster naam';
$LN['pref_poster_name']          = 'Poster naam';
$LN['pref_poster_default_text'] = 'Standaard berichtentekst';
$LN['pref_poster_default_text_msg'] = 'Standard berichtentekst die gebruikt wordt voor het posten van spots en comments';
$LN['pref_recovery_size']        = 'Percentage par2 bestanden';
$LN['pref_rarfile_size']         = 'Grootte van de rar bestanden';
$LN['pref_poster_email_msg']     = 'Het e-mail adres dat gebruikt wordt in de te posten berichten';
$LN['pref_poster_name_msg']      = 'De naam die gebruikt wordt in de te posten berichten';
$LN['pref_recovery_size_msg']    = 'Het percentage herstel bestanden (par2) dat wordt gecreeerd (0 voor geen herstelbestanden)';
$LN['pref_rarfile_size_msg']     = 'De grootte van de rar bestanden in kB (0 om niet te rarren)';
$LN['pref_posting']              = 'Posting';
$LN['pref_user_scripts']         = 'Uitvoeren gebruikersscripts';
$LN['pref_user_scripts_msg']     = 'De uit te voeren gebruikersscripts na het completeren van een download (noot: scripts moeten eindigen op .urdd_sh)';
$LN['pref_global_scripts']       = 'Uitvoeren globaal scripts';
$LN['pref_global_scripts_msg']   = 'De uit te voeren globale scripts na het completeren van een download (noot: scripts moeten eindigen op .urdd_sh)';

$LN['pref_level']       = 'Gebruikerervaringsniveau';
$LN['pref_level_msg']   = 'Hoe meer ervaring de gebruiker heeft, hoe meer opties worden getoond in instellingen en configuratie (indien admin)';
$LN['level_basic']      = 'Basis';
$LN['level_advanced']   = 'Geavanceerd';
$LN['level_master']     = 'Grootmeester';

$LN['username']         = 'Gebruikersnaam';
$LN['password']         = 'Wachtwoord';
$LN['newpw']            = 'Nieuw wachtwoord';
$LN['oldpw']            = 'Oud wachtwoord';
$LN['email']            = 'E-mail adres';
$LN['fullname']         = 'Volledige naam';
$LN['pref_maxsetname']       = 'Max. lengte van een set-naam';
$LN['pref_setsperpage']      = 'Max. aantal regels per pagina';
$LN['pref_minsetsize']       = 'Min. set-grootte in MB';
$LN['pref_maxsetsize']       = 'Max. set-grootte in MB';
$LN['setsize']          = 'Set-grootte in MB';
$LN['maxage']           = 'Max. leeftijd in dagen';
$LN['minage']           = 'Min. leeftijd in dagen';
$LN['age']              = 'Leeftijd in dagen';
$LN['rating']           = 'Score';
$LN['maxrating']        = 'Max. score (0-10)';
$LN['minrating']        = 'Min. score (0-10)';
$LN['complete']         = 'Compleet %';
$LN['maxcomplete']      = 'Max. compleet %';
$LN['mincomplete']      = 'Min. compleet %';
$LN['pref_minngsize']        = 'Min. aantal berichten in een nieuwsgroep';
$LN['config_global_hiddenfiles']      = 'Verberg bepaalde bestanden';
$LN['config_global_hidden_files_list']    = 'Lijst van te verbergen bestanden';
$LN['pref_hiddenfiles']      = 'Verberg bepaalde bestanden';
$LN['pref_hidden_files_list']    = 'Lijst van te verbergen bestanden';
$LN['pref_defaultsort']      = 'De standaard volgorde van sets';
$LN['pref_buttons']          = 'Zoekopties';
$LN['pref_unpar']            = 'Automatisch repareren met par2';
$LN['pref_download_par']     = 'Altijd par2 bestanden downloaden';
$LN['pref_download_par_msg'] = 'Indien uitgeschakeld, par2 bestanden worden alleen gedownload indien nodig, anders worden ze altijd gedownload';
$LN['pref_unrar']            = 'Automatisch uitpakken van archieven';
$LN['pref_delete_files']     = 'Originele bestanden verwijderen na uitpakken';
$LN['pref_mail_user']        = 'Verstuur e-mail-berichten';
$LN['pref_show_subcats']     = 'Toon subcats popup voor spots';
$LN['pref_show_subcats_msg'] = 'Toon de bescrhijving van de subcategorieen van een spot in een popup';
$LN['pref_show_image']       = 'Toon afbeeldingen bij spots';
$LN['pref_show_image_msg']   = 'Toon afbeeldingen bij spots in uitgebreide spot informatie';
$LN['pref_search_terms']     = 'Automatisch sets met deze tekst markeren';
$LN['pref_blocked_terms']    = 'Automatisch sets met deze tekst verbergen';
$LN['spam_reports']             = 'Spam rapporten';
$LN['pref_use_auto_download']        = 'Automatisch downloaden';
$LN['pref_use_auto_download_nzb']    = 'Automatisch downloaden als NZB bestand';
$LN['pref_download_text_file']       = 'Download berichten zonder bijlagen';
$LN['pref_download_text_file_msg']   = 'Download berichttekst wanneer geen bijlagen zijn gevonden in het bericht';
$LN['pref_download_delay']           = 'Download vertraging';
$LN['pref_download_delay_msg']       = 'Het aantal minuten dat een download wordt gepauzeeld voordat deze start';

$LN['pref_format_dl_dir']        = 'Download directory formaat';
$LN['pref_format_dl_dir_msg']    = 'Download directory formaat toegevoegd aan de basis download naam:<br/>' .
    '%c: Categorie<br/>' .
    '%D: Datum<br/>' .
    '%d: Dag van de maand<br>' .
    '%F: Maand naam (lang)<br/>' .
    '%g: Groep naam<br/>' .
    '%G: Groep ID<br/>' .
    '%m: Maand (numeriek)<br/>' .
    '%M: Maand naam (kort)<br/>' .
    '%n: Setnaam<br/>' .
    '%s: Downloadnaam<br/>' .
    '%u: Gebruikersnaam<br/>' .
    '%w: Dag van de week<br/>' .
    '%W: Weeknummer<br/>' .
    '%y: Jaar (2 cijfers)<br/>' .
    '%Y: Jaar (4 cijfers)<br/>' .
    '%x: 18+<br/>' .
    '%z: Dag van het jaar<br/>';

$LN['pref_add_setname']          = 'Voeg de setnaam toe aan de download directory';
$LN['pref_add_setname_msg']      = 'Voeg de setnaam toe aan de download directory nadat de download directory formaat string is toegepast';

$LN['config_maxexpire']	    = 'Maximale opschoontijd';
$LN['config_maxexpire_msg']	= 'Het maximale aantal dagen dat kan worden ingevuld voor de opschoontijd van niewsgroepen en rss bronnen';
$LN['config_max_login_count']	= 'Maximaal aantal mislukte loginpogingen';
$LN['config_max_login_count_msg']	= 'Het maximaal aantal loginpogingen voordat een account wordt geblokkeerd.';
$LN['config_maxheaders']	    = 'Maximaal aantal headers per batch';
$LN['config_maxheaders_msg']	= 'Het maximaal aantal headers dat in een batch wordt opgehaald';
$LN['config_max_dl_name']       = 'Maximale download naam';
$LN['config_max_dl_name_msg']   = 'De maximum lengte van de naam die gebruikt wordt voor downloads';
$LN['config_spots_max_categories']   = 'Max. aantal categori&euml;n per spot';
$LN['config_spots_max_categories_msg']   = 'Spots met meer dan dit aantal categori&euml;n wordt verwijderd (0 voor geen limiet)';
$LN['config_spots_whitelist']   = 'URL voor witte lijst voor spotters';
$LN['config_spots_whitelist_msg']       = 'URL die een lijst bevat met IDs van spotters die bekent staan als valide gebruiker';
$LN['config_spots_blacklist']   = 'URL voor zwarte lijst voor spotters';
$LN['config_spots_blacklist_msg']       = 'URL die een lijst bevat met IDs van spotters die bekent staan als misbruiker';
$LN['config_download_spots_images']      = 'Download afbeeldingen voor spots';
$LN['config_download_spots_images_msg']  = 'Download afbeeldingen voor spots als de spots worden ge&uuml;pdatet';
$LN['config_download_spots_reports']    = 'Download spam rapportages voor spots';
$LN['config_download_spots_reports_msg']    = 'Download spam rapportages voor spots als de spots worden ge&uuml;pdatet';
$LN['config_download_spots_comments']       = 'Download comments voor spots';
$LN['config_download_spots_comments_msg']   = 'Download comments voor spots als de spots worden ge&uuml;pdatet';
$LN['config_spot_expire_spam_count']    = 'Bovengrens voor aantal spam rapportages waarna de spot wordt verwijderd';
$LN['config_spot_expire_spam_count_msg']    = 'Spots worden automatisch verwijderd nadat de limiet voor het aantal spam rapportages wordt overschreden (0 om nooit te verwijderen)';
$LN['config_allow_robots']      = 'Robots toestaan';
$LN['config_allow_robots_msg']  = 'Sta robots toe om de URD webpagina&#039s te volgen en te indexeren';
$LN['config_parse_nfo']     = 'Parseer nfo bestand';
$LN['config_parse_nfo_msg'] = 'Parseer nfo bestanden wanneer deze worden gepreviewd';
$LN['pref_use_auto_download_msg']        = 'Download automatisch op basis van de zoek termen';
$LN['pref_use_auto_download_nzb_msg']    = 'Download automatisch als NZB bestand op basis van de zoek termen';

$LN['pref_subs_lang_msg']            = 'Talen waarvoor naar ondertitels zal worden gezocht (twee letter codes komma gescheiden, leeg om niet te gebruiken)';
$LN['pref_subs_lang']                = 'Ondertitel talen';
$LN['config_replacement_str']   = 'Download naam vervangingstekst';
$LN['config_replacement_str_msg'] = 'Tekst waardoor ongeschikte karakters in download naam worden vervangen';
$LN['pref_basket_type']              = 'Type downloadmand';
$LN['pref_basket_type_msg']          = 'Het type download mand dat standaard wordt gebruikt';
$LN['basket_type_small']        = 'Compact';
$LN['basket_type_large']        = 'Uitgebreid';
$LN['pref_search_type']              = 'Zoektype';
$LN['pref_search_type_msg']          = 'Het zoektype voor de database dat gebruikt wordt voor het markeren van sets';
$LN['search_type_like']         = 'Eenvoudige wildcards (LIKE)';
$LN['search_type_regexp']       = 'Reguliere expressies (REGEXP)';
$LN['config_group_filter']      = 'Niewsgroep filter';
$LN['config_group_filter_msg']  = 'Filter voor de nieuwsgroepen die opgenomen worden (gebruik komma om meerdere te scheiden)';
$LN['config_extset_group']      = 'Nieuwsgroep voor extsetdata';
$LN['config_extset_group_msg']  = 'De nieuwsgroep waar extsetdata wordt gepost en opgevraagd';
$LN['config_spots_comments_group']       = 'Nieuwsgroep voor spots comments';
$LN['config_spots_comments_group_msg']   = 'De nieuwsgroep waar comments op spots worden opgevraagd';
$LN['config_spots_reports_group']       = 'Newsgroup for spots spam reports';
$LN['config_spots_reports_group_msg']   = 'The newsgroup from which spots spam reports will be read';
$LN['config_spots_group']       = 'Nieuwsgroep voor spots';
$LN['config_spots_group_msg']   = 'De nieuwsgroep waar spots worden opgevraagd';
$LN['config_ftd_group']         = 'Nieuwsgroep voor NZB bestanden in spots';
$LN['config_ftd_group_msg']     = 'De nieuwsgroep waar NZB bestanden in spots kunnen worden gevonden';
$LN['config_queue_size']        = 'Queueomvang';
$LN['config_queue_size_msg']    = 'Maximaal aantal taken die in de queue kunnen';
$LN['config_index_page_root_msg']  = 'De standaard pagina die wordt getoond na een login';
$LN['config_index_page_root']      = 'De standaard pagina';

$LN['config_modules']          = 'Modules';
$LN['config_module_groups']    = 'Indexeren van groepen';
$LN['config_module_makenzb']   = 'Aanmaken NZB bestanden';
$LN['config_module_usenzb']    = 'Importeren NZB bestanden';
$LN['config_module_nzb']       = 'NZB ondersteuning';
$LN['config_module_post']      = 'Uploaden naar nieuwsgroepen';
$LN['config_module_spots']     = 'Inlezen van spots';
$LN['config_module_rss']       = 'RSS ondersteuning';
$LN['config_module_sync']      = 'Synchroniseren van extended set informatie';
$LN['config_module_download']  = 'Downloaden uit nieuwsgroepen';
$LN['config_module_viewfiles'] = 'Inline bestanden bekijken';

$LN['config_poster_blacklist']         = 'Posters die worden geblokkeerd';
$LN['config_poster_blacklist_msg']     = 'Posters wiens naam of email adres overeenkomen met de reguliere expressies op deze regels worden niet in de database opgenomen';

$LN['config_module_groups_msg']   = 'Indexeren van groepen';
$LN['config_module_makenzb_msg']  = 'Ondersteuning voor het aanmaken van NZB bestanden';
$LN['config_module_usenzb_msg']   = 'Ondersteuning voor het downloaden van NZB bestanden';
$LN['config_module_post_msg']     = 'Uploaden naar nieuwsgroepen';
$LN['config_module_spots_msg']    = 'Uitlezen van spots van een nieuwsgroep';
$LN['config_module_rss_msg']      = 'RSS ondersteuning';
$LN['config_module_sync_msg']     = 'Synchroniseren van extended set informatie';
$LN['config_module_download_msg'] = 'Downloaden uit nieuwsgroepen';
$LN['config_module_viewfiles_msg'] = 'Inline bestanden bekijken';

$LN['config_urdd_uid']      = 'Gebruiker ID van urdd';
$LN['config_urdd_gid']      = 'Groep ID van urdd';
$LN['config_urdd_uid_msg']  = 'De gebruiker ID waarnaar urdd wijzigt wanneer deze is gestart als root (laat leeg om niet te wijzigen)';
$LN['config_urdd_gid_msg']  = 'De groep ID waarnaar urdd wijzigt wanneer deze is gestart als root (laat leeg om niet te wijzigen)';

$LN['username_msg']         = 'De gebruikersnaam waarmee ingelogd is';
$LN['newpw1_msg']           = 'Het nieuwe wachtwoord';
$LN['newpw2_msg']           = 'Nogmaals het nieuwe wachtwoord';
$LN['oldpw_msg']            = 'Het huidige wachtwoord';
$LN['pref_maxsetname_msg']       = 'De maximale lengte van een setnaam bij weergave';
$LN['pref_setsperpage_msg']      = 'Het maximale aantal sets dat per pagina getoond wordt';
$LN['pref_minsetsize_msg']       = 'De minimale grootte van een set om getoond te worden op de Aanbod pagina';
$LN['pref_maxsetsize_msg']       = 'De maximale grootte van een set om getoond te worden op de Aanbod pagina';
$LN['pref_minngsize_msg']        = 'Het minimum aantal berichten dat een nieuwsgroep moet hebben om getoond te worden op de Nieuwsgroepen pagina';
$LN['pref_hiddenfiles_msg']      = 'Indien geselecteerd zullen bepaalde bestanden worden verborgen op de Bestanden pagina';
$LN['config_global_hiddenfiles_msg']      = 'Indien geselecteerd zullen bepaalde bestanden worden verborgen op de Bestanden pagina';
$LN['config_global_hidden_files_list_msg']    = 'Lijst van bestanden die automatisch verborgen worden op de Bestanden pagina. 1 bestandsnaam per regel, * en ? kunnen gebruikt worden.';
$LN['pref_hidden_files_list_msg']    = 'Lijst van bestanden die automatisch verborgen worden op de Bestanden pagina. 1 bestandsnaam per regel, * en ? kunnen gebruikt worden.';

$LN['pref_defaultsort_msg']      = 'Het veld dat gebruikt wordt om sets mee te sorteren';
$LN['pref_buttons_msg']          = 'Zoekopties die getoond worden op de Aanbod pagina';
$LN['pref_unpar_msg']            = 'Indien geselecteerd zullen gedownloade sets automatisch worden gerepareerd met par2';
$LN['pref_unrar_msg']            = 'Indien geselecteerd zullen gedownloade sets automatisch worden uitgepakt';
$LN['pref_delete_files_msg']     = 'Indien geselecteerd zullen, na het repareren en uitpakken, automatisch de originele bestanden worden verwijderd';
$LN['pref_mail_user_msg']        = 'Stuur een mail-bericht wanneer een download klaar is';
$LN['pref_search_terms_msg']     = 'Markeer sets die aan deze termen voldoen automatisch als interessant (1 term per regel)';
$LN['pref_blocked_terms_msg']    = 'Markeer sets die aan deze termen voldoen automatisch als verborgen (1 term per regel)';

$LN['pref_mail_user_sets']       = 'Verstuur interessante sets';
$LN['pref_mail_user_sets_msg']   = 'Verstuur een bericht als een nieuwe interessante set is gevonden';
$LN['descending']           = 'Aflopend';
$LN['ascending']            = 'Oplopend';

$LN['settings_imported']	= 'Voorkeuren opgeslagen';
$LN['settings_import']		= 'Importeer voorkeuren';
$LN['settings_export']		= 'Exporteer voorkeuren';
$LN['settings_import_file']	= 'Importeer voorkeuren vanuit bestand';
$LN['settings_notfound']	= 'Bestand niet gevonden of geen voorkeuren geladen';
$LN['settings_upload']		= 'Voorkeuren inladen';
$LN['settings_filename']	= 'Bestandsnaam';

$LN['import_servers']		= 'Importeer servers';
$LN['export_servers']		= 'Exporteer servers';
$LN['import_groups']		= 'Importeer groepen';
$LN['export_groups']		= 'Exporteer groepen';
$LN['import_feeds']		    = 'Importeer bronnen';
$LN['export_feeds']		    = 'Exporteer bronnen';
$LN['import_users']		    = 'Importeer gebruikers';
$LN['export_users']		    = 'Exporteer gebruikers';
$LN['import_buttons']		= 'Importeer zoekopties';
$LN['export_buttons']		= 'Exporteer zoekopties';
$LN['import_spots_blacklist']		= 'Importeer zwarte lijst voor spots';
$LN['export_spots_blacklist']		= 'Exporteer zwarte lijst voor spots';
$LN['import_spots_whitelist']		= 'Importeer witte lijst voor spots';
$LN['export_spots_whitelist']		= 'Exporteer witte lijst voor spots';

// pref errors
$LN['error_pwmatch']        = 'Wachtwoorden komen niet overeen';
$LN['error_pwincorrect']    = 'Wachtwoord onjuist';
$LN['error_pwusername']     = 'Wachtwoord lijkt teveel op de gebruikersnaam';
$LN['error_pwlength']       = 'Wachtwoord te kort, moet minimaal uit '. MIN_PASSWORD_LENGTH . ' karakters bestaan';
$LN['error_pwsimple']       = 'Wachtwoord te eenvoudig, gebruik een combinatie van HOOFDLETTERS, kleine letters, numm3r5 en @ndere k@r@kter$';
$LN['error_captcha']        = 'CAPTCHA onjuist';
$LN['error_nocontent']      = 'Bericht te kort';
$LN['error_toolong']        = 'Bericht te lang';
$LN['error_filetoolarge']   = 'Bestand te groot';
$LN['error_nosubcats']      = 'Geen subcategorie&euml; geselecteerd';
$LN['error_nzbfilemissing'] = 'NZB bestand ontbreekt';
$LN['error_imgfilemissing'] = 'Image bestand ontbreekt';
$LN['error_invalidcategory'] = 'Ongeldige category';

$LN['error_onlyforgrops'] 	= 'Werkt alleen voor groupen';
$LN['error_onlyoneset'] 	= 'Hiervoor is meer dan 1 set in de basket nodig';

$LN['error_downloadnotfound']   = 'Download niet gevonden';
$LN['error_linknotfound'] 	= 'Link niet gevonden';
$LN['error_nzbfailed'] 	    = 'NZB bestand kon niet worden ge&iuml;porteerd';
$LN['error_toomanybuttons']     = 'Teveel zoekopties';
$LN['error_invalidbutton']      = 'Ongeldige zoekoptie';
$LN['error_invalidemail']       = 'Ongeldig e-mail-adres';
$LN['error_invalidpassword']    = 'Ongeldig wachtwoord';
$LN['error_userexists']         = 'Gebruiker bestaat al';
$LN['error_acctexpired']        = 'Gebruikersaccount is verlopen';
$LN['error_invalid_upload_type']= 'Ongeldig upload type';
$LN['error_notleftblank']       = 'Mag niet leeggelaten worden';
$LN['error_invalidvalue']       = 'Ongeldige waarde';
$LN['error_urlstart']           = 'De link moet beginnen met http:// en eindigen met een /';
$LN['error_error']              = 'Fout';
$LN['error_invaliddir']         = 'Ongeldige map';
$LN['error_notmakedir']         = 'Kon map niet aanmaken';
$LN['error_notmaketmpdir']      = 'Kon tmp-map niet aanmaken';
$LN['error_notmakepreviewdir']  = 'Kon preview-map niet aanmaken';
$LN['error_dirnotwritable']     = 'Map is niet schrijfbaar';
$LN['error_notestfile']         = 'Kon testbestanden niet aanmaken';
$LN['error_mustbemore']         = 'moet meer zijn dan';
$LN['error_mustbeless']         = 'moet minder zijn dan';
$LN['error_filenotexec']        = 'Bestand kon niet gevonden worden of is niet uitvoerbaar voor de webserver';
$LN['error_noremovedir']        = 'Kan map niet verwijderen';
$LN['error_noremovefile']       = 'Kan bestand niet verwijderen';
$LN['error_noremovefile2']      = 'Kon bestand niet verwijderen, map is niet schrijfbaar';
$LN['error_nodeleteroot']       = 'Kan hoofdgebruiker niet verwijderen';
$LN['error_nosetids']           = 'Geen setID&#39;s opgegeven!';
$LN['error_invalidstatus']      = 'Ongeldige status-waarde opgegeven';
$LN['error_invaliduserid']      = 'Ongeldige gebruikers-identiteit';
$LN['error_groupnotfound']      = 'Groep niet gevonden';
$LN['error_invalidgroupid']     = 'Ongeldige groepsidentiteit opgegeven';
$LN['error_couldnotreadargs']   = 'Kon de parameters niet lezen. Is register_argc_argv wel On?';
$LN['error_resetnotallowed']    = 'Het wissen van de instellingen is niet toegestaan';
$LN['error_prefnotfound']       = 'Voorkeur niet gevonden';
$LN['error_invalidfilename']    = 'Ongeldige bestandsnaam';
$LN['error_fileexists']         = 'Bestand bestaat al';
$LN['error_cannotrename']       = 'Kan bestand niet hernoemen';
$LN['error_needfilenames']      = 'Bestandsnaam benodigd';
$LN['error_usenetserverexists'] = 'Een server met die naam bestaat al';
$LN['error_missingconnection']  = 'Ongelding verbindingstype';
$LN['error_missingthreads']     = 'Aantal threads moet worden opgegeven';
$LN['error_missinghostname']    = 'Hostnaam moet worden opgegeven';
$LN['error_missingname']        = 'Naam moet worden opgegeven';
$LN['error_needatleastoneport'] = 'Tenminste een poortnummer moet worden opgegeven';
$LN['error_needsecureport']     = 'Een versleutelde verbinding vereist een secure poortnummer';
$LN['error_nosuchserver']       = 'Server bestaat niet';
$LN['error_invalidsetid']       = 'Ongelding Set ID gegeven';
$LN['error_couldnotsendmail']   = 'Kon bericht niet versturen';

$LN['error_pwresetnomail']      = 'Wachtwoord gereset, maar e-mail kon niet worden verzonden';
$LN['error_userupnomail']       = 'Gebruiker aangepast, maar e-mail kon niet worden verzonden';

$LN['error_diskfull']       = 'Onvoldoende disk ruimte verwacht om download te completeren';
$LN['error_invalidaction']  = 'Onbekende actie';
$LN['error_nameexists']     = 'Een usenet server met die naam bestaat al';
$LN['error_preview_size_exceeded']      = 'Bestand te groot om te previewen';
$LN['error_cannotchmod']    = 'Toegangsrechten veranderen is niet toegestaan';
$LN['error_cannotchgrp']    = 'Groep wijzigen is niet toegestaan';
$LN['error_post_not_found'] = 'Upload niet gevonden';
$LN['error_groupnotfound']  = 'Groep bestaat niet';
$LN['error_subjectnofound'] = 'Onderwerp ontbreekt';
$LN['error_posternotfound'] = 'Poster e-mail ontbreekt';
$LN['error_invalidrecsize'] = 'Ongeldige herstel omvang';
$LN['error_invalidrarsize'] = 'Ongeldige rar omvang';
$LN['error_namenotfound']   = 'Poster naam ontbreekt';
$LN['error_nosetsfound']    = 'Geen sets gevonden';
$LN['error_nousersfound']   = 'Geen gebruikers gevonden';
$LN['error_nowrite']                = 'Kon bestand niet schrijven';
$LN['error_searchnamenotfound']     = 'Naam niet gevonden';
$LN['error_missingparameter']       = 'Ontbrekende parameter';
$LN['error_nouploaddata']           = 'Geen inhoud gevonden op te uploaden in';
$LN['error_nameexists']             = 'Naam bestaat al';

$LN['error_noserversfound']         = 'Geen servers gevonden';
$LN['error_nouploadsfound']         = 'Geen uploads gevonden';
$LN['error_nodownloadsfound']       = 'Geen downloads gevonden';
$LN['error_nogroupsfound']          = 'Geen groepen gevonden';
$LN['error_nosearchoptionsfound']   = 'Geen zoekopties gevonden';
$LN['error_nofeedsfound']           = 'Geen RSS bronnen gevonden';
$LN['error_notasksfound']           = 'Geen taken gevonden';
$LN['error_nojobsfound']            = 'Geen jobs gevonden';
$LN['error_nologsfound']            = 'Geen logs gevonden';

$LN['error_spotnotfound']           = 'Spot niet gevonden';
$LN['error_setnotfound']            = 'Set niet gevonden';
$LN['error_binariesnotfound']       = 'Kon binaries niet vinden';
$LN['error_invalidimage']           = 'Geen correct beeldbestand';

$LN['error_schedulesnotset']        = 'Taken konden niet worden ingepland';
$LN['error_unknowntype']            = 'Onbekend type';
$LN['error_emptybasket']            = 'Lege mand';

// Admin pages:
$LN['adminshutdown']        = 'Schakel de URD Daemon uit';
$LN['adminrestart']		    = 'Herstart URD Daemon';
$LN['adminpause']           = 'Pauzeer alle activiteiten';
$LN['admincontinue']        = 'Ga door met alle activiteiten';
$LN['adminclear']           = 'Schoon alle downloads op';
$LN['admincleandb']         = 'Verwijder alle tijdelijke informatie';
$LN['adminremoveready']     = 'Schoon afgelopen downloads op';
$LN['adminpoweron']         = 'Schakel de URD Daemon aan';
$LN['adminupdatenglist']    = 'Update de nieuwsgroeplijst';
$LN['adminupdateblacklist'] = 'Update de spots zwarte lijst';
$LN['adminupdatewhitelist'] = 'Update de spots witte lijst';
$LN['admingensetsallngs']   = 'Genereer sets voor alle nieuwsgroepen';
$LN['adminupdateallngs']    = 'Update alle nieuwsgroepen';
$LN['adminexpireallngs']    = 'Schoon alle nieuwsgroepen op';
$LN['adminpurgeallngs']     = 'Leeg alle nieuwsgroepen';
$LN['adminoptimisedb']      = 'Optimaliseer de database';
$LN['admincheckversion']    = 'Controleer URD versie';
$LN['admingetsetinfo']      = 'Haal set informatie op';
$LN['adminsendsetinfo']     = 'Verstuur set informatie';
$LN['admincleandir']        = 'Schoon mappen op';
$LN['adminexpireallrss']    = 'Schoon alle RSS bronnen op';
$LN['adminpurgeallrss']     = 'Leeg alle RSS bronnen';
$LN['adminupdateallrss']    = 'Update alle RSS bronnen';
$LN['adminfindservers']     = 'Autoconfigureer usenet servers';
$LN['adminfindservers_ext'] = 'Autoconfigureer usenet servers (uitgebreid)';
$LN['adminexport_all']      = 'Exporteer alle instellingen';
$LN['adminimport_all']      = 'Importeer alle instellingen';
$LN['adminupdate_spots']    = 'Spots updaten';
$LN['adminupdate_spotscomments']    = 'Spots commentaren updaten';
$LN['adminupdate_spotsimages']    = 'Spots afbeeldingen updaten';
$LN['adminexpire_spots']    = 'Spots opschonen';
$LN['adminpurge_spots']     = 'Spots legen';

// register
$LN['reg_disabled']     = 'Registratie is uitgeschakeld';
$LN['reg_title']        = 'Account registratie';
$LN['reg_codesent']     = 'De activeringscode is verstuurd';
$LN['reg_status']       = 'Registratie status';
$LN['reg_activated']    = 'Het account is geactiveerd. Klik om';
$LN['reg_activated_link'] = 'in te loggen';
$LN['reg_pending']      = 'Het account is nog niet geactiveerd. Wacht aub tot een admin het activeert.';
$LN['reg_form']         = 'Vul dit formulier in om een account aan te vragen';
$LN['reg_again']        = 'opnieuw';

//admin controls
$LN['control_title']    = 'Daemon Beheer';
$LN['control_options']  = 'Opties';
$LN['control_jobs']     = 'Taken';
$LN['control_threads']  = 'Draadjes';
$LN['control_queue']    = 'Wachtrij';
$LN['control_servers']  = 'Servers';
$LN['control_uptime']   = 'Up tijd';
$LN['control_load']     = 'Systeembelasting';
$LN['control_diskspace']    = 'Schijfruimte';
$LN['control_cancelall']    = 'Alle taken stoppen';
//$LN['control_']       = '';

//admin jobs
$LN['jobs_title']      = 'Agenda';
$LN['jobs_command']    = 'Commando';
$LN['jobs_period']     = 'Periode';
$LN['jobs_user']       = 'Gebruiker';

//posting
$LN['post_subject']         = 'Onderwerp';
$LN['post_delete_files']    = 'Verwijder bestanden';
$LN['post_delete_filesext'] = 'Verwijder tijdelijke bestanden (zoals rar en par2 bestanden)';
$LN['post_postername']      = 'Uploader naam';
$LN['post_posteremail']     = 'Uploader e-mail adres';
$LN['post_recovery']        = 'Herstel percentage';
$LN['post_rarfiles']        = 'Rarfile grootte';
$LN['post_newsgroup']       = 'Nieuwsgroep';
$LN['post_post']            = 'Uploaden';
$LN['post_directory']       = 'Directory';
$LN['post_directoryext']    = 'De directory die wordt ge&uuml;pload';
$LN['post_subjectext']      = 'De onderwerpregel in de berichten (subject)';
$LN['post_posternameext']   = 'De naam van de uploader in het bericht (from)';
$LN['post_posteremailext']  = 'Het e-mail adres van de uploader in het bericht (from)';
$LN['post_recoveryext']     = 'Het percentage par2 files te genereren';
$LN['post_rarfilesext']     = 'De omvang van de te genereren gecomprimeerde rar bestanden';
$LN['post_newsgroupext']    = 'De nieuwsgroep waarin de bestanden worden gepost';

// admin tasks
$LN['tasks_title']          = 'Taken';
$LN['tasks_description']    = 'Beschrijving';
$LN['tasks_progress']       = 'Voortgang';
$LN['tasks_added']          = 'Toegevoegd';
$LN['tasks_lastupdated']    = 'Laatst ge&uuml;pdatet';
$LN['tasks_comment']        = 'Commentaar';
//$LN['tasks_']             = '';

// admin config
$LN['config_title']         = 'Configuratie';
$LN['config_setinfo']       = 'Set informatie';
$LN['config_urdd_head']     = 'URD daemon';
$LN['config_nntp_maxthreads']      = 'Maximale aantal NNTP verbindingen';
$LN['config_urdd_maxthreads']   = 'Maximale aantal draadjes';
$LN['config_spots_expire_time']  = 'Opschoontijd voor spots (in dagen)';
$LN['config_spots_expire_time_msg']  = 'Opschoontijd voor spots (in dagen); NB dit overschrijft de waarden die voor de respectievelijke niewsgroep zijn gezet';
$LN['config_default_expire_time']                = 'Standaard opschoontijd (in dagen)';
$LN['config_expire_incomplete']     = 'Standaard opschoontijd voor incomplete sets (in dagen, 0 op niet te gebruiken)';
$LN['config_expire_percentage']     = 'Percentage compleetheid voor vroegtijdig verwijderen';
$LN['config_auto_expire']        = 'Schoon op na een update';
$LN['config_auto_getnfo']	= 'Auto-download nfo bestanden';
$LN['config_auto_getnfo_msg']= 'Automatisch downloaden van nfo files na het updaten van een nieuwsgroep';
$LN['config_period_getspots']	    = 'Laad spots';
$LN['config_period_getspots_msg']	= 'Laad spots';
$LN['config_period_getspots_whitelist']	    = 'Laad de spots witte lijst';
$LN['config_period_getspots_whitelist_msg']	= 'Plan het laden van de witte lijst met spot posters in';
$LN['config_period_getspots_blacklist']	    = 'Laad de spots zwarte lijst';
$LN['config_period_getspots_blacklist_msg']	= 'Plan het laden van de zwarte lijst met spot posters in';
$LN['pref_cancel_crypted_rars']   = 'Annuleer vercijferde downloads';
$LN['config_clickjack']     = 'Activeer clickjack bescherming';
$LN['config_clickjack_msg'] = 'Activeer clickjack bescherming zodat URD altijd in een volledig venster draait en niet in een frame';
$LN['config_need_challenge']     = 'Activeer XSS bescherming';
$LN['config_need_challenge_msg'] = 'Activeer cross-site scripting bescherming zodat URD functies niet kunnen worden misbruikt vanaf andere sites';
$LN['config_use_encrypted_passwords'] = 'Sla usenet account wachtwoorden vercijferd op';
$LN['config_use_encrypted_passwords_msg'] = 'Sla de wachtwoorden van usenet accounts vercijferd op; de sleutel wordt opgeslagen in een apart sleutelbestand.';
$LN['config_keystore_path']         = 'Locatie van het sleutelbestand';
$LN['config_keystore_path_msg']     = 'De folder waar het sleutelbestand wordt geplaatst';
$LN['config_dlpath']        = 'Sla downloads hier op';
$LN['config_pidpath']       = 'Locatie van het PID bestand';
$LN['config_pidpath_msg']   = 'De locatie van het PID bestand dat gebruikt wordt om te voorkomen dat URDD meermaals wordt opgestart (Laat leeg om geen PID file gebruiken)';
$LN['config_urdd_host']  = 'URDD systeemnaam';
$LN['config_urdd_port']      = 'URDD poort';
$LN['config_maxfilesize']   = 'Max. bestandsgrootte om te bekijken in bestanden';
$LN['config_maxpreviewsize']   = 'Max. bestandsgrootte om te previewen';
$LN['config_urdd_restart']       = 'Herstart oude taken';
$LN['config_urdd_daemonise']     = 'Start URDD als een achtergrond proces';
$LN['config_urdd_daemonise_msg'] = 'Start URDD als een achtergrond proces (daemon)';
$LN['config_admin_email']    = 'E-mailadres van beheerder';
$LN['config_baseurl']       = 'Lokaal URD websiteadres (met paden)';
$LN['config_shaping']       = 'Limiteer bandbreedte';
$LN['config_maxdl']         = 'Max. download bandbreedte (kB/s) per verbinding';
$LN['config_maxul']         = 'Max. upload bandbreedte (kB/s) ver verbinding';
$LN['config_register']      = 'Registratie toestaan';
$LN['config_auto_reg']    = 'Automatisch registratie goedkeuren';
$LN['config_scheduler']     = 'URDD inplanner';
$LN['config_scheduler_msg'] = 'De inplanner voert op gezette tijden bepaalde taken uit';
$LN['config_urdd_path']          = 'urdd';
$LN['config_unpar_path']          = 'par2';
$LN['config_unrar_path']         = 'unrar';
$LN['config_rar_path']           = 'rar';
$LN['config_unace_path']         = 'unace';
$LN['config_tar_path']           = 'tar';
$LN['config_un7zr_path']         = 'un7za';
$LN['config_unzip_path']         = 'unzip';
$LN['config_gzip_path']          = 'gzip';
$LN['config_unarj_path']         = 'unarj';
$LN['config_file_path']          = 'file';
$LN['config_yydecode_path']      = 'yydecode';
$LN['config_yyencode_path']      = 'yyencode';
$LN['config_cksfv_path']         = 'cksfv';
$LN['config_trickle_path']       = 'trickle';
$LN['config_period_update']   = 'Controleer op nieuwe versies van URD';
$LN['config_period_opt']    = 'Optimaliseer database';
$LN['config_period_ng']  = 'Update nieuwsgroepenlijst';
$LN['config_period_cd']      = 'Schoon tijdelijke bestanden en preview mappen op';
$LN['config_period_cdb']       = 'Verwijder de vluchtige informatie uit de database';
$LN['config_period_cu']            = 'Periode voor inaktieve gebruikers';
$LN['config_period_cu_msg']        = 'Periode voor inaktieve, non-admin gebruikers waarna ze worden verwijderd in dagen';
$LN['config_users_clean_age_msg']   = 'Clean inactive, non-admin users after a period of inactivity';
$LN['config_users_clean_age']       = 'Verwijder inactive gebruikers';
$LN['config_users_clean_age_msg']   = 'Verwijder inactive non-admin gebruikers na een periode van afwezigheid (in days)';
$LN['config_socket_timeout']        = 'Socket timeout';
$LN['config_urdd_connection_timeout']       = 'URDD connection timeout';
$LN['config_urdd_connection_timeout_msg']   = 'Het aantal seconden waarna een verbinding naar URDD wordt be&euml;indigd als deze niet meer reageert; standaardwaarde is 30.';
$LN['config_auto_download']                 = 'Sta automatisch downloaden toe';
$LN['config_check_nntp_connections']        = 'Controleer het aantal usenet verbindingen bij opstarten';
$LN['config_check_nntp_connections_msg']    = 'Selecteer het aantal mogelijke parallele verbindingen naar een NNTP server bij het opstarten van URDD';
$LN['config_nntp_all_servers']              = 'Run downloads op alle mogelijke servers tegelijk';
$LN['config_nntp_all_servers_msg']          = 'Sta downloads toe met het totaal maximum aan NNTP threads op alle ingeschakelde servers, in plaats van &eacute;&eacute;n download op een server te runnen.';
$LN['config_clean_dir_age']     = 'Opschoontijd tijdelijke bestanden';
$LN['config_clean_dir_age_msg'] = 'De tijd waarna tijdelijke bestanden worden opgeruimd door het cleandir commando (in dagen)';
$LN['config_clean_db_age']      = 'Opschoontijd database informatie';
$LN['config_clean_db_age_msg']  = 'De tijd waarna vluchtige informatie uit de database wordt verwijderd door het clean db commando (in dagen; 0 is uitgeschakeld)';

$LN['config_keep_interesting']          = 'Interessante sets niet opschonen';
$LN['config_keep_interesting_msg']      = 'Sets die gemarkeerd zijn als interessant worden niet verwijderd bij het opschonen';
$LN['config_prog_params']       = 'Parameters';

$LN['config_urdd_pars']         = 'urdd';
$LN['config_unpar_pars']         = 'par2';
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

$LN['config_compress_nzb']      = 'Comprimeer NZB bestanden';
$LN['config_compress_nzb_msg']  = 'Comprimeer NZB bestanden na deze te hebben gedownload';
$LN['config_auto_download_msg'] = 'Sta gebruikers toe sets automatisch te downloaden op basis van zoektermen';
$LN['config_urdd_pars_msg']     = 'urdd parameters';
$LN['config_unpar_pars_msg']     = 'par2 parameters';
$LN['config_unrar_pars_msg']    = 'unrar parameters';
$LN['config_rar_pars_msg']      = 'rar parameters';
$LN['config_unace_pars_msg']    = 'unace parameters';
$LN['config_tar_pars_msg']      = 'tar parameters';
$LN['config_un7zr_pars_msg']    = 'un7za parameters';
$LN['config_unzip_pars_msg']    = 'unzip parameters';
$LN['config_gzip_pars_msg']     = 'gzip parameters';
$LN['config_unarj_pars_msg']    = 'unarj parameters';
$LN['config_yydecode_pars_msg'] = 'yydecode parameters';
$LN['config_yyencode_pars_msg'] = 'yyencode parameters';

$LN['config_webeditfile']	    = 'Sta bewerken van bestanden toe in de webinterface';
$LN['config_webeditfile_msg']	= 'Gebruikers kunnen bestanden bewerken in de webinterface';
$LN['config_webdownload']       = 'Sta downloaden toe in webinterface';
$LN['config_sendmail']          = 'Sta het verzenden van e-mails toe';
$LN['config_sendmail_msg']      = 'Indien geaangevinkt is het mogelijk e-mails te versturen, bijvoorbeeld in geval van vergeten wachtwoorden of afronde downloads';
$LN['config_follow_link']       = 'Volg links in NFO bestanden na updaten';
$LN['config_follow_link_msg']   = 'Indien aangevinkt, links in NFO bestanden worden automatisch geparset na dat een groep is geupdate';
$LN['config_maxfilesize_msg']   = 'De maximale bestandsgrootte toegestaan om te bekijken in bestanden in kB, 0 voor geen limiet';
$LN['config_maxpreviewsize_msg']           = 'De maximale bestandsgrootte toegestaan om te previewen in kB, 0 voor geen limiet';
$LN['config_db_intensive_maxthreads']      = 'Maximum aantal database intensieve taken';
$LN['config_db_intensive_maxthreads_msg']  = 'Het maximum aantal taken dat veel toegang tot de database nodig heeft';

$LN['config_networking']             = 'Netwerk';
$LN['config_extprogs']               = 'Programma&#39;s';
$LN['config_maintenance']            = 'Onderhoudstaken';
$LN['config_globalsettings']         = 'Globaal';
$LN['config_notifysettings']         = 'Notificatie';
$LN['config_defaulttemplate']        = 'Standaard sjabloon';
$LN['config_webdownload_msg']        = 'Gebruikers kunnen bestanden als een tarbal downloaden op de bestanden pagina';
$LN['config_default_template']       = 'Standaard sjabloon';
$LN['config_default_template_msg']   = 'Het sjabloon dat gebruikt wordt als er geen (geldige) geselecteerd is';
$LN['config_default_language_msg']   = 'De taal die gebruikt wordt als er geen (geldige) geselecteerd is';
$LN['config_default_language']       = 'Standaard taal';
$LN['config_default_stylesheet']     = 'Standaard stylesheet';
$LN['config_default_stylesheet_msg'] = 'De stylesheet dat gebruikt wordt als er geen (geldige) geselecteerd is';

$LN['config_mail_account_activated']        = 'Account geactiveerd bericht';
$LN['config_mail_account_activated_msg']    = 'Mail die naar de gebruiker wordt gezonden wanneer het account is geactiveerd';
$LN['config_mail_account_disabled']         = 'Account geschorst bericht';
$LN['config_mail_account_disabled_msg']     = 'Mail die naar de gebruiker wordt gezonden wanneer het account is geschorst';
$LN['config_mail_activate_account']         = 'Activeer account bericht';
$LN['config_mail_activate_account_msg']     = 'Mail die naar de gebruiker wordt gezonden wanneer het account geactiveerd moet worden';
$LN['config_mail_download_status']          = 'Download status bericht';
$LN['config_mail_download_status_msg']      = 'Mail die naar de gebruiker wordt gezonden als de download afgerond is';
$LN['config_mail_new_interesting_sets']     = 'Nieuwe interensante sets bericht';
$LN['config_mail_new_interesting_sets_msg'] = 'Mail die naar de gebruiker wordt gezonden wanneer nieuwe sets als interessant zijn gemarkeerd';
$LN['config_mail_new_preferences']          = 'Nieuwe instellingen bericht';
$LN['config_mail_new_preferences_msg']      = 'Mail die naar de gebruiker wordt gezonden wanneer een nieuwe instelling toegevoegd is aan URD';
$LN['config_mail_new_user']                 = 'Nieuwe gebruiker bericht';
$LN['config_mail_new_user_msg']             = 'Mail die naar de beheerder wordt gezonden wanneer een gebruiker zich heeft geregistreerd';
$LN['config_mail_password_reset']           = 'Wachtwoord gereset bericht';
$LN['config_mail_password_reset_msg']       = 'Mail die naar de gebruiker wordt gezonden wanneer met het nieuwe wachtwoord';

$LN['config_log_level']         = 'Log niveau';
$LN['config_permissions_msg']   = 'Standaard permissies voor gedownloade bestanden';
$LN['config_permissions']       = 'Downloadpermissies';
$LN['config_group']             = 'Groep';
$LN['config_group_msg']         = 'De groep voor alle gedownloade bestanden';
$LN['config_maxbuttons']        = 'Maximale aantal zoekopties';
$LN['config_maxbuttons_msg']    = 'Het maximale aantal zoekopties dat wordt getoond op de browse pagina';
$LN['config_period_sendinfo']          = 'Verzend setinformatie';
$LN['config_period_sendinfo_msg']      = 'Verzend informatie naar URDland.com';
$LN['config_period_getinfo']           = 'Ophalen setinformatie';
$LN['config_period_getinfo_msg']       = 'Haal informatie op van URDland.com';
$LN['config_nice_value']               = 'Nice waarde';
$LN['config_nice_value_msg']           = 'Nice waarde voor externe programma&#39;s zoals par2 en rar';

$LN['config_nntp_maxdlthreads']         = 'Maximale aantal verbindingen per download';
$LN['config_nntp_maxdlthreads_msg']     = 'Het maximale aantal verbindingen per download (0 is geen limiet)';
$LN['config_allow_global_scripts_msg']  = 'Sta het uitvoeren van scripts ingevoerd door de administrators toe nadat een download is afgerond';
$LN['config_allow_global_scripts']      = 'Admin scripts toestaan';
$LN['config_allow_user_scripts_msg']    = 'Sta het uitvoeren van scripts ingevoerd door de gebruiker toe nadat een download is afgerond';
$LN['config_allow_user_scripts']        = 'Gebruiker scripts toestaan';
//$LN['config_']            = '';

$LN['config_total_max_articles']		= 'Maximaal artikelen gedownload per update';
$LN['config_total_max_articles_msg']	= 'Maximaal aantal artikelen dat wordt gedownload per update (0 is geen limiet)';

$LN['config_perms']['none'] = 'Niet aanpassen';
$LN['config_perms']['0400'] = 'Eigenaar alleen lezen (0400)';
$LN['config_perms']['0440'] = 'Eigenaar en groep alleen lezen (0440)';
$LN['config_perms']['0444'] = 'Iedereen lezen (0444)';
$LN['config_perms']['0600'] = 'Eigenaar lezen en schrijven (0600)';
$LN['config_perms']['0640'] = 'Eigenaar lezen en schrijven, groep alleen lezen (0640)';
$LN['config_perms']['0644'] = 'Eigenaar lezen en schrijven, overige alleen lezen (0644)';
$LN['config_perms']['0660'] = 'Eigenaar en groep lezen en schrijven (0660)';
$LN['config_perms']['0664'] = 'Eigenaar en groep lezen en schrijven, overige alleen lezen (0664)';
$LN['config_perms']['0666'] = 'Iedereen lezen en schrijven (0666)';

$LN['config_auto_login']        = 'Log automatisch in als';
$LN['config_auto_login_msg']    = 'Automatisch inloggen met de aangegeven gebruikersnaam. Laat de waarde leeg om autologin niet te gebruiken';

$LN['config_nntp_maxthreads_msg']      = 'Het maximale aantal parallelle verbindingen die de URD daemon kan gebruiken';
$LN['config_default_expire_time_msg']  = 'Het standaard aantal dagen waarna sets als verouderd worden beschouwd';
$LN['config_expire_incomplete_msg']    = 'Het aantal dagen waarna incomplete sets als verouderd worden beschouwd';
$LN['config_expire_percentage_msg']    = 'Het maximale percentage een set mag hebben om als verouderde set vroegtijdig te worden verwijderd';
$LN['config_auto_expire_msg']          = 'Schoon verouderde berichten automatisch op na een update';
$LN['pref_cancel_crypted_rars_msg']    = 'Analyseer bestanden terwijl ze worden gedownload en annuleer deze als de rar bestanden vercijferd zijn (en er geen wachtwoord voor de download bekend is)';
$LN['config_urdd_maxthreads_msg']      = 'Het maximale aantal parallelle taken dat de URD daemon uit kan voeren';
$LN['config_admin_email_msg']          = 'Het e-mail-adres van de beheerder';
$LN['config_urdd_host_msg']            = 'De systeemnaam of IP-adres van de URD daemon (standaard localhost) (Noot: IPv6 adressen moeten tussen [] worden geschreven, bijv. [::1])';
$LN['config_auto_reg_msg']    = 'Indien uitgevinkt moet de beheerder elke registratieaanvraag handmatig goedkeuren, anders wordt hij automatisch goedgekeurd';
$LN['config_dlpath_msg']    = 'De map waarin URD de gedownloade bestanden zet';
$LN['config_urdd_port_msg']  = 'Het poortnummer van de URD daemon (standaard 11666)';
$LN['config_urdd_restart_msg']   = 'Actieve taken ten tijde van het stoppen van de URD Daemon worden automatisch opnieuw opgestart indien dit aangevinkt is';
$LN['config_baseurl_msg']   = 'De basis-URL van de URD applicatie';
$LN['config_shaping_msg']   = 'Gebruik bandbreedte limieten om de bandbreedte van URDD te beperken';
$LN['config_maxdl_msg']     = 'De maximale bandbreedte die is toegestaan voor URD wanneer van de nieuwsserver wordt gedownload';
$LN['config_maxul_msg']     = 'De maximale bandbreedte die is toegestaan voor URD wanneer van de nieuwsserver wordt geupload';
$LN['config_register_msg']  = 'Indien aangevinkt is registratie van nieuwe gebruikers mogelijk op de loginpagina.';
$LN['config_urdd_path_msg']      = 'Het pad waar het URDD opstartscript (urdd.sh) kan worden gevonden';
$LN['config_unpar_path_msg']     = 'Het pad waar het programma par2 kan worden gevonden (optioneel)';
$LN['config_unrar_path_msg']     = 'Het pad waar het programma rar (of unrar) kan worden gevonden voor decomprimeren (optioneel)';
$LN['config_rar_path_msg']       = 'Het pad waar het programma rar kan worden gevonden voor comprimeren (optioneel)';
$LN['config_tar_path_msg']       = 'Het pad waar het programma tar kan worden gevonden (optioneel)';
$LN['config_unace_path_msg']     = 'Het pad waar het programma unace kan worden gevonden (optioneel)';
$LN['config_un7zr_path_msg']     = 'Het pad waar het programma 7za, 7zr of 7z kan worden gevonden (optioneel)';
$LN['config_unzip_path_msg']     = 'Het pad waar het programma unzip kan worden gevonden (optioneel)';
$LN['config_gzip_path_msg']      = 'Het pad waar het programma gzip kan worden gevonden (optioneel)';
$LN['config_unarj_path_msg']     = 'Het pad waar het programma unarj kan worden gevonden (optioneel)';
$LN['config_subdownloader_path'] = 'subdownloader';
$LN['config_subdownloader_path_msg'] = 'Het pad waar het programma subdownloader kan worden gevonden (optioneel)';
$LN['config_file_path_msg']          = 'Het pad waar het programma file kan worden gevonden';
$LN['config_yydecode_path_msg']      = 'Het pad waar het programma yydecode kan worden gevonden (optioneel)';
$LN['config_yyencode_path_msg']      = 'Het pad waar het programma yyencode kan worden gevonden (optioneel)';
$LN['config_cksfv_path_msg']         = 'Het pad waar het programma cksfv kan worden gevonden (optioneel)';
$LN['config_trickle_path_msg']       = 'Het pad waar het programma trickle kan worden gevonden (optioneel)';
$LN['config_period_update_msg']   = 'Hoe vaak moet worden gecontroleerd of er een nieuwe versie van URDD is';
$LN['config_period_opt_msg']      = 'Hoe vaak moet de database worden geoptimaliseerd';
$LN['config_period_ng_msg']       = 'Hoe vaak moet de nieuwsgroepenlijst worden gedownload';
$LN['config_period_cd_msg']       = 'Hoe vaak moeten de /tmp en /preview directory worden opgeschoond (alle bestanden ouder dan 1 dag worden verwijderd)';
$LN['config_period_cdb_msg']      = 'Hoe vaak moeten de vluchtige data uit de database verwijderd worden';
$LN['config_log_level_msg']       = 'Welk niveau van logberichten moet worden bewaard';
$LN['config_socket_timeout_msg']  = 'Het aantal seconden waarna een verbinding wordt be&euml;indigd als deze niet meer reageert; standaardwaarde is 30';

// admin log
$LN['log_title']        = 'logbestand';
$LN['log_nofile']       = 'Geen logbestand gevonden';
$LN['log_seekerror']    = 'Kon bestand niet geheel inlezen';
$LN['log_unknownerror'] = 'Een onverwachtte (ja echt!) fout is opgetreden';
$LN['log_header']       = 'Log info';
$LN['log_date']         = 'Datum';
$LN['log_level']        = 'Niveau';
$LN['log_msg']          = 'Bericht';
$LN['log_lines']        = 'Regels';
$LN['log_notopenlogfile']   = 'Kon logbestand niet openen';
//$LN['log_']           = '';

// FAQ
$LN['faq_title']    = 'Frequent gestelde vragen';

//Manual
$LN['manual_title'] = 'Handleiding';

//admin users
$LN['users_title']          = 'Gebruikers';
$LN['users_allow_erotica']  = '18+ info toestaan';
$LN['users_allow_update']   = 'Updaten databases toestaan';
$LN['users_addnew']         = 'Voeg nieuwe gebruiker toe';
$LN['users_isadmin']        = 'Beheerder';
$LN['users_autodownload']   = 'Autodownload toestaan';
$LN['users_fileedit']       = 'Bewerk bestanden';
$LN['users_post']           = 'Uploader';
$LN['users_post_help']      = 'De gebruiker mag data posten op de news server';
$LN['users_resetpw']        = 'Reset en mail wachtwoord';
$LN['users_edit']           = 'Bewerk gebruiker';
$LN['users_delete']         = 'Verwijder gebruiker';
$LN['users_enable']         = 'Activeer gebruiker';
$LN['users_disable']        = 'Uitschakelen gebruiker';
$LN['users_rights']         = 'Setbewerker';
$LN['users_rights_help']    = 'Hiermee kan de gebruiker sets bewerken in de Aanbod-pagina';
$LN['users_last_active']    = 'Laatst actief';
//$LN['users_']             = '';

$LN['error_encryptedrar']       = 'Vercijferd rar bestand';
$LN['error_feedexists']         = 'Een RSS stroom met die naam bestaat al';
$LN['error_usercancel']         = 'Geannuleerd door gebruiker';
$LN['error_noadmin']            = 'Geen beheer privileges';
$LN['error_accessdenied']       = 'Geen toegang';
$LN['error_invalidfullname']    = 'Ongeldige volledige naam';
$LN['error_invalidusername']    = 'Ongeldig gebruikersnaam';
$LN['error_userexists']         = 'Gebruiker bestaat al';
$LN['error_invalidid']          = 'Ongeldig ID gegeven';
$LN['error_nosuchuser']         = 'Gebruiker bestaat niet';
$LN['error_nouserid']           = 'Geen gebruikers ID gegeven';
$LN['error_invalidchallenge']  = 'Er is mogelijk sprake van een gevalsifiseerde aanvraag (cross site request forgery). De actie is afgebroken (Druk op opniew laden en probeer het opnieuw).';
$LN['error_toomanydays']        = 'Er zijn slechts 24 uren in een dag';
$LN['error_toomanymins']        = 'Er zijn slechs 60 minuten in een uur';
$LN['error_bogusexptime']       = 'Bogus verouder tijd';
$LN['error_invalidupdatevalue'] = 'Ongeldige update waarde ontvangen';
$LN['error_nodlpath']           = 'Download pad niet gezet';
$LN['error_dlpathnotwritable']  = 'Download pad niet schrijfbaar';
$LN['error_setithere']          = 'Zet het hier';
$LN['error_nousers']            = 'Geen gebruikers gevonden. Voer het installatie script opnieuw uit.';
$LN['error_filenotallowed']     = 'Bestand openen niet toegestaan';
$LN['error_filenotfound']       = 'Bestand niet gevonden';
$LN['error_filereaderror']      = 'Bestand kon niet worden gelezen';
$LN['error_dirnotfound']        = 'Kan directory niet openen';
$LN['error_unknown_sort']       = 'Onbekende sorteervolgorde';
$LN['error_invalidlinescount']  = 'Regels moet numeriek zijn';
$LN['error_urddconnect']        = 'Kan geen verbinding leggen met de URD Daemon';
$LN['error_createdlfailed']     = 'Kan download niet maken';
$LN['error_setsnumberunknown']  = 'Kan het aantal sets niet bepalen';
$LN['error_noqueue']            = 'Geen rij gevonden...';
$LN['error_novalidaction']      = 'Geen geldige actie gevonden.';
$LN['error_readnzbfailed']      = 'Kan NZB bestand niet lezen';
$LN['error_nopartsinnzb']       = 'Geen artikelen gevonden in NZB bestand';
$LN['error_invalidgroup']       = 'Ongeldig groep; groep naam moet bestaan in /etc/group';
$LN['error_notanumber']         = 'Geen nummer';
$LN['error_filetoolarge']       = 'Bestand te groot om te downloaden';
//$LN['error_']         = '';

/// Transfers
$LN['transfers_title']          = 'Downloads';
$LN['transfers_importnzb']      = 'Importeer NZB bestand';
$LN['transfers_import']         = 'Importeer';
$LN['transfers_clearcompleted'] = 'Schoon afgelopen downloads op';
$LN['transfers_pauseall']       = 'Pauzeer alles';
$LN['transfers_continueall']    = 'Ga door met alles';
$LN['transfers_nzblocation']    = 'Externe NZB bestandslocatie';
$LN['transfers_nzblocationext'] = 'Dit kan een link zijn (http://) of een bestand op de server (/tmp/file.nzb)';
$LN['transfers_nzbupload']      = 'Upload een lokaal NZB bestand';
$LN['transfers_nzbuploadext']   = 'Wanneer het NZB bestand op uw computer staat, kunt u het hiermee versturen naar de URD server';
$LN['transfers_uploadnzb']      = 'NZB uploaden';
$LN['transfers_runparrar']      = 'Voer par2 en unrar uit';
$LN['transfers_add_setname']    = 'Voeg de setnaam toe aan de download directory';
$LN['transfers_status_ready']   = 'Startend';
$LN['transfers_status_queued']  = 'In wachtrij';
$LN['transfers_status_active']  = 'Downloadend';
$LN['transfers_status_finished']= 'Klaar';
$LN['transfers_status_cancelled']   = 'Geannuleerd';
$LN['transfers_status_paused']      = 'Gepauzeerd';
$LN['transfers_status_stopped']     = 'Gestopt';
$LN['transfers_status_postactive']  = 'Uploadend';
$LN['transfers_status_shutdown']    = 'Uitschakelend';
$LN['transfers_status_error']       = 'Fout';
$LN['transfers_status_complete']    = 'Nabewerking';
$LN['transfers_status_rarfailed']   = 'Inpakken mislukt';
$LN['transfers_status_unrarfailed'] = 'Uitpakken mislukt';
$LN['transfers_status_par2failed']  = 'Par2 reparatie mislukt';
$LN['transfers_status_cksfvfailed'] = 'Cksfv mislukt';
$LN['transfers_status_yyencodefailed']  = 'Yenc encoding mislukt';
$LN['transfers_status_dlfailed']        = 'Artikelen niet gevonden';
$LN['transfers_status_removed'] = 'Verwijderd';
$LN['transfers_status_failed']  = 'Mislukt';
$LN['transfers_status_running'] = 'Actief';
$LN['transfers_status_crashed'] = 'Gecrasht';

$LN['transfers_status_rarred']      = 'Gerarred';
$LN['transfers_status_par2ed']      = 'Par2 aangemaakt';
$LN['transfers_status_yyencoded']   = 'Yenc ge&euml;ncodeerd';
$LN['transfers_head_subject']       = 'Onderwerp';
$LN['transfers_posts']              = 'Uploads';
$LN['transfers_post_spot']          = 'Spot plaatsen';
$LN['transfers_post']               = 'Uploaden';
$LN['transfers_downloads']          = 'Downloads';
$LN['spots_post_started']       = 'Spot wordt geplaatst';

$LN['transfers_linkview']   = 'Bekijk bestanden';
$LN['transfers_linkstart']  = 'Start';
$LN['transfers_linkedit']   = 'Eigenschappen';

$LN['transfers_details']    = 'Download informatie';
$LN['transfers_name']       = 'Downloadnaam';
$LN['transfers_archpass']   = 'Uitpakwachtwoord';

$LN['transfers_head_started']   = 'Gestart';
$LN['transfers_head_dlname']    = 'Download naam';
$LN['transfers_head_progress']  = 'Voortgang';
$LN['transfers_head_speed']     = 'Snelheid';
$LN['transfers_head_username']  = 'Gebruiker';
$LN['transfers_head_options']   = 'Opties';

$LN['transfers_unrar']          = 'Unrar';
$LN['transfers_unpar']          = 'Unpar';
$LN['transfers_deletefiles']    = 'Wis bestanden';
$LN['transfers_subdl']          = 'Download ondertitels';
$LN['transfers_badrarinfo']     = 'Bekijk het rar logbestand';
$LN['transfers_badparinfo']     = 'Bekijk het par2 logbestand';

// Fatal error
$LN['fatal_error_title']    = 'Bericht';

$LN['licence_title']        = 'Licentie';

// admin_buttons
$LN['buttons_title']        = 'Zoekopties';
$LN['buttons_url']          = 'Zoek URL';
$LN['buttons_edit']         = 'Bewerk';
$LN['buttons_test']         = 'Test';
$LN['buttons_nobuttonid']   = 'Geen zoekoptie ID opgegeven';
$LN['buttons_invalidname']  = 'Ongeldige naam opgegeven';
$LN['buttons_invalidurl']   = 'Ongeldige URL opgegeven';
$LN['buttons_clicktest']    = 'Klik om te testen';
$LN['buttons_buttonnotfound']   = 'Er bestaat geen zoek optie met die naam';
$LN['buttons_editbutton']   = 'Een zoekoptie wijzigen';
$LN['buttons_addbutton']    = 'Een zoekoptieknop toevoegen';
$LN['buttons_buttonexists'] = 'Een zoekoptie met deze naam bestaat al';
//$LN['buttons_']       = '';

// login
$LN['login_title']          = 'Log aub in';
$LN['login_title2']         = 'Log in voor toegang tot';
$LN['login_jserror']        = 'Javascript is nodig om de URD website te gebruiken.';
$LN['login_oneweek']        = 'Een week lang';
$LN['login_onemonth']       = 'Een maand lang';
$LN['login_oneyear']        = 'Een jaar lang';
$LN['login_forever']        = 'Voor altijd';
$LN['login_closebrowser']   = 'Tot de browser wordt afgesloten';
$LN['login_login']          = 'Log in';
$LN['login_remember']       = 'Onthoud mij';
$LN['login_bindip']         = 'Koppel sessie aan IP adres';
$LN['login_forgot_password']    = 'Ik ben mijn wachtwoord vergeten';
$LN['login_register']       = 'Ik wil een account aanmaken';
$LN['login_failed']         = 'Je gebruikersnaam/wachtwoord combinatie was onjuist';

// browse
$LN['browse_allsets']       = 'Alle sets';
$LN['browse_interesting']   = 'Interessant';
$LN['browse_killed']        = 'Verborgen';
$LN['browse_nzb']           = 'NZB gecre&euml;erd';
$LN['browse_downloaded']    = 'Gedownload';
$LN['browse_addedsets']     = 'Toegevoegde sets';
$LN['browse_allgroups']     = 'Alle groepen';
$LN['browse_searchsets']    = 'Zoek in sets';
$LN['browse_addtolist']     = 'Voeg toe aan lijst';
$LN['browse_emptylist']     = 'Leeg lijst';
$LN['browse_savenzb']       = 'Download als NZB bestand';
$LN['browse_download']      = 'Download';
$LN['browse_subject']       = 'Setnaam';
$LN['browse_followlink']    = 'Volg link';
$LN['browse_age']           = 'Leeftijd';
$LN['browse_percent']       = '%';
$LN['browse_schedule_at']   = 'Start op';
$LN['browse_removeset']     = 'Verberg deze set';
$LN['browse_deleteset']     = 'Verwijder deze set';
$LN['browse_deletedsets']   = 'Sets verwijderd';
$LN['browse_deletedset']    = 'Set verwijderd';
$LN['browse_resurrectset']  = 'Breng deze set terug';
$LN['browse_toggleint']     = 'Wissel interessant';
$LN['browse_invalid_timestamp'] = 'Ongeldige tijdstip';
$LN['NZB_created']          = 'NZB-bestand aangemaakt';
$LN['NZB_file']             = 'NZB-bestand';
$LN['Image_file']           = 'Afbeeldingsbestand';
$LN['browse_mergesets']     = 'Sets samenvoegen';
$LN['browse_userwhitelisted'] = 'Gebruiker staat op de witte lijst';

$LN['browse_download_dir']  = 'Download directory';
$LN['browse_add_setname']   = 'Setnaam toevoegen';

// Preview
$LN['preview_autodisp']     = 'Bestand(en) zouden automatisch moeten verschijnen.';
$LN['preview_autofail']     = 'Als het te lang duurt kan je ook op deze link klikken';
$LN['preview_view']         = 'Klik hier om het NZB bestand te bekijken';
$LN['preview_header']       = 'Preview downloaden';
$LN['preview_nzb']          = 'Klik hier om met dit NZB bestand direct te downloaden';
$LN['preview_failed']       = 'Preview mislukt';

// FAQ
$LN['faq_content'][1] = array ('Wat is URD', 'URD is een programma om bestanden van usenet (nieuwsgroepen) te downloaden, d.m.v. een webinterface.'
    .' Het is volledig in PHP geschreven, maar maakt gebruik van enkele externe programma&#39;s om bepaalde taken uit te voeren.'
    .' Alle informatie wordt opgeslagen in een database (Bijv. MySQL of PostGreSQL). Individuele berichten die bij elkaar horen'
    .' worden samengevoegd tot sets, die met slechts enkele muisklikken te downloaden zijn. Ook is het mogelijk om NZB bestanden te'
    .' genereren of te gebruiken om mee te downloaden.'
    .' Als een download binnen is kan automatisch gecontroleerd worden of er geen fouten of ontbrekende delen zijn door middel van'
    .' par2 of sfv, en bestanden kunnen automatisch worden uitgepakt.'
    .' In de achtergrond gebruikt URD een zgn. daemon genaamd de URD Daemon (URDD). Deze daemon verzorgt voor'
    .' alle interactie met de nieuwsserver, en handeld ook het aanmaken van sets en verwerken van downloads af.'
    .' URD is beschikbaar onder de GPL 3 licentie. Zie het COPYING bestand voor meer informatie over deze licentie.');

$LN['faq_content'][2] = array ('Waar komt de naam vandaan?', 'URD is een teruggeredeneerde afkorting van Usenet Resource Downloader, en komt oorspronkelijk'
    .' uit de noorse mythologie en verwijst naar de Bron van URD. Dit is de heilige bron van water voor de wereldboom '
    .' Yggdrasil. De oude engelse term is Wyrd. Qua betekenis lijkt URD nog het meeste op Lot.');
$LN['faq_content'][3] = array ('Wat als het niet werkt?', 'Controleer eerst of de instellingen juist zijn, en of er communicatie is met de nieuwsserver. Kijk ook in de apache'
    .' en URD logbestanden (standaard: /tmp/urd.log). Mocht het een programmeerfout betreffen, rapporteer dit aub via de google code website. Anders kan je het op'
    .' het forum bespreken. Zie <a href="http://www.urdland.com/forum">URDLand</a>.');

$LN['faq_content'][4] = array ('Ondersteunt URD SSL?', 'Ja, vanaf versie 0.4 al.');
$LN['faq_content'][5] = array ('Ondersteunt URD nieuwsserver authenticatie?', 'Jazeker.');
$LN['faq_content'][6] = array ('Kunnen jullie deze nuttige toevoeging implementeren?', 'Op het <a href="http://www.urdland.com/forum">forum</a> kun je een verzoek invullen. Misschien zit het dan in de volgende versie.');
$LN['faq_content'][7] = array ('Kan de URD daemon op een ander systeem draaien dan de webinterface?', 'In het kort: Waarschijnlijk niet. De database zou wel eenvoudig op een ander systeem kunnen staan.');
$LN['faq_content'][8] = array('Kan URD met NZB bestanden werken', 'Ja dat is mogelijk. Sterker nog er zijn diverse methoden om NZB bestanden in URD te gebruiken. Ten eerste kunnen NZB files gebruikt worden om vanuit te downloaden. In de downloads pagina kan een lokaal NZB bestand worden ge&uuml;pload. Het aldaar ook mogelijk om een externe link naar een NZB bestand in te geven. Daarnaast worden in sommige usenet groepen ook NZB bestanden aangeboden. Deze kunnen door gebruik te maken van de preview optie worden gedownload en gelijk worden gebruikt om de data in het NZB bestand te downloaden. Tot slot is er in de bestanden sectie een mogelijkheid om gedownloade NZB bestanden direct te gebruiken ter download. Buiten de website om kan er ook gebruik worden gemaakt van een spool directory spool/gebruikersnaam. Elk bestand dat daar geplaatst wordt, wordt automatisch gedownload. Maar er is meer. URD kan ook zelf NZB bestanden aanmaken op basis van de indices van de newsgroepen. Dit werkt hetzelfde als downloaden, alleen moet dan het NZB knopje worden ingedrukt. Het bestand wordt dan opgeslagen in de directory nzb/gebruikersnaam.');
$LN['faq_content'][9] = array ('Hoe upgrade ik naar een nieuwere URD versie?', 'Momenteel kan dit nog niet automatisch. Hierdoor zul je het installatiescript opnieuw uit moeten voeren, waarbij'
    .' wordt aanbevolen om dezelfde database e.d. te gebruiken, en deze te vervangen door de optie "delete existing user and database".');
$LN['faq_content'][10] = array ('Welke licentie gebruikt URD?', 'De meeste code is GPL v3. Sommige stukjes zijn geleend van andere projecten en hebben een andere versie of licentie.');
$LN['faq_content'][11] = array ('Moet ik het tar.gz bestand downloaden of moet ik subversion gebruiken om URD te installeren?', 'Het is sterk aanbevolen om de officiele release te downloaden.'
    .' De Subversion-versie wordt actief ontwikkeld waardoor er bugs in kunnen zitten.');
$LN['faq_content'][12] = array ('Mijn vraag staat hier niet bij. Wat nu?', 'Laat een berichtje achter op het forum, zie <a href="http://www.urdland.com/forum">URD land</a>.');
$LN['faq_content'][13] = array('Ik wil graag iets doneren. Hoe?', 'Fantastisch! Een teken van waardering is altijd welkom. We hebben niet veel uitgaven, maar hosting kost ongeveer 50 euro per jaar. De eenvoudigste manier is door middel van PayPal. Er is een doneer knop <a href="http://urdland.com/cms/component/option,com_wrapper/Itemid,33/">hier</a>. Als je een andere manier wilt gebruiken, is het het eenvoudigst om een e-mail te sturen naar "dev@ urdland . com" of PM op het forum, dan wisselen we informatie uit zoals adressen of bankgegevens.');

// manual
$LN['manual_content'][1] = array ('Algemeen', 'De meeste onderdelen van de URD website hebben directe hulp information in popups. De muis over de tekst of link bewegen laat de hulp zien.');

$LN['manual_content'][2] = array ('Niewsgroepen', 'Na installatie kun je inloggen op de URD webinterface. Klik dan op nieuwsgroepen en zoek de nieuwsgroepen waarop je wilt abonneren. Als er geen nieuwsgroepen gevonden worden, ga dan naar de beheer pagina en klik op "update nieuwsgroepen lijst". Als dat niet helpt controleer dan de instellingen. In het nieuwsgroepen overzicht geeft de "opschonen" kolom aan na hoeveel dagen berichten worden gewist. Het is ook mogelijk automatisch de nieuwsgroepen te verversen. Voer selecteer hiervoor "dagen", "uren" of "weken" en vul het aantal in alsmede ook de tijd waarop de update moet plaatsvinden. Klik dan op de "submit" knop. Verwijderen van een automatische update gaat door de tijd te verwijderen en weer op "submit" te drukken.');

$LN['manual_content'][3] = array ('RSS Bronnen', 'Daarnaast is het ook mogelijk om te abonneren op RSS bronnen. Deze bronnen moeten eerst worden toegevoegd, middels de knop nieuwe toevoegen en de benodigde informatie in te vullen, waaronder de URL van de RSS bron. Verder werkt het nagenoeg gelijk aan de nieuwsgroepen.');

$LN['manual_content'][4] = array ('Aanbod', 'Na de update van de geabonneerde nieuwsgroepen kun je bekijken welke sets er zijn in het "Aanbod" scherm. Klik op de "?" om meer informatie te krijgen over een set. De kleine "+" wordt gebruikt om een set te markeren om te downloaden. Na sets te hebben gemarkeerd, kun je op de "\/" knop drukken om een download te starten. De NZB knop slaat het NZB bestand horende bij de sets op. De "x" verwijdert alle geselecteerde sets weer. De knoppen rechts kunnen gebruikt worden om op een geselecteerde tekst te zoeken. De wijzig knop kan gebruikt worden om meer informatie over de set in te vullen om te delen met andere URD users.');

$LN['manual_content'][5] = array ('Downloads', 'Als een download is gestart, is deze te volgen in de downloads sectie. Dit toont de status, maar kan ook gebruikt worden om downloads te hernoemen, pauseren, stoppen, of opnieuw te starten. Een link naar de map waar de resultaten staan is ook aanwezig.');

$LN['manual_content'][6] = array ('Bestanden', 'Via de bestanden pagina zijn alle gedownloade bestanden zichtbaar en kunnen worden bekeken, gehernoemd of verwijderd. Ook is het mogelijk om alle bestanden als een archief te downloaden.');

$LN['manual_content'][7] = array ('Beheer', 'De beheer pagina&#39;s bieden de meeste beheer taken zoals het starten en stoppen van de daemon, alle taken stoppen, pauseren of opstarten, opschonen van de database, enz. Het kan ook worden gebruikt om oude berichten uit de niewsgroep te verwijderen, om gebruikers aan te maken en te verwijderen en om zoek knoppen te maken. Ook kan er een overzicht van alle taken worden verkregen en van de status van URD. Ook de configuratie van URD is hierin ondergebracht.');

$LN['manual_content'][8] = array ('Configuration','Deze pagina kan gebruikt worden om de meeste settings van URD te wijzigen.');
$LN['manual_content'][9] = array ('Usenet servers','Hier kun je de usenet servers configureren. Er zijn twee manieren om een usenet server te gebruiken. 1. Als een binary download servers, dat wordt bepaald door de aan/uit knop. Meer dan 1 server mag worden geactiveerd. 2. Als een indexerings server, hiervan mag er slechts een zijn. Selecteer dit met de primair checkbox');
$LN['manual_content'][10] = array ('Beheer','Hier kun je een aantal basis acties op urdd uitvoeren zoals afsluiten, opstarten, database opschonen, alle nieuwsgroepen verwijderen');
$LN['manual_content'][11] = array ('Taken','Alle actieve of in de wachtrij geplaatse acties worden hier getoond.');
$LN['manual_content'][12] = array ('Agenda','URDD kan taken uitvoeren op een gegeven tijdstip of datum. Hier wordt een overzicht van al deze ingeplande taken gegeven.');
$LN['manual_content'][13] = array ('Gebruikers','De gebruikers pagina is voor het beheren van gebruikers accounts. De rechten van de gebruiker kunnen worden aangepast, gebruikers kunnen worden toegevoegd, verwijderd of gedeactiveerd, enz.');
$LN['manual_content'][14] = array ('Knoppen','Dit zijn de zoekopties op de Aanbod pagina. The zoek URL moet een $q bevatten; deze wordt vervangen bij het uitvoeren door de gewenste zoekstring');
$LN['manual_content'][15] = array ('Logs','Hier kun je het log bestand van URD bekijken en er in zoeken e.d. Dit is de eerste plek om te kijken als er iets niet goed is gegaan.');

$LN['manual_content'][16] = array ('Voorkeuren', 'De voorkeuren pagina wordt gebruikt voor persoonlijke instellingen.');

$LN['manual_content'][17] = array ('Status overzicht', 'Links op het scherm is altijd de status van de URD daemon te zien en kan hier aan en uit worden gezet. Het laat ook de huidige acties van URD zien en de beschikbare schijfruimte, evenals de huidig ingelogde gebruiker. Als er een nieuwe versie beschikbaar is, is dat ook hier te zien.');

$LN['manual_content'][18] = array ('Het werkt niet', 'Kijk eerst of je kan verbinden met een NNTP server. Zo ja start dan de taak opnieuw maar zet het log niveau op debug. Controlleer ok de URD log (/tmp/urd.log) en de apache log voor foutmelingen. Als er sprake is van een bug, rapporteer deze dan op de google code site, of discusieer op het  <a href="http://www.urdland.com/forum/">forum</a>. Voeg zoveel mogelijk informatie toe, zoals relevante loggegevens, foutmeldingen en instellingen. De <a href="debug.php">debug pagina</a> kan ook worden gebruikt om alle informatie van urdd op te vragen.');

// ajax_showsetinfo:
$LN['showsetinfo_postedin'] = 'Gepost in';
$LN['showsetinfo_postedby'] = 'Gepost door';
$LN['showsetinfo_size']     = 'Totale grootte';
$LN['showsetinfo_shouldbe'] = 'Hoort te zijn';
$LN['showsetinfo_par2']     = 'Par2';
$LN['showsetinfo_setname']  = 'Set naam';
$LN['showsetinfo_typeofbinary'] = 'Type binary';

//basket
$LN['basket_totalsize']     = 'Totale omvang';
$LN['basket_setname']       = 'Download naam';

// usenet servers
$LN['usenet_title']         = 'Usenet servers';
$LN['usenet_hostname']      = 'Hostnaam';
$LN['usenet_port']          = 'Poort';
$LN['usenet_secport']       = 'SSL poort';
$LN['usenet_authentication']    = 'Auth';
$LN['usenet_username']      = 'Gebruikersnaam';
$LN['usenet_password']      = 'Wachtwoord';
$LN['usenet_threads']       = 'Verbindingen';
$LN['usenet_connection']    = 'Versleuteling';
$LN['usenet_needsauthentication']   = 'Authenticatie nodig';
$LN['usenet_addnew']        = 'Nieuwe toevoegen';
$LN['usenet_nrofthreads']   = 'Aantal verbindingen';
$LN['usenet_connectiontype']    = 'Type versleuteling';
$LN['usenet_priority']      = 'Prioriteit';
$LN['usenet_priority_msg']  = 'Prioriteit: 1 hoogste; 100 laagste; 0 inactive';
$LN['usenet_enable']        = 'Zet aan';
$LN['usenet_disable']       = 'Zet uit';
$LN['usenet_delete']        = 'Verwijder server';
$LN['usenet_edit']          = 'Wijzig server';
$LN['usenet_preferred']     = 'Primair';
$LN['usenet_set_preferred'] = 'Zet primair';
$LN['usenet_indexing']      = 'Indexeren';
$LN['usenet_addserver']     = 'Voeg een nieuwe usenet server toe';
$LN['usenet_editserver']    = 'Wijzig een usenet server';
$LN['usenet_compressed_headers']        = 'Gecomprimeerde headers';
$LN['usenet_compressed_headers_msg']    = 'Gebruik gecomprimeerde headers voor het updaten van nieuwsgroepen. Het XZVER commando wordt niet door alle servers ondersteund.';
$LN['usenet_posting']       = 'Uploaden';
$LN['usenet_posting_msg']   = 'Uploaden toestaan';
//$LN['usenet_']            = '';

$LN['usenet_name_msg']      = 'De naam van de usenet server';
$LN['usenet_hostname_msg']  = 'De hostnaam van de usenet server (noot: IPv6 adressen moeten tussen [] worden gezet)';
$LN['usenet_port_msg']      = 'Het poortnummer dat de usenet server gebruikt voor onvercijferde verbindingen';
$LN['usenet_secport_msg']   = 'Het poortnummer dat de usenet server gebruikt voor vercijferde verbindingen met SSL of TLS';
$LN['usenet_needsauthentication_msg']   = 'Vink als de usenet server authenticatie vereist';
$LN['usenet_username_msg']  = 'De gebruikersnaam als de usenet server authenticatie vereist';
$LN['usenet_password_msg']  = 'Het wachtwoord als de usenet server authenticatie vereist';
$LN['usenet_nrofthreads_msg']       = 'Het maximum aantal verbindingen dat parallel gebruikt wordt voor deze server';
$LN['usenet_connectiontype_msg']    = 'De vercijfering die gebruikt wordt om te verbinden met de usenet server';
$LN['usenet_preferred_msg']         = 'Dit is de primaire server om groepen te indexeren';
$LN['usenet_set_preferred_msg']     = 'Gebruik deze server als primaire server om groepen te indexeren';

$LN['forgot_title']     = 'Wachtwoord vergeten';
$LN['forgot_mail']      = 'Verzenden';
$LN['forgot_sent']      = 'Wachtwoord verzonden';
//$LN['forgot_']            = '';

$LN['browse_tag_setname']       = 'Setnaam';
$LN['browse_tag_name']          = 'Naam';
$LN['browse_tag_year']          = 'Jaar';
$LN['browse_tag_lang']          = 'Taal';
$LN['browse_tag_sublang']       = 'Ondertitels';
$LN['browse_tag_artist']        = 'Artiest';
$LN['browse_tag_quality']       = 'Kwaliteit';
$LN['browse_tag_runtime']       = 'Lengte';
$LN['browse_tag_movieformat']   = 'Filmformaat';
$LN['browse_tag_audioformat']   = 'Audioformaat';
$LN['browse_tag_musicformat']   = 'Muziekformaat';
$LN['browse_tag_imageformat']   = 'Afbeeldingsformaat';
$LN['browse_tag_softwareformat']= 'Softwareformaat';
$LN['browse_tag_moviegenre']    = 'Filmgenre';
$LN['browse_tag_musicgenre']    = 'Muziekgenre';
$LN['browse_tag_softwaregenre'] = 'Softwaregenre';
$LN['browse_tag_gameformat']    = 'Spelformaat';
$LN['browse_tag_gamegenre']     = 'Spelgenre';
$LN['browse_tag_imagegenre']    = 'Afbeeldingsgenre';
$LN['browse_tag_os']            = 'Besturingssysteem';
$LN['browse_tag_genericgenre']  = 'Genre';
$LN['browse_tag_episode']       = 'Aflevering';
$LN['browse_tag_moviescore']    = 'Filmscore';
$LN['browse_tag_score']         = 'Waardering';
$LN['browse_tag_musicscore']    = 'Muziekscore';
$LN['browse_tag_movielink']     = 'Filmlink';
$LN['browse_tag_link']          = 'Link';
$LN['browse_tag_musiclink']     = 'Musieklink';
$LN['browse_tag_serielink']     = 'Serielink';
$LN['browse_tag_xrated']        = '18+';
$LN['browse_tag_note']          = 'Opmerkingen';
$LN['browse_tag_author']        = 'Auteur';
$LN['browse_tag_ebookformat']   = 'eBook formaat';
$LN['browse_tag_password']      = 'Uitpakwachtwoord';
$LN['browse_tag_copyright']     = 'Auteursrechtelijk beschermd';

$LN['quickmenu_setsearch']      = 'Zoek';
$LN['quickmenu_addblacklist']   = 'Spotter op zwarte lijst plaatsen';
$LN['quickmenu_addposterblacklist']   = 'Spotter op zwarte lijst plaatsen';
$LN['quickmenu_addglobalblacklist']   = 'Spotter op zwarte lijst globale plaatsen';
$LN['quickmenu_addglobalwhitelist']   = 'Spotter op witte lijst globale plaatsen';
$LN['quickmenu_addwhitelist']   = 'Spotter op witte lijst plaatsen';
$LN['quickmenu_report_spam']    = 'Meld spot als spam';
$LN['quickmenu_comment_spot']   = 'Plaats een commentaar op de spot';
$LN['quickmenu_editspot']       = 'Bewerk spot';
$LN['quickmenu_setshowesi']     = 'Toon set info';
$LN['quickmenu_seteditesi']     = 'Bewerk set info';
$LN['quickmenu_setguessesi']    = 'Raad set info';
$LN['quickmenu_setbasketguessesi']  = 'Raad set info voor alles in de download mand';
$LN['quickmenu_setguessesisafe']= 'Raad set info en valideer';
$LN['quickmenu_setpreviewnfo']  = 'Preview NFO bestand';
$LN['quickmenu_setpreviewimg']  = 'Preview beeldbestand';
$LN['quickmenu_setpreviewnzb']  = 'Preview NZB bestand';
$LN['quickmenu_setpreviewvid']  = 'Preview video bestand';
$LN['quickmenu_add_search']     = 'Automatisch markeren';
$LN['quickmenu_add_block']      = 'Automatisch verbergen';

$LN['blacklist_spotter']        = 'Spotter op de zwarte lijst zetten?';
$LN['whitelist_spotter']        = 'Spotter op de witte lijst zetten?';

$LN['stats_title']  = 'Statistieken';
$LN['stats_dl']     = 'Downloads';
$LN['stats_pv']     = 'Previews';
$LN['stats_im']     = 'Ge&iuml;mporteerde NZB bestanden';
$LN['stats_gt']     = 'Gedownloade NZB bestanden';
$LN['stats_wv']     = 'Web views';
$LN['stats_ps']     = 'Uploads';
$LN['stats_total']  = 'Totale omvang';
$LN['stats_number'] = 'Aantal';
$LN['stats_user']   = 'Gebruiker';
$LN['stats_overview']   = 'Overzicht';

$LN['stats_spotsbymonth'] = 'Spots per maand';
$LN['stats_spotsbyweek']  = 'Spots per week';
$LN['stats_spotsbyhour']  = 'Spots per uur';
$LN['stats_spotsbydow']   = 'Spots per dag van de week';

$LN['feeds_title']  = 'RSS bronnen';
$LN['feeds_rss']    = 'RSS bronnen';
$LN['feeds_auth']   = 'Auth';
$LN['feeds_tooltip_active']     = 'RSS bron is actief';
$LN['feeds_tooltip_name']       = 'Naam van de RSS bron';
$LN['feeds_tooltip_posts']      = 'Aantal links in de RSS bron';
$LN['feeds_tooltip_lastupdated']    = 'Laatste update tijdstip';
$LN['feeds_tooltip_expire']     = 'Opschoontijd in dagen';
$LN['feeds_tooltip_visible']    = 'RSS bron is zichtbaar';
$LN['feeds_tooltip_auth']   = 'RSS server vereist authenticatie';
$LN['feeds_lastupdated']    = 'Laatst ge&uuml;pdatet';
$LN['feeds_expire_time']    = 'Opschoontijd';
$LN['feeds_visible']        = 'Zichtbaar';
$LN['feeds_tooltip_autoupdate']     = 'Automatisch verversen';
$LN['feeds_action']         = 'Acties';
$LN['feeds_autoupdate']     = 'Auto ververs';
$LN['feeds_searchtext']     = 'Zoek in alle beschikbare RSS bronnen';
$LN['feeds_url']            = 'URL';
$LN['feeds_tooltip_url']    = 'URL';
$LN['feeds_tooltip_uepev']  = 'Wijzigen/Verversen/Opschonen/Legen/Verwijderen';
$LN['feeds_edit']       = 'Wijzig';
$LN['feeds_addfeed']    = 'Nieuwe bron toevoegen';
$LN['feeds_editfeed']   = 'Bron wijzigen';
$LN['feeds_allgroups']  = 'Alle bronnen';
//$LN['feeds_']     = '';
$LN['feeds_hide_empty'] = 'Inactieve bronnen verbergen';
$LN['menurssfeeds']     = 'RSS bronnen';
$LN['menuspots']        = 'Spots';
$LN['menu_overview']    = 'Instellingen';
$LN['menursssets']      = 'RSS aanbod';
$LN['menugroupsets']    = 'Groep aanbod';

$LN['loading_files']    = 'Bestanden worden geladen... wachten a.u.b.';
$LN['loading']          = 'Wordt geladen... wachten a.u.b.';

$LN['error_invalidfeedid']  = 'Ongeldig feed ID';
$LN['error_feednotfound']   = 'Feed niet gevonden';

$LN['config_formatstrings'] = 'Formaat string';
$LN['config_formatstring']  = 'Formaat string voor';

$LN['post_message']         = 'Post een bericht';
$LN['post_messagetext']     = 'Berichttekst';
$LN['post_messagetextext']  = 'De inhoud van het bericht';
$LN['post_newsgroupext2']   = 'De nieuwsgroep waar het bericht naar wordt gestuurd';
$LN['post_subjectext2']     = 'Het onderwerp in het bericht';

$LN['settype'][urd_extsetinfo::SETTYPE_UNKNOWN]     = $LN['config_formatstring'] . ' Onbekend';
$LN['settype'][urd_extsetinfo::SETTYPE_MOVIE]       = $LN['config_formatstring'] . ' Film';
$LN['settype'][urd_extsetinfo::SETTYPE_ALBUM]       = $LN['config_formatstring'] . ' Album';
$LN['settype'][urd_extsetinfo::SETTYPE_IMAGE]       = $LN['config_formatstring'] . ' Plaatje';
$LN['settype'][urd_extsetinfo::SETTYPE_SOFTWARE]    = $LN['config_formatstring'] . ' Software';
$LN['settype'][urd_extsetinfo::SETTYPE_TVSERIES]    = $LN['config_formatstring'] . ' TV serie';
$LN['settype'][urd_extsetinfo::SETTYPE_EBOOK]       = $LN['config_formatstring'] . ' E-book';
$LN['settype'][urd_extsetinfo::SETTYPE_GAME]        = $LN['config_formatstring'] . ' Spel';
$LN['settype'][urd_extsetinfo::SETTYPE_TVSHOW]      = $LN['config_formatstring'] . ' TV Show';
$LN['settype'][urd_extsetinfo::SETTYPE_DOCUMENTARY] = $LN['config_formatstring'] . ' Documentaire';
$LN['settype'][urd_extsetinfo::SETTYPE_OTHER]       = $LN['config_formatstring'] . ' Anders';

$LN['newcategory']          = 'Nieuwe categorie';
$LN['nocategory']           = 'Geen categorie';
$LN['category']             = 'Categorie';
$LN['categories']           = 'Categorie&euml;s';
$LN['name']                 = 'Naam';
$LN['editcategories']       = 'Categorie bewerken';
$LN['ng_tooltip_category']  = 'Categorie';

$LN['settype_syntax'] = '%(n.mc); waar <i>()</i> een optionele omsluiting is, namelijk (), [] or {}; <i>n</i> een optionele padding waarde, <i>.m</i> een optionele maximale lengte, <i>c</i> een noodzakelijk karakter hieronder gespecificeerd (gebruik %% om een % weer te geven, zie ook de php documentatie voor sprintf):<br/><br/>';

$LN['settype_msg'][urd_extsetinfo::SETTYPE_UNKNOWN] = $LN['settype_syntax'] . 'Onbekend settype:<br/>%n: naam<br/>%t: set type<br/>%T: type afhankelijk icoon <br/>%P: wachtwoord afgescherm<br/>%C: auteursrechtelijk beschermd';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_MOVIE] = $LN['settype_syntax'] . 'Film settype: <br/>%n: naam<br/>%t: set type<br/>%T: type afhankelijk icoon<br/>%y: jaar<br/>%m: video formaat<br/>%a: audio formaat<br/>%l: taal<br/>%s: ondertitel taal<br/>%x: 18+<br/>%N: opmerkingen<br/>%q: kwaliteit<br/>%P: wachtwoord afgescherm<br/>%C: auteursrechtelijk beschermd<br/>';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_ALBUM] = $LN['settype_syntax'] . 'Album settype: <br/>%n: naam<br/>%t: set type<br/>%T: type afhankelijk icoon<br/>%y: jaar <br/>%f: audio formaat<br/>%g: genre<br/>%N: opmerkingen<br/>%q: kwaliteit<br/>%P: wachtwoord afgescherm<br/>%C: auteursrechtelijk beschermd<br/>';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_IMAGE] = $LN['settype_syntax'] . 'Plaatje settype: <br/>%n: naam<br/>%t: set type<br/>%T: type afhankelijk icoon <br/>%f: beeldformaat<br/>%g: genre<br/>%N: opmerkingen<br/>%q: kwaliteit<br/>%x: 18+<br/>%P: wachtwoord afgescherm<br/>%C: auteursrechtelijk beschermd';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_SOFTWARE] = $LN['settype_syntax'] . 'Software settype: <br/>%n: naam<br/>%t: set type<br/>%T: afhankelijk icoon icon<br/>%o: besturingssysteem <br/>%q: kwaliteit<br/>%N: opmerkingen<br/>%P: wachtwoord afgescherm<br/>%C: auteursrechtelijk beschermd';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_TVSERIES] = $LN['settype_syntax'] . 'TV serie settype: <br/>%n: naam<br/>%t: set type<br/>%T: afhankelijk icoon icon<br/>%e: episode<br/>%m: video formaat<br/>%a: audio formaat<br/>%x: 18+<br/>%q: kwaliteit<br/>%N: opmerkingen<br/>%P: wachtwoord afgescherm<br/>%C: auteursrechtelijk beschermd';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_EBOOK] = $LN['settype_syntax'] . 'E-book settype: <br/>%n: naam<br/>%t: set type<br/>%T: type afhankelijk icoon<br/>%A: auteur<br/>%y: jaar<br/>%f: formaat<br/>%q: kwaliteit<br/>%g: genre<br/>%N: opmerkingen<br/>%P: wachtwoord afgescherm<br/>%C: auteursrechtelijk beschermd';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_GAME] = $LN['settype_syntax'] . 'Spel settype: <br/>%n: naam<br/>%t: set type<br/>%T: type afhankelijk icoon<br/>%A: auteur<br/>%y: jaar<br/>%f: formaat<br/>%q: kwaliteit<br/>%g: genre<br/>%N: opmerkingen<br/>%P: wachtwoord afgescherm<br/>%C: auteursrechtelijk beschermd';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_TVSHOW] = $LN['settype_syntax'] . 'TV Show settype: <br/>%n: naam<br/>%t: set type<br/>%T: type afhankelijk icoon<br/>%m: video formaat<br/>%y: jaar<br/>%e: episode<br/>%f: formaat<br/>%q: kwaliteit<br/>%g: genre<br/>%N: opmerkingen<br/>%P: wachtwoord afgescherm<br/>%C: auteursrechtelijk beschermd';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_DOCUMENTARY] = $LN['settype_syntax'] . 'Documentaire settype: <br/>%n: naam<br/>%t: set type<br/>%T: type afhankelijk icoon<br/>%A: auteur<br/>%y: jaar<br/>%f: film formaat<br/>%q: kwaliteit<br/>%g: genre<br/>%N: opmerkingen<br/>%P: wachtwoord afgescherm<br/>%C: auteursrechtelijk beschermd';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_OTHER] = $LN['settype_syntax'] . 'Ander settype: <br/>%n: naam<br/>%t: set type<br/>%T: type afhankelijk icoon<br/>%P: wachtwoord afgescherm<br/>%C: auteursrechtelijk beschermd<br/>';

$LN['spots_allcategories']     = 'Alle categori&euml;n';
$LN['spots_allsubcategories']  = 'Alle subcategorie&euml;n';
$LN['spots_subcategories']     = 'Subcategorie&euml;n';
$LN['spots_tag']               = 'Tag';
$LN['pref_spots_category_mapping']  = 'Spots categorie afbeelding voor';
$LN['pref_spots_category_mapping_msg']  = 'De afbeelding van spotscategorie&euml;n op URD categorien';

$LN['pref_custom_values']       = 'Gebruikerswaardes';
$LN['pref_custom']              = 'Gebruikerswaarde';
$LN['config_custom']            = 'Gebruikerswaarde';
$LN['pref_custom_msg']          = 'Gebruikers waardes die gebruikt kunnen worden in  scripts';

$LN['spots_other']       = 'Anders';
$LN['spots_all']         = 'Alles';
$LN['spots_image']       = 'Beeld';
$LN['spots_sound']       = 'Geluid';
$LN['spots_game']        = 'Spellen';
$LN['spots_application'] = 'Applicaties';
$LN['spots_format']      = 'Formaat';
$LN['spots_source']      = 'Bron';
$LN['spots_language']    = 'Taal';
$LN['spots_genre']       = 'Genre';
$LN['spots_bitrate']     = 'Bitrate';
$LN['spots_platform']    = 'Platform';
$LN['spots_type']        = 'Type';
//$LN['spots_']           = '';

$LN['spots_film']        = 'Film';
$LN['spots_series']      = 'Serie';
$LN['spots_book']        = 'Boek';
$LN['spots_erotica']     = 'Erotiek';

$LN['spots_album']       = 'Album';
$LN['spots_liveset']     = 'Liveset';
$LN['spots_podcast']     = 'Podcast';
$LN['spots_audiobook']   = 'Luisterboek';
$LN['spots_divx']        = 'DivX';
$LN['spots_wmv']         = 'WMV';
$LN['spots_mpg']         = 'MPG';
$LN['spots_dvd5']        = 'DVD5';
$LN['spots_hdother']     = 'HD Anders';
$LN['spots_ebook']       = 'E-boek';
$LN['spots_bluray']      = 'Blu-ray';
$LN['spots_hddvd']       = 'HD DVD';
$LN['spots_wmvhd']       = 'WMVHD';
$LN['spots_x264hd']      = 'x264HD';
$LN['spots_dvd9']        = 'DVD9';
$LN['spots_cam']         = 'Cam';
$LN['spots_svcd']        = '(S)VCD';
$LN['spots_promo']       = 'Promo';
$LN['spots_retail']      = 'Retail';
$LN['spots_tv']          = 'TV';
$LN['spots_satellite']   = 'Satelliet';
$LN['spots_r5']          = 'R5';
$LN['spots_telecine']    = 'Telecine';
$LN['spots_telesync']    = 'Telesync';
$LN['spots_scan']        = 'Scan';

$LN['spots_subs_non']      = 'Geen ondertitels';
$LN['spots_subs_nl_ext']   = 'Nederlandse ondertitels (extern)';
$LN['spots_subs_nl_incl']  = 'Nederlandse ondertitels (hardcoded)';
$LN['spots_subs_eng_ext']  = 'Engelse ondertitels (extern)';
$LN['spots_subs_eng_incl'] = 'Engelse ondertitels (hardcoded)';
$LN['spots_subs_nl_opt']   = 'Nederlandse ondertitels (instelbaar)';
$LN['spots_subs_eng_opt']  = 'Engelse ondertitels (instelbaar)';
$LN['spots_false']         = 'False';
$LN['spots_lang_eng']      = 'Engels gesproken';
$LN['spots_lang_nl']       = 'Nederlands gesproken';
$LN['spots_lang_ger']      = 'Duits gesproken';
$LN['spots_lang_fr']       = 'Frans gesproken';
$LN['spots_lang_es']       = 'Spaans gesproken';
$LN['spots_lang_asian']    = 'Aziatisch gesproken';

$LN['spots_action']        = 'Aktie';
$LN['spots_adventure']     = 'Avontuur';
$LN['spots_animation']     = 'Animatie';
$LN['spots_cabaret']       = 'Cabaret';
$LN['spots_comedy']        = 'Komedie';
$LN['spots_crime']         = 'Misdaad';
$LN['spots_documentary']   = 'Documentaire';
$LN['spots_drama']         = 'Drama';
$LN['spots_family']        = 'Familie';
$LN['spots_fantasy']       = 'Fantasie';
$LN['spots_filmnoir']      = 'Film Noir';
$LN['spots_tvseries']      = 'TV Serie';
$LN['spots_horror']        = 'Horror';
$LN['spots_music']         = 'Muziek';
$LN['spots_musical']       = 'Musical';
$LN['spots_mystery']       = 'Mysterie';
$LN['spots_romance']       = 'Romantiek';
$LN['spots_scifi']         = 'Science fiction';
$LN['spots_sport']         = 'Sport';
$LN['spots_short']         = 'Korte film';
$LN['spots_thriller']      = 'Thriller';
$LN['spots_war']           = 'Oorlog';
$LN['spots_western']       = 'Western';
$LN['spots_ero_hetero']    = 'Erotiek (hetero)';
$LN['spots_ero_gaymen']    = 'Erotiek (homo)';
$LN['spots_ero_lesbian']   = 'Erotiek (lesbisch)';
$LN['spots_ero_bi']        = 'Erotiek (biseksueel)';
$LN['spots_asian']         = 'Aziatisch';
$LN['spots_anime']         = 'Anime';
$LN['spots_cover']         = 'Cover';
$LN['spots_comics']        = 'Comics';
$LN['spots_cartoons']      = 'Tekenfilm';
$LN['spots_children']      = 'Kinderfilm';

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
$LN['spots_compilation']   = 'Compilatie';
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
$LN['spots_320kbit']       = '320 kbit';
$LN['spots_lossless']      = 'Lossless';

$LN['spots_blues']         = 'Blues';
$LN['spots_compilation']   = 'Compilatie';
$LN['spots_cabaret']       = 'Cabaret';
$LN['spots_dance']         = 'Dance';
$LN['spots_various']       = 'Diversen';
$LN['spots_hardcore']      = 'Hardcore';
$LN['spots_international'] = 'Internationaal';
$LN['spots_jazz']          = 'Jazz';
$LN['spots_children']      = 'Jeugd';
$LN['spots_classical']     = 'Klassiek';
$LN['spots_smallarts']     = 'Kleinkunst';
$LN['spots_netherlands']   = 'Nederlands';
$LN['spots_newage']        = 'New Age';
$LN['spots_pop']           = 'Pop';
$LN['spots_soul']          = 'R&amp;B';
$LN['spots_hiphop']        = 'Hiphop';
$LN['spots_reggae']        = 'Reggae';
$LN['spots_religious']     = 'Religieus';
$LN['spots_rock']          = 'Rock';
$LN['spots_soundtracks']   = 'Soundtrack';
$LN['spots_hardstyle']     = 'Hardstyle';
$LN['spots_asian']         = 'Aziatisch';
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
$LN['spots_navigation']    = 'Navigatie';
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
$LN['spots_action']        = 'Action';
$LN['spots_adventure']     = 'Avontuur';
$LN['spots_strategy']      = 'Strategie';
$LN['spots_roleplay']      = 'Rollenspel';
$LN['spots_simulation']    = 'Simulatie';
$LN['spots_race']          = 'Race';
$LN['spots_flying']        = 'Vliegen';
$LN['spots_shooter']       = 'First Person Shooter';
$LN['spots_platform']      = 'Platform';
$LN['spots_sport']         = 'Sport';
$LN['spots_children']      = 'Kinderen / Jeugd';
$LN['spots_puzzle']        = 'Puzzel';
$LN['spots_boardgame']     = 'Bordspel';
$LN['spots_cards']         = 'Kaarten';
$LN['spots_education']     = 'Educatie';
$LN['spots_music']         = 'Muziek';
$LN['spots_family']        = 'Familie';

$LN['spots_audioedit']     = 'Geluidsbewerking';
$LN['spots_videoedit']     = 'Videobewerking';
$LN['spots_graphics']      = 'Graphisch ontwerp';
$LN['spots_cdtools']       = 'CD tools';
$LN['spots_mediaplayers']  = 'Media spelers';
$LN['spots_rippers']       = 'Rippers en  encoders';
$LN['spots_plugins']       = 'Plugins';
$LN['spots_database']      = 'Databases';
$LN['spots_email']         = 'E-mail software';
$LN['spots_photo']         = 'Fotobewerking';
$LN['spots_screensavers']  = 'Screensavers';
$LN['spots_skins']         = 'Skins software';
$LN['spots_drivers']       = 'Drivers';
$LN['spots_browsers']      = 'Browsers';
$LN['spots_downloaders']   = 'Download managers';
$LN['spots_filesharing']   = 'Filesharing';
$LN['spots_usenet']        = 'Usenet software';
$LN['spots_rss']           = 'RSS software';
$LN['spots_ftp']           = 'FTP software';
$LN['spots_firewalls']     = 'Firewalls';
$LN['spots_antivirus']     = 'Anti-virus';
$LN['spots_antispyware']   = 'Anti-spyware';
$LN['spots_optimisation']  = 'Optimilisatie software';
$LN['spots_security']      = 'Security software';
$LN['spots_system']        = 'Systeem software';
$LN['spots_educational']   = 'Educatief';
$LN['spots_office']        = 'Kantoor';
$LN['spots_internet']      = 'Internet';
$LN['spots_communication'] = 'Communicatie';
$LN['spots_development']   = 'Ontwikkeling';
$LN['spots_spotnet']       = 'Spotnet';
//$LN['spots_']             = '';

$LN['spots_daily']          = 'Dagblad';
$LN['spots_magazine']       = 'Magazine';
$LN['spots_comic']          = 'Stripboek';
$LN['spots_study']          = 'Studie';
$LN['spots_business']       = 'Zakelijk';
$LN['spots_economy']        = 'Economie';
$LN['spots_computer']       = 'Computer';
$LN['spots_hobby']          = 'Hobby';
$LN['spots_cooking']        = 'Koken';
$LN['spots_crafts']         = 'Knutselen';
$LN['spots_needlework']     = 'Handwerken';
$LN['spots_health']         = 'Gezondheid';
$LN['spots_history']        = 'Geschiedenis';
$LN['spots_psychology']     = 'Psychologie';
$LN['spots_science']        = 'Wetenschap';
$LN['spots_woman']          = 'Vrouw';
$LN['spots_religion']       = 'Religie';
$LN['spots_novel']          = 'Roman';
$LN['spots_biography']      = 'Biografie';
$LN['spots_detective']      = 'Detective';
$LN['spots_animals']        = 'Dieren';
$LN['spots_humour']         = 'Humor';
$LN['spots_travel']         = 'Reizen';
$LN['spots_truestory']      = 'Waargebeurd';
$LN['spots_nonfiction']     = 'Non-fictie';
$LN['spots_politics']       = 'Politiek';
$LN['spots_poetry']         = 'Po&euml;zie';
$LN['spots_fairytale']      = 'Sprookje';
$LN['spots_technical']      = 'Techniek';
$LN['spots_art']            = 'Kunst';
$LN['spots_bi']             = 'Erotiek: Bi';
$LN['spots_lesbo']          = 'Erotiek: Lesbisch';
$LN['spots_homo']           = 'Erotiek: Homo';
$LN['spots_hetero']         = 'Erotiek: Hetero';
$LN['spots_amateur']        = 'Erotiek: Amateur';
$LN['spots_groep']          = 'Erotiek: Groep';
$LN['spots_pov']            = 'Erotiek: POV';
$LN['spots_solo']           = 'Erotiek: Solo';
$LN['spots_teen']           = 'Erotiek: Jong';
$LN['spots_soft']           = 'Erotiek: Soft';
$LN['spots_fetish']         = 'Erotiek: Fetisj';
$LN['spots_mature']         = 'Erotiek: Oud';
$LN['spots_fat']            = 'Erotiek: Dik';
$LN['spots_sm']             = 'Erotiek: SM';
$LN['spots_rough']          = 'Erotiek: Ruig';
$LN['spots_black']          = 'Erotiek: Donker';
$LN['spots_hentai']         = 'Erotiek: Hentai';
$LN['spots_outside']        = 'Erotiek: Buiten';

$LN['update_database']      = 'Database updaten';

$LN['password_weak']        = 'Wachtwoordsterkte: zwak';
$LN['password_medium']      = 'Wachtwoordsterkte: matig';
$LN['password_strong']      = 'Wachtwoordsterkte: sterk';
$LN['password_correct']     = 'Wachtwoorden komen overeen';
$LN['password_incorrect']   = 'Wachtwoorden komen niet overeen';

$LN['dashboard_max_nntp']      = 'Maximale aantal NNTP verbindingen';
$LN['dashboard_max_threads']   = 'Maximale aantal taken';
$LN['dashboard_max_db_intensive']	= 'Maximum aantal database intensieve taken';

if (isset($smarty)) { // don't do the smarty thing if we read it from urdd
    foreach ($LN as $key => $word) {
        $LN2['LN_' . $key] = $word;
    }
    $smarty->assign($LN2);
    unset($LN2);
}
