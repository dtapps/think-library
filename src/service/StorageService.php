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

namespace DtApp\ThinkLibrary\service;

use DtApp\ThinkLibrary\Service;

/**
 * 本地存储
 * Class StorageService
 * @package DtApp\ThinkLibrary\service
 */
class StorageService extends Service
{
    private $path = '';

    public function path(string $path)
    {
        $this->path = $path;
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
     * @param $name
     * @param string $path
     * @return false|int
     */
    public function save(string $name, string $path = '')
    {
        if (empty($this->path)) $this->getConfig();
        if (empty($path)) $this->path = "{$this->path}/{$name}";
        else $this->path = $path;
        return file_put_contents($this->path, $name);
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
     * @param $bytes
     * @return string
     */
    public function bytes($bytes)
    {
        $bytes = filesize($bytes);
        if ($bytes >= 1073741824) $bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
        elseif ($bytes >= 1048576) $bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
        elseif ($bytes >= 1024) $bytes = round($bytes / 1024 * 100) / 100 . 'KB';
        else   $bytes = $bytes . 'Bytes';
        return $bytes;
    }
}
