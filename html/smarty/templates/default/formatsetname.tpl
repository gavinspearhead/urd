{* Smarty *}
{*
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
 * $LastChangedDate: 2011-07-04 23:51:30 +0200 (Mon, 04 Jul 2011) $
 * $Rev: 2245 $
 * $Author: gavinspearhead $
 * $Id: formatsetname.tpl 2245 2011-07-04 21:51:30Z gavinspearhead $
 *}

{include 'include_bin_image.tpl' scope='parent'}

{$setdesc=$newname}
{capture assign=setdesc}{$setdesc|truncate:$maxstrlen:'...':true:true|escape:htmlall}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_movie:':$btmovie}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_album:':$btmusic}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_image:':$btimage}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_software:':$btsoftw}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_series:':$bttv}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_tvshow:':$bttv}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_documentary:':$btdocu}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_ebook:':$btebook}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_game:':$btgame}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_pw:':$btpw}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_copyright:':$btcopyright}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_unknown:':''}{/capture}

{$setdesc}
