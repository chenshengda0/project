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

<div class="mode">
         <div>已绑定方式</div>
        <div class="bandWay">
            <div class="sj">
                <div>手机<span class="blue"><?php echo ($userdata["mobile"]); ?></span></div>
                <div>
                    <div class="ybd show">
                        <a href="#"><img src="/public/float/images/forward.png"></a>
                    </div>
                    <div class="wbd">
                        <span>未绑定</span>
                    </div>
                </div>
            </div>
            <div class="yx">
                <div>邮箱<span class="blue"><?php echo ($userdata["email"]); ?></span></div>
                <div>
                    <div class="ybd show">
                        <a href="#"><img src="/public/float/images/forward.png"></a>
                    </div>
                    <div class="wbd show">
                        <span>未绑定</span>
                    </div>
                </div>
            </div>

        </div>

         <div><a href="<?php echo U('Help/index');?>"  class="blue">点此联系客服</a></div>
</div>
</body>
<script src="/public/float/js/main.js"></script>
<script>
    var divs=document.querySelectorAll(".bandWay>div");
    for(var i=0;i < divs.length;i++){
        divs[i].onclick=function(){
            if(this.className=="sj"){
                if(document.querySelectorAll(".bandWay>div:first-child>div:first-child>span")[0].innerHTML){
                    window.location.href="<?php echo U('Binding/mobile_verify');?>";
                }else{
                    window.location.href="<?php echo U('Binding/mobile_set');?>";
                }
            }else if(this.className=="yx"){
                window.location.href="set_yx.html";
                if(document.querySelectorAll(".bandWay>div:nth-child(2)>div:first-child>span")[0].innerHTML){
                    window.location.href="<?php echo U('Binding/email_verify');?>";
                }else{
                    window.location.href="<?php echo U('Binding/email_set');?>";
                }
            }
        }
    }
    window.onload=function(){
        var div1=document.querySelectorAll(".bandWay>div>div:first-child>span");
        for(var i=0;i<div1.length;i++){
            if(div1[i].innerHTML){
                div1[i].parentNode.nextElementSibling.lastElementChild.style.display="none"
            }
        }

    }
</script>
 </html>