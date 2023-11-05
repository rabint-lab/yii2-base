<?php

namespace rabint\helpers;

class collection
{

    /**
     * check isset $key return it , else return default value
     * usage: return $array[$key]
     * @param mixed $array
     * @param string $key
     * @param mixed $default
     * @return array|string
     * @author mojtaba.akbarzadeh
     *
     */
    public static function getValue($array, $key, $default = null)
    {
        if (is_object($array)) {
            return isset($array->$key) ? $array->$key : $default;
        }
        return isset($array[$key]) ? $array[$key] : $default;
    }

    /**
     * get a filed of an array/object
     * usage: return $array[$key][$column]
     * @param mixed $array
     * @param string $key
     * @param $cell
     * @param mixed $default
     * @return array|string
     * @author mojtaba akbarzadeh <akbarzadeh.mojtaba@gmail.com>
     *
     */
    public static function getCell($array, $key, $cell, $default = null)
    {
        $array = self::objToArray($array);
        return (isset($array[$key]) && isset($array[$key][$cell])) ? $array[$key][$cell] : $default;
    }

    /**
     * get a fileds of an array/object
     *
     * @param mixed $array
     * @param mixed $default
     * @return array|string
     * @author mojtaba akbarzadeh <akbarzadeh.mojtaba@gmail.com>
     *
     */
    public static function getColumn($array, $column)
    {
        $array = self::objToArray($array);
        $res = [];
        foreach ((array)$array as $key => $row) {
            $res[$key] = $row[$column];
        }
        return $res;
    }

    public static function stripslashesDeep($value)
    {
        if (is_array($value)) {
            //$value = array_map('self::stripslashesDeep', $value);
            //fix: php 8.2 warning: self in callables is deprecated
            $value = array_map([self::class , 'stripslashesDeep'], $value);
        } elseif (is_object($value)) {
            $vars = get_object_vars($value);
            foreach ($vars as $key => $data) {
                $value->{$key} = self::stripslashesDeep($data);
            }
        } elseif (is_string($value)) {
            $value = stripslashes($value);
        }

        return $value;
    }

    /**
     * convert object to array
     * @param object $object
     * @return array
     * @author mojtaba akbarzadeh <akbarzadeh.mojtaba@gmail.com>
     *
     */
    public static function objToArray($object)
    {
        if (static::isJson($object)) {
            return json_decode($object, true);
        }
        return json_decode(json_encode($object), true);
    }

    /**
     * convert array to object
     * @param object $object
     * @return array
     * @author mojtaba akbarzadeh <akbarzadeh.mojtaba@gmail.com>
     *
     */
    public static function ArrayToObj($object)
    {
        return json_decode(json_encode($object));
    }


    public static function isNumericArray($array)
    {
        foreach ($array as $a => $b) {
            if (!is_int($a)) {
                return false;
            }
        }
        return true;
    }

    /* switch key value ============================================ */

    public static function switchKeyVal($array)
    {
        $new = [];
        foreach ($array as $key => $value) {
            $new[$value] = $key;
        }
        return $new;
    }

    /* rotate array ============================================ */

