<?php

namespace rabint\components;

use http\Exception\InvalidArgumentException;
use rabint\helpers\html;

/**
 * WordPress API for creating bbcode-like tags or what WordPress calls
 * "shortcodes". The tag and attribute parsing or regular expression code is
 * based on the Textpattern tag parser.
 *
 * A few examples are below:
 *
 * [shortcode /]
 * [shortcode foo="bar" baz="bing" /]
 * [shortcode foo="bar"]content[/shortcode]
 *
 * Shortcode tags support attributes and enclosed content, but does not entirely
 * support inline shortcodes in other shortcodes. You will have to call the
 * shortcode parser in your function to account for that.
 *
 * {@internal
 * Please be aware that the above note was made during the beta of WordPress 2.6
 * and in the future may not be accurate. Please update the note when it is no
 * longer the case.}}
 *
 * To apply shortcode tags to content:
 *
 *     $out = render( $content );
 *
 * @link https://codex.wordpress.org/Shortcode_API
 *
 * @package WordPress
 * @subpackage Shortcodes
 * @since 2.5.0
 */

/**
 * Description of shortcode
 *
 * @author mojtaba
 */
class Shortcode extends \yii\base\Component
{

    /**
     * Container for storing shortcode tags and their hook to call for the shortcode
     */
    public $shortcodes = [];

    public function init()
    {
        parent::init();
//        $this->add('rmapview', array(\app\modules\vcc\shortcodes::className(), 'rmapview'));
    }

    /**
     * Add hook for shortcode tag.
     *
     * There can only be one hook for each shortcode. Which means that if another
     * plugin has a similar shortcode, it will override yours or yours will override
     * theirs depending on which order the plugins are included and/or ran.
     *
     * Simplest example of a shortcode tag using the API:
     *
     *     // [footag foo="bar"]
     *     function footag_func( $atts ) {
     *         return "foo = {
     *             $atts[foo]
     *         }";
     *     }
     *     add( 'footag', 'footag_func' );
     *
     * Example with nice attribute defaults:
     *
     *     // [bartag foo="bar"]
     *     function bartag_func( $atts ) {
     *         $args = $this->atts( array(
     *             'foo' => 'no foo',
     *             'baz' => 'default baz',
     *         ), $atts );
     *
     *         return "foo = {$args['foo']}";
     *     }
     *     add( 'bartag', 'bartag_func' );
     *
     * Example with enclosed content:
     *
     *     // [baztag]content[/baztag]
     *     function baztag_func( $atts, $content = '' ) {
     *         return "content = $content";
     *     }
     *     add( 'baztag', 'baztag_func' );
     *
     * @param string $tag Shortcode tag to be searched in post content.
     * @param callable $func Hook to run when shortcode is found.
     * @since 2.5.0
     *
     * @global array $this ->shortcodes
     *
     */
    function add($tag, $func)
    {
        if ('' == trim($tag)) {
            return FALSE;
        }

        if (0 !== preg_match('@[<>&/\[\]\x00-\x20=]@', $tag)) {
            return false;
        }

        $this->shortcodes[$tag] = $func;
    }

    /**
     * Removes hook for shortcode.
     *
     * @param string $tag Shortcode tag to remove hook for.
     * @global array $this ->shortcodes
     *
     * @since 2.5.0
     *
     */
    function remove($tag)
    {
        unset($this->shortcodes[$tag]);
    }

    /**
     * Clear all shortcodes.
     *
     * This function is simple, it clears all of the shortcode tags by replacing the
     * shortcodes global by a empty array. This is actually a very efficient method
     * for removing all shortcodes.
     *
     * @since 2.5.0
     *
     * @global array $this ->shortcodes
     */
    function remove_all()
    {
        $this->shortcodes = array();
    }

    /**
     * Whether a registered shortcode exists named $tag
     *
     * @param string $tag Shortcode tag to check.
     * @return bool Whether the given shortcode exists.
     * @since 3.6.0
     *
     * @global array $this ->shortcodes List of shortcode tags and their callback hooks.
     *
     */
    function exists($tag)
    {
        return array_key_exists($tag, $this->shortcodes);
    }

