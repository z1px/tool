<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2018/9/11
 * Time: 上午9:31
 */

namespace Z1px\Tool;

/**
 * 数据格式转换
 * Class Format
 * @package tool
 */
class Format
{

    /**
     * 用户名、邮箱、手机账号中间字符串以*隐藏
     */
    public static function hide_star($str)
    {
        if (strpos($str, '@')) {
            $email_array = explode('@', $str);
            $prevfix = (strlen($email_array[0]) < 4) ? $email_array[0] : substr($email_array[0], 0, 3); //邮箱前缀
            $nextfix = (strlen($email_array[0]) > 4) ? substr($email_array[0], -1, 1) : ''; //邮箱后缀
            $count = 0;
            $rs = preg_replace('/([\d\w+_-]{0,100})@/', $prevfix.'***'.$nextfix.'@', $str, -1, $count);
        } else {
            $pattern = '/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i';
            if (preg_match($pattern, $str)) {
                $rs = preg_replace($pattern, '$1****$2', $str); // substr_replace($name,'****',3,4);
            } else {
                $prevfix = (strlen($str) < 4) ? $str : substr($str, 0, 3); //前缀
                $nextfix = (strlen($str) > 5) ? substr($str, -2, 2) : ''; //后缀
                $rs = $prevfix. '***' . $nextfix;
            }
        }
        return $rs;
    }

    /**
     * 邮箱后缀统一转成小写
     * @param $str
     * @return array|string
     */
    public static function format_email($str)
    {
        $str = explode('@', $str);
        if(count($str) === 2){
            $str[1]=strtolower($str[1]);
        }
        $str = implode('@', $str);
        return $str;
    }

    /**
     * 二维数组根据字段进行排序
     * @params array $array 需要排序的二维数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
    public static function multi_array_sort($array, $field, $sort='SORT_DESC')
    {
        $last_names = array_column($array, $field);
        array_multisort($last_names, constant($sort), $array);
        return $array;
    }

    /**
     * 数组转query
     * @param $data
     * @return string
     */
    public static function build_query($data){
        $str='';
        if(is_array($data)){
            ksort($data);
            foreach ($data as $key=>$value){
                $str .= (empty($str) ? '' : '&' ) . $key . '=' . (is_array($value) ? '(' . static::build_query($value). ')' : urldecode(str_replace('+', '%2B', $value)));
            }
        }
        return $str;
    }

    /**
     * 获取当天开始时间的时间戳
     * @param $date
     * @return false|int
     */
    public static function start_time($date){
        return strtotime(date("Y-m-d 00:00:00", strtotime($date)));
    }

    /**
     * 获取当天结束时间的时间戳
     * @param $date
     * @return false|int
     */
    public static function end_time($date){
        return strtotime(date("Y-m-d 23:59:59", strtotime($date)));
    }

    /**
     * 下划线转驼峰
     * 思路:
     * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
     * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
     * @param $uncamelized_words
     * @param string $separator
     * @return string
     */
    public static function camelize($uncamelized_words, $separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }

    /**
     * 驼峰命名转下划线命名
     * 思路:
     * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     * @param $camelCaps
     * @param string $separator
     * @return string
     */
    public static function uncamelize($camelCaps, $separator='_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }

    /**
     * 获取字符串长度，支持中文和其他编码
     * @param $str
     * @param string $charset
     * @return int
     */
    public function strlen($str, $charset="utf-8")
    {
        if(function_exists('mb_strlen')){
            return mb_strlen($str, $charset);
        }
        // 将字符串分解为单元
        preg_match_all("/./us", $str, $match);
        // 返回单元个数
        return count($match[0]);
    }

    /**
     * 字符串截取，支持中文和其他编码
     * @param $str 需要转换的字符串
     * @param int $start 开始位置
     * @param $length 截取长度
     * @param string $charset 编码格式
     * @param bool $suffix 截断显示字符
     * @return string
     */
    public static function substr($str, $start, $length, $charset="utf-8", $suffix=false)
    {
        if(function_exists("mb_substr")){
            $slice = mb_substr($str, $start, $length, $charset);
        }elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
        }else{
            $re = [
                'utf-8' => "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/",
                'gb2312' => "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/",
                'gbk' => "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/",
                'big5' => "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/",
            ];
            preg_match_all($re[$charset], $str, $match);
            $slice = join("",array_slice($match[0], $start, $length));
        }
        return $suffix ? $slice.'...' : $slice;
    }
}