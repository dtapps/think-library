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
    // 方法一 网址
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toArray());
    // 方法一 粘贴
    var_dump(WatermarkService::instance()->url('#在抖音，记录美好生活#美丽电白欢迎您 https://v.douyin.com/vPGAdM/ 复制此链接，打开【抖音短视频】，直接观看视频！')->getAll()->toArray());
    // 方法二 网址
    $dy = WatermarkService::instance()->url('https://v.douyin.com/vPafcr/');
    var_dump($dy->getAll()->toArray());
    // 方法二 粘贴
    $dy = WatermarkService::instance()->url('#在抖音，记录美好生活#2020茂名加油，广州加油，武汉加油！中国加油，众志成城！#航拍 #茂名#武汉 #广州 #旅拍 @抖音小助手 https://v.douyin.com/vPafcr/ 复制此链接，打开【抖音短视频】，直接观看视频！');
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
