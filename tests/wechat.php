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

use DtApp\ThinkLibrary\exception\WeChatException;
use DtApp\ThinkLibrary\service\WeChat\WebApps;

require '../vendor/autoload.php';

try {
    dump(WebApps::instance()
        ->appId("")
        ->redirectUri("https://www.dtapp.net")
        ->oauth2());
} catch (WeChatException $e) {
    var_dump($e->getMessage());
}
