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

namespace DtApp\ThinkLibrary\service\Notice;

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\exception\NoticeException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\Curl\HttpService;

/**
 * 通知  - 钉钉
 * Class DingDingService
 * @package DtApp\ThinkLibrary\service\notice
 */
class DingDingService extends Service
{
    /**
     * 消息类型
     * @var string
     */
    private $msgType = 'text';

    private $access_token;

    /**
     * 配置access_token
     * @param string $str
     * @return $this
     */
    public function accessToken(string $str)
    {
        $this->access_token = $str;
        return $this;
    }

    /**
     * 发送文本消息
     * @param string $content 消息内容
     * @return bool    发送结果
     * @throws NoticeException|CurlException
     */
    public function text(string $content)
    {
        $this->msgType = 'text';
        return $this->sendMsg([
            'text' => [
                'content' => $content,
            ],
        ]);
    }

    /**
     * 组装发送消息
     * @param array $data 消息内容数组
     * @return bool 发送结果
     * @throws NoticeException|CurlException
     */
    private function sendMsg(array $data)
    {
        if (empty($this->access_token)) throw new NoticeException("请检查access_token");
        if (empty($data['msgtype'])) $data['msgtype'] = $this->msgType;
        $result = HttpService::instance()
            ->url("https://oapi.dingtalk.com/robot/send?access_token=" . $this->access_token)
            ->data($data)
            ->toArray();
        if ($result['errcode'] == 0) return true;
        return false;
    }
}
