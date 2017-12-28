<?php

/**
 *  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2011-03-13 10:58:44 +0100 (Sun, 13 Mar 2011) $
 * $Rev: 2104 $
 * $Author: gavinspearhead $
 * $Id: do_functions.php 2104 2011-03-13 09:58:44Z gavinspearhead $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathspf = realpath(dirname(__FILE__));

require_once "$pathspf/spot_signing.php";

class spotparser
{
    private static function correct_elm_contents($xmlStr, $elems) {
		$cdataStart = '<![CDATA[';
        $cdata_len = strlen($cdataStart);
		$cdataEnd = ']]>';

		/* replace low-ascii characters, see messageid KNCuzvnxJJErJibUAAxQJ@spot.net */
		$xmlStr = preg_replace('/[\x00-\x1F]/', '', $xmlStr);

		/* and loop through all elements and fix them up */
		foreach($elems as $elementName) {
			// find the element entries
			$startElem = stripos($xmlStr, '<' . $elementName . '>');
			$endElem = stripos($xmlStr, '</' . $elementName . '>');

			if (($startElem === FALSE) || ($endElem === FALSE)) {
				continue;
			}

			/*
			 * Make sure this elements content is not preceeded by the
			 * required CDATA header
			 */ 
			if (substr($xmlStr, $startElem + strlen($elementName) + 2, $cdata_len) !== $cdataStart) {
				$xmlStr = str_replace(
					['<' . $elementName . '>', '</' . $elementName . '>'],
					['<' . $elementName . '>' . $cdataStart, $cdataEnd . '</' . $elementName . '>'],
					$xmlStr);
			}
		} 

		return $xmlStr;
	} # correctElmContents


    static public function parse_full($xmlStr)
    {
        # Gebruik een spot template zodat we altijd de velden hebben die we willen
        $tpl_spot = [
            'category' => '',
            'messageid' => '',
            'description' => '',
            'sub' => '',
            'size' => '',
            'poster' => '',
            'tag' => '',
            'nzb' => '',
            'title' => '',
            'key-id' => '',
            'spotid'=>'',
            'url' => '',
            'subcatlist' => [],
            'subcata' => '',
            'subcatb' => '',
            'subcatc' => '',
            'subcatd' => '',
            'subcatz' => '',
            'subcat_count' => '',
            'imageid' => '',
            'spotter_id'=> '',
        ];
        if (strpos($xmlStr, 'spot.net></Segment') !== FALSE) {
			$xmlStr = str_replace(['spot.net></Segment>', 'spot.ne</Segment>'], ['spot.net</Segment>', 'spot.net</Segment>'], $xmlStr);
		} 
		$xmlStr = self::correct_elm_contents($xmlStr, ['Title', 'Description', 'Image', 'Tag', 'Website']);
        /*  Onderdruk errors bij corrupte messaegeid, bv: <evoCgYpLlLkWe97TQAmnV@spot.net> */
        $xml = @(new SimpleXMLElement($xmlStr, LIBXML_NOERROR | LIBXML_NOWARNING));
        $xml = $xml->Posting;
        $tpl_spot['title'] = trim((string) $xml->Title);
        $tpl_spot['category'] = (string) $xml->Category;
        $tpl_spot['spotid'] = (string) $xml->ID;
        $url = (string) $xml->Website;
        if (substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://') {
            $tpl_spot['url'] = $url;
        }
        $tpl_spot['description'] = trim((string) $xml->Description);
        $tpl_spot['size'] = (string) $xml->Size;
        $tpl_spot['poster'] = trim((string) $xml->Poster);
        $tpl_spot['tag'] = trim((string) $xml->Tag);
        $tpl_spot['key-id'] = (string) $xml->{'Key-ID'};
        # Images behandelen we op een speciale manier, in de oude spots
        # was er gewoon een URL, in de nieuwe een hoogte/lengte/messageid
        if (empty($xml->Image->Segment)) {
            $img = trim((string) $xml->Image);
            $tpl_spot['image'] = '';
            if (substr($img, 0, 7) == 'http://' || substr($img, 0, 8) == 'https://' || substr($img, 0, 10) == 'data:image') {
                $tpl_spot['image'] = $img;
            }
        } else {
            $tpl_spot['image'] = array(
               'height' => (string) $xml->Image['Height'],
               'width'  => (string) $xml->Image['Width']
            );
            foreach ($xml->xpath('/Spotnet/Posting/Image/Segment') as $seg) {
            # Make sure the messageid's are valid so we do not throw an NNTP error
                if (!self::valid_message_id((string) $seg)) {
                    $tpl_spot['image']['segment'] = [];
                    break;
                } else {
                    $tpl_spot['image']['segment'][] = (string) $seg;
                }
            }
            $tpl_spot['image'] = 'articles:' . serialize($tpl_spot['image']);
        }

        # NZB segmenten plakken we gewoon aan elkaar
        $tpl_spot['nzb'] = [];
        foreach ($xml->xpath('/Spotnet/Posting/NZB/Segment') as $seg) {
            $tpl_spot['nzb'][] = (string) $seg;
        }

        # fix the category in the XML array but only for new spots
        if ((int) $xml->Key != 1) {
            $tpl_spot['category'] = ((int) $tpl_spot['category']) - 1;
        }

        # Bij oude-style (?) spots wordt er al een gesplitste array van subcategorieen aangeleverd
        # die uiteraard niet compatible is met de nieuwe style van subcategorieen
        $subcatList = [];

        # Category subelementen plakken we gewoon aan elkaar, category zelf kennen we toe
        if (!empty($xml->SubCat)) {
            foreach ($xml->xpath('/Spotnet/Posting/Category/SubCat') as $sub) {
                $subcatList[] = (string) $sub;
            }
        } else {
            foreach ($xml->xpath('/Spotnet/Posting/Category/Sub') as $sub) {
                $subcatList[] = (string) $sub;
            }
        }

        # match hoofdcat/subcat-type/subcatvalue
        foreach ($subcatList as $subcat) {
            if (preg_match('/(\d+)([abcdz])(\d+)/i', preg_quote($subcat), $tmpMatches)) {
                $subCatVal = strtolower($tmpMatches[2]) . ((int) $tmpMatches[3]);
                $tpl_spot['subcatlist'][] = $subCatVal;
                $tpl_spot['subcat' . $subCatVal[0]] .= $subCatVal . '|';
                $tpl_spot['subcat_count'] ++;
            }
        }
        if (empty($tpl_spot['subcatz'])) {
            $tpl_spot['subcatz'] = SpotCategories::createSubcatZ($tpl_spot['category'], $tpl_spot['subcata'] . $tpl_spot['subcatb'] . $tpl_spot['subcatd']);
        } 

        return $tpl_spot;
    }

    private static function fix_padding($strInput)
    {
       return $strInput . str_repeat('=', (4 - (strlen($strInput) % 4 )) % 4);
    }

    public static function unspecial_string($strInput)
    {
        $strInput = self::fix_padding($strInput);
        $strInput = str_replace(['-s', '-p'], ['/', '+'], $strInput);

        return $strInput;
    }

    static private function valid_message_id($messageId)
    {
         return (strpos($messageId, '<') === FALSE) && (strpos($messageId, '>') === FALSE);
    }
}
