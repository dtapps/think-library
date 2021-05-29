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

namespace DtApp\ThinkLibrary\extend;

/**
 * 随机数码管理扩展
 * Class CodeExtend
 * @package DtApp\ThinkLibrary\extend
 */
class CodeExtend
{
    /**
     * 获取随机字符串编码
     * @param integer $size 编码长度
     * @param integer $type 编码类型(1纯数字,2纯字母,3数字字母)
     * @param string $prefix 编码前缀
     * @return string
     * @throws \Exception
     */
    public static function random(int $size = 10, int $type = 1, string $prefix = ''): string
    {
        $numbs = '0123456789';
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        if ($type === 1) {
            $chars = $numbs;
        }
        if ($type === 3) {
            $chars = "{$numbs}{$chars}";
        }
        $code = $prefix . $chars[random_int(1, strlen($chars) - 1)];
        while (strlen($code) < $size) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    /**
     * 唯一日期编码
     * @param integer $size 编码长度
     * @param string $prefix 编码前缀
     * @return string
     * @throws \Exception
     */
    public static function uniqidDate(int $size = 16, string $prefix = ''): string
    {
        if ($size < 14) {
            $size = 14;
        }
        $code = $prefix . date('Ymd') . (date('H') + date('i')) . date('s');
        while (strlen($code) < $size) {
            $code .= random_int(0, 9);
        }
        return $code;
    }

    /**
     * 唯一数字编码
     * @param integer $size 编码长度
     * @param string $prefix 编码前缀
     * @return string
     * @throws \Exception
     */
    public static function uniqidNumber(int $size = 12, string $prefix = ''): string
    {
        $time = time() . '';
        if ($size < 10) {
            $size = 10;
        }
        $code = $prefix . ((int)$time[0] + (int)$time[1]) . substr($time, 2) . random_int(0, 9);
        while (strlen($code) < $size) {
            $code .= random_int(0, 9);
        }
        return $code;
    }
}