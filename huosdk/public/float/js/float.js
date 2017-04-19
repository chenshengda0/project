
function sendDate(url,data,succ,err,type,dataType,conentType){
    if(!type){type="POST"}
    if(!url){throw new Error("url is not find...");}
    if(!dataType){dataType="JSON"}
    if(!conentType){conentType="application/x-www-form-urlencoded"}
    $.ajax({
        type:type,
        dataType:dataType,
        url: url, //目标地址
        data:data,
        error:err,
        success:succ
    });
}

function succ(result){
	if (result.sub){
		$("#codeform").submit();
	}else{
		showmsg(result.msg);
	}
}

function checkMobile(mobile){
	if(mobile.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/)){
		return true;
	}else{
		return false;
	}
}

function checkEmail(email){
	if(email.match(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/)){
		return true;
	}else{
		return false;
	}
}
function checkUser(username){
	var regcellpwd = /^[0-9A-Za-z]{6,16}$/g;
	return regcellpwd.test(username);
}

function showmsg(msg)
{
	$("#msg_box").css("display","block");
	$("#msg_box").html('<div class="err1"><span>'+msg+'</span></div>');
}

function err(XMLHttpRequest, textStatus, errorThrown){
	showmsg('读取超时，网络错误');
}

//获取短信验证码
function _getCode(url,data,getcode,time,back,interval){
    sendDate(url,data,succ,err,"POST","JSON");
    var code=$(getcode);
    if(!time){time=30}
    if(!interval){interval=1000}
    var time1=time;
    var codeback=code.css("background-color");
    code.css("background-color","#aaa");
    code.unbind("click",back);
    time1--;
    code.html(time1+"秒");
    code.addClass("msgs1");
    var t=setInterval(function  () {
        time1--;
        code.html(time1+"秒");
        if (time1==0) {
            clearInterval(t);
            code.html("重新获取");
            code.removeClass("msgs1");
            code.css("background-color",codeback);
            code.bind("click",back);
        }else if(time1<time&&time1>0){
            
        }
    },interval)
}
