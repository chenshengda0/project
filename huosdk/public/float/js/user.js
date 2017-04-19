$(document).ready(function(){
		if(!window.name){
				window.name = 'test';
				window.location.reload();
		 }
		$("#updatepwd").click(function(){
			
			var oldpwdObj = $("#oldpwd");
			var newpwdObj = $("#newpwd");
			var unewpwdObj = $("#unewpwd");
			var tips = "";

			//var action = $("#lg-form").attr('action');

			var form_data = {
				oldpwd: oldpwdObj.val(),
				newpwd: newpwdObj.val(),
				unewpwd: unewpwdObj.val(),
				type: "updatepwd"
			};
			
			$.ajax({
				type: "POST",
				url: editpwd,
				data: form_data,
				error : function(XMLHttpRequest, textStatus, errorThrown) {   
					showmsg('读取超时，网络错误'); 
				},
				dataType:"json",
				success: function(result)
				{
					if (result.success){
						$("#lg-form").submit();
					}else{
						showmsg(result.msg);
					}
				}	
			});
			return false;
		});
	});

//
function showmsg(msg)
{
	$("#message").html('<li class="li_12"><span>'+msg+'</span></li>');
}

//检查用户名是否存在
function checkUserName() {
	var username = $('#username').val();
	var unLen = username.replace(/[^\x00-\xff]/g, "**").length;

	if (unLen < 3 || unLen > 12) {
		$("#checkusername").html('用户名长度必须大于3个字符和小于12个字符');
		$('#username').focus();
		return false;
	}
	var url = checkName;
	$.ajax({
		  type: "post",
		  url: url,
		  data:"username="+username,
		  dataType:"json",
		  success: function(data){
			if (data == 1){
				//$("#checkusername").html("该账号不存在");
				$('#username').focus();
				return true;
			}else{
				$("#checkusername").html("该账号不存在");
				$('#username').focus();
				return false;
			}
		  }
	});
	if (flag == 2) {
		$("#checkusername").html("该账号已经被注册");
		$('#username').focus();
		return false;	
	}
	
	$("#checkusername").html('用户名可以注册');
	return true;
}