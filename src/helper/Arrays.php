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
 * 数组管理类
 * Class Arrays
 * @package DtApp\ThinkLibrary\helper
 */
class Arrays
{
    /**
     * 数组随机返回一个下标
     * @param $arr
     * @return mixed
     */
    public function rand(array $arr)
    {
        return array_rand($arr);
    }

    /**
     * 数组随机返回一个值
     * @param $arr
     * @return mixed
     */
    public function randValue(array $arr)
    {
        return $arr[array_rand($arr)];
    }

    /**
     * 分隔数组
     * @param array $data 数组
     * @param int $num 数量
     * @return array
     */
    public function split(array $data, $num = 5): array
    {
        $arrRet = array();
        if (!isset($data) || empty($data)) return $arrRet;
        $iCount = count($data) / $num;
        if (!is_int($iCount)) $iCount = ceil($iCount);
        else $iCount += 1;
        for ($i = 0; $i < $iCount; ++$i) {
            $arrInfos = array_slice($data, $i * $num, $num);
            if (empty($arrInfos)) continue;
            $arrRet[] = $arrInfos;
            unset($arrInfos);
        }
        return $arrRet;
    }
}
