<?php

// +----------------------------------------------------------------------
// | ThinkLibrary 5.1 for ThinkPhP 5.1
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

namespace DtApp\ThinkLibrary;

use DtApp\ThinkLibrary\exception\DtAppException;

/**
 * XML管理类
 * Class Xmls
 * @mixin Xmls
 * @package DtApp\ThinkLibrary
 */
class Xmls
{
    /**
     * 数组转换为xml
     * @param array $values 数组
     * @return string
     * @throws DtAppException
     */
    public function toXml(array $values)
    {
        if (!is_array($values) || count($values) <= 0) throw new DtAppException('数组数据异常！');
        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . toXml($val) . "</" . $key . ">";
            } else if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 将XML转为array
     * @param string $xml
     * @return mixed
     * @throws DtAppException
     */
    public function toArray(string $xml)
    {
        if (!$xml) throw new DtAppException('xml数据异常！');
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
}
