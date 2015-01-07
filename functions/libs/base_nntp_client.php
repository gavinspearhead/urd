<?php

/**
/*  vim:ts=4:expandtab:cindent
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
 * @package    NNTP
 * @original author     Heino H. Gehlsen <heino@gehlsen.dk>
 * @Updated by Styck and Spearhead
 * @copyright  2002-2005 Heino H. Gehlsen <heino@gehlsen.dk>. All Rights Reserved.
 * @license    http://www.w3.org/Consortium/Legal/2002/copyright-software-20021231 W3C® SOFTWARE NOTICE AND LICENSE
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathbcl = realpath(dirname(__FILE__));

require_once("$pathbcl/responsecode.php");

/**
 * Low level NNTP Client
 *
 * Implements the client part of the NNTP standard acording to:
 *  - RFC 977,
 *  - RFC 2980,
 *  - RFC 850/1036, and
 *  - RFC 822/2822
 *
 * Each NNTP command is represented by a method: cmd*()
 *
 * WARNING: The Net_NNTP_Protocol_Client class is considered an internal class
 *          (and should therefore currently not be extended directly outside of
 *          the Net_NNTP package). Therefore its API is NOT required to be fully
 *          stable, for as long as such changes doesn't affect the public API of
 *          the Net_NNTP_Client class, which is considered stable.
 *
 * TODO:	cmdListActiveTimes()
 *      	cmdDistribPats()
 *
 * @package    Net_NNTP
 * @author     Heino H. Gehlsen <heino@gehlsen.dk>
 * @Updated by Styck & Spearhead
 * @version    package: 1.3.3 (beta)
 * @version    api: 0.8.1 (alpha)
 */
class Base_NNTP_Client
{
    const NNTP_PROTOCOL_CLIENT_DEFAULT_PORT     = 119;
    const NNTP_SSL_PROTOCOL_CLIENT_DEFAULT_PORT = 563;
    /**
     * The socket resource being used to connect to the NNTP server.
     *
     * @var resource
     */
    private $_socket;

    /**
     * Contains the last recieved status response code and text
     *
     * @var array
     */
    private $_current_status_response;


    /**  Constructor  */
    protected function __construct()
    {
        $this->_socket = NULL;
        $this->_current_status_response = NULL;
        $this->_socket = new socket();
    }

    protected function __destruct()
    {
        if ($this->_is_connected()) {
            $this->_socket->disconnect();
        }
        $this->_socket = NULL;
    }

    private function split_list_response($line)
    {
        $arr = explode(' ', ltrim($line));
        if (isset($arr[3])) {
            $group = array(
                    'group'   => $arr[0],
                    'last'    => $arr[1],
                    'first'   => $arr[2],
                    'posting' => $arr[3]
                    );

            return $group;
        } else {
            return FALSE;
        }
    }
    private function _send_command_fast($cmd)
    {
        // NNTP/RFC977 only allows command up to 512 (-2) chars.
        if (isset($cmd[510])) {
            throw new exception('Failed writing to socket! (Command too long - max 510 chars)');
        }
        // Send the command
        if ($this->_is_connected()) {
            $this->_socket->write_line($cmd);
        } else {
            throw new exception_nntp_connect('Not connected', ERR_NNTP_CONNECT_FAILED);
        }
    }

    /**
     * Send command
     *
     * Send a command to the server. A carriage return / linefeed (CRLF) sequence
     * will be appended to each command string before it is sent to the IMAP server.
     *
     * @param string $cmd The command to launch, ie: "ARTICLE 1004853"
     *
     * @return mixed (int) response code on success or (object) pear_error on failure
     */
    private function _send_command($cmd)
    {
        // NNTP/RFC977 only allows command up to 512 (-2) chars.
        if (isset($cmd[510])) {
            throw new exception('Failed writing to socket! (Command too long - max 510 chars)');
        }
        // Send the command
        if ($this->_is_connected()) {
            $this->_socket->write_line($cmd);
        } else {
            throw new exception_nntp_connect('Not connected', ERR_NNTP_CONNECT_FAILED);
        }

        return $this->_get_status_response();
    }

    /**
     * Get servers status response after a command.
     *
     * @return mixed (int) statuscode on success or (object) pear_error on failure
     */
    private function _get_status_response()
    {
        // Retrieve a line (terminated by "\r\n") from the server.
        $response = $this->_socket->read_line();

        // Trim the start of the response in case of misplased whitespace (should not be needed!!!)
        $response = ltrim($response);

        $this->_current_status_response = array(
                (int) substr($response, 0, 3),
                (string) substr($response, 4)
                );

        return $this->_current_status_response[0];
    }

    private function _get_status_response_line()
    {
        // Retrieve a line (terminated by "\r\n") from the server.
        $response = $this->_socket->read_line();

        // Trim the start of the response in case of misplased whitespace (should not be needed!!!)
        $response = ltrim($response);

        $this->_current_status_response = array(
                (int) substr($response, 0, 3),
                (string) substr($response, 4)
                );

        return $response;
    }

