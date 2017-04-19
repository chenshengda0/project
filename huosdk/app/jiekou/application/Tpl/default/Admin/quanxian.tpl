<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>权限管理</title>
<link href="<{$WEBSITE}>/css/admin/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$WEBSITE}>/js/admin.js"></script>
</head>
<body>
<div>
	<span>当前位置：账号权限管理->权限管理</span>
</div>
<br>
<div>
<table width="50%" border="1" cellpadding="2" cellspacing="0">
	<thead>
		<tr>
			<th>权限组</th>
			<th>操作</th>
		</tr>
	</thead>
	<volist name="list" id="data">
		<tr onmouseout="mleave(this)" onmouseleave="mleave(this)" onmouseover="mover(this)">
			<td style="text-align:center"><{$data.name}></td>
			<td style="text-align:center"><a href="<{$WEBSITE}>/admin/index.php?m=Admin&a=setQuanxian&departmentid=<{$data.id}>">修改</a></td>
		<tr>
	</volist>
</table>
</div>
</body>
</html>