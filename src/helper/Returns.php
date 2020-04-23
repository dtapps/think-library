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

use think\exception\HttpResponseException;

/**
 * 返回管理类
 * Class Returns
 * @package DtApp\ThinkLibrary\helper
 */
class Returns
{
    /**
     * 返回Json-成功
     * @param array $data 数据
     * @param string $msg 描述
     * @param int $code 状态
     */
    public function jsonSuccess(array $data = [], string $msg = 'success', int $code = 0)
    {
        date_default_timezone_set('Asia/Shanghai');
        header('Content-Type:application/json; charset=utf-8');
        throw new HttpResponseException(json(['code' => $code, 'msg' => $msg, 'time' => time(), 'data' => $data]));
    }

    /**
     * 返回Json-错误
     * @param string $msg 描述
     * @param int $code 状态码
     * @param array $data 数据
     */
    public function jsonError(string $msg = 'error', int $code = 1, array $data = [])
    {
        date_default_timezone_set('Asia/Shanghai');
        header('Content-Type:application/json; charset=utf-8');
        throw new HttpResponseException(json(['code' => $code, 'msg' => $msg, 'time' => time(), 'data' => $data]));
    }
}
