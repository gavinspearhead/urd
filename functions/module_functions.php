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
 * $LastChangedDate: 2011-04-05 20:00:36 +0200 (Tue, 05 Apr 2011) $
 * $Rev: 2113 $
 * $Author: gavinspearhead $
 * $Id: functions.php 2113 2011-04-05 18:00:36Z gavinspearhead $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class module
{
    public $name;
    public $optional;
    public $modules;
    public function __construct($name_, $optional_, $modules_)
    {
        assert(is_bool($optional_));
        $this->name = $name_;
        $this->optional = $optional_;
        $this->modules = $modules_;
    }
}

class urd_modules
{
    /* Modules defined in URD */
    const URD_CLASS_GENERIC =      1;
    const URD_CLASS_GROUPS =       2;
    const URD_CLASS_USENZB =       4;
    const URD_CLASS_MAKENZB =      8;
    const URD_CLASS_RSS =         16;
    const URD_CLASS_SYNC =        32;
    const URD_CLASS_DOWNLOAD =    64;
    const URD_CLASS_VIEWFILES =  128;
    const URD_CLASS_POST =       256;
    const URD_CLASS_SPOTS =      512;
    const URD_CLASS_ALL =       1023;

    private static $mod_fns = [ 
        self::URD_CLASS_GENERIC=> 'validate_generic',
        self::URD_CLASS_GROUPS => 'validate_groups',
        self::URD_CLASS_USENZB => 'validate_usenzb',
        self::URD_CLASS_MAKENZB=> 'validate_makenzb',
        self::URD_CLASS_RSS    => 'validate_rss',
        self::URD_CLASS_SYNC   => 'validate_sync',
        self::URD_CLASS_DOWNLOAD => 'validate_download',
        self::URD_CLASS_VIEWFILES => 'validate_viewfiles',
        self::URD_CLASS_POST  => 'validate_post'
    ];

    private static $urd_classes = [
        self::URD_CLASS_GENERIC,
        self::URD_CLASS_GROUPS,
        self::URD_CLASS_USENZB,
        self::URD_CLASS_MAKENZB,
        self::URD_CLASS_POST,
        self::URD_CLASS_RSS,
        self::URD_CLASS_SYNC,
        self::URD_CLASS_DOWNLOAD,
        self::URD_CLASS_VIEWFILES,
        self::URD_CLASS_SPOTS
    ];

    public static function get_urd_classes()
    {
        return self::$urd_classes;
    }

    public static function compose_urd_module_config(array $modules)
    {
        $bits = self::URD_CLASS_GENERIC;
        foreach (self::$urd_classes as $class) {
            $bits |= ((isset($modules[$class]) && $modules[$class] == 1) ? $class : 0);
        }

        return $bits;
    }

    public static function validate_modules(DatabaseConnection $db, array $modules, array &$module_msg)
    {
        $errors = FALSE;
        $msg = '';
        foreach ($modules as $module => $state) {
            if (isset(self::$mod_fns[$module]) && $state == TRUE) {
                $fn = self::$mod_fns[$module];
                $error = !($fn($db, $msg));
                if ($error) {
                    $module_msg[$module] .= ' ' . $msg;
                } else {
                    $module_msg[$module] = TRUE;
                }
                $errors = $errors || $error;
            } else {
                $module_msg[$module] = TRUE;
            }
        }

        return $errors;
    }

    public static function update_urd_modules(DatabaseConnection $db, array $modules, &$module_msg)
    {
        //global $config;
        $dummy = [];

        if (urd_modules::validate_modules($db, $modules, $module_msg)) {
            return FALSE;
        }
        // we store it as a bit string
        $module_bits = urd_modules::compose_urd_module_config($modules);
        try {
            $test_results = new test_result_list(); // dummy (for now)
            //       check_php_modules($test_results, $module_bits, $module_msg);
        } catch (exception $e) {
            return FALSE;
        }
        set_pref($db, 'modules', $module_bits, 0);

        return TRUE;
    }

