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

namespace DtApp\ThinkLibrary\service\pinduoduo;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\Service;
use think\exception\HttpException;

/**
 * 进宝
 * Class JinBaoService
 * @package DtApp\ThinkLibrary\service\PinDuoDuo
 */
class JinBaoService extends Service
{
    /**
     * 接口地址
     * @var
     */
    private $url = 'http://gw-api.pinduoduo.com/api/router';

    /**
     * API接口名称
     * @var string
     */
    private $type = '', $response = '';

    /**
     * 开放平台分配的clientId
     * @var string
     */
    private $client_id = '';

    /**
     * 开放平台分配的clientSecret
     * @var string
     */
    private $client_secret = '';

    /**
     * 通过code获取的access_token(无需授权的接口，该字段不参与sign签名运算)
     * @var string
     */
    private $access_token = '';

    /**
     * 响应格式，即返回数据的格式，JSON或者XML（二选一），默认JSON，注意是大写
     * @var string
     */
    private $data_type = 'JSON';

    /**
     * API协议版本号，默认为V1，可不填
     * @var string
     */
    private $version = 'v1';

    /**
     * 需要发送的的参数
     * @var
     */
    private $param;

    /**
     * 响应内容
     * @var
     */
    private $output;

    /*
     * 配置开放平台分配的clientId
     */
    public function clientId(string $clientId)
    {
        $this->client_id = $clientId;
        return $this;
    }

    /**
     * 配置开放平台分配的clientSecret
     * @param string $clientSecret
     * @return $this
     */
    public function clientSecret(string $clientSecret)
    {
        $this->client_secret = $clientSecret;
        return $this;
    }

    /**
     * 响应格式，即返回数据的格式，JSON或者XML（二选一），默认JSON，注意是大写
     * @param string $dataType
     * @return $this
     */
    public function dataType(string $dataType)
    {
        $this->data_type = $dataType;
        return $this;
    }

    /**
     * 请求参数
     * @param array $param
     * @return $this
     */
    public function param(array $param)
    {
        $this->param = $param;
        return $this;
    }

