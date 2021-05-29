<?php

namespace DtApp\ThinkLibrary\service\dingdanxia;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\curl\HttpService;
use think\exception\HttpException;

/**
 * 订单侠开放平台
 * Class DingDanXiaService
 * @package DtApp\ThinkLibrary\service\dingdanxia
 */
class DingDanXiaService extends Service
{
    /**
     * 接口秘钥
     * @var string
     */
    private $app_key;

    /**
     * API接口
     * @var string
     */
    private $method;

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

    /**
     * 接口秘钥，请登录后台获取
     * @param string $appKey
     * @return $this
     */
    public function appKey(string $appKey): self
    {
        $this->app_key = $appKey;
        return $this;
    }

    /**
     * 自定义接口
     * @param string $method
     * @return $this
     */
    public function setMethod($method = ''): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * 请求参数
     * @param array $param
     * @return $this
     */
    public function param(array $param): self
    {
        $this->param = $param;
        return $this;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig(): self
    {
        $this->app_key = config('dtapp.dingdanxia.app_key');
        return $this;
    }

    /**
     * 返回Array
     * @return array|mixed
     * @throws DtaException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) {
            throw new HttpException(404, '请开启curl模块！');
        }
        if (empty($this->app_key)) {
            $this->getConfig();
        }
        if (empty($this->method)) {
            throw new DtaException('请检查接口');
        }
        $this->output = HttpService::instance()
            ->url($this->method)
            ->data($this->param)
            ->post()
            ->toArray();
        return $this->output;
    }
}