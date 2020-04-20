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
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
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
    protected $table_name = 'think_session'; // 表名
    protected $config = [
        'session_expire' => 3600,           // Session有效期 单位：秒
        'session_prefix' => 'think_',       // Session前缀
    ];

    /**
     * read方法是在调用Session::start()的时候执行，并且只会执行一次。
     * @param string $sessionId
     * @return string
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function read(string $sessionId): string
    {
        $where = [
            'session_id' => $this->config['session_prefix'] . $sessionId,
            'session_expire' => time()
        ];
        $result = Db::table($this->table_name)
            ->where($where)
            ->find();
        if (!empty($result)) {
            return $result['session_data'];
        }
        return '';
    }

    /**
     * delete方法是在销毁会话的时候执行（调用Session::destroy()方法）。
     * @param string $sessionId
     * @return bool
     * @throws DbException
     */
    public function delete(string $sessionId): bool
    {
        $where = [
            'session_id' => $this->config['session_prefix'] . $sessionId
        ];
        $result = Db::table($this->table_name)
            ->where($where)->delete();
        return $result ? true : false;
    }

    /**
     * write方法是在本地化会话数据的时候执行（调用Session::save()方法），系统会在每次请求结束的时候自动执行。
     * @param string $sessionId
     * @param string $data
     * @return bool
     */
    public function write(string $sessionId, string $data): bool
    {
        $params = [
            'session_id' => $this->config['session_prefix'] . $sessionId,
            'session_expire' => $this->config['session_expire'] + time(),
            'session_data' => $data
        ];
        $result = Db::table($this->table_name)
            ->insert($params);
        return $result ? true : false;
    }
}
