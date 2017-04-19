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


<script type="text/javascript" src="/public/web/js/regformchk3.js"></script>
<script type="text/javascript" src="/public/web/js/regformchk2.js"></script>
<script type="text/javascript" src="/public/web/js/user.js"></script>
<script>
    var registerInfo = "<?php echo U('Register/checkRegisterInfo');?>";
</script>
<div class="main">
  <div class="reg_box">
    <div class="reg_box_l">
	  <div class="reg_main">
	    <div class="reg_main_nav">用户注册</div>
		<div class="reg_tishi"><b>温馨提示：</b>如果已在<?php echo C('BRAND_NAME');?>手机端注册了<?php echo C('BRAND_NAME');?>账号，无需再注册，可直接登录！</div>
			<form name="myform" id="myform" action="<?php echo U('Register/register');?>" method="post" >
			<div class="regmain">
                <!-- //帐号 开始 -->
                <div class="clearfix yw_mreg_item">
                    <div class=yw_mreg_label>帐 号：</div>
                    <div class=yw_mreg_int>
                        <input class="new_txt" id="username"  onblur=checkUserName(); alt="disableEnter" onkeyup=Chkcn(this); onFocus="jQuery('#unspan').show();" name="username" autocomplete="off">
                    </div>
        
                    <div class="yw_mreg_info">
                        <div class="default" id="unspan" style="display: none">6~32位数字或字母或 _作为通行证账号</div>
                    </div> 
                </div>
                <!-- //帐号 结束 -->
    
                <!-- 密码 开始 -->
                <div class="clearfix yw_mreg_item">
                    <div class="yw_mreg_label">密 码：</div>
                    <div class="yw_mreg_int" style="position: relative">
                        <input class="new_txt" onblur=ChkPwd(); onFocus="jQuery('#pwdspan').show();" type=password maxLength=16 name="password" id="usrpwd" autocomplete="off"> <!-- 密码强度与原要玩相同 -->
                        <div style="MARGIN-TOP: 5px; RIGHT: 12px; POSITION: absolute; TOP: 0px; _margin-top: 5px">
                            <TABLE cellSpacing=0 cellPadding=0 border=0>
                                <TBODY>
                                    <TR style="TEXT-ALIGN: center">
                                    <TD id=psl1 style="BORDER-RIGHT: #fff 1px solid; WIDTH: 5px; HEIGHT: 20px" bgColor="#e0e0e0"></TD>
                                    <TD id=psl2 style="BORDER-RIGHT: #fff 1px solid; WIDTH: 5px" bgColor="#e0e0e0"></TD>
                                    <TD id=psl3 style="BORDER-RIGHT: #fff 1px solid; WIDTH: 5px" bgColor="#e0e0e0"></TD></TR>
                                </TBODY>
                            </TABLE>
                        </div><!-- //密码强度 与原要玩相同 -->
                    </div><!-- 验证信息 -->
                    <div class=yw_mreg_info>
                        <div class=default id=pwdspan style="DISPLAY: none">6-16位的字符!建议使用大小写字母和数字混合</div>
                    </div><!-- //验证信息 --></div>
                <!-- //密码 结束 -->
                    
                <!-- 确认密码 开始 -->
                <div class="clearfix yw_mreg_item">
    
                    <div class=yw_mreg_label>确认密码：</div>
                    <div class=yw_mreg_int>
                        <input class=new_txt id="usrpwdc" onblur=ChkPwdc(); onfocus="jQuery('#pwdcspan').show();" type=password name="usrpwdc" autocomplete="off"> 
                    </div>
    
                    <!-- 验证信息 -->
                    <div class=yw_mreg_info>
                        <div class=default id=pwdcspan style="DISPLAY: none">再次输入密码，确保密码输入正确。</div>
                    </div>
                    <!-- //验证信息 -->
    
                </div>
                <!-- //确认密码 结束 -->

				<!-- //昵称 开始 -->
                <div class="clearfix yw_mreg_item">
                    <div class=yw_mreg_label>昵称：</div>
                    <div class=yw_mreg_int><INPUT class=new_txt id=nickname  onblur="checkNickname();" onFocus="jQuery('#nicknamespan').show();" name="nickname" autocomplete="off"> 
                    </div>
                    <!-- 验证信息 -->
    
                    <div class=yw_mreg_info>
                        <div class=default id=nicknamespan style="DISPLAY: none">昵称由3~15个字符组成。</div>
                    </div><!-- //验证信息 -->  
                </div>
                <!-- //昵称 结束 -->

                <!-- //邮件 开始 -->
                <div class="clearfix yw_mreg_item">
                    <div class=yw_mreg_label>邮 件：</div>
                    <div class=yw_mreg_int><INPUT class=new_txt id=email  onblur="checkEmail();" onFocus="jQuery('#emailspan').show();" name="email"
    autocomplete="off"> 
                    </div>
                    <!-- 验证信息 -->
    
                    <div class=yw_mreg_info>
                        <div class=default id=emailspan style="DISPLAY: none">6~16位的数字或字母作为通行证账号。</div>
                    </div><!-- //验证信息 -->  
                </div>
                <!-- //邮件 结束 -->
    
                <!-- 验证码 开始 -->
                <div class="clearfix yw_mreg_item">
                    <div class=yw_mreg_label>验证码：</div>
                    <div class=yw_mrlog_int>
                        <input class="d3log_yzm" id="verifycode" maxLength=4 name=verifycode autocomplete="off"  onblur="checkVerifycode()"   > 
                        <img src="/Web/Register/verify" id="codeimg" width="70" height="30" onclick='this.src="/Web/Register/verify?id="+Math.random();'>
                        <span><a href="javascript::void()" class="blue_line"  id="verifycode_change"  onClick="$('#codeimg').attr('src','/Web/Register/verify?id='+Math.random());return false;">换一张</a></span> 
                    </div>
                    <!-- 验证信息 -->
                    <div class=yw_mreg_info>
                        <div class=default id=codespan style="DISPLAY: none">请正确输入验证码。</div>
                    </div><!-- //验证信息 -->
                </div>
                <!-- //验证码 结束 -->
    
                <div class="check clearfix" style="padding-left:115px;">
                    <input type="checkbox" id="agreen" checked="checked" name="agreen" value="1" /> <span class="gray">我已看过并同意<a href="<?php echo U('Register/agreement');?>" class="blue2"  target="_blank"   >《<?php echo C('BRAND_NAME');?>网站服务使用协议》</a></span> 
                </div>
                <div class="reg_line"></div>
                <div class="reg_b"><input type="button" value="立即注册" id="register_btn" ></div>
                
			</div>
            <input type="hidden" name="website" id="website" value="<?php echo ($WEBSITE); ?>">
            <input type="hidden" name="action" value="register">
		</form>
	  	</div>
	</div>
	<div class="reg_box_m"></div>
		<div class="reg_box_r">
	  		<div class="reg_login">
	    		<div class="reg_login_nav">已有<?php echo C('BRAND_NAME');?>账号，登录</div>
					<div class="reg_login_list">
						<form action="<?php echo U('Web/Login/login');?>" method="post" id="login_form" >
		  					<ul>
                                <li><label>用户名：</label><input type="text" name="username" id="login_username" class="login_input"/></li>
                                <li><label>密码：</label><input type="password" name="password" id="login_password" class="login_input"/></li>
                                <li><label></label><div class="login_s"><a href="javascript:void()" id="login_btn" ><img src="/public/web/images/login_btn.gif" border="0" /></a></div>
                                <a href="<?php echo U('Web/Findpwd/index', array('show'=>findpwd));?>" class="blue_line"  style="line-height:24px;">找回密码</a></li>
		  					</ul>
                            <input type="hidden" name="action" value="login">
						</form>
					</div>
				<div class="clear"></div>
				<div class="login_line"></div>
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