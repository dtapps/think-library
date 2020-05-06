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

namespace DtApp\ThinkLibrary\service\WeMini;

use DtApp\Curl\CurlException;
use DtApp\Curl\Get;
use DtApp\ThinkLibrary\Service;

/**
 * 微信小程序 - 用户信息
 * Class UserInfoService
 * @package DtApp\ThinkLibrary\service\WeMini
 */
class UserInfoService extends Service
{
    /**
     * 用户支付完成后，获取该用户的 UnionId，无需用户授权
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/user-info/auth.getPaidUnionId.html
     * @param string $access_token
     * @param string $openid
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function getPaidUnionId(string $access_token, string $openid)
    {
        $url = "https://api.weixin.qq.com/wxa/getpaidunionid?access_token={$access_token}&openid={$openid}";
        $curl = new Get();
        return $curl->http($url, '', true);
    }
}
