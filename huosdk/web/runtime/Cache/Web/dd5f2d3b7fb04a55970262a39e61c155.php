<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="keywords" content="<?php echo ($keywords); ?>"/>
<meta name="description" content="<?php echo ($description); ?>"/>
<title><?php echo ($title); ?></title>

<link href="/public/web/css/index2.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/public/web/js/jquery-1.js"></script>

</head>
<body>
<script type="text/javascript">
	document.domain=<?php echo DOCDOMAIN;?>;
</script>
<!--游戏中心js-->
<script type="text/javascript">
// JavaScript Document
function showNav(down_id, id, cls)
{
	document.getElementById(id).className = cls;
	document.getElementById(down_id).style.display="";
}
function hideNav(down_id, id, cls)
{
	document.getElementById(id).className = cls;
	document.getElementById(down_id).style.display="none";
}

function addfavorite() {
	var url = location.href;
	var title = document.title;
	if (window.sidebar) { // Mozilla Firefox
        window.sidebar.addPanel(title, url, "");
    } else if (window.external && !window.chrome) { // IE
        window.external.AddFavorite(url, title);
    } else if (window.opera && window.print) {
        window.external.AddFavorite(url, title);
    } else {
        alert("加入收藏失败，请使用Ctrl+D进行添加");
    }
}

function setHomePage(){
	var url = '';
	if (document.all) {
		document.body.style.behavior = 'url(#default#homepage)';
		document.body.setHomePage(url);
	}
	else if (window.sidebar) {
		if (window.netscape) {
			try {
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			}
			catch (e) {
				alert("该操作被浏览器拒绝，如果想启用该功能，请在地址栏内输入 about:config,然后将项 signed.applets.codebase_principal_support 值该为true");
			}
		}
		var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
		prefs.setCharPref('browser.startup.homepage', url);
	}else{
		alert("您的浏览器不支持自动设置首页, 请使用浏览器菜单手动设置!");
	}
}
</script>
<script language="javascript" type="text/javascript"> 
$(document).function(){ 
    var name = escape("sdkuser");  
    var allcookies = document.cookie;   
    name += "=";  
    var pos = allcookies.indexOf(name);      
	
    if (pos != -1){                                           
        var start = pos + name.length;                  
        var end = allcookies.indexOf(";",start);    
        if (end == -1) end = allcookies.length;      
        var value = allcookies.substring(start,end); 
		if(value != ''){
			document.getElementById("log").style.display='block';
			document.getElementById("loged").style.display='none';
		
			var div=document.getElementById("divid");  
			var span=document.createElement("span");  
			span.id="spanid";  
			span.innerHTML=value;  
			div.appendChild(span);  
		}
    }else{  
        document.getElementById("loged").style.display='block';
		document.getElementById("log").style.display='none'; 
    }  
}
</script>

<div id="toper">
	<div class="clearfix" id="top">
    	<h1 class="head_left"><?php echo C('BRAND_NAME');?>欢迎您！</h1>
        <ul class="game_link clearfix">
        	<?php if($_SESSION['user']['sdkuser']== ''): ?><div id="loged" style="display:block">
				<li>[<a href="<?php echo U('Login/index');?>" >登录</a>]</li>
        		<li>[<a href="<?php echo U('Register/index');?>" target="_blank">免费注册</a>]</li>
			</div>
			<?php else: ?>
			<div id="log" style="display:block">
				<li>[<a id="divid" href="<?php echo U('Web/User/index','userinfo=info');?>"><?php echo ($_SESSION['user']['sdkuser']); ?></a>]</li>
        		<li>[<a href="<?php echo U('Login/logout');?>">退出</a>]</li>
			</div><?php endif; ?>
        </ul>
    </div>
