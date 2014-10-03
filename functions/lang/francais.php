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
* $LastChangedDate: 2010-02-04 17:51:12 +0100 (Thu, 04 Feb 2010) $
* $Rev: 1234 $
* $Author: gavinspearhead $
* $Id: francais.php 1234 2010-02-04 13:59:24Z philippe.corbel.75 $
*/

/* French language file for URD  */
/* first version based on english.php rev1203 */

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

$LN['byte']             = 'octet';
$LN['bytes']            = 'octets';
$LN['byte_short']       = 'o';

$LN['ok']               = 'OK';
$LN['cancel']           = 'Annuler';
$LN['pause']            = 'Mettre en pause';
$LN['continue']         = 'Continuer';

$LN['details']          = 'D&eacute;tails';
$LN['error']            = 'Erreur';
$LN['atonce']           = 'Imm&eacute;diatement';
$LN['browse']           = 'Parcourir';

// Special:
$LN['urdname']          = 'URD';
$LN['decimalseparator'] = ',';
$LN['dateformat']       = 'd-m-Y';
$LN['dateformat2']      = 'd M Y';
$LN['dateformat3']      = 'd M';
$LN['timeformat']       = 'H:i:s';
$LN['timeformat2']      = 'H:i';

// This 'overwrites' the define values:
$LN['periods'][0]       = 'Pas de mise &agrave; jour automatique';
$LN['periods'][11]      = 'Toutes les heures';
$LN['periods'][12]      = 'Toutes les 3 heures';
$LN['periods'][1]       = 'Toutes les 6 heures';
$LN['periods'][13]      = 'Toutes les 12 heures';
$LN['periods'][2]       = 'Tous les jours';
$LN['periods'][3]       = 'Chaque Lundi';
$LN['periods'][4]       = 'Chaque Mardi';
$LN['periods'][5]       = 'Chaque Mercredi';
$LN['periods'][6]       = 'Chaque Jeudi';
$LN['periods'][7]       = 'Chaque Vendredi';
$LN['periods'][8]       = 'Chaque Samedi';
$LN['periods'][9]       = 'Chaque Dimanche';
$LN['periods'][10]      = 'Toutes les 4 semaines';

$LN['autoconfig']       = 'Autoconfiguration';
$LN['autoconfig_ext']   = 'Autoconfiguration (&eacute;tendue)';
$LN['extended']         = '&eacute;tendu';
$LN['reload']           = 'recharger';
$LN['expand']           = '&eacute;tendre';
$LN['all']              = 'tout';
$LN['since']            = 'depuis';
$LN['disabled']         = 'd&eacute;sactiv&eacute;';
$LN['unknown']          = 'Inconnu';
$LN['help']             = 'Aide';
$LN['sets']             = 'ensembles';
$LN['saved']            = 'Enregistr&eacute;';
$LN['active']           = 'Actif';
$LN['deleted']          = 'Effac&eacute;';

$LN['id']                   = 'ID';
$LN['pid']                  = 'PID';
$LN['server']               = 'Serveur';
$LN['start_time']           = 'Temps de d&eacute;marrage';
$LN['queue_time']           = 'Temps file d&#039;attente';
$LN['recurrence']           = 'R&eacute;currence';
$LN['enabled']              = 'Activ&eacute;';
$LN['free_threads']         = 'Threads libres';
$LN['total_free_threads']   = 'Total des threads libres';
$LN['free_db_intensive_threads']   = 'Threads intensives base de donn&eacute;e libre';
$LN['free_nntp_threads']    = 'Threads NNTP libres';

$LN['add_search']       = 'Ajouter crit&egrave;re de recherche';
$LN['delete_search']    = 'Supprimer crit&egrave;re de recherche';
$LN['save_search_as']   = 'Enregister crit&egrave;re de recherche sous';

$LN['time']             = 'Temps';

$LN['expire']        = 'D&eacute;lai d&#39;expiration';
$LN['update']        = 'Mettre &agrave; jour';
$LN['purge']         = 'Purger';
$LN['CAPTCHA1']      = 'Captcha';
$LN['CAPTCHA2']      = '3 symboles noirs';

$LN['autoconfig_msg']       = 'Autoconfiguration: essaye tous les serveurs de la liste et regarde s&#039;il y a un serveur aux ports standard usenet (119 et 563), avec ou sans ssl/tls. Si un est trouv&eacute;&#39;, il est s&eacute;lectionn&eacute;; et \"mettre &agrave; jour serveur\" est s&eacute;lectionn&eacute; si le serveur trouv&eacute; autorise l&#039;indexation';
$LN['autoconfig_ext_msg']       = 'Autoconfiguration &eacute;tendue: essaye tous les serveurs de la liste et regarde s&#39;il y a un serveur aux ports standard usenet (119 et 563) et &agrave; d&#39;autres qui peuvent &ecirc;tre utilis&eacute;s par les fournisseurs de service usenet (comme 23, 80, 8080, 443), avec ou sans ssl/tls. Si un est trouv&eacute;, il est s&eacute;lectionn&eacute;; et \"mettre &agrave; jour serveur\" est s&eacute;lectionn&eacute; si le serveur trouv&eacute; autorise l&#39;indexation';

$LN['short_month_names'][1]       = 'Jan';
$LN['short_month_names'][2]       = 'F&eacute;v';
$LN['short_month_names'][3]       = 'Mar';
$LN['short_month_names'][4]       = 'Avr';
$LN['short_month_names'][5]       = 'Mai';
$LN['short_month_names'][6]       = 'Juin';
$LN['short_month_names'][7]       = 'Juil';
$LN['short_month_names'][8]       = 'Aou';
$LN['short_month_names'][9]       = 'Sep';
$LN['short_month_names'][10]      = 'Oct';
$LN['short_month_names'][11]      = 'Nov';
$LN['short_month_names'][12]      = 'D&eacute;c';

$LN['month_names'][1]       = 'Janvier';
$LN['month_names'][2]       = 'F&eacute;vrier';
$LN['month_names'][3]       = 'Mars';
$LN['month_names'][4]       = 'Avril';
$LN['month_names'][5]       = 'Mai';
$LN['month_names'][6]       = 'Juin';
$LN['month_names'][7]       = 'Juillet';
$LN['month_names'][8]       = 'Aout';
$LN['month_names'][9]       = 'Septembre';
$LN['month_names'][10]      = 'Octobre';
$LN['month_names'][11]      = 'Novembre';
$LN['month_names'][12]      = 'D&eacute;cembre';

$LN['short_day_names'][1]	= 'Dim';
$LN['short_day_names'][2]	= 'Lun';
$LN['short_day_names'][3]	= 'Mar';
$LN['short_day_names'][4]	= 'Mer';
$LN['short_day_names'][5]	= 'Jeu';
$LN['short_day_names'][6]	= 'Ven';
$LN['short_day_names'][7]	= 'Sam';

$LN['select']       = 'S&eacute;lectionner un';
$LN['whitelisttag'] = 'B';
$LN['blacklisttag']     = 'N';
$LN['spamreporttag']    = 'S';

$LN['off']          = '&Eacute;teint';
$LN['on']           = 'Allum&eacute;';
$LN['all']          = 'Tout';
$LN['preview']      = 'Pr&eacute;visualiser';
$LN['temporary']    = 'Fichiers temporaires';
$LN['other']        = 'Autre';
$LN['from']         = 'de';
$LN['never']        = 'jamais';
$LN['total']        = 'Total';

$LN['next']         = 'Suivant';
$LN['previous']     = 'Pr&eacute;c&eacute;dent';

// Time:
$LN['year']         = 'Ann&eacute;e';
$LN['month']        = 'Mois';
$LN['week']         = 'Semaine';
$LN['day']          = 'Jour';
$LN['hour']         = 'Heure';
$LN['minute']       = 'Minute';
$LN['second']       = 'Seconde';

$LN['years']        = 'Ann&eacute;es';
$LN['months']       = 'Mois';
$LN['weeks']        = 'Semaines';
$LN['days']         = 'Jours';
$LN['hours']        = 'Heures';
$LN['minutes']      = 'Minutes';
$LN['seconds']      = 'Secondes';

$LN['year_short']   = 'Y';
$LN['month_short']  = 'M';
$LN['week_short']   = 'w';
$LN['day_short']    = 'd';
$LN['hour_short']   = 'h';
$LN['minute_short'] = 'm';
$LN['second_short'] = 's';

// Menu:
$LN['menudownloads']    = 'T&eacute;l&eacute;chargements';
$LN['menuuploads']      = 'Envois';
$LN['menutransfers']    = 'Transferts';
$LN['menubrowsesets']   = 'Provisions';
$LN['menugroupsearch']  = 'Rechercher&nbsp;dans&nbsp;les&nbsp;groupes';
$LN['menuspotssearch']  = 'Rechercher&nbsp;dans&nbsp;les&nbsp;spots';
$LN['menusearch']       = 'Rechercher';
$LN['menursssearch']    = 'Rechercher&nbsp;dans&nbsp;les&nbsp;flux&nbsp;rss';
$LN['menunewsgroups']   = 'Newsgroups';
$LN['menuviewfiles']    = 'Voir&nbsp;les&nbsp;fichiers';
$LN['menuviewfiles_downloads']      = 'T&eacute;l&eacute;chargements';
$LN['menuviewfiles_previews']       = 'Pr&eacute;visualisation';
$LN['menuviewfiles_nzbfiles']       = 'Fichiers&nbsp;NZB';
$LN['menuviewfiles_scripts']        = 'Scripts';
$LN['menuviewfiles_posts']          = 'Envois';
$LN['menupreferences']  = 'Pr&eacute;f&eacute;rences';
$LN['menuadmin']        = 'Administrateur';
$LN['menuabout']        = 'A propos de';
$LN['menumanual']       = 'Manuel';
$LN['menuadminconfig']  = 'Configuration';
$LN['menuadmincontrol'] = 'Contr&ocirc;le';
$LN['menuadminusenet']  = 'Serveurs&nbsp;Usenet';
$LN['adminupdateblacklist'] = 'Mettre &agrave; jour la liste noire des spots';
$LN['adminupdatewhitelist'] = 'Mettre &agrave; jour la liste blanche des spots';
$LN['menuadminlog']     = 'Journaux';
$LN['menuadminjobs']    = 'Jobs';
$LN['menuadmintasks']   = 'T&acirc;ches';
$LN['menuadminusers']   = 'Utilisateurs';
$LN['menuadminbuttons'] = 'Chercher';
$LN['menuhelp']         = 'Aide';
$LN['menufaq']          = 'FAQ';
$LN['menulicence']      = 'Licence';
$LN['menulogout']       = 'D&eacute;connexion';
$LN['menulogin']        = 'Connexion';
$LN['menudebug']        = 'Debug';
$LN['menustats']        = 'Statistiques';
$LN['menuforum']        = 'Forum';
$LN['menuuserlists']    = 'Liste de spotters';

//button texts

$LN['advanced_search']  = 'Recherche avanc&eacute;e';

// Stati:
$LN['statusidling']     = 'Inactif';
$LN['statusrunningtasks']  = 'T&acirc;ches actives';

$LN['enableurddfirst']  = 'Activez d&#39;abord URDD afin de pouvoir modifier ces r&eacute;glages';
// Version:
$LN['version']          = 'Version';
$LN['enableurdd']       = 'Cliquez pour activer URDD';
$LN['disableurdd']      = 'Cliquez pour d&eacute;sactiver URDD';
$LN['urddenabled']      = 'URDD est activ&eacute;';
$LN['urddstarting']     = 'URDD est en cours de d&eacute;marrage';
$LN['urdddisabled']     = 'URDD est d&eacute;sactiv&eacute;';
$LN['versionuptodate']  = 'URD est &agrave; jour';
$LN['versionoutdated']  = 'URD n&eacute;cessite une mise &agrave; jour.';
$LN['newversionavailable']  = 'Une nouvelle version majeure est disponible.';
$LN['bugfixedversion']      = 'La nouvelle version contient des correctifs de bugs.';
$LN['newfeatureversion']    = 'La nouvelle version contient de nouvelles fonctions.';
$LN['otherversion']     = 'La nouvelle version contient des changements non sp&eacute;cifi&eacute;s(??).';
$LN['securityfixavailable'] = 'La nouvelle version contient des correctifs importants de s&eacute;curit&eacute;.';
$LN['status']           = 'Statut';
$LN['activity']         = 'Activit&eacute;';

// Tasks:
$LN['taskupdate']       = 'Mise &agrave; jour en cours';
$LN['taskpurge']        = 'Nettoyage en cours';
$LN['taskexpire']       = 'Expiration en cours';
$LN['taskpost']         = 'Postage en cours';
$LN['taskdownload']     = 'T&eacute;l&eacute;chargement en cours';
$LN['taskcontinue']     = 'Continuer';
$LN['taskpause']        = 'Pause';
$LN['taskunknown']      = 'Inconnu';
$LN['taskoptimise']     = 'Optimisation en cours';
$LN['taskgrouplist']    = 'T&eacute;l&eacute;chargement de la liste des groupes';
$LN['taskunparunrar']   = 'Extraction en cours';
$LN['taskcheckversion'] = 'V&eacute;rification de la version en cours';
$LN['taskgetsetinfo']   = 'R&eacute;cuperer les informations de l&#39;ensemble';
$LN['taskgetblacklist'] = 'R&eacute;ception de la liste noire';
$LN['taskgetwhitelist'] = 'R&eacute;ception de la liste blanche';
$LN['tasksendsetinfo']  = 'Envoyer les informations de l&#39;ensemble';
$LN['taskparsenzb']     = 'Lecture du fichier NZB';
$LN['taskmakenzb']      = 'Cr&eacute;ation du fichier NZB';
$LN['taskcleandir']     = 'Nettoyage des r&eacute;pertoires en cours';
$LN['taskcleandb']      = 'Nettoyage de la base de donn&eacute;es en cours';
$LN['taskgensets']      = 'G&eacute;n&eacute;ration d&#39;un ensemble pour';
$LN['taskadddata']      = 'Ajout de donn&eacute;es de t&eacute;l&eacute;chargement pour';
$LN['taskmergesets']    = 'Fusion des ensembles';
$LN['taskfindservers']  = 'Autoconfiguration du serveur';
$LN['taskgetnfo']     = 'R&eacute;ception des NFO data';
$LN['taskgetspots']   = 'R&eacute;ception des spots';
$LN['taskgetspot_comments']     = 'R&eacute;ception des commentaires des spots';
$LN['taskgetspot_reports']     = 'R&eacute;ception des rapports de spam des spots';
$LN['taskgetspot_images']     = 'R&eacute;ception des images des spots';
$LN['taskexpirespots']= 'Expiration des spots';
$LN['taskpurgespots'] = 'Purge des spots';
$LN['taskpostmessage']= 'Poster un message';
$LN['taskpostspot']     = 'Poster un spot';
$LN['taskdeleteset']  = 'Suppression de l&#39;ensemble';
$LN['taskset']          = 'Appliquer la configuration';

$LN['eta']          = 'Temps estim&eacute;';
$LN['inuse']        = 'du disque est rempli';
$LN['free']         = 'du disque est libre';

// Generic:
$LN['isavailable']  = 'est disponible';
$LN['apply']        = 'Appliquer';
$LN['website']      = 'Site Web';
$LN['or']           = 'ou';
$LN['submit']       = 'Valider';
$LN['add']          = 'Ajouter';
$LN['clear']        = 'Effacer';
$LN['reset']        = 'Annuler';
$LN['search']       = 'Rechercher';
$LN['number']       = 'Nombre';
$LN['rename']       = 'Renommer';
$LN['register']     = 'S&#39;enregistrer';
$LN['delete']       = 'Supprimer';
$LN['delete_all']   = 'Supprimer tous';

// Setinfo:
$LN['bin_unknown']  = 'Inconnu';
$LN['bin_movie']    = 'Film';
$LN['bin_album']    = 'Album';
$LN['bin_image']    = 'Image';
$LN['bin_software'] = 'Logiciel';
$LN['bin_tvseries'] = 'Series TV';
$LN['bin_ebook']    = 'eBook';
$LN['bin_game']     = 'Jeux';
$LN['bin_documentary']      = 'Documentaire';
$LN['bin_tvshow']   = 'Magazine TV';
$LN['bin_other']    = 'Autre';

// View files:
$LN['files']        = 'fichiers';
$LN['viewfilesheading']     = 'Visualis&eacute;';
$LN['filename']     = 'Nom du fichier';
$LN['group']        = 'Groupe';
$LN['rights']       = 'Droits';
$LN['size']         = 'Taille';
$LN['count']        = 'Nombre';
$LN['type']         = 'Type';
$LN['modified']     = 'Modifi&eacute; le';
$LN['owner']        = 'Propri&eacute;taire';
$LN['perms']        = 'Droits';
$LN['actions']      = 'Actions';
$LN['uploaded']     = 'Fichier envoy&eacute;';
$LN['edit_file']    = 'Editer le fichier';
$LN['viewfiles_title']      = 'Voir les fichiers';
$LN['viewfiles_filenotpermitted'] = 'Fichier non autoris&eacute;';
$LN['viewfiles_delete']     = 'Supprimer';
$LN['viewfiles_download']   = 'T&eacute;l&eacute;charger l&#39;archive';
$LN['viewfiles_uploadnzb']  = 'T&eacute;l&eacute;charger depuis le NZB';
$LN['viewfiles_rename']     = 'Renommer';
$LN['viewfiles_edit']       = 'Editer';
$LN['viewfiles_newfile']    = 'Nouveau fichier';
$LN['viewfiles_savefile']   = 'Sauver fichier';
$LN['viewfiles_tarnotset']  = 'La commande TAR n&#39;est pas r&eacute;gl&eacute;e. Le t&eacute;l&eacute;chargement d&#39;archives est d&eacute;sactiv&eacute;.';
$LN['viewfiles_compressfailed'] = 'Erreur lors de la compression des fichiers';

$LN['viewfiles_type_audio'] = 'Audio';
$LN['viewfiles_type_excel'] = 'Excel';
$LN['viewfiles_type_exe']   = 'Exe';
$LN['viewfiles_type_flash'] = 'Flash';
$LN['viewfiles_type_html']  = 'HTML';
$LN['viewfiles_type_iso']   = 'ISO';
$LN['viewfiles_type_php']   = 'PHP';
$LN['viewfiles_type_source']    = 'Source';
$LN['viewfiles_type_picture']   = 'Image';
$LN['viewfiles_type_ppt']   = 'PPT';
$LN['viewfiles_type_script']    = 'Script';
$LN['viewfiles_type_text']  = 'Texte';
$LN['viewfiles_type_video'] = 'Vid&eacute;o';
$LN['viewfiles_type_word']  = 'Word';
$LN['viewfiles_type_zip']   = 'Archive';
$LN['viewfiles_type_stylesheet']= 'Stylesheet';
$LN['viewfiles_type_icon']  = 'Ic&ocirc;ne';
$LN['viewfiles_type_db']    = 'DB';
$LN['viewfiles_type_folder']    = 'R&eacute;pertoire';
$LN['viewfiles_type_file']  = 'Fichier';
$LN['viewfiles_type_pdf']   = 'PDF';
$LN['viewfiles_type_nzb']   = 'NZB';
$LN['viewfiles_type_par2']  = 'Par2';
$LN['viewfiles_type_sfv']   = 'SFV';
$LN['viewfiles_type_playlist']  = 'Playlist';
$LN['viewfiles_type_torrent']   = 'Torrent';
$LN['viewfiles_type_urdd_sh']   = 'Script URD';
$LN['viewfiles_type_ebook']     = 'Ebook';

$LN['user_lists_title'] = 'Listes des utilisateurs';
$LN['user_blacklist']   = 'Liste noire des spots';
$LN['user_whitelist']   = 'Liste blanche des spots';
$LN['spotter_id']       = 'Spotter ID';
$LN['source_external']  = 'Externe';
$LN['source_user']      = 'Ajout&eacute; par l&#39;utilisateur';
$LN['global']           = 'Global';
$LN['personal']         = 'Personnel';
$LN['active']           = 'Actif';
$LN['disabled']         = 'D&eacute;sactiv&eacute;';
$LN['nonactive']        = 'Inactif';


