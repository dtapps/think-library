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

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\curl\HttpService;

/**
 * 微信小程序 - 订阅消息
 * Class NewTmplService
 * @package DtApp\ThinkLibrary\service\WeMini
 */
class NewTmplService extends Service
{
    /**
     * 组合模板并添加至帐号下的个人模板库
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.addTemplate.html
     * @param string $access_token 接口调用凭证
     * @param array $data
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function addTemplate(string $access_token, array $data = [])
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/addtemplate?access_token={$access_token}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->toArray();
    }

    /**
     * 删除帐号下的个人模板
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.deleteTemplate.html
     * @param string $access_token 接口调用凭证
     * @param string $priTmplId 要删除的模板id
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function deleteTemplate(string $access_token, string $priTmplId)
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/deltemplate?access_token={$access_token}";
        $data = [
            'priTmplId' => $priTmplId
        ];
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->toArray();
    }

    /**
     * 获取小程序账号的类目
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.getCategory.html
     * @param string $access_token 接口调用凭证
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function getCategory(string $access_token)
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getcategory?access_token={$access_token}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 获取模板标题下的关键词列表
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.getPubTemplateKeyWordsById.html
     * @param string $access_token 接口调用凭证
     * @param string $tid 模板标题 id
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function getPubTemplateKeyWordsById(string $access_token, string $tid)
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getpubtemplatekeywords?access_token={$access_token}";
        $data = [
            'tid' => $tid
        ];
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->toArray();
    }

    /**
     * 获取帐号所属类目下的公共模板标题
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.getPubTemplateTitleList.html
     * @param string $access_token 接口调用凭证
     * @param array $data
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function getPubTemplateTitleList(string $access_token, array $data = [])
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getpubtemplatetitles?access_token={$access_token}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->toArray();
    }

    /**
     * 获取当前帐号下的个人模板列表
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.getTemplateList.html
     * @param string $access_token 接口调用凭证
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function getTemplateList(string $access_token)
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/gettemplate?access_token={$access_token}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 发送订阅消息
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.send.html
     * @param string $access_token 接口调用凭证
     * @param array $data
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function send(string $access_token, array $data = [])
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token={$access_token}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }
}
