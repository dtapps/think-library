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

namespace DtApp\ThinkLibrary\helper;

/**
 * 网址管理类
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
    public function lenCode(string $url): string
    {
        if (empty($url)) {
            return '';
        }
        return urlencode($url);
    }

    /**
     * 解码
     * @param string $url
     * @return string
     */
    public function deCode(string $url): string
    {
        if (empty($url)) {
            return '';
        }
        return urldecode($url);
    }

    /**
     * 格式化参数格式化成url参数
     * @param array $data
     * @return string
     */
    public function toParams(array $data): string
    {
        $buff = "";
        foreach ($data as $k => $v) {
            if ($k !== "sign" && $v !== "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 判断是否为Url
     * @param string $url
     * @return bool
     */
    public function isUrl(string $url): bool
    {
        $pattern = "#(http|https)://(.*\.)?.*\..*#i";
        if (preg_match($pattern, $url)) {
            return true;
        }

        return false;
    }

    /**
     * 删除协议
     * @param string $url
     * @return string
     */
    public function deleteProtocol(string $url): string
    {
        if (empty($this->isUrl($url))) {
            return $url;
        }
        if (strpos($url, 'https://') !== false) {
            return str_replace("https://", "//", $url);
        }
        if (strpos($url, 'http://') !== false) {
            return str_replace("http://", "//", $url);
        }
        return $url;
    }

    /**
     * 获取URL文件格式
     * @param string $url
     * @return mixed|string
     */
    public function retrieve(string $url)
    {
        $path = parse_url($url);
        $str = explode('.', $path['path']);
        return $str[1];
    }
}
