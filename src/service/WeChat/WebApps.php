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
use DtApp\ThinkLibrary\exception\WeChatException;
use DtApp\ThinkLibrary\facade\Pregs;
use DtApp\ThinkLibrary\facade\Urls;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\Curl\HttpService;

/**
 * Class WebApps
 * @package DtApp\ThinkLibrary\service\WeChat
 */
class WebApps extends Service
{
    private $open_url = "https://open.weixin.qq.com/";
    private $api_url = "https://api.weixin.qq.com/";

    /**
     * 公众号的唯一标识
     * @var
     */
    private $app_id;

    /**
     * 公众号的appsecret
     * @var
     */
    private $app_secret;

    /**
     * 授权后重定向的回调链接地址， 请使用 urlEncode 对链接进行处理
     * @var
     */
    private $redirect_uri;

    /**
     * 返回类型，请填写code
     * @var string
     */
    private $response_type = 'code';
    private $scope = "snsapi_base";
    private $state = "";
    private $grant_type = "authorization_code";

    /**
     * 公众号的唯一标识
     * @param string $appId
     * @return $this
     */
    public function appId(string $appId)
    {
        $this->app_id = $appId;
        return $this;
    }

    /**
     * 公众号的appsecret
     * @param string $appSecret
     * @return $this
     */
    public function appSecret(string $appSecret)
    {
        $this->app_secret = $appSecret;
        return $this;
    }

    /**
     * 授权后重定向的回调链接地址， 请使用 urlEncode 对链接进行处理
     * @param string $redirectUri
     * @return $this
     * @throws WeChatException
     */
    public function redirectUri(string $redirectUri)
    {
        if (empty(Pregs::isLink($redirectUri))) throw new WeChatException("请检查redirectUri，是否正确");
        $this->redirect_uri = Urls::lenCode($redirectUri);
        return $this;
    }

    /**
     * 应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且， 即使在未关注的情况下，只要用户授权，也能获取其信息 ）
     * @param string $scope
     * @return $this
     * @throws WeChatException
     */
    public function scope(string $scope)
    {
        if ($scope === "snsapi_base") $this->scope = $scope;
        elseif ($scope === "snsapi_userinfo") $this->scope = $scope;
        else throw new WeChatException("请检查scope参数");
        return $this;
    }

    /**
     * 重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值，最多128字节
     * @param string $state
     * @return $this
     */
    public function state(string $state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * 网页授权
     * https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html#0
     * @throws WeChatException
     */
    public function oauth2()
    {
        if (strlen($this->state) > 128) throw new WeChatException("请检查state参数，最多128字节");
        $params = Urls::toParams([
            'appid' => $this->app_id,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => $this->response_type,
            'scope' => $this->scope,
            'state' => $this->state
        ]);
        return header("Location:{$this->open_url}connect/oauth2/authorize?$params#wechat_redirect");
    }

    /**
     * 通过code换取网页授权access_token
     * @param string $code
     * @param bool $is
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function accessToken(string $code, bool $is = true)
    {
        return HttpService::instance()
            ->url("{$this->api_url}sns/oauth2/access_token?appid={$this->app_id}&secret={$this->app_secret}&code={$code}&grant_type={$this->grant_type}")
            ->toArray($is);
    }

    /**
     * 刷新access_token（如果需要）
     * @param string $refreshToken
     * @param bool $is
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function refreshToken(string $refreshToken, bool $is = true)
    {
        $this->grant_type = "refresh_token";
        return HttpService::instance()
            ->url("{$this->api_url}sns/oauth2/refresh_token?appid={$this->app_id}&grant_type={$this->grant_type}&refresh_token={$refreshToken}")
            ->toArray($is);
    }

    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     * @param string $accessToken
     * @param string $openid
     * @param string $lang
     * @param bool $is
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function useInfo(string $accessToken, string $openid, $lang = "zh_CN", bool $is = true)
    {
        return HttpService::instance()
            ->url("{$this->api_url}sns/userinfo?access_token={$accessToken}&openid={$openid}&lang={$lang}")
            ->toArray($is);
    }

    /**
     * 检验授权凭证（access_token）是否有效
     * @param string $accessToken
     * @param string $openid
     * @param bool $is
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function auth(string $accessToken, string $openid, bool $is = true)
    {
        return HttpService::instance()
            ->url("{$this->api_url}sns/auth?access_token={$accessToken}&openid={$openid}")
            ->toArray($is);
    }

    /**
     * 分享
     * @return array
     * @throws CurlException
     * @throws WeChatException
     */
    public function share()
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        if (!isset($accessToken['access_token'])) throw  new WeChatException("获取access_token错误，" . $accessToken['errmsg']);
        $res = HttpService::instance()
            ->url("{$this->api_url}cgi-bin/ticket/getticket?access_token={$accessToken['access_token']}&type=jsapi")
            ->toArray();
        if (!empty($res['errcode'])) throw new WeChatException('accessToken已过期');
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 获得jsapi_ticket之后，就可以生成JS-SDK权限验证的签名了。
        $jsapiTicket = $res['ticket'];
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        return [
            "appId" => $this->app_id,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => sha1($string),
            "rawString" => $string
        ];
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        return $str;
    }

    /**
     * 生成二维码
     * @param array $data
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function qrCode(array $data)
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        return HttpService::instance()
            ->url("{$this->api_url}cgi-bin/qrcode/create?access_token={$accessToken['access_token']}")
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 发送模板消息
     * @param array $data
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function messageTemplateSend(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$accessToken['access_token']}";
        if (is_array($data)) $data = json_encode($data);
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->toArray();
    }

    /**
     * 获取access_token信息
     * @return array|bool|mixed|string|string[]
     * @throws CurlException
     */
    private function getAccessToken()
    {
        $this->grant_type = "client_credential";
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
    }
}
