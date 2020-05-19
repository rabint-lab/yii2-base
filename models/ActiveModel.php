<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace rabint\models;

/**
 * Description of ActiveFromModel
 *
 * @author mojtaba
 */
class ActiveModel extends \yii\base\Model {

    var $isNewRecord =true;
    var $rules;
    var $labels;
    var $attributes;

    /**
     * @inheritdoc
     */
    public function __get($name) {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        parent::__get($name);
    }

    public function __set($name, $value) {
        if (isset($this->attributes[$name])) {
            $this->attributes[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    public function rules() {
        return $this->rules;
    }

    public function attributeLabels() {
        return $this->labels;
    }

    function melliCodeValidator($attribute, $params) {
        $code = $this->$attribute;
        if (!preg_match('/^[0-9]{10}$/', $code))
            $this->addError($attribute, \Yii::t('rabint', 'کد ملی  نا معتبر است.'));
        for ($i = 0; $i < 10; $i++)
            if (preg_match('/^' . $i . '{10}$/', $code))
                $this->addError($attribute, \Yii::t('rabint', 'کد ملی  نا معتبر است.'));
        for ($i = 0, $sum = 0; $i < 9; $i++)
            $sum += ((10 - $i) * intval(substr($code, $i, 1)));
        $ret = $sum % 11;
        $parity = intval(substr($code, 9, 1));
        if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity))
            return true;
        $this->addError($attribute, \Yii::t('rabint', 'کد ملی  نا معتبر است.'));
        return false;
    }

}
