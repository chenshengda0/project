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
            <div class="set1 orange"> 修改密码 </div>
            <span class="set2"></span>
            <div class="set1 gray"> 修改完成 </div>
        </div>
    </div>
    <ul class="prompt_list">
        <p class="textTitle"><span>新密码:</span><?php echo ($_SESSION['user']['nickname']); ?></p>
        <li>
           <div class="item">
               <div class="icon"><img src="/public/mobile/images/btn_pwd.png" alt=""/></div>
               <input type="password" id="new_pwd" name="new_pwd" placeholder="请输入新密码" class="noBtn"/>
           </div>
        </li>
    </ul>
    <ul class="prompt_list">
        <p class="textTitle"><span>确认密码:</span></p>
        <li>
            <div class="item">
                <div class="icon"><img src="/public/mobile/images/btn_pwd.png" alt=""/></div>
                <input type="password" id="confirm_pwd" name="confirm_pwd" value="" placeholder="请输入确认密码" class="noBtn"/>
            </div>
        </li>
    </ul>
    <input  name="ajaxurl" id="ajaxurl" type="text" value="<?php echo U('Mobile/Forgetpwd/uppwd_post');?>" style="display:none"/>
    <input  name="uppwdtoken" id="uppwdtoken" type="text" value="<?php echo ($uppwdtoken); ?>" style="display:none"/>
    
    <div class="error_box"></div>
    <div class="confim_change">
        <button>修改</button>
    </div>
</section>
<footer class="kefuFooter">
    <p class="help">
    <a class="QQ" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo ($qq); ?>&amp;site=qq&amp;menu=yes">
    <span>客服QQ：</span><?php echo ($qq); ?></a>
	<?php if($idkey == ''): ?><a class="QQgroup" target="_blank" href="javascript:void();" style="color:#adadad"><span>QQ 群：</span><?php echo ($qqgroup); ?></a>
	<?php else: ?>
		<a class="QQgroup" target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=<?php echo ($idkey); ?>"><span>QQ 群：</span><?php echo ($qqgroup); ?></a><?php endif; ?>
	</p>
</footer>
<div class="main_model_box"></div>
</body>
<script src="/public/mobile/js/jquery.js"></script>
<script src="/public/mobile/js/huosdk_base.js"></script>
<script src="/public/mobile/js/public.js"></script>
<script src="/public/mobile/js/pwdnew.js"></script>
</html>