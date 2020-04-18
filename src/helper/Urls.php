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

namespace DtApp\ThinkLibrary\helper;

/**
 * 网址管理类
 * Class Urls
 * @mixin Urls
 * @package DtApp\ThinkLibrary\helper
 */
class Urls
{
    /**
     * 编码
     * @param string $url
     * @return string
     */
   public function lenCode(string $url)
    {
        if (empty($url)) return false;
        return urlencode($url);
    }

    /**
     * 解码
     * @param string $url
     * @return string
     */
   public function deCode(string $url)
    {
        if (empty($url)) return false;
        return urldecode($url);
    }

    /**
     * 格式化参数格式化成url参数
     * @param array $data
     * @return string
     */
   public function toParams(array $data)
    {
        $buff = "";
        foreach ($data as $k => $v) if ($k != "sign" && $v !== "" && !is_array($v)) $buff .= $k . "=" . $v . "&";
        $buff = trim($buff, "&");
        return $buff;
    }
}
