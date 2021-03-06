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
		<form class="well form-search" method="post" action="<?php echo U('Gmmem/charge');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20"> 
					订单号： 
						<input type="text" name="order_id" style="width: 150px;" value="<?php echo ($formget["orderid"]); ?>" placeholder="请输入订单号..."> 
						&nbsp;&nbsp; 
					游戏：
					<select class="select_2" name="gid" id="selected_id">
							<?php if(is_array($games)): foreach($games as $k=>$vo): $gid_select=$k==$formget['gid'] ?"selected":""; ?>
							<option value="<?php echo ($k); ?>"<?php echo ($gid_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
					    </select>
					    &nbsp;&nbsp; 
					
					 <br>	
					 <br>	
					 </span>
					 <span>
					 
					玩家账号： 
					<input type="text" name="username"
						style="width: 200px;" value="<?php echo ($formget["username"]); ?>"
						placeholder="请输入玩家账号..."> 
						 &nbsp;&nbsp;  
					 
					时间：
					<input type="text" name="start_time"
						class="js-date" value="<?php echo ((isset($formget["start_time"]) && ($formget["start_time"] !== ""))?($formget["start_time"]):''); ?>"
						style="width: 150px;" autocomplete="off">
					- 
					<input	type="text" class="js-date" name="end_time"
						value="<?php echo ($formget["end_time"]); ?>" style="width: 150px;"
						autocomplete="off"> &nbsp; &nbsp; 
					</span>
			    <input type="submit"  name='submit' class="btn btn-primary" value="搜索" />
				</div>
			</div>
		</form>
		<form class="js-ajax-form" action="" method="post">
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th>订单号</th>
						<th>玩家账号</th>
						<th>游戏名称</th>
						<th>游戏币</th>
						<th>充值人民币</th>
						<th>游戏币数量</th>
						<th>状态</th>
						<th>充值时间</th>
					</tr>
				</thead>
					<tr>
						<th style='color:#00f'>汇总</th>
						<th style='color:#00f'>--</th>
						<th style='color:#00f'>--</th>
						<th style='color:#00f'>--</th>
						<th style='color:#00f'><?php echo ($sumlist['sum_money']); ?></th>
						<th style='color:#00f'><?php echo ($sumlist['gm_cnt']); ?></th>
						<th style='color:#00f'>--</th>
						<th style='color:#00f'>--</th>
					</tr>
				<?php if(is_array($items)): foreach($items as $key=>$vo): ?><tr>
					<td><?php echo ($vo["order_id"]); ?></td>
					<td><?php echo ($vo["username"]); ?></td>
					<td><?php echo ($games[$vo['app_id']]); ?></td>
					<td><?php echo ($gamemoneys[$vo['app_id']]); ?></td>
					<td><?php echo ($vo["amount"]); ?></td>
					<td><?php echo ($vo["gm_cnt"]); ?></td>
					<td><?php echo ($paystatus[$vo['status']]); ?></td>
					<td><?php echo (date('Y-m-d H:i:s',$vo["create_time"])); ?></td>
				</tr><?php endforeach; endif; ?>
			</table>
			<div class="pagination"><?php echo ($page); ?></div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>