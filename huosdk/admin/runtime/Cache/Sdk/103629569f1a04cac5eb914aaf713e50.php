<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
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
			<li><a href="<?php echo U('Game/index');?>">游戏列表</a></li>
			<li class="active"><a href="<?php echo U('Game/delindex');?>">删除列表</a></li>
			<li><a href="<?php echo U('Game/add');?>">添加游戏</a></li>
		</ul>

		<form class="well form-search" method="post" action="<?php echo U('Game/delindex');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						游戏名称： 
						<input type="text" name="name" style="width: 200px;" value="<?php echo ($name); ?>" placeholder="请输入游戏名...">
						<input type="submit" name="submit" class="btn btn-primary" value="搜索" />
					</span>
				</div>
			</div>
		</form>
		<form class="js-ajax-form" action="" method="post">
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th>游戏ID(APPID)</th>
						<th>游戏名称</th>
						<th>APPKEY</th>
						<th>状态</th>
						<th>回调地址</th>
						<th>母包地址</th>
						<th>管理操作</th>
					</tr>
				</thead>
				<?php if(is_array($games)): foreach($games as $key=>$vo): ?><tr>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["name"]); ?></td>
					<td><?php echo ($vo["app_key"]); ?></td>
					<td>
							 <?php echo ($gamestatues[$vo[status]]); ?>
					</td>
					<td>
					    <?php if(empty($vo['cpurl'])): ?>暂无回调
						<?php else: ?> 
							<?php echo ($vo["cpurl"]); endif; ?> 
					</td>
					
					<td>
					    <?php if(empty($vo['packageurl'])): ?>暂无母包
						<?php else: ?> 
							<?php echo ($vo["packageurl"]); endif; ?> 
					</td>
					
					<td>
						<?php if($vo['is_delete'] == 1): ?><a href="<?php echo U('Game/restoreGame',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="确定恢复游戏？"> 恢复游戏</a><?php endif; ?>
					</td>
				</tr><?php endforeach; endif; ?>
			</table>
			<div class="pagination"><?php echo ($Page); ?></div>

		</form>
	</div>
	<script src="/public/js/common.js"></script>
	<script>
		$(function() {

			$("#navcid_select").change(function() {
				$("#mainform").submit();
			});

		});
	</script>
</body>
</html>