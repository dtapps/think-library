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

use DtApp\ThinkLibrary\helper\Requests as helper;
use think\Facade;

/**
 * 请求门面
 * @see \DtApp\ThinkLibrary\helper\Requests
 * @package DtApp\ThinkLibrary\facade
 * @package think\facade
 * @mixin helper
 *
 * @method static array isEmpty(array $data, array $arr) array|bool 判断输入的参数
 * @method static array isEmptyRet(array $data, array $arr) array 判断输入的参数为空就返回Json错误
 * @method static bool isGet() 判断是否为GET方式
 * @method static bool isPost()  判断是否为POST方式
 * @method static bool isPut() 判断是否为PUT方式
 * @method static bool isDelete() 判断是否为DELETE方式
 * @method static bool isAjax() 判断是否为Ajax方式
 * @method static bool isMobile() 判断是否为移动端访问
 * @method static bool isWeXin() 判断是否微信内置浏览器访问
 * @method static bool isWeXinMp() 判断是否为微信小程序访问
 * @method static bool isAliPay() 判断是否支付宝内置浏览器访问
 * @method static bool isQQ() 判断是否QQ内置浏览器访问
 * @method static bool isQQBrowser() 判断是否QQ浏览器访问
 * @method static string getWebsiteAddress() 获取域名地址
 */
class Requests extends Facade
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
