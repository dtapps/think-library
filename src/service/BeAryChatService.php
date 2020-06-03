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

namespace DtApp\ThinkLibrary\service;

use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\curl\HttpService;

/**
 * 倍洽
 * Class BeAryChatService
 * @package DtApp\ThinkLibrary\service
 */
class BeAryChatService extends Service
{
    /**
     * 发送文本消息
     * @param string $webhook
     * @param string $content 消息内容
     * @return bool 发送结果
     */
    public function text(string $webhook, string $content)
    {
        return $this->sendMsg($webhook, [
            'text' => $content
        ]);
    }

    /**
     * 组装发送消息
     * @param string $webhook
     * @param array $data 消息内容数组
     * @return bool 发送结果
     */
    private function sendMsg(string $webhook, array $data)
    {

        $result = HttpService::instance()
            ->url($webhook)
            ->data($data)
            ->toArray();
        if ($result['code'] !== 0) return true;
        return false;
    }
}
