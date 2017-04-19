<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo C('BRAND_NAME');?></title>

<link href="/public/web/css/findpwd.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/public/web/js/jquery-1.js"></script>
<script type="text/javascript" src="/public/web/js/regformchk3.js"></script>
<script type="text/javascript" src="/public/web/js/findpwd.js"></script>
<script>
	var checkusername = "<?php echo U('Web/Findpwd/checkUserName');?>";
</script>
</head>

<body>
<div class="top_nav">
  <div class="top_nav_main">
		<div class="top_nav_main_l">欢迎来到<?php echo C('BRAND_NAME');?>
		<?php if($_SESSION['user']['sdkuser']== ''): ?><a href="<?php echo U('Login/index');?>">登录</a>  <a href="<?php echo U('Register/index');?>">注册</a>
		<?php else: ?>
		<span class="orange"><?php echo ($_SESSION['user']['sdkuser']); ?></span>&nbsp;&nbsp;
		<a href="<?php echo U('Web/User/index','userinfo=info');?>" class="blue_line">进入用户中心</a>&nbsp;&nbsp;
		<a href="<?php echo U('Login/logout');?>" class="blue_line">退出</a><?php endif; ?>
		</div>
    </div>
  <div class="clear"></div>
</div>
<div class="top">
  <div class="top_l"><a href="/index.php"><img src="/upload//image/<?php echo ($logo["img"]); ?>" /></a></div>
</div>
<div class="clear"></div>
<form name="myform" id="myform" action="<?php echo U('Findpwd/index');?>" method="post" >
<div class="main">
  <div class="main c" id="main">

            <div class="s-guide">
                <div class="s-step focus">
                    <span>1</span>
                    <p>确认帐号</p>
                </div>
                <div class="s-step">
                    <span>2</span>
                    <p>邮箱验证</p>
                </div>
                <div class="s-step">
                    <span>3</span>
                    <p>重置密码</p>
                </div>
                <div class="s-step">
                    <span>4</span>
                    <p>完成</p>
                </div>
            </div>

            <div class="s-panel">

                <div class="s-panel-1 s-p show">
                    <p>
                        <label for="login-account">确认帐号：</label>
                        <!--<input type="text" class="text" id="login-account" data-message="请输入帐号" data-rule="required" name="username">-->
						<input class="text" id="username" alt="disableEnter" name="username" autocomplete="off">
						<div id="message"></div>                
                    </p>
				</div>
                    <input type="hidden" name="website" id="website" value="<?php echo WEBSITE;?>">
					<input type="hidden" name="show" value="two">
					<p class="ac"><input class="btn btn-s-1" data-order="1" type="submit" name="bmt" id ="findpwd_one" value="下一步"></p>
                </div>
				
            </div>
        </div>
</div>
</form>
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