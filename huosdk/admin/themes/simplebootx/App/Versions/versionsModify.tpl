<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>版本管理</title>
</head>
<script type="text/javascript" src="<{$WEBSITE}>/plus/ckeditor/ckeditor.js"></script>
<body>

<div>
    <div>
        <span>当前位置：版本管理->修改版本</span>
    </div>
    <br>
    <form method="post" name="theform" action="<{$WEBSITE}>?m=Versions&a=modifyApp">
        <div>
            <span>请填写版本 ： </span><input type="text" name="versions" value="<{$data.versions}>">
            <span>请填写路径 ： </span><input type="text" name="url" value="<{$data.url}>" size='60'><span style="color:#00F">注意带上前缀http://,如<{$DOMAINHOST}></span>
	<br>  
	    <span>请填写大小 ： </span><input type="text" name="size" value="<{$data.size}>">
	<table id="table1" width="100%">
	<tr>
		<td style="font-family: arial" width="9%">请填写内容 ：</td>
		<td align="" style="font-family: arial" width="95%">
		<textarea id="content" name="content"  style="width:710px;height:100px;"><{$data.content}></textarea>
		</td>
		 </tr>
	</table>
        </div>
        <br>

        <input type="hidden" name="action" value="versions">
	 <input type="hidden" name="ver_id" value="<{$data.id}>">
        <input type="submit" value="点击提交">
    </form>
</div>

<br>
</body>
</html>