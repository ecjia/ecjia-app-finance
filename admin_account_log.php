<?php
//
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
use Ecjia\App\Finance\Notifications\UserAccountChange;

defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 会员帐户变动记录
 */
class admin_account_log extends ecjia_admin
{
    public function __construct()
    {
        parent::__construct();

        RC_Loader::load_app_func('admin_user', 'finance');

        Ecjia\App\Finance\Helper::assign_adminlog_content();

        /* 加载所需js */
        RC_Script::enqueue_script('jquery-validate');
        RC_Script::enqueue_script('jquery-form');
        RC_Script::enqueue_script('jquery-chosen');
        RC_Style::enqueue_style('chosen');
        RC_Script::enqueue_script('jquery-peity');

        RC_Script::enqueue_script('bootstrap-datepicker', RC_Uri::admin_url('statics/lib/datepicker/bootstrap-datepicker.min.js'));
        RC_Style::enqueue_style('datepicker', RC_Uri::admin_url('statics/lib/datepicker/datepicker.css'));

        RC_Script::enqueue_script('account_log', RC_App::apps_url('statics/js/account_log.js', __FILE__));
        RC_Style::enqueue_style('admin_account_log', RC_App::apps_url('statics/css/admin_account_manage.css', __FILE__), array());

        $account_log_jslang = array(
            'change_desc_required' => RC_Lang::get('user::account_log.js_languages.no_change_desc'),
        );
        RC_Script::localize_script('account_log', 'account_log_jslang', $account_log_jslang);

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('user::users.user_list'), RC_Uri::url('user/admin/init')));
    }

    /**
     * 账户明细列表
     */
    public function init()
    {
        $this->admin_priv('account_manage');

        $user_id      = intval($_GET['user_id']);
        $account_type = trim($_GET['account_type']);

        $this->assign('form_action', RC_Uri::url('finance/admin_account_log/init', array('account_type' => $account_type, 'user_id' => $user_id)));
        $this->assign('back_link', array('text' => '会员列表', 'href' => RC_Uri::url('user/admin/init')));

        $nav_here = '会员账户变动明细';
        if ($account_type == 'user_money') {
            $nav_here = '查看余额变动';

            $link1 = array('text' => '充值', 'href' => RC_Uri::url('finance/admin_account/add', array('user_id' => $user_id)), 'i' => 'fontello-icon-dollar');
            $link2 = array('text' => '提现', 'href' => RC_Uri::url('withdraw/admin/edit', array('user_id' => $user_id)), 'i' => 'fontello-icon-dollar');

            $second_heading = '资金明细';

        } elseif ($account_type == 'pay_points') {
            $nav_here = '查看积分变动';

            $link1 = array('text' => '增加积分', 'href' => RC_Uri::url('finance/admin_account_log/add_pay_points', array('user_id' => $user_id)), 'i' => 'fontello-icon-plus');
            $link2 = array('text' => '减少积分', 'href' => RC_Uri::url('finance/admin_account_log/minus_pay_points', array('user_id' => $user_id)), 'i' => 'fontello-icon-minus');

            $second_heading = '积分明细';

        } elseif ($account_type == 'rank_points') {
            $nav_here = '查看成长值变动';

            $link1 = array('text' => '增加成长值', 'href' => RC_Uri::url('finance/admin_account_log/add_rank_points', array('user_id' => $user_id)), 'i' => 'fontello-icon-plus');
            $link2 = array('text' => '减少成长值', 'href' => RC_Uri::url('finance/admin_account_log/minus_rank_points', array('user_id' => $user_id)), 'i' => 'fontello-icon-minus');

            $second_heading = '成长值明细';
        }

        $this->assign('ur_here', $nav_here);
        $this->assign('link1', $link1);
        $this->assign('link2', $link2);
        $this->assign('second_heading', $second_heading);

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($nav_here));

        $user = get_user_info($user_id);
        $this->assign('user', $user);

        $date = array(
            'start_date' => trim($_GET['start_date']),
            'end_date'   => trim($_GET['end_date']),
        );

        $account_list = get_account_log_list($user_id, $account_type, $date);

        $this->assign('account_type', $account_type);
        $this->assign('account_list', $account_list);

        $this->display('account_log_list.dwt');
    }

    /**
     * 调节帐户
     */
    public function edit()
    {
        $this->admin_priv('account_manage');

        ecjia_screen::get_current_screen()->add_help_tab(array(
            'id'      => 'overview',
            'title'   => RC_Lang::get('user::users.overview'),
            'content' => '<p>' . RC_Lang::get('user::users.add_account_log_help') . '</p>',
        ));

        ecjia_screen::get_current_screen()->set_help_sidebar(
            '<p><strong>' . RC_Lang::get('user::users.more_info') . '</strong></p>' .
            '<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:会员列表#.E6.9F.A5.E7.9C.8B.E8.B4.A6.E7.9B.AE.E6.98.8E.E7.BB.86" target="_blank">' . RC_Lang::get('user::users.about_add_account_log') . '</a>') . '</p>'
        );

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('user::account_log.account_change_desc'), RC_Uri::url('finance/admin_account_log/init', 'user_id=' . $user_id)));
        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('user::account_log.add_account')));

        $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
        $user    = get_user_info($user_id);

        /* 显示模板 */
        $this->assign('user', $user);
        $this->assign('ur_here', RC_Lang::get('user::account_log.add_account'));
        $this->assign('action_link', array('href' => RC_Uri::url('finance/admin_account_log/init', array('user_id' => $user_id)), 'text' => RC_Lang::get('user::account_log.account_list')));
        $this->assign('form_action', RC_Uri::url('finance/admin_account_log/update', array('user_id' => $user_id)));

        $this->display('account_log_edit.dwt');
    }

    /**
     * 调节会员账户
     */
    public function update()
    {
        $this->admin_priv('account_manage', ecjia::MSGTYPE_JSON);

        $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
        $user    = get_user_info($user_id);

        if (empty($user)) {
            return $this->showmessage(RC_Lang::get('user::account_log.user_not_exist'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }

        $user_money   = !empty($_POST['user_money']) ? $_POST['user_money'] : 0;
        $frozen_money = !empty($_POST['frozen_money']) ? $_POST['frozen_money'] : 0;
        $rank_points  = !empty($_POST['rank_points']) ? $_POST['rank_points'] : 0;
        $pay_points   = !empty($_POST['pay_points']) ? $_POST['pay_points'] : 0;

        /* 参数验证 */
        if ($user_money < 0 || !is_numeric($user_money) || !isset($user_money)) {
            return $this->showmessage(RC_Lang::get('user::account_log.user_money_error'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }
        if ($frozen_money < 0 || !is_numeric($frozen_money) || !isset($frozen_money)) {
            return $this->showmessage(RC_Lang::get('user::account_log.frozen_money_error'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }
        if ($rank_points < 0 || !is_numeric($rank_points) || !isset($rank_points)) {
            return $this->showmessage(RC_Lang::get('user::account_log.rank_points_error'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }
        if ($pay_points < 0 || !is_numeric($pay_points) || !isset($pay_points)) {
            return $this->showmessage(RC_Lang::get('user::account_log.pay_points_error'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }

        if ($user_money == 0 && $frozen_money == 0 && $rank_points == 0 && $pay_points == 0) {
            return $this->showmessage(RC_Lang::get('user::account_log.null_msg'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }

        $change_desc  = RC_String::sub_str($_POST['change_desc'], 255, false);
        $user_money   = floatval($_POST['add_sub_user_money']) * abs(floatval($user_money));
        $frozen_money = floatval($_POST['add_sub_frozen_money']) * abs(floatval($frozen_money));
        $rank_points  = floatval($_POST['add_sub_rank_points']) * abs(floatval($rank_points));
        $pay_points   = floatval($_POST['add_sub_pay_points']) * abs(floatval($pay_points));

        if ($_POST['add_sub_user_money'] == 1) {
            $usermoney = '+';
        } else {
            $usermoney = '-';
        }

        if ($_POST['add_sub_frozen_money'] == 1) {
            $frozenmoney = '+';
        } else {
            $frozenmoney = '-';
        }

        if ($_POST['add_sub_rank_points'] == 1) {
            $rankpoints = '+';
        } else {
            $rankpoints = '-';
        }

        if ($_POST['add_sub_pay_points'] == 1) {
            $paypoints = '+';
        } else {
            $paypoints = '-';
        }

        /* 保存 */
        change_account_log($user_id, $user_money, $frozen_money, $rank_points, $pay_points, $change_desc, ACT_ADJUSTING);
        if (!empty($user_money) && $_POST['add_sub_user_money'] == 1) {
            $user_info = RC_DB::table('users')->where('user_id', $user_id)->select('user_name', 'user_money', 'mobile_phone')->first();
            /* 短信告知用户账户变动 */
            if (!empty($user_info['mobile_phone'])) {
                $options = array(
                    'mobile' => $user_info['mobile_phone'],
                    'event'  => 'sms_user_account_change',
                    'value'  => array(
                        'user_name'     => $user_info['user_name'],
                        'amount'        => $user_money,
                        'user_money'    => $user_info['user_money'],
                        'service_phone' => ecjia::config('service_phone'),
                    ),
                );
                RC_Api::api('sms', 'send_event_sms', $options);
            }

            //消息通知
            $orm_user_db = RC_Model::model('orders/orm_users_model');
            $user_ob     = $orm_user_db->find($user_id);

            $user_account_data = array(
                'title' => '充值到账',
                'body'  => '尊敬的' . $user_info['user_name'] . '，充值业务已受理成功，充值金额' . $user_money . '元资金已到账，目前可用资金' . $user_info['user_money'] . '元。',
                'data'  => array(
                    'user_id'              => $user_id,
                    'user_name'            => $user_info['user_name'],
                    'amount'               => $user_money,
                    'formatted_amount'     => price_format($user_money),
                    'user_money'           => $user_info['user_money'],
                    'formatted_user_money' => price_format($user_info['user_money']),
                ),
            );

            $push_account_chenged = new UserAccountChange($user_account_data);
            RC_Notification::send($user_ob, $push_account_chenged);
        }

        ecjia_admin::admin_log($user['user_name'] . '，' . RC_Lang::get('user::account_log.usermoney') . $usermoney . $user_money .
            RC_Lang::get('user::account_log.frozenmoney') . $frozenmoney . $frozen_money .
            RC_Lang::get('user::account_log.rankpoints') . $rankpoints . $rank_points .
            RC_Lang::get('user::account_log.paypoints') . $paypoints . $pay_points . '，' .
            RC_Lang::get('user::account_log.change_desc') . $change_desc, 'edit', 'usermoney');

        $links[] = array('href' => RC_Uri::url('finance/admin_account_log/init', array('user_id' => $user_id)), 'text' => RC_Lang::get('user::account_log.account_list'));
        return $this->showmessage(RC_Lang::get('user::account_log.log_account_change_ok'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('finance/admin_account_log/edit', array('user_id' => $user_id)), 'links' => $links));
    }
}

// end
