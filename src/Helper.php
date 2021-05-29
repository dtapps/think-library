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

declare (strict_types=1);

namespace DtApp\ThinkLibrary;

use think\App;
use think\Container;
use think\Db;

/**
 * 控制器挂件
 * Class Helper
 * @package DtApp\ThinkLibrary
 */
abstract class Helper
{
    /**
     * 应用容器
     * @var App
     */
    public $app;

    /**
     * 数据库实例
     * @var
     */
    public $query;

    /**
     * 控制器实例
     * @var Controller
     */
    public $class;

    /**
     * Helper constructor.
     * @param Controller $class
     * @param App $app
     */
    public function __construct(Controller $class, App $app)
    {
        $this->app = $app;
        $this->class = $class;
    }

    /**
     * 获取数据库对象
     * @param $dbQuery
     * @return Db
     */
    protected function buildQuery($dbQuery)
    {
        return is_string($dbQuery) ? $this->app->db->name($dbQuery) : $dbQuery;
    }

    /**
     * 实例对象反射
     * @param mixed ...$args
     * @return Helper
     */
    public static function instance(...$args): Helper
    {
        return Container::getInstance()->invokeClass(static::class, $args);
    }
}