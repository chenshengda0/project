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
			<li><a href="<?php echo U('Gm/index');?>">游戏币列表</a></li>
			<li class="active"><a href="#">添加游戏币</a></li>
		</ul>
		<div class="common-form">
			<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('Gm/add_post');?>">
				<fieldset>
					<div class="control-group">
						<label class="control-label">游戏:</label>
						<div class="controls">
							<select class="select_2" name="app_id" id="selected_id">
								<?php if(is_array($games)): foreach($games as $k=>$vo): $g_select=$k==$formget['app_id'] ?"selected":""; ?>
									<option value="<?php echo ($k); ?>"<?php echo ($g_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
							</select>	
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">游戏币名称</label>
						<div class="controls">
							<input type="text"  class="input" name="payname" value="">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">单次发放最大限额:</label>
						<div class="controls">
							<input type="text"  class="input" name="give_max_cnt" value="">
							* 官方发放时单次最大限额,默认为10000
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">备注:</label>
						<div class="controls">
							<input type="text"  class="input" name="dis" value="">
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<button type="submit"
						class="btn btn-primary btn_submit js-ajax-submit">添加</button>
					<a class="btn" href="<?php echo U('Gm/index');?>">返回列表</a>
				</div>
			</form>
		</div>
	</div>
	<script src="/public/js/common.js"></script>
	
</body>
</html>