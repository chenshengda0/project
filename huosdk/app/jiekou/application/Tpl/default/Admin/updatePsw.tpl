<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>账号管理</title>
</head>
<body>
<div>
	<div>
        <span>当前位置：账号权限管理->账号管理</span>
    </div>
	<br>
    <form method="post" name="theform" action="<{$WEBSITE}>/admin/index.php?m=Admin&a=updatePsw">
		<table>
			<tr>
				<td>请填写账号：</td><td><{$username}></td>
			</tr>
			<tr>
				<td>请填写密码：</td><td><input type="password" name="password" value=""></td>
			</tr>
			<tr>
				<td>请填写确认密码：</td><td><input type="password" name="chkpassword" value=""></td>
			</tr>
		</table>

        <input type="hidden" name="id" value="<{$id}>">
        <input type="hidden" name="depid" value="<{$depid}>">
        <input type="hidden" name="action" value="updatepsw">
		<br>
        <input type="submit" value="点击提交">
    </form>
</div>
</body>
</html>