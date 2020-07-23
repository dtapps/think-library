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
 * 小数管理类
 * @mixin  Decimals
 * @package DtApp\ThinkLibrary\helper
 */
class Decimals
{
    /**
     * 直接取整，舍弃小数保留整数
     * @param $num
     * @return int
     */
    public function intval($num)
    {
        return intval($num);
    }

    /**
     * 四舍五入取整
     * @param $num
     * @return float
     */
    public function round($num)
    {
        return round($num);
    }

    /**
     * 有小数，就在整数的基础上加一
     * @param $num
     * @return false|float
     */
    public function ceil($num)
    {
        return ceil($num);
    }

    /**
     * 有小数，就取整数位
     * @param $num
     * @return false|float
     */
    public function floor($num)
    {
        return floor($num);
    }
}
