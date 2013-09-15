<?php
/**
* Project:     MagpieRSS: a simple RSS integration tool
* File:        rss_parse.inc  - parse an RSS or Atom feed
*               return as a simple object.
*
* Handles RSS 0.9x, RSS 2.0, RSS 1.0, and Atom 0.3
*
* The lastest version of MagpieRSS can be obtained from:
* http://magpierss.sourceforge.net
*
* For questions, help, comments, discussion, etc., please join the
* Magpie mailing list:
* magpierss-general@lists.sourceforge.net
*
* @author           Kellan Elliott-McCrea <kellan@protest.net>
* @Updated by       Gavin Spearhead
* @version          0.7a
* @license          GPL
*
*/

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

function concat (&$str1, $str2='')
{
    if (!isset($str1)) {
        $str1 = '';
    }
    $str1 .= $str2;
}

/**
* Hybrid parser, and object, takes RSS as a string and returns a simple object.
*
* see: rss_fetch.inc for a simpler interface with integrated caching support
*
*/
class MagpieRSS
{
    const RSS = 'RSS';
    const ATOM = 'Atom';

    private $parser;

    public $current_item   = array();  // item currently being parsed
    public $items          = array();  // collection of parsed items
    public $channel        = array();  // hash of channel fields
    public $textinput      = array();
    public $image          = array();
    public $feed_type;
    public $feed_version;
    public $encoding       = '';       // output encoding of parsed rss
    public $_source_encoding = '';     // only set if we have to parse xml prolog

    // define some constants

    private static $_CONTENT_CONSTRUCTS = array('content', 'summary', 'info', 'title', 'tagline', 'copyright');
    private static $_KNOWN_ENCODINGS    = array('UTF-8', 'US-ASCII', 'ISO-8859-1');

    // parser variables, useless if you're not a parser, treat as private
    public $stack              = array(); // parser stack
    public $inchannel          = FALSE;
    public $initem             = FALSE;
    public $incontent          = FALSE; // if in Atom <content mode="xml"> field
    public $intextinput        = FALSE;
    public $inimage            = FALSE;
    public $current_namespace  = FALSE;
    public $last_modified      = 0;

    /**
     *  Set up XML parser, parse source, and return populated RSS object..
     *
     *  @param string $source           string containing the RSS to be parsed
     *
     *  NOTE:  Probably a good idea to leave the encoding options alone unless
     *         you know what you're doing as PHP's character set support is
     *         a little weird.
     *
     *  NOTE:  A lot of this is unnecessary but harmless with PHP5
     *
     *
     *  @param string $output_encoding  output the parsed RSS in this character
     *                                  set defaults to ISO-8859-1 as this is PHP's
     *                                  default.
     *
     *                                  NOTE: might be changed to UTF-8 in future
     *                                  versions.
     *
     *  @param string $input_encoding   the character set of the incoming RSS source.
     *                                  Leave blank and Magpie will try to figure it
     *                                  out.
     *
     *
     *  @param bool   $detect_encoding  if false Magpie won't attempt to detect
     *                                  source encoding. (caveat emptor)
     *
     */
    public function __construct ($source, $output_encoding='ISO-8859-1', $input_encoding=NULL, $detect_encoding=TRUE)
    {
        $source = $this->create_parser($source, $output_encoding, $input_encoding, $detect_encoding);

        if (!is_resource($this->parser)) {
            throw new exception('Failed to create an instance of the XML parser.', ERR_MAGPIE_FAILED );
        }

        # pass in parser, and a reference to this object
        # setup handlers
        #
        xml_set_object( $this->parser, $this );
        xml_set_element_handler($this->parser, 'feed_start_element', 'feed_end_element' );
        xml_set_character_data_handler( $this->parser, 'feed_cdata' );

        $status = xml_parse( $this->parser, $source );

        if (! $status) {
            $errorcode = xml_get_error_code( $this->parser );
            if ($errorcode != XML_ERROR_NONE) {
                $xml_error = xml_error_string( $errorcode );
                $error_line = xml_get_current_line_number($this->parser);
                $error_col = xml_get_current_column_number($this->parser);
                $errormsg = "$xml_error at line $error_line, column $error_col";

                throw new exception( $errormsg, ERR_MAGPIE_FAILED);
            }
        }

        xml_parser_free( $this->parser );
        $this->normalize();
    }

