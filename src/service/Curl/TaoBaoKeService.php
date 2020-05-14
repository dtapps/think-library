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
use DtApp\ThinkLibrary\exception\TaoBaoException;
use DtApp\ThinkLibrary\Service;

/**
 * 淘宝联盟 - 网络请求接口
 * Class TaoBaoKeService
 * @package DtApp\ThinkLibrary\service\Curl
 */
class TaoBaoKeService extends Service
{
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
    private $app_key = "";

    /**
     * TOP分配给应用的AppSecret
     * @var
     */
    private $app_secret = "";

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
     * 需要发送的的参数
     * @var
     */
    private $param;

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
     * @param string $method
     * @return $this
     */
    public function method(string $method)
    {
        $this->method = $method;
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
     * 组参
     * @param array $arr
     * @return $this
     * @throws TaoBaoException
     */
    public function data(array $arr)
    {
        $this->app_key = empty($this->app_key) ? config('dtapp.taobao.tbk.app_key') : $this->app_key;
        if (empty($this->app_key)) throw new TaoBaoException('请检查app_key参数');
        if (empty($this->method)) throw new TaoBaoException('请检查method参数');

        $arr['app_key'] = $this->app_key;
        $arr['method'] = $this->method;
        $arr['format'] = $this->format;
        $arr['v'] = $this->v;
        $arr['sign_method'] = $this->sign_method;
        $arr['timestamp'] = date('Y-m-d H:i:s');
        $this->param = $arr;
        return $this;
    }

    /**
     * 组参
     * @param string $value
     * @param string $name
     * @return $this
     * @throws TaoBaoException
     */
    public function datas(string $value, string $name = "requests")
    {
        $this->app_key = empty($this->app_key) ? config('dtapp.taobao.tbk.app_key') : $this->app_key;
        if (empty($this->app_key)) throw new TaoBaoException('请检查app_key参数');
        if (empty($this->method)) throw new TaoBaoException('请检查method参数');

        $arr = [];
        $arr['app_key'] = $this->app_key;
        $arr['method'] = $this->method;
        $arr['format'] = $this->format;
        $arr['v'] = $this->v;
        $arr['sign_method'] = $this->sign_method;
        $arr['timestamp'] = date('Y-m-d H:i:s');
        $arr[$name] = $value;
        $this->param = $arr;
        return $this;
    }

    /**
     * 网络请求
     * @throws TaoBaoException
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
     * 返回数组数据
     * @return array|mixed
     * @throws CurlException|TaoBaoException
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
     * 返回Xml数据
     * @return mixed
     * @throws CurlException|TaoBaoException
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
     * 签名
     * @return string
     * @throws TaoBaoException
     */
    private function createSign()
    {
        $this->app_secret = empty($this->app_secret) ? config('dtapp.taobao.tbk.app_secret') : $this->app_secret;
        if (empty($this->app_secret)) throw new TaoBaoException('请检查app_secret参数');

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
