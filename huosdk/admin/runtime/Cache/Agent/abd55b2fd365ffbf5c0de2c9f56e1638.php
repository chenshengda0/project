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
			<li class="active"><a href="#">游戏币列表</a></li>
			<li ><a href="<?php echo U('Gm/add');?>">添加游戏币</a></li>
		</ul>
		<form class="well form-search" method="post" action="<?php echo U('Gm/index');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
					游戏: 
					<select class="select_2" name="app_id" id="selected_id">
						<?php if(is_array($games)): foreach($games as $k=>$vo): $g_select=$k==$formget['app_id'] ?"selected":""; ?>
							<option value="<?php echo ($k); ?>"<?php echo ($g_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
					</select>	
					&nbsp; &nbsp;
					<input type="submit" class="btn btn-primary" value="搜索" />
					</span>
				</div>
			</div>
		</form>
		<form class="js-ajax-form" action="" method="post">
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th>游戏ID</th>
						<th>游戏名称</th>
						<th>游戏币名称</th>
						<th>发放最大限额</th>
						<th>官方发放总数</th>
						<th>代理剩余总数</th>
						<th>玩家剩余总数</th>
						<th>使用代理数量</th>
						<th>使用玩家数量</th>
						<th>备注</th>
						<th>管理操作</th>
					</tr>
				</thead>
				<?php if(is_array($items)): foreach($items as $key=>$vo): ?><tr>
					<td><?php echo ($vo["app_id"]); ?></td>
					<td><?php echo ($games[$vo['app_id']]); ?></td>
					<td><?php echo ($vo['payname']); ?></td>
					<td><?php echo ($vo["give_max_cnt"]); ?></td>
					<td><?php echo ($vo["office_total"]); ?></td>
					<td><?php echo ($vo["agent_remain"]); ?></td>
					<td><?php echo ($vo["mem_remain"]); ?></td>
					<td><?php echo ($vo["agent_cnt"]); ?></td>
					<td><?php echo ($vo["mem_cnt"]); ?></td>
					<td><?php echo ($vo["description"]); ?></td>
					<td>
						<a href="<?php echo U('gm/edit',array('app_id'=>$vo['app_id']));?>">编辑 </a>
						<?php if($vo['is_delete'] == 2): ?><a href="<?php echo U('gm/del',array('app_id'=>$vo['app_id']));?>" class="js-ajax-delete"> | 删除</a><?php endif; ?>
					</td>
				</tr><?php endforeach; endif; ?>
			</table>
			<div class="pagination"><?php echo ($page); ?></div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>