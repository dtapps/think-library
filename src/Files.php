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

namespace DtApp\ThinkLibrary;

/**
 * 文件管理类
 * Class Files
 * @mixin Files
 * @package DtApp\ThinkLibrary
 */
class Files
{
    /**
     * 删除文件
     * @param string $name 路径
     * @return bool
     */
    public function delete(string $name)
    {
        if (file_exists($name)) if (unlink($name)) return true;
        return false;
    }

    /**
     * 删除文件夹
     * @param string $name 路径
     * @return bool
     */
    public function deletes(string $name)
    {
        //先删除目录下的文件：
        $dh = opendir($name);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $name . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deletes($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if (rmdir($name)) {
            return true;
        } else {
            return false;
        }
    }
}
