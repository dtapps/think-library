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
    private $serviceName, $operatorName, $operatorPassword;

    public function serviceName(string $serviceName)
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    public function operatorName(string $operatorName)
    {
        $this->operatorName = $operatorName;
        return $this;
    }

    public function operatorPassword(string $operatorPassword)
    {
        $this->operatorPassword = $operatorPassword;
        return $this;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig()
    {
        $this->serviceName = $this->app->config->get('dtapp.upyun.uss.service_name');
        $this->operatorName = $this->app->config->get('dtapp.upyun.uss.operator_name');
        $this->operatorPassword = $this->app->config->get('dtapp.upyun.uss.operator_password');
        return $this;
    }

    /**
     * 上传文件
     * @param string $object
     * @param string $filePath
     * @return bool
     * @throws Exception
     */
    public function upload(string $object, string $filePath)
    {
        if (empty($this->serviceName) || empty($this->operatorName) || empty($this->operatorPassword)) {
            $this->getConfig();
        }
        $serviceConfig = new Config($this->serviceName, $this->operatorName, $this->operatorPassword);
        $client = new Upyun($serviceConfig);
        $file = fopen($filePath, 'r');
        $client->write($object, $file);
        return $this->app->config->get('dtapp.upyun.uss.url', '') . $object;
    }
}
