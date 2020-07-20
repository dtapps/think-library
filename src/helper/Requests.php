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
// | gitlab 仓库地址 ：https://gitlab.com/liguangchun/thinklibrary
// | weixin 仓库地址 ：https://git.weixin.qq.com/liguangchun/ThinkLibrary
// | huaweicloud 仓库地址 ：https://codehub-cn-south-1.devcloud.huaweicloud.com/composer00001/ThinkLibrary.git
// | Packagist 地址 ：https://packagist.org/packages/liguangchun/think-library
// +----------------------------------------------------------------------

declare (strict_types=1);

namespace DtApp\ThinkLibrary\helper;

/**
 * 请求管理类
 * @mixin Requests
 * @package DtApp\ThinkLibrary\helper
 */
class Requests
{
    /**
     * 判断输入的参数
     * @param array $data
     * @param array $arr
     * @return array
     */
    public function isEmpty(array $data, array $arr): array
    {
        foreach ($arr as $k => $v) {
            if (empty(isset($data["$v"]) ? $data["$v"] : '')) {
                return [];
            }
        }
        return $data;
    }

    /**
     * 判断输入的参数为空就返回Json错误
     * @param array $data
     * @param array $arr
     * @return array
     */
    public function isEmptyRet(array $data, array $arr): array
    {
        foreach ($arr as $k => $v) {
            if (empty(isset($data["$v"]) ? $data["$v"] : '')) {
                \DtApp\ThinkLibrary\facade\Returns::jsonError('请检查参数', 102);
            }
        }
        return $data;
    }

    /**
     * 判断是否为GET方式
     * @return bool
     */
    public function isGet(): bool
    {
        return request()->isGet();
    }

    /**
     * 判断是否为POST方式
     * @return bool
     */
    public function isPost(): bool
    {
        return request()->isPost();
    }

    /**
     * 判断是否为PUT方式
     * @return boolean
     */
    public function isPut(): bool
    {
        return request()->isPut();
    }

    /**
     * 判断是否为DELETE方式
     * @return boolean
     */
    public function isDelete(): bool
    {
        return request()->isDelete();
    }

    /**
     * 判断是否为Ajax方式
     * @return bool
     */
    public function isAjax(): bool
    {
        return request()->isAjax();
    }

    /**
     * 判断是否为移动端访问
     * @return bool
     */
    public function isMobile(): bool
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        //找不到为flase,否则为true
        if (isset($_SERVER['HTTP_VIA'])) {
            return stristr(request()->server('HTTP_VIA'), "wap") ? true : false;
        }
        //判断手机发送的客户端标志
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = [
                'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp',
                'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu',
                'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi',
                'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile', 'alipay'
            ];
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower(request()->server('HTTP_USER_AGENT')))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos(request()->server('HTTP_ACCEPT'), 'vnd.wap.wml') !== false) && (strpos(request()->server('HTTP_ACCEPT'), 'text/html') === false || (strpos(request()->server('HTTP_ACCEPT'), 'vnd.wap.wml') < strpos(request()->server('HTTP_ACCEPT'), 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 判断是否微信内置浏览器访问
     * @return bool
     */
    public function isWeiXin(): bool
    {
        if (strpos(request()->server('HTTP_USER_AGENT'), 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否为微信小程序访问
     * @return bool
     */
    public function isWeiXinMp(): bool
    {
        if (strpos(request()->server('HTTP_USER_AGENT'), 'miniProgram') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否支付宝内置浏览器访问
     * @return bool
     */
    public function isAliPay(): bool
    {
        if (strpos(request()->server('HTTP_USER_AGENT'), 'Alipay') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否QQ内置浏览器访问
     * @return bool
     */
    public function isQQ(): bool
    {
        if (strpos(request()->server('HTTP_USER_AGENT'), 'QQ') !== false) {
            if (strpos(request()->server('HTTP_USER_AGENT'), '_SQ_') !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * 判断是否QQ浏览器访问
     * @return bool
     */
    public function isQQBrowser(): bool
    {
        if (strpos(request()->server('HTTP_USER_AGENT'), 'QQ') !== false) {
            if (strpos(request()->server('HTTP_USER_AGENT'), '_SQ_') !== false) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取客户端类型
     * @return string
     */
    public function getDeviceType()
    {
        $agent = strtolower(request()->server('HTTP_USER_AGENT'));
        if (strpos($agent, 'iphone') || strpos($agent, 'ipad') || strpos($agent, 'android')) {
            $type = 'mobile';
        } else {
            $type = 'computer';
        }
        return $type;
    }

    /**
     * 获取手机设备类型
     * @return string
     */
    public function getMobileType()
    {
        $agent = strtolower(request()->server('HTTP_USER_AGENT'));
        $type = 'other';
        if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
            $type = 'ios';
        }
        if (strpos($agent, 'android')) {
            $type = 'android';
        }
        return $type;
    }

    /**
     * 获取域名地址
     * @return string
     */
    public function getWebsiteAddress(): string
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        return $http_type . $_SERVER['HTTP_HOST'] . "/";
    }
}
