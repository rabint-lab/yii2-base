<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 11/12/18
 * Time: 1:18 PM
 */

namespace rabint\helpers;

use rabint\attachment\models\Attachment;
use rabint\attachment\models\VirtualAttachment;
use rabint\widgets\GalleryButton;
use rabint\widgets\VideoJs\VideoJsWidget;
use Yii;
use yii\helpers\Html as baseHtml;

class html
{

    /**
     * create html of options for select tag.
     * @param $modelPath
     * @param $attribute
     * @param null $value
     * @param bool $placeholder
     * @return string
     */
    public static function getFieldOption($modelPath, $attribute, $value = null, $placeholder = false)
    {

        $modelname = strpos($modelPath, "/") ? substr($modelPath, strrpos($modelPath, '/') + 1) : $modelPath;
        $CI = &get_instance();
        $CI->load->model($modelPath);
        $res = $CI->$modelname->attributes()[$attribute];
        $out = '';
        if ($placeholder) {
            if ($placeholder === true) {
                $placeholder = '';
            }
            $out .= "<option value=\"\">$placeholder</option>";
        }
        foreach ($res['options'] as $enName => $data) {
            $selected = ($value !== null && $value == $enName) ? "selected" : "";
            $out .= "<option value=\"{$enName}\" {$selected}>{$data['title']}</option>";
        }
        return $out;
    }

    /**
     * create checked attr for and value attr for checkbox input html tag
     * @param $attribute
     * @param int $value
     * @return string
     */
    public static function checkedValue($attribute, $value = 1)
    {
        if ($attribute == $value) {
            return " value=\"$value\" checked=\"checked\"";
        }
        return " value=\"$value\"";
    }


    public static function html_split($input)
    {
        return preg_split(self::get_html_split_regex(), $input, -1, PREG_SPLIT_DELIM_CAPTURE);
    }

    public static function html_attr_parse($element)
    {
        $valid = preg_match('%^(<\s*)(/\s*)?([a-zA-Z0-9]+\s*)([^>]*)(>?)$%', $element, $matches);
        if (1 !== $valid) {
            return false;
        }

        $begin = $matches[1];
        $slash = $matches[2];
        $elname = $matches[3];
        $attr = $matches[4];
        $end = $matches[5];

        if ('' !== $slash) {
            // Closing elements do not get parsed.
            return false;
        }

        // Is there a closing XHTML slash at the end of the attributes?
        if (1 === preg_match('%\s*/\s*$%', $attr, $matches)) {
            $xhtml_slash = $matches[0];
            $attr = substr($attr, 0, -strlen($xhtml_slash));
        } else {
            $xhtml_slash = '';
        }

        // Split it
        $attrarr = self::html_hair_parse($attr);
        if (false === $attrarr) {
            return false;
        }

        // Make sure all input is returned by adding front and back matter.
        array_unshift($attrarr, $begin . $slash . $elname);
        array_push($attrarr, $xhtml_slash . $end);

        return $attrarr;
    }

    public static function html_hair_parse($attr)
    {
        if ('' === $attr) {
            return array();
        }

        $regex = '(?:'
            . '[-a-zA-Z:]+'   // Attribute name.
            . '|'
            . '\[\[?[^\[\]]+\]\]?' // Shortcode in the name position implies unfiltered_html.
            . ')'
            . '(?:'               // Attribute value.
            . '\s*=\s*'       // All values begin with '='
            . '(?:'
            . '"[^"]*"'   // Double-quoted
            . '|'
            . "'[^']*'"   // Single-quoted
            . '|'
            . '[^\s"\']+' // Non-quoted
            . '(?:\s|$)'  // Must have a space
            . ')'
            . '|'
            . '(?:\s|$)'      // If attribute has no value, space is required.
            . ')'
            . '\s*';              // Trailing space is optional except as mentioned above.
        // Although it is possible to reduce this procedure to a single regexp,
        // we must run that regexp twice to get exactly the expected result.

        $validation = "%^($regex)+$%";
        $extraction = "%$regex%";

        if (1 === preg_match($validation, $attr)) {
            preg_match_all($extraction, $attr, $attrarr);
            return $attrarr[0];
        } else {
            return false;
        }
    }

