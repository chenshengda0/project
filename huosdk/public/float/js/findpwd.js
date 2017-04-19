//用户名输入框默认值处理
$(function(){	
	$('#username').click(function(){
		var uname = $(this).val();
		if(uname == '请输入账号'){
			$(this).val('');
			return false;
		}
	}).blur(function(){
		var uname = $(this).val();
		if(uname == ''){
			$(this).val('请输入账号');
			return false;
		}
	});
})

//检查用户名是否存在
$(document).ready(function(){
	if(!window.name){
		window.name = 'test';
		window.location.reload();
	 }

	$("#find").click(function(){
		var usernameObj = $("#username");
		var form_data = {
			username: usernameObj.val(),
			type: "findpwd"
		};
		
		$.ajax({
			type: "POST",
			url: checkusername,
			data: form_data,
			error : function(XMLHttpRequest, textStatus, errorThrown) {   
				showmsg('读取超时，网络错误'); 
			},
			dataType:"json",
			success: function(result){
				if (result.success){
					$("#find-pwd").submit();
				}else{
					showmsg(result.msg);
				}
			}	
		});
		return false;
	});
});

//检查验证码是否正确
$(function(){
	$('#sms').click(function(){
		var code = $(this).val();
		if(code == '请输入验证码'){
			$(this).val('');
			return false;
		}		
		
	}).blur(function(){
		var code = $(this).val();
		if(code == ''){
			$(this).val('请输入验证码');
			return false;
		}
		
		$.ajax({
			type: "POST", //用POST方式传输  
            dataType: "JSON", //数据格式:JSON  
            url: checkCode, //目标地址  
            data: "code=" + code,  
            error: function (XMLHttpRequest, textStatus, errorThrown) { 
				showmsg('发送超时，网络错误');
			},  
            success: function (result){ 
				if(result.success){
					showmsgsu(result.msg);
				}else{
					showmsg(result.msg);
				}
			} 
		});
	})
})
  

//检查两次密码是否相同
$(function(){
	
	$('#pwd').click(function(){
		var pwd = $(this).val();
		if(pwd == '请输入新密码'){
			$(this).val('');
			return false;
		}
	}).blur(function(){
		var pwd = $(this).val();
		if(pwd == ''){
			$(this).val('请输入新密码');
			return false;
		}
	});
	
	$('#newpwd').click(function(){
		var code = $(this).val();
		if(code == '请再次输入新密码'){
			$(this).val('');
			return false;
		}
	}).blur(function(){
		var code = $(this).val();
		if(code == ''){
			$(this).val('请再次输入新密码');
			return false;
		}
		var pwd = $('#pwd').val();
		
		var newpwd = $('#newpwd').val();
		if(pwd != newpwd){
			$('#message').html('两次密码输入不一致');
			return false;
		}
	});
	
	//判断提交的信息是否正确
	$("#findok").click(function(){
		var code = $('#sms').val();
		var username = $("#username").val();
		var newpwd = $("#newpwd").val();
		var pwd = $('#pwd').val();
		
		var coderes = checkMobileCode(code);
		if(!coderes){
			showmsg("验证码不正确");
			return false;
		}
		
		if(pwd != newpwd){
			showmsg("两次密码输入不一致");
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: editpwd,
			data: "username="+username+"&newpwd="+newpwd+"&code="+code+"&pwd="+pwd,
			error : function(XMLHttpRequest, textStatus, errorThrown) {   
				showmsg('读取超时，网络错误'); 
			},
			dataType:"json",
			success: function(result){
				if (result.success){
					window.location = result.url;
				}else{
					showmsg(result.msg);
				}
			}
		});
	});
})

//错误显示返回的信息
function showmsg(msg){
	$("#message").html('<li class="li_12"><span>'+msg+'</span></li>');
}

//正确时显示返回的信息
function showmsgsu(msg){
	$("#message").html('<li class="li_12su"><span>'+msg+'</span></li>');
}

//检查手机号是否符合规则
function checkPhone(phone){
	var phone_match=/^[1][3458][0-9]{9}$/;
	if(phone_match.test(phone)){
		return true;
	}else{
		return false;
	}
}

//检查验证码是否符合规则
function checkMobileCode(code){
	var phone_match=/^[0-9]{4}$/;
	if(phone_match.test(code)){
		return true;
	}else{
		return false;
	}
}

//检查邮箱是否符合规则
function checkEmail(email){
	var phone_match=/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/;
	if(phone_match.test(email)){
		return true;
	}else{
		return false;
	}
}

/*-------------------------------------------*/  
var InterValObj; //timer变量，控制时间  
var count = 120; //间隔函数，1秒执行  
var curCount;//当前剩余秒数  
var code = ""; //验证码  
var codeLength = 6;//验证码长度  
function sendMessage() {  
    curCount = count;  
    var phone=$("#smobile").val();//手机号码  
	
	if(!checkPhone(phone)){
		var tips ="手机获取失败,请返回重新提交";
		showmsg(tips);
		return false;
	}else{  
		var step = 59;
		$('#btn').val('重新发送60');
		var _res = setInterval(function()
		{   
			$("#btnSendCode").attr("disabled", true);//设置disabled属性
			$('#btnSendCode').val('重新发送'+step);
			step-=1;
			if(step <= 0){
			$("#btnSendCode").removeAttr("disabled"); //移除disabled属性
			$('#btnSendCode').val('获取验证码');
			clearInterval(_res);//清除setInterval
			}
		},1000);
        //产生验证码  
       // for (var i = 0; i < codeLength; i++) {  
          //  code += parseInt(Math.random() * 9).toString();  
       // }  
         
		//向后台发送处理数据  
        $.ajax({  
            type: "POST", //用POST方式传输  
            dataType: "JSON", //数据格式:JSON  
            url: getcode, //目标地址  
            data: "phone=" + phone,  
            error: function (XMLHttpRequest, textStatus, errorThrown) { 
				showmsg('发送超时，网络错误');
			},  
            success: function (result){
				if(result.success){
					//设置button效果，开始计时 
					
					showmsgsu(result.msg); 
				}else{
					showmsg(result.msg); 
				}
			}  
        });  
    }
}   

function sendemailMessage() {  
    curCount = count;  
    var email=$("#email").val();//手机号码  
	
	if(!checkEmail(email)){
		var tips ="邮箱获取失败,请返回重新提交";
		showmsg(tips);
		return false;
	}
    else{  
		//向后台发送处理数据  
        $.ajax({  
            type: "POST", //用POST方式传输  
            dataType: "JSON", //数据格式:JSON  
            url: getcode, //目标地址  
            data: "email=" + email,  
            error: function (XMLHttpRequest, textStatus, errorThrown) { 
				showmsg('发送超时，网络错误');
			},  
            success: function (result){
				if(result.success){
					//设置button效果，开始计时 
					$("#btnSendCode").attr("disabled", "true");  
					$("#btnSendCode").val("请在" + curCount + "秒内输入验证码"); 
					showmsgsu(result.msg); 
					InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次 
				}else{
					showmsg(result.msg); 
				}
			}  
        });  
    }
}

//timer处理函数  
function SetRemainTime() {  
    if (curCount == 0) {                  
        window.clearInterval(InterValObj);//停止计时器  
        $("#btnSendCode").removeAttr("disabled");//启用按钮  
        $("#btnSendCode").val("重新发送验证码");  
        code = ""; //清除验证码。如果不清除，过时间后，输入收到的验证码依然有效      
    }  
    else {  
        curCount--;  
        $("#btnSendCode").val("请在" + curCount + "秒内输入验证码");  
    }  
}
