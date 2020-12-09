<?php

namespace rabint\helpers;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class str
{

    /**
     * The cache of snake-cased words.
     *
     * @var array
     */
    protected static $snakeCache = [];

    /**
     * The cache of camel-cased words.
     *
     * @var array
     */
    protected static $camelCache = [];

    /**
     * The cache of studly-cased words.
     *
     * @var array
     */
    protected static $studlyCache = [];

    public static function unique($len = null, $moreUnique = true, $prefix = "")
    {
       $uni = str_replace('.','',uniqid("", $moreUnique));
        $res = $prefix . base_convert($uni, 16, 36);
        if ($len > 0) {
            return substr($res, 0, $len);
        }
        return $res;
    }


    public static function limitUnique($length = 8, $moreUnique = true, $prefix = "")
    {
        return substr($prefix . base_convert(uniqid("", $moreUnique), 16, 36), 0, $length);
    }

    public static function uniqueHash()
    {
        return md5(uniqid('rabint', true));
    }

    /**
     * this function return a random string
     * @param int $len
     * @return bool|string
     */
    public static function random($length = 8)
    {
        //        $num = uniqid("", true);
        //        $res = md5($num);
        //        return substr($res, 0, $len);
        return substr(str_shuffle(md5(time())), 0, $length);
    }

    /**
     *
     * @param array $modelError
     * @return string
     */
    public static function modelErrToStr($modelError, $returnHtml = true)
    {
        $allErr = [];
        if (is_array($modelError)) {
            foreach ($modelError as $err) {
                if (is_array($err)) {
                    foreach ($err as $er) {
                        if (is_array($er)) {
                            foreach ($er as $e) {
                                $allErr[] = print_r($e, true);
                            }
                        } else {
                            $allErr[] = print_r($er, true);
                        }
                    }
                } else {
                    $allErr[] = print_r($err, true);
                }
            }
        } else {
            $allErr[] = print_r($modelError, true);
        }
        $allErr = implode(PHP_EOL, $allErr);
        if ($returnHtml) {
            return nl2br($allErr);
        }
        return $allErr;
    }

    /**
     *
     * @param array $modelError
     * @return string
     */
    public static function modelErrors($modelError, $prefix = true)
    {
        $allErr = '';
        if ($prefix === true) {
            $allErr = '' . \Yii::t('rabint', ' لطفا خطاهای زیر را رفع فرمایید:') . '</br>';
        } else if (!empty($prefix)) {
            $allErr = '' . $prefix . '</br>';
        }
        $allErr .= '<ul class="modeErros">';
        $errors = self::modelErrToStr($modelError, false);
        $errors = explode(PHP_EOL, $errors);
        foreach ($errors as $error) {
            $allErr .= '<li>' . $error . '</li>';
        }
        $allErr .= '</ul>';
        return $allErr;
    }

    public static function summarize($content, $len = 120, $suffix = '', $asHtml = true, $encoding = null)
    {
        return \yii\helpers\StringHelper::truncate($content, $len, $suffix, $encoding, $asHtml);
    }

    public static function summarizeWords($content, $len = 12, $suffix = '', $asHtml = true)
    {
        return \yii\helpers\StringHelper::truncateWords($content, $len, $suffix, $asHtml);
    }

    public static function countWords($content)
    {
        return \yii\helpers\StringHelper::countWords($content);
    }

    public static function htmlToText($content)
    {
        $content = strip_tags($content);
        return $content;
    }


    function strposa($haystack, $needles = array(), $offset = 0)
    {
        $chr = array();
        foreach ($needles as $needle) {
            $res = strpos($haystack, $needle, $offset);
            if ($res !== false) {
                $chr[$needle] = $res;
            }
        }
        if (empty($chr)) {
            return false;
        }
        return min($chr);
    }

    public static function textToHtml($content, $options = [])
    {
        $options = array_merge(['tag' => 'p'], $options);
        if ($options['tag']) {
            return '<p>' . nl2br($content) . '</p>';
        }
        return nl2br($content);
    }

