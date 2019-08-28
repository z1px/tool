<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2018/9/11
 * Time: 上午8:55
 */

namespace Z1px\Tool;

/**
 * 数据压缩解压
 * Class Compress
 * @package tool
 */
class Compress
{

    protected static $arr = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r',
        's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        '*', '!' ,'/', '+', '=','#'];

    /**
     * 加密压缩函数
     * @param $data
     * @param bool $arr
     * @param bool $zip
     * @return bool|string
     */
    public static function compression($data, $arr=false, $zip=true)
    {

        if(empty($arr) || !is_array($arr)) $arr = self::$arr;

        if(is_array($data)) $data = json_encode($data);
        $data = base64_encode($data);

        $encode = function ($data) use ($arr){
            if ($data == null) return '';

            $rsstr = 'x';
            $toarr = str_split($data);
            $arrlenght = count($arr);
            for ($i=0; $i < count($toarr); $i++) {
                $string = ord($toarr[$i]) + ord($arr[$i % $arrlenght]);
                $rsstr .= $string.'_';
            }

            $rsstr = substr($rsstr,0,-1);
            $rsstr .= 'y';
            return $rsstr;
        };

        $data = $encode($data);

        if($zip){
            try{
                $data = gzcompress($data,9);
            }catch(\Exception $e){
                $data = '';
            }
        }
        unset($arr,$zip);

        return $data;
    }

    /**
     * 解密解压函数
     * @param $data
     * @param bool $arr
     * @param bool $zip
     * @return bool|mixed|string
     */
    public static function decompression($data, $arr=false, $zip=true)
    {

        if(empty($arr) || !is_array($arr)) $arr = self::$arr;

        if($zip){
            try{
                $data = gzuncompress($data);
            }catch(\Exception $e){
                return '';
            }
        }

        $decode = function ($data) use ($arr){
            if ($data == '') return '';

            $first = substr($data,0,1);
            $end = substr($data,-1);

            if ($first == 'x' && $end == 'y') {
                $data = substr($data,1,-1);
                $toarr = explode('_',$data);
                $arrlenght = count($arr);
                $rsstr = '';
                for ($i=0; $i < count($toarr); $i++) {
                    $string = $toarr[$i] - ord($arr[$i % $arrlenght]);
                    $rsstr .= chr($string);
                }
                return $rsstr;
            } else {
                return '';
            }
        };

        $data = $decode($data);
        $data = base64_decode($data);
        unset($arr, $zip);

        if(!empty($data)){
            try {
                $data = json_decode($data,true) ?: $data;
            } catch (\Exception $e) {
                $data = '';
            }
        }

        return $data;
    }

}