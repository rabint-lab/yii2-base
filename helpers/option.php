<?php

namespace rabint\helpers;

use rabint\option\models\Option as OptionModel;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class option
{
    public static function get($key, $field = '', $firstRow = false, $default = false)
    {
        return OptionModel::get($key, $field, $firstRow, $default);
    }
    public static function appName()
    {
        return static::get('general', 'subject', true, config('app_name'));
    }
    public static function appSlogan()
    {
        return static::get('general', 'slogan', true, config('app_name'));
    }
    public static function appFullname()
    {
        return static::get('general', 'title', true, config('app_name'));
    }
    public static function appDescriptions($default= "")
    {
        return static::get('general', 'desc', true, $default);
    }
}