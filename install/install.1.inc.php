<?php
/*
 *  This file is part of Urd.
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
 * $LastChangedDate: 2014-05-29 09:49:55 +0200 (do, 29 mei 2014) $
 * $Rev: 3063 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: install.1.inc.php 3063 2014-05-29 07:49:55Z gavinspearhead@gmail.com $
 */


// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$php_cli = CheckPHPCLI($_SESSION['php_path']);
$php_ini_path_cli = get_php_ini_path(TRUE, ($_SESSION['php_path']));
$php_ini_path_apache = get_php_ini_path(FALSE);

// Check for PHP settings
$OUT .= '<tr><td colspan="2" class="install1">PHP Settings</td></tr>' . "\n";

$OUT .= '<tr><td class="install2">PHP version &ge; ' . MIN_PHP_VERSION . '</td>';
$OUT .= GenRetVal(CheckPHPVersion(), $rv_php);
if (!$rv_php) {
    $OUT .= ShowHelp("Please upgrade to the latest version of PHP, e.g. 'apt-get update; apt-get upgrade' as root.");
}
GenRetVal(CheckPHPVersion_rec(), $rv_php_rec);
if (!$rv_php_rec) {
    $OUT .= ShowHelp('Recommended PHP version is ' . RECOMMENDED_PHP_VERSION . ' or newer');
}

$OUT .= '<tr><td class="install2">PHP allows "exec"</td>';
$OUT .= GenRetVal(CheckPHPExec(), $rv_php2);
if (!$rv_php2) {
    $OUT .= ShowHelp('Please edit the file ' . htmlentities($php_ini_path_cli) . ' to allow use of the exec command.');
}

$OUT .= '<tr><td class="install2">PHP command line interface installed</td>';
$OUT .= GenRetVal($php_cli, $rv_php2b);
if (!$rv_php2b) {
    $OUT .= ShowHelp("Please install the PHP command line interface, e.g. try 'apt-get install php5-cli' as root.");
}

$OUT .= '<tr><td class="install2">PHP has Safe Mode disabled</td>';
$OUT .= GenRetVal(CheckPHPSafeMode(), $rv_php3);
if (!$rv_php3) {
    $OUT .= ShowHelp('Safe Mode is really outdated. You can disable it in the file ' . htmlentities($php_ini_path_apache) . '.');
}

$OUT .= '<tr><td class="install2">PHP has Register Globals disabled</td>';
$OUT .= GenRetVal(CheckPHPRegGlobals(), $rv_php4);
if (!$rv_php4) {
    $OUT .= ShowHelp('Register Globals is the biggest security hole ever. It also breaks URD in places, so you must disable it in the file ' . htmlentities($php_ini_path_apache) . '.');
}

$OUT .= '<tr><td class="install2">PHP module pcntl available</td>';
$OUT .= GenRetVal(CheckPHPpcntl($_SESSION['php_path']), $rv_php6);
if (!$rv_php6) {
    $OUT .= ShowHelp("The pcntl module is required by the URD daemon. Note that there currently is no Windows pcntl support. This and the other PHP modules should automatically be installed when you 'apt-get install php5-cli' as root.");
}

$OUT .= '<tr><td class="install2">PHP date.timezone set</td>';
$tz_cli = check_timezone(TRUE, $_SESSION['php_path']);
$tz_web = check_timezone(FALSE, $_SESSION['php_path']);
$OUT .= GenRetVal($tz_cli && $tz_web, $rv_php6b);
if (!$tz_cli) {
    $OUT .= ShowHelp('unset date.timezone settings may lead to warning and date formatting problems. Please edit ' .htmlentities($php_ini_path_cli));
}
if (!$tz_web) {
    $OUT .= ShowHelp('unset date.timezone settings may lead to warning and date formatting problems. Please edit ' .htmlentities($php_ini_path_apache));
}

$OUT .= '<tr><td class="install2">PHP module json available</td>';
$OUT .= GenRetVal(CheckPHPjson($_SESSION['php_path']), $rv_php7a);
if (!$rv_php7a) {
    $OUT .= ShowHelp('The json module is required by URD. Please restart the webserver after installing it');
}

$OUT .= '<tr><td class="install2">PHP module sockets available</td>';
$OUT .= GenRetVal(CheckPHPsockets($_SESSION['php_path']), $rv_php7);
if (!$rv_php7) {
    $OUT .= ShowHelp('The sockets module is required by the URD daemon. Please restart the webserver after installing it');
}

$OUT .= '<tr><td class="install2">PHP module posix available</td>';
$OUT .= GenRetVal(CheckPHPposix($_SESSION['php_path']), $rv_php8);
if (!$rv_php8) {
    $OUT .= ShowHelp('The posix module is required by the URD daemon.');
}

