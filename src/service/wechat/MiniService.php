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
// | gitlab 仓库地址 ：https://gitlab.com/liguangchun/thinklibrary
// | weixin 仓库地址 ：https://git.weixin.qq.com/liguangchun/ThinkLibrary
// | huaweicloud 仓库地址 ：https://codehub-cn-south-1.devcloud.huaweicloud.com/composer00001/ThinkLibrary.git
// | Packagist 地址 ：https://packagist.org/packages/liguangchun/think-library
// +----------------------------------------------------------------------

namespace DtApp\ThinkLibrary\service\wechat;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\curl\HttpService;
use think\db\exception\DbException;

/**
 * 微信小程序
 * Class MiniService
 * @package DtApp\ThinkLibrary\service\WeChat
 */
class MiniService extends Service
{
    /**
     * @var
     */
    private $app_id, $app_secret;

    /**
     * @var string
     */
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
    public function cache(string $cache): self
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig(): self
    {
        $this->cache = config('dtapp.wechat.mini.cache');
        $this->app_id = config('dtapp.wechat.mini.app_id');
        $this->app_secret = config('dtapp.wechat.mini.app_secret');
        return $this;
    }

    /**
     * 用户支付完成后，获取该用户的 UnionId，无需用户授权
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/user-info/auth.getPaidUnionId.html
     * @param string $openid
     * @return bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function getPaidUnionId(string $openid)
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxa/getpaidunionid?access_token={$accessToken['access_token']}&openid={$openid}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 获取小程序二维码，适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.createQRCode.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function createWxaQrCode(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray(false);
    }

    /**
     * 获取小程序码，适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.get.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function getWxaCode(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray(false);
    }

    /**
     * 获取小程序码，适用于需要的码数量极多的业务场景。通过该接口生成的小程序码，永久有效，数量暂无限制
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function getWxaCodeUnLimit(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray(false);
    }

    /**
     * 组合模板并添加至帐号下的个人模板库
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.addTemplate.html
     * @param array $data
     * @return bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function addTemplate(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/addtemplate?access_token={$accessToken['access_token']}";
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
     * @throws DbException
     * @throws DtaException
     */
    public function deleteTemplate(string $priTmplId)
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/deltemplate?access_token={$accessToken['access_token']}";
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
     * @throws DbException
     * @throws DtaException
     */
    public function getCategory()
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getcategory?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 获取模板标题下的关键词列表
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.getPubTemplateKeyWordsById.html
     * @param string $tid 模板标题 id
     * @return bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function getPubTemplateKeyWordsById(string $tid)
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getpubtemplatekeywords?access_token={$accessToken['access_token']}";
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
     * @throws DbException
     * @throws DtaException
     */
    public function getPubTemplateTitleList(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getpubtemplatetitles?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->toArray();
    }

