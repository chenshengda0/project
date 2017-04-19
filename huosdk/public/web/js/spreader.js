

//检测手机格式
function checkPhone(phone){
	var phone_match=/^[1][3458][0-9]{9}$/;
	if(phone_match.test(phone)){
		return true;
	}else{
		return false;
	}
}

//检测账号格式
function checkUsername(username)
{
	var RegCellPwd = /^[0-9A-Za-z_]{6,32}$/;
	if(RegCellPwd.test(username)){
		return true;
	}else{
		return false;
	}
}
var max_count_time = 180
var count_time 	   = max_count_time ;
var idInt	       = null ;

//计算剩余时间
function count_surplus_time(){
	if(count_time === max_count_time){
    	$("#surplus_time_part").css({"display":""});
    	$("#send_verify_num").css({"display":"none"})
	}
	
	$("#surplus_time").html(count_time);
	
	
    if(count_time <= 0){
    	clearInterval(idInt);
    	count_time = max_count_time;
    	$("#surplus_time_part").css({"display":"none"});
    	$("#send_verify_num").css({"display":""});
    	
    }else{
    	count_time --;
    }
    
}





/*************   查找密码 开始   *******************************/
$(document).ready(function(){
    $('.reg_input').bind('keydown',enterEvent("#find_pwd_btn"));

    //查找密码
	$("#find_pwd_btn").click(function(){
		var url='index.php?ct=user&ac=check_phone_verify';
		var username=$("#username").val();
		var phone=$("#phone").val();
		var verify_num=$("#verify_num").val();

	    if(username==""){
			alert("账号不能为空");
			$("#username").focus();
			return;
		}else if(!checkUsername(username)){
		    alert("请输入正确的账号格式!");
		    $("#username").focus();
		    return;
		}else if(phone==""){
			alert("手机不能为空!");
			$("#phone").focus();
			return;
		}else if(!checkPhone(phone)){
		    alert("请输入正确的手机格式!");
		    $("#phone").focus();
		    return;
		} else if(verify_num==""){
		    alert("验证码不能为空!");
		    $("#verify_num").focus();
		    return;
		}


        $.ajax({
			type: "post",
		    url: url,
			data:"verify_num="+verify_num+"&phone="+phone+"&username="+username,
			error:function(d,t){
				alert("系统繁忙");
			},
			dataType:"json",
			success: function(data){
				//alert(data);
				if(data.error == 0){
				    //alert('yes');
					$("#myform").submit();
				}else{
				    alert(data.tips);
				    if(data.error==1){
				        $("#verify_num").focus();
				    }else if(data.error==2){
				    	$("#phone").focus();
				    }
					//alert("请输入正确的验证码!");
				}
		    }

		});

	});
	
	
	


	
	
	
	



	//发送验证码
	$("#send_verify_num").click(function(){

	    var phone=$("#phone").val();
		var username=$("#username").val();

	    if(username==""){
			alert("账号不能为空");
			$("#username").focus();
			return;
		}else if(!checkUsername(username)){
		    alert("请输入正确的账号格式!");
		    $("#username").focus();
		    return;
		}else if(phone==""){
		   alert("手机不能为空!");
		   $("#phone").focus();
		    return;
		} else if(!checkPhone(phone)){
		   alert("请输入正确的手机格式!");
		   $("#phone").focus();
		    return;
		}

		var url='index.php?ct=user&ac=send_phone_note';

        $.ajax({
			type: "post",
		    url: url,
			data:"phone="+phone+"&username="+username,
			error:function(d,t){
			     //alert(d);
			    //alert(t);
				alert("系统繁忙");
				//clearInterval(idInt);
				
			},
			dataType:"json",
			success: function(data){
				//alert(data)
			    if(data.error===0){
			    	//alert(data.tips);
			    	idInt = setInterval("count_surplus_time()",1000) ;
			    	alert(data.tips);
			    }else if(data.error===8){
			    	alert(data.tips);
			    	
			    }else{
			    	alert(data.tips);
			    }

				/*
				if(data.err === true){
					alert('成功发送');
				}else{
					alert('发送失败');
				}*/
		    }

		});
	});



});
/*************   查找密码 结束   *******************************/






/********************注册页面   开始************************/

//是否可以使账号
var isCanUseUserName = false;
//是否可以使用邮件
var isCanUseEmail    = false;

