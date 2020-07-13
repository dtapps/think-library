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
     * ApiController constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $app->request;
        $this->app->bind('DtApp\ThinkLibrary\ApiController', $this);
        if (in_array($this->request->action(), get_class_methods(__CLASS__))) $this->error('Access without permission.');
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
        if (is_callable($name)) return call_user_func($name, $this, $one, $two);
        foreach ([$name, "_{$this->app->request->action()}{$name}"] as $method) if (method_exists($this, $method) && false === $this->$method($one, $two)) return false;
        return true;
    }


    /**
     * 验证接口签名
     * @param $name
     * @return string
     */
    public function _judgeSign($name)
    {
        if (empty(request()->header('sign', ''))) $this->error('数据未签名！', 666);
        // 全部参数
        $arr = request()->post();
        $timestamp = request()->get('timestamp', 0);
        // 判断是否有时间
        if (empty($timestamp)) $this->error('数据异常！', 666);
        $arr['timestamp'] = $timestamp;
        // 删除sign
        foreach ($arr as $k => $v) if ('sign' == $k) unset($arr[$k]);
        // 排序
        $arr = $this->argSort($arr, $name);
        // 服务器签名对比
        $sign = $this->md5Sign($arr);
        if ($sign != request()->header('sign', '')) $this->error('验证不匹配！', 666);
        // 判断是不是小于服务器时间
        $before = strtotime('-2minute');
        $rear = strtotime('+2minute');
        if ($timestamp <= $rear && $timestamp >= $before) return true;
        else  $this->error('已超时，请重新尝试！');
    }

    /**
     * 对数组排序
     * @param $param
     * @param string $name
     * @return mixed 排序后的数组
     */
    private function argSort($param, $name)
    {
        ksort($param);
        return $this->createLinkString($param, $name);
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para
     * @param string $name
     * @return bool|string 拼接完成以后的字符串
     */
    private function createLinkString(array $para, string $name)
    {
        $string = $this->toParams($para);// 将数组转换成字符串
        $string = $string . '&key=' . config("dtapp.md5.{$name}");
        return $string;
    }

    /**
     * 生成md5签名字符串
     * @param $preStr string 需要签名的字符串
     * @return string 签名结果
     */
    private function md5Sign(string $preStr)
    {
        return strtoupper(md5($preStr));
    }

    /**
     * 格式化参数格式化成url参数
     * @param array $data
     * @return string
     */
    private function toParams(array $data)
    {
        $buff = "";
        foreach ($data as $k => $v) if ($k != "sign" && $v !== "" && !is_array($v)) $buff .= $k . "=" . $v . "&";
        $buff = trim($buff, "&");
        return $buff;
    }
}
