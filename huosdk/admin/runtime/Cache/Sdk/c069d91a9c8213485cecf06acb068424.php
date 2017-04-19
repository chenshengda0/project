<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		.length_3{width: 180px;}
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
<script type="text/javascript">
//全局变量
var GV = {
		DIMAUB : "/",
		JS_ROOT : "public/js/",
    TOKEN: ""
};
</script>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/wind.js"></script>
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
<script src="/public/select2/js/select2.min.js"></script>
<link href="/public/select2/css/select2.min.css" rel="stylesheet"
	type="text/css">
<script>
	$(document).ready(function() {
		$(".select_2").select2();
	});
</script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>

<!--必要样式-->
<style type="text/css">
#BgDiv1{background-color:#000; position:absolute; z-index:9999;  display:none;left:0px; top:0px; width:100%; height:100%;opacity: 0.6; filter: alpha(opacity=60);}
.DialogDiv{position:absolute;z-index:99999;}/*配送公告*/
.U-user-login-btn{ display:block; border:none; font-size:1em; color:#efefef; line-height:49px; cursor:pointer; height:53px; font-weight:bold;
border-radius:3px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
 width:100%; box-shadow: 0 1px 4px #cbcacf, 0 0 40px #cbcacf ;}
 .U-user-login-btn:hover, .U-user-login-btn:active{ display:block; border:none; font-size:1em; color:#efefef; line-height:49px; cursor:pointer; height:53px; font-weight:bold;
border-radius:3px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
 width:100%; box-shadow: 0 1px 4px #cbcacf, 0 0 40px #cbcacf ;}
.U-user-login-btn2{ display:block; border:none; font-size:1em; color:#efefef; line-height:49px; cursor:pointer; font-weight:bold;
border-radius:3px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
 width:100%; box-shadow: 0 1px 4px #cbcacf, 0 0 40px #cbcacf ;height:53px;}
.U-guodu-box { padding:5px 15px;  background:#3c3c3f; filter:alpha(opacity=90); -moz-opacity:0.9; -khtml-opacity: 0.9; opacity: 0.9;  min-heigh:200px; border-radius:10px;}
.U-guodu-box div{ color:#fff; line-height:20px; font-size:15px; margin:0px auto; height:100%; padding-top:10%; padding-bottom:10%;}

</style>

</head>
<body class="J_scroll_fixed">
	<div id="BgDiv1"></div>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Subpackage/qudaoindex');?>">一键分包</a></li>
			<li><a href="<?php echo U('Subpackage/addagent');?>" target="_self">添加渠道</a></li>
		</ul>

		<form id="fm" class="well form-search" method="post" action="<?php echo U('Subpackage/qudiaoindex');?>">
			
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
					        游戏： 
						<select class="select_2" name="app_id" id="selected_id">
							<?php if(is_array($games)): foreach($games as $k=>$vo): $g_select=$k==$app_id ?"selected":""; ?>
								<option value="<?php echo ($k); ?>"<?php echo ($g_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;					
						渠道账号： 
						<select class="select_2" name="agent_id" id="selected_id">
							<?php if(is_array($agents)): foreach($agents as $k=>$vo): $a_select=$k==$agent_id ?"selected":""; ?>
								<option value="<?php echo ($k); ?>"<?php echo ($a_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
						</select>
						&nbsp;&nbsp;
						<input type="button" name='search' id='search' class="btn btn-warning" value="搜索" />
						<select name="" id="search_action">
							<option value="1">按渠道名查询</option>
							<option value="2">按游戏查询</option>
						</select>

						<div id="response_content_all">
						<input type="text" id="search_content" placeholder="请输入关键词" autocomplete="off">
							<div id="search_contents">
							</div>
						</div>
						<style>
							#search_action{width: 120px;height: 35px;margin-left:100px;}
							#search_content{width:130px;}
							#response_content_all{width: 130px;float:right;}
						</style>

							
						<br>
						<br>
						分成比例： 
						<input type="text" name="rate"  value="" placeholder="分成比例...">
						&nbsp;&nbsp;
						cpa价格： 
						<input type="text" name="cpa_price" value="" placeholder="CPA价格...">
						&nbsp;&nbsp;
						<input type="hidden" name="bagurl" id="bagurl" value="<?php echo U('Subpackage/subpackage');?>">
						<input type="button" name='action' id='action' class="btn btn-primary" value="一键分包" />
						
						<div class="DialogDiv"  style="display:none; ">
								<div class="U-guodu-box">
								<div>
								<table width="100%" cellpadding="0" cellspacing="0" border="0" >
									<tr><td  align="center"><img src="/public/images/load.gif"></td></tr>
									<tr><td  valign="middle" align="center" >提交中,提交完成前请勿做其它操作！</td></tr>
								</table>
								</div>
							</div>
						</div>
					</span>
				</div>
			</div>
		</form>
		<form class="js-ajax-form" action="" method="post">
		<input name="adminsite" type="hidden"  id="adminsite" value="<?php echo U('Subpackage/ajaxGetagent');?>">
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th>游戏渠道号</th>
						<th>渠道账号</th>
						<th>渠道名称</th>
						<th>游戏</th>
						<th>渠道类型</th>						
						<th>cpa价格</th>
						<th>分成比例</th>
						<th>时间</th>
						<th>下载地址</th>
						<th width="100">管理操作</th>
					</tr>
				</thead>
				<tbody id="table_data_agent">
					<?php if(is_array($subagents)): foreach($subagents as $key=>$vo): ?><tr>
							<td><?php echo ($vo["agentgame"]); ?></td>
							<td><?php echo ($agents[$vo[agent_id]]); ?></td>
							<td><?php echo ($vo['user_nicename']); ?></td>
							<td><?php echo ($games[$vo['app_id']]); ?></td>
							<td><?php echo ($roles[$vo['user_type']]); ?></td>
							<td><?php echo ($vo["cpa_price"]); ?></td>
							<td><?php echo ($vo["agent_rate"]); ?></td>
							<td>
								<?php if(!empty($vo['create_time'])): echo (date('Y-m-d H:i:s',$vo["create_time"])); endif; ?>
							</td>
							<td>
							    <?php if(!empty($vo['url'])): echo DOWNSITE;?>/sdkgame/<?php echo ($vo["url"]); ?>
								<?php else: ?>
										还没有母包<?php endif; ?>
							</td>
							<td>
								<a href="<?php echo U('Subpackage/updatepackage',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="确定更新吗？">更新 </a>
								<a href="<?php echo U('Subpackage/delagentgame',array('id'=>$vo['id']));?>" class="js-ajax-delete"> | 删除</a></td>
						</tr><?php endforeach; endif; ?>
				</tbody>
				
			</table>
			<div class="pagination" id="agent_pagination"><?php echo ($Page); ?></div>

		</form>
	</div>
	<script src="/public/js/common.js"></script>
	<script src="/public/js/jquery.form.js"></script>

	<script>
		$(function(){
			$('#search_content').keyup(function(e){
				if(e.keyCode==32){
					var content=this.value.replace(/\s*/g,'');
					var typeid=$('#search_action').val();
					var obj=document.getElementById('search_contents');
					if(content.length>0) $.post("/admin.php/Sdk/Subpackage/qudaoindex",{'typeid':typeid,'content':content},function(data){
											//console.log(data);
					$(obj).empty();
					$(obj).append(data);
					},'text');
				}

			});
							
		});
		function search_content_type(obj,id){
			var input=$('#search_content');
				input.val(obj.innerHTML);
				var obj=$("#table_data_agent");
				var pagnation=$("#agent_pagination");
				$("#search_contents").empty();
				$.post("/admin.php/Sdk/Subpackage/qudaoindex",{'userid':id},function(data){
					obj.empty();
					obj.append(data);
					pagnation.empty();
				},'text');
		}
							
		$(document).ready(function () {
			$("#action").click(function () {
				$("#BgDiv1").css({
				display: "block", height: $(document).height() });
				var yscroll = document.documentElement.scrollTop;
				var screenx=$(window).width();
				var screeny=$(window).height();
				$(".DialogDiv").css("display", "block");
				 $(".DialogDiv").css("top",yscroll+"px");
				 var DialogDiv_width=$(".DialogDiv").width();
				 var DialogDiv_height=$(".DialogDiv").height();
				  $(".DialogDiv").css("left",(screenx/2-DialogDiv_width/2)+"px")
				 $(".DialogDiv").css("top",(screeny/2-DialogDiv_height/2)+"px")
				 $("body").css("overflow","hidden");

				var bagurl = $("#bagurl").val();
				//return console.log(bagurl);
				var options = {
					url: bagurl,///admin.php/Sdk/Subpackage/subpackage.html
					type: 'post',
					dataType: 'json',
					data: $("#fm").serialize(),//app_id=60087&agent_id=121&rate=0.02&cpa_price=&bagurl=%2Fadmin.php%2FSdk%2FSubpackage%2Fsubpackage.html
					success:function(data){
						//console.log(data);
						$("#BgDiv1").css({ 
						display: "none", height: $(document).height() });
						$(".DialogDiv").css("display", "none");
						 $("body").css("overflow","visible");

						if (data.success){
							alert(data.msg);
							window.location.reload();//刷新当前页面.
						} else {
							alert(data.msg);
							window.location.reload();//刷新当前页面.
						}
					}
				};
				$.ajax(options);
				return false;
			});
			
			$("#agent_type").bind("change",function(){
				var agent_type = $("#agent_type").val();
				if (agent_type == "1") {
					$("#agent_ida").show();	
					$("#agent_idb").hide();
				} else {
					$("#agent_idb").show();	
					$("#agent_ida").hide();
				}	
			});

			Wind.use('artDialog', function () {
				$('.J_ajax_updatebag').on('click', function (e) {
					e.preventDefault();
					var $_this = this,
						$this = $($_this),
						href = $this.prop('href'),
						msg = $this.data('msg');
					art.dialog({
						title: false,
						icon: 'question',
						content: '确定要更新吗？',
						follow: $_this,
						close: function () {
							$_this.focus();; //关闭时让触发弹窗的元素获取焦点
							return true;
						},
						ok: function () {
							$("#BgDiv1").css({ 
							display: "block", height: $(document).height() });
							var yscroll = document.documentElement.scrollTop;
							var screenx=$(window).width();
							var screeny=$(window).height();
							$(".DialogDiv").css("display", "block");
							 $(".DialogDiv").css("top",yscroll+"px");
							 var DialogDiv_width=$(".DialogDiv").width();
							 var DialogDiv_height=$(".DialogDiv").height();
							  $(".DialogDiv").css("left",(screenx/2-DialogDiv_width/2)+"px")
							 $(".DialogDiv").css("top",(screeny/2-DialogDiv_height/2)+"px")
							 $("body").css("overflow","hidden");

							$.getJSON(href).done(function (data) {
								$("#BgDiv1").css({ 
								display: "none", height: $(document).height() });
								$(".DialogDiv").css("display", "none");
								 $("body").css("overflow","visible");

								if (data.success) {
									alert(data.msg);
									if (data.referer) {
										location.href = data.referer;
									} else {
										reloadPage(window);
									}
								} else {
									//art.dialog.alert(data.info);
									alert(data.info);//暂时处理方案
								}
							});
						},
						cancelVal: '关闭',
						cancel: true
					});
				});

			});

		});
	</script>
</body>
</html>