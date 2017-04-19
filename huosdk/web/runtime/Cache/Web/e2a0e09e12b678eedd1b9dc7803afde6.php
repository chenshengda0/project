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
<link href="/public/web/css/gift.css" rel="stylesheet" />
<script type="text/javascript" src="/public/web/js/jquery-1.js"></script>

<script language="javascript" type="text/javascript"> 
		function closeW(){
			  $("#fechPopBox").hide();
			  $("#r-cover").hide();
			  //如果领号成功了且号只运行领一次，关闭提示框的时候，刷新当前页面
			  var sceneSole = "1";
			  var	getSuccess	= true;
			  if(getSuccess && sceneSole == "1") {
				  window.location.reload();
			  }
			  
			  if(bookSuccess) {
				  window.location.reload();
			  }
		}
		function linqu(infoid){
			 var web = $('#website').val();
			 var image = $('#public').val();
			  var giftid = infoid;
				$.ajax({
						  type: 'POST',
						  url: web,
						  data:{giftid:giftid},
						  error:function(d,t){
							alert('网络错误');
						  },
						  success: function(data){
							  var resJson = $.parseJSON($.trim(data));
							  var code = resJson.a;
								if(code.length > 2){						
									 var htmlStr = "<div class='tanchu'><span class='tanchu_nav'>领取礼包成功</span>";
										htmlStr += "<img src='"+image+"/web/images/open_no_03.gif' onclick='closeW();' alt='' />";
										htmlStr += "<div class='hehe'><p class='p1'>领取成功</p><p class='p2'>";
										htmlStr += "序列号:<div style='text-align:center'>"+code+"</div></p></div></div>";
									 
									  $("#fetch_con").html(htmlStr);
									  $("#fechPopBox").show();
									  $("#r-cover").show();
								}else if (code == 2){
									alert('请先登录');
								}else if (code == 3){
									alert('礼包已经发放完');
								}else if (code == 1){
									alert('该礼包您已领取');
								}
						  }
				});
		}
		
</script>

<div class="gg_game">
	<div class="ggs_xx"></div>
	<div id="banner_game">
		<a href="#" target="_blank"><img
			src="/public/web/images/game_1.jpg" alt="<?php echo ($BRAND_NAME); ?>"></a>
	</div>
</div>

<div id="middle">
	<div class="content2 mt">
		<div class="contp_tit">
			<ul id="tags">
				<li class="selectTag"><a
					href="<?php echo U('Web/Gift/index');?>">礼包中心</a></li>
			</ul>
		</div>
		<div class="gift_container">
			<div class="gift_nav">
				<ul>
					<li class="nav_h1">游戏名称：</li>
					<li class="search">
						<form method="post" action="<?php echo U('Web/Gift/index');?>">
							<input id="gamename" name="gamename" type="text" class="text"
								value="" /> <input type="submit" value="" class="submit" />
						</form>
					</li>
				</ul>
			</div>
			<input id="public" type="hidden" value="/public">
			<input id="website" type="hidden" value="<?php echo U('Web/Gift/pcajax');?>">
			<div class="gift_list">
				<?php if(is_array($giftlist)): $i = 0; $__LIST__ = $giftlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul>
						<li class="list_img"><img class="bg"
							src="/public/web/images/hot.png" alt="" /><img class="top"
							src="/upload//image/<?php echo ($vo["icon"]); ?>" alt="" /></li>
						<li class="list_comtainer">
							<p class="h_title"><?php echo ($vo["title"]); ?></p>
							<p>礼包内容：</p>
							<p><?php echo ($vo["content"]); ?></p>
						</li>
						<li class="list_botton"><label>剩余：<span class="span"><?php echo ($vo["gsum"]); ?></span></label>
							<a id="getLink" class="get" onclick="linqu(<?php echo ($vo["id"]); ?>)"></a>
						</li>
						<li class="time"><span><?php echo (date("Y-m-d H:i:s",$vo["start_time"])); ?>-<?php echo (date("Y-m-d H:i:s",$vo["end_time"])); ?></span>
							<a href="<?php echo ($vo["androidurl"]); ?>" class="download"></a> <a
							href="<?php echo ($vo["url"]); ?>" class="injoly"></a></li>
					</ul><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
			<div class="mnbv"><?php echo ($showpage); ?></div>
		</div>
	</div>
</div>
<div class="clear"></div>
</div>
</div>
<div id="fechPopBox" style="display: none" class="bgcolor">
	<div id="fetch_con"></div>

</div>
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