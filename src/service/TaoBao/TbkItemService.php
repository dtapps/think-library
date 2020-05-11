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

namespace DtApp\ThinkLibrary\service\TaoBao;

use DtApp\ThinkLibrary\Service;

class TbkItemService extends Service
{
    /**
     * 淘宝客-公用-商品关联推荐
     * https://open.taobao.com/api.htm?docId=24517&docType=2
     */
    public function recommend(string $fields, int $num_iid, int $count = 20, int $platform = 1)
    {
        $c = new \TopClient();
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $req = new TbkIte;
        $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url");
        $req->setNumIid("123");
        $req->setCount("20");
        $req->setPlatform("1");
        $resp = $c->execute($req);
    }

    public function info()
    {

    }
}
