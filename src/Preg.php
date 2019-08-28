<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2018/10/15
 * Time: 下午2:16
 */

namespace Z1px\Tool;

/**
 * 常用正则表达式
 * Class Preg
 * @package tool
 */
class Preg
{
    /**
     * 提取html中的图片地址
     * @param $string
     * @return mixed
     */
    public static function extract_img_src($string)
    {
        if(is_string($string) && !empty($string)){
            $preg_img="/<img.*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/i";
            preg_match_all($preg_img, $string, $match);
        }else{
            $match = [];
        }
        
        return $match;
    }
}