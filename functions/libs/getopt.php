<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Andrei Zmievski <andrei@php.net>                             |
// +----------------------------------------------------------------------+
//
// $Id: getopt.php 1291 2008-07-12 13:43:11Z gavinspearhead $

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

/**
 * Command-line options parsing class.
 *
 * @author Andrei Zmievski <andrei@php.net>
 *
 */
class get_opt
{
    /**
     * Parses the command-line options.
     *
     * The first parameter to this function should be the list of command-line
     * arguments without the leading reference to the running program.
     *
     * The second parameter is a string of allowed short options. Each of the
     * option letters can be followed by a colon ':' to specify that the option
     * requires an argument, or a double colon '::' to specify that the option
     * takes an optional argument.
     *
     * The third argument is an optional array of allowed long options. The
     * leading '--' should not be included in the option name. Options that
     * require an argument should be followed by '=', and options that take an
     * option argument should be followed by '=='.
     *
     * The return value is an array of two elements: the list of parsed
     * options and the list of non-option command-line arguments. Each entry in
     * the list of parsed options is a pair of elements - the first one
     * specifies the option, and the second one specifies the option argument,
     * if there was one.
     *
     * Long and short options can be mixed.
     *
     * Most of the semantics of this function are based on GNU getopt_long().
     *
     * @param array  $args          an array of command-line arguments
     * @param string $short_options specifies the list of allowed short options
     * @param array  $long_options  specifies the list of allowed long options
     *
     * @return array two-element array containing the list of parsed options and
     * the non-option arguments
     *
     */

    /** * The actual implementation of the argument parsing code.  */
    public function getopt($args, $short_options, $long_options = NULL)
    {
        // in case you pass directly readPHPArgv() as the first arg
        if (empty($args)) {
            return array(array(), array());
        }
        $opts = $non_opts = array();

        settype($args, 'array');

        if ($long_options) {
            sort($long_options);
        }

        /* Preserve backwards compatibility with callers that relied on erroneous POSIX fix.  */
        reset($args);
	#        while (list($i, $arg) = each($args)) {
	foreach($args as $i => $arg) {
            /* The special element '--' means explicit end of options. Treat the rest of the arguments as non-options and end the loop. */
            if ($arg == '--') {
                $non_opts = array_merge($non_opts, array_slice($args, $i + 1));
                break;
            }

            if ($arg[0] != '-' || (strlen($arg) > 1 && $arg[1] == '-' && !$long_options)) {
                $non_opts = array_merge($non_opts, array_slice($args, $i));
                break;
            } elseif (strlen($arg) > 1 && $arg[1] == '-') {
                $error = self::parse_long_option(substr($arg, 2), $long_options, $opts, $args);
            } elseif ($arg == '-') {
                // - is stdin
                $non_opts = array_merge($non_opts, array_slice($args, $i));
                break;
            } else
                $error = self::parse_short_option(substr($arg, 1), $short_options, $opts, $args);
        }

        //var_dump( array($opts, $non_opts));
        return array($opts, $non_opts);
    }

    private function parse_short_option($arg, $short_options, &$opts, &$args)
    {
        for ($i = 0; $i < strlen($arg); $i++) {
            $opt = $arg[$i];
            $opt_arg = NULL;

            /* Try to find the short option in the specifier string. */
            if (($spec = strstr($short_options, $opt)) === FALSE || $arg[$i] == ':')
                throw new exception ("unrecognized option -- $opt");

            if (strlen($spec) > 1 && $spec[1] == ':') {
                if (strlen($spec) > 2 && $spec[2] == ':') {
                    if ($i + 1 < strlen($arg)) {
                        /* Option takes an optional argument. Use the remainder of the arg string if there is anything left. */
                        $opts[] = array($opt, substr($arg, $i + 1));
                        break;
                    }
                } else {
                    /* Option requires an argument. Use the remainder of the arg string if there is anything left. */
                    if ($i + 1 < strlen($arg)) {
                        $opts[] = array($opt,  substr($arg, $i + 1));
                        break;
		    }
		    elseif (key($args) !== null) {
			    $opt_arg = current($args);
			    next($args); // advance the pointer
//		    elseif (list(, $opt_arg) = each($args)) {
                        /* Else use the next argument. */;
                        if (self::is_short_option($opt_arg) || self::is_long_option($opt_arg)) {
                            throw new exception("option requires an argument -- $opt");
                        }
                    } else {
                        throw new exception("option requires an argument -- $opt");
                    }
                }
            }
            $opts[] = array($opt, $opt_arg);
        }
    }

    private function is_short_option($arg)
    {
        return strlen($arg) == 2 && $arg[0] == '-' && preg_match('/[a-zA-Z]/', $arg[1]);
    }

    private function is_long_option($arg)
    {
        return strlen($arg) > 2 && $arg[0] == '-' && $arg[1] == '-' &&
            preg_match('/[a-zA-Z]+$/', substr($arg, 2));
    }

    private function parse_long_option($arg, $long_options, &$opts, &$args)
    {
        @list($opt, $opt_arg) = explode('=', $arg, 2);
        $opt_len = strlen($opt);

        for ($i = 0; $i < count($long_options); $i++) {
            $long_opt  = $long_options[$i];
            $opt_start = substr($long_opt, 0, $opt_len);
            $long_opt_name = str_replace('=', '', $long_opt);

            /* Option doesn't match. Go on to the next one. */
            if ($long_opt_name != $opt) {
                continue;
            }

            $opt_rest  = substr($long_opt, $opt_len);

            /* Check that the options uniquely matches one of the allowed options. */
            if ($i + 1 < count($long_options)) {
                $next_option_rest = substr($long_options[$i + 1], $opt_len);
            } else {
                $next_option_rest = '';
            }

            if ($opt_rest != '' && $opt[0] != '=' &&
                $i + 1 < count($long_options) &&
                $opt == substr($long_options[$i+1], 0, $opt_len) &&
                $next_option_rest != '' &&
                $next_option_rest[0] != '=') {
                    throw new exception("option --$opt is ambiguous");
            }

            if (substr($long_opt, -1) == '=') {
                if (substr($long_opt, -2) != '==') { /* Long option requires an argument.  Take the next argument if one wasn't specified. */;
                    if (!strlen($opt_arg) && !(list(, $opt_arg) = each($args))) {
                        throw new exception("option --$opt requires an argument");
                    }

                    if (self::is_short_option($opt_arg) || self::is_long_option($opt_arg)) {
                        throw new exception("option requires an argument --$opt");
                    }

                }
            } elseif ($opt_arg) {
                throw new exception("option --$opt doesn't allow an argument");
            }

            $opts[] = array('--' . $opt, $opt_arg); // why prepend the --??

            return;
        }
        throw new exception("unrecognized option --$opt");
    }
}
