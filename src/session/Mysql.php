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

namespace DtApp\ThinkLibrary\session;

use think\contract\SessionHandlerInterface;
use think\Exception;
use think\facade\Config;
use think\facade\Db;

/**
 *
 * Class Mysql
 * @package DtApp\ThinkLibrary\session
 */
class Mysql implements SessionHandlerInterface
{
    protected $handler = null;
    protected $table_name = null;
    protected $config = [
        'session_expire' => 3600,           // Session有效期 单位：秒
        'session_prefix' => 'think_',       // Session前缀
        'table_name' => 'session',      // 表名（不包含表前缀）
    ];

    protected $database = [
        'type' => 'mysql',        // 数据库类型
        'hostname' => '127.0.0.1',    // 服务器地址
        'database' => '',             // 数据库名
        'username' => 'root',         // 用户名
        'password' => '',             // 密码
        'hostport' => '3306',         // 端口
        'prefix' => '',             // 表前缀
        'charset' => 'utf8',         // 数据库编码
        'debug' => true,           // 数据库调试模式
    ];

    /**
     * Mysql constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct($config = [])
    {
        // 获取数据库配置
        if (isset($config['database']) && !empty($config['database'])) {
            if (is_array($config['database'])) {
                $database = $config['database'];
            } elseif (is_string($config['database'])) {
                $database = Config::get($config['database']);
            } else {
                throw new Exception('session error:database');
            }
            unset($config['database']);
        } else {
            // 使用默认的数据库配置
            $database = Config::get('database');
        }

        $this->config = array_merge($this->config, $config);
        $this->database = array_merge($this->database, $database);


        // 判断数据库配置是否可用
        if(empty($this->database)){
            throw new Exception('session error:database empty');
        }
        $this->handler = Db::connect($this->database);
        $this->table_name = $this->database['prefix'] . $this->config['table_name'];
        return true;
    }

    /**
     * 读取Session
     * @param string $sessionId
     * @return string
     */
    public function read(string $sessionId): string
    {
        $where = [
            'session_id'        => $this->config['session_prefix'] . $sessionId,
            'session_expire'    => time()
        ];
        $sql = 'SELECT session_data FROM ' . $this->table_name . ' WHERE session_id = :session_id AND session_expire > :session_expire';
        $result = $this->handler->query($sql, $where);
        if(!empty($result)){
            return $result[0]['session_data'];
        }
        return '';
    }

    /**
     * 写入Session
     * @param string $sessionId
     * @return bool
     */
    public function delete(string $sessionId): bool
    {
        $where = [
            'session_id' => $this->config['session_prefix'] . $sessionId
        ];
        $sql = 'DELETE FROM ' . $this->table_name . ' WHERE session_id = :session_id';
        $result = $this->handler->execute($sql, $where);
        return $result ? true : false;
    }

    /**
     * 写入Session
     * @param string $sessionId
     * @param string $data
     * @return bool
     */
    public function write(string $sessionId, string $data): bool
    {
        $params = [
            'session_id'        => $this->config['session_prefix'] . $sessionId,
            'session_expire'    => $this->config['session_expire'] + time(),
            'session_data'      => $data
        ];
        $sql = 'REPLACE INTO ' . $this->table_name . ' (session_id, session_expire, session_data) VALUES (:session_id, :session_expire, :session_data)';
        $result = $this->handler->execute($sql, $params);
        return $result ? true : false;
    }

    /**
     * Session 垃圾回收
     * @param $sessMaxLifeTime
     * @return mixed
     */
    public function gc($sessMaxLifeTime)
    {
        $where = [
            'session_expire' => time()
        ];
        $sql = 'DELETE FROM ' . $this->table_name . ' WHERE session_expire < :session_expire';
        return $this->handler->execute($sql, $where);
    }
}
