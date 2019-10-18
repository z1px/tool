<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/10/18
 * Time: 11:00 上午
 */


namespace Z1px\Tool;


class Server
{
    /**
     * 检测是否使用手机访问
     *
     * @return bool
     */
    public static function isMobile()
    {
        if (($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'] ?? '', "wap")) {
            return true;
        } elseif (($_SERVER['HTTP_ACCEPT'] ?? '') && strpos(strtoupper($_SERVER['HTTP_ACCEPT'] ?? ''), "VND.WAP.WML")) {
            return true;
        } elseif (($_SERVER['HTTP_X_WAP_PROFILE'] ?? '') || ($_SERVER['HTTP_PROFILE'] ?? '')) {
            return true;
        } elseif (($_SERVER['HTTP_USER_AGENT'] ?? '') && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
        return false;
    }

    /**
     * 获取客户端操作系统信息包括win10
     *
     * @return string
     */
    public static function getOs()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknow'; // 获取用户代理字符串
        if (preg_match('/win/i', $user_agent) && strpos($user_agent, '95')){
            $os = 'Windows 95';
        }else if (preg_match('/win 9x/i', $user_agent) && strpos($user_agent, '4.90')){
            $os = 'Windows ME';
        }else if (preg_match('/win/i', $user_agent) && preg_match('/98/i', $user_agent)){
            $os = 'Windows 98';
        }else if (preg_match('/win/i', $user_agent) && preg_match('/nt 6.0/i', $user_agent)){
            $os = 'Windows Vista';
        }else if (preg_match('/win/i', $user_agent) && preg_match('/nt 6.1/i', $user_agent)){
            $os = 'Windows 7';
        }else if (preg_match('/win/i', $user_agent) && preg_match('/nt 6.2/i', $user_agent)){
            $os = 'Windows 8';
        }else if(preg_match('/win/i', $user_agent) && preg_match('/nt 10.0/i', $user_agent)){
            $os = 'Windows 10';#添加win10判断
        }else if (preg_match('/win/i', $user_agent) && preg_match('/nt 5.1/i', $user_agent)){
            $os = 'Windows XP';
        }else if (preg_match('/win/i', $user_agent) && preg_match('/nt 5/i', $user_agent)){
            $os = 'Windows 2000';
        }else if (preg_match('/win/i', $user_agent) && preg_match('/nt/i', $user_agent)){
            $os = 'Windows NT';
        }else if (preg_match('/win/i', $user_agent) && preg_match('/32/i', $user_agent)){
            $os = 'Windows 32';
        }else if (preg_match('/linux/i', $user_agent)){
            $os = 'Linux';
        }else if (preg_match('/unix/i', $user_agent)){
            $os = 'Unix';
        }else if (preg_match('/sun/i', $user_agent) && preg_match('/os/i', $user_agent)){
            $os = 'SunOS';
        }else if (preg_match('/ibm/i', $user_agent) && preg_match('/os/i', $user_agent)){
            $os = 'IBM OS/2';
        }else if (preg_match('/Mac/i', $user_agent) && preg_match('/PC/i', $user_agent)){
            $os = 'Macintosh';
        }else if (preg_match('/PowerPC/i', $user_agent)){
            $os = 'PowerPC';
        }else if (preg_match('/AIX/i', $user_agent)){
            $os = 'AIX';
        }else if (preg_match('/HPUX/i', $user_agent)){
            $os = 'HPUX';
        }else if (preg_match('/NetBSD/i', $user_agent)){
            $os = 'NetBSD';
        }else if (preg_match('/BSD/i', $user_agent)){
            $os = 'BSD';
        }else if (preg_match('/OSF1/i', $user_agent)){
            $os = 'OSF1';
        }else if (preg_match('/IRIX/i', $user_agent)){
            $os = 'IRIX';
        }else if (preg_match('/FreeBSD/i', $user_agent)){
            $os = 'FreeBSD';
        }else if (preg_match('/teleport/i', $user_agent)){
            $os = 'teleport';
        }else if (preg_match('/flashget/i', $user_agent)){
            $os = 'flashget';
        }else if (preg_match('/webzip/i', $user_agent)){
            $os = 'webzip';
        }else if (preg_match('/offline/i', $user_agent)){
            $os = 'offline';
        }else{
            $os = $user_agent;
        }
        unset($user_agent);

        return $os;
    }

    /**
     * 获取访问设备
     *
     * @return string
     */
    public static function getDevice() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknow'; // 获取用户代理字符串
        if (strpos($user_agent, 'windows nt')) {
            $platform = 'windows';
        } elseif (strpos($user_agent, 'macintosh')) {
            $platform = 'mac';
        } elseif (strpos($user_agent, 'ipod')) {
            $platform = 'ipod';
        } elseif (strpos($user_agent, 'ipad')) {
            $platform = 'ipad';
        } elseif (strpos($user_agent, 'iphone')) {
            $platform = 'iphone';
        } elseif (strpos($user_agent, 'android')) {
            $platform = 'android';
        } elseif (strpos($user_agent, 'unix')) {
            $platform = 'unix';
        } elseif (strpos($user_agent, 'linux')) {
            $platform = 'linux';
        } else {
            $platform = 'other';
        }
        unset($user_agent);

        return $platform;
    }

    /**
     * 获取客户端浏览器信息 添加win10 edge浏览器判断
     *
     * @return string
     */
    public static function getBroswer(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknow'; // 获取用户代理字符串
        if (stripos($user_agent, 'Firefox/') > 0) {
            preg_match('/Firefox\/([^;)]+)+/i', $user_agent, $b);
            $exp[0] = 'Firefox';
            $exp[1] = $b[1];  //获取火狐浏览器的版本号
        } elseif (stripos($user_agent, 'Maxthon') > 0) {
            preg_match('/Maxthon\/([\d\.]+)/', $user_agent, $aoyou);
            $exp[0] = '傲游';
            $exp[1] = $aoyou[1];
        } elseif (stripos($user_agent, 'MSIE') > 0) {
            preg_match('/MSIE\s+([^;)]+)+/i', $user_agent, $ie);
            $exp[0] = 'IE';
            $exp[1] = $ie[1];  //获取IE的版本号
        } elseif (stripos($user_agent, 'OPR') > 0) {
            preg_match('/OPR\/([\d\.]+)/', $user_agent, $opera);
            $exp[0] = 'Opera';
            $exp[1] = $opera[1];
        } elseif(stripos($user_agent, 'Edge') > 0) {
            //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
            preg_match('/Edge\/([\d\.]+)/', $user_agent, $Edge);
            $exp[0] = 'Edge';
            $exp[1] = $Edge[1];
        } elseif (stripos($user_agent, 'Chrome') > 0) {
            preg_match('/Chrome\/([\d\.]+)/', $user_agent, $google);
            $exp[0] = 'Chrome';
            $exp[1] = $google[1];  //获取google chrome的版本号
        } elseif(stripos($user_agent,'rv:')>0 && stripos($user_agent,'Gecko')>0){
            preg_match('/rv:([\d\.]+)/', $user_agent, $IE);
            $exp[0] = 'IE';
            $exp[1] = $IE[1];
        }else {
            $exp[0] = '未知浏览器';
            $exp[1] = '';
        }
        unset($user_agent);

        return $exp[0].'('.$exp[1].')';
    }
}