    public static function nl2p($content, $class = "")
    {
        $content = preg_replace("/[\n]/", "</p><p class=\"$class\">", $content);
        $content = "<p class=\"$class\">" . $content . "</p>";
        echo $content;
    }
    public static function CountToText($count)
    {
        if ($count < 10) {
            return '<10';
        }
        /* =================================================================== */
        $zlen = strlen((string) $count) - 1;
        $div = pow(10, $zlen);
        $c = (int) ($count / $div);
        $t = ($c < 2) ? 1 : (($c < 5) ? 2 : 5);
        //    echo $count.' , '.$zlen.', '.$count.' ÷ '.$div.' = '.$c.' | ';
        $ret = (int) $t . str_repeat('0', $zlen);
        if ($count < 100) {
            return '-' . number_format($ret);
        }
        return '+' . number_format($ret);
    }

    public static function sizeToText($size, $params = [])
    {
        //        if ($size < 1024) {
        //            return \Yii::t('rabint', '>1');
        //        }
        //        if ($size < 1024000) {
        //            $size = (int) ($size / 1024);
        //            return $size . \Yii::t('rabint', 'KB');
        //        }
        /* =================================================================== */
        $uint = ['B', 'KB', 'MB', 'GB', 'TB'];
        //        $uint = [
        //            \Yii::t('rabint', 'بایت'),
        //            \Yii::t('rabint', 'کیلوبایت'),
        //            \Yii::t('rabint', 'مگابایت'),
        //            \Yii::t('rabint', 'گیگابایت'),
        //            \Yii::t('rabint', 'ترابایت')];
        $res = $size;
        for ($i = 0; $i < 4; $i++) {
            if (($res / 1024) < 1) {
                break;
            }
            $res /= 1024;
        }
        if ($res > 1000) {
            $Res = number_format($res, 0);
        } else {
            $Res = number_format($res, 1);
            $Res = rtrim($Res, '.0');
        }
        if (isset($params['div'])) {
            return $Res . ' ' . '<' . $params['div'] . '>' . $uint[$i] . '</' . $params['div'] . '>';
        }
        return $Res . ' ' . $uint[$i];
    }

    /**
     * @param integer $fileSizeInBytes
     * @return mixed
     */
    public static function justifyFileSizeFormat($fileSizeInBytes)
    {
        if ($fileSizeInBytes > 0) {
            $unit = intval(log($fileSizeInBytes, 1024));
            $units = array('B', 'KB', 'MB', 'GB');

            if (array_key_exists($unit, $units) === true) {
                return sprintf('%d %s', $fileSizeInBytes / pow(1024, $unit), $units[$unit]);
            }
        }
    }

