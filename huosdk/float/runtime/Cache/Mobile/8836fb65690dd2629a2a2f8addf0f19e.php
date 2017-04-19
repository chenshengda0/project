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
<link rel="stylesheet" href="/public/mobile/css/changepwd.css"/>
<div class="changepwd_box">
       <ul>
       <li><p><span>原密码</span></p><input type="text" id="old_pwd" placeholder="请输入原密码"/></li>
       <li><p><span>新密码</span></p><input type="password" id="new_pwd" placeholder="请输入新密码"/></li>
       <li><p><span>确认密码</span></p><input type="password" id="confirm_pwd" placeholder="请输入确认密码"/></li>
   	</ul>   	
   	 <form action="<?php echo U('Password/uppwd_post');?>" method="post" id="ajaxform" style="display:none">
   	 <input type="text" id="uppwdtoken" value="<?php echo ($uppwdtoken); ?>" />
  	 </form>

</div>
<div class="error_msg"></div>
<footer>
    <div class="confim_change">
        <button>提交</button>
    </div>
</footer>

<footer class="kefuFooter">
		<p class="help"><a class="QQ" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo ($qq); ?>&amp;site=qq&amp;menu=yes"><span>客服QQ：</span><?php echo ($qq); ?></a>
		<?php if($idkey == ''): ?><a class="QQgroup" target="_blank" href="javascript:void();" style="color:#adadad"><span>QQ 群：</span><?php echo ($qqgroup); ?></a></p>
		<?php else: ?>
		<a class="QQgroup" target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=<?php echo ($idkey); ?>"><span>QQ 群：</span><?php echo ($qqgroup); ?></a></p><?php endif; ?>
</footer>
<div class="main_model_box"></div>
</body>
<script src="/public/mobile/js/jquery.js"></script>
<script src="/public/mobile/js/huosdk_base.js"></script>
<script src="/public/mobile/js/public.js"></script>
<script src="/public/mobile/js/changepwd.js"></script>
</html>