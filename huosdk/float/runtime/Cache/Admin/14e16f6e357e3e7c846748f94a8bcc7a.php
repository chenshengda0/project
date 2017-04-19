<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0 , maximum-scale=1.0, user-scalable=0">
<link rel="stylesheet" href="/public/float/css/themev3.css" />
<link href="/public/float/css/bootstrap.css" rel="stylesheet" />
<link rel="stylesheet" href="/public/float/css/box.css" />

<script type="text/javascript" src="/public/js/jquery.js"></script>
<script type="text/javascript" src="/public/float/js/msgbox.js"></script>
<script src="/public/js/jquery.min.js"></script>
<script src="/public/js/bootstrap.min.js"></script>
</head>

<body style="background-color:#F5F5F5">
<div class="panel panel-default" style="border:0px;box-shadow:0px 0px 0px">

			<ul class="nav nav-tabs">
			   <li style="width:50%;text-align:center;height:100%;font-size:16px;color:#000">
			   <a href="<?php echo U('Admin/Gift/index', array('show'=>'list'));?>" style="color:#HDDD8D6">礼包列表</a></li>
			   <li class="active" style="width:50%;text-align:center;height:100%;font-size:16px">
			   <a href="<?php echo U('Admin/Gift/index', array('show'=>'mygift'));?>">存号箱</a></li>
			</ul>

	 <div class="panel-body" style="background:#F5F5F5">
						<div class="panel-group" id="accordion">
						 <?php if(is_array($gifts)): foreach($gifts as $key=>$vo): ?><div class="panel panel-default">
								<div class="panel-heading" style="background-color:#fff">
									<a data-toggle="collapse" data-parent="#accordion" 
									  href="#collapseOne">
									  <div class="row">
										<div class="col-md-9 col-sm-8" style="padding-left:10px">
											<a class="pull-left"><img class="img-responsive" src="/public/images/float/gift.gif" style="max-width:55px;margin-right:5px" alt="" /></a>
											<div class="pull-right">
											<button type="button" class="btn btn-success" style="min-width:50%;margin-top:5px" onClick="window.ttw_w.goToGift('<?php echo ($vo["code"]); ?>')">复制</button>
											</div>
											<ul>
												<li><h3 style="font-size:16px;margin-bottom:0px;margin-top:10px"><?php echo ($vo["title"]); ?></h3></li>
												<li style="margin-top:10px"><strong style="font-weight:300">礼包码：</strong>
												<strong style="font-weight:300;color:#FC3636"><?php echo ($vo["code"]); ?></strong></li>
											</ul>
										</div>
									</div>
								</div>
							  </div><?php endforeach; endif; ?>
	 <input type="hidden" id="weburl" value="<?php echo ($WEBSITE); ?>">
	</div>
</div>
</body> 
</html>