    public static function secToTime($seconds)
    {
        $t = round($seconds);
        if ($t >= 3600) {
            return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60) . ' ' . \Yii::t('rabint', 'ساعت');
        } elseif ($t >= 60) {
            return sprintf('%02d:%02d', ($t / 60 % 60), $t % 60) . ' ' . \Yii::t('rabint', 'دقیقه');
        } else {
            return $t . ' ' . \Yii::t('rabint', 'ثانیه');
        }
    }

    public static function slugify($string, $separator = '-')
    {
        $special_chars = array(
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
            chr(0)
        );
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
        return $string;
    }

    public static function selected($first, $last = null)
    {
        if ($last === null) {
            if ($first) {
                return ' selected="selected" ';
            }
            return '';
        } else {
            if ($first == $last) {
                return ' selected="selected" ';
            }
            return '';
        }
    }

    public static function checked($first, $last = null)
    {
        if ($last === null) {
            if ($first) {
                return ' checked="checked" ';
            }
            return '';
        } else {
            if ($first == $last) {
                return ' checked="checked" ';
            }
            return '';
        }
    }

    /**
     * $cellNumber string
     * $defaultBase string :
     * if "" (empty) => remove all base
     * if "0" => replace any base with "0"
     * if "98" (CC=Country Code) => replace "0" to "CC" , "" to "CC" , "+CC" to "CC"
     * if "+98" (+CC) => replace "0" to "+CC" , "" to "+CC" , "CC" to "+CC"
     * if "0098" (00CC) => replace "0" to "00CC" , "" to "00CC" , "CC" to "00CC"
     * return string
     */
    public static function CellphoneSanitise($cellNumber, $defaultBase = "98")
    {
        $cell = static::formatCellphone($cellNumber, $defaultBase);
        if (!$cell) {
            return $cellNumber;
        }
        return $cell;
    }

    /**
     * suppoted:
     *  09151238855
     *  989151238855
     * +989151338855
     * 9151238855
     * 09011235258
     * 09901238855
     * 989011235258
     * 989901238855
     * 00989151238855
     * @param $cellNumber
     * @return bool
     */
    public static function isValidCellphone($cellNumber)
    {
        $re1 = '/^(\+\d\d|0|\d\d|00\d\d)?(9)(\d{9})$/isu';
        if (preg_match($re1, $cellNumber)) {
            return true;
        }
        return false;
    }

    public static function formatCellphone($cellNumber, $defaultBase = "98")
    {
        /**
         * cellphone not has true lenght
         */
        if (!static::isValidCellphone($cellNumber)) {
            return false;
        }
        $len = strlen($cellNumber);


        $cell = substr($cellNumber, $len - 10, 10);
        $base = substr($cellNumber, 0, $len - 10);



        if ($defaultBase === "0") {
            return '0' . $cell;
        }
        if (empty($defaultBase)) {
            return $cell;
        }
        $pf = "";
        $CC = $defaultBase;
        if (strpos($defaultBase, '+') === 0) {
            $pf = "+";
            $CC = substr($defaultBase, 1);
        } elseif (strpos($defaultBase, '00') === 0) {
            $pf = "00";
            $CC = substr($defaultBase, 2);
        }

        /**
         * NOT NEED TO CHECK IT 
         * handle default prefix and Conuntry code
         */
        /*if (empty($base) or $base == "0") {
         *   return $pf . $CC . $cell;
        }*/

        /**
         * find base country code
         */
        if (strpos($base, '+') === 0) {
            $CC = substr($base, 1);
        } elseif (strpos($base, '00') === 0 and strlen($base)>2 ) {
            $CC = substr($base, 2);
        }

        return $pf . $CC . $cell;
    }

    public static function addClass($class, $first, $last = null)
    {
        if ($last === null) {
            if ($first) {
                return $class;
            }
            return '';
        } else {
            if ($first == $last) {
                return $class;
            }
            return '';
        }
    }

    public static function fixnl($cnt)
    {
        $ret = str_replace('\n', "\n", $cnt);
        $ret = nl2br($ret);
        return $ret;
    }

    public static function removenl($cnt)
    {
        $ret = str_replace('\n', " ", $cnt);
        $ret = str_replace("\n", " ", $ret);
        return $ret;
    }

    public static function CellFormatter($cell, $globalFromat = false)
    {
        $is_match = preg_match('/(\+[0-9]{2}|0)([0-9]{10})/', $cell, $res);
        if ($is_match and $cell == $res[0]) {
            if ($res[1] == 0) {
                return $globalFromat ? '+98' . $res[2] : '0' . $res[2];
            }
            return $cell;
        } else {
            return false;
        }
    }

    public static function getCellContext($cell)
    {
        $len = strlen($cell);
        if ($len > 10) {
            return substr($cell, $len - 10);
        } else {
            return $cell;
        }
    }


    public static function remove_links($content)
    {
        $pat = '/<[a|A][^>]*>(.*?)<\/[a|A]>/';
        preg_match_all($pat, $content, $data);
        foreach ($data[0] as $key => $value) {
            $new_value = $data[1][$key];
            $value = str_replace('/', '\/', $value);
            $pat = '/(' . $value . ')/';
            $content = preg_replace($pat, $new_value, $content);
        }
        return $content;
    }

    /* ========================================================= */

    public static function remove_imgs($content)
    {
        $pat = '/<img[^<]*src=["|\']([^"|^\']*)["|\'][^<]*>/';
        preg_replace($pat, '', $content);
        return $content;
    }

    /* ========================================================= */

    public static function change_words($content, $old, $new)
    {
        foreach ($old as $key => $value) {
            $pat = '/(' . $value . ')/';
            $content = preg_replace($pat, $new[$key], $content);
        }
        return $content;
    }


    /**
     * replace $needle with $replacement if $haystack has $needle as prefix
     *
     * @param  string  $needle
     * @param  string  $replacement
     * @param  string  $haystack
     * @return string
     */
    public static function replacePrefix($needle, $replacement, $haystack)
    {
        if (static::startsWith($haystack, $needle)) {
            return $replacement . substr($haystack, strlen($needle));
        }
        return $haystack;
    }

    /**
     * replace $needle with $replacement if $haystack has $needle as postfix
     *
     * @param  string  $needle
     * @param  string  $replacement
     * @param  string  $haystack
     * @return string
     */
    public static function replacePostfix($needle, $replacement, $haystack)
    {
        if (static::endsWith($haystack, $needle)) {
            return substr($haystack, 0, -strlen($needle)) . $replacement;
        }
        return $haystack;
    }


    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }




    /**
     * Return the remainder of a string after a given value.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    public static function after($subject, $search)
    {
        return $search === '' ? $subject : array_reverse(explode($search, $subject, 2))[0];
    }

    /**
     * Transliterate a UTF-8 value to ASCII.
     *
     * @param  string  $value
     * @param  string  $language
     * @return string
     */
    public static function ascii($value, $language = 'en')
    {
        $languageSpecific = static::languageSpecificCharsArray($language);

        if (!is_null($languageSpecific)) {
            $value = str_replace($languageSpecific[0], $languageSpecific[1], $value);
        }

        foreach (static::charsArray() as $key => $val) {
            $value = str_replace($val, $key, $value);
        }

        return preg_replace('/[^\x20-\x7E]/u', '', $value);
    }

    /**
     * Get the portion of a string before a given value.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    public static function before($subject, $search)
    {
        return $search === '' ? $subject : explode($search, $subject)[0];
    }

    /**
     * Convert a value to camel case.
     *
     * @param  string  $value
     * @return string
     */
    public static function camel($value)
    {
        if (isset(static::$camelCache[$value])) {
            return static::$camelCache[$value];
        }

        return static::$camelCache[$value] = lcfirst(static::studly($value));
    }

    /**
     * Cap a string with a single instance of a given value.
     *
     * @param  string  $value
     * @param  string  $cap
     * @return string
     */
    public static function finish($value, $cap)
    {
        $quoted = preg_quote($cap, '/');

        return preg_replace('/(?:' . $quoted . ')+$/u', '', $value) . $cap;
    }

    /**
     * Convert a string to kebab case.
     *
     * @param  string  $value
     * @return string
     */
    public static function kebab($value)
    {
        return static::snake($value, '-');
    }


    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        $key = $value;
        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));
            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
            //$value = substr($value,0,20);
        }
        return static::$snakeCache[$key][$delimiter] = $value;
    }


    /**
     * Limit the number of characters in a string.
     *
     * @param  string  $value
     * @param  int  $limit
     * @param  string  $end
     * @return string
     */
    public static function limit($value, $limit = 100, $end = '...')
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }
        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
    }
    /**
     * Convert the given string to lower-case.
     *
     * @param  string  $value
     * @return string
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }
    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    public static function studly($value)
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    /**
     * Return the length of the given string.
     *
     * @param  string  $value
     * @param  string  $encoding
     * @return int
     */
    public static function length($value, $encoding = 'UTF-8')
    {
        if ($encoding) {
            return mb_strlen($value, $encoding);
        }

        return mb_strlen($value);
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     *
     * @param  string  $string
     * @param  int  $start
     * @param  int|null  $length
     * @return string
     */
    public static function substr($string, $start, $length = null, $encoding = 'UTF-8')
    {
        return mb_substr($string, $start, $length, $encoding);
    }

    /**
     * Make a string's first character uppercase.
     *
     * @param  string  $string
     * @return string
     */
    public static function ucfirst($string)
    {
        return strtoupper(substr($string, 0, 1)) . substr($string, 1);
    }

    /**
     * Returns the replacements for the ascii method.
     *
     * Note: Adapted from Stringy\Stringy.
     *
     * @see https://github.com/danielstjules/Stringy/blob/3.1.0/LICENSE.txt
     *
     * @return array
     */
    protected static function charsArray()
    {
        static $charsArray;

        if (isset($charsArray)) {
            return $charsArray;
        }

        return $charsArray = [
            '0'    => ['°', '₀', '۰', '０'],
            '1'    => ['¹', '₁', '۱', '１'],
            '2'    => ['²', '₂', '۲', '２'],
            '3'    => ['³', '₃', '۳', '３'],
            '4'    => ['⁴', '₄', '۴', '٤', '４'],
            '5'    => ['⁵', '₅', '۵', '٥', '５'],
            '6'    => ['⁶', '₆', '۶', '٦', '６'],
            '7'    => ['⁷', '₇', '۷', '７'],
            '8'    => ['⁸', '₈', '۸', '８'],
            '9'    => ['⁹', '₉', '۹', '９'],
            'a'    => ['à', 'á', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'ā', 'ą', 'å', 'α', 'ά', 'ἀ', 'ἁ', 'ἂ', 'ἃ', 'ἄ', 'ἅ', 'ἆ', 'ἇ', 'ᾀ', 'ᾁ', 'ᾂ', 'ᾃ', 'ᾄ', 'ᾅ', 'ᾆ', 'ᾇ', 'ὰ', 'ά', 'ᾰ', 'ᾱ', 'ᾲ', 'ᾳ', 'ᾴ', 'ᾶ', 'ᾷ', 'а', 'أ', 'အ', 'ာ', 'ါ', 'ǻ', 'ǎ', 'ª', 'ა', 'अ', 'ا', 'ａ', 'ä'],
            'b'    => ['б', 'β', 'ب', 'ဗ', 'ბ', 'ｂ'],
            'c'    => ['ç', 'ć', 'č', 'ĉ', 'ċ', 'ｃ'],
            'd'    => ['ď', 'ð', 'đ', 'ƌ', 'ȡ', 'ɖ', 'ɗ', 'ᵭ', 'ᶁ', 'ᶑ', 'д', 'δ', 'د', 'ض', 'ဍ', 'ဒ', 'დ', 'ｄ'],
            'e'    => ['é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'ë', 'ē', 'ę', 'ě', 'ĕ', 'ė', 'ε', 'έ', 'ἐ', 'ἑ', 'ἒ', 'ἓ', 'ἔ', 'ἕ', 'ὲ', 'έ', 'е', 'ё', 'э', 'є', 'ə', 'ဧ', 'ေ', 'ဲ', 'ე', 'ए', 'إ', 'ئ', 'ｅ'],
            'f'    => ['ф', 'φ', 'ف', 'ƒ', 'ფ', 'ｆ'],
            'g'    => ['ĝ', 'ğ', 'ġ', 'ģ', 'г', 'ґ', 'γ', 'ဂ', 'გ', 'گ', 'ｇ'],
            'h'    => ['ĥ', 'ħ', 'η', 'ή', 'ح', 'ه', 'ဟ', 'ှ', 'ჰ', 'ｈ'],
            'i'    => ['í', 'ì', 'ỉ', 'ĩ', 'ị', 'î', 'ï', 'ī', 'ĭ', 'į', 'ı', 'ι', 'ί', 'ϊ', 'ΐ', 'ἰ', 'ἱ', 'ἲ', 'ἳ', 'ἴ', 'ἵ', 'ἶ', 'ἷ', 'ὶ', 'ί', 'ῐ', 'ῑ', 'ῒ', 'ΐ', 'ῖ', 'ῗ', 'і', 'ї', 'и', 'ဣ', 'ိ', 'ီ', 'ည်', 'ǐ', 'ი', 'इ', 'ی', 'ｉ'],
            'j'    => ['ĵ', 'ј', 'Ј', 'ჯ', 'ج', 'ｊ'],
            'k'    => ['ķ', 'ĸ', 'к', 'κ', 'Ķ', 'ق', 'ك', 'က', 'კ', 'ქ', 'ک', 'ｋ'],
            'l'    => ['ł', 'ľ', 'ĺ', 'ļ', 'ŀ', 'л', 'λ', 'ل', 'လ', 'ლ', 'ｌ'],
            'm'    => ['м', 'μ', 'م', 'မ', 'მ', 'ｍ'],
            'n'    => ['ñ', 'ń', 'ň', 'ņ', 'ŉ', 'ŋ', 'ν', 'н', 'ن', 'န', 'ნ', 'ｎ'],
            'o'    => ['ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ø', 'ō', 'ő', 'ŏ', 'ο', 'ὀ', 'ὁ', 'ὂ', 'ὃ', 'ὄ', 'ὅ', 'ὸ', 'ό', 'о', 'و', 'θ', 'ို', 'ǒ', 'ǿ', 'º', 'ო', 'ओ', 'ｏ', 'ö'],
            'p'    => ['п', 'π', 'ပ', 'პ', 'پ', 'ｐ'],
            'q'    => ['ყ', 'ｑ'],
            'r'    => ['ŕ', 'ř', 'ŗ', 'р', 'ρ', 'ر', 'რ', 'ｒ'],
            's'    => ['ś', 'š', 'ş', 'с', 'σ', 'ș', 'ς', 'س', 'ص', 'စ', 'ſ', 'ს', 'ｓ'],
            't'    => ['ť', 'ţ', 'т', 'τ', 'ț', 'ت', 'ط', 'ဋ', 'တ', 'ŧ', 'თ', 'ტ', 'ｔ'],
            'u'    => ['ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'û', 'ū', 'ů', 'ű', 'ŭ', 'ų', 'µ', 'у', 'ဉ', 'ု', 'ူ', 'ǔ', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'უ', 'उ', 'ｕ', 'ў', 'ü'],
            'v'    => ['в', 'ვ', 'ϐ', 'ｖ'],
            'w'    => ['ŵ', 'ω', 'ώ', 'ဝ', 'ွ', 'ｗ'],
            'x'    => ['χ', 'ξ', 'ｘ'],
            'y'    => ['ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'ÿ', 'ŷ', 'й', 'ы', 'υ', 'ϋ', 'ύ', 'ΰ', 'ي', 'ယ', 'ｙ'],
            'z'    => ['ź', 'ž', 'ż', 'з', 'ζ', 'ز', 'ဇ', 'ზ', 'ｚ'],
            'aa'   => ['ع', 'आ', 'آ'],
            'ae'   => ['æ', 'ǽ'],
            'ai'   => ['ऐ'],
            'ch'   => ['ч', 'ჩ', 'ჭ', 'چ'],
            'dj'   => ['ђ', 'đ'],
            'dz'   => ['џ', 'ძ'],
            'ei'   => ['ऍ'],
            'gh'   => ['غ', 'ღ'],
            'ii'   => ['ई'],
            'ij'   => ['ĳ'],
            'kh'   => ['х', 'خ', 'ხ'],
            'lj'   => ['љ'],
            'nj'   => ['њ'],
            'oe'   => ['ö', 'œ', 'ؤ'],
            'oi'   => ['ऑ'],
            'oii'  => ['ऒ'],
            'ps'   => ['ψ'],
            'sh'   => ['ш', 'შ', 'ش'],
            'shch' => ['щ'],
            'ss'   => ['ß'],
            'sx'   => ['ŝ'],
            'th'   => ['þ', 'ϑ', 'ث', 'ذ', 'ظ'],
            'ts'   => ['ц', 'ც', 'წ'],
            'ue'   => ['ü'],
            'uu'   => ['ऊ'],
            'ya'   => ['я'],
            'yu'   => ['ю'],
            'zh'   => ['ж', 'ჟ', 'ژ'],
            '(c)'  => ['©'],
            'A'    => ['Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'Å', 'Ā', 'Ą', 'Α', 'Ά', 'Ἀ', 'Ἁ', 'Ἂ', 'Ἃ', 'Ἄ', 'Ἅ', 'Ἆ', 'Ἇ', 'ᾈ', 'ᾉ', 'ᾊ', 'ᾋ', 'ᾌ', 'ᾍ', 'ᾎ', 'ᾏ', 'Ᾰ', 'Ᾱ', 'Ὰ', 'Ά', 'ᾼ', 'А', 'Ǻ', 'Ǎ', 'Ａ', 'Ä'],
            'B'    => ['Б', 'Β', 'ब', 'Ｂ'],
            'C'    => ['Ç', 'Ć', 'Č', 'Ĉ', 'Ċ', 'Ｃ'],
            'D'    => ['Ď', 'Ð', 'Đ', 'Ɖ', 'Ɗ', 'Ƌ', 'ᴅ', 'ᴆ', 'Д', 'Δ', 'Ｄ'],
            'E'    => ['É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'Ë', 'Ē', 'Ę', 'Ě', 'Ĕ', 'Ė', 'Ε', 'Έ', 'Ἐ', 'Ἑ', 'Ἒ', 'Ἓ', 'Ἔ', 'Ἕ', 'Έ', 'Ὲ', 'Е', 'Ё', 'Э', 'Є', 'Ə', 'Ｅ'],
            'F'    => ['Ф', 'Φ', 'Ｆ'],
            'G'    => ['Ğ', 'Ġ', 'Ģ', 'Г', 'Ґ', 'Γ', 'Ｇ'],
            'H'    => ['Η', 'Ή', 'Ħ', 'Ｈ'],
            'I'    => ['Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị', 'Î', 'Ï', 'Ī', 'Ĭ', 'Į', 'İ', 'Ι', 'Ί', 'Ϊ', 'Ἰ', 'Ἱ', 'Ἳ', 'Ἴ', 'Ἵ', 'Ἶ', 'Ἷ', 'Ῐ', 'Ῑ', 'Ὶ', 'Ί', 'И', 'І', 'Ї', 'Ǐ', 'ϒ', 'Ｉ'],
            'J'    => ['Ｊ'],
            'K'    => ['К', 'Κ', 'Ｋ'],
            'L'    => ['Ĺ', 'Ł', 'Л', 'Λ', 'Ļ', 'Ľ', 'Ŀ', 'ल', 'Ｌ'],
            'M'    => ['М', 'Μ', 'Ｍ'],
            'N'    => ['Ń', 'Ñ', 'Ň', 'Ņ', 'Ŋ', 'Н', 'Ν', 'Ｎ'],
            'O'    => ['Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'Ø', 'Ō', 'Ő', 'Ŏ', 'Ο', 'Ό', 'Ὀ', 'Ὁ', 'Ὂ', 'Ὃ', 'Ὄ', 'Ὅ', 'Ὸ', 'Ό', 'О', 'Θ', 'Ө', 'Ǒ', 'Ǿ', 'Ｏ', 'Ö'],
            'P'    => ['П', 'Π', 'Ｐ'],
            'Q'    => ['Ｑ'],
            'R'    => ['Ř', 'Ŕ', 'Р', 'Ρ', 'Ŗ', 'Ｒ'],
            'S'    => ['Ş', 'Ŝ', 'Ș', 'Š', 'Ś', 'С', 'Σ', 'Ｓ'],
            'T'    => ['Ť', 'Ţ', 'Ŧ', 'Ț', 'Т', 'Τ', 'Ｔ'],
            'U'    => ['Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự', 'Û', 'Ū', 'Ů', 'Ű', 'Ŭ', 'Ų', 'У', 'Ǔ', 'Ǖ', 'Ǘ', 'Ǚ', 'Ǜ', 'Ｕ', 'Ў', 'Ü'],
            'V'    => ['В', 'Ｖ'],
            'W'    => ['Ω', 'Ώ', 'Ŵ', 'Ｗ'],
            'X'    => ['Χ', 'Ξ', 'Ｘ'],
            'Y'    => ['Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ', 'Ÿ', 'Ῠ', 'Ῡ', 'Ὺ', 'Ύ', 'Ы', 'Й', 'Υ', 'Ϋ', 'Ŷ', 'Ｙ'],
            'Z'    => ['Ź', 'Ž', 'Ż', 'З', 'Ζ', 'Ｚ'],
            'AE'   => ['Æ', 'Ǽ'],
            'Ch'   => ['Ч'],
            'Dj'   => ['Ђ'],
            'Dz'   => ['Џ'],
            'Gx'   => ['Ĝ'],
            'Hx'   => ['Ĥ'],
            'Ij'   => ['Ĳ'],
            'Jx'   => ['Ĵ'],
            'Kh'   => ['Х'],
            'Lj'   => ['Љ'],
            'Nj'   => ['Њ'],
            'Oe'   => ['Œ'],
            'Ps'   => ['Ψ'],
            'Sh'   => ['Ш'],
            'Shch' => ['Щ'],
            'Ss'   => ['ẞ'],
            'Th'   => ['Þ'],
            'Ts'   => ['Ц'],
            'Ya'   => ['Я'],
            'Yu'   => ['Ю'],
            'Zh'   => ['Ж'],
            ' '    => ["\xC2\xA0", "\xE2\x80\x80", "\xE2\x80\x81", "\xE2\x80\x82", "\xE2\x80\x83", "\xE2\x80\x84", "\xE2\x80\x85", "\xE2\x80\x86", "\xE2\x80\x87", "\xE2\x80\x88", "\xE2\x80\x89", "\xE2\x80\x8A", "\xE2\x80\xAF", "\xE2\x81\x9F", "\xE3\x80\x80", "\xEF\xBE\xA0"],
        ];
    }

    /**
     * Returns the language specific replacements for the ascii method.
     *
     * Note: Adapted from Stringy\Stringy.
     *
     * @see https://github.com/danielstjules/Stringy/blob/3.1.0/LICENSE.txt
     *
     * @param  string  $language
     * @return array|null
     */
    protected static function languageSpecificCharsArray($language)
    {
        static $languageSpecific;

        if (!isset($languageSpecific)) {
            $languageSpecific = [
                'bg' => [
                    ['х', 'Х', 'щ', 'Щ', 'ъ', 'Ъ', 'ь', 'Ь'],
                    ['h', 'H', 'sht', 'SHT', 'a', 'А', 'y', 'Y'],
                ],
                'de' => [
                    ['ä',  'ö',  'ü',  'Ä',  'Ö',  'Ü'],
                    ['ae', 'oe', 'ue', 'AE', 'OE', 'UE'],
                ],
            ];
        }

        return $languageSpecific[$language] ?? null;
    }
    
    public static function explodeUrlParameters($url,$part = null){
        $query = parse_url($url, PHP_URL_QUERY);
        $return = [];
        if($query){
            foreach(explode('&',$query) as $values){
                $row = explode('=',$values);
                $return[$row[0]] = $row[1];
            }
        }
        if($part){
            return $return[$part];
        }
        
        return $return;
    }
    
    public static function xml2array ( $xmlObject, $out = array () )
    {
        foreach ( (array) $xmlObject as $index => $node ){
            $out[$index] = (is_object($node)) ? self::xml2array ( $node ) : $node;
        }

        return $out;
    }
}
