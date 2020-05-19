<?php

namespace rabint\helpers;

use Yii;
use rabint\attachment\models\Attachment;
use Thread;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class media {

    /**
     * 
     * @param string $inputPath
     * @return attachment_id
     */
    public static function generateVideoThumbnail($attachment_id) {
        $command = 'ffmpeg -ss %d -i %s -vf  "thumbnail" -frames:v 1  %s';
        /* =============================================================== */
        $attachment = Attachment::findOne($attachment_id);
        if ($attachment == NULL) {
            return FALSE;
        }

        $outputPath = $inputPath = $attachment->getFullPath();
        $newName = uniqid() . '.jpg';
        $outputPath = str_replace($attachment->name, $newName, $outputPath);

        $len = (int) static::getMediaLength($inputPath) / 2;
        /* ================================================================== */
        $command = sprintf($command, $len, $inputPath, $outputPath);
        /* ================================================================== */
        $resVal = 0;
        static::systemCommand($command, $resVal);
        if ($resVal == 0) {
            $res = \rabint\attachment\models\Attachment::createByPath($outputPath);
            if ($res) {
                return $res;
            } else {
                $notifyMessage = \Yii::t('rabint', 'فایل ضمیمه {attachment} آپلود شده اما تمبنیل آن ایجاد نگردید.', ['attachment' => $attachment->getFullPath()]);
                Yii::warning($notifyMessage, 'attachment');
//                if (isset(Yii::$app->notify)) {
//                Yii::$app->notify->send(
//                        null, $notifyMessage, '', ['priority' => \app\modules\notify\models\Notification::PRIORITY_LOW]
//                );
//                }
//                print_r($attachment->errors);
                echo ('error ON save');
                return FALSE;
            }
        }
        echo ('error on converte');
        $notifyMessage = \Yii::t('rabint', 'فایل ضمیمه {attachment} آپلود شده اما تمبنیل آن ایجاد نگردید.', ['attachment' => $attachment->getFullPath()]);
        Yii::warning($notifyMessage, 'attachment');
//        Yii::$app->notify->send(
//                null, $notifyMessage, '', ['priority' => \app\modules\notify\models\Notification::PRIORITY_LOW]
//        );
        return FALSE;
    }

    public static function getMediaLength($fullPath) {
        $command = 'ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 %s';
        /* =============================================================== */
        $command = sprintf($command, $fullPath);
        /* ================================================================== */
        $resVal = 0;
        $length = static::systemCommand($command, $resVal);
        if ($resVal == 0) {
            return intval($length);
        }
        return FALSE;
    }

    public static function getMediaInfo($fullPath) {
//        $command = 'ffprobe -v quiet -print_format json -show_format -show_streams %s';
//        /* =============================================================== */
//        $command = sprintf($command, $fullPath);
//        /* ================================================================== */
//        $resVal = 0;
//        $json = static::systemCommand($command, $resVal);
//        if ($resVal == 0) {
//            return json_decode($json, TRUE);
//        }
        return FALSE;
    }

    public static function getMediaData($fullPath) {
        $res = static::getMediaInfo($fullPath);
        if (empty($res) OR ! isset($res['format'])) {
            return FALSE;
        }
        $res = $res['format'];
        $ext = $res['format_name'];
        $fcom = strpos($ext, ',');
        if ($fcom > 0) {
            $ext = substr($ext, 0, $fcom);
        }
        return [
            'ext' => $ext,
            'type' => \rabint\helpers\file::extToType($ext),
            'mime' => \rabint\helpers\file::extToMime($ext),
        ];
    }

    public static function getMediaType($fullPath) {
        $res = static::getMediaInfo($fullPath);
        if (empty($res) OR ! isset($res['format'])) {
            return FALSE;
        }
        $res = $res['format'];
        $ext = $res['format_name'];
        $fcom = strpos($ext, ',');
        if ($fcom > 0) {
            $ext = substr($ext, 0, $fcom);
        }
        return \rabint\helpers\file::extToType($ext);
    }

    public static function getMediaOrginalExt($fullPath) {
        $res = static::getMediaInfo($fullPath);
        if (empty($res) OR ! isset($res['format'])) {
            return FALSE;
        }
        $res = $res['format'];
        $ext = $res['format_name'];
        $fcom = strpos($ext, ',');
        if ($fcom > 0) {
            $ext = substr($ext, 0, $fcom);
        }
        return $ext;
    }

    /* =================================================================== */

    public static function getAttachmentScenesById($attachment_id, $tolerance = 0.3, $count = 5) {
        $attachment = Attachment::findOne($attachment_id);
        if ($attachment == NULL) {
            return FALSE;
        }
        return static::getAttachmentScenes($attachment, $saveOrgin);
    }

    /**
     * 
     * @param string $inputPath
     * @return converted image path
     */
    public static function getAttachmentScenes($attachment, $tolerance = 0.4, $count = 6) {
        $inputPath = $attachment->getFullPath();
        $outputPath = dirname($inputPath) . '/scene/';
        if ($attachment->type == Attachment::TYPE_IMAGE) {
            mkdir($outputPath, 0777, TRUE);
            //large_57f9dd7107db2.jpg
            $outputPath = $outputPath . 'scene_01.' . pathinfo($inputPath, PATHINFO_EXTENSION);
            copy($inputPath, $outputPath);
            return $outputPath;
        } elseif ($attachment->type == Attachment::TYPE_AUDIO) {
            $command = 'ffmpeg -i ' . $inputPath . ' -an -vcodec copy ' . $outputPath . 'scene_01.jpg';
        } elseif ($attachment->type == Attachment::TYPE_VIDEO) {
            $command = 'ffmpeg  -i ' . $inputPath . ' -vf "select=gt(scene\,' . $tolerance . ')" -frames:v ' . $count . ' -vsync vfr ' . $outputPath . 'scene_%02d.jpg';
            $cut = (int) static::getMediaLength($inputPath) / ($count);
            $command = 'ffmpeg -i ' . $inputPath . ' -vf fps=1/' . $cut . ' ' . $outputPath . 'scene_%02d.jpg';
//            $command = 'ffmpeg -i ' . $inputPath . ' -filter:v select=eq(pict_type\,I) -vsync 5 ' . $outputPath . 'scene_%02d.jpg';
        } else {
            return false;
        }
        mkdir($outputPath, 0777, TRUE);
        $resVal = 0;
        static::systemCommand($command, $resVal);
        if ($resVal == 0) {
            return $outputPath;
        } else {
            return false;
        }
    }

    /* =================================================================== */

    public static function convertAttachmentById($attachment_id, $saveOrgin = false, $profile = 'default') {
        $attachment = Attachment::findOne($attachment_id);
        if ($attachment == NULL) {
            return FALSE;
        }
        return static::convertAttachment($attachment, $saveOrgin);
    }

    /**
     * 
     * @param string $inputPath
     * @return converted image path
     */
    public static function convertAttachment($attachment, $saveOrgin = false, $profile = 'default') {
        return FALSE;
        if ($attachment->type == Attachment::TYPE_AUDIO) {
            $command = static::audioConvertProfiles($profile);
            $newName = uniqid() . '.mp3';
        } elseif ($attachment->type == Attachment::TYPE_VIDEO) {
            $command = static::videoConvertProfiles($profile);
            $newName = uniqid() . '.mp4';
        } else {
            return false;
        }
        /* ================================================================== */
        $outputPath = $inputPath = $attachment->getFullPath();
        $outputPath = str_replace($attachment->name, $newName, $outputPath);
        $command = sprintf($command, $inputPath, $outputPath);

        $resVal = 0;
//        var_dump($command);
//        die('-');
        static::systemCommand($command, $resVal);
        if ($resVal == 0) {
            $attachment->path = str_replace($attachment->name, $newName, $attachment->path);
            $attachment->name = $newName;
            if ($attachment->save()) {
                if (!$saveOrgin) {
                    unlink($inputPath);
                }
                return TRUE;
            } else {
                $notifyMessage = \Yii::t('rabint', 'فایل ضمیمه {attachment} آپلود شده اما تبدیل به فرمت استاندارد نگردید.', ['attachment' => $attachment->getFullPath()]);
                Yii::warning($notifyMessage, 'attachment');
//                Yii::$app->notify->send(
//                        null, $notifyMessage, '', ['priority' => \app\modules\notify\models\Notification::PRIORITY_LOW]
//                );
//                print_r($attachment->errors);
                //echo ('error ON save');
                return FALSE;
            }
        }
        //echo ('error on converte');
        $notifyMessage = \Yii::t('rabint', 'فایل ضمیمه {attachment} آپلود شده اما تبدیل به فرمت استاندارد نگردید.', ['attachment' => $attachment->getFullPath()]);
        Yii::warning($notifyMessage, 'attachment');
//        Yii::$app->notify->send(
//                null, $notifyMessage, '', ['priority' => \app\modules\notify\models\Notification::PRIORITY_LOW]
//        );
        return FALSE;
    }

    public static function videoConvertProfiles($profile = 'default') {
        return FALSE;
        switch ($profile) {
            case 'scale-1':
                return 'ffmpeg -i %s -codec:v libx264 -profile:v high -preset slow -b:v 360k -maxrate 424k -bufsize 720k -vf scale=-1:360 -threads 12 -b:a 64k -ac 2  %s '; //-codec:a libfdk_aac   2>&1
                break;
            case 'mono':
                return 'ffmpeg -i %s -codec:v libx264 -profile:v high -preset slow -b:v 360k -maxrate 424k -bufsize 720k -vf scale=-2:360 -threads 12 -b:a 64k  %s'; //-codec:a libfdk_aac   2>&1
            case 'default':
            default:
                return 'ffmpeg -i %s -codec:v libx264 -profile:v high -preset slow -b:v 360k -maxrate 424k -bufsize 720k -vf scale=-2:360 -threads 12 -b:a 64k -ac 2  %s'; //-codec:a libfdk_aac   2>&1
                break;
        }
    }

    public static function audioConvertProfiles($profile = 'default') {
        return FALSE;
        switch ($profile) {
            case 'default':
            default:
                return 'ffmpeg -i %s -vn -ar 44100 -ac 2 -ab 64k -f mp3 %s '; //-codec:a libfdk_aac   2>&1
                break;
        }
    }

    /**
     * 
     * @param string $command
     * @param int $return_var
     * @return string the last line of the command output on success, and <b>FALSE</b> on failure.
     */
    public static function systemCommand($command, &$status = null) {
        return FALSE;
        $dir = config('FFMPEG_BIN_DIR', dirname(dirname(Yii::getAlias('@base'))) . '\bin\ffmpeg\bin');
        if ($dir === FALSE) {
            $status = 1;
            return FALSE;
        }
        chdir($dir);
        ob_start();
        passthru($command, $status);
        $return = ob_get_clean();
//        var_dump($return);
//        echo '<br/>============================================<br/>';
//        var_dump($status);
        /* ------------------------------------------------------ */
        if ($status !== 0) {
            $logPath = Yii::getAlias('@app/runtime/logs/sysCmd.log');
            $cmdText = 'system_command_error:' . "\n"
                    . $command .
                    "\nmethod" . print_r(__METHOD__, TRUE) .
                    "\n_____________________________________________\n";
            file_put_contents($logPath, $cmdText, FILE_APPEND);
        }
        /* ------------------------------------------------------ */
        return $return;
    }

}
