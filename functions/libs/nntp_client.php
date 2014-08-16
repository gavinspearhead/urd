<?php

/*** PHP versions 5
/*  vim:ts=4:expandtab:cindent
 *
 * +-----------------------------------------------------------------------+
 * |                                                                       |
 * | W3C® SOFTWARE NOTICE AND LICENSE                                      |
 * | http://www.w3.org/Consortium/Legal/2002/copyright-software-20021231   |
 * |                                                                       |
 * | This work (and included software, documentation such as READMEs,      |
 * | or other related items) is being provided by the copyright holders    |
 * | under the following license. By obtaining, using and/or copying       |
 * | this work, you (the licensee) agree that you have read, understood,   |
 * | and will comply with the following terms and conditions.              |
 * |                                                                       |
 * | Permission to copy, modify, and distribute this software and its      |
 * | documentation, with or without modification, for any purpose and      |
 * | without fee or royalty is hereby granted, provided that you include   |
 * | the following on ALL copies of the software and documentation or      |
 * | portions thereof, including modifications:                            |
 * |                                                                       |
 * | 1. The full text of this NOTICE in a location viewable to users       |
 * |    of the redistributed or derivative work.                           |
 * |                                                                       |
 * | 2. Any pre-existing intellectual property disclaimers, notices,       |
 * |    or terms and conditions. If none exist, the W3C Software Short     |
 * |    Notice should be included (hypertext is preferred, text is         |
 * |    permitted) within the body of any redistributed or derivative      |
 * |    code.                                                              |
 * |                                                                       |
 * | 3. Notice of any changes or modifications to the files, including     |
 * |    the date changes were made. (We recommend you provide URIs to      |
 * |    the location from which the code is derived.)                      |
 * |                                                                       |
 * | THIS SOFTWARE AND DOCUMENTATION IS PROVIDED "AS IS," AND COPYRIGHT    |
 * | HOLDERS MAKE NO REPRESENTATIONS OR WARRANTIES, EXPRESS OR IMPLIED,    |
 * | INCLUDING BUT NOT LIMITED TO, WARRANTIES OF MERCHANTABILITY OR        |
 * | FITNESS FOR ANY PARTICULAR PURPOSE OR THAT THE USE OF THE SOFTWARE    |
 * | OR DOCUMENTATION WILL NOT INFRINGE ANY THIRD PARTY PATENTS,           |
 * | COPYRIGHTS, TRADEMARKS OR OTHER RIGHTS.                               |
 * |                                                                       |
 * | COPYRIGHT HOLDERS WILL NOT BE LIABLE FOR ANY DIRECT, INDIRECT,        |
 * | SPECIAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF ANY USE OF THE        |
 * | SOFTWARE OR DOCUMENTATION.                                            |
 * |                                                                       |
 * | The name and trademarks of copyright holders may NOT be used in       |
 * | advertising or publicity pertaining to the software without           |
 * | specific, written prior permission. Title to copyright in this        |
 * | software and any associated documentation will at all times           |
 * | remain with copyright holders.                                        |
 * |                                                                       |
 * +-----------------------------------------------------------------------+
 *
 * @category   Net
 * @package    NNTP
 * @original author     Heino H. Gehlsen <heino@gehlsen.dk>
 * @Updated by Styck & Spearhead
 * @copyright  2002-2005 Heino H. Gehlsen <heino@gehlsen.dk>. All Rights Reserved.
 * @license    http://www.w3.org/Consortium/Legal/2002/copyright-software-20021231 W3C® SOFTWARE NOTICE AND LICENSE
 * @version    CVS: $Id: nntp_client.php 1291 2008-07-12 13:43:11Z gavinspearhead $
 *
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

/**
 * Implementation of the client side of NNTP (Network News Transfer Protocol)
 * The NNTP_Client class is a frontend class to the Net_NNTP_Protocol_Client class.
 */
class NNTP_Client extends Base_NNTP_Client
{
    /**
     * Information summary about the currently selected group.
     *
     */
    private $_selectedGroupSummary;
    private $_overviewFormatCache;
    private $_compressed_headers;

