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

namespace DtApp\ThinkLibrary\service\upyun;

use DtApp\ThinkLibrary\Service;
use Exception;
use Upyun\Config;
use Upyun\Upyun;

/**
 * 又拍云存储
 * https://www.upyun.com/products/file-storage
 * Class UssService
 * @package DtApp\ThinkLibrary\service\upyun
 */
class UssService extends Service
{
    /**
     * @var
     */
    private $serviceName, $operatorName, $operatorPassword;

    /**
     * @param string $serviceName
     * @return $this
     */
    public function serviceName(string $serviceName)
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /**
     * @param string $operatorName
     * @return $this
     */
    public function operatorName(string $operatorName)
    {
        $this->operatorName = $operatorName;
        return $this;
    }

    /**
     * @param string $operatorPassword
     * @return $this
     */
    public function operatorPassword(string $operatorPassword)
    {
        $this->operatorPassword = $operatorPassword;
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
        $serviceConfig = new Config($this->serviceName, $this->operatorName, $this->operatorPassword);
        $client = new Upyun($serviceConfig);
        $file = fopen($filePath, 'r');
        $client->write($object, $file);
        return true;
    }
}
