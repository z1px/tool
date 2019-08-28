<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2018/9/11
 * Time: 上午8:52
 */

namespace Z1px\Tool;

/**
 * 格式验证
 * Class Verify
 * @package tool
 */
class Verify
{

    /**
     * 检测手机格式
     * @param $mobile
     * @return bool
     */
    public static function mobile($mobile)
    {
        preg_match('/^1[0-9]{10}$/', $mobile, $matches);
        return empty($matches) ? false : true;
    }

    /**
     * 检测邮箱格式
     * @param $email
     * @return bool
     */
    public static function email($email)
    {
        //preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i', $email, $matches);
        //return empty($matches) ? false : true;
        return filter_var($email,FILTER_VALIDATE_EMAIL) ? true : false;
    }

    /**
     * 检验身份证号码是否正确
     * @param $idcard
     * @return bool
     */
    public static function idcard($idcard)
    {
        $idcard = strtoupper($idcard);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if(!preg_match($regx, $idcard)){
            return false;
        }

        //检查15位
        if(15 === strlen($idcard)){
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
            preg_match($regx, $idcard, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = '19'.$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];

            return strtotime($dtm_birth) ? true : false;
        }

        //检查18位
        $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
        preg_match($regx, $idcard, $arr_split);
        $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
        if(!strtotime($dtm_birth)){ //检查生日日期是否正确
            return false;
        }
        //检验18位身份证的校验码是否正确。
        //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
        $arr_int = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $arr_ch = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
        $sign = 0;
        for ( $i = 0; $i < 17; $i++ ){
            $b = (int) $idcard{$i};
            $w = $arr_int[$i];
            $sign += $b * $w;
        }
        $n = $sign % 11;
        $val_num = $arr_ch[$n];

        return $val_num == substr($idcard,17, 1) ? true : false;
    }

}