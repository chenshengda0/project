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
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="javascript:;">资讯列表</a></li>
			<li><a
				href="<?php echo U('Newapp/News/add',array('term'=>empty($term['term_id'])?'':$term['term_id']));?>"
				target="_self">添加资讯</a></li>
		</ul>
		<form class="well form-search" method="post"
			action="<?php echo U('Newapp/News/index');?>">
			类型： <select class="select_2" name="ptype" id="ptype">
				<?php if(is_array($ptypes)): foreach($ptypes as $k=>$vo): $pt_select=$k==$formget['ptype'] ?"selected":""; ?>
				<option value="<?php echo ($k); ?>"<?php echo ($pt_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
			</select>
			&nbsp;&nbsp;
			游戏： <select class="select_2" name="appid" id="appid">
				<?php if(is_array($games)): foreach($games as $k=>$vo): $pt_select=$k==$formget['appid'] ?"selected":""; ?>
				<option value="<?php echo ($k); ?>" <?php echo ($pt_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
			</select>
			<br>
				<br>
			 时间： <input type="text" name="start_time" class="js-date"
				value="<?php echo ((isset($formget["start_time"]) && ($formget["start_time"] !== ""))?($formget["start_time"]):''); ?>" style="width: 80px;"
				autocomplete="off">- <input type="text" class="js-date"
				name="end_time" value="<?php echo ($formget["end_time"]); ?>" style="width: 80px;"
				autocomplete="off"> &nbsp; &nbsp; 标题关键字： <input type="text"
				name="keyword" style="width: 200px;" value="<?php echo ($formget["keyword"]); ?>"
				placeholder="请输入关键字..."> <input type="submit"
				class="btn btn-primary" value="搜索" />
		</form>
		<?php $status = array('1'=>'未审核','2'=>'已审核', '3'=>'审核不通过'); ?>
		<form class="js-ajax-form" action="" method="post">
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th width="50">序号</th>
						<th width="100">时间</th>
						<th width="50">游戏ID</th>
						<th>游戏名称</th>
						<th width="50">类型</th>
						<th>标题</th>
						<th>缩略图</th>
						<th>状态</th>
						<th width="70">操作</th>
					</tr>
				</thead>
				<?php if(is_array($posts)): foreach($posts as $key=>$vo): ?><tr>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo (date("Y-m-d", $vo["post_date"])); ?></td>
					<td><?php echo ($vo["app_id"]); ?></td>
					<td><?php echo ($vo["gamename"]); ?></td>
					<td><?php echo ($ptypes[$vo['post_type']]); ?></td>
					<td><?php echo ($vo["post_title"]); ?></td>
					<td><?php $smeta=json_decode($vo['smeta'],true); ?> <?php if(!empty($smeta['thumb'])): ?><a
							href="<?php echo sp_get_asset_upload_path($smeta['thumb']);?>"
							target='_blank'>查看</a><?php endif; ?></td>
					<td><?php echo ($status[$vo[post_status]]); ?></td>
					<td><a href="<?php echo U('Newapp/News/edit',array('id'=>$vo['id']));?>">编辑</a>
						| <a href="<?php echo U('Newapp/News/delete',array('id'=>$vo['id']));?>"
						class="js-ajax-delete">删除</a></td>
				</tr><?php endforeach; endif; ?>
			</table>
			<div class="pagination"><?php echo ($Page); ?></div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
	<script>
		$(".select_2").select2();
		function refersh_window() {
			var refersh_time = getCookie('refersh_time');
			if (refersh_time == 1) {
				window.location = "<?php echo U('Newapp/News/index',$formget);?>";
			}
		}
		setInterval(function() {
			refersh_window();
		}, 2000);
		$(function() {
			setCookie("refersh_time", 0);
			Wind.use('ajaxForm', 'artDialog', 'iframeTools', function() {
				//批量移动
				$('.js-articles-move').click(
						function(e) {
							var str = 0;
							var id = tag = '';
							$("input[name='ids[]']").each(function() {
								if ($(this).attr('checked')) {
									str = 1;
									id += tag + $(this).val();
									tag = ',';
								}
							});
							if (str == 0) {
								art.dialog.through({
									id : 'error',
									icon : 'error',
									content : '您没有勾选信息，无法进行操作！',
									cancelVal : '关闭',
									cancel : true
								});
								return false;
							}
							var $this = $(this);
							art.dialog.open(
									"/index.php?g=Admin&m=News&a=move&ids="
											+ id, {
										title : "批量移动",
										width : "80%"
									});
						});
			});
		});
	</script>
</body>
</html>