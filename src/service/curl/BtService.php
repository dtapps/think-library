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

namespace DtApp\ThinkLibrary\service\curl;

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\Service;
use Exception;

class BtService extends Service
{
    private $url;
    private $key;
    private $data = [];
    private $panel;
    private $cookie;
    private $timeout = 60;

    /**
     * 配置宝塔密钥
     * @param string $str
     * @return $this
     */
    public function key(string $str)
    {
        $this->key = $str;
        return $this;
    }

    /**
     * 配置宝塔网址
     * @param string $str
     * @return $this
     */
    public function panel(string $str)
    {
        $this->panel = $str;
        return $this;
    }

    /**
     * 配置网址
     * @param string $str
     * @return $this
     */
    public function url(string $str)
    {
        $this->url = $str;
        return $this;
    }

    /**
     * 认证内容
     * @param string $str
     * @return $this
     */
    public function cookie(string $str)
    {
        $this->cookie = $str;
        return $this;
    }

    /**
     * 超时，默认60s
     * @param int $int
     * @return $this
     */
    public function timeout(int $int)
    {
        $this->timeout = $int;
        return $this;
    }

    /**
     * 超时，数据
     * @param array $array
     * @return $this
     */
    public function data(array $array)
    {
        $this->data = $array;
        return $this;
    }

    /**
     * 返回数组数据
     * @param bool $is
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function toArray(bool $is = true)
    {
        if (empty($this->cookie)) throw new CurlException('请检查cookie内容');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->panel . $this->url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array_merge($this->getKeyData(), $this->data));
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        if (empty($is)) return $output;
        try {
            if (is_array($output)) return $output;
            return json_decode($output, true);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 构造带有签名的关联数组
     * @return array
     */
    private function getKeyData()
    {
        $time = time();
        return array(
            'request_token' => md5($time . '' . md5($this->key)),
            'request_time' => $time
        );
    }
}
