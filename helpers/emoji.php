<?php

namespace rabint\helpers;

use Yii;
use Emojione\Client;
use Emojione\Ruleset;
/**
 * rabint\helpers\emoji::shortnameToImage('hello:smile:');// hello <img class="emojione" alt="😄" title=":smile:" src="https://cdn.jsdelivr.net/emojione/assets/3.1/png/32/1f604.png">
 * rabint\helpers\emoji::toShort('😄'); // :smile:
 * rabint\helpers\emoji::unicodeToImage('😄'); // <img class="emojione" alt="😄" title=":smile:" src="https://cdn.jsdelivr.net/emojione/assets/3.1/png/32/1f604.png">
 * rabint\helpers\emoji::toImage(':smile:'); // <img class="emojione" alt="😄" title=":smile:" src="https://cdn.jsdelivr.net/emojione/assets/3.1/png/32/1f604.png">
 * rabint\helpers\emoji::shortnameToUnicode(':smile:'); // 😄
 */
class emoji {

    private static $client = false;
    private static $paramKeys = ['ascii', 'shortcodes', 'unicodeAlt', 'emojiSize', 'spriteSize', 'sprites', 'imagePathPNG'];

    private static function getClient() {
        if (self::$client == false) {
            $object = (new Client(new Ruleset()));

            if (isset(Yii::$app->params['yii2Emoji']) && null !== ($emojiParams = Yii::$app->params['yii2Emoji'])) {
                foreach ($emojiParams as $key => $param) {
                    if (in_array($key, self::$paramKeys) && !empty($param)) {
                        $object->$key = $param;
                    }
                }

                //  重新配置imagePathPNG参数
                $imagePathPNGArr = array_filter(explode('/', $object->imagePathPNG));
                if ($imagePathPNGArr[count($imagePathPNGArr)] != $object->emojiSize) {
                    $object->imagePathPNG = rtrim($object->imagePathPNG, end($imagePathPNGArr) . '/') . '/' . $object->emojiSize . '/';
                };
            }

            self::$client = $object;
        }

        return self::$client;
    }

    public static function toShort($string) {
        return self::getClient()->toShort($string);
    }

    public static function unicodeToImage($string) {
        return self::getClient()->unicodeToImage($string);
    }

    public static function toImage($string) {
        return self::getClient()->toImage($string);
    }

    public static function shortnameToUnicode($string) {
        return self::getClient()->shortnameToUnicode($string);
    }

    public static function shortnameToImage($string) {
        return self::getClient()->shortnameToImage($string);
    }

}
