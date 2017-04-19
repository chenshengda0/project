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
<!-- 公用样式 -->
		
		<link href="/public/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
        <script src="/public/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
        <script src="/public/bootstrap-fileinput/js/fileinput_locale_zh.js" type="text/javascript"></script>
        <script src="/public/bootstrap-fileinput/js/fileinput_locale_es.js" type="text/javascript"></script>

</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('Sdk/Game/index');?>">游戏列表</a></li>
			<li class="active"><a href="#">编辑游戏</a></li>
		</ul>
	
	<div class="wrap jj">
		<div class="common-form">
			<div id="kv-avatar-errors" class="center-block" style="width:800px;display:none"></div>
			<form enctype="multipart/form-data"  class="form-horizontal js-ajax-form" action="<?php echo U('Sdk/Game/edit_post');?>" method="post"> 
				<fieldset>
					<input type='hidden' name='appid' value='<?php echo ($gdata["id"]); ?>'>
					<div class="control-group">
							<label class="control-label">游戏名称:</label>
						<div class="controls">
							<input type="text" class="input" name="gamename" value="<?php echo ($gdata['name']); ?>" placeholder="请输入游戏名称">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">CP分成比例</label>
						<div class="controls">
							<input type="text"  class="input" value="<?php echo ($gdata["game_rate"]); ?>" name="game_rate" value="">
							* 比例50% 填写0.5
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">渠道默认分成比例:</label>
						<div class="controls">
							<input type="text" class="input" value="<?php echo ($gdata["agent_rate"]); ?>" name="agent_rate" value="">
							* 比例50% 填写0.5
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">当前状态</label>
						<div class="controls">
							<?php if(is_array($gamestatues)): foreach($gamestatues as $k=>$v): $gs_select=$k==$gdata[status] ?"checked":""; ?>
								<label class="radio inline" for="active_true">
								<?php if($gs_select == 'checked'): ?><input type="radio" name="gstatus" value="<?php echo ($k); ?>" <?php echo ($gs_select); ?>  id="gstatus"><?php echo ($v); ?></input><?php endif; ?>
								</label><?php endforeach; endif; ?>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<button type="submit"
						class="btn btn-primary btn_submit js-ajax-submit">添加</button>
					<a class="btn" href="<?php echo U('Sdk/Game/index');?>">返回</a>
				</div>
			</form>
		</div>
	</div>
	<script src="/public/js/common.js"></script>

	<script>
		$(function(){
			$("#version").bind("blur",function(){
				var version = $("#version").val();
				var reg = /([0-9]{1}.){1,}[0-9]{1}$/; 
				
				if(version != null){
					if(!reg.test(version)){
					   $("#cmsg").html("格式不正确,数字小数点组合");
					}else{
						$("#cmsg").html("");	
					}
				}
				
			});
		});

		var btnCust = ''; 
		$("#avatar").fileinput({
			overwriteInitial: true,
			maxFileSize: 1500,
			showClose: false,
			showCaption: false,
			browseLabel: '',
			removeLabel: '',
			browseIcon: '<i class="fa fa-folder-open-o"></i>添加',
			removeIcon: '<i class="fa fa-trash-o"></i>清除',
			removeTitle: 'Cancel or reset changes',
			elErrorContainer: '#kv-avatar-errors',
			msgErrorClass: 'alert alert-block alert-danger',
			defaultPreviewContent: '<img src="/public/bootstrap-fileinput/img/add_img.png" alt="Your Avatar" style="width:160px">',
			layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
			allowedFileExtensions: ["jpg", "png"]
		});

		$("#input-pt-br").fileinput({
			language: "zh",
			uploadUrl: "",
			maxFileCount: 5,
			showUpload: false,
			showCancel: false,
			showCaption: false,
			overwriteInitial: false,
			dropZoneEnabled: false,
			allowedFileExtensions: ["jpg","jpeg", "png", "gif"]
		});
	</script>
	
</body>

</html>