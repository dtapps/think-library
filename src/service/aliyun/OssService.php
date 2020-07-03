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

namespace DtApp\ThinkLibrary\service\aliyun;

use DtApp\ThinkLibrary\Service;
use OSS\Core\OssException;
use OSS\OssClient;

/**
 * 阿里云对象存储
 * https://www.aliyun.com/product/oss
 * Class OssService
 * @package DtApp\ThinkLibrary\service\aliyun
 */
class OssService extends Service
{
    private $accessKeyId, $accessKeySecret, $endpoint, $bucket;

    public function accessKeyId(string $accessKeyId)
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
        $this->accessKeyId = $this->app->config->get('dtapp.aliyun.oss.access_key_id');
        $this->accessKeySecret = $this->app->config->get('dtapp.aliyun.oss.access_key_secret');
        $this->endpoint = $this->app->config->get('dtapp.aliyun.oss.endpoint');
        $this->bucket = $this->app->config->get('dtapp.aliyun.oss.bucket');
        return $this;
    }

    /**
     * 上传文件
     * @param $object
     * @param $filePath
     * @return bool|string
     */
    public function upload(string $object, string $filePath)
    {
        if (empty($this->accessKeySecret)) $this->getConfig();
        if (empty($this->accessKeySecret)) $this->getConfig();
        if (empty($this->endpoint)) $this->getConfig();
        if (empty($this->bucket)) $this->getConfig();
        try {
            $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            $ossClient->uploadFile($this->bucket, $object, $filePath);
            return $this->app->config->get('dtapp.aliyun.oss.url', '') . $object;
        } catch (OssException $e) {
            return $e->getMessage();
        }
    }
}
