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

namespace DtApp\ThinkLibrary\service\Curl;

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\exception\JdException;
use DtApp\ThinkLibrary\Service;

/**
 * 京东联盟 - 网络请求接口
 * Class JdUnionService
 * @package DtApp\ThinkLibrary\service\Curl
 */
class JdUnionService extends Service
{
    /**
     * 接口地址
     * @var string
     */
    private $url = "https://router.jd.com/api";

    /**
     * API接口名称
     * @var
     */
    private $method;

    /**
     * 联盟分配给应用的appkey
     * @var
     */
    private $app_key;

    /**
     * 联盟分配给应用的secretkey
     * @var
     */
    private $secret_key;

    /**
     * 根据API属性标签，如果需要授权，则此参数必传;如果不需要授权，则此参数不需要传
     * @var
     */
    private $access_token;

    /**
     * 响应格式，暂时只支持json
     * @var string
     */
    private $format = "json";

    /**
     * API协议版本，请根据API具体版本号传入此参数，一般为1.0
     * @var string
     */
    private $v = "1.0";

    /**
     * 签名的摘要算法，暂时只支持md5
     * @var string
     */
    private $sign_method = "md5";

    /**
     * 响应内容
     * @var
     */
    private $output;

    /**
     * 需要发送的的参数
     * @var
     */
    private $param;

    /**
     * API接口名称
     * @param string $method
     * @return $this
     */
    public function method(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * 联盟分配给应用的appkey
     * @param string $appKey
     * @return $this
     */
    public function appKey(string $appKey)
    {
        $this->app_key = $appKey;
        return $this;
    }

    /**
     * 联盟分配给应用的secretkey
     * @param string $secretKey
     * @return $this
     */
    public function secretKey(string $secretKey)
    {
        $this->secret_key = $secretKey;
        return $this;
    }

    /**
     * 根据API属性标签，如果需要授权，则此参数必传;如果不需要授权，则此参数不需要传
     * @param string $accessToken
     * @return $this
     */
    public function accessToken(string $accessToken)
    {
        $this->access_token = $accessToken;
        return $this;
    }

    /**
     * 组参
     * @param array $array
     * @return $this
     * @throws JdException
     */
    public function data(array $array)
    {
        $this->app_key = empty($this->app_key) ? config('dtapp.jd.union.app_key') : $this->app_key;
        if (empty($this->app_key)) throw new JdException('请检查app_key参数');
        if (empty($this->method)) throw new JdException('请检查method参数');

        $arr = [];
        $arr['method'] = $this->method;
        $arr['app_key'] = $this->app_key;
        $arr['timestamp'] = date('Y-m-d H:i:s');
        $arr['format'] = $this->format;
        $arr['v'] = $this->v;
        $arr['sign_method'] = $this->sign_method;
        $arr['param_json'] = json_encode($array);
        $this->param = $arr;
        return $this;
    }

    /**
     * 网络请求
     * @throws JdException
     */
    private function http()
    {
        //生成签名
        $sign = $this->createSign();
        //组织参数
        $strParam = $this->createStrParam();
        $strParam .= 'sign=' . $sign;
        //访问服务
        $result = file_get_contents("{$this->url}?{$strParam}");
        $result = json_decode($result, true);
        $this->output = $result;
    }

    /**
     * 返回数组数据
     * @return array|mixed
     * @throws CurlException|JdException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) throw new CurlException('请开启curl模块！', E_USER_DEPRECATED);
        $this->http();
        if (is_array($this->output)) return $this->output;
        if (is_object($this->output)) $this->output = json_encode($this->output);
        return json_decode($this->output, true);
    }

    /**
     * 签名
     * @return string
     * @throws JdException
     */
    private function createSign()
    {
        $this->secret_key = empty($this->secret_key) ? config('dtapp.jd.union.secret_key') : $this->secret_key;
        if (empty($this->secret_key)) throw new JdException('请检查secret_key参数');

        $sign = $this->secret_key;
        ksort($this->param);
        foreach ($this->param as $key => $val) if ($key != '' && $val != '') $sign .= $key . $val;
        $sign .= $this->secret_key;
        $sign = strtoupper(md5($sign));
        return $sign;
    }

    /**
     * 组参
     * @return string
     */
    private function createStrParam()
    {
        $strParam = '';
        foreach ($this->param as $key => $val) if ($key != '' && $val != '') $strParam .= $key . '=' . urlencode($val) . '&';
        return $strParam;
    }
}