    /**
     * Whether the passed content contains the specified shortcode
     *
     * @param string $content Content to search for shortcodes.
     * @param string $tag Shortcode tag to check.
     * @return bool Whether the passed content contains the given shortcode.
     * @global array $this ->shortcodes
     *
     * @since 3.6.0
     *
     */
    function has($content, $tag)
    {
        if (false === strpos($content, '[')) {
            return false;
        }

        if ($this->exists($tag)) {
            preg_match_all('/' . $this->get_regex() . '/', $content, $matches, PREG_SET_ORDER);
            if (empty($matches))
                return false;

            foreach ($matches as $shortcode) {
                if ($tag === $shortcode[2]) {
                    return true;
                } elseif (!empty($shortcode[5]) && $this->has($shortcode[5], $tag)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Search content for shortcodes and filter shortcodes through their hooks.
     *
     * If there are no shortcode tags defined, then the content will be returned
     * without any filtering. This might cause issues when plugins are disabled but
     * the shortcode will still show up in the post or content.
     *
     * @param string $content Content to search for shortcodes.
     * @param bool $ignore_html When true, shortcodes inside HTML elements will be skipped.
     * @return string Content with shortcodes filtered out.
     * @global array $this ->shortcodes List of shortcode tags and their callback hooks.
     *
     * @since 2.5.0
     *
     */
    function render($content, $ignore_html = false)
    {
//        print_r($this->shortcodes);
//        die('--');

        if (false === strpos($content, '[')) {
            return $content;
        }
        if (empty($this->shortcodes) || !is_array($this->shortcodes))
            return $content;

// Find all registered tag names in $content.
        preg_match_all('@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches);
        $tagnames = array_intersect(array_keys($this->shortcodes), $matches[1]);

        if (empty($tagnames)) {
            return $content;
        }

        $content = $this->do_in_html_tags($content, $ignore_html, $tagnames);

        $pattern = $this->get_regex($tagnames);
        $content = preg_replace_callback("/$pattern/", [$this, 'do_tag'], $content);
        //pr($pattern, 1);

// Always restore square braces so we don't break things like <!--[if IE ]>
        $content = $this->unescape_invalids($content);

        return $content;
    }

    /**
     * Retrieve the shortcode regular expression for searching.
     *
     * The regular expression combines the shortcode tags in the regular expression
     * in a regex class.
     *
     * The regular expression contains 6 different sub matches to help with parsing.
     *
     * 1 - An extra [ to allow for escaping shortcodes with double [[]]
     * 2 - The shortcode name
     * 3 - The shortcode argument list
     * 4 - The self closing /
     * 5 - The content of a shortcode when it wraps some content.
     * 6 - An extra ] to allow for escaping shortcodes with double [[]]
     *
     * @param array $tagnames Optional. List of shortcodes to find. Defaults to all registered shortcodes.
     * @return string The shortcode search regular expression
     * @global array $this ->shortcodes
     *
     * @since 2.5.0
     * @since 4.4.0 Added the `$tagnames` parameter.
     *
     */
    protected function get_regex($tagnames = null)
    {


        if (empty($tagnames)) {
            $tagnames = array_keys($this->shortcodes);
        }
        $tagregexp = join('|', array_map('preg_quote', $tagnames));

// WARNING! Do not change this regex without changing $this->do_tag() and $this->strip_tag()
// Also, see shortcode_unautop() and shortcode.js.
        return
            '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            . '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            . '(?:'
            . '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            . '[^\\]\\/]*'               // Not a closing bracket or forward slash
            . ')*?'
            . ')'
            . '(?:'
            . '(\\/)'                        // 4: Self closing tag ...
            . '\\]'                          // ... and closing bracket
            . '|'
            . '\\]'                          // Closing bracket
            . '(?:'
            . '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            . '[^\\[]*+'             // Not an opening bracket
            . '(?:'
            . '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            . '[^\\[]*+'         // Not an opening bracket
            . ')*+'
            . ')'
            . '\\[\\/\\2\\]'             // Closing shortcode tag
            . ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }

    /**
     * Regular Expression callable for do_shortcode() for calling shortcode hook.
     * @param array $m Regular expression match array
     * @return string|false False on failure.
     * @global array $this ->shortcodes
     *
     * @see $this->get_regex for details of the match array contents.
     *
     * @since 2.5.0
     * @access private
     *
     */
    protected function do_tag($m)
    {


// allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = $this->parse_atts($m[3]);

        if (!is_callable($this->shortcodes[$tag])) {
            /* translators: %s: shortcode tag */
            $message = sprintf(('Attempting to parse a shortcode without a valid callback: %s'), $tag);
            throw new \yii\base\InvalidArgumentException($message);
            return $m[0];
        }

        $content = isset($m[5]) ? $m[5] : null;

        $output = $m[1] . call_user_func($this->shortcodes[$tag], $attr, $content, $tag) . $m[6];

        /**
         * Filters the output created by a shortcode callback.
         *
         * @param string $output Shortcode output.
         * @param string $tag Shortcode name.
         * @param array $attr Shortcode attributes array,
         * @param array $m Regular expression match array.
         * @since 4.7.0
         *
         */
        return $output;
    }

    /**
     * Search only inside HTML elements for shortcodes and process them.
     *
     * Any [ or ] characters remaining inside elements will be HTML encoded
     * to prevent interference with shortcodes that are outside the elements.
     * Assumes $content processed by KSES already.  Users with unfiltered_html
     * capability may get unexpected output if angle braces are nested in tags.
     *
     * @param string $content Content to search for shortcodes
     * @param bool $ignore_html When true, all square braces inside elements will be encoded.
     * @param array $tagnames List of shortcodes to find.
     * @return string Content with shortcodes filtered out.
     * @since 4.2.3
     *
     */
    protected function do_in_html_tags($content, $ignore_html, $tagnames)
    {
// Normalize entities in unfiltered HTML before adding placeholders.
        $trans = array('&#91;' => '&#091;', '&#93;' => '&#093;');
        $content = strtr($content, $trans);
        $trans = array('[' => '&#91;', ']' => '&#93;');

        $pattern = $this->get_regex($tagnames);
        $textarr = html::html_split($content);

        foreach ($textarr as &$element) {
            if ('' == $element || '<' !== $element[0]) {
                continue;
            }

            $noopen = false === strpos($element, '[');
            $noclose = false === strpos($element, ']');
            if ($noopen || $noclose) {
// This element does not contain shortcodes.
                if ($noopen xor $noclose) {
// Need to encode stray [ or ] chars.
                    $element = strtr($element, $trans);
                }
                continue;
            }

            if ($ignore_html || '<!--' === substr($element, 0, 4) || '<![CDATA[' === substr($element, 0, 9)) {
// Encode all [ and ] chars.
                $element = strtr($element, $trans);
                continue;
            }

            $attributes = html::html_attr_parse($element);
            if (false === $attributes) {
// Some plugins are doing things like [name] <[email]>.
                if (1 === preg_match('%^<\s*\[\[?[^\[\]]+\]%', $element)) {
                    $element = preg_replace_callback("/$pattern/", [$this, 'do_tag'], $element);
                }

// Looks like we found some crazy unfiltered HTML.  Skipping it for sanity.
                $element = strtr($element, $trans);
                continue;
            }

// Get element name
            $front = array_shift($attributes);
            $back = array_pop($attributes);
            $matches = array();
            preg_match('%[a-zA-Z0-9]+%', $front, $matches);
            $elname = $matches[0];

// Look for shortcodes in each attribute separately.
            foreach ($attributes as &$attr) {
                $open = strpos($attr, '[');
                $close = strpos($attr, ']');
                if (false === $open || false === $close) {
                    continue; // Go to next attribute.  Square braces will be escaped at end of loop.
                }
                $double = strpos($attr, '"');
                $single = strpos($attr, "'");
                if ((false === $single || $open < $single) && (false === $double || $open < $double)) {
// $attr like '[shortcode]' or 'name = [shortcode]' implies unfiltered_html.
// In this specific situation we assume KSES did not run because the input
// was written by an administrator, so we should avoid changing the output
// and we do not need to run KSES here.
                    $attr = preg_replace_callback("/$pattern/", [$this, 'do_tag'], $attr);
                } else {
// $attr like 'name = "[shortcode]"' or "name = '[shortcode]'"
// We do not know if $content was unfiltered. Assume KSES ran before shortcodes.
                    $count = 0;
                    $new_attr = preg_replace_callback("/$pattern/", [$this, 'do_tag'], $attr, -1, $count);
                    if ($count > 0) {
// Sanitize the shortcode output using KSES.
                        $new_attr = html::html_one_attr($new_attr, $elname);
                        if ('' !== trim($new_attr)) {
// The shortcode is safe to use now.
                            $attr = $new_attr;
                        }
                    }
                }
            }
            $element = $front . implode('', $attributes) . $back;

// Now encode any remaining [ or ] chars.
            $element = strtr($element, $trans);
        }

        $content = implode('', $textarr);

        return $content;
    }

    /**
     * Remove placeholders added by $this->do_in_html_tags().
     *
     * @param string $content Content to search for placeholders.
     * @return string Content with placeholders removed.
     * @since 4.2.3
     *
     */
    function unescape_invalids($content)
    {
// Clean up entire string, avoids re-parsing HTML.
        $trans = array('&#91;' => '[', '&#93;' => ']');
        $content = strtr($content, $trans);

        return $content;
    }

    /**
     * Retrieve the shortcode attributes regex.
     *
     * @return string The shortcode attribute regular expression
     * @since 4.4.0
     *
     */
    protected function get_atts_regex()
    {
        return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
    }

    /**
     * Retrieve all attributes from the shortcodes tag.
     *
     * The attributes list has the attribute name as the key and the value of the
     * attribute as the value in the key/value pair. This allows for easier
     * retrieval of the attributes, since all attributes have to be known.
     *
     * @param string $text
     * @return array|string List of attribute values.
     *                      Returns empty array if trim( $text ) == '""'.
     *                      Returns empty string if trim( $text ) == ''.
     *                      All other matches are checked for not empty().
     * @since 2.5.0
     *
     */
    protected function parse_atts($text)
    {
        $atts = array();
        $pattern = $this->get_atts_regex();
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) && strlen($m[7]))
                    $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]))
                    $atts[] = stripcslashes($m[8]);
            }

// Reject any unclosed HTML elements
            foreach ($atts as &$value) {
                if (false !== strpos($value, '<')) {
                    if (1 !== preg_match('/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value)) {
                        $value = '';
                    }
                }
            }
        } else {
            $atts = ltrim($text);
        }
        return $atts;
    }

