<?php

namespace DtApp\ThinkLibrary\service\decent;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\facade\Xmls;
use DtApp\ThinkLibrary\Service;
use think\exception\HttpException;

/**
 * 缴费平台
 * Class EJiAoFei
 * @package DtApp\ThinkLibrary\service\decent
 */
class EJiAoFei extends Service
{
    /**
     * 待请求的链接
     * @var string
     */
    private $api, $method = '';

    /**
     * 由鼎信商务提供
     * @var
     */
    private $userid, $pwd, $key = '';

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

    /**
     * ip:端口
     * @param string $api
     * @return $this
     */
    public function api(string $api): self
    {
        $this->api = $api;
        return $this;
    }

    /**
     * 由鼎信商务提供
     * @param string $userid
     * @return $this
     */
    public function userid(string $userid): self
    {
        $this->userid = $userid;
        return $this;
    }

    /**
     * 由鼎信商务提供
     * @param string $pwd
     * @return $this
     */
    public function pwd(string $pwd): self
    {
        $this->pwd = $pwd;
        return $this;
    }

    /**
     * 由鼎信商务提供
     * @param string $key
     * @return $this
     */
    public function key(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * 话费充值
     * @param string $orderid 用户提交的订单号    用户提交的订单号，最长32位（用户保证其唯一性）
     * @param int $face 充值面值    以元为单位，包含10、20、30、50、100、200、300、500 移动联通电信
     * @param string $account 手机号码    需要充值的手机号码
     * @param int $amount 购买数量    只能为1
     * @return $this
     */
    public function chongZhi(string $orderid, int $face, string $account, int $amount = 1): self
    {
        $this->method = 'chongzhi_jkorders';
        $this->param = "userid={$this->userid}&pwd={$this->pwd}&orderid={$orderid}&account={$account}&face={$face}&amount={$amount}";
        return $this;
    }

    /**
     * 通用查询
     * @param string $orderid 用户提交的订单号    用户提交的订单号，最长32位（用户保证其唯一性）
     * @return $this
     */
    public function query(string $orderid): self
    {
        $this->method = 'query_jkorders';
        $this->param = "userid={$this->userid}&pwd={$this->pwd}&orderid={$orderid}";
        return $this;
    }

    /**
     * 用户余额查询
     * @return $this
     */
    public function money(): self
    {
        $this->method = 'money_jkuser';
        $this->param = "userid={$this->userid}&pwd={$this->pwd}";
        return $this;
    }

    /**
     * 腾讯充值
     * @param string $orderid 用户提交的订单号    用户提交的订单号，最长32位（用户保证其唯一性）
     * @param string $account QQ号    需要充值的QQ号
     * @param int $productid 产品id    可以通过queryTXproduct查询
     * @param int $amount 购买数量
     * @param string $ip 充值QQ号ip    可以为空
     * @param string $times 时间戳    格式：yyyyMMddhhmmss
     * @return $this
     */
    public function txchongzhi(string $orderid, string $account, int $productid, int $amount, string $ip, string $times): self
    {
        $this->method = 'txchongzhi';
        $this->param = "userid={$this->userid}&pwd={$this->pwd}&orderid={$orderid}&account={$account}&productid={$productid}&amount={$amount}&ip={$ip}&times={$times}";
        return $this;
    }

    /**
     * 可充值腾讯产品查询
     * @return $this
     */
    public function queryTXproduct(): self
    {
        $this->method = 'queryTXproduct';
        $this->param = "userid={$this->userid}&pwd={$this->pwd}";
        return $this;
    }

    /**
     * 流量充值
     * @param string $orderid 用户提交的订单号    用户提交的订单号，最长32位（用户保证其唯一性）
     * @param string $account 充值手机号    需要充值的手机号
     * @param int $gprs 充值流量值    单位：MB（具体流量值请咨询商务）
     * @param int $area 充值流量范围    0 全国流量，1 省内流量
     * @param int $effecttime 生效日期    0 即时生效，1次日生效，2 次月生效
     * @param int $validity 流量有效期    传入月数，0为当月有效
     * @param string $times 时间戳    格式：yyyyMMddhhmmss
     * @return $this
     */
    public function gprsChongzhiAdvance(string $orderid, string $account, int $gprs, int $area, int $effecttime, int $validity, string $times): self
    {
        $this->method = 'queryTXproduct';
        $this->param = "userid={$this->userid}&pwd={$this->pwd}&orderid={$orderid}&account={$account}&gprs={$gprs}&area={$area}&effecttime={$effecttime}&validity={$validity}&times={$times}";
        return $this;
    }

    /**
     * 会员订单成本价查询
     * @param string $orderid 用户订单号    用户提交订单号
     * @return $this
     */
    public function checkCost(string $orderid): self
    {
        $this->method = 'checkCost';
        $this->param = "userid={$this->userid}&pwd={$this->pwd}&orderid={$orderid}";
        return $this;
    }

    /**
     * @throws DtaException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) {
            throw new HttpException(404, '请开启curl模块！');
        }
        if (empty($this->api)) {
            throw new DtaException('请检查api参数');
        }
        $this->http();
        // 正常
        if (is_array($this->output)) {
            return $this->output;
        }
        if (is_object($this->output)) {
            $this->output = json_encode($this->output, JSON_UNESCAPED_UNICODE);
        }
        $this->output = json_decode($this->output, true);
        return $this->output;
    }

    /**
     * 网络请求
     */
    private function http(): void
    {
        //生成签名
        $sign = $this->createSign();
        //组织参数
        $this->param .= '&userkey=' . $sign;
        $url = "http://" . $this->api . "/" . $this->method . ".do?{$this->param}";
        $result = file_get_contents($url);
        $result = Xmls::toArray($result);
        $this->output = $result;
    }

    /**
     * 签名
     * @return string
     */
    private function createSign(): string
    {
        $sign = str_replace(array("&", "="), array("", ""), $this->param);
        $sign .= $this->key;
        $sign = strtoupper(md5($sign));
        return $sign;
    }
}