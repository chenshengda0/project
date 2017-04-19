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

<div class="form">
        <form  name="codeform" id="codeform"  action="<?php echo U('Forgetpwd/bindinfo');?>" method="POST">
              <div>请输入要找回的账号：</div>
              <div>
                    <input  name="username" id="username" type="text" placeholder="请输入账号"/> 
              </div>
		        <div id="msg_box">
		<!--             <div class="err1"><span>原密码错误，请重新填写!!</span></div>
		            <div class="err2"><span>两次密码输入不一致或不能为空！！</span></div> -->
		        </div>
              <input type="button" value="确定" class="form1">
        </form>
</div>

<div><a href="<?php echo U('Help/index');?>" class="blue">点此联系客服</a></div>
</body>
<script src="/public/float/js/jquery.js"></script>
<script src="/public/float/js/main.js"></script>
<script src="/public/float/js/float.js"></script>
<script>
	var btn1=document.querySelector(".form1");
	var vurl ;
	var vusername;
    btn1.onclick=function(){
    	vurl ="<?php echo U('Forgetpwd/verifyusername');?>";
    	vusername = $('#username').val();
    	$("#msg_box").css("display","none");
		if('' == vusername){
			showmsg("请输入账号");
			return;
		}
			
		var form_data = {
			username: vusername,
			type: 'findpwd'
		};
		sendDate(vurl,form_data,succ,err,"POST","JSON");
    }
</script>
</html>