    public static function get_html_split_regex()
    {
        static $regex;

        if (!isset($regex)) {
            $comments = '!'           // Start of comment, after the <.
                . '(?:'         // Unroll the loop: Consume everything until --> is found.
                . '-(?!->)' // Dash not followed by end of comment.
                . '[^\-]*+' // Consume non-dashes.
                . ')*+'         // Loop possessively.
                . '(?:-->)?';   // End of comment. If not found, match all input.

            $cdata = '!\[CDATA\['  // Start of comment, after the <.
                . '[^\]]*+'     // Consume non-].
                . '(?:'         // Unroll the loop: Consume everything until ]]> is found.
                . '](?!]>)' // One ] not followed by end of comment.
                . '[^\]]*+' // Consume non-].
                . ')*+'         // Loop possessively.
                . '(?:]]>)?';   // End of comment. If not found, match all input.

            $escaped = '(?='           // Is the element escaped?
                . '!--'
                . '|'
                . '!\[CDATA\['
                . ')'
                . '(?(?=!-)'      // If yes, which type?
                . $comments
                . '|'
                . $cdata
                . ')';

            $regex = '/('              // Capture the entire match.
                . '<'           // Find start of element.
                . '(?'          // Conditional expression follows.
                . $escaped  // Find end of escaped element.
                . '|'           // ... else ...
                . '[^>]*>?' // Find end of normal element.
                . ')'
                . ')/';
        }

        return $regex;
    }

    public static function html_one_attr($string, $element)
    {
        $uris = array(
            'xmlns',
            'profile',
            'href',
            'src',
            'cite',
            'classid',
            'codebase',
            'data',
            'usemap',
            'longdesc',
            'action'
        );
//        $allowed_html = wp_kses_allowed_html('post');
//        $allowed_protocols = wp_allowed_protocols();
//        $string = wp_kses_no_null($string, array('slash_zero' => 'keep'));
        // Preserve leading and trailing whitespace.
        $matches = array();
        preg_match('/^\s*/', $string, $matches);
        $lead = $matches[0];
        preg_match('/\s*$/', $string, $matches);
        $trail = $matches[0];
        if (empty($trail)) {
            $string = substr($string, strlen($lead));
        } else {
            $string = substr($string, strlen($lead), -strlen($trail));
        }

        // Parse attribute name and value from input.
        $split = preg_split('/\s*=\s*/', $string, 2);
        $name = $split[0];
        if (count($split) == 2) {
            $value = $split[1];

            // Remove quotes surrounding $value.
            // Also guarantee correct quoting in $string for this one attribute.
            if ('' == $value) {
                $quote = '';
            } else {
                $quote = $value[0];
            }
            if ('"' == $quote || "'" == $quote) {
                if (substr($value, -1) != $quote) {
                    return '';
                }
                $value = substr($value, 1, -1);
            } else {
                $quote = '"';
            }

            // Sanitize quotes, angle braces, and entities.
            $value = esc_attr($value);

//            // Sanitize URI values.
//            if (in_array(strtolower($name), $uris)) {
//                $value = wp_kses_bad_protocol($value, $allowed_protocols);
//            }

            $string = "$name=$quote$value$quote";
            $vless = 'n';
        } else {
            $value = '';
            $vless = 'y';
        }

        // Sanitize attribute by name.
//        wp_kses_attr_check($name, $value, $string, $vless, $element, $allowed_html);
        // Restore whitespace.
        return $lead . $string . $trail;
    }

    public static function tagInputSanitise($tagArray, $seperateChars = ",")
    {
        $tag_ids = [];
        foreach ($tagArray as $keys => $tags) {
            $tags = str_replace("،", ",", $tags);
            $tags = trim($tags, $seperateChars . " ");
            if (strpos($tags, $seperateChars)) {
                $tagList = explode($seperateChars, $tags);
                $tag_ids = array_merge($tag_ids, $tagList);
            } else {
                $tag_ids[] = $tags;
            }
        }
        $tagArray = array_map(function ($row) use ($seperateChars) {
            $row = trim($row, $seperateChars . " ");
            return \yii\helpers\HtmlPurifier::process($row);
        }, $tag_ids);
        return $tagArray;
    }

