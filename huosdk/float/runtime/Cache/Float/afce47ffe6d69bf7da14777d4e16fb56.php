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

<div class="service">
    <div class="service0">
           <div><span>游戏客服</span></div>
           <div>
                <ul>
                       <li>客服QQ:<?php echo ($data["qq"]); ?></li>
                       <li>客服电话：<?php echo ($data["tel"]); ?></li>
                       <li>玩家QQ群：<?php echo ($data["qqgroup"]); ?></li>
                       <li>客服时间：早上9：00-晚上18：00（周一到周五）</li>
                </ul>
        </div>
      </div>
      <div class="service1">
               <ul>
                      <li>Copyright © 2016 <?php echo DOCDOMAIN;?>, 版权所有</li>
                      <li>版权所有：<?php echo C('COMPANY_NAME');?></li>
               </ul>
      </div>
</div>
</body>
</html>