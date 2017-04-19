<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>权限管理</title>
<link href="<{$WEBSITE}>/css/admin/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$WEBSITE}>/js/admin.js"></script>
<script type="text/javascript" src="<{$WEBSITE}>/js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var gamearr =  $('#test').val();
	var gameJsons = eval("("+gamearr+")");	
	

});
</script>
</head>
<body>
<div>
	<span>当前位置：账号权限管理->权限管理</span>
</div>
<br>
<div>
<input type="hidden" id="test" value="<{$arr}>">
</div>
</body>
</html>