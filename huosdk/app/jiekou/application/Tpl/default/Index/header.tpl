<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link href="<{$WEBSITE}>/css/admin/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$WEBSITE}>/js/jquery.js"></script>
</head>
<style type="text/css">
/*
.bj_1{ background:url(../images/admin/bj_1.jpg) no-repeat; line-height:30px; width:740px; padding-left:40px;}
*/
.bj_1{line-height:30px; width:840px; padding-left:40px;}
.bj_1 a{display:block; float:left; color:#FFFFFF; margin-right:20px; text-decoration:none;}
/*
.bj_2{background:url(../images/admin/bj_3.jpg) no-repeat right; overflow:hidden;}
*/
.bj_2{overflow:hidden;}
.bj_2 a{color:#fff !important;}
.bj_2 a:hover{text-decoration:none;}
.active{background:url(../images/admin/bj_2.jpg) repeat-x; line-height:30px; height:30px; }
.bj_1 a.active{color:#000 !important; padding:0 15px;}
.bj_1 a.active:hover{ text-decoration:none;}
</style>

<script type="text/javascript">
function one(){
	var web = document.getElementById("webname").value;
	window.open(web+"/admin/index.php?m=Index&a=main","main");
}
$(document).ready(function(){
	$("#menulist a").click(function(){
		$("#menulist a").removeClass("active");
		$(this).addClass("active");
		one();
	});
});
</script>
<body>
<table cellSpacing=0 cellPadding=0 width="100%" background="<{$WEBSITE}>/images/admin/header_bg.jpg" border=0>
	<tr height=56>
		<td width=260><img height=56 src="<{$WEBSITE}>/images/admin/header_left.jpg" width=260></td>
		<td style="font-weight: bold; color: #fff; padding-top: 20px" align="middle" id="menulist">
			<div class="bj_1">
				<div class="bj_2">
					<a href="<{$WEBSITE}>/admin/index.php?m=Index&a=mainmenu&type=1" target="mainmenu" class='active'>SDK联运后台</a>
					<a href="<{$WEBSITE}>/admin/index.php?m=Index&a=mainmenu&type=2" target="mainmenu">运营平台及产品联运官网后台</a>
					<a href="<{$WEBSITE}>/admin/index.php?m=Index&a=mainmenu&type=3" target="mainmenu">app商店后台</a>
					<a href="<{$WEBSITE}>/admin/index.php?m=Index&a=mainmenu&type=4" target="mainmenu">移动端运营平台后台</a>
					<a href="<{$WEBSITE}>/admin/index.php?m=Index&a=mainmenu&type=7" target="mainmenu">星游尚天官网后台</a>
					<a href="<{$WEBSITE}>/admin/index.php?m=Index&a=mainmenu&type=5" target="mainmenu">推广系统后台</a>
					<a href="<{$WEBSITE}>/admin/index.php?m=Index&a=mainmenu&type=6" target="mainmenu">系统</a>
				</div>
			</div>
		</td>
		<td align=right width=268><img height=56 src="<{$WEBSITE}>/images/admin/header_right.jpg" width=268></td>
	</tr>
</table>

<input type="hidden" name="webname" id="webname" value="<{$WEBSITE}>">
<input type="hidden" name="typeval" id="typeval" value="2">
<table cellSpacing=0 cellPadding=0 width="100%" border=0>
	<tr bgColor="#1c5db6" height=4>
    	<td></td>
	</tr>
</table>
</body>
</html>
