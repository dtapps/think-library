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

namespace DtApp\ThinkLibrary\service\ip;

use DtApp\ThinkLibrary\Service;
use Exception;

/**
 * IP  - IPIP
 * Class IpIpService
 * @package DtApp\ThinkLibrary\service\ip
 */
class IpIpService extends Service
{
    public $reader = null;

    /**
     *
     * IpIpService constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->reader = new IpIpReader(__DIR__ . '/bin/ipipfree.ipdb');
    }

    /**
     * @param $ip
     * @param $language
     * @return array|NULL
     */
    public function getFind(string $ip = '', string $language = 'CN')
    {
        if (empty($ip)) $ip = get_ip();
        return $this->reader->find($ip, $language);
    }

    /**
     * @param $ip
     * @param $language
     * @return array|false|null
     */
    public function getFindMap(string $ip = '', string $language = 'CN')
    {
        if (empty($ip)) $ip = get_ip();
        return $this->reader->findMap($ip, $language);
    }

    /**
     * @param $ip
     * @param $language
     * @return IpIpDistrictInfo|null
     */
    public function getFindInfo(string $ip = '', string $language = 'CN')
    {
        if (empty($ip)) $ip = get_ip();
        $map = $this->getFindMap($ip, $language);
        if (NULL === $map) return NUll;
        return new IpIpDistrictInfo($map);
    }
}
