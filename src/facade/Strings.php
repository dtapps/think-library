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

use DtApp\ThinkLibrary\helper\Strings as helper;
use think\facade;

/**
 * 字符串门面
 * @see \DtApp\ThinkLibrary\helper\Strings
 * @package DtApp\ThinkLibrary\Strings
 * @package think\facade
 * @mixin helper
 *
 * @method static string extractBefore(string $str, int $start_num, int $end_num) 截取字符串前面n个字符
 * @method static string extractRear(string $str, int $num) 截取字符串最后n个字符
 * @method static string filter(string $str) 过滤字符串
 * @method static bool exitContain(string $str, $nee = 3, $del = ',') bool 判断字符串是否包含某个字符
 * @method static int len(string $str) int 统计字符串长度
 * @method static string trimAll(string $str) 删除空格
 * @method static string replace(string $search, string $replace, string $subject) 替换字符串
 */
class Strings extends Facade
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
