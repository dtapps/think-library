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

namespace DtApp\ThinkLibrary\service\WeChat;

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\Curl\HttpService;

class WiFiService extends Service
{
    /**
     * 连Wi-Fi完成页跳转小程序
     * https://developers.weixin.qq.com/doc/offiaccount/WiFi_via_WeChat/WiFi_mini_programs.html
     * @param string $access_token
     * @param array $data
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function fiNihPageSet(string $access_token, array $data = [])
    {
        $url = "https://api.weixin.qq.com/bizwifi/finishpage/set?access_token={$access_token}";
        if (is_array($data)) $data = json_encode($data);
        return HttpService::instance()
            ->url($url)
            ->post()
            ->data($data)
            ->toArray();
    }
}
