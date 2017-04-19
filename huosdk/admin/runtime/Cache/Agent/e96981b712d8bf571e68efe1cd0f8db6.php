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
	<div class="wrap jj">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('Agent/index');?>">渠道管理</a></li>
			<li class="active"><a href="#" target="_self">添加渠道</a></li>
		</ul>
			<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('Agent/Agent/add_post');?>">
				<fieldset>
					<div class="control-group">
						<label class="control-label">账号:</label>
						<div class="controls">
							<input type="text" class="input" name="user_login" placeholder="输入渠道账号">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">密码:</label>
						<div class="controls">
							<input type="password" class="input" name="user_pass" value="" placeholder="输入密码">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">渠道名称:</label>
						<div class="controls">
							<input type="text" class="input" name="user_nicename" placeholder="输入渠道名称">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">联系人:</label>
						<div class="controls">
							<input type="text" class="input" name="linkman" placeholder="输入联系人">
						</div>
					</div>
					<div class="control-group" >
						<label class="control-label">QQ:</label>
						<div class="controls">
							<input type="text" class="input" name="qq" placeholder="输入QQ">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">手机:</label>
						<div class="controls">
							<input type="text" class="input" name="mobile" placeholder="输入手机号码">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">角色:</label>
						<div class="controls">
							<?php if(is_array($roles)): foreach($roles as $k=>$vo): ?><label class="checkbox inline">
								<input value="<?php echo ($k); ?>" type="radio" name="role_id" checked><?php echo ($vo); ?>
							</label><?php endforeach; endif; ?>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
				<button type="submit" class="btn btn-primary js-ajax-submit"><?php echo L('ADD');?></button>
					<a class="btn" href="Agent/Agent/index">返回</a>
				</div>
			</form>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>