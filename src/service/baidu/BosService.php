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
// | gitlab 仓库地址 ：https://gitlab.com/liguangchun/thinklibrary
// | weixin 仓库地址 ：https://git.weixin.qq.com/liguangchun/ThinkLibrary
// | huaweicloud 仓库地址 ：https://codehub-cn-south-1.devcloud.huaweicloud.com/composer00001/ThinkLibrary.git
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
    /**
     * @var
     */
    private $accessKeyId, $secretAccessKey, $endpoint, $bucket;

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
     * @param string $secretAccessKey
     * @return $this
     */
    public function secretAccessKey(string $secretAccessKey)
    {
        $this->secretAccessKey = $secretAccessKey;
        return $this;
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function endpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @param string $bucket
     * @return $this
     */
    public function bucket(string $bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * @param string $object
     * @param string $filePath
     * @return mixed
     * @throws Exception
     */
    public function upload(string $object, string $filePath)
    {
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
        return $client->putObjectFromFile($this->bucket, $object, $filePath);
    }
}