</div>
<div id="header">
	<div class="clearfix" id="head">
    	<div class="logo"><a href="/index.php"><img alt="手游平台" src="/upload//image/<?php echo ($logo["img"]); ?>"  height="60px" width="320px;"></a></div>
        <div class="nav">
        	<ul class="clearfix">
				<li><a href="<?php echo U('Index/index');?>" class="menu0_on" style="width:65px;"><i class="navIco navIco_1"></i>首页</a></li>
				<li><a href="<?php echo U('Game/index');?>" class="menu1"><i class="navIco navIco_2"></i>游戏中心</a></li>
				<li><a href="<?php echo U('Web/Pay/index');?>" class="menu3" target="_blank"><i class="navIco navIco_4"></i>充值中心</a></li>
				<li><a href="<?php echo U('Web/User/index',array('userinfo'=>'info'));?>" class="menu2"><i class="navIco navIco_3"></i>个人中心</a></li>
				<li><a href="<?php echo U('Web/Server/index',array('item'=>'zhongxin'));?>" class="menu4"><i class="navIco navIco_5"></i>客服中心</a></li>
				<li><a href="<?php echo BBSSITE;?>"  target="_blank" class="menu5"><i class="navIco navIco_6"></i>玩家社区</a></li>
            </ul>
        </div>
    </div>
</div>

	<div class="gg_game_hu">
		<div class="ggs_xx"></div>
		
</div>
<div id="middle">
	<div class="content mt">
		<div class="user_box">
	<script type="text/javascript" src="<?php echo ($WEBSITE); ?>/js/regformchk3.js"></script>
	<script type="text/javascript" src="<?php echo ($WEBSITE); ?>/js/regformchk2.js"></script>
	<script type="text/javascript" src="<?php echo ($WEBSITE); ?>/js/user.js"></script>
    <div class="cont_l">
	  <div class="user_box_l">
	    <div class="user_box_nav">用户中心</div>
		<div class="user_box_list">
		  <ul>
			
			<li><img src="/public/web/images/icon_user.gif" /> <a href="<?php echo U('Web/User/index',array('userinfo'=>'info'));?>" >个人资料</a></li>
			<!--<li><img src="images/icon_cart.gif" /> <a href="#">充值明细</a></li>
			<li><img src="/public/web/images/icon_jl.gif" /> <a href="<?php echo U('Web/User/index',array('userinfo'=>'paylog'));?>">充值记录</a></li>
			<li><img src="/public/web/images/icon_jl.gif" /> <a href="<?php echo U('Web/User/index',array('userinfo'=>'xiaofei'));?>">消费记录</a></li>
			<li><img src="/public/web/images/icon_games.gif" /> <a href="<?php echo U('Web/User/index',array('userinfo'=>'mygame'));?>">我的游戏</a></li>-->
			<li><span class="user_on"> <a href="<?php echo U('Web/User/index',array('userinfo'=>'update'));?>"  class="orange3"><em>></em>修改密码</a></span></li>
			<li><img src="/public/web/images/icon_bz.gif" /> <a href="<?php echo U('Web/User/index',array('userinfo'=>'secret'));?>">设置密保</a></li>
			<!-- <li><img src="/public/web/images/icon_tiwen.gif"/> <a href="<?php echo U('Web/User/index',array('userinfo'=>'myask'));?>" >我的提问</a></li>-->
		  </ul>
		</div>
	  </div>
	</div>

	<div class="cont_r">
	  <div class="user_r_box">
	    <div class="user_r_box_nav">修改密码</div>
	    <form method="post" action="<?php echo U('Web/User/registerinfo',array('action'=>'updatepwd'));?>" id="myform" name="myform">
		<div class="user_r_box_main">
		  <div class="user_ti"><b>温馨提示：</b>请尽量让你的密码保持在中到高的安排级别！</div>
		  		  <div class="user_r_box_list">
		    <ul>
		       <!-- 密码 开始 -->
			   <li></li>
                <div class="clearfix yw_mreg_item">
                    <div class="yw_mreg_label">原来密码：</div>
                    <div class="yw_mreg_int" style="position: relative">
                        <input class="new_txt" onFocus="" type='password' maxLength=16 name="old_pwd" id="old_pwd" autocomplete="off"> 
                        
                    </div>
					</div>

				<!-- 密码 开始 -->
			    <div class="clearfix yw_mreg_item">
                    <div class="yw_mreg_label">输入新密码：</div>
                    <div class="yw_mreg_int" style="position: relative">
                        <input class="new_txt" onblur=ChkPwd(); onFocus="jQuery('#pwdspan').show();" type='password' maxLength=16 name="usrpwd" id="usrpwd" autocomplete="off"> 
                    </div><!-- 验证信息 -->
                    <div class=yw_mreg_info>
                        <div class=default id='pwdspan' style="DISPLAY: none">6-16位的字符!建议使用大小写字母和数字混合</div>
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

			</ul>
		  </div>
		  <div class="clear"></div>
		  <div class="user_r_box_line"></div>
		  <div class="user_r_box_tj">
		  <input type="submit" id="update_pwd" value="确定" class="que_btn">
		  <!--
		    <ul>
			  <li><a href="#" class="que_btn">确 定</a></li>
			</ul>
		  -->
		  </div>
		  <div class="clear"></div>
		</div>
		 <input type="hidden" name="action" value="updatepwd">
		</form>
	  </div>
	</div>
  </div>
	</div>
	<div class="clear"></div>
	
