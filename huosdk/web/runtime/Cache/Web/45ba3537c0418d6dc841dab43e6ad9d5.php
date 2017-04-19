<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
<meta name="keywords" content="<?php echo $infodata['name'];?>,<?php echo C('BRAND_NAME');?>手机网游_火爆热门手机游戏下载_返利全网最高"></meta>
<meta name="description" content="<?php echo $infodata['name'];?>,<?php echo C('BRAND_NAME');?>游戏平台(<?php echo WEBSITE;?>)汇集了多款最新最热最火的手机游戏，免费提供下载，是游戏玩家首选的最佳服务平台。"></meta>
<title><?php echo $infodata['name'];?>手游官网|下载__礼包兑换码大全_攻略__激活码_首充号充值返利端全网最高
</title>
<link href="/public/web/css/subtoper.css" rel="stylesheet" type="text/css"></link>
<link href="/public/web/css/subindex.css" rel="stylesheet" type="text/css"></link>
<!--[if lte IE 6]>
<script src="js/DD_belatedPNG_0.0.8a-min.js" language="javascript"></script>
<script type="text/javascript" language="javascript">DD_belatedPNG.fix('div, ul, img, li, input , a');</script>
<![endif]--> 

<script type="text/javascript" src="/public/web/js/jquery-1.js"></script>
<script type="text/javascript" src="/public/js/jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var bgimg = $("#bgimg").val();
		var website = $("#website").val();
		var imgurl = website + "/upload/image/" + bgimg;
	
		$(".ind_con").css("background","url("+imgurl+") center no-repeat");
	});
</script>
</head>
<body>
<input type="hidden" id="bgimg" value="<?php echo ($webdata['banner']); ?>">
<input type="hidden" id="website" value="<?php echo ($gamehost); ?>" >
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
<div class="ind_con">
	<div class="ind_con_c">
		<div class="nav">
			<ul>
				<li class="nav_s1"><a href="<?php echo U('Index/index');?>">1</a></li>
				<li class="nav_s2"><a href="<?php echo U('Web/New/index');?>" target="_blank">1</a></li>
				<li class="nav_s3"><a href="<?php echo U('Game/index');?>" target="_blank">1</a></li>
				<li style=" width:172px;"></li>
				<li class="nav_s4"><a href="<?php echo U('Game/index');?>" target="_blank">1</a></li>
				<li class="nav_s5"><a href="<?php echo U('Web/Server/index',array('item'=>'zhongxin'));?>" target="_blank">1</a></li>
				<li class="nav_s6" style="padding:0;"><a href="<?php echo BBSSITE;?>" target="_blank">1</a></li>
			</ul>
		</div>
		<div style="height:460px;"></div>
		<div class="c_az">
			<div class="lie1">
				<dl>
					<dt><img src="<?php echo WEBSITE.'/upload/code/'.$iosname;?>" height="90" width="90"/></dt>
					<dd><span>版本号：</span><?php echo ((isset($version[$infodata['id']]) && ($version[$infodata['id']] !== ""))?($version[$infodata['id']]):"无"); ?></dd>
					<dd><span>文件大小：</span><?php echo ($infodata['size']); ?></dd>
					<dd><span>适用固件：</span>IOS系统</dd>
					<dd class="c_av">此App专为IOS</dd>
				</dl>
				<div class="clear"></div>
				<?php  if($infodata['iosurl'] == ''){ echo "<a class='ios1' target='_blank' href=''><img src='/public/web/images/iosh.jpg'/></a>"; } else { echo "<a class='ios1' target='_blank' href='".$infodata['iosurl']."'></a>"; } if($infodata['yiosurl'] == ''){ echo "<a class='ios2' target='_blank' href=''><img src='/public/web/images/ann.jpg'/></a>"; }else{ echo "<a class='ios2' target='_blank' href='".$infodata['yiosurl']."'></a>"; } ?>
			</div>
			<div class="lie1">
				<dl>
					<dt><img src="<?php echo WEBSITE.'/upload/code/'.$androidname;?>" height="90" width="90"/></dt>
					<dd><span>版本号：</span><?php echo ((isset($version[$infodata['id']]) && ($version[$infodata['id']] !== ""))?($version[$infodata['id']]):"无"); ?></dd>
					<dd><span>文件大小：</span><?php echo ($infodata['size']); ?></dd>
					<dd><span>适用固件：</span>android系统</dd>
					<dd class="c_av">此App专为android</dd>
				</dl>
				<div class="clear"></div>
				 <a class="android"  href="<?php echo ($infodata['androidurl']); ?>" target="_blank"></a>
			</div>
			<div class="lie2">
				<dl>
					<dt><a href="<?php echo ($webdata['bbs_url']); ?>" target="_blank"><img src="/public/web/images/gwlt.png" border="0" /></a></dt>
					<dd><a href="<?php echo ($webdata['gift_url']); ?>" target="_blank">游戏礼包</a></dd>
					<dd><a href="<?php echo ($webdata['guildurl']); ?>" target="_blank">公会入驻</a></dd>
					<dd><a href="<?php echo ($webdata['tgurl']); ?>" target="_blank">推广福利</a></dd>
					<dd><a href="<?php echo ($webdata['activityurl']); ?>" target="_blank">活动中心</a></dd>
					<dd><a href="<?php echo ($webdata['noviceurl']); ?>" target="_blank">新手引导</a></dd>
					<dd><a href="<?php echo ($webdata['screenshoturl']); ?>" target="_blank">游戏截图</a></dd>
				</dl>
			</div>
		</div>
		<div class="c_d">
			<div class="c_dt"></div>
			<div class="c_dc">
				<div class="c_dd_1">
					<div class="c_dd_a">
						<div id="slideBox" class="slideBox">
							<div class="hd">
								<ul>						
									<li class=""></li>
									<li class=""></li>
									<li class="on"></li>
								</ul>
							</div>
							<div class="bd">
								<ul>
									<li style="display: none;"><a href="<?php echo ($webdata['lboneurl']); ?>" title="" target="_blank"><img src="<?php echo $gamehost."/upload/image/".$arimage[0]?>"></a></li>
									<li style="display: none;"><a href="<?php echo ($webdata['lbtwourl']); ?>" title="" target="_blank"><img src="<?php echo $gamehost."/upload/image/".$arimage[1]?>"></a></li>
									<li style="display: list-item;"><a href="<?php echo ($webdata['lbthreeurl']); ?>" title="" target="_blank"><img src="<?php echo $gamehost."/upload/image/".$arimage[2]?>"></a></li>
								</ul>
							</div>
						</div>
						<script type="text/javascript">jQuery(".slideBox").slide( { mainCell:".bd ul",autoPlay:true} );</script>
					</div>
					<div class="c_dd_b">
						<h2><a href="<?php echo U('Web/News/index');?>" target="_blank">更多>></a></h2>
						<ul>
						<?php if(is_array($newsdata)): $i = 0; $__LIST__ = $newsdata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
						<span>
						<?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?>
						</span>
						<a href="<?php echo U('Web/New/index/show/display' , array('newsid'=>$vo['id']));?>">
						<?php echo ($vo["title"]); ?>
						</a>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
					</div>
					<div class="clear"></div>
				</div>
				<div class="c_dd_2">
					<div class="c_dd_2a">
						<h2></h2>
						<ul>
							
							<li><a href="<?php echo $webdata['glurlone']?>" target="_blank"><img src="<?php echo $gamehost."/upload/image/".$glimage['0']?>" border="0" /></a></li>
							<li><a href="<?php echo $webdata['glurltwo']?>" target="_blank"><img src="<?php echo $gamehost."/upload/image/".$glimage['1']?>" border="0" /></a></li>
							<li><a href="<?php echo $webdata['glurlthree']?>" target="_blank"><img src="<?php echo $gamehost."/upload/image/".$glimage['2']?>" border="0" /></a></li>
							<li><a href="<?php echo $webdata['glurlfour']?>" target="_blank"><img src="<?php echo $gamehost."/upload/image/".$glimage['3']?>" border="0" /></a></li>
						</ul>
					</div>
					<div class="c_dd_2b">
						<h2></h2>
						<ul>
							<li>客服电话：<br /><?php echo ($HOTLINE); ?></li>
							<li>客服QQ：<?php echo ($QQ); ?></li>
							<li><br />客服邮箱：<br /><?php echo ($EMAIL); ?></li>
						</ul>
					</div>
					<div class="c_dd_2c">
						<h2></h2>
						<ul id="scrollText">
							<?php  foreach ($media_data as $key => $val) { echo "<li>"; echo "<a href='".$val['url']."' target='_blank'><img src='".WEBSITE."/upload/image/".$val['icon']."' border='0' /></a>"; echo "</li>"; } ?>
						</ul>
						
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="c_dd"></div>
		</div>
		
	</div>
