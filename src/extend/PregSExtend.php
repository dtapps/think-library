<?php

namespace DtApp\ThinkLibrary\extend;

/**
 * 验证管理类
 * Class PregSExtend
 * @package DtApp\ThinkLibrary\extend
 */
class PregSExtend
{
    /**
     * 验证手机号码
     * @access public
     * @param $mobile
     * @return bool
     */
    public static function isIphone($mobile): bool
    {
        if (preg_match('/^[1]([3-9])[0-9]{9}$/', $mobile)) {
            return true;
        }
        return false;
    }

    /**
     * 严谨验证手机号码
     * 移动：134 135 136 137 138 139 147 150 151 152 157 158 159 178 182 183 184 187 188 198
     * 联通：130 131 132 145 155 156 166 171 175 176 185 186
     * 电信：133 149 153 173 177 180 181 189 199
     * 虚拟运营商: 170 195
     * @param $mobile
     * @return bool
     */
    public static function isIphoneAll($mobile): bool
    {
        if (preg_match('/^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,5,8-9]))[0-9]{8}$/', $mobile)) {
            return true;
        }
        return false;
    }

    /**
     * 验证电话号码
     * @access public
     * @param $tel
     * @return bool
     */
    public static function isTel($tel): bool
    {
        if (preg_match("/^(\(\d{3,4}\)|\d{3,4}-)?\d{7,8}$/", $tel)) {
            return true;
        }
        return false;
    }

    /**
     * 验证身份证号（15位或18位数字）
     * @access public
     * @param int $id 身份证号码
     * @return bool
     */
    public static function isIdCard(int $id): bool
    {
        if (preg_match("/^\d{15}|\d{18}$/", $id)) {
            return true;
        }
        return false;
    }

    /**
     * 验证是否是数字(这里小数点会认为是字符)
     * @access public
     * @param $digit
     * @return bool
     */
    public static function isDigit($digit): bool
    {
        if (preg_match("/^\d*$/", $digit)) {
            return true;
        }
        return false;
    }

    /**
     * 验证是否是数字(可带小数点的数字)
     * @access public
     * @param $num
     * @return bool
     */
    public static function isNum($num): bool
    {
        if (is_numeric($num)) {
            return true;
        }
        return false;
    }

    /**
     * 验证由数字、26个英文字母或者下划线组成的字符串
     * @access public
     * @param $str
     * @return bool
     */
    public static function isStr($str): bool
    {
        if (preg_match("/^\w+$/", $str)) {
            return true;
        }
        return false;
    }

    /**
     * 验证用户密码(以字母开头，长度在6-18之间，只能包含字符、数字和下划线)
     * @access public
     * @param $str
     * @return bool
     */
    public static function isPassword($str): bool
    {
        if (preg_match("/^[a-zA-Z]\w{5,17}$/", $str)) {
            return true;
        }
        return false;
    }

    /**
     * 验证汉字
     * @access public
     * @param $str
     * @return bool
     */
    public static function isChinese($str): bool
    {
        if (preg_match("/^[\u4e00-\u9fa5],{0,}$/", $str)) {
            return true;
        }
        return false;
    }

    /**
     * 验证Email地址
     * @access public
     * @param $email
     * @return bool
     */
    public static function isEmail($email): bool
    {
        if (preg_match("/^\w+[-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $email)) {
            return true;
        }
        return false;
    }

    /**
     * 验证网址URL
     * @access public
     * @param $url
     * @return bool
     */
    public static function isLink($url): bool
    {
        if (preg_match("/http|https:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is", $url)) {
            return true;
        }
        return false;
    }

    /**
     * 腾讯QQ号
     * @access public
     * @param $qq
     * @return bool
     */
    public static function isQq($qq): bool
    {
        if (preg_match("/^[1-9][0-9]{4,}$/", $qq)) {
            return true;
        }
        return false;
    }

    /**
     * 验证IP地址
     * @access public
     * @param $ip
     * @return bool
     */
    public static function isIp($ip): bool
    {
        if (preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip)) {
            return true;
        }
        return false;
    }
}