// About:
$LN['about_title']  = 'A propos d&#39;URD';
$LN['abouttext1']   = 'URD est une application &agrave; l&#39;interface  de type web pour t&eacute;l&eacute;charger des binaires usenet. Il est enti&egrave;rement &eacute;crit en PHP, mais cependant utilise certains outils externes pour r&eacute;aliser du travail intensif en CPU. Il stocke toutes les informations n&eacute;cessaires dans une base de donn&eacute;es g&eacute;n&eacute;rique (comme MySQL ou PostGreSQL). Les articles li&eacute;s entre eux sont rassembl&eacute;s en ensembles. T&eacute;l&eacute;charger des fichiers requiert seulement quelques clics de souris, et lorsque le t&eacute;l&eacute;chargement est termin&eacute; il peut &ecirc;tre automatiquement v&eacute;rifi&eacute; et d&eacute;compress&eacute;s.  T&eacute;l&eacute;charger depuis le usenet est aussi facile qu&#39;utiliser un logiciel p2p!';

$LN['abouttext2']   = 'Un point fort d&#39;URD est qu&#39;aucun site web externe n&#39;est n&eacute;cessaire puisqu&#39;URD g&eacute;n&egrave;re ses propres informations de t&eacute;l&eacute;chargements. Il est &eacute;galement possible de cr&eacute;er et de t&eacute;l&eacute;charger un fichier NZB des articles sp&eacute;cifi&eacute;s.';

$LN['abouttext3']   = 'URD est un acronyme de Usenet Resource Downloader. Le terme URD est d&eacute;riv&eacute; de la culture Nordique faisant r&eacute;f&eacute;rence au Puit de URD, qui est le puit sacr&eacute;, le Puit Source, la source d&#39;eau pour l&#39;arbre monde Yggdrasil. L&#39;ancien terme Anglais lui faisant r&eacute;f&eacute;rence est Wyrd. Conceptuellement la signification de URD la plus proche est celle de Destin.';

$LN['licence_title']  = 'Licence';

// Newsgroup
$LN['ng_title']       = 'Newsgroups';
$LN['ng_name']        = 'Nom';
$LN['ng_posts']       = 'Messages';
$LN['ng_lastupdated'] = 'Derni&egrave;re mise &agrave; jour';
$LN['ng_expire_time'] = 'D&eacute;lai d&#39;expiration';
$LN['ng_autoupdate']  = 'Mise &agrave; jour automatique';
$LN['ng_time']        = 'Date';
$LN['ng_searchtext']  = 'Chercher dans tous les newsgroups disponibles';
$LN['ng_newsgroups']  = 'Newsgroups';
$LN['ng_subscribed']  = 'Souscriptions';
$LN['ng_tooltip_name']      = 'Nom du newsgroup';
$LN['ng_tooltip_lastupdated']   = 'Combien de temps s&#39;est &eacute;coul&eacute; depuis la derni&egrave;re mise &agrave; jour de ce newsgroup';
$LN['ng_tooltip_action']    = 'Mets &agrave; jour/G&eacute;n&egrave;re les ensembles/R&egrave;gle l&#39;expiration/Purge';
$LN['ng_tooltip_expire']    = 'Nombre de jours prendant lesquels les articles sont conserv&eacute; dans la base de donn&eacute;es';
$LN['ng_tooltip_time']      = 'Date &agrave; laquelle la mise &agrave; jour automatique se d&eacute;roulera';
$LN['ng_tooltip_autoupdate']    = 'Fr&eacute;quence &agrave; laquelle ce groupe sera mis &agrave; jour automatiquement';
$LN['ng_tooltip_posts']     = 'Nombre d&#39;articles dans ce groupe';
$LN['ng_tooltip_active']    = 'V&eacute;rifie la souscription &agrave; ce newsgroup';
$LN['ng_gensets']       = 'G&eacute;n&eacute;rer les ensembles';
$LN['ng_visible']       = 'Visible';
$LN['ng_minsetsize']    = 'Regler la taille Min/Max';
$LN['ng_tooltip_visible']   = 'Le groupe est-il visible?';
$LN['ng_tooltip_minsetsize']    = 'Tailles minimum et maximum en Mo pour lesquelles afficher un ensemble pour ce groupe (0 signifie pas de limite)';
$LN['ng_admin_maxsetsize']  = 'R&eacute;gler la limite sup&eacute;rieure';
$LN['ng_tooltip_admin_maxsetsize']    = 'La taille maximum qu&#39;un set peut avoir dans la base de donn&eacute;es - ajoutez k, M, G comme suffixe, exemple 100k ou 25G';
$LN['ng_admin_minsetsize']  = 'Limite basse contre le spam';
$LN['ng_tooltip_admin_minsetsize']    = 'Taille minimum qu&#39;un ensemble doit avoir pour &ecirc;tre ajout&eacute; &agrave; la base - ajoutez le suffixe k, M, G, par ex. 100k ou 25G (contr&ocirc;le de spam)';
$LN['ng_hide_empty']    = 'Cacher les groupes vides';
$LN['ng_edit_group']        = 'Editer le groupe';
$LN['ng_adult']             = '18+';
$LN['ng_tooltip_adult']     = 'Accessible uniquement lorsque l&#39;utilisateur a r&eacute;gl&eacute; le mode 18+';
$LN['failed']           = 'erreur';
$LN['success']          = 'd&eacute;marr&eacute;';
$LN['success2']         = 'succ&egrave;s';

$LN['user_settings']   = 'R&eacute;glages utilisateur';
$LN['global_settings'] = 'R&eacute;glages globaux';
// preferences
//
$LN['change_password']      = 'Modifier le mot de passe';
$LN['password_changed']     = 'Mot de passe modifi&eacute;';
$LN['delete_account']       = 'Effacer le compte';
$LN['delete_account_msg']   = 'Effacer le compte';
$LN['account_deleted']  = 'Compte effac&eacute;';
$LN['pref_spot_spam_limit']      = 'Limite pour les rapports de Spam';
$LN['pref_spot_spam_limit_msg']  = 'Nombre de rapports de spam au del&agrave; duquel les spots ne sont pas affich&eacute;s';
$LN['pref_title']       = 'Pr&eacute;f&eacute;rences';
$LN['pref_heading']     = 'Pr&eacute;f&eacute;rences personnelles';
$LN['pref_saved']       = 'Pr&eacute;f&eacute;rences sauv&eacute;es';
$LN['pref_language']    = 'Langue';
$LN['pref_template']    = 'Mod&egrave;le';
$LN['pref_language_msg']    = 'Langue utilis&eacute;e pour afficher URD';
$LN['pref_stylesheet']      = 'Feuille de style';
$LN['pref_stylesheet_msg']  = 'Feuille de style utilis&eacute;e pour afficher URD';
$LN['pref_template_msg']    = 'Le template utilis&eacute; pour afficher URD';
$LN['pref_index_page_msg']  = 'La page par d&eacute;faut &agrave; afficher apr&egrave;s la connexion';
$LN['pref_index_page']      = 'Page par d&eacute;faut';
$LN['pref_login']           = 'Connexion';
$LN['pref_display']         = 'Affichage';
$LN['pref_downloading']     = 'T&eacute;l&eacute;chargements';
$LN['pref_spots']           = 'Spots';
$LN['pref_setcompleteness'] = 'R&egrave;gle la compl&eacute;tion';

$LN['pref_default_group']        = 'Groupe par d&eacute;faut';
$LN['pref_default_group_msg']    = 'Groupe &agrave; s&eacute;lectionner par d&eacute;faut sur la page de navigation';
$LN['pref_default_feed']        = 'Flux par d&eacute;faut';
$LN['pref_default_feed_msg']    = 'Flux &agrave; s&eacute;lectionner par d&eacute;faut sur la page des ensembles rss';
$LN['pref_default_spot']        = 'Recherche spot par d&eacute;efaut';
$LN['pref_default_spot_msg']    = 'Recherche spot &agrave; s&eacute;lectionner par d&eacute;faut sur la page des spots';
$LN['pref_user_scripts']        = 'Ex&eacute;cute les scripts utilisateurs';
$LN['pref_user_scripts_msg']    = 'Scripts utilisateur qui sont ex&eacute;cut&eacute;s apr&egrave;s un t&eacute;l&eacute;chargement compl&eacute;t&eacute; (note: les noms des scripts doivent se terminer par .urdd_sh)';
$LN['pref_global_scripts']      = 'Ex&eacute;cute les scripts globaux';
$LN['pref_global_scripts_msg']  = 'Scripts globaux qui sont ex&eacute;cut&eacute;s apr&egrave;s un t&eacute;l&eacute;chargement compl&eacute;t&eacute; (note: les noms des scripts doivent se terminer par .urdd_sh)';

$LN['pref_skip_int']         = 'Ne pas enlever les ensembles interessants';
$LN['pref_skip_int_msg']     = 'Ne pas cacher les ensembles interessants lors d&#39;un clic sur enelever tous les ensembles de donn&eacute;es';
$LN['pref_level']       = 'Niveau d&#39;exp&eacute;rience de l&#39;utilisateur';
$LN['pref_level_msg']   = 'Plus l&#39;utilisateur a d&#39;exp&eacute;rience, plus d&#39;options seront affich&eacute;es dans les pages configuration (s&#39;il est administrateur) et pr&eacute;f&eacute;rences';
$LN['level_basic']      = 'Basique';
$LN['level_advanced']   = 'Avanc&eacute;';
$LN['level_master']     = 'Grand ma&icirc;tre';

$LN['pref_format_dl_dir']    = 'Format pour le r&eacute;pertoire de t&eacute;l&eacute;chargement';
$LN['pref_format_dl_dir_msg'] = 'Format pour le r&eacute;pertoire de t&eacute;l&eacute;chargement ajout&eacute; en suffixe du nom de t&eacute;l&eacute;chargement de base<br/>'.
   '%c: cat&eacute;gorie<br/>'.
   '%D: Date<br/>'.
   '%d: Jour dans le mois<br>'.
   '%g: Nom du groupe<br/>'.
   '%G: ID du groupe<br/>'.
   '%m: Mois (num&eacute;rique)<br/>'.
   '%M: Mois (nom court)<br/>'.
   '%n: Nom d&#39;ensembles<br/>'.
   '%s: Nom de t&eacute;l&eacute;chargement<br/>'.
   '%u: Nom d&#39;utilisateur<br/>'.
   '%w: Jour de la semaine<br/>'.
   '%W: Num&eacute; de la semaine<br/>'.
   '%x: Classement parental<br/>'.
   '%y: Ann&eacute;e (2 chiffres)<br/>'.
   '%Y: Ann&eacute;e (4 chiffres)<br/>'.
   '%z: Jour dans l&#39;ann&eacute;e<br/>';
$LN['pref_add_setname']       = 'Ajouter le nom de l&#39;ensemble au r&eacute;pertoire de t&eacute;l&eacute;chargement';
$LN['pref_add_setname_msg']   = 'Ajoute le nom de l&#39;ensemble &agrave; la chaine format&eacute;e pour le nom du r&eacute;pertoire de t&eacute;l&eacute;chargement';

$LN['poster_name']            = 'Nom du Posteur';
$LN['pref_poster_email']      = 'Adresse email du Posteur';
$LN['pref_poster_name']       = 'Nom du Posteur';
$LN['pref_poster_default_text'] = 'Corps du message standard';
$LN['pref_poster_default_text_msg'] = 'Message standardis&eacute; pour l&#39;envoi de spots et de commentaires';
$LN['pref_recovery_size']     = 'Pourcentage de fichiers par2';
$LN['pref_rarfile_size']      = 'Taille des fichiers rar';
$LN['pref_poster_email_msg']  = 'Adresse email &agrave; utiliser dans les messages post&eacute;s';
$LN['pref_poster_name_msg']   = 'Nom &agrave; utiliser dans les messages post&eacute;s';
$LN['pref_recovery_size_msg'] = 'Pourcentage de fichiers de r&eacute;cup&eacute;ration (par2) &agrave; cr&eacute;er (0 pour aucun)';
$LN['pref_rarfile_size_msg']  = 'Taille des fichiers rar en ko (0 pour d&eacute;sactiver l&#39;op&eacute;ration de rar)';
$LN['pref_posting']           = 'Post';

$LN['pref_download_delay']         = 'Retard de t&eacute;l&eacute;chargement';
$LN['pref_download_delay_msg']     = 'Nombre de minutes pendant lesquelles le t&eacute;l&eacute;chargement est mis en pause avant son d&eacute;marrage';
$LN['username']        = 'Utilisateur';
$LN['password']        = 'Mot de passe';
$LN['fullname']        = 'Nom complet';
$LN['email']           = 'Adresse email';
$LN['newpw']           = 'Nouveau mot de passe';
$LN['oldpw']           = 'Ancien mot de passe';
$LN['pref_maxsetname']      = 'Longueur max du nom d&#39;ensembles';
$LN['pref_setsperpage']     = 'Nombre max de lignes par page';
$LN['pref_minsetsize']      = 'Taille min d&#39;un ensemble en Mo';
$LN['pref_maxsetsize']      = 'Taille max d&#39;un ensemble en Mo';
$LN['setsize']         = 'Taille d&#39;un ensemble en Mo';
$LN['maxage']          = 'Age max en jours';
$LN['minage']          = 'Age min en jours';
$LN['age']             = 'Age en jours';
$LN['rating']          = 'Note';
$LN['maxrating']       = 'Note max (0-10)';
$LN['minrating']       = 'Note min (0-10)';
$LN['complete']        = 'Pourcentage de compl&eacute;tion';
$LN['maxcomplete']     = 'Pourcentage max de compl&eacute;tion';
$LN['mincomplete']     = 'Pourcentage min de compl&eacute;tion';
$LN['post_comment']    = 'Poster le commentaire';
$LN['pref_minngsize']       = 'Nombre min de messages dans les newsgroups';
$LN['config_global_hiddenfiles']     = 'Ne pas afficher les fichiers cach&eacute;s';
$LN['config_global_hidden_files_list']    = 'Liste des fichiers cach&eacute;s';
$LN['pref_hiddenfiles']     = 'Ne pas afficher les fichiers cach&eacute;s';
$LN['pref_hidden_files_list']    = 'Liste des fichiers cach&eacute;s';
$LN['pref_defaultsort']     = 'Champ utilis&eacute; pour trier les ensembles';
$LN['pref_buttons']         = 'Options de recherche';
$LN['pref_unpar']           = 'Ex&eacute;cution automatique de par2';
$LN['pref_download_par']            = 'Toujours t&eacute;l&eacute;charger les fichiers par2';
$LN['pref_download_par_msg']        = 'Lorsque l&#39;option est d&eacute;sactiv&eacute;e, les fichiers par2 ne sont t&eacute;l&eacute;charg&eacute;s que si n&eacute;cessaire. Dans l&#39;autre cas, toujours les t&eacute;l&eacute;charger';
$LN['pref_unrar']            = 'D&eacute;compression automatique des archives';
$LN['pref_delete_files']     = 'Effacer les fichiers apr&egrave;s unrar';
$LN['pref_mail_user']        = 'Envoyer les messages';
$LN['pref_show_subcats']     = 'Affiche les popups de sous-cat&eacute;gories pour les spots';
$LN['pref_show_subcats_msg'] = 'Affiche une description des sous-cat&eacute;gories pour un spot dans une fen&ecirc;tre popup';
$LN['pref_show_image']       = 'Afficher les images pour les spots';
$LN['pref_show_image_msg']   = 'Afficher les images pour les spots dans les informations spot &eacute;tendues';
$LN['pref_use_auto_download']         = 'T&eacute;l&eacute;chargement automatique';
$LN['pref_use_auto_download_nzb']     = 'T&eacute;l&eacute;charger automatiquement en tant que fichier NZB';
$LN['pref_use_auto_download_nzb_msg'] = 'T&eacute;l&eacute;charger automatiquement d&#39;apr&egrave;s les termes de recherche';
$LN['pref_download_text_file']        = 'T&eacute;l&eacute;charger les messages sans pi&egrave;ces atach&eacute;es';
$LN['pref_download_text_file_msg']    = 'T&eacute;l&eacute;charger le message texte alors qu&#39;aucune pi&egrave;ce attach&eacute;e n&#39;a &eacute;t&eacute; trouv&eacute;e dans le message';
$LN['pref_search_terms']    = 'Chercher les termes';
$LN['pref_blocked_terms']   = 'Termes bloqu&eacute;s';
$LN['spam_reports']         = 'Rapports de spams';
$LN['pref_setcompleteness_msg']      = 'Les ensembles dont le pourcentage d&#039;ach&egrave;vement est au moins de cette valeur seront affich&eacute;s sur la page parcourir';
$LN['config_spots_max_categories']   = 'Nombre maximum de cat&eacute;gories par spot';
$LN['config_spots_max_categories_msg']      = 'Les spots ayant plus que ce nombre de cat&eacute;gories sont reject&eacute;s (0 pour d&eacute;sactiver)';
$LN['config_spots_whitelist']               = 'Adresse (URL) pour la liste blanche des spots';
$LN['config_spots_whitelist_msg']           = 'Adresse (URL) contenant une liste d&#39;IDs de spots connus pour &ecirc;tre une source valide';
$LN['config_spots_blacklist']               = 'Adresse (URL) pour la liste noire des spots';
$LN['config_spots_blacklist_msg']           = 'Adresse (URL) contenant une liste d&#39;IDs de spots connus pour &ecirc;tre une source d&#39;abus';
$LN['config_download_spots_images']         = 'T&eacute;l&eacute;charger les images pour les spots';
$LN['config_download_spots_images_msg']     = 'T&eacute;l&eacute;charger les images pour les spots lors de la mise &agrave; jour de ceux-ci';
$LN['config_download_spots_comments']       = 'T&eacute;l&eacute;charger les commentaires pour les spots';
$LN['config_download_spots_comments_msg']   = 'T&eacute;l&eacute;charger les commentaires pour les spots lors de la mise &agrave; jour de ceux-ci';
$LN['config_download_spots_reports']        = 'T&eacute;l&eacute;charger les rapports de spam pour les spots';
$LN['config_download_spots_reports_msg']    = 'T&eacute;l&eacute;charger les rapports de spam pour les spots lors de la mise &agrave; jour de ceux-ci';
$LN['config_download_comment_avatar']       = 'T&eacute;l&eacute;charger les avatars pour les commentaires des spots';
$LN['config_download_comment_avatar_msg']   = 'T&eacute;l&eacute;charger les avatars pour les commentaires des spots (notez que cela peut prendre de la place et saturer la base de donn&eacute;es)';
$LN['config_spot_expire_spam_count']        = 'Limite haute pour le compteur de spam &agrave; partir de laquelle les spots sont supprim&eacute;s';
$LN['config_spot_expire_spam_count_msg']    = 'Les spots sont automatiquement supprim&eacute;s lorsque le compteur de spam d&eacute;passe cette valeur pour le spot (0 pour d&eacute;sactiver)';
$LN['config_allow_robots']      = 'Autoriser les robots';
$LN['config_allow_robots_msg']  = 'Autoriser les robots &agrave; suivre et ind&eacute;xer les pages web URD';
$LN['config_parse_nfo']     = 'Parcourir les fichiers nfo';
$LN['config_max_dl_name']   = 'Longueur max du nom de t&eacute;l&eacute;chargement';
$LN['config_maxexpire']	    = 'Dur&eacute;e maximum avant expiration';
$LN['config_maxexpire_msg']	= 'Nombre maximum de jours qui peut &ecirc;tre r&eacute;gl&eacute; pour la dur&eacute;e d&#039;expiration des newsgroups et les flux RSS';
$LN['config_max_login_count']	    = 'Nombre maximum de tentatives de connexion';
$LN['config_max_login_count_msg']	= 'Nombre maximum de tentatives d&#039;identifications rat&eacute;es avant que le compte soit bloqu&eacute;';
$LN['config_maxheaders']	        = 'Nombre maximum d&#039;ent&egrave;tes par lot de traitement';
$LN['config_maxheaders_msg']	= 'Nombre maximum d&#039;ent&egrave;tes qui peuvent &ecirc;tre r&eacute;cup&eacute;r&eacute; lors d&#039;un traitement par lot';
$LN['config_max_dl_name_msg']   = 'Longueur maximum du nom utilis&eacute; pour les t&eacute;l&eacute;chargements';
$LN['config_parse_nfo_msg']     = 'Parcourir les fichiers nfo lors de leur pr&eacute;visualisation';
$LN['config_nice_value']        = 'Valeur de Nice (niveau de priorit&eacute;)';
$LN['config_nice_value_msg']    = 'Valeur de Nice (niveau de priorit&eacute;) pour les programmes externes tels que par2 et rar';
$LN['config_replacement_str']   = 'Nom de remplacement par d&eacute;faut pour les t&eacute;l&eacute;chargements';
$LN['config_replacement_str_msg'] = 'Texte pour le remplacement des caract&egrave;res inappropri&eacute;s dans les noms de t&eacute;l&eacute;chargement';
$LN['config_group_filter']      = 'Filtre de Newsgroup';
$LN['config_group_filter_msg']  = 'Filtre pour la s&eacute;lection des newsgroups qui seront inclus';
$LN['config_queue_size']        = 'Taille de la file d&#039;attente';
$LN['config_queue_size_msg']    = 'Nombre maximum de t&acirc;ches qui peuvent &ecirc;tre dans la file d&#039;attente';
$LN['config_extset_group']      = 'Newsgroup pour extsetdata';
$LN['config_extset_group_msg']  = 'Le newsgroup o&ugrave; extsetdata sera post&eacute; et lu';
$LN['config_spots_comments_group_msg']  = 'Le newsgroup o&ugrave; les commentaires pour les spots seront lus';
$LN['config_spots_comments_group']      = 'Newsgroup pour les commentaires spots';
$LN['config_spots_group']               = 'Newsgroup pour les spots';
$LN['config_spots_reports_group']       = 'Newsgroup pour les rapports de spams pour les spots';
$LN['config_spots_reports_group_msg']   = 'Le newsgroup o&ugrave; les rapports de spams seront lus';
$LN['config_spots_group_msg']   = 'Le newsgroup o&ugrave; spots sera lu';
$LN['config_ftd_group']         = 'Newsgroup pour les fichiers NZB spots';
$LN['config_ftd_group_msg']     = 'Le newsgroup o&ugrave; les fichiers NZB pour spots peuvent &ecirc;tre trouv&eacute;s';

