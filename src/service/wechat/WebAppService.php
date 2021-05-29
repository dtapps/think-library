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

namespace DtApp\ThinkLibrary\service\wechat;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\facade\Pregs;
use DtApp\ThinkLibrary\facade\Randoms;
use DtApp\ThinkLibrary\facade\Urls;
use DtApp\ThinkLibrary\facade\Xmls;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\curl\HttpService;
use think\db\exception\DbException;

/**
 * 公众号
 * Class WebAppService
 * @package DtApp\ThinkLibrary\service\WeChat
 */
class WebAppService extends Service
{
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

    /**
     * @var string
     */
    private $scope = "snsapi_base";

    /**
     * @var string
     */
    private $state = "";

    /**
     * @var string
     */
    private $grant_type = "authorization_code";

    /**
     * 驱动方式
     * @var string
     */
    private $cache = "file";

    /**
     * 商户平台设置的密钥key
     * @var
     */
    private $mch_key;

    /**
     * @param string $mchKey
     * @return $this
     */
    public function mchKey(string $mchKey)
    {
        $this->mch_key = $mchKey;
        return $this;
    }

    /**
     * 商户号
     * @var
     */
    private $mch_id;

    /**
     * 商户号
     * @param string $mchId
     * @return $this
     */
    public function mchId(string $mchId)
    {
        $this->mch_id = $mchId;
        return $this;
    }

    /**
     * 公众号的唯一标识
     * @param string $appId
     * @return $this
     */
    public function appId(string $appId): self
    {
        $this->app_id = $appId;
        return $this;
    }

