//检测账号格式
function checkUsername(username)
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


/********************注册页面   开始************************/

//是否可以使账号
var isCanUseUserName = false;
//是否可以使用邮件
var isCanUseEmail    = false;
//是否可以使用昵称
var isCanNickname    = false;

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
		return false;
	}else if(isCanUseUserName==false){
		//checkUserName();
		tips="账号有误";
		$('#username').focus();
		return false;
	}
	if (pwd == ''|| pwd.length == 0) {
		tips="密码不能为空！";
		ChkPwd();
		$('#usrpwd').focus();
		return false;
	}
	if(!(pwd.length>=6 && pwd.length<=16)){
		tips="密码的长度要在6-16之间！";
		ChkPwd();
		$('#usrpwd').focus();
		return false;
	}
	if (pwd != second_pwd) {
		tips="两次密码输入不一致";
		ChkPwdc();
		$('#usrpwdc').focus();
		return false;
	}
	if(!checkEmail()){
		//checkEmail();
		tips="邮件有误";
		$("#email").focus();
		return false;
	}
	if(!checkNickname()){
		tips="昵称有误";
		$("#nickname").focus();
		return false;
	}
	if(verifycode == '' || verifycode.length == 0){
		tips="验证码不能为空！";
		$('#verifycode').focus();
		return false;
	}
	if(isVerifySuc === false){
		tips="验证码有误";
		$("#verifycode").focus();
		return false;
	}
    
    
    if (agreen != true && tips=="") {
		tips="请阅读用户协议！";
		$("#agreen").focus();
	}

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

//论坛登录
function submit_luntan()
{
	//错误提示
	var tips='';
	var a= document.getElementById('email');
	var b= document.getElementById('nickname');
	if(b != null){
		if(!checkNickname()){
			tips="昵称有误";
			$("#nickname").focus();
		}
	}
	if(a != null){
		if(!checkEmail()){
			tips="邮箱有误";
			$("#email").focus();
		}
	}
	if(tips==''){
			$("#nickform").submit();
	}else{
		return false;
	}
}

/*
function key_login()
{

	if (event.keyCode==13)   //回车键的键值为13
		submit_register();
}*/


function checkEmail(){
	var a= document.getElementById('email');
	if(a != null){
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
					  async: false,
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
		}else{
			isCanUseEmail=false;
			jQuery('#emailspan').show();
			ShowMsg("emailspan", 0, "不是有效的电子邮箱。");
			jQuery("#emailspan").attr("class", "error");
			//ShowMsg("emailspan",0,"不像是有效的电子邮箱。");
		}
		isCanNickname = true;
		$('#emailspan').hide();
		ShowMsg("emailspan",1,"邮箱确认无误。");
		jQuery("#emailspan").attr("class","ok");
		return isCanUseEmail;
	}else{
		isCanUseEmail=true;
		return isCanUseEmail;
	}
}

//昵称检查
function checkNickname(){
	var a= document.getElementById('nickname');
	
	if(a != null){
		var nickname=$("#nickname").val();
		var website = $("#website").val();
		if(nickname==""){
			ShowMsg("nicknamespan",0,"请填写昵称！");
			jQuery("#nicknamespan").attr("class","error");
			return false;
		}

		if(nickname.length >=3 && nickname.length <= 15){  

			var url = website + "/class/user_ajax.php?do=nickname";
			$.ajax({
					  type: "post",
					  url: url,
					  data:"nickname=" + nickname,
					  dataType:"json",
					  error:function(d,t){
						ShowMsg("nicknamespan",0,"网络错误");
						isCanNickname=false;
					  },
					  success: function(data){
							if(data == 1){
								isCanNickname = true;
								$('#nicknamespan').hide();
								ShowMsg("nicknamespan",1,"昵称确认无误。");
								jQuery("#nicknamespan").attr("class","ok");
							}else if (data == 2){
								jQuery('#nicknamespan').show();
								ShowMsg("nicknamespan", 0, "该昵称已经被使用");
								jQuery("#nicknamespan").attr("class", "error");
								isCanNickname = false;
								return false;
							}
					  }
			});

		}else{
			isCanNickname=false;
			jQuery('#nicknamespan').show();
			ShowMsg("nicknamespan", 0, "昵称由3~15个字符组成。");
			jQuery("#nicknamespan").attr("class", "error");
			return false;
		}
		return isCanNickname;
	}else{
		isCanNickname=true;
		return isCanNickname;
	}
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
	$("#login_ltn").bind('click',submit_luntan);

	$("#verifycode_change").click(function(){
		isVerifySuc = false;
		$("#verifycode_img").attr("src","index.php?ct=user&ac=gen_verifycode&t=" +Math.random());
	});
	//$("#verifycode_change").click();
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