$LN['config_index_page_root_msg']  = 'La page par d&eacute;faut &agrave; afficher apr&egrave;s la connexion';
$LN['config_index_page_root']      = 'Page par d&eacute;faut';
$LN['config_modules']         = 'Modules';
$LN['config_module_groups']   = 'Indexation des groupes';
$LN['config_module_makenzb']  = 'Cr&eacute;ation des fichiers NZB';
$LN['config_module_usenzb']   = 'Import des fichiers NZB';
$LN['config_module_post']     = 'Envoi vers les groupes';
$LN['config_module_spots']    = 'Lecture des spots';
$LN['config_module_rss']      = 'Support des flux RSS';
$LN['config_module_sync']     = 'Synchronisation des informations d&#039;ensembles &eacute;tendus';
$LN['config_module_download'] = 'T&eacute;l&eacute;chargements depuis les newsgroups';
$LN['config_module_viewfiles'] = 'Explorateur de fichiers';

$LN['config_poster_blacklist']         = 'Liste noire des posteurs';
$LN['config_poster_blacklist_msg']     = 'Posteurs dont le nom ou email correspondant &agrave; l&#39;expression r&eacute;guli&egrave; suivante sont exclus de la base de donn&eacute; des ensembles';

$LN['config_module_groups_msg']   = 'Indexation des groupes';
$LN['config_module_makenzb_msg']  = 'Support pour la cr&eacute;ation des fichiers NZB';
$LN['config_module_usenzb_msg']   = 'Support pour le t&eacute;l&eacute;chargement gr&acirc;ce aux fichiers NZB';
$LN['config_module_post_msg']     = 'Envoi vers les groupes';
$LN['config_module_spots_msg']    = 'Lecture des spots depuis le serveur de newsgroups';
$LN['config_module_rss_msg']      = 'Support des flux RSS';
$LN['config_module_sync_msg']     = 'Synchronisation des informations d&#039;ensembles &eacute;tendus';
$LN['config_module_download_msg'] = 'T&eacute;l&eacute;chargements depuis les newsgroups';
$LN['config_module_viewfiles_msg'] = 'Explorateur de fichiers interne';

$LN['config_urdd_uid']      = 'ID utilisateur d&#039;urdd';
$LN['config_urdd_gid']      = 'ID de groupe d&#039;urdd';
$LN['config_urdd_uid_msg']  = 'L&#039;ID utilisateur qu&#039;utilisera urdd s&#039;il est lanc&eacute; par le root (laisser vide pour aucun changement)';
$LN['config_urdd_gid_msg']  = 'L&#039;ID de groupe qu&#039;utilisera urdd s&#039;il est lanc&eacute; par le root (laisser vide pour aucun changement)';

$LN['pref_subs_lang_msg']     = 'Langues pour lesquels les sous-titres seront recherch&eacute;s (codes sur deux lettres, separ&eacute;s par des virgules, laisser vide pour d&eacute;sactiver)';
$LN['pref_subs_lang']         = 'Langues de sous-titres';
$LN['config_nntp_maxdlthreads'] = 'Max threads par t&eacute;l&eacute;chargement';
$LN['config_nntp_maxdlthreads_msg'] = 'Nombre maximum de threads par t&eacute;l&eacute;chargement (0 pour pas de limite)';

$LN['username_msg']     = 'L&#39;utilisateur avec lequel vous vous &ecirc;tes connect&eacute;';
$LN['newpw1_msg']       = 'Votre nouveau mot de passe';
$LN['newpw2_msg']       = 'Votre nouveau mot de passe une seconde fois';
$LN['oldpw_msg']        = 'Votre mot de passe actuel';
$LN['pref_maxsetname_msg']       = 'La taille maximum du nom d&#039;un ensemble affichable sur une page';
$LN['pref_setsperpage_msg']      = 'Le nombre d&#039;ensembles affich&eacute;s sur une page';
$LN['pref_minsetsize_msg']       = 'Taille minimum qu&#39;un ensemble doit avoir afin d&#39;appara&icirc;tre dans la pr&eacute;sentation; les ensembles plus petits sont ignor&eacute;s';
$LN['pref_maxsetsize_msg']       = 'Taille maximum qu&#39;un ensemble doit avoir afin d&#39;appara&icirc;tre dans la pr&eacute;sentation; les ensembles plus grand sont ignor&eacute;s';
$LN['pref_minngsize_msg']        = 'Nombre minimum de messages qu&#39;un groupe doit avoir afin d&#39;appara&icirc;tre dans la pr&eacute;sentation';
$LN['pref_hiddenfiles_msg']      = 'Si actif, les fichiers cach&eacute;s ne seront pas affich&eacute;s dans l&#39;explorateur de fichiers';
$LN['config_global_hiddenfiles_msg']        = 'Si actif, les fichiers cach&eacute;s ne seront pas affich&eacute;s dans l&#39;explorateur de fichiers';
$LN['config_global_hidden_files_list_msg']  = 'Liste des fichiers &agrave; cacher dans l&#39;explorateur de fichiers. S&eacute;parez avec des retours &agrave; la ligne, utilisez * et ? comme cl&eacute; g&eacute;n&eacute;rique';
$LN['pref_hidden_files_list_msg']           = 'Liste des fichiers &agrave; cacher dans l&#39;explorateur de fichiers. S&eacute;parez avec des retours &agrave; la ligne, utilisez * et ? comme cl&eacute; g&eacute;n&eacute;rique';
$LN['pref_use_auto_download_msg']    = 'T&eacute;l&eacute;chargement automatique bas&eacute; sur les termes de recherche';

$LN['pref_defaultsort_msg']  = 'Champ utilis&eacute; pour le classement des ensembles';
$LN['pref_buttons_msg']      = 'Options de recherche dans la section parcourir';
$LN['pref_unpar_msg']        = 'Lorsque l&#39;option est activ&eacute;e et que l&#39;ensemble contient des fichiers par2, ceux-ci seront automatiquement utilis&eacute;s pour v&eacute;rifier et, si besoin est, corriger les fichiers t&eacute;l&eacute;charg&eacute;s';
$LN['pref_unrar_msg']        = 'Lorsque l&#39;option est activ&eacute;e, toutes les archives rar seront automatiquement d&eacute;compr&eacute;ss&eacute;es';
$LN['pref_delete_files_msg'] = 'Lorsque l&#39;option est activ&eacute;e et que le r&eacute;sultat de l&#39;ex&eacute;cution de la commande rar est correct, tous les fichiers rar et par2 seront supprim&eacute;s';
$LN['pref_mail_user_msg']    = 'Envoyer un message si un t&eacute;l&eacute;chargement s&#39;est termin&eacute;';
$LN['pref_search_terms_msg'] = 'Appliquer automatiquement ces termes de recherche &agrave; tous les groupes souscrits (s&eacute;par&eacute;s par des retour &agrave; la ligne) et les surligner';
$LN['pref_blocked_terms_msg']    = 'Appliquer automatiquement ces termes de recherche &agrave; tous les groupes souscrits (s&eacute;par&eacute;s par des retour &agrave; la ligne) et les cacher';

$LN['pref_mail_user_sets']   = 'Envoyer par Mail les ensembles int&eacute;ressants';
$LN['pref_mail_user_sets_msg']   = 'Envoyer un message si un ensemble int&eacute;ressant a &eacute;t&eacute; trouv&eacute;';
$LN['descending']       = 'D&eacute;croissant';
$LN['ascending']        = 'Croissant';

$LN['pref_basket_type']     = 'Type de panier de t&eacute;l&eacute;chargement';
$LN['pref_basket_type_msg'] = 'Le type of de panier de t&eacute;l&eacute;chargement utilis&eacute; par d&eacute;faut';
$LN['basket_type_small']    = 'Compact';
$LN['basket_type_large']    = 'Etendu';
$LN['pref_search_type']     = 'Type de recherche';
$LN['pref_search_type_msg'] = 'Type de recherche dans la base de donn&eacute;es utilis&eacute; avec les termes recherch&eacute;s';
$LN['search_type_like']     = 'Simple recherche g&eacute;n&eacute;rique (LIKE)';
$LN['search_type_regexp']   = 'Rechercher par expression r&eacute;guli&egrave;re (REGEXP)';

$LN['settings_imported']	='R&eacute;glages import&eacute;';
$LN['settings_import']		='Importer les r&eacute;glages';
$LN['settings_export']		='Exporter les r&eacute;glages';
$LN['settings_import_file']	='Importer les r&eacute;glages depuis un fichier';
$LN['settings_notfound']	= 'Fichier ou r&eacute;glages introuvables';
$LN['settings_upload']		= 'Charger les r&eacute;glages';
$LN['settings_filename']	= 'Nom de fichier';

$LN['import_servers']	= 'Importer les serveurs';
$LN['export_servers']   = 'Exporter les serveurs';
$LN['import_groups']	= 'Importer les groupes';
$LN['export_groups']	= 'Exporter les groupes';
$LN['import_feeds']		= 'Importer les flux';
$LN['export_feeds']		= 'Exporter les flux';
$LN['import_users']		= 'Importer les utilisateurs';
$LN['export_users']		= 'Exporter les utilisateurs';
$LN['import_buttons']	= 'Importer les options de recherche';
$LN['export_buttons']	= 'Exporter les options de recherche';
$LN['import_spots_blacklist']		= 'Importer les listes noires de spots';
$LN['export_spots_blacklist']		= 'Exporter les listes noires de spots';
$LN['import_spots_whitelist']		= 'Importer les listes blanches de spots';
$LN['export_spots_whitelist']		= 'Exporter les listes blanches de spots';

// pref errors
$LN['error_pwmatch']        = 'Les mots de passe ne correspondent pas';
$LN['error_pwincorrect']    = 'Mot de passe incorrect';
$LN['error_pwusername']     = 'Le mot de passe ressemble trop au nom d&#39;utilisateur';
$LN['error_pwlength']       = 'Mot de passe trop court; au moins '. MIN_PASSWORD_LENGTH . ' caract&egrave;r&eacute;s sont n&eacute;cessaires';
$LN['error_pwsimple']       = 'Mot de passe trop simple, utilisez un m&eacute;lange de majuscules, de minuscules, de nombres et de caract&egrave;res sp&eacute;ciaux';
$LN['error_captcha']        = 'CAPTCHA incorrect';
$LN['error_nocontent']      = 'Message trop court';
$LN['error_toolong']        = 'Message trop long';
$LN['error_filetoolarge']   = 'Fichier trop gros';
$LN['error_nosubcats']      = 'Aucune sous-cat&eacute;gorie s&eacute;lectionn&eacute;e';
$LN['error_nzbfilemissing'] = 'Fichier NZB manquant';
$LN['error_invalidcategory'] = 'Categorie invalide';
$LN['error_imgfilemissing'] = 'Fichier image manquant';

$LN['error_onlyforgrops'] 	= 'Fonctionne seulement pour les groupes';
$LN['error_onlyoneset'] 	= 'N&eacute;c&eacute;ssite d&#39;avoir plusieurs ensembles dans le panier';

$LN['error_feedexists']         = 'Un flux RSS portant ce nom existe d&eacute;j&agrave;';
$LN['error_encryptedrar']       = 'Fichier RAR chiffr&eacute;';
$LN['error_usercancel']         = 'Annul&eacute; par l&#39;utilisateur';
$LN['error_downloadnotfound']   = 'T&eacute;l&eacute;chargement non trouv&eacute;';
$LN['error_linknotfound'] 	    = 'Lien non trouv&eacute;';
$LN['error_nzbfailed'] 	        = 'Echec de l&#39;import des fichiers NZB';
$LN['error_toomanybuttons']     = 'Trop des options de recherche';
$LN['error_invalidbutton']      = 'Option de recherche invalide';
$LN['error_invalidemail']       = 'Adresse email invalide';
$LN['error_invalidpassword']    = 'Mot de passe invalide';
$LN['error_userexists']         = 'L&#39;utilisateur existe d&eacute;j&agrave;';
$LN['error_acctexpired']        = 'Compte expir&eacute;';
$LN['error_invalid_upload_type']= 'Type envoy&eacute; inconnu';
$LN['error_notleftblank']       = 'Ne doit pas rester vide';
$LN['error_invalidvalue']       = 'Valeur invalide';
$LN['error_urlstart']           = 'L&#39;adresse doit commencer par http:// et se terminer par /';
$LN['error_error']              = 'Erreur';
$LN['error_invaliddir']         = 'R&eacute;pertoire invalide';
$LN['error_notmakedir']         = 'Cr&eacute;ation du r&eacute;pertoire impossible';
$LN['error_notmaketmpdir']      = 'Cr&eacute;ation du r&eacute;pertoire tmp impossible';
$LN['error_notmakepreviewdir']  = 'Cr&eacute;ation du r&eacute;pertoire preview impossible';
$LN['error_dirnotwritable']     = 'R&eacute;pertoire en lecture seule';
$LN['error_notestfile']         = 'Cr&eacute;ation des fichiers test impossible';
$LN['error_mustbemore']         = 'doit &ecirc;tre sup&eacute;rieur &agrave;';
$LN['error_mustbeless']         = 'doit &ecirc;tre inf&eacute;rieur ou &eacute;gal &agrave;';
$LN['error_filenotexec']        = 'Le fichier ne peut pas &ecirc;tre trouv&eacute; ou n&#39;est pas ex&eacute;cutable par le serveur web';
$LN['error_noremovedir']        = 'Impossible de supprimer le r&eacute;pertoire';
$LN['error_noremovefile']       = 'Impossible de supprimer le fichier';
$LN['error_noremovefile2']      = 'Impossible de supprimer le fichier; r&eacute;pertoire en lecture seule';
$LN['error_nodeleteroot']       = 'Impossible de supprimer l&#39;utilisateur root';
$LN['error_nosetids']           = 'Aucun ID fourni!';
$LN['error_invalidstatus']      = 'Valeur de statut fournie invalide';
$LN['error_invaliduserid']      = 'ID utilisateur invalide';
$LN['error_groupnotfound']      = 'Groupe introuvable';
$LN['error_invalidgroupid']     = 'ID de groupe sp&eacute;cifi&eacute; invalide';
$LN['error_couldnotreadargs']   = 'Impossible de lire cmd args (register_argc_argv=Off?)';
$LN['error_resetnotallowed']    = 'r&eacute;initialisation de la configuration non autoris&eacute;e';
$LN['error_prefnotfound']       = 'Pr&eacute;f&eacute;rence non trouv&eacute;e';
$LN['error_invalidfilename']    = 'Nom de fichier invalide';
$LN['error_fileexists']         = 'Le fichier existe d&eacute;j&agrave;';
$LN['error_cannotrename']       = 'Impossible de renommer le fichier';
$LN['error_needfilenames']      = 'Nom de fichier n&eacute;cessaire';
$LN['error_usenetserverexists'] = 'Un serveur portant ce nom existe d&eacute;j&agrave;';
$LN['error_missingconnection']  = 'Type de connexion fourni invalide';
$LN['error_missingthreads']     = 'La discussion (Thread) doit &ecirc;tre indiqu&eacute;e';
$LN['error_missinghostname']    = 'Le nom de l&#39;h&ocirc;te doit &ecirc;tre indiqu&eacute;';
$LN['error_missingname']        = 'Un nom doit &ecirc;tre indiqu&eacute;';
$LN['error_needatleastoneport'] = 'Au moins un num&eacute;ro de port doit &ecirc;tre indiqu&eacute;';
$LN['error_needsecureport']     = 'Port s&eacute;curis&eacute; n&eacute;cessaire pour la connexion encrypt&eacute;e';
$LN['error_nosuchserver']       = 'Le serveur n&#39;existe pas';
$LN['error_invalidaction']      = 'Action inconnue';
$LN['error_nameexists']         = 'Un serveur usenet portant ce nom existe d&eacute;j&agrave;';
$LN['error_diskfull']           = 'Espace disque insuffisant pour compl&eacute;ter le t&eacute;l&eacute;chargement';
$LN['error_invalidsetid']       = 'ID d&#39;ensemble sp&eacute;cifi&eacute; invalide';
$LN['error_couldnotsendmail']   = 'Echec de l&#39;envoi du message';
$LN['error_filetoolarge']       = 'Fichier trop grand pour le t&eacute;l&eacute;chargement';
$LN['error_preview_size_exceeded']      = 'Fichier trop grand pour le pr&eacute;visualisation';
$LN['error_post_not_found'] = 'Post introuvable';
$LN['error_pwresetnomail']  = 'Mot de passe r&eacute;initialis&eacute;, mais impossible d&#039;envoyer un email';
$LN['error_userupnomail']   = 'Utilisateur mis &agrave; jour, mais impossible d&#039;envoyer un email';
$LN['error_groupnotfound']  = 'Le groupe n&#039;existe pas';
$LN['error_subjectnofound'] = 'Sujet manquant';
$LN['error_posternotfound'] = 'Email du posteur manquant';
$LN['error_invalidrecsize'] = 'Taille de r&eacute;cup&eacute;ration invalide';
$LN['error_invalidrarsize'] = 'Taille de fichier RAR invalide';
$LN['error_namenotfound']   = 'Nom du posteur manquant';
$LN['error_nosetsfound']    = 'Aucun ensemble trouv&eacute;';
$LN['error_nousersfound']   = 'Aucun utilisateur trouv&eacute;';
$LN['error_nowrite']        = 'Ecriture du fichier impossible';

$LN['error_noserversfound']         = 'Aucun serveur trouv&eacute;';
$LN['error_nouploadsfound']         = 'Aucun envoi trouv&eacute;';
$LN['error_nodownloadsfound']       = 'Aucun t&eacute;l&eacute;chargement trouv&eacute;';
$LN['error_nogroupsfound']          = 'Aucun groupe trouv&eacute;';
$LN['error_nosearchoptionsfound']   = 'Aucune option de recherche trouv&eacute;e';
$LN['error_nofeedsfound']           = 'Aucun flux trouv&eacute;';
$LN['error_notasksfound']           = 'Aucune t&acirc;che trouv&eacute;e';
$LN['error_nojobsfound']            = 'Aucun job trouv&eacute;';
$LN['error_nologsfound']            = 'Aucun log trouv&eacute;';

$LN['error_spotnotfound']       = 'Spot non trouv&eacute;';
$LN['error_searchnamenotfound'] = 'Nom non trouv&eacute;';
$LN['error_setnotfound']        = 'Ensemble non trouv&eacute;';
$LN['error_binariesnotfound']   = 'Impossible de trouver les binaires';
$LN['error_nameexists']         = 'Nom de recherche d&eacute;j&agrave; existant';
$LN['error_missingparameter']   = 'Param&egrave;tre manquant';
$LN['error_namenotfound']       = 'Nom non trouv&eacute;';
$LN['error_invalidimage']       = 'Image invalide';
$LN['error_nouploaddata']       = 'Aucune donn&eacute;e &agrave; envoyer trouv&eacute;e dans';

$LN['error_schedulesnotset']    = 'Planifications impossibles &agrave; r&eacute;gler';
$LN['error_unknowntype']        = 'Type inconnu';
$LN['error_emptybasket']        = 'Panier vide';

