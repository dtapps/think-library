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

namespace DtApp\ThinkLibrary\service\ucloud;

use DtApp\ThinkLibrary\Service;

/**
 * UCloud优刻得
 * https://www.ucloud.cn/site/product/ufile.html
 * Class UfileService
 * @package DtApp\ThinkLibrary\service\ucloud
 */
class UfileService extends Service
{
    private $bucket;

    public function bucket(string $bucket)
    {
        $this->bucket = $bucket;
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
        list($data, $err) = UCloud_PutFile($this->bucket, $object, $filePath);
        if (($err)) return false;
        return $this->app->config->get('dtapp.ucloud.ufile.url') . $object;
    }
}
