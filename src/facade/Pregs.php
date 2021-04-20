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

use DtApp\ThinkLibrary\helper\Pregs as helper;
use think\facade;

/**
 * 验证门面
 * @see \DtApp\ThinkLibrary\helper\Pregs
 * @package DtApp\ThinkLibrary\Pregs
 * @package think\facade
 * @mixin helper
 *
 * @method static bool isIphone($mobile) 验证手机号码
 * @method static bool isIphoneAll($mobile) 严谨验证手机号码
 * @method static bool isTel($tel) 验证电话号码
 * @method static bool isIdCard($mobile) 验证身份证号（15位或18位数字）
 * @method static bool isDigit($digit) 验证是否是数字(这里小数点会认为是字符)
 * @method static bool isNum($num) 验证是否是数字(可带小数点的数字)
 * @method static bool isStr($str) 验证由数字、26个英文字母或者下划线组成的字符串
 * @method static bool isPassword($str) 验证用户密码(以字母开头，长度在6-18之间，只能包含字符、数字和下划线)
 * @method static bool isChinese($str) 验证汉字
 * @method static bool isEmail($email) 验证Email地址
 * @method static bool isLink($url) 验证网址URL
 * @method static bool isQq($qq) 腾讯QQ号
 * @method static bool isIp($ip) 验证IP地址
 */
class Pregs extends Facade
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
