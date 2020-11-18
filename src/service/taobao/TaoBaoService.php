<?php

namespace DtApp\ThinkLibrary\service\taobao;

use DtApp\ThinkLibrary\Service;
use TbkScInvitecodeGetRequest;
use TopClient;

/**
 * 淘宝服务
 * Class TaoBaoService
 * @package DtApp\ThinkLibrary\service\taobao
 */
class TaoBaoService extends Service
{
    /**
     * TOP分配给应用的
     * @var string
     */
    private $app_key, $app_secret = "";

    /**
     * 需要发送的的参数
     * @var
     */
    private $param;

    /**
     * 响应内容
     * @var
     */
    private $output;

    /**
     * 配置应用的AppKey
     * @param string $appKey
     * @return $this
     */
    public function appKey(string $appKey): self
    {
        $this->app_key = $appKey;
        return $this;
    }

    /**
     * 应用AppSecret
     * @param string $appSecret
     * @return $this
     */
    public function appSecret(string $appSecret): self
    {
        $this->app_secret = $appSecret;
        return $this;
    }

    /**
     * 请求参数
     * @param array $param
     * @return $this
     */
    public function param(array $param): self
    {
        $this->param = $param;
        return $this;
    }

    public function scInvitecodeGet($sessionKey): self
    {
        $c = new TopClient();
        $c->appkey = $this->app_key;
        $c->secretKey = $this->app_secret;
        $req = new TbkScInvitecodeGetRequest();
        if (isset($this->param['relation_id'])) {
            $req->setRelationId($this->param['relation_id']);
        }
        if (isset($this->param['relation_app'])) {
            $req->setRelationApp($this->param['relation_app']);
        }
        if (isset($this->param['code_type'])) {
            $req->setCodeType($this->param['code_type']);
        }
        $resp = $c->execute($req, $sessionKey);
        return $this;
    }

    /**
     * 返回Array
     * @return array|mixed
     */
    public function toArray()
    {
        if (isset($this->output['error_response'])) {
            // 错误
            if (is_array($this->output)) {
                return $this->output;
            }
            if (is_object($this->output)) {
                $this->output = json_encode($this->output, JSON_UNESCAPED_UNICODE);
            }
            return json_decode($this->output, true);
        }

        // 正常
        if (is_array($this->output)) {
            return $this->output;
        }
        if (is_object($this->output)) {
            $this->output = json_encode($this->output, JSON_UNESCAPED_UNICODE);
        }
        $this->output = json_decode($this->output, true);
        return $this->output;
    }
}