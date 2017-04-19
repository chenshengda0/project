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

	<link rel="stylesheet" type="text/css" href="/public/admin/css/cloud-admin.css" >
	<!-- STYLESHEETS --><!--[if lt IE 9]><script src="js/flot/excanvas.min.js"></script><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->
	<link href="/public/admin/css/font-awesome.min.css" rel="stylesheet">
	<!-- ANIMATE -->
	<link rel="stylesheet" type="text/css" href="/public/admin/css/animatecss/animate.min.css" />
	<!-- DATE RANGE PICKER -->
	<link rel="stylesheet" type="text/css" href="/public/admin/js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
	<!-- TODO -->
	<link rel="stylesheet" type="text/css" href="/public/admin/js/jquery-todo/css/styles.css" />
	<!-- FULL CALENDAR -->
	<link rel="stylesheet" type="text/css" href="/public/admin/js/fullcalendar/fullcalendar.min.css" />
	<!-- GRITTER -->
	<link rel="stylesheet" type="text/css" href="/public/admin/js/gritter/css/jquery.gritter.css" />

</head>
<body>
	
			<div id="content" class="col-lg-12">
						<!-- PAGE HEADER-->
						<div class="row" style="margin-top:10px">
							
						</div>
						<!-- /PAGE HEADER -->
						<!-- DASHBOARD CONTENT -->
						<div class="row">
							<!-- COLUMN 1 -->
							<div class="col-sm-6 col-md-3">
							  <div class="panel panel-success panel-stat">
								<div class="panel-heading">

								  <div class="stat">
									<div class="row">
									  <div class="col-xs-4">
										<i class="fa fa-users" style="border:0px;font-size:50px;padding:0px"></i>
									  </div>
									  <div class="col-xs-8">
										<small class="stat-label">总注册</small>
										<h1><?php echo ($todayreg+$regcnt); ?></h1>
									  </div>
									</div><!-- row -->

									<div class="mb15"></div>

									<div class="row">
										  <div class="col-xs-4">
											<small class="stat-label">今日注册</small>
											<h4><?php echo ($todayreg); ?></h4>
										  </div>

										 <div class="col-xs-4">
												<small class="stat-label">今日登陆</small>
												<h4><?php echo ($todaylogin); ?></h4>
										 </div>

										 <div class="col-xs-4">
												<small class="stat-label">今日付费</small>
												<h4><?php echo ($todaypayuser); ?></h4>
										 </div>
									</div><!-- row -->
								  </div><!-- stat -->

								</div><!-- panel-heading -->
							  </div><!-- panel -->
							</div>
							<!-- col-sm-6 -->

							<div class="col-sm-6 col-md-3">
							  <div class="panel panel-dark panel-stat">
								<div class="panel-heading" style="background-color:rgb(29, 41, 57)">

								  <div class="stat">
									<div class="row">
									  <div class="col-xs-4">
										<i class="fa fa-money" style="border:0px;font-size:50px;padding:0px"></i>
									  </div>
									  <div class="col-xs-8">
										<small class="stat-label">总流水</small>
										<h1>￥<?php echo ($todaypay+$paymoney); ?></h1>
									  </div>
									</div><!-- row -->

									<div class="mb15"></div>

									<div class="row">
									  <div class="col-xs-6">
										<small class="stat-label">今日流水</small>
										<h4>￥<?php echo ($todaypay); ?></h4>
									  </div>

									  <div class="col-xs-6">
										<small class="stat-label">昨日流水</small>
										<h4>￥<?php echo ($yesterpay); ?></h4>
									  </div>
									</div><!-- row -->

								  </div><!-- stat -->

								</div><!-- panel-heading -->
							  </div><!-- panel -->
							</div><!-- col-sm-6 -->		
							<!-- col-sm-6 -->

							<div class="col-sm-6 col-md-3">
							  <div class="panel panel-danger panel-stat">
								<div class="panel-heading">

								  <div class="stat">
									<div class="row">
									  <div class="col-xs-4">
										<i class="fa fa-gamepad" style="border:0px;font-size:50px;padding:0px"></i>
									  </div>
									  <div class="col-xs-8">
										<small class="stat-label">总游戏数</small>
										<h1><?php echo ($gamescnt); ?></h1>
									  </div>
									</div><!-- row -->

									<div class="mb15"></div>

									<small class="stat-label">新增游戏</small>
									<h4><?php echo ($todaygames); ?></h4>

								  </div><!-- stat -->

								</div><!-- panel-heading -->
							  </div><!-- panel -->
							</div><!-- col-sm-6 -->