    public function __construct()
    {
        $this->_selectedGroupSummary = NULL;
        $this->_overviewFormatCache = NULL;
        $this->_compressed_headers = FALSE;
        parent::__construct();
    }
    public function __destruct()
    {
        echo_debug_function(DEBUG_WORKER, __FUNCTION__);
        $this->disconnect();
        parent::__destruct();
    }
    /**
     * Connect to a server.
     *
     * @param string $host       (optional) The hostname og IP-address of the NNTP-server to connect to, defaults to localhost.
     * @param mixed  $encryption (optional) FALSE|'tls'|'ssl', defaults to FALSE.
     * @param int    $port       (optional) The port number to connect to, defaults to 119 or 563 dependng on $encryption.
     * @param int    $timeout    (optional)
     *
     * @return mixed <br>
     *  - (bool) TRUE when posting allowed, otherwise FALSE
     *  - (object) Pear_Error on failure
     */
    public function connect($host, $encryption = NULL, $port = NULL, $timeout = NULL)
    {
        return parent::connect($host, $encryption, $port, $timeout);
    }

    /**
     * Disconnect from server.
     *
     * @return mixed <br>
     *  - (bool)
     *  - (object)
     */
    public function disconnect()
    {
        $msg = '';
        try {
            parent::cmd_quit($msg);
        } catch (exception $e) {
            // do nothing
        }

        return $msg;
    }

    public function set_compressed_headers($on)
    {
        $this->_compressed_headers = ($on ? TRUE : FALSE);
    }

    /**
     * Authenticate.
     *
     * <b>Non-standard!</b><br>
     * This method uses non-standard commands, which is not part
     * of the original RFC977, but has been formalized in RFC2890.
     *
     * @param string $user The username
     * @param string $pass The password
     *
     * @return mixed <br>
     *  - (bool) TRUE on successful authentification, otherwise FALSE
     *  - (object)
     */
    public function authenticate($user, $pass)
    {
        // Username is a must...
        if ($user == NULL) {
            throw new exception('No username supplied', ERR_INVALID_USERNAME);
        }

        return $this->cmd_authinfo($user, $pass);
    }

    /**
     * Selects a group.
     *
     * Moves the servers 'currently selected group' pointer to the group
     * a new group, and returns summary information about it.
     *
     * <b>Non-standard!</b><br>
     * When using the second parameter,
     * This method uses non-standard commands, which is not part
     * of the original RFC977, but has been formalized in RFC2890.
     *
     * @param string $group    Name of the group to select
     * @param mixed  $articles (optional) experimental! When TRUE the article numbers is returned in 'articles'
     *
     * @return mixed <br>
     *  - (array) Summary about the selected group
     *  - (object)
     */
    public function select_group($group, $articles = FALSE)
    {
        // Select group (even if $articles is set, since many servers does not select groups when the listgroup command is run)
        $summary = $this->cmd_group($group);

        // Store group info in the object
        $this->_selectedGroupSummary = $summary;

        if ($articles !== FALSE) {
            $summary2 = $this->cmd_listgroup($group, ($articles === TRUE ? NULL : $articles));

            // Make sure the summary array is correct...
            if ($summary2['group'] == $group) {
                $summary = $summary2; // ... even if server does not include summary in status reponse.
            } else {
                $summary['articles'] = $summary2['articles'];
            }
        }

        return $summary;
    }

    /**
     * Select the previous article.
     *
     * Select the previous article in current group.
     *
     * @param int $_ret (optional) Experimental
     *
     * @return mixed <br>
     *  - (integer) Article number, if $ret=0 (default)
     *  - (string) Message-id, if $ret=1
     *  - (array) Both article number and message-id, if $ret=-1
     *  - (bool) FALSE if no prevoius article exists
     *  - (object) Pear_Error on failure
     */
    public function select_previous_article($_ret = 0)
    {
        try {
            $response = $this->cmd_last();
        } catch (exception $e) {
            return FALSE;
        }

        switch ($_ret) {
        case -1:
            return array('Number' => $response[0], 'Message-ID' => (string) $response[1]);
            break;
        case 0:
            return $response[0];
            break;
        case 1:
            return (string) $response[1];
            break;
        default:
            throw new exception ('An error occurred that shouldn\'t', ERR_INTERNAL_ERROR);
        }
    }