$OUT .= '<tr><td class="install2">PHP module OpenSSL available</td>';
$OUT .= GenRetVal(CheckPHPOpenSSL($_SESSION['php_path']), $rv_php8b);
if (!$rv_php8b) {
    $OUT .= ShowHelp('The openssl module is optional, needed for secure (TLS/SSL) connections to news servers.');
}

$OUT .= '<tr><td class="install2">PHP module mcrypt available</td>';
$OUT .= GenRetVal(CheckPHPmcrypt($_SESSION['php_path']), $rv_php8c);
if (!$rv_php8c) {
    $OUT .= ShowHelp('The mcrypt module is required by URD. Try: \'apt-get install php5-mcrypt; php5enmod mcrypt\' as root');
}

$OUT .= '<tr><td class="install2">PHP module GD available</td>';
$OUT .= GenRetVal(CheckPHPgd(), $rv_phpgd);
if (!$rv_phpgd) {
    $OUT .= ShowHelp('The gd module is required, needed for captchas in the registration form and statistics. Please make sure you restart the webserver after installing it. Try: \'apt-get install php5-gd\' as root');
}

$OUT .= '<tr><td class="install2">PHP module XMLreader/writer available</td>';
$OUT .= GenRetVal(CheckPHPXMLRW(), $rv_phpxmlrw);
if (!$rv_phpxmlrw) {
    $OUT .= ShowHelp('The XMLreader and XMLwriter module is optional but required for backing-up URD configuration. ' .
            'Try: \'pecl install XMLReader && pecl install XMLwriter\' as root. Please restart the webserver after installing it');
}

$OUT .= '<tr><td class="install2">PHP module CURL available</td>';
$OUT .= GenRetVal(CheckPHPCURL(), $rv_phpcurl);
if (!$rv_phpcurl) {
    $OUT .= ShowHelp('The CURL module is required by the URD daemon. Try: \'apt-get install php5-curl\' as root');
}

$OUT .= '<tr><td class="install2">PHP module GMP available</td>';
$OUT .= GenRetVal(CheckPHPGMP(), $rv_phpgmp);
if (!$rv_phpgmp) {
    $OUT .= ShowHelp('The GMP module is required by the URD daemon. Try: \'apt-get install php5-gmp\' as root');
}

$OUT .= '<tr><td class="install2">PHP module SPL available</td>';
$OUT .= GenRetVal(CheckPHPSPL($_SESSION['php_path']), $rv_php9);
if (!$rv_php9) {
    $OUT .= ShowHelp('The SPL module is required by the URD daemon.');
}
$OUT .= '<tr><td class="install2">PHP Open base dir setting</td>';
$OUT .= GenRetVal(check_open_basedir($_SESSION['php_path']), $rv_php_obd);
if (!$rv_php_obd) {
    $OUT .= ShowHelp('If the open_basedir variable is set in ' . htmlentities($php_ini_path_cli) . 'URD might not work properly.');
}

$OUT .= '<tr><td class="install2">PHP Disabled functions</td>';
$OUT .= GenRetVal(check_disabled_functions($_SESSION['php_path']), $rv_php_df);
if (!$rv_php_df) {
    $OUT .= ShowHelp('If the disabled_functions variable is set in ' . htmlentities($php_ini_path_cli) . ' URD might not work properly.');
}
$OUT .= '<tr><td class="install2">PHP Disabled classes</td>';
$OUT .= GenRetVal(check_disabled_classes($_SESSION['php_path']), $rv_php_dc);
if (!$rv_php_dc) {
    $OUT .= ShowHelp('If the disabled_classes variable is set in ' . htmlentities($php_ini_path_cli) . ' URD might not work properly.');
}
$OUT .= '<tr><td class="install2">PHP memory limit &ge; 128 MB</td>';
$OUT .= GenRetVal(CheckPHPMemory($_SESSION['php_path']), $rv_php10);
if (!$rv_php10) {
    $OUT .= ShowHelp("The current PHP memory limit (for the command line interface) is too low, please raise it to 128 MB in the (cli!) php.ini file - " . 
        htmlentities($php_ini_path_cli) . ".<br> E.g. memory_limit = 128M; note the M here, not MB!");
}
if ($rv_php && $rv_php2 && $rv_php3 && $rv_php4 && $rv_php6 && $rv_php7 && $rv_php7a && $rv_php8c && $rv_php8 && $rv_php9 && $rv_php10 && $rv_phpcurl && $rv_phpgmp && $rv_phpgd) {
    $OUT .= '<tr><td><a onclick="LoadPage(2);">'.$continuepic.'</a>';
    if (!$rv_php_df || !$rv_php_dc || !$rv_php_obd || !$tz_clii||!$tz_web) {
        $OUT .= '<a onclick="LoadPage(1);">'.$refreshpic.'</a>';
    }
    echo '</td>';
} else {
	$OUT .= 'Not all requirements are met, please fix.<br/>';
	$OUT .= '<tr colspan="2"><td><a onclick="LoadPage(1);">'.$refreshpic.'</a></td>';
}
echo '</tr>';

