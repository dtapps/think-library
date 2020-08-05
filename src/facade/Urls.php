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

namespace DtApp\ThinkLibrary\facade;

use DtApp\ThinkLibrary\helper\Urls as helper;
use think\Facade;

/**
 * 网址门面
 * @see \DtApp\ThinkLibrary\Urls
 * @package DtApp\ThinkLibrary\Urls
 * @package think\facade
 * @mixin helper
 *
 * @method static string lenCode(string $url) 编码
 * @method static string deCode(string $url) 解码
 * @method static string toParams(array $data) 格式化参数格式化成url参数
 * @method static bool isUrl(string $url) 判断是否为Url
 * @method static string deleteProtocol(string $url) 删除协议
 * @method static string retrieve(string $url) 获取URL文件格式
 */
class Urls extends Facade
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
