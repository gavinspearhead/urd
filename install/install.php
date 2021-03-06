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
 * $LastChangedDate: 2014-06-18 23:47:21 +0200 (wo, 18 jun 2014) $
 * $Rev: 3097 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: install.php 3097 2014-06-18 21:47:21Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

// Suppress warning messages:
//error_reporting(E_ERROR | E_PARSE|);
// for testing purpose
error_reporting(E_ALL|E_STRICT|E_DEPRECATED);
// make PHP verbose

// quickfix to an error in the installer when including a language file; should do this better tho

$process_name = 'urd_web'; // needed for message format in syslog and logging
// Store variables in $_SESSION so they're remembered:
$pathlo = realpath(dirname(__FILE__));
$pathca = realpath($pathlo . '/../functions/');
session_name('URD_WEB' . md5($pathca)); // add the hashed path so we can have more than 1 session to different urds in one browser
@session_start();

require_once $pathca . '/defines.php'; 
require_once $pathca . '/extset_functions.php'; 
require_once $pathca . '/lang/english.php'; 
require_once $pathca . '/functions.php';
require_once $pathca . '/file_functions.php';
require_once $pathca . '/web_functions.php';
require_once $pathca . '/defaults.php';
require_once $pathca . '/user_functions.php';
require_once $pathca . '/config_functions.php';
require_once $pathca . '/pref_functions.php';
require_once $pathca . '/module_functions.php';
require_once $pathca . '/../urdd/urdd_protocol.php';
require_once $pathca . '/../urdd/urdd_command.php';
require_once $pathca . '/periods.php';
require_once $pathca . '/db/urd_db_structure.php';
require_once $pathca . '/search_options.php';
require_once $pathca . '/usenet_functions.php';
require_once $pathca . '/keystore_functions.php';
require_once $pathca . '/db_sqlite.php';
require_once $pathca . '/db_mysql.php';
require_once $pathca . '/db_psql.php';
require_once $pathlo . '/install.funcs.inc.php';

// Installer script:

// If .installed exists, we're already installed.
if (file_exists('../.installed'))  {
    $filename = realpath('../.installed');
echo <<<CT
<!DOCTYPE html>
<html>
<head>
<title>URD Installation script</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="robots" content="noindex, nofollow"/>
<link rel="SHORTCUT ICON" href="../html/favicon.ico" type="image/x-icon"/>
<link rel="stylesheet" href="install.css" type="text/css"/>
<script type="text/javascript" src="../html/smarty/templates/default/js/jquery-3.5.0.min.js"></script>
<script type="text/javascript" src="install.js"></script>
</head>
<body>
<h1>&nbsp;</h1>
<h2>URD is already installed.</h2> 
<h3>Remove the file <i>"$filename"</i> to enable the install script</h3>
( <i>rm $filename</i> )
<br/><br/><br/>
&nbsp;or
<h2>Proceed to <a href="../index.php">URD</a></h2>
</body>
</html>

CT;
die;
}

