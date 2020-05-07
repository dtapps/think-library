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

namespace DtApp\ThinkLibrary\facade;

use DtApp\ThinkLibrary\helper\Arrays as helper;
use think\Facade;

/**
 * 数组门面
 * Class Arrays
 * @see \DtApp\ThinkLibrary\helper\Arrays
 * @package DtApp\ThinkLibrary\facade
 * @package think\facade
 * @mixin helper
 *
 * @method helper rand(int $num) mixed 数组随机返回一个下标
 * @method helper randValue(int $num) mixed 数组随机返回一个值
 */
class Arrays extends Facade
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