    /**
     * Select the next article.
     *
     * Select the next article in current group.
     *
     * @param int $_ret (optional) Experimental
     *
     * @return mixed <br>
     *  - (integer) Article number, if $ret=0 (default)
     *  - (string) Message-id, if $ret=1
     *  - (array) Both article number and message-id, if $ret=-1
     *  - (bool) FALSE if no further articles exist
     *  - (object) Pear_Error on unexpected failure
     */
    public function select_next_article($_ret = 0)
    {
        $response = $this->cmd_next();

        switch ($_ret) {
        case -1:
            return array('Number' => $response[0], 'Message-ID' => (string) $response[1]);
            break;
        case 0:
            return $response[0];
            break;
        case 1:
            return (string) $response[1];
            break;
        default:
            throw new exception ('Invalid return type selected', ERR_INVALID_RESPONSE);
        }
    }

    /**
     * Selects an article by article message-number.
     *
     * @param mixed $article The message-number (on the server) of
     *                                  the article to select as current article.
     * @param int $_ret (optional) Experimental
     *
     * @return mixed <br>
     *  - (integer) Article number
     *  - (bool) FALSE if article doesn't exists
     *  - (object) Pear_Error on failure
     */
    public function select_article($article = NULL, $_ret = 0)
    {
        $response = $this->cmd_stat($article);

        switch ($_ret) {
        case -1:
            return array('Number' => $response[0], 'Message-ID' => (string) $response[1]);
            break;
        case 0:
            return $response[0];
            break;
        case 1:
            return (string) $response[1];
            break;
        default:
            throw new exception ('Invalid return type selected', ERR_INTERNAL_ERROR);
        }
    }

    /**
     * Fetch article into transfer object.
     *
     * Select an article based on the arguments, and return the entire
     * article (raw data).
     *
     * @param mixed $article (optional) Either the message-id or the
     *                                  message-number on the server of the
     *                                  article to fetch.
     * @param bool $implode (optional) When TRUE the result array
     *                                  is imploded to a string, defaults to
     *                                  FALSE.
     *
     * @return mixed <br>
     *  - (array) Complete article (when $implode is FALSE)
     *  - (string) Complete article (when $implode is TRUE)
     *  - (object) Pear_Error on failure
     */
    public function get_article($article = NULL, $implode = FALSE)
    {
        $data = $this->cmd_article($article);
        if ($implode == TRUE) {
            $data = implode("\r\n", $data);
        }

        return $data;
    }

    /**
     * Fetch article header.
     *
     * Select an article based on the arguments, and return the article
     * header (raw data).
     *
     * @param mixed $article (optional) Either message-id or message
     *                                  number of the article to fetch.
     * @param bool $implode (optional) When TRUE the result array
     *                                  is imploded to a string, defaults to
     *                                  FALSE.
     *
     * @return mixed <br>
     *  - (bool) FALSE if article does not exist
     *  - (array) Header fields (when $implode is FALSE)
     *  - (string) Header fields (when $implode is TRUE)
     *  - (object) Pear_Error on failure
     */
    public function get_header($article = NULL, $implode = FALSE)
    {
        $data = $this->cmd_head($article);

        if ($implode === TRUE) {
            $data = implode("\r\n", $data);
        }

        return $data;
    }
    
