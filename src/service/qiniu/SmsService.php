<?php

namespace DtApp\ThinkLibrary\service\qiniu;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\Service;
use Qiniu\Auth;
use Qiniu\Http\Client;
use think\exception\HttpException;

/**
 * Class SmsService
 * @package DtApp\ThinkLibrary\service\qiniu
 */
class SmsService extends Service
{
    /**
     * @var
     */
    private $accessKey, $secretKey, $url, $method;

    /**
     * 需要发送的的参数
     * @var
     */
    private $param = [];

    /**
     * 响应内容
     * @var
     */
    private $output;

    /**
     * @var string
     */
    private $contentType = "application/json";

    /**
     * @param string $accessKey
     * @return $this
     */
    public function accessKey(string $accessKey): self
    {
        $this->accessKey = $accessKey;
        return $this;
    }

    /**
     * @param string $secretKey
     * @return $this
     */
    public function secretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;
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
     * 发送短信
     * https://developer.qiniu.com/sms/api/5897/sms-api-send-message#1
     * @return $this
     */
    public function message(): self
    {
        $this->url = "https://sms.qiniuapi.com/v1/message";
        $this->method = "POST";
        return $this;
    }

    /**
     * 发送单条短信
     * https://developer.qiniu.com/sms/api/5897/sms-api-send-message#2
     * @return $this
     */
    public function messageSingle(): self
    {
        $this->url = "https://sms.qiniuapi.com/v1/message/single";
        $this->method = "POST";
        return $this;
    }

    /**
     * 发送国际/港澳台短信
     * https://developer.qiniu.com/sms/api/5897/sms-api-send-message#3
     * @return $this
     */
    public function messageOversea(): self
    {
        $this->url = "https://sms.qiniuapi.com/v1/message/oversea";
        $this->method = "POST";
        return $this;
    }

    /**
     * 发送全文本短信(不需要传模版 ID)
     * https://developer.qiniu.com/sms/api/5897/sms-api-send-message#3
     * @return $this
     */
    public function messageFulltext(): self
    {
        $this->url = "https://sms.qiniuapi.com/v1/message/fulltext";
        $this->method = "POST";
        return $this;
    }

    /**
     * 查询短信发送记录
     * https://developer.qiniu.com/sms/api/5852/query-send-sms
     * @return $this
     */
    public function messages(): self
    {
        $this->url = "https://sms.qiniuapi.com/v1/messages";
        $this->method = "GET";
        return $this;
    }

    /**
     * 创建签名
     * https://developer.qiniu.com/sms/api/5844/sms-api-create-signature
     * @return $this
     */
    public function signature(): self
    {
        $this->url = "https://sms.qiniuapi.com/v1/signature";
        $this->method = "POST";
        return $this;
    }

    /**
     * 查询签名
     * https://developer.qiniu.com/sms/api/5844/sms-api-create-signature
     * @return $this
     */
    public function signatureQuery(): self
    {
        $this->url = "https://sms.qiniuapi.com/v1/signature";
        $this->method = "GET";
        $this->contentType = "application/x-www-form-urlencoded";
        return $this;
    }

    /**
     * 查询单个签名信息
     * https://developer.qiniu.com/sms/api/5970/query-a-single-signature
     * @param $id
     * @return $this
     * @throws DtaException
     */
    public function signatureQueryId($id): self
    {
        if (empty($id)) {
            throw new DtaException('请检查id参数');
        }
        $this->url = "https://sms.qiniuapi.com/v1/signature/{$id}";
        $this->method = "GET";
        return $this;
    }

    /**
     * 编辑签名
     * https://developer.qiniu.com/sms/api/5890/sms-api-edit-signature
     * @param $id
     * @return $this
     * @throws DtaException
     */
    public function signatureEditId($id): self
    {
        if (empty($id)) {
            throw new DtaException('请检查id参数');
        }
        $this->url = "https://sms.qiniuapi.com/v1/signature/{$id}";
        $this->method = "PUT";
        return $this;
    }

    /**
     * 删除签名
     * https://developer.qiniu.com/sms/api/5891/sms-api-delete-signature
     * @param $id
     * @return $this
     * @throws DtaException
     */
    public function signatureDelId($id): self
    {
        if (empty($id)) {
            throw new DtaException('请检查id参数');
        }
        $this->url = "https://sms.qiniuapi.com/v1/signature/{$id}";
        $this->method = "DELETE";
        return $this;
    }

