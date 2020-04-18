<?php

// +----------------------------------------------------------------------
// | ThinkLibrary 5.1 for ThinkPhP 5.1
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

use DtApp\ThinkLibrary\helper\Times as helper;
use think\Facade;

/**
 * 时间门面
 * Class Times
 * @see \DtApp\ThinkLibrary\helper\Times
 * @package think\facade
 * @mixin helper
 *
 * @method helper getData(string $format = "Y-m-d H:i:s") false|string 当前时间
 * @method helper getTime() false|string 当前时间戳
 * @method helper getUDate() false|string 当前时间戳
 * @method helper getTimeDifference(string $end_time, string $start_time) false|string 计算两个时间差
 * @method helper dateToTimestamp(string $date) false|string 将指定日期转换为时间戳
 * @method helper dateRear(string $format = "Y-m-d H:i:s", int $mun = 10) false|string 获取某个时间之后的时间
 * @method helper dateBefore(string $format = "Y-m-d H:i:s", int $mun = 10) false|string 获取某个时间之前的时间
 * @method helper checkIsBetweenTime(string $start, string $end) bool 判断当前的时分是否在指定的时间段内
 */
class Times extends Facade
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
