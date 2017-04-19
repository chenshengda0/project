    <!DOCTYPE html>
    <html>
    <head>
	<link rel="stylesheet" type="text/css" href="<{$DOMAINHOST}>/plus/jquery-easyui-1.3.5/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<{$DOMAINHOST}>/plus/jquery-easyui-1.3.5/themes/icon.css">
	<link rel="stylesheet" href="<{$DOMAINHOST}>/css/admin/base.css">
	<script type="text/javascript" src="<{$DOMAINHOST}>/js/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="<{$DOMAINHOST}>/plus/jquery-easyui-1.3.5/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<{$DOMAINHOST}>/js/outlook2.js"></script>

	<script type="text/javascript" charset="utf-8" src="<{$DOMAINHOST}>/plus/ueditor1_4_3/ueditor.config.js"></script>
	 <script type="text/javascript" charset="utf-8" src="<{$DOMAINHOST}>/plus/ueditor1_4_3/ueditor.all.min.js"> </script>
	 <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
	 <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
	 <script type="text/javascript" charset="utf-8" src="<{$DOMAINHOST}>/plus/ueditor1_4_3/lang/zh-cn/zh-cn.js"></script>
	 <script type="text/javascript" charset="utf-8" src="<{$DOMAINHOST}>/js/easyui-ueditor.js"></script>
	
	

	<script>
		$(function(){
			$('#dg').datagrid({
				columns:[[
					{field:'id',title:'ID',hidden:'hidden'},
					{field:'versions',title:'版本'},
					{field:'url',title:'路径'},
					{field:'create_time',title:'创建时间',
						formatter:function(value,row,index){  
							var tt=new Date(parseInt(value) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
							return tt; 
						 }  
					}
				]]
			});
		});
		
		//增加删除修改
		var url;
		function newUser(){
			$('#fm').form('reset');
			$('#dlg').dialog('open').dialog('setTitle','New User');
			
			url = '<{$WEBSITE}>?m=Versions&a=insertApp';
		}
		function editUser(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#fm').form('reset');
				$('#dlg').dialog('open').dialog('setTitle','Edit User');
				$('#fm').form('load',row);
				url = '<{$WEBSITE}>?m=Versions&a=modifyApp';
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
						$.post('<{$WEBSITE}>?m=Versions&a=remove',{id:row.id},function(result){
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
    <div style="margin:5px 0;"></div>
			<div  title="DataGrid">
				<table id="dg" class="easyui-datagrid" style="height:480px"
					url="<{$WEBSITE}>?m=Versions&a=versionsList" toolbar="#toolbar"
					title="Load Data"
					pagination="true" singleSelect="true">
					
				</table>
				
				<div id="toolbar" style="padding:5px;height:auto">
					<div style="margin-bottom:5px">
						<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">添加</a>
						<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">编辑</a>
						<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeUser()">删除</a>
					</div>
					
				</div>
				<div id="dlg" class="easyui-dialog" style="width:1170px;height:520px;padding:10px 20px"
					closed="true" buttons="#dlg-buttons">
					<form id="fm" method="post" enctype="multipart/form-data" novalidate >
						<table>
							<tr>
								<td></td>
								<td><input name="id" type="hidden"></input></td>
							</tr>
							<tr>
								<td>版本:</td>
								<td><input name="versions" type="text" style="width: 130px"></input><font color="red">*</font></td>
							</tr>
							<tr>
								<td>路径:</td>
								<td><input name="url" type="text" style="width: 130px"></input><font color="red">*</font></td>
							</tr>
							<tr>
								<td>大小:</td>
								<td><input name="size" type="text" style="width: 130px"></input><font color="red">*</font></td>
							</tr>
							
							<tr>
								 <td>版本内容:</td>
								 <td>
									<textarea name="content" style="width:710px;height:100px;"></textarea>
								  </td>
							</tr>
							
							  <tr>
							    <td></td>
							    <td>
								<input type="hidden" name="action" value='versions' ></td>
							  </tr>
							 
						</table>
						
					</form>
				</div>
				<div id="dlg-buttons">
					<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser()">保存</a>
					<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a>
				</div>
			 </div>
    </div>
    </body>
    </html>