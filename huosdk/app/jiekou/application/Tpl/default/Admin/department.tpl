<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$WEBSITE}>/css/admin/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$WEBSITE}>/js/admin.js"></script>
<title>部门管理</title>
</head>
<body>

<div>
    <div>
        <span>添加部门：</span>
    </div>
    <br>
    <form method="post" name="theform" action="<{$WEBSITE}>/admin/index.php?m=Admin&a=department">
        <div>
            <span>请填写部门名称 ： </span><input type="text" name="departmentname" value="">
        </div>
        <br>
        <input type="hidden" name="action" value="add">
        <input type="submit" value="点击提交">
    </form>
</div>
<hr>
<br>
<br>
<div>
    <div>
        <span>部门列表：</span>
    </div>
    <br>
    <div>
        <table width="100%" border="1" cellpadding="2" cellspacing="0">
        	<thead>
            	<tr>
                	<th>序号</th>
                    <th>部门名称</th>
                    <th>创建时间</th>
                </tr>
            </thead>
            <volist name="list" id="data">
            	<tr onmouseout="mleave(this)" onmouseleave="mleave(this)" onmouseover="mover(this)">
                	<td><{$data.id}></td>
                    <td><{$data.name}></td>
                    <td><{$data.create_time|date='Y-m-d H:i:s',###}></td>
                </tr>
            </volist>
        </table>
        <div><{$page}></div>
    </div>
    </form>
</div>

</body>
</html>