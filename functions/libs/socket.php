<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Stig Bakken <ssb@php.net>                                   |
// |          Chuck Hagenbuch <chuck@horde.org>                           |
// |          Spearhead                                                   |
// +----------------------------------------------------------------------+
//

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}


function _stream_select (&$read, &$write, &$except, $tv_sec, $tv_usec = 0)
{
    if (function_exists('pcntl_signal_dispatch')) {
        pcntl_signal_dispatch();
    }

    stream_select($read, $write, $except, $tv_sec, $tv_usec);
    if (function_exists('pcntl_signal_dispatch')) {
        pcntl_signal_dispatch();
    }
}

/**
 * Generalised Socket class.
 *
 * @version 1.2
 * @author Stig Bakken <ssb@php.net>
 * @author Chuck Hagenbuch <chuck@horde.org>
 * @author Gavin Spearhead
 */
class socket
{
    // timeout in seconds that a socket will timeout
    const DEFAULT_SOCKET_TIMEOUT = 60;
    const NET_SOCKET_READ  = 1;
    const NET_SOCKET_WRITE = 2;
    const NET_SOCKET_ERROR = 4;
    /* Socket file pointer.  */
    public $fp;

    /* Whether the socket is blocking. Defaults to true.  */
    private $blocking;

    /* Whether the socket is persistent. Defaults to false.  */
    private $persistent;

    /* The IP address to connect to.  */
    private $addr;

    /* The port number to connect to.  */
    private $port;

    /* Number of seconds to wait on socket connections before assuming there's no more data. Defaults to no timeout.  */
    private $timeout;

    /* Number of bytes to read at a time in readLine() and readAll(). Defaults to 2048.  */
    private $line_length;

    public function __construct ()
    {
        $this->_init();

    }
    private function _init() 
    {
        $this->fp = NULL;
        $this->timeout = FALSE;
        $this->port = (int) 0;
        $this->addr = '';
        $this->persistent = FALSE;
        $this->blocking = FALSE;
        $this->line_length = (int) 2048;
    }
    public function __destruct ()
    {
        try {
            $this->disconnect();
        } catch (exception $e) {
        }
        $this->_init();
    }

    public function get_fp()
    {
        return $this->fp;
    }
    private function check_connected()
    {
        if (!is_resource($this->fp)) {
            throw new exception('Not connected');
        }
    }

    /**
     * Connect to the specified port. If called when the socket is
     * already connected, it disconnects and connects again.
     *
     * @param string  $addr       IP address or host name.
     * @param integer $port       TCP port number.
     * @param boolean $persistent (optional) Whether the connection is
     *                             persistent (kept open between requests
     *                             by the web server).
     * @param integer $timeout (optional) How long to wait for data.
     * @param array   $options See options for stream_context_create.
     *
     * @return boolean True on success
     */

