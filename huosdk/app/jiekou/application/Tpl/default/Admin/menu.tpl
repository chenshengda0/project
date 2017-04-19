<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>菜单管理</title>
</head>
<body>
<div>
	<span>当前位置：账号权限管理->菜单管理</span>
</div>
<br>
<div>
    <div>
        <span>添加一级菜单：</span>
    </div>
    <form method="post" name="theform" action="<{$WEBSITE}>/admin/index.php?m=Admin&a=menu">
        <div>
            <span>请填写菜单名 ： </span><input type="text" name="first" value="">
        </div>
        <br>

        <input type="hidden" name="action" value="first">
        <input type="submit" value="点击提交">
    </form>
</div>
<hr>
<br><br>
<div>
    <div>
        <span>添加二级菜单：</span><span style="color:#FF0000">(注：必须要添加好相应的一级菜单之后才能添加二级菜单)</span>
    </div>
    <br>
    <form method="post" name="theform" action="<{$WEBSITE}>/admin/index.php?m=Admin&a=menu">
		<table>
			<tr>
				<td>请填写二级菜单：</td><td><input type="text" name="second" value=""></td>
				<td></td>
				<td>请填写二级菜单链接：</td><td><input type="text" name="url" value=""><span style="color:#FF0000">填写时记得带上http://</span></td>
			</tr>
			<tr></tr>
			<tr>
				<td>请选择是否显示：</td><td><select name="status">
											<option value="0">显示</option>
											<option value="1">隐藏</option>
										</select></td><td></td>
				<td>请选择主菜单：</td><td><select name="firstid">
											<option value="0">请选择</option>
											<volist name="firstlist" id="data">
												<option value="<{$data.id}>"><{$data.first}></option>
											</volist>
										</select></td>
			</tr>
			<tr></tr>
			<tr>
				<td>请选择后台系统</td><td><select name="type">
											<option value="0">请选择</option>
											<option value="1">SDK联运后台</option>
											<option value="2">运营平台及产品联运官网后台</option>
											<option value="3">app商店后台</option>
											<option value="4">移动端运营平台后台</option>
											<option value="5">推广系统后台</option>
											<option value="7">星游尚天官网后台</option>
											<option value="6">系统</option>
										  </select></td>
			</tr>
			<tr></tr>
			<tr>
			<input type="hidden" name="action" value="second">
				<td><input type="submit" value="点击提交"></td>
			</tr>
		</table>
    </form>
</div>
</body>
</html>