</div>
	<div class="foot">
		<div class="foot_1"><p>注意自我保护 | 谨防上当受骗 | 适度游戏益脑 | 沉迷游戏伤身 | 合理安排时间 | 享受健康生活</p></div>
	</div>
</div>

<script type="text/javascript">  


 //添加事件响应函数的函数，与本效果无关  
 function addEventSimple(obj,evt,fn){  
 if(obj.addEventListener){  
    obj.addEventListener(evt,fn,false);  
  }else if(obj.attachEvent){  
  obj.attachEvent('on'+evt,fn);  
 }  
 }  
 addEventSimple(window,'load',initScrolling);  
 //保存想要滚动的容器  
 var scrollingBox;  
 var scrollingInterval;  
//用于记录是否“滚到头”过一次  
var reachedBottom=false;  
//记录第一次滚到头时候的scrollTop  
 var bottom;  
 //初始化滚动效果  
  function initScrolling(){  
 scrollingBox = document.getElementById("scrollText");  
  //样式设置，与滚动基本无关，应该用CSS设置。  
  scrollingBox.style.height = "150px";  
  scrollingBox.style.overflow = "hidden";  
  //滚动  
  scrollingInterval = setInterval("scrolling()",50);  
 //鼠标划过停止滚动效果  
  scrollingBox.onmouseover = over;  
 //鼠标划出回复滚动效果  
 scrollingBox.onmouseout = out;   
}  
 //滚动效果  
 function scrolling(){  
  //开始滚动,origin是原来scrollTop  
  var origin = scrollingBox.scrollTop++;  
 //如果到头了  
 if(origin == scrollingBox.scrollTop){  
  //如果是第一次到头  
 if(!reachedBottom){  
   scrollingBox.innerHTML+=scrollingBox.innerHTML;  
   reachedBottom=true;  
   bottom=origin;  
   }else{  
   //已经到头过，只需回复头接尾的效果  
  scrollingBox.scrollTop=bottom;  
 }  
  }  
   }  
 function over(){  
 clearInterval(scrollingInterval);  
}  
function out(){  
scrollingInterval = setInterval("scrolling()",50);  
}  
</script>

</body>
</html>