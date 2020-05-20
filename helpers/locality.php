<?php

namespace rabint\helpers;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class locality
{

    //    static $jalali_month_name = array("", "حمل", "ثور", "جوزا", "سرطان", "اسد", "سنبله", "میزان", "عقرب", "قوس", "جدی", "دلو", "حوت");
    static $jalali_month_name = array(
        '',
        'فروردین',
        'اردیبهشت',
        'خرداد',
        'تیر',
        'مرداد',
        'شهریور',
        'مهر',
        'آبان',
        'آذر',
        'دی',
        'بهمن',
        'اسفند'
    );
    static $jalali_month_name_short = array(
        '',
        'فرو',
        'ارد',
        'خرد',
        'تیر',
        'مرد',
        'شهر',
        'مهر',
        'آبا',
        'آذر',
        'دی',
        'بهم',
        'اسف'
    );
    static $option = array(
        'change_jdate_number_to_persian' => true,
        'change_title_number_to_persian' => true,
        'change_point_to_persian' => true,
    );
    static $jalali_month_days = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
    static $gregorian_month_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    static $jalali_week_name = array('شنبه', 'یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنج شنبه', 'جمعه');
    static $jalali_week_name_short = array("ش", "ی", "د", "س", "چ", "پ", "ج");

    /**
     * return fromated date by user language
     * @param $format
     * @param null $timestamp
     * @param bool $timezone
     * @param bool $fanum
     * @return false|string
     */
    static function date($format, $timestamp = null, $timezone = false, $language = null, $fanum = false)
    {
        if (empty($language)) {
            $language = static::lang();
        }
        if ($language == 'fa') {
            return static::jdate($format, $timestamp, $timezone, $fanum);
        } else {

            if (!$timestamp) {
                $timestamp = time();
            } elseif (!is_numeric($timestamp)) {
                $timestamp = strtotime($timestamp);
            } elseif (!is_integer($timestamp)) {
                $timestamp = intval($timestamp);
            }

            /* =================================================================== */
            if ($timezone === 'local' or $timezone === false) {
                //do noting
            } elseif ($timezone === 'current') {
                $time_zone = self::timesone();
                $dtz = new DateTimeZone($time_zone);
                $time_obj = new DateTime('now', $dtz);
                $deff_time = $dtz->getOffset($time_obj);
                $timestamp += $deff_time;
            } elseif (is_numeric($timezone)) {
                $timestamp += (int)$timezone;
            } elseif (is_string($timezone)) {
                $dtz = new DateTimeZone($timezone);
                $time_obj = new DateTime('now', $dtz);
                $deff_time = $dtz->getOffset($time_obj);
                $timestamp += $deff_time;
            }


            return date($format, $timestamp);
        }
    }