    public function feed_start_element($p, $element, array &$attrs)
    {
        $el = $element = strtolower($element);
        $attrs = array_change_key_case($attrs, CASE_LOWER);

        // check for a namespace, and split if found
        $ns = FALSE;
        if ( strpos( $element, ':' ) ) {
            list($ns, $el) = explode( ':', $element, 2);
        }
        if ($ns && $ns != 'rdf') {
            $this->current_namespace = $ns;
        }

        # if feed type isn't set, then this is first element of feed
        # identify feed from root element
        #
        if (!isset($this->feed_type) ) {
            if ($el == 'rdf') {
                $this->feed_type = self::RSS;
                $this->feed_version = '1.0';
            } elseif ($el == 'rss') {
                $this->feed_type = self::RSS;
                $this->feed_version = $attrs['version'];
            } elseif ($el == 'feed') {
                $this->feed_type = self::ATOM;
                $this->feed_version = $attrs['version'];
                $this->inchannel = true;
            }

            return;
        }

        if ($el == 'channel') {
            $this->inchannel = TRUE;
        } elseif ($el == 'item' || $el == 'entry') {
            $this->initem = TRUE;
            if ( isset($attrs['rdf:about']) ) {
                $this->current_item['about'] = $attrs['rdf:about'];
            }
        }

        // if we're in the default namespace of an RSS feed,
        //  record textinput or image fields
        elseif ($this->feed_type == self::RSS &&
            $this->current_namespace == '' &&
            $el == 'textinput') {
            $this->intextinput = TRUE;
        } elseif ($this->feed_type == self::RSS &&
            $this->current_namespace == '' &&
            $el == 'image') {
            $this->inimage = TRUE;
        }

        # handle atom content constructs
        elseif ( $this->feed_type == self::ATOM && in_array($el, self::$CONTENT_CONSTRUCTS) ) {
            // avoid clashing w/ RSS mod_content
            if ($el == 'content') {
                $el = 'atom_content';
            }
            $this->incontent = $el;
        }
        // if inside an Atom content construct (e.g. content or summary) field treat tags as text
        elseif ($this->feed_type == self::ATOM && $this->incontent) {
            // if tags are inlined, then flatten
            $attrs_str = join(' ',
                array_map('map_attrs',
                array_keys($attrs),
                array_values($attrs) ) );

            $this->append_content( "<$element $attrs_str>"  );
            array_unshift( $this->stack, $el );
        }

        // Atom support many links per containging element.
        // Magpie treats link elements of type rel='alternate'
        // as being equivalent to RSS's simple link element.
        //
        elseif ($this->feed_type == self::ATOM && $el == 'link') {
            if ( isset($attrs['rel']) && $attrs['rel'] == 'alternate' ) {
                $link_el = 'link';
            } else {
                $link_el = 'link_' . $attrs['rel'];
            }
            $this->append($link_el, $attrs['href']);
        } else { // set stack[0] to current element
            array_unshift($this->stack, $el);
        }
    }

    public function feed_cdata ($p, $text)
    {
        if ($this->feed_type == self::ATOM && $this->incontent) {
            $this->append_content( $text );
        } else {
            $current_el = join('_', array_reverse($this->stack));
            $this->append($current_el, $text);
        }
    }

    public function feed_end_element ($p, $el)
    {
        $el = strtolower($el);

        if ($el == 'item' || $el == 'entry') {
            $this->items[] = $this->current_item;
            $this->current_item = array();
            $this->initem = FALSE;
        } elseif ($this->feed_type == self::RSS && $this->current_namespace == '' && $el == 'textinput') {
            $this->intextinput = FALSE;
        } elseif ($this->feed_type == self::RSS && $this->current_namespace == '' && $el == 'image') {
            $this->inimage = FALSE;
        } elseif ($this->feed_type == self::ATOM && in_array($el, $this->_CONTENT_CONSTRUCTS) ) {
            $this->incontent = FALSE;
        } elseif ($el == 'channel' || $el == 'feed') {
            $this->inchannel = FALSE;
        } elseif ($this->feed_type == self::ATOM && $this->incontent) {
            // balance tags properly
            // note:  i don't think this is actually neccessary
            if ($this->stack[0] == $el) {
                $this->append_content("</$el>");
            } else {
                $this->append_content("<$el />");
            }

            array_shift( $this->stack );
        } else {
            array_shift( $this->stack );
        }

        $this->current_namespace = FALSE;
    }

