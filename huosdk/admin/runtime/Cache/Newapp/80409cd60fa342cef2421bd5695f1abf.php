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
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="javascript:;">游戏列表</a></li>
			<li><a href="<?php echo U('Newapp/Game/add');?>" target="_self">游戏添加</a></li>
		</ul>
		
		<form class="well form-search" method="post"
			action="<?php echo U('Newapp/Game/index');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20"> 当前状态： <select class="select_2"
						name="gstatus" id="gstatus">
							<?php if(is_array($gamestatus)): foreach($gamestatus as $k=>$vo): $gs_select=$k==$formget['gstatus'] ?"selected":""; ?>
							<option value="<?php echo ($k); ?>"<?php echo ($gs_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
					</select>&nbsp;&nbsp; 游戏标签： <select class="select_2" name="gtype" id="gtype">
							<?php if(is_array($gtypes)): foreach($gtypes as $k=>$vo): $gt_select=$k==$formget['gtype'] ?"selected":""; ?>
							<option value="<?php echo ($k); ?>"<?php echo ($gt_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
					</select>&nbsp;&nbsp; 游戏名称：
					
					 <select class="select_2" name="appid" id="appid">
				<?php if(is_array($games)): foreach($games as $k=>$vo): $pt_select=$k==$formget['appid'] ?"selected":""; ?>
				<option value="<?php echo ($k); ?>" <?php echo ($pt_select); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
			</select>
			
					<!-- 
					 <input type="text" name="gname"
						style="width: 200px;" value="<?php echo ($formget['gname']); ?>"
						placeholder="请输入游戏名...">  -->
						
						
						<input type="submit"
						name="submit" class="btn btn-primary" value="搜索" />
					</span>
				</div>
			</div>
		</form>
				
		<form class="js-ajax-form" action="" method="post">
			<div class="table-actions">
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Newapp/Game/listorders');?>">排序</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Newapp/Game/check',array('online'=>1));?>" data-subcheck="true">上线</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Newapp/Game/check',array('offline'=>1));?>" data-subcheck="true">下线</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Newapp/Game/top',array('hot'=>1));?>" data-subcheck="true">热门</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Newapp/Game/top',array('unhot'=>1));?>" data-subcheck="true">取消热门</button>
				<!-- <button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Newapp/Game/recommend',array('recommend'=>1));?>" data-subcheck="true">推荐</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Newapp/Game/recommend',array('unrecommend'=>1));?>" data-subcheck="true">取消推荐</button>
				 -->
				 <button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Newapp/Game/delete');?>" data-subcheck="true" data-msg="你确定删除吗？"><?php echo L('DELETE');?></button>
			</div>
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th width="15"><label><input type="checkbox" class="js-check-all" data-direction="x" data-checklist="js-check-x"></label></th>
						<th width="50">排序</th>
						<th width="50">游戏名称(ICON)</th>
						<th width="50">游戏标签</th>
						<th width="50">网游单机</th>
						<!--<th width="50">是否破解</th>
						 <th width="50">是否推荐</th> -->
						<th width="50">是否热门</th>
						<th width="50">添加时间</th>
						<!-- <th width="50">上线时间</th> -->
						<th width="50">当前状态</th>
						<th width="50">管理操作</th>
					</tr>
				</thead>
				<?php $gamecategory=array("2"=>"网游","1"=>"单机"); $gameclasses=array("1"=>"正版","2"=>"H5","3"=>"IOS","4"=>"破解"); $top_status=array("2"=>"精选","1"=>"热门","0"=>"普通"); $recommend_status=array("1"=>"已推荐","0"=>"未推荐"); ?>
				<?php if(is_array($appgames)): foreach($appgames as $key=>$vo): ?><tr>
					<td><input type="checkbox" class="js-check" data-yid="js-check-y" data-xid="js-check-x" name="ids[]" value="<?php echo ($vo["id"]); ?>" title="ID:<?php echo ($vo["id"]); ?>"></td>
					<td><input name="listorders[<?php echo ($vo["id"]); ?>]" style="width:60px" class="input input-order" type="text" size="5" value="<?php echo ($vo["listorder"]); ?>" title="ID:<?php echo ($vo["id"]); ?>"></td>
					<td> 
					<a class="img_a" href="javascript:onClick=image_priview('<?php echo ($vo[mobile_icon]); ?>')">
					<img class="img_prew" src="<?php echo sp_get_asset_upload_path($vo['mobile_icon']);?>" style="height: 50px;"></img></a>
					<span><?php echo ($vo["name"]); ?></span>
					
					</td>
					<td><?php echo (hs_get_game_type($vo["type"])); ?></td>
					<td><?php echo ($gamecategory[$vo[category]]); ?></td>
					<!--<td><?php echo ($gameclasses[$vo[classify]]); ?></td>
					<td><?php echo ($recommend_status[$vo[recommend]]); ?></td> -->
					<td><?php echo ($top_status[$vo[is_hot]]); ?></td>
					<td><?php echo (date('Y-m-d H:i:s',$vo["create_time"])); ?></td>
					<td><?php echo ($gamestatus[$vo['is_app']]); ?></td>
					</td>
					<td>
						<a href="<?php echo U('Newapp/Game/edit',array('id'=>$vo['id']));?>"><?php echo L('EDIT');?></a> | 
						<a href="<?php echo U('Newapp/Game/delete',array('id'=>$vo['id']));?>" class="js-ajax-delete"><?php echo L('DELETE');?></a></td>
				</tr><?php endforeach; endif; ?>
			</table>
			<div class="pagination"><?php echo ($Page); ?></div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
	<script type="text/javascript"	src="/public/js/content_addtop.js?t=<?php echo time();?>"></script>
	<script>
		function refersh_window() {
			var refersh_time = getCookie('refersh_time');
			if (refersh_time == 1) {
				window.location = "<?php echo U('Newapp/Game/index',$formget);?>";
			}
		}
		setInterval(function() {
			refersh_window();
		}, 2000);
		$(function() {
			setCookie("refersh_time", 0);
			Wind.use('ajaxForm', 'artDialog', 'iframeTools', function() {
				//批量移动
				$('.js-articles-move').click(function(e) {
					var str = 0;
					var id = tag = '';
					$("input[name='ids[]']").each(function() {
						if ($(this).attr('checked')) {
							str = 1;
							id += tag + $(this).val();
							tag = ',';
						}
					});
					if (str == 0) {
						art.dialog.through({
							id : 'error',
							icon : 'error',
							content : '您没有勾选信息，无法进行操作！',
							cancelVal : '关闭',
							cancel : true
						});
						return false;
					}
					var $this = $(this);
					art.dialog.open("/index.php?g=portal&m=Newapp/Game&a=move&ids="+ id, {
						title : "批量移动",
						width : "80%"
					});
				});
			});
		});
	</script>
</body>
</html>