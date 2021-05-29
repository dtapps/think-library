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

namespace DtApp\ThinkLibrary\service\huaweicloud;

use DtApp\ThinkLibrary\Service;
use Obs\ObsClient;

/**
 * 华为云对象存储
 * https://www.huaweicloud.com/product/obs.html
 * Class ObsService
 * @package DtApp\ThinkLibrary\service\huaweicloud
 */
class ObsService extends Service
{
    /**
     * @var
     */
    private $key, $secret, $endpoint, $bucket;

    /**
     * @param string $key
     * @return $this
     */
    public function key(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $secret
     * @return $this
     */
    public function secret(string $secret): self
    {
        $this->secret = $secret;
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
        // 创建ObsClient实例
        $obsClient = new ObsClient([
            'key' => $this->key,
            'secret' => $this->secret,
            'endpoint' => $this->endpoint
        ]);
        $resp = $obsClient->putObject([
            'Bucket' => $this->bucket,
            'Key' => $object,
            'SourceFile' => $filePath  // localfile为待上传的本地文件路径，需要指定到具体的文件名
        ]);
        if (isset($resp['RequestId'])) {
            return true;
        }

        return false;
    }
}
