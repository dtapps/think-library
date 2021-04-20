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

declare (strict_types=1);

namespace DtApp\ThinkLibrary\facade;

use DtApp\ThinkLibrary\helper\Decimals as helper;
use think\Facade;

/**
 * 小数门面
 * @see \DtApp\ThinkLibrary\helper\Decimals
 * @package DtApp\ThinkLibrary\Decimals
 * @package think\facade
 * @mixin helper
 *
 * @method static int intval($num) 直接取整，舍弃小数保留整数
 * @method static float round($num, $bl = 0) 四舍五入
 * @method static false|float ceil($num) 有小数，就在整数的基础上加一
 * @method static false|float floor($num) 有小数，就取整数位
 * @method static bool judge($num) 判断是不是小数
 */
class Decimals extends Facade
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
