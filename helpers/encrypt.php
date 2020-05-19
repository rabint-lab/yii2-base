<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 11/12/18
 * Time: 1:27 PM
 */

namespace rabint\helpers;


class encrypt
{
    public static function rsaPrivateEncrypt($data, $ppk)
    {
        if (openssl_private_encrypt($data, $encrypted, $ppk)) {
            $data = base64_encode($encrypted);
        } else {
            $data = false;
        }
        return $data;
    }

    public static function rsaPrivateDecrypt($data, $ppk)
    {

        if (openssl_private_decrypt(base64_decode($data), $decrypted, $ppk)) {
            $data = $decrypted;
        } else {
            $data = false;
        }
        return $data;
    }


    public static function rsaPublicEncrypt($data, $pk)
    {
        if (openssl_public_encrypt($data, $encrypted, $pk)) {
            $data = base64_encode($encrypted);
        } else {
            $data = false;
        }
        return $data;
    }

    public static function rsaPublicDecrypt($data, $pk)
    {

        if (openssl_public_decrypt(base64_decode($data), $decrypted, $pk)) {
            $data = $decrypted;
        } else {
            $data = false;
        }
        return $data;
    }


    public static function encryptData($data, $hashKey = "General_Hash_Key3H&89ik@jd", $hashMethod = "AES-256-CBC")
    {
        //"aes-128-gcm";//"AES-256-CBC"

        $jsonData = json_encode(['h' => '', 'd' => $data]);
        $encryption_key = base64_decode($hashKey);
        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($hashMethod));
        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($jsonData, $hashMethod, $encryption_key, 0, $iv);
        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        $return = base64_encode($encrypted . '::' . $iv);

//        echo "encoded:" .print_r($return,true)."\n\n";
//
//        echo "decoded:" .print_r($this->decodeSellKey($return),true)."\n\n";

        return $return;

    }

    public static function decryptData($data, $hashKey = "General_Hash_Key3H&89ik@jd", $hashMethod = "AES-256-CBC")
    {
        $encryption_key = base64_decode($hashKey);
        // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        $jsonData = openssl_decrypt($encrypted_data, $hashMethod, $encryption_key, 0, $iv);
        if (empty($jsonData)) {
            return false;
        }
        $data = json_decode($jsonData);
        return $data->d;
    }


}