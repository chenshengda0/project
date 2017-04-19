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
		<div class="wrap jj">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Gm/give');?>">游戏币发放</a></li>
		</ul>
		
		<div class="common-form">
			<form method="post" class="form-horizontal" action="<?php echo U('Gm/give_verify');?>">
				<fieldset>
					<div class="control-group">
						<label class="control-label">账号类型:</label>
						<div class="controls">
							<label class="radio inline" > 
								<input type="radio" checked id="takeclass" class="radio" name="takeclass" value="2" />充值玩家
							</label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label class="radio inline"> 
								<input type="radio"  id="takeclass" class="radio" name="takeclass" value="1" />代理商
							</label> 
						</div>
					</div>
					<div class="control-group">
					<label class="control-label">游戏:</label>
						<div class="controls">
							<select class="select_2" id="app_id" name="app_id" id="selected_id">
								<?php if(is_array($gamemoneys)): foreach($gamemoneys as $k=>$vo): $g_select=$k==$app_id ?"selected":""; ?>
									<option value="<?php echo ($k); ?>"<?php echo ($g_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
							</select>	
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">请填写账号:</label>
						<div class="controls">
							<input type="text" class="input" name="username" id="username" value="">
							<span id="usernamespan" style="color:#00F;display:none;">账号不存在</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">已有游戏币数量:</label>
						<div class="controls">
							<input type="text" class="input" name="oldgm" id="oldgm" style="color:blue;" value="0" readonly="readonly" >
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">请填写充值游戏币数量:</label>
						<div class="controls">
							<input type="text" class="input" name="newgm" value="" autocomplete="off">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">请填写充值金额:</label>
						<div class="controls">
							<input type="text" class="input" name="amount" value="" autocomplete="off">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">请填写备注:</label>
						<div class="controls">
							<input type="text" class="input" name="beizhu" value="" autocomplete="off">
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<input name="adminsite" type="hidden"  id="adminsite" value="<?php echo U('Gm/ajaxGetgm');?>">
					<button type="submit"	class="btn btn-primary btn_submit">确认</button>
				</div>
			</form>
		</div>
	</div>
	<script src="/public/js/common.js"></script>
	<script>
		$(function(){
			$("#username").bind("blur",function(){
				var username = $("#username").val();
				var app_id = $("#app_id").val();
				
				var takeclass=null;
				var obj=document.getElementsByName("takeclass")
				for (var i=0;i<obj.length;i++){ //遍历Radio
					if(obj[i].checked){
						takeclass=obj[i].value;
					}
				} 
				
				var adminsite = $("#adminsite").val() + "?username="+username+"&app_id="+app_id+"&takeclass="+takeclass;
				$.ajax({
					url: adminsite,
					type: "GET",
					success: function(data) {
						if (data == "noexit") {
							$("#usernamespan").show();	
						} else {
							$("#usernamespan").hide();
							$("#oldgm").val(data);
						}
					} 	
				});		
			});
		});
	</script>	
</body>
</html>