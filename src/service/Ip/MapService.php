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

namespace DtApp\ThinkLibrary\service\Ip;

use DtApp\ThinkLibrary\exception\IpException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\curl\HttpService;

/**
 * IP  - 地图
 * Class MapService
 * @package DtApp\ThinkLibrary\service\ip
 */
class MapService extends Service
{
    /**
     * 开发密钥
     * @var
     */
    private $key;

    /**
     * 开发密钥
     * @var
     */
    private $ak;

    /**
     * 需要查询的IP
     * @var
     */
    private $ip;

    /**
     * 配置腾讯地图或高德地图Key
     * @param $str
     * @return $this
     */
    public function key(string $str)
    {
        $this->key = $str;
        return $this;
    }

    /**
     * 配置百度地图ak
     * @param $str
     * @return $this
     */
    public function ak(string $str)
    {
        $this->ak = $str;
        return $this;
    }

    /**
     * 查询指定IP
     * @param string $str
     * @return $this
     */
    public function ip(string $str)
    {
        $this->ip = $str;
        return $this;
    }

    /**
     * 腾讯地图
     * https://lbs.qq.com/webservice_v1/guide-ip.html
     * @param string $output
     * @return array|bool|mixed|string
     * @throws IpException
     */
    public function qq(string $output = 'JSON')
    {
        if (empty($this->key)) throw new IpException('开发密钥不能为空');
        if (empty($this->ip)) $this->ip = get_ip();
        $url = "https://apis.map.qq.com/ws/location/v1/ip?key={$this->key}&output={$output}";
        if (!empty($this->ip)) $url = "https://apis.map.qq.com/ws/location/v1/ip?key={$this->key}&ip={$this->ip}&output={$output}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 百度地图
     * http://lbsyun.baidu.com/index.php?title=webapi/ip-api
     * @param string $coor
     * @return array|bool|mixed|string
     * @throws IpException
     */
    public function baidu(string $coor = 'bd09ll')
    {
        if (empty($this->ak)) throw new IpException('开发者密钥不能为空');
        if (empty($this->ip)) $this->ip = get_ip();
        $url = "https://api.map.baidu.com/location/ip?ak={$this->ak}&coor={$coor}";
        if (!empty($this->ip)) $url = "https://api.map.baidu.com/location/ip?ak={$this->ak}&ip={$this->ip}&coor={$coor}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 高德地图
     * https://lbs.amap.com/api/webservice/guide/api/ipconfig
     * @param string $output
     * @return array|bool|mixed|string
     * @throws IpException
     */
    public function amap(string $output = 'JSON')
    {
        if (empty($this->key)) throw new IpException('开发密钥不能为空');
        if (empty($this->ip)) $this->ip = get_ip();
        $url = "https://restapi.amap.com/v3/ip?parameters&key={$this->key}&output={$output}";
        if (!empty($this->ip)) $url = "https://restapi.amap.com/v3/ip?key={$this->key}&ip={$this->ip}&output={$output}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }
}