//验证码是否正确
var isVerifySuc 	 = false;

function submit_register()
{
	//alert(isCanUseUserName);
	var agreen = $("#agreen").attr("checked");
	var userName = $('#username').val();
	var pwd = $('#usrpwd').val();
	var second_pwd = $('#usrpwdc').val();
	var verifycode = $('#verifycode').val();
	//错误提示
	var tips='';
	//alert(isCanUseUserName);
	//alert(isCanUseEmail);
	//alert(agreen);
	//alert(pwd);
	//alert(second_pwd);
    if(userName == '' || userName.length == 0){
		tips="账号不能为空！";
		$('#username').focus();
	}else if(isCanUseUserName==false){
		//checkUserName();
		tips="账号有误";
		$('#username').focus();
	}else if (pwd == ''|| pwd.length == 0) {
		tips="密码不能为空！";
		ChkPwd();
		$('#usrpwd').focus();
	}else if(!(pwd.length>=6 && pwd.length<=16)){
		tips="密码的长度要在6-16之间！";
		ChkPwd();
		$('#usrpwd').focus();
	}else if (pwd != second_pwd) {
		tips="两次密码输入不一致";
		ChkPwdc();
		$('#usrpwdc').focus();
	}else if(!checkEmail()){
		//checkEmail();
		tips="邮件有误";
		$("#email").focus();
	}else if(verifycode == '' || verifycode.length == 0){
		tips="验证码不能为空！";
		$('#verifycode').focus();
	}else if(isVerifySuc === false){
		tips="验证码有误";
		$("#verifycode").focus();
	}
    
    
    if (agreen != true && tips=="") {
		tips="请阅读用户协议！";
		$("#agreen").focus();
	}
	//alert(tips);

	if(tips==''){
		//alert('可以提交了');
		var bl = confirm("请确认无误提交","Yesno");
		if(bl){
			$("#myform").submit();
		}
	}else{
		alert(tips);
	}
}

/*
function key_login()
{

	if (event.keyCode==13)   //回车键的键值为13
		submit_register();
}*/


function checkEmail(){
    var mail_str=$("#email").val();
	var website = $("#website").val();
    if(mail_str==""){
        ShowMsg("emailspan",0,"你的电子邮箱地址是什么？");
		jQuery("#emailspan").attr("class","error");
        return false;
    }

    if(mail_str.match(/^[-_.a-z0-9A-Z]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+([-_.][a-zA-Z0-9]+))+$/i)){    ///(^[a-zA-Z0-9_-]{1,})+@([a-zA-Z0-9]{1,})+\.([a-zA-Z]{2,})$/g)){
        // alert("yes");
        //ShowMsg("emailspan",1,"可以通过此邮箱修改密码。");
		var url = website + "/class/user_ajax.php?do=email";
        $.ajax({
				  type: "post",
				  url: url,
				  data:"email=" + mail_str,
				  dataType:"json",
				  error:function(d,t){
        		  	ShowMsg("emailspan",0,"网络错误");
        		  	isCanUseEmail=false;
				  },
				  success: function(data){
					  	if(data == 1){
					  		isCanUseEmail = true;
					  		$('#emailspan').hide();
					  	}else if (data == 2){
					  		$("#emailspan").html("该邮件已经被注册");
					  		isCanUseEmail = false;
					  	}
				  }
		});

		isCanUseEmail = true;
		$('#emailspan').hide();
		ShowMsg("emailspan",1,"邮件确认无误。");
		jQuery("#emailspan").attr("class","ok");
    }else{
    	isCanUseEmail=false;
		jQuery('#emailspan').show();
		ShowMsg("emailspan", 0, "不是有效的电子邮箱。");
		jQuery("#emailspan").attr("class", "error");
    	//ShowMsg("emailspan",0,"不像是有效的电子邮箱。");
    }
    return isCanUseEmail;
}


