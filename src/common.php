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

use DtApp\ThinkLibrary\cache\Mysql;
use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\service\QqWryService;
use DtApp\ThinkLibrary\service\SystemService;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 定义当前版本
 */
const VERSION = '6.0.56';

if (!function_exists('get_ip_info')) {

    /**
     * 获取请求IP信息
     * @param string $ip
     * @return mixed|null
     * @throws DtaException
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
        $ip = '0.0.0.0';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //为了兼容百度的CDN，所以转成数组
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

if (!function_exists('uri')) {
    /**
     * 生成最短 URL 地址
     * @param string $url 路由地址
     * @param array $vars PATH 变量
     * @param boolean|string $suffix 后缀
     * @param boolean|string $domain 域名
     * @param boolean|string $fillSuffix 补上后缀
     * @return string
     */
    function uri($url = '', array $vars = [], $suffix = true, $domain = false, $fillSuffix = false)
    {
        return SystemService::instance()->uri($url, $vars, $suffix, $domain, $fillSuffix);
    }
}

if (!function_exists('dtacache')) {
    /**
     * 缓存
     * @param string $name
     * @param array $value
     * @param int $expire
     * @return bool|int|string
     * @throws DataNotFoundException
     * @throws DbException
     * @throws DtaException
     * @throws ModelNotFoundException
     */
    function dtacache($name = '', $value = [], $expire = 6000)
    {
        $myc = new Mysql();
        if (empty($value)) {
            return $myc->name($name)
                ->get();
        } else {
            if (empty($myc->name($name)
                ->get())) {
                $myc->name($name)
                    ->expire($expire)
                    ->set($value);
            } else {
                $myc->name($name)
                    ->expire($expire)
                    ->update($value);
            }
            return $myc->name($name)
                ->get();
        }
    }
}
