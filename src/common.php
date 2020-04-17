<?php

// +----------------------------------------------------------------------
// | ThinkLibrary 5.1 for ThinkPhP 5.1
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

use DtApp\Ip\IpException;
use DtApp\Ip\QqWry;

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
        $qqwry = new QqWry();
        return $qqwry->getLocation($ip);
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