    /**
     * 公众号的appsecret
     * @param string $appSecret
     * @return $this
     */
    public function appSecret(string $appSecret): self
    {
        $this->app_secret = $appSecret;
        return $this;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig(): self
    {
        $this->cache = config('dtapp.wechat.webapp.cache');
        $this->app_id = config('dtapp.wechat.webapp.app_id');
        $this->app_secret = config('dtapp.wechat.webapp.app_secret');
        return $this;
    }

    /**
     * 授权后重定向的回调链接地址， 请使用 urlEncode 对链接进行处理
     * @param string $redirectUri
     * @return $this
     * @throws DtaException
     */
    public function redirectUri(string $redirectUri)
    {
        if (empty(Pregs::isLink($redirectUri))) {
            throw new DtaException("请检查redirectUri，是否正确");
        }
        $this->redirect_uri = Urls::lenCode($redirectUri);
        return $this;
    }

    /**
     * 应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且， 即使在未关注的情况下，只要用户授权，也能获取其信息 ）
     * @param string $scope
     * @return $this
     * @throws DtaException
     */
    public function scope(string $scope): self
    {
        if ($scope === "snsapi_base") {
            $this->scope = $scope;
        } elseif ($scope === "snsapi_userinfo") {
            $this->scope = $scope;
        } else {
            throw new DtaException("请检查scope参数");
        }
        return $this;
    }

    /**
     * 重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值，最多128字节
     * @param string $state
     * @return $this
     */
    public function state(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * 驱动方式
     * @param string $cache
     * @return $this
     */
    public function cache(string $cache): self
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * 网页授权
     * https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html#0
     * @return void
     * @throws DtaException
     */
    public function oauth2()
    {
        if (empty($this->app_id)) {
            $this->getConfig();
        }
        if (strlen($this->state) > 128) {
            throw new DtaException("请检查state参数，最多128字节");
        }
        $params = Urls::toParams([
            'appid' => $this->app_id,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => $this->response_type,
            'scope' => $this->scope,
            'state' => $this->state
        ]);
        return header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?$params#wechat_redirect");
    }

    /**
     * 通过code换取网页授权access_token
     * @param string $code
     * @param bool $is
     * @return array|bool|mixed|string
     * @throws DtaException
     */
    public function accessToken(string $code, bool $is = true)
    {
        if (empty($this->app_id) || empty($this->app_secret)) {
            $this->getConfig();
        }
        if (empty($this->app_id)) {
            throw new DtaException('请检查app_id参数');
        }
        if (empty($this->app_secret)) {
            throw new DtaException('请检查app_secret参数');
        }
        return HttpService::instance()
            ->url("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->app_id}&secret={$this->app_secret}&code={$code}&grant_type={$this->grant_type}")
            ->toArray($is);
    }

    /**
     * 刷新access_token（如果需要）
     * @param string $refreshToken
     * @param bool $is
     * @return array|bool|mixed|string
     * @throws DtaException
     */
    public function refreshToken(string $refreshToken, bool $is = true)
    {
        if (empty($this->app_id)) {
            $this->getConfig();
        }
        if (empty($this->app_id)) {
            throw new DtaException('请检查app_id参数');
        }
        $this->grant_type = "refresh_token";
        return HttpService::instance()
            ->url("https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$this->app_id}&grant_type={$this->grant_type}&refresh_token={$refreshToken}")
            ->toArray($is);
    }

    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     * @param string $accessToken
     * @param string $openid
     * @param string $lang
     * @param bool $is
     * @return array|bool|mixed|string
     */
    public function useInfo(string $accessToken, string $openid, $lang = "zh_CN", bool $is = true)
    {
        if (empty($this->app_id) || empty($this->app_secret)) {
            $this->getConfig();
        }
        return HttpService::instance()
            ->url("https://api.weixin.qq.com/sns/userinfo?access_token={$accessToken}&openid={$openid}&lang={$lang}")
            ->toArray($is);
    }

    /**
     * 检验授权凭证（access_token）是否有效
     * @param string $accessToken
     * @param string $openid
     * @param bool $is
     * @return array|bool|mixed|string
     */
    public function auth(string $accessToken, string $openid, bool $is = true)
    {
        if (empty($this->app_id) || empty($this->app_secret)) {
            $this->getConfig();
        }
        return HttpService::instance()
            ->url("https://api.weixin.qq.com/sns/auth?access_token={$accessToken}&openid={$openid}")
            ->toArray($is);
    }

    /**
     * 分享
     * @param string $url
     * @return array
     * @throws DbException
     * @throws DtaException
     * @throws \Exception
     */
    public function share($url = '')
    {
        if (empty($this->app_id)) {
            $this->getConfig();
        }
        if (empty($this->app_id)) {
            throw new DtaException('请检查app_id参数');
        }
        // 获取数据
        $accessToken = $this->getAccessToken();
        if (!isset($accessToken['access_token'])) {
            throw  new DtaException("获取access_token错误，" . $accessToken['errmsg']);
        }
        $res = HttpService::instance()
            ->url("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$accessToken['access_token']}&type=jsapi")
            ->toArray();
        if (!empty($res['errcode'])) {
            // 获取数据
            $accessToken = $this->getAccessToken();
            if (!isset($accessToken['access_token'])) {
                throw  new DtaException("获取access_token错误，" . $accessToken['errmsg']);
            }
            $res = HttpService::instance()
                ->url("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$accessToken['access_token']}&type=jsapi")
                ->toArray();
            if (!empty($res['errcode'])) {
                throw new DtaException('accessToken已过期');
            }
        }
        if (empty($url)) {
            // 注意 URL 一定要动态获取，不能 hardcode.
            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] === 443) ? "https://" : "http://";
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
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

    /**
     * @param int $length
     * @return string
     * @throws \Exception
     */
    private function createNonceStr($length = 16): string
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $str;
    }

    /**
     * 生成二维码
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function qrCode(array $data)
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        return HttpService::instance()
            ->url("https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$accessToken['access_token']}")
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 发送模板消息
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function messageTemplateSend(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->toArray();
    }

    /**
     * 设置所属行业
     * https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#0
     * @param string $access_token
     * @param array $data
     * @return bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function setIndustry(string $access_token, array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->toArray();
    }

    /**
     * 将一条长链接转成短链接
     * @param string $long_url
     * @return bool
     * @throws DbException
     * @throws DtaException
     */
    public function shortUrl(string $long_url)
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data([
                'action' => 'long2short',
                'long_url' => $long_url
            ])
            ->toArray();
    }

