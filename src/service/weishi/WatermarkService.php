<?php

namespace DtApp\ThinkLibrary\service\weishi;

use DtApp\ThinkLibrary\facade\Pregs;
use DtApp\ThinkLibrary\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Psr7\Stream;

/**
 * Class WatermarkService
 * @package DtApp\ThinkLibrary\service\weishi
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
     * @return WatermarkService
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
        $url = urldecode($url);
        $headers = [
            'Connection' => 'keep-alive',
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_1_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/16D57 Version/12.0 Safari/604.1'
        ];
        $client = new Client(['timeout' => 2, 'headers' => $headers, 'http_errors' => false,]);
        $data = [];
        if ($headers) {
            $data['headers'] = $headers;
        }
        $data['verify'] = __DIR__ . DIRECTORY_SEPARATOR . 'cacert.pem';
        $jar = new CookieJar;
        $data['cookies'] = $jar;
        if (!$params) {
            $response = $client->request('GET', $url, $data);
        } else {
            $data ['form_params'] = $params;
            $response = $client->request('POST', $url, $data);
        }
        $body = $response->getBody();
        if ($body instanceof Stream) {
            $body = $body->getContents();
        }
        $result = json_decode($body, true);
        $file = 'weishi.txt';
        $fp = fopen($file, 'ab');
        fwrite($fp, $body);
        fclose($fp);
        if ($result['ret'] == 0) {
            $video = $result['data']['feeds'][0];
            $data['video_src'] = $video['video_url'];
            $data['cover_image'] = $video['images'][0]['url'];
            return $data;
        }
        return [];
    }
}