    /**
     * The format of the outputted date string (jalali equivalent of php date() static function)
     * @param string $format for example 'Y-m-d H:i:s'
     * @param timestamp $timestamp [optional]
     * @param bool $timezone [optional]
     * @param bool $fanum [optional]<br/>convert number to persian ?<br/>
     *      default : get from plugin option
     * @return string
     * @since 5.0.0
     */
    public static function jdate($format, $timestamp = null, $timezone = false, $fanum = false)
    {

        if (!$timestamp) {
            $timestamp = time();
        } elseif (!is_numeric($timestamp)) {
            $timestamp = strtotime($timestamp);
        } elseif (!is_integer($timestamp)) {
            $timestamp = intval($timestamp);
        }

        /* =================================================================== */
        if ($timezone === 'local' or $timezone === false) {
            //do noting
        } elseif ($timezone === 'current') {
            $time_zone = self::timesone();
            $dtz = new DateTimeZone($time_zone);
            $time_obj = new DateTime('now', $dtz);
            $deff_time = $dtz->getOffset($time_obj);
            $timestamp += $deff_time;
        } elseif (is_numeric($timezone)) {
            $timestamp += (int)$timezone;
        } elseif (is_string($timezone)) {
            $dtz = new DateTimeZone($timezone);
            $time_obj = new DateTime('now', $dtz);
            $deff_time = $dtz->getOffset($time_obj);
            $timestamp += $deff_time;
        }

        /* =================================================================== */
        if ($fanum === null and self::$option['change_jdate_number_to_persian']) {
            $fanum = true;
        }
        /* =================================================================== */
        # Create need date parametrs
        list($gYear, $gMonth, $gDay, $gWeek) = explode('-', date('Y-m-d-w', $timestamp));
        list($pYear, $pMonth, $pDay) = self::gregorianToJalali($gYear, $gMonth, $gDay);
        $pWeek = ($gWeek + 1);

        if ($pWeek >= 7) {
            $pWeek = 0;
        }

        if ($format == '\\') {
            $format = '//';
        }

        $lenghFormat = strlen($format);
        $i = 0;
        $result = '';

        while ($i < $lenghFormat) {
            $par = $format{
            $i};
            if ($par == '\\') {
                $result .= $format{
                ++$i};
                $i++;
                continue;
            }
            switch ($par) {
                # Day
                case 'd':
                    $result .= (($pDay < 10) ? ('0' . $pDay) : $pDay);
                    break;

                case 'D':
                    $result .= substr(self::$jalali_week_name[$pWeek], 0, 2);
                    break;

                case 'j':
                    $result .= $pDay;
                    break;

                case 'l':
                    $result .= self::$jalali_week_name[$pWeek];
                    break;

                case 'N':
                    $result .= $pWeek + 1;
                    break;

                case 'w':
                    $result .= $pWeek;
                    break;

                case 'z':
                    $result .= self::jdayOfYear($pMonth, $pDay);
                    break;

                case 'S':
                    $result .= 'ام';
                    break;

                # Week
                case 'W':
                    $result .= ceil(self::jdayOfYear($pMonth, $pDay) / 7);
                    break;

                # Month
                case 'F':
                    $result .= self::$jalali_month_name[$pMonth];
                    break;

                case 'm':
                    $result .= (($pMonth < 10) ? ('0' . $pMonth) : $pMonth);
                    break;

                case 'M':
                    $result .= substr(self::$jalali_month_name[$pMonth], 0, 6);
                    break;

                case 'n':
                    $result .= $pMonth;
                    break;

                case 't':
                    $result .= self::jdayOfMonth($pYear, $pMonth);
                    break;

                # Years
                case 'L':
                    $result .= (int)self::isKabise($pYear);
                    break;

                case 'Y':
                case 'o':
                    $result .= $pYear;
                    break;

                case 'y':
                    $result .= substr($pYear, 2);
                    break;

                # Time
                case 'a':
                case 'A':
                    if (date('a', $timestamp) == 'am') {
                        $result .= (($par == 'a') ? 'ق.ظ' : 'قبل از ظهر');
                    } else {
                        $result .= (($par == 'a') ? 'ب.ظ' : 'بعد از ظهر');
                    }
                    break;

                case 'B':
                case 'g':
                case 'G':
                case 'h':
                case 'H':
                case 's':
                case 'u':
                case 'i':
                    # Timezone
                case 'e':
                case 'I':
                case 'O':
                case 'P':
                case 'T':
                case 'Z':
                    $result .= date($par, $timestamp);
                    break;

                # Full Date/Time
                case 'c':
                    $result .= ($pYear . '-' . $pMonth . '-' . $pDay . ' ' . date('H:i:s P', $timestamp));
                    break;

                case 'r':
                    $result .= (substr(
                            self::$jalali_week_name[$pWeek],
                            0,
                            2
                        ) . '، ' . $pDay . ' ' . substr(
                            self::$jalali_month_name[$pMonth],
                            0,
                            6
                        ) . ' ' . $pYear . ' ' . date('H::i:s P', $timestamp));
                    break;

                case 'U':
                    $result .= $timestamp;
                    break;

                default:
                    $result .= $par;
            }
            $i++;
        }
        if ($fanum) {
            return self::faNumberHtml($result);
        }
        return $result;
    }

    /* =================================================================== */

