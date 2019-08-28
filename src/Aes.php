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
    public static function encode($data, $secret_key) {
        return openssl_encrypt($data, 'AES-128-ECB', $secret_key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, '');
    }

    /**
     * AES解密
     * @param $data
     * @param $secret_key
     * @return string
     */
    public static function decode($data, $secret_key) {
        return openssl_decrypt($data, 'AES-128-ECB', $secret_key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, '');
    }

}