    public static function rotateArray($array)
    {
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                foreach ($value as $i => $val) {
                    $new[$i][$key] = $val;
                }
            }
            return $new;
        }
        return $array;
    }

    /* rotate array ============================================ */

    public static function arrayToKeyVal($array, $key = 'key', $val = 'value')
    {
        $new = [];
        if (!empty($array)) {
            foreach ($array as $arr) {
                $new[$arr[$key]] = $arr[$val];
            }
        }
        return $new;
    }


    public static function setArrayKey($array, $key = 'key')
    {
        $new = [];
        if (!empty($array)) {
            foreach ($array as $arr) {
                $new[$arr[$key]] = $arr;
            }
        }
        return $new;
    }

    public static function deepByKey($array, $key = 'key')
    {
        $new = [];
        if (!empty($array)) {
            foreach ($array as $arr) {
                $new[$arr[$key]][] = $arr;
            }
        }
        return $new;
    }

    public static function deepByKeyVal($array, $key = 'key', $val = 'value')
    {
        $new = [];
        if (!empty($array)) {
            foreach ($array as $arr) {
                $new[$arr[$key]][] = $arr[$val];
            }
        }
        return $new;
    }


    /* =================================================================== */

    public static function sortArrayByVal(&$array, $key)
    {
        return usort($array, function ($a, $b) use ($key) {
            return $a[$key] - $b[$key];
        });
    }

    public static function sortArrayColumn(&$array, $key, $order = SORT_ASC)
    {
        return array_multisort(array_column($array, $key), $order, $array);
    }

    public static function isJson($string)
    {
        if (is_string($string)) {

            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }
        return false;
    }

    /* ================================================ */

    public static function isSerialized($value, &$result = null)
    {
        // Bit of a give away this one
        if (!is_string($value)) {
            return false;
        }
        // Serialized false, return true. unserialize() returns false on an
        // invalid string or it could return false if the string is serialized
        // false, eliminate that possibility.
        if ($value === 'b:0;') {
            $result = false;
            return true;
        }
        $length = strlen($value);
        $end = '';
        switch ($value[0]) {
            case 's':
                if ($value[$length - 2] !== '"') {
                    return false;
                }
            case 'b':
            case 'i':
            case 'd':
                // This looks odd but it is quicker than isset()ing
                $end .= ';';
            case 'a':
            case 'O':
                $end .= '}';
                if ($value[1] !== ':') {
                    return false;
                }
                switch ($value[2]) {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                        break;
                    default:
                        return false;
                }
            case 'N':
                $end .= ';';
                if ($value[$length - 1] !== $end[0]) {
                    return false;
                }
                break;
            default:
                return false;
        }
        if (($result = @unserialize($value)) === false) {
            $result = null;
            return false;
        }
        return true;
    }

    public static function ArrayToNested($comments, $parent, $slug, $objUrl, $evenOdd)
    {
        //        echo '<ul class="children">';
        //        foreach ($comments as $itemArr) {
        //            if ($itemArr['replay_of'] == $parent['id']) {
        //                if (!empty($itemArr['user'])) {
        //                    $itemArr['name'] = $itemArr['user']['display_name'];
        //                }
        //
        //                echo '<li id="comment-' . $itemArr['id'] . '" class="comment ' . $evenOdd . ' thread-' . $evenOdd . ' depth-1 parent">
        //                            <div class="comment-body" id="div-comment-' . $itemArr['id'] . '">
        //                                <div class="comment-author vcard">
        //                                    <img width="32" height="32" class="avatar avatar-32 photo" srcset="' . url('/', true) . '/img/user.jpg" src="' . url('/', true) . '/img/user.jpg" alt="">
        //                                    <cite class="fn"><a class="url" rel="external nofollow" href="' . $itemArr['url'] . '">' . $itemArr['name'] . '</a></cite>
        //                                    <span class="says"> در پاسخ به ' . '<a href="#div-comment-' . $parent['id'] . '">' . $parent['name'] . '</a> می گه: </span>
        //                                </div>
        //                                <div class="comment-meta commentmetadata">
        //                                    <a href="#">
        //                                        ' . formated_to_jalali($itemArr['date_create']) . '
        //                                    </a>
        //                                <p>' . $itemArr['content'] . '</p>
        //
        //                                <div class="reply"><a aria-label="پاسخ به: ' . $itemArr['name'] . '" onclick="return addComment.moveForm( & quot; div - comment - 3412 & quot; , & quot; 3412 & quot; , & quot; respond & quot; , & quot; 48113 & quot; )"
        //                                    href="' . $objUrl->build([
        //                    "action" => "view",
        //                    $slug, $itemArr['id'], $itemArr['name']
        //                ]) . '#comments" class="comment-reply-link" rel="nofollow">پاسخ دادن</a></div>
        //                            </div>';
        //                self::makeCommentNested($comments, $itemArr, $slug, $objUrl, $evenOdd);
        //                echo '</div></li>';
        //                $evenOdd = ($evenOdd == 'even') ? 'odd' : 'even';
        //            }
        //        }
        //        echo '</ul>';
    }

    public static function renderTreeUl($tree, $options = array())
    {
        $options = array_merge([
            'ul_id' => '',
            'ul_class' => 'menu',
            'li_class' => '',
        ], $options);

        $liClass = (!empty($options['li_class'])) ? $options['li_class'] . ' ' : '';
        /* ------------------------------------------------------ */
        $current_level = 0;
        $counter = 0;

        $result = '<ul id="' . $options['ul_id'] . '" class="' . $options['ul_class'] . '">';
        $node_level = '';
        foreach ($tree as $node) {
            $node_level = $node->level;
            $node_id = $node->id;

            if ($node_level == $current_level) {
                if ($counter > 0) {
                    $result .= '</li>';
                }
            } elseif ($node_level > $current_level) {
                $pos = strrpos($result, '__li__');
                if ($pos) {
                    $result = substr_replace($result, 'has-chlid', $pos, 0);
                }
                $result .= '<ul class="submenu">';
                $current_level = $current_level + ($node_level - $current_level);
            } elseif ($node_level < $current_level) {
                $result .= str_repeat('</li></ul>', $current_level - $node_level) . '</li>';
                $current_level = $current_level - ($current_level - $node_level);
            }
            $result .= '<li id="menu-item-' . $node_id . '"';
            $result .= ' class="' . $liClass . '__li__"';
            $result .= '><a href="' . $node->link . '">' . $node->title . '</a>';
            ++$counter;
        }
        if (!empty($node_level)) {
            $result .= str_repeat('</li></ul>', $node_level) . '</li>';
        }

        $result .= '</ul>';
        $result = str_replace('__li__', '', $result);
        return $result;
    }

    /* =================================================================== */

    public static function arrayGetValueByPath($array, $path, $default = false)
    {
        if (strpos($path, '.')) {
            $keys = explode('.', $path);
            $ret = $array;
            foreach ($keys as $k) {
                if (isset($ret[$k])) {
                    $ret = $ret[$k];
                } else {
                    break;
                }
            }
            $return = $ret;
        } else {
            if (isset($array[$path])) {
                $return = $array[$path];
            }
        }
        if (!isset($return)) {
            $return = $default;
        }
        return $return;
    }

    /**
     *
     * @param $array1
     * @param $array2
     * @return bool
     */
    public static function arraysHasAnySameItem($array1, $array2)
    {
        foreach ($array1 as $val) {
            if (in_array($val, $array2)) {
                return true;
            }
        }
        return false;
    }

    public static function array1HasAllOfArray2($array1, $array2)
    {
        foreach ($array2 as $val) {
            if (!in_array($val, $array1)) {
                return false;
            }
        }
        return true;
    }

    public static function arraySearchDeep($array, $value, $inKey = false)
    {
        foreach ($array as $key => $row) {
            if (is_array($row)) {
                $ret = self::arraySearchDeep($row, $value, $inKey);
                if ($ret !== false) {
                    return $key . '.' . $ret;
                    //                } else {
                    //                    return $ret;
                }
            } else {
                if ($inKey and $key != $inKey) {
                    continue;
                } else {
                    if (is_string($value)) {
                        if (strcmp($row, $value) == 0) {
                            return $key;
                        }
                    } else {
                        if ($row == $value) {
                            return $key;
                        }
                    }
                }
            }
        }
        return false;
    }


    public static function closest($array, $index)
    {
        $curr = reset($array);
        $curKey = key($array);
        $diff = abs($index - $curr);
        foreach ($array as $key => $row) {
            $newdiff = abs($index - $row);
            if ($newdiff < $diff) {
                $diff = $newdiff;
                $curr = $row;
                $curKey = $key;
            }
        }
        return $curKey;
    }

    public static function closest2d($array, $index)
    {
        $curr = reset($array);
        $curx = $curr[0];
        $cury = $curr[1];
        $curxKey = $curyKey = key($array);
        $difx = abs($index[0] - $curx);
        $dify = abs($index[1] - $cury);
        foreach ($array as $key => $row) {
            $newdifx = abs($index[0] - $row[0]);
            $newdify = abs($index[1] - $row[1]);
            if ($newdifx < $difx) {
                $difx = $newdifx;
                $curx = $row[0];
                $curxKey = $key;
            }
            if ($newdify < $dify) {
                $dify = $newdify;
                $cury = $row[1];
                $curyKey = $key;
            }
        }
        if ($curxKey == $curyKey) {
            return $curxKey;
        }
        if ($difx < $dify) {
            return $curxKey;
        }
        return $curyKey;
    }

    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param array $array
     * @return bool
     */
    public static function isAssoc(array $array)
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;

        //        if (array() === $arr) return false;
        //        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function divide($array)
    {
        return [array_keys($array), array_values($array)];
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param array $array
     * @param string $prepend
     * @return array
     */
    public static function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }


    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $array
     * @param int $depth
     * @return array
     */
    public static function flatten($array, $depth = INF)
    {
        $result = [];

        foreach ($array as $item) {
            $item = $item instanceof Collection ? $item->all() : $item;

            if (!is_array($item)) {
                $result[] = $item;
            } elseif ($depth === 1) {
                $result = array_merge($result, array_values($item));
            } else {
                $result = array_merge($result, static::flatten($item, $depth - 1));
            }
        }

        return $result;
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param array $array
     * @param array|string $keys
     * @return void
     */
    public static function forget(&$array, $keys)
    {
        $original = &$array;

        $keys = (array)$keys;

        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (static::exists($array, $key)) {
                unset($array[$key]);

                continue;
            }

            $parts = explode('.', $key);

            // clean up before each pass
            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    public static function only($array, $keys)
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }


    /**
     * Push an item onto the beginning of an array.
     *
     * @param array $array
     * @param mixed $value
     * @param mixed $key
     * @return array
     */
    public static function prepend($array, $value, $key = null)
    {
        if (is_null($key)) {
            array_unshift($array, $value);
        } else {
            $array = [$key => $value] + $array;
        }

        return $array;
    }

    /**
     * Get a value from the array, and remove it.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function pull(&$array, $key, $default = null)
    {
        $value = static::get($array, $key, $default);

        static::forget($array, $key);

        return $value;
    }


    /**
     * Get one or a specified number of random values from an array.
     *
     * @param array $array
     * @param int|null $number
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public static function random($array, $number = null)
    {
        $requested = is_null($number) ? 1 : $number;

        $count = count($array);

        if ($requested > $count) {
            throw new InvalidArgumentException(
                "You requested {$requested} items, but there are only {$count} items available."
            );
        }

        if (is_null($number)) {
            return $array[array_rand($array)];
        }

        if ((int)$number === 0) {
            return [];
        }

        $keys = array_rand($array, $number);

        $results = [];

        foreach ((array)$keys as $key) {
            $results[] = $array[$key];
        }

        return $results;
    }

    /**
     * Shuffle the given array and return the result.
     *
     * @param array $array
     * @param int|null $seed
     * @return array
     */
    public static function shuffle($array, $seed = null)
    {
        if (is_null($seed)) {
            shuffle($array);
        } else {
            srand($seed);

            usort($array, function () {
                return rand(-1, 1);
            });
        }

        return $array;
    }

    /**
     * Filter the array using the given callback.
     *
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function where($array, callable $callback)
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Execute a callback over each item.
     *
     * @param callable $callback
     * @return $this
     */
    public static function each($array, callable $callback)
    {
        foreach ($array as $key => &$item) {
            $res = $callback($item, $key);
            if ($res === false) {
                continue;
            }
            $item = $res;
        }

        return $array;
    }

    /**
     * If the given value is not an array and not null, wrap it in one.
     *
     * @param mixed $value
     * @return array
     */
    public static function wrap($value)
    {
        if (is_null($value)) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }

    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param array $array
     * @param callable|null $callback
     * @param mixed $default
     * @return mixed
     */
    public static function first($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return value($default);
            }

            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }

        return value($default);
    }

    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param array $array
     * @param callable|null $callback
     * @param mixed $default
     * @return mixed
     */
    public static function last($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($array) ? value($default) : end($array);
        }

        return static::first(array_reverse($array, true), $callback, $default);
    }


    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param \ArrayAccess|array $array
     * @param string|array $keys
     * @return bool
     */
    public static function has($array, $keys)
    {
        if (is_null($keys)) {
            return false;
        }

        $keys = (array)$keys;

        if (!$array) {
            return false;
        }

        if ($keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;

            if (static::exists($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (static::accessible($subKeyArray) && static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }


    static function dirToArray($dir)
    {

        $result = array();

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = static::dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * convert array to json
     *
     * @param  $array as array
     * @return json
     * @author r.sheshkalani
     *
     */

    static function array_to_json($array)
    {
        if (!is_array($array)) {

            return false;
        }
        $associative = count(array_diff(array_keys($array), array_keys(array_keys($array))));

        if ($associative) {
            $construct = array();

            foreach ($array as $key => $value) {

                // We first copy each key/value pair into a staging array,

                // formatting each key and value properly as we go.
                // Format the key:

                if (is_numeric($key)) {
                    //$key = "key_$key";
                }

                $key = "\"" . addslashes($key) . "\"";
                // Format the value:

                if (is_array($value)) {

                    $value = array_to_json($value);
                } else if (!is_numeric($value) || is_string($value)) {

                    $value = "\"" . addslashes($value) . "\"";
                }
                // Add to staging array:
                $construct[] = "$key: $value";
            }
            // Then we collapse the staging array into the JSON form:
            $result = "{ " . implode(", ", $construct) . " }";
        } else { // If the array is a vector (not associative):
            $construct = array();
            foreach ($array as $value) {
                // Format the value:
                if (is_array($value)) {
                    $value = array_to_json($value);
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = "\"" . addslashes($value) . "\"";
                }
                // Add to staging array:
                $construct[] = $value;
            }
            // Then we collapse the staging array into the JSON form:
            $result = "[ " . implode(", ", $construct) . " ]";
        }
        return $result;
    }
}