    private function get_ip_addr($host, $ip_version)
    {
        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) || filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $host;
        }
        switch (strtolower($ip_version)) {
            case 'ipv6': 
                $flag = DNS_AAAA;
                break;
            case 'ipv4': 
                $flag = DNS_A;
                break;
            case 'both': 
                $flag = DNS_AAAA|DNS_A;
                break;
            default:
                throw new exception('Unknown IP version');
        }
        $rv = dns_get_record($host, $flag);
        if (!isset($rv[0]['type'])) {
            throw new exception("Unknown host");
        }
        foreach ($rv as $rec) {
            switch (strtoupper($rec['type'])) {
                case 'AAAA':
                    return '[' . $rec['ipv6'] . ']';
                case 'A':
                    return $rec['ip'];
            }
        }
    }

    public function connect($transport, $addr, $port = 0, $persistent = FALSE, $timeout = socket::DEFAULT_SOCKET_TIMEOUT, $options = NULL, $ipversion = 'both')
    {
        echo_debug ("$transport $addr $ipversion $port", DEBUG_HTTP);
        assert('is_numeric($port) && (is_numeric($timeout) || is_null($timeout)) && is_bool($persistent)');
        $this->force_disconnect();
        if (!$addr) {
            throw new exception('$addr cannot be empty');
        }

        $this->addr = $this->get_ip_addr($addr, $ipversion);
        if (!is_numeric($port) || $port > 65535 || $port < 1) {
            throw new exception ('Port must be between 1 and 65535');
        }
        echo_debug ($this->addr, DEBUG_HTTP);
        $this->port = $port;
        $this->persistent = ($persistent === TRUE);
        $this->timeout = $timeout;
        $this->transport = trim($transport);

        $errno = 0;
        $errstr = '';
        $url = "{$this->transport}{$this->addr}:$port";
        if ($options !== NULL) {
            if ($this->timeout !== NULL) {
                $timeout = $this->timeout;
            } else {
                $timeout = socket::DEFAULT_SOCKET_TIMEOUT;
            }
            $context = stream_context_create($options);
            $fp = stream_socket_client($url, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);
        } else {
            if ($this->timeout !== NULL) {
                $fp = @stream_socket_client($url, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
            } else {
                $fp = @stream_socket_client($url, $errno, $errstr);
            }
        }
        if ($fp === FALSE) {
            throw new exception_nntp_connect($errstr . ' (' . $errno . ') ');
        }

        $this->fp = $fp;
        if ($timeout !== NULL) {
            $this->set_timeout($timeout);
        }
        return $this->set_blocking($this->blocking);
    }

    /**
     * Disconnects from the peer, closes the socket.
     *
     * @return mixed true on success or an error object otherwise
     */
    public function disconnect()
    {
        $this->check_connected();
        $this->force_disconnect();

        return TRUE;
    }
    public function force_disconnect()
    {
        if (is_resource($this->fp)) {
            @fclose($this->fp);
        }
        $this->fp = NULL;
    }

    /*
     * Find out if the socket is in blocking mode.
     * @return boolean The current blocking mode.
     */
    public function is_blocking()
    {
        return $this->blocking;
    }

    /**
     * Sets whether the socket connection should be blocking or
     * not. A read call to a non-blocki	ng socket will return immediately
     * if there is no data available, whereas it will block until there
     * is data for blocking sockets.
     *
     * @param  boolean $mode True for blocking sockets, false for nonblocking.
     * @return mixed   true on success or an error object otherwise
     */
    public function set_blocking($mode)
    {
        assert('is_bool($mode)');
        $this->check_connected();

        $this->blocking = $mode;
        stream_set_blocking($this->fp, $this->blocking);

        return true;
    }

    /**
     * Sets the timeout value on socket descriptor,
     * expressed in the sum of seconds and microseconds
     *
     * @param  integer $seconds      Seconds.
     * @param  integer $microseconds Microseconds.
     * @return mixed   true on success or an error object otherwise
     */
    public function set_timeout($seconds, $microseconds=0)
    {
        assert('is_numeric($seconds) && is_numeric($microseconds)');
        $this->check_connected();

        return stream_set_timeout($this->fp, $seconds, $microseconds);
    }
    public function has_timedout()
    {
        if ($this->timeout === NULL) {
            return FALSE;
        }
        $info = stream_get_meta_data($this->fp);

        return $info['timed_out'];
    }

    public function check_readable()
    {
        if (!$this->is_readable()) {
            $this->force_disconnect();
            throw new exception_nntp_connect('Read timeout occurred');
        }
    }

    public function check_writeable()
    {
        if (!$this->is_writeable()) {
            $this->force_disconnect();
            throw new exception_nntp_connect('Write timeout occurred');
        }
    }
    protected function is_readable()
    {
        // returns TRUE if there is data reabable, a read will not block
        // returns FALSE if a timeout has occurred

        if (!is_resource($this->fp)) {
            return FALSE;
        }

        if ($this->timeout === NULL) {  // if no timeout set, we assume we can read
            return TRUE;
        }

        $null = NULL;
        $r = array ($this->fp);
        // do a quick check first, typically this succeeds and we do less expensive time() calls
        $rv = _stream_select($r, $null, $null, 0, 10);
        if (count($r) > 0) {
            return TRUE;
        }
        // a slow check later where we handle timeout stuff.
        $timeout = $this->timeout;
        $start_time = time();
        while (1) {
            $null = NULL;
            $r = array($this->fp);
            $rv = _stream_select($r, $null, $null, $timeout);
            if ($rv === FALSE) {
                $timeout = max(0, $timeout - (time() - $start_time));
                continue;
            } else {
                return (count($r) > 0) ? TRUE : FALSE;
            }
        }
        return FALSE;
    }

    protected function is_writeable()
    {
        // returns TRUE if there is data reabable, a read will not block
        // returns FALSE if a timeout has occurred
        if (!is_resource($this->fp)) {
            return FALSE;
        }
        if ($this->timeout === NULL) {  // if no timeout set, we assume we can read
            return TRUE;
        }
        $null = NULL;
        $w = array($this->fp);
        $rv = _stream_select($null, $w, $null, 0, 10); // do a quick check first
        if (count($w) > 0) {
            return TRUE;
        }
        $timeout = $this->timeout;
        $start_time = time();
        while (1) {
            $null = NULL;
            $w = array($this->fp);
            $rv = _stream_select($null, $w, $null, $timeout);
            if ($rv === FALSE) {
                $timeout = max(0, $timeout - (time() - $start_time));
                continue;
            } else {
                return (count($w) > 0) ? TRUE : FALSE;
            }
        }
        return FALSE;
    }

    /**
     * Read a specified amount of data. This is guaranteed to return,
     * and has the added benefit of getting everything in one fread()
     * chunk; if you know the size of the data you're getting
     * beforehand, this is definitely the way to go.
     *
     * @param integer $size The number of bytes to read from the socket.
     *


    /**
     * Sets the file buffering size on the stream.
     * See php's stream_set_write_buffer for more information.
     *
     * @param  integer $size Write buffer size.
     * @return mixed   on success or an exception thrown object otherwise
     */
    public function set_write_buffer($size)
    {
        assert('is_numeric($size)');
        $this->check_connected();

        $returned = stream_set_write_buffer($this->fp, $size);
        if ($returned == 0) {
            return TRUE;
        }
        throw new exception('Cannot set write buffer.');
    }

    /**
     * Returns information about an existing socket resource.
     * Currently returns four entries in the result array:
     *
     * <p>
     * timed_out (bool) - The socket timed out waiting for data<br>
     * blocked (bool) - The socket was blocked<br>
     * eof (bool) - Indicates EOF event<br>
     * unread_bytes (int) - Number of bytes left in the socket buffer<br>
     * </p>
     *
     * @return mixed Array containing information about existing socket resource or an error object otherwise
     */
    public function get_status()
    {
        $this->check_connected();

        return stream_get_meta_data($this->fp);
    }

    /**
     * Get a specified line of data
     *
     * @return $size bytes of data from the socket, or a exception thrown if
     *         not connected.
     */
    public function gets2()
    {
        $this->check_readable();

        return fgets($this->fp);
    }
    public function gets($size)
    {
        assert('is_numeric($size)');
        $this->check_readable();

        return fgets($this->fp, $size);
    }

    /**
     * Read a specified amount of data. This is guaranteed to return,
     * and has the added benefit of getting everything in one fread()
     * chunk; if you know the size of the data you're getting
     * beforehand, this is definitely the way to go.
     *
     * @param integer $size The number of bytes to read from the socket.
     * @return $size bytes of data from the socket, or a exception thrown if
     *         not connected.
     */
    public function read($size)
    {
        assert('is_numeric($size)');
        $this->check_readable();

        return stream_get_contents($this->fp, $size);
    }

    /**
     * Write a specified amount of data.
     *
     * @param string  $data      Data to write.
     * @param integer $blocksize Amount of data to write at once.
     *                            NULL means all at once.
     *
     * @return mixed true on success or an error object otherwise
     */
    public function write($data, $blocksize = NULL)
    {
        $this->check_connected();
        if ($blocksize === NULL) {
            $blocksize = 1024;
        }

        assert('is_numeric($blocksize)');
        $pos = 0;
        $size = strlen($data);

        while ($pos < $size) {
            $this->check_writeable();
            $written = @fwrite($this->fp, substr($data, $pos, $blocksize));
            if ($written === FALSE || $this->has_timedout()) {
                return FALSE;
            }
            $pos += $written;
        }

        return $pos;
    }

    /**
     * Write a line of data to the socket, followed by a trailing "\r\n".
     *
     * @return mixed fputs result, or an error
     */
    public function write_line($data)
    {
        $this->check_connected();

        if ($this->timeout !== NULL) {
            $this->set_timeout($this->timeout);
        }
        $this->check_writeable();
        $rv = @fwrite($this->fp, $data . "\r\n");
        if ($this->has_timedout()) {
            return FALSE;
        }

        return $rv;
    }

    /**
     * Tests for end-of-file on a socket descriptor.
     *
     * Also returns true if the socket is disconnected.
     *
     * @return bool
     */
    public function eof()
    {
        return (!is_resource($this->fp) || feof($this->fp));
    }

    /**
     * Reads a byte of data
     *
     * @return 1 byte of data from the socket, or a exception thrown if
     *         not connected.
     */
    public function read_byte()
    {
        $this->check_readable();
        $buf = @stream_get_contents($this->fp, 1);
        if ($buf  === FALSE) {
            return FALSE;
        }

        return ord($buf);
    }

    /**
     * Reads a word of data
     *
     * @return 1 word of data from the socket, or a exception thrown if
     *         not connected.
     */
    public function read_word()
    {
        $this->check_readable();
        $buf = @stream_get_contents($this->fp, 2);
        if ($buf === FALSE) {
            return FALSE;
        }

        return (ord($buf[0]) + (ord($buf[1]) << 8));
    }

    /**
     * Reads an int of data
     *
     * @return integer 1 int of data from the socket, or a exception thrown if
     *                  not connected.
     */
    public function read_int()
    {
        $this->check_readable();
        $buf = @stream_get_contents($this->fp, 4);
        if ($buf  === FALSE) {
            return FALSE;
        }

        return (ord($buf[0]) + (ord($buf[1]) << 8) + (ord($buf[2]) << 16) + (ord($buf[3]) << 24));
    }

    /**
     * Reads an IP Address and returns it in a dot formated string
     *
     * @return Dot formated string, or a exception thrown if
     *         not connected.
     */
    public function read_ipaddress()
    {
        $this->check_readable();
        $buf = @stream_get_contents($this->fp, 4);
        if ($buf  === FALSE) {
            return FALSE;
        }

        return sprintf('%d.%d.%d.%d', ord($buf[0]), ord($buf[1]), ord($buf[2]), ord($buf[3]));
    }

    /**
     * Read until either the end of the socket or a newline, whichever
     * comes first. Strips the trailing newline from the returned data.
     *
     * @return All available data up to a newline, without that
     *         newline, or until the end of the socket, or a exception thrown if
     *         not connected.
     */
    public function read_line()
    {
        $this->check_connected();
        if (feof($this->fp)) {
            return FALSE;
        }
        $line = '';
        while (!feof($this->fp)) {
            $this->check_readable();
            $buf = @fgets($this->fp, $this->line_length);
            if ($buf === FALSE) {
                return FALSE;
            }

            $line .= $buf;
            if (substr_compare($buf, "\n", -1) == 0) {
                return rtrim($line, "\r\n");
            }
            if (($this->timeout !== NULL) && $this->has_timedout()) {
                return FALSE;
            }
        }

        return FALSE;
    }

    /**
     * Read until the socket closes, or until there is no more data in
     * the inner PHP buffer. If the inner buffer is empty, in blocking
     * mode we wait for at least 1 byte of data. Therefore, in
     * blocking mode, if there is no data at all to be read, this
     * function will never exit (unless the socket is closed on the
     * remote end).
     *
     * @return string All data until the socket closes, or a exception thrown if
     *                 not connected.
     */
    public function read_all()
    {
        $this->check_connected();

        $data = '';
        while (!feof($this->fp)) {
            $this->check_readable();
            $data .= @stream_get_contents($this->fp, $this->line_length);
            if ($data === FALSE) {
                return FALSE;
            }
        }

        return $data;
    }

    /**
     * Runs the equivalent of the select() system call on the socket
     * with a timeout specified by tv_sec and tv_usec.
     *
     * @param integer $state   Which of read/write/error to check for.
     * @param integer $tv_sec  Number of seconds for timeout.
     * @param integer $tv_usec Number of microseconds for timeout.
     *
     * @return False if select fails, integer describing which of read/write/error
     *         are ready, or exception thrown if not connected.
     */
    public function select($state, $tv_sec, $tv_usec = 0)
    {
        assert('is_numeric($tv_sec) && is_numeric($tv_usec) && is_numeric($state) && $state > 0');
        $this->check_connected();

        $read = $write = $except = NULL;
        if ($state & self::NET_SOCKET_READ) {
            $read[] = $this->fp;
        }
        if ($state & self::NET_SOCKET_WRITE) {
            $write[] = $this->fp;
        }
        if ($state & self::NET_SOCKET_ERROR) {
            $except[] = $this->fp;
        }
        if (FALSE === _stream_select($read, $write, $except, $tv_sec, $tv_usec)) {
            return FALSE;
        }

        $result = 0;
        if (count($read)) {
            $result |= self::NET_SOCKET_READ;
        }
        if (count($write)) {
            $result |= self::NET_SOCKET_WRITE;
        }
        if (count($except)) {
            $result |= self::NET_SOCKET_ERROR;
        }

        return $result;
    }

    /**
     * Turns encryption on/off on a connected socket.
     *
     * @param bool $enabled Set this parameter to true to enable encryption
     *                          and false to disable encryption.
     * @param integer $type Type of encryption. See
     *                          http://se.php.net/manual/en/function.stream-socket-enable-crypto.php for values.
     *
     * @return false on error, true on success and 0 if there isn't enough data and the
     *         user should try again (non-blocking sockets only). A exception thrown object
     *         is returned if the socket is not connected
     */
    public function enable_crypto($enabled, $type)
    {
        if (extension_loaded('openssl')) {
            $this->check_connected();

            return @stream_socket_enable_crypto($this->fp, $enabled, $type);
        } else {
            throw new exception('OpenSSL module required', INVALID_PHP_VERSION);
        }
    }
}
