<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace rabint\components;
use yii\helpers\Url;

class UrlManager extends \yii\web\UrlManager {

    public $absoluteUrl = false;
    private $__hostInfo = null;
    private $__baseUrl = null;

    public function createUrl($params) {
//        if ($absoluteUrl == null) {
//            $absoluteUrl = $this->absoluteUrl;
//        }
        if ($this->absoluteUrl) {
            return $this->createAbsoluteUrl($params);
        }
        return parent::createUrl($params);
    }

    public function createAbsoluteUrl($params, $scheme = null) {
        $scheme = config('base_url.scheme','http');
        $params = (array) $params;
        $url = parent::createUrl($params);
        if (strpos($url, '://') === false) {
            $hostInfo = $this->getHostInfo();
            if (strpos($url, '//') === 0) {
                $url = substr($hostInfo, 0, strpos($hostInfo, '://')) . ':' . $url;
            } else {
                $url = $hostInfo . $url;
            }
        }

        $url = Url::ensureScheme($url, $scheme);
        return $url;
    }

//    public function getBaseUrl() {
//        if ($this->__baseUrl === null) {
//            $this->__baseUrl = config("base_url", parent::getBaseUrl());
//        }
//        return $this->__baseUrl;
//    }

//    public function getHostInfo() {
//        if ($this->__hostInfo === null) {
//            $this->__hostInfo = config("base_url", parent::getBaseUrl());
//            $parsed = parse_url($this->__hostInfo);
//            $this->__hostInfo = $parsed["scheme"] . "://" . $parsed['host'];
//        }
//        return $this->__hostInfo;
//    }

}
