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
			<li class="active"><a href="<?php echo U('Gamedes/gameList');?>">游戏信息</a></li>
			<li><a href="<?php echo U('Gamedes/addGamedes');?>">添加游戏信息</a></li>

		</ul>

		<form class="well form-search" method="post" action="<?php echo U('Gamedes/gameList');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						游戏名称： 
						<input type="text" name="gametitle" style="width: 200px;" placeholder="请输入游戏名...">
						<input type="submit" name="submit" class="btn btn-primary" value="搜索" />
					</span>
				</div>
			</div>
		</form>
		<form class="js-ajax-form" action="" method="post">
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th>游戏</th>
						<th>序列号</th>
						<th>拼音</th>
						<th>游戏类型</th>
						<th>状态</th>
						<!-- <th>栏目</th> -->
						<!-- <th>母包地址</th> -->
						<th>管理操作</th>
					</tr>
				</thead>
				<?php $appstatus=array("1"=>"接入中","2"=>"可上线","3"=>"已下线"); ?>
				<?php if(is_array($items)): foreach($items as $key=>$vo): ?><tr>
					<td><?php echo ($vo["name"]); ?></td>
					<td><?php echo ($vo["listorder"]); ?></td>
					<td><?php echo ($vo["pinyin"]); ?></td>
					<td><?php echo ($vo["type"]); ?></td>
					<td><?php echo ($appstatus[$vo['status']]); ?></td>
					<!-- <td><?php echo ($appclassify[$vo['classify']]); ?></td> -->
					<td>
					    <a href="<?php echo U('Gamedes/editGamedes',array('app_id'=>$vo['id']));?>">编辑</a> ||
						<a href="<?php echo U('Gamedes/delGamedes',array('app_id'=>$vo['id']));?>" class="js-ajax-delete">删除</a>
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