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

namespace DtApp\ThinkLibrary\helper;

/**
 * 请求管理类
 * Class Requests
 * @package DtApp\ThinkLibrary\helper
 */
class Requests
{
    /**
     * 判断输入的参数
     * @param array $data
     * @param array $arr
     * @return array|bool
     */
    public function isEmpty(array $data, array $arr)
    {
        foreach ($arr as $k => $v) if (empty(isset($data["$v"]) ? $data["$v"] : '')) return false;
        return $data;
    }

    /**
     * 判断输入的参数为空就返回Json错误
     * @param array $data
     * @param array $arr
     * @return array
     */
    public function isEmptyRet(array $data, array $arr)
    {
        foreach ($arr as $k => $v) if (empty(isset($data["$v"]) ? $data["$v"] : '')) \DtApp\ThinkLibrary\facade\Returns::jsonError('请检查参数', 102);
        return $data;
    }

    /**
     * 判断是否为GET方式
     * @return bool
     */
    public function isGet()
    {
        return request()->isGet();
    }

    /**
     * 判断是否为POST方式
     * @return bool
     */
    public function isPost()
    {
        return request()->isPost();
    }

    /**
     * 判断是否为PUT方式
     * @return boolean
     */
    public function isPut()
    {
        return request()->isPut();
    }

    /**
     * 判断是否为DELETE方式
     * @return boolean
     */
    public function isDelete()
    {
        return request()->isDelete();
    }

    /**
     * 判断是否为Ajax方式
     * @return bool
     */
    public function isAjax()
    {
        return request()->isAjax();
    }

    /**
     * 获取域名地址
     * @return string
     */
    public function getWebsiteAddress()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        return $http_type . $_SERVER['HTTP_HOST'] . "/";
    }
}