    /**
     * Combine user attributes with known attributes and fill in defaults when needed.
     *
     * The pairs should be considered to be all of the attributes which are
     * supported by the caller and given as a list. The returned attributes will
     * only contain the attributes in the $pairs list.
     *
     * If the $atts list has unsupported attributes, then they will be ignored and
     * removed from the final returned list.
     *
     * @param array $pairs Entire list of supported attributes and their defaults.
     * @param array $atts User defined attributes in shortcode tag.
     * @param string $shortcode Optional. The name of the shortcode, provided for context to enable filtering
     * @return array Combined and filtered attribute list.
     * @since 2.5.0
     *
     */
    static function atts($pairs, $atts, $shortcode = '')
    {
        $atts = (array)$atts;
        $out = array();
        foreach ($pairs as $name => $default) {
            if (array_key_exists($name, $atts))
                $out[$name] = $atts[$name];
            else
                $out[$name] = $default;
        }
        return $out;
    }

    /**
     * Remove all shortcode tags from the given content.
     *
     * @param string $content Content to remove shortcode tags.
     * @return string Content without shortcode tags.
     * @since 2.5.0
     *
     * @global array $this ->shortcodes
     *
     */
    function strip($content)
    {


        if (false === strpos($content, '[')) {
            return $content;
        }

        if (empty($this->shortcodes) || !is_array($this->shortcodes))
            return $content;

// Find all registered tag names in $content.
        preg_match_all('@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches);

        $tags_to_remove = array_keys($this->shortcodes);

        /**
         * Filters the list of shortcode tags to remove from the content.
         *
         * @param array $tag_array Array of shortcode tags to remove.
         * @param string $content Content shortcodes are being removed from.
         * @since 4.7.0
         *
         */
        $tagnames = array_intersect($tags_to_remove, $matches[1]);

        if (empty($tagnames)) {
            return $content;
        }

        $content = $this->do_in_html_tags($content, true, $tagnames);

        $pattern = $this->get_regex($tagnames);
        $content = preg_replace_callback("/$pattern/", '$this->strip_tag', $content);

// Always restore square braces so we don't break things like <!--[if IE ]>
        $content = $this->unescape_invalids($content);

        return $content;
    }

    /**
     * Strips a shortcode tag based on RegEx matches against post content.
     *
     * @param array $m RegEx matches against post content.
     * @return string|false The content stripped of the tag, otherwise false.
     * @since 3.3.0
     *
     */
    function strip_tag($m)
    {
// allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        return $m[1] . $m[6];
    }

}