    /**
     * Fetch article body.
     *
     * Select an article based on the arguments, and return the article
     * body (raw data).
     *
     * @param mixed $article (optional) Either the message-id or the
     *                                  message-number on the server of the
     *                                  article to fetch.
     * @param bool $implode (optional) When TRUE the result array
     *                                  is imploded to a string, defaults to
     *                                  FALSE.
     *
     * @return mixed <br>
     *  - (array) Message body (when $implode is FALSE)
     *  - (string) Message body (when $implode is TRUE)
     *  - (object) excpeption on error
     */
    public function get_body($article = NULL, $implode = FALSE)
    {
        $data = $this->cmd_body($article);

        if ($implode === TRUE) {
            $data = implode("\r\n", $data);
        }

        return $data;
    }
    /**
     * Post a raw article to a number of groups.
     *
     * @param mixed $article <br>
     *  - (string) Complete article in a ready to send format (lines terminated by LFCR etc.)
     *  - (array) First key is the article header, second key is article body - any further keys are ignored !!!
     *  - (mixed) Something 'callable' (which must return otherwise acceptable data as replacement)
     *
     * @return mixed <br>
     *  - (string) Server response
     *  - (object) Pear_Error on failure
     */
    public function post($article, &$articleid)
    {
        // Only accept $article if array or string
        if (!is_array($article) && !is_string($article)) {
            throw new exception('No article found: Should not happen', ERR_INTERNAL_ERROR);
        }
        $this->cmd_post();

        return $this->cmd_post2($article, $articleid);
    }

    /**
     * Post an article to a number of groups - using same parameters as PHP's mail() function.
     *
     * Among the aditional headers you might think of adding could be:
     * "From: <author-email-address>", which should contain the e-mail address
     * of the author of the article.
     * Or "Organization: <org>" which contain the name of the organization
     * the post originates from.
     * Or "NNTP-Posting-Host: <ip-of-author>", which should contain the IP-address
     * of the author of the post, so the message can be traced back to him.
     *
     * @param string $groups     The groups to post to.
     * @param string $subject    The subject of the article.
     * @param string $body       The body of the article.
     * @param string $additional (optional) Additional header fields to send.
     *
     * @return mixed <br>
     *  - (string) Server response
     *  - (object) exception
     */
    public function mail($groups, $subject, $body, $additional = NULL)
    {
        $version = urd_version::get_version();
        $header  = "Newsgroups: $groups\r\n";
        $header .= "Subject: $subject\r\n";
        $header .= "X-poster: URDD v$version \r\n";
        if ($additional !== NULL) {
            $header .= $additional;
        }

        return $this->cmd_post(array($header, $body));
    }

    /**
     * Get the server's internal date
     *
     * <b>Non-standard!</b><br>
     * This method uses non-standard commands, which is not part
     * of the original RFC977, but has been formalized in RFC2890.
     *
     * @param int $format (optional) Determines the format of returned date:
     *                           - 0: return string
     *                           - 1: return integer/timestamp
     *                           - 2: return an array('y'=>year, 'm'=>month,'d'=>day)
     *
     * @return mixed <br>
     *  - (mixed)
     *  - (object) Pear_Error on failure
     */
    public function get_date($format = 1)
    {
        $date = $this->cmd_date();

        switch ($format) {
        case 0:
            return $date;
            break;
        case 1:
            return strtotime(substr($date, 0, 8).' '.substr($date, 8, 2).':'.substr($date, 10, 2).':'.substr($date, 12, 2));
            break;
        case 2:
            return array(
                'y' => substr($date, 0, 4),
                'm' => substr($date, 4, 2),
                'd' => substr($date, 6, 2)
            );
            break;
        default:
            throw new exception ('An error occurred that shouldn\'t', ERR_INTERNAL_ERROR);
        }
    }

    /**
     * Get new groups since a date.
     *
     * Returns a list of groups created on the server since the specified date
     * and time.
     *
     * @param mixed $time <br>
     *  - (integer) A timestamp
     *  - (string) Somthing parseable by strtotime() like '-1 week'
     * @param string $distributions (optional)
     *
     * @return mixed <br>
     *  - (array)
     *  - (object) Pear_Error on failure
     */
    public function get_new_groups($time, $distributions = NULL)
    {
        if (is_integer($time)) {
            ;// do nothing;
        } elseif (is_string($time)) {
            $time = strtotime($time);
            if ($time === FALSE) {
                throw new exception('$time could not be converted into a timestamp!', ERR_INVALID_TIMESTAMP);
            }
        } else {
            throw new exception('$time must be either a string or an integer/timestamp!', ERR_INVALID_TIMESTAMP);
        }

        return $this->cmd_newgroups($time, $distributions);
    }

