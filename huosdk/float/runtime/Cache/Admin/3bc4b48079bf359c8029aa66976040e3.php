<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
			   <li class="active" style="width:50%;text-align:center;height:100%;font-size:16px;color:#000">
			   <a href="<?php echo U('Admin/Gift/index', array('show'=>'list'));?>">礼包列表</a></li>
			   <li style="width:50%;text-align:center;height:100%;font-size:16px">
			   <a href="<?php echo U('Admin/Gift/index', array('show'=>'mygift'));?>" style="color:#HDDD8D6">存号箱</a></li>
			</ul>

		
      <div class="panel-body" style="background:#F5F5F5">
					<div class="panel-group" id="accordion">
					<?php if(is_array($gifts)): foreach($gifts as $key=>$vo): ?><div class="panel panel-default">
							<div class="panel-heading" style="background-color:#fff">
								<a data-toggle="collapse" data-parent="#accordion" 
								  href="#collapseOne">
								  <div class="row" data-toggle="collapse" data-parent="#accordion" data-target="#collapse<?php echo ($vo["id"]); ?>">
									<div class="col-md-9 col-sm-8" style="padding-left:10px">
										<a class="pull-left"><img class="img-responsive" src="/public/images/float/gift.gif" style="max-width:55px;margin-right:5px" alt="" /></a>
										<div class="pull-right">
										<button type="button" class="btn btn-success" style="min-width:50%;margin-top:5px" >领取</button>
										<input class="gfid" type="hidden" value="<?php echo ($vo["id"]); ?>">
										<input class="total" name="total" type="hidden" value="<?php echo ($vo["total"]); ?>"> 
										</div>
										<ul>
											<li><h3 style="font-size:16px;margin-bottom:0px;margin-top:5px"><?php echo ($vo["title"]); ?></h3></li>
											<li><strong style="font-weight:300">剩余：</strong>
											<strong class="number" style="font-weight:300"><?php echo ($vo["remain"]); ?>/<?php echo ($vo["total"]); ?></strong></li>
											<?php $vo['with'] = ($vo['total']-$vo['remain'])/$vo['total']*100 ?>
											<li>
												<div class="progress">
														   <div class="progress-bar progress-bar-danger" role="progressbar" 
															  aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" 	
															  style="width: <?php echo ($vo["with"]); ?>%">
															  <span class="sr-only"></span>
														   </div>
														</div>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div id="collapse<?php echo ($vo["id"]); ?>" class="panel-collapse collapse" style="background-color:#646464">
							  <div class="panel-body" style="color:#fff">
								<?php echo ($vo["content"]); ?>
							  </div>
								
							</div>
						  </div><?php endforeach; endif; ?>
 <input type="hidden" id="weburl" value="<?php echo U('Admin/Gift/giftAjax');?>">
</div>
</div>
	
</body> 
<script>window.jQuery || document.write('<script src="/public/js/jquery-2.1.1.min.js"><\/script>')</script>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('.pull-right').click(function(){
		var $progress = $(this).parent().find('.progress'), $bar = $(this).parent().find('.progress-bar');
		
		var percent = 0;
		var gfid = $(this).parent().find('.gfid').val();
		var total = $(this).parent().find('.total').val();
		var $rmtest = $(this).parent().find('.number');
		var weburl = $("#weburl").val();
		
		$.ajax({
				type: 'POST',
				url: weburl,
				data:{giftid:gfid},
				error:function(d,t){
					alert('网络错误');
				},
				dataType:"json",
				success: function(data){						
						var code = data.a;
						var rmtotal = data.b;
						if(code.length > 2 && total > 0 && rmtotal >= 0){
							ZENG.msgbox.show('领取成功,已放入存号箱', 4);
							var str = rmtotal + "/" + total;
							$rmtest.html(str);
							percent += (total - rmtotal) / total * 100;
							percent = parseFloat(percent.toFixed(1));
							
							$bar.css('width', percent + '%' );
						}else if(code == '5'){
							ZENG.msgbox.show('已领取过,请到存号箱查看', 5);
						}else if(code == '7'){
							ZENG.msgbox.show('网络问题', 5);
						}else {
							ZENG.msgbox.show('领取失败', 5);
						}
					}
				});
			

		return false;
	});
	
	
}); 	
</script>
</html>