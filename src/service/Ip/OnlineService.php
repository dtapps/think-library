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

namespace DtApp\ThinkLibrary\service\Ip;

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\exception\IpException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\Curl\HttpService;

/**
 * IP  - 在线查询接口
 * Class OnlineService
 * @package DtApp\ThinkLibrary\service\ip
 */
class OnlineService extends Service
{
    /**
     * 需要查询的IP
     * @var
     */
    private $ip;

    /**
     * 查询指定IP
     * @param string $str
     * @return $this
     */
    public function ip(string $str)
    {
        $this->ip = $str;
        return $this;
    }

    /**
     * 哔哩哔哩ip查询接口
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function biliBili()
    {
        $url = "https://api.bilibili.com/x/web-interface/zone";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * batch
     * @param string $lang 语言
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function batch(string $lang = 'zh-CN')
    {
        $url = "http://ip-api.com/json/?lang={$lang}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * lookup
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function lookup()
    {
        $url = "https://extreme-ip-lookup.com/json/";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 网易IP查询接口
     * @return array|bool|mixed|string
     * @throws CurlException
     */
    public function netEase()
    {
        $url = "https://ipservice.3g.163.com/ip";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 百度搜索
     * @return bool|false|mixed|string|string[]
     * @throws CurlException
     */
    public function baidu()
    {
        if (empty($this->ip)) $this->ip = get_ip();
        $url = "https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query={$this->ip}&co=&resource_id=6006&ie=utf8&oe=utf8&cb=json";
        $res = HttpService::instance()
            ->url($url)
            ->toArray(false);
        $res = str_replace("/**/json", "", $res);
        $res = substr($res, 1);
        $res = substr($res, 0, -2);
        $res = json_decode($res, true);
        return $res;
    }

    /**
     * 太平洋
     * @return bool|false|mixed|string
     * @throws CurlException
     */
    public function pConLine()
    {
        $url = "http://whois.pconline.com.cn/ipJson.jsp?json=true";
        if (!empty($this->ip)) $url = "http://whois.pconline.com.cn/ipJson.jsp?json=true&ip={$this->ip}";
        $res = HttpService::instance()
            ->url($url)
            ->toArray(false);
        preg_match('/{.+}/', $res, $res);
        $res = iconv('gbk', 'utf-8', $res[0]);
        $res = json_decode($res, true);
        return $res;
    }

    /**
     * 新浪
     * @return bool|false|mixed|string|string[]
     * @throws CurlException
     */
    public function siNa()
    {
        if (empty($this->ip)) $this->ip = get_ip();
        $url = "http://ip.ws.126.net/ipquery?ip={$this->ip}";
        $res = HttpService::instance()
            ->url($url)
            ->toArray(false);
        $res = iconv('gbk', 'utf-8', $res);
        $res = substr($res, strpos($res, "{"));
        $res = substr($res, 0, -2);
        $res = str_replace("city", '"city"', $res);
        $res = str_replace("province", '"province"', $res);
        $res = json_decode($res, true);
        return $res;
    }

    /**
     * 好搜
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function so()
    {
        if (empty($this->ip)) $this->ip = get_ip();
        $url = "https://open.onebox.so.com/dataApi?type=ip&src=onebox&tpl=0&num=1&query=ip&ip={$this->ip}&url=ip";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 搜狐
     * @throws CurlException
     */
    public function soHu()
    {
        $url = "http://pv.sohu.com/cityjson?ie=utf-8";
        $res = HttpService::instance()
            ->url($url)
            ->toArray(false);
        $res = str_replace("var returnCitySN = ", "", $res);
        $res = substr($res, 0, -1);
        $res = json_decode($res, true);
        return $res;
    }

    /**
     * 淘宝
     * @param string $ip IP地址
     * @return bool|mixed|string
     * @throws CurlException
     */
    public function taoBao(string $ip = '')
    {
        if (empty($this->ip)) $this->ip = get_ip();
        $url = "http://ip.taobao.com/service/getIpInfo.php?ip={$this->ip}";
        return HttpService::instance()
            ->url($url)
            ->toArray();
    }

    /**
     * 阿里云
     * @param string $appcode
     * @return bool|mixed|string
     * @throws IpException
     */
    public function aliYun(string $appcode = '')
    {
        if (empty($this->ip)) $this->ip = get_ip();
        $host = "http://iploc.market.alicloudapi.com";
        $path = "/v3/ip";
        $method = "GET";
        if (empty($appcode)) throw new IpException('请检查阿里-阿里云配置信息 appcode');
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "ip={$this->ip}";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $content = curl_exec($curl);
        curl_close($curl);
        return json_decode($content, true);
    }
}
