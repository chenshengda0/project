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
<link rel="stylesheet" href="/public/mobile/css/user.css" />
	<section>
		<div class="bindWay">已绑定方式</div>
		<?php if(!empty($userdata['mobile'])): ?><div class="tenGrayBox"></div>
		<ul class="funcList">
			<li class="list qb"><a href="<?php echo U('Forgetpwd/mobile');?>"><div
						class="left">
						<b></b><span>手机</span>
					</div>
					<div class="right">
						<i><b><?php echo ($userdata["mobile"]); ?></b></i><s></s>
					</div></a></li>
		</ul><?php endif; ?>
		
		<?php if(!empty($userdata['email'])): ?><div class="tenGrayBox"></div>
		<ul class="funcList">
			<li class="list qb"><a href="<?php echo U('Forgetpwd/email');?>"><div
						class="left">
						<b style="background-image:none;position:relative"><img style="width:100%;height:100%;position:absolute;top:0px;left:0px;" src="/public/mobile/images/btn_getCodeBlue.png" alt=""></b><span>邮箱</span>
					</div>
					<div class="right">
						<i><b><?php echo ($userdata["email"]); ?></b></i><s></s>
					</div></a></li>
		</ul><?php endif; ?>
		<div class="tenGrayBox"></div>
	</section>
	<div class="main_model_box"></div>
	<style>
		section .funcList > .list > a > .left > b{
			background: url("/public/mobile/images/icon_list2.png");
			    background-position: 0px -24px;
		}
	</style>
</body>
</html>