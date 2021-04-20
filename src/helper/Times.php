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

namespace DtApp\ThinkLibrary\helper;

/**
 * 时间管理类
 * @mixin Times
 * @package DtApp\ThinkLibrary\helper
 */
class Times
{
    /**
     * 当前时间
     * @param string $format 格式
     * @return false|string
     */
    public function getData(string $format = "Y-m-d H:i:s")
    {
        date_default_timezone_set('Asia/Shanghai');
        return date($format, time());
    }

    /**
     * 当前时间戳
     * @return false|string
     */
    public function getTime()
    {
        date_default_timezone_set('Asia/Shanghai');
        return time();
    }

    /**
     * 毫秒时间
     * @return false|string
     */
    public function getUDate()
    {
        date_default_timezone_set('Asia/Shanghai');
        $msec = 0;
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', ((float)$msec + (float)$sec) * 1000);
    }

    /**
     * 计算两个时间差
     * @param string $end_time 结束时间
     * @param string $start_time 开始时间
     * @return false|int
     */
    public function getTimeDifference(string $end_time, string $start_time)
    {
        date_default_timezone_set('Asia/Shanghai');
        $end_time = strtotime($end_time);
        $start_time = $start_time === '' ? time() : strtotime($start_time);
        return $end_time - $start_time;
    }

    /**
     * 将指定日期转换为时间戳
     * @param string $date
     * @return false|int
     */
    public function dateToTimestamp(string $date)
    {
        date_default_timezone_set('Asia/Shanghai');
        return strtotime($date);
    }

    /**
     * 将指定时间戳转换为日期
     * @param string $format
     * @param int $time
     * @return false|string
     */
    public function timestampToDate(int $time, string $format = "Y-m-d H:i:s")
    {
        date_default_timezone_set('Asia/Shanghai');
        return date($format, $time);
    }

    /**
     * 在某个时间之前的时间
     * @param string $format 格式
     * @param int $mun 多少秒
     * @param int $time
     * @return false|string
     */
    public function dateBefore(string $format = "Y-m-d H:i:s", int $mun = 60, int $time = 0)
    {
        date_default_timezone_set('Asia/Shanghai');
        if (empty($time)) {
            $time = time();
        }
        return date($format, $time - $mun);
    }

    /**
     * 在某个时间之后的时间
     * @param string $format 格式
     * @param int $mun 多少秒
     * @param int $time
     * @return false|string
     */
    public function dateRear(string $format = "Y-m-d H:i:s", int $mun = 60, int $time = 0)
    {
        date_default_timezone_set('Asia/Shanghai');
        if (empty($time)) {
            $time = time();
        }
        return date($format, $time + $mun);
    }


    /**
     * 判断当前的时分是否在指定的时间段内
     * @param string $start 开始时间
     * @param string $end 结束时间
     * @return bool true：在范围内，false:没在范围内
     */
    public function checkIsBetweenTime(string $start, string $end)
    {
        date_default_timezone_set('Asia/Shanghai');
        $date = date('H:i');
        $curTime = strtotime($date);//当前时分
        $assignTime1 = strtotime($start);//获得指定分钟时间戳，00:00
        $assignTime2 = strtotime($end);//获得指定分钟时间戳，01:00
        $result = false;
        if ($curTime > $assignTime1 && $curTime < $assignTime2) {
            $result = true;
        }
        return $result;
    }
}
