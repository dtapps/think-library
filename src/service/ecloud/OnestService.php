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

namespace DtApp\ThinkLibrary\service\ecloud;

use Aws\S3\S3Client;
use DtApp\ThinkLibrary\Service;

/**
 * 移动云
 * https://ecloud.10086.cn/product-introduction/onest
 * Class OnestService
 * @package DtApp\ThinkLibrary\service\ecloud
 */
class OnestService extends Service
{
    private $accessKey, $secretKey, $bucket, $baseUrl, $port;

    public function accessKey(string $accessKey)
    {
        $this->accessKey = $accessKey;
        return $this;
    }

    public function secretKey(string $secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    public function bucket(string $bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    public function baseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function port(string $port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig()
    {
        $this->accessKey = $this->app->config->get('dtapp.ecloud.onest.access_key');
        $this->secretKey = $this->app->config->get('dtapp.ecloud.onest.secret_key');
        $this->bucket = $this->app->config->get('dtapp.ecloud.onest.bucket');
        $this->baseUrl = $this->app->config->get('dtapp.ecloud.onest.base_url');
        $this->port = $this->app->config->get('dtapp.ecloud.onest.port');
        return $this;
    }

    /**
     * 上传文件
     * @param $object
     * @param $filePath
     * @return bool
     */
    public function upload(string $object, string $filePath)
    {
        if (empty($this->accessKey)) $this->getConfig();
        if (empty($this->secretKey)) $this->getConfig();
        if (empty($this->baseUrl)) $this->getConfig();
        if (empty($this->port)) $this->getConfig();

        $client = S3Client::factory([
            'base_url' => $this->baseUrl,
            'port' => $this->port,
            'key' => $this->accessKey,
            'secret' => $this->secretKey,
            S3Client::COMMAND_PARAMS => [
                'PathStyle' => true,
            ],
        ]);
        if (empty($this->bucket)) $this->getConfig();
        $acl = 'public';
        $client->upload($this->bucket, $object, fopen($filePath, 'rb'), $acl);
        return $this->app->config->get('dtapp.ecloud.onest.url') . $object;
    }
}