    public static function get_urd_module_config($bits)
    {
        $modules = [];
        foreach (self::$urd_classes as $class) {
            if (($bits & $class) != 0) {
                $modules[$class] = TRUE;
            } else {
                $modules[$class] = FALSE;
            }
        }

        return $modules;
    }

    public static function check_module_enabled(DatabaseConnection $db, $type)
    {
        $modules = get_config($db, 'modules');
        if (!isset($modules)) {
            return TRUE;
        }

        return (($modules & $type) > 0);
    }

    public static function get_stats_enabled_modules(DatabaseConnection $db)
    {
        $types = [];
        if (self::check_module_enabled($db, self::URD_CLASS_DOWNLOAD|self::URD_CLASS_USENZB)) {
            $types[] = stat_actions::DOWNLOAD;
        }
        if (self::check_module_enabled($db, self::URD_CLASS_DOWNLOAD)) {
            $types[] = stat_actions::PREVIEW;
        }
        if (self::check_module_enabled($db, self::URD_CLASS_USENZB)) {
            $types[] = stat_actions::IMPORTNZB;
        }
        if (self::check_module_enabled($db, self::URD_CLASS_MAKENZB)) {
            $types[] = stat_actions::GETNZB;
        }
        $types[] = stat_actions::WEBVIEW;
        if (self::check_module_enabled($db, self::URD_CLASS_POST)) {
            $types[] = stat_actions::POST;
        }

        return $types;
    }
}

function check_php_modules(test_result_list &$test_results, $modules, &$module_msg)
{
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    $php_modules = [
        new module('curl',      FALSE, urd_modules::URD_CLASS_RSS | urd_modules::URD_CLASS_SYNC),
        new module('openssl',   TRUE,  urd_modules::URD_CLASS_GENERIC),
        new module('pcntl',     FALSE, urd_modules::URD_CLASS_GENERIC),
        new module('pcre',      FALSE, urd_modules::URD_CLASS_GENERIC),
        new module('posix',     FALSE, urd_modules::URD_CLASS_GENERIC),
        new module('sockets',   FALSE, urd_modules::URD_CLASS_GENERIC),
        new module('SPL',       FALSE, urd_modules::URD_CLASS_GENERIC),
#        new module('mcrypt',    FALSE, urd_modules::URD_CLASS_GENERIC),
        new module('json',      FALSE, urd_modules::URD_CLASS_GENERIC),
        new module('gmp',       FALSE, urd_modules::URD_CLASS_GENERIC),
        new module('xmlreader', TRUE,  urd_modules::URD_CLASS_GENERIC),
        new module('xmlwriter', TRUE,  urd_modules::URD_CLASS_GENERIC)
    ];

    $error = 0;
    foreach ($php_modules as $mod) {
        if (($modules & $mod->modules) > 0) {
            if (!extension_loaded($mod->name)) {
                if ($mod->optional) {
                    write_log("Optional PHP module not loaded :{$mod->name}. Not all functions may be working", LOG_WARNING);
                    $test_results->add(new test_result("Optional module {$mod->name}", FALSE, "Optional PHP module not loaded :{$mod->name}"));
                } else {
                    $module_msg[$mod->modules] = "Required PHP module not loaded :{$mod->name}.";
                    write_log("Required PHP module not loaded :{$mod->name}.", LOG_ERR);
                    $test_results->add(new test_result("Required module {$mod->name}", FALSE, "Required PHP module not loaded :{$mod->name}"));
                    $error++;
                }
            } else {
                $test_results->add(new test_result(($mod->optional? 'Optional': 'Required') . " module {$mod->name}", TRUE, ($mod->optional? 'Optional': 'Required') . " PHP module loaded :{$mod->name}"));
            }
        } else {
            // do nothing?
        }
    }
    if ($error > 0) {
        throw new exception ('Not all required modules have been loaded', ERR_CONFIG_ERROR);
    }
    check_xml_function($test_results);
}
