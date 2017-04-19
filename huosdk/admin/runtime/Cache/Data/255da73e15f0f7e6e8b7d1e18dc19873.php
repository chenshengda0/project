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
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Data/Retain/index');?>">总体数据</a></li>
			<?php if(sp_auth_check(sp_get_current_admin_id(),'Data/Retain/agent')): ?><li><a href="<?php echo U('Data/Retain/agent');?>">渠道留存</a></li><?php endif; ?>
			<?php if(sp_auth_check(sp_get_current_admin_id(),'Data/Retain/game')): ?><li><a href="<?php echo U('Data/Retain/game');?>">游戏留存</a></li><?php endif; ?>
			<?php if(sp_auth_check(sp_get_current_admin_id(),'Data/Retain/game')): ?><li><a href="<?php echo U('Data/Retain/agentgame');?>">留存详细</a></li><?php endif; ?>			
		</ul>
		<form class="well form-search" method="post" action="<?php echo U('Data/Retain/index');?>">		 			 
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						时间：
						<input type="text" name="start_time" class="js-date" value="<?php echo ((isset($start_time) && ($start_time !== ""))?($start_time):''); ?>" style="width: 100px;"  autocomplete="off">-						
						<input type="text" name="end_time"  class="js-date" value="<?php echo ((isset($end_time) && ($end_time !== ""))?($end_time):''); ?>" style="width: 100px;" autocomplete="off"> &nbsp; &nbsp;
						<input type="submit" class="btn btn-warning" name='date_time' value="搜索" />
					</span>
				</div>
			</div>
		</form>
		<form class="js-ajax-form" action="" method="post">
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
						<th>注册人数</th>
						<th>二日</th>
						<th>三日</th>
						<th>四日</th>
						<th>五日</th>
						<th>六日</th>
						<th>七日</th>
						<th>15日</th>
						<th>30日</th>
					</tr>
				</thead>
				<?php if(is_array($pays)): foreach($pays as $key=>$vo): ?><tr> 
					    <td><?php echo ($vo['date']); ?></td>
						<td><?php echo ($vo['reg_cnt']); ?></td>
						<td><?php echo ($vo['day2']); ?>(<?php echo (number_format($vo['day2']*100/$vo['reg_cnt'],2)); ?>%)</td>
						<td><?php echo ($vo['day3']); ?>(<?php echo (number_format($vo['day3']*100/$vo['reg_cnt'],2)); ?>%)</td>
						<td><?php echo ($vo['day4']); ?>(<?php echo (number_format($vo['day4']*100/$vo['reg_cnt'],2)); ?>%)</td>
						<td><?php echo ($vo['day5']); ?>(<?php echo (number_format($vo['day5']*100/$vo['reg_cnt'],2)); ?>%)</td>
						<td><?php echo ($vo['day6']); ?>(<?php echo (number_format($vo['day6']*100/$vo['reg_cnt'],2)); ?>%)</td>
						<td><?php echo ($vo['day7']); ?>(<?php echo (number_format($vo['day7']*100/$vo['reg_cnt'],2)); ?>%)</td>
						<td><?php echo ($vo['day15']); ?>(<?php echo (number_format($vo['day15']*100/$vo['reg_cnt'],2)); ?>%)</td>
						<td><?php echo ($vo['day30']); ?>(<?php echo (number_format($vo['day30']*100/$vo['reg_cnt'],2)); ?>%)</td>
					</tr><?php endforeach; endif; ?>
				<tfoot>
				</tfoot>
			</table>
			<div class="pagination"><?php echo ($Page); ?></div>

		</form>
	</div>
	<script src="/public/js/common.js"></script>
	
</body>
</html>