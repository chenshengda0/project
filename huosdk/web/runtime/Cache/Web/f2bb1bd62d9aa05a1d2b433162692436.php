<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<script type="text/javascript" src="/public/web/js/yoka.js"></script>

<!-- 开服 -->
<script type="text/javascript">
    $(document).ready(function(){
        // 切换
        $('.newSer_tabItem').each(function(i){
            $(this).bind('click', function(){
                $(this).addClass('sOn').siblings('.newSer_tabItem').removeClass('sOn');
                $('.newSer_tabCont').eq(i).show().siblings('.newSer_tabCont').hide();
                return false;
            });
        });
        $('.newSer_tabContItem').bind('mouseover', function(){
            var $ul = $(this).closest('ul');
            $ul.find('.sInfo').show();
            $ul.find('.sOpen').hide();
            $(this).find('.sOpen').show();
            $(this).find('.sInfo').hide();
            return false;
        });
        // 分页
        Pager({perRecords: 6, pageItemCls:'.pageItem_new', paginationCls: '.pagnation_new'});
        Pager({perRecords: 6, pageItemCls:'.pageItem_open', paginationCls: '.pagnation_open'});
    });

	
    // 简单分页函数
    var Pager = function(config){
        return new Pager.fn.init(config);
    };
    Pager.fn = Pager.prototype = {
        options: {
            currentPage: 1,
            perRecords: 3,
            pageItemCls: '.pageItem',
            paginationCls: '.pagination',
            triggerType: 'click'
        },
        init: function(config) {
            var that = this;
            config = config || {};
            $.extend(true, this.options, config);
            this.currentPage = this.options.currentPage;
            this.perRecords  = this.options.perRecords;
            this.pageItem    = $(this.options.pageItemCls);
            this.pagination  = $(this.options.paginationCls);
            this.allRecords  = this.pageItem.length;
            this.triggerType = this.options.triggerType;

            if (this.allRecords) {
                this.pagination.undelegate('.pagePrev', this.triggerType).delegate('.pagePrev', this.triggerType, function(){
                    if (!$(this).hasClass('disabled')) {
                        that.goPage(that.currentPage - 1);
                    }
                    return false;
                }).undelegate('.pageNext', this.triggerType).delegate('.pageNext', this.triggerType, function(){
                    if (!$(this).hasClass('disabled')) {
                        that.goPage(that.currentPage + 1);
                    }
                    return false;
                }).undelegate('.pageNum', this.triggerType).delegate('.pageNum', this.triggerType, function(){
                    var pageNum = parseInt($(this).attr('data-page'));
                    that.goPage(pageNum);
                    return false;
                }).html(this.getLink());

                this.goPage(this.currentPage);
            } else {
                $('.pagePrev').addClass('disabled');
                $('.pageNext').addClass('disabled');
            }
        },
        getStartIndex: function() {
            return (this.currentPage - 1) * this.perRecords;
        },
        getEndIndex: function() {
            return this.currentPage * this.perRecords - 1;
        },
        hasNext: function() {
            return this.currentPage < this.getAllPage();
        },
        getAllPage: function() {
            return Math.ceil(this.allRecords/this.perRecords);
        },
        hasPrevious: function() {
            return this.currentPage > 1;
        },
        getLink: function() {
            var tplArr = [];
            // 上一页
            if (this.hasPrevious()) {
                tplArr.push('<a class="prev pagePrev" href="javascript:void(0)">上一页</a>');
            } else {
                tplArr.push('<a class="prev pagePrev disabled" href="javascript:void(0)">上一页</a>');
            }

            // 分页数
            var allPage = this.getAllPage();
            for (var i=1; i<=allPage; i++) {
                if (i == this.currentPage) {
                    tplArr.push('    <a class="pageNum pageOn currPage" href="javascript:void(0)" data-page="' + i + '">' + i + '</a>');
                } else {
                    tplArr.push('    <a class="pageNum" href="javascript:void(0)" data-page="' + i + '">' + i + '</a>');
                }
            }

            // 下一页
            if (this.hasNext()) {
                tplArr.push('<a class="next pageNext" href="javascript:void(0)">下一页</a>');
            } else {
                tplArr.push('<a class="next pageNext disabled" href="javascript:void(0)">下一页</a>');
            }

            return tplArr.join('');
        },
        goPage: function(pageNum) {
            this.currentPage = pageNum;
            this.pageItem.removeClass('pageItemShow');
            this.pageItem.slice(this.getStartIndex(), this.getEndIndex()+1).addClass('pageItemShow');
            this.pageItem.eq(this.getStartIndex()).trigger('mouseover');

            // 上一页
            if (this.hasPrevious()) {
                $('.pagePrev').removeClass('disabled');
            } else {
                $('.pagePrev').addClass('disabled');
            }
            // 下一页
            if (this.hasNext()) {
                $('.pageNext').removeClass('disabled')
            } else {
                $('.pageNext').addClass('disabled')
            }

            this.pagination.html(this.getLink());
        }
    };
    Pager.fn.init.prototype = Pager.fn;
