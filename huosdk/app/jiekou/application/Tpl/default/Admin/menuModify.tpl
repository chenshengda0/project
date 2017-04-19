<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>推广员后台--菜单管理</title>
</head>
<body>

<if condition="$firstdata['id'] gt 0">
<div>
	 <div>
		<span>编辑一级菜单</span>
	 </div>
	 <br>
	<form method="post" name="theform" action="<{$WEBSITE}>/admin/index.php?m=Admin&a=saveModify">
	<div>
		   <span>请填写菜单名 ： </span><input type="text" name="first" value="<{$firstdata.first}>">
	</div>
	<br>
	<input type="hidden" name="action" value="first">
	<input type="hidden" name="first_id" value="<{$firstdata.id}>">
	<input type="submit" value="点击提交">
	</form>
</div>
<hr>
</if>
<br><br>

<if condition="$seconddata['id'] gt 0">
<div>
    <div>
        <span>编辑二级菜单</span>
    </div>
    <br>
    <form method="post" name="theform" action="<{$WEBSITE}>/admin/index.php?m=Admin&a=saveModify">
        <div>
            <span>请填写二级菜单 ： </span><input type="text" name="second" value="<{$seconddata.second}>">
            <span>请填写二级菜单链接 ： </span><input type="text" name="url" value="<{$seconddata.url}>">
        </div>
        <br>

        <input type="hidden" name="action" value="second">
	<input type="hidden" name="second_id" value="<{$seconddata.id}>">
        <input type="submit" value="点击提交">
    </form>
</div>
<hr>
</if>
</body>
</html>