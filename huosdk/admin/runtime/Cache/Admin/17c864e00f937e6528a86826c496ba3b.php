<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo L('ADMIN_CENTER');?></title>
		<meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge" />
		<meta name="renderer" content="webkit|ie-comp|ie-stand">
		<meta name="robots" content="noindex,nofollow">
		<link href="/admin/themes/simplebootx/Public/assets/css/admin_login.css" rel="stylesheet" />
		<style>
			#login_btn_wraper{
				text-align: center;
			}
			#login_btn_wraper .tips_success{
				color:#fff;
			}
			#login_btn_wraper .tips_error{
				color:#DFC05D;
			}
			#login_btn_wraper button:focus{outline:none;}
		</style>
		<script>
			if (window.parent !== window.self) {
					document.write = '';
					window.parent.location.href = window.self.location.href;
					setTimeout(function () {
							document.body.innerHTML = '';
					}, 0);
			}	
		</script>
		
	</head>
<body id='bodys' style="margin-top:0px;">
	<img src="http://admin.shouyoucun.cn/public/images/aixi.jpg" id="bgimg" style="position:relative;"/>
	<div class="wrap"  style="margin-top:0px;margin-left:35%;position:absolute;top:10px;left:10px">
			
	 <div style="height:255px;"></div>
		<h1><a href="/public"><?php echo L('ADMIN_CENTER');?></a></h1>
		<form method="post" name="login" action="<?php echo U('admin/public/dologin');?>" autoComplete="off" class="js-ajax-form">
			<div class="login">
				<ul>
					<li>
						<input class="input" id="js-admin-name" required name="username" type="text" placeholder="<?php echo L('USERNAME_OR_EMAIL');?>" title="<?php echo L('USERNAME_OR_EMAIL');?>" value="<?php echo ($_COOKIE['admin_username']); ?>"/>
					</li>
					<li>
						<input class="input" id="admin_pwd" type="password" required name="password" placeholder="<?php echo L('PASSWORD');?>" title="<?php echo L('PASSWORD');?>" />
					</li>
					<li class="verifycode-wrapper">
						<?php echo sp_verifycode_img('length=4&font_size=20&width=248&height=42&use_noise=1&use_curve=0','style="cursor: pointer;" title="点击获取"');?>
					</li>
					<li>
						<input class="input" type="text" name="verify" placeholder="<?php echo L('ENTER_VERIFY_CODE');?>" />
					</li>
				</ul>
				<div id="login_btn_wraper">
					<button type="submit" name="submit" class="btn js-ajax-submit" data-loadingmsg="<?php echo L('LOADING');?>"><?php echo L('LOGIN');?></button>
				</div>
			</div>
		</form>
	</div>
<script>
var GV = {
	DIMAUB: "",
	JS_ROOT: "/public/js/",//js版本号
	TOKEN : ''	//token ajax全局
};
	var w= document.body.scrollWidth;  
	var h= document.documentElement.clientHeight + 14;
					
	var b=document.getElementById("bodys");
	//console.log(b);
	b.style.height=h+'px';
	b.style.width=w+'px';
	var i=document.getElementById('bgimg');
	console.log(i);
	
	i.style.height='100%';
	i.style.width='100%';
	//b.style.backgroundImage ="url('http://admin.shouyoucun.cn/public/images/aixi.jpg')";
</script>
<script src="/public/js/wind.js"></script>
<script src="/public/js/jquery.js"></script>
<script type="text/javascript" src="/public/js/common.js"></script>
<script>
;(function(){
	document.getElementById('js-admin-name').focus();
})();
</script>
</body>
</html>