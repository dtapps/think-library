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

namespace DtApp\ThinkLibrary\service\tencent;

use DtApp\ThinkLibrary\Service;
use Exception;
use Qcloud\Cos\Client;

/**
 * 腾讯云对象存储
 * Class CosService
 * @package DtApp\ThinkLibrary\service\tencent
 */
class CosService extends Service
{
    private $secretId;
    private $secretKey;
    private $region;
    private $bucket;

    public function secretId(string $secretId)
    {
        $this->secretId = $secretId;
        return $this;
    }

    public function secretKey(string $secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    public function region(string $region)
    {
        $this->region = $region;
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
        $this->secretId = $this->app->config->get('dtapp.tencent.cos.secret_id');
        $this->secretKey = $this->app->config->get('dtapp.tencent.cos.secret_key');
        $this->region = $this->app->config->get('dtapp.tencent.cos.region');
        $this->bucket = $this->app->config->get('dtapp.tencent.cos.bucket');
        return $this;
    }

    /**
     * 上传文件
     * @param $object
     * @param $filePath
     * @return bool
     * @throws Exception
     */
    public function upload(string $object, string $filePath)
    {
        if (empty($this->secretId)) $this->getConfig();
        if (empty($this->secretKey)) $this->getConfig();
        if (empty($this->region)) $this->getConfig();
        $cosClient = new Client(
            array(
                'region' => $this->region,
                'schema' => 'http', //协议头部，默认为http
                'credentials' => array(
                    'secretId' => $this->secretId,
                    'secretKey' => $this->secretKey
                )
            )
        );
        $key = $object;
        $file = fopen($filePath, "rb");
        if ($file) {
            if (empty($this->bucket)) $this->getConfig();
            $result = $cosClient->putObject(
                array(
                    'Bucket' => $this->bucket,
                    'Key' => $key,
                    'Body' => $file)
            );
        }
        return $this->app->config->get('dtapp.tencent.cos.url') . $filePath;
    }
}