// Admin pages:
$LN['adminshutdown']    = 'Arr&eacute;ter le d&eacute;mon URD';
$LN['adminrestart']		= 'Red&eacute;marrer le d&eacute;mon URD';
$LN['adminpause']       = 'Mettre toutes les activit&eacute;s en pause';
$LN['admincontinue']    = 'Reprendre toutes les activit&eacute;s';
$LN['adminclear']       = 'Effacer tous les t&eacute;l&eacute;chargements';
$LN['admincleandb']     = 'Effacer toutes les informations volatiles';
$LN['adminremoveready'] = 'Effacer seulement les informations des t&eacute;l&eacute;chargements termin&eacute;s';
$LN['adminpoweron']     = 'D&eacute;marrer le d&eacute;mon URD';
$LN['adminupdatenglist']    = 'Mettre &agrave; jour la liste des newsgroups';
$LN['adminupdateallngs']    = 'Mettre &agrave; jour tous les newsgroups';
$LN['admingensetsallngs']   = 'G&eacute;n&eacute;rer les ensembles pour tous les newsgroups';
$LN['adminexpireallngs']    = 'Forcer l&#39;expiration de tous les newsgroups';
$LN['adminpurgeallngs']     = 'Purger tous les newsgroups';
$LN['adminexpireallrss']    = 'Forcer l&#39;expiration de tous les flux';
$LN['adminpurgeallrss']     = 'Purger tous les flux';
$LN['adminupdateallrss']    = 'Mettre &agrave; jour tous les flux';
$LN['adminoptimisedb']      = 'Optimiser la base de donn&eacute;es';
$LN['admincheckversion']    = 'V&eacute;rifier version de URD';
$LN['admingetsetinfo']      = 'R&eacute;cup&eacute;rer les informations ';
$LN['adminsendsetinfo']     = 'Envoyer les informations ';
$LN['admincleandir']        = 'Nettoyer les fichiers ';
$LN['adminfindservers']     = 'Autoconfigurer les serveurs usenet';
$LN['adminfindservers_ext'] = 'Autoconfigurer les serveurs usenet (&eacute;tendue)';
$LN['adminexport_all']      = 'Exporter tous les r&eacute;glages';
$LN['adminimport_all']      = 'Importer tous les r&eacute;glages';
$LN['adminupdate_spots']    = 'Mettre &agrave; jour les spots';
$LN['adminupdate_spotscomments']    = 'Mettre &agrave; jour les commentaires des spots';
$LN['adminupdate_spotsimages']      = 'Mettre &agrave; jour les images des spots';
$LN['adminexpire_spots']    = 'Forcer l&#39;expiration des spots';
$LN['adminpurge_spots']     = 'Purger les spots';

// register
$LN['reg_disabled']     = 'L&#39;enregistrement est d&eacute;sactiv&eacute;';
$LN['reg_title']        = 'Cr&eacute;ation de compte';
$LN['reg_codesent']     = 'Votre code d&#39;activation a &eacute;t&eacute; envoy&eacute;';
$LN['reg_status']       = 'Statut de l&#39;enregistrement';
$LN['reg_activated']    = 'Votre compte est activat&eacute;. Veuillez vous';
$LN['reg_activated_link'] = 'connecter';
$LN['reg_pending']      = 'Votre compte est en attente. Veuillez attendre qu&#39;un administrateur l&#39;active.';
$LN['reg_form']         = 'Veuillez compl&eacute;ter le formulaire afin d&#39;obtenir une compte d&#39;acc&egrave;s';
$LN['reg_again']        = 'une nouvelle fois';

//admin controls
$LN['control_title']    = 'Contr&ocirc;le du d&eacute;mon';
$LN['control_options']  = 'Options';
$LN['control_jobs']     = 'Jobs';
$LN['control_threads']  = 'Threads';
$LN['control_queue']    = 'File d&#39;attente';
$LN['control_servers']  = 'Serveurs';
$LN['control_uptime']   = 'Temps de fonctionnement';
$LN['control_load']     = 'Charge syst&egrave;me';
$LN['control_diskspace']    = 'Espace disque';
$LN['control_cancelall']    = 'Annuler toutes les t&acirc;ches';

/// posting
$LN['post_subject']         = 'Sujet';
$LN['post_delete_files']    = 'Effacer les fichiers';
$LN['post_delete_filesext'] = 'Effacer les fichiers temporaires cr&eacute;&eacute;s (par ex les fichiers rar et par2)';
$LN['post_postername']      = 'Nom du posteur';
$LN['post_posteremail']     = 'Adresse email du posteur';
$LN['post_recovery']        = 'Pourcentage de r&eacute;cup&eacute;ration';
$LN['post_rarfiles']        = 'Taille du fichier RAR';
$LN['post_newsgroup']       = 'Newsgroup';
$LN['post_post']            = 'Envoi';
$LN['post_directory']       = 'R&eacute;pertoire';
$LN['post_directoryext']    = 'Le r&eacute;pertoire qui sera upload&eacute;';
$LN['post_subjectext']      = 'Le sujet dans les diff&eacute;rents messages';
$LN['post_posternameext']   = 'Le nom du posteur dans les diff&eacute;rents messages (from)';
$LN['post_posteremailext']  = 'L&#39;adresse email du posteur dans les diff&eacute;rents messages (from)';
$LN['post_recoveryext']     = 'Le pourcentage de fichiers par2 &agrave; g&eacute;n&eacute;rer';
$LN['post_rarfilesext']     = 'La taille du fichier RAR compress&eacute; en kilo-octets';
$LN['post_newsgroupext']    = 'Le newsgroup dans lequel les messages seront post&eacute;s';

//admin jobs
$LN['jobs_title']       = 'Jobs planifi&eacute;s';
$LN['jobs_command']     = 'Commande';
$LN['jobs_time']        = 'Temps';
$LN['jobs_period']      = 'P&eacute;riode';
$LN['jobs_user']        = 'Utilisateur';

// admin tasks
$LN['tasks_title']          = 'T&acirc;ches';
$LN['tasks_description']    = 'Description';
$LN['tasks_progress']       = 'Progression';
$LN['tasks_added']          = 'Ajout&eacute;';
$LN['tasks_lastupdated']    = 'Derni&egrave;re mise &agrave; jour';
$LN['tasks_comment']        = 'Commentaire';

// admin config
$LN['config_title']         = 'Configuration';
$LN['config_setinfo']       = 'Informations pour la mise &agrave; jour';
$LN['config_urdd_head']     = 'D&eacute;mon URD';
$LN['config_nntp_maxthreads']       = 'Nombre maximum de connexion NNTP';
$LN['config_urdd_maxthreads']       = 'Nombre maximum de threads';
$LN['config_useauth']       = 'Utiliser l&#39;identification';
$LN['config_username']      = 'Nom d&#39;utilisateur';
$LN['config_password']      = 'Mot de passe';
$LN['config_useenc']        = 'Utiliser l&#39;encryption';
$LN['config_spots_expire_time']     = 'Temps d&#39;expiration pour les spots (en jours)';
$LN['config_spots_expire_time_msg'] = 'Temps d&#39;expiration pour les spots (en jours); veuillez noter que cette valeur remplace les valeurs r&eacute;gl&eacute;es pour chacun des newsgroup';
$LN['config_default_expire_time']   = 'Temps d&#39;expiration par d&eacute;faut (en jours)';
$LN['config_expire_incomplete']     = 'Temps d&#39;expiration pour les ensembles incomplets (en jours, 0 pour d&eacute;sactiver)';
$LN['config_expire_percentage']     = 'Pourcentage de compl&eacute;tion pour l&#39;expiration anticip&eacute;e des ensembles';
$LN['config_auto_expire']           = 'Forcer l&#39;expiration apr&egrave;s la mise &agrave; jour';
$LN['config_auto_getnfo']	        = 'T&eacute;l&eacute;chargement automatique des fichiers nfo';
$LN['config_period_getspots']	    = 'T&eacute;l&eacute;charger les spots';
$LN['config_period_getspots_msg']	= 'T&eacute;l&eacute;charger les spots';
$LN['config_period_getspots_whitelist']	    = 'T&eacute;l&eacute;charger la liste blanche pour les spots';
$LN['config_period_getspots_whitelist_msg']	= 'Planifier quand la liste blanche pour les spots est t&eacute;l&eacute;charg&eacute;e';
$LN['config_period_getspots_blacklist']	    = 'T&eacute;l&eacute;charger la liste noire pour les spots';
$LN['config_period_getspots_blacklist_msg']	= 'Planifier quand la liste noire pour les spots est t&eacute;l&eacute;charg&eacute;e';
$LN['config_auto_getnfo_msg']   = 'T&eacute;l&eacute;charge automatiquement et parcourt les fichiers nfo apr&egrave;s la mise &agrave; jour d&#39;un groupe de news';
$LN['pref_cancel_crypted_rars'] = 'Annuler les t&eacute;l&eacute;chargements encrypt&eacute;s';
$LN['config_dlpath']            = 'Stocker les fichiers ici';
$LN['config_urdd_host']         = 'Nom d&#39;h&ocirc;te pour URDD';
$LN['config_urdd_port']         = 'Port de URDD';
$LN['config_urdd_restart']      = 'Red&eacute;marrer les anciennes t&acirc;ches';
$LN['config_urdd_daemonise']     = 'D&eacute;marrer URDD en tacirc;âche de fond';
$LN['config_urdd_daemonise_msg'] = 'D&eacute;émarre URDD en tant que processus fonctionnant en t&acirc;ache de fond (d&eacute;mon)';
$LN['config_admin_email']        = 'Email de l&#39;administrateur';
$LN['config_baseurl']       = 'Adresse de base';
$LN['config_shaping']       = 'Activer la gestion de trafic';
$LN['config_maxdl']         = 'Bande passante maximum pour le download (ko/s) par connexion';
$LN['config_maxul']         = 'Bande passante maximum pour l&#39;upload (ko/s) par connexion';
$LN['config_maxpreviewsize']   = 'Taille maximum de fichier visible dans Pr&eacute;visualiser';
$LN['config_maxfilesize']   = 'Taille maximum de fichier visible dans l&#39;explorateur de fichiers';
$LN['config_register']      = 'Autoriser l&#39;enregistrement';
$LN['config_auto_reg']      = 'Accepter les comptes automatiquement';
$LN['config_urdd_path']     = 'urdd';
$LN['config_unpar_path']    = 'par2';
$LN['config_unrar_path']    = 'unrar';
$LN['config_rar_path']      = 'rar';
$LN['config_unace_path']    = 'unace';
$LN['config_tar_path']      = 'tar';
$LN['config_un7zr_path']    = 'un7za';
$LN['config_unzip_path']    = 'unzip';
$LN['config_gzip_path']     = 'gzip';
$LN['config_unarj_path']    = 'unarj';
$LN['config_subdownloader_path']         = 'subdownloader';
$LN['config_subdownloader_path_msg']     = 'Chemin o&ugrave; le programme subdownloader peut &ecirc;tre trouv&eacute; (optionnel)';
$LN['config_file_path']          = 'file';
$LN['config_yydecode_path']      = 'yydecode';
$LN['config_yyencode_path']      = 'yyencode';
$LN['config_cksfv_path']         = 'cksfv';
$LN['config_trickle_path']       = 'trickle';
$LN['config_period_update']         = 'V&eacute;rifier la disponibilit&eacute; de mises &agrave; jour pour URD';
$LN['config_period_opt']            = 'Optimiser la base de donn&eacute;es';
$LN['config_period_ng']             = 'Mettre &agrave; jour la liste de newsgroup';
$LN['config_period_cd']             = 'Nettoyer les r&eacute;pertoires preview et tmp';
$LN['config_clean_dir_age']         = 'Age des fichiers supprim&eacute;s';
$LN['config_clean_dir_age_msg']     = 'L&#39;&acirc;ge que doit avoir un fichier avant d&#39;&ecirc;tre supprim&eacute; par la commande d&#39;effacement de fichiers (en jours)';
$LN['config_clean_db_age']          = 'Age des informations volatiles dans la base de donn&eacute;es';
$LN['config_clean_db_age_msg']      = 'L&#39;&acirc;ge qu&#39;une information de la base de donn&eacute;es doit avoir avant d&#39;&ecirc;tre supprim&eacute;e par la commande d&#39;effacement dans la base de donn&eacute;es (en jours; 0 pour d&eacute;sactiver)';
$LN['config_period_cdb']            = 'Nettoyer la base de donn&eacute;es des informations volatiles';
$LN['config_period_cu']             = 'P&eacute;riode d&#39;inactivit&eacute; des utilisateurs';
$LN['config_period_cu_msg']         = 'P&eacute;riode d&#39;inactivit&eacute;, en jours, des utilisateurs non-admin apr&egrave;s laquelle ils seront effac&eacute;s ';
$LN['config_users_clean_age']       = 'Effacer les utilisateurs inactifs';
$LN['config_users_clean_age_msg']   = 'Effacer les utilisateurs non-admin inactifs apr&egrave;s une p&eacute;riode d&#39;inactivit&eacute; (en jours)';
$LN['config_usenet']        = 'Serveur de Newsgroups';
$LN['config_scheduler']     = 'Planificateur URDD';
$LN['config_networking']    = 'R&eacute;seau';
$LN['config_extprogs']      = 'Programmes';
$LN['config_maintenance']   = 'Maintenance';
$LN['config_globalsettings']= 'R&eacute;glages globaux';
$LN['config_notifysettings']= 'R&eacute;glages des notifications';
$LN['config_webdownload']   = 'Autoriser les t&eacute;l&eacute;chargements depuis l&#39;interface web';
$LN['config_webeditfile']	= 'Autoriser l&#039;&eacute;dition des fichiers dans l&#39;interface web';
$LN['config_webeditfile_msg']	= 'Les utilisateurs peuvent &eacute;diter les fichiers dans la page de visualisation des fichiers';
$LN['config_socket_timeout']    = 'Expiration des Socket';
$LN['config_connection_timeout']	    = 'Expiration de la connexion au serveur';
$LN['config_connection_timeout_msg']	= 'Dur&eacute;e d&#39;inactivit&eacute; au bout de laquelle la connexion au serveur est coup&eacute;e (0 pour d&eacute;sactiver - &agrave; utiliser avec pr&eacute;caution car cela peut saturer le serveur)';
$LN['config_urdd_connection_timeout']    = 'Expiration de la connexion &agrave; URDD';
$LN['config_urdd_connection_timeout_msg']= 'Nombre de secondes apr&egrave;s lesquelles une connexion &agrave; URDD expirera et sera ferm&eacute;e; par d&eacute;faut &agrave; 30s';
$LN['config_auto_download']             = 'Autoriser le t&eacute;l&eacute;chargement automatique';
$LN['config_check_nntp_connections']    = 'V&eacute;rifier les connexions usenet au d&eacute;marrage';
$LN['config_check_nntp_connections_msg']= 'Selectionne automatiquement le nombre possible de connexions concurrentes &agrave; un serveur NNTP au d&eacute;marrage';
$LN['config_nntp_all_servers']          = 'Autoriser les t&eacute;l&eacute;chargements sur tous les serveurs en parall&egrave;le';
$LN['config_nntp_all_servers_msg']      = 'Autoriser les t&eacute;l&eacute;chargements &agrave; fonctionner avec le nombre maximum de connexions NNTP sur tous les serveurs actifs, au lieu de se limiter &agrave; un seul serveur par t&eacute;l&eacute;chargement';
$LN['config_db_intensive_maxthreads']      = 'Nombre maximum de threads &agrave; usage intensif de la base de donn&eacute;es';
$LN['config_db_intensive_maxthreads_msg']  = 'Nombre maximum de threads qui requi&egrave;rent un usage intensif de la base de donn&eacute;es';

$LN['config_auto_login']      = 'Connexion automatique en tant que';
$LN['config_auto_login_msg']  = 'Connexion automatique en tant que l&#39;utilisateur sp&eacute;cifi&eacute;. Laisser blanc pour d&eacute;sactiver';

$LN['config_allow_global_scripts_msg']  = 'Autoriser les scripts r&eacute;gl&eacute;s par les administrateurs pour &ecirc;tre ex&eacute;cut&eacute; apr&egrave;s la fin d&#39;un t&eacute;l&eacute;chargement';
$LN['config_allow_global_scripts']    = 'Autoriser les scripts globaux';
$LN['config_allow_user_scripts_msg']  = 'Autoriser les scripts r&eacute;gl&eacute;s par les utilisateurs pour &ecirc;tre ex&eacute;cut&eacute; apr&egrave;s la fin d&#39;un t&eacute;l&eacute;chargement';
$LN['config_allow_user_scripts']      = 'Autoriser les scripts utilisateurs';

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
$LN['config_subdownloader_pars_msg'] 	= 'Param&ecirc;tres de subdownloader';
$LN['config_compress_nzb']      = 'Compresser les fichiers NZB';
$LN['config_compress_nzb_msg']  = 'Compresser les fichiers NZB apr&egrave;s leur t&eacute;l&eacute;chargement';
$LN['config_webdownload_msg']   = 'Les utilisateurs peuvent t&eacute;l&eacute;charger les fichiers en tant qu&#39;archives dans les pages de l&#39;explorateur de fichier';
$LN['config_maxfilesize_msg']   = 'Taille maximale pour les fichiers affichables dans l&#39;explorateur, 0 pour aucune limite';
$LN['config_maxpreviewsize_msg']     = 'Taille maximale pour les fichiers affichables dans pr&eacute;visualiser , 0 pour aucune limite';
$LN['config_default_template']       = 'Mod&egrave;le par d&eacute;faut';
$LN['config_default_template_msg']   = 'Mod&egrave;le utilis&eacute; quand aucun n&#39;est s&eacute;lectionn&eacute; ou ne peut &ecirc;tre trouv&eacute;';
$LN['config_default_stylesheet']     = 'Feuille de style par d&eacute;faut';
$LN['config_default_stylesheet_msg'] = 'Feuille de style utilis&eacute; lorsqu&#39;aucune n&#39;est s&eacute;lectionn&eacute;e ou disponible';

$LN['config_mail_account_activated']        = 'Message de compte activ&eacute;';
$LN['config_mail_account_activated_msg']    = 'Message envoy&eacute; &agrave; l&#39;utilisateur lorsque le compte a &eacute;t&eacute; activ&eacute;';
$LN['config_mail_account_disabled']         = 'Message de compte d&eacute;sactiv&eacute;';
$LN['config_mail_account_disabled_msg']     = 'Message envoy&eacute; &agrave; l&#39;utilisateur lorsque le compte a &eacute;t&eacute; d&eacute;sactiv&eacute;';
$LN['config_mail_activate_account']         = 'Message d&#39;activation du compte';
$LN['config_mail_activate_account_msg']     = 'Message envoy&eacute; &agrave; l&#39;utilisateur lorsque le compte doit &ecirc;tre activ&eacute;';
$LN['config_mail_download_status']          = 'Message de statut de t&eacute;l&eacute;chargement';
$LN['config_mail_download_status_msg']      = 'Message envoy&eacute; &agrave; l&#39;utilisateur lorsque le t&eacute;l&eacute;chargement est termin&eacute;';
$LN['config_mail_new_interesting_sets']     = 'Message de nouveaux ensembles int&eacute;ressants';
$LN['config_mail_new_interesting_sets_msg'] = 'Message envoy&eacute; &agrave; l&#39;utilisateur lorsque user when new sets have been marked as interesting';
$LN['config_mail_new_preferences']          = 'Message de nouvelles pr&eacute;f&eacute;rences';
$LN['config_mail_new_preferences_msg']      = 'Message envoy&eacute; &agrave; l&#39;utilisateur lorsqu&#39;une nouvelle pr&eacute;f&eacute;rence a &eacute;t&eacute; ajout&eacute;e &agrave; URD';
$LN['config_mail_new_user']                 = 'Message de nouvel utilisateur';
$LN['config_mail_new_user_msg']             = 'Message envoy&eacute; &agrave; l&#39;administrateur lorsqu&#39;un nouvel utilisateur s&#39;est inscrit';
$LN['config_mail_password_reset']           = 'Message de mot de passe r&eacute;initialis&eacute;';
$LN['config_mail_password_reset_msg']       = 'Message envoy&eacute; &agrave; l&#39;utilisateur avec le nouveau mot de passe';

