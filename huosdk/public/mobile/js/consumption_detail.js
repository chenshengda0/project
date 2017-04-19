$(".loading_more>.btn_top").click(function() {
	$("html,body").animate({
		scrollTop : 0
	}, 500);
});

$("nav").css({
			"position":"fixed",
			"top":"0px",
			"width":"100%",
			"z-index":"9999",
			"height":"40px"
		})
		$("body").css({
			"padding-top":"40px"
		})

$(function() {
		var page = $('#page').val();
		if(page==-1){
			$("#ajax_idx_more").html("已加载完");
			return;
		}

	var unlock = true;
	

	
	
	function sendsucc(result){
	    if ('success'==result.state){
	    	var obj = $("#ajax_idx_more");
    		var top = $(document).scrollTop();
            var html0 = $(obj).html();
            console.log(result.data);
            var ajaxdata=result.data;
//            var ajaxdata=JSON.parse(result.data);
            xlen = ajaxdata.length;
            var currencyname = $('#currency').val();
            for (var i=0;i<xlen;i++){
                $('#ptb_data').html($("#ptb_data").html()+"<ul class='item'><li><b>充值金额：</b><span><i>"+ajaxdata[i].amount+"</i>元</span></li><li><b>"+currencyname+"数量：</b><span><i>"+ajaxdata[i].ptbcnt+"</i></span></li><li><b>充值状态：</b><span><i>"+ajaxdata[i].status+"</i></span></li><li><b>订单号：</b><span>"+ajaxdata[i].orderid+"</span></li> <li><b>充值时间：</b><span>"+ajaxdata[i].createtime+"</span></li> </ul>");
            }
        	
			$(obj).attr("rel", result.page);
			$(document).scrollTop(top);

			
			$(obj).html(html0);
			$(".main_model_box").css("height",$("html").css("height"));
			if (result.page != -1) {
				unlock = true;
			//	$(obj).html(html0);
			//	$(".main_model_box").css("height",$("html").css("height"));
			} else {
				$(obj).html("已加载完");
			}
	    }else{
	    	showMsg(".error_box", result.info);
	    }
	}
	$(document).scroll(function() {
		$("nav").css({
			"position":"fixed",
			"top":"0px",
			"width":"100%",
			"z-index":"9999",
			"height":"40px"
		})
		$("body").css({
			"padding-top":"40px"
		})
		var page = $('#page').val();
		if(page==-1){
			$("#ajax_idx_more").html("已加载完");
			return;
		}
		var winH = $(window).height();
		var scrH = $(window).scrollTop();
		var htmH = $(document).height() - 100;
		if (winH + scrH >= htmH) {
			var obj = $("#ajax_idx_more");
			if ($(obj).length <= 0)
				return;
		ajaxidxmore(obj);
		}
	});
	function ajaxidxmore(obj) {
		if (!unlock)
			return;

		var html0 = $(obj).html();
		$(obj).html("加载中...");

		var page = $(obj).attr("rel");

		if (!isNaN(page)) {
			unlock = false;
			var ajaxurl = $('#ajaxurl').val();
			var status = $('#status').val();
			
			var formdata = {
				"status" : status,
				"page" : page
			};			
			sendData(ajaxurl, formdata, sendsucc,'','GET');
		}
	}

})

	