    /**
     * Get new articles since a date.
     *
     * Returns a list of message-ids of new articles (since the specified date
     * and time) in the groups whose names match the wildmat
     *
     * @param mixed $time <br>
     *  - (integer) A timestamp
     *  - (string) Somthing parseable by strtotime() like '-1 week'
     * @param string $groups        (optional)
     * @param string $distributions (optional)
     *
     * @return mixed <br>
     *  - (array)
     *  - (object) exception on failure
     */
    public function get_new_articles($time, $groups = '*', $distribution = NULL)
    {
        if (is_integer($time)) {
            ;// do nothing;
        } elseif (is_string($time)) {
            $time = strtotime($time);
            if ($time === FALSE) {
                throw new exception('$time could not be converted into a timestamp!',ERR_INVALID_TIMESTAMP);
            }
        } else {
            throw new exception('$time must be either a string or an integer/timestamp!',ERR_INVALID_TIMESTAMP);
        }

        return $this->cmd_newnews($time, $groups, $distribution);
    }
    /**
     * Fetch valid groups.
     *
     * Returns a list of valid groups (that the client is permitted to select)
     * and associated information.
     *
     * <b>Usage example:</b>
     * {@example docs/examples/phpdoc/getGroups.php}
     *
     * @return mixed <br>
     *  - (array) Nested array with information about every valid group
     *  - (object) Pear_Error on failure
     */
    public function get_groups($nzb, $wildmat = NULL)
    {
        $backup = FALSE;

        // Get groups
        try {
            $groups = $this->cmd_list_active($nzb, $wildmat);
        } catch (exception $e) {
            switch ($e->getCode()) {
            case 500:
            case 501:
                $backup = TRUE;
                break;
            default:
                throw $e;
            }
        }

        if ($backup == TRUE) {
            if (!is_null($wildmat)) {
                write_log("The server does not support the 'LIST ACTIVE' command, and the 'LIST' command does not support the wildmat parameter! Trying without...", LOG_WARNING);
            }
            $groups2 = $this->cmd_list($nzb);
            $groups = $groups2;
        }

        return $groups;
    }

    /**
     * Fetch all known group descriptions.
     *
     * Fetches a list of known group descriptions - including groups which
     * the client is not permitted to select.
     *
     * <b>Non-standard!</b><br>
     * This method uses non-standard commands, which is not part
     * of the original RFC977, but has been formalized in RFC2890.
     *
     * @param mixed $wildmat (optional)
     *
     * @return mixed <br>
     *  - (array) Associated array with descriptions of known groups
     *  - (object) Pear_Error on failure
     */
    public function get_descriptions($wildmat = NULL)
    {
        if (is_array($wildmat)) {
            $wildmat = implode(',', $wildmat);
        }

        // Get group descriptions
        $descriptions = $this->cmd_list_newsgroups($wildmat);

        // TODO: add xgtitle as backup
        return $descriptions;
    }

