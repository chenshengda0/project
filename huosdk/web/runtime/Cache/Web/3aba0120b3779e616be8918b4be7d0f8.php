<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="<?php echo ($keywords); ?>"/>
<meta name="description" content="<?php echo ($description); ?>"/>
<title>充值中心_<?php echo C('BRAND_NAME');?>手游联运平台</title>
<link href="/public/pay/css/toper.css" rel="stylesheet" type="text/css">
<link href="/public/pay/css/pay.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" charset="UTF-8" href="/public/pay/css/style2.css" type="text/css">
<!--[if lte IE 6]>
<script src="js/DD_belatedPNG_0.0.8a-min.js" language="javascript"></script>
<script type="text/javascript" language="javascript">DD_belatedPNG.fix('div, ul, img, li, input , a');</script>
<![endif]--> 
<script type="text/javascript" src="/public/pay/js/jquery.js"></script>
</head>
<body>

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
	} else if (window.sidebar) {
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

<div id="toper">
	<div class="clearfix" id="top">
    	<h1 class="head_left"><?php echo C('BRAND_NAME');?>欢迎您！</h1>
            <ul class="game_link clearfix">
            	<?php if($_SESSION['user']['sdkuser']== '' ): ?><li>[<a href="<?php echo U('Login/index');?>">登录</a>]</li>
                    <li>[<a href="<?php echo U('Register/index');?>">免费注册</a>]</li>
                <?php else: ?>
                	<li><?php echo ($_SESSION['user']['sdkuser']); ?></li>
                    <li><a href="<?php echo U('Web/User/index',array('userinfo'=>'info'));?>">进入用户中心</a></li>
                    <li><a href="<?php echo U('Login/logout');?>">退出</a></li><?php endif; ?>
            </ul>
            <ul class="menu_right clearfix">
            	<li><a rel="sidebar" href="javascript:addfavorite();">加入收藏</a></li>
                <!--<li><a rel="nofollow" href="#">保存到桌面</a></li>-->
                <li style="margin-left:10px;">
                	<div onmouseout="hideNav('game_down', 'drop_game_href', 'game');" onmouseover="showNav('game_down', 'drop_game_href', 'game_h');" class="game_center clearfix">
						<a rel="nofollow" href="<?php echo U('Game/index');?>" target="_blank" class="game" id="drop_game_href">游戏中心</a>
						<div style="display:none;" id="game_down">
							<div class="game_center_top"></div>
							
						</div>
               	 	</div>
                </li>
           </ul>
    </div>
</div>
<div id="header">
	<div class="clearfix" id="head">
    	<div class="logo"><a href="<?php echo WEBSITE;?>"><img alt="手游平台" src="/upload//image/<?php echo ($logo["img"]); ?>" width="320px;"></a></div>
        <div class="nav">
        	<ul class="clearfix">
				<li><a href="<?php echo WEBSITE;?>" class="menu0_on" style="width:65px;"><i class="navIco navIco_1"></i>首页</a></li>
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
	<div class="wrap"> 
		<ul id="left_menu" class="w_left">
			<?php if($paytypeid == 3): ?><li class="left_li left_li_state_on">
					<span class="left_li_span"><a href="<?php echo U('Web/Pay/index');?>">支付宝</a></span>
				</li>
			<?php else: ?>
				<li class="left_li">
					<span class="left_li_span"><a href="<?php echo U('Web/Pay/index');?>">支付宝</a></span>
				</li><?php endif; ?>
			
			<?php if($paytypeid == 19): ?><li class="left_li left_li_state_on">
					<span class="left_li_span"><a href="<?php echo U('Web/Pay/xqtpay');?>">微信</a></span>
				</li>
			<?php else: ?>
				<li class="left_li">
					<span class="left_li_span"><a href="<?php echo U('Web/Pay/xqtpay');?>">微信</a></span>
				</li><?php endif; ?>
			
			<?php if($paytypeid == 16): ?><li class="left_li left_li_state_on">
					<span class="left_li_span"><a href="<?php echo U('Web/Pay/shenzhoufu');?>">充值卡</a></span>
				</li>
			<?php else: ?>
				<li class="left_li">
					<span class="left_li_span"><a href="<?php echo U('Web/Pay/shenzhoufu');?>">充值卡</a></span>
				</li><?php endif; ?>
		</ul>
	<div class="main">
        <div id="main_payFor">
            <div class="main_title">充值到哪里:</div>
			<p><?php echo C('CURRENCY_NAME');?>是<?php echo C('BRAND_NAME');?>游戏平台发行的虚拟商品，游戏中的元宝钻石等商品可使用<?php echo C('CURRENCY_NAME');?>兑换 兑换比例1元=10 <?php echo C('CURRENCY_NAME');?>
            <div class="main_payFor">
                <a id="pay_for_platform" val="platform" class="main_payFor_a on">充值到<?php echo C('CURRENCY_NAME');?></a>
                <!--
                <a id="pay_for_game" val="game" class="main_payFor_a ">默认充值到游戏</a>
                -->
            </div>
        </div>
        <div id="main_ptNotice" class="main_ptNotice">
            什么是<?php echo C('CURRENCY_NAME');?>？<a target="_blank" href="<?php echo U('Web/Pay/payBi');?>">[查看详情]------<?php echo C('CURRENCY_NAME');?>兑换比例 1元=10 <?php echo C('CURRENCY_NAME');?></a>
        </div>
            
        <div id="main_user" class="main_title main_user">
            
            <div class="main_user_input" style="display: block;">
                <span>充值帐号:</span><input type="text" value="<?php echo ($_SESSION['user']['sdkuser']); ?>" id="username" class="user_input" name="username"><span class="change_user" id="change_user">[请填写账号]</span>
				<input id="userPay" name="userPay"  type="hidden" value="<?php echo U('Web/Pay/userTtb');?>" />
				<input id="website" name="website"  type="hidden" value="<?php echo ($website); ?>" />
            </div>
        </div>
        <div id="main_money">
            <div class="main_title">选择金额: 
                <span style="display:none; color:red;" id="paltform_coin_balance">您的帐户还有0<?php echo C('CURRENCY_NAME');?></span>
            </div>
				
				<div id="main_money_list" class="main_money">
					<a id="money_0" val="10" class="main_money_a">10元</a>
					<a id="money_1" val="30" class="main_money_a">30元</a>
					<a id="money_2" val="50" class="main_money_a">50元</a>
					<a id="money_3" val="100" class="main_money_a on">100元</a>
					<a id="money_4" val="200" class="main_money_a">200元</a>
					<a id="money_5" val="500" class="main_money_a">500元</a>
					<a id="money_6" val="1000" class="main_money_a">1000元</a>
					<a id="money_7" val="2000" class="main_money_a">2000元</a>
					<a id="money_8" val="5000" class="main_money_a">5000元</a>
					<a id="money_9" val="10000" class="main_money_a">10000元</a>
				</div>
				<div id="main_other_money" class="main_other_money">
                	<a id="money_9999"></a>
                    <label for="other_money">其他金额:</label>
                    <input type="text" value="" id="other_money" class="other_money" name="other_money">
                    <span id="money_unit">元</span>
                </div>
                <input type="hidden" id="moneyrate" value="<?php echo ($moneyrate[$paytypeid]["rate"]); ?>">
                <input type="hidden" id="money" value="<?php echo ($moneyrate[$paytypeid]["money"]); ?>">
				<div class="main_money_convert">对应<span id="coin_name"><?php echo C('CURRENCY_NAME');?></span>数量:<span id="money_convert"></span>&nbsp;&nbsp;&nbsp;<span id="zengsong" style="display:none;">赠送<span id="give"></span><span>个<?php echo C('CURRENCY_NAME');?></span></span>
                <!--</div>
                <div id="cz_ptNotice" class="main_ptNotice" style="display:none;">详细充值返利说明<a target="_blank" href="<?php echo ($WEBSITE); ?>/template/html/news_119.html">[查看详情]</a>
        		</div> -->
                
			</div>
            <input type="hidden" id="orderid" value="<?php echo ($orderid); ?>">
            <input type="hidden" id="url" value="<?php echo ($url); ?>">
            <input type="hidden" id="paytypeid" value="<?php echo ($paytypeid); ?>">
            <input type="hidden" id="paytype" value="<?php echo ($payarr[$paytypeid]); ?>">
			<div class="main_confirm"><a id="pay_submit" href="javascript:void(0)" onclick="check_sub();" ></a></div>
            
			<div class="main_explain"><?php echo ($_content); ?></div>
			
		</div>
        
		<div style="clear"></div>
		
	</div>
	<div class="clear"></div>
	
</div>
<div class="footer">
    <div class="content">
        <div class="footer-col c1">
            <div class="ico">&nbsp;</div>
            <div class="ls">
                <h3>热门游戏</h3>
             	 <?php if(is_array($footgamelist)): $k = 0; $__LIST__ = $footgamelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($k == 0 ): ?><li><a target="_blank" href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></li><?php endif; ?>
                    <?php if($k == 1 ): ?><li><a target="_blank" href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></li><?php endif; ?>
                    <?php if($k == 2 ): ?><li><a target="_blank" href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></li><?php endif; ?>
                    <?php if($k == 3 ): ?><li><a target="_blank" href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>     

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
        <!--<div class="footer-col c5">-->
           <!-- <p class="weibo">
                <span>官方微信</span>
            </p>-->
            <!--<p style="position:relative"><img width="100" height="100" alt="官方微信" src="images/update/wx.jpg"></p>-->
            <!--<p style="margin-top: 2px;" class="weixin">扫一扫 有惊喜 !</p>-->
        <!--</div>-->
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


<div id="light" class="white_content float_div">
<form name="alertform" id="alertform" method="post" target="_blank" action="">
	<div id="alert_confirm" class="alert_confirm" style="display: block;">
    	<div class="alert_confirm_top">充值信息确认<a class="ui_close" href="javascript:void(0)" onclick="document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'" >x</a>
        </div>
    	<div class="alert_confirm_con">
      		<div class="alert_confirm_content">
                <div class="alert_confirm_title">请确认您的充值信息:</div>
                <dl class="alert_confirm_dl">
                    <dt>您的充值方式：</dt>
                    <dd id="confirm_pay_type_name"></dd>
                    <!-- 
                    <dt>您的订单号：</dt>
                    <dd id="confirm_order_id"></dd>
                     -->
                    <dt>您的充值帐号：</dt>
                    <dd id="confirm_username"></dd>
                    <dt>您的充值金额(元)：</dt>
                    <dd id="confirm_money"></dd>
                    <dt>您将获得<?php echo C('CURRENCY_NAME');?>(个)：</dt>
                    <dd id="confirm_coin"></dd>
                </dl>
				<div class="alert_confirm_link"> 
                	<a id="confirm_submit" class="alert_confirm_link_a">确认提交</a> 
                    <a class="alert_confirm_link_a" onclick="close_floatdiv($('#light'));">返回修改</a> 
        		</div>
			</div>
    	</div>
    	<div class="alert_confirm_fot"></div>
	</div>
    <input type="hidden" id="com_username" name="username" value="">
    <input type="hidden" id="com_orderid" name="orderid" value="">
    <input type="hidden" id="com_amount" name="amount" value="">  
    <input type="hidden" id="com_ttb" name="ttb" value="">
    <input type="hidden" id="com_zhifucode" name="zhifucode" value="">
    <input type="hidden" id="com_paytypeid" name="paytypeid" value="">
</form>
</div>

<div id="payafter" class="white_content float_div">
	<div id="after_confirm" class="alert_confirm2" style="display: block;">
    	<div class="alert_confirm_top">提示<a class="ui_close" href="javascript:void(0)" onclick="document.getElementById('payafter').style.display='none';document.getElementById('fade').style.display='none'" >x</a>
        </div>
    	<div class="alert_confirm_con2">
      		<div class="alert_confirm_content">
                <div class="alert_confirm_title">请确认您的充值信息:</div>
                <dl class="alert_confirm_dl">
                    付款完成前请不要关闭或刷新此窗口。<br>
                    付款完成后请到个人中心或SDK查看<?php echo C('CURRENCY_NAME');?>余额。
                </dl>
				<div class="alert_confirm_link"> 
                	<a href="<?php echo U('Web/User/index/userinfo/info');?>" class="alert_confirm_link_a">返回个人中心</a> 
                    <a href="<?php echo U('Web/Server/index/item/zhongxin');?>" class="alert_confirm_link_a">联系客服</a> 
        		</div>
			</div>
    	</div>
    	<div class="alert_confirm_fot"></div>
	</div>
</div>

<div id="fade" class="black_overlay"></div> 

<script type="text/javascript" src="/public/pay/js/pay.js" charset="UTF-8"></script>
</body>
</html>