    /**
     * 获取当前帐号下的个人模板列表
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.getTemplateList.html
     * @return bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function getTemplateList()
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/gettemplate?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 发送订阅消息
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.send.html
     * @param array $data
     * @return bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function subscribeMessageSend(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 统一服务消息 - 下发小程序和公众号统一的服务消息
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/uniform-message/uniformMessage.send.html
     * @param array $data
     * @return bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function uniformMessageSend(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/uniform_send?access_token={$accessToken['access_token']}";
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
     * @throws DtaException
     */
    public function code2Session(string $js_code)
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
        $this->grant_type = "authorization_code";
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->app_id}&secret={$this->app_secret}&js_code={$js_code}&grant_type={$this->grant_type}";
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
     * @throws DtaException
     */
    public function userInfo(string $js_code, string $encrypted_data, string $iv)
    {
        $session = $this->code2Session($js_code);
        if (!isset($session['openid'])) {
            return false;
        }
        $result = openssl_decrypt(base64_decode($encrypted_data), "AES-128-CBC", base64_decode($session['session_key']), 1, base64_decode($iv));
        return json_decode($result, true);
    }

    /**
     * 数据签名校验，并且获取解密后的明文.
     * @param string $js_code
     * @param string $encrypted_data
     * @param string $iv
     * @return mixed
     * @throws DtaException
     */
    public function userPhone(string $js_code, string $encrypted_data, string $iv)
    {
        $session = $this->code2Session($js_code);
        if (!isset($session['openid'])) {
            return false;
        }
        $result = openssl_decrypt(base64_decode($encrypted_data), "AES-128-CBC", base64_decode($session['session_key']), 1, base64_decode($iv));
        return json_decode($result, true);
    }

    /**
     * 数据签名校验，并且获取解密后的明文.
     * @param string $session_key
     * @param string $encrypted_data
     * @param string $iv
     * @return mixed
     */
    public function decode(string $session_key, string $encrypted_data, string $iv)
    {
        $result = openssl_decrypt(base64_decode($encrypted_data), "AES-128-CBC", base64_decode($session_key), 1, base64_decode($iv));
        return json_decode($result, true);
    }

    /**
     * 【小程序直播】直播间管理接口 - 创建直播间
     * https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/studio-api.html#1
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function broadcastRoomCreate(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/create?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 【小程序直播】直播间管理接口 - 获取直播间列表
     * https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/studio-api.html#2
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function broadcastGetLiveInfos(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxa/business/getliveinfo?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 【小程序直播】直播间管理接口 - 获取直播间回放
     * https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/studio-api.html#3
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function broadcastGetLiveInfo(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxa/business/getliveinfo?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 【小程序直播】直播间管理接口 - 直播间导入商品
     * https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/studio-api.html#4
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function broadcastRoomAddGoods(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/addgoods?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 【小程序直播】直播商品管理接口 - 商品添加并提审
     * https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/commodity-api.html#1
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function broadcastGoodsAdd(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/add?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 【小程序直播】直播商品管理接口 - 撤回审核
     * https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/commodity-api.html#2
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function broadcastGoodsResetAudit(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/resetaudit?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 【小程序直播】直播商品管理接口 - 重新提交审核
     * https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/commodity-api.html#3
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function broadcastGoodsAudit(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/audit?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 【小程序直播】直播商品管理接口 - 删除商品
     * https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/commodity-api.html#4
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function broadcastGoodsDelete(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/delete?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 【小程序直播】直播商品管理接口 - 更新商品
     * https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/commodity-api.html#5
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function broadcastGoodsUpdate(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/update?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 【小程序直播】直播商品管理接口 - 获取商品状态
     * https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/commodity-api.html#6
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function broadcastGetGoodsWarehouse(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxa/business/getgoodswarehouse?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 【小程序直播】直播商品管理接口 - 获取商品列表
     * https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/commodity-api.html#7
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function broadcastGoodsGetAppRoved(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/getapproved?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 数据分析 - 获取用户访问小程序日留存
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/data-analysis/visit-retain/analysis.getDailyRetain.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function analysisGetDailyRetain(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/datacube/getweanalysisappiddailyretaininfo?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 数据分析 - 获取用户访问小程序月留存
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/data-analysis/visit-retain/analysis.getMonthlyRetain.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function analysisGetMonthlyRetain(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/datacube/getweanalysisappidmonthlyretaininfo?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 数据分析 - 获取用户访问小程序周留存
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/data-analysis/visit-retain/analysis.getWeeklyRetain.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function analysisGetWeeklyRetain(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/datacube/getweanalysisappidweeklyretaininfo?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 数据分析 - 获取用户访问小程序数据概况
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/data-analysis/analysis.getDailySummary.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function analysisGetDailySummary(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/datacube/getweanalysisappiddailysummarytrend?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 数据分析 - 获取小程序新增或活跃用户的画像分布数据。时间范围支持昨天、最近7天、最近30天。其中，新增用户数为时间范围内首次访问小程序的去重用户数，活跃用户数为时间范围内访问过小程序的去重用户数
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/data-analysis/analysis.getUserPortrait.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function analysisGetUserPortrait(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/datacube/getweanalysisappiduserportrait?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 数据分析 - 获取用户访问小程序数据概况
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/data-analysis/analysis.getVisitDistribution.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function analysisGetVisitDistribution(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/datacube/getweanalysisappidvisitdistribution?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 数据分析 - 访问页面。目前只提供按 page_visit_pv 排序的 top200
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/data-analysis/analysis.getVisitPage.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function analysisGetVisitPage(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/datacube/getweanalysisappidvisitpage?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 客服消息 - 获取客服消息内的临时素材。即下载临时的多媒体文件。目前小程序仅支持下载图片文件。
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/customer-message/customerServiceMessage.getTempMedia.html
     * @param string $media_id
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function customerServiceMessageGetTempMedia(string $media_id = '')
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/get";
        return HttpService::instance()
            ->url($url)
            ->data([
                'access_token' => $accessToken['access_token'],
                'media_id' => $media_id
            ])
            ->get()
            ->toArray();
    }

    /**
     * 客服消息 - 发送客服消息给用户。详细规则见 发送客服消息
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/customer-message/customerServiceMessage.send.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function customerServiceMessageSend(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 客服消息 - 下发客服当前输入状态给用户。详见 客服消息输入状态
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/customer-message/customerServiceMessage.setTyping.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function customerServiceMessageSetTyping(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/typing?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 客服消息 - 把媒体文件上传到微信服务器。目前仅支持图片。用于发送客服消息或被动回复用户消息
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/customer-message/customerServiceMessage.uploadTempMedia.html
     * @param array $data
     * @return array|bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function customerServiceMessageUploadTempMedia(array $data = [])
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$accessToken['access_token']}";
        return HttpService::instance()
            ->url($url)
            ->data($data)
            ->post()
            ->toArray();
    }

    /**
     * 获取小程序全局唯一后台接口调用凭据（access_token）
     * https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/access-token/auth.getAccessToken.html
     * @return bool|mixed|string
     * @throws DbException
     * @throws DtaException
     */
    public function accessToken()
    {
        return $this->getAccessToken();
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
                $accessToken_res = HttpService::instance()
                    ->url("https://api.weixin.qq.com/cgi-bin/token?grant_type={$this->grant_type}&appid={$this->app_id}&secret={$this->app_secret}")
                    ->toArray();
                $accessToken_res['expires_time'] = time() + 6000;
                file_put_contents($file, json_encode($accessToken_res, JSON_UNESCAPED_UNICODE));
                $accessToken = $accessToken_res;
            } else if (!isset($accessToken['access_token'])) {
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
}