    /**
     * Fetch an overview of article(s) in the currently selected group.
     *
     * Returns the contents of all the fields in the database for a number
     * of articles specified by either article-numnber range, a message-id,
     * or nothing (indicating currently selected article).
     *
     * The first 8 fields per article is always as follows:
     *   - 'Number' - '0' or the article number of the currently selected group.
     *   - 'Subject' - header content.
     *   - 'From' - header content.
     *   - 'Date' - header content.
     *   - 'Message-ID' - header content.
     *   - 'References' - header content.
     *   - ':bytes' - metadata item.
     *   - ':lines' - metadata item.
     *
     * The server may send more fields form it's database...
     *
     * <b>Non-standard!</b><br>
     * This method uses non-standard commands, which is not part
     * of the original RFC977, but has been formalized in RFC2890.
     *
     * <b>Usage example:</b>
     * {@example docs/examples/phpdoc/getOverview.php}
     *
     * @param mixed $range (optional)
     *                          - '<message number>'
     *                          - '<message number>-<message number>'
     *                          - '<message number>-'
     *                          - '<message-id>'
     * @param boolean $_names      (optional) experimental parameter! Use field names as array kays
     * @param boolean $_forceNames (optional) experimental parameter!
     *
     * @return mixed <br>
     *  - (array) Nested array of article overview data
     *  - (object) Pear_Error on failure
     */
    public function get_overview($range = NULL, $_names = TRUE, $_forceNames = TRUE)
    {
        assert(is_bool($_names) && is_bool($_forceNames) && (is_string($range) || is_null($range)));

        // Fetch overview from server
        if ($this->_compressed_headers === TRUE) {
            $overview = $this->cmd_xzver($range);
        } else {
            $overview = $this->cmd_xover($range);
        }
        // Use field names from overview format as keys?
        if ($_names) {
            // Already cached?
            if (is_null($this->_overviewFormatCache)) {
                // Fetch overview format
                $format = $this->get_overview_format($_forceNames, TRUE);

                // Prepend 'Number' field
                $format = array_merge(array('Number' => FALSE), $format);

                // Cache format
                $this->_overviewFormatCache = $format;
            } else {
                $format = $this->_overviewFormatCache;
            }
            // Loop through all articles
            foreach ($overview as $key => $article) {
                // Copy $format
                $f = $format;

                // Field counter
                $i = 0;

                // Loop through forld names in format
                foreach ($f as $tag => $full) {
                    $f[$tag] = $article[$i++];

                    // If prefixed by field name, remove it
                    if ($full === TRUE) {
                        $f[$tag] = ltrim( substr($f[$tag], strpos($f[$tag], ':') + 1), " \t");
                    }
                }

                // Replace article
                $overview[$key] = $f;
            }
        }

        if (is_null ($range) ||
            is_int($range) ||
            (is_string($range) && ctype_digit($range)) ||
            (is_string($range) && substr($range, 0, 1) == '<' && substr($range, -1, 1) == '>')) {
                if (count($overview) == 0) {
                    return FALSE;
                } else {
                    return reset($overview);
                }
        } else {
            return $overview;
        }
    }

    public function get_fast_overview($from, $to)
    {
        // Fetch overview from server
        $range = "$from-$to";
        if ($this->_compressed_headers) {
            $overview = $this->cmd_fast_xzver($range);
        } else {
            $overview = $this->cmd_fast_xover($range);
        }

        return $overview;
    }

    /**
     * Fetch names of fields in overview database
     *
     * Returns a description of the fields in the database for which it is consistent.
     *
     * <b>Non-standard!</b><br>
     * This method uses non-standard commands, which is not part
     * of the original RFC977, but has been formalized in RFC2890.
     *
     * @return mixed <br>
     *  - (array) Overview field names
     *  - (object) Pear_Error on failure
     */
    public function get_overview_format($_forceNames = TRUE, $_full = FALSE)
    {
        assert(is_bool($_forceNames) && is_bool($_full));
        $format = $this->cmd_list_overview_fmt();

        // Force name of first seven fields
        if ($_forceNames) {
            array_splice($format, 0, 7);
            $format = array_merge(array('Subject'    => FALSE,
                'From'       => FALSE,
                'Date'       => FALSE,
                'Message-ID' => FALSE,
                'References' => FALSE,
                ':bytes'     => FALSE,
                ':lines'     => FALSE), $format);
        }

        if ($_full === TRUE) {
            return $format;
        } else {
            return array_keys($format);
        }
    }