    /**
     * 创建模板
     * https://developer.qiniu.com/sms/api/5893/sms-api-create-template
     * @return $this
     */
    public function template(): self
    {
        $this->url = "https://sms.qiniuapi.com/v1/template";
        $this->method = "POST";
        return $this;
    }

    /**
     * 查询模板
     * https://developer.qiniu.com/sms/api/5894/sms-api-query-template
     * @return $this
     */
    public function templateQuery(): self
    {
        $this->url = "https://sms.qiniuapi.com/v1/template";
        $this->method = "GET";
        return $this;
    }

    /**
     * 查询单个模版信息
     * https://developer.qiniu.com/sms/api/5969/query-a-single-template
     * @param $id
     * @return $this
     * @throws DtaException
     */
    public function templateQueryId($id): self
    {
        if (empty($id)) {
            throw new DtaException('请检查id参数');
        }
        $this->url = "https://sms.qiniuapi.com/v1/template/{$id}";
        $this->method = "GET";
        return $this;
    }

    /**
     * 编辑签名
     * https://developer.qiniu.com/sms/api/5895/sms-api-edit-template
     * @param $id
     * @return $this
     * @throws DtaException
     */
    public function templateEditId($id): self
    {
        if (empty($id)) {
            throw new DtaException('请检查id参数');
        }
        $this->url = "https://sms.qiniuapi.com/v1/template/{$id}";
        $this->method = "PUT";
        return $this;
    }

    /**
     * 删除签名
     * https://developer.qiniu.com/sms/api/5896/sms-api-delete-template
     * @param $id
     * @return $this
     * @throws DtaException
     */
    public function templateDelId($id): self
    {
        if (empty($id)) {
            throw new DtaException('请检查id参数');
        }
        $this->url = "https://sms.qiniuapi.com/v1/template/{$id}";
        $this->method = "DELETE";
        return $this;
    }

    /**
     * 返回数组数据
     * @return array|mixed
     * @throws DtaException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) {
            throw new HttpException(404, '请开启curl模块！');
        }
        $this->http();
        // 正常
        if (is_array($this->output)) {
            return $this->output;
        }
        if (is_object($this->output)) {
            $this->output = $this->object2array($this->output);
            return $this->output;
        }
        $this->output = json_decode($this->output, true);
        return $this->output;
    }

    /**
     * @param $object
     * @return array
     */
    private function object2array(&$object): array
    {
        if (is_object($object)) {
            $arr = (array)($object);
        } else {
            $arr = &$object;
        }
        if (is_array($arr)) {
            foreach ($arr as $varName => $varValue) {
                $arr[$varName] = $this->object2array($varValue);
            }
        }
        return $arr;
    }

    /**
     * 网络请求
     * @return $this
     * @throws DtaException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function http(): self
    {
        if (empty($this->accessKey)) {
            throw new DtaException('请检查accessKey参数');
        }
        if (empty($this->secretKey)) {
            throw new DtaException('请检查secretKey参数');
        }
        $headers = [
            'Authorization' => $this->authentication(),
            'Content-Type' => $this->contentType
        ];

        $auth = new Auth($this->accessKey, $this->secretKey);
        $recToken = $auth->authorizationV2($this->url, $this->method, json_encode($this->param), $this->contentType);
        $rtcToken['Content-Type'] = $this->contentType;
        dump($recToken);
        dump($this->url . "?" . http_build_query($this->param));
        $ret = Client::get($this->url . "?" . http_build_query($this->param), $recToken);
        dump($ret);
        $this->output = $ret->json();
        return $this;
    }

    /**
     * 服务鉴权
     * https://developer.qiniu.com/sms/api/5842/sms-api-authentication
     * @return string
     */
    private function authentication()
    {
        $url = parse_url($this->url);
        $data = $url['path'] ?? '';
        if (isset($url['query'])) {
            $data .= '?' . $url['query'];
        }
        $data .= "\n";
        if (isset($this->param) && $this->contentType === 'application/x-www-form-urlencoded') {
            $data .= json_encode($this->param);
        }
        return $this->sign($data);
    }

    /**
     * @param $data
     * @return string
     */
    private function sign($data)
    {
        $sign = hash_hmac('sha1', $data, $this->secretKey, true);
        return "Qiniu " . sprintf('%s:%s', $this->accessKey, $this->encode($sign));
    }

    /**
     * @param $string
     * @return string|string[]
     */
    private function encode($string)
    {
        $find = ['+', '/'];
        $replace = ['-', '_'];
        return str_replace($find, $replace, base64_encode($string));
    }
}