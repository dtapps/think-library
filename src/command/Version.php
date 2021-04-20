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

namespace DtApp\ThinkLibrary\command;

use think\App;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Version extends Command
{
    protected function configure()
    {
        $this->setName('dta:version');
        $this->setDescription("Query ThinkPHP framework version");
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('ThinkPHP ' . App::VERSION);
    }
}
