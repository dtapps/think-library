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

namespace DtApp\ThinkLibrary\service\tencent;

use DtApp\ThinkLibrary\Service;
use Qcloud\Cos\Client;

/**
 * 腾讯云对象存储
 * https://cloud.tencent.com/product/cos
 * Class CosService
 * @package DtApp\ThinkLibrary\service\tencent
 */
class CosService extends Service
{
    /**
     * @var
     */
    private $secretId, $secretKey, $region, $bucket;

    /**
     * @param string $secretId
     * @return $this
     */
    public function secretId(string $secretId): self
    {
        $this->secretId = $secretId;
        return $this;
    }

    /**
     * @param string $secretKey
     * @return $this
     */
    public function secretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * @param string $region
     * @return $this
     */
    public function region(string $region): self
    {
        $this->region = $region;
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
        $cosClient = new Client([
            'region' => $this->region,
            'schema' => 'https',
            'credentials' => [
                'secretId' => $this->secretId,
                'secretKey' => $this->secretKey
            ]
        ]);
        $key = $object;
        $file = fopen($filePath, "rb");
        if ($file) {
            return $cosClient->putObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'Body' => $file
            ]);
        }

        return false;
    }
}
