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

namespace DtApp\ThinkLibrary\service\qiniu;

use DtApp\ThinkLibrary\Service;
use Exception;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/**
 * 七牛云对象存储
 * https://www.qiniu.com/products/kodo
 * Class KodoService
 * @package DtApp\ThinkLibrary\service\qiniu
 */
class KodoService extends Service
{
    /**
     * @var
     */
    private $accessKey, $secretKey, $bucket;

    /**
     * @param string $accessKey
     * @return $this
     */
    public function accessKey(string $accessKey): self
    {
        $this->accessKey = $accessKey;
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
     * @throws Exception
     */
    public function upload(string $object, string $filePath): bool
    {
        // 初始化签权对象
        $auth = new Auth($this->accessKey, $this->secretKey);
        // 生成上传Token
        $token = $auth->uploadToken($this->bucket);
        // 构建 UploadManager 对象
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        [$ret, $err] = $uploadMgr->putFile($token, $object, $filePath);
        return !($err !== null);

    }
}
