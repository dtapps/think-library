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

use DtApp\ThinkLibrary\exception\CurlException;
use DtApp\ThinkLibrary\Service;
use DtApp\ThinkLibrary\service\Curl\BtService;

/**
 * 宝塔Api
 * Class ApiService
 * @package DtApp\ThinkLibrary\service\bt
 */
class ApiService extends Service
{
    private $url = '';
    private $page = 1;
    private $limit = 15;
    private $order = 'id desc';
    private $where = [];
    private $contents;
    private $backtrack;
    private $key;
    private $panel;

    public function key(string $key)
    {
        $this->key = $key;
        return $this;
    }

    public function panel(string $panel)
    {
        $this->panel = $panel;
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
        if (empty($start_time)) $start_time = strtotime(date('Y-m-d'));
        if (empty($end_time)) $end_time = time();
        $this->url = "/ajax?action={$type}&start={$start_time}&end={$end_time}";
        return $this;
    }

    /**
     * 获取网站列表
     * @return mixed
     */
    public function getSites()
    {
        $this->url = $this->mimes('GetDataList');
        $this->where['type'] = 'sites';
        return $this;
    }

    /**
     * 获取数据库列表
     * @return mixed
     */
    public function getDatabases()
    {
        $this->url = $this->mimes('getData');
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
        $this->url = $this->mimes('getData');
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
        $this->url = $this->mimes('getData');
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
        $this->url = $this->mimes('get_settings');
        return $this;
    }

    /**
     * 获取网站列表
     * @return mixed
     */
    public function getCronTabs()
    {
        $this->url = $this->mimes('getData');
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
        $this->url = $this->mimes('get_site_types');
        return $this;
    }

    /**
     * 获取软件列表
     * @return mixed
     */
    public function getSoFts()
    {
        $this->url = $this->mimes('get_soft_list');
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
        $this->url = $this->mimes('GetDiskInfo');
        return $this;
    }

    /**
     * 获取信息系统
     * @return mixed
     */
    public function getSystemTotal()
    {
        $this->url = $this->mimes('GetSystemTotal');
        return $this;
    }

    /**
     * 获取用户信息
     * @return mixed
     */
    public function getUserInfo()
    {
        $this->url = $this->mimes('GetUserInfo');
        return $this;
    }

    /**
     * 获取网络信息
     * @return mixed
     */
    public function getNetWork()
    {
        $this->url = $this->mimes('GetNetWork');
        return $this;
    }

    /**
     * 获取插件信息
     * @return mixed
     */
    public function getPlugin()
    {
        $this->url = $this->mimes('get_index_list');
        return $this;
    }

    /**
     * 获取软件信息
     * @return mixed
     */
    public function getSoft()
    {
        $this->url = $this->mimes('get_soft_list');
        return $this;
    }

    /**
     * 获取更新信息
     * @return mixed
     */
    public function getUpdatePanel()
    {
        $this->url = $this->mimes('UpdatePanel');
        return $this;
    }

    /**
     * 当前页码
     * @param int $is
     * @return ApiService
     */
    public function page(int $is = 1)
    {
        $this->page = $is;
        return $this;
    }

    /**
     * 返回数量
     * @param int $is
     * @return ApiService
     */
    public function limit(int $is = 15)
    {
        $this->limit = $is;
        return $this;
    }

    /**
     * 排序
     * @param string $ss
     * @return $this
     */
    public function order(string $ss = 'id desc')
    {
        $this->order = $ss;
        return $this;
    }

    /**
     * 查询条件
     * @param array $array
     * @return ApiService
     */
    public function where($array = [])
    {
        $this->where = $array;
        return $this;
    }

    /**
     * 获取数据和总数
     */
    private function getDataWithOrderOpt()
    {
        $this->backtrack['data'] = $this->contents['data'];
        $this->backtrack['orderOpt'] = $this->contents['orderOpt'];
        return $this;
    }

    /**
     * 获取数据和总数
     */
    private function getDataWithCount()
    {
        if (empty($this->contents['data'])) $this->contents['data'] = [];
        if (!is_array($this->contents['data'])) $this->contents['data'] = [];
        $this->backtrack['data'] = $this->contents;
        if (empty($this->contents['page'])) $this->contents['page'] = 0;
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
     * @throws CurlException
     */
    private function getHttp()
    {
        //请求面板接口
        $this->contents = $this->HttpPostCookie($this->url, $this->where);
        return $this;
    }

    /**
     * 返回Array
     * @return array|mixed
     * @throws CurlException
     */
    public function toArray()
    {
        $this->getHttp();
        switch ($this->where['type']) {
            case 'sites':
                $this->getDataWithOrderOpt();
                break;
            default:
                $this->getDataWithCount();
                break;
        }
        if (empty($this->backtrack)) return [];
        if (is_array($this->backtrack)) return $this->backtrack;
        return json_decode($this->backtrack, true);
    }

    /**
     * 获取文件的信息
     * @param $name
     * @return string
     */
    private function mimes($name): string
    {
        $mimes = include __DIR__ . '/bin/mimes.php';
        if (isset($mimes[$name])) return '/' . $mimes[$name];
        return '';
    }

    /**
     * 发起POST请求
     * @param string $url 网址
     * @param array $data 数据
     * @param bool $is_json 是否返回Json格式
     * @return bool|mixed|string
     * @throws CurlException
     */
    protected function HttpPostCookie(string $url, array $data = [], bool $is_json = true)
    {
        $config = [
            'bt_panel' => $this->panel,
            'bt_key' => $this->key
        ];
        //定义cookie保存位置
        $file = app()->getRootPath() . 'runtime/dtapp/bt/cookie/';
        $cookie_file = $file . md5($this->panel) . '.cookie';
        is_dir($file) or mkdir($file, 0777, true);
        if (!file_exists($cookie_file)) {
            $fp = fopen($cookie_file, 'w+');
            fclose($fp);
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
        if (empty($count)) return 0;
        return $count;
    }
}
