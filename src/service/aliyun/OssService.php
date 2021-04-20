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
    /**
     * @var
     */
    private $accessKeyId, $accessKeySecret, $endpoint, $bucket;

    /**
     * @param string $accessKeyId
     * @return $this
     */
    public function accessKeyId(string $accessKeyId)
    {
        $this->accessKeyId = $accessKeyId;
        return $this;
    }

    /**
     * @param string $accessKeySecret
     * @return $this
     */
    public function accessKeySecret(string $accessKeySecret)
    {
        $this->accessKeySecret = $accessKeySecret;
        return $this;
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function endpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @param string $bucket
     * @return $this
     */
    public function bucket(string $bucket): self
    {
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * @param string $object
     * @param string $filePath
     * @return bool
     */
    public function upload(string $object, string $filePath): bool
    {
        try {
            $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            $ossClient->uploadFile($this->bucket, $object, $filePath);
            return true;
        } catch (OssException $e) {
            return false;
        }
    }
}
