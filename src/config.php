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

return [
    // 淘宝
    'taobao' => [
        // 淘宝客
        'tbk' => [
            'app_key' => '',
            'app_secret' => '',
        ]
    ],
    // 拼多多
    'pinduoduo' => [
        // 进宝
        'jinbao' => [
            // 开放平台分配的clientId
            'client_id' => '',
            // 开放平台分配的clientSecret
            'client_secret' => '',
        ]
    ],
    // 京东
    'jd' => [
        // 京东联盟
        'union' => [
            // 联盟分配给应用的appkey
            'app_key' => '',
            // 联盟分配给应用的secretkey
            'secret_key' => '',
        ]
    ],
    // 宝塔
    'bt' => [
        // 密钥
        'key' => '',
        // 网址
        'panel' => '',
    ],
    // 百度
    'baidu' => [
        // 地图
        'lbs' => [
            'ak' => ''
        ],
        // 百度云
        'bos' => [
            'access_key_id' => '',
            'secret_access_key' => '',
            'endpoint' => '',
            'bucket' => '',
            'url' => '',
        ]
    ],
    // 高德地图
    'amap' => [
        'key' => ''
    ],
    // 微信
    'wechat' => [
        // 公众号
        'webapp' => [
            'cache' => 'file',
            'app_id' => '',
            'app_secret' => '',
        ],
        // 小程序
        'mini' => [
            'cache' => 'file',
            'app_key' => '',
            'app_secret' => '',
        ],
        // 企业微信
        'qy' => [

        ]
    ],
    // 错误通知
    'exception' => [
        'type' => 'dingtalk',
        // 钉钉
        'dingtalk' => [
            'access_token' => ''
        ],
        // 企业微信
        'qyweixin' => [
            'key' => ''
        ],
        // 微信
        'wechat' => [
            // 通知微信
            'openid' => '',
            // 网站名称
            'node' => '',
            // 服务器IP（节点）
            'ip' => ''
        ]
    ],
    // 阿里云
    'aliyun' => [
        'oss' => [
            'access_key_id' => '',
            'access_key_secret' => '',
            'endpoint' => '',
            'bucket' => '',
            'url' => '',
        ]
    ],
    // 腾讯云
    'tencent' => [
        'cos' => [
            'secret_id' => '',
            'secret_key' => '',
            'region' => '',
            'bucket' => '',
            'url' => '',
        ]
    ],
    // 华为云
    'huaweicloud' => [
        'obs' => [
            'key' => '',
            'secret' => '',
            'endpoint' => '',
            'bucket' => '',
            'url' => '',
        ]
    ],
    // 本地存储
    'storage' => [
        'path' => ''
    ],
    // 又拍云
    'upyun' => [
        // 又拍云存储
        'uss' => [
            'service_name' => '',
            'operator_name' => '',
            'operator_password' => '',
            'url' => '',
        ]
    ],
    // 七牛云
    'qiniu' => [
        // 云存储
        'kodo' => [
            'access_key' => '',
            'secret_key' => '',
            'bucket' => '',
            'url' => '',
        ]
    ],
    // 金山云
    'ksyun' => [
        // 云存储
        'ks3' => [
            'access_key_iD' => '',
            'access_key_secret' => '',
            'endpoint' => '',
            'bucket' => '',
            'url' => '',
        ]
    ],
    // 网易云
    'netease' => [
        // 云存储
        'ks3' => [
            'access_key_id' => '',
            'access_key_secret' => '',
            'endpoint' => '',
            'bucket' => '',
            'url' => '',
        ]
    ],
    // UCloud优刻得
    'ucloud' => [
        // 云存储
        'ufile' => [
            'proxy_suffix' => '',
            'public_key' => '',
            'private_key' => '',
            'bucket' => '',
            'url' => '',
        ]
    ],
];
