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
        $this->currentUrl = $this->request->request('s');
        $this->meuns = [
            [
                "title" => "后台管理",
                "icon" => "mdi mdi-home",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sub" => []
            ],
            [
                "title" => "UI元素",
                "icon" => "mdi mdi-palette",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sub" => [
                    [
                        "title" => "按钮",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "卡片",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "格栅",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "图标",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "表格",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "模态框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "提示 / 弹出框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "警告框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "分页",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "进度条",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "标签页",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "排版",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "步骤",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "其他",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ]
                ]
            ],
            [
                "title" => "表单",
                "icon" => "mdi mdi-format-align-justify",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sub" => [
                    [
                        "title" => "基本元素",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "单选框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "复选框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "开关",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ]
                ]
            ],
            [
                "title" => "示例页面",
                "icon" => "mdi mdi-format-align-justify",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sub" => [
                    [
                        "title" => "文档列表",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "图库列表",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "网址配置",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "设置权限",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "新增文档",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "表单向导",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "登录页面",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "错误页面",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ]
                ]
            ],
            [
                "title" => "JS 插件",
                "icon" => "mdi mdi-language-javascript",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sub" => [
                    [
                        "title" => "日期选择器",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "滑块",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "选色器",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "Chart.js",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "对话框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "标签插件",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "通知消息",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ]
                ]
            ],
            [
                "title" => "多级菜单",
                "icon" => "mdi mdi-menu",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sub" => [
                    [
                        "title" => "一级菜单",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "一级菜单",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => [
                            [
                                "title" => "三级菜单",
                                "icon" => "layui-icon layui-icon-set",
                                "url" => "/admin/config/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sub" => []
                            ],
                            [
                                "title" => "三级菜单",
                                "icon" => "layui-icon layui-icon-set",
                                "url" => "/admin/config/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sub" => []
                            ]
                        ]
                    ],
                    [
                        "title" => "一级菜单",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/config/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ]
                ]
            ]
        ];
        $this->app->bind('DtApp\ThinkLibrary\Controller', $this);
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
        $this->currentUrl = $this->request->request('s');
        $this->meuns = [
            [
                "title" => "后台管理",
                "icon" => "mdi mdi-home",
                "url" => "/admin/index.html",
                "params" => "",
                "target" => "_self",
                "sub" => []
            ],
            [
                "title" => "UI元素",
                "icon" => "mdi mdi-palette",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sub" => [
                    [
                        "title" => "按钮",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "卡片",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "格栅",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "图标",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "表格",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "模态框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "提示 / 弹出框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "警告框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "分页",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "进度条",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "标签页",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "排版",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "步骤",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "其他",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ]
                ]
            ],
            [
                "title" => "表单",
                "icon" => "mdi mdi-format-align-justify",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sub" => [
                    [
                        "title" => "基本元素",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "单选框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "复选框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "开关",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ]
                ]
            ],
            [
                "title" => "示例页面",
                "icon" => "mdi mdi-format-align-justify",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sub" => [
                    [
                        "title" => "文档列表",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "图库列表",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "网址配置",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "设置权限",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "新增文档",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "表单向导",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "登录页面",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "错误页面",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ]
                ]
            ],
            [
                "title" => "JS 插件",
                "icon" => "mdi mdi-language-javascript",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sub" => [
                    [
                        "title" => "日期选择器",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "滑块",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "选色器",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "Chart.js",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "对话框",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "标签插件",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "通知消息",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ]
                ]
            ],
            [
                "title" => "多级菜单",
                "icon" => "mdi mdi-menu",
                "url" => "#",
                "params" => "",
                "target" => "_self",
                "sub" => [
                    [
                        "title" => "一级菜单",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ],
                    [
                        "title" => "一级菜单",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "#",
                        "params" => "",
                        "target" => "_self",
                        "sub" => [
                            [
                                "title" => "三级菜单",
                                "icon" => "layui-icon layui-icon-set",
                                "url" => "/admin/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sub" => []
                            ],
                            [
                                "title" => "三级菜单",
                                "icon" => "layui-icon layui-icon-set",
                                "url" => "/admin/index.html",
                                "params" => "",
                                "target" => "_self",
                                "sub" => []
                            ]
                        ]
                    ],
                    [
                        "title" => "一级菜单",
                        "icon" => "layui-icon layui-icon-set",
                        "url" => "/admin/index.html",
                        "params" => "",
                        "target" => "_self",
                        "sub" => []
                    ]
                ]
            ]
        ];
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
