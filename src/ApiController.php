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

namespace DtApp\ThinkLibrary;

use stdClass;
use think\App;
use think\exception\HttpResponseException;
use think\Request;

/**
 * 标准Api控制器基类
 * Class ApiController
 * @package DtApp\ThinkLibrary
 */
class ApiController extends stdClass
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
    public function error($msg = 'error', $code = 1, $data = [])
    {
        if ($data === []) $data = new stdClass();
        throw new HttpResponseException(json([
            'code' => $code, 'msg' => $msg, 'timestamp' => time(), 'data' => $data,
        ]));
    }

    /**
     * 返回成功的操作
     * @param mixed $msg 消息内容
     * @param mixed $data 返回数据
     * @param integer $code 返回代码
     */
    public function success($data = [], $msg = 'success', $code = 0)
    {
        if ($data === []) $data = new stdClass();
        throw new HttpResponseException(json([
            'code' => $code, 'msg' => $msg, 'timestamp' => time(), 'data' => $data,
        ]));
    }

    /**
     * 返回成功的操作
     * @param mixed $data 返回数据
     * @param mixed $msg 消息内容
     * @param integer $code 返回代码
     * @param string $name 参数名
     */
    public function aesSuccess($data = [], $msg = 'success', $code = 0, $name = 'sniff_h5')
    {
        if ($data === []) $data = new stdClass();
        $timestamp = time();
        throw new HttpResponseException(json([
            'code' => $code, 'msg' => $msg, 'timestamp' => $timestamp, 'data' => [
                'aes' => $this->encrypt($data, $name, $timestamp)
            ],
        ]));
    }

    /**
     * URL重定向
     * @param string $url 跳转链接
     * @param integer $code 跳转代码
     */
    public function redirect($url, $code = 301)
    {
        throw new HttpResponseException(redirect($url, $code));
    }

    /**
     * 数据回调处理机制
     * @param string $name 回调方法名称
     * @param mixed $one 回调引用参数1
     * @param mixed $two 回调引用参数2
     * @return boolean
     */
    public function callback($name, &$one = [], &$two = [])
    {
        if (is_callable($name)) {
            return call_user_func($name, $this, $one, $two);
        }
        foreach ([$name, "_{$this->app->request->action()}{$name}"] as $method) {
            if (method_exists($this, $method) && false === $this->$method($one, $two)) {
                return false;
            }
        }
        return true;
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
        if (isset($this->aes_decrypt_data[$name])) {
            return $this->aes_decrypt_data[$name];
        } else {
            return $default;
        }
    }

    /**
     * 验证接口签名
     * @param string $name
     */
    public function _judgeSign($name = 'sniff_h5')
    {
        if (empty($this->request->header('sign', ''))) {
            $this->error('数据未签名！', 104);
        }

        // 加密的数据参数
        $aes = $this->request->post('aes');
        // 获取时间数据
        $timestamp = $this->request->get('timestamp', 0);

        // 判断是否有时间
        if (empty($timestamp)) {
            $this->error('数据异常！', 105);
        }

        // 解密
        $aes_decode = $this->decrypt($aes, $name, $timestamp);
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
     * @param string $name
     * @param int $timestamp
     * @return bool|string
     */
    private function encrypt($data, string $name, int $timestamp)
    {
        if (!empty(is_array($data))) $data = json_encode($data);
        return urlencode(base64_encode(openssl_encrypt($data, 'AES-128-CBC', config("dtapp.md5.{$name}"), 1, config("dtapp.md5.bcw") . $timestamp)));
    }

    /**
     * 解密
     * @param string $data
     * @param string $name
     * @param int $timestamp
     * @return bool|false|string
     */
    private function decrypt(string $data, string $name, int $timestamp)
    {
        return openssl_decrypt(base64_decode(urldecode($data)), "AES-128-CBC", config("dtapp.md5.{$name}"), true, config("dtapp.md5.bcw") . $timestamp);
    }
}