function checkUserName() {
		var username = $('#username').val();
		var website = $("#website").val();
		if(checkUsername(username)) {
			var url = website + "/class/user_ajax.php?do=username";
			$.ajax({
				  type: "post",
				  url: url,
				  data:"username="+username,
				  error:function(d,t){
				  	isCanUseUserName=false;
				  	jQuery('#unspan').show();
				  	//$("#unspan").html("网络错误");
				  	ShowMsg("unspan", 0, "网络错误");
				  	jQuery("#unspan").attr("class", "error");
				  },
				  dataType:"json",
				  success: function(data){
				  	    //alert(data);
					  	if(data == 1){
					  		isCanUseUserName = true;
					  		$('#unspan').hide();
					  		ShowMsg("unspan",1,"账号确认无误。");
					  		jQuery("#unspan").attr("class","ok");

					  	}else if (data == 2){
					  		//$("#unspan").html("该账号已经被注册");
							jQuery('#unspan').show();
							ShowMsg("unspan", 0, "该账号已经被注册");
							jQuery("#unspan").attr("class", "error");
					  		isCanUseUserName = false;
					  	}
				  }});
		} else {
			jQuery('#unspan').show();
			ShowMsg("unspan", 0, "帐号由6至32位字母、数字或_组成。");
			jQuery("#unspan").attr("class", "error");
			isCanUseUserName=false;
		    return false;
		}
	}


function checkVerifycode(){
	var verifycode = $("#verifycode").val();
	var website = $("#website").val();
	if(verifycode == '' || verifycode.length == 0){
		isVerifySuc=false;
		jQuery('#codespan').show();
		ShowMsg("codespan", 0, "验证码不可以为空");
		jQuery("#codespan").attr("class", "error");
	}else{
		var url = website + "/class/user_ajax.php?do=code";
		$.ajax({
			  type: "post",
			  url: url,
			  data:"code="+verifycode,
			  error:function(d,t){
				isVerifySuc=false;
			  	jQuery('#codespan').show();
			  	//$("#unspan").html("网络错误");
			  	ShowMsg("codespan", 0, "网络错误");
			  	jQuery("#codespan").attr("class", "error");
			  },
			  dataType:"json",
			  success: function(data){
				     //alert(data);

				  	if(data == 1){
				  		isVerifySuc = true;
				  		$('#codespan').hide();
				  		ShowMsg("codespan",1,"正确");
				  		jQuery("#codespan").attr("class","ok");
				  	}else if (data == 2){
				  		//$("#unspan").html("该账号已经被注册");
						jQuery('#codespan').show();
						ShowMsg("codespan", 0, "验证码错误");
						jQuery("#codespan").attr("class", "error");
						isVerifySuc = false;
				  	}
			  }});
	}
}






$(document).ready(function(){
	$("#register_btn").bind('click',submit_register);
	

	$("#verifycode_change").click(function(){
		isVerifySuc = false;
		$("#verifycode_img").attr("src","index.php?ct=user&ac=gen_verifycode&t=" +Math.random());
	});
	$("#verifycode_change").click();
	
	
})



/********************注册页面   结束************************/

//注册页面  和   登陆页面    开始
$(document).ready(function(){
	$("#login_btn").click(function(){
		var tips="";
		var usernameObj=$("#login_username");
		var passwordObj=$("#login_password");
		var username=usernameObj.val();
		if(usernameObj.val()=="" && tips==""){
			usernameObj.focus();
			tips="账号不能为空";
		}


		if(!checkUsername(username) && tips==""){  
			usernameObj.focus();
			tips="账号必须在6-32位之间，只能包含字母、数字、下划线!";
		}

		if(passwordObj.val()=="" && tips==""){
			passwordObj.focus();
			tips="密码不能为空";
		}

		if(tips==""){
			$("#login_form").submit();
		}else{
			alert(tips);
		}
	});

	$('.login_input').bind('keydown',enterEvent("#login_btn"));
	$('.new_txt').bind('keydown',enterEvent("#register_btn"));
	//$('.login_input').bind('keydown',enterEvent("#login_form"));


})
//注册页面  和   登陆页面    结束





function  enterEvent(form_id_str,type){
    //alert(1111);
    if(type=='function'){
   		return function(){
        	//alert(1111);
    		var e=e || event;
			var keycode=e.which || e.keyCode;
			if(keycode==13){
				form_id_str();
			}
   		 }

    }else{
   		return function(){
        	//alert(1111);
        	var alt=$(this).attr('alt');
        	if(alt=="disableEnter") return;
        	//alert($(this).attr('title'));
    		var e=e || event;
			var keycode=e.which || e.keyCode;
			if(keycode==13){
				$(form_id_str).click();
			}
   		 }

    }
}





