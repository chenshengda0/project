$(function(){
	//检测用户名
	var name = 	$("#username").val();
	if (name) {
		$("#change_user").html("账号填写正确");
		$("#change_user").css("color","#6ca200");
	}
	$("#username").blur(function(){
		var username = $("#username").val();
		checkuser(username);	
	});
	
	$("#other_money").val("");
	//选择金额
	$("#main_money_list a").click(function(){
		$("#main_money_list a").removeClass("on");
		$("#money_9999").removeClass('on');
		$(this).addClass("on");
		$("#other_money").val("");
		var amount = $(this).attr("val");
		$("#money").val(amount);
		$("#input_amount").val(amount);
		var ttbarr = getTTB(amount);
		$("#money_convert").html(ttbarr[0]);
		$("#give").html(ttbarr[1]);
	});
	//选择其他金额
	$("#money_9999,#other_money").click(function(){
		$("#main_money_list a").removeClass("on");
		$("#money_9999").addClass("on");	
		$("#money_convert").html(0);
		$("#give").html(0);
	});
	
	$("#other_money").bind("keyup",function(){
		var amount = $(this).val();
		if (isNaN(amount)) {
			amount = 100;
			$("#other_money").val(amount);
		}
  		var strP = /^\d+$/; //正整数
  		if(!strP.test(amount)) {
			var tmp = Math.ceil(amount);
			$("#other_money").val(tmp);
			return false;
		}
		$("#money").attr("value",amount);//填充内容 
		$("#input_amount").val(amount);
		var ttbarr = getTTB(amount);
		$("#money_convert").html(ttbarr[0]);
		$("#give").html(ttbarr[1]);	
	});
	
	
	//选择银行
	$(".main_bank a").click(function(){
		$("#main_bank a").removeClass("on");
		$(this).addClass("on");
		$("#selectbank").val(1);
		var zhifucode = $(this).attr("val");
		$("#zhifucode").val(zhifucode);
	});
	
	$("#confirm_submit").click(function(){
		$("#light").hide();
		$("#payafter").show();
		
		//提交
		$("#alertform").submit();
	});
	
	//页面中对应必玩币数量的初始化
	var money = $("#money").val();
	var money_convert = getTTB(money);
	$("#give").html(money_convert[1]);	
	$("#money_convert").html(0);
	$("#rate_desc1").hide();
	$("#rate_desc2").hide();
	
	$("#apay").click(rechargeSubmitCheck);

	//查询必玩币返利活动是否是在有效时间内
	/*var checktime = checkTTBtime();
	if (checktime == 1) {
		$("#zengsong").show();	
		$("#cz_ptNotice").show();	
	} else {
		$("#zengsong").hide();	
		$("#cz_ptNotice").hide();		
	}*/
});

//查询必玩币返利活动是否是在有效时间内
function checkTTBtime() {
	var check=0;
	$.ajax({
		type:'post',
		async : false,
		url: 'user_ajax.php',
		data: 'action=activetime',
		success:function(data) {
			check = data;
		}
	});	
	return check;
}
/*检查充值帐号*/
function checkuser(username)
{
	var rs = true;
	if(username)
	{
		$.ajax({
			type:'post',
			async : false,
			url: 'user_ajax.php',
            data: 'action=name&username='+username,
			success:function(data){
				if (data == 1) {
					$("#change_user").html("账号填写正确");
					$("#change_user").css("color","#6ca200");
					rs = true;;
				}else if (data == 2) {
					$("#change_user").html("账号填写错误");
					$("#change_user").css("color","#F00");
					$("#username").focus();
					rs = false;	
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){
				alert('检测帐号失败，网络异常，请重试！');
				$("#username").focus();
				rs = false;	
			}
		});	
	} else {
		rs = false;	
	}
	return rs;
}

//算法
function getTTB(amount){
	var moneyrate = $("#moneyrate").val();
	amount = amount * moneyrate;
	var ttbarr = new Array();
	var ttb = 0;
	var give = 0; //赠送数量
	var give_str = '';
	
	var checktime = checkTTBtime();
    if (checktime == 1) {
		$.ajax({
			type:'post',
			async : false,
			url: 'user_ajax.php',
			data: 'action=ttb&money='+amount,
			success:function(data) {
				give_str = data * 100;
				give = Math.round(amount * data);	
			}
		});
		if(give_str > 0){
			$("#rate_text").html(give_str+"%");
	        $("#rate_desc1").show();	
		    $("#rate_desc2").show();
		}

	} else {
		$("#rate_desc1").hide();
		$("#rate_desc2").hide();
	}
	
	ttb = amount * 10 + give;
	ttbarr[0] = ttb;
	ttbarr[1] = give;
	return ttbarr;
}

function ajaxgetrate(website,amount) {
	var give = 0;
	$.ajax({
		type:'post',
		async : false,
		url: website + '/include/user_ajax.php?action=ttb&money='+amount,
		success:function(data) {
			give = Math.round(amount * data);	
		}
	});
	return give;
}

function check_sub() {
	//检测用户名
	var uflag = checkuser($("#username").val());
	if (!uflag) {
		alert("账号填写错误");
		$("#username").focus();
		return false;	
	}
	//检测选择金额
	var ttb = $("#money_convert").html();
	if (ttb == 0) {
		alert("请选择充值金额");
		$("#other_money").focus();
		return false;	
	}
	
	/*
	//检测选择银行
	if ($("#selectbank").val() == 0) {
		alert("请选择银行");
		return false;
	}
	*/
	$("#confirm_pay_type_name").html($("#paytype").val());
	$("#confirm_order_id").html($("#orderid").val());
	$("#confirm_username").html($("#username").val());
	$("#confirm_money").html($("#money").val());
	$("#confirm_coin").html($("#money_convert").html());
	
	$("#com_username").val($("#username").val());
	$("#com_orderid").val($("#orderid").val());
	$("#com_amount").val($("#money").val());
	$("#com_ttb").val($("#money_convert").html());
	$("#com_zhifucode").val($("#zhifucode").val());
	$("#com_paytypeid").val($("#paytypeid").val());
	
	var url = $("#url").val();
	$("#alertform").attr("action",url);
	
	$("#light").show();
	$("#fade").show();

	return true;
}

function fullbghide()
{
	$("#fade").hide();
}

function close_floatdiv(o)
{
	$(o).hide();
	fullbghide();
}

/**
 * 校验表单提交
 */
function rechargeSubmitCheck() {
    var amount = $("#money").val();

    // 必须为金额类型
    if (!isAmount(amount)) {
       // openErrorTip('请输入1-9999999以内的整数。');
        return false;
    }
    // 最大值和最小值(1-9999999)
    if (!isLarge(1, 9999999, amount)) {
        return false;
    }
    $('#form').submit();
	return true;
}

/**
 * 判断是否为数字
 */
function isNumber(text) {
    return text.match("^[0-9]+$");
}

function isAmount(text) {
    return text.match("^[1-9][0-9]*$");
}

/**
 * 判断长度
 */
function isLength(min, max, text) {
    if (min <= text.length && text.length <= max) {
        return true;
    } else {
        return false;
    }
}

/**
 * 金额的大小
 */
function isLarge(min, max, text) {
    var amount = Number(text);
    if (min <= amount && max >= amount) {
        return true;
    }
    return false;
}