    /**
     * 连Wi-Fi完成页跳转小程序
     * https://developers.weixin.qq.com/doc/offiaccount/WiFi_via_WeChat/WiFi_mini_programs.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function fiNihPageSet(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/bizwifi/finishpage/set?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->post()
            ->data($data)
            ->toArray();
    }

    /**
     * 自定义菜单 获取自定义菜单配置
     * https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Getting_Custom_Menu_Configurations.html
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function menuGet()
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 自定义菜单 创建个性化菜单
     * https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Personalized_menu_interface.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function menuAddConditional(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->post()
            ->data($data)
            ->toArray();
    }

    /**
     * 自定义菜单 删除个性化菜单
     * https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Personalized_menu_interface.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function menuDelConditional(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->post()
            ->data($data)
            ->toArray();
    }

    /**
     * 自定义菜单 测试个性化菜单匹配结果
     * https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Personalized_menu_interface.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function menuTryMatch(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/trymatch?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->post()
            ->data($data)
            ->toArray();
    }

    /**
     * 自定义菜单 删除接口
     * https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Deleting_Custom-Defined_Menu.html
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function menuDelete()
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 自定义菜单 查询接口
     * https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Querying_Custom_Menus.html
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function getCurrentSelfmenuInfo()
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 自定义菜单 创建接口
     * https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Creating_Custom-Defined_Menu.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function menuCreate(array $data = [])
    {
        // 获取数据
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->post()
            ->data($data)
            ->toArray();
    }

    /**
     * 获取access_token信息
     * @return array|bool|mixed|string|string[]
     * @throws DbException
     * @throws DtaException
     */
    private function getAccessToken()
    {
        if (empty($this->cache) || empty($this->app_id) || empty($this->app_secret)) {
            $this->getConfig();
        }
        if (empty($this->cache)) {
            throw new DtaException('请检查cache参数');
        }
        if (empty($this->app_id)) {
            throw new DtaException('请检查app_id参数');
        }
        if (empty($this->app_secret)) {
            throw new DtaException('请检查app_secret参数');
        }

        $this->grant_type = "client_credential";
        if ($this->cache === "file") {
            // 文件名
            $file = "{$this->app->getRootPath()}runtime/{$this->app_id}_access_token.json";
            // 获取数据
            $accessToken = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
            if (empty($accessToken) || !is_array($accessToken)) {
                $accessToken = [
                    'access_token' => '',
                    'expires_in' => '',
                    'expires_time' => '',
                ];
            }
            if (empty($accessToken['expires_time'])) {
                // 文件不存在
                $accessToken_res = HttpService::instance()
                    ->url("https://api.weixin.qq.com/cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                    ->toArray();
                $accessToken_res['expires_time'] = time() + 6000;
                file_put_contents($file, json_encode($accessToken_res, JSON_UNESCAPED_UNICODE));
                $accessToken = $accessToken_res;
            } else if (!isset($accessToken['access_token'])) {
                // 内容不存在
                $accessToken_res = HttpService::instance()
                    ->url("https://api.weixin.qq.com/cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                    ->toArray();
                $accessToken_res['expires_time'] = time() + 6000;
                file_put_contents($file, json_encode($accessToken_res, JSON_UNESCAPED_UNICODE));
                $accessToken = $accessToken_res;
            } else if ($accessToken['expires_time'] <= time()) {
                $accessToken_res = HttpService::instance()
                    ->url("https://api.weixin.qq.com/cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                    ->toArray();
                $accessToken_res['expires_time'] = time() + 6000;
                file_put_contents($file, json_encode($accessToken_res, JSON_UNESCAPED_UNICODE));
                $accessToken = $accessToken_res;
            }
            if (isset($accessToken['access_token'])) {
                $judge = HttpService::instance()
                    ->url("https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token={$accessToken['access_token']}")
                    ->toArray();
                if (!isset($judge['ip_list'])) {
                    $accessToken_res = HttpService::instance()
                        ->url("https://api.weixin.qq.com/cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                        ->toArray();
                    $accessToken_res['expires_time'] = time() + 6000;
                    file_put_contents($file, json_encode($accessToken_res, JSON_UNESCAPED_UNICODE));
                    $accessToken = $accessToken_res;
                }
            } else {
                $accessToken_res = HttpService::instance()
                    ->url("https://api.weixin.qq.com/cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                    ->toArray();
                $accessToken_res['expires_time'] = time() + 6000;
                file_put_contents($file, json_encode($accessToken_res, JSON_UNESCAPED_UNICODE));
                $accessToken = $accessToken_res;
            }
            return $accessToken;
        }

        if ($this->cache === "mysql") {
            $access_token = [];
            // 文件名
            $file = "{$this->app_id}_access_token";
            // 获取数据
            $cache_mysql_value = dtacache($file);
            if (!empty($cache_mysql_value)) {
                $access_token['access_token'] = $cache_mysql_value;
            } else {
                // 获取远程Token
                $accessToken_res = HttpService::instance()
                    ->url("https://api.weixin.qq.com/cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                    ->toArray();
                // 保存到数据库
                dtacache($file, $accessToken_res['access_token'], 6000);
                $access_token['access_token'] = $accessToken_res['access_token'];
            }
            // 判断token是否可以使用
            $judge = HttpService::instance()
                ->url("https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token={$access_token['access_token']}")
                ->toArray();
            if (!isset($judge['ip_list'])) {
                $accessToken_res = HttpService::instance()
                    ->url("https://api.weixin.qq.com/cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                    ->toArray();
                dtacache($file, $accessToken_res['access_token'], 6000);
                $access_token['access_token'] = $accessToken_res['access_token'];
            }
            return $access_token;
        }

        throw new DtaException("驱动方式错误");
    }

    /**
     * 微信支付
     * https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_1
     * @param array $array
     * @return bool|string
     */
    public function payUnfIedOrder(array $array)
    {
        $array['appid'] = $this->app_id;
        $array['mch_id'] = $this->mch_id;
        $array['nonce_str'] = Randoms::generate(32, 3);
        $array['sign_type'] = 'HMAC-SHA256';
        $array['sign'] = $this->paySign($array);
        $res = $this->postXmlCurl(Xmls::toXml($array));
        return Xmls::toArray($res);
    }

    /**
     * 微信内H5调起支付
     * @param string $prepay_id
     * @return array
     */
    public function h5Pay(string $prepay_id)
    {
        $array['appId'] = $this->app_id;
        $array['timeStamp'] = time();
        $array['nonceStr'] = Randoms::generate(32, 3);
        $array['package'] = "prepay_id={$prepay_id}";
        $array['signType'] = 'HMAC-SHA256';
        $array['paySign'] = $this->paySign($array);
        return $array;
    }

    /**
     * 生成支付签名
     * @param array $array 参与签名的内容组成的数组
     * @param bool $hmacsha256 是否使用 HMAC-SHA256算法，否则使用MD5
     * @return string
     */
    private function paySign(array $array, bool $hmacsha256 = true): string
    {
        // 排序
        ksort($array);
        // 转成字符串
        $stringA = Urls::toParams($array);
        // 在字符串接商户支付秘钥
        $stringSignTemp = "{$stringA}&key=" . $this->mch_key;
        //步骤四：MD5或HMAC-SHA256C加密
        if ($hmacsha256) {
            $str = hash_hmac("sha256", $stringSignTemp, $this->mch_key);
        } else {
            $str = md5($stringSignTemp);
        }
        //符转大写
        return strtoupper($str);
    }

    /**
     * @param $xml
     * @return bool|string
     */
    private function postXmlCurl($xml)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_URL, "https://api.mch.weixin.qq.com/pay/unifiedorder");
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //试试手气新增，增加之后 curl 不报 60# 错误，可以请求到微信的响应
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //不验证 SSL 证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//不验证 SSL 证书域名
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        }

        $error = curl_errno($ch);
        curl_close($ch);
        return "curl error, error code " . $error;
    }
}