    /**
     * Format a local time/date according to locale settings (jalali equivalent of php strftime() static function)
     * @param string $format for example 'Y-m-d H:i:s'
     * @param timestamp $timestamp [optional]
     * @param bool $fanum [optional]<br/>convert number to persian ?<br/>
     *      default : get from plugin option
     * @return type
     * @since 5.0.0
     */
    public static function jstrftime($format, $timestamp = null, $fanum = false)
    {
        if (!$timestamp) {
            $timestamp = time();
        }

        # Create need date parametrs
        list($gYear, $gMonth, $gDay, $gWeek) = explode('-', date('Y-m-d-w', $timestamp));
        list($pYear, $pMonth, $pDay) = self::gregorianToJalali($gYear, $gMonth, $gDay);
        $pWeek = $gWeek + 1;

        if ($pWeek >= 7) {
            $pWeek = 0;
        }

        $lenghFormat = strlen($format);
        $i = 0;
        $result = '';

        while ($i < $lenghFormat) {
            $par = $format{
            $i};
            if ($par == '%') {
                $type = $format{
                ++$i};
                switch ($type) {
                    # Day
                    case 'a':
                        $result .= substr(self::$jalali_week_name[$pWeek], 0, 2);
                        break;

                    case 'A':
                        $result .= self::$jalali_week_name[$pWeek];
                        break;

                    case 'd':
                        $result .= (($pDay < 10) ? '0' . $pDay : $pDay);
                        break;

                    case 'e':
                        $result .= $pDay;
                        break;

                    case 'j':
                        $dayinM = self::jdayOfYear($pMonth, $pDay);
                        $result .= (($dayinM < 10) ? '00' . $dayinM : ($dayinM < 100) ? '0' . $dayinM : $dayinM);
                        break;

                    case 'u':
                        $result .= $pWeek + 1;
                        break;

                    case 'w':
                        $result .= $pWeek;
                        break;

                    # Week
                    case 'U':
                        $result .= floor(self::jdayOfYear($pMonth, $pDay) / 7);
                        break;

                    case 'V':
                    case 'W':
                        $result .= ceil(self::jdayOfYear($pMonth, $pDay) / 7);
                        break;

                    # Month
                    case 'b':
                    case 'h':
                        $result .= substr(self::$jalali_month_name[$pMonth], 0, 6);
                        break;

                    case 'B':
                        $result .= self::$jalali_month_name[$pMonth];
                        break;

                    case 'm':
                        $result .= (($pMonth < 10) ? '0' . $pMonth : $pMonth);
                        break;

                    # Year
                    case 'C':
                        $result .= ceil($pYear / 100);
                        break;

                    case 'g':
                    case 'y':
                        $result .= substr($pYear, 2);
                        break;

                    case 'G':
                    case 'Y':
                        $result .= $pYear;
                        break;

                    # Time
                    case 'H':
                    case 'I':
                    case 'l':
                    case 'M':
                    case 'R':
                    case 'S':
                    case 'T':
                    case 'X':
                    case 'z':
                    case 'Z':
                        $result .= strftime('%' . $type, $timestamp);
                        break;

                    case 'p':
                    case 'P':
                    case 'r':
                        if (date('a', $timestamp) == 'am') {
                            $result .= (($type == 'p') ? 'ق.ظ' : ($type == 'P') ? 'قبل از ظهر' : strftime(
                                "%I:%M:%S قبل از ظهر",
                                $timestamp
                            ));
                        } else {
                            $result .= (($type == 'p') ? 'ب.ظ' : ($type == 'P') ? 'بعد از ظهر' : strftime(
                                "%I:%M:%S بعد از ظهر",
                                $timestamp
                            ));
                        }
                        break;

                    # Time and Date Stamps
                    case 'c':
                        $result .= (substr(
                                self::$jalali_week_name[$pWeek],
                                0,
                                2
                            ) . ' ' . substr(
                                self::$jalali_month_name[$pMonth],
                                0,
                                6
                            ) . ' ' . $pDay . ' ' . strftime("%T", $timestamp) . ' ' . $pYear);
                        break;

                    case 'D':
                    case 'x':
                        $result .= ((($pMonth < 10) ? '0' . $pMonth : $pMonth) . '-' . (($pDay < 10) ? '0' . $pDay : $pDay) . '-' . substr(
                                $pYear,
                                2
                            ));
                        break;

                    case 'F':
                        $result .= ($pYear . '-' . (($pMonth < 10) ? '0' . $pMonth : $pMonth) . '-' . (($pDay < 10) ? '0' . $pDay : $pDay));
                        break;

                    case 's':
                        $result .= $timestamp;
                        break;

                    # Miscellaneous
                    case 'n':
                        $result .= "\n";
                        break;

                    case 't':
                        $result .= "\t";
                        break;

                    case '%':
                        $result .= '%';
                        break;

                    default:
                        $result .= '%' . $type;
                }
            } else {
                $result .= $par;
            }
            $i++;
        }
        if ($fanum) {
            return self::faNumberHtml($result);
        }
        return $result;
    }

    /* =================================================================== */

