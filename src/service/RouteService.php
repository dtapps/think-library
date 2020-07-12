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

namespace DtApp\ThinkLibrary\service;

use DtApp\ThinkLibrary\Service;

/**
 * 路由服务
 * Class RouteService
 * @package DtApp\ThinkLibrary\service
 */
class RouteService extends Service
{
    /**
     * 跳转到某地址
     * @param string $url
     * @param int $status
     * @param bool $parameter
     */
    public function redirect(string $url = '', int $status = 302, bool $parameter = false)
    {
        if (empty($url)) $url = request()->scheme() . "://" . request()->host();
        $param = http_build_query(request()->param());
        if ($status == 301) header('HTTP/1.1 301 Moved Permanently');
        if (empty($parameter)) header("Location: {$url}");
        elseif (empty($parameter) == false && empty($param) == true) header("Location: {$url}");
        else header("Location: {$url}?{$param}");
        exit;
    }
}
