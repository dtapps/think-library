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
 * 字符串管理类
 * Class Strings
 * @mixin Strings
 * @package DtApp\ThinkLibrary\helper
 */
class Strings
{
    /**
     * 截取字符串前面n个字符
     * @param string $str 字符串
     * @param int $start_num 开始位置
     * @param int $end_num 多少个
     * @return bool|false|string
     */
    public function extractBefore(string $str, int $start_num, int $end_num): string
    {
        if (strlen($str) < $start_num + $end_num) return $str;
        return substr($str, $start_num, $end_num);
    }

    /**
     * 截取字符串最后n个字符
     * @param string $str 字符串
     * @param int $num 多少个
     * @return false|string
     */
    public function extractRear(string $str, int $num): string
    {
        if (strlen($str) <= $num) return $str;
        return substr($str, -$num);
    }

    /**
     * 过滤字符串
     * @param string $str
     * @return string
     */
    public function filter(string $str): string
    {
        $str = str_replace('`', '', $str);
        $str = str_replace('·', '', $str);
        $str = str_replace('~', '', $str);
        $str = str_replace('!', '', $str);
        $str = str_replace('！', '', $str);
        $str = str_replace('@', '', $str);
        $str = str_replace('#', '', $str);
        $str = str_replace('$', '', $str);
        $str = str_replace('￥', '', $str);
        $str = str_replace('%', '', $str);
        $str = str_replace('^', '', $str);
        $str = str_replace('……', '', $str);
        $str = str_replace('&', '', $str);
        $str = str_replace('*', '', $str);
        $str = str_replace('(', '', $str);
        $str = str_replace(')', '', $str);
        $str = str_replace('（', '', $str);
        $str = str_replace('）', '', $str);
        $str = str_replace('-', '', $str);
        $str = str_replace('_', '', $str);
        $str = str_replace('——', '', $str);
        $str = str_replace('+', '', $str);
        $str = str_replace('=', '', $str);
        $str = str_replace('|', '', $str);
        $str = str_replace('\\', '', $str);
        $str = str_replace('[', '', $str);
        $str = str_replace(']', '', $str);
        $str = str_replace('【', '', $str);
        $str = str_replace('】', '', $str);
        $str = str_replace('{', '', $str);
        $str = str_replace('}', '', $str);
        $str = str_replace(';', '', $str);
        $str = str_replace('；', '', $str);
        $str = str_replace(':', '', $str);
        $str = str_replace('：', '', $str);
        $str = str_replace('\'', '', $str);
        $str = str_replace('"', '', $str);
        $str = str_replace('“', '', $str);
        $str = str_replace('”', '', $str);
        $str = str_replace(',', '', $str);
        $str = str_replace('，', '', $str);
        $str = str_replace('<', '', $str);
        $str = str_replace('>', '', $str);
        $str = str_replace('《', '', $str);
        $str = str_replace('》', '', $str);
        $str = str_replace('.', '', $str);
        $str = str_replace('。', '', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace('、', '', $str);
        $str = str_replace('?', '', $str);
        $str = str_replace('？', '', $str);
        $str = str_replace('╮', '', $str);
        $str = str_replace('(', '', $str);
        $str = str_replace(')', '', $str);
        $str = str_replace('r', '', $str);
        $str = str_replace('ぷ', '', $str);
        $str = str_replace('〆', '', $str);
        $str = str_replace('ゞ', '', $str);
        $str = str_replace('ヤ', '', $str);
        $str = str_replace('ゼ', '', $str);
        $str = str_replace('ǎ', '', $str);
        $str = str_replace('ǎ', '', $str);
        $str = str_replace('〆', '', $str);
        $str = str_replace('む', '', $str);
        $str = str_replace('§', '', $str);
        $str = str_replace('上门', '', $str);
        return trim($str);
    }

    /**
     * 判断字符串是否包含某个字符
     * @param $str
     * @param int $nee
     * @param string $del
     * @return bool
     */
    public function exitContain(string $str, $nee = 3, $del = ','): bool
    {
        if (strpos($str, $del) !== false) {
            $var = explode($del, $str);
            foreach ($var as $v) if ($v == $nee) return true;
            return false;
        } else {
            if ($str == $nee) return true;
            return false;
        }
    }

    /**
     * 统计字符串长度
     * @param string $str 字符串
     * @return int
     */
    public function len(string $str): int
    {
        return strlen($str);
    }

    /**
     * 删除空格
     * @param $str
     * @return string|string[]
     */
    public function trimAll($str): string
    {
        $oldchar = array(" ", "　", "\t", "\n", "\r");
        $newchar = array("", "", "", "", "");
        return str_replace($oldchar, $newchar, $str);
    }

    /**
     * 替换字符串
     * @param string $search
     * @param string $replace
     * @param string $subject
     * @return string|string[]
     */
    public function replace(string $search, string $replace, string $subject)
    {
        return str_replace($search, $replace, $subject);
    }
}
