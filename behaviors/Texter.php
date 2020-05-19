<?php

namespace rabint\behaviors;

use yii\base\Behavior;
use yii\base\Exception;
use common\models\base\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\validators\UniqueValidator;

/**
 * Class Texter
 * @property ActiveRecord $owner
 */
class Texter extends Behavior {

    public $tagClass = 'texterTag';
    public $attributeNames = ['content'];
    public $replacements = [
        '#' => '{link}',
        '@' => '{link}',
    ];
    public $functions = [];

//    public function init() {
//        print_r($this->replacements);
//        die();
//        parent::init();
//    }
    public function events() {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'renderText',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'renderText'
        ];
    }

    public function renderText() {
        $scenarios = $this->owner->scenarios();
        $fields = $scenarios[$this->owner->scenario];
        $this->attributeNames = array_intersect($this->attributeNames, $fields);
        if (empty($this->attributeNames)) {
            return;
        }
        /* =================================================================== */

        foreach ($this->attributeNames as $attr) {
            if (!empty($this->owner->{$attr})) {
                $text = $this->owner->{$attr}; //. ' ';
                if (!$this->owner->isNewRecord) {
                    $text = $this->cleanOldTags($text);
                }
                /* =================================================================== */
                foreach ((array) $this->functions as $hash => $function) {
                    if (preg_match_all('/\\' . $hash . '([^\s|^\n|^\r|^\t|^\<]+)/', $text, $matches)) {
                        foreach ($matches[1] as $label) {
                            $text = $function($this->owner, $attr, $label);
                        }
                    }
                }
                if (!$this->owner->isNewRecord) {
                    $text = $this->cleanOldTags($text);
                }
                /* =================================================================== */
                $this->owner->{$attr} = $text; //. ' ';
                foreach ($this->replacements as $hash => $link) {
                    $link = str_replace('%7Blink%7D', '$1', $link);
                    $text = preg_replace('/\\' . $hash . '([^\s|^\n|^\r|^\t|^\<]+)/', '<a class="'.$this->tagClass.'" href="' . $link . '">' . $hash . '$1</a>', $text);
                }
                $this->owner->{$attr} = $text;
            }
        }
    }

    public function cleanOldTags($text) {
        $text = preg_replace('/<a class=\"'.$this->tagClass.'" [^>].*?>(.*?)<\/a>/', '$1', $text);
        return $text;
    }

}
