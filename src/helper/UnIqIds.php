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
// | gitlab 仓库地址 ：https://gitlab.com/liguangchun/thinklibrary
// | weixin 仓库地址 ：https://git.weixin.qq.com/liguangchun/ThinkLibrary
// | huaweicloud 仓库地址 ：https://codehub-cn-south-1.devcloud.huaweicloud.com/composer00001/ThinkLibrary.git
// | Packagist 地址 ：https://packagist.org/packages/liguangchun/think-library
// +----------------------------------------------------------------------

declare (strict_types=1);

namespace DtApp\ThinkLibrary\helper;

/**
 * 唯一ID管理类
 * @mixin UnIqIds
 * @package DtApp\ThinkLibrary\helper
 */
class UnIqIds
{
    /**
     * 获取随机字符串编码
     * @param integer $size 字符串长度
     * @param integer $type 字符串类型(1纯数字,2纯字母,3数字字母)
     * @param string $prefix 编码前缀
     * @return string
     */
    public function random($size = 10, $type = 1, $prefix = ''): string
    {
        $numbs = '0123456789';
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        if (intval($type) === 1) {
            $chars = $numbs;
        }
        if (intval($type) === 2) {
            $chars = "{$chars}";
        }
        if (intval($type) === 3) {
            $chars = "{$numbs}{$chars}";
        }
        $string = $prefix . $chars[rand(1, strlen($chars) - 1)];
        if (isset($chars)) {
            while (strlen($string) < $size) $string .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $string;
    }

    /**
     * 唯一日期编码
     * @param integer $size
     * @param string $prefix
     * @return string
     */
    public function date($size = 16, $prefix = ''): string
    {
        if ($size < 14) {
            $size = 14;
        }
        $string = $prefix . date('Ymd') . (date('H') + date('i')) . date('s');
        while (strlen($string) < $size) $string .= rand(0, 9);
        return $string;
    }

    /**
     * 唯一数字编码
     * @param integer $size
     * @param string $prefix
     * @return string
     */
    public function number($size = 12, $prefix = ''): string
    {
        $time = time() . '';
        if ($size < 10) {
            $size = 10;
        }
        $string = $prefix . ($time[0] . $time[1]) . substr($time, 2) . rand(0, 9);
        while (strlen($string) < $size) $string .= rand(0, 9);
        return $string;
    }
}
