<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2018/12/12
 * Time: 14:04
 */

namespace Ecjia\App\Finance\UserCleanHandlers;

use Ecjia\App\User\UserCleanAbstract;
use RC_Uri;
use RC_DB;
use RC_Api;
use ecjia_admin;

class UserAccountLogClear extends UserCleanAbstract
{

    /**
     * 代号标识
     * @var string
     */
    protected $code = 'user_account_log_clear';

    /**
     * 名称
     * @var string
     */
    protected $name = '会员积分记录';

    /**
     * 排序
     * @var int
     */
    protected $sort = 81;

    /**
     * 数据描述及输出显示内容
     */
    public function handlePrintData()
    {
        $count = $this->handleCount();

        return <<<HTML

<span class="controls-info">账户积分所有的变动记录</span>

HTML;

    }

    /**
     * 获取数据统计条数
     *
     * @return mixed
     */
    public function handleCount()
    {
        $count = RC_DB::table('account_log')->where('user_id', $this->user_id)->count();

        return $count;
    }


    /**
     * 执行清除操作
     *
     * @return mixed
     */
    public function handleClean()
    {
        $result = RC_DB::table('account_log')->where('user_id', $this->user_id)->delete();

        if ($result) {
            $this->handleAdminLog();
        }

        return $result;
    }

    /**
     * 返回操作日志编写
     *
     * @return mixed
     */
    public function handleAdminLog()
    {
        \Ecjia\App\User\Helper::assign_adminlog_content();

        $user_info = RC_Api::api('user', 'user_info', array('user_id' => $this->user_id));

        $user_name = !empty($user_info) ? '用户名是' . $user_info['user_name'] : '用户ID是' . $this->user_id;

        ecjia_admin::admin_log($user_name, 'clean', 'user_account_log');
    }

    /**
     * 是否允许删除
     *
     * @return mixed
     */
    public function handleCanRemove()
    {
        return $this->handleCount() != 0 ? true : false;
    }


}