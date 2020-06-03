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

namespace DtApp\ThinkLibrary\service\jd;

use DtApp\ThinkLibrary\exception\JdException;
use DtApp\ThinkLibrary\facade\Strings;
use DtApp\ThinkLibrary\Service;
use think\exception\HttpException;

/**
 * 京东联盟
 * Class UnionService
 * @package DtApp\ThinkLibrary\service\Jd
 */
class UnionService extends Service
{
    /**
     * 接口地址
     * @var string
     */
    private $url = "https://router.jd.com/api";

    /**
     * API接口名称
     * @var
     */
    private $method = '';
    private $response = '';

    /**
     * 联盟分配给应用的appkey
     * @var
     */
    private $app_key = '';

    /**
     * 联盟分配给应用的secretkey
     * @var
     */
    private $secret_key = '';

    /**
     * 根据API属性标签，如果需要授权，则此参数必传;如果不需要授权，则此参数不需要传
     * @var
     */
    private $access_token = '';

    /**
     * 响应格式，暂时只支持json
     * @var string
     */
    private $format = "json";

    /**
     * API协议版本，请根据API具体版本号传入此参数，一般为1.0
     * @var string
     */
    private $v = "1.0";

    /**
     * 签名的摘要算法，暂时只支持md5
     * @var string
     */
    private $sign_method = "md5";

    /**
     * 响应内容
     * @var
     */
    private $output;

    /**
     * 需要发送的的参数
     * @var
     */
    private $param;
    private $params;

    /**
     * 联盟分配给应用的appkey
     * @param string $appKey
     * @return $this
     */
    public function appKey(string $appKey)
    {
        $this->app_key = $appKey;
        return $this;
    }

    /**
     * 联盟分配给应用的secretkey
     * @param string $secretKey
     * @return $this
     */
    public function secretKey(string $secretKey)
    {
        $this->secret_key = $secretKey;
        return $this;
    }

    /**
     * 根据API属性标签，如果需要授权，则此参数必传;如果不需要授权，则此参数不需要传
     * @param string $accessToken
     * @return $this
     */
    public function accessToken(string $accessToken)
    {
        $this->access_token = $accessToken;
        return $this;
    }

