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
 * 验证管理类
 * Class Pregs
 * @mixin Pregs
 * @package DtApp\ThinkLibrary\helper
 */
class Pregs
{
    /**
     * 验证手机号码
     * @access public
     * @param $mobile
     * @return bool
     */
    public function isIphone($mobile): bool
    {
        if (preg_match('/^[1]([3-9])[0-9]{9}$/', $mobile)) return true;
        return false;
    }

    /**
     * 严谨验证手机号码
     * @access public
     * @param $mobile
     * @return bool
     */
    public function isIphoneAll($mobile): bool
    {
        if (preg_match('/^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/', $mobile)) return true;
        return false;
    }

    /**
     * 验证电话号码
     * @access public
     * @param $tel
     * @return bool
     */
    public function isTel($tel): bool
    {
        if (preg_match("/^(\(\d{3,4}\)|\d{3,4}-)?\d{7,8}$/", $tel)) return true;
        return false;
    }

    /**
     * 验证身份证号（15位或18位数字）
     * @access public
     * @param int $id 身份证号码
     * @return bool
     */
    public function isIdCard($id): bool
    {
        if (preg_match("/^\d{15}|\d{18}$/", $id)) return true;
        return false;
    }

    /**
     * 验证是否是数字(这里小数点会认为是字符)
     * @access public
     * @param $digit
     * @return bool
     */
    public function isDigit($digit): bool
    {
        if (preg_match("/^\d*$/", $digit)) return true;
        return false;
    }

    /**
     * 验证是否是数字(可带小数点的数字)
     * @access public
     * @param $num
     * @return bool
     */
    public function isNum($num): bool
    {
        if (is_numeric($num)) return true;
        return false;
    }

    /**
     * 验证由数字、26个英文字母或者下划线组成的字符串
     * @access public
     * @param $str
     * @return bool
     */
    public function isStr($str): bool
    {
        if (preg_match("/^\w+$/", $str)) return true;
        return false;
    }

    /**
     * 验证用户密码(以字母开头，长度在6-18之间，只能包含字符、数字和下划线)
     * @access public
     * @param $str
     * @return bool
     */
    public function isPassword($str): bool
    {
        if (preg_match("/^[a-zA-Z]\w{5,17}$/", $str)) return true;
        return false;
    }

    /**
     * 验证汉字
     * @access public
     * @param $str
     * @return bool
     */
    public function isChinese($str): bool
    {
        if (preg_match("/^[\u4e00-\u9fa5],{0,}$/", $str)) return true;
        return false;
    }

    /**
     * 验证Email地址
     * @access public
     * @param $email
     * @return bool
     */
    public function isEmail($email): bool
    {
        if (preg_match("/^\w+[-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $email)) return true;
        return false;
    }

    /**
     * 验证网址URL
     * @access public
     * @param $url
     * @return bool
     */
    public function isLink($url): bool
    {
        if (preg_match("/http|https:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is", $url)) return true;
        return false;
    }

    /**
     * 腾讯QQ号
     * @access public
     * @param $qq
     * @return bool
     */
    public function isQq($qq): bool
    {
        if (preg_match("/^[1-9][0-9]{4,}$/", $qq)) return true;
        return false;
    }

    /**
     * 验证IP地址
     * @access public
     * @param $ip
     * @return bool
     */
    public function isIp($ip): bool
    {
        if (preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip)) return true;
        return false;
    }
}
