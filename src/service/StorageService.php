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

use DtApp\ThinkLibrary\Service;

/**
 * 本地存储
 * Class StorageService
 * @package DtApp\ThinkLibrary\service
 */
class StorageService extends Service
{
    private $path = '', $remotely = '';

    /**
     * 文件夹
     * @param string $path
     * @return $this
     */
    public function path(string $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * 需要保存的远程文件
     * @param string $remotely
     * @return $this
     */
    public function remotely(string $remotely)
    {
        $this->remotely = $remotely;
        return $this;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig()
    {
        $this->path = $this->app->config->get('dtapp.storage.path');
        return $this;
    }

    /**
     * 保存文件
     * @param string $name 保存的文件名
     * @return array
     */
    public function save(string $name)
    {
        if (empty($this->path)) $this->getConfig();
        // 判断文件夹是否存在
        is_dir($this->path) or mkdir($this->path, 0777, true);
        $return_content = $this->http_get_data($this->remotely);
        $fp = @fopen("{$this->path}{$name}", "a"); //将文件绑定到流
        fwrite($fp, $return_content); //写入文件
        return [
            'file_name' => $name,
            'path' => $this->path,
            'remotely' => $this->remotely,
            'save_path' => "{$this->path}{$name}",
            'size' => $this->bytes($name)
        ];
    }

    /**
     * 下载
     * @param $url
     * @return false|string
     */
    private function http_get_data($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $return_content;
    }

    /**
     * 删除文件
     * @param string $name
     * @return bool
     */
    public function delete(string $name)
    {
        if (empty($this->path)) $this->getConfig();
        if (file_exists($name)) if (unlink($name)) return true;
        return false;
    }

    /**
     * 统计文件大小
     * @param string $name
     * @return string
     */
    public function bytes(string $name)
    {
        if (empty($this->path)) $this->getConfig();
        $bytes = filesize($this->path . $name);
        if ($bytes >= 1073741824) $bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
        elseif ($bytes >= 1048576) $bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
        elseif ($bytes >= 1024) $bytes = round($bytes / 1024 * 100) / 100 . 'KB';
        else   $bytes = $bytes . 'Bytes';
        return $bytes;
    }

    /**
     * 获取文件路径
     * @return string
     */
    public function getPath()
    {
        if (empty($this->path)) $this->getConfig();
        return $this->path;
    }
}