$LN['config_default_language_msg']   = 'Langue utilis&eacute;e quand aucun n&#39;est s&eacute;lectionn&eacute; ou ne peut &ecirc;tre trouv&eacute;';
$LN['config_default_language']  = 'Langue par d&eacute;faut';
$LN['config_scheduler_msg']     = 'Activer la planification des jobs automatiques en URDD';
$LN['config_log_level']         = 'Niveau de journalisation';
$LN['config_permissions_msg']   = 'Permissions par d&eacute;faut pour les fichiers t&eacute;l&eacute;charg&eacute;s';
$LN['config_permissions']   = 'Permissions pour les t&eacute;l&eacute;chargements';
$LN['config_group']         = 'Groupe';
$LN['config_group_msg']     = 'Groupe par d&eacute;faut pour tous les fichiers t&eacute;l&eacute;charg&eacute;s';
$LN['config_maxbuttons']    = 'Nombre maximum des options de recherche';
$LN['config_maxbuttons_msg']        = 'Nombre maximum des options de recherche affich&eacute;s sur les pages de l&#39;explorateur de fichier';
$LN['config_nntp_maxthreads_msg']   = 'Nombre de connexions parall&egrave;les que le d&eacute;mon URD peut utiliser';
$LN['config_urdd_maxthreads_msg']   = 'Nombre de t&acirc;ches simultan&eacute;es que le d&eacute;mon URD peut g&eacute;rer';
$LN['config_default_expire_time_msg']  = 'Nombre de jours par d&eacute;faut apr&egrave;s lesquels les ensembles seront consid&eacute;r&eacute;s comme expir&eacute;s';
$LN['config_expire_incomplete_msg']    = 'Nombre de jours par d&eacute;faut apr&egrave;s lesquels les ensembles incomplets seront consid&eacute;r&eacute;s comme expir&eacute;s';
$LN['config_expire_percentage_msg']    = 'Valeur sup&eacute;rieure pour le pourcentage pour lequel un ensemble sera consid&eacute;r&eacute; comme incomplet lors d&#39;une expiration anticip&eacute;e';
$LN['config_auto_expire_msg']          = 'Les anciens messages seront supprim&eacute;s lorsque la mise &agrave; jour sera compl&egrave;te';
$LN['pref_cancel_crypted_rars_msg']    = 'Analyser les fichiers pendant qu&#039;ils sont t&eacute;l&eacute;charg&eacute;s et annuler le t&eacute;l&eacute;chargement si un fichier RAR encrypt&eacute; est detect&eacute; (si le mot de passe n&#039;est pas connu)';
$LN['config_dlpath_msg']    = 'Chemin vers lequel URD t&eacute;l&eacute;chargera tous les fichiers';
$LN['config_clickjack']     = 'Activation de la protection clickjack';
$LN['config_clickjack_msg'] = 'Activez la protection clickjack afin de garantir qu&#39;URD ne sera accessible qu&#39;en pleine page et non dans un frame';
$LN['config_need_challenge']     = 'Activer la protection XSS';
$LN['config_need_challenge_msg'] = 'Activer la protection de scripts inter-sites pour assurer que les fonctions d&#39;URD ne peuvent pas &ecirc;tre &eacute;xploit&eacute;es depuis d&#39;autres sites';
$LN['config_use_encrypted_passwords']     = 'Stocker les mots de passe des comptes usenet sous forme encrypt&eacute;e';
$LN['config_use_encrypted_passwords_msg'] = 'Les mots de passe sont stock&eacute;s sous un format encrypt&eacute; en utilisant un fichier magasin de cl&eacute; s&eacute;par&eacute; pour stocker la cl&eacute;';
$LN['config_keystore_path']     = 'Emplacement du magasin de cl&eacute;s';
$LN['config_keystore_path_msg'] = 'R&eacute;pertoire o&ugrave; le magasin de cl&eacute;s sera plac&eacute;';
$LN['config_pidpath']           = 'Emplacement du fichier PID';
$LN['config_pidpath_msg']       = 'Emplacement du fichier PID utilis&eacute; pour pr&eacute;venir le d&eacute;marrage de multiples instances d&#39;URDD (laisser vide pour aucun)';
$LN['config_urdd_host_msg']     = 'Nom d&#39;h&ocirc;te ou addresse IP du d&eacute;mon URD; par d&eacute;faut, localhost (note: les adresses IPv6 doivent &ecirc;tre &eacute;crites avec [], par exemple [::1])';
$LN['config_urdd_port_msg']     = 'Num&eacute;ro de port du d&eacute;mon URD; par d&eacute;faut, 11666';
$LN['config_urdd_restart_msg']  = 'Les t&acirc;ches qui &eacute;taient ex&eacute;cut&eacute;es alors que le d&eacute;mon URD a plant&eacute; seront relanc&eacute;es si ce bouton est coch&eacute;';
$LN['config_admin_email_msg']   = 'Adresse email de l&#39;administrateur';
$LN['config_baseurl_msg']       = 'Adresse de base (URL) de votre installation URD';
$LN['config_shaping_msg']       = 'Utiliser la gestion de trafic afin de limiter la bande passante utilis&eacute;e par urdd';
$LN['config_maxdl_msg']         = 'Bande passante maximum utilis&eacute;e par le d&eacute;mon URD pour t&eacute;l&eacute;charger depuis le serveur de news';
$LN['config_maxul_msg']         = 'Bande passante maximum utilis&eacute;e par le d&eacute;mon URD pour envoyer vers le serveur de news';
$LN['config_register_msg']      = 'Si la case est coch&eacute;e, les utilisateurs pourront s&#39;enregistrer depuis la page d&#39;identification';
$LN['config_auto_reg_msg']      = 'Si la case n&#39;est pas coch&eacute;e, l&#39;administrateur doit autoriser le compte manuellement, autrement le compte est accept&eacute; automatiquement';
$LN['config_urdd_path_msg']     = 'Chemin dans lequel le script de d&eacute;marrage du d&eacute;mon URD peut &ecirc;tre trouv&eacute; (urdd.sh)';
$LN['config_unpar_path_msg']    = 'Chemin dans lequel le programme par2 peut &ecirc;tre trouv&eacute; (optionnel)';
$LN['config_unrar_path_msg']    = 'Chemin dans lequel le programme rar ou unrar peut &ecirc;tre trouv&eacute; pour l&#39;extraction (optionnel)';
$LN['config_rar_path_msg']      = 'Chemin dans lequel le programme rar ou unrar peut &ecirc;tre trouv&eacute; pour la compression (optionnel)';
$LN['config_tar_path_msg']      = 'Chemin dans lequel le programme tar peut &ecirc;tre trouv&eacute; (optionnel)';
$LN['config_unace_path_msg']    = 'Chemin dans lequel le programme unace peut &ecirc;tre trouv&eacute; (optionnel)';
$LN['config_un7zr_path_msg']    = 'Chemin dans lequel le programme 7za, 7zr or 7z peut &ecirc;tre trouv&eacute; (optionnel)';
$LN['config_unzip_path_msg']    = 'Chemin dans lequel le programme unzip peut &ecirc;tre trouv&eacute; (optionnel)';
$LN['config_gzip_path_msg']     = 'Chemin dans lequel le programme gzip peut &ecirc;tre trouv&eacute; (optionnel)';
$LN['config_unarj_path_msg']    = 'Chemin dans lequel le programme unarj peut &ecirc;tre trouv&eacute; (optionnel)';
$LN['config_file_path_msg']     = 'Chemin dans lequel le programme file peut &ecirc;tre trouv&eacute;';
$LN['config_yydecode_path_msg'] = 'Chemin dans lequel le programme yydecode peut &ecirc;tre trouv&eacute;';
$LN['config_yyencode_path_msg'] = 'Chemin dans lequel le programme yyencode peut &ecirc;tre trouv&eacute;';
$LN['config_cksfv_path_msg']    = 'Chemin dans lequel le programme cksfv peut &ecirc;tre trouv&eacute; (optionnel)';
$LN['config_trickle_path_msg']  = 'Chemin dans lequel le programme trickle peut &ecirc;tre trouv&eacute; (optionnel)';
$LN['config_period_update_msg'] = 'Fr&eacute;quence &agrave; laquelle la disponibilit&eacute; d&#39;une nouvelle version est v&eacute;rifi&eacute;e';
$LN['config_period_opt_msg']    = 'Fr&eacute;quence &agrave; laquelle la base de donn&eacute;es est optimis&eacute;e';
$LN['config_period_ng_msg']     = 'Fr&eacute;quence &agrave; laquelle la liste des newsgroups est mise &agrave; jour';
$LN['config_period_cd_msg']     = 'Fr&eacute;quence &agrave; laquelle les r&eacute;pertoires /preview et /tmp sont nettoy&eacute;s';
$LN['config_period_cdb_msg']    = 'Fr&eacute;quence &agrave; laquelle les informations volatiles sont enlev&eacute;es de la base de donn&eacute;es';
$LN['config_log_level_msg']     = 'Niveau de journalisation du d&eacute;mon URD';
$LN['config_usenet_msg']           = 'Serveur usenet auquel vous souhaitez vous connecter (pour configurer les serveurs, allez &agrave; la page admin/usenet_servers)';
$LN['config_period_sendinfo']      = 'Envoyer les infomations d&#39;ensembles';
$LN['config_period_sendinfo_msg']  = 'Envoyer les informations &agrave; URDland.com';
$LN['config_period_getinfo']       = 'R&eacute;cup&eacute;rer les informations d&#39;ensembles';
$LN['config_period_getinfo_msg']   = 'R&eacute;cup&eacute;rer les information depuis URDland.com';
$LN['config_keep_interesting']     = 'Conserver les articles int&eacute;ressants lors de l&#39;expiration';
$LN['config_keep_interesting_msg'] = 'Conserver les articles marqu&eacute; comme int&eacute;ressants lorsque le for&ccedil;age de l&#39;expiration est activ&eacute;';

$LN['config_auto_download_msg'] = 'Autoriser les utilisateurs &agrave; t&eacute;l&eacute;charger automatiquement bas&eacute; sur les termes de recherche';
$LN['config_socket_timeout_msg']= 'Nombre de secondes au bout desquelles la connexion passera en timeout et sera ferm&eacute;e; par d&eacute;faut, 30';
$LN['config_sendmail']          = 'Autoriser l&#39;envoi d&#39;e-mails';
$LN['config_sendmail_msg']      = 'Si la case est coch&eacute;e, des e-mails pourront &ecirc;tre envoy&eacute;s pour des choses telles que mot de passe oubli&eacute;, r&eacute;initialisation de mot de passe ou encore t&eacute;l&eacute;chargements termin&eacute;s.';
$LN['config_follow_link']       = 'Suivre les liens dans les fichiers NFO lors des mises &agrave; jour';
$LN['config_follow_link_msg']   = 'Si l&#39;option est coch&eacute;e, les liens contenus dans les fichiers NFO sont automatiquement parcourus apr&egrave;s la mise &agrave; jour d&#39;un groupe';

$LN['config_total_max_articles']        = 'Nombre d&#39;articles maximum t&eacute;l&eacute;charg&eacute; par mise &agrave; jour';
$LN['config_total_max_articles_msg']    = 'Nombre d&#39;articles maximum t&eacute;l&eacute;charg&eacute; par mise &agrave; jour (0 correspond &agrave; pas de limite)';

$LN['config_prog_params']       = 'Param&egrave;tres';
$LN['config_urdd_pars_msg']     = 'Param&egrave;tres urdd';
$LN['config_unpar_pars_msg']    = 'Param&egrave;tres par2';
$LN['config_unrar_pars_msg']    = 'Param&egrave;tres unrar pour l&#39;extraction';
$LN['config_rar_pars_msg']      = 'Param&egrave;tres rar pour la compression';
$LN['config_unace_pars_msg']    = 'Param&egrave;tres unace';
$LN['config_tar_pars_msg']      = 'Param&egrave;tres tar';
$LN['config_un7zr_pars_msg']    = 'Param&egrave;tres un7za';
$LN['config_unzip_pars_msg']    = 'Param&egrave;tres unzip';
$LN['config_gzip_pars_msg']     = 'Param&egrave;tres gzip';
$LN['config_unarj_pars_msg']    = 'Param&egrave;tres unarj';
$LN['config_yydecode_pars_msg'] = 'Param&egrave;tres yydecode';
$LN['config_yyencode_pars_msg'] = 'Param&egrave;tres yyencode';

$LN['config_perms']['none'] = 'Ne pas changer';
$LN['config_perms']['0400'] = 'Lecture seule pour le propri&eacute;taire (0400)';
$LN['config_perms']['0440'] = 'Lecture seule pour le propri&eacute;taire et le groupe (0440)';
$LN['config_perms']['0444'] = 'Lecture seule pour tout le monde (0444)';
$LN['config_perms']['0600'] = 'Lecture/&eacute;criture pour le propri&eacute;taire (0600)';
$LN['config_perms']['0640'] = 'Lecture/&eacute;criture pour le propri&eacute;taire, lecture seule pour le groupe (0640)';
$LN['config_perms']['0644'] = 'Lecture/&eacute;criture pour le propri&eacute;taire, lecture seule pour le reste (0644)';
$LN['config_perms']['0660'] = 'Lecture/&eacute;criture pour le propri&eacute;taire et le groupe (0660)';
$LN['config_perms']['0664'] = 'Lecture/&eacute;criture pour le propri&eacute;taire et le groupe, lecture seule pour le reste (0664)';
$LN['config_perms']['0666'] = 'Lecture/&eacute;criture pour tout le monde (0666)';

// admin log
$LN['log_title']        = 'Fichier journal';
$LN['log_nofile']       = 'Aucun fichier journal trouv&eacute;';
$LN['log_seekerror']    = 'Impossible de lire le fichier en entier';
$LN['log_unknownerror'] = 'Une erreur inattendue s&#39;est produite';
$LN['log_header']       = 'Info de journal';
$LN['log_date']         = 'Date';
$LN['log_time']         = 'Heure';
$LN['log_level']        = 'Niveau';
$LN['log_msg']          = 'Message';
$LN['log_notopenlogfile']   = 'Impossible d&#39;ouvrir le fichier journal';
$LN['log_lines']        = 'Lignes';
//$LN['log_']           = '';

// FAQ
$LN['faq_title']        = 'FAQ';

//Manual
$LN['manual_title']     = 'Manuel';

//admin users
$LN['users_title']          = 'Utilisateurs';
$LN['users_username']       = 'Nom d&#39;utilisateur';
$LN['users_email']          = 'Adresse email';
$LN['users_fullname']       = 'Nom complet';
$LN['users_isadmin']        = 'Admin';
$LN['users_autodownload']   = 'Autoriser le t&eacute;l&eacute;chargement automatique';
$LN['users_fileedit']       = 'Editer les fichiers';
$LN['users_post']           = 'Posteur';
$LN['users_post_help']      = 'Cet utilisateur est autoris&eacute; &agrave; poster sur le serveur de news';
$LN['users_password']       = 'Mot de passe';
$LN['users_add']            = 'Ajouter';
$LN['users_allow_erotica']  = 'Autoriser les contenus adultes';
$LN['users_allow_update']   = 'Autoriser les mises &agrave; jour des bases de donn&eacute;es';
$LN['users_resetpw']        = 'R&eacute;initialiser et envoyer par email le mot de passe';
$LN['users_edit']           = 'Modifier l&#039;utilisateur';
$LN['users_addnew']         = 'Ajouter un nouvel utilisateur';
$LN['users_delete']         = 'Effacer l&#039;utilisateur';
$LN['users_enable']         = 'Activer l&#039;utilisateur';
$LN['users_disable']        = 'D&eacute;sactiver l&#039;utilisateur';
$LN['users_rights']         = 'S&eacute;lectionner l&#39;&eacute;diteur';
$LN['users_rights_help']    = 'Autoriser cet utilisateur &agrave; &eacute;diter les informations d&#39;ensembles dans les pages de l&#39;explorateur de fichiers';

$LN['users_last_active']    = 'Derni&egrave;re activit&eacute; le';

$LN['error_noadmin']        = 'Pas de privil&egrave;ges administrateur';
$LN['error_accessdenied']   = 'Acc&egrave;s refus&eacute;';
$LN['error_invalidfullname']    = 'Nom complet invalide';
$LN['error_invalidusername']    = 'Nom d&#39;utilisateur invalide';
$LN['error_userexists']     = 'L&#39;utilisateur existe d&eacute;j&agrave;';
$LN['error_invalidid']      = 'ID fourni invalide';
$LN['error_nosuchuser']     = 'L&#39;utilisateur n&#39;existe pas';
$LN['error_nouserid']       = 'Aucun ID utilisateur fourni';
$LN['error_invalidchallenge']  = 'Une tentative d&#39;hame&ccedil;onnage a probablement &eacute;t&eacute; r&eacute;alis&eacute;e. L&#39;action a &eacute;t&eacute; annul&eacute;e. (Rechargez la page et r&eacute;essayez)';
$LN['error_toomanydays']    = 'Il y a seulement 24 heures par jour';
$LN['error_toomanymins']    = 'Il y a seulement 60 minutes dans une heure';
$LN['error_bogusexptime']   = 'Date d&#39;expiration saisie malform&eacute;e';
$LN['error_invalidupdatevalue'] = 'Valeur de mise &agrave; jour re&ccedil;ue invalide';
$LN['error_nodlpath']       = 'Chemin de t&eacute;l&eacute;chargement non saisi';
$LN['error_dlpathnotwritable']  = 'Chemin de t&eacute;l&eacute;chargement en lecture seule';
$LN['error_setithere']      = 'R&eacute;glez le ici';
$LN['error_nousers']        = 'Aucun utilisateur trouv&eacute;, veuillez relancer le script d&#39;installation';
$LN['error_filenotallowed'] = 'Vous n&#39;&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der &agrave; ce fichier';
$LN['error_filenotfound']   = 'Fichier introuvable';
$LN['error_filereaderror']  = 'Fichier illisible';
$LN['error_dirnotfound']    = 'Impossible d&#39;ouvrir ce r&eacute;pertoire';
$LN['error_unknown_sort']   = 'Ordre de tri inconnu';
$LN['error_invalidlinescount']  = 'Les identifiants de lignes doivent &ecirc;tre num&eacute;riques';
$LN['error_urddconnect']    = 'Impossible de se connecter au d&eacute;mon URD';
$LN['error_createdlfailed'] = 'Impossible de cr&eacute;er le t&eacute;l&eacute;chargement';
$LN['error_setsnumberunknown']  = 'Impossible de d&eacute;terminer le nombre total d&#39;ensembles';
$LN['error_noqueue']        = 'Pas de file d&#39;attente trouv&eacute;e...';
$LN['error_novalidaction']  = 'Pas d&#39;action valable trouv&eacute;e.';
$LN['error_readnzbfailed']  = 'Impossible de lire le contenu du fichier NZB';
$LN['error_nopartsinnzb']   = 'Aucune partie identifiable dans le fichier NZB';
$LN['error_invalidgroup']   = 'Groupe invalide; le nom du groupe doit exister dans /etc/group';
$LN['error_notanumber']     = 'N&#39;est pas un nombre';
$LN['error_cannotchmod']    = 'Modification des droits d&#39;acc&egrave;s interdite';
$LN['error_cannotchgrp']    = 'Modification du groupe interdite';
//$LN['error_']         = '';

// Transfers
$LN['transfers_title']          = 'T&eacute;l&eacute;chargements';
$LN['transfers_importnzb']      = 'Importer un NZB';
$LN['transfers_import']         = 'Importer';
$LN['transfers_clearcompleted'] = 'Effacer';
$LN['transfers_pauseall']       = 'Tout mettre en pause';
$LN['transfers_continueall']    = 'Tout reprendre';
$LN['transfers_nzblocation']    = 'Emplacement du fichier NZB distant';
$LN['transfers_nzblocationext'] = 'Ce peut &ecirc;tre une addresse (URL - d&eacute;butant par http://) ou un emplacement de fichier local (par ex. /tmp/file.nzb)';
$LN['transfers_nzbupload']      = 'Envoyer un fichier NZB local';
$LN['transfers_nzbuploadext']   = 'Dans le cas o&ugrave; le fichier NZB est sur votre ordinateur local, vous pouvez l&#39;envoyer au serveur URD';
$LN['transfers_uploadnzb']      = 'Envoyer un fichier NZB';
$LN['transfers_runparrar']      = 'Ex&eacute;cuter par2 et unrar';
$LN['transfers_add_setname']    = 'Ajouter le nom de l&#39;ensemble au r&eacute;pertoire de t&eacute;l&eacute;chargement';

