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

declare (strict_types=1);

namespace DtApp\ThinkLibrary\facade;

use DtApp\ThinkLibrary\helper\Files as helper;
use think\Facade;

/**
 * 文件门面
 * @see \DtApp\ThinkLibrary\helper\Files
 * @package DtApp\ThinkLibrary\facade
 * @package think\facade
 * @mixin helper
 *
 * @method static bool delete(string $name) 删除文件
 * @method static bool deletes(string $name) 删除文件夹
 * @method static bool folderZip(string $name, string $suffix_name = '.png', string $file_name = '*') 把文件夹里面的文件打包成zip文件
 * @method static string getFiles(string $path) 获取目录下的所有文件和目录
 * @method static bool rmFiles(string $path) 删除目录下的文件
 * @method static bool judgeFile(string $path) 判断文件是否存在
 * @method static bool judgeContents(string $path) 判断目录是否存在
 */
class Files extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return helper::class;
    }
}
