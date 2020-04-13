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

use think\facade;

/**
 * 验证门面
 * Class Preg
 * @see \DtApp\ThinkLibrary\Preg
 * @package think\facade
 * @mixin \DtApp\ThinkLibrary\Preg
 * @package DtApp\ThinkLibrary\facade
 * @method bool isIphone($mobile) static 验证手机号码
 */
class Preg extends Facade
{
    protected static function getFacadeClass()
    {
        return 'DtApp\ThinkLibrary\Preg';
    }
}