$LN['transfers_status_removed'] = 'Retir&eacute;';
$LN['transfers_status_ready']   = 'Pr&ecirc;t &agrave; d&eacute;marrer';
$LN['transfers_status_queued']  = 'Ajout&eacute; &agrave; la liste d&#39;attente';
$LN['transfers_status_active']  = 'T&eacute;l&eacute;chargement en cours';
$LN['transfers_status_postactive']  = 'Postage en cours';
$LN['transfers_status_finished']    = 'Termin&eacute;';
$LN['transfers_status_cancelled']   = 'Annul&eacute;';
$LN['transfers_status_paused']      = 'Mis en pause';
$LN['transfers_status_stopped']     = 'Stopp&eacute;';
$LN['transfers_status_shutdown']    = 'Arr&ecirc;t en cours';
$LN['transfers_status_yyencodefailed'] = 'Erreur lors de l&#39;encodage Yenc';
$LN['transfers_status_error']       = 'Erreur';
$LN['transfers_status_complete']    = 'Traitement en cours';
$LN['transfers_status_rarfailed']   = 'Erreur lors de la compression';
$LN['transfers_status_unrarfailed'] = 'Erreur lors de la d&eacute;compression';
$LN['transfers_status_failed']      = 'Echec';
$LN['transfers_status_running']     = 'Actif';
$LN['transfers_status_crashed']     = 'Crash&eacute;';
$LN['transfers_status_par2failed']  = 'Erreur de r&eacute;paration PAR2';
$LN['transfers_status_cksfvfailed'] = 'Erreur de r&eacute;paration Cksfv';
$LN['transfers_status_dlfailed']    = 'Articles manquants';

$LN['transfers_linkview']   = 'Visualiser les fichiers';
$LN['transfers_linkdelete'] = 'Effacer';
$LN['transfers_linkcancel'] = 'Annuler';
$LN['transfers_linkstart']  = 'D&eacute;marrer';
$LN['transfers_linkedit']   = 'Editer les propri&eacute;t&eacute;s';
$LN['transfers_details']    = 'Transf&eacute;rer les details';
$LN['transfers_name']       = 'Nom de t&eacute;l&eacute;chargement';
$LN['transfers_archpass']   = 'Mot de passe de l&#39;archive';
$LN['transfers_head_started']   = 'D&eacute;but&eacute; le';
$LN['transfers_head_dlname']    = 'Nom du t&eacute;l&eacute;chargement';
$LN['transfers_head_progress']  = 'Progression';
$LN['transfers_head_username']  = 'Utilisateur';
$LN['transfers_head_speed']     = 'Vitesse';
$LN['transfers_head_options']   = 'Options';
$LN['transfers_unrar']          = 'Unrar';
$LN['transfers_unpar']          = 'Unpar';
$LN['transfers_deletefiles']    = 'Effacer les fichiers';
$LN['transfers_subdl']      = 'T&eacute;l&eacute;charger les sous-titres';
$LN['transfers_badrarinfo'] = 'Visualiser le journal rar';
$LN['transfers_badparinfo'] = 'Visualiser le journal par2';
//$LN['transfers_'] = '';

$LN['transfers_status_rarred'] = 'Compress&eacute; par Rar';
$LN['transfers_status_par2ed'] = 'Par2 cr&eacute;&eacute;';
$LN['transfers_status_yyencoded'] = 'Encod&eacute; Yenc';
$LN['transfers_head_subject']   = 'Sujet';
$LN['transfers_post']           = 'Envoi';
$LN['transfers_post_spot']      = 'Poster un spot';
$LN['transfers_posts']          = 'Envois';
$LN['transfers_downloads']      = 'T&eacute;l&eacute;chargements';
$LN['spots_post_started']       = 'Spot en cours d&#39;envoi';

// Fatal error
$LN['fatal_error_title']    = 'Message';

// admin_buttons
$LN['buttons_title']        = 'Options de recherche';
$LN['buttons_name']         = 'Nom';
$LN['buttons_url']          = 'Adresse de recherche';
$LN['buttons_add']          = 'Ajouter';
$LN['buttons_delete']       = 'Effacer';
$LN['buttons_edit']         = 'Editer';
$LN['buttons_editbutton']   = 'Modifier une option de recherche';
$LN['buttons_addbutton']    = 'Ajouter une nouvelle option de recherche';
$LN['buttons_test']         = 'Tester';
$LN['buttons_nobuttonid']   = 'Aucun ID d&#39;option de recherche saisi';
$LN['buttons_invalidname']  = 'Nom saisi invalide';
$LN['buttons_invalidurl']   = 'Adresse saisie invalide';
$LN['buttons_clicktest']    = 'Cliquez pour tester';
$LN['buttons_buttonexists'] = 'Une option de recherche portant ce nom existe d&eacute;j&agrave;';
$LN['buttons_buttonnotfound']   = 'Option de recherche introuvable';
//$LN['buttons_']       = '';

// login
$LN['login_title']          = 'Veuillez vous identifier';
$LN['login_title2']         = 'Identifiez vous pour acc&eacute;der';
$LN['login_jserror']        = 'Javascript est requis pour que l&#39;interface URD interface fonctionne correctement. Veuillez l&#39;activer, s&#39;il vous plait.';
$LN['login_oneweek']        = 'Pour une semaine';
$LN['login_onemonth']       = 'Pour un mois';
$LN['login_oneyear']        = 'Pour un an';
$LN['login_forever']        = 'Toujours';
$LN['login_closebrowser']   = 'Jusqu&#39;&agrave; ce que je ferme le navigateur';
$LN['login_login']          = 'Se connecter';
$LN['login_username']       = 'Nom d&#39;utilisateur';
$LN['login_password']       = 'Mot de passe';
$LN['login_remember']       = 'Se souvenir de moi';
$LN['login_bindip']         = 'Lier la session &agrave; l&#39;adresse IP';
$LN['login_forgot_password']    = 'Mot de passe oubli&eacute;';
$LN['login_register']       = 'Cr&eacute;er un nouveau compte';
$LN['login_failed']         = 'La combinaison nom d&#39;utilisateur/mot de passe est incorrecte';

// browse
$LN['browse_allsets']       = 'Tous les ensembles';
$LN['browse_interesting']   = 'Int&eacute;ressant';
$LN['browse_killed']        = 'Cach&eacute;';
$LN['browse_nzb']           = 'Cr&eacute;&eacute; par NZB';
$LN['browse_downloaded']    = 'T&eacute;l&eacute;charg&eacute;';
$LN['browse_addedsets']     = 'Ensembles ajout&eacute;s';
$LN['browse_allgroups']     = 'Tous les groupes';
$LN['browse_searchsets']    = 'Rechercher dans les ensembles';
$LN['browse_addtolist']     = 'Ajouter &agrave; la liste';
$LN['browse_emptylist']     = 'Vider la liste';
$LN['browse_savenzb']       = 'Sauver le fichier NZB';
$LN['browse_download']      = 'T&eacute;l&eacute;charger';
$LN['browse_subject']       = 'Sujet';
$LN['browse_age']           = 'Age';
$LN['browse_followlink']    = 'Suivre le lien';
$LN['browse_percent']       = '%';
$LN['browse_removeset']     = 'Cacher cet ensemble';
$LN['browse_deletedsets']   = 'Ensembles supprim&eacute;s';
$LN['browse_deletedset']    = 'Ensemble supprim&eacute;';
$LN['browse_deleteset']     = 'Supprimer cet ensemble';
$LN['browse_resurrectset']  = 'R&eacute;cup&eacute;rer cet ensemble';
$LN['browse_toggleint']     = 'Basculer int&eacute;ressant';
$LN['browse_schedule_at']   = 'D&eacute;marrer &agrave;';
$LN['browse_invalid_timestamp'] = 'Timestamp invalide';
$LN['browse_mergesets']     = 'Fusionner les ensembles';
$LN['browse_download_dir']  = 'R&eacute;pertoire de t&eacute;l&eacute;chargement';
$LN['browse_add_setname']   = 'Ajouter un nom d&#39;ensemble';
$LN['browse_userwhitelisted'] = 'L&#39;utilisateur est dans la liste blanche';

$LN['NZB_created']          = 'Fichier NZB cr&eacute;&eacute;';
$LN['NZB_file']             = 'Fichier NZB';
$LN['image_file']           = 'Fichier image';


// Preview
$LN['preview_autodisp']     = 'Le(s) fichier(s) devrai(en)t &ecirc;tre affich&eacute;(s) automatiquement.';
$LN['preview_autofail']     = 'Sinon, vous pouvez cliquer sur ce lien';
$LN['preview_view']         = 'Cliquez ici pour voir le fichier NZB';
$LN['preview_header']       = 'T&eacute;l&eacute;charger une pr&eacute;visualisation';
$LN['preview_nzb']          = 'Pour commencer &agrave; t&eacute;l&eacute;charger directement depuis ce fichier NZB, cliquez sur ce lien';
$LN['preview_failed']       = 'Echec de la pr&eacute;visualisation';

// FAQ
$LN['faq_content'][1] = array ('URD, qu&#39;est-ce que c&#39;est?',  'URD est un programme pour t&eacute;l&eacute;charger depuis le usenet (newsgroups) &agrave; l&#39;aide d&#39;une interface web. Il'
                    .' est &eacute;crit enti&egrave;rement en PHP, bien qu&#39;il utilise quelques programmes externes pour r&eacute;aliser certaines des t&acirc;ches'
                    .' intensives en cycles CPU. Il stocke toutes les informations dont il a besoin dans une base de donn&eacute;es g&eacute;n&eacute;rique'
                    .' (comme MySQL ou PostGreSQL). Les articles qui para&icirc;ssent &ecirc;tre li&eacute;s seront rassembl&eacute;s en ensembles.'
                    .' T&eacute;l&eacute;charger ne n&eacute;cessite que quelques clics de souris. Un fichier NZB peut &eacute;galement &ecirc;tre cr&eacute;&eacute;. Lorsque le t&eacute;l&eacute;chargement est'
                    .' termin&eacute; URD peut automatiquement v&eacute;rifier celui-ci &agrave; l&#39;aide des fichiers par2 ou sfv et d&eacute;compresser le r&eacute;sultat.'
                    .' En t&acirc;che de fond, URD utilise un programme de t&eacute;l&eacute;chargement nomm&eacute; d&eacute;mon URD (URDD). Ce d&eacute;mon prend en charge pratiquement'
                    .' toutes les interactions avec les newsgroups, les ensembles et les t&eacute;l&eacute;chargements.'
                    .' URD est sous licence GPL v3. Consultez le fichier COPYING pour les d&eacute;tails sur cette licence (en Anglais).');
$LN['faq_content'][2] = array('D&#39;o&ugrave; vient le nom?', 'URD est un acronyme de Usenet Resource Downloader. Le terme URD est d&eacute;riv&eacute; de la culture Nordique'
                       .' faisant r&eacute;f&eacute;rence au Puit de URD, qui est le puit sacr&eacute;, le Puit Source, la source d&#39;eau pour l&#39;arbre monde'
                    .' Yggdrasil. L&#39;ancien terme Anglais lui faisant r&eacute;f&eacute;rence est Wyrd. Conceptuellement la signification de URD la plus proche est celle de Destin.');

$LN['faq_content'][3] = array('Que faire dans le cas o&ugrave; &ccedil;a ne fonctionne pas?', 'Tout d&#39;abord, v&eacute;rifiez vos r&eacute;glages et si vous pouvez cr&eacute;er une connexion avec le serveur NNTP. V&eacute;rifiez les journaux d&#39;Apache et de URD (par d&eacute;faut: /tmp/urd.log). Si c&#39;est un bug, veuillez le signaler au site web sourceforge; voir <a href="http://sourceforge.net/projects/urd/">the URD sourceforge page</a>. Autrement, venez en discuter sur le <a href="http://www.urdland.com/forum/">forum URDland</a>.');

$LN['faq_content'][4] = array('URD supporte-t-il SSL?', 'Oui, depuis la version 0.4 SSL est disponible parmi les options.');
$LN['faq_content'][5] = array('URD supporte-t-il les connexions avec identification aux serveurs de news?', 'Oui.');
$LN['faq_content'][6] = array('Pourriez vous ajouter cette fonctionnalit&eacute; vraiment cool?', 'Veuillez, s&#39;il vous plait, remplir une demande de nouvelle fonctionnalit&eacute; et nous la consid&eacute;rerons. Elle pourra peut &ecirc;tre appara&icirc;tre dans la prochaine version. Consultez les demandes de nouvelle fonctionnalit&eacute; sur <a href="http://sourceforge.net/tracker/?group_id=204007&amp;atid=987882">SourceForge</a>.');
$LN['faq_content'][7] = array('Le d&eacute;mon URDD peut-il fonctionner sur une machine diff&eacute;rente de celle o&ugrave; est l&#39;interface web?', 'Techniquement, URD consiste en trois parties qui peuvent &ecirc;tre install&eacute;es sur des machines s&eacute;par&eacute;es<ul><li>La base de donn&eacute;es</li><li>URDD</li><li>L&#39;interface web</li></ul> Cependant, cela n&#39;a pas encore &eacute;t&eacute; test&eacute;.');
$LN['faq_content'][8] = array('URD peut-il fonctionner avec des fichiers NZB?', 'Oui. Il existe plusieurs options pour utiliser des fichiers NZB dans URD. Premi&egrave;rement, utiliser directement des fichiers NZB du serveur URD pour lancer une t&eacute;l&eacute;chargement. Sur la page des t&eacute;l&eacute;chargements, il existe un moyen pour envoyer &agrave; URD un fichier NZB stock&eacute; localement sur votre ordinateur. Sur la m&ecirc;me page, il existe &eacute;galement un moyen pour fournir un lien vers un fichier NZB externe. Certains newsgroups publient &eacute;galement des fichiers NZB; en utilisant le fonction de pr&eacute;visualisation pour le fichier NZB, vous pourrez directement t&eacute;l&eacute;charger gr&acirc;ce aux informations de ce fichier. Finalement, sur la page visualiser les fichiers, il existe aussi un bouton pour g&eacute;rer les NZB dans la partie actions. En dehors du c&ocirc;t&eacute; interface web, vous pouvez utiliser un r&eacute;pertoire sp&eacute;cial nomm&eacute; spool/username o&ugrave; vous pouvez d&eacute;poser un fichier NZB qui sera utilis&eacute; pour lancer un t&eacute;l&eacute;chargement. Mais il y a plus! URD peut &ecirc;tre utilis&eacute; pour cr&eacute;er des fichiers NZB depuis les index qu&#39;il a cr&eacute;&eacute; afin que vous puissiez les partager avec d&#39;autres. Ceci fonctionne de la m&ecirc;me mani&egrave;re que t&eacute;l&eacute;charger depuis la page parcourir, vous avez juste &agrave; cliquer sur le bouton NZB &agrave; la place. Il sera stock&eacute; dans le sous-r&eacute;pertoire de t&eacute;l&eacute;chargement nomm&eacute; nzb/nom_d_utilisateur.');
$LN['faq_content'][9] = array('Comment faire pour mettre &agrave; jour URD vers une nouvelle version?', 'Pour le moment, il n&#39;y a a pas de m&eacute;thode automatique pour le faire. Ce qui signifie que vous devez relancer le script d&#39;installation avec la nouvelle version et, soit choisir un nouveau nom pour la base de donn&eacute;es, soit supprimer la base de donn&eacute;es courante au pr&eacute;alable.');
$LN['faq_content'][10] = array('Quelle licence emploie URD?', 'La majorit&eacute; du code est GPL v3. Quelques parties ont &eacute;t&eacute; emprunt&eacute;es &agrave; d&#39;autres projets et utilisent une autre licence.');
$LN['faq_content'][11] = array('Pour r&eacute;cup&eacute;rer URD, devrais-je utiliser l&#39;archive t&eacute;l&eacute;chargeable ou suberversion?', 'Il est fortement recommand&eacute; d&#39;utiliser les archives publi&eacute;es offciellement et non subversion. Les sources subversion pourraient ne pas fonctionner ou avoir des fonctionnalit&eacute;s partiellement impl&eacute;ment&eacute;es. Elles sont &eacute;quivalentes &agrave; des compilations nocturnes dans d&#39;autres projets; donc, s&#39;il vous plait, t&eacute;l&eacute;chargez les versions officielles.');
$LN['faq_content'][12] = array('Ma question n&#39;est pas dans cette liste. Que faire?', 'Veuillez laisser un message sur le forum <a href="http://www.URDland.com/forum/">Urdland</a>.');
$LN['faq_content'][13] = array('J&#39;aimerais faire un don &agrave; ce projet. Comment faire?', 'Merveilleux! Un preuve d&#39;appreciation est toujours la bienvenue: nous n&#39;avons pas beaucoup de d&eacute;penses mais l&#39;h&eacute;bergement nous co&ucirc;te environ 50 euros par an. La mani&egrave;re la plus simple pour nous serait d&#39;utiliser PayPal. Il existe un bouton pour les dons <a href="http://urdland.com/cms/component/option,com_wrapper/Itemid,33/">ici</a>. Si vous souhaitez utiliser une m&eacute;thode diff&eacute;rente, veuillez nous envoyer un email &agrave; "dev@ urdland . com" ou utiliser la messagerie interne du forum et nous &eacute;changerons des informations telles qu&#39;adresses ou num&eacute;ros de comptes bancaires.');

$LN['manual_content'][1] = array ('G&eacute;n&eacute;ral', 'La plupart des parties de l&#39;interface web de URD poss&egrave;de une aide contextuelle sous la fome de popups ou bulles. Passer la souris au dessus d&#39;un lien ou d&#39;un texte fera appra&icirc;tre cette fonction d&#39;aide.');

$LN['manual_content'][2] = array ('Newsgroups', 'Apr&egrave;s l&#39;installation, vous pouvez vous connecter &agrave; votre interface web URD, cliquer sur le lien newsgroups et chercher le groupe auquel vous souhaitez souscrire. Si aucun newsgroup n&#39;est trouv&eacute;, rendez-vous sur la page d&#39;administration et cliquez sur "mettre &agrave; jour la liste des newsgroups". Dans le cas o&ugrave; cela ne changerait rien, v&eacute;rifiez les pr&eacute;f&eacute;rences. Dans la vue d&#39;ensemble des newsgroups, la colonne expiration montre le nombre de jours apr&egrave;s lequel les articles arriveront &agrave; expiration. Il est &eacute;galement possible de mettr &agrave;jour automatiquement chaque groupe. Entrez un nombre, s&eacute;lectionnez "heures", "jours" ou "semaines" puis entrez l&#39;heure &agrave; laquelle la mise &agrave; jour doit s&#39;effectuer et pressez le bouton GO. La suppression d&#39;une mise &agrave; jour planifi&eacute;e peut &ecirc;tre r&eacute;alis&eacute;e par l&#39;effacement de l&#39;heure de mise &agrave; jour et le clic du bouton GO.');

$LN['manual_content'][3] = array ('Flux RSS', 'Alternativement vous pouvez souscrire &agrave; des flux RSS &eacute;galement. Les flux doivent &ecirc;tre ajout&eacute;s tout d&#39;abord en cliquant sur ajouter nouveau puis en saisissant les informations n&eacute;cessaires, incluant le lien URL du flux RSS. Les op&eacute;rations suivantes sont tr&egrave;s similaires &agrave; celles pour les newsgroups.');

$LN['manual_content'][4] = array ('Parcourir', 'Lorsque la mise &agrave; jour est termin&eacute;e, allez &agrave; la page "parcourir les ensembles" qui pr&eacute;sente les ensembles disponibles. Cliquer sur le "?" en face d&#39;un ensemble affiche les d&eacute;tails de celui-ci. Le petit "+" permet de s&eacute;lectionner un ensemble &agrave; t&eacute;l&eacute;charger. Apr&egrave;s avoir s&eacute;lectionn&eacute; l&#39;ensemble, appuyez sur le bouton "\/" pour d&eacute;marrer le t&eacute;l&eacute;chargement. Le bouton NZB sauve les ensembles s&eacute;lectionn&eacute;s sous forme de fichier NZB. Le "x" d&eacute;selectionne les ensembles. Les boutons sur la droite peuvent &ecirc;tre utilis&eacute;s pour rechercher plus d&#39;informations sur un ensemble. Premi&egrave;rement, s&eacute;lectionnez le texte d&#39;un ensemble puis cliquez sur l&#39;un des boutons pour ouvrir une nouvelle fen&ecirc;tre ou onglet contenant les r&eacute;sultats de la recherche. Le bouton "&eacute;diter" peut &ecirc;tre utilis&eacute; pour ajouter plus d&#39;informations &agrave; l&#39;ensemble qui peut &ecirc;tre partag&eacute; avec d&#39;autres utilisateurs.');

$LN['manual_content'][5] = array ('T&eacute;l&eacute;chargements', 'Lorsqu&#39;un t&eacute;l&eacute;chargement a d&eacute;marr&eacute;, sa progression peut &ecirc;tre visualis&eacute;e dans la section des t&eacute;l&eacute;chargements. Celle-ci montrera &eacute;galement le statut du t&eacute;l&eacute;chargement. Un lien direct vers le r&eacute;pertoire de t&eacute;l&eacute;chargement est fourni ici. Les t&eacute;l&eacute;chargements peuvent aussi &ecirc;tre renomm&eacute;s, mis en pause, annul&eacute;s, red&eacute;marr&eacute;s, etc...');

