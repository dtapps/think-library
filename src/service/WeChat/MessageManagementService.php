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
use DtApp\ThinkLibrary\service\curl\HttpService;

/**
 * 微信公众号 - 消息管理
 * Class MessageManagementService
 * @package DtApp\ThinkLibrary\service\WeChat
 */
class MessageManagementService extends Service
{
    /**
     * 设置所属行业
     * https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#0
     * @param string $access_token
     * @param array $data
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function setIndustry(string $access_token, array $data = [])
    {
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token={$access_token}";
        if (is_array($data)) $data = json_encode($data);
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->toArray();
    }
}
