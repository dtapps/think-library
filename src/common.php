<?php

// +----------------------------------------------------------------------
// | ThinkLibrary 6.0 for ThinkPhP 6.0
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 [ https://www.dtapp.net ]
// +----------------------------------------------------------------------
// | 官方网站: https://gitee.com/liguangchun/ThinkLibrary
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 仓库地址 ：https://gitee.com/liguangchun/ThinkLibrary
// | github 仓库地址 ：https://github.com/GC0202/ThinkLibrary
// | Packagist 地址 ：https://packagist.org/packages/liguangchun/think-library
// +----------------------------------------------------------------------

use DtApp\ThinkLibrary\exception\IpException;
use DtApp\ThinkLibrary\service\ip\QqWryService;

if (!function_exists('get_ip_info')) {

    /**
     * 获取请求IP信息
     * @param string $ip
     * @return string
     * @throws IpException
     */
    function get_ip_info($ip = '')
    {
        if (empty($ip)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                //为了兼容百度的CDN，所以转成数组
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ip = $arr[0];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }
        return QqWryService::instance()->getLocation($ip);
    }
}

if (!function_exists('get_ip')) {

    /**
     * 获取请求IP
     * @return string
     */
    function get_ip()
    {
        $ip = '';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //为了兼容百度的CDN，所以转成数组
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = $arr[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

if (!function_exists('getEnid')) {

    /**
     * 10进制转化36进制
     * https://blog.csdn.net/zhangchb/article/details/78855083
     * @param int $format
     * @return mixed|string
     */
    function getEnid($format = 8)
    {
        $dic = array(
            0 => '0', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9',
            10 => 'A', 11 => 'B', 12 => 'C', 13 => 'D', 14 => 'E', 15 => 'F', 16 => 'G', 17 => 'H', 18 => 'I',
            19 => 'J', 20 => 'K', 21 => 'L', 22 => 'M', 23 => 'N', 24 => 'O', 25 => 'P', 26 => 'Q', 27 => 'R',
            28 => 'S', 29 => 'T', 30 => 'U', 31 => 'V', 32 => 'W', 33 => 'X', 34 => 'Y', 35 => 'Z'
        );
        $int = session('user.id');
        $arr = array();
        $loop = true;
        while ($loop) {
            $arr[] = $dic[bcmod($int, 36)];
            $int = floor(bcdiv($int, 36));
            if ($int == 0) $loop = false;
        }
        array_pad($arr, $format, $dic[0]);
        return implode('', array_reverse($arr));
    }
}

