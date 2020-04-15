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

use think\App;
use think\exception\HttpResponseException;
use think\Request;

/**
 * 标准控制器基类
 * Class Controller
 * @package DtApp\ThinkLibrary
 */
class Controller extends \stdClass
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
     * 当前URL
     * @var
     */
    protected $currentUrl;

    /**
     * 菜单
     * @var array
     */
    protected $meuns;

    /**
     * Controller constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $app->request;
        $this->currentUrl = $this->request->url();
        $this->meuns = [
            [
                "id" => 2,
                "pid" => 0,
                "title" => "系统管理",
                "icon" => "layui-icon layui-icon-set",
                "node" => "",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sort" => 1000,
                "status" => 1,
                "create_at" => "2018-09-06 02:04:52",
                "sub" => [
                    [
                        "id" => 4,
                        "pid" => 2,
                        "title" => "系统配置",
                        "icon" => "",
                        "node" => "",
                        "url" => "#",
                        "params" => "",
                        "target" => "_self",
                        "sort" => 20,
                        "status" => 1,
                        "create_at" => "2018-09-06 02:07:17",
                        "sub" => [
                            [
                                "id" => 11,
                                "pid" => 4,
                                "title" => "系统参数配置",
                                "icon" => "layui-icon layui-icon-set",
                                "node" => "",
                                "url" => "/admin/config/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sort" => 4,
                                "status" => 1,
                                "create_at" => "2018-09-07 00:43:47",
                            ],
                            [
                                "id" => 27,
                                "pid" => 4,
                                "title" => "系统任务管理",
                                "icon" => "layui-icon layui-icon-log",
                                "node" => "",
                                "url" => "/admin/queue/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sort" => 3,
                                "status" => 1,
                                "create_at" => "2018-11-29 19:13:34",
                            ],
                            [
                                "id" => 49,
                                "pid" => 4,
                                "title" => "系统日志管理",
                                "icon" => "layui-icon layui-icon-form",
                                "node" => "",
                                "url" => "/admin/oplog/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sort" => 2,
                                "status" => 1,
                                "create_at" => "2019-02-18 20:56:56",
                            ],
                            [
                                "id" => 3,
                                "pid" => 4,
                                "title" => "系统菜单管理",
                                "icon" => "layui-icon layui-icon-layouts",
                                "node" => "",
                                "url" => "/admin/menu/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sort" => 1,
                                "status" => 1,
                                "create_at" => "2018-09-06 02:05:26",
                            ]
                        ]
                    ],
                    [
                        "id" => 4,
                        "pid" => 2,
                        "title" => "系统配置",
                        "icon" => "",
                        "node" => "",
                        "url" => "#",
                        "params" => "",
                        "target" => "_self",
                        "sort" => 20,
                        "status" => 1,
                        "create_at" => "2018-09-06 02:07:17",
                        "sub" => [
                            [
                                "id" => 11,
                                "pid" => 4,
                                "title" => "系统参数配置",
                                "icon" => "layui-icon layui-icon-set",
                                "node" => "",
                                "url" => "/admin/config/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sort" => 4,
                                "status" => 1,
                                "create_at" => "2018-09-07 00:43:47",
                            ],
                            [
                                "id" => 27,
                                "pid" => 4,
                                "title" => "系统任务管理",
                                "icon" => "layui-icon layui-icon-log",
                                "node" => "",
                                "url" => "/admin/queue/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sort" => 3,
                                "status" => 1,
                                "create_at" => "2018-11-29 19:13:34",
                            ],
                            [
                                "id" => 49,
                                "pid" => 4,
                                "title" => "系统日志管理",
                                "icon" => "layui-icon layui-icon-form",
                                "node" => "",
                                "url" => "/admin/oplog/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sort" => 2,
                                "status" => 1,
                                "create_at" => "2019-02-18 20:56:56",
                            ],
                            [
                                "id" => 3,
                                "pid" => 4,
                                "title" => "系统菜单管理",
                                "icon" => "layui-icon layui-icon-layouts",
                                "node" => "",
                                "url" => "/admin/menu/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sort" => 1,
                                "status" => 1,
                                "create_at" => "2018-09-06 02:05:26",
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->app->bind('DtApp\ThinkLibrary\Controller', $this);
        if (in_array($this->request->action(), get_class_methods(__CLASS__))) {
            $this->error('Access without permission.');
        }
        $this->assign('currentUrl', $this->currentUrl);
        $this->assign('meuns', $this->meuns);
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
     * @param mixed $info 消息内容
     * @param mixed $data 返回数据
     * @param integer $code 返回代码
     */
    public function error($info, $data = '{-null-}', $code = 0)
    {
        if ($data === '{-null-}') $data = new \stdClass();
        throw new HttpResponseException(json([
            'code' => $code, 'info' => $info, 'data' => $data,
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
        if ($data === '{-null-}') $data = new \stdClass();
        throw new HttpResponseException(json([
            'code' => $code, 'info' => $info, 'data' => $data,
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
     * 返回视图内容
     * @param string $tpl 模板名称
     * @param array $vars 模板变量
     */
    public function fetch($tpl = '', $vars = [])
    {
        foreach ($this as $name => $value) $vars[$name] = $value;
        throw new HttpResponseException(view($tpl, $vars));
    }

    /**
     * 模板变量赋值
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return $this
     */
    public function assign($name, $value = '')
    {
        if (is_string($name)) {
            $this->$name = $value;
        } elseif (is_array($name)) {
            foreach ($name as $k => $v) {
                if (is_string($k)) $this->$k = $v;
            }
        }
        return $this;
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
