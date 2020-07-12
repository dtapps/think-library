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
// | aliyun 仓库地址 ：https://code.aliyun.com/liguancghun/ThinkLibrary
// | coding 仓库地址 ：https://liguangchun-01.coding.net/p/ThinkLibrary/d/ThinkLibrary/git
// | coding 仓库地址 ：https://aizhineng.coding.net/p/ThinkLibrary/d/ThinkLibrary/git
// | tencent 仓库地址 ：https://liguangchundt.coding.net/p/ThinkLibrary/d/ThinkLibrary/git
// | weixin 仓库地址 ：https://git.weixin.qq.com/liguangchun/ThinkLibrary
// | huaweicloud 仓库地址 ：https://codehub-cn-south-1.devcloud.huaweicloud.com/composer00001/ThinkLibrary.git
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
