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

namespace DtApp\ThinkLibrary\service\taobao;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\facade\Strings;
use DtApp\ThinkLibrary\Service;
use think\exception\HttpException;

/**
 * 淘宝客
 * Class TbkService
 * @package DtApp\ThinkLibrary\service\TaoBao
 */
class TbkService extends Service
{
    /**
     * 是否为沙箱
     * @var bool
     */
    private $sandbox = false;

    /**
     * TOP分配给应用的AppKey
     * @var string
     */
    private $app_key = "";

    /**
     * TOP分配给应用的AppSecret
     * @var string
     */
    private $app_secret = "";

    /**
     * API接口名称
     * @var string
     */
    private $method = '';
    private $response = '';

    /**
     * 签名的摘要算法
     * @var string
     */
    private $sign_method = "md5";

    /**
     * 需要发送的的参数
     * @var
     */
    private $param;

    /**
     * 响应格式
     * @var string
     */
    private $format = "json";

    /**
     * API协议版本
     * @var string
     */
    private $v = "2.0";

    /**
     * 响应内容
     * @var
     */
    private $output;

    /**
     * 是否为沙箱
     * @return $this
     */
    public function sandbox()
    {
        $this->sandbox = true;
        return $this;
    }

    /**
     * 配置应用的AppKey
     * @param string $appKey
     * @return $this
     */
    public function appKey(string $appKey)
    {
        $this->app_key = $appKey;
        return $this;
    }

    /**
     * 应用AppSecret
     * @param string $appSecret
     * @return $this
     */
    public function appSecret(string $appSecret)
    {
        $this->app_secret = $appSecret;
        return $this;
    }