    /**
     * 组参
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
     * @throws JdException
     */
    private function http()
    {
        //生成签名
        $sign = $this->createSign();
        //组织参数
        $strParam = $this->createStrParam();
        $strParam .= 'sign=' . $sign;
        //访问服务
        $result = file_get_contents("{$this->url}?{$strParam}");
        $result = json_decode($result, true);
        $this->output = $result;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig()
    {
        $this->app_key = $this->app->config->get('dtapp.jd.union.app_key');
        $this->secret_key = $this->app->config->get('dtapp.jd.union.secret_key');
        return $this;
    }

    /**
     * 网站/APP获取推广链接接口
     * https://union.jd.com/openplatform/api/10421
     * @return array|mixed
     */
    public function promotionCommonGet()
    {
        $this->method = 'jd.union.open.promotion.common.get';
        return $this;
    }

    /**
     * 社交媒体获取推广链接接口【申请】
     * https://union.jd.com/openplatform/api/10424
     * @return array|mixed
     */
    public function promotionBySubUnionIdGet()
    {
        $this->method = 'jd.union.open.promotion.bysubunionid.get';
        return $this;
    }

    /**
     * 工具商获取推广链接接口【申请】
     * https://union.jd.com/openplatform/api/10425
     * @return array|mixed
     */
    public function promotionByUnionIdGet()
    {
        $this->method = 'jd.union.open.promotion.byunionid.get';
        return $this;
    }

    /**
     * 订单行查询接口
     * https://union.jd.com/openplatform/api/12707
     * @return array|mixed
     */
    public function orderRowQuery()
    {
        $this->method = 'jd.union.open.order.row.query';
        return $this;
    }

    /**
     * 奖励订单查询接口【申请】
     * https://union.jd.com/openplatform/api/11781
     * @return array|mixed
     */
    public function orderBonusQuery()
    {
        $this->method = 'jd.union.open.order.bonus.query';
        return $this;
    }

    /**
     * 创建推广位【申请】
     * https://union.jd.com/openplatform/api/10429
     * @return array|mixed
     */
    public function positionCreate()
    {
        $this->method = 'jd.union.open.position.create';
        return $this;
    }

    /**
     * 查询推广位【申请】
     * https://union.jd.com/openplatform/api/10428
     * @return array|mixed
     */
    public function positionQuery()
    {
        $this->method = 'jd.union.open.position.query';
        return $this;
    }

    /**
     * 获取PID【申请】
     * https://union.jd.com/openplatform/api/10430
     * @return array|mixed
     */
    public function userPidGet()
    {
        $this->method = 'jd.union.open.user.pid.get';
        return $this;
    }

    /**
     * 关键词商品查询接口【申请】
     * https://union.jd.com/openplatform/api/10421
     * @return array|mixed
     */
    public function goodsQuery()
    {
        $this->method = 'jd.union.open.goods.query';
        return $this;
    }

    /**
     * 京粉精选商品查询接口
     * https://union.jd.com/openplatform/api/10417
     * @return array|mixed
     */
    public function goodsJIngFenQuery()
    {
        if (!isset($this->param['pageIndex'])) $this->param['pageIndex'] = 1;
        if (!isset($this->param['pageSize'])) $this->param['pageSize'] = 20;
        $this->method = 'jd.union.open.goods.jingfen.query';
        return $this;
    }

    /**
     * 根据skuid查询商品信息接口
     * https://union.jd.com/openplatform/api/10422
     * @return array|mixed
     */
    public function goodsPromotionGoodsInfoQuery()
    {
        $this->method = 'jd.union.open.goods.promotiongoodsinfo.query';
        return $this;
    }

    /**
     * 礼金创建【申请】
     * https://union.jd.com/openplatform/api/12246
     * @return array|mixed
     */
    public function couponGiftGet()
    {
        $this->method = 'jd.union.open.coupon.gift.get';
        return $this;
    }

    /**
     * 礼金停止【申请】
     * https://union.jd.com/openplatform/api/12240
     * @return array|mixed
     */
    public function couponGiftStop()
    {
        $this->method = 'jd.union.open.coupon.gift.stop';
        return $this;
    }

    /**
     * 礼金效果数据
     * https://union.jd.com/openplatform/api/12248
     * @return array|mixed
     */
    public function statisticsGifTCouponQuery()
    {
        $this->method = 'jd.union.open.statistics.giftcoupon.query';
        return $this;
    }

    /**
     * 返回数组数据
     * @return array|mixed
     * @throws JdException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) throw new HttpException(404, '请开启curl模块！');
        if (empty($this->app_key)) $this->getConfig();
        if (empty($this->app_key)) throw new JdException('请检查app_key参数');
        if (empty($this->method)) throw new JdException('请检查method参数');
        $this->params['method'] = $this->method;
        $this->params['app_key'] = $this->app_key;
        $this->params['timestamp'] = date('Y-m-d H:i:s');
        $this->params['format'] = $this->format;
        $this->params['v'] = $this->v;
        $this->params['sign_method'] = $this->sign_method;
        $this->params['param_json'] = json_encode($this->param, JSON_UNESCAPED_UNICODE);
        $this->http();
        $response = Strings::replace('.', '_', $this->method) . "_response";
        if (isset($this->output["$response"]['result'])) {
            if (is_array($this->output["$response"]['result'])) return $this->output["$response"]['result'];
            if (is_object($this->output["$response"]['result'])) $this->output = json_encode($this->output["$response"]['result'], JSON_UNESCAPED_UNICODE);
            return json_decode($this->output["$response"]['result'], true);
        } else {
            if (is_array($this->output)) return $this->output;
            if (is_object($this->output)) $this->output = json_encode($this->output, JSON_UNESCAPED_UNICODE);
            return json_decode($this->output, true);
        }
    }

    /**
     * 签名
     * @return string
     * @throws JdException
     */
    private function createSign()
    {
        if (empty($this->secret_key)) $this->getConfig();
        if (empty($this->secret_key)) throw new JdException('请检查secret_key参数');

        $sign = $this->secret_key;
        ksort($this->params);
        foreach ($this->params as $key => $val) if ($key != '' && $val != '') $sign .= $key . $val;
        $sign .= $this->secret_key;
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
        foreach ($this->params as $key => $val) if ($key != '' && $val != '') $strParam .= $key . '=' . urlencode($val) . '&';
        return $strParam;
    }
}
