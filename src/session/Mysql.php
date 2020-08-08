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

namespace DtApp\ThinkLibrary\session;

use DtApp\ThinkLibrary\facade\Times;
use think\contract\SessionHandlerInterface;
use think\facade\Db;


/**
 * Session保存在MySQL驱动
 * Class Mysql
 * @package DtApp\ThinkLibrary\session
 */
class Mysql implements SessionHandlerInterface
{
    /**
     * 表名
     * @var string
     */
    protected $table_name = 'think_session';

    /**
     * session_expire Session有效期 单位：秒
     * session_prefix Session前缀
     * @var array
     */
    protected $config = [
        'session_expire' => 1800,
        'session_prefix' => 'think_'
    ];

    /**
     * read方法是在调用Session::start()的时候执行，并且只会执行一次。
     * @param string $sessionId
     * @return string
     */
    public function read(string $sessionId): string
    {
        return (string)Db::table($this->table_name)
            ->where('session_id', $this->config['session_prefix'] . $sessionId)
            ->whereTime('session_expire', '>=', time())
            ->order('session_expire desc')
            ->value('session_data', '');
    }

    /**
     * delete方法是在销毁会话的时候执行（调用Session::destroy()方法）。
     * @param string $sessionId
     * @return bool
     * @throws \think\db\exception\DbException
     */
    public function delete(string $sessionId): bool
    {
        $result = Db::table($this->table_name)
            ->where('session_id', $this->config['session_prefix'] . $sessionId)
            ->delete();
        return $result ? true : false;
    }

    /**
     * write方法是在本地化会话数据的时候执行（调用Session::save()方法），系统会在每次请求结束的时候自动执行。
     * @param string $sessionId
     * @param string $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function write(string $sessionId, string $data): bool
    {
        $get = Db::table($this->table_name)
            ->where('session_id', $this->config['session_prefix'] . $sessionId)
            ->whereTime('session_expire', '>=', time())
            ->field('id')
            ->find();
        if (empty($get)) {
            $params = [
                'session_id' => $this->config['session_prefix'] . $sessionId,
                'session_expire' => Times::dateRear("Y-m-d H:i:s", $this->config['session_expire']),
                'session_data' => $data
            ];
            $result = Db::table($this->table_name)
                ->insert($params);
            return $result ? true : false;
        } else {
            $params = [
                'session_expire' => Times::dateRear("Y-m-d H:i:s", $this->config['session_expire']),
                'session_data' => $data
            ];
            $result = Db::table($this->table_name)
                ->where('id', $get['id'])
                ->update($params);
            return $result ? true : false;
        }
    }
}
