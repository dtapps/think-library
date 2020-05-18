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

namespace DtApp\ThinkLibrary\cache;

use DtApp\ThinkLibrary\exception\CacheException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;

/**
 * 缓存数据库驱动
 * Class Mysql
 * @package DtApp\ThinkLibrary\cache
 */
class Mysql
{
    private $table = "think_cache";
    private $cache_name;
    private $cache_expire = 0;

    /**
     * 名称
     * @param string $cache_name
     * @return $this
     */
    public function name(string $cache_name)
    {
        $this->cache_name = $cache_name;
        return $this;
    }

    /**
     * 过期时间
     * @param string $cache_expire
     * @return $this
     */
    public function expire(string $cache_expire)
    {
        $this->cache_expire = $cache_expire;
        return $this;
    }

    /**
     * 设置
     * @param $cache_value
     * @return int|string
     * @throws CacheException
     */
    public function set($cache_value)
    {
        if (empty($this->cache_name)) throw new CacheException("名称未配置");
        return Db::table($this->table)
            ->insert([
                'cache_name' => $this->cache_name,
                'cache_value' => $cache_value,
                'cache_expire' => $this->cache_expire
            ]);
    }

    /**
     * 获取
     * @return string
     * @throws CacheException
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function get()
    {
        if (empty($this->cache_name)) throw new CacheException("名称未配置");
        $cache = Db::table($this->table)
            ->where('cache_name', $this->cache_name)
            ->field('cache_expire,cache_value')
            ->find();
        if (empty($cache['cache_expire'])) return $cache['cache_value'];
        if ($cache['cache_expire'] < time()) return "";
        return $cache['cache_value'];
    }

    /**
     * 删除
     * @return int
     * @throws CacheException
     * @throws DbException
     */
    public function delete()
    {
        if (empty($this->cache_name)) throw new CacheException("名称未配置");
        return Db::table($this->table)
            ->where('cache_name', $this->cache_name)
            ->delete();
    }

    /**
     * 更新
     * @param $cache_value
     * @return int
     * @throws CacheException
     * @throws DbException
     */
    public function update($cache_value)
    {
        if (empty($this->cache_name)) throw new CacheException("名称未配置");
        if (empty($this->cache_expire)) {
            return Db::table($this->table)
                ->where('cache_name', $this->cache_name)
                ->update([
                    'cache_value' => $this->cache_expire,
                ]);
        } else {
            return Db::table($this->table)
                ->where('cache_name', $this->cache_name)
                ->update([
                    'cache_value' => $cache_value,
                    'cache_expire' => $this->cache_expire
                ]);
        }
    }

    /**
     * 自增
     * @param int $int
     * @return int
     * @throws CacheException
     * @throws DbException
     */
    public function inc(int $int = 1)
    {
        $cache_value = (int)$this->get();
        return Db::table($this->table)
            ->where('cache_name', $this->cache_name)
            ->update([
                'cache_value' => $cache_value + $int
            ]);
    }

    /**
     * 自减
     * @param int $int
     * @return int
     * @throws CacheException
     * @throws DbException
     */
    public function dec(int $int = 1)
    {
        $cache_value = (int)$this->get();
        return Db::table($this->table)
            ->where('cache_name', $this->cache_name)
            ->update([
                'cache_value' => $cache_value - $int
            ]);
    }
}
