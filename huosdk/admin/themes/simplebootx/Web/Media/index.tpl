<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>后台</title>
	<link rel="stylesheet" type="text/css" href="<{$DOMAINHOST}>/plus/jquery-easyui-1.3.5/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<{$DOMAINHOST}>/plus/jquery-easyui-1.3.5/themes/icon.css">
	<link rel="stylesheet" href="<{$DOMAINHOST}>/css/admin/base.css">
	<script type="text/javascript" src="<{$DOMAINHOST}>/js/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="<{$DOMAINHOST}>/plus/jquery-easyui-1.3.5/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<{$DOMAINHOST}>/js/outlook2.js"></script>
	
	<script type="text/javascript">
		var url;
		function newUser(){
			$('#dlg').dialog('open').dialog('setTitle','New User');
			$('#fm').form('clear');
			url = '<{$WEBSITE}>?m=Media&a=addMedia';
		}
		function editUser(){
			$('#fm').form('clear');
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dlg').dialog('open').dialog('setTitle','Edit User');
				$('#fm').form('load',row);
				url = '<{$WEBSITE}>?m=Media&a=save';
			}
		}
		function saveUser(){
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.success){
						$('#dlg').dialog('close');		// close the dialog
						$('#dg').datagrid('reload');	// reload the user data
						$.messager.show({
							title: 'success',
							msg: result.msg
						});
					} else {
						$.messager.show({
							title: 'Error',
							msg: result.msg
						});
					}
				}
			});
		}
		function removeUser(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','是否要删除该记录?',function(r){
					if (r){
						$.post('<{$WEBSITE}>?m=Media&a=remove',{id:row.id},function(result){
							if (result.success){
								$('#dg').datagrid('reload');	// reload the user data
								$.messager.show({
										title: 'success',
										msg: result.msg
								});
							} else {
								$.messager.show({	// show error message
									title: 'Error',
									msg: result.msg
								});
							}
						},'json');
					}
				});
			}
		}
	</script>

	<style type="text/css">

		*{
			font-size:14px;
		        font-family:'微软雅黑';
			text-decoration:none;
			line-height: 20px;
		}

		.stat_table td{
			text-align:center;
		}

		.stat_table thead td{
			text-align:left;
		}


		.main_left{
			width:200px;line-height:25px;overflow:hidden;
		}

		.main_left a{
			color:#555;
			text-decoration:none;
		}

		.main_left a:hover{
			text-decoration:none;
		}
		.main_header{

			height:50px;font-size:12px;overflow:hidden;
		}
		.main_header a{
			text-decoration: none;
			
		}

		.main_header a:hover{
			text-decoration: none;
			
		}

		.main_header a:visit{

			text-decoration: none;
			
		}
		.main_bottom{

			height:17px;padding:5px;font-family:arial
		}



		.accordion .accordion-header {
		    border-top-width: 0;
		    cursor: pointer;
		}


		.accordion .accordion-header .panel-title {
		    font-weight: bold;
		    font-size:14px;
		    padding:5px;
		}

		.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited {
		    text-decoration: none;
		    font-weight: normal;
		}

		.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
		   
		    font-weight: normal;
		}

		</style>
    </head>
    <body>

	<table id="dg" style="height:500px"
			title="媒体" singleSelect="true" fitColumns="true" remoteSort="false"
			url="<{$WEBSITE}>?m=Media&a=mlist" 
			toolbar="#toolbar"
			pagination="true">
		<thead>
			<tr>
				<th field="name" width="80" sortable="true">媒体名称</th>
				<th field="url" width="120" sortable="true">媒体路径</th>
				<th field="id" width="0" sortable="false" hidden="hidden">ID</th>
				<th field="icon" width="0" sortable="false" hidden="hidden">媒体图标</th>
			</tr>
		</thead>
	</table>

	<div id="toolbar">
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onClick="newUser()">添加</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onClick="editUser()">编辑</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onClick="removeUser()">删除</a>
	</div>

	<div id="dlg" class="easyui-dialog" style="width:750px;height:280px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons">
		<form id="fm" method="post" enctype="multipart/form-data"  >
			<table>
				<tr>
					<td></td>
					<td><input name="id" type="hidden"></input></td>
				</tr>
				<tr>
					<td>媒体图标:</td>
					<td><input type="file" name="image"></input>
					<font color="red">*图片尺寸为210*62</font>
					</td>
				</tr>
				<tr>
					<td>媒体路径:</td>
					<td><input name="url" type="text"></input><font color="red">*请添加前缀http://，如http://www.baidu.com </font></td>
				</tr>
				<tr>
					<td>媒体名称:</td>
					<td><input name="name" type="text"></input><font color="red">*</font></td>
				</tr>
			</table>
			
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="saveUser()">Save</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onClick="javascript:$('#dlg').dialog('close')">Cancel</a>
	</div>

	<script>
		var cardview = $.extend({}, $.fn.datagrid.defaults.view, {
			renderRow: function(target, fields, frozen, rowIndex, rowData){
				var cc = [];
				cc.push('<td colspan=' + fields.length + ' style="padding:10px 5px;border:0;">');
				if (!frozen){
					//var aa = rowData.itemid.split('-');
					var img = rowData.icon;
					cc.push('<img src="<{$GAMEHOST}>/upfiles/image/' + img + '" style="width:150px;float:left">');
					cc.push('<div style="float:left;margin-left:20px;">');
					for(var i=0; i<fields.length; i++){
						if(i < fields.length-2){
							var copts = $(target).datagrid('getColumnOption', fields[i]);
							cc.push('<p><span class="c-label">' + copts.title + ':</span> ' + rowData[fields[i]] + '</p>');
						}
					}
					cc.push('</div>');
				}
				cc.push('</td>');
				return cc.join('');
			}
		});
		$(function(){
			$('#dg').datagrid({
				view: cardview
			});
		});
	</script>
	<style type="text/css">
		.c-label{
			display:inline-block;
			width:100px;
			font-weight:bold;
		}
	</style>
</body>
</html>