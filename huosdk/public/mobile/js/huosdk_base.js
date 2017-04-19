function sendData(url, data, succ, err, type, dataType, conentType) {
	if (!type) {
		type = "POST"
	}
	if (!err) {
		err = ""
	}
	if (!url) {
		throw new Error("url is not find...");
	}
	if (!dataType) {
		dataType = "JSON"
	}
	if (!conentType) {
		conentType = "application/x-www-form-urlencoded"
	}
	$.ajax({
		type : type,
		dataType : dataType,
		url : url, // 目标地址
		data : data,
		success : succ,
		error : err
	});
}
// 获取短信验证码
function _getCode(el, time, back, interval, msgBox, bgColor, color) {
	var code = $(el);
	if (!time) {
		time = 120
	}
	if (!interval) {
		interval = 1000
	}
	if (!bgColor) {
		bgColor = "#aaa"
	}
	if (!color) {
		color = "#fff"
	}
	showMsg(msgBox, "验证码已发送", "green");
	var time1 = time;
	var codeback = code.css("background-color");
	var codeColor = code.css("color");
	code.css("background-color", bgColor);
	code.css("color", color);
	code.unbind("click", back);
	time1--;
	code.html("剩余" + time1 + "s");
	code.addClass("msgs1");
	var t = setInterval(function() {
		time1--;
		code.html("剩余" + time1 + "s");
		if (time1 == 0) {
			clearInterval(t);
			code.html("重新获取");
			code.removeClass("msgs1");
			code.css("background-color", codeback);
			code.css("color", codeColor);
			code.bind("click", back);
			//showMsg(msgBox, "请尽快完成验证码，五分钟内有效..", "red");
		}
	}, interval)
}
function showMsg(el, text, color) {
	if (!color) {
		color = "red"
	}
	$(el).css("display", "block");
	$(el).html(text);
	$(el).css("color", color);
}
// 验证手机
function checkMobile(mobile) {
	if (mobile
			.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/)) {
		return true;
	} else {
		return false;
	}
}

function checkEmail(email) {
	if (email.match(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/)) {
		return true;
	} else {
		return false;
	}
}
function checkUser(username) {
	var regcellpwd = /^[0-9A-Za-z]{6,16}$/g;
	return regcellpwd.test(username);
}

function changePwd(newPwd, confim) {
	var newPwd = $(newPwd).val();
	var confim = $(confim).val();
	if (newPwd.trim() !== confim.trim()) {
		return false;
	}
	return true;
}