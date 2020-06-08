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
    private $accessKey, $secretKey, $bucket;

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

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig()
    {
        $this->accessKey = $this->app->config->get('dtapp.qiniu.kodo.access_key');
        $this->secretKey = $this->app->config->get('dtapp.qiniu.kodo.secret_key');
        $this->bucket = $this->app->config->get('dtapp.qiniu.kodo.bucket');
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
        if (empty($this->accessKey)) $this->getConfig();
        if (empty($this->secretKey)) $this->getConfig();
        if (empty($this->bucket)) $this->getConfig();
        // 初始化签权对象
        $auth = new Auth($this->accessKey, $this->secretKey);
        // 生成上传Token
        $token = $auth->uploadToken($this->bucket);
        // 构建 UploadManager 对象
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $object, $filePath);
        if ($err !== null) return false;
        else return $this->app->config->get('dtapp.qiniu.kodo.url') . $object;
    }
}
