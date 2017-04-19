$(".getCode").click(f2);
function f2() {
	var sendurl = $("#sendsms").val();
	var phone = $("#phone").val();
	if (phone.trim() !== "") {
		if (checkEmail(phone)) {
            var form_data ={
				"email" : phone,
                "type" : 2
			};
            sendData(sendurl,form_data, sendsucc);
		} else {
			showMsg(".error_box", "邮箱有误,请重新输入")
		}
	} else {
		showMsg(".error_box", "邮箱不能为空")
	}
}
//短信发送信息显示
function sendsucc(result){
    if ('success'==result.state){
    	showMsg(".error_box", result.info, 'green');
    	_getCode(".getCode", 300, f2, 1000, ".error_box", "#fff", "#000");
    }else{
    	showMsg(".error_box", result.info);
    }
}
//短信验证信息显示
function verifysucc(result){
    if ('success'==result.state){
        window.location.href=result.url;
    }else{
    	showMsg(".error_box", result.info);
    }
}

$(".confim_change>button").click(function() {
        var phone = $("#phone").val();
        var code = $("#code").val();
        var $url = $("#bindurl").val();
        
        var pwd = $("#pwd").val();
        if (pwd == ''){
            showMsg(".error_box", "请输入密码");
            return false;
        }
        if (checkEmail(phone)) {
            if (code.trim() !== "") {            
                sendData($url, {
                    "email" : phone,
                    "code" : code,
                    "pwd" : pwd,
                }, verifysucc)
            } else {
                showMsg(".error_box", "验证码不能为空..")
            }
        } else {
            showMsg(".error_box", "邮箱有误,请重新输入..")
        }
});