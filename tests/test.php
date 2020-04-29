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


use DtApp\ThinkLibrary\service\douyin\DouYinException;
use DtApp\ThinkLibrary\service\douyin\WatermarkService;

require '../vendor/autoload.php';

try {
    // 方法一
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toArray());
    // 方法二
    $dy = WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/');
    var_dump($dy->getAll()->toArray());
    // 获取全部信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toArray());
    // 获取原全部信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getApi()->toArray());
    // 获取视频信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getVideoInfo()->toArray());
    // 获取音频信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getMusicInfo()->toArray());
    // 获取分享信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getShareInfo()->toArray());
    // 获取作者信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAuthorInfo()->toArray());
    // 返回数组数据方法
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toArray());
    // 返回Object数据方法
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toObject());
} catch (DouYinException $e) {
    // 错误提示
    var_dump($e->getMessage());
}
