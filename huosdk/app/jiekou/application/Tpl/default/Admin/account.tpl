<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>账号管理</title>
<link href="<{$WEBSITE}>/css/admin/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$WEBSITE}>/js/admin.js"></script>
</head>
<body>
<div>
	<span>当前位置：账号权限管理->账号管理</span>
</div>
<br>
<div>
    <div>
        <span>添加账号：</span>
    </div>
    <form method="post" name="theform" action="<{$WEBSITE}>/admin/index.php?m=Admin&a=account">
		<table>
			<tr>
				<td>请填写账号</td><td><input type="text" name="username" value=""></td><td></td>
				<td>请填写密码</td><td><input type="password" name="password" value=""></td>
			</tr>
			<tr></tr>
			<tr>
				<td>请填写备注</td><td><input type="text" name="beizhu" value=""></td><td></td>
				<td>所属部门</td><td><select name="departmentid">
										<option value="0">请选择部门</option>
										<volist name="deplist" id="data">
											<option value="<{$data.id}>"><{$data.name}></option>
										</volist>
									</select></td>
			</tr>
		</table>
        
        <input type="hidden" name="action" value="add">
        <input type="submit" value="点击提交">
    </form>
</div>
<hr>
<br>
<br>
<div>
    <div>
        <span>账号列表</span>
    </div>
    <br>
    <form name="form2" method="post" action="<{$WEBSITE}>/admin/index.php?m=Admin&a=account">
    <div>
        <span>请填写账号 ： </span><input type="text" name="username" value="<{$username}>">&nbsp;&nbsp;&nbsp;
        <span>请填写备注 ： </span><input type="text" name="beizhu" value="<{$beizhu}>">
        <input type="submit" value="点击查询">
    </div>
    <br>
    <div>
        <table width="100%" border="1" cellpadding="2" cellspacing="0">
        	<thead>
            	<tr>
                	<th>序号</th>
                    <th>账号名称</th>
                    <th>所属部门</th>
                    <th>备注</th>
                    <td>操作</td>
                </tr>
            </thead>
            <volist name="list" id="data">
            	<tr onmouseout="mleave(this)" onmouseleave="mleave(this)" onmouseover="mover(this)">
                	<td><{$data.id}></td>
                    <td><a href="<{$WEBSITE}>/admin/index.php?m=Admin&a=updatePsw&id=<{$data.id}>&depid=<{$data.department_id}>&username=<{$data.username}>"><{$data.username}></a></td>
                    <td>
                    	<select name="departmentid" disabled="disabled">
                            <volist name="deplist" id="dep">
                                <option value="<{$dep.id}>" <if condition="$dep['id'] eq $data['department_id']"> selected="selected"</if>><{$dep.name}></option>
                            </volist>
                        </select>
                    </td>
                    <td><{$data.beizhu}></td>
                    <td><a href="<{$WEBSITE}>/admin/index.php?m=Admin&a=delectAcount&id=<{$data.id}>&username=<{$data.username}>">删除账号</a>
		    <if condition="$data.status eq '0'">
			 <a href="<{$WEBSITE}>/admin/index.php?m=Admin&a=freezeAcount&id=<{$data.id}>&username=<{$data.username}>">冻结账号</a>
		    <else />
		    <a href="<{$WEBSITE}>/admin/index.php?m=Admin&a=recoverAcount&id=<{$data.id}>&username=<{$data.username}>">解封账号</a>
		    </if>
		    </td>
                </tr>
            </volist>
        </table>
        <div><{$page}></div>
    </div>
    </form>
</div>

</body>
</html>