$maxpages = 8;
$page = 0;
if (isset($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
}

$OUT = <<<CT
<!DOCTYPE html >
<html>
<head>
<title>URD Installation script</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="SHORTCUT ICON" href="../html/favicon.ico" type="image/x-icon"/>
<link rel="stylesheet" href="install.css" type="text/css"/>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
<script type="text/javascript" src="../html/smarty/templates/default/js/jquery-3.5.0.min.js"></script>
<script type="text/javascript" src="install.js"></script>
</head>
<body>
<div id="headerleft">
    <img src="../html/smarty/templates/default/img/light/urdlogo.png" alt="Usenet Resource Downloader" class="urdlogo" title="URD"/>
</div>

<div id="separator"></div>
<br/>
<noscript>
<div id="nojs">Javascript is required for the URD interface to work correctly. Please enable.</div>
</noscript>
<div id="left">
&nbsp;
</div>
<div id="message">
</div>

<div id="installation">
<h2>Installation script ($page/$maxpages)</h2>
<form method="post" name="installform" id="installform">
<input type="hidden" name="page" id="page" value=""/>
<table class="installation">
CT;

$continuepic = '<img src="../html/smarty/templates/default/img/forward.png" alt="" title="Continue" class="buttonlike"/>';
$refreshpic  = '<img src="../html/smarty/templates/default/img/reload.png" alt="" title="Reload" class="buttonlike"/>';
$showpasspic = '<div class="inline iconsizeplus showpassicon buttonlike" alt="" title="Show password"></div>';

// An actual installation page or the frontpage?:
if ($page == 0) {
	$OUT .= <<<BDY
		<tr><td colspan="2">
		This is the installation script. <br/> It will check if PHP is configured
		correctly, if all required applications are available, if optional applications are available,
		and will ultimately help you in getting URD up and running.
		<br/><br/>
		</td></tr>
BDY;
	// Ok, we're about to install, make sure a 'config.php' file exists or all includes will throw a fatal error because require fails:
	$OUT .= '<tr><td colspan="2" class="install1">Pre-install check:</td></tr>' . "\n";

	$OUT .= '<tr><td class="install2">config.php can be written</td>';
    $OUT .= GenRetVal(@touch('../config.php'), $rv1);
    // leave this config file otherwise the installer will fail

	// And also check the smarty dir:
	$OUT .= '<tr><td class="install2">Smarty cache directory can be written to</td>';
    $OUT .= GenRetVal(@touch('../html/smarty/c_templates/default/installcheck'), $rv2);
    @unlink('../html/smarty/c_templates/default/installcheck');
	
    $OUT .= '<tr><td class="install2">Smarty template directory can be read</td>';
    $OUT .= GenRetVal(FALSE!==file_get_contents('../html/smarty/templates/default/about.tpl'), $rv2a);
    
    $OUT .= '<tr><td class="install2">URD PID directory</td>';
    $OUT .= GenRetVal(@touch('../urdd/pid/installcheck'), $rv3);
    @unlink('../urdd/pid/installcheck');

	if (!$rv1) {
        $OUT .= ShowHelp('Error: Config.php could not be written, please make sure that the file/directory permissions are correct! <br/>run the command<br/>chmod a+w ' . realpath('..') . 
            '<br/>After the installation is complete you can reset the value by running chmod 644 ' . realpath('..'));
	}
	if (!$rv2) {
        $OUT .= ShowHelp('Error: /html/smarty/c_templates/default/ could not be written to, please make sure that the directory permissions are correct!<br>' .
        'run the command <br/>chmod a+w ' . realpath('../html/smarty/c_templates/default/'));
	}
 
    if (!$rv3) {
        $OUT .= ShowHelp('Error: urdd/pid could not be written to, please make sure that the directory permissions are correct!<br>' .
        'run the command <br/>chmod a+w ' . realpath('../urdd/pid/'));
	}

	if (!$rv1 || !$rv2 || !$rv3 || !$rv2a) {
		$OUT .= ShowHelp('The installer will not work until this has been fixed. You can refresh to check again.');
		$OUT .= '<tr><td><a href="install.php" class="noborder" title="Refresh">' . $refreshpic . '</a></td></tr>';
	} else {
		$OUT .= '<tr><td colspan="2"><br/><a onclick="LoadPage(1);" class="buttonlike">Start Installation Wizard</a></td></tr>';
	}

} else {

    if (!is_numeric($page)) {
        die('What are you trying to pull?');
    }

	// Catch exceptions (installation errors) by stopping
	try {
		require 'install.' . $page . '.inc.php';
	} catch (exception $e) {
		$OUT .= 'Oops, uncaught exception! : ' . $e->getMessage();
	}
}

$OUT .= <<<LAST
</table>
</form>
</div>

</body>
</html>
LAST;

echo $OUT;

die(); // Nothing should come after here anyways.

