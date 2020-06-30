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
 * @method helper rand(array $array) mixed 数组随机返回一个下标
 * @method helper randValue(array $array) mixed 数组随机返回一个值
 * @method helper split(array $array, $num = 5) array 分隔数组
 * @method helper unique(array $array) array 多维数组去重
 * @method helper sort(array $arrays, string $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC) array 二维数组根据某个键排序
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
