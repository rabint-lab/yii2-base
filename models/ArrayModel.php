<?php

namespace rabint\models;

abstract class ArrayModel {

    static $default = 0;

    protected static function attributeLabels(){
        return [];
    }

    protected static function data() {
//        $CalledClass = get_called_class();
        $data = static::$_data;
        foreach ($data as &$d) {
            $d = (object) $d;
        }
        return $data;
    }

    public static function findAll() {
        $CalledClass = get_called_class();
        return $CalledClass::data();
    }

    public static function one() {
        $CalledClass = get_called_class();
        $data = $CalledClass::findAll();
        return $data[$default];
    }

    public static function all() {
        $CalledClass = get_called_class();
        return $CalledClass::findAll();
    }

    public static function find() {
        $CalledClass = get_called_class();
        return new $CalledClass;
    }

    public function asArray() {
        return $this;
    }

    public function where() {
        return $this;
    }

    public function select($args = '') {
        $newData = [];
        if (!is_array($args)) {
            $args = explode(',', $args);
        }
        foreach (static::$_data as $key => $data) {
            $data = (array) $data;
            foreach ($args as $arg) {
                if (strpos($arg, 'as')) {
                    list($from, $to) = explode('as', $arg);
                    $from = trim($from);
                    $to = trim($to);
                } else {
                    $from = $to = trim($arg);
                }
                $newData[$key][$to] = $data[$from];
            }
            $newData[$key] = (object) $newData[$key];
        }
        static::$_data = $newData;
        return $this;
    }

    public function andWhere() {
        return $this;
    }

    public function orWhere() {
        return $this;
    }

    public function limit() {
        return $this;
    }

    public function offset() {
        return $this;
    }

}
