<?php

namespace DtApp\ThinkLibrary\service;

use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\curl\HttpService;
use think\App;
use think\exception\HttpException;

/**
 * 卡商网
 * http://www.kashangwl.com/
 * Class KaShAngWl
 * @package DtApp\ThinkLibrary\service
 */
class KaShAngWl extends Service
{
    /**
     * 接口地址
     * @var string
     */
    private $api_url = 'http://www.kashangwl.com/api';

    /**
     * 商家编号、商家密钥
     * @var
     */
    private $customer_id, $customer_key;

    /**
     * 设置商家编号
     * @param string $customer_id
     * @return $this
     */
    public function setCustomerId(string $customer_id): self
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    /**
     * 设置商家密钥
     * @param string $customer_key
     * @return $this
     */
    public function setCustomerKey(string $customer_key): self
    {
        $this->customer_key = $customer_key;
        return $this;
    }

    /**
     * 待请求的链接
     * @var string
     */
    private $method = '';

    /**
     * 设置接口
     * @param $method
     * @return KaShAngWl
     */
    public function setMethod($method): self
    {
        $this->method = "{$this->api_url}/$method";
        return $this;
    }

    /**
     * 需要发送的的参数
     * @var
     */
    private $param;

    /**
     * 入参
     * @param $param
     * @return KaShAngWl
     */
    public function param($param): self
    {
        $this->param = $param;
        return $this;
    }

    /**
     * 响应内容
     * @var
     */
    private $output;

    /**
     * 时间戳
     * @var int
     */
    private $time;

    public function __construct(App $app)
    {
        $this->time = time();
        parent::__construct($app);
    }

    /**
     * @return array|mixed
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) {
            throw new HttpException(404, '请开启curl模块！');
        }
        $this->http();
        // 正常
        if (is_array($this->output)) {
            return $this->output;
        }
        if (is_object($this->output)) {
            $this->output = json_encode($this->output, JSON_UNESCAPED_UNICODE);
        }
        $this->output = json_decode($this->output, true);
        return $this->output;
    }

    /**
     * 网络请求
     */
    private function http(): void
    {
        //生成签名
        $sign = $this->createSign();
        //组织参数
        $this->param['customer_id'] = $this->customer_id;
        $this->param['timestamp'] = $this->time;
        $this->param['sign'] = $sign;
        $result = HttpService::instance()
            ->url($this->method)
            ->data($this->param)
            ->post()
            ->toArray();
        $this->output = $result;
    }

    /**
     * 签名
     * @return string
     */
    private function createSign(): string
    {
        $sign = $this->customer_key;
        $this->param['customer_id'] = $this->customer_id;
        $this->param['timestamp'] = $this->time;
        ksort($this->param);
        foreach ($this->param as $key => $val) {
            if ($key !== '' && $val !== '') {
                $sign .= $key . $val;
            }
        }
        $sign = strtolower(md5($sign));
        return $sign;
    }
}