    /**
     * 网络请求
     * @return JinBaoService
     * @throws DtaException
     */
    private function http()
    {
        //生成签名
        $sign = $this->createSign();
        //组织参数
        $strParam = $this->createStrParam();
        $strParam .= 'sign=' . $sign;
        //访问服务
        $url = "{$this->url}?" . $strParam;
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        $this->output = $result;
        return $this;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig()
    {
        $this->client_id = $this->app->config->get('dtapp.pinduoduo.jinbao.client_id');
        $this->client_secret = $this->app->config->get('dtapp.pinduoduo.jinbao.client_secret');
        return $this;
    }

    /**
     * 获取商品信息 - 多多进宝商品查询
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.search
     * @return array|mixed
     */
    public function goodsSearch()
    {
        $this->type = 'pdd.ddk.goods.search';
        $this->response = 'goods_search_response';
        return $this;
    }

    /**
     * 新增推广位 - 创建多多进宝推广位
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.pid.generate
     * @return array|mixed
     */
    public function goodsPidGenerate()
    {
        $this->type = 'pdd.ddk.goods.pid.generate';
        $this->response = 'p_id_generate_response';
        return $this;
    }

    /**
     * 管理推广位 - 查询已经生成的推广位信息
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.pid.query
     * @return array|mixed
     */
    public function goodsPidQuery()
    {
        $this->type = 'pdd.ddk.goods.pid.query';
        $this->response = 'p_id_query_response';
        return $this;
    }

    /**
     * CPS订单数据 - 查询订单详情
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.order.detail.get
     * @return array|mixed
     */
    public function orderDetailGet()
    {
        $this->type = 'pdd.ddk.order.detail.get';
        $this->response = 'order_detail_response';
        return $this;
    }

    /**
     * CPS订单数据 - 最后更新时间段增量同步推广订单信息
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.order.list.increment.get
     * @return array|mixed
     */
    public function orderListIncrementGet()
    {
        $this->type = 'pdd.ddk.order.list.increment.get';
        $this->response = 'order_list_get_response';
        return $this;
    }

    /**
     * CPS订单数据 - 用时间段查询推广订单接口
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.order.list.range.get
     * @return array|mixed
     */
    public function orderListRangeGet()
    {
        $this->type = 'pdd.ddk.order.list.range.get';
        $this->response = 'order_list_get_response';
        return $this;
    }

    /**
     * CPA效果数据 - 查询CPA数据
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.finance.cpa.query
     * @return array|mixed
     */
    public function financeCpaQuery()
    {
        $this->type = 'pdd.ddk.finance.cpa.query';
        $this->response = 'finance_cpa_query_response';
        return $this;
    }

    /**
     * 单品推广- 多多进宝推广链接生成
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.promotion.url.generate
     * @return array|mixed
     */
    public function goodsPromotionUrlGenerate()
    {
        $this->type = 'pdd.ddk.goods.promotion.url.generate';
        $this->response = 'goods_promotion_url_generate_response';
        return $this;
    }

    /**
     * 单品推广- 多多客生成单品推广小程序二维码url
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.weapp.qrcode.url.gen
     * @return array|mixed
     */
    public function weAppQrcodeUrlGen()
    {
        $this->type = 'pdd.ddk.weapp.qrcode.url.gen';
        $this->response = 'weapp_qrcode_generate_response';
        return $this;
    }

    /**
     * 单品推广- 多多进宝转链接口
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.zs.unit.url.gen
     * @return array|mixed
     */
    public function goodsZsUitUrlGen()
    {
        $this->type = 'pdd.ddk.goods.zs.unit.url.gen';
        $this->response = 'goods_zs_unit_generate_response';
        return $this;
    }

    /**
     * 活动转链 - 生成多多进宝频道推广
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.resource.url.gen
     * @return array|mixed
     */
    public function resourceUrlGen()
    {
        $this->type = 'pdd.ddk.resource.url.gen';
        $this->response = 'resource_url_response';
        return $this;
    }

    /**
     * 活动转链 - 多多进宝主题推广链接生成
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.theme.prom.url.generate
     * @return array|mixed
     */
    public function themePromUrlGenerate()
    {
        $this->type = 'pdd.ddk.theme.prom.url.generate';
        $this->response = 'theme_promotion_url_generate_response';
        return $this;
    }

    /**
     * 店铺推广 - 多多客生成店铺推广链接
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.mall.url.gen
     * @return array|mixed
     */
    public function mallUrlGen()
    {
        $this->type = 'pdd.ddk.mall.url.gen';
        $this->response = 'mall_coupon_generate_url_response';
        return $this;
    }

    /**
     * 营销工具 - 生成营销工具推广链接
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.rp.prom.url.generate
     * @return array|mixed
     */
    public function rpPromUrlGenerate()
    {
        $this->type = 'pdd.ddk.rp.prom.url.generate';
        $this->response = 'rp_promotion_url_generate_response';
        return $this;
    }

    /**
     * 获取商品信息 - 多多进宝商品详情查询
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.detail
     * @return array|mixed
     */
    public function goodsDetail()
    {
        $this->type = 'pdd.ddk.goods.detail';
        $this->response = 'goods_detail_response';
        return $this;
    }

    /**
     * 获取商品信息 - 查询商品的推广计划
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.unit.query
     * @return array|mixed
     */
    public function goodsUnitQuery()
    {
        $this->type = 'pdd.ddk.goods.unit.query';
        $this->response = 'ddk_goods_unit_query_response';
        return $this;
    }

    /**
     * 商品&店铺检索 - 获取商品基本信息接口
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.basic.info.get
     * @return array|mixed
     */
    public function goodsBasicInfoGet()
    {
        $this->type = 'pdd.ddk.goods.basic.info.get';
        $this->response = 'goods_basic_detail_response';
        return $this;
    }

    /**
     * 商品&店铺检索 - 查询优惠券信息
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.coupon.info.query
     * @return array|mixed
     */
    public function couponInfoQuery()
    {
        $this->type = 'pdd.ddk.coupon.info.query';
        $this->response = 'ddk_coupon_info_query_response';
        return $this;
    }

    /**
     * 商品&店铺检索 - 查询店铺商品
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.mall.goods.list.get
     * @return array|mixed
     */
    public function goodsListGet()
    {
        $this->type = 'pdd.ddk.mall.goods.list.get';
        $this->response = 'goods_info_list_response';
        return $this;
    }

    /**
     * 多多客获取爆款排行商品接口
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.top.goods.list.query
     * @return array|mixed
     */
    public function topGoodsListQuery()
    {
        $this->type = 'pdd.ddk.top.goods.list.query';
        $this->response = 'top_goods_list_get_response';
        return $this;
    }

    /**
     * 爆品推荐 - 多多进宝商品推荐API
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.recommend.get
     * @return array|mixed
     */
    public function goodsRecommendGet()
    {
        $this->type = 'pdd.ddk.goods.recommend.get';
        $this->response = 'goods_basic_detail_response';
        return $this;
    }

    /**
     * 爆品推荐 - 多多进宝主题列表查询
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.theme.list.get
     * @return array|mixed
     */
    public function themeListGet()
    {
        $this->type = 'pdd.ddk.theme.list.get';
        $this->response = 'theme_list_get_response';
        return $this;
    }

    /**
     * 活动选品库 - 多多进宝主题商品查询
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.theme.goods.search
     * @return array|mixed
     */
    public function themeGoodsSearch()
    {
        $this->type = 'pdd.ddk.theme.goods.search';
        $this->response = 'theme_list_get_response';
        return $this;
    }

    /**
     * 返回数组数据
     * @return array|mixed
     * @throws DtaException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) throw new HttpException(404, '请开启curl模块！');
        if (empty($this->client_id)) $this->getConfig();
        if (empty($this->client_id)) throw new DtaException('请检查client_id参数');
        $this->param['type'] = $this->type;
        $this->param['client_id'] = $this->client_id;
        $this->param['timestamp'] = time();
        $this->param['data_type'] = $this->data_type;
        $this->param['version'] = $this->version;
        $this->http();
        if (isset($this->output['error_response'])) {
            // 错误
            if (is_array($this->output)) return $this->output;
            if (is_object($this->output)) return $this->object2array($this->output);
            return json_decode($this->output, true);
        } else {
            // 正常
            if (is_array($this->output)) {
                if (isset($this->output["{$this->response}"])) return $this->output["{$this->response}"];
                return $this->output;
            }
            if (is_object($this->output)) {
                $this->output = $this->object2array($this->output);
                if (isset($this->output["$this->response"])) return $this->output["$this->response"];
                return $this->output;
            }
            $this->output = json_decode($this->output, true);
            if (isset($this->output["$this->response"])) return $this->output["$this->response"];
            return $this->output;
        }
    }

    /**
     * @param $object
     * @return array
     */
    private function object2array(&$object)
    {
        if (is_object($object)) $arr = (array)($object);
        else $arr = &$object;
        if (is_array($arr)) foreach ($arr as $varName => $varValue) $arr[$varName] = $this->object2array($varValue);
        return $arr;
    }

    /**
     * 签名
     * @return string
     * @throws DtaException
     */
    private function createSign()
    {
        if (empty($this->client_secret)) $this->getConfig();
        if (empty($this->client_secret)) throw new DtaException('请检查client_secret参数');

        $sign = $this->client_secret;
        ksort($this->param);
        foreach ($this->param as $key => $val) if ($key != '' && $val != '') $sign .= $key . $val;
        $sign .= $this->client_secret;
        $sign = strtoupper(md5($sign));
        return $sign;
    }

    /**
     * 组参
     * @return string
     */
    private function createStrParam()
    {
        $strParam = '';
        foreach ($this->param as $key => $val) if ($key != '' && $val != '' && !is_array($val)) $strParam .= $key . '=' . urlencode($val) . '&';
        return $strParam;
    }

    /**
     * 获取频道ID
     * @return array[]
     */
    public function getChannelTypeList()
    {
        return [
            [
                // https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.recommend.get
                'name' => '商品推荐',
                'list' => [
                    [
                        'name' => '1.9包邮',
                        'channel_type' => 0
                    ],
                    [
                        'name' => '今日爆款',
                        'channel_type' => 1
                    ],
                    [
                        'name' => '品牌清仓',
                        'channel_type' => 2
                    ],
                    [
                        'name' => '相似商品推荐',
                        'channel_type' => 3
                    ],
                    [
                        'name' => '猜你喜欢',
                        'channel_type' => 4
                    ],
                    [
                        'name' => '实时热销',
                        'channel_type' => 5
                    ],
                    [
                        'name' => '实时收益',
                        'channel_type' => 6
                    ],
                    [
                        'name' => '今日畅销',
                        'channel_type' => 7
                    ],
                    [
                        'name' => '高佣榜单',
                        'channel_type' => 8
                    ],
                ]
            ],
        ];
    }

    /**
     * 获取频道来源ID
     * @return array[]
     */
    public function getResourceTypeList()
    {
        return [
            [
                // https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.resource.url.gen
                'name' => '频道推广',
                'list' => [
                    [
                        'name' => '限时秒杀',
                        'resource_type' => 4
                    ],
                    [
                        'name' => '充值中心',
                        'resource_type' => 39997
                    ],
                    [
                        'name' => '转链',
                        'resource_type' => 39998
                    ],
                    [
                        'name' => '百亿补贴',
                        'resource_type' => 39996
                    ],
                ]
            ],
        ];
    }
}
