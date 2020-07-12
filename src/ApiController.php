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
        // 指定允许其他域名访问
        header('Access-Control-Allow-Origin:*');
        // 响应类型
        header('Access-Control-Allow-Methods:*');
        // 响应头设置
        header('Access-Control-Allow-Headers:*');
        //允许ajax异步请求带cookie信息
        header('Access-Control-Allow-Credentials:true');
    }

    /**
     * 返回失败的操作
     * @param mixed $info 消息内容
     * @param mixed $data 返回数据
     * @param integer $code 返回代码
     */
    public function error($info, $data = '{-null-}', $code = 0)
    {
        if ($data === '{-null-}') $data = new stdClass();
        throw new HttpResponseException(json([
            'code' => $code, 'info' => $info, 'timestamp' => time(), 'data' => $data,
        ]));
    }

    /**
     * 返回成功的操作
     * @param mixed $info 消息内容
     * @param mixed $data 返回数据
     * @param integer $code 返回代码
     */
    public function success($info, $data = '{-null-}', $code = 1)
    {
        if ($data === '{-null-}') $data = new stdClass();
        throw new HttpResponseException(json([
            'code' => $code, 'info' => $info, 'timestamp' => time(), 'data' => $data,
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
        foreach ([$name, "_{$this->app->request->action()}{$name}"] as $method) {
            if (method_exists($this, $method) && false === $this->$method($one, $two)) {
                return false;
            }
        }
        return true;
    }
}
