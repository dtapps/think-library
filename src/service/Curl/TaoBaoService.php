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

namespace DtApp\ThinkLibrary\service\Curl;

use DtApp\ThinkLibrary\exception\CurlException;

class TaoBaoService
{
    private $url;

    /**
     * 是否为沙箱
     * @var bool
     */
    private $sandbox = false;

    /**
     * API接口名称
     * @var
     */
    private $method;

    /**
     * TOP分配给应用的AppKey
     * @var
     */
    private $app_key;

    /**
     * 签名的摘要算法
     * @var
     */
    private $sign_method = "md5";

    /**
     * 响应格式
     * @var
     */
    private $format = "json";

    /**
     * API协议版本
     * @var
     */
    private $v = "2.0";

    /**
     * 响应内容
     * @var
     */
    private $output;

    /**
     * 是否为沙箱
     * @return $this
     */
    public function sandbox()
    {
        $this->sandbox = true;
        return $this;
    }

    /**
     * 配置应用的AppKey
     * @param string $str
     * @return $this
     */
    public function appKey(string $str)
    {
        $this->app_key = $str;
        return $this;
    }

    /**
     * 签名的摘要算法
     * @param string $str
     * @return $this
     */
    public function signMethod(string $str)
    {
        $this->sign_method = $str;
        return $this;
    }

    private function http()
    {

    }

    /**
     * 返回数组数据
     * @return array|mixed
     * @throws CurlException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) throw new CurlException('请开启curl模块！', E_USER_DEPRECATED);
        $this->format = "json";
        $this->http();
        if (is_array($this->output)) return $this->output;
        return json_decode($this->output, true);
    }

    /**
     * 返回Xml数据
     * @return mixed
     * @throws CurlException
     */
    public function toXml()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) throw new CurlException('请开启curl模块！', E_USER_DEPRECATED);
        $this->format = "xml";
        $this->http();
        return $this->output;
    }
}
