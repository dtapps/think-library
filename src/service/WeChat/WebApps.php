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
    private $url = "https://open.weixin.qq.com/";

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
        return header("Location:{$this->url}connect/oauth2/authorize?$params#wechat_redirect");
    }

    /**
     * 通过code换取网页授权access_token
     * @param string $code
     * @param bool $is
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function accessToken(string $code, bool $is = false)
    {
        return HttpService::instance()
            ->url("{$this->url}/sns/oauth2/access_token?appid={$this->app_id}&secret={$this->app_secret}&code={$code}&grant_type={$this->grant_type}")
            ->toArray($is);
    }

    /**
     * 刷新access_token（如果需要）
     * @param string $refreshToken
     * @param bool $is
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function refreshToken(string $refreshToken, bool $is = false)
    {
        $this->grant_type = "refresh_token";
        return HttpService::instance()
            ->url("{$this->url}/sns/oauth2/refresh_token?appid={$this->app_id}&grant_type={$this->grant_type}&refresh_token={$refreshToken}")
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
    public function useInfo(string $accessToken, string $openid, $lang = "zh_CN", bool $is = false)
    {
        return HttpService::instance()
            ->url("{$this->url}/sns/userinfo?access_token={$accessToken}&openid={$openid}&lang={$lang}")
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
    public function auth(string $accessToken, string $openid, bool $is = false)
    {
        return HttpService::instance()
            ->url("{$this->url}/sns/auth?access_token={$accessToken}&openid={$openid}")
            ->toArray($is);
    }
}
