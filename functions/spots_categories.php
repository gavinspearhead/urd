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
if (!defined('ORIGINAL_PAGE')) {
    define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);
}

function subcat_name_cmp($a, $b)
{
    return strcmp($a['name'], $b['name']);
}

class SpotCategories
{
    public static $adult_categories = array(
            'subcat_0_d_23' => array( 0 => '0', 1 => 'd', 2 => '23'),
            'subcat_0_d_24' => array( 0 => '0', 1 => 'd', 2 => '24'),
            'subcat_0_d_25' => array( 0 => '0', 1 => 'd', 2 => '25'),
            'subcat_0_d_26' => array( 0 => '0', 1 => 'd', 2 => '26'),
            'subcat_0_d_72' => array( 0 => '0', 1 => 'd', 2 => '72'),
            'subcat_0_d_73' => array( 0 => '0', 1 => 'd', 2 => '73'),
            'subcat_0_d_74' => array( 0 => '0', 1 => 'd', 2 => '74'),
            'subcat_0_d_75' => array( 0 => '0', 1 => 'd', 2 => '75'),
            'subcat_0_d_76' => array( 0 => '0', 1 => 'd', 2 => '76'),
            'subcat_0_d_77' => array( 0 => '0', 1 => 'd', 2 => '77'),
            'subcat_0_d_78' => array( 0 => '0', 1 => 'd', 2 => '78'),
            'subcat_0_d_79' => array( 0 => '0', 1 => 'd', 2 => '79'),
            'subcat_0_d_80' => array( 0 => '0', 1 => 'd', 2 => '80'),
            'subcat_0_d_81' => array( 0 => '0', 1 => 'd', 2 => '81'),
            'subcat_0_d_82' => array( 0 => '0', 1 => 'd', 2 => '82'),
            'subcat_0_d_83' => array( 0 => '0', 1 => 'd', 2 => '83'),
            'subcat_0_d_84' => array( 0 => '0', 1 => 'd', 2 => '84'),
            'subcat_0_d_85' => array( 0 => '0', 1 => 'd', 2 => '85'),
            'subcat_0_d_86' => array( 0 => '0', 1 => 'd', 2 => '86'),
            'subcat_0_d_87' => array( 0 => '0', 1 => 'd', 2 => '87'),
            'subcat_0_d_88' => array( 0 => '0', 1 => 'd', 2 => '88'),
            'subcat_0_d_89' => array( 0 => '0', 1 => 'd', 2 => '89'),
            'subcat_0_z_3'  => array( 0 => '0', 1 => 'z', 2 => '3')
        );

    public static $_head_categories = array(
        0 => 'spots_image',
        1 => 'spots_sound',
        2 => 'spots_game',
        3 => 'spots_application'
    );

    public static $_headcat_subcat_mapping = array(
        0 => 'd',
        1 => 'd',
        2 => 'c',
        3 => 'b'
    );

    public static $_subcat_descriptions = array(
        0 => array(
            'a' => 'spots_format',
            'b' => 'spots_source',
            'c' => 'spots_language',
            'd' => 'spots_genre',
            'z' => 'spots_type'
            ),
        1 => array(
            'a' => 'spots_format',
            'b' => 'spots_source',
            'c' => 'spots_bitrate',
            'd' => 'spots_genre',
            'z' => 'spots_type'
            ),
        2 => array(
            'a' => 'spots_platform',
            'b' => 'spots_format',
            'c' => 'spots_genre',
            'z' => 'spots_type'
            ),
        3 => array(
            'a' => 'spots_platform',
            'b' => 'spots_genre',
            'z' => 'spots_type'
         )
   );

