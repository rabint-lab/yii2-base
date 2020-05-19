<?php

namespace rabint\behaviors;

use yii\base\Behavior;
use yii\base\Exception;
use common\models\base\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\validators\UniqueValidator;

/**
 * Class Slug
 * @package baibaratsky\yii\behaviors\model
 *
 * @property ActiveRecord $owner
 */
class Slug extends Behavior
{

    public $sourceAttributeName = 'name';
    public $slugAttributeName = 'slug';
    public $replacement = '-';
    public $lowercase = true;
    public $unique = true;
    public $prefix = "";
    public $postfix = "";
    public $special_chars = [
        ",",
        "،",
        "?",
        "[",
        "]",
        "/",
        "\\",
        "=",
        "<",
        ">",
        ":",
        ";",
        ",",
        "'",
        "\"",
        "&",
        "$",
        "#",
        "*",
        "(",
        ")",
        "|",
        "~",
        "`",
        "!",
        "{",
        "}",
    ];

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'generateSlug',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'generateSlug'
        ];
    }

    public function generateSlug()
    {
        $scenarios = $this->owner->scenarios();
        $fields = $scenarios[$this->owner->scenario];
        if (!in_array($this->slugAttributeName, $fields)) {
            return;
        }
        /* =================================================================== */
        $src = '';
        if (is_array($this->sourceAttributeName)) {
            $src = [];
            foreach ($this->sourceAttributeName as $attr) {
                $src[] = $this->owner->{$attr};
            }
            $src = implode('-', $src);
        } else {
            $src = $this->owner->{$this->sourceAttributeName};
        }
        if (empty($this->owner->{$this->slugAttributeName}) && !empty($src)) {
            $slug = $this->prefix . $this->slugify($src) . $this->postfix;
            $this->owner->{$this->slugAttributeName} = $slug;

            if ($this->unique) {
                $suffix = 1;
                while (!$this->uniqueCheck()) {
                    $this->owner->{$this->slugAttributeName} = $slug . $this->replacement . ++$suffix;
                }
            }
        } else {
            if (!empty($this->owner->{$this->slugAttributeName})) {
                $slug = $this->prefix . $this->slugify($this->owner->{$this->slugAttributeName}) . $this->postfix;
                $this->owner->{$this->slugAttributeName} = $slug;

                if ($this->unique) {
                    $suffix = 1;
                    while (!$this->uniqueCheck()) {
                        $this->owner->{$this->slugAttributeName} = $slug . $this->replacement . ++$suffix;
                    }
                }
            }
        }
    }

    public function uniqueCheck()
    {
        if ($this->owner instanceof ActiveRecordInterface) {
            /** @var ActiveRecord $model */
            $model = clone $this->owner;
            $uniqueValidator = new UniqueValidator;
            $uniqueValidator->validateAttribute($model, $this->slugAttributeName);
            return !$model->hasErrors($this->slugAttributeName);
        }

        throw new Exception('Can\'t check if the slug is unique.');
    }

    public function slugify($string, $separator = '-')
    {
        $special_chars = $this->special_chars + [chr(0)];
        $string = preg_replace("#\x{00a0}#siu", ' ', $string);
        $string = str_replace($special_chars, '', $string);
        $string = str_replace(array('%20', '+'), '-', $string);
        $string = preg_replace('/[\r\n\t -]+/', '-', $string);
        $string = trim($string);
        $string = str_replace(' ', '-', $string);
        $string = str_replace('"', '-', $string);
        $string = str_replace("'", '-', $string);
        $string = str_replace("\n", '-', $string);
        $string = str_replace("\t", '-', $string);
        $string = str_replace("\t", '-', $string);
        $string = preg_replace('/\p{C}+/u', "", $string);
        return $string;
    }

}
