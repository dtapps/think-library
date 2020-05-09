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

namespace DtApp\ThinkLibrary\service\Ip;


class IpIpDistrictInfo
{
    public $country_name = '';
    public $region_name = '';
    public $city_name = '';
    public $district_name = '';
    public $china_admin_code = '';
    public $covering_radius = '';
    public $longitude = '';
    public $latitude = '';

    public function __construct(array $data)
    {
        foreach ($data AS $field => $value) {
            $this->{$field} = $value;
        }
    }

    public function __get($name)
    {
        return $this->{$name};
    }
}
