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

namespace DtApp\ThinkLibrary;

use think\admin\service\AdminService;
use think\Request;
use think\Service;

/**
 * 模块注册服务
 * Class Library
 * @package DtApp\ThinkLibrary
 */
class Library extends Service
{
    /**
     * 定义当前版本
     */
    const VERSION = '6.0.131';

    /**
     * 启动服务
     */
    public function boot()
    {

    }

    /**
     * 初始化服务
     */
    public function register()
    {
        // 输入默认过滤
        $this->app->request->filter(['trim']);
        // 判断访问模式，兼容 CLI 访问控制器
        if (!$this->app->request->isCli()) {
            // 注册访问处理中间键
            $this->app->middleware->add(function (Request $request) {
                $header = [];
                if (($origin = $request->header('origin', '*')) !== '*') {
                    $header['Access-Control-Allow-Origin'] = $origin;
                    $header['Access-Control-Allow-Methods'] = 'GET,PUT,POST,PATCH,DELETE';
                    $header['Access-Control-Allow-Headers'] = 'Authorization,Content-Type,If-Match,If-Modified-Since,If-None-Match,If-Unmodified-Since,X-Requested-With,User-Form-Token,User-Token,Token';
                    $header['Access-Control-Expose-Headers'] = 'User-Form-Token,User-Token,Token';
                    $header['Access-Control-Allow-Credentials'] = 'true';
                }
                // 访问模式及访问权限检查
                if ($request->isOptions()) {
                    return response()->code(204)->header($header);
                }
            }, 'route');
        }
    }
}
