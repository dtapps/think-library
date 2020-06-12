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

namespace DtApp\ThinkLibrary\service\netease;

use DtApp\ThinkLibrary\Service;
use NOS\Core\NosException;
use NOS\NosClient;

/**
 * 网易云
 * https://www.163yun.com/product/nos
 * Class NosService
 * @package DtApp\ThinkLibrary\service\netease
 */
class NosService extends Service
{
    private $accessKeyId, $accessKeySecret, $endpoint, $bucket;

    public function accessKeyID(string $accessKeyId)
    {
        $this->accessKeyId = $accessKeyId;
        return $this;
    }

    public function accessKeySecret(string $accessKeySecret)
    {
        $this->accessKeySecret = $accessKeySecret;
        return $this;
    }

    public function endpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function bucket(string $bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig()
    {
        $this->accessKeyId = $this->app->config->get('dtapp.netease.nos.access_key_id');
        $this->accessKeySecret = $this->app->config->get('dtapp.netease.nos.access_key_secret');
        $this->endpoint = $this->app->config->get('dtapp.netease.nos.endpoint');
        $this->bucket = $this->app->config->get('dtapp.netease.nos.bucket');
        return $this;
    }

    /**
     * 上传文件
     * @param string $object
     * @param string $filePath
     * @return bool|string
     */
    public function upload(string $object, string $filePath)
    {
        if (empty($this->accessKeyId)) $this->getConfig();
        if (empty($this->accessKeySecret)) $this->getConfig();
        if (empty($this->endpoint)) $this->getConfig();
        try {
            $nosClient = new NosClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            if (empty($this->bucket)) $this->getConfig();
            $nosClient->uploadFile($this->bucket, $object, $filePath);
            return $this->app->config->get('dtapp.netease.nos.url') . $object;
        } catch (NosException $e) {
            return false;
        }
    }
}
