<?php

namespace rabint\helpers;

use Yii;
use yii\helpers\Url;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class uri
{

    const RD_HOME = 'home';
    const RD_BACK = 'back';
    const RD_REFERRER = 'referrer';
    const RD_REMEMBER = 'remember';

    public static function home($scheme = true)
    {
        return Url::home($scheme);
    }

    public static function current($scheme = true)
    {
        if ($scheme) {
            return static::to(Yii::$app->request->url, true);
        }
        return Yii::$app->request->url;
    }

    public static function isHome()
    {
        $controller = Yii::$app->controller;
        $default_controller = Yii::$app->defaultRoute;
        return (($controller->id . '/' . $controller->action->id) === $default_controller) ? true : false;
    }

    public static function pathToUri($path)
    {
        return str_replace("\\", "/", $path);
    }

    public static function to($url = '', $scheme = true)
    {
        return Url::to($url, $scheme);
    }

    public static function toApp($app, $url = '', $scheme = true)
    {
        $appUrlComponnet = 'urlManager' . ucfirst($app);
        return \Yii::$app->{$appUrlComponnet}->createAbsoluteUrl($url, $scheme);
        //\Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/cinema/view','slug'=>$model->slug]);    
        //return Url::to($url, $scheme);
    }

    public static function toAbsolute($url = '')
    {
        return self::to($url, true);

        if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
            //            $url =  $url;
        } elseif (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            $url = Yii::$app->getRequest()->getHostInfo() . $url;
        } elseif (strpos($url, '/') !== 0) {
            $url = Yii::$app->getRequest()->getHostInfo() . '/' . $url;
        }
        return $url; //\yii\helpers\FileHelper::normalizePath($url, '/');
    }

    public static function remember($url = '', $name = 'rabintRemmemberUrl')
    {
        return Url::remember($url, $name);
    }

    public static function referrer($returnBackUrlIfNull = true, $scheme = true)
    {
        $ref = Yii::$app->request->referrer;
        if (empty($ref) and $returnBackUrlIfNull) {
            $ref = Yii::$app->getUser()->getReturnUrl();
        }
        if ($scheme) {
            return static::toAbsolute($ref);
        }
        return $ref;
    }

    /**
     *
     * @param string $location referrer,remember,home,back
     * @param type $alias
     * @return bool
     */
    public static function redirectTo($location = null, $alias = 'rabintRemmemberUrl', $inner_redirect = false)
    {
        switch ($location) {
            case self::RD_REFERRER:
                $redirectLink = static::referrer();
                break;
            case self::RD_REMEMBER:
                $redirectLink = static::previousUrl($alias);
                break;
            case self::RD_HOME:
                $redirectLink = static::home();
                break;
            case self::RD_BACK:
                $redirectLink = Yii::$app->getUser()->getReturnUrl($location);
                break;
            default:
                $redirectLink = $location;
        }
        if (empty($redirectLink) || ($inner_redirect && !isInnerLink($redirectLink))) {
            $redirectLink = static::home();
            Yii::error($redirectLink, "redirect_to_home");
        }
        return static::redirect($redirectLink);
    }

    public static function previousUrl($alias = 'rabintRemmemberUrl')
    {
        return Url::previous($alias);
    }

    public static function redirect($url)
    {
        if (is_array($url) && isset($url[0])) {
            // ensure the route is absolute
            $url[0] = '/' . ltrim($url[0], '/');
        }
        $url = Url::to($url);
        if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            $url = Yii::$app->getRequest()->getHostInfo() . $url;
        }
        if (headers_sent()) {
            echo '<META http-equiv="refresh" content="0;URL=' . $url . '">';
        } else {
            header("location: $url");
        }
        die();
    }

    public static function isInnerLink($url)
    {
        //todo : handele http and https support
        if ($url == static::home()) {
            return true;
        }
        return false;
    }

    public static function normalizeUrl($url = '')
    {

        $url = preg_replace_callback(
            '#(^[a-z]+://)(.+@)?([^/]+)(.*)$#i',
            function ($m) {
                return strtolower($m[1]) . $m[2] . strtolower($m[3]) . $m[4];
            },
            $url
        );
        //    $url = preg_replace_callback(
        //        '#(^[a-z]+://)(.+@)?([^/]+)(.*)$#i',
        //        create_function('$m',
        //            'return strtolower($m[1]).$m[2].strtolower($m[3]).$m[4];'),
        //        $url);
        $url = str_replace("////", "/", $url);
        $url = str_replace("///", "/", $url);
        $url = str_replace("//", "/", $url);
        $url = str_replace("//", "/", $url);
        $url = str_replace("http:/", "http://", $url);
        $url = str_replace("https:/", "https://", $url);
        return $url;
    }


    public static function fulfill_link($link, $baseUrl)
    {
        if (strpos($link, "http://") == 0 or strpos($link, "https://") == 0) {
            return $link;
        }
        $link_url = parse_url($link);
        $base_url = parse_url($baseUrl);

        while (strpos($link, '../') === 0) {
            if (pathinfo($base_url['path'], PATHINFO_EXTENSION) != "") {
                $base_url['path'] = dirname($base_url['path']);
            }
            $base_url['path'] = dirname($base_url['path']);
            $link = substr($link, 3);
        }
        $link_url = parse_url($link);

        $URL = "";
        if (empty($link_url['scheme'])) {
            $URL = $base_url['scheme'] . '://';
        }
        $URL .= $link_url['host'] . $base_url['host'];
        $URL .= $base_url['path'];


        $forend_link = rtrim($URL, '/') . '/' . ltrim($link, '/');
        $forend_link = trim($forend_link, '/');
        $forend_link = trim($forend_link);

        return $forend_link;
    }

    public static function addUrlParam($url, $params)
    {
        if (is_array($params)) {
            $params = http_build_query($params);
        }

        if (strpos($url, "?") === false) {
            return $url . "?" . $params;
        }
        return $url . "&" . $params;
    }


    static function urlToPath($url, $prefix = "")
    {
        $parsed = parse_url($url);
        $return = '';
        //        $return = (isset($parsed['scheme']) ? $parsed['scheme'] : '_scheme_');
        $return .= isset($parsed['host']) ? $parsed['host'] : '_nohost_' . '/';
        $return .= isset($parsed['path']) ? $parsed['path'] : '';

        $bad_char = [':', '\\', '?', ']', '[', '*', '"', '\'', ';', '|', '=', ',', '>', '<'];
        return $prefix . str_replace($bad_char, '_', $return);
    }

    static function unparseUrl($parsed_url)
    {
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }
}
