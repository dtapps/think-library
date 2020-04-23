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

use DtApp\ThinkLibrary\helper\Returns as helper;
use think\Facade;

/**
 * 返回门面
 * Class Returns
 * @see \DtApp\ThinkLibrary\helper\Returns
 * @package DtApp\ThinkLibrary\Returns
 * @package think\facade
 * @mixin helper
 *
 * @method helper jsonSuccess(array $data = [], string $msg = 'success', int $code = 0) 返回Json-成功
 * @method helper jsonError(string $msg = 'error', int $code = 1, array $data = []) 返回Json-错误
 */
class Returns extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    public static function getFacadeClass()
    {
        return helper::class;
    }
}