    public static $_shortcat = array(
            0 => array(
                0 => 'spots_DivX',
                1 => 'spots_WMV',
                2 => 'spots_MPG',
                3 => 'spots_DVD5',
                4 => 'spots_HDOvg',
                5 => 'spots_eBook',
                6 => 'spots_Blu-ray',
                7 => 'spots_HD-DVD',
                8 => 'spots_WMVHD',
                9 => 'spots_x264HD',
                10 => 'spots_DVD9'),
            1 => array(
                0 => 'spots_MP3',
                1 => 'spots_WMA',
                2 => 'spots_WAV',
                3 => 'spots_OGG',
                4 => 'spots_1:1',
                5 => 'spots_DTS',
                6 => 'spots_AAC',
                7 => 'spots_APE',
                8 => 'spots_FLAC'),
            2 => array(
                0 => 'spots_WIN',
                1 => 'spots_MAC',
                2 => 'spots_LNX',
                3 => 'spots_PS',
                4 => 'spots_PS2',
                5 => 'spots_PSP',
                6 => 'spots_XBX',
                7 => 'spots_360',
                8 => 'spots_GBA',
                9 => 'spots_GC',
                10 => 'spots_NDS',
                11 => 'spots_Wii',
                12 => 'spots_PS3',
                13 => 'spots_WinPh',
                14 => 'spots_iOS',
                15 => 'spots_Android',
                16 => 'spots_nintendo3ds'),
            3 => array(
                0 => 'spots_WIN',
                1 => 'spots_MAC',
                2 => 'spots_LNX',
                3 => 'spots_OS/2',
                4 => 'spots_WinPh',
                5 => 'spots_NAV',
                6 => 'spots_iOS',
                7 => 'spots_Android')
        );