<?php if($role_type < 4): ?><div class="col-sm-6 col-md-3">
							  <div class="panel panel-primary panel-stat">
								<div class="panel-heading">

								  <div class="stat">
									<div class="row">
									  <div class="col-xs-4">
										<i class="fa fa-sitemap" style="border:0px;font-size:50px;padding:0px"></i>
									  </div>
									  <div class="col-xs-8">
										<small class="stat-label">总渠道数</small>
										<h1><?php echo ($agentcnt); ?></h1>
									  </div>
									</div><!-- row -->

									<div class="mb15">
									</div>
									
									<div class="row">
										  <div class="col-xs-5">
											<small class="stat-label">分包数</small>
											<h4><?php echo ($packagecnt); ?></h4>
										  </div>

										 <div class="col-xs-4">
												<small class="stat-label">新增渠道</small>
												<h4><?php echo ($todayagent); ?></h4>
										 </div>
									</div><!-- row -->
								  </div><!-- stat -->

								</div><!-- panel-heading -->
							  </div><!-- panel -->
							</div><?php endif; ?>			
						  <!-- /COLUMN 1 -->
						</div>
						
						<!-- COLUMN 2 -->
							<input type="hidden" id="paydata" name="paydata" value="<?php echo ($paydata); ?>">
							<input type="hidden" id="ulogindata" name="ulogindata" value="<?php echo ($ulogindata); ?>">
							<input type="hidden" id="uregindata" name="uregindata" value="<?php echo ($uregindata); ?>">
							<input type="hidden" id="upaydata" name="upaydata" value="<?php echo ($upaydata); ?>">
							<div class="row">
								<div class="col-md-6">
									<div class="box solid grey">
										<div class="box-title">
											<h4>用户流水</h4>
											<div class="tools">
												<a href="javascript:;" class="reload">
													<i class="fa fa-refresh"></i>
												</a>	
											</div>
										</div>
										<div class="box-body">
											<div class="divide-10"></div>
											<div id="chart-revenue" style="height:190px"></div>
											
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="box solid grey">
										<div class="box-title">
											<h4>活跃用户</h4>
											<div class="tools">
												<a href="javascript:;" class="reload">
													<i class="fa fa-refresh"></i>
												</a>	
											</div>
										</div>
										<div class="box-body">
											<div class="divide-10"></div>
											<div id="chart-revenue2" style="height:190px"></div>
											
										</div>
									</div>
								</div>
							</div>
						<!-- /COLUMN 2 -->
						<!-- COLUMN 3 -->
							<div class="row">
								<div class="col-md-6">
									<div class="box solid grey">
										<div class="box-title">
											<h4>新增用户</h4>
											<div class="tools">
												<a href="javascript:;" class="reload">
													<i class="fa fa-refresh"></i>
												</a>	
											</div>
										</div>
										<div class="box-body">
											<div class="divide-10"></div>
											<div id="chart-pay" style="height:190px"></div>
											
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="box solid grey">
										<div class="box-title">
											<h4></i>付费用户</h4>
											<div class="tools">
												<a href="javascript:;" class="reload">
													<i class="fa fa-refresh"></i>
												</a>	
											</div>
										</div>
										<div class="box-body">
											<div class="divide-10"></div>
											<div id="chart-login" style="height:190px"></div>
											
										</div>
									</div>
								</div>
							</div>
						<!-- /COLUMN 3 -->
					   <!-- /DASHBOARD CONTENT -->
					    
						<div class="footer-tools">
							<span class="go-top">
								<i class="fa fa-chevron-up"></i> Top
							</span>
						</div>
					</div>
	<!--/PAGE -->
	<!-- JAVASCRIPTS -->
	<!-- Placed at the end of the document so the pages load faster -->
	<!-- JQUERY -->
	<script src="/public/admin/js/jquery/jquery-2.0.3.min.js"></script>
	<!-- JQUERY UI-->
	<script src="/public/admin/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
	<!-- BOOTSTRAP -->
	<script src="/public/admin/js/bootstrap.min.js"></script>
	
		
	
	<!-- SLIMSCROLL -->
	<script type="text/javascript" src="/public/admin/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.min.js"></script>
	<script type="text/javascript" src="/public/admin/js/jQuery-slimScroll-1.3.0/slimScrollHorizontal.min.js"></script>
	<!-- BLOCK UI -->
	<script type="text/javascript" src="/public/admin/js/jQuery-BlockUI/jquery.blockUI.min.js"></script>
	<!-- SPARKLINES -->
	<script type="text/javascript" src="/public/admin/js/sparklines/jquery.sparkline.min.js"></script>
	<!-- EASY PIE CHART -->
	<script src="/public/admin/js/jquery-easing/jquery.easing.min.js"></script>
	<script type="text/javascript" src="/public/admin/js/easypiechart/jquery.easypiechart.min.js"></script>
	<!-- FLOT CHARTS -->
	<script src="/public/admin/js/flot/jquery.flot.min.js"></script>
	<script src="/public/admin/js/flot/jquery.flot.time.min.js"></script>
    <script src="/public/admin/js/flot/jquery.flot.selection.min.js"></script>
	<script src="/public/admin/js/flot/jquery.flot.resize.min.js"></script>
    <script src="/public/admin/js/flot/jquery.flot.pie.min.js"></script>
    <script src="/public/admin/js/flot/jquery.flot.stack.min.js"></script>
    <script src="/public/admin/js/flot/jquery.flot.crosshair.min.js"></script>

	<!-- TODO -->
	<script type="text/javascript" src="/public/admin/js/jquery-todo/js/paddystodolist.js"></script>
	<!-- TIMEAGO -->
	<script type="text/javascript" src="/public/admin/js/timeago/jquery.timeago.min.js"></script>
	<!-- FULL CALENDAR -->
	<script type="text/javascript" src="/public/admin/js/fullcalendar/fullcalendar.min.js"></script>
	<!-- COOKIE -->
	<script type="text/javascript" src="/public/admin/js/jQuery-Cookie/jquery.cookie.min.js"></script>
	<!-- GRITTER -->
	<script type="text/javascript" src="/public/admin/js/gritter/js/jquery.gritter.min.js"></script>
	<!-- CUSTOM SCRIPT -->
	<script src="/public/admin/js/newscript.js"></script>
	<script>
		jQuery(document).ready(function() {	
			var paydata = jQuery.parseJSON($('#paydata').val());
			var ulogindata = jQuery.parseJSON($('#ulogindata').val());
			var uregindata = jQuery.parseJSON($('#uregindata').val());
			var upaydata = jQuery.parseJSON($('#upaydata').val());
			
			App.setPage("index");  //Set current page
			App.setIndexData(paydata,ulogindata,uregindata,upaydata);
			App.init(); //Initialise plugins and elements
		});
	</script>
	<!-- /JAVASCRIPTS -->
</body>
</html>