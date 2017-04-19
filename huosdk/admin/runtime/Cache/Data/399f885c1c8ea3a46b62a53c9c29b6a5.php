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
</head>
<body class="J_scroll_fixed">
	<div class="wrap js-check-wrap">
		<!-- <ul class="nav nav-tabs">
			<li class="active"><a href="javascript:;">所有文章</a></li>
			<li><a href="<?php echo U('AdminPost/add',array('term'=>empty($term['term_id'])?'':$term['term_id']));?>" target="_self">添加文章</a></li>
		</ul> -->
		<div></div>
		<form class="well form-search" method="get" action="<?php echo U('Data/Game/gameindex');?>">		 			 
			<div class="search_type cc mb10">			
				<div class="mb10">
				游戏： 					
					<select class="select_2" name="app_id" id="selected_id">
							<?php if(is_array($games)): foreach($games as $k=>$vo): $gid_select=$k==$formget[app_id]?"selected":""; ?>
							<option value="<?php echo ($k); ?>"<?php echo ($gid_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
					    </select>
					    
					 &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; 
					<span class="mr20">
						时间：
						<input type="text" name="start_time" class="js-date" value="<?php echo ((isset($formget["start_time"]) && ($formget["start_time"] !== ""))?($formget["start_time"]):''); ?>" style="width: 100px;"  autocomplete="off">-						
						<input type="text" class="js-date" name="end_time" value="<?php echo ($formget["end_time"]); ?>" style="width: 100px;" autocomplete="off"> &nbsp; &nbsp;
						<input type="submit" class="btn btn-warning" name='date_time' value="搜索" />
						<input type="submit" class="btn btn-warning" name='date_time' value="七日" />
						<input type="submit" class="btn btn-warning" name='date_time' value="当月" />
						<input type="submit" class="btn btn-warning" name='date_time' value="30天" />
						
						
					</span>
				</div>
			</div>
		</form>
		<form class="js-ajax-form" action="" method="get">		
			<table class="table table-hover table-bordered table-list">
					<tr>
						<th style='color:#f00'>*隔天数据*</th>
					</tr>
			</table>
			&nbsp;&nbsp; 
			
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th>日期</th>
						<th>游戏</th>
						<th>新增用户数</th>
						<th>活跃用户数</th>
						<th>付费用户数</th>
						<th>订单数量</th>
						<th>新用户付费金额</th>
						<th>总付费金额</th>
						<th>总付费率</th>
						<th>注册APRU</th>
						<th>活跃ARPU</th>
						<th>付费ARPU</th>
					</tr>
					</tr>
				</thead>
					<tr>
						<th style='color:#f00'>汇总</th>
						<th style='color:#00f'>--</th>
						<th style='color:#f00'><?php echo ($totalpays[0]['reg_cnt']); ?></th>
						<th style='color:#f00'><?php echo ($totalpays[0]['user_cnt']); ?></th>
						<th style='color:#f00'><?php echo ($totalpays[0]['pay_user_cnt']); ?></th>
						<th style='color:#f00'><?php echo ($totalpays[0]['order_cnt']); ?></th>
						<th style='color:#f00'><?php echo (floor($totalpays[0]['sum_reg_money'])); ?></th>
						<th style='color:#f00'><?php echo (floor($totalpays[0]['sum_money'])); ?></th>
						<th style='color:#f00'><?php echo (number_format($totalpays[0]['pay_user_cnt']/$totalpays[0]['user_cnt']*100,2)); ?>%</th>
						<th style='color:#f00'><?php echo (number_format($totalpays[0]['sum_reg_money']/$totalpays[0]['reg_cnt'],2)); ?></th>
						<th style='color:#f00'><?php echo (number_format($totalpays[0]['sum_money']/$totalpays[0]['user_cnt'],2)); ?></th>
						<th style='color:#f00'><?php echo (number_format($totalpays[0]['sum_money']/$totalpays[0]['pay_user_cnt'],2)); ?></th>
					</tr>
					<?php if( 1 == $current_page AND !empty($todaypays['date'])): ?><tr>
						<td><?php echo ($todaypays['date']); ?></td>
						<td><?php echo ($games[$todaypays['app_id']]); ?></td>
						<td><?php echo ($todaypays['reg_cnt']); ?></td>
						<td><?php echo ($todaypays['user_cnt']); ?></td>
						<td><?php echo ($todaypays['pay_user_cnt']); ?></td>
						<td><?php echo ($todaypays['order_cnt']); ?></td>
						<td><?php echo (floor($todaypays['sum_reg_money'])); ?></td>
						<td><?php echo (floor($todaypays['sum_money'])); ?></td>
						<td><?php echo (number_format($todaypays['pay_user_cnt']/$todaypays['user_cnt']*100,2)); ?>%</td>
						<td><?php echo (number_format($todaypays['sum_reg_money']/$todaypays['reg_cnt'],2)); ?></td>
						<td><?php echo (number_format($todaypays['sum_money']/$todaypays['user_cnt'],2)); ?></td>
						<td><?php echo (number_format($todaypays['sum_money']/$todaypays['pay_user_cnt'],2)); ?></td>
					</tr><?php endif; ?>
				<?php if(is_array($pays)): foreach($pays as $key=>$vo): ?><tr> 
					    <td><?php echo ($vo['date']); ?></td>
					    <td><?php echo ($games[$vo['app_id']]); ?></td>
						<td><?php echo ($vo['reg_cnt']); ?></td>
						<td><?php echo ($vo['user_cnt']); ?></td>
						<td><?php echo ($vo['pay_user_cnt']); ?></td>
						<td><?php echo ($vo['order_cnt']); ?></td>
						<td><?php echo (floor($vo['sum_reg_money'])); ?></td>
						<td><?php echo (floor($vo['sum_money'])); ?></td>
						<td><?php echo (number_format($vo['pay_user_cnt']/$vo['user_cnt']*100,2)); ?>%</td>
						<td><?php echo (number_format($vo['sum_reg_money']/$vo['reg_cnt'],2)); ?></td>
						<td><?php echo (number_format($vo['sum_money']/$vo['user_cnt'],2)); ?></td>
						<td><?php echo (number_format($vo['sum_money']/$vo['pay_user_cnt'],2)); ?></td>
					</tr><?php endforeach; endif; ?>

			</table>
			<div class="pagination"><?php echo ($Page); ?></div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
	
</body>
</html>