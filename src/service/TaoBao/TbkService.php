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
    /**
     * 是否为沙箱
     * @var bool
     */
    private $sandbox = false;

    /**
     * TOP分配给应用的AppKey
     * @var string
     */
    private $app_key = "";

    /**
     * TOP分配给应用的AppSecret
     * @var string
     */
    private $app_secret = "";

    /**
     * API接口名称
     * @var string
     */
    private $method = '';

    /**
     * 签名的摘要算法
     * @var string
     */
    private $sign_method = "md5";

    /**
     * 需要发送的的参数
     * @var
     */
    private $param;

    /**
     * 响应格式
     * @var string
     */
    private $format = "json";

    /**
     * API协议版本
     * @var string
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
     * API接口名称
     * @param string $signMethod
     * @return $this
     */
    public function signMethod(string $signMethod)
    {
        $this->sign_method = $signMethod;
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
        //首先检测是否支持curl
        if (!extension_loaded("curl")) throw new CurlException('请开启curl模块！', E_USER_DEPRECATED);
        $this->format = "json";
        $this->http();
        if (is_array($this->output)) return $this->output;
        if (is_object($this->output)) $this->output = json_encode($this->output);
        return json_decode($this->output, true);
    }

    /**
     * 返回Xml
     * @return mixed
     * @throws CurlException
     * @throws TaoBaoKeException
     */
    public function toXml()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) throw new CurlException('请开启curl模块！', E_USER_DEPRECATED);
        $this->format = "xml";
        $this->http();
        return $this->output;
    }

    /**
     * 网络请求
     * @throws TaoBaoKeException
     */
    private function http()
    {
        //生成签名
        $sign = $this->createSign();
        //组织参数
        $strParam = $this->createStrParam();
        $strParam .= 'sign=' . $sign;
        //访问服务
        if (empty($this->sandbox)) $url = 'http://gw.api.taobao.com/router/rest?' . $strParam;
        else $url = 'http://gw.api.tbsandbox.com/router/rest?' . $strParam;
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        $this->output = $result;
    }

    /**
     * 签名
     * @return string
     * @throws TaoBaoKeException
     */
    private function createSign()
    {
        $this->app_secret = empty($this->app_secret) ? config('dtapp.taobao.tbk.app_secret') : $this->app_secret;
        if (empty($this->app_secret)) throw new TaoBaoKeException('请检查app_secret参数');

        $sign = $this->app_secret;
        ksort($this->param);
        foreach ($this->param as $key => $val) if ($key != '' && $val != '') $sign .= $key . $val;
        $sign .= $this->app_secret;
        $sign = strtoupper(md5($sign));
        return $sign;
    }

    /**
     * 组参
     * @return string
     */
    private function createStrParam()
    {
        $strParam = '';
        foreach ($this->param as $key => $val) if ($key != '' && $val != '') $strParam .= $key . '=' . urlencode($val) . '&';
        return $strParam;
    }
}
