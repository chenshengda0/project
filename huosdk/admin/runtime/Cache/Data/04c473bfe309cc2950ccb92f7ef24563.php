<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
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
</head>
<body class="J_scroll_fixed">
	<div class="wrap js-check-wrap">
		<form class="well form-search" method="post" action="<?php echo U('Data/Pay/orderindex');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20"> 
					订单号： 
					<input type="text" name="orderid" style="width: 150px;" value="<?php echo ($formget["orderid"]); ?>" placeholder="请输入订单号..."> 
					&nbsp;&nbsp; &nbsp;&nbsp; 

					游戏：
					<select class="select_2" name="gid" id="selected_id" style="width: 200px;" >
							<?php if(is_array($games)): foreach($games as $k=>$vo): $gid_select=$k==$formget['gid'] ?"selected":""; ?>
							<option value="<?php echo ($k); ?>"<?php echo ($gid_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
					    </select>
					 &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
					 充值账号： 
					<input type="text" name="username"
						style="width: 150px;" value="<?php echo ($formget["username"]); ?>"
						placeholder="请输入账号..."> 
						
					<br><br>
					 充值方式：					
					<select class="select_2" name="payway" style="width: 150px;"  id="selected_id">
						<?php if(is_array($payways)): foreach($payways as $k=>$vo): $pw_select=$k===$formget['payway'] ?"selected":""; ?>
						<option value="<?php echo ($k); ?>"<?php echo ($pw_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
					</select>
					 &nbsp;&nbsp;  &nbsp;&nbsp; 
					 注册渠道号：
					<input type="text" name="agentname" style="width: 150px;" value="<?php echo ($formget["agentname"]); ?>" placeholder="请输入注册渠道账号..."> 
					&nbsp;&nbsp;
					注册渠道名称 ：	
					<input type="text" name="agentnickname" style="width: 150px;" value="<?php echo ($formget["agentnickname"]); ?>" placeholder="请输入渠道名称...">
					</span>
					<br><br>	
					<span> 
					
					
					充值状态：	
					<select class="select_2" name="paystatus" style="width: 150px;" id="selected_id">
						<?php if(is_array($paystatuss)): foreach($paystatuss as $k=>$vo): $ps_select=$k==$formget['paystatus'] ?"selected":""; ?>
						<option value="<?php echo ($k); ?>"<?php echo ($ps_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;
					回调状态 
					<select class="select_2" name="cpstatus" style="width: 200px;"  id="selected_id">
						<?php if(is_array($cpstatuss)): foreach($cpstatuss as $k=>$vo): $ps_select=$k==$formget['cpstatus'] ?"selected":""; ?>
						<option value="<?php echo ($k); ?>"<?php echo ($ps_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp; 
					
				时间：
					<input type="text" name="start_time"
						class="js-date" value="<?php echo ((isset($formget["start_time"]) && ($formget["start_time"] !== ""))?($formget["start_time"]):''); ?>" placeholder="开始时间..."
						style="width: 100px;" autocomplete="off">
					- 
					<input	type="text" class="js-date" name="end_time" placeholder="时间..."
						value="<?php echo ($formget["end_time"]); ?>" style="width: 100px;"
						autocomplete="off"> &nbsp; &nbsp; 
					</span>
					<br><br>
				<input type="submit"  name='submit' class="btn btn-warning" value="七日" /> 
			    <input type="submit"  name='submit' class="btn btn-danger" value="本月" /> 
			    <input type="submit"  name='submit' class="btn btn-primary" value="搜索" />
				</div>
			</div>
		</form>
		<form class="js-ajax-form" action="" method="post">
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th>订单号</th>
						<th width="120">时间</th>
						<th>账号</th>
						<th>游戏</th>
						<th>金额</th>	
						<th>状态</th>					
						<th>充值方式</th>
						<th>注册渠道账号</th>
						<th>充值渠道名称</th>						
						<th>回调状态</th>
						<th>操作</th>
					</tr>
				</thead>

					<tr>
						<th style='color:#f00'>汇总</th>
						<th style='color:#f00'>--</th>
						<th style='color:#f00'>--</th>
						<th style='color:#f00'>--</th>
						<th style='color:#f00'><?php echo ($sums); ?></th>
						<th style='color:#f00'>--</th>
						<th style='color:#f00'>--</th>
						<th style='color:#f00'>--</th>
						<th style='color:#f00'>--</th>
						<th style='color:#f00'>--</th>
						<th style='color:#f00'>--</th>
					</tr>

				<?php if(is_array($orders)): foreach($orders as $key=>$vo): ?><tr>
				<td><?php echo ($vo["order_id"]); ?></td>
				<td><?php echo (date('Y-m-d  H:i:s',$vo["create_time"])); ?></td>
				<td><?php echo ($vo["username"]); ?></td>
				<td><?php echo ($vo["gamename"]); ?></td>
				<td><?php echo ($vo["amount"]); ?></td>
				<td>				
				<?php if( 2 == $vo["status"] ): ?><span style='color:#f00'>成功</span> 
				    <?php elseif( 3 == $vo.status): ?>
				    	<span style='color:#00f'>失败</span>
				    <?php else: ?>
				    	<span style='color:#000'>待支付</span><?php endif; ?> 
				</td>
				<td>
					<?php if( '0' == $vo["payway"] OR '' == $vo["payway"] ): ?>该订单还未支付
					<?php else: ?>
						<?php echo ($payways[$vo['payway']]); endif; ?>
				</td>

				<td>				
				<?php if( 0 == $vo['agent_id'] ): ?><span style='color:#000'>default</span> 
				    <?php else: ?>
				    	<span style='color:#000'><?php echo ($vo['agentname']); ?></span><?php endif; ?> 
				</td>

				<td>				
				<?php if( 0 == $vo['agent_id'] ): ?><span style='color:#000'>官包</span> 
				    <?php else: ?>
				    	<span style='color:#000'><?php echo ($vo["agentnickname"]); ?></span><?php endif; ?> 
				</td>
				<td>
				<?php if( 2 == $vo['cpstatus'] ): ?><span style='color:#f00'>回调成功</span> 
				    <?php elseif( ( 2 == $vo['status'] ) and ( 1 == $vo['cpstatus'] OR 3 == $vo['cpstatus']) ): ?>
				    	<span style='color:#00f'>回调失败</span>
				    <?php else: ?>
				    	<span style='color:#000'>待支付</span><?php endif; ?> 
				</td>
					<td>
						<?php if( ( 2 == $vo["status"] ) and ( 1 == $vo['cpstatus'] OR 3 == $vo['cpstatus']) ): ?><a href="<?php echo U('Data/Pay/repairorder', array('orderid'=>$vo['order_id']));?>"
							class="js-ajax-dialog-btn" data-msg="您确定要补单吗？">补单</a></td><?php endif; ?> 
					</td>					
				</tr><?php endforeach; endif; ?>

			</table>
			<div class="pagination"><?php echo ($Page); ?></div>

		</form>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>