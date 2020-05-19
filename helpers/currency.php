<?php

namespace rabint\helpers;

use Yii;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class currency
{

    const CURRENCY_IR_TOMAN = 'IR_TOMAN';
    const CURRENCY_IR_RIAL = 'IR_RIAL';
    const CURRENCY_US_DOLAR = 'US_DOLAR';

    public static $MASTER_CURRENCY = 'IR_RIAL';
    public static $CURRENT_CURRENCY = 'IR_RIAL';
    
    public static function setCurrency($currency)
    {
        self::$CURRENT_CURRENCY = $currency;
    }
    public static function title($currency = NULL)
    {
        if ($currency == NULL) {
            $currency = self::$CURRENT_CURRENCY;
        }
        return self::currencies()[$currency]['title'];
    }

    public static function factor($currency = NULL)
    {
        if ($currency == NULL) {
            $currency = self::$CURRENT_CURRENCY;
        }
        return self::currencies()[$currency]['factor'];
    }

    /**
     * @param integer $amount
     * @param string $currency
     * @return string
     */
    public static function format($amount, $currency = NULL, $template = "{amount} {currency}")
    {
        if ($currency == NULL) {
            $currency = self::$CURRENT_CURRENCY;
        }
        $amount *= static::factor($currency);

        $amount = number_format($amount);
        $currency = static::title($currency);

        $template = str_replace("{amount}", $amount, $template);
        $template = str_replace("{currency}", $currency, $template);
        return $template;
    }


    /**
     * @param integer $amount
     * @param string $currency
     * @return float
     */
    public static function numberToCurrency($amount, $currency = NULL)
    {
        if ($currency == NULL) {
            $currency = self::$CURRENT_CURRENCY;
        }
        $amount *= static::factor($currency);
        return $amount;
    }
    
    
     public static function currencies()
    {
        return [
            static::CURRENCY_IR_RIAL => ['factor' => 1, 'title' => \Yii::t('rabint', 'ریال')],
            static::CURRENCY_IR_TOMAN => ['factor' => .1, 'title' => \Yii::t('rabint', 'تومان')],
            static::CURRENCY_US_DOLAR => ['factor' => .00028572, 'title' => \Yii::t('rabint', 'دلار')],
        ];
    }

    public static function getCurrentCurrencyTitle()
    {
        
        return static::currencies()[static::$CURRENT_CURRENCY]['title'];
    }

   
}
