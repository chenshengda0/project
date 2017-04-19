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
		$("#money").val(amount);
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
	
	//选择更多银行
	$("#more_bank").click(function(){
		$(".hide").show();
		$("#main_more_bank").hide();
	});
	
	
	$("#confirm_submit").click(function(){
		$("#light").hide();
		$("#payafter").show();
		
		//提交
		$("#alertform").submit();
	});
	
	//页面中对应平台币数量的初始化
	var money = $("#money").val();
	var money_convert = getTTB(money);
	$("#give").html(money_convert[1]);	
	$("#money_convert").html(money_convert[0]);
	
	//查询平台币返利活动是否是在有效时间内
	var checktime = checkTTBtime();
	if (checktime == 1) {
		$("#zengsong").show();	
		$("#cz_ptNotice").show();	
	} else {
		$("#zengsong").hide();	
		$("#cz_ptNotice").hide();		
	}
});

//查询平台币返利活动是否是在有效时间内
function checkTTBtime() {
	var check=0;
	var website = $("#website").val();
	$.ajax({
		type:'get',
		async : false,
		url: website + '?action=activetime',
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
		var userPay = $("#userPay").val();
		var website = $("#website").val();
		$.ajax({
			type:'get',
			async : false,
			url: userPay+'?action=name&username='+username,
			success:function(data){
				if (data == 1) {
					$("#change_user").html("账号填写正确");
					$("#change_user").css("color","#6ca200");
					rs = true;
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

//平台币的算法
function getTTB(amount){
	var moneyrate = $("#moneyrate").val();
	amount = amount * moneyrate;
	var ttbarr = new Array();
	var ttb = 0;
	var give = 0; //赠送数量
	
	/*
	if (amount<100) {
		give = 0;	
	} else if (amount>=100 && amount<300) {
		give = Math.round(amount * 0.1);	
	} else if (amount>=300 && amount<500){
		give = Math.round(amount * 0.2);	
	} else if (amount>=500 && amount<1000){
		give = Math.round(amount * 0.3);	
	} else if (amount>=1000 && amount<2000){
		give = Math.round(amount * 0.4);	
	} else if (amount>=2000 && amount<3000){
		give = Math.round(amount * 0.5);	
	} else if (amount>=3000 && amount<5000){
		give = Math.round(amount * 0.6);	
	} else if (amount>=5000 && amount<10000){
		give = Math.round(amount * 0.7);
	} else if (amount>=10000 && amount<20000){
		give = Math.round(amount * 0.8);	
	} else if (amount>=20000 && amount<50000){
		give = Math.round(amount * 0.9);	
	}else if (amount >=50000) {
		give = amount * 1;
	}
	*/
	//var website = $("#website").val();
	var userPay = $("#userPay").val();
	$.ajax({
		type:'get',
		async : false,
		url: userPay + '?action=ttb&money='+amount,
		success:function(data) {
			give = Math.round(data);
		}
	});
	//alert(give);
	ttb = amount * 10 + give;
	ttbarr[0] = ttb;
	ttbarr[1] = give;
	return ttbarr;
}

function ajaxgetrate(website,amount) {
	var give = 0;
	$.ajax({
		type:'get',
		async : false,
		url: website + '?action=ttb&money='+amount,
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
