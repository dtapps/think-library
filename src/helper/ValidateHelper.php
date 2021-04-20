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

namespace DtApp\ThinkLibrary\helper;

use DtApp\ThinkLibrary\Helper;
use think\Validate;

class ValidateHelper extends Helper
{
    /**
     * 快捷输入并验证（ 支持 规则 # 别名 ）
     *
     *  age.require => message // 最大值限定
     *  age.between:1,120 => message // 范围限定
     *  name.require => message // 必填内容
     *  name.default => 100 // 获取并设置默认值
     *  region.value => value // 固定字段数值内容
     *  更多规则参照 ThinkPHP 官方的验证类
     *
     * @param array $rules 验证规则（ 验证信息数组 ）
     * @param string $input 输入内容 ( post. 或 get. )
     * @param callable|null $callable 异常处理操作
     * @return array
     */
    public function init(array $rules, $input = '', ?callable $callable = null): array
    {
        if (is_string($input)) {
            $type = trim($input, '.') ?: 'request';
            $input = $this->app->request->$type();
        }
        [$data, $rule, $info] = [[], [], []];
        foreach ($rules as $name => $message) {
            if (is_numeric($name)) {
                [$name, $alias] = explode('#', $message . '#');
                $data[$name] = $input[($alias ?: $name)] ?? null;
            } elseif (strpos($name, '.') === false) {
                $data[$name] = $message;
            } elseif (preg_match('|^(.*?)\.(.*?)#(.*?)#?$|', $name . '#', $matches)) {
                [, $_key, $_rule, $alias] = $matches;
                if (in_array($_rule, ['value', 'default'])) {
                    if ($_rule === 'value') {
                        $data[$_key] = $message;
                    } elseif ($_rule === 'default') {
                        $data[$_key] = $input[($alias ?: $_key)] ?? $message;
                    }
                } else {
                    $info[explode(':', $name)[0]] = $message;
                    $data[$_key] = $data[$_key] ?? ($input[($alias ?: $_key)] ?? null);
                    $rule[$_key] = isset($rule[$_key]) ? ($rule[$_key] . '|' . $_rule) : $_rule;
                }
            }
        }
        $validate = new Validate();
        if ($validate->rule($rule)->message($info)->check($data)) {
            return $data;
        }

        if (is_callable($callable)) {
            return $callable($validate->getError());
        }

        $this->class->error($validate->getError());
    }
}