<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width,maximum-scale=1.0,initial-scale=1,user-scalable=no"/>
    <meta charset="UTF-8">
    <title><?php echo ($title); ?></title>
</head>
<body><!--
<header class="header">
    <ul class="main_layout">
        <li class="back_btn" onclick="history.go(-1);"><img src="/public/mobile/images/arrow_l.png" alt=""/></li>
        <li class="text"><?php echo ($title); ?></li>
        <li class="close_btn" click="window.android.goToGame()"><img src="/public/mobile/images/closebtn.png" alt=""/></li>
    </ul>
</header>-->
<link rel="stylesheet" href="/public/mobile/css/nobindPhone.css"/>
    <style>
		.infoMsg{
		    box-sizing: border-box;
		    padding: 0px 20px;
		    margin-bottom: 10px;
		    font-size: 16px;
		}
	</style>
    <div class="set">
        <div>
            <div class="set1 orange"> 验证身份 </div>
            <span class="set2"></span>
            <div class="set1 gray"> 修改密码 </div>
            <span class="set2"></span>
            <div class="set1 gray"> 修改完成 </div>
        </div>
    </div>
<div class="notBind">
<p class="title"><span></span></p>
    <div class="img">
        <img src="/public/mobile/images/pic.png" alt=""/>
    </div>
</div>
<p class="infoMsg">你已绑定的手机号码：<?php echo ($userdata['mobile']); ?></p>
<footer>
    <div class="getCode">
       <div>
           <input type="text" id="code" name="code" placeholder="请输入短信验证码"/><button id="getCode">发送验证码</button>
       </div>
    </div>
    <div class="error_box"></div>
    <div class="confim_change">
        <button disabled>确定</button>
    </div>
    <p class="tishi"><span>如遇问题请及时<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=2399002239&amp;site=qq&amp;menu=yes" title="客服QQ：235222111">联系客服</a></span></p>
</footer>
<input  name="sendsms" id="sendsms" type="text" value="<?php echo U('Mobile/Forgetpwd/mobile_send');?>" style="display:none"/>
<input  name="bindurl" id="bindurl" type="text" value="<?php echo U('Mobile/Forgetpwd/mobile_checkcode');?>" style="display:none"/>

<div class="main_model_box"></div>
</body>
<script src="/public/mobile/js/jquery.js"></script>
<script src="/public/mobile/js/huosdk_base.js"></script>
<script src="/public/mobile/js/public.js"></script>
<script src="/public/mobile/js/nobindPhone.js"></script>
</html>