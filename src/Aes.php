<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019-01-03
 * Time: 20:41
 */

namespace Z1px\Tool;


/**
 * AES加密解密算法
 * Class Aes
 * @package tool
 */
class Aes
{

    /**
     * AES加密
     * @param $data
     * @param $secret_key
     * @return string
     */
    public static function encrypt($decrypted, $secret_key, $method='AES-256-CBC', $options=OPENSSL_RAW_DATA, $vi='') {
        $secret_key = md5($secret_key);
        switch ($method){
            case 'AES-256-CBC':
                if(empty($vi)){
                    $vi = substr($secret_key, 0, 16);
                }
                break;
        }
        $encrypted = openssl_encrypt($decrypted, $method, $secret_key, $options, $vi);
        return base64_encode($encrypted);
    }

    /**
     * AES解密
     * @param $data
     * @param $secret_key
     * @return string
     */
    public static function decrypt($encrypted, $secret_key, $method='AES-256-CBC', $options=OPENSSL_RAW_DATA, $vi='') {
        $secret_key = md5($secret_key);
        switch ($method){
            case 'AES-256-CBC':
                if(empty($vi)){
                    $vi = substr($secret_key, 0, 16);
                }
                break;
        }
        $encrypted = base64_decode($encrypted);
        $decrypted = openssl_decrypt($encrypted, $method, $secret_key, $options, $vi);
        return $decrypted;
    }

}