    /* ------------------------------------------------------ */

    public static function imgNotFindUrl($size = [120, 68])
    {
        if ($size[0] > 300) {
            return Yii::getAlias('/img/default/noPictureMedium.jpg');
        }
        return Yii::getAlias('/img/default/noPictureTiny.jpg');
    }

    public static function avatarNotFindUrl($size = [120, 68])
    {
        return Yii::getAlias('/img/default/noAvatar.jpg');
    }

    /* ------------------------------------------------------ */

    /**
     *
     * @param Attachment $attachment
     * @param type $options
     * @param type $size
     * @return string
     */
    public static function imageTag($attachment, $size = [120, 68], $options = [], $showIfEmpty = TRUE)
    {
        $class = isset($options['class']) ? $options['class'] : 'attachment attachment-img';
        $alt = isset($options['alt']) ? $options['alt'] : ($attachment->titleTag ?? "");
//        if (empty($size)) {
//            $sizeArray = $options + ['style' => 'max-width:100%;'];
//        } else {
////            $sizeArray = $options + ['width' => $size[0], 'height' => $size[1], 'style' => 'max-width:100%;max-width:100%;height: auto'];
//        }
        if (empty($attachment)) {
            if ($showIfEmpty) {
                return baseHtml::img(static::imgNotFindUrl($size), ['class' => $class, 'alt' => $alt, 'title' => $alt]);
            } else {
                return '';
            }
        }
        if (is_string($attachment)) {
            return baseHtml::img($attachment, ['class' => $class]);
        }
        return baseHtml::img($attachment->getUrl($size), ['class' => $class, 'alt' => $alt, 'title' => $alt]);
    }

    /**
     *
     * @param Attachment $attachment
     * @param type $options
     * @param type $size
     * @return string
     */
    public static function audioTag($attachment, $options = [], $thumbnail = '')
    {
        return '<audio controls poster="' . $thumbnail . '">
    <source src="' . $attachment->url . '" type="' . $attachment['mime'] . '">'
            . \Yii::t('rabint', 'پخش فایل ضبط شده ممکن نیست')
            . '</audio>';
    }

    /**
     *
     * @param Attachment $attachment
     * @param type $options
     * @param type $size
     * @return string
     */
    public static function videoTag($attachment, $options = [], $thumbnail = '')
    {
        if (empty($attachment)) {
            return '';
        }
        return '<video controls poster="' . $thumbnail . '">
    <source src="' . $attachment->url . '" type="' . $attachment['mime'] . '">'
            . \Yii::t('rabint', 'پخش فایل ضبط شده ممکن نیست')
            . '</video>';
    }

    /**
     *
     * @param Attachment $attachment
     * @param type $options
     * @param type $size
     * @return string
     */
    public static function videoJsTag($attachment, $options = [], $thumbnail = '')
    {

        return VideoJsWidget::widget([
            'options' => [
                'class' => 'video-js vjs-default-skin vjs-big-play-centered vjs-16-9',
                'poster' => $thumbnail,
                'controls' => true,
                'preload' => 'auto',
//                        'width' => '720',
//                        'height' => '405',
            ],
            'tags' => [
                'source' => [
                    ['src' => $attachment->url, 'type' => $attachment->mime],
//                ['src' => 'http://vjs.zencdn.net/v/oceans.webm', 'type' => 'video/webm']
                ],
//            'track' => [
//                ['kind' => 'captions', 'src' => 'http://vjs.zencdn.net/vtt/captions.vtt', 'srclang' => 'en', 'label' => 'English']
//            ]
            ]
        ]);

//        return \rabint\aqr\player\widget\AqrPlayer::widget([
//                    'ulr' => $attachment->url,
//                    'mime' => $attachment['mime'],
//                    'id' => $attachment->id,
//                    'title' => $attachment->title,
//                    'thumbnail' => $thumbnail,
//        ]);
    }

