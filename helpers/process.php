<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 11/12/18
 * Time: 1:26 PM
 */

namespace rabint\helpers;


class process
{

    public static function escapeArgument($argument)
    {
        // Fix for PHP bug #43784 escapeshellarg removes % from given string
        // Fix for PHP bug #49446 escapeshellarg doesn't work on Windows
        // @see https://bugs.php.net/bug.php?id=43784
        // @see https://bugs.php.net/bug.php?id=49446
        if ('\\' === DIRECTORY_SEPARATOR) {
            if ('' === $argument) {
                return '""';
            }

            $escapedArgument = '';
            $quote = false;

            foreach (preg_split('/(")/', $argument, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE) as $part) {
                if ('"' === $part) {
                    $escapedArgument .= '\\"';
                } elseif (self::isSurroundedBy($part, '%')) {
                    // Avoid environment variable expansion
                    $escapedArgument .= '^%"'.substr($part, 1, -1).'"^%';
                } else {
                    // escape trailing backslash
                    if ('\\' === substr($part, -1)) {
                        $part .= '\\';
                    }
                    $quote = true;
                    $escapedArgument .= $part;
                }
            }

            if ($quote) {
                $escapedArgument = '"'.$escapedArgument.'"';
            }

            return $escapedArgument;
        }

        return "'".str_replace("'", "'\\''", $argument)."'";
    }

    /**
     * Is the given string surrounded by the given character?
     *
     * @param  string  $arg
     * @param  string  $char
     * @return bool
     */
    protected static function isSurroundedBy($arg, $char)
    {
        return 2 < strlen($arg) && $char === $arg[0] && $char === $arg[strlen($arg) - 1];
    }

    public static function startObToKeepProcessing($timeOut = 0)
    {
        ignore_user_abort();
        set_time_limit($timeOut);
        ob_start();
    }

    public static function endObAndKeepProcessing()
    {
        $size = ob_get_length();
        // Disable compression (in case content length is compressed).
        header("Content-Encoding: none");
        // Set the content length of the response.
        header("Content-Length: {$size}");
        // Close the connection.
        header("Connection: close");
        // Flush all output.
        ob_end_flush();
        ob_flush();
        flush();
        // Close current session (if it exists).
        if (session_id()) {
            session_write_close();
        }
    }

//    static function doInBackground($url) {
//        exec($url.' 2>&1 > /dev/null');
//    }

    public static function simpleOutputProgress($current, $total, $reportHtml = '')
    {
        $reportHtml = str_replace("\t", " &nbsp; &nbsp;  &nbsp; ", $reportHtml);
        $precent = round($current / $total * 100);
        echo "<div style='padding:10px;position: absolute;right:0;top:0;direction:rtl;z-index:$current;background:#eee;left:0; bottom:0; overflow:auto;'>"
            . $precent .
            "% "
            . '<br/>'
            . str_repeat('.', $precent)
            . '<br/>'
            . '<br/>' . nl2br($reportHtml)
            . "</div>\r\n";
        echo(str_repeat(' ', 256));
        if (@ob_get_contents()) {
            @ob_end_flush();
        }
        flush();
    }


}