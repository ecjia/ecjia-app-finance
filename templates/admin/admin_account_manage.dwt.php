<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
    ecjia.admin.account_manage.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->

<div class="alert alert-info">
    <a class="close" data-dismiss="alert">×</a>
    <p><strong>温馨提示</strong><br /></p>
    <p>1、统计时间段内，商城余额、积分概况；</p>
</div>

<div>
    <h3 class="heading">
        <!-- {if $ur_here}{$ur_here}{/if} -->
        <!-- {if $action_link} -->
        <a class="btn plus_or_reply" href="{$action_link.href}"><i class="fontello-icon-plus"></i>{$action_link.text}</a>
        <!-- {/if} -->
    </h3>
</div>

<div class="row-fluid">
    <div class="choose_list f_l">
        <form action="{$form_action}" method="post" name="searchForm">
            <div class="screen f_r">
                <span>按年份查询：</span>
                <div class="f_l m_r5">
                    <select class="w150" name="year">
                        <option value="0">请选择年份</option>
                        <!-- {foreach from=$year_list item=val} -->
                        <option value="{$val}" {if $val eq $year}selected{/if}>{$val} </option> <!-- {/foreach} -->
                    </select>
                </div>
                <span>按月份查询：</span>
                <div class="f_l m_r5">
                    <select class="no_search w120" name="month">
                        <option value="0">全年</option>
                        <!-- {foreach from=$month_list item=val} -->
                        <option value="{$val}" {if $val eq $month}selected{/if}>{$val} </option> <!-- {/foreach} -->
                    </select>
                </div>
                <button class="btn screen-btn" type="button">查询</button>
            </div>
        </form>
    </div>
</div>

<div class="row-fluid">
    <div class="stats-content">
        <div class="stats-item">
            <div class="item-left">余额统计</div>
            <div class="item-right">
                <div class="right-item">
                    <div class="item-top">会员消费（元）</div>
                    <div class="item-bottom">¥1069.00</div>
                </div>
                <div class="right-item">
                    <div class="item-top">会员充值（元）</div>
                    <div class="item-bottom">{$account.voucher_amount}</div>
                </div>
                <div class="right-item">
                    <div class="item-top">退款存入（元）</div>
                    <div class="item-bottom">¥1069.00</div>
                </div>
                <div class="right-item">
                    <div class="item-top">提现（元）</div>
                    <div class="item-bottom">{$account.to_cash_amount}</div>
                </div>
                <div class="right-item">
                    <div class="item-top">冻结（元）</div>
                    <div class="item-bottom">{$account.frozen_money}</div>
                </div>
                <div class="right-item">
                    <div class="item-top">剩余总余额（元）</div>
                    <div class="item-bottom">¥1069.00</div>
                </div>
            </div>
        </div>
        <div class="stats-item">
            <div class="item-left">积分统计</div>
            <div class="item-right">
                <div class="right-item">
                    <div class="item-top">下单发放</div>
                    <div class="item-bottom">1069</div>
                </div>
                <div class="right-item">
                    <div class="item-top">积分抵现</div>
                    <div class="item-bottom">1069</div>
                </div>
                <div class="right-item">
                    <div class="item-top">总发放积分</div>
                    <div class="item-bottom">1069</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="m_t20">
    <h3 class="heading">
        分布情况
    </h3>
</div>

<div class="row-fluid edit-page">
    <div class="span12">
        <div class="span6">
            <div class="left_stats">
                <div id="left_stats">
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="right_stats">
                <div id="right_stats">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="m_t20">
    <h3 class="heading">
        资金明细
    </h3>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
            <table class="table table-striped table-hide-edit">
                <thead>
                    <tr>
                        <th class="w180">变动时间</th>
                        <th>会员名称</th>
                        <th>变动原因</th>
                        <th>余额变动</th>
                        <th>积分变动</th>
                        <th>关联订单</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- {foreach from=$list.item key=key item=val} -->
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!-- {foreachelse}-->
                    <tr>
                        <td class="no-records" colspan="6">{lang key='system::system.no_records'}</td>
                    </tr>
                    <!-- {/foreach} -->
                </tbody>
            </table>
            <!-- {$list.page} -->
        </div>
    </div>
</div>

<!-- {/block} -->