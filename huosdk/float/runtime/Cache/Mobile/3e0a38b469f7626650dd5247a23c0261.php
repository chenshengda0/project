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
<link rel="stylesheet" href="/public/mobile/css/kefu.css"/>
<div class="kefu">
    <div class="kefuImg">
        <img src="/public/mobile/images/QQ1.jpg"/>
        <p>客服QQ:<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo ($data["qq"]); ?>&amp;site=qq&amp;menu=yes" title="<?php echo ($data["qq"]); ?>"><?php echo ($data["qq"]); ?></a></p>
        <img src="/public/mobile/images/phone1.jpg"/>
        <p>客服电话:<a href="javascript:void();"><?php echo ($data["tel"]); ?></a></p>
    </div>
</div>
<footer class="footer_info">
    <p class="kefu_time">客服时间：<?php echo ($data["service_time"]); ?></p>
    <p>Copyright © 2016 <?php echo DOCDOMAIN;?>, 版权所有</p>
    <p>版权所有：<?php echo C('COMPANY_NAME');?></p>
</footer>

<div class="main_model_box"></div>
</body>
<script src="/public/mobile/js/jquery.js"></script>
<script src="/public/mobile/js/public.js"></script>
<script src="/public/mobile/js/recharge.js"></script>
</html>