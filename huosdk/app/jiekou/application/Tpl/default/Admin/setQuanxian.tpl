<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>权限管理</title>
<link href="<{$WEBSITE}>/css/admin/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$WEBSITE}>/js/admin.js"></script>
</head>
<style type="text/css">
#thetable tr td a {
	text-decoration:none;
	color:#333333;
	font-size:14px;
}
</style>
<body>
<div>
	<span>当前位置：账号权限管理->权限管理</span>
</div>
<br>
<form name="theform" action="" method="post">
<div>
<span>权限组名称：</span>
<select name="bumenid" onchange="self.location.href='<{$WEBSITE}>/admin/index.php?m=Admin&a=quanxian&departmentid='+options[selectedIndex].value">
	<option value="0">请选择用户组</option>
    <volist name="departmentlist" id="data">
    	<option value="<{$data.id}>" <if condition="$data['id'] eq $departmentid"> selected="selected"</if>><{$data.name}></option>
    </volist>
</select>
<hr>
</div>
<span>请选择菜单：</span><span style="color:#FF0000">（注：点击相应菜单可以进行修改菜单名称及链接）</span>
<div>

<table width="100%" border="1" cellpadding="2" cellspacing="0" id="thetable">
<volist name='first' id='firstlist'>
	<tr onmouseout="mleave(this)" onmouseleave="mleave(this)" onmouseover="mover(this)">
		<td style="text-align:center;"><{$firstlist.houtai}></td>
    	<td><span><strong>
	<a href='<{$WEBSITE}>/admin/index.php?m=Admin&a=menuModify&first_id=<{$firstlist.id}>'>
	<{$firstlist.first}>:</a></strong></span>
            <volist name='second' id='secondlist'>
                <if condition="$secondlist['parentsid'] eq $firstlist['id']">
                    <input name='menuid[]' type='checkbox' value='<{$secondlist.id}>' <if condition="in_array($secondlist['id'],$menuidarr)"> checked="checked"</if> />
		    <a href='<{$WEBSITE}>/admin/index.php?m=Admin&a=menuModify&second_id=<{$secondlist.id}>'><{$secondlist.second}></a>
                </if>
            </volist>
        </td>
    </tr>
</volist>
</table>
<br>
<input type="hidden" name="action" value="menu">
<input type="submit" value="点击更新">
</div>
</form>
</body>
</html>