    /**
     * return Unix timestamp for a date (jalali equivalent of php mktime() static function)
     * @param int $hour [optional] max : 23
     * @param int $minute [optional] max : 59
     * @param int $second [optional] max: 59
     * @param int $month [optional] max: 12
     * @param int $day [optional] max: 31
     * @param int $year [optional]
     * @param int $is_dst [optional]
     * @return timestamp
     * @since 5.0.0
     */
    public static function jmktime($hour = 0, $minute = 0, $second = 0, $month = 0, $day = 0, $year = 0, $is_dst = -1)
    {
        if (($hour == 0) && ($minute == 0) && ($second == 0) && ($month == 0) && ($day == 0) && ($year == 0)) {
            return time();
        }

        list($year, $month, $day) = self::jalaliToGregorian($year, $month, $day);
        return mktime($hour, $minute, $second, $month, $day, $year, $is_dst);
    }

    /* =================================================================== */

    /**
     * validate a jalali date (jalali equivalent of php checkdate() static function)
     * @param int $month
     * @param int $day
     * @param int $year
     * @return int
     * @since 5.0.0
     */
    public static function jcheckdate($month, $day, $year)
    {
        if (($month < 1) || ($month > 12) || ($year < 1) || ($year > 32767) || ($day < 1)) {
            return 0;
        }

        if ($day > self::$jalali_month_days[$month - 1]) {
            if (($month != 12) || ($day != 30) || !self::isKabise($year)) {
                return 0;
            }
        }

        return 1;
    }

    /* =================================================================== */

    /**
     * Get date/time information (jalali equivalent of php getdate() static function)
     * @param timestamp $timestamp
     * @return array
     * @since 5.0.0
     */
    public static function jgetdate($timestamp = null)
    {
        if (!$timestamp) {
            $timestamp = mktime();
        }

        list($seconds, $minutes, $hours, $mday, $wday, $mon, $year, $yday, $weekday, $month) = explode(
            '-',
            self::jdate('s-i-G-j-w-n-Y-z-l-F', $timestamp, false, false)
        );
        return array(
            0 => $timestamp,
            'seconds' => $seconds,
            'minutes' => $minutes,
            'hours' => $hours,
            'mday' => $mday,
            'wday' => $wday,
            'mon' => $mon,
            'year' => $year,
            'yday' => $yday,
            'weekday' => $weekday,
            'month' => $month
        );
    }

    /* =================================================================== */

