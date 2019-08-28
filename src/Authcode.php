<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2018/9/11
 * Time: 上午9:54
 */

namespace Z1px\Tool;

/**
 * 秘文
 * Class Authcode
 * @package tool
 */
class Authcode
{
    /**
     * authcode加密函数
     * @param $string 待加密字符串
     * @param string $key 密钥
     * @param int $expiry 密文有效期
     * @return mixed|string
     */
    public static function encode($string, $key='', $expiry=0){
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 4;

        // 密匙
        $key = md5($key ?: "3c6e0b8a9c15224a8228b9a98ca1531d");

        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? substr(md5(microtime()), -$ckey_length) : '';
        // 参与运算的密匙
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
        // 解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        $result = $keyc . str_replace('=', '', base64_encode($result));
        $result = str_replace(["+", "/"], ["-", "_"], $result); //将密文中的’+‘和’/‘转换掉，防止影响地址
        return $result;
    }

    /**
     * authcode解密函数
     * @param $string 待解密密文
     * @param string $key 密钥
     * @return bool|string
     */
    public static function decode($string, $key=''){
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 4;

        $string = str_replace(["-", "_"], ["+", "/"], $string); //将密文中的’-‘和’_‘转换回来

        // 密匙
        $key = md5($key ?: "3c6e0b8a9c15224a8228b9a98ca1531d");

        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? substr($string, 0, $ckey_length) : '';
        // 参与运算的密匙
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
        // 解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = base64_decode(substr($string, $ckey_length));
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        // 验证数据有效性，请看未加密明文的格式
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
            substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    }

    /**
     * 密码加密
     * @param $string 待加密字符串
     * @param $key 密钥
     * @return string
     */
    public static function encrypt($string, $key){
        $key = md5($key);
        $x = 0;
        $len = strlen($string);
        $l = strlen($key);
        $char='';
        $str='';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord($string{$i}) + (ord($char{$i})) % 256);
        }

        return base64_encode($str);
    }

    /**
     * 密码解密
     * @param $string 待解密密文
     * @param $key 密钥
     * @return string
     */
    public static function decrypt($string, $key){
        $key = md5($key);
        $x = 0;
        $string = base64_decode($string);
        $len = strlen($string);
        $l = strlen($key);
        $char='';
        $str='';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($string, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($string, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($string, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }

        return $str;
    }

    /**
     * 数据库密码加密
     * @param $string 待加密字符串
     * @param string $key 密钥
     * @param int $expiry 密文有效期
     * @return string
     */
    public static function pwd_encode($string, $key = '', $expiry = 0) {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 0;

        // 密匙
        $key = md5($key ?: "3c6e0b8a9c15224a8228b9a98ca1531d");

        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? substr(md5(microtime()), -$ckey_length) : '';

        $string = sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
        $result = '';
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = $j % 256;
            $result .= chr(ord($string[$i]));
        }

        return $keyc.str_replace('=', '', base64_encode($result));
    }

    /**
     * 数据库密码解密
     * @param $string 待解密密文
     * @param string $key 密钥
     * @return bool|string
     */
    public static function pwd_decode($string, $key = '') {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 0;

        // 密匙
        $key = md5($key ?: "3c6e0b8a9c15224a8228b9a98ca1531d");

        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? substr($string, 0, $ckey_length) : '';
        $string = base64_decode(substr($string, $ckey_length));
        $string_length = strlen($string);
        $result = '';
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = $j % 256;
            $result .= chr(ord($string[$i]));
        }

        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    }

    /**
     * 获取随机码
     * @param int $length 随机码长度
     * @return string
     */
    public static function rand_code($length=8) {
        // 字符集，可任意添加你需要的字符
        //	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = '';
        for ( $i = 0; $i < $length; $i++ ) {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $string .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $string .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $string;
    }

}