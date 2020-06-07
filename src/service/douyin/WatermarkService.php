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

namespace DtApp\ThinkLibrary\service\douyin;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\facade\Pregs;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\aliyun\OssService;
use DtApp\ThinkLibrary\service\baidu\BosService;
use DtApp\ThinkLibrary\service\huaweicloud\ObsService;
use DtApp\ThinkLibrary\service\StorageService;
use DtApp\ThinkLibrary\service\tencent\CosService;
use stdClass;

/**
 * 抖音-视频去水印
 * Class WatermarkService
 * @package DtApp\ThinkLibrary\service\douyin
 */
class WatermarkService extends Service
{
    private $url;
    private $api_url;
    private $itemId;
    private $dytk;
    private $contents;
    private $backtrack;
    private $storage;
    private $storagePath;

    /**
     * 配置网址
     * @param $str
     * @return $this
     * @throws DtaException
     */
    public function url($str)
    {
        if (Pregs::isLink($str)) {
            $url = $this->judgeUrl($str);
            if (empty($url)) throw new DtaException('配置网址内容不正确');
            $this->url = $url;
        } else {
            preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $str, $match);
            $url = $this->judgeUrl($match[0][0]);
            if (empty($url)) throw new DtaException('配置网址内容不正确');
            $this->url = $url;
        }
        $content = $this->getContents($this->url);
        $this->itemId = $this->getItemId($content);
        $this->dytk = $this->getDyTk($content);
        return $this;
    }

    /**
     * 云存储
     * @param string $type
     * @param string $path
     * @return $this
     */
    public function storage(string $type, string $path)
    {
        $this->storage = $type;
        $this->storagePath = $path;
        return $this;
    }

    /**
     * 获取作者信息
     * @return string
     */
    public function getAuthorInfo()
    {
        $this->getApi();
        $data = json_decode($this->contents, true);
        if (empty($data['status_code'])) {
            $item_list = $data['item_list'][0];
            $backtrack = [];
            $backtrack['uid'] = $item_list['author']['uid'];
            $backtrack['nickname'] = $item_list['author']['nickname'];
            $backtrack['unique_id'] = $item_list['author']['unique_id'];
            $backtrack['short_id'] = $item_list['author']['short_id'];
            $backtrack['avatar'] = $this->cAuthorAvatar($item_list['author']);
            $this->backtrack = $backtrack;
        } else {
            $this->backtrack = [];
        }
        return $this;
    }

    /**
     * 获取分享信息
     * @return string
     */
    public function getShareInfo()
    {
        $this->getApi();
        $data = json_decode($this->contents, true);
        if (empty($data['status_code'])) {
            $item_list = $data['item_list'][0];
            $backtrack = [];
            $backtrack['weibo_desc'] = $item_list['share_info']['share_weibo_desc'];
            $backtrack['desc'] = $item_list['share_info']['share_desc'];
            $backtrack['title'] = $item_list['share_info']['share_title'];
            $backtrack['url'] = $item_list['share_url'];
            $this->backtrack = $backtrack;
        } else {
            $this->backtrack = [];
        }
        return $this;
    }

    /**
     * 获取音乐信息
     * @return string
     */
    public function getMusicInfo()
    {
        $this->getApi();
        $data = json_decode($this->contents, true);
        if (empty($data['status_code'])) {
            $item_list = $data['item_list'][0];
            $backtrack = [];
            $backtrack['id'] = $item_list['music']['id'];
            $backtrack['mid'] = $item_list['music']['mid'];
            $backtrack['title'] = $item_list['music']['title'];
            $backtrack['author'] = $item_list['music']['author'];
            $backtrack['avatar'] = $this->cMusicAvatar($item_list['music']);
            $backtrack['play'] = $item_list['music']['play_url']['uri'];
            $backtrack['cover'] = $item_list['music']['cover_large']['url_list'][0];
            $this->backtrack = $backtrack;
        } else {
            $this->backtrack = [];
        }
        return $this;
    }

    /**
     * 获取视频信息
     * @return string
     */
    public function getVideoInfo()
    {
        $this->getApi();
        $data = json_decode($this->contents, true);
        if (empty($data['status_code'])) {
            $item_list = $data['item_list'][0];
            $backtrack = [];
            $backtrack['vid'] = $item_list['video']['vid'];
            $backtrack['desc'] = $item_list['desc'];
            $backtrack['width'] = $item_list['video']['width'];
            $backtrack['height'] = $item_list['video']['height'];
            $cVideoAvatar = $this->cVideoAvatar($item_list['video']);
            $backtrack['dynamic'] = $cVideoAvatar['dynamic'];
            $backtrack['origin_cover'] = $cVideoAvatar['origin_cover'];
            $backtrack['cover'] = $cVideoAvatar['cover'];
            $backtrack['play'] = $this->cVideoPlayUrl($item_list['video']['play_addr']['url_list'][0], 'play');
            $backtrack['playwm'] = $this->cVideoPlayUrl($item_list['video']['play_addr']['url_list'][0], 'playwm');
            $this->backtrack = $backtrack;
        } else {
            $this->backtrack = [];
        }
        return $this;
    }

    /**
     * 获取接口全部信息
     * @return $this
     */
    public function getApi()
    {
        $this->api_url = "https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids={$this->itemId}&dytk={$this->dytk}";
        $this->contents = $this->getContents($this->api_url);
        $this->backtrack = $this->contents;
        return $this;
    }

    /**
     * 获取全部信息
     * @return $this
     */
    public function getAll()
    {
        $this->getApi();
        $data = json_decode($this->contents, true);
        if (empty($data['status_code'])) {
            $item_list = $data['item_list'][0];
            $backtrack = [];
            // 作者信息
            $backtrack['author_info']['uid'] = $item_list['author']['uid'];
            $backtrack['author_info']['nickname'] = $item_list['author']['nickname'];
            $backtrack['author_info']['unique_id'] = $item_list['author']['unique_id'];
            $backtrack['author_info']['short_id'] = $item_list['author']['short_id'];
            $backtrack['author_info']['avatar'] = $this->cAuthorAvatar($item_list['author']);
            // 分享信息
            $backtrack['share_info']['weibo_desc'] = $item_list['share_info']['share_weibo_desc'];
            $backtrack['share_info']['desc'] = $item_list['share_info']['share_desc'];
            $backtrack['share_info']['title'] = $item_list['share_info']['share_title'];
            $backtrack['share_info']['url'] = $item_list['share_url'];
            // 音乐信息
            $backtrack['music_info']['id'] = $item_list['music']['id'];
            $backtrack['music_info']['mid'] = $item_list['music']['mid'];
            $backtrack['music_info']['title'] = $item_list['music']['title'];
            $backtrack['music_info']['author'] = $item_list['music']['author'];
            $backtrack['music_info']['avatar'] = $this->cMusicAvatar($item_list['music']);
            $backtrack['music_info']['play'] = $item_list['music']['play_url']['uri'];
            $backtrack['music_info']['cover'] = $item_list['music']['cover_large']['url_list'][0];
            // 视频信息
            $backtrack['video_info']['vid'] = $item_list['video']['vid'];
            $backtrack['video_info']['desc'] = $item_list['desc'];
            $backtrack['video_info']['width'] = $item_list['video']['width'];
            $backtrack['video_info']['height'] = $item_list['video']['height'];
            $cVideoAvatar = $this->cVideoAvatar($item_list['video']);
            $backtrack['video_info']['dynamic'] = $cVideoAvatar['dynamic'];
            $backtrack['video_info']['origin_cover'] = $cVideoAvatar['origin_cover'];
            $backtrack['video_info']['cover'] = $cVideoAvatar['cover'];
            $backtrack['video_info']['play'] = $this->cVideoPlayUrl($item_list['video']['play_addr']['url_list'][0], 'play');
            $backtrack['video_info']['playwm'] = $this->cVideoPlayUrl($item_list['video']['play_addr']['url_list'][0], 'playwm');
            if (!empty($this->storage)) {
                // 头像
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['author_info']['avatar'])
                    ->save($backtrack['author_info']['uid'] . ".jpeg");
                // 音频
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['music_info']['play'])
                    ->save($backtrack['music_info']['mid'] . ".mp3");
                // 封面
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['dynamic'])
                    ->save($backtrack['video_info']['vid'] . "_dynamic" . ".jpeg");
                // 封面
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['origin_cover'])
                    ->save($backtrack['video_info']['vid'] . "_origin_cover" . ".jpeg");
                // 封面
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['cover'])
                    ->save($backtrack['video_info']['vid'] . "_cover" . ".jpeg");
                // 视频
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['play'])
                    ->save($backtrack['video_info']['vid'] . "_play" . ".mp4");
                // 视频
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['playwm'])
                    ->save($backtrack['video_info']['vid'] . "_playwm" . ".mp4");
                $system_path = StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->getPath();
                $yun_path = "douyin/" . $backtrack['author_info']['uid'] . '/';
                // 删除到云存储
                $backtrack['yun']['platform'] = $this->storage;
                switch ($this->storage) {
                    case "aliyun":
                        // 头像
                        $backtrack['yun']['author_info']['avatar'] = OssService::instance()
                            ->upload($yun_path . $backtrack['author_info']['uid'] . ".jpeg", $system_path . $backtrack['author_info']['uid'] . ".jpeg");
                        // 音频
                        $backtrack['yun']['music_info']['play'] = OssService::instance()
                            ->upload($yun_path . $backtrack['music_info']['mid'] . ".mp3", $system_path . $backtrack['music_info']['mid'] . ".mp3");
                        // 封面
                        $backtrack['yun']['video_info']['dynamic'] = OssService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_dynamic" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_dynamic" . ".jpeg");
                        // 封面
                        $backtrack['yun']['video_info']['origin_cover'] = OssService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_origin_cover" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_origin_cover" . ".jpeg");
                        // 封面
                        $backtrack['yun']['video_info']['cover'] = OssService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_cover" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_cover" . ".jpeg");
                        // 视频
                        $backtrack['yun']['video_info']['play'] = OssService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_play" . ".mp4", $system_path . $backtrack['video_info']['vid'] . "_play" . ".mp4");
                        // 视频
                        $backtrack['yun']['video_info']['playwm'] = OssService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_playwm" . ".mp4", $system_path . $backtrack['video_info']['vid'] . "_playwm" . ".mp4");
                        break;
                    case "tencent":
                        // 头像
                        $backtrack['yun']['author_info']['avatar'] = CosService::instance()
                            ->upload($yun_path . $backtrack['author_info']['uid'] . ".jpeg", $system_path . $backtrack['author_info']['uid'] . ".jpeg");
                        // 音频
                        $backtrack['yun']['music_info']['play'] = CosService::instance()
                            ->upload($yun_path . $backtrack['music_info']['mid'] . ".mp3", $system_path . $backtrack['music_info']['mid'] . ".mp3");
                        // 封面
                        $backtrack['yun']['video_info']['dynamic'] = CosService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_dynamic" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_dynamic" . ".jpeg");
                        // 封面
                        $backtrack['yun']['video_info']['origin_cover'] = CosService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_origin_cover" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_origin_cover" . ".jpeg");
                        // 封面
                        $backtrack['yun']['video_info']['cover'] = CosService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_cover" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_cover" . ".jpeg");
                        // 视频
                        $backtrack['yun']['video_info']['play'] = CosService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_play" . ".mp4", $system_path . $backtrack['video_info']['vid'] . "_play" . ".mp4");
                        // 视频
                        $backtrack['yun']['video_info']['playwm'] = CosService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_playwm" . ".mp4", $system_path . $backtrack['video_info']['vid'] . "_playwm" . ".mp4");
                        break;
                    case "huaweicloud":
                        // 头像
                        $backtrack['yun']['author_info']['avatar'] = ObsService::instance()
                            ->upload($yun_path . $backtrack['author_info']['uid'] . ".jpeg", $system_path . $backtrack['author_info']['uid'] . ".jpeg");
                        // 音频
                        $backtrack['yun']['music_info']['play'] = ObsService::instance()
                            ->upload($yun_path . $backtrack['music_info']['mid'] . ".mp3", $system_path . $backtrack['music_info']['mid'] . ".mp3");
                        // 封面
                        $backtrack['yun']['video_info']['dynamic'] = ObsService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_dynamic" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_dynamic" . ".jpeg");
                        // 封面
                        $backtrack['yun']['video_info']['origin_cover'] = ObsService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_origin_cover" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_origin_cover" . ".jpeg");
                        // 封面
                        $backtrack['yun']['video_info']['cover'] = ObsService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_cover" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_cover" . ".jpeg");
                        // 视频
                        $backtrack['yun']['video_info']['play'] = ObsService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_play" . ".mp4", $system_path . $backtrack['video_info']['vid'] . "_play" . ".mp4");
                        // 视频
                        $backtrack['yun']['video_info']['playwm'] = ObsService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_playwm" . ".mp4", $system_path . $backtrack['video_info']['vid'] . "_playwm" . ".mp4");
                        break;
                    case "baidu":
                        // 头像
                        $backtrack['yun']['author_info']['avatar'] = BosService::instance()
                            ->upload($yun_path . $backtrack['author_info']['uid'] . ".jpeg", $system_path . $backtrack['author_info']['uid'] . ".jpeg");
                        // 音频
                        $backtrack['yun']['music_info']['play'] = BosService::instance()
                            ->upload($yun_path . $backtrack['music_info']['mid'] . ".mp3", $system_path . $backtrack['music_info']['mid'] . ".mp3");
                        // 封面
                        $backtrack['yun']['video_info']['dynamic'] = BosService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_dynamic" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_dynamic" . ".jpeg");
                        // 封面
                        $backtrack['yun']['video_info']['origin_cover'] = BosService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_origin_cover" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_origin_cover" . ".jpeg");
                        // 封面
                        $backtrack['yun']['video_info']['cover'] = BosService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_cover" . ".jpeg", $system_path . $backtrack['video_info']['vid'] . "_cover" . ".jpeg");
                        // 视频
                        $backtrack['yun']['video_info']['play'] = BosService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_play" . ".mp4", $system_path . $backtrack['video_info']['vid'] . "_play" . ".mp4");
                        // 视频
                        $backtrack['yun']['video_info']['playwm'] = BosService::instance()
                            ->upload($yun_path . $backtrack['video_info']['vid'] . "_playwm" . ".mp4", $system_path . $backtrack['video_info']['vid'] . "_playwm" . ".mp4");
                        break;
                    default:
                        break;
                }
                // 大小信息
                // 头像
                $backtrack['size']['author_info']['avatar'] = StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['author_info']['avatar'])
                    ->bytes($backtrack['author_info']['uid'] . ".jpeg");
                // 音频
                $backtrack['size']['music_info']['play'] = StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['music_info']['play'])
                    ->bytes($backtrack['music_info']['mid'] . ".mp3");
                // 封面
                $backtrack['size']['video_info']['dynamic'] = StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['dynamic'])
                    ->bytes($backtrack['video_info']['vid'] . "_dynamic" . ".jpeg");
                // 封面
                $backtrack['size']['video_info']['origin_cover'] = StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['origin_cover'])
                    ->bytes($backtrack['video_info']['vid'] . "_origin_cover" . ".jpeg");
                // 封面
                $backtrack['size']['video_info']['cover'] = StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['cover'])
                    ->bytes($backtrack['video_info']['vid'] . "_cover" . ".jpeg");
                // 视频
                $backtrack['size']['video_info']['play'] = StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['play'])
                    ->bytes($backtrack['video_info']['vid'] . "_play" . ".mp4");
                // 视频
                $backtrack['size']['video_info']['playwm'] = StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['playwm'])
                    ->bytes($backtrack['video_info']['vid'] . "_playwm" . ".mp4");
                // 删除文件
                // 头像
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['author_info']['avatar'])
                    ->delete($backtrack['author_info']['uid'] . ".jpeg");
                // 音频
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['music_info']['play'])
                    ->delete($backtrack['music_info']['mid'] . ".mp3");
                // 封面
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['dynamic'])
                    ->delete($backtrack['video_info']['vid'] . "_dynamic" . ".jpeg");
                // 封面
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['origin_cover'])
                    ->delete($backtrack['video_info']['vid'] . "_origin_cover" . ".jpeg");
                // 封面
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['cover'])
                    ->delete($backtrack['video_info']['vid'] . "_cover" . ".jpeg");
                // 视频
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['play'])
                    ->delete($backtrack['video_info']['vid'] . "_play" . ".mp4");
                // 视频
                StorageService::instance()
                    ->path($this->storagePath . $backtrack['author_info']['uid'] . "/")
                    ->remotely($backtrack['video_info']['playwm'])
                    ->delete($backtrack['video_info']['vid'] . "_playwm" . ".mp4");
            }
            $this->backtrack = $backtrack;
        } else {
            $this->backtrack = [];
        }
        return $this;
    }

    private function getContents($url)
    {
        ini_set('user_agent', 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) CriOS/56.0.2924.75 Mobile/14E5239e Safari/602.1');
        return file_get_contents($url);
    }

    /**
     * 判断网址是否确认
     * @param $url
     * @return string
     */
    private function judgeUrl($url)
    {
        if (strpos($url, 'douyin.com') !== false) return $url;
        else if (strpos($url, 'iesdouyin.com') !== false) return $url;
        else return '';
    }

    /**
     * 正则匹配 mid
     * @param $content
     * @return mixed
     * @throws DtaException
     */
    private function getItemId($content)
    {
        preg_match('/"(?<=itemId:\s\")\d+"/', $content, $matches);
        if (!isset($matches[0])) throw new DtaException('视频不存在');
        preg_match("~\"(.*?)\"~", $matches[0], $matches2);
        if (!isset($matches2[1])) throw new DtaException('视频不存在');
        return $matches2[1];
    }

    /**
     * 正则匹配 dytk
     * @param $content
     * @return mixed
     * @throws DtaException
     */
    private function getDyTk($content)
    {
        preg_match("~dytk(.*?)}~", $content, $matches);
        if (!isset($matches[1])) throw new DtaException('视频不存在');
        $Dytk = $matches[1];
        preg_match("~\"(.*?)\"~", $Dytk, $matches2);
        if (!isset($matches2[1])) throw new DtaException('视频不存在');
        return $matches2[1];
    }

    /**
     * 返回Array
     * @return array|mixed
     */
    public function toArray()
    {
        if (empty($this->backtrack)) return [];
        if (is_array($this->backtrack)) return $this->backtrack;
        return json_decode($this->backtrack, true);
    }

    /**
     * 返回Object
     * @return object|string|mixed
     */
    public function toObject()
    {
        if (empty($this->backtrack)) return '';
        if (is_object($this->backtrack)) return $this->backtrack;
        $obj = new StdClass();
        foreach ($this->backtrack as $key => $val) $obj->$key = $val;
        return $obj;
    }

    /**
     * 处理作者头像 大到小
     * @param $data
     * @return string
     */
    private function cAuthorAvatar($data)
    {
        // 1080x1080
        if (isset($data['avatar_larger']['url_list'][0])) return $data['avatar_larger']['url_list'][0];
        // 720x720
        if (isset($data['avatar_medium']['url_list'][0])) return $data['avatar_medium']['url_list'][0];
        // 100x100
        if (isset($data['avatar_thumb']['url_list'][0])) return $data['avatar_thumb']['url_list'][0];
        return '';
    }

    /**
     * 处理音乐作者头像 大到小
     * @param $data
     * @return string
     */
    private function cMusicAvatar($data)
    {
        // 1080x1080
        if (isset($data['cover_hd']['url_list'][0])) return $data['cover_hd']['url_list'][0];
        // 720x720
        if (isset($data['cover_medium']['url_list'][0])) return $data['cover_medium']['url_list'][0];
        // 100x100
        if (isset($data['cover_thumb']['url_list'][0])) return $data['cover_thumb']['url_list'][0];
        return '';
    }

    /**
     * 处理视频封面 大到小
     * @param $data
     * @return array
     */
    private function cVideoAvatar($data)
    {
        $array = [];
        $array['dynamic'] = '';
        $array['origin_cover'] = '';
        $array['cover'] = '';
        // 动态
        if (isset($data['dynamic_cover']['url_list'][0])) $array['dynamic'] = substr($data['dynamic_cover']['url_list'][0], 0, strpos($data['dynamic_cover']['url_list'][0], '?from='));
        // width封面
        if (isset($data['origin_cover']['url_list'][0])) $array['origin_cover'] = substr($data['origin_cover']['url_list'][0], 0, strpos($data['origin_cover']['url_list'][0], '?from='));
        // height封面
        if (isset($data['cover']['url_list'][0])) $array['cover'] = substr($data['cover']['url_list'][0], 0, strpos($data['cover']['url_list'][0], '?from='));
        return $array;
    }

    /**
     * 返回302网址
     * @param $url
     * @param $type
     * @return mixed
     */
    private function cVideoPlayUrl($url, $type)
    {
        if ($type == 'play') $headers = get_headers(str_replace("/playwm/", "/play/", $url), TRUE);
        else  $headers = get_headers($url, TRUE);
        //输出跳转到的网址
        return $headers['location'];
    }
}
