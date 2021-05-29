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

namespace DtApp\ThinkLibrary\service\bt;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\facade\Files;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\curl\BtService;

/**
 * 宝塔Api
 * https://www.bt.cn/
 * Class ApiService
 * @package DtApp\ThinkLibrary\service\bt
 */
class ApiService extends Service
{
    /**
     * @var string
     */
    private $url = '';

    /**
     * @var int
     */
    private $page = 1;

    /**
     * @var int
     */
    private $limit = 15;

    /**
     * @var string
     */
    private $order = 'id desc';

    /**
     * @var array
     */
    private $where = [];

    /**
     * @var
     */
    private $contents, $backtrack, $key, $panel;

    /**
     * @param string $key
     * @return $this
     */
    public function key(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $panel
     * @return $this
     */
    public function panel(string $panel)
    {
        $this->panel = $panel;
        return $this;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig(): self
    {
        $this->key = config('dtapp.bt.key');
        $this->panel = config('dtapp.bt.panel');
        return $this;
    }

    /**
     * 获取监控信息
     * @param string $type 类型 GetCpuIo = CPU信息/内存 GetDiskIo = 磁盘IO GetNetWorkIo = 网络IO
     * @param int $start_time 开始时间
     * @param int $end_time 结束时间
     * @return mixed
     */
    public function getCpuIoInfo($type = 'GetCpuIo', $start_time = 0, $end_time = 0)
    {
        if (empty($start_time)) {
            $start_time = strtotime(date('Y-m-d'));
        }
        if (empty($end_time)) {
            $end_time = time();
        }
        $this->url = "/ajax?action={$type}&start={$start_time}&end={$end_time}";
        return $this;
    }

    /**
     * 获取网站列表
     * @return mixed
     */
    public function getSites()
    {
        $this->url = "crontab?action=GetDataList";
        $this->where['type'] = 'sites';
        return $this;
    }

    /**
     * 获取数据库列表
     * @return mixed
     */
    public function getDatabases()
    {
        $this->url = 'data?action=getData';
        $this->where['tojs'] = 'database.get_list';
        $this->where['table'] = 'databases';
        $this->where['limit'] = $this->limit;
        $this->where['p'] = $this->page;
        $this->where['order'] = $this->order;
        return $this;
    }

    /**
     * 获取防火墙
     * @return mixed
     */
    public function getFirewalls()
    {
        $this->url = 'data?action=getData';
        $this->where['tojs'] = 'firewall.get_list';
        $this->where['table'] = 'firewall';
        $this->where['limit'] = $this->limit;
        $this->where['p'] = $this->page;
        $this->where['order'] = $this->order;
        return $this;
    }

    /**
     * 获取面板日志
     * @return mixed
     */
    public function getLogs()
    {
        $this->url = 'data?action=getData';
        $this->where['tojs'] = 'firewall.get_log_list';
        $this->where['table'] = 'logs';
        $this->where['limit'] = $this->limit;
        $this->where['p'] = $this->page;
        $this->where['order'] = $this->order;
        return $this;
    }

    /**
     * 获取消息通道
     * @return mixed
     */
    public function getNews()
    {
        $this->url = 'config?action=get_settings';
        return $this;
    }

    /**
     * 获取网站列表
     * @return mixed
     */
    public function getCronTabs()
    {
        $this->url = 'data?action=getData';
        $this->where['tojs'] = 'site.get_list';
        $this->where['table'] = 'sites';
        $this->where['limit'] = $this->limit;
        $this->where['p'] = $this->page;
        $this->where['order'] = $this->order;
        return $this;
    }

    /**
     * 获取网站分类
     * @return mixed
     */
    public function getTypes()
    {
        $this->url = 'site?action=get_site_types';
        return $this;
    }

    /**
     * 获取软件列表
     * @return mixed
     */
    public function getSoFts()
    {
        $this->url = 'plugin?action=get_soft_list';
        $this->where['p'] = $this->page;
        $this->where['tojs'] = 'soft.get_list';
        return $this;
    }

    /**
     * 获取硬盘信息
     * @return mixed
     */
    public function getDiskInfo()
    {
        $this->url = 'system?action=GetDiskInfo';
        return $this;
    }

    /**
     * 获取信息系统
     * @return mixed
     */
    public function getSystemTotal()
    {
        $this->url = 'system?action=GetSystemTotal';
        return $this;
    }

    /**
     * 获取用户信息
     * @return mixed
     */
    public function getUserInfo()
    {
        $this->url = 'ssl?action=GetUserInfo';
        return $this;
    }

    /**
     * 获取网络信息
     * @return mixed
     */
    public function getNetWork()
    {
        $this->url = 'system?action=GetNetWork';
        return $this;
    }

    /**
     * 获取插件信息
     * @return mixed
     */
    public function getPlugin()
    {
        $this->url = 'plugin?action=get_index_list';
        return $this;
    }

    /**
     * 获取软件信息
     * @return mixed
     */
    public function getSoft()
    {
        $this->url = 'plugin?action=get_soft_list';
        return $this;
    }

    /**
     * 获取更新信息
     * @return mixed
     */
    public function getUpdatePanel()
    {
        $this->url = 'ajax?action=UpdatePanel';
        return $this;
    }

    /**
     * 当前页码
     * @param int $is
     * @return $this
     */
    public function page(int $is = 1): self
    {
        $this->page = $is;
        return $this;
    }

    /**
     * 返回数量
     * @param int $is
     * @return $this
     */
    public function limit(int $is = 15): self
    {
        $this->limit = $is;
        return $this;
    }

    /**
     * 排序
     * @param string $ss
     * @return $this
     */
    public function order(string $ss = 'id desc'): self
    {
        $this->order = $ss;
        return $this;
    }

    /**
     * 查询条件
     * @param array $array
     * @return ApiService
     */
    public function where($array = []): ApiService
    {
        $this->where = $array;
        return $this;
    }

    /**
     * 获取数据和总数
     * @return $this
     */
    private function getDataWithOrderOpt(): self
    {
        $this->backtrack['data'] = $this->contents['data'];
        $this->backtrack['orderOpt'] = $this->contents['orderOpt'];
        return $this;
    }

    /**
     * 获取数据和总数
     * @return $this
     */
    private function getDataWithCount(): self
    {
        if (empty($this->contents['data'])) {
            $this->contents['data'] = [];
        }
        if (!is_array($this->contents['data'])) {
            $this->contents['data'] = [];
        }
        $this->backtrack['data'] = $this->contents;
        if (empty($this->contents['page'])) {
            $this->contents['page'] = 0;
        }
        $this->backtrack['count'] = $this->getCountData($this->contents['page']);
        return $this;
    }

    /**
     * 获取数据
     * @return $this
     */
    private function getData()
    {
        $this->backtrack['data'] = $this->contents;
        return $this;
    }

    /**
     * 发起网络请求
     * @return $this
     * @throws DtaException
     */
    private function getHttp(): self
    {
        //请求面板接口
        $this->contents = $this->HttpPostCookie($this->url, $this->where);
        return $this;
    }

    /**
     * 返回Array
     * @return array|mixed
     * @throws DtaException
     */
    public function toArray()
    {
        $this->getHttp();
        if ($this->where['type'] === 'sites') {
            $this->getDataWithOrderOpt();
        } else {
            $this->getDataWithCount();
        }
        if (empty($this->backtrack)) {
            return [];
        }
        if (is_array($this->backtrack)) {
            return $this->backtrack;
        }
        return json_decode($this->backtrack, true);
    }

    /**
     * 发起POST请求
     * @param string $url 网址
     * @param array $data 数据
     * @param bool $is_json 是否返回Json格式
     * @return bool|mixed|string
     * @throws DtaException
     */
    protected function HttpPostCookie(string $url, array $data = [], bool $is_json = true)
    {
        if (empty($this->panel)) {
            $this->getConfig();
        }
        if (empty($this->panel)) {
            throw new DtaException('请检查panel参数');
        }
        //定义cookie保存位置
        $file = app()->getRootPath() . 'runtime/dtapp/bt/cookie/';
        $cookie_file = $file . md5($this->panel) . '.cookie';
        if (empty(Files::judgeContents($file)) && !mkdir($file, 0777, true) && !is_dir($file)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $file));
        }
        if (!file_exists($cookie_file)) {
            $fp = fopen($cookie_file, 'wb+');
            fclose($fp);
        }
        if (empty($this->key)) {
            $this->getConfig();
        }
        if (empty($this->key)) {
            throw new DtaException('请检查key参数');
        }
        return BtService::instance()
            ->panel($this->panel)
            ->key($this->key)
            ->url($url)
            ->cookie($cookie_file)
            ->data($data)
            ->toArray($is_json);
    }

    /**
     * 获取总数
     * @param string $str
     * @return false|int|string
     */
    protected function getCountData(string $str)
    {
        $start = strpos($str, "共");
        $end = strpos($str, "条数据");
        $count = substr($str, $start + 3, $end - $start - 3);
        if (empty($count)) {
            return 0;
        }
        return $count;
    }
}
