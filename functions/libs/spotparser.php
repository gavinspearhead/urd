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
    public function parseFull($xmlStr)
    {
        # Gebruik een spot template zodat we altijd de velden hebben die we willen
        $tpl_spot = array(
            'category' => '',
    //        'sabnzbdurl' => '',
            'messageid' => '',
    //      'searchurl' => '',
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
          //  'image' => '',
            'subcatlist' => array(),
            'subcata' => '',
            'subcatb' => '',
            'subcatc' => '',
            'subcatd' => '',
            'subcatz' => '',
            'imageid' => '',
            'spotter_id'=> '',
        );

        /*  Onderdruk errors bij corrupte messaegeid, bv: <evoCgYpLlLkWe97TQAmnV@spot.net> */
        $xml = @(new SimpleXMLElement($xmlStr, LIBXML_NOERROR|LIBXML_NOWARNING));
        $xml = $xml->Posting;
        $tpl_spot['category'] = (string) $xml->Category;
        $tpl_spot['spotid'] = (string) $xml->ID;
        $url = (string) $xml->Website;
        if (substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://') {
            $tpl_spot['url'] = $url;
        }
        $tpl_spot['description'] = (string) $xml->Description;
        $tpl_spot['size'] = (string) $xml->Size;
        $tpl_spot['poster'] = trim((string) $xml->Poster);
        $tpl_spot['tag'] = trim((string) $xml->Tag);
        $tpl_spot['title'] = trim((string) $xml->Title);
        $tpl_spot['key-id'] = (string) $xml->{"Key-ID"};
        # Images behandelen we op een speciale manier, in de oude spots
        # was er gewoon een URL, in de nieuwe een hoogte/lengte/messageid
        if (empty($xml->Image->Segment)) {
            $img = (string) $xml->Image;
            $tpl_spot['image'] = '';
            if (substr($img, 0, 7) == 'http://' || substr($img, 0, 8) == 'https://' ||  substr($img, 0, 10) == 'data:image') {
                $tpl_spot['image'] = $img;
            }
        } else {
            $tpl_spot['image'] = Array(
               'height' => (string) $xml->Image['Height'],
               'width' => (string) $xml->Image['Width']
            );
            foreach ($xml->xpath('/Spotnet/Posting/Image/Segment') as $seg) {
            # Make sure the messageid's are valid so we do not throw an NNTP error
                if (!$this->validMessageId((string) $seg)) {
                    $tpl_spot['image']['segment'] = array();
                    break;
                } else {
                    $tpl_spot['image']['segment'][] = (string) $seg;
                }
            }
            $tpl_spot['image'] = 'articles:' . serialize($tpl_spot['image']);
        }

        # NZB segmenten plakken we gewoon aan elkaar
        foreach ($xml->xpath('/Spotnet/Posting/NZB/Segment') as $seg) {
            $tpl_spot['nzb'][] = (string) $seg;
        }

        # fix the category in the XML array but only for new spots
        if ((int) $xml->Key != 1) {
            $tpl_spot['category'] = ((int) $tpl_spot['category']) - 1;
        }

        # Bij oude-style (?) spots wordt er al een gesplitste array van subcategorieen aangeleverd
        # die uiteraard niet compatible is met de nieuwe style van subcategorieen
        $subcatList = array();

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
            }
        }
        if (empty($tpl_spot['subcatz'])) {
            $tpl_spot['subcatz'] = SpotCategories::createSubcatZ($tpl_spot['category'], $tpl_spot['subcata'] . $tpl_spot['subcatb'] . $tpl_spot['subcatd']);
        } # if

        return $tpl_spot;
    }

    private static function fixPadding($strInput)
    {
        while ((strlen($strInput) % 4) != 0) {
            $strInput .= '=';
        }

        return $strInput;
    }

    public static function unspecialString($strInput)
    {
        $strInput = self::fixPadding($strInput);
        $strInput = str_replace('-s', '/', $strInput);
        $strInput = str_replace('-p', '+', $strInput);

        return $strInput;
    }


    private function splitBySizEx($strInput, $iSize)
    {
        $length = strlen($strInput);
        $index = 0;
        $tmp = array();

        for ($i = 0; ($i + $iSize) <= ($length + $iSize); $i += $iSize) {
            $tmp[$index] = substr($strInput, $i, $iSize);
            $index++;
        }

        return $tmp;
    }

    private function validMessageId($messageId)
    {
        $invalidChars = '<>';

        $msgIdLen = strlen($messageId);
        for ($i = 0; $i < $msgIdLen; $i++) {
            if (strpos($invalidChars, $messageId[$i]) !== FALSE) {
                return FALSE;
            }
        }

        return TRUE;
    } # validMessageId

    public function parseEncodedWord($inputStr)
    {
        $builder = '';

        if (substr($inputStr, 0, 1) !== '=') {
            return $inputStr;
        }

        if (substr($inputStr, strlen($inputStr) - 2) !== '?=') {
            return $inputStr;
        }

        $name = substr($inputStr, 2, strpos($inputStr, '?', 2) - 2);
        if (strtoupper($name) == 'UTF8') {
            $name = 'UTF-8';
        }

        $c = $inputStr[strlen($name) + 3];
        $startIndex = strlen($name) + 5;

        switch (strtolower($c)) {
        case 'q' : {
            while ($startIndex < strlen($input)) {
                $ch2 = $strInput[$startIndex];
                $chArray = null;

                switch ($ch2) {
                case '=': {
                    if ($startIndex >= (strlen($input) - 2)) {
                        $chArray = substr($strInput, $startIndex + 1, 2);
                    }
                    if ($chArray == null) {
                        //								// 'Untested code path,'
                        $builder .= $chArray . chr(10);
                        $startIndex += 3;
                    }
                    continue;
                }

                case '?': {
                    if ($strInput[$startIndex + 1] == '=') {
                        $startIndex += 2;
                    }
                    continue;
                }
                }
                $builder .= $ch2;
                $startIndex++;
            }
            break;
        }
        case 'b' : {
            $builder .= base64_decode(substr($inputStr, $startIndex, ((strlen($inputStr) - $startIndex) - 2)));
            break;
        }
        }

        return $builder;
    }

} # class Spot
