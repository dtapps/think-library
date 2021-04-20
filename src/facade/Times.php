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

use DtApp\ThinkLibrary\helper\Times as helper;
use think\Facade;

/**
 * 时间门面
 * @see \DtApp\ThinkLibrary\helper\Times
 * @package DtApp\ThinkLibrary\Times
 * @package think\facade
 * @mixin helper
 *
 * @method static string getData(string $format = "Y-m-d H:i:s") 当前时间
 * @method static string getTime() 当前时间戳
 * @method static string getUDate() 当前时间戳
 * @method static string getTimeDifference(string $end_time, string $start_time) 计算两个时间差
 * @method static string dateToTimestamp(string $date) 将指定日期转换为时间戳
 * @method static string timestampToDate(int $time, string $format = "Y-m-d H:i:s") 将指定时间戳转换为日期
 * @method static string dateRear(string $format = "Y-m-d H:i:s", int $mun = 10, int $time = 0) 在某个时间之前的时间
 * @method static string dateBefore(string $format = "Y-m-d H:i:s", int $mun = 10, int $time = 0) 在某个时间之后的时间
 * @method static bool checkIsBetweenTime(string $start, string $end) 判断当前的时分是否在指定的时间段内
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
