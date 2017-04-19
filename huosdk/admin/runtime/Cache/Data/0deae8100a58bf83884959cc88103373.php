<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		.length_3{width: 180px;}
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
<script type="text/javascript">
//全局变量
var GV = {
		DIMAUB : "/",
		JS_ROOT : "public/js/",
    TOKEN: ""
};
</script>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/wind.js"></script>
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
<script src="/public/select2/js/select2.min.js"></script>
<link href="/public/select2/css/select2.min.css" rel="stylesheet"
	type="text/css">
<script>
	$(document).ready(function() {
		$(".select_2").select2();
	});
</script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
</head>
<body class="J_scroll_fixed">
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('member/dataindex');?>">整体数据</a></li>
		</ul>
		<form class="well form-search" method="post" action="<?php echo U('Data/Member/dataindex');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						账号： 
						<input type="text" name="username" style="width: 200px;" value="<?php echo ($formget["username"]); ?>" placeholder="请输入账号...">
						&nbsp;&nbsp;
						<input type="submit" class="btn btn-warning" value="搜索" />
					</span>
				</div>
			</div>
		</form>		
		<table class="table table-hover table-bordered table-list">
			<thead>
				<tr>					
                    <!--<th>玩家ID</th> -->
                    <th  style="vertical-align:middle; text-align:center;">玩家账号</th>
					<th>游戏数量</th>					
					<th>登陆次数</th>
					<th>最近登陆时间</th>
					<th>充值次数</th>
					<th>充值总额</th>
					<th>最近充值金额</th>
					<th>最近充值时间</th>
					<th>注册时间</th>
				</tr>
			</thead>
				<tr>					
                    <!--<th>玩家ID</th> -->
                    <th style='color:#f00'>玩家数量汇总</th>
					<th style='color:#f00'><?php echo ($count); ?></th>					
					<th>--</th>
					<th>--</th>
					<th>--</th>
					<th>--</th>
					<th>--</th>
					<th>--</th>
					<th>--</th>
				</tr>
			<tbody>				
				<?php if(is_array($members)): foreach($members as $key=>$vo): ?><tr>
					<!-- <td><?php echo ($vo["mem_id"]); ?></td> -->
					<td><?php echo ($vo["username"]); ?></td>
					<td><?php echo ($vo["game_cnt"]); ?></td>
					<td><?php echo ($vo["login_cnt"]); ?></td>	
					<td><?php echo (date('Y-m-d H:i:s',$vo["last_login_time"])); ?></td>			
					<td><?php echo ($vo["order_cnt"]); ?></td>
					<td><?php echo ($vo["sum_money"]); ?></td>	
					<td><?php echo ($vo["last_money"]); ?></td>
					<td>
					<?php if(empty($vo['last_pay_time'])): ?>未充值
					<?php else: ?>
						<?php echo (date('Y-m-d H:i:s',$vo["last_pay_time"])); endif; ?>
					
					</td>
					<td><?php echo (date('Y-m-d H:i:s',$vo["reg_time"])); ?></td>		
				</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo ($Page); ?></div>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>