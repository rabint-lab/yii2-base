<?php

namespace rabint\helpers;

use yii\helpers\Html;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class form {

    public static function attachment($attribute, $value, $label, $hint, $options = []) {
        $label = '<div class="form-group"><label>' . $label . '</label>';
        $hint = '<p class="help-block">' . $hint . '</p></div>';
        $options = array_merge([
//            'returnType' => 'path'
            'maxFileSize' => 100 * 1024 * 1024,
            'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(gif|mp4|jpe?g|png)$/i')
                ], $options);
        $echo = $label . \rabint\helpers\widget::uploaderStatic($attribute, $value, $options) . $hint;
        return $echo;
    }

    public static function wysiwyg($attribute, $value, $label, $hint, $options = []) {
        $label = '<div class="form-group"><label>' . $label . '</label>';
        $hint = '<p class="help-block">' . $hint . '</p></div>';
        $options = array_merge([
                ], $options);
        $echo = $label . \rabint\helpers\widget::wysiwygStatic($attribute, $value, $options) . $hint;
        return $echo;
    }

    public static function textarea($attribute, $value, $label, $hint, $options = []) {
        $label = '<div class="form-group"><label>' . $label . '</label>';
        $hint = '<p class="help-block">' . $hint . '</p></div>';
        $options = array_merge([
            'class' => 'form-control',
            'rows' => '8'
                ], $options);
        $echo = $label . Html::textarea($attribute, $value, $options) . $hint;
        return $echo;
    }

    public static function select($attribute, $value, $items, $label, $hint, $options = []) {
        $label = '<div class="form-group"><label>' . $label . '</label>';
        $hint = '<p class="help-block">' . $hint . '</p></div>';
        $options = array_merge([
            'class' => 'form-control',
            'prompt' => ''
                ], $options);
        $echo = $label . Html::dropDownList($attribute, $value, $items, $options) . $hint;
        return $echo;
    }

    public static function checkboxlist($attribute, $value, $items, $label, $hint, $options = []) {
        $label = '<div class="form-group"><label>' . $label . '</label>';
        $hint = '<p class="help-block">' . $hint . '</p></div>';
        $options = array_merge([
            'class' => 'form-control',
            'prompt' => ''
                ], $options);
        $echo = $label . Html::checkboxList($attribute, $value, $items, $options) . $hint;
        return $echo;
    }

    public static function radiolist($attribute, $value, $items, $label, $hint, $options = []) {
        $label = '<div class="form-group"><label>' . $label . '</label>';
        $hint = '<p class="help-block">' . $hint . '</p></div>';
        $options = array_merge([
            'class' => 'form-control',
            'prompt' => ''
                ], $options);
        $echo = $label . Html::radiolist($attribute, $value, $items, $options) . $hint;
        return $echo;
    }

    public static function checkbox($attribute, $value, $label, $hint, $options = []) {
        $label = '<div class="form-group"><label>' . $label . '</label>';
        $hint = '<p class="help-block">' . $hint . '</p></div>';
        $options = array_merge([
            'class' => 'form-control',
            'prompt' => ''
                ], $options);
        $echo = $label . Html::checkbox($attribute, $value, $options) . $hint;
        return $echo;
    }

    public static function radio($attribute, $value, $label, $hint, $options = []) {
        $label = '<div class="form-group"><label>' . $label . '</label>';
        $hint = '<p class="help-block">' . $hint . '</p></div>';
        $options = array_merge([
            'class' => 'form-control',
            'prompt' => ''
                ], $options);
        $echo = $label . Html::radio($attribute, $value, $options) . $hint;
        return $echo;
    }

    public static function hidden($attribute, $value, $options = []) {
        $options = array_merge([], $options);
        $echo = Html::hiddenInput($attribute, $value, $options);
        return $echo;
    }

    public static function text($attribute, $value, $label, $hint, $options = []) {
        return static::input('text', $attribute, $value, $label, $hint, $options);
    }

    public static function number($attribute, $value, $label, $hint, $options = []) {
        return static::input('number', $attribute, $value, $label, $hint, $options);
    }

    public static function password($attribute, $value, $label, $hint, $options = []) {
        return static::input('password', $attribute, $value, $label, $hint, $options);
    }

    protected static function input($type = "text", $attribute, $value, $label, $hint, $options = []) {
        $label = '<div class="form-group"><label>' . $label . '</label>';
        $hint = '<p class="help-block">' . $hint . '</p></div>';
        $options = array_merge([
            'class' => 'form-control',
            'prompt' => ''
                ], $options);
        $echo = $label . Html::input($type, $attribute, $value, $options) . $hint;
        return $echo;
    }

}
