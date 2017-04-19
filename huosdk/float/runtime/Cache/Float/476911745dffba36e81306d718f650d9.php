<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="textml; charset=UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />
<title><?php echo ($title); ?></title>
<link rel="stylesheet" type="text/css" href="/public/float/css/float.css" />
</head>
<body>
<!--
<div class="modular">
    <ul>
        <li class="back"><a onclick="history.go(-1);"><img src="/public/float/images/goback.png"><span class="main_title"><?php echo ($title); ?></span></a></li>
        <li><a onclick="window.mgw_web_back.goToGame()">回到游戏</a></li> 
    </ul>
</div>-->

<div class="form2">
	<div class="user_num">
		<b>正在修改的帐号:</b><span><?php echo (session('username')); ?></span>
	</div>
	<form name="" action="" method="post">
		<input type="text" name="oldpwd" id="oldpwd" placeholder="原密码" /> <input
			type="text" name="newpwd" id="newpwd" placeholder="新密码" /> <input
			type="text" name="verifypwd" id="verifypwd" placeholder="再次输入密码" />
		<input type="hidden" name="url" id="url"
			value="<?php echo U('User/uppwd_post');?>" />
		<div id="msg_box">
			<!--             <div class="err1"><span>原密码错误，请重新填写!!</span></div>
            <div class="err2"><span>两次密码输入不一致或不能为空！！</span></div> -->
		</div>
		<input type="button" id="updatepwd" name="updatepwd" value="确定"
			class="form1">
	</form>

</div>
</body>
<script type="text/javascript" src="/public/float/js/jquery.js"></script>
<script type="text/javascript" src="/public/js/base64.js"></script>
<script type="text/javascript" src="/public/js/md5.js"></script>
<script type="text/javascript" src="/public/float/js/main.js"></script>
<script>
	$(document).ready(function(){
		$("#updatepwd").click(function(){
			var oldpwd = $("#oldpwd").val();
			var newpwd = $("#newpwd").val();
			var verifypwd = $("#verifypwd").val();
			var posturl = $("#url").val();

			if (oldpwd == ''|| oldpwd.length == 0){
				showmsg('请输入原密码');
				return false;
			}
			if (newpwd == ''|| newpwd.length == 0){
				showmsg('请输入新密码');
				return false;
			}
			if (verifypwd == ''|| verifypwd.length == 0){
				showmsg('请输入确认密码');
				return false;
			}
			
			var regcellpwd = /^[0-9A-Za-z-`=\\\[\];',.\/~!@#$%^&*()_+|{}:"<>?]{6,16}$/g;
			if( false == regcellpwd.test(newpwd)){
				showmsg('密码必须由6-16位的数字、字母、符号组成');
				return false;
			}
			
			if (newpwd != verifypwd){
				showmsg('确认密码与新密码不一致');
				return false;
			}

			var form_data = {
				oldpwd: oldpwd,
				newpwd: newpwd,
				verifypwd: verifypwd,
				action: "updatepwd"
			};
			
			$.ajax({
				type: "POST",
				url: posturl,
				data: form_data,
				error : function(XMLHttpRequest, textStatus, errorThrown) {   
					showmsg('读取超时，网络错误'); 
				},
				dataType:"json",
				success: function(result)
				{
					if ('fail' == result.state){
						if(result.url){
							window.location.href=result.url;
						}else{
							showmsg(result.info); 
						}
					}else{
						window.location.href=result.url;
					}
				}	
			});
			return false;
		});
	});
	
	
	function showmsg(msg)
	{
		$("#msg_box").css("display","block");
		$("#msg_box").html('<div class="err1"><span>'+msg+'</span></div>');
	}
</script>
</html>