    /**
     * Fetch content of a header field from message(s).
     *
     * Retreives the content of specific header field from a number of messages.
     *
     * <b>Non-standard!</b><br>
     * This method uses non-standard commands, which is not part
     * of the original RFC977, but has been formalized in RFC2890.
     *
     * @param string $field The name of the header field to retreive
     * @param mixed  $range (optional)
     *                            '<message number>'
     *                            '<message number>-<message number>'
     *                            '<message number>-'
     *                            '<message-id>'
     *
     * @return mixed <br>
     *  - (array) Nested array of
     *  - (object) Pear_Error on failure
     */
    public function get_header_field($field, $range = NULL)
    {
        $fields = $this->cmd_xhdr($field, $range);
        if (is_null($range) ||
            is_int($range) ||
            (is_string($range) && ctype_digit($range))||
            (is_string($range) && substr($range, 0, 1) == '<' && substr($range, -1, 1) == '>')) {
                if (count($fields) == 0) {
                    return FALSE;
                } else {
                    return reset($fields);
                }
            } else {
                return $fields;
            }
    }
    /**
     *
     * <b>Non-standard!</b><br>
     * This method uses non-standard commands, which is not part
     * of the original RFC977, but has been formalized in RFC2890.
     *
     * @param mixed $range (optional) Experimental!
     *
     * @return mixed <br>
     *  - (array)
     *  - (object) Pear_Error on failure
     * @since 1.3.0
     */
    public function get_group_articles($range = NULL)
    {
        $summary = $this->cmd_listgroup();

        // Update summary cache if group was also 'selected'
        if ($summary['group'] !== NULL) {
            $this->_selectedGroupSummary($summary);
        }

        return $summary['articles'];
    }

    /**
     * Fetch reference header field of message(s).
     *
     * Retrieves the content of the references header field of messages via
     * either the XHDR ord the XROVER command.
     *
     * Identical to getHeaderField('References').
     *
     * <b>Non-standard!</b><br>
     * This method uses non-standard commands, which is not part
     * of the original RFC977, but has been formalized in RFC2890.
     *
     * @param mixed $range (optional)
     *                            '<message number>'
     *                            '<message number>-<message number>'
     *                            '<message number>-'
     *                            '<message-id>'
     *
     * @return mixed <br>
     *  - (array) Nested array of references
     *  - (object) Pear_Error on failure
     */
    public function get_references($range = NULL)
    {
        $backup = FALSE;
        try {
            $references = $this->cmd_xhdr('References', $range);
        } catch (exception $e) {
            switch ($e->getCode()) {
            case 500:
            case 501:
                $backup = TRUE;
                break;
            default:
                return $references;
            }
        }

        if (is_array($references) && count($references) == 0) {
            $backup = TRUE;
        }
        if ($backup === TRUE) {
            try {
                $references2 = $this->cmd_xrover($range);
                $references = $references2;
            } catch (exception $e) { // just ignore it
            }
        }

        if (is_array($references)) {
            foreach ($references as $key => $val) {
                $references[$key] = preg_split("/ +/", trim($val), -1, PREG_SPLIT_NO_EMPTY);
            }
        }

        if (is_null($range) ||
            is_int($range) ||
            (is_string($range) && ctype_digit($range))||
            (is_string($range) && substr($range, 0, 1) == '<' && substr($range, -1, 1) == '>')) {
                if (count($references) == 0) {
                    return FALSE;
                } else {
                    return reset($references);
                }
            } else {
                return $references;
            }
    }

    /**
     * Number of articles in currently selected group
     *
     * @return mixed <br>
     *  - (integer) the number of article in group
     *  - (object) Pear_Error on failure
     */
    public function count()
    {
        return $this->_selectedGroupSummary['count'];
    }

    /**
     * Maximum article number in currently selected group
     *
     * @return mixed <br>
     *  - (integer) the last article's number
     *  - (object) Pear_Error on failure
     */
    public function last()
    {
        return $this->_selectedGroupSummary['last'];
    }

    /**
     * Minimum article number in currently selected group
     *
     * @return mixed <br>
     *  - (integer) the first article's number
     *  - (object) Pear_Error on failure
     */
    public function first()
    {
        return $this->_selectedGroupSummary['first'];
    }

    /**
     * Currently selected group
     *
     * @return mixed <br>
     *  - (string) group name
     *  - (object) Pear_Error on failure
     */
    public function group()
    {
        return $this->_selectedGroupSummary['group'];
    }

    /**
     * Test whether a connection is currently open or closed.
     *
     * @return bool TRUE if connected, otherwise FALSE
     */
    public function is_connected()
    {
       return parent::_is_connected();
    }

}
