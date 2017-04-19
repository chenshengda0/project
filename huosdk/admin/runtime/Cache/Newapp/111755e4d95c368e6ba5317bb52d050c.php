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
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Newapp/Version/index');?>">APP版本列表</a></li>
			<li><a href="<?php echo U('Newapp/Version/add');?>">添加APP版本</a></li>
		</ul>

<!-- 		<form class="well form-search" method="post"
			action="<?php echo U('Newapp/Version/index');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20"> 当前状态： <select class="select_2"
						name="gstatus" id="gstatus">
							<?php if(is_array($gamestatus)): foreach($gamestatus as $k=>$vo): $gs_select=$k==$formget['gstatus'] ?"selected":""; ?>
							<option value="<?php echo ($k); ?>"<?php echo ($gs_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
					</select>&nbsp;&nbsp; 游戏标签： <select class="select_2" name="gtype" id="gtype">
							<?php if(is_array($gtypes)): foreach($gtypes as $k=>$vo): $gt_select=$k==$formget['gtype'] ?"selected":""; ?>
							<option value="<?php echo ($k); ?>"<?php echo ($gt_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
					</select>&nbsp;&nbsp; 游戏名称： <input type="text" name="gname"
						style="width: 200px;" value="<?php echo ($formget['gname']); ?>"
						placeholder="请输入游戏名..."> <input type="submit"
						name="submit" class="btn btn-primary" value="搜索" />
					</span>
				</div>
			</div>
		</form> -->
		<form class="js-ajax-form" action="" method="post">
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<!-- <th>版本ID</th>-->
						<th>版本ID</th>
						<th>版本名称</th>
						<th>添加时间</th>
						<th>更新时间</th>
						<th>下载地址</th>
						<th>大小</th>
						<th>状态</th>
						<th>管理操作</th>
					</tr>
				</thead>
				<?php if(is_array($versions)): foreach($versions as $key=>$vo): ?><tr>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["version"]); ?></td>
					<td><?php echo (date('Y-m-d H:i:s',$vo["create_time"])); ?></td>
					<td><?php echo (date('Y-m-d H:i:s',$vo["update_time"])); ?></td>
					<td style="word-wrap:break-word;word-break:break-all; ">
					    <?php if(empty($vo['packageurl'])): ?>暂未上传app
							<br/><a href="<?php echo U('Version/addpackageurl',array('id'=>$vo['id']));?>">添加app</a>
						<?php else: ?> 
							<?php echo ($vo["packageurl"]); endif; ?> 
					</td>
					<td><?php echo (format_file_size($vo["size"])); ?></td>
					<td>
					<?php if($vo['status'] == 1 OR $vo['status'] == 3): ?><a href="<?php echo U('Version/set_status',array('id'=>$vo['id'],'status'=>2));?>" class="js-ajax-dialog-btn" data-msg="确定上线版本？">上线版本</a>
						<?php else: ?>
							已上线<?php endif; ?>
					</td>
					<td >
						<a href="<?php echo U('Version/get_param',array('id'=>$vo['id']));?>">版本参数 </a>
						<a href="<?php echo U('Version/edit',array('id'=>$vo['id']));?>">| 编辑 </a>
						<?php if($vo['status'] < 3): ?><a href="<?php echo U('Version/del',array('id'=>$vo['id']));?>" class="js-ajax-delete"> | 删除</a><?php endif; ?>
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