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

declare (strict_types=1);

namespace DtApp\ThinkLibrary\facade;

use DtApp\ThinkLibrary\helper\Randoms as helper;
use think\facade;

/**
 * 随机门面
 * @see \DtApp\ThinkLibrary\helper\Randoms
 * @package DtApp\ThinkLibrary\Randoms
 * @package think\facade
 * @mixin helper
 *
 * @method static string generate(int $length = 6, int $type = 1) 生成随机
 */
class Randoms extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return helper::class;
    }
}

