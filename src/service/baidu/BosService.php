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

namespace DtApp\ThinkLibrary\service\baidu;

use BaiduBce\Services\Bos\BosClient;
use DtApp\ThinkLibrary\Service;
use Exception;

/**
 * 百度云对象存储
 * https://cloud.baidu.com/product/bos.html
 * Class BosService
 * @package DtApp\ThinkLibrary\service\baidu
 */
class BosService extends Service
{
    private $accessKeyId, $secretAccessKey, $endpoint, $bucket;

    public function accessKeyId(string $accessKeyId)
    {
        $this->accessKeyId = $accessKeyId;
        return $this;
    }

    public function secretAccessKey(string $secretAccessKey)
    {
        $this->secretAccessKey = $secretAccessKey;
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
        $this->accessKeyId = $this->app->config->get('dtapp.baidu.bos.access_key_id');
        $this->secretAccessKey = $this->app->config->get('dtapp.baidu.bos.secret_access_key');
        $this->endpoint = $this->app->config->get('dtapp.baidu.bos.endpoint');
        $this->bucket = $this->app->config->get('dtapp.baidu.bos.bucket');
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
        if (empty($this->accessKeyId)) $this->getConfig();
        if (empty($this->secretAccessKey)) $this->getConfig();
        if (empty($this->endpoint)) $this->getConfig();
        // 设置BosClient的Access Key ID、Secret Access Key和ENDPOINT
        $BOS_TEST_CONFIG = array(
            'credentials' => array(
                'accessKeyId' => $this->accessKeyId,
                'secretAccessKey' => $this->secretAccessKey,
            ),
            'endpoint' => $this->endpoint,
        );
        $client = new BosClient($BOS_TEST_CONFIG);
        // 从文件中直接上传Object
        if (empty($this->bucket)) $this->getConfig();
        $client->putObjectFromFile($this->bucket, $object, $filePath);
        return $this->app->config->get('dtapp.baidu.bos.url', '') . $object;
    }
}
