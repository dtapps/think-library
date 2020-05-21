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

namespace DtApp\ThinkLibrary\exception;

use DtApp\ThinkLibrary\service\DingTalkService;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\Request;
use think\Response;
use Throwable;

/**
 * 异常处理接管
 * Class ThinkException
 * @package DtApp\ThinkLibrary\exception
 */
class ThinkException extends Handle
{
    /**
     * 异常处理接管
     * @param Request $request
     * @param Throwable $e
     * @return Response
     * @throws AliException
     * @throws CurlException
     */
    public function render($request, Throwable $e): Response
    {
        // 参数验证错误
        if ($e instanceof ValidateException) return json($e->getError(), 422);

        // 请求异常
        if ($e instanceof HttpException && $request->isAjax()) return response($e->getMessage(), $e->getStatusCode());

        $this->show($e->getMessage());

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }

    /**
     * @param $msg
     * @return bool
     * @throws AliException
     * @throws CurlException
     */
    private function show($msg)
    {
        if (empty($msg)) return true;
        $nt = config('dtapp.exception.type', '');
        if (!empty($nt) && $nt == 'dingtalk') {
            $access_token = config('dtapp.exception.dingtalk.access_token', '');
            if (!empty($access_token)) return DingTalkService::instance()
                ->accessToken($access_token)
                ->text($msg);
        }
        return false;
    }
}
