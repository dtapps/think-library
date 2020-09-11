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
     * 请求参数
     * @param array $param
     * @return $this
     */
    public function param(array $param): self
    {
        $this->param = $param;
        return $this;
    }

    /**
     * 话费充值
     * @return $this
     */
    public function chongZhi(): self
    {
        $this->method = 'chongzhi_jkorders';
        return $this;
    }

    /**
     * 通用查询
     * @return $this
     */
    public function query(): self
    {
        $this->method = 'query_jkorders';
        return $this;
    }

    /**
     * 用户余额查询
     * @return $this
     */
    public function money(): self
    {
        $this->method = 'money_jkuser';
        return $this;
    }

    /**
     * 腾讯充值
     * @return $this
     */
    public function txchongzhi(): self
    {
        $this->method = 'txchongzhi';
        return $this;
    }

    /**
     * 可充值腾讯产品查询
     * @return $this
     */
    public function queryTXproduct(): self
    {
        $this->method = 'queryTXproduct';
        return $this;
    }

    /**
     * 流量充值
     * @return $this
     */
    public function gprsChongzhiAdvance(): self
    {
        $this->method = 'queryTXproduct';
        return $this;
    }

    /**
     * 会员订单成本价查询
     * @return $this
     */
    public function checkCost(): self
    {
        $this->method = 'checkCost';
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
        };
        if (is_object($this->output)) {
            $this->output = json_encode($this->output, JSON_UNESCAPED_UNICODE);
        }
        $this->output = json_decode($this->output, true);
        return $this->output;
    }

    /**
     * 网络请求
     * @throws DtaException
     */
    private function http(): void
    {
        //生成签名
        $sign = $this->createSign();
        //组织参数
        $strParam = $this->createStrParam();
        $strParam .= '&userkey=' . $sign;
        $url = "http://" . $this->api . "/" . $this->method . ".do?{$strParam}";
        $result = file_get_contents($url);
        $result = Xmls::toArray($result);
        $this->output = $result;
    }

    /**
     * 签名
     * @return string
     * @throws DtaException
     */
    private function createSign(): string
    {
        if (empty($this->key)) {
            throw new DtaException('请检查key参数');
        }
        if (empty($this->userid)) {
            throw new DtaException('请检查userid参数');
        }
        if (empty($this->pwd)) {
            throw new DtaException('请检查pwd参数');
        }
        $this->param['userid'] = $this->userid;
        $this->param['pwd'] = $this->pwd;
        $sign = "userid{$this->userid}pwd{$this->pwd}";
        ksort($this->param);
        foreach ($this->param as $key => $val) {
            if ($key !== '' && $val !== '') {
                $sign .= $key . $val;
            }
        }
        $sign .= $this->key;
        $sign = strtoupper(md5($sign));
        return $sign;
    }

    /**
     * 组参
     * @return string
     */
    private function createStrParam(): string
    {
        $strParam = '';
        foreach ($this->param as $key => $val) {
            if ($key !== '' && $val !== '') {
                $strParam .= $key . '=' . urlencode($val) . '&';
            }
        }
        return substr($strParam, 0, -1);
    }
}