    /**
     * gregorian to jalali convertion
     * @staticvar array self::$jalali_month_days
     * @param int $g_y
     * @param int $g_m
     * @param int $g_d
     * @return array
     * @since 5.0.0
     */
    public static function gregorianToJalali($g_y, $g_m, $g_d)
    {
        $gy = $g_y - 1600;
        $gm = $g_m - 1;
        $g_day_no = (365 * $gy + self::intDiv($gy + 3, 4) - self::intDiv($gy + 99, 100) + self::intDiv($gy + 399, 400));

        for ($i = 0; $i < $gm; ++$i) {
            $g_day_no += self::$gregorian_month_days[$i];
        }

        if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0))) # leap and after Feb
        {
            $g_day_no++;
        }
        $g_day_no += $g_d - 1;
        $j_day_no = $g_day_no - 79;
        $j_np = self::intDiv($j_day_no, 12053); # 12053 = (365 * 33 + 32 / 4)
        $j_day_no = $j_day_no % 12053;
        $jy = (979 + 33 * $j_np + 4 * self::intDiv($j_day_no, 1461)); # 1461 = (365 * 4 + 4 / 4)
        $j_day_no %= 1461;

        if ($j_day_no >= 366) {
            $jy += self::intDiv($j_day_no - 1, 365);
            $j_day_no = ($j_day_no - 1) % 365;
        }

        for ($i = 0; ($i < 11 && $j_day_no >= self::$jalali_month_days[$i]); ++$i) {
            $j_day_no -= self::$jalali_month_days[$i];
        }

        return array($jy, $i + 1, $j_day_no + 1);
    }

    /* =================================================================== */

    /**
     * jalali to gregorian convertion
     * @param int $j_y
     * @param int $j_m
     * @param int $j_d
     * @return array
     * @since 5.0.0
     */
    public static function jalaliToGregorian($j_y, $j_m, $j_d)
    {
        $jy = $j_y - 979;
        $jm = $j_m - 1;
        $j_day_no = (365 * $jy + self::intDiv($jy, 33) * 8 + self::intDiv($jy % 33 + 3, 4));
        for ($i = 0; $i < $jm; ++$i) {
            $j_day_no += self::$jalali_month_days[$i];
        }

        $j_day_no += $j_d - 1;
        $g_day_no = $j_day_no + 79;
        $gy = (1600 + 400 * self::intDiv($g_day_no, 146097)); # 146097 = (365 * 400 + 400 / 4 - 400 / 100 + 400 / 400)
        $g_day_no = $g_day_no % 146097;
        $leap = 1;

        if ($g_day_no >= 36525) { # 36525 = (365 * 100 + 100 / 4)
            $g_day_no--;
            $gy += (100 * self::intDiv($g_day_no, 36524)); # 36524 = (365 * 100 + 100 / 4 - 100 / 100)
            $g_day_no = $g_day_no % 36524;
            if ($g_day_no >= 365) {
                $g_day_no++;
            } else {
                $leap = 0;
            }
        }

        $gy += (4 * self::intDiv($g_day_no, 1461)); # 1461 = (365 * 4 + 4 / 4)
        $g_day_no %= 1461;

        if ($g_day_no >= 366) {
            $leap = 0;
            $g_day_no--;
            $gy += self::intDiv($g_day_no, 365);
            $g_day_no = ($g_day_no % 365);
        }

        for ($i = 0; $g_day_no >= (self::$gregorian_month_days[$i] + ($i == 1 && $leap)); $i++) {
            $g_day_no -= (self::$gregorian_month_days[$i] + ($i == 1 && $leap));
        }

        return array($gy, $i + 1, $g_day_no + 1);
    }

    /* =================================================================== */

    /**
     * integer division
     * @param int $a
     * @param int $b
     * @return type
     * @since 5.0.0
     */
    public static function intDiv($a, $b)
    {
        return (int)($a / $b);
    }

    /* =================================================================== */

    /**
     * return day number from first day of year
     * @param int $pMonth
     * @param int $pDay
     * @return type
     * @since 5.0.0
     */
    public static function jdayOfYear($pMonth, $pDay)
    {
        $days = 0;

        for ($i = 1; $i < $pMonth; $i++) {
            $days += self::$jalali_month_days[$i - 1];
        }

        return ($days + $pDay);
    }

    /* =================================================================== */

    /**
     * check jalali year is leap(kabise)
     * @param int $year
     * @return int
     * @since 5.0.0
     */
    public static function isKabise($year)
    {
        $mod = ($year % 33);

        if (($mod == 1) or ($mod == 5) or ($mod == 9) or ($mod == 13) or ($mod == 17) or ($mod == 22) or ($mod == 26) or ($mod == 30)) {
            return 1;
        }

        return 0;
    }

    /* =================================================================== */

    /**
     * return last day of month
     * @param int $year
     * @param int $month
     * @return int number of day in month
     * @since 5.0.0
     */
    public static function jdayOfMonth($year, $month)
    {
        if (self::isKabise($year) && ($month == 12)) {
            return 30;
        }
        $month = (int)$month;
        return self::$jalali_month_days[$month - 1];
    }

    /* =================================================================== */

    /**
     * return jalali name of month from month number
     * @param int $month
     * @return string
     * @since 5.0.0
     */
    public static function monthName($month)
    {
        $month = (int)$month;
        return self::$jalali_month_name[$month];
    }

    /* =================================================================== */

    public static function faNumberMatches($matches)
    {
        if (self::$option['change_point_to_persian']) {
            $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٫");
        } else {
            $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", ".");
        }

        $english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".");

        $out = '';
        if (isset($matches[1])) {
            return str_replace($english_array, $farsi_array, $matches[1]);
        }
        return $matches[0];
    }

    /* =================================================================== */

    /**
     * preg_replace for convert number to farsi
     * @param string $content
     * @return string
     * @since 5.0.0
     * @see wp-jalali 4.5.3 : inc/farsinum-core.php line 23
     */
    public static function faNumberHtml($content)
    {
        return preg_replace_callback(
            '/(?:&#\d{2,4};)|(\d+[\.\d]*)|(?:[a-z](?:[\x20-\x3B\x3D-\x7F]|<\s*[^>]+>)*)|<\s*[^>]+>/i',
            'self::faNumberMatches',
            $content
        );
    }

    /* =================================================================== */

    /**
     * convert arabic char to persian
     * @param string $content
     * @return string
     * @since 5.0.0
     * @see wp-jalali 4.5.3 inc\yk-core.php 44
     */
    public static function arabicToPersian($content)
    {
        return str_replace(array('ي', 'ك', '٤', '٥', '٦', 'ة'), array('ی', 'ک', '۴', '۵', '۶', 'ه'), $content);
    }

    /* =================================================================== */

    /**
     * return week name
     * @param int $gWeek
     * @return string
     * @since 5.0.0
     */
    public static function getWeekName($gWeek = 0, $short = false)
    {
        $jWeek = $gWeek + 1;
        if ($jWeek >= 7) {
            $jWeek = 0;
        }
        if ($short) {
            return self::$jalali_week_name_short[$jWeek];
        }

        return self::$jalali_week_name[$jWeek];
    }

    /* =================================================================== */

    public static function jalaliToTimestamp($str, $dateSep = '/', $timeSep = ' ')
    {
        if (empty($str)) {
            return $str;
        }
        /**
         * todo check $str has standars format by regex
         */
        $str = self::enNumber($str);
        $str = trim($str);
        $tpos = strpos($str, ' ');
        if ($timeSep !== null and ($tpos)) {
            $date = substr($str, 0, $tpos);
            $time = substr($str, $tpos + 1);
        } else {
            $date = $str;
            $time = '00:00:00';
        }
        list($j_y, $j_m, $j_d) = explode($dateSep, $date);
        list($y, $m, $d) = self::jalaliToGregorian($j_y, $j_m, $j_d);
        $timestamp = strtotime("$y-$m-$d $time");
        return $timestamp;
    }

    public static function getWeekNames()
    {
        return [
            'saturday' => 'شنبه',
            'sunday' => 'یکشنیه',
            'monday' => 'دوشنبه',
            'tuesdat' => 'سه‌شنبه',
            'wednesday' => 'چهارشنبه',
            'thursday' => 'پنجشنبه',
            'friday' => 'جمعه',
        ];
    }

    public static function getWeekShortNames()
    {
        return [
            'sat' => 'ش',
            'sun' => 'ی',
            'mon' => 'د',
            'tue' => 'س',
            'wed' => 'چ',
            'thu' => 'پ',
            'fri' => 'ج',
        ];
    }

    /**
     * @param $source
     * @param string $date_divider
     * @param string $time_sep
     * @return string
     * @deprecated
     */
    public static function formatedToGregorian($source, $date_divider = '/', $time_sep = ' ')
    {
        //$source =  '۱۳۹۴/۰۵/۳۱ ۱۳:۲۹:۵۸';
        $source = self::enNumber($source);
        $source = trim($source);
        $tpos = strpos($source, $time_sep);
        if (($time_sep !== false) and ($tpos)) {
            //            list($jdate, $time) = explode($time_sep, $source);
            $jdate = substr($source, 0, $tpos);
            $time = substr($source, $tpos + 1);
            $time_sep = ' ';
        } else {
            $time = '00:00:00';
            $jdate = $source;
            $time_sep = ' ';
        }
        //    var_dump($time);
        //    var_dump($time_sep);

        list($jY, $jM, $jD) = explode($date_divider, $jdate);
        list($y, $m, $d) = self::jalaliToGregorian($jY, $jM, $jD);
        $return = $y . $date_divider . ($m > 9 ? $m : '0' . $m) . $date_divider . ($d > 9 ? $d : '0' . $d) . $time_sep . $time;
        //    echo '<br/><br/>----<br/>';
        //    echo $return;
        return $return;
        //    echo '<br/><br/>----<br/>';
    }

    /**
     * @param $source
     * @param string $date_divider
     * @param string $time_sep
     * @return string
     * @deprecated
     */
    public static function formatedToJalali($source, $date_divider = '/', $time_sep = ' ')
    {
        $source = self::enNumber($source);

        if (($time_sep !== false) and (strpos($source, $time_sep))) {
            list($jdate, $time) = explode($time_sep, $source);
        } else {
            $time = '';
            $jdate = $source;
            $time_sep = '';
        }
        list($gY, $gM, $gD) = explode($date_divider, $jdate);
        list($y, $m, $d) = self::gregorianToJalali($gY, $gM, $gD);
        return $y . $date_divider . $m . $date_divider . $d . $time_sep . $time;
    }

    /**
     * @param $str
     * @return mixed
     * @deprecated
     */
    public static function enNumber($str)
    {
        if (self::$option['change_point_to_persian']) {
            $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٫");
        } else {
            $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", ".");
        }
        $english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".");
        return str_replace($farsi_array, $english_array, $str);
    }

    /**
     * @param $str
     * @return mixed
     * @deprecated
     */
    public static function faNumber($str)
    {
        if (self::$option['change_point_to_persian']) {
            $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٫");
        } else {
            $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", ".");
        }

        $english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".");
        return str_replace($english_array, $farsi_array, $str);
    }


    public static function convertToPersian($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = range(0, 9);
        // replace all english numbers with persian numbers
        return str_replace($english, $persian, $string);
    }

    public static function convertToEnglish($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = range(0, 9);
        // replace all persian numbers with english numbers
        return str_replace($persian, $english, $string);
    }

    public static function isJalali($date)
    {
        $date = self::enNumber($date);
        $y = intval($date);
        if ($y > 0 and $y < 1700) {
            return true;
        }
        return false;
    }

    public static function timesone()
    {
        return 'Asia/Tehran';
    }

    public static function langDir($lang = null)
    {
        if (empty($lang)) {
            $lang = \Yii::$app->language;
        }
        $lang = substr($lang, 0, 2);
        $rtlLangs = [
            'ar', //  'العربية', Arabic
            'arc', //  'ܐܪܡܝܐ', Aramaic
            'bcc', //  'بلوچی مکرانی', Southern Balochi
            'bqi', //  'بختياري', Bakthiari
            'ckb', //  'Soranî / کوردی', Sorani Kurdish
            'dv', //  'ދިވެހިބަސް', Dhivehi
            'fa', //  'فارسی', Persian
            'glk', //  'گیلکی', Gilaki
            'he', //  'עברית', Hebrew
            'lrc', //- 'لوری', Northern Luri
            'mzn', //  'مازِرونی', Mazanderani
            'pnb', //  'پنجابی', Western Punjabi
            'ps', //  'پښتو', Pashto
            'sd', //  'سنڌي', Sindhi
            'ug', //  'Uyghurche / ئۇيغۇرچە', Uyghur
            'ur', //  'اردو', Urdu
            'yi', //  'ייִדיש', Yiddish
        ];
        if (in_array($lang, $rtlLangs)) {
            return 'rtl';
        }
        return 'ltr';
    }

    public static function lang($sep = "_")
    {
        $lang = \Yii::$app->language;
        return str_replace("_", $sep, $lang);
    }

    public static function baseLang($lang = null)
    {
        if (empty($lang))
            $lang = \Yii::$app->language;
        return substr($lang, 0, 2);
    }


    /**
     * @param type $source
     * @param string $targetFormat
     * @return void
     * @author mojtaba akbarzadeh <akbarzadeh.mojtaba@gmail.com>
     */
    public static function anyToGregorian($source, $targetFormat = "Y-m-d H:i:s")
    {
        $year = intval(static::convertToEnglish($source));
        if ($year > 2000) {
            //is Gregorian
            return date($targetFormat, strtotime($source));
        } else {
            return static::formattedToGregorian($source, $targetFormat);
        }
    }

    /**
     * @param type $source
     * @param string $targetFormat
     * @return void
     * @author mojtaba akbarzadeh <akbarzadeh.mojtaba@gmail.com>
     */
    public static function anyToTimeStamp($source)
    {
        $year = intval(static::convertToEnglish($source));
        if ($year > 2000) {
            //is Gregorian
            return strtotime($source);
        } else {
            return strtotime(static::formattedToGregorian($source));
        }
    }


    /**
     * @param type $source
     * @param string $targetFormat
     * @return void
     * @author mojtaba akbarzadeh <akbarzadeh.mojtaba@gmail.com>
     */
    public static function anyToJalali($source, $targetFormat = "Y-m-d H:i:s")
    {
        $year = intval(static::convertToEnglish($source));
        if ($year > 2000) {
            //is Gregorian
            return static::formattedToJalali($source, $targetFormat);
        } else {
            $greg = static::formattedToGregorian($source, $targetFormat);
            return static::convertToEnglish(self::jdate($targetFormat, strtotime($greg)));
        }
    }


    /**
     * @param type $source
     * @param string $date_divider
     * @param string $time_sep
     * @param string $targetFormat
     * @return string
     * @author mojtaba akbarzadeh <akbarzadeh.mojtaba@gmail.com>
     */
    public static function formattedToGregorian($source, $targetFormat = "Y-m-d H:i:s")
    {
        if (empty($source)) {
            return null;
        }
        $source = static::convertToEnglish($source);
        $source = trim($source);

        $year = intval($source);
        $date_divider = substr($source, strlen($year), 1);

        $month = intval(substr($source, strlen($year) + 1));
        $md = substr($source, strlen($year) + 1);

        $dt = substr($md, strpos($md, $date_divider) + 1);
        $day = intval(substr($md, strpos($md, $date_divider) + 1));

        $time = trim(substr($dt, strlen($day) + 1));

        list($y, $m, $d) = static::jalaliToGregorian($year, $month, $day);
        $greg = $y . '/' . ($m > 9 ? $m : '0' . $m) . '/' . ($d > 9 ? $d : '0' . $d) . " " . $time;

        return date($targetFormat, strtotime($greg));
    }

    /**
     * @param type $source
     * @param string $targetFormat
     * @return type
     * @author mojtaba akbarzadeh <akbarzadeh.mojtaba@gmail.com>
     */
    public static function formattedToJalali($source, $targetFormat = "Y-m-d H:i:s")
    {
        $source = static::convertToEnglish($source);
        $source = trim($source);
        return static::convertToEnglish(self::jdate($targetFormat, strtotime($source)));
    }

    /**
     * @param $date
     * @param string $targetFormat
     * @param string $from
     * @param string $to
     * @return string
     *
     */
    public static function swapTimezone($date, $targetFormat = "Y-m-d H:i:s", $from = 'UTC', $to = 'Asia/Tehran')
    {
        $date = new \DateTime($date, new \DateTimeZone($from));

        $date->setTimeZone(new \DateTimeZone($to));

        return $date->format($targetFormat);
    }

    /**
     * use elapsedTime instead
     * @param $seconds
     * @return string
     * @deprecated
     */
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

    /**
     * use elapsedTime instead
     * @param $seconds
     * @return string
     * @deprecated
     */
    public static function secToTimeNoPref($seconds)
    {
        $t = round($seconds);
        if ($t >= 3600) {
            return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
        } elseif ($t >= 60) {
            return sprintf('%02d:%02d', ($t / 60 % 60), $t % 60);
        } else {
            return $t;
        }
    }


    public static function elapsedTime($startTimestamp, $endTimestamp)
    {
        $diffTime = $endTimestamp - $startTimestamp;
        if ($diffTime == 0) {
            return 'کمتر از 1 ثانیه';
        }
        $times = [
            365 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        ];
        $persian = [
            'year' => 'سال',
            'month' => 'ماه',
            'day' => 'روز',
            'hour' => 'ساعت',
            'minute' => 'دقیقه',
            'second' => 'ثانیه'
        ];
        foreach ($times as $secs => $str) {
            $d = $diffTime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $persian[$str];
            }
        }
    }

    public static function getTimeAgo($givenTime)
    {
        if (!is_numeric($givenTime)) {
            $givenTime = strtotime($givenTime);
        }
        $diffTime = time() - $givenTime;

        if ($diffTime < 60) {
            return 'چند لحظه قبل';
        }

        $times = [
            365 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        ];
        $persian = [
            'year' => 'سال',
            'month' => 'ماه',
            'day' => 'روز',
            'hour' => 'ساعت',
            'minute' => 'دقیقه',
            'second' => 'ثانیه'
        ];
        /*$english = [
            'year'   => 'years',
            'month'  => 'months',
            'day'    => 'days',
            'hour'   => 'hours',
            'minute' => 'minutes',
            'second' => 'seconds'
        ];*/

        foreach ($times as $secs => $str) {
            $d = $diffTime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $persian[$str] . ' پیش';
            }
        }
    }

    public static function getDateBetween($date1, $date2, $format)
    { // $format: years, months, days

        $diff = abs(strtotime($date2) - strtotime($date1));
        if ($date2 > $date1)
            $sign = +1;
        elseif ($date1 > $date2)
            $sign = -1;
        else
            $sign = 0;
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff) / (30 * 60 * 60 * 24));
        $days = floor(($diff) / (60 * 60 * 24));
        $kab_t = explode('.', ($diff) / (60 * 60 * 24));
        if (isset($kab_t[1]) && substr($kab_t[1], 0, 2) > 90)
            $days++;
        return ($sign * $$format);
    }

    public static function dateTypeConvert($dateType, $targetFormat, $date)
    {
        switch ($dateType) {
            case 'jalali':
                return static::anyToJalali($date, $targetFormat);
            case 'gregorian':
                return static::anyToGregorian($date, $targetFormat);
            case 'hijri':
                return static::anyToJalali($date, $targetFormat);
        }
    }
}
