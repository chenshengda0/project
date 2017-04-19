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
<link rel="stylesheet" href="/public/mobile/css/bindPhone.css"/>
<section>
    <div class="set">
        <div>
            <div class="set1 gray"> 验证身份 </div>
            <span class="set2"></span>
            <div class="set1 orange"> 设置邮箱 </div>
            <span class="set2"></span>
            <div class="set1 gray"> 设置完成 </div>
        </div>
    </div>
    <ul class="prompt_list">
        <p class="textTitle"><span>帐号：</span><?php echo ($_SESSION['user']['nickname']); ?></p>
        <li>
           <div class="item">
               <div class="icon"><img src="/public/mobile/images/btn_getCode.png" alt=""/></div>
               <input type="text" id="phone"  placeholder="请输入要绑定的邮箱" class="noBtn"/>
           </div>
        </li>
        <li class="textTitle">
            <div class="item">
                <div class="icon"><img src="/public/mobile/images/btn_getCode.png" alt=""/></div>
                <input placeholder="请输入验证码" id="code"  type="text"/>
                <button class="getCode">获取验证码</button>
            </div>
        </li>
    </ul>
    <ul class="prompt_list">
        <p class="textTitle"><span>帐号密码</span></p>
        <li>
            <div class="item">
                <div class="icon"><img src="/public/mobile/images/btn_pwd.png" alt=""/></div>
                <input type="password" id="pwd" name="pwd" value="" placeholder="请输入平台帐号密码" class="noBtn"/>
            </div>
        </li>
    </ul>
    <div class="error_box"></div>
    <div class="confim_change">
        <button>立即绑定</button>
    </div>
</section>
<input  name="sendsms" id="sendsms" type="text" value="<?php echo U('Mobile/Security/email_send');?>" style="display:none"/>
<input  name="bindurl" id="bindurl" type="text" value="<?php echo U('Mobile/Security/email_checkcode');?>" style="display:none"/>
<footer>
    <span>绑定邮箱可以用于找回支付密码和帐号密码</span>
</footer>
<div class="main_model_box"></div>
</body>
<script src="/public/mobile/js/jquery.js"></script>
<script src="/public/mobile/js/huosdk_base.js"></script>
<script src="/public/mobile/js/public.js"></script>
<script src="/public/mobile/js/email_set.js"></script>
</html>