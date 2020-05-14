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

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\exception\TaoBaoException;
use DtApp\ThinkLibrary\service\TaoBao\TbkService;

require '../vendor/autoload.php';

try {
    dump(TbkService::instance()
        ->param([
            'text' => '阿道夫亮泽丝滑强韧顺滑洗发水洗发露露520ml男女通用香水型留香',
            'url' => 'https://uland.taobao.com/coupon/edetail?spm=a231o.13503973.20618785.2.3c9e68edEfJXWy&e=Q%2BAO1KmJyT4NfLV8niU3R40dlhWtfp96Ng4Gqf8CT4BnmB%2Fzds2ljSjtERSBzDzDvXdZtqV9xDjzg9ez7b%2B0xFO9UZ2JzZsyuXtXCVcc63e88CepcReeb7cZelJt%2Bzjy1W5E8%2FU5ompiSInajIuNIHM%2BJjo56b%2BuTEL%2F6LR%2FVNjJTE40kLCuKeW4BgFGCWxt&app_pvid=59590_11.1.87.182_599_1589349631562&ptl=floorId%3A25067%3Bapp_pvid%3A59590_11.1.87.182_599_1589349631562%3Btpp_pvid%3A5ff5ce57-c453-4c5e-b871-07ce050e67e5&union_lens=lensId%3AOPT%401589349631%400b0157b6_0f0c_1720c9ec7e8_407e%4001%3Brecoveryid%3A201_11.23.83.213_1361143_1589349629644%3Bprepvid%3A201_11.23.83.213_1361143_1589349629644&pid=mm_114410142_13616595_54524110'
        ])
        ->tpWdCreate()
        ->toArray()
    );
} catch (TaoBaoException $e) {
    dump($e->getMessage());
} catch (CurlException $e) {
    dump($e->getMessage());
}
