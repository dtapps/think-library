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
use DtApp\ThinkLibrary\exception\PinDouDouException;
use DtApp\ThinkLibrary\Service;

/**
 * 拼多多进宝 - 网络请求接口
 * Class PinDuoDuoJinBaoService
 * @package DtApp\ThinkLibrary\service\Curl
 */
class PinDuoDuoJinBaoService extends Service
{
    /**
     * SDK版本号
     */
    public static $VERSION = "0.0.2";

    /**
     * 接口地址
     * @var
     */
    private $url = 'http://gw-api.pinduoduo.com/api/router';

    /**
     * API接口名称
     * @var
     */
    private $type;

    /**
     * 开放平台分配的clientId
     * @var
     */
    private $client_id;

    /**
     * 开放平台分配的clientSecret
     * @var
     */
    private $client_secret;

    /**
     * 通过code获取的access_token(无需授权的接口，该字段不参与sign签名运算)
     * @var
     */
    private $access_token;

    /**
     * 响应格式，即返回数据的格式，JSON或者XML（二选一），默认JSON，注意是大写
     * @var
     */
    private $data_type = 'JSON';

    /**
     * API协议版本号，默认为V1，可不填
     * @var
     */
    private $version = 'v1';

    /**
     * 需要发送的的参数
     * @var
     */
    private $param;

    /**
     * 响应内容
     * @var
     */
    private $output;

    /*
     * 配置开放平台分配的clientId
     */
    public function clientId(string $clientId)
    {
        $this->client_id = $clientId;
        return $this;
    }

    /**
     * 配置开放平台分配的clientSecret
     * @param string $clientSecret
     * @return $this
     */
    public function clientSecret(string $clientSecret)
    {
        $this->client_secret = $clientSecret;
        return $this;
    }

    /**
     * API接口名称
     * @param string $type
     * @return $this
     */
    public function type(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 响应格式，即返回数据的格式，JSON或者XML（二选一），默认JSON，注意是大写
     * @param string $dataType
     * @return $this
     */
    public function dataType(string $dataType)
    {
        $this->data_type = $dataType;
        return $this;
    }

    /**
     * 组参
     * @param array $arr
     * @return $this
     * @throws PinDouDouException
     */
    public function data(array $arr)
    {
        $this->client_id = empty($this->client_id) ? config('dtapp.pinduoduo.jinbao.client_id') : $this->client_id;
        $this->client_secret = empty($this->client_secret) ? config('dtapp.pinduoduo.jinbao.client_secret') : $this->client_secret;
        if (empty($this->client_id)) throw new PinDouDouException('请检查client_id参数');
        if (empty($this->client_secret)) throw new PinDouDouException('请检查client_secret参数');

        $arr['type'] = $this->type;
        $arr['client_id'] = $this->client_id;
        $arr['timestamp'] = time();
        $arr['data_type'] = $this->data_type;
        $arr['version'] = $this->version;
        $this->param = $arr;
        return $this;
    }

    /**
     * 网络请求
     * @throws PinDouDouException
     */
    private function http()
    {
        //生成签名
        $sign = $this->createSign();
        //组织参数
        $strParam = $this->createStrParam();
        $strParam .= 'sign=' . $sign;
        //访问服务
        $url = "{$this->url}?" . $strParam;
        // http://gw-api.pinduoduo.com/api/router?client_id=c0372aa7ffa149cbbce852e4d397a577&data_type=JSON&timestamp=1589359544&type=pdd.ddk.goods.search&version=v1&sign=358B9EAFDDE596A4F2FABBAF172D4294
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        $this->output = $result;
        return $this;
    }

    /**
     * 返回数组数据
     * @return array|mixed
     * @throws CurlException|PinDouDouException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) throw new CurlException('请开启curl模块！', E_USER_DEPRECATED);
        $this->http();
        if (is_array($this->output)) return $this->output;
        if (is_object($this->output)) return $this->object2array($this->output);
        return json_decode($this->output, true);
    }

    private function object2array(&$object)
    {
        if (is_object($object)) $arr = (array)($object);
        else $arr = &$object;
        if (is_array($arr)) foreach ($arr as $varName => $varValue) $arr[$varName] = $this->object2array($varValue);
        return $arr;
    }

    /**
     * 签名
     * @return string
     * @throws PinDouDouException
     */
    private function createSign()
    {
        $this->client_secret = empty($this->client_secret) ? config('dtapp.pinduoduo.jinbao.client_secret') : $this->client_secret;
        if (empty($this->client_secret)) throw new PinDouDouException('请检查client_secret参数');

        $sign = $this->client_secret;
        ksort($this->param);
        foreach ($this->param as $key => $val) if ($key != '' && $val != '') $sign .= $key . $val;
        $sign .= $this->client_secret;
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
