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
		<div class="wrap jj">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('Gm/give');?>">游戏币发放</a></li>
			<li class="active"><a href="#">游戏币币发放确认</a></li>
		</ul>
		
		<div class="common-form">
			<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('Gm/give_post');?>">
				<fieldset>
					<div class="control-group">
						<?php if($takeclass == 1): ?><input type="hidden" id="takeclass" name="takeclass" value="1"   checked="checked"/>
						<?php else: ?>
							<input type="hidden" id="takeclass" name="takeclass" value="2"  checked="checked"/><?php endif; ?>
					<input type='hidden' name='app_id' value='<?php echo ($app_id); ?>'>
					
					<div class="control-group">
							<label class="control-label">游戏  (游戏币):</label>
							<div class="controls">
								<label class="control-label" style="text-align:left"><?php echo ($games[$app_id]); ?> (<?php echo ($gamemoneys[$app_id]); ?>)</label>
							</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">充值账号:</label>
						<div class="controls">
							<input type="hidden" class="input" name="username" readonly="readonly" id="username" value="<?php echo ($username); ?>">	
							<label class="control-label" style="text-align:left"><?php echo ($username); ?></label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">拥有<?php echo ($gamemoneys[$app_id]); ?>数量:</label>
						<div class="controls">
							<input type="hidden" class="input" name="oldgm" id="oldgm" style="color:blue;" value="<?php echo ($remain); ?>" readonly="readonly" >
							<label class="control-label" style="text-align:left"><?php echo ($remain); ?></label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">发放<?php echo ($gamemoneys[$app_id]); ?>数量:</label>
						<div class="controls">
							<input type="hidden" class="input" name="newgm" value="<?php echo ($newgm); ?>" readonly="readonly" autocomplete="off">
							<label class="control-label" style="text-align:left"><?php echo ($newgm); ?></label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">充值金额:</label>
						<div class="controls">
							<input type="hidden" class="input" name="amount" value="<?php echo ($amount); ?>" readonly="readonly" autocomplete="off">
							<label class="control-label" style="text-align:left"><?php echo ($amount); ?></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">备注:</label>
						<div class="controls">
							<input type="text" class="input" name="beizhu" value="<?php echo ($beizhu); ?>" autocomplete="off">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">请填写二级密码:</label>
						<div class="controls">
							<input type="password" class="input" name="password" value="" autocomplete="off">
						</div>
					</div>
					
				</fieldset>
				<div class="form-actions">
					<input name="action" type="hidden" value="add">
					<button type="submit" class="btn btn-primary btn_submit js-ajax-submit">发放</button>
				</div>
			</form>
		</div>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>