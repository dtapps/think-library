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
 * 微信小程序 - 订阅消息
 * Class NewTmplService
 * @package DtApp\ThinkLibrary\service\WeMini
 */
class NewTmplService extends Service
{
    /**
     * 获取当前帐号下的个人模板列表
     * @param $access_token
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function getTemplateList($access_token)
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/gettemplate?access_token=ACCESS_TOKEN";
        $curl = new Get();
        return $curl->http(str_replace('ACCESS_TOKEN', $access_token, $url), '', true);
    }
}