</div>
<div class="footer">
    <div class="content">
        <div class="footer-col c1">
            <div class="ico">&nbsp;</div>
            <div class="ls">
                <h3>热门游戏</h3>
                <ul>
				
                <?php if(is_array($footgamelist)): $k = 0; $__LIST__ = $footgamelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($k == 0 ): ?><li><a target="_blank" href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></li><?php endif; ?>
					<?php if($k == 1 ): ?><li><a target="_blank" href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></li><?php endif; ?>
					<?php if($k == 2 ): ?><li><a target="_blank" href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></li><?php endif; ?>
					<?php if($k == 3 ): ?><li><a target="_blank" href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>     
              </ul>
            </div>
        </div>
        <div class="footer-col c2">
            <div class="ico">&nbsp;</div>
            <div class="ls">
                <h3>玩家服务</h3>
                <ul>
                    <li><a target="_blank" href="<?php echo U('Server/index',array('item'=>'zhongxin'));?>">客服首页</a></li>
                    <li><a target="_blank" href="<?php echo U('Server/index',array('item'=>'tiwen'));?>">我要提问</a></li>
                    <li><a target="_blank" href="<?php echo U('Server/index',array('item'=>'question'));?>">常见问题</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-col c3">
            <div class="ico">&nbsp;</div>
            <div class="ls">
                <h3>充值服务</h3>
                <ul>
                     <li><a target="_blank" href="<?php echo U('Web/Pay/index');?>">支付宝</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-col c5">
            
        </div>
		<div class="clear"></div>
    </div>
</div>
<div class="footer_d">
    <div style="padding-top:25px;">
        <p style=" padding-bottom:10px;"><a href="<?php echo U('AboutUs/index',array('show'=>'us'));?>">关于我们</a>  |  <a href="<?php echo U('AboutUs/index',array('show'=>'hezuo'));?>">商务合作</a>  |  <a href="<?php echo U('AboutUs/index',array('show'=>'zhaopin'));?>">人才招聘</a>  |  <a href="<?php echo U('AboutUs/index',array('show'=>'lianxi'));?>">联系我们</a></p>
        <p>健康游戏忠告：抵制不良游戏 拒绝盗版游戏 注意自我保护 谨防受骗上当 适度游戏益脑 沉迷游戏伤身 合理安排时间 享受健康生活<br />
    <a href="http://www.miitbeian.gov.cn"></a>&nbsp;&nbsp;<?php echo C('COMPANY_NAME');?>版权所有</p>
    </div>
</div>
</body>
</html>