    protected function _get_text_response()
    {
        $data = array();
        $line = '';
        $fp = $this->_socket->get_fp();
        // Continue until connection is lost
        while (!feof($fp)) {
            // Retrieve and append up to 4096 characters from the server.
            $line .= $this->_socket->gets2();
            // Continue if the line is not terminated by CRLF
            if (!isset($line[1]) || substr_compare($line, "\r\n", -2) != 0) {
                continue;
            }

            // Remove CRLF from the end of the line
            $line = substr($line, 0, -2);
            // Check if the line terminates the textresponse
            if (!isset($line[1]) && $line == '.') {  // return all previous lines

                return $data;
            }

            // If 1st char is '.' it's doubled (NNTP/RFC977 2.4.1)
            if (isset($line[1]) && $line[0] == '.' && $line[1] == '.') {
                $line = substr($line, 1);
            }
            // Add the line to the array of lines
            $data[] = $line;

            // Reset/empty $line
            $line = '';
        }
        throw new exception('Data stream not terminated with period', NULL);
    }
    public function _getCompressedResponse()
    {
        $data = array();

        // We can have two kinds of compressed support:
        //
        // - yEnc encoding
        // - Just a gzip drop
        //
        // We try to autodetect which one this uses

        $fp = $this->_socket->get_fp();
        $line = @fread($fp, 512);

        if (substr($line, 0, 7) == '=ybegin') {
            $data = $this->_getTextResponse();
            $data = $line . "\r\n" . implode('', $data);
            $data = deyenc($data);
            $data = explode("\r\n", gzinflate($data));

            return $data;
        }

        // We cannot use blocked I/O on this one
        $streamMetadata = stream_get_meta_data($fp);
        stream_set_blocking($fp, FALSE);

        // Continue until connection is lost or we don't receive any data anymore
        $tries = 0;
        $uncompressed = '';
        $error_level = error_reporting(E_ERROR);
        while (!feof($fp)) {

            # Retrieve and append up to 64k characters from the server
            $received = fread($fp, 65536);
            if (strlen($received) == 0) {
                ++$tries;

                # Try decompression
                $uncompressed = gzuncompress($line);
                if (($uncompressed !== FALSE) || ($tries > 500)) {
                    break;
                }

                if ($tries % 50 == 0) {
                    usleep(50000);
                }
            }

            # an error occured
            if ($received === FALSE) {
                error_reporting($error_level);
                throw new exception('Read error occured in compressed data', NULL);
            }

            $line .= $received;
        }
        error_reporting($error_level);

        # and set the stream to its original blocked(?) value
        stream_set_blocking($fp, $streamMetadata['blocked']);
        $data = explode("\r\n", $uncompressed);
        $dataCount = count($data);

        # Gzipped compress includes the "." and linefeed in the compressed stream skip those.
        if ($dataCount >= 2) {
            if (($data[($dataCount - 2)] == '.') && (empty($data[($dataCount - 1)]))) {
                array_pop($data);
                array_pop($data);
            }

            $data = array_filter($data);
        }

        return $data;
    }

    /**
     * Retrieves the list of groups
     *
     * Get data until a line with only a '.' in it is read and return data.
     *
     * @return mixed (array) text response on success or (object) pear_error on failure
     */
    protected function _get_list_response($nzb = NULL)
    {
        $data = array();
        $line = '';
        $cnt = 0;
        // Continue until connection is lost
        while (!$this->_socket->eof()) {
            // Retrieve and append up to 1024 characters from the server.
            $line .= $this->_socket->gets(1024);

            // Continue if the line is not terminated by CRLF
            if (substr_compare($line, "\r\n", -2) != 0 || !isset($line[2])) {
                continue;
            }

            // Remove CRLF from the end of the line
            $line = substr($line, 0, -2);
            // Check if the line terminates the textresponse

            if ($line == '.') {
                // return all previous lines
                if ($nzb !== NULL) {
                    if ($cnt > 0) {
                        $nzb->db_update_group_list($data);
                        $data = array();
                    }

                    return TRUE;
                } else {
                    return $data;
                }
            }

            // If 1st char is '.' it's doubled (NNTP/RFC977 2.4.1)
            if (isset($line[2]) && substr_compare($line, '..', 0, 2) == 0) {
                $line = substr($line, 1);
            }
            // Add the line to the array of lines
            $tmp = $this->split_list_response($line);
            if ($tmp !== FALSE) {
                $data[] = $tmp;
                $cnt ++;
                if ($nzb !== NULL && ($cnt % 1000 == 0)) {
                    $nzb->db_update_group_list($data);
                    $data = array();
                    $cnt = 0;
                }
            }

            // Reset/empty $line
            $line = '';
        }
        throw new exception('Data stream not terminated with period', NULL);
    }

    protected function _send_article($article)
    {
        /* data should be in the format specified by RFC850 */
        if (is_string($article)) {
            $this->_socket->write($article);
            $this->_socket->write("\r\n.\r\n");
        } elseif (is_array($article)) {
            $header = reset($article);
            $body = next($article);
            // Send header (including separation line)
            $this->_socket->write($header);
            $this->_socket->write("\r\n");

            // Send body
            $this->_socket->write($body);
            $this->_socket->write("\r\n.\r\n");
        } else {
            throw new exception('Wrong article format');
        }

        return TRUE;
    }

    /** * * @return string status text */
    private function _current_status_response()
    {
        return $this->_current_status_response[1];
    }

    /**
     * @param int    $code Status code number
     * @param string $text Status text
     *
     * @return mixed
     */
    protected function _handle_unexpected_response($code = NULL, $text = NULL)
    {
        if ($code === NULL) {
            $code = $this->_current_status_response[0];
        }
        if ($text === NULL) {
            $text = $this->_current_status_response();
        }

        switch ($code) {
            case NNTP_PROTOCOL_RESPONSECODE_DISCONNECTING_FORCED:
            case NNTP_PROTOCOL_RESPONSECODE_TIMEOUT:
                throw new exception ("Connection terminated by server [$text]", $code);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_AUTHENTICATION_REQUIRED:
                throw new exception ('Access denied to nntp server. Login in first', $code);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // 502, 'access restriction or permission denied' / service permanently unavailable
                throw new exception ("Command not permitted / Access restriction / Permission denied [$text]", $code);
                break;
            default:
                throw new exception("Unexpected response ($code): [$text]", $code);
                break;
        }
    }