    /**
     * API接口名称
     * @param string $signMethod
     * @return $this
     */
    public function signMethod(string $signMethod)
    {
        $this->sign_method = $signMethod;
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
     * 获取配置信息
     * @return $this
     */
    private function getConfig()
    {
        $this->app_key = $this->app->config->get('dtapp.taobao.tbk.app_key');
        $this->app_secret = $this->app->config->get('dtapp.taobao.tbk.app_secret');
        return $this;
    }

    /**
     * 订单查询 - 淘宝客-推广者-所有订单查询
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=40173&docType=2
     * @return $this
     */
    public function orderDetailsGet()
    {
        $this->method = 'taobao.tbk.order.details.get';
        return $this;
    }

    /**
     * 订单查询 - 淘宝客-推广者-维权退款订单查询
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=40173&docType=2
     * @return array|mixed
     */
    public function relationRefund()
    {
        $this->method = 'taobao.tbk.relation.refund';
        return $this;
    }


    /**
     * 处罚订单 - 淘宝客-推广者-处罚订单查询
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=42050&docType=2
     * @return array|mixed
     */
    public function dgPunishOrderGet()
    {
        $this->method = 'taobao.tbk.dg.punish.order.get';
        return $this;
    }

    /**
     * 拉新订单&效果 - 淘宝客-推广者-新用户订单明细查询
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=33892&docType=2
     * @return array|mixed
     */
    public function DgNewUserOrderGet()
    {
        $this->method = 'taobao.tbk.dg.newuser.order.get';
        return $this;
    }

    /**
     * 拉新订单&效果 - 淘宝客-推广者-拉新活动对应数据查询
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=36836&docType=2
     * @return array|mixed
     */
    public function dgNewUserOrderSum()
    {
        $this->method = 'taobao.tbk.dg.newuser.order.sum';
        return $this;
    }

    /**
     * 超级红包发放个数 - 淘宝客-推广者-查询超级红包发放个数
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=47593&docType=2
     * @return array|mixed
     */
    public function dgVegasSendReport()
    {
        $this->method = 'taobao.tbk.dg.vegas.send.report';
        return $this;
    }

    /**
     * 活动转链(更新版) - 淘宝客-推广者-官方活动信息获取
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=48340&docType=2
     * @return array|mixed
     */
    public function activityInfoGet()
    {
        $this->method = 'taobao.tbk.activity.info.get';
        return $this;
    }

    /**
     * 活动转链 - 淘宝客-推广者-官方活动转链
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=41918&docType=2
     * @return array|mixed
     */
    public function activityLinkGet()
    {
        $this->method = 'taobao.tbk.activitylink.get';
        return $this;
    }

    /**
     * 淘口令 - 淘宝客-公用-淘口令生成
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=31127&docType=2
     * @return array|mixed
     * @throws DtaException
     */
    public function tpWdCreate()
    {
        if (isset($this->param['text'])) if (strlen($this->param['text']) < 5) throw new DtaException('请检查text参数长度');
        $this->method = 'taobao.tbk.tpwd.create';
        return $this;
    }

    /**
     * 长短链 - 淘宝客-公用-长链转短链
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=27832&docType=2
     * @return array|mixed
     */
    public function spreadGet()
    {
        $this->method = 'taobao.tbk.spread.get';
        return $this;
    }

    /**
     * 聚划算商品搜索接口
     * https://open.taobao.com/api.htm?docId=28762&docType=2&scopeId=16517
     * @return array|mixed
     */
    public function itemsSearch()
    {
        $this->method = 'taobao.ju.items.search';
        return $this;
    }

    /**
     * 淘抢购api
     * https://open.taobao.com/api.htm?docId=27543&docType=2&scopeId=16517
     * @return array|mixed
     */
    public function juTqgGet()
    {
        if (!isset($this->param['fields'])) $this->param['fields'] = "click_url,pic_url,reserve_price,zk_final_price,total_amount,sold_num,title,category_name,start_time,end_time";
        $this->method = 'taobao.tbk.ju.tqg.get';
        return $this;
    }

    /**
     * 淘礼金 - 淘宝客-推广者-淘礼金创建
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=40173&docType=2
     * @return array|mixed
     */
    public function dgVegasTljCreate()
    {
        $this->method = 'taobao.tbk.dg.vegas.tlj.create';
        return $this;
    }

    /**
     * 淘礼金 - 淘宝客-推广者-淘礼金发放及使用报表
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=43317&docType=2
     * @return array|mixed
     */
    public function dgVegasTljInstanceReport()
    {
        $this->method = 'taobao.tbk.dg.vegas.tlj.instance.report';
        return $this;
    }

    /**
     * 私域用户管理 - 淘宝客-公用-私域用户邀请码生成
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=38046&docType=2
     * @return array|mixed
     */
    public function scInvIteCodeGet()
    {
        $this->method = 'taobao.tbk.sc.invitecode.get';
        return $this;
    }

    /**
     * 私域用户管理 - 淘宝客-公用-私域用户备案信息查询
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=37989&docType=2
     * @return array|mixed
     */
    public function scPublisherInfoGet()
    {
        $this->method = 'taobao.tbk.sc.publisher.info.get';
        return $this;
    }

    /**
     * 私域用户管理 - 淘宝客-公用-私域用户备案
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=37988&docType=2
     * @return array|mixed
     */
    public function scPublisherInfoSave()
    {
        $this->method = 'taobao.tbk.sc.publisher.info.save';
        return $this;
    }

    /**
     * 商品详情&券详情查询 - 淘宝客-公用-淘宝客商品详情查询(简版)
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.178c24advNRYpp&docId=24518&docType=2
     * https://open.alimama.com/#!/function?id=25
     * @return array|mixed
     */
    public function itemInfoGet()
    {
        $this->method = 'taobao.tbk.item.info.get';
        return $this;
    }

    /**
     * 商品详情&券详情查询 - 淘宝客-公用-阿里妈妈推广券详情查询
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.178c24advNRYpp&docId=31106&docType=2
     * https://open.alimama.com/#!/function?id=25
     * @return array|mixed
     */
    public function couponGet()
    {
        $this->method = 'taobao.tbk.coupon.get';
        return $this;
    }

    /**
     * 商品/店铺搜索 - 淘宝客-推广者-物料搜索
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.178c24advNRYpp&docId=35896&docType=2
     * https://open.alimama.com/#!/function?id=27
     * @return array|mixed
     */
    public function dgMaterialOptional()
    {
        $this->method = 'taobao.tbk.dg.material.optional';
        return $this;
    }

    /**
     * 商品/店铺搜索 - 淘宝客-推广者-店铺搜索
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.178c24advNRYpp&docId=24521&docType=2
     * https://open.alimama.com/#!/function?id=27
     * @return array|mixed
     */
    public function shopGet()
    {
        if (!isset($this->param['fields'])) $this->param['fields'] = "user_id,shop_title,shop_type,seller_nick,pict_url,shop_url";
        $this->method = 'taobao.tbk.shop.get';
        return $this;
    }

    /**
     * 商品库/榜单精选 - 淘宝客-推广者-物料精选
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.178c24advNRYpp&docId=33947&docType=2
     * http://wsd.591hufu.com/taokelianmeng/424.html
     * https://open.alimama.com/#!/function?id=28
     * @return array|mixed
     */
    public function dgOpTiUsMaterial()
    {
        $this->method = 'taobao.tbk.dg.optimus.material';
        return $this;
    }

    /**
     * 图文内容 - 淘宝客-推广者-图文内容输出
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.178c24advNRYpp&docId=31137&docType=2
     * https://open.alimama.com/#!/function?id=30
     * @return array|mixed
     */
    public function contentGet()
    {
        $this->method = 'taobao.tbk.content.get';
        return $this;
    }

    /**
     * 图文内容 - 淘宝客-推广者-图文内容效果数据
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.178c24advNRYpp&docId=37130&docType=2
     * https://open.alimama.com/#!/function?id=30
     * @return array|mixed
     */
    public function contentEffectGet()
    {
        $this->method = 'taobao.tbk.content.effect.get';
        return $this;
    }


    /**
     * 图文内容 - 淘宝客-推广者-商品出词
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.178c24advNRYpp&docId=37538&docType=2
     * @return array|mixed
     */
    public function itemWordGet()
    {
        $this->method = 'taobao.tbk.item.word.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-商品链接转换
     * https://open.taobao.com/api.htm?docId=24516&docType=2&scopeId=11653
     * @return array|mixed
     */
    public function itemConvert()
    {
        if (!isset($this->param['fields'])) $this->param['fields'] = "num_iid,click_url";
        $this->method = 'taobao.tbk.item.convert';
        return $this;
    }

    /**
     * 淘宝客-公用-链接解析出商品id
     * https://open.taobao.com/api.htm?docId=28156&docType=2
     * @return array|mixed
     */
    public function itemClickExtract()
    {
        $this->method = 'taobao.tbk.item.click.extract';
        return $this;
    }

    /**
     * 返回Array
     * @return array|mixed
     * @throws DtaException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) throw new HttpException(404, '请开启curl模块！');
        $this->format = "json";
        if (empty($this->app_key)) $this->getConfig();
        if (empty($this->app_key)) throw new DtaException('请检查app_key参数');
        if (empty($this->method)) throw new DtaException('请检查method参数');
        $this->param['app_key'] = $this->app_key;
        $this->param['method'] = $this->method;
        $this->param['format'] = $this->format;
        $this->param['v'] = $this->v;
        $this->param['sign_method'] = $this->sign_method;
        $this->param['timestamp'] = date('Y-m-d H:i:s');
        $this->http();
        if (isset($this->output['error_response'])) {
            // 错误
            if (is_array($this->output)) return $this->output;
            if (is_object($this->output)) $this->output = json_encode($this->output, JSON_UNESCAPED_UNICODE);
            return json_decode($this->output, true);
        } else {
            // 正常
            $response = substr(Strings::replace('.', '_', $this->method), 7) . "_response";
            if (is_array($this->output)) {
                if (isset($this->output["$response"])) return $this->output["$response"];
                return $this->output;
            };
            if (is_object($this->output)) $this->output = json_encode($this->output, JSON_UNESCAPED_UNICODE);
            $this->output = json_decode($this->output, true);
            if (isset($this->output["$response"])) return $this->output["$response"];
            else return $this->output;
        }
    }

    /**
     * 返回Xml
     * @return mixed
     * @throws DtaException
     */
    public function toXml()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) throw new HttpException('请开启curl模块！', E_USER_DEPRECATED);
        $this->format = "xml";
        $this->http();
        return $this->output;
    }

    /**
     * 网络请求
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
        if (empty($this->sandbox)) $url = 'http://gw.api.taobao.com/router/rest?' . $strParam;
        else $url = 'http://gw.api.tbsandbox.com/router/rest?' . $strParam;
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        $this->output = $result;
    }

    /**
     * 签名
     * @return string
     * @throws DtaException
     */
    private function createSign()
    {
        if (empty($this->app_secret)) $this->getConfig();
        if (empty($this->app_secret)) throw new DtaException('请检查app_secret参数');

        $sign = $this->app_secret;
        ksort($this->param);
        foreach ($this->param as $key => $val) if ($key != '' && $val != '') $sign .= $key . $val;
        $sign .= $this->app_secret;
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
        foreach ($this->param as $key => $val) if ($key != '' && $val != '') $strParam .= $key . '=' . urlencode($val) . '&';
        return $strParam;
    }
}
