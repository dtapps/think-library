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

use DtApp\ThinkLibrary\helper\UnIqIds as helper;
use think\Facade;

/**
 * 唯一ID门面
 * Class UnIqIds
 * @see \DtApp\ThinkLibrary\UnIqIds
 * @package DtApp\ThinkLibrary\UnIqIds
 * @package think\facade
 * @mixin helper
 *
 * @method helper random($size = 10, $type = 1, $prefix = '') string 获取随机字符串编码
 * @method helper date($size = 16, $prefix = '') string 唯一日期编码
 * @method helper number($size = 12, $prefix = '') string 唯一数字编码
 */
class UnIqIds extends Facade
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
