<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="keywords" content="<?php echo ($keywords); ?>"/>
<meta name="description" content="<?php echo ($description); ?>"/>
<title>个人中心_<?php echo C('BRAND_NAME');?>手游联运平台</title>
<link href="/public/web/css/index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/public/web/js/jquery-1.js"></script>
<script type="text/javascript" src="/public/web/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/public/web/js/slider.js"></script>
<script type="text/javascript" src="/public/web/js/slides.js"></script>
</head>
<body>
<div class="top_nav">
	<div class="top_nav_main" id="top_nav_main"> 
    	<div class="top_nav_main_l">欢迎来到<?php echo C('BRAND_NAME');?>

        <?php if($_SESSION['user']['sdkuser']== ''): ?><a href="<?php echo U('Login/index');?>">登录</a>  <a href="<?php echo U('Register/index');?>">注册</a> 
        <?php else: ?>
            <span class="orange"><?php echo ($_SESSION['user']['sdkuser']); ?></span>&nbsp;&nbsp;<a href="<?php echo U('Web/User/index',array('userinfo'=>'info'));?>" class="blue_line">进入用户中心</a>&nbsp;&nbsp;<a href="<?php echo U('Login/index'),array('action'=>'loginout');?>" class="blue_line">退出</a><?php endif; ?>

        </div>
    </div>
  	<div class="clear"></div>
</div>
<div class="top">
	<div class="top_l"><a href="<?php echo WEBSITE;?>"><img src="/upload//image/<?php echo ($logo["img"]); ?>"></a></div>
    	<div class="top_r">
            <div class="nav">
                <ul>
                    <li><a href="<?php echo U('Web/Index/index');?>" >首   页</a></li>
                    <li><a href="<?php echo U('Web/Game/index');?>"  >游戏大全</a></li>
                    <li><a href="<?php echo U('Web/Pay/index');?>" target="_blank">充值中心</a></li>
                    <li><a href="<?php echo U('Web/User/index?userinfo=info');?>">个人中心</a></li>
                    <li><a href="<?php echo U('Web/Server/index?item=zhongxin');?>">客服中心</a></li>
                    <li><a href="<?php echo BBSSITE;?>" target="_blank">手游社区</a></li>
                </ul>
            </div>
        </div>
</div>
<div class="clear"></div>


<div class="clear"></div>
  <div class="main">
    <div class="helpbox">
	  <div class="cont_l">
	    <div class="about_l">
		  <div class="about_nav">关于<?php echo C('BRAND_NAME');?></div>
		  <div class="about_l_list">
		    <ul>
		    <ul>
			  <li><a href="<?php echo U('AboutUs/index',array('show'=>'us'));?>" >关于我们</a></li>
			  <li><a href="<?php echo U('AboutUs/index',array('show'=>'hezuo'));?>">商务合作</a></li>
			  <li><a href="<?php echo U('AboutUs/index',array('show'=>'zhaopin'));?>">人才招聘</a></li>
			  <li><a href="<?php echo U('AboutUs/index',array('show'=>'lianxi'));?>" class="orange">联系我们</a></li>
			</ul>
			</ul>
		  </div>
		</div>
	  </div>
	  <div class="cont_r">
	    <div class="about_r">
		  <div class="about_r_nav"><?php echo ($data["title"]); ?></div>
		  <div class="about_r_word"><?php echo ($data["content"]); ?></div>
		</div>
	  </div>
	</div> 
   </div>
<div class="clear"></div>
<div class="footer">
	<div style="padding-top:25px;">
		<p style=" padding-bottom:10px;"><a href="<?php echo U('AboutUs/index',array('show'=>'us'));?>">关于我们</a>  |  <a href="<?php echo U('AboutUs/index',array('show'=>'hezuo'));?>">商务合作</a>  |  <a href="<?php echo U('AboutUs/index',array('show'=>'zhaopin'));?>">人才招聘</a>  |  <a href="<?php echo U('AboutUs/index',array('show'=>'lianxi'));?>">联系我们</a></p>
		<p>健康游戏忠告：抵制不良游戏 拒绝盗版游戏 注意自我保护 谨防受骗上当 适度游戏益脑 沉迷游戏伤身 合理安排时间 享受健康生活<br />
	<a href="http://www.miitbeian.gov.cn"></a>&nbsp;&nbsp;<?php echo C('COMPANY_NAME');?>版权所有</p>
	</div>
	
	

<script type="text/javascript">
function closes(id){
	document.getElementById(id).style.display='none';
}
</script>

</p>
</div>

<!--[if IE 6]>
<style type="text/css">
* html #weixin{position:absolute;
right:expression(eval(document.documentElement.scrollLeft+document.documentElement.clientWidth
-this.offsetWidth)-(parseInt(this.currentStyle.marginLeft,10)||0)-(parseInt(this.currentStyle.marginRight,10)||0));
top:expression(eval(document.documentElement.scrollTop+document.documentElement.clientHeight-this.offsetHeight
-(parseInt(this.currentStyle.marginTop,10)||0)-(parseInt(this.currentStyle.marginBottom,10)||0)))}
</style>
<![endif]-->


</body>
</html>