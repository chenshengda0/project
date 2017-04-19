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
<link rel="stylesheet" href="/public/mobile/css/forgetpwd.css"/>
<div class="box">
    <h3>请输入需要找回密码的帐号！</h3>
    <input type="text" class="inputBox" id="username" name="username"  value="" placeholder="请输入需要找回密码的帐号" />
</div>
<div class="error_box"></div>
<div class="confim_change">
    <button>下一步</button>
</div>
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
<input type="hidden" value="<?php echo U('Mobile/Forgetpwd/check');?>" id="ajaxUrl">
</body>
<script src="/public/mobile/js/jquery.js"></script>
<script src="/public/mobile/js/huosdk_base.js"></script>
<script src="/public/mobile/js/public.js"></script>
<script src="/public/mobile/js/forgetpwd.js"></script>
</html>