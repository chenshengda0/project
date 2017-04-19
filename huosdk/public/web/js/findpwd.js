

//检测手机格式
function checkPhone(phone){
	var phone_match=/^[1][3458][0-9]{9}$/;
	if(phone_match.test(phone)){
		return true;
	}else{
		return false;
	}
}

//检测账号格式 cuname 是checkusername的缩写
function cuname(username)
{
	var RegCellPwd = /^[0-9A-Za-z_]{6,32}$/;
	if(username == 'admin'){
		RegCellPwd = /^[0-9A-Za-z_]{5,32}$/;
	}
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

function submit_upwd()
{

	var pwd = $('#usrpwd').val();
	var second_pwd = $('#usrpwdc').val();
	//错误提示
	var tips='';
    if (pwd == ''|| pwd.length == 0) {
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
	}
  
	if(tips==''){
		//alert('可以提交了');
		//var bl = confirm("请确认无误提交","Yesno");
		//if(bl){
			$("#myform").submit();
		//}
	}else{
		return false;
	}
}


function checkUserName() {
		var username = $('#username').val();
		var website = $("#website").val();
		alert(99999);
		if(cuname(username)) {
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
					  		$('#unspan').hide();
					  		ShowMsg("unspan", 0, "该账号没有注册");
							jQuery("#unspan").attr("class", "error");
					  		isCanUseUserName = false;
					  		
					  	}else if (data == 2){
					  		isCanUseUserName = true;
					  		//$("#unspan").html("该账号已经被注册");
							jQuery('#unspan').show();
							ShowMsg("unspan",1,"账号确认无误。");
					  		jQuery("#unspan").attr("class","ok");
							
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

$(document).ready(function(){
	$("#upwd_btn").bind('click',submit_upwd);
	

	//$("#verifycode_change").click(function(){
		//isVerifySuc = false;
		//$("#verifycode_img").attr("src","index.php?ct=user&ac=gen_verifycode&t=" +Math.random());
	//});
	//$("#verifycode_change").click();
	
	
})



/********************注册页面   结束************************/

//注册页面  和   登陆页面    开始
$(document).ready(function(){
	//填写账号界面

	$("#one_btn").click(function(){
		var tips="";
		var usernameObj=$("#username");
		var username=usernameObj.val();
		if(usernameObj.val()=="" && tips==""){
			usernameObj.focus();
			tips="账号不能为空";
		}

		if(!checkUsername(username) && tips==""){  
			usernameObj.focus();
			tips="账号必须在6-32位之间，只能包含字母、数字、下划线!";
		}
	
		if(isCanUseUserName == false){
			usernameObj.focus();
			tips="账号有误!";
		}
		if(tips==""){
			$("#one_btn").submit();
		}else{
			//alert(tips);
			return false;
		}
	});

	$('.text').bind('keydown',enterEvent("#one_btn"));
	//$('.new_txt').bind('keydown',enterEvent("#register_btn"));
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


//找回密码第一步，检查用户名是否存在
$(document).ready(function(){
	if(!window.name){
		window.name = 'test';
		window.location.reload();
	 }

	$("#findpwd_one").click(function(){
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
					$("#myform").submit();
				}else{
					showmsg(result.msg);
				}
			}	
		});
		return false;
	});
});


//错误显示返回的信息
function showmsg(msg){
	$("#message").html('<li class="li_12"><span>'+msg+'</span></li>');
}

//正确时显示返回的信息
function showmsgsu(msg){
	$("#message").html('<li class="li_12su"><span>'+msg+'</span></li>');
}




