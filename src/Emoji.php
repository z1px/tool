<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019-03-25
 * Time: 13:51
 */


namespace Z1px\Tool;


class Emoji
{

    /**
     * 判断字符串中是否含有 emoji 表情
     * @param $str
     * @return bool
     */
    public static function have_emoji_char($str)
    {
        $mbLen = mb_strlen($str);

        $strArr = [];
        for ($i = 0; $i < $mbLen; $i++) {
            $strArr[] = mb_substr($str, $i, 1, 'utf-8');
            if (strlen($strArr[$i]) >= 4) {
                return true;
            }
        }

        return false;
    }

    /**
     * 移除字符串中的 emoji 表情
     * 判断字符串中是否含有 emoji 表情
     * @param $str
     * @return string
     */
    public static function remove_emoji_char($str)
    {
        $mbLen = mb_strlen($str);

        $strArr = [];
        for ($i = 0; $i < $mbLen; $i++) {
            $mbSubstr = mb_substr($str, $i, 1, 'utf-8');
            if (strlen($mbSubstr) >= 4) {
                continue;
            }
            $strArr[] = $mbSubstr;
        }

        return implode('', $strArr);
    }

}