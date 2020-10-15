<?php

namespace rabint\helpers;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class ftp {

    var $params = array(
        'server' => '',
        'username' => '',
        'password' => '',
        'updir' => '',
        'upurl' => '',
        'port' => 21,
    );
    var $error = array(
        '0' => 'Successfully',
        '1' => 'FTP connection or login failed!',
        '2' => 'There was a problem while creating folder',
        '3' => 'FTP upload has failed!',
        '4' => 'FTP rename has failed!',
    );
    var $latest_url = '';

    /* =================================================================== */

    function __construct($ftpOptions) {
        $this->params = array(
            'server' => $ftpOptions['Host'],
            'username' => $ftpOptions['User'],
            'password' => $ftpOptions['Pass'],
            'port' => (isset($ftpOptions['Port'])) ? $ftpOptions['Port'] : 21,
            'updir' => $ftpOptions['BaseDir'],
            'upurl' => $ftpOptions['BaseUri'],
        );
    }

    /* =================================================================== */

    function getParam($param = NULL) {
        if (empty($param)) {
            return $this->params;
        }
        return $this->params[$param];
    }

    /* =================================================================== */

    function ren($source, $distination, $overwrite = false) {
        $port = empty($this->params['port']) ? 21 : $this->params['port'];
        $conn_id = ftp_connect($this->params['server'], $port);
        $login_result = ftp_login($conn_id, $this->params['username'], $this->params['password']);
        if ((!$conn_id ) || (!$login_result )) {
            return 1;
        }
        $distination = $this->params['updir'] . '/' . $distination;
//        $source = $this->params['updir'] .'/'. $source;
        if (!$this->mkdir_recusive($conn_id, dirname($distination))) {
            return 2;
        }
        if (!$overwrite) {
            $distination = $this->rename_if_exist($conn_id, $distination);
        }
        $renamed = @ftp_rename($conn_id, $source, $distination);
        if (!$renamed) {
            return 4;
        }
        $this->latest_url = $this->params['upurl'] . $distination;
        return 0;
    }

    function send($distination, $file, $overwrite = false) {
        $port = empty($this->params['port']) ? 21 : $this->params['port'];
        $conn_id = ftp_connect($this->params['server'], $port);
        $login_result = ftp_login($conn_id, $this->params['username'], $this->params['password']);
        if ((!$conn_id ) || (!$login_result )) {
            return 1;
        }
        $up_distination = $this->params['updir'] . '/' . $distination;
        if (!$this->mkdir_recusive($conn_id, dirname($up_distination))) {
            return 2;
        }
        if (!$overwrite) {
            $up_distination = $this->rename_if_exist($conn_id, $up_distination);
        }
        $upload = ftp_put($conn_id, $up_distination, $file, FTP_BINARY);
        if (!$upload) {
            return 3;
        }
        $this->latest_url = $this->params['upurl'] . '/' . $distination;
        return 0;
    }

    function send_content($distination, $data, $overwrite = false) {
        $port = empty($this->params['port']) ? 21 : $this->params['port'];
        $conn_id = ftp_connect($this->params['server'], $port);
        $login_result = ftp_login($conn_id, $this->params['username'], $this->params['password']);
        if ((!$conn_id ) || (!$login_result )) {
            return 1;
        }
        $up_distination = $this->params['updir'] . '/' . $distination;
        if (!$this->mkdir_recusive($conn_id, dirname($up_distination))) {
            return 2;
        }
        if (!$overwrite) {
            $up_distination = $this->rename_if_exist($conn_id, $up_distination);
        }
        $upload = ftp_put($conn_id, $up_distination, 'data://text/plain;base64,' . base64_encode($data), FTP_BINARY);
        if (!$upload) {
            return 3;
        }
        $this->latest_url = $this->params['upurl'] . '/' . $distination;
        return 0;
    }

    function get_content($file) {
        $file = $this->params['updir'] . '/' . $file;
        $conn = "ftp://{{$this->params['username']}:{$this->params['password']}@{$this->params['server']}";
        $fieContent = @file_get_contents("{$conn}{$file}");
        return $fieContent;
    }

    function get($file, $distination) {
        $port = empty($this->params['port']) ? 21 : $this->params['port'];
        $conn_id = ftp_connect($this->params['server'], $port);
        $login_result = ftp_login($conn_id, $this->params['username'], $this->params['password']);
        if ((!$conn_id ) || (!$login_result )) {
            return 1;
        }
        $file = $this->params['updir'] . '/' . $file;


        $download = ftp_get($conn_id, $distination, $file, FTP_BINARY);
        if (!$download) {
            return 3;
        }
        return $distination;
    }

    protected function rename_if_exist($connetion, $distination) {
        $dir = dirname($distination);
        $file = basename($distination);
        $name = pathinfo($file, PATHINFO_FILENAME);
        $extention = pathinfo($file, PATHINFO_EXTENSION);

        $list = ftp_nlist($connetion, $dir);
        $i = 0;
        while (array_search($file, $list)) {
            $file = $name . '_' . ( ++$i) . '.' . $extention;
        }
        return $dir . '/' . $file;
    }

    function mkdir_recusive($conn_id, $path) {
        $parts = explode("/", $path);
        $return = true;
        $fullpath = "";
        foreach ($parts as $part) {
            if (empty($part)) {
                $fullpath .= "/";
                continue;
            }
            $fullpath .= $part . "/";
            if (@ftp_chdir($conn_id, $fullpath)) {
                ftp_chdir($conn_id, $fullpath);
            } else {
                if (@ftp_mkdir($conn_id, $part)) {
                    ftp_chdir($conn_id, $part);
                } else {
                    $return = false;
                }
            }
        }
        return $return;
    }

    public static function getFile($address,$downloadPath='webinar',$destination=null){
        $curl = curl_init();
        
        $fileName = ($destination?$destination:basename($address));
        $fileAddress = $downloadPath.'/'.$fileName;
        if(!is_dir(dirname($fileAddress)))mkdir(dirname($fileAddress),755);
        if(file_exists($fileAddress)){
            unlink($fileAddress);
        }
        $file = fopen($fileAddress, 'w');
        curl_setopt($curl, CURLOPT_URL, $address); #input
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FILE, $file); #output
        curl_exec($curl);
        curl_close($curl);
        fclose($file);
        return $fileAddress;
    }
}