</script>

	<div id="gg" class="gg">
		
		<div id="banner">
			<div class="lanrentuku">

			<div class="ggs">
				<div class="ggBox" id="ggBox">

				<div id="bd1lfimg">
					<div style="margin-left:-5px;">
					<dl><dt></dt></dl>
					<?php if(is_array($indexdata)): foreach($indexdata as $key=>$vo): ?><dl>
					  <dt><a href="<?php echo ($vo[url]); ?>" target="_blank"><img src="/upload//image/<?php echo ($vo[img]); ?>" alt="<?php echo C('BRAND_NAME');?>"></a></dt>
				  	</dl><?php endforeach; endif; ?>
										<dl><dt></dt><dd></dd></dl>
										</div>
									</div>
				</div></div>
				<div id="bd1lfsj"><ul></ul></div>
			</div>

			<script>
			movec('<?php echo ($indexdata[1][title]); ?>','<?php echo ($indexdata[2][title]); ?>',
			'<?php echo ($indexdata[3][title]); ?>','<?php echo ($indexdata[4][title]); ?>');
			</script>
	</div>
</div>

<!-- 中间部分开始 -->
<div id="middle">
	<div class="content mt">
		<div class="ct_left">
			<div class="ct_1">
				<div class="ct_1a">
					<a href="#" target="_blank"><img src="/upload//image/<?php echo ($inner["img"]); ?>" alt="<?php echo C('BRAND_NAME');?>" width="246" height="240" ></a>
				</div>
				<div class="ct_1b">
					<div class="text">
						<span style="display: ;" id="more_taba_1"><a href="<?php echo U('Web/New/index');?>" target="_blank">更多>></a></span>
						<span id="more_taba_2" style="display: none;"><a href="<?php echo U('Web/New/index');?>" target="_blank">更多>></a></span> 
						<div>
							<a href="<?php echo U('Web/New/index');?>" class="hover" id="taba1" onMouseOver="setTab('taba',1,2)">新闻公告</a>
							<a href="<?php echo U('Web/New/index');?>" id="taba2" onMouseOver="setTab('taba',2,2)">活动公告</a>
						</div>
					</div>
					<ul id="con_taba_1">
						<?php if(is_array($newslist)): $i = 0; $__LIST__ = $newslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
							<span><?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?></span>
							<?php if($vo['html'] == 1): ?><a href="<?php echo ($WEBSITE); ?>/template/html/news_<?php echo ($vo["id"]); ?>.html"><?php echo ($vo["title"]); ?></a>
							<?php else: ?>
								<a href="<?php echo U('Web/New/index',array('show'=>display,'newsid'=>$vo['id']));?>"><?php echo ($vo["title"]); ?></a><?php endif; ?>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
					<ul id="con_taba_2" style="display: none ;" >
						<?php if(is_array($hdlist)): $i = 0; $__LIST__ = $hdlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
							<span><?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?></span>
							<?php if($vo['html'] == 1): ?><a href="<?php echo ($WEBSITE); ?>/template/html/news_<?php echo ($vo["id"]); ?>.html"><?php echo ($vo["title"]); ?></a>
							<?php else: ?>
								<a href="<?php echo U('Web/New/index',array('show'=>display,'newsid'=>$vo['id']));?>"><?php echo ($vo["title"]); ?></a><?php endif; ?>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
          			</ul>


				</div>
				<!-- 热门游戏开始 -->
				<div class="ct_rmtj">
					<div class="text"><h2><b>热</b>门游戏</h2><span style="position:absolute; right:0;top:15px;"><a href="<?php echo U('Web/Game/index');?>">更多>></a></span></div>
					<div class="pic-list clearfix">
					
					<?php if(is_array($gamelist)): $k = 0; $__LIST__ = array_slice($gamelist,0,4,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($k-1 == 0): ?><a class="new " target="_blank" href="<?php echo ($vo["url"]); ?>"> 
							<strong></strong>
							<em> 
								<img src="/upload//image/<?php echo ($vo["bigimage"]); ?>" alt="<?php echo ($vo["name"]); ?>">
							</em> 
							<span> 
								<b><?php echo ($vo["name"]); ?></b><i><?php echo ($vo["publicity"]); ?></i>
							</span>
						</a><?php endif; ?>
						<?php if($k-1 == 1): ?><a class="new " target="_blank" href="<?php echo ($vo["url"]); ?>"> 
							<strong></strong>
							<em> 
								<img src="/upload//image/<?php echo ($vo["bigimage"]); ?>" alt="<?php echo ($vo["name"]); ?>">
							</em> 
							<span> 
								<b><?php echo ($vo["name"]); ?></b><i><?php echo ($vo["publicity"]); ?></i>
							</span>
						</a><?php endif; ?>
						<?php if($k-1 == 2): ?><a class="new " target="_blank" href="<?php echo ($vo["url"]); ?>"> 
							<strong></strong>
							<em> 
								<img src="/upload//image/<?php echo ($vo["bigimage"]); ?>" alt="<?php echo ($vo["name"]); ?>">
							</em> 
							<span> 
								<b><?php echo ($vo["name"]); ?></b><i><?php echo ($vo["publicity"]); ?></i>
							</span>
						</a><?php endif; ?>
						<?php if($k-1 == 3): ?><a class="new last" target="_blank" href="<?php echo ($vo["url"]); ?>"> 
							<strong></strong>
							<em> 
								<img src="/upload//image/<?php echo ($vo["bigimage"]); ?>" alt="<?php echo ($vo["name"]); ?>">
							</em> 
							<span> 
								<b><?php echo ($vo["name"]); ?></b><i><?php echo ($vo["publicity"]); ?></i>
							</span>
						</a><?php endif; endforeach; endif; else: echo "" ;endif; ?>	
						
					</div>
					<div class="clear"></div>
					<div class="hr">
						<a href="<?php echo U('Game/index');?>" class="up"></a>
					</div>
				</div>
				<!-- 热门游戏结束 -->


				<!-- 搜索游戏开始 -->
				<div class="ct_rmtj1">
					<div class="text"><h2><b>游</b>戏大全</h2></div>
					<div class="pic-list clearfix">
						<div style="" class="game-all">
							<div class="game-all-search">
						        <p style="position: relative;" id="_types" class="game-all-style">
						            <span class="ico">游戏类型</span>
									<input type="hidden" class="gamedata" id="gamedata" value='<?php echo ($data); ?>'> 
									<?php if(is_array($gametypelist)): $i = 0; $__LIST__ = $gametypelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a onClick="showGamesByType('<?php echo ($vo["name"]); ?>',this)" href="javascript:;"><?php echo ($vo["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
									<a onClick="showGamesByType('0',this)" href="javascript:;">全部</a>
									<span style="display: none;" id="search-result" class="search-result">	
						                  <a style="display: none;" data="" href="javascript:;" id="search-result-none">没有结果</a>
						            </span>
						        </p>
						        <p id="_letters" class="game-all-letter">
						            <span class="ico">按首字母</span>
			                        <a onClick="showGamesByLetter('A',this);" href="javascript:void(0);">A</a>
			                        <a onClick="showGamesByLetter('B',this);" href="javascript:void(0);">B</a>
			                        <a onClick="showGamesByLetter('C',this);" href="javascript:void(0);">C</a>
			                        <a onClick="showGamesByLetter('D',this);" href="javascript:void(0);">D</a>
			                        <a onClick="showGamesByLetter('E',this);" href="javascript:void(0);">E</a>
			                        <a onClick="showGamesByLetter('F',this);" href="javascript:void(0);">F</a>
			                        <a onClick="showGamesByLetter('G',this);" href="javascript:void(0);">G</a>
			                        <a onClick="showGamesByLetter('H',this);" href="javascript:void(0);">H</a>
			                        <a onClick="showGamesByLetter('I',this);" href="javascript:void(0);">I</a>
			                        <a onClick="showGamesByLetter('J',this);" href="javascript:void(0);">J</a>
			                        <a onClick="showGamesByLetter('K',this);" href="javascript:void(0);">K</a>
			                        <a onClick="showGamesByLetter('L',this);" href="javascript:void(0);">L</a>
			                        <a onClick="showGamesByLetter('M',this);" href="javascript:void(0);">M</a>
			                        <a onClick="showGamesByLetter('N',this);" href="javascript:void(0);">N</a>
			                        <a onClick="showGamesByLetter('O',this);" href="javascript:void(0);">O</a>
			                        <a onClick="showGamesByLetter('P',this);" href="javascript:void(0);">P</a>
			                        <a onClick="showGamesByLetter('Q',this);" href="javascript:void(0);">Q</a>
			                        <a onClick="showGamesByLetter('R',this);" href="javascript:void(0);">R</a>
			                        <a onClick="showGamesByLetter('S',this);" href="javascript:void(0);">S</a>
			                        <a onClick="showGamesByLetter('T',this);" href="javascript:void(0);">T</a>
			                        <a onClick="showGamesByLetter('U',this);" href="javascript:void(0);">U</a>
			                        <a onClick="showGamesByLetter('V',this);" href="javascript:void(0);">V</a>
			                        <a onClick="showGamesByLetter('W',this);" href="javascript:void(0);">W</a>
			                        <a onClick="showGamesByLetter('X',this);" href="javascript:void(0);">X</a>
			                        <a onClick="showGamesByLetter('Y',this);" href="javascript:void(0);">Y</a>
			                        <a onClick="showGamesByLetter('Z',this);" href="javascript:void(0);">Z</a>
						        </p>
		    				</div>

							<!-- 显示所有图片开始 -->
							<div id="_allgames_list" class="game-all-con">
							
							<?php if(is_array($gamelist)): $i = 0; $__LIST__ = array_slice($gamelist,0,21,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 6 );++$i; if(($mod) == "0"): ?><div class="game-all-item  right" value="<?php echo ($vo["ucfirst"]); ?>" type="<?php echo ($vo["typename"]); ?>">
						            <p>
										<?php if($vo["url"] == ''): ?><a href="<?php echo U('Web/Game/index',array('tzgid'=>$vo.id));?>" target="_blank">
												<img width="80" height="80" src="/upload//image/<?php echo ($vo["mobile_icon"]); ?>" alt="<?php echo ($vo["name"]); ?>">
											</a>
										<?php else: ?>
											<a href="<?php echo ($vo["url"]); ?>" target="_blank">
												<img width="80" height="80" src="/upload//image/<?php echo ($vo["mobile_icon"]); ?>" alt="<?php echo ($vo["name"]); ?>">
											</a><?php endif; ?>
									</p>
						            <p>
										<?php if($vo["url"] == ''): ?><a href="<?php echo U('Web/Game/index',array('tzgid'=>$vo.id));?>" target="_blank"><?php echo ($vo["name"]); ?></a>
										<?php else: ?>
											<a href="<?php echo ($vo["url"]); ?>" target="_blank"><?php echo ($vo["name"]); ?></a><?php endif; ?>
									</p>
						        </div>
							<?php else: ?>
						        <div class="game-all-item left " value="<?php echo ($vo["ucfirst"]); ?>" type="<?php echo ($vo["typename"]); ?>">
									<p>
										<?php if($vo["url"] == ''): ?><a href="<?php echo U('Web/Game/index',array('tzgid'=>$vo.id));?>" target="_blank">
												<img width="80" height="80" src="/upload//image/<?php echo ($vo["mobile_icon"]); ?>" alt="<?php echo ($vo["name"]); ?>">
											</a>
										<?php else: ?>
											<a href="<?php echo ($vo["url"]); ?>" target="_blank">
												<img width="80" height="80" src="/upload//image/<?php echo ($vo["mobile_icon"]); ?>" alt="<?php echo ($vo["name"]); ?>">
											</a><?php endif; ?>
									</p>
						            <p>
										<?php if($vo["url"] == ''): ?><a href="<?php echo U('Web/Game/index',array('tzgid'=>$vo.id));?>" target="_blank"><?php echo ($vo["name"]); ?></a>
										<?php else: ?>
											<a href="<?php echo ($vo["url"]); ?>" target="_blank"><?php echo ($vo["name"]); ?></a><?php endif; ?>
									</p>
						        </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
						         <div class="clear"></div>
						    </div>

							<!-- 显示所有图片结束 -->
					
						</div>
					</div>
				</div>
				<!-- 搜索游戏结束 -->
			</div>
		</div>
		

		
		<!-- 右边信息开始 -->
		<div class="ct_right">
			<div class="ct_1c">
				<a href="<?php echo ($guanggao["url"]); ?>" target="_blank"><img src="/upload//image/<?php echo ($guanggao["img"]); ?>" height="167" width="167" alt="<?php echo C('BRAND_NAME');?>" border="0"></a>
				<h2><a href="<?php echo ($guanggao["url"]); ?>" target="_blank"><?php echo ($guanggao["title"]); ?></a></h2>
		   </div>
		   <div class="mod-kf mt20">
				<div class="hd">最新开服</div>
				<div class="th "><h2>游戏开服信息</h2><span><a href="<?php echo U('Web/Gift/index');?>" target="_blank">礼包中心</a></span></div>
				<div class="tb">
					<div class="newSer_tabCont">
						<ul class="clearfix">
							
							<?php if(is_array($serverlist)): $i = 0; $__LIST__ = array_slice($serverlist,0,12,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="newSer_tabContItem pageItem_new pageItemShow">
								<div style="display: none;" class="sInfo">
									<span class="gName"><?php echo (mb_substr($vo["game"],0,4,'utf-8')); ?></span><span class="gSer"><?php echo (mb_substr($vo["sername"],0,6,'utf-8')); ?></span><em><?php echo (date("m-d H:i",$vo["start_time"])); ?></em>					</div>
								<dl class="sOpen" style="display: block;">
									<dt><img width="40" height="40" src="/upload//image/<?php echo ($vo["image"]); ?>" alt="<?php echo (mb_substr($vo["game"],0,6,'utf-8')); ?>"></dt>
									<dd>
										<p>
											<a target="_blank" class="gName" href="<?php echo ($vo["url"]); ?>"><?php echo (mb_substr($vo["game"],0,4,'utf-8')); ?></a>
											<a target="_blank" class="gGift" href="<?php echo U('Web/Gift/index',array('gameid'=>$vo['app_id']));?>">礼</a>
											<a target="_blank" class="gInner" href="<?php echo ($vo["androidurl"]); ?>">立即下载</a>							</p>
										<p>
											<span class="gSer"><?php echo (mb_substr($vo["sername"],0,6,'utf-8')); ?></span>
											<em><?php echo (date("m-d H:i",$vo["start_time"])); ?></em>							</p>
									</dd>
								</dl>
							</li><?php endforeach; endif; else: echo "" ;endif; ?>
						
						</ul>
						<div class="pagnation pagnation_new pa2">
							<a href="javascript:void(0)" class="prev pagePrev disabled">上一页</a>    
							<a data-page="1" href="javascript:void(0)" class="pageNum pageOn currPage">1</a>    
							<a data-page="2" href="javascript:void(0)" class="pageNum">2</a>    
							<a data-page="3" href="javascript:void(0)" class="pageNum">3</a>
							<a href="javascript:void(0)" class="next pageNext disabled">下一页</a>
						</div>
					</div>
				</div>
		    </div>
		    <div class="mod-kf mt20">
				<div class="hd2">客服专区 </div>
				<div class="tb">
					<div class="kef">
						<dl>
							<dt><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($QQ); ?>&site=qq&menu=yes" target="_blank"><img src="/public/web/images/kehu.jpg" border="0" alt="<?php echo C('BRAND_NAME');?>" /></a>7x24</dt>
							<dd>有什么问题客服来帮您</dd>
							<div><span style="padding-top:6px;">客服电话：</span>
							<span style="color:#f6620a; font-size:14px; font-weight:bold;"><?php echo ($HOTLINE); ?></span></div>

							<div><span style="padding-top:6px;">客服QQ：</span>
							<span style="color:#f6620a; font-size:14px; font-weight:bold;"><?php echo ($QQ); ?></span></div>
							<dd>未成年人家长监护工程</dd>
							<dd></dd>
						</dl>
					</div>
				</div>
		    </div>
		    <div class="mod-kf mt20">
				<div class="hd2">合作媒体 </div>
				<div class="tb">
					<div class="hez">
						<div id="scrollText" style="clear:both; overflow:hidden; height:202px;width:209px;margin:0 auto;">  
							
							<?php if(is_array($media)): $i = 0; $__LIST__ = $media;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p><a href="<?php echo ($vo["url"]); ?>" target="_blank"><img src="/upload//image/<?php echo ($vo["icon"]); ?>" alt="<?php echo C('BRAND_NAME');?>"/></a></p><?php endforeach; endif; else: echo "" ;endif; ?>
						</div> 
					</div>
				</div>
		    </div>
		</div>
		<!-- 右边信息结束 -->

		<div class="clear"></div>
		
		<!-- 友情链接开始 -->
		<div class="in_yq">
			<div class="content">
		        <div class="linktop">
		            <p>友情链接</p>
		        </div>
		        <div class="link_con">
		         	<?php if(is_array($friendlink)): $i = 0; $__LIST__ = $friendlink;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a target="_blank" href="<?php echo ($vo["link_url"]); ?>"><?php echo ($vo["link_name"]); ?></a>|<?php endforeach; endif; else: echo "" ;endif; ?>
		        </div>
	    	</div>
		</div>
		<!-- 友情链接结束 -->
	</div>
</div>
<!-- 中间部分结束 -->

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


<script type="text/javascript">
    $(function(){
        $(".game-fire-item").hover(function(){
            $(this).find(".btns").show();
        },function(){
            $(this).find(".btns").hide();
        });
    });
</script>
<script type="text/javascript">
    var g_letter = '0',
        g_type = '0';
    var isNeedRendFocus = true,
        initFieldValue = '',
        startIndex = 0,
        curFocusIndex = 0,
        curFocusItem = null,
        selRes = [];
		
    var gamearr =  $('#gamedata').val();
    //alert(gamearr);
	//var gameJsons = eval("("+gamearr+")");
	var gameJsons = JSON.parse(gamearr);
	//var _lenght = gamearr.length;
	//alert(gameJsons[1]['ucfirst']);
	//alert(gameJsons[0]['ucfirst']);
	//alert(gameJsons.length);
	var gameLength = gameJsons.length;
    var isAll = 0;

    function showGamesByType(type,ele) {
        $('#_types').find('a').removeClass('hover');
        $(ele).addClass('hover');
        $('#_letters').find('a').removeClass('hover');
        if(type == 0) {
            fillGame( searchGameByOrd() );
        } else {
        	
            fillGame( searchGameByType(type) );
        }
    }

    function showGamesByLetter(letter,ele) {
        $('#_letters').find('a').removeClass('hover');
        $(ele).addClass('hover');
        $('#_types').find('a').removeClass('hover');
        fillGame( searchGameByLetter(letter) );
    }

    function selectGame(game) {
        $('#search-field').val(game.text());
        $('#search-result').hide();
        setTimeout(function(){isNeedRendFocus=true;},1000)
        var data = game.attr('data');
        fillGame( searchGameByTag(data.substr(0,data.indexOf('|'))) );
    }

    function fillGame(games){

        var gl = games.length;
        var html = '';
        for(var i=0; i<gl; i++) {

            if(!isAll && i>=28) break;
            var extClass = i%7 == 0 ? 'left' : ((i+1)%7==0 ? 'right' : '');
            html += '<div type="'+games[i]['type']+'" value="'+games[i]['ucfirst']+'" class="game-all-item '+extClass+'">';
            html += '<p><a href="'+games[i]['link']+'"><img alt="'+games[i]['name']+'" width="80" height="80" src="'+games[i]['pic']+'"/></a></p>';
            html += '<p><a href="'+games[i]['link']+'">'+games[i]['name']+'</a></p>';
            html += '</div>';
        }
        html += '<div class="clear"></div>';
        $('#_allgames_list').html(html);
    }

    function searchGameByTag(tag) {
        var srGame = [];
        for(var i=0; i<gameLength; i++) {
            if(gameJsons[i]['tag'] == tag) {
                srGame.push(gameJsons[i]);
            }
        }
        return srGame;
    }

    function searchGameByLetter(letter) {
        var srGame = [];
		
        for(var i=0; i<gameLength; i++) {
            if(gameJsons[i]['ucfirst'] == letter) {
                srGame.push(gameJsons[i]);
            }
        }
        return srGame;
    }

    function searchGameByType(type) {
        var srGame = [];
        
        for(var i=0; i<gameLength; i++) {
        	//alert('aaa'+gameJsons[i]['type']);
            if(gameJsons[i]['type'] == type) {
                srGame.push(gameJsons[i]);
            }
        }
        return srGame;
    }
    function searchGameByOrd() {
        return gameJsons;
    }

$(function(){
    var firstKey = true;
    $('#search-field').focus(function(){
        if($('#search-field').val() == '输入您要找的游戏') {
            $('#search-field').val('');
        }
    });
    $('#search-field').blur(function(){
        setTimeout(function(){
            $('#search-result').hide();
        }, 250);
    });

    $('#search-result').find('a').bind('click',function(event){
        if($(this).attr('id') != 'search-result-none') {
            isNeedRendFocus = false;
            selectGame($(this));
        }
        event.preventDefault();
    });

    $(document).keydown(function(e){
        if(startIndex < 1) {
            return ;
        }
        curFocusItem = selRes[curFocusIndex];
        if (e.keyCode == 40 || e.keyCode == 39) {
            if (curFocusIndex == startIndex - 1) {
                curFocusIndex = 0;
            } else {
                curFocusIndex ++;
            }
            curFocusItem.removeClass('focus');
            selRes[curFocusIndex].addClass('focus');
            curFocusItem =  selRes[curFocusIndex];
        } else if (e.keyCode == 38 || e.keyCode == 37 ) {
            if (curFocusIndex == 0) {
                curFocusIndex = startIndex - 1;
            } else {
                curFocusIndex --;
            }
            curFocusItem.removeClass('focus');
            selRes[curFocusIndex].addClass('focus');
            curFocusItem =  selRes[curFocusIndex];
        }
        if(e.keyCode == 13) {
            isNeedRendFocus = false;
            selectGame(selRes[curFocusIndex]);
        }
    });

    $('#search-field').keyup(function(e) {
        var searchText =  $('#search-field').val();
        if(searchText != initFieldValue) {
            initFieldValue = searchText;
            selRes = [];
            startIndex = 0;
            curFocusIndex = 0;
            curFocusItem = null;
        }else{
            return ;
        }

        if(!isNeedRendFocus) {
            return ;
        }

        if(searchText.length < 1) {
            if(!firstKey) {
                showGamesByType(0);
            }
            $('#search-result').css("display",'none');
            return;
        }
        firstKey = false;
        //搜索重组
        $('#search-result').find('a').each(function(){
            var curData = $(this).attr('data');
            if(curData.indexOf(searchText) !== -1) {
                if(startIndex == 0) {
                    $(this).addClass('focus');
                }else{
                    $(this).removeClass('focus');
                }
                if(startIndex % 2 ==0 ) {
                    $(this).addClass('o');
                }else{
                    $(this).removeClass('o');
                }
                $(this).css("display",'block');
                startIndex++;
                $('#search-result').css("display",'block');
                selRes.push($(this));
            }else{
                $(this).removeClass('focus o').css("display",'none');
            }
        });
        if(startIndex < 1) {
            $('#search-result-none').css("display",'block');
        }

    })
});

</script>
<!-- 图片滚动 合作媒体 -->
<script type="text/javascript">  

  

         /*****************************************************  

          *  Share JavaScript (http://www.ShareJS.com)  

          * 使用此脚本程序，请保留此声明  

         * 获取此脚本以及更多的JavaScript程序，请访问 http://www.ShareJS.com  
         ******************************************************/  

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
  scrollingBox.style.height = "170px";  
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