$LN['manual_content'][6] = array ('Visualiser les fichiers', 'Au travers de la page "visualiser les fichiers", tous les fichiers t&eacute;l&eacute;charg&eacute;s sont visibles et peuvent &ecirc;tre parcourus et effac&eacute;s.');

$LN['manual_content'][7] = array ('Administration', 'La page administration peut &ecirc;tre utilis&eacute;e pour la plupart des fonctions d&#39;administration telles que d&eacute;marrer ou arr&eacute;ter le d&eacute;mon URD, annuler ou mettre en pause toutes les actions, supprimer des t&acirc;ches de la base de donn&eacute;es. Elle peut &eacute;galement servir &agrave; mettre &agrave; jour la totalit&eacute; des newsgroups ou forcer l&#39;expiration de tous les anciens messages dans les newsgroups, g&eacute;rer les utlisateurs et optimiser la base de donn&eacute;es. De plus, elle donne une vue d&#39;ensemble des t&acirc;ches r&eacute;centes et le statut du d&eacute;mon URDD. La configuration de URD peut aussi &ecirc;tre trouv&eacute;e ici.');

$LN['manual_content'][8] = array ('Configuration','Cette page est utilis&eacute;e pour g&eacute;rer la majorit&eacute; des r&eacute;glages de URDD');
$LN['manual_content'][9] = array ('Serveurs Usenet','Ici, vous pouvez configurer les serveurs usenet. Il existe deux fa&ccedil;ons d&#39;utiliser un serveur usenet. 1. en tant que serveur de t&eacute;l&eacute;chargement binaire qui peut &ecirc;tre contr&ocirc;l&eacute; par le bouton activer/d&eacute;sactiver. Plusieurs serveurs de ce type peuvent &ecirc;tre s&eacute;l&eacute;ctionn&eacute;s. 2. en tant que serveur d&#39;indexation, type pour lequel seul un peut &ecirc;tre actif. Cela est s&eacute;lectionnable par la case &agrave; cocher "primaire"');
$LN['manual_content'][10] = array ('Contr&ocirc;le','Ici vous pouvez r&eacute;aliser quelques interactions basiques avec URDD comme l&#39;arr&eacute;ter ou le d&eacute;marrer, nettoyer la base de donn&eacute;es, supprimer tous les newsgroups etc...');
$LN['manual_content'][11] = array ('T&acirc;ches','Ceci pr&eacute;sente une vue d&#39;ensemble de toutes les t&acirc;ches en cours ou en attente');
$LN['manual_content'][12] = array ('Jobs','URDD peut planifier l&#39;ex&eacute;cution de t&acirc;ches &agrave; une heure ou une date donn&eacute;e; ici est donn&eacute; une vue d&#39;ensemble de toutes les t&acirc;ches planifi&eacute;es');
$LN['manual_content'][13] = array ('Utilisateurs','La page utilisateurs est un gestionnaire de comptes utilisateurs; vous pouvez y modifier les droits, ajouter, supprimer ou d&eacute;sactiver un compte utilisateur');
$LN['manual_content'][14] = array ('Boutons','Sur cette page sont d&eacute;finis les boutons comme ceux pr&eacute;sents sur la page parcourir. L&#39;adresse de recherche devrait contenir un $q, qui sera automatiquement remplac&eacute; par la cha&icirc;ne de caract&egrave;res contenant les crit&egrave;res de recherche');
$LN['manual_content'][15] = array ('Journaux','Ici, vous pouvez consulter le fichier journal de URD, le parcourir, etc... V&eacute;rifiez celui-ci si une erreur s&#39;est produite.');

$LN['manual_content'][16] = array ('Pr&eacute;f&eacute;rences', 'La page Pr&eacute;f&eacute;rences peux &ecirc;tre utilis&eacute;e pour modifier la majorit&eacute; des r&eacute;glages utilisateurs.');

$LN['manual_content'][17] = array ('Vue du statut', 'Sur la gauche de l&#39;&eacute;cran, il y a toujours une petite fen&ecirc;tre contenant le statut du d&eacute;mon URDD, actif ou inactif, les t&acirc;ches courantes et l&#39;espace disque disponible. Le nom de l&#39;utilisateur courant est &eacute;galement affich&eacute;. Cela pr&eacute;viendra aussi si une nouvelle version de URD est disponible.');

$LN['manual_content'][18] = array ('Ca ne fonctionne pas', 'Tout d&#39;abord, v&eacute;rifiez vos r&eacute;glages et si vous pouvez cr&eacute;er une connexion avec le serveur NNTP. Tentez &agrave; nouveau l&#39;action avec le niveau de jounalisation r&eacute;gl&eacute; pour le debug et v&eacute;rifiez les journaux d&#39;Apache et de URD (par d&eacute;faut: /tmp/urd.log). Si c&#39;est un bug, veuillez le signaler au site web sourceforge. Autrement, venez en discuter sur le <a href="http://www.urdland.com/forum/">forum URDland</a>. Veillez &agrave; ajouter autant d&#39;information que possible lors du rapport de bug ou d&#39;autres probl&egrave;mes, en incluant les entr&eacute;es de fichiers journaux concern&eacute;es, les messages d&#39;erreur et les r&eacute;glages. La <a href="debug.php">page debug</a> peut &eacute;galement &ecirc;tre utilis&eacute;e pour collecter toutes les informations du d&eacute;mon URD.');

// ajax_showsetinfo:
$LN['showsetinfo_postedin'] = 'Post&eacute; dans';
$LN['showsetinfo_postedby'] = 'Post&eacute; par';
$LN['showsetinfo_size']     = 'Taille totale';
$LN['showsetinfo_shouldbe'] = 'Devrait &ecirc;tre';
$LN['showsetinfo_par2']     = 'Par2';
$LN['showsetinfo_setname']  = 'Saisir le nom';
$LN['showsetinfo_typeofbinary'] = 'Type de binaire';

// download basket
$LN['basket_totalsize']     = 'Taille totale';
$LN['basket_setname']       = 'Nom de t&eacute;l&eacute;chargement';

// usenet servers
$LN['usenet_title']         = 'Serveurs Usenet';
$LN['usenet_name']          = 'Nom';
$LN['usenet_hostname']      = 'Nom d&#39;h&ocirc;te';
$LN['usenet_port']          = 'Port';
$LN['usenet_secport']       = 'Port s&eacute;curis&eacute;';
$LN['usenet_authentication']    = 'Identification';
$LN['usenet_username']      = 'Nom d&#39;utilisateur';
$LN['usenet_password']      = 'Mot de passe';
$LN['usenet_threads']       = 'Connexions';
$LN['usenet_connection']    = 'Encryption';
$LN['usenet_needsauthentication']       = 'N&eacute;cessite une identification';
$LN['usenet_addnew']        = 'Ajouter nouveau';
$LN['usenet_nrofthreads']   = 'Nombre de connexions';
$LN['usenet_connectiontype'] = 'Type d&#39;encryption';
$LN['usenet_name_msg']      = 'Nom sous lequel le serveur usenet sera connu';
$LN['usenet_hostname_msg']  = 'Nom d&#39;h&ocirc;te du serveur usenet (note: les adresses IPv6 doivent &ecirc;tre &eacute;crites entre [])';
$LN['usenet_port_msg']      = 'Num&eacute;ro de port du serveur usenet pour les connexions en clair';
$LN['usenet_secport_msg']   = 'Num&eacute;ro de port du serveur usenet pour les connexions SSL ou TLS';
$LN['usenet_needsauthentication_msg']    = 'Cochez si le serveur usenet n&eacute;cessite une identification';
$LN['usenet_username_msg']  = 'Nom d&#39;utilisateur &agrave; utiliser si le serveur n&eacute;cessite une identification';
$LN['usenet_password_msg']  = 'Mot de passe &agrave; utiliser si le serveur n&eacute;cessite une identification';
$LN['usenet_nrofthreads_msg']   = 'Nombre maximum de threads ex&eacute;cut&eacute;s en parall&egrave;le sur ce serveur';
$LN['usenet_connectiontype_msg']    = 'Type d&#39;encryption utilis&eacute; pour la connexion au serveur usenet';
$LN['usenet_priority']      = 'Priorit&eacute;';
$LN['usenet_priority_msg']  = 'Priorit&eacute;: 1 la plus haute; 100 la plus basse; 0 d&eacute;sactiv&eacute;';
$LN['usenet_enable']        = 'Activer';
$LN['usenet_disable']       = 'D&eacute;sactiver';
$LN['usenet_delete']        = 'Supprimer le serveur';
$LN['usenet_edit']          = 'Editer le serveur';
$LN['usenet_preferred_msg'] = 'Ceci est le serveur primaire, utilis&eacute; pour ind&eacute;xer les groupes';
$LN['usenet_set_preferred_msg'] = 'Utiliser ce serveur comme serveur primaire pour ind&eacute;xer les groupes';
$LN['usenet_indexing']      = 'Indexer';
$LN['usenet_addserver']     = 'Ajouter un nouveau serveur usenet';
$LN['usenet_editserver']    = 'Modifier un serveur usenet';
$LN['usenet_compressed_headers']    = 'Utiliser des ent&ecirc;tes compress&eacute;s';
$LN['usenet_compressed_headers_msg'] = 'Utiliser des ent&ecirc;tes compress&eacute;s pour la mise &agrave; jour des groupes. Peut ne pas &ecirc;tre support&eacute; par tous les serveurs. V&eacute;rifiez l&#39;existance de la commande XZVER.';
$LN['usenet_posting']       = 'Postage';
$LN['usenet_posting_msg']   = 'Autoriser le postage';
//$LN['usenet_']        = '';

$LN['usenet_preferred']     = 'Pr&eacute;f&eacute;r&eacute;';
$LN['usenet_set_preferred'] = 'Activer pr&eacute;f&eacute;r&eacute;';

$LN['forgot_title']     = 'Mot de passe oubli&eacute;';
$LN['forgot_username']  = 'Utilisateur';
$LN['forgot_email']     = 'Adresse Email';
$LN['forgot_sent']      = 'Mot de passe envoy&eacute;';
$LN['forgot_mail']      = 'Envoyer';

$LN['browse_tag_setname']   = 'Nom d&#39;ensemble';
$LN['browse_tag_year']      = 'Ann&eacute;e';
$LN['browse_tag_name']      = 'Nom';
$LN['browse_tag_lang']      = 'Langue audio';
$LN['browse_tag_sublang']   = 'Langue de sous-titres';
$LN['browse_tag_artist']    = 'Artiste';
$LN['browse_tag_quality']   = 'Qualit&eacute;';
$LN['browse_tag_runtime']   = 'Runtime';
$LN['browse_tag_movieformat']   = 'Format du film';
$LN['browse_tag_audioformat']   = 'Format audio';
$LN['browse_tag_musicformat']   = 'Format de la musique';
$LN['browse_tag_imageformat']   = 'Format de l&#39;image';
$LN['browse_tag_softwareformat']= 'Format du logiciel';
$LN['browse_tag_gameformat']    = 'Format du jeu';
$LN['browse_tag_gamegenre']     = 'Type de jeu';
$LN['browse_tag_moviegenre']    = 'Type de film';
$LN['browse_tag_musicgenre']    = 'Type de musique';
$LN['browse_tag_imagegenre']    = 'Type d&#39;image';
$LN['browse_tag_softwaregenre'] = 'Type de logiciel';
$LN['browse_tag_os']            = 'Syst&egrave;me d&#39;exploitation';
$LN['browse_tag_genericgenre']  = 'Genre';
$LN['browse_tag_episode']       = 'Episode';
$LN['browse_tag_moviescore']    = 'Classement du film';
$LN['browse_tag_score']         = 'Classement';
$LN['browse_tag_musicscore']    = 'Classement de la musique';
$LN['browse_tag_movielink']     = 'Lien vers des films';
$LN['browse_tag_link']          = 'Lien';
$LN['browse_tag_musiclink']     = 'Lien vers de la musique';
$LN['browse_tag_serielink']     = 'Lien vers des s&eacute;ries';
$LN['browse_tag_xrated']        = 'Class&eacute; X';
$LN['browse_tag_note']          = 'Commentaires';
$LN['browse_tag_author']        = 'Auteur';
$LN['browse_tag_ebookformat']   = 'Format eBook';
$LN['browse_tag_password']      = 'Mot de passe';
$LN['browse_tag_copyright']     = 'Prot&eacute;g&eacute; par Copyright';

$LN['quickmenu_setsearch']      = 'Rechercher';
$LN['quickmenu_addblacklist']   = 'Ajouter le spotter &aacute; la liste noire';
$LN['quickmenu_addposterblacklist']   = 'Ajouter le posteur &agrave; la liste noire';
$LN['quickmenu_addglobalblacklist']   = 'Ajouter le spotter &agrave; la liste noire globale';
$LN['quickmenu_addglobalwhitelist']   = 'Ajouter le spotter &agrave; la liste blanche globale';
$LN['quickmenu_addwhitelist']   = 'Ajouter le spotter &agrave; la liste blanche';
$LN['quickmenu_report_spam']    = 'Rapporter le spot en tant que spam';
$LN['quickmenu_comment_spot']   = 'Poster un commentaire sur le spot';
$LN['quickmenu_editspot']       = 'Editer le spot';
$LN['quickmenu_setshowesi']     = 'Afficher les informations de l&#39;ensemble';
$LN['quickmenu_seteditesi']     = 'Editer les informations de l&#39;ensemble';
$LN['quickmenu_setguessesi']    = 'Deviner les informations de l&#39;ensemble';
$LN['quickmenu_setbasketguessesi']= 'Deviner les informations d&#39;ensemble pour tout le panier de t&eacute;l&eacute;chargement';
$LN['quickmenu_setguessesisafe']= 'Deviner les informations de l&#39;ensemble et valider';
$LN['quickmenu_setpreviewnfo']  = 'Pr&eacute;visualiser le fichier NFO';
$LN['quickmenu_setpreviewimg']  = 'Pr&eacute;visualiser le fichier image';
$LN['quickmenu_setpreviewnzb']  = 'Pr&eacute;visualiser le fichier NZB';
$LN['quickmenu_setpreviewvid']  = 'Pr&eacute;visualiser le fichier vid&eacute;o';
$LN['quickmenu_add_search']     = 'Automatiquement en surbrillance';
$LN['quickmenu_add_block']      = 'Masquer automatiquement';

$LN['blacklist_spotter']        = 'Ajouter le spotter &agrave; la liste noire?';
$LN['whitelist_spotter']        = 'Ajouter le spotter &agrave; la liste blanche?';

$LN['stats_title'] = 'Statistiques';
$LN['stats_dl']    = 'T&eacute;l&eacute;chargements';
$LN['stats_pv'] = 'Pr&eacute;visualisations';
$LN['stats_im'] = 'Fichiers NZB import&eacute;s';
$LN['stats_gt'] = 'Fichiers NZB t&eacute;l&eacute;charg&eacute;s';
$LN['stats_wv'] = 'Consultation internet';
$LN['stats_ps'] = 'Envois';
$LN['stats_total']  = 'Taille totale';
$LN['stats_number'] = 'Compteur';
$LN['stats_user']   = 'Utilisateur';
$LN['stats_year']   = 'Ann&eacute;e';
$LN['stats_overview']   = 'Vue d&#39;ensemble';

$LN['stats_spotsbymonth'] = 'Spots par mois';
$LN['stats_spotsbyweek']  = 'Spots par semaine';
$LN['stats_spotsbyhour']  = 'Spots par heure';
$LN['stats_spotsbydow']   = 'Spots par jour de la semaine';

$LN['feeds_title']      = 'Flux RSS';
$LN['feeds_rss']        = 'Flux RSS';
$LN['feeds_auth']       = 'Identification';
$LN['feeds_tooltip_active'] = 'Le flux RSS est actif';
$LN['feeds_tooltip_name']   = 'Nom du flux RSS';
$LN['feeds_tooltip_posts']  = 'Nombre de liens dans le flux RSS';
$LN['feeds_tooltip_lastupdated']= 'Derni&egrave;re de de modification';
$LN['feeds_tooltip_expire'] = 'Dur&eacute;e d&#39;expiration en jours';
$LN['feeds_tooltip_visible']= 'RSS est visible';
$LN['feeds_tooltip_auth']   = 'Le serveur de Flux RSS n&eacute;cessite une identification';
$LN['feeds_name']           = 'Nom';
$LN['feeds_lastupdated']    = 'Derni&egrave;re mise &agrave; jour';
$LN['feeds_expire_time']    = 'D&eacute;lai d&#39;expiration';
$LN['feeds_visible']        = 'Visible';
$LN['feeds_tooltip_autoupdate'] = 'Mettre &agrave; jour automatiquement';
$LN['feeds_autoupdate'] = 'Mise &agrave; jour auto';
$LN['feeds_time']       = 'Heure';
$LN['feeds_searchtext'] = 'Rechercher dans tous les flux RSS disponibles';
$LN['feeds_url']        = 'URL';
$LN['feeds_tooltip_url']    = 'URL';
$LN['feeds_tooltip_uepev']  = 'Editer/Mettre &agrave; jour/Forcer l&#39;expiration/Purger/Effacer';
$LN['feeds_edit']       = 'Editer';
$LN['feeds_remove']     = 'Supprimer';
$LN['feeds_addfeed']    = 'Ajouter un nouveau flux RSS';
$LN['feeds_editfeed']   = 'Modifier le flux';
$LN['feeds_allgroups']  = 'Tous les flux';
$LN['feeds_hide_empty'] = 'Cacher les flux inactifs';
$LN['menurssfeeds']     = 'Flux RSS';
$LN['menuspots']        = 'Spots';
$LN['menu_overview']    = 'Param&egrave;tres';
$LN['menursssets']      = 'Ensembles RSS';
$LN['menugroupsets']    = 'Ensembles de groupes';

$LN['error_invalidfeedid']  = 'ID de flux invalide';
$LN['error_feednotfound']   = 'Flux non trouv&eacute;';

$LN['config_formatstrings'] = 'Formatage de cha&icirc;ne';
$LN['config_formatstring']  = 'Formatage de cha&icirc;ne pour';

$LN['newcategory']          = 'Nouvelle cat&eacute;gorie';
$LN['nocategory']           = 'Aucune cat&eacute;gorie';
$LN['category']             = 'Cat&eacute;gorie';
$LN['categories']           = 'Cat&eacute;gories';
$LN['name']                 = 'Nom';
$LN['editcategories']       = 'Editer les cat&eacute;gories';
$LN['ng_tooltip_category']  = 'Cat&eacute;gorie';

$LN['post_message']         = 'Poster un message';
$LN['post_messagetext']     = 'Texte du message';
$LN['post_messagetextext']  = 'Contenu du message &agrave; poster';
$LN['post_newsgroupext2']   = 'Newsgroup dans lequel le message sera post&eacute;';
$LN['post_subjectext2']     = 'Ligne du sujet dans le message';

$LN['settype'][urd_extsetinfo::SETTYPE_UNKNOWN] = $LN['config_formatstring'] . ' Inconnu';
$LN['settype'][urd_extsetinfo::SETTYPE_MOVIE] = $LN['config_formatstring'] .  ' Film';
$LN['settype'][urd_extsetinfo::SETTYPE_ALBUM] = $LN['config_formatstring'] .  ' Album';
$LN['settype'][urd_extsetinfo::SETTYPE_IMAGE] = $LN['config_formatstring'] .  ' Image';
$LN['settype'][urd_extsetinfo::SETTYPE_SOFTWARE] = $LN['config_formatstring'] .  ' Logiciel';
$LN['settype'][urd_extsetinfo::SETTYPE_TVSERIES] = $LN['config_formatstring'] . ' Series TV';
$LN['settype'][urd_extsetinfo::SETTYPE_EBOOK] = $LN['config_formatstring'] . ' Ebook';
$LN['settype'][urd_extsetinfo::SETTYPE_GAME] = $LN['config_formatstring'] . ' Jeu';
$LN['settype'][urd_extsetinfo::SETTYPE_TVSHOW] = $LN['config_formatstring'] . ' Magazine TV';
$LN['settype'][urd_extsetinfo::SETTYPE_DOCUMENTARY] = $LN['config_formatstring'] . ' Documentaire';
$LN['settype'][urd_extsetinfo::SETTYPE_OTHER] = $LN['config_formatstring'] . ' Autre';

