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

namespace DtApp\ThinkLibrary\service\kuaishou;

use DtApp\ThinkLibrary\facade\Pregs;
use DtApp\ThinkLibrary\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Psr7\Stream;

/**
 * 快手-视频去水印
 * Class WatermarkService
 * @package DtApp\ThinkLibrary\service\kuaishou
 */
class WatermarkService extends Service
{
    /**
     * @var
     */
    private $url, $contents, $backtrack;

    /**
     * 设置网址
     * @param $url
     * @return $this
     */
    public function setUrl($url): self
    {
        if (Pregs::isLink($url)) {
            $this->url = $url;
        } else {
            preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $url, $match);
            $this->url = $match[0][0];
        }
        return $this;
    }

    /**
     * 获取接口全部信息
     * @return WatermarkService
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getApi(): self
    {
        $this->contents = $this->getContents($this->url);
        return $this;
    }

    /**
     * 获取全部信息
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAll(): self
    {
        $this->getApi();
        $data = [
            'video_src' => $this->contents['video_src'],
            'cover_image' => $this->contents['cover_image'],
        ];
        $this->backtrack = $data;
        return $this;
    }

    /**
     * 返回Array
     * @return array|mixed
     */
    public function toArray()
    {
        if (empty($this->backtrack)) {
            return [];
        }
        if (is_array($this->backtrack)) {
            return $this->backtrack;
        }
        return json_decode($this->backtrack, true);
    }

    /**
     * 获取
     * @param $url
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getContents($url): array
    {
        $headers = [
            'Connection' => 'keep-alive',
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_1_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/16D57 Version/12.0 Safari/604.1'
        ];
        $client = new Client(['timeout' => 2, 'headers' => $headers, 'http_errors' => false,]);
        $data['headers'] = $headers;
        $data['verify'] = __DIR__ . DIRECTORY_SEPARATOR . 'cacert.pem';
        $jar = new CookieJar();
        $data['cookies'] = $jar;
        $response = $client->request('GET', $url, $data);
        $body = $response->getBody();
        if ($body instanceof Stream) {
            $body = $body->getContents();
        }
        $result = htmlspecialchars_decode($body);
        $pattern = '#"srcNoMark":"(.*?)"#';
        preg_match($pattern, $result, $match);
        $data['video_src'] = $match[1];
        $pattern = '#"poster":"(.*?)"#';
        preg_match($pattern, $result, $match);
        if (!empty($match[1])) {
            $data['cover_image'] = $match[1];
            return $data;
        }
        return [];
    }
}