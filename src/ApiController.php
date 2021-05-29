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

namespace DtApp\ThinkLibrary;

use DtApp\ThinkLibrary\helper\ValidateHelper;
use stdClass;
use think\App;
use think\exception\HttpResponseException;
use think\Request;

/**
 * 标准Api控制器基类
 * Class ApiController
 * @package DtApp\ThinkLibrary
 */
abstract class ApiController extends stdClass
{
    /**
     * 应用容器
     * @var App
     */
    public $app;

    /**
     * 请求对象
     * @var Request
     */
    public $request;

    /**
     * 解密后数据
     * @var
     */
    private $aes_decrypt_data;

    /**
     * 加密相关的东西
     * @var string
     */
    private $aes_md5, $aes_md5_iv = '';

    /**
     * ApiController constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $app->request;
        $this->app->bind('DtApp\ThinkLibrary\ApiController', $this);
        if (in_array($this->request->action(), get_class_methods(__CLASS__))) {
            $this->error('Access without permission.');
        }
        $this->initialize();
    }

    /**
     * 控制器初始化
     */
    protected function initialize()
    {
    }

    /**
     * 返回失败的操作
     * @param mixed $msg 消息内容
     * @param mixed $data 返回数据
     * @param integer $code 返回代码
     */
    public function error($msg = 'error', $code = 1, $data = []): void
    {
        throw new HttpResponseException(json([
            'code' => $code,
            'msg' => $msg,
            'timestamp' => time(),
            'data' => $data,
        ]));
    }

    /**
     * 返回成功的操作
     * @param mixed $msg 消息内容
     * @param mixed $data 返回数据
     * @param integer $code 返回代码
     */
    public function success($data = [], $msg = 'success', $code = 0): void
    {
        throw new HttpResponseException(json([
            'code' => $code,
            'msg' => $msg,
            'timestamp' => time(),
            'data' => $data,
        ]));
    }

    /**
     * key
     * @param string $name 参数名
     * @return $this
     */
    public function setAesMd5($name = 'sniff_h5'): self
    {
        $value = config("dtapp.md5.{$name}");
        $this->aes_md5 = $value;
        return $this;
    }

    /**
     * iv
     * @return $this
     */
    private function setAesMd5Iv(): self
    {
        $value = config("dtapp.md5.bcw");
        $this->aes_md5_iv = $value;
        return $this;
    }

    /**
     * 返回成功的操作
     * @param mixed $data 返回数据
     * @param mixed $msg 消息内容
     * @param integer $code 返回代码
     */
    public function aesSuccess($data = [], $msg = 'success', $code = 0)
    {
        $timestamp = time();
        throw new HttpResponseException(json([
            'code' => $code,
            'msg' => $msg,
            'timestamp' => $timestamp,
            'data' => [
                'aes' => $this->encrypt($data, $timestamp)
            ],
        ]));
    }

    /**
     * URL重定向
     * @param string $url 跳转链接
     * @param integer $code 跳转代码
     */
    public function redirect($url, $code = 301): void
    {
        throw new HttpResponseException(redirect($url, $code));
    }

    /**
     * @param array $rules
     * @param string $type
     * @return mixed
     */
    protected function _vali(array $rules, $type = '')
    {
        return ValidateHelper::instance()
            ->init($rules, $type);
    }

    /**
     * 获取解密后的数据
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function getAesDecryptData(string $name = '', $default = null)
    {
        if (empty($name)) {
            return $this->aes_decrypt_data;
        }

        return $this->aes_decrypt_data[$name] ?? $default;
    }

    /**
     * 验证接口签名
     */
    public function _judgeSign()
    {
        // 加密的数据参数
        $aes = $this->request->post('aes', '');
        if (empty($aes)) {
            $this->error('数据未签名！', 104);
        }
        // 获取时间数据
        $timestamp = $this->request->get('timestamp', 0);
        // 判断是否有时间
        if (empty($timestamp)) {
            $this->error('数据异常！', 105);
        }
        // 解密
        $aes_decode = $this->decrypt($aes, $timestamp);
        if (empty($aes_decode)) {
            $this->error('解密失败', 106);
        }
        $data = json_decode($aes_decode, true);
        // 判断是不是小于服务器时间
        $before = strtotime('-2minute');
        $rear = strtotime('+2minute');
        if ($timestamp <= $rear && $timestamp >= $before) {
            $this->aes_decrypt_data = $data;
        } else {
            $this->error('已超时，请重新尝试！');
        }
    }

    /**
     * 加密
     * @param $data
     * @param int $timestamp
     * @return bool|string
     */
    private function encrypt($data, int $timestamp)
    {
        if (empty($this->aes_md5)) {
            $this->setAesMd5();
        }
        if (empty($this->aes_md5_iv)) {
            $this->setAesMd5Iv();
        }
        if (!empty(is_array($data))) {
            $data = json_encode($data);
        }
        return urlencode(base64_encode(openssl_encrypt($data, 'AES-128-CBC', $this->aes_md5, 1, $this->aes_md5_iv . $timestamp)));
    }

    /**
     * 解密
     * @param string $data
     * @param int $timestamp
     * @return bool|false|string
     */
    private function decrypt(string $data, int $timestamp)
    {
        if (empty($this->aes_md5)) {
            $this->setAesMd5();
        }
        if (empty($this->aes_md5_iv)) {
            $this->setAesMd5Iv();
        }
        return openssl_decrypt(base64_decode(urldecode($data)), "AES-128-CBC", $this->aes_md5, true, $this->aes_md5_iv . $timestamp);
    }
}
