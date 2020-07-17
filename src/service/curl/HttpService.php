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

namespace DtApp\ThinkLibrary\service\curl;

use DtApp\ThinkLibrary\Service;
use think\exception\HttpException;

/**
 * 通用网络请求
 * Class HttpService
 * @package DtApp\ThinkLibrary\service\curl
 */
class HttpService extends Service
{
    private $url, $data, $cert, $output;
    private $timeout = 60;
    private $method = 'GET';
    private $headers = 'application/json;charset=utf-8';

    /**
     * 配置网络请求接口
     * @param string $str
     * @return $this
     */
    public function url(string $str)
    {
        $this->url = $str;
        return $this;
    }

    /**
     * 需要请求的数据
     * @param $str
     * @return $this
     */
    public function data($str)
    {
        if (is_array($str)) {
            $this->data = json_encode($str, JSON_UNESCAPED_UNICODE);
        } else {
            $this->data = $str;
        }
        return $this;
    }

    /**
     * 请求头
     * @param $str
     * @return $this
     */
    public function headers(string $str)
    {
        $this->headers = $str;
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
     * 证书
     * @param string $sslCertPath
     * @param string $sslKeyPath
     * @return $this
     */
    public function cert(string $sslCertPath, string $sslKeyPath)
    {
        $this->cert = [
            'key' => $sslKeyPath,
            'cert' => $sslCertPath,
        ];
        return $this;
    }

    /**
     * GET请求方式
     * @return $this
     */
    public function get()
    {
        $this->method = 'GET';
        return $this;
    }

    /**
     * POST请求方式
     * @return $this
     */
    public function post()
    {
        $this->method = 'POST';
        return $this;
    }

    /**
     * XML请求方式
     * @return $this
     */
    public function xml()
    {
        $this->method = 'XML';
        return $this;
    }

    /**
     * XML请求方式
     * @return $this
     */
    public function file()
    {
        $this->method = 'FILE';
        return $this;
    }

    /**
     * 返回数组数据
     * @param bool $is
     * @return array|bool|mixed|string
     */
    public function toArray(bool $is = true)
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) {
            throw new HttpException(404, '请开启curl模块！');
        }
        if ($this->method === 'GET') {
            $this->httpGet();
        } else if ($this->method === 'POST') {
            $this->httpPost();
        } else if ($this->method === 'XML') {
            $this->httpXml();
        } else if ($this->method === 'FILE') {
            $this->httpFile();
        } else {
            throw new HttpException(404, '请求方式异常');
        }
        if (empty($is)) {
            return $this->output;
        }
        if (is_array($this->output)) {
            return $this->output;
        }
        return json_decode($this->output, true);
    }

    /**
     * 发送GET请求
     * @return bool|mixed|string
     */
    private function httpGet()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($this->data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        $this->output = $output;
        return $this;
    }

    /**
     * 发送Post请求
     * @return array|bool|mixed|string
     */
    private function httpPost()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: ' . $this->headers));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        $this->output = $content;
        return $this;
    }


    /**
     * 发送Xml数据
     * @return string
     */
    private function httpXml()
    {
        //初始一个curl会话
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        set_time_limit(0);
        if (!empty($this->headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: ' . $this->headers));
        }
        //运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        $this->output = $data;
        return $this;
    }

    /**
     * 上传图片
     * @return false|string
     */
    private function httpFile()
    {
        //初始一个curl会话
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        if (empty($this->cert)) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $this->cert['cert']);
            curl_setopt($ch, CURLOPT_SSLKEY, $this->cert['key']);
        } else {
            if (substr($this->url, 0, 5) == 'https') {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
            }
        }
        if (!empty($this->headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: ' . $this->headers));
        }
        curl_setopt($ch, CURLOPT_HEADER, true);    // 是否需要响应 header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);    // 获得响应结果里的：头大小
        $response_body = substr($output, $header_size);
        curl_close($ch);
        $this->output = $response_body;
        return $this;
    }
}
