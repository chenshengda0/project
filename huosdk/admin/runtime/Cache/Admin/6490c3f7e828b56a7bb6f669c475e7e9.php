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
<div class="wrap">
	<ul class="nav nav-tabs">
		<li class="active"><a href="<?php echo U('setting/paypwd');?>">修改二级密码</a></li>
	</ul>
  	<div class="common-form">
    	<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('setting/paypwd_post');?>">
        	<fieldset>
          		<div class="control-group">
           			<label class="control-label" for="input01">原二级密码:</label>
	            	<div class="controls">
	              		<input type="text" class="input" id="old_password" name="old_password">
	            	</div>
          		</div>
				<div class="control-group">
					<label class="control-label" for="input01">新二级密码:</label>
					<div class="controls">
						<input type="text" class="input" id="password" name="password">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input01">重复新密码:</label>
					<div class="controls">
						<input type="text" class="input" id="repassword" name="repassword">
					</div>
				</div>
         	 </fieldset>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary btn_submit js-ajax-submit">更新</button>
          </div>
      </form>
  </div>
</div>
<script src="/public/js/common.js"></script>
</body>
</html>