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

if (!function_exists('auth')) {
    /**
     * 访问权限检查
     * @param string $node
     * @return boolean
     */
    function auth($node)
    {
        return $node;
    }
}

if (!function_exists('sysconf')) {

    /**
     * 获取或配置系统参数
     * @param string $name 参数名称
     * @param string $value 参数内容
     * @return mixed
     */
    function sysconf($name = '', $value = null)
    {
        if (is_null($value) && is_string($name)) {
            return cache($name);
        } else {
            return cache($name, $value);
        }
    }
}

if (!function_exists('sysdata')) {

    /**
     * JSON 数据读取与存储
     * @param string $name 数据名称
     * @param mixed $value 数据内容
     * @return mixed
     */
    function sysdata($name = '', $value = null)
    {
        if (is_null($value) && is_string($name)) {
            return cache($name);
        } else {
            return cache($name, $value);
        }
    }
}
