<?php

namespace rabint\helpers;

use yii\helpers\Html;
use Yii;
use rabint\attachment\models\Attachment;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class file {

    public static function deepCopy($src, $dst)
    {
        $dir = opendir($src);
        if (!file_exists($dst)) {
            mkdir($dst, 0777, true);
        }
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    deepCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }



    public static function extToType($ext) {
        $ext = strtolower($ext);
        $extTypes = self::extTypes();
        foreach ($extTypes as $type => $exts)
            if (in_array($ext, $exts))
                return $type;
        return 'N/A';
    }

    public static function extToMime($ext) {
        $extArray = self::extMimes();
        if (isset($extArray[$ext])) {
            return $extArray[$ext];
        }
        return 'N/A';
    }

    public static function mimeToExt($mime) {
        $extArray = self::extMimes();
        $ext = array_search($mime, $extArray);
        if ($ext) {
            return $ext;
        }
        return 'N/A';
    }

    public static function mimeToType($mime) {
        $extArray = self::extMimes();
        $ext = array_search($mime, $extArray);
        if (!$ext) {
            return 'N/A';
        }
        return self::extToType($ext);
    }

    /* =================================================================== */



    /* =================================================================== */

    public static function extMimes() {
        return include dirname(__DIR__) . '/cheatsheet/ArrayMimeTypes.php';
    }

    public static function extTypes() {
        return array(
            'image' => array('webp', 'jpg', 'jpeg', 'jpe', 'gif', 'png', 'bmp', 'tif', 'tiff', 'ico'),
            'audio' => array('weba', 'm2a', 'aac', 'ac3', 'aif', 'aiff', 'm3a', 'm4a', 'm4b', 'mka', 'mp1', 'mp2', 'mp3', 'ogg', 'oga', 'ram', 'wav', 'wma'),
            'video' => array('webm', 'm1v', '3g2', '3gp', '3gpp', 'asf', 'avi', 'divx', 'dv', 'flv', 'm4v', 'mkv', 'mov', 'mp4', 'mpeg', 'mpg', 'mpv', 'ogm', 'ogv', 'qt', 'rm', 'vob', 'wmv'),
            'document' => array('doc', 'docx', 'docm', 'dotm', 'odt', 'pages', 'pdf', 'xps', 'oxps', 'rtf', 'wp', 'wpd', 'psd', 'xcf'),
            'spreadsheet' => array('numbers', 'ods', 'xls', 'xlsx', 'xlsm', 'xlsb'),
            'interactive' => array('swf', 'key', 'ppt', 'pptx', 'pptm', 'pps', 'ppsx', 'ppsm', 'sldx', 'sldm', 'odp'),
            'text' => array('asc', 'csv', 'tsv', 'txt'),
            'archive' => array('bz2', 'cab', 'dmg', 'gz', 'rar', 'sea', 'sit', 'sqx', 'tar', 'tgz', 'zip', '7z'),
            'code' => array('css', 'htm', 'html', 'php', 'js'),
        );
    }

    public static function deleteDir($dirPath, $exclude = [], $onlyContent = false) {
        if (!is_dir($dirPath)) {
            throw new \InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (in_array(basename($file), $exclude)) {
                continue;
            }
            if (is_dir($file)) {
                self::deleteDir($file, $exclude);
            } else {
                unlink($file);
            }
        }
        if (!$onlyContent) {
            rmdir($dirPath);
        }
    }

    /* =================================================================== */

    public static function getFileExt($filename) {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    public static function getFileMime($filename) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $res = finfo_file($finfo, $filename);
        finfo_close($finfo);
        return $res;
    }

    public static function readfileChunked($filename, $retbytes = TRUE) {
        $buffer = "";
        $cnt = 0;
        // $handle = fopen($filename, "rb");
        $handle = fopen($filename, "rb");
        if ($handle === false) {
            return false;
        }
        while (!feof($handle)) {
            $buffer = fread($handle, CHUNK_SIZE);
            echo $buffer;
            ob_flush();
            flush();
            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt; // return num. bytes delivered like readfile() does.
        }
        return $status;
    }

    /* ========================================================= */

    public static function download_file($link, $type, $dist = '', $force = true) {
        if ($force) {
            $dl_type = 'force';
        } else {
            if (function_exists('curl_version'))
                $dl_type = 'curl';
            else
                $dl_type = 'stream';
        }

        /* link prossess ---------- */
        $org_link = $link;

        $pos = strpos($link, '?');
        if ($pos) {
            $link = substr($link, 0, $pos);
        }

        $all_Allow = FALSE;
        switch ($type) {
            case 'all':
            case 'allow':
                $all_Allow = true;
                break;
            case 'app':
                $Allows = array('apk', 'rar', 'zip', '7z', 'gz', 'bz2');
                break;
            case 'media':
            case 'sound':
            case 'audio':
            case 'video':
                $Allows = array('mp3', 'mp4', 'flv', 'ogg', 'wma', 'wmv', 'wav');
                break;
            case 'img':
            case 'image':
            default:
                $Allows = array('jpg', 'png', 'jpeg', 'gif');
                break;
        }
        $notAllows = array('php', 'inc');

        $ext = strtolower(pathinfo($link, PATHINFO_EXTENSION));
        /* extaintion prossess================================================== */
        // remove $_GET vars ------------------
        $pos = strpos($ext, '?');
        if ($pos) {
            $ext = substr($ext, 0, $pos);
        }
        /* ==================================================== */
        if (($all_Allow OR in_array($ext, $Allows) ) AND ! (in_array($ext, $notAllows))) {
            if (empty($dist)) {
                $dist = \Yii::getAlias("@app/runtime/tmp_file/" . \rabint\helpers\str::unique() . '.tmp');
            }

            $upload_dir = dirname($dist);
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, TRUE);
            }
            $uploadFile = $dist;
            $basename = basename($dist);

            if (file_exists($uploadFile)) {
                $fname = pathinfo($basename, PATHINFO_FILENAME);
                $fext = pathinfo($basename, PATHINFO_EXTENSION);
                $pre = 0;
                while (file_exists($uploadFile)) {
                    $pre++;
                    $uploadFile = $upload_dir . DIRECTORY_SEPARATOR . $fname . $pre . '.' . $fext;
                }
                $basename = $fname . $pre . '.' . $fext;
            }
            if ($dl_type == 'force') {
//                $file = file_get_contents($link);
                $file = file_get_contents($org_link);
                file_put_contents($uploadFile, $file);
            } elseif ($dl_type == 'curl') {
                if (strpos($link, 'https') === 0)
                    static::download_with_curl_https($org_link, $uploadFile);
                else
                    static::download_with_curl($org_link, $uploadFile);
            }else {
                static::transfer_file($link, $uploadFile);
            }
            return $uploadFile;
        } else {
            //loggg
            return FALSE;
        }
    }

    /* ======================================================== */

    public static function content_with_curl_https($url, $VerifyPeer = false, $VerifyHost = true) {
        //set_time_limit(0);
        $Channel = curl_init($url);
        $dist = '';
//        curl_setopt($Channel, CURLOPT_FIL, $dist);
        curl_setopt($Channel, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($Channel, CURLOPT_HEADER, 0);
        curl_setopt($Channel, CURLOPT_SSL_VERIFYPEER, $VerifyPeer);
        curl_setopt($Channel, CURLOPT_SSL_VERIFYHOST, $VerifyHost);
        $dist = curl_exec($Channel);
        curl_close($Channel);
        return $dist;
    }

    /* ======================================================== */

    public static function download_with_curl_https($url, $destination, $VerifyPeer = false, $VerifyHost = true) {
        //set_time_limit(0);
        $Channel = curl_init($url);
        $dist = fopen($destination, "w");
        curl_setopt($Channel, CURLOPT_FILE, $dist);
        curl_setopt($Channel, CURLOPT_HEADER, 0);
        curl_setopt($Channel, CURLOPT_SSL_VERIFYPEER, $VerifyPeer);
        curl_setopt($Channel, CURLOPT_SSL_VERIFYHOST, $VerifyHost);
        curl_exec($Channel);
        curl_close($Channel);
        $status = fclose($dist);
        return $status;
    }

    /* ======================================================== */

    public static function content_with_curl($url) {
        //set_time_limit(0);
        $Channel = curl_init($url);
        $dist = '';
//        curl_setopt($Channel, CURLOPT_FIL, $dist);
        curl_setopt($Channel, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($Channel, CURLOPT_HEADER, 0);
        $dist = curl_exec($Channel);
        curl_close($Channel);
        return $dist;
    }

    /* ======================================================== */

    public static function download_with_curl($url, $destination) {
        //set_time_limit(0);
        $Channel = curl_init($url);
        $dist = fopen($destination, "w");
        curl_setopt($Channel, CURLOPT_FILE, $dist);
        curl_setopt($Channel, CURLOPT_HEADER, 0);
        curl_exec($Channel);
        curl_close($Channel);
        $status = fclose($dist);
        return $status;
    }

    /* ======================================================== */

    public static function setTrueExt($file) {
        if (file_exists($file)) {
            $mime = static::getFileMime($file);
            $ext = static::mimeToExt($mime);
            if (security::checkAllowedMimeAndExt($mime, $ext)) {
                $dir = dirname($file);
                $fname = pathinfo($file, PATHINFO_FILENAME);
                $fext = pathinfo($file, PATHINFO_EXTENSION);
                $pre = 0;
                $distFile = $dir . DIRECTORY_SEPARATOR . $fname . '.' . $ext;
                while (file_exists($distFile)) {
                    $pre++;
                    $distFile = $dir . DIRECTORY_SEPARATOR . $fname . $pre . '.' . $ext;
                }
                rename($file, $distFile);
                return($distFile);
            }
            return FALSE;
        }
        return NULL;
    }

    /**
     * 
     * @param type $url
     * @param type $type
     * @param type $name
     * @return Attachment
     */
    public static function urlToAttachment($url, $type = "all", $name = "") {

        $res = static::download_file($url, $type, \Yii::getAlias("@app/runtime/tmp_file/" . $name), true);
//        var_dump($res);

        $res = static::setTrueExt($res);
//        var_dump($res);
//        die('-=-==-=-');
//        if (!empty($name)) {
//            
//        } else {
//            /**
//             * global security check:
//             */
//            if (!\rabint\helpers\security:::checkAllowedUploadedFile($file)) {
//                return $this->errorOutput(Yii::t('rabint', 'file type not allowed'));
//            }
//        }

        $model = new \rabint\attachment\models\Tmpupload();

        $attachement = $model->createFile('attachment', $res, $name);
        if (!empty($attachement)) {
            static::regenerateAttachment($attachement->id);
        }
        return $attachement;
    }

    public static function transfer_file($url, $destination, $bufer_size = 1048576) {
        $buffer = '';
        $handle = fopen($url, 'rb');
        if ($handle === false) {
            return false;
        }
        $dist = fopen($destination, 'w');
        while (!feof($handle)) {
            $buffer = fread($handle, $bufer_size);
            fwrite($dist, $buffer);
        }
        $status = fclose($dist);
        return $status;
    }

    /* ===================================================== */

    public static function get_file_size($url) {
        $fp = fopen($url, 'r');
        $data = stream_get_meta_data($fp);
        fclose($fp);
        foreach ($data['wrapper_data'] as $wd) {
            if (strpos($wd, 'Content-Length') === 0) {
                $size = substr($wd, 15);
                break;
            }
        }
        if (isset($size) AND is_numeric($size)) {
            return $size; //in byte
        }
        return null;
    }

    public static function rename_if_exist($file) {
        if (file_exists($file)) {
            $fname = pathinfo($file, PATHINFO_FILENAME);
            $fext = pathinfo($file, PATHINFO_EXTENSION);
            $addr = pathinfo($file, PATHINFO_DIRNAME);
            $pre = 0;
            $new_file = $file;
            while (file_exists($new_file)) {
                $pre++;
                $new_file = $addr . DIRECTORY_SEPARATOR . $fname . $pre . '.' . $fext;
            }
            return $new_file;
        } else {
            return $file;
        }
    }

    /* ========================================================= */

    public static function regenerateAttachment($id) {
        $attachment = Attachment::findOne($id);
        if ($attachment == null) {
            return "attachment_not_find";
        }
        $publicPath = $attachment->getFullPath();
        $output = "";
        if (!file_exists($publicPath)) {
            $output .= "WRN:" . 'file not exist:' . $attachment->id . "\n\r";
        } else {
            switch ($attachment->type) {
                case Attachment::TYPE_IMAGE:
                    /* =================================================================== */
                    \rabint\attachment\attachment::removeOtherSize($attachment);
                    $precents = \rabint\attachment\attachment::imgPresetsFn($attachment->component);
                    foreach ($precents as $preset => $presetFn) {
                        $realPath = dirname($publicPath);
                        $fileName = basename($publicPath);
                        $thumbPath = \rabint\attachment\behaviors\AttechmentBehavior::generateThumbName($fileName, $preset);
                        $res = $presetFn($realPath, $fileName, $thumbPath);
                    }
                    $output .= 'generateThumbnailSize(id): ' . $attachment->id;
                    /* =================================================================== */
                    break;
                case Attachment::TYPE_AUDIO:
                case Attachment::TYPE_VIDEO:
                    /* =================================================================== */
                    $output .= 'convertAttachment(id): ' . $attachment->id;
                    \rabint\helpers\media::convertAttachment($attachment);
                    /* =================================================================== */
                    break;
                default :
                    $output .= 'type of ' . $attachment->id . ':' . $attachment->type;
            }
        }
        return $output;
    }

    public static function rename_for_breakname($file, $breakname, $replace) {
        if (empty($breakname))
            return $file;
        if (!(is_array($breakname)))
            $breakname = array($breakname);
        foreach ($breakname as $name) {
            $file = str_replace($name, $replace, $file);
        }
        return $file;
    }

    public static function downloadContentImgs($content, $base_url) {
        $pat = '/<img[^<]*src=["|\']([^"|^\']*)["|\'][^<]*>/';
        preg_match_all($pat, $content, $data);
        foreach ($data[1] as $value) {
            $Url = \rabint\helpers\uri::fulfill_link($value, $base_url);
            $attach = static::urlToAttachment($Url);
            if (!empty($attach)) {
                $new_value = $attach->getUrl();
            } else {
                $new_value = '';
            }
//            $value = str_replace('/', '\/', $value);
//            $pat = '/(' . $value . ')/';
//            $content = preg_replace($pat, $new_value, $content);
            $content = str_replace($value, $new_value, $content);
        }
        return $content;
    }

    /* ========================================================= */

    public static function downloadContentFiles($content, $base_url, $ext = array('zip', 'rar', 'apk', 'pdf', 'doc', '7z')) {
        die('fix it before use : \rabint\helpers\file::downloadContentFiles');
        $pat = '/<[a|A][^>]*href=["|\']([^"|^\']*)["|\']>/';
        preg_match_all($pat, $content, $data);
        foreach ($data[1] as $value) {
            $Url = \rabint\helpers\uri::fulfill_link($value, $base_url);
            $up = static::download_file($Url, 'allow', '', 0);
            if (!empty($up)) {
                $new_value = $up['url'];
                $pat = '/(' . $value . ')/';
                $content = preg_replace($pat, $new_value, $content);
            } else {
                $new_value = '';
                $pat = '/(' . $value . ')/';
                $content = preg_replace($pat, $new_value, $content);
            }
        }
        return $content;
    }

}
