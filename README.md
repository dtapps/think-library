<img align="right" width="100" src="https://kodo-cdn.dtapp.net/04/999e9f2f06d396968eacc10ce9bc8a.png" alt="www.dtapp.net"/>

<h1 align="left"><a href="https://www.dtapp.net/">ThinkPHP6扩展包</a></h1>

📦 ThinkPHP6扩展包

[![Latest Stable Version](https://poser.pugx.org/liguangchun/think-library/v/stable)](https://packagist.org/packages/liguangchun/think-library) 
[![Latest Unstable Version](https://poser.pugx.org/liguangchun/think-library/v/unstable)](https://packagist.org/packages/liguangchun/think-library) 
[![Total Downloads](https://poser.pugx.org/liguangchun/think-library/downloads)](https://packagist.org/packages/liguangchun/think-library) 
[![License](https://poser.pugx.org/liguangchun/think-library/license)](https://packagist.org/packages/liguangchun/think-library)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.1-8892BF.svg)](http://www.php.net/)
[![Build Status](https://travis-ci.org/GC0202/ThinkLibrary.svg?branch=6.0)](https://travis-ci.org/GC0202/ThinkLibrary)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GC0202/ThinkLibrary/badges/quality-score.png?b=6.0)](https://scrutinizer-ci.com/g/GC0202/ThinkLibrary/?branch=6.0)
[![Code Coverage](https://scrutinizer-ci.com/g/GC0202/ThinkLibrary/badges/coverage.png?b=6.0)](https://scrutinizer-ci.com/g/GC0202/ThinkLibrary/?branch=6.0)

## 依赖环境

1. PHP7.1 版本及以上

## 托管

- 国外仓库地址：[https://github.com/GC0202/ThinkLibrary](https://github.com/GC0202/ThinkLibrary)
- 国内仓库地址：[https://gitee.com/liguangchun/ThinkLibrary](https://gitee.com/liguangchun/ThinkLibrary)
- Packagist 地址：[https://packagist.org/packages/liguangchun/think-library](https://packagist.org/packages/liguangchun/think-library)

### 开发版
```text
composer require liguangchun/think-library ^6.x-dev -vvv
```

### 稳定版
```text
composer require liguangchun/think-library ^6.0.* -vvv
```

## 更新

```text
composer update liguangchun/think-library -vvv
```

## 删除

```text
composer remove liguangchun/think-library -vvv
```

## 获取电脑Mac地址服务使用示例

```php
use DtApp\ThinkLibrary\service\SystemService;

dump(SystemService::instance()
->mac());
```

## 百度地图服务使用示例

```php
use DtApp\ThinkLibrary\service\baidu\LbsYunService;

dump(LbsYunService::instance()
        ->ak("")
        ->weather());
```

## 高德地图服务使用示例

```php
use DtApp\ThinkLibrary\service\amap\AmApService;

dump(AmApService::instance()
        ->key("")
        ->weather());
```

## 抖音服务使用示例

```php
use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\service\douyin\WatermarkService;

try {
    // 方法一之网址
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')
->getAll()
->toArray());
    // 方法一之粘贴
    dump(WatermarkService::instance()->url('#在抖音，记录美好生活#美丽电白欢迎您 https://v.douyin.com/vPGAdM/ 复制此链接，打开【抖音短视频】，直接观看视频！')->getAll()->toArray());
    // 方法二之网址
    $dy = WatermarkService::instance()->url('https://v.douyin.com/vPafcr/');
    dump($dy->getAll()->toArray());
    // 方法二之粘贴
    $dy = WatermarkService::instance()->url('#在抖音，记录美好生活#2020茂名加油，广州加油，武汉加油！中国加油，众志成城！#航拍 #茂名#武汉 #广州 #旅拍 @抖音小助手 https://v.douyin.com/vPafcr/ 复制此链接，打开【抖音短视频】，直接观看视频！');
    dump($dy->getAll()->toArray());
    // 获取全部信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')
->getAll()
->toArray());
    // 获取原全部信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')
->getApi()
->toArray());
    // 获取视频信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')
->getVideoInfo()
->toArray());
    // 获取音频信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')
->getMusicInfo()
->toArray());
    // 获取分享信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')
->getShareInfo()
->toArray());
    // 获取作者信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')
->getAuthorInfo()
->toArray());
    // 返回数组数据方法
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')
->getAll()
->toArray());
    // 返回Object数据方法
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')
->getAll()
->toObject());
} catch (DtaException $e) {
    // 错误提示
    dump($e->getMessage());
}
```

## 纯真数据库

```php
use DtApp\ThinkLibrary\service\QqWryService;

// 获取IP信息
dump(QqWryService::instance()
        ->getLocation());
```
