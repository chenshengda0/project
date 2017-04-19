<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport"
	content="width=device-width,initial-scale=1,user-scalable=1" />
<meta charset="UTF-8">
<title></title>
<link rel="stylesheet" href="/public/float/css/mymsg.css" />
</head>
<body>
	<!-- <header class="header">
		<ul class="main_layout">
			<li class="back_btn"></li>
			<li class="text">我的消息</li>
			<li class="close_btn"><img src="/public/float/images/closebtn.png" alt="" /></li>
		</ul>
	</header>-->
	<div class="msg_box">
		<ul class="box">
		<?php if($noticedata == ''): ?><div style="text-align: center;margin-top: 40%;font-size: 24px;">
				亲，现在暂无公告哦...
			</div>	
		<?php else: ?>
		<?php if(is_array($noticedata)): $i = 0; $__LIST__ = $noticedata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="item">
				<a href="<?php echo U('Float/Notice/notice',array('id'=>$vo['id']));?>">
					<div class="left">
						<img src="/public/float/images/test.png" alt="" />
					</div>
					<div class="right">
						<h3>管理员</h3>
						<p>
							<?php echo ($vo["title"]); ?>
						</p>
						<span><?php echo (date('Y-m-d  H:i:s',$vo["update_time"])); ?></span>
					</div>
				</a>
			</li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
		</ul>
	</div>
	<div class="loading_more">
		<ul class="loadBtn loadBtn2">
			<li class="line"><b></b></li>
			<li class="text">加载更多</li>
			<li class="line"><b></b></li>
		</ul>
		<div class="btn_top">
			<img src="/public/float/images/btn_top.png" alt="" />
		</div>
	</div>
	<!-- <footer class="footer_nav">
		<ul>
			<li class="zh"><a href="user.html"><b></b>
				<p>帐户</p></a></li>
			<li class="msg active"><a href="mymsg.html"><b></b>
				<p>消息</p></a></li>
			<li class="lb"><a href="libao.html"><b></b>
				<p>礼包</p></a></li>
			<li class="dt"><a href="#"><b></b>
				<p>大厅</p></a></li>
		</ul>
	</footer> -->
	<div class="main_model_box"></div>
</body>
<script src="/public/float/js/jquery.js"></script>
<script src="/public/float/js/public.js"></script>
<script src="/public/float/js/mymsg.js"></script>
</html>