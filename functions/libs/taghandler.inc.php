<?php

class TagHandler
{
    /*
     * Denied tags -- used to be able to process
     * all this in two passes (eg: used for tags needing information for other stuff)
     */
    private static $deniedtags = array();

    /*
     * UBB tag configuration params
     */
    public static $tagconfig =
        array(
            /* ------- a -------------------- */
            'a' =>
            array('a' =>
            array('closetags' => array('a'),
                'allowedchildren' => array(NULL),
                'handler' => array('TagHandler', 'handle_empty') )
            ),

            /* ------- b -------------------- */
            'b'	=>
            array('b' =>
            array('closetags' => array('b'),
                'allowedchildren' => array(NULL),
                'handler' => array('TagHandler', 'handle_bold') ),
            'br' =>
            array('closetags' => array(NULL),
                'allowedchildren' => array(''),
                'handler' => array('TagHandler', 'handle_br') )
            ),
            /* ------- c -------------------- */
            'c' =>
            array('color' =>
            array('closetags' => array('color'),
                'allowedchildren' => array(NULL),
                'handler' => array('TagHandler', 'handle_empty') )
            ),

            /* ------- i -------------------- */
            'i'	=>
            array('i' =>
            array('closetags' => array('i'),
                'allowedchildren' => array(NULL),
                'handler' => array('TagHandler', 'handle_italic') ),
            'img' =>
            array('closetags' => array(NULL),
                'allowedchildren' => array(''),
                'handler' => array('TagHandler', 'handle_img') )

            ),
            'q'	=>
            array('quote' =>
            array('closetags' => array('quote'),
                'allowedchildren' => array(NULL),
                'handler' => array('TagHandler', 'handle_quote') ),
            ),

            /* ------- u ------------------- */
            'u'	=>
            array('u' =>
            array('closetags' => array('u'),
                'allowedchildren' => array(NULL),
                'handler' => array('TagHandler', 'handle_underline') ),

            'url' =>
            array('closetags' => array('url'),
                'allowedchildren' => array(''),
                'handler' => array('TagHandler', 'handle_url') )
            ),
            'y'	=>
            array('youtube' =>
            array('closetags' => array('youtube'),
                'allowedchildren' => array(NULL),
                'handler' => array('TagHandler', 'handle_empty') ),
        ),

        );

    /**
     * Returns the tag config for a given tag
     */
    public static function gettagconfig($tagname)
    {
        if ((strlen($tagname) >= 1) && (isset(TagHandler::$tagconfig[$tagname[0]][$tagname]))) {
            return TagHandler::$tagconfig[$tagname[0]][$tagname];
        } else {
            return NULL;
        }
    }

    /*
     * Set the list of denied tags
     */
    public static function setdeniedtags($deniedtags)
    {
        TagHandler::$deniedtags = $deniedtags;
    }

    /*
     * Returns the list of denied tags
     */
    public static function getdeniedtags()
    {
        return TagHandler::$deniedtags;
    }

    /*
     * Processes an tag (when allowed)
     */
    public static function process_tag($tagname, $params, $contents)
    {
        if (array_search($tagname, TagHandler::$deniedtags) !== FALSE) {
            return NULL;
        }

        return call_user_func(TagHandler::$tagconfig[$tagname[0]][$tagname]['handler'], $params, $contents);
    }

    /* Returns an empty append/prepend, used for deprecated tags */
    public static function handle_empty($params, $contents)
    {
        return array('prepend' => '', 'content' => $contents, 'append' => '');
    }

    public static function handle_bold($params, $contents)
    {
        return array('prepend' => '<b>', 'content' => $contents, 'append' => '</b>');
    }

    public static function handle_underline($params, $contents)
    {
        return array('prepend' => '<u>', 'content' => $contents, 'append' => '</u>');
    }

    public static function handle_italic($params, $contents)
    {
        return array('prepend' => '<i>', 'content' => $contents, 'append' => '</i>');
    }

    /* Handles [br] */
    public static function handle_br($params, $contents)
    {
        return array('prepend' => '<br/>', 'content' => $contents, 'append' => '');
    }

    public static function handle_url($params, $contents)
    {
        # are only specific images allowed?
        if ($params['originalparams'] != '') {
            $url = substr($params['originalparams'], 1);
        } elseif (substr($contents[0]['content'], 0, 7) == 'http://' || substr($contents[0]['content'], 0,8) == 'https://') {
            $url = $contents[0]['content'];
        } else {
            return TagHandler::handle_empty($params, $contents);
        }

        return array('prepend' => '<span class="buttonlike" onclick="javascript:jump(\'' . $url . '\', true );">',
            'content' => $contents,
            'append' => '</span>');
    }

    public static function handle_img($params, $contents)
    {
        $origAppend = '';
        $content = '';
        # are only specific images allowed?
        if (isset(TagHandler::$tagconfig['i']['img']['allowedimgs'])) {
            if (!isset(TagHandler::$tagconfig['i']['img']['allowedimgs'][$params['params'][0]])) {
                return TagHandler::handle_empty($params, $contents);
            } else {
                $origAppend = $contents;
                $content = TagHandler::$tagconfig['i']['img']['allowedimgs'][$params['params'][0]];
                if (substr($content, 0, 7) != 'http://' && substr($content, 0,8) != 'https://') {
                    return TagHandler::handle_empty($params, $contents);
                }
            }
        }

        return array('prepend' => '<span class="buttonlike" onclick="javascript:jump(\'' . $content . '\', true );">' . $content . '</span>',
            'content' => $origAppend,
            'append' => '');
    }

    /* handle the noubb tag */
    public static function handle_noubb($params, $contents)
    {
        return array('prepend' => '', 'content' => $contents, 'append' => '');
    }
    /* handle the quote tag */
    public static function handle_quote($params, $contents)
    {
        # quote it

        return Array('prepend' => '<blockquote><strong>' . sprintf(_("%s commented earlier:"), substr($params['originalparams'], 1)). '</strong><br>',
            'content' => $contents,
            'append' => '</blockquote>');
    }
}
