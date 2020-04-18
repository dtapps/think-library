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

namespace DtApp\ThinkLibrary\helper;

/**
 * 数字管理类
 * Class Ints
 * @mixin Ints
 * @package DtApp\ThinkLibrary\helper
 */
class Ints
{
    /**
     * 判断一个数是不是偶数
     * @param int $num
     * @return bool
     */
    public function isEvenNumbers(int $num)
    {
        if ($num % 2 == 0) return true;
        return false;
    }

    /**
     * 判断一个数是不是奇数
     * @param int $num
     * @return bool
     */
    public function isOddNumbers(int $num)
    {
        if ($num % 2 == 0) return false;
        return true;
    }
}
