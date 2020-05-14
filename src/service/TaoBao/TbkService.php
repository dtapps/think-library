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

namespace DtApp\ThinkLibrary\service\TaoBao;

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\exception\TaoBaoKeException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\Curl\TaoBaoKeService;

/**
 * 淘宝客
 * Class TbkService
 * @package DtApp\ThinkLibrary\service\TaoBao
 */
class TbkService extends Service
{
    private $app_key = "";
    private $app_secret = "";
    private $method;
    private $param;

    /**
     * 配置应用的AppKey
     * @param string $appKey
     * @return $this
     */
    public function appKey(string $appKey)
    {
        $this->app_key = $appKey;
        return $this;
    }

    /**
     * API接口名称
     * @param string $method
     * @return $this
     */
    public function method(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * 应用AppSecret
     * @param string $appSecret
     * @return $this
     */
    public function appSecret(string $appSecret)
    {
        $this->app_secret = $appSecret;
        return $this;
    }

    /**
     * 请求参数
     * @param array $param
     * @return $this
     */
    public function param(array $param)
    {
        $this->param = $param;
        return $this;
    }

    /**
     * 订单查询 - 淘宝客-推广者-所有订单查询
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=40173&docType=2
     * @return $this
     */
    public function orderDetailsGet()
    {
        $this->method = 'taobao.tbk.order.details.get';
        return $this;
    }

    /**
     * 返回Array
     * @return array|mixed
     * @throws CurlException
     * @throws TaoBaoKeException
     */
    public function toArray()
    {
        return TaoBaoKeService::instance()
            ->appKey($this->app_key)
            ->appSecret($this->app_secret)
            ->method($this->method)
            ->data($this->param)
            ->toArray();
    }

    /**
     * 返回Xml
     * @return mixed
     * @throws CurlException
     * @throws TaoBaoKeException
     */
    public function toXml()
    {
        return TaoBaoKeService::instance()
            ->appKey($this->app_key)
            ->appSecret($this->app_secret)
            ->method($this->method)
            ->data($this->param)
            ->toXml();
    }
}
