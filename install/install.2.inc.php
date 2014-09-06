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
 * $LastChangedDate: 2014-05-29 10:10:40 +0200 (do, 29 mei 2014) $
 * $Rev: 3065 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: install.2.inc.php 3065 2014-05-29 08:10:40Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) { 
    die('This file cannot be accessed directly.');
}

$OUT .= '<tr><td colspan="2" class="install1">Required tools</td></tr>' . "\n";

$OUT .= '<tr><td class="install2">Yydecode installed</td>';
$OUT .= GenRetVal(CheckYydecode(), $rv_yy);
if (!$rv_yy) {
    $OUT .= ShowHelp("You MUST install yydecode. (You can try 'apt-get install yydecode' as root, or download the source <a href=\"http://yydecode.sourceforge.net/\">here</a>)");
}

$OUT .= '<tr><td class="install2">Par2 installed</td>';
$OUT .= GenRetVal(CheckPar2(), $rv_par2);
if (!$rv_par2) {
    $OUT .= ShowHelp("You MUST install par2. (You can try 'apt-get install par2' as root)");
}

$OUT .= '<tr><td class="install2">Unrar installed</td>';
$OUT .= GenRetVal(CheckUnRar(), $rv_unrar);
if (!$rv_unrar) {
    $OUT .= ShowHelp("You MUST install unrar. (You can try 'apt-get install unrar' (or rar) as root)");
}

$OUT .= '<tr><td class="install2">File installed</td>';
$OUT .= GenRetVal(CheckFile(), $rv_file);
if (!$rv_file) {
    $OUT .= ShowHelp("You MUST install file. (You can try 'apt-get install file' as root)");
}

$OUT .= '<tr><td colspan="2" class="install1"><br/>Optional tools (can also be installed later)</td></tr>' . "\n";

$OUT .= '<tr><td class="install2">Trickle installed</td>';
$OUT .= GenRetVal(CheckTrickle(), $rv_tr);
if (!$rv_tr) {
    $OUT .= ShowHelp("You COULD install trickle. (You can try 'apt-get install trickle' as root)");
}

$OUT .= '<tr><td class="install2">Cksfv installed</td>';
$OUT .= GenRetVal(CheckCksfv(), $rv_sfv);
if (!$rv_sfv) {
    $OUT .= ShowHelp("You COULD install cksfv. (You can try 'apt-get install cksfv' as root)");
}

$OUT .= '<tr><td class="install2">Tar installed</td>';
$OUT .= GenRetVal(CheckTar(), $rv_tar);
if (!$rv_tar) {
    $OUT .= ShowHelp("You COULD install tar. (You can try 'apt-get install tar' as root)");
}

$OUT .= '<tr><td class="install2">Gzip installed</td>';
$OUT .= GenRetVal(CheckGzip(), $rv_Gzip);
if (!$rv_Gzip) {
    $OUT .= ShowHelp("You COULD install Gzip. (You can try 'apt-get install gzip' as root)");
}

$OUT .= '<tr><td class="install2">7zip installed</td>';
$OUT .= GenRetVal(Check7zip(), $rv_7zip);
if (!$rv_7zip) { 
    $OUT .= ShowHelp("You COULD install 7zip. (You can try 'apt-get install p7zip' (or p7zip-full) as root)");
}

$OUT .= '<tr><td class="install2">Unzip installed</td>';
$OUT .= GenRetVal(CheckZip(), $rv_unzip);
if (!$rv_unzip) {
    $OUT .= ShowHelp("You COULD install unzip. (You can try 'apt-get install unzip' as root)");
}

$OUT .= '<tr><td class="install2">Arj installed</td>';
$OUT .= GenRetVal(CheckArj(), $rv_arj);
if (!$rv_arj) {
    $OUT .= ShowHelp("You COULD install arj. (You can try 'apt-get install arj' as root)");
}

$OUT .= '<tr><td class="install2">Unace installed</td>';
$OUT .= GenRetVal(CheckAce(), $rv_ace);
if (!$rv_ace) {
    $OUT .= ShowHelp("You COULD install unace. (You can try 'apt-get install unace-nonfree' as root)");
}

$OUT .= '<tr><td class="install2">Yencode installed</td>';
$OUT .= GenRetVal(CheckYencode(), $rv_yencode);
if (!$rv_yencode) {
    $OUT .= ShowHelp("You COULD install Yencode. Get it <a href=\"http://yencode.sourceforge.net/\">here</a>");
}

$OUT .= '<tr><td class="install2">Rar installed</td>';
$OUT .= GenRetVal(CheckRar(), $rv_rar);
if (!$rv_rar) {
    $OUT .= ShowHelp("You COULD install rar. (You can try 'apt-get install rar' as root)");
}

$OUT .= '<tr><td class="install2">Subdownloader installed</td>';
$OUT .= GenRetVal(CheckSubDl(), $rv_subdl);
if (!$rv_subdl) {
    $OUT .= ShowHelp("You COULD install subdownloader. (You can try 'apt-get install subdownloader' as root)");
}

$OUT .= '<tr colspan="2"><td><a onclick="LoadPage(3);">'.$continuepic.'</a>';
$OUT .= '<a onclick="LoadPage(2);">'.$refreshpic.'</a></td></tr>';