    public static $_categories = array(
        0 => array(
            'a' => array(
                0 => 'spots_divx',
                1 => 'spots_wmv',
                2 => 'spots_mpg',
                3 => 'spots_dvd5',
                4 => 'spots_hdother',
                5 => 'spots_ebook',
                6 => 'spots_bluray',
                7 => 'spots_hddvd',
                8 => 'spots_wmvhd',
                9 => 'spots_x264hd',
                10 => 'spots_dvd9'),
            'b' => array(
                0 => 'spots_cam',
                1 => 'spots_svcd',
                2 => 'spots_promo',
                3 => 'spots_dvd',
                4 => 'spots_tv',
                5 => 'spots_other',
                6 => 'spots_satellite',
                7 => 'spots_r5',
                8 => 'spots_telecine',
                9 => 'spots_telesync',
                10 => 'spots_scan'),
            'c' => array(
                0 => 'spots_subs_non',
                1 => 'spots_subs_nl_ext',
                2 => 'spots_subs_nl_incl',
                3 => 'spots_subs_eng_ext',
                4 => 'spots_subs_eng_incl',
                5 => 'spots_other',
                6 => 'spots_subs_nl_opt',
                7 => 'spots_subs_eng_opt',
                8 => '',
                9 => '',
                10 => 'spots_lang_eng',
                11 => 'spots_lang_nl',
                12 => 'spots_lang_ger',
                13 => 'spots_lang_fr',
                14 => 'spots_lang_es',
                15 => 'spots_lang_asian',
                28 => ''),
            'd' => array(
                0  => 'spots_action',
                1  => 'spots_adventure',
                2  => 'spots_animation',
                3  => 'spots_cabaret',
                4  => 'spots_comedy',
                5  => 'spots_crime',
                6  => 'spots_documentary',
                7  => 'spots_drama',
                8  => 'spots_family',
                9  => 'spots_fantasy',
                10  => 'spots_filmnoir',
                11  => 'spots_tvseries',
                12  => 'spots_horror',
                13  => 'spots_music',
                14  => 'spots_musical',
                15  => 'spots_mystery',
                16  => 'spots_romance',
                17  => 'spots_scifi',
                18  => 'spots_sport',
                19  => 'spots_short',
                20  => 'spots_thriller',
                21  => 'spots_war',
                22  => 'spots_western',
                23  => 'spots_ero_hetero',
                24  => 'spots_ero_gaymen',
                25  => 'spots_ero_lesbian',
                26  => 'spots_ero_bi',
                27  => 'spots_other',
                28  => 'spots_asian',
                29  => 'spots_anime',
                30  => 'spots_cover',
                31  => 'spots_comics',
                32  => 'spots_cartoons',
                33  => 'spots_children',
                43  => 'spots_daily',
                44  => 'spots_magazine',
                31  => 'spots_comic',
                32  => 'spots_study',
                33  => 'spots_business',
                34  => 'spots_economy',
                35  => 'spots_computer',
                36  => 'spots_hobby',
                37  => 'spots_cooking',
                38  => 'spots_crafts',
                39  => 'spots_needlework',
                40  => 'spots_health',
                41  => 'spots_history',
                42  => 'spots_psychology',
                45  => 'spots_science',
                46  => 'spots_woman',
                47  => 'spots_religion',
                48  => 'spots_novel',
                49  => 'spots_biography',
                50  => 'spots_detective',
                51  => 'spots_animals',
                52  => 'spots_humour',
                53  => 'spots_travel',
                54  => 'spots_truestory',
                55  => 'spots_nonfiction',
                56  => 'spots_politics',
                57  => 'spots_poetry',
                58  => 'spots_fairytale',
                59  => 'spots_technical',
                60  => 'spots_art',
                72  => 'spots_bi',
                73  => 'spots_lesbo',
                74  => 'spots_homo',
                75  => 'spots_hetero',
                76  => 'spots_amateur',
                77  => 'spots_groep',
                78  => 'spots_pov',
                79  => 'spots_solo',
                80  => 'spots_teen',
                81  => 'spots_soft',
                82  => 'spots_fetish',
                83  => 'spots_mature',
                84  => 'spots_fat',
                85  => 'spots_sm',
                86  => 'spots_rough',
                87  => 'spots_black',
                88  => 'spots_hentai',
                89  => 'spots_outside'),
            'z' => array(
                0 => 'spots_film',
                1 => 'spots_series',
                2 => 'spots_book',
                3 => 'spots_erotica'),
            ),

        1 => array(
            'a' => array(
                0 => 'spots_mp3',
                1 => 'spots_wma',
                2 => 'spots_wav',
                3 => 'spots_ogg',
                4 => 'spots_eac',
                5 => 'spots_dts',
                6 => 'spots_aac',
                7 => 'spots_ape',
                8 => 'spots_flac'),
            'b' => array(
                0 => 'spots_cd',
                1 => 'spots_radio',
                2 => 'spots_compilation',
                3 => 'spots_dvd',
                4 => 'spots_other',
                5 => 'spots_vinyl',
                6 => 'spots_stream'),
            'c' => array(
                0 => 'spots_variable',
                1 => 'spots_lt96kbit',
                2 => 'spots_96kbit',
                3 => 'spots_128kbit',
                4 => 'spots_160kbit',
                5 => 'spots_192kbit',
                6 => 'spots_256kbit',
                7 => 'spots_320kbit',
                8 => 'spots_lossless',
                9 => 'spots_other'),
            'd' => array(
                0 => 'spots_blues',
                1 => 'spots_compilation',
                2 => 'spots_cabaret',
                3 => 'spots_dance',
                4 => 'spots_various',
                5 => 'spots_hardcore',
                6 => 'spots_international',
                7 => 'spots_jazz',
                8 => 'spots_children',
                9 => 'spots_classical',
                10 => 'spots_smallarts',
                11 => 'spots_netherlands',
                12 => 'spots_newage',
                13 => 'spots_pop',
                14 => 'spots_soul',
                15 => 'spots_hiphop',
                16 => 'spots_reggae',
                17 => 'spots_religious',
                18 => 'spots_rock',
                19 => 'spots_soundtracks',
                20 => 'spots_other',
                21 => 'spots_hardstyle',
                22 => 'spots_asian',
                23 => 'spots_disco',
                24 => 'spots_oldschool',
                25 => 'spots_metal',
                26 => 'spots_country',
                27 => 'spots_dubstep',
                28 => 'spots_nederhop',
                29 => 'spots_dnb',
                30 => 'spots_electro',
                31 => 'spots_folk',
                32 => 'spots_soul',
                33 => 'spots_trance',
                34 => 'spots_balkan',
                35 => 'spots_techno',
                36 => 'spots_ambient',
                37 => 'spots_latin',
                38 => 'spots_live'),
        'z' => array(
                0 => 'spots_album',
                1 => 'spots_liveset',
                2 => 'spots_podcast',
                3 => 'spots_audiobook'),
        ),

        2 => array(
            'a' => array(
                0 => 'spots_windows',
                1 => 'spots_mac',
                2 => 'spots_linux',
                3 => 'spots_playstation',
                4 => 'spots_playstation2',
                5 => 'spots_psp',
                6 => 'spots_xbox',
                7 => 'spots_xbox360',
                8 => 'spots_gameboy',
                9 => 'spots_gamecube',
                10 => 'spots_nintendods',
                11 => 'spots_nintendowii',
                12 => 'spots_playstation3',
                13 => 'spots_windowsphone',
                14 => 'spots_ios',
                15 => 'spots_android',
                16 => 'spots_nintendo3ds'),
            'b' => array(
                0 => 'spots_iso',
                1 => 'spots_rip',
                2 => 'spots_retail',
                3 => 'spots_addon',
                4 => '',
                5 => 'spots_patch',
                6 => 'spots_crack'),
            'c' => array(
                0 => 'spots_action',
                1 => 'spots_adventure',
                2 => 'spots_strategy',
                3 => 'spots_roleplay',
                4 => 'spots_simulation',
                5 => 'spots_race',
                6 => 'spots_flying',
                7 => 'spots_shooter',
                8 => 'spots_platform',
                9 => 'spots_sport',
                10 => 'spots_children',
                11 => 'spots_puzzle',
                12 => 'spots_other',
                13 => 'spots_boardgame',
                14 => 'spots_cards',
                15 => 'spots_education',
                16 => 'spots_music',
                17 => 'spots_family'),
            'z' => array(
                0 =>  'spots_all'),
            ),
        3 => array(
            'a' => array(
                0 => 'spots_windows',
                1 => 'spots_mac',
                2 => 'spots_linux',
                3 => 'spots_os2',
                4 => 'spots_windowsphone',
                5 => 'spots_navigation',
                6 => 'spots_ios',
                7 => 'spots_android'),
            'b' => array(
                0 => 'spots_audioedit',
                1 => 'spots_videoedit',
                2 => 'spots_graphics',
                3 => 'spots_cdtools',
                4 => 'spots_mediaplayers',
                5 => 'spots_rippers',
                6 => 'spots_plugins',
                7 => 'spots_database',
                8 => 'spots_email', 
                9 => 'spots_photo',
                10 => 'spots_screensavers',
                11 => 'spots_skins',
                12 => 'spots_drivers',
                13 => 'spots_browsers',
                14 => 'spots_downloaders',
                15 => 'spots_filesharing',
                16 => 'spots_usenet',
                17 => 'spots_rss',
                18 => 'spots_ftp',
                19 => 'spots_firewalls',
                20 => 'spots_antivirus',
                21 => 'spots_antispyware',
                22 => 'spots_optimisation',
                23 => 'spots_security',
                24 => 'spots_system',
                25 => 'spots_other',
                26 => 'spots_educational',
                27 => 'spots_office',
                28 => 'spots_internet',
                29 => 'spots_communication',
                30 => 'spots_development',
                31 => 'spots_spotnet'),
            'z' => array(
                0 =>  'spots_all'),
        )
    );

