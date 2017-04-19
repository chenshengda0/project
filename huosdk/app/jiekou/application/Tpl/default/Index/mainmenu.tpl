<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link href="<{$WEBSITE}>/css/admin/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$WEBSITE}>/js/jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//菜单的点击隐藏
		$(".secondmenu:eq(0)").show();
		$(".menuParent").click(function(){
			$('.secondmenu').hide();
			var myid = $(this).attr("myid");
			$("#"+myid).show();
		});
	});
</script>
<style>
.menuParent{ font-weight:bold}
</style>
</head>
<body>
<table height="100%" cellSpacing=0 cellPadding=0 width=170 background="<{$WEBSITE}>/images/admin/menu_bg.jpg" border=0>
	<tr>
    	<td vAlign=top align=middle>
      		<{$menu_str}>
		</td>
		<td width=1 bgColor="#d1e6f7"></td>
	</tr>
</table>
</body>
</html>