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

namespace DtApp\ThinkLibrary\service\amap;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\curl\HttpService;

/**
 * 高德地图
 * https://lbs.amap.com/api/webservice/summary
 * Class AmApService
 * @package DtApp\ThinkLibrary\service\amap
 */
class AmApService extends Service
{
    /**
     * @var string
     */
    private $url = "https://restapi.amap.com/v3/";

    /**
     * @var string
     */
    private $key = "";

    /**
     * @var string
     */
    private $output = "JSON";

    /**
     * @param string $key
     * @return $this
     */
    public function key(string $key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig(): self
    {
        $this->key = config('dtapp.amap.key');
        return $this;
    }

    /**
     * 天气查询
     * https://lbs.amap.com/api/webservice/guide/api/weatherinfo
     * @param string $city
     * @param string $extensions
     * @return array|bool|mixed|string
     * @throws DtaException
     */
    public function weather($city = "110101", $extensions = "base")
    {
        if (empty($this->key)) {
            $this->getConfig();
        }
        if (empty($this->key)) {
            throw new DtaException('请检查key参数');
        }
        $data = http_build_query([
            "city" => $city,
            "extensions" => $extensions,
            "key" => $this->key,
            "output" => $this->output,
        ]);
        return HttpService::instance()
            ->url("{$this->url}weather/weatherInfo?{$data}")
            ->toArray();
    }
}
