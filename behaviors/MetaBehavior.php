<?php

namespace rabint\behaviors;

/**
 * @property text $meta meta field of parent
 */
class MetaBehavior extends \yii\base\Behavior {

    public $fields;
    public $destinationField;

    public function init() {
        parent::init();
    }

    public function canGetProperty($name, $checkVars = true) {
        if (in_array($name, $this->fields)) {
            return TRUE;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    public function canSetProperty($name, $checkVars = true) {
        if (in_array($name, $this->fields)) {
            return TRUE;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    public function __get($name) {
        if (in_array($name, $this->fields)) {
            return $this->getmetafield($name);
        }
        return parent::__get($name);
    }

    public function __set($name, $value) {
        if (in_array($name, $this->fields)) {
            return $this->setmetafield($name, $value);
        }
        return parent::__set($name, $value);
    }

    public function getmetafield($key) {
        $meta = $this->__getmeta();
        if (isset($meta[$key]))
            return $meta[$key];
        return NULL;
    }

    public function setmetafield($key, $value) {
        $meta = $this->__getmeta();
        $meta[$key] = $value;
        $this->__setmeta($meta);
    }

    private function __getmeta() {
        $meta = $this->destinationField;
        return json_decode($this->owner->$meta, TRUE);
    }

    private function __setmeta($value) {
        $meta = $this->destinationField;
        $this->owner->$meta = json_encode($value);
    }

}