    /**
     *
     * @param Attachment $attachment
     * @param type $options
     * @param type $size
     * @return string
     */
    public static function fileTag($attachment, $options = [])
    {
        return '<a href="' . $attachment->url . '" class="fileAttachment" >'
            . '<i class="fa fa-download"></i>'
            . '</a>';
    }

    public static function galleryTag($attachments, $size = [120, 68], $options = [],$widgetOptions=[], $activeTitle = false)
    {
        if (!is_array($attachments)) {
            $attachments = [$attachments];
        }
        $return = '';
        $items = [];
        foreach ($attachments as $attachment) {
            if (empty($attachment)) {
                continue;
            }
//            if (empty($size)) {
//                $sizeArray = $options + ['style' => 'max-width:100%;'];
//            } else {
//                $sizeArray = $options + ['width' => $size[0], 'height' => $size[1], 'style' => 'max-width:100%;'];
//            }
            $options = [];
            if ($activeTitle) {
                $options['title'] = $attachment->titleTag;
            }
            $items[] = [
                'url' => $attachment->getUrl(),
                'src' => $attachment->getUrl($size),
                'options' => $options,
            ];

//            $class = isset($options['class']) ? $options['class'] : 'attachment attachment-img';
//            $return.= baseHtml::img($attachment->getUrl($size), ['class' => $class] + $sizeArray);
        }
        return \dosamigos\gallery\Gallery::widget(['items' => $items,'options' => $widgetOptions,]);
    }

    public static function galleryBtn($attachments, $size = [120, 68], $options = [], $activeTitle = false)
    {
        if (!is_array($attachments)) {
            $attachments = [$attachments];
        }
        $return = '';
        $items = [];
        foreach ($attachments as $attachment) {
            if (empty($attachment)) {
                continue;
            }
//            if (empty($size)) {
//                $sizeArray = $options + ['style' => 'max-width:100%;'];
//            } else {
//                $sizeArray = $options + ['width' => $size[0], 'height' => $size[1], 'style' => 'max-width:100%;'];
//            }
            $option = [];
            if ($activeTitle) {
                $option['title'] = $attachment->titleTag;
            }
            $items[] = [
                'url' => $attachment->getUrl(),
                'src' => $attachment->getUrl($size),
                'options' => $option,
            ];

//            $class = isset($options['class']) ? $options['class'] : 'attachment attachment-img';
//            $return.= baseHtml::img($attachment->getUrl($size), ['class' => $class] + $sizeArray);
        }
        $options['items'] =$items;
        return GalleryButton::widget($options);
    }


    /**
     *
     */
    public static function virtualAttachmentTag($url, $options = [], $size = [120, 68])
    {
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $type = file::extToType($ext);
        $attachment = new VirtualAttachment();
        $attachment->path = $url;
        $attachment->title = pathinfo($url, PATHINFO_FILENAME);
        $attachment->name = $attachment->title;
        $attachment->extension = $ext;
        $attachment->type = $type;
        $attachment->protected = false;


        switch ($type) {
            case 'image':
                return self::imageTag($attachment, $size, $options);
                break;
            case 'audio' :
                return self::audioTag($attachment, $options);
                break;
            case 'video' :
                return self::videoTag($attachment, $options);
                break;
            case 'document' :
            case 'spreadsheet' :
            case 'interactive' :
            case 'text' :
            case 'archive' :
            case 'code' :
            default:
                return self::fileTag($attachment, $options);
                break;
        }
    }

    /**
     *
     * @param Attachment $attachment
     * @param type $options
     * @param type $size
     * @return string
     */
    public static function attachmentTag($attachment, $options = [], $size = [120, 68])
    {
        if (empty($attachment)) {
            return self::imageTag($attachment, $size, $options);
        }
        switch ($attachment['type']) {
            case 'image':
                return self::imageTag($attachment, $size, $options);
                break;
            case 'audio' :
                return self::audioTag($attachment, $options);
                break;
            case 'video' :
                return self::videoTag($attachment, $options);
                break;
            case 'document' :
            case 'spreadsheet' :
            case 'interactive' :
            case 'text' :
            case 'archive' :
            case 'code' :
            default:
                return self::fileTag($attachment, $options);
                break;
        }
    }


}