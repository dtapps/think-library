<?php

namespace DtApp\ThinkLibrary\service\taobao;

require_once __DIR__ . '/bin/TopSdk.php';

use DtApp\ThinkLibrary\Service;
use TbkScInvitecodeGetRequest;
use TbkScPublisherInfoGetRequest;
use TbkScPublisherInfoSaveRequest;
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

    /**
     * ( 淘宝客-公用-私域用户备案 )
     * 通过入参渠道管理或会员运营管理的邀请码，生成渠道id或会员运营id，完成渠道或会员的备案。
     * https://open.taobao.com/api.htm?docId=37988&docType=2&scopeId=14474
     * @param $sessionKey
     * @return $this
     */
    public function tbkScPublisherInfoSave($sessionKey): self
    {
        $c = new TopClient();
        $c->appkey = $this->app_key;
        $c->secretKey = $this->app_secret;
        $req = new TbkScPublisherInfoSaveRequest();
        if (isset($this->param['relation_from'])) {
            $req->setRelationFrom($this->param['relation_from']);
        }
        if (isset($this->param['offline_scene'])) {
            $req->setOfflineScene($this->param['offline_scene']);
        }
        if (isset($this->param['online_scene'])) {
            $req->setOnlineScene($this->param['online_scene']);
        }
        if (isset($this->param['inviter_code'])) {
            $req->setInviterCode($this->param['inviter_code']);
        }
        if (isset($this->param['info_type'])) {
            $req->setInfoType($this->param['info_type']);
        }
        if (isset($this->param['note'])) {
            $req->setNote($this->param['note']);
        }
        if (isset($this->param['register_info'])) {
            $req->setRegisterInfo($this->param['register_info']);
        }
        $this->output = $c->execute($req, $sessionKey);
        return $this;
    }

    /**
     * ( 淘宝客-公用-私域用户备案信息查询 )
     * 查询已生成的渠道id或会员运营id的相关信息。
     * https://open.taobao.com/api.htm?docId=37989&docType=2&scopeId=14474
     * @param $sessionKey
     * @return $this
     */
    public function tbkScPublisherInfoGet($sessionKey): self
    {
        $c = new TopClient();
        $c->appkey = $this->app_key;
        $c->secretKey = $this->app_secret;
        $req = new TbkScPublisherInfoGetRequest();
        if (isset($this->param['info_type'])) {
            $req->setInfoType($this->param['info_type']);
        }
        if (isset($this->param['relation_id'])) {
            $req->setRelationId($this->param['relation_id']);
        }
        if (isset($this->param['page_no'])) {
            $req->setPageNo($this->param['page_no']);
        }
        if (isset($this->param['page_size'])) {
            $req->setPageSize($this->param['page_size']);
        }
        if (isset($this->param['relation_app'])) {
            $req->setRelationApp($this->param['relation_app']);
        }
        if (isset($this->param['special_id'])) {
            $req->setSpecialId($this->param['special_id']);
        }
        if (isset($this->param['external_id'])) {
            $req->setExternalId($this->param['external_id']);
        }
        $this->output = $c->execute($req, $sessionKey);
        return $this;
    }

    /**
     * ( 淘宝客-公用-私域用户邀请码生成 )
     * 私域用户管理(即渠道管理或会员运营管理)功能中，通过此API可生成淘宝客自身的邀请码。
     * https://open.taobao.com/api.htm?docId=38046&docType=2&scopeId=14474
     * @param $sessionKey
     * @return $this
     */
    public function tbkScInvitecodeGet($sessionKey): self
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
        $this->output = $c->execute($req, $sessionKey);
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