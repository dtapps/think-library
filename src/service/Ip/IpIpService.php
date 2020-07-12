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
// | aliyun 仓库地址 ：https://code.aliyun.com/liguancghun/ThinkLibrary
// | coding 仓库地址 ：https://liguangchun-01.coding.net/p/ThinkLibrary/d/ThinkLibrary/git
// | coding 仓库地址 ：https://aizhineng.coding.net/p/ThinkLibrary/d/ThinkLibrary/git
// | tencent 仓库地址 ：https://liguangchundt.coding.net/p/ThinkLibrary/d/ThinkLibrary/git
// | weixin 仓库地址 ：https://git.weixin.qq.com/liguangchun/ThinkLibrary
// | huaweicloud 仓库地址 ：https://codehub-cn-south-1.devcloud.huaweicloud.com/composer00001/ThinkLibrary.git
// | Packagist 地址 ：https://packagist.org/packages/liguangchun/think-library
// +----------------------------------------------------------------------

namespace DtApp\ThinkLibrary\service\Ip;

use DtApp\ThinkLibrary\Service;
use Exception;
use think\App;

/**
 * IP  - IPIP
 * Class IpIpService
 * @package DtApp\ThinkLibrary\service\ip
 */
class IpIpService extends Service
{
    public $reader = null;

    /**
     * IpIpService constructor.
     * @param App $app
     * @throws Exception
     */
    public function __construct(App $app)
    {
        $this->reader = new IpIpReader(__DIR__ . '/bin/ipipfree.ipdb');
        parent::__construct($app);
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