$LN['settype_syntax'] = '%(n.mc); o&ugrave; <i>()</i> indique un imbriquement optionnel, peut &ecirc;tre (), [] ou {}; <i>n</i> une valeur de remplissage optionnelle, <i>.m</i> une valeur de longueur maximum optionnelle, <i>c</i> un carat&egrave;re obligatoire tel que d&eacute;crite ci-dessous (utilisez %% pour afficher un %, consultez &eacute;galement la documentation PHP pour sprintf):<br/><br/>';

$LN['settype_msg'][urd_extsetinfo::SETTYPE_UNKNOWN] = $LN['settype_syntax'] . 'Type d&#39;ensemble Inconnu:<br/>%n: nom<br/>%t: type s&eacute;lectionn&eacute;<br/>%T: ic&ocirc;ne d&eacute;pendante du type <br/>%P: prot&eacute;g&eacute; par mot de passe<br/>%C: produit sous copyright';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_MOVIE] =  $LN['settype_syntax'] . 'Type d&#39;ensemble Film: <br/>%n: nom<br/>%t: type s&eacute;lectionn&eacute;<br/>%T: ic&ocirc;ne d&eacute;pendante du type<br/>%y: ann&eacute;e<br/>%m: format du film<br/>%a: format audiot<br/>%l: langue<br/>%s: langue des sous-titres<br/>%x: class&eacute; X<br/>%N: notes<br/>%q: qualit&eacute;<br/>%P: prot&eacute;g&eacute; par mot de passe<br/>%C: produit sous copyright <br/>';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_ALBUM] =  $LN['settype_syntax'] . 'Type d&#39;ensemble Album: <br/>%n: nom<br/>%t: type s&eacute;lectionn&eacute;<br/>%T: ic&ocirc;ne d&eacute;pendante du type<br/>%y: ann&eacute;e <br/>%f: format<br/>%g: genre<br/>%N: notes<br/>%q: qualit&eacute;<br/>%P: prot&eacute;g&eacute; par mot de passe<br/>%C: produit sous copyright <br/>';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_IMAGE] =  $LN['settype_syntax'] . 'Type d&#39;ensemble Image: <br/>%n: nom<br/>%t: type s&eacute;lectionn&eacute;<br/>%T: ic&ocirc;ne d&eacute;pendante du type <br/>%f: format<br/>%g: genre<br/>%N: notes<br/>%q: qualit&eacute;<br/>%x: class&eacute; X<br/>%P: prot&eacute;g&eacute; par mot de passe<br/>%C: produit sous copyright';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_SOFTWARE] =  $LN['settype_syntax'] . 'Type d&#39;ensemble Logiciel: <br/>%n: nom<br/>%t: type s&eacute;lectionn&eacute;<br/>%T: ic&ocirc;ne d&eacute;pendante du type<br/>%o: syst&egrave;me d&#39;exploitation <br/>%q: qualit&eacute;<br/>%N: notes<br/>%P: prot&eacute;g&eacute; par mot de passe<br/>%C: produit sous copyright';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_TVSERIES] = $LN['settype_syntax'] .  'Type d&#39;ensemble S&eacute;rie TV: <br/>%n: nom<br/>%t: type s&eacute;lectionn&eacute;<br/>%T: ic&ocirc;ne d&eacute;pendante du type<br/>%e: &eacute;pisode<br/>%m: format du film<br/>%a: format audiot<br/>%x: class&eacute; X<br/>%q: qualit&eacute;<br/>%N: notes<br/>%P: prot&eacute;g&eacute; par mot de passe<br/>%C: produit sous copyright';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_EBOOK] =  $LN['settype_syntax'] . 'Type d&#39;ensemble Ebook: <br/>%n: nom<br/>%t: type s&eacute;lectionn&eacute;<br/>%T: ic&ocirc;ne d&eacute;pendante du type<br/>%A: auteur<br/>%y: ann&eacute;e<br/>%f: format<br/>%q: qualit&eacute;<br/>%g: genre<br/>%N: notes<br/>%P: prot&eacute;g&eacute; par mot de passe<br/>%C: produit sous copyright';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_GAME] =  $LN['settype_syntax'] . 'Type d&#39;ensemble Jeu: <br/>%n: nom<br/>%t: type s&eacute;lectionn&eacute;<br/>%T: ic&ocirc;ne d&eacute;pendante du type<br/>%A: auteur<br/>%y: ann&eacute;e<br/>%f: format<br/>%q: qualit&eacute;<br/>%g: genre<br/>%N: notes<br/>%P: prot&eacute;g&eacute; par mot de passe<br/>%C: produit sous copyright';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_TVSHOW] =  $LN['settype_syntax'] . 'Type d&#39;ensemble Magazine TV: <br/>%n: nom<br/>%t: type s&eacute;lectionn&eacute;<br/>%T: ic&ocirc;ne d&eacute;pendante du type<br/>%m: format du film<br/>%y: ann&eacute;e<br/>%e: &eacute;pisode<br/>%f: format<br/>%q: qualit&eacute;<br/>%g: genre<br/>%N: notes<br/>%P: prot&eacute;g&eacute; par mot de passe<br/>%C: produit sous copyright';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_DOCUMENTARY] =  $LN['settype_syntax'] . 'Type d&#39;ensemble Documentaire: <br/>%n: nom<br/>%t: type s&eacute;lectionn&eacute;<br/>%T: ic&ocirc;ne d&eacute;pendante du type<br/>%A: auteur<br/>%y: ann&eacute;e<br/>%f: format<br/>%q: qualit&eacute;<br/>%g: genre<br/>%N: notes<br/>%P: prot&eacute;g&eacute; par mot de passe<br/>%C: produit sous copyright';
$LN['settype_msg'][urd_extsetinfo::SETTYPE_OTHER] =  $LN['settype_syntax'] . 'Autre type d&#39;ensemble: <br/>%n: nom<br/>%t: type s&eacute;lectionn&eacute;<br/>%T: ic&ocirc;ne d&eacute;pendante du type<br/><br/>%N: notes<br/>%P: prot&eacute;g&eacute; par mot de passe<br/>%C: produit sous copyright';

$LN['loading_files']  = 'Chargement des fichiers en cours... veuillez patienter';
$LN['loading']        = 'Chargement en cours... veuillez patienter';

$LN['spots_allcategories']    = 'Toutes les cat&egrave;gories';
$LN['spots_allsubcategories'] = 'Toutes les sous-cat&egrave;gories';
$LN['spots_subcategories']    = 'Sous-cat&egrave;gories';
$LN['spots_tag']              = 'Tag';
$LN['pref_spots_category_mapping']  = 'Affecter la cat&eacute;gorie spot &agrave;';
$LN['pref_spots_category_mapping_msg']  = 'Affectation d&#39;une cat&eacute;gorie spot aux cat&eacute;gories URD';

$LN['pref_custom_values']       = 'Valeurs personnalis&eacute;es';
$LN['pref_custom']              = 'Valeur personnalis&eacute;e';
$LN['config_custom']            = 'Valeur personnalis&eacute;e';
$LN['pref_custom_msg']          = 'Valeurs personnalis&eacute;es pouvant &ecirc;tre utilis&eacute;es dans des scripts';
$LN['spots_other']        = 'Autre';
$LN['spots_all']          = 'Tous';
$LN['spots_image']        = 'Image';
$LN['spots_sound']        = 'Son';
$LN['spots_game']         = 'Jeu';
$LN['spots_application']  = 'Application';
$LN['spots_format']       = 'Format';
$LN['spots_source']       = 'Source';
$LN['spots_language']     = 'Langue';
$LN['spots_genre']        = 'Genre';
$LN['spots_bitrate']      = 'D&eacute;bit';
$LN['spots_platform']     = 'Platform';
$LN['spots_type']         = 'Type';

$LN['spots_film']           = 'Film';
$LN['spots_series']         = 'S&eacute;ries';
$LN['spots_book']           = 'Livre';
$LN['spots_erotica']        = '&Eacute;rotique';

$LN['spots_album']          = 'Album';
$LN['spots_liveset']        = 'Live';
$LN['spots_podcast']        = 'Podcast';
$LN['spots_audiobook']      = 'Livre audio';

$LN['spots_divx']       = 'DivX';
$LN['spots_wmv']        = 'WMV';
$LN['spots_mpg']        = 'MPG';
$LN['spots_dvd5']       = 'DVD5';
$LN['spots_hdother']    = 'HD autre';
$LN['spots_ebook']      = 'Livre &eacute;lectronique';
$LN['spots_bluray']     = 'Blu-ray';
$LN['spots_hddvd']      = 'HD DVD';
$LN['spots_wmvhd']      = 'WMVHD';
$LN['spots_x264hd']     = 'x264HD';
$LN['spots_dvd9']       = 'DVD9';
$LN['spots_cam']        = 'Cam';
$LN['spots_svcd']       = '(S)VCD';
$LN['spots_promo']      = 'Promo';
$LN['spots_retail']     = 'Retail';
$LN['spots_tv']         = 'TV';
$LN['spots_satellite']  = 'Satellite';
$LN['spots_r5']         = 'R5';
$LN['spots_telecine']   = 'Telecine';
$LN['spots_telesync']   = 'Telesync';
$LN['spots_scan']       = 'Scan';

$LN['spots_daily'] = 'Journal';
$LN['spots_magazine'] = 'Magazine';
$LN['spots_comic'] = 'Comic';
$LN['spots_study']  = 'Etude';
$LN['spots_business'] = 'Affaires';
$LN['spots_economy'] = 'Economie';
$LN['spots_computer'] = 'Ordinateur';
$LN['spots_hobby'] = 'Hobby';
$LN['spots_cooking'] = 'Cuisine';
$LN['spots_crafts'] = 'Crafts';
$LN['spots_needlework'] = 'Couture';
$LN['spots_health'] = 'Sant&eacute;';
$LN['spots_history'] = 'Histoire';
$LN['spots_psychology'] = 'Psychologie';
$LN['spots_science'] = 'Science';
$LN['spots_woman'] = 'Femme';
$LN['spots_religion'] = 'Religion';
$LN['spots_novel'] = 'Roman';
$LN['spots_biography'] = 'Biographie';
$LN['spots_detective'] = 'Roman policier';
$LN['spots_animals'] = 'Animaux';
$LN['spots_humour'] = 'Humour';
$LN['spots_travel'] = 'Travel';
$LN['spots_truestory'] = 'Histoire vraie';
$LN['spots_nonfiction'] = 'Non fiction';
$LN['spots_politics'] = 'Politique';
$LN['spots_poetry'] = 'Po&eacute;sie';
$LN['spots_fairytale'] = 'Contes de f&eacute;es';
$LN['spots_technical'] = 'Technique';
$LN['spots_art'] = 'Art';
$LN['spots_bi'] = 'Erotique: Bisexuel';
$LN['spots_lesbo'] = 'Erotique: Lesbiennes';
$LN['spots_homo'] = 'Erotique: Homosexuel';
$LN['spots_hetero'] = 'Erotique: H&eacute;t&eacute;rosexuel';
$LN['spots_amateur'] = 'Erotique: Amateur';
$LN['spots_groep'] = 'Erotique: Groupe';
$LN['spots_pov'] = 'Erotique: POV';
$LN['spots_solo'] = 'Erotique: Solo';
$LN['spots_teen'] = 'Erotique: Jeunesse';
$LN['spots_soft'] = 'Erotique: Soft';
$LN['spots_fetish'] = 'Erotique: F&eacute;tiche';
$LN['spots_mature'] = 'Erotique: Matures';
$LN['spots_fat'] = 'Erotique: Gros';
$LN['spots_sm'] = 'Erotique: Sadomasochisme';
$LN['spots_rough'] = 'Erotique: Rough';
$LN['spots_black'] = 'Erotique: Noir';
$LN['spots_hentai'] = 'Erotique: Hentai';
$LN['spots_outside'] = 'Erotique: Outside';

$LN['spots_subs_non']      = 'Aucun sous-titre';
$LN['spots_subs_nl_ext']   = 'Sous-titres hollandais (external)';
$LN['spots_subs_nl_incl']  = 'Sous-titres hollandais (hardcoded)';
$LN['spots_subs_eng_ext']  = 'Sous-titres anglais (external)';
$LN['spots_subs_eng_incl'] = 'Sous-titres anglais (hardcoded)';
$LN['spots_subs_nl_opt']   = 'Sous-titres hollandais (optional)';
$LN['spots_subs_eng_opt']  = 'Sous-titres anglais (optional)';
$LN['spots_false']         = 'Faux';
$LN['spots_lang_eng']      = 'Langue: Anglais';
$LN['spots_lang_nl']       = 'Langue: Hollandais';
$LN['spots_lang_ger']      = 'Langue: Allemand';
$LN['spots_lang_fr']       = 'Langue: Fran&ccedil;ais';
$LN['spots_lang_es']       = 'Langue: Espagnol';
$LN['spots_lang_asian']    = 'Langue: Asiatique';

$LN['spots_adventure']     = 'Aventure';
$LN['spots_animation']     = 'Animation';
$LN['spots_cabaret']       = 'Cabaret';
$LN['spots_comedy']        = 'Com&egrave;die';
$LN['spots_crime']         = 'Crime';
$LN['spots_documentary']   = 'Documentaire';
$LN['spots_drama']         = 'Dramatique';
$LN['spots_family']        = 'Famille';
$LN['spots_fantasy']       = 'Fantaisie';
$LN['spots_filmnoir']      = 'Film Noir';
$LN['spots_tvseries']      = 'S&eacute;rie TV';
$LN['spots_horror']        = 'Horreur';
$LN['spots_music']         = 'Musique';
$LN['spots_musical']       = 'Musicale';
$LN['spots_mystery']       = 'Myst&egrave;re';
$LN['spots_romance']       = 'Romantique';
$LN['spots_scifi']         = 'Science-fiction';
$LN['spots_sport']         = 'Sport';
$LN['spots_short']         = 'Court-m&eacute;trage';
$LN['spots_thriller']      = 'Thriller';
$LN['spots_war']           = 'Guerre';
$LN['spots_western']       = 'Western';
$LN['spots_ero_hetero']    = '&Eacute;rotique (heterosexuel)';
$LN['spots_ero_gaymen']    = '&Eacute;rotique (homosexuel)';
$LN['spots_ero_lesbian']   = '&Eacute;rotique (lesbienne)';
$LN['spots_ero_bi']        = '&Eacute;rotique (bisexuel)';
$LN['spots_asian']         = 'Asie';
$LN['spots_anime']         = 'Anime';
$LN['spots_cover']         = 'Cover';
$LN['spots_comics']        = 'Comics';
$LN['spots_cartoons']      = 'Cartoons';
$LN['spots_children']      = 'Enfants';

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
$LN['spots_dvd']           = 'DVD';
$LN['spots_vinyl']         = 'Vinyl';
$LN['spots_stream']        = 'Flux';
$LN['spots_variable']      = 'Variable';
$LN['spots_96kbit']        = '96 kbit';
$LN['spots_lt96kbit']      = '&lt;96 kbit';
$LN['spots_128kbit']       = '128 kbit';
$LN['spots_160kbit']       = '160 kbit';
$LN['spots_192kbit']       = '192 kbit';
$LN['spots_256kbit']       = '256 kbit';
$LN['spots_320kbit']       = '320kbit';
$LN['spots_lossless']      = 'Sans perte';

$LN['spots_blues']           = 'Blues';
$LN['spots_compilation']     = 'Compilation';
$LN['spots_cabaret']         = 'Cabaret';
$LN['spots_dance']           = 'Dance';
$LN['spots_various']         = 'Divers';
$LN['spots_hardcore']        = 'Hardcore';
$LN['spots_international']   = 'International';
$LN['spots_jazz']            = 'Jazz';
$LN['spots_children']        = 'Enfant / adolescent';
$LN['spots_classical']       = 'Classique';
$LN['spots_smallarts']       = 'Arts petites';
$LN['spots_netherlands']     = 'Hollandais';
$LN['spots_newage']          = 'New Age';
$LN['spots_pop']             = 'Pop';
$LN['spots_soul']            = 'R&amp;B';
$LN['spots_hiphop']          = 'Hiphop';
$LN['spots_reggae']          = 'Reggae';
$LN['spots_religious']       = 'Religieux';
$LN['spots_rock']            = 'Rock';
$LN['spots_soundtracks']     = 'Bande originale de film';
$LN['spots_hardstyle']       = 'Hardstyle';
$LN['spots_asian']           = 'Asie';
$LN['spots_disco']           = 'Disco';
$LN['spots_oldschool']       = 'Old school';
$LN['spots_metal']           = 'Metal';
$LN['spots_country']         = 'Country';
$LN['spots_dubstep']        = 'Dubstep';
$LN['spots_nederhop']       = 'Nederhop';
$LN['spots_dnb']        = 'DnB';
$LN['spots_electro']    = 'Electro';
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
$LN['spots_action']        = 'Action';
$LN['spots_adventure']     = 'Aventure';
$LN['spots_strategy']      = 'Strategie';
$LN['spots_roleplay']      = 'Jeu de r&ocirc;les';
$LN['spots_simulation']    = 'Simulation';
$LN['spots_race']          = 'Course';
$LN['spots_flying']        = 'Simulation de vol';
$LN['spots_shooter']       = 'First Person Shooter';
$LN['spots_platform']      = 'Jeu de platformes';
$LN['spots_sport']         = 'Sports';
$LN['spots_children']      = 'Enfants / adolescents';
$LN['spots_puzzle']        = 'Puzzle';
$LN['spots_boardgame']     = 'Jeu de plateau';
$LN['spots_cards']         = 'Cartes';
$LN['spots_education']     = '&Eacute;ducation';
$LN['spots_music']         = 'Musique';
$LN['spots_family']        = 'Famille';

$LN['spots_audioedit']     = 'Montage sonore';
$LN['spots_videoedit']     = 'Montage video';
$LN['spots_graphics']      = 'Edition graphique';
$LN['spots_cdtools']       = 'Outils CD';
$LN['spots_mediaplayers']  = 'Media players';
$LN['spots_rippers']       = 'Rippers et encoders';
$LN['spots_plugins']       = 'Plugins';
$LN['spots_database']      = 'Base de donn&eacute;es';
$LN['spots_email']         = 'Logiciels E-mail';
$LN['spots_photo']         = 'Editeurs de photos';
$LN['spots_screensavers']  = 'Ecrans de veille';
$LN['spots_skins']         = 'Skins';
$LN['spots_drivers']       = 'Drivers';
$LN['spots_browsers']      = 'Navigateurs';
$LN['spots_downloaders']   = 'Gestionnaires de t&eacute;l&eacute;chargement';
$LN['spots_filesharing']   = 'Logiciel de partage de fichiers';
$LN['spots_usenet']        = 'Logiciel Usenet';
$LN['spots_rss']           = 'Logiciel RSS';
$LN['spots_ftp']           = 'Logiciel FTP';
$LN['spots_firewalls']     = 'Firewalls';
$LN['spots_antivirus']     = 'Anti-virus';
$LN['spots_antispyware']   = 'Anti-spyware';
$LN['spots_optimisation']  = 'Logiciel d&#39;optimisation';
$LN['spots_security']      = 'Logiciel de s&eacute;curit&eacute;';
$LN['spots_system']        = 'Logiciel syst&egrave;me';
$LN['spots_educational']   = 'Educatif';
$LN['spots_office']        = 'Bureau';
$LN['spots_internet']      = 'Internet';
$LN['spots_communication'] = 'Communication';
$LN['spots_development']   = 'D&eacute;veloppement';
$LN['spots_spotnet']       = 'Spotnet';
//$LN['spots_']           = '';

$LN['update_database']      = 'Mettre &agrave; jour la base de donn&eacute;es';

$LN['password_weak']        = 'S&eacute;curit&eacute; du mot de passe: faible';
$LN['password_medium']      = 'S&eacute;curit&eacute; du mot de passe: medium';
$LN['password_strong']      = 'S&eacute;curit&eacute; du mot de passe: forte';
$LN['password_correct']     = 'Les mots de passe correspondent';
$LN['password_incorrect']   = 'Les mots de passe ne correspondent pas';

$LN['dashboard_max_nntp']      = 'Nombre maximum de connexion NNTP';
$LN['dashboard_max_threads']   = 'Nombre maximum de threads';
$LN['dashboard_max_db_intensive']	    = 'Nombre maximum de threads &agrave; usage intensif de la base de donn&eacute;es';

if (isset($smarty)) { // don't do the smarty thing if we read it from urdd
    foreach ($LN as $key => $word) {
        $LN2['LN_' . $key] = $word;
    }
    $smarty->assign($LN2);
    unset($LN2);
}
