<img align="right" width="100" src="https://cdn.oss.liguangchun.cn/04/999e9f2f06d396968eacc10ce9bc8a.png" alt="dtApp Logo"/>

<h1 align="left"><a href="https://www.dtapp.net/">ThinkPHP6扩展包</a></h1>

📦 ThinkPHP6扩展包

[![Latest Stable Version](https://poser.pugx.org/liguangchun/think-library/v/stable)](https://packagist.org/packages/liguangchun/think-library) 
[![Latest Unstable Version](https://poser.pugx.org/liguangchun/think-library/v/unstable)](https://packagist.org/packages/liguangchun/think-library) 
[![Total Downloads](https://poser.pugx.org/liguangchun/think-library/downloads)](https://packagist.org/packages/liguangchun/think-library) 
[![License](https://poser.pugx.org/liguangchun/think-library/license)](https://packagist.org/packages/liguangchun/think-library)

## 依赖环境

1. PHP7.1 版本及以上

## 安装

部分代码来自互联网，若有异议可以联系作者进行删除。

- Github仓库地址：https://github.com/GC0202/ThinkLibrary
- 码云仓库地址：https://gitee.com/liguangchun/ThinkLibrary
- gitlab仓库地址：https://gitlab.com/liguangchun/thinklibrary
- 阿里云仓库地址：https://code.aliyun.com/liguancghun/ThinkLibrary
- CODING仓库地址：https://liguangchun-01.coding.net/p/ThinkLibrary/d/ThinkLibrary/git
- CODING仓库地址：https://aizhineng.coding.net/p/ThinkLibrary/d/ThinkLibrary/git
- 腾讯云仓库地址：https://liguangchundt.coding.net/p/ThinkLibrary/d/ThinkLibrary/git
- 微信仓库地址：https://git.weixin.qq.com/liguangchun/ThinkLibrary
- 华为云仓库地址：https://codehub-cn-south-1.devcloud.huaweicloud.com/composer00001/ThinkLibrary.git

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

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\service\baidu\LbsYunService;

try {
    // 获取天气信息
    dump(LbsYunService::instance()
        ->ak("")
        ->weather());
} catch (CurlException $e) {
    dump($e->getMessage());
}

```

## 高德地图服务使用示例

```php

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\service\amap\AmApService;

try {
    // 获取天气信息 
    dump(AmApService::instance()
        ->key("")
        ->weather());
} catch (CurlException $e) {
    dump($e->getMessage());
}


```

## 抖音服务使用示例

```php

use DtApp\ThinkLibrary\exception\DouYinException;
use DtApp\ThinkLibrary\service\douyin\WatermarkService;

try {
    // 方法一 网址
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toArray());
    // 方法一 粘贴
    dump(WatermarkService::instance()->url('#在抖音，记录美好生活#美丽电白欢迎您 https://v.douyin.com/vPGAdM/ 复制此链接，打开【抖音短视频】，直接观看视频！')->getAll()->toArray());
    // 方法二 网址
    $dy = WatermarkService::instance()->url('https://v.douyin.com/vPafcr/');
    dump($dy->getAll()->toArray());
    // 方法二 粘贴
    $dy = WatermarkService::instance()->url('#在抖音，记录美好生活#2020茂名加油，广州加油，武汉加油！中国加油，众志成城！#航拍 #茂名#武汉 #广州 #旅拍 @抖音小助手 https://v.douyin.com/vPafcr/ 复制此链接，打开【抖音短视频】，直接观看视频！');
    dump($dy->getAll()->toArray());
    // 获取全部信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toArray());
    // 获取原全部信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getApi()->toArray());
    // 获取视频信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getVideoInfo()->toArray());
    // 获取音频信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getMusicInfo()->toArray());
    // 获取分享信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getShareInfo()->toArray());
    // 获取作者信息
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAuthorInfo()->toArray());
    // 返回数组数据方法
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toArray());
    // 返回Object数据方法
    dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toObject());
} catch (DouYinException $e) {
    // 错误提示
    dump($e->getMessage());
}
```
