<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.invoice_list.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" ><i class="fontello-icon-plus"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>
<div class="row-fluid batch">
	<form action="{$form_action}" name="searchForm" method="post">
		<div class="wspan12">
			<div class="top_right f_r">
				<input type="text" name="keywords" value="{$invoice_list.filter.keywords}" placeholder="请输入发票抬头关键字"/>
				<button class="btn m_l5" type="submit">搜索</button>
			</div>
		</div>
	</form>
</div>
<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped" id="smpl_tbl">
			<thead>
				<tr>
					<th class="w150">用户id</th>
					<th>抬头名称</th>
					<th class="w100">抬头类型</th>
					<th class="w150">纳税人识别号</th>
					<th class="w100">电话号码</th>
					<th class="w150">添加时间</th>
					
				</tr>
			</thead>
			<tbody>
				<!-- {foreach from=$invoice_list.list item=item}-->
				<tr>
					<td>
						{$item.user_id}
						<a class="data-pjax no-underline" href='{url path="user/admin/info" args="id={$item.user_id}"}'>（{$item.user_name}）</a>
					</td>
					<td>{$item.title_name}</td>
					<td>{$item.title_type_name}</td>
					<td>{$item.tax_register_no}</td>
					<td>{$item.user_mobile}</td>
					<td>{$item.add_time}</td>
				</tr>
				<!-- {foreachelse}-->
				<tr><td class="no-records" colspan="6">{lang key='system::system.no_records'}</td></tr>
				<!-- {/foreach} -->
			</tbody>
		</table>
		<!-- {$list.page} -->
	</div>
</div>
<!-- {/block} -->