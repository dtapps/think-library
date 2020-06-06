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
 * Class ObsService
 * @package DtApp\ThinkLibrary\service\huaweicloud
 */
class ObsService extends Service
{
    private $key;
    private $secret;
    private $endpoint;
    private $bucket;

    public function key(string $key)
    {
        $this->key = $key;
        return $this;
    }

    public function secret(string $secret)
    {
        $this->secret = $secret;
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
        $this->key = $this->app->config->get('dtapp.huaweicloud.obs.key');
        $this->secret = $this->app->config->get('dtapp.huaweicloud.obs.secret');
        $this->endpoint = $this->app->config->get('dtapp.huaweicloud.obs.endpoint');
        $this->bucket = $this->app->config->get('dtapp.huaweicloud.obs.bucket');
        return $this;
    }

    /**
     * 上传到华为云
     * @param $object
     * @param $filePath
     * @return bool
     */
    private function upload($object, $filePath)
    {
        if (empty($this->key)) $this->getConfig();
        if (empty($this->secret)) $this->getConfig();
        if (empty($this->endpoint)) $this->getConfig();
        // 创建ObsClient实例
        $obsClient = new ObsClient([
            'key' => $this->key,
            'secret' => $this->secret,
            'endpoint' => $this->endpoint
        ]);
        if (empty($this->bucket)) $this->getConfig();
        $resp = $obsClient->putObject([
            'Bucket' => $this->bucket,
            'Key' => $object,
            'SourceFile' => $filePath  // localfile为待上传的本地文件路径，需要指定到具体的文件名
        ]);
        if (isset($resp['RequestId'])) {
            return true;
        } else {
            return false;
        }
    }
}
