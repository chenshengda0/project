$(".getCode").click(f2);
function f2() {
	var sendurl = $("#sendsms").val();
	var phone = $("#phone").val();
	if (phone.trim() !== "") {
		if (checkMobile(phone)) {
            var form_data ={
				"phone" : phone,
                "type" : 2
			};
            sendData(sendurl,form_data, sendsucc);
		} else {
			showMsg(".error_box", "手机号有误,请重新输入")
		}
	} else {
		showMsg(".error_box", "手机号不能为空")
	}
}
//短信发送信息显示
function sendsucc(result){
    if ('success'==result.state){
    	showMsg(".error_box", result.info, 'green');
    	_getCode(".getCode", 120, f2, 1000, ".error_msg", "#fff", "#000");
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
        
        if (checkMobile(phone)) {
            if (code.trim() !== "") {            
                sendData($url, {
                    "phone" : phone,
                    "code" : code,
                    "pwd" : pwd
                }, verifysucc)
            } else {
                showMsg(".error_box", "验证码不能为空..")
            }
        } else {
            showMsg(".error_box", "手机号有误,请重新输入..")
        }
});