    /* Session administration commands */

    /**
     * Connect to a NNTP server
     *
     * @param string $host       (optional) The address of the NNTP-server to connect to, defaults to 'localhost'.
     * @param mixed  $encryption (optional)
     * @param int    $port       (optional) The port number to connect to, defaults to 119.
     * @param int    $timeout    (optional)
     *
     * @return mixed (bool) on success (true when posting allowed, otherwise false)
     *
     */
    protected function connect($host, $encryption = NULL, $port = NULL, $timeout = socket::DEFAULT_SOCKET_TIMEOUT)
    {
        assert(is_numeric($timeout) || is_null($timeout));
        if ($this->_is_connected()) {
            throw new exception('Already connected, disconnect first!');
        }
        if ($host == '') {
            throw new exception('Hostname required');
        }

        // Choose transport based on encryption, and if no port is given, use default for that encryption
        switch ($encryption) {
            case NULL:
            case FALSE:
            case 'tcp':
            case 'off':
                $transport = 'tcp';
                $port = is_null($port) ? self::NNTP_PROTOCOL_CLIENT_DEFAULT_PORT : $port;
                break;
            case 'ssl':
            case 'tls':
                $transport = $encryption;
                $port = is_null($port) ? self::NNTP_SSL_PROTOCOL_CLIENT_DEFAULT_PORT : $port;
                break;
            default:
                throw new exception('$encryption parameter must be either tcp, tls or ssl.', E_USER_ERROR);
        }
        assert(is_numeric($port));
        // Open Connection
        $this->_socket->connect($transport . '://' . $host, $port, FALSE, $timeout);
        // Retrieve the server's initial response.
        $response =  $this->_get_status_response();
        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_READY_POSTING_ALLOWED: // 200, Posting allowed

                return TRUE;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_READY_POSTING_PROHIBITED: // 201, Posting NOT allowed

                return FALSE;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_DISCONNECTING_FORCED:
                throw new exception("Server refused connection ({$this->_current_status_response()}) ", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // 502, 'access restriction or permission denied' / service permanently unavailable
                throw new exception("Server refused connection ({$this->_current_status_response()}) ", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Returns servers capabilities
     *
     * @return mixed (array) list of capabilities on success or (object) pear_error on failure
     */
    protected function cmd_capabilities()
    {
        // tell the newsserver we want an article
        $response = $this->_send_command('CAPABILITIES');

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_CAPABILITIES_FOLLOW: // 101, Draft: 'Capability list follows'

                return $this->_get_text_response();
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * @return mixed (bool) true when posting allowed, false when postind disallowed or (object) pear_error on failure
     */
    protected function cmd_mode_reader()
    {
        // tell the newsserver we want an article
        $response = $this->_send_command('MODE READER');

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_READY_POSTING_ALLOWED: // 200, RFC2980: 'Hello, you can post'

                return TRUE;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_READY_POSTING_PROHIBITED: // 201, RFC2980: 'Hello, you can't post'

                return FALSE;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // 502, 'access restriction or permission denied' / service permanently unavailable
                throw new exception("Connection being closed, since service so permanently unavailable ({$this->_current_status_response()}) ", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Disconnect from the NNTP server
     *
     * @return mixed (bool) true on success or (object) pear_error on failure
     */
    protected function cmd_quit(&$msg)
    {
        // Tell the server to close the connection
        $response = $this->_send_command('QUIT');

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_DISCONNECTING_REQUESTED: // RFC977: 'closing connection - goodbye!'
                // If socket is still open, close it.
                if ($this->_is_connected()) {
                    $this->_socket->disconnect();
                    $msg = $this->_current_status_response[1];
                }
                break;
            default:
                $this->_handle_unexpected_response($response);
        }
        $this->_socket = NULL;
    }
    /* Article posting and retrieval */

    /* Group and article selection */
    /*
     * Selects a news group (issue a GROUP command to the server)
     *
     * @param string $newsgroup The newsgroup name
     *
     * @return mixed (array) groupinfo on success or (object) pear_error on failure
     */
    protected function cmd_group($newsgroup)
    {
        assert(is_string($newsgroup) && $newsgroup != '');
        $response = $this->_send_command('GROUP ' . $newsgroup);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_GROUP_SELECTED: // 211, RFC977: 'n f l s group selected'
                $response_arr = explode(' ', ltrim($this->_current_status_response()));
                if (isset($response_arr[0])) {
                    return array(
                            'group' => $response_arr[3],
                            'first' => $response_arr[1],
                            'last'  => $response_arr[2],
                            'count' => $response_arr[0]);
                } else {
                    throw new exception('Invalid response' , $response);
                }

                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_GROUP: // 411, RFC977: 'no such news group'
                throw new exception("No such news group ({$this->_current_status_response()})" , $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     *
     * @param optional string $newsgroup
     * @param optional mixed  $range
     *
     * @return optional mixed (array) on success or exception on failure
     */
    protected function cmd_listgroup($newsgroup = NULL, $range = NULL)
    {
        $command = 'LISTGROUP';
        if (!is_null($newsgroup)) {
            $command .= ' ' . $newsgroup;
            if (!is_null($range)) {
                $command .= ' ' . $range;
            }
        }

        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_GROUP_SELECTED: // 211, RFC2980: 'list of article numbers follow'
                $articles = $this->_get_text_response();
                $response_arr = explode(' ', ltrim($this->_current_status_response()), 4);

                // If server does not return group summary in status response, return null'ed array
                if (!is_numeric($response_arr[0]) || !is_numeric($response_arr[1]) || !is_numeric($response_arr[2]) || is_empty($response_arr[3])) {
                    return array(
                            'group'    => NULL,
                            'first'    => NULL,
                            'last'     => NULL,
                            'count'    => NULL,
                            'articles' => $articles);
                }

                return array(
                        'group'    => $response_arr[3],
                        'first'    => $response_arr[1],
                        'last'     => $response_arr[2],
                        'count'    => $response_arr[0],
                        'articles' => $articles);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC2980: 'Not currently in newsgroup'
                throw new exception("Not currently in newsgroup ({$this->_current_status_response()}) ", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // RFC2980: 'no permission'
                throw new exception("No permission ({$this->_current_status_response()}) ", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * @return mixed (array) or (string) or (int) or (object) pear_error on failure
     */
    protected function cmd_last()
    {
        $response = $this->_send_command('LAST');

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_ARTICLE_SELECTED: // 223, RFC977: 'n a article retrieved - request text separately (n = article number, a = unique article id)'
                $response_arr = explode(' ', ltrim($this->_current_status_response()));

                return array((int) $response_arr[0], (string) $response_arr[1]);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC977: 'no newsgroup selected'
                throw new exception("No newsgroup has been selected ({$this->_current_status_response()}) ", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC977: 'no current article has been selected'
                throw new exception("No current article has been selected ({$this->_current_status_response()}) ", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_PREVIOUS_ARTICLE: // 422, RFC977: 'no previous article in this group'
                throw new exception("No previous article in this group ({$this->_current_status_response()}) ", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * @return mixed (array) or (string) or (int) or (object) pear_error on failure
     */
    protected function cmd_next()
    {
        $response = $this->_send_command('NEXT');

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_ARTICLE_SELECTED: // 223, RFC977: 'n a article retrieved - request text separately (n = article number, a = unique article id)'
                $response_arr = explode(' ', ltrim($this->_current_status_response()));

                return array((int) $response_arr[0], (string) $response_arr[1]);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC977: 'no newsgroup selected'
                throw new exception("No newsgroup has been selected ({$this->_current_status_response()}) ", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC977: 'no current article has been selected'
                throw new exception("No current article has been selected ({$this->_current_status_response()}) ", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_NEXT_ARTICLE: // 421, RFC977: 'no next article in this group'
                throw new exception("No next article in this group ({$this->_current_status_response()}) ", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /* Retrieval of articles and article sections */

    /**
     * Get an article from the currently open connection.
     *
     * @param mixed $article Either a message-id or a message-number of the article to fetch. If null or '', then use current article.
     *
     * @return mixed (array) article on success or (object) pear_error on failure
     */
    protected function cmd_article($article = NULL)
    {
        $command = 'ARTICLE';
        if (!is_null($article)) {
            $command .= " <$article>";
        }

        // tell the newsserver we want an article
        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_ARTICLE_FOLLOWS:  // 220, RFC977: 'n <a> article retrieved - head and body follow (n = article number, <a> = message-id)'

                return $this->_get_text_response();
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC977: 'no newsgroup has been selected'
                throw new exception("No newsgroup has been selected ({$this->_current_status_response()}) ", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC977: 'no current article has been selected'
                throw new exception("No current article has been selected ({$this->_current_status_response()}) ", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_NUMBER: // 423, RFC977: 'no such article number in this group'
                throw new exception("No such article number in this group ({$this->_current_status_response()}) ", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_ID: // 430, RFC977: 'no such article found'
                throw new exception("No such article found ({$this->_current_status_response()}) ", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Get the headers of an article from the currently open connection.
     *
     * @param mixed $article Either a message-id or a message-number of the article to fetch the headers from. If null or '', then use current article.
     *
     * @return mixed (array) headers on success or (object) pear_error on failure
     */
    protected function cmd_head($article = NULL)
    {
        $command = 'HEAD';
        if (is_array($article)) {
            return $this->cmd_head_multi($article);
        }
        if (!is_null($article)) {
            $command .= " <$article>";
        }
        // tell the newsserver we want the header of an article
        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_HEAD_FOLLOWS:     // 221, RFC977: 'n <a> article retrieved - head follows'

                return $this->_get_text_response();
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC977: 'no newsgroup has been selected'
                throw new exception("No newsgroup has been selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC977: 'no current article has been selected'
                throw new exception("No current article has been selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_NUMBER: // 423, RFC977: 'no such article number in this group'
                throw new exception("No such article number in this group ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_ID: // 430, RFC977: 'no such article found'
                throw new exception("No such article found ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }
    private function cmd_head_multi(array $articles)
    {
        $command = 'HEAD';
       
        $resps = array();
        foreach ($articles as $article) {
            // tell the newsserver we want the header of an article
            $this->_send_command_fast($command . " <$article>");
            $resps [ $article ] = '';
        }
        $nr_of_resps = count($resps);
        for ($i = 0; $i < $nr_of_resps; $i++) {
            try { 
                $line = $this->_get_status_response_line();
                $values = explode(' ', $line);
                $response = $values[0];
                $message_id = substr($values[2], 1, -1); // cut of the < and >
                switch ($response) {
                    case NNTP_PROTOCOL_RESPONSECODE_HEAD_FOLLOWS:     // 221, RFC977: 'n <a> article retrieved - head follows'

                        $resp  = $this->_get_text_response();
                        $resps [ $message_id ] = $resp;
                        break;
                    case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC977: 'no newsgroup has been selected'
                        throw new exception("No newsgroup has been selected ({$this->_current_status_response()})", $response);
                        break;
                    case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC977: 'no current article has been selected'
                        throw new exception("No current article has been selected ({$this->_current_status_response()})", $response);
                        break;
                    case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_NUMBER: // 423, RFC977: 'no such article number in this group'
                        throw new exception("No such article number in this group ({$this->_current_status_response()})", $response);
                        break;
                    case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_ID: // 430, RFC977: 'no such article found'
                        throw new exception("No such article found ({$this->_current_status_response()})", $response);
                        break;
                    default:
                }       
                // return $this->_handle_unexpected_response($response);
            } catch (exception $e) {
                write_log($e->getMessage(), LOG_NOTICE);
                continue;
            }
        }
        return $resps;
    }

    /**
     * Get the body of an article from the currently open connection.
     *
     * @param mixed $article Either a message-id or a message-number of the article to fetch the body from. If null or '', then use current article.
     *
     * @return mixed (array) body on success or (object) pear_error on failure
     */
    protected function cmd_body($article = NULL)
    {
        $command = 'BODY';
        if (!is_null($article)) {
            $command .= " <$article>";
        }
        // tell the newsserver we want the body of an article
        $response = $this->_send_command($command);
        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_BODY_FOLLOWS:     // 222, RFC977: 'n <a> article retrieved - body follows'

                return $this->_get_text_response();
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC977: 'no newsgroup has been selected'
                throw new exception("No newsgroup has been selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC977: 'no current article has been selected'
                throw new exception("No current article has been selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_NUMBER: // 423, RFC977: 'no such article number in this group'
                throw new exception("No such article number in this group ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_ID: // 430, RFC977: 'no such article found'
                throw new exception("No such article found ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    
    /**
     * @param mixed $article
     *
     * @return mixed (array) or (string) or (int) or (object) pear_error on failure
     */
    protected function cmd_stat($article = NULL)
    {
        $command = 'STAT';
        if (!is_null($article)) {
            $command .= " <$article>";
        }

        // tell the newsserver we want an article
        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_ARTICLE_SELECTED: // 223, RFC977: 'n <a> article retrieved - request text separately' (actually not documented, but copied from the ARTICLE command)
                $response_arr = explode(' ', ltrim($this->_current_status_response()));

                return array((int) $response_arr[0], (string) $response_arr[1]);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC977: 'no newsgroup has been selected' (actually not documented, but copied from the ARTICLE command)
                throw new exception("No newsgroup has been selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_NUMBER: // 423, RFC977: 'no such article number in this group' (actually not documented, but copied from the ARTICLE command)
                throw new exception("No such article number in this group ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_ID: // 430, RFC977: 'no such article found' (actually not documented, but copied from the ARTICLE command)
                throw new exception("No such article found ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /* Article posting */
    /**
     * Post an article to a newsgroup.
     *
     * @return mixed (bool) true on success or (object) pear_error on failure
     */
    protected function cmd_post()
    {
        // tell the newsserver we want to post an article
        $response = $this->_send_command('POST');

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_POSTING_SEND: // 340, RFC977: 'send article to be posted. End with <CR-LF>.<CR-LF>'

                return TRUE;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_POSTING_PROHIBITED: // 440, RFC977: 'posting not allowed'
                throw new exception("Posting not allowed ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }

    }

    /**
     * Post an article to a newsgroup.
     * @param  mixed $article (string/array)
     * @return mixed (bool) true on success or (object) pear_error on failure
     */
    protected function cmd_post2($article, &$articleid)
    {
        /* should be presented in the format specified by RFC850 */

        $this->_send_article($article);

        // Retrieve server's response.
        $response = $this->_get_status_response();

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_POSTING_SUCCESS: // 240, RFC977: 'article posted ok'
                $resp = $this->_current_status_response[1];
                $rv = preg_match('/<(.*@.*)>/', $resp, $matches);
                if ($rv > 0) {
                    $articleid = $matches[1];
                } else {
                    $articleid = '';
                }

                return TRUE;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_POSTING_FAILURE: // 441, RFC977: 'posting failed'
                throw new exception("Posting failed ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * @param  string $id
     * @return mixed  (bool) true on success or (object) pear_error on failure
     */
    protected function cmd_ihave($id)
    {
        // tell the newsserver we want to post an article
        $response = $this->_send_command('IHAVE ' . $id);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_TRANSFER_SEND: // 335

                return TRUE;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_TRANSFER_UNWANTED: // 435
                throw new exception("Article not wanted ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_TRANSFER_FAILURE: // 436
                throw new exception("Transfer not possible; try again later ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * @param  mixed $article (string/array)
     * @return mixed (bool) true on success or (object) pear_error on failure
     */
    protected function cmd_ihave2($article)
    {
        /* should be presented in the format specified by RFC850 */
        $this->_send_article($article);

        // Retrieve server's response.
        $response = $this->_get_status_response();

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_TRANSFER_SUCCESS: // 235

                return TRUE;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_TRANSFER_FAILURE: // 436
                throw new exception("Transfer not possible; try again later ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_TRANSFER_REJECTED: // 437
                throw new exception("Transfer rejected; do not retry ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /* Information commands */

    /**
     * Get the date from the newsserver format of returned date
     *
     * @return mixed (string) 'YYYYMMDDhhmmss' / (int) timestamp on success or (object) pear_error on failure
     */
    protected function cmd_date()
    {
        $response = $this->_send_command('DATE');

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_SERVER_DATE: // 111, RFC2980: 'YYYYMMDDhhmmss'

                return $this->_current_status_response();
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }
    /**
     * Returns the server's help text
     *
     * @return mixed (array) help text on success or (object) pear_error on failure
     */
    protected function cmd_help()
    {
        // tell the newsserver we want an article
        $response = $this->_send_command('HELP');

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_HELP_FOLLOWS: // 100

                return $this->_get_text_response();
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Fetches a list of all newsgroups created since a specified date.
     *
     * @param int             $time          Last time you checked for groups (timestamp).
     * @param optional string $distributions (deprecaded in rfc draft)
     *
     * @return mixed (array) nested array with informations about existing newsgroups on success or (object) pear_error on failure
     */
    protected function cmd_newgroups($time, $distributions = NULL)
    {
        $date = gmdate('ymd His', $time);

        if (is_null($distributions)) {
            $command = 'NEWGROUPS ' . $date . ' GMT';
        } else {
            $command = 'NEWGROUPS ' . $date . ' GMT <' . $distributions . '>';
        }

        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_NEW_GROUPS_FOLLOW: // 231, REF977: 'list of new newsgroups follows'
                $data = $this->_get_text_response();

                $groups = array();
                foreach ($data as $line) {
                    $arr = explode(' ', ltrim($line));

                    $group = array('group'   => $arr[0],
                            'last'    => $arr[1],
                            'first'   => $arr[2],
                            'posting' => $arr[3]);

                    $groups[$group['group']] = $group;
                }

                return $groups;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     *
     * @param timestamp $time
     * @param mixed     $newsgroups   (string or array of strings)
     * @param mixed     $distribution (string or array of strings)
     *
     * @return mixed
     */
    protected function cmd_newnews($time, $newsgroups, $distribution = NULL)
    {
        $date = gmdate('ymd His', $time);

        if (is_array($newsgroups)) {
            $newsgroups = implode(',', $newsgroups);
        }

        if (is_null($distribution)) {
            $command = 'NEWNEWS ' . $newsgroups . ' ' . $date . ' GMT';
        } else {
            if (is_array())
                $distribution = implode(',', $distribution);

            $command = 'NEWNEWS ' . $newsgroups . ' ' . $date . ' GMT <' . $distribution . '>';
        }

        // the lenght of the request string may not exceed 510 chars
        if (strlen($command) > 510) {
            throw exception ('Request string exceeds 510 characters');
        }

        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_NEW_ARTICLES_FOLLOW: // 230, RFC977: 'list of new articles by message-id follows'
                $messages = array();
                foreach ($this->_get_text_response() as $line) {
                    $messages[] = $line;
                }

                return $messages;
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }
    /* The LIST commands */

    /**
     * Fetches a list of all avaible newsgroups
     *
     * @return mixed (array) nested array with informations about existing newsgroups on success or (object) pear_error on failure
     */
    protected function cmd_list($db = NULL)
    {
        $response = $this->_send_command('LIST');

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_GROUPS_FOLLOW: // 215, RFC977: 'list of newsgroups follows'
                $data = $this->_get_text_response();

                $groups = array();
                $cnt = 0;
                foreach ($data as $line) {
                    $arr = explode(' ', ltrim($line));
                    if (isset($arr[3])) {
                        $group = array(
                                'group'   => $arr[0],
                                'last'    => $arr[1],
                                'first'   => $arr[2],
                                'posting' => $arr[3]);

                        $groups[$group['group']] = $group;
                        $cnt ++;
                        if ($db !== NULL && ($cnt % 1000 == 0)) {
                            $db->db_update_group_list($groups);
                            $groups = array();
                            $cnt = 0;
                        }
                    }
                }
                if ($db !== NULL) {
                    if (count($groups) > 0) {
                        $db->db_update_group_list($groups);
                        $groups = array();
                        $cnt = 0;
                    }

                    return TRUE;
                } else {
                    return $groups;
                }
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Fetches a list of all avaible newsgroups
     *
     * @param string $wildmat
     *
     * @return mixed (array) nested array with informations about existing newsgroups on success or (object) pear_error on failure
     */

    protected function cmd_list_active($db, $wildmat = NULL)
    {
        $command = 'LIST ACTIVE';
        if (!is_null($wildmat))
            $command .= ' ' . $wildmat;

        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_GROUPS_FOLLOW: // 215, RFC977: 'list of newsgroups follows'

                return $this->_get_list_response($db);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Fetches a list of (all) avaible newsgroup descriptions.
     *
     * @param string $wildmat Wildmat of the groups, that is to be listed, defaults to null;
     *
     * @return mixed (array) nested array with description of existing newsgroups on success or (object) pear_error on failure
     */
    protected function cmd_list_newsgroups($wildmat = NULL)
    {
        $command = 'LIST NEWSGROUPS';
        if (!is_null($wildmat)) {
            $command .= ' ' . $wildmat;
        }

        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_GROUPS_FOLLOW: // 215, RFC2980: 'information follows'
                $data = $this->_get_text_response();
                $groups = array();

                foreach ($data as $line) {
                    if (preg_match("/^(\S+)\s+(.*)$/", ltrim($line), $matches)) {
                        $groups[$matches[1]] = (string) $matches[2];
                    }
                }

                return $groups;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_SUPPORTED: // RFC2980: 'program error, function not performed'
                throw new exception('Internal server error, function not performed', $response, $this->_current_status_response());
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /* Article field access commands */

    /**
     * Fetch message header from message number $first until $last
     *
     * The format of the returned array is:
     * $messages[][header_name]
     *
     * @param optional string $range articles to fetch
     *
     * @return mixed (array) nested array of message and there headers on success or (object) pear_error on failure
     */
    protected function cmd_over($range = NULL)
    {
        $command = 'OVER';
        if (!is_null($range)) {
            $command .= ' ' . $range;
        }

        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_OVERVIEW_FOLLOWS: // 224, RFC2980: 'Overview information follows'
                $data = $this->_get_text_response();

                foreach ($data as $key => $value) {
                    $data[$key] = explode("\t", ltrim($value));
                }

                return $data;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC2980: 'No news group current selected'
                throw new exception("No news group current selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC2980: 'No article(s) selected'
                throw new exception("No article(s) selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_NUMBER: // 423:, Draft27: 'No articles in that range'
                throw new exception("No articles in that range ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // RFC2980: 'no permission'
                throw new exception("No permission ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Fetch message header from message number $first until $last
     *
     * The format of the returned array is:
     * $messages[message_id][header_name]
     *
     * @param optional string $range articles to fetch
     *
     * @return mixed (array) nested array of message and there headers on success or (object) pear_error on failure
     */
    protected function cmd_xover($range = NULL)
    {
        $command = 'XOVER';
        if (!is_null($range)) {
            $command .= ' ' . $range;
        }

        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_OVERVIEW_FOLLOWS: // 224, RFC2980: 'Overview information follows'
                $data = $this->_get_text_response();

                foreach ($data as $key => $value)
                    $data[$key] = explode("\t", ltrim($value));

                return $data;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC2980: 'No news group current selected'
                throw new exception("No news group current selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC2980: 'No article(s) selected'
                throw new exception("No article(s) selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // RFC2980: 'no permission'
                throw new exception("No permission ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    // compressed Xover
    protected function cmd_xzver($range = NULL)
    {
        $command = 'XZVER';
        if (!is_null($range)) {
            $command .= ' ' . $range;
        }
        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_OVERVIEW_FOLLOWS: // 224, RFC2980: 'Overview information follows'
                $data = $this->_getCompressedResponse();

                foreach ($data as $key => $value) {
                    $data[$key] = explode("\t", ltrim($value));
                }

                return $data;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC2980: 'No news group current selected'
                throw new exception("No news group current selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC2980: 'No article(s) selected'
                throw new exception("No article(s) selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // 502 RFC2980: 'no permission'
                throw new exception("No permission ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_UNKNOWN_COMMAND: // 500  RFC2980: 'unknown command'
                throw new exception("XZver not supported ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    protected function cmd_fast_xzver($range = NULL)
    {
        $command = 'XZVER';
        if (!is_null($range)) {
            $command .= ' ' . $range;
        }
        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_OVERVIEW_FOLLOWS: // 224, RFC2980: 'Overview information follows'

                return $this->_getCompressedResponse();
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC2980: 'No news group current selected'
                throw new exception("No news group current selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC2980: 'No article(s) selected'
                throw new exception("No article(s) selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // RFC2980: 'no permission'
                throw new exception("No permission ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    protected function cmd_fast_xover($range = NULL)
    {
        $command = 'XOVER';
        if (!is_null($range)) {
            $command .= ' ' . $range;
        }

        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_OVERVIEW_FOLLOWS: // 224, RFC2980: 'Overview information follows'

                return $this->_get_text_response();
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC2980: 'No news group current selected'
                throw new exception("No news group current selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC2980: 'No article(s) selected'
                throw new exception("No article(s) selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // RFC2980: 'no permission'
                throw new exception("No permission ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Returns a list of avaible headers which are send from newsserver to client for every news message
     *
     * @return mixed (array) of header names on success or (object) pear_error on failure
     */
    protected function cmd_list_overview_fmt()
    {
        $response = $this->_send_command('LIST OVERVIEW.FMT');

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_GROUPS_FOLLOW: // 215, RFC2980: 'information follows'
                $data = $this->_get_text_response();

                $format = array();

                foreach ($data as $line) {
                    // Check if postfixed by ':full' (case-insensitive)
                    if (0 == strcasecmp(substr($line, -5, 5), ':full')) {
                        // ':full' is _not_ included in tag, but value set to true
                        $format[substr($line, 0, -5)] = TRUE;
                    } else {
                        // ':' is _not_ included in tag; value set to false
                        $format[substr($line, 0, -1)] = FALSE;
                    }
                }

                return $format;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_SUPPORTED: // RFC2980: 'program error, function not performed'
                throw new exception("Internal server error, function not performed ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     *
     * The format of the returned array is:
     * $messages[message_id]
     *
     * @param optional string $field
     * @param optional string $range articles to fetch
     *
     * @return mixed (array) nested array of message and there headers on success or (object) pear_error on failure
     */
    protected function cmd_xhdr($field, $range = NULL)
    {
        $command = 'XHDR ' . $field;
        if (!is_null($range)) {
            $command .= ' ' . $range;
        }

        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_GROUP_SELECTED: // 221, RFC2980: 'Header follows'
                $data = $this->_get_text_response();

                $return = array();
                foreach ($data as $line) {
                    $line = explode(' ', ltrim($line), 2);
                    $return[$line[0]] = $line[1];
                }

                return $return;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC2980: 'No news group current selected'
                throw new exception("No news group current selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC2980: 'No current article selected'
                throw new exception("No current article selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_ID: // 430, RFC2980: 'No such article'
                throw new exception("No such article ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // RFC2980: 'no permission'
                throw new exception("No permission ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Fetches a list of (all) avaible newsgroup descriptions.
     * Depresated as of RFC2980.
     *
     * @param string $wildmat Wildmat of the groups, that is to be listed, defaults to '*';
     *
     * @return mixed (array) nested array with description of existing newsgroups on success or (object) pear_error on failure
     */
    protected function cmd_xgtitle($wildmat = '*')
    {
        $response = $this->_send_command('XGTITLE ' . $wildmat);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_GROUPS_AND_DESC_FOLLOW: // RFC2980: 'list of groups and descriptions follows'
                $data = $this->_get_text_response();

                $groups = array();

                foreach ($data as $line) {
                    preg_match("/^(.*?)\s(.*?$)/", ltrim($line), $matches);
                    $groups[$matches[1]] = (string) $matches[2];
                }

                return $groups;
                break;

            case NNTP_PROTOCOL_RESPONSECODE_GROUPS_UNAVAILABLE: // RFC2980: 'Groups and descriptions unavailable'
                throw new exception("Groups and descriptions unavailable ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Fetch message references from message number $first to $last
     *
     * @param optional string $range articles to fetch
     *
     * @return mixed (array) assoc. array of message references on success or (object) pear_error on failure
     */
    protected function cmd_xrover($range = NULL)
    {
        $command = 'XROVER';
        if (is_null($range)) {
            $command .= ' ' . $range;
        }

        $response = $this->_send_command($command);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_OVERVIEW_FOLLOWS: // 224, RFC2980: 'Overview information follows'
                $data = $this->_get_text_response();

                $return = array();
                foreach ($data as $line) {
                    $line = explode(' ', ltrim($line), 2);
                    $return[$line[0]] = $line[1];
                }

                return $return;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_GROUP_SELECTED: // 412, RFC2980: 'No news group current selected'
                throw new exception("No news group current selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_ARTICLE_SELECTED: // 420, RFC2980: 'No article(s) selected'
                throw new exception("No article(s) selected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // RFC2980: 'no permission'
                throw new exception("No permission ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * @param string $field
     * @param string $range
     * @param mixed  $wildmat
     *
     * @return mixed (array) nested array of message and there headers on success or (object) pear_error on failure
     */
    protected function cmd_xpat($field, $range, $wildmat)
    {
        if (is_array($wildmat)) {
            $wildmat = implode(' ', $wildmat);
        }

        $response = $this->_send_command('XPAT ' . $field . ' ' . $range . ' ' . $wildmat);

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_HEAD_FOLLOWS: // 221, RFC2980: 'Header follows'
                $data = $this->_get_text_response();

                $return = array();
                foreach ($data as $line) {
                    $line = explode(' ', ltrim($line), 2);
                    $return[$line[0]] = $line[1];
                }

                return $return;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NO_SUCH_ARTICLE_ID: // 430, RFC2980: 'No such article'
                throw new exception("No current article selected ({$this->_current_status_response()})");
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // RFC2980: 'no permission'
                throw new exception("No permission ({$this->_current_status_response()})", $response);
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Authenticate using 'original' method
     *
     * @param string $user The username to authenticate as.
     * @param string $pass The password to authenticate with.
     *
     * @return mixed (bool) true on success or (object) pear_error on failure
     */
    protected function cmd_authinfo($user, $pass)
    {
        // Send the username
        $response = $this->_send_command('AUTHINFO user ' . $user);

        // Send the password, if the server asks
        if (($response == NNTP_PROTOCOL_RESPONSECODE_AUTHENTICATION_CONTINUE) && ($pass !== NULL)) {
            // Send the password
            $response = $this->_send_command('AUTHINFO pass ' . $pass);
        }

        switch ($response) {
            case NNTP_PROTOCOL_RESPONSECODE_AUTHENTICATION_ACCEPTED: // RFC2980: 'Authentication accepted'

                return TRUE;
                break;
            case NNTP_PROTOCOL_RESPONSECODE_AUTHENTICATION_CONTINUE: // RFC2980: 'More authentication information required'
                throw new exception("Authentication uncompleted ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_AUTHENTICATION_REJECTED: // RFC2980: 'Authentication rejected'
                throw new exception("Authentication rejected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_NOT_PERMITTED: // RFC2980: 'No permission'
                throw new exception("Authentication rejected ({$this->_current_status_response()})", $response);
                break;
            case NNTP_PROTOCOL_RESPONSECODE_UNKNOWN_COMMAND:
            case NNTP_PROTOCOL_RESPONSECODE_SYNTAX_ERROR:
                throw new exception('Authentication failed', $response, $this->_current_status_response());
                break;
            default:
                return $this->_handle_unexpected_response($response);
        }
    }

    /**
     * Authenticate using 'simple' method
     *
     * @param string $user The username to authenticate as.
     * @param string $pass The password to authenticate with.
     *
     * @return mixed (bool) true on success or (object) pear_error on failure
     */
    protected function cmd_authinfo_simple($user, $pass)
    {
        throw new exception('The auth mode: "simple" is has not been implemented yet', NULL);
    }

    /**
     * Authenticate using 'generic' method
     *
     * @param string $user The username to authenticate as.
     * @param string $pass The password to authenticate with.
     *
     * @return mixed (bool) true on success or (object) pear_error on failure
     */
    protected function cmd_authinfo_generic($user, $pass)
    {
        throw new exception('The auth mode: "generic" is has not been implemented yet', NULL);
    }

    /**
     * Test whether we are connected or not.
     * @return bool true or false
     */
    protected function _is_connected()
    {
        return ($this->_socket instanceof socket) && is_resource($this->_socket->get_fp()) && !$this->_socket->eof();
    }
}
