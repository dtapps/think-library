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
// | gitlab 仓库地址 ：https://gitlab.com/liguangchun/thinklibrary
// | weixin 仓库地址 ：https://git.weixin.qq.com/liguangchun/ThinkLibrary
// | huaweicloud 仓库地址 ：https://codehub-cn-south-1.devcloud.huaweicloud.com/composer00001/ThinkLibrary.git
// | Packagist 地址 ：https://packagist.org/packages/liguangchun/think-library
// +----------------------------------------------------------------------

namespace DtApp\ThinkLibrary\service;

use DtApp\ThinkLibrary\Service;
use Exception;

/**
 * 系统服务
 * Class SystemService
 * @package DtApp\ThinkLibrary\service
 */
class SystemService extends Service
{
    /**
     * 生成最短URL地址
     * @param string $url 路由地址
     * @param array $vars PATH 变量
     * @param boolean|string $suffix 后缀
     * @param boolean|string $domain 域名
     * @param boolean|string $fillSuffix 补上后缀
     * @return string
     */
    public function uri($url = '', array $vars = [], $suffix = true, $domain = false, $fillSuffix = false): string
    {
        $default_app = config('app.default_app', 'index');
        $default_action = config('route.default_action', 'index');
        $default_controller = config('route.default_controller', 'Index');
        $url_html_suffix = config('route.url_html_suffix', 'html');
        $pathinfo_depr = config('route.pathinfo_depr', '/');
        $url_common_param = config('route.url_common_param', true);
        if (empty($url)) {
            $url = "{$default_app}/{$default_action}/{$default_controller}";
        }
        if (empty($suffix) && !empty($fillSuffix)) {
            if (empty($url_common_param)) {
                $location = $this->app->route->buildUrl($url, $vars)->suffix($suffix)->domain($domain)->build();
            } else {
                $location = $this->app->route->buildUrl($url, [])->suffix($suffix)->domain($domain)->build();
            }
            if (empty($vars)) {
                $location = substr($location . ($pathinfo_depr) . $this->arr_to_str($vars, $pathinfo_depr), 0, -1) . ".{$url_html_suffix}";
            } else {
                $location .= ($pathinfo_depr) . $this->arr_to_str($vars, $pathinfo_depr) . ".{$url_html_suffix}";
            }
        } else {
            $location = $this->app->route->buildUrl($url, $vars)->suffix($suffix)->domain($domain)->build();
        }
        return $location;
    }

    /**
     * 二维数组转化为字符串，中间用,隔开
     * @param $arr
     * @param string $glue
     * @return false|string
     */
    private function arr_to_str($arr, $glue = "/")
    {
        $t = '';
        foreach ($arr as $k => $v) {
            $t .= $k . $glue . $v . $glue;
        }
        $t = substr($t, 0, -1); // 利用字符串截取函数消除最后一个
        return $t;
    }

    /**
     * @var array
     */
    private $result = [];

    /**
     * 第一个mac地址
     * @var
     */
    private $macAddr;

    /**
     * 获取电脑MAC地址
     * @return mixed
     */
    public function mac()
    {
        switch (strtolower(PHP_OS)) {
            case "solaris":
            case "aix":
            case 'unix':
                break;
            case "linux":
                $this->getLinux();
                break;
            default:
                $this->getWindows();
                break;
        }
        $tem = array();
        foreach ($this->result as $val) {
            if (preg_match("/[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f]/i", $val, $tem)) {
                $this->macAddr = $tem[0];//多个网卡时，会返回第一个网卡的mac地址，一般够用。
                break;
                //$this->macAddrs[] = $temp_array[0];//返回所有的mac地址
            }
        }
        unset($temp_array);
        return $this->macAddr;
    }

    /**
     * Linux系统
     * @return array
     */
    private function getLinux()
    {
        @exec("ifconfig -a", $this->result);
        return $this->result;
    }

    /**
     * Windows系统
     * @return array
     */
    private function getWindows(): array
    {
        @exec("ipconfig /all", $this->result);
        if ($this->result) {
            return $this->result;
        }

        $ipconfig = $_SERVER["WINDIR"] . "\system32\ipconfig.exe";
        if (is_file($ipconfig)) {
            @exec($ipconfig . " /all", $this->result);
            return $this->result;
        }

        @exec($_SERVER["WINDIR"] . "\system\ipconfig.exe /all", $this->result);
        return $this->result;
    }

    /**
     * 获取Linux服务器IP
     * @return string
     */
    public function linuxIp()
    {
        try {
            $ip_cmd = "ifconfig eth0 | sed -n '/inet addr/p' | awk '{print $2}' | awk -F ':' '{print $2}'";
            return trim(exec($ip_cmd));
        } catch (Exception $e) {
            return "0.0.0.0";
        }
    }
}