    public static function Cat2Desc($hcat, $cat)
    {
        $catList = explode('|', $cat);
        $cat = $catList[0];

        if (empty($cat[0])) {
            return '';
        }
        $type = $cat[0];
        $nr = substr($cat, 1);

        if (isset(self::$_categories[$hcat][$type][$nr])) {
            return self::$_categories[$hcat][$type][$nr];
        }

        return '-';
    }

    public static function Cat2ShortDesc($hcat, $cat)
    {
        $catList = explode('|', $cat);
        $cat = $catList[0];

        if (empty($cat[0])) {
            return '';
        }

        $nr = substr($cat, 1);

        if (isset(self::$_shortcat[$hcat][$nr])) {
            return self::$_shortcat[$hcat][$nr];
        }

        return '-';
    }
    public static function Cat2ShortDescs($hcat, $icat)
    {
        $cat_list = explode('|', $icat);
        $cats = array();
        foreach ($cat_list as $cat) {
            if ($cat == '') {
                continue;
            }
            $c = $cat[0];
            $nr = substr($cat, 1);
            if (isset(self::$_categories[$hcat][$c][$nr])) {
                $subcat = self::$_subcat_descriptions[$hcat][$c];
                $cats[$subcat][] = array(self::$_categories[$hcat][$c][$nr], $hcat, $c, $nr);
            }
        }

        return $cats;

    }
    private static function is_adult_category($hcat, $scat, $elem)
    {
        foreach (self::$adult_categories as $c) {
            if ($c[0] == $hcat && $c[1] == $scat && ($c[2] == $elem)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public static function get_subcats_ids($hcat, $sc)
    {
        $s = array();
        foreach (self::$_categories[$hcat][$sc] as $k=>$v) {
            $s [ $sc . $k] = 0;
        }

        return $s;
    }

    public static function get_subcats($hcat, $adult)
    {
        $cats = array();
        if (!isset(self::$_subcat_descriptions[$hcat])) {
            return $cats;
        }
        foreach (self::$_categories[$hcat] as $k => $c) {
            $sc = self::$_subcat_descriptions[$hcat][$k];
            $c = array_map('to_ln', $c);
            if (!$adult) {
                foreach ($c as $scidx => $tmp) {
                    if (self::is_adult_category($hcat, $k, $scidx)) {
                        unset($c[$scidx]);
                    }
                }
            }
            asort($c);
            $cats[$k] = array(
                    'subcats' => $c,
                    'name' => to_ln($sc),
                    'counter' => 0
                    );
        }
        uasort($cats, 'subcat_name_cmp');

        return $cats;
    }
    public static function get_allsubcats($adult)
    {
        $hcats = array();
        foreach (self::$_head_categories as $k => $c) {
            $hcats[$k] = array(
                    'name' => to_ln($c),
                    'subcats' => self::get_subcats($k, $adult)
                    );
        }

        uasort($hcats, 'spot_name_cmp');
        return $hcats;
    }

    public static function SubcatToFilter($hcat, $scat)
    {
        $subcat = explode('|',$scat);

        return 'cat' . $hcat . '_' . $subcat[0];
    }

    public static function SubcatDescription($hcat, $ch)
    {
        if ((isset(self::$_subcat_descriptions[$hcat])) && (isset(self::$_subcat_descriptions[$hcat][$ch]))) {
            return self::$_subcat_descriptions[$hcat][$ch];
        }

        return '-';
    }

    public static function SubcatNumberFromHeadcat($hcat)
    {
        if (isset(self::$_headcat_subcat_mapping[$hcat])) {
            return self::$_headcat_subcat_mapping[$hcat];
        }

        return '-';
    }

    public static function HeadCat2Desc($cat)
    {
        if (isset(self::$_head_categories[$cat])) {
            return self::$_head_categories[$cat];
        }

        return '-';
    }
    public static function get_categories()
    {
        return self::$_head_categories;
    }
    public static function get_category_ids()
    {
        return array_keys(self::$_head_categories);
    }
    public static function createSubcatZ($hcat, $subcats)
    {
# z-categorieen gelden tot nu toe enkel voor films en muziek
        if (($hcat != 0) && ($hcat != 1)) {
            return '';
        } 

        $genreSubcatList = explode('|', $subcats);
        $subcatz = '';

        foreach ($genreSubcatList as $subCatVal) {
            if ($subCatVal == '') {
                continue;
            } 

            if ($hcat == 0) {
# 'Erotiek'
                if (stripos('d23|d24|d25|d26|d72|d73|d74|d75|d76|d77|d78|d79|d80|d81|d82|d83|d84|d85|d86|d87|d88|d89|', ($subCatVal . '|')) !== FALSE) {
                    $subcatz = 'z3|';
                } elseif (stripos('b4|d11|', ($subCatVal . '|')) !== FALSE) {
# Series
                    $subcatz = 'z1|';
                } elseif (stripos('a5|', ($subCatVal . '|')) !== FALSE) {
# Boeken
                    $subcatz = 'z2|';
                } elseif (empty($subcatz)) {
# default, film
                    $subcatz = 'z0|';
                } 
            } elseif ($hcat == 1) {
                $subcatz = 'z0|';
                break;
            } 
        } 

        return $subcatz;
    } 
}
