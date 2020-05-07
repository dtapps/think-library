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

namespace DtApp\ThinkLibrary\service\notice;

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\exception\NoticeException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\curl\HttpService;

/**
 * 通知 - 企业微信
 * Class QyWeiXinService
 * @package DtApp\ThinkLibrary\service\notice
 */
class QyWeiXinService extends Service
{
    /**
     * 消息类型
     * @var string
     */
    private $msgType = 'text';
    private $key;

    /**
     * 配置Key
     * @param string $str
     * @return $this
     */
    public function key(string $str)
    {
        $this->key = $str;
        return $this;
    }

    /**
     * 发送文本消息
     * @param string $content 消息内容
     * @return bool
     * @throws CurlException
     * @throws NoticeException
     */
    public function text(string $content = '')
    {
        $this->msgType = 'text';
        return $this->sendMsg([
            'text' => [
                'content' => $content,
            ],
        ]);
    }

    /**
     * 发送markdown消息
     * @param string $content 消息内容
     * @return bool
     * @throws CurlException
     * @throws NoticeException
     */
    public function markdown(string $content = '')
    {
        $this->msgType = 'markdown';
        return $this->sendMsg([
            'markdown' => [
                'content' => $content,
            ],
        ]);
    }

    /**
     * 组装发送消息
     * @param array $data 消息内容数组
     * @return bool
     * @throws NoticeException|CurlException
     */
    private function sendMsg(array $data)
    {
        if (empty($this->key)) throw new NoticeException("请检查KEY");
        if (empty($data['msgtype'])) $data['msgtype'] = $this->msgType;
        $result = HttpService::instance()
            ->url("https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=" . $this->key)
            ->data($data)
            ->toArray();
        if ($result['errcode'] == 0) return true;
        return false;
    }
}
