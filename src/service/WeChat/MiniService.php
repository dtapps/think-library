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

use DtApp\ThinkLibrary\cache\Mysql;
use DtApp\ThinkLibrary\exception\CacheException;
use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\exception\WeChatException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\Curl\HttpService;

/**
 * 微信小程序
 * Class MiniService
 * @package DtApp\ThinkLibrary\service\WeChat
 */
class MiniService extends Service
{
    private $api_url = "https://api.weixin.qq.com/";

    private $app_id;
    private $app_secret;
    private $grant_type = "client_credential";

    /**
     * 驱动方式
     * @var string
     */
    private $cache = "file";

    /**
     * @param string $appId
     * @return $this
     */
    public function appId(string $appId)
    {
        $this->app_id = $appId;
        return $this;
    }

    /**
     * @param string $appSecret
     * @return $this
     */
    public function appSecret(string $appSecret)
    {
        $this->app_secret = $appSecret;
        return $this;
    }

    /**
     * 驱动方式
     * @param string $cache
     * @return $this
     */
    public function cache(string $cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * 用户支付完成后，获取该用户的 UnionId，无需用户授权
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/user-info/auth.getPaidUnionId.html
     * @param string $openid
     * @return bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function getPaidUnionId(string $openid)
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "{$this->api_url}wxa/getpaidunionid?access_token={$accessToken['access_token']}&openid={$openid}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 获取小程序二维码，适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.createQRCode.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function createWxaQrCode(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "{$this->api_url}cgi-bin/wxaapp/createwxaqrcode?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 获取小程序码，适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.get.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function getWxaCode(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "{$this->api_url}wxa/getwxacode?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 获取小程序码，适用于需要的码数量极多的业务场景。通过该接口生成的小程序码，永久有效，数量暂无限制
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function getWxaCodeUnLimit(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "{$this->api_url}wxa/getwxacodeunlimit?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 组合模板并添加至帐号下的个人模板库
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.addTemplate.html
     * @param array $data
     * @return bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function addTemplate(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "{$this->api_url}wxaapi/newtmpl/addtemplate?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->toArray();
    }

    /**
     * 删除帐号下的个人模板
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.deleteTemplate.html
     * @param string $priTmplId 要删除的模板id
     * @return bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function deleteTemplate(string $priTmplId)
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "{$this->api_url}wxaapi/newtmpl/deltemplate?access_token={$accessToken['access_token']}";
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
     * @return bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function getCategory()
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "{$this->api_url}wxaapi/newtmpl/getcategory?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 获取模板标题下的关键词列表
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.getPubTemplateKeyWordsById.html
     * @param string $tid 模板标题 id
     * @return bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function getPubTemplateKeyWordsById(string $tid)
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "{$this->api_url}wxaapi/newtmpl/getpubtemplatekeywords?access_token={$accessToken['access_token']}";
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
     * @param array $data
     * @return bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function getPubTemplateTitleList(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "{$this->api_url}wxaapi/newtmpl/getpubtemplatetitles?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->toArray();
    }

    /**
     * 获取当前帐号下的个人模板列表
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.getTemplateList.html
     * @return bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function getTemplateList()
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "{$this->api_url}wxaapi/newtmpl/gettemplate?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 发送订阅消息
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.send.html
     * @param array $data
     * @return bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function send(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "{$this->api_url}cgi-bin/message/subscribe/send?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 登录凭证校验
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/login/auth.code2Session.html
     * @param string $js_code
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function code2Session(string $js_code)
    {
        $this->grant_type = "authorization_code";
        $url = "{$this->api_url}sns/jscode2session?appid={$this->app_id}&secret={$this->app_secret}&js_code={$js_code}&grant_type={$this->grant_type}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param string $js_code
     * @param string $encrypted_data
     * @param string $iv
     * @return bool|mixed
     * @throws CurlException
     */
    public function userInfo(string $js_code, string $encrypted_data, string $iv)
    {
        $session = $this->code2Session($js_code);
        if (!isset($session['openid'])) return false;
        $result = openssl_decrypt(base64_decode($encrypted_data), "AES-128-CBC", base64_decode($session['session_key']), 1, base64_decode($iv));
        return json_decode($result, true);
    }

    /**
     * 获取小程序全局唯一后台接口调用凭据（access_token）
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/access-token/auth.getAccessToken.html
     * @return bool|mixed|string
     * @throws CacheException
     * @throws CurlException
     * @throws WeChatException
     */
    public function accessToken()
    {
        // 获取数据
        return $this->getAccessToken();
    }

    /**
     * 获取access_token信息
     * @return array|bool|mixed|string|string[]
     * @throws CurlException
     * @throws WeChatException
     * @throws CacheException
     */
    private function getAccessToken()
    {

        $this->grant_type = "client_credential";
        if ($this->cache == "file") {
            // 文件名
            $file = "{$this->app->getRootPath()}runtime/{$this->app_id}_access_token.json";
            // 获取数据
            $accessToken = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
            if (empty($accessToken) || !is_array($accessToken)) $accessToken = [
                'access_token' => '',
                'expires_in' => '',
                'expires_time' => '',
            ];
            if (empty($accessToken['expires_time'])) {
                $accessToken_res = HttpService::instance()
                    ->url("{$this->api_url}cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                    ->toArray();
                $accessToken_res['expires_time'] = time() + 6000;
                file_put_contents($file, json_encode($accessToken_res, JSON_UNESCAPED_UNICODE));
                $accessToken = $accessToken_res;
            } else if (!isset($accessToken['access_token'])) {
                $accessToken_res = HttpService::instance()
                    ->url("{$this->api_url}cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                    ->toArray();
                $accessToken_res['expires_time'] = time() + 6000;
                file_put_contents($file, json_encode($accessToken_res, JSON_UNESCAPED_UNICODE));
                $accessToken = $accessToken_res;
            } else if ($accessToken['expires_time'] <= time()) {
                $accessToken_res = HttpService::instance()
                    ->url("{$this->api_url}cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                    ->toArray();
                $accessToken_res['expires_time'] = time() + 6000;
                file_put_contents($file, json_encode($accessToken_res, JSON_UNESCAPED_UNICODE));
                $accessToken = $accessToken_res;
            }
            return $accessToken;
        } else if ($this->cache == "mysql") {
            $access_token = [];
            // 文件名
            $file = "{$this->app_id}_access_token";
            // 获取数据
            $cache_mysql = new Mysql();
            $cache_mysql_value = $cache_mysql
                ->name($file)
                ->get();
            if (!empty($cache_mysql_value)) {
                $access_token['access_token'] = $cache_mysql_value;
            } else {
                $accessToken_res = HttpService::instance()
                    ->url("{$this->api_url}cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                    ->toArray();
                $cache_mysql
                    ->name($file)
                    ->expire(time() + 6000)
                    ->set($accessToken_res['access_token']);
                $access_token['access_token'] = $accessToken_res['access_token'];
            }
            return $access_token;
        } else throw new WeChatException("驱动方式错误");
    }
}
