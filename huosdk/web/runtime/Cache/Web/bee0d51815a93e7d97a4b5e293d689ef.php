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


<link type="text/css" href="/public/web/css/style.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="/public/web/js/regformchk3.js"></script>
<script type="text/javascript" src="/public/web/js/regformchk2.js"></script>
<div class="main">
  <div class="login_box">
    <div class="login_box_l">
	  <div class="denglu_l">
	    <div class="denglu_l_main">
		 <div class="you_l">
		<div id="example">
			<div id="slides">
				<div class="slides_container">
					<div class="slide">
						<a><img src="/upload//image/<?php echo ($splash["img"]); ?>" /></a></div>
				</div>
                
				<!--a href="#" class="prev"><img src="/public/web/images/pre.gif" /></a>
				<a href="#" class="next"><img src="/public/web/images/next.gif" /></a-->
            </div>

		</div>
	    </div>
		  <div class="you_r">
		    <h1><?php echo C('BRAND_NAME');?></h1>
			<p><?php echo C('BRAND_NAME');?>由安卓手机客户端、IOS版及wap版组成，涵盖了角色扮演、战争策略、模拟经营、休闲竞技及动作角色扮演等,让各类玩家都能在<?php echo C('BRAND_NAME');?>平台找到适合的游戏!</p>

		  </div>
		</div>
	  </div>
	</div>
	<div class="login_box_r">
	  <div class="denglu_r">
		<div class="denglu_r_main">
		<form name="myform" id="login_form" action="<?php echo U('Login/login');?>" method="post" >
		<div class="reg_login_list">
		  <ul>
		    <li><label>用户名：</label><input type="text" id="login_username" name="username" value="<?php echo ($username); ?>"  class="login_input" maxlength="20" autocomplete="off"/>  </li>
			<li><label>密码：</label><input type="password" id="login_password" name="password"  class="login_input"
			 value="<?php echo ($password); ?>" autocomplete="off"/></li>
			<li><span id="nameMsg" style="display: none;color: red;">账号必须在6-20位之间，只能包含字母、数字!</span></li>
			<li><label></label><div class="login_s">
			<input type="submit" value="登录" id="login_btn"/>
			</div>
			<a href="<?php echo U('Findpwd/Index',array('show'=>findpwd));?>" class="blue_line"  style="line-height:24px;">找回密码</a></li>
		  </ul>
		</div>
        <input type="hidden" name="action" value="login">
		</form>
		<div class="clear"></div>
		<?php if($isshow == '1'): ?><div id="light" class="white_content" style="display:block">
			<form name="nickform" id="nickform" action="<?php echo ($WEBSITE); ?>/user/login.php" method="post" >
			  <div class="text"><h2> 激活论坛帐号</h2><a href="javascript:void(0)" onclick="document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none';document.getElementById('lusername').value='';document.getElementById('lpassword').value='';" class="ui_close">x</a></div>
				<div class="download_main">
					<div class="r-dialog-panel" style="display: block;">
						<?php if($nickname == ''): ?><p><label for="rr-username" class="r-right"><span class="star">*</span>论坛昵称:</label><input
						type="text" onclick="checkNickname();" onFocus="jQuery('#nicknamespan').show();" name="nickname" 
						 id="nickname" class="r-dialog-text r-dialog-text-error"  autocomplete="off">
						 </p>
							<p class=default id="nicknamespan" style="DISPLAY: none;color:#F00">昵称由3~15个字符组成。</p>
						<?php else: ?> <input type="hidden" name="nickname" value="<?php echo ($nickname); ?>"><?php endif; ?>
						<?php if($email == ''): ?><p><label for="rr-username" class="r-right"><span class="star">*</span>邮箱:</label><input
							type="text"  onblur="checkEmail();" onFocus="jQuery('#nicknamespan').show();" name="email" 
							 id="email"  class="r-dialog-text r-dialog-text-error"  autocomplete="off">
							 </p>
								<p class=default id="emailspan" style="DISPLAY: none;color:#F00"></p>
						<?php else: ?> <input type="hidden" name="email" value="<?php echo ($email); ?>"><?php endif; ?>
						<p class="ac"><input type="button" value="登录论坛" id="login_ltn" class="btn btn-s-2"/></p></div>
				</div>
				 <input type="hidden" name="website" id="website" value="<?php echo U('Index/index');?>">
				 <input type="hidden" name="skip" id="skip" value="luntan">
				 <input type="hidden" id="lusername" name="lusername" value="<?php echo ($username); ?>">
				 <input type="hidden" id="lpassword" name="lpassword" value="<?php echo ($password); ?>">
				 <input type="hidden" name="action" value="login">
		</form>
			</div><?php endif; ?>

		<div class="login_line"></div>
		<div class="reg_login_b">
		  <div class="clear"></div>
		  <p style="line-height:36px;">您还没有<?php echo C('BRAND_NAME');?>账号，<a href="<?php echo U('Web/Register/index');?>" class="blue_line" style="font-size:14px; font-weight:bold;">立即注册</a></p>
		  </div>
	  </div>
	</div>
  </div>
  <div class="clear"></div>
</div></div>
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