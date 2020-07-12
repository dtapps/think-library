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

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\curl\HttpService;
use think\exception\HttpException;

/**
 * 钉钉
 * Class DingTalkService
 * @package DtApp\ThinkLibrary\service
 */
class DingTalkService extends Service
{
    /**
     * 消息类型
     * @var string
     */
    private $msg_type = 'text';

    private $access_token;

    private $oapi_url = "https://oapi.dingtalk.com/";

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
     * @throws DtaException
     */
    public function text(string $content)
    {
        $this->msg_type = 'text';
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
     * @throws DtaException
     */
    private function sendMsg(array $data)
    {
        if (empty($this->access_token)) throw new DtaException("请检查access_token");
        if (empty($data['msgtype'])) $data['msgtype'] = $this->msg_type;
        $result = HttpService::instance()
            ->url("{$this->oapi_url}robot/send?access_token=" . $this->access_token)
            ->data($data)
            ->post()
            ->toArray();
        if ($result['errcode'] == 0) return $result['errmsg'];
        throw new HttpException(404,  json_encode($result, JSON_UNESCAPED_UNICODE));
    }
}
