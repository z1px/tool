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
        if(($_SERVER['HTTP_VIA'] ?? '') && stristr($_SERVER['HTTP_VIA'] ?? '', "wap")) {
            return true;
        }elseif(($_SERVER['HTTP_ACCEPT'] ?? '') && strpos(strtoupper($_SERVER['HTTP_ACCEPT'] ?? ''), "VND.WAP.WML")) {
            return true;
        }elseif(($_SERVER['HTTP_X_WAP_PROFILE'] ?? '') || ($_SERVER['HTTP_PROFILE'] ?? '')) {
            return true;
        }elseif(($_SERVER['HTTP_USER_AGENT'] ?? '') && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
        return false;
    }

    /**
     * 检测是否是微信
     *
     * @return bool
     */
    public static function isWeixin()
    {
        return preg_match('/MicroMessenger/i', $_SERVER['HTTP_USER_AGENT'] ?? 'unknow');
    }

    /**
     * 获取客户端信息
     * @return array
     * platform 客户端平台
     * model 设备型号
     * os 设备系统
     * version 设备系统版本
     */
    public static function getAgent()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknow'; // 获取用户代理字符串

        $platform = ''; // 客户端平台
        $model = ''; // 设备型号
        $os = ''; // 设备系统
        $version = ''; // 设备系统版本

        //window系统
        if (preg_match('/window/i', $user_agent)) {
            $platform = 'windows';
            $os = 'Windows';
            if (preg_match('/x64/i', $user_agent)) {
                $os .= '(x64)';
            }elseif(preg_match('/x32/i', $user_agent)){
                $os .= '(x32)';
            }
            if(preg_match('/nt 10.0/i', $user_agent)) {
                $version = '10';
            }elseif(preg_match('/nt 6.3/i', $user_agent)) {
                $version = '8.1';
            }elseif(preg_match('/nt 6.2/i', $user_agent)) {
                $version = '8.0';
            }elseif(preg_match('/nt 6.1/i', $user_agent)) {
                $version = '7';
            }elseif (preg_match('/nt 6.0/i', $user_agent)) {
                $version = 'Vista';
            }elseif(preg_match('/nt 5.1/i', $user_agent)) {
                $version = 'XP';
            }elseif(preg_match('/nt 5/i', $user_agent)) {
                $version = '2000';
            }elseif(preg_match('/nt 98/i', $user_agent)) {
                $version = '98';
            }elseif(preg_match('/nt 95/i', $user_agent)) {
                $version = '95';
            }elseif(preg_match('/nt 32/i', $user_agent)) {
                $version = '32';
            }elseif(preg_match('/nt/i', $user_agent)) {
                $version = 'nt';
            }else{
                $version = '';
            }
            $model = $os . ($version ? " {$version}" : '');
        }elseif(preg_match('/linux/i', $user_agent)) {
            if (preg_match('/android/i', $user_agent)) {
                preg_match('/android\s([\d\.]+)/i', $user_agent, $matches);
                $platform = 'android';
                $os = 'Android';
                $version = $matches[1] ?? '';
            }else{
                $platform = 'linux';
                $os = 'Linux';
                $version = '';
            }
            $model = $os . ($version ? " {$version}" : '');
        }elseif(preg_match('/unix/i', $user_agent)) {
            $platform = 'unix';
            $os = 'Unix';
            $version = '';
            $model = $os . ($version ? " {$version}" : '');
        }elseif(preg_match('/iPhone|iPad|iPod/i', $user_agent)) {
            preg_match('/OS\s([0-9_\.]+)/i', $user_agent, $matches);
            $platform = 'ios';
            $os = 'IOS';
            $version = str_replace('_','.', $matches[1] ?? '');
            if(preg_match('/iPhone/i', $user_agent)){
                $model = 'iPhone';
            }elseif(preg_match('/iPad/i', $user_agent)){
                $model = 'iPad';
            }elseif(preg_match('/iPod/i', $user_agent)){
                $model = 'iPod';
            }
        }elseif(preg_match('/Mac/i', $user_agent)) {
            preg_match('/Mac OS X\s([0-9_\.]+)/i', $user_agent, $matches);
            $platform = 'osx';
            $model = 'Mac';
            $os = 'Mac OS X';
            $version = str_replace('_','.', $matches[1] ?? '');
        }else {
            $platform = 'unknow';
            $model = 'unknow';
            $os = 'unknow';
        }
        unset($user_agent);

        return [
            'platform' => $platform,
            'model' => $model,
            'os' => $os,
            'version' => $version,
        ];
    }

    /**
     * 获取客户端操作系统信息
     */
    public static function getOS()
    {
        extract(static::getAgent());
        return $os . ($version ? " {$version}" : '');
    }

    /**
     * 获取客户端平台
     */
    public static function getPlatform()
    {
        extract(static::getAgent());
        return $platform;
    }

    /**
     * 获取客户端设备型号
     *
     * @return string
     */
    public static function getModel() {
        extract(static::getAgent());
        return $model;
    }

    /**
     * 获取客户端浏览器信息
     *
     * @return string
     */
    public static function getBrowser(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknow'; // 获取用户代理字符串
        if(preg_match('/Firefox\/([^;)]+)+/i', $user_agent, $matches)) {
            $browser = 'Firefox';
            $version = $matches[1] ?? '';  //获取火狐浏览器的版本号
        }elseif(preg_match('/Maxthon\/([\d\.]+)/', $user_agent, $matches)) {
            $browser = '傲游';
            $version = $matches[1] ?? '';
        }elseif(preg_match('/MSIE\s+([^;)]+)+/i', $user_agent, $matches)) {
            $browser = 'IE';
            $version = $matches[1] ?? '';  //获取IE的版本号
        }elseif(preg_match('/OPR\/([\d\.]+)/', $user_agent, $matches)) {
            $browser = 'Opera';
            $version = $matches[1] ?? '';
        }elseif(preg_match('/Edge\/([\d\.]+)/', $user_agent, $matches)) {
            //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
            $browser = 'Edge';
            $version = $matches[1] ?? '';
        }elseif(preg_match('/Safari\/([\d\.]+)/', $user_agent, $matches)) {
            $browser = 'Safari';
            $version = $matches[1] ?? '';  //获取google chrome的版本号
        }elseif(preg_match('/Chrome\/([\d\.]+)/', $user_agent, $matches)) {
            $browser = 'Chrome';
            $version = $matches[1] ?? '';  //获取google chrome的版本号
        }elseif(preg_match('/rv:([\d\.]+)/', $user_agent, $matches)) {
            $browser = 'IE';
            $version = $matches[1] ?? '';
        }else{
            $browser = 'unknow';
            $version = '';
        }
        unset($user_agent);

        return $browser . ($version ? "({$version})" : '');
    }
}