    public function append_content($text)
    {
        if ($this->initem) {
            concat( $this->current_item[ $this->incontent ], $text );
        } elseif ($this->inchannel) {
            concat( $this->channel[ $this->incontent ], $text );
        }
    }

    // smart append - field and namespace aware
    public function append($el, $text)
    {
        if (!$el) {
            return;
        }
        if ($this->current_namespace) {
            if ($this->initem) {
                concat( $this->current_item[ $this->current_namespace ][ $el ], $text);
            } elseif ($this->inchannel) {
                concat( $this->channel[ $this->current_namespace][ $el ], $text );
            } elseif ($this->intextinput) {
                concat( $this->textinput[ $this->current_namespace][ $el ], $text );
            } elseif ($this->inimage) {
                concat( $this->image[ $this->current_namespace ][ $el ], $text );
            }
        } else {
            if ($this->initem) {
                concat( $this->current_item[ $el ], $text);
            } elseif ($this->intextinput) {
                concat( $this->textinput[ $el ], $text );
            } elseif ($this->inimage) {
                concat( $this->image[ $el ], $text );
            } elseif ($this->inchannel) {
                concat( $this->channel[ $el ], $text );
            }
        }
    }

    public function normalize ()
    {
        // if atom populate rss fields
        if ( $this->is_atom() ) {
            $this->channel['description'] = $this->channel['tagline'];
            for ( $i = 0; $i < count($this->items); $i++) {
                $item = $this->items[$i];
                if ( isset($item['summary']) ) {
                    $item['description'] = $item['summary'];
                }
                if ( isset($item['atom_content'])) {
                    $item['content']['encoded'] = $item['atom_content'];
                }

                $atom_date = (isset($item['issued']) ) ? $item['issued'] : $item['modified'];
                if ($atom_date) {
                    $epoch = @strtotime($atom_date);
                    if ($epoch and $epoch > 0) {
                        $item['date_timestamp'] = $epoch;
                    }
                }

                $this->items[$i] = $item;
            }
        } elseif ( $this->is_rss() ) {
            $this->channel['tagline'] = $this->channel['description'];
            for ( $i = 0; $i < count($this->items); $i++) {
                $item = $this->items[$i];
                if ( isset($item['description'])) {
                    $item['summary'] = $item['description'];
                }
                if ( isset($item['content']['encoded'])) {
                    $item['atom_content'] = $item['content']['encoded'];
                }
                if ( $this->is_rss() == '1.0' && isset($item['dc']['date']) ) {
                    $epoch = @strtotime($item['dc']['date']);
                    if ($epoch and $epoch > 0) {
                        $item['date_timestamp'] = $epoch;
                    }
                } elseif ( isset($item['pubdate']) ) {
                    $epoch = @strtotime($item['pubdate']);
                    if ($epoch > 0) {
                        $item['date_timestamp'] = $epoch;
                    }
                }

                $this->items[$i] = $item;
            }
        }
    }

    public function is_rss ()
    {
        if ($this->feed_type == self::RSS) {
            return $this->feed_version;
        } else {
            return FALSE;
        }
    }

    public function is_atom()
    {
        if ($this->feed_type == self::ATOM) {
            return $this->feed_version;
        } else {
            return FALSE;
        }
    }

    /**
     * return XML parser, and possibly re-encoded source
     *
     */
    public function create_parser($source, $out_enc, $in_enc, $detect)
    {
        if(!$detect && $in_enc)
            $this->parser = xml_parser_create($in_enc);
        else
            $this->parser = xml_parser_create('');

        if ($out_enc) {
            $this->encoding = $out_enc;
            xml_parser_set_option($this->parser, XML_OPTION_TARGET_ENCODING, $out_enc);
        }

        return $source;
    }

    public function known_encoding($enc)
    {
        $enc = strtoupper($enc);
        if ( in_array($enc, self::$_KNOWN_ENCODINGS) )
            return $enc;
         else
            return FALSE;
    }

} // end class RSS

function map_attrs($k, $v)
{
    return "$k=\"$v\"";
}
