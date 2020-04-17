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

use think\Facade;

/**
 * 唯一ID门面
 * Class UnIqId
 * @see \DtApp\ThinkLibrary\UnIqId
 * @package think\facade
 * @mixin \DtApp\ThinkLibrary\UnIqId
 *
 * @method \DtApp\ThinkLibrary\UnIqId random($size = 10, $type = 1, $prefix = '') static 获取随机字符串编码
 * @method \DtApp\ThinkLibrary\UnIqId date($size = 16, $prefix = '') static 唯一日期编码
 * @method \DtApp\ThinkLibrary\UnIqId number($size = 12, $prefix = '') static 唯一数字编码
 */
class UnIqId extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'DtApp\ThinkLibrary\UnIqId';
    }
}
