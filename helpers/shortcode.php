<?php

namespace rabint\helpers;

/**
 * Description of shortcode
 *
 * @author mojtaba
 */
class shortcode extends \yii\base\BaseObject
{

    /**
     * Container for storing shortcode tags and their hook to call for the shortcode
     */
    public $shortcodes = [];

    /**
     * @return \rabint\components\Shortcode
     */
    private static function getShortcodeComponnet()
    {
        static $shortcodesClass = null;
        if ($shortcodesClass == null) {
            $shortcodesClass = new \rabint\components\Shortcode();
            /**
             * init
             */
            $shortcodesClass->add('rabint-video-embed', array(static::class, '_videoIframeShortcode'));
            $shortcodesClass->add('rabint-rmapview', array(static::class, '_rmapviewShortcode'));
        }

        return $shortcodesClass;
    }

    /**
     * @param $content
     * @return string
     */
    static function renderShortcode($content)
    {
        return static::getShortcodeComponnet()->render($content);
    }

    static function addShortcode($name, $function)
    {
        static::getShortcodeComponnet()->add($name, $function);
    }


    static function _rmapviewShortcode($attrs)
    {
        return \voime\GoogleMaps\Map::widget([
            'apiKey' => config('googleMapApiKey', ''),
            'zoom' => 13,
            'center' => [$attrs['latitude'], $attrs['longitude']],
            'width' => '100%',
            'height' => '400px',
            'mapType' => \voime\GoogleMaps\Map::MAP_TYPE_ROADMAP,
        ]);
    }

    static function _videoIframeShortcode($attrs)
    {
        $videoEmbedBaseUrl = uri::to(['/site/embed']);
        return '<div class="rabint_video_embd_iframe"><span style="display: block;padding-top: 57%"></span>' .
            '    <iframe src="' . $videoEmbedBaseUrl . '?rel=' . $attrs['id'] . '" title="" ' .
            '     allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>' .
            '</div>' .
            '<style>.rabint_video_embd_iframe {position: relative;}.rabint_video_embd_iframe iframe {border:none;position: absolute;top: 0;left: 0;width: 100%;height: 100%;}</style>';
    }

}
