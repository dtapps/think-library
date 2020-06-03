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

use DtApp\ThinkLibrary\service\curl\HttpService;
use DtApp\ThinkLibrary\service\DingTalkService;
use DtApp\ThinkLibrary\service\Ip\QqWryService;
use DtApp\ThinkLibrary\service\wechat\QyService;
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
     * @throws IpException|NoticeException
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
     * @throws IpException
     * @throws NoticeException
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
        if (!empty($nt) && $nt == 'qyweixin') {
            $key = config('dtapp.exception.qyweixin.key', '');
            if (!empty($key)) return QyService::instance()
                ->key($key)
                ->text($msg);
        }
        if (!empty($nt) && $nt === 'wechat') {
            $openid = config('dtapp.exception.wechat.openid', '');
            $ip = config('dtapp.exception.wechat.ip', '未配置');
            $seip = get_ip();
            $ipinfo = QqWryService::instance()->getLocation($seip);
            if (!isset($ipinfo['location_all'])) $ipinfo['location_all'] = '';
            if (!empty($openid)) return HttpService::instance()
                ->url("https://api.dtapp.net/v1/wechatmp/tmplmsgWebError/openid/{$openid}")
                ->post()
                ->data([
                    'domain' => request()->domain(),
                    'url' => request()->url(),
                    'node' => config('dtapp.exception.wechat.node', ''),
                    'info' => "ServerIp：" . $ip . "；CdnIp：" . $_SERVER['REMOTE_ADDR'] . "；ClientIp：" . get_ip(),
                    'ip' => $ipinfo['location_all'],
                    'error' => base64_encode($msg)
                ])
                ->toArray();
        }
        return true;
    }
}
