function f2() {
	var sendurl = $("#sendsms").val();

	var form_data = {
		"type" : 1
	};
	sendData(sendurl, form_data, sendsucc);
}
// 短信发送信息显示
function sendsucc(result) {
	if ('success' == result.state) {
		showMsg(".error_box", result.info, 'green');
        $(".confim_change>button").attr("disabled",false);
		_getCode("#getCode", 300, f2, 1000, ".error_msg", "#fff", "#000");
	} else {
		showMsg(".error_box", result.info);
	}
}

// 短信验证信息显示
function verifysucc(result) {
	if ('success' == result.state) {
		window.location.href = result.url;
	} else {
		showMsg(".error_box", result.info);
	}
}

/** ****获取验证码按钮****** */
$("#getCode").click(f2);

/** ********调教信息解除绑定手机******** */
$(".confim_change>button").click(function() {
    if (!$(this).attr("disabled")) {
        var code = $("#code").val();
        var url = $("#bindurl").val();
            if (code!== "") {
                sendData(url, {
                    "code" : code,
                }, verifysucc)
            } else {
                showMsg(".error_box", "验证码不能为空")
            }
    }
})
